<?php

    include_once("../php/conexao.php");
 
        
        
	$nm_sala = $_POST['nm_sala'];
	$nm_criador= $_POST['criador'];
	$mx_pl= $_POST['mx'];
	$pegaEmail = mysqli_query($link, "SELECT * FROM sala WHERE nm_sala = '$nm_sala'");	
	
	if(mysqli_num_rows($pegaEmail) == 1){
		echo "<script language='javascript' type='text/javascript'>alert('Esta sala ja esta cadastrado em nossos registros');window.location.href='criar_sl.php'</script>";
	}
		else{
		
	
	
     $result_usuario = "INSERT INTO sala(nm_sala, nm_criador, max_pl) VALUES ('$nm_sala','$nm_criador', '$mx_pl')";
    $resultado_usuario = mysqli_query($link, $result_usuario);
    
            
    if(mysqli_affected_rows($link) != 0){
                echo "
                    <script language='javascript' type='text/javascript'>alert('Sala cadastrado com sucesso!');window.location.href=' ../usu/perfil_usuario.php'</script>";
                ;    
            }else{
                echo "
                    <script language='javascript' type='text/javascript'>alert('Nao foi possivel cadastrar este usuario');window.location.href=' ../sal/criar_sl.php'</script>"
                ;    
            }
	}

?>
