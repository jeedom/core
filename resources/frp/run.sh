#!/usr/bin/env bash
set +x
set -euo pipefail
umask 077

task_dir=$1
task_username=$2
task_relays=$3
task_scripts=$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" && pwd)
exec >>"$4" 2>&1

export FRP_BINARY="$task_dir/bin/frpc"
export FRP_STATUS_FILE="$task_dir/status.json"
exec bash "$task_scripts/client.sh" "$task_username" "$task_relays" \
  "$task_scripts/../ca_frp.crt" "$task_dir"
