<?php
// var_dump($_POST);
if (isset($_POST['username'])) {
    echo $_POST['username'];
} else {
    echo "Redirecting to form page in 3 seconds...";
    header("Refresh: 3;URl=index.php");
    // header("Location: form.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #ffffff;
            padding: 3rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1 {
            color: #28a745;
            margin-bottom: 1rem;
        }
        p {
            color: #555;
            font-size: 1.2rem;
        }
        .user-name {
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Registration Done!</h1>
    <p>Welcome, <span class="user-name"><?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : 'Guest'; ?></span>!</p>
    <p>Your account has been successfully created.</p>
</div>

</body>
</html>
