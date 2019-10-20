<?php

// Inclui classe responsavel por manipular os objetos.
require_once("Model/DadosFuncionarios/DadosFuncionariosData.php");

class ServicosAssinaturaContador {
	
	// Método utilizado para apresntar a liste de links dos Boletos.
	public function itensTabela($empresaId) {
		
		$object = false;
		
		$dadosFuncionarios = new DadosFuncionariosData();
		
		$dados = $dadosFuncionarios->PegaListaObjetoFuncionario($empresaId);
		
				
		// Variável que recebera as linhas da tabela.
		$tagTr = '<table style="font-size:12px;" width="630" cellspacing="2" cellpadding="4" border="0">
                <tbody>
                    <tr>
                        <th style="text-align: center;">Funcionários</th>
						<th style="text-align: center; width:15%;">Mês/Ano</th>
                        <th style="text-align: center; width:10%;">Baixar</th>
                    </tr>';
	
			if($dados) {
				
				// Verifica os itens da variavel dados.
				foreach($dados as $val) {
					$tagTr .= $this->linhaTable($val->getFuncionarioId(), $val->getNome());
				}
			}
		
		$tagTr .= '</tbody></table>';
	
		return $tagTr;
	}

	// Método utilizado para criar as linhas da tabela.
	public function linhaTable($funcionarioId, $nome) {
		
		$mesAno = date('m/Y');
				
		$out = <<<_TAG_
			<tr class="linha1">
				<td>{$nome}</td>
				<td><input id="{$funcionarioId}" class="mskData" size="6" maxlength="7" value="{$mesAno}" type="text" style="text-align: center"></td>
				<td style="text-align: center;">
					<a class="geraFolhaPonto" data-funcId="{$funcionarioId}" style="cursor:pointer; color:#024A68; text-align: center; text-decoration: underline; " title="Baixar PDF da folha">
						<i class="fa fa-cloud-download" aria-hidden="true" style="font-size: 18px;line-height: 18px;"></i>
					</a>
				</td>
			</tr>
_TAG_;

		return $out;

	}
}