# GradeSphere — Hostinger deployment (production)

This app is a **Laravel + Blade + MySQL** monolith. **No API server is required.**

## 1. Build assets locally

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
```

Confirm `public/build/manifest.json` exists.

## 2. Create Hostinger resources

1. Create a **MySQL database** and user in hPanel.
2. Create a website / domain.
3. Set the domain document root to the app’s **`public`** folder (critical).

## 3. Upload code

Upload the project (Git or File Manager), excluding:

- `node_modules/`
- `.env` (create on server)
- local `storage/logs/*` noise

Keep `vendor/` from `composer install --no-dev` **or** run Composer on the server if SSH is available.

## 4. Production `.env` (server)

```env
APP_NAME=GradeSphere
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

LOG_CHANNEL=stack
LOG_LEVEL=error
```

Then:

```bash
php artisan key:generate --force
php artisan migrate --force
php artisan db:seed --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

Seed creates **staff users only**. Teachers are not publicly registered.

Default local-style accounts (change passwords after first login):

| Email | Password | Role |
|-------|----------|------|
| `admin@gradesphere.test` | `password` | admin |
| `teacher@gradesphere.test` | `password` | teacher |

## 5. Permissions

```bash
chmod -R ug+rwx storage bootstrap/cache
```

## 6. SSL

Enable Hostinger free SSL and force HTTPS. `APP_URL` must use `https://`.

## 7. Smoke test after go-live

- [ ] `/` splash loads
- [ ] Teacher login works
- [ ] Input grades saves to MySQL
- [ ] Ledger search + pagination work
- [ ] Student lookup by index works
- [ ] Student sign-out clears portal session
- [ ] `/register` is not publicly available
- [ ] `APP_DEBUG=false` (no stack traces to visitors)

## Why this is senior-practice for Hostinger

- Document root = `public` (never expose `.env` / `app/`)
- Production caches enabled
- Debug off
- Auth gated; no open registration
- Blade monolith — no unnecessary API surface
