<?php
namespace App\Model;

class Residuo {
    private $codigo;
    private $usuario;
    private $data;
    private $categoria;
    private $peso;
    
    public function getCodigo() { return $this->codigo; }
    public function setCodigo($codigo) { $this->codigo = $codigo; }
    
    public function getUsuario() { return $this->usuario; }
    public function setUsuario($usuario) { $this->usuario = $usuario; }
    
    public function getData() { return $this->data; }
    public function setData($data) { $this->data = $data; }
    
    public function getCategoria() { return $this->categoria; }
    public function setCategoria($categoria) { $this->categoria = $categoria; }
    
    public function getPeso() { return $this->peso; }
    public function setPeso($peso) { $this->peso = $peso; }
} 