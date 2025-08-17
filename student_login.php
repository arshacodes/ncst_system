<?php
// File: student_login.php
session_start();
require_once __DIR__ . '/src/db/db_conn.php';

$error    = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Enter both username and password.';
    } else {
        // Students live in students_tbl
        $sql  = "SELECT id, username, password_hash
                   FROM students_tbl
                  WHERE username = ?
                  LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = 'student';

            header('Location: student_portal.php');
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
  <title>Student Login</title>
</head>
<body>
  <h1>Student Login</h1>
  <?php if ($error): ?>
    <div style="color:red"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST">
    <label>
      Username:
      <input type="text" name="username" value="<?= htmlspecialchars($username) ?>">
    </label><br>

    <label>
      Password:
      <input type="password" name="password">
    </label><br>

    <button type="submit">Log In</button>
  </form>
</body>
</html>
