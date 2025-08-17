const toggle = () => document.body.classList.toggle('sidebar-collapsed');
document.getElementById('menu-btn').addEventListener('click', toggle);
document.getElementById('toggle-btn').addEventListener('click', toggle);
// src/js/portals.js
document.addEventListener('DOMContentLoaded', () => {
  const sections = Array.from(document.querySelectorAll('main > div[id]'));
  const navLinks = Array.from(document.querySelectorAll('#sidebar nav ul li a'));

  // Hide all but the first section on load
  sections.forEach((sec, idx) => {
    if (idx !== 0) sec.classList.add('hidden');
  });

  // Function to toggle visibility
  function showSection(id) {
    sections.forEach(sec => {
      sec.classList.toggle('hidden', sec.id !== id);
    });
  }

  // Attach click handlers
  navLinks.forEach(link => {
    link.addEventListener('click', event => {
      const href = link.getAttribute('href') || '';
      if (!href.startsWith('#') || href === '#') return; // skip non-anchor links

      event.preventDefault();
      const targetId = href.slice(1);        // remove the '#'
      showSection(targetId);
    });
  });

  const fmt = new Intl.DateTimeFormat('en-US', {
    timeZone: 'Asia/Manila',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    hour12: false
  });
  const el = document.getElementById('lastLogin');
  if (el) el.textContent = fmt.format(new Date());

  if (window.jQuery && $.fn.DataTable) {
    document.querySelectorAll('table.data-table').forEach(table => {
      $(table).DataTable({
        responsive: true,
        autoWidth: false,
        pageLength: 10,
        lengthChange: true,
        searching:   true,
        ordering:    true
      });
    });
  }
});
