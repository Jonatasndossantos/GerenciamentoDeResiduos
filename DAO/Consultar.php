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
                    
                    // Agora a consulta corrigida
                    $sql = "SELECT * FROM usuario where usuario = '$usuario' AND senha = '$senha'";
                    $result = mysqli_query($conn, $sql);
                    // Verifica se a consulta foi bem-sucedida
                    if ($result) {
                        if (mysqli_num_rows($result) > 0) {
                            while($dados = mysqli_fetch_array($result)) {
                                if($dados['usuario'] == $usuario) {
                                    echo "<br>Usuario: ".$dados['usuario'].
                                         "<br>Senha: ".$dados['senha'];
                                    return true;//Finalizar o while
                                }
                            }
                            if(!$dados || $dados['codigo'] != $usuario) {
                                echo "<br>Código de usuário inválido!";
                            }
                        } else {
                            echo "<br>Senha inválida!";
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

        public function atualizarSenha($conexao, $usuario, $novaSenha) {
            try {
                $conn = $conexao->conectar();
                $senhaHash = ($novaSenha);
                
                $sql = "UPDATE usuario SET senha = '$senhaHash' WHERE usuario = '$usuario'";
                $result = mysqli_query($conn, $sql);
                
                if ($result) {
                    return true;
                } else {
                    echo "Erro ao atualizar senha: " . mysqli_error($conn);
                    return false;
                }
                
            } catch(Exception $erro) {
                echo $erro;
                return false;
            }
        }

    }//fim da classe


?>
