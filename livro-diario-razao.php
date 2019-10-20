<?php include 'header_restrita.php'; ?>
<?php include 'livro-diario-razao.class.php'; ?>
<?php 
	
	$livro_diario = new Livro_diario();
	$plano_contas = new Plano_de_contas();
	$intervalo_inicio = getAnoInicio();
	$intervalo_fim = getAnoFim();
	$tipo = getTipo();

?>

<div class="principal">
	<?php if( $tipo == 'diario' ){ ?>
	<div class="titulo">Livro Diário</div>
	<?php }else{ ?>
	<div class="titulo">Livro Razão</div>
	<?php } ?>
	Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
	tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
	quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
	consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
	cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
	proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
	<br>
	<br>
	<div style="float:left;width:100%">
		<select id="tipo">
			<option value="diario" <?php selected('diario',$_GET['tipo']) ?>>Livro Diário</option>
			<option value="razao" <?php selected('razao',$_GET['tipo']) ?>>Livro Razão</option>
		</select> 
		 no período de:  
		<input name="DataInicio" id="DataInicio" type="text" value="<?php echo getAnoInicio(); ?>" maxlength="10" style="width:70px" class="campoData"> 
		até: 
		<input name="DataFim" id="DataFim" type="text" value="<?php echo getAnoFim(); ?>" maxlength="10" style="width:70px" class="campoData"> 
		<?php if( $tipo == 'razao' ){ ?>
		para a categoria 
		<select id="categoria">
			<option value="">Selecione</option>
			<?php echo $plano_contas->getCategorias()  ?>
		</select>
		<?php } ?>
		<input name="Alterar" type="submit" id="alterarPeriodo" value="Exibir">
	</div>
	<br><br><br>
	<script>
		$( document ).ready(function() {
			$("#alterarPeriodo").click(function() {
				inicio = $("#DataInicio").val();
				fim = $("#DataFim").val();
				tipo = $("#tipo").val();
				codigo = $("#categoria").val();
				if( tipo === 'razao' )
					location = 'livro-diario-razao.php?tipo='+tipo+'&DataInicio='+inicio+'&DataFim='+fim+'&codigo='+codigo;
				else
					location = 'livro-diario-razao.php?tipo='+tipo+'&DataInicio='+inicio+'&DataFim='+fim;
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
		return mysql_query("SELECT * FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa WHERE ( data >= '".$datas->converterData($intervalo_inicio)."' AND data <= '".$datas->converterData($intervalo_fim)."' ) AND categoria != 'Devolução de adiantamento' ORDER BY data ASC");	
	}

	//Define o CNAR principal do usuario
	$livro_diario->setcnae('6311-9/00');
	//Faz a consulta no banco, retornando o SQL com os itens do livro caixa que correnpndem ao intervalo selecionado
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
		$livro_diario->settipo_user_pagamento($livro_diario->ifPagamentoTipo($item));
		//Se for pagamento seta o tipo de pagamento
		$livro_diario->ifPagamentoTipo($item);
		//Pega a relação Creditdo-Debitado para a categoria do item
		$categorias_livro = $livro_diario->getRelacao($item['categoria'],$item);
		//Cria um item do livro caixa para armazenar os dados
		$item_livro_caixa = new Item_livro_diario();
		//Define a categoria do item
		$item_livro_caixa->setcategoria($item['categoria']);
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
		//Insere o item para montar o livro diário
		$plano_contas->setItem($item_livro_caixa);
		//Insere o item para montar o livro Razão	
		$plano_contas->setItemAgrupado($item['categoria'],$categorias_livro,$item_livro_caixa->getvalor());
	}
	//Exibe os itens do livro diário
	if( $tipo == 'diario' ){
		echo '<div>';
		$plano_contas->listarItensDiario();
		echo '</div>';
	}
	//Exibe os itens Debitados do livro Razão
	if( $tipo == 'razao' ){
		echo '<div style="float: left;margin-right: 30px;">';
		$plano_contas->listarItensRazao();
		echo '</div>';
	}
?>
</div>
<div style="clear:both; height:10px"></div>
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
<?php include 'rodape.php'; ?>