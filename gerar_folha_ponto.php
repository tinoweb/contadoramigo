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
class GeraDocumentoPDF extends FPDF {
	
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

	function PegaMesAno($mes, $ano) {

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

		$n = (int) $mes;

		$mes = $meses[$n];

		return $mes.'/'.$ano;
	}
}

// Variável com data.
$mesAno = "";

// Variáveis empresa.
$nomeEmpresa = '';
$cnpjEmpresa = '';

// Variáveis empregado.
$nomeEmpregdo = '';
$cargo = '';
$cpf = '';
$ctps = '';


// Verifica se foi informado o id do funcionario.
if(isset($_GET['funcionarioId']) && !empty($_GET['funcionarioId'])){
	
	// Instância a classe que manipula os dados do funcinário.
	$dadosFuncionario = new DadosFuncionariosData();
	
	// pega os dados do funcionário.
	$dados = $dadosFuncionario->PegaDadosFuncionario($_GET['funcionarioId']);
	
	// Verifica se existe dados do funcionário.
	if($dados) {
		$nomeEmpregdo = $dados->getNome();
		$cargo = $dados->getFuncao();
		$cpf = $dados->getCPF();
		$ctps = $dados->getCTPS();
	}
}

// Verifica se foi informado o id do funcionario.
if(isset($_GET['empresaId']) && !empty($_GET['empresaId'])){
	
	$empresaId = $_GET['empresaId'];
	
	// Instância a classe que manipula os dados da empresa. 	
	$dadosEmpresa = new DadosEmpresaData();
	$dadosEmp = $dadosEmpresa->GetDataDadosEmpresa($empresaId);

	$nomeEmpresa = $dadosEmp->getRazaoSocial();
	$cnpjEmpresa = $dadosEmp->getCNPJ();
}

// Define o tipo de folha.
$pdf = new GeraDocumentoPDF("P","mm","A4");


if(isset($_GET['mes']) && !empty($_GET['mes']) && isset($_GET['ano']) && !empty($_GET['ano']) ) {
	
	// Variável com data.
	$mesAno = $pdf->PegaMesAno($_GET['mes'], $_GET['ano']);	
}

$pdf->SetTopMargin(10);

// Página - 1
$pdf->AddPage();
 
/** Define as celulas **/

// DEFINE A FONTE E O TAMANHO DELA PARA O TITULO.
$pdf->SetFont('arial','B',14); 

// Titulo Nome
$pdf->SetXY(50,10);
$pdf->Write(8,utf8_decode("Folha de Ponto - Período:")); 


$pdf->SetXY(130,10);
$pdf->Write(8,utf8_decode($mesAno));


// Define a fonte e o tamnho. 
$pdf->SetFont('arial','B',10); 

$pdf->Ln(18);

$pdf->Cell(190,6,utf8_decode('Empregador(a)'),'TRBL',0,'L');

// Define a fonte e o tamnho. 
$pdf->SetFont('arial','',10);

$pdf->Ln(6);
$pdf->Cell(95,6,utf8_decode($nomeEmpresa),'TRBL',0,'L');
$pdf->Cell(95,6,utf8_decode('CNPJ:'.$cnpjEmpresa),'TRBL',0,'L');

// Define a fonte e o tamnho. 
$pdf->SetFont('arial','B',10);

$pdf->Ln(12);
$pdf->Cell(190,6,utf8_decode('Empregado(a)'),'TRBL',0,'L');

// Define a fonte e o tamnho. 
$pdf->SetFont('arial','',10);

$pdf->Ln(6);
$pdf->Cell(95,6,utf8_decode('Nome: '.$nomeEmpregdo),'TRBL',0,'L');
$pdf->Cell(95,6,utf8_decode('CPF: '.$cpf),'TRBL',0,'L');

$pdf->Ln(6);
$pdf->Cell(95,6,utf8_decode('Cargo: '.$cargo),'TRBL',0,'L');
$pdf->Cell(95,6,utf8_decode('CTPS: '.$ctps),'TRBL',0,'L');

// Define a fonte e o tamnho. 
$pdf->SetFont('arial','B',10);

$pdf->Ln(12);

// Titulo Gride
$pdf->Cell(15,10,utf8_decode('Dia'),'TRBL',0,'C');
$pdf->Cell(20,10,utf8_decode('Entrada'),'TRBL',0,'C');
$pdf->Cell(40,10,utf8_decode('Intervalo'),'TRBL',0,'C');
$pdf->Cell(20,10,utf8_decode('Saída'),'TRBL',0,'C');
$pdf->Cell(20,10,utf8_decode('Hora Extra'),'TRBL',0,'C');
$pdf->Cell(75,10,utf8_decode('Assinatura do Empregado(a)'),'TRBL',0,'C');

// Define a fonte e o tamnho. 
$pdf->SetFont('arial','',10);

$pdf->Ln(10);

// gera a linhas da tabela.
for($i=1; $i<=31; $i++) {
	
	// linhas da tabela.
	$pdf->Cell(15,6,$i,'TRBL',0,'C');
	$pdf->Cell(20,6,'','TRBL',0,'L');
	$pdf->Cell(20,6,'','TRBL',0,'L');
	$pdf->Cell(20,6,'','TRBL',0,'L');
	$pdf->Cell(20,6,'','TRBL',0,'L');
	$pdf->Cell(20,6,'','TRBL',0,'L');
	$pdf->Cell(75,6,'','TRBL',0,'L');
	$pdf->Ln(6);

}

$pdf->Image('https://www.contadoramigo.com.br/images/logo_doc.png',170,285,30,0,'PNG');

// realiza a impressao no navegador
//$pdf->Output();

$nomeFile = "Folha_de_Ponto-".str_replace(' ','_',$nomeEmpregdo).'-'.str_replace('/','_',$mesAno);


// Gera o holerite em pdf.
$pdf->Output($nomeFile.".pdf","D");


?>