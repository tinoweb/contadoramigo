<?php
/**
 *	Autor: Átano de Farias Jacinto.
 *	Data: 14/07/2017
 */
require_once('DataBaseMySQL/TabelaRetencaoIR.php');
require_once('Model/TabelaIR/vo/TabelaIRVo.php');

class TabelaIRData {
	
	public function PegaIRPorAno($ano) {
		
		$objectIR = false;
		
		$tabelaIR = new TabelaRetencaoIR();
		
		$dadosIR = $tabelaIR->PegaDadosRetencaoIR($ano);
		
		if($dadosIR) {

			$objectIR = new TabelaIRVo();

			$objectIR->setAnoCalendario($dadosIR['ano_calendario']); 
			$objectIR->setValorBruto1($dadosIR['ValorBruto1']);
			$objectIR->setValorBruto2($dadosIR['ValorBruto2']);
			$objectIR->setValorBruto3($dadosIR['ValorBruto3']);
			$objectIR->setValorBruto4($dadosIR['ValorBruto4']);
			$objectIR->setAliquota1($dadosIR['Aliquota1']);
			$objectIR->setAliquota2($dadosIR['Aliquota2']);
			$objectIR->setAliquota3($dadosIR['Aliquota3']);
			$objectIR->setAliquota4($dadosIR['Aliquota4']);
			$objectIR->setAliquota5($dadosIR['Aliquota5']);
			$objectIR->setDesconto1($dadosIR['Desconto1']);
			$objectIR->setDesconto2($dadosIR['Desconto2']);
			$objectIR->setDesconto3($dadosIR['Desconto3']);
			$objectIR->setDesconto4($dadosIR['Desconto4']);
			$objectIR->setDesconto5($dadosIR['Desconto5']);
			$objectIR->setDescontoDependentes($dadosIR['Desconto_Ir_Dependentes']);
		}
		
		return $objectIR;
	}	
}