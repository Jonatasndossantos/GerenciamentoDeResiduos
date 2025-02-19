<?php
require_once '../vendor/autoload.php';

use App\Service\ResiduoService;
use App\Service\DashboardService;
use App\DAO\ResiduoDAO;

$residuoDAO = new ResiduoDAO();
$residuoService = new ResiduoService($residuoDAO);
$dashboardService = new DashboardService($residuoDAO);

$filtros = [
    'data_inicio' => $_GET['data_inicio'] ?? null,
    'data_fim' => $_GET['data_fim'] ?? null,
    'categoria' => $_GET['categoria_filtro'] ?? null,
    'search' => $_GET['search'] ?? null
];

$residuos = $residuoService->listarComFiltros($filtros);
$estatisticas = $dashboardService->getEstatisticas($filtros); 