<?php

session_start();
include_once("../php/conexao.php");
 $_SESSION['nm_usuario'];
if(!isset($_SESSION["email"]) || !isset($_SESSION["senha"])){
		header("Location: ../index.php");
		exit;
		}else{
   	       if( $_SESSION["nivel_da_conta"]==5){
         header("Location: ../admin/perfil_adm.php");
        
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
    
    <?php
    echo"
    <style>
     #foot{
            position: absolute; 
            bottom: 0;
        }
  

        .lock_not{
     
    width:428px;
    height: 277px;
    
}

        
        </style>
        
        ";
        ?>
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
                            <a class="nav-link " id="orange" href="../not_html/mostrar_not.php">News</a>
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

    <div id="carouselSite" class="carousel slide" data-ride="carousel">

        <ol class="carousel-indicators">
            <li data-target="#carouselSite" data-slide-to="0" class="active"></li>
            <li data-target="#carouselSite" data-slide-to="1"></li>
            <li data-target="#carouselSite" data-slide-to="2"></li>
        </ol>

        <div class="carousel-inner">

            <div class="carousel-item active">

                <img src="../img/cp.jpg" class="img-fluid d-block">
                <div class="carousel-caption d-none d-md-block">

                    <h1>Cyberpunk 2077</h1>
                    <p class="lead"> A CD Projekt Red não quis confirmar (ainda) os veículos voadores!</p>

                </div>
            </div>
            <div class="carousel-item">

                <img src="../img/god2.jpg" class="img-fluid d-block">
                <div class="carousel-caption d-none d-md-block">

                    <h1>God of War</h1>
                    <p class="lead">Foi revelado que God of War terá um modo New Game+</p>

                </div>
            </div>
            <div class="carousel-item">

                <img src="../img/zel.jpg" class="img-fluid d-block">
                <div class="carousel-caption d-none d-md-block">

                    <h1>The Legend of Zelda</h1>
                    <p class="lead">O produtor Eiji Aonuma confirmou que um novo The Legend of Zelda já está em produção!</p>

                </div>
            </div>
        </div>

        <a class="carousel-control-prev" href="#carouselSite" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon"></span>
            <span class="sr-only">Anterior</span>

        </a>

        <a class="carousel-control-next" href="#carouselSite" role="button" data-slide="next">
            <span class="carousel-control-next-icon"></span>
            <span class="sr-only">Proximo</span>

        </a>



    </div>


    <div class="container bg-dark my-3" id="orange">

        <div class="row">

            <div class="col-12 text-center my-3">
                <h1 class="display-2">Conteúdo Geek</h1>
                <p>Tudo sobre games, animes e muito mais.</p>
            </div>

        </div>


        <div class="row">
            <?php 
    if(!isset($_GET['categoria'])){
            echo'
                <div class="col-12 text-center my-3">
                    <a href="perfil_usuario.php?categoria=Games" class="mx-4">Games</a>
                    <a href="perfil_usuario.php?categoria=Anime"  class="mx-4">Animes</a>
                    <a href="perfil_usuario.php?categoria=Hqs"  class="mx-4">Hqs</a>
                    <a href="perfil_usuario.php?categoria=Manga"  class="mx-4">Manga</a>
                </div>

            </div>
            
            
            ';
// Seleciona todos os usuários
$sql = mysqli_query($link,"SELECT * FROM noticias ORDER BY Id_noticia");
 
// Exibe as informações de cada usuário
while ($usuario = mysqli_fetch_object($sql)) {
	// Exibimos a foto
	echo "
    <div class='d-flex p-1 mb-2 '>
            
     <div class='col-sm-6 col-md-4 col-lg-5'>
    <a href='../not_html/mostrar_not.php?id=".$usuario->id_noticia."' class=''>
    
    <img src='../img/".$usuario->imgs."' alt='Foto de exibição' class='img-fluid lock_not'><br />
	</a>
    </div>
    ";
    // Exibimos o nome e email
	echo "
    <div class='col-sm-6 col-md-4 col-lg-7'>
    <a href='../not_html/mostrar_not.php?id=".$usuario->id_noticia."'  class='float-left' id='orange'>
    $usuario->titu_not  <br>
   
    ";
	echo "
    
    $usuario->manchete  <br><br>
     </a>
    </div>
    </div>
    ";
} 
        

        
        }else{
    if($_GET['categoria'] =='Games'){
            echo'
                <div class="col-12 text-center my-3">
                    <a href="perfil_usuario.php"  class="mx-4">Games</a>
                    <a href="perfil_usuario.php?categoria=Anime"  class="mx-4">Animes</a>
                    <a href="perfil_usuario.php?categoria=Hqs"  class="mx-4">Hqs</a>
                    <a href="perfil_usuario.php?categoria=Manga"  class="mx-4">Manga</a>
                </div>

            </div>
            ';
              } else{
    
          if($_GET['categoria']=='Anime'){
            echo'
                <div class="col-12 text-center my-3">
                    <a href="perfil_usuario.php?categoria=Games" " class="mx-4">Games</a>
                    <a href="perfil_usuario.php" " class="mx-4">Animes</a>
                    <a href="perfil_usuario.php?categoria=Hqs" " class="mx-4">Hqs</a>
                    <a href="perfil_usuario.php?categoria=Manga"  class="mx-4">Manga</a>
                </div>

            </div>
            ';
            
        }else{
                 if($_GET['categoria']=='Hqs'){
            echo'
                <div class="col-12 text-center my-3">
                    <a href="perfil_usuario.php?categoria=Games"  class="mx-4">Games</a>
                    <a href="perfil_usuario.php?categoria=Anime"  class="mx-4">Animes</a>
                    <a href="perfil_usuario.php"  class="mx-4">Hqs</a>
                    <a href="perfil_usuario.php?categoria=Manga"  class="mx-4">Manga</a>
                </div>

            </div>
            ';     
       }else{
                     
                         if($_GET['categoria']=='Mangas'){
            echo'
                <div class="col-12 text-center my-3">
                    <a href="perfil_usuario.php?categoria=Games"  class="mx-4">Games</a>
                    <a href="perfil_usuario.php?categoria=Anime"  class="mx-4">Animes</a>
                    <a href="perfil_usuario.php?categoria=Hqs"  class="mx-4">Hqs</a>
                    <a href="perfil_usuario.php"  class="mx-4">Manga</a>
                </div>

            </div>
            '; 
        }
        }}}}     
                ?>


            <?php
    if(isset($_GET['categoria'])){
    if($_GET['categoria']=='Games') {
   
// Seleciona todos os usuários
$sql = mysqli_query($link,"SELECT * FROM noticias WHERE tema LIKE 'Games' ");
 
// Exibe as informações de cada usuário
while ($usuario = mysqli_fetch_object($sql)) {
	// Exibimos a foto
	echo "
    <div class='d-flex p-1 mb-2 '>
            
     <div class='col-sm-6 col-md-4 col-lg-5'>
    <a href='../not_html/mostrar_not.php?id=".$usuario->id_noticia."' class=''>
    
    <img src='../img/".$usuario->imgs."' alt='Foto de exibição' class='img-fluid'><br />
	</a>
    </div>
    ";
    // Exibimos o nome e email
	echo "
    <div class='col-sm-6 col-md-4 col-lg-7'>
    <a href='../not_html/mostrar_not.php?id=".$usuario->id_noticia."'  class='float-left' id='orange'>
    $usuario->titu_not  <br>
   
    ";
	echo "
    
    $usuario->manchete  <br><br>
     </a>
    </div>
    </div>
    ";
}        
    }

        }
?>
            <?php
    if(isset($_GET['categoria'])){
    if($_GET['categoria']=='Anime') {
    
// Seleciona todos os usuários
$sql = mysqli_query($link,"SELECT * FROM noticias WHERE tema LIKE 'Anime' ");
 
// Exibe as informações de cada usuário
while ($usuario = mysqli_fetch_object($sql)) {
	// Exibimos a foto
	echo "
    <div class='d-flex p-1 mb-2 '>
            
     <div class='col-sm-6 col-md-4 col-lg-5'>
    <a href='../not_html/mostrar_not.php?id=".$usuario->id_noticia."' class=''>
    
    <img src='../img/".$usuario->imgs."' alt='Foto de exibição' class='img-fluid'><br />
	</a>
    </div>
    ";
    // Exibimos o nome e email
	echo "
    <div class='col-sm-6 col-md-4 col-lg-7'>
    <a href='../not_html/mostrar_not.php?id=".$usuario->id_noticia."'  class='float-left' id='orange'>
    $usuario->titu_not  <br>
   
    ";
	echo "
    
    $usuario->manchete  <br><br>
     </a>
    </div>
    </div>
    ";
} 
    }
    }
?>
            <?php
    if(isset($_GET['categoria'])){
    if($_GET['categoria']=='Hqs') {
    
// Seleciona todos os usuários
$sql = mysqli_query($link,"SELECT * FROM noticias WHERE tema LIKE 'Hqs' ");
 
// Exibe as informações de cada usuário
while ($usuario = mysqli_fetch_object($sql)) {
	// Exibimos a foto
	echo "
    <div class='d-flex p-1 mb-2 '>
            
     <div class='col-sm-6 col-md-4 col-lg-5'>
    <a href='../not_html/mostrar_not.php?id=".$usuario->id_noticia."' class=''>
    
    <img src='../img/".$usuario->imgs."' alt='Foto de exibição' class='img-fluid'><br />
	</a>
    </div>
    ";
    // Exibimos o nome e email
	echo "
    <div class='col-sm-6 col-md-4 col-lg-7'>
    <a href='../not_html/mostrar_not.php?id=".$usuario->id_noticia."'  class='float-left' id='orange'>
    $usuario->titu_not  <br>
   
    ";
	echo "
    
    $usuario->manchete  <br><br>
     </a>
    </div>
    </div>
    ";
} 
    }
    }
?>
            <?php
    if(isset($_GET['categoria'])){
    if($_GET['categoria']=='Manga') {
    
// Seleciona todos os usuários
$sql = mysqli_query($link,"SELECT * FROM noticias WHERE tema LIKE 'Manga' ");
 
// Exibe as informações de cada usuário
while ($usuario = mysqli_fetch_object($sql)) {
	// Exibimos a foto
	echo "
    <div class='d-flex p-1 mb-2 '>
            
     <div class='col-sm-6 col-md-4 col-lg-5'>
    <a href='../not_html/mostrar_not.php?id=".$usuario->id_noticia."' class=''>
    
    <img src='../img/".$usuario->imgs."' alt='Foto de exibição' class='img-fluid'><br />
	</a>
    </div>
    ";
    // Exibimos o nome e email
	echo "
    <div class='col-sm-6 col-md-4 col-lg-7'>
    <a href='../not_html/mostrar_not.php?id=".$usuario->id_noticia."'  class='float-left' id='orange'>
    $usuario->titu_not  <br>
   
    ";
	echo "
    
    $usuario->manchete  <br><br>
     </a>
    </div>
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
                    <li class="list-group-item bg-dark" id="orange"><a href="perfil_usuario.php?categoria=Games">Games</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="perfil_usuario.php?categoria=Anime">Anime</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="perfil_usuario.php?categoria=Hqs">Hqs</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="perfil_usuario.php?categoria=Manga">Mangas</a></li>
                </ul>
            </div>
            <div class="col-lg-2">
                <ul class="list-group bg-dark" id="back-bg">
                    <li class="list-group-item bg-dark" id="orange">
                        <h3>Salas</h3>
                    </li>
                    <li class="list-group-item bg-dark" id="orange"><a href="../sal/salas.php?categoria=Novas">Novas</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="../sal/salas.php?categoria=Populares">Populares</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="../sal/salas.php?categoria=Mestres">Mestres</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="../sal/salas.php?categoria=Sorte">Sorte</a></li>
                </ul>
            </div>
            <div class="col-lg-2">
                <ul class="list-group bg-dark" id="back-bg">
                    <li class="list-group-item bg-dark" id="orange">
                        <h3>Personagens</h3>
                    </li>
                    <li class="list-group-item bg-dark" id="orange"><a href="../perso/persons.php">Meus</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="../perso/persons.php">Melhores</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="../perso/persons.php">Exemplos</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="../perso/persons.php">Populares</a></li>
                </ul>
            </div>
            <div class="col-lg-2">
                <ul class="list-group bg-dark" id="back-bg">
                    <li class="list-group-item bg-dark" id="orange">
                        <h3>Ajuda</h3>
                    </li>
                    <li class="list-group-item bg-dark" id="orange"><a href="../help/Ajuda.php">D&amp;D</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="../help/Ajuda.php">Livros</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="../help/Ajuda.php">Fale Conosco</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="../help/Ajuda.php">Perguntas</a></li>
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
                    <li class="list-group-item bg-dark" id="orange"><a href="https://www.facebook.com/Hellfier-353155698584987/">Facebook</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="https://www.instagram.com/invites/contact/?utm_medium=user_system_sheet&utm_campaign=default.ig_ci_landing_site_0621.control&utm_source=ig_contact_invite&utm_content=42xg8k8">Instagram</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="https://twitter.com/HellfierO">Twitter</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="../help/Ajuda.php?categoria=hl">E-mail</a></li>

                </ul>
            </div>
        </div>
        <div class="container mt-2">
            <a href=""> © 2017-2018 Hellfier | LinkMcCall | Rafa_Lopi | Versão 1.0 <br></a>
            <a href="../help/dir_aut.php"> Todas as imagens de filmes, séries e etc são marcas registradas dos seus respectivos proprietários.</a>
        </div>
    </footer>


    <!--script-->


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
<?php } }?>
