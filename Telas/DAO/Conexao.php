<?php
    namespace PHP\Modelo\DAO;


    class Conexao{
        public function conectar(){
            try{                
                $conn = mysqli_connect('localhost', 'root', '', 'Pesagem');
                if($conn){
                    return $conn;
                }
                echo ",br> Algo deu errado!";
            }catch(Except $erro){
                return "Algo deu errado!<br><br>".$erro;
            }
        }//Fim conectar


    }//Fim class

?>