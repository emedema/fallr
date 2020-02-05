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
```

## Logging In (Completed)

This creates the session token.

```
POST | [domain]/login
```

## User Data

### Getting user information (Completed)
Depending on if you are logged in, you will recieve all of the user information or just what the public has been allowed to see.

```
GET | [domain]/users/[userID]
```

### Update user information 

Requires valid key [Owner].

```
POST | [domain]/users/[userID]
``` 

## Feed

### Get the feed for a user (Completed)

Requires valid key [Owner].

```
GET | [domain]/feed
```

### Get the hot feed (Completed)

```
GET | [domain]/feed/hot
```

## Posts

### Create a post (Completed)
```
POST | [domain]/post
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

### Deleting a post (Not Tested)

Requires a valid key [Owner].

```
DELETE | [domain]/posts/id/[postID]
```

### Comment on a post (Completed)

Requires a valid key [logged in].

```
POST | [domain]/comment
```

### Get Comments for a post (Completed)

```
GET | [domain]/posts/comments/[postID]
```

### Get Comment (Completed)

```
GET | [domain]/comments/[commentID]
```

### Delete Comment

Requires a valid key [Owner].

```
DELETE | [domain]/comments/[commentID]
```

## Subscriptions

### Subscribing/Unsubscribing (Completed)
```
POST | [domain]/subscribe
```

### Get subscriptions for a user (Completed)
```
GET | [domain]/users/subscriptions/[userID]
```

## Likes

### Liking/UnLiking (Completed)
```
POST | [domain]/like
```

### Get Likes for a post (Completed)
```
GET | [domain]/posts/likes/[postID]
```