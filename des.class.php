<?php 

	class DES{

		private $id_user;
		private $id_empresa;

		private $id;
		private $uf;
		private $municipio;
		private $prestados;
		private $tomados;
		private $outro_m;
		private $tipo;
		private $nome;
		private $abreviacao;
		private $link;
		private $tutorial;
		private $vencimento;
		private $criterio;
		private $retencao_iss;
		private $observacao;
		private $fonte;
		private $valor;
		private $NomeSite;

		private $acao;

		function getDadosDes(){

			$consulta = mysql_query("SELECT * FROM des WHERE municipio = '".$this->getCidade()."' OR municipio = 'padrao' ORDER BY ID ASC LIMIT 1 ");
			$objeto=mysql_fetch_array($consulta);

			$this->setId($objeto['id']);
			$this->setUf($objeto['uf']);
			$this->setCidade($objeto['municipio']);
			$this->setPrestados($objeto['prestados']);
			$this->setTomados($objeto['tomados']);
			$this->setTomados_outro_municipio($objeto['outro_m']);
			$this->setTipo($objeto['tipo']);
			$this->setNome($objeto['nome']);
			$this->setAbreviacao($objeto['abreviacao']);
			$this->setLink($objeto['link']);
			$this->setTutorial($objeto['tutorial']);
			$this->setData($objeto['vencimento']);
			$this->setCriterio($objeto['criterio']);
			$this->setRetencao_iss($objeto['retencao_iss']);
			$this->setObservacao($objeto['observacao']);
			$this->setFonte($objeto['fonte']);
			$this->setvalor($objeto['valor']);
			$this->setNomeSite($objeto['nome_site']);
		}
		function getTextoValor(){
			
			if( $this->getvalor() != '' && $this->getvalor() != 0 ) {
				//echo ', mas apenas para empresas que tiveram, nos ano anterior, faturamento maior que R$ '.$this->getvalorRS();
				echo ', mas apenas para empresas que tiveram, nos ultimos 12 meses, faturamento maior que R$ '.$this->getvalorRS();
			}
		}
		function getvalorRS(){
			return number_format( $this->valor , 2 , ',' , '.' );
		}
		function getvalor(){
			return $this->valor;
		}
		function setvalor($string){
			$this->valor = $string;
		}
		function getDiaTexto(){
			if( strtolower($this->getCriterio()) == 'corridos' ){
				return 'dia '.$this->getDataVal().' do mês';	
			}
			else{
				return $this->getDataVal().'º dia útil do mês';	
			}
		}
		function getTutorialTexto(){
			if( $this->getTutorial() != '' )
				return '<a href="'.$this->getTutorial().'" target="_blank">manual de uso</a>';
			else
				return '';
		}
		function getLinkTexto(){
			if($this->getNomeSite()) {
				return '<a href="'.$this->getLink().'" target="_blank">'.$this->getNomeSite().'</a>';
			} else {
				return '<a href="'.$this->getLink().'" target="_blank">este site</a>';
			}			
		}
		function getTiposServicos(){
			if( $this->getPrestados() && !$this->getTomados() && !$this->getTomados_outro_municipio() )
				return "prestados";
			if( $this->getPrestados() && $this->getTomados() && !$this->getTomados_outro_municipio() )
				return "prestados e/ou tomados";
			if( $this->getPrestados() && $this->getTomados() && $this->getTomados_outro_municipio() )
				return "prestados e/ou tomados";
			if( !$this->getPrestados() && $this->getTomados() && $this->getTomados_outro_municipio() )
				return "tomados";
			if( !$this->getPrestados() && $this->getTomados() && !$this->getTomados_outro_municipio() )
				return "tomados";
			if( !$this->getPrestados() && !$this->getTomados() && $this->getTomados_outro_municipio() )
				return "tomados em outro município ou sujeitos à retenção de ISS";
			if( $this->getPrestados() && !$this->getTomados() && $this->getTomados_outro_municipio() )
				return "prestados e/ou tomados em outro município ou sujeitos à retenção de ISS";
		}
		function getAbreviacao(){
			return $this->abreviacao;
		}
		function setAbreviacao($string){
			$this->abreviacao = $string;
		}
		function getNomeCompletoTexto(){
			
			if($this->getNomeTexto()){
				if( $this->getNome() != '' ) {
					//return ' - '.$this->getNome();
					return $this->getNome();
				}
			} else {
				if( $this->getNome() != '' ) {
					return $this->getNome();
				}
			}
		}
		function getNomeTexto(){
			return $this->getAbreviacao();
		}
		function getCidadeTexto(){
			return ucfirst(strtolower($this->getCidade()));
		}
		function inserirNovoDes(){
			$this->setAcao("salvar-novo");
		}
		function deleteDes($id){
			mysql_query("DELETE FROM `des` WHERE id = '".$id."' ");
		}
		function listarDados(){
			$consulta = mysql_query("SELECT * FROM des ORDER BY uf,municipio ASC");
			return $consulta;
		}
		function getAcao(){
			return $this->acao;
		}
		function setAcao($string){
			$this->acao = $string;
		}
		function setPrestados($string){
			if( $string == 'x' )
				$this->prestados = true;
			else
				$this->prestados = false;
		}
		function setTomados($string){
			if( $string == 'x' )
				$this->tomados = true;
			else
				$this->tomados = false;
		}
		function getTomados(){
			return $this->tomados;
		}
		function getPrestados(){
			return $this->prestados;
		}
		function getPrestadosVal(){

			if($this->prestados == true)
				return 'x';
		}
		function getTomadosVal(){
			if($this->tomados == true)
				return 'x';
		}
		function getTomados_outro_municipioVal(){
			if($this->outro_m == true)
				return 'x';
		}
		function getTomados_outro_municipio(){
			return $this->outro_m;
		}
		function setTomados_outro_municipio($string){
			if( $string == 'x' )
				$this->outro_m = true;
			else
				$this->outro_m = false;
		}
		function getId(){
			return $this->id;
		}
		function setId($string){
			$this->id = $string;
		}
		function getUf(){
			return $this->uf;
		}
		function setUf($string){
			$this->uf = $string;
		}
		function getCidade(){
			return $this->municipio;
		}
		function setCidade($string){
			$this->municipio = $string;
		}
		function getTipo(){
			return $this->tipo;
		}
		function setTipo($string){
			$this->tipo = $string;
		}
		function getNome(){
			return $this->nome;
		}
		function setNome($string){
			$this->nome = $string;
		}
		function getLink(){
			return $this->link;
		}
		function setLink($string){
			$this->link = $string;
		}
		function getTutorial(){
			return $this->tutorial;
		}
		function setTutorial($string){
			$this->tutorial = $string;
		}
		
		function getNomeSite(){
			return $this->NomeSite;
		}		
		
		function setNomeSite($string){
			$this->NomeSite = $string;
		}
		
		function getData($mes = 1){
			if( strtolower($this->getCriterio()) == 'corridos' ){
				$aux = date('Y-m-d', strtotime("+".$this->vencimento." days",strtotime(date('Y-'.$mes.'-00')))); 
				$aux = explode('-', $aux);
				return intval($aux[2]);
			}
			else{
				$datas = new Datas();
				return $datas->getDia($datas->somarDiasUteis( $datas->primeiroDiaMes( date("Y-m-d") ) , intval($this->vencimento) ));

			}
			// $dataEnviada = '01/05/2013 17:30';

			// $diasExtenso = array("Domindo","segunda","Terça","Quarta","Quinta","Sexta","Sábado");
			// $date = DateTime::createFromFormat('d/m/Y H:i', $dataEnviada);
			// $feriados = array('01/01','31/12','25/12','01/05','25/04');

			// echo 'Data Informada: ', $date->format('d/m/Y H:i'), PHP_EOL;
			// echo 'Dia da semana (numero): ', $date->format('w'), PHP_EOL;
			// echo 'Dia da semana (extenso): ', $diasExtenso[$date->format('w')], PHP_EOL;
			// echo 'Ultimo dia do mes: ', $date->format('t'), PHP_EOL;
			// echo 'Final de semana?: ', $date->format('w') == 0 || $date->format('w') == 6 ? 'Sim' : 'Não', PHP_EOL;
			// echo 'É feriado?: ', in_array($date->format('d/m'),$feriados) ? 'Sim' : 'Não';
				
		}
		function getDataVal($string){
			return $this->vencimento;
		}
		function setData($string){
			$this->vencimento = $string;
		}
		function getCriterio(){
			return $this->criterio;
		}
		function setCriterio($string){
			$this->criterio = $string;
		}
		function getRetencao_iss(){
			return $this->retencao_iss;
		}
		function setRetencao_iss($string){
			$this->retencao_iss = $string;
		}
		function getObservacao(){
			return $this->observacao;
		}
		function setObservacao($string){
			$this->observacao = $string;
		}
		function getFonte(){
			return $this->fonte;
		}
		function setFonte($string){
			$this->fonte = $string;
		}
		function __construct(){
			$this->id_empresa = $_SESSION['id_empresaSecao'];
			$this->id_user = $_SESSION['id_userSecaoMultiplo'];

			$this->id = '';
			$this->uf = '';
			$this->municipio = '';
			$this->prestados = '';
			$this->tomados = '';
			$this->outro_m = '';
			$this->tipo = '';
			$this->nome = '';
			$this->link = '';
			$this->tutorial = '';
			$this->vencimento = '';
			$this->criterio = '';
			$this->retencao_iss = '';
			$this->observacao = '';
			$this->fonte = '';
			$this->valor = '';

			if( isset( $_GET['novo-des'] ) ):
				$this->inserirNovoDes();
			endif;
			if( isset( $_GET['delete-des'] ) ):
				$this->deleteDes($_GET['delete-des']);
				echo '<script>location.href="des.php#'.$_GET['url'].'";</script>';
			endif;
			if( isset( $_GET['editar-des'] ) ):
				$this->editarDes($_GET['id']);
				$this->setAcao("update-des");
			endif;
			if( isset( $_POST['acao'] ) && $_POST['acao'] == 'salvar-novo' ):
				$id = $this->salvarNovo();
				echo '<script>location.href="des.php#item'.$id.'";</script>';
			endif;
			if( isset( $_POST['acao'] ) && $_POST['acao'] == 'update-des' ):
				$this->updateDados();
				echo '<script>location.href="des.php#item'.$this->getId().'";</script>';
			endif;

		}
		function updateDados(){

			$this->setId($_POST['id']);
			$this->setUf($_POST['uf']);
			$this->setCidade($_POST['municipio']);
			$this->setPrestados($_POST['prestados']);
			$this->setTomados($_POST['tomados']);
			$this->setTomados_outro_municipio($_POST['outro_m']);
			$this->setTipo($_POST['tipo']);
			$this->setNome($_POST['nome']);
			$this->setAbreviacao($_POST['abreviacao']);
			$this->setLink($_POST['link']);
			$this->setTutorial($_POST['tutorial']);
			$this->setData($_POST['vencimento']);
			$this->setCriterio($_POST['criterio']);
			$this->setRetencao_iss($_POST['retencao_iss']);
			$this->setObservacao($_POST['observacao']);
			$this->setFonte($_POST['fonte']);
			$this->setvalor(str_replace(',', '.', str_replace('.', '', $_POST['valor'])));
			$this->setNomeSite($_POST['nomeSite']);

			$campos = "
						`uf` = '".$this->getUf()."',
						`municipio` = '".$this->getCidade()."',
						`prestados` = '".$this->getPrestadosVal()."',
						`tomados` = '".$this->getTomadosVal()."',
						`outro_m` = '".$this->getTomados_outro_municipioVal()."',
						`tipo` = '".$this->getTipo()."',
						`nome` = '".$this->getNome()."',
						`abreviacao` = '".$this->getAbreviacao()."',
						`link` = '".$this->getLink()."',
						`tutorial` = '".$this->getTutorial()."',
						`vencimento` = '".$this->getDataVal()."',
						`criterio` = '".$this->getCriterio()."',
						`retencao_iss` = '".$this->getRetencao_iss()."',
						`observacao` = '".$this->getObservacao()."',
						`fonte` = '".$this->getFonte()."',
						`nome_site` = '".$this->getNomeSite()."',
						`valor` = '".$this->getvalor()."'
					 ";			
			
			mysql_query("UPDATE `des` SET ".$campos." WHERE id = '".$this->getId()."' ");

		}
		function editarDes($id){

			$consulta = mysql_query("SELECT * FROM des WHERE id = '".$id."' ");
			$objeto=mysql_fetch_array($consulta);

			$this->setId($objeto['id']);
			$this->setUf($objeto['uf']);
			$this->setCidade($objeto['municipio']);
			$this->setPrestados($objeto['prestados']);
			$this->setTomados($objeto['tomados']);
			$this->setTomados_outro_municipio($objeto['outro_m']);
			$this->setTipo($objeto['tipo']);
			$this->setNome($objeto['nome']);
			$this->setAbreviacao($objeto['abreviacao']);
			$this->setLink($objeto['link']);
			$this->setTutorial($objeto['tutorial']);
			$this->setData($objeto['vencimento']);
			$this->setCriterio($objeto['criterio']);
			$this->setRetencao_iss($objeto['retencao_iss']);
			$this->setObservacao($objeto['observacao']);
			$this->setFonte($objeto['fonte']);
			$this->setvalor($objeto['valor']);
			$this->setNomeSite($objeto['nome_site']);

		}
		function salvarNovo(){

			$this->setUf($_POST['uf']);
			$this->setCidade($_POST['municipio']);
			$this->setPrestados($_POST['prestados']);
			$this->setTomados($_POST['tomados']);
			$this->setTomados_outro_municipio($_POST['outro_m']);
			$this->setTipo($_POST['tipo']);
			$this->setNome($_POST['nome']);
			$this->setAbreviacao($_POST['abreviacao']);
			$this->setLink($_POST['link']);
			$this->setTutorial($_POST['tutorial']);
			$this->setData($_POST['vencimento']);
			$this->setCriterio($_POST['criterio']);
			$this->setRetencao_iss($_POST['retencao_iss']);
			$this->setObservacao($_POST['observacao']);
			$this->setFonte($_POST['fonte']);
			$this->setvalor(str_replace(',', '.', str_replace('.', '', $_POST['valor'])));
			$this->setNomeSite($_POST['nomeSite']);

			$campos = "(`uf`, `municipio`, `prestados`, `tomados`, `outro_m`, `tipo`, `nome`, `abreviacao`, `link`, `tutorial`, `vencimento`, `criterio`, `retencao_iss`, `observacao`, `fonte`, `valor`, `nome_site`) ";
			$values = "( '".$this->getUf()."', '".$this->getCidade()."', '".$this->getPrestadosVal()."', '".$this->getTomadosVal()."', '".$this->getTomados_outro_municipioVal()."', '".$this->getTipo()."', '".$this->getNome()."', '".$this->getAbreviacao()."', '".$this->getLink()."', '".$this->getTutorial()."', '".$this->getDataVal()."', '".$this->getCriterio()."', '".$this->getRetencao_iss()."', '".$this->getObservacao()."', '".$this->getFonte()."', '".$this->getvalor()."', '".$this->getNomeSite()."' ) ";
			mysql_query("INSERT INTO `des` ".$campos." VALUES ".$values."");

			return mysql_insert_id();

		}
		function getCidades(){
			
			if( $this->getUf() != '' ){

				$consulta = mysql_query("SELECT * FROM estados WHERE sigla = '".$this->getUf()."' ");
				$objeto=mysql_fetch_array($consulta);
				$id = $objeto['id'];

				$consulta = mysql_query("SELECT * FROM cidades WHERE id_uf = '".$id."' ORDER BY cidade ");
				$string = '';
				$selected = '';
				while( $objeto=mysql_fetch_array($consulta) ){
					$selected = '';
					if( $objeto['cidade'] == $this->getCidade() ) 
						$selected = 'selected="selected"';
					$string .= '<option value="'.$objeto['cidade'].'" '.$selected.' >'.$objeto['cidade'].'</option>';
				}
				return $string;
			}

		}
		function getEstados(){

			if( $this->getUf() != '' ){
				$consulta = mysql_query("SELECT * FROM estados ORDER by estado ");
				
				$string = '<option value="">Selecione</option>';
				$selected = '';
				while ( $objeto=mysql_fetch_array($consulta) ) {
					$selected = '';
					if( $objeto['sigla'] == $this->getUf() ) 
						$selected = 'selected="selected"';
					$string.= '<option value="'.$objeto['sigla'].'" '.$selected.' >'.$objeto['estado'].'</option>';
				}

				return $string;
			}
			else{
				$consulta = mysql_query("SELECT * FROM estados ORDER by estado ");
				
				$string = '<option value="">Selecione</option>';
					while ( $objeto=mysql_fetch_array($consulta) ) {
						$string.= '<option value="'.$objeto['sigla'].'">'.$objeto['estado'].'</option>';
					}

				return $string;
			}
		}
	}
?>

