// src/js/portals.js
document.addEventListener('DOMContentLoaded', () => {
  // 1) Sidebar collapse/expand
  const toggle = () => document.body.classList.toggle('sidebar-collapsed');
  const menuBtn   = document.getElementById('menu-btn');
  const toggleBtn = document.getElementById('toggle-btn');
  if (menuBtn)   menuBtn.addEventListener('click', toggle);
  if (toggleBtn) toggleBtn.addEventListener('click', toggle);

  // 3) Last-login timestamp
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
});
