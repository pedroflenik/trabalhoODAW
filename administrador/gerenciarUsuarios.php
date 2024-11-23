<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Biblioteca - Cadastro</title>

    <link rel="stylesheet" href="../css/adm.css">
    <!--Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<script src="../js/administrador.js" type="text/javascript"></script>
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

<body>

    <div class="navVbar">
      <h3 class="titulo">TITULO</h3>
      <a href="gerenciarUsuarios.php">Gerenciar usuários</a>
      <a href="">Gerenciar Livros</a>
      <a href="">Novo emprestimo</a>
      <a href="">Nova reserva</a>

      <button class="sair-btn" onclick="sairPag()">SAIR</button>
    </div>
    <div class="main">
        <div>
            <h4>Cadastrar Administrador</h4>
            <form action="../php/cadastrarAdm.php" method="post">
            <?php if (!empty($msg)) : ?>
                <div 
                    class="alert <?php echo ($msgcod == 1) ? 'alert-danger' : 'alert-success'; ?> d-inline-block" 
                    role="alert" 
                    style="max-width: fit-content; padding: 10px;">
                    <?php echo $msg; ?>
                </div>
                <br>
            <?php endif; ?>
            <label for="">Nome:</label><br>
            <input type="text" name="nome"><br>
            <label for="" >CPF:</label><br>
            <input type="text" name="cpf"><br>
            <label for="">Senha:</label><br>
            <input type="password" name="senha"><br>
            <label for="" >Confirmar senha:</label><br>
            <input type="password" name="conSenha">
            <input value="Mostrar Senha"type="button" id="idBotaoMostraSenha" onclick="mostraSenha()"class="btn btn-primary mostrarSenha"><br>
            <input class="btn btn-primary" type="submit" value="Cadastrar">
            </form>
        </div>
        <br>
        <hr>
        <br>
        <div>
            <h4>Consulta de Adimistradores</h4>
            <form action="">
            <form action="">
            <label for="">Nome:</label>
            <input type="text">
            <label for="">CPF:</label>
            <input type="text">
            <input class="btn btn-primary" type="submit" value="Buscar">
            </form>
            <table>

            </table>
        </div>
        <br>
        <hr>
        <br>
        <div>
            <h4>Consulta de clientes:</h4>
            <form action="">
            <form action="">
            <label for="">Nome:</label>
            <input type="text">
            <label for="">CPF:</label>           
            <input type="text">
            <label for="">Multado?</label>
            <input type="checkbox"><br>
            <input class="btn btn-primary" type="submit" value="Buscar"> 
            </form>
            <table></table>
        </div>
        <br>
    </div>
       
    </body>
</html>