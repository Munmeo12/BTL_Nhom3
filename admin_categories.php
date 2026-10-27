<?php
// File: admin_categories.php
require_once 'repositories/CategoryRepository.php';

$editCategory = null;
if (isset($_GET['edit_id'])) {
    $editCategory = findCategoryById((int)$_GET['edit_id']);
}

// Xử lý Thêm / Sửa / Xóa bằng POST[cite: 9, 17]
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'save') {
        $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
        saveCategory(trim($_POST['name']), trim($_POST['slug']), trim($_POST['description']), $id);
    } elseif ($action === 'delete') {
        deleteCategory((int)$_POST['id']);
    }
    header('Location: admin_categories.php');
    exit;
}

$categories = findAllCategories();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Chuyên mục</title>
    <style>table, th, td { border: 1px solid #ccc; border-collapse: collapse; padding: 8px; }</style>
</head>
<body>
    <h2>📂 QUẢN LÝ CHUYÊN MỤC (CRUD)</h2>
    <a href="index.php">← Về Trang chủ</a><br><br>

    <!-- FORM THÊM / SỬA -->
    <h3><?= $editCategory ? 'Sửa Chuyên Mục' : 'Thêm Chuyên Mục Mới' ?></h3>
    <form method="POST">
        <input type="hidden" name="action" value="save">
        <?php if ($editCategory): ?>
            <input type="hidden" name="id" value="<?= $editCategory['id'] ?>">
        <?php endif; ?>
        Tên chuyên mục: <input type="text" name="name" value="<?= htmlspecialchars($editCategory['name'] ?? '') ?>" required><br><br>
        Slug (URL): <input type="text" name="slug" value="<?= htmlspecialchars($editCategory['slug'] ?? '') ?>" required><br><br>
        Mô tả: <textarea name="description"><?= htmlspecialchars($editCategory['description'] ?? '') ?></textarea><br><br>
        <button type="submit">Lưu Chuyên Mục</button>
    </form>
    <hr>

    <!-- BẢNG DANH SÁCH -->
    <table>
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Slug</th>
            <th>Hành động</th>
        </tr>
        <?php foreach ($categories as $cat): ?>
        <tr>
            <td><?= $cat['id'] ?></td>
            <td><?= htmlspecialchars($cat['name']) ?></td>
            <td><?= htmlspecialchars($cat['slug']) ?></td>
            <td>
                <a href="admin_categories.php?edit_id=<?= $cat['id'] ?>">Sửa</a> | 
                <form method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn xóa?')">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                    <button type="submit">Xóa (POST)</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>