<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();
    
   
    $nome      = isset($_POST['nome']) ? $_POST['nome'] : '';
    $cpf       = isset($_POST['cpf']) ? $_POST['cpf'] : '';
    $idCliente = ($_POST['idCliente']);


    $link = mysqli_connect("localhost", "root", "udesc", "biblioteca");
    
    if (!$link) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $quantParams = 0;
    // SQL Query base
    $sql = "SELECT * FROM clientes WHERE multa > 0";

    $params = [];
    $types = '';  

    if ($nome != '') {
        $sql .= " AND nome LIKE ?";
        $params[] = "%$nome%";
        $types .= "s";  
        $quantParams = 1;
    }

    if ($cpf != '') {
        $sql .= " AND cpf LIKE ?";
        $params[] = "%$cpf%";
        $types .= "s";  
        $quantParams = 2;
    }

    
    if ($idCliente != '') {
        $sql .= " AND idCliente = ?";
        $params[] = $idCliente;
        $types .= "i";  
        $quantParams = 2;
    }


    echo $sql;
    
    $link = mysqli_connect("localhost", "root", "udesc", "biblioteca");

    if (!$link) {
        die("Erro na conexão: " . mysqli_connect_error());
    }

    echo $sql;  

    
    if ($stmt = $link->prepare($sql)) {
        
        if (count($params) > 0) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $_SESSION['clienteMulta'] = $result->fetch_all(MYSQLI_ASSOC);
        }

        $stmt->close();
    } else {
        $msg = "Erro na consulta ao banco de dados.";
        $msgcod = 1;
    }


    $_SESSION['msg'] = $msg;
    $_SESSION['msgCOD'] = $msgcod;
    $_SESSION['codWhere'] = $codWhere; 

    header('Location: ../administrador/multas.php');
    exit;

   
    
}
?>
