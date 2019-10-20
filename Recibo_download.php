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

	//insere os dados na tabela de pagamentos 
	//$arrDadosSocio = explode('|',$_REQUEST['Nome']);
	$idSocio = ($_REQUEST['Nome']);
	$dataPagto = date('Y-m-d',mktime(0,0,0,substr($_REQUEST['DataPgto'],3,2),substr($_REQUEST['DataPgto'],0,2),substr($_REQUEST['DataPgto'],6,4)));
	$outraFonte = $_REQUEST['OutraFontePagadora'] == 1 ? '1' : '0' ;
	$INSSOutraFonte = str_replace(",",".",str_replace(".","",$_REQUEST['INSSOutraFonte']));
	$ValorBruto = str_replace(",",".",str_replace(".","",$_REQUEST['ValorBruto']));
	$RetencaoINSS = str_replace(",",".",str_replace(".","",$_REQUEST['RetencaoINSS']));
	$RetencaoIR = str_replace(",",".",str_replace(".","",$_REQUEST['RetencaoIR']));
	$ValorLiquido = str_replace(",",".",str_replace(".","",$_REQUEST['ValorLiquido']));

//	$rs_desc_dependente = mysql_fetch_array(mysql_query("SELECT Desconto_Ir_Dependentes FROM tabelas WHERE ano_calendario = '" . substr($_REQUEST['DataPgto'],6,4) . "'"));
//	$desc_dependentes = $rs_desc_dependente['Desconto_Ir_Dependentes'];
	$desc_dependentes = $_REQUEST['hddValorDependentes'];
	
	//$rs_dependentes = mysql_fetch_array(mysql_query("SELECT dependentes FROM dados_do_responsavel WHERE idSocio = '" . $idSocio . "'"));
	//$dependentes = $rs_dependentes['dependentes'];
	
	$total_desconto_dependentes = $desc_dependentes;// * $dependentes;
	
	if($_GET['acao'] == 'alt'){
		$sqlAcao = "
				UPDATE dados_pagamentos SET 
					outra_fonte = " . $outraFonte . "
					, INSS_outra_fonte = '" . $INSSOutraFonte . "'
					, valor_bruto = '" . $ValorBruto . "'
					, INSS = '" . $RetencaoINSS . "'
					, IR = '" . $RetencaoIR . "'
					, valor_liquido = '" . $ValorLiquido . "'
					, desconto_dependentes = '" . $total_desconto_dependentes . "'
				WHERE
					id_login = " . $_SESSION["id_empresaSecao"] . "
					AND id_socio = " . $idSocio . "
					AND data_pagto = '" . $dataPagto . "'
	
				";
	}else{
		$sqlAcao = "
					INSERT INTO dados_pagamentos (
						id_pagto
						, id_login
						, id_socio
						, data_pagto
						, outra_fonte
						, INSS_outra_fonte
						, valor_bruto
						, INSS
						, IR
						, valor_liquido
						, desconto_dependentes
					) VALUES (
						null
						, " . $_SESSION["id_empresaSecao"] . "
						, " . $idSocio . "
						, '" . $dataPagto . "'
						, " . $outraFonte . "
						, '" . $INSSOutraFonte . "'
						, '" . $ValorBruto . "'
						, '" . $RetencaoINSS . "'
						, '" . $RetencaoIR . "'
						, '" . $ValorLiquido . "'
						, '" . $total_desconto_dependentes . "'
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
		$sqlPagto = "SELECT * FROM dados_pagamentos WHERE id_login='" . $_SESSION["id_empresaSecao"] . "' AND id_socio = '".$idSocio."' ORDER BY id_pagto DESC LIMIT 0, 1";
	}
	//	echo $sqlPagto ;
	//	exit;
	
	$resultadoPagto = mysql_query($sqlPagto) or die (mysql_error());
	$linha_pagto = mysql_fetch_array($resultadoPagto);
	
	//pega dados da tabela de reponsavel
	$sqlSocio = "SELECT * FROM dados_do_responsavel WHERE idSocio='" . $linha_pagto["id_socio"] . "' LIMIT 0, 1";
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
	$pdf->Cell(170,10,utf8_decode("RECIBO DE PRÓ-LABORE"),1,1,'C','true');
	$pdf->Ln(10);
	
	$pdf->SetFont('arial','',12);
	$pdf->MultiCell(170,7,utf8_decode("Recebi de " . $linha_empresa['razao_social'] . ", CNPJ " . $linha_empresa['cnpj'] . ", a importância líquida de R$ " . number_format($linha_pagto['valor_liquido'],2,',','.') . " (" . GExtenso::moeda(number_format($linha_pagto['valor_liquido'],2,"","")) . "), como pró-labore pela função de " . $linha_socio['funcao'] . " na empresa."),0,1);
	$pdf->Ln(10);
	
	
	$pdf->SetFont('arial','B',12);
	$pdf->Cell(16,7,utf8_decode("Nome: "),0,0,'L');
	$pdf->SetFont('arial','',12);
	$pdf->Cell(174,7,utf8_decode($linha_socio['nome']),0,1,'L');
	
	$pdf->SetFont('arial','B',12);
	$pdf->Cell(13,7,utf8_decode("CPF: "),0,0,'L');
	$pdf->SetFont('arial','',12);
	$pdf->Cell(157,7,utf8_decode($linha_socio['cpf']),0,1,'L');
	
	if(!empty($linha_socio['rg'])) {
		$pdf->SetFont('arial','B',12);
		$pdf->Cell(13,7,utf8_decode("R.G.: "),0,0,'L');
		$pdf->SetFont('arial','',12);
		$pdf->Cell(35,7,utf8_decode($linha_socio['rg']),0,0,'L');
		
	} elseif(!empty($linha_socio['rne'])) {
		$pdf->SetFont('arial','B',12);
		$pdf->Cell(13,7,utf8_decode("RNE: "),0,0,'L');
		$pdf->SetFont('arial','',12);
		$pdf->Cell(35,7,utf8_decode($linha_socio['rne']),0,0,'L');
		
	} else {
		$pdf->SetFont('arial','B',12);
		$pdf->Cell(13,7,utf8_decode("R.G.: "),0,0,'L');
		$pdf->SetFont('arial','',12);
		$pdf->Cell(35,7,utf8_decode($linha_socio['rg']),0,0,'L');
	}
	
	$pdf->SetFont('arial','B',12);
	$pdf->Cell(35,7,utf8_decode("Orgão Emissor: "),0,0,'L');
	$pdf->SetFont('arial','',12);
	$pdf->Cell(87,7,utf8_decode($linha_socio['orgao_expeditor']),0,1,'L');
	
	$pdf->SetFont('arial','B',12);
	$pdf->Cell(22,7,utf8_decode("Nº do PIS: "),0,0,'L');
	$pdf->SetFont('arial','',12);
	$pdf->Cell(148,7,utf8_decode($linha_socio['nit']),0,1,'L');
	
	$pdf->SetFont('arial','B',12);
	$pdf->Cell(23,7,utf8_decode("Endereço: "),0,0,'L');
	$pdf->SetFont('arial','',12);
	$pdf->MultiCell(147,7,utf8_decode($linha_socio['endereco'] . " - " . $linha_socio['cidade'] . "-" . $linha_socio['estado'] . " - CEP " . $linha_socio['cep']),0,'L');
	$pdf->Ln(10);
	
	
	$pdf->Cell(170,7,utf8_decode("Discriminação de valores para o pagamento dos serviços prestados:"),0,1,'L');
	$pdf->Ln(5);
	$pdf->SetFont('arial','B',12);
	$pdf->Cell(35,7,utf8_decode("Valor Bruto"),0,0,'L');
	$pdf->SetFont('arial','',12);
	$pdf->Cell(100,7,str_repeat('.', 80),0,0,'L');
	$pdf->Cell(10,7,utf8_decode("R$"),0,0,'L');
	$pdf->Cell(25,7,number_format($linha_pagto['valor_bruto'],2,',','.'),0,1,'R');
	
	$pdf->SetFont('arial','B',12);
	$pdf->Cell(35,7,utf8_decode("Desconto INSS"),0,0,'L');
	$pdf->SetFont('arial','',12);
	$pdf->Cell(100,7,str_repeat('.', 80),0,0,'L');
	$pdf->Cell(10,7,utf8_decode("R$"),0,0,'L');
	$pdf->Cell(25,7,number_format($linha_pagto['INSS'],2,',','.'),0,1,'R');
	
	$pdf->SetFont('arial','B',12);
	$pdf->Cell(35,7,utf8_decode("Desconto IRRF"),0,0,'L');
	$pdf->SetFont('arial','',12);
	$pdf->Cell(100,7,str_repeat('.', 80),0,0,'L');
	$pdf->Cell(10,7,utf8_decode("R$"),0,0,'L');
	$pdf->Cell(25,7,number_format($linha_pagto['IR'],2,',','.'),0,1,'R');
	
	$pdf->SetFont('arial','B',12);
	$pdf->Cell(35,7,utf8_decode("Valor Líquido"),0,0,'L');
	$pdf->SetFont('arial','',12);
	$pdf->Cell(100,7,str_repeat('.', 80),0,0,'L');
	$pdf->Cell(10,7,utf8_decode("R$"),0,0,'L');
	$pdf->Cell(25,7,number_format($linha_pagto['valor_liquido'],2,',','.'),0,1,'R');
	
	
	$pdf->Ln(20);
	
	
	
	$pdf->MultiCell(170,5,utf8_decode("" . $linha_empresa['cidade'] . ", " . data_extenso($linha_pagto['data_pagto'],"-") . ".
	
	
	
	
	___________________________________
	".$linha_socio['nome']."
	"),0,'L');
	$pdf->Ln(10);
	
	//$pdf->Write(30, $corpo, '');
	
	$pdf->Output("RECIBO-". urldecode($linha_socio['nome']) . "-".str_replace('-','',$linha_pagto['data_pagto'])."-" .$linha_pagto['id_pagto']."-".date('YmdHis').".pdf","D");
}
?>