<?php
/**
 * Classe baseada no aquivo d e layour da SEFIP_84.
 * link: http://suporte.quarta.com.br/LayOuts/Governo/SEFIP_84%20(Layout%20Folha).pdf
 * autor: Átano de Farias Jacinto
 * data: 29/11/2017
 */

//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);

// Realiza a inclusão do arquivo de conexão com o banco.
require_once ('conect.php');

// Realiza a requisição do arquivo que retorna o objeto com os dados do funcionário.
require_once('Model/DadosFuncionarios/DadosFuncionariosData.php');

// Realiza a requisição do arquivo que retorna o objeto com os dados de pagamento do funcionário.
require_once('Model/PagamentoFuncionario/PagamentoFuncionarioData.php');

// Realiza a requisição do arquivo que retorna os dados da empresa.
require_once('Model/DadosEmpresa/DadosEmpresaData.php');

// Realiza a requisição do arquivo que retorna dados do contador.
require_once('Model/DadosContador/DadosContadorData.php');

// Realiza a requisição do arquivo que retorna dados do Socio.
require_once('Model/DadosSocio/DadosSocioData.php');

// Realiza a requisição do arquivo que pega os dados para montar a sefip.
require_once('DataBaseMySQL/DadosSefipFolha.php');

class SefipFolha {
	
	private $tipo_de_registro;
	private $brancos;
	private $tipo_de_remessa;
	private $tipo_de_inscricao;
	private $inscricao_responsavel;
	private $nome_responsavel;
	private $nome_pessoa_responsavel;
	private $logradouro_responsavel;
	private $bairro_responsavel;
	private $cep_responsavel;
	private $cidade_responsavel;
	private $uf_responsavel;
	private $telefone_responsavel;
	private $linhas_tel_responsavel;
	private $end_internet;
	private $competencia;
	private $codigo_recolhimento;
	private $indicador_FGTS;
	private $modalidade_arquivo;
	private $recolhimento_FGTS;
	private $indicador_previdencia;
	private $recolhimento_previdencia;
	private $indice_recolhimento;
	private $tipo_inscricao_fornecedor; 
	private $inscricao_fornecedor_responsavel;
	private $brancos2;
	private $final_linha;
	private $nome_empresa;
	private $tipo_de_registro_empresa;
	private $tipo_inscricao_empresa;
	private $inscricao_empresa;
	private $zeros36;
	private $inscricao;
	private $nome;
	private $nome_pessoa;
	private $logradouro;
	private $bairro;
	private $cep;
	private $cidade;
	private $uf;
	private $telefone;
	private $alteracao_endereco;
	private $cnae;
	private $alteracao_cnae;
	private $centralizacao;
	private $codigo_centralizacao;
	private $fpas;
	private $filantropia;
	private $salario_familia;
	private $salario_maternidade;
	private $contribuicao_desc_empregado;
	private $indicador_neg_pos;
	private $valor_devido_previdencia;
	private $banco;
	private $agencia;
	private $conta_corrente;
	private $zeros45;
	private $brancos4;
	private $tipo_de_registro_adicional;
	private $deducao_sal_maternidade;
	private $receita_esportivo;
	private $origem_receita;
	private $codigo_outras_entidades;
	private $comercializacao_producao;
	private $comercializacao_producao2;
	private $outras_informacoes_processo;
	private $outras_informacoes_ano;
	private $outras_informacoes_vara;
	private $outras_informacoes_inicio;
	private $outras_informacoes_fim;
	private $compensacao;
	private $compensacao_inicio;
	private $compensacao_fim;
	private $comp_anteriores;
	private $comp_anteriores_outras;
	private $comp_anteriores_comercializacao;
	private $comp_anteriores_com_outras;
	private $comp_anteriores_esportivo;
	private $parcelamento_fgts_somatorio;
	private $parcelamento_fgts_somatorio2;
	private $parcelamento_fgts_valor;
	private $cooperativas;
	private $brancos6;
	private $ids_pagamentos;
	private $houver_compensacao;
	private $cada_tomador;
	private $tipo_de_registro_tomador_servico;
	private $inscricao_tomador_servico;
	private $zeros21;
	private $empresa_tomador_servico;
	private $longadouro_tomador_servico;
	private $bairro_tomador_servico;
	private $cep_tomador_servico;
	private $cidade_tomador_servico;
	private $uf_tomador_servico;
	private $codigo_pagamento_gps_tomador_servico;
	private $salario_familia_tomador_servico;
	private $contrib_empregado_tomador_servico;
	private $indicador_de_valor_tomador_servico;
	private $valor_devido_a_previdencia_tomador_servico;
	private $valor_retencao_tomador_servico;
	private $valor_das_faturas_tomador_servico;
	private $brancos42;
	private $compensacao21;
	private $compensacao21Inicio;
	private $compensacao21Fim;
	private $infoSocio;
	private $tipo_de_registro_trabalhador;
	private $tipo_inscricao_tomador;
	private $inscricao_tomador;
	private $categoria_trabalhador;
	private $matricula_empregado;
	private $ctps;
	private $ctps_serie;
	private $data_opcao;
	private $remuneracao13;
	private $remuneracao13_cap16;
	private $remuneracao13_cap17;
	private $classe_contribuicao;
	private $ocorrencia;
	private $valor_descontado;
	private $remuneracao_base;
	private $base_claculo_13_comp;
	private $base_claculo_13_13;
	private $brancos98;
	private $tipo_de_registro_totalizador;
	private $marca_final_registro;
	private $brancos306;
	private $simples;
	private $codigo_gps;
	private $rat;
	private $cnae_principal_da_empresa;
	private $anexo_cnae_principal;
	private $codigo_cbo;
	private $PIS_PASEP_CI;
	private $data_admissao;
	private $nome_Trabalhador;
	private $data_de_Nascimento;
	private $TipoArquivo;
	private $InforcaoTomador;
	private $infoResp;
	private $infoEmp;
	private $infoAdicional;
	private $funcionario;
	private $infoTomador;
	private $socios;
	private $totalizador;
	private $compensacao_cprb;	
		
	// Método criado para gera a tabela 
	public function PegaDadosPagamentoFuncionario($empresaId, $mes, $ano) {
		
		// Pega a lista de pagamentos do funcionário.
		$pagamentoFuncionario = new PagamentoFuncionarioData();
		
		// Verifica se é a competência 13
		if($mes == 13) {
			$pagamentoLista = $pagamentoFuncionario->PegaListaPagamentoParaGefip13($empresaId, $ano);
		} else {
			$pagamentoLista = $pagamentoFuncionario->PegaListaPagamentoParaGefip($empresaId, $mes, $ano);
		}
		
		// Retorna os dados do pagamento do funcionario.
		return $pagamentoLista;
	}
	
	// Método criador para p tomador + trabalhadores INICIO
	private function getData($ano,$mes,$tipo){
		if( $tipo == 'd' ){
			if( $mes - 1 == 0 )
				return '12';
			else
				return ($mes-1);
		}
		else{
			if( $mes - 1 == 0 )
				return ($ano-1);
			else
				return $ano;
		}
	}
	
	// Método criado para regularizar os caracteres especiais Funcão de limpeza dos acentos e caracteres especiais
	private function Limpeza($pegaNome, $pegaNumero = 0) {	
		
		// Array com os caracteres especiais.
		$trans = array(
			"á" => "a", 
			"à" => "a",
			"ã" => "a",
			"â" => "a",
			"é" => "e",
			"ê" => "e",
			"í" => "i",
			"ó" => "o",
			"ô" => "o",
			"õ" => "o",
			"ú" => "u",
			"ü" => "u",
			"ç" => "c",
			"Á" => "A",
			"À" => "A",
			"Ã" => "A",
			"Â" => "A",
			"É" => "E",
			"Ê" => "E",
			"Í" => "I",
			"Ó" => "O",
			"Ô" => "O",
			"Õ" => "O",
			"Ú" => "U",
			"Ü" => "U",
			"Ç" => "C",
		);

		// antes de permite que apenes letras e numeros seja passado para variavel substitui os caracters especiais da lista acima.
		//$pegaNome = ereg_replace("[^a-zA-Z0-9 ]", "", strtr($pegaNome, $trans));
		$pegaNome = preg_replace('/[^a-zA-Z0-9 ]/', "", strtr($pegaNome, $trans));
		
		// substitui espaço duplo por espaço unico. 
		$pegaNome = str_replace("  ", " ", $pegaNome);
		
		// Passa todas letras para maiúscula.
		$pegaNome = strtoupper($pegaNome);
		
		// Pega a quantidade de caracteres.
		$quantidade = strlen($pegaNome);
		
		$retorno = $pegaNome;

		// Verifica se a quantidade de caracters e menor que a quantidade de numero informado,
		//caso seja menor inclui espaço vazio.
		if ($quantidade < $pegaNumero) {
			for ($i=$quantidade; $i<$pegaNumero; $i++) {
				$retorno .= ' ';
			}
		}

		// Verifica se a quantidade de caracteres e maior que o numero informado, 
		//ele remover o que passar e retorna a quantidade solicitada. 
		if ($quantidade > $pegaNumero) {

			$retorno = substr($retorno, 0, $pegaNumero);
		}
		
		// Retorno de dados.
		return $retorno;
	}
	
	// Método criado para normalizar o cnae.
	private function NormalizaCNAE($cnae) {
		
		$out = $cnae;
		
		switch($out){
			case '6201501':
				$out = '6201500';
			break;
			case '8020001':
				$out = '8020000';
			break;
			case '2013401':
			case '2013402':
				$out = '2013400';
			break;
			case '5239799':
				$out = '5239700';
			break;
			case '5812301':
			case '5812302':
				$out = '5812300';
			break;
			case '5822101':
			case '5822102':
				$out = '5822100';
			break;
			case '8020001':
			case '8020002':
				$out = '8020000';
			break;
			case '9412001':
			case '9412099':
				$out = '9412000';
			break;	
			case '1822999':
				$out = '1822900';
			break;
			case '4751201':
				$out = '4751200';
			break;

			case '9609208':
				$out = '9609203';
			break;

			case '1822999':
				$out = '1822900';
			break;
		}		
		
		// Retorna o CNAE
		return $out;
	}
	
	/**
	 * Método criado para gera o arquivo sefip.re.
	 * tipo do arquivo: 
	 *	1 - Funcionario.
	 *  2 - socio e autonomo.
	 */
	public function GeraArquivoSefip($empresaId, $userId, $mes, $ano, $tipoArquivo) {
		
		// Define o nome e o caminho do diretorio de acordo com o código da empresa.  
		$nome_pasta = './sefip/' . str_pad($empresaId, 6, "0", STR_PAD_LEFT);
		
		// Verifica se o qrquivo existe caso exista apaga.
		if (file_exists($nome_pasta . '/sefip.re')) {
			unlink($nome_pasta . '/sefip.re');
		}
		
		// Se o diretorio não existir cria ele.
		if(!is_dir($nome_pasta)){
			mkdir($nome_pasta, 0777, true);
		}
		
		// Pega o tipo do arquivo.
		$this->TipoArquivo = $tipoArquivo;		
				
		// Chama o método responsavel por gera o conteudo para o arquivo.
		$conteudo = $this->PegaConteudoSefip($empresaId, $userId, $mes, $ano);
		
		// Salva o arquivo com o conteudo da gefip.
		file_put_contents($nome_pasta . '/sefip.re', $conteudo);
		
		// Verifica se o arquivo foi criado. 
		if (file_exists($nome_pasta . '/sefip.re')) {
			echo json_encode(array('status'=>'OK'));
		} else {		
			echo json_encode(array('status'=>'Erro'));
		}
	}
	
	// Método criado para montar o conteudo para o arquivo da sefip.re
	private function PegaConteudoSefip($empresaId, $userId, $mes, $ano) {
		
		// variavel de retorno
		$out = "";
		
		/*
		* DETERMINANDO AS VARIAVEIS 
		* codigo de recolhimento, optante pelo simples, codigo GPS e rat 
		* Estas dependem dos anexos das atividades selecionadas e se a empresa está ou não vinculada a uma empreitada
		* conforme regras abaixo
		* 
		*
		* Anexo IV + III sem empreitada
		*   codigo de recolhimento: 115
		*   código pagamento: 2003
		*   rat: 0
		*   Optante pelo Simples
		* 
		* 
		* Anexo IV apenas, sem empreitada
		*   codigo de recolhimento: 115
		*   código pagamento: 2100
		*   rat: 3,0
		*   Não Optante
		* 
		* 
		* Anexo IV + III COM empreitada
		*   codigo de recolhimento: 150
		*   código pagamento: 2003
		*   rat: 0
		*   Optante pelo Simples
		* 
		* 
		* Anexo IV apenas, COM empreitada
		*   codigo de recolhimento: 150
		*   código pagamento: 2100
		*   rat: 3,0
		*   Não Optante
		*
		*/
		
		// Instância da classe DadosSefipFolha 
		$dadosSefipFolha = new DadosSefipFolha();
		
		// Pega cnae principal da empresa.
		$linha_codigo = $dadosSefipFolha->PegaCNAEPrincipalDaEmpresa($empresaId);
		$this->cnae_principal_da_empresa = $linha_codigo['cnae'];
		
		$this->codigo_recolhimento = 115;
		$this->simples = '2';
		$this->codigo_gps = '2003';
		$this->rat = '00';
		
		$arrAnexos = explode(',',$_SESSION['SEFIP_arrAnexos']);		

		if( in_array('IV',$arrAnexos) && count($arrAnexos) > 1 ) {
			
			/*Anexo IV + III SEM empreitada*/
			if($_SESSION['SEFIP_empreitada'] == 'n'){ // não realiza empreitada
				$this->codigo_recolhimento = 115;
				$this->simples = '2';
				$this->codigo_gps = '2003';
				$this->rat = '00';
				
			/*Anexo IV + III COM empreitada*/
			}elseif($_SESSION['SEFIP_empreitada'] == 's'){ // não realiza empreitada
				$this->codigo_recolhimento = 150;
				$this->simples = '2';
				$this->codigo_gps = '2003';
				$this->rat = '00';
			}

		} elseif( in_array('IV',$arrAnexos) && count($arrAnexos) == 1 ) {
			
			/*Anexo IV SEM empreitada*/
			if($_SESSION['SEFIP_empreitada'] == 'n'){ // não realiza empreitada
				$this->codigo_recolhimento = 115;
				$this->simples = '1';
				$this->codigo_gps = '2100';
				// Pega alíquota RAT.
				$linha_cnae_rat = $dadosSefipFolha->PegaRAT($this->cnae_principal_da_empresa);
				$this->rat = $linha_cnae_rat['rat'].'0';

			/*Anexo IV COM empreitada*/
			}elseif($_SESSION['SEFIP_empreitada'] == 's'){ // não realiza empreitada
				$this->codigo_recolhimento = 150;
				$this->simples = '1';
				$this->codigo_gps = '2100';
				// Pega alíquota RAT.
				$linha_cnae_rat = $dadosSefipFolha->PegaRAT($this->cnae_principal_da_empresa);
				$this->rat = $linha_cnae_rat['rat'].'0';
			}

		}

		if( $_SESSION['e_empreitada'] == 'false' && $_SESSION['recolhe_cprb'] == 'true' ){
			$this->codigo_gps = '2100';
			$this->simples = '1';
		}
		if( $_SESSION['e_empreitada'] == 'false' && $_SESSION['recolhe_cprb'] == 'false' ){
			$this->codigo_gps = '2100';
			$this->simples = '1';
		}
		if( $_SESSION['e_empreitada'] == 'false' && $_SESSION['anexo_s'] == 'true' ){
			$this->codigo_gps = '2003';
			$this->simples = '2';
		}

		//Se nao for empreitada o codigo e 115 - MAL
		if( $_SESSION['e_empreitada'] == 'false'  ){
			$this->codigo_recolhimento = 115;
		}
		//FIM

		$this->codigo_outras_entidades = $this->Limpeza('',4);

		// SE NÃO FOR OPTANTE
		if($this->simples == '1'){
			$this->codigo_outras_entidades = '0000';
		}
	
		// Pega o redistro do tipo 00 – Informações do Responsável(Header do arquivo).
		$this->infoResp = $this->InformacoesDoResponsavel($empresaId, $userId, $mes, $ano);
		
		// Pega o redistro do tipo 10 – Informações da Empres.
		$this->infoEmp = $this->InformacoesDaEmpresa($empresaId, $mes, $ano);
		
		// Pega o redistro do tipo 12 – Informações Adicionais do Recolhimento da Empresa.
		$this->infoAdicional = $this->InfoAdicionaisRecolhimentoEmpresa($mes, $ano);
		
		// Verifica se devera ser apresentado o arquivo da sefip com os dados do funcionario ou os dados do socio e autonomos.
		if($this->TipoArquivo == 1) {
			
			// Pega as Linhas de dados do funcionario.
			$this->funcionario = $this->RegraParaFuncionarios($empresaId, $mes, $ano);
			
		} else {
			
			// Faz a soma da compensação - MAL
			$this->compensacao_cprb = 0;

			// Busca todos os pagamentos no periodo informado
			$consulta_pagamentos = $dadosSefipFolha->BuscaTodosPagtosPeriodoInformado($empresaId, $mes, $ano);
			
			// Verifica se houve retorno dos pagamentos. 
			if($consulta_pagamentos) {

				foreach($consulta_pagamentos as $objeto) {	

					//Soma cada pagamento multiplicado pela porcentagem de acordo com o tipo de trabalhador
					if( $objeto['id_autonomo'] != 0 ) {
						$this->compensacao_cprb = floatval($this->compensacao_cprb) + floatval(($objeto['valor_bruto']) * (( 20 )/100));
					}
					else if( $objeto['id_socio'] != 0 ) {
						$this->compensacao_cprb = floatval($this->compensacao_cprb) + floatval(($objeto['valor_bruto']) * (( 20 )/100));
					}
				}

				if( $_SESSION['e_empreitada'] === 'false' ) {
					$_SESSION['compensacao'] = number_format($this->compensacao_cprb,'2',',','');
				}

				//Coloca no forma inteiro sem virgulas
				$this->compensacao_cprb = $this->compensacao_cprb * 100;
			}			
			
			if($this->codigo_recolhimento == '150') { 
				
				// Guarda a informações se deve ser declarada ou não a compensação
				$this->houver_compensacao = true;

				// Verifica se houve retenção
				if( isset( $_SESSION['SEFIP_retencao'] ) ):
				
					// Pega a linha de autônomo e sócio
					$this->RegraParaDefinirDadosSocioAutono($empresaId);
				
				else:

					// Pega os dados da empresa.
					$empresaData = new DadosEmpresaData();
					$dadosEmpregado = $empresaData->GetDataDadosEmpresa($empresaId);
				
					//$this->tipo_de_registro_tomador_servico = "20";
					$this->inscricao_tomador_servico = $this->Limpeza($dadosEmpregado->getCNPJ(),14);
					$this->empresa_tomador_servico = $this->Limpeza($dadosEmpregado->getRazaoSocial(),40);
					$this->longadouro_tomador_servico = $this->Limpeza($dadosEmpregado->getEndereco(),50);
					$this->bairro_tomador_servico = $this->Limpeza($dadosEmpregado->getBairro(),20);
					$this->cep_tomador_servico = $this->Limpeza($dadosEmpregado->getCEP(),8);
					$this->cidade_tomador_servico = $this->Limpeza($dadosEmpregado->getCidade(),20);
					$this->uf_tomador_servico = $this->Limpeza($dadosEmpregado->getEstado(),2);
					$this->codigo_pagamento_gps_tomador_servico = $this->Limpeza('',4);
					$valor_retencao_tomador_servico = '000000000000000';

				endif;
			} else {
				
				// Pega os dados do sócio
				$this->socios .= $this->PegaRegistroDosSocios($empresaId, $mes, $ano);
			}
			
			if($this->codigo_recolhimento == '155') {
				
				// Pega os dados da empresa.
				$empresaData = new DadosEmpresaData();
				$dadosEmpregado = $empresaData->GetDataDadosEmpresa($empresaId);
	
				//$this->tipo_de_registro_tomador_servico = "20";
				$this->inscricao_tomador_servico = $this->Limpeza($dadosEmpregado->getCNPJ(),14);
				$this->empresa_tomador_servico = $this->Limpeza($dadosEmpregado->getRazaoSocial(),40);
				$this->longadouro_tomador_servico = $this->Limpeza($dadosEmpregado->getEndereco(),50);
				$this->bairro_tomador_servico = $this->Limpeza($dadosEmpregado->getBairro(),20);
				$this->cep_tomador_servico = $this->Limpeza($dadosEmpregado->getCEP(),8);
				$this->cidade_tomador_servico = $this->Limpeza($dadosEmpregado->getCidade(),20);
				$this->uf_tomador_servico = $this->Limpeza($dadosEmpregado->getEstado(),2);
				$this->codigo_pagamento_gps_tomador_servico = '2003';
				$this->valor_retencao_tomador_servico = '000000000000000';
			}
		}
		
		if($this->codigo_recolhimento == '150') {

			if( !isset( $_SESSION['SEFIP_retencao'] ) ) {
			
				// Pega o redistro do tipo 20 – Registro do Tomador de Serviço/Obra de Construção Civil
				$this->infoTomador .= $this->RegistroDoTomador(); 
			}
		}
		
		// Pega o redistro do tipo 90 – Registro Totalizador do Arquivo.
		$this->totalizador = $this->RegistroTotalizadorDoArquivo();
		
		//
		if($this->codigo_recolhimento == '150') {
			$out =  $this->infoResp.$this->infoEmp.$this->infoAdicional.$this->infoTomador.$this->totalizador;
		} else {
			$out =  $this->infoResp.$this->infoEmp.$this->infoAdicional.$this->funcionario.$this->infoTomador.$this->socios.$this->totalizador;
		}
		
		// Retorna as linahs unidas.
		return $out;
	}
		
	// Método criado para pegar os dados do responsavel.
	private function PegaDadosResponsavel($empresaId, $userId) {
	
		// Instância da classe que manipula os dodos do contador.
		$dadosContadorData = new DadosContadorData();
				
		// Chama o metodo para verificar se o cliente e um assinante do premium para pegar os dados do contador.
		$dadosContador = $dadosContadorData->PegaContadorDeAcordoCliente($userId);
		
		// Verifica se existe dados.	
		if($dadosContador) {

			// 5 - Inscrição do Responsável posição inicial(56) Final(69) quantidade(14).
			$this->inscricao_responsavel = $this->Limpeza($dadosContador->getDocumento(),14);

			// 6 - Nome Responsável (Razão Social) posição inicial(70) Final(99) quantidade(30).  
			$this->nome_responsavel = $this->Limpeza($dadosContador->getRazaoSocial(),30);

			// 7 - Nome Pessoa Contato posição inicial(100) Final(119) quantidade(20).
			$this->nome_pessoa_responsavel = $this->Limpeza($dadosContador->getNome(),20);

			// 8 - Logradouro, rua, nº, andar, apartamento 	posição inicial(120) Final(169) quantidade(50).	
			$this->logradouro_responsavel =  $this->Limpeza($dadosContador->getEndereco(),50);			

			// 9 - Bairro posição inicial(170) Final(189) quantidade(20).
			$this->bairro_responsavel =  $this->Limpeza($dadosContador->getBairro(),20);

			// 10 - CEP posição inicial(190) Final(197) quantidade(8). 
			$this->cep_responsavel = $this->Limpeza($dadosContador->getCEP(),8);

			// 11 -	Cidade posição inicial(198) Final(217) quantidade(20).
			$this->cidade_responsavel = $this->Limpeza($dadosContador->getCidade(),20);

			// 12 - Unidade da Federação posição inicial(218) Final(219) quantidade(2).
			$this->uf_responsavel = $this->Limpeza($dadosContador->getUF(),2);
			
			// Pega o prefixo com o telefone do responsável.	
			$this->telefone_responsavel = preg_replace("/\W+/","",$dadosContador->getPrefTelefone()).preg_replace("/\W+/","",$dadosContador->getTelefone());
			
			// Pega a quantidade de linhas do telefone.	
			$this->linhas_tel_responsavel = strlen($this->telefone_responsavel);

			// Verifica se a quantidade de caracters do numero esta correta. se não estiver normaliza.
			if ($this->linhas_tel_responsavel < 12) {
				for ($i=$this->linhas_tel_responsavel; $i<12; $i++) {
									
					// 13 - Telefone Contato posição inicial(220) Final(231) quantidade(12).
					$this->telefone_responsavel = '0'.$this->telefone_responsavel;
				}
			};

			// 14 - Endereço internet contato posição inicial(232) Final(291) quantidade(60).
			$this->end_internet = str_pad($dadosContador->getEmail(), 60);

			// 24 - Inscrição do Fornecedor Folha de Pagamento 328 341 14 posição inicial(328) Final(341) quantidade(14).
			$this->inscricao_fornecedor_responsavel = $this->Limpeza($dadosContador->getDocumento(),14);

		} else {	

			// Pega os dados da empresa.
			$empresaData = new DadosEmpresaData();
			$dadosEmpregado = $empresaData->GetDataDadosEmpresa($empresaId);
				
			// Pega os dados do socio.
			$socioData = new DadosSocioData();
			$dadosSocio = $socioData->PegaDadosSocioResponsavel($empresaId);

			// 5 - Inscrição do Responsável posição inicial(56) Final(69) quantidade(14).
			$this->inscricao_responsavel = $this->Limpeza($dadosEmpregado->getCNPJ(),14);

			// 6 - Nome Responsável (Razão Social) posição inicial(70) Final(99) quantidade(30).  
			$this->nome_responsavel = $this->Limpeza($dadosEmpregado->getRazaoSocial(),30);

			// 7 - Nome Pessoa Contato posição inicial(100) Final(119) quantidade(20).
			$this->nome_pessoa_responsavel = $this->Limpeza($dadosSocio->getNome(),20);

			// 8 - Logradouro, rua, nº, andar, apartamento 	posição inicial(120) Final(169) quantidade(50).	
			$this->logradouro_responsavel =  $this->Limpeza($dadosEmpregado->getEndereco(),50);			

			// 9 - Bairro posição inicial(170) Final(189) quantidade(20).
			$this->bairro_responsavel =  $this->Limpeza($dadosEmpregado->getBairro(),20);

			// 10 - CEP posição inicial(190) Final(197) quantidade(8). 
			$this->cep_responsavel = $this->Limpeza($dadosEmpregado->getCEP(),8);

			// 11 -	Cidade posição inicial(198) Final(217) quantidade(20).
			$this->cidade_responsavel = $this->Limpeza($dadosEmpregado->getCidade(),20);

			// 12 - Unidade da Federação posição inicial(218) Final(219) quantidade(2).
			$this->uf_responsavel = $this->Limpeza($dadosEmpregado->getEstado(),2);

			// Pega o prefixo com o telefone do responsável.	
			$this->telefone_responsavel = preg_replace("/\W+/","",$dadosEmpregado->getPrefTelefone()).preg_replace("/\W+/","",$dadosEmpregado->getTelefone());

			// Pega a quantidade de linhas do telefone.	
			$this->linhas_tel_responsavel = strlen($this->telefone_responsavel);

			// Verifica se a quantidade de caracters do numero esta correta. se não estiver normaliza.
			if ($this->linhas_tel_responsavel < 12) {
				for ($i=$this->linhas_tel_responsavel; $i<12; $i++) {
									
					// 13 - Telefone Contato posição inicial(220) Final(231) quantidade(12).
					$this->telefone_responsavel = '0'.$this->telefone_responsavel;
				}
			};

			// 14 - Endereço internet contato posição inicial(232) Final(291) quantidade(60).
			$this->end_internet = str_pad('', 60);

			// 24 - Inscrição do Fornecedor Folha de Pagamento 328 341 14 posição inicial(328) Final(341) quantidade(14).
			$this->inscricao_fornecedor_responsavel = $this->Limpeza($dadosEmpregado->getCNPJ(),14);			

		}
	}
	
	// Método utilizado para montar o redistro do tipo 00 – Informações do Responsável(Header do arquivo)
	private function InformacoesDoResponsavel($empresaId, $userId, $mes, $ano){
			
		// Verifica os dados do responsavel
		$this->PegaDadosResponsavel($empresaId, $userId);
		
		// Define a variavel de retorno vazia.
		$out = '';

		// 1 - Tipo de registro posição inicial (1) final(2) quantidade(2). 
		$this->tipo_de_registro = '00';
		
		// 2 - Brancos posição inicial(3) final(53) quantidade(51). 
		$this->brancos = $this->Limpeza('',51);
		
		// 3 - Tipo de remessa posição inicial(54) final(54) quantidade(1).
		$this->tipo_de_remessa = 1;
		
		// 4 - Tipo de inscricao posição inicial(55) Final(55) quantidade(1).
		$this->tipo_de_inscricao = 1;

		// 5 - Inscrição do Responsável posição inicial(56) Final(69) quantidade(14).
		// $this->inscricao_responsavel;

		// 6 - Nome Responsável (Razão Social) posição inicial(70) Final(99) quantidade(30).  
		// $this->nome_responsavel;

		// 7 - Nome Pessoa Contato posição inicial(100) Final(119) quantidade(20).
		// $this->nome_pessoa_responsavel;

		// 8 - Logradouro, rua, nº, andar, apartamento 	posição inicial(120) Final(169) quantidade(50).	
		// $this->logradouro_responsavel;		

		// 9 - Bairro posição inicial(170) Final(189) quantidade(20).
		// $this->bairro_responsavel;

		// 10 - CEP posição inicial(190) Final(197) quantidade(8). 
		// $this->cep_responsavel;

		// 11 -	Cidade posição inicial(198) Final(217) quantidade(20).
		// $this->cidade_responsavel;

		// 12 - Unidade da Federação posição inicial(218) Final(219) quantidade(2).
		// $this->uf_responsavel;

		// 13 - Telefone Contato posição inicial(220) Final(231) quantidade(12).
		// $this->telefone_responsavel;

		// 14 - Endereço internet contato posição inicial(232) Final(291) quantidade(60).
		// $this->end_internet;		
		
		// 15 - Competência posição inicial(292) Final(297) quantidade(6).
		$this->competencia = $ano.str_pad($mes, 2, "0", STR_PAD_LEFT);
		
		// 16 - Código de Recolhimento posição inicial(298) Final(300) quantidade(3).
		// $this->codigo_recolhimento;
		
		// Verifica se e competência 13
		if($mes == 13) {
			
			// 17 - Indicador de Recolhimento posição inicial(301) Final(301) quantidade(1).
			$this->indicador_FGTS = ' ';
		} else {
		
			// 17 - Indicador de Recolhimento posição inicial(301) Final(301) quantidade(1).
			$this->indicador_FGTS = '1';			
		}			
			
		// Verifica como sera definida a modalidade do arquivo. O tipo de arquivo 1 = funcionario. 2 socio e autonomo
		// Branco – Recolhimento ao FGTS e Declaração à Previdência 
		// 1 - Declaração ao FGTS e à Previdência 
		// 9 - Confirmação Informações anteriores – Rec/Decl ao FGTS e Decl à Previdência
		if($this->TipoArquivo == 1 && $mes != 13){
			
			// 18 - Modalidade do arquivo posição inicial(302) Final(302) quantidade(1).
			$this->modalidade_arquivo = ' ';
		} else {
			
			// 18 - Modalidade do arquivo posição inicial(302) Final(302) quantidade(1).
			$this->modalidade_arquivo = '1';
		}
		
		// 19 - Data de Recolhimento do FGTS posição inicial(303) Final(310) quantidade(1).
		$this->recolhimento_FGTS = $this->Limpeza('',8);
		
		// 20 - Indicador de Recolhimento Previdência Social posição inicial(311) Final(311) quantidade(1).
		$this->indicador_previdencia = '1';
		
		// Verifica o indicador de recolhimento do FGTS. e ve a data do atraso.
		if ($this->indicador_previdencia == '1') {
			
			// 21 - Recolhimento previdencia posição inicial(312) Final(319) quantidade(8).
			$this->recolhimento_previdencia = $this->Limpeza('',8);
		} else {
			
			// 21 - Recolhimento previdencia posição inicial(312) Final(319) quantidade(8).
			$this->recolhimento_previdencia = $this->Limpeza($_POST['data_atraso'],8);
		}

		// 22 - Indice recolhimento posição inicial(320) Final(326) quantidade(7).
		$this->indice_recolhimento = $this->Limpeza('',7);
		
		
		// 23 - Tipo inscricao fornecedor posição inicial(327) Final(327) quantidade(1).
		$this->tipo_inscricao_fornecedor = '1';
		
		// 24 - Inscrição do Fornecedor Folha de Pagamento 328 341 14 posição inicial(328) Final(341) quantidade(14).
		// $this->inscricao_fornecedor_responsavel;

		// 25 - Brancos posição inicial(342) final(359) quantidade(18) campo obrigatório preencher com brancos.
		$this->brancos2 = $this->Limpeza(' ',18);
		
		// 26 - Final de Linha posição inicial(360) final(360) quantidade(1) deve ser uma constante “*” para marcar fim de linha.
		$this->final_linha = '*';		
		
		// Monta a linha de responsável.
		$out = $this->tipo_de_registro . $this->brancos . $this->tipo_de_remessa . $this->tipo_de_inscricao . $this->inscricao_responsavel . $this->nome_responsavel . $this->nome_pessoa_responsavel . $this->logradouro_responsavel . $this->bairro_responsavel . $this->cep_responsavel . $this->cidade_responsavel . $this->uf_responsavel . $this->telefone_responsavel . $this->end_internet . $this->competencia . $this->codigo_recolhimento . $this->indicador_FGTS . $this->modalidade_arquivo . $this->recolhimento_FGTS . $this->indicador_previdencia . $this->recolhimento_previdencia . $this->indice_recolhimento  . $this->tipo_inscricao_fornecedor . $this->inscricao_fornecedor_responsavel . $this->brancos2 . $this->final_linha;

		// Inclui a quebra de linha.
		$out .= "\r\n";
		
		// Retorna a linha.
		return $out;
	}
	
	// Método utilizado para montar o redistro do tipo 10 – Informações da Empresa (Header da empresa) 
	private function InformacoesDaEmpresa($empresaId, $mes, $ano){
				
		$pagamentoFuncionarioData = new PagamentoFuncionarioData();
		
		// Pega a lista de pagamento do funcionario 
		$rs_lista = $pagamentoFuncionarioData->PegaListaPagamentoParaGefip($empresaId, $mes, $ano);		
		
		// Inicia o salário maternidade e familia com valor zero
		$salarioMaternidade = $salarioFamilia = 0;
		
		// Verifica se existe lista
		if($rs_lista){
			
			// Percorre lista para realizar a soma dos salarios famila e maternidade
			foreach($rs_lista as $val){
				$salarioFamilia = number_format(($salarioFamilia + $val->getValorFamilia()), 2, '.', '');
				$salarioMaternidade = number_format(($salarioMaternidade + $val->getValorMaternidade()), 2, '.', '');	
			}	
			
		}
		
		// Pega os dados da empresa.
		$empresaData = new DadosEmpresaData();
		$dadosEmpregado = $empresaData->GetDataDadosEmpresa($empresaId);
				
		// Define a variavel de saida vazia.
		$out = '';
		
		// Pega a inscrição da empresa.
		// Este atributo e usado em outros lugares.
		$this->inscricao_empresa = $this->Limpeza($dadosEmpregado->getCNPJ(),14);

		// 1 - Tipo de Registro posição inicial (1) final(2) quantidade(2).
		$this->tipo_de_registro_empresa = '10';
		
		// 2 - Tipo de Inscrição – Empresa posição inicial (3) final(3) quantidade(1).
		$this->tipo_inscricao_empresa = '1';
		
		// 3 - Inscrição da Empresa posição inicial (4) final(17) quantidade(14).
		$this->inscricao = $this->Limpeza($dadosEmpregado->getCNPJ(),14);
		
		// 4 - Zeros posição inicial (18) final(53) quantidade(36).
		$this->zeros36 = '000000000000000000000000000000000000';
		
		// 5 - Nome Empresa / Razão Social posição inicial (54) final(93) quantidade(40).
		$this->nome_empresa = $this->Limpeza($dadosEmpregado->getRazaoSocial(),40);
		
		// 6 - Logradouro, rua, nº, andar, apartamento posição inicial (94) final(143) quantidade(50).
		$this->logradouro = $this->Limpeza($dadosEmpregado->getEndereco(),50);
		
		// 7 - Bairro posição inicial (144) final(163) quantidade(20).
		$this->bairro = $this->Limpeza($dadosEmpregado->getBairro(),20);
		
		// 8 - CEP posição inicial (164) final(171) quantidade(8).
		$this->cep = $this->Limpeza($dadosEmpregado->getCEP(),8);
		
		// 9 - Cidade posição inicial (172) final(191) quantidade(20).
		$this->cidade = $this->Limpeza($dadosEmpregado->getCidade(),20);
		
		// 10 - Unidade da Federação posição inicial (192) final(193) quantidade(2).
		$this->uf = $this->Limpeza($dadosEmpregado->getEstado(),2);
				
		// Pega o prefixo com o numero de telefone da empresa.
		// 11 - Telefone posição inicial (194) final(205) quantidade(12).
		$this->telefone = preg_replace("/\W+/","",$dadosEmpregado->getPrefTelefone()).preg_replace("/\W+/","",$dadosEmpregado->getTelefone());
		
		// Pega a quantidada de caracter do telefone.
		$linhas_tel = strlen($this->telefone);
		
		if ($linhas_tel < 12) {
			for ($i=$linhas_tel; $i<12; $i++) {
				
				// 11 - Telefone posição inicial (194) final(205) quantidade(12).
				$this->telefone = '0'.$this->telefone;
			}
		};		
		
		// campo alteracao endereco
		if ($mes == '13'){
			
			// 12 - Indicador de Alteração de Endereço posição inicial (206) final(206) quantidade(1).
			$this->alteracao_endereco = 'n';
		} else {
			
			// 12 - Indicador de Alteração de Endereço posição inicial (206) final(206) quantidade(1).
			$this->alteracao_endereco = 's';
		};		
		
		// Pega o código do cnae.
		$cnae = $this->Limpeza($this->cnae_principal_da_empresa,7);
		
		// Chama o método para normalizar o 
		// 13 - CNAE  207 213 7 
		$this->cnae = $this->NormalizaCNAE($cnae);
						
		//campo alteracao cnae
		if ($mes == '13') {
			
			// 14 - Indicador de Alteração CNAE  214 214 1 posição inicial (206) final(206) quantidade(1).
			$this->alteracao_cnae = 'n';
		} else {
			
			// 14 - Indicador de Alteração CNAE  214 214 1 posição inicial (206) final(206) quantidade(1).
			$this->alteracao_cnae = 'a';
		}

		// 15 - Alíquota RAT posição inicial (215) final(216) quantidade(2).
		// $this->rat;
		
		// 16 - Código de Centralização posição inicial (217) final(217) quantidade(1).
		$this->centralizacao = '0';
		
		// 17 - SIMPLES posição inicial (218) final(218) quantidade(1).
		// $this->simples;
				
		//campo fpas (duvida: devemos prever 2 fpas?) E as gfip de antes de 98?
		// Instância da classe DadosSefipFolha 
		$dadosSefipFolha = new DadosSefipFolha();
		
		// Pega cnae principal da empresa.
		$linha_cnae_fpas = $dadosSefipFolha->PegaFPS($this->cnae_principal_da_empresa);
					
		// 18 - FPAS 219 221 3 posição inicial (206) final(206) quantidade(1).
		$this->fpas = $linha_cnae_fpas['fpas'];
		
		if($this->fpas == 736){
			
			// 18 - FPAS posição inicial (219) final(221) quantidade(3).
			$this->fpas = 515;	
		}
		
		// 19 - Código de Outras Entidades posição inicial (222) final(225) quantidade(4).
		// $this->codigo_outras_entidades;
		
		// 20 - Código de Pagamento GPS posição inicial (226) final(229) quantidade(4).
		// $this->codigo_gps;

		// 21 - Percentual de Isenção de Filantropia posição inicial (230) final(234) quantidade(5).
		$this->filantropia = $this->Limpeza('',5);
		
		// 22 - Salário-Família posição inicial (235) final(249) quantidade(15).
		//$this->salario_familia = '000000000000000';
		$this->salario_familia = str_pad(str_replace(",","",str_replace(".","",$salarioFamilia)), 15, "0", STR_PAD_LEFT);
		
		// 23 - Salário-Maternidade posição inicial (250) final(264) quantidade(15).
		//$this->salario_maternidade = '000000000000000';
		$this->salario_maternidade = str_pad(str_replace(",","",str_replace(".","",$salarioMaternidade)), 15, "0", STR_PAD_LEFT);
		
		// 24 - Contrib. Desc. Empregado Referente à Competência posição inicial (265) final(279) quantidade(15).
		$this->contribuicao_desc_empregado = '000000000000000';
		
		// 25 - Indicador de valor  negativo ou positivo posição inicial (280) final(280) quantidade(1).
		$this->indicador_neg_pos = '0';
		
		// 26 - Valor Devido à Previdência Social Referente à Comp. posição inicial (281) final(294) quantidade(14).
		$this->valor_devido_previdencia = '00000000000000';
		
		// 27 - Banco posição inicial (295) final(297) quantidade(3).
		$this->banco = $this->Limpeza('',3);
		
		// 28 - Agência posição inicial (298) final(301) quantidade(4).
		$this->agencia = $this->Limpeza('',4);
		
		// 29 - Conta Corrente posição inicial (302) final(310) quantidade(9).
		$this->conta_corrente = $this->Limpeza('',9);
		
		// 30 - Zeros posição inicial (311) final(355) quantidade(45).
		$this->zeros45 = '000000000000000000000000000000000000000000000';
		
		// 31 - Brancos posição inicial (356) final(359) quantidade(4).
		$this->brancos4 = '    ';
		
		//32 - Final de Linha posição inicial (360) final(360) quantidade(1).
		// $this->final_linha
			
		// Monta a linha.
		$out .= $this->tipo_de_registro_empresa . $this->tipo_inscricao_empresa . $this->inscricao . $this->zeros36 . $this->nome_empresa . $this->logradouro . $this->bairro . $this->cep . $this->cidade . $this->uf . $this->telefone . $this->alteracao_endereco . $this->cnae . $this->alteracao_cnae . $this->rat . $this->centralizacao . $this->simples . $this->fpas . $this->codigo_outras_entidades . $this->codigo_gps . $this->filantropia . $this->salario_familia . $this->salario_maternidade . $this->contribuicao_desc_empregado . $this->indicador_neg_pos . $this->valor_devido_previdencia . $this->banco . $this->agencia . $this->conta_corrente . $this->zeros45 . $this->brancos4 . $this->final_linha;
		
		// Inclui a quebra de linha.
		$out .= "\r\n";
		
		// Retorna a linha.
		return $out;
	}
	
	// Método utilizado para montar o redistro do tipo 12 – Informações Adicionais do Recolhimento da Empresa
	private function InfoAdicionaisRecolhimentoEmpresa($mes, $ano){
		
		$out = '';
		
		// 1 - Tipo de Registro posição inicial (1) final(2) quantidade(2).
		$this->tipo_de_registro_adicional = '12';
		
		// 2 - Tipo de Inscrição – Empresa posição inicial (3) final(3) quantidade(1).
		// $this->tipo_inscricao_empresa;

		// 3 - Inscrição da Empresa posição inicial (4) final(17) quantidade(14).
		// $this->inscricao_empresa;

		// 4 - Zeros posição inicial (18) final(53) quantidade(36).
		// $this->zeros36;
		
		// 5 - Dedução 13º Salário Licença Maternidade posição inicial (54) final(68) quantidade(15).
		$this->deducao_sal_maternidade = '000000000000000';
		
		// 6 - Receita Evento Desportivo/ Patrocínio posição inicial (69) final(83) quantidade(15).  
		$this->receita_esportivo = '000000000000000';
		
		// 7 - Indicativo Origem da Receita posição inicial (84) final(84) quantidade(1).
		$this->origem_receita = ' ';
		
		// 8 - Comercialização da Produção - Pessoa Física posição inicial (85) final(99) quantidade(15).
		$this->comercializacao_producao = '000000000000000';
		
		// 9 - Comercialização da Produção – Pessoa Jurídica posição inicial (100) final(114) quantidade(15).
		$this->comercializacao_producao2 = '000000000000000';
		
		// 10 - Outras Informações Processo posição inicial (115) final(125) quantidade(11).
		$this->outras_informacoes_processo = $this->Limpeza('',11);
		
		// 11 - Outras Informações Processo – Ano posição inicial (126) final(129) quantidade(4).
		$this->outras_informacoes_ano = $this->Limpeza('',4);
		
		// 12 - Outras Informações Vara/JCJ posição inicial (130) final(134) quantidade(5).
		$this->outras_informacoes_vara = $this->Limpeza('',5);
		
		// 13 - Outras Informações Período Início posição inicial (135) final(140) quantidade(6).
		$this->outras_informacoes_inicio = $this->Limpeza('',6);
		
		// 14 - Outras Informações Período Fim posição inicial (141) final(146) quantidade(6).
		$this->outras_informacoes_fim = $this->Limpeza('',6);
		
		// 15 - Compensação – Valor Corrigido posição inicial (147) final(161) quantidade(15).
		$this->compensacao = '000000000000000';
		
		// 16 - Compensação – Período Início posição inicial (162) final(167) quantidade(6).
		$this->compensacao_inicio = $this->Limpeza('',6);
		
		// 17 - Compensação – Período Fim posição inicial (168) final(173) quantidade(6).
		$this->compensacao_fim = $this->Limpeza('',6);
		
		// 18 - Recolhimento de Competências Anteriores posição inicial (174) final(188) quantidade(15).
		$this->comp_anteriores = '000000000000000';
		
		// 19 - Recolhimento de Competências Anteriores posição inicial (189) final(203) quantidade(15).
		$this->comp_anteriores_outras = '000000000000000';
		
		// 20 - Recolhimento de Competências Anteriores posição inicial (204) final(218) quantidade(15).
		$this->comp_anteriores_comercializacao = '000000000000000';
		
		// 21 - Recolhimento de Competências Anteriores posição inicial (219) final(233) quantidade(15).
		$this->comp_anteriores_com_outras = '000000000000000';
		
		// 22 - Recolhimento de Competências Anteriores posição inicial (234) final(248) quantidade(15).
		$this->comp_anteriores_esportivo = '000000000000000';		

		// 23 - Parcelamento do FGTS posição inicial (249) final(263) quantidade(15).
		$this->parcelamento_fgts_somatorio = '000000000000000';
		
		// 24 - Parcelamento do FGTS posição inicial (264) final(278) quantidade(15).
		$this->parcelamento_fgts_somatorio2 = '000000000000000';
		
		// 25 - Parcelamento do FGTS posição inicial (279) final(293) quantidade(15).
		$this->parcelamento_fgts_valor = '000000000000000';
		
		// 26 - Valores pagos à Cooperativas de Trabalho posição inicial (294) final(308) quantidade(15).
		$this->cooperativas = '000000000000000';
		
		// 27 - Implementação  futura posição inicial (309) final(353) quantidade(45).
		// $this->zeros45;
		
		// 28 - Brancos 354 359 6 posição inicial (353) final(359) quantidade(6).
		$this->brancos6 = $this->Limpeza('',6);
		
		// 29 - Final de Linha posição inicial (360) final(360) quantidade(1).
		// $this->final_linha
		
		// Se recolhe CPRB mais não faz empreitada, adiciona a compensação no registro 12 da empresa - MAL
		if( isset($_SESSION['recolhe_cprb']) && $_SESSION['recolhe_cprb'] == 'true' && $_SESSION['e_empreitada'] == 'false' ){

			$_SESSION['compensacao'] = $this->compensacao_cprb;

			$compensacao_aux = str_pad(str_replace(",",".",str_replace(".","",$this->compensacao_cprb)), 15, "0", STR_PAD_LEFT);
			$compensacao_auxInicio =  str_pad($this->getData($ano,$mes,'a'), 4, "0", STR_PAD_LEFT).str_pad($this->getData($ano,$mes,'d'), 2, "0", STR_PAD_LEFT);
			$compensacao_auxFim =  str_pad($ano, 4, "0", STR_PAD_LEFT).str_pad($mes, 2, "0", STR_PAD_LEFT);

			$this->compensacao = $compensacao_aux;
			$this->compensacao_inicio = $compensacao_auxInicio;
			$this->compensacao_fim = $compensacao_auxFim;
		}
	
		// Monta a linha.
		$out .= $this->tipo_de_registro_adicional . $this->tipo_inscricao_empresa . $this->inscricao_empresa . $this->zeros36 . $this->deducao_sal_maternidade . $this->receita_esportivo . $this->origem_receita . $this->comercializacao_producao . $this->comercializacao_producao2 . $this->outras_informacoes_processo . $this->outras_informacoes_ano . $this->outras_informacoes_vara . $this->outras_informacoes_inicio . $this->outras_informacoes_fim . $this->compensacao . $this->compensacao_inicio . $this->compensacao_fim . $this->comp_anteriores . $this->comp_anteriores_outras . $this->comp_anteriores_comercializacao . $this->comp_anteriores_com_outras . $this->comp_anteriores_esportivo . $this->parcelamento_fgts_somatorio . $this->parcelamento_fgts_somatorio2 . $this->parcelamento_fgts_valor . $this->cooperativas . $this->zeros45 . $this->brancos6 . $this->final_linha;
				
		// Inclui a quebra de linha.
		$out .= "\r\n";
		
		// Retorna a linha.
		return $out;
	}	
	
	// Método utilizado para montar o redistro do tipo 20 – Registro do Tomador de Serviço/Obra de Construção Civil  
	private function RegistroDoTomador(){
		
		$out = '';
		
		// 01 - Tipo de Registro posição inicial (1) final(2) quantidade(2).
		$this->tipo_de_registro_tomador_servico = "20";
		
		// 02 - Tipo de Inscrição – Empresa 3 3 1 
		//$this->tipo_inscricao_empresa;
		
		// 02 - Inscrição da Empresa 4 17 14 
		//$this->inscricao;
		
		// 04 - Tipo de Inscrição–Tomador/ Obra Const. Civil (*) 18 18 1 
		$this->tipo_inscricao_tomador = 1;
				
		// 05 - Inscrição  Tomador/Obra Const. Civil 19 32 14 
		//$this->inscricao_tomador_servico;
		
		// 06 - Zeros (*) 33 53 21 
		$this->zeros21 = '000000000000000000000';
		
		// 07 - Nome do Tomador/Obra de Const. Civil 54 93 40 
		//$this->empresa_tomador_servico;
		
		// 08 - Logradouro, rua, nº, andar, apartamento 94 143 50 
		//$this->longadouro_tomador_servico;
		
		// 09 - Bairro 144 163 20 
		//$this->bairro_tomador_servico;
		
		// 10 - CEP 164 171 8 
		//$this->cep_tomador_servico;
		
		// 11 - Cidade 172 191 20 
		//$this->cidade_tomador_servico;
		
		// 12 - Unidade da Federação 192 193 2 
		//$this->uf_tomador_servico;
		
		// 13 - Código de Pagamento GPS 194 197 4  
		//$this->codigo_pagamento_gps_tomador_servico;
		
		// 14 - Salário Família 198 212 15 
		$this->salario_familia_tomador_servico = '000000000000000';

		// 15 - Contrib. Desc. Empregado Referente à competência 13 213 227 15 
		$this->contrib_empregado_tomador_servico = '000000000000000';
		
		// 16 - Indicador de valor  negativo ou positivo 228 228 1 
		$this->indicador_de_valor_tomador_servico = '0';
		
		// 17 - Valor Devido à Previdência Social 229 242 14 
		$this->valor_devido_a_previdencia_tomador_servico = '00000000000000';
		
		// 18 - Valor de Retenção 243 257 15 
		//$this->valor_retencao_tomador_servico;
		
		// 19 - Valor das faturas emitidas para o tomador 258 272 15 
		$this->valor_das_faturas_tomador_servico = '000000000000000';
		
		// 20 - Zeros 273 317 45 
		$this->zeros45 = '000000000000000000000000000000000000000000000';
		
		// 21 - Brancos 318 359 42 
		$this->brancos42 = $this->Limpeza(' ',42);
		
		// 22 - Final de Linha posição inicial (360) final(360) quantidade(1).
		//$this->final_linha;
		
		$out = $this->tipo_de_registro_tomador_servico . $this->tipo_inscricao_empresa. $this->inscricao . $this->tipo_inscricao_tomador . $this->inscricao_tomador_servico . $this->zeros21 . $this->empresa_tomador_servico . $this->longadouro_tomador_servico . $this->bairro_tomador_servico . $this->cep_tomador_servico . $this->cidade_tomador_servico . $this->uf_tomador_servico . $this->codigo_pagamento_gps_tomador_servico . $this->salario_familia_tomador_servico . $this->contrib_empregado_tomador_servico . $this->indicador_de_valor_tomador_servico . $this->valor_devido_a_previdencia_tomador_servico . $this->valor_retencao_tomador_servico . $this->valor_das_faturas_tomador_servico . $this->zeros45 . $this->brancos42 . $this->final_linha;		
		
		// Inclui a quebra de linha.
		$out .= "\r\n";
		
		// Retorna a linha.
		return $out;
		
	}	
	
	// Método utilizado para montar o redistro do tipo 30 - Registro do Trabalhador  
	private function RegistroDoTrabalhador(){
		
		$out = '';
		
		// 1 - Tipo de Registro posição inicial (1) final(2) quantidade(2).
		$this->tipo_de_registro_trabalhador = '30';
		
		// 2 - Tipo de Inscrição Empresa posição inicial (3) final(3) quantidade(1).
		// $this->tipo_inscricao_empresa;
		
		// 3 - Inscrição da Empresa posição inicial (4) final(17) quantidade(14).
		// $this->inscricao_empresa;
		
		// 4 - Tipo de Inscrição – Tomador/obra de const. Civil posição inicial (18) final(18) quantidade(1).
		// $this->tipo_inscricao_tomador;
		
		// 5 - Inscrição Tomador / obra de const. civi posição inicial (19) final(32) quantidade(14).
		// $this->inscricao_tomador;
		
		// 6 - PIS/PASEP/CI posição inicial (33) final(43) quantidade(11).
		// $this->PIS_PASEP_CI;
		
		// 7 - Data Admissão posição inicial (44) final(51) quantidade(8).
		// $this->data_admissao;
		
		// 8 - Categoria Trabalhador posição inicial (52) final(53) quantidade(2).
		// $this->categoria_trabalhador;
		
		// 9 - Nome Trabalhador posição inicial (54) final(123) quantidade(70).
		// $this->nome_Trabalhador;
		
		// 10 - Matrícula do Empregado posição inicial (124) final(134) quantidade(11).
		// $this->matricula_empregado;
		
		// 11 - Número posição inicial (135) final(141) quantidade(7).
		// $this->ctps;
		
		// 12 - Série CTPS posição inicial (142) final(146) quantidade(5).
		// $this->ctps_serie;
		
		// 13 - Data de Opção posição inicial (147) final(154) quantidade(8).
		// $this->data_opcao;
		
		// 14 - Data de Nascimento 155 162 8 posição inicial (360) final(360) quantidade(1).
		// $this->data_de_Nascimento;
		
		// 15 - CBO – Código Brasileiro de Ocupação posição inicial (163) final(167) quantidade(5).
		// $this->codigo_cbo;
		
		// 16 - Remuneração sem 13 posição inicial (168) final(182) quantidade(15).
		// $this->remuneracao13_cap16;
		
		// 17 - Destinado à informação da parcela de 13 posição inicial (183) final(197) quantidade(15).
		// $this->remuneracao13_cap17;
		
		// 18 - Classe de Contribuição posição inicial (198) final(199) quantidade(2).
		// $this->classe_contribuicao;
		
		// 19 - Ocorrência posição inicial (200) final(201) quantidade(2).
		// $this->ocorrencia;
		
		// 20 - Valor Descontado do Segurado posição inicial (202) final(216) quantidade(15).
		// $this->valor_descontado;
		
		// 21 - Remuneração base de cálculo da contribuição previdenciária posição inicial (217) final(231) quantidade(15).
		// $this->remuneracao_base;
		
		// 22 - Base de Cálculo 13º Salário Previdência Social posição inicial (232) final(246) quantidade(15).
		// $this->base_claculo_13_comp;
		
		// 23 - Base de Cálculo 13° Salário Previdência – Referente à GPS da competência 13 posição inicial (247) final(261) quantidade(15).
		// $this->base_claculo_13_13;
		
		// 24 - Brancos posição inicial (262) final(359) quantidade(98).
		$this->brancos98 = $this->Limpeza('',98);
		
		// 25 - Final de Linha posição inicial (360) final(360) quantidade(1).
		// $this->final_linha
		
		// Monta a linha.
		$out .= $this->tipo_de_registro_trabalhador . $this->tipo_inscricao_empresa . $this->inscricao_empresa . $this->tipo_inscricao_tomador . $this->inscricao_tomador . $this->PIS_PASEP_CI . $this->data_admissao . $this->categoria_trabalhador . $this->nome_Trabalhador . $this->matricula_empregado . $this->ctps . $this->ctps_serie . $this->data_opcao . $this->data_de_Nascimento . $this->codigo_cbo . $this->remuneracao13_cap16 . $this->remuneracao13_cap17 . $this->classe_contribuicao . $this->ocorrencia . $this->valor_descontado . $this->remuneracao_base . $this->base_claculo_13_comp . $this->base_claculo_13_13 . $this->brancos98 . $this->final_linha;		
		
		// Inclui a quebra de linha.
		$out .= "\r\n";
		
		// Retorna a linha.
		return $out;
	}
	
	// Método utilizado para montar o redistro do tipo 90 – Registro Totalizador do Arquivo 
	private function RegistroTotalizadorDoArquivo(){
		
		$out = '';
		
		// 1 - Tipo de Registro posição inicial (1) final(2) quantidade(2). 
		$this->tipo_de_registro_totalizador = '90';
		
		// 2 - Marca de Final de Registro posição inicial (3) final(53) quantidade(51). 
		$this->marca_final_registro = '999999999999999999999999999999999999999999999999999';
		
		// 3 - Brancos posição inicial (54) final(359) quantidade(306).
		$this->brancos306 = $this->Limpeza(' ',306);
				
		// 4 - Final de Linha posição inicial (360) final(360) quantidade(1).
		// $this->final_linha
		
		// Monta a linha.
		$out .= $this->tipo_de_registro_totalizador . $this->marca_final_registro . $this->brancos306 . $this->final_linha;
		
		// Inclui a quebra de linha.
		$out .= "\r\n";
		
		// Retorna a linha.
		return $out;
	}
	
	// Método criado para pegar os dados do funcionario.
	private function RegraParaFuncionarios($empresaId, $mes, $ano) {
		
		$pagamento = '';
		
		// Define a variavel como vazio.
		$out = '';
		
		$funcionariosData = new DadosFuncionariosData();
		
		// Verifica se a data de competência e 13.
		if($mes == 13) {
			$listaFuncionario = $funcionariosData->DadosFuncRefPagamentoMes13($empresaId, $ano);
		} else {
			$listaFuncionario = $funcionariosData->DadosFuncRefPagamentoMes($empresaId, $mes, $ano);
		}

		// Verifica se existe a lista com os dados do fincionario.
		if($listaFuncionario) {
			
			// Percorre a lista de funcionarios.
			foreach($listaFuncionario as $val) {
			
				$pagamento = $this->PegaDadosPagamento($empresaId, $val->getFuncionarioId(), $mes, $ano);
	
				$this->tipo_inscricao_empresa = "1";
				$this->tipo_inscricao_tomador = $this->Limpeza('',1);
				$this->inscricao_tomador = $this->Limpeza('',14);
				$this->PIS_PASEP_CI = $this->Limpeza($val->getPIS(),11);
				$this->data_admissao = $this->Limpeza(date('dmY', strtotime($val->getDataAdmissao())) ,8);
				$this->nome_Trabalhador = $this->Limpeza($val->getNome(),70);
				$this->categoria_trabalhador = '01';
				$this->matricula_empregado = $this->Limpeza('',11);
				$this->ctps = $this->Limpeza($val->getCTPS(),7);
				$this->ctps_serie = $this->Limpeza($val->getSerieCTPS(),5);
				$this->data_opcao = $this->Limpeza(date('dmY', strtotime($val->getDataAdmissao())) ,8);
				$this->data_de_Nascimento = $this->Limpeza(str_replace('/', '', $val->getDataNascimento()),8);
				$this->codigo_cbo = '0'.substr($this->Limpeza($val->getCodigoCBO(),7),0,4);
				$this->classe_contribuicao = '  ';
				$this->valor_descontado = '000000000000000';
				$this->remuneracao_base = '000000000000000';
				$this->base_claculo_13_13 = '000000000000000';
				$this->outra_fonteSocio = '0';
				$this->ocorrencia = '  ';
				$this->valor_descontado = '000000000000000';

				// Pega o redistro do tipo 30 - Registro do Trabalhador.
				$out .= $this->RegistroDoTrabalhador();
			}
		}
		
		// retorna as linhas.
		return $out;
	}
	
	// Pega os dados de pagamento do funcionario
	private function PegaDadosPagamento($empresaId, $funcionarioId, $mes, $ano) {
		
		// Instância da classe DadosSefipFolha 
		$dadosSefipFolha = new DadosSefipFolha();
		
		// Verifica se a data de competência e 13.
		if($mes == 13) {

			$pagamento = $dadosSefipFolha->PegaPagtoDecimoTerceiroFuncionario($empresaId, $funcionarioId, $ano);
			
			$valor_decimo = 0;
			
			if($pagamento) {
				$valor_decimo = number_format($pagamento['valor_bruto'], 2, '.', '');	
			}
			
			$this->remuneracao13_cap16 = '000000000000000';
			$this->remuneracao13_cap17 = '000000000000000';
			$this->base_claculo_13_comp = str_pad(str_replace(",","",str_replace(".","",$valor_decimo)), 15, "0", STR_PAD_LEFT);

		} else {
			
			$pagamento = $dadosSefipFolha->PegaPagtoFuncionario($empresaId, $funcionarioId, $mes, $ano);

			$valor_bruto = 0;
			$valor_decimo = 0;
			
			if($pagamento) {
				
				foreach($pagamento as $val) {
					
					// Verifica se e pagamento de salario.
					if($val['tipoPagamento'] == 'salario' ) {

						// Verifica se no salario tem ferias vendida.
						if($val['vendaUmTercoFerias'] == 'S') {
							
							// Pega o salario Bruto sem Proventos( salário familia e martenidade ).
							$valorBrutoSemProventos = $val['valor_bruto'] - ($val['valor_familia'] + $val['valor_maternidade']);

							$valor_bruto = $valorBrutoSemProventos - ($val['valorFeriasVendida'] + $val['valorUmTercoFeriasVendida']);
							$valor_bruto = number_format($valor_bruto, 2, '.', '');		
								
						} else {
							
							// Pega o salario Bruto sem Proventos( salário familia e martenidade ).
							$valorBrutoSemProventos = $val['valor_bruto'] - ($val['valor_familia'] + $val['valor_maternidade']);
							
							$valor_bruto = number_format($valorBrutoSemProventos, 2, '.', '');
						}
					}
					
					// Verifica se e pagamento de decimo.
					if($val['tipoPagamento'] == 'decimoTerceiro' ) {
						$valor_decimo = number_format($val['valor_bruto'], 2, '.', '');	
					}
				}
			}
		
			$this->remuneracao13_cap16 = str_pad(str_replace(",","",str_replace(".","",$valor_bruto)), 15, "0", STR_PAD_LEFT);
			$this->remuneracao13_cap17 = str_pad(str_replace(",","",str_replace(".","",$valor_decimo)), 15, "0", STR_PAD_LEFT);
			$this->base_claculo_13_comp = '000000000000000';
		}
	}
	
	// Método criado para definir os dados do sócio e do autônomo.
	private function RegraParaDefinirDadosSocioAutono($empresaId) {

		// Instância da classe DadosSefipFolha 
		$dadosSefipFolha = new DadosSefipFolha();
	

		//Guarda a informações se deve ser declarada ou não a compensação
		$this->houver_compensacao = true;
		
		//Pega os ids de tomadores a serem inseridos no arquivo
		$lista_de_tomadores = $dadosSefipFolha->SEFIPTomadores($empresaId);

		foreach($lista_de_tomadores as $cada_tomador) {	

			//Zera o valor da compensação
			$this->compensacao21 = 0;

			if( $cada_tomador['id_tomador'] === '0' ){
				//Pega os dados do tomador
				$tomador= $dadosSefipFolha->DadosDaEmpresa($empresaId);
				$cada_tomador['retencao'] = 0;
			}
			else{
				//Pega os dados do tomador
				$tomador = $dadosSefipFolha->DadosTomadores($cada_tomador['id_tomador']); 
			}

			$_SESSION['tomadores_sefip'] .= '
				<tr>
					<td class="td_calendario">'.$tomador['nome'].'</td>
					<td class="td_calendario">R$ '.number_format($cada_tomador['retencao'],2,',','.').'</td>		
				</tr>';

			//Se deve haver compensação, seta a variavel que marca a ação
			if ( $cada_tomador['compensacao'] == 1 ) {
				$this->houver_compensacao = true;
			}

			//Dados do tomador
			//$this->tipo_de_registro_tomador_servico = "20";
			$this->inscricao_tomador_servico = $this->Limpeza($tomador['cei'],14);
			$this->empresa_tomador_servico = $this->Limpeza($tomador['nome'],40);
			$this->longadouro_tomador_servico = $this->Limpeza($tomador['endereco'],50);
			$this->bairro_tomador_servico = $this->Limpeza($tomador['bairro'],20);
			$this->cep_tomador_servico = $this->Limpeza($tomador['cep'],8);
			$this->cidade_tomador_servico = $this->Limpeza($tomador['cidade'],20);
			$this->uf_tomador_servico = $this->Limpeza($tomador['estado'],2);
			$this->codigo_pagamento_gps_tomador_servico = $this->Limpeza('',4);
			$this->valor_retencao_tomador_servico = str_pad(number_format($cada_tomador['retencao'],'2','',''), 15, "0", STR_PAD_LEFT);

			// Pega o redistro do tipo 20 – Registro do Tomador de Serviço/Obra de Construção Civil
			$this->infoTomador .= $this->RegistroDoTomador();

			//Pega os ids dos trabalhadores para este tomador
			$lista_trabalhadores_tomador = $dadosSefipFolha->SEFIPTomadoresId($cada_tomador['id_tomador']);

			// Variavel que sera concatenada com cada trabalhador
			$infoSocio = "";

			//Percore os trabalhadores para este tomador	
			foreach($lista_trabalhadores_tomador as $cada_trabalhador) {						

				$_SESSION['trabalhadores_sefip'] .= $cada_trabalhador['id_trabalhador'].',';

				//Se o trabalhador é um autonomo, busca na tabela dos autonomos
				if( $cada_trabalhador['tipo'] == 'autonomo'  ){

					// Pega os dados da empresa.
					$empresaData = new DadosEmpresaData();
					$dadosEmpregado = $empresaData->GetDataDadosEmpresa($empresaId);

					//Pega os dados do autonomo para este tomador
					$trabalhador_tomador = $dadosSefipFolha->DadosAutonomos($cada_trabalhador['id_trabalhador']);

					//Pega o valor do pagamento realizado para o trabalhador
					$pagamento = $dadosSefipFolha->DadosPagamentos($empresaId, $cada_trabalhador['id_trabalhador'], $mes, $ano);

					//Define os dados do trabalhador
					//$tipo_de_registro_trabalhador = "30";
					$this->tipo_inscricao_empresa = "1";
					$this->inscricao_empresa = $this->Limpeza($dadosEmpregado->getCNPJ(),14);
					$this->tipo_inscricao_tomador = "1";
					$this->inscricao_tomador = $this->Limpeza($tomador['cei'],14);
					$this->PIS_PASEP_CI = $trabalhador_tomador['pis'];
					$this->data_admissao = "        ";
					$this->nome_Trabalhador = $trabalhador_tomador['nome'];
					$this->categoria_trabalhador = '13';
					$this->matricula_empregado = $this->Limpeza('',11);
					$this->ctps = $this->Limpeza('',7);
					$this->ctps_serie = $this->Limpeza('',5);
					$this->data_opcao = $this->Limpeza('',8);
					$this->data_de_Nascimento = $this->Limpeza('',8);
					$this->codigo_cbo = $trabalhador_tomador['cbo'];
					$this->remuneracao13_cap16 = str_pad(str_replace(",",".",str_replace(".","",$pagamento['valor_bruto'])), 15, "0", STR_PAD_LEFT);
					$this->remuneracao13_cap17 = '000000000000000';
					$this->classe_contribuicao = '  ';
					$this->valor_descontado = '000000000000000';
					$this->remuneracao_base = '000000000000000';
					$this->base_claculo_13_comp = '000000000000000';
					$this->base_claculo_13_13 = '000000000000000';
					$valor_descontadoSocio = "000000000000000";
					$outra_fonteSocio = '0';
					$tipo_trabalhador = $cada_trabalhador['tipo'];
					$this->ocorrencia = '  ';
					$this->valor_descontado = '000000000000000';

					if($tipo_trabalhador == 'socio'){
						$this->categoria_trabalhador = '11';
					}else{
						$this->categoria_trabalhador = '13';
					}
					if($outra_fonteSocio == '1'){
						$this->valor_descontado = $valor_descontadoSocio;
						// OCORRENCIA 05 - houve outra fonte pagadora
						$ocorrencia = '05';
					}

					//Concatena os dados do trabalhador
					$infoSocio .= $this->RegistroDoTrabalhador();

				} else {

					// Pega os dados da empresa.
					$empresaData = new DadosEmpresaData();
					$dadosEmpregado = $empresaData->GetDataDadosEmpresa($empresaId);

					//Pega os dados do sócio para este tomador
					$trabalhador_tomador = $dadosSefipFolha->DadosAutonomos($cada_trabalhador['id_trabalhador']);

					//Pega o valor do pagamento realizado para o trabalhador
					$pagamento = $dadosSefipFolha->DadosPagamentos($empresaId, $cada_trabalhador['id_trabalhador'], $mes, $ano);

					//Define os dados do trabalhador
					//$this->tipo_de_registro_trabalhador = "30";
					$this->tipo_inscricao_empresa = "1";
					$this->inscricao_empresa = $this->Limpeza($linha_empresa['cnpj'],14);
					$this->tipo_inscricao_tomador = "1";
					$this->inscricao_tomador = $this->Limpeza($tomador['cei'],14);
					$this->PIS_PASEP_CI = $trabalhador_tomador['nit'];
					$this->data_admissao = str_replace('/', '', $trabalhador_tomador['data_admissao']);
					$this->nome_Trabalhador = $trabalhador_tomador['nome'];
					$this->categoria_trabalhador = '11';
					$this->matricula_empregado = $this->Limpeza('',11);
					$this->ctps = $this->Limpeza('',7);
					$this->ctps_serie = $this->Limpeza('',5);
					$this->data_opcao = $this->Limpeza('',8);
					$this->data_de_Nascimento = $this->Limpeza('',8);
					$this->codigo_cbo = $trabalhador_tomador['codigo_cbo'];
					$this->remuneracao13_cap16 = str_pad(str_replace(",",".",str_replace(".","",$pagamento['valor_bruto'])), 15, "0", STR_PAD_LEFT);
					$this->remuneracao13_cap17 = '000000000000000';
					$this->classe_contribuicao = '  ';
					$this->valor_descontado = '000000000000000';
					$this->remuneracao_base = '000000000000000';
					$this->base_claculo_13_comp = '000000000000000';
					$this->base_claculo_13_13 = '000000000000000';
					$valor_descontadoSocio = "000000000000000";
					$outra_fonteSocio = '0';
					$tipo_trabalhador = 'socio';
					$this->ocorrencia = '  ';
					$this->valor_descontado = '000000000000000';
					if($tipo_trabalhador == 'socio'){
						$this->categoria_trabalhador = '11';
					}else{
						$this->categoria_trabalhador = '13';
					}
					if($outra_fonteSocio == '1'){
						$this->valor_descontado = $this->valor_descontadoSocio;
						// OCORRENCIA 05 - houve outra fonte pagadora
						$ocorrencia = '05';
					}

					if($tipo_trabalhador == 'socio'){
						$this->categoria_trabalhador = '11';
					}else{
						$this->categoria_trabalhador = '13';
					}
					if($outra_fonteSocio == '1'){
						$this->valor_descontado = $valor_descontadoSocio;
						// OCORRENCIA 05 - houve outra fonte pagadora
						$ocorrencia = '05';
					}

					//Concatena os dados do trabalhador
					$infoSocio .= $this->RegistroDoTrabalhador();
				}

				//Somatorio dos pagamentos para calcular a compensação(se houver_compensacao)
				if( $cada_trabalhador['tipo'] == 'autonomo' ){
					$compensacao21 = floatval($compensacao21) + floatval(($pagamento['valor_bruto']) * (( 20 )/100));
				}
				else{
					$compensacao21 = floatval($compensacao21) + floatval(($pagamento['valor_bruto']) * (( 20 )/100));
				}

			}					

			//Se houve compensação, monta o registro 21 para o tomador informando o valor e o período, que correnpondem a:
			// somatorio do Pagamentos_trabalhadores * ( 20% + RAT )
			// Data inicio: Mes anterior do mes informado dos pagamentos
			// Data Fim: Mes informado dos pagamentos
			if( $houver_compensacao == true ){

				$compensacao21 = $compensacao21 * 100;

				//Tomador 21
				$tipo_de_registro_trabalhador = "21";
				$tipo_inscricao_empresa = "1";
				$inscricao_empresa = $this->Limpeza($linha_empresa['cnpj'],14);
				$tipo_inscricao_tomador = "1";
				$inscricao_tomador = $this->Limpeza($tomador['cei'],14);

				$_SESSION['compensacao'] = floatval($_SESSION['compensacao']) + floatval($compensacao21);

				$compensacao21 = str_pad(str_replace(",",".",str_replace(".","",$compensacao21)), 15, "0", STR_PAD_LEFT);
				$compensacao21Inicio =  str_pad($this->getData($ano,$mes,'a'), 4, "0", STR_PAD_LEFT).str_pad($this->getData($ano,$mes,'d'), 2, "0", STR_PAD_LEFT);
				$compensacao21Fim =  str_pad($ano, 4, "0", STR_PAD_LEFT).str_pad($mes, 2, "0", STR_PAD_LEFT);

				//Registro 21		
				if( isset($_SESSION['recolhe_cprb']) && $_SESSION['recolhe_cprb'] == 'true'  )			
					$this->infoTomador .= $tipo_de_registro_trabalhador.$tipo_inscricao_empresa.$inscricao_empresa.$tipo_inscricao_tomador.$inscricao_tomador."000000000000000000000".$compensacao21.$compensacao21Inicio.$compensacao21Fim."000000000000000000000000000000000000000000000000000000000000000000000000000                                                                                                                                                                                                            ".$final_linha."\r\n";
			}

			//Concatena as informações dos trabalhadores 
			$this->infoTomador .= $infoSocio;
		}
		//Retira os registros da sefip gerada do banco de dados
		$consulta = mysql_query("DELETE FROM sefip_tomadores WHERE id_user = '".$empresaId."' ");
		//FIM
	}
	
	// REGISTRO DOS TRABALHADORES/SOCIOS
	private function PegaRegistroDosSocios($empresaId, $mes, $ano) {
		
		// Define a variavel de retorno vazio.
		$out = '';
		
		// Pega os dados da empresa.
		$empresaData = new DadosEmpresaData();
		$dadosEmpregado = $empresaData->GetDataDadosEmpresa($empresaId);
		
		// Instância da classe DadosSefipFolha 
		$dadosSefipFolha = new DadosSefipFolha();
		
		$dadosSociosAutonosos = $dadosSefipFolha->PegaDadosAutonomosSocio($empresaId, $mes, $ano);		

		// Verifica se houver retorno dos dados dos socios.
		if($dadosSociosAutonosos) {
			
			// PERCORRENDO OS DADOS DOS SÓCIOS PARA GUARDAR EM ARRAYS
			foreach($dadosSociosAutonosos as $linha_socio) {	

				//$tipo_de_registro_trabalhador = '30';
				$this->classe_contribuicao = '  ';
				$this->valor_descontado = '000000000000000';

				if($linha_socio['tipo_trabalhador'] == 'socio'){
					$this->categoria_trabalhador = '11';
				}else{
					$this->categoria_trabalhador = '13';
				}
				
				$this->ocorrencia = '  ';

				if($linha_socio['outra_fonte'] == '1'){
					$this->valor_descontado = str_pad(str_replace(",",".",str_replace(".","",$linha_socio['inss'])), 15, "0", STR_PAD_LEFT);
					// OCORRENCIA 05 - houve outra fonte pagadora
					$this->ocorrencia = '05';
				}

				$this->tipo_inscricao_empresa = "1";
				$this->inscricao_empresa = $this->Limpeza($dadosEmpregado->getCNPJ(),14);			

				if($this->codigo_recolhimento == '150') {
					$this->tipo_inscricao_tomador = '1';
					$this->inscricao_tomador = $this->Limpeza($linha_empresa['cnpj'],14);
				} else {
					$this->tipo_inscricao_tomador = $this->Limpeza('',1);
					$this->inscricao_tomador = $this->Limpeza('',14);
				}
				$this->PIS_PASEP_CI = $this->Limpeza($linha_socio['nit'],11);
				$this->data_admissao = $this->Limpeza($linha_socio['data_admissao'],8);
				$this->nome_Trabalhador = $this->Limpeza($linha_socio['nome'],70);
				$this->matricula_empregado = $this->Limpeza('',11);
				$this->ctps = $this->Limpeza('',7);
				$this->ctps_serie = $this->Limpeza('',5);
				$this->data_opcao = $this->Limpeza('',8);
				$this->data_de_Nascimento = $this->Limpeza('',8);
				$this->codigo_cbo = '0'.substr($this->Limpeza($linha_socio['codigo_cbo'],7),0,4);
				$this->remuneracao13_cap16 = str_pad(str_replace(",",".",str_replace(".","",$linha_socio['pro_labore'])), 15, "0", STR_PAD_LEFT);
				$this->remuneracao13_cap17 = '000000000000000';
				$this->remuneracao_base = '000000000000000';
				$this->base_claculo_13_comp = '000000000000000';
				$this->base_claculo_13_13 = '000000000000000';

				$out .= $this->RegistroDoTrabalhador();	
			}
		}
		
		// retorna a as linhas dos autônomos e sócios.
		return $out;
	}
}