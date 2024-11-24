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


<!-- MODAL cadastro genero -->
<div class="modal fade" id="cadastroGeneroModal" tabindex="-1" aria-labelledby="cadastroGeneroModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cadastroGeneroModalLabel">Cadastrar Genero</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="cadastroGeneroForm" action="../php/cadastroGenero.php" method="post">
                    <input type="hidden" name="id" id="adminId">
                    <div class="mb-3">
                        <label for="cadNomeGenero" class="form-label">Nome:</label>
                        <input type="text" id="cadNomeGenero" name="nomeGenero" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
</div>


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
    <?php if (!empty($msg) && $codWhere == 1) : ?>
      <div 
          class="alert <?php echo ($msgcod == 1) ? 'alert-danger' : 'alert-success'; ?> d-inline-block" 
          role="alert" 
          style="max-width: fit-content; padding: 10px;">
          <?php echo $msg; ?>
      </div>
      <br>
    <?php endif; ?>
    <form action="../php/cadastrarLivro.php" method="post">
      <label for="">ISBN:</label><br>
      <input type="text" name="isbn"><br>
      
      <label for="">Titulo:</label><br>
      <input type="text" name="titulo"><br>
      
      <label for="">Autor:</label><br>
      <input type="text" name="autor"><br>
      
      <label for="">Editora:</label><br>
      <input type="text" name="editora"><br>
      
      <label for="">Edição:</label><br>
      <input type="number" name="edicao"><br>
      
      <label for="">Genero:</label><br>
      
      <div class="select-container">
          
        <?php
          $link = mysqli_connect("localhost", "root", "udesc", "biblioteca");
          $query = "SELECT * FROM generos";
          $generos = mysqli_query($link, $query);

          if ($generos) {
            echo '<div class="select-container">';
            echo '<select class="form-select" aria-label="Default select example" name="genero">';
            echo '<option value="" selected>Selecione o genero</option>';

            while ($genero = mysqli_fetch_assoc($generos)) {
                echo '<option value="' . $genero['idGenero'] . '">' . $genero['genero'] . '</option>';
            }

            echo '</select>';


            echo '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cadastroGeneroModal">+</button>';
            echo '</div><br>';
          } else {
            echo "Error fetching genres: " . mysqli_error($link);
          }

          mysqli_close($link);
          ?>
      </div><br>

      <input class="btn btn-primary" type="submit" value="Cadastrar">
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