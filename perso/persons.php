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
                    <input class="form-control ml-5 mr-3 mt-2" type="search" placeholder="Buscar...">

                </form>
                <ul class="navbar-nav ">

                    <li class="nav-item dropdown dropright">

                        <a class="nav-link dropdown-toggle component-active-bg " href="#" data-toggle="dropdown" id="navDrop">
                            <i class="fab fa-gitlab " style="font-size:36px;color:#ff550a;"></i>
                        </a>
                        <div class="dropdown-menu mr-2">
                            <a class="dropdown-item" href="../sal/salas.php">Salas</a>
                            <a class="dropdown-item" href="../perso/persons.php">Personagens</a>
                            <a class="dropdown-item" href="../perso/criar_perso.php">Criar Personagem</a>

                            <form action="../crud/sair.php">
                                <input type="submit" value="Sair" name="Sair" class="dropdown-item">
                            </form>
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
                <h1 class="display-2">Personagens</h1>
                <p></p>
            </div>

        </div>

        <div class="row mb-2 ">
            <div class="col-sm-6 col-md-4  ">
                <a href="" class=""><img src="../img/wl.jpg" class="my-2 img-fluid"> </a>
            </div>

            <div class="col-sm-6 col-md-8">
                <span><a href="../not_html/Dnd.php" id="orange">Malus Leucis.<br>Raça:Tiefling<br>Classe:Guerreiro<br>
                        >Atributos<br>
                        Força: 15 M:+2 S:+4<br>
                        Destreza: 15 M:+2 S:+2<br>
                        Constituição: 10 M:0 S:+2<br>
                        Inteligencia: 12 M:+1 S:+1<br>
                        Sabedoria: 10 M:0 S:0<br>
                        Carisma: 12 M:+1 S+1<br>
                        >Proficiencias
                        Acrobracia:2<br>
                        Arcanismo:1<br>
                        Atletismo:4<br>
                        Atuação:1<br>
                        Blefar:1<br>
                        Furtividade:2<br>
                        Historia:1<br>
                        Intimidação:3<br>
                        Intuição:0<br>
                        Investigação:1<br>
                        Lidar com Animais:2<br>
                        Medicina:1<br>
                        Natureza:1<br>
                        Percepção:2<br>
                        Persuasão:1<br>
                        Prestidigitação:2<br>
                        Religião:1<br>
                        Sobrevivencia:0<br>
                        >Equipamento de Batalha<br>
                        Arco de longo Alcance: 1D8+2 Bonus Pro D20 +4<br>
                    </a></span>
                <br>
                <br>


            </div>
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
                                        <label>Nick:</label><input type="text" name="nm_usuario" class="form-control" required id="nm_usuario">
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
<?php }?>
