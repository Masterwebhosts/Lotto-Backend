<?php

require_once __DIR__ . '/../Database.php';

class Bet {
    private $db;
    public function __construct() {
        $this->db = Database::getConnection();
    }
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM bets ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM bets WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function create($user_id, $lottery_id, $numbers, $ticket_count, $cost) {
        $stmt = $this->db->prepare("INSERT INTO bets (user_id, lottery_id, numbers, ticket_count, cost) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$user_id, $lottery_id, $numbers, $ticket_count, $cost]);
    }
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM bets WHERE id = ?");
        return $stmt->execute([$id]);
    }
}