<?php
    namespace PHP\Modelo\DAO;
    require_once('Conexao.php');
    use PHP\Modelo\DAO\Conexao;

    class excluir{
        function excluirResiduos(
            conexao $conexao,
            int $codigo
        ){
            $conn = $conexao->conectar();
            $sql  = "delete from Residuos where codigo = '$codigo'";
            $result = mysqli_query($conn,$sql);
            mysqli_close($conn);

            if($result){
                echo "Deletado com sucesso!";

            }else{
                echo "Não deletado!";
            }
        }//fim excluirResiduos
        
    }//fim excluir
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
                
                echo "Erro ao excluir: " . mysqli_error($conn);
            }
            
            mysqli_close($conn);
        } else {
            
            echo "Erro na conexão com o banco de dados";
        }
    } else {
        
        echo "Requisição inválida";
    }
?>

