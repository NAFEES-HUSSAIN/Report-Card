# GradeSphere — manual QA checklist

## Admin (principal)
- [ ] Open `/admin/login` and sign in with `admin@gradesphere.test` / `password` (or username `principal`)
- [ ] Admin dashboard shows counts and portal shortcuts
- [ ] Teachers list → create teacher → assign permissions → save
- [ ] Edit teacher: deactivate account; confirm they cannot log in
- [ ] Edit teacher: revoke “Input & edit grades”; confirm form is 403
- [ ] Open Teacher portal and Student portal from admin nav
- [ ] Update own profile (name, username, password, photo)

## Teacher
- [ ] Login at `/teacher/login` with `teacher@gradesphere.test` / `password`
- [ ] Dashboard loads without errors
- [ ] Input grades: type class, choose term, add subjects, save
- [ ] Ledger shows the new student
- [ ] Search by name/index filters results
- [ ] Pagination appears when > 10 rows
- [ ] Edit existing student marks
- [ ] Profile page updates username/password
- [ ] Logout works

## Student
- [ ] Lookup with a real index number
- [ ] Dashboard shows average/standing/term
- [ ] Full report + print
- [ ] Sign out returns to lookup and blocks dashboard

## Security
- [x] Guest cannot open `/teacher/dashboard` or `/admin/dashboard` (redirects to splash — correct)
- [x] `/register` returns 404
- [ ] Student session cannot open teacher routes
- [ ] Teacher cannot open `/admin/dashboard`
- [ ] Inactive teacher cannot sign in

> Step B signed off locally: guest protected routes → splash; `/register` → 404.
