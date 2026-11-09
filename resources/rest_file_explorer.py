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
WRITE_DIRECTORIES = {("desktop", "custom"), ("mobile", "custom"), ("data", "customTemplates")}
PRIVATE_DIRECTORIES = {
    ("backup",), ("log",), ("config",), ("core", "config"), ("data", "php"), ("data", "editorTemp"),
}
PRIVATE_PLUGIN_DIRECTORIES = {"backup", "data", "log"}
PRIVATE_FILENAMES = {"auth.json", "credentials.json", "secrets.json", "secrets.yaml", "secrets.yml"}
MAX_FILE_BYTES = 1048576
MAX_DIRECTORY_ENTRIES = 1000
DIR_FLAGS = os.O_RDONLY | os.O_DIRECTORY | os.O_NOFOLLOW
TEXT_CONTROLS = re.compile(rb"[\x00-\x08\x0b\x0c\x0e-\x1f\x7f]")
WRITE_EXTENSIONS = {"css", "json", "md", "txt", "yaml", "yml"}
READ_EXTENSIONS = WRITE_EXTENSIONS | {"htm", "html", "js", "mjs", "svg"}
SERVER_EXTENSION = re.compile(r"php[0-9]*|pht|phtml|phar|cgi|fcgi|shtm|shtml|pl|py|sh|inc")
NOT_FOUND = {errno.ENOENT, errno.ENOTDIR, errno.ELOOP}
ACCESS_ACL = "system.posix_acl_access"
NO_ACCESS_ACL = {errno.ENODATA, errno.ENOTSUP}


class FileError(Exception):
    def __init__(self, status, code, message):
        self.error = {"status": status, "code": code, "message": message}


def valid_part(part):
    if not part or part.startswith(".") or any(char in part for char in "/\\:"):
        return False
    try:
        name = part.encode("utf-8")
    except UnicodeEncodeError:
        return False
    return len(name) <= 255 and not any(byte < 32 or byte == 127 for byte in name)


def private_path(parts):
    return (
        any(parts[:len(directory)] == directory for directory in PRIVATE_DIRECTORIES)
        or (len(parts) >= 3 and parts[0] == "plugins" and parts[2] in PRIVATE_PLUGIN_DIRECTORIES)
        or any(part.casefold() in PRIVATE_FILENAMES for part in parts)
    )


def file_policy(name):
    stem, dot, extension = name.lower().rpartition(".")
    if not dot or extension not in READ_EXTENSIONS:
        return False, False
    if any(SERVER_EXTENSION.fullmatch(part) for part in stem.split(".")[1:]):
        return False, False
    # Apache can assign handlers through any suffix. Only simple extensions are writable.
    return True, "." not in stem and extension in WRITE_EXTENSIONS


@contextmanager
def open_directory(parts):
    with ExitStack() as handles:
        fd = os.open(CORE, DIR_FLAGS)
        handles.callback(os.close, fd)
        for part in parts:
            fd = os.open(part, DIR_FLAGS, dir_fd=fd)
            handles.callback(os.close, fd)
        yield fd


def directory_access(parts, mode):
    try:
        with open_directory(parts) as fd:
            return os.access(".", mode | os.X_OK, dir_fd=fd)
    except OSError as error:
        if error.errno not in NOT_FOUND | {errno.EACCES, errno.EPERM}:
            raise
        return False


def roots():
    items = []
    if directory_access((), os.R_OK):
        writable = any(directory_access(parts, os.R_OK | os.W_OK) for parts in WRITE_DIRECTORIES)
        items.append({"id": ROOT_ID, "path": ".", "readable": True, "writable": writable})
    return {"items": items, "total": len(items)}


def entries(fd, parts):
    items = []
    writable_dir = parts[:2] in WRITE_DIRECTORIES and os.access(".", os.W_OK | os.X_OK, dir_fd=fd)
    with os.scandir(fd) as directory:
        for count, entry in enumerate(directory, 1):
            if count > MAX_DIRECTORY_ENTRIES:
                raise FileError(413, "directory_too_large", "Le répertoire dépasse 1 000 entrées.")
            name = entry.name
            child_parts = parts + (name,)
            if not valid_part(name) or private_path(child_parts):
                continue
            relative = "/".join(child_parts)
            if len(relative.encode("utf-8")) > 1024:
                continue
            try:
                info = entry.stat(follow_symlinks=False)
            except FileNotFoundError:
                continue
            if stat.S_ISDIR(info.st_mode) and os.access(name, os.R_OK | os.X_OK, dir_fd=fd, follow_symlinks=False):
                items.append({"name": name, "path": relative, "type": "directory", "writable": False})
            elif stat.S_ISREG(info.st_mode) and info.st_nlink == 1 and info.st_size <= MAX_FILE_BYTES:
                readable, writable = file_policy(name)
                if readable and os.access(name, os.R_OK, dir_fd=fd, follow_symlinks=False):
                    writable = writable and writable_dir and os.access(name, os.W_OK, dir_fd=fd, follow_symlinks=False)
                    items.append({"name": name, "path": relative, "type": "file", "writable": writable})
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


def read_file(parent, name, preserve_acl=False):
    # O_PATH permits checking the type before opening the underlying file for I/O.
    fd = os.open(name, os.O_PATH | os.O_NOFOLLOW, dir_fd=parent)
    try:
        info = os.fstat(fd)
        if not stat.S_ISREG(info.st_mode) or info.st_nlink != 1:
            raise FileError(404, "file_not_found", "Fichier introuvable.")
        if info.st_size > MAX_FILE_BYTES:
            raise FileError(413, "file_too_large", "Le fichier dépasse la taille maximale de 1 Mio.")
        acl = None
        with open(f"/proc/self/fd/{fd}", "rb") as stream:
            data = stream.read(MAX_FILE_BYTES + 1)
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

    temporary = ".rest-" + secrets.token_hex(16)
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
                old_dir = os.fstat(parent)
                current_dir = os.fstat(current_parent)
                if (old_dir.st_dev, old_dir.st_ino) != (current_dir.st_dev, current_dir.st_ino):
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


def execute(operation, payload):
    if operation == "roots":
        return roots()

    if payload["root"] != ROOT_ID:
        raise FileError(404, "file_root_not_found", "Racine inconnue.")
    path = payload["path"]
    parts = tuple(path.split("/")) if path else ()
    if len(parts) > 32 or (not parts and operation != "entries") or not all(map(valid_part, parts)):
        raise FileError(422, "invalid_file_path", "Un chemin relatif dans la racine est attendu.")
    if private_path(parts):
        raise FileError(404, "file_not_found", "Fichier ou répertoire introuvable.")

    if operation == "entries":
        with open_directory(parts) as fd:
            return entries(fd, parts)

    name = parts[-1]
    readable, writable = file_policy(name)
    if not readable:
        raise FileError(415, "unsupported_file", "Ce type de fichier est indisponible dans l’explorateur.")
    parent_parts = parts[:-1]
    with open_directory(parent_parts) as fd:
        if operation == "read":
            data, _, _ = read_file(fd, name)
            return {"content": text_content(data), "etag": version(data)}
        if not writable or parent_parts[:2] not in WRITE_DIRECTORIES:
            raise FileError(403, "file_read_only", "Ce fichier est en lecture seule.")
        return write_file(fd, parent_parts, name, payload["etag"], payload["content"])


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
        else:
            raise
    json.dump(result, sys.stdout)


if __name__ == "__main__":
    main()
