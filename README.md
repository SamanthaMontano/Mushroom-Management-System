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
| Module | Functionality |
|--------|---------------|
| Dashboard | Shows active production batches, the current month's harvest, sales and expenses, low-stock and pending-delivery counts, active employees, recent activity, sales trends, harvest-by-variety, production status, and upcoming harvests. |
| Production | Creates and updates growing batches with batch code, mushroom variety, substrate, bag quantity, inoculation and expected harvest dates, growing location, responsible employee, status, and notes. Status options include Incubation, Fruiting, Harvesting, Completed, and Failed. |
| Harvest | Records harvest weight and grade (A, B, C, or Reject) for an active production batch. A harvest can identify the employee who harvested it and includes notes. |
| Processing | Converts a harvest record into Fresh Pack, Dried, Powdered, Marinated, or Other output. It tracks input and output weights, output unit, process date, employee, status (In Progress or Completed), and notes. |
| Inventory | Stores raw materials and supplies with quantity, unit, reorder level, unit cost, supplier, and restock date. Inventory status is calculated automatically: Out of Stock at zero quantity, Low Stock at or below the reorder level, and In Stock otherwise. |
| Sales | Creates invoice transactions with customer details, product, quantity, unit, unit price, sale date, seller, and payment status (Paid, Pending, or Partial). The displayed total and report amount are calculated from quantity multiplied by unit price. It also provides printable receipts and automatically generated invoice numbers. |
| Delivery | Schedules fulfillment for a sale/invoice. Tracks delivery address and date, rider, contact number, delivery fee, status (Pending, In Transit, Delivered, or Cancelled), and notes. |
| Expenses | Records categorized expenses with an amount, date, description, and the employee who recorded it. Categories include Substrate, Utilities, Labor, Equipment, Transport, Packaging, Rent, and Other. |
| Employees | Manages staff accounts and employee details, including login username/password, position, role (admin/manager/staff), salary, hire date, and active/inactive status. |
| Reports | For owners only, provides a date-range summary of sales, expenses, net profit, harvested weight, sales by product, expenses by category, and production/harvest totals. Reports can be printed. |
| Settings | Lets the signed-in account review its details and change its password. |

## Interface features
- The application has a collapsible navigation sidebar, page and current-list search,
  and a responsive layout shared across modules.
- Light and dark themes apply across the application and are saved in the current
  browser.
- The notification menu surfaces current low/out-of-stock inventory, harvests due in
  the next seven days, and pending or in-transit deliveries. Read status and notification
  history are stored per account in that browser; alerts are based on current database
  conditions rather than fabricated sample activity.

## Data relationships and update effects

Changes made in one module are reflected in related screens and calculations:

- **Editing a sale** recalculates `total_amount` as `quantity × unit_price`. The updated
  `total_amount`, sale date, and product name immediately affect Dashboard monthly sales,
  the six-month sales chart, recent sales, date-range Reports, sales-by-product totals,
  and net profit (`sales - expenses`). Changing the payment status updates the sales record
  but does not exclude the sale from sales totals.
- **Deleting a sale** removes the sale and automatically deletes its delivery records
  through the database relationship. It also removes that sale from sales Dashboard totals,
  recent sales, and Reports.
- **Recording or editing a sale does not automatically reduce Inventory stock.** Sales
  and Inventory are currently separate records; inventory quantities must be maintained
  manually in the Inventory module.
- **Editing an inventory quantity or reorder level** recalculates the item's stock status
  and updates low-stock counts and low-stock alerts on the Dashboard.
- **Adding or editing a harvest** allows the production batch, harvest date, weight,
  grade, harvested-by employee, and notes to be changed. These edits update harvest totals and harvest-by-variety records
  on the Dashboard and date-range harvest totals on Reports. Deleting a harvest also
  deletes processing records linked to that harvest.
- **Adding or editing a processing record** updates recent processing activity on the
  Dashboard and production details linked through the harvest record.
- **Changing a production batch** updates batch status counts, active-batch counts,
  upcoming harvest lists, and the production summary in Reports. Deleting a production
  batch automatically deletes its linked harvest records and their linked processing
  records.
- **Updating a delivery status** changes pending/in-transit delivery counts on the
  Dashboard. Marking a delivery as Delivered or Cancelled removes it from the pending
  delivery count.
- **Editing or deleting an expense** updates the Dashboard's current-month expense
  amount, expense category totals, and net profit in Reports.
- **Changing an employee** updates employee names shown beside linked production,
  harvest, processing, and expense records. Deactivated employees remain in existing
  records but are excluded from active-employee counts and new-record dropdown lists.
  If an employee is deleted, related linked fields are set to null; production, harvest,
  processing, sales, and expenses are not deleted.

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
