# Barangay Tigbao Water District — Water Billing & Online Payment System

> ## v9 — QR Ph payments (no payment gateway / API) + online hosting + phone install
> - **Payment:** the consumer pays by scanning the district's own **QR Ph** with GCash, Maya or any bank app; the money goes
>   straight into the district account. The Treasurer then confirms it (Payments → *Check QR payments*). Cash still works.
>   The old GCash/Maya/MariBank checkout (PayMongo API and the fake demo checkout) is removed from the Pay Bill page.
>   Admin saves the QR once at **Payments → QR setup** (with a ₱1 test QR).
> - **Hosting:** see **DEPLOY.md** (cPanel/shared hosting or Docker). Settings come from `config/local.php` or environment variables.
> - **Phones:** every page now links the app manifest, so Android Chrome / iPhone Safari can install it (DEPLOY.md §5).
> - `DEMO_MODE` is now OFF by default (real IP addresses in the audit log, no fake data).
> - `config/mail.php` no longer contains a Gmail password.

> **Roles aligned with Chapter I (Scope and Limitation).** Each user now has only the pages and tasks the study lists:
>
> | User | Pages / tasks |
> |------|---------------|
> | Admin | Dashboard, Consumers & Billing (customers, meter readings, billing, water rates), Payments (prints Original + Duplicate office copy of the Official Receipt), Reports + Audit log, SMS & Email, Staff accounts, My Profile |
> | Water Reader | Dashboard, Meter Readings |
> | Treasurer | Dashboard, Billing (review bills, read only), Payments (check QR Ph payments, confirm / collect cash, print the Original receipt), Reports |
> | Consumer | Dashboard, My Bills, Consumption, Payments, Billing History, Messages (tabs of one page), Profile |
>
> Removed because the study does not list them: Bank Transfer payment method (only GCash, Maya, MariBank and Cash remain), Water Reader History and Profile pages, Treasurer Profile page, cash confirmation on the Admin Payments page (it is the Treasurer's task), the Treasurer's Duplicate office copy (Admin only), and the Consumer's decorative "Notification Preferences" box. The role is now called "Water Reader" on screen (the database value `meter_reader` and the `meter-reader/` folder are unchanged).

A PHP + MySQL starter system for XAMPP, with separate Customer and
Administrator portals.

## What changed in v8 (phone fit + merged pages)

**Phone layout** (`assets/css/mobile.css`, loaded last by `includes/header.php` and the login page)
- On a phone (760px wide or less) the left menu becomes a bar fixed at the bottom, so every menu item is always visible.
- Text, cards and forms are smaller and use the full width.
- Tables turn into one small card per row (`assets/js/table-enhance.js` copies each column title onto its cells), so nothing scrolls sideways.
- To undo it all: remove the `mobile.css` link from `includes/header.php` and `includes/shared_pages.php`.

**Pages that did the same job are now one page with tabs** (`includes/tabs.php`)

| Now | Tabs | Replaces |
|-----|------|----------|
| Admin > Billing (`admin/billing.php`) | Bills, Readings, Rates | Billing, Meter Readings, Water Rates |
| Admin > Payments (`admin/payments.php`) | Payments, Receipt check | Payments, Payment Check |
| Admin > Reports (`admin/reports.php`) | Reports, Audit log | Reports, Audit Logs |
| Consumer > Bills (`customer/bills.php`) | Bills, Payments, Usage | My Bills, Payments, Consumption |
| Consumer > Profile (`customer/profile.php`) | (one page) | Profile, Settings |

The old file names (`admin/readings.php`, `admin/rates.php`, `admin/logs.php`, `admin/payment-check.php`,
`customer/payments.php`, `customer/consumption.php`, `customer/settings.php`) still exist as one-line redirects,
so old bookmarks keep working.

**Shared code for admin and consumer** (`includes/shared_pages.php`): login, logout and the printable invoice / receipt
each exist once; `admin/login.php`, `customer/login.php`, `admin/logout.php`, `customer/logout.php`, `admin/print.php`
and `customer/print.php` are now tiny files that call it.

Menu: Admin has 8 items (was 11), Consumer has 5 (was 8).

## Requirements
- XAMPP (Apache + MySQL + PHP 8.x)
- A modern browser

## Install (XAMPP)

1. **Start Apache and MySQL** in the XAMPP Control Panel.

2. **Copy the project folder** into your XAMPP `htdocs` directory so you have:
   ```
   C:\xampp\htdocs\barangay_tigbao_water_district\
   ```

3. **Create the database.** Open `http://localhost/phpmyadmin`, and import
   `database/database.sql`. This creates the `brgy_tigbao_water` database and
   all tables (`users`, `meter_readings`, `bills`, `payments`,
   `notifications`, `audit_logs`, `sync_queue`).

4. **Run the setup script** once, in your browser:
   ```
   http://localhost/barangay_tigbao_water_district/setup.php
   ```
   This creates a development admin account, a development customer account,
   and one sample meter reading + bill so the dashboards aren't empty.

   **Delete or rename `setup.php` after running it once**, and change the
   development passwords before real use.

5. **Open the system:**
   ```
   http://localhost/barangay_tigbao_water_district/
   ```

## Development accounts

| Role     | Email                 | Password        |
|----------|------------------------|-----------------|
| Admin    | admin@tigbao.local     | Admin@12345     |
| Customer | juan@tigbao.local      | Customer@12345  |

Change both passwords (Profile page for the customer; update directly in the
database or add an admin profile page for the admin) before real deployment.

## Look and feel

`assets/css/theme.css` (loaded after `style.css` on every page) puts the Municipal
Hall photo (`assets/images/bg-tigbao.jpg`) behind the whole system and gives every
section its own color in the sidebar, page title, tables, buttons and cards:

| Admin           | Color  | Customer        | Color  |
|-----------------|--------|-----------------|--------|
| Dashboard       | indigo | Dashboard       | indigo |
| Customers       | teal   | My Bills        | orange |
| Meter Readings  | amber  | Consumption     | sky    |
| Billing         | orange | Payments        | green  |
| Payments        | green  | Billing History | violet |
| Reports         | violet | Profile         | teal   |

Change the colors at the top of `theme.css` (the `--c-...` variables). To swap the
photo, replace `assets/images/bg-tigbao.jpg` (a larger, sharper photo looks best).

## Online / offline synchronization

The office PC (XAMPP) keeps working with no internet. When internet is available it
syncs both ways with the online copy of the system: bills and readings created at
the office go online, and payments made by customers online come back. If the same
row was changed on both sides, the newest change wins. A badge at the bottom-right
of every admin page shows Online / Offline / Syncing. It syncs when the page opens,
when internet returns, and every minute.

Set-up (once):

1. Put the whole project on the online host too, and import `database/database.sql`
   there. Set `config/database.php` on each machine.
2. Edit `sync/config.php` on each machine:
   office PC `'mode' => 'local'` plus `remote_url`; online host `'mode' => 'online'`.
   Use the same long random `api_key` on both.
3. Export a backup in phpMyAdmin, then open this once on EACH machine:
   `.../barangay_tigbao_water_district/sync/prepare_db.php?key=YOUR_API_KEY`
   Every line should say `OK`.
4. Run `setup.php` (the sample accounts) on the office PC only, not the online host;
   the accounts reach the online host through the sync.
5. Test: turn Wi-Fi off and add a meter reading (badge turns red), turn it on again
   (blue, then green), then check the reading appears in the online database.

Notes:
- Customers, admins, readings, bills, payments, notifications and all sync.
  Password hashes travel with `users`, so use HTTPS and a strong `api_key` online.
- Keep the PC clock on automatic time (newest-change-wins uses the timestamps).
- If you later add a DELETE feature, use a status flag instead (e.g. bills `void`);
  a deleted row would otherwise come back on the next sync.
- `sync_queue` (the old placeholder table) is not used by this sync.

## SMS & Email (Admin -> SMS & Email, below Audit Logs)

Customers receive a real text message and a real email when a bill is generated,
when a bill is almost due or overdue, and when a payment is received.

- **Record:** the page lists every SMS and email sent (date, customer, phone/email,
  subject, message, delivered or failed). Filter by SMS or Email.
- **Send a notice:** to one customer or all active customers (announcements, interruptions...).
- **Bill reminders:** lists unpaid bills due within 3 days or overdue, with a button to
  remind one customer or everyone. The same reminder is not sent twice within 24 hours.
- **Check your connection:** send a test SMS / email to yourself.
- **Wording:** `includes/notices.php` (search `@@NOTICE-WORDING`).
- **What customers see:** every message also appears on the customer's **Messages** page,
  as a phone-style text chat and a Gmail-style inbox, whether or not real SMS / Gmail is
  connected (`customer/messages.php`, search `@@MY-MESSAGES`).

### Connect them (one time)
1. **Email (free):** fill in `config/mail.php` with a Gmail address and a Gmail App Password
   (steps are written at the top of that file), set `MAIL_ENABLED` to `true`.
2. **SMS:** create an account at semaphore.co, buy a few credits, then fill in
   `config/sms.php` and set `SMS_ENABLED` to `true`.
3. Make sure each customer has a **real mobile number and a real email address**
   (made-up addresses ending in `.local` are skipped on purpose).
4. Open Admin -> SMS & Email -> **Check your connection** and send yourself a test.

Both need internet access on the computer running XAMPP.


## Printable invoice and receipt (admin and customer)

- **Customer:** My Bills -> **Print Invoice** (any bill) and **Print Receipt** (paid bills).
- **Admin:** Billing -> **Print Invoice** / **Print Receipt** on each bill, and
  Payments -> **Print Receipt** on paid payments. Admins can print for any customer.
- Both open a clean A4 page (no menu) with a Print button. A customer can only
  open their own documents. Layout lives in `includes/print_docs.php`.
- The invoice's rate table is read from `water_charge_breakdown()` in
  `includes/billing.php`; change the tariff there and the bill and invoice stay in step.

## Background sync

The Online / Offline badge only syncs while an admin page is open. To sync all day
by itself, create a Windows Task Scheduler task: Action = start `sync\auto-sync.bat`,
Trigger = repeat every 5 minutes. Results are appended to `sync/sync-log.txt`.


## Payment mode (developer note)

Payments are **QR Ph + Cash** only (`includes/payment_methods.php`, `includes/qrph.php`, `customer/qr-pay.php`,
`treasurer/payments.php` tab *Check QR payments*, `admin/payments.php` tab *QR setup*).
`confirm_payment()` is the only place a bill is marked paid, and for QR Ph only a Treasurer can call it.
`customer/checkout.php` and `customer/demo-gcash.php` now only redirect (the old demo checkout marked bills paid without money).
`api/paymongo-webhook.php` and `config/paymongo.php` are left in the project but unused (the webhook refuses every request while no secret is set).

`config/demo.php` -> `DEMO_MODE` (off unless the environment variable `TIGBAO_DEMO=1`) now only affects audit-log IP display
and how unconnected SMS/e-mail are recorded; it no longer touches payments.

## Report charts (Admin -> Reports)

Each report now has summary cards plus a bar graph and a pie graph, above its table:
- **Collection:** collections per month (bar) and GCash vs Cash (pie).
- **Consumption:** top consumers (bar) and share of total consumption (pie). The
  highest / most active consumer is highlighted in gold in both charts, in the
  table row, and in a "Top consumer" card. A card also shows the number of active consumers.
- **Unpaid bills:** unpaid amount per customer (bar) and overdue vs not-yet-due (pie).
  Red = overdue, amber = not yet due.

The charts are drawn by `assets/js/charts.js` (no internet or chart library needed).
Hover a bar or slice to see its exact value. PDF/Excel exports are unchanged.

## Finding things in VS Code

Open **PROJECT_MAP.md** (then press Ctrl+Shift+V). It lists every screen, file,
color and database table with clickable links. Every part of the code carries a
search tag starting with `@@` (example: `@@BILLING`, `@@TARIFF`, `@@COLORS`).
Press **Ctrl+Shift+F**, type the tag and click the result to jump straight to it.

## Changing the water rate, discount and penalty

Admin -> **Water Rates**: one price per cubic meter, then the discount % (paid before the due date) and the penalty % (paid after). New bills and payments use the new values at once; bills that already exist keep their water charge.

## Small usability additions

- **Show/hide password:** every password field gets an eye button automatically
  (`assets/js/password-toggle.js`).
- **Icons:** the home page and login titles show an admin/customer icon (`@@ICON-LOGIN`).
  (`admin/logs.php`, search `@@LOGS-EXPORT`).
- **Phone numbers:** input boxes accept digits only, exactly 11 numbers, starting with 0
  (e.g. `09171234567`). Tables and printouts show it as `+63 917 123 4567`
  (`display_phone()` in `includes/bootstrap.php`, search `@@PHONE-DISPLAY`).
- **Field sizing:** short values (phone, account number, message subject) use a
  narrower box instead of stretching the full width (search `@@FIELD-SIZE`).

## Project structure

```
barangay_tigbao_water_district/
├── index.php              Landing page (uses assets/images/bg-tigbao.jpg)
├── setup.php               One-time seed script — delete after first run
├── config/
│   ├── database.php         DB connection settings
│   ├── sms.php                Semaphore SMS API key + settings
│   ├── paymongo.php           PayMongo API key + webhook secret
│   └── .htaccess             Blocks direct web access to this folder
├── includes/
│   ├── bootstrap.php         Sessions, CSRF, flash messages, helpers
│   ├── auth.php               Login/logout/role checks
│   ├── billing.php            Tariff + penalty calculation (SAMPLE rates)
│   ├── payments.php            confirm_payment()/fail_payment() — the only
│   │                            place a payment/bill is marked paid
│   ├── sms.php                 Semaphore SMS sending + notify_customer()
│   ├── paymongo.php            PayMongo API wrapper + webhook signature check
│   ├── reports.php             Shared report queries (screen + export)
│   ├── pdf_lib.php             Dependency-free PDF writer
│   ├── xlsx_lib.php            Dependency-free XLSX writer
│   ├── header.php / footer.php  Shared page chrome
├── api/
│   └── paymongo-webhook.php  Public webhook — confirms real GCash payments
├── customer/                Customer portal (login, dashboard, bills,
│                             consumption, payments, receipts, profile...)
├── admin/                   Admin portal (dashboard, customers, meter
│                             readings, billing, payments, reports, logs)
├── database/database.sql    Full schema
└── assets/
    ├── css/style.css       Base layout
    ├── css/theme.css       Colors + Municipal Hall background (loaded after style.css)
    └── images/bg-tigbao.jpg  Municipal hall background photo
```

## How the core workflow fits together

```
Customer created (admin) → Meter Reading (admin) → Bill generated (admin, SMS sent)
        → Customer pays:
            GCash → PayMongo checkout → PayMongo webhook confirms → Receipt + SMS
            Cash  → pending → Admin confirms received → Receipt + SMS
```

Consumption is always `current_reading - previous_reading`, and the meter
reading form refuses to save a current reading lower than the last one on
file for that customer.

## What's already implemented
- Separate Customer and Admin login, each checking role on every page
  (`require_role()` in `includes/auth.php`) — the admin can't reach customer
  pages just by guessing a URL, and vice versa.
- Password hashing (`password_hash`/`password_verify`), session ID
  regeneration on login, CSRF tokens on every form, and prepared statements
  throughout (no raw SQL concatenation).
- Meter reading entry with previous-reading lookup and the
  "current can't be lower than previous" validation.
- Invoice generation from an unbilled meter reading, using a tiered tariff
  function that lives in one place (`includes/billing.php`).
- Real GCash payment flow via PayMongo (hosted checkout + webhook
  confirmation) or Cash (confirmed by an admin), reference number
  generation, and a printable + downloadable-PDF digital receipt.
- Customer: dashboard, bills, consumption history, billing history, payment
  history, profile/password change.
- Admin: dashboard KPIs, customer management, meter readings, billing,
  payments (incl. confirming cash payments), reports (collection /
  consumption / unpaid bills) with PDF/Excel export, audit logs.
- SMS notifications (Semaphore) and real GCash payments (PayMongo), both
  described in detail below.

## Newly added: real GCash gateway, real SMS, PDF/Excel export

- **GCash payments now go through [PayMongo](https://paymongo.com)**, a
  BSP-licensed payment service provider. `customer/pay.php` creates a real
  GCash Source and redirects the customer to PayMongo's hosted checkout;
  the bill is marked paid **only** by `api/paymongo-webhook.php`, after it
  verifies PayMongo's webhook signature server-side — the browser is never
  trusted. Cash payments are recorded as "pending" and confirmed manually
  by an admin on the Payments page (`admin/payments.php`), which calls the
  same `confirm_payment()` function (`includes/payments.php`) used by the
  webhook, so a receipt/SMS is issued the same way either way.
  Setup: fill in `config/paymongo.php` (secret key + webhook signing
  secret from your PayMongo dashboard) and set `PAYMONGO_ENABLED` to `true`.
- **SMS notifications are wired to [Semaphore](https://semaphore.co)**
  (`includes/sms.php`), a Philippine SMS gateway. Every bill-generated and
  payment-received notification now goes through `notify_customer()`,
  which always logs an in-app notification and additionally sends + logs
  an SMS when the customer has a phone number on file.
  Setup: fill in `config/sms.php` (API key + approved Sender Name) and set
  `SMS_ENABLED` to `true`. Until then, SMS sending is skipped automatically
  (no error) and only the in-app notification is created.
- **PDF export**: customers can download a PDF receipt
  (`customer/receipt-pdf.php`, linked from the receipt page), and admins can
  export any of the three reports as PDF (`admin/reports-export.php`).
  Built with a small dependency-free PDF writer (`includes/pdf_lib.php`) —
  no Composer package needed.
- **Excel export**: the same reports export as real `.xlsx` files
  (`includes/xlsx_lib.php`, using PHP's built-in `ZipArchive`) — no
  PhpSpreadsheet dependency needed.
- If you already created the database from an older copy of this project
  (before `payments.gateway_reference` existed), run
  `database/migration_2026_gateway_and_exports.sql` once. A fresh install
  from the current `database/database.sql` already has it.

## What still needs real-world work before deployment
This is a working starter, not a production-ready deployment. Before this
goes live for an actual barangay:

- [ ] Replace the sample tariff in `includes/billing.php` with the official
      Barangay Tigbao Water District rate structure.
- [ ] Replace the sample flat penalty with the water district's actual
      approved penalty policy.
- [x] Integrate a real, authorized GCash/payment-gateway API, with the
      server verifying payment via the gateway's webhook (PayMongo — see above).
- [x] Connect `notifications` to a real SMS provider (Semaphore — see above).
      Not covered: opening the admin pages with no local server at all.
- [ ] Add password reset, admin 2FA, and login rate limiting.
- [x] Add PDF/Excel export for reports and receipts (see above).
- [ ] Add database backup/restore tooling.
- [ ] Add a customer self-registration/activation workflow if needed.
- [ ] Move DB credentials, the Semaphore API key, and the PayMongo keys into
      environment variables, not the `config/*.php` files.
- [ ] Serve over HTTPS in production (required for real PayMongo webhooks).
- [ ] Security review: rate limiting, input validation edge cases, and a



## Payment methods (GCash, Maya, MariBank, Bank Transfer, Cash)

- The list of methods lives in `includes/payment_methods.php` (name, color, hint). Add one entry there to add a method.
- The online checkout screen for GCash / Maya / MariBank / Bank Transfer is `customer/checkout.php`.
- Cash stays pending until an admin confirms it on Admin > Payments.
- The database `payments.method` list is updated automatically the first time a customer pays
  (or run `database/migration_payment_methods.sql` by hand).

## Discount, penalty, two-part print and Tigbao/Begong names
- **Discount 5%**: paid BEFORE the due date -> 5% off the water charge. **Penalty 3%**: paid AFTER the due date -> 3% of the water charge. Paid ON the due date: neither. Rates: `DISCOUNT_RATE` / `PENALTY_RATE` in `includes/billing.php` (`@@DISCOUNT`).
- The discount is saved on the bill when it is paid (`bills.discount_amount`; added automatically, or run `database/migration_discount.sql`).
- Online payments use the day the customer checked out; cash uses the day the office confirms it.
- Notice of Consumption and Official Receipt print as two parts: ORIGINAL (customer) and DUPLICATE (office) (`@@TWO-PARTS`).
- Name follows the customer's address (`@@WATER-SYSTEM-NAME`): address containing "Begong" -> Begong Water System, otherwise Tigbao Water System. Logo files: see `assets/images/README_LOGOS.txt`.

## Staff portals (Treasurer and Meter Reader)

| Role | Login page | What it can do (and nothing more) |
|------|------------|-----------------------------------|
| Administrator | `admin/login.php` | Everything: consumers and billing, payments, reports, SMS and email, staff accounts |
| Treasurer | `treasurer/login.php` | Dashboard, Payments (confirm cash, collect cash at the counter, print official receipts), collection and unpaid reports, My Profile |
| Meter Reader | `meter-reader/login.php` | Dashboard, Record readings (one per consumer per month), Reading history, My Profile |

- The administrator creates these accounts in **Admin > Staff**.
- Demo accounts made by `setup.php`: `treasurer@tigbao.local / Treasurer@12345` and `reader@tigbao.local / Reader@12345` (change them!).
- The meter reader only records readings. The administrator bills them from **Consumers & Billing** (choose the "Unbilled reading").
- The `users.role` column is updated by itself the first time anyone logs in (see `database/migration_staff_roles.sql`).
