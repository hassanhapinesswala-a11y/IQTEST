<?php
// config.php
// DB credentials (use the ones you provided)
$DB_HOST = 'localhost';
$DB_NAME = 'dbsa7aujqiiddy';
$DB_USER = 'uyhezup6l0hgf';
$DB_PASS = 'pr634bpk3knb';
 
try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    // friendly error for development
    die("Database connection failed: " . $e->getMessage());
}
?>
 
 
 
 
 
 
