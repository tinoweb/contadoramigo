<?
session_start();

//echo $_GET['acao'];
//exit;

require_once 'conect.php' ;

require_once 'classes/numero_extenso_2.php'; 
require_once 'classes/fpdf/fpdf.php'; 

function data_extenso($data,$separador){

	$arrData = explode($separador,$data);
	$day = $arrData[2];
	$month = $arrData[1];
	$arrMonth = array(
		1 => 'janeiro',
		2 => 'fevereiro',
		3 => 'março',
		4 => 'abril',
		5 => 'maio',
		6 => 'junho',
		7 => 'julho',
		8 => 'agosto',
		9 => 'setembro',
		10 => 'outubro',
		11 => 'novembro',
		12 => 'dezembro'
		);
	$year = $arrData[0];
	
	return $day . " de " . $arrMonth[(int)$month] . " de " . $year;
	
}
//pega dados da tabela empresa

$sql = "SELECT * FROM dados_da_empresa WHERE id='" . $_SESSION["id_empresaSecao"] . "' LIMIT 0, 1";
$resultado = mysql_query($sql) or die (mysql_error());
$linha_empresa=mysql_fetch_array($resultado);

// CHECANDO SE DEVE SER INSERIDO OU ALTERADO REGISTRO NA TABELA DE PAGAMENTOS
// SÓ É INSERIDO OU ALTERADO ALGUM REGISTRO NA TABELA DE PAGAMENTOS CASO O ACESSO VENHA DA PÁGINA DE REGISTRO DE PAGAMENTO
if(isset($_GET['acao'])){
	
	//insere os dados na tabela de pagamentos de pj
	$arrIdPJ = explode(';',$_REQUEST['NomeEmpresa']);
	$idPj = $arrIdPJ[0];
	$dataPagto = date('Y-m-d',mktime(0,0,0,substr($_REQUEST['DataPgto'],3,2),substr($_REQUEST['DataPgto'],0,2),substr($_REQUEST['DataPgto'],6,4)));
	$dataEmissao = date('Y-m-d',mktime(0,0,0,substr($_REQUEST['DataEmissao'],3,2),substr($_REQUEST['DataEmissao'],0,2),substr($_REQUEST['DataEmissao'],6,4)));
	$ValorBrutoPJ = str_replace(",",".",str_replace(".","",$_REQUEST['ValorBrutoPJ']));
	$RetencaoIR = str_replace(",",".",str_replace(".","",$_REQUEST['RetencaoIR']));
	
	$RetencaoISS = str_replace(",",".",str_replace(".","",$_REQUEST['RetencaoISS']));
	
	$ValorLiquido = str_replace(",",".",str_replace(".","",$_REQUEST['Liquido']));
	$atividades = $_REQUEST['atividades'];
	$codigoDARF = $_REQUEST['CodigoDARF'];
	
	$sqlAcao = "
			INSERT INTO dados_pagamentos (
				id_pagto
				, id_login
				, id_pj
				, valor_bruto
				, IR
				, ISS
				, valor_liquido
				, data_pagto
				, data_emissao
				, descricao_servico
				, codigo_servico
			) VALUES (
				null
				, " . $_SESSION["id_empresaSecao"] . "
				, " . $idPj . "
				, '" . $ValorBrutoPJ . "'
				, '" . $RetencaoIR . "'
				, '" . $RetencaoISS . "'
				, '" . $ValorLiquido . "'
				, '" . $dataPagto . "'
				, '" . $dataEmissao . "'
				, '" . $atividades . "'
				, '" . $codigoDARF . "'
			)";
		
	$resultInsert = mysql_query($sqlAcao) or die ("");

	echo mysql_insert_id();

	// PEGANDO O ID DO ULTIMO PAGAMENTO
	//$getIDPagto = mysql_fetch_array(mysql_query("
	//				SELECT 
	//					id_pagto 
	//				FROM 
	//					dados_pagamentos
	//				WHERE
	//					id_login = " . $_SESSION["id_empresaSecao"] . "
	//					AND id_pj = " . $idPj . "
	//					AND data_pagto = '" . $dataPagto . "'"));
	//$idPagto = $getIDPagto['id_pagto'];
	
}

//pega dados da tabela pj
//$sqlPJ = "SELECT * FROM dados_pj WHERE id='" . $linha_pagto["id_pj"] . "' LIMIT 0, 1";
//$resultadoPJ = mysql_query($sqlPJ) or die (mysql_error());
//$linha_pj = mysql_fetch_array($resultadoPJ);

//$_SESSION['mensagem_pagamento_cadastrado'] = 'Pagamento cadastrado com sucesso!';

//header('location: pagamento_pj.php');

?>