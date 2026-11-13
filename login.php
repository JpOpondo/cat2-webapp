<?php
session_start();
require 'db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare('SELECT id, full_name, password_hash FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['username']  = $username;
        $_SESSION['full_name'] = $user['full_name'];
        header('Location: welcome.php');
        exit;
    }
    $error = 'Invalid username or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Log in</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="box">
    <h1>Log in</h1>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="post" action="login.php">
        <label>Username <input type="text" name="username" required></label>
        <label>Password <input type="password" name="password" required></label>
        <button type="submit">Log in</button>
    </form>
    <p class="alt">No account? <a href="signup.php">Sign up</a></p>
</div>
</body>
</html>
