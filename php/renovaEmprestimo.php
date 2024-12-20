

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


$stmt = mysqli_prepare($link, "SELECT multa FROM clientes WHERE idCliente = ?");
$multa = 0;

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $idCliente);

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $multa);

    if (mysqli_stmt_fetch($stmt)) {
        echo "A multa do cliente é: " . $multa;
    } else {
        echo "Nenhum resultado encontrado.";
    }

    // Fechar a consulta
    mysqli_stmt_close($stmt);
} else {
    echo "Erro ao preparar a consulta: " . mysqli_error($link);
}


if($multa > 0){
    $_SESSION['msg'] = "Não é possível renovar esse emprestimo, pois o cliente esta multado";
    $_SESSION['msgCOD'] = 1;
    $_SESSION['codWhere'] = 2;
    header('Location: ../administrador/emprestimos.php');
    exit;
}



$link = mysqli_connect("localhost", "root", "udesc", "biblioteca");

$stmt = mysqli_prepare($link, "SELECT dataEsperadaDevolucao FROM emprestimos WHERE idExemplar = ? AND idCliente = ? AND dataEmprestimo = ?");
$dataEsperadaDevolucao = "";

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "iis", $idExemplar,$idCliente, $dataEmprestimo);

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $dataEsperadaDevolucao);

    if (mysqli_stmt_fetch($stmt)) {
        echo "A a data esperada do emprestimo é: " . $dataEsperadaDevolucao;
    } else {
        echo "Nenhum resultado encontrado.";
    }

    // Fechar a consulta
    mysqli_stmt_close($stmt);
} else {
    echo "Erro ao preparar a consulta: " . mysqli_error($link);
}





$stmt = mysqli_prepare($link, "UPDATE emprestimos SET numRenovacoes = ?, dataEsperadaDevolucao = ?,dataDaUltimaMulta = ?, status='E' WHERE idExemplar = ? AND idCliente = ? AND dataEmprestimo = ?");


$dataHoje = date('Y-m-d');
$novaData = new DateTime($dataHoje);
$novaData->modify("+1 week");
$novaDataFormatada = $novaData->format('Y-m-d'); 

if($novaDataFormatada <= $dataEsperadaDevolucao){
    $dataHoje = $dataEsperadaDevolucao;
    $novaData = new DateTime($dataHoje);
    $novaData->modify("+1 week");
    $novaDataFormatada = $novaData->format('Y-m-d'); 
}

$renovacoes = $numRenovacoes + 1;
mysqli_stmt_bind_param($stmt, "issiis", $renovacoes, $novaDataFormatada,$novaDataFormatada, $idExemplar, $idCliente, $dataEmprestimo);


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

