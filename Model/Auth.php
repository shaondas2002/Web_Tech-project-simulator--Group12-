<?php

function start_session_safe(): void {
    if (session_status() === PHP_SESSION_NONE) {
        ini_set('session.use_strict_mode', '1');
        ini_set('session.cookie_httponly', '1');
        session_start();
    }
}

function is_logged_in(): bool {
    start_session_safe();
    return isset($_SESSION['user_id']);
}

function require_login(): void {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit();
    }
}

function current_role(): string {
    start_session_safe();
    return $_SESSION['role'] ?? '';
}

function require_role(array $roles): void {
    require_login();
    if (!in_array(current_role(), $roles, true)) {
        http_response_code(403);
        echo 'Access forbidden';
        exit();
    }
}
