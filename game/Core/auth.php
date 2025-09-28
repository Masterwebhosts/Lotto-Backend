<?php
session_start();

function require_login(): void {
  if (empty($_SESSION['user_id'])) {
    header('Location: /lotto-backend/public/login');
    exit;
  }
}

function current_user_id(): ?int { return $_SESSION['user_id'] ?? null; }

function csrf_token(): string {
  if (empty($_SESSION['csrf'])) { $_SESSION['csrf'] = bin2hex(random_bytes(16)); }
  return $_SESSION['csrf'];
}

function csrf_check(): void {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $t = $_POST['csrf'] ?? $_SERVER['HTTP_X_CSRF'] ?? '';
    if (!$t || !hash_equals($_SESSION['csrf'] ?? '', $t)) {
      http_response_code(419); echo json_encode(['error'=>'CSRF']); exit;
    }
  }
}
