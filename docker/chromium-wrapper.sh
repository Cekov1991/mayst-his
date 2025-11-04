#!/bin/bash
# Wrapper script for Chromium to handle crashpad handler issues in Docker
# This script sets environment variables and launches Chromium with proper arguments

# Disable crash reporting completely by setting environment variables
export CHROME_HEADLESS=1
export BREAKPAD_DUMP_LOCATION=/tmp
export CHROME_DEVEL_SANDBOX=/usr/lib/chromium/chromium-sandbox
export GOOGLE_API_KEY=no
export GOOGLE_DEFAULT_CLIENT_ID=no
export GOOGLE_DEFAULT_CLIENT_SECRET=no

# Create a temporary directory for crashpad if it doesn't exist
mkdir -p /tmp/crashpad-db 2>/dev/null || true

# Find the crashpad handler location
CRASHPAD_HANDLER=$(find /usr/lib/chromium -name "chrome_crashpad_handler" -type f 2>/dev/null | head -1)

# If crashpad handler exists, create a fake one that does nothing
if [ -n "$CRASHPAD_HANDLER" ]; then
    CRASHPAD_DIR=$(dirname "$CRASHPAD_HANDLER")
    FAKE_HANDLER="$CRASHPAD_DIR/chrome_crashpad_handler"
    # Backup original if not already backed up
    if [ ! -f "${CRASHPAD_HANDLER}.orig" ]; then
        cp "$CRASHPAD_HANDLER" "${CRASHPAD_HANDLER}.orig" 2>/dev/null || true
    fi
    # Replace with our fake handler
    cp /var/www/html/docker/fake-crashpad-handler.sh "$FAKE_HANDLER" 2>/dev/null || true
    chmod +x "$FAKE_HANDLER" 2>/dev/null || true
fi

# Launch Chromium with all arguments passed through
exec /usr/lib/chromium/chromium \
    --disable-crash-reporter \
    --disable-breakpad \
    --crash-dumps-dir=/tmp/crashpad-db \
    "$@"

