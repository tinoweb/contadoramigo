<?php 
	include 'header.php';
	
	//include '../conect.php';
	include '../session.php';
	include 'check_login.php'; 

function selected( $value, $prev ){
   return $value==$prev ? ' selected="selected"' : ''; 
};


?>

<div class="principal minHeight">

<?

$dataInicio = $_GET["dataInicio"];
if ($dataInicio == "") {
	$dataInicio = date('Y-m-d',mktime(0,0,0,date('m'),date('d')-7,date('Y')));
}

$dataFim = $_GET["dataFim"];
if ($dataFim == "") {
	$dataFim = date("Y-m-d");
}


// CONSULTA AO BANCO DE DADOS PARA MOSTRAR AS QUANTIDADES SEPARADAS POR STATUS
$totais = mysql_fetch_array(mysql_query("
SELECT SUM(CASE WHEN STATUS = 'ativo' THEN 1 ELSE 0 END) ativos
, SUM(CASE WHEN STATUS = 'inativo' THEN 1 ELSE 0 END) inativos
, SUM(CASE WHEN STATUS IN ('demo','demoInativo') THEN 1 ELSE 0 END) demos
, SUM(CASE WHEN STATUS IN ('cancelado') THEN 1 ELSE 0 END) cancelados
FROM login 
WHERE STATUS IN ('ativo', 'inativo', 'demo', 'demoInativo', 'cancelado')
AND NOT ISNULL(email) AND email <> ''
AND id NOT IN (9,1581)")); 

?>

    <div class="titulo" style="margin-bottom:10px;">Atualização automática do Livro Caixa</div>
    <div style="clear:both; margin-bottom: 20px;"> </div>
    
    <div style="float:left">
    Período 
    <input name="DataInicio" id="DataInicio" type="text" value="<?=date('d/m/Y',strtotime($dataInicio))?>" maxlength="10"  style="width:85px;font-size: 12px;" class="campoData"/> 
      até 
    <input name="DataFim" id="DataFim" type="text" value="<?=date('d/m/Y',strtotime($dataFim))?>" maxlength="10"  style="width:85px;font-size: 12px;" class="campoData" />
    Desconto BB
    R$ <input name="descBB" id="descBB" type="text" value="3,50" maxlength="10"  style="width:40px;font-size: 12px;" class="current" />
	Desconto Cielo
    <input name="descCielo" id="descCielo" type="text" value="4" maxlength="10"  style="width:40px;font-size: 12px;" class="current" /> %
    </div>
    <div style="float: right;">
    <input name="btAtualiza" id="btAtualiza" type="button" value="Atualizar" />
    </div>
    <div style="clear:both;margin-bottom: 20px;"> </div>
  	<div id="output" style="margin-bottom: 20px; color: #C00;"></div>

</div>

<script>
$(document).ready(function(e) {

	$('#btAtualiza').click(function(e){
		e.preventDefault();
		var
		dataInicio = $('#DataInicio').val()
		, dataFim = $('#DataFim').val()
		, descBB = $('#descBB').val()
		, descCielo = $('#descCielo').val();
		$.ajax({
		  url:'cad_atualiza_livro_caixa.php'
		  , data: 'dtInicio='+dataInicio+'&dtFim=' + dataFim+'&descBB=' + descBB+'&descCielo=' + descCielo
		  , type: 'post'
		  , dataType:"json"
		  , async: true
		  , cache:false
		  , beforeSend: function(){
			$('#output').html('');  
		  }
		  , success: function(retorno){
			  
			  console.log(retorno);
			  
			  // Imprime o status na tela
			  $('#output').html(retorno.status);
			  
			//if(retorno.length > 0){
//				objCampo.parent().html(retorno);
			//}
		  }
		}); 	
	});
	$('#btAtualizaBoletos').click(function(e){
	
	});
});
</script>

<?php include '../rodape.php' ?>
