<?php 
include '../conect.php';
include '../session.php';
include 'check_login.php';
include '../classes/config.php';
?>

<?
	if ($_GET['acao'] == 'alterarSalario') { 
		$inicio = date('Y-m-d H:i:s', mktime(0,0,0,substr($_GET['inicio'],3,2),substr($_GET['inicio'],0,2),substr($_GET['inicio'],6,4)));
		if($_GET['fim'] != ''){
			$fim = date('Y-m-d H:i:s', mktime(23,59,59,substr($_GET['fim'],3,2),substr($_GET['fim'],0,2),substr($_GET['fim'],6,4)));
		}
		$sql="UPDATE salario_minimo	SET inicio_vigencia='" . $inicio . "'" . ($_GET['fim'] != '' ? ", fim_vigencia='" . $fim . "'" : "") . ", valor='" . $_GET['valor'] . "' WHERE id=" . $_GET['id'];
		mysql_query($sql)
		or die (mysql_error);
		
		header('location:indice.php');
		//echo "<script>location.href='indice.php'/script>";
	} 

	if ($_GET['acao'] == 'alterarTeto') { 
		$inicio = date('Y-m-d H:i:s', mktime(0,0,0,substr($_GET['inicio'],3,2),substr($_GET['inicio'],0,2),substr($_GET['inicio'],6,4)));
		if($_GET['fim'] != ''){
			$fim = date('Y-m-d H:i:s', mktime(23,59,59,substr($_GET['fim'],3,2),substr($_GET['fim'],0,2),substr($_GET['fim'],6,4)));
		}
		$sql="UPDATE teto_previdenciario SET inicio_vigencia='" . $inicio . "'" . ($_GET['fim'] != '' ? ", fim_vigencia='" . $fim . "'" : "") . ", valor='" . $_GET['valor'] . "' WHERE id=" . $_GET['id'];
		mysql_query($sql)
		or die (mysql_error);

		header('location:indice.php');
		//echo "<script>location.href='indice.php'/script>";
	} 

	if ($_GET['acao'] == 'inserirSalario') { 
		$inicio = date('Y-m-d H:i:s', mktime(0,0,0,substr($_GET['inicio'],3,2),substr($_GET['inicio'],0,2),substr($_GET['inicio'],6,4)));
		if($_GET['fim'] != ''){
			$fim = date('Y-m-d H:i:s', mktime(23,59,59,substr($_GET['fim'],3,2),substr($_GET['fim'],0,2),substr($_GET['fim'],6,4)));
		}
		$sql="INSERT INTO salario_minimo SET inicio_vigencia='" . $inicio . "'" . ($_GET['fim'] != '' ? ", fim_vigencia='" . $fim . "'" : "") . ", valor='" . $_GET['valor'] . "'";
		mysql_query($sql)
		or die (mysql_error);
		
		header('location:indice.php');
		//echo "<script>location.href='indice.php'/script>";
	} 

	if ($_GET['acao'] == 'inserirTeto') { 
		$inicio = date('Y-m-d H:i:s', mktime(0,0,0,substr($_GET['inicio'],3,2),substr($_GET['inicio'],0,2),substr($_GET['inicio'],6,4)));
		if($_GET['fim'] != ''){
			$fim = date('Y-m-d H:i:s', mktime(23,59,59,substr($_GET['fim'],3,2),substr($_GET['fim'],0,2),substr($_GET['fim'],6,4)));
		}
		$sql="INSERT INTO teto_previdenciario	SET inicio_vigencia='" . $inicio . "'" . ($_GET['fim'] != '' ? ", fim_vigencia='" . $fim . "'" : "") . ", valor='" . $_GET['valor'] . "'";
		mysql_query($sql)
		or die (mysql_error);

		header('location:indice.php');
		//echo "<script>location.href='indice.php'/script>";
	} 

	if ($_GET['acao'] == 'editarPlano') {
		 
		$sql="UPDATE configuracoes SET valor = " . $_GET['valor'] . " WHERE configuracao = '" . $_GET['configuracao'] . "' ";
		mysql_query($sql) or die (mysql_error);
		
		header('location:indice.php');
	} 
?>

<?php include 'header.php' ?>

<?php
/*$sql = "SELECT * FROM indices";
$resultado = mysql_query($sql)
or die (mysql_error());
$linha=mysql_fetch_array($resultado);

$Limite_Insencao = $linha['Limite_Insencao'];
$Salario_Minimo = $linha['Salario_Minimo'];
$Teto_Previdenciario = $linha['Teto_Previdenciario'];
$Contribuicao_Maxima = $linha['Contribuicao_Maxima'];
$Contribuicao_Minima = $linha['Contribuicao_Minima'];
$Desconto_Ir_Dependentes = $linha['Desconto_Ir_Dependentes'];

	if ($_POST['area'] == 'dependentes') { 
		
		$Limite_Insencao = $_POST['Limite_Insencao'];
		$Salario_Minimo = $_POST['Salario_Minimo'];
		$Teto_Previdenciario = $_POST['Teto_Previdenciario'];
		$Contribuicao_Maxima = $_POST['Contribuicao_Maxima'];
		$Contribuicao_Minima = $_POST['Contribuicao_Minima'];
		$Desconto_Ir_Dependentes = $_POST['Desconto_Ir_Dependentes'];
		
		
		$sql="UPDATE indices
		SET Limite_Insencao='$Limite_Insencao', Salario_Minimo='$Salario_Minimo', Teto_Previdenciario='$Teto_Previdenciario', Contribuicao_Maxima='$Contribuicao_Maxima', Contribuicao_Minima='$Contribuicao_Minima',Desconto_Ir_Dependentes='$Desconto_Ir_Dependentes'
		WHERE id=1";
		mysql_query($sql)
		or die (mysql_error);
	
?>
		<script>alert('dados atualizados com sucesso!')</script> 
<?php 

	} 
*/	



?>

<style>
.btSalvar{
	color: #0c0;
}
.btCancelar{
	color: #c00;
}
</style>

<div class="principal">

  <div class="titulo" style="margin-bottom:10px;">Índices</div>
<!--  
<div class="tituloVermelho" style="margin-bottom:10px">Planos de Assinatura</div>
-->
<?php
//Cria novo objeto de configuração
//$Config = new Config();

//Recebe array de valores de assinaturas
//$valores = $Config->listarValores();
?>
<!--
<table border="0" cellspacing="2" cellpadding="4" style="font-size:12px">	
<?php /* foreach ($valores as $item) {?>
	<?$plano_nome = $Config->verPlano($item['configuracao']);?>
	<tr>
		<td><?=$plano_nome?></td>
		<td>R$ <input type="text" id="txt_plano_<?=$item["configuracao"]?>" name="txt_plano" value="<?=$item["valor"]?>" class="inteiro" size="4" maxlength="3" /> <a href="#" class="editarPlano" data-configuracao="<?=$item['configuracao']?>">Editar</a></td>
	</tr>
<?php } */ ?>
</table>
-->

<br />
<div style="clear:both"> </div>

<div class="tituloVermelho" style="margin-bottom:10px">Salário Mínimo</div>

<table border="0" cellspacing="2" cellpadding="4" style="font-size:12px">
  <tr>
    <td style="background-color:#024a68; color:#FFF; padding-left:10px;">Início da Vigência</td>
    <td style="background-color:#024a68; color:#FFF; padding-left:10px;">Término da Vigência</td>
    <td style="background-color:#024a68; color:#FFF; padding-left:10px;">Valor (R$)</td>
    <td style="background-color:#024a68; color:#FFF; padding-left:10px;">Ação</td>
    <td></td>
  </tr>

<?
// MONTANDO ARRAY COM OS VALORES DOS SALARIOS MINIMOS E SUAS DATAS
$sql_sms = "SELECT * FROM salario_minimo ";
$resultado_sms = mysql_query($sql_sms)
or die (mysql_error());
// EXECUTA CONSULTA
$corLinha = "#FFF";
$mostra_linha_novo = true;
while($linha_sms = mysql_fetch_array($resultado_sms)){ // LOOP NO RESULTADO
	if ($corLinha == "#FFF") {
		$corLinha = "#E5E5E5";
	} else {
		$corLinha = "#FFF";
	} 
/*
para o jquery funcionar
echo"<tr class=\"guiaTabela\" style=\"background-color:".$corLinha."\"><input type=\"hidden\" id=\"ID".$linha_sms['id']."\" value=\"".$linha_sms['id']."\" ><td>".date('d/m/Y',strtotime($linha_sms['inicio_vigencia']))."</td><td>" . ($linha_sms['fim_vigencia'] <> 0 ? date('d/m/Y',strtotime($linha_sms['fim_vigencia'])) : " ") . "</td><td>" . $linha_sms['valor'] . "</td><td style=\"background-color: #f5f6f7\"><div class=\"editar\"><input type=\"button\" name=\"btEditar\" class=\"btEditar\" value=\"Alterar\"></div><div class=\"salvar\" style=\"display: none;\"><input type=\"button\" name=\"btSalvar\" class=\"btSalvar\" value=\"Salvar\"> <input type=\"button\" name=\"btCancelar\" class=\"btCancelar\" value=\"Cancelar\"></div></td>
	</tr>";*/
	echo "<tr>\n";
	echo "\t<td>\n\t\t<input type=\"hidden\" name=\"id\" value=\"".($linha_sms['id'])."\">\n";
	echo "\t\t<input type=\"text\" name=\"inicio\" value=\"".date('d/m/Y',strtotime($linha_sms['inicio_vigencia']))."\">\n\t</td>\n";
	echo "\t<td>\n\t\t<input type=\"text\" name=\"fim\" value=\"".($linha_sms['fim_vigencia'] <> 0 ? date('d/m/Y',strtotime($linha_sms['fim_vigencia'])) : "")."\">\n\t</td>\n";
	echo "\t<td>\n\t\t<input type=\"text\" name=\"valor\" value=\"".($linha_sms['valor'])."\">\n\t</td>\n";
	echo "\t<td>\n\t\t<a href=\"#\" class=\"editarSalario\">Editar</a>\n\t</td>\n";
	echo "</tr>\n";
	if($linha_sms['fim_vigencia'] == 0){
		$mostra_linha_novo = false;
	}
}

if($mostra_linha_novo == true){
	echo "<tr>\n";
	echo "\t<td>\n\t\t<input type=\"text\" name=\"inicio\" value=\"\">\n\t</td>\n";
	echo "\t<td>\n\t\t<input type=\"text\" name=\"fim\" value=\"\">\n\t</td>\n";
	echo "\t<td>\n\t\t<input type=\"text\" name=\"valor\" value=\"\">\n\t</td>\n";
	echo "\t<td>\n\t\t<a href=\"#\" class=\"inserirSalario\">Inserir</a>\n\t</td>\n";
	echo "</tr>\n";
}


?>

</table>
<br />
<div style="clear:both"> </div>

<div class="tituloVermelho" style="margin-bottom:10px">Teto Previdenciário</div>

<table border="0" cellspacing="2" cellpadding="4" style="font-size:12px">
  <tr>
    <td style="background-color:#024a68; color:#FFF; padding-left:10px;">Início da Vigência</td>
    <td style="background-color:#024a68; color:#FFF; padding-left:10px;">Término da Vigência</td>
    <td style="background-color:#024a68; color:#FFF; padding-left:10px;">Valor (R$)</td>
    <td style="background-color:#024a68; color:#FFF; padding-left:10px;">Ação</td>
    <td></td>
  </tr>

<?
// MONTANDO ARRAY COM OS VALORES DOS TETOS PREVIDENCIARIOS E SUAS DATAS
$sql_teto = "SELECT * FROM teto_previdenciario ";
$resultado_teto = mysql_query($sql_teto)
or die (mysql_error());
// EXECUTA CONSULTA
$corLinha = "#FFF";
$mostra_linha_novo = true;
while($linha_teto = mysql_fetch_array($resultado_teto)){ // LOOP NO RESULTADO
	if ($corLinha == "#FFF") {
		$corLinha = "#E5E5E5";
	} else {
		$corLinha = "#FFF";
	} 
/*
para o jquery funcionar
echo"<tr class=\"guiaTabela\" style=\"background-color:".$corLinha."\"><input type=\"hidden\" id=\"ID".$linha_sms['id']."\" value=\"".$linha_sms['id']."\" ><td>".date('d/m/Y',strtotime($linha_sms['inicio_vigencia']))."</td><td>" . ($linha_sms['fim_vigencia'] <> 0 ? date('d/m/Y',strtotime($linha_sms['fim_vigencia'])) : " ") . "</td><td>" . $linha_sms['valor'] . "</td><td style=\"background-color: #f5f6f7\"><div class=\"editar\"><input type=\"button\" name=\"btEditar\" class=\"btEditar\" value=\"Alterar\"></div><div class=\"salvar\" style=\"display: none;\"><input type=\"button\" name=\"btSalvar\" class=\"btSalvar\" value=\"Salvar\"> <input type=\"button\" name=\"btCancelar\" class=\"btCancelar\" value=\"Cancelar\"></div></td>
	</tr>";*/
	echo "<tr>\n";
	echo "\t<td>\n\t\t<input type=\"hidden\" name=\"id\" value=\"".($linha_teto['id'])."\">\n";
	echo "\t\t<input type=\"text\" name=\"inicio\" value=\"".date('d/m/Y',strtotime($linha_teto['inicio_vigencia']))."\">\n\t</td>\n";
	echo "\t<td>\n\t\t<input type=\"text\" name=\"fim\" value=\"".($linha_teto['fim_vigencia'] <> 0 ? date('d/m/Y',strtotime($linha_teto['fim_vigencia'])) : "")."\">\n\t</td>\n";
	echo "\t<td>\n\t\t<input type=\"text\" name=\"valor\" value=\"".($linha_teto['valor'])."\">\n\t</td>\n";
	echo "\t<td>\n\t\t<a href=\"#\" class=\"editarTeto\">Editar</a>\n\t</td>\n";
	echo "</tr>\n";
	if($linha_teto['fim_vigencia'] == 0){
		$mostra_linha_novo = false;
	}
}

if($mostra_linha_novo == true){
	echo "<tr>\n";
	echo "\t<td>\n\t\t<input type=\"text\" name=\"inicio\" value=\"\">\n\t</td>\n";
	echo "\t<td>\n\t\t<input type=\"text\" name=\"fim\" value=\"\">\n\t</td>\n";
	echo "\t<td>\n\t\t<input type=\"text\" name=\"valor\" value=\"\">\n\t</td>\n";
	echo "\t<td>\n\t\t<a href=\"#\" class=\"inserirTeto\">Inserir</a>\n\t</td>\n";
	echo "</tr>\n";
}


?>

</table>
<br />
<div style="clear:both"> </div>



</div>

<script>
$(document).ready(function(e) {
	
	var valor_coluna_1, valor_coluna_2, valor_coluna_3, indice_elemento;
	var dtInicioSalario, dtFimSalario, ValorSalario, idSalario, acaoSalario,  dtInicioTeto, dtFimTeto, ValorTeto, idTeto, acaoTeto;
	
	$('.editarSalario, .inserirSalario').click(function(e){
		dtInicioSalario = ($(this).parent().parent().find('input[name="inicio"]').val());
		dtFimSalario = ($(this).parent().parent().find('input[name="fim"]').val());
		ValorSalario = ($(this).parent().parent().find('input[name="valor"]').val());
		
		if($(this).html() == "Editar"){
			idSalario = ($(this).parent().parent().find('input[name="id"]').val());
			acaoSalario = 'alterarSalario';
		}else{
			idSalario = 0;
			acaoSalario = 'inserirSalario';
		}

		$.enviaAcao(acaoSalario,idSalario,dtInicioSalario,dtFimSalario,ValorSalario);
	});
	
	$('.editarMensalidade').click(function(e){
		ValorMensalidade = ($(this).parent().find('input[name="valor_mensalidade"]').val());

		if($(this).html() == "Editar"){
			id = 0;
			acao = 'alterarMensalidade';
		}

		$.enviaAcao(acao,id,0,0,ValorMensalidade);
	});

	$('.editarPlano').click(function(e)
	{
		var configuracao = $(this).attr("data-configuracao");
		var valor = $("#txt_plano_" + configuracao).val();
		location.href='indice.php?acao=editarPlano&configuracao=' + configuracao + '&valor=' + valor;
	});

	$('.editarTeto, .inserirTeto').click(function(e){
		dtInicioTeto = ($(this).parent().parent().find('input[name="inicio"]').val());
		dtFimTeto = ($(this).parent().parent().find('input[name="fim"]').val());
		ValorTeto = ($(this).parent().parent().find('input[name="valor"]').val());
		
		if($(this).html() == "Editar"){
			idTeto = ($(this).parent().parent().find('input[name="id"]').val());
			acaoTeto = 'alterarTeto';
		}else{
			idTeto = 0;
			acaoTeto = 'inserirTeto';
		}

		$.enviaAcao(acaoTeto,idTeto,dtInicioTeto,dtFimTeto,ValorTeto);
	});

	
	$(function(e){
		$.enviaAcao = function(acao, id, inicio, fim, valor){
			location.href='indice.php?acao=' + acao + '&id=' + id + '&inicio=' + inicio + '&fim=' + fim + '&valor=' + valor;
		}
	});
	
	// botao para editar os campos da tabela
  $('.btEditar').click(function(e){
		
		for(l=0; l < $('.guiaTabela').length; l++){
			if(indice_elemento == l){
				$('.editar').eq(l).css('display','block');
				$('.salvar').eq(l).css('display','none');
				objLinha = $('.guiaTabela').eq(l);
				coluna_1 = objLinha.children('td').eq(0);
				coluna_1.html(valor_coluna_1);
				coluna_2 = objLinha.children('td').eq(1);
				coluna_2.html(valor_coluna_2);
				coluna_3 = objLinha.children('td').eq(2);
				coluna_3.html(valor_coluna_3);
			}
		}
		
		// determinando o indice do elemento clicado
		indice_elemento = $('.btEditar').index(this);
		// escondendo o botao editar na linha selecionada
		$('.editar').eq(indice_elemento).css('display','none');
		// mostrando o botao salvar e cancelar na linha selecionada
		$('.salvar').eq(indice_elemento).css('display','block');
		// objeto da linha selecionada
		objLinha = $('.guiaTabela').eq(indice_elemento);
		// objeto da primeira coluna da linha selecionada
		coluna_1 = objLinha.children('td').eq(0);
		// valor da primeira coluna da linha selecionada
		valor_coluna_1 = coluna_1.html();
		// alterando para o campo de formulário já preenchido com o valor da coluna
		coluna_1.html('<input type=\"text\" id=\"inicio\" value=\"' + valor_coluna_1 + '\">');

		// objeto da segunda coluna da linha selecionada
		coluna_2 = objLinha.children('td').eq(1);
		// valor da segunda coluna da linha selecionada
		valor_coluna_2 = coluna_2.html();
		// alterando para o campo de formulário já preenchido com o valor da coluna
		coluna_2.html('<input type=\"text\" id=\"fim\" value=\"' + valor_coluna_2 + '\">');

		// objeto da terceira coluna da linha selecionada
		coluna_3 = objLinha.children('td').eq(2);
		// valor da terceira coluna da linha selecionada
		valor_coluna_3 = coluna_3.html();
		// alterando para o campo de formulário já preenchido com o valor da coluna
		coluna_3.html('<input type=\"text\" id=\"valor\" value=\"' + valor_coluna_3 + '\">');
	});

  $('.btSalvar').click(function(e){
		id = ($('#ID'+(indice_elemento+1)).val());
		location.href = 'indice.php?area=salario_minimo&id='+id+'&inicio=' + $('#inicio').val() + '&fim=' + $('#fim').val() + '&valor=' + $('#valor').val();
	});


  $('.btCancelar').click(function(e){
		indice_elemento = $('.btCancelar').index(this);
		$('.editar').eq(indice_elemento).css('display','block');
		$('.salvar').eq(indice_elemento).css('display','none');
		objLinha = $('.guiaTabela').eq(indice_elemento);
		coluna_1 = objLinha.children('td').eq(0);
		coluna_1.html(valor_coluna_1);
		coluna_2 = objLinha.children('td').eq(1);
		coluna_2.html(valor_coluna_2);
		coluna_3 = objLinha.children('td').eq(2);
		coluna_3.html(valor_coluna_3);
	});

});
</script>


<?php include '../rodape.php' ?>
