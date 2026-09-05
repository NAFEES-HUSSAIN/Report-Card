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

## Data contract (`$student`)
`id`, `name`, `index_number`, `class_name`, `term`, `standing`, `rank`, `average`, `total_marks`, `days_present`, `days_absent`, `total_days`, `subjects[]` (`name`, `marks`, `remarks`)
