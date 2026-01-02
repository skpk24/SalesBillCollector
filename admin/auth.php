<?php
// auth.php
session_start();

require __DIR__ . '/db.php';

/**
 * Register a user (for demo, no email verification).
 */
function register_user(string $fullname, string $username, string $email, string $password): bool
{
    global $pdo;

    $hash = password_hash($password, PASSWORD_DEFAULT); // secure hashing.[web:24][web:18]

    $sql = "INSERT INTO users (fullname, username, email, password_hash) VALUES (:f, :u, :e, :p)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':f' => $fullname,
        ':u' => $username,
        ':e' => $email,
        ':p' => $hash,
    ]);
}

/**
 * Authenticate and store user id in session.
 */
function login_user(string $usernameOrEmail, string $password): bool
{
    global $pdo;

    $sql = "SELECT * FROM users WHERE username = :id OR email = :id LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $usernameOrEmail]);
    $user = $stmt->fetch();


      if ($user && password_verify($password, $user['password_hash']) && (int)$user['is_active'] === 1) { //[web:12][web:24]
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['created_at'] = $user['created_at'];
        $sql = "
            SELECT r.description
            FROM user_roles ur
            JOIN roles r ON ur.role_id = r.id
            WHERE ur.user_id = :uid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':uid' => (int)$user['id']]);
        $_SESSION['role'] = $stmt->fetchColumn();

        return true;
    }

    return false;
}

function logout_user(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_destroy();
}

function current_user_id(): ?int
{
    return isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
}

/**
 * Check if current user has a permission.
 */
function user_has_permission(string $permissionName): bool
{
    global $pdo;

    $userId = current_user_id();
    if (!$userId) {
        return false;
    }

    $sql = "
        SELECT 1
        FROM user_roles ur
        JOIN role_permissions rp ON ur.role_id = rp.role_id
        JOIN permissions p ON rp.permission_id = p.id
        WHERE ur.user_id = :uid
          AND p.name = :perm
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':uid'  => $userId,
        ':perm' => $permissionName,
    ]);

    return (bool)$stmt->fetchColumn();
}

/**
 * Require permission for a page/action; exits if not authorized.
 */
function require_permission(string $permissionName): void
{
    if (!user_has_permission($permissionName)) {
        //http_response_code(403);
        echo "Access denied.";
        exit;
    }
}
