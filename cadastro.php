<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Biblioteca - Cadastro</title>

    <link rel="stylesheet" href="css/cadastro.css">
    <!--Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<?php
session_start();

if (isset($_SESSION['msg'])) {
  $msg = $_SESSION['msg'];
  $msgcod = $_SESSION['msgCOD'];
  unset($_SESSION['msg']);
} else {
  $msg = "";
}

?>
<script src="js/cadastro.js" type="text/javascript"></script>
<body>
   
    <div class="divide colunaEsquerda">
        <div class="center">
          <h2>Nome da biblioteca</h2>
        </div>
    </div>

    <div class="divide colunaDireita">
        <div class="centered">

            <form action="php/cadastrarUsuario.php" method="post">
            <?php if (!empty($msg)) : ?>
                <div 
                    class="alert <?php echo ($msgcod == 1) ? 'alert-danger' : 'alert-success'; ?> d-inline-block" 
                    role="alert" 
                    style="max-width: fit-content; padding: 10px;">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>
                <h4>Cadastro:</h4>
                <label for="">Nome:</label><br>
                <input type="text" name="nome"><br>
                <label for="">CPF:</label><br>
                <input type="text" name="cpf"><br>
                <label for="">Senha:</label><br>
                <input id="idSenha" type="password" name="senha"><br>
                <label for="">Confirmar senha:</label><br>
                <input id="idConfirmarSenha"type="password" name="conSenha">
                <input value="Mostrar Senha"type="button" id="idBotaoMostraSenha" onclick="mostraSenha()"class="btn btn-primary mostrarSenha"><br>
            
                <input class="btn btn-primary" type="submit" value="Cadastrar"><br>
            </form>
            <a href="index.php"><button class="btn btn-primary">Voltar</button></a>
        </div>
    </div>

</body>
</html>