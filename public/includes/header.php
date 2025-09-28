<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/Helpers/url.php';
require_once __DIR__ . '/../../app/Helpers/lang.php'; // مهم قبل navbar

$langAttr = htmlspecialchars(current_lang(), ENT_QUOTES, 'UTF-8');
$dirAttr  = htmlspecialchars(lang_dir(), ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="<?= $langAttr ?>" dir="<?= $dirAttr ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lotto Grande</title>

  <!-- Bootstrap CSS (CDN) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Style الموحد -->
  <link href="<?= url('assets/css/style.css') ?>" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/navbar.php'; ?>
<div class="container my-4">

