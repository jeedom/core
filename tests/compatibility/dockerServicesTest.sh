#!/usr/bin/env bash
set -euo pipefail
# Load the entrypoint's functions without running container initialization.
TEST_FUNCTIONS=$(mktemp)
trap 'rm -f "$TEST_FUNCTIONS"' EXIT
sed '/^ISMARIABDBINSTALLED=0/,$d' "$(dirname "$0")/../../install/OS_specific/Docker/init.sh" > "$TEST_FUNCTIONS"
source "$TEST_FUNCTIONS"
service() {
  [[ "$1" == mariadb && "$2" == restart ]]
}
service_mariadb restart
service() { return 1; }
if service_mariadb restart >/dev/null; then
  echo 'FAIL: MariaDB service failure was ignored'
  exit 1
fi
mariadb() { return 1; }
if (mariadb_sql 'SELECT 1') >/dev/null; then
  echo 'FAIL: SQL initialization failure was ignored'
  exit 1
fi
echo 'PASS: MariaDB native service and error propagation'
