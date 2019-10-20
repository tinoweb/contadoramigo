<?php include 'header_restrita.php'; ?>


<script>
$(document).ready(function(e) {
});

</script>

<div class="principal">
<div style="width:80%; text-align:left">

	<h1>Outros Serviços</h1>
  <h2>Declaração do Simples</h2>
  <div style="margin-bottom:20px"> Quando você executa um trabalho para um cliente pessoa jurídica, é comum ele pedir para você enviar uma Declaração de que a sua empresa é optante pelo Simples. Esta é uma exigência legal. A empresa tem que anexar esta declaração a todo contrato de trabalho realizado com uma empresa optante por este regime de tributação.<br>
    <br>
Para que você não perca tempo com isso o<strong> Contador Amigo</strong> preparou esta página. Preencha a seguir os dados do cliente (empresa pagadora) e também o nome e cargo do sócio que assinará a Declaração. E pronto: um arquivo PDF será gerado. Imprima-o e envie ao cliente. </div>
  
    <h2>Dados da Declaração:</h2>
    
  <form id="formGeraDeclaracao" action="declaracao_simples_download.php" method="post">
    
    <label for="txtPagadora">Empresa solicitante: </label>
  <input name="txtPagadora" id="txtPagadora" type="text" style="margin-right:10px; width:250px" maxlength="75" />


  <label for="txtCNPJ">CNPJ: </label>
  <input name="txtCNPJ" id="txtCNPJ" type="text" style="margin-right:10px;" class="campoCNPJ" maxlength="" />
  <br /><br />

  <label for="rdbSocio">Responsável pela assinatura da declaração: </label>  
<?
$query = mysql_query('SELECT idSocio, nome FROM dados_do_responsavel WHERE id = '.$_SESSION["id_empresaSecao"].' AND responsavel = 1');

if(mysql_num_rows($query) <= 0){
		echo '<input type="text" name="txtNomeSocio" style="margin-bottom:5px; text-transform:capitalize; width:245px" id="txtNomeSocio" value="" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		echo 'Cargo: <input type="text" name="txtTipoSocio" style="margin-bottom:5px; text-transform:capitalize;" id="txtTipoSocio" value="" />&nbsp;<br>';
	
} else {
	
	while($dados = mysql_fetch_array($query)){
		echo '<input type="text" name="txtNomeSocio" style="margin-bottom:5px; text-transform:capitalize; width:245px" id="txtNomeSocio" value="'.$dados['nome'].'" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		echo 'Cargo: <input type="text" name="txtTipoSocio" style="margin-bottom:5px; text-transform:capitalize;" id="txtTipoSocio" value="Sócio Responsável" />&nbsp;<br>';
	}

}
?>

<br />


<input type="button" name="btGerarRecibo" value="Gerar Declaração" id="btGerarRecibo" />

<br />

</form>

<div style="clear:both;height:80px;"></div>
<script>
$(document).ready(function(e) {
	
  $('#btGerarRecibo').bind('click',function(){
		
		if($('#txtPagadora').val() == ''){ // checando se a empresa foi preenchida
		
			alert('É necessário preencher a Razão Social da empresa.');
			$('#txtPagadora').focus();
			return false;			
		
		}
		
		if($('#txtCNPJ').val() == ''){ // checando se o CNPJ foi preenchido
		
			alert('É necessário preencher o CNPJ da empresa.');
			$('#txtCNPJ').focus();
			return false;			
		
		}

		if($("#txtNomeSocio").val() == ''){ // checando se o socio foi preenchido
		
			alert('É necessário preencher o nome do sócio responsável pela declaração.');
			$("#txtNomeSocio").focus();
			return false;			
		
		}
	
		if($("#txtTipoSocio").val() == ''){ // checando se o tipo de socio foi preenchido
		
			alert('É necessário preencher o tipo do sócio (responsável ou administrativo).');
			$("#txtTipoSocio").focus();
			return false;			
		
		}
	
		$('#formGeraDeclaracao').submit();

	});
});
</script>

<div style="clear:both;">


</div>
</div>
<?php include 'rodape.php' ?>