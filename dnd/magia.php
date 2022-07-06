<?php

    include_once("../php/conexao.php");
    
        
        
	$magia = $_POST['magia'];
	$nivel = $_POST['nl'];
	$gasto = $_POST['gst'];
	$dd = $_POST['dd'];
    $descricao = $_POST['desc'];

	$pegaEmail = mysqli_query($link, "SELECT * FROM magia WHERE nm_magia = '$magia'");	
	
	if(mysqli_num_rows($pegaEmail) == 1){
		echo "<script language='javascript' type='text/javascript'>alert('Esta magia ja esta cadastrada em nossos registros');window.location.href='../dnd/criar_rg.php'</script>";
	}
		else{
		
	
	
     $result_usuario = "INSERT INTO magia(nm_magia, nivel_mg, gasto_mg, dado_mg, descricao) VALUES ('$magia', '$nivel','$gasto,'$dd', '$descricao')";
    $resultado_usuario = mysqli_query($link, $result_usuario);
    
            
    if(mysqli_affected_rows($link) != 0){
                echo "
                    <script language='javascript' type='text/javascript'>alert('Regra cadastrada!');window.location.href=' ../dnd/criar_rg.php'</script>";
                ;    
            }else{
                echo "
                    <script language='javascript' type='text/javascript'>alert('Regra nao cadastrada');window.location.href=' ../dnd/criar_rg.php'</script>"
                ;    
            }
	}

?>
