<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Biblioteca - Gerenciar Livros</title>

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
<!-- MODAL cadastro de exemplar -->
<div class="modal fade" id="cadastroExemplarModal" tabindex="-1" aria-labelledby="cadastroExemplarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cadastroExemplarModalLabel">Cadastrar Exemplar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="cadastroExemplarForm" action="../php/cadastrarExemplar.php" method="post">
                    <input type="hidden" name="isbn" id="isbnExemplar">
                    <div class="mb-3">
                        <label for="cadQuantExemplares" class="form-label">Quantidade de exemplares:</label>
                        <input type="number" id="cadQuantExemplares" name="numExemplares" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODAL edição de livros -->
<div class="modal fade" id="edicaoDeLivroModal" tabindex="-1" aria-labelledby="edicaoDeLivroModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edicaoDeLivroModal">Editar livros Exemplar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="edicaoDeLivroFrom" action="../php/editarLivro.php" method="post">
                    <input type="hidden" name="isbn" id="isbnEditarExemplar">
                    <div class="mb-3">
                        <label for="tituloLivroId" class="form-label">Título:</label>
                        <input type="text" id="tituloLivroId" name="titulo" class="form-control" required>
                        <label for="autorLivroId" class="form-label">Autor:</label>
                        <input type="text" id="autorLivroId" name="autor" class="form-control" required>
                        <label for="editoraLivroId" class="form-label">Editora:</label>
                        <input type="text" id="editoraLivroId" name="editora" class="form-control" required>
                        <label for="edicaoLivroId" class="form-label">Edição:</label>
                        <input type="number" id="edicaoLivroId" name="edicao" class="form-control" required>
                        <br>
                        <div class="select-container">
                          <?php
                            $link = mysqli_connect("localhost", "root", "udesc", "biblioteca");
                            $query = "SELECT * FROM generos";
                            $generos = mysqli_query($link, $query);
                  
                            if ($generos) {
                              echo '<div class="select-container">';
                              echo '<select id="selectGeneroEditLivroModal" class="form-select" aria-label="Default select example" name="genero">';
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
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar Mudanças</button>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="navVbar">
      <h3 class="titulo" style="color:white"><img src="../assets/livros.png" alt="logo livros">    Biblioteca</h3>
      <a href="gerenciarUsuarios.php">Gerenciar Usuários</a>
      <a href="multas.php">Multas</a>
      <a href="gerenciarLivros.php">Gerenciar Livros</a>
      <a href="emprestimos.php">Novo Emprestimo</a>
      <a href="reservas.php">Nova Reserva</a>

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
      <h4>Consulta de Livros</h4>
      <?php if (!empty($msg) && $codWhere == 2) : ?>
      <div 
          class="alert <?php echo ($msgcod == 1) ? 'alert-danger' : 'alert-success'; ?> d-inline-block" 
          role="alert" 
          style="max-width: fit-content; padding: 10px;">
          <?php echo $msg; ?>
      </div>
      <br>
    <?php endif; ?>
      <form action="../php/consultarLivros.php" method="post">
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
      </form>
      <?php if (!empty($livros)) : ?>
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
                          <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#edicaoDeLivroModal" 
                            onclick="openModalEdicaoLivro(
                              <?php echo $livros['isbn']; ?>, 
                              '<?php echo addslashes($livros['titulo']); ?>', 
                              '<?php echo addslashes($livros['autor']); ?>', 
                              '<?php echo addslashes($livros['editora']); ?>', 
                              '<?php echo addslashes($livros['edicao']); ?>', 
                              '<?php echo addslashes($livros['idGenero']); ?>', 
                            )">
                            Editar
                          </button>
                          |<button type="button" class="btn btn-danger" href="javascript:void(0);" onclick="confirmarExluirLivro(<?php echo $livros['isbn']; ?>, '<?php echo addslashes($livros['titulo']); ?>', '<?php echo addslashes($livros['editora']); ?>')">Excluir</button>
                          | <button  data-bs-toggle="modal" data-bs-target="#cadastroExemplarModal" onclick="openModalCadatrarExemplar('<?php echo $livros['isbn']; ?>')" type="button" class="btn btn-success" href="javascript:void(0);">Criar Exemplar</button>
                        
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
      <h4>Consulta de Exemplares</h4>
      <?php if (!empty($msg) && $codWhere == 3) : ?>
      <div 
          class="alert <?php echo ($msgcod == 1) ? 'alert-danger' : 'alert-success'; ?> d-inline-block" 
          role="alert" 
          style="max-width: fit-content; padding: 10px;">
          <?php echo $msg; ?>
      </div>
      <br>
    <?php endif; ?>
      <form action="../php/consultarExemplares.php" method="post">
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
      </form>
      <?php if (!empty($exemplares)) : ?>
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
                        
                          <button type="button" class="btn btn-danger" href="javascript:void(0);" onclick="confirmaExcluirExemplar(<?php echo $exemplares['idExemplar']; ?>,<?php echo $exemplares['isbn']; ?>, '<?php echo addslashes($exemplares['titulo']); ?>', '<?php echo addslashes($exemplares['editora']); ?>')">Excluir</button>
                        
                        </td>
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