<?php
/**
 *	Autor: Átano de Farias Jacinto.
 *	Data: 11/07/2017
 */

// Classe criada para manipular os dados da aliquotas de impostos.
class ImpostosAliquotas {
	
	
	// Método criado para pegar os cnae das empresa com o fatorR.
	public function PegaCNAEComFatorR($empresaId) {	
	
		$rows = false;
	
		$query = 'SELECT * FROM cnae_2018 WHERE Fator_R = 1 AND cnae in (SELECT cnae FROM dados_da_empresa_codigos WHERE id = "'.$empresaId.'");';		
		
		$consulta = mysql_query($query);
		
		if( mysql_num_rows($consulta) > 0 ){
			
			while($array = mysql_fetch_array($consulta)){
				$rows[] = $array ;
			}
		}
		
		return $rows;	
	}
	
	// Método criado para pegar os cnae das empresa.
	public function PegaCNAE($empresaId) {	
	
		$rows = false;
	
		$query = 'SELECT * FROM cnae_2018 WHERE cnae in (SELECT cnae FROM dados_da_empresa_codigos WHERE id = "'.$empresaId.'");';		
		
		$consulta = mysql_query($query);
		
		if( mysql_num_rows($consulta) > 0 ){
			
			while($array = mysql_fetch_array($consulta)){
				$rows[] = $array ;
			}
		}
		
		return $rows;	
	}	
	
	
	// Anexo I
	public function TabelaAnexo1($receitaBruta12Meses){
		
		$rows = false;
	
		$query = 'SELECT * FROM anexo1_2018_tabela1 WHERE ReceitaBrutaMin <= '.$receitaBruta12Meses.' AND ReceitaBrutaMax >= '.$receitaBruta12Meses;		
		
		$consulta = mysql_query($query);
		
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
		
		return $rows;
	}
	
	// Anexo I
	public function PegaReparticaoTributosAnexo1($faixa){
		
		$rows = false;
	
		$query = 'SELECT * FROM anexo1_2018_tabela2 WHERE Faixa LIKE "'.$faixa.'"';		
		
		$consulta = mysql_query($query);
		
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
		
		return $rows;		
	}
		
	// Anexo II
	public function TabelaAnexo2($receitaBruta12Meses){
		
		$rows = false;
	
		$query = 'SELECT * FROM anexo2_2018_tabela1 WHERE ReceitaBrutaMin <= '.$receitaBruta12Meses.' AND ReceitaBrutaMax >= '.$receitaBruta12Meses;		
		
		$consulta = mysql_query($query);
		
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
		
		return $rows;
	}
	
	// Anexo II
	public function PegaReparticaoTributosAnexo2($faixa){
		
		$rows = false;
	
		$query = 'SELECT * FROM anexo2_2018_tabela2 WHERE Faixa LIKE "'.$faixa.'"';		
		
		$consulta = mysql_query($query);
		
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
		
		return $rows;		
	}		
	
	// Anexo III
	public function TabelaAnexo3($receitaBruta12Meses){
		
		$rows = false;
	
		$query = 'SELECT * FROM anexo3_2018_tabela1 WHERE ReceitaBrutaMin <= '.$receitaBruta12Meses.' AND ReceitaBrutaMax >= '.$receitaBruta12Meses;		
		
		$consulta = mysql_query($query);
		
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
		
		return $rows;
	}
	
	// Anexo III
	public function PegaReparticaoTributosAnexo3($faixa){
		
		$rows = false;
	
		$query = 'SELECT * FROM anexo3_2018_tabela2 WHERE Faixa LIKE "'.$faixa.'"';		
		
		$consulta = mysql_query($query);
		
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
		
		return $rows;		
	}	
	
	// Anexo IV
	public function TabelaAnexo4($receitaBruta12Meses){
		
		$rows = false;
	
		$query = 'SELECT * FROM anexo4_2018_tabela1 WHERE ReceitaBrutaMin <= '.$receitaBruta12Meses.' AND ReceitaBrutaMax >= '.$receitaBruta12Meses;		
		
		$consulta = mysql_query($query);
		
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
		
		return $rows;
	}
	
	// Anexo IV
	public function PegaReparticaoTributosAnexo4($faixa){
		
		$rows = false;
	
		$query = 'SELECT * FROM anexo4_2018_tabela2 WHERE Faixa LIKE "'.$faixa.'"';		
		
		$consulta = mysql_query($query);
		
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
		
		return $rows;		
	}	
	
	// Anexo V
	public function TabelaAnexo5($receitaBruta12Meses){
		
		$rows = false;
	
		$query = 'SELECT * FROM anexo5_2018_tabela1 WHERE ReceitaBrutaMin <= '.$receitaBruta12Meses.' AND ReceitaBrutaMax >= '.$receitaBruta12Meses;		
		
		$consulta = mysql_query($query);
		
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
		
		return $rows;
	}
	
	// Anexo V
	public function PegaReparticaoTributosAnexo5($faixa){
		
		$rows = false;
	
		$query = 'SELECT * FROM anexo5_2018_tabela2 WHERE Faixa LIKE "'.$faixa.'" ';		
		
		$consulta = mysql_query($query);
		
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
		
		return $rows;		
	}
}