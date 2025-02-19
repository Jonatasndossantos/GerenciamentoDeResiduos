<?php
namespace App\DAO;

use App\Config\Database;
use App\Model\Residuo;

class ResiduoDAO {
    private $conn;
    
    public function __construct() {
        $this->conn = Database::getInstance();
    }
    
    public function listar($filtros = [], $pagina = 1, $porPagina = 25) {
        $where_conditions = ["1=1"];
        
        if (!empty($filtros['data_inicio'])) {
            $data_inicio = mysqli_real_escape_string($this->conn, $filtros['data_inicio']);
            $where_conditions[] = "DATE(dt) >= '$data_inicio'";
        }
        
        if (!empty($filtros['data_fim'])) {
            $data_fim = mysqli_real_escape_string($this->conn, $filtros['data_fim']);
            $where_conditions[] = "DATE(dt) <= '$data_fim'";
        }
        
        if (!empty($filtros['categoria'])) {
            $categoria = mysqli_real_escape_string($this->conn, $filtros['categoria']);
            $where_conditions[] = "categoria = '$categoria'";
        }
        
        if (!empty($filtros['search'])) {
            $search = mysqli_real_escape_string($this->conn, $filtros['search']);
            $where_conditions[] = "(categoria LIKE '%$search%' 
                                 OR usuario LIKE '%$search%' 
                                 OR dt LIKE '%$search%' 
                                 OR peso LIKE '%$search%')";
        }
        
        $where_clause = implode(" AND ", $where_conditions);
        
        // Conta total de registros para paginação
        $sql_count = "SELECT COUNT(*) as total FROM residuos WHERE $where_clause";
        $result_count = mysqli_query($this->conn, $sql_count);
        $total = mysqli_fetch_assoc($result_count)['total'];
        
        // Calcula offset para paginação
        $offset = ($pagina - 1) * $porPagina;
        
        // Query principal com LIMIT e OFFSET
        $sql = "SELECT * FROM residuos WHERE $where_clause 
                ORDER BY dt DESC LIMIT $porPagina OFFSET $offset";
        
        return [
            'dados' => mysqli_query($this->conn, $sql),
            'total' => $total,
            'paginas' => ceil($total / $porPagina)
        ];
    }
    
    public function obterEstatisticas($filtros = []) {
        $where_conditions = ["1=1"];
        
        // Aplicar os mesmos filtros da listagem
        // ... código dos filtros ...
        
        $where_clause = implode(" AND ", $where_conditions);
        
        $estatisticas = [];
        
        // Total e contagem
        $sql_total = "SELECT SUM(peso) as total_peso, COUNT(*) as total_registros 
                      FROM residuos WHERE $where_clause";
        $result_total = mysqli_query($this->conn, $sql_total);
        $estatisticas['totais'] = mysqli_fetch_assoc($result_total);
        
        // Maior categoria
        $sql_cat = "SELECT categoria, SUM(peso) as total_peso 
                    FROM residuos WHERE $where_clause 
                    GROUP BY categoria ORDER BY total_peso DESC LIMIT 1";
        $result_cat = mysqli_query($this->conn, $sql_cat);
        $estatisticas['maior_categoria'] = mysqli_fetch_assoc($result_cat);
        
        // Média diária
        $sql_dias = "SELECT COUNT(DISTINCT DATE(dt)) as total_dias, 
                     SUM(peso) as total_peso 
                     FROM residuos WHERE $where_clause";
        $result_dias = mysqli_query($this->conn, $sql_cat);
        $estatisticas['media_diaria'] = mysqli_fetch_assoc($result_dias);
        
        return $estatisticas;
    }
    
    public function obterDadosTrimestre($trimestre, $ano) {
        // Código do antigo getTrimesterData
    }
    
    public function inserir(Residuo $residuo) {
        $sql = "INSERT INTO residuos (usuario, dt, categoria, peso) 
                VALUES (?, ?, ?, ?)";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssd", 
            $residuo->getUsuario(),
            $residuo->getData(),
            $residuo->getCategoria(),
            $residuo->getPeso()
        );
        
        return $stmt->execute();
    }
    
    public function excluir($codigos) {
        if (is_array($codigos)) {
            $codigos = array_map('intval', $codigos);
            $codigosString = implode(',', $codigos);
            $sql = "DELETE FROM residuos WHERE codigo IN ($codigosString)";
        } else {
            $codigo = intval($codigos);
            $sql = "DELETE FROM residuos WHERE codigo = $codigo";
        }
        
        return mysqli_query($this->conn, $sql);
    }
} 