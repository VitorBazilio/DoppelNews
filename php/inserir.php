<?php

    include_once("conexao.php");
    const nivel=0;
    const nu=1;
    const xp=0;
    $nivel=nivel;
    $nu=nu;
    $xp=xp;
        
        
	$email = $_POST['email'];
	$senha= md5($_POST['senha']);
	$confirmasenha = md5($_POST['confirmasenha']);
    $nm_usuario = $_POST['nm_usuario'];
	
	$pegaEmail = mysqli_query($link, "SELECT * FROM us_em WHERE email = '$email'");	
	
	if(mysqli_num_rows($pegaEmail) == 1){
		echo "<script language='javascript' type='text/javascript'>alert('Este email ja esta cadastrado em nossos registros');window.location.href='../index.php'</script>";
	}
		else{
		
	
	
     $result_usuario = "INSERT INTO us_em(email, senha, confsenha, nm_usuario, nivel_usuario, xp_usuario, nivel_da_conta) VALUES ('$email','$senha','$confirmasenha','$nm_usuario','$nu','$xp', '$nivel')";
    $resultado_usuario = mysqli_query($link, $result_usuario);
    
            
    if(mysqli_affected_rows($link) != 0){
                echo "
                    <script language='javascript' type='text/javascript'>alert('Usuario cadastrado com sucesso!');window.location.href=' ../index.php'</script>";
                ;    
            }else{
                echo "
                    <script language='javascript' type='text/javascript'>alert('Nao foi possivel cadastrar este usuario');window.location.href=' ../index.php'</script>"
                ;    
            }
	}

?>
