<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    session_start();
    $idAdmDeletar = $_GET['id'];
    echo $idAdmDeletar;

    $link = mysqli_connect("localhost", "root", "udesc", "biblioteca");

    $stmt = mysqli_prepare($link, "DELETE FROM bibliotecarios WHERE idBibliotecario = ?");

    mysqli_stmt_bind_param($stmt, "i", $idAdmDeletar);


    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['msg'] = 'Administrador deletatado com sucesso';
        $_SESSION['msgCOD'] = 2;
        $_SESSION['codWhere'] = 2;
        mysqli_stmt_close($stmt);
        mysqli_close($link);
        header('Location: ../administrador/gerenciarUsuarios.php');
        exit;
    } else {
        $_SESSION['msg'] = 'Erro ao deletar administrador';
        $_SESSION['msgCOD'] = 1;
        $_SESSION['codWhere'] = 2;
        mysqli_stmt_close($stmt);
        mysqli_close($link);
        header('Location: ../administrador/gerenciarUsuarios.php');
        exit;
    }
}



?>
