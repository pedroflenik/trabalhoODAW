<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Biblioteca - Gerenciar Usuários</title>

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

if (isset($_SESSION['admins']) && !empty($_SESSION['admins'])) {
    $admins = $_SESSION['admins'];
} else {
    $admins = [];
}

if (isset($_SESSION['clientes']) && !empty($_SESSION['clientes'])) {
    $clientes = $_SESSION['clientes'];
} else {
    $clientes = [];
}

?>

<body>

<div class="navVbar">
        <h3 class="titulo">TITULO</h3>
      <a href="gerenciarUsuarios.php">Gerenciar Usuários</a>
      <a href="gerenciarLivros.php">Gerenciar Livros</a>
      <a href="emprestimos.php">Novo Emprestimo</a>
      <a href="reservas.php">Nova Reserva</a>

      <button class="sair-btn" onclick="sairPag()">SAIR</button>
    </div>
    <div class="main">


<!-- ADM edit MODAL -->
<div class="modal fade" id="editAdminModal" tabindex="-1" aria-labelledby="editAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAdminModalLabel">Editar Administrador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editAdminForm" action="../php/editarAdm.php" method="post">
                    <input type="hidden" name="id" id="adminId">
                    <div class="mb-3">
                        <label for="editNome" class="form-label">Nome:</label>
                        <input type="text" id="editNome" name="nome" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="editCpf" class="form-label">CPF:</label>
                        <input type="text" id="editCpf" name="cpf" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Cliente edit MODAL -->
<div class="modal fade" id="editClienteModal" tabindex="-1" aria-labelledby="editClienteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editClienteModalLabel">Editar Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editClienteForm" action="../php/editarCliente.php" method="post">
                    <input type="hidden" name="id" id="clienteId">
                    <div class="mb-3">
                        <label for="editClienteNome" class="form-label">Nome:</label>
                        <input type="text" id="editClienteNome" name="nome" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="editClienteCpf" class="form-label">CPF:</label>
                        <input type="text" id="editClienteCpf" name="cpf" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="editClienteMulta" class="form-label">Multa:</label>
                        <input type="text" id="editClienteMulta" name="multa" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>


        <div>
            <h4>Cadastrar Administrador</h4>
            <form action="../php/cadastrarAdm.php" method="post">
            <?php if (!empty($msg) && $codWhere == 1) : ?>
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
            <input id="idSenha" type="password" name="senha"><br>
            <label for="" >Confirmar senha:</label><br>
            <input id="idConfirmarSenha" type="password" name="conSenha">
            <input value="Mostrar Senha"type="button" id="idBotaoMostraSenha" onclick="mostraSenha()"class="btn btn-primary mostrarSenha"><br>
            <input class="btn btn-primary" type="submit" value="Cadastrar">
            </form>
        </div>
        <br>
        <hr>
        <br>
        <div>
        <h4>Consulta de Administradores</h4>
<form action="../php/consultaAdm.php" method="post">
    <?php if (!empty($msg) && $codWhere == 2) : ?>
        <div 
            class="alert <?php echo ($msgcod == 1) ? 'alert-danger' : 'alert-success'; ?> d-inline-block" 
            role="alert" 
            style="max-width: fit-content; padding: 10px;">
            <?php echo $msg; ?>
        </div>
        <br>
    <?php endif; ?>
    <label for="">Nome:</label>
    <input type="text" name="nome">
    <label for="">CPF:</label>
    <input type="text" name="cpf">
    <input class="btn btn-primary" type="submit" value="Buscar">
</form>
<?php if (!empty($admins)) : ?>
    <table class="table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($admins as $admin) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($admin['nome']); ?></td>
                    <td><?php echo htmlspecialchars($admin['cpf']); ?></td>
                    <td>
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editAdminModal" onclick="openEditModalAdm(<?php echo $admin['idBibliotecario']; ?>, '<?php echo addslashes($admin['nome']); ?>', '<?php echo addslashes($admin['cpf']); ?>')">Editar</button> | 
                    <button type="button" class="btn btn-danger" href="javascript:void(0);" onclick="confirmarExcluirAdm(<?php echo $admin['idBibliotecario']; ?>, '<?php echo addslashes($admin['nome']); ?>', '<?php echo addslashes($admin['cpf']); ?>')">Excluir</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <p>Nenhum administrador encontrado.</p>
<?php endif; ?>
        </div>
        <br>
        <hr>
        <br>
        <div>
            <h4>Consulta de clientes:</h4>
            <?php if (!empty($msg) && $codWhere == 3) : ?>
                <div 
                    class="alert <?php echo ($msgcod == 1) ? 'alert-danger' : 'alert-success'; ?> d-inline-block" 
                    role="alert" 
                    style="max-width: fit-content; padding: 10px;">
                    <?php echo $msg; ?>
                </div>
                <br>
            <?php endif; ?>
            <form action="../php/consultaCliente.php" method="post">
                <label for="">Nome:</label>
                <input type="text" name="nome">
                <label for="">CPF:</label>           
                <input type="text" name="cpf">
                <label for="">Multado?</label>
                <input type="checkbox" name="multa"><br>
                <input class="btn btn-primary bntConsultaClientes" type="submit" value="Buscar"> 
            </form>
 <?php if (!empty($clientes)) : ?>
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
        <?php foreach ($clientes as $clientes) : ?>
    <tr>
        <td><?php echo htmlspecialchars($clientes['idCliente']); ?></td>
        <td><?php echo htmlspecialchars($clientes['nome']); ?></td>
        <td><?php echo htmlspecialchars($clientes['cpf']); ?></td>
        <td><?php echo htmlspecialchars($clientes['telefone']); ?></td>
        <td><?php echo htmlspecialchars($clientes['multa']); ?></td>
        <td>
          
            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editClienteModal" onclick="openEditModalCliente(<?php echo $clientes['idCliente']; ?>, '<?php echo addslashes($clientes['nome']); ?>', '<?php echo addslashes($clientes['cpf']); ?>','<?php echo addslashes($clientes['multa']); ?>')">Editar</button> | 
            
    
            <button type="button" class="btn btn-danger" href="javascript:void(0);" onclick="confirmarExcluirCliente(<?php echo $clientes['idCliente']; ?>, '<?php echo addslashes($clientes['nome']); ?>', '<?php echo addslashes($clientes['cpf']); ?>')">Excluir</button>
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