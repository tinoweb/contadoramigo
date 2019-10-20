<?php 

//	ini_set('display_errors',1);
//	ini_set('display_startup_erros',1);
//	error_reporting(E_ALL);

	if($_POST['ajaxMethod']){
		
		session_start();
		
		require('conect.php');		
		require_once('Controller/livros_caixa_movimentacao-controller.php');
				
		// Pega o arquivo responsável por manipular dados do livro caixa. 
		$controller = new LivrosCaixaMovimentacaoController();
		
		$controller->AjaxLivroCaixa($_POST['ajaxMethod']);
		
		die();
	}

	require_once('header_restrita.php'); 

	require_once('Controller/livros_caixa_movimentacao-controller.php');
	require_once('Model/Categoria/CategoriasData.php');


	// Pega o arquivo responsável por manipular dados do livro caixa. 
	$controller = new LivrosCaixaMovimentacaoController();

	// Pega as Categorias.
	$categoriaData = new CategoriasData();
	
	$categorias = $categoriaData->pegaTodosCategorias();
	
	$empresaId = $_SESSION['id_empresaSecao'];

	$userIdSecaoMultiplo = $_SESSION['id_userSecaoMultiplo'];

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
<style>
	
	.campoLancamento{
		border-top: 1px solid #f5f6f7;
		border-bottom: 1px solid #f5f6f7;
		min-height: 25px;
		padding: 5px 0 5px 10px;
		width: 956px;
	}
	
	.campoLancamentoAux {
		border-top: 1px solid #f5f6f7;
	}
	
	#tableFilter select,
	#tableFilter input {
		width: 100%;
		max-width: 160px;
	}	
	
	#tableFilter td {
		border: 1px solid #f5f6f7;
		/*min-width: 140px;*/
		text-align: left;
		height: 30px;
		padding: 3px 5px;
	}	
	
	.ContasAPagarSelect,
	.ContasAReceberSelect {
		min-width: 200px;
	}
	
	#tableFilter td.filtroLancamento{
		border-top: 1px solid #f5f6f7;
		height: 25px;
		background: #024a68;
		color: #FFF;
		font-weight: bold;
		padding: 0 5px 0 5px;
	}
	
	.linhaSpan {
		margin-left: 30px;
	}
	
</style>
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

			if($('#selCategoria').val() == 'Serviços prestados em geral') {

				if( $('#dataNotaFiscal').val().length == 0 ){
					alert('Informe a data da nota fiscal');
					$('#dataNotaFiscal').focus();
					return false;
				}
			}

			if($('#acao').val() == 'inserEmprestimo' && $('#selCategoria').val() == 'Empréstimos') {

				if( $('#apelido').val().length == 0 && $('#registroPaiId').val().length == 0 ){
					alert('Atribua um apelido para este empréstimo');
					$('#apelido').focus();
					return false;
				}

				if( $('#prazo_carencia').val().length == 0 && $('#apelido').val().length != 0 ){
					alert('Informe o prazo de carência do empréstimo');
					$('#prazo_carencia').focus();
					return false;
				}

			} else if( $('#acao').val() == 'inserNovoEmprestimo' && $('#selCategoria').val() == 'Empréstimos' ) {

				if( $('#apelido').val().length == 0 ){
					alert('Atribua um apelido para este empréstimo');
					$('#apelido').focus();
					return false;
				}

				if( $('#prazo_carencia').val().length == 0 ){
					alert('Informe o prazo de carência do empréstimo');
					$('#prazo_carencia').focus();
					return false;
				}

			} else if( $('#acaoAmortizacao').val() == 'inserArmotizacao' && $('#selCategoria').val() == 'Empréstimo (amortização)' ){

				if( $('#registroPaiIdAmortizacao').val().length == 0 ){
					alert('Informe o empréstimo a ser amortizado');
					$('#registroPaiIdAmortizacao').focus();
					return false;
				} else if( $('#registroPaiIdAmortizacao').val() == 'SemListaEmprestimo' ) {
					alert('Não exite emprestimo para ser amortizado');
					$('#registroPaiIdAmortizacao').focus();
					return false;
				}

				if( $('#saldoRemanescente').val().length == 0 ){
					alert('Informe o saldo devedor remanescente');
					$('#saldoRemanescente').focus();
					return false;
				}

			} else if( $('#acao').val() == 'editarEmprestimo' && $('#selCategoria').val() == 'Empréstimos' ){

				if( $('#apelido').val().length == 0 ){
					alert('Atribua um apelido para este empréstimo');
					$('#apelido').focus();
					return false;
				}

				if( $('#prazo_carencia').val().length == 0 ){
					alert('Informe o prazo de carência do empréstimo');
					$('#prazo_carencia').focus();
					return false;
				}

			} else if( $('#acao').val() == 'editarAmortizacao' && $('#selCategoria').val() == 'Empréstimo (amortização)' ){
				if( $('#saldoRemanescente').val().length == 0 ){
					alert('Informe o saldo devedor remanescente');
					$('#saldoRemanescente').focus();
					return false;
				}
			}	

			if( $('#selTipoLancamento').val() != 'entrada' && $('#tabEmprestimo').attr('status') == 'emprestimo' ){
				alert('Não é possível alterar o status de entrada e saída quando for empréstimo.');
				PegaListaCategoria('entrada');
				$('#selTipoLancamento').val('entrada');
				$('#selCategoria').val('Empréstimos');
				$('#selTipoLancamento').focus();
				DesabilitaCamposComplementares();
				$("#opcao_emprestimo").attr("status","aberto");
				$("#opcao_emprestimo").css("display","block");			
				return false;
			}
			if( $('#selCategoria').val() != 'Empréstimos' && $('#tabEmprestimo').attr('status') == 'emprestimo' ){
				alert('Não é possível alterar a categoria quando for empréstimo.');
				$('#selCategoria').val('Empréstimos');
				$('#selCategoria').focus();
				DesabilitaCamposComplementares();
				$("#opcao_emprestimo").attr("status","aberto");
				$("#opcao_emprestimo").css("display","block");
				return false;
			}

			if( $('#selTipoLancamento').val() != 'saida' && $('#tabEmprestimo').attr('status') == 'amortizacao' ){
				alert('Não é possível alterar o status de entrada e saída quando for empréstimo.');
				PegaListaCategoria('saida');
				$('#selTipoLancamento').val('saida');
				$('#selCategoria').val('Empréstimo (amortização)');
				$('#selTipoLancamento').focus();
				DesabilitaCamposComplementares();
				$("#saldoDevedorRemanescente").css("display","block");
				$('#saldoRemanescente').attr("disabled", false);
				$('#registroPaiIdAmortizacao').attr("disabled", false);
				return false;
			}
			if( $('#selCategoria').val() != 'Empréstimo (amortização)' && $('#tabEmprestimo').attr('status') == 'amortizacao' ){
				alert('Não é possível alterar a categoria quando for empréstimo.');
				$('#selCategoria').val('Empréstimo (amortização)');
				$('#selCategoria').focus();
				DesabilitaCamposComplementares();
				$("#saldoDevedorRemanescente").css("display","block");
				$('#saldoRemanescente').attr("disabled", false);
				$('#registroPaiIdAmortizacao').attr("disabled", false);
				return false;
			}

			console.log('Nesta Linha');

			//Vefifica se é pagamento, se nao for apenas insere o dados no livro caixa
			if( verificarPagamentos() ) {
				$('#form_livro').submit();
			}
			return;

		});

		function DesabilitaCamposComplementares(){
			// Desabilita campos complementares
			$(".opcao_pagamento").css("display","none");
			$("#opcao_emprestimo").css("display","none");
			$("#saldoDevedorRemanescente").css("display","none");

			$('#dataNotaFiscal').attr("disabled", true);
			$('#dataVencimentoReceber').attr("disabled", true);
			$('#valorOriginalReceber').attr("disabled", true);		
			
			$("#contasAReceber").hide();
		}

		//funcao que verifica se o item inserido é um pagamento, caso seja vaz as verificações para garantir que o pagamento esteja cadastrado em pagamentos, se nao avisa o usuario da inconsistencia
		function verificarPagamentos(){
			var cat = $('#selCategoria').val();
			var tipo = $("#selTipoLancamento").val();
			if( ( cat === 'Estagiários' || cat === 'Pgto. a autônomos e fornecedores' || cat === 'Pagto. de Salários' || cat === 'Pró-Labore' || cat === 'Distribuição de lucros') && tipo === 'saida' ){
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
			PegaListaCategoria(valor);
		});

		// pega as lista de categorias.
		function PegaListaCategoria(valor) {

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
		}


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

	
<div style="float: left">
	<h1 class="titulo">Escrituração</h1>
	<h2>Livro Caixa</h2>	
</div>

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
					
					$dataInicio = isset($_GET["dataInicio"]) ? $_GET["dataInicio"] : '';
					
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
					if(isset($_GET["dataInicio"]) && $_GET["dataInicio"] != $_SESSION["dataInicioLivroCaixa"]){
						$dataInicio = $_GET["dataInicio"];
					}else{
						$dataInicio = $_SESSION["dataInicioLivroCaixa"];
					}
				}

				if(!isset($_SESSION['dataFimLivroCaixa'])){
					$dataFim = isset($_GET["dataFim"]) ? $_GET["dataFim"] : '';
					
					if ($dataFim == "") {
						$dataFim = date("Y-m-d");
					}
					
				}else{
					if(isset($_GET["dataFim"]) && $_GET["dataFim"] != $_SESSION["dataFimLivroCaixa"]){
						$dataFim = $_GET["dataFim"];
					}else{
						$dataFim = $_SESSION["dataFimLivroCaixa"];
					}
				}

				$_SESSION['dataInicioLivroCaixa'] = $dataInicio;
				$_SESSION['dataFimLivroCaixa'] = $dataFim;
				
				// Realiza a verificação para ver se e edição ou inclusão.
				$editar = isset($_GET["editar"]) ? $_GET["editar"] : false;
	
				/** 
				 * Chama o método para gera os campos de lancamento.
				 * Data 10/04/2018.
				 */
	
				 echo $controller->AreaInclusaoLancamento($editar, $empresaId, $userIdSecaoMultiplo);
				
				?>
				
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

				$sql = " SELECT * FROM user_" . $_SESSION['id_empresaSecao'] . "_livro_caixa c "
					." LEFT JOIN lancamento_contas_pagar_receber l ON livro_caixa_id = c.id AND empresaId = '".$_SESSION['id_empresaSecao']."' "
					." WHERE c.data BETWEEN '$dataInicio' AND '$dataFim' "
					." AND l.contas_pag_rec_id is NULL "
					." ORDER BY c.data, c.id ASC ";												

				$resultado = mysql_query($sql) or die (mysql_error());

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
										 else if( $linha["categoria"] == 'Pagto. de Salários' && is_numeric($descricao)){
											$query = "SELECT * FROM dados_do_funcionario WHERE idFuncionario ='".$descricao."' AND id = '".$_SESSION['id_empresaSecao']."'";
										 	$consulta = mysql_query($query);
										 	$objeto_consulta = mysql_fetch_array($consulta);
										 	echo $objeto_consulta['nome'];
										 }
										else if( $linha["categoria"] == 'Pró-Labore' && is_numeric($descricao)){

											$qry = "SELECT * FROM dados_do_responsavel WHERE idSocio = '".$descricao."' AND id = '".$_SESSION['id_empresaSecao']."' ";

											$consulta = mysql_query($qry);

											$objeto_consulta = mysql_fetch_array($consulta);
											echo $objeto_consulta['nome'];
										}
										else if( $linha["categoria"] == 'Distribuição de lucros' && is_numeric($descricao)){

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
									echo $controller->PegaAnexos($linha["id"], $empresaId);		
								?>	
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
			margin-left: 1px;
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
	
	$(function(){
		$('#mostraImput').click(function(e){
			e.preventDefault();
			$('#opcao_emprestimo').css('display', 'table-cell');
			$('.selecioneEmprestimo').hide();
			$('#registroPaiId').val('');
			$('#registroPaiId').attr("disabled", true);
			$('#prazo_carencia').attr("disabled", false);
			$('#apelido').attr("disabled", false);
		});
		
		$('#mostraSelecione').click(function(e){
			e.preventDefault();			
			$('#opcao_emprestimo').hide();
			$('.selecioneEmprestimo').css('display', 'table-cell');
			$('#registroPaiId').attr("disabled", false);
			$('#registroPaiId').val('');			
			$('#prazo_carencia').attr("disabled", true);
			$('#apelido').attr("disabled", true);
		});
	});
	
</script>
<script>
		
	$( document ).ready(function() {
		
		//Cada vez que troca o tipo de lançamnto, deixa os campos iniciais
		$("#selTipoLancamento").change(function() {
			
			/** Oculta **/ 			
			$(".opcao_pagamento").hide();
			$("#opcao_emprestimo").hide();
			$("#saldoDevedorRemanescente").hide();
			$("#nomeCampo_beneficiario").hide();
			$("#contasAReceber").hide();
			$('#contasAPagar').hide();			
			$('.ContasAPagarSelect').hide();
			$('.ContasAReceberSelect').hide();
			$('#mostraSelecionePagar').hide();
			$('#mostraSelecioneReceber').hide();
			$('.selecioneAmortizacaoEmprestimo').hide();
			$('.selecioneEmprestimo').hide();
			
			/** Mostra **/
			$(".campoDescricao").css("display",'table-cell');
			$(".campoDoc").css("display",'table-cell');
			$("#nomeCampo_descricao").css("display","block");
			
			/** Desativa **/
			$('#dataNotaFiscal').attr("disabled", true);
			$('#dataVencimentoReceber').attr("disabled", true);
			$('#valorOriginalReceber').attr("disabled", true);		
			
			/** Definir tamanho Campo**/
			$('#linhaValor').attr('width','90');
			
			/** define o texto a ser apresentado **/
			$('#dataName').html('Data');				
			
		});
		//Quando troca a categoria verifica se e uma das opções de emprestimo ou pagamentos para exibir os campos correspondentes
		$("#selCategoria").change(function() {
			var valor = $(this).val();
			
			/** Oculta **/ 
			$(".opcao_pagamento_erro").hide();
			$(".ContasAPagarSelect").hide();
			$(".opcao_pagamento").hide();
			$(".ContasAReceberSelect").hide();
			$(".ContasAReceberSelect").hide();
			$('#mostraSelecionePagar').hide();
			$('#mostraSelecioneReceber').hide();
			$('.selecioneAmortizacaoEmprestimo').hide();
			$('.selecioneEmprestimo').hide();
			
			/** Mostra **/
			$(".campoDescricao").css("display",'table-cell');
			$(".campoDoc").css("display",'table-cell');

			/** Definir tamanho Campo**/
			$('#linhaValor').attr('width','90');
			
			/** define o texto a ser apresentado **/
			$('#dataName').html('Data');			
			
			verificarcontasAReceberGeral(valor);
			
			VerificarcontasAPagar(valor);
			
			// Se devera mostra primeiro a lista comos os emprestimo ou o campo para inclusão do emprestimo caso não exista lista de emprestimos.
			if('<?php echo $controller->StatusListaEmprestimo;?>'){
				verificarListaEmprestimos(valor);
			} else {
				verificarEmprestimos(valor);
			}
			
			verificarSaldoDevedorRemanescente(valor);
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
			 }else if( valor === 'Pagto. de Salários' && $("#selTipoLancamento").val() === 'saida' ){
			 	tipo = 'funcionário';
			 	tabela = 'funcionarios';
			}else if( valor === 'Pró-Labore' && $("#selTipoLancamento").val() === 'saida' ){
				tipo = 'sócio'
				tabela = 'socios';
			}else if( valor === 'Distribuição de lucros' && $("#selTipoLancamento").val() === 'saida' ){
				tipo = 'sócio'
				tabela = 'socios';
			}	
			else{
				$(".opcao_pagamento").css("display","none");
				//$(".campoDescricao").css("display","table-cell");
				$("#nomeCampo_beneficiario").css("display","none");
				$("#nomeCampo_descricao").css("display","block");
			}
			if( tipo != '' ){
				$("#campoDescricao").css("display","none");
				$(".campoDescricao").css("display",'none');
				$(".opcao_pagamento").css("display","table-cell");
				
				$("#nomeCampo_beneficiario").css("display","block");
				$("#nomeCampo_descricao").css("display","none");
				
				$(".opcao_pagamento .tipo").empty();
				$(".opcao_pagamento .tipo").append(tipo);	
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
					    	$(".opcao_pagamento_erro").css('display', 'table-cell');
					    	$(".opcao_pagamento").css('display', 'none');
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
		
		function verificarListaEmprestimos(valor){
			if( valor === 'Empréstimos' && $("#selTipoLancamento").val() === 'entrada' ){
				$(".selecioneEmprestimo").css("display","table-cell");
				$('#registroPaiId').attr("disabled", false);
			}
			else{
				$(".selecioneEmprestimo").css("display","none");
				$('#registroPaiId').attr("disabled", true);
			}
		}
		
	    //Verifica se é Categoria Entrada->emprestimos para exibir o input com o przo de carencia    
		function verificarSaldoDevedorRemanescente(valor){
			if( valor === 'Empréstimo (amortização)' && $("#selTipoLancamento").val() === 'saida' ){
				$("#saldoDevedorRemanescente").css("display","block");
				$('#saldoRemanescente').attr("disabled", false);
				$('#registroPaiIdAmortizacao').attr("disabled", false);
				if('<?php echo $controller->StatusAmortizaca; ?>'){
					$(".selecioneAmortizacaoEmprestimo").css("display", "table-cell");
				}				
			}
			else{
				$(".selecioneAmortizacaoEmprestimo").css("display", "none");
				$("#saldoDevedorRemanescente").css("display","none");
				$('#saldoRemanescente').attr("disabled", true);
				$('#registroPaiIdAmortizacao').attr("disabled", true);
				
			}
		}
		
		//Verifica se é Categoria Entrada->emprestimos para exibir o input com o przo de carencia    
		function verificarcontasAReceberGeral(valor){
			
<?php 
	$clientes = array();

	$sql_clientes = "SELECT DISTINCT apelido FROM dados_clientes WHERE id_login = " . $_SESSION['id_empresaSecao'] . " AND status = 'A' ORDER BY apelido";
	$rs_clientes = mysql_query($sql_clientes);
	if(mysql_num_rows($rs_clientes) > 0){
		while($dados_clientes = mysql_fetch_array($rs_clientes)){
			$clientes[] = $dados_clientes['apelido'];
		}
	}
?>
			// Pega uma lista dos cliente no formato json
			var clientes = '<?php echo json_encode($clientes);?>';
			
			// Converte a lista de clientes para um lista em array.
			var status = JSON.parse(clientes).indexOf(valor);

			// Verifica se sera necessario apresentar o campo da data da nota fiscal.
			if( (valor === 'Serviços prestados em geral' || status != -1 ) && $("#selTipoLancamento").val() === 'entrada' ){
					
					var status = false;
					
					$.ajax({
						url:'livros_caixa_movimentacao.php'
						, data: {ajaxMethod:'ListaContasAReceber', categoria: valor}
						, type: 'post'
						, async: true
						, cache: false
						, dataType: 'json' 
						, beforeSend: function() {}
						, success: function(dados){
							
							console.log(dados['option']);
							
							$('#contasAreceberId').html(dados['option'])
							status = dados['status'];
						}
						,error: function(erro) { // if error occured
							console.log(erro);
						}
						,complete: function() {
														
							if(status) {
								
								/** Oculta **/ 
								$(".campoDescricao").css("display",'none');
								$(".campoDoc").css("display",'none');
								$("#contasAReceber").css("display","none");
								
								/** Mostra **/
								$(".ContasAReceberSelect").css("display","table-cell");
								$('#mostraSelecioneReceber').css("display","inline-block");


								/** Desativa **/					
								$('#contasAreceberId').attr("disabled", false);	
								$('#dataNotaFiscal').attr("disabled", true);
								$('#dataVencimentoReceber').attr("disabled", true);
								$('#valorOriginalReceber').attr("disabled", true);
								
								/** Definir tamanho Campo**/
								$('#linhaValor').attr('width','');								
								
							} else {
								
								/** Oculta **/ 
								$(".ContasAReceberSelect").css("display","none");						
								$('#mostraSelecioneReceber').css("display","none");							
								
								/** Mostra **/
								$("#contasAReceber").css("display","block");
								$(".campoDescricao").css("display",'table-cell');
								$(".campoDoc").css("display",'table-cell');
								
								/** Desativa **/					
								$('#contasAreceberId').attr("disabled", true);

								/** Ativar **/
								$('#dataNotaFiscal').attr("disabled", false);
								$('#dataVencimentoReceber').attr("disabled", false);
								$('#valorOriginalReceber').attr("disabled", false);
								
								/** Definir tamanho Campo**/
								$('#linhaValor').attr('width','90');								
							}
						}				
					});
				
			} else {
				
					/** Oculta **/ 
					$(".ContasAReceberSelect").css("display","none");
					$("#contasAReceber").css("display","none");

					/** Desativa **/					
					$('#contasAreceberId').attr("disabled", true);
					$('#dataNotaFiscal').attr("disabled", true);
					$('#dataVencimentoReceber').attr("disabled", true);
					$('#valorOriginalReceber').attr("disabled", true);
				
			}
		}
		
		$('#mostraCampoDataReceber').click(function(e){
			e.preventDefault();
			/** Ocultar **/
			$(".ContasAReceberSelect").css("display","none");
						
			/** Mostra **/
			$("#contasAReceber").css("display","block");	
			$(".campoDescricao").css("display",'table-cell');
			$(".campoDoc").css("display",'table-cell');			
			
			/** desativar **/
			$('#contasAreceberId').attr("disabled", true);
						
			/** Ativar **/
			$('#dataNotaFiscal').attr("disabled", false);			
			$('#dataVencimentoReceber').attr("disabled", false);
			$('#valorOriginalReceber').attr("disabled", false);
						
			/** Definir tamanho Campo**/
			$('#linhaValor').attr('width','90');
		});
		
		$('#mostraSelecioneReceber').click(function(e){
			e.preventDefault();
			
			/** Ocultar **/
			$("#contasAReceber").css("display","none");
			$(".campoDescricao").css("display",'none');
			$(".campoDoc").css("display",'none');			
			
			/** Mostra **/
			$(".ContasAReceberSelect").css("display","table-cell");		
			
			/** desativar **/
			$('#dataNotaFiscal').attr("disabled", true);
			$('#dataVencimentoReceber').attr("disabled", true);
			$('#valorOriginalReceber').attr("disabled", true);
						
			/** Ativar **/
			$('#contasAreceberId').attr("disabled", false);	
			
			/** Definir tamanho Campo**/
			$('#linhaValor').attr('width','');
								
		});		
		
		function VerificarcontasAPagar(valor){
		
			// Itens da categoria referente ao contas a pagar.
			var categoriaArray = ['Aluguel','Aluguel de software','Água','Combustível','Comissões','Condomínio','Contador','Cursos e treinamentos','Energia elétrica','Impostos e encargos','Internet','Marketing e publicidade','Material de escritório','Segurança','Seguros','Telefone','Transportadora / Motoboy'];

			// Converte a lista de clientes para um lista em array.
			var status = categoriaArray.indexOf(valor);
			
			console.log(status);
			
			console.log($("#selTipoLancamento").val());
			
			// Verifica se sera necessario apresentar o campo da data da nota fiscal.
			if((status != -1 ) && $("#selTipoLancamento").val() === 'saida' ){

				$.ajax({
					url:'livros_caixa_movimentacao.php'
					, data: {ajaxMethod:'ListaContasAPagar', categoria: valor}
					, type: 'post'
					, async: true
					, cache: false
					, dataType: 'json' 
					, beforeSend: function() {}
					, success: function(dados){

						console.log(dados);

						$('#contasApagarId').html(dados['option'])
						status = dados['status'];
					}
					,error: function(erro) { // if error occured
						console.log(erro);
					}
					,complete: function() {

						if(status) {

							/** Oculta **/ 
							$(".campoDescricao").css("display",'none');
							$(".campoDoc").css("display",'none');
							$("#contasAPagar").css("display","none");

							/** Mostra **/
							$(".ContasAPagarSelect").css("display","table-cell");
							$('#mostraSelecionePagar').css("display","inline-block");
							
							/** Desativa **/					
							$('#dataDoc').attr("disabled", true);
							$('#dataVencimentoPagar').attr("disabled", true);
							$('#valorOriginalPagar').attr("disabled", true);

							/** Ativar **/
							$('#contasApagarId').attr("disabled", false);
							
							/** Definir tamanho Campo**/
							$('#linhaValor').attr('width','');
							
							/** define o texto a ser apresentado **/
							$('#dataName').html('Data Pagto.');
						} else {
							
							/** Oculta **/ 
							$(".ContasAPagarSelect").css("display","none");
							$("#mostraSelecionePagar").hide();

							/** Mostra **/
							$("#contasAPagar").css("display","block");
							$(".campoDescricao").css("display",'table-cell');
							$(".campoDoc").css("display",'table-cell');
							
							/** Desativa **/					
							$('#contasApagarId').attr("disabled", true);

							/** Ativar **/
							$('#dataDoc').attr("disabled", false);
							$('#dataVencimentoPagar').attr("disabled", false);
							$('#valorOriginalPagar').attr("disabled", false);
							
							/** Definir tamanho Campo**/
							$('#linhaValor').attr('width','90');
							
							/** define o texto a ser apresentado **/
							$('#dataName').html('Data Pagto.');
						}						
					}				
				});				

			} else {
				
				/** Oculta **/ 
				$(".ContasAPagarSelect").css("display","none");
				$("#contasAPagar").css("display","none");
				
				/** Desativa **/
				$('#contasApagarId').attr("disabled", true);
				$('#dataDoc').attr("disabled", true);
				$('#dataVencimentoPagar').attr("disabled", true);
				$('#valorOriginalPagar').attr("disabled", true);
								
			}			
			
		}
		
		$('#mostraCampoDataPagar').click(function(e){
			e.preventDefault();
			
			/** Ocultar **/
			$(".ContasAPagarSelect").css("display","none");
			$(".campoDescricao").css("display",'table-cell');
			$(".campoDoc").css("display",'table-cell');			
			
			/** Mostra **/
			$("#contasAPagar").css("display","block");
			
			/** desativar **/
			$('#contasApagarId').attr("disabled", true);
								
			/** Ativar **/
			$('#dataDoc').attr("disabled", false);
			$('#dataVencimentoPagar').attr("disabled", false);
			$('#valorOriginalPagar').attr("disabled", false);
			
			/** Definir tamanho Campo**/
			$('#linhaValor').attr('width','90');			
		});
		
		$('#mostraSelecionePagar').click(function(e){
			e.preventDefault();
			
			/** Ocultar **/
			$("#contasAPagar").css("display","none");
						
			/** Mostra **/
			$(".ContasAPagarSelect").css("display","table-cell");
			$(".campoDescricao").css("display",'none');
			$(".campoDoc").css("display",'none');
			
			/** desativar **/
			$('#DocNumeroContasAPagar').attr("disabled", true);
			
			/** Ativar **/
			$('#contasApagarId').attr("disabled", false);
			
			/** Definir tamanho Campo**/
			$('#linhaValor').attr('width','');			
		});
		
	});

</script>

<?php 
	if(isset($_SESSION['erroEmprestimo'])){
		
		if($_SESSION['erroEmprestimo'] == 'incluir'){
			echo "<script>alert('Não é possível incluir mais de uma amortização no mesmo dia.')</script>";
			unset($_SESSION['erroEmprestimo']);
		}
		
		if($_SESSION['erroEmprestimo'] == 'editar'){
			echo "<script>alert('Não é possível incluir mais de uma amortização no mesmo dia.')</script>";
			unset($_SESSION['erroEmprestimo']);
		}
		
		if($_SESSION['erroEmprestimo'] == 'excluir_amortizacao'){
			echo "<script>alert('Não é possível excluir um empréstimo que tem amortização.')</script>";
			unset($_SESSION['erroEmprestimo']);
		}
		
		if($_SESSION['erroEmprestimo'] == 'excluir_complementar'){
			echo "<script>alert('Não é possível excluir um empréstimo que tenha outro empréstimo relacionado a ele.')</script>";
			unset($_SESSION['erroEmprestimo']);
		}		
	}
?>

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
