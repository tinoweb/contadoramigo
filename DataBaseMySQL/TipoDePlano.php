<?php
/**
 * Autor: Átano de Farias Jacinto
 * Data: 21/01/2017 
 */
 
require_once "conect.php";
 
class TipoDePlano {
	
	// Método utilizado pra altera o tipo de plano e a assinatura em dados da cobrança.
	public function alteraTipoPlanoAssinatura($tipoPlano, $contadorId, $idUser, $plano) {
		
		$sql = "UPDATE dados_cobranca SET tipo_plano='".$tipoPlano."',plano='".$plano."',contadorId='".$contadorId."' WHERE id='".$idUser."'";
		$resultado = mysql_query($sql)
		or die (mysql_error());
		
	} 
	
	// Método utilizado pra altera o tipo de plano em dados da cobrança.
	public function alteraTipoPlano($tipoPlano, $contadorId, $idUser) {
		
		$sql = "UPDATE dados_cobranca SET tipo_plano='".$tipoPlano."', 	contadorId='".$contadorId."' WHERE id='".$idUser."'";
		$resultado = mysql_query($sql)
		or die (mysql_error());
		
	}
	
	// Pega os dados de cobranca caso o tipo da conta seja premio.
	public function UsuarioEPremio($id) {
		
		$rows = '';
		$query = ' SELECT * FROM `dados_cobranca` WHERE id = '.$id.' AND tipo_plano = P; ';
		$consulta = mysql_query($query);		
	
		if( mysql_num_rows($consulta) > 0){
			$rows = mysql_fetch_array($consulta);	
		}
			
		return $rows;
	}	
		
}