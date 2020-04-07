# API

## Why REST

1. We want to make AJAX calls for the data in the pages, which means we either create some wack php scrips that will simulate a similar function as a REST API or we just implement a REST API. 

2. Quite honestly we already have to implement something similar to this for another project, so this is the best time to learn :)

## Which REST framework
We will be writing our own as we have to write the back end in PHP. This will be a simple API and will allow users to:

1. Allow for a user to login
2. Get user page data
3. Get/Update user data
4. Get feed data (Per user or popular)
5. Get/Update/Delete/Comment a post

For reference you can use the [restAPItutorial](https://www.restapitutorial.com).

## API Pattern

This will be done in the following pattern:

1. User will login to the website, this will create a session key for that user.

2. That session key will be passed to the API each time the user requests information. 
    
    2.1 If the key is not present the API will fail out
    
    2.2 If the key is not valid the API will fail out.

3. If the user has admin privileges, the API will check to see if they are able to access/modify the information.

## Create Account (Completed)
This creates an account and creates a session token.

Takes: username, password, firstName, lastName, email, image

```
POST | [domain]/createAccount

Takes:
- username
- password
- firstName
- lastName
- email
```

## Logging In (Completed)

This creates the session token.

```
POST | [domain]/login
Takes:
- username
- password
```

## Logging Out (Completed)

This creates removes session token from the server.
As this is an API you have to remove the token from
the client using JavaScript.

```
POST | [domain]/logout
Takes:
- loggedIn
```

## User Data

### Getting user information (Completed)
Depending on if you are logged in, you will recieve all of the user information or just what the public has been allowed to see.

```
GET | [domain]/users/[userID]

Takes:
- loggedIn (optional depending if you want personal information)
```

### Update user information

Requires valid key [Owner].

```
POST | [domain]/user/[userID]

Takes:
- username
- firstName
- lastName
- email
``` 
### Update user image

Requires valid key [Owner].

```
POST | [domain]/user/[userID]

Takes:
- updateUser=true
- loggedIn
- myfile
```

### Update user background image

Requires valid key [Owner].

```
POST | [domain]/user/background/[userID]

Takes:
- loggedIn
- myfile
```

### Deactivate User (Completed)

Admins can activate or deactivate a user by using the following call

```
POST | [domain]/users/deactivate/[username]
```

## Feed

### Get the feed for a user (Completed)

Requires valid key [Owner].

```
GET | [domain]/feed

Takes:
- loggedIn 
```

### Get the hot feed (Completed)

```
GET | [domain]/feed/hot
```

## Posts

### Create a post (Completed)
```
POST | [domain]/post

Takes:
    - postName
    - postContent
    - loggedIn
```

### Get a users posts (Completed)

```
GET | [domain]/posts/[username]
```

### Get a post (Completed)

```
GET | [domain]/posts/id/[postID]
```

### Update a post (Not Tested)

```
POST | [domain]/posts/id/[postID]
```

### Deleting a post (Completed)

Requires a valid key [Owner].

```
DELETE | [domain]/posts/id/[postID]

Takes:
    - loggedIn
```

### Comment on a post (Completed)

Requires a valid key [logged in].

```
POST | [domain]/comment

Takes:
    - postID
    - comment
    - loggedIn
```

### Get Comments for a post (Completed)

```
GET | [domain]/posts/comments/[postID]
```

### Get Comment (Completed)

```
GET | [domain]/comments/[commentID]
```

### Delete Comment (Completed)

Requires a valid key [Owner].

```
DELETE | [domain]/comments/[commentID]

Takes:
    - loggedIn
```

## Subscriptions

### Subscribing/Unsubscribing (Completed)
```
POST | [domain]/subscribe

Takes:
    - username
    - loggedIn
```

### Get subscriptions for a user (Completed)
```
GET | [domain]/users/subscriptions/[userID]
```

## Likes

### Liking/UnLiking (Completed)
```
POST | [domain]/like
Takes:
    - postID
    - loggedIn
```

### Get Likes for a post (Completed)
```
GET | [domain]/posts/likes/[postID]
```

## Statistics 

### Get all posts from last hours
```
GET | [domain]/stats/posts/hour
```
### Get all posts from last 24 hours
```
GET | [domain]/stats/posts/day
```
### Get all posts from last week
```
GET | [domain]/stats/posts/week
```
### Get all posts from last month
```
GET | [domain]/stats/posts/month
```

# Return Values
If you are not getting 200 from your requests,
the following are more specific errors that you
could encounter. If you get anything else it is
not controlled, and is assumed to be an error.

## 405 - Creation/Get Failure
This means that whatever you tried to create or get failed, this cannot be recovered from, and means that someone needs to check the logs.

## 406 - Parameters Not Passed
This means that you didn't pass enough data to the
api for it to function. 

## 408 - Not Owner
This is when you are trying to update something that
isn't yours. For example if you try to delete someone
else's posts it will throw this, as you are not the 
owner.

## 409 - Login Invalid
This is used on the login page, it means that you
failed to login (bad username or password) or you
are not logged in.