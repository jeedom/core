#!/bin/bash

set -e

LOCAL_DIR="${BASH_SOURCE%/*}"

cd "${LOCAL_DIR}"

if [[ ! -r patch.git.diff ]]; then

    REF_VER="$(<ref_commit)"
    ( cd .. && git diff "${REF_VER}" -- core install )> "patch.git.diff"
    git co "${REF_VER}" -- ../core ../install

    git add patch.git.diff
    git rm -f ref_commit


else

    echo "Patch already exists"

fi
