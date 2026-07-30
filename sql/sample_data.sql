-- ============================================================================
-- GreenCrescent Rentals
-- Sample Data
-- ============================================================================

USE GreenCrescent_Rentals;

-- ============================================================================
-- Clear Existing Demo Data
-- ============================================================================

SET FOREIGN_KEY_CHECKS = 0;

DELETE FROM bookings;
DELETE FROM rental_fares;
DELETE FROM cars;
DELETE FROM users WHERE role='customer';

ALTER TABLE bookings AUTO_INCREMENT = 1;
ALTER TABLE rental_fares AUTO_INCREMENT = 1;
ALTER TABLE cars AUTO_INCREMENT = 1;
DELETE FROM users WHERE role='customer';

ALTER TABLE users AUTO_INCREMENT = 2;
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================================
-- Sample Customers
-- ============================================================================

INSERT INTO users (name,email,password,phoneno,role)
VALUES
('Muhammad Ali','ali.customer@gmail.com','pass1234','03001234567','customer'),
('Ayesha Khan','ayesha.k@yahoo.com','securepass','03219876543','customer'),
('Bilal Ahmed','bilal.ahmed@outlook.com','bilal2026','03335557788','customer');

-- ============================================================================
-- Sample Cars
-- ============================================================================

INSERT INTO cars
(carreg,make,model,year,type,availability,image)
VALUES

('LEA-789','Toyota','Corolla',2020,'sedan','available','corolla2020.png'),

('KHI-456','Toyota','Corolla',2012,'sedan','available','corolla2012.png'),

('ISB-112','Honda','Civic',2019,'sedan','available','civic.png'),

('LHR-990','Suzuki','Swift',2021,'hatchback','available','swift.png'),

('KHI-303','Toyota','Fortuner',2022,'suv','available','fortuner.png'),

('LEA-555','Toyota','Land Cruiser LC200',2021,'suv','available','lc200.png'),

('ISB-888','Lexus','LX570',2022,'suv','available','lx570.png'),

('KHI-777','Toyota','Hilux Revo',2020,'truck','available','revo2020.png'),

('LHR-202','Toyota','Hilux Revo',2018,'truck','available','revo.png'),

('ISB-404','Kia','Sportage',2023,'crossover','available','sportage.png'),

('KHI-101','Suzuki','Vitara',2020,'crossover','available','vitara.png'),

('LHR-505','Suzuki','APV',2019,'hatchback','available','apv.png'),

('KHI-999','Toyota','Hiace',2021,'crossover','available','hiace.png');

-- ============================================================================
-- Rental Fares
-- ============================================================================

INSERT INTO rental_fares
(car_id,price_per_day)
VALUES

(1,3500),
(2,2000),
(3,6000),
(4,2800),
(5,14000),
(6,25000),
(7,30000),
(8,9500),
(9,8000),
(10,7000),
(11,5000),
(12,4500),
(13,11000);

-- ============================================================================
-- Sample Bookings
-- ============================================================================

INSERT INTO bookings
(user_id,car_id,start_date,end_date,total_fare,status)
VALUES

-- Muhammad Ali
(2,3,'2026-08-01','2026-08-05',24000,'confirmed'),

-- Ayesha Khan
(3,6,'2026-08-10','2026-08-15',125000,'pending'),

-- Bilal Ahmed
(4,1,'2026-06-01','2026-06-04',10500,'completed'),

-- Muhammad Ali
(2,10,'2026-07-10','2026-07-12',14000,'cancelled');

-- ============================================================================
-- Ensure Correct Availability
-- ============================================================================

UPDATE cars
SET availability='available';

UPDATE cars
SET availability='booked'
WHERE cid IN (
    SELECT DISTINCT car_id
    FROM bookings
    WHERE status IN ('pending','confirmed')
);

-- ============================================================================
-- End
-- ============================================================================
