<?php
namespace PHP\Modelo\Telas;

require_once('../DAO/Conexao.php');
require_once('../DAO/Inserir.php');

use PHP\Modelo\DAO\Conexao;
use PHP\Modelo\DAO\Inserir;


// Inicia a sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {    
    // Redireciona para a página de login
    header('Location: login.php');
    exit;
}


$conexao = new Conexao();
$conn = $conexao->conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = "";
    $message = "";
    
    if (isset($_POST['dt']) &&
        isset($_POST['categoria']) &&
        isset($_POST['peso']) &&
        isset($_POST['destino'])) {
        
        $dt = $_POST['dt'];
        $categoria = $_POST['categoria'];
        $peso = $_POST['peso'];
        $destino = $_POST['destino'];
        
        $inserir = new Inserir();
        $result = $inserir->cadastrarResiduos($conexao, $_SESSION['usuario'], $dt, $categoria, $peso, $destino);
        
        $_SESSION['message'] = $result;
        header('Location: Menu.php');
        exit();
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../Css/BotaoDark.css">
    <style>
        body{
        background-image: url(../img/Reciclagem.jpeg);
        }
    </style>
</head>
<body class="container">
    <!--botao dark-->
    <?php include('../Templetes/BotaoDark.php');?>
    <!--fim botao dark-->
    
<div class="pt-3 container-xl">
    <div class="card p-3 modal-dialog ">
        <div class="modal-content grid gap-3">
            <form class="modal-content grid gap-3" method="POST">
                <div class="modal-header">                        
                    <h4 class="modal-title">Adicionar Residuos</h4>
                </div>
                <div class="modal-body">                    
                    <div class="form-group">
                        <label>Data</label>
                        <input name="dt" type="datetime-local" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Categoria</label>
                        <select name="categoria" class="form-select" required>
                            <option value="">Todas as categorias</option>
                            <?php
                            // Buscar categorias do banco de dados
                            $sql = "SELECT categoria FROM categoria ORDER BY categoria";
                            $result = mysqli_query($conn, $sql);

                            if ($result) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $cat = $row['categoria'];
                                    echo "<option value='$cat'>$cat</option>";
                                }
                            } else {
                                echo "<option value=''>Erro ao carregar categorias</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Peso</label>
                        <input name="peso" type="decimal" class="form-control" required placeholder="00,0">
                    </div>        
                    <div class="form-group">
                        <label>Destino</label>
                        <input name="destino" type="text" class="form-control" required placeholder="1234 Main St">
                    </div>                    
                </div>
                <div class="modal-footer">
                    <a href="Menu.php" class="btn btn-default">Cancelar</a>
                    <button type="submit" class="btn btn-info">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

 <!--javascript do botao-->
 <script src="../js/BotaoDark.js"></script>
</body>
</html>