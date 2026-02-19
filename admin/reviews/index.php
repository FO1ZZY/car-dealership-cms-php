<?php
require_once __DIR__ . '/../../includes/admin_bootstrap.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $id = (int)($_POST['id'] ?? 0);
        $approve = isset($_POST['approve']) ? 1 : 0;
        if ($id > 0) {
            $stmt = $pdo->prepare('UPDATE reviews SET is_approved = ? WHERE id = ?');
            $stmt->execute([$approve, $id]);
        }
    }
    redirect('/admin/reviews/index.php');
}

$reviews = $pdo->query('SELECT reviews.*, cars.name AS car_name FROM reviews LEFT JOIN cars ON cars.id = reviews.car_id ORDER BY reviews.id DESC')->fetchAll();
$csrf = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отзывы</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<main>
    <div class="container">
        <h1>Отзывы</h1>
        <div style="margin-bottom:16px;">
            <a class="btn" href="/admin/dashboard.php">Назад</a>
        </div>
        <div class="contact-form">
            <table class="specs-table">
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Авто</th>
                    <th>Оценка</th>
                    <th>Статус</th>
                    <th>Дата</th>
                    <th>Действия</th>
                </tr>
                <?php foreach ($reviews as $review): ?>
                    <tr>
                        <td><?= (int)$review['id'] ?></td>
                        <td><?= e($review['name']) ?></td>
                        <td><?= e($review['car_name'] ?? '-') ?></td>
                        <td><?= (int)$review['rating'] ?>/5</td>
                        <td><?= $review['is_approved'] ? 'Одобрен' : 'На модерации' ?></td>
                        <td><?= e($review['created_at']) ?></td>
                        <td>
                            <form method="post" action="/admin/reviews/index.php" style="display:inline-block;">
                                <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
                                <input type="hidden" name="id" value="<?= (int)$review['id'] ?>">
                                <?php if ($review['is_approved']): ?>
                                    <button type="submit" class="btn btn-secondary">Снять</button>
                                <?php else: ?>
                                    <input type="hidden" name="approve" value="1">
                                    <button type="submit" class="btn btn-primary">Одобрить</button>
                                <?php endif; ?>
                            </form>
                            <a class="btn btn-ghost" href="/admin/reviews/edit.php?id=<?= (int)$review['id'] ?>">Редактировать</a>
                            <form method="post" action="/admin/reviews/delete.php" style="display:inline-block;">
                                <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
                                <input type="hidden" name="id" value="<?= (int)$review['id'] ?>">
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
