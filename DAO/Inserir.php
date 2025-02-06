<?php
    namespace PHP\Modelo\DAO;

    require_once('Conexao.php');

    use PHP\Modelo\DAO\Conexao;

    class Inserir{
        function cadastrarResiduos(Conexao $conexao,
                                       string $usuario,
                                       string $dt,
                                       string $categoria,
                                       string $peso,
                                       string $destino
        ){
            try{
                $conn = $conexao->conectar();//Abrir banco de dados codigo, usuario, dt, categoria, peso, destino
                $sql  = "insert into Residuos(codigo,usuario,dt,categoria,peso,destino)
                         values('','$usuario','$dt','$categoria','$peso','$destino')";
                $result = mysqli_query($conn, $sql);
                mysqli_close($conn);
                if($result){
                    return "<br><br>Inserido com Sucesso!";
                }
                return "<br><br>Não Inserido!";
            }catch(Except $erro){
                return "<br><br> Algo deu errado".$erro;
            }


        }//fim metodo cadastrarResiduos


        function cadastrarUsuario(Conexao $conexao,
                                       string $cpf,
                                       string $nome,
                                       string $endereco,
                                       string $telefone,
                                       float  $salario
        ){
            try{
                $conn = $conexao->conectar();//Abrir banco de dados
                $sql  = "insert into Usuario(codigo,nome,telefone,endereco,salario)
                         values('$cpf','$nome','$telefone','$endereco','$salario')";
                $result = mysqli_query($conn, $sql);
                mysqli_close($conn);
                if($result){
                    return "<br><br>Inserido com Sucesso!";
                }
                return "<br><br>Não Inserido!";
            }catch(Except $erro){
                return "<br><br> Algo deu errado".$erro;
            }


        }//fim metodo cadastrarUsuario
    
    
    }//Fim class


?>