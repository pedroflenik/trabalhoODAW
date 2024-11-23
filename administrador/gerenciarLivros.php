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
  $codWhere = $_SESSION['codWhere'];
  // codWhere diz de onde vem a mesagem
  unset($_SESSION['msg']);
} else {
  $msg = "";
}

?>

<body>

    <div class="navVbar">
      <h3 class="titulo">TITULO</h3>
      <a href="gerenciarUsuarios.php">Gerenciar Usuários</a>
      <a href="gerenciarLivros.php">Gerenciar Livros</a>
      <a href="">Novo emprestimo</a>
      <a href="">Nova reserva</a>

      <button class="sair-btn" onclick="sairPag()">SAIR</button>
    </div>
    <div class="main">
        <h4>Cadastrar Livro</h4>
        <form action="">

        </form>
        <br>
        <hr>
        <br>
        <h4>Cadastrar Exemplar</h4>
        <form action="">
            
        </form>
        <br>
        <hr>
        <br>
        <h4>Consuta de Livros</h4>
        <form action="">

        </form>
        <table>

        </table>
        <br>
        <hr>
        <br>
        <h4>Consulta de Exemplares</h4>
        <form action="">

        </form>
        <table>

        </table>
    </div>
       
    </body>
</html>