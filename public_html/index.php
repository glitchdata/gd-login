<?php
session_start();
require __DIR__ . '/includes/users.php';

if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}

$errors = [];
$activeForm = $_POST['action'] ?? 'login';
$flashMessage = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($activeForm === 'login') {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $errors[] = 'Email and password are required to log in.';
        } else {
            $user = verifyCredentials($email, $password);
            if ($user === null) {
                $errors[] = 'Invalid email or password.';
            } else {
                $_SESSION['user'] = sanitizeUser($user);
                $_SESSION['flash'] = 'Welcome back!';
                header('Location: dashboard.php');
                exit;
            }
        }
    } elseif ($activeForm === 'register') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($name === '' || $email === '' || $password === '') {
            $errors[] = 'All fields are required to create an account.';
        } elseif (strlen($password) < 8) {
            $errors[] = 'Choose a password with at least 8 characters.';
        } else {
            try {
                $user = createUser($name, $email, $password);
                $_SESSION['user'] = sanitizeUser($user);
                $_SESSION['flash'] = 'Account created successfully!';
                header('Location: dashboard.php');
                exit;
            } catch (RuntimeException $e) {
                $errors[] = $e->getMessage();
            } catch (Throwable $e) {
                $errors[] = 'Unable to create account. Please try again.';
            }
        }
    }
}

function old(string $field): string
{
    return htmlspecialchars($_POST[$field] ?? '', ENT_QUOTES, 'UTF-8');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GD Login Portal</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <div class="page">
        <header class="hero">
            <div>
                <p class="eyebrow">Traditional PHP login</p>
                <h1>Welcome to GD Login</h1>
                <p class="lead">Sign in or create an account to reach your personalized dashboard.</p>
            </div>
        </header>

        <?php if ($flashMessage): ?>
            <div class="banner success">
                <p><?= htmlspecialchars($flashMessage, ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="banner error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <main class="forms">
            <section class="card">
                <h2>Sign in</h2>
                <form method="POST" class="stack">
                    <input type="hidden" name="action" value="login">
                    <label>
                        <span>Email</span>
                        <input type="email" name="email" value="<?= $activeForm === 'login' ? old('email') : ''; ?>" required>
                    </label>
                    <label>
                        <span>Password</span>
                        <input type="password" name="password" required>
                    </label>
                    <button type="submit">Login</button>
                    <p class="hint">Demo: demo@example.com / password</p>
                </form>
            </section>

            <section class="card alt">
                <h2>Create account</h2>
                <form method="POST" class="stack">
                    <input type="hidden" name="action" value="register">
                    <label>
                        <span>Name</span>
                        <input type="text" name="name" value="<?= $activeForm === 'register' ? old('name') : ''; ?>" required>
                    </label>
                    <label>
                        <span>Email</span>
                        <input type="email" name="email" value="<?= $activeForm === 'register' ? old('email') : ''; ?>" required>
                    </label>
                    <label>
                        <span>Password</span>
                        <input type="password" name="password" minlength="8" required>
                    </label>
                    <button type="submit">Register</button>
                    <p class="hint">Password must be at least 8 characters.</p>
                </form>
            </section>
        </main>
    </div>
</body>
</html>
