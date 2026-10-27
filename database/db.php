<?php
// File: database/db.php
function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $host     = 'localhost';
        $dbname   = 'news_blog_db'; // Tên DB từ Bài 4
        $username = 'root';         // Mặc định của XAMPP
        $password = '';             // Mặc định XAMPP
        $charset  = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Bật báo lỗi rõ ràng
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,     // Trả về mảng kết hợp
            PDO::ATTR_EMULATE_PREPARES   => false,                  // Dùng Prepared Statement xịn
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