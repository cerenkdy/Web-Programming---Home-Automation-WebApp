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

-- inserting users data

INSERT INTO users (id, username, password, name, email, type) VALUES (1, 'admin', 'Admin Demo', 'admin@localhost.com', 'admin');
INSERT INTO users (id, username, password, name, email, type) VALUES (2, 'user', 'User Demo', 'user', 'User Demo', 'user@localhost', 'user');
