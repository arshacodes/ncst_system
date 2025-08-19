<?php
ob_start();
include __DIR__ . '/src/db/db_conn.php';


try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $stmt = $pdo->prepare("
        SELECT id, name
        FROM courses_tbl
        WHERE status = 'Active'
        ORDER BY name
    ");
    $stmt->execute();
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

     $stmt = $pdo->prepare("
      SELECT id, name
        FROM nationalities_tbl
      ORDER BY name
    ");
    $stmt->execute();
    $nationalities = $stmt->fetchAll(PDO::FETCH_ASSOC);

    usort($nationalities, function($a, $b) {
      if ($a['name'] === 'Filipino') return -1;
      if ($b['name'] === 'Filipino') return  1;
      return strcmp($a['name'], $b['name']);
    });

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>NCST Education System | Online Admissions</title>
  <link rel="icon" href="src/assets/img/logo-1.png"/>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="src/css/styles.css"/>
  <!-- <script src="https://cdn.tailwindcss.com"></script> -->
  <style type="text/tailwindcss">
    .step-circle {
      @apply transition-colors;
    }
    .step-item.completed .step-circle {
      @apply bg-green-500 text-white;
    }
    .invalid {
      @apply border-red-500 bg-red-50;
    }
    .invalid:focus {
      @apply outline-none ring ring-red-400;
    }
  </style>

  <!-- <script src="src/js/login.js" defer></script> -->

</head>
<body class="flex h-screen bg-gray-100 overflow-hidden">
  <div id="content" class="flex-1 flex flex-col overflow-auto text-sm">
    <main class="flex-1 items-center justify-center bg-gradient-to-br from-indigo-50 to-white text-sm text-gray-700 px-6 overflow-auto">
      <div id="register" class="w-full px-6 h-screen items-center justify-center">
        <div class="p-6">
          <ol
            id="desktop-stepper"
            class="hidden md:flex w-full justify-between items-center space-x-2"
          >
            <li
              class="step-item flex flex-col items-center space-y-2"
              data-step="0"
            >
              <span class="step-circle w-10 h-10 bg-gray-300 font-bold rounded-full flex items-center justify-center">
                1
              </span>
              <h3 class="font-medium mt-1 text-center">Student Info</h3>
            </li>

            <li
              class="step-item flex flex-col items-center space-y-2"
              data-step="1"
            >
              <span class="step-circle w-10 h-10 bg-gray-300 font-bold rounded-full flex items-center justify-center">
                2
              </span>
              <h3 class="font-medium mt-1 text-center">Education</h3>
            </li>

            <li
              class="step-item flex flex-col items-center space-y-2"
              data-step="2"
            >
              <span class="step-circle w-10 h-10 bg-gray-300 font-bold rounded-full flex items-center justify-center">
                3
              </span>
              <h3 class="font-medium mt-1 text-center">Guardian Info</h3>
            </li>

            <li
              class="step-item flex flex-col items-center space-y-2"
              data-step="3"
            >
              <span class="step-circle w-10 h-10 bg-gray-300 font-bold rounded-full flex items-center justify-center">
                4
              </span>
              <h3 class="font-medium mt-1 text-center">Submit</h3>
            </li>
          </ol>
        </div>

        <div id="admissionApp" class="card bg-white rounded-lg w-full grid place-items-center p-6">
          <form id="multiStepForm" novalidate method="post" class="w-full space-y-6 px-2 sm:px-4">
            <div class="form-section gap-2" id="student_info" data-step="1">
              <h2 class="text-lg font-bold mb-4">Student Info</h2>
              
              <div class="space-y-2">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-2">
                  <div class="form-group">
                    <label for="firstname" class="block text-xs font-medium text-gray-700">Firstname</label>
                    <input
                      id="firstname"
                      name="firstName"
                      type="text"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                      required
                    />
                  </div>
                  <div class="form-group">
                    <label for="midName" class="block text-xs font-medium text-gray-700">Middlename</label>
                    <input
                      id="midName"
                      name="midName"
                      type="text"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                    />
                  </div>
                  <div class="form-group">
                    <label for="lastName" class="block text-xs font-medium text-gray-700">Lastname</label>
                    <input
                      id="lastName"
                      name="lastName"
                      type="text"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                      required
                    />
                  </div>
                  <div class="form-group">
                    <label for="suffix" class="block text-xs font-medium text-gray-700">Suffix</label>
                    <input
                      id="suffix"
                      name="suffix"
                      type="text"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                    />
                  </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                  <div class="form-group">
                    <label for="email" class="block text-xs font-medium text-gray-700">Email</label>
                    <input
                      id="email"
                      name="email"
                      type="email"
                      placeholder="Email Address"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                      required
                    />
                  </div>
                  <div class="form-group">
                    <label for="phone" class="block text-xs font-medium text-gray-700">Phone Number</label>
                    <input
                      id="phone"
                      name="phone"
                      type="tel"
                      placeholder="Phone Number"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                      required
                    />
                  </div>
                </div>

                <div class="grid grird-cols-1 md:grid-cols-2 gap-2">
                  <div class="form-group">
                    <label for="selectCourse" class="block text-xs font-medium text-gray-700">Desired Course</label>
                    <select
                      id="selectCourse"
                      name="course"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                      required
                    >
                      <option value="" disabled selected>Select a course</option>
                      <?php foreach ($courses as $course): ?>
                        <option
                          value="<?= htmlspecialchars($course['id'], ENT_QUOTES) ?>"
                        >
                          <?= htmlspecialchars($course['name'], ENT_QUOTES) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="selectNSTP" class="block text-xs font-medium text-gray-700">
                      National Service Training Program (NSTP)
                    </label>
                    <select
                      id="selectNSTP"
                      name="nstp"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                      required
                    >
                      <option value="" disabled selected>Select NSTP</option>
                      <option value="LTS">Literacy Training Service (LTS)</option>
                      <option value="CWTS">Civic Welfare Training Service (CWTS)</option>
                      <option value="ROTC">Reserve Officers' Training Corps (ROTC)</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label for="address" class="block text-xs font-medium text-gray-700">Complete Address</label>
                  <textarea
                    id="address"
                    name="address"
                    type="text"
                    placeholder="Region/Town/Barangay/Subdivision/House No."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                    required
                  ></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-2">
                  <div class="form-group">
                    <label for="birthDate" class="block text-xs font-medium text-gray-700">Date of Birth</label>
                    <input
                      id="birthDate"
                      name="birthDate"
                      type="date"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                      required
                    />
                  </div>
                  <div class="form-group">
                    <label for="birthPlace" class="block text-xs font-medium text-gray-700">Birth Place</label>
                    <input
                      id="birthPlace"
                      name="birthPlace"
                      type="text"
                      placeholder="Birth Place"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                      required
                    />
                  </div>
                  <div class="form-group">
                    <label for="gender" class="block text-xs font-medium text-gray-700">Gender</label>
                    <select
                      id="gender"
                      name="gender"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                      required
                    >
                      <option value="" disabled selected>Select Gender</option>
                      <option value="Male">Male</option>
                      <option value="Female">Female</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="nationality" class="block text-xs font-medium text-gray-700">
                      Nationality
                    </label>
                    <select
                      id="nationality"
                      name="nationality"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md
                            shadow-sm focus:outline-none focus:ring-indigo-500
                            focus:border-indigo-500 text-xs"
                      required
                    >
                      <option value="" disabled selected>Select Nationality</option>
                      <?php foreach ($nationalities as $nat): ?>
                        <option
                          value="<?= htmlspecialchars($nat['name'], ENT_QUOTES) ?>"
                        >
                          <?= htmlspecialchars($nat['name']) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                </div>
              </div>
            </div>
            <div class="form-section hidden gap-2" id="education" data-step="2">
              <h2 class="text-lg font-bold mb-4">Educational Background</h2>
              <div class="space-y-2">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                  <div class="form-group">
                    <label for="primarySchool" class="block text-xs font-medium text-gray-700">Primary School</label>
                    <input
                      id="primarySchool"
                      name="primarySchool"
                      type="text"
                      placeholder="Primary School"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                      required
                    />
                  </div>
                  <div class="form-group">
                    <label for="primaryYear" class="block text-xs font-medium text-gray-700">Year Graduated</label>
                    <input
                      id="primaryYear"
                      name="primaryYear"
                      type="text"
                      placeholder="Year Graduated"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                      required
                    />
                  </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                  <div class="form-group">
                    <label for="secondarySchool" class="block text-xs font-medium text-gray-700">Secondary School</label>
                    <input
                      id="secondarySchool"
                      name="secondarySchool"
                      type="text"
                      placeholder="Secondary School"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                      required
                    />
                  </div>
                  <div class="form-group">
                    <label for="secondaryYear" class="block text-xs font-medium text-gray-700">Year Graduated</label>
                    <input
                      id="secondaryYear"
                      name="secondaryYear"
                      type="text"
                      placeholder="Year Graduated"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                      required
                    />
                  </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                  <div class="form-group">
                    <label for="tertiarySchool" class="block text-xs font-medium text-gray-700">Tertiary School</label>
                    <input
                      id="tertiarySchool"
                      name="tertiarySchool"
                      type="text"
                      placeholder="Tertiary School"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                    />
                  </div>
                  <div class="form-group">
                    <label for="tertiaryYear" class="block text-xs font-medium text-gray-700">Year Graduated</label>
                    <input
                      id="tertiaryYear"
                      name="tertiaryYear"
                      type="text"
                      placeholder="Year Graduated"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                    />
                  </div>
                  <div class="form-group">
                    <label for="courseGraduated" class="block text-xs font-medium text-gray-700">Course Graduated</label>
                    <input
                      id="courseGraduated"
                      name="courseGraduated"
                      type="text"
                      placeholder="Course Graduated"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                    />
                  </div>
                </div>
              </div>
            </div>
            <div class="form-section hidden gap-2" id="guardian" data-step="3">
              <h2 class="text-lg font-bold mb-4">Guardian Info</h2>
              
              <div class="space-y-2">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                  <div class="form-group">
                    <label for="guardianFirstName" class="block text-xs font-medium text-gray-700">
                      Guardian’s First Name
                    </label>
                    <input
                      id="guardianFirstName"
                      name="guardianFirstName"
                      v-model="parent.guardianFirstName"
                      type="text"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                      required
                    />
                  </div>
                  <div class="form-group">
                    <label for="guardianMidName" class="block text-xs font-medium text-gray-700">
                      Guardian’s Middle Name
                    </label>
                    <input
                      id="guardianMidName"
                      name="guardianMidName"
                      v-model="parent.guardianMidName"
                      type="text"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                    />
                  </div>
                  <div class="form-group">
                    <label for="guardianLastName" class="block text-xs font-medium text-gray-700">
                      Guardian’s Last Name
                    </label>
                    <input
                      id="guardianLastName"
                      name="guardianLastName"
                      v-model="parent.guardianLastName"
                      type="text"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                      required
                    />
                  </div>
                  <div class="form-group">
                    <label for="guardianSuffix" class="block text-xs font-medium text-gray-700">
                      Suffix
                    </label>
                    <input
                      id="guardianSuffix"
                      name="guardianSuffix"
                      v-model="parent.guardianSuffix"
                      type="text"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                    />
                  </div>
                </div>

                <div class="form-group">
                  <label for="guardianAddress" class="block text-xs font-medium text-gray-700">
                    Complete Address
                  </label>
                  <input
                    id="guardianAddress"
                    name="guardianAddress"
                    v-model="parent.guardianAddress"
                    type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                    required
                  />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                  <div class="form-group">
                    <label for="guardianPhone" class="block text-xs font-medium text-gray-700">
                      Guardian’s Phone No.
                    </label>
                    <input
                      id="guardianPhone"
                      name="guardianPhone"
                      v-model="parent.guardianPhone"
                      type="tel"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                      required
                    />
                  </div>
                  <div class="form-group">
                    <label for="guardianRelationship" class="block text-xs font-medium text-gray-700">
                      Guardian’s Relationship to Student
                    </label>
                    <input
                      id="guardianRelationship"
                      name="guardianRelationship"
                      v-model="parent.guardianRelationship"
                      type="text"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                      required
                    />
                  </div>
                </div>
              </div>
            </div>

            <div class="form-section hidden" data-step="4" id="step-4">
              <h2 class="text-xl font-bold mb-4">Submit</h2>
              <p>Review your entries before submitting.</p>
            </div>

            <div class="flex justify-between items-center">
              <a href="index.php#login"
                class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                Cancel
              </a>

              <div class="flex gap-2">
                <button
                  id="prevBtn"
                  type="button"
                  class="bg-gray-400 text-white px-4 py-2 rounded disabled:opacity-50 hover:bg-gray-500"
                  disabled
                >
                  Back
                </button>
                <button
                  id="nextBtn"
                  type="button"
                  class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                >
                  Next
                </button>
              </div>

            </div>

          </form>
        </div>
      </div>
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    (()=>{
      const form         = document.getElementById('multiStepForm');
      // route the final submit to your PHP endpoint
      form.action         = 'src/functions/process_registration.php';

      const sections     = [...form.querySelectorAll('.form-section')]
                            .reduce((map, sec) => {
                              map[+sec.dataset.step] = sec;
                              return map;
                            }, {});
      const totalSteps   = Object.keys(sections).length;
      const prevBtn      = document.getElementById('prevBtn');
      const nextBtn      = document.getElementById('nextBtn');
      const desktopSteps = [...document.querySelectorAll('#desktop-stepper .step-item')]
                            .map(li => ({ el: li, step: +li.dataset.step }));

      let current = 1;

      function showStep(step) {
        Object.values(sections).forEach(sec => sec.classList.add('hidden'));
        sections[step].classList.remove('hidden');

        prevBtn.disabled    = step === 1;
        nextBtn.textContent = step === totalSteps ? 'Submit' : 'Next';

        desktopSteps.forEach(({el, step: s}) => {
          el.classList.toggle('completed', s < step);
          el.classList.toggle('active',    s === step);
        });
      }

      function validateStep(step) {
        const fields       = sections[step].querySelectorAll('input, select, textarea');
        let isValid        = true;
        let firstInvalid   = null;

        for (const f of fields) {
          // reset previous state
          f.classList.remove('invalid');
          f.setCustomValidity('');

          // trim whitespace
          if (typeof f.value === 'string') {
            f.value = f.value.trim();
          }

          // enforce non-empty after trim
          if (f.required && (!f.value || f.value.length === 0)) {
            f.setCustomValidity('This field cannot be blank');
          }

          // run native HTML5 validation
          if (!f.checkValidity()) {
            f.classList.add('invalid');
            if (!firstInvalid) firstInvalid = f;
            isValid = false;
          }
        }

        if (!isValid) {
          firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
          Swal.fire({
            icon:  'warning',
            title: 'Oops',
            text:  firstInvalid.validationMessage
          });
        }

        return isValid;
      }

      nextBtn.addEventListener('click', async () => {
        // run your per-step validator
        if (!validateStep(current)) return;

        // if not last step, just advance
        if (current < totalSteps) {
          showStep(++current);
          return;
        }

        // ---- final step: AJAX submit ----

        // 1) re-enable any disabled fields (so they get sent)
        form.querySelectorAll('input:disabled, select:disabled, textarea:disabled')
            .forEach(el => el.disabled = false);

        // 2) build FormData
        const payload = new FormData(form);

        try {
          // 3) post to your PHP endpoint
          const res  = await fetch(form.action, {
            method: 'POST',
            body:   payload
          });
          const data = await res.json();

          // 4) show result via SweetAlert
          if (!data.success) {
            Swal.fire({ icon: 'error', title: 'Oops',  text: data.message });
          } else {
            Swal.fire({ icon: 'success', title: 'Great!', text: data.message });
          }

        } catch (err) {
          // network or parse error
          Swal.fire({
            icon:  'error',
            title: 'Error',
            text:  'Could not contact server. Please try again.'
          });
        }
      });


      prevBtn.addEventListener('click', () => {
        if (current > 1) showStep(--current);
      });

      form.addEventListener('input', e => {
        const f = e.target;
        if (f.validity.valid && f.classList.contains('invalid')) {
          f.classList.remove('invalid');
        }
      });

      showStep(current);
    })();

  </script>

  


</body>
</html>
<?php
ob_end_flush();
exit;
?>