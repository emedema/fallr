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

CREATE TABLE fallr.Users 
    (
        username VARCHAR(30) NOT NULL UNIQUE, /* As we want each user to have a unique username */
        email VARCHAR(40) NOT NULL UNIQUE, /* As we want each user to have a unique email (avoid duplicates) */
        active BOOLEAN default true,
        admin BOOLEAN default false,
        firstName VARCHAR(255),
        lastName VARCHAR(255),
        image TEXT, /* This is the location on the server for the image that they uploaded */
        backgroundImage TEXT, /* This is the image for the background of their page */
        password CHAR(60) NOT NULL,
        PRIMARY KEY(username)
    );

CREATE TABLE fallr.Tokens 
    (
        tokenID INT NOT NULL AUTO_INCREMENT,
        username VARCHAR(30) NOT NULL,
        token VARCHAR(512),
        PRIMARY KEY (tokenID),
        FOREIGN KEY (username)
            REFERENCES Users(username)
            ON DELETE CASCADE
            ON UPDATE CASCADE 
    );


CREATE TABLE fallr.Posts 
    (
        postID INT NOT NULL AUTO_INCREMENT,
        created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        username VARCHAR(30) NOT NULL,
        postName VARCHAR(128),
        postContent TEXT,
        image TEXT,
        PRIMARY KEY (postID),
        FOREIGN KEY (username)
            REFERENCES Users(username)
            ON DELETE CASCADE
            ON UPDATE CASCADE
    );


CREATE TABLE fallr.Subscriptions 
    (
        subscribedUsername VARCHAR(30) NOT NULL,
        username VARCHAR(30) NOT NULL,
        CONSTRAINT uniqueSubscription 
            UNIQUE (subscribedUsername, username),
        FOREIGN KEY (username)
            REFERENCES Users(username)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
        FOREIGN KEY (subscribedUsername)
            REFERENCES Users(username)
            ON DELETE CASCADE
            ON UPDATE CASCADE
    );

CREATE TABLE fallr.Likes 
    (
        username VARCHAR(30) NOT NULL,
        postID int NOT NULL,
        PRIMARY KEY (username, postID),
        FOREIGN KEY (username)
            REFERENCES Users(username)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
        FOREIGN KEY (postID)
            REFERENCES Posts(postID)
            ON DELETE CASCADE
            ON UPDATE CASCADE
    );


CREATE TABLE fallr.Comments 
    (
        commentID INT NOT NULL AUTO_INCREMENT,
        postID int NOT NULL,
        username VARCHAR(30) NOT NULL,
        comment TEXT,
        PRIMARY KEY (commentID),
        FOREIGN KEY (username)
            REFERENCES Users(username)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
        FOREIGN KEY (postID)
            REFERENCES Posts(postID)
            ON DELETE CASCADE
            ON UPDATE CASCADE    
    );

CREATE TABLE fallr.UpdatePassword (
    creationDate DATETIME default CURRENT_TIMESTAMP,
    email VARCHAR(40),
    link TEXT,
    FOREIGN KEY (email)
            REFERENCES Users(email)
            ON DELETE CASCADE
            ON UPDATE CASCADE
);

CREATE EVENT deleteUpdatePassword
    ON SCHEDULE EVERY 24 HOUR
    DO DELETE FROM fallr.UpdatePassword WHERE `creationDate` < (CURRENT_TIMESTAMP - INTERVAL 24 HOUR);