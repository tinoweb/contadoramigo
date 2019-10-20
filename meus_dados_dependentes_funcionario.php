<?php include 'header_restrita.php'?>

<?
$acao = 'inserir';
//$acao = 'editar';

function selected( $value, $prev ){
   return $value==$prev ? ' selected="selected"' : ''; 
};

function checked( $value, $prev ){
   return $value==$prev ? ' checked="checked"' : ''; 
};

?>

<script type="text/javascript">


jQuery(document).ready(function() {		
	
	$('#btReativar').css('display','none');

	$('#btCancelar').click(function(){
		history.go(-1);
	});
	$('#btReativar').click(function(){
		location.href = 'meus_dados_funcionario_reativar.php?socio=' + $('#hidSocioID').val();
	});
});


function vinculoMasculino(){
	document.getElementById('op1V').text = 'Filho';
	document.getElementById('op1V').value = 'filho';
	document.getElementById('op2V').text = 'Irmão';
	document.getElementById('op2V').value = 'irmão';
	document.getElementById('op3V').text = 'Pai';
	document.getElementById('op3V').value = 'pai';
}

function vinculoFeminino(){
	document.getElementById('op1V').text = 'Filha';
	document.getElementById('op1V').value = 'filha';
	document.getElementById('op2V').text = 'Irmã';
	document.getElementById('op2V').value = 'irmã';
	document.getElementById('op3V').text = 'Mãe';
	document.getElementById('op3V').value = 'mãe';
}

var msg1 = 'É necessário preencher o campo';
var msg2 = 'É necessário selecionar ';
var msg3 = 'No momento o Contador Amigo não oferece suporte para empresas do ramo de Comércio e Indústria.';
var msg4 = 'No momento o Contador Amigo não oferece suporte para empresas com Lucro Presumido.';


function ValidaData(data){
	exp = /\d{2}\/\d{2}\/\d{4}/
	if(!exp.test(data))
	return false; 
}

function ValidaCPF(CPF){
	exp = /\d{11}/
	if(!exp.test(CPF))
	return false; 
}

function ValidaCep(cep){
	exp = /\d{8}/
	if(!exp.test(cep))
	return false; 
}

function formSubmit(nomeFormulario){   


	if( validRadio('radSexoDependente', msg2) == false){return false}
	if( validElement('txtNomeDependente', msg1) == false){return false}
	if( validElement('selVinculoDependente', msg1) == false){return false}
	if( validElement('txtCPFDependente', msg1) == false){return false}
	if( validElement('txtRGDependente', msg1) == false){return false}
	if( validElement('txtDataEmissaoDependente', msg1) == false){return false}
	if( validElement('txtOrgaoExpedidorDependente', msg1) == false){return false}
	if( validElement('txtDataNascimentoDependente', msg1) == false){return false}
	if( validElement('txtEnderecoDependente', msg1) == false){return false}
	if( validElement('txtBairroDependente', msg1) == false){return false}
	if( validElement('txtCEPDependente', msg1) == false){return false}
	if( validElement('txtCidadeDependente', msg1) == false){return false}
	if( validElement('selEstadoDependente', msg2) == false){return false}
	if( validRadio('radInvalidezDependente', msg2) == false){return false}

	if(document.getElementById('txtDataNascimentoDependente').value != ""){
		if (ValidaData(document.getElementById('txtDataNascimentoDependente').value) == false){
			alert('Digite a data de nascimento no formato DD/MM/AAAA'); 
			return false;
		}
	}

 	document.getElementById(nomeFormulario).submit();
}

</script>

<div class="principal">
   
<div class="titulo" style="margin-bottom:20px">Meus Dados</div>

<?
$mostrar_cadastrar_novo = false;

$textoAcao = "- Incluir";
	
$idFuncionario = $_SESSION['idFuncionario'];
	
if($_GET['act'] != 'new'){// CHECANDO SE NÃO É A INCLUSAO DE UM NOVO FUNCIONARIO	

	// CHECANDO QUANTIDADE DE FUNCIONÁRIOS
	$sql = "SELECT idDependente, nome, idFuncionario, cpf, vinculo FROM dados_dependentes_funcionario WHERE idFuncionario = '" . $_SESSION['idFuncionario'] . "'";
	//echo ($sql);
	$resultado = mysql_query($sql)
	or die (mysql_error());
	
	if(mysql_num_rows($resultado) == 1){
		$dependente = mysql_fetch_array($resultado);
		$idDependente = $dependente['idDependente'];
		$mostrar_cadastrar_novo = true;
	}
	
	if($_GET["editar"]){
	
		$idDependente = $_GET["editar"];
	
	}
	
	if($idDependente){
		
		$textoAcao = "- Editar";
		$acao = 'editar';
		// ALTERAÇÂO DE AUTONOMOS
		$sql = "SELECT * FROM dados_dependentes_funcionario WHERE idDependente='" . $idDependente . "' LIMIT 0, 1";
		$consulta = mysql_query($sql)
		or die (mysql_error());
		
		$linha=mysql_fetch_array($consulta);
	
		$idDependente 					= $linha["idDependente"];
		$idFuncionario					= $linha["idFuncionario"];
		$nome 							= $linha["nome"];
		$vinculo						= $linha["vinculo"];
		$sexo 							= $linha["sexo"];
		$cpf							= $linha["cpf"];
		$rg								= $linha["rg"];
		if(strlen($rg)>0){
			$rg = preg_replace("/\W/","",$rg);
		}
		$data_de_emissao				= $linha["data_emissao"];
		$orgao_expeditor				= $linha["orgao_expeditor"];	
		$data_de_nascimento 			= $linha["data_de_nascimento"];
		$endereco						= $linha["endereco"];
		$bairro							= $linha["bairro"];
		$cep							= $linha["cep"];	
		$cidade							= $linha["cidade"];	
		$estado							= $linha["estado"];
		$invalidez						= $linha["invalidez"];
//		$tipo_invalidez					= $linha["tipo_invalidez"];
//		$cid							= $linha["CID"];

	}
}

//echo "ID: " . $idFuncionario;

?>

<div class="tituloVermelho" style="margin-bottom:20px">Cadastro de Dependentes 

<?

if($_GET['act'] == 'new' || $_GET["editar"] || mysql_num_rows($resultado) == 1){ 


echo $textoAcao?>
</div>
 
<form method="post" name="form_dependente" id="form_dependente" action="meus_dados_dependentes_funcionario_gravar.php" >
<input type="hidden" name="hidDependenteID" value="<?=$idDependente?>" />
<input type="hidden" name="hidFuncioanrioID" id="hidFuncioanrioID" value="<?=$idFuncionario?>" />
<input type="hidden" name="pgOrigem" value="<?=basename($_SERVER['HTTP_REFERER'])?>" />
<input type="hidden" name="acao" value="<?=$acao?>" />
  
<table border="0" cellpadding="0" cellspacing="3" style="background:none" class="formTabela">
  <tr>
    <td align="right" valign="middle" class="formTabela">Sexo:</td>
    <td class="formTabela">
      <label style="margin-right:15px"><input type="radio" name="radSexoDependente" id="radSexo1" value="Masculino" alt="Sexo" onClick="vinculoMasculino()" <?php echo checked( 'Masculino', $sexo ); ?> /> Masculino</label>
      <label><input type="radio" name="radSexoDependente" id="radSexo2" value="Feminino" onClick="vinculoFeminino()" <?php echo checked( 'Feminino', $sexo ); ?> /> Feminino</label>
    </td>
  </tr>
 <tr>
   <td align="right" valign="middle" class="formTabela">Nome:</td>
   <td class="formTabela" width="300"><input name="txtNomeDependente" type="text" id="txtNomeDependente" style="width:300px" value="<?=$nome?>" maxlength="200" alt="Nome" /> </td>
 </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">Vínculo:</td>
    <td class="formTabela"><select name="selVinculoDependente" id="selVinculoDependente" alt="Vínculo">
      <option value="" <?php echo selected( '', $vinculo); ?> >Selecione...</option>
		<?php if ($sexo == 'Feminino') { ?>
        <option id="op1V" value="filha" <?php echo selected( 'filha', $vinculo); ?> >Filha</option>    
        <option id="op2V" value="irmã" <?php echo selected( 'irmã', $vinculo ); ?> >Irmã</option>
        <option id="op3V" value="mãe" <?php echo selected( 'mãe', $vinculo ); ?> >Mãe</option>
        <?php } else { ?>
        <option id="op1V" value="filho" <?php echo selected( 'filho', $vinculo ); ?> >Filho</option>    
        <option id="op2V" value="irmão" <?php echo selected( 'irmão', $vinculo ); ?> >Irmão</option>
        <option id="op3V" value="pai" <?php echo selected( 'pai', $vinculo ); ?> >Pai</option>
        <?php } ?>
    </select></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">CPF:</td>
    <td class="formTabela"><input name="txtCPFDependente" type="text" id="txtCPFDependente" style="width:100px" value="<?=str_replace(array("/","-","."),"",$cpf)?>" alt="CPF" class="campoCPF" /> 
      <span style="font-size:10px; display: none"> (somente números)</span></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">RG:</td>
    <td class="formTabela"><input name="txtRGDependente" type="text" id="txtRGDependente" style="width:80px" value="<?=$rg?>" maxlength="12" alt="RG" class="campoRG" />
    <span style="font-size:10px"> (somente números)</span></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">Data de Emissão:</td>
    <td class="formTabela"><input name="txtDataEmissaoDependente" id="txtDataEmissaoDependente" type="text" style="width:80px" value="<?=$data_de_emissao?>" alt="Data de emissão do RG" maxlength="10" class="campoData" />
      <span style="font-size:10px; display: none">DD/MM/AAAA</span></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">Orgão Expedidor:</td>
    <td class="formTabela"><input name="txtOrgaoExpedidorDependente" type="text" id="txtOrgaoExpedidorDependente" style="width:70px" value="<?=$orgao_expeditor?>" alt="Órgão Expedidor do RG" maxlength="50" /></td>
  </tr>
   <tr>
    <td align="right" valign="middle" class="formTabela">Data de Nascimento:</td>
    <td class="formTabela"><input name="txtDataNascimentoDependente" type="text" id="txtDataNascimentoDependente" style="width:80px" value="<?=$data_de_nascimento?>" alt="Data de nascimento" maxlength="10" class="campoData" /> 
      <span style="font-size:10px; display:none">DD/MM/AAAA</span></td>
  </tr>
    <tr>
    <td align="right" valign="middle" class="formTabela">Endere&ccedil;o:</td>
    <td class="formTabela"><input name="txtEnderecoDependente" id="txtEnderecoDependente" type="text" style="width:300px" value="<?=$endereco?>" maxlength="200" alt="Endereço" /></td>
  </tr>
    <tr>
      <td align="right" valign="middle" class="formTabela">Bairro:</td>
      <td class="formTabela"><input name="txtBairroDependente" id="txtBairroDependente" type="text" style="width:300px" value="<?=$bairro?>" maxlength="200" alt="Bairro" /></td>
    </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">CEP:</td>
    <td class="formTabela"><input name="txtCEPDependente" type="text" id="txtCEPDependente" style="width:80px" value="<?=str_replace(array("/","-","."),"",$cep)?>" alt="CEP" class="campoCEP" /> <span style="font-size:10px; display:none">(somente números)</span></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">Cidade:</td>
    <td class="formTabela"><input name="txtCidadeDependente" type="text" id="txtCidadeDependente" style="width:300px" value="<?=$cidade?>" alt="Cidade" maxlength="200" /></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">Estado:</td>
    <td class="formTabela"><select name="selEstadoDependente" id="selEstadoDependente" alt="Estado">
      <option value="" <?php echo selected( '',$estado ); ?>></option>
      <option value="AC" <?php echo selected( 'AC', $estado ); ?> >AC</option>
      <option value="AL" <?php echo selected( 'AL', $estado ); ?> >AL</option>
      <option value="AM"  <?php echo selected( 'AM', $estado ); ?> >AM</option>
      <option value="AP"  <?php echo selected( 'AP', $estado ); ?> >AP</option>
      <option value="BA"  <?php echo selected( 'BA', $estado ); ?> >BA</option>
      <option value="CE"  <?php echo selected( 'CE', $estado ); ?> >CE</option>
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
      <option value="SP" <?php echo selected( 'SP', $estado ); echo selected( '', $estado ); ?> >SP</option>
      <option value="TO" <?php echo selected( 'TO', $estado ); ?> >TO</option>
    </select></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela"><label for="radInvalidezDependente">Invalidez:</label></td>
    <td class="formTabela">
      <label style="margin-right:15px"><input type="radio" name="radInvalidezDependente" value="0" alt="Invalidez do dependente" <?php echo checked( '0', $invalidez ); ?> /> Não</label>
      <label><input type="radio" name="radInvalidezDependente" value="1" <?php echo checked( '1', $invalidez ); ?> /> Sim</label>
    </td>
  </tr>
<!--              <tr>
    <td align="right" valign="middle" class="formTabela"><label for="radTempoInvalidezDependente">Tipo de invalidez:</label></td>
    <td class="formTabela">
      <label style="margin-right:15px"><input type="radio" name="radTempoInvalidezDependente" value="temporária" alt="Tipo de Invalidez do dependente" <?php //echo checked( 'temporária', $tipo_invalidez ); ?> /> Temporária</label>
      <label><input type="radio" name="radTempoInvalidezDependente" value="permanente" <?php //echo checked( 'permanente', $tipo_invalidez ); ?> /> Permanente</label>
    </td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela"><label for="txtCIDInvalidezDependente">CID:</label></td>
    <td class="formTabela">
      <input name="txtCIDInvalidezDependente" type="text" id="txtCIDInvalidezDependente" style="width:20px" alt="CID da invalidez" maxlength="10" value="<? //=$cid?>" />
    </td>
  </tr>
-->

  <tr>
    <td align="right" valign="middle" class="formTabela">&nbsp;</td>
    <td class="formTabela">
		<input type="button" value="Salvar alterações" id="btSalvar" onClick="formSubmit('form_dependente')" />
    <? if($_GET["editar"]){ ?>
    	<input type="button" value="Voltar" id="btCancelar" />
    <? 	}else{ 
					if(!$mostrar_cadastrar_novo){
		?>
    	<input type="button" value="Cancelar" id="btCancelar" />
    <? 
					}
				} ?>
    </td>
	</tr>
</table>
</form>
<br />

<?
} else {
?>
</div>

<div style="text-align: right; margin-bottom:10px; width:75%"><a href="meus_dados_dependentes_funcionario.php?act=new">Cadastrar novo Dependente</a></div>
		<table width="75%" cellpadding="5">
        	<tr>
            	<th width="80">Açâo</th>
            	<th>Nome</th>
            	<th>CPF</th>
            	<th>Status</th>
            </tr>
<?

	if(mysql_num_rows($resultado) > 1){
		
		$esconde_botao_excluir = false;
		if(mysql_num_rows($resultado) == 1){ $esconde_botao_excluir = true;}
		
		// TRAZENDO OS DADOS PARA MONTAR TABELA DE AUTONOMOS
		while($linha=mysql_fetch_array($resultado)){
			$id 	= $linha["idDependente"];
			$nome 	= $linha["nome"];
			$cpf 	= $linha["cpf"];
			$vinculo 	= $linha["vinculo"];
			
			if($status == 2){
				$esconde_botao_excluir = true;
			}else{
				$esconde_botao_excluir = false;
			}
?>
            <tr>
                <td class="td_calendario" align="center"><?=!$esconde_botao_excluir ? '<a href="#" onClick="if (confirm(\'Você tem certeza que deseja excluir este Dependente?\'))location.href=\'meus_dados_dependentes_funcionario_excluir.php?dep='.$id.'\';"><img src="images/del.png" width="24" height="23" border="0" title="Excluir" /></a>' : ''?>
                <a href="meus_dados_dependentes_funcionario.php?editar=<?=$id?>"><img src="images/edit.png" width="24" height="23" border="0" title="Editar" /></a></td>
                <td class="td_calendario"><?=$nome?></td>
                <td class="td_calendario"><?=$cpf?></td>
                <td class="td_calendario"><?
				?></td>
            </tr>
<?	
		}

	}else{
?>
		<tr>
            <td class="td_calendario">&nbsp;</td>        
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

<script>
<?
if(($_SESSION["aviso"] != '')){
	echo "alert('" . $_SESSION["aviso"] . "');";
	$_SESSION["aviso"] = "";
}


?>
</script>

<?php include 'rodape.php' ?>


