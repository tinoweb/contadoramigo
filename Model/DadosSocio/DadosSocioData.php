<?php
/**
 * Autor: Átano de Farias Jacinto.
 * Data: 04/08/2017
 */
require_once('DataBaseMySQL/DadosSocio.php');
require_once('Model/DadosSocio/vo/DadosSocioVo.php');

class DadosSocioData {
	
	// Pega o socio responsavel.
	public function PegaDadosSocioResponsavel($empresaId) {
		
		$object = '';
		
		// Instâmcia da classe responsavel por pegar os dados do contador.
		$dadosSocio = new DadosSocio();
		
		// chama o método para pegar os dados do socio.
		$dados = $dadosSocio->PegaDadosSocioResponsavel($empresaId);
		
		// Verifica se existe os dados do contador.
		if($dados) {
			
			$object = new DadosSocioVo();

			$object->setSocioId($dados['idSocio']);
			$object->setId($dados['id']);
			$object->setDataAdmissao($dados['data_admissao']);
			$object->setResponsavel($dados['responsavel']);
			$object->setNome($dados['nome']);
			$object->setTipo($dados['sexo']);
			$object->setNacionalidade($dados['nacionalidade']);
			$object->setNaturalidade($dados['naturalidade']);
			$object->setEstadoCivil($dados['estado_civil']);
			$object->setProfissao($dados['profissao']);
			$object->setCPF($dados['cpf']);
			$object->setRG($dados['rg']);
			$object->setRNE($dados['rne']);
			$object->setDataEmissao($dados['data_de_emissao']);
			$object->setOrgaoExpeditor($dados['orgao_expeditor']);
			$object->setDataNascimento($dados['data_de_nascimento']);
			$object->setEndereco($dados['endereco']);
			$object->setBairro($dados['bairro']);
			$object->setCEP($dados['cep']);
			$object->setCidade($dados['cidade']);
			$object->setEstado($dados['estado']);
			$object->setPrefTelefone($dados['pref_telefone']);
			$object->setTelefone($dados['telefone']);
			$object->setEnderecoEmpresa($dados['endereco_da_empresa']);
			$object->setCodigoCBO($dados['codigo_cbo']);
			$object->setFuncao($dados['funcao']);
			$object->setRetiraProLabore($dados['retira_pro_labore']);
			$object->setProLabore($dados['pro_labore']);
			$object->setNit($dados['nit']);
			$object->setDependentes($dados['dependentes']);
			$object->setPensao($dados['pensao']);
			$object->setPercPensao($dados['perc_pensao']);
			$object->setStatus($dados['status']);
			$object->setIdBanco($dados['id_banco']);
			$object->setTipoConta($dados['tipo_conta']);
			$object->setAgencia($dados['agencia']);
			$object->setDigAgencia($dados['dig_agencia']);
			$object->setConta($dados['conta']);
			$object->setDigConta($dados['dig_conta']);
			$object->setTipo($dados['tipo']);

		}
		
		return $object;
	}
}