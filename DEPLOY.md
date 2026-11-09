# Putting the system online (cloud) + installing it on phones

The system is PHP + MySQL packed in a Docker image. On first start it **creates its own tables and the first Admin account**,
so there is no manual database import and no sample accounts with known passwords.

## What is in this version
- Everything from the QR Ph version: pay by scanning the district's own QR (GCash Business / Maya / bank), Treasurer confirms.
  Confirming now also needs the Treasurer to **type the amount seen in the district account** (must equal the amount due).
- Everything from the mobile version: installable phone app (PWA) and **Water Reader offline mode** – readings typed
  without signal are kept on the phone and uploaded when signal returns.
- Cloud pieces: `Dockerfile`, auto first-boot setup (`tools/provision.php`), `/health.php`, HTTPS redirect,
  secrets only through environment variables, persistent disk for screenshots and profile photos.

## Choose one way to host

### A. Render (or any Docker platform: Railway, Fly.io …) + a cloud MySQL
1. Create a MySQL/MariaDB database at a cloud provider (for example TiDB Cloud Serverless, Aiven, Railway MySQL – check each one's
   current free/paid terms). Note host, port, database name, user, password.
2. Put this folder in a GitHub repository.
3. Render → **New + → Blueprint** → choose the repository (it reads `render.yaml`). Fill `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`
   and `ADMIN_EMAIL`. `ADMIN_PASSWORD` and `TIGBAO_SYNC_KEY` are generated for you (see the service's *Environment* tab).
   Keep `DB_SSL=true` for cloud databases; set it to `false` only if your database does not use TLS.
4. Keep the **disk** (`/var/www/html/storage`): it holds payment screenshots and profile photos. Instances without a disk lose them on every redeploy.
5. Open the `https://….onrender.com` address and log in at **Admin login**.

### B. One rented server (VPS) – website, database, HTTPS and daily backups together
```
git clone <your repo> tigbao && cd tigbao
cp .env.example .env        # fill in DOMAIN, DB_PASS, ADMIN_EMAIL (ADMIN_PASSWORD optional)
docker compose -f docker-compose.prod.yml up -d --build
docker compose -f docker-compose.prod.yml logs web | grep -A4 "ADMIN ACCOUNT"   # shows the password if you left it empty
```
Point the domain's DNS A-record at the server first; Caddy gets the HTTPS certificate by itself.
Database dumps are written to `./backups/` every day (last 14 kept) – copy them off the server regularly.

### C. Try it on your own computer first
`docker compose up --build` → http://localhost:8080 → log in as `admin@tigbao.local` / `Admin-local-1234` (local trial only).

### D. cPanel shared hosting (no Docker)
Import `database/database.sql` in phpMyAdmin, upload the folder, copy `config/local.php.example` to `config/local.php` and fill it in,
then open `/setup.php` once (add `putenv('TIGBAO_ALLOW_SETUP=1');` to `config/local.php` first). setup.php makes sample accounts with
known passwords: delete the file afterwards and change every password.

## First day checklist
1. Log in as Admin → **Staff**: create the Treasurer and Water Reader accounts. **Profile**: change the admin password if it was generated.
2. Admin → **Payments → QR setup**: paste the text of the district's QR (or choose a screenshot) → **Save QR**.
3. Press **Show test QR** (₱1), pay it from a *different* person's GCash/Maya/bank app, and check the district account receives ₱1.00.
   **Do this before telling consumers** – wallets differ and this cannot be tested from here.
4. Admin → **Billing → Rates**: set the water rate, discount and penalty.
5. Add consumers, record readings, generate bills.
6. Optional e-mail/SMS receipts: set `MAIL_ENABLED`, `MAIL_USERNAME`, `MAIL_APP_PASSWORD` (Gmail App Password) and
   `SMS_ENABLED`, `SEMAPHORE_API_KEY` as environment variables (Render → Environment, or `.env`).
7. Keep `TIGBAO_DEMO` unset so the audit log records real IP addresses.

## How a QR payment works day to day
1. Consumer: **My Bills → Pay → QR Ph**. The QR already contains the exact amount.
2. Consumer pays in GCash/Maya/bank app, types the **reference number** from the receipt (screenshot optional) and taps **I have paid**.
3. **Treasurer → Payments → Check QR payments**: find that amount + reference in the district account, type the amount seen, **Confirm**
   (bill becomes PAID, receipt goes out) or **Reject** (consumer sees the reason).
4. One receipt reference can never pay two bills, and a bill can never be paid twice (cash or QR).
There is no payment gateway, so nothing confirms money automatically – the Treasurer's check is the safety control.

## Phones
- **Android (Chrome):** open the site → **Install** bar (or ⋮ → *Install app*). **iPhone (Safari):** Share → *Add to Home Screen*.
- **Water Reader offline:** open *Meter Readings* once with signal. Later without signal the saved page opens and **Save** keeps readings on the phone;
  they upload automatically (or press **Send now**). The server applies the usual rules (one per month, never lower than the last).
- Consumer, Treasurer and Admin pages are never stored on the phone.
- A real `.apk`: enter your https address at pwabuilder.com → Android.

## Security notes
- The old Gmail App Password that was inside the earlier zip is no longer in the code. **Revoke it** in your Google account and create a new one.
- Residents' personal data is online: limit staff accounts, use strong passwords, keep backups (Data Privacy Act).
- Never put passwords in files that are shared or committed; use environment variables (or `config/local.php`, which is blocked from the web).
