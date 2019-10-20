<?php include 'header_restrita.php' ?>


<div class="principal minHeight">

  <span class="titulo">Impostos e Obrigações</span><br /><br />

  <div style="margin-bottom:20px;" class="tituloVermelho">Recolhimento do INSS</div>

<?

$arrayAnexos = array();
$arrayAtividades = array();

foreach($_REQUEST as $requests=>$valores){ // PRECORRENDO OS DADOS ENVIADOS PARA ESTA PÁGINA VIA POST
	switch($requests){
		case 'txtMesAno': // SE FOR O MES / ANO
			$mes = substr($_REQUEST['txtMesAno'],0,2);
			$ano = substr($_REQUEST['txtMesAno'],3,4);
		break;
		case 'anexo_atividade': // SE FOR ATIVIDADE QUE NÃO POSSUIA PERGUNTA A RESPONDER
			foreach($valores as $campo=>$valor){
				if(in_array($valor,array('I','II','III','V', 'VI')))
				{
					array_push($arrayAnexos,"O");
				}else{
					if($valor == 'IV'){
						array_push($arrayAnexos,"IV");
					}
				}
			}
		break;
		case 'hddAtivIVCExercidas': 
			$AtividadesIVC = $valores;
		break;
		case 'hddAtivIVExercidas':
			$AtividadesIV = $valores;
		break;
		case 'hddAtivExercidas':
			$AtividadesO = $valores;
		break;
		default:
			if(substr($requests,0,5) == "ativ_") // SE FOR ALGUMA ATIVIDADE QUE POSSUIA PERGUNTA A RESPONDER
			{
				if(in_array(substr(substr($requests,-7),0,3),array('412','421','422','429','431','432','433','439'))){ // CHECA SE É COINCIDENTE
					if(in_array($valores,array('I','II','III','V', 'VI'))) // SE A ATIVIDADE FOR COINCIDENTE MAS A RESPOSTA À PERGUNTA GEROU UM ANEXO QUE NÃO O IV
					{
						array_push($arrayAnexos,"O");
					}else{
						array_push($arrayAnexos,$valores."C");
					}
				}else{
					array_push($arrayAnexos,$valores);
				}
			}
			if(substr($requests,0,7) == 'hddAtiv')
			{

			}
			if(substr($requests,0,8) == 'hddCNAEs'){
				$arrayAtividades = explode(',',substr($valores,1,strlen($valores)));
			}
			
		break;
	}
}
$arrayAnexos = array_unique($arrayAnexos,SORT_STRING);

if(strlen($AtividadesIVC) > 0){ // PEGANDO AS DENOMINAÇÕES DAS ATIVIDADES SELECIONADAS
	$sql = "SELECT denominacao FROM cnae WHERE REPLACE(REPLACE(REPLACE(cnae,'.',''),'-',''),'/','') IN (" . $AtividadesIVC . ")";
	$resultado2 = mysql_query($sql) or die (mysql_error());
	while($linha2=mysql_fetch_array($resultado2)){
		$vAtividadesIVC .= $linha2['denominacao'] . "<BR>";
	}
}

if(strlen($AtividadesIV) > 0){ // PEGANDO AS DENOMINAÇÕES DAS ATIVIDADES SELECIONADAS
	$sql = "SELECT denominacao FROM cnae WHERE REPLACE(REPLACE(REPLACE(cnae,'.',''),'-',''),'/','') IN (" . $AtividadesIV . ")";
	$resultado2 = mysql_query($sql) or die (mysql_error());
	while($linha2=mysql_fetch_array($resultado2)){
		$vAtividadesIV .= $linha2['denominacao'] . "<BR>";
	}
}

if(strlen($AtividadesO) > 0){ // PEGANDO AS DENOMINAÇÕES DAS ATIVIDADES SELECIONADAS
	$sql = "SELECT denominacao FROM cnae WHERE REPLACE(REPLACE(REPLACE(cnae,'.',''),'-',''),'/','') IN (" . $AtividadesO . ")";
	$resultado2 = mysql_query($sql) or die (mysql_error());
	while($linha2=mysql_fetch_array($resultado2)){
		$vAtividadesO .= $linha2['denominacao'] . "<BR>";
	}
}

$sql = "SELECT 
			pgto.id_pagto
			, pgto.valor_bruto
			, pgto.INSS
			, pgto.IR
			, pgto.ISS
			, pgto.valor_liquido
			, pgto.data_emissao
			, pgto.data_pagto  
			, case 
				  when pgto.id_autonomo <> 0 then 'Autônomo' 
				  when pgto.id_socio <> 0 then 'Sócio' 
			  end tipo
			, case 
				  when pgto.id_autonomo <> 0 then aut.id
				  when pgto.id_socio <> 0 then socio.idSocio
			  end id
			, case 
				  when pgto.id_autonomo <> 0 then aut.nome
				  when pgto.id_socio <> 0 then socio.nome
			  end nome
			, case 
				  when pgto.id_autonomo <> 0 then '2' 
				  when pgto.id_socio <> 0 then '1' 
			  end ordem
		FROM 
			dados_pagamentos pgto
			left join dados_autonomos aut on pgto.id_autonomo = aut.id
			left join dados_do_responsavel socio on pgto.id_socio = socio.idSocio
		WHERE 
			pgto.id_login='" . $_SESSION["id_empresaSecao"] . "'
			AND (pgto.id_socio <> 0 OR pgto.id_autonomo <> 0)
			AND (MONTH(pgto.data_pagto)  = '" . $mes . "' AND YEAR(pgto.data_pagto)  = '" . $ano . "')
		ORDER BY ordem, data_pagto DESC";


$resultado = mysql_query($sql)
or die (mysql_error());

if(mysql_num_rows($resultado) > 0){
?>
	<script>
<?

echo "var totalPagamentos = " . mysql_num_rows($resultado) . ";";

$rsRAT = mysql_fetch_array(mysql_query("SELECT rat.rat, cod.cnae
FROM dados_da_empresa_codigos cod
INNER JOIN cnae_rat rat ON cod.cnae = rat.cnae
WHERE cod.id = " . $_SESSION["id_empresaSecao"] . "
AND cod.tipo = 1
LIMIT 1"));

$cnaePrincipal = "";

if(in_array(substr($rsRAT['cnae'],0,3),array('412','421','422','429','431','432','433','439'))){
	$cnaePrincipal = 'Coincidente';
}else{
	$cnaePrincipal = 'NaoCoincidente';
}


$display_tabela = 'none';
$display_faturamento = 'none';
$display_aviso_helpdesk = 0;
$display_mensagem_cpp = 0;
$display_resultado = 0;
$calcula_i_cpp = 1;

if(count($arrayAnexos) == 1){
	if($arrayAnexos[0] == 'IVC'){// SOMENTE COINCIDENTE
		// EM 18/02/2015 - CPP passa a ser calculado automaticamente pelo sistema do e-CAC
		$display_mensagem_cpp = 1;
		echo 'location.href="inss_sem_cpp.php";';
//		$display_faturamento 		= 'block';
//		$controle_situacao 			= 'somenteIVCoincidente';
	} else {// SOMENTE NÃO COINCIDENTE
		$display_resultado 			= 1;
		$calcula_i_cpp 				= 0;
		$controle_situacao 			= 'somenteIVNaoCoincidente';
	}
	
} else {
	if(in_array('IVC',$arrayAnexos) && in_array('IV',$arrayAnexos) && in_array('O',$arrayAnexos)){ // ANEXO IV COINCIDENTE E NÃO COINDICENTE E OUTROS ANEXOS
		$display_aviso_helpdesk 	= 1;
		$controle_situacao = 'IVCoincidenteIVNaoCoincidenteOutros';
	}elseif(in_array('IVC',$arrayAnexos) && in_array('O',$arrayAnexos)){ // COINCIDENTE E OUTROS ANEXOS
		// EM 18/02/2015 - CPP passa a ser calculado automaticamente pelo sistema do e-CAC
		$display_mensagem_cpp = 1;
		echo 'location.href="inss_sem_cpp.php";';
//		$display_faturamento 		= 'block';
//		$controle_situacao 			= 'IVCoincidenteOutros';
	}elseif(in_array('IV',$arrayAnexos) && in_array('O',$arrayAnexos)){ // NÃO COINCIDENTE E OUTROS ANEXOS
		$display_tabela 			= 'block';
		$display_faturamento 		= 'block';
		$controle_situacao 			= 'IVNaoCoincidenteOutros';
	}elseif(in_array('IV',$arrayAnexos) && in_array('IVC',$arrayAnexos)){ // ANEXO IV COINSCIDENTE COM NÃO CONINSICDENTE E ATIVIDADE PRINCIPAL COINCIDENTE
		$display_aviso_helpdesk 	= 1;
		$controle_situacao 			= 'IVCoincidenteIVNaoCoincidente';
	}
}

$rsValoresMisto = mysql_fetch_array(mysql_query("SELECT SUM(CASE WHEN id_socio > 0 THEN valor_liquido ELSE 0 END) prolabores_misto
, SUM(CASE WHEN id_autonomo > 0 THEN valor_liquido ELSE 0 END) salarios_misto
FROM dados_pagamentos 
WHERE id_login='" . $_SESSION["id_empresaSecao"] . "'
AND (MONTH(data_pagto)  = '" . $mes . "' AND YEAR(data_pagto)  = '" . $ano . "')
"));


$rsValoresINSS = mysql_fetch_array(mysql_query("SELECT SUM(CASE WHEN id_socio > 0 THEN INSS ELSE 0 END) inssProlabores
, SUM(CASE WHEN id_autonomo > 0 THEN INSS ELSE 0 END) inssSalarios
FROM dados_pagamentos 
WHERE id_login='" . $_SESSION["id_empresaSecao"] . "'
AND (MONTH(data_pagto)  = '" . $mes . "' AND YEAR(data_pagto)  = '" . $ano . "')
"));
?>

		var envio = false;

		var mes = 0;
		var ano = 0;
		var hoje = new Date();
		var anoAtual = hoje.getFullYear();
		var arr_anexos = new Array();
		var bloqueia = 0;


		$(document).ready(function(e) {
	

	
			$('.ico_vermelho').bind('click',function(e){
				$(this).toggleClass('ico_vermelho');
				$(this).toggleClass('ico_verde');
			});
			
			
			$("input[id^='anexo_atividade_']").click(function(){
				if($(this).attr('checked')){
					if($('#' + $(this).attr('divid')).html()){
						$('#' + $(this).attr('divid')).css('display','block');
					}
				}else{
					if($('#' + $(this).attr('divid')).html()){
						$('#' + $(this).attr('divid')).css('display','none');
					}
				}
			});


			$('#bt_gerar').click(function(e){
				e.preventDefault();
				location.href='inss_gerar_gps.php';
			});
			
			/*
			Botão Prosseguir **************************************************************************************
			*/
			<? if($controle_situacao != 'somenteIVNaoCoincidente'){ ?>
			$('#bt_prosseguir').click(function(e){
				e.preventDefault();
			<? } ?>

				
				var salario_anexoIV = 0;
				var prolabore_anexoIV = 0;
				var salario_misto = 0;
				var prolabore_misto = 0;
				<? echo "var rat 				= (" . $rsRAT['rat'] . " / 100);" . "\n"; ?>
				<? echo "var cnaePrincipal 		= '".$cnaePrincipal."';" . "\n";?>
				<? echo "var inssProlabores 	= " . $rsValoresINSS['inssProlabores'] . ";" . "\n";?>
				<? echo "var inssSalarios		= " . $rsValoresINSS['inssSalarios'] . ";" . "\n";?>


				var aliquota_cpp_padrao = 0.2;
				var aliquota_cpp_coincidente = 0.02;
				var aliquota_cpp_salario = aliquota_cpp_padrao + rat;
				
				var faturamento_anexoIV_nao_coincidente = 0;
				var faturamento_anexoIV_coincidente = 0;
				var faturamento_anexoIV = 0;
				var faturamento_total = 0;
				
				var i_cpp = 0;
				var i_cpp_puro = 0;

				var cpp_salario_anexoIV = 0;
				var cpp_prolabore_anexoIV = 0;
				var cpp_anexoIV = 0;

				var cpp_salario_misto = 0;
				var cpp_prolabore_misto = 0;
				var cpp_misto = 0;

				var cpp_plena = 0;
				var cpp_coincidente = 0;

				var cpp = 0;
				
				var faturamento = false;
				var respostas = false;
				
				var arrNomesFuncionariosAnexoIV = new Array();
				var arrNomesFuncionariosOutros = new Array();
				var arrNomesSociosAnexoIV = new Array();
				var arrNomesSociosOutros = new Array();
				var arrSalariosAnexoIV = new Array();
				var arrSalariosOutros = new Array();
				var arrProlaboreAnexoIV = new Array();
				var arrProlaboreOutros = new Array();

				// variavel que armazena os ids dos pagamentos para checar se estes foram selecionados em pelo menos uma atividade
				var arrPagamentosChecked = new Array();
				var id_pagto = 0;
					
							
				if($('.atividadesCPP').length){ // checando se há dados a serem calculados


				<?
				if($controle_situacao != 'somenteIVNaoCoincidente'){ // se houver a necessidade de mostrar as tabelas devem ser feitas as validações ?>
					<?
					if($controle_situacao == 'IVNaoCoincidenteOutros'){ ?>
						$(document).find("input[name^='chkAtividades']").each(function(index, element) {
	//					$("input[name^='txtIDPagto']").eq(index).val();
							if($(this).attr('checked')){
								id_pagto = $("input[name^='txtIDPagto']").eq(index).val();
								// se não for localizado o id do pagamento no array, insere
								if($.inArray(id_pagto,arrPagamentosChecked) < 0){
									arrPagamentosChecked.push(id_pagto);
								}
								respostas = true;
							}
						});

						if(!respostas){
							alert('Selecione os funcionários que atuaram nas atividades.');
							return false;
						}

						if(totalPagamentos > arrPagamentosChecked.length){
							alert('Há trabalhadores sem marcação. Certifique-se de que todos estejam marcados em pelo menos uma atividade.');
							return false;
						}

					<?
					} // FIM DO IF $controle_situacao == 'IVNaoCoincidenteOutros'
					
									
					if($controle_situacao == 'somenteIVCoincidente' || $controle_situacao == 'IVCoincidenteOutros'){ ?>
						$('input[id^="faturamento"]').each(function(index,element){
							if($(this).val() != ''){
								faturamento = true;
							}
						});

						if(!faturamento){
							alert('Preencha o faturamento das atividades.');
							return false;
						}
					<?
					} // FIM DO IF $controle_situacao == 'somenteIVCoincidente' || $controle_situacao == 'IVCoincidenteOutros'
					?>

				<?
				}
				else // ELSE DO IF $controle_situacao != 'somenteIVNaoCoincidente'
				{
				?>
					$(document).find("input[name^='chkAtividades']").each(function(index, element) {
												
						$(this).attr('checked',true);
						
					});
				<?
				} // FIM DO IF $controle_situacao != 'somenteIVNaoCoincidente'
				?>
	
					
					var AtivIV_naoCoincidente = false;
					var AtivIV_Coincidente = false;
					var AtivOutros = false;

					$('.atividadesCPP').each(function(index, element) { // percorrendo todas as tabelas da página

						var elementoTabela = $(this); // separando o elemento tabela 
						var ID_TABELA = elementoTabela.attr('id'); // id desta tabela

						elementoTabela.find('tr.dados').each(function(index,element){ // percorrendo as linhas de dados

							var nome = ($(this).find('td.nome').html()); // texto da coluna nome
							var tipo = ($(this).find('td.tipo').html()); // texto da coluna tipo
							var checkBox = $(this).find("input[name^='chkAtividades']"); // variavel que aponta para o objeto checkbox
							var valor = $(this).find("input[name^='chkAtividades']").val(); // valor do checkbox
							var INSS = $(this).find("input[name^='txtINSS']").val(); // valor do INSS
						
							if(ID_TABELA == 'IV' || ID_TABELA == 'IVC'){ // se for tabela referente ao anexo IV

								if(tipo == 'Sócio'){ // se for do tipo sócio...
									if(checkBox.attr('checked') || elementoTabela.length == 1){ // ...e estiver selecionado
										if($.inArray(nome,arrNomesSociosAnexoIV) < 0){ // não consta na array com os nomes dos sócios selecionados para anexo IV
											arrNomesSociosAnexoIV.push(nome); // inclui o nome na array correspondente e...
											arrProlaboreAnexoIV.push(valor); // inclui o valor na array correspondente
										}
									}
								}else{// ... ou se for de outro tipo - autonomos e funcionarios ...
									if(checkBox.attr('checked') || elementoTabela.length == 1){ // ... e estiver selecionado
										if($.inArray(nome,arrNomesFuncionariosAnexoIV) < 0){// não consta na array com os nomes dos funcionarios selecionados para anexo IV
											arrNomesFuncionariosAnexoIV.push(nome); // inclui o nome na array correspondente e...
											arrSalariosAnexoIV.push(valor); // inclui o valor na array correspondente
										}

									}
								}
							}else{ // se for tabela referente a outros anexos
								
								if(tipo == 'Sócio'){// se for do tipo sócio...
									if(checkBox.attr('checked') || elementoTabela.length == 1){// ...e estiver selecionado
										if($.inArray(nome,arrNomesSociosOutros) < 0){// não consta na array com os nomes dos sócios selecionados para outros anexos
											arrNomesSociosOutros.push(nome);// inclui o nome na array correspondente e...
											arrProlaboreOutros.push(valor);// inclui o valor na array correspondente
										}

									}
								}else{
									if(checkBox.attr('checked') || elementoTabela.length == 1){
										//alert($.inArray(nome,arrNomesFuncionariosAnexoIV));
										if($.inArray(nome,arrNomesFuncionariosOutros) < 0){// não consta na array com os nomes dos funcionarios selecionados para outros anexos
											arrNomesFuncionariosOutros.push(nome);// inclui o nome na array correspondente e...
											arrSalariosOutros.push(valor);// inclui o valor na array correspondente
										}
									}
								}
								
							}


														
						}); // FIM DO .each tr.dados
						
						if(ID_TABELA == 'IV'){

							if($('#faturamentoIV').val() != ''){
								faturamento_anexoIV_nao_coincidente += parseFloat($('#faturamentoIV').val().replace(".","").replace(",","."));
								faturamento_anexoIV += parseFloat($('#faturamentoIV').val().replace(".","").replace(",","."));
								AtivIV_naoCoincidente = true;
							}

						}else if(ID_TABELA == 'IVC'){

							if($('#faturamentoIVC').val() != ''){
								faturamento_anexoIV_coincidente += parseFloat($('#faturamentoIVC').val().replace(".","").replace(",","."));
								faturamento_anexoIV += parseFloat($('#faturamentoIVC').val().replace(".","").replace(",","."));
								AtivIV_Coincidente = true;
							}
						}else{

							AtivOutros = true;

						}
					});



					<?
					if($controle_situacao != 'somenteIVNaoCoincidente'){ // se houver a necessidade de mostrar as tabelas devem ser feitas as validações 
					?>
				
					$('input[id^="faturamento"]').each(function(index,element){
						if($(this).val() != ''){
							faturamento = true;
						}
					});

					if(!faturamento){
						alert('Preencha o faturamento das atividades.');
						return false;
					}
						
					<?
					}
					?>
					
					$('input[id^="faturamento"]').each(function(index,element){
						if($(this).val() != ''){
							faturamento_total += parseFloat($(this).val().replace(".","").replace(",","."));
						}
					});

					for(var i=0; i<arrNomesFuncionariosAnexoIV.length; i++){ // percorrendo o array com nomes dos funcionarios para popular as variaveis de salarios
						if($.inArray(arrNomesFuncionariosAnexoIV[i],arrNomesFuncionariosOutros) < 0){ // se não consta o mesmo nome na array de Outros Anexos
							salario_anexoIV += parseFloat(arrSalariosAnexoIV[i]); // soma o valor na variavel de salarios de Anexo IV
						}else{ // senão
							salario_misto += parseFloat(arrSalariosAnexoIV[i]); // soma o valor na variavel de salarios misto
						}
					}

					for(var i=0; i<arrNomesSociosAnexoIV.length; i++){ // percorrendo o array com nomes dos sócios para popular as variaveis de prolabore
						if($.inArray(arrNomesSociosAnexoIV[i],arrNomesSociosOutros) < 0){// se não consta o mesmo nome na array de Outros Anexos
							prolabore_anexoIV += parseFloat(arrProlaboreAnexoIV[i]);// soma o valor na variavel de prolabore de Anexo IV
						}else{// senão
							prolabore_misto += parseFloat(arrProlaboreOutros[i]);// soma o valor na variavel de prolabore misto
						}
					}
					


					var txtResult = "";					
					/* CALCULOS */
									
<?	
switch ($controle_situacao){
	case 'somenteIVCoincidente':
	case 'IVCoincidenteOutros':
	?>
					
					cpp = parseFloat((faturamento_total * aliquota_cpp_coincidente).toFixed(2));

					txtResult += 'rat: ' + rat.toFixed(2) + "<br />";
					txtResult += 'aliquota_cpp_padrao: ' + aliquota_cpp_padrao.toFixed(2) + "<br />";
					txtResult += 'aliquota_cpp_coincidente: ' + aliquota_cpp_coincidente.toFixed(2) + "<br />";
					txtResult += 'aliquota_cpp_salario: ' + aliquota_cpp_salario.toFixed(2) + "<br /><br />";

					txtResult += 'faturamento_total: ' + faturamento_total.toFixed(2) + "<br /><br />";

					txtResult += 'cpp: ' + cpp + "<br /><br />";
					
					txtResult += 'inssProlabores: ' + inssProlabores + "<br />";
					txtResult += 'inssSalarios: ' + inssSalarios + "<br />";

						

						
	<?
	break;
	case 'somenteIVNaoCoincidente':
	?>
	
					cpp_salario_anexoIV = parseFloat((salario_anexoIV * aliquota_cpp_salario).toFixed(2));
					cpp_prolabore_anexoIV = parseFloat((prolabore_anexoIV * aliquota_cpp_padrao).toFixed(2));
					cpp_anexoIV = (cpp_salario_anexoIV + cpp_prolabore_anexoIV);
								
					cpp = parseFloat(cpp_anexoIV.toFixed(2));
					
					txtResult += 'salario_anexoIV: ' + salario_anexoIV.toFixed(2) + "<br />";
					txtResult += 'prolabore_anexoIV: ' + prolabore_anexoIV.toFixed(2) + "<br /><br />";
					
					//txtResult += 'salario_misto: ' + salario_misto.toFixed(2) + "<br />";
					//txtResult += 'prolabore_misto: ' + prolabore_misto.toFixed(2) + "<br /><br />";

					txtResult += 'rat: ' + rat.toFixed(2) + "<br />";
					txtResult += 'aliquota_cpp_padrao: ' + aliquota_cpp_padrao.toFixed(2) + "<br />";
					txtResult += 'aliquota_cpp_coincidente: ' + aliquota_cpp_coincidente.toFixed(2) + "<br />";
					txtResult += 'aliquota_cpp_salario: ' + aliquota_cpp_salario.toFixed(2) + "<br /><br />";
					
					//txtResult += 'faturamento_anexoIV_coincidente: ' + faturamento_anexoIV_coincidente.toFixed(2) + "<br />";
					//txtResult += 'faturamento_anexoIV: ' + faturamento_anexoIV.toFixed(2) + "<br />";
					//txtResult += 'faturamento_total: ' + faturamento_total.toFixed(2) + "<br /><br />";
					
					//txtResult += 'i_cpp: ' + i_cpp + "<br /><br />";

					//txtResult += 'i_cpp_puro: ' + i_cpp_puro + "<br /><br />";
					
					txtResult += 'cpp_salario_anexoIV: ' + cpp_salario_anexoIV + "<br />";
					txtResult += 'cpp_prolabore_anexoIV: ' + cpp_prolabore_anexoIV + "<br />";
					txtResult += 'cpp_anexoIV: ' + cpp_anexoIV + "<br /><br />";
					
					//txtResult += 'cpp_salario_misto: ' + cpp_salario_misto + "<br />";
					//txtResult += 'cpp_prolabore_misto: ' + cpp_prolabore_misto + "<br />";
					//txtResult += 'cpp_misto: ' + cpp_misto + "<br /><br />";
					
					//txtResult += 'cpp_plena: ' + cpp_plena + "<br />";
					//txtResult += 'cpp_coincidente: ' + cpp_coincidente + "<br />";
					txtResult += 'cpp: ' + cpp + "<br /><br />";
					
					txtResult += 'inssProlabores: ' + inssProlabores + "<br />";
					txtResult += 'inssSalarios: ' + inssSalarios + "<br />";
						
	<?
	break;
	case 'IVCoincidenteOutros':
						
/*
					cpp_salario_anexoIV = parseFloat((salario_anexoIV * aliquota_cpp_salario).toFixed(2));
					cpp_prolabore_anexoIV = parseFloat((prolabore_anexoIV * aliquota_cpp_padrao).toFixed(2));
					cpp_anexoIV = (cpp_salario_anexoIV + cpp_prolabore_anexoIV);
			
					i_cpp = <? if($controle_situacao != 'somenteIVNaoCoincidente'){ ?> parseFloat((faturamento_anexoIV / faturamento_total).toFixed(2)) <? } else {?> 1 <? } ?>;

					cpp_salario_misto = parseFloat((salario_misto * aliquota_cpp_salario).toFixed(2));
					cpp_prolabore_misto = parseFloat((prolabore_misto * aliquota_cpp_padrao).toFixed(2));
					cpp_misto = parseFloat(((cpp_salario_misto + cpp_prolabore_misto) * i_cpp).toFixed(2));


**
					if(AtivIV_naoCoincidente == false && AtivIV_Coincidente == true && AtivOutros == false){
						cpp_plena = 0;
					}else{
						cpp_plena = (cpp_anexoIV + cpp_misto);
					}
**
?>
					
					if(AtivIV_naoCoincidente == true && AtivIV_Coincidente == false && AtivOutros == false){
						cpp_coincidente = 0;
					}else{
						cpp_coincidente = parseFloat((faturamento_anexoIV_coincidente * aliquota_cpp_coincidente).toFixed(2));
					}
					
					<? if($controle_situacao == 'somenteIVCoincidente' || $controle_situacao == 'IVCoincidenteOutros'){ ?>
						cpp = parseFloat((faturamento_total * aliquota_cpp_coincidente).toFixed(2));
					<?						
					} else {
					?>
						cpp = parseFloat(((cpp_plena * i_cpp) + cpp_coincidente).toFixed(2));
					<?						
					}
					?>
					
					
					txtResult += 'salario_anexoIV: ' + salario_anexoIV.toFixed(2) + "<br />";
					txtResult += 'prolabore_anexoIV: ' + prolabore_anexoIV.toFixed(2) + "<br /><br />";
					
					txtResult += 'salario_misto: ' + salario_misto.toFixed(2) + "<br />";
					txtResult += 'prolabore_misto: ' + prolabore_misto.toFixed(2) + "<br /><br />";

					txtResult += 'rat: ' + rat.toFixed(2) + "<br />";
					txtResult += 'aliquota_cpp_padrao: ' + aliquota_cpp_padrao.toFixed(2) + "<br />";
					txtResult += 'aliquota_cpp_coincidente: ' + aliquota_cpp_coincidente.toFixed(2) + "<br />";
					txtResult += 'aliquota_cpp_salario: ' + aliquota_cpp_salario.toFixed(2) + "<br /><br />";
					
					txtResult += 'faturamento_anexoIV_coincidente: ' + faturamento_anexoIV_coincidente.toFixed(2) + "<br />";
					txtResult += 'faturamento_anexoIV: ' + faturamento_anexoIV.toFixed(2) + "<br />";
					txtResult += 'faturamento_total: ' + faturamento_total.toFixed(2) + "<br /><br />";
					
					txtResult += 'i_cpp: ' + i_cpp + "<br /><br />";

					//txtResult += 'i_cpp_puro: ' + i_cpp_puro + "<br /><br />";
					
					txtResult += 'cpp_salario_anexoIV: ' + cpp_salario_anexoIV + "<br />";
					txtResult += 'cpp_prolabore_anexoIV: ' + cpp_prolabore_anexoIV + "<br />";
					txtResult += 'cpp_anexoIV: ' + cpp_anexoIV + "<br /><br />";
					
					txtResult += 'cpp_salario_misto: ' + cpp_salario_misto + "<br />";
					txtResult += 'cpp_prolabore_misto: ' + cpp_prolabore_misto + "<br />";
					txtResult += 'cpp_misto: ' + cpp_misto + "<br /><br />";
					
					txtResult += 'cpp_plena: ' + cpp_plena + "<br />";
					txtResult += 'cpp_coincidente: ' + cpp_coincidente + "<br />";
					txtResult += 'cpp: ' + cpp + "<br /><br />";
					
					txtResult += 'inssProlabores: ' + inssProlabores + "<br />";
					txtResult += 'inssSalarios: ' + inssSalarios + "<br />";
*/
						
	break;
	case 'IVNaoCoincidenteOutros':
	?>
	
						
					cpp_salario_anexoIV = parseFloat((salario_anexoIV * aliquota_cpp_salario).toFixed(2));
					cpp_prolabore_anexoIV = parseFloat((prolabore_anexoIV * aliquota_cpp_padrao).toFixed(2));
					cpp_anexoIV = (cpp_salario_anexoIV + cpp_prolabore_anexoIV);
			
					i_cpp = <? if($controle_situacao != 'somenteIVNaoCoincidente'){ ?> parseFloat((faturamento_anexoIV / faturamento_total).toFixed(2)) <? } else {?> 1 <? } ?>;

					cpp_salario_misto = parseFloat((salario_misto * aliquota_cpp_salario).toFixed(2));
					cpp_prolabore_misto = parseFloat((prolabore_misto * aliquota_cpp_padrao).toFixed(2));
					cpp_misto = parseFloat(((cpp_salario_misto + cpp_prolabore_misto) * i_cpp).toFixed(2));
					
					cpp = parseFloat((cpp_anexoIV + cpp_misto).toFixed(2));
					
					
					txtResult += 'salario_anexoIV: ' + salario_anexoIV.toFixed(2) + "<br />";
					txtResult += 'prolabore_anexoIV: ' + prolabore_anexoIV.toFixed(2) + "<br /><br />";
					
					txtResult += 'salario_misto: ' + salario_misto.toFixed(2) + "<br />";
					txtResult += 'prolabore_misto: ' + prolabore_misto.toFixed(2) + "<br /><br />";

					txtResult += 'rat: ' + rat.toFixed(2) + "<br />";
					txtResult += 'aliquota_cpp_padrao: ' + aliquota_cpp_padrao.toFixed(2) + "<br />";
					txtResult += 'aliquota_cpp_coincidente: ' + aliquota_cpp_coincidente.toFixed(2) + "<br />";
					txtResult += 'aliquota_cpp_salario: ' + aliquota_cpp_salario.toFixed(2) + "<br /><br />";
					
					txtResult += 'faturamento_anexoIV: ' + faturamento_anexoIV.toFixed(2) + "<br />";
					txtResult += 'faturamento_total: ' + faturamento_total.toFixed(2) + "<br /><br />";
					
					txtResult += 'i_cpp: ' + i_cpp + "<br /><br />";
					
					txtResult += 'cpp_salario_anexoIV: ' + cpp_salario_anexoIV + "<br />";
					txtResult += 'cpp_prolabore_anexoIV: ' + cpp_prolabore_anexoIV + "<br />";
					txtResult += 'cpp_anexoIV: ' + cpp_anexoIV + "<br /><br />";
					
					txtResult += 'cpp_salario_misto: ' + cpp_salario_misto + "<br />";
					txtResult += 'cpp_prolabore_misto: ' + cpp_prolabore_misto + "<br />";
					txtResult += 'cpp_misto: ' + cpp_misto + "<br /><br />";
					
					txtResult += 'cpp: ' + cpp + "<br /><br />";
					
					txtResult += 'inssProlabores: ' + inssProlabores + "<br />";
					txtResult += 'inssSalarios: ' + inssSalarios + "<br />";

						
	<?
	break;
}
			
?>
					
					


			


				}



				$('#cppFinal').html('R$ ' + formatReal(limpaCaracteres((cpp + inssProlabores + inssSalarios).toFixed(2))));
				$('#celula_resultado_CPP').html('R$ ' + formatReal(limpaCaracteres((cpp).toFixed(2))));
				
				$.ajax({
					url: 'INSS_config.php',
					data: 'valor='+formatReal(limpaCaracteres((cpp + inssProlabores + inssSalarios).toFixed(2))),
					dataType:"text",
					type:"POST",
					cache:false,
					success: function(response){
					}
				});

				//$('#variaveis').css({'font-style':'italic','font-size':'90%','color':'#000'}).html(txtResult);

				$('#tabelas').css('display','none');				
				$('#calculo').css('display','none');				
				$('#resultado').css('display','block');
				
				
			<? if($controle_situacao != 'somenteIVNaoCoincidente'){ ?>
				return false;
			});
			<? } ?>
			
			$('.btVoltar').click(function(e){
				e.preventDefault();
				$('div[id^="aviso"]').css('display','none');
				$('#perguntas').css('display','block');
			});
		

    });

</script>

<div id="perguntas">

    <div style="">
		<?
        if($display_aviso_helpdesk == 1){// IF CHECA AVISO HELPDESK
        ?>
        <div id="aviso" class="quadro_passos_sem_largura atividadesCPP" style="padding:40px;">
    
            <div style="display: block; width:100%;">
    
                <div class="perguntas_principais_quadros" style="margin-bottom:20px;">
                    Impossível prosseguir
                </div>
                    
                <div  style="margin-bottom:20px; text-align: left;" class="opcao_simples">
                    Não foi possível calcular automaticamente o valor do seu INSS, pois sua empresa possui atividades enquadradas em modelos de tributação especial.<br /><br />
	                Entre em contato com o helpdesk para obter mais informações.
                </div>
                
                <div class="caixa_bt_opcoes_quadro">
	                <div class="bt_opcao_quadro" data-div=""><a href="inss.php">Voltar</a></div>
                </div>
                        
            </div>
            
        </div>
		<?
        } else {
					
					
					if($display_mensagem_cpp == 1){ // MOSTRA A MENSAGEM CASO DEVA SER BLOQUEADO PELO CALCULO DO CPP ser AUTOMATICO em 2015
	        ?>

          <div id="aviso" class="quadro_passos_sem_largura atividadesCPP" style="padding:40px;">
      
              <div style="display: block; width:100%;">
      
                  <div class="perguntas_principais_quadros" style="margin-bottom:20px;">
                      Impossível prosseguir
                  </div>
                      
                  <div  style="margin-bottom:20px; text-align: left;" class="opcao_simples">
                      CPP é calculada sobre a Receita Bruto e a guia de pagamento será gerada automaticamente ao efetuar a apuração do Simples (no E-CAC).
                  </div>
                  
                  <div class="caixa_bt_opcoes_quadro">
                    <div class="bt_opcao_quadro" data-div=""><a href="inss.php">Voltar</a></div>
                  </div>
                          
              </div>
              
          </div>
          
        <?
					}else{
				?>
        
<?
//if($_SESSION["id_empresaSecao"] == 1581){
//	echo '<BR>'.$controle_situacao.'<BR>';
//}
?>
        
            <div id="tabelas" style="margin-bottom:20px;display: <? echo ($controle_situacao == 'somenteIVNaoCoincidente' ? 'none' : 'block') ?>;">
          
                  <div style="margin-bottom:20px;display:<?=$display_tabela?>;">
                      Informe quais funcionários atuaram nas atividades que você selecionou para o período:
                  </div>
                  
									<?				
                  if($controle_situacao == 'somenteIVCoincidente' || $controle_situacao == 'IVCoincidenteOutros'){  
            
                    mysql_data_seek($resultado,0);
                    $tabela_resultado_CPP = '
                    <table width="500" cellpadding="5">
                      <th width="75%" style="background-color: #999; font-weight: normal" align="right">Totais:&nbsp;</th>
                      <th width="25%" style="background-color: #999; font-weight: normal" align="right" id="celula_resultado_CPP"></th>
                    </table>
                    ';
            
                    $tabela_resultado_INSS = '
                    <table width="500" cellpadding="5">
                      <tr>
                        <th width="50%">Nome</th>
                        <th width="25%">Categoria</th>
                        <th width="25%">INSS</th>
                      </tr>
                    ';
                    while($linha = mysql_fetch_array($resultado)){
                      // MONTA A TABELA QUE SERÁ MOSTRADA NO RESULTADO
                      $tabela_resultado_INSS .= '
                      <tr>
                        <td class="td_calendario">'.$linha['nome'].'</td>
                        <td class="td_calendario tipo">'.$linha['tipo'].'</td>
                        <td class="td_calendario" align="right">'.number_format($linha['INSS'],2,',','.').'</td>
                      </tr>
                      ';
                      $total_INSS += $linha['INSS'];
                    }
                    $tabela_resultado_INSS .= '
                    <th style="background-color: #999; font-weight: normal" align="right" colspan="2">Totais:&nbsp;</th>
                    <th style="background-color: #999; font-weight: normal" align="right"> R$ '.number_format($total_INSS,2,',','.'). '</th></table>';
            
                  
                    foreach($arrayAnexos as $campo => $valor){ 
                      if($valor == 'IVC'){
                  ?> 
                                <div class="atividadesCPP" style="margin-bottom:20px;">
                                  <div style="margin-bottom:5px;">
                                      Informe quanto faturou na atividade de <strong><?=($valor == 'IVC' ? $vAtividadesIVC : ($valor == 'IV' ? $vAtividadesIV : $vAtividadesO))?></strong>
                                    </div>
                                    R$ <input type="text" name="faturamento<?=($valor)?>" class="current" id="faturamento<?=($valor)?>" />
                                </div>
                  <?
                      }
                    }
                    
                  }else{				

										foreach($arrayAnexos as $campo => $valor){ 
										?> 
											<div id="<?=($valor)?>" class="atividadesCPP" style="margin-bottom:20px;">
												<strong><?=($valor == 'IVC' ? $vAtividadesIVC : ($valor == 'IV' ? $vAtividadesIV : $vAtividadesO))?></strong>
												<table width="95%" cellpadding="5" style="font-family: Arial, Helvetica, sans-serif;display:<?=$display_tabela?>;">
													<tr>
														<th width="55%">Nome do colaborador</th>
														<th width="25%">Categoria</th>
														<th width="15%">Remuneração</th>
														<th width="5%">Atuou</th>
													</tr>
													<? 
													mysql_data_seek($resultado,0);
													$total_INSS = 0;
													$tabela_resultado_CPP = '
													<table width="500" cellpadding="5">
														<th width="75%" style="background-color: #999; font-weight: normal" align="right">Totais:&nbsp;</th>
														<th width="25%" style="background-color: #999; font-weight: normal" align="right" id="celula_resultado_CPP"></th>
													</table>
													';
						
													$tabela_resultado_INSS = '
													<table width="500" cellpadding="5">
														<tr>
															<th width="50%">Nome</th>
															<th width="25%">Categoria</th>
															<th width="25%">INSS</th>
														</tr>
													';
													
													while($linha = mysql_fetch_array($resultado)){
														// MONTA A TABELA QUE SERÁ MOSTRADA NO RESULTADO
														$tabela_resultado_INSS .= '
														<tr>
															<td class="td_calendario">'.$linha['nome'].'</td>
															<td class="td_calendario tipo">'.$linha['tipo'].'</td>
															<td class="td_calendario" align="right">'.number_format($linha['INSS'],2,',','.').'</td>
														</tr>
														';
														$total_INSS += $linha['INSS'];
														
													?>
													<tr class="dados">
														<td class="td_calendario nome"><?=$linha['nome']?></td>
														<td class="td_calendario tipo"><?=$linha['tipo']?></td>
														<td class="td_calendario coluna_valor" align="right">R$ <?=number_format($linha['valor_bruto'],2,',','.')?></td>
														<td class="td_calendario" align="center"><input type="checkbox" name="chkAtividades<?=($valor)?>" value="<?=($linha['valor_bruto'])?>" /><input type="hidden" name="txtINSS" value="<?=($linha['INSS'])?>" /><input type="hidden" name="txtIDPagto" value="<?=($linha['id_pagto'])?>" /></td>
													</tr>
													<?
													}
													$tabela_resultado_INSS .= '
													<th style="background-color: #999; font-weight: normal" align="right" colspan="2">Totais:&nbsp;</th>
													<th style="background-color: #999; font-weight: normal" align="right"> R$ '.number_format($total_INSS,2,',','.'). '</th></table>'
													?>
												</table>
												<div style="padding:5px 0;">
													Quanto faturou nesta(s) atividade(s)? <input type="text" name="faturamento<?=$valor?>" class="current" id="faturamento<?=$valor?>" />
												</div>
											</div>
										<?
										} 
									} // FIM IF CHECA COINCIDENTES
								?>
	    				</div><!-- /tabelas -->

        			<div style="clear:both"></div>

							<div id="calculo">
            
                <div style="width:120px; margin-bottom:40px; padding:0;display:<? echo ($controle_situacao == 'somenteIVNaoCoincidente' ? 'none' : 'inline-block') ?>;">
                    <input type="button" id="bt_prosseguir" value="Calcular" />
                </div>
                <div style="clear:both"></div>
        
            	</div>
        
              <div id="resultado" style="display:<? echo ($controle_situacao == 'somenteIVNaoCoincidente' ? 'inline-block' : 'none') ?>;">
                <div>
                  <div class="destaqueAzul" style="margin-bottom: 10px;">
                    Resultado
                  </div>
                  <div style="width:780px; margin-bottom:20px;">
                      <span id="variaveis"></span>
                      <div style="float: left; margin-right: 5px; line-height: 20px;">O valor da sua GPS para o período de <? echo mesExtenso($mes); ?> de <?=$ano?> é: </div>
                      <div id="cppFinal" class="destaque" style="font-size:18px; float: left; margin-right: 15px; line-height: 20px;"></div> 
                      <div style="width:120px; margin: 0; padding:0; float: left;">
                          <input type="button" id="bt_gerar" value="Gerar guia de recolhimento" />
                      </div>
                      <div style="clear: both; height:20px;"></div>
                      Este valor engloba a CPP (Contribuição Patronal Previdenciária) e o INSS referente a pró-labore, salários e trabalhadores autônomos no período, conforme discriminado nas tabelas abaixo. Atenção: a relação de trabalhadores deve ser a mesma que você usou ao gerar sua Gfip!
                  </div>
                  <div style="margin-bottom:20px;">
                      <div class="tituloAzulPequeno">
                          INSS
                      </div>
            <?=$tabela_resultado_INSS?>
                  </div>
                  <div style="margin-bottom:20px;">
                      <div class="tituloAzulPequeno">
                          CPP (Contribuição Patronal Previdenciária)
                      </div>
            <?=$tabela_resultado_CPP?>
                  </div>
                  
              </div>
          </div>

        <?  
					}// FIM IF CHECA AVISO CPP
        } // FIM IF CHECA HELPDESK
        ?>
    
    </div><!-- /minHeigth -->

</div><!-- /perguntas -->


<?php 

}
?>

</div>
<!--fim do div principal -->


<?php include 'rodape.php' ?>

