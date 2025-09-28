<?php

require_once __DIR__ . '/../Database.php';

class Wallet {
    private $db;
    public function __construct() {
        $this->db = Database::getConnection();
    }
    public function getBalance($user_id) {
        $stmt = $this->db->prepare("SELECT balance FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchColumn();
    }
    public function addBalance($user_id, $amount) {
        $stmt = $this->db->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
        return $stmt->execute([$amount, $user_id]);
    }
    public function subtractBalance($user_id, $amount) {
        $stmt = $this->db->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
        return $stmt->execute([$amount, $user_id]);
    }
}