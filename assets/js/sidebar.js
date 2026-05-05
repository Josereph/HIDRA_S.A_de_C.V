'use strict';

/* ══════════════════════════════════════════════════════
   SIDEBAR — HIDRA v3
   Animaciones al expandir/colapsar con logo / icono-trinche
══════════════════════════════════════════════════════ */

const sidebar      = document.getElementById('sidebar');
const toggleBtn    = document.getElementById('sidebarToggle');
const navItems     = document.querySelectorAll('.nav-item[data-view]');
const views        = document.querySelectorAll('.view');
const breadPage    = document.getElementById('breadPage');
const modalOverlay = document.getElementById('clienteModal');

// ── Referencias de logos ──────────────────────────────
const brandLogo     = document.querySelector('.brand-logo');
const brandLogoIcon = document.querySelector('.brand-logo-icon');

// ── Utilidad: limpiar animaciones CSS ────────────────
function clearAnim(el) {
  if (!el) return;
  el.style.animation = 'none';
  el.style.opacity   = '';
  el.style.transform = '';
  // Forzar reflow para que la animación se pueda volver a aplicar
  void el.offsetWidth;
}

/* ══════════════════════════════════════════════════════
   setSidebarState
   collapsed  {boolean}  — true = colapsado, false = expandido
   animate    {boolean}  — false = sin transición (para restaurar estado)
══════════════════════════════════════════════════════ */
function setSidebarState(collapsed, animate = true) {

  if (!animate) {
    sidebar.style.transition = 'none';
  }

  if (collapsed) {
    /* ── COLAPSAR ── */
    sidebar.classList.add('collapsed');

    // 1. Ocultar logo completo con fade-out
    if (brandLogo) {
      brandLogo.style.animation = 'none';
      brandLogo.style.opacity   = '0';
      brandLogo.style.transform = 'scale(0.75)';
    }

    // 2. Mostrar icono/trinche con animación de entrada + glow + flotación
    setTimeout(() => {
      if (brandLogoIcon) {
        clearAnim(brandLogoIcon);
        brandLogoIcon.style.animation = [
          'iconPop    0.4s cubic-bezier(0.34,1.56,0.64,1) both',
          'logoGlow   4s ease-in-out 0.5s infinite',
          'logoFloat  5s ease-in-out 0.5s infinite'
        ].join(', ');
        brandLogoIcon.style.opacity = '1';
      }
    }, 120);

  } else {
    /* ── EXPANDIR ── */
    sidebar.classList.remove('collapsed');

    // 1. Ocultar icono/trinche con fade-out
    if (brandLogoIcon) {
      brandLogoIcon.style.animation = 'none';
      brandLogoIcon.style.opacity   = '0';
      brandLogoIcon.style.transform = 'scale(0.5)';
    }

    // 2. Mostrar logo completo con animación de entrada + glow + flotación
    setTimeout(() => {
      if (brandLogo) {
        clearAnim(brandLogo);
        brandLogo.style.animation = [
          'logoEntrance 0.6s cubic-bezier(0.34,1.56,0.64,1) both',
          'logoGlow     4s ease-in-out 0.7s infinite',
          'logoFloat    5s ease-in-out 0.7s infinite'
        ].join(', ');
        brandLogo.style.opacity   = '';
        brandLogo.style.transform = '';
      }
    }, 120);
  }

  if (!animate) {
    requestAnimationFrame(() => {
      sidebar.style.transition = '';
    });
  }

  localStorage.setItem('hidra_sidebar', collapsed ? '1' : '0');
}

// ── Toggle ────────────────────────────────────────────
toggleBtn?.addEventListener('click', () => {
  const isCollapsed = sidebar.classList.contains('collapsed');
  setSidebarState(!isCollapsed);
});

// ── Restaurar estado guardado (sin animación) ─────────
const savedState = localStorage.getItem('hidra_sidebar') === '1';
setSidebarState(savedState, false);

// ── Agregar tooltips a los nav-items ─────────────────
navItems.forEach(item => {
  const label = item.querySelector('.nav-label');
  if (label && !item.dataset.tooltip) {
    item.setAttribute('data-tooltip', label.textContent.trim());
  }
});

