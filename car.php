<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/settings.php';
require_once __DIR__ . '/includes/i18n.php';

$slug = trim($_GET['slug'] ?? '');
if ($slug === '') {
    http_response_code(404);
    $page_title = 'Автомобиль не найден - АвтоДрайв Центр';
    $page_description = 'Автомобиль не найден.';
    $active = 'catalog';
    include __DIR__ . '/templates/header.php';
    echo '<main><div class="container"><h1>Автомобиль не найден</h1><p>Проверьте ссылку и попробуйте снова.</p></div></main>';
    include __DIR__ . '/templates/footer.php';
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM cars WHERE slug = ? LIMIT 1');
$stmt->execute([$slug]);
$car = $stmt->fetch();

if (!$car) {
    http_response_code(404);
    $page_title = 'Автомобиль не найден - АвтоДрайв Центр';
    $page_description = 'Автомобиль не найден.';
    $active = 'catalog';
    include __DIR__ . '/templates/header.php';
    echo '<main><div class="container"><h1>Автомобиль не найден</h1><p>Проверьте ссылку и попробуйте снова.</p></div></main>';
    include __DIR__ . '/templates/footer.php';
    exit;
}

$page_title = $car['name'] . ' - АвтоДрайв Центр';
$page_description = $car['name'] . ' - купить в АвтоДрайв Центр. Лучший автосалон в вашем городе.';
$active = 'catalog';
include __DIR__ . '/templates/header.php';

$imageUrl = $car['image'] ? '/uploads/cars/' . $car['image'] : '/assets/img/hero/showroom.jpg';
$description = $car['description'] ?: 'Свяжитесь с нашим менеджером, чтобы получить подробную информацию о комплектациях, наличии и условиях покупки.';
$reviewsStmt = $pdo->prepare('SELECT name, rating, text, photo FROM reviews WHERE is_approved = 1 AND car_id = ? ORDER BY id DESC LIMIT 3');
$reviewsStmt->execute([(int)$car['id']]);
$reviews = $reviewsStmt->fetchAll();
?>

<main>
    <div class="container">
        <h1><?= e($car['name']) ?></h1>

        <div class="car-detail-image">
            <img src="<?= e($imageUrl) ?>" alt="<?= e($car['name']) ?>">
        </div>

        <div class="car-price" style="text-align: center; font-size: 2rem; margin: 2rem 0;">
            от <?= format_price($car['price']) ?> ₽
        </div>

        <div style="text-align: center; margin-bottom: 3rem;">
            <a href="/contacts.php" class="btn btn-primary">Купить сейчас</a>
            <a href="/contacts.php" class="btn">Записаться на тест-драйв</a>
            <a href="/services-credit.php" class="btn">Рассчитать кредит</a>
        </div>

        <h2>Описание</h2>
        <p><?= e($description) ?></p>

        <h2>Технические характеристики</h2>
        <table class="specs-table">
            <tr><th>Параметр</th><th>Значение</th></tr>
            <tr><td>Двигатель</td><td>Уточняйте у менеджера</td></tr>
            <tr><td>Мощность</td><td>Уточняйте у менеджера</td></tr>
            <tr><td>Коробка передач</td><td>Уточняйте у менеджера</td></tr>
            <tr><td>Расход топлива</td><td>Уточняйте у менеджера</td></tr>
            <tr><td>Разгон 0-100 км/ч</td><td>Уточняйте у менеджера</td></tr>
            <tr><td>Количество мест</td><td>5</td></tr>
            <tr><td>Объём багажника</td><td>Уточняйте у менеджера</td></tr>
            <tr><td>Габариты (Д×Ш×В)</td><td>Уточняйте у менеджера</td></tr>
        </table>

        <h2>Комплектации и цены</h2>
        <div class="accordion-item">
            <div class="accordion-header">Базовая комплектация</div>
            <div class="accordion-content">
                <ul>
                    <li>Кондиционер</li><li>Электростеклоподъёмники</li><li>Центральный замок</li>
                    <li>Аудиосистема с Bluetooth</li><li>Подушки безопасности</li><li>ABS и ESP</li>
                </ul>
            </div>
        </div>
        <div class="accordion-item">
            <div class="accordion-header">Комфорт</div>
            <div class="accordion-content">
                <ul>
                    <li>Климат-контроль</li><li>Круиз-контроль</li><li>Подогрев сидений</li>
                    <li>Мультимедийная система с навигацией</li>
                </ul>
            </div>
        </div>
        <div class="accordion-item">
            <div class="accordion-header">Премиум</div>
            <div class="accordion-content">
                <ul>
                    <li>Панорамная крыша</li><li>Камера заднего вида</li><li>Бесключевой доступ</li>
                </ul>
            </div>
        </div>

        <h2>Отзывы владельцев</h2>
        <?php if ($reviews): ?>
            <div class="reviews-grid">
                <?php foreach ($reviews as $review): ?>
                    <?php
                    $stars = str_repeat('★', (int)$review['rating']) . str_repeat('☆', 5 - (int)$review['rating']);
                    $photoUrl = $review['photo'] ? '/uploads/reviews/' . $review['photo'] : '/assets/img/reviews/1.jpg';
                    ?>
                    <div class="review-card">
                        <div class="review-header">
                            <div class="review-avatar"><img src="<?= e($photoUrl) ?>" alt="<?= e($review['name']) ?>"></div>
                            <div>
                                <h3 class="review-name"><?= e($review['name']) ?></h3>
                                <p class="review-car"><?= e($car['name']) ?></p>
                            </div>
                        </div>
                        <div class="review-body">
                            <p class="review-text"><?= e($review['text']) ?></p>
                            <div class="review-stars"><?= e($stars) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="car-card">Пока нет отзывов по этой модели.</div>
        <?php endif; ?>

        <div style="text-align: center; margin-top: 3rem;">
            <a href="/reviews.php" class="btn">Читать все отзывы</a>
            <a href="/contacts.php" class="btn btn-primary">Оставить заявку</a>
        </div>
    </div>
</main>

<?php include __DIR__ . '/templates/footer.php'; ?>
