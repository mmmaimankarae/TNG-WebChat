
/* จะถูกทำทุกครั้งที่โหลดหน้าใหม่ */
document.addEventListener('DOMContentLoaded', function () {
  /* ตรวจสอบ สถานะของ sidebar */
  sideAction();

  /* เมื่อมีการปรับขนาดหน้าจอ */
  window.addEventListener('resize', handleResize);

  /* ตรวจสอบ URL ปัจจุบัน */
  const currentPage = window.location.pathname.split('/').pop();
  /* ดึงลิงค์ทั้งหมดที่มี class 'nav-style' */
  const links = document.querySelectorAll('.nav-style');
  /* วนลูปทุกลิงค์ */
  links.forEach(link => {
    let linkPath = link.getAttribute('href');
    if (linkPath) {
      linkPath = linkPath.split('/').pop();
      if (linkPath === currentPage || 
        (linkPath.includes('current-tasks') && currentPage.includes('detail-message') || currentPage.includes('assign-task')) 
        || (linkPath.includes('credit-debit') && currentPage.includes('detail-credit-debit'))) {
        link.setAttribute('aria-current', 'page');
        link.querySelector('.notiHead-style').setAttribute('noti', 'hover');
      } else {
        link.setAttribute('aria-current', 'false');
      }
    }
  });

  /* เปิด-ปิด menu hambuger */
  const headBurger = document.getElementById('head-buger');
  if (headBurger) {
    headBurger.addEventListener('click', toggleMenu);
  }

  /* เปิด-ปิด menu user dropdown */
  document.getElementById('head-user').addEventListener('click', toggleUserMenu);

  /* เปิด sidebar */  
  const openSide = document.getElementById('open-side');
  if (openSide) {
    openSide.addEventListener('click', toggleSidebar);
  }
  /* ปิด sidebar */
  const closeSide = document.getElementById('close-side');
  if (closeSide) {
    closeSide.addEventListener('click', toggleSidebar);
  }
});

function toggleMenu() {
  const menu = document.getElementById('menu-buger');
  menu.classList.toggle('hidden');
}

function toggleUserMenu() {
  const menu = document.getElementById('menu-user');
  menu.classList.toggle('hidden');
}

function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  const sideTab = document.getElementById('side-tab');
  if (sidebar && sideTab) {
    sidebar.classList.toggle('hidden');
    sideTab.classList.toggle('hidden');
  }
  sideAction();
}

function sideAction() {
  const sidebar = document.getElementById('sidebar');
  const content = document.getElementById('content');
  if (sidebar.classList.contains('hidden')) {
    content.classList.remove('shifted');
  } else {
    content.classList.add('shifted');
  }
}

function handleResize() {
  const menu = document.getElementById('menu-buger');
  const userDropdown = document.getElementById('menu-user');
  const isDesktop = window.innerWidth >= 480;

  /* ปิด menu หากหน้าจอมีขนาดใหญ่ */
  if (isDesktop && menu && !menu.classList.contains('hidden')) {
    menu.classList.add('hidden');
  }

  /* ปิด user dropdown ถ้ามีการขยับหน้าจอ */
  if (userDropdown && !userDropdown.classList.contains('hidden')) {
    userDropdown.classList.add('hidden');
  }

  const sidebar = document.getElementById('sidebar');
  const sideTab = document.getElementById('side-tab');
  /* ปิด sidebar ถ้าหน้าจอมีขนาดเล็ก */
  if (!isDesktop && sideTab && sideTab.classList.contains('hidden')) {
    sidebar.classList.add('hidden');
    sideTab.classList.remove('hidden');
  }
  sideAction();
}