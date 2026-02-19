<?php
if (!ob_get_level()) {
    ob_start();
}

// Session setup (Beget compatible)
$sessionPath = __DIR__ . '/../sessions';
if (!is_dir($sessionPath)) {
    @mkdir($sessionPath, 0777, true);
}

if (!headers_sent()) {
    if (is_dir($sessionPath) && is_writable($sessionPath)) {
        ini_set('session.save_path', $sessionPath);
    }
    ini_set('session.use_strict_mode', 1);
    ini_set('session.use_only_cookies', 1);
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    if (!headers_sent()) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => false,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    }
    @session_start();
}

function is_admin(): bool {
    return isset($_SESSION['admin_id']) && $_SESSION['admin_id'] > 0;
}

function login_admin(PDO $pdo, string $email, string $password): bool {
    $stmt = $pdo->prepare('SELECT id, password_hash FROM admins WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        return false;
    }
    if (!password_verify($password, $admin['password_hash'])) {
        return false;
    }

    session_regenerate_id(true);
    $_SESSION['admin_id'] = (int)$admin['id'];
    $_SESSION['admin_email'] = $email;
    unset($_SESSION['pending_admin_id'], $_SESSION['pending_admin_email']);
    return true;
}

function logout_admin(): void {
    $_SESSION = [];
    unset($_SESSION['pending_admin_id'], $_SESSION['pending_admin_email']);

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}

function require_admin(): void {
    if (!is_admin()) {
        if (!headers_sent()) {
            header('Location: /admin/login.php');
            exit;
        }
        echo '<script>location.href="/admin/login.php";</script>';
        echo '<noscript><meta http-equiv="refresh" content="0;url=/admin/login.php"></noscript>';
        exit;
    }
    // If admin was deleted, force logout
    if (isset($GLOBALS['pdo']) && $GLOBALS['pdo'] instanceof PDO) {
        $stmt = $GLOBALS['pdo']->prepare('SELECT id FROM admins WHERE id = ? LIMIT 1');
        $stmt->execute([(int)$_SESSION['admin_id']]);
        if (!$stmt->fetchColumn()) {
            logout_admin();
            if (!headers_sent()) {
                header('Location: /admin/login.php');
                exit;
            }
            echo '<script>location.href="/admin/login.php";</script>';
            echo '<noscript><meta http-equiv="refresh" content="0;url=/admin/login.php"></noscript>';
            exit;
        }
    }
}
