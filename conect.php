<?php
	// Report all PHP errors
	error_reporting(E_ALL);
		//Caso seja necessário modificar os códigos abaixo, modificar também em 'session.php'
//		$conexao = mysql_connect("177.153.16.160", "contadoramig15", "ttq231kz1");
//		$db = mysql_select_db("contadoramig15");
//		mysql_query("SET NAMES 'utf8'");
//		mysql_query('SET character_set_connection=utf8');
//		mysql_query('SET character_set_client=utf8');
//		mysql_query('SET character_set_results=utf8');
	
	if($_SERVER['SERVER_NAME'] == 'contadoramigo.com.br' || $_SERVER['SERVER_NAME'] == 'www.contadoramigo.com.br'){
		
		//Caso seja necessário modificar os códigos abaixo, modificar também em 'session.php'
		$conexao = mysql_connect("177.153.16.160", "contadoramigo", "ttq231kz");
		$db = mysql_select_db("contadoramigo");
		mysql_query("SET NAMES 'utf8'");
		mysql_query('SET character_set_connection=utf8');
		mysql_query('SET character_set_client=utf8');
		mysql_query('SET character_set_results=utf8');
		
	} elseif($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == 'www.ambientedeteste2.hospedagemdesites.ws') {


		//Caso seja necessário modificar os códigos abaixo, modificar também em 'session.php'
		$conexao = mysql_connect("177.153.16.160", "contadoramig15", "ttq231kz1");
		$db = mysql_select_db("contadoramig15");
		mysql_query("SET NAMES 'utf8'");
		mysql_query('SET character_set_connection=utf8');
		mysql_query('SET character_set_client=utf8');
		mysql_query('SET character_set_results=utf8');
		
	} 