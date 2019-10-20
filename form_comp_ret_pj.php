<?
session_start();

//echo $_GET['acao'];
//exit;

require_once 'conect.php' ;

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

function mes_extenso($mes){

	$month = $mes;
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
	
	return $arrMonth[(int)$month];
	
}

//pega dados da tabela empresa
$sql = "SELECT * FROM dados_da_empresa WHERE id='" . $_SESSION["id_empresaSecao"] . "' LIMIT 0, 1";
$resultado = mysql_query($sql) or die (mysql_error());
$linha_empresa=mysql_fetch_array($resultado);


$razao = $linha_empresa['razao_social'];
$cnpj = $linha_empresa['cnpj'];


// CHECANDO SE FOI SELECIONADO UMA EMPRESA
	$idPJ = $_GET['id'];
	$ano = $_GET['ano'];

	$sqlPagtos = "	SELECT
					SUM(valor_bruto) TOTAL_BRUTO
					, SUM(INSS) TOTAL_INSS
					, SUM(IR) TOTAL_IR
					, SUM(valor_liquido) TOTAL_LIQUIDO
					, descricao_servico DESCR
					, codigo_servico COD
					, MONTH(data_pagto) MES
					, YEAR(data_pagto) ANO
				FROM 
					dados_pagamentos
				WHERE 
					id_pj = '" . $idPJ . "'
					AND YEAR(data_pagto) = '" . $ano . "'
				GROUP BY COD, DESCR, ANO, MES
				ORDER BY ANO, MES, COD
";
	$resultadoPagtos = mysql_query($sqlPagtos) or die (mysql_error());
	$linha_pagtos = mysql_fetch_array($resultadoPagtos);
	
	//pega dados da tabela dados_pj
	$sqlPJ = "SELECT * FROM dados_pj WHERE id='" . $idPJ . "' LIMIT 0, 1";
	$resultadoPJ = mysql_query($sqlPJ) or die (mysql_error());
	$linha_PJ = mysql_fetch_array($resultadoPJ);

//}



$pdf= new FPDF("P","mm","A4");

$pdf->SetTopMargin(10);
$pdf->AddPage();
 
$pdf->SetFont('arial','B',8);// SELECIONANDO FONTE PARA O HEADER ESQUERDO
$pdf->Image('images/brasao_pb.png',12,12,15);// IMAGEM DO BRAZAO
$pdf->Cell(19,19,'','LTB',0,'L');// CELULA QUE ENVOLVE A IMAGEM
$pdf->Cell(57,19,'','TRB',0,'L');// CELULA QUE ENVOLVE O TEXTO DA ESQUERDA
$pdf->SetXY(30,13);// POSICIONANDO PARA ESCREVER
$pdf->Write(8,utf8_decode("MINISTÉRIO DA FAZENDA")); // ESCREVENDO PRIMEIRA LINHA DO HEADER ESQUERDO
$pdf->SetXY(30,19);// POSICIONANDO PARA ESCREVER
$pdf->Write(8,utf8_decode("SECRETARIA DA RECEITA FEDERAL")); // ESCREVENDO SEGUNDA LINHA DO HEADER ESQUERDO
$pdf->SetXY(84,10);// POSICIONANDO PARA ESCREVER
$pdf->Cell(116,19,'','TRB',0,'L');// CELULA QUE ENVOLVE O TEXTO DA DIREITA
$pdf->SetXY(94,9.5);// POSICIONANDO PARA ESCREVER
$pdf->SetFont('arial','B',10);// SELECIONANDO FONTE PARA O HEADER DIREITO
$pdf->Write(8,utf8_decode("COMPROVANTE ANUAL DE RENDIMENTOS PAGOS OU"));// ESCREVENDO PRIMEIRA LINHA DO HEADER DIREITO
$pdf->SetXY(111,13.5);// POSICIONANDO PARA ESCREVER
$pdf->Write(8,utf8_decode("CREDITADOS E DE RETENÇÃO DE"));// ESCREVENDO SEGUNDA LINHA DO HEADER DIREITO
$pdf->SetXY(96,17.5);// POSICIONANDO PARA ESCREVER
$pdf->Write(8,utf8_decode("IMPOSTO DE RENDA NA FONTE - PESSOA JURÍDICA"));// ESCREVENDO ULTIMA LINHA DO HEADER DIREITO
$pdf->SetXY(128,21.5);// POSICIONANDO PARA ESCREVER
$pdf->SetFont('arial','B',8);// SELECIONANDO FONTE PARA O ANO DO HEADER
$pdf->Write(8,utf8_decode("Ano calendário: " . $linha_pagtos['ANO']));// ESCREVENDO O ANO CALENDÁRIO
$pdf->Ln(15);


$pdf->SetFont('arial','B',8);// SELECIONANDO FONTE PARA O CABEÇALHO
$pdf->Cell(170,4,utf8_decode("1. FONTE PAGADORA"),0,2); // ESCREVENDO CABEÇALHO 1
$pdf->Cell(140,9,'',1,0,'L');
$pdf->Cell(50,9,'','TRB',0,'L');
$pdf->SetXY(10,40);// POSICIONANDO PARA ESCREVER
$pdf->SetFont('arial','',6);
$pdf->Write(5,utf8_decode("NOME EMPRESARIAL"));
$pdf->SetXY(150,40);// POSICIONANDO PARA ESCREVER
$pdf->Write(5,utf8_decode("CNPJ"));
// PREENCHENDO OS DADOS DA EMPRESA
$pdf->SetFont('arial','',8);
$pdf->SetXY(10,44);// POSICIONANDO PARA ESCREVER
$pdf->Write(5,utf8_decode($razao));
$pdf->SetXY(150,44);// POSICIONANDO PARA ESCREVER
$pdf->Write(5,utf8_decode($cnpj));
$pdf->SetXY(150,40);// VOLTANDO O POSICIONAMENTO DO CURSOR PARA O ADEQUADO AO LAYOUT
$pdf->Ln(15);


$pdf->SetFont('arial','B',8);// SELECIONANDO FONTE PARA O CABEÇALHO
$pdf->Cell(170,4,utf8_decode("2. PESSOA JURÍDICA BENEFICIÁRIA DOS RENDIMENTOS"),0,2);// ESCREVENDO CABEÇALHO 2
$pdf->Cell(140,9,'',1,0,'L');
$pdf->Cell(50,9,'','TRB',0,'L');
$pdf->SetXY(10,58.5);
$pdf->SetFont('arial','',6);
$pdf->Write(5,utf8_decode("NOME EMPRESARIAL"));
$pdf->SetXY(150,58.5);
$pdf->Write(5,utf8_decode("CNPJ"));
// PREENCHENDO OS DADOS DA EMPRESA FORNECEDORA
$pdf->SetFont('arial','',8);
$pdf->SetXY(10,62.5);// POSICIONANDO PARA ESCREVER
$pdf->Write(5,utf8_decode($linha_PJ['nome']));
$pdf->SetXY(150,62.5);// POSICIONANDO PARA ESCREVER
$pdf->Write(5,utf8_decode($linha_PJ['cnpj']));
$pdf->SetXY(150,58.5);// VOLTANDO O POSICIONAMENTO DO CURSOR PARA O ADEQUADO AO LAYOUT
$pdf->Ln(15);

$pdf->SetFont('arial','B',8);
$pdf->Cell(170,4,utf8_decode("3. RENDIMENTO DE IMPOSTO RETIDO NA FONTE"),0,2);// ESCREVENDO CABEÇALHO 3
$pdf->Cell(23,9,'',1,0,'L');
$pdf->Cell(23,9,'','TRB',0,'L');
$pdf->Cell(87,9,'','TRB',0,'L');
$pdf->Cell(28.5,9,'','TRB',0,'L');
$pdf->Cell(28.5,9,'','TRB',0,'L');
$pdf->SetXY(17.5,79);
$pdf->SetFont('arial','',6);
$pdf->Write(5,utf8_decode("MÊS"));
$pdf->SetXY(37,78);
$pdf->Write(5,utf8_decode("CÓDIGO DE"));
$pdf->SetXY(37,81);
$pdf->Write(5,utf8_decode("RETENÇÃO"));
$pdf->SetXY(83,79);
$pdf->Write(5,utf8_decode("DESCRIÇÃO DO RENDIMENTO"));
$pdf->SetXY(149,78);
$pdf->Write(5,utf8_decode("RENDIMENTO"));
$pdf->SetXY(154,81);
$pdf->Write(5,utf8_decode("(R$)"));
$pdf->SetXY(179.5,78);
$pdf->Write(5,utf8_decode("IMPOSTO"));
$pdf->SetXY(183,81);
$pdf->Write(5,utf8_decode("(R$)"));
$pdf->Ln();

// LINHAS DE PREENCHIMENTO
mysql_data_seek($resultadoPagtos,0);

$count_pagtos = 1;

while($linha_pagtos = mysql_fetch_array($resultadoPagtos)){
	
	$pdf->SetFont('arial','',7);
	$pdf->Cell(23,8,utf8_decode(mes_extenso($linha_pagtos['MES'])),'LRB',0,'C');
	$pdf->Cell(23,8,utf8_decode($linha_pagtos['COD']),'RB',0,'C');
	$pdf->Cell(87,8,utf8_decode($linha_pagtos['DESCR']),'RB',0,'C');
	$pdf->Cell(28.5,8,utf8_decode(number_format($linha_pagtos['TOTAL_BRUTO'],2,',','.')),'RB',0,'R');
	$pdf->Cell(28.5,8,utf8_decode(number_format($linha_pagtos['TOTAL_IR'],2,',','.')),'RB',0,'R');
	$pdf->Ln();
	
	$count_pagtos++;
}

for($i = $count_pagtos; $i <= 16; $i++){
	
	$pdf->SetFont('arial','',7);
	$pdf->Cell(23,8,utf8_decode(''),'LRB',0,'C');
	$pdf->Cell(23,8,utf8_decode(''),'RB',0,'C');
	$pdf->Cell(87,8,utf8_decode(''),'RB',0,'C');
	$pdf->Cell(28.5,8,utf8_decode(''),'RB',0,'R');
	$pdf->Cell(28.5,8,utf8_decode(''),'RB',0,'R');
	$pdf->Ln();

}


$pdf->Ln(5.5);

$pdf->SetFont('arial','B',8);
$pdf->Cell(170,4,utf8_decode("4. INFORMAÇÕES COMPLEMENTARES"),0,2);// ESCREVENDO CABEÇALHO 4
$pdf->Cell(190,30,'',1,'L',1);
$pdf->Ln(36);

$pdf->SetFont('arial','B',8);
$pdf->Cell(170,4,utf8_decode("5. RESPONSÁVEL PELAS INFORMAÇÕES"),0,2);// ESCREVENDO CABEÇALHO 5
$pdf->Cell(94,8,'',1,'L',1);
$pdf->Cell(36,8,'',1,'L',1);
$pdf->Cell(60,8,'',1,'L',1);
$pdf->SetXY(10,262.5);
$pdf->SetFont('arial','',6);
$pdf->Write(5,utf8_decode("NOME"));
$pdf->SetXY(104,262.5);
$pdf->Write(5,utf8_decode("DATA"));
$pdf->SetXY(140,262.5);
$pdf->Write(5,utf8_decode("ASSINATURA"));
// PREENCHENDO OS DADOS DA EMPRESA
$pdf->SetFont('arial','',8); // SELECIONANDO A FONTE DOS DADOS DE PREENCHIMENTO
$pdf->SetXY(10,266.5); // POSICIONANDO PARA ESCREVER
$pdf->Write(5,utf8_decode($razao)); // ESCREVENDO O NOME DA EMPRESA
$pdf->SetXY(104,266.5); // POSICIONANDO PARA ESCREVER
$pdf->Write(5,utf8_decode(date('d/m/Y'))); // ESCREVENDO A DATA
$pdf->SetXY(140,265); // VOLTANDO AO POSICIONAMENTO DE CURSOR PADRÃO

$pdf->SetXY(10,271);
$pdf->SetFont('arial','',5.5);
$pdf->Write(5,utf8_decode("Aprovado pela IN/SRF nº 119/2000"));

//$pdf->Write(30, $corpo, '');

$pdf->Output("Comp-Ret-PJ-" . urldecode($linha_PJ['nome']) . "-" . date('YmdHis') . ".pdf","D");

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