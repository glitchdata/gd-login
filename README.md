# GD Login

A minimal Express + vanilla JS web app that demonstrates login, registration, and session-backed dashboard navigation.

## Features

- Email/password registration with bcrypt hashing
- Login endpoint that seeds the session and redirects to a protected dashboard
- Session persistence with `express-session` cookies
- Basic account management view that displays stored profile info
- JSON-based user store for simple demos (no external database required)

## Getting started

1. **Install dependencies**
   ```bash
   npm install
   ```
   > If you are on macOS/Homebrew and encounter missing ICU libraries, reinstall `icu4c` and relink Node:
   > ```bash
   > brew reinstall icu4c
   > brew reinstall node
   > ```
2. **Run the server**
   ```bash
   npm run dev
   ```
3. Open `http://localhost:3000` in your browser.

## Default account

Use the bundled seed user to log in immediately:

- Email: `demo@example.com`
- Password: `password`

You can also register a new account via the form on the landing page.

## Environment variables

- `PORT`: Port number for the HTTP server (defaults to `3000`).
- `SESSION_SECRET`: Secret used to sign the session cookie. Always override this in production.

## Project structure

```
.
├── data
│   └── users.json          # Simple JSON user store
├── public
│   ├── app.js              # Login + registration interactions
│   ├── dashboard.html      # Protected page
│   ├── dashboard.js        # Dashboard logic
│   ├── index.html          # Landing page
│   └── styles.css          # Shared styles
├── src
│   ├── server.js           # Express app + routes
│   └── usersStore.js       # User CRUD helpers
├── package.json
└── README.md
```

## API reference

| Method | Path           | Description                       |
| ------ | -------------- | --------------------------------- |
| POST   | `/api/register`| Create a new user and start session |
| POST   | `/api/login`   | Authenticate and start session    |
| POST   | `/api/logout`  | Destroy active session            |
| GET    | `/api/session` | Returns session state + user info |

All endpoints exchange JSON payloads and expect `Content-Type: application/json`.

## Notes

- This demo stores users in `data/users.json`. For production, replace the store with a real database and stronger validation.
- Cookies are marked `secure` only when `NODE_ENV=production`. Behind HTTPS you should enable that flag.
