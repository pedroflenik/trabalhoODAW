

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


//verificar numero de renovacoes
if($numRenovacoes >= 3){
    $_SESSION['msg'] = "Não é possível renovar esse emprestimo (NÚMERO DE EMPRESTIMOS MAIOR OU IGUAL A 3)";
    $_SESSION['msgCOD'] = 1;
    $_SESSION['codWhere'] = 2;
    header('Location: ../administrador/emprestimos.php');
    exit;
}



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

$link = mysqli_connect("localhost", "root", "udesc", "biblioteca");

$stmt = mysqli_prepare($link, "UPDATE emprestimos SET numRenovacoes = ?, dataEsperadaDevolucao = ?, status='E' WHERE idExemplar = ? AND idCliente = ? AND dataEmprestimo = ?");


$dataHoje = date('Y-m-d');
$novaData = new DateTime($dataHoje);
$novaData->modify("+1 week");
$novaDataFormatada = $novaData->format('Y-m-d'); 

$renovacoes = $numRenovacoes + 1/
mysqli_stmt_bind_param($stmt, "isiis", $renovacoes, $novaDataFormatada, $idExemplar, $idCliente, $dataEmprestimo);


if (mysqli_stmt_execute($stmt)) {
    $_SESSION['msg'] = 'Renovação realizada com sucesso!';
    $_SESSION['msgCOD'] = 2;
    $_SESSION['codWhere'] = 2;
    mysqli_stmt_close($stmt);
    mysqli_close($link);
    header('Location: ../administrador/emprestimos.php');
    exit;
} else {
    $_SESSION['msg'] = 'Erro na renovação';
    $_SESSION['msgCOD'] = 1;
    $_SESSION['codWhere'] = 2;
    mysqli_stmt_close($stmt);
    mysqli_close($link);
    header('Location: ../administrador/emprestimos.php');
    exit;
}

}
?>

