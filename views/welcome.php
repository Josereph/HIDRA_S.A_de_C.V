<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>HIDRA — Bienvenido</title>
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700;900&family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #000;
      font-family: 'Outfit', sans-serif;
      overflow: hidden;
    }

    .bg-stripe {
      position: fixed;
      top: 0; left: 0; right: 0;
      height: 3px;
      background: linear-gradient(90deg, #000, #66B3FF, #A8D4FF, #66B3FF, #000);
      background-size: 300% 100%;
      animation: stripe 4s linear infinite;
    }
    @keyframes stripe { to { background-position: 300% 0%; } }

    .orb {
      position: fixed;
      border-radius: 50%;
      filter: blur(100px);
      pointer-events: none;
    }
    .orb-1 { width: 500px; height: 500px; background: rgba(102,179,255,0.08); top: -150px; right: -100px; }
    .orb-2 { width: 350px; height: 350px; background: rgba(102,179,255,0.05); bottom: -100px; left: -80px; }

    .welcome-card {
      position: relative;
      z-index: 10;
      text-align: center;
      padding: 60px 50px;
      max-width: 500px;
      width: 100%;
      margin: 20px;
    }

    .logo-wrap {
      margin-bottom: 40px;
      animation: fadeIn 0.8s ease both;
    }
    .logo-wrap img {
      max-width: 220px;
      filter: brightness(0) invert(1);
      animation: logoFloat 4s ease-in-out infinite;
    }
    @keyframes logoFloat {
      0%,100% { transform: translateY(0); }
      50%      { transform: translateY(-8px); }
    }

    .welcome-text {
      animation: fadeIn 0.6s ease 0.3s both;
    }
    .welcome-greeting {
      font-size: 0.8rem;
      letter-spacing: 3px;
      text-transform: uppercase;
      color: rgba(102,179,255,0.6);
      margin-bottom: 12px;
    }
    .welcome-name {
      font-family: 'Cinzel', serif;
      font-size: 2rem;
      font-weight: 700;
      color: #fff;
      margin-bottom: 8px;
      text-shadow: 0 0 30px rgba(102,179,255,0.4);
    }
    .welcome-role {
      font-size: 0.82rem;
      color: rgba(255,255,255,0.4);
      margin-bottom: 48px;
    }
    .welcome-role span {
      display: inline-block;
      background: rgba(102,179,255,0.12);
      border: 1px solid rgba(102,179,255,0.2);
      border-radius: 20px;
      padding: 4px 14px;
      color: #66B3FF;
    }

    /* Progress bar */
    .progress-wrap {
      margin-bottom: 16px;
      animation: fadeIn 0.6s ease 0.5s both;
    }
    .progress-label {
      font-size: 0.72rem;
      color: rgba(255,255,255,0.3);
      margin-bottom: 10px;
      letter-spacing: 1px;
    }
    .progress-track {
      height: 4px;
      background: rgba(255,255,255,0.08);
      border-radius: 4px;
      overflow: hidden;
    }
    .progress-fill {
      height: 100%;
      background: linear-gradient(90deg, #66B3FF, #A8D4FF);
      border-radius: 4px;
      width: 0%;
      transition: width 0.1s linear;
      box-shadow: 0 0 10px rgba(102,179,255,0.6);
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(16px); }
      to   { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

  <div class="bg-stripe"></div>
  <div class="orb orb-1"></div>
  <div class="orb orb-2"></div>

  <div class="welcome-card">

    <div class="logo-wrap">
      <img src="../assets/img/logos/HIDRA.png" alt="HIDRA" />
    </div>

    <div class="welcome-text">
      <div class="welcome-greeting">Bienvenido de nuevo</div>
      <div class="welcome-name">Samuel Admin</div>
      <div class="welcome-role"><span>Administrador</span></div>

      <div class="progress-wrap">
        <div class="progress-label">Cargando sistema…</div>
        <div class="progress-track">
          <div class="progress-fill" id="progressFill"></div>
        </div>
      </div>
    </div>

  </div>

  <script>
    // Animate progress bar then redirect
    const fill = document.getElementById('progressFill');
    let pct = 0;
    const interval = setInterval(() => {
      pct += 2;
      fill.style.width = pct + '%';
      if (pct >= 100) {
        clearInterval(interval);
        setTimeout(() => {
          window.location.href = './layouts/index.php';
        }, 200);
      }
    }, 30); // ~1.5s total
  </script>

</body>
</html>
