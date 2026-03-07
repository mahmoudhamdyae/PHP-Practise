<?php
require_once 'connection.php';

$errors  = [];
$success = false;

function addUser(PDO $pdo, string $fullName, string $userName, string $password, string $email, string $birthDate): void
{
    $stmt = $pdo->prepare('
        INSERT INTO users (full_name, user_name, password, email, birth_date)
        VALUES (:full_name, :user_name, :password, :email, :birth_date)
    ');

    $stmt->execute([
        ':full_name'  => $fullName,
        ':user_name'  => $userName,
        ':password'   => password_hash($password, PASSWORD_BCRYPT),
        ':email'      => $email,
        ':birth_date' => $birthDate,
    ]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName  = trim($_POST['full_name']  ?? '');
    $userName  = trim($_POST['user_name']  ?? '');
    $email     = trim($_POST['email']      ?? '');
    $birthDate = trim($_POST['birth_date'] ?? '');
    $password  = $_POST['password']        ?? '';
    $confirm   = $_POST['confirm_password'] ?? '';

    if ($fullName === '')  $errors['full_name']  = 'Full name is required.';
    if ($userName === '')  $errors['user_name']  = 'Username is required.';

    if ($email === '') {
        $errors['email'] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email address.';
    }

    if ($birthDate === '') $errors['birth_date'] = 'Birth date is required.';

    if ($password === '') {
        $errors['password'] = 'Password is required.';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters.';
    }

    if ($confirm === '') {
        $errors['confirm_password'] = 'Please confirm your password.';
    } elseif ($password !== $confirm) {
        $errors['confirm_password'] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        try {
            $pdo = new PDO(
                sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', User::DB_HOST, User::DB_NAME),
                User::DB_USER,
                User::DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            addUser($pdo, $fullName, $userName, $password, $email, $birthDate);
            $success = true;
        } catch (PDOException $e) {
            $errors['db'] = 'Database error: ' . $e->getMessage();
        }
    }
}

$old = [
    'full_name'  => htmlspecialchars($_POST['full_name']  ?? '', ENT_QUOTES, 'UTF-8'),
    'user_name'  => htmlspecialchars($_POST['user_name']  ?? '', ENT_QUOTES, 'UTF-8'),
    'email'      => htmlspecialchars($_POST['email']      ?? '', ENT_QUOTES, 'UTF-8'),
    'birth_date' => htmlspecialchars($_POST['birth_date'] ?? '', ENT_QUOTES, 'UTF-8'),
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            font-family: 'Segoe UI', system-ui, sans-serif;
            padding: 2rem 1rem;
        }

        .card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
        }

        .card-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .card-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.5px;
        }

        .card-header p {
            margin-top: 0.4rem;
            color: rgba(255,255,255,0.5);
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: rgba(255,255,255,0.7);
            margin-bottom: 0.4rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        input {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.12);
            background: rgba(255,255,255,0.07);
            color: #fff;
            font-size: 0.95rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        input::placeholder { color: rgba(255,255,255,0.3); }

        input:focus {
            border-color: #7c5cfc;
            box-shadow: 0 0 0 3px rgba(124,92,252,0.25);
        }

        input.input-error {
            border-color: #f87171;
        }

        .error-msg {
            display: block;
            margin-top: 0.35rem;
            font-size: 0.8rem;
            color: #f87171;
        }

        .alert {
            padding: 0.9rem 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: 0.88rem;
            font-weight: 500;
        }

        .alert-success {
            background: rgba(52, 211, 153, 0.15);
            border: 1px solid rgba(52,211,153,0.4);
            color: #34d399;
        }

        .alert-error {
            background: rgba(248, 113, 113, 0.12);
            border: 1px solid rgba(248,113,113,0.35);
            color: #f87171;
        }

        .btn-submit {
            width: 100%;
            padding: 0.85rem;
            margin-top: 0.5rem;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #7c5cfc, #5b8af5);
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.15s;
            letter-spacing: 0.3px;
        }

        .btn-submit:hover  { opacity: 0.88; transform: translateY(-1px); }
        .btn-submit:active { transform: translateY(0); }

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        @media (max-width: 480px) { .row { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<div class="card">
    <div class="card-header">
        <h1>➕ Add User</h1>
        <p>Fill in the details to create a new account</p>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success">✅ User added successfully!</div>
    <?php endif; ?>

    <?php if (isset($errors['db'])): ?>
        <div class="alert alert-error">⚠️ <?= $errors['db'] ?></div>
    <?php endif; ?>

    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>" method="POST" novalidate>

        <div class="row">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input
                    type="text"
                    id="full_name"
                    name="full_name"
                    placeholder="John Doe"
                    value="<?= $old['full_name'] ?>"
                    class="<?= isset($errors['full_name']) ? 'input-error' : '' ?>"
                >
                <?php if (isset($errors['full_name'])): ?>
                    <span class="error-msg"><?= $errors['full_name'] ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="user_name">Username</label>
                <input
                    type="text"
                    id="user_name"
                    name="user_name"
                    placeholder="johndoe"
                    value="<?= $old['user_name'] ?>"
                    class="<?= isset($errors['user_name']) ? 'input-error' : '' ?>"
                >
                <?php if (isset($errors['user_name'])): ?>
                    <span class="error-msg"><?= $errors['user_name'] ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input
                type="email"
                id="email"
                name="email"
                placeholder="john@example.com"
                value="<?= $old['email'] ?>"
                class="<?= isset($errors['email']) ? 'input-error' : '' ?>"
                autocomplete="email"
            >
            <?php if (isset($errors['email'])): ?>
                <span class="error-msg"><?= $errors['email'] ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="birth_date">Birth Date</label>
            <input
                type="date"
                id="birth_date"
                name="birth_date"
                value="<?= $old['birth_date'] ?>"
                class="<?= isset($errors['birth_date']) ? 'input-error' : '' ?>"
            >
            <?php if (isset($errors['birth_date'])): ?>
                <span class="error-msg"><?= $errors['birth_date'] ?></span>
            <?php endif; ?>
        </div>

        <div class="row">
            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Min. 8 characters"
                    class="<?= isset($errors['password']) ? 'input-error' : '' ?>"
                    autocomplete="new-password"
                >
                <?php if (isset($errors['password'])): ?>
                    <span class="error-msg"><?= $errors['password'] ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    placeholder="Re-enter password"
                    class="<?= isset($errors['confirm_password']) ? 'input-error' : '' ?>"
                    autocomplete="new-password"
                >
                <?php if (isset($errors['confirm_password'])): ?>
                    <span class="error-msg"><?= $errors['confirm_password'] ?></span>
                <?php endif; ?>
            </div>
        </div>

        <button type="submit" class="btn-submit">Add User</button>
    </form>
</div>
</body>
</html>
