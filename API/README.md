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

## Layout of the API

The API will use the following layout. For any function that requires verification, the AJAX request will pass the session key for the user in the header:

```
$.ajax({
  type: 'GET',
  url: 'localhost/userpage/1',
  headers: {
    "auth-key":"0as8dha9s8dhaasc79asc98agsjacna98"
  }
});
```

## Create Account
This creates an account and creates a session token.

Takes: username, password, firstName, lastName, email, image

```
POST | [domain]/createAccount
```

## Logging In

This creates the session token.

```
POST | [domain]/login
```

## User Page Data

### Get User Page Data

```
GET | [domain]/userpages/[userID]
```

## User Data

### Getting the user information
Depending on if you send a key that has permission, you will recieve all of the user information or just what the public has been allowed to see.

```
GET | [domain]/users/[userID]
```

### Update user information

Requires valid key [Owner].

```
POST | [domain]/users/[userID]
``` 

## Feed

### Get the feed for a user

Requires valid key [Owner].

```
GET | [domain]/feed/[userID]
```

### Get the hot feed

```
GET | [domain]/feed/hot
```

## Posts

### Get a post

```
GET | [domain]/posts/[postID]
```

### Update a post

```
POST | [domain]/posts/[postID]
```

### Deleting a post

Requires a valid key [Owner].

```
DELETE | [domain]/posts/[postID]
```

### Comment on a post

Requires a valid key [logged in].

```
POST | [domain]/posts/comments/[postID]
```

### Get Comments

```
GET | [domain]/posts/comments/[postID]
```

### Delete Comment

Requires a valid key [Owner].

```
DELETE | [domain]/posts/comments/[postID]/[commentID]
```