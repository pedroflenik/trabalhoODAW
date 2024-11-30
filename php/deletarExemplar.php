<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    session_start();
    $_SESSION['codWhere'] = 3;
    $isbnExemplarDeletar = $_GET['idExemplar'];
    echo $isbnExemplarDeletar;

    $link = mysqli_connect("localhost", "root", "udesc", "biblioteca");

    $stmt = mysqli_prepare($link, "DELETE FROM exemplares WHERE idExemplar = ?");

    mysqli_stmt_bind_param($stmt, "i", $isbnExemplarDeletar);


    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['msg'] = 'Exemplar deletatado com sucesso';
        $_SESSION['msgCOD'] = 2;
        mysqli_stmt_close($stmt);
        mysqli_close($link);
        header('Location: ../administrador/gerenciarLivros.php');
        exit;
    } else {
        $_SESSION['msg'] = 'Erro ao deletar exemplar';
        $_SESSION['msgCOD'] = 1;
        mysqli_stmt_close($stmt);
        mysqli_close($link);
        header('Location: ../administrador/gerenciarLivros.php');
        exit;
    }
}



?>
