<?php

    include_once("../php/conexao.php");
 
        if(isset($_POST['env']) ){
        
	$mgs = $_POST['mensagem'];
    $id = $_POST['env']   ;     
		
	
	
     $result_usuario = "INSERT INTO chat(mensagem, nm_do_perso,id_sl) VALUES ('$mgs', 'yudi','$id')";
    $resultado_usuario = mysqli_query($link, $result_usuario);
    
            
    if(mysqli_affected_rows($link) != 0){
                echo "
                    <script language='javascript' type='text/javascript'>alert('mensagen enviada!');window.location.href=' ../sal/sala_mestre.php'</script>";
                ;    
            }else{
                echo "
                    <script language='javascript' type='text/javascript'>alert('Nao foi possivel envia mensagen envia');window.location.href=' ../sal/sala_mestre.php'</script>";
                ;    
            }
        }else{
        echo "<script language='javascript' type='text/javascript'>alert('Não pode enviar uma mensagem vazia'); </script>";
            
        }
        

?>
