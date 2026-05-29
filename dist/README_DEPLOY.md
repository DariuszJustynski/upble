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