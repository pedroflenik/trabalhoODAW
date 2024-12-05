<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Biblioteca - Cliente</title>

    <link rel="stylesheet" href="../css/adm.css">
    <!--Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="icon" href="../assets/livros.png">

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


if (isset($_SESSION['livros']) && !empty($_SESSION['livros'])) {
  $livros = $_SESSION['livros'];
} else {
  $livros = [];
}

if (isset($_SESSION['exemplares']) && !empty($_SESSION['exemplares'])) {
    $exemplares = $_SESSION['exemplares'];
  } else {
    $exemplares = [];
  }
  
?>


<!-- MODAL criar reserva cliente -->
<div class="modal fade" id="cadastroResevaClienteModal" tabindex="-1" aria-labelledby="cadastroResevaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cadastroResevaModalLabel">Cadastrar Reserva</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="cadastroExemplarForm" action="../PHP/cadastrarReservaCliente.php" method="post">
                    <input type="hidden" name="idExemplar" id="idExemplarReserva">
                    <input type="hidden" name="idCliente" id="idClienteAtualReserva" value="<?php echo $_SESSION['idUsuarioAtual']?>">
                    <div class="mb-3">
                        <label for="cadQuantExemplares" class="form-label">Escolha uma data para a reserva:</label>
                        <input type="date" id="cadQuantExemplares" name="dataReserva" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
</div>


<body>

    <div class="navVbar">
    <h3 class="titulo" style="color:white"><img src="../assets/livros.png" alt="logo livros">    Biblioteca</h3>
      <a href="cliente.php">Emprestimos e Multas</a>
      <a href="clienteLivros.php">Visualizar Livros</a>
      <button class="sair-btn" onclick="sairPag()">SAIR</button>
    </div>

    <div class="main">

    <h4>Consulta de Livros</h4>
      <?php if (!empty($msg) && $codWhere == 1) : ?>
      <div 
          class="alert <?php echo ($msgcod == 1) ? 'alert-danger' : 'alert-success'; ?> d-inline-block" 
          role="alert" 
          style="max-width: fit-content; padding: 10px;">
          <?php echo $msg; ?>
      </div>
      <br>
    <?php endif; ?>
      <form action="../php/buscaLivrosCliente.php" method="post">
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


           
            echo '</div><br>';
          } else {
            echo "Error fetching genres: " . mysqli_error($link);
          }

          mysqli_close($link);
          ?>
      </div><br>
          
      <input class="btn btn-primary" type="submit" value="Buscar">
      <br>
      <br>
      </form>
      <?php if (!empty($msg) && $codWhere == 2) : ?>
      <div 
          class="alert <?php echo ($msgcod == 1) ? 'alert-danger' : 'alert-success'; ?> d-inline-block" 
          role="alert" 
          style="max-width: fit-content; padding: 10px;">
          <?php echo $msg; ?>
      </div>
      <br>
    <?php endif; ?>
      <?php if (!empty($livros)) : ?>
        <h4>Livros encontrados:</h4>
        <table class="table">
              <thead>
                  <tr>
                      <th>ISBN</th>
                      <th>Titulo</th>
                      <th>Autor</th>
                      <th>Editora</th>
                      <th>Edição</th>
                      <th>Genero</th>
                      <th>Ações</th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach ($livros as $livros) : ?>
                      <tr>
                          <td><?php echo htmlspecialchars($livros['isbn']); ?></td>
                          <td><?php echo htmlspecialchars($livros['titulo']); ?></td>
                          <td><?php echo htmlspecialchars($livros['autor']); ?></td>
                          <td><?php echo htmlspecialchars($livros['editora']); ?></td>
                          <td><?php echo htmlspecialchars($livros['edicao']); ?></td>
                          <td><?php echo htmlspecialchars($livros['genero']); ?></td>
                          <td>

                          <a class="btn btn-success" href="../php/buscaExemplaresCliente.php?isbn=<?php echo $livros['isbn']?>">Buscar Exemplares</a>
                        </td>
                      </tr>
                  <?php endforeach; ?>
              </tbody>
          </table>
      <?php else : ?>
          <p>Nenhum livro encontrado.</p>
      <?php endif; ?>
      <br>
      <hr>
      <br>
      <br>
      
      <?php if (!empty($exemplares)) : ?>
    <h4>Exemplares encontrados:</h4>
    <?php if (!empty($msg) && $codWhere == 1) : ?>
      <div 
          class="alert <?php echo ($msgcod == 1) ? 'alert-danger' : 'alert-success'; ?> d-inline-block" 
          role="alert" 
          style="max-width: fit-content; padding: 10px;">
          <?php echo $msg; ?>
      </div>
      <br>
    <?php endif; ?>
      <table class="table">
              <thead>
                  <tr>
                      <th>id</th>
                      <th>isbn</th>
                      <th>Titulo</th>
                      <th>Autor</th>
                      <th>Edição</th>
                      <th>Genero</th>
                      <th>Ações</th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach ($exemplares as $exemplares) : ?>
                      <tr>
                          <td><?php echo htmlspecialchars($exemplares['idExemplar']); ?></td>
                          <td><?php echo htmlspecialchars($exemplares['isbn']); ?></td>
                          <td><?php echo htmlspecialchars($exemplares['titulo']); ?></td>
                          <td><?php echo htmlspecialchars($exemplares['autor']); ?></td>
                          <td><?php echo htmlspecialchars($exemplares['edicao']); ?></td>
                          <td><?php echo htmlspecialchars($exemplares['genero']); ?></td>
                          <td>
                        
                          <button  data-bs-toggle="modal" data-bs-target="#cadastroResevaClienteModal"  class="btn btn-info" onclick="abrirCadastroReservaModalCliente('<?php echo $exemplares['idExemplar']; ?>')">Fazer Reserva</button>
                      </tr>
                  <?php endforeach; ?>
              </tbody>
          </table>
      <?php else : ?>
          <p>Nenhum exemplar encontrado.</p>
      <?php endif; ?>
    </div>
    </body>
</html>