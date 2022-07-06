<?php
session_start();

?>
<html lang="pt-br">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <!-- Bootstrap CSS -->
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
                    <input class="form-control ml-5 mr-3 mt-2" type="searchform" placeholder="Buscar...">

                </form>
                <ul class="navbar-nav ">

                    <li class="nav-item dropdown dropright">

                        <a class="nav-link dropdown-toggle component-active-bg " href="#" data-toggle="dropdown" id="navDrop">
                            <i class="fab fa-gitlab " style="font-size:36px;color:#ff550a;"></i>
                        </a>
                        <div class="dropdown-menu ">
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#siteModalLg">Entrar</a>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#siteModalCd">Cadastrar</a>

                        </div>
                    </li>

                </ul>

            </div>
        </div>


    </nav>

    <div id="carouselSite" class="carousel slide" data-ride="carousel">

        <ol class="carousel-indicators">
            <li data-target="#carouselSite" data-slide-to="0" class="active"></li>
        </ol>

        <div class="carousel-inner">

            <div class="carousel-item active">

                <img src="../img/god2.jpg" class="img-fluid d-block">

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


    <div class="container bg-dark my-3 center-block" id="orange">

        <div class="row">

            <div class="col-12 text-center my-3">
                <h1 class="display-2">God of War</h1>
                <p>Um monstro começa a se reascender.</p>
            </div>

        </div>

        <div class="row mb-2  col-sm-10 col-md-10 center-block">


            <div class="ml-auto mr-auto center-block ">
                <span><a href="" id="orange">
                       
                           <article class="text-justify">
                            <p>
                            O New Game+ para God of War estava disponível como uma atualização gratuita no dia 20 de agosto. Lembrando que, para ter acesso à todo o conteúdo New    Game+, você teria que completar a história de God of War em qualquer dificuldade.</p>
                            <p >O New Game+ de God of War é inspirado pelo feedback dos fãs. Com o New Game+ temos equipamento novo, inimigos mais difíceis, e a habilidade de pular os vídeos da história! </p>
                            <p >Reviver a jornada de Kratos e Atreus com todo o seu equipamento, armadura, encantamentos, talismãs e recursos na dificuldade que quiser, testando suas habilidades contra inimigos de nível maior; alguns até com alguns truques novos na manga! </p>
                            <p>Exclusivo ao New Game+, temos um novo nível de raridade de equipamento para criar e melhorar! Coletando ‘Skap Slag’, um novo recurso, para melhorar seu equipamento até o melhor do melhor nível possível com os ferreiros.</p>
                            <p>Jogue o game de um novo jeito, explorando novas opções de customização com poderosos novos conjuntos de armadura e encantamentos.</p>
                            <p>Por fim, mergulhe direto na ação com a nova opção de poder pular os vídeos da história tanto no modo normal quando no modo New Game+, uma vez que já tenha terminado o jogo pelo menos uma vez.</p>
                            </article>
                       
                    </a></span>

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
                    <li class="list-group-item bg-dark" id="orange"><a href="../perso/persons.php?categoria=Meus">Meus</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="../perso/persons.php?categoria=Melhores">Melhores</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="../perso/persons.php?categoria=Exemplos">Exemplos</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="../perso/persons.php?categoria=Populares">Populares</a></li>
                </ul>
            </div>
            <div class="col-lg-2">
                <ul class="list-group bg-dark" id="back-bg">
                    <li class="list-group-item bg-dark" id="orange">
                        <h3>Ajuda</h3>
                    </li>
                    <li class="list-group-item bg-dark" id="orange"><a href="../help/Ajuda.php?categoria=DnD">D&amp;D</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="../help/Ajuda.php?categoria=DnD">Livros</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="../help/Ajuda.php?categoria=Coment">Fale Conosco</a></li>
                    <li class="list-group-item bg-dark" id="orange"><a href="../help/Ajuda.php?categoria=Pergunt">Perguntas</a></li>
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
                    <li class="list-group-item bg-dark" id="orange"><a href="../help/Ajuda.php?categoria=hl">E-mail</a></li>

                </ul>
            </div>
        </div>
        <div class="container mt-2">
            <a href=""> © 2017-2018 Hellfier | LinkMcCall | Rafa_Lopi | Versão 1.0 <br></a>
            <a href="../help/dir_aut.php"> Todas as imagens de filmes, séries e etc são marcas registradas dos seus respectivos proprietários.</a>
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
