# VisitSecure KE

A multi-tenant Visitor & Booking Management System (MIS) for organisations and buildings in Kenya. Replaces the paper sign-in book with QR-coded digital gate passes, host approvals, event scheduling, and email notifications.

Built with **PHP (no framework), MySQL (PDO), Tailwind CSS (CDN), and vanilla JS** — designed to run on **Laragon**.

---

## 1. Requirements

- Laragon (with Apache + PHP 8.1+ + MySQL)
- PHP extensions: `pdo_mysql`, `gd` (for QR code generation), `openssl` (usually bundled)
- A Gmail account with an **App Password** if you want real email notifications (optional at first)

---

## 2. Installation (Laragon)

### Step 1 — Place the project
Copy/extract this entire `visitsecure-ke` folder into:
```
C:\laragon\www\visitsecure-ke
```

### Step 2 — Create the database
1. Open Laragon, click **Database** (or HeidiSQL/phpMyAdmin) to open a MySQL client.
2. Run the SQL in `database/schema.sql`. This creates the `visitsecure_ke` database, all tables, and seed data (platform org + a demo organisation).

   Alternatively, from a terminal:
   ```bash
   cd C:\laragon\www\visitsecure-ke
   mysql -u root -p < database/schema.sql
   ```
   (Default Laragon MySQL root password is usually empty — just press Enter.)

### Step 3 — Seed demo accounts
Run the seed script once to set guaranteed-working passwords for all test roles:

- Visit in your browser: `http://localhost/visitsecure-ke/database/seed.php`
- Or via CLI: `php database/seed.php`

This creates/resets the following logins:

| Role | Email | Password |
|------|-------|----------|
| Super Admin | `admin@visitsecure.ke` | `Admin@123` |
| Org Admin | `orgadmin@bihi.demo` | `Demo@123` |
| Gateman | `gateman@bihi.demo` | `Demo@123` |
| Host | `host@bihi.demo` | `Demo@123` |
| Event Manager | `events@bihi.demo` | `Demo@123` |
| Visitor | `visitor@bihi.demo` | `Demo@123` |

The demo org **Bihi Properties Ltd** is set up with Bihi Towers → Floor 6 → Conference 6, and the host is assigned to that room.

### Step 4 — Configure the app
Open `app/config/app.php` and set `BASE_URL` to match how you'll access the site:

- If using a Laragon auto virtual host (recommended): `http://visitsecure-ke.ke`
  (Laragon auto-creates `*.test` domains for folders in `www` — just enable "Auto Virtual Hosts" in Laragon's menu and restart Apache.)
- If using plain localhost: `http://localhost/visitsecure-ke/public`

Open `app/config/database.php` and confirm `DB_HOST`, `DB_USER`, `DB_PASS` match your Laragon MySQL setup (defaults are usually `127.0.0.1` / `root` / empty password).

### Step 5 — Configure email (optional but recommended)
Open `app/config/mail.php` and set:
```php
define('MAIL_USERNAME', 'youraddress@gmail.com');
define('MAIL_PASSWORD', 'your16charapppassword');
define('MAIL_FROM_EMAIL', 'youraddress@gmail.com');
```

To get a Gmail App Password:
1. Enable 2-Step Verification on the Gmail account: https://myaccount.google.com/security
2. Generate an app password: https://myaccount.google.com/apppasswords
3. Paste the 16-character password (no spaces) into `MAIL_PASSWORD`.

Each organisation can later override this with their own Gmail sender from **Org Admin → Settings**.

### Step 6 — Visit the site
- With auto virtual host: `http://visitsecure-ke.ke`
- Without: `http://localhost/visitsecure-ke/public`

You should see the VisitSecure KE landing page.

---

## 3. Logging in

Run `php database/seed.php` first if you haven't already (see Step 3 above).

**Platform Super Admin** (manages all organisations & subscriptions):
- Email: `admin@visitsecure.ke`
- Password: `Admin@123`

**Demo organisation accounts** (Bihi Properties Ltd — all use password `Demo@123`):

| Role | Email |
|------|-------|
| Org Admin | `orgadmin@bihi.demo` |
| Gateman | `gateman@bihi.demo` |
| Host | `host@bihi.demo` |
| Event Manager | `events@bihi.demo` |
| Visitor | `visitor@bihi.demo` |

**To create your own organisation**, go to `/signup/organization` and register — this creates the organisation and your Org Admin login in one step. From the Org Admin dashboard you can then:
1. Add a Building (e.g. "Bihi Towers")
2. Add Floors to that building (e.g. "Floor 6")
3. Add Rooms/firms to that floor (e.g. "Conference 6", "Cap Africa Consulting")
4. Add staff: Gatemen, Hosts (assigned to a room), and Event Managers

**To test as a visitor**, go to `/signup/visitor` to create a visitor account, then book a visit from the Visitor dashboard.

---

## 4. How the core flows work

- **Host invites a visitor** → auto-approved, QR gate pass generated and emailed immediately.
- **Visitor books a visit themselves** → goes to `pending`, host must approve from their dashboard before a gate pass is usable.
- **Walk-in with no gate pass** → Gateman registers them under "Register Walk-in Visitor", host gets notified, and once approved a QR pass is generated.
- **Event Manager schedules an event** → can register visitors directly (auto-approved gate pass) for that event/room.
- **Gateman scans a QR pass** at `/gateman/scan` (camera-based, with manual token entry fallback) to check visitors in/out.

---

## 5. File structure overview

```
visitsecure-ke/
├── app/
│   ├── config/        # app.php, database.php, mail.php
│   ├── core/           # Router, DB, Auth, QrCode, Mailer, NotificationService
│   ├── controllers/    # one file per route, grouped by role
│   ├── models/         # Organization, User, Building, Floor, Room, Visit, Event, Notification...
│   ├── middleware/      # role-based access guards
│   └── helpers/         # global functions (view(), flash(), csrf, etc.)
├── views/                # matching view files per controller, plus layouts/partials
├── routes/web.php        # all URL → controller mappings
├── public/               # web root: index.php (front controller), assets, uploads
├── database/             # schema.sql, seed.php
├── vendor/phpqrcode/      # bundled offline QR code generator (no external API)
└── storage/logs/          # mail failure logs, etc.
```

Routes are clean (no `.php` shown) via `.htaccess` rewriting everything through `public/index.php`.

---

## 6. Notes & next steps

- Email sending uses a minimal native SMTP client (no Composer/PHPMailer dependency) — see `app/core/Mailer.php`. If email fails, the booking/check-in flow still completes; failures are logged to `storage/logs/mail.log`.
- QR codes are generated fully offline using the bundled `phpqrcode` library — no internet/API dependency at runtime.
- Subscriptions are activated/suspended manually by the Super Admin (Organisations → Manage → Subscription management) — no payment gateway is wired up yet.
- All tenant data is isolated by `org_id` in a single shared database/table model.
