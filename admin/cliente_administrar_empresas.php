<?php include '../conect.php';
include '../session.php';
include 'check_login.php'; ?>
<?php
$idUsuario = $_GET["id"];

function selected( $value, $prev ){
   return $value==$prev ? ' selected="selected"' : ''; 
};
function checked( $value, $prev ){
   return $value==$prev ? ' checked="checked"' : ''; 
};

?>
<link href="../estilo.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" media="screen" href="../estilo/font-awesome.min.css?v"><!--estilo font-awesome-->
<link rel="stylesheet" media="screen" href="../ballon.css?v"><!--estilo ballon CSS -->

<script type="text/javascript" src="../scripts/jquery_1_7.js"></script>
<script type="text/javascript" src="../scripts/meusScripts.js"></script>
<script type="text/javascript" src="../scripts/jquery.maskedinput.js"></script>
<script type="text/javascript" src="../scripts/jquery.form.js"></script>

<script type="text/javascript">
	function add() {
		var orig = document.getElementById('content');
		var count = parseInt(document.getElementById('count').value);
		var newDiv = document.createElement('div');
		newDiv.setAttribute("id", "item"+count);
		var newContent = "<div style=\"float:left; margin-right:5px\"><input name=\"txtCodigoCNAE"+count+"\" id=\"txtCodigoCNAE"+count+"\" type=\"text\" style=\"width:84px; margin-top:3px;\" class=\"campoCNAE2\"></div> <div id=\"atividade"+count+"\" style=\"float:left; margin-top:5px\"><input type=\"hidden\" id=\"pesquisaCampo"+count+"\" name=\"pesquisaCampo"+count+"\" value=\"ok\" /></div> <div style=\"clear:both\"> </div>";
		newDiv.innerHTML = newContent;
		orig.appendChild(newDiv);
		document.getElementById('count').value = count+1;
	}	
	function remove() {
		var count = parseInt(document.getElementById('count').value);
		if (count > 1) {				
			var orig = document.getElementById('content');
			var removeDiv = document.getElementById('item'+(count-1));
			orig.removeChild(removeDiv);			
			document.getElementById('count').value = count - 1;
		}
	}
	
	function addPrefeitura() {
		var orig = document.getElementById('contentPrefeitura');
		var count = parseInt(document.getElementById('countPrefeitura').value);
		var newDiv = document.createElement('div');
		newDiv.setAttribute("id", "itemPrefeitura"+count);
		var newContent = '<input name="txtCodigoAtividadePrefeitura'+count+'" id="txtCodigoAtividadePrefeitura'+count+'" type="text" style="width:50px; float:left; margin-right:3px; margin-top:3px" value="" maxlength="5" onblur="consultaBanco(\'../meus_dados_empresa_consulta_codigo_prefeitura.php?codigo=\'+document.getElementById(\'txtCodigoAtividadePrefeitura'+count+'\').value+\'&campo='+count+'\', \'denomicacaoPrefeitura'+count+'\');" /><div id="denomicacaoPrefeitura'+count+'" style="float:left; width:210px"><input type="hidden" id="pesquisaCampoPrefeitura'+count+'" name="pesquisaCampoPrefeitura'+count+'" value="ok" /></div><div style="clear:both"> </div>';
		newDiv.innerHTML = newContent;
		orig.appendChild(newDiv);
		document.getElementById('countPrefeitura').value = count+1;
	}	
	function removePrefeitura() {
		var count = parseInt(document.getElementById('countPrefeitura').value);
		if (count > 2) {				
			var orig = document.getElementById('contentPrefeitura');
			var removeDiv = document.getElementById('itemPrefeitura'+(count-1));
			orig.removeChild(removeDiv);			
			document.getElementById('countPrefeitura').value = count - 1;
		}
	}
	
	$(document).ready(function(e) {
		
	var count = parseInt($('#count').val()) - 1;

 $('.btAdicionaCNAE').bind('click',function(e){
	 e.preventDefault();
	// reposicionaBallons();
		$('#count').val(count+1);

		var orig = $('#content');
		count = parseInt($('#count').val());
		
		orig.append('<div id="item'+count+'"><div style="float:left; margin-right:5px"><input name="txtCodigoCNAE'+count+'" id="txtCodigoCNAE'+count+'" type="text" style="width:84px; margin-top:3px;" class="campoCNAE" div="atividade'+count+'" check="hddCodigoCNAE'+count+'"><input type="hidden" name="hddCodigoCNAE'+count+'" id="hddCodigoCNAE'+count+'" value=""></div> <div id="atividade'+count+'" style="float:left; margin-top:5px"></div><div style="clear:both"></div></div>');
		
		$('.campoCNAE').mask('9999-9/99');
		
	
		$('input[id^="txtCodigoCNAE"]').bind('change',function(){
//			alert('1');
			var 
				$campo 	= $(this)
				, $div		= $campo.attr("div")
				, $campoCheck		= $('#' + $campo.attr('check'))
				, $idEmp = $('#hidID').val();			
			;
			
//			alert($div);
//			alert($campo.attr('check'));
//			alert($idEmp);
			
			if($campo.val() != ''){
				var URL = '../meus_dados_empresa_consulta_cnae.php?acao=blur&codigo=' + $campo.val() + '&campoCheck=' + $campoCheck.val() + '&idEmpresa=' + $idEmp
				$.consultaBancoAjax(URL, $('#'+$div), $campo);
			}
			
		});
		
 });
 
 
	$('.btRemoveCNAE').bind('click',function(e){
	 e.preventDefault();
	 //reposicionaBallons();
		count = parseInt($('#count').val());
		if (count > 1) {				
//			var orig = $('#content');
//			var removeDiv = document.getElementById('item'+(count-1));
			$('#item'+(count-1)).remove();	
			$('#count').val(count-1);
		}
	});
		
   		$('#btLimparHistorico').click(function(e){
			if(confirm('Deseja mesmo limpar o histórico deste usuário?')){
				location.href = 'cliente_administrar.php?id=<?=$id?>&limpar_historico=<?=$id?>';
			}
		});
		$('#link_ver').bind('click',function(){
//			window.open('mostra_sessao_usuario.php?email=<?=$linhalogin["email"]?>&senha=<?=md5($linhalogin['senha'])?>');
			window.open('mostra_sessao_usuario.php?email=<?=$linhalogin["email"]?>&senha=<?=md5($linhalogin['senha'])?>');
		});
		
	
		$.mostraResultado = function(texto, qualDiv){
			qualDiv.html(texto);
		}
		
	
		$.consultaBancoAjax = function(URL, qualDiv, oCampo){
	//		alert(URL);
				$.ajax({
					url: URL,
					type: 'get',
					dataType:"json",
					cache: false,
					async: true,
					beforeSend: function(){
					},
					success: function(retorno){
						if(!retorno.status){
							alert(retorno.mensagem)
							$.mostraResultado('', qualDiv);
							oCampo.val('').focus();
						}else{
							$.mostraResultado(retorno.mensagem, qualDiv);
						}
					}
				});
		};
	
	
		$.enviaAjax = function(URL, DATA, METHOD, DATATYPE, SUCCESS){
				return $.ajax({
					url: URL,
					data: DATA,
					type: METHOD,
					dataType: DATATYPE,
					cache: false,
					async: false,
					beforeSend: function(){
					},
					success: SUCCESS
				});
		};
	
	
		function getQTDEmpresasCadastradas(URL, DATA, METHOD, DATATYPE, SUCCESS){
			return $.enviaAjax(URL, DATA, METHOD, DATATYPE, SUCCESS);
		}

		
		$('#txtCNAE_Principal, input[id^="txtCodigoCNAE"]').change(function(){
			var 
				$campo 	= $(this)
				, $div		= $campo.attr('div')
				, $campoCheck		= $('#' + $campo.attr('check'))
				, $idEmpresa = $('#hidID').val();			
			;
			if($campo.val() != ''){
				var URL = '../meus_dados_empresa_consulta_cnae.php?acao=blur&codigo=' + $campo.val() + '&campoCheck=' + $campoCheck.val() + '&idEmpresa=' + $idEmpresa
				$.consultaBancoAjax(URL, $('#'+$div), $campo);
			}
			
		});



  });

</script>


<?php



if(isset($_GET['editar']) && $_GET['editar'] != ''){
	
	
	$id = $_GET["editar"];
	$sql = "SELECT * FROM dados_da_empresa WHERE id='" . $id . "' LIMIT 0, 1";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	
	$linha=mysql_fetch_array($resultado);
?>

	<div class="tituloVermelho">Edição de dados da Empresa</div>

  <form name="form_empresa" id="form_empresa" method="post" action="cliente_administrar_gravar_empresa.php"  style="margin:0px"><!-- onSubmit="opener.location.reload();" -->
  <input type="hidden" name="hidID" id="hidID" value="<?=$linha["id"]?>" />
  <table border="0" cellpadding="0" cellspacing="3" style="background:none" class="formTabela">
    <tr>
      <td colspan="2" align="right" valign="middle" class="formTabela">&nbsp;</td>
      </tr>
    <tr>
      <td colspan="2" align="right" valign="top" class="formTabela"></td>
      </tr>
    <tr>
      <td width="173" align="right" valign="middle" class="formTabela">Razão Social:</td>
      <td class="formTabela"><input name="txtRazaoSocial" id="txtRazaoSocial" type="text" style="width:250px; margin-bottom:0px" value="<?=$linha["razao_social"]?>" maxlength="200"  alt="Razão Social"  /></td>
    </tr>
    <tr>
      <td align="right" valign="middle" class="formTabela">Nome Fantasia:</td>
      <td class="formTabela"><input name="txtNomeFantasia" id="txtNomeFantasia" type="text" style="width:250px" value="<?=$linha["nome_fantasia"]?>" maxlength="200"  /></td>
    </tr>
    <tr>
      <td align="right" valign="middle" class="formTabela">CNPJ:</td>
      <td class="formTabela"><input name="txtCNPJ" id="txtCNPJ" type="text" style="width:125px" value="<?=$linha["cnpj"]?>" maxlength="18" alt="CNPJ" class="campoCNPJ" /></td>
    </tr>
      <tr>
      <?php	
  //		$sql2 = "SELECT * FROM dados_da_empresa_codigos WHERE id='" . $id . "' AND tipo='1' LIMIT 0,1";
      $sql2 = "SELECT t1.cnae,t2.denominacao FROM dados_da_empresa_codigos t1 INNER JOIN cnae t2 ON t1.cnae = t2.cnae WHERE t1.id='" . $id . "' AND  t1.tipo='1' LIMIT 0,1";
      $resultado2 = mysql_query($sql2)
      or die (mysql_error());
  
      $linha2=mysql_fetch_array($resultado2);
    ?>
      <td align="right" valign="top" class="formTabela">C&oacute;digo da Atividade Principal:</td><td  class="formTabela"> <input name="txtCNAE_Principal" id="txtCNAE_Principal" type="text" style="width:64px; float:left; margin-right:3px" value="<?=$linha2["cnae"]?>" maxlength="9" class="campoCNAE" div="atividadePrincipal" check="hddCNAEPrincipal" />
        <div id="atividadePrincipal" style="float:left; width:199px"><?=$linha2['denominacao']?></div>
        </td>
      <?php	
  //$sql2 = "SELECT * FROM dados_da_empresa_codigos WHERE id='" . $id . "' AND tipo='2' ORDER BY idCodigo ASC";
  $sql2 = "SELECT t1.cnae,t2.denominacao FROM dados_da_empresa_codigos t1 INNER JOIN cnae t2 ON t1.cnae = t2.cnae WHERE t1.id='" . $id . "' AND  t1.tipo='2' ORDER BY t2.cnae";
  $resultado2 = mysql_query($sql2)
  or die (mysql_error());
  
  $totalResultados = mysql_num_rows($resultado2);
  ?>
      </tr>
      <tr style="margin-top:-3px">
      <td align="right" valign="top" class="formTabela" style="padding-top:3px">C&oacute;digo das Atividades Secundárias:</td>
      <td class="formTabela" valign="top">
      <div id="content">
  <?php if ($totalResultados!="0") {
  $campo = 1;	
  while ($linha2=mysql_fetch_array($resultado2)) {
  ?>
            <div id="item<?=$campo?>">
              <div style="float:left; margin-right:5px"><input name="txtCodigoCNAE<?=$campo?>" id="txtCodigoCNAE<?=$campo?>" type="text" style="width:64px; margin-top:3px;" value="<?=$linha2["cnae"]?>" maxlength="9" class="campoCNAE" div="atividade<?=$campo?>" check="hddCodigoCNAE<?=$campo?>" /></div>
              <div id="atividade<?=$campo?>" style="float:left; width:199px"><?=$linha2['denominacao']?></div>
              <div style="clear:both"> </div>
  </div>
  <?php 
  $campo = $campo + 1;
  } }
  ?>
  </div>
  <a href="#" class="btAdicionaCNAE">Adicionar</a> | <a href="#" class="btRemoveCNAE">Remover</a>
        <input type="hidden" id="count" name="skill_count" value="<?php if ($totalResultados=="0") {echo "1";} else {echo $totalResultados + 1;} ?>">
    </td>
    </tr>
    <tr>
      <td align="right" valign="middle" class="formTabela">Inscri&ccedil;&atilde;o municipal:</td>
      <td class="formTabela"><input name="txtInscricaoCCM" id="txtInscricaoCCM" type="text" style="width:90px" value="<?=$linha["inscricao_no_ccm"]?>" maxlength="17" /></td>
    </tr>
    <tr>
      <td align="right" valign="middle" class="formTabela">Inscri&ccedil;&atilde;o estadual:</td>
      <td class="formTabela"><input name="txtInscricaoEstadual" id="txtInscricaoEstadual" type="text" style="width:90px" value="<?=$linha["inscricao_estadual"]?>" maxlength="17" /></td>
    </tr>
    <tr>
      <td align="right" valign="middle" class="formTabela">Endere&ccedil;o:</td>
      <td class="formTabela"><input name="txtEndereco" id="txtEndereco" type="text" style="width:250px" value="<?=$linha["endereco"]?>" maxlength="250" alt="Endereço" /></td>
    </tr>
    <tr>
      <td align="right" valign="middle" class="formTabela">Bairro:</td>
      <td class="formTabela"><input name="txtBairro" id="txtBairro" type="text" style="width:250px" value="<?=$linha["bairro"]?>" maxlength="200" alt="Complemento" /></td>
    </tr>
    <tr>
      <td align="right" valign="middle" class="formTabela">CEP:</td>
      <td class="formTabela"><input name="txtCEP" id="txtCEP" type="text" style="width:70px" value="<?=$linha["cep"]?>" maxlength="9" alt="CEP" class="campoCEP" /></td>
    </tr>
    <tr>
      <td align="right" valign="middle" class="formTabela">Cidade:</td>
      <td class="formTabela"><input name="txtCidade" id="txtCidade" type="text" style="width:250px" value="<?=$linha["cidade"]?>" maxlength="200" alt="Cidade" /></td>
    </tr>
    <tr>
      <td align="right" valign="middle" class="formTabela">Estado:</td>
      <td class="formTabela"><select name="selEstado" id="selEstado">
        <option value="">Selecione...</option>
        <option value="AC" <?php echo selected( 'AC', $linha['estado'] ); ?> >AC</option>
        <option value="AL" <?php echo selected( 'AL', $linha['estado'] ); ?> >AL</option>
        <option value="AM"  <?php echo selected( 'AM', $linha['estado'] ); ?> >AM</option>
        <option value="AP"  <?php echo selected( 'AP', $linha['estado'] ); ?> >AP</option>
        <option value="BA"  <?php echo selected( 'BA', $linha['estado'] ); ?> >BA</option>
        <option value="CE"  <?php echo selected( 'CE', $linha['estado'] ); ?> >CE</option>
        <option value="DF" <?php echo selected( 'DF', $linha['estado'] ); ?> >DF</option>
        <option value="ES" <?php echo selected( 'ES', $linha['estado'] ); ?> >ES</option>
        <option value="GO" <?php echo selected( 'GO', $linha['estado'] ); ?> >GO</option>
        <option value="MA" <?php echo selected( 'MA', $linha['estado'] ); ?> >MA</option>
        <option value="MG" <?php echo selected( 'MG', $linha['estado'] ); ?> >MG</option>
        <option value="MS" <?php echo selected( 'MS', $linha['estado'] ); ?> >MS</option>
        <option value="MT" <?php echo selected( 'MT', $linha['estado'] ); ?> >MT</option>
        <option value="PA" <?php echo selected( 'PA', $linha['estado'] ); ?> >PA</option>
        <option value="PB" <?php echo selected( 'PB', $linha['estado'] ); ?> >PB</option>
        <option value="PE" <?php echo selected( 'PE', $linha['estado'] ); ?> >PE</option>
        <option value="PI" <?php echo selected( 'PI', $linha['estado'] ); ?> >PI</option>
        <option value="PR" <?php echo selected( 'PR', $linha['estado'] ); ?> >PR</option>
        <option value="RJ" <?php echo selected( 'RJ', $linha['estado'] ); ?> >RJ</option>
        <option value="RN" <?php echo selected( 'RN', $linha['estado'] ); ?> >RN</option>
        <option value="RO" <?php echo selected( 'RO', $linha['estado'] ); ?> >RO</option>
        <option value="RR" <?php echo selected( 'RR', $linha['estado'] ); ?> >RR</option>
        <option value="RS" <?php echo selected( 'RS', $linha['estado'] ); ?> >RS</option>
        <option value="SC" <?php echo selected( 'SC', $linha['estado'] ); ?> >SC</option>
        <option value="SE" <?php echo selected( 'SE', $linha['estado'] ); ?> >SE</option>
        <option value="SP" <?php echo selected( 'SP', $linha['estado'] ); ?> >SP</option>
        <option value="TO" <?php echo selected( 'TO', $linha['estado'] ); ?> >TO</option>
      </select></td>
    </tr>
    <tr>
      <td align="right" valign="middle" class="formTabela">Telefone:</td>
      <td valign="middle" class="formTabela"><div style="float:left; margin-right:3px; margin-top:3px; font-size:12px">(</div>
        <div style="float:left"><input name="txtPrefixoTelefone" id="txtPrefixoTelefone" type="text" style="width:50px" value="<?=$linha["pref_telefone"]?>" maxlength="3" class="inteiro" /></div>
        <div style="float:left; margin-left:3px; margin-right:3px; margin-top:3px; font-size:12px">)</div>
        <div style="float:left"><input name="txtTelefone" id="txtTelefone" type="text" style="width:75px" value="<?=$linha["telefone"]?>" maxlength="9" class="inteiro" /></div></td>
    </tr>
    <tr>
      <td align="right" valign="middle" class="formTabela">Ramo de Atividade:</td>
      <td class="formTabela"><select name="selRamoAtividade" id="selRamoAtividade">
        <option value="">Selecione...</option>
        <option value="Comércio" onClick="javascript:alert(msg3)" <?php echo selected( 'Comércio', $linha['ramo_de_atividade'] ); ?> >Comércio</option>
        <option value="Indústria" onClick="javascript:alert(msg3)" <?php echo selected( 'Indústria', $linha['ramo_de_atividade'] ); ?> >Indústria</option>
        <option value="Prestação de Serviços" <?php echo selected( 'Prestação de Serviços', $linha['ramo_de_atividade'] ); ?> >Prestação de Serviços</option>
        </select></td>
    </tr>

    <tr>
      <td align="right" valign="middle" class="formTabela">Regime de Tributação:</td>
      <td class="formTabela"><select name="selRegimeTributacao" id="selRegimeTributacao">
        <option value="">Selecione...</option>
        <option value="Simples" <?php echo selected( 'Simples', $linha['regime_de_tributacao'] ); ?> >Simples</option>
        <option value="Lucro Presumido" onClick="javascript:alert(msg4)" <?php echo selected( 'Lucro Presumido', $linha['regime_de_tributacao'] ); ?> >Lucro Presumido</option>
      </select></td>
    </tr>
  <tr>
      <td align="right" valign="top" class="formTabela">Tipo de empresa:</td>
      <td class="formTabela">
      <select name="selInscritaComo" id="selInscritaComo" onchange="selecionaRegistro()" style="float:left">
        <option value="Empresa Individual" <?php echo selected( 'Empresa Individual', $linha['inscrita_como'] ); ?>  onchange="selecionaRegistro()" >Empresa Individual</option>
        <option value="Empresa Individual de Responsabilidade Limitada (EIRELI)" <?php echo selected( 'Empresa Individual de Responsabilidade Limitada', $linha['inscrita_como'] ); ?>  onchange="selecionaRegistro()" >Empresa Individual (EIRELI)</option>
        <option value="Sociedade Empresária Limitada" <?php echo selected( 'Sociedade Empresária Limitada', $linha['inscrita_como'] ); echo selected( 'Sociedade Empresarial Limitada', $linha['inscrita_como'] ); ?> onchange="selecionaRegistro()">Sociedade Empresária Limitada</option>
        <option value="Sociedade Simples" <?php echo selected( 'Sociedade Simples', $linha['inscrita_como'] ); ?> onchange="selecionaRegistro()" >Sociedade Simples</option>
      </select>
    </td>
    </tr>
  <tr>
      <td colspan="2" class="formTabela">
            <table border="0" width="100%" cellpadding="0" cellspacing="3" class="formTabela" id="divRegistroNire" style="background:none<?php if ($linha['inscrita_como'] == 'Sociedade Simples' ) { echo '; display:none'; } ?>">
      <tr>
          <td align="right" valign="middle" class="formTabela" width="170">Registro NIRE:</td>
        <td class="formTabela"><input name="txtRegistroNire" id="txtRegistroNire" type="text" style="width:90px; float:left; margin-right:3px" value="<?=$linha["registro_nire"]?>" maxlength="12" alt="Cidade" class="campoNIRE" /> 
        <div style="float:left; margin-right:5px; margin-top:5px"><span style="font-size:10px">9999999999-9</span></div></td>
          </tr>
        </table>
        
        <table border="0" width="100%" cellpadding="0" cellspacing="3" class="formTabela" id="divRegistroCartorio" style="background:none<?php if ($linha['inscrita_como'] != 'Sociedade Simples' ) { echo '; display:none'; } ?>">
      <tr>
          <td align="right" valign="middle" class="formTabela" width="170">Número do Cartório:</td>
        <td class="formTabela"><input name="txtNumCartorio" id="txtNumCartorio" type="text" style="width:55px; float:left; margin-right:3px" value="<?=$linha["numero_cartorio"]?>" maxlength="7" alt="Cidade" />	</td>
          </tr>
      <tr>
        <td align="right" valign="middle" class="formTabela">Registro no Cartório:</td>
        <td class="formTabela"><input name="txtRegistroCartorio" id="txtRegistroCartorio" type="text" style="width:150px; float:left; margin-right:3px" value="<?=$linha["registro_cartorio"]?>" maxlength="20" alt="Cidade" /></td>
        </tr>
        </table>
      </td>
      </tr>
    <tr>
      <td align="right" valign="middle" class="formTabela">Data de Cria&ccedil;&atilde;o:</td>
      <td class="formTabela"><input name="txtDataCriacao" id="data_de_criacao" type="text" style="width:75px" value="<?=$linha["data_de_criacao"]?>" maxlength="10" class="campoData" /></td>
    </tr>
      <tr>
        <td colspan="2" valign="middle" class="formTabela"></td>
        </tr>
      <tr>
        <td colspan="2" align="center" valign="middle" class="formTabela"><input type="submit" value="Salvar" /></td>
        </tr>
    </table>
    </form>
<? } ?>