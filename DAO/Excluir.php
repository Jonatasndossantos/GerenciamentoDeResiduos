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
        public function excluirResiduos2(Conexao $conexao, $codigo): bool {
            $codigo = (int)$codigo; // Converte o valor para int
            try {
                $conn = $conexao->conectar();
                if (!$conn) {
                    throw new \Exception("Erro ao conectar ao banco de dados.");
                }
        
                // Usando prepared statements para evitar SQL injection
                $sql = "DELETE FROM Residuos WHERE codigo = ?";
                $stmt = mysqli_prepare($conn, $sql);
        
                if (!$stmt) {
                    throw new \Exception("Erro na preparação da query: " . mysqli_error($conn));
                }
        
                mysqli_stmt_bind_param($stmt, "i", $codigo);
                $result = mysqli_stmt_execute($stmt);
        
                if (!$result) {
                    throw new \Exception("Erro ao executar a query: " . mysqli_stmt_error($stmt));
                }
        
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
        
                return true; // Exclusão bem-sucedida
            } catch (\Exception $erro) {
                echo "Erro: " . $erro->getMessage();
                return false; // Exclusão falhou
            }
        }
    }//fim excluir
?>

