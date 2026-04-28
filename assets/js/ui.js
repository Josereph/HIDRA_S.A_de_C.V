// ── Modal ─────────────────────────────────────────────
function openModal() {
  if (modalOverlay) {
    modalOverlay.style.display = 'flex';
    requestAnimationFrame(() => modalOverlay.classList.add('visible'));
  }
}

function closeModal() {
  if (modalOverlay) {
    modalOverlay.classList.remove('visible');
    setTimeout(() => { modalOverlay.style.display = 'none'; }, 280);
  }
}

document.getElementById('btnNuevoCliente')?.addEventListener('click', openModal);
document.getElementById('modalClose')?.addEventListener('click', closeModal);
modalOverlay?.addEventListener('click', e => { if (e.target === modalOverlay) closeModal(); });
document.getElementById('formCliente')?.addEventListener('submit', e => {
  e.preventDefault(); closeModal(); showToast('Cliente registrado correctamente', 'success');
});

// ── Búsqueda ──────────────────────────────────────────
document.getElementById('clienteSearch')?.addEventListener('input', e => {
  const q = e.target.value.toLowerCase();
  document.querySelectorAll('#clienteTabla tr').forEach(row => {
    row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
  });
});

// ── Toast ─────────────────────────────────────────────
function showToast(msg, type = 'info') {
  const container = document.getElementById('toastContainer');
  if (!container) return;
  const icons = { success: '✓', danger: '✕', info: '💧', warning: '⚠' };
  const toast = document.createElement('div');
  toast.className = `toast toast-${type}`;
  toast.innerHTML = `<span>${icons[type] || ''} ${msg}</span><button onclick="this.parentElement.remove()">✕</button>`;
  container.appendChild(toast);
  requestAnimationFrame(() => toast.classList.add('show'));
  setTimeout(() => { toast.classList.remove('show'); setTimeout(() => toast.remove(), 300); }, 3800);
}

// Exponer globalmente
window.openModal  = openModal;
window.closeModal = closeModal;
window.showView   = showView;
window.showToast  = showToast;

// ── Init ──────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  const last = localStorage.getItem('hidra_lastView') || 'dashboard';
  showView(last);
});
