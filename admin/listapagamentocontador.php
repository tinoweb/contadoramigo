<?php
// ini_set('display_errors',1);
// ini_set('display_startup_erros',1);
// error_reporting(E_ALL);

	if(isset($_GET['contadorId'])&&!empty($_GET['contadorId'])) { 
		$contadorId = $_GET['contadorId'];
	}

	require_once 'header.php'; 
	require_once 'listaPagamentoContador-Controller.php';

	if(isset($_SERVER['erroMesagem'])) {
		echo "<script> alert('".$_SERVER['erroMesagem']."');</script>";
	}
?>
	<style>
		.tablePagamento td, .tablePagamento th{border: 1px solid #f5f6f7;}
		.tablePagamento .linhaStatus td {background: #d3d3d3; color:#676767;}
		.tablePagamento .linhaEstorno td {background: #F00; color:#FFF;}
		a.iconeSalva {color: #666; font-weight: bold;}
		a.iconeSalva:link {color: #666;}
		a.iconeSalva:hover {color: #00F;}
		a.linkMenu {display: inline-block; margin-left: 5px; margin-right: 5px;}
		a.linkMenuinicio {display: inline-block; margin-left: 25px; margin-right: 5px; text-decoration: none; color: #024a68;}
    </style>
    
    <div style="float:right; margin-top: 10px;">
		<?php echo gerafiltro(); ?> 
        <br/>  
    </div>
    
    <div style="text-align:left; margin-top:-15px;">
    	<span class="titulo">Pagamentos</span> 
		<a class="linkMenuinicio" href="clientes_premium_contador.php?contadorId=<?php echo $contadorId;?>">Premium</a>
		<a class="linkMenu" href="clientes_avulso_contador.php?contadorId=<?php echo $contadorId;?>">Avulsos</a>
		<!--<a class="linkMenu" href="pagamentocontador.php?contadorId=<?php echo $contadorId;?>">Pagamentos</a>-->
  		<a class="linkMenu" href="listapagamentocontador.php?contadorId=<?php echo $contadorId;?>">Pagamentos</a>
   		<a class="linkMenu" href="listapagamentocomissao.php?contadorId=<?php echo $contadorId;?>">Comissão</a>
    </div> 
   
    <div style="text-align:left; margin-top: 10px;">
   		<h2><?php echo pegaNomeContador();?></h2>
	</div>    
   
    <div class="lancamento">
    	<div style="height: 41px; padding: 5px 0 0 0; margin: 0;">
    		<form id="formGravaPagamento" action="listaPagamentocontadorAcao.php" method="post" style="text-align: left;">
				<input type="hidden" name="contadorId" value="<?php echo $_GET['contadorId'];?>" />
				<input type="hidden" name="gravarPagamento" value="" />
				<span style="margin-right:5px;"><b>Data:</b></span>
				<input type="text" size="8" name="dataPagamento" id="dataPagamento"  maxlength="10" value="<?php echo date('d/m/Y');?>" /> 
				<span style="margin-left: 5px; margin-right: 5px;"><b>Valor Pago:</b></span>
				<input type="text" size="10" name="valorPago" id="valorPagamento" class="current" />
				<button type="button" style="margin-left: 30px;" id="gravaPagamento">Incluir Pagamento</button>
			</form>	
    	</div>
    </div>    
 
    <div class="campoClientes">
		<?php echo geraTabelasPagamento();?>  
    </div>
    
    <div style="margin: 10px 0;"></div>
	
	<div id="caixaConfirmacao" style="display: none; position: fixed; top: 0; left: 0; background: rgba(0, 0, 0, 0.0); width:100%; height:100%; z-index:999;">
		<!--BALLOM excluir pagamento-->
		<div class="bubble only-box" style="padding: 0px; position: fixed; top:50%; left: 50%;  margin-top: -50px;  margin-left: -150px; z-index: 9990;">
			<div style="padding:20px; font-size:12px;">
				<div id="mensagemDELETEPagamento">Você tem certeza que deseja excluir este pagamento?</div><br>
				<div style="clear: both; margin: 0 auto; display: inline;">
					<form id="formExcluirPagamento" action="listaPagamentocontadorAcao.php" method="post">
						<input type="hidden" name="excluirPagamento" value="" />
						<input type="hidden" name="pagamentoId" value="" id="pagamentoId" />
						<input type="hidden" name="contadorId" value="<?php echo $_GET['contadorId'];?>" />
					</form>
					<center>
						<button id="btConfirma" type="button" linha="5206" pagto="">Sim</button>
						<button id="btCancela" type="button" linha="5206" pagto="">Não</button>
					</center>
				</div>
				<div style="clear: both;"></div>
			</div>
		</div>
		<!--FIM DO BALLOOM excluir pagamento -->
	</div>
	
	<script>
		
		$(function(){
			
			$('#btConfirma').click(function(){
				$('#formExcluirPagamento').submit();
			});
						
			$('#btCancela').click(function(){
				
				$('#pagamentoId').val('');
				
				$('#caixaConfirmacao').hide();
			});			
			
			$('.excluirPagamento').click(function(){
				
				var pagamentoId = $(this).attr('date-pagamentoId');
				
				$('#pagamentoId').val(pagamentoId);
				
				$('#caixaConfirmacao').show();
			});
			
			$(".gravaValor").click(function(){
				
				var formGrava = '#formGrava_' + $(this).attr('data-mes');
				
				// realiza o envio dos dados.
				$(formGrava).submit();
			});
			
			$('#dataPagamento').keypress(function(){
			
				var valor = '';
				
				valor = $(this).val().replace(/\D/g,"");
				
				valor = valor.replace(/(\d{2})(\d)/,"$1/$2");
				
				valor = valor.replace(/(\d{2})(\d)/,"$1/$2");

				$(this).val(valor);
			});
						
			$('#gravaPagamento').click(function() {
				
				// Define Parametros para validação da data.
				var dataPagtoArray = ($('#dataPagamento').val().split("/")); 
				var dataPgtoFomat = dataPagtoArray[1]+'/'+dataPagtoArray[0]+'/'+dataPagtoArray[2]
				var checkDataPgto = new Date(dataPgtoFomat);

				if(checkDataPgto == 'Invalid Date') {
					alert('Preencha a data de pagamento corretamente.');
					$('#DataPgto').focus();
					return false;
				}
				
				if($('#valorPagamento').val().length == 0) {
					alert('informe o valor');
					return false;
				}
					
				$('#formGravaPagamento').submit();
			});
		});
	
	</script>
	
<?php require_once '../rodape.php'; ?>