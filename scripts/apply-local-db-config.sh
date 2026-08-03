#!/bin/sh
set -e
cd "$(dirname "$0")/.."
PROJECT="$(pwd)"
ENV_FILE="$PROJECT/.local/db.env"

if [ ! -f "$ENV_FILE" ]; then
  echo "Missing $ENV_FILE"
  exit 1
fi

. "$ENV_FILE"

SITE_ROOT="$PROJECT"
BACKUP_DIR="$PROJECT/.local/backup"
mkdir -p "$BACKUP_DIR"

DBCONN="$SITE_ROOT/bitrix/php_interface/dbconn.php"
SETTINGS="$SITE_ROOT/bitrix/.settings.php"
LOCAL="$SITE_ROOT/bitrix/php_interface/dbconn.local.php"

if [ ! -f "$DBCONN" ]; then
  echo "No dbconn.php found"
  exit 1
fi

if [ ! -f "$BACKUP_DIR/dbconn.remote.php" ]; then
  cp "$DBCONN" "$BACKUP_DIR/dbconn.remote.php"
fi
if [ -f "$SETTINGS" ] && [ ! -f "$BACKUP_DIR/.settings.remote.php" ]; then
  cp "$SETTINGS" "$BACKUP_DIR/.settings.remote.php"
fi

if ! grep -q "dbconn.local.php" "$DBCONN" 2>/dev/null; then
  TMP="$DBCONN.new"
  {
    printf '%s\n' '<?php'
    printf '%s\n' 'if (file_exists(__DIR__ . '"'"'/dbconn.local.php'"'"'))'
    printf '%s\n' '{'
    printf '%s\n' '    require __DIR__ . '"'"'/dbconn.local.php'"'"';'
    printf '%s\n' '    return;'
    printf '%s\n' '}'
    printf '\n'
    if head -1 "$DBCONN" | grep -q '<?'; then
      tail -n +2 "$DBCONN"
    else
      cat "$DBCONN"
    fi
  } > "$TMP"
  mv "$TMP" "$DBCONN"
fi

cat > "$LOCAL" <<EOF
<?php
define("BX_USE_MYSQLI", true);
define("DBPersistent", false);
\$DBType = "mysql";
\$DBHost = "$DB_HOST";
\$DBLogin = "$DB_LOGIN";
\$DBPassword = "$DB_PASSWORD";
\$DBName = "$DB_NAME";
\$DBDebug = false;
\$DBDebugToFile = false;

define("DELAY_DB_CONNECT", true);
define("CACHED_b_file", 3600);
define("CACHED_b_lang", 3600);
define("CACHED_b_option", 3600);
define("BX_UTF", true);
define("BX_FILE_PERMISSIONS", 0644);
define("BX_DIR_PERMISSIONS", 0755);
@umask(~(BX_FILE_PERMISSIONS|BX_DIR_PERMISSIONS)&0777);
define("BX_DISABLE_INDEX_PAGE", true);
date_default_timezone_set("Etc/GMT-3");
mb_internal_encoding("UTF-8");
EOF

if [ -f "$SETTINGS" ]; then
  php -r "
\$file = '$SETTINGS';
\$data = include \$file;
\$data['connections']['value']['default']['host'] = '$DB_HOST';
\$data['connections']['value']['default']['database'] = '$DB_NAME';
\$data['connections']['value']['default']['login'] = '$DB_LOGIN';
\$data['connections']['value']['default']['password'] = '$DB_PASSWORD';
\$export = var_export(\$data, true);
file_put_contents(\$file, \"<?php\\n\\nreturn \" . \$export . \";\\n\");
"
fi

echo "Local DB config applied (127.0.0.1 / $DB_NAME)"
echo "Remote backup: $BACKUP_DIR/"
