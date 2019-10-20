<?php include 'header_restrita.php' ?>

<div class="principal">
<div style="width:740px" class="minHeight">

<div class="titulo" style="margin-bottom:20px">Notas fiscais</div>
<div style="margin-bottom:20px">
<div class="tituloVermelho" style="margin-bottom:10px">Aplicativo de transparência Fiscal</div>
Neste aplicativo você saberá qual o valor referente a impostos embutido em suas notas fiscais de serviços.Os valores apresentados deverão ser informados no mesmo campo da descrição do serviço. A informação dos valores dos impostos é
  necessária somente nas vendas e serviços prestados ao consumidor final pessoa física. Atenção, este aplicativo não deve ser utilizado para notas fiscais de venda. Neste caso o valor dos impostos já aparece discriminado na nota.
  <br />
  <br />
  
  <div style="width:110px; float:left"><label for="valor_servico">Valor do serviço:</label></div>
  <div style="float:left"><input type="text" name="valor_servico" id="valor_servico" style="width:100px" /></div>
  <div style="clear:both; height:10px"></div>

  <div style="width:110px; float:left"><label for="atividade_prestada">Atividade prestada:</label></div>
  <div style="float:left">
  <select name="atividade_prestada" id="atividade_prestada">
  </select>
  </div>
  <div style="clear:both; height:10px"></div>

  <div style="width:110px; float:left"><label for="faturamento">Faturamento:</label></div>
  <div style="float:left">
  <select name="faturamento" id="faturamento">
  <option>De 3.420.000,01 a 3.600.000,00</option>
  </select>
  (nos últimos 12 meses)</div>
  <div style="clear:both; height:10px"></div>
  
  <div style="margin-bottom:20px"><label for="salarios">Percentual gasto com pró-labores, salários e  autônomos em relação ao faturamento:</label>
  <input type="text" name="salarios" id="salarios" /></div>

  <div style="margin-bottom:20px"><input type="submit" name="button" id="button" value="Calcular impostos" /></div>

  <div class="tituloAzul">Resultado</div>

<div style="font-size:14px; color:#a61d00; margin-bottom:10px">Impostos: IRPJ - R$ 0,00; Contribuição Social - R$ 0,00; Cofins - R$ 0,00;<br />
PIS/Pasep - R$ 0,00; Previdência Social - R$ 0,00; ISS - R$ 0,00</div>

<div style="font-size:11px">(copie e cole esta informação em sua nota fiscal, junto com a descrição do serviço)</div>

</div>
</div>
</div>
<?php include 'rodape.php' ?>

