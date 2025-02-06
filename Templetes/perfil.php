<?php
namespace PHP\Modelo\Telas;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('..\DAO\Conexao.php');

use PHP\Modelo\DAO\Conexao;

if (!isset($_SESSION['usuario'])) {
    header('Location: ../Telas/login.php');
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
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Meu Perfil</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="fw-bold">Usuário:</label>
                            <p><?php echo htmlspecialchars($dados['usuario']); ?></p>
                        </div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            Editar Dados
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Edição -->
    <div class="modal fade" id="editProfileModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="../DAO/atualizar_perfil.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Dados</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="usuario" class="form-label">Usuário</label>
                            <input type="text" class="form-control" id="usuario" name="usuario" 
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 