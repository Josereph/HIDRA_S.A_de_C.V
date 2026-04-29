'use strict';

// ── DOM ─────────────────────────────────────────────
const sidebar   = document.getElementById('sidebar');
const toggleBtn = document.getElementById('sidebarToggle');
const navItems  = document.querySelectorAll('.nav-item[data-view]');
const views     = document.querySelectorAll('.view');
const breadPage = document.getElementById('breadPage');
const modalOverlay = document.getElementById('clienteModal');

// ── Sidebar ──────────────────────────────────────────
const brandLogo     = document.querySelector('.brand-logo');
const brandLogoIcon = document.querySelector('.brand-logo-icon');

function setSidebarState(collapsed, animate = true) {
  if (!animate) sidebar.style.transition = 'none';

  if (collapsed) {
    sidebar.classList.add('collapsed');
    // Fade out logo completo → fade in tridente
    if (brandLogo) {
      brandLogo.style.animation = 'none';
      brandLogo.style.opacity   = '0';
      brandLogo.style.transform = 'scale(0.75)';
    }
    setTimeout(() => {
      if (brandLogoIcon) {
        brandLogoIcon.style.animation =
          'logoEntrance 0.4s cubic-bezier(0.34,1.56,0.64,1) both, logoGlow 4s ease-in-out 0.5s infinite, logoFloat 5s ease-in-out 0.5s infinite';
      }
    }, 120);
  } else {
    sidebar.classList.remove('collapsed');
    // Fade out tridente → fade in logo completo
    if (brandLogoIcon) {
      brandLogoIcon.style.animation = 'none';
      brandLogoIcon.style.opacity   = '0';
    }
    setTimeout(() => {
      if (brandLogo) {
        brandLogo.style.animation =
          'logoEntrance 0.6s cubic-bezier(0.34,1.56,0.64,1) both, logoGlow 4s ease-in-out 0.7s infinite, logoFloat 5s ease-in-out 0.7s infinite';
        brandLogo.style.opacity   = '';
        brandLogo.style.transform = '';
      }
    }, 120);
  }

  if (!animate) requestAnimationFrame(() => { sidebar.style.transition = ''; });
  localStorage.setItem('hidra_sidebar', collapsed ? '1' : '0');
}

toggleBtn?.addEventListener('click', () => {
  setSidebarState(!sidebar.classList.contains('collapsed'));
});

// Restaurar estado guardado sin animación
const savedState = localStorage.getItem('hidra_sidebar') === '1';
setSidebarState(savedState, false);

