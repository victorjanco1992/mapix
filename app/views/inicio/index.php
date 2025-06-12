<!-- 📍 Panel lateral flotante -->
<div class="urbex-panel" id="mainPanel">
  <div class="panel-header">
    <h1>🧭 MAPIX</h1>
    <button class="panel-toggle" id="panelToggle">
      <i class="fas fa-chevron-right"></i>
    </button>
  </div>
  <p class="subtitle">Mapa colaborativo de lugares abandonados</p>

  <!-- Información del usuario -->
  <div class="user-info">
    <div class="user-avatar">
      <i class="fas fa-user"></i>
    </div>
    <div class="user-details">
      <span class="user-name" id="userName"><?php echo $_SESSION['usuario_cliente']->name_users ?></span>
      <span class="user-level">Nivel: <span id="userLevel">0</span></span>
    </div>
    <button class="logout-btn" onclick='window.location.href = "<?= base_url() ?>login/salir"' id="logoutBtn" title="Cerrar sesión">
      <i class="fas fa-sign-out-alt"></i>
    </button>
  </div>

  <div class="panel-buttons">
    <button class="btn primary" id="colaborarBtn">
      <i class="fas fa-plus"></i> Colaborar
    </button>
    <button class="btn" id="guardadosBtn">
      <i class="fas fa-heart"></i> Favoritos
    </button>
    <button class="btn" id="rutasBtn">
      <i class="fas fa-route"></i> Mis Rutas
    </button>
    <button class="btn" id="perfilBtn">
      <i class="fas fa-user-circle"></i> Mi Perfil
    </button>
  </div>

  <div class="panel-add">
    <button class="add-marker" id="addMarkerBtn" title="Añadir lugar">
      <i class="fas fa-map-marker-alt"></i>

    </button>
    <button class="create-route" id="createRouteBtn" title="Crear ruta">
      <i class="fas fa-route"></i>
    </button>
    <button class="follow-route" id="followRouteBtn" title="Seguir ruta">
      <i class="fas fa-location-arrow"></i>
    </button>
    <span>Herramientas</span>
  </div>

  <!-- <div class="panel-stats">
    <h4><i class="fas fa-chart-bar"></i> Estadísticas</h4>
    <div class="stats-grid">
      <div class="stat-item">
        <span class="stat-number" id="userRoutes">0</span>
        <span class="stat-label">Rutas</span>
      </div>
      <div class="stat-item">
        <span class="stat-number" id="userDistance">0km</span>
        <span class="stat-label">Distancia</span>
      </div>
    </div>
  </div> -->

  <div class="panel-icons">
    <select class="language-select" id="languageSelect">
      <option value="es">Español</option>
      <option value="en">English</option>
      <option value="fr">Français</option>
    </select>
  </div>

  <div class="info-footer">
    <small><i class="fas fa-clock"></i> Actualización: hace 8h</small><br>
    <small><strong>Nivel:</strong> <span id="footerLevel">0</span> | <strong>Lugares:</strong> <span id="userPlaces">0</span></small><br>
    <a href="#" id="inviteLink">🎁 Invita amigos = +1 nivel</a>
  </div>
</div>



<!-- 🌍 Modal para crear rutas -->
<div class="modal" id="rutaModal">
  <div class="modal-content">
    <div class="modal-header">
      <h2><i class="fas fa-route"></i> Crear Nueva Ruta</h2>
      <button class="close-modal" id="closeRutaModal">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <form id="rutaForm" class="form-ruta">
      <div class="form-group">
        <label><i class="fas fa-tag"></i> Nombre de la ruta</label>
        <input type="text" id="rutaNombre" placeholder="Ej: Exploración Hospital Central" required>
      </div>

      <div class="form-group">
        <label><i class="fas fa-align-left"></i> Descripción</label>
        <textarea id="rutaDescripcion" placeholder="Describe tu ruta de exploración..." required></textarea>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label><i class="fas fa-chart-line"></i> Dificultad</label>
          <select id="rutaDificultad" required>
            <option value="">Seleccionar</option>
            <option value="facil">🟢 Fácil</option>
            <option value="media">🟡 Media</option>
            <option value="dificil">🔴 Difícil</option>
            <option value="extrema">⚫ Extrema</option>
          </select>
        </div>

        <div class="form-group">
          <label><i class="fas fa-mountain"></i> Tipo de terreno</label>
          <select id="rutaTerreno">
            <option value="urbano">🏢 Urbano</option>
            <option value="industrial">🏭 Industrial</option>
            <option value="rural">🌾 Rural</option>
            <option value="subterraneo">🕳️ Subterráneo</option>
            <option value="maritimo">🌊 Marítimo</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label><i class="fas fa-tags"></i> Etiquetas</label>
        <input type="text" id="rutaEtiquetas" placeholder="hospital, abandonado, urbex (separadas por comas)">
      </div>
      <div class="form-group">
        <label>Distancia: </label>
        <select id="medioTransporte" required>
          <option value="5">Caminando</option>
          <option value="15">Bicicleta</option>
          <option value="60">Vehículo </option>
          <option value="800">Avión</option>
        </select>
      </div>
      <div class="form-group">
        <label><i class="fas fa-images"></i> Fotos</label>
        <input type="file" id="imagenRuta" multiple accept="image/*">
        <div class="image-preview" id="imagePreview"></div>
      </div>



      <div class="form-actions">
        <button type="button" id="cancelRuta" class="btn-cancel">
          <i class="fas fa-times"></i> Cancelar
        </button>
        <button type="submit" class="btn-submit">
          <i class="fas fa-save"></i> Guardar Ruta
        </button>
      </div>
    </form>
  </div>
</div>


<!-- Modal para agregar lugar -->
<div class="modal" id="lugarModal">
  <div class="modal-content">
    <div class="modal-header">
      <h2><i class="fas fa-map-marker-alt"></i> Agregar Nuevo Lugar</h2>
      <button class="close-modal" id="closeLugarModal">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <form id="lugarForm">
      <div class="form-group">
        <label><i class="fas fa-tag"></i> Nombre del lugar</label>
        <input type="text" id="lugarNombre" placeholder="Ej: Hospital Santa María" required>
      </div>

      <div class="form-group">
        <label><i class="fas fa-list"></i> Tipo de lugar</label>
        <select id="lugarTipo" required>
          <option value="">Seleccionar tipo</option>
          <option value="hospital">🏥 Hospital</option>
          <option value="fabrica">🏭 Fábrica</option>
          <option value="escuela">🏫 Escuela</option>
          <option value="casa">🏠 Casa/Residencia</option>
          <option value="iglesia">⛪ Iglesia</option>
          <option value="bunker">🏰 Bunker/Militar</option>
          <option value="tunel">🕳️ Túnel/Metro</option>
          <option value="otro">❓ Otro</option>
        </select>
      </div>

      <div class="form-group">
        <label><i class="fas fa-align-left"></i> Descripción</label>
        <textarea id="lugarDescripcion" placeholder="Describe el lugar, su historia, estado actual..." required></textarea>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label><i class="fas fa-exclamation-triangle"></i> Peligrosidad</label>
          <select id="lugarPeligro" required>
            <option value="baja">🟢 Baja</option>
            <option value="media">🟡 Media</option>
            <option value="alta">🔴 Alta</option>
            <option value="extrema">⚫ Extrema</option>
          </select>
        </div>

        <div class="form-group">
          <label><i class="fas fa-calendar"></i> Año aproximado</label>
          <input type="number" id="lugarAno" placeholder="1950" min="1800" max="2024">
        </div>
      </div>

      <div class="form-group">
        <label><i class="fas fa-images"></i> Fotos del lugar</label>
        <input type="file" id="lugarFotos" multiple accept="image/*">
        <div class="image-preview" id="lugarImagePreview"></div>
      </div>

      <div class="coordinates-info">
        <h4><i class="fas fa-crosshairs"></i> Coordenadas</h4>
        <div class="coords-display">
          <span>Lat: <span id="selectedLat">-</span></span>
          <span>Lng: <span id="selectedLng">-</span></span>
        </div>
      </div>

      <div class="form-actions">
        <button type="button" id="cancelLugar" class="btn-cancel">
          <i class="fas fa-times"></i> Cancelar
        </button>
        <button type="submit" class="btn-submit">
          <i class="fas fa-save"></i> Guardar Lugar
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Modal de detalle de lugar -->
<div class="modal" id="detalleModal">
  <div class="modal-content detail-modal">
    <div class="modal-header">
      <h2 id="detalleTitulo">Detalle del Lugar</h2>
      <button class="close-modal" id="closeDetalleModal">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <div class="detail-content">
      <div class="detail-images">
        <div class="main-image">
          <img id="detalleImagenPrincipal" src="" alt="">
        </div>
        <div class="thumbnail-gallery" id="detalleThumbnails"></div>
      </div>

      <div class="detail-info">
        <div class="place-meta">
          <span class="place-type" id="detalleTipo"></span>
          <span class="place-danger" id="detallePeligro"></span>
          <span class="place-year" id="detalleAno"></span>
        </div>

        <div class="place-description">
          <p id="detalleDescripcion"></p>
        </div>

        <div class="place-stats">
          <div class="stat">
            <i class="fas fa-eye"></i>
            <span id="detalleVisitas">0</span> visitas
          </div>
          <div class="stat">
            <i class="fas fa-heart"></i>
            <span id="detalleFavoritos">0</span> favoritos
          </div>
          <div class="stat">
            <i class="fas fa-star"></i>
            <span id="detalleRating">0</span>/5
          </div>
        </div>

        <div class="place-actions">
          <button class="btn-action favorite" id="toggleFavorite">
            <i class="fas fa-heart"></i> Favorito
          </button>
          <button class="btn-action route" id="createRouteToPlace">
            <i class="fas fa-route"></i> Crear Ruta
          </button>
          <button class="btn-action share" id="sharePlace">
            <i class="fas fa-share"></i> Compartir
          </button>
        </div>

        <div class="comments-section">
          <h4><i class="fas fa-comments"></i> Comentarios</h4>
          <div class="add-comment">
            <textarea id="nuevoComentario" placeholder="Comparte tu experiencia..."></textarea>
            <div class="comment-actions">
              <select id="comentarioRating">
                <option value="5">⭐⭐⭐⭐⭐</option>
                <option value="4">⭐⭐⭐⭐</option>
                <option value="3">⭐⭐⭐</option>
                <option value="2">⭐⭐</option>
                <option value="1">⭐</option>
              </select>
              <button id="enviarComentario" class="btn-submit">Comentar</button>
            </div>
          </div>
          <div class="comments-list" id="comentariosList"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- VER RUTAS -->
<div class="modal" id="modalOverlay">
  <div class="modal-content detail-modal">
    <div class="modal-header">
      <h2 class="modal-title">📍 Listado de Rutas</h2>
      <button class="btn-cerrar" onclick="cerrarModal()">×</button>
    </div>
    <div class="modal-body">
      <ul class="rutas-lista" id="rutasLista">
        <!-- Las rutas se cargarán aquí dinámicamente -->
      </ul>
    </div>
  </div>
</div>

<!-- Controles de navegación -->
<div class="navigation-controls" id="navigationControls" style="display: none;">
  <div class="nav-info">
    <h4>Navegando ruta</h4>
    <div class="nav-stats">
      <span>Distancia: <span id="navDistance">0km</span></span>
      <span>Tiempo: <span id="navTime">00:00</span></span>
    </div>
  </div>
  <div class="nav-buttons">
    <button id="pauseNav" class="nav-btn">
      <i class="fas fa-pause"></i>
    </button>
    <button id="stopNav" class="nav-btn">
      <i class="fas fa-stop"></i>
    </button>
  </div>
</div>


<div class="modal" id="modalFavoritos">
  <div class="modal-content detail-modal">
    <div class="modal-header">
      <h2 class="modal-title">
        <span>❤️</span>
        Lugares Favoritos
      </h2>
      <button class="btn-cerrar" onclick="cerrarModalFavoritos()">×</button>
    </div>
    <div class="modal-body">
      <div class="favoritos-grid" id="favoritosGrid">
        <!-- Los favoritos se cargarán aquí dinámicamente -->
      </div>
    </div>
  </div>
</div>


  <!-- Modal de perfil -->
  <div class="modal" id="perfilModal">
    <div class="modal-content profile-modal">
      <div class="modal-header">
        <h2><i class="fas fa-user-circle"></i> Mi Perfil</h2>
        <button class="close-modal" id="closePerfilModal">
          <i class="fas fa-times"></i>
        </button>
      </div>
      
      <div class="profile-content">
        <div class="profile-header">
          <div class="profile-avatar">
            <i class="fas fa-user"></i>
          </div>
          <div class="profile-info">
            <h3 id="perfilNombre"><?php echo $_SESSION['usuario_cliente']->name_users ?></h3>
            <p>Explorador Urbano - Nivel <span id="perfilNivel">0</span></p>
            <p class="join-date">Miembro desde: <?php echo $_SESSION['usuario_cliente']->fecha_users ?></p>
          </div>
        </div>
       
      </div>
    </div>
  </div>



<!-- 🗺️ Mapa principal -->
<div id="map"></div>

<!-- 🆘 Botón de emergencia -->
<button id="sosBtn" class="sos">🚨 SOS</button>

<script>
  const base_url = "<?= base_url() ?>";
</script>