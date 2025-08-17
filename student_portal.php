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
          <?php foreach (['HOME','#about','LOGIN'] as $label): ?>
            <?php $uri = $label === 'HOME' ? '#' : strtolower($label); ?>
            <li>
              <a href="<?= $label==='LOGIN'?'/login.php':$uri ?>"
                 class="relative group text-white px-2 py-2">
                <?= $label ?>
                <span class="absolute left-0 bottom-0 h-0.5 bg-yellow-300 w-0 transition-all duration-300 group-hover:w-full"></span>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </nav>

  <div class="bg-gradient-to-br from-indigo-50 to-white text-gray-900 text-lg">

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
