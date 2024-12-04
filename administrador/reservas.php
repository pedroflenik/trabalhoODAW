<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Biblioteca - Administrador</title>

    <link rel="stylesheet" href="../css/adm.css">
    <!--Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

        <script>
 
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipElements = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltipElements.forEach(function (element) {
            new bootstrap.Tooltip(element);
            });
        });
        </script>
    <link rel="icon" href="../assets/livros.png">
  </head>

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

if (isset($_SESSION['reservas']) && !empty($_SESSION['reservas'])) {
    $reservas = $_SESSION['reservas'];
  } else {
    $reservas = [];
  }
?>
<script src="../js/administrador.js" type="text/javascript"></script>

<body>

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

        <h4>Cadastrar Reserva</h4>
        <?php if (!empty($msg) && $codWhere == 1) : ?>
      <div 
          class="alert <?php echo ($msgcod == 1) ? 'alert-danger' : 'alert-success'; ?> d-inline-block" 
          role="alert" 
          style="max-width: fit-content; padding: 10px;">
          <?php echo $msg; ?>
      </div>
      <br>
    <?php endif; ?>
        <form action="../php/cadastrarReserva.php" method="post">
            <label for="">ID Cliente:</label><br>
            <input type="number" name="idCliente" name="idCliente "><br>
            <label for="">ID Exemplar:</label><br>
            <input type="number" name="idExemplar"><br>
            <label for="">Data:</label><br>
            <input type="date" name="dataReserva"> <img src="../assets/questionMark.svg" alt="ponto interrogacao" style="width:25px;height:25px" class="img-fluid" data-bs-toggle="tooltip" data-bs-placement="right" title="Essa data marca o inicio do periodo de 7 dias para emprestar o livro.">
            <br><input type="submit" value="Cadastrar Reserva" style="margin-top: 5px;" class="btn btn-primary">
        </form>
        <br>
        <hr>
        <br>
        <h4>Consultar Reservas</h4>
        <?php if (!empty($msg) && $codWhere == 2) : ?>
        <div 
          class="alert <?php echo ($msgcod == 1) ? 'alert-danger' : 'alert-success'; ?> d-inline-block" 
          role="alert" 
          style="max-width: fit-content; padding: 10px;">
          <?php echo $msg; ?>
        </div>
        <br>
        <?php endif; ?>
        <form action="../php/consultaReservas.php" method="post">
            <label for="">ID exemplar:</label><br>
            <input type="number" name="idExemplar"><br>
            <label for="">ID cliente</label><br>
            <input type="number" name="idCliente"><br>
            <input name="pendente" style="margin-right: 5px;" type="checkbox"><label for="">Pendente?</label><br>
            <input style="margin-top:5px;" class="btn btn-primary" type="submit" value="Buscar">  
        </form>
         
    <?php if (!empty($reservas)) : ?>
      <table class="table">
              <thead>
                  <tr>
                      <th>ID exemplar</th>
                      <th>ID cliente</th>
                      <th>Data reserva</th>
                      <th>Data final reserva</th>
                      <th>Status</th>
                      <th>Ações</th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach ($reservas as $reservas) : ?>
                      <tr>
                          <td><?php echo htmlspecialchars($reservas['idExemplar']); ?></td>
                          <td><?php echo htmlspecialchars($reservas['idCliente']); ?></td>
                          <td><?php echo htmlspecialchars($reservas['dataReserva']); ?></td>
                          <td><?php echo htmlspecialchars($reservas['dataFinalReserva']); ?></td>
                          <td><?php 
                        if($reservas['status'] == 'P'){
                            echo "Pendente";
                        }elseif($reservas['status'] == 'E'){
                            echo "Entregue";
                        }else{
                            echo "Expirado";
                        }
                          
                          ?></td>
                        <td>
                        
                        <?php 
                        if($reservas['status'] == 'E' || $reservas['status'] == 'X'){
                            echo '<button disabled type="button" class="btn btn-success" href="javascript:void(0);" onclick="entregarReserva('
                            .$reservas['idExemplar'].',' 
                            .$reservas['idCliente'].', \'' 
                            .$reservas['dataReserva'].'\')">Entregar</button>';
                       
                        }else{
                            echo '<button type="button" class="btn btn-success" href="javascript:void(0);" onclick="entregarReserva('
                            .$reservas['idExemplar'].',' 
                            .$reservas['idCliente'].', \'' 
                            .$reservas['dataReserva'].'\')">Entregar</button>';
                       
                        }
                        ?>

                        </td>

                      </tr>
                  <?php endforeach; ?>
              </tbody>
          </table>
      <?php else : ?>
          <p>Nenhum emprestimo encontrado.</p>
      <?php endif; ?>   
    </div>
    </body>
</html>