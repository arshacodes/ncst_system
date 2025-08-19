<?php

include 'src/db/db_conn.php';
$deptId = (int) $_SESSION['department_id'] ?? 1;

if ($deptId === 1) {
    $insSql = "SELECT id, CONCAT(f_name,' ',l_name) AS name FROM faculty_tbl";
    $instructors = $pdo->query($insSql)->fetchAll();
} else {
    $insSql = "
      SELECT id, CONCAT(f_name,' ',l_name) AS name
        FROM faculty_tbl
       WHERE department_id = :dept
    ";
    $stmt = $pdo->prepare($insSql);
    $stmt->execute([':dept' => $deptId]);
    $instructors = $stmt->fetchAll();
}

$days      = $pdo->query("SELECT id,name FROM days_tbl")->fetchAll();
$timeslots = $pdo->query("SELECT id,start_time,end_time FROM timeslots_tbl")
                  ->fetchAll();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $iId  = (int) ($_POST['instructor_id'] ?? 0);
    $dId  = (int) ($_POST['day_id'] ?? 0);
    $tId  = (int) ($_POST['timeslot_id'] ?? 0);

    if ($iId && $dId && $tId) {
        $ins = "
          INSERT INTO instructors_busy_tbl
            (instructor_id, day_id, timeslot_id, status)
          VALUES (:i,:d,:t,'Active')
        ";
        $stmt = $pdo->prepare($ins);
        $stmt->execute([
          ':i' => $iId,
          ':d' => $dId,
          ':t' => $tId
        ]);
        header('Location: src/portals/actions/schedules.php');
        exit;
    } else {
        $error = 'Please select all fields.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Add Busy Slot</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-6 bg-gray-50">
  <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-xl mb-4">Add Busy Slot</h1>

    <?php if ($error): ?>
      <div class="bg-red-100 text-red-700 p-2 mb-4 rounded">
        <?=htmlspecialchars($error)?>
      </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
      <label class="block">
        <span>Instructor</span>
        <select name="instructor_id" required
                class="mt-1 block w-full border-gray-300 rounded">
          <option value="">— choose —</option>
          <?php foreach($instructors as $ins): ?>
            <option value="<?=$ins['id']?>">
              <?=htmlspecialchars($ins['name'])?>
            </option>
          <?php endforeach;?>
        </select>
      </label>

      <label class="block">
        <span>Day</span>
        <select name="day_id" required class="mt-1 block w-full border-gray-300 rounded">
          <option value="">— choose —</option>
          <?php foreach($days as $d): ?>
            <option value="<?=$d['id']?>"><?=htmlspecialchars($d['name'])?></option>
          <?php endforeach;?>
        </select>
      </label>

      <label class="block">
        <span>Timeslot</span>
        <select name="timeslot_id" required class="mt-1 block w-full border-gray-300 rounded">
          <option value="">— choose —</option>
          <?php foreach($timeslots as $t): ?>
            <option value="<?=$t['id']?>">
              <?=substr($t['start_time'],0,5)?> – <?=substr($t['end_time'],0,5)?>
            </option>
          <?php endforeach;?>
        </select>
      </label>

      <div class="flex justify-end">
        <a href="src/portals/actions/schedules.php"
           class="mr-2 text-gray-600 hover:underline">
          Cancel
        </a>
        <button
          type="submit"
          class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700"
        >
          Save
        </button>
      </div>
    </form>
  </div>
</body>
</html>
