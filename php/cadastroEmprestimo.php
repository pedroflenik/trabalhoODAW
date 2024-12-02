<?php

function verificaCliente($idCliente) {
    /*
    Retorna um código:
    0 = Sem erros (cliente encontrado)
    1 = Cliente não existe
    */


    $link = mysqli_connect("localhost", "root", "udesc", "biblioteca");

    if (!$link) {
        echo "Erro de conexão: " . mysqli_connect_error();
        return 1;
    }

    $sql = "SELECT idCliente FROM clientes WHERE idCliente = ?";


    if ($stmt = $link->prepare($sql)) {

        $stmt->bind_param('i', $idCliente);

        $stmt->execute();
        $stmt->store_result();  

        if ($stmt->num_rows === 0) {
            $stmt->close();
            $link->close();
            return 1;
        }

        $stmt->close();
    } else {
        echo "Erro na query durante verificação do cliente: " . $link->error;
        $link->close();
        return 1;
    }

    // Conexão fechada com sucesso, cliente encontrado
    $link->close();
    return 0;
}


function  verificaExemplar($idExemplar){

    /*
    retorna um codigo
    0 = 0 ERROS
    1 = exemplar nao existe
    2 = exemplar esta emprestado
    */ 


    $link = mysqli_connect("localhost", "root", "udesc", "biblioteca");  

    $sql = "SELECT idExemplar FROM exemplares WHERE idExemplar = ?";

    if ($stmt = $link->prepare($sql)) {
        $stmt->bind_param('i', $idExemplar);
        $stmt->execute();
        $stmt->store_result();

        if (!($stmt->num_rows > 0)) {
            $stmt->close();
            $link->close();
            return 1;
        }

        $stmt->close();
    } else {
        echo "erro na query durante vericacao do exemplar" . $link->error;
        $link->close();
        return 1;
    }


    $sql = "SELECT idExemplar FROM emprestimos WHERE idExemplar = ? AND dataDevolucao IS NULL";

    // Preparar e executar a consulta de verificação
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $idExemplar);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        // Verifica se o exemplar está emprestado (se o número de linhas for maior que 0)
        if (mysqli_stmt_num_rows($stmt) > 0) {
            // Exemplar já está emprestado
            
            mysqli_stmt_close($stmt);
            mysqli_close($link);

            return 2;
            exit;
        }
    }

    return 0;
}



function calcularNovaData($dataEmprestimo, $quantidadeSemanas) {
    $data = new DateTime($dataEmprestimo);
    $data->modify("+$quantidadeSemanas weeks");
    return $data->format('Y-m-d');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $idExemplar       = $_POST['idExemplar'];
    $idCliente        = $_POST['idCliente'];
    $tempoInicial     = $_POST['tempoInicial'];
    $dataEmprestimo = date('Y-m-d');
    echo "$dataEmprestimo <br>";  
    echo "$idExemplar <br>";
    echo "$idCliente <br>";
    echo "$tempoInicial <br>";
    

    if($idExemplar == "" || $idCliente == "" || $tempoInicial == ""){
        $_SESSION['msg'] = "Preencha todos os campos corretamente";
        $_SESSION['msgCOD'] = 1;
        $_SESSION['codWhere'] = 1;
        header('Location: ../administrador/emprestimos.php');
        exit;
    }


    if($tempoInicial <=0){
        $_SESSION['msg'] = "O tempo de emprestimo deve ser maior que zero";
        $_SESSION['msgCOD'] = 1;
        $_SESSION['codWhere'] = 1;
        header('Location: ../administrador/emprestimos.php');
        exit;
    }


    if($tempoInicial > 4)
    {
        $_SESSION['msg'] = "O tempo inical maximo de um emprestimo é de 4 semanas";
        $_SESSION['msgCOD'] = 1;
        $_SESSION['codWhere'] = 1;
        header('Location: ../administrador/emprestimos.php');
        exit;
    }



    $codVerificaCliente = verificaCliente($idCliente);


    switch ($codVerificaCliente) {
        case 1:
            $_SESSION['msg'] = "O id do cliente não existe";
            $_SESSION['msgCOD'] = 1;
            $_SESSION['codWhere'] = 1;
            header('Location: ../administrador/emprestimos.php');
            exit;
    }

    
    $codVerificaExemplar = verificaExemplar($idExemplar);

    switch ($codVerificaExemplar) {
        case 1:
            $_SESSION['msg'] = "O id do exemplar cadastrado não existe";
            $_SESSION['msgCOD'] = 1;
            $_SESSION['codWhere'] = 1;
            header('Location: ../administrador/emprestimos.php');
            exit;
        case 2:
            $_SESSION['msg'] = "O exemplar já esta emprestado";
            $_SESSION['msgCOD'] = 1;
            $_SESSION['codWhere'] = 1;
            header('Location: ../administrador/emprestimos.php');
            exit;
    }  




    $dataEsperada = calcularNovaData($dataEmprestimo,$tempoInicial);


    // Cadastro emprestimo
    $link = mysqli_connect("localhost", "root", "udesc", "biblioteca");
    $stmt = mysqli_prepare($link, "INSERT INTO emprestimos (idExemplar, idCliente, dataEmprestimo, dataEsperadaDevolucao) VALUES (?, ?, ?,?)");
    mysqli_stmt_bind_param($stmt, "iiss", $idExemplar, $idCliente, $dataEmprestimo, $dataEsperada);


    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['msg'] = 'Emprestimo realizado com sucesso!';
        $_SESSION['msgCOD'] = 0;
        $_SESSION['codWhere'] = 1;
        // fecha statement e conectcao
        mysqli_stmt_close($stmt);
        mysqli_close($link);
        header('Location: ../administrador/emprestimos.php');
        exit;
    } else {
        $_SESSION['msg'] = 'Erro ao cadastrar emprestimo';
        $_SESSION['msgCOD'] = 1;
        $_SESSION['codWhere'] = 1;
        mysqli_stmt_close($stmt);
        mysqli_close($link);
        header('Location: ../administrador/emprestimos.php');
        exit;
    }

}

?>