<?php
namespace PHP\Modelo\Telas;

require_once('..\DAO\Conexao.php');

use PHP\Modelo\DAO\Conexao;

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

$usuario = $_SESSION['usuario'];
$usuario_escaped = mysqli_real_escape_string($conn, $usuario);

$sql = "SELECT usuario, senha FROM usuario WHERE usuario = '$usuario_escaped'";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Erro na consulta: " . mysqli_error($conn));
}

$dados = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../Css/BotaoDark.css">
    <style>
        body{
        background-image: url(../img/Reciclagem.jpeg);
        }
    </style>
</head>
<body>
    <!--botao dark-->
    <?php include('../Templetes/BotaoDark.php');?>
    <!--fim botao dark-->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Meu Perfil</h4>
                    </div>
                    <div class="card-body">
                        <!-- Modal de Edição -->
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="../DAO/Atualizar.php" method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Editar Dados</h5>
                                        <a href="../Telas/Menu.php"><button type="button" class="btn-close" data-bs-dismiss="modal"></button></a>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="usuario" class="form-label">Usuário</label>
                                            <input type="text" class="form-control" id="usuario" name="usuario" disabled 
                                                   value="<?php echo htmlspecialchars($dados['usuario']); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="senha" class="form-label">Nova Senha</label>
                                            <input type="password" class="form-control" id="senha" name="senha">
                                        </div>
                                        <div class="mb-3">
                                            <label for="confirmar_senha" class="form-label">Confirmar Nova Senha</label>
                                            <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha">
                                        </div>
                                    </div>
                                    <div class="modal-footer d-flex justify-content-between">
                                            <a href="../Telas/Menu.php"class="btn btn-danger text-end">
                                                Voltar
                                            </a>
                                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                                    </div>
                                </form>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>

   

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
 <!--javascript do botao-->
 <script src="../js/BotaoDark.js"></script>
</body>
</html> 