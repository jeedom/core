"""Filesystem operations for the REST editor, under the web server account."""

import errno
import fcntl
import hashlib
import json
import os
import re
import secrets
import stat
import sys
from contextlib import ExitStack, contextmanager
from pathlib import Path


CORE = Path(__file__).resolve().parents[1]
ROOT_ID = "jeedom"
MAX_FILE_BYTES = 1048576
MAX_TRANSFER_BYTES = 10 * 1048576
MAX_DIRECTORY_ENTRIES = 1000
DIR_FLAGS = os.O_RDONLY | os.O_DIRECTORY | os.O_NOFOLLOW
TEXT_CONTROLS = re.compile(rb"[\x00-\x08\x0b\x0c\x0e-\x1f\x7f]")
TEMPORARY_PREFIX = ".rest-"
NOT_FOUND = {errno.ENOENT, errno.ENOTDIR, errno.ELOOP}
ACCESS_ACL = "system.posix_acl_access"
NO_ACCESS_ACL = {errno.ENODATA, errno.ENOTSUP}


class FileError(Exception):
    def __init__(self, status, code, message):
        self.error = {"status": status, "code": code, "message": message}


def valid_part(part):
    if part in {"", ".", ".."} or part.startswith(TEMPORARY_PREFIX) or any(char in part for char in "/\\:"):
        return False
    try:
        name = part.encode("utf-8")
    except UnicodeEncodeError:
        return False
    return len(name) <= 255 and not any(byte < 32 or byte == 127 for byte in name)


@contextmanager
def open_directory(parts):
    with ExitStack() as handles:
        fd = os.open(CORE, DIR_FLAGS)
        handles.callback(os.close, fd)
        for part in parts:
            fd = os.open(part, DIR_FLAGS, dir_fd=fd)
            handles.callback(os.close, fd)
        yield fd


def roots():
    items = []
    try:
        with open_directory(()) as fd:
            if os.access(".", os.R_OK | os.X_OK, dir_fd=fd):
                writable = os.access(".", os.W_OK | os.X_OK, dir_fd=fd)
                items.append({"id": ROOT_ID, "path": ".", "readable": True, "writable": writable})
    except OSError as error:
        if error.errno not in NOT_FOUND | {errno.EACCES, errno.EPERM}:
            raise
    return {"items": items, "total": len(items)}


def file_entry(name, path, kind, writable):
    return {"name": name, "path": path, "type": kind, "writable": writable}


def entries(fd, parts):
    items = []
    writable_dir = os.access(".", os.W_OK | os.X_OK, dir_fd=fd)
    with os.scandir(fd) as directory:
        for count, entry in enumerate(directory, 1):
            if count > MAX_DIRECTORY_ENTRIES:
                raise FileError(413, "directory_too_large", "Le répertoire dépasse 1 000 entrées.")
            name = entry.name
            child_parts = parts + (name,)
            if not valid_part(name):
                continue
            relative = "/".join(child_parts)
            if len(relative.encode("utf-8")) > 1024:
                continue
            try:
                info = entry.stat(follow_symlinks=False)
            except FileNotFoundError:
                continue
            if stat.S_ISDIR(info.st_mode) and os.access(name, os.R_OK | os.X_OK, dir_fd=fd, follow_symlinks=False):
                writable = os.access(name, os.W_OK | os.X_OK, dir_fd=fd, follow_symlinks=False)
                items.append(file_entry(name, relative, "directory", writable))
            elif stat.S_ISREG(info.st_mode) and info.st_nlink == 1 and info.st_size <= MAX_TRANSFER_BYTES:
                if os.access(name, os.R_OK, dir_fd=fd, follow_symlinks=False):
                    writable = (info.st_size <= MAX_FILE_BYTES and writable_dir
                                and os.access(name, os.W_OK, dir_fd=fd, follow_symlinks=False))
                    items.append(file_entry(name, relative, "file", writable))
    items.sort(key=lambda item: (item["type"] != "directory", item["name"].casefold(), item["name"]))
    return {"items": items, "total": len(items)}


def text_content(data):
    if len(data) > MAX_FILE_BYTES:
        raise FileError(413, "file_too_large", "Le fichier dépasse la taille maximale de 1 Mio.")
    if not TEXT_CONTROLS.search(data):
        try:
            return data.decode("utf-8")
        except UnicodeDecodeError:
            pass
    raise FileError(415, "unsupported_file", "Un fichier texte UTF-8 est attendu.")


def version(data):
    return '"' + hashlib.sha256(data).hexdigest() + '"'


def file_state(info):
    return (info.st_dev, info.st_ino, info.st_ctime_ns, info.st_mtime_ns, info.st_size)


def read_file(parent, name, preserve_acl=False, max_bytes=MAX_FILE_BYTES):
    # O_PATH permits checking the type before opening the underlying file for I/O.
    fd = os.open(name, os.O_PATH | os.O_NOFOLLOW, dir_fd=parent)
    try:
        info = os.fstat(fd)
        if not stat.S_ISREG(info.st_mode) or info.st_nlink != 1:
            raise FileError(404, "file_not_found", "Fichier introuvable.")
        if info.st_size > max_bytes:
            raise FileError(413, "file_too_large", f"Le fichier dépasse la taille maximale de {max_bytes // 1048576} Mio.")
        acl = None
        with open(f"/proc/self/fd/{fd}", "rb") as stream:
            data = stream.read(max_bytes + 1)
            if preserve_acl:
                try:
                    acl = os.getxattr(stream.fileno(), ACCESS_ACL)
                except OSError as error:
                    if error.errno not in NO_ACCESS_ACL:
                        raise
        if file_state(info) != file_state(os.fstat(fd)):
            raise FileError(409, "file_changed", "Le fichier a changé pendant la lecture.")
        return data, info, acl
    finally:
        os.close(fd)


def same_directory(first, second):
    original = os.fstat(first)
    current = os.fstat(second)
    return (original.st_dev, original.st_ino) == (current.st_dev, current.st_ino)


def write_file(parent, parts, name, etag, content):
    data = content.encode("utf-8")
    text_content(data)
    # Serialize REST writers in this directory. The original inode is checked again before the rename.
    fcntl.flock(parent, fcntl.LOCK_EX)
    current, original, acl = read_file(parent, name, preserve_acl=True)
    text_content(current)
    if version(current) != etag:
        raise FileError(412, "file_version_conflict", "Le fichier a changé depuis sa lecture.")
    if not os.access(name, os.W_OK, dir_fd=parent, follow_symlinks=False):
        raise PermissionError(errno.EACCES, "File is not writable")

    temporary = TEMPORARY_PREFIX + secrets.token_hex(16)
    temp_fd = os.open(temporary, os.O_WRONLY | os.O_CREAT | os.O_EXCL | os.O_NOFOLLOW, 0o600, dir_fd=parent)
    try:
        with os.fdopen(temp_fd, "wb") as stream:
            stream.write(data)
            temp_info = os.fstat(stream.fileno())
            if (temp_info.st_uid, temp_info.st_gid) != (original.st_uid, original.st_gid):
                os.fchown(stream.fileno(), original.st_uid, original.st_gid)
            os.fchmod(stream.fileno(), original.st_mode & 0o777)
            if acl is None:
                # Drop an ACL inherited from the directory if the original had none.
                try:
                    os.removexattr(stream.fileno(), ACCESS_ACL)
                except OSError as error:
                    if error.errno not in NO_ACCESS_ACL:
                        raise
            else:
                os.setxattr(stream.fileno(), ACCESS_ACL, acl)
            stream.flush()
            os.fsync(stream.fileno())
        try:
            with open_directory(parts) as current_parent:
                if not same_directory(parent, current_parent):
                    raise FileError(412, "file_version_conflict", "Le répertoire a changé depuis la lecture.")
                if file_state(os.stat(name, dir_fd=current_parent, follow_symlinks=False)) != file_state(original):
                    raise FileError(412, "file_version_conflict", "Le fichier a changé depuis sa lecture.")
                os.replace(temporary, name, src_dir_fd=parent, dst_dir_fd=current_parent)
        except OSError as error:
            if error.errno in NOT_FOUND:
                raise FileError(412, "file_version_conflict", "Le fichier a changé depuis sa lecture.") from None
            raise
    finally:
        try:
            os.unlink(temporary, dir_fd=parent)
        except FileNotFoundError:
            pass
    return {"etag": version(data)}


@contextmanager
def current_creation_parent(parent, parts):
    with open_directory(parts) as current_parent:
        if not same_directory(parent, current_parent):
            raise FileError(404, "file_not_found", "Répertoire introuvable.")
        yield current_parent


def create_file(parent, parts, name, data):
    temporary = TEMPORARY_PREFIX + secrets.token_hex(16)
    fd = os.open(temporary, os.O_WRONLY | os.O_CREAT | os.O_EXCL | os.O_NOFOLLOW, 0o666, dir_fd=parent)
    try:
        with os.fdopen(fd, "wb") as stream:
            stream.write(data)
            stream.flush()
            os.fsync(stream.fileno())
        with current_creation_parent(parent, parts) as current_parent:
            # Publish the complete file atomically and fail if any entry already exists.
            os.link(temporary, name, src_dir_fd=parent, dst_dir_fd=current_parent, follow_symlinks=False)
    finally:
        try:
            os.unlink(temporary, dir_fd=parent)
        except FileNotFoundError:
            pass


def execute(operation, payload):
    if operation == "roots":
        return roots()

    if payload["root"] != ROOT_ID:
        raise FileError(404, "file_root_not_found", "Racine inconnue.")
    path = payload["path"]
    parts = tuple(path.split("/")) if path else ()
    if len(parts) > 32 or (not parts and operation != "entries") or not all(map(valid_part, parts)):
        raise FileError(422, "invalid_file_path", "Un chemin relatif dans la racine est attendu.")
    if operation == "entries":
        with open_directory(parts) as fd:
            return entries(fd, parts)

    name = parts[-1]
    parent_parts = parts[:-1]
    with open_directory(parent_parts) as fd:
        if operation == "read":
            data, _, _ = read_file(fd, name)
            return {"content": text_content(data), "etag": version(data)}
        if operation == "write":
            return write_file(fd, parent_parts, name, payload["etag"], payload["content"])
        if operation == "download":
            data, _, _ = read_file(fd, name, max_bytes=MAX_TRANSFER_BYTES)
            with os.fdopen(3, "wb") as transfer:
                transfer.write(data)
            return {}
        if operation == "create" and payload["type"] == "directory":
            with current_creation_parent(fd, parent_parts) as current_parent:
                os.mkdir(name, dir_fd=current_parent)
                writable = os.access(name, os.W_OK | os.X_OK, dir_fd=current_parent, follow_symlinks=False)
            return file_entry(name, path, "directory", writable)
        if operation in {"create", "upload"}:
            data = b""
            if operation == "upload":
                with os.fdopen(3, "rb") as transfer:
                    data = transfer.read(MAX_TRANSFER_BYTES + 1)
                if len(data) > MAX_TRANSFER_BYTES:
                    raise FileError(413, "file_too_large", "Le fichier dépasse la taille maximale de 10 Mio.")
            create_file(fd, parent_parts, name, data)
            writable = (len(data) <= MAX_FILE_BYTES
                        and os.access(name, os.W_OK, dir_fd=fd, follow_symlinks=False))
            return file_entry(name, path, "file", writable)
        raise ValueError("Unknown file operation")


def main():
    try:
        result = execute(sys.argv[1], json.load(sys.stdin.buffer))
    except FileError as error:
        result = {"error": error.error}
    except OSError as error:
        if error.errno in NOT_FOUND:
            result = {"error": {"status": 404, "code": "file_not_found", "message": "Fichier ou répertoire introuvable."}}
        elif error.errno in {errno.EACCES, errno.EPERM, errno.EROFS}:
            result = {"error": {"status": 403, "code": "forbidden", "message": "Accès au fichier ou au répertoire refusé."}}
        elif error.errno == errno.EEXIST:
            result = {"error": {"status": 409, "code": "file_already_exists", "message": "Un fichier ou un répertoire porte déjà ce nom."}}
        else:
            raise
    json.dump(result, sys.stdout)


if __name__ == "__main__":
    main()
