<?php
require_once __DIR__ . '/../../includes/admin_bootstrap.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/admin/leads/index.php');
}

if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
    redirect('/admin/leads/index.php');
}

$id = (int)($_POST['id'] ?? 0);
if ($id > 0) {
    $stmt = $pdo->prepare('DELETE FROM leads WHERE id = ?');
    $stmt->execute([$id]);
}

redirect('/admin/leads/index.php');