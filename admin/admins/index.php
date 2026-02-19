<?php
require_once __DIR__ . '/../../includes/admin_bootstrap.php';

$admins = [];
$hasInvite = false;
$hasPassword = false;
try {
    require_admin();

    $cols = $pdo->query('SHOW COLUMNS FROM admins')->fetchAll(PDO::FETCH_COLUMN, 0);
    $hasInvite = in_array('invite_token', $cols, true);
    $hasPassword = in_array('password_hash', $cols, true);

    $selectCols = ['id', 'email', 'created_at'];
    if ($hasInvite) {
        $selectCols[] = 'invite_token';
    }
    if ($hasPassword) {
        $selectCols[] = 'password_hash';
    }
    $admins = $pdo->query('SELECT ' . implode(', ', $selectCols) . ' FROM admins ORDER BY id DESC')->fetchAll();
} catch (Throwable $e) {
    $admins = [];
}
$csrf = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Администраторы</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<main>
    <div class="container">
        <h1>Администраторы</h1>
        <div style="margin-bottom:16px;">
            <a class="btn btn-primary" href="/admin/admins/create.php">Создать администратора</a>
            <a class="btn" href="/admin/dashboard.php">Назад</a>
        </div>
        <div class="contact-form">
            <table class="specs-table">
                <tr><th>ID</th><th>Email</th><th>Статус</th><th>Создан</th><th>Действия</th></tr>
                <?php foreach ($admins as $admin): ?>
                    <tr>
                        <td><?= (int)$admin['id'] ?></td>
                        <td><?= e($admin['email']) ?></td>
                        <td>
                            <?php if ($hasPassword): ?>
                                <?= empty($admin['password_hash']) ? 'Пароль не задан' : 'Активен' ?>
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>
                        <td><?= e($admin['created_at']) ?></td>
                        <td>
                            <?php if ($hasInvite && !empty($admin['invite_token'])): ?>
                                <div style="margin-bottom:8px; font-size:0.9rem;">Ссылка: /admin/set-password.php?token=<?= e($admin['invite_token']) ?></div>
                            <?php endif; ?>
                            <a class="btn btn-ghost" href="/admin/admins/edit.php?id=<?= (int)$admin['id'] ?>">Редактировать</a>
                            <form method="post" action="/admin/admins/delete.php" style="display:inline-block;">
                                <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
                                <input type="hidden" name="id" value="<?= (int)$admin['id'] ?>">
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
