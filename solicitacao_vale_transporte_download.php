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

$solicitou_vale_transporte = false;


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

	$sql_funcionario = "SELECT f.*
	FROM dados_do_funcionario f
	WHERE f.id='" . $_SESSION["id_userSecao"] . "' AND f.idFuncionario = '" . $_GET['id'] . "' AND status > 0
	LIMIT 0, 1";
	$result_funcionario = mysql_query($sql_funcionario) or die (mysql_error());
	$linha_funcionario = mysql_fetch_array($result_funcionario);

	if($linha_funcionario['vale_transporte'] == '1'){
		$solicitou_vale_transporte = true;
	}
	
	$sql_transporte_ida = "SELECT t.*
	FROM dados_transporte_funcionario t
	WHERE t.idFuncionario='" . $linha_funcionario["idFuncionario"] . "' AND trajeto = 'ida' ORDER BY idTransporte
	";
	$result_transporte_ida = mysql_query($sql_transporte_ida) or die (mysql_error());

	$arrTransportesIda = array();

	if(mysql_num_rows($result_transporte_ida) > 0){
		$solicitou_vale_transporte = true;
		while($linha_transportes_ida = mysql_fetch_array($result_transporte_ida)){
			array_push($arrTransportesIda,array('tipo'=>$linha_transportes_ida['tipo'],'linha'=>$linha_transportes_ida['linha'],'empresa'=>$linha_transportes_ida['empresa'],'tarifa'=>$linha_transportes_ida['tarifa']));
		}
	}
	

	$sql_transporte_volta = "SELECT t.*
	FROM dados_transporte_funcionario t
	WHERE t.idFuncionario='" . $linha_funcionario["idFuncionario"] . "' AND trajeto = 'volta' ORDER BY idTransporte
	";

	$result_transporte_volta = mysql_query($sql_transporte_volta) or die (mysql_error());

	$arrTransportesVolta = array();

	if(mysql_num_rows($result_transporte_volta) > 0){
		$solicitou_vale_transporte = true;
		while($linha_transportes_volta = mysql_fetch_array($result_transporte_volta)){
			array_push($arrTransportesVolta,array('tipo'=>$linha_transportes_volta['tipo'],'linha'=>$linha_transportes_volta['linha'],'empresa'=>$linha_transportes_volta['empresa'],'tarifa'=>$linha_transportes_volta['tarifa']));
		}
	}
	
/*	$linhas_transporte_ida = "";
	$empresas_transporte_ida = "";

	$linhas_transporte_volta = "";
	$empresas_transporte_volta = "";
	
	if(mysql_num_rows($result_transporte_ida) > 0 || mysql_num_rows($result_transporte_volta) > 0){
		$count1 = 0;
		$solicitou_vale_transporte = true;
		while($linha_transporte_ida = mysql_fetch_array($result_transporte_ida)){
			$linhas_transporte_ida .= $linha_transporte_ida['linha'];
			$empresas_transporte_ida .= $linha_transporte_ida['empresa'];
			$count1++;
			if($count1 < mysql_num_rows($result_transporte_ida)){
				$linhas_transporte_ida .= ", ";
				$empresas_transporte_ida .= ", ";
			}
		}
		$count2 = 0;
		while($linha_transporte_volta = mysql_fetch_array($result_transporte_volta)){
			$linhas_transporte_volta .= $linha_transporte_volta['linha'];
			$empresas_transporte_volta .= $linha_transporte_volta['empresa'];
			$count2++;
			if($count2 < mysql_num_rows($result_transporte_volta)){
				$linhas_transporte_volta .= ", ";
				$empresas_transporte_volta .= ", ";
			}
		}
	}*/

	$pdf= new FPDF("P","mm","A4");
	
	$pdf->SetTopMargin(10);
	$pdf->AddPage();
	 
	$pdf->SetFont('arial','B',16);
	$pdf->SetLeftMargin(0);
	$pdf->Cell(190,10,utf8_decode("SOLICITAÇÃO DE VALE TRANSPORTE"),0,1,'C');
	$pdf->Ln(20);

	$pdf->SetFont('arial','B',9);
	$pdf->SetLeftMargin(13);
	$pdf->SetXY('13','25');

	$pdf->MultiCell(0,5,utf8_decode("Dados da empresa:"),0,'J');

	$pdf->SetFont('arial','',9);
	$pdf->MultiCell(0,5,utf8_decode($linha['razao_social'] . "
" . $linha['endereco'] . ($linha['bairro'] != '' ? ", " . $linha['bairro'] : "") . "
" . $linha['cidade'] . " - " . $linha['estado'] . "

"),0,'J');


	$pdf->SetFont('arial','B',9);
	$pdf->MultiCell(0,5,utf8_decode("Dados do empregado:"),0,'J');

	$pdf->SetFont('arial','',9);
	$pdf->MultiCell(0,5,utf8_decode($linha_funcionario['nome'] . "
" . $linha_funcionario['funcao'] . "
CTPS nº " . $linha_funcionario['ctps'] . " - série " . $linha_funcionario['serie_ctps'] . " - " . $linha_funcionario['estado'] . " 

( " . ($solicitou_vale_transporte ? "X" : "  ") . " ) Opto pela utilização do Vale Transporte
( " . ($solicitou_vale_transporte ? "  " : "X") . " ) Não opto pela utilização do Vale Transporte

Nos termos do artigo 7º. do Decreto nº 95247 de 17 de Novembro de 1987, solicito receber o Vale-Transporte e comprometo-me: 
a) a utilizá-lo exclusivamente para meu efetivo deslocamento residência-trabalho e vice-versa.
b) a renovar anualmente ou sempre que ocorrer alteração no meu endereço residencial ou dos serviços e meios de transportes mais adequado ao meu deslocamento residência-trabalho e vice-versa.
c) autorizo a descontar até 6% (seis por cento) do meu salário básico mensal pelo custeio do Vale-Transporte (conforme o artigo 9º do Decreto nº 95247/87).
d) Declaro estar ciente de que a declaração falsa ou o uso indevido do Vale-Transporte constituem falta grave (conforme inciso 3º do art. 7º)

Minha residência atual:
" . $linha_funcionario['endereco'] . ", " . $linha_funcionario['bairro'] . "
" . $linha_funcionario['cidade'] . " - " . $linha_funcionario['estado'] . "
"),0,'J');
$pdf->Ln(3);

if($solicitou_vale_transporte){
	$pdf->SetFont('arial','B',10);
	$pdf->Cell(190,10,utf8_decode("MEIO DE TRANSPORTE"),0,1,'C');
	$pdf->Ln(1);
	
	$pdf->SetFont('arial','B',8);
	$pdf->Cell(190,5,utf8_decode('RESIDÊNCIA - TRABALHO'),0,0,'L');
	$pdf->Ln(5);
	$pdf->SetFont('arial','B',6);
	$pdf->Cell(20,5,'TIPO','TBL',0,'C');
	$pdf->Cell(70,5,'LINHA','TBL',0,'C');
	$pdf->Cell(70,5,'EMPRESA','TBLR',0,'C');
	$pdf->Cell(30,5,'TARIFA','TBLR',0,'C');
	$pdf->Ln(5);
	$pdf->SetFont('arial','',8);
	for($i = 0; $i < count($arrTransportesIda); $i++ ){
		$pdf->Cell(20,6,utf8_decode($arrTransportesIda[$i]['tipo']),($i < count($arrTransportesIda)-1 ? 'L' : 'LB'),0,'L');
		$pdf->Cell(70,6,utf8_decode($arrTransportesIda[$i]['linha']),($i < count($arrTransportesIda)-1 ? 'L' : 'LB'),0,'L');
		$pdf->Cell(70,6,utf8_decode($arrTransportesIda[$i]['empresa']),($i < count($arrTransportesIda)-1 ? 'LR' : 'LBR'),0,'L');
		$pdf->Cell(30,6,$arrTransportesIda[$i]['tarifa'] != "" ? utf8_decode("R$ " . number_format($arrTransportesIda[$i]['tarifa'],2,',','.')) : "",($i < count($arrTransportesIda)-1 ? 'LR' : 'LBR'),0,'R');
		$total+=$arrTransportesIda[$i]['tarifa'];
	//	if($i < count($arrTransportesIda)-1){
			$pdf->Ln(6);	
	//	}
	}
	
	$pdf->Cell(20,6,'','BL',0,'L');
	$pdf->Cell(70,6,'','BL',0,'L');
	$pdf->Cell(70,6,'','BLR',0,'L');
	$pdf->Cell(30,6,$total > 0 ? utf8_decode("R$ " . number_format($total,2,',','.')) : "",'BLR',0,'R');
	$pdf->Ln(10);
	
	//$pdf->Cell(20,6,utf8_decode($arrTransportesIda[0]['tipo']),'TL',0,'L');
	//$pdf->Cell(70,6,utf8_decode($arrTransportesIda[0]['linha']),'TL',0,'L');
	//$pdf->Cell(70,6,utf8_decode($arrTransportesIda[0]['empresa']),'TLR',0,'L');
	//$pdf->Cell(30,6,$arrTransportesIda[0]['tarifa'] != "" ? utf8_decode("R$ " . number_format($arrTransportesIda[0]['tarifa'],2,',','.')) : "",'TLR',0,'R');
	//$pdf->Ln(6);
	//$pdf->Cell(20,6,utf8_decode($arrTransportesIda[1]['tipo']),'TL',0,'L');
	//$pdf->Cell(70,6,utf8_decode($arrTransportesIda[1]['linha']),'TL',0,'L');
	//$pdf->Cell(70,6,utf8_decode($arrTransportesIda[1]['empresa']),'TLR',0,'L');
	//$pdf->Cell(30,6,$arrTransportesIda[1]['tarifa'] != "" ? utf8_decode("R$ " . number_format($arrTransportesIda[1]['tarifa'],2,',','.')) : "",'TLR',0,'R');
	//$pdf->Ln(6);
	//$pdf->Cell(20,6,utf8_decode($arrTransportesIda[2]['tipo']),1,0,'L');
	//$pdf->Cell(70,6,utf8_decode($arrTransportesIda[2]['linha']),1,0,'L');
	//$pdf->Cell(70,6,utf8_decode($arrTransportesIda[2]['empresa']),1,0,'L');
	//$pdf->Cell(30,6,$arrTransportesIda[2]['tarifa'] != "" ? utf8_decode("R$ " . number_format($arrTransportesIda[2]['tarifa'],2,',','.')) : "",1,0,'R');
	//$pdf->Ln(10);
	
	$total = 0;
	
	$pdf->SetFont('arial','B',8);
	$pdf->Cell(190,5,utf8_decode('TRABALHO - RESIDÊNCIA'),0,0,'L');
	$pdf->Ln(5);
	$pdf->SetFont('arial','B',6);
	$pdf->Cell(20,5,'TIPO','TBL',0,'C');
	$pdf->Cell(70,5,'LINHA','TBL',0,'C');
	$pdf->Cell(70,5,'EMPRESA','TBLR',0,'C');
	$pdf->Cell(30,5,'TARIFA','TBLR',0,'C');
	$pdf->Ln(5);
	$pdf->SetFont('arial','',8);
	for($i = 0; $i < count($arrTransportesVolta); $i++ ){
		$pdf->Cell(20,6,utf8_decode($arrTransportesVolta[$i]['tipo']),($i < count($arrTransportesVolta)-1 ? 'L' : 'LB'),0,'L');
		$pdf->Cell(70,6,utf8_decode($arrTransportesVolta[$i]['linha']),($i < count($arrTransportesVolta)-1 ? 'L' : 'LB'),0,'L');
		$pdf->Cell(70,6,utf8_decode($arrTransportesVolta[$i]['empresa']),($i < count($arrTransportesVolta)-1 ? 'LR' : 'LBR'),0,'L');
		$pdf->Cell(30,6,$arrTransportesVolta[$i]['tarifa'] != "" ? utf8_decode("R$ " . number_format($arrTransportesVolta[$i]['tarifa'],2,',','.')) : "",($i < count($arrTransportesVolta)-1 ? 'LR' : 'LBR'),0,'R');
		$total+=$arrTransportesVolta[$i]['tarifa'];
	//	if($i < count($arrTransportesIda)-1){
			$pdf->Ln(6);	
	//	}
	}
	
	$pdf->Cell(20,6,'','BL',0,'L');
	$pdf->Cell(70,6,'','BL',0,'L');
	$pdf->Cell(70,6,'','BLR',0,'L');
	$pdf->Cell(30,6,$total > 0 ? utf8_decode("R$ " . number_format($total,2,',','.')) : "",'BLR',0,'R');
	$pdf->Ln(10);
	
	//
	//$pdf->Cell(20,6,utf8_decode($arrTransportesVolta[0]['tipo']),'TL',0,'L');
	//$pdf->Cell(70,6,utf8_decode($arrTransportesVolta[0]['linha']),'TL',0,'L');
	//$pdf->Cell(70,6,utf8_decode($arrTransportesVolta[0]['empresa']),'TLR',0,'L');
	//$pdf->Cell(30,6,$arrTransportesVolta[0]['tarifa'] != "" ? utf8_decode("R$ " . number_format($arrTransportesVolta[0]['tarifa'],2,',','.')) : "",'TLR',0,'R');
	//$pdf->Ln(6);
	//$pdf->Cell(20,6,utf8_decode($arrTransportesVolta[1]['tipo']),'TL',0,'L');
	//$pdf->Cell(70,6,utf8_decode($arrTransportesVolta[1]['linha']),'TL',0,'L');
	//$pdf->Cell(70,6,utf8_decode($arrTransportesVolta[1]['empresa']),'TLR',0,'L');
	//$pdf->Cell(30,6,$arrTransportesVolta[1]['tarifa'] != "" ? utf8_decode("R$ " . number_format($arrTransportesVolta[1]['tarifa'],2,',','.')) : "",'TLR',0,'R');
	//$pdf->Ln(6);
	//$pdf->Cell(20,6,utf8_decode($arrTransportesVolta[2]['tipo']),1,0,'L');
	//$pdf->Cell(70,6,utf8_decode($arrTransportesVolta[2]['linha']),1,0,'L');
	//$pdf->Cell(70,6,utf8_decode($arrTransportesVolta[2]['empresa']),1,0,'L');
	//$pdf->Cell(30,6,$arrTransportesVolta[2]['tarifa'] != "" ? utf8_decode("R$ " . number_format($arrTransportesVolta[2]['tarifa'],2,',','.')) : "",1,0,'R');
	//$pdf->Ln(10);

	$pdf->SetFont('arial','',10);
	$pdf->MultiCell(0,5,utf8_decode(($linha['cidade'] != '' ? mysql_real_escape_string($linha['cidade']) : '') . ", " . data_extenso(date('Y')."-".date('m')."-".date('d'),"-")),0,'R');
	
} else {

	$pdf->Ln(10);

	$pdf->SetFont('arial','',10);
	$pdf->MultiCell(0,5,utf8_decode(($linha['cidade'] != '' ? mysql_real_escape_string($linha['cidade']) : '') . ", " . data_extenso(date('Y')."-".date('m')."-".date('d'),"-")),0,'R');

	$pdf->Ln(40);
	
}


$pdf->SetFont('arial','B',10);
$pdf->MultiCell(0,5,utf8_decode("


	
___________________________________
" . ($linha_funcionario['nome'])),0,'C');


	$pdf->Output("VALE-TRANSPORTE-". urldecode($linha['nome']) . "-".date('YmdHis').".pdf","I"); // Visualização
//	$pdf->Output("VALE-TRANSPORTE-". urldecode($linha['nome']) . "-".date('YmdHis').".pdf","D"); // Download
	
?>