<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    session_start();
    $isbnLivroDeletar = $_GET['isbn'];
    echo $isbnLivroDeletar;

    $link = mysqli_connect("localhost", "root", "udesc", "biblioteca");

    $stmt = mysqli_prepare($link, "DELETE FROM livros WHERE isbn = ?");

    mysqli_stmt_bind_param($stmt, "s", $isbnLivroDeletar);


    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['msg'] = 'Livro deletatado com sucesso';
        $_SESSION['msgCOD'] = 2;
        $_SESSION['codWhere'] = 2;
        mysqli_stmt_close($stmt);
        mysqli_close($link);
        header('Location: ../administrador/gerenciarLivros.php');
        exit;
    } else {
        $_SESSION['msg'] = 'Erro ao deletar liviro';
        $_SESSION['msgCOD'] = 1;
        $_SESSION['codWhere'] = 2;
        mysqli_stmt_close($stmt);
        mysqli_close($link);
        header('Location: ../administrador/gerenciarLivros.php');
        exit;
    }
}



?>
