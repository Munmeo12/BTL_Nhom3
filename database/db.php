<?php
// File: database/db.php
function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        // Thay 'localhost' bằng '127.0.0.1' để ép dùng IPv4
        $host = '127.0.0.1;port=3307';
        $dbname   = 'news_blog_db';
        $username = 'root';
        $password = ''; // Mặc định XAMPP để trống
        $charset  = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,[cite: 5]
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,[cite: 5]
            PDO::ATTR_EMULATE_PREPARES   => false,[cite: 5]
        ];

        try {
            $pdo = new PDO($dsn, $username, $password, $options);
        } catch (\PDOException $e) {
            throw new \PDOException("Lỗi kết nối CSDL: " . $e->getMessage(), (int)$e->getCode());
        }
    }
    return $pdo;
}
?>