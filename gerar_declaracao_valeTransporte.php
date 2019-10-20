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
}

// Variáveis para receber os dados do funcionario.
$cep = $bairro = $telefone = $ciadade = $numero = $endereco = "";

// Verifica se foi informado o id do funcionario.
if(isset($_GET['funcionarioId']) && !empty($_GET['funcionarioId'])){
	
	// Instância a classe que manipula os dados do funcinário.
	$dadosFuncionario = new DadosFuncionariosData();
	
	// pega os dados do funcionário.
	$dados = $dadosFuncionario->PegaDadosFuncionario($_GET['funcionarioId']);
	
	// Verifica se existe dados do funcionário.
	if($dados) {
		$cep = $dados->getCEP();
		$bairro = $dados->getBairro();
		$ciadade = $dados->getCidade();
		$endereco = $dados->getEndereco();
		$telefone = '('.$dados->getPrefTelefone().') '.$dados->getTelefone();
	}
}
	
// Define o tipo de folha.
$pdf = new GeraDocumentoPDF("P","mm","A4");


$pdf->SetTopMargin(10);
$pdf->AddPage();
 
/** Define as celulas **/

// DEFINE A FONTE E O TAMANHO DELA PARA O TITULO.
$pdf->SetFont('arial','B',14); 

// Titulo Nome
$pdf->SetXY(75,10);
$pdf->Write(9,utf8_decode("VALE TRANSPORTE")); 

// Define a fonte e o tamnho. 
$pdf->SetFont('arial','B',10); 

// Titulo Nome
$pdf->SetXY(40,20);
$pdf->Write(5,utf8_decode("Declaração / Termo de Compromisso (Lei nº 7619 de 30 de Setembro de 1987)")); 

// Define a fonte e o tamnho. 
$pdf->SetFont('arial','',10); 

/** Primeira linha **/

// Titulo Nome
$pdf->SetXY(9,35);
$pdf->Write(9,utf8_decode("ENDEREÇO: ".$endereco)); 

// Titulo cep
$pdf->SetXY(100,35);
$pdf->Write(9,utf8_decode("CEP : ".$cep)); 

/** Segunda linha **/

// Titulo bairro
$pdf->SetXY(9,45);
$pdf->Write(9,utf8_decode("BAIRRO: ".$bairro)); 

// Titulo cidade
$pdf->SetXY(100,45);
$pdf->Write(9,utf8_decode("CIDADE: ".$ciadade)); 

// Titulo telefone
$pdf->SetXY(150,45);
$pdf->Write(9,utf8_decode("TELEFONE: ".$telefone)); 

/** Terceira linha **/

// Titulo telefone
$pdf->SetXY(9,55);
$pdf->Write(9,utf8_decode("OPTANTE   (  )")); 


// Titulo telefone
$pdf->SetXY(45,55);
$pdf->Write(9,utf8_decode("NÃO OPTANTE   (   )")); 

$pdf->Ln(11);

/** Quarta linha **/
$pdf->SetFillColor(225,225,225);

// Define a fonte e o tamnho. 
$pdf->SetFont('arial','B',10); 

// CELULA QUE ENVOLVE O TEXTO 
$pdf->Cell(40,6,utf8_decode("TIPO"),'TRBL',0,'C');
$pdf->Cell(90,6,utf8_decode("NOME DA EMPRESA E N.º DA LINHA"),'TRBL',0,'C');
$pdf->Cell(25,6,utf8_decode("QUANT. DIA"),'TRBL',0,'C');
$pdf->Cell(25,6,utf8_decode("TARIFA R$"),'TRBL',0,'C');

// Define a fonte e o tamnho. 
$pdf->SetFont('arial','',10); 

$pdf->Ln(6);

// CELULA QUE ENVOLVE O TEXTO 
$pdf->Cell(40,6,utf8_decode('ÔNIBUS MUNICIPAL'),'TRBL',0,'L');
$pdf->Cell(65,6,'','TRBL',0,'L');
$pdf->Cell(25,6,'','TRBL',0,'L');
$pdf->Cell(25,6,'','TRBL',0,'L');
$pdf->Cell(25,6,'','TRBL',0,'L');

$pdf->Ln(6);

// CELULA QUE ENVOLVE O TEXTO 
$pdf->Cell(40,6,utf8_decode('ÔNIBUS MUNICIPAL'),'TRBL',0,'L');
$pdf->Cell(65,6,'','TRBL',0,'L');
$pdf->Cell(25,6,'','TRBL',0,'L');
$pdf->Cell(25,6,'','TRBL',0,'L');
$pdf->Cell(25,6,'','TRBL',0,'L');

$pdf->Ln(6);

// CELULA QUE ENVOLVE O TEXTO 
$pdf->Cell(40,6,utf8_decode('ÔNIBUS E METRÔ'),'TRBL',0,'L');
$pdf->Cell(65,6,'','TRBL',0,'L');
$pdf->Cell(25,6,'','TRBL',0,'L');
$pdf->Cell(25,6,'','TRBL',0,'L');
$pdf->Cell(25,6,'','TRBL',0,'L');

$pdf->Ln(6);

// CELULA QUE ENVOLVE O TEXTO 
$pdf->Cell(40,6,utf8_decode('INTEGRAÇÃO ÔNIBUS'),'TRBL',0,'L');
$pdf->Cell(65,6,'','TRBL',0,'L');
$pdf->Cell(25,6,'','TRBL',0,'L');
$pdf->Cell(25,6,'','TRBL',0,'L');
$pdf->Cell(25,6,'','TRBL',0,'L');

$pdf->Ln(6);

// CELULA QUE ENVOLVE O TEXTO 
$pdf->Cell(40,6,'','TRBL',0,'L');
$pdf->Cell(65,6,'','TRBL',0,'L');
$pdf->Cell(25,6,'','TRBL',0,'L');
$pdf->Cell(25,6,'','TRBL',0,'L');
$pdf->Cell(25,6,'','TRBL',0,'L');

$pdf->Ln(6);

// CELULA QUE ENVOLVE O TEXTO 
$pdf->Cell(40,6,'','TRBL',0,'L');
$pdf->Cell(65,6,'','TRBL',0,'L');
$pdf->Cell(25,6,'','TRBL',0,'L');
$pdf->Cell(25,6,'','TRBL',0,'L');
$pdf->Cell(25,6,'','TRBL',0,'L');

// Define a fonte e o tamnho. 
$pdf->SetFont('arial','',10); 

$pdf->Ln(16);

$pdf->SetXY(10,120);
$pdf->Write(10,utf8_decode("Valor gasto por dia em transporte :........................................ R$")); 

$pdf->SetXY(10,125);
$pdf->Write(10,utf8_decode("Número do Bilhete único:")); 

$pdf->SetXY(10,140);
$pdf->Write(5,utf8_decode("Declaro pela presente, a efetiva utilização dos meios de transporte acima indicados: exclusivamente para o percurso residência – trabalho e trabalho – residência, sob pena de caracterização de falta grave (conforme Decreto n.º 95.247 art. 7 parágrafo 3) autorizando, desde já o desconto em folha de pagamento. ")); 

$pdf->SetXY(100,160);
$pdf->Write(10,utf8_decode("São Paulo/SP, _____ de ________________ de _______."));

$pdf->SetXY(10,195);
$pdf->Write(10,utf8_decode("_________________________________"));		
$pdf->SetXY(20,200);
$pdf->Write(10,utf8_decode("assinatura do (a) optante (a)"));

$pdf->SetXY(120,195);
$pdf->Write(10,utf8_decode("_______________________________"));
$pdf->SetXY(130,200);
$pdf->Write(10,utf8_decode("Responsável pelo menor"));
						  
$pdf->SetXY(10,215);
$pdf->Write(10,utf8_decode("_________________________________"));
$pdf->SetXY(25,220);
$pdf->Write(10,utf8_decode("assinatura da Empresa"));


$pdf->Image('https://www.contadoramigo.com.br/images/logo_doc.png',170,285,30,0,'PNG');
 
// realiza a impressao no navegador
//$pdf->Output();

// Gera o holerite em pdf.
$pdf->Output("Declaracao_Vale_Transporte_". date('YmdHis') . ".pdf","D");


?>