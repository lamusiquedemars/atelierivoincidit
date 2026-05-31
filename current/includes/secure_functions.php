<?php
// includes/secure_functions.php
if (session_status() === PHP_SESSION_NONE) {
    // session cookie params - adapt domaine si nécessaire
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '.atelierivoincidit.fr',        // laisse vide pour domaine actuel, ou '.ivoincidit.fr'
        'secure' => true,      // true si HTTPS (obligatoire en prod)
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

// CSRF
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function csrf_check($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// IP normalize (store binary for efficiency)
function ip_to_bin($ip) {
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        return inet_pton($ip);
    } elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
        return inet_pton($ip);
    }
    return inet_pton('0.0.0.0');
}

// rate limiting: check attempts in last N minutes
function too_many_attempts($pdo, $ip_bin, $username=null, $limit=10, $minutes=15) {
    $sql = "SELECT COUNT(*) as c FROM login_attempts WHERE ip = :ip AND attempt_time > (NOW() - INTERVAL :mins MINUTE)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':ip' => $ip_bin, ':mins' => $minutes]);
    $c = (int)$stmt->fetchColumn();
    if ($c >= $limit) return true;

    if ($username) {
        $sql2 = "SELECT COUNT(*) FROM login_attempts WHERE username = :username AND attempt_time > (NOW() - INTERVAL :mins MINUTE)";
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->execute([':username' => $username, ':mins' => $minutes]);
        $c2 = (int)$stmt2->fetchColumn();
        if ($c2 >= $limit) return true;
    }
    return false;
}

function record_attempt($pdo, $ip_bin, $username=null, $success=0) {
    $sql = "INSERT INTO login_attempts (ip, username, success) VALUES (:ip, :username, :success)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':ip' => $ip_bin, ':username' => $username, ':success' => $success]);
}


// helper to create a new base32 secret
function generate_base32_secret($length=16) {
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    $s = '';
    for ($i=0;$i<$length;$i++) $s .= $chars[random_int(0,31)];
    return $s;
}

// require login check for admin pages
function ensure_admin() {
    if (empty($_SESSION['user_id']) || empty($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
        header('Location: /admin/login.php');
        exit;
    }
}
?>
