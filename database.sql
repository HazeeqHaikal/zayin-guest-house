-- ============================================================
-- ZAYIN GUEST HOUSE — Database Schema
-- ============================================================
-- File      : database.sql
-- Updated   : 2026-06-14
-- Purpose   : Table definitions only (no data).
--             Import once via phpMyAdmin or MySQL CLI:
--               mysql -u USER -p DATABASE < database.sql
--             Then load sample data:
--               mysql -u USER -p DATABASE < seeds.sql
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
--   bookings.user_id    → users.id
--   bookings.room_id    → rooms.id
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
    `id`                    INT(11)       NOT NULL AUTO_INCREMENT,
    `booking_code`          VARCHAR(20)   NOT NULL UNIQUE,
    `user_id`               INT(11)       NOT NULL,
    `room_id`               INT(11)       NOT NULL,
    `check_in`              DATE          NOT NULL,
    `check_out`             DATE          NOT NULL,
    `total_nights`          INT(11)       NOT NULL,
    `total_amount`          DECIMAL(10,2) NOT NULL,
    `deposit_amount`        DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `status`                ENUM('pending','confirmed','cancelled','completed') NOT NULL DEFAULT 'pending',
    `is_fullhouse`          TINYINT(1)    NOT NULL DEFAULT 0,
    `notes`                 TEXT          DEFAULT NULL,
    `receipt_path`          VARCHAR(255)  DEFAULT NULL,
    `receipt_uploaded_at`   TIMESTAMP     NULL DEFAULT NULL,
    `created_at`            TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
