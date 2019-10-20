<?php
/**
 * Autor: Átano de Farias
 * Data: 03/05/2017 
 */	
require_once('../Model/ServicoAvulso/ServicoAvulsoData.php');

	$tagTR = "";
	$assinante = '';
	$filtro = '';
	$valor = '';
	$idUser = '';

	// Pega os dados do contador da sessão.
	$DadosContador = json_decode($_SESSION['DadosContador']);
	
	// Pega o dados do contador.
	$contadorId = $DadosContador->contadorId;
	
	// Instancia a classe responsavel por pegar os dados. 
	$servicoAvulso = new ServicoAvulsoData(); 
	
	// Verifica o filtro.
	if(isset($_GET['filtro']) && $_GET['filtro'] == 'assinante') {
		$assinante = (isset($_GET['valor']) ? $_GET['valor'] : 0);
		$filtro = $_GET['filtro'];
		$valor = $_GET['valor'];
	} elseif(isset($_GET['filtro']) && $_GET['filtro'] == 'id') {
		$idUser = (isset($_GET['valor']) && is_numeric($_GET['valor']) ? $_GET['valor'] : 0);
		$filtro = $_GET['filtro'];
		$valor = $_GET['valor'];
	}

	// Verifica se GET foi definido.
	$data1 = (isset($_GET['data_inicio']) && !empty($_GET['data_inicio']) ? $_GET['data_inicio'] : '');
	$data2 = (isset($_GET['data_fim']) && !empty($_GET['data_fim']) ? $_GET['data_fim'] : '');
	$status = (isset($_GET['status']) && !empty($_GET['status']) ? $_GET['status'] : '');
	
	
	// Pega a lista de serviços.
	$dados = $servicoAvulso->listaServicoAvulso($contadorId, $idUser, $data1, $data2, $assinante, $status);
	
	// Pega a sessão do contador.
	$getSession = json_decode($_SESSION['DadosContador']);

	if($dados) {
	
		$tipoCobranca = "";
		
		foreach($dados as $val) {
	
			switch($val->getServicoName()) {
				
				case 'Gfip_GPS': 
					// Define o nome do Tipo de pagamento 
					$tipoCobranca = 'Gfip';
					break;
				case 'Simples_DAS':	
					// Define o nome do Tipo de pagamento
					$tipoCobranca = 'Simples e DAS';
					break;
				case 'Defis': 
					// Define o nome do Tipo de pagamento
					$tipoCobranca = 'Defis';
					break;	
				case 'Rais_negativa':
					// Define o nome do Tipo de pagamento
					$tipoCobranca = 'Rais';
					break;
				case 'Dirf':
					// Define o nome do Tipo de pagamento
					$tipoCobranca = 'Dirf';
					break;
				case 'F_empresa': 
					// Define o nome do Tipo de pagamento
					$tipoCobranca = 'Baixa Individual';
					break;
				case 'F_sociedade': 
					// Define o nome do Tipo de pagamento
					$tipoCobranca = 'Baixa Sociedade';
					break;
				case 'A_empresa':
					// Define o nome do Tipo de pagamento
					$tipoCobranca = "Abertura Individual";
					break;
				case 'A_sociedade':
					// Define o nome do Tipo de pagamento
					$tipoCobranca = "Abertura Sociedade";
					break;
				case 'decore':
					// Define o nome do Tipo de pagamento
					$tipoCobranca = "DECORE";
					break;
				case 'MEI-ME':
					$tipoCobranca = "MEI para ME";
					break;		
				case 'DBE':
					// Define o nome do Tipo de pagamento
					$tipoCobranca = "DBE";
					break;
				case 'CPOM':
					// Define o nome do Tipo de pagamento
					$tipoCobranca = "Cadastro no CPOM";
					break;
				case 'Premium-complementar':
					// Define o nome do Tipo de pagamento
					$tipoCobranca = "Premium-complementar";
					break;
				case 'funcionario_C_D':
					// Define o nome do Tipo de pagamento
					$tipoCobranca = "C/D funcionário";
					break;
				case 'regularizacao_emp':
					// Define o nome do Tipo de pagamento
					$tipoCobranca = "regularização de empresa";
					break;
				case 'servico_geral':
					// Define o nome do Tipo de pagamento
					$tipoCobranca = "serviço geral";
					break;
				case 'DCTF':
					// Define o nome do Tipo de pagamento
					$tipoCobranca = 'DCTF';
					break;				
				case 'DeSTDA':
					// Define o nome do Tipo de pagamento
					$tipoCobranca = 'DeSTDA';
					break;
				case 'IRPF':
					// Define o nome do Tipo de pagamento
					$tipoCobranca = 'IRPF';
					break;					
					
			}
			
			// Verifica o status bola para alterar as da bola na tabela
				if($val->getStatusBola() == 'Com a Gente'){						
						$opcaoBola = "<span id='span_E_".$val->getId()."' data-id='".$val->getId()."' data-statusBola='Com a Gente' class='statusBola' style='color:green; display:none; cursor: pointer; font-size:30px;'><i class='fa fa-circle' aria-hidden='true'></i></span>"
						."<span id='span_A_".$val->getId()."' data-id='".$val->getId()."' data-statusBola='Junta' class='statusBola' style='color:#a61d00; cursor: pointer; font-size:30px;'><i class='fa fa-circle' aria-hidden='true'></i></span>"
						."<span id='span_J_".$val->getId()."' data-id='".$val->getId()."' data-statusBola='Com o Cliente' class='statusBola' style='color:#ffa500; display:none; cursor: pointer; font-size:30px;'><i class='fa fa-circle' aria-hidden='true'></i></span>";
				} else if($val->getStatusBola() == 'Com o Cliente') {
						$opcaoBola = "<span id='span_E_".$val->getId()."' data-id='".$val->getId()."' data-statusBola='Com a Gente' class='statusBola' style='color:green; cursor: pointer; font-size:30px;'><i class='fa fa-circle' aria-hidden='true'></i></span>"
						."<span id='span_A_".$val->getId()."' data-id='".$val->getId()."' data-statusBola='Junta' class='statusBola' style='color:#a61d00; display:none; cursor: pointer; font-size:30px;'><i class='fa fa-circle' aria-hidden='true'></i></span>"
						."<span id='span_J_".$val->getId()."' data-id='".$val->getId()."' data-statusBola='Com o Cliente' class='statusBola' style='color:#ffa500; display:none; cursor: pointer; font-size:30px;'><i class='fa fa-circle' aria-hidden='true'></i></span>";
				} else {
						$opcaoBola = "<span id='span_E_".$val->getId()."' data-id='".$val->getId()."' data-statusBola='Com a Gente' class='statusBola' style='color:green; display:none; cursor: pointer; font-size:30px;'><i class='fa fa-circle' aria-hidden='true'></i></span>"
						."<span id='span_A_".$val->getId()."' data-id='".$val->getId()."' data-statusBola='Junta' class='statusBola' style='color:#a61d00; display:none; cursor: pointer; font-size:30px;'><i class='fa fa-circle' aria-hidden='true'></i></span>"
						."<span id='span_J_".$val->getId()."' data-id='".$val->getId()."' data-statusBola='Com o Cliente' class='statusBola' style='color: #ffa500; cursor: pointer; font-size:30px;'><i class='fa fa-circle' aria-hidden='true'></i></span>";
				}
			
			// Seleciona o status para exibir na tabela 
			if($val->getStatus() == 'Concluído') { 
				$option = ' <span id="span_C_'.$val->getId().'" class="campoStatus" data-status="Em Aberto" data-id="'.$val->getId().'" style="color: #3D6D9E; cursor: pointer; text-decoration: underline;">Concluído</span>'
				.'<span id="span_ABT_'.$val->getId().'" class="campoStatus" data-status="Primeiro Contato" data-id="'.$val->getId().'" style="display:none; cursor: pointer; color: #3D6D9E; text-decoration: underline;">Em Aberto</span>'
				.'<span id="span_P_'.$val->getId().'" class="campoStatus" data-status="Concluído" data-id="'.$val->getId().'" style="display:none; color: #3D6D9E; cursor: pointer; text-decoration: underline;">1&ordm; Contato</span>';
			} elseif($val->getStatus() == 'Em Aberto') {
				$option = '<span id="span_C_'.$val->getId().'" class="campoStatus" data-status="Em Aberto" data-id="'.$val->getId().'" style="display:none; cursor: pointer; color: #3D6D9E; text-decoration: underline;">Concluído</span>'
				.'<span id="span_ABT_'.$val->getId().'" class="campoStatus" data-status="Primeiro Contato" data-id="'.$val->getId().'" style="color: #3D6D9E; cursor: pointer; text-decoration: underline;">Em Aberto</span>'
				.'<span id="span_P_'.$val->getId().'" class="campoStatus" data-status="Concluído" data-id="'.$val->getId().'" style="display:none; color: #3D6D9E; cursor: pointer; text-decoration: underline;">1&ordm; Contato</span>';
			} else {
				$option = '<span id="span_C_'.$val->getId().'" class="campoStatus" data-status="Em Aberto" data-id="'.$val->getId().'" style="display:none; cursor: pointer; color: #3D6D9E; text-decoration: underline;">Concluído</span>'
				.'<span id="span_ABT_'.$val->getId().'" class="campoStatus" data-status="Primeiro Contato" data-id="'.$val->getId().'" style="display:none; color: #3D6D9E; cursor: pointer; text-decoration: underline;">Em Aberto</span>'
				.'<span id="span_P_'.$val->getId().'" class="campoStatus" data-status="Concluído" data-id="'.$val->getId().'" style="color: #3D6D9E; cursor: pointer; text-decoration: underline;">1&ordm; Contato</span>';
			}			
						
			//Verifica se o serviço possui relacionamento com uma conta. 
			if($val->getLoginStatus() != 'servico-avulso'){
				$linkEntrar = '	<td class="td_calendario" align="left" data-logStatus="'.$val->getLoginStatus().'">'
					.'		<form action="realiza_acesso_cliente.php" method="post" target="_blank" style="margin-bottom: 0px; text-align: center;" >'	
					.'			<input type="hidden" name="clienteid" value="'.$val->getIdUser().'" /> '
					.'			<input type="hidden" name="sessionId" value="'.$getSession->idsession.'" /> '	
					.'			<button class="gridButton" type="submit" style="color: #3D6D9E; text-decoration: underline;"><i class="fa fa-angle-double-right iconesAzuis iconesMed"></i></button>'
					.'		</form> '
					.'	</td>';
			} else {
				$linkEntrar = '	<td class="td_calendario" align="left"></td> ';
			}
			
			$tagTR .= '<tr>'
					.'<td class="td_calendario" style="font-size:100%">'.$opcaoBola.'</td>'
					.'	<td class="td_calendario">'
					.$option
					.'		<img id="load_'.$val->getId().'" src="../images/loading.gif" width="16" height="16" style="display:none;">'
					.'	</td>'
					.'	<td class="td_calendario" style="height: 25px;">'.date("d/m/Y", strtotime($val->getData())).'</td>'						
					.'	<td class="td_calendario" align="left">ID:&nbsp'.$val->getIdUser().'<br><a href="mailto:'.$val->getEmail().'">'.$val->getUserName().'</a><br> Tel:&nbsp('.$val->getPrefTelefone().')&nbsp'. $val->getTelefone().'</td>'		
					.'	<td class="td_calendario">'.$tipoCobranca.'</td>'						
					.'	<td class="td_calendario"><textarea style="width:410px; height:60px; padding: 10px; margin: -5px -5px -9px -5px;" class="campoObservacao" data-id="'.$val->getId().'" id="id_Observacao_'.$val->getId().'">'.$val->getObservacao().'</textarea></td>'
					.$linkEntrar		
					.'</tr>';
		}
	}
	
	$tagTable = '<table class="tablePagamento" style="width: 100%; margin-bottom: 10px;" cellpadding="10" cellspacing= "2"> 
		<tr>
			<th style="width:5%; text-align: center;">Bola</th>
			<th style="width:10%; text-align: center;">Status</th>
			<th style="width:7%; text-align: center;">Data</th>
			<th style="width:15%; text-align: center;">Assinante</th>				
			<th style="width:8%; text-align: center;">Serviço</th>				
			<th style="text-aling: center;">Descrição</th>
			<th style="width:2%; text-align: center;">Entrar</th>
		</tr>'
		.$tagTR
		.'</table>';