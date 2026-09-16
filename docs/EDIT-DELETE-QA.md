# GradeSphere — Edit / Delete / Update notes (Easy)

> Where Edit & Delete live, what confirmation appears, and how to check desktop + mobile.  
> Last updated: 2026-09-16

---

## Golden habit

```text
Find the button → try it on desktop → try it on phone → confirm dialog must appear before delete
```

---

## All DELETE places in the system

### 1) Teacher → Class ledger → Delete
→ WHEN: teacher removes a report card from the ranked list  
→ CONFIRM message:
`Delete this report card? Rankings will be recalculated. If this is the student's only record, they will be removed from student lookup.`  
→ HOW: browser asks OK / Cancel → Cancel keeps data  
→ AFTER OK: card deleted; if last card, student leaves lookup  

### 2) Teacher → Dashboard → Recent report cards → Delete
→ WHEN: same delete from the dashboard table  
→ CONFIRM: same message as ledger (kept consistent)  
→ HOW: Edit / Delete buttons in Actions column  

### 3) Admin → Teachers list → Delete
→ WHEN: remove a teacher account from the directory  
→ CONFIRM:
`Remove this teacher account? They will no longer be able to sign in.`  
→ HOW: Actions column Delete button  

### 4) Admin → Dashboard → Teachers shortcut table → Delete
→ WHEN: same teacher delete from admin dashboard  
→ CONFIRM: same teacher message  

### 5) Admin → Edit teacher page → Delete teacher button
→ WHEN: delete from the teacher edit screen (bottom danger button)  
→ CONFIRM: same teacher message  
→ HOW: separate form under “Save changes”  

### 6) Teacher → Input grades → Remove subject row
→ WHEN: remove one subject line while entering marks  
→ CONFIRM:
`Remove this subject row? Marks entered here will be lost.`  
→ IF only one row left:
`Keep at least one subject row.`  
→ NOTE: this does not delete a student — only a form row before save  

---

## All EDIT / UPDATE places

### Teacher
```text
Ledger / Dashboard → Edit
  → opens Input Grades form for that student
  → change marks / remark / attendance
  → Save / Update record
  → returns to ledger with success
```

```text
My profile (teacher)
  → update name, username, password, photo
  → Save
```

### Admin
```text
Teachers → Edit
  → update name, username, email, password, active, permissions
  → Save changes
```

```text
Teachers → Edit → Full profile
  → deeper profile + photo for that teacher
```

```text
My profile (admin)
  → update own account
```

### Student
```text
No edit/delete of grades
  → lookup only + view report
```

---

## Desktop check

```text
[ ] Ledger Edit opens form with existing data
[ ] Update record saves and shows on ledger
[ ] Delete shows confirm → Cancel keeps row
[ ] Delete shows confirm → OK removes row
[ ] Admin teacher Edit → Save changes works
[ ] Admin teacher Delete shows confirm
[ ] Profile update works (admin + teacher)
```

---

## Mobile check (narrow phone or browser DevTools)

```text
[ ] Tables can swipe sideways to reach Actions
[ ] Edit + Delete buttons are easy to tap (stacked on small screens)
[ ] Delete confirm still appears on phone
[ ] Input Grades: Subject remark visible
[ ] Input Grades: Remove subject asks confirm
[ ] Forms (profile / teacher edit / grades) fields are full width and usable
[ ] Save / Update buttons are reachable
```

---

## What we fixed in this pass (2026-09-16)

```text
✓ Unified report-card delete confirm (dashboard = ledger meaning)
✓ Clearer teacher delete confirm (mentions they cannot sign in)
✓ Subject row Remove now asks confirm
✓ Last subject row cannot be removed (alert)
✓ Action buttons stack better on mobile for tapping
```

---

## Deploy note for this pass

Door type: mostly **UI (Door A)** + **CSS (Door C)** because `resources/css/app.css` changed.

```text
On PC → npm run build
  → Upload changed Blade views + public/build
  → SSH → php artisan view:clear
  → Test live desktop + mobile
```

See `docs/DEPLOY-ORDER-CHEATSHEET.md`.

---

## After this learning topic

Next Priority 1 habits still waiting:
1. Git milestone habit — branch + commit cleanly  
2. Bug → Pest test — turn one past bug into a lasting test habit  
