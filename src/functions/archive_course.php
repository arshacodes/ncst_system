<?php
session_start();

if (empty($_SESSION['role']) || $_SESSION['role'] !== 'Head') {
    // just redirect, no JSON here
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../index.php'));
    exit;
}

require __DIR__ . '/../db/db_conn.php';

$id     = intval($_GET['id'] ?? 0);
$deptId = $_SESSION['department_id'];

if ($id <= 0) {
    $_SESSION['flash'] = [
        'icon'  => 'error',
        'title' => 'Invalid ID',
        'text'  => 'Cannot archive: invalid course identifier.'
    ];
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../index.php'));
    exit;
}

// Perform the archive only if it belongs to this department
$stmt = $db->prepare("
    UPDATE courses_tbl
       SET status = 'Archived'
     WHERE id = ?
       AND department_id = ?
");
$ok = $stmt->execute([$id, $deptId]);

if ($ok && $stmt->rowCount() > 0) {
    $_SESSION['flash'] = [
        'icon'  => 'success',
        'title' => 'Course Archived',
        'text'  => 'The course has been archived.'
    ];
} else {
    $_SESSION['flash'] = [
        'icon'  => 'error',
        'title' => 'Archive Failed',
        'text'  => 'Could not archive course. It may not exist or belong to your department.'
    ];
}

// redirect back
header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../index.php'));
