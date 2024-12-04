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


function  verificaExemplar($idExemplar,$dataReserva){

    /*
    retorna um codigo
    0 = 0 ERROS
    1 = exemplar nao existe
    2 = exeomlar emprestado nessa data
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

    $dataHoje = date('Y-m-d'); // Data atual no formato YYYY-MM-DD

    $sql = "SELECT dataEsperadaDevolucao FROM emprestimos WHERE idExemplar = ? AND dataDevolucao IS NULL";
    
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $idExemplar);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) > 0) {

            mysqli_stmt_bind_result($stmt, $dataEsperadaDevolucao);
            mysqli_stmt_fetch($stmt);
            if ($dataReserva >= $dataHoje && $dataReserva <= $dataEsperadaDevolucao) {
                return 1;
            } 
        } else {
          
        }

        mysqli_stmt_close($stmt);
        mysqli_close($link);
    }
    
    return 0;
}

function verificaReserva($idExemplar,$data){
    $link = mysqli_connect("localhost", "root", "udesc", "biblioteca");

if (!$link) {
    echo "Erro de conexão: " . mysqli_connect_error();
    return 1;
}

$sql = "SELECT COUNT(*) FROM reservas WHERE idExemplar = ? AND dataReserva = ?";

if ($stmt = $link->prepare($sql)) {

  
    $stmt->bind_param('is', $idExemplar, $data);

    $stmt->execute();
    $stmt->store_result();  

   
    $stmt->bind_result($count);
    $stmt->fetch();

    if ($count > 0) {
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

    
    $link->close();
    return 0;

}

function calcularNovaData($dataEmprestimo, $quantidadeSemanas) {
    $data = new DateTime($dataEmprestimo);
    $data->modify("+$quantidadeSemanas weeks");
    return $data->format('Y-m-d');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $idCliente  = $_POST['idCliente'];
    $idExemplar = $_POST['idExemplar'];
    $data       = $_POST['dataReserva'];

    echo $idCliente."<br>";
    echo $idExemplar."<br>";
    echo $data."<br>";

    if($idCliente == "" || $idExemplar == "" || $data == ""){
        $_SESSION['msg'] = "Preencha todos os campos corretamente";
        $_SESSION['msgCOD'] = 1;
        $_SESSION['codWhere'] = 2;
        header('Location: ../cliente/clienteLivros.php');
        exit;
    }


    $dataAtual = date('Y-m-d');


    //verificar se cliente existe
    $codVerificaCliente = verificaCliente($idCliente);

    switch ($codVerificaCliente) {
        case 1:
            $_SESSION['msg'] = "O id do cliente não existe";
            $_SESSION['msgCOD'] = 1;
            $_SESSION['codWhere'] = 2;
            header('Location: ../cliente/clienteLivros.php');
            exit;
    }

    $codVerificaExemplar = verificaExemplar($idExemplar,$data);

    switch ($codVerificaExemplar) {
        case 1:
            $_SESSION['msg'] = "O id do exemplar  não existe";
            $_SESSION['msgCOD'] = 1;
            $_SESSION['codWhere'] = 2;
            header('Location: ../cliente/clienteLivros.php');
            exit;
        case 2:
            $_SESSION['msg'] = "Esse exemplar já esta emprestado nessa data";
            $_SESSION['msgCOD'] = 1;
            $_SESSION['codWhere'] = 2;
            header('Location: ../cliente/clienteLivros.php');
            exit;
    }  
    // verificar se exemplar existe OK
        // Verificar se a data escolhida está no passado
  
    if ($data < $dataAtual) {
        $_SESSION['msg'] = "A data escohlida nao pode ser no passado";
        $_SESSION['msgCOD'] = 1;
        $_SESSION['codWhere'] = 2;
        header('Location: ../cliente/clienteLivros.php');
        exit;
    }

    if(verificaReserva($idExemplar,$data) == 1){
        $_SESSION['msg'] = "Já existe uma reserva desse exemplar";
        $_SESSION['msgCOD'] = 1;
        $_SESSION['codWhere'] = 2;
        header('Location: ../cliente/clienteLivros.php');
        exit;
    }
    //Verificar se ja existe uma reserva desse exemplar dessa data;
    
    $dataFinalReserva = calcularNovaData($data,1);
    // Cadastrar
    $link = mysqli_connect("localhost", "root", "udesc", "biblioteca");

    $stmt = mysqli_prepare($link, "INSERT INTO reservas (idExemplar, idCliente, dataReserva,dataFinalReserva) VALUES (?, ?, ?,?)");

    mysqli_stmt_bind_param($stmt, "iiss", $idExemplar, $idCliente, $data,$dataFinalReserva);


    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['msg'] = 'Reserva Cadastrada realizado com sucesso!';
        $_SESSION['msgCOD'] = 0;
        $_SESSION['codWhere'] = 2;
        // fecha statement e conectcao
        mysqli_stmt_close($stmt);
        mysqli_close($link);
        header('Location: ../cliente/clienteLivros.php');
        exit;
    } else {
        $_SESSION['msg'] = 'Erro ao cadastrar reserva';
        $_SESSION['msgCOD'] = 1;
        $_SESSION['codWhere'] = 2;
        mysqli_stmt_close($stmt);
        mysqli_close($link);
        header('Location: ../cliente/clienteLivros.php');
        exit;
    }

}

?>