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



    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['usuario']) && isset($_POST['senha'])) {
            $conexao = new Conexao();
            $usuario = $_POST['usuario'];
            $senha = $_POST['senha'];
            $consultar = new Consultar();
    
            $resultado = "";
            try{

            
            // Verifica o usuário e a senha
            $resultado = $consultar->consultarUsuarioIndividual($conexao, $usuario, $senha);
            
            if ($resultado) {
                // Armazena o nome do usuário na sessão
                $_SESSION['usuario'] = $usuario;
    
                // Redireciona para a página do menu
                header('Location: Menu.php');
                exit();
            }
            }catch(Except $erro){
                echo $erro;
            }
        }
    }
?>
<html lang="en" data-bs-theme="light">
<head>
    <script src="/docs/5.3/assets/js/color-modes.js"></script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="generator" content="Hugo 0.122.0">
    <title>Signin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../Css/BotaoDark.css">
    <style>
        body{
        background-image: url(../img/Reciclagem.jpeg);
        }
    </style>
    
</head>

<body class="d-flex align-items-center py-4 bg-body-tertiary" cz-shortcut-listen="true">
    <?php include('../Templates/BotaoDark.php');?>

    <!--caixa de login-->
    <main class="card p-4 form-signin w-100 m-auto">
        <form method="POST">
            
            <h1 class="h3 mb-4 fw-normal">Faça login</h1>

            <div class="form-floating">
                <input name="usuario" type="text" class="form-control" id="floatingInput" placeholder="Username" required>
                <label for="validationDefaultUsername floatingInput">Usuario</label>
            </div>
            <div class="form-floating">
                <input name="senha" type="password" class="form-control" id="floatingPassword" placeholder="Password" required>
                <label for="validationDefaultUsername floatingPassword">Senha</label>
            </div>

            <div class="form-check text-start my-3" style="--bs-gap: .25rem 1rem;">
                <div class="d-flex container text-center">
                      <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
                      <div class="col-7">
                        <label class="form-check-label" for="flexCheckDefault">
                            lembre de mim
                        </label>
                      </div>
                      <div class="col">
                        <div>
                            <a href="RedefinirSenha.php" class="txt1">
                                Esqueceu?
                            </a>
                        </div>
                      </div>
                  </div>                
            </div>
            <button class="btn btn-primary w-100 py-2">Entrar
                
            </button>
        </form>
        
        <p class="mt-5 mb-3 text-body-secondary">© 2017–2024</p>
    </main>
    <!--fim caixa d login-->


    <!--javascript do botao-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script src="../js/BotaoDark.js"></script>
  </body>

</html>