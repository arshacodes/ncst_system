<?php
declare(strict_types=1);
session_start();

$returnUrl = $_GET['return_url'] 
    ?? ($_SERVER['HTTP_REFERER'] ?? '../../admin_portal.php');

require_once __DIR__ . '/../db/db_conn.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: $returnUrl");
    exit;
}

$id = (int) $_GET['id'];

try {
    $stmt = $pdo->prepare("
        UPDATE staff_tbl
           SET status = 'Archived'
         WHERE id = :id
    ");
    $stmt->execute([':id' => $id]);

    $_SESSION['flash'] = [
        'icon'  => 'success',
        'title' => 'Staff Archived',
        'text'  => "Staff #{$id} has been archived."
    ];

    header("Location: $returnUrl");
    exit;
} catch (PDOException $e) {
    $_SESSION['flash'] = [
        'icon'  => 'error',
        'title' => 'Archive Failed',
        'text'  => 'Database error: ' . $e->getMessage()
    ];
    header("Location: $returnUrl");
    exit;
}
