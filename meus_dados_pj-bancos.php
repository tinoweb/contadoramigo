<?php include 'header_restrita.php' ?>

<script type="text/javascript">


jQuery(document).ready(function() {		

	$('#btCancelar').click(function(){
		location.href = 'meus_dados_pj.php'
	});
	

	$('#btSalvar').click(function(){

		if($('#CNPJ').val()!=''){
			$.ajax({
				url:'meus_dados_pj_checa.php?id=' + $('#hidID').val() + '&idLogin=' + $('#hidID2').val() + '&cnpj=' + $('#CNPJ').val(),
				type: 'get',
				async: 	false,
				cache: false,
				success: function(retorno){
					if(retorno == 1){
						alert('Já existe uma empresa fornecedora cadastrada com este CNPJ!');  
						return false;
					}else{
						formSubmit();						
					}
				}
			});
		}
		
	});

});


 var msg1 = 'É necessário preencher o campo';
 var msg2 = 'É necessário selecionar ';
 var msg3 = 'No momento o Contador Amigo não oferece suporte para empresas do ramo de Comércio e Indústria.';
 var msg4 = 'No momento o Contador Amigo não oferece suporte para empresas com Lucro Presumido.';
// var msg5 = 'Empresas de outros estados ou cidades podem usar o Contador Amigo, porém não terão suporte referente à emissão de notas fiscais e para pagamento da TFE/TFA, cujos valores são determinados pelas respectivas prefeituras. Geralmente estes impostos chegam pelo correio e são pagos apenas 1 vez por ano.';

			
	function formSubmit(){   
		if( validElement('nome', msg1) == false){return false}
		if( validElement('CNPJ', msg1) == false){return false}
		if( validElement('Endereco', msg1) == false){return false}
		if( validElement('CEP', msg1) == false){return false}
		if( validElement('Cidade', msg1) == false){return false}
		
		var Cidade = document.getElementById('Cidade');
		if(Cidade.value.toLowerCase() == 'são paulo' || Cidade.value.toLowerCase() == 'sao paulo') {
			Cidade.value = 'São Paulo';
		}
		var Estado = document.getElementById('Estado');
		if(Estado.selectedIndex == ""){
			window.alert(msg2+'o Estado.');
			return false;
		}
		document.getElementById('form_pj').submit();	
	}

</script>

<?php
$acao = 'inserir';


function selected( $value, $prev ){
   return $value==$prev ? ' selected="selected"' : ''; 
};
?>
<div class="principal">
<div class="minHeight">

<div class="titulo" style="margin-bottom:20px">Meus Dados</div>
<div class="tituloVermelho" style="margin-bottom:20px">Cadastro de Empresas Fornecedoras</div>
<?
$textoAcao = "Cadastrar nova empresa:";

if($_GET["editar"]){
	$textoAcao = "Editar dados da empresa:";
	$acao = 'editar';
	// ALTERAÇÂO DE PJ
	$sql = "SELECT * FROM dados_pj WHERE id='" . $_GET["editar"] . "' LIMIT 0, 1";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	
	$linha=mysql_fetch_array($resultado);

	$id 					= $linha["id"];
	$nome 					= $linha["nome"];
	$cnpj					= $linha["cnpj"];
	$endereco				= $linha["endereco"];
	$cep					= $linha["cep"];
	$cidade					= $linha["cidade"];
	$estado					= $linha["estado"];
	
	$tipo_conta				= $linha["tipo_conta"];
	$id_banco				= $linha["id_banco"];
	$num_agencia			= $linha["agencia"];
	$dig_agencia			= $linha["dig_agencia"];
	$num_conta				= $linha["conta"];
	$dig_conta				= $linha["dig_conta"];

}
?>

<? if($_GET['act'] == 'new' || $_GET["editar"]){ ?>

<div style="margin-bottom:20px"><?=$textoAcao;?></div>

  <form name="form_pj" id="form_pj" method="post" action="meus_dados_pj_gravar.php">
  <input type="hidden" name="acao" value="<?=$acao?>" />
    <table border="0" cellpadding="0" cellspacing="3" class="formTabela">
  <tr>
    <td width="115" align="right" valign="middle" class="formTabela">Empresa:</td>
    <td class="formTabela"><input name="nome" id="nome" type="text" size="50" maxlength="50" value="<?=$nome?>" alt="Nome" /></td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela">CNPJ:</td>
    <td class="formTabela" style="color: #000;">
	    <input name="CNPJ" id="CNPJ" type="text" value="<?=$cnpj?>" size="18" maxlength="18" alt="CNPJ" class="cnpj" />
      	</td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela">Endere&ccedil;o:</td>
    <td class="formTabela" ><input name="Endereco" id="Endereco" type="text" style="width:300px" value="<?=$endereco?>" maxlength="200" alt="Endereço" /></td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela">CEP:</td>
    <td class="formTabela"><input name="CEP" id="CEP" type="text" style="width:80px" value="<?=$cep?>" maxlength="9" alt="CEP" class="cep" /> 
      <span style="font-size:10px; display: none">(somente números)</span></td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela">Cidade:</td>
    <td class="formTabela"><input name="Cidade" id="Cidade" type="text" style="width:300px" value="<?=$cidade?>" maxlength="200" alt="Cidade" /></td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela">Estado:</td>
    <td class="formTabela"><select name="Estado" id="Estado" alt="Estado">
      <option value="" <?php echo selected( '', $estado ); ?> ></option>
      <option value="AC" <?php echo selected( 'AC', $estado ); ?> >AC</option>
      <option value="AL" <?php echo selected( 'AL', $estado ); ?> >AL</option>
      <option value="AM" <?php echo selected( 'AM', $estado ); ?> >AM</option>
      <option value="AP" <?php echo selected( 'AP', $estado ); ?> >AP</option>
      <option value="BA" <?php echo selected( 'BA', $estado ); ?> >BA</option>
      <option value="CE" <?php echo selected( 'CE', $estado ); ?> >CE</option>
      <option value="DF" <?php echo selected( 'DF', $estado ); ?> >DF</option>
      <option value="ES" <?php echo selected( 'ES', $estado ); ?> >ES</option>
      <option value="GO" <?php echo selected( 'GO', $estado ); ?> >GO</option>
      <option value="MA" <?php echo selected( 'MA', $estado ); ?> >MA</option>
      <option value="MG" <?php echo selected( 'MG', $estado ); ?> >MG</option>
      <option value="MS" <?php echo selected( 'MS', $estado ); ?> >MS</option>
      <option value="MT" <?php echo selected( 'MT', $estado ); ?> >MT</option>
      <option value="PA" <?php echo selected( 'PA', $estado ); ?> >PA</option>
      <option value="PB" <?php echo selected( 'PB', $estado ); ?> >PB</option>
      <option value="PE" <?php echo selected( 'PE', $estado ); ?> >PE</option>
      <option value="PI" <?php echo selected( 'PI', $estado ); ?> >PI</option>
      <option value="PR" <?php echo selected( 'PR', $estado ); ?> >PR</option>
      <option value="RJ" <?php echo selected( 'RJ', $estado ); ?> >RJ</option>
      <option value="RN" <?php echo selected( 'RN', $estado ); ?> >RN</option>
      <option value="RO" <?php echo selected( 'RO', $estado ); ?> >RO</option>
      <option value="RR" <?php echo selected( 'RR', $estado ); ?> >RR</option>
      <option value="RS" <?php echo selected( 'RS', $estado ); ?> >RS</option>
      <option value="SC" <?php echo selected( 'SC', $estado ); ?> >SC</option>
      <option value="SE" <?php echo selected( 'SE', $estado ); ?> >SE</option>
      <option value="SP" <?php echo selected( 'SP', $estado ); ?> >SP</option>
      <option value="TO" <?php echo selected( 'TO', $estado ); ?> >TO</option>
    </select></td>
  </tr>
  
  
  <tr>
    <td align="left" valign="middle" class="formTabela" colspan="2">Dados Bancários</td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela">Banco:</td>
    <td class="formTabela"><select name="tipo_conta" id="tipo_conta" alt="">
      <option value="" <?php echo selected( '', $tipo_conta ); ?> ></option>
      <?
	  $rsBancos = mysql_query("SELECT * FROM lista_bancos WHERE codigo <> 0 ORDER BY ordem,nome");
      while($lista_bancos = mysql_fetch_array($rsBancos)){ 
	  	$codigo_banco = str_pad($lista_bancos['codigo'],3,"0",STR_PAD_LEFT);
	  ?>
	      <option value="<?=$lista_bancos['id']?>" <?=selected( $lista_bancos['id'], $id_banco ); ?> ><?="[" . $codigo_banco . "]&nbsp;&nbsp;&nbsp;&nbsp;" . $lista_bancos['nome']?></option>
      <? } ?>
      </select>
    </td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela">Tipo Conta:</td>
    <td class="formTabela"><select name="tipo_conta" id="tipo_conta" alt="">
      <option value="" <?php echo selected( '', $tipo_conta ); ?> ></option>
      <option value="corrente" <?php echo selected( 'corrente', $tipo_conta ); ?> >Conta Corrente</option>
      <option value="poupança" <?php echo selected( 'poupança', $tipo_conta ); ?> >Conta Poupança</option>
      </select>
    </td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela">Nº Agência:</td>
    <td class="formTabela"><input name="num_agencia" id="num_agencia" type="text" style="width:80px" value="<?=$num_agencia?>" maxlength="9" alt="" class="inteiro" /> 
      <span style="font-size:10px; display: none">(somente números)</span></td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela">Dígito Agência:</td>
    <td class="formTabela"><input name="dig_agencia" id="dig_agencia" type="text" style="width:30px" value="<?=$dig_agencia?>" maxlength="2" alt="" class="inteiro" /> 
      <span style="font-size:10px; display: none">(somente números)</span></td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela">Número Conta:</td>
    <td class="formTabela"><input name="num_conta" id="num_conta" type="text" style="width:80px" value="<?=$num_conta?>" maxlength="9" alt="" class="inteiro" /> 
      <span style="font-size:10px; display: none">(somente números)</span></td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela">Dígito Conta:</td>
    <td class="formTabela"><input name="dig_conta" id="dig_conta" type="text" style="width:30px" value="<?=$dig_conta?>" maxlength="2" alt="" class="inteiro" />
      <span style="font-size:10px; display: none">(somente números)</span></td>
  </tr>
  
  <tr>
    <td colspan="2" valign="middle" class="formTabela">&nbsp;<input type="hidden" name="hidID" id="hidID" value="<?=$id?>" /><input type="hidden" name="hidID2" id="hidID2" value="<?=$_SESSION["id_empresaSecao"]?>" /> </td>
  </tr>
  <tr>
    <td colspan="2" align="center" valign="middle" class="formTabela"><input type="button" value="Salvar" id="btSalvar" />
    <? if($_GET["editar"]){ ?>
    	<input type="button" value="Voltar" id="btCancelar" />
    <? }else{ ?>
    	<input type="button" value="Cancelar" id="btCancelar" />
    <? } ?>
    </td>
    </tr>
</table>
</form>
<?
}else{
?>

<div style="text-align: right;"><a href="meus_dados_pj.php?act=new">Cadastrar nova empresa</a></div>
<?
	// MONTAGEM DA LISTAGEM DOS AUTONOMOS
	$sql = "SELECT * FROM dados_pj WHERE id_login='" . $_SESSION["id_empresaSecao"] . "'";
	$resultado = mysql_query($sql)
	or die (mysql_error());
?>
    <table width="100%" cellpadding="5">
        <tr>
            <th width="80">Açâo</th>
            <th width="140">Nome</th>
            <th width="40">CNPJ</th>
        </tr>

<?
	if(mysql_num_rows($resultado) > 0){

		// TRAZENDO OS DADOS PARA MONTAR TABELA DE AUTONOMOS
		while($linha=mysql_fetch_array($resultado)){
			$id 	= $linha["id"];
			$nome 	= $linha["nome"];
			$cnpj 	= $linha["cnpj"];
?>
            <tr>
                <td class="td_calendario" align="center"><a href="#" onClick="if (confirm('Você tem certeza que deseja excluir esta empresa fornecedora?'))location.href='meus_dados_pj_excluir.php?excluir=<?=$id?>';"><img src="images/del.png" width="24" height="23" border="0" title="Excluir" /></a>
                <a href="meus_dados_pj.php?editar=<?=$id?>"><img src="images/edit.png" width="24" height="23" border="0" title="Editar" /></a></td>
                <td class="td_calendario"><?=$nome?></td>
                <td class="td_calendario"><?=$cnpj?></td>
            </tr>
<?	
		}
?>
<?
	}else{
?>
		<tr>
            <td class="td_calendario">&nbsp;</td>        
            <td class="td_calendario">&nbsp;</td>        
            <td class="td_calendario">&nbsp;</td>        
        </tr>
<?		
	}
}
?>
    </table>


</div>
</div>
<?
if($_SESSION['mensagem_altera_pj'] != ''){
	echo "<script>alert('".$_SESSION['mensagem_altera_pj']."')</script>";
	$_SESSION['mensagem_altera_pj'] = "";
}
?>

<?php include 'rodape.php' ?>