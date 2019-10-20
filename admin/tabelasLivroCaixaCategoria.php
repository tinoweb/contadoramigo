<?php 
	
//	ini_set('display_errors',1);
//	ini_set('display_startup_erros',1);
//	error_reporting(E_ALL);

	require_once('header.php');
	require_once('../Model/LivroCaixa/LivroCaixaData.php');	
	
	$livroCaixaData = new LivroCaixaData();
	
	$tabelaTag = '';
	
	$list = $livroCaixaData->ListaCategoriasNaoPadronizadas();
	
	// cria o filtro de caregorias nao Padronizadas.
	$tagSelect = '<select name="categoria" style="width: 110px;">';
	$tagSelect .= '<option>Selecione</option>'; 
	if($list) {
		foreach($list as $val){
			if(!empty($val)){
				$tagSelect .= "<option value='".$val."'>".$val."</option>";
			}
		}
	}
	$tagSelect .= '</select>';
	
	if(isset($_GET['categoria']) && !empty($_GET['categoria'])) {
		
		$liv = $livroCaixaData->tabelaCategorias($_GET['categoria']);	
	
		$tabelaTag = "<table class='tableConteudo' style='width: 100%;'>";
		
		$tabelaTag .= "<tr class='linha0'>"
				   ."	<th style='width:15%; text-align: center;'>"
				   ."		<input type='checkbox' class='checkboxSelectAll' /> Seleciona Todos"
				   ."	</th>"
				   ."	<th style='width:10%; text-align: center;'>Código</th>"
				   ."	<th style='text-align: center;'>Categoria</th>"
				   ."</tr>";
	
		// verifica se houve retorno. 
		if($liv) {
			
			// for each para cata tabela de usuario.		   
			foreach($liv as $val){
				
				$tabelaTag .= " <tr class='linha0' align='center'>"
				   ."	<td><input type='checkbox' class='checkboxTable' name='".$val['id']."' value='".$val['id']."' /></td>"
				   ."	<td></td>"
				   ."	<td colspan='3'>Código: ".$val['id']." - ".$val['razao_social']."</td>"
				   ." </tr>";
	
				// Verifica itens das tabelas.
				foreach($val['dadosLivroCaixa'] as $v) {
					
					$tabelaTag .= "<tr class='linha1'>"
				   ."	<td></td>"
				   ."	<td>"
				   .(isset($v['id']) ? $v['id'] : '')
				   ."	</td>"
				   ."	<td>"
				   .(isset($v['categoria']) ? $v['categoria'] : '') 
				   ."	</td>"
				   ."<tr>";
				}		
			}	  
		}
   
		$tabelaTag .= "</table>";				
	}
	
	// Pega categorias não padronizada
	if(isset($_GET['categoriaNaoPadronizada'])) {
		
		
		$liv = $livroCaixaData->categoriasNaoPadronizadas();	
	
		$tabelaTag = "<table class='tableConteudo' style='width: 100%;'>";
		
		$tabelaTag .= "<tr class='linha0'>"
				   ."	<th style='width:3%; text-align: center;'></th>"
				   ."	<th style='width:10%; text-align: center;'>Código</th>"
				   ."	<th style='width:87%; text-align: center;'>Categoria</th>"
				   ."</tr>";
	
		// verifica se houve retorno. 
		if($liv) {
			
			// for each para cata tabela de usuario.		   
			foreach($liv as $val){
				
				$tabelaTag .= " <tr class='linha0' align='center'>"
				   ."	<td></td>"
				   ."	<td></td>"
				   ."	<td colspan='3'>Código: ".$val['id']." - ".$val['razao_social']."</td>"
				   ." </tr>";
	
				// Verifica itens das tabelas.
				foreach($val['dadosLivroCaixa'] as $v) {
					
					$tabelaTag .= "<tr class='linha1'>"
				   ."	<td></td>"
				   ."	<td>"
				   .(isset($v['id']) ? $v['id'] : '')
				   ."	</td>"
				   ."	<td>"
				   .(isset($v['categoria']) ? $v['categoria'] : '') 
				   ."	</td>"
				   ."<tr>";
				}		
			}	  
		}
   
		$tabelaTag .= "</table>";
	}	
?>

<!--CSS -->
<style>
	
	.linha0 button {
    	background-color: #DDD;
		color: #333;
	}
	
	.linha0 button:hover {
    	background-color: #C00;
		color: #FFF;
	}
	
	body{
		font-family: arial;
	}
	
	table{
		position: relative;	
	}
	
	td {
		font-size:12px;	
	}
	
	.fieldTable{
		display: block;
		float: left;
		width: 100%;
	}

	.linha0 th{
		background: #024a68;
		color: #FFF;
		padding: 10px;
	}	
	
	.linha0 td{
		background: #024a68;
		color: #FFF;
		padding: 10px;
	}	
	
	.linha1 td{
		background: #E5E5E8;
		color: #444;
		padding: 10px;
	}
	
	.bt_excluir{
	    cursor: pointer;
    	position: relative;
    	margin-left: 10px;
	}

	.campoBt{
		clear: both; 
		margin: 0 auto; 
		display: inline;
	}

	.campoMensagem {
		padding:20px;
		font-size:12px;
	}
	
	.caixaMensagem {
		z-index: 9999;	
	}
	
	.areabox {
		position: fixed;
		left: 0;
		top: 0;
		width: 100%;
		height: 100%;
		z-index: 9995;
		background-color:rgba(0, 0, 0, 0.4);
	}
	
	.centroObject {
		height: 100px;
		position: absolute;
		top: 45%;
		left: 50%;
		margin-left: -145;
	}
	
	.filtroCampo span,
	.filtroCampo select,
	.filtroCampo button {
		margin-right: 10px;
	}	
	
	.left{
		text-align: left;
	}
	
	.inputCampo{
		border: 1px solid #d5d5d5;
		border-radius: 3px;
		height: 24px !important;
	}
	
	.campoAlteracao {
		border: 1px solid #d7d7d7;
		border-radius: 5px;
		margin-bottom: 25px;
		padding: 20px 10px;
	}
	
	.campoSize {
		width: 350px;
		display: inline-block;
	}
	
	.campoSizeMeio {
		width: 311px;
		display: inline-block;
	}
	
	.campoSizeFim {
		width: 192px;
		display: inline-block;
	}
	
</style>

<!-- HTML-->

<div class="fieldTable"> 
	<div class="titulo" style="float: left">Busca Categoria nas Tabelas de Livro Caixa</div>
</div>

<div class="fieldTable"> 
	<div class="filtroCampo campoAlteracao campoSize">
        <form method="get" action="/admin/tabelasLivroCaixaCategoria.php" style="display: inline-block;width:356px;">
	        <span>Categorias irregulares: </span> 
            <?php echo $tagSelect; ?>
            <button type="submit">Pesquisa</button>
        </form>
    </div>
    <div class="filtroCampo campoAlteracao campoSizeMeio" style="margin-right: 20px; margin-left: 20px;">
        <form method="get" action="/admin/tabelasLivroCaixaCategoria.php" style="display: inline-block;width:310px;">
	        <span>categoria: </span> 
            <input class="inputCampo" type="categoria" name="categoria" style="width: 110px; margin-right: 7px;" value="<?php echo (isset($_GET['categoria']) ? $_GET['categoria'] : ''); ?>" />
            <button type="submit">Pesquisa</button>
        </form>
    </div>
    
    <div class="filtroCampo campoAlteracao campoSizeFim">
        <form method="get" action="/admin/tabelasLivroCaixaCategoria.php" style="display: inline-block;width:200px;">
            <button type="submit" name="categoriaNaoPadronizada">Categoria não Padronizada</button>
        </form>       
    </div>
</div>

<div class="fieldTable">
	<form id="formTablesAlteracao" method="post" action="alterar_categoria_userlivrocaixa.php">
		<div class="filtroCampo campoAlteracao">
        	<span>Altera categoria para: </span>
            <input type="text" id="categoriaCorrigida" class="inputCampo" name="categoriaCorrigida" />
			<input type="hidden" name="categoriaAntiga" value="<?php echo (isset($_GET['categoria']) ? $_GET['categoria'] : ''); ?>"/>
            <button id="confirmeAlteracao" type="button">Atualizar</button>
        </div>
		<?php echo $tabelaTag; ?>
    </form>
</div>

<!--FIM DO BALLOOM Livro Caixa -->



<!--js -->
<script type="text/javascript" src="../scripts/jquery.min.js"></script>
<script type="text/javascript">

	$('#areabox').hide();
	
	$('.bt_excluir').click(function(){
	
		var checked = false;
		
		$('.checkboxTable').each(function(){
			if($(this).attr('checked')) {
				checked = true;
				return;
			}
		});
		
		if(checked){
			$('#areabox').show();
		} else {
			alert('Nenhum item foi selecionado.');
		}		
	});
	
	$('#btNAO').click(function(){
		$('#areabox').hide();
	});
	
	$('.checkboxSelectAll').click(function(){
		
		if($(this).attr('checked')) {
			$('.checkboxTable').attr("checked", true);
		} else {
			$('.checkboxTable').attr("checked", false);
		}
			
	});
	
	$('#confirmeAlteracao').click(function(){
		
		var checked = false;
		
		$('.checkboxTable').each(function(){
			if($(this).attr('checked')) {
				checked = true;
				return;
			}
		});
		
		if(checked){
			
			if($('#categoriaCorrigida').val() == '') {
				alert('Informe o nome da categoria corrigida');
			} else {
				$('#formTablesAlteracao').submit();
			}
		
		} else {
			alert('Nenhum item foi selecionado.');
		}
	
	});
	
</script>



