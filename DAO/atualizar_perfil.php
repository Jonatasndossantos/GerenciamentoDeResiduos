<?php
namespace PHP\Modelo\DAO;

session_start();
require_once('Conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexao = new Conexao();
    $conn = $conexao->conectar();
    
    $usuario = mysqli_real_escape_string($conn, $_POST['usuario']);
    $senha = mysqli_real_escape_string($conn, $_POST['senha']);
    $usuario_atual = mysqli_real_escape_string($conn, $_SESSION['usuario']);
    
    $sql = "UPDATE usuario SET usuario = '$usuario', senha = '$senha' WHERE usuario = '$usuario_atual'";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['usuario'] = $usuario; // Atualiza a sessão com o novo usuário
        header('Location: ../Templetes/perfil.php?success=1');
    } else {
        header('Location: ../Templetes/perfil.php?error=1');
    }
    exit;
} 