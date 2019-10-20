<?php 
	
	/*ini_set('display_errors',1);
	ini_set('display_startup_erros',1);
	error_reporting(E_ALL);*/

	$requestURI = explode("/", $_SERVER['REQUEST_URI']);

	if($requestURI[1] == 'admin') {
		require_once('header.php');
		require_once('../Model/LivroCaixa/LivroCaixaData.php');	
	} else {
		require_once('Model/LivroCaixa/LivroCaixaData.php');	
	}
	
	$allUser = new LivroCaixaData();
	
	$ativo = ""; 
	$inativo = "";
	$demo = ""; 
	$cancelado = "";
	$demoInativo = "";
	$titulo_pag = ""; 
	$tabStatusVazio = "";
   	$tabStatusNaoVazio = "";
	
	$tabelaVazia = false;
	
	if(isset($_GET['tabela']) && !empty($_GET['tabela'])) {
		
		$tabStatusVazio = ($_GET['tabela'] == 'vazio' ? 'selected' : ''); 
		$tabStatusNaoVazio = ($_GET['tabela'] == 'naovazio' ? 'selected' : ''); 

		$tabelaVazia = ($_GET['tabela'] == "naovazio" ? true : false);
	}

	if(isset($_GET['selStatus']) && !empty($_GET['selStatus'])) {
	
		$selStatus = $_GET['selStatus']; 
	
		switch($selStatus) {
			
			case 'ativo':	
				$ativo = 'selected'; 
				$tabelas = $allUser->VerificaTabelasVazias($tabelaVazia, $selStatus);
				break;
				
			case 'inativo':
				$inativo = 'selected';
				$tabelas = $allUser->VerificaTabelasVazias($tabelaVazia, $selStatus);
				break;
				
			case 'demo':	
				$demo = 'selected';
				$tabelas = $allUser->VerificaTabelasVazias($tabelaVazia, $selStatus);
				break;
				
			case 'cancelado':
				$cancelado = 'selected';
				$tabelas = $allUser->VerificaTabelasVazias($tabelaVazia, $selStatus);
				break;				
				
			case 'demoInativo':	
				$demoInativo = 'selected';
				$tabelas = $allUser->VerificaTabelasVazias($tabelaVazia, $selStatus);
				break;
				
			case 'todos':
				$todos = 'selected';
				$tabelas = $allUser->VerificaTabelasVazias($tabelaVazia);
				break;				
		}
	}
	
	$messageTable = '';
	if(isset($_SESSION['tabelasMessage']) && !empty($_SESSION['tabelasMessage'])) {
		$messageTable = $_SESSION['tabelasMessage'];
		unset($_SESSION['tabelasMessage']);
	}

	if($tabelas) {
		
		$numberLinha  = 0;
		
		if($tabelaVazia) {
			
			$titulo_pag = 'Tabelas com Conteúdo';
			
			$tabelaTag = "<table class='tableConteudo linha0' style='width: 966px;'>";
			$tabelaTag .= "<tr class='linha0'><td>Nº</td><td>Códigos</td><td>Razão Social</td><td>status</td></tr>";
			
			foreach($tabelas as $val) {

				$numberLinha += 1;			
	
				$tabelaTag .= "	<tr class='linha1'>"
							."		<td>".$numberLinha."</td>"
							."		<td>".$val['id']."</td>"
							."		<td class ='left' >".utf8_encode($val['razaoSocial'])."</td>"
							."		<td>".$val['statusLog']."</td>"
							."	</tr>";
			}
			$tabelaTag .= "</table>";
			
		} else {
			
			$titulo_pag = 'Tabelas Vazias';
			
			$tabelaTag = "<table class='tableVazio linha0' style='width: 966px;'>";
			$tabelaTag .= "	<tr class='linha0' >"
					."		<td colspan='5' class='left'>"
					."			<input type='checkbox' class='checkboxSelectAll' /> <span>Selecionar Tudo</span>"
					."			<button type='button' class='bt_excluir'>Excluir Itens Selecionados</button>"
					."		</td>"
					."	</tr>";
			$tabelaTag .= "<tr class='linha0'><td></td><td>Nº</td><td>Códigos Empresa</td><td>Razão Social</td><td>status</td></tr>";
			
			foreach($tabelas as $val) {
					
				$numberLinha += 1;
				
				$tabelaTag .= "	<tr class='linha1'>"
							."		<td><input type='checkbox' class='checkboxTable' name='".$val['id']."' value='".$val['id']."' /></td>"
							."		<td>".$numberLinha."</td>"
							."		<td>".$val['id']."</td>"
							."		<td class ='left'>".utf8_encode($val['razaoSocial'])."</td>"
							."		<td>".$val['statusLog']."</td>"
							."	</tr>"; 
			}
				
			$tabelaTag .= "	<tr class='linha0' >"
						."		<td colspan='5' class='left'>"
						."			<input type='checkbox' class='checkboxSelectAll' /> <span>Selecionar Tudo</span>"
						."			<button type='button' class='bt_excluir'>Excluir Itens Selecionados</button>"
						."		</td>"
						."	</tr>";
						
			$tabelaTag .= "</table>"; 
		}
	} else {
			if($tabelaVazia) {
				$titulo_pag = 'Tabelas com Conteúdo';
			} else {
				$titulo_pag = 'Tabelas Vazias';
			}
			
			$tabelaTag = "<table class='tableVazio linha0' style='width: 966px;'>";
			$tabelaTag .= "<tr class='linha0'><td></td><td>Nº</td><td>Códigos Empresa</td><td>Razão Social</td><td>status</td></tr>";
				
			$tabelaTag .= "	<tr class='linha1'>"
						."		<td></td>"
						."		<td></td>"
						."		<td></td>"
						."		<td></td>"
						."		<td></td>"
						."	</tr>"; 
							
			$tabelaTag .= "	<tr class='linha0' >"
						."		<td colspan='5' class='left'></td>"
						."	</tr>";
						
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
	
</style>

<!-- HTML-->

<div class="fieldTable"> 
	<form method="get" action="/admin/tabelasLivroCaixa.php">
    	<div class="titulo" style="float: left"><?php echo $titulo_pag; ?></div>
    	<div class="filtroCampo" style="float: right">
	        <span>Tabela: </span>
   			<select name="tabela" id="selTabela">
      			<option value="vazio" <?php echo $tabStatusVazio; ?> >Vazio</option>
            	<option value="naovazio" <?php echo $tabStatusNaoVazio; ?> >Não Vazio</option>
        	</select>
            <span>Status do Usuário</span>
            <select name="selStatus" id="selStatus">
            	<option value="">Selecione</option>
                <option value="todos" <?php echo $todos; ?>>Todos</option>
                <option value="ativo" <?php echo $ativo; ?> >Ativo</option>
                <option value="inativo" <?php echo $inativo; ?>>Inativo</option>
                <option value="demo" <?php echo $demo; ?>>Demo</option>
                <option value="cancelado" <?php echo $cancelado; ?>>Cancelado</option>
                <option value="demoInativo" <?php echo $demoInativo; ?>>Demo Inativo</option>
            </select>
            <button type="submit">Pesquisa</button>
    	</div>        
    </form>
</div>

<div class="fieldTable">
	<form id="formTablesExcluir" method="post" action="/admin/excluir_tabela_userlivrocaixa.php">
		<?php echo $tabelaTag; ?>
    </form>
</div>

<div id="areabox" class="areabox" style="display:none;">
   
    <div class="bubble only-box centroObject caixaMensagem"  id="aviso-livro-caixa">
        <div class="campoMensagem">
            Deseja realmente excluir?<br><br>            
            <div class="campoBt">
            	<center>
                	<div id="divCarregando2" class="divCarregando2" style="margin-top:10px; text-align:center; display: none;">
                    	<img src="../images/loading.gif" width="16" height="16">
                 	</div>
                
                    <button id="confirmeExclusao" type="button" idPagto="" idSocio="">Sim</button>
                    <button id="btNAO" type="button">Não</button>
                </center>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
    
    
    
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
	
	$('#confirmeExclusao').click(function(){
		
		$('#btNAO').hide();
		$('#confirmeExclusao').hide();
		
		$('#divCarregando2').show();
		
		$('#formTablesExcluir').submit();
	});
	
</script>



