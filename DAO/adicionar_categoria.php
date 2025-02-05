<?php
// Inclua a classe de conexão
    require_once('Conexao.php');
    use PHP\Modelo\DAO\Conexao;

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém o nome da categoria do formulário
    $nomeCategoria = $_POST['nomeCategoria'];

    // Conecta ao banco de dados
    $conexao = new Conexao();
    $conn = $conexao->conectar();

    if ($conn) {
        // Prepara a query para inserir a categoria
        $sql = "INSERT INTO Categoria (nome) VALUES (?)";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            // Associa o parâmetro à query
            mysqli_stmt_bind_param($stmt, "s", $nomeCategoria);

            // Executa a query
            if (mysqli_stmt_execute($stmt)) {
                // Redireciona de volta ao formulário com mensagem de sucesso
                header("Location: adicionar_categoria.html?status=success");
                exit();
            } else {
                // Redireciona de volta ao formulário com mensagem de erro
                header("Location: adicionar_categoria.html?status=error");
                exit();
            }

            // Fecha o statement
            mysqli_stmt_close($stmt);
        } else {
            // Redireciona de volta ao formulário com mensagem de erro
            header("Location: adicionar_categoria.html?status=error");
            exit();
        }

        // Fecha a conexão
        mysqli_close($conn);
    } else {
        // Redireciona de volta ao formulário com mensagem de erro
        header("Location: adicionar_categoria.html?status=error");
        exit();
    }
} else {
    // Se o formulário não foi enviado, redireciona de volta
    header("Location: adicionar_categoria.html");
    exit();
}
?>