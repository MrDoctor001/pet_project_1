# Thousand

A Laravel e-commerce pet project: product catalog, shopping cart, checkout, customer profile, and an admin panel. Built as a learning project with real store flows and a solid test suite.

**Live demo:** [https://event-hub.kz](https://event-hub.kz)

## Features

**Customers**
- Catalog with categories and search, product detail pages
- Cart: add, update quantity, remove items
- Checkout with stock checks (authenticated users only)
- Registration, login, and password reset via email
- Profile: edit details, change password, order history with statuses
- Responsive layout for mobile

**Admins**
- Dashboard: orders, new orders, revenue, out-of-stock items, latest orders
- Products: search, create/edit, image upload, hide from catalog, delete
- Orders: filter by status, search by name/phone, view, update status
- Categories: create, rename, delete (empty categories only)
- Access restricted to admin users

## Tech stack

- PHP 8.3+, Laravel 13
- MySQL
- Blade, Tailwind CSS 4, Vite
- Pest (100+ automated tests)

## Local setup

Requirements: PHP 8.3+ (`pdo_mysql`, `gd`, `fileinfo`), Composer, Node.js, and MySQL.

```bash
git clone https://github.com/MrDoctor001/pet_project_1.git
cd pet_project_1

# create an empty MySQL database (e.g. pet_project_1) and set DB credentials in .env if needed
composer run setup
php artisan db:seed

composer run dev
```

App runs at [http://localhost:8000](http://localhost:8000).

Seeding creates demo data: 6 categories, 36 products with images, and sample users.

**Demo admin:** `admin@example.com` / `password`

## Tests

```bash
php artisan test
```

---

**Live site:** [https://event-hub.kz](https://event-hub.kz)
