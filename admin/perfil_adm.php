<?php

session_start();

include_once("../php/conexao.php");
 $_SESSION['nm_usuario'];
if(!isset($_SESSION["email"]) || !isset($_SESSION["senha"])){
		header("Location: ../index.php");
		exit;
		}else{
   	if( $_SESSION["nivel_da_conta"]==0)
    {
         header("Location: ../usu/perfil_usuario.php");
        
        
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
    <style>

        .lock_not{
     
    width:428px;
    height: 277px;
    
}
        
        footer{
            
            bottom: 0;
        }
        

        </style>
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
                    <input class="form-control ml-5 mr-3" type="search" placeholder="Buscar...">

                </form>
                <?php echo "<span id='orange'>"; echo "Olá,  "; echo "</span>"; echo "<span id='orange'>"; echo  $_SESSION['nm_usuario']; echo "</span>";?>
                <ul class="navbar-nav ">

                    <li class="nav-item dropdown dropright">

                        <a class="nav-link dropdown-toggle component-active-bg " href="#" data-toggle="dropdown" id="navDrop">
                            <i class="fab fa-gitlab " style="font-size:36px;color:#ff550a;"></i>
                        </a>
                        <div class="dropdown-menu  ">
                            <a class="dropdown-item" href="criar_not.php">Criar notícia</a>
                            <a href="../crud/pagina-alterar.php" class="dropdown-item">Atualizar</a>
                            <a Href="../crud/pagina-excluir.php" class="dropdown-item">Deletar</a>
                            <a href="../crud/pagina-listagem-dados.php" class="dropdown-item">Listar</a>
                            <a href="../crud/pagina-cadastro.php" class="dropdown-item">Inserir</a>
                            <a href="../dnd/criar_rg.php" class="dropdown-item">Regras</a>
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
                <h1 class="display-2">Conteúdo Geek</h1>
                <p>Tudo sobre games, animes e muito mais.</p>
            </div>


        </div>

        <?php
// Seleciona todos os usuários
$sql = mysqli_query($link,"SELECT * FROM noticias ORDER BY id_noticia ");
 
// Exibe as informações de cada usuário
while ($usuario = mysqli_fetch_object($sql)) {
	// Exibimos a foto
	echo "
    <div class='d-flex p-1 my-2'>
            
     <div class='col-sm-6 col-md-4 col-lg-5'>
    <a href='../not_html/".$usuario->local."' class=''>
    
    <img src='../img/".$usuario->imgs."' alt='Foto de exibição' class='img-fluid lock_not'><br />
	</a>
    </div>
    ";
    // Exibimos o nome e email
	echo "
    <div class='col-sm-6 col-md-4 col-lg-7'>
    <a href='../not_html/".$usuario->local."'  class='float-left' id='orange'>
    $usuario->titu_not  <br>
   
    ";
	echo "
    
    $usuario->manchete  <br><br>
     </a>
    </div>
    </div>
    ";
}
?>


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
            <a href="dir_aut"> Todas as imagens de filmes, séries e etc são marcas registradas dos seus respectivos proprietários.</a>
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
<?php } }?>
