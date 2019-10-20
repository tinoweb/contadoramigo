<?php include 'header_restrita.php'; ?>
<?php include 'livro-diario-razao.class.php'; ?>
<?php 
	
//	ini_set('display_errors',1);
//	ini_set('display_startup_erros',1);
//	error_reporting(E_ALL);

	$livro_diario = new Livro_diario();
	$plano_contas = new Plano_de_contas();
	$intervalo_inicio = getAnoInicio();
	$intervalo_fim = getAnoFim();
	$tipo = 'razao';

?>

<div class="principal">
	<h1 class="titulo">Escrituração</h1>
	<h2 class="hideImpressao">Livro Razão</h2>
	<h2 class="apenasImpressao">Livro Razão - de <?php echo getAnoInicio(); ?> até <?php echo getAnoFim(); ?></h2>
	<h2 class="apenasImpressao"><?php echo $_SESSION['nome_userSecao']; ?></h2>
	<p class="hideImpressao">
		O livro diário registra as entradas e saídas da empresa no formato contábil, como o livro razão, é segmentado por conta, isto é, por tipo de movimentação. É usado na elaboração do balanço. Você não precisará atualizá-lo, pois ele é gerado automaticamente a partir do seu livro-caixa.
	</p>
	<br>
	<div style="float:left;width:100%;margin-bottom:20px;" class="hideImpressao">
		Exibir 
		<select id="categoria">
			<option value="">Selecione</option>
			<?php echo $plano_contas->getCategorias()  ?>
		</select>
		no período de:  
		<input name="DataInicio" id="DataInicio" type="text" value="<?php echo getAnoInicio(); ?>" maxlength="10" style="width:70px" class="campoData"> 
		até: 
		<input name="DataFim" id="DataFim" type="text" value="<?php echo getAnoFim(); ?>" maxlength="10" style="width:70px" class="campoData"> 
		<input name="Alterar" type="submit" id="alterarPeriodo" value="Exibir">
		<div class="divCarregando2" style="margin-left:20px; text-align:center;display:none"><img src="images/loading.gif" width="16" height="16"></div>
	</div>
	<script>
		$( document ).ready(function() {
			$("#alterarPeriodo").click(function() {
				inicio = $("#DataInicio").val();
				fim = $("#DataFim").val();
				tipo = $("#tipo").val();
				codigo = $("#categoria").val();
				$(this).css('display', 'none');
				$(".divCarregando2").css("display","initial");
				location = 'livro-razao.php?tipo=diario&DataInicio='+inicio+'&DataFim='+fim+'&codigo='+codigo;
			});
		});
	</script>
	<style type="text/css" media="screen">
		tr:hover{
			background-color: rgb(234, 239, 245);
		}
		<?php if( isset($_GET['codigo_consulta']) ){ ?>
			#item_<?php echo $_GET['codigo_consulta']; ?> .td_calendario{
				background: #f9f9f9 !important;
			}
		<?php } ?>
	</style>

<?php 

	//Função que retorna o sql com os itens do livro caixa para um determinado intervalo
	function getSqlLivroCaixa( $intervalo_inicio , $intervalo_fim  ){
		$datas = new Datas();
		
//		return mysql_query("SELECT * FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa WHERE ( data >= '".$datas->converterData($intervalo_inicio)."' AND data <= '".$datas->converterData($intervalo_fim)."' ) AND categoria != '' AND categoria != 'Repasse a terceiros' ORDER BY data ASC");
		
		$query = " SELECT lc.*, pagar.categoria_secundaria_1, pagto.categoria_secundaria_2 "
			." FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa lc "
			." LEFT JOIN lancamento_contas_pagar_receber pagar ON pagar.livro_caixa_id = lc.id AND pagar.empresaId = '".$_SESSION['id_empresaSecao']."' "
			." LEFT JOIN lancamento_contas_pagar_receber pagto ON pagto.livro_caixa_id_pagamento = lc.id AND pagto.empresaId = '".$_SESSION['id_empresaSecao']."' "
			." WHERE ( data >= '".$datas->converterData($intervalo_inicio)."' AND data <= '".$datas->converterData($intervalo_fim)."' ) "
			." AND lc.categoria != '' "
			." AND lc.categoria != 'Repasse a terceiros' "
			." ORDER BY lc.data ASC ";
		
		return mysql_query($query);		
	}
	function verificaCategoriasBloqueadas( $intervalo_inicio , $intervalo_fim  ){

		$datas = new Datas();
/*		$consulta = mysql_query("SELECT * FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa WHERE ( data >= '".$datas->converterData($intervalo_inicio)."' AND data <= '".$datas->converterData($intervalo_fim)."' ) AND categoria != ''  AND ( categoria = 'Devolução de adiantamento' OR categoria = 'Reembolso de despesas' OR categoria = 'Outros' )  ORDER BY data ASC"); */

		$consulta = mysql_query("SELECT * FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa WHERE ( data >= '".$datas->converterData($intervalo_inicio)."' AND data <= '".$datas->converterData($intervalo_fim)."' ) AND categoria != ''  AND categoria = 'Reembolso de despesas'  ORDER BY data ASC");

		if( mysql_num_rows($consulta) > 0 )
/*				echo '	<br><div class="tituloVermelho">Impossível Prosseguir</div>
						Detectamos lançamentos em seu livro-caixa marcados como 
						"reembolso de despesas" e/ou "outros". Estas categorias são 
						incompatíveis com o Livro Razão. 
						Antes de prosseguir, altere tais lançamentos para categorias mais específicas.';
*/						
			echo '	<br><div class="tituloVermelho">Impossível Prosseguir</div>
						Detectamos lançamentos em seu livro-caixa marcados como 
						"reembolso de despesas". Estas categorias são 
						incompatíveis com o Livro Razão. 
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
			echo '<input name="Alterar" type="button" onclick="location.reload();" value="Prosseguir" style="margin-top:10px;margin-right:10px">';
		return false;

	}
	//Define o CNAR principal do usuario
	$cnae_principal = $user->getCnae();
	$livro_diario->setcnae($cnae_principal[0]);
	
	function ListaClientes($categoria) {
		
		$clientes = array();
		
		// Monta a consulta para pegar a lista de clientes.
		$sql_clientes = "SELECT DISTINCT apelido FROM dados_clientes WHERE id_login = " . $_SESSION['id_empresaSecao'] . " AND status = 'A' ORDER BY apelido";
		$rs_clientes = mysql_query($sql_clientes);
		if(mysql_num_rows($rs_clientes) > 0){
			while($dados_clientes = mysql_fetch_array($rs_clientes)){
				$clientes[] = $dados_clientes['apelido'];
			}
		}

		return $clientes;
	}
	
	function ListaContasAPagar($categoria){
		
		$listaContasAPagar = array(
			'Aluguel'
			,'Aluguel de software'
			,'Água'
			,'Combustível'
			,'Comissões'
			,'Condomínio'
			,'Contador'
			,'Cursos e treinamentos'
			,'Energia elétrica'
/*			,'Estagiários'*/
			,'Impostos e encargos'
			,'Internet'
			,'Marketing e publicidade'
			,'Material de escritório'
/*			,'Pagto. de Salários'
			,'Pgto. a autônomos e fornecedores'
			,'Pró-Labore'*/
			,'Segurança'
			,'Seguros'
			,'Telefone'
			,'Transportadora / Motoboy'
		);
		
		
		return $listaContasAPagar;
	}
		
	// Método criado para poder normalizar a apresentação na tebela do livro diario referente a contas a pagar e contas a receber.
	function NormalizaContasApagarEContasReceber($item){
		
		// Verifica se e se o lançamento e um pagamento ou a ser pago. 
		if(isset($item['categoria_secundaria_2']) &&  !empty($item['categoria_secundaria_2'])){

			// Cria um array com os dados para poder normalizar o a visualização da linha na tabela.
			$itemArray = array(
				'id'=>$item['id'],
				'data'=>$item['data'],
				'entrada'=>$item['entrada'],
				'saida'=>$item['saida'],
				'documento_numero'=>$item['documento_numero'],
				'descricao'=>$item['descricao'], 
				'categoria'=>$item['categoria_secundaria_2'], // Inclui a categoria referente ao pagamento. 
				'categoriaAux'=>$item['categoria'] // A index categoria auxiliar e usada para pegar a categoria real do registro pai. 
			); 

		} // Lançamento a ser pago.
		else {

			// Cria um array com os dados para poder normalizar o a visualização da linha na tabela.
			$itemArray = array(
				'id'=>$item['id'],
				'data'=>$item['data'],
				'entrada'=>$item['entrada'],
				'saida'=>$item['saida'],
				'documento_numero'=>$item['documento_numero'],
				'descricao'=>$item['descricao'], 
				'categoria'=>$item['categoria_secundaria_1'], // Inclui a categoria referente a ser pago. 
				'categoriaAux'=>$item['categoria'] // A index categoria auxiliar e usada para pegar a categoria real do registro pai. 
			);			
		}
		
		
		return $itemArray;		
	}
		
	// Método criado para vefica se o lancamento e contas a para ou contas a receber.
	function ChecaSeContasApagarOuReceber($item) {

		// Inicia o status vazio.
		$status = false;
		
		// Verifica se o lancamento é um contas a receber.
		if( $item['categoria'] == 'Serviços prestados em geral' || ListaClientes($item['categoria']) ) {	
			
			// Verifica se a categoria secundaria foi informada para poder nomalizar quando for criar o relacionamento do plano de contas. 
			if(!empty($item['categoria_secundaria_1']) || !empty($item['categoria_secundaria_2']) ) {
				$status = true;
			} 

		} // Verifica se o lancamento é um contas a .
		else if(in_array($item['categoria'], ListaContasAPagar($categoria))){
			
			// Verifica se a categoria secundaria foi informada para poder nomalizar quando for criar o relacionamento do plano de contas. 
			if(!empty($item['categoria_secundaria_1']) || !empty($item['categoria_secundaria_2']) ) {
				$status = true;
			}
		}
		
		return $status;
	}	
		
	// Método criado para montar a linhas da tabela do livro diário.
	function MontaLinhaTabela($livro_diario, $item, $plano_contas ) {

		// Verifica se a categoria a ser usada é a principal ou secundaria para normalizar os lançamentos de contas a pagar e receber.
		$categoria = isset($item['categoriaAux']) ? $item['categoriaAux'] : $item['categoria'];
		
		//Define o tipo do user que recebeu o pagamento.
		$livro_diario->settipo_user_pagamento($livro_diario->ifPagamentoTipo($item));

		//Pega a relação Creditdo-Debitado para a categoria do item
		$categorias_livro = $livro_diario->getRelacao($item['categoria'],$item);
		//Cria um item do livro caixa para armazenar os dados
		$item_livro_caixa = new Item_livro_diario();
		//Define a categoria do item
		$item_livro_caixa->setcategoria($categoria);
		//Define a descrição do item
		$item_livro_caixa->setdescricao($item['descricao']);
		//Define o valor do item
		$item_livro_caixa->setvalor($item);
		//Define a data do item
		$item_livro_caixa->setdata($item['data']);
		//Define o ID do item
		$item_livro_caixa->setid($item['id']);
		//Pega os dados da relação Creditado-Debitado para a categoria do item
		$item_livro_caixa->setrelacao_cr_db($categorias_livro);
		//Insere o item para montar o livro Razão
		$plano_contas->setItem($item_livro_caixa);
		//Insere o item para montar o livro Razão	
		//$plano_contas->setItemAgrupado($item['categoria'],$categorias_livro,$item_livro_caixa->getvalor());
	}	
	
	// Faz a consulta no banco, retornando o SQL com os itens do livro caixa que correnpndem ao intervalo selecionado
	if( verificaCategoriasBloqueadas($intervalo_inicio,$intervalo_fim) ){
		$livro_caixa = getSqlLivroCaixa($intervalo_inicio,$intervalo_fim);
		//Percorre cada item adicionando na categoria correspondente para Credito e débito
		while( $item = mysql_fetch_array($livro_caixa) ){
			
			//Se for empréstimo define o prazo de pagamento para o emprestimo do usuario
			$livro_diario->setprazo($livro_diario->ifEmprestimo($item));
			
			//Define o id de quem recebeu o pagamento para consultar se é administrativo ou operacionais 
			$livro_diario->setid_user_pagamento($livro_diario->ifPagamento($item));
			
			//Se for pagamento seta os dados de pagamento
			$livro_diario->ifPagamento($item);
			
			//Define o tipo do user que rcebeu o pagamento
			//$livro_diario->settipo_user_pagamento($livro_diario->ifPagamentoTipo($item));
			
			//Se for pagamento seta o tipo de pagamento
			$livro_diario->ifPagamentoTipo($item);
							
			// Verifica se tem tratamento para categoria.
			if($item['categoria'] == 'Empréstimo (amortização)') {
				
				// Pega o juros da amortização.
				$juros = $livro_diario->PegaJurosAmortizacao($_SESSION['id_empresaSecao'], $item['id']);

				// Subitrai do valor amortizado o juros.
				$item['saida'] = $item['saida'] - $juros;

				$itemAmortizacao = array(
					'id'=>$item['id'],
					'data'=>$item['data'],
					'entrada'=>$item['entrada'],
					'saida'=>$juros,
					'documento_numero'=>$item['documento_numero'],
					'descricao'=>$item['descricao'],
					'categoria'=>'Juros do Empréstimo'
				);
				
				// Inclui a linha da amortização.
				MontaLinhaTabela( $livro_diario, $item, $plano_contas );				

				// Inclui a linha do juros. 
				MontaLinhaTabela( $livro_diario, $itemAmortizacao, $plano_contas );
				
			} else if(ChecaSeContasApagarOuReceber($item)){
				
				// Chama o método para poder normalizar o contas a pagar.	
				$itemNormalizado = NormalizaContasApagarEContasReceber($item);
					
				// Incluir a linha de serviços em geral com a data da nota.
				MontaLinhaTabela($livro_diario, $itemNormalizado, $plano_contas );				
				
			} else {
				
				// Inclui a linha do livro diário.
				MontaLinhaTabela($livro_diario, $item, $plano_contas );
			}
		}		
		
		echo '<div style="float: left;margin-right: 30px;width:100%">';
		$plano_contas->listarItensRazao();
		echo '</div>';
?>
<div style="clear:both; height:10px"></div>
<center>
	<div style="width:966px;margin-top:20px;">
		<center>
			<button style="margin-right:20px;" onclick="window.print();">Imprimir Livro Razão</button>
			<a href="exportar-csv-livro-diario-razao.php?tipo=razao&DataInicio=<?php echo getAnoInicio(); ?>&DataFim=<?php echo getAnoFim(); ?>&codigo=<?php echo $_GET['codigo'] ?>" target="_blank">
				<button id="btnCSV2">Exportar para Excel</button>
			</a>
		</center>
	</div>
	<!-- <input type="button" id="gerarBalanco" value="Gerar Balanço">	 -->
</center>
</div>
<?php } ?>
<script>

	
	$( document ).ready(function() {
	    
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
	    
	});

</script>
<div style="clear:both; height:10px"></div>
<style type="text/css" media="screen">
	.apenasImpressao{
		display: none;
	}
</style>
<style type="text/css" media="print">

	@media print {
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
		header{
			display: none;
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
		table a{
			display: initial !important;
			color: #000000 !important;
			text-decoration: none!important;
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
		th,td {
		    background-color: #024a68;
		    color: #FFF;
		    border: 1px solid #000;
		}
		input{
			border: 0;
		}
		table{
			border: 1px solid #000;
		}
		.td_calendario{
			color: #000;
			font-style: normal;
		}
	}
</style>
<?php include 'rodape.php'; ?>