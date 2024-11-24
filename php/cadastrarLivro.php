<?php


function validaIsbn($isbn) {
    /*
    retorna um codigo:
        0 - isbn valido
        1 - isbn invalido
        2 - isbn repetido
    */ 


    $isbn = str_replace('-', '', $isbn);

    $link = mysqli_connect("localhost", "root", "udesc", "biblioteca");  

    $sql = "SELECT isbn FROM livros WHERE isbn = ?";
    if ($stmt = $link->prepare($sql)) {
        $stmt->bind_param('s', $isbn);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->close();
            $link->close();
            return 2;
        }

        $stmt->close();
    } else {
        echo "erro na query durante vericacao do isbn" . $link->error;
        $link->close();
        return 4;
    }
    

    if (strlen($isbn) == 10) {
        return validaIsbn10($isbn);
    }
  
    elseif (strlen($isbn) == 13) {
        return validaIsbn13($isbn);
    } else {
        return 1;
    }



    return 0;
}

function validaIsbn10($isbn10) {
    // I HATE REGEX
    if (!preg_match('/^\d{9}[\dX]$/', $isbn10)) {
        return 1;
    }

    $sum = 0;
    for ($i = 0; $i < 9; $i++) {
        $sum += (int)$isbn10[$i] * (10 - $i);
    }

    $lastChar = ($isbn10[9] === 'X') ? 10 : (int)$isbn10[9];
    $sum += $lastChar;

    if($sum % 11 == 0){
        return 0;
    }else{
        return 1;
    }
}

function validaIsbn13($isbn13) {
    // I HATE REGEX
    if (!preg_match('/^\d{13}$/', $isbn13)) {
        return 1;
    }

    $sum = 0;
    for ($i = 0; $i < 12; $i++) {
        $multiplier = ($i % 2 == 0) ? 1 : 3;
        $sum += (int)$isbn13[$i] * $multiplier;
    }

    $checkDigit = (10 - ($sum % 10)) % 10;

    if( $checkDigit == (int)$isbn13[12]){
        return 0;
    }else{
        return 1;
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();


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
 

    if($isbn == "" || $titulo == "" || $autor == "" || $editora == "" || $edicao == "" || $edicao == 0 || $genero == ""){
        $_SESSION['msg'] = "Preencha todos os campos corretamente";
        $_SESSION['msgCOD'] = 1;
        $_SESSION['codWhere'] = 1;
        header('Location: ../administrador/gerenciarLivros.php');
        exit;
    } 

    if($edicao < 0){
        $_SESSION['msg'] = "A edição deve ser maior que 0";
        $_SESSION['msgCOD'] = 1;
        $_SESSION['codWhere'] = 1;
        header('Location: ../administrador/gerenciarLivros.php');
        exit;
    }
    $codValidaIsbn = validaIsbn($isbn);

    
    switch ($codValidaIsbn) {
        case 1:
            $_SESSION['msg'] = "Insira um cod ISBN valido";
            $_SESSION['msgCOD'] = 1;
            $_SESSION['codWhere'] = 1;
            header('Location: ../administrador/gerenciarLivros.php');
            exit;
        case 2:
            $_SESSION['msg'] = "ISBN ja cadastrado";
            $_SESSION['msgCOD'] = 1;
            $_SESSION['codWhere'] = 1;
            header('Location: ../administrador/gerenciarLivros.php');
            exit;
    }

    $link = mysqli_connect("localhost", "root", "udesc", "biblioteca");

    $stmt = mysqli_prepare($link, "INSERT INTO livros (isbn,titulo,autor,editora,edicao,genero_id) VALUES (?, ?, ?,?,?,?)");


    mysqli_stmt_bind_param($stmt, "ssssii", $isbn,$titulo,$autor,$editora,$edicao,$genero);


    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['msg'] = 'Cadastro realizado com sucesso!';
        $_SESSION['msgCOD'] = 0;
        $_SESSION['codWhere'] = 1;
        mysqli_stmt_close($stmt);
        mysqli_close($link);
        header('Location: ../administrador/gerenciarLivros.php');
        exit;
    } else {
        $_SESSION['msg'] = 'Erro ao cadastrar livro';
        $_SESSION['msgCOD'] = 1;
        $_SESSION['codWhere'] = 1;
        mysqli_stmt_close($stmt);
        mysqli_close($link);
        header('Location: ../administrador/gerenciarLivros.php');
        exit;
    }
  

}



?>