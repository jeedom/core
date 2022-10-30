#!/bin/bash

set -e

LOCAL_DIR="${BASH_SOURCE%/*}"
DEST_INSTALL_DIR="$1"

DEST_PATCH_DIR="${DEST_INSTALL_DIR}/auto_patch"

apply_patch() {
    cd "${DEST_INSTALL_DIR}"
    git apply auto_patch/patch.git.diff
}

mkdir -p "${DEST_PATCH_DIR}"
[[ ${LOCAL_DIR} == "${DEST_PATCH_DIR}" ]] ||
    cp "$0" "${DEST_PATCH_DIR}"

if [[ ! -r ${LOCAL_DIR}/patch.git.diff ]]; then

    ( cd "${LOCAL_DIR}/.." && git diff "$(<auto_patch/ref_commit)" -- core install )> "${DEST_PATCH_DIR}/patch.git.diff"

    if [[ ${LOCAL_DIR} != "${DEST_PATCH_DIR}" ]]; then
        # Dest dir is non local : apply freshly generated patch
        apply_patch
    fi

else

    if [[ ${LOCAL_DIR} != "${DEST_PATCH_DIR}" ]]; then
        # Dest dir is not local
        cp "${LOCAL_DIR}/patch.git.diff" "${DEST_PATCH_DIR}"
    fi

    apply_patch

fi
