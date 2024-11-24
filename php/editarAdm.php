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



if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    session_start();
    $idAdm = $_POST['id'];
    $nomeAdm = $_POST['nome'];
    $cpfAdm = $_POST['cpf'];
    
    $codVerificaCpf = verificaCPF($cpfAdm);

    $cpfAntigo = "";
    $link = mysqli_connect("localhost", "root", "udesc", "biblioteca");

    $stmt = mysqli_prepare($link, "SELECT cpf FROM bibliotecarios WHERE idBibliotecario = ?");

    // Bind the input parameter
    mysqli_stmt_bind_param($stmt, "i", $idAdm);
    mysqli_stmt_bind_result($stmt, $cpfAntigo);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($link);

    
    switch ($codVerificaCpf) {
        case 1:
            $_SESSION['msg'] = "Ulitilize um cpf valido na atualização";
            $_SESSION['msgCOD'] = 1;
            $_SESSION['codWhere'] = 3;
            header('Location: ../administrador/gerenciarUsuarios.php');
            exit;
        case 2:
            if($cpfAntigo != $cpfAdm){
                $_SESSION['msg'] = "Já existe um administrador com esse cpf";
                $_SESSION['msgCOD'] = 1;
                $_SESSION['codWhere'] = 3;
                header('Location: ../administrador/gerenciarUsuarios.php');
                exit;
            }
    }

    
    // abre
    $link = mysqli_connect("localhost", "root", "udesc", "biblioteca");

    $stmt = mysqli_prepare($link, "UPDATE bibliotecarios SET nome = ?, cpf = ? WHERE idBibliotecario = ?");

    mysqli_stmt_bind_param($stmt, "ssi", $nomeAdm, $cpfAdm, $idAdm);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['msg'] = 'Atualização realizada com sucesso!';
        $_SESSION['msgCOD'] = 2;
        $_SESSION['codWhere'] = 2;
        // fecha statement e conectcao
        mysqli_stmt_close($stmt);
        mysqli_close($link);
        header('Location: ../administrador/gerenciarUsuarios.php');
        exit;
    } else {
        $_SESSION['msg'] = 'Erro na atualização do administrador';
        $_SESSION['msgCOD'] = 1;
        $_SESSION['codWhere'] = 2;
        mysqli_stmt_close($stmt);
        mysqli_close($link);
        header('Location: ../administrador/gerenciarUsuarios.php');
        exit;
    }
  

}




?>