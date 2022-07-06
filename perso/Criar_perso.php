<?php

session_start();
include_once("../php/conexao.php");
$_SESSION['nm_usuario'];
if(!isset($_SESSION["email"]) || !isset($_SESSION["senha"])){
		header("Location: ../index.php");
		exit;
		}else{ 
    
        if(!isset($_GET['cod'])) {
            header("Location: ../sal/salas.php");
            exit;
        } else {
?>
<html lang="pt-br">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="icon" href="../img/top.ico" type="image/x-icon" style="font-size:6em;" />
    <link rel="shortcut icon" href="../img/top.ico" type="image/x-icon" style="font-size:6em;" />
    <link rel="stylesheet" href="../node_modules/bootstrap/compiler/bootstrap.css">
    <link rel="stylesheet" href="../node_modules/bootstrap/compiler/bootstyle.css">
    <link rel="stylesheet" href="../Fontawasome/Fontawasome/css/fontawesome-all.css">
    <title>Hellfier</title>
</head>

<body>

    <nav class="navbar navbar-expand-lg  navbar-dark bg-dark">

        <div class="container">



            <a class="navbar-brand h1 nb-0 " href="../index.php">Hellfier</a>




            <button class="navbar-toggler " type="button" data-toggle="collapse" data-target="#navbarSite">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse   text-center" id="navbarSite">
                <div class=" ml-5 mr-auto">
                    <ul class=" navbar-nav mr-auto ml-5  ">
                        <li class="nav-item  ">
                            <a class="nav-link " id="orange" href="../not_html/news.php">News</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="blue" href="../sal/salas.php">Salas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="red" href="../perso/persons.php">Personagens</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="gold" href="../help/ajuda.php">Ajuda</a>
                        </li>



                    </ul>
                </div>


                <form class="form-inline">
                    <input class="form-control ml-5 mr-3 my-1 mt-2" type="search" placeholder="Buscar...">

                </form>
                <?php echo "<span id='orange'>"; echo "Olá,  "; echo "</span>"; echo "<span id='orange'>"; echo  $_SESSION['nm_usuario']; echo "</span>";?>
                <ul class="navbar-nav ">

                    <li class="nav-item dropdown dropright">

                        <a class="nav-link dropdown-toggle component-active-bg " href="#" data-toggle="dropdown" id="navDrop">
                            <i class="fab fa-gitlab " style="font-size:36px;color:#ff550a;"></i>
                        </a>
                        <div class="dropdown-menu mr-2">
                            <a class="dropdown-item" href="../sal/salas.php">Salas</a>
                            <a class="dropdown-item" href="../perso/persons.php">Personagens</a>

                            <form action="../crud/sair.php">
                                <input type="submit" value="Sair" name="Sair" class="dropdown-item">
                            </form>

                        </div>
                    </li>

                </ul>

            </div>
        </div>


    </nav>


    <div class="container bg-dark my-3" id="orange">

        <div class="row">

            <div class="col-12 text-center my-3">
                <h1 class="display-2">Criar personagem</h1>
                <p>Tudo pro seu universo. Coloque:</p>
            </div>

        </div>




        <div class='d-flex p-1 mb-2 '>
            <div class='col-sm-6 col-md-4 col-lg-5'>
                <?php
    echo'
                <form action="../perso/inserir_f.php" method="POST">'
                    ?>
                    
                    <code class="bg-dark"> Insira o nome da sala que quer entrar e o seu email por favor.</code><br>
                    Email:<input type="text" name="op" class="form-control d-none" value="<?php echo $_SESSION['email']; ?>">
                    <?php 
                    $id_sala = $_GET['cod'];
                    $select_sala = mysqli_query($link, "select * from sala where id_sl= '$id_sala'");
                    $result_sala = mysqli_fetch_object($select_sala);
                    ?>
                    Nome da sala:<input type="text" name="sl" class="form-control" value="<?php echo $result_sala->nm_sala; ?>"><br>
                    <code class="bg-dark"> Obrigado.</code><br>
                    
                    Nome:<input type="text" name="nm" class="form-control" >


                    Especie:<input list="Especie" name="raca" class="form-control" autocomplete="off">
                    <datalist id="Especie">
                        <?php 
                        
                        $sql = mysqli_query($link,"SELECT * FROM especie ");
 
                        // Exibe as informações de cada usuário
                        while ($usuario = mysqli_fetch_object($sql)) {
                            
                            
                            echo '<option value="'.$usuario->nm_especie.'">';
                            
                        }
                        
                        ?>


                    </datalist>

                    Classe:<input list="Classe" name="classe" class="form-control" autocomplete="off">
                    <datalist id="Classe">
                        <?php 
                        
                        $sql = mysqli_query($link,"SELECT * FROM classe LIMIT 13");
                        $n=1;
                        // Exibe as informações de cada usuário
                        while ($usuario = mysqli_fetch_object($sql) and $n<14) {
                            
                            
                            echo '<option value="'.$usuario->nm_classe.'">';
                            
                        }
                        
                        ?>


                    </datalist>
                        
                        Regras Classe:<input list="rg"  class="form-control" autocomplete="off">
                    <datalist id="rg">
                        <?php 
                       $sql = mysqli_query($link,"SELECT * FROM classe ");
 
                        // Exibe as informações de cada usuário
                        while ($usuario = mysqli_fetch_object($sql)) {
                            
                            
                            echo '<option value="'.$usuario->descricao.'">';
                            
                        }
                        ?>
                        
                    </datalist><br>
                    <code class="bg-dark">Esta em ordem como as da classe, escolha somente uma arma</code><br>

                    Arma Simples:<input list="Arma" name="arma" class="form-control" autocomplete="off">
                    <datalist id="Arma">
                        <?php 
                       $sql = mysqli_query($link,"SELECT * FROM arma where ASS LIKE '1' ");
 
                        // Exibe as informações de cada usuário
                        while ($usuario = mysqli_fetch_object($sql)) {
                            
                            
                            echo '<option value="'.$usuario->nm_arma.'">';
                            
                        }
                        ?>
                        
                        

                    </datalist>

                    Arma Macial:<input list="Armam" name="armam" class="form-control" autocomplete="off">
                    <datalist id="Armam">
                        <?php 
                       $sql = mysqli_query($link,"SELECT * FROM arma where AM LIKE '1' ");
 
                        // Exibe as informações de cada usuário
                        while ($usuario = mysqli_fetch_object($sql)) {
                            
                            
                            echo '<option value="'.$usuario->nm_arma.'">';
                            
                        }
                        ?>

                    </datalist>
                    <br>
                     <code class="bg-dark">Voce tem 27 Pontos pra distribuir, será verificado se por a mais...considere os 8 que ja estão no quadro...A soma final deve dar 75.</code><br>
                    Força:<input type="number" name="fc" value="8" class="form-control">
                    Destreza:<input type="number" name="ds" value="8" class="form-control">
                    Constituição:<input type="number" name="cs" value="8" class="form-control">
                    Inteligencia:<input type="number" name="it" value="8" class="form-control">
                    Sabedoria:<input type="number" name="sb" value="8" class="form-control">
                    Carisma:<input type="number" name="cr" value="8" class="form-control"><br>

                   <input type="submit" class="btn btn-dark form-inline" id="orange" value="Enviar">
                   <?php
    
                echo "</form>"
                    ?>
            </div>
        </div>
    </div>

</body>

</html>
<?php }}?>
