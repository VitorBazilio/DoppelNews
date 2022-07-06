<?php

    include_once("../php/conexao.php");
        
        
	$titulo = $_POST['titulo'];
    $manchete = $_POST['manchete'];
	$texto= $_POST['texto'];
    $foto= $_FILES['foto'];
    $nm_usuario = $_POST['nome'];
	
	$pegaTitu = mysqli_query($link, "SELECT * FROM noticias WHERE titu_not = '$titulo'");	
	
	if(mysqli_num_rows($pegaTitu) == 1){
		echo "<script language='javascript' type='text/javascript'>alert('Este ja existe em nossos registros');window.location.href='criar_not.php'</script>";
	}
		else{
            
		if (!empty($foto["name"])) {
		
		// Largura máxima em pixels
		$largura = 180000;
		// Altura máxima em pixels
		$altura = 180000;
		// Tamanho máximo do arquivo em bytes
		$tamanho = 104857600;
 
		$error = array();
 
        	// Verifica se o arquivo é uma imagem
    	if(!preg_match("/^image\/(pjpeg|jpeg|png|gif|bmp)$/", $foto["type"])){
     	   $error[0] = "Isso não é uma imagem.";
   	 	} 
	
		// Pega as dimensões da imagem
		$dimensoes = getimagesize($foto["tmp_name"]);
	
		// Verifica se a largura da imagem é maior que a largura permitida
		if($dimensoes[0] > $largura) {
			$error[1] = "A largura da imagem não deve ultrapassar ".$largura." pixels";
		}

		// Verifica se a altura da imagem é maior que a altura permitida
		if($dimensoes[1] > $altura) {
			$error[2] = "Altura da imagem não deve ultrapassar ".$altura." pixels";
		}
		
		// Verifica se o tamanho da imagem é maior que o tamanho permitido
		if($foto["size"] > $tamanho) {
   		 	$error[3] = "A imagem deve ter no máximo ".$tamanho." bytes";
		}

		// Se não houver nenhum erro
		if (count($error) == 0) {
		
			// Pega extensão da imagem
			preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $foto["name"], $ext);

        	// Gera um nome único para a imagem
        	$nome_imagem = md5(uniqid(time())) . "." . $ext[1];

        	// Caminho de onde ficará a imagem
        	$caminho_imagem = "../img/" . $nome_imagem;

			// Faz o upload da imagem para seu respectivo caminho
			move_uploaded_file($foto["tmp_name"], $caminho_imagem);
		
			// Insere os dados no banco
			$sql = mysqli_query($link,"INSERT INTO noticias(titu_not, manchete, noticia_texto, imgs, nm_usuario)  VALUES ('$titulo','$manchete', '$texto','$nome_imagem','$nm_usuario')");
		
			// Se os dados forem inseridos com sucesso
			if (mysqli_affected_rows($link) != 0){
				echo "Você foi cadastrado com sucesso.";
                  echo "
                    <script language='javascript' type='text/javascript'>alert('Noticia cadastrada!');window.location.href=' ../admin/perfil_adm.php'</script>";
                ;  
			}else{
                echo "
                    <script language='javascript' type='text/javascript'>alert('Nao foi possivel cadastrar esta notícia');window.location.href=' ../php/criar_not.php'</script>"
                ;    
            }
		}
	
		// Se houver mensagens de erro, exibe-as
		if (count($error) != 0) {
			foreach ($error as $erro) {
				echo $erro . "<br />";
			}
		}
	} 
        }
?>
