<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/settings.php';
require_once __DIR__ . '/includes/i18n.php';
$site_name = site_name($pdo);
$page_title = 'Сервис - ' . $site_name;
$page_description = 'Сервис - ' . $site_name . '. Профессиональное обслуживание автомобилей.';
$active = 'services';
include __DIR__ . '/templates/header.php';
?>

<main>
    <div class="container">
        <h1>Техническое обслуживание</h1>
        <p>Наш сервисный центр оснащен современным оборудованием. Мы выполняем диагностику, плановое обслуживание и ремонт автомобилей всех марок.</p>
        <p>Оригинальные запчасти и сертифицированные мастера гарантируют высокое качество работ.</p>

        <h2>Наши услуги</h2>
        <div class="cars-grid">
            <div class="car-card"><h3>Диагностика</h3><p>Компьютерная диагностика и проверка систем.</p></div>
            <div class="car-card"><h3>Плановое ТО</h3><p>Регламентное обслуживание, масло и фильтры.</p></div>
            <div class="car-card"><h3>Ремонт</h3><p>Подвеска, двигатель и трансмиссия.</p></div>
        </div>

        <h2>Почему выбирают наш сервис</h2>
        <ul>
            <li>Гарантия на работы и запчасти</li>
            <li>Прозрачные цены</li>
            <li>Соблюдение сроков</li>
        </ul>

        <div style="text-align: center; margin-top: 2rem;">
            <a href="/contacts.php" class="btn">Записаться на сервис</a>
        </div>
    </div>
</main>

<?php include __DIR__ . '/templates/footer.php'; ?>
