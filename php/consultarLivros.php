<?php



if($_SERVER['REQUEST_METHOD'] === 'POST'){
    session_start();
    unset($_SESSION['livros']);
    $_SESSION['codWhere'] = 2;

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
    echo "ISBN: " . htmlspecialchars($isbn) . "<br>";
    echo "Title: " . htmlspecialchars($titulo) . "<br>";
    echo "Author: " . htmlspecialchars($autor) . "<br>";
    echo "Publisher: " . htmlspecialchars($editora) . "<br>";
    echo "Edition: " . htmlspecialchars($edicao) . "<br>";

    echo "genero: " . htmlspecialchars($genero) . "<br>";

    $sql = "SELECT * FROM livros INNER JOIN generos  ON livros.genero_id = generos.idGenero WHERE 1";
    $params = [];
    $types = '';

    
    if ($isbn != '') {
        $sql .= " AND isbn = ?";
        $params[] = $isbn;  
        $types .= "s"; 
    }

    if ($titulo != '') {
        $sql .= " AND titulo LIKE ?";
        $params[] = "%$titulo%";  
        $types .= "s";
    }

    if ($autor != '') {
        $sql .= " AND autor LIKE ?";
        $params[] = "%$autor%";
        $types .= "s";
    }

    if ($editora != '') {
        $sql .= " AND editora LIKE ?";  
        $params[] = "%$editora%";
        $types .= "s";
    }

    if ($edicao != '') {
        $sql .= " AND edicao = ?";
        $params[] = $edicao;  
        $types .= "i";  
    }

    if ($genero != '') {
        $sql .= " AND genero_id = ?";
        $params[] = $genero;  
        $types .= "i";
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
            $_SESSION['livros'] = $result->fetch_all(MYSQLI_ASSOC);
        }

        $stmt->close();
    } else {
        $msg = "Erro na consulta ao banco de dados.";
        $msgcod = 1;
    }


    $_SESSION['msg'] = $msg;
    $_SESSION['msgCOD'] = $msgcod;
    $_SESSION['codWhere'] = $codWhere; 

    header('Location: ../administrador/gerenciarLivros.php');
    exit;


}




?>