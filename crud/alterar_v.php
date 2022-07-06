<?php

// Inclui o arquivo de conexão com o banco de dados
require_once("../php/conexao.php");

// Inclui o arquivo de funções para as ações no banco de dados
require_once("../crud/funcoes-banco.php");

// Grava as informações que foram passadas pelo método 'POST' através do formulario
$id = $_POST["id"];
$senha =  md5($_POST["senha"]);
$confisenha =  md5($_POST["confirmasenha"]);
$nm_usuario = $_POST["nm_usuario"];

// Verifica se a função 'alterar' irá retornar a alteração de dados no banco
// As variáveis com as informações são passadas para a função que será executada
// (Obs: a variável de conexão com o banco de dados também precisa ser passada, pois as funções 'mysqli' necessitam dela como parâmetro)

if(alterar($id, $senha, $confisenha, $nm_usuario, $link)){
	// Se retornar, os dados foram alterados
	echo "<p>Alterado com sucesso!<br></p>";
	echo "<p><a href=\"../crud/pagina-listagem-dados.php\">Clique aqui</a> para ir até o painel</p>";
}
else{
	// Se não retornar, os dados não foram alterados
	echo "<p>Erro de alteração!</p>";
}

?>