<?php
// New dashboard file
namespace PHP\Modelo\Telas;

require_once('../DAO/Conexao.php');
use PHP\Modelo\DAO\Conexao;

class Dashboard {
    public function getTrimesterData($trimester, $year) {
        $conexao = new Conexao();
        $conn = $conexao->conectar();
        
        // Calculate date range for trimester
        $startDate = date("Y-m-d", mktime(0, 0, 0, ($trimester * 3) - 2, 1, $year));
        $endDate = date("Y-m-d", mktime(0, 0, 0, ($trimester * 3) + 1, 0, $year));
        
        // Get total weight by category
        $sql = "SELECT categoria, 
                       SUM(peso) as total_peso,
                       COUNT(*) as total_registros 
                FROM residuos 
                WHERE dt BETWEEN '$startDate' AND '$endDate'
                GROUP BY categoria
                ORDER BY total_peso DESC";
                
        $result = mysqli_query($conn, $sql);
        return $result;
    }
}