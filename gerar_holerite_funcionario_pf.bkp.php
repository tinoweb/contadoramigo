<?php
// ini_set('display_errors',1);
// ini_set('display_startup_erros',1);
// error_reporting(E_ALL);

// Inicia a sessão.
session_start();

// Realiza a requisição do arquivo que realiza a conexão como banco de dados.
require_once('conect.php') ;

// Realiza a requisição do arquivo que possui a classe para definir numero por extenso.
require_once('classes/numero_extenso_2.php'); 

// Realiza a requisição do arquivo responsavel por manipular os dados para gerar o pdf.
require_once('classes/fpdf/fpdf.php'); 

// Realiza a requisição do arquivo que retorna o objeto com os dados de pagamento do funcionário.
require_once('Model/PagamentoFuncionario/PagamentoFuncionarioData.php');

// Realiza a requisição do arquivo que retorna os dados da empresa.
require_once('Model/DadosEmpresa/DadosEmpresaData.php');

// Realiza a requisição do arquivo que retorna os dados do funcionario.
require_once('Model/DadosFuncionarios/DadosFuncionariosData.php');

/** Adiciona a classe para controlar a rotação no texto **/

class PDF extends FPDF {
	
	var $angle=0;

	function Rotate($angle,$x=-1,$y=-1) {
		if($x==-1)
			$x=$this->x;
		if($y==-1)
			$y=$this->y;
		if($this->angle!=0)
			$this->_out('Q');
		$this->angle=$angle;
		if($angle!=0)
		{
			$angle*=M_PI/180;
			$c=cos($angle);
			$s=sin($angle);
			$cx=$x*$this->k;
			$cy=($this->h-$y)*$this->k;
			$this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
		}
	}

	function _endpage()
	{
		if($this->angle!=0)
		{
			$this->angle=0;
			$this->_out('Q');
		}
		parent::_endpage();
	}

	function RotatedText($x,$y,$txt,$angle) {
		//Text rotated around its origin
		$this->Rotate($angle,$x,$y);
		$this->Text($x,$y,$txt);
		$this->Rotate(0);
	}

	function RotatedImage($file,$x,$y,$w,$h,$angle) {
		//Image rotated around its upper-left corner
		$this->Rotate($angle,$x,$y);
		$this->Image($file,$x,$y,$w,$h);
		$this->Rotate(0);
	}
}

/** Realiza a requisição dos dados de paramento ao funcionario **/

// Dados da Empresa.
$nomeEmpresa = '';
$enderecoEmpresa = '';
$CNPJEmpresa = '';

// Mes e ano do Holerite.
$mesAno = '';

// Dados do Funcionário.
$codigoFuncionario = '';
$nomeFuncionario = '';
$CBOFuncionario = '';
$funcaoFuncionario = ''; 

// Mensagem.
$mensagem = "";

// Valor 
$totalVencimento = '';
$totalDescontos = '';	
$liquidoReceber = '';

// Valores.
$salario = '';
$salarioBase = '';
$baseCalcInss = '';
$baseCalcFgts = '';
$fgtsMes = '';
$baseCalcIrrf = '';
$faixaIrrf = '';

function PegaMesAno($data) {
	
	// Define um array com os meses.	
	$meses = array(
		1 => 'Janeiro',
		2 => 'Fevereiro',
		3 => 'Março',
		4 => 'Abril',
		5 => 'Maio',
		6 => 'Junho',
		7 => 'Julho',
		8 => 'Agosto',
		9 => 'Setembro',
		10 => 'Outubro',
		11 => 'Novembro',
		12 => 'Dezembro'
	);
	
	$dataAux = explode('-',$data);
	
	$ano = $dataAux[0];
	
	$n = (int) $dataAux[1];
	
	$mes = $meses[$n];
	
	return $mes.'/'.$ano;
} 

// Verifica se código do pagamento foi informado.
if(isset($_GET['pagtoId'])) {

	// Instancia de Classes 
	$pagamentoFuncionario = new PagamentoFuncionarioData();
	$empresaData = new DadosEmpresaData();
	$funcionarioData = new DadosFuncionariosData();
		
	// Pega os dados de pagamento do funcionario.
	$dadosPagamento = $pagamentoFuncionario->PegaPagamentoFuncionario($_GET['pagtoId']);
	
	// Pega os dados da empresa.
	$dadosEmpregado = $empresaData->GetDataDadosEmpresa($dadosPagamento->getEmpresaId());
	
	// Pega os dados do funcionário.
	$dadosFuncionario = $funcionarioData->PegaDadosFuncionario($dadosPagamento->getFuncionarioId());
	
	// Dados da Empresa.
	$nomeEmpresa = $dadosEmpregado->getRazaoSocial();
	$enderecoEmpresa = $dadosEmpregado->getEndereco();
	$CNPJEmpresa = $dadosEmpregado->getCNPJ();

	// Mes e ano do Holerite.
	$mesAno = PegaMesAno($dadosPagamento->getDataPagto());

	// Dados do Funcionário.
	$codigoFuncionario = $dadosFuncionario->getFuncionarioId();
	$nomeFuncionario = $dadosFuncionario->getNome();
	$CBOFuncionario = $dadosFuncionario->getCodigoCBO();
	$funcaoFuncionario = $dadosFuncionario->getFuncao(); 

	// Mensagem.
	$mensagem = '';

	// Valor 
	$totalVencimentoAux = $dadosPagamento->getValorBruto();	
	$totalVencimento = number_format($dadosPagamento->getValorBruto(),2,",",".");
	$totalDescontosAux = $dadosPagamento->getValorINSS() + $dadosPagamento->getValorIR() + $dadosPagamento->getValorPensao() + $dadosPagamento->getValorVR() + $dadosPagamento->getValorVT() + $dadosPagamento->getValorFaltas();	
	
	$totalDescontos = number_format($totalDescontosAux,2,",",".");
	$liquidoReceber = number_format(($totalVencimentoAux - $totalDescontosAux),2,",",".");
				
	// Valores.
	$salario = number_format($dadosPagamento->getValorSalario(),2,',','.');
	$salarioBase = number_format($dadosPagamento->getValorBruto(),2,",",".");
	$baseCalcInss = number_format($totalVencimentoAux,2,",",".");
	$baseCalcFgts = number_format($totalVencimentoAux,2,",",".");
	$fgtsMes = number_format((($totalVencimentoAux * 8) / 100),2,",",".");
	$baseCalcIrrf = $dadosPagamento->getValorBruto() - $dadosPagamento->getValorINSS() - $dadosPagamento->getDescontoDepValor() - $dadosPagamento->getValorPensao();
	$baseCalcIrrf = number_format($baseCalcIrrf,2,",",".");
	$faixaIrrf = $dadosPagamento->getFaixaIR();	
	
	// Defina as linhas para iniciar sem valor;
	$descricao = array(1=>'', 2=>'', 3=>'', 4=>'', 5=>'', 6=>'', 7=>'', 8=>'', 9=>'', 10=>'', 11=>'', 12=>'');
	$codigo = $referencia = $provento =	$desconto = $descricao;
	
	$numLinha = 1;
	
	if($dadosPagamento->getValorSalario()){
		$codigo[$numLinha] = '01';
		$referencia[$numLinha] = $dadosPagamento->getDiasTrabalhado().($dadosPagamento->getDiasTrabalhado() > 1 ? ' Dias' : ' Dia' );
		$descricao[$numLinha] = 'Salário Base';
		$provento[$numLinha] = number_format($dadosPagamento->getValorSalario(),2,',','.');
		$numLinha += 1;
	}
	
	if($dadosPagamento->getValorFamilia()){
		$codigo[$numLinha] = '02';
		$descricao[$numLinha] = 'Salário Familía';
		$provento[$numLinha] = number_format($dadosPagamento->getValorFamilia(),2,',','.');
		$numLinha += 1;		
	}
	
	if($dadosPagamento->getValorMaternidade()){
		$codigo[$numLinha] = '03';
		$descricao[$numLinha] = 'Salário Maternidade';
		$provento[$numLinha] = number_format($dadosPagamento->getValorMaternidade(),2,',','.');
		$numLinha += 1;	
	}
	
	if($dadosPagamento->getValorAbono()){
		$codigo[$numLinha] = '04';
		$descricao[$numLinha] = 'Abono Salárial';
		$provento[$numLinha] = number_format($dadosPagamento->getValorAbono(),2,',','.');
		$numLinha += 1;	
	}
		
	if($dadosPagamento->getValorINSS()){
		$codigo[$numLinha] = '06';
		$descricao[$numLinha] = 'INSS';	
		$referencia[$numLinha] = $dadosPagamento->getReferenciaINSS().'%';
		$desconto[$numLinha] = number_format($dadosPagamento->getValorINSS(),2,',','.');
		$numLinha += 1;	
	}

	if($dadosPagamento->getValorIR()){
		$codigo[$numLinha] = '07';
		$descricao[$numLinha] = 'IRFF';	
		$referencia[$numLinha] = $dadosPagamento->getReferenciaIR().'%';
		$desconto[$numLinha] = number_format($dadosPagamento->getValorIR(),2,',','.');
		$numLinha += 1;	
	}
	
	if($dadosPagamento->getValorPensao()){
		$codigo[$numLinha] = '08';
		$descricao[$numLinha] = 'Pensão';	
		$referencia[$numLinha] = $dadosPagamento->getReferenciaPensao().'%';
		$desconto[$numLinha] = number_format($dadosPagamento->getValorPensao(),2,',','.');
		$numLinha += 1;	
	}	
	
	if($dadosPagamento->getValorVR()){
		$codigo[$numLinha] = '09';
		$descricao[$numLinha] = 'Vale Refeição';	
		$referencia[$numLinha] = $dadosPagamento->getReferenciaVR().'%';
		$desconto[$numLinha] = number_format($dadosPagamento->getValorVR(),2,',','.');
		$numLinha += 1;	
	}
	
	if($dadosPagamento->getValorVT()){
		$codigo[$numLinha] = '10';
		$descricao[$numLinha] = 'Vale Transporte';	
		$referencia[$numLinha] = $dadosPagamento->getReferenciaVT().'%';
		$desconto[$numLinha] = number_format($dadosPagamento->getValorVT(),2,',','.');
		$numLinha += 1;	
	}

	if($dadosPagamento->getFaltas()){
		$codigo[$numLinha] = '11';
		$descricao[$numLinha] = 'Faltas';	
		$referencia[$numLinha] = $dadosPagamento->getFaltas().($dadosPagamento->getFaltas() > 1 ? ' Dias' : ' Dia' );
		$desconto[$numLinha] = number_format($dadosPagamento->getValorFaltas(),2,',','.');
		$numLinha += 1;	
	}	
}

/** Monta o PDF. **/

// Define o tipo de folha.
$pdf = new PDF("P","mm","A4");


$pdf->SetTopMargin(10);
$pdf->AddPage();
 
/** Define as celulas **/

// SELECIONANDO FONTE PARA O HEADER ESQUERDO
$pdf->SetFont('arial','B',8); 

//
$pdf->SetFillColor(225,225,225);

// CELULA QUE ENVOLVE O TEXTO 
$pdf->Cell(170,18,'','TRBL','L',1);

// CELULA DO CAMPO DE ASSINATURA. 
$pdf->Cell(3,3,'','','R',1);

// CELULA DO CAMPO DE ASSINATURA. 
$pdf->Cell(20,113,'','TRBL','R',1);

// Define o tamanho da fonte.
$pdf->SetFont('arial','',6);

// CONTEÚDO
$pdf->SetXY(12,10);
$pdf->Write(8,utf8_decode("EMPREGADOR")); 

// Titulo Nome
$pdf->SetXY(12,13);
$pdf->Write(8,utf8_decode("Nome:")); 

// Nome
$pdf->SetXY(25,13);
$pdf->Write(8,utf8_decode($nomeEmpresa)); 

// Titulo Endereço
$pdf->SetXY(12,17);
$pdf->Write(8,utf8_decode("Endereço:"));

// Endereço
$pdf->SetXY(25,17);
$pdf->Write(8,utf8_decode($enderecoEmpresa));

// Titulo CNPJ
$pdf->SetXY(12,21);
$pdf->Write(8,utf8_decode("CNPJ")); 

// CNPJ
$pdf->SetXY(25,21);
$pdf->Write(8,utf8_decode($CNPJEmpresa)); 

//Recibo de Pagamento de Salário
$pdf->SetFont('arial','',10); // SELECIONANDO FONTE PARA O HEADER DIREITO
$pdf->SetXY(125,11); // DEFINE A POSIÇÃO DO TEXTO 
$pdf->Write(8,utf8_decode("Recibo de Pagamento de Salário")); // ESCREVE O TEXTO.

// Referente ao Mês / Ano
$pdf->SetFont('arial','',6);
$pdf->SetXY(153,15);
$pdf->Write(8,utf8_decode("Referente ao Mês / Ano")); 

// Define o mes
$pdf->SetFont('arial','',10);
$pdf->SetXY(153,20);
$pdf->Write(8,utf8_decode($mesAno)); 

// Define o tanho da linha.
$pdf->Ln(10);

$pdf->SetFont('arial','',6);
$pdf->Cell(170,10,'',1,'L',1);

// Titulo cóidigo
$pdf->SetXY(12,30);
$pdf->Write(8,utf8_decode("CÓDIGO")); 

// cóidigo
$pdf->SetXY(12,33);
$pdf->Write(8,utf8_decode($codigoFuncionario)); 

// titulo nome do funcionário
$pdf->SetXY(25,30);
$pdf->Write(8,utf8_decode("NOME DO FUNCIONÁRIO"));

// Nome do funcionário
$pdf->SetXY(25,33);
$pdf->Write(8,utf8_decode($nomeFuncionario)); 

// Titulo CBO
$pdf->SetXY(100,30);
$pdf->Write(8,utf8_decode("CBO")); 

// CBO
$pdf->SetXY(100,33);
$pdf->Write(8,utf8_decode($CBOFuncionario)); 

// Titulo função
$pdf->SetXY(125,30);
$pdf->Write(8,utf8_decode("FUNÇÃO")); 

// Titulo função
$pdf->SetXY(125,33);
$pdf->Write(8,utf8_decode($funcaoFuncionario)); 

$pdf->Ln(9);

// Define cabeçalho.
$pdf->SetFont('arial','',7);
$pdf->Cell(15,5,'',1,'L',1);
$pdf->Cell(75,5,'','TRB','L',1);
$pdf->Cell(26,5,'','TRB','L',1);
$pdf->Cell(27,5,'','TRB','L',1);
$pdf->Cell(27,5,'','TRB','L',1);

// Titulo CÓD
$pdf->SetXY(12,41);
$pdf->Write(8,utf8_decode("CÓD")); 

// Titulo Descrição
$pdf->SetXY(25,41);
$pdf->Write(8,utf8_decode($descricao[1])); 

// Titulo Referência
$pdf->SetXY(107,41);
$pdf->Write(8,utf8_decode("Referência")); 

// Titulo Proventos
$pdf->SetXY(132,41);
$pdf->Write(8,utf8_decode("Proventos")); 

// Titulo Descontos
$pdf->SetXY(160,41);
$pdf->Write(8,utf8_decode("Descontos")); 

$pdf->Ln(6);

// Campo que recebera valores.
$pdf->SetFont('arial','',7);
$pdf->Cell(15,55,'',1,'L',1);
$pdf->Cell(75,55,'','TRB','L',1);
$pdf->Cell(26,55,'','TRB','L',1);
$pdf->Cell(27,62,'','TRB','L',1);
$pdf->Cell(27,62,'','TRB','L',1);

// cóidigo
$pdf->SetXY(12,48);
$pdf->Write(8,utf8_decode($codigo[1])); 
$pdf->SetXY(12,52);
$pdf->Write(8,utf8_decode($codigo[2])); 
$pdf->SetXY(12,56);
$pdf->Write(8,utf8_decode($codigo[3])); 
$pdf->SetXY(12,60);
$pdf->Write(8,utf8_decode($codigo[4])); 
$pdf->SetXY(12,64);
$pdf->Write(8,utf8_decode($codigo[5])); 
$pdf->SetXY(12,68);
$pdf->Write(8,utf8_decode($codigo[6])); 
$pdf->SetXY(12,72);
$pdf->Write(8,utf8_decode($codigo[7])); 
$pdf->SetXY(12,76);
$pdf->Write(8,utf8_decode($codigo[8])); 
$pdf->SetXY(12,80);
$pdf->Write(8,utf8_decode($codigo[9])); 
$pdf->SetXY(12,84);
$pdf->Write(8,utf8_decode($codigo[10])); 
$pdf->SetXY(12,88);
$pdf->Write(8,utf8_decode($codigo[11])); 
$pdf->SetXY(12,92);
$pdf->Write(8,utf8_decode($codigo[12]));

// Descrição
$pdf->SetXY(25,48);
$pdf->Write(8,utf8_decode($descricao[1])); 
$pdf->SetXY(25,52);
$pdf->Write(8,utf8_decode($descricao[2])); 
$pdf->SetXY(25,56);
$pdf->Write(8,utf8_decode($descricao[3])); 
$pdf->SetXY(25,60);
$pdf->Write(8,utf8_decode($descricao[4])); 
$pdf->SetXY(25,64);
$pdf->Write(8,utf8_decode($descricao[5])); 
$pdf->SetXY(25,68);
$pdf->Write(8,utf8_decode($descricao[6])); 
$pdf->SetXY(25,72);
$pdf->Write(8,utf8_decode($descricao[7])); 
$pdf->SetXY(25,76);
$pdf->Write(8,utf8_decode($descricao[8])); 
$pdf->SetXY(25,80);
$pdf->Write(8,utf8_decode($descricao[9])); 
$pdf->SetXY(25,84);
$pdf->Write(8,utf8_decode($descricao[10])); 
$pdf->SetXY(25,88);
$pdf->Write(8,utf8_decode($descricao[11]));
$pdf->SetXY(25,92);
$pdf->Write(8,utf8_decode($descricao[12]));

// Referência
$pdf->SetXY(107,48);
$pdf->Write(8,utf8_decode($referencia[1])); 
$pdf->SetXY(107,52);
$pdf->Write(8,utf8_decode($referencia[2])); 
$pdf->SetXY(107,56);
$pdf->Write(8,utf8_decode($referencia[3])); 
$pdf->SetXY(107,60);
$pdf->Write(8,utf8_decode($referencia[4])); 
$pdf->SetXY(107,64);
$pdf->Write(8,utf8_decode($referencia[5])); 
$pdf->SetXY(107,68);
$pdf->Write(8,utf8_decode($referencia[6])); 
$pdf->SetXY(107,72);
$pdf->Write(8,utf8_decode($referencia[7])); 
$pdf->SetXY(107,76);
$pdf->Write(8,utf8_decode($referencia[8])); 
$pdf->SetXY(107,80);
$pdf->Write(8,utf8_decode($referencia[9])); 
$pdf->SetXY(107,84);
$pdf->Write(8,utf8_decode($referencia[10])); 
$pdf->SetXY(107,88);
$pdf->Write(8,utf8_decode($referencia[11]));
$pdf->SetXY(107,92);
$pdf->Write(8,utf8_decode($referencia[12]));

// Proventos
$pdf->SetXY(132,48);
$pdf->Write(8,utf8_decode($provento[1])); 
$pdf->SetXY(132,52);
$pdf->Write(8,utf8_decode($provento[2])); 
$pdf->SetXY(132,56);
$pdf->Write(8,utf8_decode($provento[3])); 
$pdf->SetXY(132,60);
$pdf->Write(8,utf8_decode($provento[4])); 
$pdf->SetXY(132,64);
$pdf->Write(8,utf8_decode($provento[5])); 
$pdf->SetXY(132,68);
$pdf->Write(8,utf8_decode($provento[6])); 
$pdf->SetXY(132,72);
$pdf->Write(8,utf8_decode($provento[7])); 
$pdf->SetXY(132,76);
$pdf->Write(8,utf8_decode($provento[8])); 
$pdf->SetXY(132,80);
$pdf->Write(8,utf8_decode($provento[9])); 
$pdf->SetXY(132,84);
$pdf->Write(8,utf8_decode($provento[10])); 
$pdf->SetXY(132,88);
$pdf->Write(8,utf8_decode($provento[11]));
$pdf->SetXY(132,92);
$pdf->Write(8,utf8_decode($provento[12]));

// Descontos
$pdf->SetXY(160,48);
$pdf->Write(8,utf8_decode($desconto[1])); 
$pdf->SetXY(160,52);
$pdf->Write(8,utf8_decode($desconto[2])); 
$pdf->SetXY(160,56);
$pdf->Write(8,utf8_decode($desconto[3])); 
$pdf->SetXY(160,60);
$pdf->Write(8,utf8_decode($desconto[4])); 
$pdf->SetXY(160,64);
$pdf->Write(8,utf8_decode($desconto[5])); 
$pdf->SetXY(160,68);
$pdf->Write(8,utf8_decode($desconto[6])); 
$pdf->SetXY(160,72);
$pdf->Write(8,utf8_decode($desconto[7])); 
$pdf->SetXY(160,76);
$pdf->Write(8,utf8_decode($desconto[8])); 
$pdf->SetXY(160,80);
$pdf->Write(8,utf8_decode($desconto[9])); 
$pdf->SetXY(160,84);
$pdf->Write(8,utf8_decode($desconto[10])); 
$pdf->SetXY(160,88);
$pdf->Write(8,utf8_decode($desconto[11]));
$pdf->SetXY(160,92);
$pdf->Write(8,utf8_decode($desconto[12]));

// Linhas
$pdf->Ln(10);

// Define cabeçalho.
$pdf->SetFont('arial','',7);
$pdf->Cell(116,14,'',1,'L',1);
$pdf->Cell(27,14,'','TRB','L',1);
$pdf->Cell(27,14,'','TRB','L',1);


// Titulo Mensagem
$pdf->SetXY(12,100);
$pdf->Write(8,utf8_decode("Mensagem"));

// Mensagem
$pdf->SetXY(12,103);
$pdf->Write(8,utf8_decode($mensagem));

// Titulo Total dos Vencimentos
$pdf->SetXY(126,100);
$pdf->Write(8,utf8_decode("Total dos Vencimentos")); 

// Total dos Vencimentos
$pdf->SetXY(132,103);
$pdf->Write(8,utf8_decode($totalVencimento)); 


// Titulo Total dos Descontos
$pdf->SetXY(154,100);
$pdf->Write(8,utf8_decode("Total dos Descontos"));

// Total dos Descontos
$pdf->SetXY(160,103);
$pdf->Write(8,utf8_decode($totalDescontos));

// Titulo Proventos
$pdf->SetXY(127,109);
$pdf->Write(8,utf8_decode("Líquido a Receber->")); 

// Titulo Descontos
$pdf->SetXY(160,109);
$pdf->Write(8,utf8_decode($liquidoReceber)); 

// Linhas
$pdf->Ln(7);

// Define cabeçalho.
$pdf->SetFont('arial','',7);
$pdf->Cell(170,7,'',1,'L',1);

// Titulo Salário Base
$pdf->SetXY(12,114);
$pdf->Write(8,utf8_decode("Salário Base")); 

// Salário Base
$pdf->SetXY(14,117);
$pdf->Write(8,utf8_decode($salario)); 

// Titulo Base Cálc. INSS
$pdf->SetXY(35,114);
$pdf->Write(8,utf8_decode("Base Cálc. INSS"));

// Base Cálc. INSS
$pdf->SetXY(39,117);
$pdf->Write(8,utf8_decode($baseCalcInss));

// Titulo Base Cálc.FGTS
$pdf->SetXY(63,114);
$pdf->Write(8,utf8_decode("Base Cálc.FGTS")); 

// Total Base Cálc.FGTS
$pdf->SetXY(69,117);
$pdf->Write(8,utf8_decode($baseCalcFgts)); 

// Titulo FGTS do Mês
$pdf->SetXY(95,114);
$pdf->Write(8,utf8_decode("FGTS do Mês"));

// Total FGTS do Mês
$pdf->SetXY(100,117);
$pdf->Write(8,utf8_decode($fgtsMes));

// Titulo Base Cálc. IRRF
$pdf->SetXY(126,114);
$pdf->Write(8,utf8_decode("Base Cálc. IRRF")); 

// Total dos Vencimentos
$pdf->SetXY(132,117);
$pdf->Write(8,utf8_decode($baseCalcIrrf)); 

// Titulo Faixa IRRF
$pdf->SetXY(159,114);
$pdf->Write(8,utf8_decode("Faixa IRRF"));

// Total dos Descontos
$pdf->SetXY(165,117);
$pdf->Write(8,utf8_decode($faixaIrrf));

/** Campo de Assinatura **/
$pdf->SetFont('Arial','',6);
$pdf->RotatedText(188,116,utf8_decode('DECLARO TER RECEBIDO A IMPORTÂNCIA LÍQUIDA DISCRIMINADA NESTE RECIBO.'),90);
$pdf->SetFont('Arial','',7);

// Campo de data.
$pdf->SetFont('Arial','',6);
$pdf->RotatedText(194,116,'____/____/________',90);
$pdf->RotatedText(198,110,'DATA',90);

// Campo de assiantura.
$pdf->SetFont('Arial','',6);
$pdf->RotatedText(194,80,'_________________________________________________',90);
$pdf->RotatedText(198,70,utf8_decode('ASSINATURA DO FUNCIONÁRIO'),90);

// Titulo Salário Base
$pdf->SetXY(9,121);
$pdf->Write(8,utf8_decode("1ª VIA - EMPREGADOR"));

// Titulo Salário Base
$pdf->SetXY(20,125);
$pdf->Write(8,utf8_decode("------")); 

// Titulo Salário Base
$pdf->SetXY(180,125);
$pdf->Write(8,utf8_decode("------")); 

/** Segundo via do Empregado **/

// Linhas
$pdf->Ln(9);

// SELECIONANDO FONTE PARA O HEADER ESQUERDO
$pdf->SetFont('arial','B',8); 

// CELULA QUE ENVOLVE O TEXTO 
$pdf->Cell(170,18,'','TRBL','L',1);

// CELULA DO CAMPO DE ASSINATURA. 
$pdf->Cell(3,3,'','','R',1);

// CELULA DO CAMPO DE ASSINATURA. 
$pdf->Cell(20,113,'','TRBL','R',1);

// Define o tamanho da fonte.
$pdf->SetFont('arial','',6);

// CONTEÚDO
$pdf->SetXY(12,134);
$pdf->Write(8,utf8_decode("EMPREGADOR")); 

// Titulo Nome
$pdf->SetXY(12,137);
$pdf->Write(8,utf8_decode("Nome:")); 

// Nome
$pdf->SetXY(25,137);
$pdf->Write(8,utf8_decode($nomeEmpresa)); 

// Titulo Endereço
$pdf->SetXY(12,141);
$pdf->Write(8,utf8_decode("Endereço:"));

// Endereço
$pdf->SetXY(25,141);
$pdf->Write(8,utf8_decode($enderecoEmpresa));

// Titulo CNPJ
$pdf->SetXY(12,145);
$pdf->Write(8,utf8_decode("CNPJ")); 

// CNPJ
$pdf->SetXY(25,145);
$pdf->Write(8,utf8_decode($CNPJEmpresa)); 

// SELECIONANDO FONTE PARA O HEADER DIREITO
$pdf->SetFont('arial','',10);
$pdf->SetXY(113,135);
$pdf->Write(8,utf8_decode("Demonstrativo de Pagamento de Salário")); 

// Referente ao Mês / Ano
$pdf->SetFont('arial','',6);
$pdf->SetXY(153,139);
$pdf->Write(8,utf8_decode("Referente ao Mês / Ano")); 

// Define o mes
$pdf->SetFont('arial','',10);
$pdf->SetXY(153,144);
$pdf->Write(8,utf8_decode($mesAno)); 

// Define o tanho da linha.
$pdf->Ln(10);

$pdf->SetFont('arial','',6);
$pdf->Cell(170,10,'',1,'L',1);

// Titulo cóidigo
$pdf->SetXY(12,154);
$pdf->Write(8,utf8_decode("CÓDIGO")); 

// cóidigo
$pdf->SetXY(12,157);
$pdf->Write(8,utf8_decode($codigoFuncionario)); 

// titulo nome do funcionário
$pdf->SetXY(25,154);
$pdf->Write(8,utf8_decode("NOME DO FUNCIONÁRIO"));

// Nome do funcionário
$pdf->SetXY(25,157);
$pdf->Write(8,utf8_decode($nomeFuncionario)); 

// Titulo CBO
$pdf->SetXY(100,154);
$pdf->Write(8,utf8_decode("CBO")); 

// CBO
$pdf->SetXY(100,157);
$pdf->Write(8,utf8_decode($CBOFuncionario)); 

// Titulo função
$pdf->SetXY(125,154);
$pdf->Write(8,utf8_decode("FUNÇÃO")); 

// Titulo função
$pdf->SetXY(125,157);
$pdf->Write(8,utf8_decode($funcaoFuncionario)); 

$pdf->Ln(9);

// Define cabeçalho.
$pdf->SetFont('arial','',7);
$pdf->Cell(15,5,'',1,'L',1);
$pdf->Cell(75,5,'','TRB','L',1);
$pdf->Cell(26,5,'','TRB','L',1);
$pdf->Cell(27,5,'','TRB','L',1);
$pdf->Cell(27,5,'','TRB','L',1);

// Titulo CÓD
$pdf->SetXY(12,165);
$pdf->Write(8,utf8_decode("CÓD")); 

// Titulo Descrição
$pdf->SetXY(25,165);
$pdf->Write(8,utf8_decode($descricao[1])); 

// Titulo Referência
$pdf->SetXY(107,165);
$pdf->Write(8,utf8_decode("Referência")); 

// Titulo Proventos
$pdf->SetXY(132,165);
$pdf->Write(8,utf8_decode("Proventos")); 

// Titulo Descontos
$pdf->SetXY(160,165);
$pdf->Write(8,utf8_decode("Descontos")); 

$pdf->Ln(6);

// Campo que recebera valores.
$pdf->SetFont('arial','',7);
$pdf->Cell(15,55,'',1,'L',1);
$pdf->Cell(75,55,'','TRB','L',1);
$pdf->Cell(26,55,'','TRB','L',1);
$pdf->Cell(27,62,'','TRB','L',1);
$pdf->Cell(27,62,'','TRB','L',1);

// cóidigo
$pdf->SetXY(12,172);
$pdf->Write(8,utf8_decode($codigo[1])); 
$pdf->SetXY(12,176);
$pdf->Write(8,utf8_decode($codigo[2])); 
$pdf->SetXY(12,180);
$pdf->Write(8,utf8_decode($codigo[3])); 
$pdf->SetXY(12,184);
$pdf->Write(8,utf8_decode($codigo[4])); 
$pdf->SetXY(12,188);
$pdf->Write(8,utf8_decode($codigo[5])); 
$pdf->SetXY(12,192);
$pdf->Write(8,utf8_decode($codigo[6]));
$pdf->SetXY(12,196);
$pdf->Write(8,utf8_decode($codigo[7])); 
$pdf->SetXY(12,200);
$pdf->Write(8,utf8_decode($codigo[8])); 
$pdf->SetXY(12,204);
$pdf->Write(8,utf8_decode($codigo[9])); 
$pdf->SetXY(12,208);
$pdf->Write(8,utf8_decode($codigo[10])); 
$pdf->SetXY(12,212);
$pdf->Write(8,utf8_decode($codigo[11])); 
$pdf->SetXY(12,216);
$pdf->Write(8,utf8_decode($codigo[12]));

// Descrição
$pdf->SetXY(25,172);
$pdf->Write(8,utf8_decode($descricao[1])); 
$pdf->SetXY(25,176);
$pdf->Write(8,utf8_decode($descricao[2])); 
$pdf->SetXY(25,180);
$pdf->Write(8,utf8_decode($descricao[3])); 
$pdf->SetXY(25,184);
$pdf->Write(8,utf8_decode($descricao[4])); 
$pdf->SetXY(25,188);
$pdf->Write(8,utf8_decode($descricao[5]));
$pdf->SetXY(25,192);
$pdf->Write(8,utf8_decode($descricao[6]));
$pdf->SetXY(25,196);
$pdf->Write(8,utf8_decode($descricao[7])); 
$pdf->SetXY(25,200);
$pdf->Write(8,utf8_decode($descricao[8])); 
$pdf->SetXY(25,204);
$pdf->Write(8,utf8_decode($descricao[9])); 
$pdf->SetXY(25,208);
$pdf->Write(8,utf8_decode($descricao[10])); 
$pdf->SetXY(25,212);
$pdf->Write(8,utf8_decode($descricao[11])); 
$pdf->SetXY(25,216);
$pdf->Write(8,utf8_decode($descricao[12])); 

// Referência
$pdf->SetXY(107,172);
$pdf->Write(8,utf8_decode($referencia[1])); 
$pdf->SetXY(107,176);
$pdf->Write(8,utf8_decode($referencia[2])); 
$pdf->SetXY(107,180);
$pdf->Write(8,utf8_decode($referencia[3])); 
$pdf->SetXY(107,184);
$pdf->Write(8,utf8_decode($referencia[4])); 
$pdf->SetXY(107,188);
$pdf->Write(8,utf8_decode($referencia[5])); 
$pdf->SetXY(107,192);
$pdf->Write(8,utf8_decode($referencia[6])); 
$pdf->SetXY(107,196);
$pdf->Write(8,utf8_decode($referencia[7])); 
$pdf->SetXY(107,200);
$pdf->Write(8,utf8_decode($referencia[8])); 
$pdf->SetXY(107,204);
$pdf->Write(8,utf8_decode($referencia[9])); 
$pdf->SetXY(107,208);
$pdf->Write(8,utf8_decode($referencia[10])); 
$pdf->SetXY(107,212);
$pdf->Write(8,utf8_decode($referencia[11]));
$pdf->SetXY(107,216);
$pdf->Write(8,utf8_decode($referencia[12]));

// Proventos
$pdf->SetXY(132,172);
$pdf->Write(8,utf8_decode($provento[1])); 
$pdf->SetXY(132,176);
$pdf->Write(8,utf8_decode($provento[2])); 
$pdf->SetXY(132,180);
$pdf->Write(8,utf8_decode($provento[3])); 
$pdf->SetXY(132,184);
$pdf->Write(8,utf8_decode($provento[4])); 
$pdf->SetXY(132,188);
$pdf->Write(8,utf8_decode($provento[5])); 
$pdf->SetXY(132,192);
$pdf->Write(8,utf8_decode($provento[6])); 
$pdf->SetXY(132,196);
$pdf->Write(8,utf8_decode($provento[7])); 
$pdf->SetXY(132,200);
$pdf->Write(8,utf8_decode($provento[8])); 
$pdf->SetXY(132,204);
$pdf->Write(8,utf8_decode($provento[9])); 
$pdf->SetXY(132,208);
$pdf->Write(8,utf8_decode($provento[10])); 
$pdf->SetXY(132,212);
$pdf->Write(8,utf8_decode($provento[11]));
$pdf->SetXY(132,216);
$pdf->Write(8,utf8_decode($provento[12]));

// Descontos
$pdf->SetXY(160,172);
$pdf->Write(8,utf8_decode($desconto[1])); 
$pdf->SetXY(160,176);
$pdf->Write(8,utf8_decode($desconto[2])); 
$pdf->SetXY(160,180);
$pdf->Write(8,utf8_decode($desconto[3])); 
$pdf->SetXY(160,184);
$pdf->Write(8,utf8_decode($desconto[4])); 
$pdf->SetXY(160,188);
$pdf->Write(8,utf8_decode($desconto[5])); 
$pdf->SetXY(160,192);
$pdf->Write(8,utf8_decode($desconto[6])); 
$pdf->SetXY(160,196);
$pdf->Write(8,utf8_decode($desconto[7])); 
$pdf->SetXY(160,200);
$pdf->Write(8,utf8_decode($desconto[8])); 
$pdf->SetXY(160,204);
$pdf->Write(8,utf8_decode($desconto[9])); 
$pdf->SetXY(160,208);
$pdf->Write(8,utf8_decode($desconto[10])); 
$pdf->SetXY(160,212);
$pdf->Write(8,utf8_decode($desconto[11]));
$pdf->SetXY(160,216);
$pdf->Write(8,utf8_decode($desconto[12]));

// Linhas
$pdf->Ln(10);

// Define cabeçalho.
$pdf->SetFont('arial','',7);
$pdf->Cell(116,14,'',1,'L',1);
$pdf->Cell(27,14,'','TRB','L',1);
$pdf->Cell(27,14,'','TRB','L',1);

// Titulo Mensagem
$pdf->SetXY(12,224);
$pdf->Write(8,utf8_decode("Mensagem"));

// Mensagem
$pdf->SetXY(12,227);
$pdf->Write(8,utf8_decode($mensagem));

// Titulo Total dos Vencimentos
$pdf->SetXY(126,224);
$pdf->Write(8,utf8_decode("Total dos Vencimentos")); 

// Total dos Vencimentos
$pdf->SetXY(132,227);
$pdf->Write(8,utf8_decode($totalVencimento)); 


// Titulo Total dos Descontos
$pdf->SetXY(154,224);
$pdf->Write(8,utf8_decode("Total dos Descontos"));

// Total dos Descontos
$pdf->SetXY(160,227);
$pdf->Write(8,utf8_decode($totalDescontos));

// Titulo Proventos
$pdf->SetXY(127,233);
$pdf->Write(8,utf8_decode("Líquido a Receber->")); 

// Titulo Descontos
$pdf->SetXY(160,233);
$pdf->Write(8,utf8_decode($liquidoReceber)); 

// Linhas
$pdf->Ln(7);

// Define cabeçalho.
$pdf->SetFont('arial','',7);
$pdf->Cell(170,7,'',1,'L',1);

// Titulo Salário Base
$pdf->SetXY(12,238);
$pdf->Write(8,utf8_decode("Salário Base")); 

// Salário Base
$pdf->SetXY(14,241);
$pdf->Write(8,utf8_decode($salarioBase)); 


// Titulo Base Cálc. INSS
$pdf->SetXY(35,238);
$pdf->Write(8,utf8_decode("Base Cálc. INSS"));

// Base Cálc. INSS
$pdf->SetXY(39,241);
$pdf->Write(8,utf8_decode($baseCalcInss));

// Titulo Base Cálc.FGTS
$pdf->SetXY(63,238);
$pdf->Write(8,utf8_decode("Base Cálc.FGTS")); 

// Total Base Cálc.FGTS
$pdf->SetXY(69,241);
$pdf->Write(8,utf8_decode($baseCalcFgts)); 

// Titulo FGTS do Mês
$pdf->SetXY(95,238);
$pdf->Write(8,utf8_decode("FGTS do Mês"));

// Total FGTS do Mês
$pdf->SetXY(100,241);
$pdf->Write(8,utf8_decode($fgtsMes));

// Titulo Base Cálc. IRRF
$pdf->SetXY(126,238);
$pdf->Write(8,utf8_decode("Base Cálc. IRRF")); 

// Total dos Vencimentos
$pdf->SetXY(132,241);
$pdf->Write(8,utf8_decode($baseCalcIrrf)); 

// Titulo Faixa IRRF
$pdf->SetXY(159,238);
$pdf->Write(8,utf8_decode("Faixa IRRF"));

// Total dos Descontos
$pdf->SetXY(165,241);
$pdf->Write(8,utf8_decode($faixaIrrf));

/** Campo de Assinatura **/
$pdf->SetFont('Arial','',6);
$pdf->RotatedText(188,240,utf8_decode('DECLARO TER RECEBIDO A IMPORTÂNCIA LÍQUIDA DISCRIMINADA NESTE RECIBO.'),90);
$pdf->SetFont('Arial','',7);

// Campo de data.
$pdf->SetFont('Arial','',6);
$pdf->RotatedText(194,240,'____/____/________',90);
$pdf->RotatedText(198,235,'DATA',90);

// Campo de assiantura.
$pdf->SetFont('Arial','',6);
$pdf->RotatedText(194,204,'_________________________________________________',90);
$pdf->RotatedText(198,194,utf8_decode('ASSINATURA DO FUNCIONÁRIO'),90);

// 2ª VIA - EMPREGADO
$pdf->SetXY(9,245);
$pdf->Write(8,utf8_decode("2ª VIA - EMPREGADO"));

// realiza a impressao no navegador
//$pdf->Output();

// Gera o holerite em pdf.
$pdf->Output("holerite_".str_replace(' ','-',$nomeFuncionario)."_". date('YmdHis') . ".pdf","D");

?>