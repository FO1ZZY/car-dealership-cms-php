<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

function column_exists(PDO $pdo, string $table, string $column): bool {
    try {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?');
        $stmt->execute([$table, $column]);
        return (int)$stmt->fetchColumn() > 0;
    } catch (Throwable $e) {
        return false;
    }
}

$hasInvite = column_exists($pdo, 'admins', 'invite_token') && column_exists($pdo, 'admins', 'invite_expires_at');
$hasPassword = column_exists($pdo, 'admins', 'password_hash');

$error = '';
$success = '';
$inviteLink = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    if (!$email) {
        $error = 'Введите email.';
    } else {
        try {
            $exists = $pdo->prepare('SELECT id FROM admins WHERE email = ? LIMIT 1');
            $exists->execute([$email]);
            if ($exists->fetchColumn()) {
                $error = 'Такой email уже есть.';
            } else {
                if ($hasInvite) {
                    $token = generate_token(16);
                    $expires = date('Y-m-d H:i:s', time() + 86400 * 7);
                    if ($hasPassword) {
                        $stmt = $pdo->prepare('INSERT INTO admins (email, password_hash, invite_token, invite_expires_at) VALUES (?, NULL, ?, ?)');
                        $stmt->execute([$email, $token, $expires]);
                    } else {
                        $stmt = $pdo->prepare('INSERT INTO admins (email, invite_token, invite_expires_at) VALUES (?, ?, ?)');
                        $stmt->execute([$email, $token, $expires]);
                    }
                    $inviteLink = '/admin/set-password.php?token=' . $token;
                    $success = 'Администратор создан. Ссылка ниже.';
                } else {
                    $stmt = $pdo->prepare('INSERT INTO admins (email) VALUES (?)');
                    $stmt->execute([$email]);
                    $success = 'Администратор создан. Пароль можно задать через /admin/set-password.php (нужны колонки invite_token/invite_expires_at).';
                }
                // self-delete for safety
                @unlink(__FILE__);
            }
        } catch (Throwable $e) {
            $error = 'Ошибка создания. Проверьте БД.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Установка администратора</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<main>
    <div class="container" style="max-width: 640px;">
        <h1>Создать первого администратора</h1>
        <?php if ($error): ?>
            <div class="contact-form" style="border-color:#ff6b6b;"><?= e($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="contact-form"><?= e($success) ?></div>
        <?php endif; ?>
        <?php if ($inviteLink): ?>
            <div class="contact-form">
                Ссылка для задания пароля:<br>
                <strong><?= e($inviteLink) ?></strong>
            </div>
        <?php endif; ?>
        <div class="contact-form">
            <form method="post">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <button type="submit" class="btn btn-primary">Создать</button>
            </form>
        </div>
        <p style="margin-top:12px; opacity:.8;">После создания файл удаляется автоматически.</p>
    </div>
</main>
</body>
</html>
