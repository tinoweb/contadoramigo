<?php 
	
	include '../conect.php';

	include '../datas.class.php';

	class Boleto_registrado{
		
		private $id_historico;
		private $id_user;
		private $nosso_numero;
		private $status;	
		private $tipo;
		private $plano;
		private $tipoPlano;
		private $valor;
		private $multa;
		private $mora;
		private $vencimento;
		private $data_geracao;

		function __construct($dados = NULL){

			if( isset($dados) && $dados != NULL ){
			
				$this->id_historico = $dados['id_historico'];
				$this->id_user = $dados['id_user'];
				$this->nosso_numero = $dados['nosso_numero'];
				$this->status = $dados['status'];
				$this->plano = $dados['plano'];
				$this->tipo = $dados['tipo'];
				$this->tipoPlano = $dados['tipo_plano']; 
				$this->valor = $dados['valor'];
				$this->multa = $dados['multa'];
				$this->mora = $dados['mora'];
				$this->vencimento = $dados['vencimento'];
				$this->data_geracao = $dados['data_geracao'];
			}
		}
		function getid_user(){
			return $this->id_user;
		}
		function setid_user($string){
			$this->id_user = $string;
		}
		function getdata_geracao(){
			return $this->data_geracao;
		}
		function setdata_geracao($string){
			$this->data_geracao = $string;
		}
		function getvencimento(){
			return $this->vencimento;
		}
		function setvencimento($string){
			$this->vencimento = $string;
		}
		function getmora(){
			return $this->mora;
		}
		function setmora($string){
			$this->mora = $string;
		}
		function getmulta(){
			return $this->multa;
		}
		function setmulta($string){
			$this->multa = $string;
		}
		function getvalor(){
			return $this->valor;
		}
		function setvalor($string){
			$this->valor = $string;
		}
		function getplano(){
			return $this->plano;
		}
		function setplano($string){
			$this->plano = $string;
		}

		function gettipoPlano(){
			return $this->tipoPlano;
		}
		function settipoPlano($string){
			$this->tipoPlano = $string;
		}
		
		function gettipo(){
			return $this->tipo;
		}
		function settipo($string){
			$this->tipo = $string;
		}
		function getstatus(){
			return $this->status;
		}
		function setstatus($string){
			$this->status = $string;
		}
		function getnosso_numero(){
			return $this->nosso_numero;
		}
		function setnosso_numero($string){
			$this->nosso_numero = $string;
		}
		function getid_historico(){
			return $this->id_historico;
		}
		function setid_historico($string){
			$this->id_historico = $string;
		}
	}
	class Dados_user{
		private $assinante;
		private $email;
		private $endereco;
		private $bairro;
		private $cep;
		private $cidade;
		private $uf;
		private $plano;
		private $status;
		function __construct($id){
			$consulta = mysql_query("SELECT * FROM dados_cobranca WHERE id = '".$id."' ");
			$dados = mysql_fetch_array($consulta);
			$this->assinante = $dados['assinante'];
			$this->endereco = $dados['endereco'];
			$this->bairro = $dados['bairro'];
			$this->cep = $dados['cep'];
			$this->cidade = $dados['cidade'];
			$this->uf = $dados['uf'];
			$this->plano = $dados['plano'];
			$consulta = mysql_query("SELECT * FROM login WHERE id = idUsuarioPai AND id = '".$id."' ");
			$dados = mysql_fetch_array($consulta);
			$this->email = $dados['email'];
			$this->status = $dados['status'];
		}
		function getstatus(){
			return $this->status;
		}
		function setstatus($string){
			$this->status = $string;
		}
		function getemail(){
			return $this->email;
		}
		function setemail($string){
			$this->email = $string;
		}
		function getplano(){
			return $this->plano;
		}
		function setplano($string){
			$this->plano = $string;
		}
		function getuf(){
			return $this->uf;
		}
		function setuf($string){
			$this->uf = $string;
		}
		function getcidade(){
			return $this->cidade;
		}
		function setcidade($string){
			$this->cidade = $string;
		}
		function getcep(){
			return $this->cep;
		}
		function setcep($string){
			$this->cep = $string;
		}
		function getbairro(){
			return $this->bairro;
		}
		function setbairro($string){
			$this->bairro = $string;
		}
		function getendereco(){
			return $this->endereco;
		}
		function setendereco($string){
			$this->endereco = $string;
		}
		function getassinante(){
			return $this->assinante;
		}
		function setassinante($string){
			$this->assinante = $string;
		}
	}
	class Cobranca_boleto{
		
		private $nosso_numero;
		private $id_user;
		private $contadorId;
		private $plano_user;
		private $status_user;
		private $email_user;
		private $valor;
		private $status_email;
		
		private $valor_pago;

		private $boleto_registrado;
		private $tipo_plano;
		

		function getvalor_pago(){
			return $this->valor_pago;
		}
		function setvalor_pago($string){
			$this->valor_pago = $string;
		}
		function getboleto_registrado(){
			return $this->boleto_registrado;
		}
		function setboleto_registrado($string){
			$this->boleto_registrado = $string;
		}
		function getstatus_email(){
			return $this->status_email;
		}
		function setstatus_email($string){
			$this->status_email = $string;
		}
		function getid_user(){
			return $this->id_user;
		}
		function setid_user($string){
			$this->id_user = $string;
		}
		function getvalor(){
			return $this->valor;
		}
		function setvalor($string){
			$this->valor = $string;
		}
		function getemail_user(){
			return $this->email_user;
		}
		function setemail_user($string){
			$this->email_user = $string;
		}
		function getstatus_user(){
			return $this->status_user;
		}
		function setstatus_user($string){
			$this->status_user = $string;
		}
		function getplano_user(){
			return $this->plano_user;
		}
		function setplano_user($string){
			$this->plano_user = $string;
		}
		function gettipo_plano() {
			return $this->tipo_plano;
		}
		function settipo_plano($string) {
			$this->tipo_plano = $string;
		}		
		function getnosso_numero(){
			return $this->nosso_numero;
		}
		function setnosso_numero($string){
			$this->nosso_numero = $string;
		}
		function relatarErro($string){
			echo $string.'<br><br>';
		}
		//Seta o registro no historico de cobranca como pago
		function setarHistorico(){
			$values = "
				status_pagamento='pago', 
				tipo_cobranca='boleto', 
				envio_email='enviado'
			";
			$historico_user = mysql_query("UPDATE historico_cobranca SET ".$values." WHERE idHistorico = '".$this->getboleto_registrado()->getid_historico()."' ");
			
			if(mysql_num_rows($historico_user) > 0 ) {
				$objeto_historico_user = mysql_fetch_array($historico_user);
			}
		}
		//Verifica o codigo do resultado da acao para este pagamento de acordo com o tipo de boleto
		function getResultadoAcao(){
			if( $this->getboleto_registrado()->gettipo() == 'mensalidade' )
				return '1.2';
			else if( $this->getboleto_registrado()->gettipo() == 'avulso' )
				return '9.3';
			else if( $this->getboleto_registrado()->gettipo() == 'certificado' )
				return '10.1';
			else if( $this->getboleto_registrado()->gettipo() == 'ComplementarPremium' )
				return '7.1';
			else if( $this->getboleto_registrado()->gettipo() == 'Gfip_GPS' )
				return '8.2';
			else if( $this->getboleto_registrado()->gettipo() == 'Simples_DAS' )
				return '8.3';
			else if( $this->getboleto_registrado()->gettipo() == 'Defis' )
				return '8.4';
			else if( $this->getboleto_registrado()->gettipo() == 'Rais_negativa' )
				return '8.5';
			else if( $this->getboleto_registrado()->gettipo() == 'Dirf' )
				return '8.6';
			else if( $this->getboleto_registrado()->gettipo() == 'F_empresa' )
				return '8.7';
			else if( $this->getboleto_registrado()->gettipo() == 'F_sociedade' )
				return '8.8';
			else if( $this->getboleto_registrado()->gettipo() == 'MEI-ME' )
				return '8.9';	
			else if( $this->getboleto_registrado()->gettipo() == 'CPOM' )
				return '9.0';
			else if( $this->getboleto_registrado()->gettipo() == 'A_empresa' )
				return '9.4';
			else if( $this->getboleto_registrado()->gettipo() == 'A_sociedade' )
				return '9.5';
			else if( $this->getboleto_registrado()->gettipo() == 'decore' )
				return '9.6';						
			else if( $this->getboleto_registrado()->gettipo() == 'DBE' )
				return '9.7';
			else if( $this->getboleto_registrado()->gettipo() == 'funcionario_C_D' )
				return '9.8';
			else if( $this->getboleto_registrado()->gettipo() == 'regularizacao_emp' )
				return '11.0';	
			else if( $this->getboleto_registrado()->gettipo() == 'servico_geral' )
				return '11.1';	
			else if( $this->getboleto_registrado()->gettipo() == 'DCTF' )
				return '11.2';	
			else if( $this->getboleto_registrado()->gettipo() == 'DeSTDA' )
				return '11.3';	
			else if( $this->getboleto_registrado()->gettipo() == 'IRPF' )
				return '11.4';	
		}
		//Verifica se presica emitir nota para o tipo de pagamento, se for mensalidade, retornar 0, que identifica o pagamento como pendente para emitir nota, sse for 0, identifica como nao pendente ára emitir nota
//		function isEmitirNota(){
//			if( $this->getboleto_registrado()->gettipo() == 'mensalidade' )
//				return 0;
//			else
//				return 1;
//		}		
		function isEmitirNota(){
			if( $this->getboleto_registrado()->gettipo() != 'ComplementarPremium' )
				return 0;
			else
				return 1;
		}
		//Insere o boleto pago no relatorio de cobrança
		function inserirRelatorioCobranca(){
			
			// Verifica qual o tipo do pagamento que sera gravado.
			if($this->gettipo_plano() == 'P' && $this->getboleto_registrado()->gettipo() == 'mensalidade' ) {
				$tipo = 'Premium';
			} elseif($this->gettipo_plano() == 'S' && $this->getboleto_registrado()->gettipo() == 'mensalidade') {
				$tipo = 'Standard';
			} else {
				$tipo = $this->getboleto_registrado()->gettipo();
			}
			
			$campos = "(id,  idHistorico, data, data_pagamento, tipo_cobranca, resultado_acao, envio_email, valor_pago, emissao_NF, tipo_plano, plano, tipo, observacao)";
			$values = "(	
							'" . $this->getid_user() . "', 
							'" . $this->getboleto_registrado()->getid_historico() ."' ,
							'" . date("Y-m-d") . "', 
							'" . date("Y-m-d") . "', 
							'boleto', 
							'".$this->getResultadoAcao()."', 
							'enviado', 
							'" . $this->getvalor_pago() . "',
							'" . $this->isEmitirNota() . "',
							'". $this->gettipo_plano() ."',
							'". $this->getplano_user() ."',
							'". $tipo ."',
							'Boleto registro: ".$this->getnosso_numero()."'
						)";
						
			$relatorio_cobranca = mysql_query("INSERT INTO relatorio_cobranca ".$campos." VALUES ".$values." ");
			
			// Pega o ultimo código do relatorio de cobrança.
			$idRelatorio = mysql_insert_id();
			
			// Esta condição foi criada para normalizar o pagamento com atraso que retorna calcularo pelo Banco referente ao Premium.
			if( $this->gettipo_plano() == 'P' && $this->getResultadoAcao() == '1.2' ) {
				
				// desconto de 20% - No futuro verifica onde pode ser informado para não ficar na programação.
				$desconto = 0.2;
				
				// Pega a metade do valor da mensalidade do prêmio que devera ser repassada para o contador - Formula para normalizer o valor caso tenha três casa decimais.
				$valorTotal = $this->getvalor_pago() - number_format(($this->getvalor_pago() / 2),2,'.','');
				
				// Aplica o juros de 20% (vinte porcento) para pegar o valor líquido.
				//$valorLiquido = $valorTotal - ($valorTotal * $desconto);
				$valorLiquido = ($valorTotal * $desconto);
				$valorLiquido = $valorTotal - number_format(($valorLiquido),2,'.','');

				// Atualiza o relatorio de cobranca do contador.
				$qryUpdate = " UPDATE cobranca_contador cc "
						." JOIN boletos_registrados br ON cc.boletosRegistradosId = br.id "
						." SET idRelatorio = ".$idRelatorio
						." ,valor_total = '".$valorTotal."'"
						." ,valor_liquido = '".$valorLiquido."'"
						." ,data_pagamento = '". date("Y-m-d")."'"
						." ,desconto_porcentagem = '".$desconto."'"
						." WHERE br.nosso_numero = ".$this->getnosso_numero()."; ";

				mysql_query($qryUpdate);
				
				// Realiza a inclusão de email para o contador informando o contador que o serviço premium foi contratado.
				$dadosContador = $this->PegaDadsoContador($this->getnosso_numero());
				
				// Pega os dados do contador.
				$contadorNome = $dadosContador['nome'];
				$email = $dadosContador['email'];
				
				// Realiza a chamada do método que realiza a gravação do dados para o envio do email.  
				// $this->GravaDadosEnvioEmail($contadorNome, $email, 'premium_contratado');
				
			} elseif($this->gettipo_plano() == 'P' && $this->getResultadoAcao() == '7.1') {
			
				// desconto de 20% - No futuro verifica onde pode ser informado para não ficar na programação.
				$desconto = 0.2;
				
				// Pega a metade do valor da mensalidade do prêmium
				$valorTotal = $this->getvalor_pago();
				
				// Aplica o juros de 20% (vinte porcento) para pegar o valor líquido.
				$valorLiquido = ($valorTotal * $desconto);
				$valorLiquido = $valorTotal - number_format(($valorLiquido),2,'.','');
				
				
				// Atualiza o relatorio de cobranca do contador.
				$qryUpdate = " UPDATE cobranca_contador cc "
						." JOIN boletos_registrados br ON cc.boletosRegistradosId = br.id "
						." SET idRelatorio = ".$idRelatorio
						." ,valor_total = '".$valorTotal."'"
						." ,valor_liquido = '".$valorLiquido."'"
						." ,data_pagamento = '". date("Y-m-d")."'"
						." ,desconto_porcentagem = '".$desconto."'"
						." WHERE br.nosso_numero = ".$this->getnosso_numero()."; ";

				mysql_query($qryUpdate);
				
				// Realiza a inclusão de email para o contador informando o contador que o serviço premium foi contratado.
				$dadosContador = $this->PegaDadsoContador($this->getnosso_numero());
				
				// Pega os dados do contador.
				$contadorNome = $dadosContador['nome'];
				$email = $dadosContador['email'];
				
				// Realiza a chamada do método que realiza a gravação do dados para o envio do email.  
				// $this->GravaDadosEnvioEmail($contadorNome, $email, 'premium_contratado');
				
			} else {
				
				// Formata o valor Total.	
				$valorTotal = $this->getvalor_pago();
				
				// Pega o valor liquido e inclui o valor pago ou apagar.
				$valorLiquido = $this->getvalor_pago() - number_format(($this->getvalor_pago() / 2),2,'.','');
				
				// Atualiza o relatorio de cobranca do contador.
				$qryUpdate = " UPDATE cobranca_contador cc "
						." JOIN boletos_registrados br ON cc.boletosRegistradosId = br.id "
						." SET idRelatorio = ".$idRelatorio
						." ,valor_total = '".$valorTotal."'"
						." ,data_pagamento = '". date("Y-m-d")."'"
						." ,valor_liquido = '".$valorLiquido."'"
						." WHERE br.nosso_numero = ".$this->getnosso_numero()."; ";

				mysql_query($qryUpdate);
				
				if($tipo != 'Standard' && $tipo != 'Premium' && $tipo != 'avulso'){
					
					// Realiza a inclusão de email para o contador informando o contador que o serviço premium foi contratado.
					$dadosContador = $this->PegaDadsoContador($this->getnosso_numero());

					// Pega os dados do contador.
					$contadorNome = $dadosContador['nome'];
					$email = $dadosContador['email'];

					// Realiza a chamada do método que realiza a gravação do dados para o envio do email.  
					$this->GravaDadosEnvioEmail($contadorNome, $email, 'servico_contratado');
					
				}
			}
		}
		//Pega a data a que o pagamento se refere
		function getDataPagamentoRealizado(){
			$consulta = mysql_query("SELECT * FROM historico_cobranca WHERE idHistorico = '".$this->getboleto_registrado()->getid_historico()."' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			return $objeto_consulta['data_pagamento'];
		}
		//Verifica se ja existe um  'a vencer' no historico
		function ifExisteAVencer(){
			$consulta = mysql_query("SELECT * FROM historico_cobranca WHERE id = '".$this->getid_user()."' AND status_pagamento = 'a vencer' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			if( isset($objeto_consulta['id']) )
				return true;
			else
				return false;
		}
		//Insere um novo registro no historico de cobranca do usuario com o status 'a vencer'
		function insertAVencer($data){
			mysql_query("INSERT INTO historico_cobranca (id, data_pagamento, status_pagamento) VALUES ('" . $this->getid_user() . "', '" . $data . "', 'a vencer')");
		}
		//Retorna a quantidade de meeses do plano
		function getMesesPlano(){
			if( $this->getplano_user() == 'mensalidade' )
				return 1;
			if( $this->getplano_user() == 'trimestral' )
				return 3;
			if( $this->getplano_user() == 'semestral' )
				return 6;
			if( $this->getplano_user() == 'anual' )
				return 12;
		}
		//Vefifica se existe outras pendências na conta
		function existeOutrasPendencias(){
			$consulta = mysql_query("SELECT * FROM historico_cobranca WHERE id = '".$this->getid_user()."' AND status_pagamento = 'não pago' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			if( isset($objeto_consulta['id']) && $this->getplano_user() != 'mensalidade' )
				return true;
			else
				return false;
		}
		//Verifica se o plano do usuario é de multtiplos meses
		function ifPlanoMultiplosMeses(){
			if( $this->getplano_user() != 'mensalidade' )
				return true;
			else
				return false;
		}
		//Retorna a quantidade de 'não pago' no historico do usuario
		function getQuantidadeNaoPagos(){
			$consulta = mysql_query("SELECT * FROM historico_cobranca WHERE id = '".$this->getid_user()."' AND status_pagamento = 'não pago' ");
			return mysql_num_rows($consulta);
		}
		//Seta as pendências com status 'não pago' como pago
		function setarHistoricoPendencias(){
			$values = "
				status_pagamento='pago', 
				tipo_cobranca='boleto', 
				envio_email='enviado'
			";
			mysql_query("UPDATE historico_cobranca SET ".$values." WHERE status_pagamento = 'não pago' AND id = '".$this->getid_user()."' LIMIT 1 ");
		}
		//Loop para setar uma certa quantidade de 'não pago' como pago, esta quantidade é definida por plano_user - 1
		function AtualizarHistoricoNaoPagos($quantidade){
			for ( $i = 0 ; $i < $quantidade ; $i++ ) { 
				$this->setarHistoricoPendencias();
			}
		}
		//Atualiza um registro no historico do usuario com status a vencer com uma nova data
		function UpdateAVencer($data){
			$values = "
				data_pagamento='".$data."'
			";
			mysql_query("UPDATE historico_cobranca SET ".$values." WHERE status_pagamento = 'a vencer' AND id = '".$this->getid_user()."' LIMIT 1 ");
		}
		//Atualiza as pendências do usuário
		function atualizaPendencias(){
			$datas = new Datas();
			$this->UpdateAVencer( $datas->somarMes( $this->getMaiorDataHistorico(),( $this->getMesesPlano() - $this->getQuantidadeNaoPagos() ) ) );
			$this->AtualizarHistoricoNaoPagos( $this->getMesesPlano() - 1 ) ;
		}
		//Verifica se existe alguma pendencia na conta do usuario
		function existePendencia(){
			$consulta = mysql_query("SELECT * FROM historico_cobranca WHERE id = '".$this->getid_user()."' AND ( status_pagamento = 'não pago' OR status_pagamento = 'vencido' ) ");
			if( mysql_num_rows($consulta) > 0 )
				return true;
			else
				return false;
		}
		//Retorna a maior data no historico do usuario
		function getMaiorDataHistorico(){
			$consulta = mysql_query("SELECT * FROM historico_cobranca WHERE id = '".$this->getid_user()."' AND status_pagamento != 'a vencer' ORDER by data_pagamento DESC ");
			$objeto_consulta = mysql_fetch_array($consulta);
			return $objeto_consulta['data_pagamento'];
		}
		//Define o proximo pagamento do usuario ativo
		function defineProximoPagamentoAtivo(){
			$datas = new Datas();
			if( !$this->ifExisteAVencer() )
				$this->insertAVencer($datas->somarMes($this->getDataPagamentoRealizado(),$this->getMesesPlano()));
			else
				$this->UpdateAVencer($datas->somarMes($this->getDataPagamentoRealizado(),$this->getMesesPlano()));
		}
		//Define o proximo pagamento do usuario inativo
		function defineProximoPagamentoInativo(){
			$datas = new Datas();

			if( !$this->ifExisteAVencer() )//Se não existe pagamento a vencer
				if( !$this->existePendencia() && !$this->ifPlanoMultiplosMeses() )//Se nao existem pendencias e tem plano mensal
					$this->insertAVencer($datas->somarMes($this->getDataPagamentoRealizado(),$this->getMesesPlano()));
				else//
					$this->insertAVencer($datas->somarMes($this->getMaiorDataHistorico(),$this->getMesesPlano()));
			else
				if( !$this->existePendencia() )//Se nao existem pendencias
						$this->UpdateAVencer($datas->somarMes($this->getMaiorDataHistorico(),$this->getMesesPlano()));
			if( $this->existeOutrasPendencias() )//Se existem outras pendências na conta
				if( $this->ifPlanoMultiplosMeses() )//Se temos um plano de multiplos meses
					$this->atualizaPendencias();//Atualiza as pendencias de acordo com o plano de multiplos meses escolhido
		}
		//Define o proximo pagamento do usuario Demo
		function defineProximoPagamentoDemo(){
			$datas = new Datas();
			$this->insertAVencer($datas->somarMes($this->getDataPagamentoRealizado(),$this->getMesesPlano()));
		}
		//Define o proximo pagamento do usuario Demo Inativo
		function defineProximoPagamentoDemoInativo(){
			$datas = new Datas();
			$this->insertAVencer($datas->somarMes(date("Y-m-d"),$this->getMesesPlano()));
		}
		//Define o proximo pagamento do usuario Cancelado
		function defineProximoPagamentoDemoCancelado(){
			$datas = new Datas();
			$this->insertAVencer($datas->somarMes(date("Y-m-d"),$this->getMesesPlano()));
		}
		//Define o proximo pagamento para cada status de usuario
		function definirProximoPagamento(){
			$user = new Dados_user($this->getid_user());
			$this->setstatus_user($user->getstatus());
			if( $this->getstatus_user() == 'ativo' )
				$this->defineProximoPagamentoAtivo();
			else if( $this->getstatus_user() == 'inativo' )
				$this->defineProximoPagamentoInativo();
			else if( $this->getstatus_user() == 'demo' )
				$this->defineProximoPagamentoDemo();
			else if( $this->getstatus_user() == 'demoInativo' )
				$this->defineProximoPagamentoDemoInativo();
			else if( $this->getstatus_user() == 'cancelado' )
				$this->defineProximoPagamentoDemoCancelado();
		}
		//Agenda um enviao de email para o usuario
		function agendaEmail(){
			$user = new Dados_user($this->getid_user());
			$campos = "(`tipo_mensagem`, `nome`, `email`, `status`, `data`)";
			$values = "('boleto_compensado', '".$user->getassinante()."', '".$user->getemail()."', '0','".date("Y-m-d H:m:s")."')";
			mysql_query("INSERT INTO `envio_emails_cobranca` ".$campos." VALUES ".$values." ");
		}
		
		// Pega o contador responsavel por serviço atravesl do nosso numero.
		function PegaDadsoContador($nossoNumero) {
			
			$consulta = mysql_query(" SELECT nome, email FROM cobranca_contador cc "
									." JOIN boletos_registrados br ON cc.boletosRegistradosId = br.id "
									." JOIN dados_contador_balanco dc ON dc.id = cc.contadorId "
									." WHERE br.nosso_numero = '".$nossoNumero."';");
			
			return mysql_fetch_array($consulta);
		}
		
		// Retorna uma lista de funcionários de acordo com a empresa.
		public function GravaDadosEnvioEmail($contadorNome, $email, $tipo_mensagem){

			// INSERINDO OS DADOS DO ASSINANTE COM BOLETO A VENCER NA TABELA DE ENVIO DE MENSAGENS
			$qry = "INSERT INTO envio_emails_cobranca SET tipo_mensagem = '".$tipo_mensagem."', nome = '".$contadorNome."', email = '".$email."', status = 0, data = NOW()";

			// Executa a inclusão;
			mysql_query($qry);

			// Retorna o código do envio de email.
			return mysql_insert_id();
		}
		
		//Ativo um usuario
		function ativarusuario(){
			mysql_query("UPDATE login SET `status`= 'ativo' WHERE idUsuarioPai = '".$this->getid_user()."' ");
		}
		//Verifica se existe uao menos um registro nao pago no historico do usuario
		function ifNaoPago(){
			$consulta = mysql_query("SELECT * FROM historico_cobranca WHERE id = '".$this->getid_user()."' AND status_pagamento = 'não pago' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			if( isset($objeto_consulta['id']) )
				return true;
			else
				return false;
		}
		//Define o status do usuario
		function definirStatusUser(){
			if( !$this->ifNaoPago() )//Se não existir registros com 'não pago' no historico do usuario, ativa a conta
				$this->ativarusuario();
		}
		//Seta o boleto como pago
		function setarBoletoPago(){
			mysql_query("UPDATE boletos_registrados SET `status`= 'pago' WHERE nosso_numero = '".$this->getnosso_numero()."' ");
		}
		//Valida o valor pago pelo usuario
		function validarValorPago(){
			if( $this->getboleto_registrado()->gettipo() == 'mensalidade' ){
				//Se o valor pago pelo user for maior que o valor que esperamos receber, valida o pagamento e realiza os procedimentos, caso contrario, informa erro e identifica o usuario e o pagamento com erro
				if( $this->getvalor_pago() >= $this->getboleto_registrado()->getvalor() ){
					$this->setarHistorico();//Define o registro no historico como pago
					$this->inserirRelatorioCobranca();//Insere os dados no relatorio de cobranca
					$this->agendaEmail();//Agend o envio de email para o usuario informandop o resultado da acao
					$this->definirProximoPagamento();//Define a data do proximo pagamento para o usuario
					$this->definirStatusUser();//Define o status apos o pagamento
					$this->setarBoletoPago();//Seta o boleto como pago no banco de dados
					return true;
				}	
				else{
					return false;
				}
			}
			else{
				if( $this->getvalor_pago() >= $this->getboleto_registrado()->getvalor() ){
					// $this->setarHistorico();//Define o registro no historico como pago
					$this->inserirRelatorioCobranca();//Insere os dados no relatorio de cobranca
					// $this->agendaEmail();//Agend o envio de email para o usuario informandop o resultado da acao
					// $this->definirProximoPagamento();//Define a data do proximo pagamento para o usuario
					// $this->definirStatusUser();//Define o status apos o pagamento
					$this->setarBoletoPago();//Seta o boleto como pago no banco de dados
					return true;
				}	
				else{
					return false;
				}
			}
		}
		//Valida o pagamento, sendo que caso o pagament
		function validarPagamento(){

			if( !$this->validarValorPago() )
				$this->relatarErro("Erro com o boleto. ID: ".$this->getid_user()."<br>Nosso Número:".$this->getboleto_registrado()->getnosso_numero());
		}
		//Função que busca o nosso numero no banco, caso nao encontre, retorna false e paralisa o andamento da rotina
		function buscarDadosBoleto(){
			//Procura no banco o nosso numero do boleto
			$consulta = mysql_query("SELECT * FROM boletos_registrados WHERE  status = 'pendente' AND nosso_numero = ".$this->getnosso_numero()." ");
			$objeto_consulta = mysql_fetch_array($consulta);
			//Define um objeto com os dados retornados do banco
			$this->setboleto_registrado(new Boleto_registrado($objeto_consulta));
			$this->setplano_user($this->getboleto_registrado()->getplano());
			$this->settipo_plano($this->getboleto_registrado()->gettipoPlano());
			$this->setid_user($this->getboleto_registrado()->getid_user());
			//Verifica se o nosso numero esta registrado no banco
			if( $this->getboleto_registrado()->getnosso_numero() == $this->getnosso_numero() )
				return true;
			else
				return false;
		}
		//Define as remessas que foram aceitas ou recusadas de acordo com o arquivo retorno do dia
		function setarStatusRemessaRetorno($status){
			if( $status == 2 ){
				mysql_query("UPDATE boletos_registrados SET remessa_aceita = '2' WHERE nosso_numero = '".$this->getnosso_numero()."' ");
			}
			else if( $status == 3 ){
				mysql_query("UPDATE boletos_registrados SET remessa_aceita = '3'  WHERE nosso_numero = '".$this->getnosso_numero()."' ");
			}
			else if( $status == 9 ){
				mysql_query("UPDATE boletos_registrados SET status = 'não pago' WHERE nosso_numero = '".$this->getnosso_numero()."' ");
			}
		}
		//Valída o pagamento realizado, buscando nos registros os dados do boleto em questao atraves do nosso numero e do id do user
		function validarBoleto(){
			//Caso o boleto em questao exista no banco, valida o pagamento
			if( $this->buscarDadosBoleto() )
				$this->validarPagamento();
			else//Caso contrario, informa erro, mostrando o id do usuario e o nosso numero do boleto em questao
				echo $this->getid_user().'<br>Nosso número não encontrado: '.$this->getnosso_numero().'<br><br>';
		}
		function __construct(){
		}
	}	

	//// Codig basico para utilizae essa classe
	// $boleto_cobranca = new Cobranca_boleto();
	// //Define o Nosso Número do boleto
	// $boleto_cobranca->setnosso_numero("28509430186930000");
	// //Define o valor pago no boleto
	// $boleto_cobranca->setvalor_pago(177);
	// //Valida o boleto
	// $boleto_cobranca->validarBoleto();

?>
















