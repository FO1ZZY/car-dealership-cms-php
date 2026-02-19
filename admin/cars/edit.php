<?php
require_once __DIR__ . '/../../includes/admin_bootstrap.php';

require_admin();

$id = (int)($_GET['id'] ?? 0);
$hasBrand = table_has_column($pdo, 'cars', 'brand');
$hasYear = table_has_column($pdo, 'cars', 'year');
$hasFeatured = table_has_column($pdo, 'cars', 'is_featured');
$hasFeaturedOrder = table_has_column($pdo, 'cars', 'featured_order');
$hasActive = table_has_column($pdo, 'cars', 'is_active');

$stmt = $pdo->prepare('SELECT * FROM cars WHERE id = ?');
$stmt->execute([$id]);
$car = $stmt->fetch();
if (!$car) {
    redirect('/admin/cars/index.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $error = 'Неверный CSRF-токен.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $price = trim($_POST['price'] ?? '0');
        $description = trim($_POST['description'] ?? '');
        $brand = trim($_POST['brand'] ?? '');
        $year = (int)($_POST['year'] ?? 0);
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
        $featuredOrder = (int)($_POST['featured_order'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if (!$name || !$slug) {
            $error = 'Название и slug обязательны.';
        } else {
            $imageName = $car['image'];
            if (!empty($_FILES['image']['name'])) {
                $newImage = upload_car_image($_FILES['image'], __DIR__ . '/../../uploads/cars');
                if (!$newImage) {
                    $error = 'Неверный формат изображения.';
                } else {
                    if ($imageName) {
                        delete_file_safe(__DIR__ . '/../../uploads/cars/' . $imageName);
                    }
                    $imageName = $newImage;
                }
            }
            if (!$error) {
                $sets = ['name = ?', 'slug = ?', 'price = ?', 'image = ?', 'description = ?'];
                $params = [$name, $slug, $price, $imageName, $description];
                if ($hasBrand) {
                    $sets[] = 'brand = ?';
                    $params[] = $brand ?: null;
                }
                if ($hasYear) {
                    $sets[] = 'year = ?';
                    $params[] = $year ?: null;
                }
                if ($hasFeatured) {
                    $sets[] = 'is_featured = ?';
                    $params[] = $isFeatured;
                }
                if ($hasFeaturedOrder) {
                    $sets[] = 'featured_order = ?';
                    $params[] = $featuredOrder;
                }
                if ($hasActive) {
                    $sets[] = 'is_active = ?';
                    $params[] = $isActive;
                }
                $params[] = $id;
                $stmt = $pdo->prepare('UPDATE cars SET ' . implode(', ', $sets) . ' WHERE id = ?');
                try {
                    $stmt->execute($params);
                    redirect('/admin/cars/index.php');
                } catch (PDOException $e) {
                    $error = 'Slug должен быть уникальным.';
                }
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
    <title>Редактировать автомобиль</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<main>
    <div class="container" style="max-width: 760px;">
        <h1>Редактировать автомобиль</h1>
        <?php if ($error): ?>
            <div class="contact-form" style="border-color: #ff6b6b;"><?= e($error) ?></div>
        <?php endif; ?>
        <div class="contact-form">
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
                <div class="form-group">
                    <label>Название</label>
                    <input type="text" name="name" value="<?= e($car['name']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Slug</label>
                    <input type="text" name="slug" value="<?= e($car['slug']) ?>" required>
                </div>
                <?php if ($hasBrand): ?>
                <div class="form-group">
                    <label>Марка</label>
                    <input type="text" name="brand" value="<?= e($car['brand'] ?? '') ?>">
                </div>
                <?php endif; ?>
                <?php if ($hasYear): ?>
                <div class="form-group">
                    <label>Год</label>
                    <input type="number" name="year" min="1950" max="2100" value="<?= e((string)($car['year'] ?? '')) ?>">
                </div>
                <?php endif; ?>
                <div class="form-group">
                    <label>Цена</label>
                    <input type="number" step="0.01" name="price" value="<?= e($car['price']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Описание</label>
                    <textarea name="description" rows="5"><?= e($car['description'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label>Новое изображение (jpg/png/webp)</label>
                    <input type="file" name="image" accept="image/jpeg,image/png,image/webp">
                </div>
                <?php if ($car['image']): ?>
                    <div class="form-group">
                        <label>Текущее изображение</label>
                        <img src="/uploads/cars/<?= e($car['image']) ?>" alt="" style="max-width: 280px; border-radius: 12px;">
                    </div>
                <?php endif; ?>
                <?php if ($hasFeatured): ?>
                <div class="form-group">
                    <label><input type="checkbox" name="is_featured" <?= !empty($car['is_featured']) ? 'checked' : '' ?>> Показывать на главной</label>
                </div>
                <?php endif; ?>
                <?php if ($hasFeaturedOrder): ?>
                <div class="form-group">
                    <label>Порядок в блоке «Популярные модели»</label>
                    <input type="number" name="featured_order" value="<?= e((string)($car['featured_order'] ?? 0)) ?>">
                </div>
                <?php endif; ?>
                <?php if ($hasActive): ?>
                <div class="form-group">
                    <label><input type="checkbox" name="is_active" <?= !empty($car['is_active']) ? 'checked' : '' ?>> Активен (показывать в каталоге)</label>
                </div>
                <?php endif; ?>
                <button type="submit" class="btn btn-primary">Сохранить</button>
                <a class="btn" href="/admin/cars/index.php">Отмена</a>
            </form>
        </div>
    </div>
</main>
</body>
</html>
