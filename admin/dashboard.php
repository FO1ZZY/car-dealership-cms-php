<?php
require_once __DIR__ . '/../includes/admin_bootstrap.php';

$counts = [
    'cars' => (int)$pdo->query('SELECT COUNT(*) FROM cars')->fetchColumn(),
    'leads' => (int)$pdo->query('SELECT COUNT(*) FROM leads')->fetchColumn(),
    'reviews' => (int)$pdo->query('SELECT COUNT(*) FROM reviews')->fetchColumn(),
    'admins' => (int)$pdo->query('SELECT COUNT(*) FROM admins')->fetchColumn(),
];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <main>
        <div class="container">
            <h1>Админ-панель</h1>
            <div class="cars-grid">
                <div class="car-card"><h3>Автомобили</h3><div class="car-price"><?= $counts['cars'] ?></div></div>
                <div class="car-card"><h3>Заявки</h3><div class="car-price"><?= $counts['leads'] ?></div></div>
                <div class="car-card"><h3>Отзывы</h3><div class="car-price"><?= $counts['reviews'] ?></div></div>
                <div class="car-card"><h3>Админы</h3><div class="car-price"><?= $counts['admins'] ?></div></div>
            </div>
            <div class="cars-grid" style="margin-top:24px;">
                <a class="btn" href="/admin/cars/index.php">Управление машинами</a>
                <a class="btn" href="/admin/leads/index.php">Заявки</a>
                <a class="btn" href="/admin/reviews/index.php">Отзывы</a>
                <a class="btn" href="/admin/admins/index.php">Админы</a>
                <a class="btn" href="/admin/settings.php">Настройки</a>
                <a class="btn btn-ghost" href="/admin/logout.php">Выйти</a>
            </div>
        </div>
    </main>
</body>
</html>