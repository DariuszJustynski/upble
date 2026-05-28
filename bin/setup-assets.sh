#!/usr/bin/env bash
# Copy frontend-template assets into public/assets/ for production deployment.
# Usage (from project root):  bash bin/setup-assets.sh
set -e
SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
ROOT="$(dirname "$SCRIPT_DIR")"

SRC="$ROOT/frontend-template/assets"
DEST="$ROOT/public/assets"

if [ ! -d "$SRC" ]; then
  echo "Error: source directory not found: $SRC"
  exit 1
fi

mkdir -p "$DEST"
cp -r "$SRC/." "$DEST/"
echo "Assets copied: $SRC  →  $DEST"
