<?php
// File: admin_comments.php
require_once 'repositories/CommentRepository.php';

// Xử lý POST đổi trạng thái (POST-Redirect-GET)[cite: 4, 9, 17]
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $status = $_POST['status'] ?? 'pending';
    if ($id > 0) {
        updateCommentStatus($id, $status);
    }
    header('Location: admin_comments.php');
    exit;
}

$comments = findAllComments();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Bình luận - Admin</title>
    <style>table, th, td { border: 1px solid #ccc; border-collapse: collapse; padding: 8px; }</style>
</head>
<body>
    <h2>📋 QUẢN LÝ BÌNH LUẬN DỌC GIẢ</h2>
    <a href="index.php">← Về Trang chủ</a><br><br>
    <table>
        <tr>
            <th>ID</th>
            <th>Bài viết</th>
            <th>Người gửi</th>
            <th>Nội dung</th>
            <th>Trạng thái</th>
            <th>Hành động (POST)</th>
        </tr>
        <?php foreach ($comments as $cm): ?>
        <tr>
            <td><?= $cm['id'] ?></td>
            <td><?= htmlspecialchars($cm['post_title']) ?></td>
            <td><?= htmlspecialchars($cm['author_name']) ?> (<?= htmlspecialchars($cm['author_email']) ?>)</td>
            <td><?= htmlspecialchars($cm['content']) ?></td>
            <td><b><?= $cm['status'] ?></b></td>
            <td>
                <!-- Đổi trạng thái Duyệt bằng POST[cite: 9, 17] -->
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $cm['id'] ?>">
                    <input type="hidden" name="status" value="approved">
                    <button type="submit">Duyệt</button>
                </form>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $cm['id'] ?>">
                    <input type="hidden" name="status" value="hidden">
                    <button type="submit">Ẩn</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>