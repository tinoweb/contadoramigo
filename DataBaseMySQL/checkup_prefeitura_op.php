<?php 

class CheckupPrefeitura {
	
	public function VerificaCidade($idEmpresa){

		$rows = '';
		
		$query =" SELECT e.cidade FROM dados_da_empresa e WHERE id =".$idEmpresa.";";
		
		$consulta = mysql_query($query);
		
		if(mysql_num_rows($consulta) > 0){
			$rows = mysql_fetch_array($consulta);
		}
		
		return $rows['cidade'];
	}
}