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
      <div id="login" class="w-full h-screen flex items-center justify-center p-6">
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
              <p class="text-center text-sm text-gray-700">Admissions Login</p>
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
            <div class="flex flex-col items-center justify-center">
              <!-- <span class="flex-1 border-b"></span> -->
              <a href="/ncst_system/forgot_pass.php" class="text-xs hover:underline text-gray-700 whitespace-nowrap">Forgot Password?</a>
              <a href="/ncst_system/admissions_register.php" class="text-xs hover:underline text-gray-700 whitespace-nowrap">Register?</a>
              <!-- <span class="flex-1 border-b"></span> -->
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

    <!-- TERMS MODAL -->
  <div id="termsModal" class="fixed inset-0 z-50 flex items-center justify-center px-6 hidden">
    <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity duration-500" onclick="closeModal()"></div>

    <div data-terms-panel class="bg-white rounded-lg shadow-lg max-w-xl w-full p-6 z-10 transform transition-all duration-500 scale-95 opacity-0 overflow-y-auto"
         style="max-height: 90vh;">
      <h2 class="text-center text-lg font-bold text-indigo-800 mb-4">
        Terms and Conditions
      </h2>
      <hr class="text-amber-500 mb-4"/>

      <p class="text-gray-800 mb-6 text-sm leading-relaxed text-justify">
        Users agree that no action shall be taken to impose unreasonable or disproportionately large load on the infrastructure 
        of the site or NCST's systems or networks, or any systems or networks connected to the site or to NCST in general.
        You may not attempt to gain unauthorized access to any portion or feature of the site, or any other systems or networks connected
        to the site or to any NCST server, or to any services offered on or through the site, by hacking, password "mining" or
        any other illegitimate means. <br> <br>
        Users may not use anyone else's login credentials, password, or account. NCST cannot and will not be liable for any loss
        or damage arising from your failure to comply with these obligatiosns. Additionally, by using this site, you acknowledge and 
        agree that Internet transmissions are never completely private or secure. You understand that any message or information you send
        to the site may be read or intercepted by others. NCST provides the use of this site on an "as-is" basis wihtout 
        warranting any aspect of its Services. <br> <br>
        Therefore, users are on noticw that they access and use the site at their own risk. Using NCST's site and remote
        servers constitute full agreement and understanding of this policy, NCST reserves the right to modify this policy without 
        permission or consent of its users or recipients. <br> <br>
        By checking the box, you agree to all the policies stated above and acknowledge that you have read and understood them.
      </p>

      <div class="text-center">
        <button
          class="bg-indigo-800 text-white px-4 py-2 rounded-lg
                transition-transform duration-300 hover:scale-[1.02] hover:bg-indigo-700"
          onclick="closeModal()"
        >Close</button>
      </div>
    </div>
  </div>

  <script>
    function openModal() {
      const modal = document.getElementById('termsModal');
      const panel = modal.querySelector('[data-terms-panel]');
      modal.classList.remove('hidden');
      requestAnimationFrame(() => {
        panel.classList.replace('scale-95', 'scale-100');
        panel.classList.replace('opacity-0', 'opacity-100');
      });
    }

    function closeModal() {
      const modal = document.getElementById('termsModal');
      const panel = modal.querySelector('[data-terms-panel]');
      panel.classList.replace('scale-100', 'scale-95');
      panel.classList.replace('opacity-100', 'opacity-0');
      setTimeout(() => modal.classList.add('hidden'), 500);
    }

    function checkTerms() {
      // your existing checkbox validation logic
    }
  </script>

</body>
</html>
<?php
ob_end_flush();
exit;
?>