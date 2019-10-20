<?php
	
// Verifica se e uma requisição ajax.
if(isset($_POST['ajaxAliquotaImpostos'])) {

	// Inicio a sessão.
	session_start();
	
	// Faz a requisição do arquivo de manipulação de dados.
	require_once('conect.php');
	require_once('Controller/impostos_calcular_aliquotas-controller.php');
	
	// faz a requisição do arquivo de controle.
	$impCalcAliq = new ImpostosCalcularAliquotas();
	
	$impCalcAliq->ajaxAliquotasImpostos();
	
	// Para a execução.
	die();
}

require_once('header_restrita.php');
require_once('Controller/impostos_calcular_aliquotas-controller.php');

$impCalcAliq = new ImpostosCalcularAliquotas();

// Verifica se o cnae esta tendo retorno do cnae.
$fatorR = $impCalcAliq->VerificaFatorR($_SESSION['id_empresaSecao']);

?>

<div class="principal minHeight">

	<h1>Alíquotas de Impostos</h1>

	<div id="form_aliquotas" style="margin-bottom:5px;">

		<div style="margin:0 0px 0px 0;float: left;padding:5px 0 0 0">
			Com a entrada em vigor da&nbsp;<strong>Lei da Transparência Fiscal</strong>, agora todas as empresas precisam discriminar em suas notas fiscais os valores ou alíquotas dos impostos embutidos em seu preço. Para ajudá-lo nesse processo, o Contador Amigo criou este aplicativo.  Selecione a faixa de faturamento, clique em <strong>calcular impostos</strong> e você conhecerá as alíquotas incidentes para cada atividade de sua empresa. Em alguns casos será necessário informar também o faturamento e a folha de salários.<br><br>
		</div>

		<div style="clear: both; height: 10px"></div>

		<div style="margin:0 0px 0px 0;float: left;padding:0px 0 0 0">

			<div>
				<span style="display: inline-block;">Valor da nota fiscal:&nbsp;&nbsp;&nbsp;</span>
					<input style="width:180px;" type="text" class="current" id="valorNotaFiscal" value="" placeholder="">
				<br/><br/>
			</div>
				
			<div>
				A nota foi emitida para: &nbsp;&nbsp;
				<input class="emissaoNota" name="emissaoNota" type="radio" value="brasil" checked/> Brasil &nbsp;&nbsp;
				<input class="emissaoNota" name="emissaoNota" type="radio" value="exterior" /> Exterior
				<br/><br/>
			</div>
			
			<div>
				A empresa tem mais de 1 ano? &nbsp;&nbsp;
				<input class="periodoEmpresa" id="periodoEmpresaSim" name="periodoEmpresa" type="radio" value="sim">
				Sim &nbsp;&nbsp;
				<input class="periodoEmpresa" id="periodoEmpresaNao" name="periodoEmpresa" type="radio" value="nao">
				Não
				<br/><br/>
			</div>
			
			<div id="empresaMenor" style="display: none;">
				Empresa aberta em&nbsp;&nbsp;&nbsp;
				<input class="campoData" id="dataInicioEmpresa" name="dataInicioEmpresa" type="text">&nbsp;&nbsp;&nbsp;
				<button id="btProsseguirMenor" type="button">Prosseguir</button>
				<br/><br/>
			</div>
							
			<div id="empresaMaior" style="display: none;">
				Emitida em&nbsp;&nbsp;&nbsp;
				<select name="mes" id="selectMes">
					<option value="">Selecione</option>
					<option value="0">Janeiro</option>
					<option value="1">Fevereiro</option>
					<option value="2">Março</option>
					<option value="3">Abril</option>
					<option value="4">Maio</option>
					<option value="5">Junho</option>
					<option value="6">Julho</option>
					<option value="7">Agosto</option>
					<option value="8">Setembro</option>
					<option value="9">Outubro</option>
					<option value="10">Novembro</option>
					<option value="11">Dezembro</option>
				</select>&nbsp;&nbsp;&nbsp;
				de&nbsp;&nbsp;&nbsp;
				<select name="ano" id="selectAno">
					<?php 
						
						// Pega ano atual e anterior.
						$anoAtual = date('Y'); 
						$anoAnterior = $anoAtual - 1;
					
						$optionAno = "<option value='".$anoAtual."'>".$anoAtual."</option>";
						
						// Não Permite ano menor que 2018, pois foi realizada alteração nos valores das aliquota. 
						if($anoAnterior >= '2018'){
							$optionAno .= "<option value='".$anoAnterior."'>".$anoAnterior."</option>";
						}
					
						echo $optionAno;
					?>
				</select>&nbsp;&nbsp;&nbsp;
				<button id="btProsseguir" type="button">Prosseguir</button>
				<br/><br/>
			</div>
			<div id="boxImputs" style="display: none;">
				<div>		
					<span id="text_faturamentoUltimos" style="display: inline-block;">Faturamento dos últimos 12 meses:</span>
					R$ <input style="width:180px;" type="text" class="current" id="faturamento12meses" value="" placeholder="">
					<br/><br/>
				</div>

			<?php
				// Mostra o campo para informar a folha de sálario dos ultimos 12 meses. 
				if($fatorR): 
			?>			
				<div>		
					<span id="text_folhaSalario" style="display: inline-block;">Folha de sálario dos ultimos 12 meses:</span>
					R$ <input style="width:180px;" type="text" class="current" id="folhaSalario12meses" value="" placeholder="">
					<br/><br/>
				</div>

			<?php endif; ?>

				<div style="margin-top: 15px;">
					<input type="button" value="Calcular Alíquotas" name="btCalculaAliquotas" id="btCalculaAliquotas">
					<br/><br/>
				</div>
			</div>
		</div>

		<div style="clear: both; height: 10px"></div>
		
		<div id="boxAliquota" style="display: none;">
			
			Copie (Ctrl + C) a alíquota da atividade referente ao serviço prestado e cole (Ctrl + V) no campo descrição da sua nota fiscal eletrônica de serviços. Empresas do comércio e indústria normalmente já terão os valores dos impostos automaticamente gerados no sistema de emissão da nota.<br>

			<div style="clear: both; height: 30px"></div>

			<div class="tituloVermelhoLight" style="margin-bottom: 20px;">Alíquotas aproximadas por atividade</div>
		
			<div id="lista_aliquotas"></div>
		</div>		
		<!-- FIM DA DIV DA LISTA DAS ATIVIDADES -->
	</div>
</div>
<script>

	$( document ).ready(function() {
		
		
		$('input[name="periodoEmpresa"]').change(function () {
			$('#boxImputs').hide();
			if ($('input[name="periodoEmpresa"]:checked').val() == "sim") {
				$('#empresaMaior').show();
				$('#empresaMenor').hide();
			} else {
				$('#empresaMenor').show();
				$('#empresaMaior').hide();
			}
		});
		
		
		$("#btProsseguirMenor").click(function(){
						
			var mesAnoMenor = QuantidadeDeMes();		   
		   
		   if(mesAnoMenor < 0){
			   alert('Data invalida');
		   } else if(mesAnoMenor > 11){
			   alert('Data invalida');
		   } else {
			   CalculaPeriodo2();
		   }			
			
		});
		
		function QuantidadeDeMes(){
			//Fazer o calculo da empresa menor que um ano.
			var data = $("#dataInicioEmpresa").val().split('/');
			var date1 = data[1]+'/'+data[0]+'/'+data[2];
			
			var date1 = new Date(date1);
		    var date2 = new Date();

		    var ano1 = date1.getFullYear();
		    var ano2 = date2.getFullYear();
		    var mes1 = date1.getMonth();
		    var mes2 = date2.getMonth();
			
			var mesAnoMenor = ((mes2+12*ano2)-(mes1+12*ano1))-1;
			
			if(mesAnoMenor == 0){
				mesAnoMenor = 1;
				return mesAnoMenor;
			} else {
				return mesAnoMenor;
			}
		}
		
		
		$("#btProsseguir").click(function() {
			
			/**
			 * Para o cálculo serão considerados, respectivamente, os montantes pagos nos doze meses anteriores ao período de apuração
			 */
			
			// Pega o mes selecionado.
			var mesRef = $("#selectMes").val();
			var anoRef = $("#selectAno").val();	
			
			if(mesRef.length > 0){
				//Chama o metodo para realizar o calculo do periodo.
				CalculaPeriodo1(mesRef, anoRef);
		    } else {
				
				$('#boxImputs').hide();
				
				alert('Selecione o mês de emissão da nota fiscal');
			}
			
			

		});
		
		function CalculaPeriodo1(mesRef, anoRef){
			/**
			 * Para o cálculo serão considerados, respectivamente, os montantes pagos nos doze meses anteriores ao período de apuração
			 */
			var date = new Date();			
			
			// mostra box.
			$('#boxImputs').show();
			$('#lista_aliquotas').html('');
			$('#boxAliquota').hide();

			date.setFullYear(anoRef);

			// Informa o mês de referencia da Nota fiscal.
			date.setMonth(mesRef);

			// Informa o mês de referencia da Simples Nacional.
			//date.setMonth(date.getMonth() - 1);

			// Pega a data de referencia para definir a data final do período de 12 meses.
			var date2 = new Date();

			date2.setFullYear(anoRef);

			date2.setMonth(mesRef);

			// Pega a data final do periodo de 12 meses.
			date2.setMonth(date2.getMonth() - 1);

			// Pega o mes final do periodo de 12 meses.
			var mes2 = date2.getMonth();

			// Pega o ano final do periodo de 12 meses.
			var ano2 = date2.getFullYear();

			// Pega um ano em segundos.
			var calcUmAnoSecundos = 365.24 * 24 * 60 * 60 * 1;

			// Subtai um ano da data informada
			date.setSeconds(date.getSeconds() - calcUmAnoSecundos);

			// Pega o mês inicial do periodo de 12 meses.
			var mes1 = date.getMonth();

			// Pega o ano inicial do periodo de 12 meses.
			var ano1 = date.getFullYear();

			$('#text_faturamentoUltimos').html('Informe o faturamento bruto de '+PegaNomeMes(mes1)+'/'+ano1+' a '+PegaNomeMes(mes2)+'/'+ano2+'&nbsp;&nbsp;&nbsp;');
			$('#text_folhaSalario').html('Informe a folha pagamento de '+PegaNomeMes(mes1)+'/'+ano1+' a '+PegaNomeMes(mes2)+'/'+ano2+' (inclui pró-labore, sálarios e pagamento a autônomos)&nbsp;&nbsp;&nbsp;');				
			 
		}
		
		function CalculaPeriodo2(){
			/**
			 * Para o cálculo serão considerados, respectivamente, os montantes pagos nos doze meses anteriores ao período de apuração
			 */
			
			var data = $("#dataInicioEmpresa").val().split('/');
			var date1 = data[1]+'/'+data[0]+'/'+data[2];
			
			var date1 = new Date(date1);		    

		    var anoRef = date1.getFullYear();		    
		    var mesRef = date1.getMonth();		    
						
			var date = new Date();			
			
			// mostra box.
			$('#boxImputs').show();
			$('#lista_aliquotas').html('');
			$('#boxAliquota').hide();

			date.setFullYear(anoRef);

			// Informa o mês de referencia da Nota fiscal.
			date.setMonth(mesRef);

			// Informa o mês de referencia da Simples Nacional.
			//date.setMonth(date.getMonth() - 1);

			// Pega a data de referencia para definir a data final do período de 12 meses.
			var date2 = new Date();

			// Pega a data final do periodo de 12 meses.
			date2.setMonth(date2.getMonth() - 1);

			// Pega o mes final do periodo de 12 meses.
			var mes2 = date2.getMonth();

			// Pega o ano final do periodo de 12 meses.
			var ano2 = date2.getFullYear();

			// Pega o mês inicial do periodo de 12 meses.
			var mes1 = date.getMonth();

			// Pega o ano inicial do periodo de 12 meses.
			var ano1 = date.getFullYear();

			$('#text_faturamentoUltimos').html('Informe o faturamento bruto de '+PegaNomeMes(mes1)+'/'+ano1+' a '+PegaNomeMes(mes2)+'/'+ano2+'&nbsp;&nbsp;&nbsp;');
			$('#text_folhaSalario').html('Informe a folha pagamento de '+PegaNomeMes(mes1)+'/'+ano1+' a '+PegaNomeMes(mes2)+'/'+ano2+' (inclui pró-labore, sálarios e pagamento a autônomos)&nbsp;&nbsp;&nbsp;');
		}
		
		// Método criado para retorna o nome do mês seleciona. 
		function PegaNomeMes(mesNumero) {

			var nomeMes = new Array();
			
			nomeMes[0] = "janeiro";
			nomeMes[1] = "fevereiro";
			nomeMes[2] = "marco";
			nomeMes[3] = "abril";
			nomeMes[4] = "maio";
			nomeMes[5] = "junho";
			nomeMes[6] = "julho";
			nomeMes[7] = "agosto";
			nomeMes[8] = "septembro";
			nomeMes[9] = "outubro";
			nomeMes[10] = "novembro";
			nomeMes[11] = "dezembro";
			
			// Retorna p nome do mês de acordo com o numero informado.
			return 	nomeMes[mesNumero];	
		}
		
		$('#btCalculaAliquotas').click(function(){
			
			if(validation()){				
				PegaAliquotaImposto();
			}
		});		

		
		function validation(){
			
			if($('#valorNotaFiscal').val().length == 0) {
				alert('Informe o valor da nota físcal.');
				return false;				
			}
			
			if($('#faturamento12meses').val().length == 0) {
				
				alert('Informe o faturamento dos últimos 12 meses.');
				return false;
			}
			
			<?php if($fatorR):?>;
				if($('#folhaSalario12meses').val().length == 0) {
					alert('Informe a folha de sálario dos ultimos 12 meses');
					return false;
				}
			<?php endif; ?>
						
			return true;
		}
		
		function PegaAliquotaImposto(){
			
			var mesAnoMenor = '';
			var periodoEmpresa = '';
			var emissaoNota = '';
			var faturamentoEmp = $('#faturamento12meses').val();
			var valorNota = $('#valorNotaFiscal').val();
			var faturamentoEfetivo = $('#faturamento12meses').val();
			var aberturaStatus = 'maior';
			
			$('.periodoEmpresa').each(function(){
				if( $(this).is(':checked') && $(this).val() == 'nao') {
				  
			  		mesAnoMenor = QuantidadeDeMes();
				  	aberturaStatus = 'menor';
				  
				}				
			});
			
			
		<?php if($fatorR):?>;
			var folhaSalario = $('#folhaSalario12meses').val();
		<? else:?>
			var folhaSalario = 0;
		<?php endif; ?>
				
			$('.emissaoNota').each(function(){
				if( $(this).is(':checked') ) {
				  emissaoNota = $(this).val();
				}				
			});
			
			$.ajax({
				type: 'POST',
				url: 'impostos_aliquotas_calcula.php',
				data: {ajaxAliquotaImpostos:'', faturamentoEmp:faturamentoEmp, folhaSalario:folhaSalario, valorNota:valorNota, emissaoNota:emissaoNota, faturamentoEfetivo:faturamentoEfetivo, aberturaStatus:aberturaStatus, mesAnoMenor:mesAnoMenor},
				dataType: 'json',
				success: function(data) {
					
					$('#lista_aliquotas').html('');
					
					$('#boxAliquota').show();
					
					data.forEach(function(element) {
						// console.log(element);
						$('#lista_aliquotas').append(element);
					});
				},
				error: function() {
					alert("Entre em contato com o help desk");
				},
				beforeSend: function() {
				},
				complete: function() {
				}
			});
			
		}		
		

	});

</script>

<?php 
	require_once('rodape.php');
?>