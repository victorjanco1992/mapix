<?php

class LoginController extends Controller
{
    private $loginModel;

    public function __construct()
    {
        $this->loginModel = $this->model('LoginModel');
    }

    // ========== Metodo index ==========
    public function index()
    {
        $this->view('login/index');
    }

    // ========== Metodo validar ==========
    public function validar()
    {
        $response = $this->loginModel->validar_();
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    // ========== Metodo validar ==========
    public function registrar()
    {
        $response = $this->loginModel->registrar_();
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }


    // ========== Metodo Salir ==========
    public function logout()
    {
        session_start(); // Asegura que la sesión está iniciada
    
        unset($_SESSION['usuario_cliente']); // Elimina solo la variable 'usuario'
    
        header('Location: ' . base_url() . '');
        exit();
    }
    
}
