# Deployment Checklist – Upble Frontend Template

Use this checklist every time you deploy the `/dist` package to shared hosting.

---

## Pre-flight (local machine)

- [ ] Run the build script from the project root:
  ```bash
  php scripts/build-dist.php
  ```
- [ ] Confirm the output ends with:
  `✓ Build complete — NNN files in /dist`
- [ ] Spot-check at least one page in `dist/`:
  - `dist/index.html` opens in a browser without broken links
  - `dist/auth/login.html` shows full HTML (DOCTYPE → `</html>`)
  - `dist/public/biz/show.html` shows full HTML
- [ ] Confirm no leftover `<!-- INCLUDE:` strings in any dist file:
  ```bash
  grep -r "<!-- INCLUDE:" dist/ --include="*.html"
  # Should print nothing
  ```
- [ ] Confirm asset paths are relative (not `/assets/`):
  ```bash
  grep -r 'href="/assets/' dist/ --include="*.html"
  # Should print nothing
  ```

---

## Upload to shared hosting

- [ ] Zip the **contents** of `/dist` (not the folder itself):
  - macOS / Linux: `cd dist && zip -r ../dist.zip .`
  - Windows: select all files inside `dist\`, right-click → Send to → Compressed
- [ ] In cPanel → File Manager → `public_html`, upload `dist.zip`
- [ ] Extract `dist.zip` directly into `public_html` (not into a subfolder)
- [ ] Delete `dist.zip` after extraction
- [ ] Confirm `public_html/index.html` exists (not `public_html/dist/index.html`)

---

## Permissions

- [ ] Set all directories to **755**
- [ ] Set all files to **644**

Via SSH:
```bash
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;
```

---

## Smoke tests (browser)

Open each URL and verify it loads without errors:

- [ ] `https://yourdomain.com/` — Template Index loads, all section headings visible
- [ ] `https://yourdomain.com/auth/login.html` — Full page with header, nav, form, footer
- [ ] `https://yourdomain.com/public/biz/search.html` — Business search page
- [ ] `https://yourdomain.com/public/local/index.html` — City landing page
- [ ] `https://yourdomain.com/admin/node/list.html` — Admin layout (Bootstrap)
- [ ] CSS loads (Blueprint grid visible, no unstyled content)
- [ ] JS loads (no console errors for missing scripts)
- [ ] Images load (logo, icons)
- [ ] `https://yourdomain.com/nonexistent-page` — Redirects to `index.html` (no 404)

---

## Troubleshooting

| Symptom | Likely cause | Fix |
|---|---|---|
| **403 Forbidden on `/`** | No `index.html` at root, or permissions too strict | Confirm `index.html` exists in `public_html`; chmod dirs 755, files 644 |
| **403 Forbidden on subdirectory** | Permissions wrong | `chmod 755` all directories |
| **CSS / JS missing (404)** | `assets/` not uploaded or wrong path | Confirm `public_html/assets/css/screen.css` exists |
| **404 on direct page URL** | `.htaccess` not processed | Enable `mod_rewrite`; set `AllowOverride All` in host config |
| **Page shows raw `{{placeholders}}`** | Expected — these are template variables for the PHP backend | No action needed for static preview |
| **Build script fails** | PHP not in PATH | Run `php scripts/build-dist.php` with PHP 8 in PATH |

---

## Re-deploy after template changes

1. Edit files in `frontend-template/templates/` or `frontend-template/assets/`
2. Run `php scripts/build-dist.php` (regenerates `/dist` completely)
3. Re-upload changed files (or re-zip and extract as above)

---

## Related documents

- `docs/shared-hosting-deploy.md` — PHP runtime deployment (Phase 2)
- `docs/codeigniter-removal-plan.md` — Overall refactor plan and status
- `dist/README_DEPLOY.md` — Condensed upload guide bundled with the dist
