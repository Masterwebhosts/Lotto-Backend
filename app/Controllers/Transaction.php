<?php

require_once __DIR__ . '/../Database.php';

class Transaction {
    private $db;
    public function __construct() {
        $this->db = Database::getConnection();
    }
    public function getAll($user_id = null) {
        if ($user_id) {
            $stmt = $this->db->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY id DESC");
            $stmt->execute([$user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        $stmt = $this->db->query("SELECT * FROM transactions ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function create($user_id, $type, $amount) {
        $stmt = $this->db->prepare("INSERT INTO transactions (user_id, type, amount) VALUES (?, ?, ?)");
        return $stmt->execute([$user_id, $type, $amount]);
    }
}