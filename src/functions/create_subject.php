<?php
require __DIR__ . '/../db/db_conn.php';

// Only allow AJAX requests
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    http_response_code(400);
    exit(json_encode(['success' => false, 'error' => 'Invalid request']));
}

// Sanitize inputs
$code       = trim($_POST['code'] ?? '');
$name       = trim($_POST['name'] ?? '');
$units      = intval($_POST['units'] ?? 0);
$with_lab   = isset($_POST['with_lab']) ? 1 : 0;
$yearLevel  = intval($_POST['year_level'] ?? 0);
$courseId   = intval($_POST['course_id'] ?? 0);

try {
    $pdo->beginTransaction();

    // 1) Insert into subjects table
    $insertSub = $pdo->prepare("
      INSERT INTO subjects_tbl
        (code, name, units, with_lab, year_level, status)
      VALUES
        (?, ?, ?, ?, ?, 'Active')
    ");
    $insertSub->execute([
      $code, $name, $units, $with_lab, $yearLevel
    ]);
    $subId = $pdo->lastInsertId();

    // 2) Map subject to course
    $map = $pdo->prepare("
      INSERT INTO course_subject (subject_id, course_id)
      VALUES (?, ?)
    ");
    $map->execute([$subId, $courseId]);

    $pdo->commit();

    // Return the newly created record
    echo json_encode([
      'success' => true,
      'data'    => [
        'id'         => $subId,
        'code'       => $code,
        'name'       => $name,
        'year_level' => $yearLevel,
        'course_id'  => $courseId
      ]
    ]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode([
      'success' => false,
      'error'   => $e->getMessage()
    ]);
}
