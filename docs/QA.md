# GradeSphere — manual QA checklist

## Teacher
- [ ] Login with seeded teacher account
- [ ] Dashboard loads without errors
- [ ] Input grades: type class, choose term, add subjects, save
- [ ] Ledger shows the new student
- [ ] Search by name/index filters results
- [ ] Pagination appears when > 10 rows
- [ ] Edit existing student marks
- [ ] Logout works

## Student
- [ ] Lookup with a real index number
- [ ] Dashboard shows average/standing/term
- [ ] Full report + print
- [ ] Sign out returns to lookup and blocks dashboard

## Security
- [ ] Guest cannot open `/teacher/dashboard`
- [ ] `/register` returns 404
- [ ] Student session cannot open teacher routes
