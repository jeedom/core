#!/usr/bin/env bash
# Sourced by the launcher. HTTPS supplies release metadata; archives are hashed
# before extraction. No signature or executable shell content in the manifest.
client_version_valid() {
  [[ $1 =~ ^(0|[1-9][0-9]{0,5})\.(0|[1-9][0-9]{0,5})\.(0|[1-9][0-9]{0,5})$ ]]
}
client_version_newer() {
  local -a task_left task_right
  local task_index
  IFS=. read -r -a task_left <<< "$1"
  IFS=. read -r -a task_right <<< "$2"
  for task_index in 0 1 2; do
    if (( 10#${task_left[$task_index]} != 10#${task_right[$task_index]} )); then
      (( 10#${task_left[$task_index]} > 10#${task_right[$task_index]} )); return
    fi
  done
  return 1
}
client_architecture() {
  case $(uname -m) in
    x86_64) printf '%s\n' amd64 ;;
    aarch64|arm64)
      if [[ $(getconf LONG_BIT 2>/dev/null || true) == 32 ]]; then printf '%s\n' armhf;
      else printf '%s\n' arm64; fi ;;
    armv6l|armv7l|armv8l) printf '%s\n' armhf ;;
    *) printf '%s\n' 'Architecture du client FRP non prise en charge.' >&2; return 78 ;;
  esac
}
client_record() {
  local task_dir=$1 task_version=$2 task_arch=$3 task_binary=$4 task_hash
  task_hash=$(sha256sum "$task_binary") || return 78
  task_hash=${task_hash%% *}
  printf '%s %s %s\n' "$task_version" "$task_arch" "$task_hash" > "$task_dir/current.tmp" \
    && mv -f -- "$task_dir/current.tmp" "$task_dir/current" || return 78
}
client_binary() {
  # Run as a background worker: it owns the lock and returns only the path.
  local task_document=$1 task_dir=$2 task_ca=$3 task_bin='' task_arch task_version='' task_target
  local task_saved task_saved_arch task_saved_hash task_extra task_hash task_url task_sha task_archive_arch task_command
  local task_attempts=0 task_attempt_file task_work='' task_rc=0 task_fetch_pid=''
  for task_command in tar timeout uname; do
    command -v "$task_command" >/dev/null || { printf 'Commande requise pour mettre à jour FRP : %s.\n' "$task_command" >&2; exit 78; }
  done
  [[ -e $task_dir || -L $task_dir ]] || mkdir -p -m 0700 -- "$task_dir" || exit 78
  [[ -d $task_dir && ! -L $task_dir && -O $task_dir ]] || { printf '%s\n' 'Dossier des binaires FRP non sûr.' >&2; exit 78; }
  chmod 0700 -- "$task_dir" || exit 78
  exec 8>"$task_dir/update.lock"
  flock -w 20 8 || exit 75
  trap 'if [[ -n $task_fetch_pid ]]; then kill "$task_fetch_pid" 2>/dev/null || true; wait "$task_fetch_pid" 2>/dev/null || true; fi; [[ -z $task_work ]] || rm -rf -- "$task_work"' EXIT
  trap 'exit 130' INT
  trap 'exit 143' TERM
  task_arch=$(client_architecture) || exit $?
  if [[ -e $task_dir/current || -L $task_dir/current ]]; then
    [[ -f $task_dir/current && ! -L $task_dir/current && -O $task_dir/current ]] || exit 78
    IFS=' ' read -r task_saved task_saved_arch task_saved_hash task_extra < "$task_dir/current" || exit 78
    client_version_valid "$task_saved" && [[ $task_saved_arch == "$task_arch" && $task_saved_hash =~ ^[a-f0-9]{64}$ && -z $task_extra ]] || exit 78
    task_version=$task_saved task_bin=$task_dir/frpc-$task_saved-$task_arch
    [[ -f $task_bin && ! -L $task_bin && -O $task_bin && -x $task_bin ]] || exit 78
    task_hash=$(sha256sum "$task_bin") || exit 78
    [[ ${task_hash%% *} == "$task_saved_hash" && $(timeout 10 "$task_bin" --version 2>/dev/null) == "$task_version" ]] \
      || { printf '%s\n' 'Client FRP conservé invalide ; aucun retour à une ancienne version.' >&2; exit 78; }
  fi
  if jq -e '.clientRelease == null' "$task_document" >/dev/null; then
    [[ -n $task_bin ]] || { printf '%s\n' 'Le relais ne fournit pas de version du client FRP ; mettre à jour le serveur.' >&2; exit 78; }
    printf '%s\n' "$task_bin"; exit 0
  fi
  task_target=$(jq -er '.clientRelease.version | strings' "$task_document") || exit 78
  client_version_valid "$task_target" || { printf '%s\n' 'Version FRP annoncée invalide.' >&2; exit 78; }
  if [[ -n $task_version ]] && ! client_version_newer "$task_target" "$task_version"; then
    if [[ $task_target != "$task_version" ]]; then printf 'Client FRP %s conservé ; le relais annonce %s.\n' "$task_version" "$task_target" >&2; fi
    printf '%s\n' "$task_bin"; exit 0
  fi
  task_url=$(jq -er --arg arch "$task_arch" '.clientRelease.archives[$arch].url | strings' "$task_document") || exit 78
  task_sha=$(jq -er --arg arch "$task_arch" '.clientRelease.archives[$arch].sha256 | strings' "$task_document") || exit 78
  [[ ${#task_url} -le 2048 && $task_url =~ ^https://[^/[:space:]@]+/[^[:space:]]+$ && $task_sha =~ ^[a-f0-9]{64}$ ]] \
    || { printf '%s\n' 'Archive FRP annoncée invalide.' >&2; exit 78; }
  task_attempt_file=$task_dir/attempts-$task_target-$task_arch
  if [[ -e $task_attempt_file || -L $task_attempt_file ]]; then
    [[ -f $task_attempt_file && ! -L $task_attempt_file && -O $task_attempt_file ]] || exit 78
    IFS= read -r task_attempts < "$task_attempt_file" || exit 78
    [[ $task_attempts =~ ^[012]$ ]] || exit 78
  fi
  (( task_attempts < 2 )) || { printf 'Téléchargement FRP %s arrêté après deux tentatives ; corriger la cause et réarmer ce téléchargement.\n' "$task_target" >&2; exit 78; }
  printf '%s\n' "$((task_attempts+1))" > "$task_attempt_file.tmp" && mv -f -- "$task_attempt_file.tmp" "$task_attempt_file" || exit 78
  task_work=$(mktemp -d "$task_dir/.download.XXXXXX") || exit 78
  # The relay may use a private CA while GitHub uses a system CA.
  if [[ -r /etc/ssl/certs/ca-certificates.crt ]]; then cat /etc/ssl/certs/ca-certificates.crt > "$task_work/ca.pem"; fi
  cat "$task_ca" >> "$task_work/ca.pem" || exit 78
  printf 'Téléchargement du client FRP %s (%s), tentative %s/2.\n' "$task_target" "$task_arch" "$((task_attempts+1))" >&2
  curl --fail --silent --show-error --location --proto '=https' --proto-redir '=https' --tlsv1.2 --cacert "$task_work/ca.pem" \
    --connect-timeout 10 --max-time 300 --max-filesize 67108864 -o "$task_work/archive.tar.gz" "$task_url" &
  task_fetch_pid=$!
  wait "$task_fetch_pid" || task_rc=$?
  task_fetch_pid=''
  if (( task_rc != 0 )); then
    case $task_rc in 5|6|7|18|22|28|52|55|56) exit 75 ;; *) exit 78 ;; esac
  fi
  printf '%s  %s\n' "$task_sha" "$task_work/archive.tar.gz" | sha256sum --check --status \
    || { printf '%s\n' 'Empreinte SHA-256 de l’archive FRP incorrecte.' >&2; exit 78; }
  task_archive_arch=$task_arch
  [[ $task_arch != armhf ]] || task_archive_arch=arm_hf
  # Extract exactly one member to stdout; archive paths never become local paths.
  tar -xOzf "$task_work/archive.tar.gz" "frp_${task_target}_linux_${task_archive_arch}/frpc" > "$task_work/frpc" \
    && chmod 0700 "$task_work/frpc" || exit 78
  [[ $(timeout 10 "$task_work/frpc" --version 2>/dev/null) == "$task_target" ]] \
    || { printf '%s\n' 'Le nouveau client FRP ne démarre pas avec la version attendue.' >&2; exit 78; }
  task_bin=$task_dir/frpc-$task_target-$task_arch
  mv -f -- "$task_work/frpc" "$task_bin" || exit 78
  client_record "$task_dir" "$task_target" "$task_arch" "$task_bin" || exit $?
  # Only the committed highest version is used; stale binaries need no disk space.
  for task_saved in "$task_dir"/frpc-*; do [[ $task_saved == "$task_bin" ]] || rm -f -- "$task_saved"; done
  printf 'Client FRP %s installé et conservé.\n' "$task_target" >&2
  printf '%s\n' "$task_bin"
  exit 0
}
