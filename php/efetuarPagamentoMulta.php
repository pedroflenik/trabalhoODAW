
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();
    
    $idCliente = $_POST['clienteIdMulta'];
    $multa     = $_POST['multa'];
    $quantidadePagar = $_POST['quantPagar'];

    echo $idCliente."<br>";
    echo $multa."<br>";
    echo $quantidadePagar."<br>";

    $novoValor = $multa - abs($quantidadePagar);

    if($novoValor <= 0){
        $novoValor = 0;
    }

    $link = mysqli_connect("localhost", "root", "udesc", "biblioteca");

    $stmt = mysqli_prepare($link, "UPDATE clientes SET multa = ? WHERE idCliente = ?");
   
    mysqli_stmt_bind_param($stmt, "di",$novoValor,$idCliente );


    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['msg'] = 'Pagamento de multa realizado com sucesso!';
        $_SESSION['msgCOD'] = 0;
        $_SESSION['codWhere'] = 1;
        mysqli_stmt_close($stmt);
        mysqli_close($link);
        header('Location: ../administrador/multas.php');
        exit;
    } else {
        $_SESSION['msg'] = 'Erro ao pagar multa';
        $_SESSION['msgCOD'] = 1;
        $_SESSION['codWhere'] = 1;
        mysqli_stmt_close($stmt);
        mysqli_close($link);
        header('Location: ../administrador/gerenciarLivros.php');
        exit;
    }

}
    ?>