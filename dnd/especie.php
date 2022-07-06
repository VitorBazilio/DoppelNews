<?php

    include_once("../php/conexao.php");
    
        
        
	$raca = $_POST['raca'];
    $descricao = $_POST['desc'];


	$pegaEmail = mysqli_query($link, "SELECT * FROM especie WHERE nm_especie = '$raca'");	
	
	if(mysqli_num_rows($pegaEmail) == 1){
		echo "<script language='javascript' type='text/javascript'>alert('Esta classe ja esta cadastrada em nossos registros');window.location.href='../dnd/criar_rg.php'</script>";
	}
		else{
		
	
	
     $result_usuario = "INSERT INTO especie(nm_especie, descricao) VALUES ('$raca', '$descricao')";
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
