<?php
require_once __DIR__ . '/../../includes/admin_bootstrap.php';

require_admin();

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT id, email FROM admins WHERE id = ?');
$stmt->execute([$id]);
$admin = $stmt->fetch();
if (!$admin) {
    redirect('/admin/admins/index.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $error = 'Неверный CSRF-токен.';
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        if (!$email) {
            $error = 'Email обязателен.';
        } else {
            if ($password) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('UPDATE admins SET email = ?, password_hash = ? WHERE id = ?');
                $stmt->execute([$email, $hash, $id]);
            } else {
                $stmt = $pdo->prepare('UPDATE admins SET email = ? WHERE id = ?');
                $stmt->execute([$email, $id]);
            }
            redirect('/admin/admins/index.php');
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
    <title>Редактировать администратора</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<main>
    <div class="container" style="max-width: 640px;">
        <h1>Редактировать администратора</h1>
        <?php if ($error): ?>
            <div class="contact-form" style="border-color: #ff6b6b;"><?= e($error) ?></div>
        <?php endif; ?>
        <div class="contact-form">
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?= e($admin['email']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Новый пароль (необязательно)</label>
                    <input type="password" name="password">
                </div>
                <button type="submit" class="btn btn-primary">Сохранить</button>
                <a class="btn" href="/admin/admins/index.php">Отмена</a>
            </form>
        </div>
    </div>
</main>
</body>
</html>