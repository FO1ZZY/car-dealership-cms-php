<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/settings.php';
require_once __DIR__ . '/includes/i18n.php';

$site_name = site_name($pdo);
$page_title = t('index_title') . ' - ' . $site_name;
$page_description = $site_name . ' - лучший автосалон в вашем городе. Новые и проверенные автомобили 2025 года.';
$active = 'index';

$featured = $pdo->query('SELECT id, name, slug, price, image, description FROM cars WHERE is_active = 1 AND is_featured = 1 ORDER BY featured_order ASC, id DESC LIMIT 6')->fetchAll();
if (!$featured) {
    $featured = $pdo->query('SELECT id, name, slug, price, image, description FROM cars WHERE is_active = 1 ORDER BY id DESC LIMIT 6')->fetchAll();
}

include __DIR__ . '/templates/header.php';
?>

<main>
    <div class="container">

        <section class="hero" style="--hero-image: url('/assets/img/hero/showroom.jpg');">
            <div class="hero-content">
                <h1><?= e($site_name) ?></h1>
                <p><?= e(t('hero_subtitle')) ?></p>
                <div class="hero-actions">
                    <a href="/catalog.php" class="btn btn-primary"><?= e(t('hero_btn_catalog')) ?></a>
                    <a href="/contacts.php" class="btn btn-ghost"><?= e(t('hero_btn_test')) ?></a>
                </div>
                <div class="hero-stats">
                    <div class="hero-stat"><strong>1200+</strong><span>авто в наличии</span></div>
                    <div class="hero-stat"><strong>15 лет</strong><span>на рынке</span></div>
                    <div class="hero-stat"><strong>98%</strong><span>довольных клиентов</span></div>
                </div>
            </div>
        </section>

        <section>
            <h2><?= e(t('popular_models')) ?></h2>
            <div class="cars-grid">
                <?php foreach ($featured as $car): ?>
                    <?php $imageUrl = $car['image'] ? '/uploads/cars/' . $car['image'] : '/assets/img/hero/showroom.jpg'; ?>
                    <div class="car-card">
                        <div class="car-image">
                            <img src="<?= e($imageUrl) ?>" alt="<?= e($car['name']) ?>">
                        </div>
                        <h3><?= e($car['name']) ?></h3>
                        <p><?= e($car['description'] ?: 'Современный автомобиль с отличным балансом цены и качества.') ?></p>
                        <div class="car-price">от <?= format_price($car['price']) ?> ₽</div>
                        <a href="/car/<?= e($car['slug']) ?>" class="btn"><?= e(t('more')) ?></a>
                        <a href="/contacts.php?car=<?= e($car['slug']) ?>" class="btn btn-primary"><?= e(t('buy')) ?></a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section>
            <h2>Почему выбирают нас</h2>
            <div class="cars-grid">
                <div class="car-card">
                    <h3>Широкий выбор</h3>
                    <p>Более 1000 новых и проверенных автомобилей в наличии. Ассортимент постоянно обновляется.</p>
                </div>
                <div class="car-card">
                    <h3>Выгодные условия</h3>
                    <p>Кредит от 3.9%, программа Trade-in, страхование и полный сервис.</p>
                </div>
                <div class="car-card">
                    <h3>Гарантия качества</h3>
                    <p>Все автомобили проходят диагностику и имеют официальную гарантию.</p>
                </div>
            </div>
        </section>

        <section>
            <h2>Наши услуги</h2>
            <p>Мы предлагаем полный спектр услуг для наших клиентов:</p>
            <ul>
                <li><strong>Продажа новых автомобилей</strong> — широкий выбор моделей от ведущих производителей</li>
                <li><strong>Продажа автомобилей с пробегом</strong> — тщательно проверенные автомобили</li>
                <li><strong>Кредит и лизинг</strong> — выгодные условия от банков-партнеров</li>
                <li><strong>Программа Trade-in</strong> — обмен вашего авто на новый</li>
                <li><strong>Страхование</strong> — лучшие условия КАСКО и ОСАГО</li>
                <li><strong>Сервис</strong> — квалифицированные специалисты и оригинальные запчасти</li>
            </ul>
        </section>

        <section>
            <h2>Специальные предложения</h2>
            <p>В этом месяце действуют особые условия на популярные модели:</p>
            <ul>
                <li>Kia Rio 2025 — первоначальный взнос от 0%</li>
                <li>Hyundai Solaris — кредит под 3.9% годовых</li>
                <li>Toyota Camry 2025 — дополнительная гарантия 3+2 года</li>
                <li>BMW 3/5 Series — специальные условия лизинга</li>
            </ul>
            <p>Успейте воспользоваться выгодными условиями до конца месяца!</p>
        </section>

    </div>
</main>

<?php include __DIR__ . '/templates/footer.php'; ?>
