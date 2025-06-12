<?php

return [
    '' => ['controller' => 'LoginController', 'method' => 'index'],

    'login/validar' => ['controller' => 'LoginController', 'method' => 'validar'],
    'login/registrar' => ['controller' => 'LoginController', 'method' => 'registrar'],

    'login/salir' => ['controller' => 'LoginController', 'method' => 'logout'],

    'inicio' => ['controller' => 'MapaController', 'method' => 'index'],
    'inicio/guardar-rutas' => ['controller' => 'MapaController', 'method' => 'store'],
    
    'inicio/guardar-lugar' => ['controller' => 'MapaController', 'method' => 'storePlaces'],
    
    'inicio/obtener-lugares' => ['controller' => 'MapaController', 'method' => 'getPlacesComents'],

    'inicio/obtener-rutas' => ['controller' => 'MapaController', 'method' => 'obtenerRutas'],

    'inicio/obtener-favoritos' => ['controller' => 'MapaController', 'method' => 'obtenerFavoritos'],

    'inicio/toggle-favorite' => ['controller' => 'MapaController', 'method' => 'ToggleFavorite'],
    
    'inicio/comentar' => ['controller' => 'MapaController', 'method' => 'addComment'],
];
