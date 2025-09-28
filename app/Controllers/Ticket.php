<?php

require_once __DIR__ . '/../Database.php';

class Ticket {
    private $db;
    public function __construct() {
        $this->db = Database::getConnection();
    }
    public function getUserTickets($user_id) {
        $stmt = $this->db->prepare("SELECT * FROM bets WHERE user_id = ? ORDER BY id DESC LIMIT 10");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function create($user_id, $lottery_id, $numbers, $count, $price) {
        $stmt = $this->db->prepare("INSERT INTO bets (user_id, lottery_id, numbers, ticket_count, cost) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$user_id, $lottery_id, $numbers, $count, $price]);
    }
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM bets WHERE id = ?");
        return $stmt->execute([$id]);
    }
}