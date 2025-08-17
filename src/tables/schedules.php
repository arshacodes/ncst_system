<?php
// File: src/ajax/schedules.php

// 1. Start session so we can read department_id
session_start();

// 2. Tell the client we’re returning JSON
header('Content-Type: application/json');

// 3. Boot up our DB connection and shared logic
require_once __DIR__ . '/../db/db_conn.php';
require_once __DIR__ . '/../lib/get_schedules.php';

// 4. Figure out which department to fetch for
//    - superadmin if no session value (dept 1)
//    - otherwise whatever’s in the session
$userDept = (int) ($_SESSION['department_id'] ?? 1);

// 5. Fetch schedules
$schedules = fetchSchedules($pdo, $userDept);

// 6. Wrap in DataTables-friendly envelope and send
echo json_encode([
  'data' => $schedules
], JSON_UNESCAPED_UNICODE);

exit;
