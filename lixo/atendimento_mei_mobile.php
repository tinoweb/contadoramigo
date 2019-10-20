<?php include 'header_mobile.php' ?>
<?php 
	$nome_meta = 'atendimento_mei';	
	include 'cidade-estado.class.php';
?>
<?php include 'menu_mobile2.php' ?>
<div class="principal">
	<h1>Atendimento ao MEI</h1>
	<!-- <span style="font-size:14px">
		Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor
	</span><br> -->
	<h2 style="font-family: 'Open Sans', sans-serif; font-size:12px; color:#666666; margin-bottom: 20px">
		Esta central de atendimento é um serviço gratuito oferecido pelo Contador Amigo ao Microempreendedor Individual para solucionar quaisquer dúvidas relacionadas à sua empresa. Envie sua pergunta, estamos à disposição!
	</h2>
	<?php 
			if( isset( $_GET['sucesso'] ) ):
				echo '<h2 class="tituloVermelho">Sua dúvida foi enviada com sucesso.<br>Em breve entraremos em contato.</h2><br>';
				echo '<input type="button" value="Voltar" onclick="location=\'atendimento_mei_mobile.php\'"/>';
			endif; 
	?>
	<?php 
		if( isset( $_GET['sucesso'] ) ):
			echo '<style type="text/css" media="screen">#formCentraDuvidas{display:none;}</style>';
		endif; 
	?>
	<form style="width:100%;" name="formCentraDuvidas" id="formCentraDuvidas" method="post" action="central-de-duvidas.class.php?mobile">
		<span class="tituloVermelho">Informe seus dados</span><br><br>

		<div style="float:left; width:120px;max-width:20%" >
			Nome:
		</div>
		<div style="float:left; width:75%">
			<input name="nome" type="text" id="nome" style="min-width: 200px;max-width:280px;width: 96%;; margin-bottom:0px" maxlength="200"  alt="Nome"  />
		</div>
		<div style="clear:both; height:10px"></div>


		<div style="float:left; width:120px;max-width:20%" >
			E-mail:
		</div>
		<div style="float:left; width:75%">
			<input type="text" name="email" id="email" style="min-width: 200px;max-width:280px;width: 96%;" maxlength="200" alt="E-mail" />
		</div>
		<div style="clear:both; height:10px"></div>

		<div style="float:left; width:120px;max-width:20%" >
			Empresa:
		</div>
		<div style="float:left; width:75%">
			<input type="text" name="nome_empresa" id="nome_empresa" style="min-width: 200px;max-width:280px;width: 96%;" maxlength="200" alt="Nome da empresa" />
		</div>
		<div style="clear:both; height:10px"></div>

		<div style="float:left; width:120px;max-width:20%" >
			CNPJ:
		</div>
		<div style="float:left; width:75%">
			<input type="text" name="cnpj" id="cnpj" class="campoCNPJ" style="max-width:170px" size="30" alt="Nome da empresa" />
		</div>
		<div style="clear:both; height:10px"></div>

		<div style="float:left; width:120px;max-width:20%" >
			Telefone:
		</div>
		<div style="float:left; width:75%">
			<input type="text" name="pref_telefone" id="pref_telefone" style="width:20px" maxlength="2" alt="Prefixo telefone" size="2" />
			<input type="text" name="telefone" id="telefone" style="width:100px" maxlength="9" alt="Telefone" size="10" />
		</div>
		<div style="clear:both; height:10px"></div>

		<div style="float:left; width:120px;max-width:20%" >
			UF:
		</div>
		<div style="float:left; width:75%">
			<select name="estado" id="estado" alt="UF">
					<option value="">Selecione</option>
					<?php $estado = new Estado('estado','cidade'); ?>
					<?php echo $estado->getoptions(); ?>
				</select>
		</div>
		<div style="clear:both; height:10px"></div>

		<div style="float:left; width:120px;max-width:20%" >
			Cidade:
		</div>
		<div style="float:left; width:75%">
			<select style="min-width:100px;" name="cidade" id="cidade" alt="Cidade"></select>
		</div>
		<div style="clear:both; height:10px"></div>
		<input type="hidden" name="tag" value="">
		<script> <?php echo $estado->getscript(); ?> </script>
		<br>
		<div style="width:120px;max-width:35%" >
			<span class="tituloVermelho">Digite a mensagem:</span>
		</div>
		<!-- <div style="clear:both; height:1px"></diw> -->
		<div style="margin-top:5px;margin-bottom:10px;" >
			<textarea name="mensagem" id="mensagem" style="width: 96%;height: 100px;max-width:400px"></textarea><br>
		</div>
		<center>
			<input type="button" value="Enviar" id="btEnviar" />
		</center>
	</form>
</div>
<script>

	
	$( document ).ready(function() {
	    
		$("#btEnviar").click(function() {
			
			if( $("#nome").val() === '' ){
				$("#nome").focus();
				alert("Informe o nome.");
				return;
			}
			if( !isEmail( $("#email").val() ) || $("#email").val() === '' ){
				$("#email").focus();
				alert("Informe um email válido.");
				return;
			}
			if( $("#nome_empresa").val() === '' ){
				$("#nome_empresa").focus();
				alert("Informe o nome da empresa.");
				return;
			}
			if( $("#cnpj").val() === '' ){
				$("#cnpj").focus();
				alert("Informe o CNPJ da empresa.");
				return;
			}
			if( $("#pref_telefone").val() === '' ){
				$("#pref_telefone").focus();
				alert("Informe o prefixo do telefone.");
				return;
			}
			if( $("#telefone").val() === '' ){
				$("#telefone").focus();
				alert("Informe o telefone.");
				return;
			}
			if( $("#estado").val() === '' ){
				$("#estado").focus();
				alert("Informe o estado.");
				return;
			}
			if( $("#cidade").val() === '' ){
				$("#cidade").focus();
				alert("Informe a cidade.");
				return;
			}
			if( $("#mensagem").val() === '' ){
				$("#mensagem").focus();
				alert("Informe a mensagem.");
				return;
			}

			$("#formCentraDuvidas").submit();

		});
		
		function isEmail(email) {
		  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		  return regex.test(email);
		}

	});

</script>

<?php include 'rodape_mobile.php' ?>
