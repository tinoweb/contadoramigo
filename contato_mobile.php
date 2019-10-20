<?php include 'header_mobile.php' ?>
<?php include 'menu_mobile2.php' ?>
<title>Entre em Contato com o Contador Amigo</title>
<div class="principal">

<h1 style="font-size:1em">Contato</h1>

<?php if( isset( $_GET['sucesso'] ) ):
	
	echo '<h2>Sua dúvida foi enviada com sucesso.<br>Em breve entraremos em contato.</h2><br>';

endif; ?>

<form action="contato_envio.php?mobile" method="post" name="form" id="form" style="display:inline">

<div style="float:left; width:15%" >Nome:</div><div style="float:left; width:80%"><input name="NomePessoal" type="text" id="NomePessoal" style="border:1px solid #cccccc;width:98%;" maxlength="200"  alt="Nome"  /></div>
<div style="clear:both; height:10px"></div>

<div style="float:left; width:15%" >E-mail:</div><div style="float:left; width:80%"><input type="text" name="emailPessoal" id="emailPessoal" style="border:1px solid #cccccc;width:98%" maxlength="200" alt="E-mail" /></div>
<div style="clear:both; height:10px"></div>

<div style="float:left; width:15%" >Fone:</div>
<div style="float:left; width:20%"><input name="DDDPessoal" type="text" id="DDDPessoal" style="border:1px solid #cccccc;width:80%" maxlength="2" alt="Prefixo do Telefone para Cobrança" /></div>
<div style="float:left; width:60%"><input name="telPessoal" type="text" id="telPessoal" style="border:1px solid #cccccc;width:98%" maxlength="9" alt="Telefone" /></div>
<div style="clear:both; height:20px"></div>

<div style="width:100%; margin-bottom:10px" >Mensagem:<br>
<textarea name="Mensagem" rows="10" id="Mensagem" style="border:1px solid #cccccc;width:94%"></textarea>
</div>

<div style="width:100%; text-align:center; clear:both; margin-bottom:10px"><input type="button" value="Prosseguir" id="btProsseguir" /></div>

</form>

</div>
<script>

	$("#btProsseguir").click(function(event) {
		

		if (document.getElementById('NomePessoal').value=="") {
			alert('Preencha o campo Nome'); document.getElementById('NomePessoal').focus();
			return false;
		}
		if (document.getElementById('emailPessoal').value=="") {
			alert('Preencha o campo E-mail'); document.getElementById('emailPessoal').focus();
			return false;
		}
		if (document.getElementById('DDDPessoal').value=="") {
			alert('Preencha o campo DDD'); document.getElementById('DDDPessoal').focus();
			return false;
		}
		if (document.getElementById('telPessoal').value=="") {
			alert('Preencha o campo Telefone'); document.getElementById('telPessoal').focus();
			return false;
		}
		if (document.getElementById('Mensagem').value=="") {
			alert('Escreva sua mensagem'); document.getElementById('Mensagem').focus();
			return false;
		}
		
		
		document.getElementById('form').submit()
		

	});

</script>

<?php include 'rodape_mobile.php' ?>
