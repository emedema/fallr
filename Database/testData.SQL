/* 

    This is "a" test file for ensuring that all of the API code is running properly
    Nathan Nesbitt, 2020-01-29

*/

/* Creates the users */

INSERT INTO Users (username, password, firstName, lastName, email, image) VALUES ("a", "a", "a@a.ca", "a", "a", "$2y$11$/YHQfXMV8SxCxEYGfz0R4esNd2XXb/8c73EnvM0dRfSzHPOFX56V2");
INSERT INTO Users (username, password, firstName, lastName, email, image) VALUES ("b", "b", "b@b.ca", "b", "b", "$2y$11$Rg7I1RKI9tirBl/kif/N1um1RRIvB1c.a16/joglt.PI7yrdaATO6");
INSERT INTO Users (username, password, firstName, lastName, email, image) VALUES ("c", "c", "c@c.ca", "c", "c", "$2y$11$cMHPFSBFYFDfaU1vFQjK4uK2/R3cnYjEhs7UuvHatH5feMoBzld7C");

/* Creates the Posts*/
INSERT INTO Posts (username, postName, postContent) VALUES ("a", "post1", "postContent1");
INSERT INTO Posts (username, postName, postContent) VALUES ("b", "post2", "postContent2");
INSERT INTO Posts (username, postName, postContent) VALUES ("c", "post3", "postContent3");

/* Creates some Subscriptions */
INSERT INTO Subscriptions (subscribedUsername, username) VALUES ("a", "b");
INSERT INTO Subscriptions (subscribedUsername, username) VALUES ("a", "c");
INSERT INTO Subscriptions (subscribedUsername, username) VALUES ("b", "a");
INSERT INTO Subscriptions (subscribedUsername, username) VALUES ("b", "c");
INSERT INTO Subscriptions (subscribedUsername, username) VALUES ("c", "b");

/* Creates some likes */
INSERT INTO Likes (username, postID) VALUES ("a", 1);
INSERT INTO Likes (username, postID) VALUES ("b", 1);
INSERT INTO Likes (username, postID) VALUES ("c", 1);

INSERT INTO Likes (username, postID) VALUES ("a", 2);
INSERT INTO Likes (username, postID) VALUES ("b", 2);

INSERT INTO Likes (username, postID) VALUES ("a", 3);

/* Creates some comments */

INSERT INTO Comments (postID, username, comment) VALUES (1, "a", "Comment 1");
INSERT INTO Comments (postID, username, comment) VALUES (1, "a", "Comment 2");
INSERT INTO Comments (postID, username, comment) VALUES (1, "b", "Comment 3");