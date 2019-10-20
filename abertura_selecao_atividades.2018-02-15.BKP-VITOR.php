<?php include 'header_restrita.php' ?>

<? 
if($_POST['sel_ativi_cnae']) {
$cnae_escolhido = $_POST['sel_ativi_cnae'];
$linha = mysql_query("SELECT * FROM cnae WHERE cnae LIKE '" . $cnae_escolhido ."'")
or die (mysql_error());
$acha=mysql_fetch_array($linha);
$sel_ativi_resultado = $acha['anexo'].' - '.$acha['denominacao'];

if ($sel_ativi_resultado == "") { $sel_ativi_resultado = "CNAE não encontrado";}
else {
  if ($sel_ativi_resultado == "s" || $sel_ativi_resultado == "x") { 
    $aux = $sel_ativi_resultado;
    $sel_ativi_resultado = "Consulte o Help Desk";
    if( $aux == "x" ){
      $sel_ativi_resultado = "Atividade impeditiva ao Simples Nacional, consulte o Help Desk";
    }
  }
else {$sel_ativi_resultado = "ANEXO " . $sel_ativi_resultado;}
}
}
?>

<div class="principal">
  <div class="titulo" style="margin-bottom:10px;">Abertura de Empresa</div>
  <div class="tituloVermelho">Seleção de Atividades </div>
Para escolher as atividades <strong>principal e secundárias</strong> de sua  empresa, seja para a abertura ou para alteração, faça uma pesquisa no site do <a href="http://www.cnae.ibge.gov.br/" target="_blank">IBGE/Concla</a>. Lá são informados os códigos CNAE de acordo com a atividade descrita. É o código CNAE que definirá alíquota a ser paga no Simples Nacional.<br>
<br>
Se você for <strong>Comércio</strong> ou <strong>Indústria</strong>, não há muito o que fazer, pois as tabelas para estes tipos de atividade são uma só. Contudo, se você for <strong>Prestador de Serviços,</strong> há 4 tabelas. Em alguns casos, atividades muito semelhantes aparecem enquadradas em tabelas diferentes. Tente  escolher aquela com a alíquota menor sem se desvirtuar de sua verdadeira área de atuação, um vez que pagar imposto sobre a tabela errada é considerado sonegação.<br>
<br>
Sua empresa pode desempenhar atividades enquadradas em tabelas diferentes. O Imposto do Simples será calculado
de acordo com o faturamento em cada atividade separadamente.<br>
<br>
<br>
<div class="tituloPeq" style="margin-bottom:20px">Confira as Alíquotas</div>
    <!-- Digite a seguir o CNAE completo, no formato 00000-0/00<br><br> -->
 <form method="post" action="abertura_selecao_atividades.php">
      <label for="sel_ativi_cnae"><span style="width:100px">CNAE:</span></label>
      <input type="text" name="sel_ativi_cnae" id="sel_ativi_cnae" value="<?=$cnae_escolhido;?>" max="9" maxlength="9" style="width:70px">
    <input type="submit" name="submit" id="submit" value="Pesquisar" style="margin-right:30px"> 
      Resultado: <span class="destaque"><?=$sel_ativi_resultado;?></span><br>
 </form>
      <br><br>
    <table width="346" border="0" cellpadding="5" cellspacing="2">
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
          <td class="td_calendario">17,5%</td>
          <td class="td_calendario">Serviços</td>
        </tr>
        <tr>
          <td class="td_calendario">ANEXO VI</td>
          <td class="td_calendario">16,93%</td>
          <td class="td_calendario">Serviços</td>
        </tr>
      </tbody>
    </table>
    <br>
    <br>
    <b>Observações:</b><br>
      <ul>
      <li>O percentuais acima se referem a empresas com <b>receita bruta anual de até R$ 180 mil</b>.  Se a empresa estiver em início, o sistema de apuração fará uma projecão do faturamento anual com base no faturamento dos primeiros meses.<br>
        <br>
      </li>
      <li>No <b>Anexo IV</b>, embora a alíquota pareça   mais vantajosa, as empresas devem pagar separadamente a <b>CPP - Contribuição Previdenciária Patronal</b>, o que a torna menos interessante que a do Anexo III.<br>
        <br>
      </li>
      <li>No <b>Anexo V</b> a alíquota baixa gradativamente de acordo com o percentual da receita comprometido com o pagamento de pró-labore e salários. Para uma folha equivalente pelo menos 40% de sua receita bruta a alíquota cairá para 10%. Como você não tem funcionários, o único salário a levar em consideração, seria o seu pró-labore e eventuais pagamentos a autônomos. No entanto, se você estipular um pró-labore muito alto, pagará IR na pessoa física e um valor mais alto de INSS.</li>
      </ul>

  

</div>


<?php include 'rodape.php' ?>
