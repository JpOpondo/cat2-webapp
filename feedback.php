<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$error = '';
$success = isset($_GET['sent']) ? 'Feedback submitted.' : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($subject === '' || $message === '') {
        $error = 'Subject and message are required.';
    } elseif (strlen($subject) > 100) {
        $error = 'Subject must be 100 characters or fewer.';
    } else {
        $stmt = $conn->prepare('INSERT INTO feedback (username, subject, message) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $_SESSION['username'], $subject, $message);
        $stmt->execute();
        header('Location: feedback.php?sent=1');
        exit;
    }
}

$rows = $conn->query('SELECT id, username, subject, message, created_at FROM feedback ORDER BY id DESC');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Feedback</title>
<link rel="stylesheet" href="style.css">
<style>
    .wide { max-width: 760px; }
    textarea { width: 100%; padding: 9px; margin-top: 4px; box-sizing: border-box; border: 1px solid #bbb; border-radius: 4px; font-family: inherit; }
    table { width: 100%; border-collapse: collapse; margin-top: 12px; font-size: .9rem; }
    th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; vertical-align: top; }
    th { background: #eef1f6; }
</style>
</head>
<body>
<div class="box wide">
    <h1>Feedback</h1>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="ok"><?= htmlspecialchars($success) ?></div><?php endif; ?>

    <form method="post" action="feedback.php">
        <label>Subject <input type="text" name="subject" maxlength="100" required></label>
        <label>Message <textarea name="message" rows="4" required></textarea></label>
        <button type="submit">Submit feedback</button>
    </form>

    <h2>Submitted feedback</h2>
    <table>
        <tr><th>ID</th><th>User</th><th>Subject</th><th>Message</th><th>Submitted</th></tr>
        <?php while ($row = $rows->fetch_assoc()): ?>
        <tr>
            <td><?= (int)$row['id'] ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['subject']) ?></td>
            <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
            <td><?= htmlspecialchars($row['created_at']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <p class="alt"><a href="welcome.php">Back</a> | <a href="logout.php">Log out</a></p>
</div>
</body>
</html>
