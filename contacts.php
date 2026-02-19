<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/settings.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/i18n.php';

$site_name = site_name($pdo);
$page_title = t('contacts_title') . ' - ' . $site_name;
$page_description = t('contacts_title') . ' ' . $site_name . '. Запишитесь на тест-драйв и получите консультацию.';
$active = 'contacts';

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Неверный CSRF-токен.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $topic = trim($_POST['topic'] ?? '');
        $message = trim($_POST['message'] ?? '');
        $car_slug = trim($_POST['car'] ?? '');
        $car_id = null;

        if ($car_slug !== '') {
            $stmt = $pdo->prepare('SELECT id FROM cars WHERE slug = ? LIMIT 1');
            $stmt->execute([$car_slug]);
            $car_id = $stmt->fetchColumn();
            $car_id = $car_id ? (int)$car_id : null;
        }

        if ($name === '' || $phone === '' || $message === '') {
            $error = 'Пожалуйста, заполните обязательные поля.';
        } else {
            try {
                $stmt = $pdo->prepare('INSERT INTO leads (name, phone, email, topic, message, car_id) VALUES (?, ?, ?, ?, ?, ?)');
                $stmt->execute([$name, $phone, $email, $topic, $message, $car_id]);
                $success = 'Спасибо! Ваша заявка отправлена.';
            } catch (Throwable $e) {
                $error = 'Ошибка отправки. Попробуйте позже.';
            }
        }
    }
}

include __DIR__ . '/templates/header.php';
?>

<main>
    <div class="container">
        <h1><?= e(t('contacts_title')) ?></h1>
        <p>Свяжитесь с нами любым удобным способом или приезжайте в салон. Мы всегда готовы помочь подобрать автомобиль и предложить лучшие условия.</p>

        <div class="cars-grid">
            <div class="car-card">
                <div class="card-header">Телефон</div>
                <div class="card-body"><a href="tel:+74951234567">+7 (495) 123-45-67</a></div>
            </div>
            <div class="car-card">
                <div class="card-header">Email</div>
                <div class="card-body"><a href="mailto:info@autodrive.ru">info@autodrive.ru</a></div>
            </div>
            <div class="car-card">
                <div class="card-header">Адрес</div>
                <div class="card-body">Москва, Ленинградский проспект, 123</div>
            </div>
            <div class="car-card">
                <div class="card-header">График работы</div>
                <div class="card-body">Ежедневно 9:00–21:00</div>
            </div>
        </div>

        <div class="map-container">
            <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3A7b57e2f5d3b7e6e6b99e77f8d3e33d7d1c1f8c7d7b9c8f2f0d6c6d9b0e5c0e1e&source=constructor" frameborder="0"></iframe>
            <p class="map-caption">Мы на карте</p>
        </div>

        <div class="contact-form">
            <h2><?= e(t('leave_request')) ?></h2>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= e($error) ?></div>
            <?php elseif ($success): ?>
                <div class="alert alert-success"><?= e($success) ?></div>
            <?php endif; ?>
            <form method="post" id="lead-form">
                <input type="hidden" name="csrf_token" value="<?= e(generate_csrf_token()) ?>">
                <?php if (!empty($_GET['car'])): ?>
                    <input type="hidden" name="car" value="<?= e($_GET['car']) ?>">
                <?php endif; ?>
                <div class="form-group"><label>Ваше имя *</label><input type="text" name="name" required></div>
                <div class="form-group"><label>Телефон *</label><input type="tel" name="phone" required></div>
                <div class="form-group"><label>Email</label><input type="email" name="email"></div>
                <div class="form-group"><label>Тема</label>
                    <select name="topic">
                        <option>Покупка автомобиля</option>
                        <option>Тест-драйв</option>
                        <option>Кредит/Лизинг</option>
                        <option>Сервис</option>
                        <option>Другое</option>
                    </select>
                </div>
                <div class="form-group"><label>Сообщение *</label><textarea name="message" rows="5" required></textarea></div>
                <button class="btn btn-primary" type="submit"><?= e(t('send')) ?></button>
            </form>
        </div>
    </div>
</main>

<script>
const leadForm = document.getElementById('lead-form');
if (leadForm) {
    leadForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(leadForm);
        const response = await fetch('', { method: 'POST', body: formData });
        const html = await response.text();
        document.documentElement.innerHTML = html;
        const result = document.querySelector('.alert');
        if (!result) {
            const container = document.querySelector('.contact-form');
            if (container) {
                const p = document.createElement('div');
                p.className = 'alert alert-danger';
                p.textContent = 'Ошибка отправки. Попробуйте позже.';
                container.prepend(p);
            }
        }
    });
}
</script>

<?php include __DIR__ . '/templates/footer.php'; ?>
