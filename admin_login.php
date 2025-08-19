<?php
session_start();
require_once __DIR__ . '/src/db/db_conn.php';

$error    = '';
$email    = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $error = 'Enter both email and password.';
    } else {
        $sql  = "SELECT id, email, password, department_id,
                  UPPER(
                    CONCAT_WS(
                      '',
                      SUBSTR(f_name, 1, 1),
                      SUBSTR(l_name, 1, 1)
                    )
                  ) AS initials
                  FROM staff_tbl
                  WHERE email = ? AND status = 'Active' AND department_id IN (1, 2)
                  LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id']        = $user['id'];
            $_SESSION['email']          = $user['email'];
            $_SESSION['department_id']  = $user['department_id'];
            $_SESSION['user_initials']  = $user['initials'];

            if ($user['department_id'] === 1) {
                header('Location: superadmin_portal.php');
            } else {
                header('Location: admin_portal.php');
            }
            exit;
        }

        $error = 'Invalid credentials or not authorized.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>NCST Education System | Admin Login</title>
  <link rel="icon" href="src/assets/img/logo-1.png"/>
  <script src="https://cdn.tailwindcss.com"></script>
  <link
    rel="stylesheet"
    href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css"
  />
  <link rel="stylesheet" href="src/css/login.css"/>
  <link rel="stylesheet" href="src/css/tables.css"/>
  <script src="src/js/login.js" defer></script>
</head>
<body class="flex h-screen bg-gray-100 overflow-hidden">
  <div id="content" class="flex-1 flex flex-col overflow-auto text-sm">
    <main
      class="flex-1 items-center justify-center bg-gradient-to-br from-indigo-50 to-white text-sm text-gray-700"
    >
      <div
        id="login"
        class="w-full h-screen flex items-center justify-center p-6"
      >
        <div class="card shadow-lg rounded-lg bg-white flex flex-row max-w-2xl">
          <!-- Left-side image -->
          <div class="flex-1 hidden md:block">
            <img
              src="src/assets/img/students.jpg"
              alt="background pattern"
              class="w-full h-full object-cover opacity-100 rounded-l-lg
                     [mask-image:linear-gradient(to_bottom,black,transparent)]
                     [-webkit-mask-image:linear-gradient(to_bottom,black,transparent)]"
            />
          </div>

          <!-- Right-side form -->
          <div class="p-6 flex-1">
            <div class="mb-4">
              <img
                src="src/assets/img/logo-2.png"
                alt="logo"
                class="w-16 h-16 mx-auto"
              />
              <p class="text-center text-lg text-indigo-700 calsans">
                NCST
              </p>
              <p class="text-center text-sm text-gray-700">
                Admin Login
              </p>
            </div>

            <!-- Inline PHP error message -->
            <?php if ($error): ?>
              <div class="mb-4 text-red-600 text-xs">
                <?= htmlspecialchars($error) ?>
              </div>
            <?php endif; ?>

            <form method="POST" novalidate>
              <div class="mb-4">
                <div class="mb-2">
                  <label
                    for="email"
                    class="block text-xs font-medium text-gray-700"
                  >
                    Email
                  </label>
                  <input
                    type="email"
                    id="email"
                    name="email"
                    required
                    value="<?= htmlspecialchars($email) ?>"
                    class="mt-2 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                           focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                  />
                </div>

                <div>
                  <label
                    for="password"
                    class="block text-xs font-medium text-gray-700"
                  >
                    Password
                  </label>
                  <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    class="mt-2 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                           focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                  />
                </div>
              </div>

              <button
                type="submit"
                class="bg-indigo-700 text-white font-bold py-2 md:py-3 px-4 w-full rounded hover:bg-indigo-800 disabled:opacity-50"
              >
                Login
              </button>
            </form>
          </div>
        </div>
      </div>
    </main>
  </div>

  <!-- Flash via SweetAlert2 -->
  <?php if (!isset($noLoginFlash) && !empty($_SESSION['flash'])): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      Swal.fire({
        icon:   <?= json_encode($_SESSION['flash']['icon'])  ?>,
        title:  <?= json_encode($_SESSION['flash']['title']) ?>,
        text:   <?= json_encode($_SESSION['flash']['text'])  ?>,
        timer:  2000,
        timerProgressBar: true,
        showConfirmButton: false
      });
    </script>
    <?php unset($_SESSION['flash']); ?>
  <?php endif; ?>

</body>
</html>
<?php
exit;
?>
