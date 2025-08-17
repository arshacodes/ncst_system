<?php
// save_faculty.php

require_once __DIR__ . '/../../src/db/db_conn.php';
session_start();

// collect & sanitize input
$id           = $_POST['id']            ?? '';
$firstName    = trim($_POST['f_name']   ?? '');
$middleName   = trim($_POST['m_name']   ?? '');
$lastName     = trim($_POST['l_name']   ?? '');
$suffix       = trim($_POST['suffix']   ?? '');
$departmentId = $_POST['department_id'] ?? '';
$role         = $_POST['role']          ?? '';

if (!$firstName || !$lastName || !$departmentId || !$role) {
    $_SESSION['flash'] = [
        'icon'  => 'error',
        'title' => 'Missing Fields',
        'text'  => 'Please fill in all required fields.'
    ];
    header('Location: ../../superadmin_portal.php');
    exit;
}

// build email: surname.firstname@ncst.edu.ph
$emailLocal = mb_strtolower(
    preg_replace('/\s+/', '', $lastName . '.' . $firstName)
);
$email = $emailLocal . '@ncst.edu.ph';

try {
    if ($id) {
        // update existing faculty
        $sql = "
            UPDATE faculty_tbl
               SET email         = :email,
                   f_name        = :first,
                   m_name        = :middle,
                   l_name        = :last,
                   suffix        = :suffix,
                   department_id = :dept,
                   role          = :role
             WHERE id = :id
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':email'  => $email,
            ':first'  => $firstName,
            ':middle' => $middleName,
            ':last'   => $lastName,
            ':suffix' => $suffix,
            ':dept'   => $departmentId,
            ':role'   => $role,
            ':id'     => $id,
        ]);

        $_SESSION['flash'] = [
            'icon'  => 'success',
            'title' => 'Updated',
            'text'  => 'Faculty record updated.'
        ];
    } else {
        // generate next 4-digit faculty_code
        $row = $pdo
            ->query("SELECT MAX(CAST(faculty_code AS UNSIGNED)) AS max_code FROM faculty_tbl")
            ->fetch(PDO::FETCH_ASSOC);

        $next = $row['max_code']
            ? ((int)$row['max_code'] + 1)
            : 1;

        $facultyCode = str_pad((string)$next, 4, '0', STR_PAD_LEFT);

        // hash password as the surname
        $passwordHash = password_hash($lastName, PASSWORD_DEFAULT);

        // insert new faculty
        $sql = "
            INSERT INTO faculty_tbl
                (faculty_code, email, f_name, m_name, l_name, suffix,
                 department_id, role, password, status)
            VALUES
                (:code, :email, :first, :middle, :last, :suffix,
                 :dept, :role, :password, 'Active')
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':code'     => $facultyCode,
            ':email'    => $email,
            ':first'    => $firstName,
            ':middle'   => $middleName,
            ':last'     => $lastName,
            ':suffix'   => $suffix,
            ':dept'     => $departmentId,
            ':role'     => $role,
            ':password' => $passwordHash,
        ]);

        $_SESSION['flash'] = [
            'icon'  => 'success',
            'title' => 'Added',
            'text'  => 'New faculty record created.'
        ];
    }
} catch (PDOException $e) {
    $_SESSION['flash'] = [
        'icon'  => 'error',
        'title' => 'Database Error',
        'text'  => 'Could not save faculty. Please try again.'
    ];
}

header('Location: ../../superadmin_portal.php');
exit;
