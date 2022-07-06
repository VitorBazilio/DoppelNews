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
           }
    else{
               $sql = mysqli_query($link,"SELECT * FROM noticias ORDER BY Id_noticia");
   	            $usuario = mysqli_fetch_object($sql);
               if(!isset($_GET["id"]))
                  {
                     header("Location:../usu/perfil_usuario.php");
                  }
                  
                  else{
                      $nt =$_GET["id"]; 
                  $op = mysqli_query($link, "SELECT * FROM noticias where id_noticia = '$nt'");
                       $nt_at = mysqli_fetch_object($op);
?>
<html lang="pt-br">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
<link rel="icon" href="../img/top.ico" type="image/x-icon" style="font-size:6em;"/> <link rel="shortcut icon" href="./img/top.ico" type="image/x-icon" style="font-size:6em;"/> <link rel="stylesheet" href="../node_modules/bootstrap/compiler/bootstrap.css">
    <link rel="stylesheet" href="../node_modules/bootstrap/compiler/bootstyle.css">
    <link rel="stylesheet" href="../Fontawasome/Fontawasome/css/fontawesome-all.css">
    <title>Hellfier</title>
</head>

<body>

    <nav class="navbar navbar-expand-lg  navbar-dark bg-dark">

        <div class="container">



            <a class="navbar-brand h1 nb-0 " href="#">Hellfier</a>




            <button class="navbar-toggler " type="button" data-toggle="collapse" data-target="#navbarSite">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse   text-center" id="navbarSite">
                <div class=" ml-5 mr-auto">
                    <ul class=" navbar-nav mr-auto ml-5  ">
                        <li class="nav-item  ">
                            <a class="nav-link " id="orange" href="#">News</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="blue" href="#">Salas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="red" href="#">Personagens</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="gold" href="#">Ajuda</a>
                        </li>



                    </ul>
                </div>


                <form class="form-inline">
                    <input class="form-control ml-5 mr-3" type="search" placeholder="Buscar...">

                </form>
                <?php echo "<span id='orange'>"; echo "Olá, "; echo "</span>"; echo "<span class='orange'>"; echo  $_SESSION['nm_usuario']; echo "</span>";?>
                <ul class="navbar-nav ">

                    <li class="nav-item dropdown dropright">

                        <a class="nav-link dropdown-toggle component-active-bg " href="#" data-toggle="dropdown" id="navDrop">
                            <i class="fab fa-gitlab " style="font-size:36px;color:#ff550a;"></i>
                        </a>
                        <div class="dropdown-menu  ">
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#siteModalLg">Salas</a>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#siteModalCd">Personagens</a>
                            <a class="dropdown-item" href="#" data-toggle="desl" data-target="#sair">
                                <form action="sair.php" class="dropdown-item">
                                    <input type="submit" value="Sair" name="Sair" class="dropdown-item">
                                </form>
                            </a>

                        </div>
                    </li>

                </ul>

            </div>
        </div>


    </nav>

    <div class="container bg-dark my-3" id="orange">

        <div class="row">

            <div class="col-12 text-center my-3">
               
                    
                        <!-- Exibindo os valores que foram armazenados -->
                        
<?php
// Seleciona todos os usuários
$sql = mysqli_query($link,"SELECT * FROM noticias ORDER BY id_noticia");
 
// Exibe as informações de cada usuário
while ($usuario = mysqli_fetch_object($sql)) {
	// Exibimos a foto
	echo "
    <div class='d-flex p-1 mb-2 '>
            
     <div class='col-sm-6 col-md-4 '>
    <a href='../not_html/Dnd.php' class=''>
    
    <img src='../img/".$usuario->imgs."' alt='Foto de exibição' class='img-fluid'><br />
	</a>
    </div>
    ";
    // Exibimos o nome e email
	echo "
    <div class='col-sm-6 col-md-4 '>
    <a href='../not_html/Dnd.php'  class='float-left' id='orange'>
    $usuario->titu_not  <br />
   
    ";
	echo "
    
    $usuario->noticia_texto  <br /><br />
     </a>
    </div>
    </div>
    ";
}
?>

            </div>
        </div>
    </div>



    <footer class="col-lg-12  col-sm-9 text-center mt-5">
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

    <div class="modal fade back  " id="siteModalCd" tabindex="-1" role="dialog">
        <div class="modal-dialog " id="orange" role="document">
            <div class="modal-content bg-dark" id="orange">
                <div class="modal-header">
                    <h5 class="modal-title">Cadastrar</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>

                </div>
                <div class="modal-body back ">

                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-6">

                                <div class="form-group">
                                    <form method="get" action="../php/inserir.php" name="cadastro">
                                        <br><br>
                                        <label>Email:</label><input type="email" name="email" class="form-control" required id="email">
                                        <label>Senha:</label><input type="password" name="senha" class="form-control" required id="senha">
                                        <label>Confirmar Senha:</label><input type="password" name="confirmasenha" class="form-control" required id="confirmasenha">
                                        <br>
                                        <button type="submit" class="btn btn-dark form-inline" id="orange">Enviar</button>
                                    </form>

                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>


                </div>
            </div>
        </div>


    </div>

    <div class="modal fade back" id="siteModalLg" tabindex="-1" role="dialog">
        <div class="modal-dialog " id="orange" role="document">
            <div class="modal-content bg-dark">
                <div class="modal-header">
                    <h5 class="modal-title">Logar</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>

                </div>
                <div class="modal-body back ">

                    <div class="container-fluid back ">

                        <div class="row">
                            <div class="col-6">

                                <div class="form-group">
                                    <form method="get" action="../php/valida.php" name="login">
                                        <br><br>
                                        <label>Email:</label><input type="email" name="email" class="form-control" required id="email">
                                        <label>Senha:</label><input type="password" name="senha" class="form-control" required id="senha">
                                        <br>
                                        <button type="submit" class="btn btn-dark" id="orange">Entrar</button>
                                    </form>

                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>


                </div>
            </div>
        </div>


    </div>
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

    <?php }}}?>