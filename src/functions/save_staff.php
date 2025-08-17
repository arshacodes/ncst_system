<?php
// src/functions/save_staff_tbl.php

declare(strict_types=1);
session_start();

// 1) Load your PDO connection
require_once __DIR__ . '/../../src/db/db_conn.php';

// 2) Only handle POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../superadmin_portal.php');
    exit;
}

// 3) Collect & sanitize
$id            = isset($_POST['id']) && $_POST['id'] !== '' 
                 ? (int) $_POST['id'] 
                 : null;

$f_name        = trim((string) ($_POST['f_name'] ?? ''));
$m_name        = trim((string) ($_POST['m_name'] ?? ''));
$suffix        = trim((string) ($_POST['suffix'] ?? ''));
$l_name        = trim((string) ($_POST['l_name'] ?? ''));
$department_id = isset($_POST['department_id']) 
                 ? (int) $_POST['department_id'] 
                 : null;

// 4) Basic validation
$errors = [];
if ($f_name === '') {
    $errors[] = 'First name is required.';
}
if ($l_name === '') {
    $errors[] = 'Last name is required.';
}
if (!$department_id) {
    $errors[] = 'Department must be selected.';
}

if ($errors) {
    $_SESSION['staff_errors'] = $errors;
    header('Location: ../../superadmin_portal.php');
    exit;
}

// 5) Auto-generate email & password
$emailLocal   = mb_strtolower(
    preg_replace('/\s+/', '', "{$l_name}.{$f_name}")
);
$email        = $emailLocal . '@ncst.edu.ph';
$passwordHash = password_hash($l_name, PASSWORD_DEFAULT);

try {
    if ($id) {
        // 6A) UPDATE existing staff (status remains untouched)
        $sql = "
          UPDATE staff_tbl
             SET email         = :email,
                 f_name        = :f_name,
                 m_name        = :m_name,
                 suffix        = :suffix,
                 l_name        = :l_name,
                 department_id = :dept
           WHERE id = :id
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':email' => $email,
            ':f_name'=> $f_name,
            ':m_name'=> $m_name ?: null,
            ':suffix'=> $suffix ?: null,
            ':l_name'=> $l_name,
            ':dept'  => $department_id,
            ':id'    => $id,
        ]);
    } else {
        // 6B) INSERT new staff
        // 6B.i) Generate next 4-digit staff_code
        $row = $pdo
          ->query("SELECT MAX(CAST(staff_code AS UNSIGNED)) AS max_code FROM staff_tbl")
          ->fetch(PDO::FETCH_ASSOC);

        $next = $row['max_code']
          ? ((int)$row['max_code'] + 1)
          : 1;

        $staff_code = str_pad((string)$next, 4, '0', STR_PAD_LEFT);

        // 6B.ii) Persist
        $sql = "
          INSERT INTO staff_tbl
            (staff_code, email, password, f_name, m_name, suffix, l_name, department_id, status)
          VALUES
            (:code,       :email, :pwd,      :first, :middle, :suffix, :last, :dept,       'Active')
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':code'   => $staff_code,
            ':email'  => $email,
            ':pwd'    => $passwordHash,
            ':first'  => $f_name,
            ':middle' => $m_name ?: null,
            ':suffix' => $suffix ?: null,
            ':last'   => $l_name,
            ':dept'   => $department_id,
        ]);
    }

    // 7) Redirect back to Staff tab
    header('Location: ../../superadmin_portal.php');
    exit;

} catch (PDOException $e) {
    // handle or log $e->getMessage()
    $_SESSION['staff_errors'] = ['Database error: ' . $e->getMessage()];
    header('Location: ../../superadmin_portal.php');
    exit;
}
