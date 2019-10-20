<?
session_start();

//echo $_GET['acao'];
//exit;

require_once 'conect.php' ;

require_once 'classes/numero_extenso_2.php'; 
require_once 'classes/fpdf/fpdf.php'; 

/*
sintaxe: Image(string file [,float x [, float y [, float h [, string type [, mixed link ]]]]]])
				file: nome do arquivo da imagem
				x: Abscisa do canto superior esquerdo. Se não for especificada usará a abscisa atual. 
				y: Ordenada do canto superior esquerdo. Se não for especificada utilizará a ordem atual.
				w: largura da imagem na página
				h: altura da imagem na página
				type: formato da imagem
				link: link que será inserido na imagem
*/

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

$identacao = str_repeat("\t",10);

$sql = "SELECT * FROM dados_da_empresa WHERE id='" . $_SESSION["id_empresaSecao"] . "' LIMIT 0, 1";
$resultado = mysql_query($sql) or die (mysql_error());
$linha_empresa=mysql_fetch_array($resultado);

$IDEmpresa = $linha_empresa['id'];
$razao = $linha_empresa['razao_social'];
$cnpj = $linha_empresa['cnpj'];
$cidade = $linha_empresa['cidade'];


$sql_funcionario = "SELECT * FROM dados_do_funcionario WHERE id='" . $IDEmpresa . "' AND idFuncionario = '" . $_GET['id'] . "' LIMIT 0, 1";
$resultado_funcionario = mysql_query($sql_funcionario) or die (mysql_error());
$linha_funcionario=mysql_fetch_array($resultado_funcionario);

$idFuncionario = $linha_funcionario['idFuncionario'];
$nomeFuncionario = $linha_funcionario['nome'];
$rgFuncionario = $linha_funcionario['rg'];
$cpfFuncionario = $linha_funcionario['cpf'];
if($linha_funcionario['ctps'] != '' && $linha_funcionario['serie_ctps'] != '' && $linha_funcionario['uf_ctps'] != ''){
	$ctpsFuncionario = $linha_funcionario['ctps'] . ' - ' . $linha_funcionario['serie_ctps'] . ' - ' . $linha_funcionario['uf_ctps'];
}else{
	$ctpsFuncionario = "";
}


$pdf= new FPDF("P","mm","A4");

$pdf->SetTopMargin(30);
$pdf->AddPage();
 
$pdf->SetFont('arial','B',8); // SELECIONANDO FONTE PARA O HEADER ESQUERDO
$pdf->SetFont('arial','',10);// SELECIONANDO FONTE PARA O HEADER DIREITO
$pdf->MultiCell(190,6,utf8_decode('DECLARAÇÃO DE SALÁRIO - FAMÍLIA
TERMO DE RESPONSABILIDADE'),'TRL','C',0);
$pdf->SetFont('arial','B',8);// SELECIONANDO FONTE PARA O HEADER DIREITO
$pdf->MultiCell(190,6,utf8_decode('(Concessão de Salário-Família - Portaria n.º MPAS - 3.040/82)'),'RBL','C',0);

$pdf->SetFont('arial','B',6);
$pdf->Cell(120,8,'','BL','L',1);
$pdf->Cell(70,8,'','LRB','L',1);
$pdf->SetXY(10,47.5);// POSICIONANDO PARA ESCREVER
$pdf->Write(5,utf8_decode("Empresa:"));
$pdf->SetXY(130,47.5);// POSICIONANDO PARA ESCREVER
$pdf->Write(5,utf8_decode("Matrícula: INSS ou CNPJ"));
$pdf->SetFont('arial','',8);
$pdf->SetXY(10,51);// POSICIONANDO PARA ESCREVER
$pdf->Write(5,utf8_decode($razao));
$pdf->SetXY(130,51);// POSICIONANDO PARA ESCREVER
$pdf->Write(5,utf8_decode($cnpj));

$pdf->SetXY(10,56);// POSICIONANDO PARA ESCREVER
$pdf->SetFont('arial','B',6);
$pdf->Cell(190,8,'','RBL','L',1);
$pdf->SetXY(10,55.5);// POSICIONANDO PARA ESCREVER
$pdf->Write(5,utf8_decode("Nome do Segurado:"));
$pdf->SetFont('arial','',8);
$pdf->SetXY(10,59.5);// POSICIONANDO PARA ESCREVER
$pdf->Write(5,utf8_decode($nomeFuncionario));


$pdf->SetXY(10,64);// POSICIONANDO PARA ESCREVER
$pdf->SetFont('arial','B',6);
$pdf->Cell(190,8,'','RBL','L',1);
$pdf->SetXY(10,63.5);// POSICIONANDO PARA ESCREVER
$pdf->Write(5,utf8_decode("CTPS ou Documento de Identidade:"));
$pdf->SetFont('arial','',8);
$pdf->SetXY(10,67.5);// POSICIONANDO PARA ESCREVER
$pdf->Write(5,utf8_decode($ctpsFuncionario != '' ? $ctpsFuncionario : $rgFuncionario));

$pdf->SetXY(10,72);// POSICIONANDO PARA ESCREVER
$pdf->SetFont('arial','B',9);
$pdf->MultiCell(10,6.77,utf8_decode('B
E
N
E
F
I
C
I
Á
R
I
O
S'),'RBL','C',0); // COLUNA VERTICAL DA TABELA DE DEPENDENTES
$pdf->SetXY(20,72);// POSICIONANDO PARA ESCREVER
$pdf->Cell(130,8,'NOME DO FILHO','RB','C',1);
$pdf->Cell(50,8,'DATA DO NASCIMENTO','RB','C',1);

$arrDependentes = array();

$sql_dependentes = "SELECT * FROM dados_dependentes_funcionario WHERE idFuncionario = " . $idFuncionario . " ";
$resultado_dependentes = mysql_query($sql_dependentes) or die (mysql_error());
if(mysql_num_rows($resultado_dependentes) > 0){
	while($linha_dependentes = mysql_fetch_array($resultado_dependentes)){
		
		$dia = substr($linha_dependentes['data_de_nascimento'],0,2);
		$mes = substr($linha_dependentes['data_de_nascimento'],3,2);
		$ano = substr($linha_dependentes['data_de_nascimento'],6,4);
		
		$dataNasctoTIME = mktime(0,0,0,$mes,$dia,$ano);
		$dataNascto = date("Y-m-d",$dataNasctoTIME);
		$dataHojeTIME = mktime(0,0,0,date('n'),date('j'),date('Y'));
		$dataHoje = date("Y-m-d",$dataHojeTIME);
		
		$idade_anos = ($dataHojeTIME - $dataNasctoTIME) / 3600 / 24 / 30 / 12 ;

		if($idade_anos < 14 || $linha_dependentes['invalidez'] == '1'){
			array_push($arrDependentes,array('nome'=>$linha_dependentes['nome'],'data'=>$linha_dependentes['data_de_nascimento']));
		}
	}
}


$pdf->SetXY(20,80);// POSICIONANDO PARA ESCREVER
$pdf->SetFont('arial','B',8);
$pdf->Cell(5,8,'1','RB',0,'C');
$pdf->Cell(125,8,utf8_decode($arrDependentes[0]['nome']),'RB',0,'L');
$pdf->Cell(50,8,utf8_decode($arrDependentes[0]['data']),'RB',0,'R');

$pdf->SetXY(20,88);// POSICIONANDO PARA ESCREVER
$pdf->Cell(5,8,'2','RB',0,'C');
$pdf->Cell(125,8,utf8_decode($arrDependentes[1]['nome']),'RB',0,'L');
$pdf->Cell(50,8,utf8_decode($arrDependentes[1]['data']),'RB',0,'R');

$pdf->SetXY(20,96);// POSICIONANDO PARA ESCREVER
$pdf->Cell(5,8,'3','RB',0,'C');
$pdf->Cell(125,8,utf8_decode($arrDependentes[2]['nome']),'RB',0,'L');
$pdf->Cell(50,8,utf8_decode($arrDependentes[2]['data']),'RB',0,'R');

$pdf->SetXY(20,104);// POSICIONANDO PARA ESCREVER
$pdf->Cell(5,8,'4','RB',0,'C');
$pdf->Cell(125,8,utf8_decode($arrDependentes[3]['nome']),'RB',0,'L');
$pdf->Cell(50,8,utf8_decode($arrDependentes[3]['data']),'RB',0,'R');

$pdf->SetXY(20,112);// POSICIONANDO PARA ESCREVER
$pdf->Cell(5,8,'5','RB',0,'C');
$pdf->Cell(125,8,utf8_decode($arrDependentes[4]['nome']),'RB',0,'L');
$pdf->Cell(50,8,utf8_decode($arrDependentes[4]['data']),'RB',0,'R');

$pdf->SetXY(20,120);// POSICIONANDO PARA ESCREVER
$pdf->Cell(5,8,'6','RB',0,'C');
$pdf->Cell(125,8,utf8_decode($arrDependentes[5]['nome']),'RB',0,'L');
$pdf->Cell(50,8,utf8_decode($arrDependentes[5]['data']),'RB',0,'R');

$pdf->SetXY(20,128);// POSICIONANDO PARA ESCREVER
$pdf->Cell(5,8,'7','RB',0,'C');
$pdf->Cell(125,8,utf8_decode($arrDependentes[6]['nome']),'RB',0,'L');
$pdf->Cell(50,8,utf8_decode($arrDependentes[6]['data']),'RB',0,'R');

$pdf->SetXY(20,136);// POSICIONANDO PARA ESCREVER
$pdf->Cell(5,8,'8','RB',0,'C');
$pdf->Cell(125,8,utf8_decode($arrDependentes[7]['nome']),'RB',0,'L');
$pdf->Cell(50,8,utf8_decode($arrDependentes[7]['data']),'RB',0,'R');

$pdf->SetXY(20,144);// POSICIONANDO PARA ESCREVER
$pdf->Cell(5,8,'9','RB',0,'C');
$pdf->Cell(125,8,utf8_decode($arrDependentes[8]['nome']),'RB',0,'L');
$pdf->Cell(50,8,utf8_decode($arrDependentes[8]['data']),'RB',0,'R');

$pdf->SetXY(20,152);// POSICIONANDO PARA ESCREVER
$pdf->Cell(5,8,'10','RB',0,'C');
$pdf->Cell(125,8,utf8_decode($arrDependentes[9]['nome']),'RB',0,'L');
$pdf->Cell(50,8,utf8_decode($arrDependentes[9]['data']),'RB',0,'R');

$pdf->SetXY(10,160);// POSICIONANDO PARA ESCREVER
$pdf->MultiCell(190,5,utf8_decode('
' . $identacao . 'Pelo presente TERMO DE RESPONSABILIDADE declaro estar ciente, de que deverei comunicar de imediato a ocorrência dos seguintes fatos ou ocorrências que determinam a perda do direito ao salário-família:
' . $identacao . '- ÓBITO DE FILHO;
' . $identacao . '- CESSAÇÃO DA INVALIDEZ DE FILHO INVÁLIDO;
' . $identacao . '- SENTENÇA JUDICIAL QUE DETERMINE O PAGAMENTO A OUTREM (casos de desquite ou separação, abandono de filho ou perda de pátrio poder).

' . $identacao . 'Estou ciente, ainda, de que a falta de cumprimento ora assumido, além de obrigar a devolução das importâncias recebidas indevidamente, sujeitar-me-á às penalidades previstas no art. 171 do Código Penal  e à rescisão do contrato de trabalho, por justa causa, nos termos do art. 482 da Consolidação das Leis de Trabalho.

'),'BRL','J',0);

$pdf->SetXY(10,220);// POSICIONANDO PARA ESCREVER
$pdf->Cell(150,10,'','BL','L',1);
$pdf->Cell(40,10,'','LR','L',1);
$pdf->SetFont('arial','B',8);
$pdf->SetXY(10,220);// POSICIONANDO PARA ESCREVER
$pdf->Write(5,utf8_decode("Local e Data"));
$pdf->SetXY(167,220);// POSICIONANDO PARA ESCREVER
$pdf->Write(5,utf8_decode("Impressão digital"));

$pdf->SetFont('arial','',8);
$pdf->SetXY(10,225.5);// POSICIONANDO PARA ESCREVER
$pdf->Write(5,utf8_decode(($cidade != '' ? mysql_real_escape_string($cidade) : '') . ", " . data_extenso(date('Y')."-".date('m')."-".date('d'),"-")));


$pdf->SetXY(10,230);// POSICIONANDO PARA ESCREVER
$pdf->Cell(150,10,'','BL','L',1);
$pdf->Cell(40,10,'','LRB','L',1);
$pdf->SetFont('arial','B',8);
$pdf->SetXY(10,230);// POSICIONANDO PARA ESCREVER
$pdf->Write(5,utf8_decode("Assinatura"));








//$pdf->Write(30, $corpo, '');

$pdf->Output("DECLARACAO-SALARIO-FAMILIA-". urldecode($linha['nome']) . "-".date('YmdHis').".pdf","I"); // Visualização
//	$pdf->Output("DECLARACAO-SALARIO-FAMILIA-". urldecode($linha['nome']) . "-".date('YmdHis').".pdf","D"); // Download
?>