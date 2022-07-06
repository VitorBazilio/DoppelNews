

<?php

// Inclui o arquivo de conexão com o banco de dados
require_once("../php/conexao.php");

// Inclui o arquivo de funções para as ações no banco de dados
require_once("../crud/funcoes-banco.php");

// Grava as informações que foram passadas pelo método 'POST' através do formulario
    $email = $_POST['email'];
	$senha= md5($_POST['senha']);
	$confirmasenha = md5($_POST['confirmasenha']);
    $nm_usuario = $_POST['nm_usuario'];
    const nivel=0;
    const nu=1;
    const xp=0;
    $nivel=nivel;
    $nu=nu;
    $xp=xp;
// Verifica se a função 'cadastrar' irá retornar a inserção de dados no banco
// As variáveis com as informações são passadas para a função que será executada
// (Obs: a variável de conexão com o banco de dados também precisa ser passada, pois as funções 'mysqli' necessitam dela como parâmetro)
if(cadastrar($email, $senha, $confirmasenha, $nm_usuario, $nivel, $nu, $xp,$link)){
	// Se retornar, os dados foram cadastrados
	echo "<p>Cadastrado com sucesso!<br></p>";
	echo "<p><a href=\"../crud/pagina-listagem-dados.php\">Clique aqui</a> para ir até o painel</p>";
}
else{
	// Se não retornar, os dados não foram cadastrados
	echo "<p>Erro de cadastro!</p>";
}

?>
