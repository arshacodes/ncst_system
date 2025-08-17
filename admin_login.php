<?php
// File: admin_login.php
session_start();
require_once __DIR__ . '/src/db/db_conn.php';

$error    = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $error = 'Enter both username and password.';
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

        // if ($user && password_verify($password, $user['password'])) {
        if ($user && $password == $user['password']) {
            session_regenerate_id(true);
            $_SESSION['user_id']       = $user['id'];
            $_SESSION['email']      = $user['email'];
            $_SESSION['department_id'] = $user['department_id'];
            $_SESSION['user_initials'] = $user['initials'];

            if ($user['department_id'] === 1) {
                header('Location: superadmin_portal.php');
            } else {
                header('Location: employee_login.php');
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
  <meta charset="UTF-8">
  <title>Admin Login</title>
</head>
<body>
  <h1>Admin / Superadmin Login</h1>
  <?php if ($error): ?>
    <div style="color:red"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST">
    <label>
      Email:
      <input type="email" name="email" value="<?= htmlspecialchars($email) ?>">
    </label><br>

    <label>
      Password:
      <input type="password" name="password">
    </label><br>

    <button type="submit">Log In</button>
  </form>
</body>
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

</html>
