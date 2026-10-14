# QuickMed - Drug Finder and Pharmacy Delivery Website (Laravel)

"Search before you move." Customers search for listed medicines, compare registered pharmacies, prices and availability, then visit the pharmacy or submit an order / delivery request.
QuickMed does **not** diagnose illnesses or prescribe medicines.

## Setup (Laravel 11 or 12, PHP 8.2+, Composer)

1. `composer create-project laravel/laravel quickmed`
2. Copy `app/`, `database/`, `resources/`, `routes/` from this project into it (overwrite when asked).
3. In `.env` set `APP_NAME=QuickMed`, add `APP_CURRENCY="₦"` (any symbol), and configure the database
   (SQLite works out of the box; for MySQL set `DB_CONNECTION=mysql`, `DB_DATABASE=quickmed`, `DB_USERNAME`, `DB_PASSWORD`).
4. `php artisan migrate --seed`
5. `php artisan serve` then open http://127.0.0.1:8000

## Demo accounts (password: `password`)
| Role | Email |
|---|---|
| Administrator | admin@quickmed.test |
| Pharmacy owner (verified) | owner1@quickmed.test, owner2@quickmed.test |
| Pharmacy owner (pending verification) | owner3@quickmed.test |
| Customer | customer@quickmed.test |

Change these passwords before showing the project publicly.

## Features
- Registration, login, logout (customer or pharmacy owner); role-based access (customer, pharmacy owner, admin)
- Medicine search by name, generic name or category, with area filter, price sorting and in-stock filter
- Pharmacy listing and detail pages with price and availability per medicine
- Pharmacy dashboard: profile, medicine/inventory CRUD, price/quantity/availability, incoming orders with status updates
- Customer orders (pickup or delivery request), order history, cancel pending orders; stock is reserved on order and restored on cancel/reject
- Admin dashboard: users, pharmacy verification, medicines CRUD, all orders
- Bootstrap 5 responsive interface (CDN, no npm build needed)
