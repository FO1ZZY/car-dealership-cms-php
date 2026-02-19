<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/settings.php';
require_once __DIR__ . '/includes/i18n.php';
$site_name = site_name($pdo);
$page_title = 'Страхование - ' . $site_name;
$page_description = 'Страхование - ' . $site_name . '. КАСКО и ОСАГО на лучших условиях.';
$active = 'services';
include __DIR__ . '/templates/header.php';
?>

<main>
    <div class="container">
        <h1>Страхование</h1>
        <p>Мы оформляем КАСКО и ОСАГО на выгодных условиях. Подберем оптимальный тариф и поможем с оформлением.</p>
        <p>Работаем с ведущими страховыми компаниями и предлагаем спецусловия для наших клиентов.</p>

        <h2>Почему это выгодно</h2>
        <div class="cars-grid">
            <div class="car-card"><h3>Лучшие тарифы</h3><p>Скидки и специальные условия от партнеров.</p></div>
            <div class="car-card"><h3>Быстрое оформление</h3><p>Полис оформляется в день обращения.</p></div>
            <div class="car-card"><h3>Поддержка</h3><p>Помощь при наступлении страхового случая.</p></div>
        </div>

        <h2>Что нужно</h2>
        <ul>
            <li>Паспорт / удостоверение личности</li>
            <li>Водительское удостоверение</li>
            <li>Документы на автомобиль</li>
        </ul>

        <div style="text-align: center; margin-top: 2rem;">
            <a href="/contacts.php" class="btn">Получить консультацию</a>
        </div>
    </div>
</main>

<?php include __DIR__ . '/templates/footer.php'; ?>
