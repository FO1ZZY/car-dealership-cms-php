<?php
$root = realpath(__DIR__ . '/..');
$inc = $root . '/includes';
require_once $inc . '/auth.php';
require_once $inc . '/csrf.php';
require_once $inc . '/db.php';
require_once $inc . '/functions.php';

if (is_admin()) {
    redirect('/admin/dashboard.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        $error = 'Неверный CSRF-токен.';
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        if ($email === '') {
            $error = 'Введите email.';
        } elseif ($password === '') {
            $stmt = $pdo->prepare('SELECT id, password_hash FROM admins WHERE email = ? LIMIT 1');
            $stmt->execute([$email]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($admin && empty($admin['password_hash'])) {
                $_SESSION['pending_admin_id'] = (int)$admin['id'];
                $_SESSION['pending_admin_email'] = $email;
                redirect('/admin/set-password.php');
            } else {
                $error = 'Введите пароль.';
            }
        } elseif (login_admin($pdo, $email, $password)) {
            redirect('/admin/dashboard.php');
        } else {
            $error = 'Неверный email или пароль.';
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
    <title>Вход администратора</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <main>
        <div class="container" style="max-width: 560px;">
            <h1>Вход в админ-панель</h1>
            <?php if ($error): ?>
                <div class="contact-form" style="border-color: #ff6b6b;"><?= e($error) ?></div>
            <?php endif; ?>
            <div class="contact-form">
                <form method="post">
                    <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>Пароль</label>
                        <input type="password" name="password">
                    </div>
                    <div style="font-size:0.9rem; opacity:0.8; margin-bottom:12px;">
                        Если пароль ещё не задан, оставьте поле пустым — откроется установка пароля.
                    </div>
                    <button type="submit" class="btn btn-primary">Войти</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>