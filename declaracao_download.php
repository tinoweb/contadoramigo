<?
session_start();

//echo $_GET['acao'];
//exit;

require_once 'conect.php' ;

require_once 'classes/numero_extenso_2.php'; 
require_once 'classes/fpdf/fpdf.php'; 

function get_nome_mes($numero_mes){
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
	return $arrMonth[(int)$numero_mes];
}

function data_extenso($data,$separador){

	$arrData = explode($separador,$data);
	$day = $arrData[2];
	$month = $arrData[1];
	$year = $arrData[0];
	
	return $day . " de " . get_nome_mes($month) . " de " . $year;
	
}
//pega dados da tabela empresa

$sql = "SELECT * FROM dados_da_empresa WHERE id='" . $_SESSION["id_empresaSecao"] . "' LIMIT 0, 1";
$resultado = mysql_query($sql) or die (mysql_error());
$linha_empresa=mysql_fetch_array($resultado);

// CHECANDO SE A VARIAVEL CONTENDO O ID DO AUTONOMO FOI PASSADA
if(isset($_REQUEST['aut'])){

	// PEGANDO DADOS DO AUTONOMO E DA EMPRESA ONDE RECOLHEU O INSS
	$sql = "SELECT 
				aut.nome
				, aut.cpf
				, autEmp.nome_empresa
				, autEmp.cidade_empresa
				, pagto.INSS_outra_fonte 
			FROM 
				dados_autonomos aut
				LEFT JOIN dados_pagamentos_empresas_anteriores autEmp ON aut.id = autEmp.id_trabalhador
				LEFT JOIN dados_pagamentos pagto ON aut.id = pagto.id_autonomo
			WHERE 
				aut.id = " . $_REQUEST['aut'] . "
				AND MONTH(data_pagto) = '" . substr($_REQUEST['data'],3,2) . "' AND YEAR(data_pagto) = '" . substr($_REQUEST['data'],6,4) . "'";
	
	$result = mysql_query($sql) or die (mysql_error());
	$linha = mysql_fetch_array($result);

	$pdf= new FPDF("P","mm","A4");
	
	$pdf->SetTopMargin(20);
	$pdf->AddPage();
	 
	$pdf->SetFont('arial','B',16);
	$pdf->SetleftMargin(20);
	$pdf->Cell(170,10,utf8_decode("DECLARAÇÃO"),0,1,'C');
	$pdf->Ln(10);
	
	$pdf->SetFont('arial','',12);
	$pdf->MultiCell(170,7,utf8_decode("Declaramos para os devidos fins que o(a) Sr.(a) " . $linha['nome'] . ", inscrito(a) no Cadastro de Pessoa Física (CPF) nº " . $linha['cpf'] . ", realizou serviços para esta empresa, contribuindo para o Instituto Nacional de Seguro Social (INSS), com o valor de R$ " . number_format($linha['INSS_outra_fonte'],2,",","'") . " no mês de " . get_nome_mes(
	((int)substr($_REQUEST['data'],3,2) == 1 ? 12 : (int)substr($_REQUEST['data'],3,2) - 1)
	) . "."),0,1);;
	$pdf->Ln(10);

	$pdf->SetFont('arial','',12);
	$pdf->MultiCell(170,7,utf8_decode("Informamos, outrossim, que esta declaração foi solicitada pelo(a) empregado(a) supra, que tem plenos conhecimentos das implicações legais que esta pode acarretar, sendo que o mesmo se encarrega de informar aos seus empregadores, em tempo hábil, quaisquer alterações que venham a ocorrer."),0,1);;
	$pdf->Ln(10);

	
	$pdf->MultiCell(170,5,utf8_decode("" . ($linha['cidade_empresa'] != '' ? mysql_real_escape_string($linha['cidade_empresa']) : '') . ", " . data_extenso(date('Y')."-".date('m')."-".date('d'),"-") . ".
	
	
	
	
	___________________________________
	" . ($linha['nome_empresa'] != '' ? mysql_real_escape_string($linha['nome_empresa']) : '' ) . "
	"),0,'L');
	$pdf->Ln(10);
	
	
	
	$pdf->MultiCell(170,5,utf8_decode("
	
	
	de acordo:
	
	
	
	
	___________________________________
	".$linha['nome']."
	"),0,'L');
	$pdf->Ln(10);
	
	
	
	$pdf->MultiCell(170,5,utf8_decode("
	
	
	___________________________________
	".$linha_empresa['razao_social']."
	"),0,'L');
	$pdf->Ln(10);
	
	
	
	//$pdf->Write(30, $corpo, '');
	
	$pdf->Output("DECLARACAO-". urldecode($linha['nome']) . "-".date('YmdHis').".pdf","D");
	
	//echo $corpo;
	

}


?>