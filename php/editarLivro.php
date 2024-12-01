<?php



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();
    unset($_SESSION['livros']);

    $isbn = $_POST['isbn'];
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $editora = $_POST['editora'];
    $edicao = $_POST['edicao'];
    if (isset($_POST['genero'])) {
        $genero = $_POST['genero']; 
    } else {
        $genero = "";
    }
    echo $isbn;
    echo $titulo;
    echo $autor;
    echo $editora;
    echo $edicao;
    echo $genero;
 

    if($isbn == "" || $titulo == "" || $autor == "" || $editora == "" || $edicao == "" || $genero == ""){
        $_SESSION['msg'] = "Preencha todos os campos corretamente no formulario de edição";
        $_SESSION['msgCOD'] = 1;
        $_SESSION['codWhere'] = 2;
        header('Location: ../administrador/gerenciarLivros.php');
        exit;
    } 

    if($edicao < 0){
        $_SESSION['msg'] = "A edição deve ser maior que 0 no formulario de edição";
        $_SESSION['msgCOD'] = 1;
        $_SESSION['codWhere'] = 2;
        header('Location: ../administrador/gerenciarLivros.php');
        exit;
    }
    


    $link = mysqli_connect("localhost", "root", "udesc", "biblioteca");

    $stmt = mysqli_prepare($link, "UPDATE livros SET titulo = ?, autor = ?, editora = ?, edicao = ?, genero_id = ? WHERE isbn = ?");
   
    mysqli_stmt_bind_param($stmt, "sssiis",$titulo,$autor,$editora,$edicao,$genero,$isbn );


    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['msg'] = 'Ediçaõ de livro realizada com sucesso!';
        $_SESSION['msgCOD'] = 0;
        $_SESSION['codWhere'] = 2;
        mysqli_stmt_close($stmt);
        mysqli_close($link);
        header('Location: ../administrador/gerenciarLivros.php');
        exit;
    } else {
        $_SESSION['msg'] = 'Erro ao editar  livro';
        $_SESSION['msgCOD'] = 1;
        $_SESSION['codWhere'] = 2;
        mysqli_stmt_close($stmt);
        mysqli_close($link);
        header('Location: ../administrador/gerenciarLivros.php');
        exit;
    }
  

}



?>