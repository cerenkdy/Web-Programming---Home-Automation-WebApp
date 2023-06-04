-- table for users informations

CREATE TABLE users (
    id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    type enum('admin', 'user') NOT NULL DEFAULT 'user',
    PRIMARY KEY (id)
);

-- inserting mock data for demo users

INSERT INTO users (id, username, password, name, email, type) VALUES (1, 'admin', 'Admin Demo', 'admin@localhost.com', 'admin');
INSERT INTO users (id, username, password, name, email, type) VALUES (2, 'user', 'User Demo', 'user', 'User Demo', 'user@localhost', 'user');

-- table for rooms information

CREATE TABLE rooms (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    user_id INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- inserting rooms information

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