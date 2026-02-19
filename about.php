<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/settings.php';
require_once __DIR__ . '/includes/i18n.php';
$site_name = site_name($pdo);
$page_title = 'О нас - ' . $site_name;
$page_description = 'Премиальный автосалон в вашем городе.';
$active = 'about';
include __DIR__ . '/templates/header.php';
?>

<main>
    <section class="section about-hero">
        <div class="container">
            <h1>О компании</h1>
            <p>Автодрайв — премиальный автосалон, где каждый клиент получает персональный сервис и прозрачные условия покупки. Более 15 лет мы помогаем выбирать автомобили мечты и сопровождаем сделку от первого звонка до получения ключей.</p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="gallery-grid">
                <figure>
                    <img src="/assets/img/gallery/showroom-main.jpg" alt="Наш салон">
                    <p class="photo-caption">Наш салон</p>
                </figure>
                <figure>
                    <img src="/assets/img/gallery/showroom-exterior.jpg" alt="Выставочный зал">
                    <p class="photo-caption">Выставочный зал</p>
                </figure>
                <figure>
                    <img src="/assets/img/gallery/service-bay.jpg" alt="Сервисная зона">
                    <p class="photo-caption">Сервисная зона</p>
                </figure>
                <figure>
                    <img src="/assets/img/gallery/sales-team.jpg" alt="Наша команда">
                    <p class="photo-caption">Наша команда</p>
                </figure>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <h2>Наши преимущества</h2>
            <ul class="features-list">
                <li><strong>Широкий выбор</strong> — более 1000 автомобилей в наличии</li>
                <li><strong>Прозрачные условия</strong> — официальные договоры и честные цены</li>
                <li><strong>Экспертный сервис</strong> — профессиональные консультации и сопровождение</li>
                <li><strong>Гарантия качества</strong> — каждый автомобиль проходит диагностику</li>
            </ul>
            <div style="margin-top: 2rem;">
                <a href="/contacts.php" class="btn btn-primary">Связаться с нами</a>
                <a href="/catalog.php" class="btn">Перейти в каталог</a>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/templates/footer.php'; ?>
