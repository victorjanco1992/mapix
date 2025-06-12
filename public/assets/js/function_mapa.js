
let map;
let markers = [];
let routes = [];
let currentRoute = null;
let routePoints = [];
let routeMarkers = [];
let userLocation = null;
let watchId = null;
let isCreatingRoute = false;
let isAddingPlace = false;
let selectedMarker = null;
let userMarker = null;
let places = null;
let rutasData = [];
let favoritosData = [];

if (L.DomUtil.get('map')) {
  L.DomUtil.get('map')._leaflet_id = null;
}

map = L.map('map').setView([20, 0], 2);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '© OpenStreetMap contributors'
}).addTo(map);

let rutaActual = [];
let marcadores = [];

map.on('click', function (e) {
  if (marcadores.length === 2) {
    const marcadorAnterior = marcadores.pop();
    map.removeLayer(marcadorAnterior);
    rutaActual.pop();
  }

  const marcador = L.marker(e.latlng).addTo(map);

  marcador.bindPopup(`
    <div>
      <p><strong>Marcador:</strong> ${e.latlng.lat.toFixed(5)}, ${e.latlng.lng.toFixed(5)}</p>
      <button id="btnEliminar">Eliminar</button>
    </div>
  `);

  marcadores.push(marcador);
  rutaActual.push({ ...e.latlng, timestamp: Date.now() });

  marcador.on('popupopen', (event) => {
    const popupNode = event.popup.getElement();
    const btn = popupNode.querySelector("#btnEliminar");
    if (btn) {
      btn.addEventListener("click", () => {
        map.removeLayer(marcador);
        const idx = marcadores.indexOf(marcador);
        if (idx > -1) {
          marcadores.splice(idx, 1);
          rutaActual.splice(idx, 1);
        }
      });
    }
  });

  marcador.openPopup();
});

function showModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';

    // Enfocar primer input
    const firstInput = modal.querySelector('input, textarea, select');
    if (firstInput) {
      setTimeout(() => firstInput.focus(), 100);
    }
  }
}

function toggleModal(modalId) {
  const modal = document.getElementById(modalId);
  if (!modal) return;

  if (modal.classList.contains('active')) {
    // Si está abierto, cerrar modal
    modal.classList.remove('active');
    document.body.style.overflow = '';
  } else {
    // Si está cerrado, abrir modal
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';

    // Enfocar primer input
    const firstInput = modal.querySelector('input, textarea, select');
    if (firstInput) {
      setTimeout(() => firstInput.focus(), 100);
    }
  }
}
function cerrarModal() {
  const modal = document.getElementById('modalOverlay');
  if (modal) {
    modal.classList.remove('active');
    document.body.style.overflow = 'auto'; // restaurar el scroll
  }
}

function cerrarModalFavoritos() {
  const modal = document.getElementById('modalFavoritos');
  if (modal) {
    modal.classList.remove('active');
    document.body.style.overflow = 'auto'; // restaurar el scroll
  }
}

function cerrarModalPerfil() {
  const modal = document.getElementById('perfilModal');
  if (modal) {
    modal.classList.remove('active');
    document.body.style.overflow = 'auto'; // restaurar el scroll
  }
}


document.getElementById('colaborarBtn').addEventListener('click', () => showModal('rutaModal'));

document.getElementById('closePerfilModal').addEventListener('click', () => cerrarModalPerfil());

document.getElementById('rutasBtn').addEventListener('click', () => showModal('modalOverlay'));

document.getElementById('followRouteBtn').addEventListener('click', () => showModal('modalOverlay'));

document.getElementById('perfilBtn').addEventListener('click', () => showModal('perfilModal'));

document.getElementById('guardadosBtn').addEventListener('click', () => showModal('modalFavoritos'));

document.getElementById('closeRutaModal').addEventListener('click', () => toggleModal('rutaModal'));

document.getElementById('cancelRuta').addEventListener('click', () => toggleModal('rutaModal'));

document.getElementById('sosBtn').addEventListener('click', triggerSOS);


// 🚨 SOS - Emergencia
function triggerSOS() {
  if (!userLocation) {
    getCurrentLocation();
    setTimeout(() => triggerSOS(), 2000);
    return;
  }

  const sosMessage = `🚨 EMERGENCIA MAPIX
📍 Ubicación: ${userLocation.lat.toFixed(6)}, ${userLocation.lng.toFixed(6)}
🕐 Hora: ${new Date().toLocaleString()}
🗺️ Google Maps: https://maps.google.com/?q=${userLocation.lat},${userLocation.lng}

⚠️ Explorador urbano necesita asistencia`;

  const numeroWhatsApp = "5492616239777";
  const whatsappURL = `https://wa.me/${numeroWhatsApp}?text=${encodeURIComponent(sosMessage)}`;

  // Intentar compartir si está disponible
  if (navigator.share) {
    navigator.share({
      title: '🚨 Emergencia MAPIX',
      text: sosMessage
    }).catch(() => {
      // Si falla, intentar abrir WhatsApp
      window.open(whatsappURL, '_blank');
    });
  } else if (navigator.clipboard) {
    navigator.clipboard.writeText(sosMessage).then(() => {
      showToast('Mensaje SOS copiado al portapapeles');
      window.open(whatsappURL, '_blank'); // Abre WhatsApp luego de copiar
    });
  } else {
    alert(sosMessage);
    window.open(whatsappURL, '_blank');
  }

  showToast('🚨 SOS ACTIVADO - Ubicación preparada para compartir', 'error');
}



// 📍 Obtener ubicación actual
function getCurrentLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      position => {
        userLocation = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };

        // Remover marcador anterior si existe
        if (userMarker) {
          map.removeLayer(userMarker);
        }

        // Añadir marcador de usuario
        userMarker = L.marker([userLocation.lat, userLocation.lng], {
          icon: L.divIcon({
            html: '<div style="background: #007bff; border-radius: 50%; width: 20px; height: 20px; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>',
            className: 'user-marker',
            iconSize: [20, 20],
            iconAnchor: [10, 10]
          })
        }).addTo(map).bindPopup('Tu ubicación actual');

        // Centrar mapa en la ubicación del usuario
        map.setView([userLocation.lat, userLocation.lng], 13);
        
        showToast('Ubicación obtenida correctamente');
      },
      error => {
        console.error('Error obteniendo ubicación:', error);
        showToast('No se pudo obtener la ubicación GPS', 'error');
      },
      {
        enableHighAccuracy: true,
        timeout: 10000,
        maximumAge: 600000
      }
    );
  } else {
    showToast('Geolocalización no soportada por este navegador', 'error');
  }
}

document.getElementById("rutaForm").addEventListener("submit", async (e) => {
  e.preventDefault();

  if (rutaActual.length < 2) {
    alert("Agrega exactamente dos puntos para crear una ruta.");
    return;
  }


  const nombre = e.target[0].value;
  const descripcion = e.target[1].value;
  const dificultad = e.target[2].value;
  const terreno = e.target[3].value;
  const etiquetas = e.target[4].value.split(',').map(et => et.trim());
  const archivoImagen = document.getElementById("imagenRuta").files[0];
  const tiempoMs = rutaActual[1].timestamp - rutaActual[0].timestamp;
  const distancia = calcularDistanciaKm(rutaActual[0], rutaActual[1]);

  //CALCULAR TIEMPO
  const velocidadKmH = parseFloat(document.getElementById('medioTransporte').value); // caminando
  const duracionHoras = distancia / velocidadKmH;
  const duracionSegundos = duracionHoras * 3600;
  const horas = Math.floor(duracionSegundos / 3600);
  const minutos = Math.floor((duracionSegundos % 3600) / 60);
  const segundos = Math.floor(duracionSegundos % 60);
  const duracion = `${String(horas).padStart(2, '0')}:${String(minutos).padStart(2, '0')}:${String(segundos).padStart(2, '0')}`;

  // Leer la imagen como base64
  const imagenBase64 = await convertirImagenABase64(archivoImagen);

  const formData = new FormData();
  formData.append('nombre', nombre);
  formData.append('descripcion', descripcion);
  formData.append('dificultad', dificultad);
  formData.append('terreno', terreno);
  formData.append('duracion', duracion);
  formData.append('distancia_km', distancia.toFixed(2));
  formData.append('velocidad', velocidadKmH);

  // Para etiquetas (array), puedes enviarlas como string separadas por comas:
  formData.append('etiquetas', etiquetas.join(','));

  // Para puntos_gps, puedes convertir a string JSON y enviarlo así (PHP lo decodifica luego):
  formData.append('puntos_gps', JSON.stringify(rutaActual));

  // Para la imagen base64 (string largo):
  formData.append('imagen', imagenBase64);

  fetch(base_url + 'inicio/guardar-rutas', {
    method: "POST",
    body: formData
  })
    .then(async response => {
      const text = await response.text(); // Obtiene la respuesta en texto
      try {
        return JSON.parse(text); // Intenta parsear a JSON
      } catch (error) {
        throw new Error('Respuesta no es JSON: ' + text); // Muestra el texto como error
      }
    })
    .then(data => {


      if (data.status === 'error') {
        alert(data.message);
        return
      }

      console.log("Respuesta del servidor:", data);
      alert("Ruta registrada correctamente.");
      toggleModal('rutaModal');
      e.target.reset();
      // Limpieza después de guardar
      rutaActual = [];
      marcadores.forEach(m => map.removeLayer(m));
      marcadores = [];
    })
    .catch(error => {
      console.error("Error al enviar la ruta:", error);
      alert("Hubo un problema al guardar la ruta.");
    });

});


// Función para convertir imagen a base64
function convertirImagenABase64(file) {
  return new Promise((resolve, reject) => {
    if (!file) return resolve(null); // por si no se seleccionó imagen

    const reader = new FileReader();
    reader.onloadend = () => resolve(reader.result);
    reader.onerror = reject;
    reader.readAsDataURL(file);
  });
}

function calcularDistanciaKm(p1, p2) {
  const R = 6371; // Radio de la Tierra en km
  const dLat = (p2.lat - p1.lat) * Math.PI / 180;
  const dLon = (p2.lng - p1.lng) * Math.PI / 180;
  const lat1 = p1.lat * Math.PI / 180;
  const lat2 = p2.lat * Math.PI / 180;

  const a = Math.sin(dLat / 2) ** 2 +
    Math.sin(dLon / 2) ** 2 * Math.cos(lat1) * Math.cos(lat2);
  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

  return R * c;
}

//--------------------------------------------------------
document.getElementById('closeLugarModal').addEventListener('click', () => {
  document.getElementById('lugarModal').style.display = 'none';
});

document.getElementById('cancelLugar').addEventListener('click', () => {
  document.getElementById('lugarModal').style.display = 'none';
});


document.getElementById('addMarkerBtn').addEventListener('click', () => {
  isAddingPlace = true;
  showToast('Haz clic en el map para añadir un lugar');
});


// Configurar eventos del map
setupMapEvents();

// 📱 Configurar eventos del map
function setupMapEvents() {
  // Clic en el map para añadir lugares o puntos de ruta
  map.on('click', function (e) {
    if (isAddingPlace) {
      document.getElementById('selectedLat').textContent = e.latlng.lat.toFixed(6);
      document.getElementById('selectedLng').textContent = e.latlng.lng.toFixed(6);
      document.getElementById('lugarModal').style.display = 'flex';
      isAddingPlace = false;
    } else if (isCreatingRoute) {
      addRoutePoint(e.latlng);
    }
  });
}

// 🔔 Mostrar notificación toast
function showToast(message, type = 'success') {
  const toast = document.createElement('div');
  toast.className = `toast toast-${type}`;
  toast.style.cssText = `
    position: fixed;
    top: 20px;
    right: 20px;
    background: ${type === 'error' ? '#dc3545' : '#28a745'};
    color: white;
    padding: 12px 20px;
    border-radius: 5px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    z-index: 10000;
    transform: translateX(100%);
    transition: transform 0.3s ease;
    max-width: 300px;
  `;
  toast.textContent = message;

  document.body.appendChild(toast);

  setTimeout(() => {
    toast.style.transform = 'translateX(0)';
  }, 100);

  setTimeout(() => {
    toast.style.transform = 'translateX(100%)';
    setTimeout(() => toast.remove(), 300);
  }, 3000);
}

//GUARDAR LUGAR EN BD

document.getElementById('lugarForm').addEventListener('submit', async (e) => {
  e.preventDefault();

  const lat = parseFloat(document.getElementById('selectedLat').textContent);
  const lng = parseFloat(document.getElementById('selectedLng').textContent);
  const lugarNombre = document.getElementById('lugarNombre').value;
  const lugarTipo = document.getElementById('lugarTipo').value;
  const lugarDescripcion = document.getElementById('lugarDescripcion').value;
  const lugarPeligro = document.getElementById('lugarPeligro').value;
  const lugarAno = document.getElementById('lugarAno').value;
  const archivoImagen = document.getElementById("lugarFotos").files[0];
  // Leer la imagen como base64
  const imagenBase64 = await convertirImagenABase64(archivoImagen);

  if (isNaN(lat) || isNaN(lng)) {
    showToast('Por favor selecciona una ubicación en el mapa', 'error');
    return;
  }

  // Construir el objeto de datos a enviar
  const newPlace = {
    name: lugarNombre,
    type: lugarTipo,
    lat: lat,
    lng: lng,
    description: lugarDescripcion,
    danger: lugarPeligro,
    year: lugarAno,
    img: imagenBase64
  };

  // Enviar al backend con fetch
  fetch(base_url + 'inicio/guardar-lugar', {  // 🔧 Ajusta la ruta a la de tu API
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(newPlace)
  })
    .then(response => response.json())
    .then(data => {
      if (data.status === 'success') {
        showToast('¡Lugar añadido correctamente!');
        obtenerLugares();
      } else {
        showToast('Error al guardar el lugar: ' + data.message, 'error');
      }
    })
    .catch(error => {
      console.error('Error al guardar el lugar:', error);
      showToast('Ocurrió un error al guardar el lugar', 'error');
    });

  // Cerrar el modal y resetear el formulario
  document.getElementById('lugarModal').style.display = 'none';
  e.target.reset();
});

function obtenerLugares() {
  // Enviar al backend con fetch
  fetch(base_url + 'inicio/obtener-lugares', {  // 🔧 Ajusta la ruta a la de tu API
    method: 'GET',
  })
    .then(response => response.json())
    .then(data => {
      if (data.status === 'success') {
        console.log(data.data);
        places = data.data;
        loadPlaces();
        //ASIGNAR LOS LUGARES A LA VARIABLE: PLACES
      } else {
        console.log(data.message);

        showToast('Error al obtener los lugar: ' + data.message, 'error');
      }
    })
    .catch(error => {
      console.error('Error al guardar el lugar:', error);
      showToast('Ocurrió un error al guardar el lugar', 'error');
    });
}

obtenerLugares();

// 📍 Cargar lugares en el mapa
function loadPlaces() {
  // Limpiar marcadores existentes
  markers.forEach(({ marker }) => map.removeLayer(marker));
  markers = [];

  places.forEach(place => {
    const icon = getMarkerIcon(place.type, place.danger);
    const marker = L.marker([place.lat, place.lng], { icon })
      .addTo(map)
      .bindPopup(createPopupContent(place))
      .on('click', () => showPlaceDetail(place));

    markers.push({ marker, place });
  });
}
// 🎯 Obtener icono según tipo y peligrosidad
function getMarkerIcon(type, danger) {
  const colors = {
    baja: '#28a745',
    media: '#ffc107',
    alta: '#fd7e14',
    extrema: '#dc3545'
  };

  const icons = {
    hospital: '🏥',
    fabrica: '🏭',
    escuela: '🏫',
    casa: '🏠',
    iglesia: '⛪',
    bunker: '🏰',
    tunel: '🕳️',
    otro: '📍'
  };

  return L.divIcon({
    html: `<div style="background: ${colors[danger]}; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border: 2px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3); font-size: 14px;">${icons[type] || '📍'}</div>`,
    className: 'custom-marker',
    iconSize: [30, 30],
    iconAnchor: [15, 15]
  });
}



document.getElementById('closeDetalleModal').addEventListener('click', () => {
  document.getElementById('detalleModal').style.display = 'none';
});

function showPlaceDetail(placeId) {
  const place = typeof placeId === 'object' ? placeId : places.find(p => p.id === placeId);
  if (!place) return;

  document.getElementById('detalleTitulo').textContent = place.name;
  document.getElementById('detalleTipo').textContent = place.type.toUpperCase();
  document.getElementById('detallePeligro').textContent = place.danger.toUpperCase();
  document.getElementById('detalleAno').textContent = place.year;
  document.getElementById('detalleDescripcion').textContent = place.description;
  document.getElementById('detalleVisitas').textContent = place.visits;
  document.getElementById('detalleFavoritos').textContent = place.favorites;
  document.getElementById('detalleRating').textContent = place.rating;
  document.getElementById('detalleImagenPrincipal').src = base_url + place.images;


  const favoriteBtn = document.getElementById('toggleFavorite');
  favoriteBtn.innerHTML = place.favorites ?
    '<i class="fas fa-heart" style="color: red;"></i> Favorito' :
    '<i class="fas fa-heart"></i> Favorito';

  favoriteBtn.onclick = () => toggleFavorite(place);

  loadComments(place);

  document.getElementById('detalleModal').style.display = 'flex';
  selectedMarker = place;

  // ⚡️ Conectar el botón de compartir
  const shareBtn = document.getElementById('sharePlace');
  shareBtn.onclick = () => sharePlace(place);

  cerrarModalFavoritos();
  place.visits++;
}

function sharePlace(place) {
  const googleMapsUrl = `https://www.google.com/maps/search/?api=1&query=${place.lat},${place.lng}`;

  const shareText =
    `📍 ${place.name}
📌 Tipo: ${place.type}
⚠️ Peligro: ${place.danger}
📆 Año: ${place.year}
📝 Descripción: ${place.description}

🌐 Coordenadas: ${place.lat}, ${place.lng}
🗺️ Abrir en Google Maps:
${googleMapsUrl}`;

  if (navigator.share) {
    navigator.share({
      title: `¡Mira este lugar!`,
      text: shareText
      // omitimos 'url' para forzar el texto completo en el mensaje
    })
      .then(() => console.log('Compartido exitosamente'))
      .catch((error) => console.error('Error al compartir:', error));
  } else {
    navigator.clipboard.writeText(shareText)
      .then(() => alert('Texto copiado al portapapeles'))
      .catch(() => alert('No se pudo copiar el texto'));
  }
}

// 💬 Crear contenido del popup
function createPopupContent(place) {
  const dangerColors = {
    baja: '#28a745',
    media: '#ffc107',
    alta: '#fd7e14',
    extrema: '#dc3545'
  };

  const dangerLabels = {
    baja: 'BAJO',
    media: 'MEDIO',
    alta: 'ALTO',
    extrema: 'EXTREMO'
  };

  return `
    <div class="marker-popup" style="min-width: 200px;">
      <h3 style="margin: 0 0 10px 0; color: #333;">${place.name}</h3>
      <p style="margin: 0 0 10px 0; font-size: 13px; color: #666;">${place.description.substring(0, 100)}...</p>
      <div class="popup-meta" style="display: flex; gap: 8px; align-items: center; margin-bottom: 10px;">
        <span class="danger-badge" style="background: ${dangerColors[place.danger]}; color: white; padding: 2px 6px; border-radius: 10px; font-size: 10px; font-weight: bold;">
          ${dangerLabels[place.danger]}
        </span>
        
      </div>
      <button onclick="showPlaceDetail(${place.id})" class="popup-btn" style="background: #007bff; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-size: 12px;">
        Ver detalles
      </button>
    </div>
  `;
}


document.getElementById('enviarComentario').addEventListener('click', () => {
  const text = document.getElementById('nuevoComentario').value.trim();
  const rating = document.getElementById('comentarioRating').value;
  const placeId = selectedMarker.id; // usa la variable global que guarda el id del lugar seleccionado

  if (!text) {
    alert('Por favor, escribe un comentario.');
    return;
  }

  fetch(`${base_url}inicio/comentar`, {  // Ajusta la URL a tu endpoint real
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      id_place: placeId,
      text: text,
      rating: rating  // opcional
    })
  })
    .then(response => response.json())
    .then(data => {
      if (data.status === 'success') {
        showToast('Comentario agregado con éxito.');
        // Aquí añadimos el comentario dinámicamente:
        const newComment = {
          user: 'Tú',              // O el nombre de usuario real si lo tienes
          text: text,
          rating: rating,               // O el rating si lo usas
          date: new Date().toISOString().split('T')[0]  // O la fecha que te devuelva el servidor
        };

        if (!selectedMarker.comments) {
          selectedMarker.comments = [];
        }
        selectedMarker.comments.push(newComment);

        // Recarga los comentarios sin recargar la página:
        loadComments(selectedMarker);

        // Limpia el campo de texto:
        document.getElementById('nuevoComentario').value = '';

      } else {
        alert('Hubo un error: ' + data.message);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('No se pudo enviar el comentario.');
    });
});

function toggleFavorite(place) {
  const newFavoriteState = !place.is_favorite;

  fetch(`${base_url}inicio/toggle-favorite`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ id_place: place.id, favorite: newFavoriteState ? 1 : 0 })
  })
    .then(response => response.json())
    .then(data => {
      if (data.status === 'success') {
        place.is_favorite = newFavoriteState;  // actualiza localmente
        place.favorites = data.favorites;      // actualiza cantidad
        document.getElementById('detalleFavoritos').textContent = place.favorites;
        const favoriteBtn = document.getElementById('toggleFavorite');
        favoriteBtn.innerHTML = newFavoriteState ?
          '<i class="fas fa-heart" style="color: red;"></i> Favorito' :
          '<i class="fas fa-heart"></i> Favorito';
        showToast('Estado de favorito actualizado.', 'success');
      } else {
        showToast('Error al actualizar favorito.', 'error');
      }
    })
    .catch(error => {
      console.error('Error al actualizar favorito:', error);
      showToast('Ocurrió un error al actualizar favorito.', 'error');
    });
}



// 💬 Cargar comentarios
function loadComments(place) {
  const commentsList = document.getElementById('comentariosList');
  commentsList.innerHTML = '';

  if (place.comments && place.comments.length > 0) {
    place.comments.forEach(comment => {
      const commentDiv = document.createElement('div');
      commentDiv.className = 'comment-item';
      commentDiv.style.cssText = 'border-bottom: 1px solid #eee; padding: 10px 0; margin-bottom: 10px;';
      commentDiv.innerHTML = `
        <div class="comment-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
          <strong style="color: #333;">${comment.user}</strong>
          <div>
            <span class="comment-rating" style="color: #ffc107;">${'⭐'.repeat(comment.rating)}</span>
            <span class="comment-date" style="color: #999; font-size: 12px; margin-left: 10px;">${comment.date}</span>
          </div>
        </div>
        <p style="margin: 0; color: #666; font-size: 14px;">${comment.text}</p>
      `;
      commentsList.appendChild(commentDiv);
    });
  } else {
    commentsList.innerHTML = '<p style="color: #999; text-align: center; font-style: italic;">No hay comentarios aún. ¡Sé el primero!</p>';
  }
}

function obtenerRutas() {
  // Enviar al backend con fetch
  fetch(base_url + 'inicio/obtener-rutas', {  // 🔧 Ajusta la ruta a la de tu API
    method: 'GET',
  })
    .then(response => response.json())
    .then(data => {
      if (data.status === 'success') {
        console.log(data.data);
        rutasData = data.data;
        cargarRutas();
        //ASIGNAR LOS LUGARES A LA VARIABLE: PLACES
      } else {
        console.log(data.message);

        showToast('Error al obtener los lugar: ' + data.message, 'error');
      }
    })
    .catch(error => {
      console.error('Error al guardar el lugar:', error);
      showToast('Ocurrió un error al guardar el lugar', 'error');
    });
}

obtenerRutas();

function obtenerBadgeDificultad(dificultad) {
  const badges = {
    'facil': 'badge-facil',
    'media': 'badge-medio',
    'dificil': 'badge-dificil'
  };
  return badges[dificultad] || 'badge-medio';
}

function obtenerBadgeTerreno(terreno) {
  const badges = {
    'urbano': 'badge-urbano',
    'rural': 'badge-rural',
    'montaña': 'badge-rural',
    'costero': 'badge-urbano'
  };
  return badges[terreno] || 'badge-rural';
}

function formatearFecha(fecha) {
  const date = new Date(fecha);
  return date.toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
}

function cargarRutas() {
  const lista = document.getElementById('rutasLista');
  lista.innerHTML = '';

  rutasData.forEach((ruta, index) => {
    const li = document.createElement('li');
    li.className = 'ruta-item';
    li.style.animationDelay = `${(index + 1) * 0.1}s`;

    li.innerHTML = `
                    <div class="ruta-header">
                        <h3 class="ruta-nombre">${ruta.nombre}</h3>
                        <span class="ruta-id">#${ruta.id_ruta}</span>
                    </div>
                    <p class="ruta-descripcion">${ruta.descripcion}</p>
                    <div class="ruta-detalles">
                        <div class="detalle">
                            <span class="detalle-label">Dificultad</span>
                            <span class="detalle-valor">
                                <span class="badge ${obtenerBadgeDificultad(ruta.dificultad)}">${ruta.dificultad}</span>
                            </span>
                        </div>
                        <div class="detalle">
                            <span class="detalle-label">Terreno</span>
                            <span class="detalle-valor">
                                <span class="badge ${obtenerBadgeTerreno(ruta.terreno)}">${ruta.terreno}</span>
                            </span>
                        </div>
                        <div class="detalle">
                            <span class="detalle-label">Duración</span>
                            <span class="detalle-valor">
                              ${ruta.duracion} a 
                              ${ruta.velocidad === 5
        ? 'Caminando'
        : ruta.velocidad === 15
          ? 'Bicicleta'
          : ruta.velocidad === 60
            ? 'Vehículo'
            : ruta.velocidad === 800
              ? 'Avión'
              : 'Desconocido'
      }
                            </span>

                        </div>
                        <div class="detalle">
                            <span class="detalle-label">Distancia</span>
                            <span class="detalle-valor">${ruta.distancia_km} km</span>
                        </div>
                        <div class="detalle">
                            <span class="detalle-label">Etiquetas</span>
                            <span class="detalle-valor">${ruta.etiquetas}</span>
                        </div>
                        <div class="detalle">
                            <span class="detalle-label">Registrado</span>
                            <span class="detalle-valor">${formatearFecha(ruta.fecha_registro)}</span>
                        </div>
                        <div class="detalle">
                            <span class="detalle-label">Ruta Inicio</span>
                            <span class="detalle-valor">${ruta.ruta_inicio}</span>
                        </div>
                        <br>
                        <div class="detalle">
                            <span class="detalle-label">Ruta Fin</span>
                            <span class="detalle-valor">${ruta.ruta_fin}</span>
                        </div>
                    </div>
                     <button onclick="followRoute(${ruta.id_ruta})" style="background: #007bff; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer; font-size: 12px;">
          📍 Seguir Ruta
        </button>
                `;

    lista.appendChild(li);
  });
}


function followRoute(routeId) {
  const route = rutasData.find(r => r.id_ruta === routeId);
  if (!route) {
    showToast("No se encontró la ruta.");
    return;
  }

  currentRoute = route;

  // Limpia rutas anteriores
  if (window.routingControl) {
    map.removeControl(window.routingControl);
  }

  // Convertir inicio y fin a LatLng
  const inicioCoords = route.ruta_inicio.split(',').map(Number);
  const finCoords = route.ruta_fin.split(',').map(Number);

  const waypoints = [
    L.latLng(inicioCoords[0], inicioCoords[1]),
    L.latLng(finCoords[0], finCoords[1])
  ];

  window.routingControl = L.Routing.control({
    waypoints: waypoints,
    routeWhileDragging: false,
    addWaypoints: false,
    createMarker: function (i, waypoint, n) {
      return L.marker(waypoint.latLng, {
        icon: L.divIcon({
          html: `<div style="background: #28a745; color: white; border-radius: 50%; width: 25px; height: 25px; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 2px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);">${i + 1}</div>`,
          className: 'route-waypoint',
          iconSize: [25, 25]
        })
      }).bindPopup(`${i === 0 ? 'Inicio' : 'Fin'}: ${route.nombre}`);
    },
    lineOptions: {
      styles: [{ color: '#28a745', weight: 5, opacity: 0.8 }]
    }
  }).addTo(map);

  // Muestra controles
  document.getElementById('navigationControls').style.display = 'block';
  document.getElementById('navDistance').textContent = `${route.distancia_km} km`;
  document.getElementById('navTime').textContent = route.duracion;

  cerrarModal();
  showToast(`Siguiendo ruta: ${route.nombre}`);
}
// ⏸️ Pausar navegación
function pauseNavigation() {
  const pauseBtn = document.getElementById('pauseNav');

  if (pauseBtn.innerHTML.includes('pause')) {
    pauseBtn.innerHTML = '<i class="fas fa-play"></i>';
    showToast('Navegación pausada');
  } else {
    pauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
    showToast('Navegación reanudada');
  }
}

// 🛑 Detener navegación
function stopNavigation() {
  if (window.routingControl) {
    map.removeControl(window.routingControl);
    window.routingControl = null;
  }

  document.getElementById('navigationControls').style.display = 'none';
  currentRoute = null;

  showToast('Navegación detenida');
}
// Event listeners para navegación
document.getElementById('pauseNav').addEventListener('click', pauseNavigation);
document.getElementById('stopNav').addEventListener('click', stopNavigation);

function obtenerFavoritos() {
  // Enviar al backend con fetch
  fetch(base_url + 'inicio/obtener-favoritos', {  // 🔧 Ajusta la ruta a la de tu API
    method: 'GET',
  })
    .then(response => response.json())
    .then(data => {
      if (data.status === 'success') {
        console.log(data.data);
        favoritosData = data.data;
        cargarFavoritos();
        //ASIGNAR LOS LUGARES A LA VARIABLE: PLACES
      } else {
        console.log(data.message);

        showToast('Error al obtener los lugar: ' + data.message, 'error');
      }
    })
    .catch(error => {
      console.error('Error al guardar el lugar:', error);
      showToast('Ocurrió un error al guardar el lugar', 'error');
    });
}

function truncateText(text, maxLength = 120) {
  if (text.length <= maxLength) return text;
  return text.substr(0, maxLength) + '...';
}

obtenerFavoritos();

function cargarFavoritos() {
  const grid = document.getElementById('favoritosGrid');
  grid.innerHTML = '';

  if (favoritosData.length === 0) {
    grid.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-icon">💔</div>
                        <h3 class="empty-title">No tienes favoritos aún</h3>
                        <p class="empty-message">
                            Explora lugares increíbles y agrégalos a tus favoritos<br>
                            para tenerlos siempre a mano.
                        </p>
                    </div>
                `;
    return;
  }

  favoritosData.forEach((favorito, index) => {
    const card = document.createElement('div');
    card.className = 'favorito-card';
    card.style.animationDelay = `${(index + 1) * 0.1}s`;

    const hasImage = favorito.path_img && !favorito.path_img.includes('default');

    card.innerHTML = `
                    <div class="card-image">
                        ${hasImage ?
                          `<img src="${base_url + favorito.path_img}" alt="${favorito.name}" class="place-image">` :
                          `<div class="no-image">📍</div>`
                        }
                        <div class="image-overlay"></div>
                        <div class="favorite-badge">❤️</div>
                    </div>
                    <div class="card-content">
                        <div class="place-header">
                            <h3 class="place-name">${favorito.name}</h3>
                            <span class="favorite-id">#${favorito.id}</span>
                        </div>
                        <p class="place-description">${truncateText(favorito.description)}</p>
                        <div class="card-actions">
                            <button class="btn-action btn-ver" onclick="showPlaceDetail(${favorito.id_lugar}, '${favorito.name.replace(/'/g, "\\'")}')">
                                👁️ Ver
                            </button>
                            
                        </div>
                    </div>
                `;

    grid.appendChild(card);
  });
}