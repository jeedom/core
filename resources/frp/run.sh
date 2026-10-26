#!/usr/bin/env bash
set +x
set -euo pipefail
umask 077
task_dir=$1
task_username=$2
task_relays=$3
task_scripts=$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" && pwd)
task_install_pid=''
exec 7>"$task_dir/service.lock"
flock -n 7 || exit 0
stop_install() {
  [[ -z $task_install_pid ]] || kill "$task_install_pid" 2>/dev/null || true
  exit 143
}
trap stop_install TERM INT
exec >>"$4" 2>&1
printf '{"state":"installing","updated":%s}\n' "$(date +%s)" > "$task_dir/status.json.tmp"
mv -f -- "$task_dir/status.json.tmp" "$task_dir/status.json"
bash "$task_scripts/install.sh" "$task_dir" 7>&- &
task_install_pid=$!
if ! wait "$task_install_pid"; then
  printf '{"state":"blocked","updated":%s}\n' "$(date +%s)" > "$task_dir/status.json.tmp"
  mv -f -- "$task_dir/status.json.tmp" "$task_dir/status.json"
  exit 1
fi
task_install_pid=''
trap - TERM INT
exec 6< "$task_dir/password"
rm -f -- "$task_dir/password"
export FRP_BINARY="$task_dir/bin/frpc"
export FRP_STATUS_FILE="$task_dir/status.json"
exec bash "$task_scripts/client.sh" --username "$task_username" --password-stdin \
  --relay "$task_relays" --ca-file "$task_scripts/../ca_frp.crt" --retry --state-dir "$task_dir" <&6 6<&-
