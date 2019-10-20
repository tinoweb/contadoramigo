<?php include 'header_restrita.php' ?>

<div class="principal">

	<div id="divPerguntas" style="width: 790px;">
    <div class="titulo" style="margin-bottom:20px; text-align:left">Substituição Tributária</div>
    <div class="tituloVermelho" style="margin-bottom:10px">Cálculo do ICMS ST</div>
    <div style="margin-bottom:20px">Algumas mercadorias, <strong>quando vendidas para outros estados</strong>, estão sujeitas à <strong>Substituição Tributária</strong>, isto é, você fica responsável pelo pagamento do ICMS devido pela pessoa (ou empresa) que comprou sua mercadoria. 
    Para saber se um determinado produto está sujeito à Substituição Tributária e qual o valor a pagar de ICMS, preencha o formulário abaixo. O Contador Amigo retornará com a resposta pelo Help Desk. <br />
    <br />
    A mercadoria está sendo enviada para o mesmo estado?
    <div style="clear:both; height:5px;"></div>
    <input type="radio" name="pergunta01" value="1" style="margin:0 3px;" /> Sim&nbsp;&nbsp;&nbsp;<input type="radio" name="pergunta01" value="0" style="margin:0 3px;" /> Não<br />
    <div style="clear:both; height:25px;"></div>
    Você é uma indústria ou importador e a mercadoria está sendo enviada a outra empresa que também deverá fazer substituição tributária (sem alterar a marca da mesma)?
    <div style="clear:both; height:5px;"></div>
    <input type="radio" name="pergunta02" value="1" style="margin:0 3px;" /> Sim&nbsp;&nbsp;&nbsp;<input type="radio" name="pergunta02" value="0" style="margin:0 3px;" /> Não<br />
    <div style="clear:both; height:25px;"></div>
    O local de destino  é de propriedade da mesma empresa não faz venda ao varejo?
    <div style="clear:both; height:5px;"></div>
    <input type="radio" name="pergunta03" value="1" style="margin:0 3px;" /> Sim&nbsp;&nbsp;&nbsp;<input type="radio" name="pergunta03" value="0" style="margin:0 3px;" /> Não<br />
    <div style="clear:both; height:25px;"></div>
    A mercadoria será destinada à novo processo de industrialização?
    <div style="clear:both; height:5px;"></div>
    <input type="radio" name="pergunta04" value="1" style="margin:0 3px;" /> Sim&nbsp;&nbsp;&nbsp;<input type="radio" name="pergunta04" value="0" style="margin:0 3px;" /> Não<br />
    <div style="clear:both; height:25px;"></div>
    A empresa é indústria e está vendendo o produto diretamente ao consumidor final?
    <div style="clear:both; height:5px;"></div>
    <input type="radio" name="pergunta05" value="1" style="margin:0 3px;" /> Sim&nbsp;&nbsp;&nbsp;<input type="radio" name="pergunta05" value="0" style="margin:0 3px;" /> Não<br />
    <div style="clear:both; height:25px;"></div>
    </div>
  
    <div style="text-align:center;margin-bottom:20px"><input type="button" value="Continuar" style="display:inline;" id="btContinuar" /></div>  
	</div>
    
  <div id="divFormulario" style="display:none; width:790px;">
    <form name="frmSubstituicao" id="frmSubstituicao" method="post" action="substituicao_tributaria_gravar.php">
      <div style="float:left; text-align:right; margin-right:10px; width:170px">Código NCM do produto</div>
      <div style="float:left"><input name="txtNCM" type="text" value="" /> Não sabe o código? Consulte-o <a href="http://www.brasilglobalnet.gov.br/classificacaoncm/pesquisa/frmpesqncm.aspx" target="_blank">aqui</a>
      </div>
      <div style="clear:both; height:5px"></div>
      
      <div style="float:left; text-align:right; margin-right:10px; width:170px">Mercadoria Importada?</div>
      <div style="float:left">
        <input type="radio" value="1" name="rdbImportado" /> <label for="label3">Sim</label>&nbsp;&nbsp;
        <input type="radio" value="0" name="rdbImportado" /> <label for="label3">Não</label>&nbsp;&nbsp;<a href="javascript:abreDiv('importadas')">informações importantes a respeito de mercadorias importadas</a>
      </div>
      <div style="clear:both; height:5px"></div>
      
      <div style="float:left; text-align:right; margin-right:10px; width:170px">Estado de origem</div>
      <div style="float:left">
      <select name="selUFOrigem" id="selUFOrigem">
        <option value="">Selecione</option>
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
        <option value="SP">SP</option>
        <option value="TO">TO</option>
      </select>
      </div>
      <div style="clear:both; height:5px"></div>
      
      <div style="float:left; text-align:right; margin-right:10px; width:170px">Estado de destino</div>
      <div style="float:left">
      <select name="selUFDestino" id="selUFDestino">
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
        <option value="SP">SP</option>
        <option value="TO">TO</option>
      </select></div>
      <div style="clear:both; height:5px"></div>
      
      <div style="margin-bottom:5px">
        <div style="float:left; text-align:right; margin-right:10px; width:170px">Estabelecimento de origem é:</div>
        <input type="radio" value="Atacado" name="rdbTipoEmpresaOrigem" alt="Atacado"/> <label for="label">Atacado</label>&nbsp;&nbsp;&nbsp;
        <input type="radio" value="Indústria" name="rdbTipoEmpresaOrigem" alt="Indústria"/> <label for="label">Indústria</label>&nbsp;&nbsp;&nbsp;
        <input type="radio" value="Varejo" name="rdbTipoEmpresaOrigem" alt="Varejo" /> <label for="label">Varejo</label>
      </div>
      
      <div style="margin-bottom:20px">
        <div style="float:left; text-align:right; margin-right:10px; width:170px">Estabelecimento de destino é:</div>
        <input type="radio" value="Atacado" name="rdbTipoEmpresaDestino" alt="Atacado"/> <label for="label2">Atacado</label>&nbsp;&nbsp;&nbsp;
        <input type="radio" value="Indústria" name="rdbTipoEmpresaDestino" alt="Indústria"/> <label for="label2">Indústria</label>&nbsp;&nbsp;&nbsp;
        <input type="radio" value="Varejo" name="rdbTipoEmpresaDestino" alt="Varejo"/> <label for="label2">Varejo</label>&nbsp;&nbsp;&nbsp;
        <input type="radio" value="Consumidor Final" name="rdbTipoEmpresaDestino" alt="Consumidor Final"/> <label for="label2">Consumidor Final</label>
      </div>
      
      <div>
        <div style="float:left; text-align:right; margin-right:10px; width:170px"><label for="txtValorTotalMercadorias">Total das mercadorias:</label></div>
        <div style="float:left"><input type="text" id="txtValorTotalMercadorias" name="txtValorTotalMercadorias" placeholder="R$ 0,00" class="current" maxlength="14" /></div>
        <div style="clear:both; height:5px"></div>
        
        <div style="float:left; text-align:right; margin-right:10px; width:170px"><label for="txtValorOutrasDespesas">Outras despesas:</label></div>
        <div style="float:left"><input type="text" id="txtValorOutrasDespesas" name="txtValorOutrasDespesas" placeholder="R$ 0,00" class="current" maxlength="14" /></div>
        <div style="clear:both; height:5px"></div>
        
        <div style="float:left; text-align:right; margin-right:10px; width:170px"><label for="txtValorFrete">Frete:</label></div>
        <div style="float:left"><input type="text" id="txtValorFrete" name="txtValorFrete" placeholder="R$ 0,00" class="current" maxlength="14" /></div>
        <div style="clear:both; height:5px"></div>
        
        <div style="float:left; text-align:right; margin-right:10px; width:170px"><label for="txtValorIPI">IPI:</label></div>
        <div style="float:left"><input type="text" id="txtValorIPI" name="txtValorIPI" placeholder="R$ 0,00" class="current" maxlength="14" /></div>
        <div style="clear:both; height:5px"></div>
        
        <div style="float:left; text-align:right; margin-right:10px; width:170px"><label for="txtValorSeguro">Seguro:</label></div>
        <div style="float:left"><input type="text" id="txtValorSeguro" name="txtValorSeguro" placeholder="R$ 0,00" class="current" maxlength="14" /></div>
        <div style="clear:both; height:5px"></div>
        
        <div style="float:left; text-align:right; margin-right:10px; width:170px"><label for="txtValorDesconto">Desconto:</label></div>
        <div style="float:left"><input type="text" id="txtValorDesconto" name="txtValorDesconto" placeholder="R$ 0,00" class="current" maxlength="14" /></div>
        <div style="clear:both; height:5px"></div>
        
        <div style="text-align:center"><input type="submit" value="Enviar consulta" style="display:inline" /></div>
      </div>
		</form>
	</div>


  <div id="divMensagem" style="display:none; width:790px;">
    <div style="background-color:#FFFFFF; border-color:#CCC; border-style:solid; border-width:1px; padding:10px; margin-bottom:30px">
      <span class="destaque">ATENÇÃO:</span><br /><br />
      Com base nas respostas fornecidas, verificamos que sua mercadoria não está sujeita a substituição tributária.<br />
			Não é necessário prosseguir com a consulta.
      <br />
		</div>
	</div>

</div>

<script>
$(document).ready(function(e) {
	
	$('#frmSubstituicao').bind('submit',function(e){

//		alert($('input[name=rdbImportado]:checked').val());
		if($('input[name=txtNCM]').val() == ''){
			alert('Preencha o campo Código NCM do produto');
			$('input[name=txtNCM]').focus();
			return false;
		}
		if($('input[name=rdbImportado]:checked').val() == undefined){
			alert('Preencha o campo Mercadoria Importada');
			$('input[name=rdbImportado]').focus();
			return false;
		}
		if($('select[name=selUFOrigem]').val() == ''){
			alert('Preencha o campo Estado de Origem');
			$('select[name=selUFOrigem]').focus();
			return false;
		}
		if($('select[name=selUFDestino]').val() == ''){
			alert('Preencha o campo Estado de destino');
			$('select[name=selUFDestino]').focus();
			return false;
		}
		if($('input[name=rdbTipoEmpresaOrigem]:checked').val() == undefined){
			alert('Preencha o campo tipo do estabelecimento de origem');
			$('input[name=rdbTipoEmpresaOrigem]').focus();
			return false;
		}
		if($('input[name=rdbTipoEmpresaDestino]:checked').val() == undefined){
			alert('Preencha o campo tipo do estabelecimento de destino');
			$('input[name=rdbTipoEmpresaDestino]').focus();
			return false;
		}



		if($('input[name=txtValorTotalMercadorias]').val() == ''){
			alert('Preencha o valor total das mercadorias');
			$('input[name=txtValorTotalMercadorias]').focus();
			return false;
		}
		if($('input[name=txtValorOutrasDespesas]').val() == ''){
			alert('Preencha o valor de outras despesas');
			$('input[name=txtValorOutrasDespesas]').focus();
			return false;
		}
		if($('input[name=txtValorFrete]').val() == ''){
			alert('Preencha o valor do frete');
			$('input[name=txtValorFrete]').focus();
			return false;
		}
		if($('input[name=txtValorIPI]').val() == ''){
			alert('Preencha o valor do IPI');
			$('input[name=txtValorIPI]').focus();
			return false;
		}
		if($('input[name=txtValorSeguro]').val() == ''){
			alert('Preencha o valor do seguro');
			$('input[name=txtValorSeguro]').focus();
			return false;
		}
		if($('input[name=txtValorDesconto]').val() == ''){
			alert('Preencha o valor do desconto');
			$('input[name=txtValorDesconto]').focus();
			return false;
		}

		return true;
		
	});
	
  $('#btContinuar').bind('click',function(){
		var mostra_formulario = true;
		var mostra_alerta = false;
		
		var total_perguntas = ($("input[name^=pergunta]").size() / 2);
		
		for(var i = 1; i <= total_perguntas; i++){

			switch($("input[name=pergunta0"+i+"]:checked").val()){
				case '1':
					mostra_formulario = false;
					i = total_perguntas;
				break;
				case undefined:
					mostra_formulario = false;
					mostra_alerta = true;
					i = total_perguntas;
				break;
			}
		}

		if(mostra_formulario == true){
			$('#divFormulario').css('display','block');
			$('#divPerguntas').css('display','none');
			$('#divMensagem').css('display','none');
		}else if(mostra_alerta == true){
			$('#divMensagem').css('display','none');
			$('#divFormulario').css('display','none');		
			$('#divPerguntas').css('display','block');
			alert('Responda as questões');
		}else{
			$('#divMensagem').css('display','block');
			$('#divFormulario').css('display','none');		
		}
		
	});	
});

<?
if($_SESSION['mensagem_substituicao_enviada']){
	echo "alert('".$_SESSION['mensagem_substituicao_enviada']."')";
	$_SESSION['mensagem_substituicao_enviada'] = "";
}
?>

</script>

<?php include 'rodape.php' ?>
