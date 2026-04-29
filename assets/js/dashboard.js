// ── Dashboard ─────────────────────────────────────────
let dashInit = false;
function initDashboard() {
  if (dashInit) return;
  dashInit = true;
  document.querySelectorAll('.bar[data-pct]').forEach(bar => {
    setTimeout(() => { bar.style.height = bar.dataset.pct + '%'; }, 100 + Math.random() * 300);
  });
  animateDonut();
}

function animateDonut() {
  document.querySelectorAll('.donut-ring').forEach(circle => {
    const target = parseFloat(circle.dataset.offset || 0);
    const circumference = 2 * Math.PI * 15.9;
    circle.style.strokeDasharray  = circumference;
    circle.style.strokeDashoffset = circumference;
    setTimeout(() => {
      circle.style.transition = 'stroke-dashoffset 1.1s ease';
      circle.style.strokeDashoffset = circumference * (1 - target / 100);
    }, 250);
  });
}

// ── Reportes charts ───────────────────────────────────
let repInit = false;
function initReportCharts() {
  if (repInit) return;
  repInit = true;
  document.querySelectorAll('.bar[data-pct]').forEach(bar => {
    setTimeout(() => { bar.style.height = bar.dataset.pct + '%'; }, 100 + Math.random() * 300);
  });
  animateDonut();
}

