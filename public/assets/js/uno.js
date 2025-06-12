const loginBtn = document.getElementById('loginBtn');
const registerBtn = document.getElementById('registerBtn');
const loginForm = document.getElementById('loginForm');
const registerForm = document.getElementById('registerForm');
const startButtons = document.getElementById('start-buttons');
const toRegister = document.getElementById('toRegister');
const toLogin = document.getElementById('toLogin');
const acceptDisclaimer = document.getElementById('acceptDisclaimer');


// Manejar el descargo de responsabilidad
acceptDisclaimer.addEventListener('change', function() {
  if (this.checked) {
    startButtons.style.display = 'flex';
    startButtons.style.animation = 'fadeIn 0.5s ease-in-out';
  } else {
    startButtons.style.display = 'none';
  }
});


// Mostrar formulario inicio sesión
loginBtn.addEventListener('click', () => {
  startButtons.style.display = 'none';
  registerForm.classList.remove('active');
  loginForm.classList.add('active');
});

// Mostrar formulario registro
registerBtn.addEventListener('click', () => {
  startButtons.style.display = 'none';
  loginForm.classList.remove('active');
  registerForm.classList.add('active');
});

// Cambiar a registro desde login
toRegister.addEventListener('click', () => {
  loginForm.classList.remove('active');
  registerForm.classList.add('active');
});

// Cambiar a login desde registro
toLogin.addEventListener('click', () => {
  registerForm.classList.remove('active');
  loginForm.classList.add('active');
});

// Función para scroll suave a advertencias
function scrollToWarnings() {
  const warningsSection = document.getElementById('warnings-section');
  warningsSection.scrollIntoView({ behavior: 'smooth' });
}

// Redirigir después del login
loginForm.addEventListener('submit', async (e) => {
  e.preventDefault(); // Evita que recargue la página
  // Aquí podrías validar los campos si lo necesitas
  try {
    const formData = new FormData(loginForm);
    // Envía los datos al servidor usando fetch
    const response = await fetch(base_url + 'login/validar', {
      method: 'POST',
      body: formData
    });

    // Procesa la respuesta del servidor
    if (response.ok) {
      
      const data = await response.json();
      if (data.status == "success") {
        alert('Inicio de sesión exitoso.');
        window.location.href = base_url + "inicio";
      } else {
        alert(data.message || 'Usuario o contraseña incorrectos.');
      }
    } else {
      alert('Hubo un problema con la solicitud.');
    }
  } catch (error) {
    console.error('Error:', error);
    alert('Error al intentar conectarse al servidor.');
  }
});

// Redirigir después del registro
registerForm.addEventListener('submit', async (e) => {
  e.preventDefault();
  // También podrías guardar datos o validar aquí
  try {
    const formData = new FormData(registerForm);
    // Envía los datos al servidor usando fetch
    const response = await fetch(base_url + 'login/registrar', {
      method: 'POST',
      body: formData
    });

    if (response.ok) {
      const data = await response.json();
      if (data.status == "success") {
        alert('Inicio de sesión exitoso.');
        window.location.href = base_url;
      } else {
        alert(data.message || 'Usuario o contraseña incorrectos.');
      }
    } else {
      alert('Hubo un problema con la solicitud.');
    }
  } catch (error) {
    console.error('Error:', error);
    alert('Error al intentar conectarse al servidor.');
  }
});


var map = L.map('map').setView([0, 0], 2); // Centrado en el ecuador y nivel de zoom 2

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);




