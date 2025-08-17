<?php
ob_start();
include __DIR__ . '/src/db/db_conn.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>NCST Education System | Online Admissions</title>
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
    <main class="flex-1 items-center justify-center bg-gradient-to-br from-indigo-50 to-white text-sm text-gray-700">
      <div id="register" class="w-full h-screen flex items-center justify-center p-6">
        <div class="card shadow-lg rounded-lg bg-white flex flex-row max-w-2xl">
          <div class="flex-1 hidden md:block">
            <img
              src="src/assets/img/students.jpg"
              alt="background pattern"
              class="w-full h-full object-cover opacity-100 rounded-l-lg
                      [mask-image:linear-gradient(to_bottom,black,transparent)]
                      [-webkit-mask-image:linear-gradient(to_bottom,black,transparent)]"
            />
          </div>
          <div class="p-6 flex-1">
            <div class="mb-4">
              <img src="src/assets/img/logo-2.png" alt="logo" class="w-16 h-16 mx-auto">
              <p class="text-center text-lg text-indigo-700 calsans">NCST</p>
              <p class="text-center text-sm text-gray-700">Student Registration</p>
            </div>
            <div class="mb-4">
              <form action="admissionsLogin">
                <div class="mb-4">
                  <div class="mb-2">
                    <label for="email" class="block text-xs font-medium text-gray-700">Email</label>
                    <input type="text" id="email" name="email" required
                          class="mt-2 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs">
                  </div>
                  <div>
                    <label for="password" class="block text-xs font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" required
                          class="mt-2 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs">
                  </div>
                </div>
                <div class="mb-4 flex items-center">
                  <input id="formTerms" type="checkbox" name="terms" value="true" class="w-4 h-4 text-white rounded-lg accent-amber-300" required>
                  <label for="formTerms" class="ms-2 text-xs font-medium text-gray-700">I agree with the <a href="#" onclick="openModal()" class="text-indigo-700 hover:underline">terms and conditions</a>.</label>
                </div>
                <div class="">
                  <button class="bg-indigo-700 text-white font-bold py-2 md:py-3 px-4 w-full rounded hover:bg-indigo-800 disabled:opacity-50">
                      <!-- <span v-if="loading">Logging in...</span> -->
                      <span>Login</span>
                  </button>
                </div>
              </form>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
<?php
ob_end_flush();
exit;
?>