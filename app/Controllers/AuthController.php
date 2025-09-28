<?php

namespace App\Controllers;

use App\Models\User;

class AuthController
{
    public function login($email, $password)
    {
        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user) {
            return ['success' => false, 'message' => 'Email not registered.'];
        }

        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Incorrect password.'];
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // نحفظ بيانات المستخدم في الجلسة
        $_SESSION['user'] = [
            'id'   => $user['id'],
            'role' => $user['role'],
            'name' => $user['name'],
            'email'=> $user['email']
        ];

        return ['success' => true, 'message' => 'Login successful', 'user' => $user];
    }

    public function register($name, $email, $password)
    {
        $userModel = new User();

        if ($userModel->findByEmail($email)) {
            return ['success' => false, 'message' => 'Email is already in use.'];
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $created = $userModel->create($name, $email, $hashedPassword);

        if (!$created) {
            return ['success' => false, 'message' => 'Error while creating account.'];
        }

        return ['success' => true, 'message' => 'Account created successfully.'];
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_unset();
        session_destroy();

        return ['success' => true, 'message' => 'Logged out successfully.'];
    }
}
