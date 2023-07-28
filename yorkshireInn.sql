CREATE DATABASE yorkshireInn;
use yorkshireInn;
      
Create table room (
	RoomID int auto_increment,
    Occupancy int not null, 
	Amenities varchar(200) not null,
    constraint RoomID_pk PRIMARY KEY (RoomID)
);  
    
CREATE TABLE isAvailable (
	isAvailableDate DATE not null,
	isAvailableBool1 TINYINT(1) not null,
    isAvailableBool2 TINYINT(1) not null,
    isAvailableBool3 TINYINT(1) not null,
    isAvailableBool4 TINYINT(1) not null,
    constraint isAvailableDate_pk PRIMARY KEY (isAvailableDate)
);  

CREATE TABLE isBlocked (
	isBlockedDate DATE not null,
	isBlockedBool1 TINYINT(1) not null,
    isBlockedBool2 TINYINT(1) not null,
    isBlockedBool3 TINYINT(1) not null,
    isBlockedBool4 TINYINT(1) not null,
	constraint isBlockedDate_pk PRIMARY KEY (isBlockedDate)
); 

CREATE TABLE price (
	priceDate DATE not null,
	room1Price FLOAT,
   room2Price FLOAT,
   room3Price FLOAT,
   room4Price FLOAT,
	constraint priceDate_pk PRIMARY KEY (priceDate)
); 


Create table payment (
	  PaymentID int not null auto_increment,
      PaymentDate DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      CreditCardNo VARCHAR(20) NOT NULL,
	  CCV varchar(4) not null, 
      ZIPCode CHAR(5) not null,
      ExpireDate date not null,
      constraint PaymentID_pk PRIMARY KEY (PaymentID)
);    


CREATE TABLE employee(
   employeeID INT not null auto_increment,
   FirstName VARCHAR(50) NOT NULL,
   LastName VARCHAR(50) NOT NULL,
   Phone VARCHAR(20),
   Email VARCHAR(40) NOT NULL,
   constraint employee_pk PRIMARY KEY (employeeID)
);

CREATE TABLE traveler(
   TravelerID INT not null auto_increment,
   FirstName VARCHAR(50) NOT NULL,
   LastName VARCHAR(50) NOT NULL,
   Phone VARCHAR(20) NOT NULL,
   Email VARCHAR(40) NOT NULL,
   constraint traveler_pk PRIMARY KEY (TravelerID)
);


CREATE TABLE login(
   LoginID INT not null auto_increment,
   UserName VARCHAR(50) NOT NULL,
   Passwords BINARY(64) NOT NULL,
   employeeID INT, 
   PRIMARY KEY (LoginID),
   FOREIGN KEY (employeeID) REFERENCES employee(employeeID)
   ON UPDATE CASCADE
   ON DELETE CASCADE
);


CREATE TABLE reservation(
   ReservationsID INT not null auto_increment,
   BookingDate DATE,
   CheckIn DATE,
   CheckOut DATE,
   NumGuests INT,
   SpecialReq VARCHAR(200),
   TotalPayment FLOAT(9,2),
   TotalDeposit FLOAT (9,2),
   RoomID Int,		
   TravelerID INT,	
   PaymentID INT,
   ConfirmationNum varchar(10) not null,
   PRIMARY KEY (ReservationsID),
   FOREIGN KEY (RoomID) REFERENCES room(RoomID),
   FOREIGN KEY (TravelerID) REFERENCES traveler(TravelerID),
   FOREIGN KEY (PaymentID) REFERENCES payment(PaymentID)
);


CREATE TABLE review(
   ReviewID INT not null auto_increment,
   FirstName VARCHAR(50) NOT NULL,
   LastName VARCHAR(50) NOT NULL,
   Comments VARCHAR(500),
   Rating INT,
   constraint review_pk PRIMARY KEY (ReviewID)
);




-- -- Fake data
-- alter table payment modify column ZIPCode CHAR(5) not null;
-- alter table reservation add column ConfirmationNum varchar(10) not null;

-- Insert into employee (FirstName, LastName, Phone, Email) VALUES ("Jiajia", "Chen", "123-456-7890", "example@gmail.com"),("Alice", "Smith", "917-456-1234", "example@gmail.com");

-- Insert into login (UserName, Passwords, employeeID) VALUES ("admin", "admin", 1);

-- Insert into room (Occupancy, Amenities) VALUES (2, "room1: En-suite Bathroom, King Size Bed, Air-conditioning");
-- Insert into room ( Occupancy, Amenities) VALUES ( 2, "room2: En-suite Bathroom, King Size Bed, Air-conditioning");
-- Insert into room ( Occupancy, Amenities) VALUES ( 4, "room3: En-suite Bathroom, King Size Bed, Air-conditioning");
-- Insert into room ( Occupancy, Amenities) VALUES ( 4, "room4: En-suite Bathroom, King Size Bed, Air-conditioning");


-- Insert into traveler (FirstName, LastName, Phone, Email) VALUES ("travelerOne", "1", "123-456-7890", "example@gmail.com"),("travelerTwo", "2", "123-456-7890", "example@gmail.com");

-- Insert into payment (CreditCardNo, CCV, ZIPCode, ExpireDate) VALUES ("111122233334444", 111, "04586", "2026-06-08");

-- Insert into review (FirstName, LastName, Comments, Rating) VALUES ("reviewOne", "1", "Comment: testing", 5);

-- Insert into reservation (BookingDate, CheckIn, CheckOut, NumGuests, SpecialReq, TotalPayment, TotalDeposit,ConfirmationNum, RoomID, TravelerID, PaymentID) VALUES ("2023-03-08", "2023-03-09", "2023-03-11", 4, "", 465.92, 200.00, "ConfirmNum" ,1, 1, 1);
-- Insert into reservation (BookingDate, CheckIn, CheckOut, NumGuests, SpecialReq, TotalPayment, TotalDeposit,ConfirmationNum, RoomID, TravelerID, PaymentID) VALUES ("2023-03-17", "2023-03-19", "2023-03-22", 2, "", 400.00, 200.00, "sdsbcj" ,2, 2, 2);

-- Insert into pricing (RoomID, daytime, price) values (1, "2021-03-13", 200.00);

-- Insert into isAvailable (dayTime, isAvailableBool1, isAvailableBool2, isAvailableBool3, isAvailableBool4) VALUES ("2023-03-09 03:14:07", 3, 5, 0,0);
-- Insert into isAvailable (dayTime, isAvailableBool1, isAvailableBool2, isAvailableBool3, isAvailableBool4) VALUES ("2023-03-10 03:14:07", 1, 1, 0,0);
-- Insert into isAvailable (dayTime, isAvailableBool1, isAvailableBool2, isAvailableBool3, isAvailableBool4) VALUES ("2023-03-17", 0, 0, 0, 0);



-- -- update
-- UPDATE pricing SET price = 253.00 WHERE RoomID = 1;
-- UPDATE room SET Occupancy = 2 WHERE RoomID = 1;
-- UPDATE isAvailable SET isAvailableBool1 = 0 WHERE dayTime = "2023-03-09 03:14:07";


-- -- can select fields from traveler + reservation tables by travelerID = 1
-- select FirstName, Email, CheckIn, CheckOut, TotalPayment from reservation 
-- inner join traveler on reservation.TravelerID = traveler.TravelerID 
-- where reservation.TravelerID = 1;

-- -- select all from reservation by confirmation number
-- select * from reservation where ConfirmationNum = "ConfirmNum";



-- -- SELECT isAvailableBool
-- -- FROM isAvailable
-- -- WHERE isAvailable.roomID NOT IN (SELECT re.RoomID from reservation re
-- --                        WHERE NOT (re.CheckIn < '' OR re.CheckOut > '')
-- --                       )
-- -- ORDER BY r.RoomID;

