# GradeSphere — Bug → Pest test habit (Easy Notes)

> Every real bug should leave behind a test, so it cannot silently return.  
> Last updated: 2026-09-16

---

## The habit in one line

```text
Bug found → Fix code → Write/adjust Pest test → Run test → Commit
```

---

## Why seniors do this

```text
You fixed a bug today
  → without a test, next month the bug can come back unnoticed
  → with a Pest test, `php artisan test` fails if the bug returns
```

This is how small school apps grow into safe POS / money apps later.

---

## Words (easy)

**Bug**  
→ something wrong for a real user (example: deleted student still looked up)

**Fix**  
→ change code so the wrong behaviour stops

**Pest test**  
→ a small PHP script that pretends to be a user/browser and checks the result

**Regression**  
→ an old bug that comes back after new changes

---

## WHEN to write a test

```text
YES → lookup wrong, delete wrong, permission wrong, save/update wrong, ranks wrong
MAYBE → pure CSS/mobile layout (hard to automate; still manual check)
NO need for every typo in docs
```

---

## HOW (simple steps)

```text
1) Write down the bug in one sentence
2) Fix the code
3) Add a Pest test that would FAIL if the bug came back
4) Run: php artisan test --compact path/to/Test.php
5) Commit (GitHub Desktop) with a clear Summary
```

### Good test idea examples
```text
Deleted last report card → student lookup is rejected
Teacher updates marks → new average is saved
Guest opens /teacher/dashboard → redirected to splash
```

---

## Your real example (already in this project)

### Bug (past)
```text
Teacher deleted report card
  → student row stayed in DB
  → student could still look up index and see name
```

### Fix
```text
On last report card delete → soft-delete student
Lookup only allows students who still have a report card
```

### Pest tests that guard it
File: `tests/Feature/StudentDeleteCleanupTest.php`

```text
✓ removes student from lookup after last report card deleted
✓ keeps student lookupable when another report card still exists
✓ rejects lookup for students with no report cards
✓ restores soft-deleted student when teacher saves same index again
```

**This is the Bug → Pest habit done correctly.**

---

## How to ask the agent (copy-paste phrases)

Use these exact lines in chat:

**Run an existing test**
```text
Run this Pest test and show me the result:
tests/Feature/StudentDeleteCleanupTest.php
```

**Write a test for a bug I found**
```text
We found this bug: [one sentence].
Please write a Pest feature test that fails if the bug comes back, then run it.
```

**Write a test for a feature we just built**
```text
Please add a Pest test for [feature name], run it, and tell me if it passed.
```

**After a fix**
```text
Bug is fixed. Add/update Pest test for it, run the narrow test file, then tell me the pass/fail result.
```

---

## How YOU confirm it (do not only trust chat)

After the agent runs tests, you should see something like:

```text
Tests:    4 passed (22 assertions)
```

### Confirm yourself in PowerShell
```powershell
cd D:\SEPTEMBER\Report-Card
php artisan test --compact tests/Feature/YourTestFile.php
```

### Green = good
```text
Tests:    X passed
```

### Red = not done yet
```text
FAILED
```
→ send the error to the agent: `This test failed, please fix.`

### Rule
```text
Agent says passed → you also run once → then trust it
```

---

## Practice for this lesson

### Step 1 — Run the bug-guard tests
In PowerShell (project folder):

```powershell
cd D:\SEPTEMBER\Report-Card
php artisan test --compact tests/Feature/StudentDeleteCleanupTest.php
```

→ Expect: all green / passed

### Step 2 — Understand one test (read it)
Open:

```text
tests/Feature/StudentDeleteCleanupTest.php
```

Read the first test title out loud:
`removes the student from lookup after their last report card is deleted`

Ask yourself:
```text
If I broke the fix tomorrow, would this test catch me? → YES
```

### Step 3 — New rule for every future bug
Write this on your wall:

```text
No bug is “done” until a Pest test exists (when it can be automated)
```

---

## Mini checklist

```text
[ ] Bug described in one sentence
[ ] Code fixed
[ ] Pest test added or updated
[ ] php artisan test --compact (that file) passed
[ ] Commit in GitHub Desktop
```

---

## After this lesson

Priority 1 item **Bug → Pest test** can be ticked when you:
1. Run `StudentDeleteCleanupTest` successfully  
2. Reply to mentor: **`pest habit understood`**
