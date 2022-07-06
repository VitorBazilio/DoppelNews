<?php
    session_start(); 
        //Incluindo a conexão com banco de dados   
    include_once("../php/conexao.php");    
    //O campo usuário e senha preenchido entra no if para validar
    $nm_usuario="0";
    $email = mysqli_escape_string($link, $_POST['email']);
    $log="SELECT email , nivel_da_conta FROM us_em WHERE email = '$email'   LIMIT 1";
    $log1 = mysqli_query($link, $log);
    $log2 = mysqli_fetch_assoc($log1);
    $nivel['nivel_da_conta'] = $log2['nivel_da_conta'];
    if($nivel['nivel_da_conta'] < 5)
    {
        
        
   

	if(isset($_POST['email']) && isset($_POST['senha'])){
		
        $email = mysqli_escape_string($link, $_POST['email']); //Escapar de caracteres especiais, como aspas, prevenindo SQL injection
        $senha = mysqli_escape_string($link, $_POST['senha']);
		$senha = md5($_POST['senha']);
        //Buscar na tabela usuario o usuário que corresponde com os dados digitado no formulário
        $result_usuario = "SELECT * FROM us_em WHERE email = '$email' && senha = '$senha' && nm_usuario != '$nm_usuario' LIMIT 1";
        $resultado_usuario = mysqli_query($link,$result_usuario);
        $resultado = mysqli_fetch_assoc($resultado_usuario);
       
        //Encontrado um usuario na tabela usuário com os mesmos dados digitado no formulário
        if(isset($resultado)){
          
            $_SESSION['email'] = $resultado['email'];
			$_SESSION['senha'] = $resultado['senha'];
            $_SESSION['nm_usuario'] = $resultado['nm_usuario'];
            $_SESSION["nivel_da_conta"] = $resultado["nivel_da_conta"];
            header("Location: ../usu/perfil_usuario.php");
        //Não foi encontrado um usuario na tabela usuário com os mesmos dados digitado no formulário
        //redireciona o usuario para a página de login
        }else{    
            //Váriavel global recebendo a mensagem de erro
            $_SESSION['loginErro'] = "<script language='javascript' type='text/javascript'>alert('Usuário ou senha Inválidos')</script>";
            header("Location:../index.php");
        }
    //O campo usuário e senha não preenchido entra no else e redireciona o usuário para a página de login
    }else{
        $_SESSION['loginErro'] = "<script language='javascript' type='text/javascript'>alert('Usuário ou senha Inválidos')</script>";
        header("Location: ../index.php");
    }
        
         }else{
        //O campo adm e senha
        
        if(isset($_POST['email']) && isset($_POST['senha'])){
		
        $email = mysqli_escape_string($link, $_POST['email']); //Escapar de caracteres especiais, como aspas, prevenindo SQL injection
        $senha = mysqli_escape_string($link, $_POST['senha']);
		$senha = md5($_POST['senha']);
        //Buscar na tabela usuario o usuário que corresponde com os dados digitado no formulário
        $result_usuario = "SELECT * FROM us_em WHERE email = '$email' && senha = '$senha' && nm_usuario != '$nm_usuario' LIMIT 1";
        $resultado_usuario = mysqli_query($link,$result_usuario);
        $resultado = mysqli_fetch_assoc($resultado_usuario);
        
        //Encontrado um usuario na tabela usuário com os mesmos dados digitado no formulário
        if(isset($resultado)){
          
           $_SESSION['email'] = $resultado['email'];
			$_SESSION['senha'] = $resultado['senha'];
            $_SESSION['nm_usuario'] = $resultado['nm_usuario'];
            $_SESSION["nivel_da_conta"] = $resultado["nivel_da_conta"];
            header("Location: ../admin/perfil_adm.php");
        //Não foi encontrado um usuario na tabela usuário com os mesmos dados digitado no formulário
        //redireciona o usuario para a página de login
        }else{    
            //Váriavel global recebendo a mensagem de erro
            $_SESSION['loginErro'] = "<script language='javascript' type='text/javascript'>alert('Usuário ou senha Inválidos')</script>";
            header("Location: ../index.php");
        }
    //O campo usuário e senha não preenchido entra no else e redireciona o usuário para a página de login
    }else{
        $_SESSION['loginErro'] = "<script language='javascript' type='text/javascript'>alert('Usuário ou senha Inválidos')</script>";
        header("Location: ../index.php");
    }
        
        
    }
?>
