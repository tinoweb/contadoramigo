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

//pega dados da empresa que pagou INSS
$sql2 = "SELECT * FROM dados_pagamentos_empresas_anteriores WHERE id_trabalhador='" . $_REQUEST['NomeAutonomo'] . "'";
$resultado2 = mysql_query($sql2) or die (mysql_error());
$linha_empresa_anterior=mysql_fetch_array($resultado2);


// CHECANDO SE DEVE SER INSERIDO OU ALTERADO REGISTRO NA TABELA DE PAGAMENTOS
// SÓ É INSERIDO OU ALTERADO ALGUM REGISTRO NA TABELA DE PAGAMENTOS CASO O ACESSO VENHA DA PÁGINA DE REGISTRO DE PAGAMENTO
if(isset($_GET['acao'])){


	//$rs_desc_dependente = mysql_fetch_array(mysql_query("SELECT Desconto_Ir_Dependentes FROM tabelas WHERE ano_calendario = '" . substr($_REQUEST['DataPgto'],6,4) . "'"));
	//$desc_dependentes = $rs_desc_dependente['Desconto_Ir_Dependentes'];
	
	//$rs_dependentes = mysql_fetch_array(mysql_query("SELECT dependentes FROM dados_autonomos WHERE id = '" . $_REQUEST['NomeAutonomo'] . "'"));
	//$dependentes = $rs_dependentes['dependentes'];
	$desc_dependentes = $_REQUEST['hddValorDependentes'];
	
	$total_desconto_dependentes = $desc_dependentes;// * $dependentes;
	
		
	// SE HOUVER DADOS DA EMPRESA QUE RETEM INSS 
	if(mysql_num_rows($resultado2) > 0){
		// ATUALIZA
		mysql_query("UPDATE dados_pagamentos_empresas_anteriores SET nome_empresa = '" . mysql_real_escape_string($_REQUEST['NomeOutraFonte']) . "', cidade_empresa = '" . mysql_real_escape_string($_REQUEST['CidadeOutraFonte']) . "' WHERE id_trabalhador = '" . $_REQUEST['NomeAutonomo'] . "'" );
	}else{
		// INSERE
		mysql_query("INSERT INTO dados_pagamentos_empresas_anteriores (id_trabalhador, nome_empresa, cidade_empresa) VALUES ('" . $_REQUEST['NomeAutonomo'] . "','" . mysql_real_escape_string($_REQUEST['NomeOutraFonte']) . "', '" . mysql_real_escape_string($_REQUEST['CidadeOutraFonte']) . "');" );
	}
	
	//insere os dados na tabela de pagamentos de autonomos
	//$arrDadosAutonomo = explode('|',$_REQUEST['NomeAutonomo']);
	$idAutonomo = $_REQUEST['NomeAutonomo'];
	if(isset($_REQUEST['NomeTomador']) && $_REQUEST['NomeTomador'] != ''){
		$campo_tomador = ", id_tomador";
		$valor_tomador = ", " . $_REQUEST['NomeTomador'] . "";
	}else{
		$campo_tomador = "";
		$valor_tomador = "";
	}
	$dataPagto = date('Y-m-d',mktime(0,0,0,substr($_REQUEST['DataPgto'],3,2),substr($_REQUEST['DataPgto'],0,2),substr($_REQUEST['DataPgto'],6,4)));
	$outraFonte = $_REQUEST['OutraFontePagadora'] == 1 ? '1' : '0' ;
	$INSSOutraFonte = str_replace(",",".",str_replace(".","",$_REQUEST['INSSOutrafonte']));
	$ValorBruto = str_replace(",",".",str_replace(".","",$_REQUEST['ValorBruto']));
	$RetencaoINSS = str_replace(",",".",str_replace(".","",$_REQUEST['RetencaoINSS']));
	$RetencaoIR = str_replace(",",".",str_replace(".","",$_REQUEST['RetencaoIR']));
	$RetencaoISS = str_replace(",",".",str_replace(".","",$_REQUEST['RetencaoISS']));
	$ValorLiquido = str_replace(",",".",str_replace(".","",$_REQUEST['ValorLiquido']));
	
/*
	if($_GET['acao'] == 'alt'){
		$sqlAcao = "
				UPDATE dados_pagamentos SET 
					outra_fonte = " . $outraFonte . "
					, INSS_outra_fonte = '" . $INSSOutraFonte . "'
					, valor_bruto = '" . $ValorBruto . "'
					, INSS = '" . $RetencaoINSS . "'
					, IR = '" . $RetencaoIR . "'
					, ISS = '" . $RetencaoISS . "'
					, valor_liquido = '" . $ValorLiquido . "'
				WHERE
					id_login = " . $_SESSION["id_empresaSecao"] . "
					AND id_autonomo = " . $idAutonomo . "
					AND data_pagto = '" . $dataPagto . "'
	
				";
	}else{
*/

		$sqlAcao = "
					INSERT INTO dados_pagamentos (
						id_pagto
						, id_login
						, id_autonomo
						, data_pagto
						, outra_fonte
						, INSS_outra_fonte
						, valor_bruto
						, INSS
						, IR
						, ISS
						, valor_liquido
						, desconto_dependentes
						" . $campo_tomador . "
					) VALUES (
						null
						, " . $_SESSION["id_empresaSecao"] . "
						, " . $idAutonomo . "
						, '" . $dataPagto . "'
						, " . $outraFonte . "
						, '" . $INSSOutraFonte . "'
						, '" . $ValorBruto . "'
						, '" . $RetencaoINSS . "'
						, '" . $RetencaoIR . "'
						, '" . $RetencaoISS . "'
						, '" . $ValorLiquido . "'
						, '" . $total_desconto_dependentes . "'
						" . $valor_tomador . "
					)";
//	}
	//echo $sqlAcao;
	//exit;
	
	$resultInsert = mysql_query($sqlAcao) or die (mysql_error());

	// PEGANDO O ID DO ULTIMO PAGAMENTO
	$getIDPagto = mysql_fetch_array(mysql_query("
					SELECT 
						id_pagto 
					FROM 
						dados_pagamentos
					WHERE
						id_login = " . $_SESSION["id_empresaSecao"] . "
						AND id_autonomo = " . $idAutonomo . "
						AND data_pagto = '" . $dataPagto . "'"));
	$idPagto = $getIDPagto['id_pagto'];
	
	
	if($_GET['acao'] == 'alt'){
		echo 1;
	}else{
		echo mysql_insert_id();
	}
	
}else{
		

	// NA LISTAGEM HÁ UM LINK PARA IMPRESSAO DO RECIBO - ESTE VEM PELA VARIAVEL ID_PAGTO
	if(isset($_GET['id_pagto'])){
		$idPagto = ($_GET['id_pagto']);

		
		$sqlPagto = "SELECT * FROM dados_pagamentos WHERE id_pagto='" . $idPagto . "'";
		$resultadoPagto = mysql_query($sqlPagto) or die (mysql_error());
		$linha_pagto = mysql_fetch_array($resultadoPagto);
		
		//pega dados da tabela autonomos
		$sqlAutonomo = "SELECT * FROM dados_autonomos WHERE id='" . $linha_pagto["id_autonomo"] . "' LIMIT 0, 1";
		$resultadoAutonomo = mysql_query($sqlAutonomo) or die (mysql_error());
		$linha_autonomo = mysql_fetch_array($resultadoAutonomo);
		
		$pdf= new FPDF("P","mm","A4");
		
		$pdf->SetTopMargin(20);
		$pdf->AddPage();
		 
		$pdf->SetFont('arial','B',16);
		$pdf->SetFillColor(175);
		$pdf->SetleftMargin(20);
		$pdf->Cell(170,10,utf8_decode("RECIBO DE PAGAMENTO A AUTÔNOMO - RPA"),1,1,'C','true');
		$pdf->Ln(10);
		
		$pdf->SetFont('arial','',12);
		$pdf->MultiCell(170,7,utf8_decode("Recebi de " . $linha_empresa['razao_social'] . ", CNPJ " . $linha_empresa['cnpj'] . ", a importância líquida de R$ " . number_format($linha_pagto['valor_liquido'],2,',','.') . " (" . GExtenso::moeda(number_format($linha_pagto['valor_liquido'],2,"","")) . "), pela prestação de serviços de " . $linha_autonomo['tipo_servico'] . "."),0,1);
		$pdf->Ln(10);
		
		
		$pdf->SetFont('arial','B',12);
		$pdf->Cell(16,7,utf8_decode("Nome: "),0,0,'L');
		$pdf->SetFont('arial','',12);
		$pdf->Cell(174,7,utf8_decode($linha_autonomo['nome']),0,1,'L');
		
		$pdf->SetFont('arial','B',12);
		$pdf->Cell(43,7,utf8_decode("Inscrição Municipal: "),0,0,'L');
		$pdf->SetFont('arial','',12);
		$pdf->Cell(127,7,utf8_decode($linha_autonomo['inscr_municipal']),0,1,'L');
		
		$pdf->SetFont('arial','B',12);
		$pdf->Cell(13,7,utf8_decode("CPF: "),0,0,'L');
		$pdf->SetFont('arial','',12);
		$pdf->Cell(157,7,utf8_decode($linha_autonomo['cpf']),0,1,'L');
		
		$pdf->SetFont('arial','B',12);
		$pdf->Cell(13,7,utf8_decode("R.G.: "),0,0,'L');
		$pdf->SetFont('arial','',12);
		$pdf->Cell(35,7,utf8_decode($linha_autonomo['rg']),0,0,'L');
		$pdf->SetFont('arial','B',12);
		$pdf->Cell(35,7,utf8_decode("Orgão Emissor: "),0,0,'L');
		$pdf->SetFont('arial','',12);
		$pdf->Cell(87,7,utf8_decode($linha_autonomo['orgao_emissor']),0,1,'L');
		
		$pdf->SetFont('arial','B',12);
		$pdf->Cell(22,7,utf8_decode("Nº do PIS: "),0,0,'L');
		$pdf->SetFont('arial','',12);
		$pdf->Cell(148,7,utf8_decode($linha_autonomo['pis']),0,1,'L');
		
		$pdf->SetFont('arial','B',12);
		$pdf->Cell(23,7,utf8_decode("Endereço: "),0,0,'L');
		$pdf->SetFont('arial','',12);
		$pdf->MultiCell(147,7,utf8_decode($linha_autonomo['endereco'] . " - " . $linha_autonomo['cidade'] . "-" . $linha_autonomo['estado'] . " - CEP " . $linha_autonomo['cep']),0,'L');
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
		$pdf->Cell(35,7,utf8_decode("Desconto ISS"),0,0,'L');
		$pdf->SetFont('arial','',12);
		$pdf->Cell(100,7,str_repeat('.', 80),0,0,'L');
		$pdf->Cell(10,7,utf8_decode("R$"),0,0,'L');
		$pdf->Cell(25,7,number_format($linha_pagto['ISS'],2,',','.'),0,1,'R');
		
		$pdf->SetFont('arial','B',12);
		$pdf->Cell(35,7,utf8_decode("Valor Líquido"),0,0,'L');
		$pdf->SetFont('arial','',12);
		$pdf->Cell(100,7,str_repeat('.', 80),0,0,'L');
		$pdf->Cell(10,7,utf8_decode("R$"),0,0,'L');
		$pdf->Cell(25,7,number_format($linha_pagto['valor_liquido'],2,',','.'),0,1,'R');
		
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
		
		
		
		$pdf->MultiCell(170,5,utf8_decode("" . $linha_empresa['cidade'] . ", " . data_extenso($linha_pagto['data_pagto'],"-") . ".
		
		
		
		
		___________________________________
		".$linha_autonomo['nome']."
		"),0,'L');
		$pdf->Ln(10);
		
		//$pdf->Write(30, $corpo, '');
		
		$pdf->Output("RPA-". urldecode($linha_autonomo['nome']) . "-".str_replace('-','',$linha_pagto['data_pagto'])."-" .$linha_pagto['id_pagto']."-".date('YmdHis').".pdf","D");

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
}

?>