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

 <!--  <div class="titulo" style="margin-bottom:10px;">Tabelas</div>
  Os valores, alíquotas e desconto das tabelas abaixo foram tirados da página: <a href="http://www.receita.fazenda.gov.br/aliquotas/contribfont2012a2015.htm">Receita Federal</a> e são utilizadas no Contador Amigo em:
<ul>
<li><a href="https://contadoramigo.websiteseguro.com/header_restrita.php"> Header Restrita</a></li>
<li><a href="https://contadoramigo.websiteseguro.com/pro_labore.php"> Pró-Labore</a></li>
<li><a href="https://contadoramigo.websiteseguro.com/pagamento_autonomos.php">Pagamento de autonomos</a></li>
</ul> -->
  <!-- 
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
    Ano Calendário: <select name="ano_calendario" id="ano_calendario" onchange="location.href='tabelas.php?ano_calendario='+this.value">
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
<br /> -->

  </form>
<div style="clear:both">
Os valores e alíquotas das tabelas abaixo foram tirados das páginas: <a href="http://www.pcaetano-rnc.com.br/noticias-detalhes.asp?noticia=1206">P.Caetano</a> e <a href="http://www.planalto.gov.br/ccivil_03/leis/lcp/lcp123.htm">Planalto</a> e são utilizadas no Contador Amigo em:
<ul>
<li><a href="https://contadoramigo.websiteseguro.com/distribuicao_de_lucros.php">Distribuição de Lucros</a></li>
<li><a href="https://contadoramigo.websiteseguro.com/pro_labore.php"> Pró-Labore</a></li>
<li><a href="https://contadoramigo.websiteseguro.com/pagamento_autonomos.php">Pagamento de autonomos</a></li>
</ul>
</div>


<div class="tituloVermelho" style="margin-bottom:10px">Anexo I</div>
  <form action="tabelas_gravar.php" method="post">
  <input type="hidden" name="acao" value="anexo1" />
  <table border="0" cellspacing="3" cellpadding="3" style="margin-bottom:5px">
  <tr>
  <tr>
  	<td style="background-color:#024a68; color:#FFF">Valor Minimo</td>
    <td style="background-color:#024a68; color:#FFF">Valor Maximo</td>
    <td style="background-color:#024a68; color:#FFF">Aliquota</td>
    
    </tr>
    <td><input type="text" name="faturamento_min1[]" readonly ></td>
    <td><input type="text" name="faturamento_max1[]" value=<?=$faturamento_max1[1]?>></td>
    <td><input type="text" name="ir1[]" value="<?=$ir1[1]?>"></td>
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min1[]" value="<?=$faturamento_min1[2]?>"></td>
    <td><input type="text" name="faturamento_max1[]" value="<?=$faturamento_max1[2]?>"></td>
    <td><input type="text" name="ir1[]" value="<?=$ir1[2]?>"></td>
   
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min1[]" value="<?=$faturamento_min1[3]?>"></td>
    <td><input type="text" name="faturamento_max1[]" value="<?=$faturamento_max1[3]?>"></td>
    <td><input type="text" name="ir1[]" value="<?=$ir1[3]?>"></td>
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min1[]" value="<?=$faturamento_min1[4]?>"></td>
    <td><input type="text" name="faturamento_max1[]" value="<?=$faturamento_max1[4]?>"></td>
    <td><input type="text" name="ir1[]" value="<?=$ir1[4]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min1[]" value="<?=$faturamento_min1[5]?>"></td>
    <td><input type="text" name="faturamento_max1[]" value=<?=$faturamento_max1[5]?>></td>
    <td><input type="text" name="ir1[]" value=<?=$ir1[5]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min1[]" value="<?=$faturamento_min1[6]?>"></td>
    <td><input type="text" name="faturamento_max1[]" value=<?=$faturamento_max1[6]?>></td>
    <td><input type="text" name="ir1[]" value=<?=$ir1[6]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min1[]" value="<?=$faturamento_min1[7]?>"></td>
    <td><input type="text" name="faturamento_max1[]" value=<?=$faturamento_max1[7]?>></td>
    <td><input type="text" name="ir1[]" value=<?=$ir1[7]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min1[]" value="<?=$faturamento_min1[8]?>"></td>
    <td><input type="text" name="faturamento_max1[]" value=<?=$faturamento_max1[8]?>></td>
    <td><input type="text" name="ir1[]" value=<?=$ir1[8]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min1[]" value="<?=$faturamento_min1[9]?>"></td>
    <td><input type="text" name="faturamento_max1[]" value=<?=$faturamento_max1[9]?>></td>
    <td><input type="text" name="ir1[]" value=<?=$ir1[9]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min1[]" value="<?=$faturamento_min1[10]?>"></td>
    <td><input type="text" name="faturamento_max1[]" value=<?=$faturamento_max1[10]?>></td>
    <td><input type="text" name="ir1[]" value=<?=$ir1[10]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min1[]" value="<?=$faturamento_min1[11]?>"></td>
    <td><input type="text" name="faturamento_max1[]" value="<?=$faturamento_max1[11]?>"></td>
    <td><input type="text" name="ir1[]" value="<?=$ir1[11]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min1[]" value="<?=$faturamento_min1[12]?>"></td>
    <td><input type="text" name="faturamento_max1[]" value="<?=$faturamento_max1[12]?>"></td>
    <td><input type="text" name="ir1[]" value="<?=$ir1[12]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min1[]" value="<?=$faturamento_min1[13]?>"></td>
    <td><input type="text" name="faturamento_max1[]" value="<?=$faturamento_max1[13]?>"></td>
    <td><input type="text" name="ir1[]" value="<?=$ir1[13]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min1[]" value="<?=$faturamento_min1[14]?>"></td>
    <td><input type="text" name="faturamento_max1[]" value="<?=$faturamento_max1[14]?>"></td>
    <td><input type="text" name="ir1[]" value="<?=$ir1[14]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min1[]" value="<?=$faturamento_min1[15]?>"></td>
    <td><input type="text" name="faturamento_max1[]" value="<?=$faturamento_max1[15]?>"></td>
    <td><input type="text" name="ir1[]" value="<?=$ir1[15]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min1[]" value="<?=$faturamento_min1[16]?>"></td>
    <td><input type="text" name="faturamento_max1[]" value="<?=$faturamento_max1[16]?>"></td>
    <td><input type="text" name="ir1[]" value="<?=$ir1[16]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min1[]" value="<?=$faturamento_min1[17]?>"></td>
    <td><input type="text" name="faturamento_max1[]" value="<?=$faturamento_max1[17]?>"></td>
    <td><input type="text" name="ir1[]" value="<?=$ir1[17]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min1[]" value="<?=$faturamento_min1[18]?>"></td>
    <td><input type="text" name="faturamento_max1[]" value="<?=$faturamento_max1[18]?>"></td>
    <td><input type="text" name="ir1[]" value="<?=$ir1[18]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min1[]" value="<?=$faturamento_min1[19]?>"></td>
    <td><input type="text" name="faturamento_max1[]" value="<?=$faturamento_max1[19]?>"></td>
    <td><input type="text" name="ir1[]" value="<?=$ir1[19]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min1[]" value="<?=$faturamento_min1[20]?>"></td>
    <td><input type="text" name="faturamento_max1[]" readonly ></td>
    <td><input type="text" name="ir1[]" value="<?=$ir1[20]?>"></td>
    
    
  </tr>
</table>
<br />

<input type="submit" value="Salvar"><br />
<br />

</form>
<div style="clear:both"> </div>


<div class="tituloVermelho" style="margin-bottom:10px">Anexo II</div>
  <form action="tabelas_gravar.php" method="post">
  <input type="hidden" name="acao" value="anexo2" />
  <table border="0" cellspacing="3" cellpadding="3" style="margin-bottom:5px">
  <tr>
  <tr>
  	<td style="background-color:#024a68; color:#FFF">Valor Minimo</td>
    <td style="background-color:#024a68; color:#FFF">Valor Maximo</td>
    <td style="background-color:#024a68; color:#FFF">Aliquota</td>
    
    </tr>
    <td><input type="text" name="faturamento_min2[]" readonly ></td>
    <td><input type="text" name="faturamento_max2[]" value=<?=$faturamento_max2[1]?>></td>
    <td><input type="text" name="ir2[]" value="<?=$ir2[1]?>"></td>
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min2[]" value="<?=$faturamento_min2[2]?>"></td>
    <td><input type="text" name="faturamento_max2[]" value="<?=$faturamento_max2[2]?>"></td>
    <td><input type="text" name="ir2[]" value="<?=$ir2[2]?>"></td>
   
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min2[]" value="<?=$faturamento_min2[3]?>"></td>
    <td><input type="text" name="faturamento_max2[]" value="<?=$faturamento_max2[3]?>"></td>
    <td><input type="text" name="ir2[]" value="<?=$ir2[3]?>"></td>
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min2[]" value="<?=$faturamento_min2[4]?>"></td>
    <td><input type="text" name="faturamento_max2[]" value="<?=$faturamento_max2[4]?>"></td>
    <td><input type="text" name="ir2[]" value="<?=$ir2[4]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min2[]" value="<?=$faturamento_min2[5]?>"></td>
    <td><input type="text" name="faturamento_max2[]" value=<?=$faturamento_max2[5]?>></td>
    <td><input type="text" name="ir2[]" value=<?=$ir2[5]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min2[]" value="<?=$faturamento_min2[6]?>"></td>
    <td><input type="text" name="faturamento_max2[]" value=<?=$faturamento_max2[6]?>></td>
    <td><input type="text" name="ir2[]" value=<?=$ir2[6]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min2[]" value="<?=$faturamento_min2[7]?>"></td>
    <td><input type="text" name="faturamento_max2[]" value=<?=$faturamento_max2[7]?>></td>
    <td><input type="text" name="ir2[]" value=<?=$ir2[7]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min2[]" value="<?=$faturamento_min2[8]?>"></td>
    <td><input type="text" name="faturamento_max2[]" value=<?=$faturamento_max2[8]?>></td>
    <td><input type="text" name="ir2[]" value=<?=$ir2[8]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min2[]" value="<?=$faturamento_min2[9]?>"></td>
    <td><input type="text" name="faturamento_max2[]" value=<?=$faturamento_max2[9]?>></td>
    <td><input type="text" name="ir2[]" value=<?=$ir2[9]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min2[]" value="<?=$faturamento_min2[10]?>"></td>
    <td><input type="text" name="faturamento_max2[]" value=<?=$faturamento_max2[10]?>></td>
    <td><input type="text" name="ir2[]" value=<?=$ir2[10]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min2[]" value="<?=$faturamento_min2[11]?>"></td>
    <td><input type="text" name="faturamento_max2[]" value="<?=$faturamento_max2[11]?>"></td>
    <td><input type="text" name="ir2[]" value="<?=$ir2[11]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min2[]" value="<?=$faturamento_min2[12]?>"></td>
    <td><input type="text" name="faturamento_max2[]" value="<?=$faturamento_max2[12]?>"></td>
    <td><input type="text" name="ir2[]" value="<?=$ir2[12]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min2[]" value="<?=$faturamento_min2[13]?>"></td>
    <td><input type="text" name="faturamento_max2[]" value="<?=$faturamento_max2[13]?>"></td>
    <td><input type="text" name="ir2[]" value="<?=$ir2[13]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min2[]" value="<?=$faturamento_min2[14]?>"></td>
    <td><input type="text" name="faturamento_max2[]" value="<?=$faturamento_max2[14]?>"></td>
    <td><input type="text" name="ir2[]" value="<?=$ir2[14]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min2[]" value="<?=$faturamento_min2[15]?>"></td>
    <td><input type="text" name="faturamento_max2[]" value="<?=$faturamento_max2[15]?>"></td>
    <td><input type="text" name="ir2[]" value="<?=$ir2[15]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min2[]" value="<?=$faturamento_min2[16]?>"></td>
    <td><input type="text" name="faturamento_max2[]" value="<?=$faturamento_max2[16]?>"></td>
    <td><input type="text" name="ir2[]" value="<?=$ir2[16]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min2[]" value="<?=$faturamento_min2[17]?>"></td>
    <td><input type="text" name="faturamento_max2[]" value="<?=$faturamento_max2[17]?>"></td>
    <td><input type="text" name="ir2[]" value="<?=$ir2[17]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min2[]" value="<?=$faturamento_min2[18]?>"></td>
    <td><input type="text" name="faturamento_max2[]" value="<?=$faturamento_max2[18]?>"></td>
    <td><input type="text" name="ir2[]" value="<?=$ir2[18]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min2[]" value="<?=$faturamento_min2[19]?>"></td>
    <td><input type="text" name="faturamento_max2[]" value="<?=$faturamento_max2[19]?>"></td>
    <td><input type="text" name="ir2[]" value="<?=$ir2[19]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min2[]" value="<?=$faturamento_min2[20]?>"></td>
    <td><input type="text" name="faturamento_max2[]" readonly ></td>
    <td><input type="text" name="ir2[]" value="<?=$ir2[20]?>"></td>
    
    
  </tr>
</table>
<br />

<input type="submit" value="Salvar"><br />
<br />

</form>
<div style="clear:both"> </div>


<div class="tituloVermelho" style="margin-bottom:10px">Anexo III</div>
  <form action="tabelas_gravar.php" method="post">
  <input type="hidden" name="acao" value="anexo3" />
  <table border="0" cellspacing="3" cellpadding="3" style="margin-bottom:5px">
  <tr>
  <tr>
  	<td style="background-color:#024a68; color:#FFF">Valor Minimo</td>
    <td style="background-color:#024a68; color:#FFF">Valor Maximo</td>
    <td style="background-color:#024a68; color:#FFF">Aliquota</td>
    
    </tr>
    <td><input type="text" name="faturamento_min3[]" readonly ></td>
    <td><input type="text" name="faturamento_max3[]" value=<?=$faturamento_max3[1]?>></td>
    <td><input type="text" name="ir3[]" value="<?=$ir3[1]?>"></td>
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min3[]" value="<?=$faturamento_min3[2]?>"></td>
    <td><input type="text" name="faturamento_max3[]" value="<?=$faturamento_max3[2]?>"></td>
    <td><input type="text" name="ir3[]" value="<?=$ir3[2]?>"></td>
   
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min3[]" value="<?=$faturamento_min3[3]?>"></td>
    <td><input type="text" name="faturamento_max3[]" value="<?=$faturamento_max3[3]?>"></td>
    <td><input type="text" name="ir3[]" value="<?=$ir3[3]?>"></td>
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min3[]" value="<?=$faturamento_min3[4]?>"></td>
    <td><input type="text" name="faturamento_max3[]" value="<?=$faturamento_max3[4]?>"></td>
    <td><input type="text" name="ir3[]" value="<?=$ir3[4]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min3[]" value="<?=$faturamento_min3[5]?>"></td>
    <td><input type="text" name="faturamento_max3[]" value=<?=$faturamento_max3[5]?>></td>
    <td><input type="text" name="ir3[]" value=<?=$ir3[5]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min3[]" value="<?=$faturamento_min3[6]?>"></td>
    <td><input type="text" name="faturamento_max3[]" value=<?=$faturamento_max3[6]?>></td>
    <td><input type="text" name="ir3[]" value=<?=$ir3[6]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min3[]" value="<?=$faturamento_min3[7]?>"></td>
    <td><input type="text" name="faturamento_max3[]" value=<?=$faturamento_max3[7]?>></td>
    <td><input type="text" name="ir3[]" value=<?=$ir3[7]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min3[]" value="<?=$faturamento_min3[8]?>"></td>
    <td><input type="text" name="faturamento_max3[]" value=<?=$faturamento_max3[8]?>></td>
    <td><input type="text" name="ir3[]" value=<?=$ir3[8]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min3[]" value="<?=$faturamento_min3[9]?>"></td>
    <td><input type="text" name="faturamento_max3[]" value=<?=$faturamento_max3[9]?>></td>
    <td><input type="text" name="ir3[]" value=<?=$ir3[9]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min3[]" value="<?=$faturamento_min3[10]?>"></td>
    <td><input type="text" name="faturamento_max3[]" value=<?=$faturamento_max3[10]?>></td>
    <td><input type="text" name="ir3[]" value=<?=$ir3[10]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min3[]" value="<?=$faturamento_min3[11]?>"></td>
    <td><input type="text" name="faturamento_max3[]" value="<?=$faturamento_max3[11]?>"></td>
    <td><input type="text" name="ir3[]" value="<?=$ir3[11]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min3[]" value="<?=$faturamento_min3[12]?>"></td>
    <td><input type="text" name="faturamento_max3[]" value="<?=$faturamento_max3[12]?>"></td>
    <td><input type="text" name="ir3[]" value="<?=$ir3[12]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min3[]" value="<?=$faturamento_min3[13]?>"></td>
    <td><input type="text" name="faturamento_max3[]" value="<?=$faturamento_max3[13]?>"></td>
    <td><input type="text" name="ir3[]" value="<?=$ir3[13]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min3[]" value="<?=$faturamento_min3[14]?>"></td>
    <td><input type="text" name="faturamento_max3[]" value="<?=$faturamento_max3[14]?>"></td>
    <td><input type="text" name="ir3[]" value="<?=$ir3[14]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min3[]" value="<?=$faturamento_min3[15]?>"></td>
    <td><input type="text" name="faturamento_max3[]" value="<?=$faturamento_max3[15]?>"></td>
    <td><input type="text" name="ir3[]" value="<?=$ir3[15]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min3[]" value="<?=$faturamento_min3[16]?>"></td>
    <td><input type="text" name="faturamento_max3[]" value="<?=$faturamento_max3[16]?>"></td>
    <td><input type="text" name="ir3[]" value="<?=$ir3[16]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min3[]" value="<?=$faturamento_min3[17]?>"></td>
    <td><input type="text" name="faturamento_max3[]" value="<?=$faturamento_max3[17]?>"></td>
    <td><input type="text" name="ir3[]" value="<?=$ir3[17]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min3[]" value="<?=$faturamento_min3[18]?>"></td>
    <td><input type="text" name="faturamento_max3[]" value="<?=$faturamento_max3[18]?>"></td>
    <td><input type="text" name="ir3[]" value="<?=$ir3[18]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min3[]" value="<?=$faturamento_min3[19]?>"></td>
    <td><input type="text" name="faturamento_max3[]" value="<?=$faturamento_max3[19]?>"></td>
    <td><input type="text" name="ir3[]" value="<?=$ir3[19]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min3[]" value="<?=$faturamento_min3[20]?>"></td>
    <td><input type="text" name="faturamento_max3[]" readonly ></td>
    <td><input type="text" name="ir3[]" value="<?=$ir3[20]?>"></td>
    
    
  </tr>
</table>
<br />

<input type="submit" value="Salvar"><br />
<br />

</form>
<div style="clear:both"> </div>

<div class="tituloVermelho" style="margin-bottom:10px">Anexo IV</div>
  <form action="tabelas_gravar.php" method="post">
  <input type="hidden" name="acao" value="anexo4" />
  <table border="0" cellspacing="3" cellpadding="3" style="margin-bottom:5px">
  <tr>
  <tr>
  	<td style="background-color:#024a68; color:#FFF">Valor Minimo</td>
    <td style="background-color:#024a68; color:#FFF">Valor Maximo</td>
    <td style="background-color:#024a68; color:#FFF">Aliquota</td>
    
    </tr>
    <td><input type="text" name="faturamento_min4[]" readonly ></td>
    <td><input type="text" name="faturamento_max4[]" value=<?=$faturamento_max4[1]?>></td>
    <td><input type="text" name="ir4[]" value="<?=$ir4[1]?>"></td>
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min4[]" value="<?=$faturamento_min4[2]?>"></td>
    <td><input type="text" name="faturamento_max4[]" value="<?=$faturamento_max4[2]?>"></td>
    <td><input type="text" name="ir4[]" value="<?=$ir4[2]?>"></td>
   
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min4[]" value="<?=$faturamento_min4[3]?>"></td>
    <td><input type="text" name="faturamento_max4[]" value="<?=$faturamento_max4[3]?>"></td>
    <td><input type="text" name="ir4[]" value="<?=$ir4[3]?>"></td>
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min4[]" value="<?=$faturamento_min4[4]?>"></td>
    <td><input type="text" name="faturamento_max4[]" value="<?=$faturamento_max4[4]?>"></td>
    <td><input type="text" name="ir4[]" value="<?=$ir4[4]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min4[]" value="<?=$faturamento_min4[5]?>"></td>
    <td><input type="text" name="faturamento_max4[]" value=<?=$faturamento_max4[5]?>></td>
    <td><input type="text" name="ir4[]" value=<?=$ir4[5]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min4[]" value="<?=$faturamento_min4[6]?>"></td>
    <td><input type="text" name="faturamento_max4[]" value=<?=$faturamento_max4[6]?>></td>
    <td><input type="text" name="ir4[]" value=<?=$ir4[6]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min4[]" value="<?=$faturamento_min4[7]?>"></td>
    <td><input type="text" name="faturamento_max4[]" value=<?=$faturamento_max4[7]?>></td>
    <td><input type="text" name="ir4[]" value=<?=$ir4[7]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min4[]" value="<?=$faturamento_min4[8]?>"></td>
    <td><input type="text" name="faturamento_max4[]" value=<?=$faturamento_max4[8]?>></td>
    <td><input type="text" name="ir4[]" value=<?=$ir4[8]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min4[]" value="<?=$faturamento_min4[9]?>"></td>
    <td><input type="text" name="faturamento_max4[]" value=<?=$faturamento_max4[9]?>></td>
    <td><input type="text" name="ir4[]" value=<?=$ir4[9]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min4[]" value="<?=$faturamento_min4[10]?>"></td>
    <td><input type="text" name="faturamento_max4[]" value=<?=$faturamento_max4[10]?>></td>
    <td><input type="text" name="ir4[]" value=<?=$ir4[10]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min4[]" value="<?=$faturamento_min4[11]?>"></td>
    <td><input type="text" name="faturamento_max4[]" value=<?=$faturamento_max4[11]?>></td>
    <td><input type="text" name="ir4[]" value=<?=$ir4[11]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min4[]" value="<?=$faturamento_min4[12]?>"></td>
    <td><input type="text" name="faturamento_max4[]" value=<?=$faturamento_max4[12]?>></td>
    <td><input type="text" name="ir4[]" value=<?=$ir4[12]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min4[]" value="<?=$faturamento_min4[13]?>"></td>
    <td><input type="text" name="faturamento_max4[]" value=<?=$faturamento_max4[13]?>></td>
    <td><input type="text" name="ir4[]" value=<?=$ir4[13]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min4[]" value="<?=$faturamento_min4[14]?>"></td>
    <td><input type="text" name="faturamento_max4[]" value=<?=$faturamento_max4[14]?>></td>
    <td><input type="text" name="ir4[]" value=<?=$ir4[14]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min4[]" value="<?=$faturamento_min4[15]?>"></td>
    <td><input type="text" name="faturamento_max4[]" value=<?=$faturamento_max4[15]?>></td>
    <td><input type="text" name="ir4[]" value=<?=$ir4[15]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min4[]" value="<?=$faturamento_min4[16]?>"></td>
    <td><input type="text" name="faturamento_max4[]" value=<?=$faturamento_max4[16]?>></td>
    <td><input type="text" name="ir4[]" value=<?=$ir4[16]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min4[]" value="<?=$faturamento_min4[17]?>"></td>
    <td><input type="text" name="faturamento_max4[]" value=<?=$faturamento_max4[17]?>></td>
    <td><input type="text" name="ir4[]" value=<?=$ir4[17]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min4[]" value="<?=$faturamento_min4[18]?>"></td>
    <td><input type="text" name="faturamento_max4[]" value=<?=$faturamento_max4[18]?>></td>
    <td><input type="text" name="ir4[]" value=<?=$ir4[18]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min4[]" value="<?=$faturamento_min4[19]?>"></td>
    <td><input type="text" name="faturamento_max4[]" value=<?=$faturamento_max4[19]?>></td>
    <td><input type="text" name="ir4[]" value=<?=$ir4[19]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min4[]" value="<?=$faturamento_min4[20]?>"></td>
    <td><input type="text" name="faturamento_max4[]" readonly></td>
    <td><input type="text" name="ir4[]" value=<?=$ir4[20]?>></td>
    
    
  </tr>
</table>
<br />

<input type="submit" value="Salvar"><br />
<br />

</form>
<div style="clear:both"> </div>

<div class="tituloVermelho" style="margin-bottom:10px">Anexo V</div>
  <form action="tabelas_gravar.php" method="post">
  <input type="hidden" name="acao" value="anexo5" />
  <table border="0" cellspacing="3" cellpadding="3" style="margin-bottom:5px">
  <tr>
  <tr>
  	<td style="background-color:#024a68; color:#FFF">Valor Minimo</td>
    <td style="background-color:#024a68; color:#FFF">Valor Maximo</td>
    <td style="background-color:#024a68; color:#FFF">CPP</td>
    
    </tr>
    <td><input type="text" name="faturamento_min5[]" readonly ></td>
    <td><input type="text" name="faturamento_max5[]" value="<?=$faturamento_max5[1]?>"></td>
    <td><input type="text" name="cpp[]" value="<?=$cpp[1]?>"></td>
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min5[]" value="<?=$faturamento_min5[2]?>"></td>
    <td><input type="text" name="faturamento_max5[]" value="<?=$faturamento_max5[2]?>"></td>
    <td><input type="text" name="cpp[]" value="<?=$cpp[2]?>"></td>
   
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min5[]" value="<?=$faturamento_min5[3]?>"></td>
    <td><input type="text" name="faturamento_max5[]" value="<?=$faturamento_max5[3]?>"></td>
    <td><input type="text" name="cpp[]" value="<?=$cpp[3]?>"></td>
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min5[]" value="<?=$faturamento_min5[4]?>"></td>
    <td><input type="text" name="faturamento_max5[]" value="<?=$faturamento_max5[4]?>"></td>
    <td><input type="text" name="cpp[]" value="<?=$cpp[4]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min5[]" value="<?=$faturamento_min5[5]?>"></td>
    <td><input type="text" name="faturamento_max5[]" value="<?=$faturamento_max5[5]?>"></td>
    <td><input type="text" name="cpp[]" value="<?=$cpp[5]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min5[]" value="<?=$faturamento_min5[6]?>"></td>
    <td><input type="text" name="faturamento_max5[]" value="<?=$faturamento_max5[6]?>"></td>
    <td><input type="text" name="cpp[]" value="<?=$cpp[6]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min5[]" value="<?=$faturamento_min5[7]?>"></td>
    <td><input type="text" name="faturamento_max5[]" value="<?=$faturamento_max5[7]?>"></td>
    <td><input type="text" name="cpp[]" value="<?=$cpp[7]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min5[]" value="<?=$faturamento_min5[8]?>"></td>
    <td><input type="text" name="faturamento_max5[]" value="<?=$faturamento_max5[8]?>"></td>
    <td><input type="text" name="cpp[]" value="<?=$cpp[8]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min5[]" value="<?=$faturamento_min5[9]?>"></td>
    <td><input type="text" name="faturamento_max5[]" value="<?=$faturamento_max5[9]?>"></td>
    <td><input type="text" name="cpp[]" value="<?=$cpp[9]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min5[]" value="<?=$faturamento_min5[10]?>"></td>
    <td><input type="text" name="faturamento_max5[]" value="<?=$faturamento_max5[10]?>"></td>
    <td><input type="text" name="cpp[]" value="<?=$cpp[10]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min5[]" value="<?=$faturamento_min5[11]?>"></td>
    <td><input type="text" name="faturamento_max5[]" value="<?=$faturamento_max5[11]?>"></td>
    <td><input type="text" name="cpp[]" value="<?=$cpp[11]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min5[]" value="<?=$faturamento_min5[12]?>"></td>
    <td><input type="text" name="faturamento_max5[]" value="<?=$faturamento_max5[12]?>"></td>
    <td><input type="text" name="cpp[]" value="<?=$cpp[12]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min5[]" value="<?=$faturamento_min5[13]?>"></td>
    <td><input type="text" name="faturamento_max5[]" value="<?=$faturamento_max5[13]?>"></td>
    <td><input type="text" name="cpp[]" value="<?=$cpp[13]?>" /></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min5[]" value="<?=$faturamento_min5[14]?>"></td>
    <td><input type="text" name="faturamento_max5[]" value="<?=$faturamento_max5[14]?>"></td>
    <td><input type="text" name="cpp[]" value="<?=$cpp[14]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min5[]" value="<?=$faturamento_min5[15]?>"></td>
    <td><input type="text" name="faturamento_max5[]" value="<?=$faturamento_max5[15]?>"></td>
    <td><input type="text" name="cpp[]" value="<?=$cpp[15]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min5[]" value="<?=$faturamento_min5[16]?>"></td>
    <td><input type="text" name="faturamento_max5[]" value="<?=$faturamento_max5[16]?>"></td>
    <td><input type="text" name="cpp[]" value="<?=$cpp[16]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min5[]" value="<?=$faturamento_min5[17]?>"></td>
    <td><input type="text" name="faturamento_max5[]" value="<?=$faturamento_max5[17]?>"></td>
    <td><input type="text" name="cpp[]" value="<?=$cpp[17]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min5[]" value="<?=$faturamento_min5[18]?>"></td>
    <td><input type="text" name="faturamento_max5[]" value="<?=$faturamento_max5[18]?>"></td>
    <td><input type="text" name="cpp[]" value="<?=$cpp[18]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min5[]" value="<?=$faturamento_min5[19]?>"></td>
    <td><input type="text" name="faturamento_max5[]" value="<?=$faturamento_max5[19]?>"></td>
    <td><input type="text" name="cpp[]" value="<?=$cpp[19]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min5[]" value="<?=$faturamento_min5[20]?>"></td>
    <td><input type="text" name="faturamento_max5[]" readonly ></td>
    <td><input type="text" name="cpp[]" value="<?=$cpp[20]?>"></td>
    
    
  </tr>
</table>
<br />

<input type="submit" value="Salvar"><br />
<br />

</form>
<div style="clear:both"> </div>




<div class="tituloVermelho" style="margin-bottom:10px">Anexo VI</div>
  <form action="tabelas_gravar.php" method="post">
  <input type="hidden" name="acao" value="anexo6" />
  <table border="0" cellspacing="3" cellpadding="3" style="margin-bottom:5px">
  <tr>
  <tr>
  	<td style="background-color:#024a68; color:#FFF">Valor Minimo</td>
    <td style="background-color:#024a68; color:#FFF">Valor Maximo</td>
    <td style="background-color:#024a68; color:#FFF">CPP</td>
    
    </tr>
    <td><input type="text" name="faturamento_min6[]" readonly ></td>
    <td><input type="text" name="faturamento_max6[]" value=<?=$faturamento_max6[1]?>></td>
    <td><input type="text" name="cpp6[]" value="<?=$cpp6[1]?>"></td>
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min6[]" value="<?=$faturamento_min6[2]?>"></td>
    <td><input type="text" name="faturamento_max6[]" value="<?=$faturamento_max6[2]?>"></td>
    <td><input type="text" name="cpp6[]" value="<?=$cpp6[2]?>"></td>
   
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min6[]" value="<?=$faturamento_min6[3]?>"></td>
    <td><input type="text" name="faturamento_max6[]" value="<?=$faturamento_max6[3]?>"></td>
    <td><input type="text" name="cpp6[]" value="<?=$cpp6[3]?>"></td>
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min6[]" value="<?=$faturamento_min6[4]?>"></td>
    <td><input type="text" name="faturamento_max6[]" value="<?=$faturamento_max6[4]?>"></td>
    <td><input type="text" name="cpp6[]" value="<?=$cpp6[4]?>"></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min6[]" value="<?=$faturamento_min6[5]?>"></td>
    <td><input type="text" name="faturamento_max6[]" value=<?=$faturamento_max6[5]?>></td>
    <td><input type="text" name="cpp6[]" value=<?=$cpp6[5]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min6[]" value="<?=$faturamento_min6[6]?>"></td>
    <td><input type="text" name="faturamento_max6[]" value=<?=$faturamento_max6[6]?>></td>
    <td><input type="text" name="cpp6[]" value=<?=$cpp6[6]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min6[]" value="<?=$faturamento_min6[7]?>"></td>
    <td><input type="text" name="faturamento_max6[]" value=<?=$faturamento_max6[7]?>></td>
    <td><input type="text" name="cpp6[]" value=<?=$cpp6[7]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min6[]" value="<?=$faturamento_min6[8]?>"></td>
    <td><input type="text" name="faturamento_max6[]" value=<?=$faturamento_max6[8]?>></td>
    <td><input type="text" name="cpp6[]" value=<?=$cpp6[8]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min6[]" value="<?=$faturamento_min6[9]?>"></td>
    <td><input type="text" name="faturamento_max6[]" value=<?=$faturamento_max6[9]?>></td>
    <td><input type="text" name="cpp6[]" value=<?=$cpp6[9]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min6[]" value="<?=$faturamento_min6[10]?>"></td>
    <td><input type="text" name="faturamento_max6[]" value=<?=$faturamento_max6[10]?>></td>
    <td><input type="text" name="cpp6[]" value=<?=$cpp6[10]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min6[]" value="<?=$faturamento_min6[11]?>"></td>
    <td><input type="text" name="faturamento_max6[]" value=<?=$faturamento_max6[11]?>></td>
    <td><input type="text" name="cpp6[]" value=<?=$cpp6[11]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min6[]" value="<?=$faturamento_min6[12]?>"></td>
    <td><input type="text" name="faturamento_max6[]" value=<?=$faturamento_max6[12]?>></td>
    <td><input type="text" name="cpp6[]" value=<?=$cpp6[12]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min6[]" value="<?=$faturamento_min6[13]?>"></td>
    <td><input type="text" name="faturamento_max6[]" value=<?=$faturamento_max6[13]?>></td>
    <td><input type="text" name="cpp6[]" value=<?=$cpp6[13]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min6[]" value="<?=$faturamento_min6[14]?>"></td>
    <td><input type="text" name="faturamento_max6[]" value=<?=$faturamento_max6[14]?>></td>
    <td><input type="text" name="cpp6[]" value=<?=$cpp6[14]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min6[]" value="<?=$faturamento_min6[15]?>"></td>
    <td><input type="text" name="faturamento_max6[]" value=<?=$faturamento_max6[15]?>></td>
    <td><input type="text" name="cpp6[]" value=<?=$cpp6[15]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min6[]" value="<?=$faturamento_min6[16]?>"></td>
    <td><input type="text" name="faturamento_max6[]" value=<?=$faturamento_max6[16]?>></td>
    <td><input type="text" name="cpp6[]" value=<?=$cpp6[16]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min6[]" value="<?=$faturamento_min6[17]?>"></td>
    <td><input type="text" name="faturamento_max6[]" value=<?=$faturamento_max6[17]?>></td>
    <td><input type="text" name="cpp6[]" value=<?=$cpp6[17]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min6[]" value="<?=$faturamento_min6[18]?>"></td>
    <td><input type="text" name="faturamento_max6[]" value=<?=$faturamento_max6[18]?>></td>
    <td><input type="text" name="cpp6[]" value=<?=$cpp6[18]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min6[]" value="<?=$faturamento_min6[19]?>"></td>
    <td><input type="text" name="faturamento_max6[]" value=<?=$faturamento_max6[19]?>></td>
    <td><input type="text" name="cpp6[]" value=<?=$cpp6[19]?>></td>
    
    
  </tr>
  <tr>
    <td><input type="text" name="faturamento_min6[]" value="<?=$faturamento_min6[20]?>"></td>
    <td><input type="text" name="faturamento_max6[]" readonly></td>
    <td><input type="text" name="cpp6[]" value=<?=$cpp6[20]?>></td>
    
    
  </tr>
</table>
<br />

<input type="submit" value="Salvar"><br />
<br />

</form>
<div style="clear:both"> </div>


</div>


<?php include '../rodape.php' ?>
