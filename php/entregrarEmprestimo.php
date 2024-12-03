<?php


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
session_start();
$_SESSION['codWhere'] = 2;

$idCliente      = $_GET['idCliente'];
$idExemplar     = $_GET['idExemplar'];
$dataEmprestimo = $_GET['dataEmprestimo'];
$numRenovacoes  = $_GET['numRenovacoes'];

echo $idCliente."<br>";
echo $idExemplar."<br>";
echo $dataEmprestimo."<br>";
echo $numRenovacoes;




$link = mysqli_connect("localhost", "root", "udesc", "biblioteca");

if (!$link) {
    die("Erro na conexão: " . mysqli_connect_error());
}

// Chamar a stored procedure para verificar e aplicar multa se necessário
$result = mysqli_query($link, "CALL VerificarEmprestimosAtrasados()");

if (!$result) {
    echo "Erro ao chamar a stored procedure: " . mysqli_error($link);
} else {
    echo "Multas aplicadas e status de empréstimos atualizados com sucesso.";
}



$dataEntrega = date('Y-m-d');
$stmt = mysqli_prepare($link, "UPDATE emprestimos SET status = 'D',dataDevolucao = ? WHERE idCliente = ? AND idExemplar = ? AND dataEmprestimo = ? ");

mysqli_stmt_bind_param($stmt, "siis",$dataEntrega, $idCliente, $idExemplar, $dataEmprestimo);


if (mysqli_stmt_execute($stmt)) {
    $_SESSION['msg'] = 'Entrega realizada com sucesso!';
    $_SESSION['msgCOD'] = 2;
    $_SESSION['codWhere'] = 3;
    // fecha statement e conectcao
    mysqli_stmt_close($stmt);
    mysqli_close($link);
    header('Location: ../administrador/emprestimos.php');
    exit;
} else {
    $_SESSION['msg'] = 'Erro na atualização do cliente';
    $_SESSION['msgCOD'] = 1;
    $_SESSION['codWhere'] = 3;
    mysqli_stmt_close($stmt);
    mysqli_close($link);
    header('Location: ../administrador/emprestimos.php');
    exit;
}

}



?>