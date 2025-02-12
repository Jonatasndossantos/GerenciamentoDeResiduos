<?php
namespace PHP\Modelo\Telas;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('..\DAO\Consultar.php');
require_once('..\DAO\Conexao.php');
require_once('..\DAO\Inserir.php');

use PHP\Modelo\DAO\Consultar;
use PHP\Modelo\DAO\Conexao;
use PHP\Modelo\DAO\Inserir;

// Cria uma instância da classe Conexao
$conexao = new Conexao();
$consultar = new Consultar();

// Obtém a conexão mysqli
$conn = $conexao->conectar();

// Gerencia o checkbox "selecionar todos"
if (isset($_POST['select_all_checkbox'])) {
    $_SESSION['select_all'] = true;
} else {
    $_SESSION['select_all'] = false;
}

// Gerencia as ações de edição
if (isset($_GET['action']) && $_GET['action'] === 'edit') {
    $editando = true;
    $codigo_edit = $_GET['codigo'] ?? '';
    $dt_edit = $_GET['dt'] ?? '';
    $categoria_edit = $_GET['categoria'] ?? '';
    $peso_edit = $_GET['peso'] ?? '';
}

// Processa as ações
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'edit':
            // O modal será exibido automaticamente pelos parâmetros GET
            break;
        case 'delete':
            // Redireciona para o processamento de exclusão
            if (isset($_POST['codigos'])) {
                header('Location: ../DAO/Excluir.php');
                exit;
            }
            break;
    }
}
?>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="../Css/Table.css">
    <script src="../js/CheckBox.js"></script>
    
    
    
        <div class="">
            <!-- Cabeçalho com Pesquisa e Perfil -->
            <div class="p-3">    
                <div class="navbar navbar-expand-lg bg-body-tertiary container-fluid fixed-top">
                    <div class="container-lg" d-flex justify-content-between">
                        <div class="align-items-center ">
                            <h2 class="">Gerenciar Resíduos</h2>
                        </div>

                        <div class="d-flex gap-3">
                            <!-- Barra de Pesquisa -->
                            <div class="search-wrapper">
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control" 
                                           id="searchInput"
                                           name="search" 
                                           placeholder="Pesquisar..."
                                           value="<?php echo $_GET['search'] ?? ''; ?>">
                                    <button class="btn btn-outline-secondary" type="button" onclick="realizarPesquisa()">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Filtros -->
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-funnel"></i> Filtros
                                </button>
                                <div class="dropdown-menu dropdown-menu-end p-3" style="min-width: 250px;">
                                    <!-- Get para filtro e pesquisa-->
                                    <form method="GET" action="">
                                        <h6 class="dropdown-header">Filtrar por:</h6>
                                        <div class="mb-3">
                                            <div class="d-flex gap-2">
                                                <div>
                                                    <label class="form-label small">De</label>
                                                    <input type="date" 
                                                           name="data_inicio" 
                                                           class="form-control form-control-sm" 
                                                           value="<?php echo $_GET['data_inicio'] ?? ''; ?>">
                                                </div>
                                                <div>
                                                    <label class="form-label small">Até</label>
                                                    <input type="date" 
                                                           name="data_fim" 
                                                           class="form-control form-control-sm" 
                                                           value="<?php echo $_GET['data_fim'] ?? ''; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Categoria</label>
                                            <select name="categoria_filtro" class="form-select">
                                                <option value="">Todas as categorias</option>
                                                <?php
                                                    // Buscar categorias do banco de dados
                                                    $sql = "SELECT categoria FROM categoria ORDER BY categoria";
                                                    $result = mysqli_query($conn, $sql);
                                                    
                                                    if ($result) {
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            $cat = $row['categoria'];
                                                            $selected = (isset($_GET['categoria_filtro']) && $_GET['categoria_filtro'] === $cat) ? 'selected' : '';
                                                            echo "<option value='$cat' $selected>$cat</option>";
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm w-100 mb-2">Aplicar Filtros</button>
                                        <?php if (isset($_GET['data_inicio']) || isset($_GET['data_fim']) || isset($_GET['categoria_filtro'])): ?>
                                            <a href="<?php echo strtok($_SERVER["REQUEST_URI"], '?'); ?>" 
                                               class="btn btn-outline-secondary btn-sm w-100">Limpar Filtros</a>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>

                            <!-- Perfil -->
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">
                                  <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"></path>
                                </svg>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="../Telas/perfil.php"><i class="bi bi-person me-2"></i>Meu Perfil</a></li>
                                    <li><a class="dropdown-item" href="../Telas/configuracoes.php"><i class="bi bi-gear me-2"></i>Categorias</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i>Sair</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="container-xl mt-5">
                <!-- Tabela Responsiva -->
                <div class="card container-xl">                    
                    <!-- Ações em Lote e Adicionar -->
                    <div class="card-body d-flex justify-content-between align-items-center pt-4 ">
                        <div class="d-flex gap-2">
                            <button class="btn btn-danger d-flex" onclick="submitDelete()">
                                <i class="material-icons px-1 pt-1"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"></font></font>
                                </i> 
                                <h6 class="px-1 pt-1"> Excluir Selecionados</h6>
                            </button>
                            <span class="badge bg-secondary align-self-center" id="selectedCount">0 selecionados</span>
                        </div>
                        <a href="adicionar_residuo.php" class="btn btn-success d-flex">
                            <i class="material-icons px-1 pt-1"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"></font></font>
                            </i> <h6 class="px-1 pt-1">Adicionar Resíduo</h6> 
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>
                                            <form method="POST" action="">
                                                <input type="hidden" name="select_all" value="1">
                                                <span class="custom-checkbox">
                                                    <input type="checkbox" id="selectAll" name="select_all_checkbox" 
                                                           <?php echo (isset($_SESSION['select_all']) && $_SESSION['select_all']) ? 'checked' : ''; ?> 
                                                           onchange="this.form.submit()">
                                                    <label for="selectAll"></label>
                                                </span>
                                            </form>
                                        </th>
                                        <th scope="col"><h4>Usuario</h4></th>
                                        <th scope="col"><h4>Data</h4></th>
                                        <th scope="col"><h4>Categoria</h4></th>
                                        <th scope="col"><h4>Peso</h4></th>
                                        <th scope="col"><h4>Ações</h4></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    
                                    // Verifica se a conexão foi bem-sucedida
                                    if ($conn) {
                                        // Prepara a consulta SQL base
                                        $sql = "SELECT * FROM residuos WHERE 1=1";
                                        
                                        // Adiciona filtros se existirem
                                        if (isset($_GET['data_inicio']) && !empty($_GET['data_inicio'])) {
                                            $data_inicio = mysqli_real_escape_string($conn, $_GET['data_inicio']);
                                            $sql .= " AND DATE(dt) >= '$data_inicio'";
                                        }
                                        
                                        if (isset($_GET['data_fim']) && !empty($_GET['data_fim'])) {
                                            $data_fim = mysqli_real_escape_string($conn, $_GET['data_fim']);
                                            $sql .= " AND DATE(dt) <= '$data_fim'";
                                        }
                                        
                                        if (isset($_GET['categoria_filtro']) && !empty($_GET['categoria_filtro'])) {
                                            $categoria_filtro = mysqli_real_escape_string($conn, $_GET['categoria_filtro']);
                                            $sql .= " AND categoria = '$categoria_filtro'";
                                        }

                                        // Adicionar condição de pesquisa
                                        if (isset($_GET['search']) && !empty($_GET['search'])) {
                                            $search = mysqli_real_escape_string($conn, $_GET['search']);
                                            $sql .= " AND (categoria LIKE '%$search%' 
                                                      OR usuario LIKE '%$search%' 
                                                      OR dt LIKE '%$search%' 
                                                      OR peso LIKE '%$search%')";
                                        }

                                        // Executa a consulta
                                        $result = mysqli_query($conn, $sql);
                                    
                                        // Verifica se a consulta foi bem-sucedida
                                        if ($result) {
                                            $row = mysqli_num_rows($result);
                                            if ($row > 0) {
                                                while ($res = mysqli_fetch_assoc($result)) {
                                        ?>
                                                <tr>
                                                    <td>
                                                        <span class="custom-checkbox">
                                                            <input type="checkbox" class="checkbox-item" name="codigos[]" value="<?php echo $res['codigo']; ?>"
                                                                   <?php echo (isset($_SESSION['select_all']) && $_SESSION['select_all']) ? 'checked' : ''; ?>>
                                                        </span>
                                                    </td>
                                                    <td><?php echo $res['usuario']; ?></td>
                                                    <td><?php echo $res['dt']; ?>hrs</td>
                                                    <td><?php echo $res['categoria']; ?></td>
                                                    <td><?php echo $res['peso']; ?>kg</td>
                                                    <td>
                                                        <a href="editar_residuo.php?codigo=<?php echo $res['codigo']; ?>&dt=<?php echo $res['dt']; ?>&categoria=<?php echo $res['categoria']; ?>&peso=<?php echo $res['peso']; ?>" 
                                                            class="edit">
                                                            <i class="material-icons" data-toggle="tooltip" title="Edit" data-original-title="Edit"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"></font></font></i>
                                                        </a>
                                                        <a href="#" 
                                                           class="delete" 
                                                           onclick="submitDeleteSingle(<?php echo $res['codigo']; ?>)"
                                                           data-bs-toggle="modal" 
                                                           data-bs-target="#deleteEmployeeModal">
                                                            <i class="material-icons" data-toggle="tooltip" title="Delete" data-original-title="Delete"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"></font></font></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                        <?php
                                                }
                                            } else {
                                                echo "<tr><td colspan='7'>Nenhum registro encontrado.</td></tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='7'>Erro na consulta: " . mysqli_error($conn) . "</td></tr>";
                                        }
                                    
                                        // Fecha a conexão (opcional)
                                        mysqli_close($conn);
                                    } else {
                                        echo "<tr><td colspan='7'>Não foi possível conectar ao banco de dados.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>     
                        </div>
                    </div>
                </div>
            </div>
        </div>
    

<!-- Modal de Exclusão -->
<div id="deleteEmployeeModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="../DAO/Excluir.php" id="deleteForm">
                <div class="modal-header">						
                    <h4 class="modal-title">Excluir Resíduo(s)</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">					
                    <p>Tem certeza que deseja excluir estes registros?</p>
                    <p class="text-warning"><small>Esta ação não pode ser desfeita.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal" onclick="window.location.reload()">Cancelar</button>
                    <button type="submit" name="excluir" class="btn btn-danger" onclick="return handleDelete()">Excluir</button>
                </div>
                <input type="hidden" name="codigos" id="selectedCodigos" value="">
            </form>
        </div>
    </div>
</div>

<!-- Modal de Sucesso -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sucesso!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Registros excluídos com sucesso!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="window.location.reload()">OK</button>
            </div>
        </div>
    </div>
</div>

<script src="../js/Tabela.js"></script>




