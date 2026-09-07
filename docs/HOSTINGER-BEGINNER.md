# GradeSphere — Hostinger hosting guide (complete beginner)

This guide assumes you have **never hosted a website before**.  
We go **one step at a time**. After each step, tell me what you see, then we continue.

---

## Big picture (read this once)

### What “hosting” means
Your app currently runs only on **your PC** (`127.0.0.1`).  
**Hosting** = renting a small computer on the internet (Hostinger) that:

1. stores your Laravel files  
2. runs PHP  
3. connects to MySQL  
4. gives you a public address like `https://yourschool.com`

Then admin / teachers / students open that link on phone or PC.

### The plan (best for first app)

| Phase | What | Why |
|-------|------|-----|
| **0** | Understand + Hostinger account | You need a place to put the site |
| **1** | Create MySQL database | All school data lives here |
| **2** | Build files on your PC | CSS/JS must be production-ready |
| **3** | Upload project to Hostinger | Copy code to the server |
| **4** | Point domain to `public/` | Security — hide `.env` and `app/` |
| **5** | Create server `.env` | Tell Laravel the real DB + URL |
| **6** | Run migrate (+ seed admin) | Create tables + first principal |
| **7** | Smoke test live site | Prove admin/teacher/student work |
| **8** | Principal manages school | Add real teachers only |

**Best plan for you:** Hostinger shared hosting + MySQL + document root = `public`.  
No VPS, no Docker, no extra API server. Simple and correct for GradeSphere.

---

## Important rules (never skip)

1. **Never** put `.env` in a public GitHub repo or chat if it has real passwords.  
2. Website must open from the **`public`** folder (not the project root).  
3. On live site: `APP_DEBUG=false` (strangers must not see error details).  
4. Change seed passwords (you already did C1 locally — do again on live if you seed).  
5. Only the principal creates teachers after go-live.

---

## Phase 0 — Do you have Hostinger?

### Why
Without Hostinger (or similar), the site cannot be public.

### What you need
- A Hostinger account  
- A domain (example: `gradesphere.yourname.com` or a real school domain)  
- A hosting plan that supports **PHP 8.2+** and **MySQL**

### Your action now
1. Open Hostinger and log in (or create account).  
2. Note your **domain name** (or subdomain).  
3. Reply to me with:

```text
Phase 0 done
Domain: _______________
I can open hPanel: yes/no
```

**Stop here until Phase 0 is done.** Do not upload files yet.

---

## Phase 1 — Create MySQL database (after Phase 0)

### Why
Laravel does not keep students/grades in files. It needs a database on the server.

### What you will create in hPanel → Databases → MySQL
- Database name  
- Database username  
- Database password  
- Host (often `localhost`)

Write them down offline.

Reply: `Phase 1 done` + (you can say “I saved DB name/user/host” — **do not paste the real password in chat**).

---

## Phase 2 — Build on your PC (we do this together)

### Why
Browsers need compiled CSS/JS from Vite. `npm run build` creates `public/build`.

### Command
```bash
npm run build
```

Reply: `Phase 2 done` when build succeeds.

---

## Phase 3 — Upload files

### Why
Hostinger must receive your project files.

### Typical method (beginner)
hPanel → **File Manager** → `domains/your-domain/public_html/`  
Upload a zip of the project (excluding `node_modules`), then extract.

Or use Hostinger Git deploy if you use Git (we can choose later).

**Do not upload** your local `.env` with PC passwords. We create a new `.env` on the server.

---

## Phase 4 — Document root = `public`

### Why
If document root is the project root, people could try to open sensitive files.  
Laravel’s public front door is always `public/index.php`.

In Hostinger: set domain document root to  
`.../public_html/public`  
(or move contents carefully — we will choose the safest method for your panel).

---

## Phase 5 — Server `.env`

### Why
This file tells Laravel:
- production mode  
- real website URL  
- Hostinger MySQL login  

Example shape (values will be yours):

```env
APP_NAME=GradeSphere
APP_ENV=production
APP_DEBUG=false
APP_URL=https://YOUR-DOMAIN

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

Then generate app key on server (SSH or Hostinger terminal if available).

---

## Phase 6 — Migrate

### Why
Creates all tables (`users`, `students`, `report_cards`, …) on the **live** database.

```bash
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
```

(Exact commands depend on whether Hostinger gives SSH.)

---

## Phase 7 — Live test

Open `https://YOUR-DOMAIN` and test:
- Admin login  
- Create/check teacher  
- Teacher grades  
- Student lookup  

---

## Phase 8 — Hand to principal

- Give principal **only** admin login  
- Principal creates teachers  
- Teachers enter students  
- Students use index lookup  

---

## If something breaks after hosting

1. Note the page + error (screenshot).  
2. We fix on your PC.  
3. Re-test locally.  
4. Re-upload changed files.  
5. Ask users to refresh.

---

## Where you are now

- [x] App built  
- [x] Step B testing  
- [x] C1 passwords changed locally  
- [ ] **Phase 0 — Hostinger account + domain** ← you are here
