<?php




function cadastrar($email, $senha, $confirmasenha, $nm_usuario, $nivel, $nu, $xp,$link){
	
  
    // Cria a query para inserir os dados na tabela 'usuario'
	$pegaEmail = mysqli_query($link, "SELECT * FROM us_em WHERE email = '$email'");	
	
	if(mysqli_num_rows($pegaEmail) == 1){
		echo "<script language='javascript' type='text/javascript'>alert('Este email ja esta cadastrado em nossos registros');window.location.href='../crud/cadastrar.php'</script>";
	}
		else{
		
    $query = "INSERT INTO us_em(email, senha, confsenha, nm_usuario, nivel_usuario, xp_usuario, nivel_da_conta) VALUES ('$email','$senha','$confirmasenha','$nm_usuario','$nu','$xp', '$nivel')";
            
	// Retorna a execução da inserção de dados no banco
	return mysqli_query($link, $query);
}
}

function alterar($id, $senha, $confisenha, $nm_usuario, $link){
	// Cria a query para alterar os dados da tabela 'usuario'. Obs: (Só vai alterar se os ids forem iguais)
	$query = "UPDATE us_em SET senha = '$senha', confsenha = '$confisenha', nm_usuario = '$nm_usuario'  WHERE email = '$id'";
   
   
	// Retorna a execução da alteração dos dados no banco
    $resultado_usuario = mysqli_query($link, $query);
    return  $resultado_usuario;
}

function excluir($id, $link){
	// Cria a query para excluir os dados da tabela 'usuario'. Obs: (Só vai excluir se os ids forem iguais)
	$query = "DELETE FROM us_em WHERE email = '$id'";
  

	// Retorna a execução da exclução dos dados no banco
	return mysqli_query($link, $query);
}

function listar($link){
	// Cria um array
	$listaDeUsuario = array();

	// Cria a query para exibir todos os dados da tabela 'usuario'
	$query = "SELECT * FROM us_em";

	// Grava a execução dessa query em uma variável
	$resultado = mysqli_query($link, $query);

	// Cria um looping para armazenar na variável todas as linhas da tabela 'usuario'
	while($usuario = mysqli_fetch_array($resultado)){
		// A linha armazenada em '$usuario' é colocada no final do array 'listaDeUsuario'
		array_push($listaDeUsuario, $usuario);
	}

	// Retorna o array com os valores das linhas da tabela 'usuario'
	return $listaDeUsuario;
}

?>
