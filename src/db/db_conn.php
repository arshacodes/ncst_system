<?php
  $servername = "localhost";
  $dbname = "ncst_system_db";
  $user = "root";
  $pass = "";
  $charset = "utf8mb4";

  $dsn = "mysql:host=$servername;dbname=$dbname;charset=$charset";

  $options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // throw exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // fetch as associative array
    PDO::ATTR_EMULATE_PREPARES   => false,                  // use native prepared statements
  ];

  try {
    $pdo = new PDO($dsn, $user, $pass, $options);
  } catch (PDOException $e) {
    exit('Database connection failed: ' . $e->getMessage());
  }
?>