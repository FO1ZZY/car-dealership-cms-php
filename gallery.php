<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/settings.php';
require_once __DIR__ . '/includes/i18n.php';
$site_name = site_name($pdo);
$page_title = 'Галерея - ' . $site_name;
$page_description = 'Галерея ' . $site_name . ' — фото салона и мероприятий.';
$active = 'gallery';
include __DIR__ . '/templates/header.php';

$sections = [
    [
        'title' => 'Шоурум',
        'items' => [
            ['/assets/img/gallery/showroom-exterior.jpg', 'Наш салон'],
            ['/assets/img/gallery/showroom-main.jpg', 'Главный выставочный зал'],
            ['/assets/img/gallery/reception.jpg', 'Зона ресепшн'],
            ['/assets/img/gallery/meeting-room.jpg', 'Переговорная'],
            ['/assets/img/gallery/lounge.jpg', 'Лаунж для клиентов'],
            ['/assets/img/gallery/kids-room.jpg', 'Детская зона'],
            ['/assets/img/gallery/cafe.jpg', 'Кафе'],
        ],
    ],
    [
        'title' => 'Сервисный центр',
        'items' => [
            ['/assets/img/gallery/service-bay.jpg', 'Сервисный пост'],
            ['/assets/img/gallery/diagnostic.jpg', 'Диагностика'],
            ['/assets/img/gallery/paint-booth.jpg', 'Покрасочная камера'],
            ['/assets/img/gallery/warehouse.jpg', 'Склад запчастей'],
            ['/assets/img/gallery/car-wash.jpg', 'Мойка'],
            ['/assets/img/gallery/tire-service.jpg', 'Шиномонтаж'],
        ],
    ],
    [
        'title' => 'Популярные модели',
        'items' => [
            ['/assets/img/cars/bmw-x5.jpg', 'BMW X5'],
            ['/assets/img/cars/mercedes-e.jpg', 'Mercedes E'],
            ['/assets/img/cars/audi-a6.jpg', 'Audi A6'],
            ['/assets/img/cars/toyota-camry.jpg', 'Toyota Camry'],
        ],
    ],
    [
        'title' => 'Мероприятия',
        'items' => [
            ['/assets/img/gallery/exhibition-2024.jpg', 'Автовыставка 2024'],
            ['/assets/img/gallery/test-drive.jpg', 'Тест-драйвы'],
            ['/assets/img/gallery/training.jpg', 'Обучение персонала'],
            ['/assets/img/gallery/corporate.jpg', 'Корпоративные события'],
            ['/assets/img/gallery/charity.jpg', 'Благотворительные акции'],
            ['/assets/img/gallery/sport.jpg', 'Спортивные мероприятия'],
        ],
    ],
    [
        'title' => 'Команда',
        'items' => [
            ['/assets/img/gallery/sales-team.jpg', 'Менеджеры по продажам'],
            ['/assets/img/gallery/service-advisors.jpg', 'Сервис-консультанты'],
            ['/assets/img/gallery/technicians.jpg', 'Техники сервиса'],
            ['/assets/img/gallery/admin.jpg', 'Администрация'],
            ['/assets/img/gallery/training-team.jpg', 'Тренинговая группа'],
            ['/assets/img/gallery/corporate-2024.jpg', 'Корпоратив 2024'],
        ],
    ],
];
?>

<main>
    <section class="section">
        <div class="container">
            <h1>Галерея</h1>
            <p>Познакомьтесь с нашим автосалоном: просторные залы, современный сервисный центр и мероприятия для клиентов.</p>
        </div>
    </section>

    <?php foreach ($sections as $section): ?>
        <section class="section">
            <div class="container">
                <h2><?= e($section['title']) ?></h2>
                <div class="gallery-grid">
                    <?php foreach ($section['items'] as $item): ?>
                        <figure>
                            <img src="<?= e($item[0]) ?>" alt="<?= e($item[1]) ?>">
                            <p class="photo-caption"><?= e($item[1]) ?></p>
                        </figure>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endforeach; ?>

    <section class="section">
        <div class="container" style="text-align: center;">
            <a href="/contacts.php" class="btn btn-primary">Записаться на экскурсию</a>
            <a href="/catalog.php" class="btn">Перейти в каталог</a>
        </div>
    </section>
</main>

<?php include __DIR__ . '/templates/footer.php'; ?>
