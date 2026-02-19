<?php
require_once __DIR__ . '/../../includes/admin_bootstrap.php';

require_admin();

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM reviews WHERE id = ?');
$stmt->execute([$id]);
$review = $stmt->fetch();
if (!$review) {
    redirect('/admin/reviews/index.php');
}

$cars = $pdo->query('SELECT id, name FROM cars ORDER BY name')->fetchAll();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $error = 'Неверный CSRF-токен.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $text = trim($_POST['text'] ?? '');
        $rating = (int)($_POST['rating'] ?? 5);
        $carId = (int)($_POST['car_id'] ?? 0);
        $isApproved = isset($_POST['is_approved']) ? 1 : 0;
        $removePhoto = isset($_POST['remove_photo']);

        if (!$name || !$text) {
            $error = 'Имя и текст обязательны.';
        } else {
            $photo = $review['photo'];
            if ($removePhoto && $photo) {
                delete_file_safe(__DIR__ . '/../../uploads/reviews/' . $photo);
                $photo = null;
            }
            if (!empty($_FILES['photo']['name'])) {
                $newPhoto = upload_review_image($_FILES['photo'], __DIR__ . '/../../uploads/reviews');
                if ($newPhoto) {
                    if ($photo) {
                        delete_file_safe(__DIR__ . '/../../uploads/reviews/' . $photo);
                    }
                    $photo = $newPhoto;
                } else {
                    $error = 'Неверный формат изображения.';
                }
            }
            if (!$error) {
                $stmt = $pdo->prepare('UPDATE reviews SET name = ?, text = ?, rating = ?, car_id = ?, photo = ?, is_approved = ? WHERE id = ?');
                $stmt->execute([$name, $text, $rating, $carId > 0 ? $carId : null, $photo, $isApproved, $id]);
                redirect('/admin/reviews/index.php');
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
    <title>Редактировать отзыв</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<main>
    <div class="container" style="max-width: 760px;">
        <h1>Редактировать отзыв</h1>
        <?php if ($error): ?>
            <div class="contact-form" style="border-color: #ff6b6b;"><?= e($error) ?></div>
        <?php endif; ?>
        <div class="contact-form">
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
                <div class="form-group">
                    <label>Имя</label>
                    <input type="text" name="name" value="<?= e($review['name']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Автомобиль</label>
                    <select name="car_id">
                        <option value="0">Не выбрано</option>
                        <?php foreach ($cars as $car): ?>
                            <option value="<?= (int)$car['id'] ?>" <?= (int)$review['car_id'] === (int)$car['id'] ? 'selected' : '' ?>>
                                <?= e($car['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Оценка</label>
                    <select name="rating">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <option value="<?= $i ?>" <?= (int)$review['rating'] === $i ? 'selected' : '' ?>><?= $i ?>/5</option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Текст</label>
                    <textarea name="text" rows="6" required><?= e($review['text']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Фото (jpg/png/webp)</label>
                    <input type="file" name="photo" accept="image/jpeg,image/png,image/webp">
                    <?php if (!empty($review['photo'])): ?>
                        <div style="margin-top:8px;">
                            <img src="/uploads/reviews/<?= e($review['photo']) ?>" alt="Фото" style="max-width:200px;border-radius:12px;">
                            <label style="display:block;margin-top:8px;">
                                <input type="checkbox" name="remove_photo"> Удалить фото
                            </label>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label><input type="checkbox" name="is_approved" <?= $review['is_approved'] ? 'checked' : '' ?>> Одобрен</label>
                </div>
                <button type="submit" class="btn btn-primary">Сохранить</button>
                <a class="btn" href="/admin/reviews/index.php">Отмена</a>
            </form>
        </div>
    </div>
</main>
</body>
</html>
