<?php include 'header_restrita.php' ?>

<?php

$_SESSION['recolhe_cprb'] = '';
$_SESSION['e_empreitada'] = '';

$_SESSION['compensacao'] = '';
$_SESSION['trabalhadores_sefip'] = "";
$_SESSION['tomadores_sefip'] = "";
$_SESSION['anexo4'] = '';
$_SESSION['anexo_s'] = '';
$_SESSION['e_anexo_IV'] = '';

// pega os cnaes da empresa
$sql_cnaes = "SELECT * FROM dados_da_empresa_codigos WHERE id='" . $_SESSION["id_empresaSecao"] . "'";

$resultado_cnaes = mysql_query($sql_cnaes)
or die (mysql_error());

$checa_cnaes_anexoIV = mysql_query("SELECT * FROM dados_da_empresa_codigos ec INNER JOIN cnae c ON ec.cnae = c.cnae WHERE 1=1 AND ec.id = '" . $_SESSION["id_empresaSecao"] . "' AND ( c.anexo = 'IV' OR (REPLACE(REPLACE(REPLACE(c.cnae,'.',''),'-',''),'/','') IN  ('4321500','4322301','4322303','4330401','4330402','4330403','4330404','4330405','4330499')))")
or die (mysql_error());
?>

<div class="principal minHeight">

  <span class="titulo">Impostos e Obrigações</span><br /><br />

  <div style="margin-bottom:20px" class="tituloVermelho">Envio da Gfip</div>

<?
// ZERANDO VARIAVEIS DE SESSAO QUE SERÃO ATUALIZADAS VIA AJAX A MEDIDA QUE O USUARIO VAI RESPONDENDO AS PERGUNTAS
$_SESSION['SEFIP_empreitada'] = "";
$_SESSION['SEFIP_anexoIVeoutros'] = "";
$_SESSION['SEFIP_pgdestino'] = "";
$_SESSION['SEFIP_arrAnexos'] = "";
?>

	<script>
			var gambiarra_servicos_advocaticios = false;
			var lista_anexo_s_IV = new Array();
			var p_vez = "";
			var movimento = "";
			var atraso = "";
			var inss = "";
			var empreitadaPasso1 = false;
			
$(document).ready(function(e) {
	
			$("input[id^='anexo_atividade_']").click(function(){ // estes são os checkbox com as atividades da empresa
				if($(this).attr('checked')){
					if($('#' + $(this).attr('divid')).html()){
						$('#' + $(this).attr('divid')).css('display','block');
					}
				}else{  
					if($('#' + $(this).attr('divid')).html()){// se o checkbox for desmarcado, limpa os radio buttons e a variável que guarda a informação de vinculação à empreitada
						$('#' + $(this).attr('divid')).css('display','none');
						var $this = $(this);
						$('#' + $this.attr('divid')).find("input[name^='ativ_']:checked").attr('checked',false);
						empreitadaPasso1 = false;
						$.ajax({
							url: 'SEFIP_config.php',
							data: 'empreitada=',
							dataType:"text",
							type:"POST",
							success: function(response){
							}
						});

					}
				}
			});
	
			$('#checa_gfip').click(function(e){
				e.preventDefault();
				
				var status = true;

				$('.inputCheckBox').each(function(){

					if($(this).is(':checked')) {
						status = false;
					}
				});

				if(status) {
					alert('Selecione pelo menos uma das atividades.');
				} else {
					acaoButton();
				}
				
			});
			
			function acaoButton() {
			
				var arr_anexos = new Array();
				var bloqueia_gfip = 0;
				var atividades_advocaticias = false;
				
				// //Se for instalação e manuntenção elétrica e estiver vinculada a uma emppreitada, exibe um alert de que o contador amigo nao atende esse tipo de empresa, e não permite a execução dorestante do script
				// if( $("#bloquear_continuacao").attr("checked") ){
				// 	alert("Impossível prosseguir!\n\nInfelizmente o Contador Amigo ainda não atende empresas que fazem serviços de cessão de mão de obra.\nPor favor entre em contato com nosso Help Desk.")
				// 	return;
				// }
				


				$.each($("input[id^='anexo_atividade_']"),function(indice,valor){
					var $this = $(this);
					if($this.attr('checked')){
//					alert($this.attr('divid'));
						if($this.attr('divid') == '6911701'){
							atividades_advocaticias = true;//$this.attr('divid');
						}
//						alert($this.val());
						if($this.val() != 's' && $this.val() != 'x'){
							arr_anexos.push($this.val());
						}else{
							if($('#' + $this.attr('divid')).find("input[name^='ativ_']:checked").val()){
//								alert('1');
								var AnexoAtividade = $('#' + $this.attr('divid')).find("input[name^='ativ_']:checked").val();
								arr_anexos.push(AnexoAtividade);

								if(AnexoAtividade == 'IV'){
									empreitadaPasso1 = true;
									$.ajax({
										url: 'SEFIP_config.php',
										data: 'empreitada=s',
										dataType:"text",
										type:"POST",
										cache: false,
										success: function(response){
										}
									});
								}
							}else{
								//Verifica se esta na lista de Anexo S que podem ser anexo IV, caso nao possa ser anexo IV, nao exige a resposta da pergunta da empreitada - MAL
								var aux_anexo_IV = false;
								for (var i = 0; i < lista_anexo_s_IV.length; i++) {
									aux = lista_anexo_s_IV[i];
									// console.log(aux);
									// console.log($(this).attr("divid"));

									if (aux === $(this).attr("divid") ){
										aux_anexo_IV = true;
											}

								};

								if( aux_anexo_IV === true ){
									alert('Selecione uma opção dentro da Atividade selecionada!');
									$('#' + $this.attr('divid')).find("input[name^='ativ_']").focus();
									bloqueia_gfip = 2;
									return false;
								}

								//FIM
								//ANTES ESTAVA DESSA FORMA
								// alert('Selecione uma opção dentro da Atividade selecionada!');
								// $('#' + $this.attr('divid')).find("input[name^='ativ_']").focus();
								// bloqueia_gfip = 2;
								// return false;
							}
						}


						//Verifica se é anexo S que pode ser anexo IV, se estiver na lista, verifica se as perguntas foram respondidas - MAL
						var aux_anexo_IV = false;
						for (var i = 0; i < lista_anexo_s_IV.length; i++) {
							aux = lista_anexo_s_IV[i];
							// console.log(aux);
							
							if( $(this).attr("divid") === '6911701' ){
								gambiarra_servicos_advocaticios = true;
							}

							if (aux === $(this).attr("divid") ){
								aux_anexo_IV = true;
								console.log($(this).attr("divid"));
							}

						};
						if( aux_anexo_IV === true ){
							//Verifica se respondeu a pergunta da CPRB - MAL
							var recolhe_cprb_aux = $('#' + $this.attr('divid')).find(".recolhe_cprb input");
							var opcao_recolhe_cprb_sim = $(recolhe_cprb_aux[0]);
							var opcao_recolhe_cprb_nao = $(recolhe_cprb_aux[1]);
							var recolhe_cprb_ajax;



							if( $(opcao_recolhe_cprb_sim).attr('checked') != true && $(opcao_recolhe_cprb_nao).attr('checked') != true && gambiarra_servicos_advocaticios === false){
								alert("Responda todas as perguntas para prosseguir!");
								bloqueia_gfip = 2;
								return;
							}
							//Verifica se marcou sim ou nao para recolhe CRPB
							if( $(opcao_recolhe_cprb_sim).attr('checked') === true ){
								recolhe_cprb_ajax = true;
							}
							if( $(opcao_recolhe_cprb_nao).attr('checked') === true ){
								recolhe_cprb_ajax = false;
							}

							var e_empreitada_aux = $('#' + $this.attr('divid')).find(".abrir_cprb_input");
							var e_empreitada_aux_sim = $(e_empreitada_aux[0]);
							var e_empreitada_aux_nao = $(e_empreitada_aux[1]);
							var e_empreitada_ajax;
							//Verifica se marcou Sim ou nao para empreitada
							if( $(e_empreitada_aux_sim).attr('checked') === true ){
								e_empreitada_ajax = true;
							}
							if( $(e_empreitada_aux_nao).attr('checked') === true ){
								e_empreitada_ajax = false;
							}
							//Salva as opções escolhidas para recolhe CPRB e se E EMPREITADA
							var id_anexo_aux = $this.attr('divid');

							if( gambiarra_servicos_advocaticios === true ){
								recolhe_cprb_ajax = false;
								e_empreitada_ajax = false;
							}

							$.ajax({
								url: 'SEFIP_config.php',
								data: 'recolhe_cprb='+recolhe_cprb_ajax+'&e_empreitada='+e_empreitada_ajax+'&anexo_s='+id_anexo_aux,
								dataType:"text",
								type:"POST",
								cache: false,
								success: function(response){
								}
							});
						}//FIM - MAL

					}
				});


				$.ajax({
					url: 'SEFIP_config.php',
					data: 'arrAnexos='+arr_anexos,
					dataType:"text",
					type:"POST",
					cache: false,
					success: function(response){
					}
				});
				
				if(bloqueia_gfip != 2){
//													alert('3');

					for(i=0; i < arr_anexos.length; i++){
						if(arr_anexos[i] == 'IV'){
//															alert('4');
							if(atividades_advocaticias){
								empreitadaPasso1 = false;
								bloqueia_gfip = 0;
								$.ajax({
									url: 'SEFIP_config.php',
									data: 'empreitada=n',
									dataType:"text",
									type:"POST",
									cache: false,
									success: function(response){
									}
								});
							}
							else{
								bloqueia_gfip = 0;
							}
						}
					}
				}
				
				if(
					$.inArray('IV',arr_anexos) >=0 
					&& arr_anexos.length > 0 
					&& 
						($.inArray('I',arr_anexos) >= 0
						|| $.inArray('II',arr_anexos) >= 0
						|| $.inArray('III',arr_anexos) >= 0
						|| $.inArray('V',arr_anexos) >= 0
						|| $.inArray('VI',arr_anexos) >= 0
						)
					){
					$.ajax({
						url: 'SEFIP_config.php',
						data: 'IVeoutros=1',
						dataType:"text",
						type:"POST",
						cache: false,
						success: function(response){
						}
					});

					alert("Impossível prosseguir. Por favor contate nosso help desk.");
					return;



				}

//				alert(bloqueia_gfip);
//				return false;
				
				if(bloqueia_gfip == 1){ // FOI SELECIONADA UMA ATIVIDADE DO ANEXO IV
					// alert("aki");
//					if(empreitadaPasso1){
						$('#aviso_cessao_mao_de_obra').css('display','block');
						$('#aviso_empreitada').css('display','none');
						$('#aviso_matriculaCEI').css('display','none');
						$('#aviso_responsavelMatriculaCEI').css('display','none');
						$('#aviso_remuneracao').css('display','none');
	
						$('#aviso_bloqueio').css('display','none');
						$('#aviso_ok').css('display','none');
						$('#perguntas').css('display','none');
//					}else{
//						escolhe_destino();
//					}
				}else if(bloqueia_gfip == 0){
						
//						$('#aviso_cessao_mao_de_obra').css('display','none');
//						$('#aviso_empreitada').css('display','none');
//						$('#aviso_matriculaCEI').css('display','none');
//						$('#aviso_responsavelMatriculaCEI').css('display','none');
//						$('#aviso_remuneracao').css('display','none');
//
//						$('#aviso_bloqueio').css('display','none');
//						$('#aviso_ok').css('display','block');
//						$('#perguntas').css('display','none');
					
					
					escolhe_destino();
					
				}

			}
	
			$('#btVoltar').click(function(e){
				e.preventDefault();
				$('div[id^="aviso"]').css('display','none');
				$('#perguntas').css('display','none');
				var divId = $(this).data('div');
				$('#' + divId).css('display','block');
				console.log('#' + divId);
				console.log(this);
			});
		
		
			// resposta 1
			$("div[id^='simDiv']").hover(function() {
					$(this).css("background-color", "#a61d00");
					}
				, function() {
					$(this).css("background-color", "#024a68");
					}
				).bind('click',function(event){
					event.preventDefault();
					switch($(this).attr('id')){
						case 'simDiv1':
							$('div[id^="aviso"]').css('display','none');
							$('#perguntas').css('display','none');
	
							$('#aviso_bloqueio').find('.opcao_simples').html('Infelizmente o Contador Amigo ainda não atende empresas que fazem serviços de cessão de mão de obra.<br /><br />Por favor entre em contato com nosso <a href="suporte.php">Help Desk</a>.');
							$('#aviso_bloqueio').find('#btVoltar').data('div','aviso_cessao_mao_de_obra');
							$('#aviso_bloqueio').css('display','block');
						break;
						case 'simDiv2':
							$('div[id^="aviso"]').css('display','none');
							$('#perguntas').css('display','none');
							// variavel de empreitada é marcada como "SIM"
							$.ajax({
								url: 'SEFIP_config.php',
								data: 'empreitada=s',
								dataType:"text",
								type:"POST",
								cache: false,
								success: function(response){
								}
							});

							$('#aviso_matriculaCEI').css('display','block');

						break;
						case 'simDiv3':
							$('div[id^="aviso"]').css('display','none');
							$('#perguntas').css('display','none');

							$('#aviso_responsavelMatriculaCEI').css('display','block');
							
						break;
						case 'simDiv4':
							$('div[id^="aviso"]').css('display','none');
							$('#perguntas').css('display','none');

							$('#aviso_bloqueio').find('.opcao_simples').html('Infelizmente o Contador Amigo ainda não atende empresas com empreitada própria.<br /><br />Por favor entre em contato com nosso <a href="suporte.php">Help Desk</a>.');
							$('#aviso_bloqueio').find('#btVoltar').data('div','aviso_responsavelMatriculaCEI');
							$('#aviso_bloqueio').css('display','block');
						break;
						case 'simDiv5':
							$('div[id^="aviso"]').css('display','none');
							$('#perguntas').css('display','none');

							$('#aviso_bloqueio').find('.opcao_simples').html('Infelizmente o Contador Amigo ainda não atende empresas cujos trabalhadores desempenham tarefas para obras específicas da construção civil.<br /><br />Por favor entre em contato com nosso <a href="suporte.php">Help Desk</a>.');
							$('#aviso_bloqueio').find('#btVoltar').data('div','aviso_responsavelMatriculaCEI');

							$('#aviso_bloqueio').css('display','block');
						break;
					}
				});	

			$("div[id^='naoDiv']").hover(function() {
					$(this).css("background-color", "#a61d00");
					}
				, function() {
					$(this).css("background-color", "#024a68");
					}
				).bind('click',function(event){
					event.preventDefault();
					switch($(this).attr('id')){
						case 'naoDiv1':
							$('div[id^="aviso"]').css('display','none');
							$('#perguntas').css('display','none');
							if(!empreitadaPasso1){ // checa se foi selecionado a opção "vinculada a empreitada" na pergunta inicial
								$('#aviso_empreitada').css('display','block');
							}else{
								$('#aviso_matriculaCEI').css('display','block');
							}
						break;
						case 'naoDiv2':
							$('div[id^="aviso"]').css('display','none');
							$('#perguntas').css('display','none');
							
							// variavel de empreitada é marcada como "NAO"
							$.ajax({
								url: 'SEFIP_config.php',
								data: 'empreitada=n',
								dataType:"text",
								type:"POST",
								cache: false,
								success: function(response){
								}
							});
							
							escolhe_destino();
							//$('div[id^="aviso"]').css('display','none');
							//$('#perguntas').css('display','none');

//							//$('#aviso_ok').css('display','block');
							//$('#aviso_matriculaCEI').css('display','block');
						break;
						case 'naoDiv3':
							$('div[id^="aviso"]').css('display','none');
							$('#perguntas').css('display','none');
							escolhe_destino();
							//$('#aviso_responsavelMatriculaCEI').css('display','block');
						break;
						case 'naoDiv4':
							$('div[id^="aviso"]').css('display','none');
							$('#perguntas').css('display','none');

							$('#aviso_remuneracao').css('display','block');
						break;
						case 'naoDiv5':
							$('div[id^="aviso"]').css('display','none');
							$('#perguntas').css('display','none');

							$('#aviso_ok').css('display','block');
						break;
						

					}
				});	
			
			
			$('#btContinuarFinal').bind('click',function(e){
				e.preventDefault();
				switch($("input[name='rdbRenumeracao']:checked").val()){
					case undefined:
						alert('Selecione uma opção!');
						$("input[name='rdbRenumeracao']").eq(0).focus();
						return false;
					break;
					case '0':
						escolhe_destino();
					break;
					case '1':
						$('div[id^="aviso"]').css('display','none');
						$('#perguntas').css('display','none');

						$('#aviso_bloqueio').find('.opcao_simples').html('Infelizmente o Contador Amigo ainda não atende empresas cujos trabalhadores desempenham tarefas para obras específicas da construção civil.<br /><br />Por favor entre em contato com nosso <a href="suporte.php">Help Desk</a>.');
						$('#aviso_bloqueio').find('#btVoltar').data('div','aviso_remuneracao');

						$('#aviso_bloqueio').css('display','block');
					break;
				}
			});

			$("#voltar_gfip").click(function() {
				$('#aviso_ok').css('display','block');
				$('#perguntas').css('display','none');
			});

			
			$('#btContinuar').bind('click',function(e){
				e.preventDefault();
				if( movimento === '' || p_vez === '' ){
					alert("Responda todas as perguntas para continuar.");
					return;
				}
<? if(mysql_num_rows($checa_cnaes_anexoIV) > 0) { 

//echo 6911701
?>
				if(movimento == 'nao'){
					escolhe_destino();
				} else {
					$('div[id^="aviso"]').css('display','none');
					$('#perguntas').css('display','block');
				}
<? } else { ?>
				escolhe_destino();
<? } ?>
			});
			



			// resposta 1
			$("#sim1").click(function(e) {
				e.preventDefault();
				$("#sim1").css("background-color", "#a61d00");
				$("#nao1").css("background-color", "#024a68");
				p_vez = "sim"});	
			
			$("#nao1").click(function(e) {
				e.preventDefault();
				$("#nao1").css("background-color", "#a61d00");
				$("#sim1").css("background-color", "#024a68");
				p_vez = "nao"});
			
			//resposta 2
			$("#sim2").click(function(e) {
				e.preventDefault();
				$("#sim2").css("background-color", "#a61d00");
				$("#nao2").css("background-color", "#024a68");
				movimento = "sim";
				});	
			
			$("#nao2").click(function(e) {
				e.preventDefault();
				$("#nao2").css("background-color", "#a61d00");
				$("#sim2").css("background-color", "#024a68");
				movimento = "nao";
				});
				
    });


			
	function escolhe_destino() {
			//alert (p_vez + '-' + movimento + '-' + atraso + '-' + inss);

			//Envio Primeira Vez 
			if(p_vez == 'sim' && movimento == 'sim' ){
				$.ajax({
					url: 'SEFIP_config.php',
					data: 'pgDestino=gfip_orientacoes_primeira_vez_normal.php',
					dataType:"text",
					type:"POST",
					cache: false,
					success: function(response){
						window.location = "sefip_folha.php"; 
					}
				});
				return false;
			}
			if(p_vez == 'sim' && movimento == 'nao' ){
//				$.ajax({
//					url: 'SEFIP_config.php',
//					data: 'pgDestino=gfip_sem_movimento_primeira_vez.php',
//					dataType:"text",
//					type:"POST",
//					success: function(response){
//						window.location = "sefip_folha.php"; 
//					}
//				});
				window.location = "gfip_sem_movimento_primeira_vez.php"; 
				return false;
			}
			
			//Envio Segunda vez em diante 
			if(p_vez == 'nao' && movimento == 'sim' ){
				$.ajax({
					url: 'SEFIP_config.php',
					data: 'pgDestino=gfip_orientacoes_envio_normal.php',
					dataType:"text",
					type:"POST",
					cache: false,
					success: function(response){
						window.location = "sefip_folha.php"; 
					}
				});
				return false;
			}

			if(p_vez == 'nao' && movimento == 'nao' ){
//				$.ajax({
//					url: 'SEFIP_config.php',
//					data: 'pgDestino=gfip_sem_movimento.php',
//					dataType:"text",
//					type:"POST",
//					success: function(response){
//						window.location = "sefip_folha.php"; 
//					}
//				});
				window.location = "gfip_sem_movimento.php"; 
				return false;
			}
			
			else {alert ('Por favor, responda todas as perguntas')}
			
	};
	
		
  </script>


    <div id="aviso_ok">
        
       
        <div style="margin-bottom:20">
          <p>A Gfip é uma declaração na qual você informa à Receita Federal quanto retirou de pró-labore e os salários pagos no período. Com base nessa informação, será calculado o recolhimento do INSS, que corresponde a 11% do valor declarado. Assim,<strong> antes  de iniciar o processo de geração e envio da Gfip, você precisa primeiro cadastrar o(s) pró-labore(s) do sócio-proprietário e pagamentos efetuados a autônomos no período</strong>. Faça isso na <strong>aba Pagamentos</strong> do menu acima.<br>
          <br>
          <span class="destaqueAzul">ADQUIRA UM CERTIFICADO DIGITAL</span><br>
          <br>
          Para transmitir a Gfip <b>é indispensável dispor de um certificado digital</b>. 
          Assinantes do Contador Amigo podem adquiri-lo pela Valid Certificadora com um super desconto: apenas R$ 189,75, em 3 x sem juros. <a href="http://www.validcertificadora.com.br/e-CNPJ-A1.htm/388C546B-98DA-4A0E-B8F3-89BBA8FB5FEE/RD007797" target="_blank">Solicite agora mesmo o seu</a>.
          <!-- Assinantes do Contador Amigo podem adquirir o certificado modelo E-CNPJ A1 da Valid Certificadora com um super desconto: <strong>apenas R$ 140</strong>  (o preço normal é R$ 224). Para ter direito a esta promoção, <a data-cke-saved-href="https://minha_conta.php" href="https://minha_conta.php/">confirme agora sua assinatura</a> e selecione a opção <strong>assinatura mensal + certificado.</strong> -->
          <br>
          <br>

 
          <span class="destaqueAzul">CONFIGURE SEU MICRO</span><br>
          <br>         
  Para enviar a Gfip, você precisa primeiro baixar o programa Sefip da Caixa, configurar o plugin do Java e o navegador Internet Explorer.  Siga estas instruções:          </p>
          <ul>
            <li><a href="https://www.contadoramigo.com.br/configuracao_sefip.php">Download e configuração do Sefip</a></li>
  <li><a href="https://www.contadoramigo.com.br/java_tutorial.php">Configuração do Java</a></li>
  <li><a href="https://www.contadoramigo.com.br/ie_tutorial.php">Configuração do Internet Explorer</a></li>
  <li><span style="margin-bottom:20px"><a href="avast_avg_tutorial.php">Configurar o antivírus</a></span> (somente se necessário)</li>
          </ul>
  
  Se mesmo assim não funcionar, entre em contato com o <strong>suporte da Caixa pelos telefones 3004 1104 (capitais) ou 0800 726 0104</strong> (demais regiões). <br>
  <br>
<span class="destaqueAzul"><br>
  OBSERVAÇÕES IMPORTANTES</span>
          <ul>
        <li><strong>Gfip sem movimento:</strong> se a sua empresa não teve faturamento no período e você não deseja declarar retirada de pró-labore, responda NÃO à pergunta &quot;Pretende declarar retiradas de pró-labore ou salários, no período?&quot;, no quadro abaixo. Se não declarar pró-labore, você não fará recolhimento de INSS e, consequentemente, este período não será contado como tempo de contribuição para sua aposentadoria.<br />
          <br />
        </li>
        <li><strong>Gfip competência 13:</strong> refere-se ao 13º salário e é declarada em janeiro (portanto em janeiro serão enviadas 2 Gfips). Como sócios e autônomos não têm 13º, então você deverá enviar, na ocasião, uma Gfip sem movimento. Para isso, responda "NÃO" à pergunta  &quot;Pretende declarar retiradas de pró-labore ou salários, no período?&quot;, no quadro a seguir.</li>
        </ul>
      </div>
    
	    <div class="quadro_passos_sem_largura" style="padding:40px;">

	    	<div style="display: block;">
        
                <div  style="margin-bottom:20px">
                    <div class="perguntas_principais_quadros" style="float:left; width:600px">Está enviando a Gfip pela primeira vez com o Contador Amigo,  formatou seu micro ou está usando um novo computador?</div>
                    <div class="caixa_bt_opcoes_quadro" style="margin:0; float:right;">
                        <div class="bt_opcao_quadro_linha" id="sim1">Sim</div>
                        <div class="bt_opcao_quadro_linha" id="nao1">Não</div>
                    </div>
                    <div style="clear:both;"></div>
                </div>
                
                <div style="margin-bottom:40px">
                    <div class="perguntas_principais_quadros" style="float:left;">Pretende declarar retiradas de pró-labore ou salários no período?
</div>
                    <div class="caixa_bt_opcoes_quadro" style="margin:0; float:right;">
                        <div class="bt_opcao_quadro_linha" id="sim2">Sim</div>
                        <div class="bt_opcao_quadro_linha" id="nao2">Não</div>
                    </div>
                    <div style="clear:both;"></div>
                </div>
            

              <div class="caixa_bt_opcoes_quadro">
                    <div class="bt_opcao_quadro" style="width:120px; padding:5px 0;" id="btContinuar">
                        Continuar
                    </div>
                </div>
	        </div>

        </div>

	</div>




	<div id="perguntas" style="display: none;">

<? if(mysql_num_rows($checa_cnaes_anexoIV) > 0) { ?>
    
    <div class="quadro_passos_sem_largura" style="padding:40px;">
    
    	<div style="display: block; width:100%;">
    
            <div class="perguntas_principais_quadros">
                Assinale as atividades que exerceu no período, entre aquelas previstas no seu CNPJ.<br />
            </div>
            <br />
    <?
    // faz o loop para gerar a lista de cnae (enquanto existirem cnaes nos paremetros indicados gere uma lista
    while ($linha_cnaes=mysql_fetch_array($resultado_cnaes)) { 
    
        //numera as linhas
        $linhaAtual = $linhaAtual + 1;
        
        //pega a descrição de cada cnae
        $sql2 = "SELECT tomador ,denominacao, anexo, REPLACE(REPLACE(REPLACE(cnae,'.',''),'-',''),'/','') cnae_limpo
                FROM 
                    cnae
                WHERE 
                    REPLACE(REPLACE(REPLACE(cnae,'.',''),'-',''),'/','')='" . str_replace(array("/","-","."),"",$linha_cnaes["cnae"]) . "' LIMIT 0, 1";
        $resultado2 = mysql_query($sql2)
        or die (mysql_error());
    
    
        //cria uma lista de descrições de cnaes
        $linha2=mysql_fetch_array($resultado2);
    ?>
            <div class="perguntas_secundarias_quadros">
    <?php 
    //monta conteúdo: radio button +  numero do cnae + descrição ( pre-seleciona e esconde radio buttom clicado se tiver apenas um cnae) 
    
        switch($linha2["tomador"]){
    
            case "sim":
    ?>	
                <input class="inputCheckBox" id="anexo_atividade_<?=$linhaAtual?>" type="checkbox" name="anexo_atividade[]" value="<?=$linha2["anexo"]?>" divid="<?=$linha2["cnae_limpo"]?>"  <?php if ($total == "1") { echo 'checked="checked" style="display:none"'; } ?> />&nbsp;&nbsp;<label><?=$linha2["denominacao"]?></label>
                <div style="margin:10px 0px 10px 30px; display: none;" class="opcao_simples" id="<?=$linha2["cnae_limpo"]?>">
                    Esta atividade está vinculada a empreitada da construção civil?<br />
                    <div style="clear: both; height: 5px" ></div>
                    <input class="abrir_cprb_input" name="ativ_<?=$linha2["cnae_limpo"]?>" type="radio" value="IV" /> Sim (selecione esta opção se estiver executando o serviço para uma obra registrada)
                    <div style="clear: both; height: 5px" ></div>
                    <input class="abrir_cprb_input" name="ativ_<?=$linha2["cnae_limpo"]?>" type="radio" value="III" /> Não

                    <?php if( $linha2["anexo"] == 's' ){  ?>
                    	<input type="hidden" class="anexo_s_class" value="true">
                    <?php } ?>
                    <div class="recolhe_cprb" style="padding-left: 20px;padding-top: 10px;padding-bottom: 10px; display:none">
	                	Sua empresa recolhe CPRB?
	                    <div style="clear: both; height: 5px"></div>
	                    <input type="radio" name="recolhe_cprb_<?=$linha2["cnae_limpo"]?>" value="sim"> Sim
	                    <div style="clear: both; height: 5px"></div>
	                    <input type="radio" name="recolhe_cprb_<?=$linha2["cnae_limpo"]?>" value="nao"> Não
	                </div>

                </div>

                <script>
                	lista_anexo_s_IV.push("<?=$linha2["cnae_limpo"]?>");
                </script>
   
    <?php
            break;
    
            default:
    ?>	
               <input class="inputCheckBox" id="anexo_atividade_<?=$linhaAtual?>" type="checkbox" name="anexo_atividade[]" value="<?=($linha2["anexo"])?>" divid="<?=$linha2["cnae_limpo"]?>"  <?php if ($total == "1") { echo 'checked="checked" style="display:none"'; } ?> />&nbsp;&nbsp;<label><?=$linha2["denominacao"]?></label>
   				 <?php if( $linha2["cnae_limpo"] == '6911701' ){ ?>
   				 <script>
                	lista_anexo_s_IV.push("<?=$linha2["cnae_limpo"]?>");
                </script>
                <?php } ?>
    <?php
            break;
            
        }
    ?>
    
    
            </div> <!-- /perguntas_simples2 -->
    <?php 
                    // fim do loop
    } 
    ?>
			<div class="caixa_bt_opcoes_quadro">
				<div class="bt_opcao_quadro" style="width:120px;padding:5px 0;float: left;margin-right: 20px;" id="voltar_gfip">
	                Voltar
	            </div>
	            <div class="bt_opcao_quadro" style="width:120px;padding:5px 0;float: left;" id="checa_gfip">
	                Continuar
	            </div>
	           
	            <br><br>
            </div>
    	
        </div>
            
    </div><!-- /quadro_passos -->

<?
} // fim do if mysql_num_rows($resultado_cnaes) > 0
?>

</div><!-- /perguntas -->



	<script type="text/javascript">

			
    </script>

    
    <!--BALLOM Sefip -->
    <div style="width:310px; position:absolute; margin-left:170px; margin-top:120px; display:none" id="sefip">
    <div style="width:8px; position:absolute; margin-left:280px; margin-top:12px"><a href="javascript:fechaDiv('sefip')"><img src="images/x.png" width="8" height="9" border="0" /></a></div>
      <table cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td colspan="3"><img src="images/balloon_topo.png" width="310" height="19" /></td>
        </tr>
        <tr>
          <td background="images/balloon_fundo_esq.png" valign="top" width="18"><img src="images/ballon_ponta.png" width="18" height="58" /></td>
          <td width="285" bgcolor="#ffff99" valign="top"><div style="width:245px; margin-left:20px; font-size:12px">
          <strong>Sefip</strong> é um programa disponibilizado gratuitamente pela Caixa Econômica Federal para geração da Gfip. Veja em <a href="procedimentos_iniciais.php">procedimentos iniciais</a> como baixá-lo e instalá-lo em seu computador</div></td>
          <td background="images/balloon_fundo_dir.png" width="7"></td>
        </tr>
        <tr>
          <td colspan="3"><img src="images/balloon_base.png" width="310" height="27" /></td>
        </tr>
      </table>
    </div>
    <!--FIM DO BALLOOM CCM -->

     <script>
    	$(document).ready(function() {

    		$(".opcao_simples .abrir_cprb_input").click(function() {
    			
    			var aux = $(this).parent().find(".anexo_s_class").val();
    			var opcao_clicada = $(this).val();
    			if( aux === 'true' && opcao_clicada  != 'IV' ){
    				var aux_click = $(this).parent().find(".recolhe_cprb input");
    				$(aux_click[1]).click();
    				$(this).parent().find(".recolhe_cprb").css("display","none");
    			}
    			else{
    				$(this).parent().find(".recolhe_cprb").css("display","block");

    			}


    		});

     		$("#cprb_sim").click(function() {
    			
    			if( $(this).attr("checked") === 'false' ){
    				$(this).attr("checked",'true');
    				$(this).css("background-color","rgb(166, 29, 0)");
    				$("#cprb_nao").attr("checked","false");
    				$("#cprb_nao").css("background-color","rgb(2, 74, 104)");
    			}

    		});

    		$("#cprb_nao").click(function() {
    			
    			if( $(this).attr("checked") === 'false' ){
    				$(this).attr("checked",'true');
    				$(this).css("background-color","rgb(166, 29, 0)");
    				$("#cprb_sim").attr("checked",'false');
    				$("#cprb_sim").css("background-color","rgb(2, 74, 104)");
    			}

    		});
    		
    		$("#btVoltarEmpreitadaParcial").click(function() {
    			
    			$("#aviso_cessao_mao_de_obra").css("display","none");
    			$("#perguntas").css("display","block");

    		});
    		

    		$("#btContinuarEmpreitadaParcial").click(function() {

    			if( $("#cprb_sim").attr("checked") === 'false' && $("#cprb_nao").attr("checked") == 'false' ){
    				alert("Responda a pergunta para continuar");
    				return;
    			}
    			var compensacao = '';
    			if( $("#cprb_sim").attr("checked") === 'true' )
    				compensacao = true;
    			else
    				compensacao = false;

    			$.ajax({
					url: 'SEFIP_config.php',
					data: 'compensacao_setar='+compensacao,
					dataType:"text",
					type:"POST",
					cache: false,
					success: function(response){
						escolhe_destino();
					}
				});
    		});
    	});
    </script>

    
    <div id="aviso_cessao_mao_de_obra" class="quadro_passos_sem_largura" style="padding:40px; display: none;">
  		  <div id="pergunta_inicial_cprb">
    			<div class="perguntas_principais_quadros">
            		<div style="float:left;margin-right:50px;">
            			Durante apuração do Simples, você recolhe também a CPRB?
            		</div>
            		<div style="width: 300px;float: left;margin-bottom:30px;">
		            	<div class="bt_opcao_quadro" style="float: left;margin-right:50px;" id="cprb_sim" checked="false">
			            	Sim
				        </div>
				        <div class="bt_opcao_quadro" style="float: left;" id="cprb_nao" checked="false">
				            Não
				        </div>	
	            	</div>
	            </div>
	            
	        	<br>
	            <br>
            	<div style="width: 100%;float: left;margin-left: 290px;">
		            <div class="bt_opcao_quadro" style="width:120px;padding:5px 0;display:block;margin-top:30px;float: left;" id="btVoltarEmpreitadaParcial">
		            	Voltar
		        	</div>
		            <div class="bt_opcao_quadro" style="width:120px;padding:5px 0;display:block;margin-top:30px;float: left;margin-left:30px;" id="btContinuarEmpreitadaParcial">
		            	Continuar
		        	</div>
		        	
		        </div>
		        <div style="clear: both; height: 5px" ></div>
    		</div>

    </div>
    
    <div id="aviso_empreitada" class="quadro_passos_sem_largura" style="padding:40px; display: none;">

    	<div style="display: block; width:100%;">

            <div class="perguntas_principais_quadros" style="margin-bottom:20px;">
            	Realiza empreitada em alguma obra de construção civil?
            </div>
    
            <div class="caixa_bt_opcoes_quadro">
                <div class="bt_opcao_quadro_linha" id="simDiv2">Sim</div>
                <div class="bt_opcao_quadro_linha" id="naoDiv2">Não</div>
            </div>

		</div>        

    </div>



    
    <div id="aviso_matriculaCEI" class="quadro_passos_sem_largura" style="padding:40px; display: none;">

    	<div style="display: block; width:100%;">

            <div class="perguntas_principais_quadros" style="margin-bottom:20px;">
				Alguma das obras em que realiza empreitada tem matrícula CEI?
            </div>
    
            <div class="caixa_bt_opcoes_quadro">
                <div class="bt_opcao_quadro_linha" id="simDiv3">Sim</div>
                <div class="bt_opcao_quadro_linha" id="naoDiv3">Não</div>
            </div>
		
        </div>
        
    </div>




    
    <div id="aviso_responsavelMatriculaCEI" class="quadro_passos_sem_largura" style="padding:40px; display: none;">

    	<div style="display: block; width:100%;">

            <div class="perguntas_principais_quadros" style="margin-bottom:20px;">
				Sua empresa é responsável pela matrícula CEI?
            </div>
    
            <div class="caixa_bt_opcoes_quadro">
                <div class="bt_opcao_quadro_linha" id="simDiv4">Sim</div>
                <div class="bt_opcao_quadro_linha" id="naoDiv4">Não</div>
            </div>
		
        </div>
        
    </div>
    
  
  
    
    <div id="aviso_remuneracao" class="quadro_passos_sem_largura" style="padding:40px; display: none;">

    	<div style="display: block; width:100%;">

            <div class="perguntas_principais_quadros" style="margin-bottom:20px;">
				É possível individualizar a remuneração dos trabalhadores por obra?
            </div>
            <div style="margin-bottom:5px; font-size:15px" class="perguntas_secundarias_quadros">
                <div style="float:left; width:13px; margin-right: 5px;"><input type="radio" name="rdbRenumeracao" value="0" /></div><div style="float:left; width:830px;">Não é possível individualizar a remuneração por obra. Os mesmos trabalhadores foram utilizados para atender a várias empresas contratantes, alternadamente, no mesmo período.</div>
            </div>
			<div style="clear:both;margin-bottom:10px;"></div>
            <div style="margin-bottom:5px; font-size:15px" class="perguntas_secundarias_quadros">
                <div style="float:left; width:13px; margin-right: 5px;"><input type="radio" name="rdbRenumeracao" value="1" /></div><div style="float:left; width:830px;">Sim, é possível individualizar a remuneração por obra.</div>
            </div>
			<div style="clear:both;"></div>
    
            <div class="caixa_bt_opcoes_quadro">
	            <div class="bt_opcao_quadro" style="width:120px; padding:5px 0;" id="btContinuarFinal">
                	Continuar
                </div>
            </div>
		
        </div>
        
    </div>
      

    
    <div id="aviso_bloqueio" class="quadro_passos_sem_largura" style="padding:40px; display: none;">

    	<div style="display: block; width:100%;">

     		<div class="perguntas_principais_quadros" style="margin-bottom:20px;">
				Impossível prosseguir
            </div>
            	
            <div  style="margin-bottom:20px; text-align: left;" class="opcao_simples">
                Infelizmente o Contador Amigo ainda não atende empresas que fazem serviços de cessão de mão de obra.<br /><br />
    Por favor entre em contato com nosso <a href="suporte.php">Help Desk</a>.
            </div>
            
			<div class="caixa_bt_opcoes_quadro">
                <div class="bt_opcao_quadro" id="btVoltar" data-div="">Voltar</div>
            </div>
            		
        </div>
        
    </div>




 </div>
 
</div>
<!--fim do div principal -->

<?php 
//}
?>
<?php include 'rodape.php' ?>

