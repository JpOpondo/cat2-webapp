<?php
// A tiny "function": takes input, returns output, keeps no state.
header('Content-Type: application/json');
$name = $_GET['name'] ?? 'world';
echo json_encode([
    'message' => "Hello, $name",
    'served_by' => gethostname(),
    'time' => date('c'),
]);
