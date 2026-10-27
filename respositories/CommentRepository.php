<?php
// File: repositories/CommentRepository.php
require_once __DIR__ . '/../database/db.php';

function findAllComments(): array {
    $sql = "SELECT c.*, p.title AS post_title 
            FROM comments c 
            JOIN posts p ON c.post_id = p.id 
            ORDER BY c.created_at DESC";
    return db()->query($sql)->fetchAll();
}

// Xử lý cập nhật trạng thái Duyệt/Ẩn bình luận bằng Prepared Statement[cite: 7]
function updateCommentStatus(int $id, string $status): bool {
    $sql = "UPDATE comments SET status = :status WHERE id = :id";
    $stmt = db()->prepare($sql);
    return $stmt->execute(['status' => $status, 'id' => $id]);
}

// Thêm bình luận mới từ độc giả
function createComment(int $postId, string $name, string $email, string $content): bool {
    $sql = "INSERT INTO comments (post_id, author_name, author_email, content, status) 
            VALUES (:post_id, :name, :email, :content, 'pending')";
    $stmt = db()->prepare($sql);
    return $stmt->execute([
        'post_id' => $postId,
        'name'    => $name,
        'email'   => $email,
        'content' => $content
    ]);
}
?>