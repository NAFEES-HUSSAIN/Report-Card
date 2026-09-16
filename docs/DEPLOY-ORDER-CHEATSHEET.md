# GradeSphere — Deploy Order Cheatsheet (Easy Notes)

> Simple notes: **when → why → how**.  
> Related: `DEPLOY.md`, `docs/HOSTINGER-BEGINNER.md`, `docs/LEARNING-ROADMAP.md`  
> Last updated: 2026-09-16

---

## Before anything — two places you type commands

**1) Windows PowerShell (your PC)**  
→ Used to open SSH, or run local things like `npm run build`  
→ This is NOT where `migrate` usually runs for live

**2) Hostinger server (after SSH)**  
→ Used for `view:clear`, `migrate --force`, etc.

Live app folder on server:

```text
~/domains/myschoolreports.site/public_html/gradesphere
```

Open server like this from PowerShell:

```powershell
ssh -p 65002 u738629442@217.21.90.68
```

→ type password → then you are on the server → run Laravel commands → type `exit` when done

---

## The golden order (always in your head)

```text
Backup → Build local → Test local → Deploy → Migrate → Test live
```

**1) Backup**  
→ WHEN: before database changes (and anytime you feel nervous)  
→ WHY: if something breaks, you can get old data back  
→ HOW: Hostinger phpMyAdmin → Export → save SQL to `D:\SEPTEMBER\Backups\`

**2) Build local**  
→ WHEN: every feature/fix  
→ WHY: fix mistakes on PC, not on school live site  
→ HOW: code in Cursor + Laragon

**3) Test local**  
→ WHEN: before upload  
→ WHY: catch bugs early  
→ HOW: click the pages; optional `php artisan test --compact`

**4) Deploy**  
→ WHEN: local looks good  
→ WHY: put new files on Hostinger  
→ HOW: File Manager / FTP upload only changed files

**5) Migrate**  
→ WHEN: only if you added/changed a migration (DB structure)  
→ WHY: live database needs the new column/table  
→ HOW: SSH → `php artisan migrate --force`  
→ SKIP this for pure UI / Blade changes

**6) Test live**  
→ WHEN: after every deploy  
→ WHY: prove the real website works  
→ HOW: teacher login, student lookup, mobile check — same as local

**Never skip Test live.**

---

## First question before deploy

```text
Did I change the database? (new migration / new column / new table)
```

```text
YES → use Door B (DB path)
NO  → Did I change CSS / JS / Tailwind / Vite files?
        YES → use Door C (assets path)
        NO  → use Door A (UI path)
```

---

## Door A — UI / Blade / PHP only (no DB)

**Example:** mobile subject remarks layout, text change, delete message text

**WHEN to use Door A**  
→ you changed views or PHP logic  
→ you did NOT add a migration file

**WHY this path is simple**  
→ live HTML/PHP updates only  
→ database stays the same  
→ no `migrate` needed

**HOW — step by step**

```text
Change on PC
  → Test on PC (click the page)
  → Upload changed files to Hostinger gradesphere folder
  → PowerShell: ssh into server
  → cd into gradesphere
  → php artisan view:clear
  → exit
  → Test on live website
```

**PowerShell (PC) → open server**

```powershell
ssh -p 65002 u738629442@217.21.90.68
```

**On server**

```bash
cd ~/domains/myschoolreports.site/public_html/gradesphere
php artisan view:clear
exit
```

**Why `view:clear`?**  
→ Laravel caches Blade pages  
→ clear = force live to show your new UI  
→ use after almost every Blade/UI upload

**Do NOT run**  
→ `migrate` (no DB change)  
→ `npm run build` (unless styles also changed — then Door C)

---

## Door B — Database change

**Example:** teacher remark column, any new migration

**WHEN to use Door B**  
→ you created/changed a file in `database/migrations/`  
→ live DB structure must change

**WHY backup is required**  
→ migrate changes real school data structure  
→ if something goes wrong, backup is your safety net

**HOW — step by step**

```text
Backup live DB (phpMyAdmin Export)
  → Build feature + migration on PC
  → Migrate + test on PC
  → Upload migration + related app/views files
  → PowerShell: ssh into server
  → cd into gradesphere
  → php artisan migrate --force
  → php artisan view:clear
  → exit
  → Test live (old data still works + new feature works)
```

**PowerShell (PC) → open server**

```powershell
ssh -p 65002 u738629442@217.21.90.68
```

**On server**

```bash
cd ~/domains/myschoolreports.site/public_html/gradesphere
php artisan migrate --force
php artisan view:clear
exit
```

**Why `migrate --force`?**  
→ applies new columns/tables on live  
→ `--force` is needed in production (no yes/no question)

**Why `view:clear` after migrate?**  
→ if you also uploaded Blade/PHP, clear views so UI matches new DB fields

**Backup file name tip**

```text
D:\SEPTEMBER\Backups\2026-09-16_before-teacher-remark_gradesphere.sql
         date     →    reason              →   db name
```

---

## Door C — CSS / JS / Tailwind / Vite changed

**Example:** edited `resources/css/app.css` or JS that Vite builds

**WHEN to use Door C**  
→ you changed frontend source files that Vite compiles  
→ OR live looks unstyled / old styles after a UI deploy

**WHY `npm run build` on PC**  
→ Vite creates hashed files like `app-xxxxx.css`  
→ Hostinger needs those built files in `public/build/`  
→ building on server is usually harder — build on PC, then upload

**HOW — step by step**

```text
On PC: cd to project
  → npm run build
  → Upload public/build/ (+ any Blade/PHP you changed)
  → PowerShell: ssh into server
  → view:clear
  → Test live (Ctrl+F5 if browser shows old CSS)
```

**On PC (PowerShell)**

```powershell
cd D:\SEPTEMBER\Report-Card
npm run build
```

**Then SSH + server**

```powershell
ssh -p 65002 u738629442@217.21.90.68
```

```bash
cd ~/domains/myschoolreports.site/public_html/gradesphere
php artisan view:clear
exit
```

**Important note**  
→ If you only changed Blade and reused Tailwind classes already in the project, you often skip `npm run build`  
→ If live CSS looks wrong → do Door C

---

## Command notes (PC)

**`ssh -p 65002 u738629442@217.21.90.68`**  
→ WHEN: every time you need live server commands  
→ WHY: opens Hostinger terminal from Windows  
→ HOW: run in PowerShell, enter password

**`cd D:\SEPTEMBER\Report-Card`**  
→ WHEN: before local build/test  
→ WHY: make sure you are in the project folder  
→ HOW: PowerShell on PC

**`npm run build`**  
→ WHEN: CSS/JS/Vite source changed  
→ WHY: create production files for live  
→ HOW: on PC only, then upload `public/build`

**`php artisan test --compact`**  
→ WHEN: before important deploys  
→ WHY: prove features still pass  
→ HOW: on PC in project folder

**`composer run dev`**  
→ WHEN: daily local coding  
→ WHY: run local app + Vite together  
→ HOW: on PC

---

## Command notes (Hostinger after SSH)

**Always first:**

```bash
cd ~/domains/myschoolreports.site/public_html/gradesphere
```

→ WHEN: every SSH session  
→ WHY: Laravel commands must run inside the app folder  

**`php artisan view:clear`**  
→ WHEN: after uploading Blade/UI (almost every deploy)  
→ WHY: show new pages, not old cached ones  

**`php artisan migrate --force`**  
→ WHEN: after uploading migrations  
→ WHY: update live database structure  
→ DO NOT use for pure UI uploads  

**`php artisan config:clear`**  
→ WHEN: `.env` / config feels wrong on live  
→ WHY: reload configuration  

**`php artisan cache:clear`**  
→ WHEN: strange cached data issues  
→ WHY: clear app cache  

**`php artisan route:clear`**  
→ WHEN: routes look stuck / wrong  
→ WHY: clear route cache  

**`exit`**  
→ WHEN: finished on server  
→ WHY: close SSH and return to Windows PowerShell  

---

## Things you usually do NOT run

**`npm run build` on the server**  
→ build on PC → upload `public/build`

**`composer install` for every small UI fix**  
→ only when PHP packages in `composer.json` changed

**`migrate` for Blade-only fixes**  
→ no DB change = no migrate

---

## Mini checklists

### UI-only (Door A)
```text
[ ] Tested locally
[ ] Uploaded changed files
[ ] SSH → view:clear
[ ] Tested live
```

### Database (Door B)
```text
[ ] Backup SQL saved (date + reason)
[ ] Tested locally (migrate + feature)
[ ] Uploaded migration + code
[ ] SSH → migrate --force
[ ] SSH → view:clear
[ ] Tested live (old + new)
```

### CSS/JS (Door C)
```text
[ ] npm run build on PC
[ ] Uploaded public/build
[ ] SSH → view:clear
[ ] Tested live (Ctrl+F5 if needed)
```

---

## Looking at a backup later (Situation B)

```text
Do NOT import backup over live just to look
  → Laragon Start All
  → phpMyAdmin
  → create temp DB (example: gradesphere_backup_0916)
  → Import your .sql into THAT temp DB only
  → Browse students / report_cards
```

→ WHY: live school data stays safe  
→ HOW: local “museum” copy of the backup  

---

## One-line teacher memory

```text
Backup → Build local → Test local → Deploy → Migrate (only if DB) → Test live
```

And after almost every upload on the server:

```bash
php artisan view:clear
```
