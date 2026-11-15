# CMD — Cultivation & Mushroom Data Management System

A full-featured PHP MVC inventory and operations system for a mushroom farm/business:
Production, Harvest, Processing, Inventory, Sales, Delivery, Expenses, Employees, and
a Reports dashboard with date-range filtering — all behind a login screen.

## Tech stack
- PHP 8+ (plain PHP, no framework — custom lightweight MVC)
- MySQL / MariaDB (PDO, prepared statements throughout)
- Bootstrap 5 + Bootstrap Icons (via CDN)
- Chart.js (via CDN, used on the Dashboard)

## Folder structure
```
mushroom-system/
├── app/
│   ├── controllers/     # One controller per module
│   ├── models/          # One model per table (extends core/Model.php)
│   ├── core/             # App.php (router), Controller.php, Model.php (base classes)
│   ├── views/            # layouts/ (header, sidebar, footer) + one folder per module
│   └── helpers.php       # e(), peso(), niceDate(), csrf, statusBadge(), etc.
├── config/
│   └── database.php      # PDO connection settings — EDIT THIS
├── public/                # Web root — point your server here
│   ├── index.php          # Front controller / router entry point
│   ├── .htaccess          # Pretty URL rewriting (Apache + mod_rewrite)
│   ├── images/             # Local mushroom farm photo and transparent CMD logo
│   ├── css/style.css
│   └── js/app.js
└── database/
    └── mushroom_db.sql    # Full schema + seed data
```

## Setup (XAMPP / Apache + MySQL)

1. **Copy the project.** Place the whole `mushroom-system` folder inside your
   `htdocs` (XAMPP) or `www` folder.

2. **Create the database.** Open phpMyAdmin (or the `mysql` CLI) and import
   `database/mushroom_db.sql`. This creates the `mushroom_db` database, all
   tables, and sample/seed data.

3. **Configure the connection.** Edit `config/database.php` if your MySQL
   username/password differ from the defaults (`root` / empty password).

4. **Set the web root to `public/`.**
   - Easiest: in XAMPP, either move/symlink so Apache's `DocumentRoot` points
     at `mushroom-system/public`, **or**
   - Just browse to `http://localhost/mushroom-system/public/` — routing
     works either way because `BASE_URL` is auto-detected.
   - Make sure Apache's `mod_rewrite` is enabled (it is by default in XAMPP)
     so the `.htaccess` pretty-URL rewriting works.

5. **Log in.** Go to `.../public/auth/login`.
   - Choose **Owner** and use `admin` / `password123`.
   - Choose **Employee** and use `mstaff` or `preyes` / `password123`.
   - The selected account type must match the role assigned to that username.
   - **Change these passwords** (via the Employees module) before using this
     in a real/production setting.

## Account access
- `admin` accounts are **Owners**. Owners can view business and operations
  dashboards, expenses, reports, and employee accounts.
- `manager` and `staff` accounts are **Employees**. Employees can use the farm
  operations, sales, and delivery modules, but cannot open expense reports or
  employee administration.
- The Owner/Employee choice on the sign-in form is checked against the
  authenticated account; it does not grant access by itself. Owner-only routes
  are also enforced by the server, not just hidden from the navigation.
- The sign-in page uses a local mushroom cultivation photo at
  `public/images/mushroom-farm.jpg`, so that image does not need to be fetched
  from an external host at runtime.
- The transparent farm logo is stored at `public/images/cmd-logo.png` and is
  used in the sign-in page and application navigation.

## Modules included
| Module      | What it does |
|-------------|---------------|
| Dashboard   | KPI cards, sales trend chart, harvest-by-variety chart, recent activity, low-stock alerts |
| Production  | Track growing batches: variety, substrate, bag count, inoculation → harvest dates, status |
| Harvest     | Log yields per batch, with grade (A/B/C/Reject) |
| Processing  | Convert raw harvest into fresh-pack / dried / powdered product, tracking input vs output weight |
| Inventory   | Raw materials & supplies with auto-computed stock status and reorder alerts |
| Sales       | Invoices, customer info, product, quantity, pricing, payment status, printable receipts |
| Delivery    | Schedule and track fulfillment tied to a sale/invoice |
| Expenses    | Categorized expense logging |
| Employees   | Staff accounts, roles (admin/manager/staff), login credentials |
| Reports     | Date-range P&L: sales vs expenses, sales by product, expenses by category, production summary, printable |
| Settings    | Signed-in account details and password change |

## Notes on the architecture
- **Routing:** `public/index.php?url=controller/action/param` (rewritten by
  `.htaccess` to clean URLs like `/production/store`). See `app/core/App.php`.
- **Auth:** session-based; `Controller::requireAuth()` guards every
  controller except `AuthController`. Owner-only expenses, reports, and
  employee administration use server-side role guards.
- **Security:** all queries use PDO prepared statements; passwords are
  hashed with `password_hash()`/`bcrypt`; output is escaped via the `e()`
  helper; a CSRF token helper (`csrfToken()`/`verifyCsrf()`) is included in
  `helpers.php` and wired into the session — add `csrfField()` to any new
  forms you create and call `verifyCsrf()` in the matching controller action.
- **CRUD pattern:** each module's `index()` lists records and renders "Add"
  and "Edit" as Bootstrap modals on the same page (no separate create/edit
  pages), posting to `store()` / `update($id)` / `delete($id)`.

## Extending it
To add a new module (say, "Customers"):
1. Add the table to `database/mushroom_db.sql`.
2. Create `app/models/Customer.php` extending `Model`.
3. Create `app/controllers/CustomerController.php` extending `Controller`
   with `index()`, `store()`, `update($id)`, `delete($id)`.
4. Create `app/views/customers/index.php` following the pattern used in
   `app/views/inventory/index.php`.
5. Add a sidebar link in `app/views/layouts/sidebar.php`.
