<?php 
session_start();
if (isset($_SESSION["id_userSecao"])) {include 'header_restrita.php';}
else {
	$nome_meta = "regularizacao";
	include 'header.php';} 
?>
<script>
function mudaImagem() {document.getElementById('ilustracao').src="images/regularizacao_ilustra2.png"}
</script>
<div class="hidden">
	<script type="text/javascript">
		<!--//--><![CDATA[//><!--

			if (document.images) {
				img1 = new Image();
				img2 = new Image();

				img1.src = "images/regularizacao_ilustra.png";
				img2.src = "images/regularizacao_ilustra2.png";
			}

		//--><!]]>
	</script>
</div>

<div class="principal minHeight">
	<H1>Serviços Avulsos</H1>
	<img id="ilustracao" src="images/regularizacao_ilustra.png" width="25%" alt="Regularize-se" style="margin-bottom: 10px; margin-left: 20px; float: right"/><H2>Imposto de Renda Pessoa Física</H2>

	Está precisando de uma ajuda para fazer sua declaração IR pessoa física? O contador Amigo pdoe ajudá-lo nisso também. Nossos contadores parceiros fazem o preenchimento e a transmissão de sua declaração. A tarifa básica do serviço é de R$ 100. Responda as perguntas a seguir para ver se o seu perfil se encaixa neste valor padrão. Caso contrário enviaremos um orçamento específico por e-mail em 24 horas.<br><br>

	
	<form id="formIRPF" method="post" action="form_irpf.php">
		
		<div id="tudo">

		<div style="margin-bottom: 10px; font-weight: bold">Quais as rendas que possui</div>
		<div style="margin-bottom: 20px">
			<input type="checkbox" name="tipo_de_renda" value="Pró-Labore"> Apenas as retiradas de pró-lore e dsitribuição de lucros da minha empresa<br>
			<input type="checkbox" name="tipo_de_renda" value="Profisional Liberal" onClick="javascript:aviso4()">Honorários de profissional liberal (sem empresa)<br>
			<input type="checkbox" name="tipo_de_renda" value="Profisional Liberal" onClick="javascript:aviso4()">Salário CLT<br>
			<input type="checkbox" name="tipo_de_renda" value="Alugueis" onClick="javascript:aviso4()">Renda de Alugueis<br>
			<input type="checkbox" name="tipo_de_renda" value="Outras" onClick="javascript:aviso4()">Outras&nbsp;&nbsp;&nbsp;Quais <input type="text" id="quais_outras" name="quais_outras" size="30" maxlength="30">
		</div>

		<div style="margin-bottom: 10px; font-weight: bold">Possui dependentes com rendas?</div>
				<div style="margin-bottom: 20px">
			<input type="radio" name="certificado" value="Sim" onClick="javascript:aviso3()"> Sim&nbsp;&nbsp;&nbsp;Quais <input type="text" id="quais_outras" name="quais_outras" size="30" maxlength="30"><br>
			<input type="radio" name="certificado" value="Não" onClick="javascript:aviso4()"> Não<br>
		</div>
		
<div class="tituloAzul" style="margin-bottom: 20px">Seus Dados</div>


			<div style="float: left; text-align: right; width: 120px; margin-right: 10px">Nome: </div>
			<div style="float: left; text-align: left">
				<input type="text" id="socio_resp" name="socio_resp" size="30" maxlength="30">
			</div>
			<div style="clear: both; height: 10px"></div>

			<div style="float: left; text-align: right; width: 120px; margin-right: 10px">CPF:</div>
			<div style="float: left; text-align: left">
				<input type="text" class="campoCPF" id="cpf_resp" name="cpf_resp" size="14" maxlength="14">
			</div>
			<div style="clear: both; height: 10px"></div>

			<div style="float: left; text-align: right; width: 120px; margin-right: 10px">Data de Nascimento: </div>
			<div style="float: left; text-align: left">
				<input type="text" class="campoData" id="nascimento_socio_resp" name="nascimento_socio_resp" size="12" maxlength="12"> (do sócio-responsável)
			</div>
			<div style="clear: both; height: 10px"></div>

			<div style="float: left; text-align: right; width: 120px; margin-right: 10px">Tel. para contato: </div>
			<div style="float: left; text-align: left; margin-right: 10px;">
				<input type="text" id="ddd" name="ddd" size="3" maxlength="3"></div>
			<div style="float: left; text-align: left">
				<input type="text" id="tel_contato" name="tel_contato" size="9" maxlength="9"></div>
			<div style="clear: both; height: 10px"></div>

			<div style="float: left; text-align: right; width: 120px; margin-right: 10px">Possui WhatsApp?</div>
			<div style="float: left; text-align: left; margin-right: 10px">
				<input type="radio" name="whatsapp" onClick="javascript:abreDiv('numero_whatsapp')"> Sim
			</div>
			<div id="numero_whatsapp" style="float:left; display: none">
				<input type="text" name='numero_whatsapp' size="14" maxlength="14">
				(Inclua o DDD)
			</div>
			<div style="clear: both; height: 10px"></div>

			<div style="float: left; text-align: right; width: 120px; margin-right: 10px">E-mail:</div>
			<div style="float: left; text-align: left">
				<input type="text" id="e-mail" name="e-mail">
			</div>
			<div style="clear: both; height: 20px"></div>
			<input type="button" id="btProsseguir" value="Prosseguir" onClick="javascript:mudaImagem()" />
			<div class="divCarregando1" style="margin-top:10px; text-align:center;display:none">
				<img src="images/loading.gif" width="16" height="16">
			</div>
			</div>
		</div>

		<div id="orcamento" style="display:none">
			<div class="tituloVermelho">Tarifa Padrão</div>
			Seu perfil de contribuindo se encaixa em nossa tarifa padrão de R$ 300 para elaboração do Imposto de Renda Pessoa Física. O serviço inclui
			<ul>
				<li>Análise e cálcula das rendas</li>
				<li>Preenchimento e envio da declaração</li>
				<li>Parcelamento do Impsoto a restituir (quandor for o caso)</li>
			</ul>


			<input type="button" id="paginaPagamento" value="Contratar" />
			<div class="divCarregando2" style="margin-top:10px; text-align:center;display:none">
				<img src="images/loading.gif" width="16" height="16">
			</div>
		</div>
	</form>	
</div>

<script>


	
	$(function(){
		
		
		$('#paginaPagamento').click(function(){
			
			$("#paginaPagamento").css("display","none");
			$(".divCarregando2").css("display","table-row");						
			$('#formOrcamento').submit();
			
		});	
		
		$('#btProsseguir').click(function(){
			
			var status = validaCampos();
			
			if(status){
				
				
				if(document.getElementById('optante_sim').checked && document.getElementById('funcionarios_nao').checked && document.getElementById('servicos_sim').checked){
					
					document.getElementById('tudo').style.display = "none"
					document.getElementById('orcamento').style.display = "block"
					
				} else {
					
					$("#btProsseguir").css("display","none");
					$(".divCarregando1").css("display","table-row");
					$('#formOrcamento').submit();
					
				}
			}			
		});
		
		function validaCampos(){
			
			
			if($('#cpf_resp').val().length <= 0){
				alert('Informe o CPF');
				$(this).focus();
				return false;
			}
			
			if($('#nascimento_socio_resp').val().length <= 0){
				alert('Informe a data de nascimento');
				$(this).focus();
				return false;
			}
			
			if($('#ddd').val().length <= 0){
				alert('Informe o DDD');
				$(this).focus();
				return false;
			}
			
			if($('#tel_contato').val().length <= 0){
				alert('Informe o telefone');
				$(this).focus();
				return false;
			}
			
			if($('#e-mail').val().length <= 0){
				alert('Informe o E-mail');
				$(this).focus();
				return false;
			}
			
			if(isEmail($('#e-mail').val()) === false){
				alert("Informe um email válido");
				$('#e-mail').focus();
				$('#e-mail').select();
				return false;
			}
						
			return true;
		}
		
	});
	
	//faz a validação de e-mail
	function isEmail(email) {
	  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	  return regex.test(email);
	}
	
	
	
</script>

<?php include 'rodape.php' ?>
