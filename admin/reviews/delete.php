<?php
require_once __DIR__ . '/../../includes/admin_bootstrap.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/admin/reviews/index.php');
}

if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
    redirect('/admin/reviews/index.php');
}

$id = (int)($_POST['id'] ?? 0);
if ($id > 0) {
    $stmt = $pdo->prepare('SELECT photo FROM reviews WHERE id = ?');
    $stmt->execute([$id]);
    $photo = $stmt->fetchColumn();
    $stmt = $pdo->prepare('DELETE FROM reviews WHERE id = ?');
    $stmt->execute([$id]);
    if ($photo) {
        delete_file_safe(__DIR__ . '/../../uploads/reviews/' . $photo);
    }
}

redirect('/admin/reviews/index.php');
