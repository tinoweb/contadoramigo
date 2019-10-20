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

	$sql_funcionario = "SELECT f.*
	FROM dados_do_funcionario f
	WHERE f.id='" . $_SESSION["id_empresaSecao"] . "' AND f.idFuncionario = '" . $_GET['id'] . "'
	LIMIT 0, 1";
	$result_funcionario = mysql_query($sql_funcionario) or die (mysql_error());
	$linha_funcionario = mysql_fetch_array($result_funcionario);

	$sql_dependente = "SELECT d.*
	FROM dados_dependentes_funcionario d
	WHERE d.idFuncionario='" . $linha_funcionario["idFuncionario"] . "'
	";
	$result_dependente = mysql_query($sql_dependente) or die (mysql_error());
	


	$sql = "SELECT e.razao_social
	, e.cnpj
	, e.inscricao_estadual
	, e.endereco
	, e.cidade
	, e.estado
	, e.cep
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
	$pdf->Cell(170,10,utf8_decode("DECLARAÇÃO DE DEPENDENTES"),0,1,'C');
	$pdf->Ln(20);

	$pdf->SetFont('arial','',12);
	$pdf->SetLeftMargin(20);
//	$pdf->SetXY('13','30');

	$pdf->MultiCell(170,6,utf8_decode("Eu, " . $linha_funcionario['nome'] . ($linha_funcionario['nacionalidade'] != '' ? ", " . $linha_funcionario['nacionalidade'] : "") . ($linha_funcionario['estado_civil'] != '' ? ", " . $linha_funcionario['estado_civil'] : "") . ($linha_funcionario['profissao'] != '' ? ", " . $linha_funcionario['profissao'] : "") . ", carteira de identidade nº " . $linha_funcionario['rg'] . ", CPF nº " . $linha_funcionario['cpf'] . ", carteira de trabalho nº " . $linha_funcionario['ctps'] . ", série nº " . $linha_funcionario['serie_ctps'] . " - " . $linha_funcionario['uf_ctps'] . " residente e domiciliad" . ($linha_funcionario['sexo'] == 'Feminino' ? 'a' : 'o') . " à " . $linha_funcionario['endereco'] . ($linha_funcionario['bairro'] != '' ? ", " . $linha_funcionario['bairro'] : "") . ", " . $linha_funcionario['cep'] . ", " . $linha_funcionario['cidade'] . " - " . $linha_funcionario['estado'] . ", funcionari" . ($linha_funcionario['sexo'] == 'Feminino' ? 'a' : 'o') . " da " . $linha['razao_social'] . ", com sede à " . $linha['endereco'] .($linha['bairro'] != '' ? ", " . $linha['bairro'] : "") . ", " . $linha['cep'] . ", " . $linha['cidade'] . " - "  . $linha['estado'] . ", inscrita no CNPJ sob o nº " . $linha['cnpj'] . ", declaro, para fins de retenção do Imposto de Renda na Fonte, que mantenho sob minha exclusiva dependência:

"),0,'J');
	
	while($linha_dependente = mysql_fetch_array($result_dependente)){
$pdf->MultiCell(170,6,utf8_decode("" . $linha_dependente['nome'] . ", " . $linha_dependente['vinculo'] . ", portador" . ($linha_dependente['sexo'] == 'Feminino' ? 'a' : '')  . " do CPF nº " . $linha_dependente['cpf'] . " e do RG nº " . $linha_dependente['rg'] . ", nascid" . ($linha_dependente['sexo'] == 'Feminino' ? 'a' : 'o') . " em " . $linha_dependente['data_de_nascimento'] . ", residente e domiciliad" . ($linha_dependente['sexo'] == 'Feminino' ? 'a' : 'o') . " à " . $linha_dependente['endereco'] . ", " . $linha_dependente['bairro'] . ", " . $linha_dependente['cep'] . ", " . $linha_dependente['cidade'] . " - " . $linha_dependente['estado'] . ".

"),0,'J');
	}
	
$pdf->MultiCell(170,6,utf8_decode("Estando ciente da obrigação, a mim atribuída, de informar ao Empregador qualquer alteração ou baixa do vínculo acima referido.
        
Por ser verdade, assino a presente.
"),0,'J');
	$pdf->Ln(5);

	
	$pdf->MultiCell(170,5,utf8_decode(($linha['cidade'] != '' ? mysql_real_escape_string($linha['cidade']) : '') . ", " . data_extenso(date('Y')."-".date('m')."-".date('d'),"-")),0,'R');
	$pdf->MultiCell(170,5,utf8_decode("



	
	___________________________________
	" . ($linha_funcionario['nome'])),0,'C');

	$pdf->Output("DECLARACAO-DEPENDENTES-". urldecode($linha['nome']) . "-".date('YmdHis').".pdf","I"); // Visualização
//	$pdf->Output("DECLARACAO-DEPENDENTES-". urldecode($linha['nome']) . "-".date('YmdHis').".pdf","D"); // Download
	
?>