<?php
/**
 *	Autor: Átano de Farias Jacinto.
 *	Data: 11/07/2017
 */
$requestURI = explode("/", $_SERVER['REQUEST_URI']);

if($requestURI[1] == 'contador') {
	require_once('../DataBaseMySQL/TabelaINSS.php');
	require_once('../Model/TabelaINSS/vo/TabelaINSSVo.php');
} elseif($requestURI[1] == 'admin') {	
	require_once('../DataBaseMySQL/TabelaINSS.php');
	require_once('../Model/TabelaINSS/vo/TabelaINSSVo.php');
} else {
	require_once('DataBaseMySQL/TabelaINSS.php');
	require_once('Model/TabelaINSS/vo/TabelaINSSVo.php');
}

class TabelaINSSData {
	
	public function PegaINSSPorAno($ano) {
		
		$out = false;
		
		$tabelaINSS = new TabelaINSS();
		
		$listaPorcentagemINSS = $tabelaINSS->PegaTabelaINSS($ano);
		
		if($listaPorcentagemINSS) {
			
			foreach($listaPorcentagemINSS as $val) {
				
				$objetoInss = new TabelaINSSVo();
				
				$objetoInss->setInssId($val['inssId']);
				$objetoInss->setAno($val['ano']);
				$objetoInss->setValor($val['valor']);
				$objetoInss->setPorcentagem($val['porcentagem']);
				
				$out[] = $objetoInss;
			}
		}
		
		return $out;
	}
	
	public function PreparaDadosGravaINSS($ano, $valor, $porcentagem) {
		
		$tabelaINSS = new TabelaINSS();

		$objetoInss = new TabelaINSSVo();

		$objetoInss->setAno($ano);
		$objetoInss->setValor($valor);
		$objetoInss->setPorcentagem($porcentagem);
		
		return $tabelaINSS->GravaDadosINSS($objetoInss);
	}
	
	public function PreparaDadosAlteraINSS($inssId, $valor, $porcentagem) {
		
		$tabelaINSS = new TabelaINSS();

		$objetoInss = new TabelaINSSVo();

		$objetoInss->setInssId($inssId);	
		$objetoInss->setValor($valor);
		$objetoInss->setPorcentagem($porcentagem);
		
		return $tabelaINSS->AtualizaDadosINSS($objetoInss);
	}
	
	public function PegaMenorAno() {
		
		$out = '';
		
		$tabelaINSS = new TabelaINSS();
		
		$out = $tabelaINSS->PegaMenorAno();
		
		return $out['ano']; 
	}
	
	
	
}