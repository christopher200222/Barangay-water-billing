> **v8 note:** Billing = Bills + Readings + Rates, Payments = Payments + Receipt check, Reports = Reports + Audit log, Consumer Bills = Bills + Payments + Usage, Consumer Profile includes Settings. Login / logout / print live in `includes/shared_pages.php`. The old page files are redirects. See README.md > "What changed in v8".

# Project Map - find and change anything in 1 tap

Open this file in VS Code, then press **Ctrl+Shift+V** to see it as a clickable page. **Click any file link to open that file.**

## 1. Find anything in 3 seconds

Every part of the system has a search tag that starts with `@@`.

1. Press **Ctrl+Shift+F** (Search).
2. Type a tag, for example `@@BILLING`, and press **Enter**.
3. Click a result. VS Code jumps to that exact line.

| To do this | Press |
|---|---|
| Search the whole project | **Ctrl+Shift+F** |
| See every tag in the project | Search for `@@` |
| Open a file by name | **Ctrl+P**, type part of the name (example: `billing`) |
| Search inside the open file | **Ctrl+F** |
| Go to a line number | **Ctrl+G** |
| Go back to where you were | **Alt+Left** |
| Change a color | Hover a code like `#1c5cc9`, click the little colored square |

**Optional one-click list:** install the *Todo Tree* extension (VS Code will suggest it). It adds a tree in the left bar listing every `@@` tag. Click one to jump to it.

Tag names: pages end in `-FORM`, `-TABLE`, `-CARDS`, `-GRAPHS`, `-SAVE` (what happens when a button is pressed), `-PAGE` (page title). Customer pages start with `@@MY-`, so `@@BILLING` is the admin page and `@@MY-BILLS` is the customer page.

**Full list of tags:** open [TAG_INDEX.md](TAG_INDEX.md) - every tag with a clickable link to its exact line. New tag families: `@@FN-name` (every function), `@@CLASS-name` (every class), `@@SQL-table-ACTION` (every database query, e.g. `@@SQL-bills-UPDATE`). Search `@@FN-` to list all functions, or `@@SQL-payments` for everything that touches the payments table.

## 2. I want to change...

| I want to change | Search this | Where |
|---|---|---|
| Water rates (price per cubic meter): Admin > Water Rates | `@@RATES` | [admin/rates.php](admin/rates.php) |
| How the rates are read and the bill is worked out | `@@TARIFF` | [includes/billing.php](includes/billing.php) |
| Late payment penalty | `@@PENALTY` | [includes/billing.php](includes/billing.php) |
| The wording of every SMS / email (new bill, reminder, overdue, payment received) | `@@NOTICE-WORDING` | [includes/notices.php](includes/notices.php) |
| How many days before the due date reminders go out | `@@REMINDER-DAYS` | [includes/notices.php](includes/notices.php) |
| The SMS & Email page (history, send notice, reminders) | `@@NOTIFICATIONS-PAGE` | [admin/notifications.php](admin/notifications.php) |
| What customers see: Messages page (SMS chat + Gmail inbox) | `@@MY-MESSAGES` | [customer/messages.php](customer/messages.php) |
| Gmail address / App Password (real emails) | `@@EMAIL` | [config/mail.php](config/mail.php) |
| Semaphore key (real SMS) | `@@SMS` | [config/sms.php](config/sms.php) |
| The look of the email customers receive | `@@EMAIL-TEMPLATE` | [includes/mailer.php](includes/mailer.php) |
| System name and icon in the left menu | `@@SYSTEM-NAME` | [includes/header.php](includes/header.php) |
| Text on the browser tab | `@@BROWSER-TAB-TITLE` | [includes/header.php](includes/header.php) |
| Show/hide (eye) button on password fields | `@@PASSWORD-EYE` | [assets/js/password-toggle.js](assets/js/password-toggle.js) |
| Icons on the home page / login titles | `@@ICON-LOGIN` | [index.php](index.php) |
| Phone number rules (11 digits) and +63 display format | `@@PHONE-INPUT` | [assets/js/phone-input.js](assets/js/phone-input.js) |
| Short text boxes (phone, account no., subject) | `@@FIELD-SIZE` | [assets/css/theme.css](assets/css/theme.css) |
| Add or rename a menu item (admin) | `@@MENU-ADMIN` | [includes/header.php](includes/header.php) |
| Add or rename a menu item (customer) | `@@MENU-CUSTOMER` | [includes/header.php](includes/header.php) |
| Name and address on printed invoice / receipt | `@@ORG-NAME` | [includes/print_docs.php](includes/print_docs.php) |
| Every place the district name is written | Ctrl+Shift+F: `Barangay Tigbao` | whole project |
| Columns of a table (example: Billing) | `@@BILLING-TABLE` | [admin/billing.php](admin/billing.php) |
| Fields of a form (example: Billing) | `@@BILLING-FORM` | [admin/billing.php](admin/billing.php) |
| What happens when a button is pressed | `@@BILLING-SAVE` | [admin/billing.php](admin/billing.php) |
| Dashboard summary cards | `@@DASHBOARD-CARDS` | [admin/dashboard.php](admin/dashboard.php) |
| Reports: graphs | `@@CHARTS` | [assets/js/charts.js](assets/js/charts.js) |
| Reports: what data each report shows | `@@REPORT-COLLECTION-QUERY` | [includes/reports.php](includes/reports.php) |
| How money is displayed (PHP 1,234.00) | `@@PESO-FORMAT` | [includes/bootstrap.php](includes/bootstrap.php) |
| Login rules | `@@LOGIN-CHECK` | [includes/auth.php](includes/auth.php) |
| Home page text and buttons | `@@HOME-PAGE` | [index.php](index.php) |
| GCash payment screen / real PayMongo switch | `@@DEMO` | [config/demo.php](config/demo.php) |
| GCash keys (real GCash) | `@@GCASH` | [config/paymongo.php](config/paymongo.php) |
| Database name / password | `@@DATABASE` | [config/database.php](config/database.php) |

## 3. Colors - change how it looks

All colors are in two places: **`@@COLORS`** in [assets/css/theme.css](assets/css/theme.css) (the main palette) and **`@@COLORS-BASE`** in [assets/css/style.css](assets/css/style.css). Change a code such as `#1c5cc9` and save. Refresh the browser with **Ctrl+F5**.

| I want to change the color of... | Search this |
|---|---|
| The whole palette (blues, green, one color per section) | `@@COLORS` |
| The color of each section (Billing orange, Payments green, SMS & Email teal...) | `@@COLOR-SECTIONS` |
| The Municipal Hall photo behind everything | `@@COLOR-BACKGROUND` |
| Left menu | `@@COLOR-SIDEBAR` and `@@COLOR-MENU-ITEMS` |
| Top bar of each page | `@@COLOR-TOPBAR` |
| Summary cards | `@@COLOR-CARDS` |
| Tables | `@@COLOR-TABLE` |
| Buttons | `@@COLOR-BUTTONS` and `@@COLOR-BUTTONS-SMALL` |
| PAID / UNPAID labels | `@@COLOR-STATUS` |
| Green / red / blue message boxes | `@@COLOR-ALERTS` |
| SMS & Email page (badges, filter chips) | `@@COLOR-NOTICES` |
| Customer Messages page (phone chat, Gmail inbox) | `@@COLOR-MESSAGES` |
| Login boxes | `@@COLOR-LOGIN` |
| Home page | `@@COLOR-LANDING` |
| On-screen receipt | `@@COLOR-RECEIPT` |
| Report graph bars and pies | `@@COLOR-CHARTS` |
| Gold top-consumer highlight | `@@COLOR-TOP-CONSUMER` |
| Printed invoice / receipt | `@@COLOR-INVOICE` |
| Online / Offline badge | `@@COLOR-SYNC-BADGE` |

## 4. Pages - what file is each screen?

| Screen | File |
|---|---|
| Home page | [index.php](index.php) |
| Admin login | [admin/login.php](admin/login.php) |
| Admin Dashboard | [admin/dashboard.php](admin/dashboard.php) |
| Admin Customers | [admin/customers.php](admin/customers.php) |
| Admin Meter Readings | [admin/readings.php](admin/readings.php) |
| Admin Billing | [admin/billing.php](admin/billing.php) |
| Admin Payments | [admin/payments.php](admin/payments.php) |
| Admin Reports (graphs) | [admin/reports.php](admin/reports.php) |
| Admin SMS & Email  | [admin/notifications.php](admin/notifications.php) |
| Admin printable invoice / receipt | [admin/print.php](admin/print.php) |
| Customer login | [customer/login.php](customer/login.php) |
| Customer home | [customer/dashboard.php](customer/dashboard.php) |
| Customer My Bills | [customer/bills.php](customer/bills.php) |
| Customer Consumption | [customer/consumption.php](customer/consumption.php) |
| Customer My Payments | [customer/payments.php](customer/payments.php) |
| Customer Profile | [customer/profile.php](customer/profile.php) |
| Customer Settings | [customer/settings.php](customer/settings.php) |
| Customer Messages (SMS + email inbox) | [customer/messages.php](customer/messages.php) |
| Customer Pay a bill | [customer/pay.php](customer/pay.php) |
| GCash payment screen | [customer/demo-gcash.php](customer/demo-gcash.php) |
| Customer receipt (screen) | [customer/receipt.php](customer/receipt.php) |
| Customer printable invoice / receipt | [customer/print.php](customer/print.php) |
| Left menu + top of every page | [includes/header.php](includes/header.php) |
| Layout of printed invoice / receipt | [includes/print_docs.php](includes/print_docs.php) |
| Message wording + reminders | [includes/notices.php](includes/notices.php) |
| Sending SMS + saving notifications | [includes/sms.php](includes/sms.php) |
| Sending Gmail | [includes/mailer.php](includes/mailer.php) |

## 5. Database tables

File: [database/database.sql](database/database.sql). Every column has a comment you can also read in phpMyAdmin (Structure tab).

| Table | Tag | Holds |
|---|---|---|
| users | `@@DB-USERS` | customers and admin accounts |
| meter_readings | `@@DB-METER-READINGS` | monthly meter readings |
| bills | `@@DB-BILLS` | bills |
| payments | `@@DB-PAYMENTS` | cash and GCash payments |
| app_settings | `@@DB-SETTINGS` | water rate, discount %, penalty % (Admin > Water Rates) |
| notifications | `@@DB-NOTIFICATIONS` | in-app messages plus the SMS and email records |

> Column comments show in phpMyAdmin only after a fresh import of `database.sql`. Re-importing an existing database erases its data, so back it up first.

## 6. Every tag in the project (built from the code)

Search the tag to see the exact lines.

| Tag | Files |
|---|---|
| `@@ACCESS-CONTROL` | [includes/auth.php](includes/auth.php) |
| `@@ADMIN-LOGIN` | [admin/login.php](admin/login.php) |
| `@@ADMIN-LOGIN-FORM` | [admin/login.php](admin/login.php) |
| `@@ADMIN-LOGIN-SAVE` | [admin/login.php](admin/login.php) |
| `@@AUTH` | [includes/auth.php](includes/auth.php), [includes/bootstrap.php](includes/bootstrap.php) |
| `@@BILLING` | [admin/billing.php](admin/billing.php), [includes/billing.php](includes/billing.php) |
| `@@BILLING-FORM` | [admin/billing.php](admin/billing.php) |
| `@@BILLING-PAGE` | [admin/billing.php](admin/billing.php) |
| `@@BILLING-SAVE` | [admin/billing.php](admin/billing.php) |
| `@@BILLING-TABLE` | [admin/billing.php](admin/billing.php) |
| `@@BROWSER-TAB-TITLE` | [includes/header.php](includes/header.php) |
| `@@CASH` | [customer/pay.php](customer/pay.php), [admin/payments.php](admin/payments.php) |
| `@@CHARTS` | [admin/reports.php](admin/reports.php), [assets/js/charts.js](assets/js/charts.js) |
| `@@COLOR-ALERTS` | [assets/css/style.css](assets/css/style.css), [assets/css/theme.css](assets/css/theme.css) |
| `@@COLOR-BACKGROUND` | [assets/css/theme.css](assets/css/theme.css) |
| `@@COLOR-BUTTONS` | [assets/css/style.css](assets/css/style.css), [assets/css/theme.css](assets/css/theme.css) |
| `@@COLOR-BUTTONS-SMALL` | [assets/css/style.css](assets/css/style.css) |
| `@@COLOR-CARDS` | [assets/css/style.css](assets/css/style.css), [assets/css/theme.css](assets/css/theme.css) |
| `@@COLOR-CHARTS` | [assets/css/theme.css](assets/css/theme.css), [assets/js/charts.js](assets/js/charts.js) |
| `@@COLOR-INVOICE` | [assets/css/theme.css](assets/css/theme.css), [includes/print_docs.php](includes/print_docs.php) |
| `@@COLOR-LANDING` | [assets/css/style.css](assets/css/style.css), [assets/css/theme.css](assets/css/theme.css) |
| `@@COLOR-LOGIN` | [assets/css/style.css](assets/css/style.css), [assets/css/theme.css](assets/css/theme.css) |
| `@@COLOR-MENU-ITEMS` | [assets/css/style.css](assets/css/style.css), [assets/css/theme.css](assets/css/theme.css) |
| `@@COLOR-MESSAGES` | [customer/messages.php](customer/messages.php), [assets/css/theme.css](assets/css/theme.css) |
| `@@COLOR-NOTICES` | [assets/css/theme.css](assets/css/theme.css) |
| `@@COLOR-RECEIPT` | [assets/css/theme.css](assets/css/theme.css) |
| `@@COLOR-SECTION-TITLE` | [assets/css/style.css](assets/css/style.css), [assets/css/theme.css](assets/css/theme.css) |
| `@@COLOR-SECTIONS` | [assets/css/theme.css](assets/css/theme.css) |
| `@@COLOR-SIDEBAR` | [assets/css/style.css](assets/css/style.css), [assets/css/theme.css](assets/css/theme.css) |
| `@@COLOR-STATUS` | [assets/css/style.css](assets/css/style.css), [assets/css/theme.css](assets/css/theme.css) |
| `@@COLOR-TABLE` | [assets/css/theme.css](assets/css/theme.css) |
| `@@COLOR-TOP-CONSUMER` | [admin/reports.php](admin/reports.php), [assets/css/theme.css](assets/css/theme.css) |
| `@@COLOR-TOPBAR` | [assets/css/style.css](assets/css/style.css), [assets/css/theme.css](assets/css/theme.css) |
| `@@COLORS` | [assets/css/style.css](assets/css/style.css), [assets/css/theme.css](assets/css/theme.css) |
| `@@COLORS-BASE` | [assets/css/style.css](assets/css/style.css) |
| `@@CONFIG` | [config/database.php](config/database.php), [config/demo.php](config/demo.php), [config/mail.php](config/mail.php), [config/paymongo.php](config/paymongo.php) and 3 more |
| `@@CUSTOMER-LOGIN` | [customer/login.php](customer/login.php) |
| `@@CUSTOMER-LOGIN-FORM` | [customer/login.php](customer/login.php) |
| `@@CUSTOMER-LOGIN-SAVE` | [customer/login.php](customer/login.php) |
| `@@CUSTOMERS` | [admin/customers.php](admin/customers.php) |
| `@@CUSTOMERS-FORM` | [admin/customers.php](admin/customers.php) |
| `@@CUSTOMERS-PAGE` | [admin/customers.php](admin/customers.php) |
| `@@CUSTOMERS-SAVE` | [admin/customers.php](admin/customers.php) |
| `@@CUSTOMERS-TABLE` | [admin/customers.php](admin/customers.php) |
| `@@DASHBOARD` | [admin/dashboard.php](admin/dashboard.php) |
| `@@DASHBOARD-CARDS` | [admin/dashboard.php](admin/dashboard.php) |
| `@@DASHBOARD-PAGE` | [admin/dashboard.php](admin/dashboard.php) |
| `@@DASHBOARD-RECENT-PAYMENTS` | [admin/dashboard.php](admin/dashboard.php) |
| `@@DASHBOARD-SCRIPT` | [admin/dashboard.php](admin/dashboard.php) |
| `@@DASHBOARD-TABLE` | [admin/dashboard.php](admin/dashboard.php) |
| `@@DATABASE` | [database/database.sql](database/database.sql), [database/migration_2026_gateway_and_exports.sql](database/migration_2026_gateway_and_exports.sql), [database/migration_notifications_flag.sql](database/migration_notifications_flag.sql) and 2 more |
| `@@DB-BILLS` | [database/database.sql](database/database.sql) |
| `@@DB-INDEXES` | [database/database.sql](database/database.sql) |
| `@@DB-METER-READINGS` | [database/database.sql](database/database.sql) |
| `@@DB-MIGRATION` | [database/migration_2026_gateway_and_exports.sql](database/migration_2026_gateway_and_exports.sql), [database/migration_notifications_flag.sql](database/migration_notifications_flag.sql) |
| `@@DB-NOTIFICATIONS` | [database/database.sql](database/database.sql) |
| `@@DB-PAYMENTS` | [database/database.sql](database/database.sql) |
| `@@DB-USERS` | [database/database.sql](database/database.sql) |
| `@@DB-SETTINGS` | [database/database.sql](database/database.sql), [includes/billing.php](includes/billing.php) |
| `@@DEMO-COLUMN` | [includes/sms.php](includes/sms.php) |
| `@@EMAIL` | [admin/notifications.php](admin/notifications.php), [config/mail.php](config/mail.php), [includes/mailer.php](includes/mailer.php) |
| `@@EMAIL-INBOX` | [customer/messages.php](customer/messages.php) |
| `@@EMAIL-READY` | [includes/mailer.php](includes/mailer.php) |
| `@@EMAIL-TEMPLATE` | [includes/mailer.php](includes/mailer.php) |
| `@@EMAIL-VALID` | [includes/mailer.php](includes/mailer.php) |
| `@@EXPORT-EXCEL` | [admin/reports-export.php](admin/reports-export.php), [includes/xlsx_lib.php](includes/xlsx_lib.php) |
| `@@EXPORT-EXCEL-BUILD` | [includes/xlsx_lib.php](includes/xlsx_lib.php) |
| `@@EXPORT-PDF` | [customer/receipt-pdf.php](customer/receipt-pdf.php), [admin/reports-export.php](admin/reports-export.php), [includes/pdf_lib.php](includes/pdf_lib.php) |
| `@@FIELD-SIZE` | [assets/css/theme.css](assets/css/theme.css) |
| `@@FLASH-MESSAGES` | [includes/bootstrap.php](includes/bootstrap.php) |
| `@@GCASH` | [customer/demo-gcash.php](customer/demo-gcash.php), [customer/pay-return.php](customer/pay-return.php), [customer/pay.php](customer/pay.php), [config/paymongo.php](config/paymongo.php) and 2 more |
| `@@GCASH-CREATE` | [includes/paymongo.php](includes/paymongo.php) |
| `@@GCASH-DEMO` | [customer/pay.php](customer/pay.php) |
| `@@GCASH-DEMO-PAGE` | [customer/demo-gcash.php](customer/demo-gcash.php) |
| `@@GCASH-DEMO-PAGE-FORM` | [customer/demo-gcash.php](customer/demo-gcash.php) |
| `@@GCASH-DEMO-PAGE-FORM-2` | [customer/demo-gcash.php](customer/demo-gcash.php) |
| `@@GCASH-DEMO-PAGE-SAVE` | [customer/demo-gcash.php](customer/demo-gcash.php) |
| `@@GCASH-DEMO-PAGE-SCRIPT` | [customer/demo-gcash.php](customer/demo-gcash.php) |
| `@@GCASH-REAL` | [customer/pay.php](customer/pay.php) |
| `@@GCASH-WEBHOOK-CHECK` | [includes/paymongo.php](includes/paymongo.php) |
| `@@HELPERS` | [includes/bootstrap.php](includes/bootstrap.php) |
| `@@HOME` | [index.php](index.php) |
| `@@HOME-PAGE` | [index.php](index.php) |
| `@@ICON-LOGIN` | [assets/css/theme.css](assets/css/theme.css) |
| `@@INVOICE` | [customer/print.php](customer/print.php), [admin/billing.php](admin/billing.php), [admin/print.php](admin/print.php), [includes/print_docs.php](includes/print_docs.php) |
| `@@LAYOUT` | [includes/footer.php](includes/footer.php), [includes/header.php](includes/header.php) |
| `@@LOGIN` | [customer/login.php](customer/login.php), [customer/logout.php](customer/logout.php), [admin/login.php](admin/login.php), [admin/logout.php](admin/logout.php) and 1 more |
| `@@LOGIN-CHECK` | [includes/auth.php](includes/auth.php) |
| `@@LOGS-EXPORT-QUERY` | [includes/reports.php](includes/reports.php) |
| `@@LOGS-WRITE` | [includes/bootstrap.php](includes/bootstrap.php) |
| `@@MENU` | [includes/header.php](includes/header.php) |
| `@@MENU-ADMIN` | [includes/header.php](includes/header.php) |
| `@@MENU-CUSTOMER` | [includes/header.php](includes/header.php) |
| `@@MY-BILLS` | [customer/bills.php](customer/bills.php) |
| `@@MY-BILLS-PAGE` | [customer/bills.php](customer/bills.php) |
| `@@MY-CONSUMPTION` | [customer/consumption.php](customer/consumption.php) |
| `@@MY-CONSUMPTION-PAGE` | [customer/consumption.php](customer/consumption.php) |
| `@@MY-CONSUMPTION-TABLE` | [customer/consumption.php](customer/consumption.php) |
| `@@MY-DASHBOARD` | [customer/dashboard.php](customer/dashboard.php) |
| `@@MY-DASHBOARD-CARDS` | [customer/dashboard.php](customer/dashboard.php) |
| `@@MY-DASHBOARD-PAGE` | [customer/dashboard.php](customer/dashboard.php) |
| `@@MY-MESSAGES` | [customer/messages.php](customer/messages.php), [database/database.sql](database/database.sql) |
| `@@MY-MESSAGES-PAGE` | [customer/messages.php](customer/messages.php) |
| `@@MY-PAYMENTS` | [customer/payments.php](customer/payments.php) |
| `@@MY-PAYMENTS-PAGE` | [customer/payments.php](customer/payments.php) |
| `@@MY-PAYMENTS-TABLE` | [customer/payments.php](customer/payments.php) |
| `@@MY-PROFILE` | [customer/profile.php](customer/profile.php) |
| `@@MY-PROFILE-FORM` | [customer/profile.php](customer/profile.php) |
| `@@MY-PROFILE-FORM-2` | [customer/profile.php](customer/profile.php) |
| `@@MY-PROFILE-PAGE` | [customer/profile.php](customer/profile.php) |
| `@@MY-PROFILE-SAVE` | [customer/profile.php](customer/profile.php) |
| `@@MY-RECEIPT` | [customer/receipt.php](customer/receipt.php) |
| `@@MY-RECEIPT-PAGE` | [customer/receipt.php](customer/receipt.php) |
| `@@MY-SETTINGS` | [customer/settings.php](customer/settings.php) |
| `@@MY-SETTINGS-PAGE` | [customer/settings.php](customer/settings.php) |
| `@@NET-CERTIFICATES` | [includes/mailer.php](includes/mailer.php) |
| `@@NOTICE-COMPOSE` | [admin/notifications.php](admin/notifications.php) |
| `@@NOTICE-WORDING` | [customer/messages.php](customer/messages.php), [admin/billing.php](admin/billing.php), [admin/notifications.php](admin/notifications.php), [includes/notices.php](includes/notices.php) |
| `@@NOTICES` | [includes/notices.php](includes/notices.php) |
| `@@NOTIFICATIONS` | [includes/mailer.php](includes/mailer.php), [includes/sms.php](includes/sms.php) |
| `@@NOTIFICATIONS-PAGE` | [admin/dashboard.php](admin/dashboard.php), [admin/notifications.php](admin/notifications.php) |
| `@@NOTIFICATIONS-PAGE-CARDS` | [admin/notifications.php](admin/notifications.php) |
| `@@NOTIFICATIONS-PAGE-FORM` | [admin/notifications.php](admin/notifications.php) |
| `@@NOTIFICATIONS-PAGE-PAGE` | [admin/notifications.php](admin/notifications.php) |
| `@@NOTIFICATIONS-PAGE-SAVE` | [admin/notifications.php](admin/notifications.php) |
| `@@ORG-NAME` | [includes/print_docs.php](includes/print_docs.php) |
| `@@PASSWORD-EYE` | [assets/css/theme.css](assets/css/theme.css), [assets/js/password-toggle.js](assets/js/password-toggle.js), [includes/footer.php](includes/footer.php) |
| `@@PAY-BILL` | [customer/pay.php](customer/pay.php) |
| `@@PAY-BILL-FORM` | [customer/pay.php](customer/pay.php) |
| `@@PAY-BILL-PAGE` | [customer/pay.php](customer/pay.php) |
| `@@PAY-BILL-SAVE` | [customer/pay.php](customer/pay.php) |
| `@@PAYMENT-CONFIRM` | [includes/payments.php](includes/payments.php) |
| `@@PAYMENT-FAIL` | [includes/payments.php](includes/payments.php) |
| `@@PAYMENTS` | [customer/pay-return.php](customer/pay-return.php), [customer/pay.php](customer/pay.php), [admin/payments.php](admin/payments.php), [api/paymongo-webhook.php](api/paymongo-webhook.php) and 1 more |
| `@@PAYMENTS-FORM` | [admin/payments.php](admin/payments.php) |
| `@@PAYMENTS-PAGE` | [admin/payments.php](admin/payments.php) |
| `@@PAYMENTS-SAVE` | [admin/payments.php](admin/payments.php) |
| `@@PAYMENTS-TABLE` | [admin/payments.php](admin/payments.php) |
| `@@PENALTY` | [includes/billing.php](includes/billing.php) |
| `@@PESO-FORMAT` | [includes/bootstrap.php](includes/bootstrap.php) |
| `@@PHONE-DISPLAY` | [includes/bootstrap.php](includes/bootstrap.php) |
| `@@PHONE-INPUT` | [assets/js/phone-input.js](assets/js/phone-input.js) |
| `@@PHONE-VALID` | [includes/bootstrap.php](includes/bootstrap.php) |
| `@@PRINT` | [customer/print.php](customer/print.php), [admin/print.php](admin/print.php), [includes/print_docs.php](includes/print_docs.php) |
| `@@PRINT-DATA` | [includes/print_docs.php](includes/print_docs.php) |
| `@@PRINT-LAYOUT` | [includes/print_docs.php](includes/print_docs.php) |
| `@@PROFILE` | [customer/profile.php](customer/profile.php) |
| `@@READINGS` | [admin/readings.php](admin/readings.php) |
| `@@READINGS-FORM` | [admin/readings.php](admin/readings.php) |
| `@@READINGS-PAGE` | [admin/readings.php](admin/readings.php) |
| `@@READINGS-SAVE` | [admin/readings.php](admin/readings.php) |
| `@@READINGS-TABLE` | [admin/readings.php](admin/readings.php) |
| `@@RECEIPT` | [customer/print.php](customer/print.php), [customer/receipt-pdf.php](customer/receipt-pdf.php), [customer/receipt.php](customer/receipt.php), [admin/payments.php](admin/payments.php) and 2 more |
| `@@REFERENCE-NO` | [includes/billing.php](includes/billing.php) |
| `@@REMINDER-DAYS` | [includes/notices.php](includes/notices.php) |
| `@@REMINDER-LIST` | [includes/notices.php](includes/notices.php) |
| `@@REMINDER-SEND` | [includes/notices.php](includes/notices.php) |
| `@@REMINDERS` | [admin/notifications.php](admin/notifications.php), [includes/notices.php](includes/notices.php) |
| `@@REMINDERS-TABLE` | [admin/notifications.php](admin/notifications.php) |
| `@@REPORT-COLLECTION` | [admin/reports.php](admin/reports.php) |
| `@@REPORT-COLLECTION-QUERY` | [includes/reports.php](includes/reports.php) |
| `@@REPORT-CONSUMPTION` | [admin/reports.php](admin/reports.php) |
| `@@REPORT-CONSUMPTION-QUERY` | [includes/reports.php](includes/reports.php) |
| `@@REPORT-UNPAID` | [admin/reports.php](admin/reports.php) |
| `@@REPORT-UNPAID-QUERY` | [includes/reports.php](includes/reports.php) |
| `@@REPORTS` | [admin/reports-export.php](admin/reports-export.php), [admin/reports.php](admin/reports.php), [assets/js/charts.js](assets/js/charts.js), [includes/reports.php](includes/reports.php) |
| `@@REPORTS-CARDS` | [admin/reports.php](admin/reports.php) |
| `@@REPORTS-CARDS-2` | [admin/reports.php](admin/reports.php) |
| `@@REPORTS-CARDS-3` | [admin/reports.php](admin/reports.php) |
| `@@REPORTS-GRAPHS` | [admin/reports.php](admin/reports.php) |
| `@@REPORTS-GRAPHS-2` | [admin/reports.php](admin/reports.php) |
| `@@REPORTS-GRAPHS-3` | [admin/reports.php](admin/reports.php) |
| `@@REPORTS-PAGE` | [admin/reports.php](admin/reports.php) |
| `@@REPORTS-SCRIPT` | [admin/reports.php](admin/reports.php) |
| `@@REPORTS-TABLE` | [admin/reports.php](admin/reports.php) |
| `@@REPORTS-TABLE-2` | [admin/reports.php](admin/reports.php) |
| `@@REPORTS-TABLE-3` | [admin/reports.php](admin/reports.php) |
| `@@SECURITY-CSRF` | [includes/bootstrap.php](includes/bootstrap.php) |
| `@@SETTINGS` | [customer/settings.php](customer/settings.php) |
| `@@SETUP` | [setup.php](setup.php) |
| `@@SMS` | [config/sms.php](config/sms.php), [includes/sms.php](includes/sms.php) |
| `@@SMS-INBOX` | [customer/messages.php](customer/messages.php) |
| `@@SMS-LOG` | [admin/notifications.php](admin/notifications.php), [database/database.sql](database/database.sql) |
| `@@SMS-LOG-TABLE` | [admin/notifications.php](admin/notifications.php) |
| `@@SMS-PHONE-FORMAT` | [includes/sms.php](includes/sms.php) |
| `@@SMS-READY` | [includes/sms.php](includes/sms.php) |
| `@@SMS-SEND` | [includes/sms.php](includes/sms.php) |
| `@@SMS-TEXT` | [admin/billing.php](admin/billing.php), [includes/notices.php](includes/notices.php), [includes/payments.php](includes/payments.php) |
| `@@SYNC-CARD` | [admin/dashboard.php](admin/dashboard.php) |
| `@@SYSTEM-NAME` | [includes/header.php](includes/header.php) |
| `@@TARIFF` | [admin/billing.php](admin/billing.php), [database/database.sql](database/database.sql), [includes/billing.php](includes/billing.php) |
| `@@TARIFF-TOTAL` | [includes/billing.php](includes/billing.php) |
| `@@THEME-BASE` | [assets/css/style.css](assets/css/style.css) |


## Tags added in the latest changes (search them with Ctrl+Shift+F)

| Tag | Where |
|---|---|
| `@@RATES` | [admin/rates.php](admin/rates.php): water rate per m³, discount %, penalty % |
| `@@SETTINGS` | [includes/billing.php](includes/billing.php): how those values are saved and read |
| `@@DB-SETTINGS` | [database/database.sql](database/database.sql): table `app_settings` |
| `@@MY-DASHBOARD-CHART` | [customer/dashboard.php](customer/dashboard.php): the "Consumption Overview" bars (one bar per month) |
| `@@MY-CONSUMPTION-MONTHS` | [customer/consumption.php](customer/consumption.php): one row per month |
| `@@READINGS-SAVE` | [admin/readings.php](admin/readings.php): saving a reading (one per customer per month) |
| `@@CUSTOMERS-SEARCH` / `@@CUSTOMERS-LIVE-SEARCH` | [admin/customers.php](admin/customers.php): the search box that filters while typing |
| `@@INPUT-FIT` | [assets/js/fit-inputs.js](assets/js/fit-inputs.js), [assets/css/theme.css](assets/css/theme.css): text boxes fit their words |
