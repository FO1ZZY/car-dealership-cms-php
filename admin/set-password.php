<?php
require_once __DIR__ . '/../includes/admin_bootstrap.php';

if (is_admin()) {
    redirect('/admin/dashboard.php');
}

$success = '';
$error = '';

$token = $_GET['token'] ?? '';
$pendingId = (int)($_SESSION['pending_admin_id'] ?? 0);
$admin = null;

if ($token) {
    $stmt = $pdo->prepare('SELECT id, invite_expires_at FROM admins WHERE invite_token = ? LIMIT 1');
    $stmt->execute([$token]);
    $admin = $stmt->fetch();
    if (!$admin) {
        $error = 'Ссылка недействительна.';
    } elseif (!empty($admin['invite_expires_at']) && strtotime($admin['invite_expires_at']) < time()) {
        $error = 'Срок действия ссылки истёк.';
    }
} elseif ($pendingId > 0) {
    $stmt = $pdo->prepare('SELECT id FROM admins WHERE id = ? LIMIT 1');
    $stmt->execute([$pendingId]);
    $admin = $stmt->fetch();
    if (!$admin) {
        $error = 'Администратор не найден.';
    }
} else {
    $error = 'Ссылка недействительна.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error) {
    $csrfOk = verify_csrf_token($_POST['csrf_token'] ?? null);
    // Fallback for first-time setup if session cookies are blocked
    if (!$csrfOk && $token && $admin) {
        $csrfOk = true;
    }
    if (!$csrfOk) {
        $error = 'Неверный CSRF-токен.';
    } else {
        $password = $_POST['password'] ?? '';
        if (!$password) {
            $error = 'Введите пароль.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('UPDATE admins SET password_hash = ?, invite_token = NULL, invite_expires_at = NULL WHERE id = ?');
            $stmt->execute([$hash, (int)$admin['id']]);
            $_SESSION['admin_id'] = (int)$admin['id'];
            $_SESSION['admin_email'] = $_SESSION['pending_admin_email'] ?? '';
            unset($_SESSION['pending_admin_id'], $_SESSION['pending_admin_email']);
            $success = 'Пароль задан. Теперь можно войти.';
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
    <title>Задать пароль</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <main>
        <div class="container" style="max-width: 560px;">
            <h1>Задать пароль</h1>
            <?php if ($error): ?>
                <div class="contact-form" style="border-color: #ff6b6b;"><?= e($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="contact-form"><?= e($success) ?></div>
            <?php endif; ?>
            <?php if (!$success): ?>
                <div class="contact-form">
                    <form method="post">
                        <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
                        <div class="form-group">
                            <label>Новый пароль</label>
                            <input type="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Сохранить пароль</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
