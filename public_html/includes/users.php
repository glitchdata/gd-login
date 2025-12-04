<?php

const USERS_FILE = __DIR__ . '/../../schema/data/users.json';

function loadUsers(): array
{
    if (!file_exists(USERS_FILE)) {
        return [];
    }

    $raw = file_get_contents(USERS_FILE);
    $data = json_decode($raw, true);

    return is_array($data) ? $data : [];
}

function saveUsers(array $users): void
{
    $json = json_encode($users, JSON_PRETTY_PRINT);
    file_put_contents(USERS_FILE, $json, LOCK_EX);
}

function normalizeEmail(string $email): string
{
    return strtolower(trim($email));
}

function findUserByEmail(string $email): ?array
{
    $needle = normalizeEmail($email);
    foreach (loadUsers() as $user) {
        if (normalizeEmail($user['email']) === $needle) {
            return $user;
        }
    }

    return null;
}

function sanitizeUser(array $user): array
{
    $safe = $user;
    unset($safe['password']);
    return $safe;
}

function createUser(string $name, string $email, string $password): array
{
    $users = loadUsers();
    $normalizedEmail = normalizeEmail($email);

    foreach ($users as $existing) {
        if (normalizeEmail($existing['email']) === $normalizedEmail) {
            throw new RuntimeException('Email already registered. Please log in instead.');
        }
    }

    $newUser = [
        'id' => bin2hex(random_bytes(16)),
        'name' => trim($name),
        'email' => $normalizedEmail,
        'password' => password_hash($password, PASSWORD_BCRYPT),
        'createdAt' => date(DATE_ATOM)
    ];

    $users[] = $newUser;
    saveUsers($users);

    return $newUser;
}

function verifyCredentials(string $email, string $password): ?array
{
    $user = findUserByEmail($email);
    if (!$user) {
        return null;
    }

    if (!password_verify($password, $user['password'])) {
        return null;
    }

    return $user;
}
