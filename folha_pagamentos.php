<?php include 'header_restrita.php';

//echo $_SESSION["categoria"];
if(isset($_REQUEST["categoria"]) && $_REQUEST['categoria'] != 'Folha SEFIP'){
	$categoria = $_REQUEST["categoria"];
}else{
	$categoria = $_SESSION["categoria"];

	$_SESSION["categoria"] = "";
}

if($_SESSION['area_folha_pagto'] == 'pj'){
	$categoria = "pessoa jurídica";
	$_SESSION['area_folha_pagto'] = "";
}

$categoria = ($categoria != "" ? strtolower($categoria) : '');

$tipoFiltro = "mes";

//echo $categoria;

function get_nome_mes($numero_mes){
	$arrMonth = array(
		1 => 'janeiro',
		2 => 'fevereiro',
		3 => 'março',
		4 => 'abril',
		5 => 'maio',
		6 => 'junho',
		7 => 'julho',
		8 => 'agosto',
		9 => 'setembro',
		10 => 'outubro',
		11 => 'novembro',
		12 => 'dezembro'
		);
	return $arrMonth[(int)$numero_mes];
}

function get_ultimo_dia_mes($mes, $ano){
	switch($mes){
		case 1:
		case 3:
		case 5:
		case 7:
		case 8:
		case 10:
		case 12:
			return '31';
		break;
		case 4:
		case 6:
		case 9:
		case 11:
			return '30';
		break;
		case 2:
			if($ano % 4 == 0){
				return '29';
			}else{
				return '28';
			}
		break;
	}
}


	$sql = "SELECT 
				MIN(YEAR(pgto.data_pagto)) ano
			FROM 
				dados_pagamentos pgto
			WHERE 
				pgto.id_login='" . $_SESSION["id_empresaSecao"] . "'
			LIMIT 0,1";
	$rsAnoInicial = mysql_fetch_array(mysql_query($sql));
	$anoInicioPagamentos = $rsAnoInicial['ano'];
?>


<script>

	$(document).ready(function(e) {
		
		$('#link_outro_periodo').bind('click',function(e){
			e.preventDefault();
			if($(this).html() == 'definir período maior'){
				$(this).html('definir período por mês');
				$('#hddTipoFiltro').val('periodo');
				$('#form_mes_ano').css('display','none');
				$('#form_outro_periodo').css('display','inline');
				$('#form_mes_ano').find('select').val('');
			}else{
				$(this).html('definir período maior');
				$('#form_mes_ano').css('display','inline');
				$('#form_mes_ano').find('select').val('');
				$('#form_outro_periodo').css('display','none');
				$('#hddTipoFiltro').val('mes');
				$('#dataInicio').val('');
				$('#dataFim').val('');
			}
		});
		
		// MONTA A COMBO COM OS NOMES  é passado o id do request para deixar o ultimo
		<?
		if(isset($_REQUEST["nome"])){
			$arrDadosNome = explode("|",$_REQUEST["nome"]);
			$idNome = $arrDadosNome[0];
			$filtro_categoria = $arrDadosNome[1];
		}
		?>
	
		// MONTA A COMBO COM OS NOMES  é passado o id do request para deixar o ultimo
		// FOI SOLICITADA MUDANÇA PARA EXECUTAR O FILTRO A CADA CHANGE
		$('#categoria').change(function(){
			$('#nome').val(''); // PARA QUE O FILTRO FUNCIONE CORRETAMENTE É NECESSÁRIO ZERAR A COMBO DE NOMES
			$('#form_filtro').attr('action','<?=$_SERVER['PHP_SELF']?>');
			$('#form_filtro').submit();
		});

		if($('#categoria').val() == "" || $('#categoria').val() == "sefip"){
			$('#nome').css('display','none');
		}else{
			$('#nome').css('display','inline');
			montaCombo('populaCombo','area=folha_pagto&tipo='+$('#categoria').val() + '&id=<?=$_REQUEST["nome"]?>','nome');
		}

		// FOI SOLICITADA MUDANÇA PARA EXECUTAR O FILTRO A CADA CHANGE
		$('#nome').change(function(){
			$('#form_filtro').submit();
		});
		
		$('#cad_pagamento').bind('click',function(e){
			location.href=$('#opt_pagamento').val() + ".php";
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
//					$('#resultado').html(result);
					$('#'+idCampoDestino).html(result);//arrResult[1]);
				}
				
			});
		}


</script>
<div class="principal minHeight">

<h1>Folha de Pagamentos</h1>

<?
function selected( $value, $prev ){
   return $value==$prev ? ' selected="selected"' : ''; 
};

?>

<!-- LISTAGEM DE PAGAMENTOS -->

<script>
function ultimoDiaMes(mes,ano){
	switch(mes){
		case 1:
		case 3:
		case 5:
		case 7:
		case 8:
		case 10:
		case 12:
			return '31';
		break;
		case 4:
		case 6:
		case 9:
		case 11:
			return '30';
		break;
		case 2:
			if(ano % 4 == 0){
				return '29';
			}else{
				return '28';
			}
		break;
	}
}

function alterarPeriodo() {
	
	if(document.getElementById('dataInicio').value != '' && document.getElementById('dataFim').value != ''){
		
		dataInicio = document.getElementById('dataInicio').value;
		anoInicio = dataInicio.substr(6,4);
		mesInicio = dataInicio.substr(3,2);
		diaInicio = dataInicio.substr(0,2);
		dataFim = document.getElementById('dataFim').value;
		anoFim = dataFim.substr(6,4);
		mesFim = dataFim.substr(3,2);
		diaFim = dataFim.substr(0,2);

	}else{
		if(document.getElementById('periodoMes').value != '' && document.getElementById('periodoAno').value != ''){
			anoInicio = anoFim = document.getElementById('periodoAno').value;
			mesInicio = mesFim = document.getElementById('periodoMes').value;
			diaInicio = '01';
			diaFim = ultimoDiaMes(mesInicio,anoInicio);
			alert(diaFim);
		}
	}
	
	
	window.location='<?=$_SERVER['PHP_SELF']?>?dataInicio='+anoInicio+'-'+mesInicio+'-'+diaInicio+'&dataFim='+anoFim+'-'+mesFim+'-'+diaFim+'&busca=<?=$_REQUEST["busca"]?>&coluna=<?=$_REQUEST["coluna"]?>';
}
</script>


<div style="margin-bottom: 20px;">
Esta página deve ser usada para verificar pagamentos de pró-labores, distribuição de lucros,  salários, trabalhadores autônomos, estagiários e também a pessoas jurídicas prestadoras de serviços. É muito importante que você registre  todos estes pagamentos no Contador Amigo (para fazê-lo use a aba <strong>Pagamentos</strong> no menu superior), pois assim o sistema poderá informá-lo dos recolhimentos devidos à Previdência e eventuais retenções de Imposto de Renda. Além disso, os dados registrados nesta página serão usados para a geração da DIRF e da DEFIS, declarações que devem ser entregue todo começo de ano. </div>

<!--
<div class="tituloVermelho" style="margin-bottom: 10px;">Cadastrar novo pagamento</div>
<div style="clear:both;"></div>

<div style="float:left;">
    <div style="clear:both;margin-bottom: 25px;">
    <select name="opt_pagamento" id="opt_pagamento">
        <option value="pro_labore">Pró Labore</option>
        <option value="distribuicao_de_lucros">Distribuição de Lucros</option>
        <option value="pagamento_autonomos">Autônomos</option>
        <option value="pagamento_pj">Pessoa Jurídica</option>
        <option value="estagiarios">Estagiários</option>
    </select>
    <input type="button" value="Abrir" id="cad_pagamento" />
    </div>
</div>
   
<div style="float:right;">
<a href="sefip_folha.php">Exportar folha de pagamentos para SEFIP</a>
</div>
<div style="clear:both;"></div>
-->
<h2>Pagamentos Efetuados</h2>
<div id="resultado"></div>

<div style="float:left;">
<? 
//echo ("'");
//var_dump($_REQUEST);
//echo ("'");
?>
     <form id="form_filtro" method="post" action="<?=$_SERVER['PHP_SELF']?>">
    <?php
    //Valores pré-definidos para a busca.
	if($_POST || $_GET){
		
		$tipoFiltro = $_REQUEST['hddTipoFiltro'];

		if($_REQUEST["periodoMes"] != ""){ // selecionou mes/ano
			$dataInicio = date('Y-m-d',mktime(0,0,0,$_REQUEST["periodoMes"],'01',$_REQUEST["periodoAno"]));
			$dataFim = date('Y-m-d',mktime(0,0,0,$_REQUEST["periodoMes"],get_ultimo_dia_mes($_REQUEST["periodoMes"],$_REQUEST["periodoAno"]),$_REQUEST["periodoAno"]));
			$comparaMes = $_REQUEST["periodoMes"];
			$comparaAno = $_REQUEST["periodoAno"];
		}else{
			if($_REQUEST["dataInicio"] != "" && $_REQUEST["dataFim"] != ""){ // selecionou periodo de data
				if($_REQUEST["dataFim"] != ""){
					$dataFim = date('Y-m-d',mktime(0,0,0,substr($_REQUEST["dataFim"],3,2),substr($_REQUEST["dataFim"],0,2),substr($_REQUEST["dataFim"],6,4)));
				}
				if($_REQUEST["dataInicio"] != ""){
					$dataInicio = date('Y-m-d',mktime(0,0,0,substr($_REQUEST["dataInicio"],3,2),substr($_REQUEST["dataInicio"],0,2),substr($_REQUEST["dataInicio"],6,4)));
			//		$comparaMes = substr($_REQUEST["dataInicio"],3,2);
			//		$comparaAno = substr($_REQUEST["dataInicio"],6,4);
				}
			}else{ // mostrar todos os meses
				$dataInicio = date('Y-m-d',mktime(0,0,0,'01','01',$_REQUEST["periodoAno"]));
				$dataFim = date('Y-m-d',mktime(0,0,0,'12','31',$_REQUEST["periodoAno"]));
				$comparaMes = $_REQUEST["periodoMes"];
				$comparaAno = $_REQUEST["periodoAno"];
			}
		}
	}
	
	if ($dataInicio == "") {
	//	$dataInicio = date('Y-m-d',mktime(0,0,0,date('m'),'01',date('Y')));
			$dataInicio = date('Y-m-d',mktime(0,0,0,(date('m') == 1 ? '12' : date('m')-1) ,'01',(date('m') == 1 ? date('Y')-1 : date('Y'))));
			$comparaMes = (date('m') == 1 ? '12' : date('m')-1) ;
			$comparaAno = (date('m') == 1 ? date('Y')-1 : date('Y')) ;
	}
    

        
	if ($dataFim == "") {
			$dataFim = date('Y-m-d',strtotime("-1 days",strtotime('01-'.(date('m')).'-'.date('Y'))));
	}
//    echo $dataInicio;
//    echo $dataFim;
    /* 
    COM A MUDANÇA DA MANEIRA DE EXECUTAR O FILTRO (change das combos) O CÓDIGO É MONTADO USANDO O PARÂMETRO categoria PASSADO VIA POST E, AO CARREGAR O JQUERY, PEGA-SE O VALOR DESTA COMBO PARA MONTAR A COMBO DE nomes CORRESPONDENTE
    */
    ?>
       <div style="display:inline;float:left;margin-right:5px;">

       <select name="categoria" id="categoria">
            <option value="" <?=$categoria=="" ? "selected" : ""?>>Todos</option>
            <option value="sefip" <?=$categoria=="sefip" ? "selected" : ""?>>Folha Sefip</option>
            <option value="pró-labore" <?=$categoria=="pró-labore" ? "selected" : ""?>>Pró-labore</option>
            <option value="distr. de lucros" <?=$categoria=="distr. de lucros" ? "selected" : ""?>>Distr. de lucros</option>
            <option value="Autônomos" <?=$categoria=="autônomos" ? "selected" : ""?>>Autônomos</option>
            <option value="Estagiários" <?=$categoria=="estagiários" ? "selected" : ""?>>Estagiários</option>
            <option value="pessoa jurídica" <?=$categoria=="pessoa jurídica" ? "selected" : ""?>>Pessoa jurídica</option>
       </select>
    
       <select name="nome" id="nome">
    <?
            // ESTA COMBO É MONTADA COM O EVENTO CHANGE DA COMBO categoria
/*            $sql = "
                select 
                    idSocio as id
                    , nome 
                    , 'pró-labore' as categoria
                from 
                    dados_do_responsavel 
                WHERE 
                    id='" . $_SESSION["id_empresaSecao"] . "'
            ";
    
            $query = mysql_query($sql);
    
            if(mysql_num_rows($query) > 0){
                $id_primeiro_filtro = '';
                while($dados = mysql_fetch_array($query)){
                    echo "<OPTION value=\"".$dados['id']."|".$dados['categoria']."\"";
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
            }*/
    
    
    ?>
        </select>
	    </div>
      
      <div id="form_mes_ano" style="display:<?=$tipoFiltro == "mes" ? 'inline' : 'none' ?>;float:left;margin-right:5px;">
        No mês de 
        <select name="periodoMes" id="periodoMes">
        <option value="">Todos</option>
        <? for($i = 1; $i <= 12; $i++) {?>
        <option value="<?=$i?>"<?=(($comparaMes == $i) ? " selected" : "")?>><?=ucfirst(get_nome_mes($i))?></option>
        <? } ?>
        </select>
        de 
       <select name="periodoAno" id="periodoAno">
        <option value=""></option>
        <? for($i = $anoInicioPagamentos; $i <= date('Y'); $i++) {?>
        <option value="<?=$i?>"<?=($comparaAno == $i ? " selected" : "")?>><?=$i?></option>
        <? } ?>
       </select>
        </div>
    	<div id="form_outro_periodo" style="display:<?=$tipoFiltro == "mes" ? 'none' : 'inline' ?>;float:left;margin-right:5px;">
        Período de: <input name="dataInicio" id="dataInicio" type="text" value="<?=$_REQUEST['dataInicio'] != "" ? date('d/m/Y',strtotime($dataInicio)) : ""?>" maxlength="10"  style="width:80px" class="campoData" /> 
        até: <input name="dataFim" id="dataFim" type="text" value="<?=$_REQUEST['dataFim'] != "" ? date('d/m/Y',strtotime($dataFim)) : ""?>" maxlength="10"  style="width:80px" class="campoData" /> 
    	</div>
<!--        Período de: <input name="dataInicio" id="dataInicio" type="text" value="<?=date('d/m/Y',strtotime($dataInicio))?>" maxlength="10"  style="width:80px" class="campoData" /> 
        até: <input name="dataFim" id="dataFim" type="text" value="<?=date('d/m/Y',strtotime($dataFim))?>" maxlength="10"  style="width:80px" class="campoData" /> -->
    	<div style="display:inline;float:left;margin-right:5px;">
		      <input type="hidden" name="hddTipoFiltro" id="hddTipoFiltro" value="<?=$tipoFiltro?>" />
	        <input type="submit" value="Pesquisar" />
        </div>
    	<div style="display:inline;float:left;padding-top:5px;margin-right:5px;">
			ou <a href="#" id="link_outro_periodo"><?=(($_REQUEST['dataInicio'] != "") || ($_REQUEST['dataFim'] != "") ? 'definir período por mês' : 'definir período maior' )?></a>
    	</div>
       
     </form>
 </div>


<div style="clear:both;margin-bottom:20px;"></div>

<?
if($categoria == "" || $categoria == "distr. de lucros"){

	// DISTRIBUIÇÃO DE LUCROS
		// MONTAGEM DA LISTAGEM DOS PAGAMENTOS
		$sql = "SELECT 
					pgto.id_pagto
					, pgto.valor_liquido
					, pgto.data_pagto  
					, case 
						  when pgto.id_lucro <> 0 AND LENGTH(pgto.data_periodo_ini) = 4 then 'Anual' 
						  when pgto.id_lucro <> 0 AND LENGTH(pgto.data_periodo_ini) > 4 then 'Antecipação mensal' 
						  else '' 
					  end periodo
					, dl.idSocio id
					, dl.nome nome
				FROM 
					dados_pagamentos pgto
					INNER join dados_do_responsavel dl on pgto.id_lucro = dl.idSocio
				WHERE 
					pgto.id_login='" . $_SESSION["id_empresaSecao"] . "'";
					
		$resDatas = "";
		if($dataInicio != ''){
			$resDatas .= " AND pgto.data_pagto >= '" . $dataInicio . "'"; 
		}
		if($dataFim != ''){
			$resDatas .= " AND pgto.data_pagto <= '" . $dataFim . "'"; 
		}
	
		if ($_REQUEST["nome"] != ""){
			$resColuna = " HAVING 1=1 AND id = ". $idNome . "";
		}
		
		$resOrdem = " ORDER BY data_pagto DESC";
		
		//echo $sql . $resDatas . $resColuna . $resOrdem;
		
		$resultado = mysql_query($sql . $resDatas . $resColuna . $resOrdem)
		or die (mysql_error());
		
		if(($categoria != '' && $categoria != 'sefip') || mysql_num_rows($resultado) > 0){

	?>
            <div class="tituloAzulPequeno" style="float:left;">
                <?
                    echo 'Distribuição de Lucros';
                ?>
            </div>
            <div style="float:right;">
                <a href="distribuicao_de_lucros.php">Cadastrar novo pagamento</a>
            </div>	
            <table width="100%" cellpadding="5" style="margin-bottom:25px;">
                <tr>
                    <th width="7%">Ação</th>
                    <th width="45%">Nome</th>
                    <th width="15%">Tipo</th>
                    <th width="9%">Data</th>
                    <th width="14%">Valor Distribuído</th>
                </tr>
	
	<?	
	
			if(mysql_num_rows($resultado) > 0){
		
				// TRAZENDO OS DADOS PARA MONTAR TABELA DE AUTONOMOS
				while($linha=mysql_fetch_array($resultado)){
					$idPagto 	= $linha["id_pagto"];
					$id 	= $linha["id"];
					$nome 	= $linha["nome"];
					$periodo 	= $linha["periodo"];
					$valor_liquido 	= $linha["valor_liquido"];
					$data_pagto 	= (date("d/m/Y",strtotime($linha['data_pagto'])));
	?>
                    <tr>
                        <td class="td_calendario" align="center">
                            <a href="#" onClick="if (confirm('Você tem certeza que deseja excluir este Pagamento?'))location.href='folha_pagamentos_excluir.php?excluir=<?=$idPagto?>&categoria=<?=$categoria?>&dataInicio=<?=date('d/m/Y',strtotime($dataInicio))?>&dataFim=<?=date('d/m/Y',strtotime($dataFim))?>&periodoMes=<?=$comparaMes?>&periodoAno=<?=$comparaAno?>&hddTipoFiltro=<?=$tipoFiltro?>';"><img src="images/del.png" width="24" height="23" border="0" title="Excluir" /></a>
                            <a href="Recibo_distribuicao_download.php?id_pagto=<?=$idPagto?>"><img src="images/printer3.png" width="24" border="0" title="Imprimir" /></a>
                        </td>
                        <td class="td_calendario"><a href="meus_dados_socio.php?editar=<?=$id?>"><?=$nome?></a></td>
                        <td class="td_calendario"><?=$periodo?></td>
                        <td class="td_calendario" align="right"><?=$data_pagto?></td>
                        <td class="td_calendario" align="right"><?=number_format($valor_liquido,2,',','.')?></td>
                    </tr>
	<?
					$total_valor_liquido += ($op_simples == 0 ? $valor_liquido : $valor_bruto);	
		
					// FIM DO LOOP
		
				}
	?>
                <tr>
                    <th style="background-color: #999; font-weight: normal" colspan="4" align="right">Totais:&nbsp;</th>
                    <th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_valor_liquido,2,',','.')?></th>
                </tr>
	
		<?
				$total_valor_liquido = 0;
		
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
        ?>
	
			</table>
	<?		
		}
	// FIM DISTRIBUIÇÃO DE LUCROS
}




if($categoria == "" || $categoria == "sefip" || $categoria == "pró-labore"){ // INCLUIDA EM 17/03/2015 A OPCAO SEFIP QUE DEVERÁ MOSTRAR OS AUTONOMOS E PRO-LABORES
	//PRO-LABORE
		// MONTAGEM DA LISTAGEM DOS PAGAMENTOS
		$sql = "SELECT 
					pgto.id_pagto
					, pgto.valor_bruto
					, pgto.INSS
					, pgto.IR
					, pgto.ISS
					, pgto.valor_liquido
					, pgto.data_pagto  
					, pgto.desconto_dependentes
					, socio.dependentes dependentes
					, socio.idSocio id
					, socio.nome nome
					, socio.cpf cpf
				FROM 
					dados_pagamentos pgto
					inner join dados_do_responsavel socio on pgto.id_socio = socio.idSocio
				WHERE 
					pgto.id_login='" . $_SESSION["id_empresaSecao"] . "'";
					
		$resDatas = "";
		if($dataInicio != ''){
			$resDatas .= " AND pgto.data_pagto >= '" . $dataInicio . "'"; 
		}
		if($dataFim != ''){
			$resDatas .= " AND pgto.data_pagto <= '" . $dataFim . "'"; 
		}
		if ($_REQUEST["nome"] != ""){
			$resColuna = " HAVING 1=1 AND id = ". $idNome . "";
		}
	
		$resOrdem = " ORDER BY data_pagto DESC";
		
	//	echo $sql . $resDatas . $resColuna . $resOrdem;
		
	
		$resultado = mysql_query($sql . $resDatas . $resColuna . $resOrdem)
		or die (mysql_error());
	
	
		if(($categoria != '' && $categoria != 'sefip') || mysql_num_rows($resultado) > 0){
			
	?>
            <div class="tituloAzulPequeno" style="float:left;">
                Pró Labore
            </div>
            <div style="float:right;">
                <a href="pro_labore.php">Cadastrar novo pagamento</a>
            </div>	
            <table width="100%" cellpadding="5" style="margin-bottom:25px;">
                <tr>
                    <th width="7%">Ação</th>
                    <th width="30%">Nome</th>
                    <th width="9%">Data</th>
                    <th width="9%">Valor Bruto</th>
                    <th width="9%">INSS</th>
                    <th width="10%">Desconto<br />Dependentes</th>
                    <th width="9%">IR</th>
                    <th width="8%">ISS</th>
                    <th width="9%">Valor Líquido</th>
                </tr>
        
	<?	
			if(mysql_num_rows($resultado) > 0){
		
				// TRAZENDO OS DADOS PARA MONTAR TABELA DE AUTONOMOS
				while($linha=mysql_fetch_array($resultado)){
					$idPagto 	= $linha["id_pagto"];
					$id 	= $linha["id"];
					$nome 	= $linha["nome"];
		
					$valor_bruto 	= $linha["valor_bruto"];
					$INSS		 	= $linha["INSS"];
					$dependentes	= $linha["dependentes"];
					$desc_dep 		= $linha["desconto_dependentes"];
					$IR			 	= $linha["IR"];
					$ISS		 	= $linha["ISS"];
					$valor_liquido 	= $linha["valor_liquido"];
					$data_pagto 	= (date("d/m/Y",strtotime($linha['data_pagto'])));
					
	?>
                    <tr>
                        <td class="td_calendario" align="center">
                            <a href="#" onClick="if (confirm('Você tem certeza que deseja excluir este Pagamento?'))location.href='folha_pagamentos_excluir.php?excluir=<?=$idPagto?>&categoria=<?=$categoria?>&dataInicio=<?=date('d/m/Y',strtotime($dataInicio))?>&dataFim=<?=date('d/m/Y',strtotime($dataFim))?>&periodoMes=<?=$comparaMes?>&periodoAno=<?=$comparaAno?>&hddTipoFiltro=<?=$tipoFiltro?>';"><img src="images/del.png" width="24" height="23" border="0" title="Excluir" /></a>
                            <a href="Recibo_download.php?id_pagto=<?=$idPagto?>"><img src="images/printer3.png" width="24" border="0" title="Imprimir" /></a>
                        </td>
                        <td class="td_calendario"><a href="meus_dados_socio.php?editar=<?=$id?>"><?=$nome?></a></td>
                        <td class="td_calendario" align="right"><?=$data_pagto?></td>
                        <td class="td_calendario" align="right"><?=number_format($valor_bruto,2,',','.')?></td>
                        <td class="td_calendario" align="right"><?=number_format($INSS,2,',','.')?></td>
                        <td class="td_calendario" align="right"><?=number_format($desc_dep,2,',','.') . " (" . $dependentes . ")"?></td>
                        <td class="td_calendario" align="right"><?=number_format($IR,2,',','.')?></td>
                        <td class="td_calendario" align="right"><?=number_format($ISS,2,',','.')?></td>
                        <td class="td_calendario" align="right"><?=number_format($valor_liquido,2,',','.')?></td>
                    </tr>
	<?
			
					$total_desc_dep += $desc_dep;
					$total_INSS += $INSS;
		
					$total_valor_bruto += $valor_bruto;
					$total_IR += $IR;
					$total_ISS += $ISS;
					$total_valor_liquido += $valor_liquido;	
		
					// FIM DO LOOP
				}
	
	?>
				<tr>
					<th style="background-color: #999; font-weight: normal" colspan="3" align="right">Totais:&nbsp;</th>
					<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_valor_bruto,2,',','.')?></th>
					<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_INSS,2,',','.')?></th>
					<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_desc_dep,2,',','.')?></th>
					<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_IR,2,',','.')?></th>
					<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_ISS,2,',','.')?></th>
					<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_valor_liquido,2,',','.')?></th>
				</tr>
		<?
				$total_INSS = 0;
				$total_desc_dep = 0;
				$total_valor_bruto = 0;
				$total_IR = 0;
				$total_ISS = 0;
				$total_valor_liquido = 0;	
	
	
			}else{
	?>
                <tr>
                    <td class="td_calendario">&nbsp;</td>
                    <td class="td_calendario">&nbsp;</td>
                    <td class="td_calendario">&nbsp;</td>
                    <td class="td_calendario">&nbsp;</td>
                    <td class="td_calendario">&nbsp;</td>
                    <td class="td_calendario">&nbsp;</td>
                    <td class="td_calendario">&nbsp;</td>
                    <td class="td_calendario">&nbsp;</td>
                    <td class="td_calendario">&nbsp;</td>
                </tr>
	<?		
		}
	?>
	
		</table>
<?		
	}

	// FIM PRO-LABORE
}




if($categoria == "" || $categoria == "sefip" || $categoria == "autônomos"){ // INCLUIDA EM 17/03/2015 A OPCAO SEFIP QUE DEVERÁ MOSTRAR OS AUTONOMOS E PRO-LABORES
	// AUTONOMO
		// MONTAGEM DA LISTAGEM DOS PAGAMENTOS
		$sql = "SELECT 
					pgto.id_pagto
					, pgto.valor_bruto
					, pgto.INSS
					, pgto.IR
					, pgto.ISS
					, pgto.valor_liquido
					, pgto.data_pagto  
					, pgto.desconto_dependentes
					, aut.dependentes dependentes
					, aut.id id
					, aut.nome nome
					, aut.cpf cpf
				FROM 
					dados_pagamentos pgto
					inner join dados_autonomos aut on pgto.id_autonomo = aut.id
				WHERE 
					pgto.id_login='" . $_SESSION["id_empresaSecao"] . "'";
					
		$resDatas = "";
		if($dataInicio != ''){
			$resDatas .= " AND pgto.data_pagto >= '" . $dataInicio . "'"; 
		}
		if($dataFim != ''){
			$resDatas .= " AND pgto.data_pagto <= '" . $dataFim . "'"; 
		}
		if ($_REQUEST["nome"] != ""){
			$resColuna = " HAVING 1=1 AND id = ". $idNome . "";
		}
	
		$resOrdem = " ORDER BY data_pagto DESC";
		
	//	echo $sql . $resDatas . $resColuna . $resOrdem;
		
	
		$resultado = mysql_query($sql . $resDatas . $resColuna . $resOrdem)
		or die (mysql_error());
	
	
		if(($categoria != '' && $categoria != 'sefip') || mysql_num_rows($resultado) > 0){
	
	?>
            <div class="tituloAzulPequeno" style="float:left;">
                Autônomos
            </div>
            <div style="float:right;">
                <a href="pagamento_autonomos.php">Cadastrar novo pagamento</a>
            </div>	
            <table width="100%" cellpadding="5" style="margin-bottom:25px;">
                <tr>
                    <th width="7%">Ação</th>
                    <th width="30%">Nome</th>
                    <th width="9%">Data</th>
                    <th width="9%">Valor Bruto</th>
                    <th width="9%">INSS</th>
                    <th width="10%">Desconto<br />Dependentes</th>
                    <th width="9%">IR</th>
                    <th width="8%">ISS</th>
                    <th width="9%">Valor Líquido</th>
                </tr>
	
	<?	
			if(mysql_num_rows($resultado) > 0){
		
				// TRAZENDO OS DADOS PARA MONTAR TABELA DE AUTONOMOS
				while($linha=mysql_fetch_array($resultado)){
					$idPagto 	= $linha["id_pagto"];
					$id 	= $linha["id"];
					$nome 	= $linha["nome"];
		
					$valor_bruto 	= $linha["valor_bruto"];
					$INSS		 	= $linha["INSS"];
					$dependentes	= $linha["dependentes"];
					$desc_dep 		= $linha["desconto_dependentes"];
					$IR			 	= $linha["IR"];
					$ISS		 	= $linha["ISS"];
					$valor_liquido 	= $linha["valor_liquido"];
					$data_pagto 	= (date("d/m/Y",strtotime($linha['data_pagto'])));
				
	?>
                    <tr>
                        <td class="td_calendario" align="center">
                            <a href="#" onClick="if (confirm('Você tem certeza que deseja excluir este Pagamento?'))location.href='folha_pagamentos_excluir.php?excluir=<?=$idPagto?>&categoria=<?=$categoria?>&dataInicio=<?=date('d/m/Y',strtotime($dataInicio))?>&dataFim=<?=date('d/m/Y',strtotime($dataFim))?>&periodoMes=<?=$comparaMes?>&periodoAno=<?=$comparaAno?>&hddTipoFiltro=<?=$tipoFiltro?>';"><img src="images/del.png" width="24" height="23" border="0" title="Excluir" /></a>
                            <a href="RPA_download.php?id_pagto=<?=$idPagto?>"><img src="images/printer3.png" width="24" border="0" title="Imprimir" /></a>
                        </td>
                        <td class="td_calendario"><a href="meus_dados_autonomos.php?editar=<?=$id?>"><?=$nome?></a></td>
                        <td class="td_calendario" align="right"><?=$data_pagto?></td>
                        <td class="td_calendario" align="right"><?=number_format($valor_bruto,2,',','.')?></td>
                        <td class="td_calendario" align="right"><?=number_format($INSS,2,',','.')?></td>
                        <td class="td_calendario" align="right"><?=number_format($desc_dep,2,',','.') . " (" . $dependentes . ")"?></td>
                        <td class="td_calendario" align="right"><?=number_format($IR,2,',','.')?></td>
                        <td class="td_calendario" align="right"><?=number_format($ISS,2,',','.')?></td>
                        <td class="td_calendario" align="right"><?=number_format($valor_liquido,2,',','.')?></td>
                    </tr>
	<?
				
					$total_desc_dep += $desc_dep;
					$total_INSS += $INSS;
		
					$total_valor_bruto += $valor_bruto;
					$total_IR += $IR;
					$total_ISS += $ISS;
					$total_valor_liquido += $valor_liquido;	
		
					// FIM DO LOOP
				}
	
	?>
				<tr>
					<th style="background-color: #999; font-weight: normal" colspan="3" align="right">Totais:&nbsp;</th>
					<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_valor_bruto,2,',','.')?></th>
					<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_INSS,2,',','.')?></th>
					<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_desc_dep,2,',','.')?></th>
					<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_IR,2,',','.')?></th>
					<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_ISS,2,',','.')?></th>
					<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_valor_liquido,2,',','.')?></th>
				</tr>
		<?
	
				$total_INSS = 0;
				$total_desc_dep = 0;
				$total_valor_bruto = 0;
				$total_IR = 0;
				$total_ISS = 0;
				$total_valor_liquido = 0;	
	
			}else{
		?>
				<tr>
					<td class="td_calendario">&nbsp;</td>
					<td class="td_calendario">&nbsp;</td>
					<td class="td_calendario">&nbsp;</td>
					<td class="td_calendario">&nbsp;</td>
					<td class="td_calendario">&nbsp;</td>
					<td class="td_calendario">&nbsp;</td>
					<td class="td_calendario">&nbsp;</td>
					<td class="td_calendario">&nbsp;</td>
					<td class="td_calendario">&nbsp;</td>
				</tr>
		<?		
			}
	?>
	
		</table>
	<?		
		}
	// FIM AUTONOMO
}










if($categoria == "" || $categoria == "estagiários"){
	// ESTAGIARIO
		// MONTAGEM DA LISTAGEM DOS PAGAMENTOS
		$sql = "SELECT 
					pgto.id_pagto
					, pgto.valor_bruto
					, pgto.INSS
					, pgto.IR
					, pgto.ISS
					, pgto.valor_liquido
					, pgto.data_emissao
					, pgto.data_pagto  
					, pgto.codigo_servico
					, pgto.descricao_servico
					, est.id id
					, est.nome nome
					, est.cpf cpf
				FROM 
					dados_pagamentos pgto
					inner join estagiarios est on pgto.id_estagiario = est.id
				WHERE 
					pgto.id_login='" . $_SESSION["id_empresaSecao"] . "'";
					
		$resDatas = "";
		if($dataInicio != ''){
			$resDatas .= " AND pgto.data_pagto >= '" . $dataInicio . "'"; 
		}
		if($dataFim != ''){
			$resDatas .= " AND pgto.data_pagto <= '" . $dataFim . "'"; 
		}
		if ($_REQUEST["nome"] != ""){
			$resColuna = " HAVING 1=1 AND id = ". $idNome . "";
		}
	
		$resOrdem = " ORDER BY data_pagto DESC";
		
	//	echo $sql . $resDatas . $resColuna . $resOrdem;
		
	
		$resultado = mysql_query($sql . $resDatas . $resColuna . $resOrdem)
		or die (mysql_error());
	
	
		if(($categoria != '' && $categoria != 'sefip') || mysql_num_rows($resultado) > 0){
	
	?>
            <div class="tituloAzulPequeno" style="float:left;">
                Estagiários
            </div>
            <div style="float:right;">
                <a href="estagiarios.php">Cadastrar novo pagamento</a>
            </div>	
            <table width="100%" cellpadding="5" style="margin-bottom:25px;">
                <tr>
                    <th width="7%">Ação</th>
                    <th width="30%">Nome</th>
                    <th width="9%">Data</th>
                    <th width="9%">Valor Bruto</th>
                    <th width="9%">INSS</th>
                    <th width="9%">IR</th>
                    <th width="8%">ISS</th>
                    <th width="9%">Valor Líquido</th>
                </tr>
	<?	
			if(mysql_num_rows($resultado) > 0){
		
				// TRAZENDO OS DADOS PARA MONTAR TABELA DE AUTONOMOS
				while($linha=mysql_fetch_array($resultado)){
					$idPagto 	= $linha["id_pagto"];
					$id 	= $linha["id"];
					$nome 	= $linha["nome"];
		
					$valor_bruto 	= $linha["valor_bruto"];
					$INSS		 	= $linha["INSS"];
					$dependentes	= $linha["dependentes"];
					$desc_dep 		= $linha["desconto_dependentes"];
					$IR			 	= $linha["IR"];
					$ISS		 	= $linha["ISS"];
					$valor_liquido 	= $linha["valor_liquido"];
					$data_pagto 	= (date("d/m/Y",strtotime($linha['data_pagto'])));
				
	?>
                    <tr>
                        <td class="td_calendario" align="center">
                            <a href="#" onClick="if (confirm('Você tem certeza que deseja excluir este Pagamento?'))location.href='folha_pagamentos_excluir.php?excluir=<?=$idPagto?>&categoria=<?=$categoria?>&dataInicio=<?=date('d/m/Y',strtotime($dataInicio))?>&dataFim=<?=date('d/m/Y',strtotime($dataFim))?>&periodoMes=<?=$comparaMes?>&periodoAno=<?=$comparaAno?>&hddTipoFiltro=<?=$tipoFiltro?>';"><img src="images/del.png" width="24" height="23" border="0" title="Excluir" /></a>
                            <a href="Recibo_estagiarios_download.php?id_pagto=<?=$idPagto?>"><img src="images/printer3.png" width="24" border="0" title="Imprimir" /></a>
                        </td>
                        <td class="td_calendario"><a href="meus_dados_estagiarios.php?editar=<?=$id?>"><?=$nome?></a></td>
                        <td class="td_calendario" align="right"><?=$data_pagto?></td>
                        <td class="td_calendario" align="right"><?=number_format($valor_bruto,2,',','.')?></td>
                        <td class="td_calendario" align="right"><?=number_format($INSS,2,',','.')?></td>
                        <td class="td_calendario" align="right"><?=number_format($IR,2,',','.')?></td>
                        <td class="td_calendario" align="right"><?=number_format($ISS,2,',','.')?></td>
                        <td class="td_calendario" align="right"><?=number_format($valor_liquido,2,',','.')?></td>
                    </tr>
        <?
			
					$total_INSS += $INSS;
		
					$total_valor_bruto += $valor_bruto;
					$total_IR += $IR;
					$total_ISS += $ISS;
					$total_valor_liquido += $valor_liquido;	
		
					// FIM DO LOOP
				}
	
	?>
				<tr>
					<th style="background-color: #999; font-weight: normal" colspan="3" align="right">Totais:&nbsp;</th>
					<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_valor_bruto,2,',','.')?></th>
					<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_INSS,2,',','.')?></th>
					<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_IR,2,',','.')?></th>
					<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_ISS,2,',','.')?></th>
					<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_valor_liquido,2,',','.')?></th>
				</tr>
		<?
				$total_INSS = 0;
				$total_valor_bruto = 0;
				$total_IR = 0;
				$total_ISS = 0;
				$total_valor_liquido = 0;	
	
			}else{
		?>
				<tr>
					<td class="td_calendario">&nbsp;</td>
					<td class="td_calendario">&nbsp;</td>
					<td class="td_calendario">&nbsp;</td>
					<td class="td_calendario">&nbsp;</td>
					<td class="td_calendario">&nbsp;</td>
					<td class="td_calendario">&nbsp;</td>
					<td class="td_calendario">&nbsp;</td>
					<td class="td_calendario">&nbsp;</td>
				</tr>
		<?		
			}
	?>
	
		</table>
	<?		
		}
	// FIM ESTAGIARIO
}




if($categoria == "" || $categoria == "pessoa jurídica"){
	// PESSOA JURIDICA
		// MONTAGEM DA LISTAGEM DOS PAGAMENTOS

/*
					, pgto.codigo_servico
					, pgto.descricao_servico

*/
		$sql = "SELECT 
					pgto.id_pagto
					, pgto.valor_bruto
					, pgto.INSS
					, pgto.IR
					, pgto.ISS
					, pgto.valor_liquido
					, pgto.data_emissao
					, pgto.data_pagto  
					, pgto.desconto_dependentes
					, pj.id id
					, pj.nome nome
					, pj.cnpj cpf
					, pj.op_simples op_simples
				FROM 
					dados_pagamentos pgto
					inner join dados_pj pj on pgto.id_pj = pj.id
				WHERE 
					pgto.id_login='" . $_SESSION["id_empresaSecao"] . "'";
					
		$resDatas = "";
		if($dataInicio != ''){
			$resDatas .= " AND pgto.data_emissao >= '" . $dataInicio . "'"; 
		}
		if($dataFim != ''){
			$resDatas .= " AND pgto.data_emissao <= '" . $dataFim . "'"; 
		}
		if ($_REQUEST["nome"] != ""){
			$resColuna = " HAVING 1=1 AND id = ". $idNome . "";
		}
	
		$resOrdem = " ORDER BY data_pagto DESC";
		
		//echo $sql . $resDatas . $resColuna . $resOrdem;
		
	
		$resultado = mysql_query($sql . $resDatas . $resColuna . $resOrdem)
		or die (mysql_error());
	
	
		if(($categoria != '' && $categoria != 'sefip') || mysql_num_rows($resultado) > 0){

?>
    
            <div class="tituloAzulPequeno" style="float:left;">
                Pessoa Jurídica
            </div>	
            <div style="float:right;">
                <a href="pagamento_pj.php">Cadastrar novo pagamento</a>
            </div>	
            <table width="100%" cellpadding="5" style="margin-bottom:25px;">
                <tr>
                    <th width="4%">Ação</th>
                    <th width="47%">Nome</th>
                    <th width="9%">Data da NF</th>
                    <th width="9%">Valor Bruto</th>
                    <th width="8%">IR</th>
                    <th width="9%">ISS</th>
                    <th width="9%">Valor Líquido</th>
                </tr>
            
<?
		
			if(mysql_num_rows($resultado) > 0){
				$loop = 0;
				$codigo_servicoAnterior 	= "0";
				// TRAZENDO OS DADOS PARA MONTAR TABELA DE AUTONOMOS
				while($linha=mysql_fetch_array($resultado)){
					$idPagto 					= $linha["id_pagto"];
					$id 						= $linha["id"];
					$nome 						= $linha["nome"];
					$periodo 					= $linha["periodo"];
					$tipo 						= $linha["tipo"];
					$cpf 						= $linha["cpf"];
		
					$op_simples 				= $linha["op_simples"];
	//				$codigo_servico 			= $linha["codigo_servico"];
	//				$descricao_servico 			= $linha["descricao_servico"];
					
					$valor_bruto 				= $linha["valor_bruto"];
					$INSS		 				= $linha["INSS"];
					$IR			 				= $linha["IR"];
					$ISS		 				= $linha["ISS"];
					$valor_liquido 				= $linha["valor_liquido"];
					
					$data_pagto 				= (date("d/m/Y",strtotime($linha['data_pagto'])));
					$data_emissao 				= $linha['data_emissao'] != null ? date("d/m/Y",strtotime($linha['data_emissao'])) : "";
					

	?>
        
                    <tr>
                        <td class="td_calendario" align="center">
                            <a href="#" onClick="if (confirm('Você tem certeza que deseja excluir este Pagamento?'))location.href='folha_pagamentos_excluir.php?excluir=<?=$idPagto?>&categoria=<?=$categoria?>&dataInicio=<?=$_REQUEST['dataInicio']?>&dataFim=<?=$_REQUEST['dataFim']?>&periodoMes=<?=$comparaMes?>&periodoAno=<?=$comparaAno?>&hddTipoFiltro=<?=$tipoFiltro?>';"><img src="images/del.png" width="24" height="23" border="0" title="Excluir" /></a>
                        </td>
                        <td class="td_calendario"><a href="meus_dados_pj.php?editar=<?=$id?>"><?=$nome?></a></td>
                        <td class="td_calendario" align="right"><?=$data_emissao?></td>
                        <td class="td_calendario" align="right"><?=number_format($valor_bruto,2,',','.')?></td>
                        <td class="td_calendario" align="right"><?=number_format(($op_simples == 0 ? $IR : 0),2,',','.')?></td>
                        <td class="td_calendario" align="right"><?=number_format(($op_simples == 0 ? $ISS : 0),2,',','.')?></td>
                        <td class="td_calendario" align="right"><?=number_format(($op_simples == 0 ? $valor_liquido : $valor_bruto),2,',','.')?></td>
                    </tr>
	<?	
					$total_desc_dep += $desc_dep;
					$total_INSS += $INSS;
		
					$total_valor_bruto += $valor_bruto;
					$total_IR += ($op_simples == 0 ? $IR : 0);
					$total_ISS += ($op_simples == 0 ? $ISS : 0);
					$total_valor_liquido += ($op_simples == 0 ? $valor_liquido : $valor_bruto);	
		
					$codigo_servicoAnterior 	= $codigo_servico;
					$descricao_servicoAnterior	= $descricao_servico;
					
					$loop++;
					// FIM DO LOOP
					}
	?>
					<tr>
						<th style="background-color: #999; font-weight: normal" colspan="3" align="right">Totais:&nbsp;</th>
						<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_valor_bruto,2,',','.')?></th>
						<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_IR,2,',','.')?></th>
						<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_ISS,2,',','.')?></th>
						<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_valor_liquido,2,',','.')?></th>
					</tr>
					<?
					$total_valor_bruto = 0;
					$total_INSS = 0;
					$total_desc_dep = 0;
					$total_IR = 0;
					$total_ISS = 0;
					$total_valor_liquido = 0;
					
			}else{
	?>

                <tr>
                    <td class="td_calendario">&nbsp;</td>
                    <td class="td_calendario">&nbsp;</td>
                    <td class="td_calendario">&nbsp;</td>
                    <td class="td_calendario">&nbsp;</td>
                    <td class="td_calendario">&nbsp;</td>
                    <td class="td_calendario">&nbsp;</td>
                    <td class="td_calendario">&nbsp;</td>
                </tr>
        
	<?	
			}
	?>
			</table>
			
			
	<?
		}
	// FIM PESSOA JURIDICA
}



	switch($categoria){
		case 'pessoa jurídica':
?>
            <div class="tituloVermelho" style="margin-top:20px; margin-bottom:10px">Como recolher o DARF</div>
             
                <ul>
                    <li>O recolhimento do <strong>IRRF</strong>, deverá ser efetuado até o dia 20 do mês seguinte à emissão da nota fiscal (se cair em feriado, antecipe). Você deve gerar um Darf único para todas as retenções de pagamentos a pessoa jurídica do mês, <strong>desde que todas tenham o mesmo código de receita</strong> (veja o código de receita, nesta página, no topo da tabela onde constam os pagamentos).  Siga <a href="darf_orientacoes.php">estas orientações</a> para emissão da guia.</li>
                </ul>
            

<?
		break;
		case 'pró-labore':
?>
            <div class="tituloVermelho" style="margin-top:20px; margin-bottom:10px">Como recolher os impostos</div>
            <ul>
                <li>O <strong>INSS</strong> referente ao pró-labore já estará automaticamente incluído na <strong>Gfip</strong> que sua empresa deve pagar todo mês.</li>
                <li>O recolhimento do <strong>IRRF</strong>, se houver, deverá ser efetuado até o dia 20 do mês seguinte aos pagamentos (se cair em feriado, antecipe). Você deve gerar um DARF único para as retenções de todos os sócios no mês. 
                Siga <a href="darf_orientacoes.php">estas orientações</a>, para emissão da guia.</li>
            </ul>

<?
		break;
		case 'Autônomos':
?>
            <div class="tituloVermelho" style="margin-top:20px; margin-bottom:10px">Como recolher os impostos</div>
            <ul>
                <li>O <strong>INSS</strong> referente ao autônomo já estará automaticamente incluído na <strong>Gfip</strong> que sua empresa deve pagar todo mês.</li>
                <li>O recolhimento do <strong>IRRF</strong>, se houver, deverá ser efetuado até o dia 20 do mês seguinte aos pagamentos (se cair em feriado, antecipe). Você deve gerar um DARF único para as retenções de todos os autônomos do mês. Siga <a href="darf_orientacoes.php">estas orientações</a>, para emissão da guia.</li>
            </ul>

<?
		break;
		case '':
?>


<?
		break;
		
	}
?>


</div>

<?php include 'rodape.php' ?>
