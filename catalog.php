<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/settings.php';
require_once __DIR__ . '/includes/i18n.php';
$site_name = site_name($pdo);
$page_title = t('nav_catalog') . ' - ' . $site_name;
$page_description = t('nav_catalog') . ' ' . $site_name . '. Лучшие автомобили в вашем городе.';
$active = 'catalog';

$cars = $pdo->query('SELECT id, name, slug, price, image FROM cars WHERE is_active = 1 ORDER BY id DESC')->fetchAll();
$featured = $pdo->query('SELECT name, slug FROM cars WHERE is_active = 1 AND is_featured = 1 ORDER BY featured_order ASC, id DESC LIMIT 6')->fetchAll();

include __DIR__ . '/templates/header.php';
?>

<main>
    <div class="container">
        <h1><?= e(t('nav_catalog')) ?></h1>
        <p>В нашем автосалоне представлен широкий выбор новых и проверенных автомобилей разных брендов и классов. Выбирайте от городских хэтчбеков до премиальных седанов и внедорожников.</p>

        <h2>Популярные модели</h2>
        <p>Самые востребованные модели в нашем салоне:</p>
        <ul>
            <?php foreach ($featured as $car): ?>
                <li><strong><?= e($car['name']) ?></strong> — <a href="/car/<?= e($car['slug']) ?>">посмотреть</a></li>
            <?php endforeach; ?>
            <?php if (!$featured): ?>
                <li>Пока нет популярных моделей — добавьте их в админке.</li>
            <?php endif; ?>
        </ul>

        <h2>Все автомобили</h2>
        <div class="cars-grid">
            <?php foreach ($cars as $car): ?>
                <?php $imageUrl = $car['image'] ? '/uploads/cars/' . $car['image'] : '/assets/img/hero/showroom.jpg'; ?>
                <div class="car-card">
                    <div class="car-image"><img src="<?= e($imageUrl) ?>" alt="<?= e($car['name']) ?>"></div>
                    <h3><?= e($car['name']) ?></h3>
                    <div class="car-price">от <?= format_price($car['price']) ?> ₽</div>
                    <a href="/car/<?= e($car['slug']) ?>" class="btn"><?= e(t('more')) ?></a>
                    <a href="/contacts.php?car=<?= e($car['slug']) ?>" class="btn btn-primary"><?= e(t('buy')) ?></a>
                </div>
            <?php endforeach; ?>
        </div>

        <h2>Фильтр автомобилей</h2>
        <div class="contact-form">
            <form>
                <div class="form-group">
                    <label for="brand">Марка автомобиля</label>
                    <select id="brand" name="brand">
                        <option value="">Все марки</option>
                        <option value="kia">Kia</option>
                        <option value="hyundai">Hyundai</option>
                        <option value="toyota">Toyota</option>
                        <option value="bmw">BMW</option>
                        <option value="mercedes">Mercedes-Benz</option>
                        <option value="lada">Lada</option>
                        <option value="volkswagen">Volkswagen</option>
                        <option value="skoda">Skoda</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="price">Цена, ₽</label>
                    <select id="price" name="price">
                        <option value="">Любая цена</option>
                        <option value="0-1000000">до 1 000 000</option>
                        <option value="1000000-2000000">1–2 млн</option>
                        <option value="2000000-3000000">2–3 млн</option>
                        <option value="3000000-5000000">3–5 млн</option>
                        <option value="5000000-10000000">5–10 млн</option>
                        <option value="10000000-999999999">свыше 10 млн</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="year">Год выпуска</label>
                    <select id="year" name="year">
                        <option value="">Любой год</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                        <option value="2022">2022</option>
                        <option value="2021">2021</option>
                        <option value="2020">2020</option>
                        <option value="2019">2019</option>
                        <option value="2018">2018</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="type">Тип кузова</label>
                    <select id="type" name="type">
                        <option value="">Любой тип</option>
                        <option value="sedan">Седан</option>
                        <option value="hatchback">Хэтчбек</option>
                        <option value="suv">Кроссовер</option>
                        <option value="coupe">Купе</option>
                        <option value="minivan">Минивэн</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Найти автомобили</button>
                <button type="reset" class="btn btn-secondary">Сбросить фильтр</button>
            </form>
        </div>
    </div>
</main>

<?php include __DIR__ . '/templates/footer.php'; ?>
