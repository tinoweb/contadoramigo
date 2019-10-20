<?php
/**
 *	Autor: Átano de Farias Jacinto.
 *	Data: 07/07/2017
 */

// Realiza a requisição do arquivo que possui a classe que manipula os dados do funcionario no banco.
require_once('DataBaseMySQL/DadosFuncionarios.php');

// Realiza a requisição do arquivo que possui a classe que manipula os dados do funcionario no banco.
require_once('Model/DadosFuncionarios/vo/DadosFuncionariosVo.php');

// Classe criada para manipular os do funcionário no banco de dados.
class DadosFuncionariosData {
	
	// Método que monta a lista de funcionario em um objeto.
	public function PegaListaObjetoFuncionario($empresaId) {
		
		// Instancia a classe que manipula os dados do funcionário.
		$dadosFuncionarios = new DadosFuncionarios();
		
		// Chama o método que retorna a lista de funcionário.
		$lista = $dadosFuncionarios->PegaListaFuncionarios($empresaId);
		
		$out = false;
		
		// Verifica se existe lista de funcionário.
		if($lista) {
			
			// Percorre arry com os dados do funcionário.
			foreach($lista as $val) {
		
				// Instancia a classe de objeto.
				$object = new DadosFuncionariosVo();

				// Passa os dados do funcionario para o objeto.
				$object->setFuncionarioId($val['idFuncionario']);
				$object->setUserId($val['id']);
				$object->setDataAdmissao($val['data_admissao']);
				$object->setNome($val['nome']);
				$object->setSexo($val['sexo']);
				$object->setNacionalidade($val['nacionalidade']);
				$object->setNaturalidade($val['naturalidade']);
				$object->setEstadoCivil($val['estado_civil']);
				$object->setCodigoCBO($val['codigo_cbo']);
				$object->setCPF($val['cpf']);
				$object->setRG($val['rg']);
				$object->setCTPS($val['ctps']);
				$object->setSerieCTPS($val['serie_ctps']);
				$object->setUfCTPS($val['uf_ctps']);
				$object->setDataEmissao($val['data_de_emissao']);
				$object->setOrgaoExpeditor($val['orgao_expeditor']);
				$object->setDataNascimento($val['data_de_nascimento']);
				$object->setEndereco($val['endereco']);
				$object->setBairro($val['bairro']);
				$object->setCEP($val['cep']);
				$object->setCidade($val['cidade']);
				$object->setEstado($val['estado']);
				$object->setPrefTelefone($val['pref_telefone']);
				$object->setTelefone($val['telefone']);
				$object->setFuncao($val['funcao']);
				$object->setPIS($val['pis']);
				$object->setDependentes($val['dependentes']);
				$object->setPensao($val['pensao']);
				$object->setPercPensao($val['perc_pensao']);
				$object->setValeTransporte($val['vale_transporte']);
				$object->setStatus($val['status']);
				$object->setBancoId($val['id_banco']);
				$object->setTipoConta($val['tipo_conta']);
				$object->setCPF($val['cpf']);
				$object->setDigAgencia($val['dig_agencia']);
				$object->setConta($val['conta']);
				$object->setDigConta($val['dig_conta']);
				$object->setValorSalario($val['valor_salario']);
				$object->setValeRefeicao($val['vale_refeicao']);	
				$object->setValeRefeicaoPorc($val['vale_refeicao_porc']);
				$object->setJornadaTrabalhoDiaria($val['jornada_trabalho_diaria']);
				$object->setInicioJornada($val['inicio_jornada']);
				$object->setFimJornada($val['fim_jornada']);
				$object->setInicioIntervalo($val['inicio_intervalo']);
				$object->setFimIntervalo($val['fim_intervalo']);
				$object->setTrabalhoSabado($val['trabalhoSabado']);
				$object->setInicioHorarioSabado($val['inicio_horario_Sabado']);
				$object->setFimHorarioSabado($val['fim_horario_Sabado']);		

				$out[] = $object;
			}
		}

		// Retorna objeto com os dados do funcionário. 
		return $out;
	}
	
	public function PegaDadosFuncionario($idFuncionario) {
		// Instancia a classe que manipula os dados do funcionário.
		$dadosFuncionarios = new DadosFuncionarios();
		
		// Chama o método que retorna a lista de funcionário.
		$dados = $dadosFuncionarios->PegaFuncionarios($idFuncionario);
		
		$numDepedentes = $dadosFuncionarios->PegaNumeroDepedentes($idFuncionario);
		
		$object = false;
		
		// Verifica se existe lista de funcionário.
		if($dados) {		
			
			// Instancia a classe de objeto.
			$object = new DadosFuncionariosVo();

			// Passa os dados do funcionario para o objeto.
			$object->setFuncionarioId($dados['idFuncionario']);
			$object->setUserId($dados['id']);
			$object->setDataAdmissao($dados['data_admissao']);
			$object->setNome($dados['nome']);
			$object->setSexo($dados['sexo']);
			$object->setNacionalidade($dados['nacionalidade']);
			$object->setNaturalidade($dados['naturalidade']);
			$object->setEstadoCivil($dados['estado_civil']);
			$object->setCodigoCBO($dados['codigo_cbo']);
			$object->setCPF($dados['cpf']);
			$object->setRG($dados['rg']);
			$object->setCTPS($dados['ctps']);
			$object->setSerieCTPS($dados['serie_ctps']);
			$object->setUfCTPS($dados['uf_ctps']);
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
			$object->setFuncao($dados['funcao']);
			$object->setPIS($dados['pis']);
			$object->setDependentes($numDepedentes['dependentes']);
			$object->setPensao($dados['pensao']);
			$object->setPercPensao($dados['perc_pensao']);
			$object->setValeTransporte($dados['vale_transporte']);
			$object->setStatus($dados['status']);
			$object->setBancoId($dados['id_banco']);
			$object->setTipoConta($dados['tipo_conta']);
			$object->setCPF($dados['cpf']);
			$object->setDigAgencia($dados['dig_agencia']);
			$object->setConta($dados['conta']);
			$object->setDigConta($dados['dig_conta']);
			$object->setValorSalario($dados['valor_salario']);
			$object->setValeRefeicao($dados['vale_refeicao']);	
			$object->setValeRefeicaoPorc($dados['vale_refeicao_porc']);	
			$object->setJornadaTrabalhoDiaria($dados['jornada_trabalho_diaria']);
			$object->setInicioJornada($dados['inicio_jornada']);
			$object->setFimJornada($dados['fim_jornada']);
			$object->setInicioIntervalo($dados['inicio_intervalo']);
			$object->setFimIntervalo($dados['fim_intervalo']);	
			$object->setTrabalhoSabado($dados['trabalhoSabado']);
			$object->setInicioHorarioSabado($dados['inicio_horario_Sabado']);
			$object->setFimHorarioSabado($dados['fim_horario_Sabado']);			
			
		}
		
		return $object;
	}
	
	// Pega um lista com os funcionario que receberam salario ferias ou decimo no mes.
	public function DadosFuncRefPagamentoMes($empresaId, $mes, $ano) {
		
		// Instancia a classe que manipula os dados do funcionário.
		$dadosFuncionarios = new DadosFuncionarios();
		
		// Chama o método que retorna a lista de funcionário.
		$lista = $dadosFuncionarios->PegaDadosFuncRefPagamentoMes($empresaId, $mes, $ano);
		
		$out = false;
		
		// Verifica se existe lista de funcionário.
		if($lista) {
			
			// Percorre arry com os dados do funcionário.
			foreach($lista as $val) {
		
				// Instancia a classe de objeto.
				$object = new DadosFuncionariosVo();

				// Passa os dados do funcionario para o objeto.
				$object->setFuncionarioId($val['idFuncionario']);
				$object->setUserId($val['id']);
				$object->setDataAdmissao($val['data_admissao']);
				$object->setNome($val['nome']);
				$object->setSexo($val['sexo']);
				$object->setNacionalidade($val['nacionalidade']);
				$object->setNaturalidade($val['naturalidade']);
				$object->setEstadoCivil($val['estado_civil']);
				$object->setCodigoCBO($val['codigo_cbo']);
				$object->setCPF($val['cpf']);
				$object->setRG($val['rg']);
				$object->setCTPS($val['ctps']);
				$object->setSerieCTPS($val['serie_ctps']);
				$object->setUfCTPS($val['uf_ctps']);
				$object->setDataEmissao($val['data_de_emissao']);
				$object->setOrgaoExpeditor($val['orgao_expeditor']);
				$object->setDataNascimento($val['data_de_nascimento']);
				$object->setEndereco($val['endereco']);
				$object->setBairro($val['bairro']);
				$object->setCEP($val['cep']);
				$object->setCidade($val['cidade']);
				$object->setEstado($val['estado']);
				$object->setPrefTelefone($val['pref_telefone']);
				$object->setTelefone($val['telefone']);
				$object->setFuncao($val['funcao']);
				$object->setPIS($val['pis']);
				$object->setDependentes($val['dependentes']);
				$object->setPensao($val['pensao']);
				$object->setPercPensao($val['perc_pensao']);
				$object->setValeTransporte($val['vale_transporte']);
				$object->setStatus($val['status']);
				$object->setBancoId($val['id_banco']);
				$object->setTipoConta($val['tipo_conta']);
				$object->setCPF($val['cpf']);
				$object->setDigAgencia($val['dig_agencia']);
				$object->setConta($val['conta']);
				$object->setDigConta($val['dig_conta']);
				$object->setValorSalario($val['valor_salario']);
				$object->setValeRefeicao($val['vale_refeicao']);	
				$object->setValeRefeicaoPorc($val['vale_refeicao_porc']);
				$object->setJornadaTrabalhoDiaria($val['jornada_trabalho_diaria']);
				$object->setInicioJornada($val['inicio_jornada']);
				$object->setFimJornada($val['fim_jornada']);
				$object->setInicioIntervalo($val['inicio_intervalo']);
				$object->setFimIntervalo($val['fim_intervalo']);
				$object->setTrabalhoSabado($val['trabalhoSabado']);
				$object->setInicioHorarioSabado($val['inicio_horario_Sabado']);
				$object->setFimHorarioSabado($val['fim_horario_Sabado']);		

				$out[] = $object;
			}
		}

		// Retorna objeto com os dados do funcionário. 
		return $out;
	}
	
	// Pega um lista com os funcionario que receberam salario ferias ou decimo no mes.
	public function DadosFuncRefPagamentoMes13($empresaId, $ano) {
		
		// Instancia a classe que manipula os dados do funcionário.
		$dadosFuncionarios = new DadosFuncionarios();
		
		// Chama o método que retorna a lista de funcionário.
		$lista = $dadosFuncionarios->PegaDadosFuncRefPagamentoMes13($empresaId, $ano);
		
		$out = false;
		
		// Verifica se existe lista de funcionário.
		if($lista) {
			
			// Percorre arry com os dados do funcionário.
			foreach($lista as $val) {
		
				// Instancia a classe de objeto.
				$object = new DadosFuncionariosVo();

				// Passa os dados do funcionario para o objeto.
				$object->setFuncionarioId($val['idFuncionario']);
				$object->setUserId($val['id']);
				$object->setDataAdmissao($val['data_admissao']);
				$object->setNome($val['nome']);
				$object->setSexo($val['sexo']);
				$object->setNacionalidade($val['nacionalidade']);
				$object->setNaturalidade($val['naturalidade']);
				$object->setEstadoCivil($val['estado_civil']);
				$object->setCodigoCBO($val['codigo_cbo']);
				$object->setCPF($val['cpf']);
				$object->setRG($val['rg']);
				$object->setCTPS($val['ctps']);
				$object->setSerieCTPS($val['serie_ctps']);
				$object->setUfCTPS($val['uf_ctps']);
				$object->setDataEmissao($val['data_de_emissao']);
				$object->setOrgaoExpeditor($val['orgao_expeditor']);
				$object->setDataNascimento($val['data_de_nascimento']);
				$object->setEndereco($val['endereco']);
				$object->setBairro($val['bairro']);
				$object->setCEP($val['cep']);
				$object->setCidade($val['cidade']);
				$object->setEstado($val['estado']);
				$object->setPrefTelefone($val['pref_telefone']);
				$object->setTelefone($val['telefone']);
				$object->setFuncao($val['funcao']);
				$object->setPIS($val['pis']);
				$object->setDependentes($val['dependentes']);
				$object->setPensao($val['pensao']);
				$object->setPercPensao($val['perc_pensao']);
				$object->setValeTransporte($val['vale_transporte']);
				$object->setStatus($val['status']);
				$object->setBancoId($val['id_banco']);
				$object->setTipoConta($val['tipo_conta']);
				$object->setCPF($val['cpf']);
				$object->setDigAgencia($val['dig_agencia']);
				$object->setConta($val['conta']);
				$object->setDigConta($val['dig_conta']);
				$object->setValorSalario($val['valor_salario']);
				$object->setValeRefeicao($val['vale_refeicao']);	
				$object->setValeRefeicaoPorc($val['vale_refeicao_porc']);
				$object->setJornadaTrabalhoDiaria($val['jornada_trabalho_diaria']);
				$object->setInicioJornada($val['inicio_jornada']);
				$object->setFimJornada($val['fim_jornada']);
				$object->setInicioIntervalo($val['inicio_intervalo']);
				$object->setFimIntervalo($val['fim_intervalo']);
				$object->setTrabalhoSabado($val['trabalhoSabado']);
				$object->setInicioHorarioSabado($val['inicio_horario_Sabado']);
				$object->setFimHorarioSabado($val['fim_horario_Sabado']);		

				$out[] = $object;
			}
		}

		// Retorna objeto com os dados do funcionário. 
		return $out;
	}
}
