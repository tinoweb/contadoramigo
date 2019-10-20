<?php include '../conect.php';
include '../session.php';
include 'check_login.php' ?>
<?php
$id = $_GET["id"];
$_SESSION['linha_id'] = $_GET["id"];
$sql = "SELECT * FROM faq WHERE id_faq='" . $id . "' LIMIT 0, 1";
$resultado = mysql_query($sql)
or die (mysql_error());

if($linha=mysql_fetch_array($resultado)){
	$id = $linha['id_faq'];
	$pergunta = urldecode($linha['pergunta']);
	$resposta = urldecode($linha['resposta']);
}

function selected( $value, $prev ){
   return $value==$prev ? ' selected="selected"' : ''; 
};
function checked( $value, $prev ){
   return $value==$prev ? ' checked="checked"' : ''; 
};


?>
<?php include 'header.php' ?>

<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>

<script type="text/javascript">
	
	$(document).ready(function(e) {

  });
	
</script>

<div class="principal">
<div class="titulo" style="margin-bottom:20px">Editar FAQ</div>
<div style="width:450px; float:left;">

<div class="tituloVermelho">Dados</div>
<form name="form_dados_cobranca" id="form_dados_cobranca" method="post" action="faq_administrar_gravar.php">
<table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
  <tr>
    <td colspan="2" align="right" valign="top" class="formTabela">&nbsp;</td>
  </tr>
  <tr>
    <td width="123" align="right" valign="top" class="formTabela">Pergunta:</td>
    <td class="formTabela" width="840"><textarea name="txtPergunta" id="txtPergunta" style="width:840px;height:100px;margin-bottom:0px; font-size: 12px;"><?=$pergunta?></textarea>
<!--		<script type="text/javascript">
    CKEDITOR.replace( 'txtPergunta',
    {
      language: ['pt-br'],
	   	disableNativeSpellChecker:true,
		  scayt_autoStartup:true,
			scayt_sLang: 'pt_BR',
      toolbar: 
      [
        ['Source'],['Bold', 'Italic', '-', 'Link', 'Unlink','-','SpellChecker', 'Scayt'],['TextColor']
      ],
      width: [ '840px' ],
      height: [ '180px' ],
      contentsCss : ['style.css']/*,
      forcePasteAsPlainText:[ false ]*/
    });
    </script>-->

    </td>
  </tr>
  <tr>
    <td colspan="2" align="right" valign="top" class="formTabela">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" valign="top" class="formTabela">Resposta:</td>
    <td class="formTabela"><textarea name="txtResposta" id="txtResposta" style="width:840px;height:200px;margin-bottom:0px"><?=($resposta)?></textarea>
		<script type="text/javascript">
    CKEDITOR.replace( 'txtResposta',
    {
      language: ['pt-br'],
	   	disableNativeSpellChecker:true,
		  scayt_autoStartup:true,
			scayt_sLang: 'pt_BR',
      toolbar: 
      [
        ['Source'],['Bold', 'Italic', '-', 'Link', 'Unlink','-','SpellChecker', 'Scayt'],['TextColor']
      ],
      width: [ '840px' ],
      height: [ '180px' ],
      contentsCss : ['style.css']/*,
      forcePasteAsPlainText:[ false ]*/
    });
    </script>
    </td>
  </tr>
  <tr>
    <td colspan="2" valign="middle" class="formTabela">
    <input type="hidden" name="hidID" id="hidID" value="<?=$id?>" />
    <input type="hidden" name="hidPerguntaTxt" id="hidPerguntaTxt" value="" /></td>
  </tr>
  <tr>
    <td colspan="2" align="center" valign="middle" class="formTabela"><input name="btnSalvar" type="submit" id="btnSalvar"  value="Salvar"/>&nbsp;<input name="btnCancelar" type="button" id="btnCancelar"  value="Cancelar" onclick="location.href='faq_lista.php'" /></td>
  </tr>
</table>
</form>
<br />
</div>

<script>

$(document).ready(function(e) {
	
	$('#btnSalvar').click(function(event){
		event.preventDefault();

		var perguntaOrdem = $('#txtPergunta').val();//CKEDITOR.instances['txtPergunta'].document.getBody().getText();

		$('#hidPerguntaTxt').val(perguntaOrdem);

		$('#form_dados_cobranca').submit();
		
	});
	
});

function enviaMensagem(arquivoMensagem, assuntoMensagem){
	$.ajax({
		url: '../enviar_mensagens.php',
		type: 'POST',
		cache:false, 
		data: 'nomeMensagem=<?=addslashes($linhalogin["assinante"])?>&emailMensagem=<?=$linhalogin["email"]?>&assuntoMensagem=' + assuntoMensagem + '&arquivoMensagem=' + arquivoMensagem,
		success: function(resultado){
		}
	});
}

</script>

<div style="clear:both"></div>
</div>


<?php include '../rodape.php' ?>