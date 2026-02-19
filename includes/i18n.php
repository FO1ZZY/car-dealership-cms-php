<?php
$lang = 'ru';

$translations = [
    'nav_home' => 'Главная',
    'nav_about' => 'О нас',
    'nav_catalog' => 'Каталог',
    'nav_services' => 'Услуги',
    'nav_reviews' => 'Отзывы',
    'nav_gallery' => 'Галерея',
    'nav_contacts' => 'Контакты',
    'topbar_text' => 'Премиальный автосалон • Москва',
    'topbar_hours' => 'Ежедневно 9:00-21:00',
    'btn_catalog' => 'Каталог',
    'btn_contact' => 'Записаться',
    'btn_menu' => 'Меню',
    'footer_about' => 'Премиальный автосалон новых и проверенных автомобилей. Индивидуальный подбор, прозрачные условия и высокий уровень сервиса.',
    'footer_contacts' => 'Контакты',
    'footer_nav' => 'Навигация',
    'footer_services' => 'Услуги',
    'footer_credit' => 'Кредит и лизинг',
    'footer_tradein' => 'Trade-in',
    'footer_insurance' => 'Страхование',
    'footer_service' => 'Сервис',
    'footer_privacy' => 'Политика конфиденциальности',
    'footer_feedback' => 'Обратная связь',
    'index_title' => 'Главная',
    'hero_title' => 'Премиальный подбор автомобилей',
    'hero_subtitle' => 'Прозрачные условия и персональные предложения.',
    'hero_btn_catalog' => 'Смотреть каталог',
    'hero_btn_test' => 'Записаться на тест-драйв',
    'popular_models' => 'Популярные модели',
    'more' => 'Подробнее',
    'buy' => 'Купить',
    'contacts_title' => 'Контакты',
    'leave_request' => 'Оставить заявку',
    'send' => 'Отправить',
    'reviews_title' => 'Отзывы',
    'leave_review' => 'Оставить отзыв',
];

function t(string $key): string {
    global $translations;
    return $translations[$key] ?? $key;
}

function current_lang(): string {
    return 'ru';
}

function l(string $ru, string $en): string {
    return $ru;
}
