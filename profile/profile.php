<!-- 

for logout
session_start();
session_unset();
session_regenerate_id();
session_destroy();
  -->

<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: register.php');
    exit;
}

$user = $_SESSION['user'];
$name  = htmlspecialchars($user['name'],  ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8');
$image = htmlspecialchars($user['image'], ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $name ?>'s Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="card profile-card">
    <div class="card-header">
        <div class="avatar-placeholder profile-avatar">
            <?php if ($image): ?>
                <img src="<?= $image ?>" alt="<?= $name ?>">
            <?php else: ?>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                </svg>
            <?php endif; ?>
        </div>
        <h1><?= $name ?></h1>
        <p><?= $email ?></p>
    </div>

    <div class="profile-details">
        <div class="detail-item">
            <span class="detail-label">Full Name</span>
            <span class="detail-value"><?= $name ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Email Address</span>
            <span class="detail-value"><?= $email ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Account Status</span>
            <span class="detail-value status-badge">✓ Active</span>
        </div>
    </div>

    <a href="register.php" class="btn-submit" style="display:block;text-align:center;text-decoration:none;margin-top:1.5rem;">
        ← Back to Register
    </a>
</div>
</body>
</html>
