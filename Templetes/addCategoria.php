<?php
require_once('../DAO/Conexao.php');
require_once('../DAO/Inserir.php');

use PHP\Modelo\DAO\Conexao;
use PHP\Modelo\DAO\Inserir;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conexao = new Conexao();
    $conn = $conexao->conectar();
    
    if ($conn) {
        $categoria = mysqli_real_escape_string($conn, $_POST['nomeCategoria']);
        
        // Gera o próximo código disponível
        $sql = "SELECT MAX(codigo) as max_codigo FROM Categoria";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $novo_codigo = ($row['max_codigo'] ?? 0) + 1;
        
        // Insere a nova categoria
        $sql = "INSERT INTO Categoria (codigo, categoria) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "is", $novo_codigo, $categoria);
        
        if (mysqli_stmt_execute($stmt)) {
            header("Location: addCategoria.php?status=success");
        } else {
            header("Location: addCategoria.php?status=error");
        }
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Categorias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col">
                <h1>Gerenciar Categorias</h1>
            </div>
        </div>
        
        <!-- Formulário para adicionar categoria -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Adicionar Nova Categoria</h5>
                    </div>
                    <div class="card-body">
                        <form action="addCategoria.php" method="POST">
                            <div class="mb-3">
                                <label for="nomeCategoria" class="form-label">Nome da Categoria</label>
                                <input type="text" class="form-control" id="nomeCategoria" name="nomeCategoria" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Adicionar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Categorias -->
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Categorias Existentes</h5>
                    </div>
                    <div class="card-body">
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
                                    <?php
                                    $conexao = new Conexao();
                                    $conn = $conexao->conectar();
                                    
                                    if ($conn) {
                                        $sql = "SELECT * FROM Categoria ORDER BY codigo";
                                        $result = mysqli_query($conn, $sql);
                                        
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>";
                                            echo "<td>{$row['codigo']}</td>";
                                            echo "<td>{$row['categoria']}</td>";
                                            echo "<td>
                                                    <button class='btn btn-sm btn-primary' onclick='editarCategoria({$row['codigo']}, \"{$row['categoria']}\")'>
                                                        <i class='bi bi-pencil'></i>
                                                    </button>
                                                    <button class='btn btn-sm btn-danger' onclick='excluirCategoria({$row['codigo']})'>
                                                        <i class='bi bi-trash'></i>
                                                    </button>
                                                  </td>";
                                            echo "</tr>";
                                        }
                                        
                                        mysqli_close($conn);
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mensagens de feedback -->
        <?php if (isset($_GET['status'])): ?>
            <div class="alert alert-<?php echo $_GET['status'] == 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show mt-3">
                <?php echo $_GET['status'] == 'success' ? 'Categoria adicionada com sucesso!' : 'Erro ao adicionar categoria. Tente novamente.'; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal de Edição -->
    <div class="modal fade" id="editarModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Categoria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="editar_categoria.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" id="editCodigo" name="codigo">
                        <div class="mb-3">
                            <label for="editCategoria" class="form-label">Nome da Categoria</label>
                            <input type="text" class="form-control" id="editCategoria" name="categoria" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editarCategoria(codigo, categoria) {
            document.getElementById('editCodigo').value = codigo;
            document.getElementById('editCategoria').value = categoria;
            new bootstrap.Modal(document.getElementById('editarModal')).show();
        }

        function excluirCategoria(codigo) {
            if (confirm('Tem certeza que deseja excluir esta categoria?')) {
                window.location.href = `excluir_categoria.php?codigo=${codigo}`;
            }
        }
    </script>
</body>
</html>