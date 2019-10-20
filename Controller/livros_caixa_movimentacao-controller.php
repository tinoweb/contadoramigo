<?php
/**
 * Esta classe esta sendo criada para tentar organizar o arquivo livros_caixa_movimentacao.php
 * data: 26/01/2018 as 12:53
 */

//	ini_set('display_errors',1);
//	ini_set('display_startup_erros',1);
//	error_reporting(E_ALL);

// Faz a requisição do arquivo que manipula os dados do emprestimo.
require_once('DataBaseMySQL/LivrosCaixaMovimentacao.php');
require_once('Model/Categoria/CategoriasData.php');


class LivrosCaixaMovimentacaoController {
	
	private $StatusEmprestimo = false;
	public function getStatusEmprestimo(){
		return $this->StatusEmprestimo;
	}
	
	// Método utilizado para setar o status do emprestimo como verdadeiro quando for edição. 
	private function setStatusEmprestimo($val){
		$this->StatusEmprestimo = $val;
	}	
	
	private $TituloLancamento;
	private $ActionForm;
	private $EntradaSaida;
	private $Categorias;
	private $DisplayPagamento = 'none';
	private $TagOption;
	private $EmprestimoOption;
	private $TipoPagamento;
	private $Data;
	private $Valor;
	private $NumeroDoc;
	private $Descricao;
	private $DisplayDescricao = 'table-cell';
	private $DisabledDesc;
	private $EmprestimoCampos;
	private $DisplayEmprestimo = 'none';	
	private $DisplaySelectEmprestimo = 'none';
	private $Comprovantes;
	private $ListaEmprestimo = false;
	public  $StatusListaEmprestimo = false;
	private $AmortizacaoOption;
	private $AmortizacaoEmprestimo;
	public  $StatusAmortizaca = false;
	private $DisplayAmortizacao = 'none';
	private $DisplaySelectAmortizacao = 'none';
	private $DisplayDoc = 'table-cell';
	private $DataWidth = 'width="90"';
	private $DataName = 'Data';	
	
	
	private $CampoContasAReceber;
	private $OpcaoContasAReceber;
	private $DisplayContasAReceber = 'none';
	public  $StatusContasAReceber = false;
	
	private $CampoContasAPagar;
	private $OpcaoContasAPagar;
	private $DisplayContasAPagar = 'none';
	public  $StatusContasAPagar = false; 
	
	// Método criado para poder criar a inclusão do lançamento.
	public function AreaInclusaoLancamento($editarId, $empresaId, $userIdSecaoMultiplo) {
		
		// Define o estado padrão da tela de acordo com as variáveis
		$tags = '';
		$lancamento = '';

				
		// Verifica se é edição ou inclusão do lancçamento.
		if(!$editarId){
			
			$this->ituloLancamento = 'Novo Lançamento'; 
			$this->ActionForm = 'livros_caixa_movimentacao_inserir.php';

			// Pega as opção do select de entrada e saída.
			$this->EntradaSaida = $this->PegaLancamentoEntradaOuSaida(false,'');

			$this->Categorias = '<select name="selCategoria" id="selCategoria"><option value="">selecione</option></select>';

			$this->MontaCampoEmprestimos($empresaId, '');

			// Chama o método para montar o campo de armotização para inclusão.
			$this->CampoAmortizacaoInclusao($empresaId);
			
			// Pega os campos do conta a receber.
			$this->CompoContasAReceber($empresaId);

			// Pega os campos do conta a pagar.
			$this->CompoContasAPagar($empresaId);
			
			// Pega os comprovantes anexidos.
			$this->CampoComprovantes($empresaId);
			
		} //Define os paramentros para edição do lançamento.
		else {
			
			$this->TituloLancamento = 'Editar Lançamento';
			$this->ActionForm = 'livros_caixa_movimentacao_gravar.php';
			
			$livroCaixa = new LivrosCaixaMovimentacao();
			
			$linha = $livroCaixa->PegalancamentoDeAcordoIdIlinha($empresaId, $editarId);

			// Pega o lançamento que será editado.
			if($linha){
				
				$this->Data = date('d/m/Y', strtotime($linha['data']));

				if ($linha["saida"] != 0) {
					$this->Valor = number_format($linha["saida"],2,',','.');
					$lancamento = "saída <input type='hidden' name='selTipoLancamento' value='saida'>";
				} else {
					$this->Valor = number_format($linha["entrada"],2,',','.');
					$lancamento = "entrada <input type='hidden' name='selTipoLancamento' value='entrada'>";
				}

				$this->Descricao = $linha['descricao'];
				
				$this->NumeroDoc = $linha['documento_numero'];
				
				// Paga as opções do select da categorias.
				$this->Categorias = $linha['categoria'].'<input type="hidden" name="selCategoria" value="'.$linha['categoria'].'">';
				
				// Pega as opção do select de entrada e saída.
				$this->EntradaSaida = $lancamento;
				
				// Pega os nomes da pessoas de acordo com a forma de pagamento informado na categoria.
				$this->PegaQuemReceberapagamento($linha['categoria'], $empresaId, $linha['descricao']);
				
				// Pega os elementos para montar os campos de emprestimos.
				$this->MontaCampoEmprestimos($empresaId, $linha['id']);				
								
				// Pega os elementos para montar os campos de armotização.
				$this->CampoAmortizacao($empresaId, $linha['id']);
				
				// Pega os campos do conta a receber.
				$this->CompoContasAReceber($empresaId, $linha['id'], $linha['categoria']);
				
				// Pega os campos do conta a pagar.
				$this->CompoContasAPagar($empresaId, $linha['id'], $linha['categoria']);
				
				// Pega o campo com os comprovantes.
				$this->CampoComprovantes($empresaId, $linha['id']);	
			}
		}
		
		$tags .= <<<__HTML__
			
			<div class="tituloVermelho" style="margin-bottom:10px">{$this->TituloLancamento}</div>
				<div style="margin-bottom:20px; width:966px;"> 
					<form method="post" id="form_livro" action="{$this->ActionForm}" accept-charset="utf-8" enctype="multipart/form-data">
						<div style="background-color: #e5e5e5; margin-bottom:20px;">
					
							<!-- Define a apresentação do formulario de acordo com selecte de entrada/saída, categoria e etc -->
							<div>
								<table id="tableFilter" cellspacing=0 cellpadding=0 style="width:100%">
									<tr class="tituloLinha">
										<td class="filtroLancamento" style="width: 80px;">
											<span>Tipo</span>
										</td>
										<td width="160" class="filtroLancamento">
											<span>Categoria</span>
										</td>
										<td class="opcao_pagamento_erro filtroLancamento" status="fechado" style="display: none;">
											<span>Beneficiário</span>
										</td >
										<td class="opcao_pagamento filtroLancamento" status="fechado" style="display:{$this->DisplayPagamento};">
											<span>Beneficiário</span>
										</td >

										<td width="200" class="selecioneEmprestimo filtroLancamento" style="display:{$this->DisplaySelectEmprestimo};">
											<span>Empréstimos</span>
										</td>

										<td class="selecioneAmortizacaoEmprestimo filtroLancamento" style="display:{$this->DisplaySelectAmortizacao};">
											<span>Eempréstimos</span>
										</td>
										
										<td width="200" class="ContasAReceberSelect filtroLancamento" style="display: none;">
											<span>Selecione o pagamento</span>	
										</td>
										
										<td width="200" class="ContasAPagarSelect filtroLancamento" style="display: none;">
											<span>Selecione o pagamento</span>	
										</td>
										
										<td id="dataName" width="90" class="filtroLancamento">{$this->DataName}</td>
										
										<td id="linhaValor" width="90" class="filtroLancamento">Valor</td>
										
										<td width="90" class="campoDoc filtroLancamento" style="display:{$this->DisplayDoc};">Doc nº</td>
										
										<td class="campoDescricao filtroLancamento" style="display:{$this->DisplayDescricao};">Descrição</td>
										
									</tr>
									<tr>
										<td align="left">			
											{$this->EntradaSaida}
										</td>
										<td align="left">
											{$this->Categorias}
										</td>
										<td align="left" class="opcao_pagamento_erro" status="fechado" style="display: none;">
											<div id="opcao_pagamento_erro" style="display: inline-block; width: 350px;"></div>
										</td>
										<td class="opcao_pagamento" status="fechado" style="display:{$this->DisplayPagamento};">	
											<div>
												<select id="nome_pagamentos" name="nome_pagamento">
													{$this->TagOption}
												</select>
											</div>	
										</td>
										<td class="selecioneEmprestimo" style="display:{$this->DisplaySelectEmprestimo};">
											<div style="width: 200px;">
												<select id="registroPaiId" name="registroPaiId" disabled>
													{$this->EmprestimoOption}
												</select>
												<a id="mostraImput" href="">Novo</a>
											</div>	
										</td>

										<td class="selecioneAmortizacaoEmprestimo" style="display:{$this->DisplaySelectAmortizacao};">
											<div>
												<select id="registroPaiIdAmortizacao" name="registroPaiIdAmortizacao" disabled>
													{$this->AmortizacaoOption}
												</select>
											</div>	
										</td>
										
										<td class="ContasAReceberSelect" style="display: none;">
											<div style="width: 200px;">
												<select id="contasAreceberId" name="contasAreceberId" disabled>
													{$this->OpcaoContasAReceber}
												</select>
												<a id="mostraCampoDataReceber" href="">Novo</a>
											</div>	
										</td>										
										
										<td class="ContasAPagarSelect" style="display: none;">
											<div style="width: 200px;">
												<select id="contasApagarId" name="contasApagarId" disabled>
													{$this->OpcaoContasAPagar}
												</select>
												<a id="mostraCampoDataPagar" href="">Novo</a>
											</div>	
										</td>
										
										<td>
											<input type="text" id="txtData"  name="txtData"  value="{$this->Data}" class="campoData" maxlength="10" style="width: 80px;" />
										</td>
										<td>
											<input type="text" id="txtValor" name="txtValor" value="{$this->Valor}" class="current" style="width: 80px;" />
										</td>
										<td class="campoDoc" style="display:{$this->DisplayDoc};">
											<input type="text" id="txtDocumentoNumero" name="txtDocumentoNumero"  value="{$this->NumeroDoc}" maxlength="256" style="width: 80px;" />
										</td>
										<td class="campoDescricao" style="display:{$this->DisplayDescricao};">
											<input type="text" id="txtDescricao" name="txtDescricao" value="{$this->Descricao}" maxlength="70" {$this->DisabledDesc} style="max-width:none;width:Calc(100% - 8px);" />
										</td>
										
									</tr>
								</table>								
							</div>
							
							<!-- Campo para emprestimo. -->
							<div id="opcao_emprestimo" class="campoLancamento" status="fechado" style="display:{$this->DisplayEmprestimo};">
								{$this->EmprestimoCampos}
							</div>	
							
							<div id="saldoDevedorRemanescente" class="campoLancamento" status="fechado" style="display:{$this->DisplayAmortizacao};">
								{$this->AmortizacaoEmprestimo}
							</div>	

							<!-- Campo de contas a Receber -->
							<div id="contasAReceber" class="campoLancamento" style="display: {$this->DisplayContasAReceber}";>
								{$this->CampoContasAReceber}
							</div>
							
							<!-- Campo de contas a Pagar -->
							<div id="contasAPagar" class="campoLancamento" style="display: {$this->DisplayContasAPagar};">
								{$this->CampoContasAPagar}
							</div>							

							<!-- Campo para anexar arquivos. -->	
							<div class="campoLancamento">
								{$this->Comprovantes}
							</div>

						</div>
						{$this->MontaBotao($editarId, $empresaId)}
					</form>
				</div>				
__HTML__;

		return $tags;
	}
	
	// Método criado para montar os botões de acordo com a edição e a inclusão. 
	private function MontaBotao($editarId, $empresaId){
	
		$tags = '';
		
		// Define os botões e os paramentros para inclusão e edição. 
		if(!$editarId){
			
		$tags .= <<<__HTML__
			<center>
				<input type="hidden" name="hidID" id="hidID" value="{$empresaId}" />
				<input name="incluir" type="submit" value="Incluir lançamento" id="btnSubmit" />
			</center>
__HTML__;
			
		} else {
			
		$tags .= <<<__HTML__
			<center>
				<input type="hidden" name="hidID" id="hidID" value="{$empresaId}" />
				<input type="hidden" name="hidLinha" id="hidLinha" value="{$editarId}"  />
				<input name="editar" type="submit" value="Editar lançamento" id="btnSubmit" />
				<input type="button" value="cancelar" onClick="location.href='livros_caixa_movimentacao.php'" />
			</center>
__HTML__;
			
		}
		
		// Define o retorno.
		return $tags;
	}
	
	private function selected( $value, $prev ) {
		
		return $value==$prev ? ' selected ' : ''; 
	}
		
	// Método criado para verificar se é entrada ou saída.  
	private function PegaLancamentoEntradaOuSaida($edicao, $status){
	
		$out = '';
		
		$out .= ' <select name="selTipoLancamento" id="selTipoLancamento"> ';
		
		if(!$edicao){
			$out .= ' <option value="">selecione</option><option value="entrada">entrada</option><option value="saida">saída</option> ';	
		} else {
			$out .= ' <option value="">selecione</option>
					  <option value="entrada" '.($status=='entrada' ? 'selected' : '').'>entrada</option>
					  <option value="saida"  '.($status=='saida' ? 'selected' : '').'>saída</option> ';		
		}
		
		$out .= '</select>';
		
		return $out;
	}

	function PegaOpcaoCategoria( $userIdSecaoMultiplo, $lancamento, $categoria ){
		
		// Pega as Categorias.
		$categoriaData = new CategoriasData();

		$categorias = $categoriaData->pegaTodosCategorias();
		
		$tagOption = "<option value=''>Selecione</option>";
		
		if($lancamento == "saida") {

			if($categorias) {
				
				foreach( $categorias as $val ){
					
					if($val->getCategoriaTipo() == 'S') {
						if($val->getCategoriaNome() == $categoria ) {
							if($val->getCategoriaAtivo() == 'A' || $val->getCategoriaAtivo() == 'I' && $userIdSecaoMultiplo == '9' ) {
								$tagOption .= "<option value='".$val->getCategoriaNome()."' selected>".$val->getCategoriaNome()."</option>";
							}
						} else {
							if($val->getCategoriaAtivo() == 'A' || $val->getCategoriaAtivo() == 'I' && $userIdSecaoMultiplo == '9' ) {
								$tagOption .= "<option value='".$val->getCategoriaNome()."'>".$val->getCategoriaNome()."</option>";
							}
						}
					}
				}
			}
			
		} else { 
		
			$livroCaixa = new LivrosCaixaMovimentacao();
			
			$dados_clientes = $livroCaixa->PegaApelidoCliente($userIdSecaoMultiplo);
			
			if($dados_clientes){
				
				foreach($dados_clientes as $val){
					
					$tagOption .='<option value="'.$val['apelido'].'"'.$this->selected( $val['apelido'], $categoria ).'>'.$val['apelido'].'</option>';
					
				}
			}

			if($categorias) {

				foreach( $categorias as $val ){
					
					if($val->getCategoriaTipo() == 'E') {
						if($val->getCategoriaNome() == $categoria ) {
							if($val->getCategoriaAtivo() == 'A' || $val->getCategoriaAtivo() == 'I' && $userIdSecaoMultiplo == '9' ) {
								$tagOption .= "<option value='".$val->getCategoriaNome()."' selected>".$val->getCategoriaNome()."</option>";
							}
						} else {
							if($val->getCategoriaAtivo() == 'A' || $val->getCategoriaAtivo() == 'I' && $userIdSecaoMultiplo == '9' ) {
								$tagOption .= "<option value='".$val->getCategoriaNome()."'>".$val->getCategoriaNome()."</option>";
							}
						}
					}
				}
			}												
		 }		
		
		$tagOption .= '</select>';
		
		return $tagOption;
		
	}
	
	// Método criado para pegar a pessoa que recebera o pagamento de acordo com a categoria informada 
	private function PegaQuemReceberapagamento($categoria, $empresaId, $descricao) {
			
		$livroCaixa = new LivrosCaixaMovimentacao();
		
		switch($categoria){
			
			case 'Estagiários':
								
				// Pega a lista de estagiários
				$dados = $livroCaixa ->PegaListaEstagiarios($empresaId);

				// Verifica se existe a lista de estagiários.
				if($dados) {
					
					$this->TipoPagamento = 'Estagiários';
					
					// Monta itens do select
					foreach($dados as $val) {
						$this->TagOption .= '<option '.$this->selected($val['id'],$descricao).' value="'.$val['id'].'">'.$val['nome'].'</option>';	
					}
				}				
				
				break;
			case 'Pgto. a autônomos e fornecedores':
				
				// Pega a lista de pagamento dos autônomos e fornecedores
				$dados = $livroCaixa ->PegaListaAutonomos($empresaId, $descricao);
				
				// Verifica se existe a lista de pagamento dos autônomos e fornecedores.
				if($dados) {
					
					$this->TipoPagamento = 'Pgto. a autônomos e fornecedores';	
					
					// Monta itens do select
					foreach($dados as $val) {
						$this->TagOption .= '<option '.$this->selected($val['id'],$descricao).' value="'.$val['id'].'">'.$val['nome'].'</option>';	
					}
				}			
				
				break;
			case 'Pagto. de Salários':
				
				// Pega a lista de pagamento de sálario.
				$dados = $livroCaixa ->PegaListaFuncionarios($empresaId);
				
				// Verifica se existe a lista de pagamento de salários.
				if($dados) {
					
					$this->TipoPagamento = 'Pagto. de Salários';
					
					// Monta itens do select
					foreach($dados as $val) {
						
						$this->TagOption .= '<option '.$this->selected($val['idFuncionario'],$descricao).' value="'.$val['idFuncionario'].'">'.$val['nome'].'</option>';	
					}
				}
				
				break;
			case 'Pró-Labore':
				
				// Pega a lista de pró-labore
				$dados = $livroCaixa ->PegaListaResponsavel($empresaId);
				
				// Verifica se existe a lista de pagamento de pró-labore.
				if($dados) {
					
					$this->TipoPagamento = 'Pró-Labore';
					
					// Monta itens do select
					foreach($dados as $val) {
						$this->TagOption .= '<option '.$this->selected($val['idSocio'],$descricao).' value="'.$val['nome'].'">'.$val['nome'].'</option>';
					}
				}
				
				break;
			case 'Distribuição de lucros':
				
				// Pega a lista de pró-labore
				$dados = $livroCaixa ->PegaListaResponsavel($empresaId);
				
				// Verifica se existe a lista de pagamento de pró-labore.
				if($dados) {
					
					$this->TipoPagamento = 'Distribuição de lucros';
					
					// Monta itens do select
					foreach($dados as $val) {
						$this->TagOption .= '<option '.$this->selected($val['idSocio'],$descricao).' value="'.$val['nome'].'">'.$val['nome'].'</option>';
					}
				}
				
				break;
		}

		if($this->TipoPagamento) {
			$this->DisplayPagamento = 'table-cell';
			$this->DisplayDescricao = 'none';
			$this->DisabledDesc = 'disabled';
		}
	} 
	
	// Método criado para montar o campo de emprestimo e armotização.
	private function MontaCampoEmprestimos($empresaId, $livroCaixaId){
		
		$out = ''; 
		
		$emprestimoArray = false;
		
		$campoEmprestimo = $emprestimoId = ''; 
		
		$livroCaixa = new LivrosCaixaMovimentacao();
		
		// Verifica se os dados que estão indo para edição e referente ao emprestimo ou armotização.
		$dados = $livroCaixa->PegaLinhaEmprestimoRelacionadaComLivroCaixa2($empresaId, $livroCaixaId);
			
		// inicia o status do emprestimo como falso.
		$status = false;
		
		// Verifica se existe dados.
		if($dados){
			
			// Verifica o tipo de cadastro.
			if($dados['tipo'] == 'emprestimo') {
				
				// se a carencia ou apelido não for informado o empréstimo e complementar, ou seja ele devera ser somado ao principal.
				if(!empty($dados['carencia']) && !empty($dados['apelido'])) {
					$status = $dados['tipo'];
				} else {
					$status = 'complementar';
				}
				
				// Chama o método para que informa que e uma edição e o seu status.
				$this->setStatusEmprestimo($dados['tipo']);				
			} else {
				
				// Define uma inclusão de emprestimo.
				$status = 'novo_emprestimo';
			}
						
			// Verifica se o empréstimo e principal ou complementar.
			if($status == 'emprestimo' || $status == 'complementar') {

				// inclui o campo de edição para empréstimo.
				if($status == 'emprestimo') {

					// Desabileita o select com os emprestimos.
					$this->DisplaySelectEmprestimo = 'none';
					
					// Habilita o campo dos emprestimos.
					$this->DisplayEmprestimo = 'none'; 
					
					$campoEmprestimo .= $this->CampoEmprestimoEdicao($dados['apelido'], $dados['carencia'], $dados['emprestimoId']);

				} // inclui o campo de edição para empréstimos complementares.
				else {
					
					$this->DisplaySelectEmprestimo = 'none';
					$this->DisplayEmprestimo = 'none'; 					
					
					$campoEmprestimo .= $this->CampoComplementarEmprestimo($dados['emprestimoId'], $dados['registroPaiId']);
				}
				
			} else {

				// campo para inclusão dos dados do empréstimo. 	
				$campoEmprestimo .= $this->CampoEmprestimoInclusao($empresaId);
			}			
			
			// Pega o código da linha do emprestimo no banco de dados.
			$emprestimoId = $dados['emprestimoId'];
		
			// Pega as opções do emprestimo.
			$optionEmprestimos = $this->MontaOpcaoEmprestimos($empresaId, $emprestimoId);
			
			$this->DisplaySelectEmprestimo = 'none';
			$this->DisplayEmprestimo = 'inline-block'; 				
			
			$optionNum = '';
			
			for ($i=1; $i < 25; $i++) { 
				$optionNum .= '<option value="'.$i.'" >'.$i.'</option>';
			}
			
		} else {

			// Pega as opções do emprestimo.
			$optionEmprestimos = $this->MontaOpcaoEmprestimos($empresaId);
			
			if($this->ListaEmprestimo){
				$this->StatusListaEmprestimo = true;
			}
			
			$campoEmprestimo .= $this->CampoEmprestimoInclusao($empresaId);
		}
		
		// Pega os campos de emprestimos.
		//$this->DisplayEmprestimo = = 'inline-block'; $displayEmprestimo;
		$this->EmprestimoCampos = $campoEmprestimo;
		$this->EmprestimoOption = $optionEmprestimos;	
	}
	
	// Método criado para criar a lista com os emprestimos.
	private function MontaOpcaoEmprestimos($empresaId, $emprestimoId = '') {
		
		$livroCaixa = new LivrosCaixaMovimentacao();
		
		$this->ListaEmprestimo = $livroCaixa->PegaListaDeEmprestimos($empresaId);
		
		$option = '';
		
		if($this->ListaEmprestimo){
			
			$option .= "<option value=''>Selecione</option>";
			
			foreach($this->ListaEmprestimo as $val) {

				if($emprestimoId == $val['emprestimoId']) {
					$option .= "<option value=".$val['emprestimoId']." selected>".$val['apelido']."</option>";
				} else {
					$option .= "<option value=".$val['emprestimoId'].">".$val['apelido']."</option>";
				}
			}
			
		} else {
			$option .= "<option value='SemListaEmprestimo'>Selecione</option>";
		}
		
		return $option;		
	}	
	
	// Monta o campo dos comprovantes.
	private function CampoComprovantes($empresaId = '', $id = '') {
		
		$tags = '';
		
		$livroCaixa = new LivrosCaixaMovimentacao();
		
		$filhoDados = false;
		
		// Pega os comprovantos do lançamento.
		$dados = $livroCaixa->Comprovantes($id, $empresaId);
				
		// pega o id do lançamento filho(contas a pagar e receber).
		$contasAPRDados = $livroCaixa->PegaLancamentoContasAReceberUoAPagar($empresaId, $id);
		
		// Pega os comprovantos do lançamento (contas a pagar e receber).
		if($contasAPRDados){
			$filhoDados = $livroCaixa->Comprovantes($contasAPRDados['livro_caixa_id'], $empresaId);
		}
			
		if($dados || $filhoDados) {
			
			$tags .= '<div style="float: left;margin-right: 20px; height: 25px;">
				<input type="file" name="anexos_doc[]" value="" multiple><br>
			</div>';
			
			if($filhoDados){

				foreach($filhoDados as $val){ 

				$tags .= ' <div style="display:inline-block;margin-right: 20px; height: 25px;"> '
						.'	<a href="#" class="excluirPagamento" imagem="sim" arquivo="'.$val["nome"].'" linha="'.$val["id"].'" pagto="" cat="" title="Excluir"> '
						.'		<i class="fa fa-times" aria-hidden="true" style="color:red;font-size:15px;"></i> '
						.'	</a>'
						.$val['nome']
						.'</div>';
				}
			}
			
			if($dados) {
				
				foreach($dados as $val){ 

					$tags .= ' <div style="display:inline-block;margin-right: 20px; height: 25px;"> '
							.'	<a href="#" class="excluirPagamento" imagem="sim" arquivo="'.$val["nome"].'" linha="'.$val["id"].'" pagto="" cat="" title="Excluir"> '
							.'		<i class="fa fa-times" aria-hidden="true" style="color:red;font-size:15px;"></i> '
							.'	</a>'
							.$val['nome']
							.'</div>';
				}
			}
			
		} else {
			
			$tags .= 'Anexar Comprovante(s): <input type="file" name="anexos_doc[]" value="" multiple style="margin-left:10px;margin-right:10px;"> (Max 1Mb)';
			
		}	
				
		// retorna o campo dos comprovantes.
		$this->Comprovantes = $tags;
	}
	
	// Monta a linhas complementar para Emprestimo.
	private function CampoEmprestimoInclusao($empresaId){
			
		// Verifica se existe lista de emprestimos ou sera a primeira inclusão.
		if($this->ListaEmprestimo){

			$out =' <div id="novoEmprestimo">
						<input type="hidden" value="inserEmprestimo" name="acaoEmprestimo" id="acaoEmprestimo">
						<span>Atribua um apelido para este empréstimo:</span>
						<input id="apelido" name="apelido" type="text" value="" style="margin-left:5px; width:150px;" disabled="">
						<a id="mostraSelecione" href="">Voltar</a>
						<span style="margin-left: 30px;">Informe o prazo de carência do empréstimo:</span>
						<select id="prazo_carencia" name="prazo_carencia" style="margin-left:5px;width:40px" disabled="">'.$this->MontaOpcaoCarencia().'</select> Meses
					</div>';
		} else {
			
			$out ='	<div id="novoEmprestimo">
						<input type="hidden" value="inserNovoEmprestimo" name="acaoEmprestimo" id="acaoEmprestimo">
						<span>Atribua um apelido para este empréstimo:</span>
						<input id="apelido" name="apelido" type="text" value="" style="margin-left:5px; width:150px;">
						<span style="margin-left: 30px;">Informe o prazo de carência do empréstimo:</span>
						<select id="prazo_carencia" name="prazo_carencia" style="margin-left:5px;width:40px">'.$this->MontaOpcaoCarencia().'</select> Meses
					</div>';
		}
					
		return $out;
	}
	
	// Monta a linhas complementar para Emprestimo.
	private function CampoEmprestimoEdicao($apelido, $carencia, $emprestimoId){
		
		$out ='<div style="display: inline-block;">
					<input type="hidden" value="'.$emprestimoId.'" name="emprestimoId">
					<input type="hidden" value="editarEmprestimo" name="acaoEmprestimo" id="acaoEmprestimo">
					<span>Atribua um apelido para este empréstimo:</span>
					<input id="apelido" name="apelido" type="text" value="'.$apelido.'" style="margin-left:5px; width:150px;"/>
					<span style="margin-left: 30px;">Informe o prazo de carência do empréstimo:</span>
					<select id="prazo_carencia" name="prazo_carencia" style="margin-left:5px;width:40px">'.$this->MontaOpcaoCarencia($carencia).'</select> Meses
				</div>';
		
		return $out;
	}	
	
	// Monta a linahs complementar para Amortizção.
	private function CampoComplementarEmprestimo($emprestimoId, $registroPaiId){
			
		$livroCaixa = new LivrosCaixaMovimentacao();
		
		$emprestimo = $livroCaixa->PegaEmprestimoAmortizacaoPorId($registroPaiId);
		
		$out =' <input type="hidden" value="'.$emprestimoId.'" name="emprestimoId">
				<input type="hidden" value="'.$registroPaiId.'" name="registroPaiId">
				<input type="hidden" value="editarComplementar" name="acaoEmprestimo" id="acaoEmprestimo">
				<div style="display: inline-block;">
					<span>Empréstimo: <b>'.$emprestimo['apelido'].'</b></span>
				</div>';
		
		return $out;
	}	
	
		// Monta o campo complementar para Amortizção.
	private function CampoAmortizacao($empresaId, $livroCaixaId){
		
		$livroCaixa = new LivrosCaixaMovimentacao();
		
		// Verifica se os dados que estão indo para edição e referente ao emprestimo ou armotização.
		$dados = $livroCaixa->PegaLinhaAmortizacaoEmprestimo($empresaId, $livroCaixaId);
		
		if($dados){
			
			$this->DisplayAmortizacao = 'inline-block';
			
			// Pega os dados do registro Pai(empréstimo).
			$emprestimo = $livroCaixa->PegaEmprestimoAmortizacaoPorId($dados['registroPaiId']);
			
			// Inclui os dados da armotização e os dados do emprestimo pai.
			$this->AmortizacaoEmprestimo ='	<input type="hidden" value="'.$dados['emprestimoId'].'" name="emprestimoId">
			<input type="hidden" value="'.$dados['registroPaiId'].'" name="registroPaiIdAmortizacao">
			<input type="hidden" value="editarAmortizacao" name="acaoAmortizacao" id="acaoAmortizacao">
			<span>Empréstimo a ser amortizado: <b>'.$emprestimo['apelido'].'</b></span>										
			<span style="margin-left: 30px;">Saldo devedor remanescente:</span>
			<input id="saldoRemanescente" class="current" name="saldoRemanescente" type="text" value="'.number_format($dados['saldo_remanescente'],2,',','.').'" style="margin-left:5px;">';
			
		} // Define o campo para a inclusão da armotização. 
		else {
		
			// Chama o método para montar o campo de armotização para inclusão.
			$this->CampoAmortizacaoInclusao($empresaId);
		}
	}
	
	// Monta o campo complementar para Amortizção.
	private function CampoAmortizacaoInclusao($empresaId){

		$this->StatusAmortizaca = true;
		
		$this->AmortizacaoEmprestimo = ' <input type="hidden" value="inserArmotizacao" name="acaoAmortizacao" id="acaoAmortizacao">
				<span>Saldo devedor remanescente:</span>
				<input id="saldoRemanescente" class="current" name="saldoRemanescente" type="text" value="" style="margin-left:5px;" disabled="">';
		
		// Pega a lista de emprestimos.
		$this->AmortizacaoOption = $this->MontaOpcaoEmprestimos($empresaId);
	}
	
	
	// Monta o campo referente ao contas a receber.
	private function CompoContasAReceber($empresaId, $livroCaixaId = '', $categoria = '') {
		
		$livroCaixa = new LivrosCaixaMovimentacao();
		
		// Pega a data do lançamento de contas a receber a ser editado.
		$dadosContasReceber = $livroCaixa->PegaDataLancamentoContasAReceber($empresaId, $livroCaixaId);
		
		if($dadosContasReceber){

			$data = date('d/m/Y', strtotime($dadosContasReceber['data']));
			$vencimento = date('d/m/Y', strtotime($dadosContasReceber['vencimento']));
			$valor = number_format($dadosContasReceber['entrada'],2,',','.');			

			$this->DisplayContasAReceber = 'inline-block';			
			
			$this->CampoContasAReceber = '<span>Data do documento: </span>
				<input name="dataNotaFiscal" id="dataNotaFiscal" type="text" maxlength="10" style="width:80px;" class="campoData" value="'.$data.'" />				
				<span class="linhaSpan">Vencimento: </span>
				<input name="dataVencimento" id="dataVencimentoReceber" type="text" maxlength="10" style="width:80px;" class="campoData" value="'.$vencimento.'" />
				<span class="linhaSpan">Valor original (sem juros e multa): </span>
				<input type="text" id="valorOriginalReceber" class="current" name="valorOriginal" style="width:80px;" maxlength="70" value="'.$valor.'" />
				<a id="mostraSelecioneReceber" href="" style="display:none;">Voltar</a>';	

		} else {
			
			$dados = $livroCaixa->PegaListaLancamentoContasAReceber($empresaId, $categoria);

			if($dados){			
			
				$this->StatusContasAReceber = true;
				
				foreach($dados as $val){
					$this->OpcaoContasAReceber .= "<option value='".$val['livro_caixa_id']."'>".$val['descricao']."</option>";
				}
			}
				
			$this->CampoContasAReceber = '<span>Data do documento: </span>
				<input name="dataNotaFiscal" id="dataNotaFiscal" type="text" maxlength="10" style="width:80px;" class="campoData" disabled />						
				<span class="linhaSpan">Vencimento: </span>
				<input name="dataVencimento" id="dataVencimentoReceber" type="text" maxlength="10" style="width:80px;" class="campoData" disabled />
				<span class="linhaSpan">Valor original (sem juros e multa): </span>
				<input type="text" id="valorOriginalReceber" class="current" name="valorOriginal" style="width:80px;" maxlength="70" disabled />
				<a id="mostraSelecioneReceber" href="" style="display:none;">Voltar</a>';
			
		}
	}

	// Monta o campo referente ao contas a pagar.
	private function CompoContasAPagar($empresaId, $livroCaixaId = '', $categoria = '') {
		
		$livroCaixa = new LivrosCaixaMovimentacao();
		
		// Pega a data do lançamento de contas a pagar a ser editado.
		$dadosContasPagar = $livroCaixa->PegaDataLancamentoContasAPagar($empresaId, $livroCaixaId);
		
		// Define a data com nome "Data Pagto." quando for contas a pagar.
				
		if($dadosContasPagar){
			
			$this->DataName = 'Data Pagto.';
			
			$data = date('d/m/Y', strtotime($dadosContasPagar['data']));
			$vencimento = date('d/m/Y', strtotime($dadosContasPagar['vencimento']));
			$valor = number_format($dadosContasPagar['saida'],2,',','.');
			
			$this->DisplayContasAPagar = 'inline-block';
		
			$this->CampoContasAPagar = '<span>Data do documento: </span>
				<input name="dataNotaFiscal" id="dataDoc" type="text" maxlength="10" style="width:80px;" class="campoData" value="'.$data.'" />						
				<span class="linhaSpan">Vencimento: </span>
				<input name="dataVencimento" id="dataVencimentoPagar" type="text" maxlength="10" style="width:80px;" class="campoData" value="'.$vencimento.'" />
				<span class="linhaSpan">Valor original (sem juros e multa): </span>
				<input type="text" id="valorOriginalPagar" class="current" name="valorOriginal" style="width:80px;" maxlength="70" value="'.$valor.'" />
				<a id="mostraSelecionePagar" href="" style="display:none;">Voltar</a>';

		} else {
			
			$dados = $livroCaixa->PegaListaLancamentoContasAPagar($empresaId, $categoria);

			if($dados){			
			
				$this->StatusContasAPagar = true;
				
				foreach($dados as $val){
					$this->OpcaoContasAPagar .= "<option value='".$val['livro_caixa_id']."'>".$val['descricao']."</option>";
				}
			}
			
			$this->CampoContasAPagar = '<span>Data do documento: </span>
				<input name="dataNotaFiscal" id="dataDoc" type="text" maxlength="10" style="width:80px;" class="campoData" disabled />						
				<span class="linhaSpan">Vencimento: </span>
				<input name="dataVencimento" id="dataVencimentoPagar" type="text" maxlength="10" style="width:80px;" class="campoData" disabled />
				<span class="linhaSpan">Valor original (sem juros e multa): </span>
				<input type="text" id="valorOriginalPagar" class="current" name="valorOriginal" style="width:80px;" maxlength="70" disabled />
				<a id="mostraSelecionePagar" href="" style="display:none;">Voltar</a>';
		}
	}

	// método usado para criar a lista de opção do select referenta a carência
	private function MontaOpcaoCarencia($carencia=''){
		
		$optionCarencia = '';
		
		// Cria lista de opção do select referenta a carência
		for ($i=1; $i < 25; $i++) {
			if($carencia == $i ) {
				$optionCarencia .= '<option value="'.$i.'" selected>'.$i.'</option>';
			} else {
				$optionCarencia .= '<option value="'.$i.'" >'.$i.'</option>';
			}
		}
		
		return $optionCarencia;
	}
	
	public function PegaAnexos($id_lancamento, $empresaId) {
				
		$out =  '';
		
		$Contas_rec_pag_comprovantes = false;
		
		$livroCaixa = new LivrosCaixaMovimentacao();
		
		$comprovantes = $livroCaixa->Comprovantes($id_lancamento, $empresaId);
		
		// Pega o id do lançamento de "contas a pagar e receber".
		$contasAPRDados = $livroCaixa->PegaLancamentoContasAReceberUoAPagar($empresaId, $id_lancamento);
		
		// Pega os comprovantos do lançamento (contas a pagar e receber).
		if($contasAPRDados){
			$Contas_rec_pag_comprovantes = $livroCaixa->Comprovantes($contasAPRDados['livro_caixa_id'], $empresaId);			
		}
		
		if( $comprovantes || $Contas_rec_pag_comprovantes ){
			
			if($Contas_rec_pag_comprovantes){
				
				foreach($Contas_rec_pag_comprovantes as $val){ 

					if( isset( $val['nome'] )) {
						$out .=  '<div class="download-arquivo"><a href="upload/comprovantes/'.$val['nome'].'" download>'
								.'<i class="fa fa-file-text-o icone-download" aria-hidden="true"></i>'
								.'</a><div class="mouse_over_nome_arquivo">'.$val['nome'].'</div></div>';
					} else {
						$out .= '<i class="fa fa-file-text-o icone-download" aria-hidden="true"></i>';	
					}
				}
			}
			
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
		}
		
		return $out;
	}
	
	public function AjaxLivroCaixa($method){
		
		// Chama o método. 
		$this->$method();			
	}
	
	private function ListaContasAPagar() {
		
		$status = false;
		
		$empresaId = $_SESSION['id_empresaSecao'];
			
		$categoria = $_POST['categoria'];
		
		$livroCaixa = new LivrosCaixaMovimentacao();
		
		$dados = $livroCaixa->PegaListaLancamentoContasAPagar($empresaId, $categoria);

		$out = "<option value=''>Selecione</option>";
				
		if($dados){			
				
			$status = true;
			
			foreach($dados as $val) {
				
				$out .= "<option value='".$val['livro_caixa_id']."'>".$val['descricao']."</option>";
			}
		}
		
		echo json_encode(array('option'=>$out,'status'=>$status)); 
		
	}
	
	private function ListaContasAReceber() {
		
		$status = false;
		
		$empresaId = $_SESSION['id_empresaSecao'];
			
		$categoria = $_POST['categoria'];

		$livroCaixa = new LivrosCaixaMovimentacao();
		
		$dados = $livroCaixa->PegaListaLancamentoContasAReceber($empresaId, $categoria);

		$out = "<option value=''>Selecione</option>";
		
		if($dados){			
				
			$status = true;
			
			foreach($dados as $val) {
				
				$out .= "<option value='".$val['livro_caixa_id']."'>".$val['descricao']."</option>";
			}
		}
		
		echo json_encode(array('option'=>$out,'status'=>$status)); 
	}	
}

?>