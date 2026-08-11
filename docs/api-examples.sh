#!/usr/bin/env bash
# Minimal examples for the H-Code REST API.
# Requires curl and jq. Point BASE at your running instance.

BASE="${BASE:-http://localhost:8080}"

echo "== health =="
curl -s "$BASE/api/health.php"

echo
echo "== humanize (JSON body) =="
curl -s -X POST "$BASE/api/ai-humanize.php" \
  -H 'Content-Type: application/json' \
  -d '{"text": "Implement the function signature and return early on empty input."}'

echo
echo "== config (GET) =="
curl -s "$BASE/api/config.php"
