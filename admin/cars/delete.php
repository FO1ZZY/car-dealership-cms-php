<?php
require_once __DIR__ . '/../../includes/admin_bootstrap.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/admin/cars/index.php');
}

if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
    redirect('/admin/cars/index.php');
}

$id = (int)($_POST['id'] ?? 0);
if ($id > 0) {
    $stmt = $pdo->prepare('SELECT image FROM cars WHERE id = ?');
    $stmt->execute([$id]);
    $car = $stmt->fetch();

    $stmt = $pdo->prepare('DELETE FROM cars WHERE id = ?');
    $stmt->execute([$id]);

    if ($car && $car['image']) {
        delete_file_safe(__DIR__ . '/../../uploads/cars/' . $car['image']);
    }
}

redirect('/admin/cars/index.php');