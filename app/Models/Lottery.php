<?php
require_once __DIR__ . '/../Core/Database.php';

class Lottery {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // جلب كل السحوبات
    public function getAll() {
        $stmt = $this->db->pdo->query("SELECT * FROM lotteries ORDER BY draw_date DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // إنشاء سحب جديد
    public function create($draw_date) {
        $stmt = $this->db->pdo->prepare("INSERT INTO lotteries (draw_date, created_at, updated_at) VALUES (?, NOW(), NOW())");
        return $stmt->execute([$draw_date]);
    }

    // تحديد الأرقام الفائزة
    public function setWinningNumbers($id, $numbers) {
        $stmt = $this->db->pdo->prepare("UPDATE lotteries SET winning_numbers = ?, updated_at = NOW() WHERE id = ?");
        return $stmt->execute([$numbers, $id]);
    }

    // جلب سحب محدد
    public function find($id) {
        $stmt = $this->db->pdo->prepare("SELECT * FROM lotteries WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
