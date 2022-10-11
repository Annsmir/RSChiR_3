CREATE DATABASE IF NOT EXISTS appDB DEFAULT CHARACTER SET utf8;
CREATE USER IF NOT EXISTS 'user' @'%' IDENTIFIED BY 'password';
GRANT SELECT,
    UPDATE,
    INSERT ON appDB.* TO 'user' @'%';
FLUSH PRIVILEGES;
USE appDB;
-- Tables
CREATE TABLE IF NOT EXISTS users (
    ID INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(20) NOT NULL,
    password VARCHAR(40) NOT NULL,
    PRIMARY KEY (ID)
);
CREATE TABLE IF NOT EXISTS drinks (
    ID INT(10) NOT NULL AUTO_INCREMENT,
    title VARCHAR(32) NOT NULL,
    description VARCHAR(256) NOT NULL,
    cost INT(6) NOT NULL,
    PRIMARY KEY (ID)
);
-- Admin
INSERT INTO users (name, password)
SELECT *
FROM (
        SELECT 'iamadmin',
            'mypass'
    ) AS temp
WHERE NOT EXISTS (
        SELECT name
        FROM users
        WHERE name = 'iamadmin'
            AND password = 'mypass'
    )
LIMIT 1;
-- Drinks
INSERT INTO drinks (title, description, cost)
SELECT *
FROM (
        SELECT 'Raf',
            'A coffee drink prepared by adding steam-heated cream with a small amount of foam to a single shot of espresso',
            420
    ) AS temp
WHERE NOT EXISTS (
        SELECT title
        FROM drinks
        WHERE title = 'Raf'
            AND description = 'A coffee drink prepared by adding steam-heated cream with a small amount of foam to a single shot of espresso'
            AND cost = 420
    )
LIMIT 1;
INSERT INTO drinks (title, description, cost)
SELECT *
FROM (
        SELECT 'Latte',
            'Coffee drink based on milk, which is a three-layer mixture of foam, milk and espresso coffee',
            370
    ) AS temp
WHERE NOT EXISTS (
        SELECT title
        FROM drinks
        WHERE title = 'Latte'
            AND description = 'Coffee drink based on milk, which is a three-layer mixture of foam, milk and espresso coffee'
            AND cost = 370
    )
LIMIT 1;
INSERT INTO drinks (title, description, cost)
SELECT *
FROM (
         SELECT 'Cappuccino',
                'Espresso-based coffee drink of Italian cuisine with the addition of heated frothed milk',
                410
     ) AS temp
WHERE NOT EXISTS (
        SELECT title
        FROM drinks
        WHERE title = 'Cappuccino'
          AND description = 'Espresso-based coffee drink of Italian cuisine with the addition of heated frothed milk'
          AND cost = 410
    )
    LIMIT 1;