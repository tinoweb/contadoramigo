<?php
	
	include 'dre.class.php';	
	//Define o id a ser pesquisado
	if( isset($_GET['id']) )
		$id = $_GET['id'];
	else
		$id = $_SESSION['id_empresaSecao'];
	if( isset($_GET['ano']) )
		$ano = $_GET['ano'];
	else
		$ano = 2016;
	
	define("id_user", $id);
	define("ano", $ano);

?>
<?php 
	class Balanco_patrimonial{

		private $ano;
		private $id;

		private $a_c_caixa_equivalente_caixa;
		private $a_c_contas_receber;
		private $a_c_estoques;
		private $a_c_outros_creditos;
		private $a_c_despesas_exercicio_seguinte;
		private $a_c_total;

		private $a_n_c_contas_receber;
		private $a_n_c_investimentos;
		private $a_n_c_imobilizado;
		private $a_n_c_intangivel;
		private $a_n_c_depreciacao;
		private $a_n_c_total;

		private $p_c_fornecedores;
		private $p_c_emprestimos_bancarios;
		private $p_c_obrigacoes_sociais_impostos;
		private $p_c_contas_pagar;
		private $p_c_lucros_distribuir;
		private $p_c_provisoes;
		private $p_c_total;

		private $p_n_c_contas_pagar;
		private $p_n_c_financiamentos_bancarios;
		private $p_n_c_total;

		private $p_l_capital_social;
		private $p_l_reservas_capital;
		private $p_l_ajustes_avaliacao_patrimonial;
		private $p_l_reservas_lucro;
		private $p_l_lucros_acumulados;
		private $p_l_prejuizos_acumulados;
		private $p_l_total;

		private $total_ativo;
		private $total_passivo;

		private function Normaliza($string) {
			$table = array('Š'=>'S','š'=>'s','Đ'=>'Dj','đ'=>'dj','Ž'=>'Z','ž'=>'z','Č'=>'C','č'=>'c','Ć'=>'C','ć'=>'c','À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Å'=>'A','Æ'=>'A','Ç'=>'C','È'=>'E','É'=>'E',
			'Ê'=>'E','Ë'=>'E','Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I','Ñ'=>'N','Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O','Ø'=>'O','Ù'=>'U','Ú'=>'U','Û'=>'U','Ü'=>'U','Ý'=>'Y','Þ'=>'B','ß'=>'Ss',
			'à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a','æ'=>'a','ç'=>'c','è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ð'=>'o','ñ'=>'n','ò'=>'o','ó'=>'o',
			'ô'=>'o','õ'=>'o','ö'=>'o','ø'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ý'=>'y','ý'=>'y','þ'=>'b','ÿ'=>'y','Ŕ'=>'R','ŕ'=>'r','?'=>'');
			return strtr($string, $table);
		}
		function emprestimosPrazo(){
			$datas = new Datas();
			$emprestimos = mysql_query("SELECT * FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa_emprestimos ORDER BY id_item");	
			$aux = '';
			while( $objeto_emprestimos = mysql_fetch_array($emprestimos) ){
				$livro_caixa_emprestimo = mysql_query("SELECT * FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa WHERE id = '".$objeto_emprestimos['id_item']."'");
				while( $objeto_livro_caixa_emprestimo = mysql_fetch_array($livro_caixa_emprestimo) ){
					$data_final = $datas->somarMes($objeto_livro_caixa_emprestimo['data'],$objeto_emprestimos['meses']);
					if( $datas->diferencaData($this->ano.'-12-31',$data_final) >= 0 || true ){
						$aux .= '<tr>
					    			<td class="td_calendario" align="center" valign="middle" width="150">'.$objeto_livro_caixa_emprestimo['descricao'].'</td>
					    			<td class="td_calendario" align="center" valign="middle" width="120">'.$datas->desconverterData($objeto_livro_caixa_emprestimo['data']).'</td>
					    			<td class="td_calendario" align="center" valign="middle" width="100">R$ '.number_format($objeto_livro_caixa_emprestimo['entrada'] , 2 , ',' , '.').'</td>
					    			<td class="td_calendario" align="center" valign="middle" width="100">'.$objeto_emprestimos['meses'].'</td>
					    			<td class="td_calendario" align="center" valign="middle" width="100">
					    				'.number_format($objeto_emprestimos['valor_pago'] , 2 , ',' , '.').'
					    			</td>
					    			<td class="td_calendario" align="center" valign="middle" width="100">
					    				<i class="fa fa-file-text-o icone-download abrirJanelaAnexos" aria-hidden="true" tipo="p_c_emprestimos_bancarios'.$objeto_emprestimos['id'].'" titulo="'.$objeto_emprestimos['item'].'" ></i>
					    			</td>

					    		</tr>';
					  }
				}
			}


			$string = '
				<div id="cadastrarValorPagoEmprestimos" status="aberto" class="" style="width: 500px; left: 42%; margin-left: -223px; top: 150px; display: none; z-index: 999; position: fixed; border-radius: 0px; border: 1px solid rgb(204, 204, 204); padding: 20px; background: rgb(255, 255, 255);">
					<img class="fecharDiv" src="images/x.png" width="8" height="9" border="0" alt="Mídia sobre o Contador Amigo" title="" style="float: right;cursor:pointer">
					<div class="tituloVermelho" style="margin-bottom:20px">Cadastro Empréstimos</div>
				    <table id="itens_imobilizados" border="0" cellspacing="2" cellpadding="4" class="formTabela">
					  	<tbody>
						  	<tr>
				       			<th align="center" width="150">Descrição</th>
				       			<th align="center" width="120">Data</th>
				       			<th align="center" width="100">Valor</th>
				       			<th align="center" width="100">prazo</th>
				       			<th align="center" width="100">Saldo a Pagar</th>
				       			<th align="center" width="100">Anexo</th>
				    		</tr>
				    		'.$aux.'
				    	</tbody>
					</table>
			    </div>
			';
			return $string;
		}
		function financiamentosPrazo(){
			$datas = new Datas();
			$emprestimos = mysql_query("SELECT * FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa_emprestimos ORDER BY id_item");	
			$aux = '';
			while( $objeto_emprestimos = mysql_fetch_array($emprestimos) ){
				$livro_caixa_emprestimo = mysql_query("SELECT * FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa WHERE id = '".$objeto_emprestimos['id_item']."' ");
				while( $objeto_livro_caixa_emprestimo = mysql_fetch_array($livro_caixa_emprestimo) ){
					$data_final = $datas->somarMes($objeto_livro_caixa_emprestimo['data'],$objeto_emprestimos['meses']);
					if( $datas->diferencaData($this->ano.'-12-31',$data_final) < 0 || true ){
						$aux .= '<tr>
					    			<td class="td_calendario" align="center" valign="middle" width="150">'.$objeto_livro_caixa_emprestimo['descricao'].'</td>
					    			<td class="td_calendario" align="center" valign="middle" width="120">'.$datas->desconverterData($objeto_livro_caixa_emprestimo['data']).'</td>
					    			<td class="td_calendario" align="center" valign="middle" width="100">R$ '.number_format($objeto_livro_caixa_emprestimo['entrada'] , 2 , ',' , '.').'</td>
					    			<td class="td_calendario" align="center" valign="middle" width="100">'.$objeto_emprestimos['meses'].'</td>
					    			<td class="td_calendario" align="center" valign="middle" width="100">
					    				'.number_format($objeto_emprestimos['valor_pago'] , 2 , ',' , '.').'
					    			</td>
					    			<td class="td_calendario" align="center" valign="middle" width="100">
					    				<i class="fa fa-file-text-o icone-download abrirJanelaAnexos" aria-hidden="true" tipo="p_c_emprestimos_bancarios'.$objeto_emprestimos['id'].'" titulo="'.$objeto_emprestimos['item'].'" ></i>
					    			</td>

					    		</tr>';
					}
				}
			}


			$string = '
				<div id="cadastrarValorPagoFinanciamentos" status="aberto" class="" style="width: 500px; left: 42%; margin-left: -223px; top: 150px; display: none; z-index: 999; position: fixed; border-radius: 0px; border: 1px solid rgb(204, 204, 204); padding: 20px; background: rgb(255, 255, 255);">
					<img class="fecharDiv" src="images/x.png" width="8" height="9" border="0" alt="Mídia sobre o Contador Amigo" title="" style="float: right;cursor:pointer">
					<div class="tituloVermelho" style="margin-bottom:20px">Cadastro Empréstimos</div>
				    <table id="itens_imobilizados" border="0" cellspacing="2" cellpadding="4" class="formTabela">
					  	<tbody>
						  	<tr>
				       			<th align="center" width="150">Descrição</th>
				       			<th align="center" width="120">Data</th>
				       			<th align="center" width="100">Valor</th>
				       			<th align="center" width="100">prazo</th>
				       			<th align="center" width="100">Saldo a Pagar</th>
				       			<th align="center" width="100">Anexo</th>
				    		</tr>
				    		'.$aux.'
				    	</tbody>
					</table>
			    </div>
			';
			return $string;
		}
		function inserirIntangiveis(){

			$consulta = mysql_query("SELECT * FROM intangiveis WHERE id_user = '".$this->id."' AND ano <= '".$this->ano."' ");
			$string = '';
			$i = 1;
			if( mysql_num_rows($consulta) == 0 )
				$string = '<tr><td colspan="5">Não existe itens cadastrados</td></tr>';
			while( $objeto=mysql_fetch_array($consulta) ){

				$string .= 	'
					<tr>
						<td class="td_calendario" align="center" valign="middle">
							 '.$objeto['item'].'
						</td>
						<td class="td_calendario" align="center" valign="middle" >
							<label>
								'.$objeto['quantidade'].'
							</label>
						</td>
						<td class="td_calendario" align="center" valign="middle" >
							<label>
								'.number_format($objeto['valor'],2,',','.').'
							</label>
						</td>
						<td class="td_calendario" align="center" valign="middle" >
							<label>
								'.$objeto['ano_item'].'
							</label>
						</td>
						<td class="td_calendario" align="center" valign="middle" >
							<i class="fa fa-file-text-o icone-download abrirJanelaAnexos" aria-hidden="true" tag="#tag=2_'.$i.'" tipo="'.$objeto['id'].'" titulo="'.$objeto['item'].'" ></i>
						</td>
					</tr>';
					$i=$i+1;
			}

			echo '
				<div id="cadastrar_intangiveis" class="" style="border: 0px; width: 500px; left: 42%; margin-left: -223px; top: 150px; display: none; z-index: 999; position: fixed; border-radius: 0px; border:1px solid #cccccc; padding: 20px; background: rgb(255, 255, 255);">
					<img class="fecharDiv" src="images/x.png" width="8" height="9" border="0" alt="Mídia sobre o Contador Amigo" title="" style="float: right;cursor:pointer">
					<div class="tituloVermelho" style="margin-bottom:20px">Cadastro de Ativos Intangíveis</div>
				    <table id="itens_intangiveis" border="0" cellpadding="0" cellspacing="3" class="formTabela">
					  	<tbody>
					  	<tr>
			       			<th align="center" width="150">Tipo de Ativo</th>
			       			<th align="center" width="120">Quantidade</th>
			       			<th align="center" width="100">Valor Unitário</th>
			       			<th align="center" width="100">Ano</th>
			       			<th align="center" width="100">Anexo</th>
			    		</tr>
					  	'.$string.'		
						</tbody>
					</table>
			    </div>

			';
		}
		function inserirImobilizados(){
			$datas = new Datas();
			$consulta = mysql_query("SELECT * FROM imobilizados WHERE id_user = '".$this->id."' AND ano <= '".$this->ano."' ");
			$string = '';
			$i=1;
			if( mysql_num_rows($consulta) == 0 )
				$string = '<tr><td colspan="5">Não existe itens cadastrados</td></tr>';
			while( $objeto=mysql_fetch_array($consulta) ){
				$string .= 	'
					<tr>
						<td class="td_calendario" align="center" valign="middle" width="200">
							'.$objeto['item'].'
						</td>
						<td class="td_calendario" align="center" valign="middle" width="100">
							<label>
								'.$objeto['quantidade'].'
							</label>
						</td>
						<td class="td_calendario" align="center" valign="middle" width="150">
							<label>
								'.number_format($objeto['valor'],2,',','.').'
							</label>
						</td>
						<td class="td_calendario" align="center" valign="middle" width="100">
							<label>
								'.$datas->desconverterData($objeto['data']).'
							</label>
						</td>
						<td class="td_calendario" align="center" valign="middle" width="40">
							<i class="fa fa-file-text-o icone-download abrirJanelaAnexos" aria-hidden="true" tipo="'.$objeto['id'].'" titulo="'.$objeto['item'].'" ></i>
						</td>
					</tr>';
					$i=$i+1;
			}
			echo '

				<div id="cadastrar_imobilizado" status="fechado" class="" style="border: 0px; width: 500px; left: 42%; margin-left: -223px; top: 150px; display: none; z-index: 999; position: fixed; border-radius: 0px;border:1px solid #cccccc; padding: 20px; background: rgb(255, 255, 255);">
					<img class="fecharDiv" src="images/x.png" width="8" height="9" border="0" alt="Mídia sobre o Contador Amigo" title="" style="float: right;cursor:pointer">
					<div class="tituloVermelho" style="margin-bottom:20px">Cadastro de Ativos Imobilizados</div>
				    <table id="itens_imobilizados" border="0" cellspacing="2" cellpadding="4" class="formTabela">
					  	<tbody>
						  	<tr>
				       			<th align="center" width="150">Tipo de Ativo</th>
				       			<th align="center" width="120">Quantidade</th>
				       			<th align="center" width="100">Valor Unitário</th>
				       			<th align="center" width="100">Data</th>
				       			<th align="center" width="100">Anexo</th>
				    		</tr>
					  		'.$string;
			
			if( $string == '' ){
				echo '
							<tr>
								<td class="td_calendario" align="center" valign="middle" width="150">
									<select class="item">
										<option value="">Selecione</option>
										<option value="Veículos">Veículos</option>
										<option value="Imóveis (prédios)">Imóveis (prédios)</option>
										<option value="Móveis e utensílios">Móveis e utensílios</option>
										<option value="Computadores e periféricos">Computadores e periféricos</option>
										<option value="Máquinas e equipamentos">Máquinas e equipamentos</option>
										<option value="Terreno">Terreno</option>
									</select>
								</td>
								<td class="td_calendario" align="center" valign="middle" width="150">
									<label>
										<input class="input_quantidade" type="number" value="" min="1" style="width:50px;">
									</label>
								</td>
								<td class="td_calendario" align="center" valign="middle" width="150">
									<label>
										<input class="input_valor currency" type="text" style="width:70px;">
										<input type="hidden" class="input_id" value="">
									</label>
								</td>
								<td class="td_calendario" align="center" valign="middle" width="100">
									<label>
										<input class="campoData input_ano" type="text" style="width:60px;" size="10">
									</label>
								</td>
								<td class="td_calendario" align="center" valign="middle" width="40"><i class="fa fa-file-text-o anexo_aux_imobilizados" aria-hidden="true" tag="#tag=1_0" ></i></td>
							</tr>
				';

			}
			echo '			</tbody>
					</table>
			    </div>

			';
		}
		function gerarTabelaFinalInput(){
	
			echo 	'

				
						<tr>
							 <td style="min-width:250px"class="erro_merda td_calendario agrupado titulo_tabela"><strong>TOTAL</strong></td>
							 <td style="min-width:132px"class="erro_merda_ td_calendario agrupado titulo_tabela"><span class="total_passivo_geral">'.$this->formataValor($this->getTotal_passivo()).'</span></td>
							 <td style="min-width:40px"class="td_calendario agrupado titulo_tabela anexo_tabela"></td>
						</tr>						
					</tbody>
				</table>
			</div>

					';
		}

		function gerarTabelaPatrimonioLiquidoInput(){
			echo 	'
				<table border="0" cellspacing="2" cellpadding="4" style="width:400px;font-size: 11px;margin-top: -2px;float:left;margin-left:40px;margin-top:32px;" id="tabela_patrimonio_liquido">
					<tbody>
			    		<tr>
							<td style="min-width:250px"class="td_calendario agrupado titulo_tabela"><strong>PATRIMÔNIO LÍQUIDO</strong></td>
							<td style="min-width:80px"class="td_calendario agrupado titulo_tabela"><span class="total_patrimonio_liquido">'.$this->formataValor($this->getP_l_total()).'</span></td>
							<td style="min-width:40px"class="td_calendario agrupado titulo_tabela anexo_tabela"></td>
						</tr>
						<tr>
							 <td class="td_calendario">Capital Social'.$this->gerarImagemBallonP_l_capital_social().'</td>
							 <td class="td_calendario">'.$this->gerarInputP_l_capital_social($this->formataValor($this->getP_l_capital_social())).'</td>
							 <td class="td_calendario anexo_tabela" align="center"><i class="fa fa-file-text-o icone-download abrirJanelaAnexos" aria-hidden="true" tipo="p_l_capital_social" titulo="Capital Social" tag="#tag=0_p_l_capital_social" ></i></td>
						</tr>
						<tr>
							 <td class="td_calendario">Reservas de Capital'.$this->gerarImagemBallonP_l_reservas_capital().'</td>
							 <td class="td_calendario">'.$this->gerarInputP_l_reservas_capital($this->formataValor($this->getP_l_reservas_capital())).'</td>
							 <td class="td_calendario anexo_tabela"></td>
						</tr>
						<tr>
							 <td class="td_calendario">Ajustes de Avaliação Patrimonial'.$this->gerarImagemBallonP_l_ajustes_avaliacao_patrimonial().'</td>
							 <td class="td_calendario">'.$this->gerarInputP_l_ajustes_avaliacao_patrimonial($this->formataValor($this->getP_l_ajustes_avaliacao_patrimonial())).'</td>
							 <td class="td_calendario anexo_tabela"></td>
						</tr>
						<tr>
							 <td class="td_calendario">Reservas de Lucros'.$this->gerarImagemBallonP_l_reservas_lucro().'</td>
							 <td class="td_calendario">'.$this->gerarInputP_l_reservas_lucro($this->formataValor($this->getP_l_reservas_lucro())).'</td>
							 <td class="td_calendario anexo_tabela"></td>
						</tr>
						<tr>
							 <td class="td_calendario">Lucros Acumulados</td>
							 <td class="td_calendario">'.$this->gerarInputP_l_lucros_acumulados($this->formataValor($this->getP_l_lucros_acumulados())).'</td>
							 <td class="td_calendario anexo_tabela"></td>
						</tr>
						<tr>
							 <td class="td_calendario">(-) Prejuízos Acumulados </td>
							 <td class="td_calendario">'.$this->gerarInputP_l_prejuizos_acumulados($this->formataValor($this->getP_l_prejuizos_acumulados())).'</td>
							 <td class="td_calendario anexo_tabela"></td>
						</tr>
					

					';
		}
		function gerarTabelaPassivoInput(){
			echo 	'
			
			<div style="width:450px;float:left">
					
				<table border="0" cellspacing="2" cellpadding="4" style="width:400px;font-size: 11px;margin-top: -2px;float:left;margin-left:40px;" id="tabela_circulantes_passivo">
					<tbody>
						<tr>
			       			<th style="font-size: 12px;min-width:250px">PASSIVO E PATRIMÔNIO LÍQUIDO</th>
			       			<th style="font-size: 12px;min-width:80px">R$</th>
			       			<th style="font-size: 12px;min-width:40px" class="anexo_tabela" style="font-size: 12px;">Anexo</th>
			    		</tr>
			    		<tr>
							<td class="td_calendario agrupado titulo_tabela"><strong>CIRCULANTE</strong></td>
							<td class="td_calendario agrupado titulo_tabela"><span class="total_passivo_circulante">'.$this->formataValor($this->getP_c_total()).'</span></td>
							<td class="td_calendario agrupado titulo_tabela anexo_tabela"></td>
						</tr>
						<tr>
							 <td class="td_calendario">Fornecedores'.$this->gerarImagemBallonP_c_fornecedores().'</td>
							 <td class="td_calendario">'.$this->gerarInputP_c_fornecedores($this->formataValor($this->getP_c_fornecedores())).'</td>
							 <td class="td_calendario anexo_tabela" align="center"><i class="fa fa-file-text-o icone-download abrirJanelaAnexos" aria-hidden="true" tipo="p_c_fornecedores" tag="#tag=0_p_c_fornecedores" titulo="Fornecedores" ></i></td>
						</tr>
						<tr>
							 <td class="td_calendario">Empréstimos Bancários'.$this->gerarImagemBallonP_c_emprestimos_bancarios().' <a id="abrirCadastrarValorPagoEmprestimos" href="#" title="">ver itens</a></td>
							 <td class="td_calendario">'.$this->gerarInputP_c_emprestimos_bancarios($this->formataValor($this->getP_c_emprestimos_bancarios())).'</td>
							 <td class="td_calendario anexo_tabela" align="center"></td>
						</tr>
						<tr>
							 <td class="td_calendario">Obrigações Sociais e Impostos a Recolher'.$this->gerarImagemBallonP_c_obrigacoes_sociais_impostos().'</td>
							 <td class="td_calendario">'.$this->gerarInputP_c_obrigacoes_sociais_impostos($this->formataValor($this->getP_c_obrigacoes_sociais_impostos())).'</td>
							 <td class="td_calendario anexo_tabela" align="center"><i class="fa fa-file-text-o icone-download abrirJanelaAnexos" aria-hidden="true" tipo="p_c_obrigacoes_sociais_impostos" tag="#tag=0_p_c_obrigacoes_sociais_impostos" titulo="Obrigações Sociais e Impostos" ></i></td>
						</tr>
						<tr>
							 <td class="td_calendario">Contas a Pagar'.$this->gerarImagemBallonP_c_contas_pagar().'</td>
							 <td class="td_calendario">'.$this->gerarInputP_c_contas_pagar($this->formataValor($this->getP_c_contas_pagar())).'</td>
							 <td class="td_calendario anexo_tabela" align="center"><i class="fa fa-file-text-o icone-download abrirJanelaAnexos" aria-hidden="true" tipo="p_c_contas_pagar" tag="#tag=0_p_c_contas_pagar" titulo="Contas a Pagar" ></i></td>
						</tr>
						<tr>
							 <td class="td_calendario">Lucros a Distribuir'.$this->gerarImagemBallonP_c_lucros_distribuir().'</td>
							 <td class="td_calendario">'.$this->gerarInputP_c_lucros_distribuir($this->formataValor($this->getP_c_lucros_distribuir())).'</td>
							 <td class="td_calendario anexo_tabela"></td>
						</tr>
						<tr>
							 <td class="td_calendario">Provisões (cíveis, fiscais, trabalhistas, etc)'.$this->gerarImagemBallonP_c_provisoes().'</td>
							 <td class="td_calendario">'.$this->gerarInputP_c_provisoes($this->formataValor($this->getP_c_provisoes())).'</td>
							 <td class="td_calendario anexo_tabela"></td>
						</tr>
					</tbody>
				</table>
				<table border="0" cellspacing="2" cellpadding="4" style="width:400px;font-size: 11px;margin-top: -2px;float:left;margin-left:40px;margin-top:32px;" id="tabela_nao_curculante_passivo">
					<tbody>
						<tr>
							<td style="min-width:250px;" class="td_calendario agrupado titulo_tabela"><strong>NÃO CIRCULANTE</strong></td>
							<td style="min-width:80px;" class="td_calendario agrupado titulo_tabela"><span class="total_passivo_nao_circulante">'.$this->formataValor($this->getP_n_c_total()).'</span></td>
							<td style="font-size: 12px;min-width:40px" class="td_calendario agrupado titulo_tabela anexo_tabela"></td>
						</tr>
						<tr>
							 <td class="td_calendario">Contas a Pagar'.$this->gerarImagemBallonP_n_c_contas_pagar().'</td>
							 <td class="td_calendario">'.$this->gerarInputP_n_c_contas_pagar($this->formataValor($this->getP_n_c_contas_pagar())).'</td>
							 <td class="td_calendario anexo_tabela" align="center"><i class="fa fa-file-text-o icone-download abrirJanelaAnexos" aria-hidden="true" tipo="p_n_c_contas_pagar" tag="#tag=0_p_n_c_contas_pagar" titulo="Contas a Pagar" ></i></td>
						</tr>
						<tr>
							 <td class="td_calendario">Financiamentos Bancários'.$this->gerarImagemBallonP_n_c_financiamentos_bancarios().' <a id="abrirCadastrarValorPagoFinanciamentos" href="#" title="">ver itens</a></td>
							 <td class="td_calendario">'.$this->gerarInputP_n_c_financiamentos_bancarios($this->formataValor($this->getP_n_c_financiamentos_bancarios())).'</td>
							 <td class="td_calendario anexo_tabela" align="center"></td>
						</tr>
					</tbody>
				</table>

					';
		}
		function gerarTabelaAtivo(){
			echo 	'<div style="width:450px;float:left">
					
				
				<table border="0" cellspacing="2" cellpadding="4" style="width:400px;font-size: 11px;margin-top: -2px;float:left" id="tabela_circulant_ativo">
					<tbody>
						<tr>
			       			<th style="min-width:250px;font-size: 12px;">ATIVO</th>
			       			<th style="min-width:80px;font-size: 12px;">R$</th>
			       			<th style="min-width:40px;font-size: 12px;" class="anexo_tabela">Anexo</th>
			    		</tr>

			    		<tr>
							<td class="td_calendario agrupado titulo_tabela"><strong>CIRCULANTE</strong></td>
							<td class="td_calendario agrupado titulo_tabela"><span class="total_ativo_circulante">'.$this->formataValor($this->getA_c_total()).'</span></td>
							<td class="td_calendario agrupado titulo_tabela anexo_tabela"></td>
						</tr>

						<tr>
							<td class="td_calendario">Caixa e Equivalentes de Caixa'.$this->gerarImagemBallonA_c_caixa_equivalente_caixa().'</td>
							<td class="td_calendario">'.$this->gerarInputA_c_caixa_equivalente_caixa($this->formataValor($this->getA_c_caixa_equivalente_caixa())).'</td>
							<td class="td_calendario anexo_tabela" align="center"><i class="fa fa-file-text-o icone-download abrirJanelaAnexos" aria-hidden="true" tipo="a_c_caixa_equivalente_caixa" tag="#tag=0_a_c_caixa_equivalente_caixa" titulo="Caixa e Equivalentes de Caixa" ></i></td>
						</tr>

						<tr>
							<td class="td_calendario">Contas a Receber'.$this->gerarImagemBallonA_c_contas_receber().'</td>
							<td class="td_calendario">'.$this->gerarInputA_c_contas_receber($this->formataValor($this->getA_c_contas_receber())).'</td>
							<td class="td_calendario anexo_tabela" align="center"><i class="fa fa-file-text-o icone-download abrirJanelaAnexos" aria-hidden="true" tipo="a_c_contas_receber" tag="#tag=0_a_c_contas_receber" titulo="Contas a Receber" ></i></td>
						</tr>
						<tr>
							<td class="td_calendario">Estoques'.$this->gerarImagemBallonA_c_estoques().'</td>
							<td class="td_calendario">'.$this->gerarInputA_c_estoques($this->formataValor($this->getA_c_estoques())).'</td>
							<td class="td_calendario anexo_tabela"></td>
						</tr>
						<tr>
							<td class="td_calendario">Outros Créditos'.$this->gerarImagemBallonA_c_outros_creditos().'</td>
							<td class="td_calendario">'.$this->gerarInputA_c_outros_creditos($this->formataValor($this->getA_c_outros_creditos())).'</td>
							<td class="td_calendario anexo_tabela" align="center"><i class="fa fa-file-text-o icone-download abrirJanelaAnexos" aria-hidden="true" tipo="a_c_outros_creditos" tag="#tag=0_a_c_outros_creditos" titulo="Outros Créditos" ></i></td>
						</tr>
						<tr>
							<td class="td_calendario">Despesas do Exercício Seguinte'.$this->gerarImagemBallonA_c_despesas_exercicio_seguinte().'</td>
						 	<td class="td_calendario">'.$this->gerarInputA_c_despesas_exercicio_seguinte($this->formataValor($this->getA_c_despesas_exercicio_seguinte())).'</td>
						 	<td class="td_calendario anexo_tabela" align="center"><i class="fa fa-file-text-o icone-download abrirJanelaAnexos" aria-hidden="true" tipo="a_c_despesas_exercicio_seguinte" tag="#tag=0_a_c_despesas_exercicio_seguinte" titulo="Despesas do exercício seguinte" ></i></td>
						</tr>
					</tbody>
				</table>
				<table border="0" cellspacing="2" cellpadding="4" style="width:400px;font-size: 11px;margin-top: -2px;float:left;margin-top:66px;" id="tabela_nao_circulante_ativo">
					<tbody>
						<tr>
							<td style="min-width:250px" class="td_calendario agrupado titulo_tabela"><strong>NÃO CIRCULANTE</strong></td>
							<td style="min-width:80px" class="td_calendario agrupado titulo_tabela"><span class="total_ativo_nao_circulante">'.$this->formataValor($this->getA_n_c_total()).'</span></td>
							<td style="min-width:40px" class="td_calendario agrupado titulo_tabela anexo_tabela"></td>
						</tr>

						<tr>
							<td class="td_calendario">Contas a Receber'.$this->gerarImagemBallonA_n_c_contas_receber().'</td>
							<td class="td_calendario">'.$this->gerarInputA_n_c_contas_receber($this->formataValor($this->getA_n_c_contas_receber())).'</td>
							<td class="td_calendario anexo_tabela" align="center"><i class="fa fa-file-text-o icone-download abrirJanelaAnexos" aria-hidden="true" tipo="a_n_c_contas_receber" tag="#tag=0_a_n_c_contas_receber" titulo="Contas a Receber" ></i></td>
						</tr>

						<tr>
							<td class="td_calendario">Investimentos'.$this->gerarImagemBallonA_n_c_investimentos().'</td>
							<td class="td_calendario">'.$this->gerarInputA_n_c_investimentos($this->formataValor($this->getA_n_c_investimentos())).'</td>
							<td class="td_calendario anexo_tabela" align="center"><i class="fa fa-file-text-o icone-download abrirJanelaAnexos" aria-hidden="true" tipo="a_n_c_investimentos" tag="#tag=0_a_n_c_investimentos" titulo="Investimentos" ></i></td>
						</tr>

						<tr>
							<td class="td_calendario">Imobilizado'.$this->gerarImagemBallonA_n_c_imobilizado().' <a id="cadastrarImobilizado" href="#" title="">ver itens</a></td>
							<td class="td_calendario">'.$this->gerarInputA_n_c_imobilizado($this->formataValor($this->getA_n_c_imobilizado())).'</td>
							<td class="td_calendario anexo_tabela"></td>
						</tr>

						<tr>
							<td class="td_calendario">Intangível'.$this->gerarImagemBallonA_n_c_intangivel().' <a id="cadastrarIntangiveis" href="#" title="">ver itens</a></td>
							<td class="td_calendario">'.$this->gerarInputA_n_c_intangivel($this->formataValor($this->geta_n_c_intangivel())).'</td>
							<td class="td_calendario anexo_tabela"></td>
						</tr>
						<tr>
							<td class="td_calendario">(-) Depreciação e Amortização Acumuladas</td>
							<td class="td_calendario">'.$this->gerarInputA_n_c_depreciacao($this->formataValor($this->getA_n_c_depreciacao())).'</td>
							<td class="td_calendario anexo_tabela"></td>
						</tr>
					</tbody>
				</table>
				<table border="0" cellspacing="2" cellpadding="4" style="width:400px;font-size: 11px;margin-top: -2px;float:left;margin-top:168px;"" id="tabela_total_ativo">
					<tbody>
						<tr class="total">
							<td style="min-width:250px" class="td_calendario titulo_tabela"><strong>TOTAL</strong></td>
							<td style="min-width:132px" class="td_calendario titulo_tabela"><span class="total_ativo_geral">'.$this->formataValor($this->getTotal_ativo()).'</span></td>
							<td style="min-width:40px" class="td_calendario titulo_tabela anexo_tabela"></td>
						</tr>

						</tbody>
					</table>
				</div>
					';
		}

		//####################################################################################################################################

		function gerarTabelaCSV(){
	
			return 'Balanço Patrimonial

ATIVO;R$;;PASSIVO E PATRIMÔNIO LÍQUIDO;R$
CIRCULANTE;'.$this->formataValor($this->getA_c_total()).';;CIRCULANTE;'.$this->formataValor($this->getP_c_total()).'
Caixa e Equivalentes de Caixa;'.$this->formataValor($this->getA_c_caixa_equivalente_caixa()).';;Fornecedores;'.$this->formataValor($this->getP_c_fornecedores()).'
Contas a Receber;'.$this->formataValor($this->getA_c_contas_receber()).';;Empréstimos Bancários;'.$this->formataValor($this->getP_c_emprestimos_bancarios()).'
Estoques;'.$this->formataValor($this->getA_c_estoques()).';;Obrigações Sociais e Impostos a Recolher;'.$this->formataValor($this->getP_c_obrigacoes_sociais_impostos()).'
Outros Créditos;'.$this->formataValor($this->getA_c_outros_creditos()).';;Contas a Pagar;'.$this->formataValor($this->getP_c_contas_pagar()).'
Despesas do Exercício Seguinte;'.$this->formataValor($this->getA_c_despesas_exercicio_seguinte()).';;Lucros a Distribuir;'.$this->formataValor($this->getP_c_lucros_distribuir()).'
;;;Provisões (cíveis, fiscais, trabalhistas, etc);'.$this->formataValor($this->getP_c_provisoes()).'

NÃO CIRCULANTE;'.$this->formataValor($this->getA_n_c_total()).';;NÃO CIRCULANTE;'.$this->formataValor($this->getP_n_c_total()).'
Contas a Receber;'.$this->formataValor($this->getA_n_c_contas_receber()).';;Contas a Pagar;'.$this->formataValor($this->getP_n_c_contas_pagar()).'
Investimentos;'.$this->formataValor($this->getA_n_c_investimentos()).';;Financiamentos Bancários;'.$this->formataValor($this->getP_n_c_financiamentos_bancarios()).'
Imobilizado;'.$this->formataValor($this->getA_n_c_imobilizado()).'
Intangível;'.$this->formataValor($this->getA_n_c_intangivel()).';;PATRIMÔNIO LÍQUIDO;'.$this->formataValor($this->getP_l_total()).'
(-) Depreciação e Amortização Acumuladas;'.strval($this->formataValor($this->getA_n_c_depreciacao())).';;Capital Social;'.$this->formataValor($this->getP_l_capital_social()).'
;;;Reservas de Capital;'.$this->formataValor($this->getP_l_reservas_capital()).'
;;;Ajustes de Avaliação Patrimonial;'.$this->formataValor($this->getP_l_ajustes_avaliacao_patrimonial()).'
;;;Reservas de Lucros;'.$this->formataValor($this->getP_l_reservas_lucro()).'
;;;Lucros Acumulados;'.$this->formataValor($this->getP_l_lucros_acumulados()).'
;;;(-) Prejuízos Acumulados;'.$this->formataValor($this->getP_l_prejuizos_acumulados()).'

TOTAL;'.$this->formataValor($this->getTotal_ativo()).';;TOTAL;'.$this->formataValor($this->getTotal_passivo())."\n";
		}

		function gerarTabelaPassivoInputCSV(){
			return 	'';
		}
		function gerarTabelaAtivoCSF(){
			$pula_linha = "\n";
			return 	'';
		}

		function formataValor($valor){
			return number_format($valor,2,',','.');
		}
		function diferencaMesesAtual($data1,$data2,$prazo){
			$datas = new Datas();	
			$aux = 0;
			if( $datas->diferencaData($data1, $this->ano.'-01-01' ) <= 0 )
				$data1 = ($this->ano-1).'-12-20';
			while( $datas->diferencaData($data1, $data2 ) <= 0 && $prazo >= 0  ){
				$data1 = $datas->somarMes($data1,1);
				$aux = $aux + 1;
				$prazo = $prazo - 1;
				// echo $data1.'<br>';
			}

			if( $aux > 0 )
				return $aux - 1;					
			else
				return 0;
		}
		function diferencaMesesAnterior($data1,$data2,$prazo){
			$datas = new Datas();	
			$aux = 0;
			while( $datas->diferencaData($data1, $data2 ) <= 0 && $prazo >= 0  ){
				$data1 = $datas->somarMes($data1,1);
				$aux = $aux + 1;
				$prazo = $prazo - 1;
				// echo $data1.'<br>';
			}

			if( $aux > 0 )
				return $aux - 1;					
			else
				return 0;
		}
		function diferencaMesesProximo($data1,$data2,$prazo){
			$datas = new Datas();	
			$aux = 0;
			$data1 = ($this->ano).'-12-20';
			while( $datas->diferencaData($data1, $data2 ) <= 0 && $prazo >= 0  ){
				// echo $data1.'<br>';
				$data1 = $datas->somarMes($data1,1);
				$aux = $aux + 1;
				$prazo = $prazo - 1;	
			}

			if( $aux > 0 )
				return $aux - 1;					
			else
				return 0;
		}
		function getMesesAnoAtual($data,$prazo){
			// echo 'Anteriores';
			// echo '<br>';
			return $this->diferencaMesesAtual( $data , $this->ano.'-12-20' , $prazo );
		
		}
		function getMesesAnosAnteriores($data,$prazo){
			// echo 'Atuais';
			// echo '<br>';
			return $this->diferencaMesesAnterior( $data , $this->ano.'-01-01' , $prazo );
		
		}
		function getMesesAnoProximo($data,$prazo){
		
			return $this->diferencaMesesProximo( $data , ($this->ano+2).'-01-01' , $prazo );
		
		}
		function setDados($array,$id,$ano){

			$this->ano = $ano;
			$this->id = $id;
			if( isset($array['a_c_caixa_equivalente_caixa']) ){
				$this->a_c_caixa_equivalente_caixa = floatval($array['a_c_caixa_equivalente_caixa']);
			}
			if( isset($array['a_c_contas_receber']) )
				$this->a_c_contas_receber = floatval($array['a_c_contas_receber']);
			if( isset($array['a_c_estoques']) )
				$this->a_c_estoques = floatval($array['a_c_estoques']);
			if( isset($array['a_c_outros_creditos']) )
				$this->a_c_outros_creditos = floatval($array['a_c_outros_creditos']);
			if( isset($array['a_c_despesas_exercicio_seguinte']) )
				$this->a_c_despesas_exercicio_seguinte = floatval($array['a_c_despesas_exercicio_seguinte']);
			if( isset($array['a_c_total']) )
				$this->a_c_total = floatval($array['a_c_total']);
			if( isset($array['a_n_c_contas_receber']) )
				$this->a_n_c_contas_receber = floatval($array['a_n_c_contas_receber']);
			if( isset($array['a_n_c_investimentos']) ){
				$this->a_n_c_investimentos = floatval($array['a_n_c_investimentos']);
			}
			if( isset($array['a_n_c_imobilizado']) )
				$this->a_n_c_imobilizado = floatval($array['a_n_c_imobilizado']);
			if( isset($array['a_n_c_intangivel']) )
				$this->a_n_c_intangivel = floatval($array['a_n_c_intangivel']);
			if( isset($array['a_n_c_depreciacao']) )
				$this->a_n_c_depreciacao = floatval($array['a_n_c_depreciacao']);
			if( isset($array['a_n_c_total']) )
				$this->a_n_c_total = floatval($array['a_n_c_total']);
			if( isset($array['p_c_fornecedores']) )
				$this->p_c_fornecedores = floatval($array['p_c_fornecedores']);
			if( isset($array['p_c_emprestimos_bancarios']) )
				$this->p_c_emprestimos_bancarios = floatval($array['p_c_emprestimos_bancarios']);
			if( isset($array['p_n_c_financiamentos_bancarios']) )
				$this->p_n_c_financiamentos_bancarios = floatval($array['p_n_c_financiamentos_bancarios']);
			if( isset($array['p_c_obrigacoes_sociais_impostos']) )
				$this->p_c_obrigacoes_sociais_impostos = floatval($array['p_c_obrigacoes_sociais_impostos']);
			if( isset($array['p_c_contas_pagar']) )
				$this->p_c_contas_pagar = floatval($array['p_c_contas_pagar']);
			if( isset($array['p_c_lucros_distribuir']) )
				$this->p_c_lucros_distribuir = floatval($array['p_c_lucros_distribuir']);
			if( isset($array['p_c_provisoes']) )
				$this->p_c_provisoes = floatval($array['p_c_provisoes']);
			if( isset($array['p_c_total']) )
				$this->p_c_total = floatval($array['p_c_total']);
			if( isset($array['p_n_c_contas_pagar']) )
				$this->p_n_c_contas_pagar = floatval($array['p_n_c_contas_pagar']);
			if( isset($array['p_n_c_total']) )
				$this->p_n_c_total = floatval($array['p_n_c_total']);
			if( isset($array['p_l_capital_social']) )
				$this->p_l_capital_social = floatval($array['p_l_capital_social']);
			if( isset($array['p_l_reservas_capital']) )
				$this->p_l_reservas_capital = floatval($array['p_l_reservas_capital']);
			if( isset($array['p_l_ajustes_avaliacao_patrimonial']) )
				$this->p_l_ajustes_avaliacao_patrimonial = floatval($array['p_l_ajustes_avaliacao_patrimonial']);
			if( isset($array['p_l_reservas_lucro']) )
				$this->p_l_reservas_lucro = floatval($array['p_l_reservas_lucro']);
			if( isset($array['p_l_lucros_acumulados']) )
				$this->p_l_lucros_acumulados = floatval($array['p_l_lucros_acumulados']);
			if( isset($array['p_l_prejuizos_acumulados']) )
				$this->p_l_prejuizos_acumulados = floatval($array['p_l_prejuizos_acumulados']);
			if( isset($array['p_l_total']) )
				$this->p_l_total = floatval($array['p_l_total']);
		}
		function getTotal_passivo(){
			return floatval($this->getP_l_total()) + floatval($this->getP_c_total())+ floatval($this->getP_n_c_total());
		}
		function getTotal_ativo(){
			return floatval($this->getA_c_total()) + floatval($this->getA_n_c_total());
		}
		function setA_c_caixa_equivalente_caixa($string){
			$this->a_c_caixa_equivalente_caixa = $string;
		}
		function getA_c_caixa_equivalente_caixa(){
			return $this->a_c_caixa_equivalente_caixa;
		}
		function gerarImagemBallonA_c_caixa_equivalente_caixa(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="a_c_caixa_equivalente_caixa" style="cursor: pointer;">';
		}
		function gerarInputA_c_caixa_equivalente_caixa($value){
			return '<input class="input_tabela currency input_valor a_c_caixa_equivalente_caixa" type="text" name="a_c_caixa_equivalente_caixa" value="'.$value.'" disabled="disabled">';
		}
		function gerarBallonInputA_c_caixa_equivalente_caixa($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="a_c_caixa_equivalente_caixa" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function setA_c_contas_receber($string){
			$this->a_c_contas_receber = $string;
		}
		function getA_c_contas_receber(){
			return $this->a_c_contas_receber;
		}
		function gerarImagemBallonA_c_contas_receber(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="a_c_contas_receber" style="cursor: pointer;">';
		}
		function gerarInputA_c_contas_receber($value){
			return '<input class="input_tabela currency input_valor a_c_contas_receber" type="text" name="a_c_contas_receber" value="'.$value.'" disabled="disabled">';
		}
		function gerarBallonInputA_c_contas_receber($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="a_c_contas_receber" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function setA_c_estoques($string){
			$this->a_c_estoques = $string;
		}
		function getA_c_estoques(){
			return $this->a_c_estoques;
		}
		function gerarImagemBallonA_c_estoques(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="a_c_estoques" style="cursor: pointer;">';
		}
		function gerarInputA_c_estoques($value){
			return '<input class="input_tabela currency input_valor a_c_estoques" type="text" name="a_c_estoques" value="'.$value.'" disabled="disabled">';
		}
		function gerarBallonInputA_c_estoques($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="a_c_estoques" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function setA_c_outros_creditos($string){
			$this->a_c_outros_creditos = $string;
		}
		function getA_c_outros_creditos(){
			return $this->a_c_outros_creditos;
		}
		function gerarImagemBallonA_c_outros_creditos(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="a_c_outros_creditos" style="cursor: pointer;">';
		}
		function gerarInputA_c_outros_creditos($value){
			return '<input class="input_tabela currency input_valor a_c_outros_creditos" type="text" name="a_c_outros_creditos" value="'.$value.'" disabled="disabled">';
		}
		function gerarBallonInputA_c_outros_creditos($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="a_c_outros_creditos" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function setA_c_despesas_exercicio_seguinte($string){
			$this->a_c_despesas_exercicio_seguinte = $string;
		}
		function getA_c_despesas_exercicio_seguinte(){
			return $this->a_c_despesas_exercicio_seguinte;
		}
		function gerarImagemBallonA_c_despesas_exercicio_seguinte(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="a_c_despesas_exercicio_seguinte" style="cursor: pointer;">';
		}
		function gerarInputA_c_despesas_exercicio_seguinte($value){
			return '<input class="input_tabela currency input_valor a_c_despesas_exercicio_seguinte" type="text" name="a_c_despesas_exercicio_seguinte" value="'.$value.'" disabled="disabled">';
		}
		function gerarBallonInputA_c_despesas_exercicio_seguinte($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="a_c_despesas_exercicio_seguinte" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function setA_c_total(){
			$this->a_c_total = floatval($this->a_c_caixa_equivalente_caixa) +floatval($this->a_c_contas_receber) +floatval($this->a_c_estoques) +floatval($this->a_c_outros_creditos) +floatval($this->a_c_despesas_exercicio_seguinte);
		}
		function getA_c_total(){
			$this->setA_c_total();
			return $this->a_c_total;
		}
		function gerarImagemBallonA_c_total(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="a_c_total" style="cursor: pointer;">';
		}
		function gerarInputA_c_total($value){
			return '<input class="input_tabela currency input_valor a_c_total" type="text" name="a_c_total" value="'.$value.'" disabled="disabled">';
		}
		function gerarBallonInputA_c_total($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="a_c_total" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function setA_n_c_contas_receber($string){
			$this->a_n_c_contas_receber = $string;
		}
		function getA_n_c_contas_receber(){
			return $this->a_n_c_contas_receber;
		}
		function gerarImagemBallonA_n_c_contas_receber(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="a_n_c_contas_receber" style="cursor: pointer;">';
		}
		function gerarInputA_n_c_contas_receber($value){
			return '<input class="input_tabela currency input_valor a_n_c_contas_receber" type="text" name="a_n_c_contas_receber" value="'.$value.'" disabled="disabled">';
		}
		function gerarBallonInputA_n_c_contas_receber($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="a_n_c_contas_receber" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function setA_n_c_investimentos($string){
			$this->a_n_c_investimentos = $string;
			$this->a_c_caixa_equivalente_caixa = $this->a_c_caixa_equivalente_caixa - $this->a_n_c_investimentos;
		}
		function getA_n_c_investimentos(){
			return $this->a_n_c_investimentos;
		}
		function gerarImagemBallonA_n_c_investimentos(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="a_n_c_investimentos" style="cursor: pointer;">';
		}
		function gerarInputA_n_c_investimentos($value){
			return '<input class="input_tabela currency input_valor a_n_c_investimentos" type="text" name="a_n_c_investimentos" value="'.$value.'" disabled="disabled">';
		}
		function gerarBallonInputA_n_c_investimentos($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="a_n_c_investimentos" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function setA_n_c_imobilizado($string){
			$this->a_n_c_imobilizado = $string;
		}
		function getA_n_c_imobilizado(){
			return $this->a_n_c_imobilizado;
		}
		function gerarImagemBallonA_n_c_imobilizado(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="a_n_c_imobilizado" style="cursor: pointer;">';
		}
		function gerarInputA_n_c_imobilizado($value){
			return '<input class="input_tabela currency input_valor a_n_c_imobilizado" type="text" name="a_n_c_imobilizado" value="'.$value.'" disabled="disabled">';
		}
		function gerarBallonInputA_n_c_imobilizado($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="a_n_c_imobilizado" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function setA_n_c_intangivel($string){
			$this->a_n_c_intangivel = $string;
		}
		function getA_n_c_intangivel(){
			return $this->a_n_c_intangivel;
		}
		function gerarImagemBallonA_n_c_intangivel(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="a_n_c_intangivel" style="cursor: pointer;">';
		}
		function gerarInputA_n_c_intangivel($value){
			return '<input class="input_tabela currency input_valor a_n_c_intangivel" type="text" name="a_n_c_intangivel" value="'.$value.'" placeholder="" disabled="disabled">';
		}
		function gerarBallonInputA_n_c_intangivel($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="a_n_c_intangivel" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function setA_n_c_depreciacao($string){
			$this->a_n_c_depreciacao = $string;
		}
		function getMesesDepreciacao($data){
			$datas = new Datas();
			while( $datas->diferencaData($data, $this->ano.'-12-31' ) <= 0){
				$data = $datas->somarMes($data,1);
				$aux = $aux + 1;
			}
			// echo $aux - 1;
			return $aux - 1;
		}
		function calcularMesesDepreciacao($data){
			return intval( $this->getMesesDepreciacao($data) % 12 );
		}
		function calcularAnosDepreciacao($data){
			return intval( $this->getMesesDepreciacao($data) / 12 );
		}
		function getA_n_c_depreciacao(){
			$datas = new Datas();
			$id = $_SESSION['id_empresaSecao'];
			if( isset($_GET['ano']) )
				$ano = $_GET['ano'];
			else
				$ano = date("Y");

			$tabela_depreciacao = array();
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



			$consulta = mysql_query("SELECT * FROM imobilizados WHERE id_user = '".$id."' AND YEAR(data) <= '".$ano."' AND item != '' ");
			$total = 0;
			while( $objeto=mysql_fetch_array($consulta) ){
				// if( floatval($ano) - floatval( $datas->getAno($objeto['data']) ) <= $vida_util[$objeto['item']] ){
					// $vida = floatval($ano) - floatval($objeto['data']);
					// $vida = $this->calcularAnosDepreciacao($objeto['data']);
					// $meses_vida = $this->calcularMesesDepreciacao($objeto['data']);
					// $valor_total_item = ( floatval($objeto['quantidade']) * floatval($objeto['valor']) );
					// $total_depreciacao = 0;
					// for ($i=1; $i <= $vida; $i++) { 
					// 	$parcial_depreciacao = ( $valor_total_item * floatval($tabela_depreciacao[$objeto['item']]) );
					// 	$total_depreciacao = $total_depreciacao + $parcial_depreciacao;
					// 	$valor_total_item = $valor_total_item - $parcial_depreciacao;						
					// }
					// $total_depreciacao = $total_depreciacao + ( $meses_vida / 12 ) * ( $valor_total_item * floatval($tabela_depreciacao[$objeto['item']]) );
					$meses_depreciacao = $this->getMesesDepreciacao($objeto['data']);
					$depreciacao = floatval($objeto['valor']) * floatval($tabela_depreciacao[$objeto['item']]);
					$depreciacao = $depreciacao / 12;
					$total_depreciacao = $depreciacao * $meses_depreciacao;
					// echo $objeto['valor'];
					// echo '<br>';
					// echo $meses_depreciacao;
					// echo '<br>';
					// echo $depreciacao;
					if( $total_depreciacao <= 0 )
						$total_depreciacao = 0;
					$total = $total + $total_depreciacao;
				// }
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

			$consulta = mysql_query("SELECT * FROM intangiveis WHERE id_user = '".$id."' AND YEAR(data) <= '".$ano."' ");
			while( $objeto=mysql_fetch_array($consulta) ){
				if( floatval($ano) - floatval( $datas->getAno($objeto['data']) ) <= $vida_util[$objeto['item']] ){
					// $vida = floatval($ano) - floatval($objeto['data']);
					// $vida = $this->calcularAnosDepreciacao($objeto['data']);
					// $meses_vida = $this->calcularMesesDepreciacao($objeto['data']);
					// $valor_total_item = ( floatval($objeto['quantidade']) * floatval($objeto['valor']) );
					// $total_depreciacao = 0;
					// for ($i=1; $i <= $vida; $i++) { 
					// 	$parcial_depreciacao = ( $valor_total_item * floatval($tabela_depreciacao[$objeto['item']]) );
					// 	$total_depreciacao = $total_depreciacao + $parcial_depreciacao;
					// 	$valor_total_item = $valor_total_item - $parcial_depreciacao;						
					// }
					// $total_depreciacao = $total_depreciacao + ( $meses_vida / 12 ) * ( $valor_total_item * floatval($tabela_depreciacao[$objeto['item']]) );
					$meses_depreciacao = $this->getMesesDepreciacao($objeto['data']);
					$depreciacao = floatval($objeto['valor']) * floatval($tabela_depreciacao[$objeto['item']]);
					$depreciacao = $depreciacao / 12;
					$total_depreciacao = $depreciacao * $meses_depreciacao;
					// echo $objeto['valor'];
					// echo '<br>';
					// echo $meses_depreciacao;
					// echo '<br>';
					// echo $depreciacao;
					if( $total_depreciacao <= 0 )
						$total_depreciacao = 0;
					$total = $total + $total_depreciacao;
				}
			}
			// echo $total;
			// exit();
			$this->setA_n_c_depreciacao(floatval($total*-1));
			return floatval($total*-1);
		}
		function gerarImagemBallonA_n_c_depreciacao(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="a_n_c_depreciacao" style="cursor: pointer;">';
		}
		function gerarInputA_n_c_depreciacao($value){
			return '<input class="input_tabela currency input_valor a_n_c_depreciacao" type="text" name="a_n_c_depreciacao" value="'.$value.'" placeholder="" disabled="disabled">';
		}
		function gerarBallonInputA_n_c_depreciacao($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="a_n_c_depreciacao" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function setA_n_c_total(){
			$this->a_n_c_total = 
				$this->getA_n_c_contas_receber()+
				$this->getA_n_c_investimentos()+
				$this->getA_n_c_imobilizado()+
				$this->getA_n_c_intangivel()+
				$this->getA_n_c_depreciacao();

		}
		function getA_n_c_total(){
			$this->setA_n_c_total();
			return $this->a_n_c_total;
		}
		function gerarImagemBallonA_n_c_total(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="a_n_c_total" style="cursor: pointer;">';
		}
		function gerarInputA_n_c_total($value){
			return '<input class="input_tabela currency input_valor a_n_c_total" type="text" name="a_n_c_total" value="'.$value.'" placeholder="">';
		}
		function gerarBallonInputA_n_c_total($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="a_n_c_total" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function setP_c_fornecedores($string){
			$this->p_c_fornecedores = $string;
		}
		function getP_c_fornecedores(){
			return $this->p_c_fornecedores;
		}
		function gerarImagemBallonP_c_fornecedores(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="p_c_fornecedores" style="cursor: pointer;">';
		}
		function gerarInputP_c_fornecedores($value){
			return '<input class="input_tabela currency input_valor p_c_fornecedores" type="text" name="p_c_fornecedores" value="'.$value.'" disabled="disabled">';
		}
		function gerarBallonInputP_c_fornecedores($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="p_c_fornecedores" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function setP_c_emprestimos_bancarios($string){
			$this->p_c_emprestimos_bancarios = $string;
		}
		function getP_c_emprestimos_bancarios(){
			return $this->p_c_emprestimos_bancarios;
		}
		function gerarImagemBallonP_c_emprestimos_bancarios(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="p_c_emprestimos_bancarios" style="cursor: pointer;">';
		}
		function gerarInputP_c_emprestimos_bancarios($value){
			return '<input class="input_tabela currency input_valor p_c_emprestimos_bancarios" type="text" name="p_c_emprestimos_bancarios" value="'.$value.'" disabled="disabled">';
		}
		function gerarBallonInputP_c_emprestimos_bancarios($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="p_c_emprestimos_bancarios" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function setP_c_obrigacoes_sociais_impostos($string){
			$this->p_c_obrigacoes_sociais_impostos = $string;
		}
		function getP_c_obrigacoes_sociais_impostos(){
			return $this->p_c_obrigacoes_sociais_impostos;
		}
		function gerarImagemBallonP_c_obrigacoes_sociais_impostos(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="p_c_obrigacoes_sociais_impostos" style="cursor: pointer;">';
		}
		function gerarInputP_c_obrigacoes_sociais_impostos($value){
			return '<input class="input_tabela currency input_valor p_c_obrigacoes_sociais_impostos" type="text" name="p_c_obrigacoes_sociais_impostos" value="'.$value.'" disabled="disabled">';
		}
		function gerarBallonInputP_c_obrigacoes_sociais_impostos($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="p_c_obrigacoes_sociais_impostos" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function setP_c_contas_pagar($string){
			$this->p_c_contas_pagar = $string;
		}
		function getP_c_contas_pagar(){
			return $this->p_c_contas_pagar;
		}
		function gerarImagemBallonP_c_contas_pagar(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="p_c_contas_pagar" style="cursor: pointer;">';
		}
		function gerarInputP_c_contas_pagar($value){
			return '<input class="input_tabela currency input_valor p_c_contas_pagar" type="text" name="p_c_contas_pagar" value="'.$value.'" disabled="disabled">';
		}
		function gerarBallonInputP_c_contas_pagar($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="p_c_contas_pagar" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function setP_c_lucros_distribuir($string){
			$this->p_c_lucros_distribuir = $string;
		}
		function getP_c_lucros_distribuir(){
			return $this->p_c_lucros_distribuir;
		}
		function gerarImagemBallonP_c_lucros_distribuir(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="p_c_lucros_distribuir" style="cursor: pointer;">';
		}
		function gerarInputP_c_lucros_distribuir($value){
			return '<input class="input_tabela currency input_valor p_c_lucros_distribuir" type="text" name="p_c_lucros_distribuir" value="'.$value.'" disabled="disabled">';
		}
		function gerarBallonInputP_c_lucros_distribuir($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="p_c_lucros_distribuir" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function setP_c_provisoes($string){
			$this->p_c_provisoes = $string;
		}
		function getP_c_provisoes(){
			return $this->p_c_provisoes;
		}
		function gerarImagemBallonP_c_provisoes(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="p_c_provisoes" style="cursor: pointer;">';
		}
		function gerarInputP_c_provisoes($value){
			return '<input class="input_tabela currency input_valor p_c_provisoes" type="text" name="p_c_provisoes" value="'.$value.'" disabled="disabled">';
		}
		function gerarBallonInputP_c_provisoes($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="p_c_provisoes" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function setP_c_total(){
			$this->p_c_total = $this->getP_c_fornecedores()+$this->getP_c_emprestimos_bancarios()+$this->getP_c_obrigacoes_sociais_impostos()+$this->getP_c_contas_pagar()+$this->getP_c_lucros_distribuir()+$this->getP_c_provisoes();
		}
		function getP_c_total(){
			$this->setP_c_total();
			return $this->p_c_total;
		}
		function gerarImagemBallonP_c_total(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="p_c_total" style="cursor: pointer;">';
		}
		function gerarInputP_c_total($value){
			return '<input class="input_tabela currency input_valor p_c_total" type="text" name="p_c_total" value="'.$value.'" disabled="disabled">';
		}
		function gerarBallonInputP_c_total($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="p_c_total" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function setP_n_c_contas_pagar($string){
			$this->p_n_c_contas_pagar = $string;
		}
		function getP_n_c_contas_pagar(){
			return $this->p_n_c_contas_pagar;
		}
		function gerarImagemBallonP_n_c_contas_pagar(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="p_n_c_contas_pagar" style="cursor: pointer;">';
		}
		function gerarInputP_n_c_contas_pagar($value){
			return '<input class="input_tabela currency input_valor p_n_c_contas_pagar" type="text" name="p_n_c_contas_pagar" value="'.$value.'" disabled="disabled">';
		}
		function gerarBallonInputP_n_c_contas_pagar($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="p_n_c_contas_pagar" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function setP_n_c_financiamentos_bancarios($string){
			$this->p_n_c_financiamentos_bancarios = $string;
		}
		function getP_n_c_financiamentos_bancarios(){
			return $this->p_n_c_financiamentos_bancarios;
		}
		function gerarImagemBallonP_n_c_financiamentos_bancarios(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="p_n_c_financiamentos_bancarios" style="cursor: pointer;">';
		}
		function gerarInputP_n_c_financiamentos_bancarios($value){
			return '<input class="input_tabela currency input_valor p_n_c_financiamentos_bancarios" type="text" name="p_n_c_financiamentos_bancarios" value="'.$value.'" disabled="disabled">';
		}
		function gerarBallonInputP_n_c_financiamentos_bancarios($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="p_n_c_financiamentos_bancarios" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function setP_n_c_total(){
			$this->p_n_c_total = $this->getP_n_c_contas_pagar()+$this->getP_n_c_financiamentos_bancarios();
		}
		function getP_n_c_total(){
			$this->setP_n_c_total();
			return $this->p_n_c_total;
		}
		function gerarImagemBallonP_n_c_total(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="p_n_c_total" style="cursor: pointer;">';
		}
		function gerarInputP_n_c_total($value){
			return '<input class="input_tabela currency input_valor p_n_c_total" type="text" name="p_n_c_total" value="'.$value.'" disabled="disabled">';
		}
		function gerarBallonInputP_n_c_total($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="p_n_c_total" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function setP_l_capital_social($string){
			$this->p_l_capital_social = $string;
		}
		function getP_l_capital_social(){
			return $this->p_l_capital_social;
		}
		function gerarImagemBallonP_l_capital_social(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="p_l_capital_social" style="cursor: pointer;">';
		}
		function gerarInputP_l_capital_social($value){
			return '<input class="input_tabela currency input_valor p_l_capital_social" type="text" name="p_l_capital_social" value="'.$value.'" disabled="disabled">';
		}
		function gerarBallonInputP_l_capital_social($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="p_l_capital_social" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function setP_l_reservas_capital($string){
			$this->p_l_reservas_capital = $string;
		}
		function getP_l_reservas_capital(){
			return $this->p_l_reservas_capital;
		}
		function gerarImagemBallonP_l_reservas_capital(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="p_l_reservas_capital" style="cursor: pointer;">';
		}
		function gerarInputP_l_reservas_capital($value){
			return '<input class="input_tabela currency input_valor p_l_reservas_capital" type="text" name="p_l_reservas_capital" value="'.$value.'" disabled="disabled">';
		}
		function gerarBallonInputP_l_reservas_capital($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="p_l_reservas_capital" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function setP_l_ajustes_avaliacao_patrimonial($string){
			$this->p_l_ajustes_avaliacao_patrimonial = $string;
		}
		function getP_l_ajustes_avaliacao_patrimonial(){
			$consulta = mysql_query("SELECT * FROM imobilizados WHERE id_user = '".$this->id."' AND YEAR(data) = '".$this->ano."' ");
			$total = 0;
			while( $objeto=mysql_fetch_array($consulta) ){
				$total = $total + ( $objeto['valor'] - $objeto['valor_mercado'] ) * $objeto['quantidade']  ;
			}
			return $total;
		}
		function gerarImagemBallonP_l_ajustes_avaliacao_patrimonial(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="p_l_ajustes_avaliacao_patrimonial" style="cursor: pointer;">';
		}
		function gerarInputP_l_ajustes_avaliacao_patrimonial($value){
			return '<input class="input_tabela currency input_valor p_l_ajustes_avaliacao_patrimonial" type="text" name="p_l_ajustes_avaliacao_patrimonial" value="'.$value.'" disabled="disabled">';
		}
		function gerarBallonInputP_l_ajustes_avaliacao_patrimonial($string){				
			return '
				<div style="width:310px; position:absolute; display:none;" id="p_l_ajustes_avaliacao_patrimonial" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>
			';
		}
		function setP_l_reservas_lucro($string){
			$this->p_l_reservas_lucro = $string;
		}
		function getP_l_reservas_lucro(){
			return $this->p_l_reservas_lucro;
		}
		function gerarImagemBallonP_l_reservas_lucro(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="p_l_reservas_lucro" style="cursor: pointer;">';
		}
		function gerarInputP_l_reservas_lucro($value){
			return '<input class="input_tabela currency input_valor p_l_reservas_lucro" type="text" name="p_l_reservas_lucro" value="'.$value.'" disabled="disabled">';
		}
		function gerarBallonInputP_l_reservas_lucro($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="p_l_reservas_lucro" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function setP_l_lucros_acumulados(){
			$dre = new Gerar_DRE();
			$dre->setano($this->ano);
			$dre->gerarDre();		
			if( $dre->getResultadoDre() > 0 )
				$this->p_l_lucros_acumulados = $dre->getResultadoDre();
			else
				$this->p_l_lucros_acumulados = 0;
			// $this->p_l_lucros_acumulados = $string;
		}
		function getP_l_lucros_acumulados(){
			$this->setP_l_lucros_acumulados();
			return $this->p_l_lucros_acumulados;
		}
		function gerarImagemBallonP_l_lucros_acumulados(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="p_l_lucros_acumulados" style="cursor: pointer;">';
		}
		function gerarInputP_l_lucros_acumulados($value){
			return '<input class="input_tabela  input_valor p_l_lucros_acumulados" type="text" name="p_l_lucros_acumulados" value="'.$value.'" disabled="disabled">';
		}
		function gerarBallonInputP_l_lucros_acumulados($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="p_l_lucros_acumulados" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function setP_l_prejuizos_acumulados(){
			$dre = new Gerar_DRE();
			$dre->setano($this->ano);
			$dre->gerarDre();		
			if( $dre->getResultadoDre() < 0 )
				$this->p_l_prejuizos_acumulados = $dre->getResultadoDre();
			else
				$this->p_l_prejuizos_acumulados = 0;
			// $this->p_l_prejuizos_acumulados = $string;
		}
		function getP_l_prejuizos_acumulados(){
			$this->setP_l_prejuizos_acumulados();
			return $this->p_l_prejuizos_acumulados;
		}
		function gerarImagemBallonP_l_prejuizos_acumulados(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="p_l_prejuizos_acumulados" style="cursor: pointer;">';
		}
		function gerarInputP_l_prejuizos_acumulados($value){
			return '<input class="input_tabela  input_valor p_l_prejuizos_acumulados" type="text" name="p_l_prejuizos_acumulados" value="'.$value.'" disabled="disabled">';
		}
		function gerarBallonInputP_l_prejuizos_acumulados($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="p_l_prejuizos_acumulados" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function setP_l_total(){
			$this->p_l_total = $this->getP_l_capital_social()+$this->getP_l_reservas_capital()+$this->getP_l_ajustes_avaliacao_patrimonial()+$this->getP_l_reservas_lucro()+$this->getP_l_lucros_acumulados()+$this->getP_l_prejuizos_acumulados();
		}
		function getP_l_total(){
			$this->setP_l_total();
			return $this->p_l_total;
		}
		function gerarImagemBallonP_l_total(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="p_l_total" style="cursor: pointer;">';
		}
		function gerarInputP_l_total($value){
			return '<input class="input_tabela currency input_valor p_l_total" type="text" name="p_l_total" value="'.$value.'" disabled="disabled">';
		}
		function gerarBallonInputP_l_total($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="p_l_total" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
	}
	class Itens{
		function __construct(){
			$this->itens = array();
			$this->array_itens = array();
		}
		private $itens;
		private $total;
		private $array_itens;
		function getItensArray(){
			return $this->array_itens;
		}
		//Armazena no vetor o item no formato vet[$key] = "key___value", incrementando o valor do item a cada repetição dele
		function putItem($key,$value,$paridade = 1){
			$value = floatval($value)*$paridade;
			if( isset( $this->itens[$key] ) ){
				$this->itens[$key] = floatval( $this->itens[$key] ) + floatval( $value );
				$this->array_itens [$key] = $key.'___'.$this->itens[$key];
				
				// $this->array_itens [] = $key.'___'.$this->itens[$key];
			}
			else{
				$this->itens[$key] = floatval( $value );
				$this->array_itens [$key] = $key.'___'.$this->itens[$key];
				
				// $this->array_itens [] = $key.'___'.$this->itens[$key];
 			}
		}
		function getItens(){
			return $this->itens;
		}
		function getTotal(){
			$aux = 0;
			foreach ($this->itens as $value) {
				$aux = $aux + $value;
			}
			return $aux;
		}
	}
	class Receita{
		private $receita;	
		private $impostos;	
		private $receitas_diversas;
		function __construct(){
			
			$this->receita = array();
			$this->impostos = array();
			$this->receitas_diversas = array();
			//Pega os clientes 
			$sql_clientes = mysql_query("SELECT DISTINCT apelido FROM dados_clientes WHERE id_login = ".id_user." ORDER BY apelido");
			while( $rs_clientes = mysql_fetch_array($sql_clientes) ){
				array_push($this->receita, 
					strtoupper($this->Normaliza($rs_clientes['apelido']))
				);	
			}		
			array_push($this->receita, 
					strtoupper($this->Normaliza("Outros"))
				);	
			array_push($this->impostos, 
				strtoupper($this->Normaliza("Impostos e encargos"))
			);				
			array_push($this->receitas_diversas, 
			// 	strtoupper($this->Normaliza("Rendimentos de aplicação")),
			// 	strtoupper($this->Normaliza("Outros")),
			// 	// strtoupper($this->Normaliza("Aumento de Capital")),
				strtoupper($this->Normaliza("Serviços prestados em geral"))
			);				
			
		}
		private function Normaliza($string) {
			$table = array('Š'=>'S','š'=>'s','Đ'=>'Dj','đ'=>'dj','Ž'=>'Z','ž'=>'z','Č'=>'C','č'=>'c','Ć'=>'C','ć'=>'c','À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Å'=>'A','Æ'=>'A','Ç'=>'C','È'=>'E','É'=>'E',
			'Ê'=>'E','Ë'=>'E','Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I','Ñ'=>'N','Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O','Ø'=>'O','Ù'=>'U','Ú'=>'U','Û'=>'U','Ü'=>'U','Ý'=>'Y','Þ'=>'B','ß'=>'Ss',
			'à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a','æ'=>'a','ç'=>'c','è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ð'=>'o','ñ'=>'n','ò'=>'o','ó'=>'o',
			'ô'=>'o','õ'=>'o','ö'=>'o','ø'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ý'=>'y','ý'=>'y','þ'=>'b','ÿ'=>'y','Ŕ'=>'R','ŕ'=>'r','?'=>'');
			return strtr($string, $table);
		}
		function isReceita($string){
			if( in_array( strtoupper ( $this->Normaliza ( $string ) ) , $this->receita ) )
				return true;
			if( in_array( strtoupper ( $this->Normaliza ( $string ) ) , $this->impostos ) )
				return true;
			if( in_array( strtoupper ( $this->Normaliza ( $string ) ) , $this->receitas_diversas ) )
				return true;
			return false;
		}
		function isDeducao($string){
			if( in_array( strtoupper ( $this->Normaliza ( $string ) ) , $this->impostos ) )
				return true;
		}
	}
	class ReceitaFinanceira{
		private $receita;	
		private $impostos;	
		private $receitas_diversas;
		function __construct(){
			
			$this->receita = array();
			$this->impostos = array();
			$this->receitas_diversas = array();
			//Pega os clientes 
			// $sql_clientes = mysql_query("SELECT DISTINCT apelido FROM dados_clientes WHERE id_login = ".id_user." ORDER BY apelido");
			// while( $rs_clientes = mysql_fetch_array($sql_clientes) ){
			// 	array_push($this->receita, 
			// 		strtoupper($this->Normaliza($rs_clientes['apelido']))
			// 	);	
			// }		
			// array_push($this->impostos, 
			// 	strtoupper($this->Normaliza("Impostos e encargos"))
			// );				
			array_push($this->receitas_diversas, 
				strtoupper($this->Normaliza("Rendimentos de aplicação"))
				// strtoupper($this->Normaliza("Outros"))
				// strtoupper($this->Normaliza("Aumento de Capital")),
				// strtoupper($this->Normaliza("Serviços prestados em geral"))
			);				
			
		}
		private function Normaliza($string) {
			$table = array('Š'=>'S','š'=>'s','Đ'=>'Dj','đ'=>'dj','Ž'=>'Z','ž'=>'z','Č'=>'C','č'=>'c','Ć'=>'C','ć'=>'c','À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Å'=>'A','Æ'=>'A','Ç'=>'C','È'=>'E','É'=>'E',
			'Ê'=>'E','Ë'=>'E','Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I','Ñ'=>'N','Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O','Ø'=>'O','Ù'=>'U','Ú'=>'U','Û'=>'U','Ü'=>'U','Ý'=>'Y','Þ'=>'B','ß'=>'Ss',
			'à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a','æ'=>'a','ç'=>'c','è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ð'=>'o','ñ'=>'n','ò'=>'o','ó'=>'o',
			'ô'=>'o','õ'=>'o','ö'=>'o','ø'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ý'=>'y','ý'=>'y','þ'=>'b','ÿ'=>'y','Ŕ'=>'R','ŕ'=>'r','?'=>'');
			return strtr($string, $table);
		}
		function isReceitaFinanceira($string){
			if( in_array( strtoupper ( $this->Normaliza ( $string ) ) , $this->receitas_diversas ) )
				return true;
		}
		function isDeducao($string){
			if( in_array( strtoupper ( $this->Normaliza ( $string ) ) , $this->impostos ) )
				return true;
		}
	}
	class DespesasFinanceiras{
		private $despesa_financeira;	
		function __construct(){
			
			$this->despesa_financeira = array();
			array_push($this->despesa_financeira, 
				strtoupper($this->Normaliza("Despesas bancárias"))
				// strtoupper($this->Normaliza("Outros"))
				// strtoupper($this->Normaliza("Aumento de Capital")),
				// strtoupper($this->Normaliza("Serviços prestados em geral"))
			);				
			
		}
		private function Normaliza($string) {
			$table = array('Š'=>'S','š'=>'s','Đ'=>'Dj','đ'=>'dj','Ž'=>'Z','ž'=>'z','Č'=>'C','č'=>'c','Ć'=>'C','ć'=>'c','À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Å'=>'A','Æ'=>'A','Ç'=>'C','È'=>'E','É'=>'E',
			'Ê'=>'E','Ë'=>'E','Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I','Ñ'=>'N','Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O','Ø'=>'O','Ù'=>'U','Ú'=>'U','Û'=>'U','Ü'=>'U','Ý'=>'Y','Þ'=>'B','ß'=>'Ss',
			'à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a','æ'=>'a','ç'=>'c','è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ð'=>'o','ñ'=>'n','ò'=>'o','ó'=>'o',
			'ô'=>'o','õ'=>'o','ö'=>'o','ø'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ý'=>'y','ý'=>'y','þ'=>'b','ÿ'=>'y','Ŕ'=>'R','ŕ'=>'r','?'=>'');
			return strtr($string, $table);
		}
		function isDespesaFinanceira($string){
			if( in_array( strtoupper ( $this->Normaliza ( $string ) ) , $this->despesa_financeira ) )
				return true;
		}
	}
	class Despesas{
		private $despesas;		
		function __construct(){
			
			$this->despesas = array();
			// //Pega os clientes 
			// $sql_clientes = mysql_query("SELECT DISTINCT apelido FROM dados_clientes WHERE id_login = ".id_user." ORDER BY apelido");
			// while( $rs_clientes = mysql_fetch_array($sql_clientes) ){
			// 	array_push($this->ativos_circulante_validos, 
			// 		strtoupper($this->Normaliza($rs_clientes['apelido']))
			// 	);	
			// }			
			array_push($this->despesas, 
				// strtoupper($this->Normaliza("Despesas bancárias")),
				strtoupper($this->Normaliza("Água")),
				strtoupper($this->Normaliza("Aluguel")),
				strtoupper($this->Normaliza("Combustível")),
				strtoupper($this->Normaliza("Correios")),
				strtoupper($this->Normaliza("Energia elétrica")),
				strtoupper($this->Normaliza("Condomínio")),
				strtoupper($this->Normaliza("Contador")),
				strtoupper($this->Normaliza("Equipamentos")),
				strtoupper($this->Normaliza("Internet")),
				strtoupper($this->Normaliza("Licença ou aluguel de softwares")),
				strtoupper($this->Normaliza("Limpeza")),
				strtoupper($this->Normaliza("Manutenção de equipamentos")),
				strtoupper($this->Normaliza("Manutenção veículo")),
				strtoupper($this->Normaliza("Material de escritório")),
				strtoupper($this->Normaliza("Telefone")),
				strtoupper($this->Normaliza("Vale-Transporte")),
				strtoupper($this->Normaliza("Vale-Refeição")),
				strtoupper($this->Normaliza("Viagens e deslocamentos")),
				strtoupper($this->Normaliza("Seguros")),
				strtoupper($this->Normaliza("Transportadora / Motoboy")),
				strtoupper($this->Normaliza("Marketing e publicidade")),
				strtoupper($this->Normaliza("Cursos e treinamentos")),
				// strtoupper($this->Normaliza("Devolução de adiantamento")),
				strtoupper($this->Normaliza("Ajuste caixa")),
				strtoupper($this->Normaliza("Seguros")),
				strtoupper($this->Normaliza("Estagiários")),
				// strtoupper($this->Normaliza("Impostos e encargos")),
				strtoupper($this->Normaliza("Pgto. a autônomos e fornecedores")),
				strtoupper($this->Normaliza("Pgto. de salários")),
				strtoupper($this->Normaliza("Pró-Labore")),
				strtoupper($this->Normaliza("Reembolso de despesas")),
				strtoupper($this->Normaliza("Outros"))
			);			
		}
		private function Normaliza($string) {
			$table = array('Š'=>'S','š'=>'s','Đ'=>'Dj','đ'=>'dj','Ž'=>'Z','ž'=>'z','Č'=>'C','č'=>'c','Ć'=>'C','ć'=>'c','À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Å'=>'A','Æ'=>'A','Ç'=>'C','È'=>'E','É'=>'E',
			'Ê'=>'E','Ë'=>'E','Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I','Ñ'=>'N','Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O','Ø'=>'O','Ù'=>'U','Ú'=>'U','Û'=>'U','Ü'=>'U','Ý'=>'Y','Þ'=>'B','ß'=>'Ss',
			'à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a','æ'=>'a','ç'=>'c','è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ð'=>'o','ñ'=>'n','ò'=>'o','ó'=>'o',
			'ô'=>'o','õ'=>'o','ö'=>'o','ø'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ý'=>'y','ý'=>'y','þ'=>'b','ÿ'=>'y','Ŕ'=>'R','ŕ'=>'r','?'=>'');
			return strtr($string, $table);
		}
		function isDespesas($string){
			if( in_array( strtoupper ( $this->Normaliza ( $string ) ) , $this->despesas ) )
				return true;
			return false;
		}
	}
	class Dre{
		private $total_receita;
		private $total_despesa;
		private $textoArray;
		function getTextoArray(){
			return $this->textoArray;
		}
		function setTextoArray($string){
			$this->textoArray = $string;
		}
		function __construct(){
		}
		function getReceita(){
			return $this->total_receita;
		}
		function getDespesa(){
			return $this->total_despesa;
		}
		function gerarReceita($ano){
			//Recebe os ativos circulantes
			$receita_itens = new Itens();
			//Verifica se é ativo válido
			$receita = new Receita();

			//Busca os dados de entrada e saída para o usuario
			$consulta = mysql_query("SELECT * FROM user_".id_user."_livro_caixa WHERE data >= '".$ano."-01-01' AND data <= '".$ano."-12-31' AND entrada > 0 OR ( categoria = 'Impostos e encargos' AND data >= '".$ano."-01-01' AND data <= '".$ano."-12-31' ) ");
			while( $objeto=mysql_fetch_array($consulta) ){
				$key = $objeto['categoria'];
				if( $key == "Impostos e encargos")
					$value = $objeto['saida'];
				else	
					$value = $objeto['entrada'];
				
				//Percorre cada item, inserindo os itens de acorcom com uma lista
				if( $receita->isReceita($key) ){
					if( $receita->isDeducao($key) )
						$receita_itens->putItem($key,$value,-1);
					else
						$receita_itens->putItem($key,$value);

				}
			}
			//Salva o valor total de ativos circulantes
			$this->total_receita = $receita_itens->getTotal();
			//Salva o valor total de ativos não circulantes
			//Salva o total de ativos
			//Pega o vetor no formato categoria___valor para preencher as tabelas dos ativos circulantes
			$this->setTextoArray($receita_itens->getItensArray());	
			// var_dump($this->textoArray);
		}
		function gerarReceitaFinanceira($ano){
			//Recebe os ativos circulantes
			$receita_itens = new Itens();
			//Verifica se é ativo válido
			$receita = new ReceitaFinanceira();

			//Busca os dados de entrada e saída para o usuario
			$consulta = mysql_query("SELECT * FROM user_".id_user."_livro_caixa WHERE data >= '".$ano."-01-01' AND data <= '".$ano."-12-31' AND entrada > 0 ");
			while( $objeto=mysql_fetch_array($consulta) ){
				$key = $objeto['categoria'];
				$value = $objeto['entrada'];
				//Percorre cada item, inserindo os itens de acorcom com uma lista
				if( $receita->isReceitaFinanceira($key) ){
					if( $receita->isDeducao($key) )
						$receita_itens->putItem($key,$value,-1);
					else
						$receita_itens->putItem($key,$value);

				}
			}
			//Salva o valor total de ativos circulantes
			$this->total_receita = $receita_itens->getTotal();
			//Salva o valor total de ativos não circulantes
			//Salva o total de ativos
			//Pega o vetor no formato categoria___valor para preencher as tabelas dos ativos circulantes
			$this->setTextoArray($receita_itens->getItensArray());	
			// var_dump($this->textoArray);
		}
		function gerarDespesas($ano){
			//Recebe os ativos circulantes
			$despesas_itens = new Itens();
			//Verifica se é ativo válido
			$despesas = new Despesas();

			//Busca os dados de entrada e saída para o usuario
			$consulta = mysql_query("SELECT * FROM user_".id_user."_livro_caixa WHERE data >= '".$ano."-01-01' AND data <= '".$ano."-12-31' AND saida > 0");
			while( $objeto=mysql_fetch_array($consulta) ){
				$key = $objeto['categoria'];
				$value = $objeto['saida'];

				//Percorre cada item, inserindo os itens de acorcom com uma lista
				if( $despesas->isDespesas($key) )
					$despesas_itens->putItem($key,$value,-1);
			}
			//Salva o valor total de ativos circulantes
			$this->total_despesa = $despesas_itens->getTotal();
			//Salva o valor total de ativos não circulantes
			//Salva o total de ativos
			//Pega o vetor no formato categoria___valor para preencher as tabelas dos ativos circulantes
			$this->setTextoArray($despesas_itens->getItensArray());	
			// var_dump($this->textoArray);
		}
		function gerarDespesasFinanceiras($ano){
			//Recebe os ativos circulantes
			$despesas_itens = new Itens();
			//Verifica se é ativo válido
			$despesas = new DespesasFinanceiras();

			//Busca os dados de entrada e saída para o usuario
			$consulta = mysql_query("SELECT * FROM user_".id_user."_livro_caixa WHERE data >= '".$ano."-01-01' AND data <= '".$ano."-12-31' AND saida > 0");
			while( $objeto=mysql_fetch_array($consulta) ){
				$key = $objeto['categoria'];
				$value = $objeto['saida'];

				//Percorre cada item, inserindo os itens de acorcom com uma lista
				if( $despesas->isDespesaFinanceira($key) )
					$despesas_itens->putItem($key,$value,-1);
			}
			//Salva o valor total de ativos circulantes
			$this->total_despesa = $despesas_itens->getTotal();
			//Salva o valor total de ativos não circulantes
			//Salva o total de ativos
			//Pega o vetor no formato categoria___valor para preencher as tabelas dos ativos circulantes
			$this->setTextoArray($despesas_itens->getItensArray());	
			// var_dump($this->textoArray);
		}
	}
	class Plano_de_contas{
		
		private $textoArray;
		private $tipo;

		function getTipo(){
			return $this->tipo;
		}
		function setTipo($string){
			$this->tipo = $string;
		}
		function getTextoArray(){
			return $this->textoArray;
		}
		function setTextoArray($string){
			$this->textoArray = $string;
		}
		function gerarItens($ano){
			//Recebe os ativos circulantes
			$itens = new Itens();

			//Busca os dados de entrada e saída para o usuario
			$consulta = mysql_query("SELECT * FROM user_".id_user."_livro_caixa WHERE data >= '".$ano."-01-01' AND data <= '".$ano."-12-31' ");
			while( $objeto=mysql_fetch_array($consulta) ){
				$key = $objeto['categoria'];
				if( $objeto['saida'] > 0)
					$value = $objeto['saida'];
				else	
					$value = $objeto['entrada'];
				
				//Percorre cada item, inserindo os itens de acorcom com uma lista
				if( $this->isTipo($key,$objeto['entrada']) )
					$itens->putItem($key,$value);

			}
			if( $this->tipo == 'depreciacao' )
				$this->setTextoArray($this->setItensDepreciacao());
			else
				$this->setTextoArray($itens->getItensArray());	
		}
		function isTipo($key,$entrada){

			switch ($this->tipo) {
				case 'vendas-de-servicos':
					if( $this->isVendasDeServico($key) )
						return true;
					break;
				case 'deducoes-com-impostos':
					if( $this->isDeducoesComImpostos($key) )
						return true;
					break;
				case 'despesas-com-pessoal':
					if( $this->isDespesaComPessoal($key) )
						return true;
					break;
				case 'despesas-administrativas':
					if( $this->isDespesaAdministrativa($key) )
						return true;
					break;
				case 'despesas-com-vendas':
					if( $this->isDespesaComVendas($key) )
						return true;
					break;
				case 'receitas-financeiras':
					if( $this->isReceitaFinanceira($key) && $entrada > 0 )
						return true;
					break;
				case 'despesas-financeiras':
					if( $this->isDespesaFinanceira($key) )
						return true;
					break;
				case 'outras-receitas':
					if( $this->isOutrasReceitas($key) && $entrada > 0 )
						return true;
					break;
				case 'outras-despesas':
					if( $this->isOutrasDespesas($key) && $entrada == 0 )
						return true;
					break;
			}

		}
		function setItensDepreciacao(){
			$datas = new Datas();
			$id = $_SESSION['id_empresaSecao'];
			if( isset($_GET['ano']) )
				$ano = $_GET['ano'];
			else
				$ano = date("Y");

			$tabela_depreciacao = array();
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

			$consulta = mysql_query("SELECT * FROM imobilizados WHERE id_user = '".$id."' AND YEAR(data) <= '".$ano."' ");
			$total = 0;
			while( $objeto=mysql_fetch_array($consulta) ){
				if( floatval($ano) - floatval( $datas->getAno($objeto['data']) ) <= $vida_util[$objeto['item']] ){
					// $vida = floatval($ano) - floatval($objeto['data']);
					// $vida = $this->calcularAnosDepreciacao($objeto['data']);
					// $meses_vida = $this->calcularMesesDepreciacao($objeto['data']);
					// $valor_total_item = ( floatval($objeto['quantidade']) * floatval($objeto['valor']) );
					// $total_depreciacao = 0;
					// for ($i=1; $i <= $vida; $i++) { 
					// 	$parcial_depreciacao = ( $valor_total_item * floatval($tabela_depreciacao[$objeto['item']]) );
					// 	$total_depreciacao = $total_depreciacao + $parcial_depreciacao;
					// 	$valor_total_item = $valor_total_item - $parcial_depreciacao;						
					// }
					// $total_depreciacao = $total_depreciacao + ( $meses_vida / 12 ) * ( $valor_total_item * floatval($tabela_depreciacao[$objeto['item']]) );
					$meses_depreciacao = $this->getMesesDepreciacao($objeto['data']);
					$depreciacao = floatval($objeto['valor']) * floatval($tabela_depreciacao[$objeto['item']]);
					$depreciacao = $depreciacao / 12;
					$total_depreciacao = $depreciacao * $meses_depreciacao;
					// echo $objeto['valor'];
					// echo '<br>';
					// echo $meses_depreciacao;
					// echo '<br>';
					// echo $depreciacao;
					if( $total_depreciacao <= 0 )
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

			$consulta = mysql_query("SELECT * FROM intangiveis WHERE id_user = '".$id."' AND YEAR(data) <= '".$ano."' ");
			while( $objeto=mysql_fetch_array($consulta) ){
				if( floatval($ano) - floatval( $datas->getAno($objeto['data']) ) <= $vida_util[$objeto['item']] ){
					// $vida = floatval($ano) - floatval($objeto['data']);
					// $vida = $this->calcularAnosDepreciacao($objeto['data']);
					// $meses_vida = $this->calcularMesesDepreciacao($objeto['data']);
					// $valor_total_item = ( floatval($objeto['quantidade']) * floatval($objeto['valor']) );
					// $total_depreciacao = 0;
					// for ($i=1; $i <= $vida; $i++) { 
					// 	$parcial_depreciacao = ( $valor_total_item * floatval($tabela_depreciacao[$objeto['item']]) );
					// 	$total_depreciacao = $total_depreciacao + $parcial_depreciacao;
					// 	$valor_total_item = $valor_total_item - $parcial_depreciacao;						
					// }
					// $total_depreciacao = $total_depreciacao + ( $meses_vida / 12 ) * ( $valor_total_item * floatval($tabela_depreciacao[$objeto['item']]) );
					$meses_depreciacao = $this->getMesesDepreciacao($objeto['data']);
					$depreciacao = floatval($objeto['valor']) * floatval($tabela_depreciacao[$objeto['item']]);
					$depreciacao = $depreciacao / 12;
					$total_depreciacao = $depreciacao * $meses_depreciacao;
					// echo $objeto['valor'];
					// echo '<br>';
					// echo $meses_depreciacao;
					// echo '<br>';
					// echo $depreciacao;
					if( $total_depreciacao <= 0 )
						$total_depreciacao = 0;
					$total = $total + $total_depreciacao;
				}
			}

			// $id = $_SESSION['id_empresaSecao'];
			// if( isset($_GET['ano']) )
			// 	$ano = $_GET['ano'];
			// else
			// 	$ano = date("Y");

			// $tabela_depreciacao = array();

			// $vida_util = array();
			// $vida_util['Veículos'] = floatval(5);
			// $vida_util['Imóveis (prédios)'] = floatval(25);
			// $vida_util['Móveis e utensílios'] = floatval(10);
			// $vida_util['Computadores e periféricos'] = floatval(5);
			// $vida_util['Máquinas e equipamentos'] = floatval(10);

			// $tabela_depreciacao['Veículos'] = floatval(0.2);
			// $tabela_depreciacao['Imóveis (prédios)'] = floatval(0.04);
			// $tabela_depreciacao['Móveis e utensílios'] = floatval(0.1);
			// $tabela_depreciacao['Computadores e periféricos'] = floatval(0.2);
			// $tabela_depreciacao['Máquinas e equipamentos'] = floatval(0.1);

			// $consulta = mysql_query("SELECT * FROM imobilizados WHERE id_user = '".$id."' AND ano <= '".$ano."' ");
			// $total = array();
			// while( $objeto=mysql_fetch_array($consulta) ){
			// 	if( floatval($ano) - floatval($objeto['data']) <= $vida_util[$objeto['item']] ){
			// 		$vida = floatval($ano) - floatval($objeto['data']);
			// 		$valor_total_item = ( floatval($objeto['quantidade']) * floatval($objeto['valor']) );
			// 		$total_depreciacao = 0;
			// 		for ($i=1; $i <= $vida; $i++) { 
			// 			$parcial_depreciacao = ( $valor_total_item * floatval($tabela_depreciacao[$objeto['item']]) );
			// 			$total_depreciacao = $total_depreciacao + $parcial_depreciacao;
			// 			$valor_total_item = $valor_total_item - $parcial_depreciacao;
			// 		}
			// 		$parcial_depreciacao = ( $valor_total_item * floatval($tabela_depreciacao[$objeto['item']]) );
			// 		if( $vida == 0 )
			// 			$parcial_depreciacao = 0;
			// 		$total[$objeto['item']] = $objeto['item'].'___'.$parcial_depreciacao;
					
			// 	}
			// }


			// $vida_util = array();
			// $vida_util['Software'] = floatval(5);
			// $vida_util['Marca'] = floatval(99999);
			// $vida_util['Patente'] = floatval(10);
			// $vida_util['Direitos autorais'] = floatval(99999);
			// $vida_util['Licenças'] = floatval(10);
			// $vida_util['Pesquisa e desenvolvimento'] = floatval(10);
			
			// $tabela_depreciacao = array();
			// $tabela_depreciacao['Software'] = floatval(0.2);
			// $tabela_depreciacao['Marca'] = floatval(0);
			// $tabela_depreciacao['Patente'] = floatval(0.1);
			// $tabela_depreciacao['Direitos autorais'] = floatval(0);
			// $tabela_depreciacao['Licenças'] = floatval(0.1);
			// $tabela_depreciacao['Pesquisa e desenvolvimento'] = floatval(0.1);

			// $consulta = mysql_query("SELECT * FROM intangiveis WHERE id_user = '".$id."' AND ano <= '".$ano."' ");
			// while( $objeto=mysql_fetch_array($consulta) ){
			// 	if( floatval($ano) - floatval($objeto['ano_item']) <= $vida_util[$objeto['item']] ){
			// 		$vida = floatval($ano) - floatval($objeto['ano_item']);
			// 		$valor_total_item = ( floatval($objeto['quantidade']) * floatval($objeto['valor']) );
			// 		$total_depreciacao = 0;
			// 		for ($i=1; $i <= $vida; $i++) { 
			// 			$parcial_depreciacao = ( $valor_total_item * floatval($tabela_depreciacao[$objeto['item']]) );
			// 			$total_depreciacao = $total_depreciacao + $parcial_depreciacao;
			// 			$valor_total_item = $valor_total_item - $parcial_depreciacao;
			// 		}
			// 		$parcial_depreciacao = ( $valor_total_item * floatval($tabela_depreciacao[$objeto['item']]) );
			// 		if( $vida == 0 )
			// 			$parcial_depreciacao = 0;
			// 		$total[$objeto['item']] = $objeto['item'].'___'.$parcial_depreciacao;
					
			// 	}
			// }

			return $total;

		}
		function isOutrasDespesas($string){
			//Define outros itens
			$outros = array();
			array_push($outros, 
				strtoupper($this->Normaliza("Outros"))
				// strtoupper($this->Normaliza("Comissões"))
			);
			if( in_array( strtoupper( $this->Normaliza ( $string ) ) , $outros ) )
				return true;
		}
		function isOutrasReceitas($string){
			//Define outros itens
			$outros = array();
			array_push($outros, 
				strtoupper($this->Normaliza("Outros"))
				// strtoupper($this->Normaliza("Comissões"))
			);
			if( in_array( strtoupper( $this->Normaliza ( $string ) ) , $outros ) )
				return true;
		}
		function isDespesaFinanceira($string){
			// Define os itens de despesa bancaria
			$despesas_bancarias = array();
			array_push($despesas_bancarias, 
				strtoupper($this->Normaliza("Despesas bancárias"))
			);
			if( in_array( strtoupper( $this->Normaliza ( $string ) ) , $despesas_bancarias ) )
				return true;
		}
		function isReceitaFinanceira($string){
			//Define os itens de despesa de vendas
			$receitas_diversas = array();
			array_push($receitas_diversas, 
				strtoupper($this->Normaliza("Rendimentos de aplicação"))
				// strtoupper($this->Normaliza("Outros"))
			);
			if( in_array( strtoupper( $this->Normaliza ( $string ) ) , $receitas_diversas ) )
				return true;
		}
		function isDespesaComVendas($string){
			//Define os itens de despesa de vendas
			$despesas_com_vendas = array();
			array_push($despesas_com_vendas, 
				strtoupper($this->Normaliza("Marketing e publicidade"))
				// strtoupper($this->Normaliza("Devolução de adiantamento"))
			);
			if( in_array( strtoupper( $this->Normaliza ( $string ) ) , $despesas_com_vendas ) )
				return true;
		}
		function isDespesaAdministrativa($string){
			$despesas_administrativas = array();
			array_push($despesas_administrativas, 
				strtoupper($this->Normaliza("Água")),
				strtoupper($this->Normaliza("Aluguel")),
				strtoupper($this->Normaliza("Combustível")),
				strtoupper($this->Normaliza("Correios")),
				strtoupper($this->Normaliza("Energia elétrica")),
				strtoupper($this->Normaliza("Condomínio")),
				strtoupper($this->Normaliza("Equipamentos")),
				strtoupper($this->Normaliza("Empréstimo (amortização)")),
				strtoupper($this->Normaliza("Internet")),
				strtoupper($this->Normaliza("Empréstimos")),
				strtoupper($this->Normaliza("Licença ou aluguel de softwares")),
				strtoupper($this->Normaliza("Limpeza")),
				strtoupper($this->Normaliza("Manutenção de equipamentos")),
				strtoupper($this->Normaliza("Manutenção veículo")),
				strtoupper($this->Normaliza("Material de escritório")),
				strtoupper($this->Normaliza("Telefone")),
				strtoupper($this->Normaliza("Viagens e deslocamentos")),
				strtoupper($this->Normaliza("Seguros")),
				strtoupper($this->Normaliza("Transportadora / Motoboy")),
				strtoupper($this->Normaliza("Cursos e treinamentos")),
				strtoupper($this->Normaliza("Contador")),
				strtoupper($this->Normaliza("Ajuste caixa"))
				
			);		
			if( in_array( strtoupper( $this->Normaliza ( $string ) ) , $despesas_administrativas ) )
				return true;
		}
		function isDespesaComPessoal($string){
			$despesa_pessoal = array();
			array_push($despesa_pessoal, 
				strtoupper($this->Normaliza("Estagiários")),
				strtoupper($this->Normaliza("Pgto. a autônomos e fornecedores")),
				strtoupper($this->Normaliza("Pgto. de salários")),
				strtoupper($this->Normaliza("Pró-Labore")),
				strtoupper($this->Normaliza("Vale-Transporte")),
				strtoupper($this->Normaliza("Vale-Refeição")),
				strtoupper($this->Normaliza("Reembolso de despesas"))
			);
			if( in_array( strtoupper( $this->Normaliza ( $string ) ) , $despesa_pessoal ) )
				return true;
		}
		function isVendasDeServico($string){
			$clientes = array();
			//Pega os clientes do usuário
			$sql_clientes = mysql_query("SELECT DISTINCT apelido FROM dados_clientes WHERE id_login = ".id_user." ORDER BY apelido");
			while( $rs_clientes = mysql_fetch_array($sql_clientes) ){
				array_push($clientes, 
					strtoupper($this->Normaliza($rs_clientes['apelido']))
				);	
			}
			if( in_array( strtoupper( $this->Normaliza ( $string ) ) , $clientes ) )
				return true;
		}
		function isDeducoesComImpostos($string){
			$impostos = array();
			array_push($impostos, 
				strtoupper($this->Normaliza("Impostos e encargos"))
			);
			if( in_array( strtoupper( $this->Normaliza ( $string ) ) , $impostos ) )
				return true;
		}
		private function Normaliza($string) {
			$table = array('Š'=>'S','š'=>'s','Đ'=>'Dj','đ'=>'dj','Ž'=>'Z','ž'=>'z','Č'=>'C','č'=>'c','Ć'=>'C','ć'=>'c','À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Å'=>'A','Æ'=>'A','Ç'=>'C','È'=>'E','É'=>'E',
			'Ê'=>'E','Ë'=>'E','Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I','Ñ'=>'N','Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O','Ø'=>'O','Ù'=>'U','Ú'=>'U','Û'=>'U','Ü'=>'U','Ý'=>'Y','Þ'=>'B','ß'=>'Ss',
			'à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a','æ'=>'a','ç'=>'c','è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ð'=>'o','ñ'=>'n','ò'=>'o','ó'=>'o',
			'ô'=>'o','õ'=>'o','ö'=>'o','ø'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ý'=>'y','ý'=>'y','þ'=>'b','ÿ'=>'y','Ŕ'=>'R','ŕ'=>'r','?'=>'');
			return strtr($string, $table);
		}

	}
	class Agrupar{
		private $texto;
		private $vetor_retorno;
		private $tipo;
		private function Normaliza($string) {
			$table = array('Š'=>'S','š'=>'s','Đ'=>'Dj','đ'=>'dj','Ž'=>'Z','ž'=>'z','Č'=>'C','č'=>'c','Ć'=>'C','ć'=>'c','À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Å'=>'A','Æ'=>'A','Ç'=>'C','È'=>'E','É'=>'E',
			'Ê'=>'E','Ë'=>'E','Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I','Ñ'=>'N','Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O','Ø'=>'O','Ù'=>'U','Ú'=>'U','Û'=>'U','Ü'=>'U','Ý'=>'Y','Þ'=>'B','ß'=>'Ss',
			'à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a','æ'=>'a','ç'=>'c','è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ð'=>'o','ñ'=>'n','ò'=>'o','ó'=>'o',
			'ô'=>'o','õ'=>'o','ö'=>'o','ø'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ý'=>'y','ý'=>'y','þ'=>'b','ÿ'=>'y','Ŕ'=>'R','ŕ'=>'r','?'=>'');
			return strtr($string, $table);
		}
		function getCategoriaReceita($string){
				$clientes = array();
				//Pega os clientes do usuário
				$sql_clientes = mysql_query("SELECT DISTINCT apelido FROM dados_clientes WHERE id_login = ".id_user." ORDER BY apelido");
				while( $rs_clientes = mysql_fetch_array($sql_clientes) ){
					array_push($clientes, 
						strtoupper($this->Normaliza($rs_clientes['apelido']))
					);	
				}
				$outros = array();
				array_push($outros, 
					strtoupper($this->Normaliza("Outros"))
				);
				$impostos = array();
				array_push($impostos, 
					strtoupper($this->Normaliza("Impostos e encargos"))
				);
				$receitas_diversas = array();
				array_push($receitas_diversas, 
					strtoupper($this->Normaliza("Serviços prestados em geral"))
				);
				//Define a qual categoria pertence o item e retorna a string correspondente
				if( in_array( strtoupper( $this->Normaliza ( $string ) ) , $impostos ) )
					return "Deduções com Impostos, Devoluções e Descontos Incondicionais";
				if( in_array( strtoupper( $this->Normaliza ( $string ) ) , $receitas_diversas ) )
					return "Vendas de Serviços";
				if( in_array( strtoupper( $this->Normaliza ( $string ) ) , $clientes ) )
					return "Vendas de Serviços";
				if( in_array( strtoupper( $this->Normaliza ( $string ) ) , $outros ) )
					return "Outros";
		}
		function agruparReceitas($tipo,$string){
				if( $tipo == '' )
					return false;				
				$clientes = array();
				//Pega os clientes do usuário
				$sql_clientes = mysql_query("SELECT DISTINCT apelido FROM dados_clientes WHERE id_login = ".id_user." ORDER BY apelido");
				while( $rs_clientes = mysql_fetch_array($sql_clientes) ){
					array_push($clientes, 
						strtoupper($this->Normaliza($rs_clientes['apelido']))
					);	
				}
				$outros = array();
				array_push($outros, 
					strtoupper($this->Normaliza("Outros"))
				);
				$impostos = array();
				array_push($impostos, 
					strtoupper($this->Normaliza("Impostos e encargos"))
				);
				$receitas_diversas = array();
				array_push($receitas_diversas, 
					strtoupper($this->Normaliza("Serviços prestados em geral"))
				);
				//Define a qual categoria pertence o item e retorna a string correspondente
				if( strtoupper($this->Normaliza($tipo)) == strtoupper($this->Normaliza("Deduções com Impostos, Devoluções e Descontos Incondicionais")) && in_array( strtoupper( $this->Normaliza ( $string ) ) , $impostos ) )
					return true;
				if( strtoupper($this->Normaliza($tipo)) == strtoupper($this->Normaliza("Vendas de Serviços")) && in_array( strtoupper( $this->Normaliza ( $string ) ) , $receitas_diversas ) )
					return true;
				if( strtoupper($this->Normaliza($string)) == strtoupper($this->Normaliza("Receitas financeiras")) && in_array( strtoupper( $this->Normaliza ( $string ) ) , $mensalidades ) )
					return true;
				if( strtoupper($this->Normaliza($tipo)) == strtoupper($this->Normaliza("Vendas de Serviços")) && in_array( strtoupper( $this->Normaliza ( $string ) ) , $clientes ) )
					return true;
				if( strtoupper($this->Normaliza($tipo)) == strtoupper($this->Normaliza("Outros")) && in_array( strtoupper( $this->Normaliza ( $string ) ) , $outros ) )
					return true;
		}
		function getCategoriaReceitaFinanceira($string){
				$clientes = array();
				$receitas_diversas = array();
				array_push($receitas_diversas, 
					strtoupper($this->Normaliza("Rendimentos de aplicação")),
					strtoupper($this->Normaliza("Outros"))
				);
				if( in_array( strtoupper( $this->Normaliza ( $string ) ) , $receitas_diversas ) )
					return "Receitas financeiras";
		}
		function agruparReceitasFinanceiras($tipo,$string){
				if( $tipo == '' )
					return false;				

				$receitas_diversas = array();
				array_push($receitas_diversas, 
					strtoupper($this->Normaliza("Rendimentos de aplicação")),
					strtoupper($this->Normaliza("Outros"))
					// strtoupper($this->Normaliza("Aumento de Capital")),
					// strtoupper($this->Normaliza("Serviços prestados em geral"))
				);

				//Define a qual categoria pertence o item e retorna a string correspondente
				if( strtoupper($this->Normaliza($tipo)) == strtoupper($this->Normaliza("Receitas financeiras")) && in_array( strtoupper( $this->Normaliza ( $string ) ) , $receitas_diversas ) )
					return true;		
		}
		function getCategoriaDespesaFinanceira($string){
			//Define os itens de despesa bancaria
			$despesas_bancarias = array();
			array_push($despesas_bancarias, 
				strtoupper($this->Normaliza("Despesas bancárias"))
			);
			//Define a qual categoria pertence o item e retorna a string correspondente
			if( in_array( strtoupper( $this->Normaliza ( $string ) ) , $despesas_bancarias ) )
				return "Despesas Financeiras";
			return false;
		}
		function agruparDespesasFinanceira($tipo,$string){
			// Define os itens de despesa bancaria
			$despesas_bancarias = array();
			array_push($despesas_bancarias, 
				strtoupper($this->Normaliza("Despesas bancárias"))
			);

			//Define a qual categoria pertence o item e retorna a string correspondente
			if( strtoupper($this->Normaliza($tipo)) == strtoupper($this->Normaliza("Despesas Financeiras")) && in_array( strtoupper( $this->Normaliza ( $string ) ) , $despesas_bancarias ) )
				return true;
			return false;
		}
		function agruparDespesas($tipo,$string){
			//Define os itens de despesa bancaria
			// $despesas_bancarias = array();
			// array_push($despesas_bancarias, 
			// 	strtoupper($this->Normaliza("Despesas bancárias"))
			// );
			//Define os itens de despesas administrativas
			$despesas_administrativas = array();
			array_push($despesas_administrativas, 
				strtoupper($this->Normaliza("Água")),
				strtoupper($this->Normaliza("Aluguel")),
				strtoupper($this->Normaliza("Combustível")),
				strtoupper($this->Normaliza("Correios")),
				strtoupper($this->Normaliza("Energia elétrica")),
				strtoupper($this->Normaliza("Condomínio")),
				strtoupper($this->Normaliza("Equipamentos")),
				strtoupper($this->Normaliza("Empréstimo (amortização)")),
				strtoupper($this->Normaliza("Internet")),
				strtoupper($this->Normaliza("Empréstimos")),
				strtoupper($this->Normaliza("Licença ou aluguel de softwares")),
				strtoupper($this->Normaliza("Limpeza")),
				strtoupper($this->Normaliza("Manutenção de equipamentos")),
				strtoupper($this->Normaliza("Manutenção veículo")),
				strtoupper($this->Normaliza("Material de escritório")),
				strtoupper($this->Normaliza("Telefone")),
				strtoupper($this->Normaliza("Viagens e deslocamentos")),
				strtoupper($this->Normaliza("Seguros")),
				strtoupper($this->Normaliza("Transportadora / Motoboy")),
				strtoupper($this->Normaliza("Cursos e treinamentos")),
				strtoupper($this->Normaliza("Contador")),
				strtoupper($this->Normaliza("Ajuste caixa"))
				
			);		
			//Define os itens de Despesas com Pessoal
			$despesa_pessoal = array();
			array_push($despesa_pessoal, 
				strtoupper($this->Normaliza("Estagiários")),
				strtoupper($this->Normaliza("Pgto. a autônomos e fornecedores")),
				strtoupper($this->Normaliza("Pgto. de salários")),
				strtoupper($this->Normaliza("Pró-Labore")),
				strtoupper($this->Normaliza("Vale-Transporte")),
				strtoupper($this->Normaliza("Vale-Refeição")),
				strtoupper($this->Normaliza("Reembolso de despesas"))
			);
			//Define outros itens
			$outros = array();
			array_push($outros, 
				strtoupper($this->Normaliza("Outros")),
				strtoupper($this->Normaliza("Comissões"))
			);
			//Define os itens de despesa de vendas
			$despesas_com_vendas = array();
			array_push($despesas_com_vendas, 
				strtoupper($this->Normaliza("Marketing e publicidade"))
				// strtoupper($this->Normaliza("Devolução de adiantamento"))
			);

			//Define a qual categoria pertence o item e retorna a string correspondente
			if( strtoupper($this->Normaliza($tipo)) == strtoupper($this->Normaliza("Despesas Administrativas")) && in_array( strtoupper( $this->Normaliza ( $string ) ) , $despesas_administrativas ) )
				return true;
			else if( strtoupper($this->Normaliza($tipo)) == strtoupper($this->Normaliza("Despesas de Vendas")) && in_array( strtoupper( $this->Normaliza ( $string ) ) , $despesas_com_vendas ) )
				return true;
			else if( strtoupper($this->Normaliza($tipo)) == strtoupper($this->Normaliza("Outros")) && in_array( strtoupper( $this->Normaliza ( $string ) ) , $outros ) )
				return true;
			else if( strtoupper($this->Normaliza($tipo)) == strtoupper($this->Normaliza("Despesas com Pessoal")) && in_array( strtoupper( $this->Normaliza ( $string ) ) , $despesa_pessoal ) )
				return true;			
			// else if( strtoupper($this->Normaliza($tipo)) == strtoupper($this->Normaliza("Despesa de Encargos")) && in_array( strtoupper( $this->Normaliza ( $string ) ) , $despesa_encargos ) )
				// return true;
			return false;
		}
		function getCategoriaDespesa($string){
			//Define os itens de despesa bancaria
			// $despesas_bancarias = array();
			// array_push($despesas_bancarias, 
			// 	strtoupper($this->Normaliza("Despesas bancárias"))
			// );
			//Define os itens de despesas administrativas
			$despesas_administrativas = array();
			array_push($despesas_administrativas, 
				strtoupper($this->Normaliza("Água")),
				strtoupper($this->Normaliza("Aluguel")),
				strtoupper($this->Normaliza("Combustível")),
				strtoupper($this->Normaliza("Correios")),
				strtoupper($this->Normaliza("Energia elétrica")),
				strtoupper($this->Normaliza("Condomínio")),
				strtoupper($this->Normaliza("Equipamentos")),
				strtoupper($this->Normaliza("Empréstimo (amortização)")),
				strtoupper($this->Normaliza("Internet")),
				strtoupper($this->Normaliza("Empréstimos")),
				strtoupper($this->Normaliza("Licença ou aluguel de softwares")),
				strtoupper($this->Normaliza("Limpeza")),
				strtoupper($this->Normaliza("Manutenção de equipamentos")),
				strtoupper($this->Normaliza("Manutenção veículo")),
				strtoupper($this->Normaliza("Material de escritório")),
				strtoupper($this->Normaliza("Telefone")),
				strtoupper($this->Normaliza("Vale-Transporte")),
				strtoupper($this->Normaliza("Vale-Refeição")),
				strtoupper($this->Normaliza("Viagens e deslocamentos")),
				strtoupper($this->Normaliza("Seguros")),
				strtoupper($this->Normaliza("Transportadora / Motoboy")),
				strtoupper($this->Normaliza("Cursos e treinamentos")),
				strtoupper($this->Normaliza("Contador")),
				strtoupper($this->Normaliza("Ajuste caixa"))
				
			);		
			//Define os itens de Despesas com Pessoal
			$despesa_pessoal = array();
			array_push($despesa_pessoal, 
				strtoupper($this->Normaliza("Estagiários")),
				strtoupper($this->Normaliza("Pgto. a autônomos e fornecedores")),
				strtoupper($this->Normaliza("Pgto. de salários")),
				strtoupper($this->Normaliza("Pró-Labore")),
				strtoupper($this->Normaliza("Reembolso de despesas"))
			);
			// //Define os itens de despesa encargos
			// $despesa_encargos = array();
			// array_push($despesa_encargos, 
			// 	strtoupper($this->Normaliza("Impostos e encargos"))
			// );
			//Define outros itens
			$outros = array();
			array_push($outros, 
				strtoupper($this->Normaliza("Outros")),
				strtoupper($this->Normaliza("Comissões"))
			);
			//Define os itens de despesa de vendas
			$despesas_com_vendas = array();
			array_push($despesas_com_vendas, 
				strtoupper($this->Normaliza("Marketing e publicidade"))
				// strtoupper($this->Normaliza("Devolução de adiantamento"))
			);

			//Define a qual categoria pertence o item e retorna a string correspondente
			// if( in_array( strtoupper( $this->Normaliza ( $string ) ) , $despesas_bancarias ) )
			// 	return "Despesas Financeiras";
			if( in_array( strtoupper( $this->Normaliza ( $string ) ) , $despesas_administrativas ) )
				return "Despesas Administrativas";
			else if( in_array( strtoupper( $this->Normaliza ( $string ) ) , $despesas_com_vendas ) )
				return "Despesas de Vendas";
			else if( in_array( strtoupper( $this->Normaliza ( $string ) ) , $outros ) )
				return "Outros";
			else if( in_array( strtoupper( $this->Normaliza ( $string ) ) , $despesa_pessoal ) )
				return "Despesas com Pessoal";			
			// else if( in_array( strtoupper( $this->Normaliza ( $string ) ) , $despesa_encargos ) )
				// return "Despesa de Encargos";
		}
		function getCategoria($string){
			if( $this->tipo == 'receita' ){
				return $this->getCategoriaReceita($string);
			}
			if( $this->tipo == 'despesa' ){
				return $this->getCategoriaDespesa($string);
			}
			if( $this->tipo == 'receita-financeira' ){
				return $this->getCategoriaReceitaFinanceira($string);
			}
			if( $this->tipo == 'despesa-financeira' ){
				return $this->getCategoriaDespesaFinanceira($string);
			}
		}
		function getArrayTexto($outros = false){
			if( $outros){
				$this->vetor_retorno['Outros'] = 0;

			}
			return $this->vetor_retorno;
		}
		//Parametro 1: array na forma "key___value", Parametro 2: true or false para agrupar por categoria, Parametro 3: Define se é do tipo receita ou despesa
		function agruparCategoria($string=array(),$agrupar=false,$tipo=''){
			$this->tipo = $tipo;
			$this->texto = array();
			$this->vetor_retorno = array();
			$aux_vet = array();
			foreach ($string as $value) {
				$aux = explode("___", $value);
				//Agrupa itens em uma categoria definida
				if( $agrupar )
					$aux[0] = $this->getCategoria($aux[0]);
				if( isset( $aux_vet[ strtoupper( $aux[0] ) ] ) )
					$aux_vet[ strtoupper( $aux[0] ) ] = $aux_vet[ strtoupper( $aux[0] ) ] + floatval($aux[1]);			
				else	
					$aux_vet[ strtoupper( $aux[0] ) ] = floatval($aux[1]);				
			}
			foreach ($aux_vet as $key => $value) {
				$this->vetor_retorno[ucfirst($key = strtolower($key))] = $value;
			}
		}	
	}
	class Dados{
		private $array;
		private $total;
		function __construct(){
			$this->array = array();
			$this->total = 0;
		}
		function setArray($string){
			$this->array = $string;
		}
		function getArray(){
			return $this->array;
		}
		function setTotal($string){
			$this->total = $string;
		}
		function getTotal(){
			return $this->total - $array['Outros'];
		}
	}
	class GerarItem{
		function gerarReceita($ano){
			$receita = new Dre();//Cria um objeto Dre que gera as despesas e receitas
			$receita->gerarReceita($ano);//Gera as Receitas de um determinado ano, os parâmetros que definem quais dados entram estão na classe Receitas, oa parâmetros para agrupamento estão na classe Agrupar
			$array = $receita->getTextoArray();
			$receita_categorias = new Agrupar();//Cria um objeto responsavel por agrupar itens relacionados
			$receita_categorias->agruparCategoria($receita->getTextoArray(),true,'receita');//Agrupa os itens da receita por categoria baseado no array criado nesta classe para este item
			$array = $receita_categorias->getArrayTexto();//Retorna o array gerado no objeto	
			
			$dados = new Dados();
			$dados->setTotal($receita->getReceita());
			$dados->setArray($array);

			return $dados;
		}
		function gerarReceitaFinanceira($ano){
			$receita = new Dre();//Cria um objeto Dre que gera as despesas e receitas
			$receita->gerarReceitaFinanceira($ano);//Gera as Receitas de um determinado ano, os parâmetros que definem quais dados entram estão na classe Receitas, oa parâmetros para agrupamento estão na classe Agrupar
			$array = $receita->getTextoArray();
			$receita_categorias = new Agrupar();//Cria um objeto responsavel por agrupar itens relacionados
			$receita_categorias->agruparCategoria($receita->getTextoArray(),true,'receita-financeira');//Agrupa os itens da receita por categoria baseado no array criado nesta classe para este item
			$array = $receita_categorias->getArrayTexto();//Retorna o array gerado no objeto	
			
			$dados = new Dados();
			$dados->setTotal($receita->getReceita());
			$dados->setArray($array);

			return $dados;
		}
		function gerarDespesas($ano){
			$receita = new Dre();//Cria um objeto Dre que gera as despesas e receitas
			$receita->gerarDespesas($ano);//Gera as Receitas de um determinado ano, os parâmetros que definem quais dados entram estão na classe Receitas, oa parâmetros para agrupamento estão na classe Agrupar
			$array = $receita->getTextoArray();
			$receita_categorias = new Agrupar();//Cria um objeto responsavel por agrupar itens relacionados
			$receita_categorias->agruparCategoria($receita->getTextoArray(),true,'despesa');//Agrupa os itens da receita por categoria baseado no array criado nesta classe para este item
			$array = $receita_categorias->getArrayTexto();//Retorna o array gerado no objeto	
			
			$dados = new Dados();
			$dados->setTotal($receita->getDespesa());
			$dados->setArray($array);

			return $dados;
		}
		function gerarDespesasFinanceiras($ano){
			$receita = new Dre();//Cria um objeto Dre que gera as despesas e receitas
			$receita->gerarDespesasFinanceiras($ano);//Gera as Receitas de um determinado ano, os parâmetros que definem quais dados entram estão na classe Receitas, oa parâmetros para agrupamento estão na classe Agrupar
			$array = $receita->getTextoArray();
			$receita_categorias = new Agrupar();//Cria um objeto responsavel por agrupar itens relacionados
			$receita_categorias->agruparCategoria($receita->getTextoArray(),true,'despesa-financeira');//Agrupa os itens da receita por categoria baseado no array criado nesta classe para este item
			$array = $receita_categorias->getArrayTexto();//Retorna o array gerado no objeto	
			
			$dados = new Dados();
			$dados->setTotal($receita->getDespesa());
			$dados->setArray($array);

			return $dados;
		}
	}
	class TabelaDRE{
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

		function gerarTabelaCSV($ano){
			return 'Descrição;R$

VENDAS DE MERCADORIAS, PRODUTOS E SERVIÇOS;'.$this->getVendas_de_mercadorias_produtos_e_servicosNumber().'
Vendas de Mercadorias;'.$this->getVendas_de_mercadoriasNumber().'
Vendas de Produtos;'.$this->getVendas_de_produtosNumber().'
Vendas de Serviços;'.$this->getVendas_de_servicosNumber().'
(-) Deduções com Impostos, Devoluções e Descontos Incondicionais;'.$this->getDeducoes_com_impostos_devolucoes_e_descontosNumber().'

(=) RECEITA LÍQUIDA;'.$this->getReceita_liquidaNumber().'

(-) CUSTO DAS VENDAS;'.$this->getCusto_das_vendasNumber().'
Custo das Mercadorias Vendidas;'.$this->getCusto_das_mercadorias_vendidasNumber().'
Custo dos Produtos Vendidos;'.$this->getCusto_dos_produtos_vendidosNumber().'
Custo dos Serviços Prestados;'.$this->getCusto_dos_servicos_prestadosNumber().'

(=) LUCRO BRUTO;'.$this->getLucro_brutoNumber().'

(-) DESPESAS OPERACIONAIS;'.$this->getDespesas_operacionaisNumber().'
Despesas com Pessoal;'.$this->getDespesas_com_pessoalNumber().'
Despesas Administrativas;'.$this->getDespesas_administrativasNumber().'
Despesas de Vendas;'.$this->getDespesas_de_vendasNumber().'
Despesas Tributárias;'.$this->getDespesas_tributariasNumber().'
(-) Depreciação e Amortização Acumuladas;'.$this->getDepreciacao_e_amortizacaoNumber().'
Perdas Diversas;'.$this->getPerdas_diversasNumber().'

(+/-) RESULTADO FINANCEIRO;'.$this->getResultado_financeiroNumber().'
Receitas Financeiras;'.$this->getReceitas_financeirasNumber().'
(-) Despesas Financeiras;'.$this->getDespesas_financeirasNumber().'

(+) OUTRAS RECEITAS;'.$this->getOutras_receitasNumber().'

(-) OUTRAS DESPESAS;'.$this->getOutras_despesasNumber().'

(=) RESULTADO LÍQUIDO DO EXERCÍCIO;'.$this->getResultado_liquido_do_exercicioNumber().'


';
		}

		function gerarTabela(){
			echo 	'<table border="0" cellspacing="2" cellpadding="4" style="width:840px;font-size: 11px;margin-top: -2px;float:left;margin-bottom:30px;">
							<tbody>
								<tr>
					       			<th style="width:650px;font-size: 12px;">Descrição</th>
					       			<th style="width:180px;font-size: 12px;">R$</th>
					    		</tr>	
					    		<tr>
									<td class="td_calendario agrupado titulo_tabela">VENDAS DE MERCADORIAS, PRODUTOS E SERVIÇOS </td>
									<td class="td_calendario agrupado titulo_tabela">'.$this->getVendas_de_mercadorias_produtos_e_servicosNumber().'</td>
								</tr>
								<tr>
									<td class="td_calendario">Vendas de Mercadorias</td>
									<td class="td_calendario">'.$this->getVendas_de_mercadoriasNumber().'</td>
								</tr>
								<tr>
									<td class="td_calendario">Vendas de Produtos</td>
									<td class="td_calendario">'.$this->getVendas_de_produtosNumber().'</td>
								</tr>
								<tr>
									<td class="td_calendario">Vendas de Serviços</td>
									<td class="td_calendario">'.$this->getVendas_de_servicosNumber().'</td>
								</tr>
								<tr>
									<td class="td_calendario">(-) Deduções com Impostos, Devoluções e Descontos Incondicionais</td>
									<td class="td_calendario">'.$this->getDeducoes_com_impostos_devolucoes_e_descontosNumber().'</td>
								</tr>
							</tbody>
						</table>
						<table border="0" cellspacing="2" cellpadding="4" style="width:840px;font-size: 11px;margin-top: -2px;float:left;margin-bottom:30px;">
							<tbody>
								<tr>
									<td style="width:650px;" class="td_calendario agrupado titulo_tabela">= RECEITA LÍQUIDA</td>
									<td style="width:180px;" class="td_calendario agrupado titulo_tabela">'.$this->getReceita_liquidaNumber().'</td>
								</tr>
							</tbody>
						</table>
						<table border="0" cellspacing="2" cellpadding="4" style="width:840px;font-size: 11px;margin-top: -2px;float:left;margin-bottom:30px;">
							<tbody>
								<tr>
									<td style="width:650px;" class="td_calendario agrupado titulo_tabela">(-) CUSTO DAS VENDAS</td>
									<td style="width:180px;" class="td_calendario agrupado titulo_tabela">'.$this->getCusto_das_vendasNumber().'</td>
								</tr>
								<tr>
									<td class="td_calendario">Custo das Mercadorias Vendidas</td>
									<td class="td_calendario">'.$this->getCusto_das_mercadorias_vendidasNumber().'</td>
								</tr>
								<tr>
									<td class="td_calendario">Custo dos Produtos Vendidos</td>
									<td class="td_calendario">'.$this->getCusto_dos_produtos_vendidosNumber().'</td>
								</tr>
								<tr>
									<td class="td_calendario">Custo dos Serviços Prestados</td>
									<td class="td_calendario">'.$this->getCusto_dos_servicos_prestadosNumber().'</td>
								</tr>
							</tbody>
						</table>
						<table border="0" cellspacing="2" cellpadding="4" style="width:840px;font-size: 11px;margin-top: -2px;float:left;margin-bottom:30px;">
							<tbody>
								<tr>
									<td style="width:650px;" class="td_calendario agrupado titulo_tabela">= LUCRO BRUTO</td>
									<td style="width:180px;" class="td_calendario agrupado titulo_tabela">'.$this->getLucro_brutoNumber().'</td>
								</tr>
							</tbody>
						</table>
						<table border="0" cellspacing="2" cellpadding="4" style="width:840px;font-size: 11px;margin-top: -2px;float:left;margin-bottom:30px;">
							<tbody>
								
								<tr>
	 								<td style="width:650px;" class="td_calendario agrupado titulo_tabela">(-) DESPESAS OPERACIONAIS</td>
	 								<td style="width:180px;" class="td_calendario agrupado titulo_tabela">'.$this->getDespesas_operacionaisNumber().'</td>
								</tr>
								<tr>
									<td class="td_calendario">Despesas com Pessoal</td>
									<td class="td_calendario">'.$this->getDespesas_com_pessoalNumber().'</td>
								</tr>
								<tr>
									<td class="td_calendario">Despesas Administrativas</td>
									<td class="td_calendario">'.$this->getDespesas_administrativasNumber().'</td>
								</tr>
								<tr>
									<td class="td_calendario">Despesas de Vendas</td>
									<td class="td_calendario">'.$this->getDespesas_de_vendasNumber().'</td>
								</tr>
								<tr>
									<td class="td_calendario">Despesas Tributárias</td>
									<td class="td_calendario">'.$this->getDespesas_tributariasNumber().'</td>
								</tr>
								<tr>
									<td class="td_calendario">(-) Depreciação e Amortização Acumuladas</td>
									<td class="td_calendario">'.$this->getDepreciacao_e_amortizacaoNumber().'</td>
								</tr>
								<tr>
									<td class="td_calendario">Perdas Diversas</td>
									<td class="td_calendario">'.$this->getPerdas_diversasNumber().'</td>
								</tr>
							</tbody>
						</table>
						<table border="0" cellspacing="2" cellpadding="4" style="width:840px;font-size: 11px;margin-top: -2px;float:left;margin-bottom:30px;">
							<tbody>
								<tr>
									<td style="width:650px;" class="td_calendario agrupado titulo_tabela">(+/-) RESULTADO FINANCEIRO</td>
									<td style="width:180px;" class="td_calendario agrupado titulo_tabela">'.$this->getResultado_financeiroNumber().'</td>
								</tr>
								<tr>
									<td class="td_calendario">Receitas Financeiras</td>
									<td class="td_calendario">'.$this->getReceitas_financeirasNumber().'</td>
								</tr>
								<tr>
									<td class="td_calendario">(-) Despesas Financeiras</td>
									<td class="td_calendario">'.$this->getDespesas_financeirasNumber().'</td>
								</tr>
							</tbody>
						</table>
						<table border="0" cellspacing="2" cellpadding="4" style="width:840px;font-size: 11px;margin-top: -2px;float:left;margin-bottom:30px;">
							<tbody>
								<tr>
									<td style="width:650px;" class="td_calendario agrupado titulo_tabela">(+) OUTRAS RECEITAS</td>
									<td style="width:180px;" class="td_calendario agrupado titulo_tabela">'.$this->getOutras_receitasNumber().'</td>
								</tr>
								<tr>
									<td class="td_calendario agrupado titulo_tabela">(-) OUTRAS DESPESAS</td>
									<td class="td_calendario agrupado titulo_tabela">'.$this->getOutras_despesasNumber().'</td>
								</tr>
							</tbody>
						</table>
						<table border="0" cellspacing="2" cellpadding="4" style="width:840px;font-size: 11px;margin-top: -2px;float:left;margin-bottom:30px;">
							<tbody>
								<tr>
									<td style="width:650px;" class="td_calendario agrupado titulo_tabela">= RESULTADO LÍQUIDO DO EXERCÍCIO</td>
									<td style="width:180px;" class="td_calendario agrupado titulo_tabela total_dre">'.$this->getResultado_liquido_do_exercicioNumber().'</td>
								</tr>
								
							</tbody>
						</table>


					';
		}

		function __construct(){
			$this->vendas_de_mercadorias_produtos_e_servicos = 0;
			$this->vendas_de_mercadorias = 0;
			$this->vendas_de_produtos = 0;
			$this->vendas_de_servicos = 0;
			$this->deducoes_com_impostos_devolucoes_e_descontos = 0;
			$this->incondicionais = 0;
			$this->receita_liquida = 0;
			$this->custo_das_vendas = 0;
			$this->custo_das_mercadorias_vendidas = 0;
			$this->custo_dos_produtos_vendidos = 0;
			$this->custo_dos_servicos_prestados = 0;
			$this->lucro_bruto = 0;
			$this->despesas_operacionais = 0;
			$this->despesas_com_pessoal = 0;
			$this->despesas_administrativas = 0;
			$this->despesas_de_vendas = 0;
			$this->despesas_tributarias = 0;
			$this->depreciacao_e_amortizacao = 0;
			$this->perdas_diversas = 0;
			$this->resultado_financeiro = 0;
			$this->receitas_financeiras = 0;
			$this->despesas_financeiras = 0;
			$this->outras_receitas = 0;
			$this->outras_despesas = 0;
			$this->resultado_antes_das_despesas_com_tributos_sobre_o_lucro = 0;
			$this->despesa_com_imposto_de_renda_da_pessoa_juridica = 0;
			$this->despesa_com_contribuicao_social = 0;
			$this->resultado_liquido_do_exercicio = 0;
		}
		function setVendas_de_mercadorias_produtos_e_servicos($string){
			$this->vendas_de_mercadorias_produtos_e_servicos = $this->getVendas_de_mercadorias() + $this->getVendas_de_produtos() + $this->getVendas_de_servicos() + $this->getDeducoes_com_impostos_devolucoes_e_descontos(); 
		}
		function getVendas_de_mercadorias_produtos_e_servicos(){
			return $this->vendas_de_mercadorias_produtos_e_servicos;
		}
		function setVendas_de_mercadorias($string){
			$this->vendas_de_mercadorias = $string;
		}
		function getVendas_de_mercadorias(){
			return $this->vendas_de_mercadorias;
		}

		function setVendas_de_produtos($string){
			$this->vendas_de_produtos = $string;
		}
		function getVendas_de_produtos(){
			return $this->vendas_de_produtos;
		}

		function setVendas_de_servicos($string){
			$this->vendas_de_servicos = floatval($string['Vendas de serviços']);
		}
		function getVendas_de_servicos(){
			return $this->vendas_de_servicos;
		}

		function setDeducoes_com_impostos_devolucoes_e_descontos($string){
			$this->deducoes_com_impostos_devolucoes_e_descontos = floatval($string['Deduções com impostos, devoluções e descontos incondicionais']);
		}
		function getDeducoes_com_impostos_devolucoes_e_descontos(){
			return $this->deducoes_com_impostos_devolucoes_e_descontos;
		}

		function setIncondicionais($string){
			$this->incondicionais = $string;
		}
		function getIncondicionais(){
			return $this->incondicionais;
		}

		function setReceita_liquida($string){
			$this->receita_liquida = $string;
		}
		function getReceita_liquida(){
			return $this->receita_liquida = $this->getVendas_de_mercadorias_produtos_e_servicos();
		}

		function setCusto_das_vendas($string){
			$this->custo_das_vendas = $string;
		}
		function getCusto_das_vendas(){
			return $this->custo_das_vendas;
		}

		function setCusto_das_mercadorias_vendidas($string){
			$this->custo_das_mercadorias_vendidas = $string;
		}
		function getCusto_das_mercadorias_vendidas(){
			return $this->custo_das_mercadorias_vendidas;
		}

		function setCusto_dos_produtos_vendidos($string){
			$this->custo_dos_produtos_vendidos = $string;
		}
		function getCusto_dos_produtos_vendidos(){
			return $this->custo_dos_produtos_vendidos;
		}

		function setCusto_dos_servicos_prestados($string){
			$this->custo_dos_servicos_prestados = $string;
		}
		function getCusto_dos_servicos_prestados(){
			return $this->custo_dos_servicos_prestados;
		}

		function setLucro_bruto($string){
			$this->lucro_bruto = $string;
		}
		function getLucro_bruto(){
			$this->setLucro_bruto($this->getReceita_liquida()+$this->getCusto_das_vendas());
			return $this->lucro_bruto;
		}

		function setDespesas_operacionais($string){
			$this->despesas_operacionais = $this->getDespesas_com_pessoal() + $this->getDespesas_administrativas() + $this->getDespesas_de_vendas() + $this->getDespesas_tributarias() + $this->getDepreciacao_e_amortizacao() + $this->getPerdas_diversas();
		}
		function getDespesas_operacionais(){
			return $this->despesas_operacionais;
		}

		function setDespesas_com_pessoal($string){
			$this->despesas_com_pessoal = $string['Despesas com pessoal'];
		}
		function getDespesas_com_pessoal(){
			return $this->despesas_com_pessoal;
		}

		function setDespesas_administrativas($string){
			$this->despesas_administrativas = $string['Despesas administrativas'];
		}
		function getDespesas_administrativas(){
			return $this->despesas_administrativas;
		}

		function setDespesas_de_vendas($string){
			$this->despesas_de_vendas = $string['Despesas de vendas'];
		}
		function getDespesas_de_vendas(){
			return $this->despesas_de_vendas;
		}

		function setDespesas_tributarias($string){
			$this->despesas_tributarias = $string;
		}
		function getDespesas_tributarias(){
			return $this->despesas_tributarias;
		}	

		function setDepreciacao_e_amortizacao($string){
			$this->depreciacao_e_amortizacao = $string;
		}
		function getDepreciacao_e_amortizacao($id=10055,$ano=2016){

			$datas = new Datas();
			$id = $_SESSION['id_empresaSecao'];
			if( isset($_GET['ano']) )
				$ano = $_GET['ano'];
			else
				$ano = date("Y");

			$tabela_depreciacao = array();
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

			$consulta = mysql_query("SELECT * FROM imobilizados WHERE id_user = '".$id."' AND YEAR(data) <= '".$ano."' ");
			$total = 0;
			while( $objeto=mysql_fetch_array($consulta) ){
				if( floatval($ano) - floatval( $datas->getAno($objeto['data']) ) <= $vida_util[$objeto['item']] ){
					// $vida = floatval($ano) - floatval($objeto['data']);
					// $vida = $this->calcularAnosDepreciacao($objeto['data']);
					// $meses_vida = $this->calcularMesesDepreciacao($objeto['data']);
					// $valor_total_item = ( floatval($objeto['quantidade']) * floatval($objeto['valor']) );
					// $total_depreciacao = 0;
					// for ($i=1; $i <= $vida; $i++) { 
					// 	$parcial_depreciacao = ( $valor_total_item * floatval($tabela_depreciacao[$objeto['item']]) );
					// 	$total_depreciacao = $total_depreciacao + $parcial_depreciacao;
					// 	$valor_total_item = $valor_total_item - $parcial_depreciacao;						
					// }
					// $total_depreciacao = $total_depreciacao + ( $meses_vida / 12 ) * ( $valor_total_item * floatval($tabela_depreciacao[$objeto['item']]) );
					$meses_depreciacao = $this->getMesesDepreciacao($objeto['data']);
					$depreciacao = floatval($objeto['valor']) * floatval($tabela_depreciacao[$objeto['item']]);
					$depreciacao = $depreciacao / 12;
					$total_depreciacao = $depreciacao * $meses_depreciacao;
					// echo $objeto['valor'];
					// echo '<br>';
					// echo $meses_depreciacao;
					// echo '<br>';
					// echo $depreciacao;
					if( $total_depreciacao <= 0 )
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

			$consulta = mysql_query("SELECT * FROM intangiveis WHERE id_user = '".$id."' AND YEAR(data) <= '".$ano."' ");
			while( $objeto=mysql_fetch_array($consulta) ){
				if( floatval($ano) - floatval( $datas->getAno($objeto['data']) ) <= $vida_util[$objeto['item']] ){
					// $vida = floatval($ano) - floatval($objeto['data']);
					// $vida = $this->calcularAnosDepreciacao($objeto['data']);
					// $meses_vida = $this->calcularMesesDepreciacao($objeto['data']);
					// $valor_total_item = ( floatval($objeto['quantidade']) * floatval($objeto['valor']) );
					// $total_depreciacao = 0;
					// for ($i=1; $i <= $vida; $i++) { 
					// 	$parcial_depreciacao = ( $valor_total_item * floatval($tabela_depreciacao[$objeto['item']]) );
					// 	$total_depreciacao = $total_depreciacao + $parcial_depreciacao;
					// 	$valor_total_item = $valor_total_item - $parcial_depreciacao;						
					// }
					// $total_depreciacao = $total_depreciacao + ( $meses_vida / 12 ) * ( $valor_total_item * floatval($tabela_depreciacao[$objeto['item']]) );
					$meses_depreciacao = $this->getMesesDepreciacao($objeto['data']);
					$depreciacao = floatval($objeto['valor']) * floatval($tabela_depreciacao[$objeto['item']]);
					$depreciacao = $depreciacao / 12;
					$total_depreciacao = $depreciacao * $meses_depreciacao;
					// echo $objeto['valor'];
					// echo '<br>';
					// echo $meses_depreciacao;
					// echo '<br>';
					// echo $depreciacao;
					if( $total_depreciacao <= 0 )
						$total_depreciacao = 0;
					$total = $total + $total_depreciacao;
				}
			}
			// $this->setA_n_c_depreciacao($total*-1);
			return floatval($total*-1);

			// return $this->depreciacao_e_amortizacao;
		}

		function setPerdas_diversas($string){
			$this->perdas_diversas = $string;
		}
		function getPerdas_diversas(){
			return $this->perdas_diversas;
		}

		function setResultado_financeiro($string){
			$this->resultado_financeiro = $string;
		}
		function getResultado_financeiro(){
			$this->setResultado_financeiro( $this->getReceitas_financeiras() + $this->getDespesas_financeiras() );
			return $this->resultado_financeiro;
		}

		function setReceitas_financeiras($string){
			$this->receitas_financeiras = $string['Receitas financeiras'];
		}
		function getReceitas_financeiras(){
			return $this->receitas_financeiras;
		}

		function setDespesas_financeiras($string){
			$this->despesas_financeiras = $string['Despesas financeiras'];
		}
		function getDespesas_financeiras(){
			return $this->despesas_financeiras;
		}

		function setOutras_receitas($string){
			$this->outras_receitas = $string['Outros'];
		}
		function getOutras_receitas(){
			return $this->outras_receitas;
		}

		function setOutras_despesas($string){
			$this->outras_despesas = $string['Outros'];
		}
		function getOutras_despesas(){
			return $this->outras_despesas;
		}

		function setResultado_antes_das_despesas_com_tributos_sobre_o_lucro($string){
			$this->resultado_antes_das_despesas_com_tributos_sobre_o_lucro = $string;
		}
		function getResultado_antes_das_despesas_com_tributos_sobre_o_lucro(){
			return $this->resultado_antes_das_despesas_com_tributos_sobre_o_lucro;
		}

		function setDespesa_com_imposto_de_renda_da_pessoa_juridica($string){
			$this->despesa_com_imposto_de_renda_da_pessoa_juridica = $string;
		}
		function getDespesa_com_imposto_de_renda_da_pessoa_juridica(){
			return $this->despesa_com_imposto_de_renda_da_pessoa_juridica;
		}

		function setDespesa_com_contribuicao_social($string){
			$this->despesa_com_contribuicao_social = $string;
		}
		function getDespesa_com_contribuicao_social(){
			return $this->despesa_com_contribuicao_social;
		}

		function setResultado_liquido_do_exercicio($string){
			$this->resultado_liquido_do_exercicio = $string;
		}
		function getResultado_liquido_do_exercicio(){
			$this->setResultado_liquido_do_exercicio($this->getLucro_bruto() + $this->getDespesas_operacionais() + $this->getResultado_financeiro() + $this->getOutras_receitas() + $this->getOutras_despesas() );
			return $this->resultado_liquido_do_exercicio;
		}
		function getVendas_de_mercadorias_produtos_e_servicosNumber(){
			return number_format($this->getVendas_de_mercadorias_produtos_e_servicos(),2,',','.');
		}
		function getVendas_de_mercadoriasNumber(){
			return number_format($this->getVendas_de_mercadorias(),2,',','.');
		}
		function getVendas_de_produtosNumber(){
			return number_format($this->getVendas_de_produtos(),2,',','.');
		}
		function getVendas_de_servicosNumber(){
			return number_format($this->getVendas_de_servicos(),2,',','.');
		}
		function getDeducoes_com_impostos_devolucoes_e_descontosNumber(){
			return number_format($this->getDeducoes_com_impostos_devolucoes_e_descontos(),2,',','.');
		}
		function getIncondicionaisNumber(){
			return number_format($this->getIncondicionais(),2,',','.');
		}
		function getReceita_liquidaNumber(){
			return number_format($this->getReceita_liquida(),2,',','.');
		}
		function getCusto_das_vendasNumber(){
			return number_format($this->getCusto_das_vendas(),2,',','.');
		}
		function getCusto_das_mercadorias_vendidasNumber(){
			return number_format($this->getCusto_das_mercadorias_vendidas(),2,',','.');
		}
		function getCusto_dos_produtos_vendidosNumber(){
			return number_format($this->getCusto_dos_produtos_vendidos(),2,',','.');
		}
		function getCusto_dos_servicos_prestadosNumber(){
			return number_format($this->getCusto_dos_servicos_prestados(),2,',','.');
		}
		function getLucro_brutoNumber(){
			return number_format($this->getLucro_bruto(),2,',','.');
		}
		function getDespesas_operacionaisNumber(){
			return number_format($this->getDespesas_operacionais(),2,',','.');
		}
		function getDespesas_com_pessoalNumber(){
			return number_format($this->getDespesas_com_pessoal(),2,',','.');
		}
		function getDespesas_administrativasNumber(){
			return number_format($this->getDespesas_administrativas(),2,',','.');
		}
		function getDespesas_de_vendasNumber(){
			return number_format($this->getDespesas_de_vendas(),2,',','.');
		}
		function getDespesas_tributariasNumber(){
			return number_format($this->getDespesas_tributarias(),2,',','.');
		}
		function getDepreciacao_e_amortizacaoNumber(){
			return number_format($this->getDepreciacao_e_amortizacao(),2,',','.');
		}
		function getPerdas_diversasNumber(){
			return number_format($this->getPerdas_diversas(),2,',','.');
		}
		function getResultado_financeiroNumber(){
			return number_format($this->getResultado_financeiro(),2,',','.');
		}
		function getReceitas_financeirasNumber(){
			return number_format($this->getReceitas_financeiras(),2,',','.');
		}
		function getDespesas_financeirasNumber(){
			return number_format($this->getDespesas_financeiras(),2,',','.');
		}
		function getOutras_receitasNumber(){
			return number_format($this->getOutras_receitas(),2,',','.');
		}
		function getOutras_despesasNumber(){
			return number_format($this->getOutras_despesas(),2,',','.');
		}
		function getResultado_antes_das_despesas_com_tributos_sobre_o_lucroNumber(){
			return number_format($this->getResultado_antes_das_despesas_com_tributos_sobre_o_lucro(),2,',','.');
		}
		function getDespesa_com_imposto_de_renda_da_pessoa_juridicaNumber(){
			return number_format($this->getDespesa_com_imposto_de_renda_da_pessoa_juridica(),2,',','.');
		}
		function getDespesa_com_contribuicao_socialNumber(){
			return number_format($this->getDespesa_com_contribuicao_social(),2,',','.');
		}
		function getResultado_liquido_do_exercicioNumber(){
			return number_format($this->getResultado_liquido_do_exercicio(),2,',','.');
		}

	}
	class Carta{

		private $nome_empresa;
		private $natureza_juridica;
		private $cidade;
		private $estado;
		private $endereco;
		private $objetivo_social;
		private $contingencia;

		function getDados(){
			$consulta = mysql_query("SELECT * FROM dados_da_empresa WHERE id = '".$_SESSION['id_empresaSecao']."' ");
			$objeto=mysql_fetch_array($consulta);

			$this->nome_empresa = $objeto['nome_fantasia'];
			$this->natureza_juridica = $objeto['inscrita_como'];
			$this->cidade = $objeto['cidade'];
			$this->estado = $objeto['estado'];
			$this->endereco = $objeto['endereco'];
			$this->contingencia = "contingencia";

			$consulta = mysql_query("SELECT * FROM dados_da_empresa_codigos WHERE id = '".$_SESSION['id_empresaSecao']."' ORDER BY tipo ASC");
			while( $objeto=mysql_fetch_array($consulta) ){
				$consulta2 = mysql_query("SELECT * FROM cnae WHERE cnae = '".$objeto['cnae']."' ");
				$objeto2=mysql_fetch_array($consulta2);
				$this->objetivo_social .= $objeto2['denominacao'].', ';
			}
		}
		function gerarTextAreaContingencia($texto=""){

			echo '<div class="hideImpressao" style="display:none">
						<div class="tituloVermelho hideImpressao">CONTINGÊNCIAS PASSIVAS</div>
						<p style="width: 840px">Contingência é uma condição ou situação cujo o resultado final, favorável ou desfavorável, depende de eventos futuros incertos.<br>Por exemplo: a empresa  tem uma reclamatória trabalhista em  andamento, e estima que haverá uma perda de aproximadamente de R$ ....</p>
						<textarea id="contingenciaTexto" style="width: 840px;height: 200px;margin-top:10px;">'.$texto.'</textarea>
					</div>
				';

		}
		function cadastroContador(){
			$consulta = mysql_query("SELECT * FROM dados_contador_balanco WHERE id_user = '".$_SESSION['id_empresaSecao']."' ");
			$objeto=mysql_fetch_array($consulta);

			$consulta_empresa = mysql_query("SELECT * FROM dados_da_empresa WHERE id = '".$_SESSION['id_empresaSecao']."' ");
			$objeto_empresa=mysql_fetch_array($consulta_empresa);

			$consulta_contador_padrao = mysql_query("SELECT * FROM dados_contador_balanco WHERE id_user = '".$objeto_empresa['estado']."' ");
			$objeto_contador_padrao=mysql_fetch_array($consulta_contador_padrao);


			
				$nome = $objeto['nome'];
				$nosso_contador_nome = $objeto_contador_padrao['nome'];
			
				$crc = $objeto['crc'];
				$nosso_contador_crc = $objeto_contador_padrao['crc'];
			
				$endereco = $objeto['endereco'];
				$nosso_contador_endereco = $objeto_contador_padrao['endereco'];
			
				$cidade = $objeto['cidade'];
				$nosso_contador_cidade = $objeto_contador_padrao['cidade'];
			
				$estado = $objeto['estado'];
				$nosso_contador_estado = $objeto_contador_padrao['estado'];
			
				$cep = $objeto['cep'];
				$nosso_contador_cep = $objeto_contador_padrao['cep'];

				$tipo = $objeto['tipo'];
				$nosso_contador_tipo = $objeto_contador_padrao['tipo'];
				
				$id = $objeto['id'];

			echo '<br><br>';
			// echo '<div class="hideImpressao">Frase que o vitor vai escrever</div><br>';
			echo '<div class="hideImpressao tituloVermelho" id="inicioTabelaBalanco">Dados do Contador</div><br>';
			if( $_GET['contador_proprio'] == 'true' ){
				echo '<input type="checkbox" class="hideImpressao contador_proprio" value="" checked="checked"> Daesejo indicar um contabilista de minha confiança para validar meu balanço.';
				echo '<script>$(document).ready(function(e) {$("#dados_contador").css("display","block");});</script>';
			}
			else{
				echo '<input type="checkbox" class="hideImpressao contador_proprio" value=""> <span class="hideImpressao">Desejo indicar um contabilista de minha confiança para validar meu balanço.</span>';
			}
			
			echo '<br><div class="hideImpressao" style="width:400px;">';

			$tipo_crc = '';
			$tipo_cpf = '';
			$estilo_crc = '';
			$estilo_cpf = '';
			if( $objeto['tipo'] == 'crc' ){
				$tipo_crc = 'checked';
				$estilo_crc = 'style="display:table-row"';
				$estilo_cpf = 'style="display:none"';
			}
			if( $objeto['tipo'] == 'cpf' ){
				$tipo_cpf = 'checked';
				$estilo_cpf = 'style="display:table-row"';
				$estilo_crc = 'style="display:none"';
			}

			echo '<table id="dados_contador" style="display:none">
			    	<tbody>
			    		<tr>
			    			<td>
			    				<input type="hidden" class="nosso_contador_nome" value="'.$nosso_contador_nome.'">
			    				<input type="hidden" class="nosso_contador_crc" value="'.$nosso_contador_crc.'">
			    				<input type="hidden" class="nosso_contador_endereco" value="'.$nosso_contador_endereco.'">
			    				<input type="hidden" class="nosso_contador_cidade" value="'.$nosso_contador_cidade.'">
			    				<input type="hidden" class="nosso_contador_estado" value="'.$nosso_contador_estado.'">
			    				<input type="hidden" class="nosso_contador_cep" value="'.$nosso_contador_cep.'">
			    				<input type="hidden" class="nosso_contador_tipo" value="'.$nosso_contador_tipo.'">
			    			</td>
			    		</tr>
			    		<tr>
			    		<td align="right">
			    			Tipo:
			    		</td>
		    			<td class="formTabela">
			            	<input type="radio" name="rdbTipo" id="contador_PJ" value="J" '.$tipo_crc.'> <label for="contador_PJ">Pessoa Jurídica</label>&nbsp;&nbsp;
			              	<input type="radio" name="rdbTipo" id="contador_PF" value="F" '.$tipo_cpf.'> <label for="boleto_PF">Pessoa Física</label>
			            </td>
			    		</tr>
			    		<tr>
			    			<td align="right">
			    				Nome:
			    			</td>
			    			<td width="300">
			    				<input type="text" class="nome" value="'.$nome.'" placeholder="" style="width:300px;">
			    			</td>
			    		</tr>
			    		<tr id="contador_cpf" '.$estilo_cpf.'>
					    	<td align="right">
					    		CPF:
					    	</td>
					    	<td width="300">
					    		<input type="text" class="cpf_contador" id="boleto_cpf" value="'.$crc.'" placeholder="" style="width:100px;">
					    	</td>
					    </tr>
					    <tr id="contador_crc" '.$estilo_crc.'>
					    	<td align="right">
					    		Crc:
					    	</td>
					    	<td width="300">
					    		<input type="text" class="crc" value="'.$crc.'" placeholder="" style="width:100px;">
					    	</td>
					    </tr>
					    
					    <tr>
					    	<td align="right">
					    		Endereco:
					    	</td>
					    	<td width="300">
					    		<input type="text" class="endereco" value="'.$endereco.'" placeholder="" style="width:300px;">
					    	</td>
					    </tr>
					    
					    <tr>
					    	<td align="right">
					    		Estado:
					    	</td>
					    	<td width="300">
					    	<select class="estado" id="estado_contador" placeholder="" >
						    	<option value="'.$estado.'">'.$estado.'</option>';

						  		$arrEstados = array();
								$sql = "SELECT * FROM estados ORDER BY sigla";
								$result = mysql_query($sql) or die(mysql_error());
								while($estados = mysql_fetch_array($result)){
									array_push($arrEstados,array('id'=>$estados['id'],'sigla'=>$estados['sigla']));
								}	    	
					            foreach($arrEstados as $dadosEstado){
									echo "<option class=\"escolher_estado\" id-uf=\"".$dadosEstado['id']."\" value=\"".$dadosEstado['sigla']."\" >".$dadosEstado['sigla']."</option>";
					            }
					echo '
						    </select>
					    		
					    	</td>
					    </tr>
					    <tr>
					    	<td align="right">
					    		Cidade:
					    	</td>
					    	<td width="300">
					    		<select class="cidade" id="contador_cidade" >
					    			<option value="'.$cidade.'">'.$cidade.'	</option>}
					    		</select>
					    	</td>
					    </tr>
					    
					    <tr>
					    	<td align="right">
					    		Cep:
					    	</td>
					    	<td width="300">
					    		<input type="text" class="cep" value="'.$cep.'" placeholder="" style="width:80px;">
					    	</td>
					    </tr>
			    	</tbody>
			    	<input type="hidden" class="id_item" value="'.$id.'" placeholder="" style="width:100%;">
			    	<input type="hidden" class="tipo_item" value="'.$tipo.'" placeholder="" style="width:100%;">
			    </table>
			    <br>
			    
			    

			    ';

			echo '</div>';
		}
		function getMes($string){

			if( $string == '01' )
				return 'Janeiro';
			if( $string == '02' )
				return 'Fevereiro';
			if( $string == '03' )
				return 'Março';
			if( $string == '04' )
				return 'Abril';
			if( $string == '05' )
				return 'Maio';
			if( $string == '06' )
				return 'Junho';
			if( $string == '07' )
				return 'Julho';
			if( $string == '08' )
				return 'Agosto';
			if( $string == '09' )
				return 'Setembro';
			if( $string == '10' )
				return 'Outubro';
			if( $string == '11' )
				return 'Novembro';
			if( $string == '12' )
				return 'Dezembro';

		}
		function apendice1($id,$ano){

			$consulta = mysql_query("SELECT * FROM dados_contador_balanco WHERE id_user = '".$_SESSION['id_empresaSecao']."' ");
			$objeto=mysql_fetch_array($consulta);

			$consulta_login = mysql_query("SELECT * FROM login WHERE id = '".$id."' ");
			$objeto_login=mysql_fetch_array($consulta_login);

			$consulta_empresa = mysql_query("SELECT * FROM dados_da_empresa WHERE id = '".$id."' ");
			$objeto_empresa=mysql_fetch_array($consulta_empresa);

			$nome_empresa_contabilidade = $objeto['nome'];
			$CRC_empresa_contabilidade = $objeto['crc'];
			$endereco = $objeto['endereco'];
			$cidade = $objeto['cidade'];
			$estado = $objeto['estado'];
			$cep = $objeto['cep'];
					
			if( $objeto['id'] == '' ){

				$consulta_contador_padrao = mysql_query("SELECT * FROM dados_contador_balanco WHERE id_user = '".$objeto_empresa['estado']."' ");
				$objeto_contador_padrao=mysql_fetch_array($consulta_contador_padrao);

				$nome_empresa_contabilidade = $objeto_contador_padrao['nome'];
				$CRC_empresa_contabilidade = $objeto_contador_padrao['crc'];
				$endereco = $objeto_contador_padrao['endereco'];
				$cidade = $objeto_contador_padrao['cidade'];
				$estado = $objeto_contador_padrao['estado'];
				$cep = $objeto_contador_padrao['cep'];
			}

			$denominacao_social = $objeto_empresa['nome_fantasia'];
			$cnpj = $objeto_empresa['cnpj'];
			$nome_empresa = $objeto_empresa['razao_social'];
			$nome = $objeto_login['assinante'];
			$ano_base = $ano;
			$sistema_em_uso = "Contador Amigo";

			$consulta_dados_responsavel = mysql_query("SELECT * FROM dados_do_responsavel WHERE id = '".$_SESSION['id_empresaSecao']."' AND responsavel = '1'	");
			$objeto_dados_responsavel=mysql_fetch_array($consulta_dados_responsavel);

			$nome_responsavel = $objeto_dados_responsavel['nome'];
			$cpf_responsavel = $objeto_dados_responsavel['cpf'];

			echo '
		<div style="position: relative;">
		<div style="">
			<div class="tituloVermelho"><center>Carta de Responsabilidades da Administração</center></div>
			<br>
			<br>
			<br>
			<br>
			<br>
			<div style="float:right">'.$objeto_empresa['cidade'].', '.date("d").' de '.$this->getMes(date("m")).' de '.$ano_base.'</div>
			
			<span class="contador_nome_empresa_contabilidade"></span><br>
			
			<span class="tipo_contador1">CRC</span>: <span class="contador_CRC_empresa_contabilidade"></span><br>
			Endereço: <span class="contador_endereco"></span><br>
			Cidade e Estado: <span class="contador_cidade"></span><br>
			CEP: <span class="contador_cep"></span>

			<br><br><br><br>
			Prezados Senhores:
			<br><br>
			Declaramos para os devidos fins, como administrador e responsável legal da empresa
			'.$denominacao_social.', CNPJ '.$cnpj.', que as informações fornecidas à V.Sas.
			para preparação das demonstrações contábeis, obrigações acessórias, apuração de
			impostos e arquivos eletrônicos exigidos pela fiscalização federal, estadual, municipal,
			trabalhista e previdenciária são fidedignos e compreendem a realidade do que diz
			respeito a:
			<br>
			
			<ol style="list-style: decimal">		
				<li>o valor apresentado na conta caixa, perfaz a realidade que tínhamos no
				encerramento do exercício de '.$ano_base.';</li>
				
				<li>informamos desconhecer e não possuir nenhuma operação que não tenha sido
				registrada em nossa contabilidade, pois, todas as nossas operações são
				geradas com documentação suporte adequada;</li>
				
				<li>asseguramos que os controles internos adotados pela nossa entidade são de
				responsabilidade da administração e adequados ao tipo de atividade e volume
				de transações;</li>
				
				<li>não realizamos nenhum tipo de operação que possa ser considerada ilegal,
				frente à legislação vigente ;</li>
				
				<li>todos os documentos que geramos e recebemos de nossos fornecedores estão
				revestidos de total idoneidade;</li>
				
				<li>todos os ativos que informamos para V.Sas., são de nossa propriedade;
				<li>os estoques registrados em conta própria, foram por nós avaliados, contados e
				levantados fisicamente, e perfazem a realidade do exercício encerrado em
				'.$ano_base.';</li>
				
				<li>as informações registradas no sistema de gestão e controle interno,
				denominado '.$sistema_em_uso.' são controladas e validadas com a
				documentação suporte adequada, sendo de nossa inteira responsabilidade todo
				conteúdo do banco de dados e arquivos eletrônicos gerados.</li>
			</ol>
				
			
			
			Além disso, não temos conhecimento:
			<br>
			
				<ol style="list-style: decimal">
				<li> de que não tenhamos cumprido todas as leis, normas e regulamentos a que
				à empresa está sujeita. Também não temos conhecimento de que houve,
				durante o exercício, operações ou transações que possam ser reconhecidas
				como irregulares ou ilegais e/ou que não tenham sido realizadas no
				interesse da empresa;</li>
				<li>de que diretores ou empregados em cargos de responsabilidade ou
				confiança tenham participado ou participem da administração ou tenham
				interesses em sociedades com as quais a empresa manteve transações;
				</li>
				<li>de quaisquer fatos ocorridos que possam afetar as demonstrações contábeis
				ou que as afetam até a data desta carta ou, ainda, que possam afetar a
				continuidade das operações da empresa;
				</li>
				<li>de efeitos relevantes nas demonstrações contábeis, decorrentes das
				seguintes situações:
				</li>
				<ol style="list-style:lower-alpha">				
					<li>ações ou reclamações materiais contra a empresa;
					</li>
					<li>acordos ou operações estranhos aos negócios normais ou quaisquer
					outros acordos;
					</li>
					<li>inadimplências contratuais que possam resultar em prejuízos para a
					empresa;
					</li>
					<li>existência de contingências (ativas ou passivas) além daquelas que
					estejam descritas, reconhecidas ou provisionadas, por serem
					consideradas virtualmente certas (contingências ativas) ou prováveis
					(contingências passivas).</li>
				</ol>
			</ol>
			Também confirmamos que não houve:
			<ol style="list-style: decimal">		
				<li> fraude envolvendo administração ou empregados em cargos de
				responsabilidades ou confiança;
				</li>
				<li> fraude envolvendo terceiros que poderiam ter efeito material nas
				demonstrações contábeis;
				</li>
				<li> violação ou possíveis violações de leis, normas ou regulamentos cujos
				efeitos deveriam ser considerados para divulgação nas demonstrações
				contábeis ou mesmo dar origem ao registro de provisão para
				contingências passivas.
				</li>
			</ol>
		</ol>
			Como também declaramos ciência quanto a:
			<br>
			<ol style="list-style: decimal">		
				<li>exigência da fiscalização eletrônica federal, estadual, municipal, trabalhista e
				previdenciária, relacionadas a obrigatoriedade de: SPED FISCAL; SPED
				CONTRIBUIÇÕES; SPED ECD; SPED NFE; MANAD; SINTEGRA; Certificação
				Digital;
				</li>
				<li>necessidade de auditoria eletrônica de dados, haja vista que os arquivos
				exigidos pela fiscalização eletrônica contem informações de diversas fontes e
				sistemas, tais como: contábil, fiscal, trabalhista, financeiro, administrativo,
				comercial, entre outros;
				</li>
				<li>toda e qualquer divergência encontrada pelo fisco nos arquivos eletrônicos são
				de nossa inteira responsabilidade.
				</li>
			</ol>
			<br>
			<br>
			Atenciosamente,<br><br><br><br><br>
			<br>
			<br>	
			<div style="width:350px">
				<center>
					<p>_______________________________________________________</p>
					'.$nome_responsavel.'<br>
					CPF: '.$cpf_responsavel.'<br>
					Responsável Legal
				</center>
			</div>			
	
			';
			echo '<div style="clear: both;height: 10px;"></div></div></div>';
		}
		function gerarCarta($id,$ano){
			$this->getDados();
			$string = '<br><br>
			
				<div class="apenasImpressao">
				<div class="tituloVermelho"><strong>Notas Explicativas</strong></div>
				<div style="font-size:14px"><strong>1. Contexto Operacional</strong></div>
				A '.$this->nome_empresa.' é uma '.$this->natureza_juridica.' com sede em '.$this->cidade.', '.$this->estado.' e '.$this->endereco.' e tem como principal por objeto '.$this->objetivo_social.'. Foi constituída em data conforme seu documento constitutivo.
				<br><br><br>
				<div style="font-size:14px"><strong>2. Declaração de conformidade e política contábil significativas</strong></div>
				A administração declara que as Demonstrações Contábeis da '.$this->nome_empresa.' do período compreendido entre 01 de janeiro de '.$ano.' e 31 de dezembro de '.$ano.', apresentam adequadamente a posição patrimonial e financeira, o desempenho e os fluxos de caixa da entidade, com observância aos Princípios de Contabilidade e foram elaboradas em conformidade com a ITG 1000, aprovada pela resolução CFC  1418/2012. As demonstrações contábeis, exceto informações de fluxo de caixa foram elaborados segundo o regime de competência e estão representadas em real, a moeda nacional brasileira.
				<br><br>
				<strong>2.1. Imobilizado – </strong>Os terrenos e imóveis estão demonstrados ao valor justo (custo atribuído) conforme opção prevista no Pronunciamento Técnico CPC 27, aprovado pelo CFC – Conselho Federal de Contabilidade pela Resolução 1.177/09. A avaliação pelo custo atribuído, bem como suas estimativas de vida útil dos imóveis foram determinadas com base em laudo técnico emitida por empresa especializada para a data base de 1º de janeiro de 201X. Os demais itens de ativo imobilizado são demonstrados ao custo de aquisição, mais todos os gastos incorridos para colocar o bem em condições de uso. As depreciações das edificações são calculadas com base na estimativa de vida útil dos bens determinados em virtude do custo atribuído. Os demtais itens são depreciados linearmente com base nas mesmas taxas estabelecidas conforme legislação brasileira.”
				<br><br>
				<div class="remove_contingencias">
				<strong>2.2 Contingências passivas -</strong> <span id="textoContingencia"></span>
				<br><br><br>
				</div>
				<div style="font-size:14px"><strong>3. Apresentação das demonstrações contábeis</strong></div>
				<strong>3.1. Demonstração do resultado do exercício -</strong> Demonstração contábil que apresenta todos os itens de receita e despesa reconhecidos no período, excluindo os itens de outros resultados abrangentes;
				<br><br>
				<strong>3.2. Balanço patrimonial -</strong> Demonstração que apresenta a relação de ativos, passivos e patrimônio líquido de uma entidade em data específica,entendendo que Ativos são recursos controlados pela entidade como resultado de eventos passados do qual se esperam benefícios econômicos futuros para a entidade, passivo,como Obrigação presente da entidade, derivada de eventos já ocorridos,, cuja liquidação se espera resulte em saída de recursos capazes de gerar benefícios econômicos e patrimônio líquido como o valor residual dos ativos da entidade após a dedução de todos os seus passivos;
				<br><br>
				<strong>3.3. Demonstração de lucros ou prejuízos acumulados  -</strong> Demonstração contábil que apresenta as alterações em lucros ou prejuízos acumulados para um período.
				<br><br>
				<strong>3.4. Demonstração do resultado abrangente - </strong>Demonstração que começa com lucro ou prejuízo do período e a seguir mostra os itens de outros resultados abrangentes do período, que não foram demonstradas no Resultado do Exercício. 
				<br><br>
				<strong>3.5. Demonstração dos fluxos de caixa -</strong> Demonstração que oferece informações sobre as alterações em caixa e equivalentes de caixa da entidade por um período, mostrando alterações separadamente durante o período em atividades operacionais, de investimento e de financiamento.
			</div>
			';

			echo str_replace(", .", ".", $string);
		}

	}
	//Função que cria o DRE e exibe a tabela resultante
	function executeDRE($ano){

		$dre = new TabelaDRE();

		//Gera as receitas de clientes e de serviços gerais
		$receita = new GerarItem();
		$dados = $receita->gerarReceita($ano);
		$dre->setVendas_de_servicos($dados->getArray());
		$dre->setDeducoes_com_impostos_devolucoes_e_descontos($dados->getArray());
		$dre->setVendas_de_mercadorias_produtos_e_servicos();
		$dre->setOutras_receitas($dados->getArray());

		//Gera as despesas e define o valor de cada item da tabela final
		$despesa = new GerarItem();
		$dados = $despesa->gerarDespesas($ano);
		$dre->setdespesas_com_pessoal($dados->getArray());
		$dre->setDespesas_administrativas($dados->getArray());
		$dre->setDespesas_de_vendas($dados->getArray());
		$dre->setDespesas_tributarias(0);
		$dre->setDepreciacao_e_amortizacao(0);
		$dre->setPerdas_diversas(0);
		$dre->setDespesas_operacionais();
		$dre->setOutras_despesas($dados->getArray());

		//Gera as receitas financeiras
		$receita_financeira = new GerarItem();
		$dados = $receita_financeira->gerarReceitaFinanceira($ano);
		$dre->setReceitas_financeiras($dados->getArray());

		//Gera as despesas financeiras
		$despesa_financeira = new GerarItem();
		$dados = $despesa_financeira->gerarDespesasFinanceiras($ano);
		$dre->setDespesas_financeiras($dados->getArray());

		$dre->gerarTabela();
	}
	function executeDRECSV($ano){

		$dre = new TabelaDRE();

		//Gera as receitas de clientes e de serviços gerais
		$receita = new GerarItem();
		$dados = $receita->gerarReceita($ano);
		$dre->setVendas_de_servicos($dados->getArray());
		$dre->setDeducoes_com_impostos_devolucoes_e_descontos($dados->getArray());
		$dre->setVendas_de_mercadorias_produtos_e_servicos();
		$dre->setOutras_receitas($dados->getArray());

		//Gera as despesas e define o valor de cada item da tabela final
		$despesa = new GerarItem();
		$dados = $despesa->gerarDespesas($ano);
		$dre->setdespesas_com_pessoal($dados->getArray());
		$dre->setDespesas_administrativas($dados->getArray());
		$dre->setDespesas_de_vendas($dados->getArray());
		$dre->setDespesas_tributarias(0);
		$dre->setDepreciacao_e_amortizacao(0);
		$dre->setPerdas_diversas(0);
		$dre->setDespesas_operacionais();
		$dre->setOutras_despesas($dados->getArray());

		//Gera as receitas financeiras
		$receita_financeira = new GerarItem();
		$dados = $receita_financeira->gerarReceitaFinanceira($ano);
		$dre->setReceitas_financeiras($dados->getArray());

		//Gera as despesas financeiras
		$despesa_financeira = new GerarItem();
		$dados = $despesa_financeira->gerarDespesasFinanceiras($ano);
		$dre->setDespesas_financeiras($dados->getArray());

		return $dre->gerarTabelaCSV($ano);

	}
?>