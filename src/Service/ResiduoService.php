<?php
namespace App\Service;

use App\DAO\ResiduoDAO;

class ResiduoService {
    private $residuoDAO;
    
    public function __construct(ResiduoDAO $residuoDAO) {
        $this->residuoDAO = $residuoDAO;
    }
    
    public function listarComFiltros($filtros) {
        return $this->residuoDAO->listar($filtros);
    }
    
    public function gerarEstatisticas($filtros) {
        return $this->residuoDAO->obterEstatisticas($filtros);
    }
} 