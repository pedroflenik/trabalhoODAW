<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    session_start();
    $idClienteDeletar = $_GET['id'];
    echo $idClienteDeletar;

    $link = mysqli_connect("localhost", "root", "udesc", "biblioteca");

    $stmt = mysqli_prepare($link, "DELETE FROM clientes WHERE idCliente = ?");

    mysqli_stmt_bind_param($stmt, "i", $idClienteDeletar);


    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['msg'] = 'Cliente deletatado com sucesso';
        $_SESSION['msgCOD'] = 2;
        $_SESSION['codWhere'] = 3;
        mysqli_stmt_close($stmt);
        mysqli_close($link);
        header('Location: ../administrador/gerenciarUsuarios.php');
        exit;
    } else {
        $_SESSION['msg'] = 'Erro ao deletar cliente';
        $_SESSION['msgCOD'] = 1;
        $_SESSION['codWhere'] = 3;
        mysqli_stmt_close($stmt);
        mysqli_close($link);
        header('Location: ../administrador/gerenciarUsuarios.php');
        exit;
    }
}



?>
