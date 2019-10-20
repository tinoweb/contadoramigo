<?php include 'header_restrita.php' ?>

<?

unset($_SESSION['dados_DARF_userSessao']);

function get_nome_mes($numero_mes){
	$arrMonth = array(
		1 => 'janeiro',
		2 => 'fevereiro',
		3 => 'março',
		4 => 'abril',
		5 => 'maio',
		6 => 'junho',
		7 => 'julho',
		8 => 'agosto',
		9 => 'setembro',
		10 => 'outubro',
		11 => 'novembro',
		12 => 'dezembro'
		);
	return $arrMonth[(int)$numero_mes];
}
?>

<div class="minHeight principal">

<h1>Impostos e Obrigações</h1>

<h2>Recolhimento do IRRF - DARF</h2>

<div style="width:100%;">
<form id="formDARF" method="post" action="darf_pagamentos.php">
Recolhimentos de DARF referente à retenção de IR sobre pró-labore, salários e serviços efetuados em <select name="mes" id="mes">
<?
$mesReferencia = ((int)date('m')) - 1;
if(((int)date('m')) == 1){
	$mesReferencia = 12;
}
for($i = 1; $i <= 12; $i++) {?>
	<option value="<?=$i?>"<?=($mesReferencia == $i ? " selected" : "")?>><?=ucfirst(get_nome_mes($i))?></option>
<? } ?>
</select> de 
<select name="ano" id="ano">
<?
$anoInicio = date('Y') - 5;
$anoAtual = date('Y');
$anoReferencia = $anoAtual;
if(date('m') == '01'){
	$anoReferencia = $anoAtual - 1;
}

for($i = $anoInicio; $i <= $anoAtual; $i++) {?>
	<option value="<?=$i?>"<?
    echo ($anoReferencia == $i ? " selected" : "");
	?>><?=$i?></option>
<? } ?>
</select> <input type="button" id="btProsseguir" value="Prosseguir" />
</form>
</div>

</div>

<script>
	$(document).ready(function(e) {
        
		$('#btProsseguir').bind('click',function(e){
			e.preventDefault();
			$('#formDARF').submit();
			
//			$.ajax({
//			  url:'darf_checa_movimento.php?id=<?=$_SESSION["id_empresaSecao"]?>&mes=' + $('#mes').val() + '&ano=' + $('#ano').val(),
//			  type: 'get',
//			  async: false,
//			  cache: false,
//			  success: function(ret){
//
//				console.log(ret);
//				  
//				if(ret == 'ok'){
//					
//				}
//				if(ret == 'pagto-funcionario'){
//					alert('Verificamos que em sua folha de pagamento do período há remunerações a funcionários. A Gfip de empresas com funcionários só pode ser gerada a partir do plano Premium.');
//					return false;
//				}   
//			  }
//			});
//			
		});
		
    });
</script>

<?php include 'rodape.php' ?>

