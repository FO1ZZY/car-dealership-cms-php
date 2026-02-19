<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <div class="logo"><?= e($site_name) ?></div>
            <p><?= e(t('footer_about')) ?></p>
        </div>
        <div>
            <h4><?= e(t('footer_contacts')) ?></h4>
            <ul>
                <li>Телефон: +7 (495) 123-45-67</li>
                <li>Email: info@autodrive.ru</li>
                <li>Адрес: Москва, Ленинградский проспект, 123</li>
            </ul>
        </div>
        <div>
            <h4><?= e(t('footer_nav')) ?></h4>
            <ul>
                <li><a href="/index.php"><?= e(t('nav_home')) ?></a></li>
                <li><a href="/about.php"><?= e(t('nav_about')) ?></a></li>
                <li><a href="/catalog.php"><?= e(t('nav_catalog')) ?></a></li>
                <li><a href="/reviews.php"><?= e(t('nav_reviews')) ?></a></li>
                <li><a href="/gallery.php"><?= e(t('nav_gallery')) ?></a></li>
                <li><a href="/contacts.php"><?= e(t('nav_contacts')) ?></a></li>
            </ul>
        </div>
        <div>
            <h4><?= e(t('footer_services')) ?></h4>
            <ul>
                <li><a href="/services-credit.php"><?= e(t('footer_credit')) ?></a></li>
                <li><a href="/services-tradein.php"><?= e(t('footer_tradein')) ?></a></li>
                <li><a href="/services-insurance.php"><?= e(t('footer_insurance')) ?></a></li>
                <li><a href="/services-maintenance.php"><?= e(t('footer_service')) ?></a></li>
            </ul>
        </div>
    </div>
    <div class="container footer-bottom">
        <p>© 2026 <?= e($site_name) ?>. Все права защищены.</p>
        <div class="footer-links">
            <a href="#"><?= e(t('footer_privacy')) ?></a>
            <a href="/contacts.php"><?= e(t('footer_feedback')) ?></a>
        </div>
    </div>
</footer>

<?php if (empty($is_admin)) : ?><script src="/assets/js/main.js"></script><?php endif; ?>
</body>
</html>