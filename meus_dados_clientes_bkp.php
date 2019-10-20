<?php include 'header_restrita.php' ?>

<script type="text/javascript">

<?
// quebrando a url
$arrUrl_origem = explode('/',$_SERVER['HTTP_REFERER']);
// VARIAVEL com o nome da página
$pagina_origem = $arrUrl_origem[count($arrUrl_origem) - 1];
	
//if(!isset($_SESSION['paginaOrigem'])){
//if(!stripos($_SESSION['paginaOrigem'],'pagamento_') || !stripos($_SESSION['paginaOrigem'],'distribuicao') || !stripos($_SESSION['paginaOrigem'],'pro_labore') || !stripos($_SESSION['paginaOrigem'],'estagiario') || isset($_GET['editar'])){
	$_SESSION['paginaOrigem'] = $pagina_origem;
//}

echo " var PaginaOrigem = '" . $_SESSION['paginaOrigem'] . "';";
?>

jQuery(document).ready(function() {		

	$('#btCancelar').click(function(){
		location.href = PaginaOrigem;
	});
	$('input[name="rdbTipoCliente"]').click(function(){
		var $this = $(this);
		if($this.val() == "PF"){
			$('#celulaNome').html('Nome:');
			$('#celulaLabelDocumento').html('CPF:');
			$('#CPF').css('display','block');
			$('#CNPJ').css('display','none').val('');
		}else{
			$('#celulaNome').html('Razão Social:');
			$('#celulaLabelDocumento').html('CNPJ:');
			$('#CPF').css('display','none').val('');
			$('#CNPJ').css('display','block');
		}
	});
	
	$('#btSalvar').click(function(){
		var $tipoCliente = $('input[name="rdbTipoCliente"]:checked');

		if($tipoCliente.val()=='PF'){
			var $campoDocumento = $('#CPF');
			var $txtTipoDocumento = "cpf";
		}else{
			var $campoDocumento = $('#CNPJ');
			var $txtTipoDocumento = "cnpj";
		}
		
		if($campoDocumento.val() != ''){
			$.ajax({
				url:'meus_dados_clientes_checa.php?id=' + $('#hidID').val() + '&idLogin=' + $('#hidID2').val() + '&documento=' + $campoDocumento.val() + '&tipo=' + $txtTipoDocumento,
				type: 'get',
				async: 	true,
				cache: false,
				success: function(retorno){
					
					if(retorno == 1){
						alert('Já existe um cliente cadastrado com este ' + $txtTipoDocumento.toUpperCase() + '!');  
						return false;
					} else if(retorno == 2) {
						alert('Já existe um cliente cadastrado com este apelido!');  
						return false;						
					} else if(retorno == 3) {
						alert('Não e possivel realizar a alteração, pois existe lançamento para este o apelido deste cliente!');  
						return false;
					} else if(retorno == 4) {		
						alert('Não e possivel usar este apelido!');  
						return false;
					}else{
						formSubmit();	
					}					
					
//					if(retorno == 1){
//						alert('Já existe um cliente cadastrado com este ' + $txtTipoDocumento.toUpperCase() + '!');  
//						return false;
//					}else{
//						formSubmit();	
//					}
				}
			});
		}else{
			formSubmit();	
			//alert('Preencha o campo ' + $txtTipoDocumento.toUpperCase() + '!');  
			//return false;
		}
		
	});


	$('#Estado').bind('change',function(){
		var arrDadosEstado = $('#Estado').val().split(';');
		var idUF = arrDadosEstado[0];
		$.getJSON('consultas.php?opcao=cidades&valor='+idUF, function (dados){ 
			if (dados.length > 0){
				var option = '<option></option>';
				$.each(dados, function(i, obj){
					option += '<option value="'+obj.cidade+'">'+obj.cidade+'</option>';
					})
				$('#Cidade').html(option).show();
			}
		});
	});


});


 var msg1 = 'É necessário preencher o campo';
 var msg2 = 'É necessário selecionar ';
 var msg3 = 'No momento o Contador Amigo não oferece suporte para empresas do ramo de Comércio e Indústria.';
 var msg4 = 'No momento o Contador Amigo não oferece suporte para empresas com Lucro Presumido.';
// var msg5 = 'Empresas de outros estados ou cidades podem usar o Contador Amigo, porém não terão suporte referente à emissão de notas fiscais e para pagamento da TFE/TFA, cujos valores são determinados pelas respectivas prefeituras. Geralmente estes impostos chegam pelo correio e são pagos apenas 1 vez por ano.';

			
	function formSubmit(){   
		if( validElement('Apelido', msg1) == false){return false}
		if( validElement('Nome', msg1) == false){return false}

		var $tipoCliente = $('input[name="rdbTipoCliente"]:checked');

		if($tipoCliente.val()=='PF'){
			if( validElement('CPF', msg1) == false){return false}
		}else{
			if( validElement('CNPJ', msg1) == false){return false}
		}

		var email = document.getElementById('Email');
		if(email.value != '' && !$.validateEmail(email.value)){
				email.focus();
				window.alert('E-mail inválido!');
				return false;
		}
		
//		if( validElement('CNPJ', msg1) == false){return false}
//		if( validElement('Endereco', msg1) == false){return false}
//		if( validElement('CEP', msg1) == false){return false}
//		if( validElement('Cidade', msg1) == false){return false}
		
//		var Cidade = document.getElementById('Cidade');
//		if(Cidade.value.toLowerCase() == 'são paulo' || Cidade.value.toLowerCase() == 'sao paulo') {
//			Cidade.value = 'São Paulo';
//		}
//		var Estado = document.getElementById('Estado');
//		if(Estado.selectedIndex == ""){
//			window.alert(msg2+'o Estado.');
//			return false;
//		}
		document.getElementById('form_cliente').submit();	
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
<div class="tituloVermelho" style="margin-bottom:20px">Cadastro de Clientes
<?
$textoAcao = "- Incluir";

if($_GET["editar"]){
	$textoAcao = "- Editar";
	$acao = 'editar';
	// ALTERAÇÂO DE PJ
	$sql = "SELECT * FROM dados_clientes WHERE id='" . $_GET["editar"] . "' AND status = 'A' LIMIT 0, 1";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	
	$linha=mysql_fetch_array($resultado);

	$id 				= $linha["id"];
	$apelido			= $linha["apelido"];
	$nome 				= $linha["nome"];
	$email 				= $linha["email"];
	$cnpj				= $linha["cnpj"];
	$cpf				= $linha["cpf"];
	$endereco			= $linha["endereco"] . ($linha['numero'] != '' ? ', ' . $linha['numero'] : '') . ($linha['complemento'] != '' ? ', ' . $linha['complemento'] : '');
	$cep				= $linha["cep"];
	$cidade				= $linha["cidade"];
	$estado				= $linha["estado"];
	
	$pref_telefone		= $linha["pref_telefone"];
	$telefone			= $linha["telefone"];

	$tipo 				= ($cnpj != '' ? 'PJ' : 'PF');
}



$arrEstados = array();
$sql = "SELECT * FROM estados ORDER BY sigla";
$result = mysql_query($sql) or die(mysql_error());
while($estados = mysql_fetch_array($result)){
	array_push($arrEstados,array('id'=>$estados['id'],'sigla'=>$estados['sigla']));
}



if($_GET['act'] == 'new' || $_GET["editar"]){ ?>

<?=$textoAcao;?></div>

  <form name="form_cliente" id="form_cliente" method="post" action="meus_dados_clientes_gravar.php">
  <input type="hidden" name="acao" value="<?=$acao?>" />
    <table border="0" cellpadding="0" cellspacing="3" class="formTabela">

  <tr>
    <td width="115" align="right" valign="middle" class="formTabela">Tipo:</td>
    <td class="formTabela" colspan="3">
    	<label for="pf" style="margin-right: 20px;"><input name="rdbTipoCliente" id="pf" type="radio" value="PF" <?=$tipo == 'PF' || !isset($tipo) ? 'checked="checked"' : ''?>> Pessoa Física</label>
    	<label for="pj"><input name="rdbTipoCliente" id="pj" type="radio" value="PJ" <?=$tipo == 'PJ' ? 'checked="checked"' : ''?>> Pessoa Jurídica</label>
    </td>
  </tr>

  <tr>
    <td width="115" align="right" valign="middle" class="formTabela">Apelido:</td>
    <td class="formTabela" colspan="3">
    	<input name="Apelido" id="Apelido" type="text" size="50" maxlength="25" value="<?=$apelido?>" alt="Apelido" />
    	<input name="ApelidoAntigo" id="ApelidoAntigoy" type="hidden" value="<?=$apelido?>" alt="Apelido" />
    </td>
  </tr>

  <tr>
    <td width="115" align="right" valign="middle" class="formTabela" id="celulaNome">Nome:</td>
    <td class="formTabela" colspan="3"><input name="Nome" id="Nome" type="text" size="50" maxlength="50" value="<?=$nome?>" alt="Nome" /></td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela" id="celulaLabelDocumento"><?=$tipo == 'PF' || !isset($tipo) ? 'CPF' : 'CNPJ'?>:</td>
    <td class="formTabela" style="color: #000;" colspan="3">
	    <input name="CPF" id="CPF" type="text" value="<?=$cpf?>" style="width:150px;display:<?=$tipo == 'PF' || !isset($tipo) ? 'block' : 'none'?>" size="18" maxlength="14" alt="CPF" class="cpf" />
	    <input name="CNPJ" id="CNPJ" type="text" value="<?=$cnpj?>" style="width:150px;display:<?=$tipo == 'PJ' ? 'block' : 'none'?>;" size="18" maxlength="18" alt="CNPJ" class="cnpj" />
    </td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela">E-mail:</td>
    <td class="formTabela" style="color: #000;" colspan="3">
	    <input name="Email" id="Email" type="text" value="<?=$email?>" style="width:205px;" maxlength="75" alt="Email" /> (contato de cobrança)
      	</td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela">Endere&ccedil;o:</td>
    <td class="formTabela" colspan="3"><input name="Endereco" id="Endereco" type="text" style="width:300px" value="<?=$endereco?>" maxlength="50" alt="Endereço" /></td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela">CEP:</td>
    <td class="formTabela" colspan="3"><input name="CEP" id="CEP" type="text" style="width:80px" value="<?=$cep?>" maxlength="9" alt="CEP" class="cep" /> 
      <span style="font-size:10px; display: none">(somente números)</span></td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela">Estado:</td>
    <td class="formTabela" colspan="3"><select name="Estado" id="Estado" alt="Estado">
      <option value="" <?php echo selected( '', $estado ); ?> ></option>
          <?
            foreach($arrEstados as $dadosEstado){
				echo "<option value=\"".$dadosEstado['id'].";".$dadosEstado['sigla']."\" " . selected( $dadosEstado['sigla'], $estado ) . " >".$dadosEstado['sigla']."</option>";
				if($dadosEstado['sigla'] == $estado){
					$idEstadoSelecionado = $dadosEstado['id'];
				}
            }
		?>
    </select></td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela">Cidade:</td>
    <td class="formTabela" colspan="3"><!--<input name="Cidade" id="Cidade" type="text" style="width:300px" value="<?=$cidade?>" maxlength="200" alt="Cidade" />-->
            <select name="Cidade" id="Cidade" style="width:300px" class="comboM" alt="Cidade">
            <option value="" <?php echo selected( '', $cidade ); ?>></option>
			<?
			if($idEstadoSelecionado != ''){
				$sql = "SELECT * FROM cidades WHERE id_uf = '" . $idEstadoSelecionado . "' ORDER BY cidade";
				$result = mysql_query($sql) or die(mysql_error());
				while($cidades = mysql_fetch_array($result)){
					echo "<option value=\"".$cidades['cidade']."\" " . selected( $cidades['cidade'], $cidade) . " >".$cidades['cidade']."</option>";
				}
			}
            ?>
            </select>
    </td>
  </tr>
  
  <tr>
    <td align="right" valign="middle" class="formTabela">Telefone:</td>
    <td valign="middle" colspan="3" class="formTabela">
      <div style="float:left; margin-right: 3px;"><input name="pref_telefone" type="text" id="pref_telefone" style="width:30px" value="<?=$pref_telefone?>" maxlength="2" /></div>
      <div style="float:left"><input name="telefone" type="text" id="telefone" style="width:75px" value="<?=$telefone?>" maxlength="9" /></div></td>
  </tr>
  
  <tr>
    <td colspan="4" valign="middle" class="formTabela">&nbsp;<input type="hidden" name="hidID" id="hidID" value="<?=$id?>" /><input type="hidden" name="hidID2" id="hidID2" value="<?=$_SESSION["id_empresaSecao"]?>" /> </td>
  </tr>
  <tr>
    <td colspan="4" align="center" valign="middle" class="formTabela"><input type="button" value="Salvar" id="btSalvar" />
    	<input type="button" value="<?=isset($_GET["editar"]) ? "Voltar" : "Cancelar"?>" id="btCancelar" />
    </td>
    </tr>
</table>
</form>
<?
}else{
?>
</div>
<div style="text-align: right; margin-bottom:10px; width:70%"><a href="meus_dados_clientes.php?act=new">Cadastrar novo cliente</a></div>
<?
	// MONTAGEM DA LISTAGEM DOS AUTONOMOS
	$sql = "SELECT id, nome, apelido, cnpj, cpf FROM dados_clientes WHERE id_login='" . $_SESSION["id_empresaSecao"] . "' AND status = 'A' ORDER BY nome";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	
	function checa_cliente_lancamento($apelido, $nome, $idCliente){
		$sql = "SELECT * FROM user_" . $_SESSION['id_empresaSecao'] . "_livro_caixa WHERE categoria like '%" . mysql_real_escape_string($apelido) . "%' OR  categoria like '%" . mysql_real_escape_string($nome) . "%' LIMIT 0,1";
		$resultado = mysql_query($sql)
		or die (mysql_error());
		if(mysql_num_rows($resultado) > 0){
			return "alert('Este cliente não pode ser excluído pois possui lançamentos no livro-caixa');return false;";
		}else{
			return "if(confirm('Você tem certeza que deseja excluir este cliente?'))location.href='meus_dados_clientes_excluir.php?excluir=".$idCliente."';";
		}
		
	}
	
?>
    <table width="70%" cellpadding="5">
        <tr>
            <th width="65">Ação</th>
            <th width="400">Nome</th>
            <th width="25">Tipo</th>
            <th width="134">Documento</th>
        </tr>

<?
	if(mysql_num_rows($resultado) > 0){

		// TRAZENDO OS DADOS PARA MONTAR TABELA DE AUTONOMOS
		while($linha=mysql_fetch_array($resultado)){
			$id 		= $linha["id"];
			$nome 		= $linha["nome"];
			$apelido	= $linha["apelido"];
			$documento 	= ($linha["cnpj"] != '' ? $linha["cnpj"] : $linha["cpf"]);
			$tipo 		= ($linha["cnpj"] != '' ? 'PJ' : 'PF');
?>
            <tr>
                <td class="td_calendario" align="center"><a href="#" onClick="<?=checa_cliente_lancamento($apelido, $nome, $id)?>" title="Excluir"><i class="fa fa-trash-o iconesAzul iconesGrd"></i></a>
                <a href="meus_dados_clientes.php?editar=<?=$id?>" title="Editar"><i class="fa fa-pencil-square-o iconesAzul iconesGrd"></i></a></td>
                <td class="td_calendario"><?=$nome?></td>
                <td class="td_calendario"><?=$tipo?></td>
                <td class="td_calendario"><?=$documento?></td>
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
            <td class="td_calendario">&nbsp;</td>        
        </tr>
<?		
	}
}
?>
    </table>


</div>
</div>
<script>
<?
if($_SESSION['mensagem_altera_clientes'] != ''){
	echo "alert('".$_SESSION['mensagem_altera_clientes']."')";
	$_SESSION['mensagem_altera_clientes'] = "";
}
?>
</script>

<?php include 'rodape.php' ?>