<?php
session_start(); // Inicia a sessão (se necessário)

require_once('Conexao.php'); // Inclui a classe de conexão
require_once('Excluir.php'); // Inclui a classe de exclusão

use PHP\Modelo\DAO\Conexao;
use PHP\Modelo\DAO\Excluir;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigos'])) {
    $conexao = new Conexao();
    $conn = $conexao->conectar();
    
    if ($conn) {
        $codigos = explode(',', $_POST['codigos']);
        $codigos = array_map('intval', $codigos); // Sanitiza os valores
        $codigosString = implode(',', $codigos);
        
        $sql = "DELETE FROM residuos WHERE codigo IN ($codigosString)";
        
        if (mysqli_query($conn, $sql)) {
            echo "success";
        } else {
            http_response_code(500);
            echo "Erro ao excluir: " . mysqli_error($conn);
        }
        
        mysqli_close($conn);
    } else {
        http_response_code(500);
        echo "Erro na conexão com o banco de dados";
    }
} else {
    http_response_code(400);
    echo "Requisição inválida";
}
?>