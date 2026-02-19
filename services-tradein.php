<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/settings.php';
require_once __DIR__ . '/includes/i18n.php';
$site_name = site_name($pdo);
$page_title = 'Trade-in - ' . $site_name;
$page_description = 'Trade-in ' . $site_name . '. Лучшие условия по trade-in.';
$active = 'services';
include __DIR__ . '/templates/header.php';
?>

<main>
    <div class="container">
        <h1>Trade-in</h1>
        <p>Обменяйте свой текущий автомобиль на новый. Мы оценим стоимость, оформим документы и поможем выбрать новую модель.</p>
        <p>Оценка за 30 минут, прозрачная цена и честные условия.</p>

        <h2>Преимущества Trade-in</h2>
        <div class="cars-grid">
            <div class="car-card"><h3>Быстро</h3><p>Оценка и оформление в один день.</p></div>
            <div class="car-card"><h3>Прозрачно</h3><p>Честная цена без скрытых комиссий.</p></div>
            <div class="car-card"><h3>Выгодно</h3><p>Скидки на новые автомобили при обмене.</p></div>
        </div>

        <h2>Как это работает</h2>
        <ol>
            <li>Оставьте заявку на оценку</li>
            <li>Приезжайте на осмотр</li>
            <li>Получите предложение и выберите новый автомобиль</li>
        </ol>

        <div style="text-align: center; margin-top: 2rem;">
            <a href="/contacts.php" class="btn btn-primary">Оставить заявку</a>
            <a href="/catalog.php" class="btn">Перейти в каталог</a>
        </div>
    </div>
</main>

<?php include __DIR__ . '/templates/footer.php'; ?>
