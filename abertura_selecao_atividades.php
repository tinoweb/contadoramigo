<?php include 'header_restrita.php' ?>

<? 
if($_POST['sel_ativi_cnae']) {
	$cnae_escolhido = $_POST['sel_ativi_cnae'];
	$query = "SELECT * FROM cnae_2018 WHERE cnae ='".$cnae_escolhido."'";
	$resulCnae = mysql_query($query);
	$listaResultado = mysql_fetch_array($resulCnae);

	if($listaResultado == ''){
		$sel_ativi_resultado = 'CNAE não encontrado';
	} elseif($listaResultado['anexo'] == 's' || $$listaResultado['anexo'] == 'x'){
		$sel_ativi_resultado = 'Consulte o Help Desk';
	} else {		
		if($listaResultado['Fator_R'] == 0){
			$sel_ativi_resultado = 'Anexo III - '.$listaResultado['denominacao'];
		} else {
			$sel_ativi_resultado = 'Anexo: '.$listaResultado['anexo'].' - '.$listaResultado['denominacao'];
		}
	}
}
?>

<div class="principal">
  <h1>Seleção de Atividades</h1>
  
Para escolher as atividades <strong>principal e secundárias</strong> de sua  empresa, seja para a abertura ou para alteração, faça uma pesquisa no site do <a href="http://www.cnae.ibge.gov.br/" target="_blank">IBGE/Concla</a>. Lá são informados os códigos CNAE de acordo com a atividade descrita. É o código CNAE que definirá alíquota a ser paga no Simples Nacional.<br>
<br>
Se você for <strong>Comércio</strong> ou <strong>Indústria</strong>, não há muito o que fazer, pois as tabelas para estes tipos de atividade são uma só. Contudo, se você for <strong>Prestador de Serviços,</strong> há 3 tabelas. Em alguns casos, atividades muito semelhantes aparecem enquadradas em tabelas diferentes. Tente  escolher aquela com a alíquota menor sem se desvirtuar de sua verdadeira área de atuação, já que pagar imposto sobre a tabela errada é considerado sonegação.<br>
<br>
Sua empresa pode desempenhar atividades enquadradas em tabelas diferentes. O Imposto do Simples será calculado
de acordo com o faturamento em cada atividade separadamente.<br>
<br>

<div class="tituloVermelho" style="margin-bottom: 20px">Confira as alíquotas </div>

<div style="float: left; margin-bottom: 20px">   
 	<form method="post" action="abertura_selecao_atividades.php">
      <label for="sel_ativi_cnae"><span style="width:100px">CNAE:</span></label>
      <input type="text" name="sel_ativi_cnae" id="sel_ativi_cnae" value="<?=$cnae_escolhido;?>" max="9" maxlength="9" style="width:70px">
      <input type="submit" name="submit" id="submit" value="Pesquisar" style="margin-right:30px"> 
	</form>
</div>
     
<div style="float: left; margin-bottom: 20px">RESULTADO: <span class="destaque"><?=$sel_ativi_resultado?></span></div>

<div style="clear: both"></div>

<?php 
	  if($listaResultado['Fator_R'] == 1){ ?>
		<strong>Atividade sujeita ao fator R</strong>: se a folha de pagamento (pró-labore + salários + autômomos) for inferior a 28% do faturamento, a atividade será tributada pelo <span class="destaque">Anexo V</span><br>
		  <br>
	  <?php } ?>

	<br>
    <table width="40%" border="0" cellpadding="5" cellspacing="2">
      <tbody>
        <tr>
          <th width="82"><b>Tabela</b></th>
          <th width="103"><b>Alíquota inicial</b></th>
          <th width="123"><b>Ramo de Atividade</b></th>
        </tr>
        <tr>
          <td class="td_calendario">ANEXO I</td>
          <td class="td_calendario">4%</td>
          <td class="td_calendario">Comércio</td>
        </tr>
        <tr>
          <td class="td_calendario">ANEXO II</td>
          <td class="td_calendario">4,5%</td>
          <td class="td_calendario">Indústria</td>
        </tr>
        <tr>
          <td class="td_calendario">ANEXO III</td>
          <td class="td_calendario">6%</td>
          <td class="td_calendario">Serviços</td>
        </tr>
        <tr>
          <td class="td_calendario">ANEXO IV</td>
          <td class="td_calendario">4,5%</td>
          <td class="td_calendario">Serviços</td>
        </tr>
        <tr>
          <td class="td_calendario">ANEXO V</td>
          <td class="td_calendario">15,5%</td>
          <td class="td_calendario">Serviços</td>
        </tr>
      </tbody>
    </table>
    <br>
    <br>
    <b>Observações:</b><br>
      <ul>
		  <li>Alíquotas válidas para faturamento <strong>até R$ 180 mil</strong> em 12 meses</li>
		  <li>Serviços prestados para o <strong>exterior têm alíquotas reduzidas</strong>, consulte nosso <a href="suporte.php">Help Desk</a></li>
      <li>No <b>Anexo IV</b>, embora a alíquota pareça   mais vantajosa, as empresas devem pagar separadamente a <b>CPP - Contribuição Previdenciária Patronal</b>, o que a torna menos interessante que a do Anexo III.
      </li>	  
      </ul>

  

</div>


<?php include 'rodape.php' ?>
