# GradeSphere — Git milestone habit (GitHub Desktop)

> Learn git the way you use it: **GitHub Desktop** (clicks, not scary terminal).  
> Last updated: 2026-09-16

---

## STOP — read this if you feel confused

You asked: *“Do I commit every update to main? Or create a sub-branch for every feature and merge?”*

**Both are valid. Here is the simple truth:**

### Road 1 — Commit on `main` (what you do now)
```text
Change code → Commit to main → (optional) Push
```
→ WHEN: small solo project, tiny fixes, you are the only developer  
→ WHY: fast and simple  
→ OK for GradeSphere learning **if commits are clear and frequent**

### Road 2 — Feature branch → then merge to `main` (senior habit)
```text
New branch → Change code → Commit on branch → Merge into main → (optional) Push
```
→ WHEN: bigger feature, risky change, or you want `main` to stay “known good”  
→ WHY: if the experiment fails, `main` is still safe  
→ This is what teams use; good habit for POS later

### So what should YOU do?
```text
Tiny typo / one-line fix     → Road 1 (commit on main) is fine
A real feature / lesson pack → Road 2 (branch → commit → merge) practice this
```

**You were not wrong** committing to main.  
We are now teaching Road 2 so you understand both.

---

## Picture in your head

```text
main     = the school’s finished road (keep clean)
branch   = a side road where you build one feature
merge    = copy the finished side road back onto main
```

```text
main:     A --- B --- C --------------- E  (E = after merge)
                   \                 /
branch:             D1 --- D2 --- D3
                    (your feature work)
```

---

## Why seniors use git

```text
Save a snapshot of working code
  → can go back if something breaks
  → history shows WHY you changed things
  → safe before big experiments
```

**Milestone habit = after a feature works locally, make a commit.**  
Do not wait weeks with 50 mixed changes.

---

## Words you need (easy)

**Repository (repo)**  
→ your project folder tracked by git (`Report-Card`)

**Branch**  
→ a line of work  
→ `main` = safe / finished line  
→ `feature/...` = your experiment line (recommended)

**Commit**  
→ one saved snapshot + short message  
→ like a checkpoint in a game

**Push**  
→ send commits from your PC to GitHub website  
→ only when you want the cloud copy updated

**Pull**  
→ download commits from GitHub to your PC  
→ use if you work on two computers

---

## Golden order (GitHub Desktop)

```text
Work locally until it works
  → (optional) New branch
  → Review changes
  → Write clear Summary
  → Commit
  → (optional) Push to GitHub
```

---

## Practice WITH ME NOW (Road 2 — do this today)

Your Edit/Delete + docs work is still **uncommitted**.  
We will save it the senior way once.

### Step 1 — Create a branch (side road)
1. Open **GitHub Desktop**
2. Repo = **Report-Card**
3. Click **Current branch** (top) → it should say `main`
4. Click **New branch**
5. Name exactly:

```text
fix/edit-delete-and-docs
```

6. Create from **main** → Create branch  
7. Top should now show: `fix/edit-delete-and-docs` ← you are on the side road

### Step 2 — Commit on the branch (not on main yet)
1. Left: review changed files  
2. Include docs + blade + css + test (no `.env`)  
3. **Summary:**

```text
Improve edit/delete confirms and add learning deploy docs
```

4. Click **Commit to fix/edit-delete-and-docs**  
   → button text shows the **branch name**, not main — that is correct

### Step 3 — Merge into main (copy side road → main)
1. Click **Current branch** → choose **main** (switch back to main)
2. Menu: **Branch** → **Merge into current branch…**
3. Select `fix/edit-delete-and-docs`
4. Click **Create a merge commit** / **Merge**
5. Done → your work is now on `main` too

### Step 4 — (Optional) Push
1. Still on `main`
2. Click **Push origin**
3. Cloud GitHub now matches your PC

### Step 5 — Tell mentor
Reply: **`branch merge done`**

Then we tick Git habit and start **Bug → Pest**.

---

## After this practice — your daily rule

```text
Small fix?
  → stay on main → Commit to main → Push if you want

Bigger feature / lesson?
  → New branch → Commit on branch → switch to main → Merge → Push
```

You do **not** need a new branch for every tiny spelling change.

---

## Habit A — Commit on `main` (Road 1)

Use this while learning. Later prefer Habit B (branch).

### WHEN
→ a feature/fix works (you clicked and tested)

### WHY
→ lock the win before you break something else

### HOW (GitHub Desktop)

1. Open **GitHub Desktop**
2. Top left: make sure repo is **Report-Card** (or your GradeSphere repo name)
3. Left side: you see **changed files**
4. Click each file → right side shows what changed (green/red)
5. **Check** the files you want in this commit  
   → leave out secrets like `.env` (should stay untracked)
6. Bottom left box **Summary** (required) — short WHY message  
   Example: `Improve delete confirms and mobile edit actions`
7. Description (optional) — extra detail
8. Click **Commit to main**
9. Done — changes clear from the list

### Good Summary examples
```text
Add teacher remark field and student visibility
Fix deleted students still appearing in lookup
Improve mobile subject remarks layout
Document deploy order and edit/delete QA notes
```

### Bad Summary examples
```text
update
final
asdf
changes
```

---

## Habit B — Branch then commit (senior style)

### WHEN
→ starting a new feature or risky change

### WHY
→ `main` stays clean; experiment on a side line

### HOW (GitHub Desktop)

1. Click **Current Branch** (top)
2. Click **New Branch**
3. Name like:
   - `feature/publish-flag`
   - `fix/delete-confirms`
   - `docs/learning-roadmap`
4. Create branch from `main`
5. Do your work in Cursor
6. Back to Desktop → Review → Summary → **Commit to [your-branch]**
7. When feature is done and tested:
   - **Branch** menu → **Merge into main** (or open Pull Request on GitHub)
8. Switch back to **main**

For now: one branch per big lesson is enough.

---

## Habit C — Push to GitHub

### WHEN
→ after commit(s) you want backed up online  
→ or before switching computers

### WHY
→ PC crash / USB loss won’t wipe history

### HOW
1. After commit, Desktop shows **Push origin** (top bar)
2. Click **Push origin**
3. Wait until it finishes

**Teacher note:** Push is separate from Commit.  
Commit = save on PC. Push = copy to GitHub.

---

## What NOT to commit

```text
.env                  → passwords / DB secrets
node_modules/         → huge, reinstall with npm
vendor/               → usually ignored / reinstall with composer
storage/logs/*.log    → noise
```

If Desktop shows `.env` as a change → **uncheck it** and tell mentor.

---

## Practice right now (your current work)

You already finished Edit/Delete QA + learning docs. Those files are waiting to be committed.

### Suggested first milestone (GitHub Desktop)

**Summary:**
```text
Improve edit/delete confirms and add learning deploy docs
```

**Include these kinds of files:**
- `docs/LEARNING-ROADMAP.md`
- `docs/DEPLOY-ORDER-CHEATSHEET.md`
- `docs/EDIT-DELETE-QA.md`
- `docs/QA.md`
- teacher/admin blade fixes
- `resources/css/app.css`
- `tests/Feature/TeacherReportCardTest.php`

**Optional Description:**
```text
Clearer delete confirmations, subject-row remove confirm,
mobile-friendly action buttons, and student learning docs.
```

### Steps for you now
```text
1) Open GitHub Desktop
2) Select Report-Card repo
3) Review changed files (read a few diffs)
4) Paste the Summary above
5) Commit to main
6) (Optional) Push origin
7) Tell mentor: "commit done"
```

After that, Priority 1 item **Git every milestone** can be ticked.

---

## Mini checklist (print this)

```text
[ ] Feature works locally
[ ] Open GitHub Desktop
[ ] Review diffs (no .env)
[ ] Summary explains WHY
[ ] Commit
[ ] Push if you want GitHub backup
```

---

## Next lesson after Git

**Bug → Pest test habit**  
→ every real bug gets an automated test so it cannot silently return  

We start that after your first clean Desktop commit.
