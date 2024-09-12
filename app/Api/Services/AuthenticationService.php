<?php

namespace App\Api\Services;

use App\Api\Models\User;

class AuthenticationService
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Login method
    public function login($username, $password, $validUsers): bool
    {
        /** @var User $user */
        foreach ($validUsers as $user) {
            // Validate credentials
            if ($username === $user->username && $password === $user->password) {
                $_SESSION['logged_in'] = 1; // Set a session variable to indicate the user is logged in
                $_SESSION['username'] = $username;
                return true;
            }
        }
        return false;
    }

    // Logout method
    public function logout()
    {
        // Clear all session data
        $_SESSION = array();

        // Destroy the session cookie if it exists
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Destroy the session
        session_destroy();
    }

    public static function isLoggedIn()
    {
        if (session_status() === PHP_SESSION_NONE) {

            session_start();
        }

        return isset($_SESSION['logged_in']);
    }
}


