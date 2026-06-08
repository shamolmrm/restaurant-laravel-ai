# 🍽️ Restaurant Management System (RMS)

<p align="center">
  <img src="public/images/logo.png" alt="Restaurant RMS Logo" width="120" />
</p>

<p align="center">
  A complete, full-featured Restaurant Management System built with Laravel 12 and Bootstrap 5.
</p>

---

## Built With

| Technology | Version |
|---|---|
| PHP | 8.2+ |
| Laravel | 12.x |
| Bootstrap | 5.3 |
| MySQL / PostgreSQL | Latest |
| Chart.js | 4.x |
| Spatie Laravel Permission | 6.x |
| DomPDF (barryvdh) | 3.x |
| Font Awesome | 6.x |

---

## Features

- **Dashboard** — Real-time sales charts, KPIs, recent orders & low-stock alerts
- **POS (Point of Sale)** — Fast order entry with table/takeaway/delivery modes
- **Menu Management** — Categories, items, modifiers, pricing & availability
- **Order Management** — Track orders from kitchen to delivery
- **Table Management** — Floor plan with real-time table status
- **Kitchen Display (KDS)** — Live kitchen order queue
- **Inventory** — Stock tracking, low-stock alerts, supplier management
- **Employees** — Profiles, roles, attendance & salary
- **Customers** — CRM with loyalty points & purchase history
- **Delivery** — Assign & track delivery orders
- **Coupons & Discounts** — Flexible coupon system with usage limits
- **Reports** — Sales, inventory, customers, tax — export to PDF
- **Settings** — Restaurant info, business hours, tax & loyalty config
- **User & Role Management** — RBAC with 7 roles via Spatie Permission
- **Notifications** — In-app notification system

---

## User Roles

| Role | Description |
|---|---|
| Super Admin | Full system access |
| Admin | All modules except system settings |
| Manager | Orders, inventory, reports, staff |
| Cashier | POS, orders, payments |
| Waiter | Table orders, KDS view |
| Kitchen Staff | KDS, order status updates |
| Delivery | Delivery order management |

---

## Installation (Local)

```bash
git clone https://github.com/shamolmrm/restaurant-laravel-ai.git
cd restaurant-laravel-ai
composer install
cp .env.example .env
php artisan key:generate
# Configure database in .env
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Default login after seeding:

| Role | Email | Password |
|---|---|---|
| Super Admin | admin@restaurant.com | password |
| Manager | manager@restaurant.com | password |
| Cashier | cashier@restaurant.com | password |

---

## Developer

**Shamol**
Email: shamolbro@gmail.com
GitHub: [shamolmrm](https://github.com/shamolmrm)

---

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
