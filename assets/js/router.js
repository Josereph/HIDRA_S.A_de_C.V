// ── Router ───────────────────────────────────────────
const LABELS = {
  dashboard:   'Dashboard',
  clientes:    'Clientes',
  territorio:  'Territorio',
  operaciones: 'Operaciones',
  reportes:    'Reportes',
  config:      'Configuración',
};

function showView(name) {
  views.forEach(v => v.classList.remove('active'));
  navItems.forEach(n => n.classList.remove('active'));

  const target = document.getElementById(`view-${name}`);
  if (target) target.classList.add('active');

  document.querySelector(`.nav-item[data-view="${name}"]`)?.classList.add('active');
  if (breadPage) breadPage.textContent = LABELS[name] || name;

  if (name === 'dashboard') initDashboard();
  if (name === 'territorio') initMapa();
  if (name === 'reportes') initReportCharts();

  localStorage.setItem('hidra_lastView', name);
}

navItems.forEach(item => item.addEventListener('click', () => showView(item.dataset.view)));

// ── Tabs ─────────────────────────────────────────────
document.querySelectorAll('.section-tab').forEach(tab => {
  tab.addEventListener('click', () => {
    const panel = tab.dataset.panel;
    const group = tab.closest('.section-tabs').dataset.group;

    document.querySelectorAll(`.section-tabs[data-group="${group}"] .section-tab`).forEach(t => t.classList.remove('active'));
    document.querySelectorAll(`.tab-panel[data-group="${group}"]`).forEach(p => p.classList.remove('active'));

    tab.classList.add('active');
    const target = document.querySelector(`.tab-panel[data-panel="${panel}"][data-group="${group}"]`)
                || document.querySelector(`.tab-panel[data-panel="${panel}"]`);
    if (target) target.classList.add('active');
  });
});

