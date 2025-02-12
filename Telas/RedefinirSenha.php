<?php
namespace PHP\Modelo\Telas;

require_once('..\DAO\Consultar.php');
require_once('..\DAO\Conexao.php');
use PHP\Modelo\DAO\Consultar;
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['usuario']) && isset($_POST['nova_senha'])) {
        if ($_POST['nova_senha'] === $_POST['confirmar_senha']) {
            // atualiza a senha
        
            $conexao = new Conexao();
            $usuario = $_POST['usuario'];
            $novaSenha = $_POST['nova_senha'];
            $consultar = new Consultar();

            // Verifica se o usuário existe e atualiza a senha
            if ($consultar->atualizarSenha($conexao, $usuario, $novaSenha)) {
                $mensagem = "Senha atualizada com sucesso!";
                header('refresh:2;url=Login.php'); // Redireciona após 2 segundos
            } else {
                $erro = "Usuário não encontrado ou erro ao atualizar senha.";
            }
        } else {
            $erro = "As senhas não coincidem!";
        }
    }
}
?>

<html lang="pt-br" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Redefinir Senha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../Css/BotaoDark.css">
</head>

<body class="d-flex align-items-center py-4 bg-body-tertiary">
    <?php include('../Templetes/BotaoDark.php');?>

    <main class="form-signin w-100 m-auto">
        <form method="POST">
            <h1 class="h3 mb-3 fw-normal">Redefinir Senha</h1>

            <?php if (isset($mensagem)): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $mensagem; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($erro)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $erro; ?>
                </div>
            <?php endif; ?>

            <div class="form-floating mb-3">
                <input name="usuario" type="text" class="form-control" id="floatingInput" placeholder="Username" required>
                <label for="floatingInput">Usuário</label>
            </div>

            <div class="form-floating mb-3">
                <input name="nova_senha" type="password" class="form-control" id="novaSenha" required>
                <label for="novaSenha">Nova senha</label>
            </div>

            <div class="form-floating mb-3">
    <input name="confirmar_senha" type="password" class="form-control" id="confirmarSenha" required>
                <label for="confirmarSenha">Confirmar nova senha</label>
            </div>

            <button class="btn btn-primary w-100 py-2">Alterar senha</button>
            
            <div class="mt-3 text-center">
                <a href="Login.php">Voltar para o login</a>
            </div>
        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/BotaoDark.js"></script>
</body>
</html> 