<?php 

//	ini_set('display_errors',1);
//	ini_set('display_startup_erros',1);
//	error_reporting(E_ALL);

session_start();

if (isset($_SESSION["id_userSecao"])) 
{
	require_once('header_restrita.php');

	$data = date("d/m/Y");
	$action = "servico_form_dados_usuario.php";
	$userId = $_SESSION['id_userSecaoMultiplo'];

} else {

	$nome_meta = "irpf";

	require_once('header.php' );

	$data = date("d/m/Y");
	$action = "servico_informar_dados_contratar.php";

	$userId = '';
}

?>

<div class="principal minHeight">
	<H1>Serviços Avulsos</H1>
	<!--<img id="ilustracao" src="images/regularizacao_ilustra.png" width="25%" alt="Regularize-se" style="margin-bottom: 10px; margin-left: 20px; float: right"/>-->
	<H2>Imposto de Renda Pessoa Física</H2>

	Está precisando fazer sua declaração IR pessoa física? O contador Amigo pode ajudá-lo nisso também. Nossos contadores parceiros fazem o preenchimento e a transmissão de sua declaração. A tarifa básica do serviço é de R$ 100. Responda as perguntas a seguir para ver se o seu perfil se encaixa neste valor padrão. Caso contrário enviaremos um orçamento específico por e-mail em 24 horas.<br><br>
	
	<form id="formIRPF" method="post" action="form_irpf.php">
		
		<div id="formulario">

			<div style="margin-bottom: 10px; font-weight: bold">Quais as rendas que possui</div>
			<div style="margin-bottom: 20px">
				<input type="checkbox" name="pro_lore" value="Pró-Labore" id="pro_lore" class="perguntaRendas" style="margin-bottom: 10px;" > Apenas as retiradas de pró-lore e distribuição de lucros da minha empresa<br>
				<input type="checkbox" name="honorarios" value="Profisional Liberal" id="honorarios" class="perguntaRendas" style="margin-bottom: 10px;" > Honorários de profissional liberal (sem empresa)<br>
				<input type="checkbox" name="clt" value="Profisional Liberal" id="clt" class="perguntaRendas" style="margin-bottom: 10px;" > Salário CLT<br>
				<input type="checkbox" name="alugueis" value="Alugueis" id="alugueis" class="perguntaRendas" style="margin-bottom: 10px;" > Renda de Alugueis<br>
				<input type="checkbox" name="quais_outras_rendas_ativa" value="Outras" id="quais_outras_rendas_ativa" class="perguntaRendas" > Outras&nbsp;&nbsp;&nbsp;Quais <input type="text" id="quais_outras_rendas" class="perguntaRendas" name="quais_outras_rendas" size="40" maxlength="250">
			</div>

			<div style="margin-bottom: 10px; font-weight: bold">Possui dependentes com rendas?</div>
			<div style="margin-bottom: 20px">
				<input id="dependentesSim" type="radio" name="dependentes" value="Sim"> Sim&nbsp;&nbsp;&nbsp;Quais <input type="text" id="dependentes_quais" name="dependentes_quais" size="40" maxlength="250"><br>
				<input type="radio" name="dependentes" value="Não" id="dependentesNao" checked> Não<br>
			</div>

			<div class="tituloAzul" style="margin-bottom: 20px">Seus Dados</div>


				<div style="float: left; text-align: right; width: 120px; margin-right: 10px">Nome: </div>
				<div style="float: left; text-align: left">
					<input type="text" id="nome" name="nome" size="30" maxlength="30">
				</div>
				<div style="clear: both; height: 10px"></div>

				<div style="float: left; text-align: right; width: 120px; margin-right: 10px">CPF:</div>
				<div style="float: left; text-align: left">
					<input type="text" class="campoCPF" id="cpf_resp" name="cpf_resp" size="14" maxlength="14">
				</div>
				<div style="clear: both; height: 10px"></div>

				<div style="float: left; text-align: right; width: 120px; margin-right: 10px">Data de Nascimento: </div>
				<div style="float: left; text-align: left">
					<input type="text" class="campoData" id="nascimento" name="nascimento" size="12" maxlength="12"> (do sócio-responsável)
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
					<input id="whatsappAtivo" type="checkbox" name="whatsapp" onClick="javascript:abreDiv('numero_whatsapp')"> Sim
				</div>
				<div id="numero_whatsapp" style="float:left; display: none">
					<input type="text" id="numeroWhatsapp" name='numeroWhatsapp' size="14" maxlength="14">
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
			
			<div id="tarifaPadrao" style="display:none;">
				<div class="tituloVermelho">Tarifa Padrão</div>
				Seu perfil de contribuindo se encaixa em nossa tarifa padrão de R$ 300 para elaboração do Imposto de Renda Pessoa Física. O serviço inclui
				<ul>
					<li>Análise e cálcula das rendas</li>
					<li>Preenchimento e envio da declaração</li>
					<li>Parcelamento do Impsoto a restituir (quandor for o caso)</li>
				</ul>

				<input type="submit" id="paginaPagamento" value="Contratar" />
				<div class="divCarregando2" style="margin-top:10px; text-align:center;display:none">
					<img src="images/loading.gif" width="16" height="16">
				</div>
				<div class="divCarregando1" style="margin-top:10px; text-align:center;display:none">
					<img src="images/loading.gif" width="16" height="16">
				</div>					
			</div>
		</div>
	</form>	
</div>

<script>

	$(function(){
		
		$('#paginaPagamento').click(function(){
			
			$("#paginaPagamento").hide();
			$(".divCarregando1").css("display","table-row");
			$('#formIRPF').submit();
			
		});	
		
		$('#btProsseguir').click(function(){
			
			var status = validaCampos();
			
			if(status) {
				
				console.log($('#dependentesNao').is(':checked'));
				console.log($('#pro_lore').is(':checked'));
				
				if($('#dependentesNao').is(':checked') && $('#pro_lore').is(':checked')) { 
					
					$('#formulario').hide();
					$('#tarifaPadrao').show();
					
				} else {
					
					$("#btProsseguir").hide();
					$(".divCarregando1").css("display","table-row");
					$('#formIRPF').submit();
				}
			}			
		});
		
		function validaCampos(){
			
			var statusPerguntaRendas = false;
			
			// valida perguntas.			
//			$('.perguntaRendas').each(function(){
//				
//				if($(this).is(':checked')){
//					statusPerguntaRendas = true;
//				}
//				
//			});
//			
//			if(!statusPerguntaRendas){
//				alert('Informe uma dos rendas que possui');
//				return false;
//			}
			
			if($('#quais_outras_rendas').val().length <= 0 && $('#quais_outras_rendas_ativa').is(':checked')) {
				alert('Informe quais outras rendas');
				$(this).focus();
				return false;
			}
			
			if($('#dependentes_quais').val().length <= 0 && $('#dependentesSim').is(':checked')) {
				alert('Informe quantos dependentes');
				$(this).focus();
				return false;
			}
			
			
			// Valida dados do cliente.	
			if($('#nome').val().length <= 0){
				alert('Informe o nome');
				$(this).focus();
				return false;
			}
			
			if($('#cpf_resp').val().length <= 0){
				alert('Informe o CPF');
				$(this).focus();
				return false;
			}
			
			if($('#nascimento').val().length <= 0){
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
			
			if($('#numeroWhatsapp').val().length <= 0 && $('#whatsappAtivo').is(':checked')) {
				alert('Informe o whatsapp');
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
	
	//Não envia o form ao clicar no enter
	$('#formIRPF').keypress(function(e) {
		if(e.which == 13) {
		  e.preventDefault();
		  console.log('Não envia o form ao clicar no enter');
		}
	});	
	
</script>

<?php include 'rodape.php' ?>
