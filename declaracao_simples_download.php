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

	$sql = "SELECT e.razao_social
	, e.cnpj
	, e.endereco
	, e.cidade
	, e.estado
	FROM dados_da_empresa e
	WHERE e.id='" . $_SESSION["id_empresaSecao"] . "'
	LIMIT 0, 1";
	
	$result = mysql_query($sql) or die (mysql_error());
	$linha = mysql_fetch_array($result);

	$pdf= new FPDF("P","mm","A4");
	
	$pdf->SetTopMargin(20);
	$pdf->AddPage();
	 
	$pdf->SetFont('arial','B',16);
	$pdf->SetLeftMargin(20);
	$pdf->Cell(170,10,utf8_decode("DECLARAÇÃO"),0,1,'C');
	$pdf->Ln(10);

	$pdf->SetFont('arial','',10);
	$pdf->SetLeftMargin(13);
	$pdf->SetXY('13','55');

	$pdf->MultiCell(0,6,utf8_decode("Ilmo. Sr.
	
" . $identacao . $txtPagadora . " - CNPJ " . $txtCNPJ . "

" . $identacao . $linha['razao_social'] . ", com sede à " . $linha['endereco'] . " - " . $linha['cidade'] . " - " . $linha['estado'] . ", inscrita no CNPJ sob o nº " . $linha['cnpj'] . " declara à " . $txtPagadora . ", para fins de não incidência na fonte da Contribuição Social sobre o Lucro Líquido (CSLL), da Contribuição para o Financiamento da Seguridade Social (Confins), e da Contribuição para o PIS/PASEP, a que se refere o art. 30 da Lei nº l 0.833, de 29 de dezembro de 2003, que e regularmente inscrita no Regime Especial Unificado de Arrecadação de Tributos e Contribuições devidos pelas Microempresas e Empresas de Pequeno Porte- Simples Nacional, de que trata o art. 12 da Lei Complementar nº 123, de 14 de dezembro de 2006.

" . $identacao . "Para esse efeito, a declarante informa que:

" . $identacao . "I - preenche os seguintes requisitos:
" . $identacao . "a) conserva em boa ordem, pelo prazo de cinco anos, contado da data da emissão, os documentos que comprovam a origem de suas receitas e a efetivação de suas despesas, bem assim a realização de quaisquer outros atos ou operações que venham a modificar sua situação patrimonial;
" . $identacao . "b) cumpre as obrigações acessórias a que esta sujeita, em conformidade com a legislação pertinente;

" . $identacao . "II - o signatário e representante legal desta empresa, assumindo o compromisso de informar a Secretaria da Receita Federal do Brasil e a pessoa jurídica pagadora, imediatamente, eventual desenquadramento da presente situação e esta ciente de que a falsidade na prestação destas informações, sem prejuízo do disposto no art. 32 da Lei nº 9.430, de 1996, o sujeitara, juntamente com as demais pessoas que para ela concorrem, as penalidades previstas na legislação criminal e tributaria, relativas a falsidade ideológica (art. 299 do Código Penal) e ao crime contra a ordem tributária (art. 12 da Lei nº 8.137, de 27 de dezembro de 1990)."),0,'J');
	$pdf->Ln(10);

	
	$pdf->MultiCell(0,5,utf8_decode(($linha['cidade'] != '' ? mysql_real_escape_string($linha['cidade']) : '') . ", " . data_extenso(date('Y')."-".date('m')."-".date('d'),"-")),0,'R');
	$pdf->MultiCell(0,5,utf8_decode("




	
	___________________________________
	" . ($nomeSocio) . "
	" . ($tipoSocio) . "
" . $linha['razao_social']),0,'C');

//	$pdf->Output("DECLARACAO-SIMPLES-". urldecode($linha['nome']) . "-".date('YmdHis').".pdf","I"); // Visualização
	$pdf->Output("DECLARACAO-SIMPLES-". urldecode($linha['nome']) . "-".date('YmdHis').".pdf","D"); // Download
	
?>