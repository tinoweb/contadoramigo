<?php 

//	ini_set('display_errors',1);
//	ini_set('display_startup_erros',1);
//	error_reporting(E_ALL);

	require_once('header_restrita.php'); 
	require_once('Model/Categoria/CategoriasData.php');

	// Pega as Categorias.
	$categoriaData = new CategoriasData();
	
	$categorias = $categoriaData->pegaTodosCategorias();
	
	$optionEntrada1 = '';
	
	$optionSaida1 = '';
	
	if($categorias) {
		foreach( $categorias as $val ){
			if($val->getCategoriaTipo() == 'E'){
				if($val->getCategoriaAtivo() == 'A' || $val->getCategoriaAtivo() == 'I' && $_SESSION['id_userSecaoMultiplo'] == '9' ) {
					$optionEntrada1 .= '{text: "'.$val->getCategoriaNome().'", value: "'.$val->getCategoriaNome().'"},';
				}
			} else {
				if($val->getCategoriaAtivo() == 'A' || $val->getCategoriaAtivo() == 'I' && $_SESSION['id_userSecaoMultiplo'] == '9' ) {
					$optionSaida1 .='{text: "'.$val->getCategoriaNome().'", value: "'.$val->getCategoriaNome().'"},';
				}
			}
		}
	}
?>


<? // echo $_SESSION['id_empresaSecao']; ?>

<script>
function alterarPeriodo() {
	dataInicio = document.getElementById('DataInicio').value;
	anoInicio = dataInicio.substr(6,4);
	mesInicio = dataInicio.substr(3,2);
	diaInicio = dataInicio.substr(0,2);
	dataFim = document.getElementById('DataFim').value;
	anoFim = dataFim.substr(6,4);
	mesFim = dataFim.substr(3,2);
	diaFim = dataFim.substr(0,2);

	window.location='livros_caixa_movimentacao.php?dataInicio='+anoInicio+'-'+mesInicio+'-'+diaInicio+'&dataFim='+anoFim+'-'+mesFim+'-'+diaFim+'&editar=<?=$_GET["editar"]?>';
}

function formImprimir() {
//	dataInicio = document.getElementById('imprimirDataInicio').value;
dataInicio = document.getElementById('DataInicio').value;
anoInicio = dataInicio.substr(6,4);
mesInicio = dataInicio.substr(3,2);
diaInicio = dataInicio.substr(0,2);
//	dataFim = document.getElementById('imprimirDataFim').value;
dataFim = document.getElementById('DataFim').value;
anoFim = dataFim.substr(6,4);
mesFim = dataFim.substr(3,2);
diaFim = dataFim.substr(0,2);

window.open('livros_caixa_movimentacao_impressao.php?dataInicio='+anoInicio+'-'+mesInicio+'-'+diaInicio+'&dataFim='+anoFim+'-'+mesFim+'-'+diaFim);

}

function formExcel() {
//	dataInicio = document.getElementById('imprimirDataInicio').value;
dataInicio = document.getElementById('DataInicio').value;
anoInicio = dataInicio.substr(6,4);
mesInicio = dataInicio.substr(3,2);
diaInicio = dataInicio.substr(0,2);
//	dataFim = document.getElementById('imprimirDataFim').value;
dataFim = document.getElementById('DataFim').value;
anoFim = dataFim.substr(6,4);
mesFim = dataFim.substr(3,2);
diaFim = dataFim.substr(0,2);

window.open('livros_caixa_movimentacao_excel.php?dataInicio='+anoInicio+'-'+mesInicio+'-'+diaInicio+'&dataFim='+anoFim+'-'+mesFim+'-'+diaFim);

}



function formCSV() {
//	dataInicio = document.getElementById('imprimirDataInicio').value;
dataInicio = document.getElementById('DataInicio').value;
anoInicio = dataInicio.substr(6,4);
mesInicio = dataInicio.substr(3,2);
diaInicio = dataInicio.substr(0,2);
//	dataFim = document.getElementById('imprimirDataFim').value;
dataFim = document.getElementById('DataFim').value;
anoFim = dataFim.substr(6,4);
mesFim = dataFim.substr(3,2);
diaFim = dataFim.substr(0,2);

window.open('livros_caixa_movimentacao_csv.php?dataInicio='+anoInicio+'-'+mesInicio+'-'+diaInicio+'&dataFim='+anoFim+'-'+mesFim+'-'+diaFim);

}


function validaDat(valor) {
	var date=valor;
	var ardt=new Array;
	var ExpReg=new RegExp("(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/[12][0-9]{3}");
	ardt=date.split("/");
	erro=false;
	if ( date.search(ExpReg)==-1){
		erro = true;
	}
	else if (((parseInt(ardt[1])==4)||(parseInt(ardt[1])==6)||(parseInt(ardt[1])==9)||(parseInt(ardt[1])==11))&&(ardt[0]>30))
		erro = true;
	else if ( parseInt(ardt[1])==2) {
		if ((parseInt(ardt[0])>28)&&((ardt[2]%4)!=0))
			erro = true;
		if ((parseInt(ardt[0])>29)&&((ardt[2]%4)==0))
			erro = true;
	} 
	if (ardt[2] < 1970 || ardt[2] > 2030)
		erro = true;

	return erro;
}

$(document).ready(function(e) {
	
	
	$('.mostraDocs').bind('click',function(){
		var indice = ($('.mostraDocs').index($(this)));
		$('.listaDocs').eq(indice).toggle();
	});
	
	
	$('#btnSubmit').click(function(e){
		e.preventDefault();
		if($('#txtData').val() == ''){
			alert('Preencha a data!');
			$('#txtData').focus();
			return false;
		}else{
			if(validaDat($('#txtData').val())){
				alert('Data inválida!');
				$('#txtData').focus();
				return false;
			}
		}
		if($('#txtValor').val() == ''){
			alert('Preencha o valor!');
			$('#txtValor').focus();
			return false;
		}
		if($('#selTipoLancamento').val() == ''){
			alert('Selecione um tipo de lançamento!');
			$('#selTipoLancamento').focus();
			return false;
		}
		if($('#selCategoria').val() == ''){
			alert('Selecione uma categoria para o lançamento!');
			$('#selCategoria').focus();
			return false;
		}
		//Vefifica se é pagamento, se nao for apenas insere o dados no livro caixa
		if( verificarPagamentos() )
			$('#form_livro').submit();
		return;
		
	});
	//funcao que verifica se o item inserido é um pagamento, caso seja vaz as verificações para garantir que o pagamento esteja cadastrado em pagamentos, se nao avisa o usuario da inconsistencia
	function verificarPagamentos(){
		var cat = $('#selCategoria').val();
		var tipo = $("#selTipoLancamento").val();
		if( ( cat === 'Estagiários' || cat === 'Pgto. a autônomos e fornecedores' /*|| cat === 'Pagto. de salários'*/ || cat === 'Pró-Labore' ) && tipo === 'saida' ){
			if($('#nome_pagamentos').val() == ''){
				alert('Selecione o '+cat+' para o lançamento!');
				$('#nome_pagamentos').focus();
				return false;
			}
			verificarPagamentoCadastrado();
			return false;
		}
		else
			return true;
	}
	//Funcao que verifica se o pagamento em questao ja esta cadastrado nos pagamentos sem um correspondente no livro caixa
	function verificarPagamentoCadastrado(){
		var categoria = $('#selCategoria').val();
		var data = $('#txtData').val();
		var valor  = $('#txtValor').val();
		var beneficiario  = $('#nome_pagamentos').val();
		$.ajax({
			url:'ajax.php'
			, data: 'varificarDuplicidadePagamentos=varificarDuplicidadePagamentos&categoria='+categoria+'&data='+data+'&valor='+valor+'&beneficiario='+beneficiario
			, type: 'post'
			, async: true
			, cache:false
			, success: function(retorno){
				// console.log(retorno);
			    obj = JSON.parse(retorno);
			    //Se retorna erro, avisa o usuario exibindo a mensagem retornada
			    if( obj[0] === 'erro' ){
			    	alert(obj[1]);
			    	if( confirm("Dejesa cadastrar no livro caixa?") === true )//Se o usuario insitir em cadastrar o item, permite a ação
			    		$('#form_livro').submit();
			    }
			    else{
			    	$('#form_livro').submit();
			    }
			}
		});

	}

	$('#selTipoLancamento').change(function(){
		var valor = $(this).val();

		if(valor == 'saida'){
			// ALTERANDO DESCRICOES AQUI ALTERAR NOS PAGAMENTOS
			var options = [
			{text: "selecione", value: ""},
			<?php echo $optionSaida1;?>
			];
		}else{
			var options = [
				{text: "selecione", value: ""},
				<?php
					$sql_clientes = "SELECT DISTINCT apelido FROM dados_clientes WHERE id_login = " . $_SESSION['id_empresaSecao'] . " AND status = 'A' ORDER BY apelido";
					$rs_clientes = mysql_query($sql_clientes);
					if(mysql_num_rows($rs_clientes) > 0){
						while($dados_clientes = mysql_fetch_array($rs_clientes)){
							echo '{text: "' . $dados_clientes['apelido'] . '", value: "' . $dados_clientes['apelido'] . '"},';
						}
					}
					
					echo $optionEntrada1;
				?>
			];
		}
		
			$('#selCategoria').replaceOptions(options);
		});


(function($, window) {
	$.fn.replaceOptions = function(options) {
		var self, $option;
		
		this.empty();
		self = this;
		
		$.each(options, function(index, option) {
			$option = $("<option></option>")
			.attr("value", option.value)
			.text(option.text);
			self.append($option);
		});
	};
})(jQuery, window);


<?php 

		//INICIO - Alteração dia 06/05/2016 - Uploads de arquivos junto com o lancamento
		//Arquivos: livros_caixa_movimentacao.php, livros_caixa_movimentacao_inserir.php, livros_caixa_movimentacao_gravar.php, livros_caixa_movimentacao_excluir.php

		// - Essa trecho de codigo trabalha para setar duas variaveis globais para definir quando é exlusao de arquivo  e diferenciar a mensagem que aparece para o user

?>

var global_delete_imagem = false;
var global_arquivo = 0;

<?php //FIM ?>

$('.excluirPagamento').bind('click',function(e){
	e.preventDefault();

	<?php 

				//INICIO - Alteração dia 06/05/2016 - Uploads de arquivos junto com o lancamento
				//Arquivos: livros_caixa_movimentacao.php, livros_caixa_movimentacao_inserir.php, livros_caixa_movimentacao_gravar.php, livros_caixa_movimentacao_excluir.php

				// - Essa trecho de codigo trabalha para trocar a mensagem e abrir o box de confirmação de exclusao

	?>
	var imagem = $(this).attr("imagem");

	if( imagem === 'sim' ){

		var arquivo = $(this).attr("arquivo");

		var mensagem = "Você tem certeza que deseja excluir este arquivo?<br>'<b>"+arquivo+"<b>'";
		$('#aviso-delete-livro-caixa').fadeOut(100);
		$('#aviso-delete-livro-caixa').find('#mensagemDELETEPagamento').html(mensagem);
		$('#aviso-delete-livro-caixa').css('top',($(this).offset().top - 203) + 'px').fadeIn(100);
		global_delete_imagem = true;
		global_arquivo = $(this).attr("linha");
		return;
	}
	<?php //FIM ?>

	

	var idPagto = $(this).attr("linha");
	var idLivroCaixa = $(this).attr("pagto");
	var txtCategoria = $(this).attr("cat");

	var mensagem = "Você tem certeza que deseja excluir este lançamento?";

	$('#aviso-delete-livro-caixa').fadeOut(100);

	if(idLivroCaixa != 0 && idLivroCaixa != ''){
		mensagem = "Deseja excluir também da folha de pagamentos " + txtCategoria + "?";
	}

	$('#aviso-delete-livro-caixa').find('#mensagemDELETEPagamento').html(mensagem);

	$('#btSIMDeletePagamentoLivroCaixa').attr("linha",idPagto);
	$('#btSIMDeletePagamentoLivroCaixa').attr("pagto",idLivroCaixa);

	$('#btNAODeletePagamentoLivroCaixa').attr("linha",idPagto);
	$('#btNAODeletePagamentoLivroCaixa').attr("pagto",idLivroCaixa);

	$('#aviso-delete-livro-caixa').css('top',($(this).offset().top - 203) + 'px').fadeIn(100);
	
	
});


$('#btSIMDeletePagamentoLivroCaixa').bind('click',function(){

	<?php 

				//INICIO - Alteração dia 06/05/2016 - Uploads de arquivos junto com o lancamento
				//Arquivos: livros_caixa_movimentacao.php, livros_caixa_movimentacao_inserir.php, livros_caixa_movimentacao_gravar.php, livros_caixa_movimentacao_excluir.php

				// - Essa trecho de codigo trabalha para redirecionar a pagina para a exclusao do arquivo quando clicado em sim

	?>
	if( global_delete_imagem === true ){
		location.href='livros_caixa_movimentacao_excluir.php?acao=excluirArquivo&editar=<?php echo $_GET["editar"] ?>&dataInicio=<?php echo $_GET["dataInicio"] ?>&dataFim=<?php echo $_GET["dataFim"] ?>&id_arquivo='+global_arquivo;
		return;
	}
	<?php //Fim ?>
	
	var idPagto = $(this).attr("linha");
	var idLivroCaixa = $(this).attr("pagto");
	
	if(idLivroCaixa != 0 && idLivroCaixa != ''){
		location.href='livros_caixa_movimentacao_excluir.php?' + "linha=" + idPagto + "&del_pagto";
	}else{
		location.href='livros_caixa_movimentacao_excluir.php?' + "linha=" + idPagto;
	}
});

$('#btNAODeletePagamentoLivroCaixa').bind('click',function(){


	<?php 

				//INICIO - Alteração dia 06/05/2016 - Uploads de arquivos junto com o lancamento
				//Arquivos: livros_caixa_movimentacao.php, livros_caixa_movimentacao_inserir.php, livros_caixa_movimentacao_gravar.php, livros_caixa_movimentacao_excluir.php

				// - Essa trecho de codigo trabalha para cancelar a exclusao do arquivo, quando clica em não

	?>
	if(global_delete_imagem === true){
		global_delete_imagem = false;
		global_arquivo = 0;
		$('#aviso-delete-livro-caixa').fadeOut(100);
		return;
	}

	<?php //FIM ?>
	
	var idPagto = $(this).attr("linha");
	var idLivroCaixa = $(this).attr("pagto");

	if(idLivroCaixa != 0 && idLivroCaixa != ''){
		location.href='livros_caixa_movimentacao_excluir.php?' + "linha=" + idPagto;
	}else{
		$('#aviso-delete-livro-caixa').fadeOut(100);
	}
});



});

</script>


<div style="position: relative;">
	
	<!--BALLOM excluir pagamento-->
	<div class="bubble only-box" style="display: none; padding:0; position:absolute; top: -50px; left:50%; margin-left: -415px; z-index: 9990;" id="aviso-delete-livro-caixa">
		<div style="padding:20px; font-size:12px;">
			<div id="mensagemDELETEPagamento"></div><br>
			<div style="clear: both; margin: 0 auto; display: inline;">
				<center>
					<button id="btSIMDeletePagamentoLivroCaixa" type="button" linha="" pagto="">Sim</button>
					<button id="btNAODeletePagamentoLivroCaixa" type="button" linha="" pagto="">Não</button>
				</center>
			</div>
			<div style="clear: both;"></div>
		</div>
	</div>
	<!--FIM DO BALLOOM excluir pagamento -->
	
	
</div>

<div class="principal">

	
<h1 style="float: left">Livro Caixa</h1>
<div class="tituloAzul" style="float:right">
<a href="#" class="btReAbrirBox" div="video"  style="text-decoration: none"><i class="fa fa-file-video-o" aria-hidden="true"></i> Vídeo de instruções</a>
</div>
<div style="clear:both; height: 20px"></div>

		<!--video de instrucoes-->
		<div id="video" class="box_visualizacao check_visualizacao x_visualizacao" style="display:none; border:1px solid #CCCCCC; background-color:#fff; width:680px; position:absolute; left:50%; margin-left:-340px; top:0px">
			<div style="padding:20px">
				<div class="titulo" style="text-align:left; margin-bottom:10px">Instruções</div>
				<video id="video1" width="640" height="360" controls>
					<source src="videos/livro_caixa.mp4" type='video/mp4'> 
					</video>
				</div>
			</div>
			<script>
				$( document ).ready(function() {
					if( $(".check_caixa_visualizacao").attr("checked") === false ){
						$("#video").show();
						$(".btReAbrirBox").hide();
					}
				});
			</script>
				<!--fim do video de instrucoesa-->

				<div style="margin-bottom:20px">Utilize este aplicativo de livro-caixa para manter escriturada toda a movimentação financeira da empresa (esta é uma exigência legal). Dica: cadastre primeiro seus principais clientes em <a href="meus_dados_clientes.php">Meus Dados/Cadastro de Clientes</a>, assim você poderá separar suas entradas e os <a href="livros_caixa_graficos.php">gráficos</a> exibirão a receita do período dividida por cliente.</div>
				<div style="clear:both"></div>



				<?php
	
				$mostra_categoria = false;

				$sqlEstrutura = "SHOW COLUMNS FROM user_" . $_SESSION['id_empresaSecao'] . "_livro_caixa";

//echo $sqlEstrutura;

				$rsEstrutura = mysql_query($sqlEstrutura);
//echo $descricao_colunas_tabela['Field'];
				while($descricao_colunas_tabela = mysql_fetch_array($rsEstrutura)){
					if($descricao_colunas_tabela['Field'] == "categoria"){
						$mostra_categoria = true;
						break;
					}
				}

				if(!isset($_SESSION['dataInicioLivroCaixa'])){
					
					$dataInicio = $_GET["dataInicio"];
					
					//Query para definir o filtro.
					$sql = "SELECT data FROM user_" . $_SESSION['id_empresaSecao'] . "_livro_caixa WHERE data = (SELECT data FROM user_" . $_SESSION['id_empresaSecao'] . "_livro_caixa WHERE data < DATE(NOW()) ORDER BY data DESC LIMIT 50,1) LIMIT 1";
					
					$result = mysql_query($sql)
					or die (mysql_error());
					
					$rowDate=mysql_fetch_array($result);
					
					if(isset($rowDate['data']) && !empty($rowDate['data'])) {
						$dataInicio = $rowDate['data'];						
					} else if ($dataInicio == "") {
						$dataInicio = date('Y-m-d',mktime(0,0,0,date('m'),date('d')-30,date('Y')));
					}
					
				}else{
					if($_GET["dataInicio"] != $_SESSION["dataInicioLivroCaixa"] && isset($_GET["dataInicio"])){
						$dataInicio = $_GET["dataInicio"];
					}else{
						$dataInicio = $_SESSION["dataInicioLivroCaixa"];
					}
				}

				if(!isset($_SESSION['dataFimLivroCaixa'])){
					$dataFim = $_GET["dataFim"];
					
					if ($dataFim == "") {
						$dataFim = date("Y-m-d");
					}
					
				}else{
					if($_GET["dataFim"] != $_SESSION["dataFimLivroCaixa"] && isset($_GET["dataFim"])){
						$dataFim = $_GET["dataFim"];
					}else{
						$dataFim = $_SESSION["dataFimLivroCaixa"];
					}
				}

				$_SESSION['dataInicioLivroCaixa'] = $dataInicio;
				$_SESSION['dataFimLivroCaixa'] = $dataFim;


				$editar = $_GET["editar"];

				if($editar == "") { ?>
				<div class="tituloVermelho" style="margin-bottom:10px">Novo Lançamento</div>
				<div style="margin-bottom:20px">
					<form method="post" id="form_livro" action="livros_caixa_movimentacao_inserir.php" accept-charset="utf-8" enctype="multipart/form-data">
						<table border="0" cellspacing="2" cellpadding="4" width="966" style="font-size: 11px;">
							<thead>
								<tr>
									<th width="80" style="font-size: 12px;">Data</th>
									<th width="100" style="font-size: 12px;">Valor</th>
									<th width="87" style="font-size: 12px;">Entrada/Saída</th>
									<th width="190" style="font-size: 12px;">Categoria</th>
									<th width="70" style="font-size: 12px;">Doc nº</th>
									<th width="360" style="font-size: 12px;"><span id="nomeCampo_descricao">Descrição</span><span style="display:none" id="nomeCampo_beneficiario">Beneficiário</span></th>
								</tr>
							</thead>
							<tr>
								<td class="td_calendario"><input name="txtData" id="txtData" type="text" value="" maxlength="10" style="width:80px;font-size:12px;" class="campoData" /></td>
								<td class="td_calendario"><input name="txtValor" id="txtValor" type="text" style="width:100px;font-size:12px !important;" class="current" /></td>
								<td class="td_calendario">
									<select name="selTipoLancamento" id="selTipoLancamento" style="font-size:12px !important;">
										<option value="">selecione</option>
										<option value="entrada">entrada</option>
										<option value="saida">saída</option>
									</select>
								</td>
								<td class="td_calendario">
									<select name="selCategoria" id="selCategoria" style="width:190px;font-size:12px !important;">
										<option value="">selecione</option>
									</select>
								</td>   
								<td class="td_calendario">
									<input name="txtDocumentoNumero" id="txtDocumentoNumero" type="text" value="" maxlength="256" style="width:70px;font-size:12px !important;" />
								</td>
								<td class="td_calendario">
									<div id="opcao_pagamento" status="fechado" style="display:none;">
										Selecione o <span class="tipo"></span>
										<select id="nome_pagamentos" name="nome_pagamento" style="margin-left:5px;max-width:160px;">
										 	
										</select>
									</div>
									<div id="opcao_pagamento_erro" status="fechado" style="display:none;">
										
									</div>
									<div id="campoDescricao">
										<input name="txtDescricao" id="txtDescricao" type="text" value="" maxlength="70" style="width:350px;font-size:12px !important; margin-top:5px; margin-bottom:5px" />
									</div>
								</td>
							</tr>       
							<!-- Opção para quando o tipo é entrada e a categoria e emprestimos -->
							<!-- <tr >
								<td colspan="6" class="td_calendario" style="font-size: 12px;display:none" id="opcao_emprestimo" status="fechado">
									Informe o prazo de carência do empréstimo: 
									<select name="" style="margin-left:5px;width:40px">
									 	<option value=""></option>
									 	<option value="1">1</option>
									 	<option value="2">2</option>
									 	<option value="3">3</option>
									 	<option value="4">4</option>
									 </select> Meses
								</td>
							</tr> -->
						</table>
						<table border="0" cellspacing="2" cellpadding="4" style="width: 100%;margin-bottom: 20px;margin-top: -2px">
							<tbody>
								<tr colspan="6" style="width: 100%;">
									<td colspan="3" class="td_calendario" style="font-size: 12px;display:none" id="opcao_emprestimo" status="fechado">
										Informe o prazo de carência do empréstimo: 
										<select name="prazo_carencia" style="margin-left:5px;width:40px">
										 	<option value=""></option>
										 	<?php 
									 			for ($i=1; $i < 25; $i++) { 
								 					echo '<option value="'.$i.'" >'.$i.'</option>';
									 			}
									 		?>
										 </select> Meses
									</td>
								</tr>
								<tr colspan="6" style="width: 100%;">
									<td class="td_calendario" colspan="3"style="padding-bottom: 8px;padding-top:8px;">
										Anexar Comprovante(s): 
										<input type="file" name="anexos_doc[]" value="" multiple style="margin-left:10px;margin-right:10px;">
										(Max 1Mb)
									</td>
								</tr>
							</tbody>
						</table>
						<input type="hidden" name="hidID" id="hidID" value="<?=$_SESSION['id_empresaSecao']?>" />
						<center>
							<input name="incluir" type="submit" value="Incluir lançamento" id="btnSubmit" />
						</center>
					</form>
				</div>
				<?php } 
				else { 

					$sql = "SELECT * FROM user_" . $_SESSION['id_empresaSecao'] . "_livro_caixa WHERE id='$editar'";
					$resultado = mysql_query($sql)
					or die (mysql_error());
					
					$linha=mysql_fetch_array($resultado);
					
					function selected( $value, $prev ){
						return $value==$prev ? ' selected="selected"' : ''; 
					};
					?>
					<div class="tituloVermelho" style="margin-bottom:10px">Editar Lançamento</div>
					<div style="margin-bottom:20px">
						<form method="post" id="form_livro" action="livros_caixa_movimentacao_gravar.php" accept-charset="utf-8" enctype="multipart/form-data">
							<table border="0" cellspacing="2" cellpadding="4" width="966" style="font-size: 11px;">
								<thead>
									<tr>
										<th width="70" style="font-size: 12px;">Data</th>
										<th width="100" style="font-size: 12px;">Valor</th>
										<th width="87" style="font-size: 12px;">Entrada/Saída</th>
										<th width="190" style="font-size: 12px;">Categoria</th>
										<th width="70" style="font-size: 12px;">Doc nº</th>
										<th width="370" style="font-size: 12px;">Descrição</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="td_calendario">
											<input name="txtData" id="txtData" type="text" value="<?=date('d/m/Y', strtotime($linha["data"]))?>" maxlength="10" style="width:70px;font-size:12px;" class="campoData" /></td>
											<td class="td_calendario"><input name="txtValor" id="txtValor" type="text" value="<?php 
											if ($linha["saida"] != 0) {
										//	echo str_replace(".",",",$linha["saida"]);
												echo number_format($linha["saida"],2,',','.');
												$lancamento = 'saida';
											}
											else {
									//	echo str_replace(".",",",$linha["entrada"]);
												echo number_format($linha["entrada"],2,',','.');
												$lancamento = 'entrada';
											}
											?>" style="width:100px;font-size:12px !important;" class="current" /></td>
											<td class="td_calendario">
												<select name="selTipoLancamento" id="selTipoLancamento" style="font-size:12px !important;">
													<option value="">selecione</option>
													<option value="entrada" <?php echo selected( 'entrada', $lancamento ); ?>>entrada</option>
													<option value="saida" <?php echo selected( 'saida', $lancamento ); ?>>saída</option>
												</select>
											</td>
											<td class="td_calendario">
												<? if(isset($linha["categoria"])){ ?>
												<select name="selCategoria" id="selCategoria" style="max-width:180px;">
													<?php
														if($lancamento == "saida"){
															
															$tagOption = "<option value=''>Selecione</option>";
															
															if($categorias) {
																foreach( $categorias as $val ){
																	if($val->getCategoriaTipo() == 'S') {
																		if($val->getCategoriaNome() == $linha["categoria"] ) {
																			if($val->getCategoriaAtivo() == 'A' || $val->getCategoriaAtivo() == 'I' && $_SESSION['id_userSecaoMultiplo'] == '9' ) {
																				$tagOption .= "<option value='".$val->getCategoriaNome()."'
																				selected>".$val->getCategoriaNome()."</option>";
																			}
																		} else {
																			if($val->getCategoriaAtivo() == 'A' || $val->getCategoriaAtivo() == 'I' && $_SESSION['id_userSecaoMultiplo'] == '9' ) {
																				$tagOption .= "<option value='".$val->getCategoriaNome()."'>".$val->getCategoriaNome()."</option>";
																			}
																		}
																	}
																}
	
																echo $tagOption;
															}
														} else { 
													
															$tagOption = "<option value=''>Selecione</option>";
															
															$sql_clientes = "SELECT DISTINCT apelido FROM dados_clientes WHERE id_login = " . $_SESSION['id_empresaSecao'] . " AND status = 'A' ORDER BY apelido";
															$rs_clientes = mysql_query($sql_clientes);
															if(mysql_num_rows($rs_clientes) > 0){
																while($dados_clientes = mysql_fetch_array($rs_clientes)){
																	$tagOption .='<option value="' . $dados_clientes['apelido'] . '" ' . selected( $dados_clientes['apelido'], $linha["categoria"] ) . '>' . $dados_clientes['apelido'] . '</option>';
																}
															}
			
															if($categorias) {
																
																foreach( $categorias as $val ){
																	if($val->getCategoriaTipo() == 'E') {
																		if($val->getCategoriaNome() == $linha["categoria"] ) {
																			if($val->getCategoriaAtivo() == 'A' || $val->getCategoriaAtivo() == 'I' && $_SESSION['id_userSecaoMultiplo'] == '9' ) {
																				$tagOption .= "<option value='".$val->getCategoriaNome()."' selected>".$val->getCategoriaNome()."</option>";
																			}
																		} else {
																			if($val->getCategoriaAtivo() == 'A' || $val->getCategoriaAtivo() == 'I' && $_SESSION['id_userSecaoMultiplo'] == '9' ) {
																				$tagOption .= "<option value='".$val->getCategoriaNome()."'>".$val->getCategoriaNome()."</option>";
																			}
																		}
																	}
																}
			
																echo $tagOption;
															}												
														 } ?>
												</select>
												
												
												<? } ?>
											</td>   
											<td class="td_calendario"><input name="txtDocumentoNumero" id="txtDocumentoNumero" type="text" value="<?=$linha["documento_numero"]?>" maxlength="256" style="width:70px;font-size:12px !important;" /></td>
											<td class="td_calendario">
												<?php if( $linha["categoria"] == 'Pró-Labore' || $linha["categoria"] == 'Estagiários' || $linha["categoria"] == 'Pgto. a autônomos e fornecedores' || $linha["categoria"] == 'Pagto. de salários' ){ ?>
												<?php 
												
													$string = '';
													if( $linha["categoria"] == 'Estagiários' ){
														$consulta = mysql_query("SELECT * FROM estagiarios WHERE id_login = '".$_SESSION['id_empresaSecao']."' ");
														while( $objeto_consulta = mysql_fetch_array($consulta) )
															$string .= '<option '.selected($objeto_consulta['id'],$linha["descricao"]).' value="'.$objeto_consulta['id'].'">'.$objeto_consulta['nome'].'</option>';
													}
													else if( $linha["categoria"] == 'Pgto. a autônomos e fornecedores' ){
														$consulta = mysql_query("SELECT id,id_login,nome,cpf FROM dados_autonomos WHERE  dados_autonomos.id_login = '".$_SESSION['id_empresaSecao']."' UNION SELECT id,id_login,nome,cnpj FROM dados_pj WHERE id = '".$descricao."' AND dados_pj.id_login = '".$_SESSION['id_empresaSecao']."' order by nome");
														while( $objeto_consulta = mysql_fetch_array($consulta) )
															$string .= '<option '.selected($objeto_consulta['id'],$linha["descricao"]).' value="'.$objeto_consulta['id'].'">'.$objeto_consulta['nome'].'</option>';
													}
													else if( $linha["categoria"] == 'Pagto. de salários' ){
														$consulta = mysql_query("SELECT * FROM dados_do_funcionario WHERE id = '".$_SESSION['id_empresaSecao']."' ");
														while( $objeto_consulta = mysql_fetch_array($consulta) )
															$string .= '<option '.selected($objeto_consulta['id'],$linha["descricao"]).' value="'.$objeto_consulta['id'].'">'.$objeto_consulta['nome'].'</option>';
													}
													else if( $linha["categoria"] == 'Pró-Labore' ){
														$consulta = mysql_query("SELECT * FROM dados_do_responsavel WHERE id = '".$_SESSION['id_empresaSecao']."' ");
														while( $objeto_consulta = mysql_fetch_array($consulta) )
															$string .= '<option '.selected($objeto_consulta['id'],$linha["descricao"]).' value="'.$objeto_consulta['nome'].'">'.$objeto_consulta['nome'].'</option>';
													}
												?>
												<div id="opcao_pagamento" status="fechado" style="display:block;">
													Selecione o <span class="tipo"></span>
													<select id="nome_pagamentos" name="nome_pagamento" style="margin-left:5px;max-width:160px;">
													 	<?php echo $string; ?>
													</select>
												</div>
												<div id="opcao_pagamento_erro" status="fechado" style="display:none;">
										
												</div>
												<div id="campoDescricao" style="display:none">
													<input name="txtDescricao" id="txtDescricao" type="text" value="" maxlength="70" style="width:350px;font-size:12px !important; margin-top:5px; margin-bottom:5px" />
												</div>
												<?php }else{ ?>
													<input name="txtDescricao" id="txtDescricao" type="text" value="<?=$linha["descricao"]?>" maxlength="75" style="width:370px;font-size:12px !important; margin-top:5px; margin-bottom:5px" />
												<?php } ?>
											</td>
										</tr>
									</tbody>
								</table>
								<?php 

								//INICIO - Alteração dia 06/05/2016 - Uploads de arquivos junto com o lancamento
								//Arquivos: livros_caixa_movimentacao.php, livros_caixa_movimentacao_inserir.php, livros_caixa_movimentacao_gravar.php, livros_caixa_movimentacao_excluir.php

								// - Essa trecho de codigo trabalha para realizar o upload do arquivo para o servidor

								?>
								<table <table border="0" cellspacing="2" cellpadding="4"  style="width: 100%;margin-bottom: 20px;margin-top: -2px">
									<tbody>
										<tr style="width: 100%;">
											<?php 
												function isEmprestimo($prazo_carencia){
													if( $prazo_carencia != '' )
														echo 'block';
													else 
														echo 'none';
												}
												$consulta = mysql_query ("SELECT * FROM `user_".$_SESSION['id_empresaSecao']."_livro_caixa_emprestimos` WHERE id_item = '".$linha['id']."' ");
												$objeto_consulta = mysql_fetch_array($consulta);
												$prazo_carencia = $objeto_consulta['meses'];
											?>
											<td colspan="6" class="td_calendario" style="padding-bottom: 8px;padding-top:8px;font-size: 12px; display: <?php isEmprestimo($prazo_carencia); ?>;" id="opcao_emprestimo" status="aberto">
												Informe o prazo de carência do empréstimo: <?php isEmprestimo($linha); ?>
												<select name="prazo_carencia" style="margin-left:5px;width:40px">
												 	<option value="" <?php selected($prazo_carencia,'') ?> ></option>
												 	<?php 
												 		for ($i=1; $i < 25; $i++) { 
											 				echo '<option value="'.$i.'" '.selected($prazo_carencia,$i).' >'.$i.'</option>';
												 		}
												 	?>
												 	<!-- <option value="1">1</option> -->
												 	<!-- <option value="2">2</option> -->
												 	<!-- <option value="3">3</option> -->
												 	<!-- <option value="4">4</option> -->
												 </select> Meses
											</td>
										</tr>
										<tr style="width: 100%;">

											<td class="td_calendario" style="padding-bottom: 8px;padding-top:8px;">
												<div style="float: left;margin-right: 20px; height: 25px;">
													<input type="file" name="anexos_doc[]" value="" multiple><br>
												</div>
												

												<?php 
 		   					//Trerco que busca os arquivos enviados para o lancamento e exibe cada um com  a opções de exclusao
												$consulta = mysql_query("SELECT * FROM comprovantes WHERE id_lancamento = '".$linha["id"]."' AND id_user = '".$_SESSION["id_empresaSecao"]."' ");
												
												while( $objeto=mysql_fetch_array($consulta) ){ 
													
													?>
													
													<div style="float: left;margin-right: 20px; height: 25px;">
														<a href="#" class="excluirPagamento" imagem="sim" arquivo="<?php echo $objeto["nome"]; ?>" linha="<?=$objeto["id"];?>" pagto="" cat="" title="Excluir">
															<i class="fa fa-times" aria-hidden="true" style="color:red;font-size:15px;"></i> 
														</a>
														<?php echo $objeto['nome']; ?>
													</div>
													
													
													<?php 
												} 
												?>
											</td>
										</tr>
										
										
									</tbody></table>
									<?php //FIM ?>
									<center>
										<input type="hidden" name="hidID" id="hidID" value="<?=$_SESSION['id_empresaSecao']?>" />
										<input type="hidden" name="hidLinha" id="hidLinha" value="<?=$linha["id"]?>"  />
										<input name="editar" type="submit" value="Editar lançamento" id="btnSubmit" />
										<input type="button" value="cancelar" onClick="location.href='livros_caixa_movimentacao.php'" />
									</center>
								</form>
							</div>

							<?php } ?><br />

							<div class="tituloVermelho" style="margin-bottom:10px">Movimentação</div>
							<form method="post" style="display:inline" action="Javascript:alterarPeriodo()">
								<div style="float:left">Período de:  <input name="DataInicio" id="DataInicio" type="text" value="<?=date('d/m/Y',strtotime($dataInicio))?>" maxlength="10"  style="width:70px" class="campoData" /> até: <input name="DataFim" id="DataFim" type="text" value="<?=date('d/m/Y',strtotime($dataFim))?>" maxlength="10"  style="width:70px" class="campoData" /> <input name="Alterar" type="submit" value="Alterar Período" /></div>
							</form>
							<div style="float:right; display:none">Assinale os ítens que deseja alterar e escolha a ação: 
								<select name="">
									<option>excluir</option>
									<option>editar</option>
								</select>
								<input name="ok" type="button" value="Ok" />
							</div>
							<div style="clear:both; height:10px"> </div>
							<?php

							$sql = "SELECT SUM(entrada) entrada,SUM(saida) saida FROM user_" . $_SESSION['id_empresaSecao'] . "_livro_caixa WHERE data < '$dataInicio'";
							$resultado = mysql_query($sql)
							or die (mysql_error());

							$saldo = 0;

							while ($linha=mysql_fetch_array($resultado)) {
								$entrada = $linha["entrada"];
								$saida = $linha["saida"];
								$saldo = bcadd($saldo,bcsub($entrada, $saida, 2),2);
							}

							$sql = "SELECT * FROM user_" . $_SESSION['id_empresaSecao'] . "_livro_caixa WHERE data BETWEEN '$dataInicio' AND '$dataFim' ORDER BY data, id ASC";

							$resultado = mysql_query($sql)
							or die (mysql_error());

							$total = mysql_num_rows($resultado);

							if ($total != 0) {


								?>
								<table border="0"  cellspacing="2" cellpadding="4" width="966" style="font-size: 11px;">
									<? if($mostra_categoria == false){ ?>
									<tr>
										<th width="91" style="font-size: 12px;">Ação</th>
										<th width="80" style="font-size: 12px;">Data</th>
										<th width="135" style="font-size: 12px;">Documento nº</th>
										<th width="450" style="font-size: 12px;">Descrição</th>
										<th width="70" style="font-size: 12px;">Entrada</th>
										<th width="70" style="font-size: 12px;">Saída</th>
										<th width="70" style="font-size: 12px;">Saldo</th>
										<th width="70" style="font-size: 12px;">Anexos</th>
									</tr>
									<? } else { ?>
									<tr>
										<th width="91" style="font-size: 12px;">Ação</th>
										<th width="80" style="font-size: 12px;">Data</th>
										<th width="135" style="font-size: 12px;">Doc nº</th>
										<th width="150" style="font-size: 12px;">Categoria</th>
										<th width="300" style="font-size: 12px;">Descrição</th>
										<th width="70" style="font-size: 12px;">Entrada</th>
										<th width="70" style="font-size: 12px;">Saída</th>
										<th width="70" style="font-size: 12px;">Saldo</th>
										<th width="70" style="font-size: 12px;">Anexos</th>
									</tr>
									<?
								}

								while ($linha=mysql_fetch_array($resultado)) {
									$id = $linha["id"];
									$data = date('d/m/Y', strtotime($linha["data"]));
									$entrada = $linha["entrada"];
									$saida = $linha["saida"];
									$documento_numero = $linha["documento_numero"];
									$descricao = $linha["descricao"];
									if($mostra_categoria == true){ 
										$categoria = $linha["categoria"];
									}

									switch($categoria){
										case 'Pró-Labore':
										$txtCategoriaMensagem = "de pró-labore";
										break;
										case 'Pgto. a autônomos e fornecedores':
										$txtCategoriaMensagem = "de autônomos ou pessoa jurídica";
										break;
										case 'Estagiários':
										$txtCategoriaMensagem = "de estagiários";
										break;
										case 'Distribuição de lucros':
										$txtCategoriaMensagem = "de distribuição de lucro";
										break;
									}



									$sql_pagto = "SELECT 
									id_pagto
									FROM 
									dados_pagamentos pgto
									WHERE 
									pgto.id_login='" . $_SESSION["id_empresaSecao"] . "'
									AND pgto.idLivroCaixa = " . $id;	
									
									$resultado_pagto = mysql_query($sql_pagto)
									or die (mysql_error());
									
									$total_pagto = mysql_num_rows($resultado_pagto);

									$saldo = bcadd($saldo,bcsub($entrada, $saida, 2),2);

									?>
									<tr>
										<td class="td_calendario" align="center">
											
											<a href="#" class="excluirPagamento" linha="<?=$id?>" pagto="<?=$total_pagto > 0 ? "1" : ""?>" cat="<?=$txtCategoriaMensagem?>" title="Excluir"><i class="fa fa-trash-o iconesAzul iconesGrd"></i></a>

											<? if($total_pagto <= 0){?>
											<a href="livros_caixa_movimentacao.php?dataInicio=<?=$dataInicio?>&dataFim=<?=$dataFim?>&editar=<?=$id?>" title="Editar"><i class="fa fa-pencil-square-o iconesAzul iconesGrd"></i></a></td>
											<? } else { ?>
											<a href="#" onClick="alert('Não é possível editar este lançamento pois ele está vinculado à folha de pagamentos <?=$txtCategoriaMensagem?>. Exclua e depois cadastre-o novamente.')" title="Editar"><i class="fa fa-pencil-square-o iconesAzul iconesGrd"></i></a></td>
											<? } ?>
											<td class="td_calendario" align="center"><?=$data?></td>
											<td class="td_calendario" width="135">
												<? 
												if($documento_numero != ''){
													
													// Normaliza os documento informado.
													$DocsAux = str_replace(", ",",",$documento_numero);
													
													$DocsAux = str_replace(",",", ",$DocsAux);
													
													
													echo $DocsAux;
 
												}?>

											</td>
											<? if($mostra_categoria == true){ ?>
											<td class="td_calendario"><?=$categoria?></td>   
											<? } ?>
											<td class="td_calendario">
												<?php 
													if( $linha["categoria"] == 'Estagiários' ){
														$consulta = mysql_query("SELECT * FROM estagiarios WHERE id_login = '".$_SESSION['id_empresaSecao']."' AND id = '".$descricao."' ");
														$objeto_consulta = mysql_fetch_array($consulta);
														echo $objeto_consulta['nome'];
													}
													else if( $linha["categoria"] == 'Pgto. a autônomos e fornecedores' ){
														$consulta = mysql_query("SELECT id,id_login,nome,cpf FROM dados_autonomos WHERE id = '".$descricao."' AND dados_autonomos.id_login = '".$_SESSION['id_empresaSecao']."' UNION SELECT id,id_login,nome,cnpj FROM dados_pj WHERE id = '".$descricao."' AND dados_pj.id_login = '".$_SESSION['id_empresaSecao']."' order by nome");
														$objeto_consulta = mysql_fetch_array($consulta);
														echo $objeto_consulta['nome'];
													}
													// else if( $linha["categoria"] == 'Pagto. de salários' ){
													// 	$consulta = mysql_query("SELECT * FROM dados_do_funcionario WHERE id = '".$_SESSION['id_empresaSecao']."' ");
													// 	$objeto_consulta = mysql_fetch_array($consulta);
													// 	echo $objeto_consulta['nome'];
													// }
													else if( $linha["categoria"] == 'Pró-Labore' && is_numeric($descricao)){
														
														$qry = "SELECT * FROM dados_do_responsavel WHERE idSocio = '".$descricao."' AND id = '".$_SESSION['id_empresaSecao']."' ";
														
														$consulta = mysql_query($qry);
														
														$objeto_consulta = mysql_fetch_array($consulta);
														echo $objeto_consulta['nome'];
													}
													else
														echo $descricao;
												?>
											</td>
											<td class="td_calendario" align="right"><?=($entrada > 0 ? number_format($entrada,2,",",".") : '')?></td>
											<td class="td_calendario" align="right"><?=($saida > 0 ? number_format($saida,2,",",".") : '')?></td>
											<td class="td_calendario" align="right"><?=($saldo != 0 ? number_format($saldo,2,",",".") : '0,00')?></td>
											<td class="td_calendario" align="right">
												

											<?php 

												//INICIO - Alteração dia 06/05/2016 - Uploads de arquivos junto com o lancamento
												//Arquivos: livros_caixa_movimentacao.php, livros_caixa_movimentacao_inserir.php, livros_caixa_movimentacao_gravar.php, livros_caixa_movimentacao_excluir.php

												// - Essa trecho de codigo trabalha exibir icones de download para cada arquivo que foi enviado para o servidor

											?>

											<?php 

											$consulta = mysql_query("SELECT * FROM comprovantes WHERE id_lancamento = '".$linha["id"]."' AND id_user = '".$_SESSION["id_empresaSecao"]."' ");
											
											while( $objeto=mysql_fetch_array($consulta) ){ 
												
												if( isset( $objeto['nome'] )):
													echo '<div class="download-arquivo"><a href="upload/comprovantes/'.$objeto['nome'].'" download>';
												endif;
												?>
												<i class="fa fa-file-text-o icone-download" aria-hidden="true"></i>
												
												<?php 
												if( isset( $objeto['nome'] ) ):
													echo '</a>
												<div class="mouse_over_nome_arquivo"> 
												'.$objeto['nome'].'
												</div></div>';
												endif;
											} ?>	
									</td>
								</tr>
								<?php } ?>
							</table>
	<?php //FIM ?>
	<div style="margin:20px 0 0 0 ;text-align:center;"><input type="button" value="Imprimir Livro Caixa" onClick="formImprimir()" />&nbsp;&nbsp;&nbsp;<input type="button" value="Exportar para Excel" onClick="formCSV()" /></div>
	<style type="text/css" media="screen">
		.icone-download{
			font-size: 14px !important;
			color: #143C62;
			margin-right: 2px;
			cursor: pointer;
			position: relative;
		}
		.download-arquivo{
			float: right;
			margin-left: 2px;
			position: relative;
		}
		.mouse_over_nome_arquivo{
			position: absolute;
			display: none;
			background: #FFFF99;
			padding: 10px;
			margin-top: 14px;
			right: -20px;
			min-width: 150px;
			z-index: 99999;

		}
		.mouse_over_nome_arquivo:before{
			content: '';
			position: absolute;
			border-style: solid;
			border-width: 0 15px 15px;
			border-color: #FFFF99 transparent;
			display: block;
			width: 0;
			z-index: 0;
			top: -12px;;
			right: 13px;
		}
		.excluirPagamento{
			text-decoration: none;
		}
		</style>
	<script>					
		// Informações - Hover
		$(".download-arquivo").hover(function(){
			$(this).find(".mouse_over_nome_arquivo").fadeIn(0);
		}, function(){
			$(this).find(".mouse_over_nome_arquivo").fadeOut(0);
		});
	</script>	

	<?php } else {?>
	Não foi efetuada nenhuma movimentação no período selecionado.
	<?php } ?>
</div>
</div>

<script>
		
	$( document ).ready(function() {
		//Cada vez que troca o tipo de lançamnto, deixa os campos iniciais
		$("#selTipoLancamento").change(function() {
			$("#opcao_pagamento").css("display","none");
			$("#opcao_emprestimo").css("display","none");
			$("#campoDescricao").css("display","block");
			$("#nomeCampo_beneficiario").css("display","none");
			$("#nomeCampo_descricao").css("display","block");
		});
		//Quando troca a categoria verifica se e uma das opções de emprestimo ou pagamentos para exibir os campos correspondentes
		$("#selCategoria").change(function() {
			var valor = $(this).val();
			
			$("#opcao_pagamento_erro").css('display', 'none');
			$("#opcao_pagamento").css('display', 'none');
			$("#campoDescricao").css("display","block");
			
			verificarEmprestimos(valor);
			verificarPagamentos(valor)
		});
		//Verifica se é pagamento e busca via ajax os dados correspondentes para o tipo de pagamento
		function verificarPagamentos(valor){
			var tipo = '';
			if( valor === 'Estagiários' && $("#selTipoLancamento").val() === 'saida' ){
				tipo = 'estagiário';
				tabela = 'estagiarios';
			}else if( valor === 'Pgto. a autônomos e fornecedores' && $("#selTipoLancamento").val() === 'saida' ){
				tipo = 'autônomo ou fornecedor';
				tabela = 'autonomos_fornecedores';
			// }else if( valor === 'Pagto. de salários' && $("#selTipoLancamento").val() === 'saida' ){
			// 	tipo = 'funcionário';
			// 	tabela = 'funcionarios';
			}else if( valor === 'Pró-Labore' && $("#selTipoLancamento").val() === 'saida' ){
				tipo = 'sócio'
				tabela = 'socios';
			}	
			else{
				$("#opcao_pagamento").css("display","none");
				$("#campoDescricao").css("display","block");
				$("#nomeCampo_beneficiario").css("display","none");
				$("#nomeCampo_descricao").css("display","block");
			}
			if( tipo != '' ){
				$("#campoDescricao").css("display","none");
				$("#opcao_pagamento").css("display","block");
				
				$("#nomeCampo_beneficiario").css("display","block");
				$("#nomeCampo_descricao").css("display","none");
				
				$("#opcao_pagamento .tipo").empty();
				$("#opcao_pagamento .tipo").append(tipo);	
				$.ajax({
					url:'ajax.php'
					, data: 'selectPagamentos=selectPagamentos&tabela='+tabela
					, type: 'post'
					, async: true
					, cache:false
					, success: function(retorno){
						// console.log(retorno);
					    obj = JSON.parse(retorno);
					    if( obj[0] === 'erro' ){
					    	$("#opcao_pagamento_erro").css('display', 'block');
					    	$("#opcao_pagamento").css('display', 'none');
					    	$("#opcao_pagamento_erro").empty();
					    	$("#opcao_pagamento_erro").append(obj[1]);
					    }
					    else{
						    $("#nome_pagamentos").empty();
						    $("#nome_pagamentos").append(obj[0]);
						}
					}
				});		
			}
		}
	    //Verifica se é Categoria Entrada->emprestimos para exibir o input com o przo de carencia    
		function verificarEmprestimos(valor){
			if( valor === 'Empréstimos' && $("#selTipoLancamento").val() === 'entrada' ){
				$("#opcao_emprestimo").attr("status","aberto");
				$("#opcao_emprestimo").css("display","block");
			}
			else{
				$("#opcao_emprestimo").attr("status","fechado");	
				$("#opcao_emprestimo").css("display","none");
			}
		}

	});

</script>


<?php include 'rodape.php' ?>




<div id="imprimirLivroCaixa" style="left:50%; top:200px; margin-left:200px; position:absolute; display:none">

	<div style="width:255px; height:221px; background:url(images/balloon_livros_caixa_movimentacao_impressao.png)"><a href="javascript:fechaDiv('imprimirLivroCaixa')"><img src="images/x.png" width="8" height="9" border="0" style="float:right; margin-top:35px; margin-right:20px" /></a>
		<div style="padding:10px; padding-top:35px; padding-left:20px; text-align:left">
			<!--aqui começa o conteudo -->
			<div class="tituloPeq">Impressão de Livro Caixa</div><br />
			<form style="display:inline" method="post" action="Javascript:formImprimir()">
				<div class="tituloVermelho" style="margin-bottom:10px">Período</div>
				Imprimir livro caixa no período de:<br />
				<br />
				<input name="imprimirDataInicio" id="imprimirDataInicio" type="text" value="<?=date('d/m/Y',strtotime($dataInicio))?>" maxlength="10"  style="width:70px; padding:0px; padding-top:1px; padding-bottom:1px"/> até: <input name="imprimirDataFim" id="imprimirDataFim" type="text" value="<?=date('d/m/Y',strtotime($dataFim))?>" maxlength="10"  style="width:70px; padding:0px; padding-top:1px; padding-bottom:1px"/>
				<br /><br />
				<input name="" type="submit" value="Imprimir" />
			</form>
		</div>
		<!--aqui termina o conteudo -->
	</div>
</div>

<?php 

	if( isset( $_GET['erro_file'] ) ):
		echo'
		<script>
		
			alert("O arquivo \""+'.$_GET["erro_file"].'+"\" ultrapassa o limite máximo de 1Mb.");
		
		</script>
		';

	endif;

?>



<div id="loader" style="
position: absolute;left: 0;top: 0;width: 100%;height: 100%;background: rgba(0,0,0,0.1);padding-top: 300px;z-index: 999999;display:none">
<center>
	<div class="sk-fading-circle">
		<div class="sk-circle1 sk-circle"></div>
		<div class="sk-circle2 sk-circle"></div>
		<div class="sk-circle3 sk-circle"></div>
		<div class="sk-circle4 sk-circle"></div>
		<div class="sk-circle5 sk-circle"></div>
		<div class="sk-circle6 sk-circle"></div>
		<div class="sk-circle7 sk-circle"></div>
		<div class="sk-circle8 sk-circle"></div>
		<div class="sk-circle9 sk-circle"></div>
		<div class="sk-circle10 sk-circle"></div>
		<div class="sk-circle11 sk-circle"></div>
		<div class="sk-circle12 sk-circle"></div>
	</div>
</center>
</div>

<style type="text/css" media="screen">
.sk-fading-circle {
	margin: 100px auto;
	width: 40px;
	height: 40px;
	position: relative;
}

.sk-fading-circle .sk-circle {
	width: 100%;
	height: 100%;
	position: absolute;
	left: 0;
	top: 0;
}

.sk-fading-circle .sk-circle:before {
	content: '';
	display: block;
	margin: 0 auto;
	width: 15%;
	height: 15%;
	background-color: #143C62;
	border-radius: 100%;
	-webkit-animation: sk-circleFadeDelay 1.2s infinite ease-in-out both;
	animation: sk-circleFadeDelay 1.2s infinite ease-in-out both;
}
.sk-fading-circle .sk-circle2 {
	-webkit-transform: rotate(30deg);
	-ms-transform: rotate(30deg);
	transform: rotate(30deg);
}
.sk-fading-circle .sk-circle3 {
	-webkit-transform: rotate(60deg);
	-ms-transform: rotate(60deg);
	transform: rotate(60deg);
}
.sk-fading-circle .sk-circle4 {
	-webkit-transform: rotate(90deg);
	-ms-transform: rotate(90deg);
	transform: rotate(90deg);
}
.sk-fading-circle .sk-circle5 {
	-webkit-transform: rotate(120deg);
	-ms-transform: rotate(120deg);
	transform: rotate(120deg);
}
.sk-fading-circle .sk-circle6 {
	-webkit-transform: rotate(150deg);
	-ms-transform: rotate(150deg);
	transform: rotate(150deg);
}
.sk-fading-circle .sk-circle7 {
	-webkit-transform: rotate(180deg);
	-ms-transform: rotate(180deg);
	transform: rotate(180deg);
}
.sk-fading-circle .sk-circle8 {
	-webkit-transform: rotate(210deg);
	-ms-transform: rotate(210deg);
	transform: rotate(210deg);
}
.sk-fading-circle .sk-circle9 {
	-webkit-transform: rotate(240deg);
	-ms-transform: rotate(240deg);
	transform: rotate(240deg);
}
.sk-fading-circle .sk-circle10 {
	-webkit-transform: rotate(270deg);
	-ms-transform: rotate(270deg);
	transform: rotate(270deg);
}
.sk-fading-circle .sk-circle11 {
	-webkit-transform: rotate(300deg);
	-ms-transform: rotate(300deg);
	transform: rotate(300deg); 
}
.sk-fading-circle .sk-circle12 {
	-webkit-transform: rotate(330deg);
	-ms-transform: rotate(330deg);
	transform: rotate(330deg); 
}
.sk-fading-circle .sk-circle2:before {
	-webkit-animation-delay: -1.1s;
	animation-delay: -1.1s; 
}
.sk-fading-circle .sk-circle3:before {
	-webkit-animation-delay: -1s;
	animation-delay: -1s; 
}
.sk-fading-circle .sk-circle4:before {
	-webkit-animation-delay: -0.9s;
	animation-delay: -0.9s; 
}
.sk-fading-circle .sk-circle5:before {
	-webkit-animation-delay: -0.8s;
	animation-delay: -0.8s; 
}
.sk-fading-circle .sk-circle6:before {
	-webkit-animation-delay: -0.7s;
	animation-delay: -0.7s; 
}
.sk-fading-circle .sk-circle7:before {
	-webkit-animation-delay: -0.6s;
	animation-delay: -0.6s; 
}
.sk-fading-circle .sk-circle8:before {
	-webkit-animation-delay: -0.5s;
	animation-delay: -0.5s; 
}
.sk-fading-circle .sk-circle9:before {
	-webkit-animation-delay: -0.4s;
	animation-delay: -0.4s;
}
.sk-fading-circle .sk-circle10:before {
	-webkit-animation-delay: -0.3s;
	animation-delay: -0.3s;
}
.sk-fading-circle .sk-circle11:before {
	-webkit-animation-delay: -0.2s;
	animation-delay: -0.2s;
}
.sk-fading-circle .sk-circle12:before {
	-webkit-animation-delay: -0.1s;
	animation-delay: -0.1s;
}

@-webkit-keyframes sk-circleFadeDelay {
	0%, 39%, 100% { opacity: 0; }
	40% { opacity: 1; }
}

@keyframes sk-circleFadeDelay {
	0%, 39%, 100% { opacity: 0; }
	40% { opacity: 1; } 
}
</style>
