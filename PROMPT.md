# GradeSphere — UI & product conventions

**Product name:** GradeSphere  
**Tagline:** Track. Grade. Grow.  
**Theme:** Deep violet primary + amber accent, class-based dark mode (`localStorage` key `gradesphere-theme`), Space Grotesk + Manrope.

## Stack
- Laravel + Blade + Tailwind CSS v4 (Vite)
- MySQL
- Dark mode via Tailwind `class` strategy (not `media`)
- Vanilla JS theme toggle (no Alpine)

## Screens
| View | Layout | Route name |
|------|--------|------------|
| `splash.blade.php` | guest | `splash` (`/`) |
| `auth/login.blade.php` | guest | `login` |
| `auth/register.blade.php` | guest | `register` |
| `teacher/dashboard.blade.php` | app | `teacher.dashboard` |
| `teacher/form.blade.php` | app | `teacher.form` / store / edit |
| `teacher/ledger.blade.php` | app | `teacher.ledger` |
| `student/lookup.blade.php` | guest | `student.lookup` |
| `student/dashboard.blade.php` | app | `student.dashboard` |
| `student/report.blade.php` | app | `student.report` |

## Components
- `x-stats-cards`, `x-report-card-transcript`, `x-dark-mode-toggle`, `x-nav-sidebar`, `x-theme-boot`

## Rules
- No Laravel branding in UI copy
- Semantic HTML; forms use `@csrf`, `old()`, `@error()`
- Subjects: `subjects[$i][name|marks|remarks]`
- Transcript forces light print styles (`.print-force-light`)
- CSS tokens in `:root` / `.dark` as `--gs-*` custom properties

## Seeded local accounts (after `php artisan migrate:fresh --seed`)
| Role | Email / Index | Password |
|------|---------------|----------|
| Admin | admin@gradesphere.test | password |
| Teacher | teacher@gradesphere.test | password |
| Teacher | teacher2@gradesphere.test | password |
| Student | STU-2026-0142 (Amina Rahman) | none — index lookup |

## Database (MySQL `:3308`, DB `report_card`)
Normalized: `academic_years` → `terms` / `school_classes` → `enrollments` / `report_cards` → `subject_scores`; `subjects` + `class_subject`; `students` (soft deletes); `users.role` teacher|admin.

## Data contract (`$student` presentation object)
`id`, `name`, `index_number`, `class_name`, `term`, `standing`, `rank`, `average`, `total_marks`, `days_present`, `days_absent`, `total_days`, `subjects[]` (`subject_id`, `name`, `marks`, `remarks`), plus `report_card_id`, `school_class_id`, `term_id`, `student_id`
