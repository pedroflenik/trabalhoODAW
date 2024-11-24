<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();

    $nomeGenero = $_POST['nomeGenero'];
    echo $nomeGenero;
    $count = 0;

    if($nomeGenero == ""){
        $_SESSION['msg'] = "Preencha o nome do genero corretamente";
        $_SESSION['msgCOD'] = 1;
        $_SESSION['codWhere'] = 1;
        mysqli_close($link);
        header('Location: ../administrador/gerenciarLivros.php');
        exit;
    }
    
    $link = mysqli_connect("localhost", "root", "udesc", "biblioteca");
    $stmt = mysqli_prepare($link, "SELECT COUNT(*) FROM generos WHERE genero = ?");
    
    mysqli_stmt_bind_param($stmt, "s", $nomeGenero);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($count > 0) {
        $_SESSION['msg'] = "Genero ja cadastrado";
        $_SESSION['msgCOD'] = 1;
        $_SESSION['codWhere'] = 1;
        mysqli_close($link);
        header('Location: ../administrador/gerenciarLivros.php');
        exit;
    } else {
        $cadastroStmt = mysqli_prepare($link, "INSERT INTO generos (genero) VALUES (?)");
        mysqli_stmt_bind_param($cadastroStmt, "s", $nomeGenero);
        mysqli_stmt_execute($cadastroStmt);
        mysqli_stmt_close($cadastroStmt);
        mysqli_close($link);
        $_SESSION['msg'] = "Genero cadastrado com sucesso";
        $_SESSION['msgCOD'] = 2;
        $_SESSION['codWhere'] = 1;
        header('Location: ../administrador/gerenciarLivros.php');
        exit;
    }
    
    mysqli_close($link);
}
?>