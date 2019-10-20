<?php
/**
 * Classe Responsavel pelas ações do usuário.
 * autor: Atano de Farias Jacinto
 * Date: 14/03/2017
 */
require_once('DataBaseMySQL/DadosDaEmpresa.php');
require_once('Model/DadosEmpresa/vo/DadosEmpresaVo.php');

class DadosEmpresaData {
	
	// Realiza a verificação para validaçao do usuario.
	public function GetDataDadosEmpresa($id_login){
		
		$empresa = new DadosDaEmpresa();
		
		// Pega os dados do usuário pelo Id.
		$dados = $empresa->pegaDadosEmpresa($id_login);
	
		// Verifica se houve dados de retorno.
		if($dados){
			
			// Instância a classe de requisição de dados do banco. 
			$empresaVo = new DadosEmpresaVo();
			
			// Passa os dados para objeto.
			$empresaVo->setId($dados['id']);
			$empresaVo->setRazaoSocial($dados['razao_social']);
			$empresaVo->setNomeFantasia($dados['nome_fantasia']);
			$empresaVo->setCNPJ($dados['cnpj']);
			$empresaVo->setInscricaoNoCCM($dados['inscricao_no_ccm']);
			$empresaVo->setInscricaoEstadual($dados['inscricao_estadual']);
			$empresaVo->setTipoEndereco($dados['tipo_endereco']);
			$empresaVo->setEndereco($dados['endereco']);
			$empresaVo->setNumero($dados['numero']);
			$empresaVo->setComplemento($dados['complemento']);
			$empresaVo->setBairro($dados['bairro']);
			$empresaVo->setCEP($dados['cep']);
			$empresaVo->setCidade($dados['cidade']);
			$empresaVo->setEstado($dados['estado']);
			$empresaVo->setPrefTelefone($dados['pref_telefone']);
			$empresaVo->setTelefone($dados['telefone']);
			$empresaVo->setRamoAtividade($dados['ramo_de_atividade']);
			$empresaVo->setCodigoAtividadePrefeitura($dados['codigo_de_atividade_prefeitura']);
			$empresaVo->setRegimeTributacao($dados['regime_de_tributacao']);
			$empresaVo->setInscritaComo($dados['inscrita_como']);
			$empresaVo->setRegistroNire($dados['registro_nire']);
			$empresaVo->setNumeroCartorio($dados['numero_cartorio']);
			$empresaVo->setRegistroCartorio($dados['registro_cartorio']);
			$empresaVo->setDataCriacao($dados['data_de_criacao']);
			$empresaVo->setAtiva($dados['ativa']);
			$empresaVo->setDataDesativacao($dados['data_desativacao']);
			
		}

		return $empresaVo;
	}
}