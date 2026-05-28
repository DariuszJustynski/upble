# Upble Frontend Extraction — Refactor Map

> Generated: 2026-05-28  
> Branch: `refactor/frontend-extraction`  
> Stack: PHP 5.x / CodeIgniter 2.x + jQuery 1.x + Bootstrap 2.x

---

## Table of Contents

1. [File Classification Legend](#1-file-classification-legend)
2. [Frontend-Only Files](#2-frontend-only-files)
3. [Backend-Only Files](#3-backend-only-files)
4. [Mixed Files](#4-mixed-files)
5. [Legacy Core — Do Not Touch](#5-legacy-core--do-not-touch)
6. [Route-to-View Map](#6-route-to-view-map)
7. [Form-to-Controller Map](#7-form-to-controller-map)
8. [Asset Dependency Map](#8-asset-dependency-map)
9. [Database Schema Summary](#9-database-schema-summary)
10. [Migration Notes & Risks](#10-migration-notes--risks)

---

## 1. File Classification Legend

| Label | Meaning |
|---|---|
| `FRONTEND_ONLY` | Pure HTML/CSS/JS — safe to extract to a modern frontend |
| `BACKEND_ONLY` | Pure PHP logic, no presentational value — stays in API layer |
| `MIXED` | Contains both rendering and PHP server-state calls — needs decomposition |
| `LEGACY_CORE_DO_NOT_TOUCH` | CI framework, tank_auth, or third-party libraries |

---

## 2. Frontend-Only Files

### 2.1 Views — Public

| File | Role |
|---|---|
| `application/views/local/index.php` | Home / city landing page |
| `application/views/biz/add.php` | Add / edit business form |
| `application/views/biz/show.php` | Business detail page (reviews, photos, map) |
| `application/views/biz/search.php` | Search results page |
| `application/views/biz/category.php` | Category browse listing |
| `application/views/biz/_list.php` | Business list partial |
| `application/views/photo/add.php` | Photo upload page (uses Uploadify widget) |
| `application/views/photo/show.php` | Photo gallery / single photo view |
| `application/views/photo/proccess.php` | Partial returned by AJAX after upload |
| `application/views/review/add.php` | Add / edit review form |
| `application/views/review/show.php` | Review detail (admin view) |
| `application/views/user/profile.php` | User profile page shell |
| `application/views/user/bio_container.php` | Bio sidebar partial |
| `application/views/user/feed_container.php` | Activity feed partial (AJAX target) |
| `application/views/user/network_container.php` | Following/followers list partial (AJAX target) |
| `application/views/user/setting_form.php` | Profile settings form |
| `application/views/user/user_review.php` | User's own review list page |
| `application/views/user/form.php` | Minimal user form partial |
| `application/views/message/message.php` | Message page shell (inbox/sent/compose/show) |
| `application/views/message/_box.php` | Inbox/sent list partial |
| `application/views/message/_form.php` | Compose message form partial |
| `application/views/message/_show.php` | Single message view partial |
| `application/views/report/report.php` | Report form partial |

### 2.2 Views — Auth

| File | Role |
|---|---|
| `application/views/auth/login_form.php` | Login page |
| `application/views/auth/register_form.php` | Registration page |
| `application/views/auth/forgot_password_form.php` | Forgot password page |
| `application/views/auth/reset_password_form.php` | Reset password page |
| `application/views/auth/change_password_form.php` | Change password page |
| `application/views/auth/change_email_form.php` | Change email page |
| `application/views/auth/send_again_form.php` | Resend activation email page |
| `application/views/auth/general_message.php` | Generic info message page |

### 2.3 Views — Email Templates

> These are server-rendered email bodies. Extract to a templating engine or transactional email service.

| File | Role |
|---|---|
| `application/views/email/activate-html.php` | Account activation email (HTML) |
| `application/views/email/activate-txt.php` | Account activation email (plain text) |
| `application/views/email/welcome-html.php` | Welcome email (HTML) |
| `application/views/email/welcome-txt.php` | Welcome email (plain text) |
| `application/views/email/forgot_password-html.php` | Forgot password email (HTML) |
| `application/views/email/forgot_password-txt.php` | Forgot password email (plain text) |
| `application/views/email/reset_password-html.php` | Reset password confirmation (HTML) |
| `application/views/email/reset_password-txt.php` | Reset password confirmation (plain text) |
| `application/views/email/change_email-html.php` | Change email verification (HTML) |
| `application/views/email/change_email-txt.php` | Change email verification (plain text) |

### 2.4 Views — Admin Panel

| File | Role |
|---|---|
| `application/views/admin/node/list.php` | City/category list (admin) |
| `application/views/admin/node/add.php` | Add/edit city or category (admin) |
| `application/views/admin/report/list.php` | Flagged content list (admin) |
| `application/views/admin/user/list.php` | User management list (admin) |
| `application/views/admin/user/add.php` | Edit user form (admin) |

### 2.5 CSS

| File | Notes |
|---|---|
| `css/screen.css` | Blueprint CSS grid — main public layout |
| `css/style.css` | Custom theme rules (site chrome, spacing, components) |
| `css/print.css` | Print stylesheet |
| `css/ie.css` | IE6/7 compatibility overrides |
| `css/colorbox.css` | Colorbox lightbox modal styles |
| `css/bootstrap.min.css` | Bootstrap 2.x — admin panel only |
| `css/bootstrap-responsive.min.css` | Bootstrap responsive — admin panel only |
| `uploadify/uploadify.css` | Uploadify widget styles — photo/add only |

### 2.6 JavaScript

| File | Notes |
|---|---|
| `js/jquery.js` | jQuery 1.x — global dependency |
| `js/common.js` | Custom app JS: `Utils` AJAX wrapper, `Login` panel, star rating, flower button, colorbox report form, follow/unfollow |
| `js/jquery.colorbox-min.js` | Colorbox lightbox — report modal, location map |
| `js/jquery.cookie.js` | Cookie helper — used by Uploadify to pass session cookie |
| `js/bootstrap.min.js` | Bootstrap 2.x JS — admin panel only |
| `js/ajaxfileupload.js` | Alternative AJAX file upload (appears unused — verify) |
| `js/ckeditor/` | CKEditor rich-text editor — referenced in biz/show review form |
| `uploadify/jquery.uploadify-3.1.js` | Uploadify full source |
| `uploadify/jquery.uploadify-3.1.min.js` | Uploadify minified — loaded in photo/add |
| `uploadify/uploadify.swf` | Flash fallback for Uploadify |

### 2.7 Images & Static Assets

| Path | Notes |
|---|---|
| `images/logo.png` | Site logo |
| `images/loading.gif` | Global AJAX spinner (`#loading` div) |
| `images/default_avatar*.gif` | User avatar placeholders (small/mid/big) |
| `images/rating-stars.png` | Star rating sprite |
| `images/stars.png` | Additional star sprite |
| `images/camera.png` | Photo section icon |
| `images/reply.png` | Reply icon |
| `images/add_button.gif` | Add button graphic |
| `images/plus.gif` | Plus icon |
| `images/flag.jpg` | Report/flag icon |
| `images/reviews.gif` | Reviews section icon |
| `images/flower.gif` | Flower "like" icon |
| `images/success-bg.gif` | Success state background |
| `images/s_success.png` | Success notification icon |
| `images/s_error.png` | Error notification icon |
| `images/topbar.png` | Top bar background |
| `images/grid.png` | Blueprint grid overlay (dev tool) |
| `images/grid.psd` | Photoshop source — not deployed |
| `images/colorbox/` | Colorbox UI sprites |
| `img/glyphicons-halflings.png` | Bootstrap icon sprite |
| `img/glyphicons-halflings-white.png` | Bootstrap icon sprite (white) |
| `uploadify/uploadify-cancel.png` | Cancel button for Uploadify queue |

---

## 3. Backend-Only Files

### 3.1 Controllers

| File | Class | Responsibility |
|---|---|---|
| `application/controllers/ucp.php` | `Ucp` | All auth flows: login, logout, register, activate, forgot/reset/change password, change email |
| `application/controllers/biz.php` | `Biz` | Business CRUD, search, category browse, AJAX children/location |
| `application/controllers/mcp.php` | `Mcp` | User profile, settings, avatar upload, follow/unfollow, feed, network, reviews |
| `application/controllers/photo.php` | `Photo` | Photo upload (Uploadify), processing/resizing, captions, gallery page, delete |
| `application/controllers/review.php` | `Review` | Review add (AJAX), edit, delete (admin), show (admin) |
| `application/controllers/pm.php` | `Pm` | Private messages: inbox, sent, compose, reply, show, delete |
| `application/controllers/report.php` | `Report` | Flagging content (AJAX POST, returns plain text) |
| `application/controllers/flower.php` | `flower` | Send flower "like" to review or photo (AJAX GET) |
| `application/controllers/local.php` | `Local` | City homepage / default route handler |
| `application/controllers/admin/node.php` | `Node` | Admin: manage city and category taxonomy nodes |
| `application/controllers/admin/report.php` | `Report` | Admin: view and delete flagged content |
| `application/controllers/admin/user.php` | `User` | Admin: list and edit user accounts |

### 3.2 Models

| File | Class | Tables Touched |
|---|---|---|
| `application/models/bizs.php` | `Bizs` | `biz` |
| `application/models/reviews.php` | `Reviews` | `review` |
| `application/models/photos.php` | `Photos` | `photo` |
| `application/models/messages.php` | `Messages` | `message` |
| `application/models/feeds.php` | `Feeds` | `feeds` |
| `application/models/friends.php` | `Friends` | `friends` |
| `application/models/flowers.php` | `Flowers` | `flower` |
| `application/models/reports.php` | `Reports` | `report` |
| `application/models/catsandcities.php` | `CatsAndCities` | `city`, `category`, `news_cats` |
| `application/models/cities.php` | `Cities` | `city` (thin wrapper) |
| `application/models/posts.php` | `Posts` | `posts` (unused/vestigial — verify) |
| `application/models/usermodel.php` | `Usermodel` | `users`, `user_profiles` |

### 3.3 Config

| File | Purpose |
|---|---|
| `application/config/config.php` | CI app config (base URL, session, etc.) |
| `application/config/database.php` | Database credentials |
| `application/config/routes.php` | URL routing rules |
| `application/config/autoload.php` | Auto-loaded libraries, helpers, models |
| `application/config/constants.php` | App-wide constants |
| `application/config/email.php` | Email transport config |
| `application/config/feeds.php` | Feeds pagination config |
| `application/config/hooks.php` | CI hooks |
| `application/config/tank_auth.php` | Tank Auth library settings |
| `application/config/mimes.php` | MIME type map |
| `application/config/doctypes.php` | HTML doctype map |
| `application/config/smileys.php` | Smiley map |
| `application/config/user_agents.php` | User agent strings |

### 3.4 Core Overrides

| File | Purpose |
|---|---|
| `application/core/MY_Model.php` | Base model with pagination helpers (`getPageData`) |
| `application/core/MY_Input.php` | CI input override |
| `application/core/MY_Security.php` | CI security override |

### 3.5 Helpers

| File | Purpose |
|---|---|
| `application/helpers/common_helper.php` | `avatar()`, `biz_cat_info()`, `biz_rate_stats()`, `biz_photo_stats()`, `getBizById()`, `mkdirs()`, `get_catorcity_by_field()` |
| `application/helpers/boostrap_helper.php` | Bootstrap HTML helper functions |
| `application/helpers/recaptcha_helper.php` | reCAPTCHA v1 integration |

### 3.6 Upload Logic

| Location | Description |
|---|---|
| `application/controllers/mcp.php :: uploadThumb()` | Avatar image upload + 3-size thumbnail generation |
| `application/controllers/photo.php :: uploadFile()` | Uploadify receiver — validates, resizes, saves to `upload/biz_photos/` |
| `application/controllers/photo.php :: proccess()` | Creates thumbnail, writes DB record, emits feed entry |
| `uploadify/check-exists.php` | Standalone PHP script for Uploadify duplicate check |

### 3.7 Entry Point & Server Config

| File | Purpose |
|---|---|
| `index.php` | CodeIgniter bootstrap entry point |
| `.htaccess` | Apache mod_rewrite — removes `index.php` from URLs |
| `system/.htaccess` | Blocks direct web access to CI system folder |

### 3.8 Database Schema

| File | Purpose |
|---|---|
| `db.sql` | Full schema + seed admin user |

---

## 4. Mixed Files

These files combine server-side PHP state with HTML rendering. They must be decomposed: extract data into API responses; replace PHP logic with frontend data binding.

| File | Mixed Concerns |
|---|---|
| `application/views/header.php` | **HTML layout** (nav, search box, logo) + **PHP auth state** (`$this->tank_auth->is_logged_in()`, city list, category nav, flash messages, inline login form) |
| `application/views/userpanel.php` | **Auth-conditional HTML** (admin link, logout, PM count, username, avatar) driven entirely by `$this->tank_auth` calls |
| `application/views/footer.php` | Mostly pure HTML — only risk is `</body></html>` coupling to header |
| `application/views/admin/header.php` | **Bootstrap admin shell** + `$this->tank_auth->get_username()`, `$this->uri->uri_string()` for active nav |
| `application/views/admin/footer.php` | Pure closing HTML — effectively frontend-only |

**Decomposition strategy:** Replace `$this->tank_auth` calls with a `/api/me` endpoint. Replace city/category nav data with `/api/cities` and `/api/categories`. Flash messages → standard JSON error/success in API responses.

---

## 5. Legacy Core — Do Not Touch

These are third-party or CI framework internals. They should be replaced (not refactored) during migration.

| Path | Reason |
|---|---|
| `system/` | CodeIgniter 2.x framework core — entire folder |
| `application/libraries/Tank_auth.php` | Tank Auth authentication library (~20k lines, PHP 5.x) |
| `application/libraries/MY_Session.php` | CI session override tied to Tank Auth |
| `application/libraries/phpass-0.1/` | phppass password hashing — replace with `password_hash()` in new stack |
| `application/models/tank_auth/users.php` | Tank Auth user model |
| `application/models/tank_auth/login_attempts.php` | Tank Auth brute-force tracking |
| `application/models/tank_auth/user_autologin.php` | Tank Auth remember-me tokens |
| `application/config/tank_auth.php` | Tank Auth configuration |
| `captcha/` | CI captcha font assets |

---

## 6. Route-to-View Map

### Public Routes

| URL Pattern | Controller Method | View Rendered |
|---|---|---|
| `/` | `local::city()` | `local/index` |
| `/{city-slug}` | `local::city()` | `local/index` |
| `/biz/:id` | `biz::show()` | `biz/show` |
| `/biz/:id/:page` | `biz::show()` | `biz/show` |
| `/biz/add` | `biz::add()` | `biz/add` |
| `/biz/edit/:id` | `biz::edit()` | `biz/add` (shared) |
| `/biz/del` (POST) | `biz::del()` | redirect |
| `/biz/search/:keyword` | `biz::search()` | `biz/search` |
| `/biz/c/:city/:cat` | `biz::c()` | `biz/category` |
| `/biz/get_children/:id/:table` | `biz::get_children()` | AJAX fragment (plain HTML options) |
| `/biz/location` (POST) | `biz::location()` | AJAX (plain text) |
| `/photo/upload/:bizid` | `photo::upload()` | `photo/add` |
| `/photo/uploadFile` (POST multipart) | `photo::uploadFile()` | AJAX JSON |
| `/photo/edit/:bizid` (POST) | `photo::edit()` | redirect |
| `/photo/:bizid/:id` | `photo::show()` | `photo/show` |
| `/photo/:bizid/:id/from/:username` | `photo::show()` | `photo/show` |
| `/photo/:bizid/page/:offset` | `photo::page()` | redirect to `/photo/:bizid/:id` |
| `/photo/del/:bizid/:id` | `photo::del()` | AJAX (plain text) |
| `/review/add` (POST) | `review::add()` | AJAX (redirect marker) |
| `/review/edit/:bizid/:id` | `review::edit()` | `review/add` |
| `/review/del` (POST) | `review::del()` | redirect |
| `/review/show/:id` | `review::show()` | `review/show` |
| `/report/add` (POST) | `report::add()` | AJAX (plain text) |
| `/flower/send/:bizid/:id/:type` | `flower::send()` | AJAX (plain text / number) |
| `/flower/user/:uid` | `flower::user()` | AJAX (number) |

### Auth Routes (`/ucp/`)

| URL Pattern | Controller Method | View Rendered |
|---|---|---|
| `/ucp/login` | `ucp::login()` | `auth/login_form` |
| `/ucp/login` (POST) | `ucp::login()` | AJAX (plain `1`) or redirect |
| `/ucp/logout` | `ucp::logout()` | `userpanel` (partial, AJAX) |
| `/ucp/register` | `ucp::register()` | `auth/register_form` |
| `/ucp/activate/:uid/:key` | `ucp::activate()` | `auth/general_message` |
| `/ucp/forgot_password` | `ucp::forgot_password()` | `auth/forgot_password_form` |
| `/ucp/reset_password/:uid/:key` | `ucp::reset_password()` | `auth/reset_password_form` |
| `/ucp/change_password` | `ucp::change_password()` | `auth/change_password_form` |
| `/ucp/change_email` | `ucp::change_email()` | `auth/change_email_form` |
| `/ucp/reset_email/:uid/:key` | `ucp::reset_email()` | `auth/general_message` |
| `/ucp/send_again` | `ucp::send_again()` | `auth/send_again_form` |
| `/ucp/userpanel` | `ucp::userpanel()` | `userpanel` (AJAX partial) |
| `/ucp/is_logged_in` | `ucp::is_logged_in()` | AJAX (`1` or `0`) |

### Member / Profile Routes (`/mcp/`, `/member/`)

| URL Pattern | Controller Method | View Rendered |
|---|---|---|
| `/member/:username` | `mcp::profile()` | `user/profile` |
| `/member/:username/network` | `mcp::profile()` | `user/profile` |
| `/member/:username/reviews` | `mcp::review()` | `user/user_review` |
| `/member/:username/reviews/:page` | `mcp::review()` | `user/user_review` |
| `/mcp/setting` | `mcp::setting()` | `user/setting_form` |
| `/mcp/follow` (POST) | `mcp::follow()` | AJAX (`1` or error) |
| `/mcp/unfollow` (POST) | `mcp::unfollow()` | AJAX (`1` or error) |
| `/mcp/feedData/:username` | `mcp::feedData()` | `user/feed_container` (AJAX partial) |
| `/mcp/network/:username/:type` | `mcp::network()` | `user/network_container` (AJAX partial) |

### Private Messages (`/pm/`)

| URL Pattern | Controller Method | View Rendered |
|---|---|---|
| `/pm/inbox` | `pm::inbox()` | `message/message` + `message/_box` |
| `/pm/sent` | `pm::sent()` | `message/message` + `message/_box` |
| `/pm/compose/:username` | `pm::compose()` | `message/message` + `message/_form` |
| `/pm/reply/:id` | `pm::reply()` | `message/message` + `message/_form` |
| `/pm/show/:id` | `pm::show()` | `message/message` + `message/_show` |
| `/pm/del/:id` | `pm::del()` | redirect |

### Admin Routes (`/admin/`)

| URL Pattern | Controller Method | View Rendered |
|---|---|---|
| `/admin/node/nodelist/:table` | `node::nodelist()` | `admin/node/list` |
| `/admin/node/add/:table` | `node::add()` | `admin/node/add` |
| `/admin/node/edit/:table/:id` | `node::edit()` | `admin/node/add` (shared) |
| `/admin/node/del/:table/:id` | `node::del()` | redirect |
| `/admin/node/order/:table` (POST) | `node::order()` | redirect |
| `/admin/report` | `admin/report::listing()` | `admin/report/list` |
| `/admin/report/del` (POST) | `admin/report::del()` | redirect |
| `/admin/user` | `admin/user::listing()` | `admin/user/list` |
| `/admin/user/edit/:id` | `admin/user::edit()` | `admin/user/add` |

---

## 7. Form-to-Controller Map

### Auth Forms

| Form (View) | Method | Action URL | Fields |
|---|---|---|---|
| Quick login (header inline) | POST (AJAX) | `/ucp/login` | `login`, `password`, `remember`, `inajax=1` |
| Login page | POST | `/ucp/login` | `login`, `password`, `remember`, `captcha`? |
| Register | POST | `/ucp/register` | `username`, `email`, `password`, `confirm_password`, `captcha`? |
| Forgot password | POST | `/ucp/forgot_password` | `login` |
| Reset password | POST | `/ucp/reset_password/:uid/:key` | `new_password`, `confirm_new_password` |
| Change password | POST | `/ucp/change_password` | `old_password`, `new_password`, `confirm_new_password` |
| Change email | POST | `/ucp/change_email` | `email`, `password` |
| Resend activation | POST | `/ucp/send_again` | `email` |

### Business Forms

| Form (View) | Method | Action URL | Fields |
|---|---|---|---|
| Add business | POST | `/biz/add` | `city_id`, `district_id`, `name`, `addrs1`, `addrs2`, `catid_1`, `catid_2`, `tel`, `website`, `about`, `location_x`, `location_y`, `rating`*, `review`*, `with_review`* |
| Edit business | POST | `/biz/edit/:id` | same as add minus rating/review |
| Search | POST | `/biz/search` | `q` |
| City/cat selector (AJAX) | GET | `/biz/get_children/:id/:table` | URL params only |
| Map pin drag (AJAX) | POST | `/biz/location` | `id`, `lat`, `lng` |

### Review Forms

| Form (View) | Method | Action URL | Fields |
|---|---|---|---|
| Add review (inline biz/show) | POST (AJAX) | `/review/add` | `bizid`, `rating`, `content` |
| Edit review | POST | `/review/edit/:bizid/:id` | `rating`, `content` |
| Delete review (admin) | POST | `/review/del` | `bizid`, `id` |

### Photo Forms

| Form (View) | Method | Action URL | Fields |
|---|---|---|---|
| Upload photos (Uploadify) | POST multipart | `/photo/uploadFile` | `Filedata` (file), `bizid`, `browser_cookie` |
| Save captions | POST | `/photo/edit/:bizid` | `caption[{id}]` array |
| Delete photo (AJAX) | GET | `/photo/del/:bizid/:id` | URL params only |

### User / Profile Forms

| Form (View) | Method | Action URL | Fields |
|---|---|---|---|
| Profile settings | POST multipart | `/mcp/setting` | `website`, `about_me`, `city`, `picture` (file) |
| Follow user (AJAX) | POST | `/mcp/follow` | `username` |
| Unfollow user (AJAX) | POST | `/mcp/unfollow` | `username` |

### Messaging Forms

| Form (View) | Method | Action URL | Fields |
|---|---|---|---|
| Compose / reply | POST | `/pm/compose` | `reciever`, `title`, `content` |

### Report / Flag Form

| Form (View) | Method | Action URL | Fields |
|---|---|---|---|
| Report content (colorbox AJAX) | POST | `/report/add` | `url`, `comment` |

### Admin Forms

| Form (View) | Method | Action URL | Fields |
|---|---|---|---|
| Add city or category | POST | `/admin/node/add/:table` | `name`, `slug`, `order`, `parent_id` |
| Edit city or category | POST | `/admin/node/edit/:table/:id` | `name`, `slug`, `order`, `parent_id` |
| Reorder nodes (drag) | POST | `/admin/node/order/:table` | `order[]` |
| Edit user | POST | `/admin/user/edit/:id` | `role`, `activated`, `banned`, `ban_reason` |
| Delete reports | POST | `/admin/report/del` | `id[]` |

---

## 8. Asset Dependency Map

### Public Pages

| Page | CSS | JS | Images |
|---|---|---|---|
| All pages | `screen.css`, `style.css`, `print.css`, `ie.css`, `colorbox.css` | `jquery.js`, `jquery.colorbox-min.js`, `common.js` | `loading.gif`, `logo.png` |
| Home (`local/index`) | ← same | ← same | `rating-stars.png`, avatar images |
| Business detail (`biz/show`) | ← same | ← same + CKEditor (inline) | `stars.png`, `camera.png`, `flag.jpg`, `flower.gif` |
| Photo upload (`photo/add`) | ← same + `uploadify.css` | ← same + `jquery.uploadify-3.1.min.js` + `jquery.cookie.js` | `uploadify-cancel.png` |
| Photo gallery (`photo/show`) | ← same | ← same | review/photo assets |
| Auth pages (`/ucp/`) | ← same | ← same | `default_avatar*.gif` |
| Profile pages (`/mcp/`, `/member/`) | ← same | ← same | avatar images, `add_button.gif`, `reply.png` |

### Admin Pages

| Page | CSS | JS |
|---|---|---|
| All admin pages | `bootstrap.min.css`, `bootstrap-responsive.min.css` | `jquery.js`, `bootstrap.min.js` |

> **Note:** Admin and public panels use completely separate CSS stacks (Blueprint vs Bootstrap 2). No CSS is shared.

### AJAX Endpoints (No View — Data Only)

These endpoints return plain text, a number, an HTML fragment, or JSON. They have no asset dependencies.

| Endpoint | Response Type |
|---|---|
| `/ucp/login` (inajax) | `1` or `<!--_ERROR-->…` or `<!--_REDIRECT-->…` |
| `/ucp/userpanel` | HTML partial |
| `/ucp/logout` | HTML partial |
| `/ucp/is_logged_in` | `1` or `0` |
| `/biz/get_children/…` | HTML `<option>` fragment |
| `/biz/location` | `1` |
| `/photo/uploadFile` | JSON `{type, view|msg}` |
| `/photo/del/…` | `1` or `<!--_ERROR-->…` |
| `/review/add` | `<!--_REDIRECT-->…` or `<!--_ERROR-->…` |
| `/flower/send/…` | flower count (integer) or `<!--_ERROR-->…` |
| `/flower/user/…` | integer |
| `/report/add` | `1` or error string |
| `/mcp/follow` / `/mcp/unfollow` | `1` or `<!--_ERROR-->…` |
| `/mcp/feedData/…` | HTML partial |
| `/mcp/network/…` | HTML partial |

### Custom AJAX Protocol

`common.js` uses a bespoke error-signalling convention. Response bodies are scanned for:

- `<!--_ERROR-->message<!--_ERROR-->` → `alert(message)` and abort
- `<!--_REDIRECT-->url<!--_REDIRECT-->` → `window.location.href = url`
- `<!--_LOGIN_REQUIRED-->` → show inline login panel

**Migration risk:** Every API endpoint must be rewritten to emit proper HTTP status codes and JSON error bodies. This convention must be replaced globally.

---

## 9. Database Schema Summary

| Table | Purpose | Key Relations |
|---|---|---|
| `users` | Auth accounts (tank_auth) | PK: `id`; `role` ∈ `{user, admin}` |
| `user_profiles` | Extended profile data | FK → `users.id`; also stores `newpm` counter |
| `user_autologin` | Remember-me tokens | FK → `users.id` |
| `login_attempts` | Brute-force tracking | by `ip_address` + `login` |
| `ci_sessions` | PHP session storage | `session_id` PK |
| `biz` | Business listings | FK → `city.id`, `category.id`; owned by `users.id` |
| `category` | Business categories (2-level tree) | `parent_id` self-ref |
| `city` | Cities and neighborhoods (2-level) | `parent_id` self-ref |
| `news_cats` | News categories (unused/vestigial) | same structure as category |
| `review` | User reviews of businesses | FK → `biz.id`, `users.id` |
| `photo` | Business photos | FK → `biz.id`, `users.id`; `id` is a `uniqid()` hash |
| `feeds` | Activity feed entries | polymorphic `idtype` + `objectid` |
| `friends` | Follow relationships | `uid` follows `fid` |
| `flower` | "Like" on reviews or photos | composite PK `objectid+sender+idtype` |
| `message` | Private messages | `inbox`/`sentbox` soft-delete flags |
| `report` | User-flagged content URLs | `url` + `comment` |

---

## 10. Migration Notes & Risks

### High Priority

| Risk | Detail |
|---|---|
| **AJAX protocol replacement** | The `<!--_ERROR-->` / `<!--_REDIRECT-->` / `<!--_LOGIN_REQUIRED-->` string-in-body convention is used across all AJAX endpoints. Every endpoint and every JS caller must be migrated simultaneously or via adapter. |
| **Session-coupled upload** | Photo upload (`photo/uploadFile`) receives the CI session cookie via a `browser_cookie` POST field, because Flash (Uploadify SWF) cannot send cookies. Replacing Uploadify removes this problem but requires a new upload widget. |
| **`mysql_real_escape_string`** | Used in `biz::search()`. This function was removed in PHP 7. The app will crash on PHP 7+ as-is. Must be replaced with parameterized queries. |
| **Tank Auth coupling** | `$this->tank_auth` is called directly inside views (`header.php`, `userpanel.php`, `admin/header.php`). These calls must be replaced with data passed from the controller before frontend extraction is possible. |
| **Flash storage** | Avatar and business photos are written to `upload/` relative to the document root. The new backend must replicate or migrate this storage path, or switch to object storage. |

### Medium Priority

| Risk | Detail |
|---|---|
| **Uploadify (Flash)** | Uploadify relies on Adobe Flash (`uploadify.swf`). Flash is end-of-life. Must be replaced with a modern file upload widget (e.g. FilePond, Dropzone). |
| **Blueprint CSS grid** | The public frontend uses Blueprint CSS (not Bootstrap). Components will need to be rewritten in a modern CSS system. |
| **Inline JS in views** | `photo/add.php` contains an inline `<script>` block with Uploadify config and business ID. This data must be injected via a data attribute or JS config object from the server. |
| **reCAPTCHA v1** | `recaptcha_helper.php` uses the decommissioned reCAPTCHA v1 API. Must migrate to reCAPTCHA v3 or alternative. |
| **`posts` model** | `application/models/posts.php` has no corresponding controller or view. Likely a dead vestige — confirm before deleting. |
| **`ajaxfileupload.js`** | Present in `js/` but not referenced in any view. Likely dead code. |
| **`news_cats` table** | Created in schema, managed by admin Node controller, but no public-facing route uses it. Vestigial feature. |
| **Admin `report/del` bug** | `admin/controllers/report.php :: del()` calls `array_walk()` with no arguments — this will fatal-error on execution. Function is currently broken. |

### Low Priority

| Risk | Detail |
|---|---|
| **Shared view for add/edit** | `biz/add.php` and `admin/node/add.php` are reused for both add and edit. The view detects mode via `isset($biz['id'])`. Splitting these into dedicated components simplifies the frontend. |
| **`message/message.php` shell pattern** | The message controller loads a shell view (`message/message`) and passes a `$partial` variable pointing to a sub-view. This is a manual component slot system — can map directly to a layout slot in a modern framework. |
| **Blueprint `span-*` classes** | Grid classes like `span-5`, `span-12` are Blueprint-specific. All layout must be rewritten; they are not compatible with Bootstrap or Tailwind. |
| **Image upload path derivation** | Avatar path uses zero-padded user ID split into directory segments (`000/00/00/`). Photo path uses a custom hash function. Both must be documented and preserved or migrated during storage refactor. |
