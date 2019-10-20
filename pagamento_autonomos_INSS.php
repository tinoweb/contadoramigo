<?php include 'header_restrita.php' ?>

<script>

	$(document).ready(function(e) {

		// FOCO NO CAMPO DO VALOR BRUTO DEVE LIMPAR OS RESULTADOS DAS RETENÇÕES
		$("#ValorBruto").focus(function(){
			$("#RetencaoIR").val('');
			$("#RetencaoINSS").val('');
			$("#RetencaoISS").val('');
			$("#ValorLiquido").val('');
		});

		// BOTAO PARA GERACAO DA DECLARACAO
		$("#geraDeclaracao").click(function(){
			$.ajax({
				  url:'declaracao_download.php',
				  data: 'aut=' + $('#NomeAutonomo').val() + '&inss=' + $('#INSSOutrafonte').val() + '&cidade=' + $('#CidadeOutraFonte').val() + '&empresa=' + $('#NomeOutraFonte').val(),
				  type: 'POST',
				  cache: false,
				  async: true
			});
		});
		
		$('#NomeAutonomo').change(function(){
			if($(this).val() != ''){
				//url:'pagamento_autonomos_retorna_dados_autonomo.php?aut=' + $(this).val().split('|')[0],
				$.ajax({
				  url:'pagamento_autonomos_retorna_dados_autonomo.php?aut=' + $(this).val() + '&dtPagto=' + $('#DataPgto').val(),
				  type: 'get',
				  cache: false,
				  async: true,
				  beforeSend: function(){
					$("body").css("cursor", "wait");
				  },
				  success: function(retorno){
					$("body").css("cursor", "auto");
					if(retorno != '0'){
						// QUEBRANDO O RETORNO PARA POPULAR OS CAMPOS RESPECTIVOS
						ArrRetorno = retorno.split("|");
						$('#hddDependentes').val(ArrRetorno[2]);
						$('#hddAliquotaISS').val(ArrRetorno[3]);
						$('#hddPercPensao').val(ArrRetorno[4]);
						$('#hddSomaINSS').val(ArrRetorno[7]);
						$('#NomeOutraFonte').val(ArrRetorno[5]);
						$('#CidadeOutraFonte').val(ArrRetorno[6]);

					}
				  }
				});
			}
		});
		
		
        $('#OutraFontePagadora').click(function(){
			
			if($('#NomeAutonomo').val() == ''){
				alert('Selecione um autônomo');
				$('#OutraFonte').css('display','none');
				$('#INSSOutrafonte').val('');
				$('#NomeOutrafonte').val('');
				$('#CidadeOutrafonte').val('');
				$('#hddSomaINSS').val('');
				$('#OutraFontePagadora').attr('checked',false);
			}else{
				
				if($(this).attr('checked')==true){
					$('#OutraFonte').css('display','block');
				}else{
					$('#OutraFonte').css('display','none');
					$('#INSSOutrafonte').val('');
					$('#NomeOutrafonte').val('');
					$('#CidadeOutrafonte').val('');
					$('#hddSomaINSS').val('');
				}
			}
		});

		
		$('#btnGerarRPA').click(function(){
			if($('#DataPgto').val() == ''){
				alert('Preencha a data de pagamento.');
				return false;
			}
			
			if($('#ValorLiquido').val() == ''){
				calculaRetencoes();
				//alert('É necessário calcular as retenções para geração do RPA.');
				//$('#btnCalculaRetencoes').focus();
				//return false;
			}
			
			$.ajax({
			  url:'pagamento_autonomos_checa.php?id=<?=$_SESSION["id_empresaSecao"]?>&aut=' + $('#NomeAutonomo').val() + '&data=' + $('#DataPgto').val(),
			  type: 'get',
			  cache: false,
			  async: false,
			  beforeSend: function(){
				$("body").css("cursor", "wait");
			  },
			  success: function(retorno){
				$("body").css("cursor", "auto");
				
				<? //CHECANDO SE EXISTE UM PAGAMENTO PARA O MESMO AUTONOMO. SE EXISTIR PERGUNTA SE DEVE SOBRESCREVER ?>
				if(retorno == '1'){
					if(!confirm('Já existe um RPA gerado para este mesmo autônomo na data de hoje.\rEste registro será sobrescrito!')){
						return false;
					}else{
						$('#formGeraRPA').attr('action','RPA_download.php?acao=alt');
					}
				}else{
					$('#formGeraRPA').attr('action','RPA_download.php?acao=ins');
				}
				$('#formGeraRPA').submit();
				// HABILITA O BOTAO LIMPAR
				$('#btnLimparCampos').css('display','inline-block');
				// DITA A AÇÃO DO BOTAO LIMPAR
				$('#btnLimparCampos').click(function(){
					history.go(0);
				});
								
				if($('#OutraFontePagadora').attr('checked') == true){
					$('#aviso_declaracao').css('display','block');
				}

				// ZERA O FORMULARIO
				/*
				$('#OutraFontePagadora').attr('checked',false);
				$('#NomeAutonomo').val('');
				$('#ValorBruto').val('');
				$('#ValorLiquido').val('');
				$('#RetencaoIR').val('');
				$('#RetencaoINSS').val('');
				$('#RetencaoISS').val('');
				$('#OutraFonte').css('display','none');
				$('#INSSOutrafonte').val('');
				//$('#aviso_declaracao').css('display','none');
				*/

			  }
			});
			
		});
    });



function limparForm(idForm){
		var arrForm = $('#' + idForm).serializeArray();
		$.each(arrForm, function(i, objCampo){
			switch($("#" + idForm + " :input[name="+this.name+"]").attr('type')){
				case 'text':
					$("#" + idForm + " :input[name="+this.name+"]").attr('value','');
				break;
				case 'select-one':
					$('#'+this.name).attr('value','');
				break;
				case 'checkbox':
					$("#" + idForm + " :input[name="+this.name+"]").attr('checked','');
				break;
				case 'radio':
					$("#" + idForm + " :input[name="+this.name+"]").attr('checked','');
				break;
			}
		});
};

function calculaRetencoes(){
	
	if($('#NomeAutonomo').val() == ''){
		alert('Selecione um autônomo');
		return false;
	}
	
//pega o ValorBruto, transforma em float e põe no padrão americano para efeito de cálculo
	ValorBruto = document.getElementById('ValorBruto').value;
	if(ValorBruto == ''){
		alert('Preencha o valor bruto.');
		document.getElementById('ValorBruto').focus();
		return false;
	}
	ValorBruto = ValorBruto.replace(".","");
	ValorBruto = ValorBruto.replace(",",".");
	ValorBruto = parseFloat(ValorBruto);

	// calcula retenção do INSS
	INSS = (ValorBruto * 11)/100;

	if (INSS > <?= $Contribuicao_Maxima_n ?>) {INSS = <?= $Contribuicao_Maxima_n ?> };
	if (INSS < <?= $Contribuicao_Minima_n ?>) {INSS = <?= $Contribuicao_Minima_n ?> };

	INSSOutra = document.getElementById('INSSOutrafonte').value;
	if(INSSOutra != ''){
		INSSOutra = INSSOutra.replace(".","");
		INSSOutra = INSSOutra.replace(",",".");
		INSSOutra = parseFloat(INSSOutra);
		INSS = INSS - INSSOutra;
		if(INSS <= 0){
			INSS = 0;
		}
	}

	
	SomaINSSMes = document.getElementById('hddSomaINSS').value;
	if(SomaINSSMes != ''){
//		SomaINSSMes = SomaINSSMes.replace(".","");
//		SomaINSSMes = SomaINSSMes.replace(",",".");
//		SomaINSSMes = parseFloat(SomaINSSMes);
		if(SomaINSSMes >= INSS){
			INSS = 0;
		}else{
			INSS = INSS - SomaINSSMes;
		}
	}

//	INSS = 205.63;//****************
	
// calcula o desconto por quantidade de dependentes
	// PEGANDO DO VALUE DO COMBO A SEGUNDA POSIÇÃO DO VALUE = DEPENDENTES
	
	//arrValueAutonomo = document.getElementById('NomeAutonomo').value.split('|');
	//NumeroDep = arrValueAutonomo[1];
	
	NumeroDep = document.getElementById('hddDependentes').value;
    DescontoDep = NumeroDep * <?= $Desconto_Ir_Dependentes_n ?>;
//	DescontoDep = 106//*****************

// calcula alíquota e desconto do IR com base no ValorBruto para cálculo da pensão
    if (ValorBruto <= <?=$ValorBruto1?>) {aliquotaIR = <?=$Aliquota1?>; descontoIR = <?=$Desconto1?>}
    if (ValorBruto >  <?=$ValorBruto1?> && ValorBruto <= <?=$ValorBruto2?>){ aliquotaIR = <?=$Aliquota2?>; descontoIR = <?=$Desconto2?>}
    if (ValorBruto >  <?=$ValorBruto2?> && ValorBruto <= <?=$ValorBruto3?>){ aliquotaIR = <?=$Aliquota3?>; descontoIR = <?=$Desconto3?>}
    if (ValorBruto >  <?=$ValorBruto3?> && ValorBruto <= <?=$ValorBruto4?>){ aliquotaIR = <?=$Aliquota4?>; descontoIR = <?=$Desconto4?>}
    if (ValorBruto >  <?=$ValorBruto4?> ){ aliquotaIR = <?=$Aliquota5?>; descontoIR = <?=$Desconto5?>}
//	aliquotaIR = 27.5//*****************
//	descontoIR = 423.08//*****************

// obtem o valor da pensao 

    //Pega percentual da pensao, transforma em float, põe no padrão americano
	//PercentPensao = arrValueAutonomo[3];
	PercentPensao = document.getElementById('hddPercPensao').value;
	if (PercentPensao != "") {
		PercentPensao = PercentPensao.replace(".","");
		PercentPensao = PercentPensao.replace(",",".");
		PercentPensao = parseFloat(PercentPensao);
		
		//faz conta para descobrir o valor da pensao
        a = ValorBruto - INSS; 
		b = aliquotaIR/100; 
		c = ValorBruto - INSS - DescontoDep; 
		d = descontoIR; 
		e = PercentPensao/100; 
		f = b * c; 
		x = a - f + d; 
		y = e*x; 
		w = e*b; 
		Pensao = y/(1-w);
		
	} else {
		Pensao = 0;
	}
	
	
	// Veja como cheguei nesse calculo: 
	// Pensao = [ValorBruto - INSS - ((aliquotaIR/100) * (ValorBruto - INSS - DescontoDep - Pensao)) + descontoIR] * (percentPensao/100)
	// Pensao = (a - (b * (c-Pensao)) + d) * e	
	// Pensao = (a - (b * c - b * Pensao) + d) * e	
	// Pensao = (a - (f - b * Pensao) + d) * e	
	// Pensao = (a - f + b*Pensao + d) * e	
	// Pensao = (x + b*Pensao) * e	
	// Pensao = e*x + e*b*Pensao	
	// Pensao = y + w*Pensao
	// Pensao/Pensao = y/Pensao + w
	// 1 = y/Pensao + w
	// 1 - w = y/Pensao
	// (1-w)/y = 1/Pensao
	// y/(1-w) = Pensao
	
	

//Obtém a base de calculo
    BaseCalculoIR = ValorBruto - INSS - DescontoDep - Pensao;
	
//Calcula a alíquota e o desconto do IR
    if (BaseCalculoIR <= <?=$ValorBruto1?>) {aliquotaIR = <?=$Aliquota1?>; descontoIR = <?=$Desconto1?>}
    if (BaseCalculoIR >  <?=$ValorBruto1?> && BaseCalculoIR <= <?=$ValorBruto2?>){ aliquotaIR = <?=$Aliquota2?>; descontoIR = <?=$Desconto2?>}
    if (BaseCalculoIR >  <?=$ValorBruto2?> && BaseCalculoIR <= <?=$ValorBruto3?>){ aliquotaIR = <?=$Aliquota3?>; descontoIR = <?=$Desconto3?>}
    if (BaseCalculoIR >  <?=$ValorBruto3?> && BaseCalculoIR <= <?=$ValorBruto4?>){ aliquotaIR = <?=$Aliquota4?>; descontoIR = <?=$Desconto4?>}
    if (BaseCalculoIR >  <?=$ValorBruto4?> ){ aliquotaIR = <?=$Aliquota5?>; descontoIR = <?=$Desconto5?>}


	
	//Calcula o valor do IR incluindo a pensao
	IR = [(BaseCalculoIR * aliquotaIR)/100 ] - descontoIR;

	
	//calcula o ISS
	//aliquotaISS = arrValueAutonomo[2];
	aliquotaISS = document.getElementById('hddAliquotaISS').value;
	ISS = (ValorBruto * aliquotaISS)/100;
	
	//valor liquido
	ValorLiquido = ValorBruto - INSS - IR - ISS;

	document.getElementById('RetencaoIR').value		=	parseFloat(IR).toFixed(2).replace('.',',');
	document.getElementById('RetencaoINSS').value	=	parseFloat(INSS).toFixed(2).replace('.',',');
	document.getElementById('RetencaoISS').value	=	parseFloat(ISS).toFixed(2).replace('.',',');
	document.getElementById('ValorLiquido').value	=	parseFloat(ValorLiquido).toFixed(2).replace('.',',');
	
}



	function valida_cpf(cpf) {
		exp = /\d{11}/
		if(!exp.test(cpf))
		return false;
	} 
	
	function ValidaCep(cep){
		exp = /\d{8}/
		if(!exp.test(cep))
		return false; 
	}
	
	function ValidaDataCriacao(dataCriacao){
		exp = /\d{2}\/\d{2}\/\d{4}/
		if(!exp.test(dataCriacao))
		return false; 
	}
			
	function formSubmit(){   
		if( validElement('nome', msg1) == false){return false}
		if( validElement('CBO', msg1) == false){return false}
		if( validElement('CPF', msg1) == false){return false}
		if( validElement('RG', msg1) == false){return false}
		if( validElement('orgao_emissor', msg1) == false){return false}
		if( validElement('PIS', msg1) == false){return false}
		if( validElement('NumeroDep', msg1) == false){return false}
		if( validElement('Endereco', msg1) == false){return false}
		if( validElement('CEP', msg1) == false){return false}
		if( validElement('Cidade', msg1) == false){return false}
		if( validElement('Estado', msg1) == false){return false}
		if( validElement('tipo_servico', msg1) == false){return false}
		if( validElement('AliquotaISS', msg1) == false){return false}	
		
		var Cidade = document.getElementById('Cidade');
		if(Cidade.value.toLowerCase() == 'são paulo' || Cidade.value.toLowerCase() == 'sao paulo') {
			Cidade.value = 'São Paulo';
		}
		var Estado = document.getElementById('Estado');
		if(Estado.selectedIndex == ""){
			window.alert(msg2+'o Estado.');
			return false;
		}
		document.getElementById('form_autonomo').submit()			
	}
	
	function addJQuery(){
		var orig = $('#content');
		var count = parseInt($('#count').val());
		orig.append("<div id=\"item"+count+"\"><div style=\"float:left; margin-right:5px\"><input name=\"txtCodigoCNAE"+count+"\" id=\"txtCodigoCNAE"+count+"\" type=\"text\" style=\"width:84px; margin-top:3px;\" class=\"campoCNAE\" onblur=\"consultaBanco('meus_dados_empresa_consulta_cnae.php?codigo='+document.getElementById('txtCodigoCNAE"+count+"').value+'&campo="+count+"', 'atividade"+count+"');\" /></div> <div id=\"atividade"+count+"\" style=\"float:left; margin-top:5px\"><input type=\"hidden\" id=\"pesquisaCampo"+count+"\" name=\"pesquisaCampo"+count+"\" value=\"ok\" /></div> <div style=\"clear:both\"> </div></div>");
		$('#count').val(count+1);
		
		$('.campoCNAE').mask('9999-9/99');
	}
	function removeJQuery() {
		var count = parseInt($('#count').val());
		if (count > 1) {				
	//			var orig = $('#content');
	//			var removeDiv = document.getElementById('item'+(count-1));
			$('#item'+(count-1)).remove();	
			$('#count').val(count-1);
		}
	} 

</script>
<div class="principal">

<div class="titulo" style="margin-bottom:10px;">Pagamento a autônomo</div>
<div style="margin-bottom:30px">Toda vez que você contratar o trabalho de um <strong>profissional autônomo</strong> para serviços eventuais, precisará fazer a retenção de <strong>Imposto de Renda</strong>, o recolhimento do <strong>INSS</strong>, e o <strong>ISS</strong> relativos ao valor bruto cobrado. Retenção significa descontar uma parte do valor cobrado pelo profissional para pagar os impostos aos quais ele está sujeito. Pode parecer estranho você ter que reter e pagar um imposto que não é seu. Mas se não fizer isso e o autônomo não pagar o imposto, toda a dívida, incluindo juros e multa recairão sobre você e não sobre ele. A lógica por trás desta mecânica é que assim o governo garante que o imposto seja pago, Afinal já que o dinheiro do imposto não iria ficar com você mesmo, não há motivo para não pagá-lo.<br /><br />

Ao receber o pagamento pelo trabalho prestado, o profissional precisará assinar um recibo, chamado RPA (Recibo de Pagamento a Autônomo), com a discriminação de todos os valores retidos e posteriormente você deverá enviar ao prestador o comprovantes do pagamento dos impostos.<br />
<br />
Para que você não tenha dificuldades, o Contador Amigo criou o <strong>Aplicativo para Pagamento de Autônomo</strong> que calcula automaticamente as retenções devidas, emite o RPA a ser assinado pelo autônomo e mostra a você como gerar as guias de retenção.</div>

<div class="tituloVermelho" style="margin-bottom:20px">Aplicativo para Pagamento de Autônomo</div>

<form id="formGeraRPA" action="RPA_download.php" method="post">
<input type="hidden" name="hddDependentes" id="hddDependentes" value="" />
<input type="hidden" name="hddAliquotaISS" id="hddAliquotaISS" value="" />
<input type="hidden" name="hddPercPensao" id="hddPercPensao" value="" />
<input type="hidden" name="hddSomaINSS" id="hddSomaINSS" value="" />

<div style="line-height:25px">

<!--nome -->
<label for="NomeAutonomo" style="width:120px; text-align:right; margin-right:10px">Nome do autônomo: </label> 
<select name="NomeAutonomo" id="NomeAutonomo">
	<option value="">Selecione o autônomo</option>
<?
$query = mysql_query('SELECT id, nome FROM dados_autonomos WHERE id_login = '.$_SESSION["id_empresaSecao"].' ORDER BY nome');
while($dados = mysql_fetch_array($query)){
//	echo "<OPTION value=\"".$dados['id']."|".$dados['dependentes']."|".$dados['aliquota_ISS']."|".$dados['perc_pensao']."\">".$dados['nome']."</OPTION>";
	echo "<OPTION value=\"".$dados['id']."\">".$dados['nome']."</OPTION>";
}
?>
</select>
<a href="javascript:abreDiv('novo_autonomo')">cadastrar novo autônomo</a>&nbsp;&nbsp;ou&nbsp;&nbsp;<a href="meus_dados_autonomos.php">alterar dados de autônomo já cadastrado</a>
<div style="clear:both; height:5px"></div>

<!--data -->
<label for="DataPgto" style="width:120px; margin-right:10px">Data do pagamento:</label> <input name="DataPgto" id="DataPgto" type="text" size="12" maxlength="50" class="campoData" value="<?=date('d/m/Y')?>" /> (dd/mm/aaaa)<br />
<label for="OutraFontePagadora" style="margin-right:10px">Autônomo recolheu INSS por meio de outra fonte pagadora este mês?</label>
<input name="OutraFontePagadora" id="OutraFontePagadora" type="checkbox" value="1" /> Sim 
<!--
<input name="OutraFontePagadora" type="radio" value="1" /> Sim &nbsp;&nbsp;&nbsp;&nbsp;
<input name="OutraFontePagadora" type="radio" value="0" checked="checked" /> Não
-->
<div style="clear:both; height:5px"></div>

<!--outra fonte pagadora -->
<div style="display:none" id="OutraFonte"><label for="INSSOutrafonte" style="margin-left:30px; margin-right:10px">Valor Recolhido do imposto:</label>
R$ <input name="INSSOutrafonte" id="INSSOutrafonte" type="text" size="30" maxlength="12" class="current" />
<div style="clear:both; height:0px"></div>

<label for="NomeOutraFonte" style="float: left; margin-left:30px; margin-right:10px">Nome da outra fonte pagadora:</label>
 <input name="NomeOutraFonte" style="width:230px; float: left;" id="NomeOutraFonte" type="text" size="25" maxlength="50" />
<div style="clear:both; height:0px"></div>

<label for="CidadeOutraFonte" style="float: left; margin-left:30px; margin-right:10px">Cidade:</label>
 <input name="CidadeOutraFonte" style="width:230px; float: left;" id="CidadeOutraFonte" type="text" size="20" maxlength="50" />
<div style="clear:both; height:20px"></div>
</div>


<!--valor bruto -->
<label for="ValorBruto" style="text-align:right; margin-right:10px">Valor bruto cobrado pelo autônomo (R$)</label> <input name="ValorBruto" id="ValorBruto" type="text" size="30" maxlength="12" class="current" /> 
<div style="clear:both; height:5px"></div>


<!--botao calculo -->
<div style="margin-bottom:20px"><input name="btnCalculaRetencoes" id="btnCalculaRetencoes" type="button" value="Calcular retenções" onclick="javascript:calculaRetencoes();"/></div>
</div>

<div class="destaqueAzul" style="margin-bottom:20px">Retenções devidas:</div>

<div style="float:left; width:40px">INSS:</div> <input name="RetencaoINSS" id="RetencaoINSS" type="text" size="21" maxlength="50"  readonly="readonly" />
<div style="clear:both; height:5px"></div>
<div style="float:left; width:40px">IRRF:</div> <input name="RetencaoIR" id="RetencaoIR" type="text" size="21" maxlength="50"  readonly="readonly" />
<div style="clear:both; height:5px"></div>
<div style="float:left; width:40px">ISS:</div> <input name="RetencaoISS" id="RetencaoISS" type="text" size="21" maxlength="50"  readonly="readonly" />
<div style="clear:both; height:20px"></div>
<div style="margin-bottom:20px">Valor líquido a ser pago ao autônomo: <strong>R$</strong> <input name="ValorLiquido" id="ValorLiquido" type="text" size="21" maxlength="50" style="font-weight:bold" readonly="readonly" /></div>

<input name="btnGerarRPA" id="btnGerarRPA" type="button" value="Gerar RPA" style="position: relative; " />

<input name="btnLimparCampos" id="btnLimparCampos" type="button" value="Limpar" style="position: relative; margin-left: 10px; display: none; " />

</form>


<div id="aviso_declaracao" style="display:none; margin-top: 20px; background-color:#FFF; border-color:#a61d00; border-width:1px; border-style:solid; padding:20px;">
<strong>ATENÇÃO:</strong> você está recolhendo um valor menor de INSS pois o autônomo declarou já recolhê-lo, parcial ou integralmente, por meio de outra fonte pagadora. Para isso, a lei exige que você arquive junto com o RPA uma declaração assinada pela outra fonte pagadora, atestando o pagamento do imposto. Esta declaração, deve ser assinada também pelo autônomo. <a href="#" onclick="location.href='declaracao_download.php?aut=' + $('#NomeAutonomo').val()" id="geraDeclaracao">Imprima aqui</a> o modelo preenchido desta declaração e peça para o autônomo providenciar as assinaturas.
</div>


  <div class="tituloVermelho" style="margin-top:20px; margin-bottom:10px">Como recolher os impostos</div>
  <ul>
  <li>O <strong>INSS</strong> referente ao autônomo já estará automaticamente incluído na <strong>Gfip</strong> que sua empresa deve pagar todo mês.</li>
  <li>O recolhimento do <strong>IRRF</strong>, se houver você deverá emitir uma guia do <strong>DARF</strong> e pagar no banco. Siga <a href="tutorial_darf.php">este tutorial</a>, para emissão da guia</li>
  </ul>


<!-- ************************************--BALLOM ISS **********************************-->
<div style="width: 310px; position: absolute; top: 553px; margin-left: 515px; display: none; z-index: 3" id="balloon_iss">
<div style="width:8px; position:absolute; margin-left:280px; margin-top:12px"><a href="javascript:fechaDiv('balloon_iss')"><img src="images/x.png" width="8" height="9" border="0" /></a></div>
  <table cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td colspan="3"><img src="images/balloon_topo.png" width="310" height="19" /></td>
    </tr>
    <tr>
      <td background="images/balloon_fundo_esq.png" valign="top" width="18"><img src="images/ballon_ponta.png" width="18" height="58" /></td>
      <td width="285" bgcolor="#ffff99" valign="top"><div style="width:245px; margin-left:20px; font-size:12px">
      <strong>Alíquota ISS</strong> - refere-se ao percentual que o autônomo deve pagar de <strong>ISS</strong>. A taxa varia de acordo com o município e a atividade desenvolvida. Pergunte a seu prestador qual a alíquota dele e se há algum tipo de isenção, ou consulte a tabela de ISS do seu município. Na dúvida, você pode deixar 5%, que é o teto permitido por lei federal. Dessa forma você estará seguro.  
      
      </div></td>
      <td background="images/balloon_fundo_dir.png" width="7"></td>
    </tr>
    <tr>
      <td colspan="3"><img src="images/balloon_base.png" width="310" height="27" /></td>
    </tr>
  </table>
</div>
<!-- ***********************************FIM DO BALLOOM ISS***************************** -->



<!-- ************************************--BALLOM CBO **********************************-->
<div style="width:310px; position:absolute; top:190px; margin-left:475px; display:none; z-index:3" id="balloon_cbo">
<div style="width:8px; position:absolute; margin-left:280px; margin-top:12px"><a href="javascript:fechaDiv('balloon_cbo')"><img src="images/x.png" width="8" height="9" border="0" /></a></div>
  <table cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td colspan="3"><img src="images/balloon_topo.png" width="310" height="19" /></td>
    </tr>
    <tr>
      <td background="images/balloon_fundo_esq.png" valign="top" width="18"><img src="images/ballon_ponta.png" width="18" height="58" /></td>
      <td width="285" bgcolor="#ffff99" valign="top"><div style="width:245px; margin-left:20px; font-size:12px">
      <strong>CBO</strong> - significa Código Brasileiro de Ocupações. Informe o código da atividade desenvolvida pelo autônomo. Para saber o número, consulte <a href="http://www.mtecbo.gov.br/cbosite/pages/home.jsf" target="_blank">este site</a>. </div></td>
      <td background="images/balloon_fundo_dir.png" width="7"></td>
    </tr>
    <tr>
      <td colspan="3"><img src="images/balloon_base.png" width="310" height="27" /></td>
    </tr>
  </table>
</div>
<!-- ***********************************FIM DO BALLOOM CBO***************************** -->


<!--layer para cadastramento de novo autonomo (deve estar relacionado como a cadastro de autonomo na seção meus dados -->
<script>
		
$(document).ready(function(e) {
    $('#btCadastroNovo').click(function(){
		if($('#cpf').val()!='' && $('#pis').val() != ''){
			$.ajax({
				url:'meus_dados_autonomos_checa.php?idLogin=' + $('#id_login').val() + '&cpf=' + $('#cpf').val() + '&pis=' + $('#pis').val(),
				type: 'get',
				cache: false,
				async: true,
				success: function(retorno){
					if(retorno == 1){
						alert('Já existe um autônomo cadastrado com esses dados!');  
						return false;
					}
				}
			});
		}

		if( $('#nome').val() == ''){					alert('Preencha o campo ' + $('#nome').attr('alt')); return false}
		if( $('#CBO').val() == ''){						alert('Preencha o campo ' + $('#CBO').attr('alt')); return false}
		if( $('#cpf').val() == ''){						alert('Preencha o campo ' + $('#cpf').attr('alt')); return false}
		if( $('#rg').val() == ''){						alert('Preencha o campo ' + $('#rg').attr('alt')); return false}
		if( $('#orgao_emissor').val() == ''){			alert('Preencha o campo ' + $('#orgao_emissor').attr('alt')); return false}
		if( $('#pis').val() == ''){						alert('Preencha o campo ' + $('#pis').attr('alt')); return false}
		if( $('#NumeroDep').val() == ''){				alert('Preencha o campo ' + $('#NumeroDep').attr('alt')); return false}
		if( $('#endereco').val() == ''){				alert('Preencha o campo ' + $('#endereco').attr('alt')); return false}
		if( $('#cep').val() == ''){						alert('Preencha o campo ' + $('#cep').attr('alt')); return false}
		if( $('#cidade').val() == ''){					alert('Preencha o campo ' + $('#cidade').attr('alt')); return false}
		if( $('#estado').val() == ''){					alert('Preencha o campo ' + $('#estado').attr('alt')); return false}
		if( $('#tipo_servico').val() == ''){			alert('Preencha o campo ' + $('#tipo_servico').attr('alt')); return false}
		if( $('#AliquotaISS').val() == ''){				alert('Preencha o campo ' + $('#AliquotaISS').attr('alt')); return false}

		var arrData = $('#novoAutonomo').serialize();
		$.ajax({
			url: 'dados_autonomo_gravar.php',
			data: arrData,
			type: 'POST',
			cache:false,
			beforeSend: function(){
				$('body').css('cursor','wait');
			},
			success: function(result){
				$('body').css('cursor','default');
				if(result != ""){
					alert('Autônomo cadastrado');
					$('#novo_autonomo').css('display','none');
					$('#NomeAutonomo').html(result);
					var arrForm = $('#novoAutonomo').serializeArray();
					$.each(arrForm, function(i, objCampo){
						switch($("#novoAutonomo :input[name="+this.name+"]").attr('type')){
							case 'text':
								$("#novoAutonomo :input[name="+this.name+"]").attr('value','');
							break;
							case 'select-one':
								$('#'+this.name).attr('value','');
							break;
							case 'checkbox':
								$("#novoAutonomo :input[name="+this.name+"]").attr('checked','');
							break;
							case 'radio':
								$("#novoAutonomo :input[name="+this.name+"]").attr('checked','');
							break;
						}
					});
				}else{
					alert('Erro no cadastramento do autônomo');
				}
				//$('#testaResultado').html(result).show();
			}
		});
	});
})
</script>

<div id="novo_autonomo" class="layer_branco" style="width:450px; border:#ccc solid 1px;  position:absolute; left:50%; top:130px; margin-left:-200px; display:none">
<div style="text-align:right; margin-right:20px; margin-top:15px"><a href="javascript:fechaDiv('novo_autonomo')"><img src="images/x.png" width="8" height="9" border="0" /></a></div>
<div style="margin-bottom:20px; padding:20px; padding-top:0px">
<div class="tituloVermelho" style="margin-bottom:20px">Cadastro de Autônomos</div>
<form name="novoAutonomo" id="novoAutonomo" method="post" style="display:inline">
<input type="hidden" name="id_login" value="<?=$_SESSION["id_empresaSecao"]?>" />
    <div style="height:25px;">
    <div style="float:left; width:60px; text-align:right; margin-right:3px; padding-top:3px;"><label for="nome">Nome:</label></div><div style="float:left;"><input name="nome" id="nome" type="text" size="50" maxlength="50" alt="Nome" /></div>
    </div>

    <div style="height:25px;">
    <div style="float:left; width:60px; text-align:right; margin-right:3px; padding-top:3px"><label for="nome">CBO:</label></div><div style="float:left;"><input name="CBO" id="CBO" type="text" size="8" maxlength="10" alt="CBO" /> <a href="javascript:abreDiv('balloon_cbo')"><img src="images/dica.gif" width="13" height="14" border="0" align="texttop" /></a></div>
    <div style="clear:both;"></div></div>

    <div style="height:25px;">
    <div style="float:left; width:60px; text-align:right; margin-right:3px; padding-top:3px"><label for="cpf">CPF:</label></div><div style="float:left;"><input name="cpf" id="cpf" type="text" size="14" maxlength="14" alt="CPF" class="cpf" /></div>
    <div style="clear:both;"></div></div>
    
    <div style="height:25px;">
    <div style="float:left; width:60px; text-align:right; margin-right:3px; padding-top:3px"><label for="rg">RG:</label></div><div style="float:left;"><input name="rg" id="rg" type="text" size="14" maxlength="12" alt="RG" class="rg" /> <label for="orgao_emissor">&nbsp;&nbsp;Órgão Emissor:</label> <input name="orgao_emissor" id="orgao_emissor" type="text" size="14" maxlength="25" alt="Órgão Emissor" /></div>
    <div style="clear:both;"></div></div>

    <div style="height:25px;">
    <div style="float:left; width:60px; text-align:right; margin-right:3px; padding-top:3px"><label for="pis">PIS:</label></div><div style="float:left;"><input name="pis" id="pis" type="text" size="14" maxlength="14" alt="PIS" class="pis" /></div>
    <div style="clear:both;"></div></div>

    <div style="height:25px;">    
    <div style="float:left; width:110px; text-align:right; margin-right:3px; padding-top:3px"><label for="NumeroDep">Nº de dependentes:</label></div><div style="float:left;"><input name="NumeroDep" id="NumeroDep" type="text" size=2 maxlength="2" value="0" alt="Nº de Dependentes" /> <span style="font-size:10px">(Somente os declarados como dependentes no IR)</span></div>
    <div style="clear:both;"></div></div>

    <div style="height:25px;"> 
    <div style="float:left; width:60px; text-align:right; margin-right:3px; padding-top:3px"><label for="endereco">Endereço:</label></div><div style="float:left;"><input name="endereco" id="endereco" type="text" size="50" maxlength="50" alt="Endereço" /></div>
    <div style="clear:both;"></div>
    </div>
    
    <div style="height:25px;"> 
    <div style="float:left; width:60px; text-align:right; margin-right:3px; padding-top:3px"><label for="cep">Cep:</label></div><div style="float:left;"><input name="cep" id="cep" type="text" maxlength="9" alt="CEP" class="cep" /></div>
    <div style="clear:both;"></div>
    </div>
    
    <div style="height:25px;"> 
    <div style="float:left; width:60px; text-align:right; margin-right:3px; padding-top:3px"><label for="cidade">Cidade:</label></div><div style="float:left;"><input name="cidade" id="cidade" type="text" maxlength="50" alt="Cidade" /></div>
    <div style="clear:both;"></div>
    </div>
    
    <div style="height:40px;">
    <div style="float:left; width:60px; text-align:right; margin-right:3px; padding-top:4px"><label for="estado">Estado:</label></div>
    <div style="float:left;">
    <select name="estado" id="estado" alt="Estado">
          <option value="AC">AC</option>
          <option value="AL">AL</option>
          <option value="AM">AM</option>
          <option value="AP">AP</option>
          <option value="BA">BA</option>
          <option value="CE">CE</option>
          <option value="DF">DF</option>
          <option value="ES">ES</option>
          <option value="GO">GO</option>
          <option value="MA">MA</option>
          <option value="MG">MG</option>
          <option value="MS">MS</option>
          <option value="MT">MT</option>
          <option value="PA">PA</option>
          <option value="PB">PB</option>
          <option value="PE">PE</option>
          <option value="PI">PI</option>
          <option value="PR">PR</option>
          <option value="RJ">RJ</option>
          <option value="RN">RN</option>
          <option value="RO">RO</option>
          <option value="RR">RR</option>
          <option value="RS">RS</option>
          <option value="SC">SC</option>
          <option value="SE">SE</option>
          <option value="SP" selected>SP</option>
          <option value="TO">TO</option>
        </select></div>
    <div style="clear:both;"></div>
    </div>
    
     <div style="height:50px;">
     <label for="tipo_servico">Tipo de Serviço a ser desenvolvido:</label><br />
	 <input name="tipo_servico" id="tipo_servico" type="text" size="60" maxlength="250" alt="Tipo de Serviço" />
    </div>
    
     <div style="height:50px;">
    <div style="float:left"><label for="pensao">Autônomo paga pensão alimentícia?</label> <input name="pensao" id="pensao" type="checkbox" value="1" />&nbsp;<label for="PercentPensao">Qual o percentual?</label> <input name="PercentPensao" id="PercentPensao" type="text" size=4 maxlength="50" />%</div>
    <div style="clear:both;"></div>
    <div style="font-size:10px">(Considerar apenas a pensão alimentícia quando definida em acordo judicial)</div>
    </div>
    
    <div style="height:25px;">
    <div style="float:left; width:113px; margin-right:3px; padding-top:3px"><label for="inscricao_municipal">Inscrição Municipal:</label></div><div style="float:left"><input name="inscricao_municipal" id="inscricao_municipal" type="text" size="12" maxlength="50" va;lue="não tem" alt="Inscrição Municipal" /></div>
    <div style="clear:both;"></div></div>
    
    <div style="float:left; width:113px; text-align:right; margin-right:3px; padding-top:3px"><label for="estado">Alíquota ISS:</label></div><input name="AliquotaISS" id="AliquotaISS" type="text" size="6" maxlength="6" value="5%" alt="Alíquota ISS" /> <a href="javascript:abreDiv('balloon_iss')"><img src="images/dica.gif" width="13" height="14" border="0" align="texttop" /></a>
    <div style="clear:both; height:15px"></div>
    
   <input name="btCadastroNovo" id="btCadastroNovo" type="button" value="Cadastrar" />
</form>

</div>
</div>
<!--fim do layer para cadastro de autônomo -->



<!--O profissional autônomo nessa situação deve declarar, no verso do recibo de pagamento: "Profissional autônomo não estabelecido, estando isento do ISS e dispensado de inscrição municipal, conforme art. Inciso XIX do artigo 12 da Lei nº 691/84 com as alterações da Lei 3.691/03 e § 2º do art. 153 do Decreto 10.514, de 08 de outubro de 1991". -->
</div>

<?php include 'rodape.php' ?>
