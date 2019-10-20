<?php

//	 ini_set('display_errors',1);
//	 ini_set('display_startup_erros',1);
//	 error_reporting(E_ALL);

	require_once 'header.php'; 
	require_once 'clientes_avulso-Controller.php';
?>
<div class="minHeight"> 
    <div class="titulo" style="text-align:left;">Serviços Avulsos</div>    
    <div class="campoClientes" style="float: right; margin-bottom: 10px; margin-top: -20px;">
		<form method="get" action="clientes_avulso.php">
			Exibir serviços:
            <select name="status">                
                <option value="todos" <?php echo ($status == "todos" ? "selected":'') ?>>Todos</option>
				<option value="Em Aberto" <?php echo ($status == "Em Aberto" || empty($status) ? "selected":'') ?>>Em Aberto</option>
				<option value="Primeiro Contato" <?php echo ($status == "Primeiro Contato" ? "selected":'') ?>>Primeiro Contato</option>                	
				<option value="Concluído" <?php echo ($status == "Concluído" ? "selected":'') ?>>Concluído</option> 
            </select> 
			ou buscar por
            <select name="filtro">
                <?php if($filtro == 'id') :?>
                    <option value="assinante" >Assinante</option>
                    <option value="id" selected>ID</option>
                <?php elseif($filtro == 'assinante') :?>
                    <option value="assinante" selected>Assinante</option>
                    <option value="id" >ID</option>
                <?php else:?>
                    <option value="assinante" >Assinante</option>
                    <option value="id" >ID</option>
                <?php endif;?>
            </select>
       		<input name="valor" id="txtBusca" style="width:100px; font-size: 11px;" value="<?php echo $valor;?>" type="text">

<!--		<span style="margin:0 5px"> <strong> Data de:</strong></span>
       		<input class="campoData" maxlength="10" name="data_inicio" id="txtBusca" style="width:100px; font-size: 11px;" value="" type="text">
			<span style="margin:0 5px"> <strong> ate:</strong></span>
       		<input class="campoData" maxlength="10" name="data_fim" id="txtBusca" style="width:100px; font-size: 11px;" value="" type="text"> -->
          
        	<button type="submit" style="margin-left:5px">Pesquisar</button>
		</form>
    </div>
	
	<div style="clear: both"></div>
	<?php if($status == "todos"):?>
	<h2 style="text-align: left">Todos</h2>
	<?php elseif($status == "Concluído"):?>
	<h2 style="text-align: left">Concluído</h2>
	<?php elseif($status == "Primeiro Contato"):?>
	<h2 style="text-align: left">Primeiro Contato</h2>
	<?php else:?>
	<h2 style="text-align: left">Em Aberto</h2>
	<?php endif; ?>
	
    <div style="margin: 10px 0; flo">
    	<?php echo $tagTable;?>
    </div>
</div>    
<script>
	$(document).ready(function(){
		
		$(".campoObservacao").focus(function() {
			$(this).css("height","260px");
			var tds = $(this).parent().parent().find('td');
			for (var i = 0; i < tds.length; i++) {
				td = tds[i];
				$(td).attr("valign","top");
			};
		});
		
		$(".campoObservacao").focusout(function() {
			$(this).css("height","60px");
			var tds = $(this).parent().parent().find('td');
			for (var i = 0; i < tds.length; i++) {
				td = tds[i];
				$(td).removeAttr("valign");
			};
		});
		
		$('.campoObservacao').change(function(){
			
			var id = $(this).attr('data-id');
			
			var observacao = $('#id_Observacao_'+id).val();			
						
			var dados = {method: 'atualizaObservacao', id:id, observacao:observacao}
			
			$.ajax({
				type: 'post',
				url: 'ajaxContador.php',
				dataType: 'json',
				data: dados,
				async: true,
				cache: false,
				success: function(data){
					//console.log(data['observacao']);					
				}
			});
		});		
		
		$('.campoStatus').click(function(){
	
			var id = $(this).attr('data-id');
			
			var statusAvulso = $(this).attr('data-status');
			
			var dados = {method:'AtualizaStatusServicoAvulso', id:id, status:statusAvulso}
	
			$.ajax({
				type: "post",
				url: 'ajaxContador.php',
				dataType: "json",
				data: dados,
				async: true,
				cache: false,
				beforeSend: function() {
					$('#span_ABT_'+id).hide();
					$('#span_C_'+id).hide();
					$('#span_P_'+id).hide();
					$('#load_'+id).show();
				},
				success: function(data) {
					//$('#span_'+id).html(data['status']);
					
					if(data['status'] == 'Concluído') {
						console.log('1');
						$('#load_'+id).hide();						
						$('#span_C_'+id).show();	
					} else if(data['status'] == 'Em Aberto') {
						console.log('2');
						$('#load_'+id).hide();
						$('#span_ABT_'+id).show();
					} else {
						console.log('3');
						$('#load_'+id).hide();
						$('#span_P_'+id).show();
					}
				},
				error: function(error) { 
					alert('Não foi possível realizar a alteração');
				},
				complete: function() {}
			});
		});
		
		$('.statusBola').click(function(){
			
			var id = $(this).attr('data-id');			
			
			var statusBola = $(this).attr('data-statusBola');			
			
			var dados = {method:'atualizaBola', id:id, statusBola:statusBola}
			
			$.ajax({
				type: 'post',
				url: 'ajaxContador.php',
				dataType: 'json',
				data: dados,
				async: true,
				cache: false,				
				success: function(data){					
					if(data['statusBola'] == 'Com a Gente'){
						$('#span_A_'+id).show();
						$('#span_E_'+id).hide();
						$('#span_J_'+id).hide();
					} else if(data['statusBola'] == 'Com o Cliente') {
						$('#span_A_'+id).hide();
						$('#span_E_'+id).show();
						$('#span_J_'+id).hide();
					} else {
						$('#span_A_'+id).hide();
						$('#span_E_'+id).hide();
						$('#span_J_'+id).show();
					}
				}
			});
		});		
	});
</script>    

<?php require_once '../rodape.php'; ?>
