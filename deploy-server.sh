#!/bin/bash
# Deploy script for api.allincase.id
# Run from: /home/rzyqlorq/api.allincase.id/allincase-laravel/

set -e

LARAVEL_DIR="/home/rzyqlorq/api.allincase.id/allincase-laravel"
PUBLIC_DIR="/home/rzyqlorq/api.allincase.id/public"
PHP="/opt/cpanel/ea-php82/root/usr/bin/php"

echo "=== Allincase Deploy Script ==="
echo ""

# 1. Ensure .htaccess has PHP 8.2 handler
echo "[1/6] Checking .htaccess PHP 8.2 handler..."
if ! head -1 "$PUBLIC_DIR/.htaccess" | grep -q "AddHandler application/x-httpd-ea-php82 .php"; then
    sed -i '1i AddHandler application/x-httpd-ea-php82 .php' "$PUBLIC_DIR/.htaccess"
    echo "  ✓ Added PHP 8.2 handler to .htaccess"
else
    echo "  ✓ PHP 8.2 handler already present"
fi

# 2. Copy assets to public folder
echo "[2/6] Copying assets to public folder..."
cp -r "$LARAVEL_DIR/public/tailwick" "$PUBLIC_DIR/" 2>/dev/null && echo "  ✓ Copied tailwick assets" || echo "  ⚠ tailwick folder not found"
cp -r "$LARAVEL_DIR/public/images" "$PUBLIC_DIR/" 2>/dev/null && echo "  ✓ Copied images" || echo "  ⚠ images folder not found"
cp "$LARAVEL_DIR/public/favicon.ico" "$PUBLIC_DIR/" 2>/dev/null && echo "  ✓ Copied favicon" || true

# 3. Ensure storage link
echo "[3/6] Checking storage link..."
if [ ! -L "$PUBLIC_DIR/storage" ]; then
    ln -sf "$LARAVEL_DIR/storage/app/public" "$PUBLIC_DIR/storage"
    echo "  ✓ Created storage symlink"
else
    echo "  ✓ Storage symlink exists"
fi

# 4. Set permissions
echo "[4/6] Setting permissions..."
chmod -R 755 "$LARAVEL_DIR/storage" 2>/dev/null
chmod -R 755 "$LARAVEL_DIR/bootstrap/cache" 2>/dev/null
echo "  ✓ Permissions set"

# 5. Clear and cache config
echo "[5/6] Caching configuration..."
cd "$LARAVEL_DIR"
$PHP artisan config:cache
$PHP artisan route:cache
$PHP artisan view:cache
echo "  ✓ Config, routes, and views cached"

# 6. Run migrations (if any)
echo "[6/6] Running migrations..."
$PHP artisan migrate --force
echo "  ✓ Migrations complete"

echo ""
echo "=== Deploy Complete ==="
echo "Site: https://api.allincase.id"
echo "Admin: https://api.allincase.id/admin/login"
