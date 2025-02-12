<?php
namespace PHP\Modelo\Telas;
require_once('../DAO/Conexao.php');
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

// Processar ações
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['acao'])) {
        switch ($_POST['acao']) {
            case 'adicionar':
                $categoria = ucwords(trim($_POST['categoria']));
                $sql = "INSERT INTO categoria (categoria) VALUES ('$categoria')";
                if (!mysqli_query($conn, $sql)) {
                    $erro = "Erro ao adicionar categoria: " . mysqli_error($conn);
                }
                break;

            case 'editar':
                $codigo = $_POST['codigo'];
                $categoria = ucwords(trim($_POST['categoria']));
                $sql = "UPDATE categoria SET categoria = '$categoria' WHERE codigo = $codigo";
                if (!mysqli_query($conn, $sql)) {
                    $erro = "Erro ao atualizar categoria: " . mysqli_error($conn);
                }
                break;

            case 'excluir':
                $codigo = $_POST['codigo'];
                $sql = "DELETE FROM categoria WHERE codigo = $codigo";
                if (!mysqli_query($conn, $sql)) {
                    $erro = "Erro ao excluir categoria: " . mysqli_error($conn);
                }
                break;
        }
    }
}

// Buscar todas as categorias
$sql = "SELECT * FROM categoria ORDER BY categoria";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Erro na consulta: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Configurações</title>
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
    <div class="card p-2 container mt-4">
        <h2>Configurações</h2>
        
        
                
        <!-- Conteúdo das Abas -->
        <div class="tab-content" id="configTabsContent">
            <!-- Aba de Categorias -->
            <div class="tab-pane fade show active" id="categorias" role="tabpanel">
                <div class="p-3">
                    <?php if (isset($erro)): ?>
                        <div class="alert alert-danger"><?php echo $erro; ?></div>
                    <?php endif; ?>

                    <!-- Formulário para adicionar categoria -->
                    <div class="d-flex">    
                        <form method="POST" action="configuracoes.php" class="mb-3 d-flex">
                            <input type="hidden" name="acao" value="adicionar">
                            <div class="row g-3 align-items-center">
                                <div class="col-auto">
                                    <input type="text" class="form-control" name="categoria" 
                                           placeholder="Nova categoria" required>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary">Adicionar</button>
                                </div>
                            </div>
                        </form>
                        <div class="col text-end">
                            <a href="../Telas/Menu.php"><button class="btn btn-danger">Voltar</button></a>
                        </div>
                    </div>        
                    <!-- Tabela de categorias -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Categoria</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($categoria = mysqli_fetch_array($result)): ?>
                                    <tr id="row-<?php echo $categoria['codigo']; ?>">
                                         <!--Codigo-->
                                        <td><?php echo ($categoria['codigo']); ?></td>
                                        <!--FIM Codigo-->
                                        <!--categoria-->
                                        <td>
                                            <div class="view-mode">
                                                <span class="categoria-texto">
                                                    <?php echo ($categoria['categoria']); ?>
                                                </span>
                                            </div>
                                            
                                            <div class="edit-mode" style="display: none;">
                                                <form method="POST" action="configuracoes.php">
                                                    <input type="hidden" name="acao" value="editar">
                                                    <input type="hidden" name="codigo" 
                                                           value="<?php echo $categoria['codigo']; ?>">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="categoria" 
                                                               value="<?php echo ($categoria['categoria']); ?>">
                                                        <button type="submit" class="btn btn-success btn-sm">Salvar</button>
                                                        <button type="button" class="btn btn-danger btn-sm" 
                                                                onclick="cancelarEdicao(<?php echo $categoria['codigo']; ?>)">
                                                            Cancelar
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </td>
                                        <!--FIM categoria-->
                                        <!--acoes-->
                                        <td>
                                            <button class="btn btn-sm btn-warning" 
                                                    onclick="habilitarEdicao(<?php echo $categoria['codigo']; ?>)">
                                                Editar
                                            </button>
                                            <form method="POST" action="configuracoes.php" class="d-inline">
                                                <input type="hidden" name="acao" value="excluir">
                                                <input type="hidden" name="codigo" 
                                                       value="<?php echo $categoria['codigo']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('Tem certeza que deseja excluir esta categoria?')">
                                                    Excluir
                                                </button>
                                            </form>
                                        </td>
                                        <!--FIM acoes-->
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Categoria -->
    <div class="modal fade" id="editCategoriaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <input type="hidden" id="edit_codigo" name="codigo">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Categoria</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_categoria" class="form-label">Nome da Categoria</label>
                            <input type="text" class="form-control" id="edit_categoria" name="categoria" required>
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
    <script>
        function habilitarEdicao(codigo) {
            const row = document.getElementById('row-' + codigo);
            const viewMode = row.querySelector('.view-mode');
            const editMode = row.querySelector('.edit-mode');
            
            viewMode.style.display = 'none';
            editMode.style.display = 'block';
        }

        function cancelarEdicao(codigo) {
            const row = document.getElementById('row-' + codigo);
            const viewMode = row.querySelector('.view-mode');
            const editMode = row.querySelector('.edit-mode');
            
            viewMode.style.display = 'block';
            editMode.style.display = 'none';
        }
    </script>
 <!--javascript do botao-->
 <script src="../js/BotaoDark.js"></script>
</body>
</html> 