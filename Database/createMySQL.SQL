/*
    Due to requirements, we have to have a MySQL database running. So we will be using it to
    store and maintain login information.

    USER: Main table for the users on the service
        username (PK)
        email
        firstName
        lastName
        image (this is the file location of the image)
        password (hashed and salted)

    TOKENS: Table for session tokens for each user
        tokenId (PK)
        username
        token
*/

CREATE DATABASE fallr;

CREATE USER 'fallr'@'localhost' IDENTIFIED BY '';

GRANT ALL ON fallr.* to 'fallr'@'localhost';

FLUSH PRIVILEGES;

CREATE TABLE users 
    (
        userID int NOT NULL AUTO_INCREMENT,
        username VARCHAR(30) NOT NULL,
        email VARCHAR(40),
        firstName VARCHAR(255),
        lastName VARCHAR(255),
        image TEXT,
        password CHAR(60) NOT NULL,
        PRIMARY KEY(userID)
    );

CREATE TABLE tokens 
    (
        tokenID INT NOT NULL AUTO_INCREMENT,
        userID int NOT NULL,
        token VARCHAR(512),
        PRIMARY KEY (tokenID)
    );