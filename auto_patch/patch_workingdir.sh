#!/bin/bash

set -e

LOCAL_DIR="${BASH_SOURCE%/*}"


if [[ -r ${LOCAL_DIR}/patch.git.diff ]]; then

    cd "${LOCAL_DIR}/.."
    git apply auto_patch/patch.git.diff
    git rm auto_patch/patch.git.diff
    git rev-parse HEAD > auto_patch/ref_commit
    git add auto_patch/ref_commit

else

    echo "No patch available"

fi
