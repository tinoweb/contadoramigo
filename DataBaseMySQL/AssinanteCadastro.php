<?php
/**
 * Classe para manipular os dados da tabela do contador.
 * Autor: Átano de Farias 
 * Data: 29/03/2017 
 */
class AssinanteCadastro {

	public function AtualizaDadosLogin($id, $nome, $assinante, $email, $senha, $status, $idPlano, $idUsuarioPai) {

		$update = "UPDATE login 
				SET nome ='".$nome."'
				, assinante = '".$assinante."'
				, email = '".$email."'
				, senha = '".$senha."'
				, status = '".$status."'
				, id_plano = '".$idPlano."'
				, idUsuarioPai = '".$idUsuarioPai."'
				WHERE id = '".$id."'";

		mysql_query($update)  or die(mysql_error());	
	}
	
	// Atualiza o id pai
	public function AtualizaIdUsuarioPaiLogin($idUsuarioPai) {
		
		$update = "UPDATE login SET idUsuarioPai = id WHERE id = '" . $idUsuarioPai . "'";
		
		mysql_query($update)  or die(mysql_error());	
	}
	
	// Inclui os dados de login.
	public function IncluirDadosLogin($nome, $assinante, $email, $senha, $status, $idPlano, $idUsuarioPai) {
	
		$insert = "INSERT INTO login (nome, assinante, email, senha, status, data_inclusao, id_plano, sessionID, idUsuarioPai) VALUES ('".$nome."', '".$assinante."', '".$email."', '".$senha."', '".$status."', NOW(), '".$idPlano."', '".$sessionID."', '".$idUsuarioPai."')";
		
		mysql_query($insert) or die(mysql_error());
		
		return mysql_insert_id();
	}
	
	// Atualiza os daos de cobranca
	public function AtualizaDadosCobranca($id_user, $sacado, $prefixo, $telefone, $documento, $endereco, $bairro, $cep, $cidade, $uf, $tipo) {
		
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
	public function IncluiDadosCobranca($id, $assinante, $pref_telefone, $telefone, $forma_pagameto, $numero_cartao, $codigo_seguranca, $nome_titular, $data_validade, $desconto_mesalidade) {
			
		// Gravar dados em dados de cobrança.
		$insert = "INSERT INTO dados_cobranca (id, assinante, pref_telefone, telefone, data_inclusao,forma_pagameto, numero_cartao, codigo_seguranca, nome_titular, data_validade, desconto_mesalidade) VALUES ('".$id."', '".$assinante."', '".$pref_telefone."', '".$telefone."', NOW(), '".$forma_pagameto."', '".$numero_cartao."', '".$codigo_seguranca."', '".$nome_titular."', '".$data_validade."', '".$desconto_mesalidade."')";
		
		mysql_query($insert) or die(mysql_error());				
	}

	// Pega os dados de cobranca do usando avulso pelo email.
	public function VerificaEmailServicoAvulsoExiste($email) {
		
		$rows = false;
		
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
	
	// Pega os dados de cobranca do usando avulso pelo email.
	public function VerificaEmaildoLoginExiste($email) {
		
		$rows = false;
		
		//Pegar o id de login para utilizar nas demais tabelas.
		$query = "SELECT * FROM login 
				WHERE email='" . $Email . "' 
				ORDER BY id DESC LIMIT 0, 1";
		
		$consulta = mysql_query($query);		
		
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
		
		return $rows;	
	}
	
	public function GravaMetricasConversao($id_login, $status) {
		
		$insert = "insert into metricas_conversao (id_login, status, data) VALUES (".$id_login.",'".$status."', NOW())";
		
		mysql_query($insert) or die(mysql_error());
		
		return mysql_insert_id();
	}
	
	
	public function GravalogsAcesso($id_user, $acao) {
		
		$insert = "insert into log_acessos (id_user, acao) VALUES (".$id_user.",'".$acao."')";
	
		
		mysql_query($insert) or die(mysql_error());
		
		return mysql_insert_id();
	}

	public function GravaContratoAceito($id_user) {
		
		$insert = "INSERT INTO `contratos_aceitos`(`id`, `user`, `aceito`, `data`) VALUES ( '','".$id_user."','1', NOW() )";
		
		mysql_query($insert) or die(mysql_error());
		
		return mysql_insert_id();
	}
	
	public function GravaHistoricoCobranca($id, $dataPagamento, $status_pagamento) {
		
		$insert = "INSERT INTO historico_cobranca (id, data_pagamento, status_pagamento) VALUES ('$id', '$dataPagamento', '$status_pagamento')";
		
		mysql_query($insert) or die(mysql_error());
		
		return mysql_insert_id();
	}
}
