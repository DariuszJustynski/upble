# Template Map

Maps every file in `frontend-template/templates/` back to its original PHP source file in `application/views/`.

---

## Conventions

| Template convention | Meaning |
|---|---|
| `<!-- INCLUDE: path/to/partial.html -->` | Replace with content of that partial at render time |
| `{{placeholder}}` | Mustache-style data variable injected by the backend |
| `<!-- STATE: X -->` | HTML comment describing a conditional render state |
| `<!-- REPEAT: ... -->` | Marks a loop block; example items are hardcoded |
| `<!-- /REPEAT -->` | End of loop block |

All PHP (`<?php ... ?>`) has been removed. CodeIgniter helpers (`form_open`, `site_url`, `anchor`, `set_value`, `form_error`, `$this->tank_auth`, `$this->session->flashdata`) have been replaced with static HTML equivalents using `{{placeholder}}` variables.

---

## Layout

| Template | Original source | Notes |
|---|---|---|
| `layout/header.html` | `application/views/header.php` | Global HTML `<head>`, Blueprint CSS/JS includes, city nav bar, main search form, userpanel include, inline login overlay, Colorbox report target. PHP `$this->tank_auth` replaced with `{{user.*}}` data placeholders. |
| `layout/footer.html` | `application/views/footer.php` | Closing `</body></html>`, Upble credit link. |
| `layout/userpanel.html` | `application/views/userpanel.php` | Auth-conditional navigation bar. Logged-in state shown; guest state in HTML comment. |

---

## Public — Local (home)

| Template | Original source | Notes |
|---|---|---|
| `public/local/index.html` | `application/views/local/index.php` | City landing page. Newest reviews list with 2 example items. Star rating via `rating-{{review.rating}}` CSS class. |

---

## Public — Business

| Template | Original source | Notes |
|---|---|---|
| `public/biz/_list.html` | `application/views/biz/_list.php` | Reusable business card list partial. 2 example cards. |
| `public/biz/search.html` | `application/views/biz/search.php` | Search results shell. Includes `_list.html` partial and pagination. |
| `public/biz/category.html` | `application/views/biz/category.php` | Category browse page. Breadcrumb, neighbourhood filter sidebar, sub-category filter, includes `_list.html`. |
| `public/biz/show.html` | `application/views/biz/show.php` | Full business detail page. Reviews list with flower/flag actions, rating distribution bars, write-review inline form, Google Maps v3 embed with draggable fix-location pin. Admin actions preserved in HTML comments. |
| `public/biz/add.html` | `application/views/biz/add.php` | Add / edit business form. Google Maps geocoding JS retained with `{{config.google_maps_api_key}}`. AJAX-driven city/category/district dropdowns documented via JS comments. |

---

## Public — Photo

| Template | Original source | Notes |
|---|---|---|
| `public/photo/add.html` | `application/views/photo/add.php` | Uploadify 3.1 Flash-based upload widget. Session cookie field `{{config.session_cookie_name}}` replaces `$this->session->sess_cookie_name`. |
| `public/photo/process.html` | `application/views/photo/proccess.php` | AJAX upload result fragment returned after each file upload. |
| `public/photo/show.html` | `application/views/photo/show.php` | Photo gallery. Prev/next nav, thumbnail strip, admin delete via AJAX POST. |

---

## Public — Review

| Template | Original source | Notes |
|---|---|---|
| `public/review/add.html` | `application/views/review/add.php` | Star-rating widget + textarea. Handles both add (`/review/add/{{business.id}}`) and edit (`/review/edit/{{business.id}}/{{review.id}}`) modes. |
| `public/review/show.html` | `application/views/review/show.php` | Admin single-review view. Used from the flagged-content report workflow. |

---

## Public — User

| Template | Original source | Notes |
|---|---|---|
| `public/user/profile.html` | `application/views/user/profile.php` | Profile page shell. Includes `feed_container.html`, `network_container.html`, `bio_container.html` partials via AJAX-loadable boxes. |
| `public/user/bio_container.html` | `application/views/user/bio_container.php` | Sidebar bio partial. Three relationship states: `is_self`, `is_friend` (following), `stranger`. Follow/Unfollow buttons with hidden AJAX form. |
| `public/user/feed_container.html` | `application/views/user/feed_container.php` | Activity feed partial. 2 example feed items. AJAX-paginated. |
| `public/user/network_container.html` | `application/views/user/network_container.php` | Following / Followers tabs partial. 2 example friend cards. AJAX-paginated. |
| `public/user/setting_form.html` | `application/views/user/setting_form.php` | Profile settings form (`multipart/form-data`). Avatar preview + file upload, website, city, about-me fields. |
| `public/user/user_review.html` | `application/views/user/user_review.php` | User's own review list page. Reviews with flower action and edit link. Bio sidebar included via `bio_container.html`. |

---

## Public — Private Messages

| Template | Original source | Notes |
|---|---|---|
| `public/message/message.html` | `application/views/message/message.php` | PM shell page. Inbox/Sent tab bar with "Write A message" button. Active tab state shown via HTML comment alternatives. Loads one of the three sub-partials into `#pmbox`. |
| `public/message/_box.html` | `application/views/message/_box.php` | Inbox or sent message list table. Unread inbox items get `class="unread"`. 2 example rows. |
| `public/message/_form.html` | `application/views/message/_form.php` | Compose / reply form. Fields: receiver, title, content. |
| `public/message/_show.html` | `application/views/message/_show.php` | Single message view. Shows From/To depending on inbox/sent state. Reply button visible for inbox only. |

---

## Auth

| Template | Original source | Notes |
|---|---|---|
| `auth/login.html` | `application/views/auth/login_form.php` | Login form. Optional captcha block preserved in HTML comment. "Remember me" checkbox included. |
| `auth/register.html` | `application/views/auth/register_form.php` | Registration form. Username, email, password, confirm password. Optional captcha in HTML comment. |
| `auth/forgot_password.html` | `application/views/auth/forgot_password_form.php` | Forgot password — email/login input. |
| `auth/reset_password.html` | `application/views/auth/reset_password_form.php` | Reset password — new + confirm password fields. Token passed via URL `{{token}}`. |
| `auth/change_password.html` | `application/views/auth/change_password_form.php` | Change password — old + new + confirm fields. For logged-in users. |
| `auth/change_email.html` | `application/views/auth/change_email_form.php` | Change email — password confirmation + new email address. |
| `auth/send_again.html` | `application/views/auth/send_again_form.php` | Resend activation email form for unactivated accounts. |
| `auth/message.html` | `application/views/auth/general_message.php` | Generic auth message page. Used for activation success, email-sent confirmations, error notices. `{{message}}` is rendered HTML. |

---

## Admin

| Template | Original source | Notes |
|---|---|---|
| `admin/layout/header.html` | `application/views/admin/header.php` | Bootstrap 2.x admin shell open. Fixed top navbar with username dropdown. Sidebar nav with active-state comments. Flash alert blocks in HTML comments. |
| `admin/layout/footer.html` | `application/views/admin/footer.php` | Admin shell close — closes `.span9`, `.row-fluid`, `.container-fluid`, `</body></html>`. |
| `admin/node/list.html` | `application/views/admin/node/list.php` | City / category taxonomy list. Two-level tree (top nodes + indented children). Inline order inputs with bulk-save form. Delete uses `confirm()` dialog. |
| `admin/node/add.html` | `application/views/admin/node/add.php` | Add / edit city or category node. `form-horizontal` Bootstrap layout. Parent Node `<select>` built from `topNodes[]`. Context hint paragraph for city vs category. |
| `admin/report/list.html` | `application/views/admin/report/list.php` | Flagged content list. Bulk-delete checkboxes. Pagination. Note: original file contained `array_walk()` bug (fixed in `9faaf4b`). |
| `admin/user/list.html` | `application/views/admin/user/list.php` | User management list. Username, role, email, activated/banned flags, register time. Edit link per row. Bulk-delete checkboxes. |
| `admin/user/edit.html` | `application/views/admin/user/add.php` | Edit user form. Read-only username. Role, activated, banned selects. Ban reason textarea. (`add.php` was actually the edit form — no separate add-user view existed.) |

---

## Email

| Template | Original source | Notes |
|---|---|---|
| `email/activate-html.html` | `application/views/email/activate-html.php` | HTML activation email. `{{activation_url}}` replaces `site_url('/ucp/activate/'.$user_id.'/'.$new_email_key)`. |
| `email/activate-txt.txt` | `application/views/email/activate-txt.php` | Plain-text activation email. |
| `email/welcome-html.html` | `application/views/email/welcome-html.php` | HTML welcome email (sent after activation, no further verification). |
| `email/welcome-txt.txt` | `application/views/email/welcome-txt.php` | Plain-text welcome email. |
| `email/forgot_password-html.html` | `application/views/email/forgot_password-html.php` | HTML forgot-password email. `{{reset_url}}` replaces `site_url('/ucp/reset_password/'.$user_id.'/'.$new_pass_key)`. |
| `email/forgot_password-txt.txt` | `application/views/email/forgot_password-txt.php` | Plain-text forgot-password email. |
| `email/reset_password-html.html` | `application/views/email/reset_password-html.php` | HTML password-changed confirmation email. |
| `email/reset_password-txt.txt` | `application/views/email/reset_password-txt.php` | Plain-text password-changed confirmation email. |
| `email/change_email-html.html` | `application/views/email/change_email-html.php` | HTML new-email confirmation email. `{{confirm_url}}` replaces `site_url('/ucp/reset_email/'.$user_id.'/'.$new_email_key)`. |
| `email/change_email-txt.txt` | `application/views/email/change_email-txt.php` | Plain-text new-email confirmation email. |

---

## Placeholder reference

Common `{{placeholder}}` variables used across templates:

| Variable | Source field |
|---|---|
| `{{site_name}}` | `$config['site_name']` (CI config) |
| `{{user.name}}` | `$this->tank_auth->get_username()` |
| `{{user.id}}` | `$this->tank_auth->get_user_id()` |
| `{{user.avatar_big_path}}` | `users_profiles.avatar_big` |
| `{{user.review_count}}` | Derived count |
| `{{user.flower_count}}` | Derived count |
| `{{user.profile.website}}` | `users_profiles.website` |
| `{{user.profile.city}}` | `users_profiles.city` |
| `{{user.profile.about_me}}` | `users_profiles.about_me` |
| `{{business.id}}` | `biz.id` |
| `{{business.name}}` | `biz.name` |
| `{{business.address_1}}` | `biz.address_1` |
| `{{business.city.name}}` | `city` node name |
| `{{business.city.slug}}` | `city` node slug |
| `{{business.district.name}}` | `district` node name |
| `{{business.category_1.name}}` | Primary category node name |
| `{{business.category_1.slug}}` | Primary category node slug |
| `{{business.lat}}` / `{{business.lng}}` | `biz.lat` / `biz.lng` |
| `{{review.id}}` | `reviews.id` |
| `{{review.rating}}` | `reviews.rating` (1–5) |
| `{{review.content}}` | `reviews.content` |
| `{{review.date}}` | `reviews.created_at` (formatted) |
| `{{review.flower_count}}` | Derived count from `flowers` table |
| `{{config.google_maps_api_key}}` | Server-side config value |
| `{{config.session_cookie_name}}` | `$this->session->sess_cookie_name` |
| `{{config.thank_author_text}}` | Configurable UI string |
| `{{pagination.links}}` | CI pagination HTML output |
| `{{errors.*}}` | `form_error()` / `$errors[field]` |
| `{{flash.error}}` | `$this->session->flashdata('error')` |
| `{{flash.success}}` | `$this->session->flashdata('success')` |

---

## Asset layout

```
frontend-template/
├── assets/
│   ├── css/          — Blueprint grid + app styles (public)
│   │                   bootstrap.min.css, bootstrap-responsive.min.css (admin)
│   ├── js/           — jQuery 1.x, common.js, colorbox, cookie, ajaxfileupload,
│   │                   bootstrap.min.js, ckeditor/
│   ├── images/       — Public UI images (icons, default avatars, etc.)
│   ├── img/          — Bootstrap 2 glyphicons sprite
│   └── uploadify/    — Uploadify 3.1 SWF + CSS + JS
├── docs/
│   └── template-map.md   ← this file
└── templates/
    ├── layout/           — Public header / footer / userpanel
    ├── public/
    │   ├── local/        — Home / city landing
    │   ├── biz/          — Business pages
    │   ├── photo/        — Photo gallery & upload
    │   ├── review/       — Review forms
    │   ├── user/         — Profile & settings
    │   └── message/      — Private messaging
    ├── auth/             — Tank Auth pages
    ├── admin/
    │   ├── layout/       — Admin Bootstrap shell
    │   ├── node/         — City / category management
    │   ├── report/       — Flagged content
    │   └── user/         — User management
    └── email/            — Transactional emails (HTML + TXT)
```
