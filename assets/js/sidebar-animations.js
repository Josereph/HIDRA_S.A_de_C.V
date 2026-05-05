'use strict';

/* ══════════════════════════════════════════════════════
   SIDEBAR ANIMATIONS — HIDRA v3
   Archivo extra dedicado a las animaciones avanzadas
   del sidebar: logo, icono, partículas y efectos hover.
══════════════════════════════════════════════════════ */

(function () {

  const sidebar       = document.getElementById('sidebar');
  const brandLogo     = document.querySelector('.brand-logo');
  const brandLogoIcon = document.querySelector('.brand-logo-icon');

  if (!sidebar) return;

  /* ── Observa cambios en la clase collapsed ─────────── */
  const observer = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
      if (mutation.attributeName === 'class') {
        const isCollapsed = sidebar.classList.contains('collapsed');
        handleLogoTransition(isCollapsed);
      }
    });
  });

  observer.observe(sidebar, { attributes: true });

  /* ── Transición de logos ────────────────────────────── */
  function handleLogoTransition(isCollapsed) {
    if (isCollapsed) {
      animateOut(brandLogo, () => {
        animateIn(brandLogoIcon, 'icon');
      });
    } else {
      animateOut(brandLogoIcon, () => {
        animateIn(brandLogo, 'logo');
      });
    }
  }

  function animateOut(el, cb) {
    if (!el) { cb?.(); return; }
    el.style.transition = 'opacity 0.15s ease, transform 0.15s ease';
    el.style.opacity    = '0';
    el.style.transform  = 'scale(0.7)';
    setTimeout(() => { el.style.display = 'none'; cb?.(); }, 160);
  }

  function animateIn(el, type) {
    if (!el) return;
    el.style.display    = 'block';
    el.style.opacity    = '0';
    el.style.transform  = type === 'icon' ? 'scale(0.4) rotate(-20deg)' : 'scale(0.7)';
    el.style.transition = 'none';
    void el.offsetWidth; // reflow

    const keyframe = type === 'icon'
      ? 'iconPop    0.4s cubic-bezier(0.34,1.56,0.64,1) both, logoGlow 4s ease-in-out 0.5s infinite, logoFloat 5s ease-in-out 0.5s infinite'
      : 'logoEntrance 0.55s cubic-bezier(0.34,1.56,0.64,1) both, logoGlow 4s ease-in-out 0.7s infinite, logoFloat 5s ease-in-out 0.7s infinite';

    el.style.transition = '';
    el.style.opacity    = '';
    el.style.transform  = '';
    el.style.animation  = keyframe;
  }

  /* ── Efecto ripple en nav-items ──────────────────────── */
  document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', function (e) {
      const ripple = document.createElement('span');
      const rect   = this.getBoundingClientRect();
      const size   = Math.max(rect.width, rect.height);
      const x      = e.clientX - rect.left - size / 2;
      const y      = e.clientY - rect.top  - size / 2;

      ripple.style.cssText = `
        position: absolute;
        width: ${size}px; height: ${size}px;
        left: ${x}px; top: ${y}px;
        background: rgba(102,179,255,0.25);
        border-radius: 50%;
        transform: scale(0);
        animation: rippleEffect 0.5s ease-out forwards;
        pointer-events: none;
      `;

      // Asegurar position relative en item
      this.style.position = 'relative';
      this.style.overflow = 'hidden';
      this.appendChild(ripple);
      setTimeout(() => ripple.remove(), 550);
    });
  });

  /* ── Keyframe ripple (inyectar si no existe) ─────────── */
  if (!document.getElementById('hidra-anim-extra')) {
    const style = document.createElement('style');
    style.id    = 'hidra-anim-extra';
    style.textContent = `
      @keyframes rippleEffect {
        to { transform: scale(2.5); opacity: 0; }
      }
      @keyframes iconPop {
        0%   { opacity: 0; transform: scale(0.4) rotate(-20deg); }
        60%  { opacity: 1; transform: scale(1.15) rotate(5deg); }
        100% { opacity: 1; transform: scale(1)   rotate(0deg); }
      }
    `;
    document.head.appendChild(style);
  }

})();
