<?php 

include '../conect.php';

include '../session.php';

include 'check_login.php';
	
include 'header.php';	
	
if( isset($_GET['gerar_remessa']) ){

	include '../arquivo_remessa/remessa/bean.php';

	//Cria um objeto para cada segmento de arquivo
	$header_arquivo 	=   new Registro_header_arquivo();
	$header_lote 		=  	new Registro_header_lote();//Retirando teste, enviamos para ambiente de produção
	$segmento_p 		=  	new Registro_segmento_p();
	$segmento_q 		=  	new Registro_segmento_q();
	$segmento_r 		=  	new Registro_segmento_r();
	$trailer_lote 		=  	new Registro_trailer_lote();
	$trailer_arquivo 	=   new Registro_trailer_arquivo();
	
	//Header do arquivo		
	$string = 	$header_arquivo->getArquivo();
	$string .= 	$header_lote->getArquivo();

	//Consulta para geração do arquivo remessa com todos os boletos no intervalo desejado
	//$consulta = mysql_query("SELECT * FROM boleto WHERE remessa_gerada = '0' AND vencto_get >= '".date("Y-m-d")."'  ");
	$consulta = mysql_query("SELECT * FROM boleto WHERE remessa_gerada = '0'");
	
	//Contador de segmentos, p começa em 1 e q em dois e incrementa de 2 em dois cada um deles
	$cont_segmento_p = 1;
	$cont_segmento_q = 2;
	$cont_segmento_r = 3;
	//Gerar um segmento p e um segmento q para cada boleto a ser registrado
	$linhas = 0;
	while( $boleto = mysql_fetch_array($consulta) ){

		//Define os dados do segmento P para este boleto, caso comentado, puxa dados default
		$segmento_p->setDados($boleto,$cont_segmento_p);
		$string .= 	$segmento_p->getArquivo();

		//Define os dados do segmento Q para este boleto, caso comentado, puxa dados default
		$segmento_q->setDados($boleto,$cont_segmento_q);	
		$string .= 	$segmento_q->getArquivo();

		$segmento_r->setDados($boleto,$cont_segmento_r);	
		$string .= 	$segmento_r->getArquivo();
		
		//Incrementa o contador de segmentos
		$cont_segmento_p += 3;
		$cont_segmento_q += 3;
		$cont_segmento_r += 3;
		$linhas = $linhas + 1;

		
	}

	mysql_query("UPDATE boleto set remessa_gerada = 1 , gerassao_remessa = '".date("Y-m-d H:m:s")."' WHERE remessa_gerada = '0' ");
	
	//Definir as quantidades para o trailer de arquivo
	$trailer_lote->quantidade_de_registros_do_lote = $trailer_lote->zeros(6-strlen(strval($linhas*3+2))).strval($linhas*3+2);//Total de linhas do lote (inclui Header de lote, Registros e Trailer de lote).

	//Definir as quantidades para o trailer de lote
	$trailer_arquivo->quantidade_de_lotes_do_arquivo = $trailer_arquivo->zeros(6-strlen(strval(count(1)))).strval(count(1));//Informar quantos lotes o arquivo possui.
	$trailer_arquivo->quantidade_de_registros_do_arquivo = $trailer_arquivo->zeros(6-strlen(strval($linhas*3+4))).strval($linhas*3+4);//Quantidade igual ao número total de registros (linhas) do arquivo.

	$string .= 	$trailer_lote->getArquivo();
	$string .= 	$trailer_arquivo->getArquivo();

	if( $linhas > 0){

		$consulta = mysql_query("SELECT max(id) as novo_id FROM arquivo_remessa");
		$objeto=mysql_fetch_array($consulta);
		if($objeto['novo_id'] == '')
			$novo_id = 1;
		else
			$novo_id = $objeto['novo_id'];

		//Gera um arquivo com nome teste.rem, caso não informe ao construtor o nome do arquivo, e escreve o conteudo gerado pelos header, segmentos e trailers de arquivos
		$file = new File();

		$file_name = '../arquivo_remessa/files/'.$novo_id.'-'.date("Y-m-d").'.rem';
		$file->write(strtoupper($string),$file_name);

		

		$consulta = mysql_query("INSERT INTO `arquivo_remessa`(`id`, `nome`, `status`, `gerado`, `baixado`) VALUES ('','".$novo_id.'-'.date("Y-m-d").".rem','gerado','".date("Y-m-d H:m:s")."','0')");
	}

}


?>

<?

$titulo_vermelho = "Arquivos Remessa";

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

	window.location='arquivo_remessa.php?dataInicio='+anoInicio+'-'+mesInicio+'-'+diaInicio+'&dataFim='+anoFim+'-'+mesFim+'-'+diaFim+'&status='+status;
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

	<div class="titulo" style="margin-bottom:10px;">Arquivo Remessa</div>
	<button type="button" onclick="location.href='arquivo_remessa.php?gerar_remessa';">Gerar Remessa</button>
	<?php

		$boletos_a_gerar = mysql_query("SELECT * FROM historico_cobranca WHERE data_pagamento = '".date("Y-m-d")."' ");
		$quantidade_boletos_a_gerar = mysql_num_rows($boletos_a_gerar); 
		if($quantidade_boletos_a_gerar < 9)
			$quantidade_boletos_a_gerar = '0'.$quantidade_boletos_a_gerar;
	?>

	<!-- <p>Existem <strong><?php echo $quantidade_boletos_a_gerar; ?></strong> boletos a vencer hoje</p> -->

	<?php 

		$boletos_em_aberto = mysql_query("SELECT * FROM boleto WHERE remessa_gerada = '0' ");
		$quantidade_remessa = mysql_num_rows($boletos_em_aberto); 
		if($quantidade_remessa <= 9)
			$quantidade_remessa = '0'.$quantidade_remessa;

	?>
	<p>Existem <strong><?php echo $quantidade_remessa; ?></strong> boletos pendentes para gerar remessa</p>
<?
// A CONSULTA
$sql_arquivos = 'SELECT * FROM arquivo_remessa WHERE 1 = 1';

// OS FILTROS
if($dataInicio != "" && $dataFim != "") {
	$sql_arquivos .= " AND gerado BETWEEN '$dataInicio 00:00:00' AND '$dataFim 23:59:59'";
	$titulo_vermelho = "Arquivos Retorno  - " . ($dataInicio == $dataFim ? "dia " . date('d/m/Y',strtotime($dataInicio)) : "período entre " . date('d/m/Y',strtotime($dataInicio)) . " até " . date('d/m/Y',strtotime($dataFim)) );

}

if($status != "todos") {
	$sql_arquivos .= " AND baixado='$status'";
}

$sql_arquivos .= ' ORDER BY id DESC';

// lista os arquivos se houverem

$rsArquivos = mysql_query($sql_arquivos);

?>

<div style=" clear:both; height: 15px;"> </div>
<div style="float:left">
<form method="post" action="Javascript:alterarPeriodo()">
Exibir 
<select name="selStatus" id="selStatus" style="width:150px;font-size: 12px;"> 
<option value="todos" <?php echo selected( 'todos', $status); ?>>Todos</option>
<option value="1" <?php echo selected( '1', $status); ?>>Baixados</option>
<option value="0" <?php echo selected( '0', $status); ?>>Pendentes</option>
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

<table border="0" cellspacing="2" cellpadding="4" style="font-size:12px;">
    <tr>
        <th width="150" align="center">Arquivo</th>
        <th width="100" align="center">Status</th>
        <th width="150" align="center">Data</th>
	</tr> 
<?

function formataDataGeracao($string){

	$aux = explode(' ', $string);
	$data_aux = explode('-', $aux[0]);
	$data = $data_aux[2].'/'.$data_aux[1].'/'.$data_aux[0];

	$hora = $aux[1];

	return $data.' às '.$hora;

}

if (mysql_num_rows($rsArquivos) > 0) {

	while($arquivos=mysql_fetch_array($rsArquivos)){
		$nome = $arquivos['nome'];
		if( $arquivos['baixado'] == 1 )
			$baixado = "baixado";
		else
			$baixado = "pendente";
		$data = $arquivos['gerado'];

	    print " <tr class='guiaTabela' style='background-color:#FFF' valign='top'>

	   				<td class='alterar_status'><a onClick=\"abreJanela('arquivo_remessa_visualizar".( ($arquivos['id'] > 205 ) ? '' : '1' ).".php?id=".$arquivos['id']."','_blank','width=700, height=600, top=150, left=150, scrollbars=yes, resizable=yes');\" href='#'>".$arquivos['nome']."</a>&nbsp&nbsp&nbsp<a href='../arquivo_remessa/download_arquivo_remessa.php?id=".$arquivos['id']."' ><i class='fa fa-download' aria-hidden='true'></i></a></td>
					<td align='center' class='status' style=\"text-transform:capitalize\">".$baixado."</td>
					<td>".formataDataGeracao($data)."</td>
				</tr>";

	}
}else{
	print "<tr class='guiaTabela' style='background-color:#FFF' valign='top'><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
}
?>
</table>

<script>

	$(".alterar_status").click(function() {
		$(this).parent().find(".status").empty();
		$(this).parent().find(".status").append('Baixado');
	});

</script>
</div>