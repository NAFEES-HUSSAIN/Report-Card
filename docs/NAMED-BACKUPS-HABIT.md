# GradeSphere — Named backups habit (Easy Notes)

> Save database backups with clear names so future-you knows what each file is.  
> Last updated: 2026-09-16

---

## What is this?

A **backup** = a copy of your live database (SQL file).

A **named backup** = that file saved with a clear name:

```text
date + reason + database name
```

### Bad name (hard to understand later)
```text
u738629442_gradesphere.sql
backup.sql
db.sql
new.sql
```

### Good name (senior style)
```text
2026-09-16_before-teacher-remark_gradesphere.sql
2026-09-16_before-delete-fix_gradesphere.sql
2026-10-01_monthly_gradesphere.sql
```

```text
2026-09-16     → when you made it
before-teacher-remark → why you made it
gradesphere    → which database
```

---

## WHEN to take a backup

```text
ALWAYS before database migrate on live
ALWAYS before big risky live change
NICE monthly even if nothing big changed
```

---

## WHY named backups matter

```text
3 months later you ask:
  "Which file was before teacher remark?"
  → good name answers instantly
  → bad name = guess / open every file
```

For POS later (money), this habit is critical.

---

## WHERE to keep them

```text
D:\SEPTEMBER\Backups\
```

Do **not** put backups inside the Laravel project git folder if they contain real school data (keep private on your PC / safe drive).

---

## HOW — Hostinger export (create backup)

```text
1) hPanel → Databases → phpMyAdmin
2) Click your live database
3) Top tab: Export
4) Go / Export
5) Save the .sql file
6) Immediately rename it with date + reason
7) Move/save into D:\SEPTEMBER\Backups\
```

---

## HOW — Rename an old backup (practice today)

If you already have:

```text
u738629442_gradesphere.sql
```

Rename to something like:

```text
2026-09-16_before-teacher-remark_gradesphere.sql
```

(Use the real date you exported it, if you remember. From earlier: about 2026-09-16 morning.)

### Windows rename
```text
File Explorer → D:\SEPTEMBER\Backups\
  → right-click file → Rename
  → paste good name
  → Enter
```

---

## Mini checklist

```text
[ ] Backup folder exists: D:\SEPTEMBER\Backups\
[ ] New backup renamed with YYYY-MM-DD_reason_dbname.sql
[ ] You know WHEN to backup (before live migrate)
```

---

## After this lesson

Reply: **`named backup done`** when your file has a clear name.
