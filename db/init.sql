CREATE DATABASE IF NOT EXISTS appDB DEFAULT CHARACTER SET utf8;
CREATE USER IF NOT EXISTS 'user' @'%' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON appDB.* TO 'user' @'%';
FLUSH PRIVILEGES;
USE appDB;
-- Tables
CREATE TABLE IF NOT EXISTS users (
    ID INT(11) NOT NULL AUTO_INCREMENT,
    name CHAR(20) NOT NULL UNIQUE,
    password VARCHAR(40) NOT NULL,
    PRIMARY KEY (ID)
);
CREATE TABLE IF NOT EXISTS drinks (
    ID INT(10) NOT NULL AUTO_INCREMENT,
    title VARCHAR(32) NOT NULL UNIQUE,
    description VARCHAR(256) NOT NULL,
    cost INT(6) NOT NULL,
    PRIMARY KEY (ID)
);
-- Admin
-- https://www.web2generators.com/apache-tools/htpasswd-generator
INSERT INTO users (name, password)
VALUES (
           'iamadmin',
           '$apr1$6m1kplbd$vj70h1X3zQJy0tlp3JXKW0' -- mypass
       ),
       (
           'login',
           '$apr1$epobq07t$QlAR40n8JIhlPfgldEIXS.' -- password
       ),
       (
           'user',
           '$apr1$2c1lbz39$C0R15lfFdXaNm/LlR9iuI1' -- donotuse
       ),
       (
           'alex',
           '$apr1$tj5z9kdw$5ViDVDC6eU/5x6QhNvhEc/' -- securePWD
       );
-- Drinks
INSERT INTO drinks (title, description, cost)
VALUES ('Raf',
        'A coffee drink prepared by adding steam-heated cream with a small amount of foam to a single shot of espresso',
        420),
       ('Latte',
        'Coffee drink based on milk, which is a three-layer mixture of foam, milk and espresso coffee',
        370),
       ('Cappuccino',
        'Espresso-based coffee drink of Italian cuisine with the addition of heated frothed milk',
        410);