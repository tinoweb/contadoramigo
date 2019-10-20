<?php 
	class Gerar_DRE{
		private $vendas_de_mercadorias_produtos_e_servicos;
		private $vendas_de_mercadorias;
		private $vendas_de_produtos;
		private $vendas_de_servicos;
		private $deducoes_com_impostos_devolucoes_e_descontos;
		private $incondicionais;
		private $receita_liquida;
		private $custo_das_vendas;
		private $custo_das_mercadorias_vendidas;
		private $custo_dos_produtos_vendidos;
		private $custo_dos_servicos_prestados;
		private $lucro_bruto;
		private $despesas_operacionais;
		private $despesas_com_pessoal;
		private $despesas_administrativas;
		private $despesas_de_vendas;
		private $despesas_tributarias;
		private $depreciacao_e_amortizacao;
		private $perdas_diversas;
		private $resultado_financeiro;
		private $receitas_financeiras;
		private $despesas_financeiras;
		private $outras_receitas;
		private $outras_despesas;
		private $resultado_antes_das_despesas_com_tributos_sobre_o_lucro;
		private $despesa_com_imposto_de_renda_da_pessoa_juridica;
		private $despesa_com_contribuicao_social;
		private $resultado_liquido_do_exercicio;
	
		private $array_total;
		private $ano;

		private $cnae;
		private $outras_despesas_gerais;
		private $tipo_user_pagamento;
		private $id_user_pagamento;
		private $outras_receitas_e_despesas_operacionais;
		
		function getAgrupamentoDespesas($string){
			
			$cnae = $this->getcnae();

			if( $string == 'Aluguel' ) return "despesas_administrativas";
	
			if( $string == 'Comissões' ) return "custo_dos_servicos_prestados";
				
			if( $string == 'Condomínio' ) return "despesas_administrativas";
			if( $string == 'Contador' ) return "despesas_administrativas";
			if( $string == 'Correios' ) return "despesas_administrativas";
			if( $string == 'Cursos e treinamentos' ) return "despesas_administrativas";
			if( $string == 'Taxas e Comissões Bancárias' ) return "despesas_financeiras";		
			if( $string == 'Juros do Cheque especial' ) return "despesas_financeiras";	
			if( $string == 'Energia elétrica' ) return "despesas_administrativas";

			if( $string == 'Estorno Serviços' ) return "deducoes_com_impostos_devolucoes_e_descontos";
			if( $string == 'Impostos e encargos' ) return "deducoes_com_impostos_devolucoes_e_descontos";
	
			if( $string == 'Limpeza' ) return "despesas_administrativas";
			if( $string == 'Manutenção de equipamentos' ) return "custo_dos_servicos_prestados";
	
			if( $string == 'Marketing e publicidade' ) return "outras_despesas";
			if( $string == 'Material de escritório' ) return "despesas_administrativas";

			if( $string == 'Segurança' ) return "despesas_administrativas";
			if( $string == 'Seguros' ) return "despesas_administrativas";
			if( $string == 'Telefone' ) return "despesas_administrativas";
			if( $string == 'Transportadora / Motoboy' ) return "despesas_administrativas";
			
			
			if( $string == 'Viagens e deslocamentos' ) return "despesas_administrativas";
			
			if( $string == 'Reembolso de despesas' ) return "despesas_com_pessoal";
			if( $string == 'Outros' ) return "outras_despesas";
			
			// Item de acordo com CNAE.
			if( $string == 'Água' ) {
				
				if($this->defineDREDeAcordoCNAE($cnae, $string) == 1){
					return "custo_dos_produtos_vendidos";	
				} else {
					return "despesas_administrativas";	
				}
			}			
			if( $string == 'Combustível' ) {
				
				if($this->defineDREDeAcordoCNAE($cnae, $string) == 1) {
					return "custo_dos_produtos_vendidos";
				} else { 
					return "despesas_administrativas";
				}
			}
			if( $string == 'Variação Cambial Passiva' ) {
				
				if($this->defineDREDeAcordoCNAE($cnae, $string) == 1) {
					return "custo_dos_servicos_prestados";	
				} else {
					return "despesas_financeiras";	
				}	
			}	
			if( $string == 'Manutenção de Veículos' ) {
				
				if($this->defineDREDeAcordoCNAE($cnae, $string) == 1) {
					return "custo_dos_servicos_prestados";	
				} else {
					return "despesas_financeiras";	
				}	
			}			
			if( $string == 'Internet' ) {
	
				if($this->defineDREDeAcordoCNAE($cnae, $string) == 1){
					return "custo_dos_produtos_vendidos";
				} else {
					return "despesas_administrativas";
				}
			}
			if( $string == 'Aluguel de softwares' ) {
				
				if($this->defineDREDeAcordoCNAE($cnae, $string) == 1){
					return "custo_dos_servicos_prestados";
				} else {
					return "despesas_administrativas";
				}
			}
			
			//Define a DRE de acordo Operacional ou administrativo.		
			if( $string == 'Vale-Refeição' ){
				
				switch($this->definirCondicaoValeRefeicao()) {
					
					case 3:
						return array('custo_dos_servicos_prestados','despesas_administrativas');
						break;	
					
					case 2:
						return "custo_dos_servicos_prestados";
						break;	
					
					default:
						return "despesas_administrativas";
						break;	
				}
			}
			if( $string == 'Estagiários' ) {
		
				switch($this->definirCondicaoValeRefeicao()) {
					
					case 3:
						return array('custo_dos_servicos_prestados','despesas_administrativas');
						break;	
					
					case 2:
						return "custo_dos_servicos_prestados";
						break;
						
					default:
						return "despesas_administrativas";
						break;
				}
			}	
			if( $string == 'Pgto. a autônomos e fornecedores' ) {
	
				switch($this->definirCondicaoValeRefeicao()) {
					
					case 3:
						return array('custo_dos_servicos_prestados','despesas_administrativas');
						break;	
					
					case 2:
						return "custo_dos_servicos_prestados";
						break;
						
					default:
						return "despesas_administrativas";
						break;
				}
			}
			if( $string == 'Pagto. de Salários' ) {
				
				switch($this->definirCondicaoValeRefeicao()) {
					
					case 3:
						return array('custo_dos_servicos_prestados','despesas_administrativas');
						break;	
					
					case 2:
						return "custo_dos_servicos_prestados";
						break;
						
					default:
						return "despesas_administrativas";
						break;
				}
			} 
			if( $string == 'Pró-Labore' ) {
				
				switch($this->definirCondicaoValeRefeicao()) {
					
					case 3:
						return array('custo_dos_servicos_prestados','despesas_administrativas');
						break;	
					
					case 2:
						return "custo_dos_servicos_prestados";
						break;
						
					default:
						return "despesas_administrativas";
						break;
				}
			}

			if( $string == 'Vale-Transporte' ) {
				
				switch($this->definirCondicaoValeRefeicao()) {
					
					case 3:
						return array('custo_dos_servicos_prestados','despesas_administrativas');
						break;	
					
					case 2:
						return "custo_dos_servicos_prestados";
						break;
						
					default:
						return "despesas_administrativas";
						break;
				}
			}

			// Preserva ate verificar no banco.
			if( $string == 'Estagiários' ) return "despesas_com_pessoal";
			if( $string == 'Juros' ) return "despesas_financeiras";
			if( $string == 'Licença de Software' ) return "despesas_administrativas";
			if( $string == 'Despesas bancárias' ) return "despesas_financeiras";
			if( $string == 'Licença ou aluguel de softwares' ) return "despesas_administrativas";
			if( $string == 'Pgto. de salários' ) return "despesas_com_pessoal";
			if( $string == 'Pgto. de salários' ) return "despesas_com_pessoal";

			return false;
		}		
		
		// Define o DRE de acordo com o CNAE
		function defineDREDeAcordoCNAE($cnae, $categoria){
			
			$out = 2;
			
			$consulta = mysql_query("SELECT * FROM livro_diario_custo_despesa WHERE cnae = '".cnae."' AND categoria = '".categoria."' ");
			$objeto_consulta = mysql_fetch_array($consulta);

			if( $objeto_consulta['tipo'] == 'custo' ) {
				$out = 1;
			}
				
			return $out;					
		}		
		
		//Define o DRE de deacordo com se e Operacional ou Administrativo.
		function definirCondicaoValeRefeicao(){
			
			switch($this->verificarAdministrativoOperacional()) {
				
				case 'Exclusivamente Administrativas':
					return 1;
					break;
						
				case 'Administrativas e Operacionais':
					return 3;
					break;
					
				case 'Exclusivamente Operacionais':
					return 2;
					break;	
					
				default:	
					return 1;
					break;
			}
		}
		
		//Retorna o tipo de usuario que recebeu o pagamento.
		function verificarAdministrativoOperacional(){
	
			if( $this->gettipo_user_pagamento() == 'estagiario' )
				return $this->getSqlUserPagamento('estagiarios','id');
			if( $this->gettipo_user_pagamento() == 'autonomo' )
				return $this->getSqlUserPagamento('dados_autonomos','id');
			if( $this->gettipo_user_pagamento() == 'socio' )
				return $this->getSqlUserPagamento('dados_do_responsavel','idSocio');
			if( $this->gettipo_user_pagamento() == 'fornecedor' )
				return $this->getSqlUserPagamento('dados_pj','id');
		}
		
		//Método que devolve o tipo do usuário.
		function getSqlUserPagamento($tabela,$campo){
			
			$out = 'Exclusivamente Administrativas';
	
			if(!empty($this->getid_user_pagamento())) {
				$consulta = mysql_query("SELECT * FROM ".$tabela." WHERE ".$campo." = '".$this->getid_user_pagamento()."' ");
				$objeto_consulta = mysql_fetch_array($consulta);
				$out = $objeto_consulta['tipo'];
			}
			
			return $out;
		}				
		
		function getClientes(){
			$array = array();
			$consulta = mysql_query("SELECT * FROM dados_clientes WHERE id_login = '".$_SESSION['id_empresaSecao']."' ");
			while( $objeto_consulta = mysql_fetch_array($consulta) ){
				$array[] = $objeto_consulta['apelido'];
			}
			return $array;
		}
		function getAgrupamentoReceitas($string){
			$clientes = $this->getClientes();
			
			foreach ($clientes as $value) {
				if( $string == $value ) {
					return "vendas_de_servicos";
				}
			}
			if( $string == 'Rendimentos de aplicação' ) return "receitas_financeiras";
			if( $string == 'Serviços prestados em geral' ) return "vendas_de_servicos";
			if( $string == 'Variação Cambial Ativa' )	return "receitas_financeiras"; 
			if( $string == 'Vendas de Bens Patrimoniais' )	return "outras_receitas";
			if( $string == 'Outros' ) return "outras_receitas";
			
			return false;
		}
		function getValorItem($item){
			if( $item['entrada'] > 0 )
				return $item['entrada'];
			if( $item['saida'] > 0 )
				return (-1)*$item['saida'];
		}
		function setItemCategoria($string,$valor){
			$this->array_total[$string] = $this->array_total[$string] + $valor;
		}
		function setItemReceita($item){
			//$this->setItemCategoria($this->getAgrupamentoReceitas($item['categoria']),$this->getValorItem($item));
		
			$categoria = $this->getAgrupamentoReceitas($item['categoria']);
			$valor = $this->getValorItem($item);
				
			if(is_array($categoria)) {
	
				foreach( $categoria as $cant ) {					
					$this->setItemCategoria($cant, $valDiv);
				}
			} else {	
				$this->setItemCategoria(	$categoria, $valor);
			}
		}
		function setItemDespesa($item){
			//$this->setItemCategoria($this->getAgrupamentoDespesas($item['categoria']),$this->getValorItem($item));
			
			$categoria = $this->getAgrupamentoDespesas($item['categoria']);
			$valor = $this->getValorItem($item);
	
			if(is_array($categoria)) {
	
				foreach( $categoria as $cant ) {
					$this->setItemCategoria($cant, $valDiv);
				}
			} else {	
				$this->setItemCategoria(	$categoria, $valor);
			}
		}
		function setItem($item){
		
			if( $this->getAgrupamentoReceitas($item['categoria']) != false && $item['entrada'] > 0 ) {
				$this->setItemReceita($item);
			}
			
			if( $this->getAgrupamentoDespesas($item['categoria']) != false && $item['saida'] > 0 ) {
				$this->setItemDespesa($item);
			}
				
		}
		function getItensLivroCaixa(){
			$livro_caixa = mysql_query("SELECT * FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa WHERE YEAR(data) = '".$this->ano."' AND categoria != 'Repasse a terceiros' ");
			while( $item = mysql_fetch_array($livro_caixa) ){
				$this->settipo_user_pagamento($this->ifPagamentoTipo($item));
				$this->setid_user_pagamento($this->ifPagamento($item));
				$this->setItem($item);
			}
		}
		function ifPagamento($item){
			$livro_caixa_emprestimos = mysql_query("SELECT * FROM dados_pagamentos WHERE id_login = '".$_SESSION['id_empresaSecao']."' AND idLivroCaixa = '".$item['id']."' ");
			$objeto_consulta = mysql_fetch_array($livro_caixa_emprestimos);
			if( mysql_num_rows($livro_caixa_emprestimos) > 0 ){
				if( $objeto_consulta['id_autonomo'] != 0 )
					return $objeto_consulta['id_autonomo'];
				if( $objeto_consulta['id_socio'] != 0 )
					return $objeto_consulta['id_socio'];
				if( $objeto_consulta['id_lucro'] != 0 )
					return $objeto_consulta['id_lucro'];
				if( $objeto_consulta['id_estagiario'] != 0 )
					return $objeto_consulta['id_estagiario'];
				if( $objeto_consulta['id_pj'] != 0 )
					return $objeto_consulta['id_pj'];
			}
		}		
		function ifPagamentoTipo($item){
	
			$livro_caixa_emprestimos = mysql_query("SELECT * FROM dados_pagamentos WHERE id_login = '".$_SESSION['id_empresaSecao']."' AND idLivroCaixa = '".$item['id']."' ");
			$objeto_consulta = mysql_fetch_array($livro_caixa_emprestimos);
			if( mysql_num_rows($livro_caixa_emprestimos) > 0 ){
				if( $objeto_consulta['id_autonomo'] != 0 )
					return 'autonomo';
				if( $objeto_consulta['id_socio'] != 0 )
					return 'socio';
				if( $objeto_consulta['id_estagiario'] != 0 )
					return 'estagiario';
				if( $objeto_consulta['id_pj'] != 0 )
					return 'fornecedor';
			}
		}
		function calcularItens(){
			
			//Calcula o total das vendas
			$this->array_total['vendas_de_mercadorias_produtos_e_servicos'] = 
				$this->array_total['vendas_de_mercadorias'] 				+
				$this->array_total['vendas_de_produtos'] 					+
				$this->array_total['vendas_de_servicos'];

			//Calcula a receita liquida
			$this->array_total['receita_liquida'] = 
				$this->array_total['vendas_de_mercadorias_produtos_e_servicos'] +
				$this->array_total['deducoes_com_impostos_devolucoes_e_descontos'];

			//Calcula o custo das vendas
			$this->array_total['custo_das_vendas'] 						= 
				$this->array_total['custo_das_mercadorias_vendidas']	+
				$this->array_total['custo_dos_produtos_vendidos'] 		+
				$this->array_total['custo_dos_servicos_prestados'];
			
			//Calcula o lucro bruto			
			$this->array_total['lucro_bruto'] 			=		 
				$this->array_total['receita_liquida']	+
				$this->array_total['custo_das_vendas'];

			//Calcula as despesas operacionais
			$this->array_total['despesas_operacionais'] 		= 
				$this->array_total['despesas_com_pessoal'] 		+
				$this->array_total['despesas_administrativas'] 	+
				$this->array_total['despesas_de_vendas'] 		+
				$this->array_total['despesas_tributarias'] 		+
				$this->array_total['depreciacao_e_amortizacao']	+
				$this->array_total['perdas_diversas'];

			//Calcula o resultado financceiro
			$this->array_total['resultado_financeiro'] 		= 
				$this->array_total['receitas_financeiras'] 	+
				$this->array_total['despesas_financeiras'];

			//Calcular o resultado liquido do exercicio
			$this->array_total['resultado_liquido_do_exercicio'] = 
				$this->array_total['lucro_bruto']				+
				$this->array_total['despesas_operacionais']		+
				$this->array_total['resultado_financeiro']		+
				$this->array_total['outras_receitas'] 			+
				$this->array_total['outras_despesas'];
		
			// Calcula 	
			$this->array_total['outras_despesas_gerais'] = $this->array_total['despesas_com_pessoal']; 
			$this->array_total['outras_despesas_gerais'] += $this->array_total['despesas_tributarias'];
			$this->array_total['outras_despesas_gerais'] += $this->array_total['perdas_diversas'];
			$this->array_total['outras_despesas_gerais'] += $this->array_total['depreciacao_e_amortizacao'];	
		
			$this->array_total['outras_receitas_e_despesas_operacionais'] = ($this->array_total['outras_receitas'] + $this->array_total['outras_despesas']);
		
		}
		function gerarDre(){
			$this->getItensLivroCaixa();
			$this->calcularItens();
			// echo '<pre>';
			// var_dump($this->array_total);
			// echo '</pre>';
		}

		function gerarTabelas(){
	
			$outrasDespesasGerais = $this->array_total['despesas_com_pessoal'] + $this->array_total['despesas_tributarias'] + $this->array_total['perdas_diversas'] + $this->array_total['depreciacao_e_amortizacao'];
			
			$string = '
				<table border="0" cellspacing="2" cellpadding="4" style="width:840px;font-size: 11px;margin-top: -2px;float:left;margin-bottom:30px;">
					<tbody>
						<tr>
			       			<th style="width:650px;font-size: 12px;">Descrição</th>
			       			<th style="width:180px;font-size: 12px;">R$</th>
			    		</tr>	
			    		<tr>
							<td class="td_calendario agrupado titulo_tabela">VENDAS DE PRODUTOS, MERCADORIAS E SERVIÇOS </td>
							<td class="td_calendario agrupado titulo_tabela"></td>
						</tr>
						<tr>
							<td class="td_calendario">Vendas de Produtos, Mercadorias e Serviços</td>
							<td class="td_calendario">'.$this->getvendas_de_mercadorias_produtos_e_servicos().'</td>
						</tr>
						<tr>
							<td class="td_calendario">(-) Deduções de Tributos, Abatimentos e Devoluções</td>
							<td style="width:180px;" class="td_calendario">'.str_replace('-','',$this->getdeducoes_com_impostos_devolucoes_e_descontos()).'</td>
						</tr>
						<tr>
							<td style="width:650px;" class="td_calendario agrupado titulo_tabela">= RECEITA LÍQUIDA</td>
							<td style="width:180px;" class="td_calendario agrupado titulo_tabela">'.$this->getreceita_liquida().'</td>
						</tr>
					</tbody>
				</table>
			';
			$string .= '
				<table border="0" cellspacing="2" cellpadding="4" style="width:840px;font-size: 11px;margin-top: -2px;float:left;margin-bottom:30px;">
					<tbody>
						<tr>
							<td style="width:650px;" class="td_calendario agrupado titulo_tabela">(-) CUSTO DAS VENDAS</td>
							<td style="width:180px;" class="td_calendario agrupado titulo_tabela"></td>
						</tr>
						<tr>
							<td class="td_calendario">Custo dos Produtos, Mercadorias e Serviços</td>
							<td class="td_calendario">'.str_replace('-','',$this->getcusto_das_vendas()).'</td>
						</tr>
					</tbody>
				</table>
			';
			$string .= '
				<table border="0" cellspacing="2" cellpadding="4" style="width:840px;font-size: 11px;margin-top: -2px;float:left;margin-bottom:30px;">
					<tbody>
						<tr>
							<td style="width:650px;" class="td_calendario agrupado titulo_tabela">= LUCRO BRUTO</td>
							<td style="width:180px;" class="td_calendario agrupado titulo_tabela">'.str_replace('-','',$this->getlucro_bruto()).'</td>
						</tr>
					</tbody>
				</table>
			';
			
			$string .= '
				<table border="0" cellspacing="2" cellpadding="4" style="width:840px;font-size: 11px;margin-top: -2px;float:left;margin-bottom:30px;">
					<tbody>
						
						<tr>
							<td style="width:650px;" class="td_calendario agrupado titulo_tabela">(-) DESPESAS OPERACIONAIS</td>
							<td style="width:180px;" class="td_calendario agrupado titulo_tabela"></td>
						</tr>
						<tr>
							<td class="td_calendario">Despesas Administrativas</td>
							<td class="td_calendario">'.str_replace('-','',$this->getdespesas_administrativas()).'</td>
						</tr>
						<tr>
							<td class="td_calendario">Despesas com Vendas</td>
							<td class="td_calendario">'.str_replace('-','',$this->getdespesas_de_vendas()).'</td>
						</tr>
						<tr>
							<td class="td_calendario">Outras Despesas Gerais</td>
							<td class="td_calendario">'.str_replace('-','',$this->getoutras_despesas_gerais()).'</td>
						</tr>
					</tbody>
				</table>
			';
			
			$string .= '
				<table border="0" cellspacing="2" cellpadding="4" style="width:840px;font-size: 11px;margin-top: -2px;float:left;margin-bottom:30px;">
					<tbody>
						<tr>
							<td style="width:650px;" class="td_calendario agrupado titulo_tabela">= RESULTADO OPERACIONAL ANTES DO RESULTADO
FINANCEIRO</td>
							<td style="width:180px;" class="td_calendario agrupado titulo_tabela">'.str_replace('-','',$this->getdespesas_operacionais()).'</td>
						</tr>
					</tbody>
				</table>
			';			
			
			$string .= '
				<table border="0" cellspacing="2" cellpadding="4" style="width:840px;font-size: 11px;margin-top: -2px;float:left;margin-bottom:30px;">
					<tbody>
						<tr>
							<td style="width:650px;" class="td_calendario agrupado titulo_tabela">(+/-) RESULTADO FINANCEIRO</td>
							<td style="width:180px;" class="td_calendario agrupado titulo_tabela">'.str_replace('-','',$this->getresultado_financeiro()).'</td>
						</tr>
						<tr>
							<td class="td_calendario">Receitas Financeiras</td>
							<td class="td_calendario">'.str_replace('-','',$this->getreceitas_financeiras()).'</td>
						</tr>
						<tr>
							<td class="td_calendario">(-) Despesas Financeiras</td>
							<td class="td_calendario">'.str_replace('-','',$this->getdespesas_financeiras()).'</td>
						</tr>
					</tbody>
				</table>
			';
			
			$string .= '
				<table border="0" cellspacing="2" cellpadding="4" style="width:840px;font-size: 11px;margin-top: -2px;float:left;margin-bottom:30px;">
					<tbody>
						<tr>
							<td style="width:650px;" class="td_calendario agrupado titulo_tabela">(+/-) OUTRAS RECEITAS E DESPESAS OPERACIONAIS</td>
							<td style="width:180px;" class="td_calendario agrupado titulo_tabela">'.$this->getoutras_receitas_e_despesas_operacionais().'</td>
						</tr>
					</tbody>
				</table>
			';
			
			$string .= '
				<table border="0" cellspacing="2" cellpadding="4" style="width:840px;font-size: 11px;margin-top: -2px;float:left;margin-bottom:30px;">
					<tbody>
						<tr>
							<td style="width:650px;" class="td_calendario agrupado titulo_tabela">= RESULTADO ANTES DAS DESPESAS COM TRIBUTOS
SOBRE O LUCRO</td>
							<td style="width:180px;" class="td_calendario agrupado titulo_tabela">0,00</td>
						</tr>
						<tr>
							<td class="td_calendario">(-) Despesa com Contribuição Social (*)</td>
							<td class="td_calendario">0,00</td>
						</tr>
						<tr>
							<td class="td_calendario">(-) Despesa com Imposto de Renda da Pessoa Jurídica (*)</td>
							<td class="td_calendario">0,00</td>
						</tr>						
					</tbody>
				</table>
			';
			
			$string .= '
				<table border="0" cellspacing="2" cellpadding="4" style="width:840px;font-size: 11px;margin-top: -2px;float:left;margin-bottom:30px;">
					<tbody>
						<tr>
							<td style="width:650px;" class="td_calendario agrupado titulo_tabela">= RESULTADO LÍQUIDO DO PERÍODO</td>
							<td style="width:180px;" class="td_calendario agrupado titulo_tabela total_dre">'.$this->getresultado_liquido_do_exercicio().'</td>
						</tr>
						
					</tbody>
				</table>
			';
			return $string;
		}
	
		function __construct(){
			$this->setvendas_de_mercadorias_produtos_e_servicos(0);
			$this->setvendas_de_mercadorias(0);
			$this->setvendas_de_produtos(0);
			$this->setvendas_de_servicos(0);
			$this->setdeducoes_com_impostos_devolucoes_e_descontos(0);
			$this->setreceita_liquida(0);
			$this->setcusto_das_vendas(0);
			$this->setcusto_das_mercadorias_vendidas(0);
			$this->setcusto_dos_produtos_vendidos(0);
			$this->setcusto_dos_servicos_prestados(0);
			$this->setlucro_bruto(0);
			$this->setdespesas_operacionais(0);
			$this->setdespesas_com_pessoal(0);
			$this->setdespesas_administrativas(0);
			$this->setdespesas_de_vendas(0);
			$this->setdespesas_tributarias(0);
			$this->setdepreciacao_e_amortizacao();
			$this->setperdas_diversas(0);
			$this->setresultado_financeiro(0);
			$this->setreceitas_financeiras(0);
			$this->setdespesas_financeiras(0);
			$this->setoutras_receitas(0);
			$this->setoutras_despesas(0);
			$this->setresultado_liquido_do_exercicio(0);
			$this->setoutras_despesas_gerais(0);
		}
		function getano(){
			return $this->ano;
		}
		function setano($string){
			$this->ano = $string;
		}
		function getCnae() {
			return $this->cnae;
		}
		function setCnae($string) {
			$this->cnae = $string;
		}
		function gettipo_user_pagamento(){
			return $this->tipo_user_pagamento;
		}
		function settipo_user_pagamento($string){
			$this->tipo_user_pagamento = $string;
		}
		function getid_user_pagamento(){
			return $this->id_user_pagamento;
		}
		function setid_user_pagamento($string){
			$this->id_user_pagamento = $string;
		}
		function getoutras_receitas_e_despesas_operacionais() {
			return number_format($this->array_total['outras_receitas_e_despesas_operacionais'], 2 , ',' , '.' );
		}
		function setoutras_receitas_e_despesas_operacionais($string) {
			$this->array_total['outras_receitas_e_despesas_operacionais'] = string;
		}		
		function getoutras_despesas_gerais() {
			return number_format($this->array_total['outras_despesas_gerais'], 2 , ',' , '.' );
		}
		function setoutras_despesas_gerais($string) {
			$this->array_total['outras_despesas_gerais'] = string;
		}		
		function getvendas_de_mercadorias_produtos_e_servicos(){
			return number_format( $this->array_total['vendas_de_mercadorias_produtos_e_servicos'] , 2 , ',' , '.' );
		}
		function setvendas_de_mercadorias_produtos_e_servicos($string){
			$this->array_total['vendas_de_mercadorias_produtos_e_servicos'] = $string;
		}
		function getvendas_de_mercadorias(){
			return number_format( $this->array_total['vendas_de_mercadorias'] , 2 , ',' , '.' );
		}
		function setvendas_de_mercadorias($string){
			$this->array_total['vendas_de_mercadorias'] = $string;
		}
		function getvendas_de_produtos(){
			return number_format( $this->array_total['vendas_de_produtos'] , 2 , ',' , '.' );
		}
		function setvendas_de_produtos($string){
			$this->array_total['vendas_de_produtos'] = $string;
		}
		function getvendas_de_servicos(){
			return number_format( $this->array_total['vendas_de_servicos'] , 2 , ',' , '.' );
		}
		function setvendas_de_servicos($string){
			$this->array_total['vendas_de_servicos'] = $string;
		}
		function getdeducoes_com_impostos_devolucoes_e_descontos(){
			return number_format( $this->array_total['deducoes_com_impostos_devolucoes_e_descontos'] , 2 , ',' , '.' );
		}
		function setdeducoes_com_impostos_devolucoes_e_descontos($string){
			$this->array_total['deducoes_com_impostos_devolucoes_e_descontos'] = $string;
		}
		function getreceita_liquida(){
			return number_format( $this->array_total['receita_liquida'] , 2 , ',' , '.' );
		}
		function setreceita_liquida($string){
			$this->array_total['receita_liquida'] = $string;
		}
		function getcusto_das_vendas(){
			return number_format( $this->array_total['custo_das_vendas'] , 2 , ',' , '.' );
		}
		function setcusto_das_vendas($string){
			$this->array_total['custo_das_vendas'] = $string;
		}
		function getcusto_das_mercadorias_vendidas(){
			return number_format( $this->array_total['custo_das_mercadorias_vendidas'] , 2 , ',' , '.' );
		}
		function setcusto_das_mercadorias_vendidas($string){
			$this->array_total['custo_das_mercadorias_vendidas'] = $string;
		}
		function getcusto_dos_produtos_vendidos(){
			return number_format( $this->array_total['custo_dos_produtos_vendidos'] , 2 , ',' , '.' );
		}
		function setcusto_dos_produtos_vendidos($string){
			$this->array_total['custo_dos_produtos_vendidos'] = $string;
		}
		function getcusto_dos_servicos_prestados(){
			return number_format( $this->array_total['custo_dos_servicos_prestados'] , 2 , ',' , '.' );
		}
		function setcusto_dos_servicos_prestados($string){
			$this->array_total['custo_dos_servicos_prestados'] = $string;
		}
		function getlucro_bruto(){
			return number_format( $this->array_total['lucro_bruto'] , 2 , ',' , '.' );
		}
		function setlucro_bruto($string){
			$this->array_total['lucro_bruto'] = $string;
		}
		function getdespesas_operacionais(){
			return number_format( $this->array_total['despesas_operacionais'] , 2 , ',' , '.' );
		}
		function setdespesas_operacionais($string){
			$this->array_total['despesas_operacionais'] = $string;
		}
		function getdespesas_com_pessoal(){
			return number_format( $this->array_total['despesas_com_pessoal'] , 2 , ',' , '.' );
		}
		function setdespesas_com_pessoal($string){
			$this->array_total['despesas_com_pessoal'] = $string;
		}
		function getdespesas_administrativas(){
			return number_format( $this->array_total['despesas_administrativas'] , 2 , ',' , '.' );
		}
		function setdespesas_administrativas($string){
			$this->array_total['despesas_administrativas'] = $string;
		}
		function getdespesas_de_vendas(){
			return number_format( $this->array_total['despesas_de_vendas'] , 2 , ',' , '.' );
		}
		function setdespesas_de_vendas($string){
			$this->array_total['despesas_de_vendas'] = $string;
		}
		function getdespesas_tributarias(){
			return number_format( $this->array_total['despesas_tributarias'] , 2 , ',' , '.' );
		}
		function setdespesas_tributarias($string){
			$this->array_total['despesas_tributarias'] = $string;
		}
		function getdepreciacao_e_amortizacao(){
			return number_format( $this->array_total['depreciacao_e_amortizacao'] , 2 , ',' , '.' );
		}
		function setdepreciacao_e_amortizacao(){
			$id = $_SESSION['id_empresaSecao'];
			if( isset($_GET['ano']) )
				$ano = $_GET['ano'];
			else
				$ano = date("Y");

			$vida_util = array();
			$vida_util['Veículos'] = floatval(5);
			$vida_util['Imóveis (prédios)'] = floatval(25);
			$vida_util['Móveis e utensílios'] = floatval(10);
			$vida_util['Computadores e periféricos'] = floatval(5);
			$vida_util['Máquinas e equipamentos'] = floatval(10);

			$tabela_depreciacao['Veículos'] = floatval(0.2);
			$tabela_depreciacao['Imóveis (prédios)'] = floatval(0.04);
			$tabela_depreciacao['Móveis e utensílios'] = floatval(0.1);
			$tabela_depreciacao['Computadores e periféricos'] = floatval(0.2);
			$tabela_depreciacao['Máquinas e equipamentos'] = floatval(0.1);

			$consulta = mysql_query("SELECT * FROM imobilizados WHERE id_user = '".$id."' AND ano <= '".$ano."' ");
			$total = 0;
			while( $objeto=mysql_fetch_array($consulta) ){
				if( floatval($ano) - floatval($objeto['ano']) <= $vida_util[$objeto['item']] ){
					$vida = floatval($ano) - floatval($objeto['ano']);
					$valor_total_item = ( floatval($objeto['quantidade']) * floatval($objeto['valor']) );
					$total_depreciacao = 0;
					for ($i=1; $i <= $vida; $i++) { 
						$parcial_depreciacao = ( $valor_total_item * floatval($tabela_depreciacao[$objeto['item']]) );
						$total_depreciacao = $total_depreciacao + $parcial_depreciacao;
						$valor_total_item = $valor_total_item - $parcial_depreciacao;
					}
					if( $vida == 0 )
						$total_depreciacao = 0;
					$total = $total + $total_depreciacao;
				}
			}

			$vida_util = array();
			$vida_util['Software'] = floatval(5);
			$vida_util['Marca'] = floatval(99999);
			$vida_util['Patente'] = floatval(10);
			$vida_util['Direitos autorais'] = floatval(99999);
			$vida_util['Licenças'] = floatval(10);
			$vida_util['Pesquisa e desenvolvimento'] = floatval(10);
			
			$tabela_depreciacao = array();
			$tabela_depreciacao['Software'] = floatval(0.2);
			$tabela_depreciacao['Marca'] = floatval(0);
			$tabela_depreciacao['Patente'] = floatval(0.1);
			$tabela_depreciacao['Direitos autorais'] = floatval(0);
			$tabela_depreciacao['Licenças'] = floatval(0.1);
			$tabela_depreciacao['Pesquisa e desenvolvimento'] = floatval(0.1);

			$consulta = mysql_query("SELECT * FROM intangiveis WHERE id_user = '".$id."' AND ano_item <= '".$ano."' ");
			while( $objeto=mysql_fetch_array($consulta) ){
				if( floatval($ano) - floatval($objeto['ano_item']) <= $vida_util[$objeto['item']] ){
					$vida = floatval($ano) - floatval($objeto['ano_item']);
					$valor_total_item = ( floatval($objeto['quantidade']) * floatval($objeto['valor']) );
					$total_depreciacao = 0;
					for ($i=1; $i <= $vida; $i++) { 
						$parcial_depreciacao = ( $valor_total_item * floatval($tabela_depreciacao[$objeto['item']]) );
						$total_depreciacao = $total_depreciacao + $parcial_depreciacao;
						$valor_total_item = $valor_total_item - $parcial_depreciacao;
					}
					if( $vida == 0 )
						$total_depreciacao = 0;
					$total = $total + $total_depreciacao;
				}
			}
			// echo $total;
			$this->array_total['depreciacao_e_amortizacao'] = floatval($total*-1);
		}
		function getperdas_diversas(){
			return number_format( $this->array_total['perdas_diversas'] , 2 , ',' , '.' );
		}
		function setperdas_diversas($string){
			$this->array_total['perdas_diversas'] = $string;
		}
		function getresultado_financeiro(){
			return number_format( $this->array_total['resultado_financeiro'] , 2 , ',' , '.' );
		}
		function setresultado_financeiro($string){
			$this->array_total['resultado_financeiro'] = $string;
		}
		function getreceitas_financeiras(){
			return number_format( $this->array_total['receitas_financeiras'] , 2 , ',' , '.' );
		}
		function setreceitas_financeiras($string){
			$this->array_total['receitas_financeiras'] = $string;
		}
		function getdespesas_financeiras(){
			return number_format( $this->array_total['despesas_financeiras'] , 2 , ',' , '.' );
		}
		function setdespesas_financeiras($string){
			$this->array_total['despesas_financeiras'] = $string;
		}
		function getoutras_receitas(){
			return number_format( $this->array_total['outras_receitas'] , 2 , ',' , '.' );
		}
		function setoutras_receitas($string){
			$this->array_total['outras_receitas'] = $string;
		}
		function getoutras_despesas(){
			return number_format( $this->array_total['outras_despesas'] , 2 , ',' , '.' );
		}
		function setoutras_despesas($string){
			$this->array_total['outras_despesas'] = $string;
		}
		function getresultado_antes_das_despesas_com_tributos_sobre_o_lucro(){
			return number_format( $this->array_total['resultado_antes_das_despesas_com_tributos_sobre_o_lucro'] , 2 , ',' , '.' );
		}
		function setresultado_antes_das_despesas_com_tributos_sobre_o_lucro($string){
			$this->array_total['resultado_antes_das_despesas_com_tributos_sobre_o_lucro'] = $string;
		}
		function getdespesa_com_imposto_de_renda_da_pessoa_juridica(){
			return number_format( $this->array_total['despesa_com_imposto_de_renda_da_pessoa_juridica'] , 2 , ',' , '.' );
		}
		function setdespesa_com_imposto_de_renda_da_pessoa_juridica($string){
			$this->array_total['despesa_com_imposto_de_renda_da_pessoa_juridica'] = $string;
		}
		function getdespesa_com_contribuicao_social(){
			return number_format( $this->array_total['despesa_com_contribuicao_social'] , 2 , ',' , '.' );
		}
		function setdespesa_com_contribuicao_social($string){
			$this->array_total['despesa_com_contribuicao_social'] = $string;
		}
		function getresultado_liquido_do_exercicio(){
			return number_format( $this->array_total['resultado_liquido_do_exercicio'] , 2 , ',' , '.' );
		}
		function setresultado_liquido_do_exercicio($string){
			$this->array_total['resultado_liquido_do_exercicio'] = $string;
		}
		function getResultadoDre(){
			return $this->array_total['resultado_liquido_do_exercicio'];
		}
		function gerarTabelaCSV(){

			return 'Descrição;R$ 
VENDAS DE PRODUTOS, MERCADORIAS E SERVIÇOS;
Vendas de Produtos, Mercadorias e Serviços;'.$this->getvendas_de_mercadorias_produtos_e_servicos().'
(-) Deduções com Impostos, Devoluções e Descontos Incondicionais;'.$this->getdeducoes_com_impostos_devolucoes_e_descontos().'

(=) RECEITA LÍQUIDA;'.$this->getreceita_liquida().'

(-) CUSTO DAS VENDAS;
Custo dos Produtos, Mercadorias e Serviços'.$this->getcusto_das_vendas().'

(=) LUCRO BRUTO;'.$this->getlucro_bruto().'

(-) DESPESAS OPERACIONAIS;'.$this->getdespesas_operacionais().'
Despesas Administrativas;'.$this->getdespesas_administrativas().'
Despesas com Vendas;'.$this->getdespesas_de_vendas().'
Outras Despesas Gerais;'.$this->getoutras_despesas_gerais().'

(=) RESULTADO OPERACIONAL ANTES DO RESULTADO FINANCEIRO;'.$this->getdespesas_operacionais().'

(+/-) RESULTADO FINANCEIRO;'.$this->getresultado_financeiro().'
Receitas Financeiras;'.$this->getreceitas_financeiras().'
(-) Despesas Financeiras;'.$this->getdespesas_financeiras().'

(+/-) OUTRAS RECEITAS E DESPESAS OPERACIONAIS;'.$this->getoutras_receitas_e_despesas_operacionais().'

= RESULTADO ANTES DAS DESPESAS COM TRIBUTOS SOBRE O LUCRO; 0,00
(-) Despesa com Contribuição Social (*); 0,00
(-) Despesa com Imposto de Renda da Pessoa Jurídica (*); 0,00

(=) RESULTADO LÍQUIDO DO PERÍODO;'.$this->getresultado_liquido_do_exercicio().'';

/*			return 'Descrição;R$

VENDAS DE MERCADORIAS, PRODUTOS E SERVIÇOS;'.$this->getvendas_de_mercadorias_produtos_e_servicos().'
Vendas de Mercadorias;'.$this->getvendas_de_mercadorias().'
Vendas de Produtos;'.$this->getvendas_de_produtos().'
Vendas de Serviços;'.$this->getvendas_de_servicos().'
(-) Deduções com Impostos, Devoluções e Descontos Incondicionais;'.$this->getdeducoes_com_impostos_devolucoes_e_descontos().'

(=) RECEITA LÍQUIDA;'.$this->getreceita_liquida().'

(-) CUSTO DAS VENDAS;'.$this->getcusto_das_vendas().'
Custo das Mercadorias Vendidas;'.$this->getcusto_das_mercadorias_vendidas().'
Custo dos Produtos Vendidos;'.$this->getcusto_dos_produtos_vendidos().'
Custo dos Serviços Prestados;'.$this->getcusto_dos_servicos_prestados().'

(=) LUCRO BRUTO;'.$this->getlucro_bruto().'

(-) DESPESAS OPERACIONAIS;'.$this->getdespesas_operacionais().'
Despesas com Pessoal;'.$this->getdespesas_com_pessoal().'
Despesas Administrativas;'.$this->getdespesas_administrativas().'
Despesas de Vendas;'.$this->getdespesas_de_vendas().'
Despesas Tributárias;'.$this->getdespesas_tributarias().'
Depreciação e Amortização Acumuladas;'.$this->getdepreciacao_e_amortizacao().'
Perdas Diversas;'.$this->getperdas_diversas().'

(+/-) RESULTADO FINANCEIRO;'.$this->getresultado_financeiro().'
Receitas Financeiras;'.$this->getreceitas_financeiras().'
(-) Despesas Financeiras;'.$this->getdespesas_financeiras().'

(+) OUTRAS RECEITAS;'.$this->getoutras_receitas().'

(-) OUTRAS DESPESAS;'.$this->getoutras_despesas().'

(=) RESULTADO LÍQUIDO DO PERÍODO;'.$this->getresultado_liquido_do_exercicio().'
';
*/
		}
	}
?>