<?php
namespace PHP\Modelo\Telas;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['usuario'])) {
    // Redireciona para a página de login
    header('Location: login.php');
    exit();
}
require_once('../DAO/Conexao.php');
require_once('../DAO/Inserir.php');

use PHP\Modelo\DAO\Conexao;
use PHP\Modelo\DAO\Inserir;

$conexao = new Conexao();
$conn = $conexao->conectar();


$codigo = $_GET['codigo'] ?? '';
$dt = $_GET['dt'] ?? '';
$categoria = $_GET['categoria'] ?? '';
$peso = $_GET['peso'] ?? '';
$destino = $_GET['destino'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    try {
            $codigo = $_POST['codigo'];
            $dt = $_POST['dt'];
            $categoria = $_POST['categoria'];
            $peso = $_POST['peso'];
            $destino = $_POST['destino'];
            
            
            $sql = "UPDATE Residuos SET dt = '$dt', categoria = '$categoria', peso = '$peso', destino = '$destino' WHERE codigo = $codigo";
            if (!mysqli_query($conn, $sql)) {
                $erro = "Erro ao atualizar categoria: " . mysqli_error($conn);
                $_SESSION['message'] = "Atualizado com sucesso!";
            }else {
            $_SESSION['message'] = "Erro ao atualizar!";
        }
        
        mysqli_close($conn);
        header('Location: Menu.php');
        exit();
    } catch (Exception $e) {
        $_SESSION['message'] = "Erro: " . $e->getMessage();
        header('Location: Menu.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
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
                <input type="hidden" name="codigo" value="<?php echo $codigo; ?>">
                <div class="modal-header">                        
                    <h4 class="modal-title">Adicionar Residuos</h4>
                </div>
                <div class="modal-body">                    
                    <div class="form-group">
                        <label>Data</label>
                        <input name="dt" type="datetime-local" class="form-control" required value="<?php echo $dt; ?>">
                    </div>
                    <div class="form-group">
                        <label>Categoria</label>
                        <select name="categoria" class="form-select" required>
                            <option value="<?php echo $categoria; ?>"><?php echo $categoria; ?></option>
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
                        <input name="peso" type="decimal" class="form-control" required value="<?php echo $peso; ?>">
                    </div>        
                    <div class="form-group">
                        <label>Destino</label>
                        <input name="destino" type="text" class="form-control" value="<?php echo $destino; ?>">
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