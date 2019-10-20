<?php 
	require_once('header_restrita.php');
	require_once('livros_caixa_fluxo_class.php');
	require_once('balanco-form.class.php');

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
	<div class="titulo">Bens e direitos<br></div>
	<div>
		<span style="margin-right: 20px">Selecione o ano-calendário do balanço: </span>
		<?php $item->gerarInputano($ano); ?>	
		<input type="hidden" id="ano" value="<?php echo date("Y"); ?>">
		<br><br>
	</div>
	<div>
		<strong>Capital Social: </strong><?php echo $item->gerarInputp_l_capital_social(); ?>
		<br><br>
	</div>	
	<div class="tituloVermelho">Bens em nome da empresa</div>
	<div>
		<?php $item->inserirImobilizados(); ?>
		<br><br><br>
		<div class="tituloVermelho">Bens Intangíveis <?php echo $item->gerarImagemBallona_n_c_intangivel(); ?></div> 
		<?php $item->inserirIntangiveis(); ?>		
		<br><br>
	</div>	
</div>

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
</div>

<script>
	$( document ).ready(function() {
		
		//Remove o ultimo item imobilizado
	    $("#remover_imobilizado").click(function() {
			$.ajax({
				url:'ajax.php'
				, data: 'removerImobilizado=removerImobilizado&&id='+$("#imobilizado_deletar").val()
				, type: 'post'
				, async: true
				, cache:false
				, success: function(retorno){}
				, complete: function() {
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
				, success: function(retorno){}
				, complete: function() {
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
				, success: function(retorno){}
				, complete: function(){
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
				, success: function(retorno){}
				, complete: function(){
					location = "<?php echo $_SERVER['REQUEST_URI']; ?>";
					location.reload();
				}
			});
		});
		
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
				
		//Altera o ano do balanço
		$(".atualizarAno").change(function() {
			
			var ano = $(this).val();
			
			if('<?=$ano?>' != ano){
				window.location = 'bens_e_direitos.php?ano='+ano
			}
			
		});
	});
	
</script>	
		
<?php include 'rodape.php'; ?>