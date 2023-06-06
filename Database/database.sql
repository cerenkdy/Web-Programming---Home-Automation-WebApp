-- table for consumers
CREATE TABLE consumers (
    id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    settings text NOT NULL DEFAULT '{}',
    PRIMARY KEY (id)
);

-- inserting mock data for consumers

INSERT INTO consumers (id, username, password, name, email, settings) VALUES (1, 'demouser', 'Who0808', 'User Demo', 'user@localhost', '{"theme": "light", "language": "en", "notifications": true}');

-- table for producers
CREATE TABLE producers (
    id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    settings text NOT NULL DEFAULT '{}',
    PRIMARY KEY (id)
);

-- inserting mock data for producers

INSERT INTO producers (id, username, password, name, email, settings) VALUES (1, 'demoadmin', 'Who0808', 'Admin Demo', 'admin@localhost', '{"theme": "light", "language": "en", "notifications": true}');


-- inserting mock data for demo users

INSERT INTO users (id, username, password, name, email, type) VALUES (1, 'admin', 'Admin Demo', 'admin@localhost.com', 'admin');
INSERT INTO users (id, username, password, name, email, type) VALUES (2, 'user', 'User Demo', 'user', 'User Demo', 'user@localhost', 'user');

-- table for rooms information

CREATE TABLE rooms (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    user_id INT NOT NULL,
    PRIMARY KEY (id),
);

-- inserting rooms information
INSERT INTO rooms (id, name, user_id, data) VALUES (1, 'Living room', 1, '{"temperature": 24, "humidity": 51}');
INSERT INTO rooms (id, name, user_id, data) VALUES (2, 'Bedroom', 1, '{"temperature": 23, "humidity": 45}');
INSERT INTO rooms (id, name, user_id, data) VALUES (3, 'Kitchen', 1, '{"temperature": 25, "humidity": 55}');
INSERT INTO rooms (id, name, user_id, data) VALUES (4, 'Bathroom', 1, '{"temperature": 23, "humidity": 65}');

-- table for devices
CREATE TABLE devices (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(255) NOT NULL DEFAULT 'sensor',
    status INT NOT NULL DEFAULT 0,
    data text NOT NULL DEFAULT '{}',
    PRIMARY KEY (id),
    FOREIGN KEY (room_id) REFERENCES rooms(id)
);

-- inserting the data of living room devices 
INSERT INTO devices (id, name, room_id, type, status, data) VALUES (1, 'Sensor', '1', 0, '{temperature: 24, humidity: 50}');
INSERT INTO devices (id, name, room_id, type, status, data) VALUES (2, 'Light', 0, '{color: #ffffff}');
INSERT INTO devices (id, name, room_id, type, status, data) VALUES (3, 'Air Conditioner', 1, 'air_conditioner', 0, '{auto_start: 25}');
INSERT INTO devices (id, name, room_id, type, status, data) VALUES (4, 'TV', 1, 'tv', 0, '{channel: 1, volume: 50}');

-- table for sensor data 
CREATE TABLE sensor_data (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL AUTO_INCREMENT,
    device_id INT NOT NULL,
    temperature decimal(2,1) NOT NULL,
    humidity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (device_id) REFERENCES devices(id)
);

-- mock sensor data for demo
INSERT INTO sensor_data (device_id, temperature,humidity) VALUES (1, 24, 50);
INSERT INTO sensor_data (device_id, temperature,humidity) VALUES (1, 25, 51);
INSERT INTO sensor_data (device_id, temperature,humidity) VALUES (1, 22, 50);
INSERT INTO sensor_data (device_id, temperature,humidity) VALUES (1, 24, 49);
INSERT INTO sensor_data (device_id, temperature,humidity) VALUES (1, 25, 50);
INSERT INTO sensor_data (device_id, temperature,humidity) VALUES (1, 24, 50);

-- table for logs   
CREATE TABLE logs (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    user_type VARCHAR(255) NOT NULL DEFAULT 'consumers',
    device_id INT NOT NULL,
    action VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (device_id) REFERENCES devices (id)
);

-- mock log data for demo
INSERT INTO logs (user_id, user_type, device_id, action) VALUES (1, 'consumers', 1, 'Smart TV turned on');
INSERT INTO logs (user_id, user_type, device_id, action) VALUES (1, 'consumers', 3, 'Air Conditioner turned off');
INSERT INTO logs (user_id, user_type, device_id, action) VALUES (1, 'consumers', 2, 'Lamps turned off');
