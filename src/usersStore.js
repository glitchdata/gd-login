import { readFile, writeFile } from 'fs/promises';
import path from 'path';
import { randomUUID } from 'crypto';
import bcrypt from 'bcryptjs';

const dataFilePath = path.join(process.cwd(), 'data', 'users.json');

export const sanitizeUser = (user) => {
  if (!user) return null;
  const { password, ...rest } = user;
  return rest;
};

async function readUsers() {
  try {
    const raw = await readFile(dataFilePath, 'utf8');
    return JSON.parse(raw);
  } catch (error) {
    if (error.code === 'ENOENT') {
      return [];
    }
    throw error;
  }
}

async function writeUsers(users) {
  await writeFile(dataFilePath, JSON.stringify(users, null, 2), 'utf8');
}

export async function getAllUsers() {
  const users = await readUsers();
  return users.map(sanitizeUser);
}

export async function findUserByEmail(email) {
  if (!email) return null;
  const users = await readUsers();
  const user = users.find((entry) => entry.email.toLowerCase() === email.toLowerCase());
  return sanitizeUser(user);
}

export async function validateUserCredentials(email, password) {
  if (!email || !password) return null;
  const users = await readUsers();
  const user = users.find((entry) => entry.email.toLowerCase() === email.toLowerCase());
  if (!user) return null;
  const isValid = await bcrypt.compare(password, user.password);
  return isValid ? sanitizeUser(user) : null;
}

export async function createUser({ name, email, password }) {
  if (!name || !email || !password) {
    throw new Error('Name, email, and password are required');
  }

  const users = await readUsers();
  const normalizedEmail = email.toLowerCase();

  if (users.some((entry) => entry.email.toLowerCase() === normalizedEmail)) {
    throw new Error('Email already exists');
  }

  const hashedPassword = await bcrypt.hash(password, 10);
  const newUser = {
    id: randomUUID(),
    name,
    email: normalizedEmail,
    password: hashedPassword,
    createdAt: new Date().toISOString()
  };

  users.push(newUser);
  await writeUsers(users);
  return sanitizeUser(newUser);
}
