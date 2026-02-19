<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/settings.php';
require_once __DIR__ . '/includes/i18n.php';
$site_name = site_name($pdo);
$page_title = 'Автокредитование - ' . $site_name;
$page_description = 'Автокредитование - ' . $site_name . '. Лучшие условия кредитования.';
$active = 'services';
include __DIR__ . '/templates/header.php';
?>

<main>
    <div class="container">
        <h1>Автокредитование</h1>
        <p>Мы предлагаем выгодные программы автокредитования от ведущих банков-партнеров. Поможем подобрать лучшие условия, подготовим документы и быстро получим одобрение.</p>
        <p>Ставки от 3.9% годовых, минимальный пакет документов и срок до 7 лет. Первоначальный взнос от 0%.</p>
        <p>Специальные программы для молодых специалистов, семей с детьми и других категорий. Индивидуальный подход к каждому клиенту.</p>

        <h2>Преимущества программы</h2>
        <div class="cars-grid">
            <div class="car-card">
                <h3>Низкие ставки</h3>
                <p>Ставки от 3.9% годовых. Скидки для льготных категорий.</p>
            </div>
            <div class="car-card">
                <h3>Быстрое одобрение</h3>
                <p>Рассмотрение заявки от 1 часа. Минимум документов.</p>
            </div>
            <div class="car-card">
                <h3>Гибкие условия</h3>
                <p>Первоначальный взнос от 0%. Срок кредита до 7 лет.</p>
            </div>
        </div>

        <h2>Как это работает</h2>
        <div class="accordion">
            <div class="accordion-item">
                <div class="accordion-header">Шаг 1: Консультация</div>
                <div class="accordion-content">
                    <p>Мы подберем программу и рассчитаем платежи под ваш бюджет.</p>
                </div>
            </div>
            <div class="accordion-item">
                <div class="accordion-header">Шаг 2: Документы</div>
                <div class="accordion-content">
                    <p>Поможем с документами и подадим заявку в банки-партнеры.</p>
                </div>
            </div>
            <div class="accordion-item">
                <div class="accordion-header">Шаг 3: Выдача</div>
                <div class="accordion-content">
                    <p>После одобрения вы получаете автомобиль и оформляете страховку.</p>
                </div>
            </div>
        </div>

        <h2>Необходимые документы</h2>
        <ul>
            <li>Паспорт / удостоверение личности</li>
            <li>Водительское удостоверение</li>
            <li>Справка о доходах (при необходимости)</li>
        </ul>

        <h2>Стоимость и условия</h2>
        <p>Стоимость рассчитывается индивидуально. Обратитесь к менеджеру для персонального предложения.</p>

        <div style="text-align: center; margin-top: 2rem;">
            <a href="/contacts.php" class="btn">Позвонить менеджеру</a>
        </div>
    </div>
</main>

<?php include __DIR__ . '/templates/footer.php'; ?>
