<?php
require_once dirname(__DIR__, 2) . '/core/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . admin_url('login.php'));
    exit;
}

require_valid_csrf();

$pdo = db();

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$next = $_POST['next'] ?? admin_url();

$ipBin = current_ip_bin();

if ($username === '' || $password === '') {
    header('Location: ' . admin_url('login.php'));
    exit;
}

if (too_many_attempts($pdo, $ipBin, $username, 8, 15)) {
    sleep(1);
    record_attempt($pdo, $ipBin, $username, 0);

    http_response_code(429);
    exit('Trop de tentatives. Réessaie plus tard.');
}

$stmt = $pdo->prepare("
    SELECT id, username, password_hash, is_active
    FROM users
    WHERE username = :username
    LIMIT 1
");

$stmt->execute([
    'username' => $username,
]);

$user = $stmt->fetch();

$isValidUser = $user
    && (int) $user['is_active'] === 1
    && password_verify($password, $user['password_hash']);

if (!$isValidUser) {
    record_attempt($pdo, $ipBin, $username, 0);

    http_response_code(401);
    exit('Nom d’utilisateur ou mot de passe incorrect.');
}

record_attempt($pdo, $ipBin, $username, 1);

login_admin_user($user);

$stmt = $pdo->prepare("
    UPDATE users
    SET last_login = NOW()
    WHERE id = :id
");

$stmt->execute([
    'id' => $user['id'],
]);

// Redirection sécurisée : uniquement vers l’admin.
$nextPath = parse_url($next, PHP_URL_PATH);

if (
    !is_string($nextPath)
    || (
        $nextPath !== admin_url()
        && !str_starts_with($nextPath, admin_url() . '/')
    )
) {
    $next = admin_url();
}

header('Location: ' . $next);
exit;
