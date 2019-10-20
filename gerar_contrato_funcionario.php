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

// Realiza a requisição do arquivo que retorna os dados da empresa.
require_once('Model/DadosEmpresa/DadosEmpresaData.php');

// Realiza a requisição do arquivo que retorna os dados do socio.
require_once('Model/DadosSocio/DadosSocioData.php');

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

	function PegaMesNome($n) {

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
		
		return $meses[$n];
	}
}

$nome = '';
$estadoCivil = ''; 
$cargo = '';
$cpf = '';
$rg = '';
$endereco = '';
$bairro = '';
$cidade = '';
$estado = '';

// Verifica se foi informado o id do funcionario.
if(isset($_GET['funcionarioId']) && !empty($_GET['funcionarioId'])){
	
	// Instância a classe que manipula os dados do funcinário.
	$dadosFuncionario = new DadosFuncionariosData();
	
	// pega os dados do funcionário.
	$dados = $dadosFuncionario->PegaDadosFuncionario($_GET['funcionarioId']);
	
	// Verifica se existe dados do funcionário.
	if($dados) {
		$nome = $dados->getNome();
		$estadoCivil = $dados->getEstadoCivil();
		$cargo = $dados->getFuncao();
		$cpf = $dados->getCPF();
		$rg = $dados->getRG();
		$ctps = $dados->getCTPS();
		$endereco = $dados->getEndereco();
		$bairro = $dados->getBairro();
		$cidade = $dados->getCidade();
		$estado = $dados->getEstado();
		$salario = number_format($dados->getValorSalario(),2,",",".");
		$salarioExtenso = GExtenso::moeda(number_format(str_replace(",",".", $dados->getValorSalario()),2,"",""));
		
		$jornadaTrabalho = $dados->getJornadaTrabalhoDiaria();
		$jornadaExtenso = GExtenso::numero($jornadaTrabalho);
		
		// Formata a horario de 00:00:00 para 00:00
		$inicioJornada = date('H:i',strtotime($dados->getInicioJornada()));
		$fimJornada = date('H:i',strtotime($dados->getFimJornada()));
		$inicioIntervalo = date('H:i',strtotime($dados->getInicioIntervalo()));
		$fimIntervalo = date('H:i',strtotime($dados->getFimIntervalo()));

		// Verifica se funcionario trabalha ao sabados.
		if($dados->getTrabalhoSabado() == 'N') {
			$expediente = "não havendo expediente aos sábados e domingos.";
		} else {
			$expediente = "não havendo expediente aos domingos.";
		}
		
		
	}
}

// Verifica se foi informado o id do funcionario.
if(isset($_GET['empresaId']) && !empty($_GET['empresaId'])){
	
	$empresaId = $_GET['empresaId'];
	
	// Instância a classe que manipula os dados da empresa. 	
	$dadosEmpresa = new DadosEmpresaData();
	$dadosEmp = $dadosEmpresa->GetDataDadosEmpresa($empresaId);
	
	$nomeFantasia = $dadosEmp->getNomeFantasia();
	$razaoSocial = $dadosEmp->getRazaoSocial();
	$empCNPJ = $dadosEmp->getCNPJ();
	$empEndereco = $dadosEmp->getEndereco();
	$empNumero = $dadosEmp->getNumero();
	$empComplemento = $dadosEmp->getComplemento();
	$empBairro = $dadosEmp->getBairro();
	$empCidade = $dadosEmp->getCidade();
	$empEstado = $dadosEmp->getEstado();
	
	
	// Instância a classe que manipula os dados da socio.
	$dataSocio = new DataSocioData(); 
	$dadosSocio = $dataSocio->PegaDadosSocioResponsavel($empresaId);	
	
	$socioNome = $dadosSocio->getNome();
	$socioRG = $dadosSocio->getCPF();
	$socioCPF = $dadosSocio->getRG();
}


//echo '<pre>';
//	print_r($dados);
//echo '</pre>';
//
//die();


// Define o tipo de folha.
$pdf = new GeraDocumentoPDF("P","mm","A4");


$pdf->SetTopMargin(10);

// Página - 1
$pdf->AddPage();
 
/** Define as celulas **/

// DEFINE A FONTE E O TAMANHO DELA PARA O TITULO.
$pdf->SetFont('arial','B',14); 

$pdf->Ln(3);
$pdf->MultiCell(0,4,utf8_decode("Contrato Individual de Trabalho por Prazo Indeterminado"),0,'C', false);

// Define a fonte e o tamnho. 
$pdf->SetFont('arial','',9); 
		   
$pdf->Ln(6);		   
$pdf->MultiCell(0,4,utf8_decode("Pelo presente instrumento e na melhor forma de direito, as partes:"),0,'L', false);

$pdf->Ln(3);
		   
$pdf->MultiCell(0,4,utf8_decode("1. ".$razaoSocial.", inscrita no CNPJ/MF sob nº ".$empCNPJ.", com sede nesta Capital, na ".$empEndereco.", ".$empNumero." ".$empComplemento.", ".$empBairro.", ".$empCidade." - ".$empEstado."  aqui simplesmente denominado EMPREGADOR, neste ato representada pelo seu sócio e diretor, ".$socioNome.", portador da Cédula de Identidade nº ".$socioRG." e inscrito no CPF/MF sob nº ".$socioCPF.";"),0,'L', false);

$pdf->Ln(3);		   
		   
$pdf->MultiCell(0,4,utf8_decode("2. ".$nome.", ".$estadoCivil.", ".$cargo.", titular do CPF nº ".$cpf.", RG ".$rg.", CTPS ".$ctps.", residente à ".$endereco." - ".$bairro.", ".$cidade.", ".$estado.", doravante designado EMPREGADO ;"),0,'L', false);

$pdf->Ln(3);		   
		   
$pdf->MultiCell(0,4,utf8_decode("Firmam o presente CONTRATO INDIVIDUAL DE TRABALHO , nos termos da Lei e, seguintes cláusulas assim pactuadas:"),0,'L', false);	

// Define a fonte e o tamnho. 
$pdf->SetFont('arial','B',9); 

$pdf->Ln(3);		      
$pdf->MultiCell(0,4,utf8_decode("Cláusula 1ª - Da Função"),0,'L', false);	

// Define a fonte e o tamnho. 
$pdf->SetFont('arial','',9); 

$pdf->Ln(1);		      
$pdf->MultiCell(0,4,utf8_decode("O EMPREGADO, obriga-se a prestar seus serviços no quadro de funcionários da EMPREGADORA para exercer a função de Programador, mediante a remuneração de R$ ".$salario." (".$salarioExtenso."), a ser paga mensalmente ao empregado, até o 5º (quinto) dia útil do mês."),0,'L', false);

$pdf->Ln(3);		      
$pdf->MultiCell(0,4,utf8_decode("Ressalva-se o EMPREGADOR, no direito de proceder à transferência do empregado para outro cargo ou função que entenda que este demonstre melhor capacidade de adaptação desde que compatível com sua condição pessoal."),0,'L', false);

// Define a fonte e o tamnho. 
$pdf->SetFont('arial','B',9); 

$pdf->Ln(3);		      
$pdf->MultiCell(0,4,utf8_decode("Cláusula 2ª - Do Horário"),0,'L', false);

// Define a fonte e o tamnho. 
$pdf->SetFont('arial','',9); 

$pdf->Ln(1);
$pdf->MultiCell(0,4,utf8_decode("O EMPREGADO cumprirá uma jornada de trabalho de ".$jornadaTrabalho." (".$jornadaExtenso.") horas diárias, iniciando suas atividades as ".$inicioJornada." e encerrando às ".$fimJornada.", com intervalo de uma hora para almoço, ".$expediente." 
Se houver horas extras, estas serão pagas na forma da lei ou serão compensadas com repouso correspondente."),0,'L', false);
		 
// Define a fonte e o tamnho. 
$pdf->SetFont('arial','B',9); 

$pdf->Ln(3);
$pdf->MultiCell(0,4,utf8_decode("Cláusula 3ª - Da Transferência"),0,'L', false);

// Define a fonte e o tamnho. 
$pdf->SetFont('arial','',9); 

$pdf->Ln(1);
$pdf->MultiCell(0,4,utf8_decode("O EMPREGADO está ciente e concorda que a prestação de seus serviços se dará tanto na localidade de celebração do Contrato de Trabalho, como em qualquer outra Cidade, Capital ou Vila do Território Nacional, nos termos do que dispõe o § 1° do artigo 469, da Consolidação das Leis do Trabalho."),0,'L', false);

// Define a fonte e o tamnho. 
$pdf->SetFont('arial','B',9); 

$pdf->Ln(3);
$pdf->MultiCell(0,4,utf8_decode("Cláusula 4ª- Dos descontos"),0,'L', false);

// Define a fonte e o tamnho. 
$pdf->SetFont('arial','',9); 

$pdf->Ln(1);
$pdf->MultiCell(0,4,utf8_decode("O EMPREGADO autoriza o desconto em seu salário das importâncias que lhe forem adiantadas pelo empregador, bem como aos descontos legais, sobretudo, os previdenciários, de alimentação, habitação e vale transporte.
Sempre que causar algum prejuízo, resultante de alguma conduta dolosa ou culposa, ficará obrigada o EMPREGADO a ressarcir ao EMPREGADOR por todos os danos causados."),0,'L', false);

// Define a fonte e o tamnho. 
$pdf->SetFont('arial','B',9); 

$pdf->Ln(3);
$pdf->MultiCell(0,4,utf8_decode("Cláusula 5ª - Das Disposições Especiais"),0,'L', false);

// Define a fonte e o tamnho. 
$pdf->SetFont('arial','',9); 

$pdf->Ln(1);
$pdf->MultiCell(0,4,utf8_decode("O EMPREGADO compromete-se também, a respeitar o regulamento da empresa, mantendo conduta irrepreensível no ambiente de trabalho, constituindo motivos para imediata dispensa do empregado, além dos previstos em lei, o desacato moral ou agressão física ao EMPREGADOR, ao administrador ou a pessoa de seus respectivos companheiros de trabalho, a embriaguês no serviço ou briga no local de trabalho."),0,'L', false);

// /* Página - 2 */
//$pdf->AddPage();

$pdf->Ln(3);
$pdf->MultiCell(0,4,utf8_decode("E por estarem assim contratados, nos termos de seus respectivos interesses, mandaram as partes lavrar o presente instrumento que assinam na presença de 02 (duas) testemunhas, para as finalidades de direito"),0,'L', false); 
                                              
$pdf->Ln(3);
$pdf->MultiCell(0,4,utf8_decode("São Pulo, ".date('d').", de ".$pdf->PegaMesNome(date('n'))." de ".date('Y')."."),0,'R', false);
 

$pdf->Ln(20);
$pdf->Cell(95,4,utf8_decode('___________________________________________'),'',0,'C');
$pdf->Cell(0,4,utf8_decode('___________________________________________'),'',0,'C');

$pdf->Ln(6); 
$pdf->Cell(95,4,utf8_decode('Empregador'),'',0,'C');
$pdf->Cell(0,4,utf8_decode('Empregado'),'',0,'C');

$pdf->Ln(12);
$pdf->Cell(95,4,utf8_decode('___________________________________________'),'',0,'C');
$pdf->Cell(0,4,utf8_decode('___________________________________________'),'',0,'C');

$pdf->Ln(6); 
$pdf->Cell(95,4,utf8_decode('Testemunha'),'',0,'C');
$pdf->Cell(0,4,utf8_decode('Testemunha'),'',0,'C');


$pdf->Image('https://www.contadoramigo.com.br/images/logo_doc.png',170,285,30,0,'PNG');

// realiza a impressao no navegador
//$pdf->Output();

$nomefile = 'Contrato-'.str_replace(' ','_',$nome).'-';

// Gera o holerite em pdf.
$pdf->Output($nomefile . date('YmdHis') . ".pdf","D");

?>