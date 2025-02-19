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
                            echo "<script>alert('Senha ou Usuario invalidos.');</script>";
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

        function consultarCategoria(
        ){
            try{
                $conexao = new Conexao();
                $conn = $conexao->conectar();

                if ($conn) {
                    
                    // Agora a consulta corrigida
                $sql = "SELECT * FROM categoria ORDER BY categoria";
                    $result = mysqli_query($conn, $sql);
                    // Verifica se a consulta foi bem-sucedida
                    if ($result) {
                        if (mysqli_num_rows($result) > 0) {
                            while ($categoria = mysqli_fetch_assoc($result)){
                                if($dados['categoria'] == $categoria) {
                                    echo "<option value=".$dados['categoria'].">".$dados['categoria']."</option>";
                                    return true;//Finalizar o while
                                }
                            }
                            if(!$dados || $dados['codigo'] != $categoria) {
                                echo "<br>Código de usuário inválido!";
                            }
                        } else {
                            echo "<script>alert('Senha ou categoria invalidos.');</script>";
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

        public function consultarResiduos($filtros = array()) {
            try {
                $conexao = new Conexao();
                $conn = $conexao->conectar();
                
                // Inicia com a condição base
                $where_conditions = array("1=1");
                
                // Aplica filtros de data
                if (!empty($filtros['data_inicio'])) {
                    $data_inicio = mysqli_real_escape_string($conn, $filtros['data_inicio']);
                    $where_conditions[] = "DATE(dt) >= '$data_inicio'";
                }
                
                if (!empty($filtros['data_fim'])) {
                    $data_fim = mysqli_real_escape_string($conn, $filtros['data_fim']);
                    $where_conditions[] = "DATE(dt) <= '$data_fim'";
                }
                
                // Aplica filtro de categoria
                if (!empty($filtros['categoria'])) {
                    $categoria = mysqli_real_escape_string($conn, $filtros['categoria']);
                    $where_conditions[] = "categoria = '$categoria'";
                }
                
                // Aplica filtro de pesquisa
                if (!empty($filtros['search'])) {
                    $search = mysqli_real_escape_string($conn, $filtros['search']);
                    $where_conditions[] = "(categoria LIKE '%$search%' 
                                         OR usuario LIKE '%$search%' 
                                         OR dt LIKE '%$search%' 
                                         OR peso LIKE '%$search%')";
                }
                
                // Monta a query
                $where_clause = implode(" AND ", $where_conditions);
                $sql = "SELECT * FROM residuos WHERE $where_clause ORDER BY dt DESC";
                
                // Executa a consulta
                $result = mysqli_query($conn, $sql);
                
                if (!$result) {
                    throw new \Exception("Erro na consulta: " . mysqli_error($conn));
                }
                
                return $result;
                
            } catch (\Exception $erro) {
                error_log($erro->getMessage());
                return false;
            }
        }

        public function consultarEstatisticas($filtros = array()) {
            try {
                $conexao = new Conexao();
                $conn = $conexao->conectar();
                
                // Prepara as condições WHERE com base nos filtros
                $where_conditions = array("1=1");
                
                if (!empty($filtros)) {
                    if (!empty($filtros['data_inicio'])) {
                        $data_inicio = mysqli_real_escape_string($conn, $filtros['data_inicio']);
                        $where_conditions[] = "DATE(dt) >= '$data_inicio'";
                    }
                    
                    if (!empty($filtros['data_fim'])) {
                        $data_fim = mysqli_real_escape_string($conn, $filtros['data_fim']);
                        $where_conditions[] = "DATE(dt) <= '$data_fim'";
                    }
                    
                    if (!empty($filtros['categoria'])) {
                        $categoria = mysqli_real_escape_string($conn, $filtros['categoria']);
                        $where_conditions[] = "categoria = '$categoria'";
                    }
                }
                
                $where_clause = implode(" AND ", $where_conditions);
                
                // Consultas para estatísticas
                $estatisticas = array();
                
                // Total de resíduos e registros
                $sql_total = "SELECT SUM(peso) as total_peso, COUNT(*) as total_registros 
                             FROM residuos WHERE $where_clause";
                $result_total = mysqli_query($conn, $sql_total);
                $estatisticas['totais'] = mysqli_fetch_assoc($result_total);
                
                // Maior categoria
                $sql_cat = "SELECT categoria, SUM(peso) as total_peso 
                           FROM residuos WHERE $where_clause 
                           GROUP BY categoria ORDER BY total_peso DESC LIMIT 1";
                $result_cat = mysqli_query($conn, $sql_cat);
                $estatisticas['maior_categoria'] = mysqli_fetch_assoc($result_cat);
                
                // Média diária
                $sql_dias = "SELECT COUNT(DISTINCT DATE(dt)) as total_dias, 
                            SUM(peso) as total_peso 
                            FROM residuos WHERE $where_clause";
                $result_dias = mysqli_query($conn, $sql_dias);
                $estatisticas['media_diaria'] = mysqli_fetch_assoc($result_dias);
                
                return $estatisticas;
                
            } catch (\Exception $erro) {
                error_log($erro->getMessage());
                return false;
            }
        }

    }//fim da classe


?>
