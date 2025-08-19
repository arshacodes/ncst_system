<?php
declare(strict_types=1);
session_start();

$returnUrl = $_GET['return_url'] 
    ?? ($_SERVER['HTTP_REFERER'] ?? '../../admin_portal.php');

require_once __DIR__ . '/../../src/db/db_conn.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    $_SESSION['flash'] = [
        'icon'  => 'error',
        'title' => 'Invalid Request',
        'text'  => 'No valid faculty ID provided.'
    ];
    header("Location: $returnUrl");
    exit;
}

try {
    $stmt = $pdo->prepare("
        UPDATE faculty_tbl
           SET status = 'Archived'
         WHERE id = :id
    ");
    $stmt->execute([':id' => $id]);

    if ($stmt->rowCount()) {
        $_SESSION['flash'] = [
            'icon'  => 'success',
            'title' => 'Archived',
            'text'  => 'Faculty member has been archived.'
        ];
    } else {
        $_SESSION['flash'] = [
            'icon'  => 'info',
            'title' => 'No Changes',
            'text'  => 'Faculty member was already archived or not found.'
        ];
    }
} catch (PDOException $e) {
    $_SESSION['flash'] = [
        'icon'  => 'error',
        'title' => 'Database Error',
        'text'  => 'Could not archive faculty. Please try again.'
    ];
}

header("Location: $returnUrl");
exit;
