<?php
// src/functions/archive_staff_tbl.php

declare(strict_types=1);
session_start();

// Adjust this path to wherever your DB connection lives
require_once __DIR__ . '/../db/db_conn.php';

/**
 * Mark a staff_tbl record as Archived.
 */

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: ../../superadmin_portal.php');
    exit;
}

$id = (int) $_GET['id'];

try {
    $stmt = $pdo->prepare('
        UPDATE staff_tbl
           SET status = :archived
         WHERE id = :id
    ');
    $stmt->execute([
        'archived' => 'Archived',
        'id'       => $id,
    ]);

    header('Location: ../../superadmin_portal.php');
    exit;
} catch (PDOException $e) {
    // Log $e->getMessage() in real app
    $_SESSION['staff_errors'] = ['Database error: ' . $e->getMessage()];
    header('Location: ../../superadmin_portal.php');
    exit;
}
