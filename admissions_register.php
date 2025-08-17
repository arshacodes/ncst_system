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
    <main class="flex-1 items-center justify-center bg-gradient-to-br from-indigo-50 to-white text-sm text-gray-700 px-6">
      <div id="register" class="w-full px-6 h-screen flex flex-row items-center justify-center">
        <div class="p-6">
          <ol class="hidden md:block space-y-2 w-full flex justify-between items-center" id="desktop-stepper">
            <!-- Steppers -->
            <li class="step-item flex flex-col items-center space-y-2">
              <span class="step-circle w-10 h-10 bg-amber-300 font-bold rounded-full flex items-center justify-center shrink-0">1</span>
              <h3 class="hidden md:block font-medium mt-1 break-words text-center">Student Info</h3>
            </li>
            <li class="step-item flex flex-col items-center space-y-2">
              <span class="step-circle w-10 h-10 bg-amber-300 font-bold rounded-full flex items-center justify-center shrink-0">2</span>
              <h3 class="hidden md:block font-medium mt-1 break-words text-center">Education</h3>
            </li>
            <li class="step-item flex flex-col items-center space-y-2">
              <span class="step-circle w-10 h-10 bg-amber-300 font-bold rounded-full flex items-center justify-center shrink-0">3</span>
              <h3 class="hidden md:block font-medium mt-1 break-words text-center">Guardian Info</h3>
            </li>
            <li class="step-item flex flex-col items-center space-y-2">
              <span class="step-circle w-10 h-10 bg-amber-300 font-bold rounded-full flex items-center justify-center shrink-0">4</span>
              <h3 class="hidden md:block font-medium mt-1 break-words text-center">Personal Info</h3>
            </li>
            <li class="step-item flex flex-col items-center space-y-2">
              <span class="step-circle w-10 h-10 bg-amber-300 font-bold rounded-full flex items-center justify-center shrink-0">5</span>
              <h3 class="hidden md:block font-medium mt-1 break-words text-center">Submit</h3>
            </li>
          </ol>
        </div>
        <div id="admissionApp" class="card bg-white rounded-lg w-full grid place-items-center p-6">
          <!--Mobile step circles-->
          <!-- <div class="flex md:hidden justify-center mb-6 space-x-4" id="mobile-stepper">
              <span class="step-circle w-8 h-8 bg-green-200 text-blue-900 font-bold rounded-full flex items-center justify-center ring-2 ring-white">1</span>
              <span class="step-circle w-8 h-8 bg-teal-200 text-blue-900 font-bold rounded-full flex items-center justify-center ring-2 ring-white">2</span>
              <span class="step-circle w-8 h-8 bg-teal-200 text-blue-900 font-bold rounded-full flex items-center justify-center ring-2 ring-white">3</span>
              <span class="step-circle w-8 h-8 bg-teal-200 text-blue-900 font-bold rounded-full flex items-center justify-center ring-2 ring-white">4</span>
              <span class="step-circle w-8 h-8 bg-teal-200 text-blue-900 font-bold rounded-full flex items-center justify-center ring-2 ring-white">5</span>
              <span class="step-circle w-8 h-8 bg-teal-200 text-blue-900 font-bold rounded-full flex items-center justify-center ring-2 ring-white">6</span>
          </div> -->

              <!--Form-->
          <form @submit.prevent="submitAdmission" id="regForm" method="post" class="w-full space-y-6 px-2 sm:px-4">

            <!--Personal info-->
            <div class="form-section" id="personal_info">
              <h2 class="text-lg font-bold mb-4">I. Student Info</h2>
              
              <div class="grid grid-cols-1 md:grid-cols-4 gap-2 mb-4">
                <input type="text" name="firstName" placeholder="First Name" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs" required/>
                <input type="text" name="midName" placeholder="Middle Name" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs" required/>
                <input type="text" name="lastName" placeholder="Last Name" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs" required/>
                <input type="text" name="suffix" placeholder="Suffix" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"/>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-4">
                <input type="text" name="address" placeholder="Complete Address (Region/Town/Barangay/Subdivision/House No.)" class="w-full  px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs" required/>
                <input type="tel" name="phone" placeholder="Phone Number" class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs" required/>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-4 gap-2 mb-4">
                <input type="date" name="birthDate" placeholder="Date of Birth" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs" required/>
                <input type="text" name="birthPlace" placeholder="Birth Place" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs" required/>
                <select name="gender" id="gender" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs" required>
                  <option value="0" disabled selected>Select Gender</option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                </select>
                <select name="nationality" id="nationality" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs" required>
                  <option value="0" disabled selected>Select Nationality</option>
                  <option value="American">American</option>
                  <option value="Australian">Australian</option>
                  <option value="Brazilian">Brazilian</option>
                  <option value="British">British</option>
                  <option value="Canadian">Canadian</option>
                  <option value="Chinese">Chinese</option>
                  <option value="Filipino">Filipino</option>
                  <option value="French">French</option>
                  <option value="German">German</option>
                  <option value="Indian">Indian</option>
                  <option value="Indonesian">Indonesian</option>
                  <option value="Italian">Italian</option>
                  <option value="Japanese">Japanese</option>
                  <option value="Korean">Korean</option>
                  <option value="Malaysian">Malaysian</option>
                  <option value="Mexican">Mexican</option>
                  <option value="Russian">Russian</option>
                  <option value="Singaporean">Singaporean</option>
                  <option value="South African">South African</option>
                  <option value="Spanish">Spanish</option>
                  <option value="Thai">Thai</option>
                  <option value="Vietnamese">Vietnamese</option>
                </select>
              </div>
          </div>

            <!--Educ background-->
            <div class="form-section hidden" id="education">
            <h2 class="text-xl font-bold mb-4">II. Educational Background</h2>

            <div class="mb-5">
                <label for="primary" class="font-semibold">Primary School</label>
                <input type="text" name="primarySchool" placeholder="Primary School" id="primarySchool" class="w-full border border-gray-500 p-2 rounded mb-2" />
                <input type="text" name="primaryYear" placeholder="Year Graduated" class="w-full border border-gray-500 p-2 rounded" />
            </div>
            
            <div class="mb-5">
                <label for="secondary" class="font-semibold">Secondary School</label>
                <input type="text" name="secondarySchool" placeholder="Secondary School" class="w-full border border-gray-500 p-2 rounded mb-2" />
                <input type="text" name="secondaryYear" placeholder="Year Graduated" class="w-full border border-gray-500 p-2 rounded" />
            </div>

            <div class="mb-5">
                <label for="tertiary" class="font-semibold">Tertiary School</label>
                <input type="text" name="tertiarySchool" placeholder="Tertiary School" class="w-full border border-gray-500 p-2 rounded mb-2" />
                <input type="text" name="tertiaryYear" placeholder="Year Graduated" class="w-full border border-gray-500 p-2 rounded mb-2" />
                <input type="text" name="courseGraduated" placeholder="Course Graduated" class="w-full border border-gray-500 p-2 rounded" />
            </div>
            
            </div>

            <!--Work info-->
            <div class="form-section hidden" id="work">
            <h2 class="text-xl font-bold mb-1">III. Work Information</h2>
            <p class="text-s text-gray-500 mb-5">
                If the following does not apply to you, put 'NA' or leave it blank
            </p>
            <input type="text" name="employer" placeholder="Employer Name (if any)" class="w-full border border-gray-500 p-2 rounded mb-5" />
            <input type="text" name="position" placeholder="Occupation (if any)" class="w-full border border-gray-500 p-2 rounded"/>
            </div>

            <!--Desired course-->
            <div class="form-section hidden" id="course">
            <h2 class="text-xl font-bold mb-4">IV. Course, Year Level, House of Heroes & NSTP</h2>
            <h4 class="font-semibold mb-4">Desired Course</h2>
            <select name="course" id="selectCourse" class="w-full border border-gray-300 p-2 rounded">
                <option value="0" disabled selected>Select a course</option>
                <option value="BAC">Bachelor of Arts in Communication</option>
                <option value="ACT">Associate in Computer Technology</option>
                <option value="AOM">Associate in Office Management</option>
                <option value="BSA">Bachelor of Science in Architecture</option>
                <option value="BSBA-OM">Bachelor of Science in Business Administration-Operations Management</option>
                <option value="BSEE">Bachelor of Science in Electronics Engineering</option>
                <option value="BSE">Bachelor of Science in Entrepreneurship</option>
                <option value="BSHM">Bachelor of Science in Hospitality Management</option>
                <option value="BSIE">Bachelor of Science in Industrial Engineering</option>
                <option value="BSISM">Bachelor of Science in Industrial Security Management</option>
                <option value="BSMA">Bachelor of Science in Management Accounting</option>
                <option value="BSPA">Bachelor of Science in Public Administration</option>
                <option value="BSREM">Bachelor of Science in Real Estate Management</option>
                <option value="BSAc">Bachelor of Science in Accountancy</option>
                <option value="BSCpE">Bachelor of Science in Computer Engineering</option>
                <option value="BSCS">Bachelor of Science in Computer Science</option>
                <option value="BSCrim">Bachelor of Science in Criminology</option>
                <option value="BSCA">Bachelor of Science in Customs Administration</option>
                <option value="BSIT">Bachelor of Science in Information Technology</option>
                <option value="BSOA">Bachelor of Science in Office Administration</option>
                <option value="BSPsy">Bachelor of Science in Psychology</option>
                <option value="BSTM">Bachelor of Science in Tourism Management</option>
                <option value="BSBA-FM">Bachelor of Science in Business Administration Major in Financial Management</option>
                <option value="BSBA-MM">Bachelor of Science in Business Administration Major in Marketing-Management</option>
                <option value="BSED-Eng">Bachelor of Secondary Education Major in English</option>
                <option value="BSED-Fil">Bachelor of Secondary Education Major in Filipino</option>
                <option value="BSED-Math">Bachelor of Secondary Education Major in Mathematics</option>
                <option value="BSED-SS">Bachelor of Secondary Education Major in Social Studies</option>
                <option value="PEU">Professional Educational Units</option>
                <option value="TCP">Teacher Certificate Program</option>
            </select>

            <h4 class="font-semibold mb-4 mt-5">Year Level</h2>
            <select name="yearLevel" id="selectYear" class="w-full border border-gray-300 p-2 rounded">
                <option value="0" disabled selected>Select a year level</option>
                <option value="1st Year">1st Year</option>
                <option value="2nd Year">2nd Year</option>
                <option value="3rd Year">3rd Year</option>
                <option value="4th Year">4th Year</option>
            </select>

            <!--HOH-->
            <h4 class="font-semibold mb-4 mt-5">House of Heroes</h2>
            <select name="houseHeroes" id="selectHouse" class="w-full border border-gray-300 p-2 rounded">
                <option value="0" disabled selected>Select a house</option>
                <option value="Makabayan">House of Makabayan</option>
                <option value="Makadiyos">House of Makadiyos</option>
                <option value="Makatao">House of Makatao</option>
                <option value="Makakalikasan">House of Makakalikasan</option>
            </select>

            <!--NSTP-->
            <h4 class="font-semibold mb-4 mt-5">National Service Training Program (NSTP) Components</h2>
            <select name="nstp" id="selectNSTP" class="w-full border border-gray-300 p-2 rounded">
                <option value="0" disabled selected>Select a NSTP</option>
                <option value="LTS">Literacy Training Service (LTS)</option>
                <option value="CWTS">Civic Welfare Training Service (CWTS)</option>
                <option value="ROTC">Reserve Officers' Training Corps (ROTC)</option>
            </select>
            </div>

            <!--Parent/guardian info-->
            <div class="form-section hidden" id="parent">
            <h2 class="text-xl font-bold mb-4">V. Parent / Guardian</h2>
            <p class="font-semibold">Father's Information</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-5">
                <input v-model="parent.fatherFirstName" type="text" name="fatherFirstName" placeholder="Father's First Name" class="w-full border border-gray-500 p-2 rounded" required/>
                <input v-model="parent.fatherMidName" type="text" name="fatherMidName" placeholder="Father's Middle Name" class="w-full border border-gray-500 p-2 rounded" required/>
                <input v-model="parent.fatherLastName" type="text" name="fatherLastName" placeholder="Father's Last Name" class="w-full border border-gray-500 p-2 rounded" required/>
                <input v-model="parent.fatherSuffix" type="text" name="fatherSuffix" placeholder="Suffix" class="w-full border border-gray-500 p-2 rounded"/>
            </div>

            <div class="lg:col-span-2 sm:col-span-2 col-span-1 mb-5">
                <input v-model="parent.fatherAddress" type="text" name="fatherAddress" placeholder="Complete Address" class="w-full border border-gray-500 p-2 rounded" required/>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-5">
                <input v-model="parent.fatherPhone" type="tel" name="fatherPhone" placeholder="Father's Phone No." class="w-full border border-gray-500 p-2 rounded" required/>
                <input v-model="parent.fatherOccupation" type="text" name="fatherOccupation" placeholder="Father's Occupation" class="w-full border border-gray-500 p-2 rounded" required/>
            </div>

            <p class="font-semibold">Mother's Information</p>
            <div class="grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-3 gap-4 mb-5">
                <input v-model="parent.motherFirstName" type="text" name="motherFirstName" placeholder="Mother's First Name" class="w-full border border-gray-500 p-2 rounded" required/>
                <input v-model="parent.motherMidName" type="text" name="motherMidName" placeholder="Mother's Middle Name" class="w-full border border-gray-500 p-2 rounded" required/>
                <input v-model="parent.motherLastName" type="text" name="motherLastName" placeholder="Mother's Last Name" class="w-full border border-gray-500 p-2 rounded" required/>
            </div>

            <div class="lg:col-span-2 sm:col-span-2 col-span-1 mb-5">
                <input v-model="parent.motherAddress" type="text" name="motherAddress" placeholder="Complete Address" class="w-full border border-gray-500 p-2 rounded" required/>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-5">
                <input v-model="parent.motherPhone" type="tel" name="motherPhone" placeholder="Mother's Phone No." class="w-full border border-gray-500 p-2 rounded" required/>
                <input v-model="parent.motherOccupation" type="text" name="motherOccupation" placeholder="Mother's Occupation" class="w-full border border-gray-500 p-2 rounded" required/>
            </div>

            <p class="font-semibold">Guardian's Information</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-5">
                <input v-model="parent.guardianFirstName" type="text" name="guardianFirstName" placeholder="Guardian's First Name" class="w-full border border-gray-500 p-2 rounded" required/>
                <input v-model="parent.guardianMidName" type="text" name="guardianMidName" placeholder="Guardian's Middle Name" class="w-full border border-gray-500 p-2 rounded" required/>
                <input v-model="parent.guardianLastName" type="text" name="guardianLastName" placeholder="Guardian's Last Name" class="w-full border border-gray-500 p-2 rounded" required/>
                <input v-model="parent.guardianSuffix" type="text" name="guardianSuffix" placeholder="Suffix" class="w-full border border-gray-500 p-2 rounded"/>
            </div>

            <div class="lg:col-span-2 sm:col-span-2 col-span-1 mb-5">
                <input v-model="parent.guardianAddress" type="text" name="guardianAddress" placeholder="Complete Address" class="w-full border border-gray-500 p-2 rounded" required/>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-3 gap-4 mb-5">
                <input v-model="parent.guardianPhone" type="tel" name="guardianPhone" placeholder="Guardian's Phone No." class="w-full border border-gray-500 p-2 rounded" required/>
                <input v-model="parent.guardianOccupation" type="text" name="guardianOccupation" placeholder="Guardian's Occupation" class="w-full border border-gray-500 p-2 rounded" required/>
                <input v-model="parent.guardianRelationship" type="text" name="guardianRelationship" placeholder="Guardian's Relationship to Student" class="w-full border border-gray-500 p-2 rounded" required/>
            </div>
            </div>

            <!--Preview and submit-->
            <div class="form-section hidden" id="submit">
              <h2 class="text-xl font-bold mb-4">Submit</h2>
              <p>Review your entries before submitting.</p>
            </div>

            <div class="flex justify-between items-center">
              <a href="index.php#login" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Cancel</a>
            
              <div class="flex gap-2">
                  <button type="button" @click="prevStep()" class="bg-gray-400 text-white px-4 py-2 rounded disabled:opacity-50 hover:bg-gray-500 cursor-pointer" :disabled="currentStep === 0">Back</button>
                  <button type="button" @click="nextStep()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 cursor-pointer">{{ currentStep === 5 ? 'Submit' : 'Next' }}</button>
              </div>
            </div>
          </form>
      </div>
        <!-- <div class="card shadow-lg rounded-lg bg-white flex flex-row max-w-2xl">
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
                      <span>Login</span>
                  </button>
                </div>
              </form>
          </div>
        </div> -->

      </div>
    </main>
  </div>
</body>
</html>
<?php
ob_end_flush();
exit;
?>