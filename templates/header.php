<?php
require_once __DIR__ . '/../includes/settings.php';
require_once __DIR__ . '/../includes/i18n.php';

if (!isset($page_title)) {
    $page_title = site_name($pdo);
}
if (!isset($page_description)) {
    $page_description = site_name($pdo) . ' - лучший автосалон в вашем городе.';
}
if (!isset($active)) {
    $active = '';
}
$site_name = site_name($pdo);

$nav = [
    'index' => [t('nav_home'), '/index.php'],
    'about' => [t('nav_about'), '/about.php'],
    'catalog' => [t('nav_catalog'), '/catalog.php'],
    'services' => [t('nav_services'), '/services-credit.php'],
    'reviews' => [t('nav_reviews'), '/reviews.php'],
    'gallery' => [t('nav_gallery'), '/gallery.php'],
    'contacts' => [t('nav_contacts'), '/contacts.php'],
];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($page_title) ?></title>
    <meta name="description" content="<?= e($page_description) ?>">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="icon" href="/assets/img/logo.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/assets/img/logo.ico" type="image/x-icon">
</head>
<body>
<header class="site-header">
    <div class="topbar">
        <div class="container topbar-content">
            <span><?= e(t('topbar_text')) ?></span>
            <div class="topbar-contacts">
                <a href="tel:+74951234567">+7 (495) 123-45-67</a>
                <span><?= e(t('topbar_hours')) ?></span>
            </div>
        </div>
    </div>
    <div class="container header-content">
        <a href="/index.php" class="logo"><?= e($site_name) ?></a>
        <nav class="desktop-nav">
            <ul>
                <?php foreach ($nav as $key => $item): ?>
                    <li><a href="<?= $item[1] ?>" class="<?= $active === $key ? 'active' : '' ?>"><?= e($item[0]) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </nav>
        <div class="header-actions">
            <a href="/catalog.php" class="btn btn-ghost btn-sm"><?= e(t('btn_catalog')) ?></a>
            <a href="/contacts.php" class="btn btn-primary btn-sm"><?= e(t('btn_contact')) ?></a>
            <button class="mobile-menu-btn" aria-label="<?= e(t('btn_menu')) ?>" aria-expanded="false"><?= e(t('btn_menu')) ?></button>
        </div>
    </div>
    <nav class="mobile-nav" aria-hidden="true">
        <ul>
            <?php foreach ($nav as $key => $item): ?>
                <li><a href="<?= $item[1] ?>" class="<?= $active === $key ? 'active' : '' ?>"><?= e($item[0]) ?></a></li>
            <?php endforeach; ?>
        </ul>
        <div class="mobile-actions">
            <a href="/catalog.php" class="btn btn-ghost"><?= e(t('btn_catalog')) ?></a>
            <a href="/contacts.php" class="btn btn-primary"><?= e(t('btn_contact')) ?></a>
        </div>
    </nav>
</header>
