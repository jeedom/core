#!/usr/bin/env bash
set +x
set -euo pipefail
umask 077

# Superviseur natif : obtenir une session, lancer frpc et surveiller le tunnel.
# Mot de passe lu sur stdin ; FRP_BINARY et FRP_STATUS_FILE fournis par run.sh.
readonly EXIT_RELAY_UNAVAILABLE=75
readonly EXIT_RETRY_CURRENT_RELAY=76
readonly EXIT_PERMANENT_FAILURE=78

die() {
  if [[ -n ${FRP_STATUS_FILE:-} ]] && command -v jq >/dev/null; then
    write_status blocked || true
  fi
  printf '%s\n' "$*" >&2
  exit 1
}

is_valid_relay() {
  # Chaque label DNS commence et finit par une lettre ou un chiffre.
  local label='[a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?'
  local hostname_pattern="^(${label}\\.)+${label}$"
  [[ ${#1} -le 253 && $1 =~ $hostname_pattern ]]
}

write_status() {
  local state=$1
  local registered=${2:-false}
  local published=${3:-false}
  jq -n \
    --arg state "$state" --arg relay "${relay:-}" \
    --argjson pid "$$" --argjson updated "$(date +%s)" \
    --argjson registered "$registered" --argjson published "$published" \
    '{state: $state, relay: $relay, pid: $pid, updated: $updated,
      registered: $registered, published: $published}' > "$FRP_STATUS_FILE.tmp" \
    && mv -f -- "$FRP_STATUS_FILE.tmp" "$FRP_STATUS_FILE"
}

save_recovery_state() {
  session_state=$1
  if [[ $session_state == blocked ]]; then
    write_status blocked
  fi
  printf '%s %s %s %s\n' "$session_state" "${session_id:--}" "$session_relay" "$route_owner" > "$recovery_file.tmp" \
    && mv -f -- "$recovery_file.tmp" "$recovery_file"
}

stop_frpc() {
  if [[ -n $frpc_pid ]]; then
    kill "$frpc_pid" 2>/dev/null || true
    wait "$frpc_pid" 2>/dev/null || true
    frpc_pid=''
  fi
}

cleanup() {
  trap - EXIT INT TERM
  if [[ -n $sleep_pid ]]; then
    kill "$sleep_pid" 2>/dev/null || true
  fi
  if [[ -n $update_pid ]]; then
    kill "$update_pid" 2>/dev/null || true
    wait "$update_pid" 2>/dev/null || true
  fi
  stop_frpc
  if [[ $session_state != blocked ]]; then
    write_status stopped || true
  fi
  if [[ -s $work_directory/bootstrap.json ]]; then
    if curl --fail --silent --show-error --proto '=https' --tlsv1.2 \
      --cacert "$ca_file" --connect-timeout 3 --max-time 5 \
      -H 'Content-Type: application/json' --data-binary "@$work_directory/bootstrap.json" \
      "https://$session_relay/frp/v1/close" >/dev/null 2>&1; then
      if [[ $stop_requested == true && $session_state != blocked && -n $session_id ]]; then
        save_recovery_state closed || true
      fi
    fi
  fi
  rm -rf -- "$work_directory"
}

pause_client() {
  sleep "$1" &
  sleep_pid=$!
  wait "$sleep_pid"
  sleep_pid=''
}

# Seule une indisponibilité du relais autorise la bascule vers un autre relais.
# Les refus d'authentification et erreurs TLS ne déclenchent pas de bascule.
request_relay() {
  local endpoint=$1
  local request_file=$2
  local response_file=$3
  local request_result=0
  local connect_timeout=5
  local request_timeout=15
  if [[ $endpoint == status ]]; then
    connect_timeout=3
    request_timeout=5
  fi
  http_status=$(curl --silent --show-error --proto '=https' --tlsv1.2 \
    --cacert "$ca_file" --connect-timeout "$connect_timeout" --max-time "$request_timeout" \
    -H 'Content-Type: application/json' --data-binary "@$request_file" \
    -o "$response_file" -w '%{http_code}' "https://$relay/frp/v1/$endpoint") || request_result=$?
  if (( request_result != 0 )); then
    case $request_result in
      5|6|7|18|28|52|55|56)
        return "$EXIT_RELAY_UNAVAILABLE"
        ;;
      *)
        printf '%s\n' 'Échec TLS ou configuration réseau invalide.' >&2
        return "$EXIT_PERMANENT_FAILURE"
        ;;
    esac
  fi
  if [[ $http_status == 429 || $http_status =~ ^5[0-9][0-9]$ ]]; then
    return "$EXIT_RELAY_UNAVAILABLE"
  fi
  if [[ $endpoint == status && $http_status != 401 && $http_status != 403 ]]; then
    if [[ $http_status != 200 ]] || ! jq -e '
      type == "object"
      and (.registered | type == "boolean")
      and (.published | type == "boolean")
    ' "$response_file" >/dev/null 2>&1; then
      printf '%s\n' 'Réponse de statut invalide.' >&2
      return "$EXIT_PERMANENT_FAILURE"
    fi
  fi
  return 0
}

prepare_session_request() {
  if [[ $session_state == initial ]]; then
    cp -- "$work_directory/request.json" "$work_directory/retry-request.json" || return "$EXIT_PERMANENT_FAILURE"
  else
    local allow_unassigned=false
    if [[ $session_state == closed || $route_owner == - ]]; then
      allow_unassigned=true
    fi
    jq --arg previous "$session_id" --arg owner "$route_owner" --arg relay "$relay" \
      --argjson unassigned "$allow_unassigned" '
        . + {reconnect: true, allowUnassigned: $unassigned, previousSessionId: $previous}
        + (if $owner != "-" and $owner != $relay then {previousRelay: $owner} else {} end)
      ' "$work_directory/request.json" > "$work_directory/retry-request.json" || return "$EXIT_PERMANENT_FAILURE"
  fi
}

open_session() {
  local request_result=0
  local reason
  request_relay bootstrap "$work_directory/retry-request.json" "$work_directory/reply.json" || request_result=$?
  (( request_result == 0 )) || return "$request_result"
  if [[ $http_status != 200 ]]; then
    reason=$(jq -r '.error // "BOOTSTRAP_FAILED"' "$work_directory/reply.json" 2>/dev/null) || reason=BOOTSTRAP_FAILED
    [[ $reason =~ ^[A-Z_]+$ ]] || reason=BOOTSTRAP_FAILED
    if [[ $http_status == 409 ]]; then
      case $reason in
        ROUTE_CLEANUP_PENDING|SESSION_STILL_CLOSING|BOOTSTRAP_PENDING)
          return "$EXIT_RETRY_CURRENT_RELAY"
          ;;
      esac
    fi
    printf 'Connexion refusée (%s).\n' "$reason" >&2
    return "$EXIT_PERMANENT_FAILURE"
  fi
  if ! jq -e --arg issuer "https://$relay/frp/v1" '
    .version == 1 and .issuer == $issuer
    and (.sessionId | test("^[a-f0-9]{48}$"))
    and (.sessionSecret | test("^[a-f0-9]{48}$"))
    and (.domain | type == "string") and (.port | type == "number")
  ' "$work_directory/reply.json" >/dev/null; then
    printf '%s\n' 'Réponse de bootstrap invalide.' >&2
    return "$EXIT_PERMANENT_FAILURE"
  fi
  if (( ${#relays[@]} > 1 )); then
    if ! jq -e 'has("assignedRelay") and (.assignedRelay == null or (.assignedRelay | type == "string"))' \
      "$work_directory/reply.json" >/dev/null; then
      printf '%s\n' 'Mettre à jour les serveurs avant d’utiliser plusieurs relais.' >&2
      return "$EXIT_PERMANENT_FAILURE"
    fi
  fi
  if jq -e 'has("assignedRelay")' "$work_directory/reply.json" >/dev/null; then
    route_owner=$(jq -r '.assignedRelay // "-"' "$work_directory/reply.json") || return "$EXIT_PERMANENT_FAILURE"
    [[ $route_owner == - ]] || is_valid_relay "$route_owner" || return "$EXIT_PERMANENT_FAILURE"
  fi
  mv -- "$work_directory/reply.json" "$work_directory/bootstrap.json" || return "$EXIT_PERMANENT_FAILURE"
  session_id=$(jq -r .sessionId "$work_directory/bootstrap.json") || return "$EXIT_PERMANENT_FAILURE"
  session_relay=$relay
  # Une session déjà reçue doit toujours être présentée lors de la reconnexion.
  if [[ $session_state == initial || $session_state == closed ]]; then
    save_recovery_state pending || return "$EXIT_PERMANENT_FAILURE"
  else
    save_recovery_state "$session_state" || return "$EXIT_PERMANENT_FAILURE"
  fi
}

start_frpc() {
  jq --arg relay "$relay" --arg ca "$ca_file" -f "$script_directory/client-config.jq" \
    "$work_directory/bootstrap.json" > "$work_directory/frpc.json" || return "$EXIT_PERMANENT_FAILURE"
  env -u http_proxy -u HTTP_PROXY -u https_proxy -u HTTPS_PROXY -u all_proxy -u ALL_PROXY \
    setpriv --pdeathsig TERM -- "$active_binary" -c "$work_directory/frpc.json" 9>&- &
  frpc_pid=$!
}

is_tunnel_ready() {
  [[ $http_status == 200 ]] || return 1
  jq -e '.registered == true and .published == true' \
    "$work_directory/status.json" >/dev/null 2>&1
}

wait_for_tunnel() {
  local request_result
  local attempt
  local deadline
  local ready=false
  deadline=$((SECONDS+60))
  for (( attempt=0; attempt<60 && SECONDS<deadline; attempt++ )); do
    kill -0 "$frpc_pid" 2>/dev/null || break
    request_result=0
    request_relay status "$work_directory/bootstrap.json" "$work_directory/status.json" || request_result=$?
    (( request_result != EXIT_PERMANENT_FAILURE )) || return "$EXIT_PERMANENT_FAILURE"
    if (( request_result == 0 )) && [[ $http_status == 401 || $http_status == 403 ]]; then
      return "$EXIT_RETRY_CURRENT_RELAY"
    fi
    if (( request_result == 0 )) && is_tunnel_ready; then
      ready=true
      route_owner=$relay
      save_recovery_state owned || return "$EXIT_PERMANENT_FAILURE"
      break
    fi
    pause_client 1 || return "$EXIT_PERMANENT_FAILURE"
  done
  if [[ $ready != true ]]; then
    printf '%s\n' 'Tunnel non confirmé.' >&2
    return "$EXIT_RELAY_UNAVAILABLE"
  fi
  write_status connected true true
  printf '%s\n' 'Proxy enregistré et route publiée.'
  connected_since=$SECONDS
}

monitor_tunnel() {
  local request_result
  local consecutive_failures=0
  local recovery_result=$EXIT_RETRY_CURRENT_RELAY
  while kill -0 "$frpc_pid" 2>/dev/null; do
    pause_client 5 || return "$EXIT_PERMANENT_FAILURE"
    request_result=0
    request_relay status "$work_directory/bootstrap.json" "$work_directory/status.json" || request_result=$?
    (( request_result != EXIT_PERMANENT_FAILURE )) || return "$EXIT_PERMANENT_FAILURE"
    if (( request_result == 0 )) && is_tunnel_ready; then
      consecutive_failures=0
      write_status connected true true
    else
      write_status reconnecting
      consecutive_failures=$((consecutive_failures + 1))
      # Vérifier notre session sur le relais qui répond avant toute bascule :
      # un ancien client ne doit pas contourner un refus SESSION_REPLACED.
      if (( request_result == EXIT_RELAY_UNAVAILABLE )); then
        recovery_result=$EXIT_RELAY_UNAVAILABLE
      else
        recovery_result=$EXIT_RETRY_CURRENT_RELAY
      fi
    fi
    (( consecutive_failures < 3 )) || break
  done
  printf '%s\n' 'Tunnel perdu ; vérification de l’affectation avant reconnexion.' >&2
  return "$recovery_result"
}

prepare_client_binary() {
  local update_result=0
  client_binary "$work_directory/bootstrap.json" "$state_directory/client-bin" "$FRP_BINARY" "$ca_file" \
    > "$work_directory/client-path" &
  update_pid=$!
  wait "$update_pid" || update_result=$?
  update_pid=''
  (( update_result == 0 )) || return "$update_result"
  IFS= read -r active_binary < "$work_directory/client-path" || return "$EXIT_PERMANENT_FAILURE"
}

connect_once() {
  local refresh_attempt
  local ticket_received_at
  local ticket_age
  for refresh_attempt in 0 1; do
    prepare_session_request || return "$EXIT_PERMANENT_FAILURE"
    open_session || return $?
    ticket_received_at=$SECONDS
    prepare_client_binary || return $?
    ticket_age=$((SECONDS-ticket_received_at))
    # Le bootstrap peut prendre 15 s : conserver une marge avant le Login.
    (( ticket_age >= 40 )) || break
    if (( refresh_attempt != 0 )); then
      printf '%s\n' 'Renouvellement de session trop lent ; reprise arrêtée.' >&2
      return "$EXIT_PERMANENT_FAILURE"
    fi
    printf '%s\n' 'Client prêt ; demande d’une nouvelle session sans retéléchargement.' >&2
    # Attendre l'expiration certaine, puis conserver la reprise conditionnelle.
    if (( ticket_age < 61 )); then
      pause_client "$((61-ticket_age))" || return "$EXIT_PERMANENT_FAILURE"
    fi
  done
  start_frpc || return "$EXIT_PERMANENT_FAILURE"
  wait_for_tunnel || return $?
  monitor_tunnel
}

read_configuration() {
  [[ $# == 4 ]] || die 'Paramètres du client FRP incomplets.'
  local command_name
  username=$1
  ca_file=$3
  state_directory=$4
  IFS=, read -r -a relays <<< "$2"
  # Premier départ aléatoire ; la reprise privilégie ensuite le relais mémorisé.
  relay=${relays[$((RANDOM % ${#relays[@]}))]}
  for command_name in curl jq flock sha256sum setpriv tar timeout uname; do
    command -v "$command_name" >/dev/null || die "Commande requise : $command_name."
  done
  [[ -n $username && ${#username} -le 255 ]] || die 'Identité DNS invalide.'
  IFS= read -r password || [[ -n $password ]]
  [[ -n $password && ${#password} -le 512 ]] || die 'Mot de passe DNS invalide.'
  [[ -r $ca_file && -x ${FRP_BINARY:?} ]] || die 'CA non lisible ou frpc non installé.'
}

acquire_client_lock() {
  local identity_hash
  lock_directory=/tmp/jeedom-frp-client-$UID
  if [[ ! -e $lock_directory ]]; then
    mkdir -m 0700 -- "$lock_directory" || die 'Création du répertoire de verrou impossible.'
  fi
  [[ -d $lock_directory && ! -L $lock_directory && -O $lock_directory ]] || die 'Répertoire de verrou non sûr.'
  chmod 0700 -- "$lock_directory"
  identity_hash=$(printf '%s' "$username" | sha256sum | cut -d' ' -f1)
  exec 9>"$lock_directory/$identity_hash.lock"
  flock -n 9 || die 'Un client tourne déjà pour cette identité sous ce compte.'
}

# Le fichier de reprise contient des identifiants, jamais les secrets de session.
# pending : session reçue ; owned : tunnel confirmé ; closed : arrêt volontaire.
# blocked : refus permanent, une relance manuelle est nécessaire.
# session_relay désigne le dernier relais ayant délivré une session ;
# route_owner désigne le relais ayant publié la route, qui peut être différent.
load_recovery_state() {
  local credential_hash
  local extra_fields
  local index
  session_state=initial
  session_id=''
  session_relay=$relay
  route_owner=$relay
  [[ -d $state_directory && ! -L $state_directory && -O $state_directory ]] || die 'Dossier de reprise non sûr.'
  chmod 0700 -- "$state_directory"
  credential_hash=$(printf '%s\0%s' "$username" "$password" | sha256sum | cut -d' ' -f1)
  recovery_file=$state_directory/$credential_hash.state
  if [[ -e $recovery_file || -L $recovery_file ]]; then
    [[ -f $recovery_file && ! -L $recovery_file && -O $recovery_file ]] || die 'État de reprise non sûr.'
    IFS=' ' read -r session_state session_id session_relay route_owner extra_fields < "$recovery_file" || die 'État de reprise illisible.'
    [[ $session_state =~ ^(pending|owned|closed|blocked)$ && -z $extra_fields ]] || die 'État de reprise invalide.'
    [[ $session_id =~ ^[a-f0-9]{48}$ || ( $session_state == blocked && $session_id == - ) ]] || die 'Session de reprise invalide.'
    is_valid_relay "$session_relay" || die 'Relais de reprise invalide.'
    [[ $route_owner == - ]] || is_valid_relay "$route_owner" || die 'Relais de reprise invalide.'
    if [[ $session_state == blocked ]]; then
      write_status blocked
      unset password
      printf '%s\n' 'Connexion bloquée après un refus permanent. Corriger la cause puis utiliser (Re)démarrer dans la configuration réseau.' >&2
      exit 0
    fi
  fi
  relay_index=0
  for index in "${!relays[@]}"; do
    if [[ ${relays[$index]} == "$session_relay" ]]; then
      relay_index=$index
    fi
  done
  relay=${relays[$relay_index]}
}

# Les secrets temporaires restent hors du répertoire web, quel que soit TMPDIR.
prepare_runtime() {
  work_directory=$(mktemp -d /tmp/jeedom-frp.XXXXXX)
  frpc_pid=''
  sleep_pid=''
  update_pid=''
  stop_requested=false

  trap cleanup EXIT
  trap 'stop_requested=true; exit 130' INT
  trap 'stop_requested=true; exit 143' TERM
  printf '%s' "$password" | jq -Rs --arg username "$username" --arg dns '' \
    '{username: $username, password: ., dns: $dns}' > "$work_directory/request.json"
  unset password
}

wait_before_retry() {
  local result=$1
  local retry_wait
  if (( connected_since >= 0 && SECONDS-connected_since >= 60 )); then
    retry_delay=5
  fi
  connected_since=-1
  if (( result == EXIT_RELAY_UNAVAILABLE && ${#relays[@]} > 1 )); then
    relay_index=$(((relay_index + 1) % ${#relays[@]}))
    relay=${relays[$relay_index]}
    printf 'Bascule vers le relais %s.\n' "$relay" >&2
  fi
  retry_wait=$((retry_delay + RANDOM % (retry_delay/5 + 1)))
  printf 'Nouvelle tentative dans %s s.\n' "$retry_wait" >&2
  pause_client "$retry_wait"
  retry_delay=$((retry_delay < 25 ? retry_delay*2 : 50))
}

supervise_client() {
  local result
  retry_delay=5
  connected_since=-1
  while true; do
    write_status connecting
    result=0
    connect_once || result=$?
    stop_frpc
    write_status reconnecting
    if (( result != EXIT_RELAY_UNAVAILABLE && result != EXIT_RETRY_CURRENT_RELAY )); then
      # Un redémarrage ne doit jamais réactiver une session révoquée.
      save_recovery_state blocked || die 'Enregistrement du refus permanent impossible.'
      printf '%s\n' 'Reconnexion automatique arrêtée. Corriger la cause puis utiliser (Re)démarrer dans la configuration réseau.' >&2
      exit 0
    fi
    wait_before_retry "$result"
  done
}

main() {
  read_configuration "$@"
  write_status connecting
  script_directory=$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" && pwd)
  source "$script_directory/client-update.sh"
  acquire_client_lock
  load_recovery_state
  prepare_runtime
  supervise_client
}

main "$@"
