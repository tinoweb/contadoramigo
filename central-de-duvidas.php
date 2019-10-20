<?php 
	
	include 'header.php';

	include 'cidade-estado.class.php';

?>
<div class="principal minHeight">
	<div style="width:100%; float:left">
		<br>
		<h1>Atendimento especial ao MEI</h1>
		<!-- <span style="font-size:14px">
			Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor
		</span><br> -->
		<br>
		<form style="width:420px;" name="formCentraDuvidas" id="formCentraDuvidas" method="post" action="central-de-duvidas.class.php">
			<h2>Informe seus dados</h2>
			<table border="0" cellpadding="0" cellspacing="3" style="margin-bottom:20px;" class="formTabela">
				<tr>
					<td align="right" valign="top" class="formTabela">Nome:</td>
					<td valign="top" class="formTabela">
						<input name="nome" type="text" id="nome" style="width:300px; margin-bottom:0px" maxlength="200"  alt="Nome"  />
					</td>
				</tr>
				<tr>
					<td align="right" valign="top" class="formTabela">E-mail:</td>
					<td valign="top" class="formTabela">
						<input type="text" name="email" id="email" style="width:300px" maxlength="200" alt="E-mail" />
					</td>
				</tr>
				<tr>
					<td align="right" valign="top" class="formTabela">Nome da empresa:</td>
					<td valign="top" class="formTabela">
						<input type="text" name="nome_empresa" id="nome_empresa" style="width:300px" maxlength="200" alt="Nome da empresa" />
					</td>
				</tr>
				<tr>
					<td align="right" valign="top" class="formTabela">CNPJ:</td>
					<td valign="top" class="formTabela">
						<input style="width:120px;" type="text" name="cnpj" id="cnpj" class="campoCNPJ" style="width:300px" maxlength="200" alt="Nome da empresa" />
					</td>
				</tr>
				<tr>
					<td align="right" valign="top" class="formTabela">Telefone:</td>
					<td valign="top" class="formTabela">
						<input type="text" name="pref_telefone" id="pref_telefone" style="width:20px" maxlength="2" alt="Prefixo telefone" size="2" />
						<input type="text" name="telefone" id="telefone" style="width:100px" maxlength="9" alt="Telefone" size="10" />
					</td>
				</tr>
				<tr>
        			<td align="right" valign="middle" class="formTabela">UF:</td>
    				<td class="formTabela">
      					<select name="estado" id="estado" alt="UF">
          					<option value=""></option>
          					<?php $estado = new Estado('estado','cidade'); ?>
          					<?php echo $estado->getoptions(); ?>
          				</select>
        			</td>
      			</tr>
      			<tr>
        			<td align="right" valign="middle" class="formTabela">Cidade:</td>
    				<td class="formTabela">
      					<select style="min-width:100px;" name="cidade" id="cidade" alt="Cidade"></select>
        			</td>
      			</tr>
      			<input type="hidden" name="tag" value="">
      			<script> <?php echo $estado->getscript(); ?> </script>
			</table>
			<div style="width:420px;">
				<h2>Digite a mensagem</h2>
				<textarea name="mensagem" id="mensagem" style="width: 100%;height: 100px;"></textarea><br><br>
			</div>
			<?php if( isset( $_GET['sucesso'] ) ):
			
				echo '<span class="tituloVermelho">Sua dúvida foi enviada com sucesso. Em breve sera analisada e entraremos em contato.</span><br><br>';
			
			endif; ?>
			<input type="button" value="Enviar" id="btEnviar" />
		</form>
	</div>
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
