<?
//$conexao = mysql_connect("177.153.16.160", "contadoramigo", "ttq231kz");
//$db = mysql_select_db("contadoramigo");
//mysql_query("SET NAMES 'utf8'");
//mysql_query('SET character_set_connection=utf8');
//mysql_query('SET character_set_client=utf8');
//mysql_query('SET character_set_results=utf8');

// inclui o arquivo de conexão com o banco.
require_once "conect.php";

session_start();
$codigo = $_GET["codigo"];

$sql = "SELECT * FROM suporte WHERE idPostagem='$codigo' LIMIT 0,1";
$resultado = mysql_query($sql)
or die (mysql_error());

$linha=mysql_fetch_array($resultado);

if (($linha["id"] != $_SESSION["id_userSecaoMultiplo"]) or ($linha["id"] == "")) {
	header('Location: suporte.php' );
}

else {
	$data = date('d/m/Y', strtotime($linha["ultimaResposta"])) .  " às " . date('H:i', strtotime($linha["ultimaResposta"]));
	$titulo = $linha["titulo"];
	$nome = $linha["nome"];
	$status = $linha["status"];
}

?>
<?php include 'header_restrita.php' ?>
<script type="text/javascript" src="./ckeditor/ckeditor.js"></script>
<?php
?>
<div class="principal">

  <div class="titulo" style="margin-bottom:20px;">Help Desk - Visualização do Chamado</div>

<span class="tituloVermelho"><?=$titulo?></span><br />
<br />

<table border="0" cellpadding="10" cellspacing="2">
<tr>
	<th width="200" style="padding:4px">Nome / Data</th>
    <th width="764" style="padding:4px">Mensagem</th>
  <?php 
$sql = "SELECT * FROM suporte WHERE idPostagem='$codigo' OR idPergunta='$codigo' ORDER BY idPostagem ASC";
$resultado = mysql_query($sql)
or die (mysql_error());

while ($linha=mysql_fetch_array($resultado)) { 


	$arrNomeArquivo = explode("/",$linha["anexo"]);
?>  
  <tr <?php  if ($nome != $linha["nome"]) { echo 'style="background-color:#DEDDD6"'; }else{ echo 'style="background-color:#FFF"'; } ?>>
	<td valign="top">
    	<strong><?=$linha["nome"]?></strong><br />
		<em><?=date('d/m/Y', strtotime($linha["data"]))?>, às<?=date('H:i', strtotime($linha["data"]))?></em>
    </td>
    <td valign="top">
			<?=urldecode($linha["mensagem"])?>
      <?
			if(count($arrNomeArquivo)>1){
				echo "
					<br>
					<div style='clear:both'><em><b>Anexo: </b><a href=\"./" . $linha["anexo"] . "\" target=\"_blank\">" . $arrNomeArquivo[count($arrNomeArquivo)-1] . "</a></em></div>
				";
			}
			?>
    </td>
    </tr>
<?php } ?>
</table>
<div style="float:right">
<!--<a href="#" onclick="if (confirm('Você realmente deseja encerrar este chamado?'))location.href='suporte_encerrar.php?codigo=<?=$codigo?>';">Encerrar Chamado</a> | -->
<a href="suporte.php">Voltar</a></div>


<div style="clear:both;height:10px;"></div>

<?php if($status != "Não Respondido") { ?>
<form method="post" action="suporte_nova_postagem.php" enctype="multipart/form-data" onsubmit="return validaFormSuporte();">

	<span class="tituloVermelho">Responder</span>
  
	<div style="clear:both;height:10px;"></div>
  
	<textarea name="txtMensagem" id="txtMensagem" style="width:400px; height:150px"></textarea>
		<script type="text/javascript">
    CKEDITOR.replace( 'txtMensagem',
    {
            language: ['pt-br'],
            toolbar: 
            [
                ['Bold', 'Italic', '-', 'Link', 'Unlink'],['TextColor']
            ],
            width: [ '400px' ],
            height: [ '180px' ],
            contentsCss : ['style.css'],
			enterMode: CKEDITOR.ENTER_BR,
            forcePasteAsPlainText:[ true ]
    });
    </script>

	<div style="clear:both;height:15px;"></div>

	Anexo: <input type="file" name="arqAnexo" id="arqAnexo" style="width:400px" >

	<div style="clear:both;height:15px;"></div>

  <input type="hidden" name="hidCodigo" id="hidCodigo" value="<?=$codigo?>" />
  <input type="hidden" name="hidTitulo" id="hidTitulo" value="<?=$titulo?>" />
  <input type="hidden" name="hidNome" id="hidNome" value="<?=$nome?>" />
  <input type="submit" value="Enviar" style="margin-left:180px;" />

</form>
    
<?php } else { ?>
Esta chamada encontra-se encerrada.
<?php } ?>
<br />
</div>

<!--video de boas vindas-->
<div id="video" class="box_visualizacao x_visualizacao" style="border-style:solid; border-width:1px; border-color:#CCCCCC; position:absolute; left:50%; margin-left:-340px; top:148px; background-color:#fff; width:680px; display:none">
    <div style="padding:20px">
        <div class="titulo" style="text-align:left; margin-bottom:10px">Orientações Gerais</div>
        <video id="video1" width="640" height="360" controls>
        <source src="videos/orientacoes_gerais.mp4" type='video/mp4'> 
        <source src="videos/orientacoes_gerais.ogv" type='video/ogg'>
        <object id="video2" width="640" height="360" type="application/x-shockwave-flash" data="video.swf"> 
        <param name="movie" value="video.swf" />
        <param name="play" value="false" />
        <param name="flashvars" value="file=videos/orientacoes_gerais.mp4" />
        </object>
        </video>
    </div>
</div>
<!--fim do video de boas vindas-->
<?php include 'rodape.php' ?>
