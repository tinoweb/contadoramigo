<?php
/**
 * Autor: Átano de Farias
 * Data: 21/02/2017 
 */	
$requestURI = explode("/", $_SERVER['REQUEST_URI']);

if($requestURI[1] == 'admin') {
	require_once('../conect.PDO.php');
} elseif($requestURI[1] == 'contador') {
	require_once('../conect.PDO.php');
} else {
	require_once('conect.PDO.php');
}

class AreaContador extends AccessDB {
	
	// Pega os cliente Prêmio sem contador.
	public function PegaClientePremioSemContador($posicao = 0, $quantidade = 20, $filtro = '', $valor = false) {
		
		$qry = " SELECT l.id, l.nome, l.assinante, l.email, l.senha, l.status, l.data_inclusao, dc.pref_telefone , dc.telefone, dc.documento FROM `login` l  "
			." JOIN `dados_cobranca` dc on l.id = dc.id "
			." WHERE dc.tipo_plano = 'P' "
			." AND l.status = 'ativo' "
			." And dc.contadorId is null ";
			
		switch($filtro) {
			
			case 'id':
				$qry .= " AND l.id = ".$valor." ";
				break;
			case 'assinante':
				$qry .= " AND l.assinante LIKE '%".$valor."%' ";
				break;	
		}			
	
		$qry .= "ORDER BY l.assinante ASC LIMIT ".$posicao.", ".$quantidade.";";
		
		$query = $this->PDO->prepare($qry);
		  
		if(!$query->execute()){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
		
		return $query->fetchAll(PDO::FETCH_ASSOC);
		 
	}
	
	// Pega a quantidade de clientes.
	public function pegaQuantidadeClinete($filtro = '', $valor = false) {
		
		
		
		$qry = " SELECT COUNT(*) as numberRows FROM `login` l  "
			." JOIN `dados_cobranca` dc on l.id = dc.id "
			." WHERE dc.tipo_plano = 'P' "
			." AND l.status = 'ativo' "
			." And dc.contadorId is null ";
			
		switch($filtro) {
			
			case 'id':
				$qry .= " AND l.id = ".$valor." ";
				break;
			case 'assinante':
				$qry .= " AND l.assinante LIKE '%".$valor."%' ";
				break;	
		}			
	
		$qry .= "ORDER BY l.assinante ASC ;";
				
		$query = $this->PDO->prepare($qry);
		  
		if(!$query->execute()){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
		
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	// Pega os cliente Prêmio do contador.
	public function PegaClientePremioDoContador($contadorId, $posicao = 0, $quantidade = 20, $filtro = '', $valor = false, $status ='', $opcoes ='', $ordem ='') {
						
		//verifica se a opcao dmed foi selecionada
		if($opcoes == 'dmed'){
			$qry = "SELECT l.id, l.nome, l.assinante, l.email, l.senha, l.status, l.data_inclusao, dc.pref_telefone , dc.telefone, dc.sacado, dc.documento, ddec.cnae FROM `login` l"  
			." JOIN `dados_cobranca` dc on l.id = dc.id "
			." JOIN dados_da_empresa_codigos ddec on dc.id = ddec.id "
            ."WHERE dc.tipo_plano = 'P' "
            ."AND l.status in ('ativo', 'inativo') "
            ." AND dc.contadorId =".$contadorId;
				if($status == 'ativo'){
					$qry .= " AND l.status = 'ativo' ";
				} else if($status == 'inativo') {
					$qry .= " AND l.status = 'inativo' ";
				} else {
					$qry .= " AND (l.status = 'ativo' OR l.status = 'inativo') ";
				}
            
            $qry .= " AND ddec.cnae in ('3250-7/06', '3250-7/09', '8610-1/01', "
										." '8610-1/02', '8630-5/01', '8630-5/02', "
										." '8630-5/03', '8630-5/04', '8630-5/07', "
										." '8640-2/01', '8640-2/02', '8640-2/05', "
										." '8640-2/10', '8640-2/11', '8640-2/12', "
										." '8640-2/14', '8640-2/99', '8650-0/03', "
										." '8650-0/04', '8650-0/05', '8650-0/06', "
										." '8650-0/99', '8711-5/01', '8720-4/01', "
										." '8720-4/99', '8650-0/07', '8690-9/01', "
										." '8690-9/02', '8711-5/03', '8800-6/00') ";
             
			switch($filtro) {
			
			case 'id':
				$qry .= " AND l.id = ".$valor." ";
				break;
			case 'assinante':
				$qry .= " AND l.assinante LIKE '%".$valor."%' ";
				break;	
		}	
             $qry .= " GROUP BY ddec.id ";
             		if(!empty($ordem) && $ordem == "1"){
						$qry .= " ORDER BY l.data_inclusao DESC LIMIT ".$posicao.", ".$quantidade.";";
					} else {
						$qry .= " ORDER BY l.assinante ASC LIMIT ".$posicao.", ".$quantidade.";";
					}
		} 
		
		// verifica se a opcao dimob esta selecionada
		else if ($opcoes == 'dimob'){
			$qry = "SELECT l.id, l.nome, l.assinante, l.email, l.senha, l.status, l.data_inclusao, dc.pref_telefone , dc.telefone, dc.sacado, dc.documento, ddec.cnae FROM `login` l"  
			." JOIN `dados_cobranca` dc on l.id = dc.id "
			." JOIN dados_da_empresa_codigos ddec on dc.id = ddec.id "
            ."WHERE dc.tipo_plano = 'P' "
            ."AND l.status in ('ativo', 'inativo') "
            ." AND dc.contadorId =".$contadorId;
				if($status == 'ativo'){
					$qry .= " AND l.status = 'ativo' ";
				} else if($status == 'inativo') {
					$qry .= " AND l.status = 'inativo' ";
				} else {
					$qry .= " AND (l.status = 'ativo' OR l.status = 'inativo') ";
				}
            
            $qry .= "AND (ddec.cnae LIKE '5590%' OR 
					 ddec.cnae LIKE '6810%' OR 
					 ddec.cnae LIKE '6821%' OR 
					 ddec.cnae LIKE '6822%' OR 
					 ddec.cnae = '4110-7/00')";
             
			switch($filtro) {
			
			case 'id':
				$qry .= " AND l.id = ".$valor." ";
				break;
			case 'assinante':
				$qry .= " AND l.assinante LIKE '%".$valor."%' ";
				break;	
		}	
             $qry .= " GROUP BY ddec.id ";
             		if(!empty($ordem) && $ordem == "1"){
						$qry .= " ORDER BY l.data_inclusao DESC LIMIT ".$posicao.", ".$quantidade.";";
					} else {
						$qry .= " ORDER BY l.assinante ASC LIMIT ".$posicao.", ".$quantidade.";";
					}
		}
		
		//verifica se a opcao funcionario foi selecionada
		else if($opcoes == 'funcionario'){
			$qry = " SELECT l.id, l.nome, l.assinante, l.email, l.senha, l.status, l.data_inclusao, dc.pref_telefone , dc.telefone, dc.sacado, dc.documento FROM `login` l  "
			." JOIN `dados_cobranca` dc on l.id = dc.id "
			." JOIN dados_do_funcionario df on l.id = df.id "
			."WHERE dc.tipo_plano = 'P' "
            ."AND l.status in ('ativo', 'inativo') "
            ." AND dc.contadorId =".$contadorId;
			if($status == 'ativo'){
				$qry .= " AND l.status = 'ativo' ";
			} else if($status == 'inativo') {
				$qry .= " AND l.status = 'inativo' ";
			} else {
				$qry .= " AND (l.status = 'ativo' OR l.status = 'inativo') ";
			}
			
			switch($filtro) {
			
				case 'id':
					$qry .= " AND l.id = ".$valor." ";
					break;
				case 'assinante':
					$qry .= " AND l.assinante LIKE '%".$valor."%' ";
					break;	
			}
			
			$qry .= " GROUP BY df.id ";
				if(!empty($ordem) && $ordem == "1"){
					$qry .= " ORDER BY l.data_inclusao DESC LIMIT ".$posicao.", ".$quantidade.";";
				} else {
					$qry .= " ORDER BY l.assinante ASC LIMIT ".$posicao.", ".$quantidade.";";
				}
				
		}
		
		//tras a lista com todos os assinantes premio
		else {			
			$qry = " SELECT l.id, l.nome, l.assinante, l.email, l.senha, l.status, l.data_inclusao, dc.pref_telefone , dc.telefone, dc.sacado, dc.documento FROM `login` l  "
			." JOIN `dados_cobranca` dc on l.id = dc.id "
			."WHERE dc.tipo_plano = 'P' "
            ."AND l.status in ('ativo', 'inativo') "
            ." AND dc.contadorId =".$contadorId;
			if($status == 'ativo'){
				$qry .= " AND l.status = 'ativo' ";
			} else if($status == 'inativo') {
				$qry .= " AND l.status = 'inativo' ";
			} else {
				$qry .= " AND (l.status = 'ativo' OR l.status = 'inativo') ";
			}
			
			switch($filtro) {
			
				case 'id':
					$qry .= " AND l.id = ".$valor." ";
					break;
				case 'assinante':
					$qry .= " AND l.assinante LIKE '%".$valor."%' ";
					break;	
			}
			
			
			$qry .= " ORDER BY l.assinante ASC LIMIT ".$posicao.", ".$quantidade.";";
									
		}
						
		$query = $this->PDO->prepare($qry);
		
		if(!$query->execute()){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
		
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
	
	//Ordena os dados premio por data
	public function PegaClientePremioDoContadorOrdemData($contadorId, $posicao, $quantidade, $filtro, $valor, $status, $opcoes){
						
		$qry = " SELECT l.id, l.nome, l.assinante, l.email, l.senha, l.status, l.data_inclusao, dc.pref_telefone , dc.telefone, dc.sacado, dc.documento, MAX(ca.data) AS data_aceito FROM contratos_aceitos ca "
			." JOIN login l ON l.id = ca.user "
			." JOIN dados_cobranca dc ON dc.id = ca.user "
			." WHERE user in (SELECT id FROM dados_cobranca WHERE contadorId = 9) "
			." AND dc.tipo_plano = 'P' "
			." AND l.status in ('ativo', 'inativo')"
			." AND contratoId = 2";
		
			if($status == 'ativo'){
				$qry .= " AND l.status = 'ativo' ";
			} else if($status == 'inativo') {
				$qry .= " AND l.status = 'inativo' ";
			} else {
				$qry .= " AND (l.status = 'ativo' OR l.status = 'inativo') ";
			}
			
			switch($filtro) {
			
				case 'id':
					$qry .= " AND l.id = ".$valor." ";
					break;
				case 'assinante':
					$qry .= " AND l.assinante LIKE '%".$valor."%' ";
					break;	
			}
		
		$qry .= " GROUP BY user ORDER BY data_aceito DESC LIMIT ".$posicao.", ".$quantidade.";";
						
		$query = $this->PDO->prepare($qry);
		
		if(!$query->execute()){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
		
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
		
	
	// Pega os cliente Prêmio do contador.
	public function pegaQuantidadeDoContador($contadorId, $filtro = '', $valor = false, $status = '', $opcoes = '') {
		
		//verifica se a opcao dmed foi selecionada para montar a paginação
		if($opcoes == 'dmed'){
			$qry = "SELECT count(*) as numberRows FROM `login` l"  
					." JOIN `dados_cobranca` dc on l.id = dc.id "
					." JOIN dados_da_empresa_codigos ddec on dc.id = ddec.id "
					."WHERE dc.tipo_plano = 'P' "
					."AND l.status in ('ativo', 'inativo') "
					." AND dc.contadorId =".$contadorId;
				if($status == 'ativo'){
					$qry .= " AND l.status = 'ativo' ";
				} else if($status == 'inativo') {
					$qry .= " AND l.status = 'inativo' ";
				} else {
					$qry .= " AND (l.status = 'ativo' OR l.status = 'inativo') ";
				}
            
            $qry .= " AND ddec.cnae in ('3250-7/06', '3250-7/09', '8610-1/01', "
										." '8610-1/02', '8630-5/01', '8630-5/02', "
										." '8630-5/03', '8630-5/04', '8630-5/07', "
										." '8640-2/01', '8640-2/02', '8640-2/05', "
										." '8640-2/10', '8640-2/11', '8640-2/12', "
										." '8640-2/14', '8640-2/99', '8650-0/03', "
										." '8650-0/04', '8650-0/05', '8650-0/06', "
										." '8650-0/99', '8711-5/01', '8720-4/01', "
										." '8720-4/99', '8650-0/07', '8690-9/01', "
										." '8690-9/02', '8711-5/03', '8800-6/00') ";
             
			switch($filtro) {
			
			case 'id':
				$qry .= " AND l.id = ".$valor." ";
				break;
			case 'assinante':
				$qry .= " AND l.assinante LIKE '%".$valor."%' ";
				break;	
			}
			
			$qry .= " GROUP BY ddec.id ";
			
		} 
		
		// verifica se a opcao dimob esta selecionada para montar a paginação
		else if ($opcoes == 'dimob'){
			$qry = "SELECT count(*) as numberRows FROM `login` l"  
					." JOIN `dados_cobranca` dc on l.id = dc.id "
					." JOIN dados_da_empresa_codigos ddec on dc.id = ddec.id "
					."WHERE dc.tipo_plano = 'P' "
					."AND l.status in ('ativo', 'inativo') "
					." AND dc.contadorId =".$contadorId;
			
				if($status == 'ativo'){
					$qry .= " AND l.status = 'ativo' ";
				} else if($status == 'inativo') {
					$qry .= " AND l.status = 'inativo' ";
				} else {
					$qry .= " AND (l.status = 'ativo' OR l.status = 'inativo') ";
				}
            
            $qry .= "AND (ddec.cnae LIKE '5590%' OR 
					 ddec.cnae LIKE '6810%' OR 
					 ddec.cnae LIKE '6821%' OR 
					 ddec.cnae LIKE '6822%' OR 
					 ddec.cnae = '4110-7/00')";
             
			switch($filtro) {
			
			case 'id':
				$qry .= " AND l.id = ".$valor." ";
				break;
			case 'assinante':
				$qry .= " AND l.assinante LIKE '%".$valor."%' ";
				break;	
			}
			
			$qry .= " GROUP BY ddec.id ";
            
		}
		
		//verifica se a opcao funcionario foi selecionada para montar a paginação
		else if($opcoes == 'funcionario'){
			$qry = " SELECT count(*) as numberRows FROM `login` l  "
					." JOIN `dados_cobranca` dc on l.id = dc.id "
					." JOIN dados_do_funcionario df on l.id = df.id "
					."WHERE dc.tipo_plano = 'P' "
					."AND l.status in ('ativo', 'inativo') "
					." AND dc.contadorId =".$contadorId;
			
			if($status == 'ativo'){
				$qry .= " AND l.status = 'ativo' ";
			} else if($status == 'inativo') {
				$qry .= " AND l.status = 'inativo' ";
			} else {
				$qry .= " AND (l.status = 'ativo' OR l.status = 'inativo') ";
			}
			
			switch($filtro) {
			
				case 'id':
					$qry .= " AND l.id = ".$valor." ";
					break;
				case 'assinante':
					$qry .= " AND l.assinante LIKE '%".$valor."%' ";
					break;	
			}
			
			$qry .= " GROUP BY df.id ";
			
		} else {
				
			$qry = " SELECT count(*) as numberRows FROM `login` l  "
				." JOIN `dados_cobranca` dc on l.id = dc.id "
				." WHERE dc.tipo_plano = 'P' "
				." AND l.status in ('ativo', 'inativo') "
				." AND dc.contadorId = ".$contadorId;

			if($status == 'ativo'){
				$qry .= " AND l.status = 'ativo' ";
			} else if($status == 'inativo') {
				$qry .= " AND l.status = 'inativo' ";
			} else {
				$qry .= " AND (l.status = 'ativo' OR l.status = 'inativo') ";
			}

			switch($filtro) {

				case 'id':
					$qry .= " AND l.id = ".$valor." ";
					break;
				case 'assinante':
					$qry .= " AND l.assinante LIKE '%".$valor."%' ";
					break;	
			}
		}
		
		$query = $this->PDO->prepare($qry);
			
		$params = array( 'contadorId' => $contadorId );	
		  
		if(!$query->execute($params)){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
		
		return $query->fetch(PDO::FETCH_ASSOC);
	}	
	
	
	// Pega os empresa do cliente.
	public function PegaEmpresaCliente($clienteId, $conatadorId) {
		
		$query = $this->PDO->prepare(" SELECT de.id, razao_social, cnpj, nome_fantasia, ativa, data_desativacao, l.data_inclusao FROM dados_da_empresa de "
			." JOIN `login` l ON de.id = l.id "
			." JOIN `dados_cobranca` dc on l.idUsuarioPai = dc.id "
			." WHERE l.idUsuarioPai = :clienteId "
			." And dc.contadorId = :contadorId"
			." AND de.ativa = 1 ORDER BY razao_social, l.data_inclusao DESC; ");
			
		$params = array( 'clienteId' => $clienteId, 'contadorId' => $conatadorId);	
		  
		if(!$query->execute($params)){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
		
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
	
	// Pega a quantidade de empresa do cliente.
	public function PegaQuantidadeEmpresa($clienteId) {
		
		$query = $this->PDO->prepare(" SELECT COUNT(*) as numberRows FROM dados_da_empresa de "
			." JOIN `login` l ON de.id = l.id "
			." WHERE l.idUsuarioPai = :clienteId "
			." AND de.ativa = 1 ORDER BY razao_social, l.data_inclusao DESC; ");
			
		$params = array( 'clienteId' => $clienteId);	
		  
		if(!$query->execute($params)){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
		
		return $query->fetch(PDO::FETCH_ASSOC);
	}
	
	// Pega a data do contrato.
	public function PegaDataContatoPremio($userId, $ordem) {
			
		$query = $this->PDO->prepare(" SELECT * FROM `contratos_aceitos` WHERE `user` = :userId AND contratoId = 2 ORDER BY data DESC  LIMIT 1;");
			
		$params = array( 'userId' => $userId);	
		  
		if(!$query->execute($params)){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
		
		return $query->fetch(PDO::FETCH_ASSOC);
	}	
	
	// Define o contador para a conta Prêmio.
	public function RelacionaContadorCliente($clienteId, $contadorId) {
		$query = $this->PDO->prepare(
			 " UPDATE `dados_cobranca` "
			." SET contadorId = :contadorId"
			." WHERE id = :clienteId "
		);
		
		$params = array(
			'contadorId' => $contadorId,
			'clienteId' => $clienteId
		 );
		 
		if(!$query->execute($params)){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
	}
}


