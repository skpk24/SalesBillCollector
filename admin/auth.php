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
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['created_at'] = $user['created_at'];
        // Fetch user roles and permissions
        $sql = "SELECT ur.role_id, r.description AS role, p.name AS permission_name
                FROM user_roles ur
                INNER JOIN roles r ON ur.role_id = r.id
                INNER JOIN role_permissions rp ON ur.role_id = rp.role_id
                INNER JOIN permissions p ON rp.permission_id = p.id
                WHERE ur.user_id = :uid;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':uid' => (int)$user['id']]);
        // $user_role = $stmt->fetch();

        $userSchema = [];
        $permissions = [];
        $roles = [];

        // Optimize by grouping permissions under their respective roles
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $roleId = $row['role'];
            
            if (!isset($userSchema[$roleId])) {
                $userSchema[$roleId] = [
                    'permissions' => []
                ];
            }
            
            $userSchema[$roleId]['permissions'][] = $row['permission_name'];
            $permissions[] = $row['permission_name'];
            if(!in_array($roleId, $roles))
            $roles[] = $roleId;
        }
        $_SESSION['user_schema'] = $userSchema;
        $_SESSION['permissions'] = array_unique($permissions);
        $_SESSION['roles'] = array_unique($roles);
        $_SESSION['last_login_time'] = time();

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


/**
 * Helper function to verify permission
 * @param string $requiredAction The action to check
 * @param string $role The user's current role
 * @param array $map The permission matrix
 * @return bool
 */
function hasPermission($requiredAction, $role, $map) {
    if (!isset($map[$role]['permissions'])) {
        return false;
    }
    return in_array($requiredAction, $map[$role]['permissions']);
}


function checkPermission($requiredAction, $permissions) {
    
    return in_array($requiredAction, $permissions);
}
