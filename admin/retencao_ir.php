<?php include '../conect.php';
include '../session.php';
include 'check_login.php' ?>
<?php include 'header.php' ?>

<?php

//CRIA VARIÁVEIS PARA A TABELA DE IR
$sql2 = "SELECT * FROM tabelas WHERE ano_calendario = ";
if(isset($_REQUEST['ano_calendario'])){
	$sql2 .= "'" . $_REQUEST['ano_calendario'] . "'";
}else{
	$sql2 .= "'" . date('Y') . "'";
}
//echo $sql2;
$resultado2 = mysql_query($sql2)
or die (mysql_error());
$linha2=mysql_fetch_array($resultado2);

$ano_calendario = $linha2['ano_calendario'];

$isento = number_format($linha2['ValorBruto1'],2,',','.');

$ValorBruto1 = number_format($linha2['ValorBruto1'],2,',','.');
$ValorBruto2 = number_format($linha2['ValorBruto2'],2,',','.');
$ValorBruto3 = number_format($linha2['ValorBruto3'],2,',','.');
$ValorBruto4 = number_format($linha2['ValorBruto4'],2,',','.');
$Aliquota1 = number_format($linha2['Aliquota1'],2,',','.');
$Aliquota2 = number_format($linha2['Aliquota2'],2,',','.');
$Aliquota3 = number_format($linha2['Aliquota3'],2,',','.');
$Aliquota4 = number_format($linha2['Aliquota4'],2,',','.');
$Aliquota5 = number_format($linha2['Aliquota5'],2,',','.');
$Desconto1 = number_format($linha2['Desconto1'],2,',','.');
$Desconto2 = number_format($linha2['Desconto2'],2,',','.');
$Desconto3 = number_format($linha2['Desconto3'],2,',','.');
$Desconto4 = number_format($linha2['Desconto4'],2,',','.');
$Desconto5 = number_format($linha2['Desconto5'],2,',','.');
$Deducao = number_format($linha2['Desconto_Ir_Dependentes'],2,',','.');
 

// CRIA VARIÀVEIS DO ANEXO I
$sql1 = "SELECT * FROM anexoI";
$resultado1 = mysql_query($sql1)
or die (mysql_error());


$i=0;
while($linha1 = mysql_fetch_array($resultado1)){
   $i++;
   $faturamento_min1[$i] = number_format($linha1['faturamento_min'],2,',','.');
   $faturamento_max1[$i] = number_format($linha1['faturamento_max'],2,',','.');
   $ir1[$i] = number_format($linha1['ir'],2,',','.');
}


// CRIA VARIÀVEIS DO ANEXO II
$sql2 = "SELECT * FROM anexoII";
$resultado2 = mysql_query($sql2)
or die (mysql_error());


$i=0;
while($linha2 = mysql_fetch_array($resultado2)){
   $i++;
   $faturamento_min2[$i] = number_format($linha2['faturamento_min'],2,',','.');
   $faturamento_max2[$i] = number_format($linha2['faturamento_max'],2,',','.');
   $ir2[$i] = number_format($linha2['ir'],2,',','.');
}


// CRIA VARIÀVEIS DO ANEXO III
$sql3 = "SELECT * FROM anexoIII";
$resultado3 = mysql_query($sql3)
or die (mysql_error());


$i=0;
while($linha3 = mysql_fetch_array($resultado3)){
   $i++;
   $faturamento_min3[$i] = number_format($linha3['faturamento_min'],2,',','.');
   $faturamento_max3[$i] = number_format($linha3['faturamento_max'],2,',','.');
   $ir3[$i] = number_format($linha3['ir'],2,',','.');
}




// CRIA VARIÀVEIS DO ANEXO IV
$sql4 = "SELECT * FROM anexoIV";
$resultado4 = mysql_query($sql4)
or die (mysql_error());


$i=0;
while($linha4 = mysql_fetch_array($resultado4)){
   $i++;
   $faturamento_min4[$i] = number_format($linha4['faturamento_min'],2,',','.');
   $faturamento_max4[$i] = number_format($linha4['faturamento_max'],2,',','.');
   $ir4[$i] = number_format($linha4['ir'],2,',','.');
}


// CRIA VARIÀVEIS DO ANEXO V
$sql5 = "SELECT * FROM anexoV";
$resultado5 = mysql_query($sql5)
or die (mysql_error());


$i=0;
while($linha5 = mysql_fetch_array($resultado5)){
   $i++;
   $faturamento_min5[$i] = number_format($linha5['faturamento_min'],2,',','.');
   $faturamento_max5[$i] = number_format($linha5['faturamento_max'],2,',','.');
   $cpp[$i] = number_format($linha5['cpp'],3,',','.');
}




// CRIA VARIÀVEIS DO ANEXO VI
$sql6 = "SELECT * FROM anexoVI";
$resultado6 = mysql_query($sql6)
or die (mysql_error());


$i=0;
while($linha6 = mysql_fetch_array($resultado6)){
   $i++;
   $faturamento_min6[$i] = number_format($linha6['faturamento_min'],2,',','.');
   $faturamento_max6[$i] = number_format($linha6['faturamento_max'],2,',','.');
   $cpp6[$i] = number_format($linha6['cpp'],3,',','.');
}



?>

<div class="principal">

  <div class="titulo" style="margin-bottom:10px;">Tabelas</div>
  Os valores, alíquotas e desconto das tabelas abaixo foram tirados da página: <a href="http://www.receita.fazenda.gov.br/aliquotas/contribfont2012a2015.htm">Receita Federal</a> e são utilizadas no Contador Amigo em:
<ul>
<li><a href="https://contadoramigo.websiteseguro.com/header_restrita.php"> Header Restrita</a></li>
<li><a href="https://contadoramigo.websiteseguro.com/pro_labore.php"> Pró-Labore</a></li>
<li><a href="https://contadoramigo.websiteseguro.com/pagamento_autonomos.php">Pagamento de autonomos</a></li>
</ul>
  
  <div class="tituloVermelho" style="margin-bottom:10px">Tabela de retenção de IR</div>
  
  <form action="tabelas_gravar.php" method="post">
  <input type="hidden" name="acao" value="IR" />
  <table border="0" cellspacing="3" cellpadding="3" style="margin-bottom:5px">
  <tr>
  	<td colspan="4">
    Valor máximo da faixa anterior e mínimo da seguinte devem ser iguais
    </td>
  </tr>  
  <tr>
  	<td colspan="4">
    Ano Calendário: <select name="ano_calendario" id="ano_calendario" onchange="location.href='retencao_ir.php?ano_calendario='+this.value">
    <?
    $ano_inicio = (int)(date('Y')+1);
    $ano_fim = 2011;
	$ano_compara = (isset($_REQUEST['ano_calendario']) ? $_REQUEST['ano_calendario'] : date('Y'));
	for($i=$ano_inicio; $i >= $ano_fim; $i--){?>
    	<option value="<?=$i?>" <?=$ano_compara == $i ? "selected" : ""?>><?=$i?></option>
    <? } ?>
    </select>
    </td>
  </tr>
  <tr>
  	<td colspan="4">
    Limite Isento de Retenção de IR: <?=$isento;?>
    </td>
  </tr>
  <tr>
  	<td colspan="4">
    Dedução por Dependente: <input type="text" name="Deducao" value="<?=$Deducao;?>" />
    </td>
  </tr>
  <tr>
  	<td style="background-color:#024a68; color:#FFF">Valor Bruto Minimo</td>
    <td style="background-color:#024a68; color:#FFF">Valor Bruto Maximo</td>
    <td style="background-color:#024a68; color:#FFF">Aliquota</td>
    <td style="background-color:#024a68; color:#FFF">Desconto</td>
  </tr>
  <tr>
    <td><input type="text" readonly ></td>
    <td><input type="text" name="ValorBruto1" value=<?=$ValorBruto1?>></td>
    <td><input type="text" name="Aliquota1" value="<?=$Aliquota1?>"></td>
    <td><input type="text" name="Desconto1" value="<?=$Desconto1?>"></td>
  </tr>
  <tr>
    <td><input type="text" name="ValorBruto11" value="<?=$ValorBruto1?>"></td>
    <td><input type="text" name="ValorBruto2" value="<?=$ValorBruto2?>"></td>
    <td><input type="text" name="Aliquota2" value="<?=$Aliquota2?>"></td>
    <td><input type="text" name="Desconto2" value="<?=$Desconto2?>"></td>
    
  </tr>
  <tr>
    <td><input type="text" name="ValorBruto22" value="<?=$ValorBruto2?>"></td>
    <td><input type="text" name="ValorBruto3" value="<?=$ValorBruto3?>"></td>
    <td><input type="text" name="Aliquota3" value="<?=$Aliquota3?>"></td>
    <td><input type="text" name="Desconto3" value="<?=$Desconto3?>"></td>
  </tr>
  <tr>
    <td><input type="text" name="ValorBruto33" value="<?=$ValorBruto3?>"></td>
    <td><input type="text" name="ValorBruto4" value="<?=$ValorBruto4?>"></td>
    <td><input type="text" name="Aliquota4" value="<?=$Aliquota4?>"></td>
    <td><input type="text" name="Desconto4" value="<?=$Desconto4?>"></td>
    
  </tr>
  <tr>
    <td><input type="text" name="ValorBruto44" value="<?=$ValorBruto4?>"></td>
    <td><input type="text" readonly /></td>
    <td><input type="text" name="Aliquota5" value="<?=$Aliquota5?>"></td>
    <td><input type="text" name="Desconto5" value="<?=$Desconto5?>"></td>
  </tr>
</table>
<br />

<input type="submit" value="Salvar"><br />
<br />

  </form>
</div>


<?php include '../rodape.php' ?>
