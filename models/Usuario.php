<?php

namespace Model;

class Usuario extends ActiveRecord {
    // Base de datos

    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id','nombre','apellido','email','password','telefono','admin','confirmado','token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;


    public function __construct($args =[]) {

        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';


    }

    //Mensajes de validacion para la creacion de una cuenta
    public function validarNuevaCuenta() {
        if (!$this->nombre) {
            self::$alertas['error'] [] = 'El nombre del cliente es obligatorio';
        }

        if (!$this->apellido) {
            self::$alertas['error'] [] = 'El apellido del cliente es obligatorio';
        }

        if (!$this->email) {
            self::$alertas['error'] [] = 'El email es obligatorio';
        }

        if (!$this->password) {
            self::$alertas['error'] [] = 'El password es obligatorio';
        }

        if (strlen($this->password) < 6) {
            self::$alertas['error'] [] = 'El password debe contener al menos 6 caracteres';

        }

        return self::$alertas;
    }



    public function validarLogin() {

        if (!$this->email) {
            self::$alertas['error'] [] = 'El email es obligatorio';
        }

        if (!$this->password) {
            self::$alertas['error'] [] = 'El password es obligatorio';
        }

        return self::$alertas; 

    }


    public function validarEmail() {
        
        if (!$this->email) {
            self::$alertas['error'] [] = 'El email es obligatorio';
        }
        return self::$alertas;
    }


    public function validarPassword() {
        if (!$this->password) {
            self::$alertas ['error'] [] = 'El Password es obligatorio';
        }

        if (strlen($this->password) < 6) {
            self::$alertas ['error'] [] = 'El Password debe tener al menos 6 caracteres';
        }
    }


    //Revisa si el usuario ya existe 
    public function existeUsuario() {

        $query = "Select * from " . self::$tabla . " where email = '" . $this->email . "' Limit 1";

        $resultado = self::$db->query($query);

        if ($resultado->num_rows) {
            self::$alertas['error'] [] = 'El usuario ya esta registrado';
        }
 
        return $resultado;
    }


    //Hashear password
    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }


    //Crear token 
    public function crearToken() {
        $this->token = uniqid();
    }


    public function comprobarPasswordAndVerificado($password) {
        
        $resultado = password_verify($password,$this->password);
        
        if (!$resultado || !$this->confirmado) {
           self::$alertas['error'] [] = 'Password Incorrecto o tu cuenta no ha sido confirmada';

        }else {
            return true;
        }

    }

    
}

