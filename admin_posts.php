<?php
// File: admin_posts.php
require_once 'repositories/PostRepository.php';
require_once 'repositories/CategoryRepository.php';

$editPost = null;
if (isset($_GET['edit_id'])) {
    $editPost = findPostById((int)$_GET['edit_id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'save') {
        $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
        savePost($_POST, $id);
    } elseif ($action === 'delete') {
        deletePost((int)$_POST['id']);
    }
    header('Location: admin_posts.php');
    exit;
}

$posts = findAllPostsAdmin();
$categories = findAllCategories();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Bài viết</title>
    <style>table, th, td { border: 1px solid #ccc; border-collapse: collapse; padding: 8px; }</style>
</head>
<body>
    <h2>📝 QUẢN LÝ BÀI VIẾT (CRUD)</h2>
    <a href="index.php">← Về Trang chủ</a><br><br>

    <h3><?= $editPost ? 'Sửa Bài Viết' : 'Tạo Bài Viết Mới' ?></h3>
    <form method="POST">
        <input type="hidden" name="action" value="save">
        <?php if ($editPost): ?>
            <input type="hidden" name="id" value="<?= $editPost['id'] ?>">
        <?php endif; ?>
        Chuyên mục: 
        <select name="category_id" required>
            <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>" <?= ($editPost && $editPost['category_id'] == $c['id']) ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach; ?>
        </select><br><br>
        Tiêu đề: <input type="text" name="title" value="<?= htmlspecialchars($editPost['title'] ?? '') ?>" required size="50"><br><br>
        Slug (URL): <input type="text" name="slug" value="<?= htmlspecialchars($editPost['slug'] ?? '') ?>" required size="50"><br><br>
        Tóm tắt: <br><textarea name="summary" cols="60" rows="3"><?= htmlspecialchars($editPost['summary'] ?? '') ?></textarea><br><br>
        Nội dung: <br><textarea name="content" cols="60" rows="6"><?= htmlspecialchars($editPost['content'] ?? '') ?></textarea><br><br>
        Trạng thái: 
        <select name="status">
            <option value="draft" <?= ($editPost && $editPost['status'] == 'draft') ? 'selected' : '' ?>>Bản nháp</option>
            <option value="published" <?= ($editPost && $editPost['status'] == 'published') ? 'selected' : '' ?>>Xuất bản</option>
        </select><br><br>
        <button type="submit">Lưu Bài Viết</button>
    </form>
    <hr>

    <table>
        <tr>
            <th>ID</th>
            <th>Tiêu đề</th>
            <th>Chuyên mục</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
        <?php foreach ($posts as $p): ?>
        <tr>
            <td><?= $p['id'] ?></td>
            <td><?= htmlspecialchars($p['title']) ?></td>