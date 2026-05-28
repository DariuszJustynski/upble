# CodeIgniter Removal Plan

This document records the step-by-step plan for replacing the legacy
CodeIgniter 2.x layer with a minimal PHP 8 runtime suitable for shared
hosting.

---

## Status

| Phase | Description | Branch | Status |
|---|---|---|---|
| 0 | Analyse legacy codebase | `main` | ✅ Done — `docs/refactor-map.md` |
| 1 | Extract frontend templates | `refactor/frontend-extraction` | ✅ Done — `frontend-template/` |
| 2 | PHP 8 runtime (no CI) | `refactor/php8-runtime` | 🔄 In progress |
| 3 | Database layer (PDO) | _planned_ | ⬜ Pending |
| 4 | Authentication | _planned_ | ⬜ Pending |
| 5 | Admin panel | _planned_ | ⬜ Pending |
| 6 | Remove legacy directory | _planned_ | ⬜ Pending |

---

## Phase 2 — What was done

### Directory changes

| Action | Source | Destination |
|---|---|---|
| Moved | `application/` | `legacy-codeigniter/application/` |
| Moved | `system/` | `legacy-codeigniter/system/` |
| Moved | `index.php` (CI front controller) | `legacy-codeigniter/index.php` |
| Moved | `captcha/` | `legacy-codeigniter/captcha/` |
| Created | — | `public/` (new web root) |
| Created | — | `app/` (PHP 8 application code) |
| Created | — | `templates/` (PHP view layer) |
| Created | — | `data/` (JSON repositories) |
| Preserved | `frontend-template/` | Unchanged; v1 snapshot in `frontend-template-v1/` |
| Tagged | `v1/frontend-extraction` | Git tag preserving the extracted HTML templates |

### New architecture

```
public/
  index.php          Front controller + dev-server asset proxy
  .htaccess          Apache mod_rewrite rules

app/
  Core/
    Router.php       Regex-based GET router with named {params}
    View.php         Template renderer: extract() + XSS-safe e() helper
  Controllers/
    HomeController.php
    OfferController.php     /offers, /offers/{id}
    EmployerController.php  /employers/{id}
  Repositories/
    JsonOfferRepository.php       Reads data/offers.json
    JsonEmployerRepository.php    Reads data/employers.json

templates/
  layouts/main.php          HTML shell (Blueprint CSS + app styles)
  pages/
    home.php                Homepage: hero + latest offers + top employers
    offers.php              Offer listing with search
    offer-show.php          Single offer detail + employer sidebar
    employer-show.php       Employer profile + open offers + reviews
    error.php               Generic 404 page
  components/
    header.php              Top bar + main nav + search form
    footer.php              Footer links + copyright
    offer-card.php          Reusable offer card partial

data/
  offers.json
  employers.json
  accommodations.json
  reviews.json
```

---

## What was intentionally NOT done in Phase 2

- **No database** — JSON files replace SQL for now. Phase 3 will introduce
  a PDO-backed repository layer using the existing `db.sql` schema.
- **No authentication** — Tank Auth removed with CodeIgniter. A lightweight
  session-based auth will be added in Phase 4.
- **No admin panel** — The admin interface needs the auth layer first.
- **No Composer / PSR-4 autoloading** — All files are `require`d manually
  in `index.php` to stay dependency-free. Composer can be introduced later
  if the team decides to pull in specific libraries (e.g. a mailer).
- **No Flash upload widget** — Uploadify used Flash (EOL). Phase 2 adds a
  plain `<input type="file">` placeholder; a modern JavaScript uploader
  (e.g. Dropzone) will replace it in a later phase.

---

## Remaining CodeIgniter dependencies to resolve

| Dependency | CI feature used | Planned replacement |
|---|---|---|
| Authentication | `$this->tank_auth` | Custom session-based auth (Phase 4) |
| DB queries | `$this->db->get()` | PDO repositories (Phase 3) |
| Email sending | `$this->email->send()` | PHP `mail()` or SwiftMailer (Phase 5) |
| Pagination | `$this->pagination->create_links()` | Custom `Paginator` helper |
| Form validation | `$this->form_validation` | Custom `Validator` helper |
| Image resize | `$this->image_lib` | GD2 wrapper or Intervention Image |
| File upload | `$this->upload` | Native `$_FILES` handling |
| Session | `$this->session` | Native `$_SESSION` with CSRF token |

---

## Risk register

| Risk | Severity | Mitigation |
|---|---|---|
| `mysql_real_escape_string` in legacy biz model (PHP 7+ fatal) | High | Addressed in Phase 3 PDO rewrite |
| Flash-based Uploadify (browsers block Flash) | High | Replace with JS uploader in Phase 2/3 |
| Hard-coded absolute URLs in CI config | Medium | Resolved — new router uses relative paths |
| Tank Auth session format incompatible with new auth | Medium | Users will need to re-login after auth migration |
| Blueprint CSS grid (IE 6-era) | Low | Acceptable for now; migrate in UI refresh phase |
