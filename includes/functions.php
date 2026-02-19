<?php
function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void {
    if (!headers_sent()) {
        header('Location: ' . $path);
        exit;
    }
    echo '<script>location.href=' . json_encode($path) . ';</script>';
    echo '<noscript><meta http-equiv="refresh" content="0;url=' . htmlspecialchars($path, ENT_QUOTES, 'UTF-8') . '"></noscript>';
    exit;
}

function format_price($price): string {
    return number_format((float)$price, 0, '.', ' ');
}

function upload_image_generic(array $file, string $uploadDir): ?string {
    if (!isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    if (!isset($allowed[$mime])) {
        return null;
    }

    $ext = $allowed[$mime];
    $name = bin2hex(random_bytes(16)) . '.' . $ext;
    $target = rtrim($uploadDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $name;

    if (!move_uploaded_file($file['tmp_name'], $target)) {
        return null;
    }

    return $name;
}

function upload_car_image(array $file, string $uploadDir): ?string {
    return upload_image_generic($file, $uploadDir);
}

function upload_review_image(array $file, string $uploadDir): ?string {
    return upload_image_generic($file, $uploadDir);
}

function delete_file_safe(string $path): void {
    if ($path && file_exists($path)) {
        @unlink($path);
    }
}

function generate_token(int $bytes = 16): string {
    return bin2hex(random_bytes($bytes));
}

function table_has_column(PDO $pdo, string $table, string $column): bool {
    static $cache = [];
    $key = $table . '.' . $column;
    if (array_key_exists($key, $cache)) {
        return $cache[$key];
    }
    try {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?');
        $stmt->execute([$table, $column]);
        $cache[$key] = ((int)$stmt->fetchColumn() > 0);
    } catch (Throwable $e) {
        $cache[$key] = false;
    }
    return $cache[$key];
}
