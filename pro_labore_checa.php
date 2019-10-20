<?
session_start();

require_once 'conect.php' ;

	// CHECANDO SE HÁ ALGUM CAMPO RELEVANTE À GERAÇÃO DO RECIBO QUE NÃO ESTÁ PREENCHIDO
	$sqlSocio = "SELECT * FROM dados_do_responsavel WHERE idSocio='" . $_GET['idSocio'] . "' LIMIT 0, 1";
	//	echo $sqlSocio ;
	//	exit;
	$resultadoSocio = mysql_query($sqlSocio) or die (mysql_error());
	
	if(mysql_num_rows($resultadoSocio) > 0){

		$linha_socio = mysql_fetch_array($resultadoSocio);

		// EXISTE ALGUM CAMPO RELEVANTE QUE ESTÁ VAZIO
		if($linha_socio['funcao'] == ''){
			echo '2|Função';
			exit;
		}
		if($linha_socio['nome'] == ''){
			echo '2|Nome';
			exit;
		}
		if($linha_socio['cpf'] == ''){
			echo '2|CPF';
			exit;
		}
		if($linha_socio['rg'] == '' and $linha_socio['rne'] == ''){
			echo '2|RG';
			exit;
		}
		if($linha_socio['orgao_expeditor'] == ''){
			echo '2|Órgão Emissor';
			exit;
		}
		if($linha_socio['nit'] == ''){
			echo '2|PIS';
			exit;
		}
		if($linha_socio['endereco'] == ''){
			echo '2|Endereço';
			exit;
		}
		if($linha_socio['cidade'] == ''){
			echo '2|Cidade';
			exit;
		}
		if($linha_socio['estado'] == ''){
			echo '2|Estado';
			exit;
		}
		if($linha_socio['cep'] == ''){
			echo '2|CEP';
			exit;
		}
		
		
	}
		
	$sql = "SELECT * FROM dados_pagamentos WHERE id_login='" . $_GET['id'] . "' AND id_socio = '" . $_GET['idSocio'] . "' AND MONTH(data_pagto) = '" . substr($_GET['data'],3,2) . "' AND YEAR(data_pagto) = '" . substr($_GET['data'],6,4) . "' LIMIT 0, 1";
			
	$resultado = mysql_query($sql) or die (mysql_error());
	
	if(mysql_num_rows($resultado) > 0){
		echo '1';
	}else{
		// CORREU TUDO BEM E FARÁ O INSERT DOS DADOS DE PAGAMENTO
		echo '0';
	}

?>