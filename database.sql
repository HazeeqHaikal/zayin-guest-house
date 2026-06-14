-- ============================================================
-- ZAYIN GUEST HOUSE — Database Schema
-- ============================================================
-- File      : database.sql
-- Updated   : 2026-06-14
-- Purpose   : Full schema + seed data.
--             Import once via phpMyAdmin or MySQL CLI:
--               mysql -u USER -p DATABASE < database.sql
--
-- Tables (creation order respects FK dependencies):
--   1. rooms          — 8 guest rooms
--   2. users          — unified walk-in guests + registered customers
--                       password_hash NULL  = walk-in (no account)
--                       password_hash SET   = registered customer
--   3. bookings       — room reservations  (FK → users, rooms)
--   4. blocked_dates  — fullhouse / maintenance date blocks
--   5. invoices       — generated receipts  (FK → bookings)
--   6. admin_users    — owner/staff accounts (separate from users)
--
-- Key relationships:
--   bookings.user_id   → users.id
--   bookings.room_id   → rooms.id
--   invoices.booking_id → bookings.id
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+08:00";

-- ============================================================
-- 1. rooms
-- ============================================================
CREATE TABLE IF NOT EXISTS `rooms` (
    `id`               INT(11)        NOT NULL AUTO_INCREMENT,
    `name`             VARCHAR(100)   NOT NULL,
    `description`      TEXT           DEFAULT NULL,
    `capacity`         INT(11)        NOT NULL DEFAULT 2,
    `price_per_night`  DECIMAL(10,2)  NOT NULL,
    `image`            VARCHAR(255)   DEFAULT NULL,
    `room_type`        ENUM('standard','deluxe') NOT NULL DEFAULT 'standard',
    `is_active`        TINYINT(1)     NOT NULL DEFAULT 1,
    `created_at`       TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- 2. users  (unified table: walk-in guests + registered customers)
--    password_hash NULL  = walk-in (no account)
--    password_hash SET   = registered customer
-- ============================================================
CREATE TABLE IF NOT EXISTS `users` (
    `id`            INT(11)       NOT NULL AUTO_INCREMENT,
    `full_name`     VARCHAR(150)  NOT NULL,
    `ic_number`     VARCHAR(20)   DEFAULT NULL,
    `phone`         VARCHAR(20)   NOT NULL,
    `email`         VARCHAR(100)  DEFAULT NULL,
    `company`       VARCHAR(150)  DEFAULT NULL,
    `password_hash` VARCHAR(255)  DEFAULT NULL,
    `is_active`     TINYINT(1)    NOT NULL DEFAULT 1,
    `created_at`    TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_phone` (`phone`),
    UNIQUE KEY `unique_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- 3. bookings
-- ============================================================
CREATE TABLE IF NOT EXISTS `bookings` (
    `id`              INT(11)       NOT NULL AUTO_INCREMENT,
    `booking_code`    VARCHAR(20)   NOT NULL UNIQUE,
    `user_id`         INT(11)       NOT NULL,
    `room_id`         INT(11)       NOT NULL,
    `check_in`        DATE          NOT NULL,
    `check_out`       DATE          NOT NULL,
    `total_nights`    INT(11)       NOT NULL,
    `total_amount`    DECIMAL(10,2) NOT NULL,
    `deposit_amount`  DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `status`          ENUM('pending','confirmed','cancelled','completed') NOT NULL DEFAULT 'pending',
    `is_fullhouse`    TINYINT(1)    NOT NULL DEFAULT 0,
    `notes`           TEXT          DEFAULT NULL,
    `created_at`      TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `fk_booking_user` (`user_id`),
    KEY `fk_booking_room` (`room_id`),
    CONSTRAINT `fk_booking_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `fk_booking_room` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- 4. blocked_dates  (Fullhouse Override)
-- ============================================================
CREATE TABLE IF NOT EXISTS `blocked_dates` (
    `id`           INT(11)      NOT NULL AUTO_INCREMENT,
    `block_date`   DATE         NOT NULL,
    `block_type`   ENUM('fullhouse','maintenance') NOT NULL DEFAULT 'fullhouse',
    `reason`       VARCHAR(255) DEFAULT NULL,
    `created_at`   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_date` (`block_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- 5. invoices
-- ============================================================
CREATE TABLE IF NOT EXISTS `invoices` (
    `id`              INT(11)       NOT NULL AUTO_INCREMENT,
    `invoice_number`  VARCHAR(30)   NOT NULL UNIQUE,
    `booking_id`      INT(11)       NOT NULL,
    `issue_date`      DATE          NOT NULL,
    `subtotal`        DECIMAL(10,2) NOT NULL,
    `tax_amount`      DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `total_amount`    DECIMAL(10,2) NOT NULL,
    `payment_method`  VARCHAR(50)   DEFAULT NULL,
    `payment_status`  ENUM('paid','partial','unpaid') NOT NULL DEFAULT 'unpaid',
    `created_at`      TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `fk_invoice_booking` (`booking_id`),
    CONSTRAINT `fk_invoice_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- 6. admin_users
-- ============================================================
CREATE TABLE IF NOT EXISTS `admin_users` (
    `id`            INT(11)       NOT NULL AUTO_INCREMENT,
    `username`      VARCHAR(50)   NOT NULL UNIQUE,
    `password_hash` VARCHAR(255)  NOT NULL,
    `full_name`     VARCHAR(100)  DEFAULT NULL,
    `email`         VARCHAR(100)  DEFAULT NULL,
    `is_active`     TINYINT(1)    NOT NULL DEFAULT 1,
    `last_login`    TIMESTAMP     NULL DEFAULT NULL,
    `created_at`    TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- SEED DATA — 8 Rooms
-- ============================================================
INSERT INTO `rooms` (`name`, `description`, `capacity`, `price_per_night`, `image`, `room_type`) VALUES
('Standard Room A', 'A cozy queen-size room with air conditioning, ideal for couples or solo travelers.', 2, 80.00, 'assets/rooms/room-1.jpg', 'standard'),
('Standard Room B', 'Comfortable queen-size room with natural lighting and modern furnishings.', 2, 80.00, 'assets/rooms/room-2.jpg', 'standard'),
('Standard Room C', 'Clean and well-ventilated queen-size room with private bathroom.', 2, 80.00, 'assets/rooms/room-3.jpg', 'standard'),
('Standard Room D', 'Bright standard room with a queen-size bed and all essential amenities.', 2, 80.00, 'assets/rooms/room-4.jpg', 'standard'),
('Deluxe Room A', 'Spacious deluxe room with a king-size bed and premium furnishings.', 3, 110.00, 'assets/rooms/room-5.jpg', 'deluxe'),
('Deluxe Room B', 'Upgraded deluxe room with extra space, king-size bed, and modern decor.', 3, 110.00, 'assets/rooms/room-6.jpg', 'deluxe'),
('Family Room', 'Large family room with two double beds — perfect for families with children.', 5, 140.00, 'assets/rooms/room-7.jpg', 'standard'),
('Master Suite', 'Premium master suite with king-size bed, lounge area, and en-suite bathroom.', 4, 160.00, 'assets/rooms/room-8.jpg', 'deluxe');

-- ============================================================
-- SEED DATA — Admin User
-- ============================================================
-- IMPORTANT: Replace the password_hash below BEFORE going live.
-- Generate a new hash by running this PHP one-liner:
--   php -r "echo password_hash('YOUR_PASSWORD', PASSWORD_BCRYPT);"
-- Then UPDATE admin_users SET password_hash = 'NEW_HASH' WHERE username = 'admin';
--
-- Default login → username: admin | password: Admin@zayin2026  (CHANGE THIS!)
-- ============================================================
INSERT INTO `admin_users` (`username`, `password_hash`, `full_name`, `email`) VALUES
('admin', 'REPLACE_WITH_BCRYPT_HASH', 'Guest House Admin', 'admin@zayinguesthouse.com');

-- ============================================================
-- SEED DATA — Users (walk-in, no password)
-- These are sample walk-in guests without registered accounts.
-- password_hash defaults to NULL — they cannot log in.
-- ============================================================
INSERT IGNORE INTO `users` (`id`, `full_name`, `ic_number`, `phone`, `email`) VALUES
(1, 'Ahmad Firdaus bin Ramli',      '890514-03-5231', '0123456789', 'ahmad.firdaus@gmail.com'),
(2, 'Siti Nabilah binti Hassan',    '920301-07-6140', '0167891234', 'nabilah92@gmail.com'),
(3, 'Lim Wei Ming',                 '880720-10-4567', '0111234567', 'lim.weiming@outlook.com'),
(4, 'Rajesh a/l Subramaniam',       '950612-05-7890', '0197654321', NULL),
(5, 'Nur Izzati binti Mohd Zaidi',  '991105-14-3210', '0134567890', 'izzati99@gmail.com');

-- ============================================================
-- SEED DATA — Bookings
-- Covers: completed (past), confirmed (current + upcoming), pending, cancelled
-- ============================================================
INSERT IGNORE INTO `bookings`
    (`id`, `booking_code`, `user_id`, `room_id`, `check_in`, `check_out`,
     `total_nights`, `total_amount`, `deposit_amount`, `status`, `notes`) VALUES
(1, 'ZGH-20260510-AB12', 1, 1, '2026-05-10', '2026-05-13', 3, 240.00, 120.00, 'completed', NULL),
(2, 'ZGH-20260520-CD34', 2, 3, '2026-05-20', '2026-05-22', 2, 160.00,  80.00, 'completed', 'Extra pillows requested'),
(3, 'ZGH-20260601-EF56', 3, 5, '2026-06-01', '2026-06-05', 4, 440.00, 220.00, 'completed', NULL),
(4, 'ZGH-20260614-GH78', 4, 7, '2026-06-14', '2026-06-17', 3, 420.00, 210.00, 'confirmed', 'Family with 2 young children — needs extra towels'),
(5, 'ZGH-20260620-IJ90', 1, 2, '2026-06-20', '2026-06-22', 2, 160.00,  80.00, 'confirmed', NULL),
(6, 'ZGH-20260625-KL11', 5, 6, '2026-06-25', '2026-06-28', 3, 330.00, 165.00, 'pending',   'Late check-in, arriving around 10:00 PM'),
(7, 'ZGH-20260704-MN22', 2, 8, '2026-07-04', '2026-07-07', 3, 480.00, 240.00, 'confirmed', NULL),
(8, 'ZGH-20260515-OP33', 3, 4, '2026-05-15', '2026-05-17', 2, 160.00,  80.00, 'cancelled', 'Guest cancelled — changed travel plans');

-- ============================================================
-- SEED DATA — Blocked Dates
-- ============================================================
INSERT IGNORE INTO `blocked_dates` (`block_date`, `block_type`, `reason`) VALUES
('2026-07-01', 'maintenance', 'Annual deep cleaning & maintenance'),
('2026-07-02', 'maintenance', 'Annual deep cleaning & maintenance');

-- ============================================================
-- SEED DATA — Invoices (for completed + confirmed bookings)
-- ============================================================
INSERT IGNORE INTO `invoices`
    (`id`, `invoice_number`, `booking_id`, `issue_date`, `subtotal`, `tax_amount`,
     `total_amount`, `payment_method`, `payment_status`) VALUES
(1, 'INV-2026-0001', 1, '2026-05-13', 240.00, 0.00, 240.00, 'DuitNow QR', 'paid'),
(2, 'INV-2026-0002', 2, '2026-05-22', 160.00, 0.00, 160.00, 'DuitNow QR', 'paid'),
(3, 'INV-2026-0003', 3, '2026-06-05', 440.00, 0.00, 440.00, 'DuitNow QR', 'paid'),
(4, 'INV-2026-0004', 4, '2026-06-14', 420.00, 0.00, 420.00, 'DuitNow QR', 'partial'),
(5, 'INV-2026-0005', 5, '2026-06-20', 160.00, 0.00, 160.00, 'DuitNow QR', 'partial'),
(6, 'INV-2026-0006', 7, '2026-07-04', 480.00, 0.00, 480.00, 'DuitNow QR', 'partial');
