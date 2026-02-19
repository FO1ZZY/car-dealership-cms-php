<?php
require_once __DIR__ . '/../../includes/admin_bootstrap.php';

require_admin();

$error = '';
$inviteLink = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $error = 'Неверный CSRF-токен.';
    } else {
        $email = trim($_POST['email'] ?? '');
        if (!$email) {
            $error = 'Email обязателен.';
        } else {
            $hasInvite = table_has_column($pdo, 'admins', 'invite_token') && table_has_column($pdo, 'admins', 'invite_expires_at');
            $hasPassword = table_has_column($pdo, 'admins', 'password_hash');
            try {
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
                } else {
                    $stmt = $pdo->prepare('INSERT INTO admins (email) VALUES (?)');
                    $stmt->execute([$email]);
                }
            } catch (PDOException $e) {
                $error = 'Email уже используется.';
            }
        }
    }
}
$csrf = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создать администратора</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<main>
    <div class="container" style="max-width: 640px;">
        <h1>Создать администратора</h1>
        <?php if ($error): ?>
            <div class="contact-form" style="border-color: #ff6b6b;"><?= e($error) ?></div>
        <?php endif; ?>
        <?php if ($inviteLink): ?>
            <div class="contact-form">
                Ссылка для задания пароля (скопируй и отправь):<br>
                <strong><?= e($inviteLink) ?></strong>
            </div>
        <?php endif; ?>
        <div class="contact-form">
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div style="font-size:0.9rem; opacity:0.8; margin-bottom:12px;">
                    Пароль не нужен. Администратор задаст его при первом входе.
                </div>
                <button type="submit" class="btn btn-primary">Создать</button>
                <a class="btn" href="/admin/admins/index.php">Отмена</a>
            </form>
        </div>
    </div>
</main>
</body>
</html>
