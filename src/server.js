import express from 'express';
import session from 'express-session';
import path from 'path';
import { fileURLToPath } from 'url';
import { createUser, validateUserCredentials } from './usersStore.js';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const publicDir = path.join(__dirname, '..', 'public');
const app = express();
const PORT = process.env.PORT || 3000;

app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(
  session({
    name: 'gdlogin.sid',
    secret: process.env.SESSION_SECRET || 'change-this-secret',
    resave: false,
    saveUninitialized: false,
    cookie: {
      httpOnly: true,
      sameSite: 'lax',
      maxAge: 1000 * 60 * 60, // 1 hour
      secure: process.env.NODE_ENV === 'production'
    }
  })
);

const requireAuth = (req, res, next) => {
  if (req.session.user) {
    return next();
  }
  if (req.accepts('html')) {
    return res.redirect('/');
  }
  return res.status(401).json({ message: 'Authentication required' });
};

app.use(express.static(publicDir));

app.get('/', (req, res) => {
  res.sendFile(path.join(publicDir, 'index.html'));
});

app.get('/dashboard', requireAuth, (req, res) => {
  res.sendFile(path.join(publicDir, 'dashboard.html'));
});

app.get('/api/session', (req, res) => {
  if (!req.session.user) {
    return res.status(200).json({ authenticated: false });
  }
  return res.json({ authenticated: true, user: req.session.user });
});

app.post('/api/login', async (req, res, next) => {
  try {
    const { email, password } = req.body;
    const user = await validateUserCredentials(email, password);

    if (!user) {
      return res.status(401).json({ message: 'Invalid email or password' });
    }

    req.session.user = user;
    return res.json({ message: 'Login successful', user });
  } catch (error) {
    return next(error);
  }
});

app.post('/api/register', async (req, res, next) => {
  try {
    const { name, email, password } = req.body;

    if (!name || !email || !password) {
      return res.status(400).json({ message: 'All fields are required' });
    }

    const newUser = await createUser({ name, email, password });
    req.session.user = newUser;
    return res.status(201).json({ message: 'User created', user: newUser });
  } catch (error) {
    if (error.message === 'Email already exists') {
      return res.status(409).json({ message: error.message });
    }
    return next(error);
  }
});

app.post('/api/logout', (req, res, next) => {
  req.session.destroy((destroyErr) => {
    if (destroyErr) {
      return next(destroyErr);
    }
    res.clearCookie('gdlogin.sid');
    return res.json({ message: 'Logged out' });
  });
});

// Basic error handler
app.use((err, req, res, _next) => {
  console.error(err);
  res.status(500).json({ message: 'Unexpected error' });
});

app.listen(PORT, () => {
  console.log(`Server listening on http://localhost:${PORT}`);
});
