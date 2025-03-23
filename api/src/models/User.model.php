<?php

class User implements JsonSerializable {
    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;

    public function __construct($id, $nombre, $apellido, $email, $password)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->email = $email;
        $this->password = $password;
    }

    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getApellido() {
        return $this->apellido;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function jsonSerialize(): mixed {
        return get_object_vars($this);
    }
}