#!/bin/bash

# Paths
SRC_DIR="$(pwd)"
DEST_DIR="/Applications/MAMP/htdocs/wp-content/plugins/evolutio-blocks"

echo "→ Running composer dump-autoload..."
composer dump-autoload

echo "→ Running npm run build..."
npm run build

echo "→ Syncing files to MAMP plugin directory..."

# Sync only necessary files
rsync -av --delete \
  "$SRC_DIR/build" \
  "$SRC_DIR/vendor" \
  "$SRC_DIR/evolutio-blocks.php" \
  "$SRC_DIR/composer.json" \
  "$SRC_DIR/composer.lock" \
  "$DEST_DIR"

echo "✅ Deployment completed."
