<?php include 'header_restrita.php'; ?>


<script>

$(document).ready(function(e) {
});

</script>

<div class="principal">

<div style="width:790px;">

<div class="titulo" style="clear:both;margin-bottom:10px;">Outros Serviços</div>

<div class="tituloVermelho" style="clear:both;margin-bottom:10px;">Procuração para a Receita Federal e Previdência Social</div>

<div style="clear:both;"> Se você precisar comparecer a uma agência da Receita Federal para resolver algum problema mas quiser mandar alguém em seu lugar, será necessária uma procuração. Para isso, preencha os campos abaixo. O documento deve ser assinado pelo sócio-responsável e a firma reconhecida em cartório.
<div style="height:20px;clear:both;"></div>
</div>

<?
$sql = "SELECT r.idSocio
FROM dados_do_responsavel r 
WHERE r.id='" . $_SESSION["id_empresaSecao"] . "'
AND (r.nome = '' OR r.cpf = '' OR r.rg = '' OR r.orgao_expeditor = '')";
$resultado = mysql_query($sql) or die (mysql_error());
$linha_dados=mysql_fetch_array($resultado);

if($linha_dados['idSocio']){
?>
<div style="background-color:#FFFFFF; border-color:#CCC; border-style:solid; border-width:1px; padding:10px; margin-bottom:30px">
<span class="destaque">IMPORTANTE:</span><br /><br />
Para gerar a procuração é necessário que os seguintes dados do Sócio Responsável estejam preenchidos:<br />
- CPF;<br />
- RG;<br />
- Órgão Expedidor.<br />
<br />
Acesse a página de <a href="meus_dados_socio.php?editar=<?=$linha_dados['idSocio']?>">Cadastro de Sócios</a> e complete as informações.
</div>

<?
}

?>

<div class="tituloAzul" style="clear:both;margin-bottom:10px;">Dados do Outorgado</div>

<div style="clear:both;">Informe abaixo os dados de quem irá representar a sua empresa junto à Receita Federal ou à Previdência Social.
<div style="height:20px;clear:both;"></div>

<form action="procuracao_pj_download.php" method="post" name="frmProcuracao" id="frmProcuracao">

  <table border="0" cellpadding="0" cellspacing="3" style="background:none" class="formTabela">
    <tr>
      <td align="right" valign="middle" class="formTabela"><label for="txtNome">Nome completo: </label></td>
      <td class="formTabela"><input type="text" name="txtNome" id="txtNome" style="width:350px" maxlength="75"></td>
    </tr>
    <tr>
      <td align="right" valign="middle" class="formTabela"><label for="txtNacionalidade">Nacionalidade: </label></td>
      <td class="formTabela"><input type="text" name="txtNacionalidade" id="txtNacionalidade" style="width:220px" maxlength="75"></td>
    </tr>
    <tr>
      <td align="right" valign="middle" class="formTabela"><label for="selEstadoCivil">Estado Civil: </label></td>
      <td class="formTabela">
        <select name="selEstadoCivil" id="selEstadoCivil">
          <option>Selecione...</option>
          <option value="Solteiro">Solteiro</option>    
          <option value="Casado">Casado</option>
          <option value="Divorciado">Divorciado</option>
          <option value="Viúvo">Viúvo</option>
        </select>
      </td>
    </tr>
    <tr>
      <td align="right" valign="middle" class="formTabela"><label for="txtProfissao">Profissão: </label></td>
      <td class="formTabela"><input type="text" name="txtProfissao" id="txtProfissao" style="width:220px" maxlength="75"></td>
    </tr>
    <tr>
      <td align="right" valign="middle" class="formTabela"><label for="txtCPF">CPF: </label></td>
      <td class="formTabela"><input type="text" name="txtCPF" id="txtCPF" class="campoCPF" style="width:100px"></td>
    </tr>
    <tr>
      <td align="right" valign="middle" class="formTabela"><label for="txtRG">RG: </label></td>
      <td class="formTabela"><input type="text" name="txtRG" id="txtRG" style="width:100px"></td>
    </tr>
    <tr>
      <td align="right" valign="middle" class="formTabela"><label for="txtOrgaoExpedidor">Órgão Expedidor: </label></td>
      <td class="formTabela"><input type="text" name="txtOrgaoExpedidor" id="txtOrgaoExpedidor" style="width:100px"></td>
    </tr>
    <tr>
      <td align="right" valign="middle" class="formTabela"><label for="txtEndereco">Endereço:</label></td>
      <td class="formTabela"><input type="text" name="txtEndereco" id="txtEndereco" style="width:350px" maxlength="75"></td>
    </tr>
    <tr>
      <td align="right" valign="middle" class="formTabela"><label for="txtBairro">Bairro:</label></td>
      <td class="formTabela"><input type="text" name="txtBairro" id="txtBairro" style="width:220px"></td>
    </tr>
    <tr>
      <td align="right" valign="middle" class="formTabela"><label for="txtCep">Cep:</label></td>
      <td class="formTabela"><input type="text" name="txtCep" id="txtCep" style="width:70px" class="campoCEP"></td>
    </tr>
    <tr>
      <td align="right" valign="middle" class="formTabela"><label for="txtCidade">Cidade:</label></td>
      <td class="formTabela"><input type="text" name="txtCidade" id="txtCidade" style="width:220px" maxlength="50"></td>
    </tr>
    <tr>
      <td align="right" valign="middle" class="formTabela"><label for="selEstado">UF:</label></td>
      <td class="formTabela">
        <select name="selEstado" id="selEstado">
          <option value="">Selecione...</option>
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
      </td>
    </tr>
    <tr>
      <td align="right" valign="middle" class="formTabela"><label for="txtTelefone">Telefone:</label></td>
      <td class="formTabela">( <input type="text" name="txtDDDTelefone" id="txtDDDTelefone" style="width:40px" maxlength="2" class="inteiro"> ) <input type="text" name="txtTelefone" id="txtTelefone" style="width:70px" maxlength="9" class="inteiro"></td>
    </tr>
    <tr>
      <td style="padding-top:20px;" align="right" valign="middle" class="formTabela"><label for="txtDataLimite">Validade da procuração:</label></td>
      <td style="padding-top:20px;" class="formTabela"><input type="text" name="txtDataLimite" id="txtDataLimite" class="campoData" style="width:70px"></td>
    </tr>
    <tr>
      <td style="padding-top:20px;" align="right" valign="middle" class="formTabela"></td>
      <td style="padding-top:20px;" class="formTabela"><input type="button" name="btGerarProcuracao" value="Gerar Procuração" id="btGerarProcuracao" /></td>
    </tr>
  </table>
</form>
</div>

<script>
$(document).ready(function(e) {

  $('#btGerarProcuracao').bind('click',function(){
		
		if($('#txtNome').val() == ''){ // 
			alert('É necessário preencher o nome.');
			$('#txtNome').focus();
			return false;			
		}
		if($('#txtNacionalidade').val() == ''){ // 
			alert('É necessário preencher a nacionalidade.');
			$('#txtNacionalidade').focus();
			return false;			
		}
		if($('#selEstadoCivil').val() == ''){ // 
			alert('É necessário selecionar o estado civil.');
			$('#selEstadoCivil').focus();
			return false;			
		}
		if($('#txtProfissao').val() == ''){ // 
			alert('É necessário preencher a profissão.');
			$('#txtProfissao').focus();
			return false;			
		}
		if($('#txtCPF').val() == ''){ // 
			alert('É necessário preencher o CPF.');
			$('#txtCPF').focus();
			return false;			
		}
		if($('#txtRG').val() == ''){ // 
			alert('É necessário preencher o RG.');
			$('#txtRG').focus();
			return false;			
		}
		if($('#txtOrgaoExpedidor').val() == ''){ // 
			alert('É necessário preencher o órgão expedidor.');
			$('#txtOrgaoExpedidor').focus();
			return false;			
		}
		if($('#txtEndereco').val() == ''){ // 
			alert('É necessário preencher o endereço.');
			$('#txtEndereco').focus();
			return false;			
		}
		if($('#txtCep').val() == ''){ // 
			alert('É necessário preencher o cep.');
			$('#txtCep').focus();
			return false;			
		}
		if($('#txtCidade').val() == ''){ // 
			alert('É necessário preencher a cidade.');
			$('#txtCidade').focus();
			return false;			
		}
		if($('#selEstado').val() == ''){ // 
			alert('É necessário selecionar um estado.');
			$('#selEstado').focus();
			return false;			
		}
		if($('#txtDDDTelefone').val() == ''){ // 
			alert('É necessário preencher o ddd do telefone.');
			$('#txtDDDTelefone').focus();
			return false;			
		}
		if($('#txtTelefone').val() == ''){ // 
			alert('É necessário preencher o telefone.');
			$('#txtTelefone').focus();
			return false;			
		}
		if($('#txtDataLimite').val() == ''){ // 
			alert('É necessário preencher a data de validade da procuração.');
			$('#txtDataLimite').focus();
			return false;			
		}
	  
	  	var status = false;

	  	$.ajax({
			  url: 'ajax.php'
			, dataType: 'json'
			, type: 'POST'
			, data: {getDadosSocioResponsavel:''}
			, beforeSend: function() {}
			, success: function(ret) {
				console.log(ret);
				if(ret['status']){
					status = ret['status'];
				}
			}
			, error: function(xhr) { // if error occured
				console.log("Error occured.please try again");
			}
			, complete: function() {
				if(status){
					$('#frmProcuracao').submit();
				} else {
					alert('Não e possivel gerar procuração para a receita federal e previdência social, pois não existe um sócio responsavel.');
				}
			}
		});
	  	
	});
	
});
</script>


</div>

<div style="clear:both;">


</div>
<?php include 'rodape.php' ?>