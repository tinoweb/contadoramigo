<?php
/**
 * Autor: Átano de Farias
 * Data: 15/02/2017 
 */
class Login {

	// Pega Todos usuarios.
	public function pegaDadosUsuario($id_user) {
		$rows = false;
	
		$consulta = mysql_query(' SELECT * FROM `login` WHERE id = '.$id_user.'; ');		
		
		if( mysql_num_rows($consulta) > 0 ){
			
			$rows = mysql_fetch_array($consulta);
			
		}
			
		return $rows;	
	}
	
	// Pega o status do usuario.
	public function pegaDadosusuarioPai($id_user) {
		
		$rows = false;
	
		$consulta = mysql_query(' SELECT l2.id
				,l2.nome
				,l2.assinante
				,l2.email
				,l2.senha
				,l2.status
				,l2.info_preliminar
				,l2.data_inclusao
				,l2.id_plano
				,l2.sessionID
				,l2.idUsuarioPai 
			FROM login l1 
			JOIN login l2 ON l2.id = l1.idUsuarioPai 
			WHERE l1.id = '.$id_user.';');		
		
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);	
		}
			
		return $rows;	
	}
	
	// Atualiza os dados de login.
	public function AtualizaDadosLogin($id_user, $nome, $assinante, $email, $senha, $status, $infoPreliminar, $idPlano, $sessionID, $idUsuarioPai) {
		
		$update = "UPDATE login 
				SET nome ='".$nome."'
				, assinante = '".$assinante."'
				, email = '".$email."'
				, senha = '".$senha."'
				, status = '".$status."'
				, info_preliminar = '".$infoPreliminar."'
				, id_plano = '".$idPlano."'
				, sessionID = '".$sessionID."'
				, idUsuarioPai = '".$idUsuarioPai."'
				WHERE id = '".$id_user."';";
				
		mysql_query($update)  or die(mysql_error());				
	}
	
	public function AtualizaDadosLoginPorStatus($id_user, $nome, $assinante, $email, $senha, $status, $infoPreliminar, $idPlano, $sessionID, $idUsuarioPai) {

		$update = "UPDATE login 
				SET nome ='".$nome."'
				, assinante = '".$assinante."'
				, email = '".$email."'
				, senha = '".$senha."'
				, info_preliminar = '".$infoPreliminar."'
				, id_plano = '".$idPlano."'
				, sessionID = '".$sessionID."'
				, idUsuarioPai = '".$idUsuarioPai."'
				WHERE id = '".$id_user."' 
				AND status = '".$status."';";

		mysql_query($update)  or die(mysql_error());	
	}
	
	
	
	
	// Inclui os dados de login.
	public function IncluirDadosLogin($nome, $assinante, $email, $senha, $status, $infoPreliminar, $idPlano, $sessionID, $idUsuarioPai) {
	
		$insert = "INSERT INTO login (nome, assinante, email, senha, status, info_preliminar, data_inclusao, id_plano, sessionID, idUsuarioPai) VALUES ('".$nome."', '".$assinante."', '".$email."', '".$senha."', '".$status."', '".$infoPreliminar."', NOW(), '".$idPlano."', '".$sessionID."', '".$idUsuarioPai."')";
		
		mysql_query($insert) or die(mysql_error());
		
		return mysql_insert_id();
	}
}