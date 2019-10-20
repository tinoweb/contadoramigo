<?php include 'header_restrita.php' ?>
<?php include 'livros_caixa_fluxo_class.php' ?>
<?php include 'balanco-form.class.php'; ?>
<?php 
	
	if( isset($_GET['ano']) )
		$ano = $_GET['ano'];
	else
		$ano = 0;

	//Pega os dados do item do ano atual se existir,
	$consulta = mysql_query("SELECT * FROM balanco_patrimonial WHERE id_user = '".$_SESSION['id_empresaSecao']."' AND ano = '".$ano."' ");
	$dados = mysql_fetch_array($consulta);

	$item = new Balanco_form($dados,$ano);
?>



<script src="jquery.maskMoney.js" type="text/javascript"></script>
<style type="text/css" media="screen">
	input{
		text-align: right;
	}
	#dados_contador input{
		text-align: left;
	}
	.item_contador_padrao{
		display: none;
	}
	.form table{
		/*width: 100%;*/
		margin-left: -3px;
	}
	textarea{
		width: 100%;
		height: 200px;
		/*margin-top:10px;*/
	}
	.form .item input{
		width: 130px;
	}
	.form .item{
		float: left;
		width: 100%;
		margin-bottom: 5px;
	}
	.form .item span{
		 vertical-align:middle;
	}
	.form .item .descricao{
	    float: left;
	    width: 250px;
	    text-align: right;
	    padding-right: 5px;
	    max-width: 30%;
	}
	.form .item .input i{
		margin-left: 5px;
	}
	.form .item .input table:last
	.form .item .input{
		max-width: 70%;
		float: left;
	}
	.form .item .input table{
		float: left;
	}
	.icone-download{
		cursor: pointer;
	}
	.excluirAnexoBalanco{
		cursor: pointer;
	}
	.anexo_aux,.anexo_aux_intangiveis,.anexo_aux_imobilizados{
		cursor: pointer;
	}
</style>	
<style type="text/css" media="screen">
	.agrupado{
		font-weight: bold;
		color: #000;
	}
	.apenasImpressao{
		display: none;
	}
	.icone-download{
		cursor: pointer;
	}
	.excluirAnexoBalanco{
		cursor: pointer;
	}
	.anexo_aux,.anexo_aux_intangiveis,.anexo_aux_imobilizados{
		cursor: pointer;
	}
</style>
<style type="text/css" media="print">
	@media print {
		.principal:first-child,.anos,.titulo,.tituloVermelho,.rodape,.btnPRINT,#btnCSV{
			display: none;
		}
		th {
		    background-color: #024a68;
		    color: #FFF;
		}
		.erro_merda{
			min-width: 251px !important;
		}
		.erro_merda_{
			min-width: 130px !important;
		}
	}
</style>
<style type="text/css" media="screen">		
</style>	
<style type="text/css" media="print">
	@media print {
		#tabela_final_passivo{

		}
		#tabela_patrimonio_liquido{

		}
		#tabela_circulantes_passivo{

		}
		#tabela_nao_curculante_passivo{

		}
		#tabela_circulant_ativo{

		}
		#tabela_nao_circulante_ativo{
			margin-top: 64px !important;
		}
		#tabela_total_ativo{
			margin-top: 160px!important;
		}

		.celula_invisivel{
			border: 0 !important;
		}
		.celula_invisivel td{
			border: 0 !important;	
			border-left: 2px solid #fff;
		}
		body{
			margin-left: 1.91cm;
			margin-right: : 1.91cm;
			margin-top: 2.54cm;
			margin-bottom: : 2.54cm;
			font-family: arial !important;
			font-size: 12px!important;
		}
		table{
			border-spacing: 0!important;
			border:solid!important;
			border-width:1px 0px 0px 1px!important;
			border-color:#000!important;
			/*margin-left: 0!important;*/
			font-size: 12px!important;

		}
		tr{
			height: 32px !important;
		}
		td{
			border-style:solid!important;
			border-width:0px 1px 1px 0px!important;
			border-color:#000!important;
		}
		th{
			border-style:solid!important;
			border-width:0px 1px 1px 0px!important;
			border-color:#000!important;
		}
		.anexo_tabela{
			display: none;
		}
		.tituloVermelho,p,.apenasImpressao,th{
			font-family: arial;
			color:#000000 !important;
			font-style: bold;
		}
		.principal:first-child,.anos,.titulo,.tituloVermelho,.rodape,.btnPRINT,#btnCSV1,#btnCSV2,.imagemDica,form,button,a{
			display: none;
		}
		.hideImpressao{
			display: none !important; 	
		}
		.apenasImpressao{
			display: block !important;
		}
	}
	.tipo_contador1,.tipo_contador2{
		text-transform: uppercase;
	}
	.pagina {page-break-after: always;text-align: justify;}
	.carta .tituloVermelho{
		display: block;
	}
	.carta{
		max-width: 900px;
	}
</style>
<div class="principal">
<div class="titulo">Escrituração</div>
<div class="tituloVermelho">Balanço patrimonial</div>
Bem-vindo ao <strong>Programa Gerador de Balanço do Contador Amigo</strong>. Informamos que esta ferramenta ainda está em fase de testes. Qualquer problema, por favor, envie uma mensagem ao <a href="suporte.php">help desk</a>!<br>
<br>
Embora empresas optantes pelo Simples não sejam obrigadas a declarar balanço, há uma grande vantagem em fazê-lo: você poderá retirar como distribuição todo o lucro real da empresa e não apenas o presumido pelo Governo. A vantagem é que a distribuição de lucros é isenta de impostos. <br>
<br>
Sem declarar balanço, você fica limitado a tirar como lucro apenas 32% (menos IR) do faturamento bruto, se for um prestador de serviço e 8% se for comerciante. O restante só pode ir para a pessoa física na forma de pró-labore e está sujeito a INSS e Imposto de Renda na Fonte.<br>
<br>

<div class="tituloAzul">Atualize o Livro Caixa</div>
Antes de começar, é necessário que o <a href="livros_caixa_movimentacao.php">livro caixa</a> do ano esteja completo, incluindo todas as movimentações na conta da empresa de 1º de janeiro a 31 de dezembro. É necessário também que os pagamentos de <a href="pro_labore.php">pró-labore</a>, <a href="pagamento_autonomos.php">autônomos</a>, <a href="estagiarios.php">estagiários</a>,  <a href="pagamento_pj.php">serviços tomados de pessoa jurídica</a> e as antecipações de <a href="distribuicao_de_lucros.php">distribuição de lucros</a> estejam todos atualizados.<br>
<br>
Preencha cuidadosamente os campos a seguir e anexe os respectivos comprovantes (eles são necessários para que o balanço possa ser auditado pelo contador). Em caso de dúvida, consulte nosso <a href="suporte.php">Help desk</a>. 
<br>
<br>



	<div class="form" id="visualizar">
		<br>
		<div class="ano">
			<span style="margin-right: 20px">Selecione o ano-calendário do balanço: </span>
			<?php $item->gerarInputano($ano); ?>	
			<input type="hidden" id="ano" value="<?php echo date("Y"); ?>">
			
			
				<!-- <input name="Alterar" type="button" id="definirAno" value="Prosseguir" style="margin-top:10px;margin-right:10px;">
				<script>
					$("#definirAno").click(function() {
						location.href = 'balanco-patrimonial-form.php?ano='+$("#ano").val()+"#visualizar";
					});
				</script>
			 -->
		</div>
		<?php 

		function verificaCategoriasBloqueadas( $ano ){

			$datas = new Datas();
/*			$consulta = mysql_query("SELECT * FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa WHERE ( data >= '".$ano."-01-01' AND data <= '".$ano."-12-31' ) AND ( categoria = 'Devolução de adiantamento' OR categoria = 'Reembolso de despesas' OR categoria = 'Outros' )  ORDER BY data ASC");	*/
$consulta = mysql_query("SELECT * FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa WHERE ( data >= '".$ano."-01-01' AND data <= '".$ano."-12-31' ) AND ( categoria = 'Reembolso de despesas')  ORDER BY data ASC");	
			if( mysql_num_rows($consulta) > 0 )
/*				echo '	<br><div class="tituloVermelho">Impossível Prosseguir</div>
						Detectamos lançamentos em seu livro-caixa marcados como 
						"reembolso de despesas" e/ou "outros". Estas categorias são 
						incompatíveis com o Balanço Patrimonial. 
						Antes de prosseguir, altere tais lançamentos para categorias mais específicas.';
*/						
				echo '	<br><div class="tituloVermelho">Impossível Prosseguir</div>
						Detectamos lançamentos em seu livro-caixa marcados como 
						"reembolso de despesas" e/ou "outros". Estas categorias são 
						incompatíveis com o Balanço Patrimonial. 
						Antes de prosseguir, altere tais lançamentos para categorias mais específicas.';						
						
						
						
						
			else
				return true;
			echo '<br><br>Os lançamentos que precisam ser revistos são:';
			echo '
				<table border="0" cellspacing="2" cellpadding="4" style="font-size: 12px;margin-top:10px;">
					<tbody>
						<tr>
							<th align="left" style="background:#a61d00">Data</th>
							<th align="left" style="background:#a61d00">Categoria</th>
							<th align="left" style="background:#a61d00">Descrição</th>
						</tr>
			';
			$entrada = '';
			$sql_clientes = "SELECT DISTINCT apelido FROM dados_clientes WHERE id_login = " . $_SESSION['id_empresaSecao'] . " ORDER BY apelido";
			$rs_clientes = mysql_query($sql_clientes);
			if(mysql_num_rows($rs_clientes) > 0){
				while($dados_clientes = mysql_fetch_array($rs_clientes)){
					$entrada .= '<option value="" >' . $dados_clientes['apelido'] . '</option>';
				}
			}
			$entrada .= '<option value="Serviços prestados em geral">Serviços prestados em geral</option>';
			$entrada .= '<option value="Empréstimos">Empréstimos</option>';
			$entrada .= '<option value="Rendimentos de aplicação">Rendimentos de aplicação</option>';
			$entrada .= '<option value="Repasse a terceiros">Repasse a terceiros</option>';
			$entrada .= '<option value="Saldo Anterior">Saldo Anterior</option>';
			$entrada .= '<option value="Variação Cambial Ativa">Variação Cambial Ativa</option>';
			$entrada .= '<option value="Vendas">Vendas</option>';

			$saida = '
				<option value="Água">Água</option>
				<option value="Ajuste caixa">Ajuste caixa</option>
				<option value="Aluguel">Aluguel</option>
				<option value="Combustível">Combustível</option>
				<option value="Comissões">Comissões</option>
				<option value="Condomínio">Condomínio</option>
				<option value="Contador">Contador</option>
				<option value="Correios">Correios</option>
				<option value="Cursos e treinamentos">Cursos e treinamentos</option>
				<option value="Despesas bancárias">Despesas bancárias</option>
				<option value="Distribuição de lucros">Distribuição de lucros</option>
				<option value="Empréstimo (amortização)">Empréstimo (amortização)</option>
				<option value="Energia elétrica">Energia elétrica</option>
				<option value="Equipamentos">Equipamentos</option>
				<option value="Estagiários">Estagiários</option>
				<option value="Estorno Serviços">Estorno Serviços</option>
				<option value="Impostos e encargos">Impostos e encargos</option>
				<option value="Internet">Internet</option>
				<option value="Licença ou aluguel de softwares">Licença ou aluguel de softwares</option>
				<option value="Limpeza">Limpeza</option>
				<option value="Manutenção de equipamentos">Manutenção de equipamentos</option>
				<option value="Manutenção veículo">Manutenção veículo</option>
				<option value="Marketing e publicidade">Marketing e publicidade</option>
				<option value="Material de escritório">Material de escritório</option>
				<option value="Pgto. a autônomos e fornecedores">Pgto. a autônomos e fornecedores</option>
				<option value="Pgto. de salários">Pgto. de salários</option>
				<option value="Pró-Labore">Pró-Labore</option>
				<option value="Repasse a terceiros">Repasse a terceiros</option>
				<option value="Saldo Anterior">Saldo Anterior</option>
				<option value="Segurança">Segurança</option>
				<option value="Seguros">Seguros</option>
				<option value="Telefone">Telefone</option>
				<option value="Transportadora / Motoboy">Transportadora / Motoboy</option>
				<option value="Vale-Transporte">Vale-Transporte</option>
				<option value="Vale-Refeição">Vale-Refeição</option>
				<option value="Variação Cambial Passiva">Variação Cambial Passiva</option>
				<option value="Viagens e deslocamentos">Viagens e deslocamentos</option>
			';
			while( $objeto_consulta = mysql_fetch_array($consulta) ){
				if( $objeto_consulta['saida'] > 0 ){
					echo '	<tr>
								<td class="td_calendario" style="height:17px">'.$datas->desconverterData($objeto_consulta['data']).'</td>
								<td class="td_calendario" style="height:17px">
									<select class="editar_item_livro_caixa" campo="categoria" tabela="user_'.$_SESSION['id_empresaSecao'].'_livro_caixa" id="'.$objeto_consulta['id'].'" style="width:100%">
										<option value="">'.$objeto_consulta['categoria'].'</option>
										'.$saida.'
									</select>
								</td>
								<td class="td_calendario" style="height:17px">'.$objeto_consulta['descricao'].'</td>
							</tr>';
				}
				else{
					echo '	<tr>
								<td class="td_calendario" style="height:17px">'.$datas->desconverterData($objeto_consulta['data']).'</td>
								<td class="td_calendario" style="height:17px">
									<select class="editar_item_livro_caixa" campo="categoria" tabela="user_'.$_SESSION['id_empresaSecao'].'_livro_caixa" id="'.$objeto_consulta['id'].'" style="width:100%">
										<option value="">'.$objeto_consulta['categoria'].'</option>
										'.$entrada.'
									</select>
								</td>
								<td class="td_calendario" style="height:17px">'.$objeto_consulta['descricao'].'</td>
							</tr>';
				}
			}
			echo '</tbody></table>';
			echo '<input name="Alterar" type="button" id="prosseguirBalanco" value="Prosseguir" style="margin-top:10px;margin-right:10px">';
			return false;

		}
		if( verificaCategoriasBloqueadas($ano) && $ano != 0 ){

	?>
		<div style="clear: both;height: 10px;"></div>
		<br>
		<div class="tituloVermelho">Informações do Ativo</div>
		<table  border="0" cellspacing="2" cellpadding="4" class="formTabela" style="text-align:left;width:100%">
			<tbody>
				<tr>
	       			<th align="center" width="690">Tipo de Ativo</th>
	       			<th align="center" width="170">Valor</th>
	       			<th align="center" width="100">Comprovante</th>
	    		</tr>
				<tr>
					<td class="td_calendario" align="left" valign="middle">
						<strong>Contas a Receber</strong> <br>Valores a receber referentes a serviços prestados até 31/12/<?php echo $ano+1; ?>. 
					</td>
					<td class="td_calendario" align="center" valign="middle" width="100">
						<?php echo $item->gerarInputa_c_contas_receber(); ?>
					</td>
					<td class="td_calendario" align="center" valign="middle" >
						<i class="fa fa-file-text-o icone-download abrirJanelaAnexos" aria-hidden="true" tipo="a_c_contas_receber" tag="#tag=0_a_n_c_investimentos" titulo="Investimentos" ></i>
					</td>
				</tr>
				<tr>
					<td class="td_calendario" align="left" valign="middle">
						<strong>Contas a Receber</strong><br>Valores a receber referentes a serviços prestados após 31/12/<?php echo $ano+1; ?>.
					</td>
					<td class="td_calendario" align="center" valign="middle" width="100">
						<?php echo $item->gerarInputa_n_c_contas_receber(); ?>
					</td>
					<td class="td_calendario" align="center" valign="middle" >
						<i class="fa fa-file-text-o icone-download abrirJanelaAnexos" aria-hidden="true" tipo="a_n_c_contas_receber" tag="#tag=0_a_n_c_investimentos" titulo="Investimentos" ></i>
					</td>
				</tr>
				<tr>
					<td class="td_calendario" align="left" valign="middle">
						<strong>Outros Créditos</strong><br>Impostos a recuperar, adiantamento a terceiros, funcionários, adiantamento de 13°, adiantamento de férias.
					</td>
					<td class="td_calendario" align="center" valign="middle" width="100">
						<?php echo $item->gerarInputa_c_outros_creditos(); ?>
					</td>
					<td class="td_calendario" align="center" valign="middle" >
						<i class="fa fa-file-text-o icone-download abrirJanelaAnexos" aria-hidden="true" tipo="a_c_outros_creditos" tag="#tag=0_a_n_c_investimentos" titulo="Investimentos" ></i>
					</td>
				</tr>
				<tr>
					<td class="td_calendario" align="left" valign="middle">
						<strong>Despesas já pagas do Exercício Seguinte</strong><br>Despesas pagas antecipadamente, mas que se referem ao próximo ano. Exemplos: Prêmios de seguro (somente a parte proporcional ao ano seguinte); alugueis, assinatura de periódicos e anuidades, juros sobre descontos de duplicatas.
					</td>
					<td class="td_calendario" align="center" valign="middle" width="100">
						<?php echo $item->gerarInputa_c_despesas_exercicio_seguinte(); ?>
					</td>
					<td class="td_calendario" align="center" valign="middle" >
						<i class="fa fa-file-text-o icone-download abrirJanelaAnexos" aria-hidden="true" tipo="a_c_despesas_exercicio_seguinte" tag="#tag=0_a_n_c_investimentos" titulo="Investimentos" ></i>						
					</td>
				</tr>
				<tr>
					<td class="td_calendario" align="left" valign="middle">
						<strong>Investimentos</strong> <br> 
						Valores em  aplicações  que serão resgatadas em um prazo inferior a 12 meses.
					</td>
					<td class="td_calendario" align="center" valign="middle" width="100">
						<?php echo $item->gerarInputa_n_c_investimentos(); ?>
					</td>
					<td class="td_calendario" align="center" valign="middle" >
						<i class="fa fa-file-text-o icone-download abrirJanelaAnexos" aria-hidden="true" tipo="a_n_c_investimentos" tag="#tag=0_a_n_c_investimentos" titulo="Investimentos" ></i>
					</td>
				</tr>
				<tr>
			</tbody>
		</table>
		<br><br>
		<div class="tituloVermelho">Informações do Passivo</div>
		<table  border="0" cellspacing="2" cellpadding="4" class="formTabela" style="text-align:left;width:100%">
			<tbody>
				<tr>
	       			<th align="center" width="690">Tipo de Passivo</th>
	       			<th align="center" width="170">Valor</th>
	       			<th align="center" width="100">Comprovante</th>
	    		</tr>
	    		<tr>
					<td class="td_calendario" align="left" valign="middle">
						<strong>Contas a Pagar</strong><br>Dívidas a serem quitadas até 31/12/<?php echo $ano+1; ?> (compra de materiais, pagamento de juros e aluguel).
					</td>
					<td class="td_calendario" align="center" valign="middle" width="100">
						<?php echo $item->gerarInputp_c_contas_pagar(); ?>
					</td>
					<td class="td_calendario" align="center" valign="middle" >
						<i class="fa fa-file-text-o icone-download abrirJanelaAnexos" aria-hidden="true" tipo="p_c_contas_pagar" tag="#tag=0_a_n_c_investimentos" titulo="Investimentos" ></i>
					</td>
				</tr>
				<tr>
					<td class="td_calendario" align="left" valign="middle">
						<strong>Contas a Pagar</strong><br>Dívidas a serem quitadas após 31/12/<?php echo $ano+1; ?> (compra de materiais, pagamento de juros e aluguel).
					</td>
					<td class="td_calendario" align="center" valign="middle" width="100">
						<?php echo $item->gerarInputp_n_c_contas_pagar(); ?>
					</td>
					<td class="td_calendario" align="center" valign="middle" >
						<i class="fa fa-file-text-o icone-download abrirJanelaAnexos" aria-hidden="true" tipo="p_n_c_contas_pagar" tag="#tag=0_a_n_c_investimentos" titulo="Investimentos" ></i>
					</td>
				</tr>
				<tr>					
					<td class="td_calendario" align="left" valign="middle">
						<strong>Fornecedores</strong><br>Dívidas com seus fornecedores a serem quitadas até o final do ano seguinte. Inclui energia elétrica, internet, seguros etc. Atenção informe apenas aquelas já efetivamente devidas. Por exemplo conta de luz atrasada ou com o boleto já emitido e não a conta de luz futura.
					</td>
					<td class="td_calendario" align="center" valign="middle" width="100">
						<?php echo $item->gerarInputp_c_fornecedores(); ?>
					</td>
					<td class="td_calendario" align="center" valign="middle" >
						<i class="fa fa-file-text-o icone-download abrirJanelaAnexos" aria-hidden="true" tipo="p_c_fornecedores" tag="#tag=0_a_n_c_investimentos" titulo="Investimentos" ></i>
					</td>
				</tr>
				<tr>
					<td class="td_calendario" align="left" valign="middle">
						<strong>Obrigações Sociais e Impostos a Recolher</strong><br>DAS e INSS em atraso ou já geradas.
					</td>
					<td class="td_calendario" align="center" valign="middle" width="100">
						<?php echo $item->gerarInputp_c_obrigacoes_sociais_impostos(); ?>
					</td>
					<td class="td_calendario" align="center" valign="middle" >
						<i class="fa fa-file-text-o icone-download abrirJanelaAnexos" aria-hidden="true" tipo="p_c_obrigacoes_sociais_impostos" tag="#tag=0_a_n_c_investimentos" titulo="Investimentos" ></i>
					</td>
				</tr>
				<tr>
					<td class="td_calendario" align="left" valign="middle">
						<strong>Lucros a Distribuir</strong><br>Parte do lucro apurado, ainda não distribuída, mas que pretende distribuir aos sócios/proprietário da empresa até o final do ano seguinte ao balanço.
					</td>
					<td class="td_calendario" align="center" valign="middle" width="100">
						<?php echo $item->gerarInputp_c_lucros_distribuir(); ?>
					</td>
					<td class="td_calendario" align="center" valign="middle" >
						<i class="fa fa-file-text-o icone-download abrirJanelaAnexos" aria-hidden="true" tipo="p_c_lucros_distribuir" tag="#tag=0_a_n_c_investimentos" titulo="Investimentos" ></i>
					</td>
				</tr>
				<tr>
					<td class="td_calendario" align="left" valign="middle">
						<strong>Provisões (cíveis, fiscais, trabalhistas, etc)</strong><br>Se você está fazendo uma reserva para pagar encargos futuros (cíveis, fiscais ou trabalhistas) previstos para vencer até o final do ano seguinte ao balanço, informe aqui o valor guardado.
					</td>
					<td class="td_calendario" align="center" valign="middle" width="100">
						<?php echo $item->gerarInputp_c_provisoes(); ?>
					</td>
					<td class="td_calendario" align="center" valign="middle" >
						<i class="fa fa-file-text-o icone-download abrirJanelaAnexos" aria-hidden="true" tipo="p_c_provisoes" tag="#tag=0_a_n_c_investimentos" titulo="Investimentos" ></i>
					</td>
				</tr>
				<tr>
					<td class="td_calendario" align="left" valign="middle">
						<strong>Capital Social</strong><br>O valor do capital social registrado em nome de sua empresa.
					</td>
					<td class="td_calendario" align="center" valign="middle" width="100">
						<?php echo $item->gerarInputp_l_capital_social(); ?>
					</td>
					<td class="td_calendario" align="center" valign="middle" >
						<i class="fa fa-file-text-o icone-download abrirJanelaAnexos" aria-hidden="true" tipo="p_l_capital_social" tag="#tag=0_a_n_c_investimentos" titulo="Investimentos" ></i>
					</td>
				</tr>
				<tr>
					<td class="td_calendario" align="left" valign="middle">
						<strong>Reservas de Capital</strong><br>São recursos que não foram obtidos pela empresa pela venda de seus produtos ou prestação de serviços. Por exemplo se você vende um equipamento ou qualquer outro bem de propriedade da empresa, o dinheiro recebido deve ser contabilizado no balanço como reserva de capital.
					</td>
					<td class="td_calendario" align="center" valign="middle" width="100">
						<?php echo $item->gerarInputp_l_reservas_capital(); ?>
					</td>
					<td class="td_calendario" align="center" valign="middle" >-</td>
				</tr>
				<tr>
					<td class="td_calendario" align="left" valign="middle">
						<strong>Reservas de Lucros</strong><br>Parte do lucro separada pela empresa como reserva, ou seja, que você não tem a intenção de mexer.
					</td>
					<td class="td_calendario" align="center" valign="middle" width="100">
						<?php echo $item->gerarInputp_l_reservas_lucro(); ?>
					</td>
					<td class="td_calendario" align="center" valign="middle" >-</td>
				</tr>
			</tbody>
		</table>
		<br><br>
		<div class="tituloVermelho">Bens em nome da empresa</div>
		<?php $item->inserirImobilizados(); ?>
		<br><br><br>
		<div class="tituloVermelho">Bens Intangíveis <?php echo $item->gerarImagemBallona_n_c_intangivel(); ?></div> 
		<?php $item->inserirIntangiveis(); ?>		
		<br><br>	
		<div class="tituloVermelho">Empréstimos e Financiamentos</div>
		<table id="itens_emprestimos" border="0" cellspacing="2" cellpadding="4" class="formTabela" style="width:966px">
		  	<tbody>
			  	<tr>
	       			<th align="center" >Descrição</th>
	       			<th align="center" >Data</th>
	       			<th align="center" >Valor</th>
	       			<th align="center" >Carência</th>
	       			<th align="center" >Saldo a pagar em 31/12</th>
	       			<th align="center" >Comprovante</th>
	    		</tr>
	    		<?php echo $item->gerarTabelaEmprestimos(); ?>
	    	</tbody>
		</table>
		<!-- <span id="aviso_emprestimos" style="display:none">Não existem empréstimos</span>
		<table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="width:100%;flot:left">
		  	<tbody>
		  		<tr>
		  			<td colspan="" align="left">
		  				<a id="inserir_outro_emprestimo" href="" title="">Adicionar </a>
		  				|
		  				<a id="remover_emprestimo" href="" title=""> Remover</a>
		  			</td>
		  		</tr>
			</tbody>
		</table> -->
	</div>	
	<br>
	<?php 
		echo $item->gerarBallona_c_caixa_equivalente_caixa("Saldo no banco no último dia do ano, mais valores em espécie e aplicações de curto prazo que serão resgatadas em um prazo inferior a 12 meses.");
		echo $item->gerarBallona_c_contas_receber("Valores a receber em um prazo inferior a 12 meses, referentes a serviços prestados.");
		echo $item->gerarBallona_c_estoques("Como sua empresa é uma prestadora de serviços, não há estoques a declarar.");
		echo $item->gerarBallona_c_outros_creditos("Impostos a recuperar, adiantamento a terceiros, funcionários, adiantamento de 13°, adiantamento de férias.");
		echo $item->gerarBallona_c_despesas_exercicio_seguinte("Despesas pagas antecipadamente, mas que se referem ao próximo ano. Exemplos: Prêmios de seguro (somente a parte proporcional ao ano seguinte); alugueis, assinatura de periódicos e anuidades, juros sobre descontos de duplicatas.");
		echo $item->gerarBallona_n_c_contas_receber("Valores a receber em um prazo superior a 12 meses referentes a serviços prestados.");
		echo $item->gerarBallona_n_c_investimentos("Bens e direitos não sejam destinados à manutenção das atividades normais da companhia. Exemplo: aplicações financeiras de caráter permanente, participações societárias em outras empresas e terrenos que não sejam de uso da empresa.");
		echo $item->gerarBallona_n_c_imobilizado("Bens e direitos destinado à manutenção das atividades da empresa. Exemplo: máquinas e equipamentos, móveis e utensílios, ferramentas, veículos e terrenos de uso da empresa.");
		echo $item->gerarBallona_n_c_intangivel("Bens que não podem ser tocados ou vistos. Possuem valor econômico mas são incorpóreos. Exemplo: patentes, marcas registradas, licenças, direitos autorais, websites e softwares.");
		echo $item->gerarBallonp_c_fornecedores("Dívidas com seus fornecedores a serem quitadas até o final do ano seguinte. Inclui energia elétrica, internet, seguros etc. Atenção informe apenas aquelas já efetivamente devidas. Por exemplo conta de luz atrasada ou com o boleto já emitido e não a conta de luz futura.");
		echo $item->gerarBallonp_c_emprestimos_bancarios("Informe o saldo devedor do empréstimo no último dia do ano do balanço.");
		echo $item->gerarBallonp_c_obrigacoes_sociais_impostos("DAS e INSS em atraso ou já geradas.");
		echo $item->gerarBallonp_c_contas_pagar("Dívidas assumidas com a compra de materiais, pagamento de juros e aluguel que vencerão até o final do ano seguinte ao balanço.");
		echo $item->gerarBallonp_c_lucros_distribuir("Parte do lucro apurado, ainda não distribuída, mas que pretende distribuir aos sócios/proprietário da empresa até o final do ano seguinte ao balanço.");
		echo $item->gerarBallonp_c_provisoes("Se você está fazendo uma reserva para pagar encargos futuros (cíveis, fiscais ou trabalhistas) previstos para vencer até o final do ano seguinte ao balanço, informe aqui o valor guardado.");
		echo $item->gerarBallonp_n_c_contas_pagar("Dívidas a serem quitadas APÓS o final do ano seguinte.");
		echo $item->gerarBallonp_n_c_financiamentos_bancarios("Saldo de empréstimo a ser liquidado somente após o final do ano seguinte.");
		echo $item->gerarBallonp_l_capital_social("O valor do capital social registrado em nome de sua empresa.");
		echo $item->gerarBallonp_l_reservas_capital("São recursos que não foram obtidos pela empresa pela venda de seus produtos ou prestação de serviços. Por exemplo se você vende um equipamento ou qualquer outro bem de propriedade da empresa, o dinheiro recebido deve ser contabilizado no balanço como reserva de capital.");
		echo $item->gerarBallonp_l_ajustes_avaliacao_patrimonial("Se o valor de mercado de algum de seus ativos ou passivos for diferente do resultado apresentado no balanço patrimonial, você deve informar aqui a diferença. Por exemplo, a empresa adquiriu um automóvel por R$ 30 mil. Sobre este valor, nosso sistema aplica a depreciação por tempo de uso, resultando num valor final de R$ 26 mil. É este o montante que será registrado na coluna de ativos. No entanto o preço de mercado do carro é 28 mil, então o ajuste de avaliação patrimonial deste bem será de R$ 2 mil.");
		echo $item->gerarBallonp_l_reservas_lucro("Parte do lucro separada pela empresa como reserva, ou seja, que você não tem a intenção de mexer.");
		echo $item->gerarBalloncontingencias("Contingência é uma condição ou situação cujo o resultado final, favorável ou desfavorável, depende de eventos futuros incertos.<br>Por exemplo: a empresa  tem uma reclamatória trabalhista em  andamento, e estima que haverá uma perda de aproximadamente de R$ ....");
	?>
	<br>
	 

	<?php /*?>	$carta = new Carta();

		$consulta = mysql_query("SELECT * FROM contingenciass WHERE id_user = '".$_SESSION['id_empresaSecao']."' AND ano = '".$ano."'  ");
		$objeto=mysql_fetch_array($consulta);

		echo '<div class="tituloVermelho hideImpressao">Contingências passivas'.$item->gerarImagemBalloncontingencias().'</div>';
		$carta->gerarTextAreaContingencia($objeto['texto']);
		echo '<br>';

		$carta->cadastroContador();<?php */?>
	
	<div class="tituloAzul">Assinatura do contador e registro do balanço</div>
	Para que o balanço tenha validade, deverá auditado por um contador e registrado na Junta Comercial, junto com o <a href="dre.php">DRE - Demonstrativo de Resultado do Exercício</a>. O contador Amigo dispõe de um contabilista perceiro que poderá fazer isso para você. O valor desde serviço varia de acordo com o número de lançamentos efetuados no livro-caixa durante o período. No seu caso, para o ano-calendário de 2017 o custo é de R$ 0,00.<br>
<br>
Para solicitar o serviço de nosso contabilista parceiro, envie uma mensagem ao <a href="suporte.php">Help Desk</a>. O Contador fará toda a validação. Possivelmente entrará em contato com você para esclarecer alguns pontos. Depois de concluído o trabalho, enviará o balanço assinado pelo Correio para que você possa registrá-lo.<br><br> 
	
	Mesmo que não pretenda submeter a um contador, você poderá gerar o balanço nesta ferramenta e usá-lo para sua própria análise. O balanço contém informações muito relevantes para a empresa que poderão ajudá-lo em seu planejamento para o futuro.<br><br><br>


	
<!--<table border="0" cellspacing="2" cellpadding="4" class="formTabela" style="width:350px;">
<tbody><tr>
    <th>Nº de lançamentos no livro caixa</th><th>Valor</th></tr>
<tr><td class="td_calendario">até 370</td><td class="td_calendario">R$ 150</td></tr>
<tr><td class="td_calendario">de 371 a 600</td><td class="td_calendario">R$ 250</td></tr>
<tr><td class="td_calendario">de 601 a 1000</td><td class="td_calendario">R$ 320</td></tr>
<tr><td class="td_calendario">mais de 1000</td><td class="td_calendario">a combinar</td></tr>
</tbody>
</table>
<br>-->


	<center>
		<div style="width:966px;">
			<center>
				<a id="imprimir_balanco" style="text-decoration:none" href="balanco-patrimonial.php?ano=<?php echo $ano; ?>" target="_blank">
					<button style="margin-right:20px;" >Imprimir Balanço</button>
				</a>
				<a href="exportar-csv-balanco.php?id=<?php echo $_SESSION['id_empresaSecao']; ?>&ano=<?php echo $ano; ?>" target="_blank">
					<button id="btnCSV2">Exportar para Excel</button>
				</a>
			</center>
		</div>
		<!-- <input type="button" id="gerarBalanco" value="Gerar Balanço">	 -->
	</center>
	<div id="upload_anexos" class="" style="width: 300px;left: 47.5%;margin-left: -223px;top: 195px;display: none;z-index: 999;position: fixed;border-radius: 0px;border: 1px solid rgb(204, 204, 204);padding: 20px;background: rgb(255, 255, 255);">
		<img class="fecharDiv" src="images/x.png" width="8" height="9" border="0" alt="Mídia sobre o Contador Amigo" title="" style="float: right;cursor:pointer" fechar="false">
		<div class="tituloVermelho" style="margin-bottom:20px;text-align: left;">Anexar comprovante do bem</div>
	    <form id="form_anexos" action="upload-anexos-balanco.php" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
			<table border="0" cellpadding="0" cellspacing="3" class="anexos_existentes" style="text-align:left">
				<tbody>
					
				</tbody>
			</table>
	    	<br>
		    <table border="0" cellpadding="0" cellspacing="3" class="formTabela">
			  	<tbody>
					<input type="file" name="anexos_doc[]" value="" multiple style="margin-right:10px;float:left">
				</tbody>
			</table>
			<br><br>
			<input type="hidden" name="tipo" class="tipo" value="">
			<input type="hidden" name="ano" class="ano" value="">
			<input type="hidden" name="id" class="id" value="">
			<input type="hidden" name="tag" class="tag" value="">
			<input type="button" name="" value="Incluir" class="enviar_anexos">
		</form>
	<?php } ?>
	</div>				
	
</div>
<script>

	
	$( document ).ready(function() {
	    //
	    // $("#gerarBalanco").click(function() {
	    // 	var contador_proprio = $(".contador_proprio").attr("checked");

	    // 	if( contador_proprio === true ){
	    // 		if( $("#dados_contador .nome").val() === '' ){
	    // 			alert("Infore o nome do contador");
	    // 			$("#dados_contador .nome").focus();
	    // 			return;
	    // 		}
	    // 		if( $("#dados_contador .crc").val() === '' && $("#contador_PJ").attr("checked") === true ){
	    // 			alert("Infore o crc do contador");
	    // 			$("#dados_contador .crc").focus();
	    // 			return;
	    // 		}
	    // 		if( $("#dados_contador .cpf_contador").val() === '' && $("#contador_PF").attr("checked") === true ){
	    // 			alert("Infore o cpf do contador");
	    // 			$("#dados_contador .cpf_contador").focus();
	    // 			return;
	    // 		}
	    // 		if( $("#dados_contador .endereco").val() === '' ){
	    // 			alert("Infore o endereço do contador");
	    // 			$("#dados_contador .endereco").focus();
	    // 			return;
	    // 		}
	    // 		if( $("#dados_contador .estado").val() === '' ){
	    // 			alert("Infore o estado do contador");
	    // 			$("#dados_contador .estado").focus();
	    // 			return;
	    // 		}
	    // 		if( $("#dados_contador .cidade").val() === '' ){
	    // 			alert("Infore a cidade do contador");
	    // 			$("#dados_contador .cidade").focus();
	    // 			return;
	    // 		}
	    // 		if( $("#dados_contador .cep").val() === '' ){
	    // 			alert("Infore o cep do contador");
	    // 			$("#dados_contador .cep").focus();
	    // 			return;
	    // 		}

	    // 	}
	    // 	location = "balanco-patrimonial.php?ano=<?php echo $ano; ?>&contador_proprio="+contador_proprio;
	    // });
		$("#prosseguirBalanco").click(function() {
			location.href = 'balanco-patrimonial-form.php?ano='+$("#ano").val();
		});
		$(".editar_item_livro_caixa").change(function() {
			var valor = $(this).val();
			var id = $(this).attr("id");
			var tabela = $(this).attr("tabela");
			var campo = $(this).attr("campo");
			if( tabela === 'user_<?php echo $_SESSION['id_empresaSecao'] ?>_livro_caixa' ){
				$.ajax({
				  url:'ajax.php'
				  , data: 'editar_item=editar_item&id='+id+'&valor='+valor+'&campo='+campo+'&tabela='+tabela
				  , type: 'post'
				  , async: true
				  , cache:false
				  , success: function(retorno){
				  		console.log(retorno);
				  }
				}); 
			}
		});
		//Edita um item intangivel via ajax
	    $(".editar_item_intengivel").change(function() {
			var valor = $(this).val();
			if( campo != 'item' ){
				var valor = valor.replace(".", "");
				var valor = valor.replace(",", ".");
			}
			var id = $(this).attr("id");
			var tabela = $(this).attr("tabela");
			var campo = $(this).attr("campo");

			var ano = "<?php echo $ano ?>";

			$.ajax({
			  url:'ajax.php'
			  , data: 'editar_item_imobilizado=editar_item_imobilizado&id='+id+'&valor='+valor+'&campo='+campo+'&tabela='+tabela+'&ano='+ano
			  , type: 'post'
			  , async: true
			  , cache:false
			  , success: function(retorno){
			  		console.log(retorno);
			  	 	obj = JSON.parse(retorno);
			  }
			}); 
		});
		//Edita um item imobilizado via ajax
		$(".item_imobilizado_editar").change(function() {
			var valor = $(this).val();
			if( campo != 'item' ){
				var valor = valor.replace(".", "");
				var valor = valor.replace(",", ".");
			}
			var id = $(this).attr("id");
			var tabela = $(this).attr("tabela");
			var campo = $(this).attr("campo");

			var ano = "<?php echo $ano ?>";

			var linha = $(this).parent().parent().parent();

			if( campo === 'valor' || campo === 'data' || campo === 'quantidade' )
				if( $(linha).find(".input_valor").val() != '' && $(linha).find(".input_ano").val() != '' && $(linha).find(".input_quantidade").val() != '' ){
					sugerirValorMercado(linha);
				}

			$.ajax({
			  url:'ajax.php'
			  , data: 'editar_item_imobilizado=editar_item_imobilizado&id='+id+'&valor='+valor+'&campo='+campo+'&tabela='+tabela+'&ano='+ano
			  , type: 'post'
			  , async: true
			  , cache:false
			  , success: function(retorno){
			  		console.log(retorno);
			  	 	obj = JSON.parse(retorno);
			  }
			}); 
		});
		//Pega o valor, ano de compra e calcula a depreciação para sugerir um valor de mercado
		function sugerirValorMercado(obj){
			var item =	$(obj).find(".item").val(); 
			var valor =	$(obj).find(".input_valor").val(); 
			valor = valor.replace(".", "");
			valor = valor.replace(",", ".");
			var data  =	$(obj).find(".input_ano").val();
			var quantidade = $(obj).find(".input_quantidade").val();

			var id = $(obj).find(".item").attr("id"); 

			var ano = "<?php echo $ano ?>";

			$.ajax({
			  url:'ajax.php'
			  , data: 'sugerirValorMercado=sugerirValorMercado&valor='+valor+'&data='+data+'&item='+item+'&ano='+ano+'&quantidade='+quantidade+'&id='+id
			  , type: 'post'
			  , async: true
			  , cache:false
			  , success: function(retorno){
			  		$(obj).find(".input_valor_mercado").val(retorno);
			  }
			}); 		
		}
		//Remove o ultimo item imobilizado
	    $("#remover_imobilizado").click(function() {
			$.ajax({
				url:'ajax.php'
				, data: 'removerImobilizado=removerImobilizado&&id='+$("#imobilizado_deletar").val()
				, type: 'post'
				, async: true
				, cache:false
				, success: function(retorno){
					location = "<?php echo $_SERVER['REQUEST_URI']; ?>";
					location.reload();
				}
			});
		});
		//Insere um item imobilizado
		$("#inserir_outro_imobilizado").click(function() {
			$.ajax({
				url:'ajax.php'
				, data: 'inserirImobilizado=inserirImobilizado&ano='+<?php echo $ano ?>
				, type: 'post'
				, async: true
				, cache:false
				, success: function(retorno){
					location = "<?php echo $_SERVER['REQUEST_URI']; ?>";
					location.reload();
				}
			});
		});
		//Remove o ultimo item intangivel
		$("#remover_intangiveis").click(function() {
			$.ajax({
				url:'ajax.php'
				, data: 'removerIntangivel=removerIntangivel&&id='+$("#intangivel_deletar").val()
				, type: 'post'
				, async: true
				, cache:false
				, success: function(retorno){
					location = "<?php echo $_SERVER['REQUEST_URI']; ?>";
					location.reload();
				}
			});
		});
		//Insere outro item intangivel
		$("#inserir_outro_intangiveis").click(function() {
			$.ajax({
				url:'ajax.php'
				, data: 'inserirIntangivel=inserirIntangivel&ano='+<?php echo $ano ?>
				, type: 'post'
				, async: true
				, cache:false
				, success: function(retorno){
					location = "<?php echo $_SERVER['REQUEST_URI']; ?>";
					location.reload();
				}
			});
		});
		//Remove o ultimo item do emprestimo
		$("#remover_emprestimo").click(function() {
			$.ajax({
				url:'ajax.php'
				, data: 'removerEmprestimo=removerEmprestimo&&id='+$("#emprestimo_deletar").val()
				, type: 'post'
				, async: true
				, cache:false
				, success: function(retorno){
					location = "<?php echo $_SERVER['REQUEST_URI']; ?>";
					location.reload();
				}
						});
		});
		//Insere outro item do emprestimo
		$("#inserir_outro_emprestimo").click(function() {
			$.ajax({
				url:'ajax.php'
				, data: 'inserirEmprestimo=inserirEmprestimo&ano='+<?php echo $ano ?>
				, type: 'post'
				, async: true
				, cache:false
				, success: function(retorno){
					location = "<?php echo $_SERVER['REQUEST_URI']; ?>";
					location.reload();
				}
			});
		});
	});
	//Alterna entre o contador proprio e o contador indicado
	$(".contador_proprio").change(function() {
		if( $(".contador_proprio").attr("checked") === false ){
			$("#dados_contador").css('display', 'none');
			$("#dados_contador_indicado").css('display', 'block');
		}
		else{
			$("#dados_contador").css('display', 'block');
			$("#dados_contador_indicado").css('display', 'none');
		}


	});
	//Salva o tipo do contador
	function salvarTipoContador(tipo){
		$.ajax({
			url:'ajax.php'
			, data: 'acao=salvarTipoContador'+'&tipo='+tipo
			, type: 'post'
			, async: true
			, cache:false
			, success: function(retorno){
				
			}
		});
	}
	//Mostra os campos CRC ou CPF de acordo com o tipo escolhido
	$("#contador_PJ").click(function() {
		
		$("#contador_crc").css("display","table-row");
		$("#contador_cpf").css("display","none");

	});
	//Mostra os campos CRC ou CPF de acordo com o tipo escolhido
	$("#contador_PF").click(function() {
		
		$("#contador_cpf").css("display","table-row");
		$("#contador_crc").css("display","none");

	});
	//Busca as cidades de um estado
	$("#estado_contador").change(function(event) {
		var uf = $(this).val();
		$.ajax({
			url: 'SEFIP_config.php',
			data: 'uf='+uf,
			dataType:"text",
			type:"POST",
			cache: false,
			success: function(response){
				$("#contador_cidade").empty();
				$("#contador_cidade").append(response);
			}
		});
	});
	//Salva as contingencias passivas quando alterada
	$("textarea").focusout(function() {
		var texto = $("#contingenciaTexto").val();

		$("#textoContingencia").empty();
		$("#textoContingencia").append(texto);

		if( texto === "" ){
			$(".remove_contingencias").addClass('hideImpressao');
			$(".remove_contingencias").removeClass('apenasImpressao');
		}
		else{
			$(".remove_contingencias").removeClass('apenasImpressao');
			$(".remove_contingencias").addClass('apenasImpressao');
		}

		var ano_aux = "<?php echo $ano ?>";
		var id = "<?php echo $_SESSION['id_empresaSecao'] ?>";

		$.ajax({
		  url:'ajax.php'
		  , data: 'acao=salvarContingencia&texto='+texto+'&ano='+ano_aux+'&id='+id
		  , type: 'post'
		  , async: true
		  , cache:false
		  , success: function(retorno){
		  }
		}); 

	});
</script>
<script>
	$( document ).ready(function() {
	    //Abre a janela com os anexos de um determinado item
		$(".abrirJanelaAnexos").click(function() {
			var titulo = $(this).attr("titulo");
			var tipo = $(this).attr("tipo");
			var ano = "<?php echo $ano ?>";
			var id = "<?php echo $_SESSION['id_empresaSecao']; ?>";

			var tag = $(this).attr("tag");		

			// $("#upload_anexos").find('.fecharDiv').attr("abrir","imobilizados");

			$("#upload_anexos").find('.tituloVermelho').empty();

			$("#upload_anexos").find('.tituloVermelho').append("Anexar comprovante do bem");

			$("#upload_anexos").find('form').find('.tipo').val(tipo);		
			$("#upload_anexos").find('form').find('.ano').val(ano);		
			$("#upload_anexos").find('form').find('.id').val(id);		

			$("#upload_anexos").find('form').find('.tag').val(tag);

			$("#upload_anexos").css("display","block");

			$.ajax({
				url:'ajax.php'
				, data: 'acao=gerarItensAnexo&tipo='+tipo+'&ano='+ano+'&id='+id
				, type: 'post'
				, async: true
				, cache:false
				, success: function(retorno){
					$(".anexos_existentes tbody").empty();
					$(".anexos_existentes tbody").append(retorno);
					funcaoExcluirItem();
				}
			}); 

		});	
		//Salva os itens do anexo
		$(".enviar_anexos").click(function() {
			
			$("#form_anexos").append('<input type="hidden" name="hash" value="'+$(this).parent().parent().parent().find(".fecharDiv").attr("abrir")+'">');
			if( $(this).parent().parent().find(".fecharDiv").attr("abrir") === "intangiveis" ){
				salvarDadosIntangiveis();	
			}
			if( $(this).parent().parent().find(".fecharDiv").attr("abrir") === "imobilizados" ){
				salvarDadosImobilizado();	
			}		
			$("#form_anexos").submit();
		});
		//Exclui um item do anexo
		function funcaoExcluirItem(){
			$(".excluirAnexoBalanco").click(function() {
				
				var id_item = $(this).attr("id");
				var id = "<?php echo $_SESSION['id_empresaSecao']; ?>";

				var confirmacao = confirm("Tem certeza que deseja excluir o anexo '"+$(this).attr("nome-arquivo")+"'");
				var item = $(this).parent().parent();

				if( confirmacao ){

					$.ajax({
						url:'ajax.php'
						, data: 'acao=excluirItemId'+'&id_item='+id_item+'&id='+id
						, type: 'post'
						, async: true
						, cache:false
						, success: function(retorno){
							$(item).remove();
						}
					});
				}
			});
		}
		//Fecha a div
		$(".fecharDiv").click(function() {
			$(this).parent().css('display', 'none');
		});   
	});
</script>
<script>
	$( document ).ready(function() {
		//Mascara do CRC
		$('.campoCRC').mask('**.999.999.*/9');
		//Altera o ano do balanço
		$(".atualizarAno").change(function() {
			$("#ano").val($(this).val());
			location = 'balanco-patrimonial-form.php?ano='+$(this).val()+"#visualizar";
		});
		//Edita os itens do contador via ajax
		$(".editar_item_contador_tipo").click(function() {
			var valor = $(this).val();
			var id = $(this).attr("id-item");
			var tabela = $(this).attr("tabela");
			var campo = $(this).attr("campo");
			$.ajax({
			  url:'ajax.php'
			  , data: 'editar_item=true&id='+id+'&valor='+valor+'&campo='+campo+'&tabela='+tabela
			  , type: 'post'
			  , async: true
			  , cache:false
			  , success: function(retorno){

			  }
			}); 
		});
		//Edita os itens do contador via ajax
		$(".editar_item_contador").change(function() {
			var valor = $(this).val();
			var id = $(this).attr("id-item");
			var tabela = $(this).attr("tabela");
			var campo = $(this).attr("campo");
			$.ajax({
			  url:'ajax.php'
			  , data: 'editar_item=true&id='+id+'&valor='+valor+'&campo='+campo+'&tabela='+tabela
			  , type: 'post'
			  , async: true
			  , cache:false
			  , success: function(retorno){

			  }
			}); 
		});
		//Edita os itens do emprestimo via ajax
		$(".editar_item_emprestimos").change(function() {
			var valor = $(this).val();
			var valor = valor.replace(".", "");
			var valor = valor.replace(",", ".");
			var id = $(this).attr("id");
			var tabela = $(this).attr("tabela");
			var campo = $(this).attr("campo");

			$.ajax({
			  url:'ajax.php'
			  , data: 'editar_item=true&id='+id+'&valor='+valor+'&campo='+campo+'&tabela='+tabela
			  , type: 'post'
			  , async: true
			  , cache:false
			  , success: function(retorno){

			  }
			}); 
		});
		//Edita os itens do emprestimo via ajax
		$(".editar_item_emprestimo").change(function() {
			var valor = $(this).val();
			var valor = valor.replace(".", "");
			var valor = valor.replace(",", ".");
			var id = $(this).attr("id");
			var tabela = $(this).attr("tabela");
			var campo = $(this).attr("campo");
			$.ajax({
			  url:'ajax.php'
			  , data: 'editar_item_emprestimo=editar_item_emprestimo&id='+id+'&valor='+valor+'&campo='+campo+'&tabela='+tabela+'&ano='+ano
			  , type: 'post'
			  , async: true
			  , cache:false
			  , success: function(retorno){
			  		console.log(retorno);
			  }
			}); 
		});
		//Edita um item geral via ajax
		$(".editar_item").change(function() {
			var valor = $(this).val();
			var valor = valor.replace(".", "");
			var valor = valor.replace(",", ".");
			var id = $(this).attr("id");
			var tabela = $(this).attr("tabela");
			var campo = $(this).attr("campo");
			$.ajax({
			  url:'ajax.php'
			  , data: 'editar_item=editar_item&id='+id+'&valor='+valor+'&campo='+campo+'&tabela='+tabela+'&ano='+ano
			  , type: 'post'
			  , async: true
			  , cache:false
			  , success: function(retorno){
			  		console.log(retorno);
			  }
			}); 
		});
		//Edita via ajax os itens do balanço
		$(".editar_item_balanco").change(function() {
			var valor = $(this).val();
			var valor = valor.replace(".", "");
			var valor = valor.replace(",", ".");
			var id = $(this).attr("id");
			var tabela = $(this).attr("tabela");
			var campo = $(this).attr("campo");

			var ano = $("#ano").val();

			$.ajax({
			  url:'ajax.php'
			  , data: 'editar_item_balanco=true&id='+id+'&valor='+valor+'&campo='+campo+'&tabela='+tabela+'&ano='+ano
			  , type: 'post'
			  , async: true
			  , cache:false
			  , success: function(retorno){
			  		console.log(retorno);
			  	 	obj = JSON.parse(retorno);
			  	 	if( obj[0] === 'gerarId' ){
			  	 		var inputs = $("table input");
			  	 		for (var i = 0; i < inputs.length; i++) {
			  	 			$(inputs[i]).attr("id",obj[1]);
			  	 		};
			  	 	}
			  }
			}); 
			if( campo === 'a_n_c_investimentos' ){
				atualizarCaixaInvestimento(valor);
			}
		});
	    function atualizarCaixaInvestimento(valor){
			var total = getFloatVal(".original_a_c_caixa_equivalente_caixa") - valor;
			$(".a_c_caixa_equivalente_caixa").val(inverterString(total));
	    }
	    function setFloatVal(string){
			return string.replace(".", ",");
		}
		//Transforma a string em formato de dinheiro
		function inverterString(string){
			var num = Number(string).toFixed(2);
			num = setFloatVal(num);
			var j = -2;
			var aux = '';
			for (var i = num.length - 1; i >= 0; i--) {
				aux = aux + num[i];
				if( j % 3 === 0 && num.length > 6 && j > 1 && i > 0)
					aux = aux + '.';	
				j = j + 1;
			};
			result = '';
			for (var i = aux.length - 1; i >= 0; i--) {
				result = result + aux[i];
			};
			result = result.replace("-.", "-");
			return result;
		}
		//Pega o tipo float de um item
		function getFloatVal(classe){
			var valor = $(classe).val();
			var result = valor.replace(".", "");
			var result = result.replace(",", ".");
			if( result === '' )
				result = 0;
			return parseFloat(result);
		}
	});
</script>
<script>
	$( document ).ready(function() {
	    //Inicia a mascara de dinheiro
		$("table").find('.currency').maskMoney();	
		$(function() {
			$('.currency').maskMoney();
		})
		//Reinicia a mascara quando o item vem via script
		function setarMascara(){
			$("table").find('.currency').maskMoney();
		 	$("table").find('.campoData').mask('99/99/9999');
		}	
		//Define o contador, se é proprio ou indicado pelo contador amigo - INICIAL
		$("#imprimir_balanco").attr("href","balanco-patrimonial.php?ano=<?php echo $ano; ?>&contador_proprio="+$(".contador_proprio").attr("checked"));
		//Define o contador, se é proprio ou indicado pelo contador amigo - CLICK
		$(".contador_proprio").click(function() {
			$("#imprimir_balanco").attr("href","balanco-patrimonial.php?ano=<?php echo $ano; ?>&contador_proprio="+$(".contador_proprio").attr("checked"));
		});
	});
</script>
<div style="clear: both;height: 10px;"></div>
<?php include 'rodape.php'; ?>






