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

CREATE TABLE Users 
    (
        username VARCHAR(30) NOT NULL UNIQUE, /* As we want each user to have a unique username */
        email VARCHAR(40) NOT NULL UNIQUE, /* As we want each user to have a unique email (avoid duplicates) */
        firstName VARCHAR(255),
        lastName VARCHAR(255),
        image TEXT,
        password CHAR(60) NOT NULL,
        PRIMARY KEY(username)
    );

CREATE TABLE Tokens 
    (
        tokenID INT NOT NULL AUTO_INCREMENT,
        username VARCHAR(30) NOT NULL,
        token VARCHAR(512),
        PRIMARY KEY (tokenID)
    );


CREATE TABLE Posts 
    (
        postID INT NOT NULL AUTO_INCREMENT,
        username VARCHAR(30) NOT NULL,
        postName VARCHAR(128),
        postContent TEXT,
        PRIMARY KEY (postID)
    );


CREATE TABLE Subscriptions 
    (
        subscribedUsername VARCHAR(30) NOT NULL,
        username VARCHAR(30) NOT NULL,
        PRIMARY KEY (subscribedUsername, username)
    );

CREATE TABLE Likes 
    (
        username VARCHAR(30) NOT NULL,
        postID int NOT NULL,
        PRIMARY KEY (username, postID)
    );


CREATE TABLE Comments 
    (
        commentID INT NOT NULL AUTO_INCREMENT,
        username VARCHAR(30) NOT NULL,
        comment TEXT,
        PRIMARY KEY (commentID)
    );