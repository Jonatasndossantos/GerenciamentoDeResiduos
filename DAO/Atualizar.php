<?php
    namespace PHP\Modelo\DAO;
    require_once('Conexao.php');
    use PHP\Modelo\DAO\Conexao;

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
    

?>