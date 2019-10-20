<?php
/**
 * Autor: Átano de Farias
 * Data: 23/06/2017 
 */
// Inclui classe responsavel por manipular os objetos.
require_once("Model/DadosServicosAvulso/DadosServicosAvulsoData.php");

class ContrataServicos {
	
	// Método Criado para incluir a tabela de serviços na tela.
	public function GetTableServicos (){
		
		$data = new DadosServicosAvulsoData();
		
		$servicosObject = $data->PegaObjetoComServicos();
		
		$class =  "semDados";
		
		// Data atual
		$data = date("d/m/Y");
		
		// Variável que recebera as linhas da tabela.
		$tagTr = '<table style="font-size:12px;" width="630" cellspacing="2" cellpadding="4" border="0">
                <tbody>
                    <tr>
                        <th style="text-align: center;">Serviço</th>
                        <th style="text-align: center; width:20%;">Valor</th>
                        <th style="text-align: center; width:20%;">Ação</th>
                    </tr>';
		
		// Verifica se a dados.
		if($servicosObject) {
		
			// Verifica os itens da variavel dados.
			foreach($servicosObject as $val) {

				// Verifica se chamada do método é da linha para help desk ou contrato.
				if($val->getHelpDesk() == 'N') {	

					$tagTr .= $this->linhaTable($val->getNome(), $val->getValor(), $val->getTipo(), $val->getContratoId());

				} else {
					
					$tagTr .= $this->linhaHelpDesk($val->getNome());
				}
			}
		}
		
		$tagTr .= '</tbody></table>';
		
		return $tagTr; 
	}
	
	// Método utilizado para criar as linhas da tabela.
	private function linhaTable($nome, $valor, $tipo, $contratoId) {
		
		// Gera o Link 
		$link = 'href="servico_informar_dados_contratar.php?tipo='. $tipo .'&valor='. $valor .'&contratoId='.$contratoId.'" ';
		
		$out = <<<_TAG_
			<tr class="linha1">
				<td>{$nome}</td>
				<td style="text-align: center;">R$ {$valor}</td>
				<td style="text-align: center;">
					<a style="cursor:pointer; text-align: center; text-decoration: underline; " {$link} >Contratar</a>
				</td>
			</tr>
_TAG_;

		return $out;

	}
	
	// Cria linha para help desk.  
	public function linhaHelpDesk($nome) {
		
		$out = '';
		
		$out = <<<_TAG_
			<tr class="linha1">
				<td>{$nome}</td>
				<td colspan="2" style="text-align: center;">
					<a href="/suporte.php">Contate o nosso help desk</a>
				</td>
			</tr>
_TAG_;

		return $out;
	}	
	
}

?>