<?php
require_once __DIR__ . '/../../includes/admin_bootstrap.php';

require_admin();

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
$leads = $pdo->query('SELECT ' . implode(', ', $selectCols) . ' FROM leads LEFT JOIN cars ON cars.id = leads.car_id ORDER BY leads.id DESC')->fetchAll();
$csrf = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заявки</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<main>
    <div class="container">
        <h1>Заявки</h1>
        <div style="margin-bottom:16px;">
            <a class="btn" href="/admin/dashboard.php">Назад</a>
        </div>
        <div class="contact-form">
            <table class="specs-table">
                <tr><th>ID</th><th>Имя</th><th>Телефон</th><th>Email</th><th>Тема</th><th>Авто</th><th>Дата</th><th>Действия</th></tr>
                <?php foreach ($leads as $lead): ?>
                    <?php $isNew = $hasViewed ? empty($lead['is_viewed']) : false; ?>
                    <tr style="<?= $isNew ? 'background:#1f2f1f;border:1px solid #2ecc71;' : '' ?>">
                        <td><?= (int)$lead['id'] ?></td>
                        <td><?= e($lead['name']) ?></td>
                        <td><?= e($lead['phone']) ?></td>
                        <td><?= $hasEmail ? e($lead['email'] ?? '-') : '-' ?></td>
                        <td><?= $hasTopic ? e($lead['topic'] ?? '-') : '-' ?></td>
                        <td><?= e($lead['car_name'] ?? '-') ?></td>
                        <td><?= e($lead['created_at']) ?></td>
                        <td>
                            <a class="btn btn-ghost" href="/admin/leads/view.php?id=<?= (int)$lead['id'] ?>"><?= $isNew ? 'Новая' : 'Просмотр' ?></a>
                            <form method="post" action="/admin/leads/delete.php" style="display:inline-block;">
                                <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
                                <input type="hidden" name="id" value="<?= (int)$lead['id'] ?>">
                                <button type="submit" class="btn btn-secondary">Удалить</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</main>
</body>
</html>
