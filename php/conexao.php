<html>
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
<?php
$servidor ="localhost"; 
$usuario ="root";
$senha = "5465LINK"; 
$banco ="doppel"; 
//Criar a conexao
    $link = mysqli_connect($servidor, $usuario, $senha, $banco);  /*query executa, connect conecta*/  
    if(!$link){
        die("Falha na conexao: " . mysqli_connect_error());
    }else{
       //echo "Conexao realizada com sucesso";
    }  
?>

</body>
</html>