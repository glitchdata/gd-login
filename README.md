# GD Login (PHP Edition)

A lightweight, traditional PHP login portal that uses server-rendered forms, PHP sessions, and a JSON-backed user store. The app features a split login/registration page and a protected dashboard rendered with classic PHP templates.

## Features

- PHP session-based authentication without external frameworks
- Login and registration handled by `index.php` with friendly error banners
- User data persisted to `data/users.json` with `password_hash` / `password_verify`
- Protected `dashboard.php` that greets the signed-in user and exposes account metadata
- Simple `logout.php` endpoint to clear the session

## Requirements

- PHP 8.1 or newer with JSON extension enabled (default in most installs)

## Getting started

1. **Install dependencies** – none beyond PHP itself.
2. **Seed demo data (optional)** – `public_html/data/users.json` already includes `demo@example.com / password`. You can delete the file to start fresh.
3. **Run the built-in PHP server**
   ```bash
   php -S localhost:8000 -t public_html
   ```
4. Visit `http://localhost:8000` in your browser. Use the login form or create a new account.

## Project structure

```
.
├── public_html
│   ├── assets
│   │   └── styles.css          # Shared styling for login + dashboard
│   ├── data
│   │   └── users.json          # JSON data store (bcrypt hashes)
│   ├── includes
│   │   └── users.php           # Helper functions for user CRUD + auth
│   ├── dashboard.php           # Protected area
│   ├── index.php               # Login + registration portal
│   └── logout.php              # Session teardown
└── README.md
```

## Default account

If you kept the bundled seed file you can log in with:

- Email: `demo@example.com`
- Password: `password`

Otherwise, submit the “Create account” form to register a new profile. Users are written to `public_html/data/users.json` using `password_hash()` with the BCRYPT algorithm.

## Notes

- This project is intentionally simple for educational purposes. For production, move the data store to a proper database and enforce CSRF tokens.
- The JSON file is not locked between requests beyond the `LOCK_EX` used during writes. Avoid concurrent writes or adopt a database when scaling up.
