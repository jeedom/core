#!/usr/bin/env bash
set -euo pipefail
export JEEDOM_COMPATIBILITY_TEST=1
cd "$(dirname "$0")/../.."
TEST_LOG_DIR=$(mktemp -d)
# Preserve failure logs so a failed restore can be diagnosed.
echo "Round-trip logs: $TEST_LOG_DIR"
php tests/compatibility/databaseRoundTrip.php seed
php install/backup.php > "$TEST_LOG_DIR/backup.log" 2>&1
grep -qF '[END BACKUP SUCCESS]' "$TEST_LOG_DIR/backup.log"
TEST_BACKUP=$(sed -n 's/^Backup name: //p' "$TEST_LOG_DIR/backup.log" | tail -1)
test -s "$TEST_BACKUP"
php tests/compatibility/databaseRoundTrip.php mutate
php install/restore.php "backup=$TEST_BACKUP" > "$TEST_LOG_DIR/restore.log" 2>&1
grep -qF '[END RESTORE SUCCESS]' "$TEST_LOG_DIR/restore.log"
php tests/compatibility/databaseRoundTrip.php verify
php tests/compatibility/databaseRoundTrip.php cleanup
curl --fail --silent http://127.0.0.1/ > /dev/null
echo 'PASS: Jeedom backup/restore and HTTP response'
