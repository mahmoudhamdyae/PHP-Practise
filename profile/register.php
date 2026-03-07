<?php
session_start();

$errors = [];
$old = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    $old['name']  = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $old['email'] = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');

    // --- Validation ---
    if ($name === '') {
        $errors['name'] = 'Name is required.';
    } elseif (strlen($name) < 2 || strlen($name) > 60) {
        $errors['name'] = 'Name must be between 2 and 60 characters.';
    }

    if ($email === '') {
        $errors['email'] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    if ($password === '') {
        $errors['password'] = 'Password is required.';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters.';
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $errors['password'] = 'Password must contain at least one uppercase letter.';
    } elseif (!preg_match('/[0-9]/', $password)) {
        $errors['password'] = 'Password must contain at least one number.';
    }

    if ($confirm === '') {
        $errors['confirm_password'] = 'Please confirm your password.';
    } elseif ($password !== $confirm) {
        $errors['confirm_password'] = 'Passwords do not match.';
    }

    // --- Image Upload ---
    $imagePath = null;
    if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
        $errors['image'] = 'Profile picture is required.';
    } elseif ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $errors['image'] = 'Image upload failed. Please try again.';
    } else {
        $file     = $_FILES['image'];
        $maxSize  = 2 * 1024 * 1024; // 2 MB
        $allowed  = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $finfo    = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if ($file['size'] > $maxSize) {
            $errors['image'] = 'Image must be smaller than 2 MB.';
        } elseif (!in_array($mimeType, $allowed, true)) {
            $errors['image'] = 'Only JPEG, PNG, GIF, and WebP images are allowed.';
        } else {
            $ext       = pathinfo($file['name'], PATHINFO_EXTENSION);
            $safeName  = bin2hex(random_bytes(16)) . '.' . strtolower($ext);
            $uploadDir = __DIR__ . '/uploads/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (move_uploaded_file($file['tmp_name'], $uploadDir . $safeName)) {
                $imagePath = 'uploads/' . $safeName;
            } else {
                $errors['image'] = 'Could not save image. Please try again.';
            }
        }
    }

    // --- All valid → store in session and redirect to profile ---
    if (empty($errors)) {
        $_SESSION['user'] = [
            'name'      => $old['name'],
            'email'     => $old['email'],
            'image'     => $imagePath,
            'password'  => password_hash($password, PASSWORD_BCRYPT),
        ];

        header('Location: profile.php');
        exit;
    }
}
?>
<?php require "header.php" ?>
<div class="card">
    <div class="card-header">
        <div class="avatar-placeholder" id="avatarPreview">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
            </svg>
        </div>
        <h1>Create Account</h1>
        <p>Fill in your details to get started</p>
    </div>

    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>" method="POST" enctype="multipart/form-data" novalidate>

        <div class="form-group">
            <label for="name">Full Name</label>
            <input
                type="text"
                id="name"
                name="name"
                placeholder="John Doe"
                value="<?= $old['name'] ?? '' ?>"
                class="<?= isset($errors['name']) ? 'input-error' : '' ?>"
                autocomplete="name"
            >
            <?php if (isset($errors['name'])): ?>
                <span class="error-msg"><?= $errors['name'] ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input
                type="email"
                id="email"
                name="email"
                placeholder="you@example.com"
                value="<?= $old['email'] ?? '' ?>"
                class="<?= isset($errors['email']) ? 'input-error' : '' ?>"
                autocomplete="email"
            >
            <?php if (isset($errors['email'])): ?>
                <span class="error-msg"><?= $errors['email'] ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="image">Profile Picture</label>
            <label for="image" class="file-label <?= isset($errors['image']) ? 'input-error' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
                    <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                </svg>
                <span id="fileName">Choose an image (max 2 MB)</span>
            </label>
            <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/gif,image/webp" style="display:none">
            <?php if (isset($errors['image'])): ?>
                <span class="error-msg"><?= $errors['image'] ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <div class="input-wrap">
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Min. 8 chars, 1 uppercase, 1 number"
                    class="<?= isset($errors['password']) ? 'input-error' : '' ?>"
                    autocomplete="new-password"
                >
                <button type="button" class="toggle-password" aria-label="Toggle password visibility" data-target="password">
                    <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                    </svg>
                </button>
            </div>
            <?php if (isset($errors['password'])): ?>
                <span class="error-msg"><?= $errors['password'] ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <div class="input-wrap">
                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    placeholder="Re-enter your password"
                    class="<?= isset($errors['confirm_password']) ? 'input-error' : '' ?>"
                    autocomplete="new-password"
                >
                <button type="button" class="toggle-password" aria-label="Toggle confirm password visibility" data-target="confirm_password">
                    <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                    </svg>
                </button>
            </div>
            <?php if (isset($errors['confirm_password'])): ?>
                <span class="error-msg"><?= $errors['confirm_password'] ?></span>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn-submit">Create Account</button>
    </form>
</div>

<script>
    // Image preview
    const imageInput = document.getElementById('image');
    const avatarPreview = document.getElementById('avatarPreview');
    const fileName = document.getElementById('fileName');

    imageInput.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        fileName.textContent = file.name;

        const reader = new FileReader();
        reader.onload = (e) => {
            avatarPreview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
        };
        reader.readAsDataURL(file);
    });

    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', () => {
            const targetId = btn.dataset.target;
            const input    = document.getElementById(targetId);
            input.type = input.type === 'password' ? 'text' : 'password';
        });
    });
</script>
<?php require "footer.php" ?>
