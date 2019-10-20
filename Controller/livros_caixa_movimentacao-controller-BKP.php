<?php
/**
 * Esta classe esta sendo criada para tentar organizar o arquivo livros_caixa_movimentacao.php
 * data: 26/01/2018 as 12:53
 */
// Faz a requisição do arquivo que manipula os dados do emprestimo.
require_once('DataBaseMySQL/LivroCaixa.php');

class LivrosCaixaMovimentacao {
	
	private $StatusEmprestimo = false;
	
	public function getStatusEmprestimo(){
		return $this->StatusEmprestimo;
	}
	
	// Método utilizado para setar o status do emprestimo como verdadeiro quando for edição. 
	private function setStatusEmprestimo($val){
		$this->StatusEmprestimo = $val;
	}	
	
	// Gera a tabela com os campos complementares de acordo com a categoria selecionada. 
	function LinhaDadosComplementaresEmprestimo($empresaId, $livroCaixaId){
		
		$livroCaixa = new LivroCaixa();
		
		// Verifica se os dados que estão indo para edição e referente ao emprestimo ou armotização.
		$dados = $livroCaixa->PegaLinhaEmprestimoRelacionadaComLivroCaixa($empresaId, $livroCaixaId);
		
		// inicia o status do emprestimo como falso.
		$status = false;
		
		// inicia a variável responsável por  apresenta a tabela com os campos para edição do emprestimo vazio.
		$out = '';
		
		// Verifica se existe dados.
		if($dados){
			
			// Verifica o tipo de cadastro.
			if($dados['tipo'] == 'amortizacao') {
				$status = $dados['tipo'];
				
				// Chama o método para que informa que e uma edição e o seu status.
				$this->setStatusEmprestimo($dados['tipo']);
			} elseif($dados['tipo'] == 'emprestimo') {
				
				// se a carencia ou apelido não for innnnnformado o empréstimo e complementar, ou seja ele devera ser somado ao principal.
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
		}
		
		$out .= '<table id="tabEmprestimo" status="'.$status.'" border="0" cellspacing="2" cellpadding="4"  style="width: 100%;margin-bottom: 2px;margin-top: -2px"><tbody>';

		// Verifica se o empréstimo e principal ou complementar.
		if($status == 'emprestimo' || $status == 'complementar') {
			
			// inclui a linha de edição para empréstimo.
			if($status == 'emprestimo') {		
				$out .= $this->LinhasEmprestimoEdicao($dados['apelido'], $dados['carencia'], $dados['emprestimoId']);
				
			} // inclui a linha de edição para empréstimos complementares.
			else {
				$out .= $this->LinhasComplementarEmprestimo($dados['emprestimoId'], $dados['registroPaiId']);
			}
			
			// Linhas para inclusão dos dados de amortização.
			$out .= $this->LinhasAmortizacaoInclusao($empresaId);
				
		} // Verifica se o campo de amortização a ser incluido na tela sera de inclusão ou edição.
		elseif($status == 'amortizacao') {
		
			// Pega os dados do registro Pai(empréstimo).
			$emprestimo = $livroCaixa->PegaEmprestimoAmortizacaoPorId($dados['registroPaiId']);

			$out .= $this->LinhasAmortizacaoEdicao($emprestimo['apelido'], $dados['registroPaiId'], $dados['saldo_remanescente'], $dados['emprestimoId']);
			
			// Linhas para inclusão dos dados do empréstimo. 	
			$out .= $this->LinhasEmprestimoInclusao($empresaId);
			
		} else {
		
			// Linhas para inclusão dos dados do empréstimo. 	
			$out .= $this->LinhasEmprestimoInclusao($empresaId);
						
			$out .= $this->LinhasAmortizacaoInclusao($empresaId);
			
		}

		// Pega a data da nota caso exista.
		$lancamentoDuplo = $livroCaixa->PegaDataLancamentoDuplo($empresaId, $livroCaixaId);

		if(isset($lancamentoDuplo['categoria']) && $lancamentoDuplo['categoria'] == 'Cliente Contas Apagar'){
			$dataNota = date('d/m/Y', strtotime($lancamentoDuplo['data']));
			$out .= $this->LinhaDadosServicosPrestadosGeral($dataNota);
		} else {
			$out .= $this->LinhaDadosServicosPrestadosGeral();
		}
		
		if(isset($lancamentoDuplo['categoria']) && $lancamentoDuplo['categoria'] == 'Cliente Contas Apagar'){
			$data = date('d/m/Y', strtotime($lancamentoDuplo['data']));
			//$out .= $this->LinhaDadosContasAPagar($data);
		} else {
			$out .= $this->LinhaDadosContasAPagar();
		}
		
		$out .= '</tbody></table>';			
		
		return $out;
	}
	
	// Monta a linhas complementar para Emprestimo.
	private function LinhasEmprestimoInclusao($empresaId){
		
		// instância a classe respônsavel por manipular os dados do emprestimo.
		$livroCaixa = new LivroCaixa();
		
		$lista = $livroCaixa->PegaListaDeEmprestimos($empresaId);
		
		$option = '';		
			
		// Verifica se existe a lista.
		if($lista){
			
			$option .= "<option value=''>Selecione</option>";
			
			// Percorre a lista de empréstimos para monta o select da tela. 
			foreach($lista as $val){
				$option .= "<option value=".$val['emprestimoId'].">".$val['apelido']."</option>";
			}

			$out ='<tr style="width: 100%; display:none; background-color: #e5e5e5;" id="opcao_emprestimo" status="fechado">
					<td class="td_calendario" style="font-size: 12px;" >
							<input type="hidden" value="inserEmprestimo" name="acao" id="acao">
							<div id="selecioneEmprestimo" style="display: inline-block;">
								<span>Atribua um apelido para este empréstimo:</span>
								<select id="registroPaiId" name="registroPaiId" style="margin-left:5px; width:150px; disabled="">'.$option.'</select>
								<a id="mostraImput" href="">Novo apelido</a>
							</div>
							<div id="novoEmprestimo" style="display: none;">
								<span>Atribua um apelido para este empréstimo:</span>
									<input id="apelido" name="apelido" type="text" value="" style="margin-left:5px; width:150px;" disabled="">
									<a id="mostraSelecione" href="">Voltar</a>
									<span style="margin-left: 30px;">Informe o prazo de carência do empréstimo:</span>
									<select id="prazo_carencia" name="prazo_carencia" style="margin-left:5px;width:40px" disabled="">'.$this->MontaOpcaoCarencia().'</option>
								</select> Meses
							</div>
						</td>
					</tr>';
		} else {
			
				$out ='<tr style="width: 100%; display:none; background-color: #e5e5e5;" id="opcao_emprestimo" status="fechado"> 
					<td class="td_calendario" style="font-size: 12px;" >
						<div id="novoEmprestimo">
							<input type="hidden" value="inserNovoEmprestimo" name="acao" id="acao">
							<span>Atribua um apelido para este empréstimo:</span>
								<input id="apelido" name="apelido" type="text" value="" style="margin-left:5px; width:150px;">
								<span style="margin-left: 30px;">Informe o prazo de carência do empréstimo:</span>
								<select id="prazo_carencia" name="prazo_carencia" style="margin-left:5px;width:40px">'.$this->MontaOpcaoCarencia().'</option>
							</select> Meses
						</div>
					</td>
				</tr>';
		}
			
		
		return $out;
	}
	
	// Monta a linhas complementar para Emprestimo.
	private function LinhasEmprestimoEdicao($apelido, $carencia, $emprestimoId){
		
		$out ='<tr id="opcao_emprestimo" style="width: 100%; background-color: #e5e5e5;">
				<td  class="td_calendario" style="font-size: 12px;" status="aberto" >
					<div style="display: inline-block;">
					<input type="hidden" value="'.$emprestimoId.'" name="emprestimoId">
					<input type="hidden" value="editarEmprestimo" name="acao" id="acao">
						<span>Atribua um apelido para este empréstimo:</span>
						<input id="apelido" name="apelido" type="text" value="'.$apelido.'" style="margin-left:5px; width:150px;"/>
						<span style="margin-left: 30px;">Informe o prazo de carência do empréstimo:</span>
						<select id="prazo_carencia" name="prazo_carencia" style="margin-left:5px;width:40px">'.$this->MontaOpcaoCarencia($carencia).'</select> Meses
					</div>
				</td>
			</tr>';
		
		return $out;
	}
	
	// Monta a linahs complementar para Amortizção.
	private function LinhasAmortizacaoEdicao($apelidoEmprestimo, $registroPaiId, $saldoRemanescente, $emprestimoId){
				
		$out ='<tr id="saldoDevedorRemanescente" style="width: 100%; background-color: #e5e5e5;">
				<td class="td_calendario" style="font-size: 12px;">
					<input type="hidden" value="'.$emprestimoId.'" name="emprestimoId">
					<input type="hidden" value="'.$registroPaiId.'" name="registroPaiIdAmortizacao">
					<input type="hidden" value="editarAmortizacao" name="acao" id="acao">
					<span>Empréstimo a ser amortizado: <b>'.$apelidoEmprestimo.'</b></span>										
					<span style="margin-left: 30px;">Saldo devedor remanescente:</span>
					<input id="saldoRemanescente" class="current" name="saldoRemanescente" type="text" value="'.number_format($saldoRemanescente,2,',','.').'" style="margin-left:5px;">
				</td>
			</tr>';
		
		return $out;
	}
	
	// Monta a linahs complementar para Amortizção.
	private function LinhasAmortizacaoInclusao($empresaId){

			$out ='<tr id="saldoDevedorRemanescente" style="display:none; background-color: #e5e5e5;"> 
					<td class="td_calendario" style="font-size: 12px;">
						<input type="hidden" value="inserArmotizacao" name="acao" id="acaoAmortizacao">
						<span>Informe o apelido:</span>										
						<select id="registroPaiIdAmortizacao" name="registroPaiIdAmortizacao" style="margin-left:5px;" disabled="">'.$this->MontaOpcaoEmprestimos($empresaId).'</select>
						<span style="margin-left: 30px;">Saldo devedor remanescente:</span>
						<input id="saldoRemanescente" class="current" name="saldoRemanescente" type="text" value="" style="margin-left:5px;" disabled="">
					</td>
				</tr>';
		
		return $out;
	}
	
	// Monta a linahs complementar para Amortizção.
	private function LinhasComplementarEmprestimo($emprestimoId, $registroPaiId){
			
		$livroCaixa = new LivroCaixa();
		
		$emprestimo = $livroCaixa->PegaEmprestimoAmortizacaoPorId($registroPaiId);
		
		$out ='<tr id="opcao_emprestimo" style="background-color: #e5e5e5;"> 
				<td class="td_calendario" style="padding-bottom: 8px;padding-top:8px;font-size: 12px; display: block;">
					<input type="hidden" value="'.$emprestimoId.'" name="emprestimoId">
					<input type="hidden" value="'.$registroPaiId.'" name="registroPaiId">
					<input type="hidden" value="editarComplementar" name="acao" id="acao">
					<div style="display: inline-block;">
						<span>Empréstimo: <b>'.$emprestimo['apelido'].'</b></span>
					</div>		
				</td></tr>';
		
		return $out;
	}
	
	// Método criado para definir ação serviços prestados em geral.
	private function LinhaDadosServicosPrestadosGeral($dataNota = false) {
		
		$display = $disabled = '';
		
		if(!$dataNota){
			$display = 'display: none;';
			$disabled = 'disabled';
		}
		
		$out = '<tr id="servicosPrestados" style="'.$display.' background-color: #e5e5e5;">
					<td class="td_calendario" style="font-size: 12px;">
						<span>Data da nota fiscal:</span>
						<input name="dataNotaFiscal" id="dataNotaFiscal" type="text" maxlength="10" style="width:70px;font-size:12px;" class="campoData" value="'.$dataNota.'" '.$disabled.' />
					<td>
				</tr>';
		
		return $out;
	}
	
	// Método criado para incluir a linhas para pefar os dados do conta a pagar.
	private function LinhaDadosContasAPagar($data = false) {
		
		$display = $disabled = '';
		
		if(!$data){
			$display = 'display: none;';
			$disabled = 'disabled';
		}
		
		$out = '<tr id="servicosPrestados" style="'.$display.' background-color: #e5e5e5;">
					<td class="td_calendario" style="font-size: 12px;">
						<span>Data do boleto:</span>
						<input name="dataBoleto" id="dataBoleto" type="text" maxlength="10" style="width:70px;font-size:12px;" class="campoData" value="'.$data.'" '.$disabled.' />
					<td>
				</tr>';
		
		return $out;
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
	
	// Método criado para criar a lista com os emprestimos.
	private function MontaOpcaoEmprestimos($empresaId, $emprestimoId = '') {
		
		$livroCaixa = new LivroCaixa();
		
		$lista = $livroCaixa->PegaListaDeEmprestimos($empresaId);
		
		$option = '';
		
		if($lista){
			
			$option .= "<option value=''>Selecione</option>";
			
			foreach($lista as $val) {

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
}

?>