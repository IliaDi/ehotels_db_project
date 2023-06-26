CREATE TABLE `ehotels`.`hotel_group` ( 
`hotel_group_id` VARCHAR(100) NOT NULL , 
`number_of_hotels` INT NOT NULL,
`street`  VARCHAR(100) NOT NULL, 
`number` INT NOT NULL,
`postal_code` INT NOT NULL , 
`city` VARCHAR(100) NOT NULL , 
PRIMARY KEY (`hotel_group_id`)
) ENGINE = InnoDB;

CREATE TABLE `ehotels`.`group_email` (  
`hotel_group_id` VARCHAR(100) NOT NULL, 
`email` VARCHAR(320) NOT NULL,
PRIMARY KEY (email),
FOREIGN KEY (`hotel_group_id`) 
REFERENCES hotel_group(`hotel_group_id`)
ON DELETE CASCADE
ON UPDATE CASCADE
) ENGINE = InnoDB;

CREATE TABLE `ehotels`.`group_phone` (  
`hotel_group_id` VARCHAR(100) NOT NULL, 
`phone` VARCHAR(15) NOT NULL,
PRIMARY KEY (phone),
FOREIGN KEY (`hotel_group_id`) 
REFERENCES hotel_group(`hotel_group_id`)
ON DELETE CASCADE
ON UPDATE CASCADE
) ENGINE = InnoDB;

CREATE TABLE `ehotels`.`hotel` ( 
`hotel_id` VARCHAR(100) , 
`hotel_group_id` VARCHAR(100) NOT NULL ,
`stars` ENUM('1','2','3','4','5'),
`number_of_rooms` INT NOT NULL,
`street`  VARCHAR(100), 
`number` INT ,
`postal_code` INT NOT NULL , 
`city` VARCHAR(100) NOT NULL , 
PRIMARY KEY (`hotel_id`),
FOREIGN KEY (`hotel_group_id`) 
REFERENCES hotel_group(`hotel_group_id`)
ON DELETE CASCADE 
ON UPDATE CASCADE
) ENGINE = InnoDB;

CREATE TABLE `ehotels`.`hotel_email` (  
`hotel_id` VARCHAR(100) NOT NULL, 
`email` VARCHAR(320) NOT NULL,
PRIMARY KEY (email),
FOREIGN KEY (`hotel_id`) 
REFERENCES hotel(`hotel_id`)
ON DELETE CASCADE
ON UPDATE CASCADE
) ENGINE = InnoDB;

CREATE TABLE `ehotels`.`hotel_phone` (  
`hotel_id` VARCHAR(100) NOT NULL, 
`phone` VARCHAR(15) NOT NULL,
PRIMARY KEY (phone),
FOREIGN KEY (`hotel_id`) 
REFERENCES hotel(`hotel_id`)
ON DELETE CASCADE 
ON UPDATE CASCADE
) ENGINE = InnoDB;

CREATE TABLE `ehotels`.`employee` ( 
`employee_irs_number` VARCHAR(9) NOT NULL, 
`social_security_number` VARCHAR(11) NOT NULL,
`first_name` VARCHAR(100)  NOT NULL,
`last_name` VARCHAR(100) NOT NULL,
`street`  VARCHAR(100), 
`number` INT,
`postal_code` INT NOT NULL , 
`city` VARCHAR(100) NOT NULL , 
PRIMARY KEY (`employee_irs_number`)
) ENGINE = InnoDB;

CREATE TABLE `ehotels`.`works` ( 
`employee_irs_number` VARCHAR(9) NOT NULL UNIQUE , 
`hotel_id` VARCHAR(100) NOT NULL, 
`position` VARCHAR(100) NOT NULL, 
`start_date` DATE NOT NULL,
`finish_date` DATE,
PRIMARY KEY (`employee_irs_number`,`hotel_id`),
FOREIGN KEY (`employee_irs_number`) 
REFERENCES employee(`employee_irs_number`)
ON DELETE CASCADE
ON UPDATE CASCADE,
FOREIGN KEY (`hotel_id`) 
REFERENCES hotel(`hotel_id`)
ON DELETE CASCADE 
ON UPDATE CASCADE
) ENGINE = InnoDB;

CREATE TABLE `ehotels`.`customer` ( 
`customer_irs_number` VARCHAR(9) NOT NULL , 
`social_security_number` VARCHAR(11) NOT NULL, 
`first_name` VARCHAR(100) NOT NULL,
 `last_name` VARCHAR(100) NOT NULL,
`street` VARCHAR(100), 
`number` INT ,
`postal_code` INT , 
`city` VARCHAR(100),
`first_registration` DATE NOT NULL, 
PRIMARY KEY (`customer_irs_number`)
) ENGINE = InnoDB;


CREATE TABLE `ehotels`.`hotel_room` (
  `room_id` INT NOT NULL AUTO_INCREMENT,
  `hotel_id` VARCHAR(100) NOT NULL,
  `capacity`ENUM ('1','2','3','4'),
  `view` ENUM('sea view', 'street view', 'garden view'),
  `expandable` ENUM(
    'yes ,extra beds',
    'no',
    'yes, connects with next room'
  ),
  `repair_need` ENUM('yes', 'no'),
  `price` INT NOT NULL,
  PRIMARY KEY (room_id,hotel_id),
  FOREIGN KEY(`hotel_id`) REFERENCES hotel(`hotel_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB;


CREATE TABLE `ehotels`.`reserves` (
  `hotel_id` VARCHAR(100) ,
  `customer_irs_number` VARCHAR(9),
  `room_id` INT,
  `start_date` DATE NOT NULL,
  `finish_date` DATE NOT NULL,
  `paid` ENUM('yes', 'no'),
  UNIQUE(hotel_id,room_id,start_date),
  FOREIGN KEY (`customer_irs_number`) REFERENCES customer(`customer_irs_number`) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (`room_id`) REFERENCES hotel_room(`room_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (`hotel_id`) REFERENCES hotel(`hotel_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB;


CREATE TABLE `ehotels`.`amenities` ( 
`room_id` INT NOT NULL,
`amenity_names` VARCHAR(100), 
PRIMARY KEY (room_id,amenity_names),
FOREIGN KEY (`room_id`) 
REFERENCES hotel_room(`room_id`)
ON DELETE CASCADE
ON UPDATE CASCADE
) ENGINE = InnoDB;

CREATE TABLE `ehotels`.`rents` ( 
`employee_irs_number` VARCHAR(9),
`customer_irs_number` VARCHAR(9),
`room_id` INT,
`hotel_id` VARCHAR(100),
`start_date` DATE NOT NULL, 
`finish_date` DATE NOT NULL,
UNIQUE(room_id,hotel_id,start_date),
FOREIGN KEY (`employee_irs_number`) 
REFERENCES employee(`employee_irs_number`)
ON DELETE SET NULL
ON UPDATE CASCADE,
FOREIGN KEY (`customer_irs_number`) 
REFERENCES customer(`customer_irs_number`)
ON DELETE SET NULL
ON UPDATE CASCADE,
FOREIGN KEY (`room_id`) 
REFERENCES hotel_room(`room_id`)
ON DELETE SET NULL 
ON UPDATE CASCADE,
FOREIGN KEY (`hotel_id`) 
REFERENCES hotel(`hotel_id`)
ON DELETE SET NULL 
ON UPDATE CASCADE
) ENGINE = InnoDB;

CREATE TABLE `ehotels`.`payment_transaction` ( 
`employee_irs_number` VARCHAR(9) NOT NULL,
`customer_irs_number` VARCHAR(9) NOT NULL,
`room_id` INT NOT NULL,
`hotel_id` VARCHAR(100) NOT NULL,
`payment_amount` INT NOT NULL,
`payment_method` ENUM('card','cash on arrival'),
PRIMARY KEY(employee_irs_number,customer_irs_number, room_id,hotel_id),
FOREIGN KEY (`employee_irs_number`) 
REFERENCES employee(`employee_irs_number`)
ON DELETE CASCADE
ON UPDATE CASCADE,
FOREIGN KEY (`customer_irs_number`) 
REFERENCES customer(`customer_irs_number`)
ON DELETE CASCADE 
ON UPDATE CASCADE,
FOREIGN KEY (`room_id`) 
REFERENCES hotel_room(`room_id`)
ON DELETE CASCADE
ON UPDATE CASCADE,
FOREIGN KEY (`hotel_id`) 
REFERENCES hotel(`hotel_id`)
ON DELETE CASCADE 
ON UPDATE CASCADE
) ENGINE = InnoDB;

CREATE VIEW room_capacities AS
SELECT room_id,hotel_id,capacity
FROM hotel_room
ORDER BY capacity ;

CREATE VIEW available_rooms_per_area AS
SELECT city,SUM(number_of_rooms)
FROM
hotel
GROUP BY city;

delimiter //
CREATE TRIGGER add_hotel_room 
BEFORE INSERT ON hotel_room
    FOR EACH ROW BEGIN
        UPDATE hotel
		SET hotel.number_of_rooms=hotel.number_of_rooms+1
		WHERE (hotel.hotel_id=new.hotel_id );
    END //

delimiter //
CREATE TRIGGER del_hotel_room BEFORE DELETE ON hotel_room
    FOR EACH ROW
    BEGIN
        UPDATE hotel
		SET hotel.number_of_rooms=hotel.number_of_rooms-1
		WHERE (hotel.hotel_id=hotel_room.hotel_id) ;
    END//

delimiter //
CREATE TRIGGER add_hotel BEFORE INSERT ON hotel
    FOR EACH ROW
    BEGIN
        UPDATE hotel_group
		SET hotel_group.number_of_hotels=hotel_group.number_of_hotels+1
		WHERE (new.hotel_group_id=hotel_group.hotel_group_id);
    END//

delimiter //
CREATE TRIGGER del_hotel BEFORE DELETE ON hotel
    FOR EACH ROW
    BEGIN
        UPDATE hotel_group
		SET hotel_group.number_of_hotels=hotel_group.number_of_hotels-1
		WHERE (hotel_group.hotel_group_id=hotel.hotel_group_id);
    END//
	
delimiter //
CREATE TRIGGER del_employee BEFORE DELETE ON works
    FOR EACH ROW
    BEGIN
	DELETE FROM employee 
	WHERE(employee.employee_irs_number=works.employee_irs_number);
    END//
delimiter ;



