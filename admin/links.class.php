<?php 

	class Links{
		private $id;
		private $estado;
		private $cidade;
		private $pagina;
		private $link;
		function getid(){
			return $this->id;
		}
		function setid($string){
			$this->id = $string;
		}
		function getestado(){
			return $this->estado;
		}
		function setestado($string){
			$this->estado = $string;
		}
		function getcidade(){
			return $this->cidade;
		}
		function setcidade($string){
			$this->cidade = $string;
		}
		function getpagina(){
			if( $this->pagina == '' )
				$this->pagina = 'alteracao_contrato_sociedade_junta';
			return $this->pagina;
		}
		function setpagina($string){
			$this->pagina = $string;
		}
		function getlink(){
			return $this->link;
		}
		function setlink($string){
			$this->link = $string;
		}
		function getSqlItens($pagina){
			return mysql_query("SELECT * FROM links_controle WHERE pagina = '".$pagina."' group by estado,cidade,pagina order by estado , cidade , pagina ASC  ");
		}
		function listarItens($pagina){
			$string = '';
			$itens = $this->getSqlItens($pagina);	
			while ( $item = mysql_fetch_array($itens) ) {
				$string .= '
					<tr class="guiaTabela" style="background-color: rgb(234, 239, 245);">
						<td>'.$item['estado'].'</td>
						<td>'.$item['cidade'].'</td>
						<td align="center" class="acoes">
							<a href="links-manter.php?id='.$item['id'].'&acao=editar">
								<i class="fa fa-pencil-square-o" style="font-size: 16px;"></i>
							</a> | 
							<a href="#" onclick="if(confirm(\'Você tem certeza que deseja excluir este Item?\')){location.href=\'links.php?id='.$item['id'].'&acao=deletar\'};">
								<i class="fa fa-trash-o" style="font-size: 16px;"></i>
							</a>	
						</td>
					</tr>
				';
			}
			return $string;
		}
		function getSqlPaginas(){
			return mysql_query("SELECT * FROM links_controle group by pagina order by pagina");
		}
		function selected($string1,$string2){
			if( $string1 == $string2 ){
				return 'selected="selected"';
			}
		}
		function getOptionPaginas(){
			$paginas = $this->getSqlPaginas();
			$string = '<option value="">Selecione</option>';
			while( $pagina = mysql_fetch_array($paginas) ){
				$string .= '<option value="'.$pagina['pagina'].'" '.$this->selected($pagina['pagina'],$this->getpagina()).' >'.$pagina['pagina'].'</option>';
			}
			if( $_GET['acao'] == 'inserir' )
				$string .= '<option value="outra">outra</option>';
			return $string;
		}
		function getInputPagina(){
			
			if( $_GET['acao'] == 'inserir' )
				return '<input type="text" class="outra_pagina" name="" value="" style="display:none">';
		
		}
		function getSqlEstados(){
			return mysql_query("SELECT * FROM estados order by estado ASC ");
		}
		function listarEstados(){
			$string = '';
			$estados = $this->getSqlEstados();
			while( $estado = mysql_fetch_array($estados) ){
				$string .= '<option value="'.$estado['sigla'].'-'.$estado['estado'].'" '.$this->selected($estado['estado'],$this->getestado()).' >'.$estado['estado'].'</option>';
			}
			return $string;
		}
		function getSqlCidades(){
			$consulta = mysql_query("SELECT * FROM estados WHERE estado = '".$this->getestado()."' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			return mysql_query("SELECT * FROM cidades WHERE id_uf = '".$objeto_consulta['id']."' ");
		}
		function listarCidades(){
			$string = '';
			$cidades = $this->getSqlCidades();
			while( $cidade = mysql_fetch_array($cidades) ){
				$string .= '<option value="'.$cidade['cidade'].'" '.$this->selected($cidade['cidade'],$this->getcidade()).' >'.$cidade['cidade'].'</option>';
			}
			return $string;
		
		}
		function getSqlLinks(){
			return mysql_query("SELECT * FROM links_controle WHERE pagina = '".$this->getpagina()."' AND cidade = '".$this->getcidade()."' AND estado = '".$this->getestado()."' ");
		}
		function getLinks(){
			$string = '
				<tr>
					<td>
						Link:
					</td>
					<td>
						<input type="text" name="link" value="" placeholder="" style="width:500px">
					</td>
				</tr>';
			$links = $this->getSqlLinks();
			if( mysql_num_rows($links) > 0 )
				$string = '';
			$ids = '';
			while( $link = mysql_fetch_array($links) ){
				$string .= '
					<tr>
						<td>
							Link:
						</td>
						<td>
							<input class="editar_item" tabela="links_controle" campo="link" id="'.$link['id'].'" type="text" name="link" value="'.$link['link'].'" placeholder="" style="width:500px">
						</td>
					</tr>';
					$ids .= $link['id'].'-'; 
			}
			$this->setid($ids);
			return $string;
		}
		function salvarDados(){
			$estado = explode('-',$_POST['estado']);
			$estado = $estado[1];
			mysql_query("INSERT INTO `links_controle`(`cidade`, `estado`, `pagina`, `link`) VALUES ( '".$_POST['cidade']."' , '".$estado."' , '".$_POST['pagina']."' , '".$_POST['link']."' )");
		}
		function definirAcao(){
			if( isset($_GET['id']) )
				return 'editar';
			else
				return 'inserir';
		}
		function getSqlDados(){
			return mysql_query("SELECT * FROM links_controle WHERE id = '".$_GET['id']."' ");
		}
		function getDados(){
			$dados = $this->getSqlDados();
			$dado = mysql_fetch_array($dados);
			$this->setestado($dado['estado']);
			$this->setcidade($dado['cidade']);
			$this->setpagina($dado['pagina']);
			$this->setlink($dado['link']);
			$this->getLinks();
		}
		function ifExibirLink( $user , $pagina = '' , $posicao = '' , $parametro = '' ){
			$consulta = mysql_query("SELECT * FROM links_controle WHERE pagina = '".$pagina."' LIMIT ".($posicao-1).",1 ");
			$item = mysql_fetch_array($consulta);
			$consulta = mysql_query("SELECT * FROM estados WHERE sigla = '".$user->getEstado()."' ");
			$estado = mysql_fetch_array($consulta);
			$estado = $estado['estado'];
			if( $parametro == 'estado' ){
				if( $estado == $item['estado'] ){
					$this->setlink($item['link']);
					$this->setestado($item['estado']);
					$this->setcidade($item['cidade']);
					return true;
				}
			}
			if( $parametro == 'cidade' ){
				if( $user->getCidade() == $item['cidade'] ){
					$this->setlink($item['link']);
					$this->setestado($item['estado']);
					$this->setcidade($item['cidade']);
					return true;
				}
			}
		}
		function deletar(){
			
			$consulta = mysql_query("SELECT * FROM links_controle WHERE id = '".$_GET['id']."' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			mysql_query("DELETE FROM links_controle WHERE cidade = '".$objeto_consulta['cidade']."' AND estado = '".$objeto_consulta['estado']."' AND pagina = '".$objeto_consulta['pagina']."' ");
		
		}
		function __construct(){
			if( isset($_GET['acao']) ){
				if( $_GET['acao'] == 'editar' ){
					$this->getDados();
				}
			}
			else if( isset($_POST['acao']) ){
				if( $_POST['acao'] == 'inserir' ){
					$this->salvarDados();
					header("Location: links.php");
				}
			}
			if( isset($_GET['acao']) ){
				if( $_GET['acao'] == 'deletar' ){
					$this->deletar();
					header("Location: links.php");
				}
			}
		}
	}

?>