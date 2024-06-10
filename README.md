# Subscription Notification System - Notify Me

A Laravel-based application that allows users to subscribe to websites and receive email notifications for new posts.

## Features

- Subscribe to websites using an email address.
- Create posts for subscribed websites.
- Send email notifications to subscribers for new posts.
- Ensure no duplicate notifications are sent.
- Use queues to handle email sending in the background.

## Requirements

- PHP 7.4 or higher
- Composer
- MySQL
- Mailtrap or other email testing tool (for development)

## Setup Instructions

### Step 1: Clone the Repository
```
git clone git@github.com:roc41d/notify-me.git
cd notify-me
```

### Step 2: Install Dependencies
```
composer install
```

### Step 3: Configure Environment Variables
```
cp .env.example .env
```

Edit the .env file to set up your database, mail, and queue configurations:
```
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=database
SESSION_DRIVER=file
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=from@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Step 4: Generate Application Key
```
php artisan key:generate
```

### Step 5: Run Migrations
```
php artisan migrate
```

### Step 6: Seed the Database (Optional)
```
php artisan db:seed
```

### Step 7: Set Up Queue Worker
Start the queue worker to process the email jobs.
```
php artisan queue:work
```

### Step 8: Set Schudeler
Start the scheduler worker to process cron jobs.
```
php artisan schedule:work
```

### Step 9: Run the Application
```
php artisan serve
```

### Step 10: Test the Application
```
http://localhost:8000/api/
```

You can now use Postman or any other API client to interact with the endpoints.

#### Create Website
Endpoint: `POST /websites`

Request Body:

```
{
    "name": "Acme Inc",
    "url": "https://acmeinc.xzy"
}
```

#### Get Websites
Endpoint: `Get /websites?limit=20`

#### Subscribe to a Website:

Endpoint: `POST /subscribe`

Request Body:
```
{
    "email": "user@example.com",
    "website_id": 1
}
```

#### Create a Post:

Endpoint: `POST /posts`

Request Body:
```
{
    "website_id": 1,
    "title": "New Post Title",
    "description": "Description of the new post."
}
```

#### Create a Post:

Endpoint: `GET /posts?limit=20`

### Note: For testing purposes, the command checks for new posts and notifies subscribers every five minutes.

This can be change in `/routes/console.php`
```
Schedule::command(SendEmails::class)->everyFiveMinutes();
```