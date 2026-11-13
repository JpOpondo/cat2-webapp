<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Welcome</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="box">
    <h1>Welcome, <?= htmlspecialchars($_SESSION['full_name']) ?></h1>
    <div class="ok">Login successful. You are logged in as <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>.</div>
    <p><a href="logout.php">Log out</a></p>
</div>
</body>
</html>
