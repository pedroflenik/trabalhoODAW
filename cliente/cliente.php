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

if (isset($_SESSION['emprestimosCliente']) && !empty($_SESSION['emprestimosCliente'])) {
    $emprestimosCliente = $_SESSION['emprestimosCliente'];
} else {
    $emprestimosCliente = [];
}


if (isset($_SESSION['reservasCliente']) && !empty($_SESSION['reservasCliente'])) {
  $reservasCliente = $_SESSION['reservasCliente'];
} else {
  $reservasCliente = [];
}



?>

<body>

    <div class="navVbar">
    <h3 class="titulo" style="color:white"><img src="../assets/livros.png" alt="logo livros">    Biblioteca</h3>
      <a href="cliente.php">Emprestimos e Multas</a>
      <a href="clienteLivros.php">Visualizar Livros</a>
      <button class="sair-btn" onclick="sairPag()">SAIR</button>
    </div>

    <div class="main">

    <h4>Bem vindo - <?php echo $_SESSION['nomeUsuarioAtual']?></h4>
    <h4>Multa: <?php echo $_SESSION['multaUsuarioAtual']?></h4>
    <br>
    <hr>
    <br>
    <h4>Seus Emprestimos:</h4>
    <?php if (!empty($msg) && $codWhere == 1) : ?>
      <div 
          class="alert <?php echo ($msgcod == 1) ? 'alert-danger' : 'alert-success'; ?> d-inline-block" 
          role="alert" 
          style="max-width: fit-content; padding: 10px;">
          <?php echo $msg; ?>
      </div>
      <br>
    <?php endif; ?>
    <form action="../php/buscarEmprestimosCliente.php?idCliente=<?php echo $_SESSION['idUsuarioAtual']?>" method="post">
      <input type="checkbox" name="pendente"><label for="">Pendente?</label><br><input class='btn btn-primary' type="submit" value="buscar">
    </form>

    <?php if (!empty($emprestimosCliente)) : ?>
      <table class="table">
              <thead>
                  <tr>
                      <th>ID exemplar</th>
                      <th>ID cliente</th>
                      <th>Data emprestimo</th>
                      <th>Data devolução esperada</th>
                      <th>Data devolução</th>
                      <th>Número renovacoes</th>
                      <th>Status</th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach ($emprestimosCliente as $emprestimosCliente) : ?>
                      <tr>
                          <td><?php echo htmlspecialchars($emprestimosCliente['idExemplar']); ?></td>
                          <td><?php echo htmlspecialchars($emprestimosCliente['idCliente']); ?></td>
                          <td><?php echo htmlspecialchars($emprestimosCliente['dataEmprestimo']); ?></td>
                          <td><?php echo htmlspecialchars($emprestimosCliente['dataEsperadaDevolucao']); ?></td>
                          <td><?php echo htmlspecialchars($emprestimosCliente['dataDevolucao']); ?></td>
                          <td><?php echo htmlspecialchars($emprestimosCliente['numRenovacoes']); ?></td>
                          <td><?php 
                        if($emprestimosCliente['status'] == 'E'){
                            echo "Emprestado";
                        }elseif($emprestimosCliente['status'] == 'D'){
                            echo "Devolvido";
                        }else{
                            echo "Atrasado";
                        }
                          
                          ?></td>
                        <td>
                      </tr>
                  <?php endforeach; ?>
              </tbody>
          </table>
      <?php else : ?>
          <p>Nenhum emprestimo encontrado.</p>
      <?php endif; ?>
 
    <br>
    <hr>
    <br>
    <h4>Suas Reservas:</h4>
    <form action="../php/buscarReservasCliente.php?idCliente=<?php echo $_SESSION['idUsuarioAtual']?>" method="post">
      <input type="checkbox" name="pendente"><label for="">Pendente?</label><br><input class='btn btn-primary' type="submit" value="buscar">
    </form>

    <?php if (!empty($reservasCliente)) : ?>
      <table class="table">
              <thead>
                  <tr>
                      <th>ID exemplar</th>
                      <th>ID cliente</th>
                      <th>Data reserva</th>
                      <th>Data final reserva</th>
                      <th>Status</th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach ($reservasCliente as $reservasCliente) : ?>
                      <tr>
                          <td><?php echo htmlspecialchars($reservasCliente['idExemplar']); ?></td>
                          <td><?php echo htmlspecialchars($reservasCliente['idCliente']); ?></td>
                          <td><?php echo htmlspecialchars($reservasCliente['dataReserva']); ?></td>
                          <td><?php echo htmlspecialchars($reservasCliente['dataFinalReserva']); ?></td>
                          <td><?php 
                        if($reservasCliente['status'] == 'P'){
                            echo "Pendente";
                        }elseif($reservasCliente['status'] == 'E'){
                            echo "Entregue";
                        }else{
                            echo "Expirado";
                        }
                          
                          ?></td>
                      </tr>
                  <?php endforeach; ?>
              </tbody>
          </table>
      <?php else : ?>
          <p>Nenhuma reserva encontrada.</p>
      <?php endif; ?>   

      </div>
    </body>
</html>