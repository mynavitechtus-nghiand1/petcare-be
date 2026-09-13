#!/usr/bin/env bash
# Sinh /etc/nginx/conf.d/cors.map chỉ từ CORS_ALLOWED_ORIGINS (Origin đầy đủ, cách nhau bởi dấu phẩy).
# Local cần ghi rõ ví dụ http://localhost:3000 trong CORS_ALLOWED_ORIGINS.

set -euo pipefail

CORS_FILE="${1:-/etc/nginx/conf.d/cors.map}"

tmp="$(mktemp)"
{
  printf '%s\n' 'map $http_origin $cors_allow_origin {'
  printf '%s\n' '    default "";'

  if [ -n "${CORS_ALLOWED_ORIGINS:-}" ]; then
    printf '%s' "$CORS_ALLOWED_ORIGINS" | tr ',' '\n' | while IFS= read -r line || [ -n "$line" ]; do
      origin=$(echo "$line" | sed 's/^[[:space:]]*//;s/[[:space:]]*$//')
      [ -z "$origin" ] && continue
      case "$origin" in
        *'"'*)
          echo "[generate-nginx-cors-map] skip origin containing double-quote: ${origin}" >&2
          continue
          ;;
      esac
      printf '    "%s" $http_origin;\n' "$origin"
    done
  fi

  printf '%s\n' '}'
  printf '%s\n' ''
  cat <<'REST'
map $request_uri $cors_is_api {
    default 0;
    ~^/api/  1;
}

map "${cors_is_api}:${cors_allow_origin}" $cors_response_origin {
    default "";
    ~^1:https?:// $cors_allow_origin;
}
REST
} >"$tmp"

mv "$tmp" "$CORS_FILE"
