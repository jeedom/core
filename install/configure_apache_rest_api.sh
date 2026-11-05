#!/bin/sh
set -eu

enabled_site=/etc/apache2/sites-enabled/000-default.conf
[ -f "$enabled_site" ] || exit 0
site=$(readlink -f "$enabled_site")
rule=$(awk '$1 == "AliasMatch" { sub(/^[[:space:]]+/, ""); print; exit }' "$(dirname "$0")/apache_default")
[ -n "$rule" ]

workdir=$(mktemp -d "$(dirname "$site")/.jeedom-rest-api.XXXXXX")
trap 'rm -f "$workdir/original" "$workdir/updated"; rmdir "$workdir"' EXIT
cp -p "$site" "$workdir/original"
cp -p "$site" "$workdir/updated"

# Add the route only to virtual hosts serving the standard Jeedom document root.
if awk -v rule="$rule" '
	tolower($1) == "<virtualhost" { jeedom = 0; configured = 0 }
	tolower($1) == "documentroot" && ($2 == "/var/www/html" || $2 == "\"/var/www/html\"") { jeedom = 1 }
	{
		line = $0
		sub(/^[[:space:]]+/, "", line)
		if (line == rule) configured = 1
	}
	tolower($1) == "</virtualhost>" && jeedom && !configured {
		print "        " rule
		changed = 1
	}
	{ print }
	END { if (!changed) exit 3 }
' "$workdir/original" > "$workdir/updated"; then
	mv "$workdir/updated" "$site"
else
	status=$?
	[ "$status" -eq 3 ] && exit 0
	exit "$status"
fi

if ! apache2ctl configtest; then
	mv "$workdir/original" "$site"
	exit 1
fi

if service apache2 status >/dev/null 2>&1 && ! service apache2 reload; then
	mv "$workdir/original" "$site"
	service apache2 start || true
	exit 1
fi
