<?php include 'header_restrita.php' ?>
<?php include 'livros_caixa_fluxo_class.php' ?>

<?php include 'dre.class.php'; ?>
<div class="principal minHeight">		
	<h1>Escrituração</h1>
	<h2>Demonstrativo de Resultados do Exercício</h2>
	<div style="margin-bottom: 20px">A demonstração do resultado do exercício (DRE) se destina a evidenciar a formação do resultado líquido em um ano através do confronto entre as receitas, custos e despesas apuradas. Ele é parte integrante do balanço patrimonial. O DRE exibido abaixo mostra o resultado de sua empresa com base em seus lançamentos no livro-caixa. Para que apresente corretamente as demonstrações, é preciso que seu livro-caixa esteja completo.</div>
	<div class="anos"  style="margin-bottom:25px;">
	Selecione o ano-calendário do DRE&nbsp;&nbsp;&nbsp;&nbsp;
		<form action="dre.php" method="GET" accept-charset="utf-8">
			
			<?php 

				$ano_atual = intval(date("Y"));
				
				for ($i=intval(date("Y")); $i > intval(date("Y")) - 5; $i--) { 
					
					echo '	<label style="margin-right:20px;">
								<input class="alterarAno" style="margin-right:5px;" type="radio" name="ano" value="'.$ano_atual.'"';

					if( $ano_atual == intval(date("Y")) || $ano_atual == $_GET['ano'] )
						echo 'checked="checked"';
					echo '" >'.$ano_atual.'
							</label>';

					$ano_atual = $ano_atual - 1;

				}

				if( isset($_GET['ano']) )
					$ano = $_GET['ano'];
				else
					$ano = intval(date("Y"));

			?>
		</form>
	</div>
	<style type="text/css" media="screen">
		.agrupado{
			font-weight: bold;
    		color: #000;
		}
	</style>	
	<?php 

		function verificaCategoriasBloqueadas( $ano ){

			$datas = new Datas();
/*			$consulta = mysql_query("SELECT * FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa WHERE ( data >= '".$ano."-01-01' AND data <= '".$ano."-12-31' ) AND ( categoria = 'Devolução de adiantamento' OR categoria = 'Reembolso de despesas' OR categoria = 'Outros' )  ORDER BY data ASC");*/	
			$consulta = mysql_query("SELECT * FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa WHERE ( data >= '".$ano."-01-01' AND data <= '".$ano."-12-31' ) AND ( categoria = 'Reembolso de despesas' )  ORDER BY data ASC");
			if( mysql_num_rows($consulta) > 0 )
/*				echo '	<div class="tituloVermelho">Impossível Prosseguir</div>
						Detectamos lançamentos em seu livro-caixa marcados como 
						"reembolso de despesas" e/ou "outros". Estas categorias são 
						incompatíveis com o DRE. 
						Antes de prosseguir, altere na tabela abaixo os lançamentos para categorias mais específicas.';
*/			
				echo '	<div class="tituloVermelho">Impossível Prosseguir</div>
						Detectamos lançamentos em seu livro-caixa marcados como 
						"reembolso de despesas" e/ou "outros". Estas categorias são 
						incompatíveis com o DRE. 
						Antes de prosseguir, altere na tabela abaixo os lançamentos para categorias mais específicas.';			
			
			
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
		if( verificaCategoriasBloqueadas($ano) ){

	?>
	<?php 
		$dre = new Gerar_DRE();
		$dre->setano($ano);
		
		// Pega o cnae.
		$cnae = $user->getCnae();
		$dre->setcnae($cnae[0]);
		
		$dre->gerarDre();		
		echo $dre->gerarTabelas();

	 ?>
	 <div style="clear: both;height: 10px;"></div>
	 <div style="width:800px;"><br><center><button style="margin-right:20px;"id="btnCSV">Exportar para Excel</button><button id="btnPRINT">Imprimir</button></center></div>
	 <div style="clear: both;height: 10px;"></div>
<br>
	<?php } ?>
</div>
	<style type="text/css" media="print">
		@media print {
			.principal:first-child,.anos,.titulo,.tituloVermelho,.rodape,#btnPRINT,#btnCSV{
				display: none;
			}
			th {
			    background-color: #024a68;
			    color: #FFF;
			}
		}
	</style>
	<script>
		$(".alterarAno").change(function() {
			location = "dre.php?ano="+$(this).val();
		});
		$("#btnPRINT").click(function() {
			window.print();
		});
		$("#btnCSV").click(function(event) {
			window.open("exportar-csv-dre.php?ano=<?php echo $ano ?>",'_blank');
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
	</script>
<?php include 'rodape.php' ?>