<?php
    namespace PHP\Modelo\DAO;
    require_once('Conexao.php');
    use PHP\Modelo\DAO\Conexao;

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    class Atualizar{
        function atualizarCliente(
            Conexao $conexao,
            string $campo,
            string $novoDado, 
            string $cpf
        ){
            $conn = $conexao->conectar();
            $sql  = "update cliente set $campo = '$novoDado' where codigo = '$cpf'";
            $result = mysqli_query($conn, $sql);
            mysqli_close($conn);
            if($result){
                echo "<br>Atualizado com sucesso!";
            }else{
                echo "<br>Não Atualizado!";
            }

        }//Fim atualizaCliente

        function atualizarFuncionario(
            Conexao $conexao,
            string $campo,
            string $novoDado, 
            string $cpf
        ){
            $conn = $conexao->conectar();
            $sql  = "update Funcionario set $campo = '$novoDado' where codigo = '$cpf'";
            $result = mysqli_query($conn, $sql);
            mysqli_close($conn);
            if($result){
                echo "<br>Atualizado com sucesso!";
            }else{
                echo "<br>Não Atualizado!";
            }
    
        }//Fim atualizaFuncionario
    }//Fim Atualizar
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $conexao = new Conexao();
        $conn = $conexao->conectar();
        
        $usuario = mysqli_real_escape_string($conn, $_POST['usuario']);
        $senha = mysqli_real_escape_string($conn, $_POST['senha']);
        $usuario_atual = mysqli_real_escape_string($conn, $_SESSION['usuario']);
        
        $sql = "UPDATE usuario SET usuario = '$usuario', senha = '$senha' WHERE usuario = '$usuario_atual'";
        
        if (mysqli_query($conn, $sql)) {
            $_SESSION['usuario'] = $usuario; // Atualiza a sessão com o novo usuário
            header('Location: ../Telas/perfil.php?success=1');
        } else {
            header('Location: ../Telas/perfil.php?error=1');
        }
        exit;
    } 
?>