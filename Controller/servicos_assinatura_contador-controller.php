<?php

// Inclui classe responsavel por manipular os objetos.
require_once("Model/DadosServicosAvulso/DadosServicosAvulsoData.php");
require_once('Model/DadosContratoContadorCliente/DadosContratoContadorClienteData.php');

class ServicosAssinaturaContador {
	
	// Método utilizado para paresenta a liste de links dos Boletos.
	public function itensTabela($id_user) {
		
		$cliente = new DadosContratoContadorClienteData(); 
		$dadosCliente = $cliente->GetDataDadosCliente($id_user);
		
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
	
			if($servicosObject) {
				// Verifica os itens da variavel dados.
				foreach($servicosObject as $val) {

					// Verifica se chamada do método é da linha para help desk ou contrato.
					if($val->getHelpDesk() == 'N') {	
						$tagTr .= $this->linhaTable($val->getNome(), $val->getValor(), $val->getTipo(), $data, $id_user, $class, $val->getContratoId());
					} else {
						$tagTr .= $this->linhaHelpDesk($val->getNome());
					}
				}
			}
		
		$tagTr .= '</tbody></table>';//.($dadosCliente->getUF() != 'SP' && $dadosCliente->getUF() != 'MG' ? "<p>serviço não disponível para empresas de comércio</p>": "");
	
		return $tagTr;
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
	
	// Método utilizado para criar as linhas da tabela.
	public function linhaTable($nome, $valor, $tipo, $data, $idUser, $class, $contratoId) {
		
		
		// if($class == 'btAbre') {
			// $link = 'class="' . $class .'" href="servicos_contador_contrato.php?tipo='. $tipo .'&valor='. $valor .'&data='. $data .'&id_user='. $idUser.'&contratoId='. $contratoId .'" ';
		// } else {
			// $link = 	'class="' . $class .'" href="form_dados_usuario.php?tipo='. $tipo .'&valor='. $valor .'&data='. $data .'&id_user='. $idUser.'&contratoId='. $contratoId .'" ';
		// }
		
		// Gera o Link 
		$link = 'class="' . $class .'" href="servico_form_dados_usuario.php?tipo='. $tipo .'&valor='. $valor .'&data='. $data .'&id_user='. $idUser.'&contratoId='. $contratoId .'" ';
		
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
	
	// Método utilizado para paresenta a liste de links dos Boletos.
	public function AjaxValidaDadosCobranca($id_user) {
		
		// instancia dos dado do cliente.
		$cliente = new DadosContratoContadorClienteData(); 
		
		$dadosCliente = $cliente->GetDataDadosCliente($id_user);
		
		$out =  array('status'=>0);
		
		// Verifica se os dados do cliente foram informados. tabela Login e Dados de cobranca.
		if(trim ($dadosCliente->getAssinante()) && trim ($dadosCliente->getEndereco()) && trim ($dadosCliente->getBairro()) && trim ($dadosCliente->getCidade()) && trim ($dadosCliente->getUF()) && trim ($dadosCliente->getNome()) && trim ($dadosCliente->getDocumento()))
		{
			$out = array('status'=>1);
		}
		
		return json_encode($out);
	}	
}