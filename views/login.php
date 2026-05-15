<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>HIDRA — Acceso al sistema</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700;900&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { height: 100%; font-family: 'Outfit', sans-serif; }

    /* ── Background ── */
    body {
      min-height: 100vh;
      background: #000;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      position: relative;
    }

    /* ── Card ── */
    .login-card {
      position: relative;
      z-index: 10;
      width: 100%;
      max-width: 420px;
      margin: 20px;
      background: #111;
      border: 1px solid #333;
      border-radius: 8px;
      padding: 48px 40px;
      animation: cardIn 0.7s cubic-bezier(0.34,1.56,0.64,1) both;
    }
    @keyframes cardIn {
      from { opacity: 0; transform: translateY(30px) scale(0.95); }
      to   { opacity: 1; transform: translateY(0)    scale(1); }
    }

    /* ── Logo ── */
    .login-logo {
      text-align: center;
      margin-bottom: 32px;
    }
    .login-logo img {
      max-width: 200px;
      height: auto;
      filter: brightness(0) invert(1);
    }
    @keyframes logoIn {
      from { opacity: 0; transform: scale(0.8); }
      to   { opacity: 1; transform: scale(1); }
    }
    .login-tagline {
      font-size: 0.72rem;
      letter-spacing: 3px;
      text-transform: uppercase;
      color: rgba(102,179,255,0.7);
      text-align: center;
      margin-top: 10px;
      font-weight: 500;
    }

    /* ── Form ── */
    .login-divider {
      height: 1px;
      background: #333;
      margin: 28px 0;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 6px;
      margin-bottom: 18px;
    }
    .form-label {
      font-size: 0.7rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: rgba(255,255,255,0.5);
    }
    .input-wrap {
      position: relative;
    }
    .input-icon {
      position: absolute;
      left: 14px; top: 50%;
      transform: translateY(-50%);
      font-size: 0.85rem;
      color: rgba(102,179,255,0.5);
      pointer-events: none;
      transition: color 0.2s;
    }
    .form-control {
      width: 100%;
      background: rgba(255,255,255,0.04);
      border: 1.5px solid rgba(102,179,255,0.15);
      border-radius: 10px;
      color: #fff;
      padding: 12px 14px 12px 42px;
      font-size: 0.88rem;
      font-family: 'Outfit', sans-serif;
      transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
      outline: none;
    }
    .form-control:focus {
      border-color: #66B3FF;
      background: rgba(102,179,255,0.06);
      box-shadow: 0 0 0 3px rgba(102,179,255,0.1);
    }
    .form-control:focus + .input-icon,
    .input-wrap:focus-within .input-icon { color: #66B3FF; }
    .form-control::placeholder { color: rgba(255,255,255,0.25); }

    /* ── Options row ── */
    .login-options {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 24px;
    }
    .remember-me {
      display: flex;
      align-items: center;
      gap: 7px;
      font-size: 0.78rem;
      color: rgba(255,255,255,0.45);
      cursor: pointer;
    }
    .remember-me input[type="checkbox"] { accent-color: #66B3FF; }
    .forgot-link {
      font-size: 0.78rem;
      color: rgba(102,179,255,0.7);
      text-decoration: none;
      transition: color 0.2s;
    }
    .forgot-link:hover { color: #66B3FF; }

    /* ── Button ── */
    .btn-login {
      width: 100%;
      padding: 14px;
      border-radius: 8px;
      background: #66B3FF;
      color: #000;
      font-weight: 800;
      font-size: 0.9rem;
      font-family: 'Outfit', sans-serif;
      letter-spacing: 0.5px;
      border: none;
      cursor: pointer;
    }
    .btn-login:hover {
      background: #3E96F0;
    }
    .btn-login:hover::before { opacity: 1; }
    .btn-login:active { transform: translateY(0) scale(0.98); }
    .btn-login.loading { pointer-events: none; opacity: 0.7; }

    /* Loading spinner inside button */
    .btn-spinner {
      display: none;
      width: 16px; height: 16px;
      border: 2px solid rgba(0,0,0,0.3);
      border-top-color: #000;
      border-radius: 50%;
      animation: spin 0.6s linear infinite;
      margin: 0 auto;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .btn-login.loading .btn-text { display: none; }
    .btn-login.loading .btn-spinner { display: block; }

    /* ── Error message ── */
    .error-msg {
      display: none;
      background: rgba(198,40,40,0.1);
      border: 1px solid rgba(198,40,40,0.35);
      border-radius: 8px;
      padding: 10px 14px;
      font-size: 0.8rem;
      color: #ef9a9a;
      margin-bottom: 16px;
    }
    .error-msg.show { display: flex; gap: 8px; align-items: center; }

    /* ── Footer ── */
    .login-footer {
      margin-top: 32px;
      text-align: center;
      font-size: 0.68rem;
      color: rgba(255,255,255,0.2);
      letter-spacing: 0.5px;
    }

    /* ── Demo badge ── */
    .demo-badge {
      position: fixed;
      bottom: 20px; right: 20px;
      background: rgba(102,179,255,0.12);
      border: 1px solid rgba(102,179,255,0.25);
      border-radius: 8px;
      padding: 8px 14px;
      font-size: 0.72rem;
      color: rgba(102,179,255,0.8);
      z-index: 100;
    }
    .demo-badge strong { color: #66B3FF; }
  </style>
</head>
<body>



  <div class="login-card">

    <div class="login-logo">
      <img src="../assets/img/logos/HIDRA.png" alt="HIDRA S.A. de C.V." />
      <div class="login-tagline">Sistema Facturador de Agua</div>
    </div>

    <div class="login-divider"></div>

    <div class="error-msg" id="errorMsg">
      <span><i class="fas fa-exclamation-triangle"></i></span>
      <span>Usuario o contraseña incorrectos.</span>
    </div>

    <form id="loginForm" onsubmit="doLogin(event)" novalidate>

      <div class="form-group">
        <label class="form-label" for="email">Usuario o correo</label>
        <div class="input-wrap">
          <input
            class="form-control"
            type="text"
            id="email"
            placeholder="admin@hidra.sv"
            autocomplete="username"
            required
          />
          <span class="input-icon"><i class="fas fa-envelope"></i></span>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label" for="password">Contraseña</label>
        <div class="input-wrap">
          <input
            class="form-control"
            type="password"
            id="password"
            placeholder="••••••••"
            autocomplete="current-password"
            required
          />
          <span class="input-icon"><i class="fas fa-lock"></i></span>
        </div>
      </div>

      <div class="login-options">
        <label class="remember-me">
          <input type="checkbox" id="remember" />
          Recordar sesión
        </label>
        <a href="#" class="forgot-link">¿Olvidé mi contraseña?</a>
      </div>

      <button type="submit" class="btn-login" id="btnLogin">
        <span class="btn-text">Acceder al sistema</span>
        <span class="btn-spinner"></span>
      </button>

    </form>

    <div class="login-footer">
      HIDRA S.A. de C.V. — Sistema de Facturación v1.0<br>
      © 2026 · Todos los derechos reservados
    </div>

  </div>

  <!-- Demo hint -->
  <div class="demo-badge">
    Demo: <strong>admin</strong> / <strong>admin123</strong>
  </div>

  <script>
    function doLogin(e) {
      e.preventDefault();

      const email    = document.getElementById('email').value.trim();
      const password = document.getElementById('password').value;
      const btn      = document.getElementById('btnLogin');
      const errMsg   = document.getElementById('errorMsg');

      // Ocultar error previo
      errMsg.classList.remove('show');

      if (!email || !password) return;

      // Simular carga
      btn.classList.add('loading');

      fetch('../api/auth_api.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ identificador: email, password: password })
      })
      .then(async response => {
        if (!response.ok) {
           const text = await response.text();
           throw new Error('HTTP ' + response.status + ': ' + text);
        }
        const text = await response.text();
        try {
           return JSON.parse(text);
        } catch (e) {
           throw new Error('JSON Error: ' + text);
        }
      })
      .then(data => {
        btn.classList.remove('loading');
        if (data.success) {
          // Redirigir a pantalla de bienvenida
          window.location.href = './welcome.php';
        } else {
          errMsg.querySelector('span:nth-child(2)').textContent = data.message;
          errMsg.classList.add('show');
          // Shake effect
          document.querySelector('.login-card').style.animation = 'none';
          document.querySelector('.login-card').offsetHeight;
          document.querySelector('.login-card').style.animation = 'shake 0.4s ease';
        }
      })
      .catch(error => {
        btn.classList.remove('loading');
        errMsg.querySelector('span:nth-child(2)').textContent = error.message;
        errMsg.classList.add('show');
      });
    }

    // Shake keyframe inyectado
    const s = document.createElement('style');
    s.textContent = `@keyframes shake {
      0%,100% { transform: translateX(0); }
      20%      { transform: translateX(-8px); }
      40%      { transform: translateX(8px); }
      60%      { transform: translateX(-5px); }
      80%      { transform: translateX(5px); }
    }`;
    document.head.appendChild(s);
  </script>

</body>
</html>
