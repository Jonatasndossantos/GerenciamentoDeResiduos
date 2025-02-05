<?php
    namespace PHP\Modelo\DAO;
    require_once('Conexao.php');
    use PHP\Modelo\DAO\Conexao;


    class Consultar{
        function consultarUsuarioIndividual(
            Conexao $conexao,
            string $usuario,
            string $senha
        ){
            try{
                $conexao = new Conexao();
                $conn = $conexao->conectar();

                if ($conn) {
                    // Executa a consulta
                    $sql = "SELECT * FROM usuario";
                    $result = mysqli_query($conn, $sql);
                
                    // Verifica se a consulta foi bem-sucedida
                    if ($result) {
                        $row = mysqli_num_rows($result);
                        if ($row > 0) {
                            while ($res = mysqli_fetch_array($result)) {
                                if($dados['codigo'] == $usuario){
                                    echo "<br>Usuario: ".$dados['codigo'].
                                         "<br>Senha: ".$dados['senha'];
                                    return;//Finalizar o while
                                }
                                return "código digitado invalido!";
                            }
                        } else {
                            echo "Nenhum registro encontrado.";
                        }
                    } else {
                        echo "Erro na consulta: " . mysqli_error($conn);
                    }
                
                    // Fecha a conexão (opcional)
                    mysqli_close($conn);
                } else {
                    echo "Não foi possível conectar ao banco de dados.";
                }

            }catch(Except $erro){
                echo $erro;
            }
        }//fim do consultarUsuarioIndividual
    }//fim da classe


?>
