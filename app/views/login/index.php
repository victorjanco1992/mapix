<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= NAME_EMPRESA ?></title>
    <link rel="stylesheet" href="<?= url_assets() ?>assets/css/uno.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>

<body>

    <div id="map"></div>

     <main class="container">
    <div class="language-selector">
      <select id="languageSelect">
        <option value="es">Español</option>
        <option value="en">English</option>
        <option value="fr">Français</option>
      </select>
    </div>

    <h1>Bienvenido a <span style="color:#ff9800;">MAPIX</span></h1>
    
    <!-- Descargo de responsabilidad prominente -->
    <div class="disclaimer-box">
      <h3>⚠️ IMPORTANTE - Lea antes de continuar</h3>
      <p>Al usar MAPIX acepta que:</p>
      <ul>
        <li>Es mayor de 18 años</li>
        <li>Actúa bajo su propia responsabilidad</li>
        <li>Respetará la propiedad privada</li>
        <li>No dañará ni vandalizará lugares</li>
        <li>Verificará la información antes de visitar cualquier lugar</li>
      </ul>
      <label class="disclaimer-check">
        <input type="checkbox" id="acceptDisclaimer" required>
        <span>He leído y acepto los términos y condiciones</span>
      </label>
    </div>

    <div class="btn-group" id="start-buttons" style="display: none;">
      <button id="loginBtn">Iniciar sesión</button>
      <button id="registerBtn">Registrarse</button>
    </div>

    <!-- Formulario inicio sesión -->
    <form id="loginForm">
      <label for="emailLogin">Correo electrónico</label>
      <input type="email" id="emailLogin" name="email" placeholder="Correo electrónico" required />

      <label for="passwordLogin">Contraseña</label>
      <input type="password" id="passwordLogin"   name="password" placeholder="Contraseña" required />
      <button type="submit" class="submit-btn">Entrar</button>
      
      <!-- Separador -->
      <div class="separator">
        <span>O continúa con</span>
      </div>
      
      <!-- Botón de Google -->
      <div id="g_id_onload"
           data-client_id="YOUR_GOOGLE_CLIENT_ID"
           data-context="signin"
           data-ux_mode="popup"
           data-callback="handleCredentialResponse">
      </div>
      <div class="g_id_signin" 
           data-type="standard"
           data-shape="rectangular"
           data-theme="outline"
           data-text="signin_with"
           data-size="large"
           data-logo_alignment="left">
      </div>
      
      <p class="switch-msg" id="toRegister">¿No tienes cuenta? Regístrate</p>
    </form>

    <!-- Formulario registro -->
    <form id="registerForm">
      <label for="nameRegister">Nombre completo</label>
      <input type="text" id="nameRegister" name="name" placeholder="Tu nombre completo" required />
      <label for="emailRegister">Correo electrónico</label>
      <input type="email" id="emailRegister" name="email" placeholder="Correo electrónico" required />
      <label for="passwordRegister">Contraseña</label>
      <input type="password" id="passwordRegister" name="password" placeholder="Contraseña (mín. 6 caracteres)" required minlength="6" />
    
      <!-- Descargo en registro -->
      <div class="register-disclaimer">
        <label class="disclaimer-check">
          <input type="checkbox" id="registerDisclaimer" required>
          <span>Acepto los términos de uso y soy mayor de 18 años</span>
        </label>
      </div>
      
      <button type="submit" class="submit-btn">Registrar</button>
      
      <!-- Separador -->
      <div class="separator">
        <span>O regístrate con</span>
      </div>
      
      <!-- Botón de Google para registro -->
      <div class="google-register-btn" onclick="handleGoogleRegister()">
        <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google">
        <span>Registrarse con Google</span>
      </div>
      
      <p class="switch-msg" id="toLogin">¿Ya tienes cuenta? Inicia sesión</p>
    </form>
     
    <a href="https://discord.com/invite/mapix-urbex" target="_blank" class="discord-link">
      <img src="https://cdn-icons-png.flaticon.com/512/2111/2111370.png" alt="Discord" class="discord-icon">
      Únete a nuestra comunidad de Discord
    </a>

    <div class="join-button-wrapper">
      <button class="join-button" onclick="scrollToWarnings()">ÚNETE A LA COMUNIDAD</button>
    </div>

    <div class="warnings" id="warnings-section">
      <div class="warning-box">
        <h2>⚠️ ¡Advertencia importante!</h2>
        <ul>
          <li>El sitio es colaborativo, por lo que contiene muchos <strong>errores</strong>. ¡Verifica siempre la información!</li>
          <li>Este sitio es solo para <strong>fotografía</strong>. En muchos países <u>no tienes derecho a entrar</u> sin permiso.</li>
          <li><strong>Respeta</strong> los lugares: no marques, no rompas, no dejes nada.</li>
          <li>Usted es <strong>totalmente responsable</strong> de sus acciones.</li>
          <li>Usar MAPIX es aceptar que usted es responsable. <strong>Prohibido para menores</strong>.</li>
          <li>Para cualquier incumplimiento de estas reglas básicas, <strong>MAPIX no dudará en compartir TODOS sus metadatos con las autoridades</strong>.</li>
          <li>Los contribuyentes asumen la completa responsabilidad <strong>de sus contribuciones y certifican que tienen derecho a compartirlas</strong>.</li>
        </ul>
      </div>
    </div>

    <h3>Ejemplos de señalizaciones:</h3>
    <div class="map-gallery-scroll">
      <img src="<?= url_assets() ?>assets/images/mapa1.png" alt="Mapa 1">
      <img src="<?= url_assets() ?>assets/images/mapa2.png" alt="Mapa 2">
      <!--<img src="<?= url_assets() ?>assets/images/mapa1.jpg" alt="Mapa 3">-->
    </div>

    <!-- Estadísticas de la comunidad -->
    <div class="community-stats">
      <h3>📊 Estadísticas de la comunidad</h3>
      <div class="stats-grid">
        <div class="stat-item">
          <span class="stat-number" id="totalPlaces">1,247</span>
          <span class="stat-label">Lugares registrados</span>
        </div>
        <div class="stat-item">
          <span class="stat-number" id="totalUsers">892</span>
          <span class="stat-label">Exploradores</span>
        </div>
        <div class="stat-item">
          <span class="stat-number" id="totalRoutes">456</span>
          <span class="stat-label">Rutas compartidas</span>
        </div>
        <div class="stat-item">
          <span class="stat-number" id="totalPhotos">3,421</span>
          <span class="stat-label">Fotos subidas</span>
        </div>
      </div>
    </div>

  </main>

   <script src="<?= url_assets() ?>assets/js/uno.js"></script>
   <script> const base_url = "<?= base_url() ?>";</script>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</body>

</html>