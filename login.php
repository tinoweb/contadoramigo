<?php session_start(); $paginaAssinatura = '';?>
<?php $nome_meta = "assinatura_orientacoes"; ?>

<?php //include 'meta_assinatura_orientacoes.php' ?>
<?php include 'header.php' ?>
<script type="text/javascript">

	jQuery(document).ready(function() {		

		$('#btProsseguir').click(function(e){
			e.preventDefault();
			formSubmitAssina();
		});
		
		$('#link_aviseme').bind('click',function(e){
	//		if($('#textoAviseme').css('margin-bottom') == '20px'){
	//			$('#textoAviseme').css('margin-bottom','0');
	//		} 

			e.preventDefault();
			
			if($('#mensagemEnvio').length > 0){
				if($('#mensagemEnvio').css('display') == 'block'){
					$('#mensagemEnvio').css('display','none');
				}
			}
			
			if($('#formAvise').length > 0){
				$('#formAvise').toggle();
				if($('#formAvise').css('display') == 'none'){
					$('#textoAviseme').css('margin-bottom','0');
				} else {
					$('#textoAviseme').css('margin-bottom','20px');
				}
			}
		});


		$('#selEstado').bind('change',function(){
			var arrDadosEstado = $('#selEstado').val().split(';');
			var idUF = arrDadosEstado[0];
			$.getJSON('consultas.php?opcao=cidades&valor='+idUF, function (dados){ 
				if (dados.length > 0){
					var option = '<option></option>';
					$.each(dados, function(i, obj){
						option += '<option value="'+obj.cidade+'">'+obj.cidade+'</option>';
						})
					$('#txtCidade').html(option).show();
				}
			});
		});
		
	});

		
	var msg1 = 'É necessário preencher o campo';
	var msg2 = 'É necessário selecionar ';
	var msg3 = 'No momento o Contador Amigo não oferece suporte para empresas do ramo de Comércio e Indústria.';
	var msg4 = 'No momento o Contador Amigo não oferece suporte para empresas com Lucro Presumido.';

	function fnValidaEmail(email){

		var v_email = email.value;
		var jSintaxe;
		var jArroba;
		var jPontos;

		var ExpReg = new RegExp('[^a-zA-Z0-9\.@_-]', 'g');

	        jSintaxe = !ExpReg.test(v_email);
		if(jSintaxe == false){
	            window.alert('Favor digitar o e-mail corretamente!');
	            return false;
		}
		jPontos = (v_email.indexOf('.') > 0) && !(v_email.indexOf('..') > 0);
		if (jPontos == false){
	            window.alert('Favor digitar o e-mail corretamente!');
	            return false;
		}
		jArroba = (v_email.indexOf('@') > 0) && (v_email.indexOf('@') == v_email.lastIndexOf('@'));
		if (jArroba == false){
	            window.alert('Favor digitar o e-mail corretamente!');
	            return false;
		}

		return true;

	}




	function consultaDadosExistentes(){
		prosseguir = true;
		if(($('#txtCNPJ').val() != '') && ($('#txtEmailAssina').val() != '')){
			$.ajax({
			  url:'assinatura_checa_dados.php',
			  data: 'cnpj=' + $('#txtCNPJ').val() + '&email=' + $('#txtEmailAssina').val(),
			  type: 'get',
			  cache: false,
			  async: false,
			  beforeSend: function(){
				$("#hidPassCNPJ").val('');
				$("#hidPass").val('');
				$('#btProsseguir').val('Checando...').attr('disabled',true);

			  },
			  success: function(retorno){
				$('#hidPassCNPJ').val(retorno.split("|")[0]);
				if(retorno.split("|")[0] > 0){
					$('#txtCNPJ').focus();
					alert('O CNPJ já está cadastrado em nosso sistema.');
					prosseguir = false;
					$('#btProsseguir').val('Prosseguir').attr('disabled',false);

				}
				$('#hidPass').val(retorno.split("|")[1]);
				if(retorno.split("|")[1] > 0){
					$('#txtEmailAssina').focus();
					alert('O E-mail já está cadastrado em nosso sistema.');
					prosseguir = false;
					$('#btProsseguir').val('Prosseguir').attr('disabled',false);
				}
			  }
			});
		}	
		return prosseguir;
	}
		function salvarDadosEMKT(){
			var nome = $("#txtAssinante").val();
			var email = $("#txtEmailAssina").val();

			$("#avisoNomePessoal").val(nome);
			$("#avisoEmailPessoal").val(email);

			if( nome != "" && email != '' )
				$("#form_aviso").submit();
			
		}
	function formSubmitAssina(){ 

			var erro = false;
				var valor = $("#selRamoAtividade").val();
				if( valor === "Comércio" ){
					erro = true;
					alert("Infelizmente o Contador Amigo ainda não atende empresas do ramo do Comércio e Indústria. Deixaremos seu e-mail guardado e lhe informaremos quando este serviço estiver disponível.");
					salvarDadosEMKT();
					return false;
				}
				if( valor === "Indústria" ){
					erro = true;
					alert("Infelizmente o Contador Amigo ainda não atende empresas do ramo do Comércio e Indústria. Deixaremos seu e-mail guardado e lhe informaremos quando este serviço estiver disponível.");
					salvarDadosEMKT();
					return false;
				}

				if( valor === "" ){
					alert("Selecione o ramo de atividade")
					return;
				}



	            if( validElement('txtAssinante', msg1) == false){document.getElementById('txtAssinante').focus(); return false;}
	            if( validElement('txtEmailAssina', msg1)== false){
	                document.getElementById('txtEmailAssina').focus(); return false;
	            } 
				if( validElement('txtPrefixoTelefoneCobranca', msg1) == false){document.getElementById('txtPrefixoTelefoneCobranca').focus(); return false;}
	            if( validElement('txtTelefoneCobranca', msg1) == false){document.getElementById('txtTelefoneCobranca').focus(); return false;}

	            if( validElement('txtSenhaAssina', msg1) == false){document.getElementById('txtSenhaAssina').focus(); return false;}
				if(document.getElementById('txtSenhaAssina').value.length < 8) {
					window.alert('A senha deve ter no mínimo 8 caracteres.');
	                return false;
				}
	            if( validElement('txtConfirmaSenhaAssina', msg1) == false){document.getElementById('txtConfirmaSenhaAssina').focus(); return false;}
				var Senha = document.getElementById('txtSenhaAssina').value;
				var ConfirmaSenha = document.getElementById('txtConfirmaSenhaAssina').value;

				if(Senha != ConfirmaSenha) {
					window.alert('As senhas não conferem.');
	                document.getElementById('txtConfirmaSenhaAssina').select(); 
					return false;
				}

				if(document.getElementById('hidPass').value == "erro") {
					alert('O e-mail já está cadastrado em nosso sistema.');
					return false;
				} 
				if(document.getElementById('cheTermos').checked == false) {
					window.alert('É necessário concordar com termos e condições de serviço.');
	               	return false;
				}

				var email = document.getElementById('txtEmailAssina');
				if(fnValidaEmail(email) == false){
					document.getElementById('txtEmailAssina').focus();
					return false;
				}

				if( erro === true ){
					salvarDadosEMKT();
					return false;
				}

				if(consultaDadosExistentes()){
					if( erro === false )
						document.getElementById('frmAssinatura').submit();

				}

							
	 }
</script>


<div class="principal">

	<div class="minHeight">
	  <div style="clear:both; height:80px"></div>
	  
		<div style="float:left; margin-top:40px; width: 101px;">
		<img src="images/boneca4.png" width="101" height="197" alt=""/> 
		</div>
							
		<div class="bubble_left" style="width:255px; margin-left:14px; margin-right:24px; float:left;"> 
		<div style="padding:20px"> 
		<div class="saudacao">Faça Login para continuar</div>
		<div>Caso você ainda não possua uma conta no Contador Amigo, clique <a href="assinatura.php" title="Assinar Contador amigo">aqui</a> para se cadastrar.<br>
		</div>
		</div> 
		</div> 
		  
		   
		<script>

			$("#entrar").remove();
			$(".botao_login").css("display","none");

		</script>

		 <div style="float:left; width:200px">
		 	<form name="login" id="login" action="auto_login.php?login" method="post" onkeypress="if (event.keyCode == 13) enterLogin()" style="display:inline">
				<div style="margin-bottom:3px">
		        	<span style="color:#336699; margin-right:5px">Email: </span>
		            <input type="text" name="txtEmail" value="" maxlength="60" style="width:140px; height:20px; border-style:solid; border-width:1px; border-color:#cccccc" id="txtEmail" alt="Email">
		        </div> 
				<div style="margin-bottom:11px">
		        	<span style="color:#336699">Senha: </span>
		            <input type="password" name="txtSenha" value="" maxlength="60" id="txtSenha" style="width:65px; margin-right:5px; height:20px;  border-style:solid; border-width:1px; border-color:#cccccc" alt="Senha"> <a class="linkCinza" href="envio_senha.php" style="font-size:11px">Não lembro</a>
		        </div>
				<div style="margin-bottom:8px">
		        	<input name="cheConectado" type="checkbox" id="cheConectado" value="" checked="checked"> Manter-me conectado
				</div>
				<div style="text-align:center">
		        	<input type="button" value="Entrar" onclick="enterLogin()" name="button">
				</div>
			</form>
		</div>
	     
	</div>

	<div style="clear:both"></div>
</div>

<? unset($_SESSION["email_aviso_enviado"]) ?>

</div>
<div class="index_mobile" style="display:none">

  <?php include 'login_mobile.php'; ?>

</div>

<div style="clear:both"></div>


<?php include 'rodape.php' ?>

<?php 
if(isset($_GET['erro']) || isset($_GET['erro'])) { ?>
<script>
javascript:abreDiv('entrar');
</script>
<?php ; } ?>
