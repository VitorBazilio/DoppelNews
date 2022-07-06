<?php
include '../php/conexao.php';
    $select = "select * from persona ORDER By p_rank desc limit 10";
    $executa = mysqli_query($link, $select);
    echo '<div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">Nome Usuario</th>
                  <th scope="col">Personagem</th>
                  <th scope="col">Sala</th>
                  <th scope="col">Pontuação</th>
                </tr>
              </thead>
    
    ';
        
    while($rank = mysqli_fetch_object($executa)) {
        $info = "select * from us_em where email = '$rank->email'";
        $executainfo = mysqli_query($link, $info);
        $minfo = mysqli_fetch_object($executainfo);
        echo '<tbody>
            <tr>
              <th>'.$minfo->nm_usuario.'</th>
              <td>'.$rank->nm_do_perso.'</td>
              <td>'.$rank->sala.'</td>
              <td>'.$rank->p_rank.'</td>
            </tr>
        </tbody>';
    }
    echo "</table>
        </div>";
?>




<?php 

    if(!isset($_GET['id_usuario']) or (!isset($_GET['id_sala']))) {
        header('Location:  ../sal/salas.php');
        exit;
    } else {
        $idu = $_GET['id_usuario'];
        $ids = $_GET['id_sala'];
        $select = mysql_query($link, "select * from persona where id_usuario='$idu'");
        $resu = mysqli_fetch_object($select);
        
        $valor = ($_POST[''] + $resul->id_usuario);

        
        
        $sql = "update persona set p_rank = '$valor' where id_usuario ='$idu'";
        $execute = mysqli_query($link, $sql);
        $result = mysqli_nuw_rows($execute);
        if($result > 0 ){
            
        } else {
            echo '<script>
                    alert("Erro em adicionar pontuação");
                <script>';
        }
    }
    
    

?> 