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
                <h1 class="display-2">Classe</h1>
                <p></p>
            </div>


        </div>



        <div class="row">
            <?php 
    if(!isset($_GET['categoria'])){
            echo'
                <div class="col-12 text-center my-3">
                    <a href="criar_rg.php?categoria=Classe" class="mx-4">Classe</a>
                    <a href="criar_rg.php?categoria=Especie"  class="mx-4">Especie</a>
                    <a href="criar_rg.php?categoria=Arma"  class="mx-4">Arma</a>
                    <a href="criar_rg.php?categoria=Magia"  class="mx-4">Magia</a>
                </div>

            </div>
            
            
            ';

        

        
        }else{
    if($_GET['categoria'] =='Classe'){
            echo'
                <div class="col-12 text-center my-3">
                    <a href="criar_rg.php" class="mx-4">Classe</a>
                    <a href="criar_rg.php?categoria=Especie"  class="mx-4">Especie</a>
                    <a href="criar_rg.php?categoria=Arma"  class="mx-4">Arma</a>
                    <a href="criar_rg.php?categoria=Magia"  class="mx-4">Magia</a>
                </div>

            </div>
            ';
              } else{
    
          if($_GET['categoria']=='Especie'){
            echo'
                <div class="col-12 text-center my-3">
                    <a href="criar_rg.php?categoria=Classe" class="mx-4">Classe</a>
                    <a href="criar_rg.php"  class="mx-4">Especie</a>
                    <a href="criar_rg.php?categoria=Arma"  class="mx-4">Arma</a>
                    <a href="criar_rg.php?categoria=Magia"  class="mx-4">Magia</a>
                </div>

            </div>
            ';
            
        }else{
                 if($_GET['categoria']=='Arma'){
            echo'
                <div class="col-12 text-center my-3">
                    <a href="criar_rg.php?categoria=Classe" class="mx-4">Classe</a>
                    <a href="criar_rg.php?categoria=Especie"  class="mx-4">Especie</a>
                    <a href="criar_rg.php"  class="mx-4">Arma</a>
                    <a href="criar_rg.php?categoria=Magia"  class="mx-4">Magia</a>
                </div>

            </div>
            ';     
       }else{
                     
                         if($_GET['categoria']=='Magia'){
            echo'
                <div class="col-12 text-center my-3">
                    <a href="criar_rg.php?categoria=Classe" class="mx-4">Classe</a>
                    <a href="criar_rg.php?categoria=Especie"  class="mx-4">Especie</a>
                    <a href="criar_rg.php?categoria=Arma"  class="mx-4">Arma</a>
                    <a href="criar_rg.php"  class="mx-4">Magia</a>
                </div>

            </div>
            '; 
        }
        }}}}     
                ?>


            <?php
    if(isset($_GET['categoria'])){
    if($_GET['categoria']=='Classe') {
   
      echo '
      <div class="col-12 text-center my-3 ml-5">
                <form action="classe.php" method="POST" class="col-8 ml-5">
                    <br><br>
                    <label>Classe:</label><input type="text" name="classe" class="form-control" required id="classe">
                    <label>Dado de vida:</label><input type="text" name="d_v" class="form-control" required id="d_v">
                    <label>Força:</label><input type="number" name="id_fc" class="form-control" required id="id_fc">
                    <label>Destreza:</label><input type="number" name="id_dz" class="form-control" required id="id_dz">
                    <label>Constituição:</label><input type="number" name="id_ct" class="form-control" required id="id_ct">
                    <label>Inteligencia:</label><input type="number" name="id_it" class="form-control" required id="id_it">
                    <label>Sabedoria:</label><input type="number" name="id_sb" class="form-control" required id="id_sb">
                    <label>Carisma:</label><input type="number" name="id_cr" class="form-control" required id="id_cr">
                    <label>Arma Simples:</label><input type="number" name="id_ass" class="form-control" required id="id_ass">
                    <label>Arma Marcial:</label><input type="number" name="id_am" class="form-control" required id="id_am">
                    <label>Armadura Pesada:</label><input type="number" name="id_arp" class="form-control" required id="id_arp">
                    <label>Armadura Leve:</label><input type="number" name="id_arl" class="form-control" required id="id_arl">
                    <label>Armadura Media:</label><input type="number" name="id_arm" class="form-control" required id="id_arm">
                    <label>Descrição:</label>
                         <textarea name="desc" class="w-50 h-25 my-2">
                        </textarea > 
                    <br>
                    <button type="submit" class="btn btn-dark form-inline" id="orange">Enviar</button>
                </form>
         </div>
      ';
        
    }

        }
?>
            <?php
    if(isset($_GET['categoria'])){
    if($_GET['categoria']=='Especie') {
                echo '
      <div class="col-12 text-center my-3 ml-5">
                <form action="../dnd/especie.php" method="POST" class="col-8 ml-5">
                    <br><br>
                    <label>Raça:</label><input type="text" name="raca" class="form-control" required id="raca">
                    <label>Descrição:</label>
                         <textarea name="desc" class="w-50 h-25 my-2">
                        </textarea > 
                    <br>
                    <button type="submit" class="btn btn-dark form-inline" id="orange">Enviar</button>
                </form>
         </div>
      ';

    }
    }
?>
            <?php
    if(isset($_GET['categoria'])){
    if($_GET['categoria']=='Arma') {
        
        echo '
      <div class="col-12 text-center my-3 ml-5">
                <form action="../dnd/arma.php" method="POST" class="col-8 ml-5">
                    <br><br>
                    <label>Arma:</label><input type="text" name="arma" class="form-control" required id="arma">
                    <label>Dado de dano:</label><input type="text" name="d_d" class="form-control" required id="d_d">
                    <label>Preço:</label><input type="text" name="preco" class="form-control" required id="preco">
                    <label>Peso:</label><input type="number" name="peso" class="form-control" required id="peso">
                    <label>Arma Simples:</label><input type="number" name="id_ass" class="form-control" required id="id_ass">
                    <label>Arma Marcial:</label><input type="number" name="id_am" class="form-control" required id="id_am">
                    <label>Descrição:</label>
                         <textarea name="desc" class="w-50 h-25 my-2">
                        </textarea > 
                    <br>
                    <button type="submit" class="btn btn-dark form-inline" id="orange">Enviar</button>
                </form>
         </div>
      ';

    }
    }
?>
            <?php
    if(isset($_GET['categoria'])){
    if($_GET['categoria']=='Magia') {
    
        echo '
      <div class="col-12 text-center my-3 ml-5">
                <form action="magia.php" method="POST" class="col-8 ml-5">
                    <br><br>
                    <label>Magia:</label><input type="text" name="arma" class="form-control" required id="arma">
                    <label>Nivel:</label><input type="number" name="nl" class="form-control" required id="nl">
                    <label>Gasto:</label><input type="number" name="gst" class="form-control" required id="gst">
                    <label>Dado:</label><input type="text" name="dd" class="form-control" required id="dd">
                    <label>Descrição:</label>
                         <textarea name="desc" class="w-50 h-25 my-2">
                        </textarea > 
                    <br>
                    <button type="submit" class="btn btn-dark form-inline" id="orange">Enviar</button>
                </form>
         </div>
      ';

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
