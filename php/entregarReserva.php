<?php

function calcularNovaData($dataEmprestimo, $quantidadeSemanas) {
    $data = new DateTime($dataEmprestimo);
    $data->modify("+$quantidadeSemanas weeks");
    return $data->format('Y-m-d');
}


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
session_start();
$_SESSION['codWhere'] = 2;

$idCliente      = $_GET['idCliente'];
$idExemplar     = $_GET['idExemplar'];
$dataReserva = $_GET['dataEmprestimo'];

echo $idCliente."<br>";
echo $idExemplar."<br>";
echo $dataReserva."<br>";


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



$stmt = mysqli_prepare($link, "UPDATE reservas SET status = 'E' WHERE idCliente = ? AND idExemplar = ? AND dataReserva = ? ");

mysqli_stmt_bind_param($stmt, "iis", $idCliente, $idExemplar, $dataReserva);


if (mysqli_stmt_execute($stmt)) {
    $_SESSION['msg'] = 'Entrega realizada com sucesso!';
    $_SESSION['msgCOD'] = 2;
    $_SESSION['codWhere'] = 3;
    // fecha statement e conectcao
    mysqli_stmt_close($stmt);
} else {
    $_SESSION['msg'] = 'Erro na atualização do cliente';
    $_SESSION['msgCOD'] = 1;
    $_SESSION['codWhere'] = 3;
    mysqli_stmt_close($stmt);
}

$data = date('Y-m-d');
$dataEntrega = calcularNovaData($data,1);

$stmt = mysqli_prepare($link, "INSERT INTO emprestimos (idExemplar, idCliente, dataEmprestimo, dataEsperadaDevolucao,dataDaUltimaMulta) VALUES (?, ?, ?,?,?)");
    mysqli_stmt_bind_param($stmt, "iisss", $idExemplar, $idCliente, $data, $dataEntrega,$dataEntrega);


    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['msg'] = 'Emprestimo realizado com sucesso!';
        $_SESSION['msgCOD'] = 0;
        $_SESSION['codWhere'] = 1;
        // fecha statement e conectcao
        mysqli_stmt_close($stmt);
        mysqli_close($link);
        header('Location: ../administrador/emprestimos.php');
        exit;
    } else {
        $_SESSION['msg'] = 'Erro ao cadastrar emprestimo';
        $_SESSION['msgCOD'] = 1;
        $_SESSION['codWhere'] = 1;
        mysqli_stmt_close($stmt);
        mysqli_close($link);
        header('Location: ../administrador/emprestimos.php');
        exit;
    }

}


?>