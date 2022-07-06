<?php

    include_once("../php/conexao.php");
    
        
        
	$classe = $_POST['classe'];
	$id_fc = $_POST['id_fc'];
	$id_dz = $_POST['id_dz'];
	$id_ct = $_POST['id_ct'];
	$id_it = $_POST['id_it'];
	$id_sb = $_POST['id_sb'];
	$id_cr = $_POST['id_cr'];
	$id_ass = $_POST['id_ass'];
	$id_am = $_POST['id_am'];
	$id_arp = $_POST['id_arp'];
	$id_arl = $_POST['id_arl'];
	$id_arm = $_POST['id_arm'];
    $descricao = $_POST['desc'];
    $d_v = $_POST['d_v'];


	$pegaEmail = mysqli_query($link, "SELECT * FROM classe WHERE nm_classe = '$classe'");	
	
	if(mysqli_num_rows($pegaEmail) == 1){
		echo "<script language='javascript' type='text/javascript'>alert('Esta classe ja esta cadastrada em nossos registros');window.location.href='../dnd/criar_rg.php'</script>";
	}
		else{
		
	
	
     $result_usuario = "INSERT INTO classe(nm_classe, dado_vida, forca_cl, destreza_cl, constituicao_cl, inteligencia_cl, sabedoria_cl, carisma_cl, AM, ASS, ARL, ARM, ARP, descricao) VALUES ('$classe', '$d_v','$id_fc','$id_dz','$id_ct','$id_it','$id_sb', '$id_cr', '$id_ass', '$id_am', '$id_arl', '$id_arm', '$id_arp', '$descricao')";
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
