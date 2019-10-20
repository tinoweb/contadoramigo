<?php
	
	//$conexao = mysql_connect("177.153.16.160", "contadoramigo", "ttq231kz");
//	$db = mysql_select_db("contadoramigo");
//	mysql_query("SET NAMES 'utf8'");
//	mysql_query('SET character_set_connection=utf8');
//	mysql_query('SET character_set_client=utf8');
//	mysql_query('SET character_set_results=utf8');

// inclui o arquivo de conexão.
require_once "conect.php";

?>

<?php 
	
	class Historico_cobranca {
		
		private $idHistorico;
		private $id;
		private $data_pagamento;
		private $tipo_cobranca;
		private $status_pagamento;
		private $envio_email;
		function __construct($dados=NULL){
			if( $dados != NULL ){
				$this->setidHistorico($dados['idHistorico']);
				$this->setid($dados['id']);
				$this->setdata_pagamento($dados['data_pagamento']);
				$this->settipo_cobranca($dados['tipo_cobranca']);
				$this->setstatus_pagamento($dados['status_pagamento']);
				$this->setenvio_email($dados['envio_email']);
			}
		}
		function getenvio_email(){
			return $this->envio_email;
		}
		function setenvio_email($string){
			$this->envio_email = $string;
		}
		function getstatus_pagamento(){
			return $this->status_pagamento;
		}
		function setstatus_pagamento($string){
			$this->status_pagamento = $string;
		}
		function gettipo_cobranca(){
			return $this->tipo_cobranca;
		}
		function settipo_cobranca($string){
			$this->tipo_cobranca = $string;
		}
		function getdata_pagamento(){
			return $this->data_pagamento;
		}
		function setdata_pagamento($string){
			$this->data_pagamento = $string;
		}
		function getid(){
			return $this->id;
		}
		function setid($string){
			$this->id = $string;
		}
		function getidHistorico(){
			return $this->idHistorico;
		}
		function setidHistorico($string){
			$this->idHistorico = $string;
		}
	}
	class Dados_user{
		private $id_user;
		private $status_login;
		private $forma_pagameto;

		function getforma_pagameto(){
			return $this->forma_pagameto;
		}
		function setforma_pagameto($string){
			$this->forma_pagameto = $string;
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
		function getDadosUser(){
			$sql_dados_login = "
				SELECT 
					l.id
					, l.email
					, l.status
					, dc.forma_pagameto
				FROM 
					login l
					INNER JOIN dados_cobranca dc ON l.id = dc.id
				WHERE 
					l.id='".$this->getid_user()."' 
				LIMIT 0, 1
				";
			$resultado_dados_login = mysql_query($sql_dados_login);
			$linha_dados_login = mysql_fetch_array($resultado_dados_login);

			$this->setstatus_login($linha_dados_login['status']);
			$this->setforma_pagameto($linha_dados_login['forma_pagameto']);
		}
	}
	class Gerar_boletos{
		function getPagamentosAVencer(){
			$consulta = mysql_query("SELECT * FROM historico_cobranca WHERE status_pagamento = 'a vencer' AND DATEDIFF(data_pagamento, DATE(now())) <= 45 ");
			return $consulta;
		}
		function __construct(){		

			$pagamentos = $this->getPagamentosAVencer();
			while( $pagamento = mysql_fetch_array($pagamentos) ){
				$historico = new Historico_cobranca($pagamento);
				$user = new Dados_user();
				$user->setid_user($historico->getid());
				$user->getDadosUser();
				if( $user->getstatus_login() == 'ativo' && $user->getforma_pagameto() == 'boleto' ){
					// echo $historico->getidHistorico().'<br>';
					// echo $user->getid_user().'<br>';
					file_get_contents("https://www.contadoramigo.com.br/boleto.class.php?via=0&id_historico=".$historico->getidHistorico()."&tipo=mensalidade&id_user=".$user->getid_user());
				}
			}

		}

	}

	$boletos = new Gerar_boletos();

?>
