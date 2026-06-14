-- ============================================================
-- ZAYIN GUEST HOUSE — Migration: guests → users
-- ============================================================
-- Run this ONCE on a database that still has the old `guests`
-- table (i.e. imported from the original database.sql).
-- It renames the table, adds auth columns, and fixes the FK.
--
-- Run via phpMyAdmin SQL tab, or:
--   mysql -u YOUR_USER -p zayin_guest_house < migrate_guests_to_users.sql
--
-- NOTE: For fresh installs just import database.sql directly —
--       this file is only needed to upgrade an existing database.
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Rename the table
RENAME TABLE `guests` TO `users`;

-- 2. Add auth columns
ALTER TABLE `users`
    ADD COLUMN `password_hash` VARCHAR(255) NULL DEFAULT NULL,
    ADD COLUMN `is_active`     TINYINT(1)  NOT NULL DEFAULT 1;

-- 3. Add unique constraints
ALTER TABLE `users`
    ADD UNIQUE KEY `unique_phone` (`phone`),
    ADD UNIQUE KEY `unique_email` (`email`);

-- 4. Drop the old FK on bookings that references guests
ALTER TABLE `bookings`
    DROP FOREIGN KEY `fk_booking_guest`;

-- 5. Rename the column guest_id → user_id
ALTER TABLE `bookings`
    CHANGE COLUMN `guest_id` `user_id` INT(11) NOT NULL;

-- 6. Re-add FK pointing to the renamed table
ALTER TABLE `bookings`
    ADD CONSTRAINT `fk_booking_user`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT;

SET FOREIGN_KEY_CHECKS = 1;

-- Verify with:
--   SHOW TABLES;
--   DESCRIBE users;
--   DESCRIBE bookings;
