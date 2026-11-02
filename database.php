<?php
declare(strict_types=1);

$host = 'localhost';
$dbname = 'my_store';
$dbuser = 'root';
$dbpass = ''; // Sesuaikan jika MySQL kamu memakai password.

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $dbuser,
        $dbpass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    exit('Koneksi database gagal. Periksa Apache/MySQL, database my_store, dan config/database.php.');
}
