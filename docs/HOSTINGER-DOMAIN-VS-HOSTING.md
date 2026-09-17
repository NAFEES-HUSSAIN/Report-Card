# Hostinger — Hosting vs Domain (Easy Notes)

> Answers: expire in 20 days? domain days? upgrade? change domain? subdomains?  
> Last updated: 2026-09-17

---

## Two different things (most important)

On Hostinger you usually pay for **two products**:

### 1) Hosting plan (the house)
```text
= the server where your Laravel files + MySQL database live
= GradeSphere app runs here
```

The banner **“Your hosting plan expires in 20 days”** means:
```text
In ~20 days your HOSTING subscription ends
  → if you do not renew, the website can stop working
  → files/DB may be suspended until you renew
```

**This is NOT automatically “domain expired in 20 days.”**

### 2) Domain name (the address)
```text
= the name people type: myschoolreports.site
= like a house address / phone number for your site
```

Domain is usually bought for **1 year** (sometimes more).  
It has its **own** expiry date in hPanel → Domains.

```text
Hosting expired  → house closed (site down) even if address exists
Domain expired   → address gone/parked even if house still exists
```

---

## So will my site die in 20 days?

```text
If you renew hosting before expiry → site keeps working
If you ignore hosting expiry → site can go offline / suspended
```

**Do this:**
```text
hPanel → look for Renew on hosting
  → renew before the 20 days end
```

Also check domain separately:
```text
hPanel → Domains → your domain → expiry date
```

---

## Renew vs Upgrade (Hostinger buttons)

### Renew
```text
WHEN: keep the same plan, pay for more time
WHY: site stays online with same package
HOW: click Renew on the hosting tip / billing
```

### Upgrade
```text
WHEN: you want more resources (disk, RAM, better plan)
WHY: bigger/faster hosting plan
HOW: click Upgrade
```

**Can I keep the same domain after upgrade?**  
→ **Yes.** Upgrade changes the hosting package, not the domain name.  
→ Your domain `myschoolreports.site` can stay pointed to the same site.

---

## When does my domain expire?

```text
Check in hPanel → Domains
  → each domain shows Expiry / Renewal date
  → often 1 year from purchase
```

Domain expiry ≠ hosting “20 days” message (unless they happen to be close by chance).

---

## Can I change to a more beautiful domain?

**Yes.** Example: keep app, change address to `gradesphere.lk` or `myschool.lk`.

### What you pay
```text
New domain name → usually pay for the new domain (yearly)
Hosting → you already pay hosting; you do not always need new hosting
```

### How (simple Hostinger idea)
```text
1) Buy/register the new domain in Hostinger (or transfer in)
2) Point/add it to the same website / hosting
3) In Laravel .env set APP_URL to the new domain (https://...)
4) Update any Hostinger SSL for the new domain
5) Test the site on the new address
```

Old domain can still exist if you keep paying for it.

---

## If I change domain, can I use the old domain for another system?

**Yes — if you still own/renew the old domain.**

```text
Old domain  → point to Project B (another folder / another site)
New domain  → point to GradeSphere (current system)
```

Same Hostinger account can host multiple sites (plan limits apply).

---

## Can I “rename” the old domain to match the new system?

**Short answer: you cannot rename a domain like renaming a file.**

```text
myschoolreports.site  = the name you bought
  → that spelling is fixed
  → you do NOT turn it into mypos.lk by “rename”
```

### What you CAN do

**A) Keep the old domain name, use it for another app**
```text
WHEN: you still like/own myschoolreports.site
WHY: no need to buy another domain for Project B
HOW: point myschoolreports.site → new site folder
NOTE: the URL people type stays myschoolreports.site
      (even if the app inside is a POS — name may not match branding)
```

**B) Buy a new domain that matches the new system (recommended for branding)**
```text
WHEN: new system needs a beautiful matching name (example: schoolpos.lk)
WHY: address matches the product
HOW: buy schoolpos.lk → point to Project B
PAY: yes, new domain usually has its own yearly fee
```

**C) “Rename” only the project/site label (not the public domain)**
```text
WHEN: you want Hostinger / Laravel name clearer
WHY: organize your account
HOW examples:
  - Hostinger website label: “GradeSphere Live”
  - Folder name: gradesphere / pos-app
  - Laravel APP_NAME=GradeSphere or APP_NAME=MyPOS
NOTE: visitors still use the domain you pointed (myschoolreports.site etc.)
```

### What you CANNOT do
```text
Cannot edit myschoolreports.site → become coolpos.site as a rename
  → that would be a different domain purchase
```

### Clear picture for your plan
```text
Today:
  https://myschoolreports.site → GradeSphere

Later option 1 (keep old domain for another app):
  nice-new-domain.com     → GradeSphere (school)
  myschoolreports.site    → other system (URL name stays the same)

Later option 2 (best branding):
  nice-school-domain.com  → GradeSphere
  nice-pos-domain.com     → POS system
  (old domain: keep, sell/transfer, or let expire if unused)
```

### Special question: can I rename `myschoolreports.site` → `pos.site`?

**No.** That is not a rename.

```text
myschoolreports.site  = one domain you already own
pos.site              = a DIFFERENT domain name
```

Even though both end with `.site`, they are two separate products:

```text
myschoolreports.site → your current school site address
pos.site             → only yours if you BUY it (if still available)
pos.lk / pos.com     → also different domains; each needs its own purchase
```

**So for a POS on the old domain you have two real choices:**

**Choice A — use old domain as-is (no new domain fee for POS)**
```text
https://myschoolreports.site  → put POS files here
```
→ works technically  
→ visitors still type **myschoolreports.site** (name does not become pos.site)

**Choice B — get a POS-looking address (pay for a new domain)**
```text
Buy something available, examples only:
  yourshop.site
  almanar-pos.site
  mytill.lk
  ...
Then point that new domain to the POS hosting folder
```
→ you **cannot** magically rename to `pos.site`  
→ you **can** buy `pos.site` only if Hostinger/registrar shows it available (short names are often taken)

**`.site` vs `.lk` vs `.com`**
```text
All are just domain endings (TLD)
  → none is a free “rename” of your current domain
  → pick one available name and register it
```

---

## Are there subdomains?

**Yes.** Examples:

```text
myschoolreports.site          → main GradeSphere
demo.myschoolreports.site     → test/staging copy
admin.myschoolreports.site    → optional (usually not needed)
shop.myschoolreports.site     → another app later (POS practice)
```

### Typical use for you
```text
main domain     → live school system
subdomain demo. → practice deploys / staging (Priority 2/4 learning)
```

In Hostinger:
```text
hPanel → Domains / Websites → Add website or Subdomain
  → create subdomain
  → point document root to another folder (or same app if intentional)
```

Subdomains are usually **included** with the domain (no second “full domain” fee), but hosting disk/site limits still apply.

---

## Quick answers

| Question | Answer |
|---|---|
| Site dies in 20 days? | Only if you **don’t renew hosting** |
| Is that domain expiry? | **No** — that’s hosting plan tip; check Domains for domain date |
| Upgrade keeps domain? | **Yes** |
| Change to nicer domain? | **Yes** — buy/point new domain; update `APP_URL` + SSL |
| Pay again for domain? | **Yes** for a **new** domain name (yearly) |
| Old domain for another app? | **Yes** if you still own it |
| Rename old domain to match new app? | **No rename** — keep old name, or **buy a new matching domain** |
| Rename Hostinger folder / APP_NAME? | **Yes** — that is project labeling, not the public domain |
| Subdomains? | **Yes** (e.g. `demo.yoursite.com`) |

---

## Safe habit for you

```text
Every few months in hPanel check:
  1) Hosting renewal date
  2) Domain renewal date
  3) Take a named DB backup before big changes
```

---

## Teacher tip

```text
Hosting = electricity + building
Domain  = street address sign
```

You need **both** working for people to open GradeSphere on the internet.
