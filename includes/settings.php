<?php
require_once __DIR__ . '/db.php';

function settings_table_exists(PDO $pdo): bool {
    try {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?');
        $stmt->execute(['settings']);
        return (int)$stmt->fetchColumn() > 0;
    } catch (Throwable $e) {
        return false;
    }
}

function settings_get(PDO $pdo, string $key, string $default = ''): string {
    if (!settings_table_exists($pdo)) {
        return $default;
    }
    try {
        $stmt = $pdo->prepare('SELECT value FROM settings WHERE `key` = ? LIMIT 1');
        $stmt->execute([$key]);
        $val = $stmt->fetchColumn();
        return $val !== false ? (string)$val : $default;
    } catch (Throwable $e) {
        return $default;
    }
}

function settings_set(PDO $pdo, string $key, string $value): void {
    if (!settings_table_exists($pdo)) {
        $pdo->exec('CREATE TABLE IF NOT EXISTS settings ( `key` VARCHAR(191) PRIMARY KEY, `value` TEXT NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
    }
    $stmt = $pdo->prepare('INSERT INTO settings (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)');
    $stmt->execute([$key, $value]);
}

function site_name(PDO $pdo): string {
    return settings_get($pdo, 'site_name', 'Автодрайв');
}