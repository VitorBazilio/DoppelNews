<?php

// Inclui o arquivo de conexão com o banco de dados
require_once("../php/conexao.php");

// Inclui o arquivo de funções para as ações no banco de dados
require_once("../crud/funcoes-banco.php");

// Grava o id que foi passado pelo método 'POST' através do formulario
$id = $_POST["id"];

// Verifica se a função 'excluir' irá retornar a exclução de dados no banco
// A variável com o id é passada para a função ser executada
// (Obs: a variável de conexão com o banco de dados também precisa ser passada, pois as funções 'mysqli' necessitam dela como parâmetro)
if(excluir($id, $link)){
	// Se retornar, os dados foram excluidos
	echo "<p>Registro excluído com sucesso!<br></p>";
	echo "<p><a href=\"../crud/pagina-listagem-dados.php\">Clique aqui</a> para ir até o painel</p>";
}
else{
	// Se não retornar, os dados não foram excluidos
	echo "<p>Erro ao excluir!</p>";
}

?>