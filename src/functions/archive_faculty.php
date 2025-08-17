<?php
// archive_faculty.php

require_once __DIR__ . '/../../src/db/db_conn.php';
session_start();

// Grab the ID from the query string
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    $_SESSION['flash'] = [
        'icon'  => 'error',
        'title' => 'Invalid Request',
        'text'  => 'No valid faculty ID provided.'
    ];
    header('Location: ../../superadmin_portal.php');
    exit;
}

try {
    // Soft‐archive by updating the status field
    $sql = "UPDATE faculty_tbl
               SET status = 'Archived'
             WHERE id = :id";
    $stmt = $pdo->prepare($sql);
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

// Redirect back to the faculty tab
header('Location: ../../superadmin_portal.php');
exit;
