<?
session_start();

//echo $_GET['acao'];
//exit;

require_once 'conect.php' ;

//require_once 'classes/numero_extenso_2.php'; 
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
$sql = "SELECT e.razao_social, e.cnpj, e.endereco, e.bairro, e.cep, e.cidade, e.estado, e.pref_telefone, e.telefone,  
r.nome, r.cpf, r.rg, r.orgao_expeditor, r.sexo
FROM dados_da_empresa e INNER JOIN dados_do_responsavel r ON e.id = r.id
WHERE e.id='" . $_SESSION["id_empresaSecao"] . "' AND r.responsavel = 1 LIMIT 0, 1";
$resultado = mysql_query($sql) or die (mysql_error());
$linha_dados=mysql_fetch_array($resultado);

$razaoSocialEmpresa 	= $linha_dados['razao_social'];
$cnpjEmpresa 					= $linha_dados['cnpj'];
$enderecoEmpresa 			= $linha_dados['endereco'];
$bairroEmpresa 				= $linha_dados['bairro'];
$cepEmpresa 					= $linha_dados['cep'];
$cidadeEmpresa 				= $linha_dados['cidade'];
$estadoEmpresa 				= $linha_dados['estado'];
$prefTelefoneEmpresa 	= $linha_dados['pref_telefone'];
$telefoneEmpresa 			= $linha_dados['telefone'];

$nomeSocio 						= $linha_dados['nome'];
$sexoSocio 						= $linha_dados['sexo'];
$cpfSocio 						= $linha_dados['cpf'];
$rgSocio 							= $linha_dados['rg'];
$orgaoExpedidorSocio 	= $linha_dados['orgao_expeditor'];


$enderecoCompletoEmpresa = "";
$enderecoCompletoEmpresa .= ($enderecoEmpresa != "" ? $enderecoEmpresa : "");
$enderecoCompletoEmpresa .= ($bairroEmpresa != "" ? ", " . $bairroEmpresa : "");
$enderecoCompletoEmpresa .= ($cepEmpresa != "" ? " - " . $cepEmpresa : "");

$cidadeCompletoEmpresa = "";
$cidadeCompletoEmpresa .= ($cidadeEmpresa != "" ? "" . $cidadeEmpresa : "");
$cidadeCompletoEmpresa .= ($estadoEmpresa != "" ? " - " . $estadoEmpresa : "");

$telefoneCompletoEmpresa = "";
$telefoneCompletoEmpresa .= ($prefTelefoneEmpresa != "" ? "(" . $prefTelefoneEmpresa . ") " : "");
$telefoneCompletoEmpresa .= ($telefoneEmpresa != "" ? $telefoneEmpresa : "");

$txtNome					= $_REQUEST['txtNome'];
$txtNacionalidade	= $_REQUEST['txtNacionalidade'];
$selEstadoCivil		= $_REQUEST['selEstadoCivil'];
$txtProfissao			= $_REQUEST['txtProfissao'];
$txtCPF						= $_REQUEST['txtCPF'];
$txtRG						= $_REQUEST['txtRG'];
$txtOrgaoExpedidor= $_REQUEST['txtOrgaoExpedidor'];
$txtEndereco			= $_REQUEST['txtEndereco'];
$txtBairro				= $_REQUEST['txtBairro'];
$txtCep						= $_REQUEST['txtCep'];
$txtCidade				= $_REQUEST['txtCidade'];
$selEstado				= $_REQUEST['selEstado'];
$txtDDDTelefone		= $_REQUEST['txtDDDTelefone'];
$txtTelefone			= $_REQUEST['txtTelefone'];
$txtPoderes				= $_REQUEST['txtPoderes'];
$txtDataLimite		= $_REQUEST['txtDataLimite'];

$enderecoCompleto = "";
$enderecoCompleto .= ($txtEndereco != "" ? $txtEndereco : "");
$enderecoCompleto .= ($txtBairro != "" ? " - " . $txtBairro : "");
$enderecoCompleto .= ($txtCep != "" ? " - " . $txtCep : "");

$cidadeCompleto = "";
$cidadeCompleto .= ($txtCidade != "" ? "" . $txtCidade : "");
$cidadeCompleto .= ($selEstado != "" ? " - " . $selEstado : "");

$telefoneCompleto = "";
$telefoneCompleto .= ($txtDDDTelefone != "" ? "(" . $txtDDDTelefone . ") " : "");
$telefoneCompleto .= ($txtTelefone != "" ? $txtTelefone : "");


$dataExtenso = date('Y-m-d',mktime(0,0,0,substr($txtDataLimite,3,2),substr($txtDataLimite,0,2),substr($txtDataLimite,6,4)));

// montar query sql
//	echo $sqlAcao;
//	exit;
//	$resultInsert = mysql_query($sqlAcao) or die (mysql_error());
	
$pdf= new FPDF("P","mm","A4");

$pdf->SetTopMargin(20);
$pdf->AddPage();
 
$pdf->SetFont('arial','B',16);
$pdf->SetleftMargin(20);
$pdf->Cell(170,10,utf8_decode("PROCURAÇÃO"),0,1,'C','');
$pdf->Ln(20);

$pdf->SetFont('arial','',12);
$pdf->MultiCell(170,7,utf8_decode("Outorgante: " . $razaoSocialEmpresa . ", pessoa jurídica de direito privado, com sede à " . $enderecoCompletoEmpresa . ", na cidade de " . $cidadeCompletoEmpresa . ", inscrita no CNPJ sob o nº " . $cnpjEmpresa . ", neste ato representada por " . ($sexoSocio == 'Masculino' ? 'seu' : 'sua' ) . " sóci" . ($sexoSocio == 'Masculino' ? 'o' : 'a' ) . " gerente " . $nomeSocio . ", portador" . ($sexoSocio == 'Masculino' ? '' : 'a' ) . " do CPF nº " . $cpfSocio . " e da Carteira de Identidade nº " . $rgSocio . ", emitida pela " . $orgaoExpedidorSocio . ".

Outorgado: " . $txtNome . ", " . $txtNacionalidade . ", " . $selEstadoCivil . ", " . $txtProfissao . ", portador da Carteira de Identidade nº " . $txtRG . ", emitida pela " . $txtOrgaoExpedidor . " e do CPF nº " . $txtCPF . ", residente e domiciliado na cidade de " . $cidadeCompleto . ", à " . $enderecoCompleto . ".

Por este instrumento particular de procuração, valido até " . $txtDataLimite . " o Outorgante nomeia e constitui o Outorgado seu bastante procurador, conferindo-lhe os necessários poderes para representá-lo junto à Secretaria da Receita Federal, Procuradoria Geral da Fazenda Nacional, INSS (Instituto Nacional de Seguridade Social), Secretaria da Receita Previdenciária, Receita Federal e Receita Federal do Brasil, podendo tudo praticar, requerer, assinar, concordar, discordar, receber e dar quitação, requerer intimações, acompanhar processos em todos os seus temos e instâncias administrativas e judiciais, e ainda, praticar todos os atos necessários ao integral cumprimento do presente mandato, para o que confere os mais amplos poderes, dando tudo por bom, firme e valioso."),0,'J');
$pdf->Ln(20);


$pdf->MultiCell(170,5,utf8_decode("" . $cidadeEmpresa . ", " . data_extenso(date('Y-m-d',mktime(0,0,0,date('m'),date('d'),date('Y'))),"-") . ".





__________________________________________________
".$nomeSocio." - Sóci" . ($sexoSocio == 'Masculino' ? 'o' : 'a' ) . " Responsável
"),0,'C');
$pdf->Ln(10);

//$pdf->Write(30, $corpo, '');

$pdf->Output("PROCURACAO_Receita_Federal.pdf","D");
//$pdf->Output("PROCURACAO_Receita_Federal.pdf","I");

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

?>