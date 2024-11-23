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
      <a href="">Novo emprestimo</a>
      <a href="">Nova reserva</a>

      <button class="sair-btn" onclick="sairPag()">SAIR</button>
    </div>
    <div class="main">
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
                        <a href="editAdmin.php?id=<?php echo $admin['idBibliotecario']; ?>">Editar</a> | 
                        <a href="javascript:void(0);" onclick="confirmarExcluirAdm(<?php echo $admin['idBibliotecario']; ?>, '<?php echo addslashes($admin['nome']); ?>', '<?php echo addslashes($admin['cpf']); ?>')">Excluir</a>
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
                    <td><?php echo htmlspecialchars($clientes['nome']); ?></td>
                    <td><?php echo htmlspecialchars($clientes['cpf']); ?></td>
                    <td><?php echo htmlspecialchars($clientes['telefone']); ?></td>
                    <td><?php echo htmlspecialchars($clientes['multa']); ?></td>
                    <td>
                       <a href="editAdmin.php?id=<?php echo $clientes['idCliente']; ?>">Editar</a> | 
                       <a href="javascript:void(0);" onclick="confirmarExcluirCliente(<?php echo $clientes['idCliente']; ?>, '<?php echo addslashes($clientes['nome']); ?>', '<?php echo addslashes($clientes['cpf']); ?>')">Excluir</a>
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