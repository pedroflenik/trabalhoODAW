<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Biblioteca - Login</title>

    <link rel="stylesheet" href="css/login.css">
    <!--Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
   
    <div class="divide colunaEsquerda">
        <div class="center">
          <h2>Nome da biblioteca</h2>
        </div>
    </div>

    <div class="divide colunaDireita">
        <div class="centered">
            <form action="">
                <label for="">CPF:</label><br>
                <input type="text"><br>
                <label for="">Senha:</label><br>
                <input type="passsword"><br>
                <input class="btn btn-primary" type="submit" value="Entrar"><br>
            </form>
            
            <a href="cadastro.php"><button class="btn btn-primary">Cadastro</button></a>
        </div>
    </div>

</body>
</html>