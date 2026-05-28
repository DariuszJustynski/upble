# Shared Hosting Deployment Guide

How to deploy the PHP 8 Upble runtime to a standard shared hosting account
(cPanel / Plesk / DirectAdmin) that provides:

- PHP 8.0 or later
- Apache with `mod_rewrite`
- File manager or FTP access
- MySQL (optional for Phase 2 — only needed from Phase 3)

---

## Prerequisites

| Requirement | Notes |
|---|---|
| PHP ≥ 8.0 | Check in cPanel → PHP Selector or `php -v` via SSH |
| `mod_rewrite` | Enabled by default on most cPanel hosts |
| `AllowOverride All` | Required for `.htaccess`; usually enabled for `public_html/` |
| JSON extension | Bundled with PHP 8; no action needed |

---

## Step 1 — Upload files

Upload the following directories and files to your hosting account.
**The web root must point to `/public`**, not the project root.

```
Upload these to your server:

  /app/            → /home/<user>/upble/app/
  /data/           → /home/<user>/upble/data/
  /templates/      → /home/<user>/upble/templates/
  /public/         → /home/<user>/upble/public/      ← web root

Do NOT upload:
  /legacy-codeigniter/    (old CI code — not needed)
  /frontend-template/     (source of truth; assets go into public/assets)
  /frontend-template-v1/  (backup only)
```

In cPanel the easiest way is to compress the four folders into a `.zip`
locally, upload via File Manager, and extract.

---

## Step 2 — Copy assets to /public/assets

The PHP views reference `/assets/css/`, `/assets/js/`, `/assets/images/`
etc. These files live in `frontend-template/assets/` and must be copied
into `public/assets/` for production.

**Via cPanel File Manager** — copy the contents of `frontend-template/assets/`
into `public/assets/`.

**Via SSH (if available):**

```bash
cp -r frontend-template/assets/. public/assets/
```

**One-line helper script** (run from project root):

```bash
bash bin/setup-assets.sh
```

The `bin/setup-assets.sh` script is included in the repo:

```bash
#!/usr/bin/env bash
set -e
SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
ROOT="$(dirname "$SCRIPT_DIR")"
mkdir -p "$ROOT/public/assets"
cp -r "$ROOT/frontend-template/assets/." "$ROOT/public/assets/"
echo "✓  Assets copied to public/assets/"
```

---

## Step 3 — Configure the document root

### cPanel (Addon / Sub domain)

1. **Domains** → **Addon Domains** (or **Subdomains**)
2. Set **Document Root** to `public_html/upble/public` (or wherever you
   uploaded the `/public` folder)
3. Save.

### Apache virtual host (VPS / SSH access)

```apache
<VirtualHost *:80>
    ServerName upble.example.com
    DocumentRoot /home/<user>/upble/public

    <Directory /home/<user>/upble/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog  ${APACHE_LOG_DIR}/upble-error.log
    CustomLog ${APACHE_LOG_DIR}/upble-access.log combined
</VirtualHost>
```

---

## Step 4 — Verify mod_rewrite

Open your browser at `https://yourdomain.com/offers`. If you see the offers
list page, URL rewriting works.

If you get a **404 from Apache** (not the app's own 404), `.htaccess` is not
being processed. Check:

1. `AllowOverride All` is set in your virtual host or `httpd.conf`.
2. `mod_rewrite` is loaded: `apache2ctl -M | grep rewrite`

---

## Step 5 — PHP version selector (cPanel)

1. **Software** → **PHP Selector** (or **MultiPHP Manager**)
2. Select **PHP 8.1** (or 8.2/8.3) for the domain.
3. Enable the `json` extension (usually pre-enabled).

---

## Step 6 — File permissions

```
/data/*.json          644  (readable by web server, not writable)
/templates/           755
/public/              755
/public/index.php     644
```

If you later add file upload functionality, the upload directory needs `755`
or `775` and the PHP process must be the owner.

---

## Step 7 — Test the deployment checklist

- [ ] `https://yourdomain.com/` — homepage loads
- [ ] `https://yourdomain.com/offers` — offer list loads
- [ ] `https://yourdomain.com/offers/1` — offer detail loads
- [ ] `https://yourdomain.com/employers/1` — employer profile loads
- [ ] CSS / JS / images load (check browser DevTools → Network tab)
- [ ] `https://yourdomain.com/nonexistent` — shows 404 page (not Apache's)

---

## Development server (local)

Run from the project root:

```bash
php -S localhost:8080 -t public public/index.php
```

Then open `http://localhost:8080/`.

The front controller (`public/index.php`) automatically detects
`PHP_SAPI === 'cli-server'` and proxies `/assets/*` requests to
`frontend-template/assets/`, so **no file copying is needed in development**.

---

## Environment differences

| Setting | Development | Shared hosting |
|---|---|---|
| `display_errors` | On (helpful) | Off (security) |
| `error_log` | stderr / terminal | `/home/<user>/logs/php_error.log` |
| Asset serving | Proxied by `index.php` | Static files in `public/assets/` |
| `.htaccess` | Not used (built-in server) | Required |
| HTTPS | Optional | Recommended (Let's Encrypt via cPanel) |

To enable verbose errors in development, add to `public/index.php` before
the bootstrap block:

```php
if (PHP_SAPI === 'cli-server') {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}
```

---

## Troubleshooting

| Symptom | Likely cause | Fix |
|---|---|---|
| Blank white page | PHP error with `display_errors = Off` | Check error log |
| `500 Internal Server Error` | `.htaccess` syntax error | Validate with `apachectl -t` |
| CSS/JS not loading | Assets not copied to `public/assets/` | Run `bin/setup-assets.sh` |
| All URLs return homepage | `RewriteBase` mismatch | Set `RewriteBase /subfolder/` if deployed in a subdirectory |
| `JSON decode error` | Malformed JSON in `/data/` | Validate with `python -m json.tool data/offers.json` |
