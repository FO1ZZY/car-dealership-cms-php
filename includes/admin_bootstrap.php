<?php
$is_admin = true;
$root = realpath(__DIR__ . '/..');

require_once $root . '/includes/auth.php';
require_once $root . '/includes/csrf.php';
require_once $root . '/includes/db.php';
require_once $root . '/includes/functions.php';
require_once $root . '/includes/settings.php';

require_admin();