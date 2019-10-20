<?php include 'header_restrita.php' ?>

<script type="text/javascript">

<?
// quebrando a url
$arrUrl_origem = explode('/',$_SERVER['HTTP_REFERER']);
// VARIAVEL com o nome da página
$pagina_origem = $arrUrl_origem[count($arrUrl_origem) - 1];
	
//if(!isset($_SESSION['paginaOrigem'])){
if($_SESSION['paginaOrigem'] != 'pagamento_autonomos.php' || isset($_GET['editar'])){
	$_SESSION['paginaOrigem'] = $pagina_origem;
}

echo " var PaginaOrigem = '" . $_SESSION['paginaOrigem'] . "';";
?>


jQuery(document).ready(function() {		

	$('#btCancelar').click(function(){
		location.href = 'meus_dados_tomadores.php';
	});
	

	$('#btSalvar').click(function(){
		if($('#boleto_cnpj').val()!=''){
			var erro = false;
			$.ajax({
				url:'meus_dados_tomadores_checa.php?id=' + $('#hidID').val() + '&cei=' + $('#boleto_cnpj').val(),
				type: 'get',
				cache: false,
				async: true,
				success: function(retorno){
					if(retorno == 1){
						alert('Já existe um tomador cadastrado com esses dados!');  
						erro = true; 
						return false;
					}
				}
			});
		}
		if(erro == false){
			
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
		if( validElement('boleto_cnpj', msg1) == false){return false}
		if( validElement('Endereco', msg1) == false){return false}
		if( validElement('CEP', msg1) == false){return false}
		if( validElement('Cidade', msg1) == false){return false}
		if( validElement('Estado', msg1) == false){return false}
		if( validElement('Bairro', msg1) == false){return false}
		
		var Cidade = document.getElementById('Cidade');
		if(Cidade.value.toLowerCase() == 'são paulo' || Cidade.value.toLowerCase() == 'sao paulo') {
			Cidade.value = 'São Paulo';
		}
		var Estado = document.getElementById('Estado');
		if(Estado.selectedIndex == ""){
			window.alert(msg2+'o Estado.');
			return false;
		}
		document.getElementById('form_tomador').submit()			
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
<div class="tituloVermelho" style="margin-bottom:20px">Cadastro de Tomadores (Construção Civil)</div>
<?
$textoAcao = "Cadastrar novo tomador:";

$arrEstados = array();
$sql = "SELECT * FROM estados ORDER BY sigla";
$result = mysql_query($sql) or die(mysql_error());
while($estados = mysql_fetch_array($result)){
	array_push($arrEstados,array('id'=>$estados['id'],'sigla'=>$estados['sigla']));
}


if($_GET["editar"]){
	$textoAcao = "Editar dados do tomador:";
	$acao = 'editar';
	// ALTERAÇÂO DE TOMADORES
	$sql = "SELECT * FROM dados_tomadores WHERE id='" . $_GET["editar"] . "' LIMIT 0, 1";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	
	$linha=mysql_fetch_array($resultado);

	$id 					= $linha["id"];
	$nome 					= $linha["nome"];
	$cei					= $linha["cei"];
	$endereco				= $linha["endereco"];
	$cep					= $linha["cep"];
	$cidade					= $linha["cidade"];
	$estado					= $linha["estado"];
	$bairro					= $linha["bairro"];
}
?>

<? if($_GET['act'] == 'new' || $_GET["editar"]){ ?>

<div style="margin-bottom:20px"><?=$textoAcao;?></div>

  <form name="form_tomador" id="form_tomador" method="post" action="meus_dados_tomadores_gravar.php">
  <input type="hidden" name="acao" value="<?=$acao?>" />
    <table border="0" cellpadding="0" cellspacing="3" class="formTabela">
  <tr>
    <td width="115" align="right" valign="middle" class="formTabela">Nome:</td>
    <td class="formTabela"><input name="nome" id="nome" type="text" size="50" maxlength="50" value="<?=$nome?>" alt="Nome" /></td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela">CNPJ/CEI:</td>
    <td class="formTabela" >  <input type="text" name="CEI" id="boleto_cnpj" maxlength="18" size="18" class="campoCNPJ" value="<?=$cei?>" style="display: block;" alt="CNPJ">
	</td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela">Endere&ccedil;o:</td>
    <td class="formTabela" ><input name="Endereco" id="Endereco" type="text" style="width:300px" value="<?=$endereco?>" maxlength="200" alt="Endereço" /></td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela">Bairro:</td>
    <td class="formTabela"><input name="Bairro" id="Bairro" type="text" value="<?=$bairro?>" size="40" maxlength="20" alt="Bairro" /></td>
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

<div style="text-align: right; margin-bottom:10px; width:70%"><a href="meus_dados_tomadores.php?act=new">Cadastrar novo tomador</a></div>
<?
	// MONTAGEM DA LISTAGEM DOS TOMADORES
	$sql = "SELECT * FROM dados_tomadores WHERE id_login='" . $_SESSION["id_empresaSecao"] . "'";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	if(mysql_num_rows($resultado) > 0){
?>
		<table width="70%" cellpadding="5">
        	<tr>
            	<th width="80">Açâo</th>
            	<th>Nome</th>
            	<th>CNPJ/CEI</th>
            </tr>
<?
		// TRAZENDO OS DADOS PARA MONTAR TABELA DE TOMADORES
		while($linha=mysql_fetch_array($resultado)){
			$id 	= $linha["id"];
			$nome 	= $linha["nome"];
			$cei 	= $linha["cei"];
?>
            <tr>
                <td class="td_calendario" align="center"><a href="#" onClick="if (confirm('Você tem certeza que deseja excluir este tomador?'))location.href='meus_dados_tomadores_excluir.php?excluir=<?php echo $id?>';"><img src="images/del.png" width="24" height="23" border="0" title="Excluir" /></a>
                <a href="meus_dados_tomadores.php?editar=<?=$id?>"><img src="images/edit.png" width="24" height="23" border="0" title="Editar" /></a></td>
                <td class="td_calendario"><?=$nome?></td>
                <td class="td_calendario"><?=$cei?></td>
            </tr>
<?	
		}
?>
		</table>
<?
	}else{
?>
		Nenhum tomador cadastrado
<?		
	}
}
?>


</div>
</div>
<?
if($_SESSION['mensagem_altera_tomadores'] != ''){
	echo "<script>alert('".$_SESSION['mensagem_altera_tomadores']."')</script>";
	$_SESSION['mensagem_altera_tomadores'] = "";
}
?>

<?php include 'rodape.php' ?>