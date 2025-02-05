<?php
namespace PHP\Modelo\DAO;
require_once('Conexao.php');
require_once('Atualizar.php');

use PHP\Modelo\DAO\Conexao;
use PHP\Modelo\DAO\Atualizar;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo'];
    $dt = $_POST['dt'];
    $categoria = $_POST['categoria'];
    $peso = $_POST['peso'];

    $conexao = new Conexao();
    $atualizar = new Atualizar();

    try {
        $conn = $conexao->conectar();
        $sql = "UPDATE Residuos SET dt = ?, categoria = ?, peso = ? WHERE codigo = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssdi", $dt, $categoria, $peso, $codigo);
        
        if (mysqli_stmt_execute($stmt)) {
            header('Location: ../Telas/Menu.php?success=1');
        } else {
            header('Location: ../Telas/Menu.php?error=1');
        }
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    } catch (Exception $e) {
        header('Location: ../Telas/Menu.php?error=1');
    }
}
?> 