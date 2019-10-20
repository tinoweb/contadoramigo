<?php
/**
 * Classe Responsavel pelas ações do usuário.
 * autor: Atano de Farias Jacinto
 * Date: 06/06/2017
 */
require_once('DataBaseMySQL/DadosTokenPagamento.php');
require_once('Model/TokenPagamento/vo/TokenPagamentoVo.php');


class TokenPagamentoData {
	
	public function PegaDadosTokenPagamento($idUser) {
	
		$objeto = '';
	
		// Instancia a classe responsavel pela manipulação dos dados do cliente. 
		$token = new DadosTokenPagamento();
		
		$dados = $token->pegaDadosToken($idUser);
		
		// Verifica se pegou os dados do banco de dados.
		if($dados){
			
			// Instancia objetos
			$objeto = new TokenPagamentoVo();
			
			$objeto->setId($dados['id']);
			$objeto->setIdUser($dados['id_user']);
			$objeto->setToken($dados['token']);
			$objeto->setNumeroCartao($dados['numero_cartao']);
			$objeto->setNomeTitular($dados['nome_titular']);
			$objeto->setBandeira($dados['bandeira']); 
			$objeto->setDataCriacao($dados['data_criacao']);
		}
		
		return $objeto;
	}
}

