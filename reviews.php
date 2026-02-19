<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/settings.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/i18n.php';

$site_name = site_name($pdo);
$page_title = t('reviews_title') . ' - ' . $site_name;
$page_description = t('reviews_title') . ' ' . $site_name . ' — реальные истории клиентов.';
$active = 'reviews';

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Неверный CSRF-токен.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $car_id = (int)($_POST['car_id'] ?? 0);
        $rating = (int)($_POST['rating'] ?? 5);
        $text = trim($_POST['text'] ?? '');
        $photo = null;

        if ($name === '' || $text === '') {
            $error = 'Пожалуйста, заполните обязательные поля.';
        } else {
            if (!empty($_FILES['photo']['name'])) {
                $allowed = ['image/jpeg', 'image/png', 'image/webp'];
                $mime = mime_content_type($_FILES['photo']['tmp_name']);
                if (!in_array($mime, $allowed, true)) {
                    $error = 'Неверный формат изображения.';
                } else {
                    $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                    $photo = uniqid('review_', true) . '.' . $ext;
                    move_uploaded_file($_FILES['photo']['tmp_name'], __DIR__ . '/uploads/cars/' . $photo);
                }
            }

            if ($error === '') {
                $stmt = $pdo->prepare('INSERT INTO reviews (name, car_id, rating, text, photo, is_approved) VALUES (?, ?, ?, ?, ?, 0)');
                $stmt->execute([$name, $car_id ?: null, $rating, $text, $photo]);
                $success = 'Спасибо! Ваш отзыв отправлен на модерацию.';
            }
        }
    }
}

$reviews = $pdo->query('SELECT r.*, c.name AS car_name FROM reviews r LEFT JOIN cars c ON c.id = r.car_id WHERE r.is_approved = 1 ORDER BY r.id DESC')->fetchAll();
$cars = $pdo->query('SELECT id, name FROM cars WHERE is_active = 1 ORDER BY name')->fetchAll();

include __DIR__ . '/templates/header.php';
?>

<main>
    <div class="container">
        <h1><?= e(t('reviews_title')) ?></h1>
        <p>Нам доверяют. Вот что говорят клиенты о покупке автомобилей и сервисе:</p>

        <div class="reviews-grid">
            <?php foreach ($reviews as $review): ?>
                <div class="review-card">
                    <div class="review-header">
                        <strong><?= e($review['name']) ?></strong>
                        <span><?= e(str_repeat('★', (int)$review['rating'])) ?></span>
                    </div>
                    <p class="review-car"><?= e($review['car_name'] ?? 'Клиент') ?></p>
                    <p><?= nl2br(e($review['text'])) ?></p>
                    <?php if ($review['photo']): ?>
                        <img src="/uploads/cars/<?= e($review['photo']) ?>" alt="<?= e($review['name']) ?>">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="contact-form">
            <h2><?= e(t('leave_review')) ?></h2>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= e($error) ?></div>
            <?php elseif ($success): ?>
                <div class="alert alert-success"><?= e($success) ?></div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data" id="review-form">
                <input type="hidden" name="csrf_token" value="<?= e(generate_csrf_token()) ?>">
                <div class="form-group"><label>Ваше имя *</label><input type="text" name="name" required></div>
                <div class="form-group">
                    <label>Модель автомобиля</label>
                    <select name="car_id">
                        <option value="0">Не выбрано</option>
                        <?php foreach ($cars as $car): ?>
                            <option value="<?= e($car['id']) ?>"><?= e($car['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Оценка</label>
                    <select name="rating">
                        <option value="5">5 — Отлично</option>
                        <option value="4">4 — Хорошо</option>
                        <option value="3">3 — Средне</option>
                        <option value="2">2 — Плохо</option>
                        <option value="1">1 — Очень плохо</option>
                    </select>
                </div>
                <div class="form-group"><label>Ваш отзыв *</label><textarea name="text" rows="5" required></textarea></div>
                <div class="form-group">
                    <label>Фото (необязательно)</label>
                    <input type="file" name="photo" accept="image/*">
                </div>
                <button class="btn btn-primary" type="submit"><?= e(t('send')) ?></button>
            </form>
        </div>

        <div class="section" style="text-align: center; margin-top: 2rem;">
            <a href="/catalog.php" class="btn btn-primary">Выбрать автомобиль</a>
            <a href="/contacts.php" class="btn">Связаться с нами</a>
        </div>
    </div>
</main>

<script>
const reviewForm = document.getElementById('review-form');
if (reviewForm) {
    reviewForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(reviewForm);
        const response = await fetch('', { method: 'POST', body: formData });
        const html = await response.text();
        document.documentElement.innerHTML = html;
        const result = document.querySelector('.alert');
        if (!result) {
            const container = document.querySelector('.contact-form');
            if (container) {
                const p = document.createElement('div');
                p.className = 'alert alert-danger';
                p.textContent = 'Ошибка отправки. Попробуйте позже.';
                container.prepend(p);
            }
        }
    });
}
</script>

<?php include __DIR__ . '/templates/footer.php'; ?>
