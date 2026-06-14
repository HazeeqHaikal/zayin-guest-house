-- ============================================================
-- ZAYIN GUEST HOUSE — Database Schema
-- File    : database.sql
-- Purpose : Backup of all CREATE TABLE and seed data.
--           Import this file via phpMyAdmin or MySQL CLI.
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
-- 2. guests
-- ============================================================
CREATE TABLE IF NOT EXISTS `guests` (
    `id`          INT(11)       NOT NULL AUTO_INCREMENT,
    `full_name`   VARCHAR(150)  NOT NULL,
    `ic_number`   VARCHAR(20)   DEFAULT NULL,
    `phone`       VARCHAR(20)   NOT NULL,
    `email`       VARCHAR(100)  DEFAULT NULL,
    `company`     VARCHAR(150)  DEFAULT NULL,
    `created_at`  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- 3. bookings
-- ============================================================
CREATE TABLE IF NOT EXISTS `bookings` (
    `id`              INT(11)       NOT NULL AUTO_INCREMENT,
    `booking_code`    VARCHAR(20)   NOT NULL UNIQUE,
    `guest_id`        INT(11)       NOT NULL,
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
    KEY `fk_booking_guest` (`guest_id`),
    KEY `fk_booking_room`  (`room_id`),
    CONSTRAINT `fk_booking_guest` FOREIGN KEY (`guest_id`) REFERENCES `guests` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `fk_booking_room`  FOREIGN KEY (`room_id`)  REFERENCES `rooms`  (`id`) ON DELETE RESTRICT
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
