<?php
/**
 * Authentification et sécurité admin.
 *
 * Ce fichier ne lance pas la session automatiquement au chargement.
 * La session démarre seulement quand une fonction d'auth en a besoin.
 */

function start_secure_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $secure = APP_ENV === 'prod'
        || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

    $cookieParams = [
        'lifetime' => 0,
        'path' => '/',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ];

    // Optionnel : à mettre dans .env si nécessaire.
    // Exemple : SESSION_DOMAIN=.atelierivoincidit.fr
    $sessionDomain = $_ENV['SESSION_DOMAIN'] ?? '';

    if ($sessionDomain !== '') {
        $cookieParams['domain'] = $sessionDomain;
    }

    session_set_cookie_params($cookieParams);
    session_start();
}

function admin_url(string $path = ''): string
{
    $base = rtrim($_ENV['APP_BASE'] ?? '', '/');
    $path = trim($path, '/');

    if ($path === '') {
        return $base . '/admin';
    }

    return $base . '/admin/' . $path;
}

function current_admin(): ?array
{
    start_secure_session();

    if (
        empty($_SESSION['user_id'])
        || empty($_SESSION['authenticated'])
        || $_SESSION['authenticated'] !== true
    ) {
        return null;
    }

    return [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'] ?? '',
        'role' => $_SESSION['role'] ?? 'admin',
    ];
}

function is_admin(): bool
{
    return current_admin() !== null;
}

function require_admin(): void
{
    if (is_admin()) {
        return;
    }

    $next = $_SERVER['REQUEST_URI'] ?? admin_url();

    header('Location: ' . admin_url('login.php?next=' . urlencode($next)));
    exit;
}

function login_admin_user(array $user): void
{
    start_secure_session();

    session_regenerate_id(true);

    $_SESSION['user_id'] = (int) ($user['id'] ?? $user['user_id'] ?? 0);
    $_SESSION['username'] = (string) ($user['username'] ?? '');
    $_SESSION['role'] = (string) ($user['role'] ?? 'admin');
    $_SESSION['authenticated'] = true;
}

function logout_admin_user(): void
{
    start_secure_session();

    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'] ?? '',
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}

function csrf_token(): string
{
    start_secure_session();

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' .
        htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') .
        '">';
}

function csrf_check(?string $token): bool
{
    start_secure_session();

    return is_string($token)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

function require_valid_csrf(): void
{
    if (csrf_check($_POST['csrf_token'] ?? null)) {
        return;
    }

    http_response_code(419);
    exit('Jeton de sécurité invalide.');
}

function ip_to_bin(string $ip): string
{
    if (filter_var($ip, FILTER_VALIDATE_IP)) {
        return inet_pton($ip);
    }

    return inet_pton('0.0.0.0');
}

function current_ip_bin(): string
{
    return ip_to_bin($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
}

function too_many_attempts(PDO $pdo, string $ipBin, ?string $username = null, int $limit = 10, int $minutes = 15): bool
{
    $minutes = max(1, $minutes);
    $limit = max(1, $limit);

    $sql = "
        SELECT COUNT(*)
        FROM login_attempts
        WHERE ip = :ip
        AND attempt_time > (NOW() - INTERVAL {$minutes} MINUTE)
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['ip' => $ipBin]);

    if ((int) $stmt->fetchColumn() >= $limit) {
        return true;
    }

    if ($username === null || $username === '') {
        return false;
    }

    $sql = "
        SELECT COUNT(*)
        FROM login_attempts
        WHERE username = :username
        AND attempt_time > (NOW() - INTERVAL {$minutes} MINUTE)
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['username' => $username]);

    return (int) $stmt->fetchColumn() >= $limit;
}

function record_attempt(PDO $pdo, string $ipBin, ?string $username = null, int $success = 0): void
{
    $sql = "
        INSERT INTO login_attempts (ip, username, success)
        VALUES (:ip, :username, :success)
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'ip' => $ipBin,
        'username' => $username,
        'success' => $success,
    ]);
}