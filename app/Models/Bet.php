<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Bet {
    public static function create(int $userId, int $lotteryId, float $amount, string $numbers): bool {
        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO bets (user_id, lottery_id, amount, numbers) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$userId, $lotteryId, $amount, $numbers]);
    }

    public static function findByUser(int $userId): array {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT b.*, l.name AS lottery_name
            FROM bets b
            JOIN lotteries l ON b.lottery_id = l.id
            WHERE b.user_id = ?
            ORDER BY b.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
