<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpf = $_POST["cpf"];
    $senha = $_POST['senha'];
    $id = 0;
    $nome = "";
    $cpf_result = "";
    $senha_result = "";
    

    if($cpf == "" || $senha == ""){
        $_SESSION['msg'] = "Preencha corretamente todos os campos";
        header('Location: ../loginAdm.php');
        exit;
    }

    $link = mysqli_connect("localhost", "root", "udesc", "biblioteca");  

    $sql = "SELECT * FROM bibliotecarios WHERE cpf = ? AND senha = ?";

    if ($stmt = $link->prepare($sql)) {
        $stmt->bind_param('ss', $cpf, $senha);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $nome, $cpf_result, $senha_result);
        
        if ($stmt->num_rows > 0) {
            $stmt->fetch(); 
            // echo "ID: " . $id . ", Nome: " . $nome . ", CPF: " . $cpf_result . ", Senha: " . $senha_result; 
            $_SESSION['nomeUsuarioAtual'] = $nome;
            $stmt->close();
            $link->close();
            header('Location: ../administrador/adm.php');
            exit(); 
        }

        $stmt->close();
    } else {
        echo "Erro na query durante verificação do CPF: " . $link->error;
        $link->close();
        return 1;
    }
}
?>
