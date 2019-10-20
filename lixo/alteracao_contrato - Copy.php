<?php include 'header_restrita.php' ?>
<script>

function checaNomes() {
	
	var count = parseInt(document.getElementById('count').value) - 1;

	for (i=1; i<=count; i++){
		var nome = document.getElementById("txtNome" + i).value;
		if (nome != "") {	
			var orig = document.getElementById('cotas');
			var newDiv = document.createElement('div');
			newDiv.setAttribute("id", "linha"+i);
			var newContent = nome + " <input type='text' id='txtCota" + i + "' value='cotas' style='margin-bottom:3px'>";
			newDiv.innerHTML = newContent;
			orig.appendChild(newDiv);
		}
	}
}

function add() {
	
//		var orig = document.getElementById('form_socio');
		var orig = $("#form_socio");
//		alert(orig.html());
//		var count = parseInt(document.getElementById('count').value)+1;
		var count = parseInt($("#count").val()) + 1;
		var newDiv = document.createElement('div');
		
		newDiv.setAttribute("id", "socio"+count);
		var newContent = '<table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none"><tr><td align="right" valign="middle" class="formTabela">Nome:</td><td class="formTabela" width="300"><input name="txtNome'+ count +'" type="text" id="txtNome'+ count +'" style="width:300px" value="" maxlength="200" alt="Nome do novo sócio" onblur="javascript:mudaNomeSocio('+ count +')" onkeyup="javascript:mudaNomeSocio('+ count +')" /></td></tr><tr><td align="right" valign="middle" class="formTabela">Nacionalidade:</td><td class="formTabela"><input name="txtNacionalidade'+ count +'" type="text" id="txtNacionalidade'+ count +'" style="width:300px" value="" maxlength="200" alt="Nacionalidade do novo sócio" /></td></tr><tr><td align="right" valign="middle" class="formTabela">Sexo:</td><td class="formTabela"><label style="margin-right:15px"><input type="radio" name="radSexoSocio'+ count +'" id="radSexo1Socio'+ count +'" value="Masculino" /> Masculino</label><label><input type="radio" name="radSexoSocio'+ count +'" id="radSexo2Socio'+ count +'" value="Feminino" /> Feminino</label></td></tr> <tr>    <td align="right" valign="middle" class="formTabela">Estado Civil:</td>    <td class="formTabela"><input name="txtEstadoCivil'+ count +'" type="text" id="txtEstadoCivil'+ count +'" style="width:300px" value="" maxlength="200" alt="Estado Civil do novo sócio"/></td>  </tr><tr>    <td align="right" valign="middle" class="formTabela">Profissão:</td>    <td class="formTabela"><input name="txtProfissao'+ count +'" type="text" id="txtProfissao'+ count +'" style="width:300px" value="" maxlength="200" alt="Profissão do novo sócio"/></td>  </tr>  <tr><td align="right" valign="middle" class="formTabela">CPF</td><td class="formTabela"><input name="txtCPF'+ count +'" type="text" id="txtCPF'+ count +'" style="width:125px" value="" maxlength="14" alt="CPF do novo sócio" class="cpf" /><span style="font-size:10px">Ex.: 999.999.999-99</span></td></tr><tr><td align="right" valign="middle" class="formTabela">RG:</td><td class="formTabela"><input name="txtRG'+ count +'" type="text" id="txtRG'+ count +'" style="width:300px" value="" maxlength="17" alt="RG do novo sócio" /></td></tr><tr><td align="right" valign="middle" class="formTabela">Orgão Expeditor:</td><td class="formTabela"><input name="txtOrgaoExpedidor'+ count +'" type="text" id="txtOrgaoExpedidor'+ count +'" style="width:300px" value="" maxlength="250" alt="Orgão Expeditor do novo sócio" /></td></tr><tr><td align="right" valign="middle" class="formTabela">Endere&ccedil;o:</td><td class="formTabela"><input name="txtEnderecoSocio'+ count +'" id="txtEnderecoSocio'+ count +'" type="text" style="width:300px" value="" maxlength="250" alt="Endereço do novo sócio" /></td></tr><tr><td align="right" valign="middle" class="formTabela">Bairro:</td><td class="formTabela"><input name="txtBairroSocio'+ count +'" id="txtBairroSocio'+ count +'" type="text" style="width:300px" value="" maxlength="200" alt="Complemento" /></td></tr><tr><td align="right" valign="middle" class="formTabela">CEP:</td><td class="formTabela"><input name="txtCEPSocio'+ count +'" type="text" id="txtCEPSocio'+ count +'" style="width:70px" value="" maxlength="9" alt="CEP do novo sócio" class="cep" /><span style="font-size:10px">Ex.: 99999-999</span></td></tr><tr><td align="right" valign="middle" class="formTabela">Cidade:</td><td class="formTabela"><input name="txtCidadeSocio'+ count +'" type="text" id="txtCidadeSocio'+ count +'" style="width:300px" value="" maxlength="200" /></td></tr><tr>    <td align="right" valign="middle" class="formTabela">Estado:</td>    <td class="formTabela"><select name="selEstadoSocio'+ count +'" id="selEstadoSocio'+ count +'">      <option value="AC">AC</option>      <option value="AL">AL</option>      <option value="AM">AM</option>      <option value="AP">AP</option>      <option value="BA">BA</option>      <option value="CE">CE</option>      <option value="DF">DF</option>      <option value="ES">ES</option>      <option value="GO">GO</option>      <option value="MA">MA</option>      <option value="MG">MG</option>      <option value="MS">MS</option>      <option value="MT">MT</option>      <option value="PA">PA</option>      <option value="PB">PB</option>      <option value="PE">PE</option>      <option value="PI">PI</option>      <option value="PR">PR</option>      <option value="RJ">RJ</option>      <option value="RN">RN</option>      <option value="RO">RO</option>      <option value="RR">RR</option>      <option value="RS">RS</option>      <option value="SC">SC</option>      <option value="SE">SE</option>      <option value="SP" selected="selected">SP</option>      <option value="TO">TO</option>    </select></td>  </tr></table>';
		newDiv.innerHTML = newContent;
		orig.append(newDiv);
		
		//Insirir socios na "Alteração na distribuição de cotas" - Tranferência
		var totalSociosAtuais = document.getElementById('hidTotalSocioExistente').value;
		for (i=1; i<=totalSociosAtuais; i++){
			var orig = document.getElementById('transfereParaNovosSocios'+i);
			var newDiv = document.createElement('div');
			newDiv.setAttribute("id", "Socio"+i+"novoSocio"+count);
			var newContent = '<input name="txtSocio'+i+'TransfereCotaParaNovo'+count+'" id="txtSocio'+i+'TransfereCotaParaNovo'+count+'" type="text" style="width:60px; float:left"><div id="Socio'+i+'nomeSocio'+count+'" style="float:left; margin-top:3px; margin-left:3px">quotas para</div><div style="clear:both"></div>';
			newDiv.style.marginBottom = '3px';
			newDiv.innerHTML = newContent;
			orig.appendChild(newDiv);
		}

		//Insirir socios na "Alteração na distribuição de cotas" - Distribuição
		var orig = document.getElementById('distribuicaoParaNovoSocio');
		var newDiv = document.createElement('div');
		newDiv.setAttribute("id", "divDistribuicaoFuturaNovoSocio"+count);
		var newContent = '<input name="txtDistribuicaoFuturaNovoSocio'+count+'" id="txtDistribuicaoFuturaNovoSocio'+count+'" type="text" style="width:60px; float:left"><div id="distribuicaoNomeSocio'+count+'" style="float:left; margin-top:3px; margin-left:3px">quotas para</div><div style="clear:both"></div>';
		newDiv.style.marginBottom = '3px';
		newDiv.innerHTML = newContent;
		orig.appendChild(newDiv);
		
		
		//Insirir socios na "Administração da sociedade"
		var orig = document.getElementById('AdministracaoParaNovoSocio');
		var newDiv = document.createElement('div');
		newDiv.setAttribute("id", "divAdministracaoNovoSocio"+count);
		var newContent = '<input type="checkbox" style="float:left" id="cheAdministracaoNovoSocio'+count+'" name="cheAdministracaoNovoSocio'+count+'"><div style="float:left; margin-left:3px" id="AdministracaoNomeSocio'+count+'">&nbsp;</div><div style="clear:both"></div>';
		newDiv.innerHTML = newContent;
		orig.appendChild(newDiv);
		
		document.getElementById('count').value = count;
	}	
	
	
	function remove() {
		var count = parseInt(document.getElementById('count').value);
		if (count <= 1) {alert('É preciso cadastrar pelo menos um sócio!')}
		else {				
//		alert(count);
			var orig = document.getElementById('form_socio');
			var removeDiv = document.getElementById("socio"+(count));
			orig.removeChild(removeDiv);	

			document.getElementById('count').value = count-1;
			
			//Remover socio na "Alteração na distribuição de cotas"  - Tranferência
			var totalSociosAtuais = document.getElementById('hidTotalSocioExistente').value;
				for (i=1; i<=totalSociosAtuais; i++){
					var orig = document.getElementById('transfereParaNovosSocios'+i);
					var removeDiv = document.getElementById("Socio"+i+"novoSocio"+count);
					orig.removeChild(removeDiv);
				}
				
			//Remover socio na "Alteração na distribuição de cotas"  - Distribuição
			var orig = document.getElementById('distribuicaoParaNovoSocio');
			var removeDiv = document.getElementById("divDistribuicaoFuturaNovoSocio"+count);
			orig.removeChild(removeDiv);
			
			//Remover socio na "Administração da sociedade"
			var orig = document.getElementById('AdministracaoParaNovoSocio');
			var removeDiv = document.getElementById("divAdministracaoNovoSocio"+count);
			orig.removeChild(removeDiv);
			
		}
	}
	
	$('input[id^="txtCodigoCNAE"]').bind('change',function(){
		var 
			$campo 	= $(this)
			, $div		= $campo.attr('div')
			, $campoCheck		= $('#' + $campo.attr('check'))
			, $idEmpresa = <?=$_SESSION["id_userSecao"]?>;			
		;
		if($campo.val() != ''){
			var URL = 'meus_dados_empresa_consulta_cnae.php?acao=blur&codigo=' + $campo.val() + '&campoCheck=' + $campoCheck.val() + '&idEmpresa=' + $idEmpresa
			$.consultaBancoAjax(URL, $('#'+$div), $campo);
		}
		
	});
	
 
 $('.btAdicionaCNAE').bind('click',function(e){
	 e.preventDefault();
	 //reposicionaBallons();

		var orig = $('#content');
		var count = parseInt($('#countCNAEIncluso').val());
		
		$('#countCNAEIncluso').val(count+1);

		
		orig.append('<div id="item'+count+'"><div style="float:left; margin-right:5px"><input name="txtCodigoCNAE'+count+'" id="txtCodigoCNAE'+count+'" type="text" style="width:84px; margin-top:3px;" class="campoCNAE" div="atividade'+count+'" check="hddCodigoCNAE'+count+'"><input type="hidden" name="hddCodigoCNAE'+count+'" id="hddCodigoCNAE'+count+'" value=""></div> <div id="atividade'+count+'" style="float:left; margin-top:5px"></div><div style="clear:both"></div></div>');
		
		$('.campoCNAE').mask('9999-9/99');
		
	
		$('input[id^="txtCodigoCNAE"]').bind('change',function(){
//			alert('1');
			var 
				$campo 	= $(this)
				, $div		= $campo.attr("div")
				, $campoCheck		= $('#' + $campo.attr('check'))
				, $idEmp = <?=$_SESSION["id_userSecao"]?>;			
			;
			
//			alert($div);
//			alert($campo.attr('check'));
//			alert($idEmp);
			
			if($campo.val() != ''){
				var URL = 'meus_dados_empresa_consulta_cnae.php?acao=blur&codigo=' + $campo.val() + '&campoCheck=' + $campoCheck.val() + '&idEmpresa=' + $idEmp
				$.consultaBancoAjax(URL, $('#'+$div), $campo);
			}
			
		});
		
 });
 
 
	$('.btRemoveCNAE').bind('click',function(e){
	 e.preventDefault();
	 //reposicionaBallons();
		var count = parseInt($('#countCNAEIncluso').val());
		if (count > 1) {				
//			var orig = $('#content');
//			var removeDiv = document.getElementById('item'+(count-1));
			$('#item'+(count-1)).remove();	
			$('#countCNAEIncluso').val(count-1);
		}
	});
	
	
	
	function addCNAE() {
		var orig = document.getElementById('content');
		var count = parseInt(document.getElementById('countCNAEIncluso').value);
//		alert(count);
		var newDiv = document.createElement('div');
		newDiv.setAttribute("id", "item"+(count+1));
		var newContent = "<div style=\"float:left; margin-right:5px\"><input name=\"txtCodigoCNAE"+(count+1)+"\" id=\"txtCodigoCNAE"+(count+1)+"\" type=\"text\" style=\"width:75px; margin-top:3px;\" maxlength=\"15\" onblur=\"consultaBanco('meus_dados_empresa_consulta_cnae.php?codigo='+document.getElementById('txtCodigoCNAE"+(count+1)+"').value, 'atividade"+(count+1)+"');\" alt=\"Código CNAE\" /></div> <div id=\"atividade"+(count+1)+"\" style=\"float:left; margin-top:5px\"></div> <div style=\"clear:both\"> </div>";
		newDiv.innerHTML = newContent;
		orig.appendChild(newDiv);
		document.getElementById('countCNAEIncluso').value = count+1;
	}	
	
	function removeCNAE() {
		var count = parseInt(document.getElementById('countCNAEIncluso').value);
//		alert(count);
		if (count > 1) {
			var orig = document.getElementById('content');
			var removeDiv = document.getElementById('item'+(count));
			orig.removeChild(removeDiv);			
			document.getElementById('countCNAEIncluso').value = count - 1;
		}
	}
	
	function exibeNovosSocios() {
			var totalSociosAtuais = document.getElementById('hidTotalSocioExistente').value;
			for (i=1; i<=totalSociosAtuais; i++){
				abreDiv('transfereParaNovosSocios'+i);
			}
			abreDiv('distribuicaoParaNovoSocio');
			abreDiv('AdministracaoParaNovoSocio');
	}
	
	function mudaNomeSocio(numero) {
		var totalSociosAtuais = document.getElementById('hidTotalSocioExistente').value;
		for (i=1; i<=totalSociosAtuais; i++){
			document.getElementById('Socio'+i+'nomeSocio'+numero).innerHTML = 'quotas para ' + document.getElementById('txtNome'+numero).value;
		}
		document.getElementById('distribuicaoNomeSocio'+numero).innerHTML = 'quotas para ' + document.getElementById('txtNome'+numero).value;
		if (document.getElementById('txtNome'+numero).value == "") {
			document.getElementById('AdministracaoNomeSocio'+numero).innerHTML = "&nbsp;"
		} else {
			document.getElementById('AdministracaoNomeSocio'+numero).innerHTML = document.getElementById('txtNome'+numero).value;
		}
	}
	
	function someSocioExcluido(numero, obj) {
		var totalSociosAtuais = document.getElementById('hidTotalSocioExistente').value;

		for (i=1; i<=totalSociosAtuais; i++){
			if (i != numero) {
				document.getElementById('cheSocioTransfere' + numero).checked = false;
				if(obj.checked){
					document.getElementById('divSocio'+i+'TransfereCotaPara'+numero).style.display = 'block';
					document.getElementById('divAdministracaoFuturaSocio'+numero).style.display  = 'block';
					document.getElementById('cheSocioTransfere' + numero).checked = true;
				}
				abreDiv('divSocio'+i+'TransfereCotaPara'+numero);
				abreDiv('divSocioTransfereCota' + numero);
			}
		}

		abreDiv('divDistribuicaoFuturaSocio'+numero);
		abreDiv('divAdministracaoFuturaSocio'+numero);
	}
	
	function reapareceSocioExcluido() {
		var totalSociosAtuais = document.getElementById('hidTotalSocioExistente').value;
		for (i=1; i<=totalSociosAtuais; i++){
			if(document.getElementById('cheSocioExcluso' + i).checked) {
				for (o=1; o<=totalSociosAtuais; o++){
						if (o != i) {
							abreDiv('divSocio'+o+'TransfereCotaPara'+i);
						}
				}
				abreDiv('divDistribuicaoFuturaSocio'+i);
				abreDiv('divAdministracaoFuturaSocio'+i);
			}
		}
	}
	
	function bloqueiaAletracaoCotas() {
		if(document.getElementById('cheIncluirSocio').checked || document.getElementById('cheExcluirSocio').checked) {
			document.getElementById('cheAlterarCotas').checked = true;
			document.getElementById('cheAlterarCotas').disabled = true;
			abreDiv2('cotas');
		} else {
			document.getElementById('cheAlterarCotas').checked = false;
			document.getElementById('cheAlterarCotas').disabled = false;
			abreDiv2('cotas');
		}
	}

 var msg1 = 'É necessário preencher o campo';
 var msg2 = 'É necessário selecionar ';

function _validElement(idElement, msg){

                var Element= document.getElementById(idElement);

                if(Element.value == "" || Element.value == false ){

                    window.alert(msg+' '+Element.alt+'.');
                    return false;
                }
            }

function formSubmit(){  
	var totalSociosAtuais = document.getElementById('hidTotalSocioExistente').value;
	
	var distribuicaoAtual = 0;
	for (i=1; i<=totalSociosAtuais; i++){
		if(validElement('txtDistribuicaoAtualSocio'+i, msg1) == false){return false}
		else { distribuicaoAtual = distribuicaoAtual + parseInt(document.getElementById('txtDistribuicaoAtualSocio'+i).value); }
	}
	
	if(validElement('totalReaisAtual', msg1) == false){return false}
	if(validElement('totalCotasAtual', msg1) == false){return false}
	
	if(document.getElementById('totalCotasAtual').value != distribuicaoAtual){
		alert('O total de quotas atual não corresponde com a distribuição societária atual');
		return false;
	}
	
	if(document.getElementById('cheMudancaRazao').checked) {
		if(validElement('txtRazaoSocial', msg1) == false){return false}
	}

	if(document.getElementById('cheMudancaEndereco').checked) {
		if(validElement('txtEndereco', msg1) == false){return false}
		if(validElement('txtCEP', msg1) == false){return false}
        if(validElement('txtCidade', msg1) == false){return false}
		var Cidade = document.getElementById('txtCidade');
		if(Cidade.value.toLowerCase() == 'são paulo' || Cidade.value.toLowerCase() == 'sao paulo') {
			Cidade.value = 'São Paulo';
		}
		var Estado = document.getElementById('selEstado');
        if(Estado.selectedIndex == ""){
        	window.alert(msg2+'o Estado.');
            return false
		}
	}
	
	if(document.getElementById('cheExcluirAtividade').checked) {
		var cnaeExcluido = 0;
		for (i=1;i<=document.getElementById('hidTotalCnaeExistente').value;i++) {
			if(document.getElementById('cheCnaeExcluso' + i).checked) {
				cnaeExcluido = cnaeExcluido + 1;
			}
		}
		if(cnaeExcluido == 0){
			window.alert(msg2+'pelo menos uma atividade para excluir.');
			return false
		}
		document.getElementById('hidTotalCnaeExcluido').value = cnaeExcluido;		
	}
	
	
	if(document.getElementById('cheIncluirAtividade').checked) {
		for (i=1;i<=document.getElementById('countCNAEIncluso').value - 1;i++) {
			if( validElement('txtCodigoCNAE'+i, msg1) == false){return false}
		}
	}
	
	if(document.getElementById('cheExcluirSocio').checked) {
		var socioExcluido = 0;
		for (i=1;i<=document.getElementById('hidTotalSocioExistente').value;i++) {
			if(document.getElementById('cheSocioExcluso' + i).checked) {
				socioExcluido = socioExcluido + 1;
			}
		}
		if(socioExcluido == 0){
			window.alert(msg2+'pelo menos um socio para excluir.');
			return false
		}
		document.getElementById('hidTotalSocioExcluido').value = socioExcluido;		
	}
		
	if(document.getElementById('cheIncluirSocio').checked) {

		var count = parseInt(document.getElementById('count').value);
		
		//alert('socios: '  +count);
		
		for(i=1; i<=count; i++) {
			if(validElement('txtNome'+i, msg1) == false){return false}
			if(validElement('txtNacionalidade'+i, msg1) == false){return false}
			if(!document.getElementById('radSexo1Socio'+i).checked && !document.getElementById('radSexo2Socio'+i).checked) {
				alert (msg2 + 'o Sexo do novo sócio.');
				return false
			}
			if(validElement('txtEstadoCivil'+i, msg1) == false){return false}
			if(validElement('txtProfissao'+i, msg1) == false){return false}
			if(validElement('txtCPF'+i, msg1) == false){return false}
			if(validElement('txtRG'+i, msg1) == false){return false}
			if(validElement('txtOrgaoExpedidor'+i, msg1) == false){return false}
			if(validElement('txtEnderecoSocio'+i, msg1) == false){return false}
			if(validElement('txtCEPSocio'+i, msg1) == false){return false}
			if(validElement('txtCidadeSocio'+i, msg1) == false){return false}
			var Estado = document.getElementById('selEstadoSocio'+i);
       		if(Estado.selectedIndex == ""){
        		window.alert(msg2+'o Estado do novo sócio.');
            	return false
			}
			
			if(document.getElementById('txtNacionalidade'+i).value.toLowerCase() == 'brasileiro' || document.getElementById('txtNacionalidade'+i).value.toLowerCase() == 'brasileira') {
				if(document.getElementById('radSexo1Socio'+i).checked) {
					document.getElementById('txtNacionalidade'+i).value = 'Brasileiro';
				} else if(document.getElementById('radSexo2Socio'+i).checked) {
					document.getElementById('txtNacionalidade'+i).value = 'Brasileira';
				}
			}

			
		}
		
		
	}

	if(document.getElementById('cheAlterarCotas').checked) {
		var distribuicaoFutura = 0;
		// PEGANDO A QUANTIDADE DE SÓCIOS QUE A EMPRESA POSSUI PARA CHECAR SE HÁ ALGUEM QUE TRANSFERE COTA
		for (i=1;i<=document.getElementById('hidTotalSocioExistente').value;i++) {
			// SE O SÓCIO DO LOOP ESTÁ SELECIONADO
			if (document.getElementById('cheSocioTransfere'+i).checked) {

				var totalFinal = 0;
				var cotasTransferidas = 0;
				// INICIAR OUTRO LOOP PARA CHECAR E O CAMPO PARA TRANSFERENCIA DE COTA PARA O OUTRO SOCIO ESTA PREECNHIDO
				for (o=1;o<=document.getElementById('hidTotalSocioExistente').value;o++) {

					if(i != o && document.getElementById('txtSocio'+i+'TransfereCotaPara'+o).value != "" && document.getElementById('divSocio'+i+'TransfereCotaPara'+o).style.display == 'block'){
						totalFinal += 1;
						cotasTransferidas = cotasTransferidas + parseInt(document.getElementById('txtSocio'+i+'TransfereCotaPara'+o).value);
						
					}
						

				}
				
				// SE HOUVER UMA INCLUSAO DE SOCIO, CHECAR NOS CAMPOS DE TRANSFERENCIA PARA NOVO SOCIO
				if (document.getElementById('cheIncluirSocio').checked) {
					for (o=1;o<=(document.getElementById('count').value);o++) {
						if(document.getElementById('txtSocio'+i+'TransfereCotaParaNovo'+o).value != ""){
							totalFinal += 1;
							cotasTransferidas = cotasTransferidas + parseInt(document.getElementById('txtSocio'+i+'TransfereCotaParaNovo'+o).value);
						}
					}
				}

				if(totalFinal == 0 || totalFinal == ""){
					alert('É necessário preencher a quantidade de quotas a serem transferidas por ' + document.getElementById('hidTotalSocio'+i+'Transfere').alt + '');	
					return false;
				} else {
					document.getElementById('hidTotalSocio'+i+'Transfere').value = totalFinal;
				}
				if(parseInt(document.getElementById('txtDistribuicaoAtualSocio'+i).value) - cotasTransferidas < 0) {
					alert('A quantidade de quotas transferidas pelo(a) socio(a) ' + document.getElementById('hidTotalSocio'+i+'Transfere').alt + ' é superior que seu total de quotas.')
					return false;
				}
			} else {
				document.getElementById('hidTotalSocio'+i+'Transfere').value = 0;
			}
			
			if(document.getElementById('cheExcluirSocio').checked) {
				
				if(!document.getElementById('cheSocioExcluso'+i).checked) {
					if(validElement('txtDistribuicaoFuturaSocio'+i, msg1) == false){
						return false;
					} else {
						distribuicaoFutura = distribuicaoFutura + parseInt(document.getElementById('txtDistribuicaoFuturaSocio'+i).value);
					}
				}else{

					if((document.getElementById('hidTotalSocio'+i+'Transfere').value == '' || document.getElementById('hidTotalSocio'+i+'Transfere').value == '0') && (totalSocioFinal > 1)){
						alert('É necessário informar a transferência de quotas do sócio que está sendo excluído.');
						return false;
					}

				}
			} else {
				if(validElement('txtDistribuicaoFuturaSocio'+i, msg1) == false){return false}
				else {
					distribuicaoFutura = distribuicaoFutura + parseInt(document.getElementById('txtDistribuicaoFuturaSocio'+i).value);
				}
			}
		}
		
		if(document.getElementById('cheIncluirSocio').checked) {
			var count = parseInt(document.getElementById('count').value);
			for(i=1;i<=count;i++) {
				document.getElementById('txtDistribuicaoFuturaNovoSocio'+i).alt = 'distribuição societária futura com as quotas de ' + document.getElementById('txtNome'+i).value;
				if(validElement('txtDistribuicaoFuturaNovoSocio'+i, msg1) == false){return false}
				else {
					distribuicaoFutura = distribuicaoFutura + parseInt(document.getElementById('txtDistribuicaoFuturaNovoSocio'+i).value);
				}
			}
		}
		
		if(!document.getElementById('cheAlterarCapitalSocial').checked) {
			if(distribuicaoFutura != document.getElementById('totalCotasAtual').value) {
				alert('A distribuição societária futura não corresponde com o capital social atual.');
				return false;
			}
		} else {
			if(distribuicaoFutura != document.getElementById('totalCotasFuturo').value) {
				alert('A distribuição societária futura não corresponde com o futuro capital social.');
				return false;
			}
		}
	}

	var totalSocioFinal = parseInt(document.getElementById('hidTotalSocioExistente').value);
//alert(totalSocioFinal);
	if (document.getElementById('cheExcluirSocio').checked) {
		totalSocioFinal = totalSocioFinal - parseInt(document.getElementById('hidTotalSocioExcluido').value);
	}
	if (document.getElementById('cheIncluirSocio').checked) {
		//alert(document.querySelectorAll('[id^=txtNome]').value);
		
		 totalSocioFinal = totalSocioFinal + parseInt(document.getElementById('count').value);
	}
	document.getElementById('hidTotalSocioFinal').value = totalSocioFinal;

//alert(totalSocioFinal);

	//CHECANDO SE O TOTAL DE SÓCIOS QUE CONSTITUEM A EMPRESA É MENOR QUE 2. SE FOR, DEVE EXIBIR UM ALERT
//	if((parseInt(document.getElementById('hidTotalSocioExistente').value) - socioExcluido) < 2){
	if(totalSocioFinal < 2){
		alert('Com as alterações definidas, a empresa ficará com apenas 1 sócio, tornando-se uma sociedade unipessoal. Você terá até 180 dias para incluir um novo sócio ou para transformar a sociedade em uma EI - Empresa Individual, ou EIRELI - Empresa Individual de Responsabilidade Limitada.');
//		return false;
	}
	
	if(document.getElementById('cheAlterarCapitalSocial').checked) {
		if(validElement('totalReaisFuturo', msg1) == false){return false}
		if(validElement('totalCotasFuturo', msg1) == false){return false}
	}
	
	var contAdministracao = 0;
	for (i=1;i<=document.getElementById('hidTotalSocioExistente').value;i++) {
		if (document.getElementById('cheAdministracaoSocio' + i).checked) {
			if (document.getElementById('cheExcluirSocio').checked) {
				if(!document.getElementById('cheSocioExcluso' + i).checked) {
					contAdministracao = contAdministracao + 1;
				}
			} else {
				contAdministracao = contAdministracao + 1;
			}
		}
	}

	if (document.getElementById('cheIncluirSocio').checked) {
		for (i=1;i<=(document.getElementById('count').value - 1);i++) {
			alert(document.getElementById('cheAdministracaoNovoSocio' + i).value);
			if(document.getElementById('cheAdministracaoNovoSocio' + i).checked){
				contAdministracao = contAdministracao + 1;
			}
		}
	}

	if (contAdministracao == 0 || contAdministracao == "") {
		alert(msg2 + 'a quem caberá a administração da sociedade.');
		return false
	} else {
		document.getElementById('hidTotalAdministracao').value = contAdministracao;
	}
/*	
	var totalSocioFinal = parseInt(document.getElementById('hidTotalSocioExistente').value);
	if (document.getElementById('cheExcluirSocio').checked) {
		totalSocioFinal = totalSocioFinal - parseInt(document.getElementById('hidTotalSocioExcluido').value);
	}
	if (document.getElementById('cheIncluirSocio').checked) {
		 totalSocioFinal = totalSocioFinal + parseInt(document.getElementById('count').value) - 1;
	}
	document.getElementById('hidTotalSocioFinal').value = totalSocioFinal;
*/
	for (i=1;i<=2;i++) {
		if(validElement('txtNomeTest'+i, msg1) == false){return false}
		if(validElement('txtRGTest'+i, msg1) == false){return false}
		if(validElement('txtOrgaoExpedidorTest'+i, msg1) == false){return false}
	}
	
	document.getElementById('form1').submit()
}


/*
function SetCookie(objeto){
	var tipo = objeto.type;
	var valor = '';
	
	if(tipo == 'checkbox'){
		valor = objeto.checked;
	}else{
		valor = objeto.value;
	}

//	if(valor != ''){
		$.ajax({
			url: 'set_dados_contrato.php',
			data: 'nomeCookie='+objeto.name+'&valorCookie='+valor+'&id=<?=$_SESSION['id_userSecao']?>',
			async: false,
			type: 'POST'
		});		
//	}

}
*/




$(document).ready(function() {
	
/*
		$('input[id^="txtSocio"]').blur(function(){
				if($(this).val() == ''){
					$(this).val(0);
				}
				
				var
					$this 									= $(this),
					$idSocio								= $this.attr("pos"),
					$transfereParaSocioNovo = ($this.attr('id').indexOf('Novo',0) > 0 ? true : false),
					$novoIdCampo 						= $this.attr('id').replace(/txtSocio/gi,'').replace(/Novo/gi,''),
					$txtIdSociosCampoAtual 	= $novoIdCampo.replace(/TransfereCotaPara/gi,'#'),
					$arrIdSociosCampoAtual 	= $txtIdSociosCampoAtual.split('#'),
					$idSocioTransfereDe 		= $arrIdSociosCampoAtual[0],
					$idSocioTransferePara 	= $arrIdSociosCampoAtual[1],
					
					$indiceCampo						= $('input[id^="txtSocio'+$idSocioTransfereDe+'"]').index($this),
	
					$valorQuotaNova 				= parseInt($this.val()),
					$valorCedeQuotaAtual		= parseInt($('#txtDistribuicaoAtualSocio' + $idSocioTransfereDe).val()),
					
					$valorSubtraiTotal			= 0,
					$valorSomaTotal					= 0,
					
					$valorRecebeQuotaAtual 	= 0
				;
	
	var $1=0, $2=0, $3 = 0;
$('input[id^="txtSocio'+$idSocioTransfereDe+'"]').each(function(index,element){
	$1 += parseInt($(this).val());
});
	
console.log('VALOR A SUBTRAIR DESTE SOCIO: ' + $1);

if(!$transfereParaSocioNovo){
	var $valorRecebeQuotaAtual	= parseInt($('#txtDistribuicaoAtualSocio' + $idSocioTransferePara).val());
	
	$('input[id$="TransfereCotaPara'+$idSocioTransferePara+'"]').each(function(index,element){
		if($(this).val() != ''){
			$2 += parseInt($(this).val());
		}
	});
	
}else{
	
	$('input[id$="TransfereCotaParaNovo'+$idSocioTransferePara+'"]').each(function(index,element){
		if($(this).val() != ''){
			$2 += parseInt($(this).val());
		}
	});
	
}

	
console.log('VALOR A SOMAR PARA OUTRO SOCIO: ' + $2);

	
				if(!$transfereParaSocioNovo){
					var $valorRecebeQuotaAtual	= parseInt($('#txtDistribuicaoAtualSocio' + $idSocioTransferePara).val());
					
					$('input[id$="TransfereCotaPara'+$idSocioTransferePara+'"]').each(function(index,element){
						var $valor = 0;
						if($(this).val() != ''){
							$valor = $(this).val();
						}
						$valorSomaTotal += parseInt($valor);
					});
					
				}else{
					
					$('input[id$="TransfereCotaParaNovo'+$idSocioTransferePara+'"]').each(function(index,element){
						var $valor = 0;
						if($(this).val() != ''){
							$valor = $(this).val();
						}
						$valorSomaTotal += parseInt($valor);
					});
					
				}
//console.log('total que deve ser somado ao outro sócio: ' + $valorSomaTotal);

				if(!$transfereParaSocioNovo && $('#txtSocio'+$idSocioTransferePara+'TransfereCotaPara'+$idSocioTransfereDe).length){
				//alert('#txtSocio'+$idSocioTransferePara+'TransfereCotaPara'+$idSocioTransfereDe);
					$('#txtSocio'+$idSocioTransferePara+'TransfereCotaPara'+$idSocioTransfereDe).attr('disabled',true);
				}
	
				$('input[id^="txtSocio'+$idSocioTransfereDe+'"]').each(function(index,element){
					var $valor = 0;
					if($(this).val() != ''){
						$valor = $(this).val();
					}
					$valorSubtraiTotal += parseInt($valor);
				});
//console.log('total que deve ser subtraído deste sócio: ' + $valorSubtraiTotal);
	
	//alert($valorRecebeQuotaAtual);
	
//				if($indiceCampo > 0){
//					for(var i=0; i<$indiceCampo; i++){
//						$valorSubtrai += parseInt($('input[id^="txtSocio'+$idSocioTransfereDe+'"]').eq(i).val());
//					}
//				}

				$('#txtDistribuicaoFuturaSocio' + $idSocioTransfereDe).val(parseInt($valorCedeQuotaAtual) - $valorSubtraiTotal);
				if($transfereParaSocioNovo){
					//$('#txtDistribuicaoFuturaNovoSocio' + $idSocioTransferePara).val(($('#txtDistribuicaoFuturaNovoSocio' + $idSocioTransferePara).val() != '' ? parseInt($('#txtDistribuicaoFuturaNovoSocio' + $idSocioTransferePara).val()) : 0) + parseInt($this.val()));
					$('#txtDistribuicaoFuturaNovoSocio' + $idSocioTransferePara).val(parseInt($valorSomaTotal));
				}else{
					$('#txtDistribuicaoFuturaSocio' + $idSocioTransferePara).val(parseInt($valorSomaTotal));
				}
		//	}

			$('input[id^="txtDistribuicaoFutura"]').attr('readonly',true);
			

			//alert($this.attr('id') + ' - ' + $txtIdSociosCampoAtual + ' - transfere de ' + $idSocioTransfereDe + ' para ' + $idSocioTransferePara);
		});*/

		$('input[type="text"]').blur(function(){
			if($(this).val() == '0'){
				$(this).val('');
			}
		});
		
   $("#btSalvarContrato").click(function(e){
		var arrData = $('#form1').serialize();
		$.ajax({
			url: 'set_dados_contrato.php',
			data: arrData,
			type: 'POST',
			cache: false,
			beforeSend: function(){
				$('body').css('cursor','wait');
			},
			success: function(result){
				//alert(result);
				if(result == 'ok'){
					alert('Informações salvas com sucesso.');
				}else{
					alert('Ocorreu um erro ao salvar as informações.');
				}
				$('body').css('cursor','default');
				//$('#testaResultado').html(result).show();
			}
		});

   });

   $("#btProsseguirContrato").click(function(e){
		var arrData = $('#form1').serialize();
		$.ajax({
			url: 'set_dados_contrato.php',
			data: arrData,
			type: 'POST',
			cache: false,
			beforeSend: function(){
				$('body').css('cursor','wait');
			},
			success: function(result){
				//alert(result);
				$('body').css('cursor','default');
				formSubmit();
				//$('#testaResultado').html(result).show();
			}
		});

   });

	$("#btLimparContrato").click(function(){
		var arrForm = $('#form1').serializeArray();
		$.each(arrForm, function(i, objCampo){
			switch($("#form1 :input[name="+this.name+"]").attr('type')){
				case 'text':
					$("#form1 :input[name="+this.name+"]").attr('value','');
				break;
				case 'select-one':
					$('#'+this.name).attr('value','');
				break;
				case 'checkbox':
					$("#form1 :input[name="+this.name+"]").attr('checked','');
				break;
				case 'radio':
					$("#form1 :input[name="+this.name+"]").attr('checked','');
				break;
			}
		});
		
		$('#capital').css('display','none');
		$('#distribuicaoParaNovoSocio').css('display','none');
		$('#cotas').css('display','none');
		$('#novo_socio').css('display','none');
		$('#exclui_socio').css('display','none');
		$('#novo_cnae').css('display','none');
		$('#exclui_cnae').css('display','none');
		$('#novo_endereco').css('display','none');
		$('#AdministracaoParaNovoSocio').css('display','none');
		$('#nova_razao').css('display','none');
		$('div[id^="divSocioTransfereCota"]').css('display','none');
		$('div[id^="transfereParaNovosSocios"]').css('display','none');
		
/*		for(i=0; i <= arrForm.length; i++){
			alert(arrForm[i][0]);
		}*/
	});

	/*
	LIMPAR OS CAMPOS DOS DIVS QUANDO O CHECKBOX FOR DESMARCADO
	*/
	$("#cheMudancaRazao").click(function(){
		if(!$(this).attr('checked')){
			$('#nova_razao').find('input').each(function(){
				$(this).val('');
			});
		}
	});

	$("#cheMudancaEndereco").click(function(){
		if(!$(this).attr('checked')){
			$('#novo_endereco').find('input[type="text"]').each(function(){
				$(this).val('');
			});
			$('#novo_endereco').find('input[type="checkbox"]').each(function(){
				$(this).attr('checked','');
			});
			$('#novo_endereco').find('select').each(function(){
				$(this).val('SP');
			});
		}
	});

	$("#cheExcluirAtividade").click(function(){
		if(!$(this).attr('checked')){
			$('#exclui_cnae').find('input[type="checkbox"]').each(function(){
				$(this).attr('checked','');
			});
		}
	});

	$("#cheIncluirAtividade").click(function(){
		if(!$(this).attr('checked')){
			// CHECA SE TEM MAIS DE UM CAMPO PARA O NOVO CNAE
//			if($('div[id^="item"]').length > 1){
				for(c=1; c<=$('div[id^="item"]').length; c++){
//					alert($('#item'+c).html());
					$('#atividade'+c).html('');
					removeCNAE();
				}
//			}
			$('#novo_cnae').find('input[type="text"]').each(function(){
				$(this).val('');
			});
		}
	});

	$("#cheExcluirSocio").click(function(){
		if(!$(this).attr('checked')){
			$('#exclui_socio').find('input[type="checkbox"]').each(function(){
				$(this).attr('checked','');
			});
			// LIMPA E ESCONDE INFORMAÇÕES DE ALTERAÇÃO DE COTAS
			// ->
				$('#cotas').find('input[type="text"]').each(function(){
					$(this).val('');
				});
				$('#cotas').find('input[type="checkbox"]').each(function(){
					$(this).attr('checked','');
				});
				$('#cheAlterarCotas').attr('disabled','').attr('checked','');
				reapareceSocioExcluido();
				$('#cotas').css('display','none');
				$('div[id^="divSocioTransfereCota"]').css('display','none');
		}
	});

	$("#cheIncluirSocio").click(function(){
		if(!$(this).attr('checked')){
			$('#novo_socio').find('input[type="text"]').each(function(){
				$(this).val('');
			});
			$('#novo_socio').find('input[type="radio"]').each(function(){
				$(this).attr('checked','');
			});
			$('#novo_socio').find('input[type="checkbox"]').each(function(){
				$(this).attr('checked','');
			});
			$('#novo_socio').find('select').each(function(){
				$(this).val('SP');
			});
			for(c=1; c<=$('div[id^="socio"]').length; c++){
				if( c>1 ){
					remove();
				}
			}
			// LIMPA E ESCONDE INFORMAÇÕES DE ALTERAÇÃO DE COTAS
			// ->
				$('#cotas').find('input[type="text"]').each(function(){
					$(this).val('');
				});
				$('#cotas').find('input[type="checkbox"]').each(function(){
					$(this).attr('checked','');
				});
				$('#cheAlterarCotas').attr('disabled','').attr('checked','');
				$('#cotas').css('display','none');
				$('div[id^="divSocioTransfereCota"]').css('display','none');
		}
	});

	$("#cheAlterarCotas").click(function(){
		if(!$(this).attr('checked')){
			$('#cotas').find('input[type="text"]').each(function(){
				$(this).val('');
			});
			$('#cotas').find('input[type="checkbox"]').each(function(){
				$(this).attr('checked','');
			});
			$('div[id^="divSocioTransfereCota"]').css('display','none');
		}
	});


/*
	$('.campoTransfereCotasPara').blur(function(){
		if($(this).val() == '333'){
			$('#hidTotalSocio' + $(this).attr('pos') + 'Transfere').val();
		}else{
			$('#hidTotalSocio' + $(this).attr('pos') + 'Transfere').val() = parseInt($('#hidTotalSocio' + $(this).attr('pos') + 'Transfere').val());
		}
	});
*/

	$("#cheAlterarCapitalSocial").click(function(){
		if(!$(this).attr('checked')){
			$('#capital').find('input[type="text"]').each(function(){
				$(this).val('');
			});
		}
	});

// TENTANDO RESOLVER A QUESTÃO DAS MASCARAS
	$(".adicionaSocio").live("click",function(e){
		e.preventDefault();
		add();
	});

});



</script>


<?

function getDadosContrato($tabela, $nomeCampo, $idUser){

	// TRAZ DADOS DO CONTRATO	
	$resultado2 = mysql_query("SELECT valor_campo FROM " . $tabela . " WHERE id='" . $idUser . "' AND nome_campo = '" . $nomeCampo . "'") or die (mysql_error());
	if($contrato = mysql_fetch_array($resultado2)){
		return $contrato['valor_campo'];
	}else{
		return "";
	}
	
}


function getDadosCliente($tabela, $nomeCampo, $idUser){

	// TRAZ DADOS DO CLIENTE
	$res = mysql_query("SELECT " . $nomeCampo . " valor FROM " . $tabela . " WHERE id='" . $idUser . "'") or die (mysql_error());
	if($dados = mysql_fetch_array($res)){
		return $dados['valor'];
	}else{
		return "";
	}
	
}

?>

<div class="principal">

	<div id="testaResultado" style="position: absolute; z-index; 9999999; background-color: #FFF; width: 600px; width: 800px; display: none"></div>


	<div class="minHeight" style="width:740px">
        <div class="titulo" style="margin-bottom:10px;">Alterações Contratuais</div>
          Antes de iniciar o processo de alteração contratual, você precisa completar todos os campos em <a href="meus_dados_empresa.php">Dados da Empresa</a> e incluir todos os sócios em <a href="meus_dados_socio.php">Cadastro de Sócios</a>, inclusive aqueles que eventualmente irão deixar a sociedade neste ato. Feito isto, preencha os campos abaixo. Uma nova janela se abrirá, com o documento pronto. Imprima-o em três vias.<br /><br />

        <form id="form1" name="form1" method="post" action="alteracao_contrato_visualizar.php">  
        <div class="destaqueAzul" style="margin-bottom:10px"> Digite a distribuição societária atual</div>

		<?php 
        $sql = "SELECT * FROM dados_do_responsavel WHERE id='" . $_SESSION["id_userSecao"] . "' ORDER BY idSocio ASC";
        
        $resultado = mysql_query($sql)
        or die (mysql_error());
        
        $listaSocios = 1;
        
        while ($linha=mysql_fetch_array($resultado)) {	
        
        ?>
            <div style="margin-bottom:3px">
                <input class="inteiro" type="text" name="txtDistribuicaoAtualSocio<?=$listaSocios?>" id="txtDistribuicaoAtualSocio<?=$listaSocios?>" style="width:60px" alt="distribuição societária atual com as quotas de <?=$linha["nome"]?>" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtDistribuicaoAtualSocio' . $listaSocios . '', $_SESSION["id_userSecao"]))?>" /> quotas para <?=$linha["nome"]?>
            </div>
<?php  
			$listaSocios = $listaSocios + 1;
		}
?>

<input type="hidden" name="hidTotalSocioExistente" id="hidTotalSocioExistente" value="<?=mysql_num_rows($resultado)?>" />
	<br />
    <div class="destaqueAzul" style="margin-bottom:10px"> Digite o capital social  atual</div>
    <input class="current" type="text" name="totalReaisAtual" id="totalReaisAtual" style="width:60px; margin-bottom:3px" maxlength="10" alt="capital social atual com o valor de cada quota (em reais)" value="<?=(getDadosContrato('dados_alteracao_contrato', 'totalReaisAtual', $_SESSION["id_userSecao"]))?>" /> Valor de cada quota (em reais)<br />
    
	<input class="inteiro" type="text" name="totalCotasAtual" id="totalCotasAtual" style="width:60px" alt="capital social atual com o total de cotas" value="<?=(getDadosContrato('dados_alteracao_contrato', 'totalCotasAtual', $_SESSION["id_userSecao"]))?>" /> Total de quotas<br />
    <br />

<? // 	CHECKBOX COM AS ALTERAÇÕES A SEREM FEITAS ?>
    <div class="destaqueAzul" style="margin-bottom:10px"> Quais as alterações a serem feitas?</div>


	    <label><input type="checkbox" name="cheMudancaRazao" id="cheMudancaRazao" onClick="javascript:abreDiv('nova_razao'); " <?=(getDadosContrato('dados_alteracao_contrato', 'cheMudancaRazao', $_SESSION["id_userSecao"]) == 'on' ? 'checked' : '')?> /> Mudança da razão social</label><br />

<? // 	FORMULARIO DE MUDANÇA DE RAZAO SOCIAL ?>
        <div style="margin-left:17px; margin-bottom:20px; <?=(getDadosContrato('dados_alteracao_contrato', 'cheMudancaRazao', $_SESSION["id_userSecao"]) == 'on' ? 'display:block' : 'display:none')?>" id="nova_razao">

	        <div class="destaqueAzul" style="margin-bottom:5px; margin-top:10px">Nova razão social:</div>
            <input type="text" name="txtRazaoSocial" id="txtRazaoSocial" style="width:150px" alt="razão social" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtRazaoSocial', $_SESSION["id_userSecao"]))?>" />
        </div>




        <label><input type="checkbox" name="cheMudancaEndereco" id="cheMudancaEndereco" onClick="javascript:abreDiv('novo_endereco');" <?=(getDadosContrato('dados_alteracao_contrato', 'cheMudancaEndereco', $_SESSION["id_userSecao"]) == 'on' ? 'checked' : '')?> /> Mudança de endereço </label><br />

		<? // 	FORMULARIO DE MUDANÇA DE ENDEREÇO ?>
        <div style="margin-left:17px; <?=(getDadosContrato('dados_alteracao_contrato', 'cheMudancaEndereco', $_SESSION["id_userSecao"]) == 'on' ? 'display:block' : 'display:none')?>" id="novo_endereco">

            <div class="destaqueAzul" style="margin-bottom:5px; margin-top:10px">Novo endereço:</div>
  
                <table border="0" cellpadding="0" cellspacing="3" style="background:none" class="formTabela">
                    <tr>
                        <td align="right" valign="middle" class="formTabela">Endere&ccedil;o:</td>
                        <td class="formTabela"><input name="txtEndereco" id="txtEndereco" type="text" style="width:300px" maxlength="200" alt="Endereço" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtEndereco', $_SESSION["id_userSecao"]))?>" /></td>
                    </tr>
                    <tr>
                        <td align="right" valign="middle" class="formTabela">Bairro:</td>
                        <td class="formTabela"><input name="txtBairro" id="txtBairro" type="text" style="width:300px" maxlength="200" alt="Bairro" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtBairro', $_SESSION["id_userSecao"]))?>" /></td>
                    </tr>
                    <tr>
                        <td align="right" valign="middle" class="formTabela">CEP:</td>
                        <td class="formTabela"><input name="txtCEP" id="txtCEP" type="text" style="width:70px" maxlength="9" alt="CEP" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtCEP', $_SESSION["id_userSecao"]))?>" /> <span style="font-size:10px">Ex.: 99999-999</span></td>
                    </tr>
                    <tr>
                        <td align="right" valign="middle" class="formTabela">Cidade:</td>
                        <td class="formTabela"><input name="txtCidade" id="txtCidade" type="text" style="width:300px" maxlength="200" alt="Cidade" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtCidade', $_SESSION["id_userSecao"]))?>" /></td>
                    </tr>
                    <tr>
                        <td align="right" valign="middle" class="formTabela">Estado:</td>
                        <td class="formTabela">
                        	<select name="selEstado" id="selEstado">
                                <option value="AC" <?=(getDadosContrato('dados_alteracao_contrato', 'selEstado', $_SESSION["id_userSecao"]) == 'AC' ? 'SELECTED' : '')?>>AC</option>
                                <option value="AL" <?=(getDadosContrato('dados_alteracao_contrato', 'selEstado', $_SESSION["id_userSecao"]) == 'AL' ? 'SELECTED' : '')?>>AL</option>
                                <option value="AM" <?=(getDadosContrato('dados_alteracao_contrato', 'selEstado', $_SESSION["id_userSecao"]) == 'AM' ? 'SELECTED' : '')?>>AM</option>
                                <option value="AP" <?=(getDadosContrato('dados_alteracao_contrato', 'selEstado', $_SESSION["id_userSecao"]) == 'AP' ? 'SELECTED' : '')?>>AP</option>
                                <option value="BA" <?=(getDadosContrato('dados_alteracao_contrato', 'selEstado', $_SESSION["id_userSecao"]) == 'BA' ? 'SELECTED' : '')?>>BA</option>
                                <option value="CE" <?=(getDadosContrato('dados_alteracao_contrato', 'selEstado', $_SESSION["id_userSecao"]) == 'CE' ? 'SELECTED' : '')?>>CE</option>
                                <option value="DF" <?=(getDadosContrato('dados_alteracao_contrato', 'selEstado', $_SESSION["id_userSecao"]) == 'DF' ? 'SELECTED' : '')?>>DF</option>
                                <option value="ES" <?=(getDadosContrato('dados_alteracao_contrato', 'selEstado', $_SESSION["id_userSecao"]) == 'ES' ? 'SELECTED' : '')?>>ES</option>
                                <option value="GO" <?=(getDadosContrato('dados_alteracao_contrato', 'selEstado', $_SESSION["id_userSecao"]) == 'GO' ? 'SELECTED' : '')?>>GO</option>
                                <option value="MA" <?=(getDadosContrato('dados_alteracao_contrato', 'selEstado', $_SESSION["id_userSecao"]) == 'MA' ? 'SELECTED' : '')?>>MA</option>
                                <option value="MG" <?=(getDadosContrato('dados_alteracao_contrato', 'selEstado', $_SESSION["id_userSecao"]) == 'MG' ? 'SELECTED' : '')?>>MG</option>
                                <option value="MS" <?=(getDadosContrato('dados_alteracao_contrato', 'selEstado', $_SESSION["id_userSecao"]) == 'MS' ? 'SELECTED' : '')?>>MS</option>
                                <option value="MT" <?=(getDadosContrato('dados_alteracao_contrato', 'selEstado', $_SESSION["id_userSecao"]) == 'MT' ? 'SELECTED' : '')?>>MT</option>
                                <option value="PA" <?=(getDadosContrato('dados_alteracao_contrato', 'selEstado', $_SESSION["id_userSecao"]) == 'PA' ? 'SELECTED' : '')?>>PA</option>
                                <option value="PB" <?=(getDadosContrato('dados_alteracao_contrato', 'selEstado', $_SESSION["id_userSecao"]) == 'PB' ? 'SELECTED' : '')?>>PB</option>
                                <option value="PE" <?=(getDadosContrato('dados_alteracao_contrato', 'selEstado', $_SESSION["id_userSecao"]) == 'PE' ? 'SELECTED' : '')?>>PE</option>
                                <option value="PI" <?=(getDadosContrato('dados_alteracao_contrato', 'selEstado', $_SESSION["id_userSecao"]) == 'PI' ? 'SELECTED' : '')?>>PI</option>
                                <option value="PR" <?=(getDadosContrato('dados_alteracao_contrato', 'selEstado', $_SESSION["id_userSecao"]) == 'PR' ? 'SELECTED' : '')?>>PR</option>
                                <option value="RJ" <?=(getDadosContrato('dados_alteracao_contrato', 'selEstado', $_SESSION["id_userSecao"]) == 'RJ' ? 'SELECTED' : '')?>>RJ</option>
                                <option value="RN" <?=(getDadosContrato('dados_alteracao_contrato', 'selEstado', $_SESSION["id_userSecao"]) == 'RN' ? 'SELECTED' : '')?>>RN</option>
                                <option value="RO" <?=(getDadosContrato('dados_alteracao_contrato', 'selEstado', $_SESSION["id_userSecao"]) == 'RO' ? 'SELECTED' : '')?>>RO</option>
                                <option value="RR" <?=(getDadosContrato('dados_alteracao_contrato', 'selEstado', $_SESSION["id_userSecao"]) == 'RR' ? 'SELECTED' : '')?>>RR</option>
                                <option value="RS" <?=(getDadosContrato('dados_alteracao_contrato', 'selEstado', $_SESSION["id_userSecao"]) == 'RS' ? 'SELECTED' : '')?>>RS</option>
                                <option value="SC" <?=(getDadosContrato('dados_alteracao_contrato', 'selEstado', $_SESSION["id_userSecao"]) == 'SC' ? 'SELECTED' : '')?>>SC</option>
                                <option value="SE" <?=(getDadosContrato('dados_alteracao_contrato', 'selEstado', $_SESSION["id_userSecao"]) == 'SE' ? 'SELECTED' : '')?>>SE</option>
                                <option value="SP" <?=(getDadosContrato('dados_alteracao_contrato', 'selEstado', $_SESSION["id_userSecao"]) == 'SP' || getDadosContrato('dados_alteracao_contrato', 'txtCidade', $_SESSION["id_userSecao"]) == '' ? 'SELECTED' : '')?>>SP</option>
                            	<option value="TO" <?=(getDadosContrato('dados_alteracao_contrato', 'selEstado', $_SESSION["id_userSecao"]) == 'TO' ? 'SELECTED' : '')?>>TO</option>
                    		</select>
						</td>
                    </tr>
                </table>
                <br />
            </div>



            <label><input type="checkbox" name="cheExcluirAtividade" id="cheExcluirAtividade" onClick="javascript:abreDiv('exclui_cnae'); " <?=(getDadosContrato('dados_alteracao_contrato', 'cheExcluirAtividade', $_SESSION["id_userSecao"]) == 'on' ? 'checked' : '')?> /> Exclusão de atividade</label><br />

			<? // 	FORMULARIO DE EXCLUIR ATIVIDADE ?>
            <div style="margin-left:17px; margin-top:10px; margin-bottom:20px; <?=(getDadosContrato('dados_alteracao_contrato', 'cheExcluirAtividade', $_SESSION["id_userSecao"]) == 'on' ? 'display:block' : 'display:none')?>" id="exclui_cnae">

				<div class="destaqueAzul" style="margin-bottom:10px">Selecione as atividades que deseja excluir</div>
				<?php
					$sql = "SELECT * FROM dados_da_empresa_codigos WHERE id='" . $_SESSION["id_userSecao"] . "' ORDER BY idCodigo ASC";
					$resultado = mysql_query($sql)
					or die (mysql_error());
					
					$campo = 1;
					
					while ($linha=mysql_fetch_array($resultado)) {
						$sql2 = "SELECT denominacao FROM cnae WHERE REPLACE(REPLACE(REPLACE(cnae,'.',''),'-',''),'/','')='" . str_replace(array("/","-","."),"",$linha["cnae"]) . "' LIMIT 0, 1";
						$resultado2 = mysql_query($sql2)
						or die (mysql_error());
						$linha2=mysql_fetch_array($resultado2);
                ?>
					<label><input type="checkbox" name="cheCnaeExcluso<?=$campo?>" id="cheCnaeExcluso<?=$campo?>" <?=(getDadosContrato('dados_alteracao_contrato', 'cheCnaeExcluso'.$campo, $_SESSION["id_userSecao"]) == 'on' ? 'checked' : '')?> /> <?=$linha2["denominacao"]?></label><br />
<?php 
						$campo = $campo + 1;
					}
?>
					<input type="hidden" name="hidTotalCnaeExistente" id="hidTotalCnaeExistente" value="<?=mysql_num_rows($resultado)?>" />
					<input type="hidden" name="hidTotalCnaeExcluido" id="hidTotalCnaeExcluido" value="0" />
				</div>



                <label><input type="checkbox" name="cheIncluirAtividade" id="cheIncluirAtividade" onClick="javascript:abreDiv('novo_cnae'); " <?=(getDadosContrato('dados_alteracao_contrato', 'cheIncluirAtividade', $_SESSION["id_userSecao"]) == 'on' ? 'checked' : '')?> /> Inclusão de atividade</label><br />


				<? // 	FORMULARIO DE INCLUIR ATIVIDADE ?>
                <div style="margin-left:17px; <?=(getDadosContrato('dados_alteracao_contrato', 'cheIncluirAtividade', $_SESSION["id_userSecao"]) == 'on' ? 'display:block' : 'display:none')?>" id="novo_cnae">

                    <div class="destaqueAzul" style="margin-top:10px; margin-bottom:10px">Digite o código CNAE da nova atividade:&nbsp;&nbsp;&nbsp;</div>
 
                        <table cellpadding="3" cellspacing="0" border="0" width="100%" style="margin-left:-3px">
                        	<tr>
                            	<td width="80" valign="top" >Código CNAE:</td>
                                <td>
                                	<div id="content">

<?
										$novoCNAE = mysql_query("SELECT * FROM dados_alteracao_contrato WHERE nome_campo like 'txtCodigoCNAE%' AND id='" . $_SESSION["id_userSecao"] . "' ORDER BY nome_campo ASC")
										or die (mysql_error());
										
										$totalCNAEs = 1;
										if(mysql_num_rows($novoCNAE)>1){										
											while ($rsNovoCNAE=mysql_fetch_array($novoCNAE)) {
?>
                                            <div id="item<?=$totalCNAEs?>">
                                                <div style="float:left; margin-right:5px">
                                                    <input name="txtCodigoCNAE<?=$totalCNAEs?>" id="txtCodigoCNAE<?=$totalCNAEs?>" type="text" style="width:75px;" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtCodigoCNAE'.$totalCNAEs, $_SESSION["id_userSecao"]))?>" maxlength="15" class="campoCNAE" onBlur="<? // consultaBanco('meus_dados_empresa_consulta_cnae.php?codigo='+this.value, 'atividade<?=$totalCNAEs>');?>" alt="Código CNAE" />
                                                </div>
                                                <div id="atividade<?=$totalCNAEs?>" style="float:left; margin-top:5px">
    
                                                </div>      
                                                <div style="clear:both; height:0px"></div>
                                            </div> 

<?
											$totalCNAEs++;
											}
										}else{
					
?>
                                    
                                        <div id="item<?=$totalCNAEs?>">
	                                        <div style="float:left; margin-right:5px">
                                            	<input name="txtCodigoCNAE<?=$totalCNAEs?>" id="txtCodigoCNAE<?=$totalCNAEs?>" type="text" style="width:75px;" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtCodigoCNAE'.$totalCNAEs, $_SESSION["id_userSecao"]))?>" maxlength="15" class="campoCNAE" onBlur="<? // consultaBanco('meus_dados_empresa_consulta_cnae.php?codigo='+this.value, 'atividade<?=$totalCNAEs>');?>" alt="Código CNAE" />
                                            </div>
                        					<div id="atividade<?=$totalCNAEs?>" style="float:left; margin-top:5px">

											</div>      
					                        <div style="clear:both; height:0px"></div>
                                        </div> 

<?
										}
					
?>


                                    </div>  
			                        <a href="<? // javascript:addCNAE();?>#" class="btAdicionaCNAE">Adicionar novo CNAE</a> | <a href="<? // javascript:removeCNAE();?>#" class="btRemoveCNAE">Remover CNAE</a>
			                        <input type="hidden" id="countCNAEIncluso" name="countCNAEIncluso" value="<?=$totalCNAEs?>">         
                                </td>
							</tr>
						</table>

						<div style="margin-top:10px; margin-bottom:20px">
                            <ul>
                                <li><a href="http://www.cnae.ibge.gov.br/" target="_blank">Pesquisa de código de atividade do CNAE</a></li>
                                <li><a href="http://cnae-simples.net/" target="_blank">Verificar se a nova atividade pode pode ser enquadrada no Simples Nacional</a>.</li>
                            </ul>
						</div>
					</div>



					<label><input type="checkbox" name="cheExcluirSocio" id="cheExcluirSocio" onClick="javascript:abreDiv('exclui_socio');bloqueiaAletracaoCotas();reapareceSocioExcluido();" <?=(getDadosContrato('dados_alteracao_contrato', 'cheExcluirSocio', $_SESSION["id_userSecao"]) == 'on' ? 'checked' : '')?> /> Exclusão de sócio</label><br />

					<? // 	FORMULARIO DE EXCLUIR SOCIO ?>
                    <div style="margin-left:17px; margin-top:10px; margin-bottom:20px; <?=(getDadosContrato('dados_alteracao_contrato', 'cheExcluirSocio', $_SESSION["id_userSecao"]) == 'on' ? 'display:block' : 'display:none')?>" id="exclui_socio">
	                    <div class="destaqueAzul" style="margin-bottom:10px">Selecione os sócios que deseja excluir</div>
						<?php
                        $sql = "SELECT * FROM dados_do_responsavel WHERE id='" . $_SESSION["id_userSecao"] . "' ORDER BY idSocio ASC";
                        $resultado = mysql_query($sql)
                        or die (mysql_error());
                        
                        $campo = 1;
                        
                        while ($linha=mysql_fetch_array($resultado)) {
                        ?>
							<label><input type="checkbox" name="cheSocioExcluso<?=$campo?>" id="cheSocioExcluso<?=$campo?>" onClick="javaScript:someSocioExcluido(<?=$campo?>, this)" <?=(getDadosContrato('dados_alteracao_contrato', 'cheSocioExcluso'.$campo.'', $_SESSION["id_userSecao"]) == 'on' ? 'checked' : '')?>/> <?=$linha["nome"]?></label><br />
<?php 
							$campo = $campo + 1;
						}
?>
						<input type="hidden" name="hidTotalSocioExcluido" id="hidTotalSocioExcluido" value="0" />
					</div>




                    <label><input type="checkbox" name="cheIncluirSocio" id="cheIncluirSocio" onClick="javascript:abreDiv('novo_socio');exibeNovosSocios();bloqueiaAletracaoCotas();" <?=(getDadosContrato('dados_alteracao_contrato', 'cheIncluirSocio', $_SESSION["id_userSecao"]) == 'on' ? 'checked' : '')?> /> Inclusão de novo sócio</label><br />

					<? // 	FORMULARIO DE MUDANÇA DE RAZAO SOCIAL ?>
                    <div style="margin-top:10px; margin-bottom:20px; <?=(getDadosContrato('dados_alteracao_contrato', 'cheIncluirSocio', $_SESSION["id_userSecao"]) == 'on' ? 'display:block' : 'display:none')?>" id="novo_socio">

                        <div id="form_socio">
                        
<?
							// CHECA SE HÁ ALGUM NOVO SÓCIO NA TABELA COM OS DADOS DO CONTRATO SALVO
							$novoSocio = mysql_query("SELECT * FROM dados_alteracao_contrato WHERE nome_campo like 'txtNome%' AND nome_campo not like 'txtNomeT%' AND id='" . $_SESSION["id_userSecao"] . "' ORDER BY nome_campo ASC")
							or die (mysql_error());
							
							// MONTAR A LISTA DE FORMULÁRIOS PARA OS SÓCIOS LOCALIZADOS NA TABELA DE EDIÇÃO
							$totalSOCIOs = 1;
							if(mysql_num_rows($novoSocio)>1){
															
								while ($rsNovoSocio=mysql_fetch_array($novoSocio)) {
									
									$ufSocio = getDadosContrato('dados_alteracao_contrato', 'selEstadoSocio'.$totalSOCIOs, $_SESSION["id_userSecao"]);
?>

                  <div id="socio<?=$totalSOCIOs?>">
                      <table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
                          <tr>
                              <td align="right" valign="middle" class="formTabela">Nome:</td>
                              <td class="formTabela" width="300">
                                  <input name="txtNome<?=$totalSOCIOs?>" type="text" id="txtNome<?=$totalSOCIOs?>" style="width:300px" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtNome'.$totalSOCIOs, $_SESSION["id_userSecao"]))?>" maxlength="200" alt="Nome do novo sócio" onBlur="javascript:mudaNomeSocio(<?=$totalSOCIOs?>)" onKeyUp="javascript:mudaNomeSocio(<?=$totalSOCIOs?>)" />
                              </td>
                          </tr>
                          <tr>
                              <td align="right" valign="middle" class="formTabela">Nacionalidade:</td>
                              <td class="formTabela">
                                  <input name="txtNacionalidade<?=$totalSOCIOs?>" type="text" id="txtNacionalidade<?=$totalSOCIOs?>" style="width:300px" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtNacionalidade'.$totalSOCIOs, $_SESSION["id_userSecao"]))?>" maxlength="200" alt="Nacionalidade do novo sócio" />
                              </td>
                          </tr>
                          <tr>
                              <td align="right" valign="middle" class="formTabela">Sexo:</td>
                              <td class="formTabela">
                                  <label style="margin-right:15px"><input type="radio" name="radSexoSocio<?=$totalSOCIOs?>" id="radSexo1Socio<?=$totalSOCIOs?>" value="Masculino" <?=(getDadosContrato('dados_alteracao_contrato', 'radSexoSocio'.$totalSOCIOs, $_SESSION["id_userSecao"]) == 'Masculino' ? 'CHECKED' : '')?> /> Masculino</label>
                                  <label><input type="radio" name="radSexoSocio<?=$totalSOCIOs?>" id="radSexo2Socio<?=$totalSOCIOs?>" value="Feminino" <?=(getDadosContrato('dados_alteracao_contrato', 'radSexoSocio'.$totalSOCIOs, $_SESSION["id_userSecao"]) == 'Feminino' ? 'CHECKED' : '')?> /> Feminino</label>
                              </td>
                          </tr>
                          <tr>
                              <td align="right" valign="middle" class="formTabela">Estado Civil:</td>
                              <td class="formTabela">
                                  <input name="txtEstadoCivil<?=$totalSOCIOs?>" type="text" id="txtEstadoCivil<?=$totalSOCIOs?>" style="width:300px" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtEstadoCivil'.$totalSOCIOs, $_SESSION["id_userSecao"]))?>" maxlength="200" alt="Estado Civil do novo sócio"/>
                              </td>
                          </tr>
                          <tr>
                              <td align="right" valign="middle" class="formTabela">Profissão:</td>
                              <td class="formTabela">
                                  <input name="txtProfissao<?=$totalSOCIOs?>" type="text" id="txtProfissao<?=$totalSOCIOs?>" style="width:300px" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtProfissao'.$totalSOCIOs, $_SESSION["id_userSecao"]))?>" maxlength="200" alt="Profissão do novo sócio"/>
                              </td>
                          </tr>
                          <tr>
                              <td align="right" valign="middle" class="formTabela">CPF</td>
                              <td class="formTabela">
                                  <input name="txtCPF<?=$totalSOCIOs?>" type="text" id="txtCPF<?=$totalSOCIOs?>" style="width:125px" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtCPF'.$totalSOCIOs, $_SESSION["id_userSecao"]))?>" maxlength="14" alt="CPF do novo sócio" /><span style="font-size:10px">Ex.: 999.999.999-99</span>
                              </td>
                          </tr>
                          <tr>
                              <td align="right" valign="middle" class="formTabela">RG:</td>
                              <td class="formTabela">
                                  <input name="txtRG<?=$totalSOCIOs?>" type="text" id="txtRG<?=$totalSOCIOs?>" style="width:300px" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtRG'.$totalSOCIOs, $_SESSION["id_userSecao"]))?>" maxlength="17" alt="RG do novo sócio" />
                              </td>
                          </tr>
                          <tr>
                              <td align="right" valign="middle" class="formTabela">Orgão Expeditor:</td>
                              <td class="formTabela">
                                  <input name="txtOrgaoExpedidor<?=$totalSOCIOs?>" type="text" id="txtOrgaoExpedidor<?=$totalSOCIOs?>" style="width:300px" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtOrgaoExpedidor'.$totalSOCIOs, $_SESSION["id_userSecao"]))?>" maxlength="250" alt="Orgão Expeditor do novo sócio" />
                              </td>
                          </tr>
                          <tr>
                              <td align="right" valign="middle" class="formTabela">Endere&ccedil;o:</td>
                              <td class="formTabela">
                                  <input name="txtEnderecoSocio<?=$totalSOCIOs?>" id="txtEnderecoSocio<?=$totalSOCIOs?>" type="text" style="width:300px" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtEnderecoSocio'.$totalSOCIOs, $_SESSION["id_userSecao"]))?>" maxlength="250" alt="Endereço do novo sócio" />
                              </td>
                          </tr>
                          <tr>
                              <td align="right" valign="middle" class="formTabela">Bairro:</td>
                              <td class="formTabela">
                                  <input name="txtBairroSocio<?=$totalSOCIOs?>" id="txtBairroSocio<?=$totalSOCIOs?>" type="text" style="width:300px" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtBairroSocio'.$totalSOCIOs, $_SESSION["id_userSecao"]))?>" maxlength="200" alt="Complemento do novo sócio" />
                              </td>
                          </tr>
                          <tr>
                              <td align="right" valign="middle" class="formTabela">CEP:</td>
                              <td class="formTabela">
                                  <input name="txtCEPSocio<?=$totalSOCIOs?>" type="text" id="txtCEPSocio<?=$totalSOCIOs?>" style="width:70px" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtCEPSocio'.$totalSOCIOs, $_SESSION["id_userSecao"]))?>" maxlength="9" alt="CEP do novo sócio" /><span style="font-size:10px">Ex.: 99999-999</span>
                              </td>
                          </tr>
                          <tr>
                              <td align="right" valign="middle" class="formTabela">Cidade:</td>
                              <td class="formTabela">
                                  <input name="txtCidadeSocio<?=$totalSOCIOs?>" type="text" id="txtCidadeSocio<?=$totalSOCIOs?>" style="width:300px" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtCidadeSocio'.$totalSOCIOs, $_SESSION["id_userSecao"]))?>" maxlength="200" alt="Cidade do novo sócio" />
                              </td>
                          </tr>
                          <tr>
                              <td align="right" valign="middle" class="formTabela">Estado:</td>
                              <td class="formTabela">
                                  <select name="selEstadoSocio<?=$totalSOCIOs?>" id="selEstadoSocio<?=$totalSOCIOs?>">
                                      <option value="AC" <?=($ufSocio == 'AC' ? 'SELECTED' : '')?>>AC</option>
                                      <option value="AL" <?=($ufSocio == 'AL' ? 'SELECTED' : '')?>>AL</option>
                                      <option value="AM" <?=($ufSocio == 'AM' ? 'SELECTED' : '')?>>AM</option>
                                      <option value="AP" <?=($ufSocio == 'AP' ? 'SELECTED' : '')?>>AP</option>
                                      <option value="BA" <?=($ufSocio == 'BA' ? 'SELECTED' : '')?>>BA</option>
                                      <option value="CE" <?=($ufSocio == 'CE' ? 'SELECTED' : '')?>>CE</option>
                                      <option value="DF" <?=($ufSocio == 'DF' ? 'SELECTED' : '')?>>DF</option>
                                      <option value="ES" <?=($ufSocio == 'ES' ? 'SELECTED' : '')?>>ES</option>
                                      <option value="GO" <?=($ufSocio == 'GO' ? 'SELECTED' : '')?>>GO</option>
                                      <option value="MA" <?=($ufSocio == 'MA' ? 'SELECTED' : '')?>>MA</option>
                                      <option value="MG" <?=($ufSocio == 'MG' ? 'SELECTED' : '')?>>MG</option>
                                      <option value="MS" <?=($ufSocio == 'MS' ? 'SELECTED' : '')?>>MS</option>
                                      <option value="MT" <?=($ufSocio == 'MT' ? 'SELECTED' : '')?>>MT</option>
                                      <option value="PA" <?=($ufSocio == 'PA' ? 'SELECTED' : '')?>>PA</option>
                                      <option value="PB" <?=($ufSocio == 'PB' ? 'SELECTED' : '')?>>PB</option>
                                      <option value="PE" <?=($ufSocio == 'PE' ? 'SELECTED' : '')?>>PE</option>
                                      <option value="PI" <?=($ufSocio == 'PI' ? 'SELECTED' : '')?>>PI</option>
                                      <option value="PR" <?=($ufSocio == 'PR' ? 'SELECTED' : '')?>>PR</option>
                                      <option value="RJ" <?=($ufSocio == 'RJ' ? 'SELECTED' : '')?>>RJ</option>
                                      <option value="RN" <?=($ufSocio == 'RN' ? 'SELECTED' : '')?>>RN</option>
                                      <option value="RO" <?=($ufSocio == 'RO' ? 'SELECTED' : '')?>>RO</option>
                                      <option value="RR" <?=($ufSocio == 'RR' ? 'SELECTED' : '')?>>RR</option>
                                      <option value="RS" <?=($ufSocio == 'RS' ? 'SELECTED' : '')?>>RS</option>
                                      <option value="SC" <?=($ufSocio == 'SC' ? 'SELECTED' : '')?>>SC</option>
                                      <option value="SE" <?=($ufSocio == 'SE' ? 'SELECTED' : '')?>>SE</option>
                                      <option value="SP" <?=($ufSocio == 'SP' || $ufSocio == '' ? 'SELECTED' : '')?>>SP</option>
                                      <option value="TO" <?=($ufSocio == 'TO' ? 'SELECTED' : '')?>>TO</option>
                                  </select>
                              </td>
                          </tr>
                          </table>
                      <br />
                      </div>
<?                                
								$totalSOCIOs++;
								}
								
							}else{
?>
                <div id="socio<?=$totalSOCIOs?>">
                    <table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
                        <tr>
                            <td align="right" valign="middle" class="formTabela">Nome:</td>
                            <td class="formTabela" width="300">
                                <input name="txtNome<?=$totalSOCIOs?>" type="text" id="txtNome<?=$totalSOCIOs?>" style="width:300px" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtNome'.$totalSOCIOs, $_SESSION["id_userSecao"]))?>" maxlength="200" alt="Nome do novo sócio" onBlur="javascript:mudaNomeSocio(<?=$totalSOCIOs?>)" onKeyUp="javascript:mudaNomeSocio(<?=$totalSOCIOs?>)" />
                            </td>
                        </tr>
                        <tr>
                            <td align="right" valign="middle" class="formTabela">Nacionalidade:</td>
                            <td class="formTabela">
                                <input name="txtNacionalidade<?=$totalSOCIOs?>" type="text" id="txtNacionalidade<?=$totalSOCIOs?>" style="width:300px" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtNacionalidade'.$totalSOCIOs, $_SESSION["id_userSecao"]))?>" maxlength="200" alt="Nacionalidade do novo sócio" />
                            </td>
                        </tr>
                        <tr>
                            <td align="right" valign="middle" class="formTabela">Sexo:</td>
                            <td class="formTabela">
                                <label style="margin-right:15px"><input type="radio" name="radSexoSocio<?=$totalSOCIOs?>" id="radSexo1Socio<?=$totalSOCIOs?>" value="Masculino" <?=(getDadosContrato('dados_alteracao_contrato', 'radSexoSocio'.$totalSOCIOs, $_SESSION["id_userSecao"]) == 'Masculino' ? 'CHECKED' : '')?> /> Masculino</label>
                                <label><input type="radio" name="radSexoSocio<?=$totalSOCIOs?>" id="radSexo2Socio<?=$totalSOCIOs?>" value="Feminino" <?=(getDadosContrato('dados_alteracao_contrato', 'radSexoSocio'.$totalSOCIOs, $_SESSION["id_userSecao"]) == 'Feminino' ? 'CHECKED' : '')?> /> Feminino</label>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" valign="middle" class="formTabela">Estado Civil:</td>
                            <td class="formTabela">
                                <input name="txtEstadoCivil<?=$totalSOCIOs?>" type="text" id="txtEstadoCivil<?=$totalSOCIOs?>" style="width:300px" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtEstadoCivil'.$totalSOCIOs, $_SESSION["id_userSecao"]))?>" maxlength="200" alt="Estado Civil do novo sócio"/>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" valign="middle" class="formTabela">Profissão:</td>
                            <td class="formTabela">
                                <input name="txtProfissao<?=$totalSOCIOs?>" type="text" id="txtProfissao<?=$totalSOCIOs?>" style="width:300px" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtProfissao'.$totalSOCIOs, $_SESSION["id_userSecao"]))?>" maxlength="200" alt="Profissão do novo sócio"/>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" valign="middle" class="formTabela">CPF</td>
                            <td class="formTabela">
                                <input name="txtCPF<?=$totalSOCIOs?>" type="text" id="txtCPF<?=$totalSOCIOs?>" style="width:125px" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtCPF'.$totalSOCIOs, $_SESSION["id_userSecao"]))?>" maxlength="14" alt="CPF do novo sócio" class="cpf" /><span style="font-size:10px">Ex.: 999.999.999-99</span>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" valign="middle" class="formTabela">RG:</td>
                            <td class="formTabela">
                                <input name="txtRG<?=$totalSOCIOs?>" type="text" id="txtRG<?=$totalSOCIOs?>" style="width:300px" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtRG'.$totalSOCIOs, $_SESSION["id_userSecao"]))?>" maxlength="17" alt="RG do novo sócio" />
                            </td>
                        </tr>
                        <tr>
                            <td align="right" valign="middle" class="formTabela">Orgão Expeditor:</td>
                            <td class="formTabela">
                                <input name="txtOrgaoExpedidor<?=$totalSOCIOs?>" type="text" id="txtOrgaoExpedidor<?=$totalSOCIOs?>" style="width:300px" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtOrgaoExpedidor'.$totalSOCIOs, $_SESSION["id_userSecao"]))?>" maxlength="250" alt="Orgão Expeditor do novo sócio" />
                            </td>
                        </tr>
                        <tr>
                            <td align="right" valign="middle" class="formTabela">Endere&ccedil;o:</td>
                            <td class="formTabela">
                                <input name="txtEnderecoSocio<?=$totalSOCIOs?>" id="txtEnderecoSocio<?=$totalSOCIOs?>" type="text" style="width:300px" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtEnderecoSocio'.$totalSOCIOs, $_SESSION["id_userSecao"]))?>" maxlength="250" alt="Endereço do novo sócio" />
                            </td>
                        </tr>
                        <tr>
                            <td align="right" valign="middle" class="formTabela">Bairro:</td>
                            <td class="formTabela">
                                <input name="txtBairroSocio<?=$totalSOCIOs?>" id="txtBairroSocio<?=$totalSOCIOs?>" type="text" style="width:300px" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtBairroSocio'.$totalSOCIOs, $_SESSION["id_userSecao"]))?>" maxlength="200" alt="Complemento do novo sócio" />
                            </td>
                        </tr>
                        <tr>
                            <td align="right" valign="middle" class="formTabela">CEP:</td>
                            <td class="formTabela">
                                <input name="txtCEPSocio<?=$totalSOCIOs?>" type="text" id="txtCEPSocio<?=$totalSOCIOs?>" style="width:70px" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtCEPSocio'.$totalSOCIOs, $_SESSION["id_userSecao"]))?>" maxlength="9" alt="CEP do novo sócio" class="cep" /><span style="font-size:10px">Ex.: 99999-999</span>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" valign="middle" class="formTabela">Cidade:</td>
                            <td class="formTabela">
                                <input name="txtCidadeSocio<?=$totalSOCIOs?>" type="text" id="txtCidadeSocio<?=$totalSOCIOs?>" style="width:300px" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtCidadeSocio'.$totalSOCIOs, $_SESSION["id_userSecao"]))?>" maxlength="200" alt="Cidade do novo sócio" />
                            </td>
                        </tr>
                        <tr>
                            <td align="right" valign="middle" class="formTabela">Estado:</td>
                            <td class="formTabela">
<?
$ufSocio = getDadosContrato('dados_alteracao_contrato', 'selEstadoSocio'.$totalSOCIOs, $_SESSION["id_userSecao"]);
?>
                                <select name="selEstadoSocio<?=$totalSOCIOs?>" id="selEstadoSocio<?=$totalSOCIOs?>">
                                    <option value="AC" <?=($ufSocio == 'AC' ? 'SELECTED' : '')?>>AC</option>
                                    <option value="AL" <?=($ufSocio == 'AL' ? 'SELECTED' : '')?>>AL</option>
                                    <option value="AM" <?=($ufSocio == 'AM' ? 'SELECTED' : '')?>>AM</option>
                                    <option value="AP" <?=($ufSocio == 'AP' ? 'SELECTED' : '')?>>AP</option>
                                    <option value="BA" <?=($ufSocio == 'BA' ? 'SELECTED' : '')?>>BA</option>
                                    <option value="CE" <?=($ufSocio == 'CE' ? 'SELECTED' : '')?>>CE</option>
                                    <option value="DF" <?=($ufSocio == 'DF' ? 'SELECTED' : '')?>>DF</option>
                                    <option value="ES" <?=($ufSocio == 'ES' ? 'SELECTED' : '')?>>ES</option>
                                    <option value="GO" <?=($ufSocio == 'GO' ? 'SELECTED' : '')?>>GO</option>
                                    <option value="MA" <?=($ufSocio == 'MA' ? 'SELECTED' : '')?>>MA</option>
                                    <option value="MG" <?=($ufSocio == 'MG' ? 'SELECTED' : '')?>>MG</option>
                                    <option value="MS" <?=($ufSocio == 'MS' ? 'SELECTED' : '')?>>MS</option>
                                    <option value="MT" <?=($ufSocio == 'MT' ? 'SELECTED' : '')?>>MT</option>
                                    <option value="PA" <?=($ufSocio == 'PA' ? 'SELECTED' : '')?>>PA</option>
                                    <option value="PB" <?=($ufSocio == 'PB' ? 'SELECTED' : '')?>>PB</option>
                                    <option value="PE" <?=($ufSocio == 'PE' ? 'SELECTED' : '')?>>PE</option>
                                    <option value="PI" <?=($ufSocio == 'PI' ? 'SELECTED' : '')?>>PI</option>
                                    <option value="PR" <?=($ufSocio == 'PR' ? 'SELECTED' : '')?>>PR</option>
                                    <option value="RJ" <?=($ufSocio == 'RJ' ? 'SELECTED' : '')?>>RJ</option>
                                    <option value="RN" <?=($ufSocio == 'RN' ? 'SELECTED' : '')?>>RN</option>
                                    <option value="RO" <?=($ufSocio == 'RO' ? 'SELECTED' : '')?>>RO</option>
                                    <option value="RR" <?=($ufSocio == 'RR' ? 'SELECTED' : '')?>>RR</option>
                                    <option value="RS" <?=($ufSocio == 'RS' ? 'SELECTED' : '')?>>RS</option>
                                    <option value="SC" <?=($ufSocio == 'SC' ? 'SELECTED' : '')?>>SC</option>
                                    <option value="SE" <?=($ufSocio == 'SE' ? 'SELECTED' : '')?>>SE</option>
                                    <option value="SP" <?=($ufSocio == 'SP' || $ufSocio == '' ? 'SELECTED' : '')?>>SP</option>
                                    <option value="TO" <?=($ufSocio == 'TO' ? 'SELECTED' : '')?>>TO</option>
                                </select>
                            </td>
                        </tr>
                        </table>
                    <br />
                    </div>

<?
							}
?>


                            </div>
							<input type="hidden" id="count" name="skill_count" value="<?=$totalSOCIOs?>">
                    
                            <div style="margin-left:120px; margin-bottom:20px">
                                <a href="#" class="adicionaSocio">Adicionar outro sócio</a> | <a href="javascript:remove()">Remover Sócio</a>
                            </div> 
                            
                        </div>






                        <label><input type="checkbox" name="cheAlterarCapitalSocial" id="cheAlterarCapitalSocial" onClick="javascript:abreDiv('capital')" <?=(getDadosContrato('dados_alteracao_contrato', 'cheAlterarCapitalSocial', $_SESSION["id_userSecao"]) == 'on' ? 'checked' : '')?> /> 
                        Alteração no Capital Social</label><br />

						<? // 	FORMULARIO DE ALTERAR CAPITAL SOCIAL ?>
                        <div id="capital" style="margin:10px 0 10px 17px ; <?=(getDadosContrato('dados_alteracao_contrato', 'cheAlterarCapitalSocial', $_SESSION["id_userSecao"]) == 'on' ? 'display: block' : 'display: none')?>">
							<div class="destaqueAzul" style="margin-bottom:10px"><span class="destaqueAzul" style="margin-bottom:10px">Digite o capital social futuro </span></div>
                            <input class="current" type="text" name="totalReaisFuturo" id="totalReaisFuturo" style="width:60px; margin-bottom:3px" alt="capital social futuro com o valor de cada quota (em reais)" value="<?=(getDadosContrato('dados_alteracao_contrato', 'totalReaisFuturo', $_SESSION["id_userSecao"]))?>" /> 
                            Valor de cada cota em Reais<br />
                            <input class="inteiro" type="text" name="totalCotasFuturo" id="totalCotasFuturo" style="width:60px" alt="capital social futuro com o total de cotas" value="<?=(getDadosContrato('dados_alteracao_contrato', 'totalCotasFuturo', $_SESSION["id_userSecao"]))?>" /> Total de Cotas
                        </div>



                        
                        <label><input type="checkbox" name="cheAlterarCotas" id="cheAlterarCotas" onClick="javascript:abreDiv('cotas');" <?=(getDadosContrato('dados_alteracao_contrato', 'cheAlterarCotas', $_SESSION["id_userSecao"]) == 'on') || (getDadosContrato('dados_alteracao_contrato', 'cheIncluirSocio', $_SESSION["id_userSecao"]) == 'on') || (getDadosContrato('dados_alteracao_contrato', 'cheExcluirSocio', $_SESSION["id_userSecao"]) == 'on') ? 'checked' : ''?> <?=(getDadosContrato('dados_alteracao_contrato', 'cheIncluirSocio', $_SESSION["id_userSecao"]) == 'on') || (getDadosContrato('dados_alteracao_contrato', 'cheExcluirSocio', $_SESSION["id_userSecao"]) == 'on') ? 'DISABLED' : ''?> /> Alteração na distribuição de cotas</label><br />

						<? // 	FORMULARIO DE ALTERAR COTAS ?>
                        <div id="cotas" style="margin-left:17px; margin-top:10px; margin-bottom:20px; <?=(getDadosContrato('dados_alteracao_contrato', 'cheAlterarCotas', $_SESSION["id_userSecao"]) == 'on' ? 'display:block' : 'display:none')?>">

							<div class="destaqueAzul" style="margin-bottom:10px">Selecione os sócios que transferem cotas</div>
							<?php
                            
                            $sql = "SELECT * FROM dados_do_responsavel WHERE id='" . $_SESSION["id_userSecao"] . "' ORDER BY idSocio ASC";
                            $resultado = mysql_query($sql)
                            or die (mysql_error());
                            
                            $campo = 1;
                            
                            
                            while ($linha=mysql_fetch_array($resultado)) {
                            ?>
								<label><input type="checkbox" name="cheSocioTransfere<?=$campo?>" id="cheSocioTransfere<?=$campo?>" onClick="javascript:abreDiv('divSocioTransfereCota<?=$campo?>')" <?=(getDadosContrato('dados_alteracao_contrato', 'cheSocioTransfere'.$campo, $_SESSION["id_userSecao"]) == 'on' || getDadosContrato('dados_alteracao_contrato', 'cheSocioExcluso'.$campo, $_SESSION["id_userSecao"]) == 'on' ? 'checked' : '')?> /> <?=$linha["nome"]?></label><br />

								<div id="divSocioTransfereCota<?=$campo?>" style="margin-left:17px; margin-bottom:10px; margin-top:10px; <?=(getDadosContrato('dados_alteracao_contrato', 'cheSocioTransfere'.$campo, $_SESSION["id_userSecao"]) == 'on' || getDadosContrato('dados_alteracao_contrato', 'cheSocioExcluso'.$campo, $_SESSION["id_userSecao"]) == 'on' ? 'display:block' : 'display:none')?>">
									Transfere:
									<?php 
                                    $resultado2 = mysql_query($sql)
                                    or die (mysql_error());
                                    
                                    $listaSocios = 1;
                                    mysql_data_seek($resultado2, '0');
                                    
                                    while ($linha2=mysql_fetch_array($resultado2)) {
                                        if ($linha["idSocio"] != $linha2["idSocio"]) {
                                    ?>
                                        <div id="divSocio<?=$campo?>TransfereCotaPara<?=$listaSocios?>" style="margin-bottom:3px; display:block">
                                          <input type="text" name="txtSocio<?=$campo?>TransfereCotaPara<?=$listaSocios?>" id="txtSocio<?=$campo?>TransfereCotaPara<?=$listaSocios?>" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtSocio'.$campo.'TransfereCotaPara'.$listaSocios, $_SESSION["id_userSecao"]))?>" style="width:60px" class="campoTransfereCotasPara inteiro" pos="<?=$campo?>" /> 
                                          quotas para <?=$linha2["nome"]?>  
                                        </div>
									<?php
										} 
										$listaSocios = $listaSocios + 1;
									}

									// CHECA SE HÁ ALGUM NOVO SÓCIO NA TABELA COM OS DADOS DO CONTRATO SALVO
									$novoSocio = mysql_query("SELECT * FROM dados_alteracao_contrato WHERE nome_campo like 'txtNome%' AND nome_campo not like 'txtNomeT%' AND id='" . $_SESSION["id_userSecao"] . "' ORDER BY nome_campo ASC")
									or die (mysql_error());
									$listaNovosSocios = 1;
?>



                                    <div id="transfereParaNovosSocios<?=$campo?>" style=" <?=(getDadosContrato('dados_alteracao_contrato', 'cheIncluirSocio', $_SESSION["id_userSecao"]) == 'on' ? 'display:block' : 'display:none')?>">
        
        <?
                                    if(mysql_num_rows($novoSocio) > 0){
                                        // MONTAR A LISTA DE CAMPOS DE TEXTO PARA PREENCHIMENTO DE QUOTAS
                                        while ($rsNovoSocio=mysql_fetch_array($novoSocio)) {
        ?>
                                        <div id="Socio<?=$campo?>novoSocio<?=$listaNovosSocios?>" style="margin-bottom:3px">
                                            <input name="txtSocio<?=$campo?>TransfereCotaParaNovo<?=$listaNovosSocios?>" id="txtSocio<?=$campo?>TransfereCotaParaNovo<?=$listaNovosSocios?>" type='text' style='width:60px; float:left' value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtSocio'.$campo.'TransfereCotaParaNovo1', $_SESSION["id_userSecao"]))?>"  class="campoTransfereCotasPara inteiro" pos="<?=$campo?>" />
                                            <div id="Socio<?=$campo?>nomeSocio<?=$listaNovosSocios?>" style="float:left; margin-top:3px; margin-left:3px">quotas para <?=$rsNovoSocio["valor_campo"]?></div>
                                            <div style="clear:both"></div>
                                        </div>
                                        <script>
                                            $(document).ready(function(e) {
                                                if($('#cheSocioExcluso<?=$listaNovosSocios?>').attr('checked')){
                                                    $('#divDistribuicaoFuturaSocio<?=$listaNovosSocios?>').css('display','none');
                                                }
                                            });
                                        </script>
        <?
                                        $listaNovosSocios = $listaNovosSocios + 1;
                                        }
                                    }else{
        ?>
                                        <div id="Socio<?=$campo?>novoSocio<?=$listaNovosSocios?>" style="margin-bottom:3px">
                                            <input name="txtSocio<?=$campo?>TransfereCotaParaNovo<?=$listaNovosSocios?>" id="txtSocio<?=$campo?>TransfereCotaParaNovo<?=$listaNovosSocios?>" type='text' style='width:60px; float:left' value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtSocio'.$campo.'TransfereCotaParaNovo'.$listaNovosSocios, $_SESSION["id_userSecao"]))?>" class="campoTransfereCotasPara inteiro" pos="<?=$campo?>" />
                                            <div id="Socio<?=$campo?>nomeSocio<?=$listaNovosSocios?>" style="float:left; margin-top:3px; margin-left:3px">quotas para</div>
                                            <div style="clear:both"></div>
                                        </div>
                                               
        <?	
                                    }
                                    ?>
            
                                    </div>


                                    <input type="hidden" name="hidTotalSocio<?=$campo?>Transfere" id="hidTotalSocio<?=$campo?>Transfere" value="<?=(getDadosContrato('dados_alteracao_contrato', 'hidTotalSocio'.$campo.'Transfere', $_SESSION["id_userSecao"]))?>" alt="<?=$linha["nome"]?>" />

								</div>
<?php 
								$campo = $campo + 1;
							}
?>
<br />


							<div class="destaqueAzul" style="margin-bottom:10px"> Distribuição societaria futura</div>
							<?php 
                            $resultado = mysql_query($sql)
                            or die (mysql_error());
                            
                            $listaSocios = 1;
                            
                            while ($linha=mysql_fetch_array($resultado)) {
                            ?>
    
                                <div id="divDistribuicaoFuturaSocio<?=$listaSocios?>" style="margin-bottom:3px; display:block">
                                    <input class="inteiro" type="text" name="txtDistribuicaoFuturaSocio<?=$listaSocios?>" id="txtDistribuicaoFuturaSocio<?=$listaSocios?>" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtDistribuicaoFuturaSocio'.$listaSocios.'', $_SESSION["id_userSecao"]))?>" style="width:60px" alt="distribuição societária futura com as quotas de <?=$linha["nome"]?>" /> 
                                    quotas para <?=$linha["nome"]?>
                                </div>
                                <script>
                                	$(document).ready(function(e) {
                                        if($('#cheSocioExcluso<?=$listaSocios?>').attr('checked')){
											$('#divDistribuicaoFuturaSocio<?=$listaSocios?>').css('display','none');
										}
                                    });
                                </script>
    
							<?php  
								
	                            $listaSocios = $listaSocios + 1;
                            }
							

							// CHECA SE HÁ ALGUM NOVO SÓCIO NA TABELA COM OS DADOS DO CONTRATO SALVO
							$novoSocio = mysql_query("SELECT * FROM dados_alteracao_contrato WHERE nome_campo like 'txtNome%' AND nome_campo not like 'txtNomeT%' AND id='" . $_SESSION["id_userSecao"] . "' ORDER BY nome_campo ASC")
							or die (mysql_error());
							$listaNovosSocios = 1;
?>
                            <div id="distribuicaoParaNovoSocio" style=" <?=(getDadosContrato('dados_alteracao_contrato', 'cheIncluirSocio', $_SESSION["id_userSecao"]) == 'on' ? 'display:block' : 'display:none')?>">
<?
							if(mysql_num_rows($novoSocio) > 0){
								// MONTAR A LISTA DE CAMPOS DE TEXTO PARA PREENCHIMENTO DE QUOTAS
								while ($rsNovoSocio=mysql_fetch_array($novoSocio)) {
?>
                                <div id="divDistribuicaoFuturaNovoSocio<?=$listaNovosSocios?>" style="margin-bottom:3px">
                                    <input class="inteiro" name="txtDistribuicaoFuturaNovoSocio<?=$listaNovosSocios?>" id="txtDistribuicaoFuturaNovoSocio<?=$listaNovosSocios?>" type='text' style='width:60px; float:left' value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtDistribuicaoFuturaNovoSocio'.$listaNovosSocios, $_SESSION["id_userSecao"]))?>">
                                    <div id="distribuicaoNomeSocio<?=$listaNovosSocios?>" style="float:left; margin-top:3px; margin-left:3px">quotas para <?=$rsNovoSocio["valor_campo"]?></div>
                                    <div style="clear:both"></div>
                                </div>
                                <script>
                                	$(document).ready(function(e) {
                                        if($('#cheSocioExcluso<?=$listaNovosSocios?>').attr('checked')){
											$('#divDistribuicaoFuturaSocio<?=$listaNovosSocios?>').css('display','none');
										}
                                    });
                                </script>
<?
								$listaNovosSocios = $listaNovosSocios + 1;
								}
							}else{
?>
                                <div id="divDistribuicaoFuturaNovoSocio<?=$listaNovosSocios?>" style="margin-bottom:3px">
                                    <input class="inteiro" name="txtDistribuicaoFuturaNovoSocio<?=$listaNovosSocios?>" id="txtDistribuicaoFuturaNovoSocio<?=$listaNovosSocios?>" type='text' style='width:60px; float:left' value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtDistribuicaoFuturaNovoSocio'.$listaNovosSocios, $_SESSION["id_userSecao"]))?>">
                                    <div id="distribuicaoNomeSocio<?=$listaNovosSocios?>" style="float:left; margin-top:3px; margin-left:3px">quotas para </div>
                                    <div style="clear:both"></div>
                                </div>

<?	
							}
                            ?>
    
                            </div>
                        
                        </div>

<br />




						<div class="destaqueAzul" style="margin-bottom:10px"> Selecione a quem caberá a administração da sociedade  </div>

<?
								$resultado = mysql_query($sql)
								or die (mysql_error());
								
								$listaSocios = 1;
								
								while ($linha=mysql_fetch_array($resultado)) {
?>
                                <div id="divAdministracaoFuturaSocio<?=$listaSocios?>" style="display:<?=getDadosContrato('dados_alteracao_contrato', 'cheSocioExcluso'.$listaSocios.'', $_SESSION["id_userSecao"]) == 'on' ? 'none' : 'block'?>">
                                    <label><input type="checkbox" name="cheAdministracaoSocio<?=$listaSocios?>" id="cheAdministracaoSocio<?=$listaSocios?>" <?=(getDadosContrato('dados_alteracao_contrato', 'cheAdministracaoSocio'.$listaSocios, $_SESSION["id_userSecao"]) == 'on' ? 'checked' : '' )?> /> <?=$linha["nome"]?></label>
                                </div>

<?php  
										$listaSocios = $listaSocios + 1;

								}

						// CHECA SE HÁ ALGUM NOVO SÓCIO NA TABELA COM OS DADOS DO CONTRATO SALVO
						$novoSocio = mysql_query("SELECT * FROM dados_alteracao_contrato WHERE nome_campo like 'txtNome%' AND nome_campo not like 'txtNomeT%' AND id='" . $_SESSION["id_userSecao"] . "' ORDER BY nome_campo ASC")
						or die (mysql_error());
						$listaNovosSocios = 1;
							
?>
                        <div id="AdministracaoParaNovoSocio" style=" <?=(getDadosContrato('dados_alteracao_contrato', 'cheIncluirSocio', $_SESSION["id_userSecao"]) == 'on' ? 'display:block' : 'display:none')?>">
<?							
						if(mysql_num_rows($novoSocio)>0){
							// MONTAR A LISTA DE CHECKBOX PARA QUEM CABERÁ ADMINISTRAÇÃO DE NOVOS SÓCIOS
							while ($rsNovoSocio=mysql_fetch_array($novoSocio)) {
?>
                                <div id="divAdministracaoNovoSocio<?=$listaNovosSocios?>">
                                    <label><input name="cheAdministracaoNovoSocio<?=$listaNovosSocios?>" id="cheAdministracaoNovoSocio<?=$listaNovosSocios?>" type='checkbox' style='float:left' <?=(getDadosContrato('dados_alteracao_contrato', 'cheAdministracaoNovoSocio'.$listaNovosSocios, $_SESSION["id_userSecao"]) == 'on' ? 'checked' : '' )?> />
                                    <div id="AdministracaoNomeSocio<?=$listaNovosSocios?>" style="float:left; margin-left:3px"> <?=$rsNovoSocio["valor_campo"]?></div>
                                    </label>
                                    <div style="clear:both"></div>
                                </div>
<?
								$listaNovosSocios = $listaNovosSocios + 1;
							}
						}else{
?>
                            <div id="divAdministracaoNovoSocio<?=$listaNovosSocios?>">
                                <label><input name="cheAdministracaoNovoSocio<?=$listaNovosSocios?>" id="cheAdministracaoNovoSocio<?=$listaNovosSocios?>" type='checkbox' style='float:left' <?=(getDadosContrato('dados_alteracao_contrato', 'cheAdministracaoNovoSocio'.$listaNovosSocios, $_SESSION["id_userSecao"]) == 'on' ? 'checked' : '' )?> />
                                <div id="AdministracaoNomeSocio<?=$listaNovosSocios?>" style="float:left; margin-left:3px"> </div>
                                </label>
                                <div style="clear:both"></div>
                            </div>

<?
						}
?>
						</div>
						<input type="hidden" name="hidTotalSocioFinal" id="hidTotalSocioFinal" value="0"/><input type="hidden" name="hidTotalAdministracao" id="hidTotalAdministracao" value="0"/><br />

						<div class="destaqueAzul" style="margin-bottom:10px"> Comarca a que sua cidade pertence</div>
<?
$cidade = getDadosCliente('dados_da_empresa', 'cidade', $_SESSION["id_userSecao"]);
$estado = getDadosCliente('dados_da_empresa', 'estado', $_SESSION["id_userSecao"]);

$comarca = getDadosContrato('dados_alteracao_contrato', 'txtComarca', $_SESSION["id_userSecao"]);
$UFcomarca = getDadosContrato('dados_alteracao_contrato', 'selUFComarca', $_SESSION["id_userSecao"]);

if($UFcomarca == ""){
	$UFcomarca = $estado;
}
?>
            <input name="txtComarca" type="text" id="txtComarca" style="width:300px" value="<?=($comarca == "" ? $cidade : $comarca)?>" maxlength="125" alt="Comarca" />&nbsp;&nbsp;
            <select name="selUFComarca" id="selUFComarca">
                <option value="AC" <?=($UFcomarca == 'AC' ? 'SELECTED' : '')?>>AC</option>
                <option value="AL" <?=($UFcomarca == 'AL' ? 'SELECTED' : '')?>>AL</option>
                <option value="AM" <?=($UFcomarca == 'AM' ? 'SELECTED' : '')?>>AM</option>
                <option value="AP" <?=($UFcomarca == 'AP' ? 'SELECTED' : '')?>>AP</option>
                <option value="BA" <?=($UFcomarca == 'BA' ? 'SELECTED' : '')?>>BA</option>
                <option value="CE" <?=($UFcomarca == 'CE' ? 'SELECTED' : '')?>>CE</option>
                <option value="DF" <?=($UFcomarca == 'DF' ? 'SELECTED' : '')?>>DF</option>
                <option value="ES" <?=($UFcomarca == 'ES' ? 'SELECTED' : '')?>>ES</option>
                <option value="GO" <?=($UFcomarca == 'GO' ? 'SELECTED' : '')?>>GO</option>
                <option value="MA" <?=($UFcomarca == 'MA' ? 'SELECTED' : '')?>>MA</option>
                <option value="MG" <?=($UFcomarca == 'MG' ? 'SELECTED' : '')?>>MG</option>
                <option value="MS" <?=($UFcomarca == 'MS' ? 'SELECTED' : '')?>>MS</option>
                <option value="MT" <?=($UFcomarca == 'MT' ? 'SELECTED' : '')?>>MT</option>
                <option value="PA" <?=($UFcomarca == 'PA' ? 'SELECTED' : '')?>>PA</option>
                <option value="PB" <?=($UFcomarca == 'PB' ? 'SELECTED' : '')?>>PB</option>
                <option value="PE" <?=($UFcomarca == 'PE' ? 'SELECTED' : '')?>>PE</option>
                <option value="PI" <?=($UFcomarca == 'PI' ? 'SELECTED' : '')?>>PI</option>
                <option value="PR" <?=($UFcomarca == 'PR' ? 'SELECTED' : '')?>>PR</option>
                <option value="RJ" <?=($UFcomarca == 'RJ' ? 'SELECTED' : '')?>>RJ</option>
                <option value="RN" <?=($UFcomarca == 'RN' ? 'SELECTED' : '')?>>RN</option>
                <option value="RO" <?=($UFcomarca == 'RO' ? 'SELECTED' : '')?>>RO</option>
                <option value="RR" <?=($UFcomarca == 'RR' ? 'SELECTED' : '')?>>RR</option>
                <option value="RS" <?=($UFcomarca == 'RS' ? 'SELECTED' : '')?>>RS</option>
                <option value="SC" <?=($UFcomarca == 'SC' ? 'SELECTED' : '')?>>SC</option>
                <option value="SE" <?=($UFcomarca == 'SE' ? 'SELECTED' : '')?>>SE</option>
                <option value="SP" <?=($UFcomarca == 'SP' || $UFcomarca == '' ? 'SELECTED' : '')?>>SP</option>
                <option value="TO" <?=($UFcomarca == 'TO' ? 'SELECTED' : '')?>>TO</option>
            </select>
            <div style="clear:both; height: 20px;"></div>

						<div class="destaqueAzul" style="margin-bottom:10px"> Testemunha 1</div>

                        <table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
                            <tr>
                                <td align="right" valign="middle" class="formTabela">Nome:</td>
                                <td class="formTabela" width="300"><input name="txtNomeTest1" type="text" id="txtNomeTest1" style="width:300px" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtNomeTest1', $_SESSION["id_userSecao"]))?>" maxlength="200" alt="Nome da Testemunha 1" /></td>
                            </tr>
                            <tr>
                                <td align="right" valign="middle" class="formTabela">RG:</td>
                                <td class="formTabela"><input name="txtRGTest1" type="text" id="txtRGTest1" style="width:300px" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtRGTest1', $_SESSION["id_userSecao"]))?>" maxlength="17" alt="RG da Testemunha 1" /></td>
                            </tr>
                            <tr>
                                <td align="right" valign="middle" class="formTabela">Orgão Expeditor:</td>
                                <td class="formTabela"><input name="txtOrgaoExpedidorTest1" type="text" id="txtOrgaoExpedidorTest1" style="width:300px" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtOrgaoExpedidorTest1', $_SESSION["id_userSecao"]))?>" maxlength="250" alt="Orgão Expedidor da Testemunha 1" /></td>
                            </tr>
                        </table>
                        <br />
                        
                        <div class="destaqueAzul" style="margin-bottom:10px"> Testemunha 2</div>
                        <table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
                            <tr>
                                <td align="right" valign="middle" class="formTabela">Nome:</td>
                                <td class="formTabela" width="300"><input name="txtNomeTest2" type="text" id="txtNomeTest2" style="width:300px" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtNomeTest2', $_SESSION["id_userSecao"]))?>" maxlength="200" alt="Nome da Testemunha 2" /></td>
                            </tr>
                            <tr>
                                <td align="right" valign="middle" class="formTabela">RG:</td>
                                <td class="formTabela"><input name="txtRGTest2" type="text" id="txtRGTest2" style="width:300px" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtRGTest2', $_SESSION["id_userSecao"]))?>" maxlength="17" alt="RG da Testemunha 2" /></td>
                            </tr>
                            <tr>
                                <td align="right" valign="middle" class="formTabela">Orgão Expeditor:</td>
                                <td class="formTabela"><input name="txtOrgaoExpedidorTest2" type="text" id="txtOrgaoExpedidorTest2" style="width:300px" value="<?=(getDadosContrato('dados_alteracao_contrato', 'txtOrgaoExpedidorTest2', $_SESSION["id_userSecao"]))?>" maxlength="250" alt="Orgão Expedidor da Testemunha 2" /></td>
                            </tr>
                        </table>
                        <br />
                        <input type="button" value="Limpar" id="btLimparContrato" />
                        <input type="button" value="Salvar" id="btSalvarContrato" />
                        <input type="button" value="Prosseguir" id="btProsseguirContrato" />
                        </form>
                        
                        </div>
				</div>

<script>
$(document).ready(function(e) {
	
	// AO ENTRAR NA PÁGINA CHECA SE OS CHECKBOX DE ALTERAÇÕES ESTÃO MARCADOS E MOSTRA OS DIVS COM AS INFORMAÇÕES A SEREM ALTERADAS
	if($('#cheMudancaRazao').attr('checked')){
		$('#nova_razao').css('display','block');
	}else{
		$('#nova_razao').css('display','none');
	}

	if($('#cheMudancaEndereco').attr('checked')){
		$('#novo_endereco').css('display','block');
	}else{
		$('#novo_endereco').css('display','none');
	}

	if($('#cheExcluirAtividade').attr('checked')){
		$('#exclui_cnae').css('display','block');
	}else{
		$('#exclui_cnae').css('display','none');
	}

	if($('#cheIncluirAtividade').attr('checked')){
		$('#novo_cnae').css('display','block');
	}else{
		$('#novo_cnae').css('display','none');
	}

	if($('#cheExcluirSocio').attr('checked')){
		$('#exclui_socio').css('display','block');
	}else{
		$('#exclui_socio').css('display','none');
	}

	if($('#cheIncluirSocio').attr('checked')){
		$('#novo_socio').css('display','block');
	}else{
		$('#novo_socio').css('display','none');
	}

	if($('#cheAlterarCotas').attr('checked')){
		$('#cotas').css('display','block');
	}else{
		$('#cotas').css('display','none');
	}

	if($('#cheAlterarCapitalSocial').attr('checked')){
		$('#capital').css('display','block');
	}else{
		$('#capital').css('display','none');
	}
	
	// CHECANDO QUANTOS CNAEs NOVOS FORAM PREENCHIDOS PARA TRAZER AS DESCRIÇÕES DELES
	for(i=1; i <= $(document).find('input[id^="txtCodigoCNAE"]').length; i++){
		consultaBanco('meus_dados_empresa_consulta_cnae.php?codigo='+$('#txtCodigoCNAE' + i).val(), 'atividade' + i)
	}
});
</script>

<?php include 'rodape.php' ?>
