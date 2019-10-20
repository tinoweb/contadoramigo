
<?php include 'header.php' ?>
<script language="JavaScript">
function validaForm(form) {	
	if (form.txtEmail.value==''){
		alert('Digite o Email.');
		form.txtEmail.focus();
	}else{		
		if (form.txtSenha.value==''){
			alert('Digite a Senha.');
			form.txtSenha.focus();
		}else{		
			form.submit();
		}
	}
}
</script>
<div class="principal">
  <div class="titulo" style="margin-bottom:10px;">Imprensa</div>
  
  <div style="width:740px">
  
 Os arquivos a seguir são disponibilizados para uso livre por parte da Impresa, com a finalidade exclusiva de divulgação do Portal Contador Amigo. Qualquer outro uso das imagens e textos anexos é terminatemente proibido.<br />
  <br />
  <span class="tituloVermelho"><br />
  Textos</span><br />
<br />
  <img src="images/pdficon_large.gif" alt="" width="32" height="32" /> Release de Lançamento<br />
  <br />
  <img src="images/pdficon_large.gif" alt="" width="32" height="32" /> VAD - Estúdio Multimídia<br />
  <br />
  <span class="destaqueAzul"><br />
  </span><span class="tituloVermelho">Imagens</span><br />
  <br />
  <img src="images/Adobe_Illustrator_JPEG.png" alt="" width="32" height="32" />Vitor Maradei - Diretor do Portal<br />
  <br />
  <img src="images/Adobe_Illustrator_JPEG.png" alt="" width="32" height="32" />Print Screen - Homepage <br />
  <br />
  <img src="images/Adobe_Illustrator_JPEG.png" alt="" width="32" height="32" />Print Screen - Área Restrita<br />
  <br />
  <br />
  <br />
  <br />
  <img src="images/get_adobe_reader.png" alt="" width="158" height="39" /> <br />
<br />
  </div>
</div>
<?php include 'rodape.php' ?>
