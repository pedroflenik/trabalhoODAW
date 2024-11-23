<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();
    
    // Retrieve input values
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $cpf = isset($_POST['cpf']) ? $_POST['cpf'] : '';
    $multado = isset($_POST['multa']) ? 1 : 0;

    // Connect to the database
    $link = mysqli_connect("localhost", "root", "udesc", "biblioteca");
    
    if (!$link) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $quantParams = 0;
    // SQL Query base
    $sql = "SELECT * FROM clientes WHERE";

    // Prepare the parameters array for binding
    $params = [];
    $types = '';  // We will dynamically build this

    // Add conditions based on the provided form values
    if ($nome != '') {
        $sql .= " nome LIKE ?";
        $params[] = "%$nome%";
        $types .= "s";  // "s" stands for string
        $quantParams = 1;
    }
    if($quantParams == 1){
        $sql .= " AND ";
    }

    if ($cpf != '') {
        $sql .= " cpf LIKE ?";
        $params[] = "%$cpf%";
        $types .= "s";  // "s" stands for string
        $quantParams = 2;
    }

    if($quantParams == 2){
        $sql .= " AND";
    }

    if ($multado == 1) {
        $sql .= " multa > 0";
    }else{
        $sql .= " multa = 0";
    }

    echo $sql;
    
    if($cpf == "" and $nome == ""){
        if ($stmt = $link->prepare($sql)) {

            $stmt->execute();
            
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $_SESSION['clientes'] = $result->fetch_all(MYSQLI_ASSOC);
            }
    
            // Close the statement
            $stmt->close();
        } else {
            $msg = "Erro na consulta ao banco de dados.";
            $msgcod = 1;
        }
    
        // Set the codWhere for view logic
        $codWhere = 3;
    
        // Store messages in session
        $_SESSION['msg'] = $msg;
        $_SESSION['msgCOD'] = $msgcod;
        $_SESSION['codWhere'] = $codWhere;
    
        // Redirect to the results page
        header('Location: ../administrador/gerenciarUsuarios.php');
        exit;
    }else{
        if ($stmt = $link->prepare($sql)) {
            // Dynamically bind parameters
            $stmt->bind_param($types, ...$params);
    
            // Execute the statement
            $stmt->execute();
            
            // Fetch the result
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                // Store the result in session
                $_SESSION['clientes'] = $result->fetch_all(MYSQLI_ASSOC);
            } 
    
            // Close the statement
            $stmt->close();
        } else {
            $msg = "Erro na consulta ao banco de dados.";
            $msgcod = 1;
        }
    
        // Set the codWhere for view logic
        $codWhere = 3;
    
        // Store messages in session
        $_SESSION['msg'] = $msg;
        $_SESSION['msgCOD'] = $msgcod;
        $_SESSION['codWhere'] = $codWhere;
    
        // Redirect to the results page
        header('Location: ../administrador/gerenciarUsuarios.php');
        exit;
    }

   
    
}
?>
