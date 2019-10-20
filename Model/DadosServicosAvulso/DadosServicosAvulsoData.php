<?php
/**
 * autor: Átano de Farias Jacinto
 * Date: 23/06/2017
 */

// Far a requisição do arquivo responsavel por pegar os dados do serviço avulso do banco.
require_once("DataBaseMySQL/DadosServicosAvulso.php");

// Faz a requisição do arquivo de objectos.
require_once("Model/DadosServicosAvulso/vo/DadosServicosAvulsoVo.php");

// Classe Responsavel por manipular os dados do serviço avulso.
class DadosServicosAvulsoData {
	
	
	// Método criado recupera os dados do serviço avulso.
	public function PegaObjetoComServicos(){
	
		// variável de retorno.
		$out = false;
		
		// Instancia a classe que manipula os dados.
		$dadosServicosAvulso = new DadosServicosAvulso();
		
		// Pega os dados do serviço.
		$dadosServico = $dadosServicosAvulso->PegaTodasServicos();
		
		// Verifica se existe dados
		if($dadosServico) {
			
			// Percorre o array e repassa para o object.
			foreach($dadosServico as $val){
				$object = new DadosServicosAvulsoVo();

				$object->setServicoId($val['servicoId']);
				$object->setNome($val['nome']);
				$object->setNomeResumido($val['nomeResumido']);
				$object->setValor($val['valor']);
				$object->setTipo($val['tipo']);
				$object->setContratoId($val['contratoId']);
				$object->setHelpDesk($val['helpDesk']);

				$out[] = $object;
			}
		}
		
		// Retorna o objeto.
		return $out;
	}
	
	
// Método criado recupera os dados do serviço avulso.
	public function PegaNomeServicosTipo($tipo){
	
		// variável de retorno.
		$out = false;
		
		// Instancia a classe que manipula os dados.
		$dadosServicosAvulso = new DadosServicosAvulso();
		
		// Pega os dados do serviço.
		$dadosServico = $dadosServicosAvulso->PegaServicosPorTipo($tipo);
		
		// Verifica se existe dados
		if($dadosServico) {

			$object = new DadosServicosAvulsoVo();

			$object->setServicoId($dadosServico['servicoId']);
			$object->setNome($dadosServico['nome']);

			$out = $object;
		}
		
		// Retorna o objeto.
		return $out;
	}	
	
	
	
}