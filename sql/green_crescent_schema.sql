-- Create database
CREATE DATABASE IF NOT EXISTS GreenCrescent_Rentals;
USE GreenCrescent_Rentals;

-- Users table: admin hardcoded, customers register via UI
CREATE TABLE users (
    uid INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phoneno VARCHAR(15),
    role ENUM('admin','customer') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Hardcoded admin
INSERT INTO users (name, email, password, role) VALUES
('Waiz', 'admin@greencrescent.com', 'admin321', 'admin');

-- Cars table
CREATE TABLE cars (
    cid INT AUTO_INCREMENT PRIMARY KEY,
    carreg VARCHAR(7) NOT NULL,
    make VARCHAR(50) NOT NULL,
    model VARCHAR(50) NOT NULL,
    year YEAR NOT NULL,
    type ENUM('sedan','crossover','hatchback','suv','truck'),
    availability ENUM('available','booked') DEFAULT 'available',
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Rental fares table
CREATE TABLE rental_fares (
    rid INT AUTO_INCREMENT PRIMARY KEY,
    car_id INT NOT NULL,
    price_per_day INT NOT NULL,
    FOREIGN KEY (car_id) REFERENCES cars(cid) ON DELETE CASCADE
);

-- Bookings table
CREATE TABLE bookings (
    bid INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    car_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    total_fare DECIMAL(10,2) NOT NULL,
    status ENUM('pending','confirmed','cancelled','completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(uid) ON DELETE CASCADE,
    FOREIGN KEY (car_id) REFERENCES cars(cid) ON DELETE CASCADE
);

DELIMITER $$
CREATE TRIGGER after_booking_insert
AFTER INSERT ON bookings
FOR EACH ROW
BEGIN
    UPDATE cars SET availability='booked' WHERE cid=NEW.car_id;
END$$
DELIMITER ;

-- Trigger: Reset availability if booking cancelled/completed
DELIMITER $$
CREATE TRIGGER after_booking_update
AFTER UPDATE ON bookings
FOR EACH ROW
BEGIN
    IF NEW.status IN ('cancelled','completed') THEN
        UPDATE cars SET availability='available' WHERE cid=NEW.car_id;
    END IF;
END$$
DELIMITER ;

-- Procedure: Manual car availability update
DELIMITER $$
CREATE PROCEDURE update_car_availability(IN booked_car_id INT)
BEGIN
    UPDATE cars SET availability='booked' WHERE cid=booked_car_id;
END$$
DELIMITER ;

-- View: Available cars for booking
CREATE VIEW available_cars AS
SELECT c.cid, c.carreg, c.make, c.model, c.year, c.type, f.price_per_day
FROM cars c
JOIN rental_fares f ON c.cid=f.car_id
WHERE c.availability='available';

-- View: Booking summary for admin
CREATE VIEW booking_summary AS
SELECT b.bid AS booking_id, u.name AS customer_name, u.email, c.carreg, c.make, c.model,
       b.start_date, b.end_date, b.total_fare, b.status
FROM bookings b
JOIN users u ON b.user_id=u.uid
JOIN cars c ON b.car_id=c.cid;

-- Reset AUTO_INCREMENTs for portability
ALTER TABLE users AUTO_INCREMENT = 1;
ALTER TABLE cars AUTO_INCREMENT = 1;
ALTER TABLE rental_fares AUTO_INCREMENT = 1;
ALTER TABLE bookings AUTO_INCREMENT = 1;