<?php 
	
//	ini_set('display_errors',1);
//	ini_set('display_startup_erros',1);
//	error_reporting(E_ALL);
//		
	error_reporting(0);

	include 'conect.php';
	include 'datas.class.php';

	class Boleto{
		
		private $tipo_boleto;

		private $id_historico;
		private $id_user;
		private $avulso;
		private $plano_user;

		private $valor_mora;

		private $isMora;
		private $isMulta;
		private $mora;
		private $multa;
		private $vencimento_original;
		private $data_processamento;

		private $sacado_user;
		private $endereco_user;
		private $bairro_user;
		private $cidade_user;
		private $cep_user;
		private $uf_user;

		private $linha_digitavel;
		
		private $isSegundaVia;

		private $isBoletoVencido;

		private $total_empresas;

		private $gerar_segunda_via;

		private $via;

		private $prazo;
		private $valor_boleto;
		private $nosso_numero;
		private $numero_documento;
		private $data_vencimento;
		private $data_documento;
		private $sacado;
		private $endereco1;
		private $endereco2;
		private $demonstrativo1;
		private $demonstrativo2;
		private $demonstrativo3;
		private $instrucoes1;
		private $instrucoes2;
		private $instrucoes3;
		private $instrucoes4;
		private $quantidade;
		private $valor_unitario;
		private $aceite;
		private $especie;
		private $especie_doc;
		private $agencia;
		private $conta;
		private $convenio;
		private $contrato;
		private $carteira;
		private $variacao_carteira;
		private $formatacao_convenio;
		private $formatacao_nosso_numero;
		private $identificacao;
		private $cpf_cnpj;
		private $endereco;
		private $cidade_uf;
		private $cedente;
		private $TipoPlano;
		private $ContadorId;

		function getvia(){
			return $this->via;
		}
		function setvia($string){
			$this->via = $string;
		}
		function getgerar_segunda_via(){
			return $this->gerar_segunda_via;
		}
		function setgerar_segunda_via($string){
			$this->gerar_segunda_via = $string;
		}
		function gettotal_empresas(){
			return $this->total_empresas;
		}
		function settotal_empresas($string){
			$this->total_empresas = $string;
		}
		function gettipo_boleto(){
			return $this->tipo_boleto;
		}
		function settipo_boleto($string){
			$this->tipo_boleto = $string;
		}
		function getisBoletoVencido(){
			return $this->isBoletoVencido;
		}
		function setisBoletoVencido($string){
			$this->isBoletoVencido = $string;
		}
		function getisSegundaVia(){
			return $this->isSegundaVia;
		}
		function setisSegundaVia($string){
			$this->isSegundaVia = $string;
		}
		function getlinha_digitavel(){
			return $this->linha_digitavel;
		}
		function setlinha_digitavel($string){
			$this->linha_digitavel = $string;
		}
		function getuf_user(){
			return $this->uf_user;
		}
		function setuf_user($string){
			$this->uf_user = $string;
		}
		function getcep_user(){
			return $this->cep_user;
		}
		function setcep_user($string){
			$this->cep_user = $string;
		}
		function getcidade_user(){
			return $this->cidade_user;
		}
		function setcidade_user($string){
			$this->cidade_user = $string;
		}
		function getbairro_user(){
			return $this->bairro_user;
		}
		function setbairro_user($string){
			$this->bairro_user = $string;
		}
		function getendereco_user(){
			return $this->endereco_user;
		}
		function setendereco_user($string){
			$this->endereco_user = $string;
		}
		function getsacado_user(){
			return $this->sacado_user;
		}
		function setsacado_user($string){
			$this->sacado_user = $string;
		}
		function getdata_processamento(){
			return $this->data_processamento;
		}
		function setdata_processamento($string){
			$this->data_processamento = $string;
		}
		function getvencimento_original(){
			return $this->vencimento_original;
		}
		function setvencimento_original($string){
			$this->vencimento_original = $string;
		}
		function getmulta(){
			return $this->multa;
		}
		function setmulta($string){
			$this->multa = $string;
		}
		function getmora(){
			return $this->mora;
		}
		function setmora($string){
			$this->mora = $string;
		}
		function getisMulta(){
			return $this->isMulta;
		}
		function setisMulta($string){
			$this->isMulta = $string;
		}
		function getisMora(){
			return $this->isMora;
		}
		function setisMora($string){
			$this->isMora = $string;
		}
		function getvalor_mora(){
			return $this->valor_mora;
		}
		function setvalor_mora($string){
			$this->valor_mora = $string;
		}
		function getplano_user(){
			return $this->plano_user;
		}
		function setplano_user($string){
			$this->plano_user = $string;
		}
		function getid_historico(){
			return $this->id_historico;
		}
		function setid_historico($string){
			if( $string == '0' ){
				$consulta = mysql_query("SELECT * FROM historico_cobranca WHERE id = '".$this->getid_user()."' ORDER BY idHistorico DESC LIMIT 1 ");
				$objeto_consulta = mysql_fetch_array($consulta);
				$string = $objeto_consulta['idHistorico'];
			}
			$this->id_historico = $string;
		}
		function getid_user(){
			return $this->id_user;
		}
		function setid_user($string){
			$this->id_user = $string;
		}
		function getavulso(){
			return $this->avulso;
		}
		function setavulso($string){
			$this->avulso = $string;
		}
		function getprazo(){
			return $this->prazo;
		}
		function setprazo($string){
			$this->prazo = $string;
		}
		function getvalor_boleto(){
			return $this->valor_boleto;
		}
		function setvalor_boleto($string){
			$this->valor_boleto = $string;
		}
		function getnosso_numero(){
			return $this->nosso_numero;
		}
		function setnosso_numero($string){
			$this->nosso_numero = $string;
		}
		function getnumero_documento(){
			return $this->numero_documento;
		}
		function setnumero_documento($string){
			$this->numero_documento = $string;
		}
		function getdata_vencimento(){
			return $this->data_vencimento;
		}
		function setdata_vencimento($string){
			$this->data_vencimento = $string;
		}
		function getdata_documento(){
			return $this->data_documento;
		}
		function setdata_documento($string){
			$this->data_documento = $string;
		}
		function getsacado(){
			return $this->sacado;
		}
		function setsacado($string){
			$this->sacado = $string;
		}
		function getendereco1(){
			return $this->endereco1;
		}
		function setendereco1($string){
			$this->endereco1 = $string;
		}
		function getendereco2(){
			return $this->endereco2;
		}
		function setendereco2($string){
			$this->endereco2 = $string;
		}
		function getdemonstrativo1(){
			return $this->demonstrativo1;
		}
		function setdemonstrativo1($string){
			$this->demonstrativo1 = $string;
		}
		function getdemonstrativo2(){
			return $this->demonstrativo2;
		}
		function setdemonstrativo2($string){
			$this->demonstrativo2 = $string;
		}
		function getdemonstrativo3(){
			return $this->demonstrativo3;
		}
		function setdemonstrativo3($string){
			$this->demonstrativo3 = $string;
		}
		function getinstrucoes1(){
			return $this->instrucoes1;
		}
		function setinstrucoes1($string){
			$this->instrucoes1 = $string;
		}
		function getinstrucoes2(){
			return $this->instrucoes2;
		}
		function setinstrucoes2($string){
			$this->instrucoes2 = $string;
		}
		function getinstrucoes3(){
			return $this->instrucoes3;
		}
		function setinstrucoes3($string){
			$this->instrucoes3 = $string;
		}
		function getinstrucoes4(){
			return $this->instrucoes4;
		}
		function setinstrucoes4($string){
			$this->instrucoes4 = $string;
		}
		function getquantidade(){
			return $this->quantidade;
		}
		function setquantidade($string){
			$this->quantidade = $string;
		}
		function getvalor_unitario(){
			return $this->valor_unitario;
		}
		function setvalor_unitario($string){
			$this->valor_unitario = $string;
		}
		function getaceite(){
			return $this->aceite;
		}
		function setaceite($string){
			$this->aceite = $string;
		}
		function getespecie(){
			return $this->especie;
		}
		function setespecie($string){
			$this->especie = $string;
		}
		function getespecie_doc(){
			return $this->especie_doc;
		}
		function setespecie_doc($string){
			$this->especie_doc = $string;
		}
		function getagencia(){
			return $this->agencia;
		}
		function setagencia($string){
			$this->agencia = $string;
		}
		function getconta(){
			return $this->conta;
		}
		function setconta($string){
			$this->conta = $string;
		}
		function getcontrato(){
			return $this->contrato;
		}
		function setcontrato($string){
			$this->contrato = $string;
		}
		function getcarteira(){
			return $this->carteira;
		}
		function setcarteira($string){
			$this->carteira = $string;
		}
		function getvariacao_carteira(){
			return $this->variacao_carteira;
		}
		function setvariacao_carteira($string){
			$this->variacao_carteira = $string;
		}
		function getconvenio(){
			return $this->convenio;
		}
		function setconvenio($string){
			$this->convenio = $string;
		}
		function getformatacao_convenio(){
			return $this->formatacao_convenio;
		}
		function setformatacao_convenio($string){
			$this->formatacao_convenio = $string;
		}
		function getformatacao_nosso_numero(){
			return $this->formatacao_nosso_numero;
		}
		function setformatacao_nosso_numero($string){
			$this->formatacao_nosso_numero = $string;
		}
		function getidentificacao(){
			return $this->identificacao;
		}
		function setidentificacao($string){
			$this->identificacao = $string;
		}
		function getcpf_cnpj(){
			return $this->cpf_cnpj;
		}
		function setcpf_cnpj($string){
			$this->cpf_cnpj = $string;
		}
		function getendereco(){
			return $this->endereco;
		}
		function setendereco($string){
			$this->endereco = $string;
		}
		function getcidade_uf(){
			return $this->cidade_uf;
		}
		function setcidade_uf($string){
			$this->cidade_uf = $string;
		}
		function getcedente(){
			return $this->cedente;
		}
		function setcedente($string){
			$this->cedente = $string;
		}
		function getTipoPlano() {
			return $this->TipoPlano;	
		}
		function setTipoPlano($tipoPlano) {
			$this->TipoPlano = $tipoPlano;		
		}
		public function getContadorId() {
			return $this->ContadorId;	
		}
		public function setContadorId($contadorId = false) {
			$this->ContadorId = $contadorId;	
		}
		
		function getTotalEmpresas(){
		    $rsTotalEmpresas = mysql_fetch_array(mysql_query("SELECT COUNT(*) total_empresas FROM login l INNER JOIN dados_da_empresa e ON l.id = e.id WHERE l.idUsuarioPai = '" . $this->getid_user() . "' AND e.ativa = 1"));
		    if( intval($rsTotalEmpresas['total_empresas']) == 0 )
		    	$this->settotal_empresas(1);	
		    else
		 		$this->settotal_empresas(intval($rsTotalEmpresas['total_empresas'])	);
		}
		//Define se gera multa ou nao
		function getGerarMulta(){
			if( $this->gettipo_boleto() != 'mensalidade' )
				return 'false';
			if( $this->getisMulta() )
				return 'false';
			else
				return 'true';
		}
		//Define o valor da multa de acordo com o plano do usuario
		function calcularMulta(){
			if( $this->getplano_user() == 'mensalidade' ){
				return 1.18;
			}
			if( $this->getplano_user() == 'trimestral' ){
				return 3.54;
			}
			if( $this->getplano_user() == 'semestral' ){
				return 6.9;
			}
			if( $this->getplano_user() == 'anual' ){
				return 11.7;
			}
		}
		//Calcula a dta de vencimento do boleto
		function calcularVencimento(){
			$datas = new Datas();
			$this->setvencimento_original($this->getdata_vencimento());

			$consulta = mysql_query("SELECT * FROM login WHERE id = '".$this->getid_user()."' ");
			$objeto_consulta = mysql_fetch_array($consulta);

			if( $objeto_consulta['status'] == 'ativo' ) {
				$this->setdata_vencimento($datas->somarDiasUteis($this->getdata_vencimento(), $this->getprazo()));
			} else if( $this->getdata_vencimento() <= date("Y-m-d") ) {
				$this->setdata_vencimento($datas->somarDiasUteis(date("Y-m-d"), $this->getprazo()));
			}else {
				if( $datas->ifDiaUtil($this->getdata_vencimento()) ) {
					$this->setdata_vencimento($this->getdata_vencimento());
				} else {
					$this->setdata_vencimento($datas->somarDiasUteis($this->getdata_vencimento(),$this->getprazo()));
				}
			}
		}
		//Pega os dados do registro para o qual sera gerado o boleto
		function getDadosHistorico(){
			$consulta = mysql_query("SELECT * FROM historico_cobranca WHERE idHistorico = '".$this->getid_historico()."' AND id = '".$this->getid_user()."' ");
			$objeto=mysql_fetch_array($consulta);
			$this->setdata_vencimento($objeto['data_pagamento']);
		}
		//Pega os dados do usuario para preencimento do boleot e geração da remessa
		function getDadosUser(){
			$consulta_cobranca = mysql_query("SELECT * FROM dados_cobranca WHERE id = '".$this->getid_user()."' ");
			$cobranca = mysql_fetch_array($consulta_cobranca);
			//Seta dados para gerar o boleto
			$this->setsacado($cobranca['sacado']);
			$this->setendereco1($cobranca['endereco']." - ".$cobranca['bairro']);
			$this->setendereco2("CEP: ".$cobranca['cep']." - ".$cobranca['cidade']." - ".$cobranca['uf']);
			$this->setplano_user($cobranca['plano']);
			$this->setTipoPlano($cobranca['tipo_plano']);			
			if( $this->gettipo_boleto() == 'mensalidade' ) {
				$this->setvalor_boleto($this->getValorPlano($this->getTipoPlano()));
			}			
			//Seta dados do sacado
			$this->setsacado_user($cobranca['sacado']);
			$this->setendereco_user($cobranca['endereco']);
			$this->setbairro_user($cobranca['bairro']);
			$this->setcidade_user($cobranca['cidade']);
			$this->setcep_user($cobranca['cep']);
			$this->setuf_user($cobranca['uf']);
		}
		//Define se havera multa de acordo com a data que esta sendo gerado o boleto, aso seja maior que a data de vencimento original, gera multa
		function definirMulta(){
			$datas = new Datas();
			$diferenca = $datas->diferencaData(date("Y-m-d"),$this->getvencimento_original());
			if( $diferenca > 0) 
				$this->gerarMulta();
		}
		//Calcula o valor da multa
		function gerarMulta(){
			$this->setisMulta(true);
			$this->setmulta($this->calcularMulta());
			$this->setvalor_boleto($this->getvalor_boleto()+$this->getmulta());
		}
		//Define se havera juros de mora de acordo com a data que esta sendo gerado o boleto, aso seja maior que a data de vencimento original, gera jurosde mora na forma 0.22 * $dias
		function definirMora(){
			$datas = new Datas();
			//$diferenca = $datas->diferencaData(date("Y-m-d"),$this->getvencimento_original());
			
			// Inclui a verificação para dias uteis. - Átano farias.
			$diferenca = $datas->diferencaData(date("Y-m-d"),$datas->retornaDiaUtil($this->getvencimento_original())); 
			if( $diferenca >= 1) 
				$this->gerarMora($diferenca);
		}
		//Define o valor do juros de mora
		function gerarMora($dias){
			$this->setisMora(true);
			$this->setmora($this->calcularMora($dias));
			$this->setvalor_boleto($this->getvalor_boleto()+$this->calcularMora($dias));
		}
		//Calcula o valor do juros de mora, multiplicando 0.22 pelo numero de dias de atraso
		function calcularMora($dias){
			$datas = new Datas();
			$mora = $this->getvalor_mora();
			$aux = $this->getvencimento_original();
			$valor_total = 0;
			for ($i=0; $i < $dias; $i++) { 
				// if( $datas->ifDiaUtil($aux) )  {
					$valor_total = $valor_total + $mora;	
				// }
				$aux = $datas->somarData($aux,1);
			}
			return $valor_total;
		}
		//Pega o valor do plano do usuario
		function getValorPlano($tipoPlano = 'S'){
			$consulta = mysql_query("SELECT * FROM configuracoes WHERE configuracao = '".$this->getplano_user()."' AND tipo_plano = '".$tipoPlano."' ");
			$objeto=mysql_fetch_array($consulta);
			return $objeto['valor'];
		}
		//Define as instruções para o boleto
		function setInstrucoes(){
			$datas = new Datas();
			if( $this->getisMulta() || $this->getisMora() ){
				$frase_inicial = "Boleto reemitido com data de vencimento e valor atualizados<br>";
				$frase_vencimento = "Vencimento original: ".$datas->desconverterData($this->getvencimento_original())."<br>";
				$frase_descricao = "(Valor original";
				$frase_valor_original = "Valor original:..... : ".number_format($this->getvalor_boleto() - $this->getmulta() - $this->getmora(),2,',','.')."<br>";
				$frase_mora = "";
				$frase_multa = "";
				if( $this->getisMulta() ){
					$frase_multa = "Multa por atraso... : ".number_format($this->getmulta(),2,',','.');
					$frase_descricao .= " + Multa";
				}
				if( $this->getisMora() ){
					$frase_mora = "Encargos............... : ".number_format($this->getmora(),2,',','.');
					$frase_descricao .= " + Encargos";
				}
				$frase_descricao .= ")";
			}
			else{
				$frase_inicial = "Após o vencimento cobrar multa de 2%, mais juros de mora de R$ 0,22/dia.";
			}
			$string = "

				".$frase_inicial."
				".$frase_vencimento."
				".$frase_descricao."<br>
				".$frase_valor_original."
				".$frase_multa."<br>
				".$frase_mora."

			";
			$this->setinstrucoes1($string);
		}
		//Pega o mes de vencimento da data de vencimento original
		function getMesVencimento(){
			$aux = explode('-', $this->getvencimento_original());
			return $aux[1];
		}
		//Pega o ano de vencimento da data de vencimneto original
		function getAnoVencimento(){
			$aux = explode('-', $this->getvencimento_original());
			return substr($aux[0], 2,2);
		}
		//Pega o id maximo dos boleto registrados avulsos para gerar o nosso numero
		function getMaxIdBoletosAvulsos(){
			$consulta = mysql_query("SELECT id FROM `boletos_registrados` ");
			return mysql_num_rows($consulta) + 1;
		}
		//Pega o id maximo dos boleto registrados de certificados para gerar o nosso numero
		function getMaxIdBoletosCertificados(){
			$consulta = mysql_query("SELECT id FROM `boletos_registrados`");
			return mysql_num_rows($consulta) + 1;
		}
		
		// Pega o id maximo dos boleto registrados.
		function getMaxIdBoletos(){
			$consulta = mysql_query("SELECT id FROM `boletos_registrados` ");
			return mysql_num_rows($consulta) + 1;
		}	
		
		//Pega o nosso numero atual do boleto de certificado
		function getNossoNumeroCertificado(){
			$consulta = mysql_query("SELECT * FROM boletos_registrados WHERE tipo = 'certificado' AND status = 'pendente' AND id_user = '".$this->getid_user()."' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			return substr( $objeto_consulta['nosso_numero'] , 7 , 10 );		
		}
		//Verifica se é segunda via do boleto de certificado
		function ifsegundaViaCertificado(){
			$consulta = mysql_query("SELECT * FROM boletos_registrados WHERE tipo = 'certificado' AND status = 'pendente' AND id_user = '".$this->getid_user()."' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			if( $objeto_consulta['nosso_numero'] != '' )
				return true;
			else
				return false;
		}
		//Define a via do boleto atual, ainda estiver pendente, gera uma segunda via, se ja venceu ou foi baixado, gera um novo boleto
		function definirVia(){
			$consulta = mysql_query("SELECT * FROM boletos_registrados WHERE 
												id_historico = '".$this->getid_historico()."' AND
												id_user = '".$this->getid_user()."' AND 
												plano = '".$this->getplano_user()."' AND
												tipo_plano = '".$this->getTipoPlano()."'  
										");
			$objeto_consulta = mysql_fetch_array($consulta);
			if( $objeto_consulta['nosso_numero'] != '' && $objeto_consulta['status'] == 'pendente' )
				$this->setvia(substr($objeto_consulta['nosso_numero'], 7 , 1));
			else{
				$consulta = mysql_query("SELECT * FROM boletos_registrados WHERE 
												id_historico = '".$this->getid_historico()."' AND
												id_user = '".$this->getid_user()."'
										");
				$this->setvia(mysql_num_rows($consulta));
			}
		}
		//Gera o nosso numero do boleto de mensalidade
		function getGerarNossoNumeroBoletoMensalidade(){
			$this->definirVia();
			return $this->getvia().str_pad( $this->getid_historico() , 5 , "0" , STR_PAD_LEFT ).'0000';//Formato: 0+idHistorico
		}
		//Gera o nosso numero para um boleto avulso
		//Formato: 9+id_boletos
		function getGerarNossoNumeroBoletoAvulso(){
			//Formato: 9 + id_boletos_avulsos
			return  '9'.str_pad( $this->getMaxIdBoletosAvulsos() , 5 , "0" , STR_PAD_LEFT ).'0000';
		}
		
		//Gera o nosso numero para um boleto avulso
		//Formato: 9+id_boletos
		function getGerarNossoNumeroBoleto(){
			//Formato: 9 + id_boletos_avulsos
			return  '9'.str_pad( $this->getMaxIdBoletos() , 5 , "0" , STR_PAD_LEFT ).'0000';
		}
		
		//Gera o nosso numero para um boleto de certificado, verificando antes se é uma segunda via que esta pendende no pagamento, se for emite o mesmo boleto, senao gera um novo
		//Formato: 8+id_boletos_certificado
		function getGerarNossoNumeroBoletoCertificado(){
			if( !$this->ifsegundaViaCertificado() )
				return  '8'.str_pad( $this->getMaxIdBoletosCertificados() , 5 , "0" , STR_PAD_LEFT ).'0000';
			else 
				return $this->getNossoNumeroCertificado();
		}
		//Gera a segunda via de um boleto
		//Formato: via+id_historico
		//via = quantidade de boletos com o mesmo nosso numero gerados 
		//O primeiro boleto e sempre 0, o segundo 1 e assim por diante
		function getGerarNossoNumeroBoletoMensalidadeSegundaVia(){
			$consulta = mysql_query("SELECT * FROM boletos_registrados WHERE nosso_numero LIKE '%".$this->getid_historico()."%' ");
			$via = mysql_num_rows($consulta);
			if( $via > 5)
				$via = 5;
			return $via.str_pad( $this->getid_historico() , 5 , "0" , STR_PAD_LEFT ).'0000';	
		}
		//Funcao que gera o nosso umero
		function gerarNossoNumero(){
			if( $this->gettipo_boleto() == "mensalidade" )//Caso seja mensalidade verifica se e boleto normal ou segunda via
				if( $this->getgerar_segunda_via() )
					return $this->getGerarNossoNumeroBoletoMensalidadeSegundaVia();
				else
					return $this->getGerarNossoNumeroBoletoMensalidade();
			else if( $this->gettipo_boleto() == "avulso" ){//Se for avulso, gera o nosso numero
				return $this->getGerarNossoNumeroBoletoAvulso();
			}
			else if( $this->gettipo_boleto() == "certificado" ){//Se for avulso, gera o nosso numero
				return $this->getGerarNossoNumeroBoletoCertificado();
			}
			
			// Gera os Boletos de serviços com assinatura do contador.
			else if( $this->gettipo_boleto() == "AbertAltEmpresa" )
			{ 
				return $this->getGerarNossoNumeroBoleto(); // Gera o nosso numero.
			}
			else if( $this->gettipo_boleto() == "AbertAltSociedade" )
			{
				return $this->getGerarNossoNumeroBoleto(); // Gera o nosso numero.
			}						
			else if( $this->gettipo_boleto() == "decore" )
			{ 
				return $this->getGerarNossoNumeroBoleto(); // Gera o nosso numero.
			}
			else if( $this->gettipo_boleto() == "DBE" )
			{ 
				return $this->getGerarNossoNumeroBoleto(); // Gera o nosso numero.
			}
		}
		//Define o nosso numero, sendo que apenas os 10 ultimos digitos sao variaveis
		function setNossoNumero(){
			$nosso_numero = $this->gerarNossoNumero();
			$this->setnosso_numero($nosso_numero);
			$this->setnumero_documento($nosso_numero);
		}
		//Define a mensagem que sera criada no historico eo usuario
		function gerarMensagemHistorico(){
			$datas = new Datas();
			$data = $datas->desconverterData($this->getvencimento_original());
			$valor = number_format($this->getvalor_boleto()-$this->getmora()-$this->getmulta(),2,',','.');
			$mora = number_format($this->getmora(),2,',','.');
			$multa = number_format($this->getmulta(),2,',','.');
			$string = "MINHA CONTA: BOLETO ".strtoupper($this->gettipo_boleto())." A EMITIR: ".$data." VALOR: ".$valor;
			if( $this->getisMulta() ){
				$string .= " MULTA: ".$multa;
			}
			if( $this->getisMora() ){
				$string .= " MORA: ".$mora;
			}
			return $string;
		}
		//Insere os dados do boleto gerado na remassa
		function inserirRemesssa(){
			$datas = new Datas();
			$campos = "(`id`, `user`, `valor_get`,`gerar_multa`, `numdoc_get`, `sacado_get`, `enderecosac_get`, `bairrosac_get`, `cidadesac_get`, `cepsac_get`, `ufsac_get`, `datadoc_get`, `vencto_get`, `linha_digitavel`, `nosso_numero`, `instrucoes`, `remessa_gerada`)";
			$values = "(
							'',
							'".$this->getid_user()."',
							'".$this->getvalor_boleto()."',
							'".$this->getGerarMulta()."',
							'".$this->getnosso_numero()."',
							'".$this->getsacado_user()."',
							'".$this->getendereco_user()."',
							'".$this->getbairro_user()."',
							'".$this->getcidade_user()."',
							'".$this->getcep_user()."',
							'".$this->getuf_user()."',
							'".$datas->desconverterData($this->getdata_documento())."',
							'".$this->getdata_vencimento()."',
							'".$this->getlinha_digitavel()."',
							'".$this->getconvenio().$this->getnosso_numero()."',
							'".$this->getinstrucoes1()."',
							'0'
						)";
			//Salva no historico do 
			mysql_query("INSERT INTO `log_acessos`(`id`, `id_user`, `acao`) VALUES ('','".$this->getid_user()."','".$this->gerarMensagemHistorico()."')");
			mysql_query("INSERT INTO `boleto` ".$campos." VALUES ".$values."");
		}
		//Exibe a tela para recalculo do boleto, quando o boleto ja venceu e o usuario nao conseguiu pagar
		function recalcularBoleto(){
			echo '</div><link href="estilo.css" rel="stylesheet" type="text/css">
			
			<div class="principal" style="font-size:14px;">
			<div class="tituloVermelho">
				Boleto Vencido!
			</div>';
			echo 'Para gerar o boleto corrigido copie e cole abaixo o código de barras original: ';
			echo ' <span style="color: #a61d00;"><strong>'.str_replace('.', ' ', $this->getlinha_digitavel()).'</strong></span><br>
			 Cole no primeiro campo "Linha Digitável do Título". <span style="color: #a61d00;">
					Os demais campos podem ficar em  branco.</span>';
			echo '</div></div>';
			echo '<br><br>';
			echo '<iframe src="https://www63.bb.com.br/portalbb/boleto/boletos/hc21e,802,3322,10343.bbx" style="overflow:hidden;width: 963px;margin: 0 auto;text-align: left;height: 1840px;border: 0;margin-bottom: 20px"></iframe>';
		}
		//Verifica se o boleto a gerar é uma segunda via de algumboleto existete
		function verificaSegundaVia(){
			$datas = new Datas();
			//Busca um boleto com o nosso numero gerado no banco de dados, se existir, existe, o boleto ja foi registrado
			$consulta = mysql_query("SELECT * FROM boleto WHERE user = '".$this->getid_user()."' AND numdoc_get = '".$this->getnosso_numero()."' ");
			$objeto=mysql_fetch_array($consulta);
			//Se ja existe o boleto, verifica se a remassa ja foi enviada
			if( isset($objeto['numdoc_get']) && $objeto['numdoc_get'] == $this->getnosso_numero() ){
				//Se a remessa ainda nao foi enviada e temos um boleto com valor diferente, deletamos o boleto antigo e usamos os novos dados
				if( intval($objeto['valor_get']) != intval($this->getvalor_boleto()) && $objeto['remessa_gerada'] == 0 ){
					mysql_query("DELETE FROM boleto WHERE id = '".$objeto['id']."' ");
				}//Se o boleto é igual ao antigo ja gerado ou a remessa ja foi enviada, exibimos a segunda via desse boleto.
				else{
					//Seta os dados do boleto existente
					$this->setid_user($objeto['user']);
					$this->setvalor_boleto($objeto['valor_get']);
					$this->setnosso_numero($objeto['numdoc_get']);
					$this->setsacado_user($objeto['sacado_get']);
					$this->setendereco_user($objeto['enderecosac_get']);
					$this->setbairro_user($objeto['bairrosac_get']);
					$this->setcidade_user($objeto['cidadesac_get']);
					$this->setcep_user($objeto['cepsac_get']);
					$this->setuf_user($objeto['ufsac_get']);
					$this->setdata_documento($datas->converterData($objeto['datadoc_get']));
					$this->setdata_processamento($datas->converterData($objeto['datadoc_get']));
					$this->setdata_vencimento($objeto['vencto_get']);
					$this->setinstrucoes1($objeto['instrucoes']);
					$this->setisSegundaVia(true);
					//Se o boleto ja venceu, seta a variavel para definir o recalculo do boleto atraves do portal do BB com um iframe
					if( $this->getdata_vencimento() < date("Y-m-d") && $objeto['remessa_gerada'] == 1 )
						$this->setisBoletoVencido(true);
				}
			}
		}
		//Define os dados que serao escritos no boleto
		function gerarArrayBoleto(){
			$this->verificaSegundaVia();
			$datas = new Datas();
			$dadosboleto["nosso_numero"] 			= $this->getnosso_numero();
			$dadosboleto["numero_documento"] 		= $this->getnumero_documento();
			$dadosboleto["data_vencimento"] 		= $datas->desconverterData($this->getdata_vencimento());
			$dadosboleto["data_documento"] 			= $datas->desconverterData($this->getdata_documento());
			$dadosboleto["data_processamento"] 		= $datas->desconverterData($this->getdata_processamento());
			$dadosboleto["valor_boleto"] 			= number_format($this->getvalor_boleto(), 2, ',', '');
			$dadosboleto["sacado"] 					= $this->getsacado();
			$dadosboleto["endereco1"] 				= $this->getendereco1();
			$dadosboleto["endereco2"] 				= $this->getendereco2();
			$dadosboleto["demonstrativo1"] 			= $this->getdemonstrativo1();
			$dadosboleto["demonstrativo2"] 			= $this->getdemonstrativo2();
			$dadosboleto["demonstrativo3"] 			= $this->getdemonstrativo3();
			$dadosboleto["instrucoes1"] 			= $this->getinstrucoes1();
			$dadosboleto["instrucoes2"] 			= $this->getinstrucoes2();
			$dadosboleto["instrucoes3"] 			= $this->getinstrucoes3();
			$dadosboleto["instrucoes4"] 			= $this->getinstrucoes4();
			$dadosboleto["quantidade"] 				= $this->getquantidade();
			$dadosboleto["valor_unitario"] 			= $this->getvalor_unitario();
			$dadosboleto["aceite"] 					= $this->getaceite();
			$dadosboleto["especie"] 				= $this->getespecie();
			$dadosboleto["especie_doc"] 			= $this->getespecie_doc();
			$dadosboleto["agencia"] 				= $this->getagencia();
			$dadosboleto["conta"] 					= $this->getconta();
			$dadosboleto["convenio"] 				= $this->getconvenio();
			$dadosboleto["contrato"] 				= $this->getcontrato();
			$dadosboleto["carteira"] 				= $this->getcarteira();
			$dadosboleto["variacao_carteira"] 		= $this->getvariacao_carteira();
			$dadosboleto["formatacao_convenio"] 	= $this->getformatacao_convenio();
			$dadosboleto["formatacao_nosso_numero"] = $this->getformatacao_nosso_numero();
			$dadosboleto["identificacao"] 			= $this->getidentificacao();
			$dadosboleto["cpf_cnpj"] 				= $this->getcpf_cnpj();
			$dadosboleto["endereco"] 				= $this->getendereco();
			$dadosboleto["cidade_uf"] 				= $this->getcidade_uf();
			$dadosboleto["cedente"] 				= $this->getcedente();
			return $dadosboleto;
		}
		//Registra o boleto no banco, (diferente da remmessa)
		function registrarBoleto(){	
			$campos = "(`id_user`, `id_historico`, `nosso_numero`, `status`, `tipo`, Tipo_plano,`plano`, `valor`, `multa`, `mora`, `vencimento`)";
			$values = "(
				'".$this->getid_user()."',
				'".$this->getid_historico()."',
				'".$this->getconvenio().$this->getnosso_numero()."',
				'pendente',
				'".$this->gettipo_boleto()."',
				'".$this->getTipoPlano()."',
				'".$this->getplano_user()."',
				'".$this->getvalor_boleto()."',
				'".$this->getmulta()."',
				'".$this->getmora()."',
				'".$this->getdata_vencimento()."'
				)";
			if($this->getnosso_numero() > 0){
				mysql_query("DELETE FROM `boletos_registrados` WHERE nosso_numero LIKE '%".$this->getnosso_numero()."' ");			
			}
			mysql_query("INSERT INTO `boletos_registrados` ".$campos." VALUES ".$values." ");
			
			$boletosRegistradosId = mysql_insert_id();
			
			// Verifica se o codigo do contador foi informado.
			if($this->getContadorId() && $this->gettipo_boleto() == 'AbertAltEmpresa' || $this->gettipo_boleto() == 'AbertAltSociedade' || $this->gettipo_boleto() == 'decore' || $this->gettipo_boleto() == 'DBE') {
				mysql_query("INSERT INTO `cobranca_contador` (`contadorId`, `boletosRegistradosId`) VALUE (".$this->getContadorId().", ".$boletosRegistradosId.");");
			} else if($this->getContadorId() && $this->getTipoPlano() == 'P' && $this->gettipo_boleto() == 'mensalidade') {
				mysql_query("INSERT INTO `cobranca_contador` (`contadorId`, `boletosRegistradosId`) VALUE (".$this->getContadorId().", ".$boletosRegistradosId.");");
			}			
		}
		function definirValorBoleto(){
			$this->setvalor_boleto($this->getvalor_boleto() * $this->gettotal_empresas());		
		}
		//Verifica o status do usuario e define o vencimento original como hoje, caso o status do usuario seja demoInativo ou cancelado
		function verificaStatusUsuario(){
			$consulta = mysql_query("SELECT * FROM login WHERE id = '".$this->getid_user()."' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			if( $objeto_consulta['status'] == 'demoInativo' || $objeto_consulta['status'] == 'cancelado' )
				$this->setvencimento_original(date("Y-m-d"));		
		}
		//Função que gera o boleto
		function gerarBoleto(){
			if( $this->gettipo_boleto() == 'mensalidade' ){//Se for boleto para mensalidade pega os dados no histrico para definir datas
				$this->getDadosHistorico();//Pega o registro referente ao historico de cobrança do usuario para o qual asera gerado o boleto
				$this->getTotalEmpresas();
				$this->calcularVencimento();//Calcula a data de vencimento do boleto
				$this->verificaStatusUsuario();//Verifica se o status do usuario é demo inativo ou cacelado, caso sseja, define a data de vencimento original como hj para nao cobrar multa ou juros
				$this->getDadosUser();//Pega os dados do usuario
				$this->definirValorBoleto();//Define o vlor do boleto com o valor do plano e a quantidade de empresas
				$this->definirMulta();//Define se haverá multa, baseado na data de geração do boleto e na data de vencimento da fatura
				$this->definirMora();//Define se haverá mora, baseado na data de geração do boleto e na data de vencimento da fatura
				$this->setInstrucoes();//Define as instruções do boleto
				$this->setNossoNumero();//Define o nosso numero para este boleto
			}
			else if( $this->gettipo_boleto() == 'avulso' ){
				$this->calcularVencimento();//Calcula a data de vencimento do boleto
				$this->getDadosUser();//Pega os dados do usuario
				$this->setInstrucoes();//Define as instruções do boleto
				$this->setNossoNumero();//Define o nosso numero para este boleto
			}
			else if( $this->gettipo_boleto() == 'certificado' ){
				$this->calcularVencimento();//Calcula a data de vencimento do boleto
				$this->getDadosUser();//Pega os dados do usuario
				$this->setInstrucoes();//Define as instruções do boleto
				$this->setNossoNumero();//Define o nosso numero para este boleto
			}
			else if( $this->gettipo_boleto() == 'AbertAltEmpresa' ){
				$this->calcularVencimento();//Calcula a data de vencimento do boleto
				$this->getDadosUser();//Pega os dados do usuario
				$this->setInstrucoes();//Define as instruções do boleto
				$this->setNossoNumero();//Define o nosso numero para este boleto
			}
			else if( $this->gettipo_boleto() == 'AbertAltSociedade' ){
				$this->calcularVencimento();//Calcula a data de vencimento do boleto
				$this->getDadosUser();//Pega os dados do usuario
				$this->setInstrucoes();//Define as instruções do boleto
				$this->setNossoNumero();//Define o nosso numero para este boleto
			}
			else if( $this->gettipo_boleto() == 'decore' ){
				$this->calcularVencimento();//Calcula a data de vencimento do boleto
				$this->getDadosUser();//Pega os dados do usuario
				$this->setInstrucoes();//Define as instruções do boleto
				$this->setNossoNumero();//Define o nosso numero para este boleto
			}
			else if( $this->gettipo_boleto() == 'DBE' ){
				$this->calcularVencimento();//Calcula a data de vencimento do boleto
				$this->getDadosUser();//Pega os dados do usuario
				$this->setInstrucoes();//Define as instruções do boleto
				$this->setNossoNumero();//Define o nosso numero para este boleto
			}

			//Pega os dados do boleto e coloca no array para serem gerados os dados do boleto
			$dadosboleto = $this->gerarArrayBoleto();
			//Insere as funções para geração dos dados do boleto
			include 'boleto_funcoes.php';
			//Define a linha digitavel, atribuida para o array abaixo em boleto_funcoes
			$this->setlinha_digitavel($dadosboleto["linha_digitavel"]);
			
			//Insere os dados do boleto na remessa se não for segunda via de boleto
			if( !$this->getisSegundaVia() ){//e nao for segunda via, registra o boleto no banco e cria a remessa
				$this->registrarBoleto();//Registra o boleto no banco
				$this->inserirRemesssa();//Insere a remessa no banco
			}
				
			if( $this->getisBoletoVencido() )//Caso seja um boleto vencido, exibe a tela para recalculo do boleto
				$this->recalcularBoleto();//Chama o iframe de reclculo do boleto
			else{
				if( $this->getisSegundaVia() )
					echo '2º Via';
				include 'boleto_layout.php';//Insere o layout  do boleto				
			}
		}
		function __construct(){
			//Define dados iniciais do boleto
			$this->setaceite("N");	
			$this->setespecie("R$");
			$this->setagencia("2962"); 
			$this->setconta("17877"); 
			$this->setconvenio("2850943");
			$this->setcarteira("17");
			$this->setformatacao_convenio("7");
			$this->setformatacao_nosso_numero("2");
			$this->setidentificacao("Contador Amigo");
			$this->setcpf_cnpj("96533310000140");
			$this->setendereco("Av. das Nacoes Unidas, 8501 17 Andar");
			$this->setcidade_uf("Sao Paulo / SP");
			$this->setcedente("Contador Amigo Ltda. - ME");
			$this->setdata_documento(date("Y-m-d"));
			$this->setdata_processamento(date("Y-m-d"));
			//Define o valor da mora a ser acrescido no valor do boleto vencido
			$this->setvalor_mora(0.22);
			//Define dados iniciais, partindo do principio que o boleto e novo, sem multa e mora
			$this->setisMulta(false);
			$this->setisMora(false);
			$this->setmulta(0);
			$this->setmora(0);
			$this->setisSegundaVia(false);
			$this->setisBoletoVencido(false);			
			$this->setgerar_segunda_via(false);
			$this->setvia(0);
		}
	}

	//Define o tipo de boleto a ser gerado
	$tipo = "mensalidade";
	//Define a data de vencimento original caso seja boleto avulso
	$data_vencimento = "2016-09-25";
	//Define a nova data de vencimento caso seja boleto avulso
	$prazo = "4";

	$boleto = new Boleto();

	if( $_GET['tipo'] == "mensalidade" ){

		//Define o tipo de boleto
		$boleto->settipo_boleto("mensalidade");
		//Define o id do uusuario para pegar os dados
		$boleto->setid_user($_GET['id_user']);
		//Define o id do historico para pegar os dados
		$boleto->setid_historico($_GET['id_historico']);
		//Verifica se o boleto se trata de uma segunda via
		if( isset( $_GET['segunda_via'] ) )
			$boleto->setgerar_segunda_via(true);
		if( isset( $_GET['via'] ) )
			$boleto->setvia($_GET['via']);
		//define o prazo em dias para o vencimento a partir de hoje
		$boleto->setprazo(0);
		// Verifica se o código do contador foi informado.
		if(isset($_GET['contadorId']) && !empty($_GET['contadorId'])) {	
			$boleto->setContadorId($_GET['contadorId']);
		}
		//Gera o boleto
		$boleto->gerarBoleto();

	}
	else if( $_GET['tipo'] == "avulso" ){
		//Cria um objeto para manipular datas
		$datas = new Datas();
		//Define o tipo de boleto
		$boleto->settipo_boleto("avulso");
		//Define o valor do boleto
		$boleto->setvalor_boleto(str_replace(',', '.' , str_replace('.', '' , $_GET['valor'])));
		//Define o vencimento original do boleto avulso
		$boleto->setdata_vencimento($datas->converterData($_GET['data']));
		//Define o id do uusuario para pegar os dados
		$boleto->setid_user($_GET['id_user']);
		//define o prazo em dias para o vencimento a partir de hoje, no caso do boleto avulso, a diferenca entre a data de vencimento original e o novo vencimento
		$boleto->setprazo(0);
		//Gera o boleto
		$boleto->gerarBoleto();
	}
	else if( $_GET['tipo'] == "AbertAltEmpresa" ){
		//Cria um objeto para manipular datas
		$datas = new Datas();
		//Define o tipo de boleto
		$boleto->settipo_boleto("AbertAltEmpresa");
		//Define o valor do boleto
		$boleto->setvalor_boleto(str_replace(',', '.' , str_replace('.', '' , $_GET['valor'])));
		//Define o vencimento original do boleto
		$boleto->setdata_vencimento($datas->converterData($_GET['data']));
		//Define o id do uusuario para pegar os dados
		$boleto->setid_user($_GET['id_user']);
		//define o prazo em dias para o vencimento a partir de hoje.
		$boleto->setprazo(2);
		//Pega o codigo do contador.
		$boleto->setContadorId($_GET['contadorId']);
		//Gera o boleto
		$boleto->gerarBoleto();
	}	
	else if( $_GET['tipo'] == "AbertAltSociedade" ){
		//Cria um objeto para manipular datas
		$datas = new Datas();
		//Define o tipo de boleto
		$boleto->settipo_boleto("AbertAltSociedade");
		//Define o valor do boleto
		$boleto->setvalor_boleto(str_replace(',', '.' , str_replace('.', '' , $_GET['valor'])));
		//Define o vencimento original do boleto
		$boleto->setdata_vencimento($datas->converterData($_GET['data']));
		//Define o id do uusuario para pegar os dados
		$boleto->setid_user($_GET['id_user']);
		//define o prazo em dias para o vencimento a partir de hoje.
		$boleto->setprazo(2);
		//Pega o codigo do contador.
		$boleto->setContadorId($_GET['contadorId']);
		//Gera o boleto
		$boleto->gerarBoleto();
	}
	else if( $_GET['tipo'] == "decore" ){
		//Cria um objeto para manipular datas
		$datas = new Datas();
		//Define o tipo de boleto
		$boleto->settipo_boleto("decore");
		//Define o valor do boleto
		$boleto->setvalor_boleto(str_replace(',', '.' , str_replace('.', '' , $_GET['valor'])));
		//Define o vencimento original do boleto
		$boleto->setdata_vencimento($datas->converterData($_GET['data']));
		//Define o id do uusuario para pegar os dados
		$boleto->setid_user($_GET['id_user']);
		//define o prazo em dias para o vencimento a partir de hoje.
		$boleto->setprazo(2);
		//Pega o codigo do contador.
		$boleto->setContadorId($_GET['contadorId']);
		//Gera o boleto
		$boleto->gerarBoleto();
	}		
	else if( $_GET['tipo'] == "DBE" ){
		//Cria um objeto para manipular datas
		$datas = new Datas();
		//Define o tipo de boleto
		$boleto->settipo_boleto("DBE");
		//Define o valor do boleto
		$boleto->setvalor_boleto(str_replace(',', '.' , str_replace('.', '' , $_GET['valor'])));
		//Define o vencimento original do boleto
		$boleto->setdata_vencimento($datas->converterData($_GET['data']));
		//Define o id do usuario para pegar os dados
		$boleto->setid_user($_GET['id_user']);
		//define o prazo em dias para o vencimento a partir de hoje.
		$boleto->setprazo(2);
		//Pega o codigo do contador.
		$boleto->setContadorId($_GET['contadorId']);
		//Gera o boleto
		$boleto->gerarBoleto();
	}
	else if( $_GET['tipo'] == "certificado" ){
		//Cria um objeto para manipular datas
		$datas = new Datas();
		//Define o tipo de boleto
		$boleto->settipo_boleto("certificado");
		//Define o valor do boleto
		$boleto->setvalor_boleto(140);
		//Define o vencimento original do boleto avulso
		$boleto->setdata_vencimento($datas->somarDiasUteis(date("Y-m-d"),2));
		//Define o id do uusuario para pegar os dados
		$boleto->setid_user($_GET['id_user']);
		//define o prazo em dias para o vencimento a partir de hoje, no caso do boleto avulso, a diferenca entre a data de vencimento original e o novo vencimento
		$boleto->setprazo(0);
		//Gera o boleto
		$boleto->gerarBoleto();
	}	

?>