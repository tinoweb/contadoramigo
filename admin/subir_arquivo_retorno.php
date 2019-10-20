<?php 
include '../conect.php';

include '../session.php';

include 'check_login.php';

include 'header.php';

?>

<?

$titulo_vermelho = "Arquivos de Retorno";

if(isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'subir'){

		$checaArquivo = mysql_fetch_array(mysql_query("select count(*) total FROM arquivos_retorno_banco WHERE nome = '" . $_FILES['arquivo']['name'] . "'"));

		if($checaArquivo['total'] <= 0){
	
			// ATRIBUINDO O NOME DO ARQUIVO PROCESSADO À VARIÁVEL
			$temp_arquivo = $_FILES['arquivo']['tmp_name'];
			$nome_arquivo = ('arquivos_retorno/'.$_FILES['arquivo']['name']);
			$nome_arquivo_proc = ('arquivos_retorno/proc_'.$_FILES['arquivo']['name']);
	
			// SUBINDO O ARQUIVO DE RETORNO
			if(!file_exists($nome_arquivo)) move_uploaded_file($temp_arquivo,$nome_arquivo);
			
			// GRAVANDO DADOS DO ARQUIVO NA TABELA
			mysql_query("INSERT INTO arquivos_retorno_banco SET nome = '" . $_FILES['arquivo']['name'] . "', data_carga = '" . date('Y-m-d H:i:s') . "', status = 'carregado'");
	
			//header ('location: subir_arquivo_retorno.php');

		}else{

			echo "<script>alert('Já existe um arquivo com este nome, carregado.');window.location='subir_arquivo_retorno.php';</script>";
			
		}
		
}


if($_REQUEST['reset'] != ''){
	mysql_query("UPDATE arquivos_retorno_banco SET data_processamento = null, status = 'carregado' WHERE id = " . $_GET['reset']);
		header ('location: subir_arquivo_retorno.php');
}


if($_REQUEST['excluir'] != ''){
	$linhaNome = mysql_fetch_array(mysql_query("SELECT nome FROM arquivos_retorno_banco WHERE id = " . $_GET['excluir']));
	if(file_exists($linhaNome['nome'])) unlink($linhaNome['nome']);
	mysql_query("DELETE FROM arquivos_retorno_banco WHERE id = " . $_GET['excluir']);
		header ('location: subir_arquivo_retorno.php');
}
?>


<script type="text/javascript">

$(document).ready(function(e) {

	esquerda = ($('#assinante').offset().left);
	topo = ($('#assinante').offset().top);
	altura = ($('#assinante').innerHeight());
			
	$('#assinante').keyup(function(){
		if($(this).val() != ''){
			$.ajax({
				url:'preenchecampobusca.php',
				type: 'POST',
				data: 'valor='+$('#assinante').val(),
				async: true,
				cache:false,
				success: function(result){
					if(result != ''){
						$('#preenchimentoBusca').css({
							'height':'auto'
							,'display': 'block'
							, 'top': topo + altura + 3
							, 'left': esquerda
						}).fadeIn('fast');
						$('#preenchimentoBusca').html(result);
					} else {
						$('#preenchimentoBusca').html('').css('display','none');
					}
				}						
			});
			
			$('.selResultBusca').live('click',function(){
				$('#assinante').val($(this).html());
				$('#hddIdUser').val($(this).attr('iduser'));
				$('#preenchimentoBusca').fadeOut('fast');
			});
		}else{
			$('#preenchimentoBusca').fadeOut('fast');
		}
	});
});

</script>
<?
$dataInicio = $_GET["dataInicio"];
if ($dataInicio == "") {
	$dataInicio = date('Y-m-d',mktime(0,0,0,date('m'),date('d')-7,date('Y')));
}

$dataFim = $_GET["dataFim"];
if ($dataFim == "") {
	$dataFim = date("Y-m-d");
}

$status = $_GET["status"];
if ($status == "") {
	$status = "todos";
}

function selected( $value, $prev ){
   return $value==$prev ? ' selected="selected"' : ''; 
};
?>

<script>

function alterarPeriodo() {
	dataInicio = document.getElementById('DataInicio').value;
	anoInicio = dataInicio.substr(6,4);
	mesInicio = dataInicio.substr(3,2);
	diaInicio = dataInicio.substr(0,2);
	dataFim = document.getElementById('DataFim').value;
	anoFim = dataFim.substr(6,4);
	mesFim = dataFim.substr(3,2);
	diaFim = dataFim.substr(0,2);
	status = document.getElementById('selStatus').value;

	window.location='subir_arquivo_retorno.php?dataInicio='+anoInicio+'-'+mesInicio+'-'+diaInicio+'&dataFim='+anoFim+'-'+mesFim+'-'+diaFim+'&status='+status;
}

function enviaArquivo(){
	if(document.form.arquivo.value==''){
		alert('Selecione um arquivo');
		document.form.arquivo.focus();
		return false;
	}
}

</script>

<div class="principal">

	<div class="titulo" style="margin-bottom:10px;">Arquivo Retorno</div>

	<form name="form" action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
    	<input type="hidden" name="acao" value="subir" />
        <input type="file" name="arquivo" />
        <input type="submit" value="Carregar" onClick="return enviaArquivo();" />
    </form>


<?
// A CONSULTA
$sql_arquivos = 'SELECT id,nome,data_processamento,data_carga,status FROM arquivos_retorno_banco WHERE 1 = 1';

// OS FILTROS
if($dataInicio != "" && $dataFim != "") {
	$sql_arquivos .= " AND data_carga BETWEEN '$dataInicio 00:00:00' AND '$dataFim 23:59:59'";
	$titulo_vermelho = "Arquivos Retorno  - " . ($dataInicio == $dataFim ? "dia " . date('d/m/Y',strtotime($dataInicio)) : "período entre " . date('d/m/Y',strtotime($dataInicio)) . " até " . date('d/m/Y',strtotime($dataFim)) );

}

if($status != "todos") {
	$sql_arquivos .= " AND status='$status'";
}

$sql_arquivos .= ' ORDER BY data_carga DESC';

// lista os arquivos se houverem
$rsArquivos = mysql_query($sql_arquivos);

?>

<div style=" clear:both; height: 15px;"> </div>
<div style="float:left">
<form method="post" action="Javascript:alterarPeriodo()">
Exibir 
<select name="selStatus" id="selStatus" style="width:150px;font-size: 12px;"> 
<option value="todos" <?php echo selected( 'todos', $status); ?>>Todos</option>
<option value="carregado" <?php echo selected( 'carregado', $status); ?>>Carregados</option>
<option value="processado" <?php echo selected( 'processado', $status); ?>>Processados</option>
<option value="processado com erro" <?php echo selected( 'processado com erro', $status); ?>>Processados com erro</option>
</select>
&nbsp;no período entre  
  <input name="DataInicio" id="DataInicio" type="text" value="<?=date('d/m/Y',strtotime($dataInicio))?>" maxlength="10"  style="width:85px;font-size: 12px;" class="campoData"/> 
  até 
  <input name="DataFim" id="DataFim" type="text" value="<?=date('d/m/Y',strtotime($dataFim))?>" maxlength="10"  style="width:85px;font-size: 12px;" class="campoData" /> <input name="Alterar" type="submit" value="Filtrar" />
</form>
</div>
<div style=" clear:both; height: 10px;"> </div>

<div class="tituloVermelho">
	<?=$titulo_vermelho?>
</div>

<table border="0" cellspacing="2" cellpadding="4" style="font-size:12px; width: 80%;">
    <tr>
        <th align="center" rowspan="2">Arquivo</th>
        <th align="center" rowspan="2">Status</th>
        <th align="center" colspan="2">Data</th>
        <th align="center" rowspan="2">Ação</th>
	</tr> 
    <tr>
        <th align="center">Carga</th>
        <th align="center">Processamento</th>
    </tr>
<?


if (mysql_num_rows($rsArquivos) > 0) {
	while($arquivos=mysql_fetch_object($rsArquivos)){
		if( filesize("./arquivos_retorno/".$arquivos->nome) > 1000 )
	   	print " 	<tr class='guiaTabela' style='background-color:#FFF' valign='top'>
	   				<td><a onClick=\"abreJanela('https://www.contadoramigo.com.br/admin/arquivo_retorno_visulizar".( ($arquivos->id > 1024 ) ? '' : '1' ).".php?id=".$arquivos->id."','_blank','width=700, height=600, top=150, left=150, scrollbars=yes, resizable=yes');\" href='#'>".$arquivos->nome."</a>&nbsp&nbsp&nbsp<a href='./arquivos_retorno/".$arquivos->nome."' target='_blank'><i class='fa fa-download' aria-hidden='true'></i></a></td>
					<td style=\"text-transform:capitalize\">".$arquivos->status."</td>
					<td>" . date('d/m/Y H:i:s',strtotime($arquivos->data_carga)) . "</td>
					<td>" . ($arquivos->data_processamento != '' ? date('d/m/Y H:i:s',strtotime($arquivos->data_processamento)) : '') . "</td>
					<td align=\"center\"><a href=\"subir_arquivo_retorno.php?reset=".$arquivos->id."\">Reset</a>&nbsp;&nbsp;<a href=\"javascript:if(confirm('Deseja realmente excluir este arquivo?')){location.href='subir_arquivo_retorno.php?excluir=".$arquivos->id."';}\">Excluir</a></td>
				</tr>";
//					<td>" . ($arquivos->status == "processado" ? '<a href="subir_arquivo_retorno.php?acao=reprocessar&arq=' . $arquivos->nome . '">reprocessar</a>' : '<a href="subir_arquivo_retorno.php?acao=processar&arq=' . $arquivos->nome . '">processar</a>' ) . "</td>

	}
}else{
	print "<tr class='guiaTabela' style='background-color:#FFF' valign='top'><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
}
?>
</table>
</div>