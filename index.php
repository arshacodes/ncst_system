<?php
$aboutCards = [
    [
        'title' => 'Founding Year', 'text'  => 'NCST was born in the historic and progressive City of Dasmariñas in 1998.', 'image' => 'src/assets/img/ncst-main.jpg',
    ],
    [
        'title' => 'Founder', 'text'  => 'It was founded by Emerson B. Atanacio, who was 23 years old at the time.', 'image' => 'src/assets/img/ncst-main.jpg',
    ],
    [
        'title' => 'Enrollment Growth', 'text'  => 'From 550 students in 1998, NCST\'s enrollment has grown tenfold.', 'image' => 'src/assets/img/ncst-main.jpg',
    ],
    [
        'title' => 'Mission', 'text'  => 'We commit to molding students as industry-responsive graduates.', 'image' => 'src/assets/img/ncst-main.jpg',
    ],
    [
        'title' => 'IIRT Launch', 'text'  => 'In 2015, NCST launched the IIRT—Institute of Industry and Research Technology.', 'image' => 'src/assets/img/ncst-main.jpg',
    ],
    [
        'title' => 'Dual Training System', 'text'  => 'Students train in both school and industry under the dual-training modality.', 'image' => 'src/assets/img/ncst-main.jpg',
    ],
];

$missionVision = [
    [
        'id'      => 'mv1', 'title'   => 'MISSION', 'content' => 'The National College of Science and Technology undertakes the responsibility of providing the country with quality graduates who are trained with industry responsive knowledge and skills and founded with underpinning values of love, justice, mutual respect and peace. The school commits itself to the promotion of academic excellence, research and community extension through relevant programs and projects and participative decision-making.',
    ],
    [
        'id'      => 'mv2', 'title'   => 'VISION', 'content' => 'The National College of Science and Technology envisions to become one of the nation’s leading technological educational institutions with campuses in various areas of the Philippines. NCST, in response to the commercial and industrial sector’s need of highly professional and skilled manpower, provides advanced technology and industry-based education and sets standards of proficiency and competency compatible with the demands of industry, instilling enduring positive work values, competitiveness, and quality among its graduates.',
    ],
];

$houses = [
    [
        'name'        => 'MAKADIYOS',
        'desc'        => 'Inspires godly courage and commitment through faith, love, and spiritual excellence aligned with a lasting relationship with God.',
        'bgClass'     => 'bg-white',
        'textClass'   => 'text-gray-700',
        'image'       => 'src/assets/img/ncst-main.jpg',
        'logo'        => 'src/assets/img/makadiyos.png',
    ],
    [
        'name'        => 'MAKATAO',
        'desc'        => 'Kindles nationalistic spirit and dedication to contribute one\'s best skills and knowledge to the advancement of science and the nation.',
        'bgClass'     => 'bg-red-700',
        'textClass'   => 'text-white',
        'image'       => 'src/assets/img/ncst-main.jpg',
        'logo'        => 'src/assets/img/makatao.png',
        'reverse'     => true,
    ],
    [
        'name'        => 'MAKABAYAN',
        'desc'        => 'Uplifts the welfare of students through a caring environment and quality facilities to support perseverance and belonging at NCST.',
        'bgClass'     => 'bg-indigo-800',
        'textClass'   => 'text-white',
        'image'       => 'src/assets/img/ncst-main.jpg',
        'logo'        => 'src/assets/img/makabayan.png',
    ],
    [
        'name'        => 'MAKAKALIKASAN',
        'desc'        => 'Empowers environmental conservation efforts through awareness and active participation in eco-driven initiatives.',
        'bgClass'     => 'bg-amber-300',
        'textClass'   => 'text-gray-700',
        'image'       => 'src/assets/img/ncst-main.jpg',
        'logo'        => 'src/assets/img/makakalikasan.png',
        'reverse'     => true,
    ],
];

$portals = [
  [
    'id'         => 1,
    'title'      => 'Admissions Portal',
    'subtitle'   => 'Become a Nation Builder',
    'image'      => 'src/assets/img/registration.png',
    'link'       => '/ncst_system/admissions_login.php',
    'buttonText' => 'Go to Admissions',
  ],
  [
    'id'         => 2,
    'title'      => 'Student Portal',
    'subtitle'   => 'Login as Student',
    'image'      => 'src/assets/img/graduate.png',
    'link'       => '/ncst_system/student_login.php',
    'buttonText' => 'Go to Student Portal',
  ],
  [
    'id'         => 3,
    'title'      => 'Employee Portal',
    'subtitle'   => 'Login as Employee',
    'image'      => 'src/assets/img/pass.png',
    'link'       => '/ncst_system/employee_login.php',
    'buttonText' => 'Go to Employee Portal',
  ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>NCST Education System</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" href="src/assets/img/logo-1.png" />
  <link rel="stylesheet" href="src/css/styles.css" />
  <style>
    [data-animate] { opacity: 0; transform: translateY(20px); transition: all 0.8s ease-in-out; }
    [data-animate].visible { opacity: 1; transform: none; }
    html { scroll-behavior: smooth; }
    .card p { transition: all 400ms ease; }
  </style>
</head>
<body class="antialiased">
  <nav class="bg-indigo-900 w-full z-30 top-0 start-0 sticky shadow-lg px-6 md:px-20">
    <div class="flex flex-wrap items-center justify-between mx-auto py-4">
      <a href="#" class="flex items-center gap-3">
        <span class="bg-white rounded-full">
          <img src="src/assets/img/logo-1.png" class="h-10 rounded-full" alt="NCST Logo">
        </span>
        <span class="calsans self-center text-lg sm:text-lg text-white">
          NCST Education System
        </span>
      </a>

      <button id="navbar-toggler"
        class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-white rounded-lg md:hidden focus:ring-1 focus:ring-white hover:ring-1 hover:ring-white transition-all"
        aria-label="Toggle navigation">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 17 14" xmlns="http://www.w3.org/2000/svg">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M1 1h15M1 7h15M1 13h15" />
        </svg>
      </button>

      <div id="navbar-menu" class="hidden w-full md:flex md:w-auto md:order-1">
        <ul class="flex flex-col lg:p-4 lg:pe-0 md:p-0 mt-4 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0">
          <!-- ?php foreach (['HOME','#about','LOGIN'] as $label): ?>
            ?php $uri = $label === 'HOME' ? '#' : strtolower($label); ?>
            <li>
              <a href="?= $label==='LOGIN'?'/login.php':$uri ?>"
                 class="relative group text-white px-2 py-2">
                ?= $label ?>
                <span class="absolute left-0 bottom-0 h-0.5 bg-yellow-300 w-0 transition-all duration-300 group-hover:w-full"></span>
              </a>
            </li>
          ?php endforeach; ?> -->
        </ul>
      </div>
    </div>
  </nav>

  <div id="hero" class="relative h-screen py-20 max-w-screen overflow-hidden md:h-full">
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat z-0"
         style="background-image: url('src/assets/img/ncst-main.jpg')"></div>
    <div class="absolute inset-0 backdrop-blur-sm bg-indigo-900/10 z-10"></div>
    <div class="relative z-20 flex items-center justify-center h-full px-6 md:px-20">
      <div class="flex flex-col content-center md:flex-row items-center gap-4 w-fit px-4">
        <img src="src/assets/img/logo-2.png" class="w-40" alt="Secondary Logo"/>
        <div class="text-center md:text-start text-white">
          <p class="text-xl">School of Nation Builders</p>
          <p class="calsans font-semibold text-2xl">NATIONAL COLLEGE OF SCIENCE & TECHNOLOGY</p>
        </div>
      </div>
    </div>
  </div>

  <div class="bg-gradient-to-br from-indigo-50 to-white text-gray-900 text-lg">

    <section id="about" class="py-20 mx-auto relative px-6 md:px-20">
      <div class="mb-10 text-center">
        <p class="text-xl calsans font-semibold mb-2 text-indigo-800">ABOUT US</p>
        <p class="text-sm">Brief History of NCST</p>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 place-items-center">
        <?php foreach ($aboutCards as $idx => $card): ?>
          <div
            class="card group w-full h-80 flex flex-col overflow-hidden bg-white rounded-lg shadow-lg cursor-pointer transition-all duration-500"
            data-animate
            onmouseenter="toggleHover(<?= $idx ?>, true)"
            onmouseleave="toggleHover(<?= $idx ?>, false)"
            onclick="toggleExpand(<?= $idx ?>)"
          >
            <div class="flex-auto overflow-hidden relative">
              <img src="<?= $card['image'] ?>" class="w-full h-full object-cover" />
            </div>
            <div id="about-body-<?= $idx ?>"
                 class="px-6 py-4 bg-white relative z-10 transition-all duration-500 ease-in-out overflow-hidden"
                 style="height: 3.75rem;"
            >
              <p class="text-lg font-bold text-indigo-800 mb-0"><?= $card['title'] ?></p>
              <p id="about-text-<?= $idx ?>" class="opacity-0 translate-y-2 transition-all duration-300 ease-in-out mt-2 text-sm">
                <?= $card['text'] ?>
              </p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <section id="mission-vision"
             class="max-w-screen mx-auto relative parallax py-20"
             style="background-image: url('src/assets/img/ncst-entrance.jpg'); background-attachment: fixed;"
    >
      <div class="absolute inset-0 backdrop-blur-sm bg-indigo-900/10 z-10 h-full"></div>
      <div class="relative z-20 px-6 md:px-20">
        <div class="mb-10 text-center">
          <p class="text-lg calsans font-semibold text-indigo-800">OUR MISSION AND VISION</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 place-items-center w-full">
          <?php foreach ($missionVision as $item): ?>
            <div
              class="card group w-full flex flex-col justify-between bg-white rounded-lg shadow-lg p-6 cursor-pointer transition-transform duration-300 hover:scale-[1.02]"
              data-animate
              onclick="openModal('<?= $item['id'] ?>')"
            >

              <p class="text-center text-lg font-bold text-indigo-800 mb-4"><?= $item['title'] ?></p>
              <hr class="text-amber-500 mb-4"/>
              <p class="text-gray-700 line-clamp-6 text-sm"><?= $item['content'] ?></p>
            </div>

            <div id="modal-<?= $item['id'] ?>" class="fixed inset-0 z-50 flex items-center justify-center px-6 hidden">
              <div
                class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity duration-500"
                onclick="closeModal('<?= $item['id'] ?>')"
              ></div>
              <div
                class="bg-white rounded-lg shadow-lg max-w-xl w-full p-6 z-10 transform transition-all duration-500 scale-95 opacity-0"
              >
                <h2 class="text-center text-lg font-bold text-indigo-800 mb-4"><?= $item['title'] ?></h2>
                <hr class="text-amber-500 mb-4"/>
                <p class="text-gray-800 mb-6 text-sm"><?= $item['content'] ?></p>
                <div class="text-center">
                  <button
                    class="bg-indigo-800 text-white px-4 py-2 rounded-lg transition-transform duration-300 hover:scale-[1.02] hover:bg-indigo-700"
                    onclick="closeModal('<?= $item['id'] ?>')"
                  >Close</button>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <section id="houses" class="bg-gradient-to-br from-indigo-900 to-indigo-800 py-20 mx-auto relative px-6 md:px-20">
      <div class="mb-10 text-center">
        <p class="text-xl calsans font-semibold mb-2 text-white">HOUSE OF HEROES</p>
        <p class="text-white text-sm">The Nation Builders</p>
      </div>
      <div class="space-y-12">
        <?php foreach ($houses as $hidx => $h): ?>
          <div
            class="grid grid-cols-1 md:grid-cols-2 items-center <?= $h['bgClass'] ?> shadow-lg rounded-lg <?= $h['textClass'] ?>"
            data-animate
          >

            <?php if (!empty($h['reverse'])): ?>
              <div class="order-1 md:order-2">
                <div class="h-fit relative">
                  <img src="<?= $h['image'] ?>" alt="<?= $h['name'] ?>" class="w-full rounded-lg z-10"/>
                  <img src="<?= $h['logo'] ?>" alt="<?= $h['name'] ?> Logo" class="absolute h-40 z-20 inset-0 m-auto"/>
                </div>
              </div>
              <div class="flex flex-col justify-center p-6 order-2 md:order-1 text-center">
                <h2 class="text-lg font-bold mb-2"><?= $h['name'] ?></h2>
                <p class="text-sm"><?= $h['desc'] ?></p>
              </div>
            <?php else: ?>
              <div>
                <div class="h-fit relative">
                  <img src="<?= $h['image'] ?>" alt="<?= $h['name'] ?>" class="w-full rounded-lg z-10"/>
                  <img src="<?= $h['logo'] ?>" alt="<?= $h['name'] ?> Logo" class="absolute h-40 z-20 inset-0 m-auto"/>
                </div>
              </div>
              <div class="flex flex-col justify-center p-6 text-center">
                <h2 class="text-lg font-bold mb-2"><?= $h['name'] ?></h2>
                <p class="text-sm"><?= $h['desc'] ?></p>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <section id="portals" class="py-20 mx-auto relative px-6 md:px-20">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 place-items-center">
        <?php foreach ($portals as $card): ?>
          <div
            class="card group w-full flex flex-col overflow-hidden bg-white rounded-lg shadow-lg"
            data-animate
          >
            <!-- Header / Image Container -->
            <div class="bg-indigo-50 h-24 relative">
              <div class="absolute inset-x-0 -bottom-6 flex justify-center">
                <div class="w-24 h-24 flex items-center justify-center">
                  <img
                    src="<?= $card['image'] ?>"
                    alt="<?= htmlspecialchars($card['title'], ENT_QUOTES) ?>"
                    class="w-24 h-24 object-contain"
                  />
                </div>
              </div>
            </div>

            <!-- Body / Text + Button -->
            <div class="px-6 py-4 text-center">
              <p class="mt-6 text-lg font-bold text-indigo-800 mb-0">
                <?= htmlspecialchars($card['title'], ENT_QUOTES) ?>
              </p>
              <p class="text-gray-700 mb-0 text-sm">
                <?= htmlspecialchars($card['subtitle'], ENT_QUOTES) ?>
              </p>
              <a href="<?= htmlspecialchars($card['link'], ENT_QUOTES) ?>">
                <button
                  class="mt-4 bg-amber-300 rounded-lg py-2 px-4 cursor-pointer
                        transition-transform duration-300 hover:scale-[1.02] text-sm"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    viewBox="0 0 24 24"
                    class="fill-current text-gray-700 inline me-2"
                  >
                    <path
                      d="M23.987 12a2.4 2.4 0 0 0-.814-1.8L11.994.361a1.44 
                        1.44 0 0 0-1.9 2.162l8.637 
                        7.6a.25.25 0 0 1-.165.437H1.452a1.44 
                        1.44 0 0 0 0 2.88h17.111a.251.251 
                        0 0 1 .165.438l-8.637 
                        7.6a1.44 1.44 0 1 0 1.9 
                        2.161L23.172 13.8a2.4 2.4 
                        0 0 0 .815-1.8"
                    />
                  </svg>
                  <?= htmlspecialchars($card['buttonText'], ENT_QUOTES) ?>
                </button>
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
    <div class="font-sans text-[#1a1a1a]" id="admission">
      <div class="min-h-screen flex items-center justify-center px-4 py-8">
        <div class="bg-white/80 w-full max-w-6xl p-6 md:p-10 rounded-md shadow-lg">
        
          <h1 class="text-center text-2xl md:text-3xl font-bold text-blue-900 mb-4">
            NCST Educational System
          </h1>

        
          <div class="flex flex-col md:flex-row justify-between items-start gap-6">
      
            <div class="w-full md:w-1/2 space-y-4 text-center my-auto">
              <h2 class="text-yellow-500 font-bold text-lg">
                COLLEGE ENROLLMENT <br /> ARE NOW OPEN!
              </h2>
              <div>
                <p class="font-bold">MONDAY - FRIDAY</p>
                <p>8:00 AM – 5:00 PM</p>
              </div>
              <div>
                <p class="font-bold">SATURDAY</p>
                <p>8:00 AM – 5:00 PM</p>
              </div>
            </div>

            <div class="hidden md:block w-px bg-black h-60 mx-4 my-auto"></div>

        
            <div class="w-full md:w-1/2 space-y-2 text-center my-auto">
              <p>
                Enrollment for the 1st Semester, A.Y. 2025–2026<br />
                is now ONGOING for the following programs:
              </p>

              <div class="mt-4">
                <p class="font-bold">ARCHITECTURE DEPARTMENT</p>
                <p class="ml-4">BS in Architecture</p>
              </div>

              <div class="mt-4">
                <p class="font-bold">CRIMINAL JUSTICE DEPARTMENT</p>
                <p class="ml-4">BS in Criminology (BSCRIM)</p>
                <p class="ml-4">BS in Public Administration (BSPA)</p>
              </div>

              <div class="mt-4">
                <p class="font-bold">COMPUTER STUDIES DEPARTMENT</p>
                <p class="ml-4">BS in Information Technology (BSIT)</p>
                <p class="ml-4">BS in Computer Science (BSCS)</p>
                <p class="ml-4">Associate in Computer Technology (ACT)</p>
              </div>
            </div>
          </div>

        
          <div class="mt-8 grid grid-cols-2 md:grid-cols-4 text-center font-bold text-sm">
            <div class="bg-white py-2">MAKADIYOS</div>
            <div class="bg-blue-800 text-white py-2">MAKABAYAN</div>
            <div class="bg-red-600 text-white py-2">MAKATAO</div>
            <div class="bg-yellow-400 py-2">MAKAKALIKASAN</div>
          </div>
        </div>
      </div>
    </div>

    <!--FOOTER, SOCIALS & BOTTOM LINE-->

    <footer class="bg-[#1c2a7c] text-white pt-16 pb-0">
      <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-10">

        <div>
          <div class="flex items-center space-x-3 mb-4">
              <img src="img/NCST-Edu.png" alt="NCST Logo" class="h-12" />
          </div>
          <p class="text-gray-300 leading-relaxed mb-6">
            The National College of Science and Technology (NCST) is one of the leading educational institutions in the vast growing locality of Dasmariñas, Cavite.
          </p>
          <div class="flex space-x-4 mt-6">
            <a href="https://www.facebook.com/NCST.OfficialPage" class="text-white hover:text-yellow-400 text-xl"><i class="ri-facebook-fill"></i></a>
            <div class="hidden md:block w-px bg-black h-5 mx-4 my-auto"></div>
            <a href="https://linkedin.com/in/ncstofficial?fbclid=IwZXh0bgNhZW0CMTAAAR3OxDI99Dsfd6Ag4TnITSRfkdngjdYF0kyQ4LTqaR1URFCGKlzvcKlo9Os_aem_-w5d6tm9VTVlNTd2ZWu8TQ" class="text-white hover:text-yellow-400 text-xl"><i class="ri-linkedin-fill"></i></a>
            <a href="https://x.com/NCSTOFFICIAL?fbclid=IwZXh0bgNhZW0CMTAAAR0nQi0_JtReFv63kZaQObp-6V_Xfk0tzNRjjDkx7mhOZ6gextXe3wvJuVM_aem_GMFx4WmkJJU4PjEvQZDEUQ" class="text-white hover:text-yellow-400 text-xl"><i class="ri-twitter-fill"></i></a>
            <a href="https://www.youtube.com/@NCSTEducationSystemChannel" class="text-white hover:text-yellow-400 text-xl"><i class="ri-youtube-fill"></i></a>
            <a href="https://tiktok.com/@ncstofficial?fbclid=IwZXh0bgNhZW0CMTAAAR1qkZoMQx7pCsOYBo2Kp1ZTfyTNiP6obC6zA8CiQWIANKsipaxpE1q92Vg_aem_F6EvAiklYw0wUXkCYNQYJQ" class="text-white hover:text-yellow-400 text-xl"><i class="ri-tiktok-fill"></i></a>
          </div>
        </div>

        <div>
          <h3 class="text-xl font-semibold mb-2">Subscribe Now</h3>
          <p class="text-gray-300 mb-5">Don’t miss our future updates! Get Subscribed Today!</p>
          <form class="flex w-full max-w-md">
            <input type="email" placeholder="Your mail here" class="flex-grow px-4 py-3 rounded-l-full focus:outline-none text-black" />
            <button type="submit" class="bg-[#f2cb05] px-5 flex items-center justify-center rounded-r-full hover:bg-yellow-500 transition duration-300">
              <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 12H8m0 0l4 4m-4-4l4-4" />
              </svg>
            </button>
          </form>
        </div>
      </div>

      <div class="bg-[#f2cb05] text-[#1c2a7c] mt-12 py-4 text-center font-medium tracking-wide">
        Empowering Future Innovators — Built by NCST Developers
      </div>


    </footer>

  </div>

  <script>
    document.getElementById('navbar-toggler').addEventListener('click', () => {
      document.getElementById('navbar-menu').classList.toggle('hidden');
    });

    let expanded = null, hovered = null;
    function toggleExpand(i) {
      if (expanded === i) expanded = null;
      else expanded = i;
      updateCards();
    }
    function toggleHover(i, state) {
      hovered = state ? i : null;
      updateCards();
    }
    function updateCards() {
      <?php foreach ($aboutCards as $idx => $_): ?>
        const body<?= $idx ?> = document.getElementById('about-body-<?= $idx ?>');
        const text<?= $idx ?> = document.getElementById('about-text-<?= $idx ?>');
        if (expanded === <?= $idx ?> || hovered === <?= $idx ?>) {
          body<?= $idx ?>.style.height = '12.5rem';
          text<?= $idx ?>.classList.remove('opacity-0','translate-y-2');
          text<?= $idx ?>.classList.add('opacity-100','translate-y-0');
        } else {
          body<?= $idx ?>.style.height = '3.75rem';
          text<?= $idx ?>.classList.add('opacity-0','translate-y-2');
          text<?= $idx ?>.classList.remove('opacity-100','translate-y-0');
        }
      <?php endforeach; ?>
    }

    function openModal(id) {
      const modal = document.getElementById(`modal-${id}`);
      modal.classList.remove('hidden');
      setTimeout(() => {
        modal.querySelector('div.z-10').classList.replace('scale-95','scale-100');
        modal.querySelector('div.z-10').classList.replace('opacity-0','opacity-100');
      }, 10);
    }
    function closeModal(id) {
      const modal = document.getElementById(`modal-${id}`);
      modal.querySelector('div.z-10').classList.replace('scale-100','scale-95');
      modal.querySelector('div.z-10').classList.replace('opacity-100','opacity-0');
      setTimeout(() => modal.classList.add('hidden'), 400);
    }

    document.addEventListener('DOMContentLoaded', () => {
      const obs = new IntersectionObserver(entries => {
        entries.forEach(e => e.isIntersecting && e.target.classList.add('visible'));
      }, {threshold: .1});
      document.querySelectorAll('[data-animate]').forEach(el => obs.observe(el));
    });

    entries.forEach((e, i) => {
      if (e.isIntersecting) {
        setTimeout(() => e.target.classList.add('visible'), i * 100);
      }
    });

  </script>
</body>
</html>
