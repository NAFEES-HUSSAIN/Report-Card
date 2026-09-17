# GradeSphere — Learning Roadmap (Student ↔ Senior Mentor)

> **Purpose:** Living notebook for your journey from *good student* → *growing mid-level* → *senior habits*.  
> **Rule:** After every learning activity or real production lesson, this file is updated **immediately** before the next task.  
> **Current training project:** GradeSphere (Laravel school report-card system)  
> **Long-term aim:** Build production-ready business systems (especially a **perfect POS** — Point of Sale — plus similar apps: inventory, billing, shop/restaurant)  
> **Last updated:** 2026-09-16

---

## Long-term aim — Perfect POS & business software

GradeSphere is your **training ground**. A real POS needs the same habits you are learning now, then extra depth.

### Skills you are already practicing (transfer to POS)
| GradeSphere skill | Same skill in a POS |
|---|---|
| Roles (admin / teacher / student) | Roles (owner / cashier / manager / stock keeper) |
| Safe DB migrate + backup | Stock, sales, payments — money data must never be guessed |
| Ledger + search + ranks | Sales history, receipts, daily totals |
| Mobile-friendly forms | Counter tablet / phone checkout UI |
| Deploy + smoke test | Every release must not break billing |
| Soft-delete / cleanup rules | Void sale ≠ delete history carelessly |

### What a “perfect POS” usually needs (learn in order later)
1. **Catalog** — products, categories, prices, barcodes  
2. **Inventory** — stock in/out, low-stock alerts  
3. **Checkout / cart** — add items, discounts, tax, payments  
4. **Receipts & history** — print/PDF, void, refunds (auditable)  
5. **Shifts / cash drawer** — open/close day, cash vs card  
6. **Reports** — daily sales, top products, profit (careful with cost price)  
7. **Multi-branch / multi-tenant** (advanced) — several shops, one codebase  
8. **Hardware later** — receipt printer, barcode scanner (after software is solid)

**Teacher rule:** Do **not** jump to a full POS tomorrow. Finish Priority 1–3 habits on GradeSphere first. POS without backup/test/git habits becomes a dangerous money app.

---

## Roles (how we work together)

| Role | Who | Responsibility |
|---|---|---|
| **Student / Builder** | You | Own the product, click every step, deploy, smoke-test, ask when stuck, decide go/no-go for live |
| **Senior mentor / Teacher** | Cursor (AI) | Explain *why*, design safe steps, write/fix code with you, catch traps, update this roadmap |
| **Production owner** | You | Hostinger live site, passwords, backups, school data — never share secrets in chat |

### Working agreement
1. You do the **hosting clicks** and **live smoke tests** (muscle memory).
2. Mentor proposes the **smallest safe next step** (one skill at a time).
3. After each skill: **update this markdown** → then move on.
4. Prefer: *Backup → Local → Test → Deploy → Verify* for anything that touches live/DB.
5. Prefer: *git commit* after a working milestone so history matches reality.

---

## Your current level (honest snapshot)

| Area | Level | Notes |
|---|---|---|
| Build features in Laravel | Strong junior | You ship end-to-end features |
| Host on Hostinger | Strong junior | Method B, views, assets, storage link |
| Safe DB changes | Early mid habit | Backup + migrate + smoke test done for real |
| Debug live issues | Strong junior | Root-cause fixes (CSS, storage, orphan student, mobile) |
| Git discipline | Developing | Commits exist; daily branch habit still to lock in |
| Automated testing | Developing | Pest exists; habit of “bug → test” still next |
| Security / ops | Beginner–junior | Must deepen before scaling school data |
| Solo independence | Growing | Next: one feature mostly without guidance |

**Verdict:** You *can* build, host, and fix a small real system. You are *not* “finished.” Path is correct.

---

## Software Development Lifecycle (how real teams work)

We use this cycle for **every** feature:

```text
1. Plan        → What problem? Who uses it? Success = ?
2. Design      → Screens + tables + permissions (short)
3. Build       → Local code (smallest change)
4. Test        → Automated (Pest) + manual smoke
5. Commit      → Git message that says WHY
6. Backup      → Especially before DB / risky live work
7. Deploy      → Upload only what changed (+ build if CSS/JS)
8. Migrate     → Only if DB changed (`migrate --force` on live)
9. Verify      → Live smoke test checklist
10. Learn      → Update THIS file with what you learned
```

### Mentor vs you in each step

| Step | You | Mentor |
|---|---|---|
| Plan | Confirm school need / priority | Help write clear success criteria |
| Design | Approve UX / rules | Propose simple design |
| Build | Review diffs, run local app | Implement / guide code |
| Test | Run tests + click UI | Write/adjust Pest tests |
| Commit | Ask to commit when ready | Draft message, run git safely |
| Backup | Export SQL / keep file named | Remind + teach restore options |
| Deploy | Upload / SSH on Hostinger | Exact file list + commands |
| Verify | Live smoke test | Checklist |
| Learn | Say what felt hard | Update roadmap |

---

## What you have already completed (real experience)

### A. Product / features
- [x] Multi-portal app: **Admin / Teacher / Student** with roles & permissions
- [x] Teacher grade input, ledger, ranks/standing
- [x] Student lookup by index number + dashboard + full report
- [x] Profile avatars (storage) for staff
- [x] Static school logo (no admin upload path)
- [x] Splash / login UI polish (industry-style guest screens)
- [x] **Teacher remark** (DB column + teacher form + student visibility)
- [x] **Delete cleanup**: last report card gone → student removed from lookup
- [x] **Mobile subject remarks**: stacked subject cards (remarks visible on phone)

### B. Hosting & ops (Hostinger)
- [x] Hostinger Method B layout (`public_html` vs app/`gradesphere`)
- [x] Deploy Blade/PHP updates + `php artisan view:clear`
- [x] Vite/build assets awareness (when CSS/JS changes need `npm run build`)
- [x] Storage symlink for avatars (`storage` → `storage/app/public`)
- [x] Live smoke testing after deploy

### C. Database discipline
- [x] Backup live DB **before** schema change (`u738629442_gradesphere.sql`)
- [x] Additive migration on live (`teacher_remark`) with `migrate --force`
- [x] Understand: UI-only deploy ≠ DB migrate
- [x] Started **Situation B**: restore backup into **local temp DB** to browse safely (not overwrite live)
- [x] **Situation B completed** — local backup import + browse practiced
- [x] Deploy order cheatsheet written (`docs/DEPLOY-ORDER-CHEATSHEET.md`)

### D. Debugging lessons (keep forever)
- [x] Missing PHP class / wrong deploy → 500
- [x] Wrong/missing hashed CSS → broken UI
- [x] Storage not linked → avatar breaks after save
- [x] Delete report card ≠ delete student row (orphan lookup bug)
- [x] Wide HTML table on mobile hides columns (subject remarks)

### E. Engineering habits started
- [x] Prefer backup before risky live work
- [x] Local verify before upload when possible
- [x] Combined deploys (ship related fixes together)
- [x] Learning roadmap document (this file)

---

## What you still need to learn (priority queue)

Work **one item at a time**. When done, tick it and move the next item up.

### Priority 1 — Make shipping repeatable
1. [x] **Git every milestone** — branch + clear commit after each working feature *(practiced with GitHub Desktop: branch → commit → merge → push)*  
2. [x] **Bug → Pest test** — every production bug gets one automated test *(ran StudentDeleteCleanupTest — 4 passed; understood bug→fix→test habit)*  
3. [x] **Staging mindset** — practice risky changes on a copy before live (or local temp DB) *(Situation B done; full staging site still later)*  
4. [x] **Named backups** — `YYYY-MM-DD_reason_dbname.sql` + keep folder organized *(practiced: renamed live export in D:\SEPTEMBER\Backups)*  

### Priority 2 — Independence
5. [ ] **Solo feature drill** — e.g. “publish report card” flag; you drive plan → build → test → deploy with mentor only reviewing  
6. [ ] **Laravel log first** — on every live error, open `storage/logs/laravel.log` before guessing  
7. [ ] **Draw the data model** — Student → Enrollment → ReportCard → SubjectScore (one page)  

### Priority 3 — Security & reliability
8. [ ] **Deploy security checklist** — `.env` secret, HTTPS, permissions, logout, student session  
9. [x] **Backup restore drill** — restore backup to local temp DB and browse *(Situation B completed)*  
10. [ ] **Monthly ops habit** — backup schedule + quick health check of live site  

### Priority 4 — Growth track (when shipping habits feel boring)

These are **not urgent now**, but you **must** learn them while growing toward POS / serious business apps.

#### A) Delivery & DevOps
11. [ ] **CI/CD** — run tests automatically on push; eventually auto-deploy to staging  
12. [ ] **Docker** — same app runs the same way on your PC and server (fewer “works on my machine” bugs)  
13. [ ] **Staging environment** — a copy of live used only for practice deploys  

#### B) Background work & communication
14. [ ] **Queues / jobs** — heavy work off the web request (export sales, rebuild ranks, send receipts)  
15. [ ] **Mail / notifications** — password reset, low stock email, daily sales summary  
16. [ ] **Scheduled tasks (cron)** — nightly backup reminder, daily report, clear old sessions  

#### C) Performance & scale
17. [ ] **Performance tuning** — DB indexes, avoid N+1 queries, cache where safe  
18. [ ] **Logging & monitoring** — see errors before customers complain (e.g. log alerts)  
19. [ ] **File / report generation** — PDF receipts, Excel exports (common in POS)  

#### D) Architecture for real products
20. [ ] **Multi-tenant / SaaS thinking** — one system serving many shops or many schools safely  
21. [ ] **Audit trails** — who changed price / voided a sale / deleted a student (money + trust)  
22. [ ] **Payments mindset** — never lose a payment row; idempotent saves; careful refunds  
23. [ ] **API basics** (optional path) — mobile app or scanner talking to Laravel  

#### E) POS-specific product skills (after Priority 1–3)
24. [ ] Design POS data model on paper (Product, StockMovement, Sale, SaleItem, Payment)  
25. [ ] Build a **tiny POS MVP** (one shop, cash only, simple stock) as a new project  
26. [ ] Add refunds/voids with history (no silent deletes)  
27. [ ] Add daily close / Z-report  
28. [ ] Harden security for money data (permissions, backups, restore drill)  

> Optional later phrase to remember: **CI/CD, queues/mail, Docker, performance tuning, multi-tenant SaaS** — plus POS domain skills above.

---

## Growth path (big picture)

```text
NOW (GradeSphere)
  → ship safely, git, tests, backup, security basics
THEN (stronger Laravel)
  → CI/CD, queues/mail, Docker, performance, audit logs
THEN (POS MVP)
  → catalog + cart + stock + receipts (one shop)
THEN (perfect POS)
  → refunds, shifts, reports, multi-branch / SaaS
```

---

## Standard playbooks (copy these)

> **Easy deploy order + PowerShell/SSH commands:** see [`docs/DEPLOY-ORDER-CHEATSHEET.md`](DEPLOY-ORDER-CHEATSHEET.md)  
> **Git with GitHub Desktop:** see [`docs/GITHUB-DESKTOP-HABIT.md`](GITHUB-DESKTOP-HABIT.md)  
> **Bug → Pest habit:** see [`docs/BUG-TO-PEST-HABIT.md`](BUG-TO-PEST-HABIT.md)  
> **Named backups:** see [`docs/NAMED-BACKUPS-HABIT.md`](NAMED-BACKUPS-HABIT.md)  
> **Hostinger domain vs hosting:** see [`docs/HOSTINGER-DOMAIN-VS-HOSTING.md`](HOSTINGER-DOMAIN-VS-HOSTING.md)  
> **Edit/Delete map:** see [`docs/EDIT-DELETE-QA.md`](EDIT-DELETE-QA.md)

### Playbook: UI-only change (Blade / PHP, no DB)
```text
1. Change locally → click test
2. Commit (optional but recommended)
3. Upload changed files to gradesphere
4. php artisan view:clear
5. Live smoke test
```
`npm run build`? **Only if** CSS/JS/Vite assets changed.

### Playbook: Database change
```text
1. Backup live SQL → save with date + reason
2. Build migration locally
3. Migrate + Pest/manual test locally
4. Upload code (migration + app files)
5. SSH: php artisan migrate --force
6. view:clear if views changed
7. Live smoke test (old data still works + new field works)
```

### Playbook: Inspect a backup (Situation B)
```text
1. Laragon Start All
2. phpMyAdmin → create temp DB (e.g. gradesphere_backup_0916)
3. Import the .sql file into THAT database only
4. Browse tables / run SELECT
5. Never import over live just to "look"
```

### Playbook: Production bug
```text
1. Reproduce (who, which page, mobile/desktop)
2. Check laravel.log
3. Fix smallest root cause
4. Add/adjust test if possible
5. Deploy + verify
6. Update this roadmap "Debugging lessons"
```

---

## Session log (newest first)

### 2026-09-17 — Domain “rename” clarified
- **Updated:** `docs/HOSTINGER-DOMAIN-VS-HOSTING.md`  
- **Lesson:** domain names cannot be renamed like files; reuse old domain as-is or buy a matching new domain for the other system  

### 2026-09-17 — Deploy done + Hostinger domain/hosting explained
- **Deployed:** edit/delete confirms + mobile action CSS (`public/build` + views)  
- **Created:** `docs/HOSTINGER-DOMAIN-VS-HOSTING.md`  
- **Lesson:** hosting expiry ≠ domain expiry; renew hosting to keep site online  

### 2026-09-16 — Named backups habit completed
- **You did:** renamed backup with date + reason style  
- **Priority 1:** all four shipping habits complete (Git, Pest, Staging/Situation B, Named backups)  
- **Next:** Priority 2 — pick one (solo feature / laravel log first / draw data model)

### 2026-09-16 — Named backups lesson started
- **Created:** `docs/NAMED-BACKUPS-HABIT.md`  
- **Your action:** rename `u738629442_gradesphere.sql` to date+reason style  
- **Meaning:** clear backup filenames so you know when/why each copy exists  

### 2026-09-16 — Bug → Pest habit understood
- **You ran:** `php artisan test --compact tests/Feature/StudentDeleteCleanupTest.php`  
- **Result:** 4 passed (22 assertions)  
- **Lesson:** robot tests guard the orphan-student delete bug  
- **Rule to keep:** Bug → Fix → Pest test → Run → Commit  

### 2026-09-16 — Git milestone habit completed (GitHub Desktop)
- **Practiced:** create branch `fix/edit-delete-and-docs` → bring changes → commit → merge to `main` → push  
- **Learned:** tiny fixes can stay on main; bigger work uses branch → merge  
- **Dialog tip:** choose **Bring my changes to [new branch]** when switching with uncommitted work  
- **Next:** Bug → Pest test habit (`docs/BUG-TO-PEST-HABIT.md`)

### 2026-09-16 — Git habit guide (GitHub Desktop)
- **Created:** `docs/GITHUB-DESKTOP-HABIT.md`  
- **Your action now:** commit current Edit/Delete + docs work in GitHub Desktop  
- **After commit:** tick Git milestone; then Bug → Pest test lesson  

### 2026-09-16 — Edit/Delete local click check signed off
- **You verified:** ledger edit/update, delete cancel/OK, mobile actions, subject remove confirm, admin teacher save/delete confirm  
- **Status:** Edit/Delete QA pass accepted locally  
- **Next lesson:** Git milestone habit (GitHub Desktop)

### 2026-09-16 — Edit/Delete QA pass (desktop + mobile)
- **Created:** `docs/EDIT-DELETE-QA.md` (all delete confirms + edit paths)  
- **Fixed:** clearer/unified delete confirms; subject Remove confirm; mobile action button stacking  
- **Test added:** teacher can update existing report card  
- **CSS:** `app.css` action buttons — needs `npm run build` before live deploy  
- **Still next:** Git milestone habit → then Bug → Pest test habit  

### 2026-09-16 — Deploy cheatsheet rewritten as easy notes
- **Updated:** `docs/DEPLOY-ORDER-CHEATSHEET.md` — note format with when → why → how (less tables)  
- **Next learning focus:** Git milestone habit **or** Bug → Pest test  

### 2026-09-16 — Deploy order cheatsheet + Situation B marked done
- **Created:** `docs/DEPLOY-ORDER-CHEATSHEET.md` (Backup→…→Test live, UI vs DB vs CSS, PowerShell/SSH commands)  
- **Completed:** Situation B (local backup import + browse)  
- **Next learning focus:** Git milestone habit **or** Bug → Pest test  

### 2026-09-16 — Full system health check (tests + structure)
- **Tests:** 43 passed (152 assertions)  
- **Pint:** passed (PHP formatting OK)  
- **Routes:** 32 app routes, role portals clear  
- **Verdict:** Healthy for current school MVP — **not** “senior-perfect architecture,” but solid Laravel structure and safe to continue learning  
- **Next:** Priority 1 skill (Situation B drill / Git habit / bug→test)

### 2026-09-16 — Added POS aim + growth topics (CI/CD, Docker, etc.)
- **Updated:** Long-term aim section (perfect POS / business systems)  
- **Updated:** Priority 4 growth track — CI/CD, queues/mail, Docker, performance, multi-tenant SaaS, audit/payments, POS MVP steps  
- **Lesson:** GradeSphere skills transfer to POS; money apps need the same safe habits first  

### 2026-09-16 — Learning roadmap created + recent wins
- **Completed:** Teacher Remark live migrate + smoke test  
- **Completed:** Delete student cleanup (lookup no longer shows orphans)  
- **Completed:** Mobile subject remark UI (stacked cards)  
- **Completed:** Deployed both fixes together (no `npm run build` needed)  
- **Learned:** Backup is for *safe inspection* via local temp DB, not casual live overwrite  
- **Created:** This roadmap file  
- **Next learning focus:** *(choose with mentor)* Git milestone habit **or** finish Situation B restore drill **or** bug→test habit  

---

## How we update this file (mandatory)

After **every** learning task:

1. Tick completed checkboxes under “What you still need to learn” or “Completed”.
2. Add a new **Session log** entry (date + what + lesson + next).
3. If a new playbook/trap appears, add it under Debugging lessons or Playbooks.
4. Only then start the next single task.

---

## Next single task (fill when we start)

| Field | Value |
|---|---|
| **Task name** | Priority 2 — choose next lesson |
| **Goal** | Grow independence after shipping habits |
| **Success looks like** | One Priority 2 item started |
| **Options** | 5) Solo feature drill · 6) Laravel log first · 7) Draw the data model |
| **Update this file after?** | Yes |

---

*Teacher note: Growth is not “knowing everything.” Growth is repeating safe habits until they are boring — then adding the next habit.*
