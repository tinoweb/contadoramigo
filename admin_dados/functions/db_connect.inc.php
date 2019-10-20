<?php
	# Verifica se consegue se conectar ao banco de dados
	if(!($id = mysql_connect($servidor,$usuario,$password))) {
	   echo "Não foi possível estabelecer uma conexão com o gerenciador MySQL. Favor Contactar o Administrador. [Usuário ou Senha Incorreto]";
	   exit;
	}
	
	# Verifica se consegue selecionar o banco de dados que vai utilizar
	if(!($con=mysql_select_db($dbname,$id))) { 
	   echo "Nao foi possivel estabelecer uma conexao com o gerenciador MySQL. Favor Contactar o Administrador. [BD Incorreto]";
	   exit; 
	}

	# Codifica todos os dados que recebe ou insere no banco de dados como UTF-8
	ini_set('default_charset','UTF-8');
	mysql_set_charset('utf8');
	
	# Função que previne SQL Injection
	function _antiSqlInjection($Target){
		$sanitizeRules = array('FROM','SELECT','INSERT','DELETE','WHERE','DROP TABLE','SHOW TABLES');
		foreach($Target as $key => $value):
			if(is_array($value)): $arraSanitized[$key] = _antiSqlInjection($value);
			else:
				$arraSanitized[$key] = (!get_magic_quotes_gpc()) ? addslashes(str_ireplace($sanitizeRules,"",$value)) : str_ireplace($sanitizeRules,"",$value);
			endif;
		endforeach;
		return @$arraSanitized;
	}

	# Aplica a função para os dados recebidos por GET e POST
	$_GET = _antiSqlInjection($_GET);
	$_POST = _antiSqlInjection($_POST);
	
	# Enquanto você está programando o site, é bom você vizualizar estes erros que dão, por isso você deixa esta linha comentada.
	//error_reporting(0);
?>

