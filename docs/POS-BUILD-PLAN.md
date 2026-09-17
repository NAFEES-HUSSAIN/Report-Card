# POS System — Senior Build Plan (MVP → Strong → Perfect)

> **Product name:** ShopPOS  
> **Folder (target):** `D:\SEPTEMBER\POS`  
> Written before `laravel new`, so we build smart like GradeSphere.  
> Last updated: 2026-09-17  
> Audience: you (student growing to mid) + mentor

---

## Locked decisions

```text
Product:   ShopPOS
v1 stack:  Laravel 13 + Blade + Tailwind (+ Alpine)
Auth:      Owner / Cashier / Manager
API/React: later, same project
```

## Honest starting point

You already proved this loop on GradeSphere:

```text
Plan → Build local → Test → Backup → Deploy → Fix → Commit
```

POS uses the **same loop**, but money/stock rules are stricter.

**Goal now:** a **solid POS MVP** (one shop), not a perfect multi-branch SaaS on day one.

---

## Recommended stack (for YOU)

### Backend
```text
Laravel 13 (PHP)
```
→ You already know it  
→ Hostinger-friendly  
→ Same deploy habits

### Frontend (MVP — recommended)
```text
Laravel Blade + Tailwind CSS + Alpine.js
(optional later: Livewire for reactive cart without full SPA)
```

**Why not React/Vue SPA first?**
```text
You would learn POS domain + new frontend stack at the same time
  → slower, more bugs, harder on shared Hostinger
Blade kept GradeSphere shipping — use that strength first
```

### Frontend later (optional upgrade)
```text
Inertia.js + Vue/React
  → when MVP works and you want a more “app-like” UI
```

### API (when needed — not day 1)
```text
Laravel Sanctum API
  → mobile cashier app
  → barcode scanner integrations
  → separate frontend later
```

**Senior rule:**  
Ship POS screens with Blade first. Add API when a real device/app needs it.

---

## Extra sides (security & product) — plan by phase

### Authentication (who can log in)
```text
Email/username + password
Roles: Owner, Manager, Cashier
Session auth for web (same idea as teacher/admin login)
```

### Authorization (who can do what)
```text
Cashier  → sell, take payment, basic void (limited)
Manager  → refunds, discounts, stock adjust
Owner    → users, settings, reports, all permissions
```
Use Laravel policies / gates / permission tables (like GradeSphere permissions).

### Data protection
```text
HTTPS only on live
APP_DEBUG=false on live
Never commit .env
Named DB backups before migrate
Soft-delete / audit for sales voids (never silent delete of money history)
```

### High-security habits (money)
```text
Audit log: who voided / refunded / changed price
Idempotent checkout (don’t double-charge on double-click)
Shift open/close (cash drawer accountability) — phase 2
Server-side price calc (never trust only browser totals)
```

### API / tokens (later)
```text
Laravel Sanctum for token auth
Rate limiting
No secrets in frontend
```

---

## What a POS MVP includes (v1)

Build these first:

```text
1) Auth + roles (Owner / Cashier at minimum)
2) Products (name, SKU/barcode optional, price, stock qty)
3) Categories (simple)
4) Checkout / cart (add item, qty, total, pay cash)
5) Sale + sale items saved in DB
6) Stock decrease on sale
7) Receipt page (print-friendly)
8) Sales history (search by date/receipt no.)
9) Basic dashboard (today’s total sales)
10) Deploy playbook (same as GradeSphere)
```

### Explicitly NOT in v1
```text
Multi-shop / multi-tenant SaaS
Card gateway / online payments
Advanced accounting
Loyalty / CRM
Full hardware suite (can add printer later)
CI/CD, Docker, queues/mail (optional later — not urgent)
```

Those “optional later” items:

```text
CI/CD, queues/mail, Docker, performance tuning, multi-tenant SaaS
```

→ after MVP is live and stable.

---

## Data model (v1 sketch)

```text
users
roles / permissions

categories
products
  → stock_qty, price, sku

sales
  → receipt_no, user_id, total, paid, change, status (completed/void)
sale_items
  → sale_id, product_id, qty, unit_price, line_total

stock_movements (simple log)
  → product_id, qty_change, reason (sale/adjust), user_id

settings (shop name, currency)
```

---

## Build phases (like school system)

### Phase 0 — Project setup (now)
```text
Protect docs
Create Laravel project
Put learning docs into /docs
GitHub Desktop new repo
Local run (Laragon)
```

### Phase 1 — Skeleton
```text
Auth login
Roles
Admin layout / cashier layout
Empty dashboard
Deploy empty shell once (optional) to prove hosting path
```

### Phase 2 — Catalog + stock
```text
Products CRUD
Stock quantity
Pest tests for create/update product
```

### Phase 3 — Selling (core POS)
```text
Cart UI
Checkout
Save sale + reduce stock
Receipt
Pest: sale reduces stock exactly once
```

### Phase 4 — History + safety
```text
Sales list / search
Void sale rules (permission + audit)
Named backups before any money-related migrate
```

### Phase 5 — Harden
```text
Reports (daily total)
Shift open/close (optional)
Security checklist
Then consider API / Inertia / SaaS
```

---

## Deploy strategy (same discipline)

```text
Backup → Build local → Test local → Deploy → Migrate → Test live
```

Use your existing notes:
- `DEPLOY-ORDER-CHEATSHEET.md`
- `GITHUB-DESKTOP-HABIT.md`
- `BUG-TO-PEST-HABIT.md`
- `NAMED-BACKUPS-HABIT.md`
- `HOSTINGER-DOMAIN-VS-HOSTING.md`

POS gets its **own** Hostinger site/folder/DB when ready (don’t mix with GradeSphere live).

---

## Mentor vs you (same as GradeSphere)

| You | Mentor |
|---|---|
| Decide MVP scope / shop needs | Propose architecture |
| Click Hostinger / GitHub Desktop | Guide commands & code |
| Smoke test every phase | Write/review Pest tests with you |
| Own backups & go-live | Catch money-safety traps |

---

## Decision locked (proposed)

```text
Backend:     Laravel 13
Frontend:    Blade + Tailwind (+ Alpine as needed)
Auth:        Session login + roles/permissions
API:         Later (Sanctum), not v1 blocker
Hosting:     Hostinger Method B style (like GradeSphere)
Quality:     Pest for money/stock bugs; named backups; git milestones
```

If you agree, next step is only:

```text
Create Laravel project in empty Report-Card root
Restore docs into /docs
Write POS Learning Roadmap
Start Phase 0/1
```

---

## Your reply options

Reply one line:

```text
AGREE STACK — create Laravel now
```

or ask to change frontend (e.g. Inertia/React) before we install.
