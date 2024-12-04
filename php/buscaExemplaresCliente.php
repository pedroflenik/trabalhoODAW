<?php



if($_SERVER['REQUEST_METHOD'] === 'GET'){
    session_start();
    unset($_SESSION['exemplares']);
    $_SESSION['codWhere'] = 3;

    $isbn = $_GET['isbn'];


    echo "ISBN: " . htmlspecialchars($isbn) . "<br>";
    

    $sql = "SELECT e.idExemplar, e.isbn,l.titulo, l.autor, l.editora, l.edicao, g.genero
    FROM exemplares e
    INNER JOIN livros l ON e.isbn = l.isbn
    INNER JOIN generos g ON l.genero_id = g.idGenero 
    ";
    $params = [];
    $types = '';

    
    if ($isbn != '') {
        $sql .= " WHERE l.isbn = ?";
        $params[] = $isbn;  
        $types .= "s"; 
    }


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
            $_SESSION['exemplares'] = $result->fetch_all(MYSQLI_ASSOC);
        }

        $stmt->close();
    } else {
        $msg = "Erro na consulta ao banco de dados.";
        $msgcod = 1;
    }


    $_SESSION['msg'] = $msg;
    $_SESSION['msgCOD'] = $msgcod;
    $_SESSION['codWhere'] = $codWhere; 

    header('Location: ../cliente/clienteLivros.php');
    exit;


}




?>