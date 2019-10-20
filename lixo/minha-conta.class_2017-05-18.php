<?php 
	// include 'conect.php';
	// include 'datas.class.php';
	
	// Realiza a inclusão classe .
	require_once('classes/numero_extenso.php'); 

	class Minha_conta{
		
		private $datas;

		private $id_user;
		private $status_login;
		private $email_usuario;
		private $assinante;
		private $plano;
		private $TipoPlano;
		private $data_inclusao;
		private $forma_pagameto;
		private $pref_telefone;
		private $telefone;
		private $senha;

		private $dados_cobranca_assinante;
		private $dados_cobranca_pref_telefone;
		private $dados_cobranca_telefone;
		private $dados_cobranca_data_inclusao;
		private $dados_cobranca_forma_pagameto;
		private $dados_cobranca_numero_cartao;
		private $dados_cobranca_codigo_seguranca;
		private $dados_cobranca_nome_titular;
		private $dados_cobranca_data_validade;
		private $dados_cobranca_sacado;
		private $dados_cobranca_documento;
		private $dados_cobranca_endereco;
		private $dados_cobranca_numero;
		private $dados_cobranca_complemento;
		private $dados_cobranca_bairro;
		private $dados_cobranca_cep;
		private $dados_cobranca_cidade;
		private $dados_cobranca_uf;
		private $dados_cobranca_tipo;
		private $dados_cobranca_plano;
		private $dados_cobranca_tipo_plano;

		private $total_empresas;

		private $arrEstados;

		private $dados_cartao_numero_cartao;
		private $dados_cartao_nome_titular;
		private $dados_cartao_bandeira;
		private $valor_desconto;
	
		function getdados_cartao_bandeira(){
			return $this->dados_cartao_bandeira;
		}
		function setdados_cartao_bandeira($string){
			$this->dados_cartao_bandeira = $string;
		}
		function getdados_cartao_nome_titular(){
			return $this->dados_cartao_nome_titular;
		}
		function setdados_cartao_nome_titular($string){
			$this->dados_cartao_nome_titular = $string;
		}
		function getdados_cartao_numero_cartao(){
			return $this->dados_cartao_numero_cartao;
		}
		function setdados_cartao_numero_cartao($string){
			$this->dados_cartao_numero_cartao = $string;
		}
		function getdados_cobranca_plano(){
			return $this->dados_cobranca_plano;
		}
		function setdados_cobranca_plano($string){
			$this->dados_cobranca_plano = $string;
		}
		
		function getdados_cobranca_tipo_plano(){
			return $this->dados_cobranca_tipo_plano;
		}
		function setdados_cobranca_tipo_plano($string){
			$this->dados_cobranca_tipo_plano = $string;
		}
		
		function getdados_cobranca_tipo(){
			return $this->dados_cobranca_tipo;
		}
		function setdados_cobranca_tipo($string){
			$this->dados_cobranca_tipo = $string;
		}
		function getdados_cobranca_uf(){
			return $this->dados_cobranca_uf;
		}
		function setdados_cobranca_uf($string){
			$this->dados_cobranca_uf = $string;
		}
		function getdados_cobranca_cidade(){
			return $this->dados_cobranca_cidade;
		}
		function setdados_cobranca_cidade($string){
			$this->dados_cobranca_cidade = $string;
		}
		function getdados_cobranca_cep(){
			return $this->dados_cobranca_cep;
		}
		function setdados_cobranca_cep($string){
			$this->dados_cobranca_cep = $string;
		}
		function getdados_cobranca_bairro(){
			return $this->dados_cobranca_bairro;
		}
		function setdados_cobranca_bairro($string){
			$this->dados_cobranca_bairro = $string;
		}
		function getdados_cobranca_complemento(){
			return $this->dados_cobranca_complemento;
		}
		function setdados_cobranca_complemento($string){
			$this->dados_cobranca_complemento = $string;
		}
		function getdados_cobranca_numero(){
			return $this->dados_cobranca_numero;
		}
		function setdados_cobranca_numero($string){
			$this->dados_cobranca_numero = $string;
		}
		function getdados_cobranca_endereco(){
			return $this->dados_cobranca_endereco;
		}
		function setdados_cobranca_endereco($string){
			$this->dados_cobranca_endereco = $string;
		}
		function getdados_cobranca_documento(){
			return $this->dados_cobranca_documento;
		}
		function setdados_cobranca_documento($string){
			$this->dados_cobranca_documento = $string;
		}
		function getdados_cobranca_sacado(){
			return $this->dados_cobranca_sacado;
		}
		function setdados_cobranca_sacado($string){
			$this->dados_cobranca_sacado = $string;
		}
		function getdados_cobranca_data_validade(){
			return $this->dados_cobranca_data_validade;
		}
		function setdados_cobranca_data_validade($string){
			$this->dados_cobranca_data_validade = $string;
		}
		function getdados_cobranca_nome_titular(){
			return $this->dados_cobranca_nome_titular;
		}
		function setdados_cobranca_nome_titular($string){
			$this->dados_cobranca_nome_titular = $string;
		}
		function getdados_cobranca_codigo_seguranca(){
			return $this->dados_cobranca_codigo_seguranca;
		}
		function setdados_cobranca_codigo_seguranca($string){
			$this->dados_cobranca_codigo_seguranca = $string;
		}
		function getdados_cobranca_numero_cartao(){
			return $this->dados_cobranca_numero_cartao;
		}
		function setdados_cobranca_numero_cartao($string){
			$this->dados_cobranca_numero_cartao = $string;
		}
		function getdados_cobranca_forma_pagameto(){
			return $this->dados_cobranca_forma_pagameto;
		}
		function setdados_cobranca_forma_pagameto($string){
			$this->dados_cobranca_forma_pagameto = $string;
		}
		function getdados_cobranca_data_inclusao(){
			return $this->dados_cobranca_data_inclusao;
		}
		function setdados_cobranca_data_inclusao($string){
			$this->dados_cobranca_data_inclusao = $string;
		}
		function getdados_cobranca_telefone(){
			return $this->dados_cobranca_telefone;
		}
		function setdados_cobranca_telefone($string){
			$this->dados_cobranca_telefone = $string;
		}
		function getdados_cobranca_pref_telefone(){
			return $this->dados_cobranca_pref_telefone;
		}
		function setdados_cobranca_pref_telefone($string){
			$this->dados_cobranca_pref_telefone = $string;
		}
		function getdados_cobranca_assinante(){
			return $this->dados_cobranca_assinante;
		}
		function setdados_cobranca_assinante($string){
			$this->dados_cobranca_assinante = $string;
		}
		function getarrEstados(){
			return $this->arrEstados;
		}
		function setarrEstados($string){
			$this->arrEstados = $string;
		}
		function gettotal_empresas(){
			if( $this->total_empresas == 0 )
				return 1;
			else
				return $this->total_empresas;
		}
		function settotal_empresas($string){
			$this->total_empresas = $string;
		}
		function getsenha(){
			return $this->senha;
		}
		//Define a senha do usuario, colocando * no lugar de cada algarismo
		function setsenha($string){
			$passNum = 0;
			while ($passNum < strlen($string)) {
			    $senha   = $senha . '*';
			    $passNum = $passNum + 1;
			}
			$this->senha = $senha;
		}
		function gettelefone(){
			return $this->telefone;
		}
		function settelefone($string){
			$this->telefone = $string;
		}
		function getpref_telefone(){
			return $this->pref_telefone;
		}
		function setpref_telefone($string){
			$this->pref_telefone = $string;
		}
		function getforma_pagameto(){
			return $this->forma_pagameto;
		}
		function setforma_pagameto($string){
			$this->forma_pagameto = $string;
		}
		function getdata_inclusao(){
			$aux = explode(' ', $this->data_inclusao);
			return $aux[0];
		}
		function setdata_inclusao($string){
			$this->data_inclusao = $string;
		}
		function getplano(){
			return $this->plano;
		}
		function setplano($string){
			$this->plano = $string;
		}
		
		function getTipoPlano(){
			return $this->TipoPlano;
		}
		function setTipoPlano($string){
			$this->TipoPlano = $string;
		}
				
		function getassinante(){
			return $this->assinante;
		}
		function setassinante($string){
			$this->assinante = $string;
		}
		function getemail_usuario(){
			return $this->email_usuario;
		}
		function setemail_usuario($string){
			$this->email_usuario = $string;
		}
		function getstatus_login(){
			return $this->status_login;
		}
		function setstatus_login($string){
			$this->status_login = $string;
		}
		function getid_user(){
			return $this->id_user;
		}
		function setid_user($string){
			$this->id_user = $string;
		}
		function getValorDesconto() {
			return $this->valor_desconto;	
		}
		function setValorDesconto($valor) {
			$this->valor_desconto = $valor;	
		}
		
		function getFraseNotificacaoSucesso(){
			$string = '
				<div style="clear: both;">
			        <strong style="color: #a61d00;">Transação efetuada com sucesso!</strong><br>
			        <br>
			    </div>
			';
			return $string;
		}
		function getFraseNotificacaoErro(){
			$string = '
				<div style="clear: both;">
			        <strong style="color: #a61d00;">O pagamento não pode ser realizado, pois a sua operadora não autorizou a transação.</strong><br>
			        <br>
			    </div>
			';
			return $string;
		}
		function getFraseNotificacaoErroInvalido(){
			$string = '
				<div style="clear: both;">
			        <strong style="color: #a61d00;">O pagamento não pode ser realizado, pois o cartão informado é inválido.</strong><br>
			        <br>
			    </div>
			';
			return $string;
		}
		//Insere a mensagem de notificação caso haja algum erro no pagamento por cartao
		function getMensagensNotificacao(){
			if( isset( $_GET['sucesso'] ) )
				return $this->getFraseNotificacaoSucesso();
			if( isset( $_GET['erro_cartao'] ) && $_GET['erro_cartao'] == 'invalido' )
				return $this->getFraseNotificacaoErroInvalido();
			if( isset( $_GET['erro_cartao'] ) )
				return $this->getFraseNotificacaoErro();

		}
		function getDadosCobranca(){
			
			$consulta = mysql_query("SELECT * FROM dados_cobranca WHERE id = '".$this->getid_user()."' ");
			$objeto_consulta = mysql_fetch_array($consulta);

			$this->setdados_cobranca_assinante($objeto_consulta['assinante']);
			$this->setdados_cobranca_pref_telefone($objeto_consulta['pref_telefone']);
			$this->setdados_cobranca_telefone($objeto_consulta['telefone']);
			$this->setdados_cobranca_data_inclusao($objeto_consulta['data_inclusao']);
			$this->setdados_cobranca_forma_pagameto($objeto_consulta['forma_pagameto']);
			$this->setdados_cobranca_numero_cartao($objeto_consulta['numero_cartao']);
			$this->setdados_cobranca_codigo_seguranca($objeto_consulta['codigo_seguranca']);
			$this->setdados_cobranca_nome_titular($objeto_consulta['nome_titular']);
			$this->setdados_cobranca_data_validade($objeto_consulta['data_validade']);
			$this->setdados_cobranca_sacado($objeto_consulta['sacado']);
			$this->setdados_cobranca_documento($objeto_consulta['documento']);
			$this->setdados_cobranca_endereco($objeto_consulta['endereco']);
			$this->setdados_cobranca_numero($objeto_consulta['numero']);
			$this->setdados_cobranca_complemento($objeto_consulta['complemento']);
			$this->setdados_cobranca_bairro($objeto_consulta['bairro']);
			$this->setdados_cobranca_cep($objeto_consulta['cep']);
			$this->setdados_cobranca_cidade($objeto_consulta['cidade']);
			$this->setdados_cobranca_uf($objeto_consulta['uf']);
			$this->setdados_cobranca_tipo($objeto_consulta['tipo']);
			$this->setdados_cobranca_plano($objeto_consulta['plano']);
			$this->setdados_cobranca_tipo_plano($objeto_consulta['tipo_plano']);
		
		}
		function getConfiguracoes($tipoPlano = 'S'){
			$consulta = mysql_query("SELECT * FROM configuracoes WHERE tipo_plano = '".$tipoPlano."'");
			return $consulta;
		}
		function getConfiguracoesPremiun($configuracao){
			$consulta = mysql_query("SELECT * FROM configuracoes WHERE configuracao = '".$configuracao."' AND tipo_plano = 'P'");
			return $consulta;
		}
		function getConfiguracoesTipoPlano($configuracao, $tipoPlano = 'P'){
			$consulta = mysql_query("SELECT * FROM configuracoes WHERE configuracao = '".$configuracao."' AND tipo_plano = '".$tipoPlano."'");
			return $consulta;
		}		
		function exibirNomePlano($item){
			
			// Pega o valor e implementa o desconto.
			$valor = $item['valor']; // - $this->getValorDesconto();
			
			if( $item['configuracao'] == 'mensalidade' ){
				return 'Mensal: '.$this->tomoney($valor);
			}
			if( $item['configuracao'] == 'trimestral' ){
				return 'Trimestral: '.$this->tomoney($valor);	
			}
			if( $item['configuracao'] == 'semestral' ){
				return 'Semestral: '.$this->tomoney($valor).' ('.($this->tomoney($valor / 6)).' por mês)';
			}
			if( $item['configuracao'] == 'anual' ){
				return 'Anual: '.$this->tomoney($valor).' ('.($this->tomoney($valor / 12)).' por mês)';
			}
		}
		function getPlanospagamento(){
			
			// Variável qie recebera o input com o plano cadastrado.
			$planoNoBanco ='';
			
			$string = '
				<table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
					<tbody>';
			// Pega o Plano de acordo com o tipo do plano informado.
			$itens = $this->getConfiguracoes($this->getTipoPlano());

			
			while( $item = mysql_fetch_array($itens) ){
				
				$item2 = mysql_fetch_array($this->getConfiguracoesTipoPlano($item['configuracao']));
	
				// Pega o valor do plano prêmio com desconto.
				$valor2 = $item2["valor"];
				
				$valorExtenso = 'R$ '.number_format($valor2, 2,",",".").' ('.GExtenso::moeda(number_format(str_replace(",",".",str_replace(".","",$valor2)),2,"","")).')';
				if($this->getTipoPlano() == 'P') {
					$itemAux = mysql_fetch_array($this->getConfiguracoesTipoPlano($item['configuracao'], 'S'));
					$classOne = 'classP';
					$classTwo = 'classS';
				} else {
					$itemAux = mysql_fetch_array($this->getConfiguracoesTipoPlano($item['configuracao'], 'P'));
					$classOne = 'classS';
					$classTwo = 'classP';
				}
				
				//getConfiguracoesTipoPlano
				$string .= '
						<tr>
							<td>
								<input type="radio" class="atualizarValorAvencer" tabela="dados_cobranca" campo="plano" name="radPlano" id="radPlano_'.$item['configuracao'].'" value="'.$item['configuracao'].'" '.$this->checked($item['configuracao'],$this->getplano()).'>
							</td>
							<td>
								<label class="'.$classOne.'" for="radPlano_mensalidade">'.$this->exibirNomePlano($item).'</label>
								<label class="'.$classTwo.'" for="radPlano_mensalidade" style="display:none;">'.$this->exibirNomePlano($itemAux).'</label>
								
								<input type="hidden" class="inputValplano" id="id_'.$item["configuracao"].'" value="'.$valor2.'" />
								<input type="hidden" id="extenso_'.$item["configuracao"].'" value="'.$valorExtenso.'">
							</td>
						</tr>
				';
				
				if($this->checked($item['configuracao'],$this->getplano())) {
					$planoNoBanco = '<input id="planoNoBanco" type="hidden" value="'.$item['configuracao'].'">';		
				}
			}
			$string .= '
					</tbody>
				</table>
				'.$planoNoBanco.'
			';
			echo $string;
		}
		
		public function inputComTipoPlano() {

			$tag = '';
			
			// Texto que será apresentado para a explicação dos planos.
			$textStandard = ' - Você cuida sozinho de suas obrigações fiscais';
			$textPremium = ' - Um contador gera as guias e declarações para você';

			switch($this->getTipoPlano()) {
				
				case 'P':
					$tag .= '<input id="tipo_plano_S" class="inputPlano" id="tipo_plano_S" type="radio" name="tipo_plano" campo="tipo_plano" tabela="dados_cobranca" value="S"/> <span><b>Standard</b>'.$textStandard.' </span> <br>'
    		            .'<input id="tipo_plano_P" class="inputPlano" type="radio" name="tipo_plano" campo="tipo_plano" tabela="dados_cobranca" value="P" checked="checked"/> <span><b>Premium</b>'.$textPremium.' </span> <br>';
					break;	
				default:
					$tag .= '<input id="tipo_plano_S" class="inputPlano" type="radio" name="tipo_plano" campo="tipo_plano" tabela="dados_cobranca" value="S" checked="checked"/> <span><b>Standard</b>'.$textStandard.' </span> <br>'
    		            .'<input id="tipo_plano_P" class="inputPlano" type="radio" name="tipo_plano" campo="tipo_plano" tabela="dados_cobranca" value="P"/> <span><b>Premium</b>'.$textPremium.' </span> <br>';
					break;
			}
			
			$tag .= '	<input id="formContador" type="hidden" name="contadorId" value="" />' // Este campo sera preechido apos a confirmaçao dos termos do contrato.
					.'	<input id="planoAtual" type="hidden" name="planoAtual" value="'.$this->getTipoPlano().'" />'
            		.'	<input id="formValor" type="hidden" name="valor" value="" />'
					.'	<input id="formContatro" type="hidden" name="contratoId" value="2" />';
			
			echo $tag;
		}
		
		function getPlanoUsuario(){
			
			// Verifica o tipo do plano.
			switch($this->getTipoPlano()) {

				case 'P':
					$tipoPlano = 'Premium';
					break;
					
				default:
					$tipoPlano = 'Standard';
					break;
			}
			
			// Verifica o plano
			switch($this->getplano()) {
			
				case 'trimestral':
					$plano = "Trimestral";
					break;
			
				case 'semestral':
					$plano = "Semestral";
					break;
				case 'anual':
					$plano = "Anual";
					break;		
			
				case 'mensalidade':
					$plano = "Mensal";
					break;
			}
			
			// Define o retorno do plano.	
			// return $tipoPlano.' - '.$plano;
			return $this->getTipoPlano().' - '.$plano;
		}
		
		function selected($value, $prev){
		    return $value == $prev ? ' selected="selected"' : '';
		}
		function checked($value, $prev){
		    return $value == $prev ? ' checked="checked"' : '';
		}
		function toMoney($string){
			return 'R$ '.number_format($string,2,',','.');
		}
		//Funcao que retorna o valor do plano de acordo com o plano atual do usuario
		function getValorMensalidade($tipoPlano = 'S'){
			$planos = array();
			$consulta = mysql_query("SELECT * FROM configuracoes WHERE tipo_plano = '".$tipoPlano."'");//Busca os planos cadastrados no banco
			while ($configuracao = mysql_fetch_array($consulta)) {
			    $planos[$configuracao['configuracao']] = $configuracao['valor'];//Monta um array com cada plano
			}
			if($this->getplano()) {
				return floatval($planos[$this->getplano()]);//Retorna o valor do plano do usuario
			} else {
				return floatval($planos['mensalidade']);//Retorna o valor do plano do usuario
			}
		}
		function ifFormDadosCobranca(){
			
			return true;

		}
		function ifDadosCobrancaPreenchidos(){
			
			if( $this->getplano() != '' && $this->getforma_pagameto() != '' && $this->ifFormDadosCobranca() == true )
				return true;
			else
				return false;

		}
		//Funcao que busca o proximo pagamento do usuario demo
		function proximoPagamentoDemo(){
			$consulta = mysql_query("SELECT * FROM historico_cobranca WHERE id = '".$this->getid_user()."' ORDER BY data_pagamento DESC ");
			$objeto_consulta = mysql_fetch_array($consulta);
			return $objeto_consulta['data_pagamento'];
		}
		//Funcao que retorna a quantidade de dias restantes para o usuario demo, subtraindo a daat do vencimento da fatura pela data de inclusao, resultando em um valor entre 0 e 31
		function diasRestantesDemo(){
			return $this->datas->diferencaData( $this->proximoPagamentoDemo() , date("Y-m-d") );		
		}
		//Retorna resultado sql contendo todos as faturas não pagas
		function getSqlNaoPagos(){
			$consulta = mysql_query("SELECT * FROM historico_cobranca WHERE id = '".$this->getid_user()."' AND ( status_pagamento = 'não pago' OR status_pagamento = 'vencido' ) ");
			return $consulta;
		}
		//Retorna resultado sql contendo todos as faturas vencidas
		function getSqlVencidos(){
			$consulta = mysql_query("SELECT * FROM historico_cobranca WHERE id = '".$this->getid_user()."' AND status_pagamento = 'vencido' ");
			return $consulta;
		}
		//Calcula o valor da mensalidade
		function calcularMensalidade($id){
			$consulta = mysql_query("SELECT * FROM boletos_registrados WHERE id_historico = '".$id."' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			if( $objeto_consulta['valor'] != 0 )
				return floatval($objeto_consulta['valor']);
			else
				return floatval( $this->getValorMensalidade($this->getTipoPlano()) * $this->gettotal_empresas() );
		}
		//Calcula o valor pendente nas faturas do usuario, multiplicando os meses devidos pelo valor da mensalidade(plano*qtd_empresas)
		function calcularValorDevendo($quantidade){
			return $quantidade * $this->calcularMensalidade();
		}
		//Define o valor pendente nas faturas do usuario
		function valorPagamentoPendente(){
			$pendencias = $this->getSqlNaoPagos();
			$quantidade = mysql_num_rows($pendencias);
			return $this->calcularValorDevendo($quantidade);
		}
		//Funcao que retorna o valor devido pelo usuario com pagamentos vencidos
		function getValorpagamentosVencidos(){
			$vencidos = $this->getSqlVencidos();//Pega a SEL ddos pagamentos vencidos
			$quantidade = mysql_num_rows($vencidos);//Pega a quantidade de pagamentos vencidos
			return $this->calcularValorDevendo($quantidade);//retorna o valor devido, de acordo com a quantidade de pagaamentos vencidos e o valor da mensalidade
		}
		//Verifica se existe alguma fatura vencida para o usuario
		function ifFaturaVencida(){
			$consulta = mysql_query("SELECT * FROM historico_cobranca WHERE id = '".$this->getid_user()."' AND status_pagamento = 'vencido' ");
			if( mysql_num_rows($consulta) > 0 )
				return true;
			else
				return false;
		}
		//Retorna a data da fatura com status vencido
		function getDataFaturaPendente(){
			$consulta = mysql_query("SELECT * FROM historico_cobranca WHERE id = '".$this->getid_user()."' AND status_pagamento = 'vencido' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			return $objeto_consulta['data_pagamento'];
		}
		//Insere o texto informativo da conta para o usuario demo inativo
		function getTextoDemoInativo(){
			$string = '
				<span class="tituloVermelho">Prazo de avaliação esgotado</span><br />
	        	<br /> 
	        	Para continuar utilizando o Contador Amigo, você deverá confirmar sua assinatura.<br />
	        	<br />
	        	<strong>Preencha os dados de cobrança ao lado</strong> e clique no botão &quot;Imprimir Boleto&quot; 
	        	para efetuar o pagamento da primeira mensalidade';
        	if( $this->ifDadosCobrancaPreenchidos() ){
        		$string .= ', no valor de <span class="destaque valorProximoAVencer">'.$this->tomoney($this->getValorMensalidade($this->getTipoPlano())).'</span>';
        	}
        	$string .='.<br><br />';
        	echo $string;
		}
		//Retorna o id do registro a vencer no historico de cobranca do demo
		function getIdHistoricoAvencerDemoInativo(){
			$consulta = mysql_query("SELECT * FROM historico_cobranca WHERE id = '".$this->getid_user()."' AND status_pagamento = 'não pago' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			return $objeto_consulta['idHistorico'];
		}
		//Retorna o botao de pagamento do usuario demo inativo
		function getDadosPagamentoDemoInativo(){
			if( $this->getforma_pagameto() == 'boleto' ){
				$id_historico = "0";
				$string = '<div via="0" id="'.$id_historico.'"><center><input type="button" name="button2" id="button2" class="ativarAssinatura" value="Imprimir boleto"></center></div>';
				echo $string;
			}
			else{
				$id_historico = "0";
				$string = '<div tipo="ativarAssinatura" ><center><input type="button" name="button2" id="button2" class="pagarCartaoAtrasados" value="Ativar Assinatura"></center></div>';
				echo $string;
			}
		}
		//Insere o texto informativo da conta para o usuario demo
		function getTextoDemo(){
			$string = '
				<span class="tituloVermelho">Período de avaliação gratuita</span><br />
	            <br />
	            Você poderá utilizar o Contador Amigo por mais <span class="destaque valorProximoAVencer">'.$this->diasRestantesDemo().'</span> dias.<br />
	            <br />
	            Para confirmar sua assinatura, <strong>preencha os dados de cobrança ao lado </strong>e realize o pagamento da primeira mensalidade';
        	if( $this->ifDadosCobrancaPreenchidos() ){
        		$string .= ', no valor de <span class="destaque valorProximoAVencer">R$ '.$this->tomoney($this->getValorMensalidade($this->getTipoPlano())).'</span>';
        	}
        	$string .='.<br><br />';
        	echo $string;
		}
		//Retorna o id do registro a vencer no historico de cobranca do demo
		function getIdHistoricoAvencerDemo(){
			$consulta = mysql_query("SELECT * FROM historico_cobranca WHERE id = '".$this->getid_user()."' AND status_pagamento = 'a vencer' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			return $objeto_consulta['idHistorico'];
		}
		//Retorna o botao de pagamento do demo
		function getDadosPagamentoDemo(){
			if( $this->getforma_pagameto() == 'boleto' ){
				$id_historico = $this->getIdHistoricoAvencerDemo();
				$string = '<div via="'.$this->verificarViaBoleto($item['idHistorico']).'" id="'.$id_historico.'"><center><input type="button" name="button2" id="button2" class="gerarBoleto" value="Imprimir boleto"></center></div>';
				echo $string;
			}
			else{
				$id_historico = "0";
				$string = '<div tipo="ativarAssinatura"><center><input type="button" name="button2" id="button2" class="pagarCartaoAVencer" value="Ativar Assinatura"></center></div>';
				echo $string;
			}
		}
		//Insere o texto informativo da conta para o usuario inativo
		function getTextoInativo(){
			$string = '
				<span class="tituloVermelho">Conta Inativa</span><br />
	            <br />
	            Consta em nosso sistema débito(s) pendente(s)'; 
        	// if( $this->ifDadosCobrancaPreenchidos() ){
        	// 	$string .= ' no valor de <input type="hidden" id="pendencias" value="'.mysql_num_rows($this->getSqlNaoPagos()).'"><span class="destaque valorProximoAVencer">'.$this->tomoney($this->valorPagamentoPendente()).'</span>';
        	// }
        	$string .='.<br><br />';
        	echo $string;
		}
		//Pega os dados de pagamento para o usuario inativo
		function getDadosPagamentoInativo(){
			$this->getFaturasEmAberto();
			echo '<br />';
			$this->faturasPagas();
		}
		//Insere o texto informativo da conta para o usuario cancelado
		function getTextoCancelado(){
			$string = '
		        <span class="tituloVermelho">Assinatura Cancelada</span><br />
		        <br /> 
		       	Se você deseja voltar ao Contador Amigo, aqui vai uma proposta: reative agora mesmo sua conta, pagando apenas 
		       	<span class="destaque valorProximoAVencer">'.$this->tomoney($this->getValorMensalidade($this->getTipoPlano())).'</span> Seus débitos anteriores serão perdoados. <br /><br /> 
		        Para mudar o plano, dados e forma de pagamento, altere os campos ao lado.
		        Prefira sempre o pagamento por cartão. É mais prático e a liberação do acesso, imediata.';
        	$string .='<br><br />';
        	echo $string;
		}
		//Pega o botao de pagamento para o usuario cancelado
		function getDadosPagamentoCancelado(){
			if( $this->getforma_pagameto() == 'boleto' ){
				$id_historico = "0";
				$string = '<div via="'.$this->verificarViaBoleto($item['idHistorico']).'" id="'.$id_historico.'"><center><input type="button" name="button2" id="button2" class="ativarAssinatura" value="Ativar Assinatura"></center></div>';
				echo $string;
			}
			else{
				$id_historico = "0";
				$string = '<div tipo="ativarAssinatura"><center><input type="button" name="button2" id="button2" class="pagarCartaoAtrasados" value="Ativar Assinatura"></center></div>';
				echo $string;
			}
		}
		//Insere o texto informativo da conta para o usuario ativo
		function getTextoAtivo(){
			//Verifica se existe fatura vencida para o usuario, se for altera a mensagem para a conta ativ 
			if( $this->ifFaturaVencida() ){
				$string = '
					<span class="tituloVermelho">Assinatura pendente</span><br /><br />
		            Consta em nosso sistema débito(s) pendente(s). Sua conta ficará sujeita a desativação a partir de 
		            <span class="destaque"><strong>'.$this->datas->desconverterData($this->datas->somarData($this->getDataFaturaPendente(),5)).'</strong></span>.';
			}//Se a cont do usuario nao possui pendencias, exibe a mensagem de usuario ativo
			else{
				$string = '
					<span class="tituloVermelho">Assinatura ativa</span><br /><br />
			        Não existem pendências em sua conta. <br /><br />
					Assinante desde: <strong>'.$this->datas->desconverterData($this->getdata_inclusao()).'</strong><br /><br />
					Para mudar o plano, dados e forma de pagamento, altere os campos ao lado. Prefira sempre o pagamento por cartão. É mais prático e debitado automaticamente.';
			}
			$string .='<br><br />';	
        	echo $string;
		}
		//Pega os dados de pagamento do cliente Ativo
		function getDadosPagamentoAtivo(){
			$this->getFaturasEmAberto();
			echo '<br />';
			$this->faturasPagas();
		}
		//Insere as ultimas 5 faturas do clientes
		function faturasPagas(){
			$string = '
				<div class="faturasPagas" style="overflow: auto">
					<span>Faturas Anteriores</span>';
			$string .= '
				<div style="overflow-x:auto;">
				<table border="0" cellspacing="2" cellpadding="4" style="font-size:12px" width="100%">
					<tbody>
						<tr>
							<th align="center">Data Pgto.</th>
							<th align="center">Competência</th>
					        <th align="center">Valor</th>
							<!--th align="center">Plano</th-->
					        <th align="center">Pgto</th>
						</tr>
			';

			$faturas_pagas = $this->getSqlFaturasPagas();
			if( mysql_num_rows($faturas_pagas) == 0 )
				echo '<style>.faturasPagas{display:none}</style>';
			while( $item = mysql_fetch_array($faturas_pagas) ){
				
				$data = $item['data'];
				$string .= '
						<tr class="guiaTabela" style="background-color: rgb(255, 255, 255);" valign="center">
							<td align="center">
					        	'.$this->datas->desconverterData($data).'
							</td>
					        <td align="center">
					        	'.$this->definirCompetenciaPagamento($item).'
							</td>
							<td align="center">
					        	'.$this->toMoney($item['valor_pago']).'
							</td>
							<!--td align="center">
								'.$item['tipo_plano'].' - '.$this->pegaNomeAssinaturaPlano($item['plano']).'
							</td-->
							<td align="center">    
					        	'.ucfirst($item['tipo_cobranca']).'
							</td>
						</tr>
				';	
			}
			$string .='
					</tbody>
				</table>
				</div>
			</div>
			';
			echo $string;
		}
		
		// Método criado para retornar nome do Tipo do plano
		public function getNomeTipoPlano($tipoPlano = 'S'){					
			
			switch($tipoPlano) {
				case 'P':
					$out = 'Premium';
					break;
				default:
					$out = 'Standard';
					break;
			}
			return $out;
		} 
		
		//Pega nome da assinatura do plano
		function pegaNomeAssinaturaPlano($planoAssinatura) {
		
			switch($planoAssinatura) {
			
				case 'trimestral':
					$plano = "Trimestral";
					break;
			
				case 'semestral':
					$plano = "Semestral";
					break;
				case 'anual':
					$plano = "Anual";
					break;		
			
				case 'mensalidade':
					$plano = "Mensal";
					break;
			}
			
			return $plano;
		}
		
		
		//Retorna o lpano do usuario de acordo com o valor pago
		function getPlanoPorValor($valorPago, $tipoPlano = 'S'){
			
			// Pega a lista de plano e seus valores.
			$itens = $this->getConfiguracoes($tipoPlano);
			while( $item = mysql_fetch_array($itens) ){
				$planoValor[$item['configuracao']] = $item['valor'];
			}
	
			$valorPago = floatval($valorPago);
			if( $valorPago <= $planoValor['mensalidade']){
				return 'Mensal';	
			}
			if( $valorPago <= $planoValor['trimestral']){
				return 'Trimestral';	
			}
			if( $valorPago <= $planoValor['semestral']){
				return 'Semestral';	
			}
			if( $valorPago <= $planoValor['anual']){
				return 'Anual';	
			}
			if( $valorPago > $planoValor['anual']){
				return 'Anual';	
			}
			return 'Mensal';
		}
		
		//Define para qual competencia determinado pagamento refere-se, sendo que pagamento antigos nao possuem uma ligacao, entao mantemos a competencia como a data de pagamento
		//Quando essa nova função estiver funcionando, ela pega os pagamentos recentes e alem de fzer a ligacao, atualiza o campo no relatorio para que todos os registros tenham ligacao
		function definirCompetenciaPagamento($fatura){
			//Pagamento muito antigos nao tem referencia, entrao definimos a competencia como a data de pagamento
			if( $fatura['idHistorico'] == '' || $fatura['idHistorico'] == 0 )
				return $this->datas->desconverterData($fatura['data']);
			else{
				//Caso existe a referencia, buscamos a data e escrevemos ela
				if( $this->ifExisteLigacaoRelatorioHistoricoCobranca($fatura['idHistorico']) )
					return $this->datas->desconverterData($this->getDataHistoricoCobranca($fatura['idHistorico']));
				else//Se nao existe id mas existe referencia, ela esta na forma AAAA/MM, entao repartimos a data e entao buscamos o item no historico que tem relação com esta data
					return $this->datas->desconverterData($this->getDataHistoricoCobrancaReferencia($fatura));
			}
		}
		//Pega o dia mes e ano do relatorio de cobranca e faz a ligacao com o historico de cobranca do usuario, e por fim salva o id do historico no relatorio de cobranca
		function getDataHistoricoCobrancaReferencia($fatura){
			$ano = substr( $fatura['idHistorico'] , 0 , 4 );
			$mes = substr( $fatura['idHistorico'] , 4 , 6 );
			$consulta = mysql_query("SELECT * FROM historico_cobranca WHERE  month(data_pagamento) = '".$mes."' and YEAR(data_pagamento) = '".$ano."' AND id = '".$this->getid_user()."'  ");
			$objeto_consulta = mysql_fetch_array($consulta);
			mysql_query("UPDATE relatorio_cobranca SET idHistorico = '".$objeto_consulta['idHistorico']."' WHERE idRelatorio = '".$fatura['idRelatorio']."' ");
			return $objeto_consulta['data_pagamento'];
		}
		//Pega a data de um item no historico de cobranca atraves do id do histoico
		function getDataHistoricoCobranca($id_historico){
			$consulta = mysql_query("SELECT * FROM historico_cobranca WHERE idHistorico = '".$id_historico."' AND id = '".$this->getid_user()."' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			return $objeto_consulta['data_pagamento'];
		}
		//Verifica se existe uma ligacao entre o registro no relatorio de cobranca e um registro no historico de cobranca
		function ifExisteLigacaoRelatorioHistoricoCobranca($id_historico){
			$consulta = mysql_query("SELECT * FROM historico_cobranca WHERE idHistorico = '".$id_historico."' AND id = '".$this->getid_user()."' ");
			if( mysql_num_rows($consulta) == 1 )
				return true;
			else
				return false;
		}
		function verificarStatusBoleto($id_historico){

			$consulta = mysql_query("SELECT * FROM boletos_registrados WHERE id_historico = '".$id_historico."' ");
			$via_atual = mysql_num_rows($consulta);
			$consulta = mysql_query("SELECT * FROM boletos_registrados WHERE nosso_numero LIKE '%".($via_atual-1)."".$id_historico."%'");
			$objeto_consulta = mysql_fetch_array($consulta);
			if( $objeto_consulta['status'] == "pendente" )
				return 'false';
			else if( $objeto_consulta['status'] == "não pago" )
				return 'true';
			else if( $objeto_consulta['remessa_aceita'] == "3" )
				return 'true';
			else 
				return 'false';
		}
		//Verifica a via atual do boleto
		function verificarViaBoleto($id_historico){
			$consulta = mysql_query("SELECT * FROM boletos_registrados WHERE id_historico = '".$id_historico."' ");
			$via_atual = mysql_num_rows($consulta);
			if( $via_atual > 0 )
				return $via_atual - 1;
			else
				return $via_atual;
		}
		//Verifica se o boleto em questao esta vencido
		function verificarBoletoVencido($id_historico){
			$consulta = mysql_query("SELECT * FROM boletos_registrados WHERE DATEDIFF(vencimento,'".date("Y-m-d")."') < 0 AND id_historico = '".$id_historico."' AND status = 'pendente' AND remessa_aceita = 2 ");
			if( mysql_num_rows($consulta) > 0 )
				return 'true';
			else
				return 'false';
		}
		//Verifica se o boleto em questão esta vencido
		function verificarBoletoVencido2($id_historico){
			$consulta = mysql_query("SELECT * FROM boleto b JOIN boletos_registrados r ON r.nosso_numero = b.nosso_numero WHERE DATEDIFF(vencto_get,'".date("Y-m-d")."') < 0  AND r.id_historico = '".$id_historico."' AND b.remessa_gerada = 1 ");
			if( mysql_num_rows($consulta) > 0 ) {
				return 'true';
			} else {
				return 'false';
			}
		}
		//Retorna as faturas em aberto para o usuario(a vencer, nao pagos)
		function getFaturasEmAberto(){
			$string = '
				<div class="proximasFaturas">
					<span>Próximos Pagamentos</span>
					<input id="totalEmpresas" type="hidden" name="total_empresas" value="'.( $this->gettotal_empresas() ? $this->gettotal_empresas() : 1 ).'" />';
			$string .= '
				<table border="0" cellspacing="2" cellpadding="4" style="font-size:12px" width="100%">
					<tbody>
						<tr>
							<th align="center">Vencimento</th>
					        <th align="center">Valor</th>
					        <!--<th align="center">Plano</th>-->
					        <th align="center">Ação</th>
						</tr>
			';
			
			$faturas_pendentes = $this->getSqlFaturasAnteriores();
			if( mysql_num_rows($faturas_pendentes) == 0 )
				echo '<style>.proximasFaturas{display:none}</style>';
			while( $item = mysql_fetch_array($faturas_pendentes) ){
				
				$string .= '
						<tr class="guiaTabela" style="background-color: rgb(255, 255, 255);" valign="center" id="'.$item['idHistorico'].'" data="'.$item['data_pagamento'].'" boleto-vencido="'.$this->verificarBoletoVencido2($item['idHistorico']).'" via="'.$this->verificarViaBoleto($item['idHistorico']).'" gerar-segunda-via="'.$this->verificarStatusBoleto($item['idHistorico']).'">
							<td align="center" '.$this->ifPagamentoVencido($item['data_pagamento']).'" >
					        	'.$this->datas->desconverterData($item['data_pagamento']).'
							</td>
					        <td align="center" class="valorProximoAVencer">
					        	'.$this->toMoney($this->getValorAPagar()).'
							</td>
<!--							<td align="center" class="tituloPlano">
					        	'.$this->getPlanoUsuario().'
							</td>-->
							<td align="center">    
					        	'.$this->getBotaoPagamento($item['id'],$item['data_pagamento']).'
							</td>
						</tr>
				';		
			}
			$string .='
					</tbody>
				</table>
			</div>
			';
			echo $string;
	
		}
		// Metodo para pegar o valor a ser pago.
		public function getValorAPagar() {
			// Pega o valor da mensalidade multiplicado pela quantidade de empresa e aplica o desconto se houver
			return floatval( ($this->getValorMensalidade($this->getTipoPlano()) * $this->gettotal_empresas()) - $this->getValorDesconto());
		}
		
		//Se o pagamento estiver vencido, marca como vermelho na tabela
		function ifPagamentoVencido($data){
			if( $this->datas->diferencaData( date("Y-m-d") , $data ) > 0  )
				return 'style="color:red"';
		}
		function ifPagamentoVencido2($data){
			if( $this->datas->diferencaData( date("Y-m-d") , $data ) > 0  )
				return true;
		}
		//Retorna o botao de pagamento de acordo com a forma de pagamento do usuario
		function getBotaoPagamento($id_historico,$data=""){
			if( $this->getforma_pagameto() == 'boleto' )
				return $this->getBotaoPagamentoBoleto($data);
			else
				return $this->getBotaoPagamentoCartao($data);

		}
		function getBotaoPagamentoBoleto($data){
			if( $this->ifPagamentoVencido2($data) )
				return '<a href="#" style=" text-transform: uppercase;   cursor: pointer;" class="gerarBoleto" >Recalcular Boleto Vencido</a>';
			else
				return '<a href="#" style=" text-transform: uppercase;   cursor: pointer;" class="gerarBoleto" >Imprimir Boleto</a>';
		
		}
		function getBotaoPagamentoCartao($data){
			if( $this->ifPagamentoVencido($data) != '' )
				return '<a href="#" style=" text-transform: uppercase;   cursor: pointer;" class="pagarCartaoAtrasados" >Pagar</a>';
			else
				return '<a href="#" style=" text-transform: uppercase;   cursor: pointer;" class="pagarCartaoAVencer" >Pagar</a>';
		
		}
		//Retorna o SQL com as faturas pendentes do usuario( a vencer e não pagos)
		function getSqlFaturasAnteriores(){
			$consulta = mysql_query("SELECT * FROM historico_cobranca WHERE id = '".$this->getid_user()."' AND ( status_pagamento = 'a vencer' OR status_pagamento = 'não pago' OR status_pagamento = 'vencido' ) ORDER BY data_pagamento DESC ");
			return $consulta;
		}
		
		// Pega o historico de cobrança do usuario que estiver vencido ou não pago 07-04-2017.
		function pegaHistoricoVencidaNaoPago() {
			$rows = false;
			$consulta = mysql_query(" SELECT * FROM login l 
									JOIN historico_cobranca hc ON hc.id = l.id
									WHERE l.id = ".$this->getid_user()." 
									AND hc.status_pagamento IN ('Vencido','não Pago')
									AND l.status IN ('ativo','Inativo'); ");
									
			if( mysql_num_rows($consulta) > 0 ){	$rows = mysql_fetch_array($consulta); }
			
			return $rows;	
		}		
	
		//Retorna o SQL com as faturas pagas com cartao ou boleto
		function getSqlFaturasPagas(){
			
			$qry = "SELECT * FROM relatorio_cobranca WHERE id = '".$this->getid_user()."' AND ( resultado_acao = '1.2' OR resultado_acao = '2.1' ) ORDER BY idRelatorio DESC LIMIT 5";
						
			$consulta = mysql_query($qry);
			return $consulta;
		}
		//Pega o texto do ballon de acordo com o status do usuario
		function getTextoBallon(){
			if( $this->getstatus_login() ==  'ativo' )
				$this->getTextoAtivo();
			if( $this->getstatus_login() ==  'inativo' )
				$this->getTextoInativo();
			if( $this->getstatus_login() ==  'demo' )
				$this->getTextoDemo();
			if( $this->getstatus_login() ==  'demoInativo' )
				$this->getTextoDemoInativo();
			if( $this->getstatus_login() ==  'cancelado' )
				$this->getTextoCancelado();
		}
		//Pega os dados de pagamento para o usuario de acordo com o status
		function getDadosPagamento(){
			if( $this->getstatus_login() ==  'ativo' )
				$this->getDadosPagamentoAtivo();
			if( $this->getstatus_login() ==  'inativo' )
				$this->getDadosPagamentoInativo();
			if( $this->getstatus_login() ==  'demo' )
				$this->getDadosPagamentoDemo();
			if( $this->getstatus_login() ==  'demoInativo' )
				$this->getDadosPagamentoDemoInativo();
			if( $this->getstatus_login() ==  'cancelado' )
				$this->getDadosPagamentoCancelado();
		}
		//Busca no banco todos os estados e suas siglas para preenchimento de forms
		function getEstados(){
			$arrEstados = array();
			$sql        = "SELECT * FROM estados ORDER BY sigla";
			$result = mysql_query($sql) or die(mysql_error());
			while ($estados = mysql_fetch_array($result)) {
			    array_push($arrEstados, array(
			        'id' => $estados['id'],
			        'sigla' => $estados['sigla']
			    ));
			}
			$this->setarrEstados($arrEstados);
		}
		//Veririca se existe um a vencer no historico do usuario
		function ifExisteAVencer(){
			$consulta = mysql_query("SELECT * FROM historico_cobranca WHERE status_pagamento = 'a vencer' AND id = '".$this->getid_user()."' ");
			if( mysql_num_rows($consulta) > 0 )
				return true;
			else
				return false;		
		}
		//Pega a maior data do historico para inserir o a vencer
		function getMaxDataHistorico(){
			$consulta = mysql_query("SELECT max(data_pagamento) as data FROM historico_cobranca WHERE id = '".$this->getid_user()."' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			return $objeto_consulta['data'];
		}
		function inserirAVencer(){
			
			if( $this->getstatus_login() == 'ativo' || $this->getstatus_login() == 'inativo' ){				
				$datas = new Datas();
				if( !$this->ifExisteAVencer() )
					mysql_query("INSERT INTO historico_cobranca (id, data_pagamento, status_pagamento) VALUES ('" . $this->getid_user() . "', '" . $datas->somarMes($this->getMaxDataHistorico(),1) . "', 'a vencer')");
			}
		
		}
		//Funcao que altera o status de pagamento antes de tudo para não correr o risco de efetivar pagamentos futuros (status a vencer)
		function atualizarPagamentosVencidos(){
			$sqlUpdateVencidos  = "
				UPDATE 
					historico_cobranca h 
				INNER JOIN 
					login l ON h.id = l.id 
				SET 
					h.status_pagamento = (CASE 
											WHEN l.status in ('demo','demoInativo') THEN 'não pago' 
											ELSE 'vencido' 
										END) 
				WHERE 
					l.id = '".$this->getid_user()."' 
					AND status_pagamento IN ('a vencer') 
					AND DATEDIFF(data_pagamento, DATE(now())) < 0
				";
			$rsAtualizaVencidos = mysql_query($sqlUpdateVencidos);
			// if( mysql_affected_rows() > 0 ){
			// 	$this->inserirAVencer();
			// }
		}
		//Funcao que atualiza os pagamentos vencidos a mais de 5 disa com o status de 'não pago'
		function atualizarpagamentosNaoPagos(){
			$sqlUpdateNaoPago  = "
				UPDATE 
					`historico_cobranca` 
				SET 
					status_pagamento = 'não pago' 
				WHERE 
					id = '".$this->getid_user()."' AND
					 status_pagamento IN ('vencido') AND 
					 DATEDIFF(data_pagamento, DATE(now())) <= -6
			";
			$rsAtualizaNaoPago = mysql_query($sqlUpdateNaoPago);
			if( mysql_affected_rows() > 0 ){
				$this->inserirAVencer();
			}
		}
		//Desativa o usuario de acordo com o status antigo
		function desativarUsuario(){
			if( $this->getstatus_login() == 'ativo' )
				$this->setstatus_login("inativo");
			if( $this->getstatus_login() == 'demo' )
				$this->setstatus_login("demoInativo");
		}
		//Funcao que desativa usuario que possue alguma pendencia nos pagementos
		function desativarUsuarioComPendencia(){
			$sqlUpdateLogin = "
				UPDATE 
					login l 
				SET 
					l.status = CASE WHEN l.status =  'ativo' THEN  'inativo' 
									WHEN l.status =  'demo' THEN  'demoInativo' 
									ELSE l.status END 
				WHERE 
					l.id = '".$this->getid_user()."' 
					AND ( SELECT COUNT(*) FROM historico_cobranca WHERE id = l.id AND status_pagamento =  'não pago') > 0
			";
			$rsUpdateLogin = mysql_query($sqlUpdateLogin);
			if ( mysql_affected_rows() > 0) {
				$this->desativarUsuario();
			}
		}
		//Método que pega os dados da cobrança
		function getDadosLogin(){
			$sql_dados_login = "
				SELECT 
					l.id
					, l.email
					, l.status
					, dc.assinante
					, l.data_inclusao
					, dc.forma_pagameto
					, dc.pref_telefone
					, dc.telefone
					, dc.plano
					, dc.tipo_plano
					, dc.numero_cartao
					, dc.codigo_seguranca
					, dc.nome_titular
					, dc.data_validade
					, dc.valor_desconto
				FROM 
					login l
					INNER JOIN dados_cobranca dc ON l.id = dc.id
				WHERE 
					l.id='".$this->getid_user()."' 
				LIMIT 0, 1
				";

			$resultado_dados_login = mysql_query($sql_dados_login);
			$linha_dados_login = mysql_fetch_array($resultado_dados_login);

			$this->setid_user($linha_dados_login['id']);
			$this->setemail_usuario($linha_dados_login['email']);
			$this->setstatus_login($linha_dados_login['status']);
			$this->setassinante($linha_dados_login['assinante']);
			$this->setplano($linha_dados_login['plano']);
			$this->setTipoPlano($linha_dados_login['tipo_plano']);
			$this->setdata_inclusao($linha_dados_login['data_inclusao']);
			$this->setforma_pagameto($linha_dados_login['forma_pagameto']);
			$this->setpref_telefone($linha_dados_login['pref_telefone']);
			$this->settelefone($linha_dados_login['telefone']);
			$this->setsenha($senha);
			$this->getTotalEmpresas();
			$this->getEstados();
			$this->setValorDesconto($linha_dados_login['valor_desconto']);
		}
		//Pega os dados do cartao do usuario
		function getDadosCartao(){
			$consulta_numero_cartao = mysql_query("SELECT * FROM token_pagamento WHERE id_user = '".$this->getid_user()."' ");
			$token = mysql_fetch_array($consulta_numero_cartao);
			$this->setdados_cartao_numero_cartao($token['numero_cartao']);
			$this->setdados_cartao_nome_titular($token['nome_titular']);
			$this->setdados_cartao_bandeira($token['bandeira']);
		}
		//Insere no log de acesso do cliente os dados informando que foi realizado o login
		function insertLogLogin(){
			mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $this->getid_user() . ",'MINHA CONTA: USUARIO ".$this->getstatus_login()." LOGADO')");		
		}
		//Pega a quantidade de empresas ativas do usuario
		function getTotalEmpresas(){
		    $rsTotalEmpresas = mysql_fetch_array(mysql_query("SELECT COUNT(*) total_empresas FROM login l INNER JOIN dados_da_empresa e ON l.id = e.id WHERE l.idUsuarioPai = '" . $this->getid_user() . "' AND e.ativa = 1"));
		 	$this->settotal_empresas(intval($rsTotalEmpresas['total_empresas']));
		}
		//Informa que o pagamento com cartao foi efetuado com sucesso
		function cartaoComSucesso(){
			echo '
				<div style="clear: both; margin-top: 15px;">
			        <strong>Transação efetuada com sucesso!</strong><br />
			        <br />
			    </div>
			';
		}
		//Informa que o pagamento com cartao retornou erro
		function cartaoComErro(){
			echo '
				<div style="clear: both; margin-top: 15px;">
			        <strong>Ocorreu um erro durante a transação</span><br />
			        <br />       
			    </div>
			';
		}
		//Retorna a div carregando
		function loader(){
			return '<div id="divCarregando" style="margin-top:10px; text-align:center; <?= "display:none" ?>"><img src="images/loading.gif" width="16" height="16" /> Carregando, por favor aguarde...</div>';
		}
		//Informa o usuario que apenas user ativo pode adiquirir esta promocao
		function erroCertificadoRestricaoStatus(){
			// $_SESSION['erro_certificado'] = '';
			echo '<script>
				$( document ).ready(function() {
					alert("Para ter direito à promoção, é preciso primeiro confirmar sua assinatura no Contador Amigo. Vá em Meus Dados/dados da Conta e escolha seu plano de assinatura.");
				});
			</script>';
		}
		function execute(){

			$this->getDadosLogin();
			$this->atualizarPagamentosVencidos();
			$this->atualizarpagamentosNaoPagos();
			$this->desativarUsuarioComPendencia();		
			
			$this->insertLogLogin();

			$this->getDadosCobranca();

			$this->getDadosCartao();

		}
		function __construct(){
			$this->datas = new Datas();
		}
	}

	// $minha_conta = new Minha_conta();
	// $minha_conta->setid_user(10127);
	// $minha_conta->execute();

	// $minha_conta->getTextoBallon();

	// $minha_conta->getDadosPagamento();

	// echo '<pre>';
	// var_dump($minha_conta);
	// echo '</pre>';

?>