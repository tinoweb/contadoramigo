<?
session_start();

$_SESSION['INSS_variaveis'] = "";

include 'conect.php';

	$checa_cnaes_anexoIV = mysql_query("SELECT * FROM dados_da_empresa_codigos ec INNER JOIN cnae c ON ec.cnae = c.cnae WHERE 1=1 AND ec.id = '" . $_SESSION["id_empresaSecao"] . "' AND ( c.anexo = 'IV' OR (REPLACE(REPLACE(REPLACE(c.cnae,'.',''),'-',''),'/','') IN  ('4321500','4322301','4322303','4330401','4330402','4330403','4330404','4330405','4330499')))")
	or die (mysql_error());
	
	if(mysql_num_rows($checa_cnaes_anexoIV) <= 0){
		header('location:inss_sem_cpp.php');
	} 
?>


<?php include 'header_restrita.php' ?>


<div class="principal minHeight">

  <span class="titulo">Impostos e Obrigações</span><br /><br />

  <div style="margin-bottom:20px" class="tituloVermelho">Recolhimento do INSS</div>
    
    
<?
// checar se a empresa possui cnae cadastrado
$rsCnaeEmpresa = mysql_query("SELECT REPLACE(REPLACE(cnae,'/',''),'-','') cnae FROM dados_da_empresa_codigos WHERE id='" . $_SESSION["id_empresaSecao"] . "'");

$cnaes_empresa = mysql_num_rows($rsCnaeEmpresa);

if($cnaes_empresa <= 0){
?>

    <div id="aviso_bloqueio" class="quadro_passos_sem_largura" style="padding:40px; display: none;">

    	<div style="display: block; width:100%;">

     		<div class="perguntas_principais_quadros" style="margin-bottom:20px;">
				Impossível prosseguir
            </div>
            	
            <div  style="margin-bottom:20px; text-align: left;" class="opcao_simples">
                Você ainda não cadastrou a atividade principal e/ou secundários da sua empresa em <a href="meus_dados_empresa.php">Dados da Empresa</a>. Elas são necessárias para o cálculo do recolhimento do INSS. Atualize os dados e retorne a esta página em seguida.
            </div>
            
			<div class="caixa_bt_opcoes_quadro">
                <div class="bt_opcao_quadro" data-div=""><a href="meus_dados_empresa.php">Cadastrar</a></div>
            </div>
            		
        </div>
        
    </div>

<?
} else {


?>

	<script>

		var envio = false;

		var mes = 0;
		var ano = 0;
		var hoje = new Date();
		var anoAtual = hoje.getFullYear();
		var arr_anexos = new Array();
		var bloqueia = 0;

		function checaAtividadeCoincidente(valorCompara){
			var arr = new Array('412','421','422','429','431','432','433','439');
			for(var i=0; i < arr.length; i++){
				if(valorCompara.substr(0,3) == arr[i]){
					return true;
				}
			}
			return false;
		}

		$(document).ready(function(e) {

			$("input[id^='anexo_atividade_']").each(function(index,element){
				if($(this).attr('checked')){
					if($('#' + $(this).attr('divid')).html()){
						$('#' + $(this).attr('divid')).css('display','block');
					}
				}else{
					$('#' + $(this).attr('divid')).find("input[name^='ativ_']:checked").attr('checked',false);
					if($('#' + $(this).attr('divid')).html()){
						$('#' + $(this).attr('divid')).css('display','none');
					}
				}
			});
			
			$("input[id^='anexo_atividade_']").click(function(){
				if($(this).attr('checked')){
					if($('#' + $(this).attr('divid')).html()){
						$('#' + $(this).attr('divid')).css('display','block');
					}
				}else{
					$('#' + $(this).attr('divid')).find("input[name^='ativ_']:checked").attr('checked',false);
					if($('#' + $(this).attr('divid')).html()){
						$('#' + $(this).attr('divid')).css('display','none');
					}
				}
			});
			
			$('#bt_continuar_cpp').click(function(e){
				e.preventDefault();
				var arrIVCAtividades = new Array();
				var arrIVAtividades = new Array();
				var arrAtividades = new Array();
				var arr_anexos = new Array();

				$('#hddAtivIVCExercidas').val('');
				$('#hddAtivIVExercidas').val('');
				$('#hddAtivExercidas').val('');
				

				if($('#txtMesAno').val() != ''){
					mes = ($('#txtMesAno').val().split('/')[0]);
					ano = ($('#txtMesAno').val().split('/')[1]);
	
					if(mes<1 || mes >12){
						alert('Mês inválido!');
						return false;
					}
					if(ano<2000 || ano > anoAtual){
						alert('Ano inválido!');
						return false;
					}
				}else{
					alert('Preencha o período!');
					$('#txtMesAno').focus();
					return false;
				}

				$.each($("input[id^='anexo_atividade_']"),function(indice,valor){
					
					var objCheckBoxAtividade = $("input[id^='anexo_atividade_']").eq(indice);
					
					if(objCheckBoxAtividade.attr('checked')){ // se a atividade foi selecionada
						if(objCheckBoxAtividade.val() != 's' && objCheckBoxAtividade.val() != 'x'){ // se o anexo não for dubio nem impeditivo
							
							arr_anexos.push(objCheckBoxAtividade.val());
									
							if(objCheckBoxAtividade.val() == "IV"){
								arrIVAtividades.push(objCheckBoxAtividade.attr('divid')); // incluindo no array de atividades diversas do ANEXO IV
								$('#hddAtivIVExercidas').val(arrIVAtividades.toString());
							}else{
								arrAtividades.push(objCheckBoxAtividade.attr('divid')); // incluindo no array de atividades diversas
								$('#hddAtivExercidas').val(arrAtividades.toString());
							}
							
						}else{

							var radioResposta = $('#' + objCheckBoxAtividade.attr('divid')).find("input[name^='ativ_']:checked");
							var valueAnexo = radioResposta.val();
							bloqueia = 2;
							if(radioResposta.val()){

								if(radioResposta.val() == "IV"){
									if(checaAtividadeCoincidente(objCheckBoxAtividade.attr('divid'))){
										arrIVCAtividades.push(objCheckBoxAtividade.attr('divid'));
									}else{
										arrIVAtividades.push(objCheckBoxAtividade.attr('divid'));
									}
								} else{
									arrAtividades.push(objCheckBoxAtividade.attr('divid'));
								}
			
			
								$('#hddAtivIVExercidas').val(arrIVAtividades.toString());
								$('#hddAtivIVCExercidas').val(arrIVCAtividades.toString());
								$('#hddAtivExercidas').val(arrAtividades.toString());

								arr_anexos.push(radioResposta.val());
								bloqueia = 0;
							}else{
								$('#' + objCheckBoxAtividade.attr('divid')).find("input[name^='ativ_']").eq(0).focus();
							}
						}
					}
				});
				if(arr_anexos.length == 0){
					alert('Não é possível prosseguir, pois você não selecionou nenhuma atividade. A CPP só é devida se alguma atividade for desempenhada no período.');
					$("input[id^='anexo_atividade_']").eq(0).focus();
					return false;
				}else{
					
					if(arr_anexos.length == 1 && $.inArray('IV',arr_anexos) >= 0){
							$.ajax({
								url: 'INSS_config.php',
								data: 'somenteIV=1',
								dataType:"text",
								type:"POST",
								cache:false,
								success: function(response){
								}
							});
					}else{
							$.ajax({
								url: 'INSS_config.php',
								data: 'IVeoutros=1',
								dataType:"text",
								type:"POST",
								cache:false,
								success: function(response){
								}
							});
					}
					
					if(bloqueia == 2){
						alert('Selecione uma opção dentro da atividade selecionada!');
						return false;
					}else{
						var anexoIV = false;
						for(var i=0; i<arr_anexos.length; i++){
							if(arr_anexos[i] == "IV"){
								anexoIV = true;
							}
						}
						if(anexoIV){
							$('div[id^="aviso"]').css('display','none');
							$('#perguntas').css('display','none');
							$('#aviso_ok').css('display','block');
						}else{
							location.href='inss_sem_cpp.php';
						}
					}
				}

			});

			$('#btSubmit').bind('click',function(e){
				if(envio){
					$.ajax({
					  url:'sefip_folha_checa_movimento.php?id=<?=$_SESSION["id_empresaSecao"]?>&mes=' + mes + '&ano=' + ano,
					  type: 'post',
					  async: false,
					  cache:false,
					  success: function(ret){
						$("body").css("cursor", "auto");
						if(ret == 'erro'){
							$('div[id^="aviso"]').css('display','none');
							$('#perguntas').css('display','none');
							$('#aviso_movimento').css('display','block');
							return false;
						}
						if(ret == 'ok' || ret == 'ok1'){
							$('#frmCPP').submit();
						}
					  }
					});
				}else{
					$('div[id^="aviso"]').css('display','none');
					$('#perguntas').css('display','none');
					$('#aviso_movimento').css('display','block');
				}
			});
			
			$('.btVoltar').click(function(e){
				e.preventDefault();
				$('div[id^="aviso"]').css('display','none');
				$('#'+$(this).attr('href')).css('display','block');
			});
		

			// resposta 1
			$("#sim1").click(function() {
					$("#sim1").css("background-color", "#a61d00");
					$("#nao1").css("background-color", "#024a68");
					envio = true;
					$.ajax({
					  url:'sefip_folha_checa_movimento.php?id=<?=$_SESSION["id_empresaSecao"]?>&mes=' + mes + '&ano=' + ano,
					  type: 'post',
					  async: false,
					  cache:false,
					  success: function(ret){
						$("body").css("cursor", "auto");
						if(ret == 'erro'){
							$('div[id^="aviso"]').css('display','none');
							$('#perguntas').css('display','none');
							$('#aviso_movimento').css('display','block');
							$("#sim1").css("background-color", "#024a68");
							$("#nao1").css("background-color", "#024a68");
							return false;
						}
						if(ret == 'ok' || ret == 'ok1'){
							$('#frmCPP').submit();
						}
					  }
					});
				}
			);	
			
			$("#nao1").click(function() {
					envio = false;
					$('div[id^="aviso"]').css('display','none');
					$('#perguntas').css('display','none');
					$('#aviso_gfip').css('display','block');
				}
			);


    });
    
  </script>

<div id="perguntas">

    <div style="width:780px; margin-bottom: 20px;">
    O INSS é recolhido através da GPS - Guia da Previdência Social. Só é devido se, no período, você efetuou pagamentos referentes a pró-labore, remuneração de profissionais autônomos ou salários. Quando cabível, a CPP - Contribuição Previdenciária Patronal, será calculada junto com o INSS. <a href="inss_compensacao.php">Veja aqui</a> como compensar valores pagos indevidamente ou a maior.
    </div>


    <!-- AQUI VAI O TEXTO DA TELA INICIAL NO FORMATO DE TEXTO SIMPLES DO SITE -->
    <form id="frmCPP" name="frmCPP" action="inss_calculo.php" method="post" onsubmit="return false;">
    <input type="hidden" id="hddAtivIVCExercidas" name="hddAtivIVCExercidas" />
    <input type="hidden" id="hddAtivIVExercidas" name="hddAtivIVExercidas" />
    <input type="hidden" id="hddAtivExercidas" name="hddAtivExercidas" />
    <input type="hidden" id="hddCNAEs" name="hddCNAEs" />
<? if(mysql_num_rows($checa_cnaes_anexoIV) > 0) { ?>

    <div class="quadro_passos">

			<div style="padding:40px 0;">
    
            <div style="font-size:20px; margin-bottom:20px; display: block" class="perguntas_simples2">
                A INSS que pretende recolher se refere a qual período?<br />
				<input type="text" name="txtMesAno" id="txtMesAno" class="campoDataMesAno" style="width:80px;" /> <span style="font-size:15px">mm/aaaa</span>
            </div>

    
            <div style="font-size:20px;" class="perguntas_simples2">
                Assinale as atividades que exerceu no período, entre aquelas previstas no seu CNPJ.<br />
            </div>
            <br />
<?
			// faz o loop para gerar a lista de cnae (enquanto existirem cnaes nos paremetros indicados gere uma lista
			while ($linha_cnaes=mysql_fetch_array($rsCnaeEmpresa)) { 

				//numera as linhas
				$linhaAtual = $linhaAtual + 1;
				
				//pega a descrição de cada cnae
				$sql2 = "SELECT denominacao, anexo, REPLACE(REPLACE(REPLACE(cnae,'.',''),'-',''),'/','') cnae_limpo
						FROM 
							cnae
						WHERE 
							REPLACE(REPLACE(REPLACE(cnae,'.',''),'-',''),'/','')='" . $linha_cnaes["cnae"] . "' LIMIT 0, 1";
				$resultado2 = mysql_query($sql2)
				or die (mysql_error());


				//cria uma lista de descrições de cnaes
				$linha2=mysql_fetch_array($resultado2);
?>
        		<div style="margin-bottom:5px; font-size:15px" class="perguntas_simples2">
<?php 
				//monta conteúdo: radio button +  numero do cnae + descrição ( pre-seleciona e esconde radio buttom clicado se tiver apenas um cnae) 

					switch($linha2["cnae_limpo"]){

						case "4321500":
				?>	
							<input id="anexo_atividade_<?=$linhaAtual?>" type="checkbox" name="anexo_atividade[]" value="<?=$linha2["anexo"]?>" divid="<?=$linha2["cnae_limpo"]?>"  <?php if ($total == "1") { echo 'checked="checked" style="display:none"'; } ?> />&nbsp;&nbsp;<label><?=$linha2["denominacao"]?></label>
							<div style="margin:10px 0px 10px 30px; display: none;" class="opcao_simples" id="<?=$linha2["cnae_limpo"]?>">
								Esta atividade está vinculada a empreitada ou trata-se apenas de um serviço de manutenção?<br />
								<div style="clear: both; height: 5px" ></div>
								<input name="ativ_4321500" type="radio" value="IV" /> Está vinculada a uma empreitada
								<div style="clear: both; height: 5px" ></div>
								<input name="ativ_4321500" type="radio" value="III" /> Apenas serviço de manutenção
							</div>
				<?php
						break;
				
						case "4322301":
				?>	
							<input id="anexo_atividade_<?=$linhaAtual?>" type="checkbox" name="anexo_atividade[]" value="<?=$linha2["anexo"]?>" divid="<?=$linha2["cnae_limpo"]?>"  <?php if ($total == "1") { echo 'checked="checked" style="display:none"'; } ?> />&nbsp;&nbsp;<label><?=$linha2["denominacao"]?></label>
							<div style="margin:10px 0px 10px 30px; display: none;" class="opcao_simples" id="<?=$linha2["cnae_limpo"]?>">
								Esta atividade está vinculada a empreitada ou trata-se apenas de um serviço de manutenção?<br />
								<div style="clear: both; height: 5px" ></div>
								<input name="ativ_4322301" type="radio" value="IV" /> Está vinculada a uma empreitada
								<div style="clear: both; height: 5px" ></div>
								<input name="ativ_4322301" type="radio" value="III" /> Apenas serviço de manutenção
							</div>
				<?php
						break;
				
				
						case "4322303":
				?>	
							<input id="anexo_atividade_<?=$linhaAtual?>" type="checkbox" name="anexo_atividade[]" value="<?=$linha2["anexo"]?>" divid="<?=$linha2["cnae_limpo"]?>"  <?php if ($total == "1") { echo 'checked="checked" style="display:none"'; } ?> />&nbsp;&nbsp;<label><?=$linha2["denominacao"]?></label>
							<div style="margin:10px 0px 10px 30px; display: none;" class="opcao_simples" id="<?=$linha2["cnae_limpo"]?>">
								Esta atividade está vinculada a empreitada da construção civil?<<br />
								<div style="clear: both; height: 5px" ></div>
								<input name="ativ_4322303" type="radio" value="IV" /> Está vinculada a uma empreitada
								<div style="clear: both; height: 5px" ></div>
								<input name="ativ_4322303" type="radio" value="III" /> Não está vinculado a uma empreitada
							</div>
				<?php
						break;
				
						case "4330401":
				?>	
							<input id="anexo_atividade_<?=$linhaAtual?>" type="checkbox" name="anexo_atividade[]" value="<?=$linha2["anexo"]?>" divid="<?=$linha2["cnae_limpo"]?>"  <?php if ($total == "1") { echo 'checked="checked" style="display:none"'; } ?> />&nbsp;&nbsp;<label><?=$linha2["denominacao"]?></label>
							<div style="margin:10px 0px 10px 30px; display: none;" class="opcao_simples" id="<?=$linha2["cnae_limpo"]?>">
								Esta atividade está vinculada a empreitada da construção civil? Realizam montagens e instalações para feiras e eventos?<br />
								<div style="clear: both; height: 5px" ></div>
								<input name="ativ_4330401" type="radio" value="IV" /> É vinculada à empreitada.
								<div style="clear: both; height: 5px" ></div>
								<input name="ativ_4330401" type="radio" value="V" /> Não é vinculada à empreitada e consiste na montagens e instalações para feiras e eventos.
								<div style="clear: both; height: 5px" ></div>
								<input name="ativ_4330401" type="radio" value="III" /> Não é vinculada à empreitada, mas não se trata de montagem e instalação para feiras e eventos.
							</div>
				<?php
						break;
				
						case "4330402":
				?>	
							<input id="anexo_atividade_<?=$linhaAtual?>" type="checkbox" name="anexo_atividade[]" value="<?=$linha2["anexo"]?>" divid="<?=$linha2["cnae_limpo"]?>"  <?php if ($total == "1") { echo 'checked="checked" style="display:none"'; } ?> />&nbsp;&nbsp;<label><?=$linha2["denominacao"]?></label>
							<div style="margin:10px 0px 10px 30px; display: none;" class="opcao_simples" id="<?=$linha2["cnae_limpo"]?>">
								Esta atividade está vinculada a empreitada da construção civil?<br />
								<div style="clear: both; height: 5px" ></div>
								<input name="ativ_4330402" type="radio" value="IV" /> Sim
								<div style="clear: both; height: 5px" ></div>
								<input name="ativ_4330402" type="radio" value="III" /> Não
							</div>
				<?php
						break;
				
						case "4330403":
				?>	
							<input id="anexo_atividade_<?=$linhaAtual?>" type="checkbox" name="anexo_atividade[]" value="<?=$linha2["anexo"]?>" divid="<?=$linha2["cnae_limpo"]?>"  <?php if ($total == "1") { echo 'checked="checked" style="display:none"'; } ?> />&nbsp;&nbsp;<label><?=$linha2["denominacao"]?></label>
							<div style="margin:10px 0px 10px 30px; display: none;" class="opcao_simples" id="<?=$linha2["cnae_limpo"]?>">
								Esta atividade está vinculada a empreitada da construção civil?<br />
								<div style="clear: both; height: 5px" ></div>
								<input name="ativ_4330403" type="radio" value="IV" /> Sim
								<div style="clear: both; height: 5px" ></div>
								<input name="ativ_4330403" type="radio" value="III" /> Não
							</div>
				<?php
						break;
				
						case "4330404":
				?>	
							<input id="anexo_atividade_<?=$linhaAtual?>" type="checkbox" name="anexo_atividade[]" value="<?=$linha2["anexo"]?>" divid="<?=$linha2["cnae_limpo"]?>"  <?php if ($total == "1") { echo 'checked="checked" style="display:none"'; } ?> />&nbsp;&nbsp;<label><?=$linha2["denominacao"]?></label>
							<div style="margin:10px 0px 10px 30px; display: none;" class="opcao_simples" id="<?=$linha2["cnae_limpo"]?>">
								Esta atividade está vinculada a empreitada da construção civil?<br />
								<div style="clear: both; height: 5px" ></div>
								<input name="ativ_4330404" type="radio" value="IV" /> Sim
								<div style="clear: both; height: 5px" ></div>
								<input name="ativ_4330404" type="radio" value="III" /> Não
							</div>
				<?php
						break;
				
						case "4330405":
				?>	
							<input id="anexo_atividade_<?=$linhaAtual?>" type="checkbox" name="anexo_atividade[]" value="<?=$linha2["anexo"]?>" divid="<?=$linha2["cnae_limpo"]?>"  <?php if ($total == "1") { echo 'checked="checked" style="display:none"'; } ?> />&nbsp;&nbsp;<label><?=$linha2["denominacao"]?></label>
							<div style="margin:10px 0px 10px 30px; display: none;" class="opcao_simples" id="<?=$linha2["cnae_limpo"]?>">
								Esta atividade está vinculada a empreitada da construção civil?<br />
								<div style="clear: both; height: 5px" ></div>
								<input name="ativ_4330405" type="radio" value="IV" /> Sim
								<div style="clear: both; height: 5px" ></div>
								<input name="ativ_4330405" type="radio" value="III" /> Não
							</div>
				<?php
						break;
				
						case "4330499":
				?>	
							<input id="anexo_atividade_<?=$linhaAtual?>" type="checkbox" name="anexo_atividade[]" value="<?=$linha2["anexo"]?>" divid="<?=$linha2["cnae_limpo"]?>"  <?php if ($total == "1") { echo 'checked="checked" style="display:none"'; } ?> />&nbsp;&nbsp;<label><?=$linha2["denominacao"]?></label>
							<div style="margin:10px 0px 10px 30px; display: none;" class="opcao_simples" id="<?=$linha2["cnae_limpo"]?>">
								Esta atividade está vinculada a empreitada da construção civil?<br />
								<div style="clear: both; height: 5px" ></div>
								<input name="ativ_4330499" type="radio" value="IV" /> Sim
								<div style="clear: both; height: 5px" ></div>
								<input name="ativ_4330499" type="radio" value="III" /> Não
							</div>
				<?php
						break;
						
						default:
				?>
				
							<input id="anexo_atividade_<?=$linhaAtual?>" type="checkbox" name="anexo_atividade[]" value="<?=$linha2["anexo"]?>" divid="<?=$linha2["cnae_limpo"]?>"  <?php if ($total == "1") { echo 'checked="checked" style="display:none"'; } ?> />&nbsp;&nbsp;<label><?=$linha2["denominacao"]?></label>
							
				<?
						break;
					}
				?>


       			</div> <!-- /perguntas_simples2 -->
<?php 
			// fim do loop
			} 
?><br />

	      <div class="opcao" id="bt_continuar_cpp" style="width:120px; margin-left: 400px; padding:5px 0;">Continuar</div>                
<br />
        
			</div>    <!-- /quadro padding -->
      
    </div><!-- /quadro_passos -->

</form>
<?
} // fim do if mysql_num_rows($resultado_cnaes) > 0
?>

</div><!-- /perguntas -->


    <div id="aviso_movimento" class="quadro_passos_sem_largura" style="padding:40px; display: none;">

    	<div style="display: block; width:100%;">

     		<div class="perguntas_principais_quadros" style="margin-bottom:20px;">
				Impossível prosseguir
            </div>
            	
            <div  style="margin-bottom:20px; text-align: left;" class="opcao_simples">
                Não há nenhum pagamento referente a <strong>pró-labore</strong>, <strong>trabalhadores autônomos</strong> ou <strong>salários</strong> cadastrados nesse período. Cadastre-os (use a aba pagamentos no menu superior), certifique-se também de ter enviado a Gfip referente a este período e depois retorne a esta página.
            </div>
            
			<div class="caixa_bt_opcoes_quadro">
                <div class="bt_opcao_quadro btVoltar" href="perguntas">Voltar</div>
            </div>
            		
        </div>
        
    </div>


    <div id="aviso_bloqueio" class="quadro_passos_sem_largura" style="padding:40px; display: none;">

    	<div style="display: block; width:100%;">

     		<div class="perguntas_principais_quadros" style="margin-bottom:20px;">
				Impossível prosseguir
            </div>
            	
            <div  style="margin-bottom:20px; text-align: left;" class="opcao_simples">
                Não há recolhimento a ser feito.<br />
				A CPP referente às atividades exercidas por sua empresa no período selecionado já está embutida na própria DAS (Documento de Arrecadação do Simples).
            </div>
            
			<div class="caixa_bt_opcoes_quadro">
                <div class="bt_opcao_quadro btVoltar" href="perguntas">Voltar</div>
            </div>
            		
        </div>
        
    </div>
    
    
    <div id="aviso_gfip" class="quadro_passos_sem_largura" style="padding:40px; display: none;">

    	<div style="display: block; width:100%;">

     		<div class="perguntas_principais_quadros" style="margin-bottom:20px;">
				Impossível prosseguir
            </div>
            	
            <div  style="margin-bottom:20px; text-align: left;" class="opcao_simples">
                Para recolher o INSS é preciso primeiro transmitir a Gfip referente ao período.
            </div>
            
			<div class="caixa_bt_opcoes_quadro">
                <div class="bt_opcao_quadro btVoltar" href="perguntas">Voltar</div>
            </div>
            		
        </div>
        
    </div>
    


<div id="aviso_ok" style="display: <?=mysql_num_rows($checa_cnaes_anexoIV)<=0 ? "block" : "none"?>;">
    <div class="quadro_passos" style="height:300px">
        <div class="perguntas_simples">
            <div style="text-align:center;margin-bottom:50px; margin-top:50px; font-family:vagLight">Já enviou a Gfip?</div>
            <div style="float:left;margin:0 0 0 330px; display:block;">
                <div class="opcao" id="sim1">Sim</div>
                <div class="opcao" id="nao1">Não</div>
                <div style="clear:both; height:5px"></div>
            </div>
        </div>
    </div>
</div>


<?php 
	

}
?>
</div>
<!--fim do div principal -->

<?php 
//}
?>

<?php include 'rodape.php' ?>

