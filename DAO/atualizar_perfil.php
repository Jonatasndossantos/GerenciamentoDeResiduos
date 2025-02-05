<?php
namespace PHP\Modelo\DAO;

session_start();
require_once('Conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexao = new Conexao();
    $conn = $conexao->conectar();
    
    $usuario_id = $_SESSION['usuario_id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    
    // Processa o upload da foto se houver
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto_tmp = $_FILES['foto']['tmp_name'];
        $foto_nome = time() . '_' . $_FILES['foto']['name'];
        $foto_destino = '../uploads/perfil/' . $foto_nome;
        
        // Cria o diretório se não existir
        if (!file_exists('../uploads/perfil/')) {
            mkdir('../uploads/perfil/', 0777, true);
        }
        
        if (move_uploaded_file($foto_tmp, $foto_destino)) {
            $sql = "UPDATE usuarios SET nome = ?, email = ?, telefone = ?, foto_perfil = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $nome, $email, $telefone, $foto_destino, $usuario_id);
        }
    } else {
        $sql = "UPDATE usuarios SET nome = ?, email = ?, telefone = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $nome, $email, $telefone, $usuario_id);
    }
    
    if ($stmt->execute()) {
        header('Location: ../Templetes/perfil.php?success=1');
    } else {
        header('Location: ../Templetes/perfil.php?error=1');
    }
    exit;
} 