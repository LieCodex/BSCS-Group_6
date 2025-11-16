# RizGroup

# Squeal API Documentation for FlutterFlow

**Base URL:** `https://yourdomain.com/api`  
**Authentication:** Bearer Token (generated on login/register via Sanctum)

---

## 1. Test Endpoint
**GET** `/ping`

**Description:** Simple test to check API connectivity.  
**Authentication:** None

**Response:**
```json
{
  "message": "pong"
}
```

---

## 2. Authentication

### Register
**POST** `/register`

**Body Parameters:**
```json
{
  "name": "string, min 3 chars, unique",
  "email": "string, unique, must end with @gmail.com/@yahoo.com/@usm.edu.ph",
  "password": "string, 8-30 chars"
}
```

**Response:**
```json
{
  "success": true,
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@gmail.com"
  },
  "token": "api-token-string"
}
```

### Login
**POST** `/login`

**Body Parameters:**
```json
{
  "email": "string",
  "password": "string"
}
```

**Response:**
```json
{
  "success": true,
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@gmail.com"
  },
  "token": "api-token-string"
}
```

---

## 3. User Profile

### Get Current User Profile
**GET** `/user/profile`  
**Authentication:** Required

**Response:**
```json
{
  "success": true,
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@gmail.com",
    "bio": "string",
    "avatar": "url"
  },
  "posts": [
    {
      "id": 10,
      "body": "Hello world",
      "images": [{"id": 1, "image_path": "url"}],
      "comments": [...]
    }
  ],
  "followers_count": 5,
  "following_count": 3
}
```

### Update Profile
**PUT** `/user/profile`  
**Authentication:** Required

**Body Parameters (optional avatar upload):**
```json
{
  "name": "string",
  "bio": "string",
  "avatar": "file (jpg/jpeg/png/gif)"
}
```

**Response:**
```json
{
  "success": true,
  "user": {
    "id": 1,
    "name": "John Doe",
    "bio": "Updated bio",
    "avatar": "url"
  }
}
```

### Get Another User Profile
**GET** `/user/{id}`  
**Authentication:** Required

**Response:**
```json
{
  "success": true,
  "user": {
    "id": 2,
    "name": "Jane Doe",
    "avatar": "url"
  },
  "posts": [...],
  "followers_count": 10,
  "following_count": 2
}
```

---

## 4. Posts

### Get All Posts
**GET** `/posts`  
**Authentication:** Optional

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "body": "Post content",
      "user": {"id": 1, "name": "John Doe"},
      "images": [{"id":1,"image_path":"url"}],
      "comments": [...],
      "likes": [...],
      "is_liked": true
    }
  ]
}
```

### Create Post
**POST** `/posts`  
**Authentication:** Required

**Body Parameters:**
```json
{
  "body": "string",
  "image_urls": ["url1", "url2"]  // optional
}
```

**Response:**
```json
{
  "success": true,
  "post": {
    "id": 1,
    "body": "Hello world",
    "images": [{"id":1,"image_path":"url"}],
    "user": {"id":1,"name":"John Doe"}
  }
}
```

---

## 5. Post Likes

### Like Post
**POST** `/posts/{post}/like`  
**Authentication:** Required

**Response:**
```json
{
  "success": true,
  "liked": true,
  "likes": 10,
  "like_url": "url",
  "unlike_url": "url"
}
```

### Unlike Post
**POST** `/posts/{post}/unlike`  
**Authentication:** Required

**Response:**
```json
{
  "success": true,
  "liked": false,
  "likes": 9,
  "like_url": "url",
  "unlike_url": "url"
}
```

---

## 6. Comments

### Create Comment
**POST** `/posts/{post}/comments`  
**Authentication:** Required

**Body Parameters:**
```json
{
  "content": "string",
  "parent_comment_id": 1 // optional for replies
}
```

**Response:**
```json
{
  "message": "Comment created successfully.",
  "comment": {
    "id": 10,
    "content": "string",
    "parent_comment_id": null
  },
  "bot_reply": {
    "id": 11,
    "content": "AI response if @squeal mentioned",
    "parent_comment_id": 10
  }
}
```

### Delete Comment
**DELETE** `/comments/{comment}`  
**Authentication:** Required

**Response:**
```json
{
  "success": true,
  "message": "Comment deleted successfully."
}
```

---

## 7. Comment Likes

### Like Comment
**POST** `/comments/{comment}/like`  
**Authentication:** Required

**Response:**
```json
{
  "success": true,
  "liked": true,
  "likes": 5
}
```

### Unlike Comment
**POST** `/comments/{comment}/unlike`  
**Authentication:** Required

**Response:**
```json
{
  "success": true,
  "liked": false,
  "likes": 4
}
```

---

## 8. Follow System

### Follow User
**POST** `/users/{user}/follow`  
**Authentication:** Required

**Response:**
```json
{
  "success": true,
  "message": "You are now following Jane Doe",
  "following": true,
  "user_id": 2
}
```

### Unfollow User
**POST** `/users/{user}/unfollow`  
**Authentication:** Required

**Response:**
```json
{
  "success": true,
  "message": "You unfollowed Jane Doe",
  "following": false,
  "user_id": 2
}
```

### Check Following Status
**GET** `/users/{user}/is-following`  
**Authentication:** Required

**Response:**
```json
{
  "success": true,
  "following": true,
  "user_id": 2
}
```

---

## 9. Notifications

### Get Notifications
**GET** `/notifications`  
**Authentication:** Required

**Response:**
```json
{
  "success": true,
  "data": [
    {"id": 1, "type": "like_post", "preview_text": "liked your post.", "is_seen": false}
  ]
}
```

### Mark Notification as Seen
**POST** `/notifications/{notification}/seen`  
**Authentication:** Required

**Response:**
```json
{
  "success": true,
  "notification": {"id":1,"is_seen":true}
}
```

### Count Unseen Notifications
**GET** `/notifications/unseen-count`  
**Authentication:** Required

**Response:**
```json
{
  "success": true,
  "unseen_count": 3
}
```

### Mark All as Seen
**POST** `/notifications/mark-all-seen`  
**Authentication:** Required

**Response:**
```json
{
  "success": true,
  "message": "All notifications marked as seen."
}
```

---

## 10. Search

### Search Users & Posts
**GET** `/search?q=keyword`  
**Authentication:** Required

**Response:**
```json
{
  "success": true,
  "query": "keyword",
  "posts": [...],
  "users": [...]
}
```

---

✅ **Notes for FlutterFlow:**

- All endpoints requiring authentication need a Bearer Token in headers.  
- Image uploads in FlutterFlow require `multipart/form-data`.  
- All POST requests return JSON with `success` boolean.  
- Optional fields can be omitted, but required fields will throw va








To make this project work, do this step

1. clone the project using git clone command in the htdocs folder in xampp program files if you are using xampp or in the www if you are using laragon
    "git clone <url>"
    then "cd RizGroup" => "cd app"
2. run "composer install" in your terminal
3. run this command "cp .env.example .env "
4. the this "php artisan key:generate"
5. then this "php artisan migrate"
6. then this "npm install"
"npm run build"
"npm run dev"
7. Crtl + C to stop the process
9. Since we are using images, run "php artisan storage:link", if you aren't, then don't run this
8. then run "php artisan serve"

When pulling new data run these commands to make sure nothing will go wrong with syncronazation

1. "composer install" - if there is changes in composer.json
2. "npm install" - if there is changes in package.json
3. "php artisan migrate" - if there is data changes in database

If by some situation where php does not work, follow these steps:
There maybe be 2 php versions being used, one in xampp and one in your system.
The one that is working is the one in xampp, so you need to change the php path in your system environment variable.
To do this, follow these steps:
1. Search for "Environment Variables" in your system settings.
2. Click on "Environment Variables".
3. Under "System variables", find the "Path" variable and click "Edit".
4. Add the path to the php executable in your xampp installation, typically something like `C:\xampp\php`., if it's on laragon it will be `C:\laragon\bin\php\php-8.1.0-Win32-vs16-x64`
   - Make sure to use the correct version of PHP that your project requires.
5. move the new path to the top of the list to give it priority above other php installations.
6. Click "OK" to save the changes.
7. Restart your terminal or command prompt and vsCode to apply the changes.

Possible Problem: cURL Error 60  SSL Certificate Issue

Run:
php --ini

It should show:
Loaded Configuration File: C:\xampp\php\php.ini

If it doesn't, go to the path shown and open the php.ini file manually.
Download this file: https://curl.se/ca/cacert.pem
Save it to:
C:\xampp\php\extras\SSL

Copy the full path of the file:
C:\xampp\php\extras\SSL\cacert.pem

Open php.ini and search for curl.cainfo and openssl.cafile.
Remove any semicolons (;) at the start of the lines.

Paste the path so it looks like this:

curl.cainfo="C:\xampp\php\extras\SSL\cacert.pem"
openssl.cafile="C:\xampp\php\extras\SSL\cacert.pem"

Save the php.ini file.

Restart the Apache server in XAMPP.



Notes for accessing the droplet server:
1. run cmd
2. run "ssh root@squeal.site"
3. password: 6_cMtKzvj.2!v2T
4. run "cd /var/www/RizGroup/app"

commmand for maintaining the server: //this does not need to be run every time, only when server is off or down
# Reload Nginx after changing configs
sudo systemctl reload nginx

# Restart PHP-FPM after PHP config changes
sudo systemctl restart php8.4-fpm

# Check status
sudo systemctl status nginx
sudo systemctl status php8.4-fpm

run this commmand to pull new changes
    bash update.sh


command for accessing the mysql database
# Login as your Laravel database admin
    mysql -u admin -p
password: admin
#search up the commands because I don't know them


steps to use the server database in local to work on the same data

#note! any changes in the migrations will affect the server database, so
be careful and make sure to pull all the changes in the server
before using the server database with the changes, as to not have discrepancies
in the database structure.

1. open a cmd terminal and run "ssh -L 3307:127.0.0.1:3306 root@squeal.site"
2. password: 6_cMtKzvj.2!v2T
note! keep the terminal open, if you close this, the port will also close
3. change your .env file to:
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3307
    DB_DATABASE=app
    DB_USERNAME=admin
    DB_PASSWORD=admin
done! you are now using the server database in your local project



