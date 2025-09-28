<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/Core/auth.php';

use App\Core\Database;

require_login('admin');

// Ensure response is JSON
header('Content-Type: application/json');

// Receive JSON data from JavaScript
$data = json_decode(file_get_contents("php://input"), true);

$lotteryId = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
$action    = $data['action'] ?? null;

if (!$lotteryId || !in_array($action, ['close', 'reopen'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$db = Database::getConnection();
$newStatus = ($action === 'close') ? 'closed' : 'open';

$stmt = $db->prepare("UPDATE lotteries SET status = :status WHERE id = :id");
$ok = $stmt->execute(['status' => $newStatus, 'id' => $lotteryId]);

if ($ok) {
    echo json_encode(['success' => true, 'message' => "Lottery has been {$newStatus}."]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database update failed']);
}
