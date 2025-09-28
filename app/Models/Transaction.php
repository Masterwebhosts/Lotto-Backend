<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Transaction
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * إنشاء حركة جديدة
     */
    public function create(int $userId, string $type, float $amount, string $description = ''): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO transactions (user_id, type, amount, description)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$userId, $type, $amount, $description]);
    }

    /**
     * جلب كل الحركات الخاصة بمستخدم معين
     */
    public function getByUser(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM transactions 
            WHERE user_id = ? 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
