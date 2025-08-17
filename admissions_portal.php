<?php
ob_start();
include __DIR__ . '/src/db/db_conn.php';
// require_once __DIR__ . '/src/lib/get_schedules.php';

session_start();
$userId = $_SESSION['user_id'];
$userDept = $_SESSION['department_id'] ?? null;

if ($userDept != 1) {
    $_SESSION = [];
    session_destroy();

    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo <<<JS
<script>
  Swal.fire({
    icon: 'error',
    title: 'Access Denied',
    text: 'You do not belong to this department.',
    timer: 2000,
    timerProgressBar: true,
    showConfirmButton: false
  }).then(() => {
    window.location.href = 'admin_login.php';
  });
</script>
JS;
    exit;
}

$schedules = fetchSchedules($pdo, $userDept);

$menuItems = [
  ['href' => '#dashboard', 'label' => 'Dashboard', 'icon' => 'layout-dashboard'],
  ['href' => '#schedules', 'label' => 'Schedules', 'icon' => 'school'],
];

$linkClasses = 'flex items-center justify-start px-0 py-1 rounded';
function inline_icon(string $name): string {
  $file = __DIR__ . "/src/assets/icons/{$name}.svg";
  if (!file_exists($file)) return '';
  $svg = file_get_contents($file);
  return preg_replace(
    '/<svg[^>]*>/i',
    '<svg class="w-6 h-6 flex-none text-gray-700" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">',
    $svg,
    1
  );
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Superadmin Access</title>
  <link rel="icon" href="src/assets/img/logo-1.png"/>
  <script src="https://cdn.tailwindcss.com"></script>
  <link
    rel="stylesheet"
    href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css"
  />
  <link rel="stylesheet" href="src/css/portals.css"/>
  <link rel="stylesheet" href="src/css/tables.css"/>
  <script src="src/js/portals.js" defer></script>

</head>
<body class="sidebar-collapsed flex h-screen bg-gray-100 overflow-hidden">
  <aside id="sidebar" class="bg-indigo-100 p-4 flex flex-col text-gray-700 shadow-lg">
    <div id="sidebar-header" class="flex items-center justify-between mb-6">
      <div class="flex items-center space-x-3">
        <img id="logo" src="src/assets/img/logo-1.png" alt="logo" class="w-8 h-8"/>
        <span class="logo-text font-semibold calsans text-indigo-700 text-lg">NCST</span>
      </div>
      <button id="toggle-btn" class="focus:outline-none w-8 h-8" aria-label="Expand or collapse sidebar">
        <svg class="w-6 h-6 m-auto text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
      </button>
      <img
        src="src/assets/img/pattern.png"
        alt="background pattern"
        class="absolute inset-0 top-[80%] z-[-1] w-full h-[60%] object-cover opacity-100
               [mask-image:linear-gradient(to_bottom,transparent,black)]
               [-webkit-mask-image:linear-gradient(to_bottom,transparent,black)]"
      />
    </div>

    <nav class="flex-1 flex flex-col justify-between text-sm">
      <ul class="space-y-2 flex-auto">
        <?php foreach ($menuItems as $item): ?>
          <li class="px-1">
            <a href="<?= $item['href'] ?>" class="<?= trim($linkClasses) ?>">
              <?= inline_icon($item['icon']) ?>
              <span class="label ml-3 hidden md:inline"><?= htmlspecialchars($item['label'], ENT_QUOTES) ?></span>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
      <div class="space-y-2 px-1">
        <a href="#" class="flex items-center justify-start px-0 py-1 rounded">
          <svg class="w-6 h-6 flex-none text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/>
          </svg>
          <span class="label ml-3 hidden md:inline">Log out</span>
        </a>
      </div>
    </nav>
  </aside>

  <div id="content" class="flex-1 flex flex-col overflow-auto">
    <header class="flex items-center justify-between bg-indigo-100 p-4 md:px-6 shadow-lg">
      <div class="flex items-center space-x-2">
        <button id="menu-btn" class="md:hidden w-8 h-8 text-gray-500 focus:outline-none transform scale-x-[-1]">
          <svg class="w-6 h-6 m-auto transition-transform duration-300 ease-in-out" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
        </button>
        <span class="text-indigo-700 text-lg font-semibold">Superadmin</span>
        <span class="text-gray-500 text-lg">|</span>
        <span class="text-gray-500 text-xs">Last login: 14:30</span>
      </div>
    </header>

    <main class="flex-1 p-6 bg-gradient-to-br from-indigo-50 to-white text-sm text-gray-700">
    <!-- ALL TABS ARE ACCESSIBLE SA SUPERADMIN -->
    <!-- wala dito yung for students<3 -->

    <!-- common tab. content changes depending on userDept, or -->
      <div id="dashboard" class="w-full">
          dashboard content here
      </div>
      <!-- admin tabs -->
      <div id="staff" class="w-full hidden"></div>
      <div id="faculty" class="w-full hidden"></div>
      <div id="rooms" class="w-full hidden"></div>
      <div id="activity-logs" class="w-full hidden"></div>
      <!-- treasury tabs -->
      <div id="payments" class="w-full hidden"></div>
      <!-- admissions -->
      <div id="applications" class="w-full hidden"></div>
      <div id="accountabilities" class="w-full hidden"></div>
      <div id="enrollment" class="w-full hidden"></div>
      <!-- college dept head -->
      <div id="schedules" class="w-full hidden">
        <div class="overflow-x-auto card shadow-lg rounded-lg mb-6 p-6 bg-white">
        <table id="schedulesTable" class="display min-w-full table table-auto">
          <thead class="w-full">
            <tr class="w-full">
              <th>#</th>
              <th>Instructor</th>
              <th>Day</th>
              <th>Time</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody class="w-full m-0 p-0">
            <?php foreach ($schedules as $row): ?>
              <tr>
                <td><?= htmlspecialchars($row['schedule_id']) ?></td>
                <td><?= htmlspecialchars($row['instructor']) ?></td>
                <td><?= htmlspecialchars($row['day']) ?></td>
                <td><?= htmlspecialchars($row['time']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div id="curriculum" class="w-full hidden"></div>
      <div id="" class="w-full hidden"></div>
      <div id="" class="w-full hidden"></div>
      <div id="sections" class="w-full hidden"></div>
      <div id="department-faculty" class="w-full hidden"></div>
    </main>
  </div>

</body>
</html>
<?php
ob_end_flush();
exit;
?>



