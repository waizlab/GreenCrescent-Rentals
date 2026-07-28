USE GreenCrescent_Rentals;

-- Sample customers
INSERT INTO users (name, email, password, role, phoneno) VALUES
('Ali', 'ali@gmail.com', 'ali123', 'customer', '03001234567'),
('Huzaifa', 'huz@yahoo.com', 'huz123', 'customer', '03111234567');

INSERT INTO users (name, email, password, role, phoneno) VALUES
('admin', 'admin@gmail.com', 'admin321', 'admin', '03008561725');

-- Sample cars (additional)
INSERT INTO cars (carreg, make, model, year, type, availability, image) VALUES
('BAD-234','Kia','Sportage',2023,'suv','available','images/sportage.jpg'),
('AZ-3345','Toyota','Revo',2020,'sedan','available','images/revo.jpg');

-- Rental fares for new cars
INSERT INTO rental_fares (car_id, price_per_day) VALUES
(6, 10000);

-- Sample bookings
INSERT INTO bookings (user_id, car_id, start_date, end_date, status) VALUES
(2, 6, '2025-12-28', '2025-12-30', 'confirmed');
select * from booking_summary;