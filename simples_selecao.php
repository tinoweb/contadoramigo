<?php
include 'header_restrita.php';


foreach($_SESSION['cnaes_empresa_mes'] as $cnae){

//	$descr_cnae = mysql_fetch_array(mysql_query("SELECT * FROM cnae_completa WHERE cnae = '" .  $cnae . "'"));
	
	$CNAE = str_replace("/","",str_replace("-","",$cnae));
	
	//echo $CNAE . " -> ";
	//echo $_POST['anexo_'.$CNAE] . " -> ";
	//echo $_POST['marcar_check_'.$CNAE] . '<BR>';
	$checks .= $_POST['marcar_check_'.$CNAE] . ';';

}

$arr_checks = explode(';',substr($checks,0,strlen($checks)-1));
var_dump($arr_checks);
//exit;

$comretencao3 = 'nao';
$comretencao4 = 'nao';
$comretencao5 = 'nao';

$semretencao3 = 'nao';
$semretencao4 = 'nao';
$semretencao5 = 'nao';

$mesmomunicipio3 = 'nao';
$mesmomunicipio4 = 'nao';
$mesmomunicipio5 = 'nao';

$outromunicipio3 = 'nao';
$outromunicipio4 = 'nao';
$outromunicipio5 = 'nao';

$atividades3 = '';
$atividades4 = '';
$atividades5 = '';

?>

<script>

function checa_tudo1 () {

if (semretencao3 == "sim" && outromunicipio3 == "sim") {document.getElementById('check_div1').src="images/simples/pontovermelho.png";}
if (semretencao3 == "sim" && mesmomunicipio3 == "sim") {document.getElementById('check_div2').src="images/simples/pontovermelho.png";}
if (comretencao3 == "sim")                             {document.getElementById('check_div3').src="images/simples/pontovermelho.png";}
if (semretencao4 == "sim" && outromunicipio4 == "sim") {document.getElementById('check_div4').src="images/simples/pontovermelho.png";}
if (semretencao4 == "sim" && mesmomunicipio4 == "sim") {document.getElementById('check_div5').src="images/simples/pontovermelho.png";}
if (comretencao4 == "sim")                             {document.getElementById('check_div6').src="images/simples/pontovermelho.png";}
if (semretencao5 == "sim" && outromunicipio5 == "sim") {document.getElementById('check_div7').src="images/simples/pontovermelho.png";}
if (semretencao5 == "sim" && mesmomunicipio5 == "sim") {document.getElementById('check_div8').src="images/simples/pontovermelho.png";}
if (comretencao5 == "sim" )                            {document.getElementById('check_div9').src="images/simples/pontovermelho.png";}

}

</script>

<div class="principal">

<span class="titulo">Impostos e Obrigações - Simples Nacional</span><br />
<br />
<div class="tituloVermelho" style="margin-bottom:20px">Orientações para pagamento</div>

 <div id="passo7" style="display:block">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo6','passo7')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 7 de 12</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDiv('intro8'); abreDivFechaOutro('passo8','passo7'); checa_tudo2 (); ">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a> 
<br />
<br />
Muito bem, agora começa a complicar um pouquinho. Você deve se lembrar que assinalou no início deste tutorial quais atividades exerceu no período. A <a href="http://www.receita.fazenda.gov.br/Legislacao/LeisComplementares/2006/leicp123.htm" target="_blank">Lei Complementar 123</a>, que criou o Simples Nacional, instituiu alíquotas de imposto distintas, de acordo com a atividade exercida. Todas as atividades relacionadas ao comércio são agrupadas no chamado <strong>Anexo I</strong> da lei, as da indústria, no <strong>Anexo II</strong> e as de prestação de servicos, nos <strong>Anexos III, IV e V.</strong><br /><br />
Ou seja, se você é uma empresa prestadora de serviços e suas atividades estão agrupadas no Anexo III, você pagará o imposto estabelecido para as atividades do Anexo III, se suas atividades pertencerem ao Anexo IV, pagará o imposto referente às atividades do Anexo IV e se forem do Anexo V, pagará o imposto referente ao Anexo V. O sistema da Receita quer saber, neste passo, em quais anexos suas atividades se encaixam.<br /><br />
Baseado no que você assinalou no início do tutorial, o Contador Amigo já fez essa separação para você. <strong>Marque a(s) opção(es) indicada(s) em vermelho</strong>. Tome muito cuidado para não selecionar a opção errada, elas são  parecidas entre si!<br /><br />
ATENÇÃO: repare que, nas opções marcadas, além de indicar o Anexo, você está informando se o imposto é devido ao mesmo município e ainda se houve ou não retenção. Estas informações são importantes, pois se algum cliente fez retenção de imposto ao pagar sua nota, você não vai querer pagar o imposto de novo, certo?<br /><br />

<div style="background-image:url(images/simples2/simples_selecao.png); width:966px; height:953px">
<div style="height:323px"></div>
<div style="margin-left:35px; width:13px; height:16px"><img id="check_01" src="images/simples2/<?=in_array('01',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/></div>
<div style="margin-left:50px; width:13px; height:20px"><img id="check_02" src="images/simples2/<?=in_array('02',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/></div>
<div style="margin-left:50px; width:13px; height:21px"><img id="check_03" src="images/simples2/<?=in_array('03',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/></div>
<div style="margin-left:35px; width:13px; height:20px"><img id="check_04" src="images/simples2/<?=in_array('04',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/></div>
<div style="margin-left:35px; width:13px; height:16px"><img id="check_05" src="images/simples2/<?=in_array('05',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/></div>
<div style="margin-left:49px; width:13px; height:18px"><img id="check_06" src="images/simples2/<?=in_array('06',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/></div>
<div style="margin-left:49px; width:13px; height:22px"><img id="check_07" src="images/simples2/<?=in_array('07',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/></div>
<div style="margin-left:35px; width:13px; height:19px"><img id="check_08" src="images/simples2/<?=in_array('08',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/></div>
<div style="margin-left:35px; width:13px; height:20px"><img id="check_09" src="images/simples2/<?=in_array('09',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/></div>
<div style="margin-left:36px; width:13px; height:16px"><img id="check_10" src="images/simples2/<?=in_array('10',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/></div>
<div style="margin-left:50px; width:13px; height:19px"><img id="check_11" src="images/simples2/<?=in_array('11',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/></div>
<div style="margin-left:50px; width:13px; height:19px"><img id="check_12" src="images/simples2/<?=in_array('12',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/></div>
<div style="margin-left:50px; width:13px; height:19px"><img id="check_13" src="images/simples2/<?=in_array('13',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/></div>
<div style="margin-left:50px; width:13px; height:19px"><img id="check_14" src="images/simples2/<?=in_array('14',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/></div>
<div style="margin-left:50px; width:13px; height:19px"><img id="check_15" src="images/simples2/<?=in_array('15',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/></div>
<div style="margin-left:50px; width:13px; height:19px"><img id="check_16" src="images/simples2/<?=in_array('16',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/></div>
<div style="margin-left:50px; width:13px; height:19px"><img id="check_17" src="images/simples2/<?=in_array('17',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/></div>
<div style="margin-left:50px; width:13px; height:19px"><img id="check_18" src="images/simples2/<?=in_array('18',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/></div>
<div style="margin-left:50px; width:13px; height:19px"><img id="check_19" src="images/simples2/<?=in_array('19',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/></div>
<div style="margin-left:50px; width:13px; height:24px"><img id="check_20" src="images/simples2/<?=in_array('20',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/></div>
<div style="margin-left:34px; width:13px; height:16px"><img id="check_21" src="images/simples2/<?=in_array('21',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/></div>
<div style="margin-left:49px; width:13px; height:19px"><img id="check_22" src="images/simples2/<?=in_array('22',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/></div>
<div style="margin-left:49px; width:13px; height:19px"><img id="check_23" src="images/simples2/<?=in_array('23',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/></div>
<div style="margin-left:49px; width:13px; height:19px"><img id="check_24" src="images/simples2/<?=in_array('24',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/></div>
<div style="margin-left:49px; width:13px; height:22px"><img id="check_25" src="images/simples2/<?=in_array('25',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/></div>
<div style="margin-left:34px; width:13px; height:17px"><img id="check_26" src="images/simples2/<?=in_array('26',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/></div>
<div style="margin-left:49px; width:13px; height:19px"><img id="check_27" src="images/simples2/<?=in_array('27',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/></div>
<div style="margin-left:49px; width:13px; height:19px"><img id="check_28" src="images/simples2/<?=in_array('28',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/></div>
<div style="margin-left:49px; width:13px; height:19px"><img id="check_29" src="images/simples2/<?=in_array('29',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/></div>
</div>
</div>


</body>
</html>
