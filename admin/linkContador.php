<?php
	
	require_once('../Model/DadosContador/DadosContadorData.php');	
	
	// Pega o link para acessa a tela com o pagamento do contador.
	function linkContador() {
		
		$dadosContador =  new DadosContadorData();
		
		$dados = $dadosContador->GetListDadosContador();
		
		$link = '<ul style="width: 130px !important;">';
		$link2 = '';

		if($dados) {
			foreach($dados as $val){			
				
				if($val->getId() == 13) {
						
					$link2 = '<li> <img src="../images/seta_opcoes.png"> <a class="linkMenu" href="clientes_avulso_contador_amigo.php?contadorId='.$val->getId().'">Avulso</a> </li>';					
					
				} else {
					
					$firstName =  explode(" ",$val->getNome());
					
					$link .= '<li> <img src="../images/seta_opcoes.png"> <a class="linkMenu" href="listapagamentocontador.php?contadorId='.$val->getId().'">'.$firstName[0].' '.$firstName[1].'</a> </li>';
					
				}
			}
			
			$link .= $link2; 
			
		} else {
			$link .= '<li> <img src="../images/seta_opcoes.png"> <a class="linkMenu" href="#">Não Existe Contador</a> </li>';
		}
		
		// Linha com o link para acessar o alteração do contador responsável
		$link .= '<li><img src="../images/seta_opcoes.png"><a class="linkMenu" href="altera_contador_do_cliente.php">Alterar Contador</a></li>';
		
		$link .= '</ul>';
		
		return $link	;
	}
?>