<?php
/**
 * autor: Átano de Farias Jacinto
 * data: 06/02/2018
 */
//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);

require_once('DataBaseMySQL/DadosCnae.php');

class SimplesNascinal {
	
	// Passo 6 - Método criado para montar as atividades econômicas com receita no período de apuração.
	public function PegaEstruturaAtividadesEconomicas() {
	
		$checks = '';

		// VARIAVEL QUE CONTROLA SE VEIO DIRETO DE UMA ATIVIDADE QUE NÃO PRECISA DE ESCOLHA DE OPÇÃO DE RETENÇÃO
		if($_SESSION['passou_direto'] == 0){

			// PERCORRENDO A VARIAVEL DE SESSAO QUE CONTEM A ARRAY DE CNAEs PARA MONTAR OS CHECKS E DIVS DA PAGINA
			foreach($_SESSION['cnaes_empresa_mes'] as $cnae){

				// TIRANDO OS CARACTERES ESPECIAIS PARA MONTAR O NOME DO CAMPO DO FORM ANTERIOR
				$indesCNAE = str_replace("/","",str_replace("-","",$cnae));
				
				// MONTANDO STRING COM OS VALORES DOS CAMPOS QUE VIERAM DO FORM ANTERIOR (lista de checks)
				$checks .= (isset($_REQUEST['marcar_check_'.$indesCNAE]) ? $_REQUEST['marcar_check_'.$indesCNAE] . ';': "");
			}

		}else{

			$cnae = $_SESSION['cnaes_empresa_mes'][0];

			// TIRANDO OS CARACTERES ESPECIAIS PARA MONTAR O NOME DO CAMPO DO FORM ANTERIOR
			$indesCNAE = str_replace("/","",str_replace("-","",$cnae));
			
			$checks = (isset($_SESSION['marcar_check_'.$indesCNAE]) ? $_SESSION['marcar_check_'.$indesCNAE].";" : "");
		}		
		
		// CRIANDO UM ARRAY COM OS CHECKs QUE VIERAM DO FORM ANTERIOR
		$arr_checks = explode(';',substr($checks,0,strlen($checks)-1));
		
		$out = '<span '.$this->DefineVisualizacao(1,$arr_checks,'block').'><b>Revenda de mercadorias, exceto para o exterior</b></span>
				<ul style="margin-bottom: 25px;">
					<li '.$this->DefineVisualizacao(2,$arr_checks,'list-item').'>Sem substituição tributária/tributação monofásica/antecipação com encerramento de tributação (o substituto tributário do ICMS deve utilizar essa opção)</li>
					<li '.$this->DefineVisualizacao(3,$arr_checks,'list-item').'>Com substituição tributária/tributação monofásica/antecipação com encerramento de tributação (o substituído tributário do ICMS deve utilizar essa opção)</li>
				</ul>
				<span '.$this->DefineVisualizacao(4,$arr_checks,'block').'><b>Revenda de mercadorias para o exterior</b></span>
				<span '.$this->DefineVisualizacao(5,$arr_checks,'block').'><b>Venda de mercadorias industrializadas pelo contribuinte, exceto para o exterior</b></span>
				<ul style="margin-bottom: 25px;">
					<li '.$this->DefineVisualizacao(6,$arr_checks,'list-item').'>Sem substituição tributária/tributação monofásica/antecipação com encerramento de tributação (o substituto tributário do ICMS deve utilizar essa opção)</li>
					<li '.$this->DefineVisualizacao(7,$arr_checks,'list-item').'>Com substituição tributária/tributação monofásica/antecipação com encerramento de tributação (o substituído tributário do ICMS deve utilizar essa opção)</li>
				</ul>
				<span '.$this->DefineVisualizacao(8,$arr_checks,'block').'><b>Venda de mercadorias industrializadas pelo contribuinte para o exterior</b></span>
				<span '.$this->DefineVisualizacao(9,$arr_checks,'block').'><b>Locação de bens móveis, exceto para o exterior</b></span>
				<span '.$this->DefineVisualizacao(10,$arr_checks,'block').'><b>Locação de bens móveis para o exterior</b></span>
				<span '.$this->DefineVisualizacao(11,$arr_checks,'block').'><b>Prestação de Serviços, exceto para o exterior</b></span>
				<ul style="margin-bottom: 25px;">	
					<li '.$this->DefineVisualizacao(12,$arr_checks,'list-item').'>Escritórios de serviços contábeis autorizados pela legislação municipal a pagar o ISS em valor fixo em guia do Município</li>
					<li '.$this->DefineVisualizacao(13,$arr_checks,'list-item').'>Sujeitos ao fator “r”, sem retenção/substituição tributária de ISS, com ISS devido a outro(s) Município(s)</li>
					<li '.$this->DefineVisualizacao(14,$arr_checks,'list-item').'>Sujeitos ao fator “r”, sem retenção/substituição tributária de ISS, com ISS devido ao próprio Município do estabelecimento</li>
					<li '.$this->DefineVisualizacao(15,$arr_checks,'list-item').'>Sujeitos ao fator “r”, com retenção/substituição tributária de ISS</li>
					<li '.$this->DefineVisualizacao(16,$arr_checks,'list-item').'>Não sujeitos ao fator “r” e tributados pelo Anexo III, sem retenção/substituição tributária de ISS, com ISS devido a outro(s) Município(s)</li>
					<li '.$this->DefineVisualizacao(17,$arr_checks,'list-item').'>Não sujeitos ao fator “r” e tributados pelo Anexo III, sem retenção/substituição tributária de ISS, com ISS devido ao próprio Município do estabelecimento</li>
					<li '.$this->DefineVisualizacao(18,$arr_checks,'list-item').'>Não sujeitos ao fator “r” e tributados pelo Anexo III, com retenção/substituição tributária de ISS</li>
					<li '.$this->DefineVisualizacao(19,$arr_checks,'list-item').'>Sujeitos ao Anexo IV, sem retenção/substituição tributária de ISS, com ISS devido a outro(s) Município(s)</li>
					<li '.$this->DefineVisualizacao(20,$arr_checks,'list-item').'>Sujeitos ao Anexo IV, sem retenção/substituição tributária de ISS, com ISS devido ao próprio Município do estabelecimento</li>
					<li '.$this->DefineVisualizacao(21,$arr_checks,'list-item').'>Sujeitos ao Anexo IV, com retenção/substituição tributária de ISS</li>
				</ul>
				<span '.$this->DefineVisualizacao(22,$arr_checks,'block').'><b>Prestação de Serviços relacionados nos subitens 7.02, 7.05 e 16.1 da lista anexa à LC 116/2003, exceto para o exterior</b></span>
				<ul style="margin-bottom: 25px;">
					<li '.$this->DefineVisualizacao(23,$arr_checks,'list-item').'>Serviços da área da construção civil relacionados nos subitens 7.02 e 7.05 da lista anexa à LC 116/2003 e tributados pelo Anexo III, sem retenção/substituição tributária de ISS, com ISS devido a outro(s) Município(s)</li>
					<li '.$this->DefineVisualizacao(24,$arr_checks,'list-item').'>Serviços da área da construção civil relacionados nos subitens 7.02 e 7.05 da lista anexa à LC 116/2003 e tributados pelo Anexo III, sem retenção/substituição tributária de ISS, com ISS devido ao próprio Município do estabelecimento</li>
					<li '.$this->DefineVisualizacao(25,$arr_checks,'list-item').'>Serviços da área da construção civil relacionados nos subitens 7.02 e 7.05 da lista anexa à LC 116/2003 e tributados pelo Anexo III, com retenção/substituição tributária de ISS</li>
					<li '.$this->DefineVisualizacao(26,$arr_checks,'list-item').'>Serviços da área da construção civil relacionados nos subitens 7.02 e 7.05 da lista anexa à LC 116/2003 e tributados pelo Anexo IV, sem retenção/substituição tributária de ISS, com ISS devido a outro(s) Município(s)</li>
					<li '.$this->DefineVisualizacao(27,$arr_checks,'list-item').'>Serviços da área da construção civil relacionados nos subitens 7.02 e 7.05 da lista anexa à LC 116/2003 e tributados pelo Anexo IV, sem retenção/substituição tributária de ISS, com ISS devido ao próprio Município do estabelecimento</li>
					<li '.$this->DefineVisualizacao(28,$arr_checks,'list-item').'>Serviços da área da construção civil relacionados nos subitens 7.02 e 7.05 da lista anexa à LC 116/2003 e tributados pelo Anexo IV, com retenção/substituição tributária de ISS</li>
					<li '.$this->DefineVisualizacao(29,$arr_checks,'list-item').'>Serviços de transporte coletivo municipal rodoviário, metroviário, ferroviário e aquaviário de passageiros, sem retenção/substituição tributária de ISS, com ISS devido a outro(s) Município(s)</li>
					<li '.$this->DefineVisualizacao(30,$arr_checks,'list-item').'>Serviços de transporte coletivo municipal rodoviário, metroviário, ferroviário e aquaviário de passageiros, sem retenção/substituição tributária de ISS, com ISS devido ao próprio Município do estabelecimento</li>
					<li '.$this->DefineVisualizacao(31,$arr_checks,'list-item').'>Serviços de transporte coletivo municipal rodoviário, metroviário, ferroviário e aquaviário de passageiros, com retenção/substituição tributária de ISS</li>
				</ul>
				<span '.$this->DefineVisualizacao(32,$arr_checks,'block').'><b>Prestação de Serviços para o exterior</b></span>
				<ul style="margin-bottom: 25px;"> 
					<li '.$this->DefineVisualizacao(33,$arr_checks,'list-item').'>Escritórios de serviços contábeis autorizados pela legislação municipal a pagar o ISS em valor fixo em guia do Município</li>
					<li '.$this->DefineVisualizacao(34,$arr_checks,'list-item').'>Sujeitos ao fator “r”</li> 
					<li '.$this->DefineVisualizacao(35,$arr_checks,'list-item').'>Não sujeitos ao fator “r” e tributados pelo Anexo III </li>
					<li '.$this->DefineVisualizacao(36,$arr_checks,'list-item').'>Sujeitos ao Anexo IV</li>
				</ul>
				<span '.$this->DefineVisualizacao(37,$arr_checks,'block').'><b>Prestação de Serviços relacionados nos subitens 7.02 e 7.05 da lista anexa à LC 116/2003, para o exterior</b></span>
				<ul style="margin-bottom: 25px;">
					<li '.$this->DefineVisualizacao(38,$arr_checks,'list-item').'>Serviços da área da construção civil relacionados nos subitens 7.02 e 7.05 da lista anexa à LC 116/2003 e tributados pelo Anexo III</li>
					<li '.$this->DefineVisualizacao(39,$arr_checks,'list-item').'>Serviços da área da construção civil relacionados nos subitens 7.02 e 7.05 da lista anexa à LC 116/2003 e tributados pelo Anexo IV</li>
				</ul>
				<span '.$this->DefineVisualizacao(40,$arr_checks,'block').'><b>Serviços de comunicação; de transporte intermunicipal e interestadual de carga; e de transporte intermunicipal e interestadual de passageiros autorizados no inciso VI do art. 17 da LC 123, exceto para o exterior</b></span>
				<ul style="margin-bottom: 25px;">
					<li '.$this->DefineVisualizacao(41,$arr_checks,'list-item').'>Transporte sem substituição tributária de ICMS (o substituto tributário deve utilizar essa opção)</li>
					<li '.$this->DefineVisualizacao(42,$arr_checks,'list-item').'>Transporte com substituição tributária de ICMS (o substituído tributário deve utilizar essa opção)</li>
					<li '.$this->DefineVisualizacao(43,$arr_checks,'list-item').'>Comunicação sem substituição tributária de ICMS (o substituto tributário deve utilizar essa opção)</li>
					<li '.$this->DefineVisualizacao(44,$arr_checks,'list-item').'>Comunicação com substituição tributária de ICMS (o substituído tributário deve utilizar essa opção)</li>
				</ul>
				<span '.$this->DefineVisualizacao(45,$arr_checks,'block').'><b>Serviços de comunicação; de transporte intermunicipal e interestadual de carga; e de transporte intermunicipal e interestadual de passageiros autorizados no inciso VI do art. 17 da LC 123, para o exterior</b></span>
				<ul style="margin-bottom: 25px;">
					<li '.$this->DefineVisualizacao(46,$arr_checks,'list-item').'>Transporte</li>
					<li '.$this->DefineVisualizacao(47,$arr_checks,'list-item').'>Comunicação</li>
				</ul>
				<span '.$this->DefineVisualizacao(48,$arr_checks,'block').'><b>Atividades com incidência simultânea de IPI e de ISS, exceto para o exterior</b></span>
				<ul style="margin-bottom: 25px;">
					<li '.$this->DefineVisualizacao(49,$arr_checks,'list-item').'>Sem retenção/substituição tributária de ISS, com ISS devido a outro(s) Município(s)</li>
					<li '.$this->DefineVisualizacao(50,$arr_checks,'list-item').'>Sem retenção/substituição tributária de ISS, com ISS devido ao próprio Município do estabelecimento</li>
					<li '.$this->DefineVisualizacao(51,$arr_checks,'list-item').'>Com retenção/substituição tributária de ISS</li>
				</ul>
				<span '.$this->DefineVisualizacao(52,$arr_checks,'block').'><b>Atividades com incidência simultânea de IPI e de ISS para o exterior</b></span>';
		
		// Retorna atividades econômicas com receita no período de apuração do cliente.
		return $out;		
	}
	
	// Passo 7 - Método criado para montar as atividades econômicas com receita no período de apuração.
	public function PegaEstruturaReceitaDoQuadro() {
	
		$checks = '';
		
		$dadosCnae = new DadosCnae();

		// VARIAVEL QUE CONTROLA SE VEIO DIRETO DE UMA ATIVIDADE QUE NÃO PRECISA DE ESCOLHA DE OPÇÃO DE RETENÇÃO
		if($_SESSION['passou_direto'] == 0){

			// PERCORRENDO A VARIAVEL DE SESSAO QUE CONTEM A ARRAY DE CNAEs PARA MONTAR OS CHECKS E DIVS DA PAGINA
			foreach($_SESSION['cnaes_empresa_mes'] as $cnae){

				$valCampos = array();
				
				// pega os dados do cnae.
				$dados = $dadosCnae->PegaCnae($cnae);
			
				// TIRANDO OS CARACTERES ESPECIAIS PARA MONTAR O NOME DO CAMPO DO FORM ANTERIOR
				$indesCNAE = str_replace("/","",str_replace("-","",$cnae));
								
				// MONTANDO STRING COM OS VALORES DOS CAMPOS QUE VIERAM DO FORM ANTERIOR (lista de checks)
				$checks .= $checksAux = (isset($_REQUEST['marcar_check_'.$indesCNAE]) ? $_REQUEST['marcar_check_'.$indesCNAE].';': "");
				
				// Inclui a denominação do cnae para itens da atividade.
				$valCampos = explode(';',substr($checksAux,0,strlen($checksAux)-1));	
				
				$valCampos['atividade'] = $dados['denominacao'];
				$listaAtividade[] = $valCampos;
			}

		}else{

			$valCampos = array();
			
			$cnae = $_SESSION['cnaes_empresa_mes'][0];

			// pega os dados do cnae.
			$dados = $dadosCnae->PegaCnae($cnae);

			// TIRANDO OS CARACTERES ESPECIAIS PARA MONTAR O NOME DO CAMPO DO FORM ANTERIOR
			$indesCNAE = str_replace("/","",str_replace("-","",$cnae));

			$checks = (isset($_SESSION['marcar_check_'.$indesCNAE]) ? $_REQUEST['marcar_check_'.$indesCNAE].";" : "");
			
			// Inclui a denominação do cnae para itens da atividade.
			$valCampos = explode(';',substr($checks,0,strlen($checks)-1));	
			$valCampos['atividade'] = $dados['denominacao'];
			$listaAtividade[] = $valCampos;
		}
				
		// CRIANDO UM ARRAY COM OS CHECKs QUE VIERAM DO FORM ANTERIOR
		$arr_checks = explode(';',substr($checks,0,strlen($checks)-1));		
		
		$out =	'<span '.$this->DefineVisualizacao(2,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Revenda de mercadorias, exceto para o exterior</b> > <b>Sem substituição tributária/tributação monofásica/antecipação com encerramento de tributação (o substituto tributário do ICMS deve utilizar essa opção)</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(2, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
		
		$out =	'<span '.$this->DefineVisualizacao(3,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Revenda de mercadorias, exceto para o exterior</b> > <b>Com substituição tributária/tributação monofásica/antecipação com encerramento de tributação (o substituído tributário do ICMS deve utilizar essa opção)</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(3, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
		
		$out =	'<span '.$this->DefineVisualizacao(4,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Revenda de mercadorias para o exterior</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(4, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
		
		$out .=	'<span '.$this->DefineVisualizacao(6,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Venda de mercadorias industrializadas pelo contribuinte, exceto para o exterior</b> > <b>Sem substituição tributária/tributação monofásica/antecipação com encerramento de tributação (o substituto tributário do ICMS deve utilizar essa opção)</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(6, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
				
		$out .=	'<span '.$this->DefineVisualizacao(7,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Venda de mercadorias industrializadas pelo contribuinte, exceto para o exterior</b> > <b>Com substituição tributária/tributação monofásica/antecipação com encerramento de tributação (o substituído tributário do ICMS deve utilizar essa opção)</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(7, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
				
		$out .=	'<span '.$this->DefineVisualizacao(8,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Venda de mercadorias industrializadas pelo contribuinte para o exterior</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(8, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
				
		$out .=	'<span '.$this->DefineVisualizacao(9,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Locação de bens móveis, exceto para o exterior</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(9, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
				
		$out .=	'<span '.$this->DefineVisualizacao(10,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Locação de bens móveis para o exterior</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';					
		$out .= $this->ListaAtividades(10, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
				
		$out .=	'<span '.$this->DefineVisualizacao(12,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Prestação de Serviços, exceto para o exterior</b> > <b>Escritórios de serviços contábeis autorizados pela legislação municipal a pagar o ISS em valor fixo em guia do Município</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(12, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
				
		$out .=	'<span '.$this->DefineVisualizacao(13,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Prestação de Serviços, exceto para o exterior</b> > <b>Sujeitos ao fator “r”, sem retenção/substituição tributária de ISS, com ISS devido a outro(s) Município(s)</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(13, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
				
		$out .=	'<span '.$this->DefineVisualizacao(14,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Prestação de Serviços, exceto para o exterior</b> > <b>Sujeitos ao fator “r”, sem retenção/substituição tributária de ISS, com ISS devido ao próprio Município do estabelecimento</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(14, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
				
		$out .=	'<span '.$this->DefineVisualizacao(15,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Prestação de Serviços, exceto para o exterior</b> > <b>Sujeitos ao fator “r”, com retenção/substituição tributária de ISS</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(15, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
				
		$out .=	'<span '.$this->DefineVisualizacao(16,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Prestação de Serviços, exceto para o exterior</b> > <b>Não sujeitos ao fator “r” e tributados pelo Anexo III, sem retenção/substituição tributária de ISS, com ISS devido a outro(s) Município(s)</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(16, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
				
		$out .=	'<span '.$this->DefineVisualizacao(17,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Prestação de Serviços, exceto para o exterior</b> > <b>Não sujeitos ao fator “r” e tributados pelo Anexo III, sem retenção/substituição tributária de ISS, com ISS devido ao próprio Município do estabelecimento</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(17, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
				
		$out .=	'<span '.$this->DefineVisualizacao(18,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Não sujeitos ao fator “r” e tributados pelo Anexo III, com retenção/substituição tributária de ISS</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(18, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
				
		$out .=	'<span '.$this->DefineVisualizacao(19,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Prestação de Serviços, exceto para o exterior</b> > <b>Sujeitos ao Anexo IV, sem retenção/substituição tributária de ISS, com ISS devido a outro(s) Município(s)</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(19, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
				
		$out .=	'<span '.$this->DefineVisualizacao(20,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Prestação de Serviços, exceto para o exterior</b> > <b>Sujeitos ao Anexo IV, sem retenção/substituição tributária de ISS, com ISS devido ao próprio Município do estabelecimento</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(20, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
				
		$out .=	'<span '.$this->DefineVisualizacao(21,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Prestação de Serviços, exceto para o exterior</b> > <b>Sujeitos ao Anexo IV, com retenção/substituição tributária de ISS</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(21, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
				
		$out .=	'<span '.$this->DefineVisualizacao(23,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Prestação de Serviços relacionados nos subitens 7.02, 7.05 e 16.1 da lista anexa à LC 116/2003, exceto para o exterior</b> > <b>Serviços da área da construção civil relacionados nos subitens 7.02 e 7.05 da lista anexa à LC 116/2003 e tributados pelo Anexo III, sem retenção/substituição tributária de ISS, com ISS devido a outro(s) Município(s)</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(23, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
		
		$out .=	'<span '.$this->DefineVisualizacao(24,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Prestação de Serviços relacionados nos subitens 7.02, 7.05 e 16.1 da lista anexa à LC 116/2003, exceto para o exterior</b> > <b>Serviços da área da construção civil relacionados nos subitens 7.02 e 7.05 da lista anexa à LC 116/2003 e tributados pelo Anexo III, sem retenção/substituição tributária de ISS, com ISS devido ao próprio Município do estabelecimento</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(24, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
		
		$out .=	'<span '.$this->DefineVisualizacao(25,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Prestação de Serviços relacionados nos subitens 7.02, 7.05 e 16.1 da lista anexa à LC 116/2003, exceto para o exterior</b> > <b>Serviços da área da construção civil relacionados nos subitens 7.02 e 7.05 da lista anexa à LC 116/2003 e tributados pelo Anexo III, com retenção/substituição tributária de ISS</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(25, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
		
		$out .=	'<span '.$this->DefineVisualizacao(26,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Prestação de Serviços relacionados nos subitens 7.02, 7.05 e 16.1 da lista anexa à LC 116/2003, exceto para o exterior</b> > <b>Serviços da área da construção civil relacionados nos subitens 7.02 e 7.05 da lista anexa à LC 116/2003 e tributados pelo Anexo IV, sem retenção/substituição tributária de ISS, com ISS devido a outro(s) Município(s)</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(26, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
		
		$out .=	'<span '.$this->DefineVisualizacao(27,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Prestação de Serviços relacionados nos subitens 7.02, 7.05 e 16.1 da lista anexa à LC 116/2003, exceto para o exterior</b> > <b>Serviços da área da construção civil relacionados nos subitens 7.02 e 7.05 da lista anexa à LC 116/2003 e tributados pelo Anexo IV, sem retenção/substituição tributária de ISS, com ISS devido ao próprio Município do estabelecimento</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(27, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
		
		$out .=	'<span '.$this->DefineVisualizacao(28,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Serviços da área da construção civil relacionados nos subitens 7.02 e 7.05 da lista anexa à LC 116/2003 e tributados pelo Anexo IV, com retenção/substituição tributária de ISS</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(28, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
		
		$out .=	'<span '.$this->DefineVisualizacao(29,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Prestação de Serviços relacionados nos subitens 7.02, 7.05 e 16.1 da lista anexa à LC 116/2003, exceto para o exterior</b> > <b>Serviços de transporte coletivo municipal rodoviário, metroviário, ferroviário e aquaviário de passageiros, sem retenção/substituição tributária de ISS, com ISS devido a outro(s) Município(s)</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(29, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
		
		$out .=	'<span '.$this->DefineVisualizacao(30,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Prestação de Serviços relacionados nos subitens 7.02, 7.05 e 16.1 da lista anexa à LC 116/2003, exceto para o exterior</b> > <b>Serviços de transporte coletivo municipal rodoviário, metroviário, ferroviário e aquaviário de passageiros, sem retenção/substituição tributária de ISS, com ISS devido ao próprio Município do estabelecimento</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(30, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
		
		$out .=	'<span '.$this->DefineVisualizacao(31,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Prestação de Serviços relacionados nos subitens 7.02, 7.05 e 16.1 da lista anexa à LC 116/2003, exceto para o exterior</b> > <b>Serviços de transporte coletivo municipal rodoviário, metroviário, ferroviário e aquaviário de passageiros, com retenção/substituição tributária de ISS</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(31, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
				
		$out .=	'<span '.$this->DefineVisualizacao(33,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Prestação de Serviços para o exterior</b> > <b>Escritórios de serviços contábeis autorizados pela legislação municipal a pagar o ISS em valor fixo em guia do Município</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(33, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
				
		$out .=	'<span '.$this->DefineVisualizacao(34,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Prestação de Serviços para o exterior</b> > <b>Sujeitos ao fator “r”</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>'; 
		$out .= $this->ListaAtividades(34, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
		
		$out .=	'<span '.$this->DefineVisualizacao(35,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Prestação de Serviços para o exterior</b> > <b>Não sujeitos ao fator “r” e tributados pelo Anexo III </b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(35, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
		
		$out .=	'<span '.$this->DefineVisualizacao(36,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Prestação de Serviços para o exterior</b> > <b>Sujeitos ao Anexo IV</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(36, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
				
		$out .=	'<span '.$this->DefineVisualizacao(38,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Prestação de Serviços relacionados nos subitens 7.02 e 7.05 da lista anexa à LC 116/2003, para o exterior</b> > <b>Serviços da área da construção civil relacionados nos subitens 7.02 e 7.05 da lista anexa à LC 116/2003 e tributados pelo Anexo III</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(38, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
				
		$out .=	'<span '.$this->DefineVisualizacao(39,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Prestação de Serviços relacionados nos subitens 7.02 e 7.05 da lista anexa à LC 116/2003, para o exterior</b> > <b>Serviços da área da construção civil relacionados nos subitens 7.02 e 7.05 da lista anexa à LC 116/2003 e tributados pelo Anexo IV</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(39, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
				
		$out .=	'<span '.$this->DefineVisualizacao(41,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Serviços de comunicação; de transporte intermunicipal e interestadual de carga; e de transporte intermunicipal e interestadual de passageiros autorizados no inciso VI do art. 17 da LC 123, exceto para o exterior</b> > <b>Transporte sem substituição tributária de ICMS (o substituto tributário deve utilizar essa opção)</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(41, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
				
		$out .=	'<span '.$this->DefineVisualizacao(42,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Serviços de comunicação; de transporte intermunicipal e interestadual de carga; e de transporte intermunicipal e interestadual de passageiros autorizados no inciso VI do art. 17 da LC 123, exceto para o exterior</b> > <b>Transporte com substituição tributária de ICMS (o substituído tributário deve utilizar essa opção)</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(42, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
				
		$out .=	'<span '.$this->DefineVisualizacao(43,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Serviços de comunicação; de transporte intermunicipal e interestadual de carga; e de transporte intermunicipal e interestadual de passageiros autorizados no inciso VI do art. 17 da LC 123, exceto para o exterior</b> > <b>Comunicação sem substituição tributária de ICMS (o substituto tributário deve utilizar essa opção)</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(43, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
				
		$out .=	'<span '.$this->DefineVisualizacao(44,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Serviços de comunicação; de transporte intermunicipal e interestadual de carga; e de transporte intermunicipal e interestadual de passageiros autorizados no inciso VI do art. 17 da LC 123, exceto para o exterior</b> > <b>Comunicação com substituição tributária de ICMS (o substituído tributário deve utilizar essa opção)</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(44, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
				
		$out .=	'<span '.$this->DefineVisualizacao(46,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Serviços de comunicação; de transporte intermunicipal e interestadual de carga; e de transporte intermunicipal e interestadual de passageiros autorizados no inciso VI do art. 17 da LC 123, para o exterior</b> > <b>Transporte</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(46, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
				
		$out .=	'<span '.$this->DefineVisualizacao(47,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Serviços de comunicação; de transporte intermunicipal e interestadual de carga; e de transporte intermunicipal e interestadual de passageiros autorizados no inciso VI do art. 17 da LC 123, para o exterior</b> > <b>Comunicação</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(47, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
				
		$out .=	'<span '.$this->DefineVisualizacao(49,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Atividades com incidência simultânea de IPI e de ISS, exceto para o exterior</b> > <b>Sem retenção/substituição tributária de ISS, com ISS devido a outro(s) Município(s)</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(49, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
				
		$out .=	'<span '.$this->DefineVisualizacao(50,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Atividades com incidência simultânea de IPI e de ISS, exceto para o exterior</b> > <b>Sem retenção/substituição tributária de ISS, com ISS devido ao próprio Município do estabelecimento</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(50, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
				
		$out .=	'<span '.$this->DefineVisualizacao(51,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Atividades com incidência simultânea de IPI e de ISS, exceto para o exterior</b> > <b>Com retenção/substituição tributária de ISS</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(51, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.

		$out .=	'<span '.$this->DefineVisualizacao(52,$arr_checks,'block').'>No campo <b>Receita</b> do quadro <b>Atividades com incidência simultânea de IPI e de ISS para o exterior</b> informe a soma das notas fiscais, com estas características, emitidas por você que  referente a(s) atividade(s):</span>';
		$out .= $this->ListaAtividades(52, $listaAtividade);// Chama o método para criar a lista com as denominação do cnae.
		
		// retorna 	
		return $out;
	}
		
	private function DefineVisualizacao($index, $array, $type = ''){
		
		$out = 'style="display: none;"';
		
		switch($type) {
		
			case 'block':
				if(in_array($index, $array)){
					$out = 'style="display: block;"';
				}
				break;
		
			case 'list-item':
				if(in_array($index, $array)){
					$out = 'style="display: list-item;"';
				}
				break;
		}
		
		return $out;
	}

	// Método criado para montar lista de atividades.
	private function ListaAtividades($index, $array){
		
		$out = $li = '';
		
		foreach($array as $val) {
			
			if(in_array($index, $val)) {
				$li .= '<li>'.$val['atividade'].'</li>';
			}
		}
		
		if(!empty($li)){
			$out .= '<ul>'.$li.'</ul><br/>';
		}
		
		return $out;
	}
	
//	// Método criado para conter as atividades econômicas com receita no período de apuração.
//	private function ListaAtividadesEconomicas($index){
//		
//		$out[1] = 'Revenda de mercadorias, exceto para o exterior';
//		$out[2] = 'Sem substituição tributária/tributação monofásica/antecipação com encerramento de tributação (o substituto tributário do ICMS deve utilizar essa opção)';
//		$out[3] = 'Com substituição tributária/tributação monofásica/antecipação com encerramento de tributação (o substituído tributário do ICMS deve utilizar essa opção)';
//		$out[4] = 'Revenda de mercadorias para o exterior';
//		$out[5] = 'Venda de mercadorias industrializadas pelo contribuinte, exceto para o exterior';
//		$out[6] = 'Sem substituição tributária/tributação monofásica/antecipação com encerramento de tributação (o substituto tributário do ICMS deve utilizar essa opção)';
//		$out[7] = 'Com substituição tributária/tributação monofásica/antecipação com encerramento de tributação (o substituído tributário do ICMS deve utilizar essa opção)';
//		$out[8] = 'Venda de mercadorias industrializadas pelo contribuinte para o exterior';
//		$out[9] = 'Locação de bens móveis, exceto para o exterior';
//		$out[10] = 'Locação de bens móveis para o exterior'; 
//		$out[11] = 'Prestação de Serviços, exceto para o exterior';
//		$out[12] = 'Escritórios de serviços contábeis autorizados pela legislação municipal a pagar o ISS em valor fixo em guia do Município';
//		$out[13] = 'Sujeitos ao fator “r”, sem retenção/substituição tributária de ISS, com ISS devido a outro(s) Município(s)';
//		$out[14] = 'Sujeitos ao fator “r”, sem retenção/substituição tributária de ISS, com ISS devido ao próprio Município do estabelecimento';
//		$out[15] = 'Sujeitos ao fator “r”, com retenção/substituição tributária de ISS';
//		$out[16] = 'Não sujeitos ao fator “r” e tributados pelo Anexo III, sem retenção/substituição tributária de ISS, com ISS devido a outro(s) Município(s)';
//		$out[17] = 'Não sujeitos ao fator “r” e tributados pelo Anexo III, sem retenção/substituição tributária de ISS, com ISS devido ao próprio Município do estabelecimento';
//		$out[18] = 'Não sujeitos ao fator “r” e tributados pelo Anexo III, com retenção/substituição tributária de ISS';
//		$out[19] = 'Sujeitos ao Anexo IV, sem retenção/substituição tributária de ISS, com ISS devido a outro(s) Município(s)';
//		$out[20] = 'Sujeitos ao Anexo IV, sem retenção/substituição tributária de ISS, com ISS devido ao próprio Município do estabelecimento';
//		$out[21] = 'Sujeitos ao Anexo IV, com retenção/substituição tributária de ISS';
//		$out[22] = 'Prestação de Serviços relacionados nos subitens 7.02, 7.05 e 16.1 da lista anexa à LC 116/2003, exceto para o exterior';
//		$out[23] = 'Serviços da área da construção civil relacionados nos subitens 7.02 e 7.05 da lista anexa à LC 116/2003 e tributados pelo Anexo III, sem retenção/substituição tributária de ISS, com ISS devido a outro(s) Município(s)';
//		$out[24] = 'Serviços da área da construção civil relacionados nos subitens 7.02 e 7.05 da lista anexa à LC 116/2003 e tributados pelo Anexo III, sem retenção/substituição tributária de ISS, com ISS devido ao próprio Município do estabelecimento';
//		$out[25] = 'Serviços da área da construção civil relacionados nos subitens 7.02 e 7.05 da lista anexa à LC 116/2003 e tributados pelo Anexo III, com retenção/substituição tributária de ISS';
//		$out[26] = 'Serviços da área da construção civil relacionados nos subitens 7.02 e 7.05 da lista anexa à LC 116/2003 e tributados pelo Anexo IV, sem retenção/substituição tributária de ISS, com ISS devido a outro(s) Município(s)';
//		$out[27] = 'Serviços da área da construção civil relacionados nos subitens 7.02 e 7.05 da lista anexa à LC 116/2003 e tributados pelo Anexo IV, sem retenção/substituição tributária de ISS, com ISS devido ao próprio Município do estabelecimento';
//		$out[28] = 'Serviços da área da construção civil relacionados nos subitens 7.02 e 7.05 da lista anexa à LC 116/2003 e tributados pelo Anexo IV, com retenção/substituição tributária de ISS';
//		$out[29] = 'Serviços de transporte coletivo municipal rodoviário, metroviário, ferroviário e aquaviário de passageiros, sem retenção/substituição tributária de ISS, com ISS devido a outro(s) Município(s)';
//		$out[30] = 'Serviços de transporte coletivo municipal rodoviário, metroviário, ferroviário e aquaviário de passageiros, sem retenção/substituição tributária de ISS, com ISS devido ao próprio Município do estabelecimento';
//		$out[31] = 'Serviços de transporte coletivo municipal rodoviário, metroviário, ferroviário e aquaviário de passageiros, com retenção/substituição tributária de ISS';
//		$out[32] = 'Prestação de Serviços para o exterior'; 
//		$out[33] = 'Escritórios de serviços contábeis autorizados pela legislação municipal a pagar o ISS em valor fixo em guia do Município';
//		$out[34] = 'Sujeitos ao fator “r”'; 
//		$out[35] = 'Não sujeitos ao fator “r” e tributados pelo Anexo III ';
//		$out[36] = 'Sujeitos ao Anexo IV';
//		$out[37] = 'Prestação de Serviços relacionados nos subitens 7.02 e 7.05 da lista anexa à LC 116/2003, para o exterior';
//		$out[38] = 'Serviços da área da construção civil relacionados nos subitens 7.02 e 7.05 da lista anexa à LC 116/2003 e tributados pelo Anexo III';
//		$out[39] = 'Serviços da área da construção civil relacionados nos subitens 7.02 e 7.05 da lista anexa à LC 116/2003 e tributados pelo Anexo IV';
//		$out[40] = 'Serviços de comunicação; de transporte intermunicipal e interestadual de carga; e de transporte intermunicipal e interestadual de passageiros autorizados no inciso VI do art. 17 da LC 123, exceto para o exterior';
//		$out[41] = 'Transporte sem substituição tributária de ICMS (o substituto tributário deve utilizar essa opção)';
//		$out[42] = 'Transporte com substituição tributária de ICMS (o substituído tributário deve utilizar essa opção)';
//		$out[43] = 'Comunicação sem substituição tributária de ICMS (o substituto tributário deve utilizar essa opção)';
//		$out[44] = 'Comunicação com substituição tributária de ICMS (o substituído tributário deve utilizar essa opção)';
//		$out[45] = 'Serviços de comunicação; de transporte intermunicipal e interestadual de carga; e de transporte intermunicipal e interestadual de passageiros autorizados no inciso VI do art. 17 da LC 123, para o exterior';
//		$out[46] = 'Transporte';
//		$out[47] = 'Comunicação';
//		$out[48] = 'Atividades com incidência simultânea de IPI e de ISS, exceto para o exterior';
//		$out[49] = 'Sem retenção/substituição tributária de ISS, com ISS devido a outro(s) Município(s)';
//		$out[50] = 'Sem retenção/substituição tributária de ISS, com ISS devido ao próprio Município do estabelecimento';
//		$out[51] = 'Com retenção/substituição tributária de ISS';
//		$out[52] = 'Atividades com incidência simultânea de IPI e de ISS para o exterior';
//		
//		// Retorna o texto referente atividades econômicas com receita no período de apuração
//		return $out[$index];	
//	}

}