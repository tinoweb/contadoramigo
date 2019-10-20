<?php 
	
//	ini_set('display_errors',1);
//	ini_set('display_startup_erros',1);
//	error_reporting(E_ALL);

	if(isset($_POST['methodAjax']) && !empty($_POST['methodAjax'])) {

		session_start();
		
		require_once('conect.php');
		require_once('Controller/balanco_patrimonial-controller.php');
		
		// Instância a classe.
		$balancoPatrimonial = new Balanco_Patrimonial();
		
		// Chama o método de controle do ajax.
		echo $balancoPatrimonial->ControleAjax($_POST['methodAjax']);
		
		// Para a execução do código.
		die();
	}

	include 'header_restrita.php';
	include 'livros_caixa_fluxo_class.php';
	include 'balanco-form.class.php'; 
	
	if( isset($_GET['ano']) )
		$ano = $_GET['ano'];
	else
		$ano = 0;

	//Pega os dados do item do ano atual se existir,
	$consulta = mysql_query("SELECT * FROM balanco_patrimonial WHERE id_user = '".$_SESSION['id_empresaSecao']."' AND ano = '".$ano."' ");
	$dados = mysql_fetch_array($consulta);

	$item = new Balanco_form($dados,$ano);
?>
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
		</div>
	<br>
	<br>
	</div>
	<div id="tag">
				
	</div>
	
<?php 
	
		function verificaCategoriasBloqueadas( $ano ){

			$datas = new Datas();
/*			$consulta = mysql_query("SELECT * FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa WHERE ( data >= '".$ano."-01-01' AND data <= '".$ano."-12-31' ) AND ( categoria = 'Devolução de adiantamento' OR categoria = 'Reembolso de despesas' OR categoria = 'Outros' )  ORDER BY data ASC");	*/
$consulta = mysql_query("SELECT * FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa WHERE ( data >= '".$ano."-01-01' AND data <= '".$ano."-12-31' ) AND ( categoria = 'Reembolso de despesas')  ORDER BY data ASC");	
			if( mysql_num_rows($consulta) > 0 )
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
	
	if( verificaCategoriasBloqueadas($ano) && $ano != 0 ): 
?>	
	<div class="tituloAzul">Assinatura do contador e registro do balanço</div>
	Para que o balanço tenha validade, deverá auditado por um contador e registrado na Junta Comercial, junto com o <a href="dre.php">DRE - Demonstrativo de Resultado do Exercício</a>. O contador Amigo dispõe de um contabilista perceiro que poderá fazer isso para você. O valor desde serviço varia de acordo com o número de lançamentos efetuados no livro-caixa durante o período. No seu caso, para o ano-calendário de 2017 o custo é de R$ 0,00.<br>
<br>
Para solicitar o serviço de nosso contabilista parceiro, envie uma mensagem ao <a href="suporte.php">Help Desk</a>. O Contador fará toda a validação. Possivelmente entrará em contato com você para esclarecer alguns pontos. Depois de concluído o trabalho, enviará o balanço assinado pelo Correio para que você possa registrá-lo.<br><br> 
	
	Mesmo que não pretenda submeter a um contador, você poderá gerar o balanço nesta ferramenta e usá-lo para sua própria análise. O balanço contém informações muito relevantes para a empresa que poderão ajudá-lo em seu planejamento para o futuro.<br><br><br>

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
	</center>
<?php 
	endif;
?>
</div>

<script>
	$( document ).ready(function() {
				
		//Altera o ano do balanço
		$(".atualizarAno").change(function() {
			
			var status = false;
			var ano = $(this).val();
			
			$("#ano").val(ano);
			
			$.ajax({
				type: 'POST',
				url: '/balanco_patrimonial.php',
				data: {methodAjax:'RealizaCalculoBalanco',ano:ano},
				dataType: 'json',
				beforeSend: function() {},
				success: function(data) {
					
					console.log(data);
					
					//$("#tag").html('<pre>'+data+'</pre>');
									
					if(data['status']){
						status = true;
					} else {
						alert(data['mensagem']);	
					}
					
				},
				error: function(xhr) { 
					
					console.log(xhr);
					
					alert('houve um erro, por favor, entre em contato com o nosso help dask.');
				},
				complete: function() {
					if(status){
						location = 'balanco_patrimonial.php?ano='+ano+"#visualizar";
					}
				}
			});
		});
	});
</script>
<div style="clear: both;height: 10px;"></div>
<?php include 'rodape.php'; ?>






