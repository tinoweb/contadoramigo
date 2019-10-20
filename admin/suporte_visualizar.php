<?php include '../conect.php';
include '../session.php';
include 'check_login.php';
include 'header.php';
include 'DataBaseMySQL/DadosContador.php';

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
 
//PEGA OS DADOS DO CONTADOR DE ACORDO COM O CLIENTE
$dadosContador = new DadosContador;
$dadosDoContador = $dadosContador->PegaContadorDeAcordoClienteId($id);

/* PEGANDO DADOS DO USUARIO */
$sql = "SELECT * FROM login WHERE id='" . $id . "' LIMIT 0, 1";
$rsDadosUsuario = mysql_query($sql) or die (mysql_error());
$dadosUsuario = mysql_fetch_array($rsDadosUsuario);
/* PEGANDO DADOS DO USUARIO */ 
/* PEGA OS DADOS DE COBRANCA DO USUARIO */
$sql2 = "SELECT * FROM dados_cobranca WHERE id='".$id. "'";
$rsDadosCobranca = mysql_query($sql2) or die (mysql_error());
$dadosCobranca = mysql_fetch_array($rsDadosCobranca);
/* PEGA OS DADOS DE COBRANCA DO USUARIO */
/* PEGA OS DADOS DE COBRANCA DO USUARIO */
$sql3 = "SELECT cnpj, cidade, estado, ativa, inscrita_como FROM login l, dados_da_empresa e
WHERE l.idUsuarioPai = ".$id."
AND e.id = l.id
AND e.ativa = 1;";
$rsDadosEmpresa = mysql_query($sql3) or die (mysql_error());
$empresas = '';

while($dadosEmpresa = mysql_fetch_array($rsDadosEmpresa)){
	
	if($dadosEmpresa['inscrita_como'] == 'Empresa Individual de Responsabilidade Limitada (EIRELI)'){
		$inscrita_como = 'EIRELI';
	} else {
		$inscrita_como = $dadosEmpresa['inscrita_como'];
	}
	
	$empresas .= " <br/> ".$dadosEmpresa['cidade']." - ".$dadosEmpresa['estado']." - ".$inscrita_como." - ".str_replace(array('.','/','-'),array('','',''),$dadosEmpresa['cnpj']);
	
}

/* PEGA OS DADOS DE COBRANCA DO USUARIO */

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

<span class="tituloVermelho"><?=$titulo?></span><br />
<br />

<?php 
	
	// PEGA O PLANO DO USUÁRIO
	$plano = ($dadosCobranca['tipo_plano'] == 'P' ? 'Premium' : 'Standard'); 
	
	if($dadosCobranca['plano'] == 'mensalidade') {
		$assinatura = 'Mensal';
	} elseif($dadosCobranca['plano'] == 'trimestral') {
		$assinatura = 'Trimestral';
	} elseif($dadosCobranca['plano'] == 'semestral') {
		$assinatura = 'Semestral';
	} elseif($dadosCobranca['plano'] == 'anual') {
		$assinatura = 'Anual';
	} else {
		$assinatura = 'Mensal';
	}
	
	$planoAssinatura = $plano.' - '.$assinatura;
?>	


<div style="clear: both;">
  <div style="position:relative;float:left;margin-bottom:10px;">
    Usuário <?php echo $dadosUsuario['status']." - ".$planoAssinatura." - ".$dadosDoContador['nome'].$empresas." ";?> 
  </div>
  
  <div style="position:relative;float:right;margin-bottom:10px;">
    <a href="#" id="btConversaAnterior">Carregar conversa anterior</a>
  </div>
</div>

<div id="conteudo_pagina" style="position:relative;float:left;">

<table border="0" cellpadding="10" cellspacing="2">
<thead>
<tr>
	<th width="200" style="padding:4px">Nome / Data</th>
    <th width="700" style="padding:4px">Mensagem</th>
</tr>
</thead>
<tbody>
  <?php 
$sql = "SELECT * FROM suporte WHERE idPostagem='$codigo' OR idPergunta='$codigo' ORDER BY idPostagem ASC";
$resultado = mysql_query($sql)
or die (mysql_error());

while ($linha=mysql_fetch_array($resultado)) { 

	if(is_null($linha['idPergunta'])){
		$idUsuario = $linha['id'];
	}

	$arrNomeArquivo = explode("/",$linha["anexo"]);

?>  
  <tr>
	<td valign="top">
    	<strong><?php
    	if ($linha["nome"] == '') { 

    		$consulta_nome_execao = mysql_query("SELECT * FROM login WHERE id = '".$id."' AND id = idUsuarioPai ");
    		$objeto_nome_execao=mysql_fetch_array($consulta_nome_execao);

		echo '<a href="cliente_administrar.php?id=' . $id . '" target="_blank">'.$objeto_nome_execao['assinante'].'</a>';
		}
        else if ($nome == $linha["nome"]) { 
		echo '<a href="cliente_administrar.php?id=' . $id . '" target="_blank">' . $id . " - " . $linha["nome"] . '</a>';
		} else {
		echo $linha["nome"];
		} ?></strong><br />
		<em><?=date('d/m/Y', strtotime($linha["data"]))?>, às <?=date('H:i', strtotime($linha["data"]))?></em>
                <br />
<br />
<?php  if ($nome != $linha["nome"]) { ?>
  <a href="#" onClick="if (confirm('Você tem certeza que deseja excluir esta mensagem?'))location.href='suporte_excluir_postagem.php?codigo=<?=$codigo?>&linha=<?=$linha["idPostagem"]?>';"><i class="fa fa-trash-o iconesAzul iconesGrd"></i></a>
	<a href="suporte_visualizar.php?codigo=<?=$codigo?>&editar=<?=$linha["idPostagem"]?>"><i class="fa fa-pencil-square-o iconesAzul iconesGrd"></i></a>
	<?php } ?>
    </td>
    <td valign="top">
			<?=urldecode($linha["mensagem"])?>
      <?
			if(count($arrNomeArquivo)>1){
				echo "<div style='clear:both'><em><b>Anexo: </b><a href=\"../" . $linha["anexo"] . "\" target=\"_blank\">" . $arrNomeArquivo[count($arrNomeArquivo)-1] . "</a></em></div>";
			}
			?>
    </td>
    </tr>
<?php } ?>
</tbody>

</table>


<br />


<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>

<script>
$(document).ready(function(e) {
	
  $('#conteudo_pagina tbody tr:even').css('background-color','#DEDDD6');
  $('#conteudo_pagina tbody tr:odd').css('background-color','#FFFFFF');
	  
  var codigo = "<?=$codigo?>";

  $('.perguntas_faq').hover(function(e){
		$(this).css('background-color','#eee');
	},function(e){
		$(this).css('background-color','transparent');
	});

/*
  $('.usar_resposta').hover(function(e){
		$(this).css('background-color','#eee');
	},function(e){
		$(this).css('background-color','transparent');
	});

  $('.usar_resposta').bind('click',function(e){
		var selecionado = $('.usar_resposta').index(this);
	
		e.preventDefault();
		var texto = CKEDITOR.instances['txtMensagem'].getData();
		CKEDITOR.instances['txtMensagem'].setData(texto + $('.respostas_faq').eq(selecionado).html());
	});
*/
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
			var texto = CKEDITOR.instances['txtMensagem'].getData();
			texto = texto.replace("<br />\n&nbsp;","");
			texto = texto.replace("<p>&nbsp;</p>","");
			console.log(texto);
			CKEDITOR.instances['txtMensagem'].setData(texto + $('.respostas_faq').eq(selecionado).html());
			$('.perguntas_faq').eq(selecionado).css('font-weight','normal');
			$('.respostas_faq').eq(selecionado).slideUp(200);
			$(this).css('display','none');
			
		});
	});

	$('#cad_FAQ').bind('click',function(ev){
		ev.preventDefault();
		
		if($('#caixa_pergunta').css('display') == 'none'){

			$('#caixa_pergunta').css('display','block');
			
			$('#bt_submit').css('display','none');
			
			return false;

		}else{

			if($('#txtPerguntaFAQ').val() == ''){
				alert('Preencha a pergunta para ser inserida nas opções da FAQ');
				$('#txtPerguntaFAQ').focus();
				return false;
			}
			if(CKEDITOR.instances['txtMensagem'].getData() == ''){
				alert('Preencha a resposta ao usuário ou selecione uma resposta padrão da tabela ao lado');
				CKEDITOR.instances['txtMensagem'].focus();
				return false;
			}
			
		}

		$('#hidCadastrarFAQ').val('1');
		$('#hidPerguntaFAQ').val($('#txtPerguntaFAQ').val());

		$('#form_postagem').submit();
			
	});


	$('#btConversaAnterior').bind('click',function(ev){
		ev.preventDefault();

		$.ajax({
			url:"suporte_conversa_anterior.php"
			, async:true
			, dataType:"json"
			, data: "idUsuario=<?=$idUsuario?>&idPergunta=" + codigo + "&nomeUser=<?=$nome?>"
			, type:"POST"
			, cache:false
			, success: function(result){
				//alert(result.data);
				console.log(result);
				
				if(result.data != ''){
					$('#conteudo_pagina table tbody:first').prepend(result.data);
					$('#conteudo_pagina tbody tr:even').css('background-color','#DEDDD6');
					$('#conteudo_pagina tbody tr:odd').css('background-color','#FFFFFF');
					codigo=result.codigo;
					console.log(codigo);
				}else{
					alert('Não há mais conversas...');
					$('#btConversaAnterior').css('display','none');
				}
			}
		});
		
	});
});
</script>

<div style="clear:both;height:20px;"></div>

<div id="tabela_form" style="position:relative;float:left;">

<?php
$editar = $_GET["editar"];

if($editar == "") { ?>
<form method="post" id="form_postagem" name="form_nova_postagem" action="suporte_nova_postagem.php" enctype="multipart/form-data">
  <input type="hidden" name="txtNome" id="txtNome" value="Suporte">
  <input type="hidden" name="hidCodigo" id="hidCodigo" value="<?=$codigo?>" />
  <input type="hidden" name="hidNomeDestinatario" id="hidNomeDestinatario" value="<?=$nome?>" />
  <input type="hidden" name="hidTitulo" id="hidTitulo" value="<?=$titulo?>" />
  <input type="hidden" name="hidIdDestinatario" id="hidIdDestinatario" value="<?=$id?>" />
  <input type="hidden" name="hidCadastrarFAQ" id="hidCadastrarFAQ" value="" />
  <input type="hidden" name="hidPerguntaFAQ" id="hidPerguntaFAQ" value="" />

	<div id="caixa_pergunta" style="display:none">
    <div class="tituloVermelho" style="position:relative;float:left;margin-bottom:10px;text-align:left;">Pergunta</div>
    <div style="clear:both;margin-bottom:10px;">
      <textarea name="txtPerguntaFAQ" id="txtPerguntaFAQ" style="width:400px; height:50px"></textarea>
    </div>
	</div>

  <div class="tituloVermelho" style="position:relative;float:left;margin-bottom:10px;text-align:left;">Mensagem</div>
  <div style="clear:both;margin-bottom:10px;">
  	<?php 

  		$consulta_texto_rascunho = mysql_query("SELECT * FROM suporte_rascunho WHERE codigo = '".$_GET['codigo']."' ORDER BY id DESC LIMIT 1 ");
  		$objeto_texto_racunho=mysql_fetch_array($consulta_texto_rascunho);
  		if( isset($objeto_texto_racunho['texto']) && $objeto_texto_racunho['texto'] != '' ){
  			$texto_rascunho = $objeto_texto_racunho['texto'];
  		}
  		else{
  			$consulta = mysql_query("SELECT * FROM login WHERE id = '".$id."' AND id = idUsuarioPai ");
  			$objeto=mysql_fetch_array($consulta);
  			$nome_aux = explode(' ', $objeto['assinante']);
  			$texto_rascunho = 'Olá, '.ucfirst(strtolower($nome_aux[0])).'!<br><br><br>';
  		}

  	?>
    <textarea name="txtMensagem" id="txtMensagem"><?php echo $texto_rascunho; ?></textarea>
    <script type="text/javascript">
    CKEDITOR.replace( 'txtMensagem',
    {
        language: ['pt-br'],
				disableNativeSpellChecker:false,
				scayt_autoStartup:false,
				scayt_sLang: 'pt_BR',
        toolbar: 
        [
          ['Source'],['Bold', 'Italic', '-', 'Link', 'Unlink','-','SpellChecker', 'Scayt'],['TextColor']
        ],
        width: [ '400px' ],
        height: [ '255px' ],
        contentsCss : ['style.css'],
        forcePasteAsPlainText:[ false ]
    });
    </script>
	</div>
  
  <div style="position:relative;float:left;margin-bottom:10px;text-align:left;clear:both;margin-bottom:10px;">
	  Anexo: <input type="file" name="arqAnexo" id="arqAnexo" >
	</div>
  <div style="clear:both"></div>  
  <div class="tituloVermelho" style="position:relative;float:left;margin-bottom:10px;text-align:left;">Status</div>
  <div style="clear:both;margin-bottom:10px;">
    <input type="radio" name="radStatus" id="radStatus" value="Em análise" /> <span style="margin-right:10px">Em análise</span>
    <input type="radio" name="radStatus" id="radStatus" value="Respondido" checked="checked" /> <span style="margin-right:10px">Respondido</span>
	</div>
  
  <div style="margin:0 auto 10px auto;clear:both;text-align:center;">
    <input type="submit" value="Enviar" id="bt_submit" />
    <input type="button" value="Enviar e Usar na FAQ" id="cad_FAQ" />
    <input type="button" name="" value="Rascunho" class="salvar_rascunho">
  </div>
  
</form>
<?php } 
else { 
$sql = "SELECT * FROM suporte WHERE idPostagem='$editar'";
$resultado = mysql_query($sql)
or die (mysql_error());

$linha=mysql_fetch_array($resultado);

?>
<form method="post" id="form_postagem" name="form_editar_postagem" action="suporte_editar_postagem.php" enctype="multipart/form-data">
  <input type="hidden" name="txtNome" id="txtNome" value="<?=$linha["nome"]?>">
  <input type="hidden" name="hidCodigo" id="hidCodigo" value="<?=$codigo?>" />
  <input type="hidden" name="hidLinha" id="hidLinha" value="<?=$editar?>" />
  <input type="hidden" name="hidCadastrarFAQ" id="hidCadastrarFAQ" value="" />
  <input type="hidden" name="hidPerguntaFAQ" id="hidPerguntaFAQ" value="" />

	<div id="caixa_pergunta" style="display:none">
    <div class="tituloVermelho" style="position:relative;float:left;margin-bottom:10px;text-align:left;">Pergunta</div>
    <div style="clear:both;margin-bottom:10px;">
      <textarea name="txtPerguntaFAQ" id="txtPerguntaFAQ" style="width:400px; height:50px"></textarea>
    </div>
	</div>

  <div class="tituloVermelho" style="position:relative;float:left;margin-bottom:10px;text-align:left;">Mensagem</div>
  <div style="clear:both;margin-bottom:10px;">
    <textarea name="txtMensagem" id="txtMensagem"><?=urldecode($linha["mensagem"])?></textarea>
    <script type="text/javascript">
    CKEDITOR.replace( 'txtMensagem',
    {
        language: ['pt-br'],
				disableNativeSpellChecker:false,
				scayt_autoStartup:false,
				scayt_sLang: 'pt_BR',
        toolbar: 
        [
          ['Source'],['Bold', 'Italic', '-', 'Link', 'Unlink','-','SpellChecker', 'Scayt'],['TextColor']
        ],
        width: [ '400px' ],
        height: [ '255px' ],
        contentsCss : ['style.css'],
        forcePasteAsPlainText:[ false ]
    });
    </script>
	</div>
  
  <div style="position:relative;float:left;margin-bottom:10px;text-align:left;clear:both;margin-bottom:10px;">
	  Anexo: <input type="file" name="arqAnexo" id="arqAnexo" >
	</div>
  <div style="clear:both"></div>  
  
  <div style="margin:0 auto 10px auto;clear:both;text-align:center;">
      <input type="submit" value="Editar" id="bt_submit" />
      <input type="button" value="Nova Mensagem" onclick="location.href='suporte_visualizar.php?codigo=<?=$codigo?>'" />
      <input type="button" value="Enviar e Usar na FAQ" id="cad_FAQ" />
  </div>

      
</form>
<?php } ?>

</div>

<div class="tituloVermelho" style="position:relative;float:right;width:395px;margin-bottom:10px;text-align:left;">Perguntas e respostas - FAQ</div>
<div id="janela_faq" style="position:relative;float:right;width:395px;height:443px;overflow:auto;background-color:#FFF;border:1px solid #000;">

<?
$sql = "SELECT id_faq,pergunta,resposta FROM faq ORDER BY pergunta";
$resultado = mysql_query($sql)
or die (mysql_error());

while($linha=mysql_fetch_array($resultado)){
//	echo '<div class="perguntas_faq" style="color:#024a68;cursor:pointer;position:relative;float:left;clear:both;width:370px;padding:2px 0 2px 5px;" id="'.$linha['id_faq'].'">'.str_replace('<p>','',str_replace('</p>','',$linha['pergunta'])).'</div>' . "\r\n";
//	echo '<div class="respostas_faq" style="color:#666;position:relative;float:left;clear:both;width:370px;padding:0 0 0 5px;display:none;" id="'.$linha['id_faq'].'">'.str_replace('</p>','',str_replace('<p>','',($linha['resposta']))).'</div>' . "\r\n";
//	echo '<div class="usar_resposta" style="color:#c00;cursor:pointer;font-size:10px;text-align:right;position:relative;float:left;clear:both;width:370px;padding:0 0 5px 5px;display:none;">usar resposta</div>' . "\r\n";
	echo '<div class="perguntas_faq" style="color:#024a68;cursor:pointer;position:relative;float:left;clear:both;width:370px;padding:2px 0 2px 5px;" id="'.$linha['id_faq'].'">'.str_replace('<p>','',str_replace('</p>','',$linha['pergunta'])).'</div>' . "\r\n";
	echo '<div class="respostas_faq" style="color:#666;position:relative;float:left;clear:both;width:370px;padding:0 0 0 5px;display:none;" id="url'.$linha['id_faq'].'">'.(urldecode($linha['resposta'])).'</div>' . "\r\n";
	echo '<div class="usar_resposta" style="color:#c00;cursor:pointer;font-size:10px;text-align:right;position:relative;float:left;clear:both;width:370px;padding:0 0 5px 5px;display:none;">usar resposta</div>' . "\r\n";
}
?>

</div>


<!--<textarea name="txtPerguntaFAQ" id="txtPerguntaFAQ" style="width:475px; height:100px" placeholder="Pergunta:"></textarea>
<script type="text/javascript">
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


</div>


<script>

	$(".salvar_rascunho").click(function() {
		
		var texto = encodeURI(CKEDITOR.instances['txtMensagem'].getData());	
		console.log("acao=rascunho_suporte&texto="+texto+"&codigo=<?=$_GET['codigo'];?>");
		for (var i = 0; i < texto.length; i++) {
			var texto = texto.replace("&", "___");
		};
			

		$.ajax({
			url:"ajax.php"
			, async:true
			, data: "acao=rascunho_suporte&texto="+texto+"&codigo=<?=$_GET['codigo'];?>"
			, type:"GET"
			, cache:false
			, success: function(result){
				// console.log(result);
			}
		});

	});

</script>

</div>
<div style="clear:both"> </div>

</div>
<?php include '../rodape.php' ?>
