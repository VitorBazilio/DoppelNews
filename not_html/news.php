<?php


include_once("../php/conexao.php");
session_start();

if(isset($_SESSION["email"]) || isset($_SESSION["senha"]))
    {
         header("Location:  ../usu/perfil_usuario.php");
        
    }
    else{
        
        
    header("Location: ../index.php");
		
    }           
?>