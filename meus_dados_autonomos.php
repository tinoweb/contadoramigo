<?php include 'header_restrita.php' ?>

<script type="text/javascript">

<?
// quebrando a url
//$arrUrl_origem = explode('/',$_SERVER['HTTP_REFERER']);
$arrUrl_origem = explode('/',$_SERVER['PHP_SELF']);
// VARIAVEL com o nome da página
$pagina_origem = $arrUrl_origem[count($arrUrl_origem) - 1];
	
//if(!isset($_SESSION['paginaOrigem'])){
//if((stripos($_SERVER['HTTP_REFERER'],'pagamento_') > 0 && !stripos($_SESSION['paginaOrigem'],'pagamento_')) || (isset($_GET['editar']))){
//if($_SESSION['paginaOrigemAutonomos'] != 'pagamento_autonomos.php' || isset($_GET['editar'])){
//	$_SESSION['paginaOrigemAutonomos'] = $pagina_origem;
//}
if(!isset($_SESSION['paginaOrigemAutonomos']) || $_SESSION['paginaOrigemAutonomos'] == ''){
	$_SESSION['paginaOrigemAutonomos'] = $pagina_origem;
}


echo " var PaginaOrigem = '" . $_SESSION['paginaOrigemAutonomos'] . "';";
?>


jQuery(document).ready(function() {		

	$('#btCancelar').click(function(){
		location.href = PaginaOrigem;
	});
	

	$('#btSalvar').click(function(){
		if($('#CPF').val()!='' && $('#PIS').val() != ''){
			var erro = false;
			$.ajax({
				url:'meus_dados_autonomos_checa.php?id=' + $('#hidID').val() + '&cpf=' + $('#CPF').val() + '&pis=' + $('#PIS').val() + '&idLogin=' + $('#hidID2').val(),
				type: 'get',
				cache: false,
				async: true,
				success: function(retorno){
//					alert(retorno); 
					if(retorno != ''){
						alert('Já existe um autônomo cadastrado com esses dados!');  
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

			
	function formSubmit(){   
		if( validElement('nome', msg1) == false){return false}
		if( validElement('CBO', msg1) == false){return false}
		if( validElement('CPF', msg1) == false){return false}
		if( validElement('RG', msg1) == false){return false}
		if( validElement('orgao_emissor', msg1) == false){return false}
		if( validElement('PIS', msg1) == false){return false}
		if( validElement('NumeroDep', msg1) == false){return false}
		if( validElement('Email', msg1) == false){return false}

		var email = document.getElementById('Email');
		if(email.value != '' && !$.validateEmail(email.value)){
				email.focus();
				window.alert('E-mail inválido!');
				return false;
		}

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

</script>

<?php
$acao = 'inserir';


function selected( $value, $prev ){
   return $value==$prev ? ' selected="selected"' : ''; 
};
?>
<div class="principal minHeight">

	<h1>Meus Dados</h1>
<h2>Cadastro de Autônomos
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
	// ALTERAÇÂO DE AUTONOMOS
	$sql = "SELECT * FROM dados_autonomos WHERE id='" . $_GET["editar"] . "' LIMIT 0, 1";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	
	$linha=mysql_fetch_array($resultado);

	$id 					= $linha["id"];
	$nome 					= $linha["nome"];
	$cbo					= $linha["cbo"];
	$cpf					= $linha["cpf"];
	$rg						= $linha["rg"];	
	if(strlen($rg)>0){
		$rg = preg_replace("/\W/","",$rg);
	}

	$orgao_emissor			= $linha["orgao_emissor"];
	$pis					= $linha["pis"];
	$dependentes			= $linha["dependentes"];
	$email					= $linha["email"];
	$endereco				= $linha["endereco"];
	$cep					= $linha["cep"];
	$cidade					= $linha["cidade"];
	$estado					= $linha["estado"];
	$tipo_servico			= $linha["tipo_servico"];
	$pensao					= $linha["pensao"];
	$perc_pensao			= $linha["perc_pensao"];
	$inscr_municipal		= $linha["inscr_municipal"];
	$aliquota_ISS			= $linha["aliquota_ISS"];
	
	$tipo_conta				= $linha["tipo_conta"];
	$id_banco				= $linha["id_banco"];
	$num_agencia			= $linha["agencia"];
	$dig_agencia			= $linha["dig_agencia"];
	$num_conta				= $linha["conta"];
	$dig_conta				= $linha["dig_conta"];
	
	$pref_telefone	= $linha["pref_telefone"];
	$telefone				= $linha["telefone"];

	$tipo				= $linha["tipo"];
}


if($_GET['act'] == 'new' || $_GET["editar"]){ ?>

	<?=$textoAcao;?></h2>

  <form name="form_autonomo" id="form_autonomo" method="post" action="meus_dados_autonomos_gravar.php">
  <input type="hidden" name="acao" value="<?=$acao?>" />
<input type="hidden" name="paginaOrigem" value="<?=$_SESSION['paginaOrigem']?>" />
	
	O autônomo desempenha tarefas: 
	<select name="tipo">
		<option value="">selecione</option>
		<option value="Exclusivamente Administrativas" <?=$tipo == 'Exclusivamente Administrativas' ? 'selected' : ''?> >Exclusivamente Administrativas</option>
		<option value="Administrativas e Operacionais" <?=$tipo == 'Administrativas e Operacionais' ? 'selected' : ''?>>Administrativas e Operacionais</option>
		<option value="Exclusivamente Operacionais" <?=$tipo == 'Exclusivamente Operacionais' ? 'selected' : ''?>>Exclusivamente Operacionais</option>
	</select>
	<br><br>	
    <table border="0" cellpadding="0" cellspacing="3" width="100%" class="formTabela">
  <tr>
    <td width="115" align="right" valign="middle" class="formTabela">Nome:</td>
    <td class="formTabela"><input name="nome" id="nome" type="text" size="50" maxlength="50" value="<?=$nome?>" alt="Nome" /></td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela">CBO:</td>
    <td class="formTabela" >  <input name="CBO" id="CBO" type="text" size="8" maxlength="10" value="<?=$cbo?>" alt="CBO" />
        <img class="imagemDica" src="images/dica.gif" width="13" height="14" border="0" align="texttop" div="balloon_cbo" />
</td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela">CPF:</td>
    <td class="formTabela" style="color: #000;">
	    <input name="CPF" id="CPF" type="text" value="<?=$cpf?>" size="14" maxlength="14" alt="CPF" class="cpf" />
      	<div name="divCPF" id="divCPF" style="display:none"></div>
      	</td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela">RG:</td>
    <td class="formTabela">
      <input name="RG" id="RG" type="text" value="<?=$rg?>" size="14" alt="RG" maxlength="12" class="" />&nbsp;&nbsp;&nbsp;<span style="color:#666">Órgão Emissor</span>
      <input name="orgao_emissor" id="orgao_emissor" type="text" size="14" maxlength="25" value="<?=$orgao_emissor?>" alt="Órgão Emissor" /></td>
    </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela">PIS:</td>
    <td class="formTabela" style="color: #000">
	    <input name="PIS" id="PIS" type="text" value="<?=$pis?>" size="14" maxlength="14" alt="PIS" class="pis" />
      	</td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela">Nº de dependentes:</td>
    <td class="formTabela" style="color: #000;">
	    <input name="NumeroDep" id="NumeroDep" type="text" size=2 maxlength="2" value="<?=$dependentes?>" alt="Nº de dependentes" /> 
	    <span style="font-size:10px">(Somente os declarados como dependentes no IR)</span>
      	</td>
    </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">E-mail:</td>
    <td class="formTabela" ><input name="Email" id="Email" type="text" style="width:300px" value="<?=$email?>" maxlength="75" alt="Email" /></td>
  </tr>


  <tr>
    <td align="right" valign="middle" class="formTabela">Endere&ccedil;o:</td>
    <td class="formTabela" ><input name="Endereco" id="Endereco" type="text" style="width:300px" value="<?=$endereco?>" maxlength="200" alt="Endereço" /></td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela">CEP:</td>
    <td class="formTabela"><input name="CEP" id="CEP" type="text" style="width:80px" value="<?=$cep;//str_replace(array("/","-","."),"",$linha["cep"])?>" maxlength="9" alt="CEP" class="cep" /> 
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
      <div style="float:left; margin-right: 3px;"><input name="pref_telefone" type="text" id="pref_telefone" style="width:30px" value="<?=$pref_telefone?>" maxlength="2" /></div>
      <div style="float:left"><input name="telefone" type="text" id="telefone" style="width:75px" value="<?=$telefone?>" maxlength="9" /></div></td>
  </tr>
    
  <tr>
    <td align="right" valign="middle" class="formTabela">Presta serviços de:</td>
    <td class="formTabela" style="color: #000;" colspan="3">
	    <input name="tipo_servico" id="tipo_servico" type="text" size="60" maxlength="250" value="<?=$tipo_servico?>" alt="Tipo de Serviço" /> 
      	</td>
  </tr>

  <tr>
    <td align="left" valign="middle" class="formTabela" colspan="2"><div style="float: left;">Autônomo paga pensão alimentícia? <input name="pensao" id="pensao" type="checkbox" value="1" <?=$pensao == 1 ? 'checked' : ''?> />&nbsp;&nbsp;&nbsp;&nbsp;<label for="PercentPensao">Qual o percentual?</label> <input name="PercentPensao" id="PercentPensao" type="text" size=4 maxlength="50" value="<?=$perc_pensao != 0 ? $perc_pensao : ''?>" /> %</div> <div style="float: left; padding: 5px 0 0 10px; font-size:10px">(Considerar apenas a pensão alimentícia quando definida em acordo judicial)</div>
</td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela">Inscrição Municipal:</td>
    <td class="formTabela"><input name="inscricao_municipal" id="inscricao_municipal" type="text" value="<?=$inscr_municipal?>" size="12" maxlength="50" alt="Inscrição Municipal" /></td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela">Alíquota ISS:</td>
    <td class="formTabela" ><input name="AliquotaISS" id="AliquotaISS" type="text" value="<?=$aliquota_ISS != '' ? number_format($aliquota_ISS,2,',','.') : ''?>" size="6" maxlength="6" class="current" alt="Alíquota ISS" /> % 
            <img class="imagemDica" src="images/dica.gif" width="13" height="14" border="0" align="texttop" div="balloon_iss" />

    </td>
  </tr>



  
  <tr>
    <td align="left" valign="middle" class="formTabela" colspan="2"><br />
	<strong>Dados Bancários</strong></td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela">Banco:</td>
    <td class="formTabela"><select name="id_banco" id="id_banco" alt="">
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
	
	$_SESSION['paginaOrigemAutonomos'] = $pagina_origem;
	
?>
	</h2>
	
<div style="text-align: right; margin-bottom:10px; width:100%"><a href="meus_dados_autonomos.php?act=new">Cadastrar novo autônomo</a></div>
		<table width="100%" cellpadding="5">
        	<tr>
            	<th width="80">Açâo</th>
            	<th>Nome</th>
            	<th>CPF</th>
            	<th>PIS</th>
            </tr>
<?
	// MONTAGEM DA LISTAGEM DOS AUTONOMOS
	$sql = "SELECT * FROM dados_autonomos WHERE id_login='" . $_SESSION["id_empresaSecao"] . "' ORDER BY nome";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	if(mysql_num_rows($resultado) > 0){

		// TRAZENDO OS DADOS PARA MONTAR TABELA DE AUTONOMOS
		while($linha=mysql_fetch_array($resultado)){
			$id 	= $linha["id"];
			$nome 	= $linha["nome"];
			$cpf 	= $linha["cpf"];
			$pis 	= $linha["pis"];
?>
            <tr>
                <td class="td_calendario" align="center">
                <a href="#" onClick="if (confirm('Você tem certeza que deseja excluir este autônomo?'))location.href='meus_dados_autonomos_excluir.php?excluir=<?=$id?>';" title="Excluir"><i class="fa fa-trash-o iconesAzul iconesGrd"></i></a>
                <a href="meus_dados_autonomos.php?editar=<?=$id?>" title="Editar"><i class="fa fa-pencil-square-o iconesAzul iconesGrd"></i></a>
                </td>
                <td class="td_calendario"><?=$nome?></td>
                <td class="td_calendario"><?=$cpf?></td>
                <td class="td_calendario"><?=$pis?></td>
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

<!-- ************************************--BALLOM ISS **********************************-->
<div style="width:310px; position:absolute; display:none;" id="balloon_iss" class="bubble_left box_visualizacao x_visualizacao">

	<div style="padding:20px;">
	    <strong>Alíquota ISS</strong> - refere-se ao percentual que o autônomo deve pagar de <strong>ISS</strong>. A taxa varia de acordo com o município e a atividade desenvolvida. Pergunte a seu prestador qual a alíquota dele e se há algum tipo de isenção, ou consulte a tabela de ISS do seu município. Na dúvida, você pode deixar 5%, que é o teto permitido por lei federal. Dessa forma você estará seguro.
    </div>
    
</div>
<!-- ***********************************FIM DO BALLOOM ISS***************************** -->


<!-- ************************************--BALLOM CBO **********************************-->
<div style="width:310px; position:absolute; display:none;" id="balloon_cbo" class="bubble_left box_visualizacao x_visualizacao">

	<div style="padding:20px;">
	    <strong>CBO</strong> - significa Código Brasileiro de Ocupações. Informe o código da atividade desenvolvida pelo autônomo. Para saber o número, consulte <a href="http://www.mtecbo.gov.br/cbosite/pages/home.jsf" target="_blank">este site</a>.
    </div>
    
</div>
<!-- ***********************************FIM DO BALLOOM CBO***************************** -->

</div>
</div>
<script>
<?
if($_SESSION['mensagem_altera_autonomos'] != ''){
	echo "alert('".$_SESSION['mensagem_altera_autonomos']."')";
	$_SESSION['mensagem_altera_autonomos'] = "";
}
?>
</script>

<?php include 'rodape.php' ?>