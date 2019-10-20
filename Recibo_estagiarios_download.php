<?
session_start();

//echo $_GET['acao'];
//exit;

require_once 'conect.php' ;

require_once 'classes/numero_extenso_2.php'; 
require_once 'classes/fpdf/fpdf.php'; 

function mes_extenso($mes){
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

	return $arrMonth[(int)$mes];

}

function data_extenso($data,$separador){

	$arrData = explode($separador,$data);
	$day = $arrData[2];
	$month = $arrData[1];
	$year = $arrData[0];
	
	return $day . " de " . mes_extenso((int)$month) . " de " . $year;
	
}
//pega dados da tabela empresa

$sql = "SELECT * FROM dados_da_empresa WHERE id='" . $_SESSION["id_empresaSecao"] . "' LIMIT 0, 1";
$resultado = mysql_query($sql) or die (mysql_error());
$linha_empresa=mysql_fetch_array($resultado);

// CHECANDO SE DEVE SER INSERIDO OU ALTERADO REGISTRO NA TABELA DE PAGAMENTOS
// SÓ É INSERIDO OU ALTERADO ALGUM REGISTRO NA TABELA DE PAGAMENTOS CASO O ACESSO VENHA DA PÁGINA DE REGISTRO DE PAGAMENTO
if(isset($_GET['acao'])){

	//insere os dados na tabela de pagamentos 
	$idEstagiario = ($_REQUEST['txtnome']);
	$dataPagto = date('Y-m-d',mktime(0,0,0,substr($_REQUEST['DataPgto'],3,2),substr($_REQUEST['DataPgto'],0,2),substr($_REQUEST['DataPgto'],6,4)));
	$mes_extenso = mes_extenso((int)substr($_REQUEST['DataPgto'],3,2));
	$ValorBruto = str_replace(",",".",str_replace(".","",$_REQUEST['ValorBruto']));
	
	if($_GET['acao'] == 'alt'){
		$sqlAcao = "
				UPDATE dados_pagamentos SET 
					valor_bruto = '" . $ValorBruto . "'
					, valor_liquido = '" . $ValorBruto . "'
				WHERE
					id_login = " . $_SESSION["id_empresaSecao"] . "
					AND id_estagiario = " . $idEstagiario . "
					AND data_pagto = '" . $dataPagto . "'
	
				";
	}else{
		$sqlAcao = "
					INSERT INTO dados_pagamentos (
						id_pagto
						, id_login
						, id_estagiario
						, data_pagto
						, valor_bruto
						, valor_liquido
					) VALUES (
						null
						, " . $_SESSION["id_empresaSecao"] . "
						, " . $idEstagiario . "
						, '" . $dataPagto . "'
						, '" . $ValorBruto . "'
						, '" . $ValorBruto . "'
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
		$sqlPagto = "SELECT * FROM dados_pagamentos WHERE id_pagto='" . $_GET["id_pagto"] . "'";
	}else{
		$sqlPagto = "SELECT * FROM dados_pagamentos WHERE id_login='" . $_SESSION["id_empresaSecao"] . "' AND id_estagiario= '".$idEstagiario."' ORDER BY id_pagto DESC LIMIT 0, 1";
	}
	//	echo $sqlPagto ;
	//	exit;
	
	$resultadoPagto = mysql_query($sqlPagto) or die (mysql_error());
	$linha_pagto = mysql_fetch_array($resultadoPagto);
	
	//pega dados do estagiario
	$sqlEstagiario = "SELECT * FROM estagiarios WHERE id='" . $linha_pagto["id_estagiario"] . "' LIMIT 0, 1";
	//	echo $sqlSocio ;
	//	exit;
	$resultadoEstagiario = mysql_query($sqlEstagiario) or die (mysql_error());
	$linha_estagiario = mysql_fetch_array($resultadoEstagiario);
	
	$pdf= new FPDF("P","mm","A4");
	
	$pdf->SetTopMargin(20);
	$pdf->AddPage();
	 
	$pdf->SetFont('arial','B',16);
	$pdf->SetFillColor(175);
	$pdf->SetleftMargin(20);
	$pdf->Cell(170,10,utf8_decode("RECIBO BOLSA-AUXÍLIO ESTÁGIO"),1,1,'C','true');
	$pdf->Ln(30);
	
	$pdf->SetFont('arial','',12);
	$pdf->MultiCell(170,7,utf8_decode("Recebi da empresa " . $linha_empresa['razao_social'] . ", com sede à " . $linha_empresa['endereco'] . ", inscrita no CNPJ sob n° " . $linha_empresa['cnpj'] . ", o valor de R$ " . number_format($linha_pagto['valor_bruto'],2,',','.') . " (" . GExtenso::moeda(number_format($linha_pagto['valor_bruto'],2,"","")) . "), referente a Bolsa-Auxílio Estágio, conforme Acordo de Cooperação e Termo de Compromisso de Estágio firmado entre as partes."),0,1);
	$pdf->Ln(70);
	
	
	
	
	$pdf->MultiCell(170,5,utf8_decode("" . $linha_empresa['cidade'] . ", " . data_extenso($linha_pagto['data_pagto'],"-") . ".
	
	
	
	
	___________________________________
	".$linha_estagiario['nome']."
	"),0,'L');
	$pdf->Ln(10);
	
	//$pdf->Write(30, $corpo, '');
	
	$pdf->Output("RECIBO-BOLSA-ESTAGIO-". urldecode($linha_estagiario['nome']) . "-".str_replace('-','',$linha_pagto['data_pagto'])."-" .$linha_pagto['id_pagto']."-".date('YmdHis').".pdf","D");
	
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