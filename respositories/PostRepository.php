<?php
// File: repositories/PostRepository.php
require_once __DIR__ . '/../database/db.php';

function findAllPostsAdmin(): array {
    $sql = "SELECT p.*, c.name AS category_name, u.full_name AS author_name 
            FROM posts p 
            JOIN categories c ON p.category_id = c.id 
            JOIN users u ON p.author_id = u.id 
            ORDER BY p.id DESC";
    return db()->query($sql)->fetchAll();
}

function findPostById(int $id): ?array {
    $stmt = db()->prepare("SELECT * FROM posts WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $res = $stmt->fetch();
    return $res ?: null;
}

// Thêm bài viết dùng Prepared Statement[cite: 7, 10]
function savePost(array $data, ?int $id = null): bool {
    if ($id) {
        $sql = "UPDATE posts SET category_id = :cat_id, title = :title, slug = :slug, summary = :summary, content = :content, status = :status WHERE id = :id";
        $data['id'] = $id;
    } else {
        $sql = "INSERT INTO posts (category_id, author_id, title, slug, summary, content, status, published_at) 
                VALUES (:cat_id, 3, :title, :slug, :summary, :content, :status, NOW())";
    }
    $stmt = db()->prepare($sql);
    return $stmt->execute([
        'cat_id'  => (int)$data['category_id'],
        'title'   => trim($data['title']),
        'slug'    => trim($data['slug']),
        'summary' => trim($data['summary']),
        'content' => trim($data['content']),
        'status'  => $data['status']
    ] + ($id ? ['id' => $id] : []));
}

function deletePost(int $id): bool {
    $stmt = db()->prepare("DELETE FROM posts WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}
?>