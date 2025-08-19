<?php
session_start();
require __DIR__ . '/../db/db_conn.php';  // this file must set up $pdo as your PDO instance

// only Dept Heads may proceed
if (empty($_SESSION['role']) || $_SESSION['role'] !== 'Head') {
    $isAjax = (
        !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
    );
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Unauthorized']);
        exit;
    }
    $_SESSION['flash'] = [
        'icon'  => 'error',
        'title' => 'Unauthorized',
        'text'  => 'You do not have permission to save courses.'
    ];
    header('Location: ../../dept_head_portal.php');
    exit;
}

// collect & trim inputs
$deptId = $_SESSION['department_id'];
$id     = trim($_POST['id']   ?? '');
$code   = trim($_POST['code'] ?? '');
$name   = trim($_POST['name'] ?? '');

$errors = [];
if ($code === '') $errors[] = 'Course code is required.';
if ($name === '') $errors[] = 'Course name is required.';

$isAjax = (
    !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
);

if (!empty($errors)) {
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit;
    }
    $_SESSION['flash'] = [
        'icon'  => 'error',
        'title' => 'Validation Failed',
        'text'  => implode(' ', $errors)
    ];
    header('Location: ../../dept_head_portal.php');
    exit;
}

try {
    // 1) Uniqueness check
    if ($id === '') {
        $dup = $pdo->prepare("
            SELECT COUNT(*) 
              FROM courses_tbl 
             WHERE department_id = ? 
               AND code = ?
        ");
        $dup->execute([$deptId, $code]);
    } else {
        $dup = $pdo->prepare("
            SELECT COUNT(*) 
              FROM courses_tbl 
             WHERE department_id = ? 
               AND code = ? 
               AND id <> ?
        ");
        $dup->execute([$deptId, $code, $id]);
    }
    if ($dup->fetchColumn() > 0) {
        throw new Exception('Course code already exists in your department.');
    }

    // 2) Insert or Update
    if ($id === '') {
        $stmt = $pdo->prepare("
            INSERT INTO courses_tbl
                (code, name, department_id, status)
            VALUES
                (?,    ?,    ?,             'Active')
        ");
        $ok  = $stmt->execute([$code, $name, $deptId]);
        $msg = 'Course added successfully.';
    } else {
        // confirm ownership
        $chk = $pdo->prepare("
            SELECT department_id 
              FROM courses_tbl 
             WHERE id = ?
        ");
        $chk->execute([$id]);
        $row = $chk->fetch(PDO::FETCH_ASSOC);
        if (!$row || $row['department_id'] != $deptId) {
            throw new Exception('Unauthorized operation.');
        }

        $stmt = $pdo->prepare("
            UPDATE courses_tbl
               SET code = ?, name = ?
             WHERE id = ?
        ");
        $ok  = $stmt->execute([$code, $name, $id]);
        $msg = 'Course updated successfully.';
    }

    // determine course ID
    $courseId = empty($id)
      ? $pdo->lastInsertId()
      : $id;

    // clear old links
    $pdo->prepare("DELETE FROM course_subject WHERE course_id = ?")
        ->execute([$courseId]);

    // insert selected subjects
    $subs = $_POST['subjects'] ?? [];
    foreach ($subs as $sid) {
      $pdo->prepare("
        INSERT IGNORE INTO course_subject (course_id, subject_id)
        VALUES (?, ?)
      ")->execute([$courseId, intval($sid)]);
    }

    // then continue with your redirect/JSON response…


    if (!$ok) {
        throw new Exception('Database error.');
    }

    $courseId = ($id === '') 
        ? $pdo->lastInsertId() 
        : $id;

    $pdo->prepare("DELETE FROM course_subject WHERE course_id = ?")
        ->execute([$courseId]);

    // re‐insert new ones
    $subs = $_POST['subjects'] ?? [];
    foreach ($subs as $sid) {
        $pdo->prepare("
          INSERT INTO course_subject (course_id, subject_id) 
          VALUES (?, ?)
        ")->execute([$courseId, intval($sid)]);
    }


    // Success response
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => $msg]);
        exit;
    } else {
        $_SESSION['flash'] = [
            'icon'  => 'success',
            'title' => 'Saved',
            'text'  => $msg
        ];
        header('Location: ../../dept_head_portal.php');
        exit;
    }

} catch (Exception $e) {
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
    $_SESSION['flash'] = [
        'icon'  => 'error',
        'title' => 'Error',
        'text'  => $e->getMessage()
    ];
    header('Location: ../../dept_head_portal.php');
    exit;
}
