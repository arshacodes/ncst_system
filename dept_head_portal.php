<?php
date_default_timezone_set('Asia/Manila');
$currentTime = date('M j, H:i');

include __DIR__ . '/src/db/db_conn.php';

session_start();

$userDept = $_SESSION['department_id'] ?? null;

if ($_SESSION['role'] !== 'Head') {
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

// fetch active subjects, group by year
$stmt = $pdo->query("
  SELECT id, code, name, year_level
    FROM subjects_tbl
   WHERE status = 'Active'
   ORDER BY year_level, code
");
$subjectsByYear = [];
while ($s = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $subjectsByYear[$s['year_level']][] = $s;
}

// fetch mapping of subject → assigned course
$stmt2 = $pdo->query("
  SELECT subject_id, course_id
    FROM course_subject
");
$subjectCourseMap = $stmt2->fetchAll(PDO::FETCH_KEY_PAIR);


ob_start();

// Only show courses for the logged-in head’s department
$stmt = $pdo->prepare("
  SELECT id, code, name, status
    FROM courses_tbl
   WHERE department_id = ?
   ORDER BY code
");
$stmt->execute([$userDept]);
$coursesList = $stmt->fetchAll(PDO::FETCH_ASSOC);


$menuItems = [
  // ['href' => '#dashboard', 'label' => 'Dashboard', 'icon' => 'home'],
  ['href' => '#courses', 'label' => 'Courses', 'icon' => 'book-open-text'],
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
  <title>Dept Head Access</title>
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
        <span class="text-indigo-700 text-lg font-semibold me-2">Dept Head</span>
        <span class="text-gray-500 text-lg me-2">|</span>
        <span class="text-gray-500 text-xs me-2">Last login: <?= htmlspecialchars($currentTime, ENT_QUOTES, 'UTF-8') ?></span>
      </div>
    </header>

    <main class="flex-1 p-6 bg-gradient-to-br from-indigo-50 to-white text-sm text-gray-700">
      <!-- <div id="dashboard" class="w-full">
          dashboard content here
      </div> -->

      <div id="courses" class="w-full hidden">
        <div class="overflow-x-auto card shadow-lg rounded-lg mb-6 p-6 bg-white">
          <table id="coursesTable" class="display min-w-full data-table table table-auto">
            <thead>
              <tr>
                <th>Course Code</th>
                <th>Course Name</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($coursesList as $course): ?>
              <tr>
                <td><?= htmlspecialchars($course['code']) ?></td>
                <td><?= htmlspecialchars($course['name']) ?></td>
                <td><?= htmlspecialchars($course['status']) ?></td>
                <td class="space-x-2 text-center">
                  <button
                    class="text-blue-600 hover:text-blue-800"
                    title="Edit"
                    onclick="openCourseModal(<?= htmlspecialchars(json_encode($course), ENT_QUOTES) ?>)"
                  >
                    <!-- pencil icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5
                              M18.5 2.5a2.121 2.121 0 113 3L12 15 9 16 10 13 18.5 2.5z" />
                    </svg>
                  </button>
                  <button
                    onclick="confirmArchiveCourse(<?= $course['id'] ?>)"
                    class="text-red-600 hover:text-red-800"
                    title="Archive"
                  >
                    <!-- archive icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 12H4m1-7h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                    </svg>
                  </button>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <div class="flex justify-start mb-4">
          <button
            onclick="openCourseModal()"
            class="bg-indigo-800 text-white px-4 py-2 rounded-lg
                  transition-transform duration-300 hover:scale-[1.02] hover:bg-indigo-700"
          >Add New Course</button>
        </div>
      </div>

    </main>
  </div>

  <div
    id="courseModal"
    class="fixed inset-0 z-50 hidden flex items-center justify-center px-6"
  >
    <div
      class="fixed inset-0 bg-black/30 backdrop-blur-sm"
      onclick="closeCourseModal()"
    ></div>

    <div
      data-modal-panel
      class="bg-white rounded-lg drop-shadow-lg w-full max-w-lg p-6
            transform transition-all duration-500 scale-95 opacity-0 relative"
    >
      <button
        onclick="closeCourseModal()"
        class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-2xl font-bold"
      >&times;</button>

      <h2 class="text-center text-lg font-bold text-indigo-800 mb-4">
        <span id="courseModalTitle">Add New Course</span>
      </h2>
      <hr class="text-amber-500 mb-4" />

      <form id="courseForm" method="POST" action="src/functions/save_course.php">
        <!-- <input type="hidden" name="id" id="courseId" value="">
        <input type="hidden" name="department_id" value="?= $userDept ?>"> -->
        <!-- … inside <form id="courseForm" …> -->
        <input type="hidden" name="id" id="courseId" value="">
        <input type="hidden" name="department_id" id="departmentId" value="<?= $userDept ?>">

        <div class="grid grid-cols-1 gap-4">
          <label>
            <span class="block text-sm font-medium text-gray-700">Course Code</span>
            <input
              type="text"
              name="code"
              id="courseCode"
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm text-xs"
              required
            />
          </label>

          <label>
            <span class="block text-sm font-medium text-gray-700">Course Name</span>
            <input
              type="text"
              name="name"
              id="courseName"
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm text-xs"
              required
            />
          </label>
        </div>

            <!-- ========= Subjects Checklist ========= -->
            <div class="mt-4">
              <h3 class="text-sm font-medium text-gray-700 mb-2">Assign Subjects</h3>

              <div id="subject-list">
                <?php foreach ($subjectsByYear as $year => $list): ?>
                  <div id="year-group-<?= $year ?>" class="mb-3">
                    <strong>Year <?= $year ?></strong>
                    <div class="grid grid-cols-1 gap-1 ml-4 mt-1 subject-group">
                      <?php foreach ($list as $sub): ?>
                        <label class="inline-flex items-center text-xs">
                          <input
                            type="checkbox"
                            name="subjects[]"
                            value="<?= $sub['id'] ?>"
                            <?= in_array($sub['id'], $_POST['subjects'] ?? [], true) ? 'checked' : '' ?>
                            class="form-checkbox h-4 w-4 text-indigo-600"
                          >
                          <span class="ml-2">
                            <?= htmlspecialchars($sub['code'] . ' – ' . $sub['name'], ENT_QUOTES) ?>
                          </span>
                        </label>
                      <?php endforeach; ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>

              <button
                type="button"
                id="addSubjectBtn"
                class="mt-2 bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-500"
              >+ Add New Subject</button>
            </div>
            <!-- ======================================== -->


        <div class="mt-6 text-center">
          <button
            type="submit"
            class="bg-indigo-800 text-white px-4 py-2 rounded-lg
                  transition-transform duration-300 hover:scale-[1.02] hover:bg-indigo-700"
          >Save Course</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Add New Subject Modal -->
  <div id="subjectModal" class="fixed inset-0 z-50 hidden flex items-center justify-center px-6">
    <div class="fixed inset-0 bg-black/30" onclick="closeSubjectModal()"></div>
    <div data-modal-panel class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 transform scale-95 opacity-0 transition-all duration-300">
      <button onclick="closeSubjectModal()" class="absolute top-3 right-3 text-gray-500 text-lg">&times;</button>
      <h2 class="text-lg font-bold text-indigo-800 mb-4">Add New Subject</h2>

      <form id="newSubjectForm" action="src/functions/create_subject.php" method="POST">
        <input type="hidden" name="course_id" id="newSubCourseId" value="">
        <div class="space-y-3">
          <label class="block text-sm">Code
            <input type="text" name="code" class="mt-1 block w-full border px-2 py-1 rounded text-xs" required>
          </label>
          <label class="block text-sm">Name
            <input type="text" name="name" class="mt-1 block w-full border px-2 py-1 rounded text-xs" required>
          </label>
          <label class="block text-sm">Units
            <input type="number" name="units" min="0" class="mt-1 block w-full border px-2 py-1 rounded text-xs">
          </label>
          <label class="inline-flex items-center text-sm">
            <input type="checkbox" name="with_lab" value="1" class="form-checkbox">
            <span class="ml-2">With Lab</span>
          </label>
          <label class="block text-sm">Year Level
            <select name="year_level" class="mt-1 block w-full border px-2 py-1 rounded text-xs" required>
              <option value="">Select year</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
            </select>
          </label>
        </div>
        <div class="mt-6 text-center">
          <button type="submit" class="bg-indigo-800 text-white px-4 py-2 rounded hover:bg-indigo-700">Save Subject</button>
        </div>
      </form>
    </div>
  </div>


  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
  <script>
    // 1) Inject the PHP‐built map
    const subjectCourseMap = <?= json_encode($subjectCourseMap, JSON_PRETTY_PRINT) ?>;

    // 2) Grab all modal elements, panels, forms, buttons
    const courseModal     = document.getElementById('courseModal');
    const coursePanel     = courseModal.querySelector('[data-modal-panel]');
    const subjectModal    = document.getElementById('subjectModal');
    const subjectPanel    = subjectModal.querySelector('[data-modal-panel]');
    const courseForm      = document.getElementById('courseForm');
    const newSubjectForm  = document.getElementById('newSubjectForm');
    const addSubjectBtn   = document.getElementById('addSubjectBtn');

    // 3) Show/Hide helpers
    function showCourseModal() {
      courseModal.classList.remove('hidden');
      setTimeout(() => {
        coursePanel.classList.replace('scale-95','scale-100');
        coursePanel.classList.replace('opacity-0','opacity-100');
      }, 20);
    }
    function closeCourseModal() {
      coursePanel.classList.replace('scale-100','scale-95');
      coursePanel.classList.replace('opacity-100','opacity-0');
      setTimeout(() => courseModal.classList.add('hidden'), 200);
    }

    function showSubjectModal() {
      subjectModal.classList.remove('hidden');
      setTimeout(() => {
        subjectPanel.classList.replace('scale-95','scale-100');
        subjectPanel.classList.replace('opacity-0','opacity-100');
      }, 20);
    }
    function closeSubjectModal() {
      subjectPanel.classList.replace('scale-100','scale-95');
      subjectPanel.classList.replace('opacity-100','opacity-0');
      setTimeout(() => subjectModal.classList.add('hidden'), 200);
    }

    // 4) Expose for your inline buttons
    window.openCourseModal      = openCourseModal;
    window.closeCourseModal     = closeCourseModal;
    window.openSubjectModal     = showSubjectModal;
    window.closeSubjectModal    = closeSubjectModal;
    window.confirmArchiveCourse = confirmArchiveCourse;

    // 5) Core logic
    function openCourseModal(courseData) {
      // 5a) Prefill your form fields
      document.getElementById('courseId').value     = courseData.id;
      document.getElementById('courseCode').value   = courseData.code;
      document.getElementById('courseName').value   = courseData.name;
      document.getElementById('departmentId').value = courseData.deptId;
      // …any other inputs you have…

      // 5b) Show only subjects valid for this course
      filterSubjectCheckboxes(courseData.id);

      // 5c) Finally display it
      showCourseModal();
    }

    function filterSubjectCheckboxes(courseId) {
      document.querySelectorAll('#subject-list label').forEach(lbl => {
        const chk      = lbl.querySelector('input[type="checkbox"]');
        const sid      = chk.value;
        const assigned = subjectCourseMap[sid] ?? null;

        if (assigned === null || Number(assigned) === Number(courseId)) {
          lbl.style.display = '';
        } else {
          lbl.style.display = 'none';
          chk.checked       = false;
        }
      });

      // hide empty year groups
      document.querySelectorAll('#subject-list > div').forEach(group => {
        const hasVisible = group.querySelector('label:not([style*="display: none"])');
        group.style.display = hasVisible ? '' : 'none';
      });
    }

    function confirmArchiveCourse(id) {
      Swal.fire({
        title: 'Are you sure?',
        text: 'This will archive the course permanently.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, archive it'
      })
      .then(result => {
        if (result.isConfirmed) {
          window.location.href = `src/functions/archive_course.php?id=${id}`;
        }
      });
    }

    // 6) Event listeners
    // 6a) Overlay clicks
    courseModal.querySelector('.fixed').addEventListener('click', closeCourseModal);
    subjectModal.querySelector('.fixed').addEventListener('click', closeSubjectModal);

    // 6b) “Add New Subject” button
    addSubjectBtn.addEventListener('click', () => {
      // inject current course ID
      document.getElementById('newSubCourseId').value =
        document.getElementById('courseId').value;
      showSubjectModal();
    });

    // 6c) AJAX submit for new subject
    newSubjectForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const fd = new FormData(this);
      fetch(this.action, {
        method: 'POST',
        body: fd,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(r => r.json())
      .then(res => {
        if (!res.success) throw new Error(res.error || 'Server error');
        const s = res.data;
        // update map
        subjectCourseMap[s.id] = s.course_id;
        // inject into the right year‐group
        let grp = document.querySelector('#year-group-' + s.year_level + ' .subject-group');
        if (!grp) {
          const cont = document.getElementById('subject-list');
          const wrapper = document.createElement('div');
          wrapper.id = 'year-group-' + s.year_level;
          wrapper.className = 'mb-3';
          wrapper.innerHTML = `
            <strong>Year ${s.year_level}</strong>
            <div class="grid grid-cols-1 gap-1 ml-4 mt-1 subject-group"></div>
          `;
          cont.appendChild(wrapper);
          grp = wrapper.querySelector('.subject-group');
        }
        const lbl = document.createElement('label');
        lbl.className = 'inline-flex items-center text-xs';
        lbl.innerHTML = `
          <input type="checkbox" name="subjects[]" value="${s.id}"
                class="form-checkbox h-4 w-4 text-indigo-600" checked>
          <span class="ml-2">${s.code} – ${s.name}</span>
        `;
        grp.appendChild(lbl);

        closeSubjectModal();
        this.reset();
      })
      .catch(err => Swal.fire('Error', err.message, 'error'));
    });
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