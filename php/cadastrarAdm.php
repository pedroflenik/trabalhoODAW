<?php

function verificaCPF($cpf) {


        /*
    retorna um codigo.
        0 == cpf valido
        1 == cpf invalido
        2 == cpf ja cadastrado
    */ 

    // I HATE REGEX
    $cpf = preg_replace('/\D/', '', $cpf);

    if (strlen($cpf) !== 11) {
        echo "Invalid CPF length.<br>";
        return 1;
    }

    
    $chars = str_split($cpf);

    foreach ($chars as $char) {
        if (!is_numeric($char)) {
            echo "Invalid character detected: $char<br>";
            return 1;
        }
    }
    // converte  para int
    $chars = array_map('intval', $chars);

    foreach ($chars as $index => $char) {
        echo "The number is: $char (index $index)<br>";
    }
    $pd = 0; // primeiro digito verificador
    $var = 10;
    for($i = 0; $i < 9; $i++){
        $pd += $chars[$i] * $var;
        $var--;
    }

    $pd %= 11;

    if($pd < 2){
        $pd = 0;
    }else{
        $pd = 11 - $pd;
    }

    if($pd != $chars[9]){
        return 1;
    }

    $pd = 0; // segundo digito verificador
    $var = 11;
    for($i = 0; $i < 10; $i++){
        $pd += $chars[$i] * $var;
        $var--;
    }
    $pd %= 11;

    if($pd < 2){
        $pd == 0;
    }else{
        $pd = 11 - $pd;
    }

    if($pd != $chars[10]){
        return 1;
    }
    $link = mysqli_connect("localhost", "root", "udesc", "biblioteca");  

    $sql = "SELECT cpf FROM bibliotecarios WHERE cpf = ?";
    if ($stmt = $link->prepare($sql)) {
        $stmt->bind_param('s', $cpf);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->close();
            $link->close();
            return 2;
        }

        $stmt->close();
    } else {
        echo "erro na query durante vericacao do cpf" . $link->error;
        $link->close();
        return 1;
    }

    return 0;
}

function verificaSenha($senha,$conSenha) {
    /*
    retorna um codigo.
        0 == SENHA atende todos os requisitos
        1 == SENHA não possui pelo menos 8 characteres
        2 == Senha não possui characteres especiais;
        3 == Senha e conSenha sao diferentes
    */ 

    if (strlen($senha) < 8) {
        return 1;
    }
    
    if (!preg_match('/[\W_]/', $senha)) {
        return 2;
    }
    if($senha != $conSenha){
        return 3;
    }
    return 0;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $nome     = $_POST['nome'];
    $cpf      = $_POST['cpf'];
    $senha    = $_POST['senha'];
    $conSenha = $_POST['conSenha'];
    
    verificaCPF($cpf);
    
    if($nome == "" || $cpf == "" || $senha == ""){
        $_SESSION['msg'] = "Preencha todos os campos!";
        $_SESSION['msgCOD'] = 1;
        header('Location: ../administrador/gerenciarUsuarios.php');
        exit;
    }
    //verificações
    $codVerificaCpf = verificaCPF($cpf);


    switch ($codVerificaCpf) {
        case 1:
            $_SESSION['msg'] = "Insira um cpf válido";
            $_SESSION['msgCOD'] = 1;
            header('Location: ../administrador/gerenciarUsuarios.php');
            exit;
        case 2:
            $_SESSION['msg'] = "Já existe um usuario com seu cpf";
            $_SESSION['msgCOD'] = 1;
            header('Location: ../administrador/gerenciarUsuarios.php');
            exit;
    }


    $codVerificaSenha = verificaSenha($senha,$conSenha);

    switch ($codVerificaSenha) {
        case 1:
            $_SESSION['msg'] = "A sua senha deve:<br>• Ter no mínimo 8 digitos<br>• Um character especial";
            $_SESSION['msgCOD'] = 1;
            header('Location: ../administrador/gerenciarUsuarios.php');
            exit;
        case 3:
            $_SESSION['msg'] = "Os campos:<br>• Senha<br>• Confirmar senha<br>Devem ser iguais";
            $_SESSION['msgCOD'] = 1;
            header('Location: ../administrador/gerenciarUsuarios.php');
            exit;
    }


    // abre
    $link = mysqli_connect("localhost", "root", "udesc", "biblioteca");

    $stmt = mysqli_prepare($link, "INSERT INTO bibliotecarios (nome, cpf, senha) VALUES (?, ?, ?)");

    mysqli_stmt_bind_param($stmt, "sss", $nome, $cpf, $senha);


    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['msg'] = 'Cadastro realizado com sucesso!';
        $_SESSION['msgCOD'] = 0;
        // fecha statement e conectcao
        mysqli_stmt_close($stmt);
        mysqli_close($link);
        header('Location: ../administrador/gerenciarUsuarios.php');
        exit;
    } else {
        $_SESSION['msg'] = 'Erro ao cadastrar administrador';
        $_SESSION['msgCOD'] = 1;
        mysqli_stmt_close($stmt);
        mysqli_close($link);
        header('Location: ../administrador/gerenciarUsuarios.php');
        exit;
    }
  
}

?>