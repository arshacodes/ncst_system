<?php
declare(strict_types=1);
session_start();

$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
    && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($isAjax) {
    header('Content-Type: application/json; charset=utf-8');
}

$returnUrl = $_POST['return_url']
    ?? ($_SERVER['HTTP_REFERER'] ?? '../../admin_portal.php');

require_once __DIR__ . '/../../src/db/db_conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    if ($isAjax) {
        echo json_encode([
            'success' => false,
            'errors'  => ['Invalid request method.']
        ]);
    } else {
        header("Location: $returnUrl");
    }
    exit;
}

$id            = isset($_POST['id']) && $_POST['id'] !== ''   ? (int) $_POST['id']        : null;
$f_name        = trim((string) ($_POST['f_name']        ?? ''));
$m_name        = trim((string) ($_POST['m_name']        ?? ''));
$suffix        = trim((string) ($_POST['suffix']        ?? ''));
$l_name        = trim((string) ($_POST['l_name']        ?? ''));
$department_id = isset($_POST['department_id'])          ? (int) $_POST['department_id'] : null;

$errors = [];
if ($f_name === '')        { $errors[] = 'First name is required.'; }
if ($l_name === '')        { $errors[] = 'Last name is required.'; }
if (!$department_id)       { $errors[] = 'Department must be selected.'; }

if ($errors) {
    if ($isAjax) {
        echo json_encode([
            'success' => false,
            'errors'  => $errors
        ]);
    } else {
        $_SESSION['staff_errors'] = $errors;
        header("Location: $returnUrl");
    }
    exit;
}

$emailLocal   = mb_strtolower(preg_replace('/\s+/', '', "{$l_name}.{$f_name}"));
$email        = $emailLocal . '@ncst.edu.ph';
$passwordHash = password_hash($l_name, PASSWORD_DEFAULT);

try {
    if ($id) {
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
            ':email'  => $email,
            ':f_name' => $f_name,
            ':m_name' => $m_name ?: null,
            ':suffix' => $suffix ?: null,
            ':l_name' => $l_name,
            ':dept'   => $department_id,
            ':id'     => $id,
        ]);
    } else {
        $check = $pdo->prepare("
            SELECT id, department_id, status
              FROM staff_tbl
             WHERE email = :email
        ");
        $check->execute([':email' => $email]);
        $existing = $check->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            if ((int)$existing['department_id'] !== $department_id
                || $existing['status'] === 'Archived'
            ) {
                $upd = $pdo->prepare("
                    UPDATE staff_tbl
                       SET department_id = :dept,
                           status        = 'Active'
                     WHERE id = :id
                ");
                $upd->execute([
                    ':dept' => $department_id,
                    ':id'   => $existing['id']
                ]);
            }

            if ($isAjax) {
                echo json_encode([
                    'success'  => true,
                    'existing' => true,
                    'message'  => 'Existing staff reactivated or updated.'
                ]);
            } else {
                header("Location: $returnUrl");
            }
            exit;
        }

        $row = $pdo
            ->query("SELECT MAX(CAST(staff_code AS UNSIGNED)) AS max_code FROM staff_tbl")
            ->fetch(PDO::FETCH_ASSOC);

        $next       = $row['max_code'] ? ((int)$row['max_code'] + 1) : 1;
        $staff_code = str_pad((string)$next, 4, '0', STR_PAD_LEFT);

        $sql = "
            INSERT INTO staff_tbl
                (staff_code, email, password, f_name, m_name, suffix, l_name, department_id, status)
            VALUES
                (:code, :email, :pwd, :first, :middle, :suffix, :last, :dept, 'Active')
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

    if ($isAjax) {
        echo json_encode([
            'success' => true,
            'message' => $id
                ? 'Staff updated successfully.'
                : 'New staff created successfully.'
        ]);
    } else {
        header("Location: $returnUrl");
    }
    exit;
} catch (PDOException $e) {
    $msg = 'Database error: ' . $e->getMessage();
    if ($isAjax) {
        echo json_encode([
            'success' => false,
            'errors'  => [$msg]
        ]);
    } else {
        $_SESSION['staff_errors'] = [$msg];
        header("Location: $returnUrl");
    }
    exit;
}
