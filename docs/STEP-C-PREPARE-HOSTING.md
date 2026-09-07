# GradeSphere — Step C: Prepare for hosting (first app)

Do this **on your PC** before uploading to Hostinger.  
Go slow. Finish one section, then tell me before the next.

---

## What Step C means

We make the app ready for a **real school website**:

- strong secret key
- production settings checklist
- built frontend files (`npm run build`)
- confirm database still works
- change default passwords (important!)

Hosting upload comes in **Step D** (later).

---

## C1 — Change default passwords (do this first)

Seeded accounts still use `password`. That is OK for learning, **not OK** for a real school.

1. Login as admin: `admin@gradesphere.test` / `password`
2. Open **My profile**
3. Set a **new strong password** → Save
4. Logout → login again with the new password (prove it works)
5. Login as teacher → **My profile** → change teacher password too
6. Write both new passwords somewhere safe (notebook / password manager) — **not** in chat if this is a real school

When C1 is done, reply: **C1 done**

---

## C2 — Build frontend for production

In the project folder terminal:

```bash
npm run build
```

You should get a success message and a `public/build` folder.

When C2 is done, reply: **C2 done**

---

## C3 — Full test one more time (quick)

```bash
php artisan test --compact
```

All green = good.

Then open the site once more:

- admin login (new password)
- teacher login (new password)
- student lookup with a real index

When C3 is done, reply: **C3 done**

---

## C4 — Production checklist (we fill this together)

Before Hostinger we will set:

- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL=https://your-real-domain.com`
- [ ] MySQL host/user/password for Hostinger DB
- [ ] `php artisan key:generate` already done locally (keep `.env` secret — never upload `.env` to public chat/Git if private)

**Do not edit production `.env` yet** until you have the Hostinger domain + database.  
Reply **C4 ready** when you want that guided together.

---

## After Step C → Step D (hosting)

Only then we:

1. Create Hostinger MySQL + domain
2. Upload files
3. Point domain to `public/`
4. Run migrate on server
5. Create/login principal
6. Principal adds real teachers

---

## If users report a bug after hosting (preview)

1. Reproduce on your PC if possible  
2. Fix in Cursor  
3. Test locally (Step B style)  
4. Upload only changed files / redeploy  
5. Ask them to refresh  

We will practice that after go-live. Not today.
