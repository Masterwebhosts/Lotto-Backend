<?php

namespace App\Models;

use App\Core\Database;

class User
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /** البحث عن مستخدم عن طريق البريد */
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /** إنشاء مستخدم جديد */
    public function create($name, $email, $hashedPassword, $role = 'user')
    {
        $stmt = $this->db->prepare("
            INSERT INTO users (name, email, password, role, balance, created_at)
            VALUES (:name, :email, :password, :role, 0.00, NOW())
        ");

        return $stmt->execute([
            'name'     => $name,
            'email'    => $email,
            'password' => $hashedPassword,
            'role'     => $role
        ]);
    }

    /** البحث عن مستخدم بالـ ID */
    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /** تحديث رصيد المستخدم */
    public function updateBalance($id, $amount)
    {
        $stmt = $this->db->prepare("UPDATE users SET balance = :balance WHERE id = :id");
        return $stmt->execute([
            'balance' => $amount,
            'id'      => $id
        ]);
    }

    /** جلب كل المستخدمين (ممكن نحتاجها في لوحة التحكم) */
    public function getAll()
    {
        $stmt = $this->db->query("SELECT id, name, email, role, balance, created_at FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
