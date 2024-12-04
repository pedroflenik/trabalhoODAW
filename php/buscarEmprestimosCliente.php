<?php
session_start(); 

unset($_SESSION['emprestimosCliente']);
$idCliente = $_GET['idCliente'];
$pendente = isset($_POST['pendente']) ? true : false; 
$_SESSION['codWhere'] = 1;
echo $idCliente."<br>";
echo $pendente ? "1" : "0"; 

if ($pendente == 1) {
    $sql = "SELECT * FROM emprestimos WHERE dataDevolucao IS NULL AND idCliente = ?";
} else {
    $sql = "SELECT * FROM emprestimos WHERE dataDevolucao IS NOT NULL AND idCliente = ?";
}

$link = mysqli_connect("localhost", "root", "udesc", "biblioteca");

if (!$link) {
    die("Erro na conexão: " . mysqli_connect_error());
}

if ($stmt = $link->prepare($sql)) {
   
    mysqli_stmt_bind_param($stmt, "i", $idCliente);


    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['emprestimosCliente'] = $result->fetch_all(MYSQLI_ASSOC); 
    } else {
        $_SESSION['msg'] = 'Nenhum empréstimo encontrado com os critérios fornecidos.';
        $_SESSION['msgCOD'] = 1;
    }


    $stmt->close();
} else {
    $_SESSION['msg'] = 'Erro na consulta ao banco de dados.';
    $_SESSION['msgCOD'] = 1;
}


mysqli_close($link);


header('Location: ../cliente/cliente.php');
exit;
?>
