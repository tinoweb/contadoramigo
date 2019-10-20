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


	if( validElement('txtOrigem', msg1) == false){return false}
	if( validElement('txtDestino', msg1) == false){return false}
	if( validRadio('radTipo', msg2) == false){return false}
	if( validElement('txtTarifa', msg1) == false){return false}
	if( validRadio('radTrajeto', msg2) == false){return false}

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
	$sql = "SELECT idTransporte, idFuncionario, origem, destino, tipo, trajeto FROM dados_transporte_funcionario WHERE idFuncionario = '" . $_SESSION['idFuncionario'] . "'";
	//echo ($sql);
	$resultado = mysql_query($sql)
	or die (mysql_error());
	
	if(mysql_num_rows($resultado) == 1){
		$transporte = mysql_fetch_array($resultado);
		$idTransporte = $transporte['idTransporte'];
		$mostrar_cadastrar_novo = true;
	}
	
	if($_GET["editar"]){
	
		$idTransporte = $_GET["editar"];
	
	}
	
	if($idTransporte){

		$textoAcao = "- Editar";
		$acao = 'editar';
		// ALTERAÇÂO DE AUTONOMOS
		$sql = "SELECT * FROM dados_transporte_funcionario WHERE idTransporte='" . $idTransporte . "' LIMIT 0, 1";
		$consulta = mysql_query($sql)
		or die (mysql_error());
		
		$dados=mysql_fetch_array($consulta);
	
		$idTransporte 			= $dados["idTransporte"];
		$idFuncionario			= $dados["idFuncionario"];
		$origem					= $dados["origem"];
		$destino				= $dados["destino"];
		$tipo					= $dados["tipo"];
		$linha 					= $dados["linha"];
		$empresa				= $dados["empresa"];
		$tarifa					= number_format($dados["tarifa"],2,',','');
		$trajeto				= $dados["trajeto"];

	}
}

//echo "idTransporte: " . $idTransporte;
//echo "idFuncionario: " . $idFuncionario;
//echo "origem: " . $origem;
//echo "destino: " . $destino;
//echo "linha: " . $linha;
//echo "empresa: " . $empresa;
//echo "tarifa: " . $tarifa;
//echo "trajeto: " . $trajeto;
//echo "tipo: " . $tipo;

?>

<div class="tituloVermelho" style="margin-bottom:20px">Cadastro de Vale Transporte 

<?

if($_GET['act'] == 'new' || $_GET["editar"] || mysql_num_rows($resultado) == 1){ 


echo $textoAcao?>
</div>
 
<form method="post" name="form_transporte" id="form_transporte" action="meus_dados_transporte_funcionario_gravar.php" >
  <input type="hidden" name="hidFuncioanrioID" value="<?=$idFuncionario?>" />
  <input type="hidden" name="hidTransporteID" value="<?=$idTransporte?>" />
  <input type="hidden" name="pgOrigem" value="<?=basename($_SERVER['HTTP_REFERER'])?>" />
  <input type="hidden" name="acao" value="<?=$acao?>" />
  
<table border="0" cellpadding="0" cellspacing="3" style="background:none" class="formTabela">
 <tr>
    <td align="right" valign="middle" class="formTabela">Trajeto:</td>
    <td class="formTabela">
      <input type="radio" name="radTrajeto" value="ida" alt="Trajeto" <?php echo checked( 'ida', $trajeto ); ?> /> Ida</label>
      <input type="radio" name="radTrajeto" value="volta" <?php echo checked( 'volta', $trajeto ); ?> /> Volta</label>
    </td>
 </tr>
 <tr>
    <td align="right" valign="middle" class="formTabela">Tipo de condução:</td>
    <td class="formTabela">
      <input type="radio" name="radTipo" value="ônibus" alt="Tipo de condução" <?php echo checked( 'ônibus', $tipo ); ?> /> Ônibus</label>
      <input type="radio" name="radTipo" value="metrô" <?php echo checked( 'metrô', $tipo ); ?> /> Metrô</label>
      <input type="radio" name="radTipo" value="trem" <?php echo checked( 'trem', $tipo ); ?> /> Trem</label>
    </td>
 </tr>
 <tr>
   <td align="right" valign="middle" class="formTabela">Ponto de origem:</td>
   <td class="formTabela" width="300"><input name="txtOrigem" type="text" id="txtOrigem" style="width:300px" value="<?=$origem?>" maxlength="100" alt="Ponto de origem" /> </td>
 </tr>
 <tr>
   <td align="right" valign="middle" class="formTabela">Ponto de destino:</td>
   <td class="formTabela" width="300"><input name="txtDestino" type="text" id="txtDestino" style="width:300px" value="<?=$destino?>" maxlength="100" alt="Ponto de destino" /> </td>
 </tr>
 <tr>
   <td align="right" valign="middle" class="formTabela">Nome ou nº da Linha:</td>
   <td class="formTabela" width="300"><input name="txtLinha" type="text" id="txtLinha" style="width:300px" value="<?=$linha?>" maxlength="100" alt="Nome ou nº da Linha" /> </td>
 </tr>
 <tr>
   <td align="right" valign="middle" class="formTabela">Empresa prestadora:</td>
   <td class="formTabela" width="300"><input name="txtEmpresa" type="text" id="txtEmpresa" style="width:300px" value="<?=$empresa?>" maxlength="100" alt="Empresa prestadora" /> </td>
 </tr>
 <tr>
   <td align="right" valign="middle" class="formTabela">Tarifa:</td>
   <td class="formTabela" width="300"><input name="txtTarifa" type="text" id="txtTarifa" style="width:50px" value="<?=$tarifa?>" maxlength="5" alt="Tarifa" class="current" /> </td>
 </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela">&nbsp;</td>
    <td class="formTabela">
		<input type="button" value="Salvar alterações" id="btSalvar" onClick="formSubmit('form_transporte')" />
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
  if($mostrar_cadastrar_novo){
  ?>
  	<div style="text-align: left; margin-bottom:10px; width:75%"><a href="meus_dados_transporte_funcionario.php?act=new">Cadastrar novo Vale Transporte</a></div>
	<?	
  }
  

} else {
?>
</div>

<div style="text-align: right; margin-bottom:10px; width:75%"><a href="meus_dados_transporte_funcionario.php?act=new">Cadastrar novo Vale Transporte</a></div>
		<table width="75%" cellpadding="5">
        	<tr>
            	<th width="80">Açâo</th>
            	<th>Trajeto</th>
            	<th>Origem</th>
            	<th>Destino</th>
            	<th>Tipo</th>
            </tr>
<?

	if(mysql_num_rows($resultado) > 1){
		
		$esconde_botao_excluir = false;
		if(mysql_num_rows($resultado) == 1){ $esconde_botao_excluir = true;}
		
		// TRAZENDO OS DADOS PARA MONTAR TABELA DE AUTONOMOS
		while($linha=mysql_fetch_array($resultado)){
			$id 		= $linha["idTransporte"];
			$trajeto 	= $linha["trajeto"];
			$origem 	= $linha["origem"];
			$destino 	= $linha["destino"];
			$tipo 		= $linha["tipo"];
			
			if($status == 2){
				$esconde_botao_excluir = true;
			}else{
				$esconde_botao_excluir = false;
			}
?>
            <tr>
                <td class="td_calendario" align="center"><?=!$esconde_botao_excluir ? '<a href="#" onClick="if (confirm(\'Você tem certeza que deseja excluir este Registro?\'))location.href=\'meus_dados_transporte_funcionario_excluir.php?vt='.$id.'\';"><img src="images/del.png" width="24" height="23" border="0" title="Excluir" /></a>' : ''?>
                <a href="meus_dados_transporte_funcionario.php?editar=<?=$id?>"><img src="images/edit.png" width="24" height="23" border="0" title="Editar" /></a></td>
                <td class="td_calendario"><?=$trajeto?></td>
                <td class="td_calendario"><?=$origem?></td>
                <td class="td_calendario"><?=$destino?></td>
                <td class="td_calendario"><?=$tipo?></td>
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


