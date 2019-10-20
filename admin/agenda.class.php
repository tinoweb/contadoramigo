
<?php 
	
	class Agenda{

		private $id;
		private $mes;
		private $dia;
		private $tipo;
		private $acao;
		private $redirect;

		function __construct(){
			$this->id = 	"";
			$this->mes = 	"";
			$this->dia = 	"";
			$this->tipo = 	"";
			$this->acao = 	"new";
			$this->redirect = 	"";

			if( isset( $_GET['editar'] ) ):
				$this->editarItem();
			endif;
			if( isset( $_POST['new'] ) ):
				$this->novoItem();
			endif;
			if( isset( $_POST['update'] ) ):
				$this->updateItem();
			endif;
			if( isset( $_GET['excluir'] ) ):
				$this->excluirItem();
			endif;
		}
		function listaTipos(){
			$consulta = mysql_query("SELECT * FROM itens_agenda");
			return $consulta;
		}		
		function getInfoTipo($tipo){
			$consulta = mysql_query("SELECT * FROM itens_agenda WHERE id = '".$tipo."' ");
			$objeto=mysql_fetch_array($consulta);
			return $objeto;
		}
		
		function editarItem(){
			$id = $_GET['id'];
			$consulta = mysql_query("SELECT * FROM agenda_index WHERE id = '".$id."' ");
			$objeto=mysql_fetch_array($consulta);

			$this->setid($objeto['id']);
			$this->setmes($objeto['mes']);
			$this->setdia($objeto['dia']);
			$this->settipo($objeto['tipo']);	

			$this->setacao("update");

		}
		function novoItem(){

			$dia = $_POST['dia'];
			$mes = $_POST['mes'];
			$tipo = $_POST['tipo'];

			$values = " '".$mes."','".$dia."','".$tipo."' ";

			mysql_query("INSERT INTO `agenda_index` ( `mes`, `dia`, `tipo` ) VALUES ( ".$values." )");

			$id = mysql_insert_id();

			$script = ' <script>location.href = "agenda.php#item'.$id.'";</script>';

			$this->setredirect($script);
		}
		function updateItem(){
			
			$id = $_POST['id'];
			$dia = $_POST['dia'];
			$mes = $_POST['mes'];
			$tipo = $_POST['tipo'];

			$campos = "`mes`='.$mes.',`dia`='.$dia.',`tipo`='.$tipo.'";

			mysql_query("UPDATE `agenda_index` SET ".$campos." WHERE id = '".$id."' ");

			$script = ' <script>location.href = "agenda.php#item'.$id.'";</script>';

			$this->setredirect($script);

		}
		function excluirItem(){
			$id = $_GET['id'];

			mysql_query("DELETE FROM `agenda_index` WHERE id = '".$id."' ");

			$script = ' <script>location.href = "agenda.php";</script>';

			$this->setredirect($script);

		}
		function getMeses(){
			$consulta = mysql_query("SELECT * FROM meses ORDER BY id ASC");
			return $consulta;
		}
		function getTipos(){
			$consulta = mysql_query("SELECT * FROM itens_agenda ORDER BY id ASC");
			return $consulta;
		}
		function getTextoMes($mes){
			$consulta = mysql_query("SELECT * FROM meses WHERE id = '".$mes."' ");
			$objeto=mysql_fetch_array($consulta);
			return $objeto['mes'];
		}
		function getTextoTipo($tipo){
			$consulta = mysql_query("SELECT * FROM itens_agenda WHERE id = '".$tipo."' ");
			$objeto=mysql_fetch_array($consulta);
			return $objeto['frase'];
		}
		function listarItens($mes){
			$consulta = mysql_query("SELECT * FROM 	agenda_index WHERE mes = '".$mes."' order by mes,dia,tipo ASC");
			return $consulta;

		}
		function listarItensMes($string){
			
			// instrução para bloquear a DMED, DIMOB e a DSTDA. 
			$statusDIMOB = " AND tipo != '12' ";
			$statusDMED = " AND tipo != '11' ";
			$statusDSTDA = " AND tipo != '13' ";
						
			// pega os cnae da empresa.
			$listaCnae = $this->PegaCnaesDaEmpresa($_SESSION["id_empresaSecao"]);
			
			// Percorre a lista de cnae para verificar se existe alguma restrição.
			foreach($listaCnae as $val){
				
				// Inclui a restrição de bloqueio para DIMOB.
				if(!empty($statusDIMOB)){
					
					// verifica se o cnae esta na lista para ser apresentado a DIMOB nas obrigações
					if($this->ListaCnaeDIMOB($val['cnae'])){
						$statusDIMOB = "";
					}
				}
					
				// Inclui a restrição de bloqueio para DMED.
				if(!empty($statusDMED)){					
					
					// verifica se o cnae esta na lista para ser apresentado a DMED nas obrigações
					if($this->ListaCnaeDMED($val['cnae'])) {
						$statusDMED = "";
					}
				}
				
				// Inclui a restrição de bloqueio para DSTDA.
				if(!empty($statusDSTDA)){					
					
					// verifica se o DSTDA esta na lista para ser apresentado a DSTDA nas obrigações
					if($this->ListaCnaeDSTDA($val['cnae'])) {
						$statusDSTDA = "";
					}
				}
			}
			
			$qry = "SELECT * FROM agenda_index_publicar WHERE mes = '".$string."'";
			$qry .= $statusDIMOB;
			$qry .= $statusDMED;
			$qry .= $statusDSTDA;
			$qry .=	" ORDER BY dia,tipo ASC ";
	
			$consulta = mysql_query($qry);
			return $consulta;			
		}
		function PegaCnaesDaEmpresa($id){
			
			$rows = '';
		
			$query = "SELECT cnae FROM `dados_da_empresa_codigos` WHERE `id` = '".$id."'";

			$consulta = mysql_query($query);		
			if( mysql_num_rows($consulta) > 0 ){
				while($array = mysql_fetch_array($consulta)){
					$rows[] = $array ;
				}
			}

			return $rows;
		}
		function setredirect($string){
			$this->redirect = $string;
		}
		function getredirect(){
			return $this->redirect;
		}
		function setacao($string){
			$this->acao = $string;
		}
		function getacao(){
			return $this->acao;
		}
		function setid($string){
			$this->id = $string;
		}
		function getid(){
			return $this->id;
		}
		function setmes($string){
			$this->mes = $string;
		}
		function getmes(){
			return $this->mes;
		}
		function setdia($string){
			$this->dia = $string;
		}
		function getdia(){
			return $this->dia;
		}
		function settipo($string){
			$this->tipo = $string;
		}
		function gettipo(){
			return $this->tipo;
		}
		// Método criado para verificar se a empresa esta entre alguns dos cnae informado, assim permitido que a DMED apareça nas obrigações do cliente.
		function ListaCnaeDMED($cnae){
			
			$out = false;
			
			// Lista de cnae
			$array_cnae = array('3250-7/06','3250-7/09','8610-1/01','8610-1/02','8630-5/01','8630-5/02','8630-5/03','8630-5/04','8630-5/07','8640-2/01','8640-2/02','8640-2/05','8640-2/10','8640-2/11','8640-2/12','8640-2/14','8640-2/99','8650-0/03','8650-0/04','8650-0/05','8650-0/06','8650-0/99','8711-5/01','8720-4/01','8720-4/99','8650-0/07','8690-9/01','8690-9/02','8711-5/03','8800-6/00');
			
			// Verifica se o cnae informado esta na lista.
			if(in_array($cnae, $array_cnae)){
				$out = true;
			}
			
			return $out;
		}
		
		// Método criado para verificar se a empresa esta entre alguns dos cnae informado, assim permitido que a DSTDA apareça nas obrigações do cliente.
		function ListaCnaeDSTDA($cnae){
			
			$out = false;
			
			// Lista de cnae
			$array_cnae = array('4912-4/02','4912-4/03','4921-3/01','4923-0/01','4923-0/02','4924-8/00','4929-9/01','4929-9/03','4930-2/01','5021-1/01','5022-0/01','5091-2/01','5030-1/01','5030-1/02','5030-1/03','5099-8/01','5112-9/99','4911-6/00','4912-4/01','4921-3/02','4922-1/01','4922-1/02','4922-1/03','4929-9/02','4929-9/04','4930-2/02','5012-2/01','5012-2/02','5021-1/02','5022-0/02','5091-2/02','5111-1/00','5120-0/00','5130-7/00');
			
			// Verifica se o cnae informado esta na lista.
			if(in_array($cnae, $array_cnae)){
				$out = true;
			}
			
			return $out;
		}
		
		// Método criado para verificar se a empresa esta entre alguns dos cnae ou nos grupos de cnae informado, assim permitido que a DIMOB apareça nas obrigações do cliente.
		function ListaCnaeDIMOB($cnae){
			
			$out = false;
			
			$cnaeInicio = explode('-',$cnae);
						
			// Lista de grupo de cnae
			$array_grupos_cnae = array('5590','6810','6821','6822');
			
			// Verifica se o cnae informado esta no grupo informado.
			if(in_array($cnaeInicio[0], $array_grupos_cnae)){
				$out = true;
				return $out;
			}			
						
			// Lista de cnae
			$array_cnae = array('4110-7/00');
						
			if(in_array($cnae, $array_cnae)){
				$out = true;
				return $out;
			}
	
			return $out;
		}
	}
?>