# GradeSphere — Step B: Testing guide (first app)

Do this **before hosting**. Keep `composer run dev` running so the site works at `http://127.0.0.1:8000`.

---

## Part 1 — Computer tests (already automated)

In a terminal (project folder):

```bash
php artisan test --compact
```

If everything is green, Part 1 is done.

---

## Part 2 — You click through the website (manual)

Use these accounts (local only):

| Portal | URL | Login |
|--------|-----|--------|
| Home | `/` | — |
| Admin | `/admin/login` | `admin@gradesphere.test` / `password` (or username `principal`) |
| Teacher | `/teacher/login` | `teacher@gradesphere.test` / `password` (or `alex.morgan`) |
| Student | `/student/lookup` | Index number of a student you created |

Tick each box as you finish it.

### A) Admin (principal)

- [ ] Open home `/` → click **Admin** → login works
- [ ] Dashboard shows numbers (teachers, students, report cards)
- [ ] **Teachers** → **Add teacher** → fill name, username, email, password → tick permissions → save
- [ ] New teacher appears in the table → **Edit** / **Delete** buttons look OK
- [ ] Open **School logo** → logo shows (or upload one)
- [ ] Sidebar: open **Teacher portal** and **Student portal** (admin is allowed)
- [ ] **My profile** → change nothing required; just confirm page loads
- [ ] **Log out**

### B) Teacher

- [ ] `/teacher/login` with teacher account → dashboard OK
- [ ] **Input Grades** → type class (e.g. `Form 3A`) → term → student name + index (e.g. `STU-TEST-001`) → subjects + marks → attendance → save
- [ ] **Class Ledger** → see that student → search by name works
- [ ] Click **Edit** on that row → change a mark → save → ledger updates
- [ ] **My profile** loads
- [ ] **Log out**

### C) Student

- [ ] `/student/lookup` → enter the **same index** you saved (e.g. `STU-TEST-001`)
- [ ] Dashboard shows average / standing
- [ ] **Full Report** opens
- [ ] **Sign out** → try `/student/dashboard` again → should send you back to lookup

### D) Security (important)

- [ ] Logged out: open `/admin/dashboard` → should NOT stay on admin page
- [ ] Logged out: open `/teacher/dashboard` → blocked
- [ ] Open `/register` → should be **404 / not found**
- [ ] As teacher: open `/admin/dashboard` → blocked (403 or not allowed)
- [ ] Admin: edit a teacher → uncheck **Active** → that teacher cannot log in

---

## If something fails

1. Write down: **which page**, **what you clicked**, **what you saw** (error text or screenshot).
2. Tell me that — I will help fix it in the code.
3. Do **not** panic. First-app bugs are normal.

---

## After Step B is all ticked

Tell me: **“Step B done”**  
Then we start **Step C** (prepare for hosting) slowly — one command at a time. We will not rush.

---

## Later (after hosting) — how updates/fixes work (preview only)

You do **not** need this today. Just know the idea:

1. Fix code on your PC (Cursor) → test locally (Step B again).
2. Upload changed files / redeploy to Hostinger.
3. If DB change needed → run migrate on server.
4. Ask users to hard-refresh the browser.

We will practice that **after** local testing is complete.
