<?php include 'header_restrita.php' ?>

<script type="text/javascript" src="./ckeditor/ckeditor.js"></script>

<script>
function alterarPeriodo() {
	var dataInicio = document.getElementById('DataInicio').value;
	var anoInicio = dataInicio.substr(6,4);
	var mesInicio = dataInicio.substr(3,2);
	var diaInicio = dataInicio.substr(0,2);
	var dataFim = document.getElementById('DataFim').value;
	var anoFim = dataFim.substr(6,4);
	var mesFim = dataFim.substr(3,2);
	var diaFim = dataFim.substr(0,2);

	window.location='suporte.php?dataInicio='+anoInicio+'-'+mesInicio+'-'+diaInicio+'&dataFim='+anoFim+'-'+mesFim+'-'+diaFim+'&editar=<?=$_GET["editar"]?>';
}

function validaFormSuporte(){
	var assunto = document.getElementById('txtAssunto').value;
	var nome = document.getElementById('txtNome').value;
//	var mensagem = document.getElementById('txtMensagem').value;
	var mensagem = CKEDITOR.instances['txtMensagem'].getData();
	var arquivo = document.getElementById('arqAnexo').value;

	if(assunto == '' || nome == '' || mensagem == ''){
		alert('Preencha os campos do formulário para enviar sua mensagem ao Help Desk.');
		return false;
	}
	
	if(mensagem.indexOf('anexo') > 0 && arquivo == ''){
		if(!confirm('Você digitou a palavra anexo na sua mensagem mas não anexou arquivo algum. Deseja enviar sua mensagem mesmo assim?')){
			return false;
		}
	}
	
	return true;
}

</script>

<div class="principal">

  <div class="titulo" style="margin-bottom:10px;">Help Desk</div>
  
<div style="float:left; width:450px; margin-right:30px">
 Através do Help Desk você poderá esclarecer todas as  dúvidas referentes a questões tributárias de sua empresa ou  obter suporte para o uso dos  aplicativos disponíveis no <strong>Contador Amigo</strong>. Para abrir um chamado, <strong>preencha os campos ao lado e envie</strong>. Sua resposta será enviada em até 24 horas. <br />
 <br />
 Sempre que uma necessidade sua não estiver prevista no portal, nossa equipe se mobilizará para fornecer-lhe a resposta no menor tempo possível<br />
<br />
<div class="tituloVermelho" style="margin-bottom:10px">Atendimento online e telefônico</div>
<strong>Exclusivamente para assuntos relacionados à cobrança</strong>, você poderá também entrar em contato com o<strong> Contador Amigo</strong> pelo tel: (<strong>11) 3881-4398</strong>, no horário das 9 às 18 horas, de segunda a sexta-feira.<br /><br />

<div class="tituloVermelho" style="margin-bottom:10px">Quer rever os procedimentos iniciais?</div>
Para rever a página de procedimentos inciais, exibida no seu primeiro acesso, <a href="procedimentos_iniciais.php">clique aqui</a>. A página contém orientações sobre como adquirir seu Certificado Digital e baixar a programa Sefip, para envio da Gfip.
</div>  

 <!--BALLOM erro_anexo_suporte -->
<div class="bubble box_visualizacao x_visualizacao check_visualizacao" style="padding:0; width:350px; position:absolute; top:375px; left:50%; margin-left: -330px;" id="erro_anexo_suporte">
    <div style="padding:20px; font-size:12px;">
        Muitos erros ficam mais fáceis de serem entendidos se você anexar a imagem da tela no momento em que ele ocorre. Utilize o botão <em>Print Screen</em> do seu teclado para gravar a tela, cole-a (ctrl + V) em um programa tipo <em>Paintbrush</em>, ou similar, salve-a como <strong>jpg</strong>, <strong>png </strong>ou <strong>gif </strong>e anexe aqui.<br />
Se necessário, você pode anexar também arquivos tipo <strong>docx</strong> ou <strong>pdf</strong> (até 2MB).
    </div>
<!--FIM DO BALLOOM erro_anexo_suporte -->
</div>

 <div style="float:left">
   <form method="post" action="suporte_novo_chamado_testes.php" style="display:inline" enctype="multipart/form-data" onsubmit="return validaFormSuporte();">
  <table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
  <tr>
    <td width="55" align="right" valign="top" class="formTabela">Assunto:</td>
    <td class="formTabela" width="400"><input type="text" name="txtAssunto" id="txtAssunto" style="width:400px" maxlength="200"  /></td>
  </tr>
  <tr>
    <td align="right" valign="top" class="formTabela">Nome:</td>
    <td class="formTabela"><input type="text" name="txtNome" id="txtNome" value="<?=$_SESSION["nome_assinanteSecao"]?>" style="width:400px" >
</td>
  </tr>
  <tr>
    <td align="right" valign="top" class="formTabela">Mensagem:</td>
    <td class="formTabela"><textarea name="txtMensagem" id="txtMensagem" style="width:400px; height:180px"></textarea>
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
    </td>
  </tr>
  <tr>
    <td align="right" valign="top" class="formTabela">Anexo:</td>
    <td class="formTabela"><input type="file" name="arqAnexo" id="arqAnexo" style="width:400px" >
		</td>
  </tr>
  <tr>
    <td colspan="2" align="center" valign="top" class="formTabela"><input type="submit" value="Enviar" /></td>
    </tr>
</table>
</form>
</div>


<div style="clear:both"> </div>

<?php

if(!isset($_SESSION['dataInicioSuporte'])){
	$dataInicio = $_GET["dataInicio"];
	
	if ($dataInicio == "") {
		$dataInicio = date('Y-m-d',mktime(0,0,0,date('m')-3,date('d'),date('Y')));
	}
	
}else{
	if($_GET["dataInicio"] != $_SESSION["dataInicioSuporte"] && isset($_GET["dataInicio"])){
		$dataInicio = $_GET["dataInicio"];
	}else{
		$dataInicio = $_SESSION["dataInicioSuporte"];
	}
}

if(!isset($_SESSION['dataFimSuporte'])){
	$dataFim = $_GET["dataFim"];
	
	if ($dataFim == "") {
		$dataFim = date("Y-m-d");
	}
		
}else{
	if($_GET["dataFim"] != $_SESSION["dataFimSuporte"] && isset($_GET["dataFim"])){
		$dataFim = $_GET["dataFim"];
	}else{
		$dataFim = $_SESSION["dataFimSuporte"];
	}
}

$_SESSION['dataInicioSuporte'] = $dataInicio;
$_SESSION['dataFimSuporte'] = $dataFim;


$sql = "SELECT * FROM suporte WHERE id='" . $_SESSION["id_userSecao"] . "' AND tipoMensagem='pergunta' AND data BETWEEN '$dataInicio 00:00:00' AND '$dataFim 23:59:59' ORDER BY ultimaResposta DESC";

$resultado = mysql_query($sql)
or die (mysql_error());

$total = mysql_num_rows($resultado);

if ($total != 0) {
?>
<span class="tituloVermelho">Histórico de chamadas</span><br />
<br />
<div style="clear: both"></div>
<form method="post" style="display:inline" action="Javascript:alterarPeriodo()">
<div style="float:left">Período de:  <input name="DataInicio" id="DataInicio" type="text" value="<?=date('d/m/Y',strtotime($dataInicio))?>" maxlength="10"  style="width:70px" class="campoData" /> até: <input name="DataFim" id="DataFim" type="text" value="<?=date('d/m/Y',strtotime($dataFim))?>" maxlength="10"  style="width:70px" class="campoData" /> <input name="Alterar" type="submit" value="Alterar Período" /></div>
</form>
<div style="clear: both; height: 15px;"></div>

<table border="0" cellpadding="4" cellspacing="2">
  <tr>
  	<th width="18"></th>
  	<th width="170">Início do chamado</th>
    <th width="516">Assunto</th>
    <th width="170">Última Resposta em</th>
    <th width="60">Ação</th>
  </tr>
<?php while ($linha=mysql_fetch_array($resultado)) { 
$arrNomeArquivo = explode("/",$linha["anexo"]);
?>  
<tr class="guiaTabela">
    <td class=""><?           
	echo '<div class="';
	switch($linha['status']){
		case "Respondido":
			echo 'bullets_indica_ativo';
			$alt = 'Respondido';
		break;
		case "Não Respondido":
		case "Em análise":
			echo 'bullets_indica_naoentrou';
			$alt = 'Não Respondido';
		break;
	}
	echo '" title="'.$alt.'" style="margin: 0 auto;"></div>';
?></td>
	<td class=""><?=date('d/m/Y', strtotime($linha["data"]))?>, às <?=date('H:i', strtotime($linha["data"]))?></td>
    <td class=""><?=$linha["titulo"]?></td>
    <td class="">
<?php
if ($linha["ultimaResposta"] == $linha["data"] || $linha["ultimaResposta"] == '') {
	echo 'Sem Resposta';
}
else {
	echo date('d/m/Y', strtotime($linha["ultimaResposta"])) . ", às " . date('H:i', strtotime($linha["ultimaResposta"]));
}
?></td>
    <td class=""><a href="suporte_visualizar.php?codigo=<?=$linha["idPostagem"]?>">Visualizar</a></td>
  </tr>
<?php } ?>
</table>
<?php } ?>
</div>


<?php include 'rodape.php' ?>
