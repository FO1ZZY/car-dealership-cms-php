<?php
require_once __DIR__ . '/../../includes/admin_bootstrap.php';

require_admin();

$hasFeatured = table_has_column($pdo, 'cars', 'is_featured');
$hasActive = table_has_column($pdo, 'cars', 'is_active');
$selectCols = ['id', 'name', 'slug', 'price', 'image', 'created_at'];
if ($hasFeatured) {
    $selectCols[] = 'is_featured';
}
if ($hasActive) {
    $selectCols[] = 'is_active';
}
$cars = $pdo->query('SELECT ' . implode(', ', $selectCols) . ' FROM cars ORDER BY id DESC')->fetchAll();
$csrf = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Автомобили</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<main>
    <div class="container">
        <h1>Автомобили</h1>
        <div style="margin-bottom:16px;">
            <a class="btn btn-primary" href="/admin/cars/create.php">Добавить автомобиль</a>
            <a class="btn" href="/admin/dashboard.php">Назад</a>
        </div>
        <div class="contact-form">
            <table class="specs-table">
                <tr><th>ID</th><th>Название</th><th>Slug</th><th>Цена</th><th>Изображение</th><th>Главная</th><th>Активен</th><th>Действия</th></tr>
                <?php foreach ($cars as $car): ?>
                    <tr>
                        <td><?= (int)$car['id'] ?></td>
                        <td><?= e($car['name']) ?></td>
                        <td><?= e($car['slug']) ?></td>
                        <td><?= e($car['price']) ?></td>
                        <td><?= $car['image'] ? e($car['image']) : '-' ?></td>
                        <td><?= $hasFeatured ? (!empty($car['is_featured']) ? 'Да' : 'Нет') : '—' ?></td>
                        <td><?= $hasActive ? (!empty($car['is_active']) ? 'Да' : 'Нет') : '—' ?></td>
                        <td>
                            <a class="btn btn-ghost" href="/admin/cars/edit.php?id=<?= (int)$car['id'] ?>">Редактировать</a>
                            <form method="post" action="/admin/cars/delete.php" style="display:inline-block;">
                                <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
                                <input type="hidden" name="id" value="<?= (int)$car['id'] ?>">
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
