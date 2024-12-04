<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    unset($_SESSION['emprestimos']);
    $idExemplar = $_POST['idExemplar'];
    $idCliente = $_POST['idCliente'];
    $_SESSION['codWhere'] = 2;
    $pendente = isset($_POST['pendente']) ? true : false;


    echo "$idExemplar <br>";
    echo "$idCliente <br>";
    echo "$pendente <br>";
    $sql = "SELECT * FROM emprestimos WHERE 1=1"; 

    
    if ($pendente) {
        $sql .= " AND dataDevolucao IS NULL";
    } else {
        $sql .= " AND dataDevolucao IS NOT NULL";
    }

    $params = [];
    $types = '';

    
    if ($idExemplar != '') {
        $sql .= " AND idExemplar = ?";
        $params[] = $idExemplar;  
        $types .= "i"; 
    }

    if ($idCliente != '') {
        $sql .= " AND idCliente = ?";
        $params[] = $idCliente;  
        $types .= "i";
    }


    echo $sql;


    $link = mysqli_connect("localhost", "root", "udesc", "biblioteca");

    if (!$link) {
        die("Erro na conexão: " . mysqli_connect_error());
    }

    if ($stmt = $link->prepare($sql)) {

        if (count($params) > 0) {
            $stmt->bind_param($types, ...$params);
        }


        $stmt->execute();
        

        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $_SESSION['emprestimos'] = $result->fetch_all(MYSQLI_ASSOC); 
        } else {
            $_SESSION['msg'] = 'Nenhum empréstimo encontrado com os critérios fornecidos.';
            $_SESSION['msgCOD'] = 1;
        }

        // Ffecha
        $stmt->close();
    } else {
        $_SESSION['msg'] = 'Erro na consulta ao banco de dados.';
        $_SESSION['msgCOD'] = 1;
    }

    // fecha
    mysqli_close($link);

    // volta
    header('Location: ../administrador/emprestimos.php');
    exit;
}

?>