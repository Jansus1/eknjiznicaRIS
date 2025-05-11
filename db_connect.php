<?php
try {
    $conn = new PDO(
        "sqlsrv:server = tcp:{$_ENV['DB_HOST']},1433; Database = {$_ENV['DB_NAME']}",
        $_ENV['DB_USER'],
        $_ENV['DB_PASSWORD']
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>