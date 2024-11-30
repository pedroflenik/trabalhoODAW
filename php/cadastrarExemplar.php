<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    

    
    $isbn = $_POST['isbn'];
    $quantidadeExemplares = $_POST['numExemplares'];


    if($quantidadeExemplares <= 0){
        $_SESSION['msg'] = 'Erro: Insira um valor maior que zero';
        $_SESSION['msgCOD'] = 1;
        $_SESSION['codWhere'] = 2;
        header('Location: ../administrador/gerenciarLivros.php');
        exit;
    }

    $link = mysqli_connect("localhost", "root", "udesc", "biblioteca");
    for ($i = 0; $i < $quantidadeExemplares; $i++) {
        $stmt = mysqli_prepare($link, "INSERT INTO exemplares (isbn) VALUES (?)");
        mysqli_stmt_bind_param($stmt, "s", $isbn);
    
        if (mysqli_stmt_execute($stmt)) {
            if($quantidadeExemplares > 1){
                $_SESSION['msg'] = 'Exemplares cadastrado com sucesso';
            }else{
                $_SESSION['msg'] = 'Exemplares cadastrado com sucesso';
            }
            $_SESSION['msgCOD'] = 2;
            $_SESSION['codWhere'] = 2;
        } else {
            $_SESSION['msg'] = 'Erro ao cadastrar exemplar';
            $_SESSION['msgCOD'] = 1;
            $_SESSION['codWhere'] = 2;
            mysqli_stmt_close($stmt);
            mysqli_close($link);
            header('Location: ../administrador/gerenciarLivros.php');
            exit;
        }
    
        mysqli_stmt_close($stmt);
    }
    mysqli_close($link);
    header('Location: ../administrador/gerenciarLivros.php');
    exit;    
}

?>