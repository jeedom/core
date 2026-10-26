#!/usr/bin/env bash
set -euo pipefail
umask 077
task_dir=$1
task_version=0.71.1
case "$(uname -m)" in
  x86_64) task_arch=amd64; task_sha=1d9875210972a5d1a62b2dc4c48df45ab0af05d3ca1b5e494a014428259df803 ;;
  aarch64) task_arch=arm64; task_sha=8edf590b0c396fa42f9c147121a76af83cfc2002b08f5aef0ea1008c43482019 ;;
  armv7l) task_arch=arm_hf; task_sha=a40116f15f2a05b7001f985f1f1ccb2570e2aa41329b06de76a82e71495786db ;;
  *) printf '%s\n' 'Architecture FRP non prise en charge.' >&2; exit 1 ;;
esac
task_missing=false
for task_bin in curl jq flock setpriv; do command -v "$task_bin" >/dev/null || task_missing=true; done
if [[ $task_missing == true ]]; then
  task_sudo=()
  [[ $EUID == 0 ]] || task_sudo=(sudo -n)
  "${task_sudo[@]}" apt-get update
  "${task_sudo[@]}" env DEBIAN_FRONTEND=noninteractive apt-get install -y curl jq util-linux ca-certificates
fi
mkdir -p -- "$task_dir/bin"
exec 8>"$task_dir/install.lock"
flock -x 8
task_marker="$task_version $task_arch $task_sha"
if [[ -x $task_dir/bin/frpc && -r $task_dir/bin/version && -r $task_dir/bin/frpc.sha256 && $(cat "$task_dir/bin/version") == "$task_marker" ]]; then
  (cd -- "$task_dir/bin" && sha256sum --check --status frpc.sha256) && exit 0
fi
task_work=$(mktemp -d "$task_dir/install-XXXXXX")
trap 'rm -rf -- "$task_work"' EXIT
curl --fail --silent --show-error --location --proto '=https' --proto-redir '=https' --tlsv1.2 \
  --connect-timeout 10 --max-time 180 --retry 2 --retry-delay 2 \
  "https://github.com/fatedier/frp/releases/download/v$task_version/frp_${task_version}_linux_${task_arch}.tar.gz" -o "$task_work/frp.tar.gz"
printf '%s  %s\n' "$task_sha" "$task_work/frp.tar.gz" | sha256sum --check --status
tar --no-same-owner -xzf "$task_work/frp.tar.gz" -C "$task_work" "frp_${task_version}_linux_${task_arch}/frpc"
install -m 0700 "$task_work/frp_${task_version}_linux_${task_arch}/frpc" "$task_dir/bin/frpc.new"
mv -f -- "$task_dir/bin/frpc.new" "$task_dir/bin/frpc"
(cd -- "$task_dir/bin" && sha256sum frpc > frpc.sha256)
printf '%s\n' "$task_marker" > "$task_dir/bin/version"
