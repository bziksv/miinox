#!/bin/sh
set -e
cd "$(dirname "$0")/.."
PROJECT="$(pwd)"
ENV_FILE="$PROJECT/.local/db.env"
LOG_DIR="$PROJECT/.local/run"
LOG_FILE="$LOG_DIR/mysql-import.log"
PID_FILE="$LOG_DIR/mysql-import.pid"

# mysql@8.0 client — стабильнее сокета/плагинов, чем mysql 9 из PATH
MYSQL_BIN="${MYSQL_BIN:-/opt/homebrew/opt/mysql@8.0/bin/mysql}"
MYSQLADMIN_BIN="${MYSQLADMIN_BIN:-/opt/homebrew/opt/mysql@8.0/bin/mysqladmin}"
if [ ! -x "$MYSQL_BIN" ]; then
  MYSQL_BIN=mysql
  MYSQLADMIN_BIN=mysqladmin
fi
MYSQL="$MYSQL_BIN --socket=/tmp/mysql.sock -u root"

. "$ENV_FILE"

SQL_PATH="$PROJECT/$SQL_DUMP"
if [ ! -f "$SQL_PATH" ]; then
  if [ -f "$PROJECT/test_metplus6719.sql" ]; then
    SQL_PATH="$PROJECT/test_metplus6719.sql"
  else
    echo "Dump not found: $PROJECT/$SQL_DUMP"
    exit 1
  fi
fi

import_sql() {
  case "$SQL_PATH" in
    *.gz) gunzip -c "$SQL_PATH" | $MYSQL "$DB_NAME" ;;
    *) $MYSQL "$DB_NAME" < "$SQL_PATH" ;;
  esac
}

mkdir -p "$LOG_DIR"

if ! $MYSQLADMIN_BIN --socket=/tmp/mysql.sock ping --silent 2>/dev/null; then
  echo "MySQL is not running (socket /tmp/mysql.sock)."
  echo "Ожидается общий mysql@8.0 (как у almamed), без второго инстанса."
  exit 1
fi

echo "Creating database and user..."
$MYSQL <<SQL
CREATE DATABASE IF NOT EXISTS \`$DB_NAME\`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '$DB_LOGIN'@'localhost' IDENTIFIED BY '$DB_PASSWORD';
CREATE USER IF NOT EXISTS '$DB_LOGIN'@'127.0.0.1' IDENTIFIED BY '$DB_PASSWORD';
ALTER USER '$DB_LOGIN'@'localhost' IDENTIFIED BY '$DB_PASSWORD';
ALTER USER '$DB_LOGIN'@'127.0.0.1' IDENTIFIED BY '$DB_PASSWORD';
GRANT ALL PRIVILEGES ON \`$DB_NAME\`.* TO '$DB_LOGIN'@'localhost';
GRANT ALL PRIVILEGES ON \`$DB_NAME\`.* TO '$DB_LOGIN'@'127.0.0.1';
GRANT SESSION_VARIABLES_ADMIN, SYSTEM_VARIABLES_ADMIN ON *.* TO '$DB_LOGIN'@'localhost';
GRANT SESSION_VARIABLES_ADMIN, SYSTEM_VARIABLES_ADMIN ON *.* TO '$DB_LOGIN'@'127.0.0.1';
FLUSH PRIVILEGES;
SQL

TABLES=$($MYSQL -N -e \
  "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='$DB_NAME'" 2>/dev/null || echo 0)

FORCE_IMPORT=false
if [ "${1:-}" = "--force" ] || [ "${1:-}" = "-f" ]; then
  FORCE_IMPORT=true
fi

if [ "$FORCE_IMPORT" = true ]; then
  echo "Dropping database $DB_NAME..."
  $MYSQL -e "DROP DATABASE IF EXISTS \`$DB_NAME\`;"
  $MYSQL <<SQL
CREATE DATABASE \`$DB_NAME\`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
SQL
  TABLES=0
fi

if [ "$TABLES" -ge 50 ] && [ "$FORCE_IMPORT" = false ]; then
  echo "Database already has $TABLES tables — skip import."
  echo "To re-import: $0 --force"
else
  DUMP_SIZE=$(du -h "$SQL_PATH" | awk '{print $1}')
  echo "Importing $(basename "$SQL_PATH") ($DUMP_SIZE) into $DB_NAME..."

  if [ "${1:-}" = "--background" ] || [ "${1:-}" = "-b" ]; then
    if [ -f "$PID_FILE" ] && kill -0 "$(cat "$PID_FILE")" 2>/dev/null; then
      echo "Import already running (pid $(cat "$PID_FILE")). tail -f $LOG_FILE"
      exit 0
    fi
    : > "$LOG_FILE"
    nohup sh -c "
      case '$SQL_PATH' in
        *.gz) gunzip -c '$SQL_PATH' | $MYSQL_BIN --socket=/tmp/mysql.sock -u root '$DB_NAME' ;;
        *) $MYSQL_BIN --socket=/tmp/mysql.sock -u root '$DB_NAME' < '$SQL_PATH' ;;
      esac
      echo 'IMPORT OK' >> '$LOG_FILE'
    " >> "$LOG_FILE" 2>&1 &
    echo $! > "$PID_FILE"
    echo "Import started in background (pid $(cat "$PID_FILE"))."
    echo "Progress: tail -f $LOG_FILE"
    echo "When done: ./scripts/apply-local-db-config.sh && ./scripts/start-dev.sh"
    exit 0
  fi

  import_sql
  echo "Import finished."
fi

# Bitrix domain → localhost for local
$MYSQL "$DB_NAME" <<'SQL' 2>/dev/null || true
DELETE FROM b_lang_domain WHERE LID='s1';
INSERT INTO b_lang_domain (LID, DOMAIN) VALUES ('s1', 'localhost');
INSERT INTO b_lang_domain (LID, DOMAIN) VALUES ('s1', '127.0.0.1');
UPDATE b_option SET VALUE='N' WHERE MODULE_ID='main' AND NAME='site_stopped' LIMIT 10;
SQL

"$PROJECT/scripts/apply-local-db-config.sh"

TABLES=$($MYSQL_BIN -h 127.0.0.1 -u "$DB_LOGIN" -p"$DB_PASSWORD" -N -e \
  "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='$DB_NAME'" 2>/dev/null)
echo "Tables in $DB_NAME: $TABLES"
echo "Start: ./scripts/start-dev.sh"
