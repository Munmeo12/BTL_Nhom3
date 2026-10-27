<?php
// File: index.php
require_once 'database/db.php';

// Phân trang an toàn[cite: 13]
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 3;
$offset = ($page - 1) * $perPage;

$keyword = trim($_GET['kw'] ?? '');
$searchParam = '%' . $keyword . '%';

// Prepared Statement lấy danh sách bài viết[cite: 8]
$sql = "SELECT p.*, c.name AS category_name 
        FROM posts p 
        JOIN categories c ON p.category_id = c.id 
        WHERE p.status = 'published' AND (p.title LIKE :kw OR p.summary LIKE :kw)
        ORDER BY p.published_at DESC 
        LIMIT :limit OFFSET :offset";

$stmt = db()->prepare($sql);
$stmt->bindValue(':kw', $searchParam, PDO::PARAM_STR);
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll();

// Tính tổng trang[cite: 13]
$stmtCount = db()->prepare("SELECT COUNT(*) FROM posts WHERE status = 'published' AND (title LIKE :kw OR summary LIKE :kw)");
$stmtCount->bindValue(':kw', $searchParam, PDO::PARAM_STR);
$stmtCount->execute();
$totalPosts = $stmtCount->fetchColumn();
$totalPages = ceil($totalPosts / $perPage);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang Chủ Tin Tức</title>
</head>
<body>
    <h1>🌐 TIN TỨC & BLOG MỚI NHẤT</h1>
    <nav>
        <a href="admin_posts.php"> Quản lý Bài viết</a> | 
        <a href="admin_categories.php"> Quản lý Chuyên mục</a> | 
        <a href="admin_comments.php"> Quản lý Bình luận</a>
    </nav><br>

    <form method="GET">
        <input type="text" name="kw" value="<?= htmlspecialchars($keyword) ?>" placeholder="Từ khóa tìm kiếm...">
        <button type="submit">Tìm kiếm</button>
    </form><br>

    <?php foreach ($posts as $p): ?>
        <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
            <small>[<?= htmlspecialchars($p['category_name']) ?>]</small>
            <h3><a href="detail.php?id=<?= $p['id'] ?>"><?= htmlspecialchars($p['title']) ?></a></h3>
            <p><?= htmlspecialchars($p['summary']) ?></p>
        </div>
    <?php endforeach; ?>

    <!-- Thanh Phân Trang[cite: 13] -->
    <div>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="index.php?page=<?= $i ?>&kw=<?= urlencode($keyword) ?>">[ <?= $i ?> ]</a>
        <?php endfor; ?>
    </div>
</body>
</html>