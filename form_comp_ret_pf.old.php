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

$sql = "SELECT * FROM dados_da_empresa WHERE id='" . $_SESSION["id_empresaSecao"] . "' LIMIT 0, 1";
$resultado = mysql_query($sql) or die (mysql_error());
$linha_empresa=mysql_fetch_array($resultado);

$razao = $linha_empresa['razao_social'];
$cnpj = $linha_empresa['cnpj'];

// CHECANDO SE FOI SELECIONADO UM AUTONOMO OU SÓCIO
$id = $_GET['id'];
$ano = $_GET['ano'];
$tipo = $_GET['tp'];
if($tipo == 'sócio'){
	$tabela = 'dados_do_responsavel';
	$campo = 'idSocio';
	$campo_pagamentos = 'id_socio';
	// PEGANDO TAMBEM OS DADOS DE DISTRIBUICAO DE LUCROS
	$campo_distribuicao_de_lucros = " OR id_lucro = " . $id;
}else{
	$tabela = 'dados_autonomos';
	$campo = 'id';
	$campo_pagamentos = 'id_autonomo';
	$campo_distribuicao_de_lucros = "";
}

$sqlPagtos = "	SELECT
				SUM(valor_bruto) TOTAL_BRUTO
				, SUM(INSS) TOTAL_INSS
				, SUM(IR) TOTAL_IR
				, SUM(valor_liquido) TOTAL_LIQUIDO
				, SUM(case when id_lucro <> 0 then valor_liquido else 0 end) TOTAL_N_TRIB
				, YEAR(data_pagto) ANO
			FROM 
				dados_pagamentos
			WHERE 
				(" . $campo_pagamentos . " = '" . $id . "'
				" . $campo_distribuicao_de_lucros . "
				) AND YEAR(data_pagto) = '" . $ano . "'
			GROUP BY YEAR(data_pagto)
";

$resultadoPagtos = mysql_query($sqlPagtos) or die (mysql_error());
$linha_pagtos = mysql_fetch_array($resultadoPagtos);

//pega dados da tabela autonomos
$sqlDados = "SELECT * FROM " . $tabela . " WHERE " . $campo . "='" . $id . "' LIMIT 0, 1";
$resultadoDados = mysql_query($sqlDados) or die (mysql_error());
$linha_dados = mysql_fetch_array($resultadoDados);


$pdf= new FPDF("P","mm","A4");

$pdf->SetTopMargin(15);
$pdf->AddPage();
 
//$pdf->SetFont('arial','B',8); // SELECIONANDO FONTE PARA O HEADER ESQUERDO
//$pdf->Cell(190,18,'','TRBL','L',1);// CELULA QUE ENVOLVE O TEXTO DA DIREITA
//$pdf->SetFont('arial','',10);// SELECIONANDO FONTE PARA O HEADER DIREITO
//$pdf->SetXY(60,11);// POSICIONANDO PARA ESCREVER
//$pdf->Write(8,utf8_decode("COMPROVANTE DE RENDIMENTOS PAGOS E DE")); // ESCREVENDO PRIMEIRA LINHA DO HEADER DIREITO
//$pdf->SetXY(62,16);// POSICIONANDO PARA ESCREVER
//$pdf->Write(8,utf8_decode("RETENÇÃO DE IMPOSTO DE RENDA NA FONTE")); // ESCREVENDO ULTIMA LINHA DO HEADER DIREITO
//$pdf->SetXY(85,21);// POSICIONANDO PARA ESCREVER
//$pdf->SetFont('arial','BI',9); // SELECIONANDO FONTE PARA O ANO DO HEADER
//$pdf->Write(8,utf8_decode("Ano-calendário de " . $linha_pagtos['ANO']));// ESCREVENDO O ANO CALENDÁRIO


$pdf->Image('images/brasao_pb.png',12,16,15); // IMAGEM DO BRAZAO
$pdf->Cell(19,18,'','LTB','L',1);// CELULA QUE ENVOLVE A IMAGEM
$pdf->Cell(70,18,'','TRB','L',1);// CELULA QUE ENVOLVE O TEXTO DA ESQUERDA
$pdf->SetXY(45,15); // POSICIONANDO PARA ESCREVER
$pdf->Write(8,utf8_decode("Ministério da Fazenda")); // ESCREVENDO PRIMEIRA LINHA DO HEADER ESQUERDO
$pdf->SetFont('arial','',8); // SELECIONANDO FONTE PARA O HEADER ESQUERDO
$pdf->SetXY(35,16);// POSICIONANDO PARA ESCREVER
$pdf->Write(8,utf8_decode("Secretaria da Receita Federal do Brasil")); // ESCREVENDO SEGUNDA LINHA DO HEADER ESQUERDO
$pdf->SetFont('arial','B',8); // SELECIONANDO FONTE PARA O HEADER ESQUERDO
$pdf->SetXY(32,20);// POSICIONANDO PARA ESCREVER
$pdf->Write(8,utf8_decode("Imposto sobre a Renda da Pessoa Física")); // ESCREVENDO SEGUNDA LINHA DO HEADER ESQUERDO
$pdf->SetFont('arial','BI',8); // SELECIONANDO FONTE PARA O HEADER ESQUERDO
$pdf->SetXY(48,24);// POSICIONANDO PARA ESCREVER
$pdf->Write(8,utf8_decode("Exercício de " . ((int)$linha_pagtos['ANO'] + 1))); // ESCREVENDO SEGUNDA LINHA DO HEADER ESQUERDO

$pdf->SetXY(98,15);// POSICIONANDO PARA ESCREVER
$pdf->Cell(102,18,'','TRB','L',1);// CELULA QUE ENVOLVE O TEXTO DA DIREITA
$pdf->SetFont('arial','',10);// SELECIONANDO FONTE PARA O HEADER DIREITO
$pdf->SetXY(114,16);// POSICIONANDO PARA ESCREVER
$pdf->Write(8,utf8_decode("Comprovante de Rendimentos Pagos e de")); // ESCREVENDO PRIMEIRA LINHA DO HEADER DIREITO
$pdf->SetXY(116,21);// POSICIONANDO PARA ESCREVER
$pdf->Write(8,utf8_decode("Imposto sobre a Renda Retido na Fonte")); // ESCREVENDO ULTIMA LINHA DO HEADER DIREITO
$pdf->SetXY(132,25);// POSICIONANDO PARA ESCREVER
$pdf->SetFont('arial','BI',8); // SELECIONANDO FONTE PARA O ANO DO HEADER
$pdf->Write(8,utf8_decode("Ano-calendário de " . $linha_pagtos['ANO']));// ESCREVENDO O ANO CALENDÁRIO
$pdf->Ln(5.5);

//$pdf->Cell(190,8,'',1,'L',1);
//$pdf->SetFont('arial','B',8);// SELECIONANDO FONTE PARA O CABEÇALHO
//$pdf->SetXY(10,32);// POSICIONANDO PARA ESCREVER
//$pdf->Write(3,utf8_decode("Verifique as condições e o prazo para a apresentação da Declaração do Imposto sobre a Renda da Pessoa Física para este ano-calendário no sítio da Secretaria da Receita Federal do Brasil na internet, no endereço <www.receita.fazenda.gov.br>.")); // ESCREVENDO ULTIMA LINHA DO HEADER DIREITO
$pdf->Ln(8.0);

$pdf->SetFont('arial','B',8);// SELECIONANDO FONTE PARA O CABEÇALHO
$pdf->Cell(170,4,utf8_decode("1. Fonte Pagadora Pessoa Jurídica"),0,2); // ESCREVENDO CABEÇALHO 1
$pdf->SetFont('arial','',6);
$pdf->Cell(50,7,'',1,'L',1);
$pdf->Cell(140,7,'','TRB','L',1);
$pdf->SetXY(10,42);// POSICIONANDO PARA ESCREVER
$pdf->SetFont('arial','',6);
$pdf->Write(5,utf8_decode("CNPJ"));
$pdf->SetXY(60,42);// POSICIONANDO PARA ESCREVER
$pdf->Write(5,utf8_decode("Nome empresarial"));
// PREENCHENDO OS DADOS DA EMPRESA
$pdf->SetFont('arial','',8);
$pdf->SetXY(10,45);// POSICIONANDO PARA ESCREVER
$pdf->Write(5,utf8_decode($cnpj));
$pdf->SetXY(60,45);// POSICIONANDO PARA ESCREVER
$pdf->Write(5,utf8_decode($razao));
$pdf->SetXY(60,45);// VOLTANDO O POSICIONAMENTO DO CURSOR PARA O ADEQUADO AO LAYOUT
$pdf->Ln(8);


$pdf->SetFont('arial','B',8); // SELECIONANDO FONTE PARA O CABEÇALHO
$pdf->Cell(170,4,utf8_decode("2. Pessoa Física Beneficiária dos Rendimentos"),0,2);// ESCREVENDO CABEÇALHO 2
$pdf->Cell(50,7,'',1,'L',1); // CRIANDO CELULA DO CPF
$pdf->Cell(140,7,'','TRB','L',2); // CRIANDO CELULA DO NOME
$pdf->Ln(); // QUEBRA LINHA
$pdf->Cell(190,8,'','LRB','L',2); // CRIANDO CELULA DA NATUREZA
$pdf->SetFont('arial','',6); // SELECIONANDO FONTE PARA ESCREVER 
$pdf->SetXY(10,56.5);// POSICIONANDO PARA ESCREVER
$pdf->Write(5,utf8_decode("CPF"));
$pdf->SetXY(60,56.5);// POSICIONANDO PARA ESCREVER
$pdf->Write(5,utf8_decode("Nome completo"));
$pdf->SetXY(10,63.5);// POSICIONANDO PARA ESCREVER
$pdf->Write(5,utf8_decode("Natureza do rendimento"));

// PREENCHENDO OS DADOS DO AUTONOMO
$pdf->SetFont('arial','',8);
$pdf->SetXY(10,59.5);// POSICIONANDO PARA ESCREVER
$pdf->Write(5,utf8_decode($linha_dados['cpf']));
$pdf->SetXY(60,59.5);// POSICIONANDO PARA ESCREVER
$pdf->Write(5,utf8_decode($linha_dados['nome']));
$pdf->SetXY(10,67.5);// POSICIONANDO PARA ESCREVER
$pdf->Write(5,utf8_decode(($tipo == 'sócio' ? 'Rendimentos do trabalho assalariado' : $linha_dados['tipo_servico'])));
$pdf->SetXY(10,67.5);// VOLTANDO O POSICIONAMENTO DO CURSOR PARA O ADEQUADO AO LAYOUT
$pdf->Ln(8);

$pdf->SetFont('arial','B',8);// SELECIONANDO FONTE PARA O CABEÇALHO
$pdf->Cell(140,4,utf8_decode("3. Rendimentos Tributáveis, Deduções e Imposto sobre a Renda Retido na Fonte"),0,0);// ESCREVENDO CABEÇALHO 3
$pdf->Cell(47,4,utf8_decode("Valores em reais"),0,'L',0);
$pdf->Ln(4);
// LINHAS DE PREENCHIMENTO
$pdf->SetFont('arial','',7);
$pdf->Cell(160,5,utf8_decode('1. Total dos rendimentos (inclusive férias)'),1,0,'L');
$pdf->Cell(30,5,utf8_decode(number_format($linha_pagtos['TOTAL_BRUTO'],2,',','.')),'TRB',0,'R');
$pdf->Ln();
$pdf->Cell(160,5,utf8_decode('2. Contribuição Previdenciária Oficial'),'LRB',0,'L');
$pdf->Cell(30,5,utf8_decode(number_format($linha_pagtos['TOTAL_INSS'],2,',','.')),'RB',0,'R');
$pdf->Ln();
$pdf->Cell(160,5,utf8_decode('3. Contribuições a entidades de previdência complementar e a fundos de aposentgadoria prog. individual (Fapi)'),'LRB',0,'L');
$pdf->Cell(30,5,utf8_decode(number_format(0,2,',','.')),'RB',0,'R');
$pdf->Ln();
$pdf->Cell(160,5,utf8_decode('4. Pensão Alimentícia'),'LRB',0,'L');
$pdf->Cell(30,5,utf8_decode(number_format(0,2,',','.')),'RB',0,'R');
$pdf->Ln();
$pdf->Cell(160,5,utf8_decode('5. Imposto sobre a renda retido na fonte'),'LRB',0,'L');
$pdf->Cell(30,5,utf8_decode(number_format($linha_pagtos['TOTAL_IR'],2,',','.')),'RB',0,'R');
$pdf->Ln(8);

$pdf->SetFont('arial','B',8);// SELECIONANDO FONTE PARA O CABEÇALHO
$pdf->Cell(140,4,utf8_decode("4. Rendimentos Isentos e Não Tributáveis"),0,0);// ESCREVENDO CABEÇALHO 4
$pdf->Cell(47,4,utf8_decode("Valores em reais"),0,'L',0);
$pdf->Ln(4);
// LINHAS DE PREENCHIMENTO
$pdf->SetFont('arial','',7);
$pdf->Cell(160,5,utf8_decode('1. Parcela Isenta dos proventos de aposentadoria, reserva remunerada, reforma e pensão (65 anos ou mais)'),1,'L',1);
$pdf->Cell(30,5,utf8_decode(number_format(0,2,',','.')),'TRB',0,'R');
$pdf->Ln();

$pdf->Cell(160,5,utf8_decode('2. Diárias e ajudas de custo'),'LRB','L',1);
$pdf->Cell(30,5,utf8_decode(number_format(0,2,',','.')),'TRB',0,'R');
$pdf->Ln();

$pdf->Cell(160,5,utf8_decode('3. Pensão e proventos de aposentadoria ou reforma por moléstia grave; proventos de aposentadoria ou reforma por acidente em serviço'),'LRB','L',1);
$pdf->Cell(30,5,utf8_decode(number_format(0,2,',','.')),'TRB',0,'R');
$pdf->Ln();

$pdf->Cell(160,5,utf8_decode('4. Lucros e dividendos, apurados a partir de 1996, pagos por pessoa jurídica (lucro real, presumido ou arbitrado)'),'LRB','L',1);
//$pdf->Cell(30,5,utf8_decode(number_format($linha_pagtos['TOTAL_N_TRIB'],2,',','.')),'TRB',0,'R');
$pdf->Cell(30,5,utf8_decode(number_format(0,2,',','.')),'TRB',0,'R');
$pdf->Ln();

$pdf->Cell(160,5,utf8_decode('5. Valores pagos ao titular ou sócio da microempresa ou empresa de pequeno porte, exceto pro labore, aluguéis ou serviços prestados'),'LRB','L',1);
//$pdf->Cell(30,5,utf8_decode(number_format(0,2,',','.')),'TRB',0,'R');
$pdf->Cell(30,5,utf8_decode(number_format($linha_pagtos['TOTAL_N_TRIB'],2,',','.')),'TRB',0,'R');
$pdf->Ln();

$pdf->Cell(160,5,utf8_decode('6. Indenizações por rescisão de contrato de trabalho, inclusive a título de PDV, e por acidente de trabalho'),'LRB','L',1);
$pdf->Cell(30,5,utf8_decode(number_format(0,2,',','.')),'TRB',0,'R');
$pdf->Ln();

$pdf->Cell(160,5,utf8_decode('7. Outros (especificar)'),'LRB','L',1);
$pdf->Cell(30,5,utf8_decode(number_format(0,2,',','.')),'TRB',0,'R');
$pdf->Ln(8);

$pdf->SetFont('arial','B',8);// SELECIONANDO FONTE PARA O CABEÇALHO
$pdf->Cell(140,4,utf8_decode("5. Rendimentos sujeitos à Tributação Exclusiva (rendimento líquido)"),0,0);// ESCREVENDO CABEÇALHO 4
$pdf->Cell(47,4,utf8_decode("Valores em reais"),0,'L',0);
$pdf->Ln(4);
// LINHAS DE PREENCHIMENTO
$pdf->SetFont('arial','',7);
$pdf->Cell(160,5,utf8_decode('1. Décimo terceiro salário'),1,'L',1);
$pdf->Cell(30,5,utf8_decode(number_format(0,2,',','.')),'TRB',0,'R');
$pdf->Ln();
$pdf->Cell(160,5,utf8_decode('2. Outros'),'LRB','L',1);
$pdf->Cell(30,5,utf8_decode(number_format(0,2,',','.')),'TRB',0,'R');
$pdf->Ln(8);

$pdf->SetFont('arial','B',8);// SELECIONANDO FONTE PARA O CABEÇALHO
$pdf->Cell(140,4,utf8_decode("6. Rendimentos recebidos acumuladamente - Art. 12-A da Lei nº7.713, de 1988 (sujeito à tributação exclusiva)"),0,0);// ESCREVENDO CABEÇALHO 4
$pdf->Ln(4);

$pdf->SetFont('arial','',8);
$pdf->Cell(100,5,utf8_decode('6.1 Número do processo:'),1,'L',1);
$pdf->Cell(40,5,utf8_decode('Quantidade de meses'),1,'L',1);
$pdf->Cell(20,5,utf8_decode(number_format(0,2,',','.')),'TRB',0,'R');
$pdf->Ln();
$pdf->Cell(160,5,utf8_decode('Natureza do rendimento:'),1,'L',1);
$pdf->Ln(3);

$pdf->SetFont('arial','B',8);// SELECIONANDO FONTE PARA O CABEÇALHO
$pdf->Cell(140,4,utf8_decode(''),0,0);// ESCREVENDO CABEÇALHO 4
$pdf->Cell(47,4,utf8_decode("Valores em reais"),0,'L',0);
$pdf->Ln(4);

// LINHAS DE PREENCHIMENTO
$pdf->SetFont('arial','',7);
$pdf->Cell(160,5,utf8_decode('1. Total dos rendimentos tributáveis (inclusive férias e décimo terceiro salário)'),1,'L',1);
$pdf->Cell(30,5,utf8_decode(number_format(0,2,',','.')),'TRB',0,'R');
$pdf->Ln();

$pdf->Cell(160,5,utf8_decode('2. Exclusão: Despesas com a ação judicial'),'LRB','L',1);
$pdf->Cell(30,5,utf8_decode(number_format(0,2,',','.')),'TRB',0,'R');
$pdf->Ln();

$pdf->Cell(160,5,utf8_decode('3. Dedução: Contribuição previdenciária oficial'),'LRB','L',1);
$pdf->Cell(30,5,utf8_decode(number_format(0,2,',','.')),'TRB',0,'R');
$pdf->Ln();

$pdf->Cell(160,5,utf8_decode('4. Dedução: Pensão alimentícia'),'LRB','L',1);
$pdf->Cell(30,5,utf8_decode(number_format(0,2,',','.')),'TRB',0,'R');
$pdf->Ln();

$pdf->Cell(160,5,utf8_decode('5. Imposto sobre a renda retido na fonte'),'LRB','L',1);
$pdf->Cell(30,5,utf8_decode(number_format(0,2,',','.')),'TRB',0,'R');
$pdf->Ln();

$pdf->MultiCell(160,4,utf8_decode('6. Rendimentos isentos de pensão, proventos de aposentadoria ou reforma por moléstia grave ou aposentadoria ou reforma por acidente em serviço'),'LRB','L',0);
//$pdf->MultiCell(140,5,utf8_decode('03. Pensão, Proventos de Aposentadoria ou Reforma por Moléstia Grave e Aposentadoria ou Reforma por Acidente em Serviço'),'LRB','L',0);
$pdf->SetXY(170,207.5);
$pdf->Cell(30,8,utf8_decode(number_format(0,2,',','.')),'RB',0,'R');
//$pdf->Cell(30,5,,'TRB',0,'R');
$pdf->Ln(12);

$pdf->SetFont('arial','B',8);// SELECIONANDO FONTE PARA O CABEÇALHO
$pdf->Cell(170,4,utf8_decode("7. Informações complementares"),0,2);// ESCREVENDO CABEÇALHO 6
$pdf->Cell(190,30,'',1,'L',1);
$pdf->Ln(34);

$pdf->SetFont('arial','B',8);// SELECIONANDO FONTE PARA O CABEÇALHO
$pdf->Cell(170,4,utf8_decode("8. Responsável pelas informações"),0,2);// ESCREVENDO CABEÇALHO 7
$pdf->Cell(160,8,'',1,'L',1);
$pdf->Cell(30,8,'',1,'L',1);
$pdf->SetXY(10,261);// POSICIONANDO PARA ESCREVER
$pdf->SetFont('arial','',6);
$pdf->Write(5,utf8_decode("NOME"));
$pdf->SetXY(170,261);// POSICIONANDO PARA ESCREVER
$pdf->Write(5,utf8_decode("DATA"));
//$pdf->SetXY(1620,262);// POSICIONANDO PARA ESCREVER
//$pdf->Write(5,utf8_decode("ASSINATURA"));

// PREENCHENDO OS DADOS DA EMPRESA
$pdf->SetFont('arial','',8); // SELECIONANDO A FONTE DOS DADOS DE PREENCHIMENTO
$pdf->SetXY(10,265); // POSICIONANDO PARA ESCREVER
$pdf->Write(5,utf8_decode($razao)); // ESCREVENDO O NOME DA EMPRESA
$pdf->SetXY(170,265); // POSICIONANDO PARA ESCREVER
$pdf->Write(5,utf8_decode(date('d/m/Y'))); // ESCREVENDO A DATA
$pdf->SetXY(140,265); // VOLTANDO AO POSICIONAMENTO DE CURSOR PADRÃO
$pdf->Ln(9);

$pdf->SetXY(9,271);// POSICIONANDO PARA ESCREVER
$pdf->SetFont('arial','',7); // SELECIONANDO A FONTE DO RODAPE
$pdf->Write(3,utf8_decode("Dispensa assinatura conforme IN RFB nº 1.215, de 15 de dezembro de 2011.")); // ESCREVENDO RODAPE


//$pdf->Write(30, $corpo, '');

$pdf->Output("Comp-Ret-PF-". urldecode($linha_dados['nome']) . "-" . date('YmdHis') . ".pdf","D");

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