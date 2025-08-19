<?php
date_default_timezone_set('Asia/Manila');
$currentTime = date('M j, H:i');

include __DIR__ . '/src/db/db_conn.php';

session_start();

$sql = "
  SELECT 
    a.id,
    a.f_name,
    a.m_name,
    a.l_name,
    a.suffix,
    a.email,
    c.name AS course_name,
    a.year_level,
    a.status
  FROM applicants_tbl AS a
  LEFT JOIN courses_tbl    AS c
    ON a.course_id = c.id
  ORDER BY a.l_name, a.f_name
";
$applicantList = $pdo
  ->query($sql)
  ->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
  SELECT id, name
    FROM departments_tbl
   WHERE department_type = :deptType
   ORDER BY name
");
$stmt->execute([
  ':deptType' => 'Academic'
]);
$academicDepts = $stmt->fetchAll(PDO::FETCH_ASSOC);


$stmt = $pdo->prepare("
  SELECT id, name
    FROM departments_tbl
   WHERE department_type = :deptType
   ORDER BY name
");
$stmt->execute([
  ':deptType' => 'Non-Academic'
]);
$nonAcademicDepts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$userDept = $_SESSION['department_id'] ?? null;

if ($userDept !== 3) {
    echo <<<HTML
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Access Denied</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="src/css/portals.css"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>
  <body class="poppins">
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Access Denied',
        text: 'You do not belong to this department.',
        timer:  3000,
        timerProgressBar: true,
        showConfirmButton: false
      }).then(() => {
        window.location.href = 'employee_login.php';
      });
    </script>
  </body>
  </html>
  HTML;
      exit;
}

$userId         = $_SESSION['user_id'];
$userDept       = $_SESSION['department_id'];
$userInitials   = $_SESSION['user_initials'] ?? '';
ob_start();

$menuItems = [
  // ['href' => '#dashboard', 'label' => 'Dashboard', 'icon' => 'home'],
  ['href' => '#applicants', 'label' => 'Applicants', 'icon' => 'users'],
  // ['href' => '#faculty', 'label' => 'Faculty', 'icon' => 'users']
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
  <title>Registrar Access</title>
  <link rel="icon" href="src/assets/img/logo-1.png"/>
  <script src="https://cdn.tailwindcss.com"></script>
  <link
    rel="stylesheet"
    href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css"
  />
  <link rel="stylesheet" href="src/css/portals.css"/>
  <link rel="stylesheet" href="src/css/tables.css"/>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
  <script src="src/js/portals.js" defer></script>
</head>
<body class="sidebar-collapsed flex h-screen bg-gray-100 overflow-hidden">
  <aside id="sidebar" class="bg-indigo-100 p-4 flex flex-col text-gray-700 shadow-lg h-screen">
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
        class="absolute inset-0 top-[80%] z-[-1] w-full h-[20%] object-cover opacity-50
               [mask-image:linear-gradient(to_bottom,transparent,black)]
               [-webkit-mask-image:linear-gradient(to_bottom,transparent,black)]"
      />
    </div>

    <nav class="flex-1 flex flex-col justify-between text-sm">
    <ul class="space-y-2 flex-auto">
      <?php foreach ($menuItems as $item): 
        $target = ltrim($item['href'], '#');
      ?>
        <li class="px-1">
          <a
            href="<?= htmlspecialchars($item['href'], ENT_QUOTES) ?>"
            data-target="<?= $target ?>"
            class="<?= trim($linkClasses) ?>"
          >
            <?= inline_icon($item['icon']) ?>
            <span class="label ml-3 hidden md:inline">
              <?= htmlspecialchars($item['label'], ENT_QUOTES) ?>
            </span>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
    <div class="space-y-2 px-1">
      <a href="#" class="flex items-center justify-start px-0 py-1 rounded">
        <div class="w-6 h-6 p-0 m-0 rounded-full bg-indigo-700 text-white text-center justify-center flex flex-col"><span><?= $userInitials ?></span></div>
        <span class="label ml-3 hidden md:inline">Account</span>
      </a>
      <a href="src/functions/logout.php" class="flex items-center justify-start px-0 py-1 rounded">
        <svg class="w-6 h-6 flex-none text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/>
        </svg>
        <span class="label ml-3 hidden md:inline">Log out</span>
      </a>
    </div>
  </nav>
  </aside>

  <div id="content" class="flex-1 flex flex-col overflow-auto h-screen">
    <header class="flex items-center justify-between bg-indigo-100 p-4 md:px-6 shadow-lg">
      <div class="flex items-center">
        <button id="menu-btn" class="md:hidden me-2 w-8 h-8 text-gray-500 focus:outline-none transform scale-x-[-1]">
          <svg class="w-6 h-6 m-auto transition-transform duration-300 ease-in-out" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
        </button>
        <span class="text-indigo-700 text-lg font-semibold me-2">Admin</span>
        <span class="text-gray-500 text-lg me-2">|</span>
        <span class="text-gray-500 text-xs me-2">Last login: <?= htmlspecialchars($currentTime, ENT_QUOTES, 'UTF-8') ?></span>
      </div>
    </header>

    <main class="flex-1 p-6 bg-gradient-to-br from-indigo-50 to-white text-sm text-gray-700">
      <!-- <div id="dashboard" class="w-full">
          dashboard content here
      </div> -->

      <div id="applicants" class="w-full">
        <div class="overflow-x-auto card shadow-lg rounded-lg mb-6 p-6 bg-white">
          <table
            id="applicantTable"
            class="display min-w-full data-table stripe hover w-full"
          >
            <thead>
              <tr>
                <th>Full Name</th>
                <th>Email</th>
                <th>Course</th>
                <th>Year Level</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($applicantList as $row): ?>
              <tr>
                <!-- Full Name -->
                <td>
                  <?=
                    htmlspecialchars(
                      $row['f_name']
                      . ' '
                      . ($row['m_name']
                          ? substr($row['m_name'], 0, 1) . '. '
                          : ''
                        )
                      . $row['l_name']
                      . ($row['suffix']
                          ? ', ' . $row['suffix']
                          : ''
                        )
                    )
                  ?>
                </td>

                <!-- Email -->
                <td>
                  <?= htmlspecialchars($row['email']) ?>
                </td>

                <!-- Course -->
                <td>
                  <?= htmlspecialchars($row['course_name']) ?>
                </td>

                <!-- Year Level -->
                <td>
                  <?= htmlspecialchars($row['year_level']) ?>
                </td>

                <!-- Status -->
                <td>
                  <?= htmlspecialchars($row['status']) ?>
                </td>

                <!-- Actions -->
                <td class="space-x-2 text-center">
                  <button
                    class="text-indigo-600 hover:text-indigo-800"
                    title="Update Status"
                    onclick="openApplicantModal(<?= htmlspecialchars(
                      json_encode($row), ENT_QUOTES
                    ) ?>)"
                  >
                    <!-- pencil icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5
                              M18.5 2.5a2.121 2.121 0 113 3L12 15l-3 .5 1-3 8.5-8.5z" />
                    </svg>
                  </button>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <!-- if you ever want an “Add New Applicant” button -->
        <!-- <div class="flex justify-start mb-4">
          <button
            onclick="openApplicantModal()"
            class="bg-indigo-800 text-white px-4 py-2 rounded-lg
                  transition-transform duration-300 hover:scale-[1.02] hover:bg-indigo-700"
          >
            Add New Applicant
          </button>
        </div> -->
      </div>
    </main>
  </div>

  <div
    id="facultyModal"
    class="fixed inset-0 z-50 hidden flex items-center justify-center px-6"
  >
    <div
      class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity duration-500"
      onclick="closeFacultyModal()"
    ></div>
    <div
      data-modal-panel
      class="bg-white rounded-lg drop-shadow-lg w-full max-w-lg p-6
            transform transition-all duration-500 scale-95 opacity-0 relative"
    >
      <button
        onclick="closeFacultyModal()"
        class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-2xl font-bold"
      >&times;</button>

      <h2 class="text-center text-lg font-bold text-indigo-800 mb-4">
        <span id="facultyModalTitle">Add New Faculty</span>
      </h2>
      <hr class="text-amber-500 mb-4" />

      <form id="facultyForm" method="POST" action="src/functions/save_faculty.php">
        <input type="hidden" name="id" id="facultyId" value="">

        <div class="grid grid-cols-1 gap-2">
          <label>
            <span class="block text-sm font-medium text-gray-700">First Name</span>
            <input
              type="text"
              name="f_name"
              id="facultyFName"
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
              required
            />
          </label>
          <label>
            <span class="block text-sm font-medium text-gray-700">Middle Name</span>
            <input
              type="text"
              name="m_name"
              id="facultyMName"
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
            />
          </label>
          <label>
            <span class="block text-sm font-medium text-gray-700">Last Name</span>
            <input
              type="text"
              name="l_name"
              id="facultyLName"
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
              required
            />
          </label>
          <label>
            <span class="block text-sm font-medium text-gray-700">Suffix</span>
            <input
              type="text"
              name="suffix"
              id="facultySuffix"
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
            />
          </label>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
            <label>
              <span class="block text-sm font-medium text-gray-700">Department</span>
              <select
                name="department_id"
                id="facultyDept"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                required
              >
                <option value="">Select Department</option>
                <?php foreach ($academicDepts as $dept): ?>
                  <option value="<?= $dept['id'] ?>">
                    <?= htmlspecialchars($dept['name']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </label>
            <label>
              <span class="block text-sm font-medium text-gray-700">Role</span>
              <select
                name="role"
                id="facultyRole"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                required
              >
                <option value="Employee">Employee</option>
                <option value="Head">Head</option>
              </select>
            </label>
          </div>
        </div>

        <div class="mt-6 text-center">
          <button
            type="submit"
            class="bg-indigo-800 text-white px-4 py-2 rounded-lg
                  transition-transform duration-300 hover:scale-[1.02] hover:bg-indigo-700"
          >Save</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

  <script>
    // const deptMap = {
    //   <?php foreach($academicDepts as $d): ?>
    //     "<?= $d['id'] ?>": "<?= addslashes($d['name']) ?>",
    //   <?php endforeach; ?>
    // };

    // const nonAcadDeptMap = {
    //   <?php foreach($nonAcademicDepts as $d): ?>
    //     "<?= $d['id'] ?>": "<?= addslashes($d['name']) ?>",
    //   <?php endforeach; ?>
    // };

    // $(document).ready(function() {
    //   const base    = '<?= rtrim(dirname($_SERVER['SCRIPT_NAME']), "/") ?>';

    //   const fmt = new Intl.DateTimeFormat('en-US', {
    //     timeZone: 'Asia/Manila',
    //     month:   'short',
    //     day:     'numeric',
    //     hour:    '2-digit',
    //     minute:  '2-digit',
    //     hour12:  false
    //   });
    //   document.getElementById('lastLogin').textContent = fmt.format(new Date());
    // });

    // const staffModal   = document.getElementById('staffModal');
    // const staffPanel   = staffModal.querySelector('[data-modal-panel]');
    // const staffTitle   = document.getElementById('staffModalTitle');
    // const staffForm    = document.getElementById('staffForm');
    // const staffInputs  = {
    //   id:      document.getElementById('staffId'),
    //   f_name:  document.getElementById('staffFName'),
    //   m_name:  document.getElementById('staffMName'),
    //   suffix:  document.getElementById('staffSuffix'),
    //   l_name:  document.getElementById('staffLName'),
    //   dept:    document.getElementById('staffDept'),
    // };

    // function openStaffModal(staff = null) {
    //   staffForm.reset();
    //   staffInputs.id.value = '';

    //   if (staff) {
    //     staffTitle.textContent         = 'Edit Staff';
    //     staffInputs.id.value           = staff.id;
    //     staffInputs.f_name.value       = staff.f_name;
    //     staffInputs.m_name.value       = staff.m_name || '';
    //     staffInputs.suffix.value       = staff.suffix  || '';
    //     staffInputs.l_name.value       = staff.l_name;
    //     staffInputs.dept.value         = staff.department_id;
    //   } else {
    //     staffTitle.textContent = 'Add New Staff';
    //   }

    //   staffModal.classList.remove('hidden');

    //   setTimeout(() => {
    //     staffPanel.classList.remove('scale-95', 'opacity-0');
    //     staffPanel.classList.add   ('scale-100','opacity-100');
    //   }, 10);
    // }

    // function closeStaffModal() {
    //   staffPanel.classList.remove('scale-100','opacity-100');
    //   staffPanel.classList.add   ('scale-95','opacity-0');
    //   setTimeout(() => staffModal.classList.add('hidden'), 200);
    // }

    // function confirmArchiveStaff(id) {
    //   Swal.fire({
    //     icon: 'warning',
    //     title: 'Archive Staff?',
    //     text: 'This action cannot be undone.',
    //     showCancelButton: true,
    //     confirmButtonText: 'Yes, archive',
    //     cancelButtonText: 'Cancel'
    //   }).then((result) => {
    //     if (result.isConfirmed) {
    //       window.location.href = `src/functions/archive_staff.php?id=${id}`;
    //     }
    //   });
    // }

    // staffModal.querySelector('.fixed').addEventListener('click', closeStaffModal);

    // const facultyModal = document.getElementById('facultyModal');
    // const panel        = facultyModal.querySelector('[data-modal-panel]');
    // const titleSpan    = document.getElementById('facultyModalTitle');
    // const form         = document.getElementById('facultyForm');
    // const inputs       = {
    //   id:     document.getElementById('facultyId'),
    //   f_name: document.getElementById('facultyFName'),
    //   m_name: document.getElementById('facultyMName'),
    //   l_name: document.getElementById('facultyLName'),
    //   suffix: document.getElementById('facultySuffix'),
    //   dept:   document.getElementById('facultyDept'),
    //   role:   document.getElementById('facultyRole')
    // };

    // function openFacultyModal(faculty = null) {
    //   form.reset();
    //   inputs.id.value = '';

    //   if (faculty) {
    //     titleSpan.textContent = 'Edit Faculty';
    //     inputs.id.value      = faculty.id;
    //     inputs.f_name.value  = faculty.f_name;
    //     inputs.m_name.value  = faculty.m_name || '';
    //     inputs.l_name.value  = faculty.l_name;
    //     inputs.suffix.value  = faculty.suffix || '';
    //     inputs.role.value    = faculty.role;

    //     if (inputs.dept.querySelector(`option[value="${faculty.department_id}"]`)) {
    //       inputs.dept.value = faculty.department_id;
    //     } else {
    //       console.warn(
    //         `Dept ID ${faculty.department_id} not found; available IDs:`,
    //         Object.keys(deptMap)
    //       );
    //       inputs.dept.value = ''; // fallback to placeholder
    //     }
    //   }
    //   else {
    //     titleSpan.textContent = 'Add New Faculty';
    //   }

    //   facultyModal.classList.remove('hidden');
    //   setTimeout(() => {
    //     panel.classList.remove('scale-95','opacity-0');
    //     panel.classList.add('scale-100','opacity-100');
    //   }, 20);
    // }

    // function closeFacultyModal() {
    //   panel.classList.remove('scale-100','opacity-100');
    //   panel.classList.add('scale-95','opacity-0');
    //   setTimeout(() => facultyModal.classList.add('hidden'), 200);
    // }

    // function confirmArchive(id) {
    //   Swal.fire({
    //     icon: 'warning',
    //     title: 'Archive Faculty?',
    //     text: 'This cannot be undone.',
    //     showCancelButton: true,
    //     confirmButtonText: 'Yes, archive',
    //     cancelButtonText: 'Cancel'
    //   }).then(result => {
    //     if (result.isConfirmed) {
    //       window.location.href = `src/functions/archive_faculty.php?id=${id}`;
    //     }
    //   });
    // }

    // $(function() {
    //   const requiredFields = ['f_name','l_name','department_id'];

    //   $('#staffForm').on('submit', function(e) {
    //     e.preventDefault();
    //     const form = this;
    //     let missing = [];

    //     requiredFields.forEach(name => {
    //       const el = form.querySelector(`[name="${name}"]`);
    //       el.classList.remove('border-red-500','ring','ring-red-500');
    //     });

    //     requiredFields.forEach(name => {
    //       const el = form.querySelector(`[name="${name}"]`);
    //       el.value = el.value.trim();
    //       if (!el.value) {
    //         missing.push(name);
    //         el.classList.add('border-red-500','ring','ring-red-500');
    //       }
    //     });

    //     if (missing.length) {
    //       return Swal.fire({
    //         icon: 'error',
    //         title: 'Missing Fields',
    //         text: 'Please fill out all required fields.',
    //         timer:  3000,
    //         timerProgressBar: true,
    //         showConfirmButton: false
    //       });
    //     }

    //     $.post(form.action, $(form).serialize(), null, 'json')
    //       .done(res => {
    //         if (res.success) {
    //           closeStaffModal();
    //           Swal.fire({
    //             icon: 'success',
    //             title: res.message || 'Staff saved',
    //             timer:  3000,
    //             timerProgressBar: true,
    //             showConfirmButton: false
    //           });
    //           location.reload();
    //         } else {
    //           Swal.fire({
    //             icon: 'error',
    //             title: 'Error',
    //             text: res.error || 'Unable to save staff.',
    //             timer:  3000,
    //             timerProgressBar: true,
    //             showConfirmButton: false
    //           });
    //         }
    //       })
    //       .fail(() => {
    //         Swal.fire({
    //           icon: 'error',
    //           title: 'Server Error',
    //           text: 'Could not reach the server.',
    //           timer:  3000,
    //           timerProgressBar: true,
    //           showConfirmButton: false
    //         });
    //       });
    //   });

    //   requiredFields.forEach(name => {
    //     $('#staffForm').on('input change', `[name="${name}"]`, function() {
    //       $(this).removeClass('border-red-500 ring ring-red-500');
    //     });
    //   });
    // });

  </script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    // $(function() {
    //   $('#staffForm').on('submit', function(e) {
    //     e.preventDefault();
    //     const form = this;
    //     let missing = [];
    //     ['f_name','l_name','department_id'].forEach(name => {
    //       const el = form.querySelector(`[name="${name}"]`);
    //       el.value = el.value.trim();
    //       if (!el.value) missing.push(name);
    //     });
    //     if (missing.length) {
    //       return showAlert({
    //         icon: 'error',
    //         title: 'Missing Fields',
    //         text: 'Please fill out all required fields.'
    //       });
    //     }

    //     $.post(form.action, $(form).serialize(), null, 'json')
    //     .done(resp => {})
    //     .fail(() => {
    //       showAlert({
    //         icon: 'error',
    //         title: 'Network Error',
    //         text: 'Could not reach server.',
    //         timer: 3000
    //       });
    //     });
    //   });

    //   $('#facultyForm').on('submit', function(e) {
    //     e.preventDefault();
    //     const form = this;
    //     let missing = [];
    //     ['f_name','l_name','department_id','role'].forEach(name => {
    //       const el = form.querySelector(`[name="${name}"]`);
    //       el.classList.remove('border-red-500','ring','ring-red-500');
    //       el.value = el.value.trim();
    //       if (!el.value) {
    //         missing.push(name);
    //         el.classList.add('border-red-500','ring','ring-red-500');
    //       }
    //     });
    //     if (missing.length) {
    //       return Swal.fire({
    //         icon: 'error',
    //         title: 'Missing Fields',
    //         text: 'Please fill out all required fields.',
    //         timer: 3000,
    //         timerProgressBar: true,
    //         showConfirmButton: false
    //       });
    //     }
    //     $.post(form.action, $(form).serialize(), null, 'json')
    //       .done(res => {
    //         if (res.success) {
    //           closeFacultyModal();
    //           Swal.fire({
    //             icon: 'success',
    //             title: res.message || 'Faculty saved',
    //             timer: 3000,
    //             timerProgressBar: true,
    //             showConfirmButton: false
    //           });
    //           location.reload();
    //         } else {
    //           Swal.fire({
    //             icon: 'error',
    //             title: 'Error',
    //             text: Array.isArray(res.errors) ? res.errors.join(', ') : res.error || 'Unable to save faculty.',
    //             timer: 3000,
    //             timerProgressBar: true,
    //             showConfirmButton: false
    //           });
    //         }
    //       })
    //       .fail(() => {
    //         Swal.fire({
    //           icon: 'error',
    //           title: 'Network Error',
    //           text: 'Could not reach server.',
    //           timer: 3000,
    //           timerProgressBar: true,
    //           showConfirmButton: false
    //         });
    //       });
    //   });

    //   ['f_name','l_name','department_id','role'].forEach(name => {
    //     $('#facultyForm').on('input change', `[name="${name}"]`, function() {
    //       $(this).removeClass('border-red-500 ring ring-red-500');
    //     });
    //   });
    // });
  </script>
</body>
<?php if (!empty($_SESSION['flash'])): ?>
  <script>
    Swal.fire({
      icon:   <?= json_encode($_SESSION['flash']['icon'])  ?>,
      title:  <?= json_encode($_SESSION['flash']['title']) ?>,
      text:   <?= json_encode($_SESSION['flash']['text'])  ?>,
      timer:  3000,
      timerProgressBar: true,
      showConfirmButton: false
    });
  </script>
  <?php unset($_SESSION['flash']); ?>
<?php endif; ?>
</html>
<?php
ob_end_flush();
exit;
?>