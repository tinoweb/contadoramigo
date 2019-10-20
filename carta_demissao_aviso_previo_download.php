<?
session_start();

//echo $_GET['acao'];
//exit;

require_once 'conect.php' ;

require_once 'classes/numero_extenso_2.php'; 
require_once 'classes/fpdf/fpdf.php'; 

$identacao = str_repeat("\t",10);

$txtPagadora = $_POST['txtPagadora'];
$txtCNPJ = $_POST['txtCNPJ'];
$nomeSocio = $_POST['txtNomeSocio'];
$tipoSocio = $_POST['txtTipoSocio'];

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

	$sql2 = "SELECT f.nome
	FROM dados_do_funcionario f
	WHERE f.id='" . $_SESSION["id_userSecao"] . "'
	LIMIT 0, 1";
	$result2 = mysql_query($sql2) or die (mysql_error());
	$linha2 = mysql_fetch_array($result2);

	$sql = "SELECT e.razao_social
	, e.cnpj
	, e.endereco
	, e.cidade
	, e.estado
	FROM dados_da_empresa e
	WHERE e.id='" . $_SESSION["id_userSecao"] . "'
	LIMIT 0, 1";
	
	$result = mysql_query($sql) or die (mysql_error());
	$linha = mysql_fetch_array($result);

	$pdf= new FPDF("P","mm","A4");
	
	$pdf->SetTopMargin(30);
	$pdf->AddPage();
	 
	$pdf->SetFont('arial','B',16);
	$pdf->SetLeftMargin(20);
	$pdf->Cell(170,10,utf8_decode("Demissão com cumprimento do aviso prévio"),0,1,'C');
	$pdf->Ln(20);

	$pdf->SetFont('arial','',10);
	$pdf->SetLeftMargin(13);
	$pdf->SetXY('13','65');

	$pdf->MultiCell(0,6,utf8_decode("À Empresa " . $linha['razao_social'] . "
	
Prezado(s) senhor(es),

Por motivos profissionais (ou pessoais), apresento por meio desta carta meu pedido de demissão, assim me desligando do cargo que ocupo nesta empresa.
Informo também que cumprirei o aviso prévio previsto por lei no período de DD/MM/AAAA a DD/MM/AAAA.
"),0,'J');
	$pdf->Ln(20);

	
	$pdf->MultiCell(0,5,utf8_decode(($linha['cidade'] != '' ? mysql_real_escape_string($linha['cidade']) : '') . ", " . data_extenso(date('Y')."-".date('m')."-".date('d'),"-")),0,'R');
	$pdf->MultiCell(0,5,utf8_decode("




	
	___________________________________
	" . ($linha2['nome'])),0,'C');

	$pdf->Output("DEMISSAO-AVISO-". urldecode($linha['nome']) . "-".date('YmdHis').".pdf","I"); // Visualização
//	$pdf->Output("DEMISSAO-AVISO-". urldecode($linha['nome']) . "-".date('YmdHis').".pdf","D"); // Download
	
?>