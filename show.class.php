<?php
	
	// session_start();

	// include 'conect.php';

?>
<?php 
	
	//Esta classe define condições de acordo com os dados do usuario ou empresa para exibir ou esconder informações baseadas em Cnaes, Estado, entre outros
	class Show{

		private $id_user;
		private $id_empresa;
		private $cnae;
		private $estado;
		private $cidade;

		//Inicia as variaies com os dados do clientes e puxa os dados necessários do banco
		function __construct(){
			$this->id_empresa = $_SESSION['id_empresaSecao'];
			$this->id_user = $_SESSION['id_userSecaoMultiplo'];

			$this->getCnae();
			$this->getEstado();
			$this->getCidade();

		}
		function getCnae(){
			$this->cnae = array();
			$consulta = mysql_query("SELECT * FROM dados_da_empresa_codigos WHERE id = '".$this->id_empresa."' ");
			while($objeto=mysql_fetch_array($consulta)){
				$this->cnae [] = $objeto['cnae'];
			}
			return $this->cnae;
		}
		function getEstado(){
			$consulta = mysql_query("SELECT estado FROM dados_da_empresa WHERE id = '".$this->id_empresa."' ");
			$objeto=mysql_fetch_array($consulta);
			$this->estado= $objeto['estado'];

			return $this->estado;
		}
		function getCidade(){
			$consulta = mysql_query("SELECT cidade FROM dados_da_empresa WHERE id = '".$this->id_empresa."' ");
			$objeto=mysql_fetch_array($consulta);
			$this->cidade= $objeto['cidade'];

			return $this->cidade;
		}
		function isConstrucaoCivil(){
			foreach ($this->cnae as $value) {
				$construcao_civil [] = '412';
				$construcao_civil [] = '432';
				$construcao_civil [] = '433';
				$construcao_civil [] = '439';
				$aux = $value[0].$value[1].$value[2];
				if( in_array( $aux , $construcao_civil) )
					return true;
			}
			return false;
		}
		function isEstado($string){
			if( strtoupper($this->estado) == strtoupper($string) )
				return true;
			else
				return false;
		}
	}

?>