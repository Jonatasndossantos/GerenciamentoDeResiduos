<?php
namespace PHP\Modelo\Telas;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('../DAO/Conexao.php');
require_once('../DAO/Atualizar.php');

use PHP\Modelo\DAO\Conexao;
use PHP\Modelo\DAO\Atualizar;

$codigo = $_GET['codigo'] ?? '';
$dt = $_GET['dt'] ?? '';
$categoria = $_GET['categoria'] ?? '';
$peso = $_GET['peso'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexao = new Conexao();
    $atualizar = new Atualizar();
    
    try {
        $conn = $conexao->conectar();
        $sql = "UPDATE Residuos SET dt = ?, categoria = ?, peso = ? WHERE codigo = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssdi", $_POST['dt'], $_POST['categoria'], $_POST['peso'], $_POST['codigo']);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['message'] = "Atualizado com sucesso!";
        } else {
            $_SESSION['message'] = "Erro ao atualizar!";
        }
        
        mysqli_stmt_close($stmt);
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
</head>
<body class="container">
    <!--botao dark-->
    <?php include('../Templetes/BotaoDark.php');?>
    <!--fim botao dark-->

    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <input type="hidden" name="codigo" value="<?php echo $codigo; ?>">
                <div class="modal-header">                        
                    <h4 class="modal-title">Editar Residuo</h4>
                </div>
                <div class="modal-body">                    
                    <div class="form-group">
                        <label>Data</label>
                        <input name="dt" type="datetime-local" class="form-control" required value="<?php echo $dt; ?>">
                    </div>
                    <div class="form-group">
                        <label>Categoria</label>
                        <select name="categoria" class="form-select" required>
                            <?php
                            $categorias = [
                                'nao reciclavel', 'reciclavel', 'óleo', 'tampinhas plasticas',
                                'lacres de aluminio', 'tecidos', 'meias', 'material de escrita',
                                'sponjas', 'eletrônicos', 'pilhas e baterias', 'infectante',
                                'químicos', 'lâmpada fluorescente', 'tonners de impressora',
                                'esmaltes', 'cosméticos', 'cartela de medicamento'
                            ];
                            foreach ($categorias as $cat) {
                                $selected = ($categoria == $cat) ? 'selected' : '';
                                echo "<option value='$cat' $selected>$cat</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Peso</label>
                        <input name="peso" type="decimal" class="form-control" required value="<?php echo $peso; ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="Menu.php" class="btn btn-default">Cancelar</a>
                    <button type="submit" class="btn btn-info">Atualizar</button>
                </div>
            </form>
        </div>
    </div>
    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

 <!--javascript do botao-->
 <script src="../js/BotaoDark.js"></script>
</body>
</html>