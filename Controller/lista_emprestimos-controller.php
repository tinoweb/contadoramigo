<?php
/**
 * Class criada para minipular os dados dos emprestimos.
 * data: 23/01/2018
 */
// realiza a requisição do arquivo que manipula os dados do emprestimo.
require_once('DataBaseMySQL/Emprestimos.php');

class ListaEmprestimosController{
	
	public function PegaSelectAno(){
		
		// Pega o codigo da empresa atraves da sessão.
		$empresaId = $_SESSION['id_empresaSecao'];
		
		$out = "<select id='ano' name='ano' style='float: right;'><option value=''>Selecione</option>"; 
		
		$emprestimos = new Emprestimos();
		
		// Pega o menor ano do emprestimo da empresa.
		$data = $emprestimos->PegaMenorAno($empresaId);

		$anoMenor = date('Y');
		
		if(isset($data['ano'])){
			$anoMenor = $data['ano']; 
		}
			
		$ano = date('Y');
		
		$ultimoAnoLista = $ano + 1;
		
		$ultimoAno = $emprestimos->PegaMaiorAno($empresaId);		
		
		if($ultimoAno){
			$ano = $ultimoAno['ano'];
		}
		
		// Verifica se o ano foi definido.
		if(isset($_GET['ano']) && !empty($_GET['ano'])){
			$ano = $_GET['ano'];
		}
	
		for($i=$anoMenor; $i<=$ultimoAnoLista; $i++){
			
			if($ano == $i){
				$out .= "<option value='".$i."' selected>".$i."</option>";
			} else {
				$out .= "<option value='".$i."'>".$i."</option>";
			}
		}
		
		$out .= "</select>";
		
		return $out;
	}
	
	private function MontaLinhaEmprestimo($data, $valor){
		
		$out = '';
		
		$out = '<tr class="linha_entrada">
					<td class="td_calendario" align="center">'.date('d/m/Y', strtotime($data)).'</td>
					<td class="td_calendario" align="right">R$ '.number_format($valor,2,',','.').'</td>
					<td class="td_calendario" align="right"></td>
					<td class="td_calendario" align="right"></td>
					<td class="td_calendario" align="right"></td>
				</tr>';
		
		return $out;
	}
	
	private function MontaLinhaAmortizacao($data, $valorAmortizacao, $juros, $saldoRemanescente){
		
		$out = '';
		
		$out = '<tr>
					<td class="td_calendario" align="center">'.date('d/m/Y', strtotime($data)).'</td>
					<td class="td_calendario" align="right"></td>
					<td class="td_calendario" align="right">R$ '.number_format($valorAmortizacao,2,',','.').'</td>
					<td class="td_calendario" align="right">R$ '.number_format($juros,2,',','.').'</td>
					<td class="td_calendario" align="right">R$ '.number_format($saldoRemanescente,2,',','.').'</td>
				</tr>';
		
		return $out;
	}	
		
	// Método criado para gerar a tabela.
	public function GeraTabelasEmprestimos() {
		
		// Pega o codigo da empresa atraves da sessão.
		$empresaId = $_SESSION['id_empresaSecao'];
		
		// Pega o ano atual. 
		$ano = date('Y');
		
		$emprestimos = new Emprestimos();
		
		$ultimoAno = $emprestimos-> PegaMaiorAno($empresaId);		
		
		if($ultimoAno){
			$ano = $ultimoAno['ano'];
		}
		
		// Verifica se o ano foi definido.
		if(isset($_GET['ano']) && !empty($_GET['ano'])){
			$ano = $_GET['ano'];
		}
		
		$out = '';
		
		$emprestimos = new Emprestimos();
		
		// Pega o emprestimo.
		$dadosEmprestimo = $emprestimos->PegaEmprestimos($empresaId, $ano);

		// Verifica se existe empréstimo.
		if($dadosEmprestimo) {

			// Loop criado para percorrer a lista se emprestimo.
			foreach($dadosEmprestimo as $valEmp) {

				/* --- Inicio --------------------- Pega os emprestimos. ---------------------------------------- */

				$out .='<div class="tituloVermelho" style="float:left;">'.$valEmp['apelido'].'</div>
						<div style="float:right;"><b>Carência: '.$valEmp['carencia'].'</b></div>
						<div style="clear: both; height:5px;"></div>
						<table border="0" cellspacing="2" cellpadding="4" width="966" style="font-size: 11px; margin-bottom:20px">
							<tbody>
								<tr>
									<th width="20%" align="center" style="font-size: 12px;">Data</th>
									<th width="20%" align="center" style="font-size: 12px;">Entrada</th>
									<th width="20%" align="center" style="font-size: 12px;">Amortização</th>
									<th width="20%" align="center" style="font-size: 12px;">Juros</th>
									<th width="20%" align="center" style="font-size: 12px;">Saldo Remanescente</th>
								</tr>';

				// Monta a linha do emprestimo na tabela.
				$out .= $this->MontaLinhaEmprestimo($valEmp['data_emprestimo'], $valEmp['entrada']);	

				// Pega empréstimos complementares.
				$complementarEmprestimo = $emprestimos->PegaEmprestimosComplementar($empresaId, $valEmp['emprestimoId']);

				// Verifica se existe empréstimo relacionado ao emprestimo pai.
				if($complementarEmprestimo){

					foreach($complementarEmprestimo as $val){

						// Monta a linha do emprestimo na tabela.
						$out .= $this->MontaLinhaEmprestimo($val['data_emprestimo'], $val['entrada']);	
					}
				}

				/* --- Fim ------------------------ Pega os emprestimos. ---------------------------------------- */

				/* --- Inicio --------------------- Pega a amortização do empréstimo. --------------------------- */

				// Pega a amortização dos empréstimos.
				$amortizacao = $emprestimos->PegaAmortizacaoEmprestimos($empresaId, $valEmp['emprestimoId']);

				// Verifica se existe amortização relacionado ao emprestimo pai.
				if($amortizacao){

					foreach($amortizacao as $valAm){

						// Monta a linha da amortizaçao na tabela.
						$out .= $this->MontaLinhaAmortizacao($valAm['data_emprestimo'], $valAm['amortizacao'], $valAm['juros'], $valAm['saldo_remanescente']);
					}
				}

				/* -- Fim ------------------------- Pega a amortização do empréstimo. --------------------------- */

				// Fecha a tag da tabela.
				$out .= '</tbody></table>';	
			}
		}
		
		return $out;
	}
}
?>