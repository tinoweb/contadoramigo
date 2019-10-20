<?php
/**
 * Classe criada para para manipularos dados da conta a receber.
 * Data: 02/04/2018
 */

// Faz a requisição do arquivo responsavel por manipular os dados do banco de dados.
require_once('DataBaseMySQL/LivroCaixa.php');
require_once('DataBaseMySQL/DadosClientes.php');

class ContasAReceberController {
	
	private $Mes;
	private $Ano;
	private $PrimeiroAno;
	public $nomes;
		
	function __construct($empresaId){
		
		$this->Mes = date('n');
		$this->Ano = date('Y');
		$this->PrimeiroAno = date('Y');
		
		$livroCaixa = new LivroCaixa();
		
		// Função para pegar o ultimo pagamento. Localizado DataBaseMySQL/LivroCaixa.php
		$rs_ultimaData = $livroCaixa->pegaDadosContasAReceberUltimo($empresaId);
		
		// Função para pegar o primeiro pagamento. Localizado DataBaseMySQL/LivroCaixa.php
		$rs_primeiraData = $livroCaixa->pegaDadosContasAReceberPrimeiro($empresaId);
		
		// Verifica se tem a primeira data de pagamento
		if($rs_primeiraData){
			$this->PrimeiroAno = date('Y', strtotime($rs_primeiraData['data']));
			
			// Verifica se tem um ultimo pagamento
			if($rs_ultimaData){
				
				// Passa o mês e ano do ultimo pagamento
				$this->Ano = date('Y', strtotime($rs_ultimaData['data']));
				$this->Mes = date('n', strtotime($rs_ultimaData['data']));
			}
		}
	}
	
	// Método criado para montar o filtro.
	public function Montafiltro() {
		
		// Pega o menor ano da data do banco de dados.
		$ano = $this->PrimeiroAno;
		
		// Pega o ano atual. 
		$anoNumAux = $anoAux = $this->Ano;
		
		// Pega o numero do mês atual.
		$mesNumber = $this->Mes;
		
		// Verifica qual o mês.
		if(isset($_GET['periodoMes'])) {
			// Pega o numero do mês
			$mesNumber = $_GET['periodoMes'];
		}
		
		// Verifica qual o Ano.
		if(isset($_GET['periodoAno']) && !empty($_GET['periodoAno'])) {
			
			// Pega o ano que esta na busca.
			$anoNumAux = $anoAux = $_GET['periodoAno'];
		}		
		
		// Monta array com os meses do ano.
		$meses = array(1=>'Janeiro'
					   ,2=>'Fevereiro'
					   ,3=>'Março'
					   ,4=>'Abril'
					   ,5=>'Maio'
					   ,6=>'Junho'
					   ,7=>'Julho'
					   ,8=>'Agosto'
					   ,9=>'Setembro'
					   ,10=>'Outubro'
					   ,11 =>'Novembro'
					   ,12=>'Dezembro');
		
		// Inicia a criação do select.
		$selectMes = "<select name='periodoMes' id='periodoMes'><option value='todos'>Todos</option>";
		
		// Caso não exista a data ele pega a data atual
		if(!$meses){
			$meses = date('Y');
		}
		
		// Monta a lista de opções do select.
		foreach($meses as $key=>$val){
			
			if($mesNumber == $key) {
				$selectMes .= "<option value='".$key."' selected=''>".$val."</option>";
			} else {
				$selectMes .= "<option value='".$key."'>".$val."</option>";
			}
		}
				
		$selectMes .="</select>";
				
		$selectAno = '<select name="periodoAno" id="periodoAno">';

		if($anoAux < (date('Y') + 1)){
			$anoAux = $anoAux + 1;
		}		
		
		for($i = $anoAux; $i >= $ano; $i--) {
			
			if($anoNumAux == $i) {
				$selectAno .=	'<option value="'.$i.'" selected="">'.$i.'</option>';
			} else {
				$selectAno .=	'<option value="'.$i.'">'.$i.'</option>';
			}
		}
		
		$selectAno .= '</select>';

		$filtro = "<div style='float:left'> <form action='/contas_a_receber.php' method='get'>"
			." Mês: ".$selectMes
			." Ano: ".$selectAno
			." <input type='submit' value='Pesquisar'>"
			." </form></div> "
			." <div style='clear:both; height:5px'></div>";
		
		return $filtro;
	}
	
	// Método criado para pegar a tabela com os lancamentos do conta a receber.
	public function MontaTabela($empresaId) {
				
		$table = "<table width='100%' cellpadding='5' style='margin-bottom:25px;'> "
				."		<tr> "
				."			<th width='35' style='font-size: 12px;'>Ação</th> "
				."			<th width='80' style='font-size: 12px;'>Data</th> "
				."			<th width='135' style='font-size: 12px;'>Doc nº</th> "
				."			<th style='font-size: 12px;'>Categoria</th> "
				."			<th style='font-size: 12px;'>Descrição</th> "
				."			<th width='70' style='font-size: 12px;'>Valor</th> "
				."			<th width='70' style='font-size: 12px;'>Anexo</th> "
				."		</tr> ";
						
		// Chama o método criado para pegar as linhas da tabela.		
		$table .= $this->PegaLinhasTabela($empresaId);
		
										
		// Passa a tag para fechar a tabela.						
		$table .= " </table> ";		
				
		return $table;
	}
	
	// Método criado para pegar
	private function PegaLinhasTabela($empresaId) {
		
		$livroCaixa = new LivroCaixa();
		
		$selectAno = $this->Ano;
		$selectMes = $this->Mes;
						
		// Verifica se o mês e ano estão vindo pelo filtro
		if((isset($_GET['periodoMes']) && !empty($_GET['periodoMes'])) && (isset($_GET['periodoAno']) && !empty($_GET['periodoAno']))){			
			
			// Pega mês e ano do filtro
			$selectAno = $_GET['periodoAno'];
			$selectMes = $_GET['periodoMes'];
		}

		$out = "";			

		// Método para pegar a lista de contas a receber. Localizado DataBaseMySQL/LivroCaixa.php
		$dados = $livroCaixa->PegaDadosContasAReceber($empresaId, $selectAno, $selectMes);				
		
		// Verifica se existe dados.
		if($dados) {			
			
			$countAux = 0;
			
			foreach($dados as $val){
				
				$countAux += 1;
				
				$out .= " <tr> "						
						." <td class='td_calendario'><a id='excluirLinha_".$countAux."' style='cursor:pointer;' onclick='excluirLinha(".$empresaId.",".$val['id'].",".$countAux.")'><i class='fa fa-trash-o iconesAzul iconesGrd'></i></a></td> "
						." <td class='td_calendario' align='center'>".date('d/m/Y', strtotime($val['data']))."</td> "
						." <td class='td_calendario'>".$val['documento_numero']."</td> "
						." <td class='td_calendario'>".$val['categoria']."</td> "
						." <td class='td_calendario'>".$val['descricao']."</td> "
						." <td class='td_calendario' align='right'>".number_format($val['entrada'], 2, ",", ".")."</td> "
						." <td class='td_calendario'>".$this->PegaAnexos($val['id'], $empresaId)."</td> "
						." </tr> ";
			}
		} // retona as linha vazia 
		else {
			$out .=	" <tr> "
					."   <td class='td_calendario'>&nbsp;</td> "
					."   <td class='td_calendario'>&nbsp;</td> "
					."   <td class='td_calendario'>&nbsp;</td> "
					."   <td class='td_calendario'>&nbsp;</td> "
					."   <td class='td_calendario'>&nbsp;</td> "
					."	 <td class='td_calendario'>&nbsp;</td> "
					."	 <td class='td_calendario'>&nbsp;</td> "
					." </tr> ";
		}

		// Retonas as linha(s) da tabela.
		return $out;	
	}
	
	private function PegaAnexos2($id_lancamento, $empresaId) {
		
		$out =  '';
		
		$livroCaixa = new LivroCaixa();
		
		$comprovantes = $livroCaixa->Comprovantes($id_lancamento, $empresaId);

		foreach($comprovantes as $val){ 

			if( isset( $val['nome'] )) {
				$out .=  '<div class="download-arquivo"><a href="upload/comprovantes/'.$val['nome'].'" download>'
						.'<i class="fa fa-file-text-o icone-download" aria-hidden="true"></i>'
						.'</a><div class="mouse_over_nome_arquivo">'.$val['nome'].'</div></div>';
			} else {
				$out .= '<i class="fa fa-file-text-o icone-download" aria-hidden="true"></i>';	
			}
		}		
		
		
		return $out;
	}	
	
	private function PegaAnexos($id_lancamento, $empresaId) {
				
		$out =  '';
		
		$Contas_rec_pag_comprovantes = false;
		
		$livroCaixa = new LivroCaixa();
		
		$comprovantes = $livroCaixa->Comprovantes($id_lancamento, $empresaId);
		
		// Pega o id do lançamento de "contas a pagar e receber".
		$pagtoContasAPRDados = $livroCaixa->PegaPagamentoDeContasAReceberUoAPagar($empresaId, $id_lancamento);
		
		// Pega os comprovantos do lançamento (contas a pagar e receber).
		if($pagtoContasAPRDados){
			$pagtoContas_rec_pag_comprovantes = $livroCaixa->Comprovantes($pagtoContasAPRDados['livro_caixa_id_pagamento'], $empresaId);			
		}
		
		if( $comprovantes || $Contas_rec_pag_comprovantes ){
			
			if($comprovantes){
				
				foreach($comprovantes as $val){ 

					if( isset( $val['nome'] )) {
						$out .=  '<div class="download-arquivo"><a href="upload/comprovantes/'.$val['nome'].'" download>'
								.'<i class="fa fa-file-text-o icone-download" aria-hidden="true"></i>'
								.'</a><div class="mouse_over_nome_arquivo">'.$val['nome'].'</div></div>';
					} else {
						$out .= '<i class="fa fa-file-text-o icone-download" aria-hidden="true"></i>';	
					}
				}
			}
			
			if($pagtoContas_rec_pag_comprovantes){
				
				foreach($pagtoContas_rec_pag_comprovantes as $val){ 

					if( isset( $val['nome'] )) {
						$out .=  '<div class="download-arquivo"><a href="upload/comprovantes/'.$val['nome'].'" download>'
								.'<i class="fa fa-file-text-o icone-download" aria-hidden="true"></i>'
								.'</a><div class="mouse_over_nome_arquivo">'.$val['nome'].'</div></div>';
					} else {
						$out .= '<i class="fa fa-file-text-o icone-download" aria-hidden="true"></i>';	
					}
				}
			}			
		}
		
		return $out;
	}	
	
	public function IncluiContasAReceber($empresaId, $data, $entrada, $documento, $descricao, $categoria, $vencimento) {
		
		$livroCaixa = new LivroCaixa();
				
		//Chama o metodo para realizar a inclusão do lançãmento de conta a receber no livro caixa
		$livroCaixaId = $livroCaixa->InclusaoNoLivroCaixa($empresaId, $data, $entrada, 0, $documento, $descricao, $categoria);		
		
		//Pega o id da ultima linha da tabela do livro caixa
		if($livroCaixaId){			
			
			//Chama o metodo para realizar a inclusão do lançamento de conta a receber na tabela de relacionamento com o pagamento.
			$livroCaixa->lancamentoContasReceber($empresaId, $livroCaixaId, 'Cliente Contas a Pagar', $vencimento);
			
			// Inclui o arquivo
			$this->executeUpload($livroCaixaId,$empresaId,$documento,$data);

			$livroCaixa = new LivroCaixa();	
			
			$files = $this->nomes;
			foreach ($files as $file) {
				$livroCaixa->InclusaoComprovantes($empresaId, $livroCaixaId, $file);
			}			
			
			$mes = date('n', strtotime($data));
			$ano = date('Y', strtotime($data));
			
			// Redireciona para a tela de contas a receber de acordo com a data da nota
			header('Location: /contas_a_receber.php?periodoMes='.$mes.'&periodoAno='.$ano);
			
		}
		
		// Redireciona para a tela de contas a receber 
		header('Location: /contas_a_receber.php');
		
	}
	
	public function pegaListaCliente($empresaId){
		
		$dadosCliente = new DadosCliente();
		
		
		//chama metodo que chama lista de clientes. Esse metodo esta localizado em DataBaseMySQL/DadosClientes.php 
		$rs_clientes = $dadosCliente->pegaCliente($empresaId);
		
		$tagOption = '<select name="categoria" id="categoria"><option value="">selecione</option>';
		
		
		//verifica se existe lista de cliente
		if($rs_clientes){
			foreach($rs_clientes as $val){
				$tagOption .='<option value="' . $val['apelido'] . '" >' . $val['apelido'] . '</option>';
			}
		}
		
		$tagOption .= '<option value="Serviços prestados em geral">Serviços prestados em geral</option>';
				
		$tagOption .= '</select>';
						
						
		return $tagOption;		
		
	}
	
	// Método para busca e verificar se existe o id 'livro_caixa_id_pagamento' e permitir a exclusão, metodo localizado 'DataBaseMySQL/LivroCaixa.php'
	public function verificaIdLancamentoExistente($idEmpresa, $idLinha){
		
		$livroCaixa = new LivroCaixa();
		
		$resultado = $livroCaixa->idLancamentoExistente($idEmpresa, $idLinha);
		
		return $resultado;
				
	}
	
	// Faz e exclusão dos registro na tabela 'lancamento_contas_pagar_receber' e 'user_$empresaId_livro_caixa'	
	public function excluiDados($id, $empresaId){
		
		$livroCaixa = new LivroCaixa();	
		
		
		$livroCaixa->excluiContasAPagarReceber($id, $empresaId);
		
		$this->ExcluirComprovantes($id, $empresaId);		
		
		$livroCaixa->excluiContasAPagarLivroCaixa($id, $empresaId);	
		
		header('Location: contas_a_receber.php');
	}
		
	private function ExcluirComprovantes($livrocaixaId, $empresaId) {
		
		$livroCaixa = new LivroCaixa();
		
		$comprovantes = $livroCaixa->Comprovantes($livrocaixaId, $empresaId);
		
		foreach ($comprovantes as $val) {
			
			if($val['nome'] != '' ) {
				
				if( file_exists('upload/comprovantes/'.$val['nome']) ){
					unlink('upload/comprovantes/'.$val['nome']);
				}
				
				$consulta = $livroCaixa->ExcluirComprovantes($val['id'], $empresaId);			
			}
		}
	}	
	
	// Monta o campo dos comprovantes.
	private function CampoComprovantes($empresaId = '', $id = '') {

		$tags='';
		
		$livroCaixa = new LivroCaixa();
			
		$dados = $livroCaixa->Comprovantes($id, $empresaId);
		
		if($dados) {

			$tags .= '<div style="float: left;margin-right: 20px; height: 25px;">
				<input type="file" name="anexos_doc[]" value="" multiple><br>
			</div>';
			
			foreach($dados as $val){ 
				
				$tags .= ' <div style="float: left;margin-right: 20px; height: 25px;"> '
						.'	<a href="#" class="excluirPagamento" imagem="sim" arquivo="'.$val["nome"].'" linha="'.$val["id"].'" pagto="" cat="" title="Excluir"> '
						.'		<i class="fa fa-times" aria-hidden="true" style="color:red;font-size:15px;"></i> '
						.'	</a>'
						.$val['nome']
						.'</div>';
			}
			
		} else {
			
			$tags .= 'Anexar Comprovante(s): <input type="file" name="anexos_doc[]" value="" multiple style="margin-left:10px;margin-right:10px;"> (Max 1Mb)';
			
		}	
				
		// retorna o campo dos comprovantes.
		$this->Comprovantes = $tags;
	}
	
	private function Normaliza($string) {
		$table = array('Š'=>'S','š'=>'s','Đ'=>'Dj','đ'=>'dj','Ž'=>'Z','ž'=>'z','Č'=>'C','č'=>'c','Ć'=>'C','ć'=>'c','À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Å'=>'A','Æ'=>'A','Ç'=>'C','È'=>'E','É'=>'E',
		'Ê'=>'E','Ë'=>'E','Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I','Ñ'=>'N','Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O','Ø'=>'O','Ù'=>'U','Ú'=>'U','Û'=>'U','Ü'=>'U','Ý'=>'Y','Þ'=>'B','ß'=>'Ss',
		'à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a','æ'=>'a','ç'=>'c','è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ð'=>'o','ñ'=>'n','ò'=>'o','ó'=>'o',
		'ô'=>'o','õ'=>'o','ö'=>'o','ø'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ý'=>'y','ý'=>'y','þ'=>'b','ÿ'=>'y','Ŕ'=>'R','ŕ'=>'r');
		return strtr($string, $table);
	}

	private function extensao($file){

		$ext = explode(".", $file);
		$tam = count($ext);
		$ext = strtolower($ext[$tam-1]); 

		if( $ext != 'pdf' && $ext != 'doc' && $ext != 'jpg' && $ext != 'gif' && $ext != 'png' )
			return false;

		return $ext;

	}
	private function nomeArquivo($file,$ext){
		$nome = explode(".".$ext, $file);
		return $nome[0];
	}

	private function ifArquivoValido($file){
		if( $file != 'pdf' && $file != 'doc' && $file != 'jpg' && $file != 'gif' && $file != 'png' )
			return false;

		return true;
	}

	function executeUpload($last_id,$ID,$doc,$Data){			

		foreach ($_FILES as $__FILES) {
			for ($i=0; $i < count($__FILES["name"]); $i++) { 
				$size_file = $__FILES['size'][$i];	

				if($size_file > 1000000){
					// header('Location: livros_caixa_movimentacao.php?dataInicio='.$_GET['dataInicio'].'&dataFim='.$_GET['dataFim'].'&editar='.$_GET['editar'].'
					header('Location: contas_a_receber.php?erro_file="'.$__FILES["name"][$i].'"');
					exit();
				}
			}
		}


		$this->nomes = array();
		foreach ($_FILES as $__FILES) {

			for ($i=0; $i < count($__FILES["name"]); $i++) { 
								
				$file = $this->extensao($__FILES["name"][$i]);
				
				if( $this->ifArquivoValido($file) ){

					$name = $__FILES["name"][$i];
					$extensao = $this->extensao($file);
					$file_name = $this->nomeArquivo($name,$extensao);

					$file_name = utf8_encode($this->Normaliza($file_name."_".$last_id.".".$extensao));
					
					$this->nomes[] = $file_name;
					if( file_exists('upload/comprovantes/'.$file_name) )
						unlink('upload/comprovantes/'.$file_name);

					$ret = move_uploaded_file($__FILES["tmp_name"][$i],"upload/comprovantes/".$file_name);

				 }
			}
		}
	}
}