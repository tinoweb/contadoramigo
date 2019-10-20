<?php include '../conect.php';
include '../session.php';
include 'check_login.php' ?>
<?php include 'header.php' ?>
<?php
$codigo = $_GET["codigo"];

$sql = "SELECT * FROM suporte WHERE idPostagem='$codigo' LIMIT 0,1";
$resultado = mysql_query($sql)
or die (mysql_error());

$linha=mysql_fetch_array($resultado);

$data = date('d/m/Y', strtotime($linha["ultimaResposta"])) .  " às " . date('H:i', strtotime($linha["ultimaResposta"]));
$titulo = $linha["titulo"];
$nome = $linha["nome"];
$status = $linha["status"];
$id = $linha["id"];

function selected( $value, $prev ){
   return $value==$prev ? ' selected="selected"' : ''; 
};
?>
<div class="principal">

  <div class="titulo" style="margin-bottom:10px;">Suporte - Visualização do Chamado</div>

 <?php include 'suporte_menu.php' ?>

<div style="float:right; padding-left:20px; border-left:#113b63 solid 1px; width:820px">

<form method="post" action="suporte_muda_status.php">
<div style="float:right">Status: 
<select name="selMudaStatus" id="selMudaStatus">
<option value="Em análise" <?php echo selected( 'Em análise', $status ); ?>>Em análise</option>
<option value="Respondido" <?php echo selected( 'Respondido', $status ); ?>>Respondido</option>
</select>
<input type="hidden" name="hidCodigo" id="hidCodigo" value="<?=$codigo?>" />
<input type="submit" value="Alterar" />
</div>
</form>

<span class="tituloVermelho"><?=$titulo?> - Última postagem em <?=$data?></span><br />
<br />

<div id="conteudo_pagina" style="position:relative;float:left;">

<table border="0" cellpadding="10" cellspacing="2">
<tr>
	<th width="200" style="padding:4px">Interação</th>
    <th width="700" style="padding:4px">Mensagem</th>
  <?php 
$sql = "SELECT * FROM suporte WHERE idPostagem='$codigo' OR idPergunta='$codigo' ORDER BY idPostagem ASC";
$resultado = mysql_query($sql)
or die (mysql_error());

while ($linha=mysql_fetch_array($resultado)) { ?>  
  <tr <?php  if ($nome != $linha["nome"]) { echo 'style="background-color:#DEDDD6"'; } ?>>
	<td valign="top">
    	<strong><?php
        if ($nome == $linha["nome"]) { 
		echo '<a href="cliente_administrar.php?id=' . $id . '" target="_blank">' . $linha["nome"] . '</a>';
		} else {
		echo $linha["nome"];
		} ?></strong><br />
		<em><?=date('d/m/Y', strtotime($linha["data"]))?>, às<?=date('H:i', strtotime($linha["data"]))?></em>
                <br />
<br />
<?php  if ($nome != $linha["nome"]) { ?>
<a href="#" onClick="if (confirm('Você tem certeza que deseja excluir esta mensagem?'))location.href='suporte_excluir_postagem.php?codigo=<?=$codigo?>&linha=<?=$linha["idPostagem"]?>';"><img src="../images/del.png" width="24" height="23" border="0" title="Excluir" /></a>
	<a href="suporte_visualizar.php?codigo=<?=$codigo?>&editar=<?=$linha["idPostagem"]?>"><img src="../images/edit.png" width="24" height="23" border="0" title="Editar" /></a>
	<?php } ?>
    </td>
    <td valign="top"><?=$linha["mensagem"]?></td>
    </tr>
<?php } ?>
</table>


<br />


<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>

<script>
$(document).ready(function(e) {
  $('.perguntas_faq').hover(function(e){
		$(this).css('background-color','#eee');
	},function(e){
		$(this).css('background-color','transparent');
	});
	
  $('.perguntas_faq').bind('click',function(e){
		e.preventDefault();
		var selecionado = $('.perguntas_faq').index(this);

//		for(var id = 0; id <= $('.perguntas_faq').size(); id++){
//			if(id != selecionado){
				$('.perguntas_faq').css({
					'font-weight':'normal',
					'color':'#022846'
				});
				$('.respostas_faq').slideUp(200);
				$('.usar_resposta').css('display','none');
//			}
//		}
		
			$(this).css({
				'font-weight':'bold',
			});
	
		$('.respostas_faq').eq(selecionado).slideDown(200);
		$('.usar_resposta').eq(selecionado).css({
			'display':'block'
		}).bind('click',function(e){
			
			e.preventDefault();
			CKEDITOR.instances['txtMensagem'].setData($('.respostas_faq').eq(selecionado).html());
			$('.perguntas_faq').eq(selecionado).css('font-weight','normal');
			$('.respostas_faq').eq(selecionado).slideUp(200);
			$(this).css('display','none');
			
		});
	});

	$('#cad_FAQ').bind('click',function(ev){
		ev.preventDefault();
		
		if(CKEDITOR.instances['txtMensagem'].getData() == ''){
			alert('Preencha a resposta ao usuário ou selecione uma resposta padrão da tabela ao lado');
			CKEDITOR.instances['txtMensagem'].focus();
			return false;
		}
		if($('#txtPerguntaFAQ').val() == ''){
			alert('Preencha a pergunta para ser inserida nas opções da FAQ');
			$('#txtPerguntaFAQ').focus();
			return false;
		}

		$('#hidCadastrarFAQ').val('1');
		$('#hidPerguntaFAQ').val($('#txtPerguntaFAQ').val());

		$('#form_postagem').submit();
			
	});

});
</script>

<div id="tabela_form" style="position:relative;float:left;margin-top:20px;">

<?php
$editar = $_GET["editar"];

if($editar == "") { ?>
<form method="post" id="form_postagem" name="form_nova_postagem" action="suporte_nova_postagem.php">
<table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
  <tr>
    <td width="55" align="right" valign="top" class="formTabela">Nome:</td>
    <td width="400" class="formTabela"><input type="text" name="txtNome" id="txtNome" value="Suporte" style="width:400px" >
  </td>
  </tr>
  <tr>
    <td align="right" valign="top" class="formTabela">Mensagem:</td>
    <td class="formTabela">
    	<textarea name="txtMensagem" id="txtMensagem" style="width:400px; height:150px"></textarea>
		<script type="text/javascript">
		CKEDITOR.replace( 'txtMensagem',
		{
				language: ['pt-br'],
				toolbar: 
				[
					['Source'],['Bold', 'Italic', '-', 'Link', 'Unlink'],['TextColor']
				],
				width: [ '400px' ],
				height: [ '180px' ],
				contentsCss : ['style.css'],
				forcePasteAsPlainText:[ true ]
		});
		</script>

    </td>
  </tr>
  <tr>
    <td width="55" align="right" valign="top" class="formTabela">Status:</td>
    <td width="400" class="formTabela"><input type="radio" name="radStatus" id="radStatus" value="Em análise" /> <span style="margin-right:10px">Em análise</span>
    <input type="radio" name="radStatus" id="radStatus" value="Respondido" checked="checked" /> <span style="margin-right:10px">Respondido</span>
    </td>
  </tr>

  <tr>
    <td colspan="2" align="center" valign="top" class="formTabela">
    <input type="hidden" name="hidCodigo" id="hidCodigo" value="<?=$codigo?>" />
    <input type="hidden" name="hidNomeDestinatario" id="hidNomeDestinatario" value="<?=$nome?>" />
    <input type="hidden" name="hidTitulo" id="hidTitulo" value="<?=$titulo?>" />
    <input type="hidden" name="hidIdDestinatario" id="hidIdDestinatario" value="<?=$id?>" />
    <input type="hidden" name="hidCadastrarFAQ" id="hidCadastrarFAQ" value="" />
    <input type="hidden" name="hidPerguntaFAQ" id="hidPerguntaFAQ" value="" />
    <input type="submit" value="Enviar" /></td>
    </tr>
</table>
</form>
<?php } 
else { 
$sql = "SELECT * FROM suporte WHERE idPostagem='$editar'";
$resultado = mysql_query($sql)
or die (mysql_error());

$linha=mysql_fetch_array($resultado);

?>
<form method="post" id="form_postagem" name="form_editar_postagem" action="suporte_editar_postagem.php">
<table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
  <tr>
    <td width="55" align="right" valign="top" class="formTabela">Nome:</td>
    <td width="400" class="formTabela"><input type="text" name="txtNome" id="txtNome" value="<?=$linha["nome"]?>" style="width:400px" >
  </td>
  </tr>
  <tr>
    <td align="right" valign="top" class="formTabela">Mensagem:</td>
    <td class="formTabela">
    	<textarea name="txtMensagem" id="txtMensagem" style="width:400px; height:150px"><?=$linha["mensagem"]?></textarea>
		<script type="text/javascript">
		CKEDITOR.replace( 'txtMensagem',
		{
				language: ['pt-br'],
				toolbar: 
				[
					['Source'],['Bold', 'Italic', '-', 'Link', 'Unlink'],['TextColor']
				],
				width: [ '400px' ],
				height: [ '180px' ],
				contentsCss : ['style.css'],
				forcePasteAsPlainText:[ true ]
		});
		</script>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center" valign="top" class="formTabela">
      <input type="hidden" name="hidCodigo" id="hidCodigo" value="<?=$codigo?>" />
      <input type="hidden" name="hidLinha" id="hidLinha" value="<?=$editar?>" />
	    <input type="hidden" name="hidCadastrarFAQ" id="hidCadastrarFAQ" value="" />
	    <input type="hidden" name="hidPerguntaFAQ" id="hidPerguntaFAQ" value="" />
      <input type="submit" value="Editar" />
      <input type="button" value="Nova Mensagem" onclick="location.href='suporte_visualizar.php?codigo=<?=$codigo?>'" /></td>
  </tr>
</table>
</form>
<?php } ?>

</div>

<div class="tituloVermelho" style="position:relative;float:right;width:320px;margin-bottom:10px;text-align:left;">Perguntas e respostas - FAQ</div>
<div id="janela_faq" style="position:relative;float:right;width:320px;height:500px;overflow:auto;border:1px solid #000;">

<?
$sql = "SELECT id_faq,pergunta,resposta FROM faq ORDER BY pergunta";
$resultado = mysql_query($sql)
or die (mysql_error());

while($linha=mysql_fetch_array($resultado)){
	echo '<div class="perguntas_faq" style="color:#024a68;cursor:pointer;position:relative;float:left;clear:both;width:300px;padding:2px 0 2px 5px;" id="'.$linha['id_faq'].'">'.$linha['pergunta'].'</div>' . "\r\n";
	echo '<div class="respostas_faq" style="color:#999;position:relative;float:left;clear:both;width:300px;padding:0 0 0 5px;display:none;" id="'.$linha['id_faq'].'">'.str_replace('</p>','',str_replace('<p>','',($linha['resposta']))).'</div>' . "\r\n";
	echo '<div class="usar_resposta" style="color:#c00;cursor:pointer;font-size:10px;text-align:right;position:relative;float:left;clear:both;width:300px;padding:0 0 5px 5px;display:none;">usar resposta</div>' . "\r\n";
}
?>

</div>


<textarea name="txtPerguntaFAQ" id="txtPerguntaFAQ" style="width:475px; height:100px" placeholder="Pergunta:"></textarea>
<!--<script type="text/javascript">
CKEDITOR.replace( 'txtPerguntaFAQ',
{
  language: ['pt-br'],
  toolbar: 
  [
    ['Source'],['Bold', 'Italic', '-', 'Link', 'Unlink'],['TextColor']
  ],
  width: [ '400px' ],
  height: [ '180px' ],
  contentsCss : ['style.css'],
  forcePasteAsPlainText:[ true ]
});
</script>-->
<br />
<input type="button" value="Enviar e Usar na FAQ" id="cad_FAQ" />

</div>


</div>
<div style="clear:both"> </div>

</div>
<?php include '../rodape.php' ?>
