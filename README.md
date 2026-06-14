# ZAYIN GUEST HOUSE — Website & Booking System

> A complete website and room booking system for **Zayin Guest House**.
> Developed by **Hazeeq Haikal (Hazeeq Programming)**.

---

## Project Info

| Field | Details |
|---|---|
| Developer | Muhammad Hazeeq Haikal bin Roslan |
| Contact | 011-2432-6970 |
| Email | hazeeq.programming@gmail.com |
| Client | Zayin Guest House |
| Agreement Date | 13 June 2026 |
| Development Period | 2 Months (+ 1 Month Testing Phase) |
| Stack | PHP, MySQL, HTML, CSS, JavaScript |
| Hosting | InfinityFree (Free) |

---

## Scope of Work

### ✅ Included

- **Catalogue & Basic Info**
  - Brand display and amenities list
  - Photo gallery for all 8 rooms
  - Location map (Google Maps Embed)
  - House rules and deposit policy

- **UI/UX Design**
  - Responsive design (mobile-friendly)
  - Dropdown/Accordion for long text sections (terms & rules)

- **Live Booking Calendar**
  - Real-time room availability check
  - Booked rooms displayed as unavailable (greyed out)

- **Fullhouse Override Feature**
  - Owner can block specific dates for whole-house bookings only
  - Guests cannot book individual rooms on those dates

- **Automatic Invoice/Receipt Generation**
  - Official receipt with guest and booking details
  - Suitable for company or government staff expense claims

- **Admin Dashboard**
  - Secure login for the owner
  - Record and manage incoming bookings
  - Basic sales reports

### ❌ Not Included

- Third-party integrations (Booking.com, Agoda, Airbnb)
- Online payment gateway (FPX)
- Photography services

---

## Development Phases

```
Phase 1 — Setup & Project Structure
  └── Folder structure, database schema, wireframe

Phase 2 — Frontend (Public Pages)
  └── Landing page, gallery, room info, map, house rules

Phase 3 — Booking System
  └── Live calendar, availability check, booking form

Phase 4 — Fullhouse Override
  └── Block dates module for fullhouse-only bookings

Phase 5 — Automatic Invoice & Receipt
  └── PDF/receipt generation after booking is confirmed

Phase 6 — Admin Dashboard
  └── Login, manage bookings, sales reports

Phase 7 — Testing & Stabilisation (Month 3)
  └── Bug fixes, UAT, pre-launch preparation

Phase 8 — Launch
  └── Deploy to InfinityFree, domain setup
```

---

## Project Structure

Follows the **Single File Approach** — each page combines PHP, HTML, CSS, and JS in one `.php` file.

```
zayin-guesthouse/
│
├── 📁 admin/                    # Admin interface & functions
│   ├── dashboard.php            # Main admin dashboard
│   ├── manage_bookings.php      # View and manage all bookings
│   ├── manage_rooms.php         # Manage room details
│   ├── fullhouse.php            # Set Fullhouse Override dates
│   └── reports.php              # Basic sales reports
│
├── 📁 guest/                    # Guest-facing pages (public)
│   ├── booking.php              # Booking form & processing
│   ├── check_availability.php   # Room availability check (AJAX)
│   └── invoice.php              # Generate & display invoice/receipt
│
├── 📁 includes/                 # Shared reusable components
│   ├── config.php               # Database connection (PHP only, no HTML)
│   ├── header.php               # <nav> tag + CDN links (TailwindCSS, scripts)
│   └── footer.php               # <footer> tag (copyright, links)
│
├── 📁 assets/                   # Images only (logo, banner, room photos)
│   ├── logo.png
│   ├── banner.jpg
│   └── rooms/                   # Individual room images
│
├── database.sql                 # All CREATE TABLE & initial INSERT statements
├── index.php                    # Main landing page
└── README.md
```

### Structure Rules

| Rule | Description |
|---|---|
| **Single File** | Combine PHP + HTML + CSS + JS in one `.php` file per page |
| **No separate CSS/JS files** | Use CDN only (TailwindCSS via CDN) |
| **`/assets/` for images only** | No CSS or JS stored here |
| **`/includes/` required on every page** | Always include `config.php`, `header.php`, `footer.php` |
| **`database.sql`** | Schema backup — all `CREATE TABLE` and `ALTER TABLE` statements go here |

### Standard Page Template

```php
<?php include '../includes/config.php'; ?>
<?php include '../includes/header.php'; ?>

<!-- Page-specific styles (if needed) -->
<style>
    .custom-class { /* ... */ }
</style>

<!-- Page HTML content -->
<main class="container mx-auto p-4">
    <h1>Page Title</h1>
    <?php
        // PHP logic & database queries here
    ?>
</main>

<?php include '../includes/footer.php'; ?>

<!-- Page-specific scripts (if needed) -->
<script>
    // JavaScript logic here
</script>
```

---

## Database Tables

| Table | Description |
|---|---|
| `rooms` | Details of all 8 rooms (name, price, image, capacity) |
| `bookings` | All booking records |
| `guests` | Guest information |
| `blocked_dates` | Dates blocked for Fullhouse Override |
| `invoices` | Generated invoice/receipt records |
| `admin_users` | Admin panel accounts |

---

## Payment Details

| Stage | Amount | Status |
|---|---|---|
| Deposit (60%) | RM 480.00 | ⬜ Pending |
| Balance (40%) | RM 320.00 | ⬜ Pending |
| **Total** | **RM 800.00** | |

**Bank:** RHB Islamic Bank
**Account No.:** 15901800353109 *(Hazeeq Haikal / Hazeeq Programming)*

---

## Ownership

Upon full payment (RM 800.00):
- All **source code** is the sole property of **Zayin Guest House**
- All **database data** is the sole property of **Zayin Guest House**
- No mandatory monthly maintenance fee owed to the Developer

---

## Post-Project Maintenance

If changes are needed after the stabilisation phase:

| Work Type | Estimated Cost |
|---|---|
| Update images / text content | RM 40 – RM 80 |
| Add new features | RM 80 – RM 150 |
| Structural redesign | RM 100 – RM 150 |

---

## Technical Requirements

- PHP >= 7.4
- MySQL >= 5.7
- Web server with `.htaccess` support (Apache)
- Hosting: InfinityFree (managed by Developer)
- Domain: Client's responsibility (~RM 50–100/year)

---

*This document is updated continuously throughout the development phases.*
