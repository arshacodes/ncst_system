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

$id           = $_POST['id']            ?? '';
$firstName    = trim((string) ($_POST['f_name']        ?? ''));
$middleName   = trim((string) ($_POST['m_name']        ?? ''));
$lastName     = trim((string) ($_POST['l_name']        ?? ''));
$suffix       = trim((string) ($_POST['suffix']        ?? ''));
$departmentId = $_POST['department_id'] ?? '';
$role         = $_POST['role']          ?? '';

$errors = [];
if (!$firstName)    { $errors[] = 'First name is required.'; }
if (!$lastName)     { $errors[] = 'Last name is required.'; }
if (!$departmentId) { $errors[] = 'Department must be selected.'; }
if (!$role)         { $errors[] = 'Role must be selected.'; }

if ($errors) {
    if ($isAjax) {
        echo json_encode([
            'success' => false,
            'errors'  => $errors
        ]);
    } else {
        $_SESSION['flash'] = [
            'icon'  => 'error',
            'title' => 'Validation Failed',
            'text'  => implode(' ', $errors)
        ];
        header("Location: $returnUrl");
    }
    exit;
}

$emailLocal = mb_strtolower(preg_replace('/\s+/', '', "{$lastName}.{$firstName}"));
$email      = $emailLocal . '@ncst.edu.ph';

try {
    if (!$id) {
        $check = $pdo->prepare(
            "SELECT id, department_id, status
            FROM faculty_tbl
            WHERE email = :email"
        );
        $check->execute([':email' => $email]);
        $existing = $check->fetch(PDO::FETCH_ASSOC);
        if ($existing) {
            $upd = $pdo->prepare(
                "UPDATE faculty_tbl
                SET department_id = :dept,
                    role          = :role,
                    status        = 'Active'
                WHERE id = :id"
            );
            $upd->execute([
                ':dept' => $departmentId,
                ':role' => $role,
                ':id'   => $existing['id'],
            ]);
            if ($isAjax) {
                echo json_encode([
                    'success'  => true,
                    'existing' => true,
                    'message'  => 'Existing faculty reactivated or updated.'
                ]);
            } else {
                $_SESSION['flash'] = [
                    'icon'  => 'info',
                    'title' => 'Faculty Exists',
                    'text'  => 'Existing faculty reactivated or updated.'
                ];
                header("Location: $returnUrl");
            }
            exit;
        }
    }

    if (!$id) {
        $check = $pdo->prepare("
            SELECT id, department_id, status
              FROM faculty_tbl
             WHERE email = :email
        ");
        $check->execute([':email' => $email]);
        $existing = $check->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            // Reactivate or update department/role if needed
            if ((int)$existing['department_id'] !== (int)$departmentId
                || $existing['status'] === 'Archived'
            ) {
                $upd = $pdo->prepare("
                    UPDATE faculty_tbl
                       SET department_id = :dept,
                           role          = :role,
                           status        = 'Active'
                     WHERE id = :id
                ");
                $upd->execute([
                    ':dept' => $departmentId,
                    ':role' => $role,
                    ':id'   => $existing['id'],
                ]);
            }

            // Return early just like save_staff.php
            $message = 'Existing faculty reactivated or updated.';
            if ($isAjax) {
                echo json_encode([
                    'success'  => true,
                    'existing' => true,
                    'message'  => $message
                ]);
            } else {
                $_SESSION['flash'] = [
                    'icon'  => 'info',
                    'title' => 'Faculty Exists',
                    'text'  => $message
                ];
                header("Location: $returnUrl");
            }
            exit;
        }
    }

    if ($id) {
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
            ':middle' => $middleName ?: null,
            ':last'   => $lastName,
            ':suffix' => $suffix ?: null,
            ':dept'   => $departmentId,
            ':role'   => $role,
            ':id'     => $id,
        ]);

        $successMsg = 'Faculty record updated.';
    } else {
        $row = $pdo
            ->query("SELECT MAX(CAST(faculty_code AS UNSIGNED)) AS max_code FROM faculty_tbl")
            ->fetch(PDO::FETCH_ASSOC);

        $next        = $row['max_code'] ? ((int)$row['max_code'] + 1) : 1;
        $facultyCode = str_pad((string)$next, 4, '0', STR_PAD_LEFT);
        $passwordHash = password_hash($lastName, PASSWORD_DEFAULT);

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
            ':middle'   => $middleName ?: null,
            ':last'     => $lastName,
            ':suffix'   => $suffix ?: null,
            ':dept'     => $departmentId,
            ':role'     => $role,
            ':password' => $passwordHash,
        ]);

        $successMsg = 'New faculty record created.';
    }

    if ($isAjax) {
        echo json_encode([
            'success' => true,
            'message' => $successMsg
        ]);
    } else {
        $_SESSION['flash'] = [
            'icon'  => 'success',
            'title' => 'Success',
            'text'  => $successMsg
        ];
        header("Location: $returnUrl");
    }
    exit;
} catch (PDOException $e) {
    if ($isAjax) {
        echo json_encode([
            'success' => false,
            'errors'  => ['Database error: ' . $e->getMessage()]
        ]);
    } else {
        $_SESSION['flash'] = [
            'icon'  => 'error',
            'title' => 'Database Error',
            'text'  => 'Could not save faculty. Please try again.'
        ];
        header("Location: $returnUrl");
    }
    exit;
}
