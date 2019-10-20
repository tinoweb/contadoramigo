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

// Realiza a requisição do arquivo que retorna o objeto com os dados de pagamento de férias.
require_once('Model/PagamentoFerias/PagamentoFeriasData.php');

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

/** 
 * Classe criada para fazer o controle da geração do holerite.
 */
class GeraHoleriteFuncionario {
	
	private $NomeEmpresa = '';
	private $EnderecoEmpresa = '';
	private $CNPJEmpresa = '';
	private $MesAno = '';
	private $CodigoFuncionario = '';
	private $NomeFuncionario = '';
	private $CBOFuncionario = '';
	private $CTPS = '';
	private $SerieCTPS = '';
	private $PeriodoGozo = '';
	private $PeriodoAbono = '';
	private $DiasAbono = '';
	private $FuncaoFuncionario = '';
	private $Codigo = '';
	private $Descricao = '';
	private $Referencia = '';
	private $Provento = '';
	private $Desconto = '';
	private $TotalVencimento = '';
	private $TotalDescontos = '';
	private $LiquidoReceber = '';
	private $SalarioBase = '';
	private $BaseCalcInss = '';
	private $BaseCalcFgts = '';
	private $FGTSMes = '';
	private $BaseCalcIrrf = '';
	private $FaixaIrrf = '';
	
	public function __construct() {
		
		// Verifica se código do pagamento foi informado.
		if(isset($_GET['feriasId'])) {

			// Instancia de Classes 
			$pagamentoFerias = new PagamentoFeriasData();
			$empresaData = new DadosEmpresaData();
			$funcionarioData = new DadosFuncionariosData();

			// Pega os dados de pagamento do funcionario.
			$dadosFerias = $pagamentoFerias->PegaDadosFeriasCodigo($_GET['feriasId']);
			
			// Verifica se existe dados de pagamento para o funcionario informado.
			if($dadosFerias) {

				// Pega os dados da empresa.
				$dadosEmpregado = $empresaData->GetDataDadosEmpresa($dadosFerias->getEmpresaId());

				// Pega os dados do funcionário.
				$dadosFuncionario = $funcionarioData->PegaDadosFuncionario($dadosFerias->getFuncionarioId());

				// Dados da Empresa.
				$this->NomeEmpresa = $dadosEmpregado->getRazaoSocial();
				$this->EnderecoEmpresa = $dadosEmpregado->getEndereco();
				$this->CNPJEmpresa = $dadosEmpregado->getCNPJ();

				// Dados do Funcionário.
				$this->CodigoFuncionario = $dadosFuncionario->getFuncionarioId();
				$this->NomeFuncionario = $dadosFuncionario->getNome();
				$this->CBOFuncionario = $dadosFuncionario->getCodigoCBO();
				$this->FuncaoFuncionario = $dadosFuncionario->getFuncao();
				$this->CTPS = $dadosFuncionario->getCTPS();
				$this->SerieCTPS = $dadosFuncionario->getSerieCTPS();
				
				// Pega o periodo de ferias
				$periodoFeriasInicial = date('d/m/Y', strtotime($dadosFerias->getDataInicio()));
				$periodoFeriasFinal = date('d/m/Y', strtotime($dadosFerias->getDataFim()));
				
				$this->PeriodoGozo = $periodoFeriasInicial.' a '.$periodoFeriasFinal;
							
				if($dadosFerias->getVendaUmTercoFerias() == 'S') {
					// pega o periodo do abono
					$periodoAbonoInicial = date('d/m/Y', strtotime($dadosFerias->getDataFeriasAbonoInicio()));
					$periodoAbonoFinal =	date('d/m/Y', strtotime($dadosFerias->getDataFeriasAbonoFim()));				
					$this->PeriodoAbono = $periodoAbonoInicial.' a '.$periodoAbonoFinal; 
					$this->DiasAbono = 10;
				}
			
				// Chama o método responsavel por organizar os dados.
				$this->PreparaDadosPagamentoFerias($dadosFerias);
				
				// Chama o método criado para gerar o holerite.
				$this->GeraHolerite();	
			}
		}
	}
	
	// Método criado para gera o holerite.
	private function GeraHolerite() {
		
		// Define o tipo de folha.
		$pdf = new PDF("P","mm","A4");

		$pdf->SetTopMargin(10);
		$pdf->AddPage();

		/** Define as celulas **/

		// SELECIONANDO FONTE PARA O HEADER ESQUERDO
		$pdf->SetFont('arial','B',14); 
		$pdf->SetFillColor(225,225,225);
		// CELULA QUE ENVOLVE O TEXTO 
		$pdf->Cell(188,10,'','TRBL','L',1);

		// Titulo 
		$pdf->SetXY(80,11);
		$pdf->Write(8,utf8_decode("Recibo de Férias")); 
		
		$pdf->Ln(11);
		
		$pdf->SetFont('arial','B',8); 

		$pdf->Cell(188,20,'','TRBL','L',1);

		// Titulo Empresa
		$pdf->SetXY(12,22);
		$pdf->Write(8,utf8_decode("Empresa:"));
		
		// Nome da empresa 
		$pdf->SetXY(26,22);
		$pdf->Write(8,utf8_decode($this->NomeEmpresa));
		
		// Titulo CTPS
		$pdf->SetXY(138,22);
		$pdf->Write(8,utf8_decode("CTPS:"));
		
		// Numero da CTPS
		$pdf->SetXY(148,22);
		$pdf->Write(8,utf8_decode($this->CTPS));
	
		// Titulo Série
		$pdf->SetXY(168,22);
		$pdf->Write(8,utf8_decode("Série:"));
		
		// Numero da Série
		$pdf->SetXY(177,22);
		$pdf->Write(8,utf8_decode($this->SerieCTPS));
	
		// Titulo funcionário.
		$pdf->SetXY(12,26);
		$pdf->Write(8,utf8_decode("Funcionário:")); 

		// Nome do funcionário.
		$pdf->SetXY(30,26);
		$pdf->Write(8,utf8_decode($this->NomeFuncionario)); 

		// Titulo Periodo de gozo
		$pdf->SetXY(12,30);
		$pdf->Write(8,utf8_decode("Período de Gozo:"));

		// periodo de gozo
		$pdf->SetXY(36,30);
		$pdf->Write(8,utf8_decode($this->PeriodoGozo));
		
		// Titulo periodo de abono
		$pdf->SetXY(12,34);
		$pdf->Write(8,utf8_decode("Período de Abono:")); 

		// periodo de abono
		$pdf->SetXY(38,34);
		$pdf->Write(8,utf8_decode($this->PeriodoAbono)); 

		// Titulo salario base
		$pdf->SetXY(138,30);
		$pdf->Write(8,utf8_decode("Base Férias:"));

		// salario base
		$pdf->SetXY(156,30);
		$pdf->Write(8,utf8_decode($this->SalarioBase));		
				
		// Titulo dias de abono
		$pdf->SetXY(138,34);
		$pdf->Write(8,utf8_decode("Dias Abono:")); 

		// dias de abono
		$pdf->SetXY(156,34);
		$pdf->Write(8,utf8_decode($this->DiasAbono)); 
		
		$pdf->Ln(8);
		
		// Campo que recebera valores.
		$pdf->SetFont('arial','b',9);
		$pdf->Cell(80,5,'',1,'L',1);
		$pdf->Cell(36,5,'','TRB','L',1);
		$pdf->Cell(36,5,'','TRB','L',1);
		$pdf->Cell(36,5,'','TRB','L',1);		
		
		// Titulo Descrição
		$pdf->SetXY(40,41);
		$pdf->Write(8,utf8_decode("Descrição")); 

		// Titulo Referência
		$pdf->SetXY(100,41);
		$pdf->Write(8,utf8_decode("Referência")); 

		// Titulo Proventos
		$pdf->SetXY(135,41);
		$pdf->Write(8,utf8_decode("Proventos")); 

		// Titulo Descontos
		$pdf->SetXY(170,41);
		$pdf->Write(8,utf8_decode("Descontos")); 

		$pdf->Ln(6);

		// Campo que recebera valores.
		$pdf->SetFont('arial','',7);
		$pdf->Cell(80,59,'',1,'L',1);
		$pdf->Cell(36,59,'','TRB','L',1);
		$pdf->Cell(36,66,'','TRB','L',1);
		$pdf->Cell(36,66,'','TRB','L',1);

		// Descrição
		$pdf->SetXY(12,48);
		$pdf->Write(8,utf8_decode($this->Descricao[1])); 
		$pdf->SetXY(12,52);
		$pdf->Write(8,utf8_decode($this->Descricao[2])); 
		$pdf->SetXY(12,56);
		$pdf->Write(8,utf8_decode($this->Descricao[3])); 
		$pdf->SetXY(12,60);
		$pdf->Write(8,utf8_decode($this->Descricao[4])); 
		$pdf->SetXY(12,64);
		$pdf->Write(8,utf8_decode($this->Descricao[5])); 
		$pdf->SetXY(12,68);
		$pdf->Write(8,utf8_decode($this->Descricao[6])); 
		$pdf->SetXY(12,72);
		$pdf->Write(8,utf8_decode($this->Descricao[7])); 
		$pdf->SetXY(12,76);
		$pdf->Write(8,utf8_decode($this->Descricao[8])); 
		$pdf->SetXY(12,80);
		$pdf->Write(8,utf8_decode($this->Descricao[9])); 
		$pdf->SetXY(12,84);
		$pdf->Write(8,utf8_decode($this->Descricao[10])); 
		$pdf->SetXY(12,88);
		$pdf->Write(8,utf8_decode($this->Descricao[11]));
		$pdf->SetXY(12,92);
		$pdf->Write(8,utf8_decode($this->Descricao[12]));
		$pdf->SetXY(12,96);
		$pdf->Write(8,utf8_decode($this->Descricao[13]));	

		// Referência
		$pdf->SetXY(95,48);
		$pdf->Write(8,utf8_decode($this->Referencia[1])); 
		$pdf->SetXY(95,52);
		$pdf->Write(8,utf8_decode($this->Referencia[2])); 
		$pdf->SetXY(95,56);
		$pdf->Write(8,utf8_decode($this->Referencia[3])); 
		$pdf->SetXY(95,60);
		$pdf->Write(8,utf8_decode($this->Referencia[4])); 
		$pdf->SetXY(95,64);
		$pdf->Write(8,utf8_decode($this->Referencia[5])); 
		$pdf->SetXY(95,68);
		$pdf->Write(8,utf8_decode($this->Referencia[6])); 
		$pdf->SetXY(95,72);
		$pdf->Write(8,utf8_decode($this->Referencia[7])); 
		$pdf->SetXY(95,76);
		$pdf->Write(8,utf8_decode($this->Referencia[8])); 
		$pdf->SetXY(95,80);
		$pdf->Write(8,utf8_decode($this->Referencia[9])); 
		$pdf->SetXY(95,84);
		$pdf->Write(8,utf8_decode($this->Referencia[10])); 
		$pdf->SetXY(95,88);
		$pdf->Write(8,utf8_decode($this->Referencia[11]));
		$pdf->SetXY(95,92);
		$pdf->Write(8,utf8_decode($this->Referencia[12]));
		$pdf->SetXY(95,96);
		$pdf->Write(8,utf8_decode($this->Referencia[13]));
		
		// Proventos
		$pdf->SetXY(132,48);
		$pdf->Write(8,utf8_decode($this->Provento[1])); 
		$pdf->SetXY(132,52);
		$pdf->Write(8,utf8_decode($this->Provento[2])); 
		$pdf->SetXY(132,56);
		$pdf->Write(8,utf8_decode($this->Provento[3])); 
		$pdf->SetXY(132,60);
		$pdf->Write(8,utf8_decode($this->Provento[4])); 
		$pdf->SetXY(132,64);
		$pdf->Write(8,utf8_decode($this->Provento[5])); 
		$pdf->SetXY(132,68);
		$pdf->Write(8,utf8_decode($this->Provento[6])); 
		$pdf->SetXY(132,72);
		$pdf->Write(8,utf8_decode($this->Provento[7])); 
		$pdf->SetXY(132,76);
		$pdf->Write(8,utf8_decode($this->Provento[8])); 
		$pdf->SetXY(132,80);
		$pdf->Write(8,utf8_decode($this->Provento[9])); 
		$pdf->SetXY(132,84);
		$pdf->Write(8,utf8_decode($this->Provento[10])); 
		$pdf->SetXY(132,88);
		$pdf->Write(8,utf8_decode($this->Provento[11]));
		$pdf->SetXY(132,92);
		$pdf->Write(8,utf8_decode($this->Provento[12]));
		$pdf->SetXY(132,96);
		$pdf->Write(8,utf8_decode($this->Provento[13]));
		
		// Descontos
		$pdf->SetXY(165,48);
		$pdf->Write(8,utf8_decode($this->Desconto[1])); 
		$pdf->SetXY(165,52);
		$pdf->Write(8,utf8_decode($this->Desconto[2])); 
		$pdf->SetXY(165,56);
		$pdf->Write(8,utf8_decode($this->Desconto[3])); 
		$pdf->SetXY(165,60);
		$pdf->Write(8,utf8_decode($this->Desconto[4])); 
		$pdf->SetXY(165,64);
		$pdf->Write(8,utf8_decode($this->Desconto[5])); 
		$pdf->SetXY(165,68);
		$pdf->Write(8,utf8_decode($this->Desconto[6])); 
		$pdf->SetXY(165,72);
		$pdf->Write(8,utf8_decode($this->Desconto[7])); 
		$pdf->SetXY(165,76);
		$pdf->Write(8,utf8_decode($this->Desconto[8])); 
		$pdf->SetXY(165,80);
		$pdf->Write(8,utf8_decode($this->Desconto[9])); 
		$pdf->SetXY(165,84);
		$pdf->Write(8,utf8_decode($this->Desconto[10])); 
		$pdf->SetXY(165,88);
		$pdf->Write(8,utf8_decode($this->Desconto[11]));
		$pdf->SetXY(165,92);
		$pdf->Write(8,utf8_decode($this->Desconto[12]));
		$pdf->SetXY(165,96);
		$pdf->Write(8,utf8_decode($this->Desconto[13]));
		
		// Linhas
		$pdf->Ln(10);

		// Define cabeçalho.
		$pdf->SetFont('arial','',7);
		$pdf->Cell(116,14,utf8_decode('Recebia importância de R$ '.$this->LiquidoReceber.'referente as férias, conforme a demonstração deste recibo.'),1,'L',1);
		$pdf->Cell(36,14,'','TRB','L',1);
		$pdf->Cell(36,14,'','TRB','L',1);
		
		$pdf->SetFont('arial','b',7);
		// Titulo Total dos Vencimentos
		$pdf->SetXY(130,104);
		$pdf->Write(8,utf8_decode("Total dos Vencimentos")); 

		$pdf->SetFont('arial','',7);
		// Total dos Vencimentos
		$pdf->SetXY(130,107);
		$pdf->Write(8,utf8_decode($this->TotalVencimento)); 

		$pdf->SetFont('arial','b',7);
		// Titulo Total dos Descontos
		$pdf->SetXY(165,104);
		$pdf->Write(8,utf8_decode("Total dos Descontos"));

		$pdf->SetFont('arial','',7);
		// Total dos Descontos
		$pdf->SetXY(165,107);
		$pdf->Write(8,utf8_decode($this->TotalDescontos));

		$pdf->SetFont('arial','b',7);
		// Titulo Proventos
		$pdf->SetXY(130,113);
		$pdf->Write(8,utf8_decode("Líquido a Receber->")); 

		$pdf->SetFont('arial','',7);
		// Titulo Descontos
		$pdf->SetXY(165,113);
		$pdf->Write(8,utf8_decode($this->LiquidoReceber)); 

		// Linhas
		$pdf->Ln(7);

		// Define cabeçalho.
		$pdf->SetFont('arial','',8);
		$pdf->Cell(188,13,'',1,'L',1);

		// Campo de data.
		$pdf->SetFont('Arial','',8);
		$pdf->SetXY(12 ,122);
		$pdf->Write(8,'____/____/________');
		
		$pdf->SetFont('Arial','',7);
		$pdf->SetXY(18,126);
		$pdf->Write(8,utf8_decode('DATA'));

		$pdf->SetXY(90,122);			
		$pdf->Write(8,'________________________________________________________________');

		$pdf->SetFont('Arial','',7);
		$pdf->SetXY(110,126);			
		$pdf->Write(8,utf8_decode('ASSINATURA DO FUNCIONÁRIO'));

		// Titulo Salário Base
		$pdf->SetXY(9,131);
		$pdf->Write(8,utf8_decode("1ª VIA - EMPREGADOR"));

		// Titulo Salário Base
		$pdf->SetXY(20,135);
		$pdf->Write(8,utf8_decode("------")); 

		// Titulo Salário Base
		$pdf->SetXY(180,135);
		$pdf->Write(8,utf8_decode("------")); 
		
		$pdf->Ln(7);
		
		// SELECIONANDO FONTE PARA O HEADER ESQUERDO
		$pdf->SetFont('arial','B',14); 
		$pdf->SetFillColor(225,225,225);
		// CELULA QUE ENVOLVE O TEXTO 
		$pdf->Cell(188,10,'','TRBL','L',1);

		// Titulo 
		$pdf->SetXY(80,143);
		$pdf->Write(8,utf8_decode("Recibo de Férias")); 
		
		$pdf->Ln(11);
		
		$pdf->SetFont('arial','B',8); 

		$pdf->Cell(188,20,'','TRBL','L',1);

		// Titulo Empresa
		$pdf->SetXY(12,154);
		$pdf->Write(8,utf8_decode("Empresa:"));
		
		// Nome da empresa 
		$pdf->SetXY(26,154);
		$pdf->Write(8,utf8_decode($this->NomeEmpresa));
		
		// Titulo CTPS
		$pdf->SetXY(138,154);
		$pdf->Write(8,utf8_decode("CTPS:"));
		
		// Numero da CTPS
		$pdf->SetXY(148,154);
		$pdf->Write(8,utf8_decode($this->CTPS));
	
		// Titulo Série
		$pdf->SetXY(168,154);
		$pdf->Write(8,utf8_decode("Série:"));
		
		// Numero da Série
		$pdf->SetXY(177,154);
		$pdf->Write(8,utf8_decode($this->SerieCTPS));
	
		// Titulo funcionário.
		$pdf->SetXY(12,158);
		$pdf->Write(8,utf8_decode("Funcionário:")); 

		// Nome do funcionário.
		$pdf->SetXY(30,158);
		$pdf->Write(8,utf8_decode($this->NomeFuncionario)); 

		// Titulo periodo de gozo
		$pdf->SetXY(12,162);
		$pdf->Write(8,utf8_decode("Período de Gozo:"));

		// periodo de gozo
		$pdf->SetXY(36,162);
		$pdf->Write(8,utf8_decode($this->PeriodoGozo));

		// Titulo periodo de abono
		$pdf->SetXY(12,166);
		$pdf->Write(8,utf8_decode("Período de Abono:")); 

		// Periodo de abono
		$pdf->SetXY(38,166);
		$pdf->Write(8,utf8_decode($this->PeriodoAbono)); 
		
		// Titulo salario base
		$pdf->SetXY(138,162);
		$pdf->Write(8,utf8_decode("Base Férias:"));

		// salario base
		$pdf->SetXY(156,162);
		$pdf->Write(8,utf8_decode($this->SalarioBase));		
		
		// Titulo dias do abono
		$pdf->SetXY(138,166);
		$pdf->Write(8,utf8_decode("Dias Abono:")); 

		// dias do abono
		$pdf->SetXY(156,166);
		$pdf->Write(8,utf8_decode($this->DiasAbono)); 
		
		$pdf->Ln(8);
		
		// Campo que recebera valores.
		$pdf->SetFont('arial','b',9);
		$pdf->Cell(80,5,'',1,'L',1);
		$pdf->Cell(36,5,'','TRB','L',1);
		$pdf->Cell(36,5,'','TRB','L',1);
		$pdf->Cell(36,5,'','TRB','L',1);		
		
		// Titulo Descrição
		$pdf->SetXY(40,173);
		$pdf->Write(8,utf8_decode("Descrição")); 

		// Titulo Referência
		$pdf->SetXY(100,173);
		$pdf->Write(8,utf8_decode("Referência")); 

		// Titulo Proventos
		$pdf->SetXY(135,173);
		$pdf->Write(8,utf8_decode("Proventos")); 

		// Titulo Descontos
		$pdf->SetXY(170,173);
		$pdf->Write(8,utf8_decode("Descontos")); 

		$pdf->Ln(6);

		// Campo que recebera valores.
		$pdf->SetFont('arial','',7);
		$pdf->Cell(80,59,'',1,'L',1);
		$pdf->Cell(36,59,'','TRB','L',1);
		$pdf->Cell(36,66,'','TRB','L',1);
		$pdf->Cell(36,66,'','TRB','L',1);

		// Descrição
		$pdf->SetXY(12,180);
		$pdf->Write(8,utf8_decode($this->Descricao[1])); 
		$pdf->SetXY(12,184);
		$pdf->Write(8,utf8_decode($this->Descricao[2])); 
		$pdf->SetXY(12,188);
		$pdf->Write(8,utf8_decode($this->Descricao[3])); 
		$pdf->SetXY(12,192);
		$pdf->Write(8,utf8_decode($this->Descricao[4])); 
		$pdf->SetXY(12,196);
		$pdf->Write(8,utf8_decode($this->Descricao[5])); 
		$pdf->SetXY(12,200);
		$pdf->Write(8,utf8_decode($this->Descricao[6])); 
		$pdf->SetXY(12,204);
		$pdf->Write(8,utf8_decode($this->Descricao[7])); 
		$pdf->SetXY(12,208);
		$pdf->Write(8,utf8_decode($this->Descricao[8])); 
		$pdf->SetXY(12,212);
		$pdf->Write(8,utf8_decode($this->Descricao[9])); 
		$pdf->SetXY(12,216);
		$pdf->Write(8,utf8_decode($this->Descricao[10])); 
		$pdf->SetXY(12,220);
		$pdf->Write(8,utf8_decode($this->Descricao[11]));
		$pdf->SetXY(12,224);
		$pdf->Write(8,utf8_decode($this->Descricao[12]));
		$pdf->SetXY(12,228);
		$pdf->Write(8,utf8_decode($this->Descricao[13]));	

		// Referência
		$pdf->SetXY(95,180);
		$pdf->Write(8,utf8_decode($this->Referencia[1])); 
		$pdf->SetXY(95,184);
		$pdf->Write(8,utf8_decode($this->Referencia[2])); 
		$pdf->SetXY(95,188);
		$pdf->Write(8,utf8_decode($this->Referencia[3])); 
		$pdf->SetXY(95,192);
		$pdf->Write(8,utf8_decode($this->Referencia[4])); 
		$pdf->SetXY(95,196);
		$pdf->Write(8,utf8_decode($this->Referencia[5])); 
		$pdf->SetXY(95,200);
		$pdf->Write(8,utf8_decode($this->Referencia[6])); 
		$pdf->SetXY(95,204);
		$pdf->Write(8,utf8_decode($this->Referencia[7])); 
		$pdf->SetXY(95,208);
		$pdf->Write(8,utf8_decode($this->Referencia[8])); 
		$pdf->SetXY(95,212);
		$pdf->Write(8,utf8_decode($this->Referencia[9])); 
		$pdf->SetXY(95,216);
		$pdf->Write(8,utf8_decode($this->Referencia[10])); 
		$pdf->SetXY(95,220);
		$pdf->Write(8,utf8_decode($this->Referencia[11]));
		$pdf->SetXY(95,224);
		$pdf->Write(8,utf8_decode($this->Referencia[12]));
		$pdf->SetXY(95,228);
		$pdf->Write(8,utf8_decode($this->Referencia[13]));
		
		// Proventos
		$pdf->SetXY(132,180);
		$pdf->Write(8,utf8_decode($this->Provento[1])); 
		$pdf->SetXY(132,184);
		$pdf->Write(8,utf8_decode($this->Provento[2])); 
		$pdf->SetXY(132,188);
		$pdf->Write(8,utf8_decode($this->Provento[3])); 
		$pdf->SetXY(132,192);
		$pdf->Write(8,utf8_decode($this->Provento[4])); 
		$pdf->SetXY(132,196);
		$pdf->Write(8,utf8_decode($this->Provento[5])); 
		$pdf->SetXY(132,200);
		$pdf->Write(8,utf8_decode($this->Provento[6])); 
		$pdf->SetXY(132,204);
		$pdf->Write(8,utf8_decode($this->Provento[7])); 
		$pdf->SetXY(132,208);
		$pdf->Write(8,utf8_decode($this->Provento[8])); 
		$pdf->SetXY(132,212);
		$pdf->Write(8,utf8_decode($this->Provento[9])); 
		$pdf->SetXY(132,216);
		$pdf->Write(8,utf8_decode($this->Provento[10])); 
		$pdf->SetXY(132,220);
		$pdf->Write(8,utf8_decode($this->Provento[11]));
		$pdf->SetXY(132,224);
		$pdf->Write(8,utf8_decode($this->Provento[12]));
		$pdf->SetXY(132,228);
		$pdf->Write(8,utf8_decode($this->Provento[13]));
		
		// Descontos
		$pdf->SetXY(165,180);
		$pdf->Write(8,utf8_decode($this->Desconto[1])); 
		$pdf->SetXY(165,184);
		$pdf->Write(8,utf8_decode($this->Desconto[2])); 
		$pdf->SetXY(165,188);
		$pdf->Write(8,utf8_decode($this->Desconto[3])); 
		$pdf->SetXY(165,192);
		$pdf->Write(8,utf8_decode($this->Desconto[4])); 
		$pdf->SetXY(165,196);
		$pdf->Write(8,utf8_decode($this->Desconto[5])); 
		$pdf->SetXY(165,200);
		$pdf->Write(8,utf8_decode($this->Desconto[6])); 
		$pdf->SetXY(165,204);
		$pdf->Write(8,utf8_decode($this->Desconto[7])); 
		$pdf->SetXY(165,208);
		$pdf->Write(8,utf8_decode($this->Desconto[8])); 
		$pdf->SetXY(165,212);
		$pdf->Write(8,utf8_decode($this->Desconto[9])); 
		$pdf->SetXY(165,216);
		$pdf->Write(8,utf8_decode($this->Desconto[10])); 
		$pdf->SetXY(165,220);
		$pdf->Write(8,utf8_decode($this->Desconto[11]));
		$pdf->SetXY(165,224);
		$pdf->Write(8,utf8_decode($this->Desconto[12]));
		$pdf->SetXY(165,228);
		$pdf->Write(8,utf8_decode($this->Desconto[13]));
		
		// Linhas
		$pdf->Ln(10);

		// Define cabeçalho.
		$pdf->SetFont('arial','',7);
		$pdf->Cell(116,14,utf8_decode('Recebia importância de R$ '.$this->LiquidoReceber.' referente as férias, conforme a demonstração deste recibo.'),1,'L',1);
		$pdf->Cell(36,14,'','TRB','L',1);
		$pdf->Cell(36,14,'','TRB','L',1);

		$pdf->SetFont('arial','b',7);
		// Titulo Total dos Vencimentos
		$pdf->SetXY(130,236);
		$pdf->Write(8,utf8_decode("Total dos Vencimentos")); 

		$pdf->SetFont('arial','',7);
		// Total dos Vencimentos
		$pdf->SetXY(130,239);
		$pdf->Write(8,utf8_decode($this->TotalVencimento)); 

		$pdf->SetFont('arial','b',7);
		// Titulo Total dos Descontos
		$pdf->SetXY(165,236);
		$pdf->Write(8,utf8_decode("Total dos Descontos"));

		$pdf->SetFont('arial','',7);
		// Total dos Descontos
		$pdf->SetXY(165,239);
		$pdf->Write(8,utf8_decode($this->TotalDescontos));

		$pdf->SetFont('arial','b',7);
		// Titulo Proventos
		$pdf->SetXY(130,245);
		$pdf->Write(8,utf8_decode("Líquido a Receber->")); 

		$pdf->SetFont('arial','',7);
		// Titulo Descontos
		$pdf->SetXY(165,245);
		$pdf->Write(8,utf8_decode($this->LiquidoReceber)); 

		// Linhas
		$pdf->Ln(7);

		// Define cabeçalho.
		$pdf->SetFont('arial','',8);
		$pdf->Cell(188,13,'',1,'L',1);

		// Campo de data.
		$pdf->SetFont('Arial','',8);
		$pdf->SetXY(12 ,254);
		$pdf->Write(8,'____/____/________');
		
		$pdf->SetFont('Arial','',7);
		$pdf->SetXY(18,258);
		$pdf->Write(8,utf8_decode('DATA'));

		$pdf->SetXY(90,254);			
		$pdf->Write(8,'________________________________________________________________');

		$pdf->SetFont('Arial','',7);
		$pdf->SetXY(110,258);			
		$pdf->Write(8,utf8_decode('ASSINATURA DO FUNCIONÁRIO'));

		// Titulo Salário Base
		$pdf->SetXY(9,263);
		$pdf->Write(8,utf8_decode("2ª VIA - EMPREGADO"));

		// realiza a impressao no navegador
		//$pdf->Output();

		// Gera o holerite em pdf.
		$pdf->Output("holerite_".str_replace(' ','-',$nomeFuncionario)."_". date('YmdHis') . ".pdf","D");
	}

	// Método criado para definir os dados do Salário a ser pago.
	private function PreparaDadosPagamentoFerias($dadosPagamentoFerias) {
		
			// Passa o valor do vencimento para uma variável que servira usada para os calculos.
			$totalVencimentoAux = $dadosPagamentoFerias->getValorFerias() + $dadosPagamentoFerias->getValorUmTercoFerias();
		
			// Define o vencimento já formatado como moeda.
			$this->TotalVencimento = number_format(($totalVencimentoAux + $dadosPagamentoFerias->getValorFeriasVendida() + $dadosPagamentoFerias->getValorUmTercoFeriasVendida()),2,",",".");
		
			if($dadosPagamentoFerias->getValorSecundarioINSS() > 0){
				// Pega o total de desconto.
				$totalDescontosAux = $dadosPagamentoFerias->getValorSecundarioINSS() + $dadosPagamentoFerias->getValorIR() + $dadosPagamentoFerias-> getValorPensao();
			} else {
				// Pega o total de desconto.
				$totalDescontosAux = $dadosPagamentoFerias->getValorINSS() + $dadosPagamentoFerias->getValorIR() + $dadosPagamentoFerias-> getValorPensao();
			}

			// Formata total de desconto
			$this->TotalDescontos = number_format($totalDescontosAux,2,",",".");
		
			// Valor liquido a receber.
			$this->LiquidoReceber = number_format(($totalVencimentoAux - $totalDescontosAux),2,",",".");

			// Define o valor do salário do funcionario já formatado como moeda.
			$this->SalarioBase = number_format($dadosPagamentoFerias->getSalarioFuncionario(),2,",",".");

			// Define a quantidade de linas de vencimentos, descontos, etc.
			$this->Descricao = array(1=>'', 2=>'', 3=>'', 4=>'', 5=>'', 6=>'', 7=>'', 8=>'', 9=>'', 10=>'', 11=>'', 12=>'', 13=>'', 14=>'', 15=>'');
		
			$this->Codigo = $this->Referencia = $this->Provento = $this->Desconto = $this->Descricao;
		
			// Define o inicio da linha do holerite.
			$numLinha = 1;
		
			if($dadosPagamentoFerias->getValorFerias()){
				$this->Referencia[$numLinha] = $dadosPagamentoFerias->getDiasFerias().($dadosPagamentoFerias->getDiasFerias() > 1 ? ' Dias' : ' Dia' );
				$this->Descricao[$numLinha] = 'Férias';
				$this->Provento[$numLinha] = number_format($dadosPagamentoFerias->getValorFerias(),2,',','.');
				$numLinha += 1;
			}

			if($dadosPagamentoFerias->getValorUmTercoFerias()){
				$this->Descricao[$numLinha] = 'Ferias 1/3 adcional';
				$this->Provento[$numLinha] = number_format($dadosPagamentoFerias->getValorUmTercoFerias(),2,',','.');
				$numLinha += 1;		
			}

			if($dadosPagamentoFerias->getVendaUmTercoFerias() == 'S'){
				$this->Descricao[$numLinha] = 'Abono Pecuniário';
				$this->Provento[$numLinha] = number_format($dadosPagamentoFerias->getValorFeriasVendida(),2,',','.');
				$numLinha += 1;	

				$this->Descricao[$numLinha] = 'Abono 1/3 adcional';
				$this->Provento[$numLinha] = number_format($dadosPagamentoFerias->getValorUmTercoFeriasVendida(),2,',','.');
				$numLinha += 1;
				
				// pega os dias de abono
				$this->DiasAbono = 10;	
			}

			// O INSS secundario e usado quando o mês que o funcionario ira gozar e quebrado.
			if($dadosPagamentoFerias->getValorSecundarioINSS() > 0){
				$this->Descricao[$numLinha] = 'INSS';	
				$this->Referencia[$numLinha] = ($dadosPagamentoFerias->setReferenciaSecundarioINSS() ? $dadosPagamentoFerias->setReferenciaSecundarioINSS().'%':'');
				$this->Desconto[$numLinha] = number_format($dadosPagamentoFerias->getValorSecundarioINSS(),2,',','.');
				$numLinha += 1;	
			} else {
				if($dadosPagamentoFerias->getValorINSS()){
					$this->Descricao[$numLinha] = 'INSS';	
					$this->Referencia[$numLinha] = $dadosPagamentoFerias->getReferenciaINSS().'%';
					$this->Desconto[$numLinha] = number_format($dadosPagamentoFerias->getValorINSS(),2,',','.');
					$numLinha += 1;	
				}
			}

			if($dadosPagamentoFerias->getValorIR()){
				$this->Descricao[$numLinha] = 'IRFF';	
				$this->Referencia[$numLinha] = $dadosPagamentoFerias->getReferenciaIR().'%';
				$this->Desconto[$numLinha] = number_format($dadosPagamentoFerias->getValorIR(),2,',','.');
				$numLinha += 1;	
			}
		
			if($dadosPagamentoFerias->getValorPensao() > 0){
				$this->Descricao[$numLinha] = 'Pensão';	
				$this->Referencia[$numLinha] = $dadosPagamentoFerias->getReferenciaPensao().'%';
				$this->Desconto[$numLinha] = number_format($dadosPagamentoFerias->getValorPensao(),2,',','.');
				$numLinha += 1;
			}		
	}
}

// Realiza a chamada da classe.
$geraHolerite = new GeraHoleriteFuncionario();

