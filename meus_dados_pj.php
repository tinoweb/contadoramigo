<?php include 'header_restrita.php' ?>

<?
$dados_empresa = mysql_fetch_array(mysql_query("SELECT cidade FROM dados_da_empresa WHERE id='" . $_SESSION["id_empresaSecao"] . "' LIMIT 0, 1"));

//	echo stripos($_SESSION['paginaOrigem'],'pagamento_') >= 0;
//	echo $_SESSION['paginaOrigem'];
?>

<script type="text/javascript">

<?
// quebrando a url
//$arrUrl_origem = explode('/',$_SERVER['HTTP_REFERER']);
$arrUrl_origem = explode('/',$_SERVER['PHP_SELF']);
// VARIAVEL com o nome da página
$pagina_origem = $arrUrl_origem[count($arrUrl_origem) - 1];
	
	
//if(!isset($_SESSION['paginaOrigem'])){
//if($_SESSION['paginaOrigem'] != 'pagamento_pj.php' || isset($_GET['editar'])){
//	$_SESSION['paginaOrigem'] = $pagina_origem;
//}

if(!isset($_SESSION['paginaOrigemPJ']) || $_SESSION['paginaOrigemPJ'] == ''){
	$_SESSION['paginaOrigemPJ'] = $pagina_origem;
}


echo " var PaginaOrigem = '" . $_SESSION['paginaOrigemPJ'] . "';";
?>

 var msg1 = 'É necessário preencher o campo';
 var msg2 = 'É necessário selecionar ';
 var msg3 = 'No momento o Contador Amigo não oferece suporte para empresas do ramo de Comércio e Indústria.';
 var msg4 = 'No momento o Contador Amigo não oferece suporte para empresas com Lucro Presumido.';
// var msg5 = 'Empresas de outros estados ou cidades podem usar o Contador Amigo, porém não terão suporte referente à emissão de notas fiscais e para pagamento da TFE/TFA, cujos valores são determinados pelas respectivas prefeituras. Geralmente estes impostos chegam pelo correio e são pagos apenas 1 vez por ano.';

$(document).ready(function() {		


    function formSubmit() {

		if( validElement('nome', msg1) == false){return false}
		if( validElement('CNPJ', msg1) == false){return false}
		if( validElement('Endereco', msg1) == false){return false}
		if( validElement('CEP', msg1) == false){return false}
		
		var Estado = $('#Estado');
		if(Estado.val() == ""){
			window.alert(msg2+'o Estado.');
			Estado.focus();
			return false;
		}
		
		var Cidade = $('#Cidade');
		if(Cidade.val() == '') {
			window.alert(msg2+'a Cidade.');
			Cidade.focus();
			return false;
		}

		if(!$('input[name="radMei"]').is(':checked')){
			alert('Selecione se o Fornecedor é MEI!');
			$('input[name="radMei"]').focus();
			return false;
		}

		if(!$('input[name="radSimples"]').is(':disabled') && !$('input[name="radSimples"]').is(':checked')){
			alert('Selecione se o Fornecedor é optante pelo SIMPLES!');
			$('input[name="radSimples"]').focus();
			return false;
		}

		if($('#linhaCPOM').css('display') == 'block' && !$('input[name="radCPOM"]').is(':checked')){
			alert('Selecione se o Fornecedor tem registro no CPOM!');
			$('input[name="radCPOM"]').focus();
			return false;
		}
		
		$('#form_pj').submit();	
    }
	

	var CIDADE = "<?=$dados_empresa['cidade'] != '' ? $dados_empresa['cidade'] : ''?>";

	$('#btCancelar').click(function(){
		location.href = PaginaOrigem;
	});
	

	$('#btSalvar').click(function(){
		var erro = false;
		if($('#CNPJ').val()!=''){
			$.ajax({
				url:'meus_dados_pj_checa.php?id=' + $('#hidID').val() + '&idLogin=' + $('#hidID2').val() + '&cnpj=' + $('#CNPJ').val(),
				type: 'get',
				async: 	false,
				cache: false,
				success: function(retorno){
					if(retorno == 1){
						alert('Já existe uma empresa fornecedora cadastrada com este CNPJ!');  
						erro = true;
					}else{

						if(erro == false){
							
							formSubmit();	
							
						}	
					}
				}
			});
		}else{
			
			formSubmit();	

		}

	});

	$('input[name="radSimples"]').click(function(){
		if($('input[name="radMei"]:checked').val() == '1'){ // SE FOR MEI OPTANTE PELO SIMPLES SEMPRE 1 (sim)
			$('input[name="radSimples"][value="1"]').attr('checked',true);
		}
	});


	$('input[name="radMei"]').click(function(){
		if($('input[name="radMei"]:checked').val() == '1'){ // SE FOR MEI OPTANTE PELO SIMPLES SEMPRE 1 (sim)
			$('input[name="radSimples"][value="1"]').attr('checked',true);
			$('input[name="radSimples"]').attr('disabled',true);
		}else{
			$('input[name="radSimples"][value="1"]').attr('checked',false);
			$('input[name="radSimples"]').attr('disabled',false);
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


	$('#Cidade').bind('change',function(){
		if(CIDADE == 'São Paulo' && $(this).val() != CIDADE){
			$('#linhaCPOM').css('display','block');
		}else{
			$('#linhaCPOM').css('display','none');
		}
	});
});

		

</script>

<?php


$acao = 'inserir';
$op_simples = 0;

function selected( $value, $prev ){
   return $value==$prev ? ' selected="selected"' : ''; 
};
function checked( $value, $prev ){
   return $value==$prev ? ' checked="checked"' : ''; 
};

?>
<div class="principal minHeight">

<h1>Meus Dados</h1>
<h2>Cadastro de Pessoa Jurídica (fornecedora)
<?
$textoAcao = "- Incluir";

$arrEstados = array();
$sql = "SELECT * FROM estados ORDER BY sigla";
$result = mysql_query($sql) or die(mysql_error());
while($estados = mysql_fetch_array($result)){
	array_push($arrEstados,array('id'=>$estados['id'],'sigla'=>$estados['sigla']));
}


if($_GET["editar"]){
	$textoAcao = "- Editar";
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
	
	$pref_telefone			= $linha["pref_telefone"];
	$telefone				= $linha["telefone"];

	$op_simples				= $linha["op_simples"];
	$mei					= $linha["mei"];
	$CPOM					= $linha["cpom"];

}
?>

<? if($_GET['act'] == 'new' || $_GET["editar"]){ ?>

	<?=$textoAcao;?></h2>

<!--BALLOM CPOM -->
<div style="width:310px; position:absolute; display:none;" id="cpom" class="bubble_left box_visualizacao x_visualizacao">

	<div style="padding:20px;">
        CPOM quer dizer Cadastro de Prestadores de Outros Municípios. Normalmente, quando o prestador é de outra cidade, ele precisa fazer este cadastro na Prefeitura em que o tomador do serviço (no caso, você) está sediado para ter isenção de ISS. <br>
<br>
Para saber se o prestador está registrado no CPOM de São Paulo acesse <a href="https://www3.prefeitura.sp.gov.br/cpom2/Consulta_Tomador.aspx" target="_blank">este link</a> e informe no campo indicado o cnpj dele.    
    </div>
    
</div>
<!--FIM DO BALLOOM CPOM -->

<form name="form_pj" id="form_pj" method="post" action="meus_dados_pj_gravar.php">
<input type="hidden" name="acao" value="<?=$acao?>" />
<input type="hidden" name="paginaOrigem" value="<?=$_SESSION['paginaOrigem']?>" />

<table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="margin-bottom: 10px;">
  <tr>
    <td width="124" align="right" valign="middle" class="formTabela">Empresa:</td>
    <td width="464" class="formTabela"><input name="nome" id="nome" type="text" size="50" maxlength="50" value="<?=$nome?>" alt="Empresa" /></td>
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
    <td align="right" valign="middle" class="formTabela">Estado:</td>
    <td class="formTabela"><select name="Estado" id="Estado" alt="Estado">
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
    <td class="formTabela"><!--<input name="Cidade" id="Cidade" type="text" style="width:300px" value="<?=$cidade?>" maxlength="200" alt="Cidade" />-->
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
      <div style="float:left;margin-right: 3px;"><input name="pref_telefone" type="text" id="pref_telefone" style="width:30px" value="<?=$pref_telefone?>" maxlength="2" /></div>
      <div style="float:left"><input name="telefone" type="text" id="telefone" style="width:75px" value="<?=$telefone?>" maxlength="9" /></div></td>
  </tr>
</table>

<div style="width: 600px; float:left; margin-bottom: 10px;">
	<div style="width: 124px; float:left; margin-right: 10px; text-align: right;">Fornecedor é MEI?</div>
	<div style="width: 464px; float:left;">
      <label style="margin-right:10px"><input type="radio" name="radMei" id="radMei1" value="1" <?php if($mei != '') {echo @checked( 1, $mei);} ?> /> Sim</label>
      <label><input type="radio" name="radMei" id="radMei2" value="0" <?php if($mei != '') {echo @checked( 0, $mei);} ?> /> Não</label>
    </div>
</div>
<div style="clear:both;"></div>

<div style="width: 600px; float:left; margin-bottom: 10px;">
	<div style="width: 124px; float:left; margin-right: 10px; text-align: right;">Optante pelo Simples?</div>
	<div style="width: 464px; float:left;">
      <label style="margin-right:10px"><input type="radio" name="radSimples" id="radSimples1" value="1" <?php if($op_simples != '') {echo @checked( 1, $op_simples);} ?> <?=$mei == 1 ? 'disabled' : ''?> /> Sim</label>
      <label><input type="radio" name="radSimples" id="radSimples2" value="0" <?php if($op_simples != '') {echo @checked( 0, $op_simples);} ?> <?=$mei == 1 ? 'disabled' : ''?> /> Não</label>
    </div>
</div>
<div style="clear:both;"></div>


<div id="linhaCPOM" style="width: 600px; float:left; margin-bottom: 10px; display: <?=($dados_empresa['cidade'] == 'São Paulo' && $cidade != $dados_empresa['cidade'] ? 'block' : 'none')?>">
	<div style="width: 124px; float:left; margin-right: 10px; text-align: right;">Registro no CPOM?</div>
	<div style="width: 464px; float:left;">
		<div style="float: left;">
      <label style="margin-right:10px"><input type="radio" name="radCPOM" id="radCPOM1" value="1" <?php if($CPOM != '') {echo @checked( 1, $CPOM);} ?> /> Sim</label>
      <label><input type="radio" name="radCPOM" id="radCPOM2" value="0"  <?php if($CPOM != '') {echo @checked( 0, $CPOM);} ?> /> Não</label>
		</div>    
		<div style="float: left;">
          <div style="margin-left:10px;">
              <img class="imagemDica" src="images/dica.gif" width="13" height="14" border="0" align="texttop" div="cpom" />
          </div>
		</div>    
    </div>
</div>
<div style="clear:both;"></div>
  
  
<table border="0" cellpadding="0" cellspacing="3" class="formTabela">
  <tr>
    <td align="left" valign="middle" class="formTabela" colspan="2"><br />
    <strong>Dados Bancários</strong></td>
  </tr>

  <tr>
    <td width="124" align="right" valign="middle" class="formTabela">Banco:</td>
    <td width="464" class="formTabela"><select name="id_banco" id="id_banco" alt="">
      <option value="" <?php echo selected( '', $id_banco ); ?> ></option>
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
    <td class="formTabela"><input name="agencia" id="agencia" type="text" style="width:80px" value="<?=$num_agencia?>" maxlength="9" alt="" class="inteiro" /> <input name="dig_agencia" id="dig_agencia" type="text" style="width:30px" value="<?=$dig_agencia?>" maxlength="2" alt="" class="inteiro" /> 
    </td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela">Número Conta:</td>
    <td class="formTabela"><input name="conta" id="conta" type="text" style="width:80px" value="<?=$num_conta?>" maxlength="9" alt="" class="inteiro" /> <input name="dig_conta" id="dig_conta" type="text" style="width:30px" value="<?=$dig_conta?>" maxlength="2" alt="" class="inteiro" />
    </td>
  </tr>
  
  <tr>
    <td colspan="2" valign="middle" class="formTabela">&nbsp;<input type="hidden" name="hidID" id="hidID" value="<?=$id?>" /><input type="hidden" name="hidID2" id="hidID2" value="<?=$_SESSION["id_empresaSecao"]?>" /> </td>
  </tr>
  <tr>
    <td colspan="2" align="center" valign="middle" class="formTabela"><input type="button" value="Salvar" id="btSalvar" />
    	<input type="button" value="<?=isset($_GET["editar"]) ? "Voltar" : "Cancelar"?>" id="btCancelar" />
    </td>
    </tr>
</table>
</form>
<?




}else{
	// LISTAGEM
	
	$_SESSION['paginaOrigemPJ'] = $pagina_origem;
	
	
	
	
	
?>
	</h2>
<div style="text-align: right; margin-bottom:10px; width:100%"><a href="meus_dados_pj.php?act=new">Cadastrar nova empresa</a></div>
<?
	// MONTAGEM DA LISTAGEM DOS AUTONOMOS
	$sql = "SELECT * FROM dados_pj WHERE id_login='" . $_SESSION["id_empresaSecao"] . "' ORDER BY nome";
	$resultado = mysql_query($sql)
	or die (mysql_error());
?>
    <table width="100%" cellpadding="5">
        <tr>
            <th width="80">Açâo</th>
            <th>Nome</th>
            <th>CNPJ</th>
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
                <td class="td_calendario" align="center"><a href="#" onClick="if (confirm('Você tem certeza que deseja excluir esta empresa fornecedora?'))location.href='meus_dados_pj_excluir.php?excluir=<?=$id?>';" title="Excluir"><i class="fa fa-trash-o iconesAzul iconesGrd"></i></a>
                <a href="meus_dados_pj.php?editar=<?=$id?>" title="Editar"><i class="fa fa-pencil-square-o iconesAzul iconesGrd"></i></a></td>
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
<script>
<?
if($_SESSION['mensagem_altera_pj'] != ''){
	echo "alert('".$_SESSION['mensagem_altera_pj']."')";
	$_SESSION['mensagem_altera_pj'] = "";
}
?>
</script>

<?php include 'rodape.php' ?>