# Feeling Lucky Game

This project supports both standard (HTTP) and secure (HTTPS) configurations.

### 1. Standard (HTTP) - Default

This is the easiest way to run the application locally.

```bash
docker compose up -d
```

The application will be available at: **[http://127.0.0.1](http://127.0.0.1)**

### 2. Secure (HTTPS)

To run the application with SSL, you must first generate valid certificates. We recommend using `mkcert`.

#### Generate Certificates for localhost and 127.0.0.1

1.  Use [mkcert](https://github.com/FiloSottile/mkcert).

#### Launch with SSL

```bash
docker compose -f docker-compose.ssl.yml up -d
```

The application will be available at: **[https://127.0.0.1](https://127.0.0.1)**

### 3. If there is 502 Bad Gateway error, just wait a few seconds and try again.

### 4. Running Tests

To run the test suite, ensure your Docker containers are running, then execute:

```bash
docker compose exec php php artisan test
```

To run a specific test file:

```bash
docker compose exec php php artisan test tests/Feature/Api/GameControllerTest.php
```

## Usage Guide

### Registration

1.  On the home page, enter your **Username** and **Phonenumber**.
2.  Click **Register**.
3.  You will be redirected to your unique Game Page.
4.  **Note**: The unique link is valid for 7 days. Save it!

### Playing the Game

1.  Click **"I'm feeling lucky"**.
2.  A random number (1-1000) will be generated.
3.  **Win Condition**: If the number is **EVEN**, you win!
    - **> 900**: Win 70% of the number.
    - **> 600**: Win 50% of the number.
    - **> 300**: Win 30% of the number.
    - **<= 300**: Win 10% of the number.
4.  **Lose Condition**: If the number is **ODD**, you lose.

### Other Features

- **History**: Click the **History** button to see your last 3 results.
- **Deactivate Link**: Disables the current unique link. You will need to register again.
- **Regenerate Link**: Generates a new unique link for your account (invalidates the old one).

## Technical Details

- **Backend**: Laravel 12 (PHP 8.5)
- **Frontend**: Vue 3
- **Database**: MySQL 8.0
- **Server**: Nginx 1.29
