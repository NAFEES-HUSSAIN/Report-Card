# How to update GradeSphere after hosting

Your live site: `https://myschoolreports.site`

## Before sharing the link widely

1. `APP_DEBUG=false` in `gradesphere/.env`
2. Change admin password (and teacher password)
3. Test on phone: homepage → admin login → teacher login → student lookup
4. Verify domain email if Hostinger still shows a yellow warning

## Day-to-day use (no code update needed)

- Principal uses Admin portal
- Principal creates teachers
- Teachers enter grades
- Students use index lookup

## When you need a code/UI fix later

1. Fix and test on your PC first (`composer run dev`)
2. Rebuild assets if frontend changed: `npm run build`
3. Upload only changed files via Hostinger File Manager  
   (or make a new zip of changed folders)
4. SSH into server:

```bash
ssh -p 65002 u738629442@217.21.90.68
cd ~/domains/myschoolreports.site/public_html/gradesphere
php artisan config:clear
php artisan config:cache
php artisan route:cache
```

5. If database tables changed (new migration):

```bash
php artisan migrate --force
```

6. Hard refresh browser/phone (`Ctrl+F5`)

## Never do this on live

- Don’t set `APP_DEBUG=true` for long
- Don’t share `.env` or passwords in chat
- Don’t delete `gradesphere` folder

## If something breaks

1. Note the page + error/screenshot
2. Check log in SSH:

```bash
cd ~/domains/myschoolreports.site/public_html/gradesphere
tail -n 50 storage/logs/laravel.log
```

3. Tell me the error — we fix step by step
