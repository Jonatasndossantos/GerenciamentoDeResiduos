<?php
require_once('../DAO/Conexao.php');

use PHP\Modelo\DAO\Conexao;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conexao = new Conexao();
    $conn = $conexao->conectar();
    
    if ($conn) {
        $codigo = mysqli_real_escape_string($conn, $_POST['codigo']);
        $categoria = mysqli_real_escape_string($conn, $_POST['categoria']);
        
        $sql = "UPDATE Categoria SET categoria = ? WHERE codigo = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $categoria, $codigo);
        
        if (mysqli_stmt_execute($stmt)) {
            header("Location: addCategoria.php?status=success");
        } else {
            header("Location: addCategoria.php?status=error");
        }
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
} 