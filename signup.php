<?php
require 'db.php';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $username  = trim($_POST['username'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $password  = $_POST['password'] ?? '';

    if ($full_name === '' || $username === '' || $email === '' || $password === '') {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $conn->prepare('INSERT INTO users (full_name, username, email, password_hash) VALUES (?, ?, ?, ?)');
            $stmt->bind_param('ssss', $full_name, $username, $email, $hash);
            $stmt->execute();
            $success = 'Account created. You can now log in.';
        } catch (mysqli_sql_exception $e) {
            $error = ($e->getCode() === 1062) ? 'That username is already taken.' : 'Could not create account.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Sign up</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="box">
    <h1>Sign up</h1>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="ok"><?= htmlspecialchars($success) ?></div><?php endif; ?>
    <form method="post" action="signup.php">
        <label>Full name <input type="text" name="full_name" required></label>
        <label>Username <input type="text" name="username" required></label>
        <label>Email <input type="email" name="email" required></label>
        <label>Password <input type="password" name="password" required></label>
        <button type="submit">Create account</button>
    </form>
    <p class="alt">Already registered? <a href="login.php">Log in</a></p>
</div>
</body>
</html>
