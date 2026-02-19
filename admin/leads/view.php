<?php
require_once __DIR__ . '/../../includes/admin_bootstrap.php';

require_admin();

$id = (int)($_GET['id'] ?? 0);
$hasEmail = table_has_column($pdo, 'leads', 'email');
$hasTopic = table_has_column($pdo, 'leads', 'topic');
$hasViewed = table_has_column($pdo, 'leads', 'is_viewed');

$selectCols = ['leads.id', 'leads.name', 'leads.phone', 'leads.message', 'leads.car_id', 'leads.created_at', 'cars.name AS car_name'];
if ($hasEmail) {
    $selectCols[] = 'leads.email';
}
if ($hasTopic) {
    $selectCols[] = 'leads.topic';
}
if ($hasViewed) {
    $selectCols[] = 'leads.is_viewed';
}

$stmt = $pdo->prepare('SELECT ' . implode(', ', $selectCols) . ' FROM leads LEFT JOIN cars ON cars.id = leads.car_id WHERE leads.id = ?');
$stmt->execute([$id]);
$lead = $stmt->fetch();
if (!$lead) {
    redirect('/admin/leads/index.php');
}

// mark as viewed
if ($hasViewed) {
    $pdo->prepare('UPDATE leads SET is_viewed = 1, viewed_at = NOW() WHERE id = ?')->execute([$id]);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заявка #<?= (int)$lead['id'] ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<main>
    <div class="container" style="max-width: 720px;">
        <h1>Заявка #<?= (int)$lead['id'] ?></h1>
        <div class="contact-form">
            <p><strong>Имя:</strong> <?= e($lead['name']) ?></p>
            <p><strong>Телефон:</strong> <?= e($lead['phone']) ?></p>
            <p><strong>Email:</strong> <?= e($lead['email'] ?? '-') ?></p>
            <p><strong>Тема:</strong> <?= e($lead['topic'] ?? '-') ?></p>
            <p><strong>Автомобиль:</strong> <?= e($lead['car_name'] ?? '-') ?></p>
            <p><strong>Сообщение:</strong> <?= e($lead['message'] ?? '-') ?></p>
            <p><strong>Дата:</strong> <?= e($lead['created_at']) ?></p>
        </div>
        <div style="margin-top:16px;">
            <a class="btn" href="/admin/leads/index.php">Назад</a>
        </div>
    </div>
</main>
</body>
</html>
