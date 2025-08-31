#!/usr/bin/env bash
set -euo pipefail
URL="$1"
code=$(curl -s -o /dev/null -w "%{http_code}" "$URL")
echo "HTTP status: $code"
test "$code" = "200"
