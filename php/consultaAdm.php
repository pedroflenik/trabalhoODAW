<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
 
    if($cpf == "" && $nome==""){
        $_SESSION['msg'] = "Preenchas algum dos campos";
        $_SESSION['msgCOD'] = 1; // 1 == erro == 0 sucesso
        $_SESSION['codWhere'] = 2;
        header('Location: ../administrador/gerenciarUsuarios.php');
        exit;
    }

    $link = mysqli_connect("localhost", "root", "udesc", "biblioteca");  
    if($cpf == "" && $nome != ""){
        // So cpf
        $sql = "SELECT * FROM bibliotecarios WHERE  cpf LIKE ?";
    
        if ($stmt = $link->prepare($sql)) {
            $cpfParam = "%$cpf%";
            
            $stmt->bind_param("s", $cpfParam);
            

            $stmt->execute();
        
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $_SESSION['admins'] = $result->fetch_all(MYSQLI_ASSOC);
            } else {
                $msg = "Nenhum administrador encontrado.";
                $msgcod = 1;
            }
            $codWhere = 2;
            
            $stmt->close();
        } else {
            $msg = "Erro na consulta ao banco de dados.";
            $msgcod = 1;
            $codWhere = 2;
        }
    }
    elseif($cpf == "" && $nome != ""){
        // so nome

        
        $sql = "SELECT * FROM bibliotecarios WHERE nome LIKE ?";
    
        // Prepare the SQL statement
        if ($stmt = $link->prepare($sql)) {
            $nomeParam = "%$nome%";

            
            $stmt->bind_param("s", $nomeParam,);
            

            $stmt->execute();
            
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $_SESSION['admins'] = $result->fetch_all(MYSQLI_ASSOC);
            } else {
                $msg = "Nenhum administrador encontrado.";
                $msgcod = 1;
            }
            $codWhere = 2;
            
            // fecha
            $stmt->close();
        } else {
            $msg = "Erro na consulta ao banco de dados.";
            $msgcod = 1;
            $codWhere = 2;
        }
    }else{
        // os dois
        $sql = "SELECT * FROM bibliotecarios WHERE nome LIKE ? AND cpf LIKE ?";
    
   
        if ($stmt = $link->prepare($sql)) {
            $nomeParam = "%$nome%";
            $cpfParam = "%$cpf%";
            
            $stmt->bind_param("ss", $nomeParam, $cpfParam);
            

            $stmt->execute();
        
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $_SESSION['admins'] = $result->fetch_all(MYSQLI_ASSOC);
            } else {
                $msg = "Nenhum administrador encontrado.";
                $msgcod = 1;
            }
            $codWhere = 2;
            
            $stmt->close();
        } else {
            $msg = "Erro na consulta ao banco de dados.";
            $msgcod = 1;
            $codWhere = 2;
        }
    }
    
    header('Location: ../administrador/gerenciarUsuarios.php');  // Redirect to gerenciarUsuarios.php
    exit;
    }

  










?>