#!/usr/bin/env bash
set +x
set -euo pipefail
umask 077
die() { { declare -F report >/dev/null && report blocked; } || true; printf '%s\n' "$*" >&2; exit 1; }
usage() { printf '%s\n' 'Usage: bash scripts/start-client.sh --username IDENTITE [--password SECRET | --password-stdin] [--relay FQDN[,FQDN...]] [--dns DOMAINE_BOX] [--local-ip IP] [--local-port PORT] [--ca-file CA] [--retry] [--state-dir DOSSIER]'; }
username='' password='' password_given=false password_stdin=false relay='' dns='' local_ip=127.0.0.1 local_port=80 ca=${FRP_CA_FILE:-/etc/ssl/certs/ca-certificates.crt}
task_retry=false task_state_dir=''
while (( $# )); do
  case "$1" in
    --username|--password|--relay|--dns|--local-ip|--local-port|--ca-file|--state-dir)
      (( $# >= 2 )) || die 'Valeur de paramètre manquante.'
      case "$1" in
        --username) username=$2 ;; --password) password=$2; password_given=true ;;
        --relay) relay=${relay:+$relay,}$2 ;; --dns) dns=$2 ;; --local-ip) local_ip=$2 ;;
        --local-port) local_port=$2 ;; --ca-file) ca=$2 ;; --state-dir) task_state_dir=$2 ;;
      esac
      shift 2 ;;
    --retry) task_retry=true; shift ;;
    --password-stdin) password_stdin=true; shift ;;
    --help|-h) usage; exit 0 ;;
    *) usage >&2; die 'Paramètre inconnu.' ;;
  esac
done
for task_bin in curl jq flock sha256sum setpriv; do command -v "$task_bin" >/dev/null || die "Commande requise : $task_bin (installation FRP incomplète)."; done
[[ -n $username && ${#username} -le 255 ]] || die '--username requis.'
[[ ! ( $password_given == true && $password_stdin == true ) ]] || die 'Choisir un seul mode de saisie du mot de passe.'
if [[ $password_stdin == true ]]; then IFS= read -r password || [[ -n $password ]];
elif [[ $password_given == false ]]; then IFS= read -r -s -p 'Mot de passe DNS : ' password </dev/tty; printf '\n' >&2; fi
[[ -n $password && ${#password} -le 512 ]] || die 'Mot de passe DNS requis.'
if [[ -z $relay && -r /etc/jeedom-frp/relay ]]; then relay=$(cat /etc/jeedom-frp/relay); fi
valid_relay() {
  [[ ${#1} -le 253 && $1 =~ ^([a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?$ ]]
}
[[ -n $relay && $relay != *, && $relay != ,* && $relay != *$'\n'* && ${#relay} -le 4096 ]] || die 'Fournir --relay FQDN[,FQDN...] ou le configurer avec install-client.sh.'
IFS=, read -r -a task_inputs <<< "$relay"
task_relays=()
for task_item in "${task_inputs[@]}"; do
  task_item=${task_item#"${task_item%%[![:space:]]*}"}
  task_item=${task_item%"${task_item##*[![:space:]]}"}
  valid_relay "$task_item" || die 'Adresse de relais invalide : fournir des FQDN séparés par des virgules.'
  task_duplicate=false
  for task_existing in "${task_relays[@]}"; do [[ $task_existing != "$task_item" ]] || task_duplicate=true; done
  [[ $task_duplicate == true ]] || task_relays+=("$task_item")
done
(( ${#task_relays[@]} >= 1 && ${#task_relays[@]} <= 16 )) || die 'Fournir entre 1 et 16 relais distincts.'
(( ${#task_relays[@]} <= 1 )) || task_retry=true
relay=${task_relays[0]}
[[ $local_port =~ ^[0-9]{1,5}$ ]] || die 'Port local invalide.'
local_port=$((10#$local_port))
[[ $local_port -gt 0 && $local_port -le 65535 ]] || die 'Port local invalide.'
[[ -r $ca && -x ${FRP_BINARY:?} ]] || die 'CA non lisible ou frpc non installé.'
report() {
  local state=$1 registered=${2:-false} published=${3:-false}
  jq -n --arg state "$state" --arg relay "${relay:-}" --argjson pid "$$" --argjson updated "$(date +%s)" --argjson registered "$registered" --argjson published "$published" \
    '{state:$state,relay:$relay,pid:$pid,updated:$updated,registered:$registered,published:$published}' > "$FRP_STATUS_FILE.tmp" && mv -f -- "$FRP_STATUS_FILE.tmp" "$FRP_STATUS_FILE"
}
report connecting
task_scripts=$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" && pwd)
task_lock_dir=/tmp/jeedom-frp-client-$UID
if [[ ! -e $task_lock_dir ]]; then mkdir -m 0700 -- "$task_lock_dir" || die 'Création du répertoire privé impossible.'; fi
[[ -d $task_lock_dir && ! -L $task_lock_dir && -O $task_lock_dir ]] || die 'Répertoire de verrou non sûr.'
chmod 0700 -- "$task_lock_dir"
task_identity_hash=$(printf '%s' "$username" | sha256sum | cut -d' ' -f1)
exec 9>"$task_lock_dir/$task_identity_hash.lock"
flock -n 9 || die 'Un client tourne déjà pour cette identité sous ce compte.'
task_state=initial task_previous='' task_state_file='' task_last_relay=$relay task_owner=$relay
if [[ $task_retry == true ]]; then
  task_state_dir=${task_state_dir:-$task_lock_dir}
  if [[ ! -e $task_state_dir ]]; then mkdir -m 0700 -- "$task_state_dir" || die 'Création du dossier de reprise impossible.'; fi
  [[ -d $task_state_dir && ! -L $task_state_dir && -O $task_state_dir ]] || die 'Dossier de reprise non sûr.'
  chmod 0700 -- "$task_state_dir"
  # Bind state to credentials, not list order: editing a list must not rearm a
  # refusal or turn an established client's next boot into a fresh takeover.
  task_state_hash=$(printf '%s\0%s' "$username" "$password" | sha256sum | cut -d' ' -f1)
  task_state_file=$task_state_dir/$task_state_hash.state
  if [[ ! -e $task_state_file && ! -L $task_state_file ]]; then
    task_legacy=''
    for task_item in "${task_relays[@]}"; do
      task_hash=$(printf '%s\0%s\0%s' "$username" "$task_item" "$password" | sha256sum | cut -d' ' -f1)
      task_candidate=$task_state_dir/$task_hash.state
      if [[ -e $task_candidate || -L $task_candidate ]]; then
        [[ -z $task_legacy ]] || die 'Plusieurs anciens états de connexion : intervention requise.'
        task_legacy=$task_candidate task_last_relay=$task_item task_owner=$task_item
      fi
    done
    [[ -z $task_legacy ]] || mv -- "$task_legacy" "$task_state_file"
  fi
  if [[ -e $task_state_file || -L $task_state_file ]]; then
    [[ -f $task_state_file && ! -L $task_state_file && -O $task_state_file ]] || die 'État de reprise non sûr.'
    task_legacy_relay=$task_last_relay
    IFS=' ' read -r task_state task_previous task_last_relay task_owner task_extra < "$task_state_file" || die 'État de reprise illisible.'
    task_last_relay=${task_last_relay:-$task_legacy_relay}
    task_owner=${task_owner:-$task_legacy_relay}
    [[ $task_state =~ ^(pending|owned|closed|blocked)$ && ( $task_previous =~ ^[a-f0-9]{48}$ || ( $task_state == blocked && $task_previous == - ) ) && -z $task_extra ]] || die 'État de reprise invalide.'
    valid_relay "$task_last_relay" && { [[ $task_owner == - ]] || valid_relay "$task_owner"; } || die 'Relais de reprise invalide.'
    if [[ $task_state == blocked ]]; then
      report blocked
      unset password
      printf '%s\n' 'Connexion bloquée après un refus permanent. Corriger la cause puis utiliser (Re)démarrer dans la configuration réseau.' >&2
      exit 0
    fi
  fi
fi
task_relay_index=0
for task_index in "${!task_relays[@]}"; do
  [[ ${task_relays[$task_index]} != "$task_last_relay" ]] || task_relay_index=$task_index
done
relay=${task_relays[$task_relay_index]}
task_work=$(mktemp -d)
task_pid='' task_sleep_pid='' task_stopping=false
save_state() {
  task_state=$1
  [[ $task_state != blocked ]] || report blocked
  [[ $task_retry == true ]] || return 0
  printf '%s %s %s %s\n' "$task_state" "${task_previous:--}" "$task_last_relay" "$task_owner" > "$task_state_file.tmp" && mv -f -- "$task_state_file.tmp" "$task_state_file"
}
stop_frpc() {
  if [[ -n $task_pid ]]; then kill "$task_pid" 2>/dev/null || true; wait "$task_pid" 2>/dev/null || true; task_pid=''; fi
}
cleanup() {
  trap - EXIT INT TERM
  [[ -z $task_sleep_pid ]] || kill "$task_sleep_pid" 2>/dev/null || true
  stop_frpc
  [[ $task_state == blocked ]] || report stopped || true
  if [[ -s $task_work/bootstrap.json ]]; then
    if curl --fail --silent --show-error --proto '=https' --tlsv1.2 --cacert "$ca" --connect-timeout 3 --max-time 5 \
      -H 'Content-Type: application/json' --data-binary "@$task_work/bootstrap.json" "https://$task_last_relay/frp/v1/close" >/dev/null 2>&1; then
      if [[ $task_stopping == true && $task_state != blocked && -n $task_previous ]]; then save_state closed || true; fi
    fi
  fi
  rm -rf -- "$task_work"
}
trap cleanup EXIT
trap 'task_stopping=true; exit 130' INT
trap 'task_stopping=true; exit 143' TERM
printf '%s' "$password" | jq -Rs --arg username "$username" --arg dns "$dns" '{username: $username, password: ., dns: $dns}' > "$task_work/request.json"
unset password
pause_client() {
  sleep "$1" & task_sleep_pid=$!
  wait "$task_sleep_pid"
  task_sleep_pid=''
}
# Only availability failures return 75; TLS/auth/protocol failures are terminal.
request() {
  local task_endpoint=$1 task_input=$2 task_output=$3 task_rc=0 task_connect=5 task_timeout=15
  if [[ $task_endpoint == status ]]; then task_connect=3; task_timeout=5; fi
  task_status=$(curl --silent --show-error --proto '=https' --tlsv1.2 --cacert "$ca" --connect-timeout "$task_connect" --max-time "$task_timeout" \
    -H 'Content-Type: application/json' --data-binary "@$task_input" -o "$task_output" -w '%{http_code}' "https://$relay/frp/v1/$task_endpoint") || task_rc=$?
  if (( task_rc != 0 )); then
    case $task_rc in 5|6|7|18|28|52|55|56) return 75 ;; *) printf '%s\n' 'Échec TLS ou configuration réseau invalide.' >&2; return 78 ;; esac
  fi
  [[ $task_status != 429 && ! $task_status =~ ^5[0-9][0-9]$ ]] || return 75
  if [[ $task_endpoint == status && $task_status != 401 && $task_status != 403 ]]; then
    [[ $task_status == 200 ]] && jq -e 'type == "object" and (.registered|type == "boolean") and (.published|type == "boolean")' "$task_output" >/dev/null 2>&1 || { printf '%s\n' 'Réponse de statut invalide.' >&2; return 78; }
  fi
  return 0
}
connect_once() {
  local task_rc=0 task_reason task_attempt task_failures=0 task_deadline task_admission task_recovery=76
  if [[ $task_state == initial ]]; then
    cp -- "$task_work/request.json" "$task_work/retry-request.json" || return 78
  else
    jq --arg previous "$task_previous" --arg owner "$task_owner" --arg relay "$relay" \
      --argjson unassigned "$([[ $task_state == closed || $task_owner == - ]] && printf true || printf false)" \
      '. + {reconnect: true, allowUnassigned: $unassigned, previousSessionId: $previous} + (if $owner != "-" and $owner != $relay then {previousRelay: $owner} else {} end)' "$task_work/request.json" > "$task_work/retry-request.json" || return 78
  fi
  for (( task_admission=0; task_admission<8; task_admission++ )); do
    task_rc=0
    request bootstrap "$task_work/retry-request.json" "$task_work/reply.json" || task_rc=$?
    if [[ $task_retry != true ]] && (( task_rc == 75 && task_admission < 7 )); then
      pause_client "$((1 + RANDOM % 5))" || return 78
    else
      (( task_rc == 0 )) || return "$task_rc"
      break
    fi
  done
  if [[ $task_status != 200 ]]; then
    task_reason=$(jq -r '.error // "BOOTSTRAP_FAILED"' "$task_work/reply.json" 2>/dev/null) || task_reason=BOOTSTRAP_FAILED
    [[ $task_reason =~ ^[A-Z_]+$ ]] || task_reason=BOOTSTRAP_FAILED
    if [[ $task_status == 409 && ( $task_reason == ROUTE_CLEANUP_PENDING || $task_reason == SESSION_STILL_CLOSING || $task_reason == BOOTSTRAP_PENDING ) ]]; then return 76; fi
    printf 'Connexion refusée (%s).\n' "$task_reason" >&2
    return 78
  fi
  jq -e --arg issuer "https://$relay/frp/v1" '.version == 1 and .issuer == $issuer and (.sessionId|test("^[a-f0-9]{48}$")) and (.sessionSecret|test("^[a-f0-9]{48}$")) and (.domain|type == "string") and (.port|type == "number")' "$task_work/reply.json" >/dev/null || { printf '%s\n' 'Réponse de bootstrap invalide.' >&2; return 78; }
  if (( ${#task_relays[@]} > 1 )); then
    jq -e 'has("assignedRelay") and (.assignedRelay == null or (.assignedRelay | type == "string"))' "$task_work/reply.json" >/dev/null || { printf '%s\n' 'Mettre à jour les serveurs avant d’utiliser plusieurs relais.' >&2; return 78; }
  fi
  if jq -e 'has("assignedRelay")' "$task_work/reply.json" >/dev/null; then
    task_owner=$(jq -r '.assignedRelay // "-"' "$task_work/reply.json") || return 78
    [[ $task_owner == - ]] || valid_relay "$task_owner" || return 78
  fi
  mv -- "$task_work/reply.json" "$task_work/bootstrap.json" || return 78
  task_previous=$(jq -r .sessionId "$task_work/bootstrap.json") || return 78
  task_last_relay=$relay
  # After receiving a generation, never fall back to an unconditional bootstrap.
  if [[ $task_state == initial || $task_state == closed ]]; then save_state pending || return 78; else save_state "$task_state" || return 78; fi
  jq --arg relay "$relay" --arg ca "$ca" --arg localIp "$local_ip" --argjson localPort "$local_port" -f "$task_scripts/client-config.jq" "$task_work/bootstrap.json" > "$task_work/frpc.json" || return 78
  env -u http_proxy -u HTTP_PROXY -u https_proxy -u HTTPS_PROXY -u all_proxy -u ALL_PROXY \
    setpriv --pdeathsig TERM -- "$FRP_BINARY" -c "$task_work/frpc.json" 9>&- &
  task_pid=$!
  task_ready=false
  task_deadline=$((SECONDS+60))
  for (( task_attempt=0; task_attempt<60 && SECONDS<task_deadline; task_attempt++ )); do
    kill -0 "$task_pid" 2>/dev/null || break
    task_rc=0
    request status "$task_work/bootstrap.json" "$task_work/status.json" || task_rc=$?
    (( task_rc != 78 )) || return 78
    if (( task_rc == 0 )) && [[ $task_status == 401 || $task_status == 403 ]]; then return 76; fi
    if (( task_rc == 0 )) && [[ $task_status == 200 ]] && jq -e '.registered == true and .published == true' "$task_work/status.json" >/dev/null 2>&1; then
      task_ready=true
      task_owner=$relay
      save_state owned || return 78
      break
    fi
    pause_client 1 || return 78
  done
  [[ $task_ready == true ]] || { printf '%s\n' 'Tunnel non confirmé.' >&2; return 75; }
  report connected true true
  printf '%s\n' 'Proxy enregistré et route publiée. Accès web externe à vérifier ; Ctrl+C pour arrêter.'
  task_connected_at=$SECONDS
  while kill -0 "$task_pid" 2>/dev/null; do
    pause_client 5 || return 78
    task_rc=0
    request status "$task_work/bootstrap.json" "$task_work/status.json" || task_rc=$?
    (( task_rc != 78 )) || return 78
    if (( task_rc == 0 )) && [[ $task_status == 200 ]] && jq -e '.registered == true and .published == true' "$task_work/status.json" >/dev/null 2>&1; then task_failures=0; report connected true true;
    else
      report reconnecting
      task_failures=$((task_failures + 1))
      # A responding relay must check our generation before any failover;
      # otherwise an old client could evade SESSION_REPLACED on its source.
      if (( task_rc == 75 )); then task_recovery=75; else task_recovery=76; fi
    fi
    (( task_failures < 3 )) || break
  done
  printf '%s\n' 'Tunnel perdu ; vérification de l’affectation avant reconnexion.' >&2
  return "$task_recovery"
}
task_delay=5 task_connected_at=-1
while true; do
  report connecting
  task_result=0
  connect_once || task_result=$?
  stop_frpc
  report reconnecting
  if [[ $task_retry != true ]]; then exit 1; fi
  if (( task_result != 75 && task_result != 76 )); then
    # Persist refusals before exiting: unless-stopped may restart this process,
    # but a Docker restart or host reboot must never rearm a revoked connection.
    save_state blocked || die 'Enregistrement du refus permanent impossible.'
    printf '%s\n' 'Reconnexion automatique arrêtée. Corriger la cause puis utiliser (Re)démarrer dans la configuration réseau.' >&2
    exit 0
  fi
  if (( task_connected_at >= 0 && SECONDS-task_connected_at >= 60 )); then task_delay=5; fi
  task_connected_at=-1
  if (( task_result == 75 && ${#task_relays[@]} > 1 )); then
    task_relay_index=$(((task_relay_index + 1) % ${#task_relays[@]}))
    relay=${task_relays[$task_relay_index]}
    printf 'Bascule vers le relais %s.\n' "$relay" >&2
  fi
  task_wait=$((task_delay + RANDOM % (task_delay/5 + 1)))
  printf 'Nouvelle tentative dans %s s.\n' "$task_wait" >&2
  pause_client "$task_wait"
  task_delay=$((task_delay < 25 ? task_delay*2 : 50))
done
