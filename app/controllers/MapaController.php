<?php

class MapaController extends Controller
{
    private $mapa;

    public function __construct()
    {
        $this->mapa = $this->model('MapaModel');
        session_start();
        
     
        if (!isset($_SESSION['usuario_cliente'])) {
            header('Location: ' . base_url() . '');
            exit();
        }
    }

    // ========== Metodo index ==========
    public function index()
    {
        //$usuario = $_SESSION['usuario_cliente'];
        $data = [
            'titulo' => 'Mapa',
            'js' => 'mapa',
        ];

        $this->view('inicio/index', $data);
    }
    
    // ========== Metodo Agregar ==========
    public function store()
    {
        $response = $this->mapa->store_();
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    // ========== Metodo Agregar ==========
    public function storePlaces()
    {
        $response = $this->mapa->storePlaces_();
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
   
    // ========== Obtener Agregar ==========
    public function getPlacesComents()
    {
        $response = $this->mapa->getPlacesComents_();
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
   
    // ========== Obtener  ==========
    public function obtenerRutas()
    {
        $response = $this->mapa->obtenerRutas_();
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
   
    // ========== Obtener  ==========
    public function obtenerFavoritos()
    {
        $response = $this->mapa->obtenerFavoritos_();
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    // ========== Metodo Editar Favorito ==========
    public function ToggleFavorite()
    {
        $response = $this->mapa->ToggleFavorite_();
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
   
    // ========== Metodo Editar Comebtar ==========
    public function addComment()
    {
        $response = $this->mapa->addComment_();
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

}
