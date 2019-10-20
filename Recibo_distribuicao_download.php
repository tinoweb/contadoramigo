<?
session_start();

//echo $_GET['acao'];
//exit;

require_once 'conect.php' ;

require_once 'classes/numero_extenso_2.php'; 
require_once 'classes/fpdf/fpdf.php'; 

function data_extenso($data,$separador = ''){

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


	if($separador != ''){
		$arrData = explode($separador,$data);
		$day = $arrData[2];
		$month = $arrData[1];
		$year = $arrData[0];
		$mes = $arrMonth[(int)$month];
	}
	else{
		$day = '';
		$mes = '';
		$year = $data;
	}
	
	return ($day != '' ? $day . " de " : "") . ($mes != '' ? $mes . " de " : "") . $year;
	
}
//pega dados da tabela empresa

$sql = "SELECT * FROM dados_da_empresa WHERE id='" . $_SESSION["id_empresaSecao"] . "' LIMIT 0, 1";
$resultado = mysql_query($sql) or die (mysql_error());
$linha_empresa=mysql_fetch_array($resultado);

// CHECANDO SE DEVE SER INSERIDO OU ALTERADO REGISTRO NA TABELA DE PAGAMENTOS
// SÓ É INSERIDO OU ALTERADO ALGUM REGISTRO NA TABELA DE PAGAMENTOS CASO O ACESSO VENHA DA PÁGINA DE REGISTRO DE PAGAMENTO
if(isset($_GET['acao'])){

	//insere os dados na tabela de pagamentos 
	//$arrDadosSocio = explode('|',$_REQUEST['Nome']);
	$idSocio = ($_REQUEST['id']);
	$dtPagto = ($_REQUEST['DataPgto']);

	switch(strlen($_REQUEST['periodo'])){
		case 4:
			$periodo = $_REQUEST['periodo'];
			$separador_data = '';
			$descr_periodo = 'ano-base';
		break;
		case 7:
			$periodo = date('Y-m',mktime(0,0,0,substr($_REQUEST['periodo'],0,2),1,substr($_REQUEST['periodo'],3,4)));
			$separador_data = '-';
			$descr_periodo = 'mês';
		break;
		default:
			$periodo = date('Y-m-d',mktime(0,0,0,substr($_REQUEST['periodo'],3,2),substr($_REQUEST['periodo'],0,2),substr($_REQUEST['periodo'],6,4)));
			$separador_data = '-';
			$descr_periodo = 'período';
		break;
	}

//	$dataPagto = date('Y-m-d',mktime(0,0,0,date('m'),date('d'),date('Y')));
	$dataPagto = date('Y-m-d',mktime(0,0,0,substr($dtPagto,3,2),substr($dtPagto,0,2),substr($dtPagto,6,4)));

//	$periodo = date('m/Y',mktime(0,0,0,substr($_REQUEST['DataPgto'],0,2),'01',substr($_REQUEST['DataPgto'],3,4)));
	$ValorLiquido = str_replace(",",".",str_replace(".","",$_REQUEST['ValorLiquido']));
	
	if($_GET['acao'] == 'ins'){
		$sqlAcao = "
					INSERT INTO dados_pagamentos (
						id_pagto
						, id_login
						, id_lucro
						, data_pagto
						, data_periodo_ini
						, valor_liquido
					) VALUES (
						null
						, " . $_SESSION["id_empresaSecao"] . "
						, " . $idSocio . "
						, '" . $dataPagto . "'
						, '" . $periodo . "'
						, '" . $ValorLiquido . "'
					)";
	}
//	echo $sqlAcao;
//	exit;
	
	$resultInsert = mysql_query($sqlAcao) or die (mysql_error());
	
	
	
	if($_GET['acao'] == 'alt'){
		echo 1;
	}else{
		echo mysql_insert_id();
	}
	
}else{
		
	
	// NA LISTAGEM HÁ UM LINK PARA IMPRESSAO DO RECIBO - ESTE VEM PELA VARIAVEL ID_PAGTO
	if(isset($_GET['id_pagto'])){
	
		$linha_pagto = mysql_fetch_array(mysql_query("SELECT * FROM dados_pagamentos WHERE id_pagto='" . $_GET["id_pagto"] . "'")) or die (mysql_error());
	
	
		$dataPagto = date('Y-m-d',strtotime($linha_pagto['data_pagto']));
	
	}else{
	
		$linha_pagto = mysql_fetch_array(mysql_query("SELECT * FROM dados_pagamentos WHERE id_login='" . $_SESSION["id_empresaSecao"] . "' AND id_lucro = '".$idSocio."' ORDER BY id_pagto DESC LIMIT 0, 1")) or die (mysql_error());
	
		$dataPagto = date('Y-m-d',strtotime($linha_pagto['data_pagto']));
	
	}
	
	
	switch(strlen($linha_pagto['data_periodo_ini'])){
		case 4:
			$periodo = $linha_pagto['data_periodo_ini'];
			$separador_data = '';
			$descr_periodo = 'ano-base';
		break;
		case 7:
			$periodo = $linha_pagto['data_periodo_ini'];
	//		$periodo = date('Y-m',mktime(0,0,0,substr($_REQUEST['DataPgto'],0,2),1,substr($_REQUEST['DataPgto'],3,4)));
			$separador_data = '-';
			$descr_periodo = 'mês';
		break;
		default:
			$periodo = $linha_pagto['data_periodo_ini'];
	//		$periodo = date('Y-m-d',mktime(0,0,0,substr($_REQUEST['DataPgto'],3,2),substr($_REQUEST['DataPgto'],0,2),substr($_REQUEST['DataPgto'],6,4)));
			$separador_data = '-';
			$descr_periodo = 'período';
		break;
	}
	
	
	//pega dados da tabela de reponsavel
	$sqlSocio = "SELECT * FROM dados_do_responsavel WHERE idSocio='" . $linha_pagto["id_lucro"] . "' LIMIT 0, 1";
	//	echo $sqlSocio ;
	//	exit;
	$resultadoSocio = mysql_query($sqlSocio) or die (mysql_error());
	$linha_socio = mysql_fetch_array($resultadoSocio);
	
	$pdf= new FPDF("P","mm","A4");
	
	$pdf->SetTopMargin(20);
	$pdf->AddPage();
	 
	$pdf->SetFont('arial','B',16);
	$pdf->SetFillColor(175);
	$pdf->SetleftMargin(20);
	$pdf->Cell(170,10,utf8_decode("RECIBO DE DISTRIBUIÇÃO DE LUCROS"),1,1,'C','true');
	$pdf->Ln(10);
	
	$pdf->SetFont('arial','',12);
	$pdf->MultiCell(170,7,utf8_decode("Eu, " . $linha_socio['nome'] . ", recebi a importância de R$ " . number_format($linha_pagto['valor_liquido'],2,',','.') . " (" . GExtenso::moeda(number_format($linha_pagto['valor_liquido'],2,"","")) . ") referente a Distribuição de Lucros relativos ao " . $descr_periodo . " de " . data_extenso($periodo,$separador_data) . " da Empresa " . $linha_empresa['razao_social'] . ", CNPJ " . $linha_empresa['cnpj'] . "."),0,1);
	$pdf->Ln(20);
	
	$pdf->SetFont('arial','',12);
	$pdf->MultiCell(170,7,utf8_decode("Para maior clareza, firmo o presente."),0,1);
	$pdf->Ln(10);
	
	
	/*
	$pdf->MultiCell(190,10,utf8_decode("Discriminação de valores para o pagamento dos serviços prestados:
	Valor Bruto " . str_repeat('.', 20) . " R$ " . number_format($linha_pagto['valor_bruto'],2,',','.') . "
	Desconto INSS " . str_repeat('.', 38) . " R$ " . number_format($linha_pagto['INSS'],2,',','.') . "
	Desconto IRRF " . str_repeat('.', 38) . " R$ " . number_format($linha_pagto['IR'],2,',','.') . "
	Desconto ISS " . str_repeat('.', 39) . " R$ " . number_format($linha_pagto['ISS'],2,',','.') . "
	Valor Líquido " . str_repeat('.', 38) . " R$ " . number_format($linha_pagto['valor_liquido'],2,',','.') . "
	"),1,1);
	*/
	$pdf->Ln(20);
	
	
	
	$pdf->MultiCell(170,5,utf8_decode("" . $linha_empresa['cidade'] . ", " . data_extenso($dataPagto,"-") . ".
	
	
	
	
	___________________________________
	".$linha_socio['nome']."
	"),0,'L');
	$pdf->Ln(10);
	
	//$pdf->Write(30, $corpo, '');
	
	$pdf->Output("RECIBO_DISTR_LUCROS-". urldecode($linha_socio['nome']) . "-".str_replace('/','',$periodo)."-" .$linha_pagto['id_pagto']."-".date('YmdHis').".pdf","D");
	
	//echo $corpo;
	
	/*
	exit;
	
	$file = 'sefip.re';
	
	if (file_exists($file)) {
			header('Content-Description: File Transfer');
			header('Content-type: application/pdf');
			header('Content-Disposition: attachment; filename='.basename($file));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			ob_clean();
			flush();
			readfile($file);
			exit;
	}*/
}
?>