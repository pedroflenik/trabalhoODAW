<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Biblioteca - Administrador</title>

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

if (isset($_SESSION['clienteMulta']) && !empty($_SESSION['clienteMulta'])) {
    $clienteMulta = $_SESSION['clienteMulta'];
} else {
    $clienteMulta = [];
}

?>



<body>

<div class="navVbar">
        <h3 class="titulo">TITULO</h3>
      <a href="gerenciarUsuarios.php">Gerenciar Usuários</a>
      <a href="multas.php">Multas</a>
      <a href="gerenciarLivros.php">Gerenciar Livros</a>
      <a href="emprestimos.php">Novo Emprestimo</a>
      <a href="reservas.php">Nova Reserva</a>

      <button class="sair-btn" onclick="sairPag()">SAIR</button>
    </div>

    <!-- pagarMultaModal MODAL -->
<div class="modal fade" id="pagamentoMultaModal" tabindex="-1" aria-labelledby="pagarMultaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAdminModalLabel">Efetuando Pagamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editAdminForm" action="../php/efetuarPagamentoMulta.php" method="post">
                    <input type="hidden" name="clienteIdMulta" id="clienteIdMultaId">
                    <input type="hidden" name="multa" id="multaId">
                    <div class="mb-3">
                        <label for="inputPagamento" class="form-label">Quantidade a pagar:</label>
                        <input type="number" id="inputPagamento" name="quantPagar" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Confirmar Pagamento</button>
                </form>
            </div>
        </div>
    </div>
</div>
    <div class="main">

            <h4>Verificar clientes com multa:</h4>
            <?php if (!empty($msg) && $codWhere == 1) : ?>
                <div 
                    class="alert <?php echo ($msgcod == 1) ? 'alert-danger' : 'alert-success'; ?> d-inline-block" 
                    role="alert" 
                    style="max-width: fit-content; padding: 10px;">
                    <?php echo $msg; ?>
                </div>
                <br>
            <?php endif; ?>
            <?php if (!empty($msg) && $codWhere == 3) : ?>
                <div 
                    class="alert <?php echo ($msgcod == 1) ? 'alert-danger' : 'alert-success'; ?> d-inline-block" 
                    role="alert" 
                    style="max-width: fit-content; padding: 10px;">
                    <?php echo $msg; ?>
                </div>
                <br>
            <?php endif; ?>
            <form action="../php/verificarMultas.php" method="post">
                <label for="">idCliente:</label>
                <input type="number" name="idCliente">
                <label for="">Nome:</label>
                <input type="text" name="nome">
                <label for="">CPF:</label>           
                <input type="text" name="cpf">
                <input class="btn btn-primary bntConsultaClientes" type="submit" value="Buscar"> 
            </form>
 <?php if (!empty($clienteMulta)) : ?>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>CPF</th>
                <th>Telefone</th>
                <th>Multa</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($clienteMulta as $clienteMulta) : ?>
    <tr>
        <td><?php echo htmlspecialchars($clienteMulta['idCliente']); ?></td>
        <td><?php echo htmlspecialchars($clienteMulta['nome']); ?></td>
        <td><?php echo htmlspecialchars($clienteMulta['cpf']); ?></td>
        <td><?php echo htmlspecialchars($clienteMulta['telefone']); ?></td>
        <td><?php echo htmlspecialchars($clienteMulta['multa']); ?></td>
        <td>
          
        <button data-bs-toggle="modal" data-bs-target="#pagamentoMultaModal" type="button" class="btn btn-info" href="javascript:void(0);" onclick="pagamentoMultaModal(
        <?php echo $clienteMulta['idCliente']; ?>,<?php echo $clienteMulta['multa']; ?>
        
        )">Pagar</button>

        </td>
    </tr>
<?php endforeach; ?>

        </tbody>
    </table>
<?php else : ?>
    <p>Nenhum cliente encontrado.</p>
<?php endif; ?>
        </div>
        <br>
    </div>
    </body>
</html>