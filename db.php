<?php
// Database connection settings. Edit DB_PASS on the server after cloning.
$DB_HOST = 'localhost';
$DB_NAME = 'CAT2';
$DB_USER = 'cat2user';
$DB_PASS = 'CHANGE_ME';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());
    die('Database connection failed. Check db.php settings.');
}
