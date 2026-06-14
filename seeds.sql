-- ============================================================
-- ZAYIN GUEST HOUSE — Seed Data
-- ============================================================
-- File      : seeds.sql
-- Updated   : 2026-06-14
-- Purpose   : Sample data for development / testing.
--             Run AFTER database.sql:
--               mysql -u USER -p DATABASE < seeds.sql
--
-- Seeded tables (insertion order respects FK dependencies):
--   1. rooms
--   2. admin_users
--   3. users
--   4. bookings
--   5. blocked_dates
--   6. invoices
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+08:00";

-- ============================================================
-- 1. Rooms — 8 rooms (4 standard, 2 deluxe, 1 family, 1 suite)
-- ============================================================
INSERT INTO `rooms` (`name`, `description`, `capacity`, `price_per_night`, `image`, `room_type`) VALUES
('Standard Room A', 'A cozy queen-size room with air conditioning, ideal for couples or solo travelers.', 2, 80.00,  'assets/rooms/room-1.jpg', 'standard'),
('Standard Room B', 'Comfortable queen-size room with natural lighting and modern furnishings.',          2, 80.00,  'assets/rooms/room-2.jpg', 'standard'),
('Standard Room C', 'Clean and well-ventilated queen-size room with private bathroom.',                  2, 80.00,  'assets/rooms/room-3.jpg', 'standard'),
('Standard Room D', 'Bright standard room with a queen-size bed and all essential amenities.',           2, 80.00,  'assets/rooms/room-4.jpg', 'standard'),
('Deluxe Room A',   'Spacious deluxe room with a king-size bed and premium furnishings.',                3, 110.00, 'assets/rooms/room-5.jpg', 'deluxe'),
('Deluxe Room B',   'Upgraded deluxe room with extra space, king-size bed, and modern decor.',           3, 110.00, 'assets/rooms/room-6.jpg', 'deluxe'),
('Family Room',     'Large family room with two double beds — perfect for families with children.',      5, 140.00, 'assets/rooms/room-7.jpg', 'standard'),
('Master Suite',    'Premium master suite with king-size bed, lounge area, and en-suite bathroom.',      4, 160.00, 'assets/rooms/room-8.jpg', 'deluxe');

-- ============================================================
-- 2. Admin User
-- ============================================================
-- IMPORTANT: Replace the password_hash below BEFORE going live.
-- Generate a new hash:
--   php -r "echo password_hash('YOUR_PASSWORD', PASSWORD_BCRYPT);"
-- Then run:
--   UPDATE admin_users SET password_hash = 'NEW_HASH' WHERE username = 'admin';
-- ============================================================
INSERT INTO `admin_users` (`username`, `password_hash`, `full_name`, `email`) VALUES
('admin', 'REPLACE_WITH_BCRYPT_HASH', 'Guest House Admin', 'admin@zayinguesthouse.com');

-- ============================================================
-- 3. Users (walk-in guests, no password — cannot log in)
-- ============================================================
INSERT IGNORE INTO `users` (`id`, `full_name`, `ic_number`, `phone`, `email`) VALUES
(1, 'Ahmad Firdaus bin Ramli',      '890514-03-5231', '0123456789', 'ahmad.firdaus@gmail.com'),
(2, 'Siti Nabilah binti Hassan',    '920301-07-6140', '0167891234', 'nabilah92@gmail.com'),
(3, 'Lim Wei Ming',                 '880720-10-4567', '0111234567', 'lim.weiming@outlook.com'),
(4, 'Rajesh a/l Subramaniam',       '950612-05-7890', '0197654321', NULL),
(5, 'Nur Izzati binti Mohd Zaidi',  '991105-14-3210', '0134567890', 'izzati99@gmail.com');

-- ============================================================
-- 4. Bookings
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
-- 5. Blocked Dates
-- ============================================================
INSERT IGNORE INTO `blocked_dates` (`block_date`, `block_type`, `reason`) VALUES
('2026-07-01', 'maintenance', 'Annual deep cleaning & maintenance'),
('2026-07-02', 'maintenance', 'Annual deep cleaning & maintenance');

-- ============================================================
-- 6. Invoices (for completed + confirmed bookings)
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
