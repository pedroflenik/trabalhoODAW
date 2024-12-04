<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Biblioteca - Emprestimos</title>

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


  if (isset($_SESSION['emprestimos']) && !empty($_SESSION['emprestimos'])) {
    $emprestimos = $_SESSION['emprestimos'];
  } else {
    $emprestimos = [];
  }
  
?>
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

    <h4>Cadastrar emprestimo</h4>
    <?php if (!empty($msg) && $codWhere == 1) : ?>
      <div 
          class="alert <?php echo ($msgcod == 1) ? 'alert-danger' : 'alert-success'; ?> d-inline-block" 
          role="alert" 
          style="max-width: fit-content; padding: 10px;">
          <?php echo $msg; ?>
      </div>
      <br>
    <?php endif; ?>
        <form action="../php/cadastroEmprestimo.php" method='post'>
            <label for="">ID cliente:</label><br>
            <input type="number" name="idCliente"><br>
            <label for="">ID exemplar:</label><br>
            <input type="text" name="idExemplar"><br>
            <label ho for="">Tempo emprestimo (semanas):</label><br> <!-- Tempo max == 2 semanas  -->
            <input placeholder="MAX 4 semanas" type="number:" name="tempoInicial"><br>
            <input style="margin-top:5px;" class="btn btn-primary" type="submit" value="Cadastrar emprestimo">  
        </form>
    <br>
    <hr>
    <br>
    

    <h4>Consultar Emprestimos</h4>
    <?php if (!empty($msg) && $codWhere == 2) : ?>
      <div 
          class="alert <?php echo ($msgcod == 1) ? 'alert-danger' : 'alert-success'; ?> d-inline-block" 
          role="alert" 
          style="max-width: fit-content; padding: 10px;">
          <?php echo $msg; ?>
      </div>
      <br>
    <?php endif; ?>
    <form action="../php/consultaEmprestimos.php" method="post">
        <label for="">ID exemplar:</label><br>
        <input type="number" name="idExemplar"><br>
        <label for="">ID cliente</label><br>
        <input type="number" name="idCliente"><br>
        <input name="pendente" style="margin-right: 5px;" type="checkbox"><label for="">Pendente?</label><br>
        <input style="margin-top:5px;" class="btn btn-primary" type="submit" value="Buscar">  
    </form>


    <?php if (!empty($emprestimos)) : ?>
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
                      <th>Ações</th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach ($emprestimos as $emprestimos) : ?>
                      <tr>
                          <td><?php echo htmlspecialchars($emprestimos['idExemplar']); ?></td>
                          <td><?php echo htmlspecialchars($emprestimos['idCliente']); ?></td>
                          <td><?php echo htmlspecialchars($emprestimos['dataEmprestimo']); ?></td>
                          <td><?php echo htmlspecialchars($emprestimos['dataEsperadaDevolucao']); ?></td>
                          <td><?php echo htmlspecialchars($emprestimos['dataDevolucao']); ?></td>
                          <td><?php echo htmlspecialchars($emprestimos['numRenovacoes']); ?></td>
                          <td><?php 
                        if($emprestimos['status'] == 'E'){
                            echo "Emprestado";
                        }elseif($emprestimos['status'] == 'D'){
                            echo "Devolvido";
                        }else{
                            echo "Atrasado";
                        }
                          
                          ?></td>
                        <td>
                        <?php
                            if ($emprestimos['status'] == 'D') {
                                echo '<button type="button" class="btn btn-info" href="javascript:void(0);" onclick="" disabled>Renovar</button>';
                            } else {

                                $dataEmprestimo = $emprestimos['dataEmprestimo']; 
                                echo '<button type="button" class="btn btn-info" href="javascript:void(0);" onclick="renovaEmprestimo('
                                    .$emprestimos['idExemplar'].',' 
                                    .$emprestimos['idCliente'].', \'' 
                                    .$dataEmprestimo.'\','.$emprestimos['numRenovacoes'].')">Renovar</button>';
                            }
                        ?>
                        | 
                    
                        <?php 
                        if($emprestimos['status'] == 'D'){
                            echo '<button disabled type="button" class="btn btn-success" href="javascript:void(0);" onclick="entregarEmprestimo('
                            .$emprestimos['idExemplar'].',' 
                            .$emprestimos['idCliente'].', \'' 
                            .$emprestimos['dataEmprestimo'].'\','.$emprestimos['numRenovacoes'].')">Entregar</button>';
                       
                        }else{
                            echo '<button type="button" class="btn btn-success" href="javascript:void(0);" onclick="entregarEmprestimo('
                            .$emprestimos['idExemplar'].',' 
                            .$emprestimos['idCliente'].', \'' 
                            .$emprestimos['dataEmprestimo'].'\','.$emprestimos['numRenovacoes'].')">Entregar</button>';
                       
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