<?php
/**
 * Autor: Átano de Farias
 * Data: 15/03/2017 
 */
class DadosCobranca {

	// Pega os dados de cobranca.
	public function pegaDadosCobranca($id) {
		
		$rows = '';
		
		$query = ' SELECT * FROM `dados_cobranca` WHERE id = '.$id.'; ';
	
		$consulta = mysql_query($query);		
		
		if( mysql_num_rows($consulta) > 0 ){
			
			$rows = mysql_fetch_array($consulta);
			
		}
			
		return $rows;	
	}
	
	// Pega Todos usuarios.
	public function pegaDadosLogin($id) {
	
		$rows = '';
		
		$query = ' SELECT c.id,
					c.assinante,
					c.pref_telefone,
					c.telefone,
					c.sacado,
					c.documento,
					c.endereco,
					c.numero,
					c.complemento,
					c.bairro,
					c.cep,
					c.cidade,
					c.uf,
					c.tipo,
					c.forma_pagameto,
					l.nome,
					l.email 
					FROM dados_cobranca c, login l
					WHERE c.id = l.id 
					AND l.id = '.$id.'; ';
	
		$consulta = mysql_query($query);		
		
		if( mysql_num_rows($consulta) > 0 ){
			
			$rows = mysql_fetch_array($consulta);
			
		}
			
		return $rows;	
	}
	
	
	// Pega os dados de cobranca do usando avulso pelo documento.
	public function VerificaDocumetoServicoAvulsoExiste($documento) {
		
		$rows = '';
		
		$query = " SELECT c.id FROM login l, dados_cobranca c 
					WHERE c.documento = '".$documento."' 
					AND l.status = 'servico-avulso'
					AND c.id = l.id; ";
		
		$consulta = mysql_query($query);		
		
		if( mysql_num_rows($consulta) > 0 ){
			
			$rows = mysql_fetch_array($consulta);
			
		}
		
		return $rows;	
	}
	
	// Pega os dados de cobranca do usando avulso pelo email.
	public function VerificaEmailServicoAvulsoExiste($email) {
		
		$rows = '';
		
		$query = " SELECT c.id FROM login l, dados_cobranca c 
					WHERE l.email = '".$email."' 
					AND l.status = 'servico-avulso'
					AND c.id = l.id; ";
		
		$consulta = mysql_query($query);		
		
		if( mysql_num_rows($consulta) > 0 ){
			
			$rows = mysql_fetch_array($consulta);
			
		}
		
		return $rows;	
	}
	
	// Atualiza os daos de cobranca
	public function AtualizaDadosCobranca($id_user, $sacado, $documento, $endereco, $bairro, $cep, $cidade, $uf, $tipo, $auxiliar = '') {
		
		$update = "UPDATE dados_cobranca SET sacado = '".$sacado."',
					documento = '".$documento."',
					endereco = '".$endereco."',
					bairro = '".$bairro."',
					cep = '".$cep."',
					cidade = '".$cidade."',
					uf = '".$uf."',
					tipo = '".$tipo."' 
					".$auxiliar."
				WHERE id = '".$id_user."';";
				
		mysql_query($update) or die(mysql_error());				
	}
	
	// Atualiza os daos de cobranca
	public function AtualizaDadosCobranca2($id_user, $sacado, $prefixo, $telefone, $documento, $endereco, $bairro, $cep, $cidade, $uf, $tipo) {
		
		$update = "UPDATE dados_cobranca SET sacado = '".$sacado."'
					, documento = '".$documento."'
					, endereco = '".$endereco."'
					, bairro = '".$bairro."'
					, cep = '".$cep."'
					, cidade = '".$cidade."'
					, uf = '".$uf."'
					, tipo = '".$tipo."' 
					, pref_telefone = '".$prefixo."'
					, telefone = '".$telefone."'
				WHERE id = '".$id_user."';";
		
		mysql_query($update) or die(mysql_error());				
	}	
	
	
	// inclui os daos de cobranca
	public function IncluiDadosCobranca($id_user, $assinante, $sacado, $prefixo, $telefone, $formaPagamento, $documento, $endereco, $bairro, $cep, $cidade, $uf, $tipo) {
		
		// Gravar dados em dados de cobrança.
		$insert = "INSERT INTO dados_cobranca (id, assinante, sacado, pref_telefone, telefone, data_inclusao, forma_pagameto, documento, endereco, bairro, cep, cidade, uf, tipo, desconto_mesalidade) VALUES ('".$id_user."', '".$assinante."', '".$sacado."', '".$prefixo."', '".$telefone."', NOW(), '".$formaPagamento."', '".$documento."', '".$endereco."', '".$bairro."', '".$cep."', '".$cidade."', '".$uf."', '".$tipo."', '0')";
		
		mysql_query($insert) or die(mysql_error());				
	}
	
	// Pega dados de cobrança de pesso fisica
	public function PegaDadosCobrancaPessoaFisica($id){
		$row = false;
		
		$query = "SELECT * FROM dados_cobranca WHERE id = ".$id." AND tipo = 'PF'";
		
		$resultado = mysql_query($query) or die(mysql_error());
		
		if(mysql_num_rows($resultado) > 0){
			$row = mysql_fetch_array($resultado);			
		}
		
		return $row;
	}
}


