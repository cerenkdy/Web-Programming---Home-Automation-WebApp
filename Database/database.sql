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

INSERT INTO consumers (id, username, password, name, email, settings) VALUES (1, 'demouser', 'Who0808', 'Consumer Demo', 'user@localhost', '{"theme": "light", "language": "en", "notifications": true}');

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

INSERT INTO producers (id, username, password, name, email, settings) VALUES (1, 'demoadmin', 'Who0808', 'Producer Demo', 'admin@localhost', '{"theme": "light", "language": "en", "notifications": true}');

-- table for rooms information

CREATE TABLE rooms (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    user_id INT NOT NULL,
    data text NOT NULL DEFAULT '{}',
    PRIMARY KEY (id)
);

-- inserting rooms information
INSERT INTO rooms (id, name, user_id, data) VALUES (1, 'Living room', 1, '{"temperature": 24, "humidity": 51, "temperature_status":"1","humidity_status":"1"}');
INSERT INTO rooms (id, name, user_id, data) VALUES (2, 'Bedroom', 1, '{"temperature": 23, "humidity": 45, "temperature_status":"1","humidity_status":"1"}');
INSERT INTO rooms (id, name, user_id, data) VALUES (3, 'Kitchen', 1, '{"temperature": 25, "humidity": 55, "temperature_status":"1","humidity_status":"1"}');
INSERT INTO rooms (id, name, user_id, data) VALUES (4, 'Bathroom', 1, '{"temperature": 23, "humidity": 65, "temperature_status":"1","humidity_status":"1"}');


-- table for devices
CREATE TABLE devices (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(255) NOT NULL DEFAULT 'sensor',
    status INT NOT NULL DEFAULT 0,
    electricity INT NOT NULL DEFAULT 0,
    water INT NOT NULL DEFAULT 0,
    gas INT NOT NULL DEFAULT 0,
    data text NOT NULL DEFAULT '{}',
    PRIMARY KEY (id)
);

-- devices data
INSERT INTO devices (id, user_id, name, room_id, type, status, data, electricity) VALUES (2, 1, 'Main Lamp', 1, 'light', 0, '{"color": "#ffffff"}', 1);
INSERT INTO devices (id, user_id, name, room_id, type, status, data, electricity) VALUES (3, 1, 'Bedroom Lamp', 2, 'light', 0, '{"color": "#ffffff"}', 1);
INSERT INTO devices (id, user_id, name, room_id, type, status, data, electricity) VALUES (4, 1, 'Kitchen Lamp', 3, 'light', 0, '{"color": "#ffffff"}', 1);
INSERT INTO devices (id, user_id, name, room_id, type, status, data, electricity) VALUES (5, 1, 'Bathroom Lamp', 4, 'light', 0, '{"color": "#ffffff"}', 1);
INSERT INTO devices (id, user_id, name, room_id, type, status, data, electricity) VALUES (6, 1, 'Air Conditioner', 1, 'ac', 0, '{"auto_start": 25}', 1);
INSERT INTO devices (id, user_id, name, room_id, type, status, data, electricity) VALUES (7, 1, 'TV', 1, 'tv', 1, '{"channel": 1, "volume": 50}', 1);

-- home configs table
CREATE TABLE home_configs (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    type VARCHAR(255) NOT NULL,
    data text NOT NULL DEFAULT '',
    PRIMARY KEY (id)
);

-- home configs data
INSERT INTO home_configs (id, user_id, type, data) VALUES (1, 1, 'outdoor_lock', '{"status": 0}');
INSERT INTO home_configs (id, user_id, type, data) VALUES (2, 1, 'door', '{"name": "Main Door", "status": 1}');
INSERT INTO home_configs (id, user_id, type, data) VALUES (3, 1, 'door', '{"name": "Exit Door", "status": 1}');
INSERT INTO home_configs (id, user_id, type, data) VALUES (4, 1, 'door', '{"name": "Balcony Door", "status": 1}');
INSERT INTO home_configs (id, user_id, type, data) VALUES (5, 1, 'door', '{"name": "Garage Door", "status": 1}');


-- table for sensor data 
CREATE TABLE sensor_data (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    temperature INT NOT NULL DEFAULT 0,
    humidity INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);

-- mock sensor data for demo
INSERT INTO sensor_data (user_id, room_id, temperature, humidity) VALUES (1, 1, 24, 50);
INSERT INTO sensor_data (user_id, room_id, temperature, humidity) VALUES (1, 1, 25, 51);
INSERT INTO sensor_data (user_id, room_id, temperature, humidity) VALUES (1, 1, 22, 50);
INSERT INTO sensor_data (user_id, room_id, temperature, humidity) VALUES (1, 1, 24, 49);
INSERT INTO sensor_data (user_id, room_id, temperature, humidity) VALUES (1, 1, 25, 50);
INSERT INTO sensor_data (user_id, room_id, temperature, humidity) VALUES (1, 1, 24, 50);
INSERT INTO sensor_data (user_id, room_id, temperature, humidity) VALUES (1, 1, 24, 51);

-- table for logs 
CREATE TABLE logs (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    user_type VARCHAR(255) NOT NULL DEFAULT 'consumers',
    device_id INT DEFAULT NULL,
    config_id INT DEFAULT NULL,
    action INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);

-- mock log data for demo
INSERT INTO logs (user_id, user_type, device_id, action) VALUES (1, 'consumers', 2, 0);
INSERT INTO logs (user_id, user_type, device_id, action) VALUES (1, 'consumers', 3, 0);

ALTER TABLE `rooms` ADD FOREIGN KEY (`user_id`) REFERENCES `consumers` (`id`);

ALTER TABLE `devices` ADD FOREIGN KEY (`user_id`) REFERENCES `consumers` (`id`);

ALTER TABLE `sensor_data` ADD FOREIGN KEY (`user_id`) REFERENCES `consumers` (`id`);

ALTER TABLE `home_configs` ADD FOREIGN KEY (`user_id`) REFERENCES `consumers` (`id`);

ALTER TABLE `devices` ADD FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

ALTER TABLE `sensor_data` ADD FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

ALTER TABLE `logs` ADD FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`);

ALTER TABLE `logs` ADD FOREIGN KEY (`config_id`) REFERENCES `home_configs` (`id`);