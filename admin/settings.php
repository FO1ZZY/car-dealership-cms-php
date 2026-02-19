<?php
require_once __DIR__ . '/../includes/admin_bootstrap.php';
require_once __DIR__ . '/../includes/settings.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Неверный CSRF-токен.';
    } else {
        $name = trim($_POST['site_name'] ?? '');
        if ($name === '') {
            $error = 'Введите название сайта.';
        } else {
            settings_set($pdo, 'site_name', $name);
            $success = 'Сохранено.';
        }
    }
}

$siteName = settings_get($pdo, 'site_name', 'Автодрайв');
$page_title = 'Настройки';
include __DIR__ . '/../templates/header.php';
?>

<main class="admin-page">
    <div class="container">
        <h1>Настройки сайта</h1>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php elseif ($success): ?>
            <div class="alert alert-success"><?= e($success) ?></div>
        <?php endif; ?>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= e(generate_csrf_token()) ?>">
            <div class="form-group">
                <label>Название сайта</label>
                <input type="text" name="site_name" value="<?= e($siteName) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a class="btn" href="/admin/dashboard.php">Назад</a>
        </form>
    </div>
</main>

<?php include __DIR__ . '/../templates/footer.php'; ?>