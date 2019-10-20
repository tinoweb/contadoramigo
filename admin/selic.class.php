<?php 
	
	include '../conect.php';

	class Selic{
		
		private $ano;

		function __construct(){
			
			if( isset($_GET['filtro']) )
				$this->setano($_GET['filtro']);
			else
				$this->setano(date("Y"));


			$this->getDadosAno();
		}
		function getDadosAno(){
			
			$consulta = mysql_query("SELECT * FROM selic WHERE ano = '".$this->getano()."' ");
			
			if( mysql_num_rows($consulta) == 0 ){
			
				$this->criarDadosAno();
				$consulta = mysql_query("SELECT * FROM selic WHERE ano = '".$this->getano()."' ");

			}

			return $consulta;

		}
		function getAnos(){

			$consulta = mysql_query("SELECT * FROM selic GROUP BY ano ORDER BY ano DESC");
			return $consulta;

		}
		function getMes($mes){
			
			if($mes == 1)
				return "Janeiro";
			if($mes == 2)
				return "Fevereiro";
			if($mes == 3)
				return "Março";
			if($mes == 4)
				return "Abril";
			if($mes == 5)
				return "Maio";
			if($mes == 6)
				return "Junho";
			if($mes == 7)
				return "Julho";
			if($mes == 8)
				return "Agosto";
			if($mes == 9)
				return "Setembro";
			if($mes == 10)
				return "Outubro";
			if($mes == 11)
				return "Novembro";
			if($mes == 12)
				return "Dezembro";

		}
		function criarDadosAno(){
			
			$consulta = mysql_query("INSERT INTO `selic`(`ano`, `mes`, `valor`) VALUES ( '".$this->getano()."','1','' )");
			$consulta = mysql_query("INSERT INTO `selic`(`ano`, `mes`, `valor`) VALUES ( '".$this->getano()."','2','' )");
			$consulta = mysql_query("INSERT INTO `selic`(`ano`, `mes`, `valor`) VALUES ( '".$this->getano()."','3','' )");
			$consulta = mysql_query("INSERT INTO `selic`(`ano`, `mes`, `valor`) VALUES ( '".$this->getano()."','4','' )");
			$consulta = mysql_query("INSERT INTO `selic`(`ano`, `mes`, `valor`) VALUES ( '".$this->getano()."','5','' )");
			$consulta = mysql_query("INSERT INTO `selic`(`ano`, `mes`, `valor`) VALUES ( '".$this->getano()."','6','' )");
			$consulta = mysql_query("INSERT INTO `selic`(`ano`, `mes`, `valor`) VALUES ( '".$this->getano()."','7','' )");
			$consulta = mysql_query("INSERT INTO `selic`(`ano`, `mes`, `valor`) VALUES ( '".$this->getano()."','8','' )");
			$consulta = mysql_query("INSERT INTO `selic`(`ano`, `mes`, `valor`) VALUES ( '".$this->getano()."','9','' )");
			$consulta = mysql_query("INSERT INTO `selic`(`ano`, `mes`, `valor`) VALUES ( '".$this->getano()."','10','' )");
			$consulta = mysql_query("INSERT INTO `selic`(`ano`, `mes`, `valor`) VALUES ( '".$this->getano()."','11','' )");
			$consulta = mysql_query("INSERT INTO `selic`(`ano`, `mes`, `valor`) VALUES ( '".$this->getano()."','12','' )");
			
		}
		function getano(){
			return $this->ano;
		}
		function setano($string){
			$this->ano = $string;
		}
	}

?>