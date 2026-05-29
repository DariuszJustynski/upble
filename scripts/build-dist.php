<?php declare(strict_types=1);
/**
 * scripts/build-dist.php
 *
 * Builds /dist — a static, self-contained copy of the frontend templates
 * ready to upload directly to public_html on shared hosting.
 *
 * What it does:
 *   1. Deletes the old /dist and recreates it clean.
 *   2. Processes every .html template:
 *      – Recursively resolves <!-- INCLUDE: path --> directives by inlining
 *        the referenced file content.
 *      – Rewrites absolute /assets/ paths to relative equivalents based on
 *        directory depth (e.g. depth-2 file → ../../assets/).
 *   3. Copies .txt email templates verbatim.
 *   4. Copies frontend-template/assets/ → dist/assets/.
 *   5. Generates dist/index.html  (template navigation dashboard).
 *   6. Generates dist/.htaccess   (directory listing off, safe fallback).
 *   7. Generates dist/README_DEPLOY.md (upload guide).
 *
 * Usage (run from project root):
 *   php scripts/build-dist.php
 */

$ROOT        = dirname(__DIR__);
$TEMPLATES   = $ROOT . '/frontend-template/templates';
$ASSETS_SRC  = $ROOT . '/frontend-template/assets';
$DIST        = $ROOT . '/dist';

// ─── 1. Clean and recreate /dist ─────────────────────────────────────────────
echo "Removing old /dist …\n";
if (is_dir($DIST)) {
    rmdirRecursive($DIST);
}
mkdir($DIST, 0755, true);
echo "Created fresh /dist\n\n";

// ─── 2. Process HTML templates ───────────────────────────────────────────────
echo "Processing HTML templates …\n";
$htmlFiles = findFiles($TEMPLATES, 'html');
$processed = 0;
foreach ($htmlFiles as $srcFile) {
    $relPath  = str_replace('\\', '/', substr($srcFile, strlen($TEMPLATES) + 1));
    $destFile = $DIST . '/' . $relPath;
    $destDir  = dirname($destFile);
    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }

    // Resolve all <!-- INCLUDE: path --> directives recursively
    $content = resolveIncludes((string) file_get_contents($srcFile), $TEMPLATES);

    // Rewrite /assets/ to relative path based on directory depth
    $depth   = substr_count($relPath, '/');   // slashes = depth from dist root
    $prefix  = $depth > 0 ? str_repeat('../', $depth) : '';
    $content = rewriteAssets($content, $prefix);

    file_put_contents($destFile, $content);
    echo "  [html] {$relPath}\n";
    $processed++;
}
echo "Processed {$processed} HTML files\n\n";

// ─── 3. Copy TXT email templates verbatim ────────────────────────────────────
echo "Copying TXT email templates …\n";
$txtFiles = findFiles($TEMPLATES, 'txt');
foreach ($txtFiles as $srcFile) {
    $relPath  = str_replace('\\', '/', substr($srcFile, strlen($TEMPLATES) + 1));
    $destFile = $DIST . '/' . $relPath;
    $destDir  = dirname($destFile);
    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }
    copy($srcFile, $destFile);
    echo "  [txt]  {$relPath}\n";
}
echo "Copied " . count($txtFiles) . " TXT files\n\n";

// ─── 4. Copy assets ──────────────────────────────────────────────────────────
if (is_dir($ASSETS_SRC)) {
    echo "Copying assets …\n";
    $assetsDest = $DIST . '/assets';
    copyDir($ASSETS_SRC, $assetsDest);
    $assetCount = count(findFiles($assetsDest, '*'));
    echo "Copied {$assetCount} asset files → dist/assets/\n\n";
} else {
    echo "WARNING: frontend-template/assets/ not found — skipping asset copy\n\n";
}

// ─── 5. Generate dist/index.html ─────────────────────────────────────────────
file_put_contents($DIST . '/index.html', buildIndexHtml());
echo "Generated dist/index.html\n";

// ─── 6. Generate dist/.htaccess ──────────────────────────────────────────────
file_put_contents($DIST . '/.htaccess', buildHtaccess());
echo "Generated dist/.htaccess\n";

// ─── 7. Generate dist/README_DEPLOY.md ───────────────────────────────────────
file_put_contents($DIST . '/README_DEPLOY.md', buildReadme());
echo "Generated dist/README_DEPLOY.md\n\n";

// ─── Summary ─────────────────────────────────────────────────────────────────
$total = count(findFiles($DIST, '*'));
echo "✓ Build complete — {$total} files in /dist\n";
echo "  Upload the contents of /dist to public_html and open index.html\n";



// ═════════════════════════════════════════════════════════════════════════════
// Helper functions
// ═════════════════════════════════════════════════════════════════════════════

/**
 * Return all files under $dir with the given extension.
 * Pass '*' to match all extensions.
 */
function findFiles(string $dir, string $ext): array
{
    $result = [];
    if (!is_dir($dir)) {
        return $result;
    }
    $iter = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
    );
    foreach ($iter as $file) {
        if (!$file->isFile()) {
            continue;
        }
        if ($ext === '*' || strtolower($file->getExtension()) === $ext) {
            $result[] = (string) $file->getRealPath();
        }
    }
    sort($result);
    return $result;
}

/**
 * Recursively resolve <!-- INCLUDE: path --> directives.
 * $templatesRoot is the directory relative paths are resolved from.
 * $depth guards against circular includes.
 */
function resolveIncludes(string $content, string $templatesRoot, int $depth = 0): string
{
    if ($depth > 10) {
        return $content; // safety: no infinite loops
    }
    return (string) preg_replace_callback(
        '/<!--\s*INCLUDE:\s*([^\s\->]+)\s*-->/',
        static function (array $m) use ($templatesRoot, $depth): string {
            $includePath = $templatesRoot . '/' . ltrim($m[1], '/');
            if (!is_file($includePath)) {
                return "<!-- INCLUDE NOT FOUND: {$m[1]} -->";
            }
            $included = (string) file_get_contents($includePath);
            return resolveIncludes($included, $templatesRoot, $depth + 1);
        },
        $content
    );
}

/**
 * Convert absolute /assets/ references to relative ones.
 * $prefix is the relative path prefix, e.g. '../../' for a file two dirs deep.
 */
function rewriteAssets(string $content, string $prefix): string
{
    // href="/assets/  →  href="<prefix>assets/
    $content = preg_replace('#(href|src)="/assets/#', '$1="' . $prefix . 'assets/', $content);
    // url(/assets/    →  url(<prefix>assets/
    $content = preg_replace('#url\(/assets/#', 'url(' . $prefix . 'assets/', $content);
    // action="/  and similar absolute paths in forms — leave as-is (they are {{placeholder}} targets)
    return $content;
}

/**
 * Recursively copy a directory tree.
 */
function copyDir(string $src, string $dst): void
{
    if (!is_dir($dst)) {
        mkdir($dst, 0755, true);
    }
    $iter = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($src, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($iter as $item) {
        $destPath = $dst . '/' . $iter->getSubPathname();
        if ($item->isDir()) {
            if (!is_dir($destPath)) {
                mkdir($destPath, 0755, true);
            }
        } else {
            copy((string) $item->getRealPath(), $destPath);
        }
    }
}

/**
 * Recursively delete a directory and all its contents.
 */
function rmdirRecursive(string $dir): void
{
    $iter = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($iter as $item) {
        if ($item->isDir()) {
            rmdir((string) $item->getRealPath());
        } else {
            unlink((string) $item->getRealPath());
        }
    }
    rmdir($dir);
}

// ─── Generated file contents ─────────────────────────────────────────────────

function buildIndexHtml(): string
{
    return <<<'HTML'
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Upble – Template Index</title>
  <link rel="stylesheet" href="assets/css/screen.css" />
  <link rel="stylesheet" href="assets/css/style.css" />
  <style>
    body  { font-family: Georgia, serif; max-width: 680px; margin: 40px auto; padding: 0 24px; color: #333; }
    h1    { font-size: 22px; border-bottom: 2px solid #e67e22; padding-bottom: 8px; margin-bottom: 4px; }
    .sub  { color: #888; font-size: 13px; margin: 0 0 28px; }
    h2    { font-size: 13px; color: #999; margin: 28px 0 8px;
            text-transform: uppercase; letter-spacing: .07em; }
    ul    { list-style: none; padding: 0; margin: 0 0 4px; }
    li    { margin: 5px 0; }
    a     { color: #e67e22; text-decoration: none; }
    a:hover { text-decoration: underline; }
    .badge { font-size: 11px; background: #f5f5f5; border: 1px solid #ddd;
             border-radius: 3px; padding: 1px 5px; margin-left: 6px; color: #999; }
    code  { background: #f5f5f5; padding: 1px 4px; font-size: 12px; }
  </style>
</head>
<body>

<h1>Upble – Frontend Template Index</h1>
<p class="sub">
  Static preview. Dynamic values are shown as <code>{{placeholders}}</code>.
</p>

<h2>Public Pages</h2>
<ul>
  <li><a href="public/local/index.html">Home / City Landing</a></li>
  <li><a href="public/biz/search.html">Business Search Results</a></li>
  <li><a href="public/biz/category.html">Business Category</a></li>
  <li><a href="public/biz/show.html">Business Detail</a></li>
  <li><a href="public/biz/add.html">Add a Business</a></li>
  <li><a href="public/review/add.html">Write / Edit a Review</a></li>
  <li><a href="public/review/show.html">Review Detail</a></li>
  <li><a href="public/photo/add.html">Upload Photos</a> <span class="badge">Flash / Uploadify widget</span></li>
  <li><a href="public/photo/show.html">Photo Detail</a></li>
  <li><a href="public/photo/process.html">Photo Processing</a></li>
</ul>

<h2>User Account</h2>
<ul>
  <li><a href="public/user/profile.html">User Profile</a></li>
  <li><a href="public/user/setting_form.html">Account Settings</a></li>
</ul>

<h2>Messaging</h2>
<ul>
  <li><a href="public/message/message.html">Message Inbox</a></li>
</ul>

<h2>Authentication</h2>
<ul>
  <li><a href="auth/login.html">Login</a></li>
  <li><a href="auth/register.html">Register</a></li>
  <li><a href="auth/forgot_password.html">Forgot Password</a></li>
  <li><a href="auth/reset_password.html">Reset Password</a></li>
  <li><a href="auth/change_password.html">Change Password</a></li>
  <li><a href="auth/change_email.html">Change Email</a></li>
  <li><a href="auth/send_again.html">Re-send Activation</a></li>
  <li><a href="auth/message.html">Auth Confirmation Message</a></li>
</ul>

<h2>Admin Panel</h2>
<ul>
  <li><a href="admin/node/list.html">Node List</a></li>
  <li><a href="admin/node/add.html">Add Node</a></li>
  <li><a href="admin/report/list.html">Report List</a></li>
  <li><a href="admin/user/list.html">User List</a></li>
  <li><a href="admin/user/edit.html">Edit User</a></li>
</ul>

<h2>Email Templates (HTML preview)</h2>
<ul>
  <li><a href="email/activate-html.html">Account Activation</a></li>
  <li><a href="email/welcome-html.html">Welcome</a></li>
  <li><a href="email/forgot_password-html.html">Forgot Password</a></li>
  <li><a href="email/reset_password-html.html">Reset Password</a></li>
  <li><a href="email/change_email-html.html">Change Email Notification</a></li>
</ul>

<h2>Layout Partials (raw fragments)</h2>
<ul>
  <li><a href="layout/header.html">header.html</a> <span class="badge">partial</span></li>
  <li><a href="layout/footer.html">footer.html</a> <span class="badge">partial</span></li>
  <li><a href="layout/userpanel.html">userpanel.html</a> <span class="badge">partial</span></li>
</ul>

</body>
</html>
HTML;
}

function buildHtaccess(): string
{
    return <<<'HTACCESS'
# Upble /dist – Apache shared hosting
Options -Indexes
DirectoryIndex index.html index.php

# Serve existing files and directories as-is
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} -f [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^ - [L]

# Fallback: send any unmatched path to index.html (prevents 404 on reload)
RewriteRule ^ index.html [L]
HTACCESS;
}

function buildReadme(): string
{
    return <<<'MD'
# Upble – Shared Hosting Deployment Guide

This `/dist` folder is the **ready-to-upload** static template preview.
Upload its **contents** (not the folder itself) to `public_html`.

---

## Quick Deploy via cPanel File Manager

1. On your local machine, zip the contents of `/dist` (not the folder):
   - macOS / Linux: `cd dist && zip -r ../dist.zip .`
   - Windows: select everything inside `dist\`, right-click → Send to → Compressed
2. In cPanel → **File Manager**, navigate to `public_html`.
3. Click **Upload** and upload `dist.zip`.
4. Select the uploaded file → **Extract** (extract *into* `public_html`, not a subfolder).
5. Delete `dist.zip` after extraction.
6. Open `https://yourdomain.com/` — you should see the Template Index.

---

## Quick Deploy via SSH / SCP

```bash
scp -r dist/* user@host:public_html/
```

---

## Directory structure after upload

```
public_html/
  index.html          ← Template Index (links to all pages)
  .htaccess           ← Prevents 403; sets DirectoryIndex
  README_DEPLOY.md    ← This file
  assets/
    css/
    js/
    images/
  public/
    local/index.html
    biz/search.html  show.html  add.html  category.html
    review/add.html  show.html
    photo/add.html  show.html  process.html
    user/profile.html  setting_form.html
    message/message.html
  auth/
    login.html  register.html  forgot_password.html …
  admin/
    node/list.html  add.html
    report/list.html
    user/list.html  edit.html
  email/
    activate-html.html  welcome-html.html …
  layout/
    header.html  footer.html  userpanel.html
```

---

## File permissions

Set via SSH:

```bash
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;
```

Or in cPanel File Manager: select all → Permissions → 644 for files, 755 for folders.

---

## Requirements

| Requirement | Notes |
|---|---|
| Apache with `mod_rewrite` | Required for `.htaccess` to work |
| `AllowOverride All` | Must be set in host config for `public_html` |
| PHP | Not required for static HTML preview |

---

## Diagnostics

| Symptom | Likely cause | Fix |
|---|---|---|
| **403 Forbidden** | No `index.html` at root, or directory permissions wrong | Confirm `index.html` is in `public_html`; set dirs to 755 |
| **CSS / JS not loading** | Wrong asset paths, `assets/` missing | Confirm `assets/` folder is present; check browser DevTools |
| **404 on page reload** | `.htaccess` not processed | Enable `mod_rewrite`; set `AllowOverride All` |
| **Blank white page** | Browser blocked inline script | Open DevTools Console for errors |

---

## Rebuilding

From the project root:

```bash
php scripts/build-dist.php
```

This deletes `/dist` entirely and regenerates it fresh from
`frontend-template/templates/` and `frontend-template/assets/`.
MD;
}
