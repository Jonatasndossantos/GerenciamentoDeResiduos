<?php
namespace App\Service;

use App\DAO\ResiduoDAO;

class DashboardService {
    private $residuoDAO;
    
    public function __construct(ResiduoDAO $residuoDAO) {
        $this->residuoDAO = $residuoDAO;
    }
    
    public function getDadosTrimestre($trimestre, $ano) {
        return $this->residuoDAO->obterDadosTrimestre($trimestre, $ano);
    }
    
    public function getEstatisticas($filtros = []) {
        return $this->residuoDAO->obterEstatisticas($filtros);
    }
} 