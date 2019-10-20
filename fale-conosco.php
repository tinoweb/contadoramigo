<?php $nome_meta = "fale_conosco"; ?>
<?php include 'header.php' ?>


<?php 
include 'cidade-estado.class.php';
?>

<div class="principal minHeight">
	
		
		<h1>Fale Conosco</h1>
		<h2 >
			Tire suas dúvidas sobre os impostos, declarações e demais obrigações relativas à sua ME ou MEI. 
		</h2>
		<?php if( isset( $_GET['sucesso'] ) ):
			
				echo '<style type="text/css" media="screen">
					#formCentraDuvidas{display:none;}
				</style>';
			
			endif; ?>
		<form  name="formCentraDuvidas" id="formCentraDuvidas" method="post" action="central-de-duvidas.class.php">
			<h3>Informe seus dados</h3>
			<table border="0" cellpadding="0" cellspacing="3" style="margin-bottom:20px; width: 100%; max-width: 500px">
				<tr>
					<td align="right" valign="top" style="width: 52px;">Nome:</td>
					<td valign="top">
						<input name="nome" type="text" id="nome" style="width:100%; margin-bottom:0px" maxlength="200"  alt="Nome"  />
					</td>
				</tr>
				<tr>
					<td align="right" valign="top">E-mail:</td>
					<td valign="top">
						<input type="text" name="email" id="email" style="width:100%" maxlength="200" alt="E-mail" />
					</td>
				</tr>
				<!--tr>
					<td align="right" valign="top">Empresa:</td>
					<td valign="top">
						<input type="text" name="nome_empresa" id="nome_empresa" style="width:100%" maxlength="200" alt="Nome da empresa" />
					</td>
				</tr-->
				<!--tr>
					<td align="right" valign="top">CNPJ:</td>
					<td valign="top">
						<input style="width:60%" type="text" name="cnpj" id="cnpj" class="campoCNPJ" style="width:300px" maxlength="200" alt="Nome da empresa" />
					</td>
				</tr-->
				<tr>
					<td align="right" valign="top">Telefone:</td>
					<td valign="top">
						<input type="text" name="pref_telefone" id="pref_telefone" style="width:10%" maxlength="2" alt="Prefixo telefone" size="2" />
						<input type="text" name="telefone" id="telefone" style="width:40%" maxlength="9" alt="Telefone" size="10" />
					</td>
				</tr>
				<!--tr>
        			<td align="right" valign="middle">UF:</td>
    				<td>
      					<select name="estado" id="estado" alt="UF">
          					<option value=""></option>
          					<?php $estado = new Estado('estado','cidade'); ?>
          					<?php echo $estado->getoptions(); ?>
          				</select>
        			</td>
      			</tr-->
      			<!--tr>
        			<td align="right" valign="middle">Cidade:</td>
    				<td>
      					<select style="min-width:40%" name="cidade" id="cidade" alt="Cidade"></select>
        			</td>
      			</tr-->
      			<input type="hidden" name="tag" value="">
      			<script> <?php echo $estado->getscript(); ?> </script>
			</table>
			<div style="width: 100%; max-width: 500px">
				<h3>Digite a mensagem</h3>
				<textarea name="mensagem" id="mensagem" style="width: 100%; height: 100px"></textarea><br><br>
			</div>
			<input type="button" value="Enviar" id="btEnviar" />
			<div id="load" style="width:100px; text-align:center; display:none;">
	    		<img src="../images/loading.gif" width="16" height="16">
	    	</div>
		</form>
		<?php 
			if( isset( $_GET['sucesso'] ) ):
				echo '<span style="font-size:14px">Sua dúvida foi enviada com sucesso.<br>Em breve entraremos em contato.</span><br><br>';
				echo '<input type="button" value="Voltar" onclick="location=\'fale-conosco.php\'" />';
			endif; 
		?>
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
			/*if( $("#cnpj").val() === '' ){
				$("#cnpj").focus();
				alert("Informe o CNPJ da empresa.");
				return;
			}*/
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

			$(this).hide();
			$('#load').show();		
			
			$("#formCentraDuvidas").submit();			
		});
		
		function isEmail(email) {
		  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		  return regex.test(email);
		}

	});

</script>

<?php include 'rodape.php'; ?>