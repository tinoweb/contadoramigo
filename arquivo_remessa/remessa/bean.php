<?php 

	function FunctionName($value=''){
		$str = $value;
		$str = strtolower(utf8_decode($str)); $i=1;
		$str = strtr($str, utf8_decode(' '), '_');
		// $str = preg_replace("/([^a-z0-9])/",'-',utf8_encode($str));
		// while($i>0) $str = str_replace('--','-',$str,$i);
		// 	if (substr($str, -1) == '-') $str = substr($str, 0, -1);
				echo $str.'<br>';
	}
	
		
	function brancos($tam){
		for ($i=0; $i < $tam; $i++) { 
			echo '&nbsp';
		}
	}
	function zeros($tam){
		for ($i=0; $i < $tam; $i++) { 
			echo '0';
		}	
	}

	/**
	* 
	*/
	class Registro_header_arquivo{

		public $codigo_do_banco_na_compensacao;
		public $lote_de_servico;
		public $tipo_de_registro;
		public $tipo_de_inscricao_da_empresa;
		public $numero_de_inscricao_da_empresa;
		public $codigo_do_convenio_no_banco;
		public $agencia_mantenedora_da_conta;
		public $digito_verificador_da_agencia;
		public $numero_da_conta_corrente;
		public $digito_verificador_da_conta;
		public $digito_verificador_da_ag_conta;
		public $nome_da_empresa;
		public $nome_do_banco;
		public $codigo_remessa_retorno;
		public $data_de_geracao_do_arquivo;
		public $hora_de_geracao_do_arquivo;

		function brancos($tam){
			$string = '';
			for ($i=0; $i < $tam; $i++) { 
					$string .= ' ';
				}
			return $string;
		}

		function zeros($tam){
			$string = '';
			for ($i=0; $i < $tam; $i++) { 
					$string .= '0';
				}
			return $string;
		}
		function gerar($path="remessa/"){
			include $path.'header_arquivo.php'; 
		}
		function __construct(){
			$this->codigo_do_banco_na_compensacao = "001";
			$this->lote_de_servico = '0000';
			$this->tipo_de_registro = '0';
			$this->tipo_de_inscricao_da_empresa = '2';
			$this->numero_de_inscricao_da_empresa = '96533310000140';
			$this->codigo_do_convenio_no_banco = '002850943001417019'.$this->brancos(2).'';
			$this->agencia_mantenedora_da_conta = '02962';
			$this->digito_verificador_da_agencia = '9';
			$this->numero_da_conta_corrente = '000000017877';
			$this->digito_verificador_da_conta = '2';
			$this->nome_da_empresa = 'Contador Amigo Ltda. - ME     ';
			$this->nome_do_banco = "BANCO DO BRASIL S.A".$this->brancos(30-strlen("BANCO DO BRASIL S.A"));
			$this->codigo_remessa_retorno = '1';//1 se refere a remessa, 2 a retorno
			$this->data_de_geracao_do_arquivo = date('dmY');
			$this->hora_de_geracao_do_arquivo = date('Hms');
		}		
		function getArquivo(){
			return $this->codigo_do_banco_na_compensacao.$this->lote_de_servico.$this->tipo_de_registro.$this->brancos(9).$this->tipo_de_inscricao_da_empresa.$this->numero_de_inscricao_da_empresa.$this->codigo_do_convenio_no_banco.$this->agencia_mantenedora_da_conta.$this->digito_verificador_da_agencia.$this->numero_da_conta_corrente.$this->digito_verificador_da_conta.$this->brancos(1).$this->nome_da_empresa.$this->nome_do_banco.$this->brancos(10).$this->codigo_remessa_retorno.$this->data_de_geracao_do_arquivo.$this->hora_de_geracao_do_arquivo.$this->zeros(6).$this->zeros(3).$this->brancos(5).$this->brancos(20).$this->brancos(20).$this->brancos(29)."\n";
		}
	}

	class Registro_header_lote{

		public $codigo_do_banco_na_compensacao;
		public $lote_de_servico;
		public $tipo_de_registro;
		public $tipo_de_operacao;
		public $tipo_de_servico;
		public $n_da_versao_do_layout_do_lote;
		public $tipo_de_inscricao_da_empresa;
		public $n_de_inscricao_da_empresa;
		public $codigo_do_convenio_no_banco;
		public $agencia_mantenedora_da_conta;
		public $digito_verificador_da_conta;
		public $numero_da_conta_corrente;
		public $digito_verificador_da_conta_2;
		public $digito_verificador_da_ag_conta;
		public $nome_da_empresa;
		public $mensagem_1;
		public $mensagem_2;
		public $numero_remessa_retorno;
		public $data_de_gravacao_remessa_retorno;
		public $data_do_credito;
		public $uso_exclusivo_febraban_cnab;

		function brancos($tam){
			$string = '';
			for ($i=0; $i < $tam; $i++) { 
					$string .= ' ';
				}
			return $string;
		}

		function zeros($tam){
			$string = '';
			for ($i=0; $i < $tam; $i++) { 
					$string .= '0';
				}
			return $string;
		}
		function gerar($path="remessa/"){
			include $path.'header_lote.php'; 
		}
		function __construct($teste = false){
			$this->codigo_do_banco_na_compensacao = "001";
			$this->lote_de_servico = "0001";
			$this->tipo_de_registro = "1";
			$this->tipo_de_operacao = "R";
			$this->tipo_de_servico = "01";
			$this->n_da_versao_do_layout_do_lote = "000";
			$this->tipo_de_inscricao_da_empresa = "2";
			$this->n_de_inscricao_da_empresa = "096533310000140";
			$this->codigo_do_convenio_no_banco = "002850943001417019  ";
			if($teste)
				$this->codigo_do_convenio_no_banco = "002850943001417019TS";
			$this->agencia_mantenedora_da_conta = "02962";
			$this->digito_verificador_da_conta = "9";
			$this->numero_da_conta_corrente = "000000017877";
			$this->digito_verificador_da_conta_2 = "2";
			$this->nome_da_empresa = "Contador Amigo Ltda. - ME     ";
			$this->data_de_gravacao_remessa_retorno = date('dmY');
		}
		function getArquivo(){
			return $this->codigo_do_banco_na_compensacao.$this->lote_de_servico.$this->tipo_de_registro.$this->tipo_de_operacao.$this->tipo_de_servico.$this->brancos(2).$this->n_da_versao_do_layout_do_lote.$this->brancos(1).$this->tipo_de_inscricao_da_empresa.$this->n_de_inscricao_da_empresa.$this->codigo_do_convenio_no_banco.$this->agencia_mantenedora_da_conta.$this->digito_verificador_da_conta.$this->numero_da_conta_corrente.$this->digito_verificador_da_conta_2.$this->brancos(1).$this->nome_da_empresa.$this->brancos(40).$this->brancos(40).$this->zeros(8).$this->data_de_gravacao_remessa_retorno.$this->brancos(8).$this->brancos(33)."\n";
		}
	}

	class Registro_segmento_p{
		
		public $codigo_do_banco_na_compensacao;
		public $lote_de_servico;
		public $tipo_de_registro;
		public $n_sequencial_do_registro_no_lote;
		public $cod_segmento_do_registro_detalhe;
		public $uso_exclusivo_febraban_cnab;
		public $codigo_de_movimento_remessa;
		public $agencia_mantenedora_da_conta;
		public $digito_verificador_da_agencia;
		public $numero_da_conta_corrente;
		public $digito_verificador_da_conta;
		public $digito_verificador_da_ag_conta;
		public $identificacao_do_titulo_no_banco;
		public $codigo_da_carteira;
		public $forma_de_cadastr_do_titulo_no_banco;
		public $tipo_de_documento;
		public $identificacao_da_emissao_do_bloqueto;
		public $identificacao_da_distribuicao;
		public $numero_do_documento_de_cobranca;
		public $data_de_vencimento_do_titulo;
		public $valor_nominal_do_titulo;
		public $agencia_encarregada_da_cobranca;
		public $digito_verificador_da_agencia_2;
		public $especie_do_titulo;
		public $identific_de_titulo_aceito_nao_aceit;
		public $data_da_emissao_do_titulo;
		public $codigo_do_juros_de_mora;
		public $data_do_juros_de_mora;
		public $juros_de_mora_por_dia_taxa;
		public $codigo_do_desconto_1;
		public $data_do_desconto_1;
		public $valor_percentual_a_ser_concedido;
		public $valor_do_iof_a_ser_recolhido;
		public $valor_do_abatimento;
		public $identificacao_do_titulo_na_empresa;
		public $codigo_para_protesto;
		public $numero_de_dias_para_protesto;
		public $codigo_para_baixa_devolucao;
		public $numero_de_dias_para_baixa_devolucao;
		public $codigo_da_moeda;
		public $n_do_contrato_da_operacao_de_cred;
		public $uso_livre_banco_empresa;

		function brancos($tam){
			$string = '';
			for ($i=0; $i < $tam; $i++) { 
					$string .= ' ';
				}
			return $string;
		}

		function zeros($tam){
			$string = '';
			for ($i=0; $i < $tam; $i++) { 
					$string .= '0';
				}
			return $string;
		}
		function gerar($path="remessa/"){
			include $path.'segmento_p.php'; 
		}
		function formataData($data){
			$aux = explode('-', $data);
			return $aux[2].$aux[1].$aux[0];
		}
		function setDados($dados,$cont){

			include("../../conect.php");

			$dados_user = mysql_query("SELECT * FROM dados_cobranca WHERE id = '".$dados['user']."' ");
			$usuario=mysql_fetch_array($dados_user);
			$dados['valor_get'] = floatval($dados['valor_get']) * 100;
			// $this->codigo_do_banco_na_compensacao = $dados['codigo_do_banco_na_compensacao'];
			// $this->lote_de_servico = $dados['lote_de_servico'];
			// $this->tipo_de_registro = $dados['tipo_de_registro'];
			$this->n_sequencial_do_registro_no_lote = $this->zeros(5-strlen(strval($cont))).strval($cont);
			// $this->cod_segmento_do_registro_detalhe = $dados['cod_segmento_do_registro_detalhe'];
			// $this->codigo_de_movimento_remessa = $dados['codigo_de_movimento_remessa'];
			// $this->agencia_mantenedora_da_conta = $dados['agencia_mantenedora_da_conta'];
			// $this->digito_verificador_da_agencia = $dados['digito_verificador_da_agencia'];
			// $this->numero_da_conta_corrente = $dados['numero_da_conta_corrente'];
			// $this->digito_verificador_da_conta = $dados['digito_verificador_da_conta'];
			$this->identificacao_do_titulo_no_banco = $dados['nosso_numero']."   ";
			// $this->codigo_da_carteira = $dados['codigo_da_carteira'];
			// $this->identificacao_da_emissao_do_bloqueto = $dados['identificacao_da_emissao_do_bloqueto'];
			// $this->identificacao_da_distribuicao = $dados['identificacao_da_distribuicao'];
			// $this->numero_do_documento_de_cobranca = $dados['numero_do_documento_de_cobranca'];
			$this->data_de_vencimento_do_titulo = $this->formataData($dados['vencto_get']);
			$this->valor_nominal_do_titulo = $this->zeros(15 - strlen(strval($dados['valor_get']) ) ).$dados['valor_get']   ;
			// $this->agencia_encarregada_da_cobranca = $dados['agencia_encarregada_da_cobranca'];
			// $this->digito_verificador_da_agencia_2 = $dados['digito_verificador_da_agencia_2'];
			// $this->especie_do_titulo = $dados['especie_do_titulo'];
			// $this->identific_de_titulo_aceito_nao_aceit = $dados['identific_de_titulo_aceito_nao_aceit'];
			// $this->codigo_do_juros_de_mora = $dados['codigo_do_juros_de_mora'];

			$dados_user_aux = mysql_query("SELECT * FROM login WHERE id = '".$dados['user']."' ");
			
			$usuario=mysql_fetch_array($dados_user_aux);

			if( $usuario['status'] == 'demoInativo' || $usuario['status'] == 'demo' || $usuario['status'] == 'cancelado' ){
				$this->juros_de_mora_por_dia_taxa = "000000000000000";
				$this->codigo_do_juros_de_mora = '3';
			}
			else{
				$this->juros_de_mora_por_dia_taxa = "000000000000022";	
			}
			// $this->codigo_do_desconto_1 = $dados['codigo_do_desconto_1'];
			// $this->data_do_desconto_1 = $dados['data_do_desconto_1'];
			// $this->codigo_para_protesto = $dados['codigo_para_protesto'];
			// $this->numero_de_dias_para_protesto = $dados['numero_de_dias_para_protesto'];
			// $this->codigo_da_moeda = $dados['codigo_da_moeda'];
		}
		function __construct()
		{
			$this->codigo_do_banco_na_compensacao = "001";
			$this->lote_de_servico = "0001";
			$this->tipo_de_registro = "3";
			$this->n_sequencial_do_registro_no_lote = "00001";
			$this->cod_segmento_do_registro_detalhe = "P";
			$this->codigo_de_movimento_remessa = "01";
			$this->agencia_mantenedora_da_conta = "02962";
			$this->digito_verificador_da_agencia = "9";
			$this->numero_da_conta_corrente = "000000017877";
			$this->digito_verificador_da_conta = "2";
			$this->identificacao_do_titulo_no_banco = "28509430100550617   ";
			$this->codigo_da_carteira = "7";
			$this->identificacao_da_emissao_do_bloqueto = "2";
			$this->identificacao_da_distribuicao = "2";
			$this->numero_do_documento_de_cobranca = $this->zeros(15-strlen("1"))."1";
			$this->data_de_vencimento_do_titulo = date('dmY', strtotime('+5 days'));
			$this->valor_nominal_do_titulo = "000000000005900";
			$this->agencia_encarregada_da_cobranca = "00000";
			$this->digito_verificador_da_agencia_2 = $this->brancos(1);
			$this->especie_do_titulo = "02";
			$this->identific_de_titulo_aceito_nao_aceit = "N";
			$this->codigo_do_juros_de_mora = "2";//'1' = Valor por Dia '2' = Taxa Mensal '3' = Isento
			$this->codigo_do_desconto_1 = "0";
			$this->data_do_desconto_1 = "00000000";
			$this->codigo_para_protesto = "3";//O Banco do Brasil trata somente os códigos '1' – Protestar dias corridos, '2' – Protestar dias úteis, e '3' – Não protestar. No caso de carteira 31 ou carteira 11/17 modalidade Vinculada, se não informado nenhum código, osistema assume automaticamente Protesto em 3 dias úteis.
			$this->numero_de_dias_para_protesto = "00";
			$this->codigo_da_moeda = "09";
		}

		function getArquivo(){
			return $this->codigo_do_banco_na_compensacao.$this->lote_de_servico.$this->tipo_de_registro.$this->n_sequencial_do_registro_no_lote.$this->cod_segmento_do_registro_detalhe.$this->brancos(1).$this->codigo_de_movimento_remessa.$this->agencia_mantenedora_da_conta.$this->digito_verificador_da_agencia.$this->numero_da_conta_corrente.$this->digito_verificador_da_conta.$this->brancos(1).$this->identificacao_do_titulo_no_banco.$this->codigo_da_carteira.$this->brancos(1).$this->brancos(1).$this->identificacao_da_emissao_do_bloqueto.$this->identificacao_da_distribuicao.$this->numero_do_documento_de_cobranca.$this->data_de_vencimento_do_titulo.$this->valor_nominal_do_titulo.$this->agencia_encarregada_da_cobranca.$this->brancos(1).$this->especie_do_titulo.$this->identific_de_titulo_aceito_nao_aceit.date('dmY').$this->codigo_do_juros_de_mora.$this->zeros(8).$this->juros_de_mora_por_dia_taxa.$this->codigo_do_desconto_1.$this->data_do_desconto_1.$this->zeros(15).$this->zeros(15).$this->zeros(15).$this->zeros(25).$this->codigo_para_protesto.$this->numero_de_dias_para_protesto.$this->zeros(1).$this->zeros(3).$this->codigo_da_moeda.$this->zeros(10).$this->brancos(1)."\n";
		}

	}

	class Registro_segmento_q{

		public $codigo_do_banco_na_compensacao;
		public $lote_de_servico;
		public $tipo_de_registro;
		public $n_sequencial_do_registro_no_lote;
		public $cod_segmento_do_registro_detalhe;
		public $uso_exclusivo_febraban_cnab;
		public $codigo_de_movimento_remessa;
		public $tipo_de_inscricao;
		public $numero_de_inscricao;
		public $nome;
		public $endereco;
		public $bairro;
		public $cep;
		public $sufixo_do_cep;
		public $cidade;
		public $unidade_da_federacao;
		public $nome_do_sacador_avalista;
		public $cod_bco_corresp_na_compensacao;
		public $nosso_n_no_banco_correspondente;

		function brancos($tam){
			$string = '';
			for ($i=0; $i < $tam; $i++) { 
					$string .= ' ';
				}
			return $string;
		}

		function zeros($tam){
			$string = '';
			for ($i=0; $i < $tam; $i++) { 
					$string .= '0';
				}
			return $string;
		}
		function gerar($path="remessa/"){
			include $path.'segmento_q.php'; 
		}
		function normaliza($string) {
			$table = array('Š'=>'S','š'=>'s','Đ'=>'Dj','đ'=>'dj','Ž'=>'Z','ž'=>'z','Č'=>'C','č'=>'c','Ć'=>'C','ć'=>'c','À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Å'=>'A','Æ'=>'A','Ç'=>'C','È'=>'E','É'=>'E',
			'Ê'=>'E','Ë'=>'E','Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I','Ñ'=>'N','Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O','Ø'=>'O','Ù'=>'U','Ú'=>'U','Û'=>'U','Ü'=>'U','Ý'=>'Y','Þ'=>'B','ß'=>'Ss',
			'à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a','æ'=>'a','ç'=>'c','è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ð'=>'o','ñ'=>'n','ò'=>'o','ó'=>'o',
			'ô'=>'o','õ'=>'o','ö'=>'o','ø'=>'o','ù'=>'u','ú'=>'u','û'=>'u','˚'=>' ','º'=>' ','ý'=>'y','þ'=>'b','ÿ'=>'y','Ŕ'=>'R','ŕ'=>'r','?'=>' ');
			return strtr($string, $table);
		}
		function setDados($dados,$cont){

			include("../../conect.php");

			$dados_user = mysql_query("SELECT * FROM dados_cobranca WHERE id = '".$dados['user']."' ");
			
			$usuario=mysql_fetch_array($dados_user);

			// $this->codigo_do_banco_na_compensacao = $dados['codigo_do_banco_na_compensacao'];
			// $this->lote_de_servico = $dados['lote_de_servico'];
			// $this->tipo_de_registro = $dados['tipo_de_registro'];
			$this->n_sequencial_do_registro_no_lote = $this->zeros(5-strlen(strval($cont))).strval($cont);
			// $this->cod_segmento_do_registro_detalhe = $dados['cod_segmento_do_registro_detalhe'];
			// $this->codigo_de_movimento_remessa = $dados['codigo_de_movimento_remessa'];
			// $this->tipo_de_inscricao = $dados['tipo_de_inscricao'];
			// $this->numero_de_inscricao = $dados['numero_de_inscricao'];

			$documento = str_replace('.', '', $usuario['documento']);
			$documento = str_replace('-', '', $documento);
			$documento = str_replace('/', '', $documento);
			if( $usuario['tipo'] == "PJ" ){
					$this->numero_de_inscricao = substr( $this->zeros(15-strlen($this->normaliza($documento))).$this->normaliza($documento),0,15);
					$this->tipo_de_inscricao = "2";
			}
			else{
				$this->numero_de_inscricao = substr( $this->zeros(15-strlen($this->normaliza($documento))).$this->normaliza($documento),0,15);
				$this->tipo_de_inscricao = "1";	
			}


			$this->nome = substr( $this->normaliza($usuario['sacado']).$this->brancos(40-strlen($this->normaliza($usuario['sacado'])))."",0,40);
			$this->endereco = substr( $this->normaliza($usuario['endereco']).$this->brancos(40-strlen($this->normaliza($usuario['endereco'])))."",0,40);
			$this->bairro = substr($this->normaliza($usuario['bairro']).$this->brancos(15-strlen($this->normaliza($usuario['bairro'])))."",0,15);
			$aux_cep = explode('-', $usuario['cep']);
			$this->cep = $aux_cep[0];
			$this->sufixo_do_cep = $aux_cep[1];
			$this->cidade = substr($this->normaliza($usuario['cidade']).$this->brancos(15-strlen($this->normaliza($usuario['cidade'])))."",0,15);
			$this->unidade_da_federacao = $usuario['uf'];
		}
		function __construct(){

			$this->codigo_do_banco_na_compensacao = "001";
			$this->lote_de_servico = "0001";
			$this->tipo_de_registro = "3";
			$this->n_sequencial_do_registro_no_lote = "00002";
			$this->cod_segmento_do_registro_detalhe = "Q";
			$this->codigo_de_movimento_remessa = "01";
			$this->tipo_de_inscricao = "2";
			$this->numero_de_inscricao = "096533310000140";
			$this->nome = "NOME DA PESSOA".$this->brancos(40-strlen('nome da pessoa'))."";
			$this->endereco = "ENDERECO DA PESSOA".$this->brancos(40-strlen('ENDERECO DA PESSOA'))."";
			$this->bairro = "BAIRRO".$this->brancos(15-strlen('BAIRRO'))."";
			$this->cep = "06700";
			$this->sufixo_do_cep = "230";
			$this->cidade = "COTIA".$this->brancos(15-strlen('COTIA'))."";
			$this->unidade_da_federacao = "SP";

		}

		function getArquivo(){
			return $this->codigo_do_banco_na_compensacao.$this->lote_de_servico.$this->tipo_de_registro.$this->n_sequencial_do_registro_no_lote.$this->cod_segmento_do_registro_detalhe.$this->brancos(1) .$this->codigo_de_movimento_remessa.$this->tipo_de_inscricao.$this->numero_de_inscricao.$this->nome.$this->endereco.$this->bairro.$this->cep.$this->sufixo_do_cep.$this->cidade.$this->unidade_da_federacao.$this->brancos(1) .$this->brancos(15).$this->brancos(40).$this->zeros(3).$this->brancos(20).$this->zeros(8)."\n";
		}

	}

	class Registro_segmento_r{
		
		private $codigo_do_banco_na_compensacao;
		private $lote_de_servico;
		private $tipo_de_registro;
		private $n_sequencial_do_registro_no_lote;
		private $cod_segmento_do_registro_detalhe;
		private $codigo_de_movimento_remessa;
		private $codigo_da_multa;
		private $data_da_multa;
		private $valor_percentual_a_ser_aplicado;

		function brancos($tam){
			$string = '';
			for ($i=0; $i < $tam; $i++) { 
					$string .= ' ';
				}
			return $string;
		}

		function zeros($tam){
			$string = '';
			for ($i=0; $i < $tam; $i++) { 
					$string .= '0';
				}
			return $string;
		}
		function gerar($path="remessa/"){
			include $path.'segmento_p.php'; 
		}
		function formataData($data){
			$aux = explode('-', $data);
			return $aux[2].$aux[1].$aux[0];
		}
		function setDados($dados,$cont){

			include("../../conect.php");
			$this->n_sequencial_do_registro_no_lote = $this->zeros(5-strlen(strval($cont))).strval($cont);

			$valor_aux = floatval($dados['valor_get']);
			$multa_aux = $valor_aux * 0.02;
			$multa_aux = strval($multa_aux);
			
			$multa_aux = str_replace(".", "", number_format($multa_aux,2,".", ""));
			
			$multa_aux = $this->zeros(15-strlen(strval($multa_aux))).strval($multa_aux);


//			if( $dados['gerar_multa'] == 'true' ){
				$this->codigo_da_multa = "1";
				$this->data_da_multa = $this->formataData($dados['vencto_get']);
				$this->valor_percentual_a_ser_aplicado = '|'.$multa_aux."|";
//			}
//			else{
//				$this->codigo_da_multa = "0";
//				$this->data_da_multa = "00000000";
//				$this->valor_percentual_a_ser_aplicado = "000000000000000";	
//			}

			$dados_user_aux = mysql_query("SELECT * FROM login WHERE id = '".$dados['user']."' ");
			
			$usuario=mysql_fetch_array($dados_user_aux);

			if( $usuario['status'] == 'demoInativo' || $usuario['status'] == 'demo' || $usuario['status'] == 'cancelado' ){
				$this->codigo_da_multa = "0";
				$this->data_da_multa = "00000000";
				$this->valor_percentual_a_ser_aplicado = "000000000000000";		
			}

		}
		function __construct()
		{	

			$this->codigo_do_banco_na_compensacao = "001";
			$this->lote_de_servico = "0001";
			$this->tipo_de_registro = "3";
			$this->n_sequencial_do_registro_no_lote = "00001";
			$this->cod_segmento_do_registro_detalhe = "R";
			$this->codigo_de_movimento_remessa = "01";
			$this->codigo_da_multa = "";
			$this->data_da_multa = "";
			$this->valor_percentual_a_ser_aplicado = "";
		}

		function getArquivo(){
			return $this->codigo_do_banco_na_compensacao.$this->lote_de_servico.$this->tipo_de_registro.$this->n_sequencial_do_registro_no_lote.$this->cod_segmento_do_registro_detalhe.$this->brancos(1).$this->codigo_de_movimento_remessa.$this->zeros(1).$this->zeros(8).$this->zeros(15).$this->zeros(1).$this->zeros(8).$this->zeros(15).$this->codigo_da_multa.$this->data_da_multa.$this->valor_percentual_a_ser_aplicado.$this->zeros(10).$this->zeros(40).$this->zeros(40).$this->zeros(20).$this->zeros(8).$this->zeros(3).$this->zeros(5).$this->zeros(1).$this->zeros(12).$this->zeros(1).$this->zeros(1).$this->zeros(1).$this->brancos(9)."\n";
		}

	}


	class Registro_trailer_arquivo{
		



		public $codigo_do_banco_na_compensacao;
		public $lote_de_servico;
		public $tipo_de_registro;
		public $uso_exclusivo_febraban_cnab;
		public $quantidade_de_lotes_do_arquivo;
		public $quantidade_de_registros_do_arquivo;
		public $qtde_de_contas_p_conc;
		public $uso_exclusivo_febraban_cnab_2;

		function brancos($tam){
			$string = '';
			for ($i=0; $i < $tam; $i++) { 
					$string .= ' ';
				}
			return $string;
		}

		function zeros($tam){
			$string = '';
			for ($i=0; $i < $tam; $i++) { 
					$string .= '0';
				}
			return $string;
		}

		function __construct(){

			$this->codigo_do_banco_na_compensacao = "001";
			$this->lote_de_servico = "9999";
			$this->tipo_de_registro = "9";
			$this->uso_exclusivo_febraban_cnab = $this->brancos(9);
			$this->quantidade_de_lotes_do_arquivo = $this->brancos(6-strlen("0"))."0";//Informar quantos lotes o arquivo possui.
			$this->quantidade_de_registros_do_arquivo = $this->brancos(6-strlen("20"))."20";//Quantidade igual ao número total de registros (linhas) do arquivo.
			$this->qtde_de_contas_p_conc = $this->brancos(6);
			$this->uso_exclusivo_febraban_cnab_2 = $this->brancos(205);

		}

		function getArquivo(){
			return $this->codigo_do_banco_na_compensacao.$this->lote_de_servico.$this->tipo_de_registro.$this->uso_exclusivo_febraban_cnab.$this->quantidade_de_lotes_do_arquivo.$this->quantidade_de_registros_do_arquivo.$this->qtde_de_contas_p_conc.$this->uso_exclusivo_febraban_cnab_2."\n";
		}
	}

	class Registro_trailer_lote{

		public $codigo_do_banco_na_compensacao;
		public $lote_de_servico;
		public $tipo_de_registro;
		public $uso_exclusivo_febraban_cnab;
		public $quantidade_de_registros_do_lote;
		public $uso_exclusivo_febraban_cnab_2;

		function brancos($tam){
			$string = '';
			for ($i=0; $i < $tam; $i++) { 
					$string .= ' ';
				}
			return $string;
		}

		function zeros($tam){
			$string = '';
			for ($i=0; $i < $tam; $i++) { 
					$string .= '0';
				}
			return $string;
		}

		function __construct(){

			$this->codigo_do_banco_na_compensacao = "001";
			$this->lote_de_servico = "0001";
			$this->tipo_de_registro = "5";
			$this->uso_exclusivo_febraban_cnab = $this->brancos(9);
			$this->quantidade_de_registros_do_lote = $this->brancos(6-strlen("20"))."20";//Total de linhas do lote (inclui Header de lote, Registros e Trailer de lote).
			$this->uso_exclusivo_febraban_cnab_2 = $this->brancos(217);

		}

		function getArquivo(){
			return $this->codigo_do_banco_na_compensacao.$this->lote_de_servico.$this->tipo_de_registro.$this->uso_exclusivo_febraban_cnab.$this->quantidade_de_registros_do_lote.$this->uso_exclusivo_febraban_cnab_2."\n";
		}



	} 

	class File{

		function write( $content , $file = "teste.rem" ){
			$myfile = fopen($file, "w") or die("Unable to open file!");
			fwrite($myfile, $content);		
			fclose($myfile);
		}

	}

?>


