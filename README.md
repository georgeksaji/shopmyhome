# ShopMyHome / OHAS

A demo online home-appliance store written in procedural PHP with MariaDB/MySQL.
It covers the full order lifecycle: browsing the catalogue, cart and checkout,
card payment, courier assignment, shipping, delivery, invoices, purchase
reporting, and role-specific dashboards.

> This is a learning/demo project. It is not connected to a real payment
> gateway and must not be used with real card data.

## Features

- Public catalogue: categories, types, brands, appliance details, search
- Customer self-registration, login, profile, saved cards and order history
- Cart with stock checks, checkout, and a simulated card payment
- Admin dashboard: staff, couriers, vendors, catalogue and purchase management
- Staff dashboard: purchases, inventory and courier assignment
- Courier dashboard: accept, ship, deliver, or mark a delivery unreachable
- Invoices and purchase/sales reports

## Requirements

- PHP 8.0+ (tested with the PHP bundled in XAMPP 8.2)
- MariaDB 10.4+ / MySQL 5.7+
- The `mysqli` and `curl` PHP extensions (enabled by default in XAMPP)
- No Composer install is required for the running application. The `vendor/`
  directory is left over from the retired PDF generator and is not loaded.

## Setup

1. Start **MySQL** in the XAMPP Control Panel.

2. Create the schema and load the demo data from the project root:

   ```bat
   C:\xampp\mysql\bin\mysql.exe -u root --default-character-set=utf8mb4 < database\ohas_schema.sql
   C:\xampp\mysql\bin\mysql.exe -u root --default-character-set=utf8mb4 < database\ohas_seed.sql
   ```

   The scripts drop and recreate the tables in the `ohas` database. Running
   them again resets the demo to a clean state.

3. Start the PHP built-in server from the project root:

   ```bat
   cd /d D:\Projects\shopmyhome
   C:\xampp\php\php.exe -S 127.0.0.1:8000 router.php
   ```

   `router.php` blocks direct downloads of `database/`, `vendor/`, IDE
   metadata, dotfiles, SQL/archive files, and PHP execution inside the upload
   folders. Do not expose the built-in server to the internet; it is intended
   for local development only.

4. Open <http://127.0.0.1:8000/>.

### Configuration

Defaults match a stock XAMPP install (`root`, empty password, database `ohas`).
Override them with environment variables before starting PHP:

| Variable | Default |
| --- | --- |
| `OHAS_DB_HOST` | `localhost` |
| `OHAS_DB_USER` | `root` |
| `OHAS_DB_PASS` | *(empty)* |
| `OHAS_DB_NAME` | `ohas` |
| `OHAS_DEBUG` | `0` |

With `OHAS_DEBUG=1`, PHP errors are displayed in the browser. Leave it unset
for normal use; errors are written to the PHP error log instead.

## Demo accounts

| Role | Email | Password | Landing page |
| --- | --- | --- | --- |
| Admin | `admin@shopmyhome.com` | `Admin@123` | `admin.php` |
| Staff | `staff@shopmyhome.com` | `Staff@123` | `staff.php` |
| Courier | `courier@shopmyhome.com` | `Courier@123` | `courier.php` |
| Customer | `customer@shopmyhome.com` | `Customer@123` | `cart.php` |

The admin account is linked to staff record `ST00001`; the staff demo is
`ST00002`; the courier is `CR00001`; the customer is `CU00001`.

Passwords are stored as bcrypt hashes. Self-registered customers choose their
own password; staff, courier and vendor accounts are created from the admin
dashboard or the registration pages.

## Project layout

```
bootstrap.php          Shared config, error handling, DB helpers, auth, CSRF,
                       ID validation and upload handling
connection.php         Bootstraps the app and exposes $conn (mysqli)
database/              Canonical schema and seed data
router.php             Development-server guard for private paths
assets/
  css/                 Bootstrap stylesheets
  js/                  Bootstrap and report/PDF libraries
  images/
    backgrounds/       Page backgrounds
    banners/           Carousel and invoice artwork
    icons/             Navigation, cart and profile icons
    features/          Storefront feature graphics
    social/            Social and contact icons
    dashboard/         Dashboard card icons
    misc/              Legacy decorative GIFs
  uploads/
    brand_images/      Uploaded brand logos
    product_images/    Uploaded appliance images
Login.php              Authentication and role-based landing
admin.php / staff.php / courier.php
                       Role dashboards and order workflows
cart.php / payment.php Checkout and simulated payment
product_details.php    Product page and add-to-cart
list_*.php             Catalogue browsing
Invoice.php            Printable invoice
profile*.php           Customer/staff profile pages
Update_*.php           Admin edit forms
```

The root now contains only PHP pages, configuration, SQL, and documentation.
Old static HTML prototypes, a generated `Sales report.pdf`, and other
unreferenced files were moved to `D:\Projects\shopmyhome_legacy` (outside the
web root) instead of being deleted.

## Security notes

The application was hardened as part of the database reconstruction:

- All database access uses prepared statements or validated identifiers.
- Passwords use `password_verify()` / `password_hash()` (bcrypt). Existing
  plain-text rows are upgraded once at login.
- Sessions use `HttpOnly`, `SameSite=Lax` cookies, strict mode, and are
  regenerated on login.
- Every POST request requires a per-session CSRF token; requests without a
  valid token receive HTTP 403.
- Role checks (`AD`, `ST`, `CR`, `CU`) protect every dashboard and action.
- User-controlled output is escaped with `e()` (`htmlspecialchars`).
- Uploads are checked by image type/size, renamed randomly, and limited to
  5 MB.
- CVV is never stored. Card numbers are stored for the demo but masked in the
  UI; do not enter real card details.
- Error details are logged, not displayed, unless `OHAS_DEBUG=1`.
- The `success_page.php` return URL only accepts a local `.php` page, and
  invoice/cart/card views verify ownership.

## Resetting the demo

Re-run the two SQL files from step 2. This removes any test accounts and
orders and restores the demo catalogue and accounts listed above.
