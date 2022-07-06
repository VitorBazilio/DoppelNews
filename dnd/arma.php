<?php

    include_once("../php/conexao.php");
    
        
        
	$arma = $_POST['arma'];
    $dado = $_POST['d_d'];
    $preco = $_POST['preco'];
    $peso = $_POST['peso'];
	$id_ass = $_POST['id_ass'];
	$id_am = $_POST['id_am'];
    $descricao = $_POST['desc'];


	$pegaEmail = mysqli_query($link, "SELECT * FROM arma WHERE nm_arma = '$arma'");	
	
	if(mysqli_num_rows($pegaEmail) == 1){
		echo "<script language='javascript' type='text/javascript'>alert('Esta arma ja esta cadastrada em nossos registros');window.location.href='../dnd/criar_rg.php'</script>";
	}
		else{
		
	
	
     $result_usuario = "INSERT INTO arma(nm_arma, preco_am, dado_am, peso_am, AM, ASS, descricao) VALUES ('$arma', '$preco','$dado','$peso','$id_am', '$id_ass', '$descricao')";
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
