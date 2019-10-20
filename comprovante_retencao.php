<?php include 'header_restrita.php' ?>
<script>

	$(document).ready(function(e) {
		
		// MONTA A COMBO COM OS NOMES  é passado o id do request para deixar o ultimo
		montaCombo('populaCombo','area=folha_pagto&tipo='+$('#categoria').val() + '&id=<?=$_REQUEST["nome"]?>','nome');
		
		// MONTA A COMBO COM OS NOMES  é passado o id do request para deixar o ultimo
//		$('#categoria').change(function(){
//			montaCombo('populaCombo','area=folha_pagto&tipo=' + $(this).val() + '&id=','nome');
//		});

		$('#categoria').change(function(){
			$('#nome').val(''); // PARA QUE O FILTRO FUNCIONE CORRETAMENTE É NECESSÁRIO ZERAR A COMBO DE NOMES
			$('#form_filtro').submit();
		});

		// FOI SOLICITADA MUDANÇA PARA EXECUTAR O FILTRO A CADA CHANGE
		$('#nome').change(function(){
			$('#form_filtro').submit();
		});
						
	});


		// MONTAR COMBO
		function montaCombo(codigo, parametros, idCampoDestino){
			$.ajax({
				url: codigo+'.php',
				data: parametros,
				type: 'POST',
				async: false ,
				cache: false,
				success: function(result){
					$('#'+idCampoDestino).html(result);//arrResult[1]);
				}
				
			});
		}


</script>
<div class="principal">
	<h1>RH</h1>
<h2>Informe de Rendimentos</h2>

<?
function selected( $value, $prev ){
   return $value==$prev ? ' selected="selected"' : ''; 
};

?>

<!-- LISTAGEM DE PAGAMENTOS -->

<script>
function alterarPeriodo() {
	ano = document.getElementById('ano').value;

	window.location='<?=$_SERVER['PHP_SELF']?>?ano='+ano+'&busca=<?=$_REQUEST["busca"]?>&coluna=<?=$_REQUEST["coluna"]?>';
}
</script>

Utilize esta página para imprimir o <strong>Comprovante Anual de Retenção de Imposto de Renda na Fonte</strong>, que você deve enviar para todos os sócios, funcionários, autônomos e empresas (não optantes pelo Simples) que lhe prestaram serviços no período. O envio do comprovante é obrigatório e o não cumprimento desta norma está sujeito a multa. O envio deve ser realizado até o último dia útil de fevereiro do ano subsequente àquele a que se referem os rendimentos.<br />
<br /> 
Selecione o tipo de prestador, o ano calendário (aquele em que o serviço foi prestado) e clique no ícone da impressora para imprimir o documento.
<br />
<br />

<div class="quadro_passos_sem_largura" style="padding:20px;font-family: Arial, Helvetica, sans-serif;">
<span class="destaque">Atenção:</span> para que você possa imprimir corretamente o Informe de Rendimentos é preciso que já tenha cadastrado todos os pagamentos efetuados a sócios, funcionários, autônomos ou empresas que lhe prestaram serviços no período. Para isso, vá na aba "Pagamentos".
</div>


<br />


<div class="tituloVermelho" style="margin-bottom:20px">Relatório de Retenções de Imposto de Renda na Fonte</div>

 <form method="post" id="form_filtro" action="<?=$_SERVER['PHP_SELF']?>">
<?php
//Valores pré-definidos para a busca.

if($_REQUEST["ano"] != ""){
	$ano = $_REQUEST["ano"];
}
	
if ($ano == "") {
	$ano = date('Y')-1;
}

?>
   
   <select name="categoria" id="categoria">
		<option value="sócio" <?=$_REQUEST['categoria']=="sócio" || $_REQUEST['categoria']=="" ? "selected" : ""?>>sócios</option>
		<option value="autônomo" <?=$_REQUEST['categoria']=="autônomo" ? "selected" : ""?>>autônomos</option>
		<option value="pessoa jurídica" <?=$_REQUEST['categoria']=="pessoa jurídica" ? "selected" : ""?>>pessoa jurídica</option>
   </select>

   <select name="nome" id="nome">
<?
		// ESTA COMBO É MONTADA COM O EVENTO CHANGE DA COMBO categoria
		$sql = "
			select 
				idSocio as id
				, nome 
			from 
				dados_do_responsavel 
			WHERE 
				id='" . $_SESSION["id_empresaSecao"] . "'
		";

		$query = mysql_query($sql);

		if(mysql_num_rows($query) > 0){
			$id_primeiro_filtro = '';
			while($dados = mysql_fetch_array($query)){
				echo "<OPTION value=\"".$dados['id']."\"";
				if($id_primeiro_filtro == ''){
					$id_primeiro_filtro = $dados['id'];
				}
				if($dados['id'] == $_POST['id']){
					echo " selected";
				}
				echo ">".$dados['nome']."</OPTION>";
			}
		}else{
			echo "<OPTION value=\"\">nenhum dado localizado</OPTION>";
		}


?>
   </select>

	Ano Calendário: 
    	<select name="ano" id="ano">
<? for($i = date('Y'); $i >= 2010; $i--){ ?>
        	<option value="<?=$i?>" <?=($ano == $i ? 'selected' : '')?>><?=$i?></option>
<? } ?>
        </select>

   <input type="submit" value="Pesquisar" />
   
 </form>
<br />
<?
	// MONTAGEM DA LISTAGEM DOS PAGAMENTOS
//				case when pgto.id_lucro <> 0 then SUBSTR(pgto.data_periodo_ini,1,4) else YEAR(pgto.data_pagto) end ano

	$sql = "SELECT 
				YEAR(pgto.data_pagto) ano
				, case 
					  when pgto.id_autonomo <> 0 then 'autônomo' 
					  when (pgto.id_socio <> 0 OR pgto.id_lucro <> 0) then 'sócio' 
					  when pgto.id_pj <> 0 then 'pessoa jurídica' 
				  end tipo
				, case 
					  when pgto.id_autonomo <> 0 then aut.id
						when pgto.id_socio <> 0 then socio.idSocio
						when pgto.id_lucro <> 0 then dl.idSocio
					  when pgto.id_pj <> 0 then pj.id 
				  end id
				, case 
					  when pgto.id_autonomo <> 0 then aut.nome
					  when pgto.id_socio <> 0 then socio.nome
						when pgto.id_lucro <> 0 then dl.nome
					  when pgto.id_pj <> 0 then pj.nome
				  end nome
				, case 
					  when pgto.id_autonomo <> 0 then aut.cpf 
					  when pgto.id_socio <> 0 then socio.cpf 
						when pgto.id_lucro <> 0 then dl.cpf
					  when pgto.id_pj <> 0 then pj.cnpj
				  end cpf
				, sum(case when pgto.id_lucro <> 0 then pgto.valor_liquido else 0 end) valor_n_tributavel
				, sum(pgto.valor_bruto) valor_bruto
				, sum(pgto.IR) IR
			FROM 
				dados_pagamentos pgto
				left join dados_autonomos aut on pgto.id_autonomo = aut.id
				left join dados_do_responsavel socio on pgto.id_socio = socio.idSocio
				left join dados_pj pj on pgto.id_pj = pj.id
				left join dados_do_responsavel dl on pgto.id_lucro = dl.idSocio
			WHERE 
				pgto.id_estagiario = 0
				AND pgto.id_login='" . $_SESSION["id_empresaSecao"] . "'";
				
	$resDatas = "";
	if($ano != ''){
		$resDatas .= " AND YEAR(pgto.data_pagto) = '" . $ano . "'"; 
	}

	$resColuna .= " GROUP BY 1,2,3,4,5 
									HAVING 1=1 ";

	if ($_REQUEST["nome"] != "" || $_REQUEST["categoria"] != ""){

		if ($_REQUEST["nome"] != ""){
			$vNome = explode('|',$_REQUEST["nome"]);
			$resColuna .= " AND id = ". $vNome[0] . "";
		}
	
		if ($_REQUEST["categoria"] != ""){
			$resColuna .= " AND tipo = '". $_REQUEST["categoria"] . "'";
		}
	}else{
		if($id_primeiro_filtro != ''){
			$resColuna .= " AND id = ". $id_primeiro_filtro . " AND tipo = 'sócio'";
		}
	}

	$resOrdem = " ORDER BY data_pagto DESC";

//echo $sql . $resDatas . $resColuna . $resOrdem . "<BR><BR>";
//exit;	

	$resultado = mysql_query($sql . $resDatas . $resColuna . $resOrdem)
	or die (mysql_error());
?>
    <table width="100%" cellpadding="5">
        <tr>
            <th width="7%">Ação</th>
            <th width="30%">Nome</th>
<!--            <th width="10%">Categoria</th>-->
            <th width="9%">Rend. Tributáveis</th>
<?
	if($_REQUEST["categoria"] == 'sócio' || $_REQUEST["categoria"] == ''){
?>
						<th width="10%">Rend. NÃO Tributáveis</th>
<?
	}
?>
            <th width="9%">Valor Retenção</th>
        </tr>
<?
	if(mysql_num_rows($resultado) > 0){
?>


<?
		// TRAZENDO OS DADOS PARA MONTAR TABELA DE AUTONOMOS
		while($linha=mysql_fetch_array($resultado)){
			$id 	= $linha["id"];
			$nome 	= $linha["nome"];
			$tipo 	= $linha["tipo"];
			
			$valor_bruto 	= $linha["valor_bruto"];
			$valor_n_tributavel 	= $linha["valor_n_tributavel"];
			$total_valor_bruto += $valor_bruto;
			$total_valor_n_tributavel += $valor_n_tributavel;
						
			$retencao 	= $linha["IR"];
			$total_retencao += $retencao;
?>
            <tr>
                <td class="td_calendario" align="center">
                	<a href="
					<? switch($tipo){
						case 'sócio':
							echo 'form_comp_ret_pf.php';
						break;
						case 'autônomo':
							echo 'form_comp_ret_pf.php';
						break;
						case 'pessoa jurídica':
							echo 'form_comp_ret_pj.php';
						break;
					}
					?>?id=<?=$id?>&ano=<?=$ano?>&tp=<?=$tipo?>"><i class="fa fa-cloud-download" aria-hidden="true" style="font-size: 20px;line-height: 20px;"></i></a>
                </td>
                <td class="td_calendario"><?=$nome?></td>
<!--                <td class="td_calendario"><?=$tipo?></td>-->
                <td class="td_calendario" align="right"><?=number_format($valor_bruto,2,',','.')?></td>
<?
	if($_REQUEST["categoria"] == 'sócio' || $_REQUEST["categoria"] == ''){
?>
                <td class="td_calendario" align="right"><?=number_format($valor_n_tributavel,2,',','.')?></td>
<?
	}
?>
                <td class="td_calendario" align="right"><?=number_format($retencao,2,',','.')?></td>
            </tr>
<?	
		}
?>
			<tr>
            	<th style="background-color: #999; font-weight: normal" colspan="2" align="right">Totais:&nbsp;</th>
            	<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_valor_bruto,2,',','.')?></th>
<?
	if($_REQUEST["categoria"] == 'sócio' || $_REQUEST["categoria"] == ''){
?>
              <th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_valor_n_tributavel,2,',','.')?></th>
<?
	}
?>
            	<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_retencao,2,',','.')?></th>
            </tr>
<?
	}else{
?>
        <tr>
            <td class="td_calendario">&nbsp;</td>
            <td class="td_calendario">&nbsp;</td>
<?
	if($_REQUEST["categoria"] == 'sócio' || $_REQUEST["categoria"] == ''){
?>
						<td class="td_calendario">&nbsp;</td>
<?
	}
?>
            <td class="td_calendario">&nbsp;</td>
            <td class="td_calendario">&nbsp;</td>
        </tr>

<?	
	}
?>
		</table>

</div>

<?php include 'rodape.php' ?>
