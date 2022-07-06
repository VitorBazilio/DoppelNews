<?php

session_start();
include_once("../php/conexao.php");
 $_SESSION['nm_usuario'];
if(!isset($_SESSION["email"]) || !isset($_SESSION["senha"])){
		header("Location: ../index.php");
		exit;
		}else{
			
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
                            <a class="dropdown-item" href="../sal/criar_sl.php">Criar Sala</a>

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
                <h1 class="display-2">Salas</h1>
                <p>Venha ver nossas salas de rpg ja criadas e abertas.</p>
            </div>

        </div>
        <div class="row">
            <?php 
    if(!isset($_GET['categoria'])){
            echo'
                <div class="col-12 text-center my-3">
                    <a href="salas.php?categoria=Novas" class="mx-4">Novas</a>
                    <a href="salas.php?categoria=Mestres"  class="mx-4">Mestres</a>
                    <a href="salas.php?categoria=Populares"  class="mx-4">Populares</a>
                </div>

            </div>
            
            
            ';
       
                    
// Seleciona todos os usuários
$sql = mysqli_query($link,"SELECT * FROM sala ORDER BY id_sl");
 
// Exibe as informações de cada usuário
while ($usuario = mysqli_fetch_object($sql)) {
	// Exibimos a foto
	echo "
    <table border='1' class='d-flex p-1 mb-2 '>
            
     <tr class='col-sm-6 col-md-4 col-lg-5'>
    
    
                        <th style='padding: 10px; text-align: center;' id='orange' class='mx-5'>Nome</th>
                        <th style='padding: 10px; text-align: center;' id='orange' class='mx-5'>
    Mestre</th>
    
    </tr>
    ";
    // Exibimos o nome e email
	echo "
    <tr class='col-sm-6 col-md-4 col-lg-7 bd-2 mr-2'>
    <th style='padding: 10px; text-align: center;' id='orange' class='mx-5'><a href='../sal/sala_mestre.php?id=".$usuario->id_sl."' text-center id='orange'>$usuario->nm_sala </a> </th>
   
    ";
	echo "
    <th style='padding: 10px; text-align: center;' id='orange' class='mx-5'><a href='../sal/sala_mestre.php?id=".$usuario->id_sl."' text-center id='orange'>
    $usuario->nm_criador </a> </th>
     </a>
    </table>
    ";
} 
        

        
        }else{
    if($_GET['categoria'] =='Novas'){
            echo'
                  <div class="col-12 text-center my-3">
                    <a href="salas.php" class="mx-4">Novas</a>
                    <a href="salas.php?categoria=Mestres"  class="mx-4">Mestres</a>
                    <a href="salas.php?categoria=Populares"  class="mx-4">Populares</a>
                </div>


            </div>
            ';
              } else{
    
          if($_GET['categoria']=='Mestres'){
            echo'
                 <div class="col-12 text-center my-3">
                    <a href="salas.php?categoria=Novas" class="mx-4">Novas</a>
                    <a href="salas.php"  class="mx-4">Mestres</a>
                    <a href="salas.php?categoria=Populares"  class="mx-4">Populares</a>
                </div>


            </div>
            ';
            
        }else{
                 if($_GET['categoria']=='Populares'){
            echo'
                  <div class="col-12 text-center my-3">
                    <a href="salas.php?categoria=Novas" class="mx-4">Novas</a>
                    <a href="salas.php?categoria=Mestres"  class="mx-4">Mestres</a>
                    <a href="salas.php"  class="mx-4">Populares</a>
                </div>

            </div>
            ';     
       }
        }}}     
                ?>

            <?php
    if(isset($_GET['categoria'])){
    if($_GET['categoria']=='Novas') {
    
        
        
// Seleciona todos os usuários
$sql = mysqli_query($link,"SELECT * FROM sala ORDER BY id_sl  ");
 
// Exibe as informações de cada usuário
while ($usuario = mysqli_fetch_object($sql)) {
	// Exibimos a foto
	echo "
    
      <div class='d-flex p-1 mb-2 '>      
     <div class='col-sm-6 col-md-4 col-lg-5'>
    <a href='../sal/sala_mestre.php?id=".$usuario->id_sl."' class='float-left' id='orange'>
    
    
    </div>
    ";
    // Exibimos o nome e email
	echo "
    <div class='col-sm-6 col-md-4 col-lg-7 bd-2 mr-2'>
    $usuario->nm_sala  
   
    ";
	echo "
    
    $usuario->nm_criador  
     </a> </div>
    </div>
    ";
} 
        
   
        
        
    }
        }
?>
            <?php
    if(isset($_GET['categoria'])){
    if($_GET['categoria']=='Mestres') {
   
// Seleciona todos os usuários
$sql = mysqli_query($link,"SELECT * FROM sala ORDER BY nm_criador");
 
// Exibe as informações de cada usuário
while ($usuario = mysqli_fetch_object($sql)) {
	// Exibimos a foto
	echo "
    <div class='d-flex p-1 mb-2 '>
            
     <div class='col-sm-6 col-md-4 col-lg-5'>
    <a href='../sala/".$usuario->nm_sala."' class='float-left' id='orange'>
    
    
    </div>
    ";
    // Exibimos o nome e email
	echo "
    <div class='col-sm-6 col-md-4 col-lg-7 bd-2 mr-2'>
    $usuario->nm_sala  
   
    ";
	echo "
    
    $usuario->nm_criador  
     </a> </div>
    </div>
    ";
} 
        
   
        
        
    }
        }
?>

            <?php
    if(isset($_GET['categoria'])){
    if($_GET['categoria']=='Populares') {
   
// Seleciona todos os usuários
$sql = mysqli_query($link,"SELECT * FROM sala ORDER BY nm_sala ");
 
// Exibe as informações de cada usuário
while ($usuario = mysqli_fetch_object($sql)) {
	// Exibimos a foto
	echo "
    <div class='d-flex p-1 mb-2 '>
            
     <div class='col-sm-6 col-md-4 col-lg-5'>
    <a href='../sala/".$usuario->nm_sala."' class='float-left' id='orange'>
    
    
    </div>
    ";
    // Exibimos o nome e email
	echo "
    <div class='col-sm-6 col-md-4 col-lg-7 bd-2 mr-2'>
    $usuario->nm_sala  
   
    ";
	echo "
    
    $usuario->nm_criador  
     </a> </div>
    </div>
    ";
} 
   
        
        
    }
        }
?>

        </div>
    </div>
    <footer class="col-lg-12  col-sm-9 text-center">
        <div class="row mn-2 bg-dark">
            <div class="col-lg-2 bg-dark">
                <ul class="list-group bg-dark" id="back-bg">
                    <li class="list-group-item bg-dark" id="orange">
                        <h3>News</h3>
                    </li>
                    <li class="list-group-item bg-dark" id="orange"><a href="">Games</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="">Anime</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="">Hqs</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="">Mangas</a></li>
                </ul>
            </div>
            <div class="col-lg-2">
                <ul class="list-group bg-dark" id="back-bg">
                    <li class="list-group-item bg-dark" id="orange">
                        <h3>Salas</h3>
                    </li>
                    <li class="list-group-item bg-dark" id="orange"><a href="">Novas</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="">Populares</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="">Mestres</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="">Sorte</a></li>
                </ul>
            </div>
            <div class="col-lg-2">
                <ul class="list-group bg-dark" id="back-bg">
                    <li class="list-group-item bg-dark" id="orange">
                        <h3>Personagens</h3>
                    </li>
                    <li class="list-group-item bg-dark" id="orange"><a href="">Meus</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="">Melhores</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="">Exemplos</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="">Populares</a></li>
                </ul>
            </div>
            <div class="col-lg-2">
                <ul class="list-group bg-dark" id="back-bg">
                    <li class="list-group-item bg-dark" id="orange">
                        <h3>Ajuda</h3>
                    </li>
                    <li class="list-group-item bg-dark" id="orange"><a href="">D&amp;D</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="">Livros</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="">Fale Conosco</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="">Perguntas</a></li>
                </ul>
            </div>
            <div class="col-lg-2">
                <ul class="list-group bg-dark" id="back-bg">
                    <li class="list-group-item bg-dark" id="orange">
                        <h3>Sobre Nos</h3>
                    </li>
                    <li class="list-group-item bg-dark" id="orange"><a href="">Facebook</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="">Instagram</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="">Twitter</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="">Sobre Nos</a></li>

                </ul>
            </div>
            <div class="col-lg-2">
                <ul class="list-group bg-dark" id="back-bg">
                    <li class="list-group-item bg-dark" id="orange">
                        <h3>Ajude-nos</h3>
                    </li>
                    <li class="list-group-item bg-dark" id="orange"><a href="">Facebook</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="">Instagram</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="">Twitter</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="">E-mail</a></li>

                </ul>
            </div>
        </div>
        <div class="container mt-2">
            <a href=""> © 2017-2018 Hellfier | LinkMcCall | Rafa_Lopi | Versão 1.0 <br>
                Todas as imagens de filmes, séries e etc são marcas registradas dos seus respectivos proprietários.</a>
        </div>
    </footer>


    <!--modal-->


    <script type="text/javascript">
        function validar() {
            var senha = cadastro.senha.value;
            var confirmasenha = cadastro.confirmasenha.value;
            if (senha != confirmasenha) {
                alert('Senhas diferentes');
                cadastro.confirmasenha.focus();
                return false;
            }

        }

    </script>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="../node_modules/jquery/dist/jquery.js"></script>
    <script src="../node_modules/popper.js/dist/umd/popper.js"></script>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.js"></script>

</body>

</html>
<?php }?>
