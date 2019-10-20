<?php include 'header_restrita.php'; ?>

<?php

if(($_SESSION['id_userSecao'] == 1581) || ($_SESSION['id_userSecao'] == 9)){
	$mostra_variaveis = false;
//echo "SEFIP_anexoIVeoutros: '". $_SESSION['SEFIP_anexoIVeoutros'] . "'" . "<BR>";
//echo "SEFIP_empreitada: '". $_SESSION['SEFIP_empreitada'] . "'" . "<BR>";
//echo "SEFIP_arrAnexos: '". $_SESSION['SEFIP_arrAnexos'] . "'" . "<BR>";

}



$arrAnexos = explode(',',$_SESSION['SEFIP_arrAnexos']);
//echo "arrAnexos: '". $arrAnexos[0] . "'" . "<BR>";


//verifica se a pessoa deve ser direcionada para a página de pagamento do gps como concomitante ou normal
//if ($_POST[hidConcomitante] == 'sim') {session_start(); $_SESSION["concomitante"] = "sim";}
//else {$_SESSION["concomitante"] = "nao";}


//pega dados da tabela empresa
$sql = "SELECT * FROM dados_da_empresa WHERE id='" . $_SESSION["id_empresaSecao"] . "' LIMIT 0, 1";
$resultado = mysql_query($sql) or die (mysql_error());
$linha_empresa=mysql_fetch_array($resultado);

//pega dados do socio principal
$sql2 = "SELECT nome FROM dados_do_responsavel WHERE id='" . $_SESSION["id_empresaSecao"] . "' AND responsavel='1' LIMIT 0, 1";
$resultado2 = mysql_query($sql2) or die (mysql_error());
$linha_responsavel=mysql_fetch_array($resultado2);

//pega dados de todos os sócios
// NIT = PIS
// NESTA MESMA QUERY VOU PEGAR OS DADOS DO AUTONOMO E DO SOCIO
//$sql2_1 = "SELECT * FROM dados_do_responsavel WHERE id='" . $_SESSION["id_userSecao"] ."' AND pro_labore > 0 ORDER BY nit";
/*$sql2_1 = "(SELECT 'socio' tipo_trabalhador, nit, data_admissao, data_de_nascimento, nome, codigo_cbo, pro_labore, 0 inss, 0 outra_fonte FROM dados_do_responsavel WHERE id=9 and  pro_labore > 0)
UNION
(SELECT 'autonomo' tipo_trabalhador, REPLACE(REPLACE(pis, '-', ''), '.', '') as nit, '        ' data_admissao, '        ' as data_de_nascimento, nome, cbo as codigo_cbo , valor_bruto as pro_labore, pag.INSS as inss, pag.outra_fonte outra_fonte FROM dados_autonomos aut, dados_pagamentos pag WHERE aut.id = pag.id_autonomo and aut.id_login=9 and  valor_liquido > 0 )
ORDER BY nit";
*/
/*
$sql2_1 = "
SELECT 
	case when pgto.id_autonomo <> 0 then 'autonomo' else 'socio' end tipo_trabalhador
	, case when pgto.id_autonomo <> 0 then REPLACE(REPLACE(aut.pis, '-', ''), '.', '') else socio.nit end nit
	, case when pgto.id_autonomo <> 0 then aut.nome else socio.nome end nome
	, case when pgto.id_autonomo <> 0 then '        ' else socio.data_admissao end data_admissao
	, case when pgto.id_autonomo <> 0 then '        ' else socio.data_de_nascimento end data_de_nascimento
	, case when pgto.id_autonomo <> 0 then aut.cbo else socio.codigo_cbo end codigo_cbo
	, pgto.valor_bruto pro_labore
	, pgto.INSS inss
	, pgto.outra_fonte outra_fonte
FROM 
	dados_pagamentos pgto
	left join dados_autonomos aut on pgto.id_autonomo = aut.id
	left join dados_do_responsavel socio on pgto.id_socio = socio.idSocio
WHERE 
	pgto.id_login='" . $_SESSION["id_userSecao"] ."'
	AND YEAR(data_pagto) = '".$_REQUEST['ano']."'
	AND MONTH(data_pagto) = '".$_REQUEST['mes']."'
ORDER BY 
	2
";*/

$sql2_1 = "
SELECT 
	case when pgto.id_autonomo <> 0 then 'autonomo' else 'socio' end tipo_trabalhador
	, case when pgto.id_autonomo <> 0 then REPLACE(REPLACE(aut.pis, '-', ''), '.', '') else socio.nit end nit
	, case when pgto.id_autonomo <> 0 then aut.nome else socio.nome end nome
	, case when pgto.id_autonomo <> 0 then '        ' else socio.data_admissao end data_admissao
	, case when pgto.id_autonomo <> 0 then '        ' else socio.data_de_nascimento end data_de_nascimento
	, case when pgto.id_autonomo <> 0 then aut.cbo else socio.codigo_cbo end codigo_cbo
	, sum(pgto.valor_bruto) pro_labore
	, sum(pgto.INSS) inss
	, sum(pgto.outra_fonte) outra_fonte
FROM 
	dados_pagamentos pgto
	left join dados_autonomos aut on pgto.id_autonomo = aut.id
	left join dados_do_responsavel socio on pgto.id_socio = socio.idSocio
WHERE 
	pgto.id_login='" . $_SESSION["id_empresaSecao"] ."'
	AND pgto.id_estagiario = '0'
	AND pgto.id_pj = '0'
	AND pgto.id_lucro = '0'
	AND YEAR(data_pagto) = '".$_REQUEST['ano']."'
	AND MONTH(data_pagto) = '".$_REQUEST['mes']."'
GROUP BY 1, 2, 3, 4, 5, 6
ORDER BY 2
";

//echo $sql2_1;

$resultado2_1 = mysql_query($sql2_1) or die (mysql_error());
$linhaAtual = 0;

// CHECANDO SE HÁ MOVIMENTO PARA O MÊS SELECIONADO
/*if(mysql_num_rows($resultado2_1) == 0){

	<script>
		alert('Não há movimento para o mês selecionado.\nNão foi possível gerar o arquivo da sefip.'); 
		history.go(-1);
	</script>
}*/

// PERCORRENDO OS DADOS DOS SÓCIOS PARA GUARDAR EM ARRAYS
while ($linha_socio=mysql_fetch_array($resultado2_1)){
		
	$linhaAtual = $linhaAtual + 1;
	
	$nitSocio[$linhaAtual] = $linha_socio['nit'];
	$data_admissaoSocio[$linhaAtual] = preg_replace('/\D/','',$linha_socio['data_admissao']);
	$nomeSocio[$linhaAtual] = $linha_socio['nome'];
	$data_de_nascimentoSocio[$linhaAtual] = $linha_socio['data_de_nascimento'];
//	$pro_laboreSocio[$linhaAtual] = str_pad(number_format(str_replace(",",".",str_replace(".","",$linha_socio['pro_labore'])),2,"",""), 15, "0", STR_PAD_LEFT);
	$pro_laboreSocio[$linhaAtual] = str_pad(str_replace(",",".",str_replace(".","",$linha_socio['pro_labore'])), 15, "0", STR_PAD_LEFT);
	$valor_descontadoSocio[$linhaAtual] = str_pad(str_replace(",",".",str_replace(".","",$linha_socio['inss'])), 15, "0", STR_PAD_LEFT);
	$outra_fonteSocio[$linhaAtual] = $linha_socio['outra_fonte'];
	$codigo_cboSocio[$linhaAtual] = $linha_socio['codigo_cbo'];
	$tipo_trabalhador[$linhaAtual] = $linha_socio['tipo_trabalhador'];
}


//pega cnae principal da empresa
$sql3 = "SELECT * FROM dados_da_empresa_codigos WHERE id='" . $_SESSION["id_empresaSecao"] . "' AND tipo='1' LIMIT 0, 1";
$resultado3 = mysql_query($sql3) or die (mysql_error());
$linha_codigo=mysql_fetch_array($resultado3);

//pega anexo do cnae principal
$sql4 = "SELECT * FROM cnae WHERE cnae='" . $linha_codigo['cnae'] . "' LIMIT 0, 1";
$resultado4 = mysql_query($sql4) or die (mysql_error());
$linha_cnae=mysql_fetch_array($resultado4);

//funcão de limpeza dos acentos e caracteres especiais
function limpeza($pegaNome,$pegaNumero = 0) {	

	$trans = array(
		"á" => "a", 
		"à" => "a",
		"ã" => "a",
		"â" => "a",
		"é" => "e",
		"ê" => "e",
		"í" => "i",
		"ó" => "o",
		"ô" => "o",
		"õ" => "o",
		"ú" => "u",
		"ü" => "u",
		"ç" => "c",
		"Á" => "A",
		"À" => "A",
		"Ã" => "A",
		"Â" => "A",
		"É" => "E",
		"Ê" => "E",
		"Í" => "I",
		"Ó" => "O",
		"Ô" => "O",
		"Õ" => "O",
		"Ú" => "U",
		"Ü" => "U",
		"Ç" => "C",
	);

	$pegaNome = ereg_replace("[^a-zA-Z0-9 ]", "", strtr($pegaNome, $trans));
	$pegaNome = str_replace("  ", " ", $pegaNome);
	$pegaNome = strtoupper($pegaNome);
	$quantidade = strlen($pegaNome);
	$retorno = $pegaNome;

    if ($quantidade < $pegaNumero) {
        for ($i=$quantidade; $i<$pegaNumero; $i++) {
            $retorno .= ' ';
        }
    }
    
	if ($quantidade > $pegaNumero) {
		
		$retorno = substr($retorno, 0, $pegaNumero);
	}	
	return $retorno;
}

/*
* DETERMINANDO AS VARIAVEIS 
* codigo de recolhimento, optante pelo simples, codigo GPS e rat 
* Estas dependem dos anexos das atividades selecionadas e se a empresa está ou não vinculada a uma empreitada
* conforme regras abaixo
* 
*
* Anexo IV + III sem empreitada
*   codigo de recolhimento: 115
*   código pagamento: 2003
*   rat: 0
*   Optante pelo Simples
* 
* 
* Anexo IV apenas, sem empreitada
*   codigo de recolhimento: 115
*   código pagamento: 2100
*   rat: 3,0
*   Não Optante
* 
* 
* Anexo IV + III COM empreitada
*   codigo de recolhimento: 150
*   código pagamento: 2003
*   rat: 0
*   Optante pelo Simples
* 
* 
* Anexo IV apenas, COM empreitada
*   codigo de recolhimento: 150
*   código pagamento: 2100
*   rat: 3,0
*   Não Optante
*
*/

$codigo_recolhimento = 115;
$simples = '2';
$codigo_gps = '2003';
$rat = '00';

if(
	in_array('IV',$arrAnexos)
	&& count($arrAnexos) > 1
){
	/*Anexo IV + III SEM empreitada*/
	if($_SESSION['SEFIP_empreitada'] == 'n'){ // não realiza empreitada
		$codigo_recolhimento = 115;
		$simples = '2';
		$codigo_gps = '2003';
		$rat = '00';
	/*Anexo IV + III COM empreitada*/
	}elseif($_SESSION['SEFIP_empreitada'] == 's'){ // não realiza empreitada
		$codigo_recolhimento = 150;
		$simples = '2';
		$codigo_gps = '2003';
		$rat = '00';
	}

}elseif(
	in_array('IV',$arrAnexos)
	&& count($arrAnexos) == 1
){
	/*Anexo IV SEM empreitada*/
	if($_SESSION['SEFIP_empreitada'] == 'n'){ // não realiza empreitada
		$codigo_recolhimento = 115;
		$simples = '1';
		$codigo_gps = '2100';
		$sql4 = "SELECT * FROM cnae_rat WHERE cnae='" . $linha_codigo['cnae'] . "' LIMIT 1";
		$resultado4 = mysql_query($sql4) or die (mysql_error());
		$linha_cnae_rat=mysql_fetch_array($resultado4);
		$rat = $linha_cnae_rat['rat'].'0';

	/*Anexo IV COM empreitada*/
	}elseif($_SESSION['SEFIP_empreitada'] == 's'){ // não realiza empreitada
		$codigo_recolhimento = 150;
		$simples = '1';
		$codigo_gps = '2100';
		$sql4 = "SELECT * FROM cnae_rat WHERE cnae='" . $linha_codigo['cnae'] . "' LIMIT 1";
		$resultado4 = mysql_query($sql4) or die (mysql_error());
		$linha_cnae_rat=mysql_fetch_array($resultado4);
		$rat = $linha_cnae_rat['rat'].'0';
	}
	
}

$codigo_outras_entidades = limpeza('',4);

// SE NÃO FOR OPTANTE
if($simples == '1'){
	$codigo_outras_entidades = '0000';
}


//**************** 1 TRATAMENTO INFORACACOES DO RESPONSAVEL

$tipo_de_registro = '00';
$brancos = limpeza('',51);
$tipo_de_remessa = 1;
$tipo_de_inscricao = 1;
$inscricao = limpeza($linha_empresa['cnpj'],14);
$nome = limpeza($linha_empresa['razao_social'],30);
//$nome = str_pad(" ",30,limpeza($linha_empresa[razao_social]),STR_PAD_LEFT);
$nome_pessoa = limpeza($linha_responsavel['nome'],20);
$logradouro =  limpeza($linha_empresa['endereco'],50);
$bairro =  limpeza($linha_empresa['bairro'],20);
$cep = limpeza($linha_empresa['cep'],8);
$cidade = limpeza($linha_empresa['cidade'],20);
$uf = limpeza($linha_empresa['estado'],2);
//$telefone = $linha_empresa[pref_telefone].$linha_empresa[telefone];
$telefone = preg_replace("/\W+/","",$linha_empresa['pref_telefone']).preg_replace("/\W+/","",$linha_empresa['telefone']);
$linhas_tel = strlen($telefone);
if ($linhas_tel < 12) {for ($i=$linhas_tel; $i<12; $i++) {$telefone = '0'.$telefone;}};
$end_internet = limpeza('',60);
$competencia = $_POST[ano].$_POST['mes'];
$codigo_recolhimento = $codigo_recolhimento;//$_POST[hidCod_Recolhimento];
$indicador_FGTS = '1';
$modalidade_arquivo = '1';
$recolhimento_FGTS = limpeza('',8);
$indicador_previdencia = "1";
//$indicador_previdencia = $_POST[indicador_previdencia];

// campo recolhimento previdencia
//if ($_POST[indicador_previdencia]== '1') {$recolhimento_previdencia = limpeza('',8);;}
if ($indicador_previdencia == '1') {$recolhimento_previdencia = limpeza('',8);}
else {$recolhimento_previdencia = limpeza($_POST['data_atraso'],8);}

$indice_recolhimento = limpeza('',7);
$tipo_inscricao_fornecedor = '1'; 
$inscricao_fornecedor = limpeza($linha_empresa['cnpj'],14);
$brancos2 = limpeza(' ',18);
$final_linha = '*';

// ************* 2 TRATAMENTO INFORMACAOES DA EMPRESA
$nome_empresa = limpeza($linha_empresa['razao_social'],40);
$tipo_de_registro_empresa = '10';
$tipo_inscricao_empresa = '1';
$inscricao_empresa = limpeza($linha_empresa['cnpj'],14);
$zeros36 = '000000000000000000000000000000000000';

// telefone empresa pego o mesmo do responsavel

// campo alteracao endereco
if ($_POST[mes] == '13'){$alteracao_endereco = 'n';} else {$alteracao_endereco = 's';};

//campo cnae
$cnae = limpeza($linha_codigo['cnae'],7);
switch($cnae){
	case '6201501':
		$cnae = '6201500';
	break;
	case '8020001':
		$cnae = '8020000';
	break;
	case '2013401':
	case '2013402':
		$cnae = '2013400';
	break;
	case '5239799':
		$cnae = '5239700';
	break;
	case '5812301':
	case '5812302':
		$cnae = '5812300';
	break;
	case '5822101':
	case '5822102':
		$cnae = '5822100';
	break;
	case '8020001':
	case '8020002':
		$cnae = '8020000';
	break;
	case '9412001':
	case '9412099':
		$cnae = '9412000';
	break;	
}



//campo alteracao cnae
if ($_POST[mes] == '13') {$alteracao_cnae = 'n';} else {$alteracao_cnae = 'a';}

// campo centralizacao
$centralizacao = '0';

$codigo_centralizacao = '0'; // um caso a se pensar

//campo fpas (duvida: devemos prever 2 fpas?) E as gfip de antes de 98?
$sql5 = "SELECT * FROM cnae_fpas WHERE cnae='" . $linha_codigo['cnae'] . "' LIMIT 0, 1";
$resultado5 = mysql_query($sql5) or die (mysql_error());
$linha_cnae_fpas = mysql_fetch_array($resultado5);
$fpas = $linha_cnae_fpas['fpas'];
if($fpas == 736){
$fpas = 515;	
}
$filantropia = limpeza('',5);
$salario_familia = '000000000000000';
$salario_maternidade = '000000000000000';
$contribuicao_desc_empregado = '000000000000000';
$indicador_neg_pos = '0';
$valor_devido_previdencia = '00000000000000';
$banco = limpeza('',3);
$agencia = limpeza('',4);
$conta_corrente = limpeza('',9);
$zeros45 = '000000000000000000000000000000000000000000000';
$brancos4 = '    ';

// ****************** 3 TRATAMENTO INFORMACOES ADICONAIS RECOLHIMENTO EMPRESA

$tipo_de_registro_adicional = '12';
// alguns campos faltam pois sao aproveitados dos anteirores
$deducao_sal_maternidade = '000000000000000';
$receita_esportivo = '000000000000000';
$origem_receita = ' ';
$comercializacao_producao = '000000000000000';
$comercializacao_producao2 = '000000000000000';
$outras_informacoes_processo = limpeza('',11);
$outras_informacoes_ano = limpeza('',4);
$outras_informacoes_vara = limpeza('',5);
$outras_informacoes_inicio = limpeza('',6);
$outras_informacoes_fim = limpeza('',6);
$compensacao = '000000000000000';
$compensacao_inicio = limpeza('',6);
$compensacao_fim = limpeza('',6);
$comp_anteriores = '000000000000000';
$comp_anteriores_outras = '000000000000000';
$comp_anteriores_comercializacao = '000000000000000';
$comp_anteriores_com_outras = '000000000000000';
$comp_anteriores_esportivo = '000000000000000';
$parcelamento_fgts_somatorio = '000000000000000';
$parcelamento_fgts_somatorio2 = '000000000000000';
$parcelamento_fgts_valor = '000000000000000';
$cooperativas = '000000000000000';
$brancos6 = limpeza('',6);

// ****************** 4. REGISTRO DO TOMADOR DE SERVIÇO/OBRA DE CONSTRUÇÃO CIVIL

if($codigo_recolhimento == '150') {
	$tipo_de_registro_tomador_servico = "20";
	$tipo_inscricao_tomador = '1';
	$inscricao_tomador_servico = limpeza($linha_empresa['cnpj'],14);
	$zeros21 = '000000000000000000000';
	$empresa_tomador_servico = limpeza($linha_empresa['razao_social'],40);
	$longadouro_tomador_servico = limpeza($linha_empresa['endereco'],50);
	$bairro_tomador_servico = limpeza($linha_empresa['bairro'],20);
	$cep_tomador_servico = limpeza($linha_empresa['cep'],8);
	$cidade_tomador_servico = limpeza($linha_empresa['cidade'],20);
	$uf_tomador_servico = limpeza($linha_empresa['estado'],2);
	$codigo_pagamento_gps_tomador_servico = limpeza('',4);
	$salario_familia_tomador_servico = '000000000000000';
	$contrib_empregado_tomador_servico = '000000000000000';
	$indicador_de_valor_tomador_servico = '0';
	$valor_devido_a_previdencia_tomador_servico = '00000000000000';
	$valor_retencao_tomador_servico = '000000000000000';
	$valor_das_faturas_tomador_servico = '000000000000000';
	$zeros45 = '000000000000000000000000000000000000000000000';
	$brancos42 = limpeza(' ',42);
}


if($codigo_recolhimento == '155') {
	$tipo_de_registro_tomador_servico = "20";
	$inscricao_tomador_servico = limpeza($linha_empresa['cnpj'],14);
	$zeros21 = '000000000000000000000';
	$empresa_tomador_servico = limpeza($linha_empresa['razao_social'],40);
	$longadouro_tomador_servico = limpeza($linha_empresa['endereco'],50);
	$bairro_tomador_servico = limpeza($linha_empresa['bairro'],20);
	$cep_tomador_servico = limpeza($linha_empresa['cep'],8);
	$cidade_tomador_servico = limpeza($linha_empresa['cidade'],20);
	$uf_tomador_servico = limpeza($linha_empresa['estado'],2);
	$codigo_pagamento_gps_tomador_servico = '2003';
	$salario_familia_tomador_servico = '000000000000000';
	$contrib_empregado_tomador_servico = '000000000000000';
	$indicador_de_valor_tomador_servico = '0';
	$valor_devido_a_previdencia_tomador_servico = '00000000000000';
	$valor_retencao_tomador_servico = '000000000000000';
	$valor_das_faturas_tomador_servico = '000000000000000';
	$zeros45 = '000000000000000000000000000000000000000000000';
	$brancos42 = limpeza(' ',42);
}



// ****************** 5. TRATAMENTO REGISTRO DOS TRABALHADORES/SOCIOS

$tipo_de_registro_trabalhador = '30';
// alguns campos faltam pois sao aproveitados dos anteirores
// outros campos são pegos do loop sql no começo da página


if($codigo_recolhimento == '150') {
	$tipo_inscricao_tomador = '1';
	$inscricao_tomador = limpeza($linha_empresa['cnpj'],14);
} else {
	$tipo_inscricao_tomador = ' ';
	$inscricao_tomador = limpeza('',14);
}
$categoria_trabalhador = '11';
$matricula_empregado = limpeza('',11);
$ctps = limpeza('',7);
$ctps_serie = limpeza('',5);
$data_opcao = limpeza('',8);
$remuneracao13 = '000000000000000';
$classe_contribuicao = '  ';
$ocorrencia = '  ';
$valor_descontado = '000000000000000';
$remuneracao_base = '000000000000000';
$base_claculo_13_comp = '000000000000000';
$base_claculo_13_13 = '000000000000000';
$brancos98 = limpeza('',98);


// ****************** 6. TRATAMENTO REGISTRO FINALIZADOR

$tipo_de_registro_totalizador = '90';
$marca_final_registro = '999999999999999999999999999999999999999999999999999';
$brancos306 = limpeza(' ',306);


//************************************** FORMATACAO DAS VARIAVEIS DO ARQUIVO SEFIP ***************


//**************** 1 INFORACACOES DO RESPONSAVEL
$infoResp = 
$tipo_de_registro.
$brancos.
$tipo_de_remessa.
$tipo_de_inscricao.
$inscricao.
$nome.
$nome_pessoa.
$logradouro.
$bairro.
$cep.
$cidade.
$uf.
$telefone.
$end_internet.
$competencia.
$codigo_recolhimento.
$indicador_FGTS.
$modalidade_arquivo.
$recolhimento_FGTS.
$indicador_previdencia.
$recolhimento_previdencia.
$indice_recolhimento. 
$tipo_inscricao_fornecedor.
$inscricao_fornecedor.
$brancos2.
$final_linha.
"\r\n";
$variaveis .= "tipo_de_registro: $tipo_de_registro" . "<BR>";
$variaveis .= "brancos: $brancos" . "<BR>";
$variaveis .= "tipo_de_remessa: $tipo_de_remessa" . "<BR>";
$variaveis .= "tipo_de_inscricao: $tipo_de_inscricao" . "<BR>";
$variaveis .= "inscricao: $inscricao" . "<BR>";
$variaveis .= "nome: $nome" . "<BR>";
$variaveis .= "nome_pessoa: $nome_pessoa" . "<BR>";
$variaveis .= "logradouro: $logradouro" . "<BR>";
$variaveis .= "bairro: $bairro" . "<BR>";
$variaveis .= "cep: $cep" . "<BR>";
$variaveis .= "cidade: $cidade" . "<BR>";
$variaveis .= "uf: $uf" . "<BR>";
$variaveis .= "telefone: $telefone" . "<BR>";
$variaveis .= "end_internet: $end_internet" . "<BR>";
$variaveis .= "competencia: $competencia" . "<BR>";
$variaveis .= "<span style=\"color:#C00\">codigo_recolhimento:  $codigo_recolhimento</span>" . "<BR>";
$variaveis .= "indicador_FGTS: $indicador_FGTS" . "<BR>";
$variaveis .= "modalidade_arquivo: $modalidade_arquivo" . "<BR>";
$variaveis .= "recolhimento_FGTS: $recolhimento_FGTS" . "<BR>";
$variaveis .= "indicador_previdencia: $indicador_previdencia" . "<BR>";
$variaveis .= "recolhimento_previdencia: $recolhimento_previdencia" . "<BR>";
$variaveis .= "indice_recolhimento: $indice_recolhimento" . "<BR>";
$variaveis .= "tipo_inscricao_fornecedor: $tipo_inscricao_fornecedor" . "<BR>";
$variaveis .= "inscricao_fornecedor: $inscricao_fornecedor" . "<BR>";
$variaveis .= "brancos2: $brancos2" . "<BR>";
$variaveis .= "final_linha: $final_linha" . "<BR>";

// ************* 2 INFORMACAOES DA EMPRESA

$infoEmp = 
$tipo_de_registro_empresa.
$tipo_inscricao_empresa.
$inscricao.
$zeros36.
$nome_empresa.
$logradouro.
$bairro.
$cep.
$cidade.
$uf.
$telefone.
$alteracao_endereco.
$cnae.
$alteracao_cnae.
$rat.
$centralizacao.
$simples.
$fpas.
$codigo_outras_entidades.
$codigo_gps.
$filantropia.
$salario_familia.
$salario_maternidade.
$contribuicao_desc_empregado.
$indicador_neg_pos.
$valor_devido_previdencia.
$banco.
$agencia.
$conta_corrente.
$zeros45.
$brancos4.
$final_linha.
"\r\n";
$variaveis .= "<BR>";
$variaveis .= "tipo_de_registro_empresa: $tipo_de_registro_empresa" . "<BR>";
$variaveis .= "tipo_inscricao_empresa: $tipo_inscricao_empresa" . "<BR>";
$variaveis .= "inscricao_empresa: $inscricao_empresa" . "<BR>";
$variaveis .= "zeros36: $zeros36" . "<BR>";
$variaveis .= "nome_empresa: $nome_empresa" . "<BR>";
$variaveis .= "logradouro: $logradouro" . "<BR>";
$variaveis .= "bairro: $bairro" . "<BR>";
$variaveis .= "cep: $cep" . "<BR>";
$variaveis .= "cidade: $cidade" . "<BR>";
$variaveis .= "uf: $uf" . "<BR>";
$variaveis .= "telefone: $telefone" . "<BR>";
$variaveis .= "alteracao_endereco: $alteracao_endereco" . "<BR>";
$variaveis .= "cnae: $cnae" . "<BR>";
$variaveis .= "alteracao_cnae: $alteracao_cnae" . "<BR>";
$variaveis .= "<span style=\"color:#C00\">rat: $rat</span>" . "<BR>";
$variaveis .= "centralizacao: $centralizacao" . "<BR>";
$variaveis .= "<span style=\"color:#C00\">simples: $simples</span>" . "<BR>";
$variaveis .= "fpas: $fpas" . "<BR>";
$variaveis .= "<span style=\"color:#C00\">codigo_outras_entidades: $codigo_outras_entidades</span>" . "<BR>";
$variaveis .= "<span style=\"color:#C00\">codigo_gps: $codigo_gps</span>" . "<BR>";
$variaveis .= "filantropia: $filantropia" . "<BR>";
$variaveis .= "salario_familia: $salario_familia" . "<BR>";
$variaveis .= "salario_maternidade: $salario_maternidade" . "<BR>";
$variaveis .= "contribuicao_desc_empregado: $contribuicao_desc_empregado" . "<BR>";
$variaveis .= "indicador_neg_pos: $indicador_neg_pos" . "<BR>";
$variaveis .= "valor_devido_previdencia: $valor_devido_previdencia" . "<BR>";
$variaveis .= "banco: $banco" . "<BR>";
$variaveis .= "agencia: $agencia" . "<BR>";
$variaveis .= "conta_corrente: $conta_corrente" . "<BR>";
$variaveis .= "zeros45: $zeros45" . "<BR>";
$variaveis .= "brancos4: $brancos4" . "<BR>";
$variaveis .= "final_linha: $final_linha" . "<BR>";


// ****************** 3 INFORMACOES ADICONAIS RECOLHIMENTO EMPRESA

$infoAdicional = 
$tipo_de_registro_adicional.
$tipo_inscricao_empresa.
$inscricao_empresa.
$zeros36.
$deducao_sal_maternidade.
$receita_esportivo.
$origem_receita.
$comercializacao_producao.
$comercializacao_producao2.
$outras_informacoes_processo.
$outras_informacoes_ano.
$outras_informacoes_vara.
$outras_informacoes_inicio.
$outras_informacoes_fim.
$compensacao.
$compensacao_inicio.
$compensacao_fim.
$comp_anteriores.
$comp_anteriores_outras.
$comp_anteriores_comercializacao.
$comp_anteriores_com_outras.
$comp_anteriores_esportivo.
$parcelamento_fgts_somatorio.
$parcelamento_fgts_somatorio2.
$parcelamento_fgts_valor.
$cooperativas.
$zeros45.
$brancos6.
$final_linha.
"\r\n";
$variaveis .= "<BR>";
$variaveis .= "tipo_de_registro_adicional: $tipo_de_registro_adicional" . "<BR>";
$variaveis .= "tipo_inscricao_empresa: $tipo_inscricao_empresa" . "<BR>";
$variaveis .= "inscricao_empresa: $inscricao_empresa" . "<BR>";
$variaveis .= "zeros36: $zeros36" . "<BR>";
$variaveis .= "deducao_sal_maternidade: $deducao_sal_maternidade" . "<BR>";
$variaveis .= "receita_esportivo: $receita_esportivo" . "<BR>";
$variaveis .= "origem_receita: $origem_receita" . "<BR>";
$variaveis .= "comercializacao_producao: $comercializacao_producao" . "<BR>";
$variaveis .= "comercializacao_producao2: $comercializacao_producao2" . "<BR>";
$variaveis .= "outras_informacoes_processo: $outras_informacoes_processo" . "<BR>";
$variaveis .= "outras_informacoes_ano: $outras_informacoes_ano" . "<BR>";
$variaveis .= "outras_informacoes_vara: $outras_informacoes_vara" . "<BR>";
$variaveis .= "outras_informacoes_inicio: $outras_informacoes_inicio" . "<BR>";
$variaveis .= "outras_informacoes_fim: $outras_informacoes_fim" . "<BR>";
$variaveis .= "compensacao: $compensacao" . "<BR>";
$variaveis .= "compensacao_inicio: $compensacao_inicio" . "<BR>";
$variaveis .= "compensacao_fim: $compensacao_fim" . "<BR>";
$variaveis .= "comp_anteriores: $comp_anteriores" . "<BR>";
$variaveis .= "comp_anteriores_outras: $comp_anteriores_outras" . "<BR>";
$variaveis .= "comp_anteriores_comercializacao: $comp_anteriores_comercializacao" . "<BR>";
$variaveis .= "comp_anteriores_com_outras: $comp_anteriores_com_outras" . "<BR>";
$variaveis .= "comp_anteriores_esportivo: $comp_anteriores_esportivo" . "<BR>";
$variaveis .= "parcelamento_fgts_somatorio: $parcelamento_fgts_somatorio" . "<BR>";
$variaveis .= "parcelamento_fgts_somatorio2: $parcelamento_fgts_somatorio2" . "<BR>";
$variaveis .= "parcelamento_fgts_valor: $parcelamento_fgts_valor" . "<BR>";
$variaveis .= "cooperativas: $cooperativas" . "<BR>";
$variaveis .= "zeros45: $zeros45" . "<BR>";
$variaveis .= "brancos6: $brancos6" . "<BR>";
$variaveis .= "final_linha: $final_linha" . "<BR>";


// ****************** 4. REGISTRO DO TOMADOR DE SERVIÇO/OBRA DE CONSTRUÇÃO CIVIL

if($codigo_recolhimento == '150') 
    {
	$infoTomador = 
	$tipo_de_registro_tomador_servico.
	$tipo_inscricao_empresa.
	$inscricao.
	$tipo_inscricao_tomador.
	$inscricao_tomador_servico.
	$zeros21.
	$empresa_tomador_servico.
	$longadouro_tomador_servico.
	$bairro_tomador_servico.
	$cep_tomador_servico.
	$cidade_tomador_servico.
	$uf_tomador_servico.
	$codigo_pagamento_gps_tomador_servico.
	$salario_familia_tomador_servico.
	$contrib_empregado_tomador_servico.
	$indicador_de_valor_tomador_servico.
	$valor_devido_a_previdencia_tomador_servico.
	$valor_retencao_tomador_servico.
	$valor_das_faturas_tomador_servico.
	$zeros45.
	$brancos42.
	$final_linha.
	"\r\n";

$variaveis .= "<BR>";
$variaveis .= "tipo_de_registro_tomador_servico: $tipo_de_registro_tomador_servico" . "<BR>";
$variaveis .= "tipo_inscricao_empresa: $tipo_inscricao_empresa" . "<BR>";
$variaveis .= "inscricao: $inscricao" . "<BR>";
$variaveis .= "tipo_inscricao_tomador: $tipo_inscricao_tomador" . "<BR>";
$variaveis .= "inscricao_tomador_servico: $inscricao_tomador_servico" . "<BR>";
$variaveis .= "zeros21: $zeros21" . "<BR>";
$variaveis .= "empresa_tomador_servico: $empresa_tomador_servico" . "<BR>";
$variaveis .= "longadouro_tomador_servico: $longadouro_tomador_servico" . "<BR>";
$variaveis .= "bairro_tomador_servico: $bairro_tomador_servico" . "<BR>";
$variaveis .= "cep_tomador_servico: $cep_tomador_servico" . "<BR>";
$variaveis .= "cidade_tomador_servico: $cidade_tomador_servico" . "<BR>";
$variaveis .= "uf_tomador_servico: $uf_tomador_servico" . "<BR>";
$variaveis .= "codigo_pagamento_gps_tomador_servico: $codigo_pagamento_gps_tomador_servico" . "<BR>";
$variaveis .= "salario_familia_tomador_servico: $salario_familia_tomador_servico" . "<BR>";
$variaveis .= "contrib_empregado_tomador_servico: $contrib_empregado_tomador_servico" . "<BR>";
$variaveis .= "indicador_de_valor_tomador_servico: $indicador_de_valor_tomador_servico" . "<BR>";
$variaveis .= "valor_devido_a_previdencia_tomador_servico: $valor_devido_a_previdencia_tomador_servico" . "<BR>";
$variaveis .= "valor_retencao_tomador_servico: $valor_retencao_tomador_servico" . "<BR>";
$variaveis .= "valor_das_faturas_tomador_servico: $valor_das_faturas_tomador_servico" . "<BR>";
$variaveis .= "zeros45: $zeros45" . "<BR>";
$variaveis .= "brancos42: $brancos42" . "<BR>";
$variaveis .= "final_linha: $final_linha" . "<BR>";

}

else {$infoTomador = '';}


// ****************** 5. REGISTRO DOS TRABALHADORES/SOCIOS
// AQUI 

$socios = '';
for($i=1; $i<=$linhaAtual; $i++) {
	$ocorrencia = '  ';
	$valor_descontado = '000000000000000';
	if($tipo_trabalhador[$i] == 'socio'){
		$categoria_trabalhador = '11';
	}else{
		$categoria_trabalhador = '13';
	}
	if($outra_fonteSocio[$i] == '1'){
		$valor_descontado = $valor_descontadoSocio[$i];
		// OCORRENCIA 05 - houve outra fonte pagadora
		$ocorrencia = '05';
	}
	$infoSocio[$i] = 
	$tipo_de_registro_trabalhador.
	$tipo_inscricao_empresa.
	$inscricao_empresa.
	$tipo_inscricao_tomador.
	$inscricao_tomador.
	limpeza($nitSocio[$i],11).
	limpeza($data_admissaoSocio[$i],8).
	$categoria_trabalhador.
	limpeza($nomeSocio[$i],70).
	$matricula_empregado.
	$ctps.
	$ctps_serie.
	$data_opcao.
	limpeza('',8).
	'0'.substr(limpeza($codigo_cboSocio[$i],7),0,4).
	$pro_laboreSocio[$i].
	$remuneracao13.
	$classe_contribuicao.
	$ocorrencia.
	$valor_descontado.
	$remuneracao_base.
	$base_claculo_13_comp.
	$base_claculo_13_13.
	$brancos98.
	$final_linha.
	"\r\n";
	$socios .= $infoSocio[$i];
	
$variaveis .= "<BR>";
$variaveis .= "tipo_de_registro_trabalhador: $tipo_de_registro_trabalhador" . "<BR>";
$variaveis .= "tipo_inscricao_empresa: $tipo_inscricao_empresa" . "<BR>";
$variaveis .= "inscricao_empresa: $inscricao_empresa" . "<BR>";
$variaveis .= "tipo_inscricao_tomador: $tipo_inscricao_tomador" . "<BR>";
$variaveis .= "inscricao_tomador: $inscricao_tomador" . "<BR>";
$variaveis .= "nitSocio[$i]: $nitSocio[$i]" . "<BR>";
$variaveis .= "data_admissaoSocio[$i]: '".$data_admissaoSocio[$i]."'" . "<BR>";
$variaveis .= "categoria_trabalhador: $categoria_trabalhador" . "<BR>";
$variaveis .= "nomeSocio[$i]: $nomeSocio[$i]" . "<BR>";
$variaveis .= "matricula_empregado: $matricula_empregado" . "<BR>";
$variaveis .= "ctps: $ctps" . "<BR>";
$variaveis .= "ctps_serie: $ctps_serie" . "<BR>";
$variaveis .= "data_opcao: $data_opcao" . "<BR>";
$variaveis .= "codigo_cboSocio[$i]: $codigo_cboSocio[$i]" . "<BR>";
$variaveis .= "pro_laboreSocio[$i]: $pro_laboreSocio[$i]" . "<BR>";
$variaveis .= "remuneracao13: $remuneracao13" . "<BR>";
$variaveis .= "classe_contribuicao: $classe_contribuicao" . "<BR>";
$variaveis .= "ocorrencia: $ocorrencia" . "<BR>";
$variaveis .= "valor_descontado: $valor_descontado" . "<BR>";
$variaveis .= "remuneracao_base: $remuneracao_base" . "<BR>";
$variaveis .= "base_claculo_13_comp: $base_claculo_13_comp" . "<BR>";
$variaveis .= "base_claculo_13_13: $base_claculo_13_13" . "<BR>";
$variaveis .= "brancos98: $brancos98" . "<BR>";
$variaveis .= "final_linha: $final_linha" . "<BR>";
	
}




// ****************** 6. REGISTRO TOTALIZADOR

$totalizador = 
$tipo_de_registro_totalizador.
$marca_final_registro.
$brancos306.
$final_linha.
"\r\n";

$variaveis .= "<BR>";
$variaveis .= "tipo_de_registro_totalizador: $tipo_de_registro_totalizador" . "<BR>";
$variaveis .= "marca_final_registro: $marca_final_registro" . "<BR>";
$variaveis .= "brancos306: $brancos306" . "<BR>";
$variaveis .= "final_linha: $final_linha" . "<BR>";


$folha = $infoResp.$infoEmp.$infoAdicional.$infoTomador.$socios.$totalizador;
// MONTANDO O DIRETORIO PARA SALVAR O ARQUIVO sefip.re DO USUARIO CASO NÃO EXISTA
$nome_pasta = './sefip/' . str_pad($_SESSION["id_empresaSecao"], 6, "0", STR_PAD_LEFT);
// VERIFICANDO SE A PASTA JÁ EXISTE
if(!is_dir($nome_pasta)){
	mkdir($nome_pasta, 0777, true);
}
/*
// VERIFICANDO SE O ARQUIVO JÁ EXISTE
if(file_exists($nome_pasta . '/sefip.re')){
	unlink($nome_pasta . '/sefip.re');
}
*/
// SALVANDO O ARQUIVO
file_put_contents($nome_pasta . '/sefip.re', $folha);
?>

 

<div class="principal minHeight">

  <span class="titulo">Impostos e Obrigações</span><br /><br />

  <div style="margin-bottom:20px" class="tituloVermelho">Envio da Gfip</div>

<div style="width:780px;">
Confira na tabela abaixo os pagamentos de pró-labores, salários e remunerações a autônomos para o período selecionado. <br />
Certifique-se de que estejam corretos, caso contrário, vá à aba pagamentos e corrija. Uma vez conferido os valores, você está pronto para baixar a folha de pagamento, que será usada no processo de geração da Gfip.<br />
<br />
A folha de pagmento será salva em seu micro com o nome de <strong>sefip.re</strong>. Você não conseguirá visualizar seu conteúdo. Trata-se de um arquivo codificado para leitura nos sistemas da Caixa Econômica Federal. Guarde-o em um local de fácil acesso (na sua área de trabalho, por exemplo), pois você precisará dele logo em seguida.
<br />
<br />
</div>
<?
	// MONTAGEM DA LISTAGEM DOS AUTONOMOS
	
	$sql = "SELECT 
				pgto.id_pagto
				, pgto.valor_bruto
				, pgto.INSS
				, pgto.IR
				, pgto.ISS
				, pgto.valor_liquido
				, pgto.data_pagto  
				, case when pgto.id_autonomo <> 0 then 'autonomo' else 'socio' end tipo
				, case when pgto.id_autonomo <> 0 then aut.id else socio.idSocio end id
				, case when pgto.id_autonomo <> 0 then aut.nome else socio.nome end nome
				, case when pgto.id_autonomo <> 0 then aut.cpf else socio.cpf end cpf
			FROM 
				dados_pagamentos pgto
				left join dados_autonomos aut on pgto.id_autonomo = aut.id
				left join dados_do_responsavel socio on pgto.id_socio = socio.idSocio
			WHERE 
				pgto.id_login='" . $_SESSION["id_empresaSecao"] . "'
				AND pgto.id_estagiario = '0'
				AND pgto.id_pj = '0'
				AND pgto.id_lucro = '0'
				AND YEAR(data_pagto) = '".$_REQUEST['ano']."'
				AND MONTH(data_pagto) = '".$_REQUEST['mes']."'";


	$resOrdem = " ORDER BY data_pagto DESC";
//	echo $sql . $resColuna . $resDatas. $resOrdem;
	
	$resultado = mysql_query($sql . $resOrdem)
	or die (mysql_error());
	if(mysql_num_rows($resultado) > 0){
?>

		<table width="100%" cellpadding="5">
        	<tr>
            	<th width="23%">Nome</th>
            	<th width="11%">Categoria</th>
            	<th width="11%">Data Pagto.</th>
            	<th width="11%">Valor Bruto</th>
            	<th width="11%">INSS</th>
            	<th width="11%">IR</th>
            	<th width="11%">ISS</th>
            	<th width="11%">Valor Líquido</th>
            </tr>
<?
		// TRAZENDO OS DADOS PARA MONTAR TABELA DE AUTONOMOS
		while($linha=mysql_fetch_array($resultado)){
			$idPagto 	= $linha["id_pagto"];
			$id 	= $linha["id"];
			$nome 	= $linha["nome"];
			$tipo 	= $linha["tipo"];
			$cpf 	= $linha["cpf"];
			
			$valor_bruto 	= $linha["valor_bruto"];
			$total_valor_bruto += $valor_bruto;
			
			$INSS		 	= $linha["INSS"];
			$total_INSS += $INSS;
			
			$IR			 	= $linha["IR"];
			$total_IR += $IR;
			
			$ISS		 	= $linha["ISS"];
			$total_ISS += $ISS;
			
			$valor_liquido 	= $linha["valor_liquido"];
			$total_valor_liquido += $valor_liquido;
			
			$data_pagto 	= date("d/m/Y",strtotime($linha['data_pagto']));
?>
            <tr>
                <td class="td_calendario"><?=$nome?></td>
                <td class="td_calendario"><?=$tipo?></td>
                <td class="td_calendario"><?=$data_pagto?></td>
                <td class="td_calendario" align="right"><?=number_format($valor_bruto,2,',','.')?></td>
                <td class="td_calendario" align="right"><?=number_format($INSS,2,',','.')?></td>
                <td class="td_calendario" align="right"><?=number_format($IR,2,',','.')?></td>
                <td class="td_calendario" align="right"><?=number_format($ISS,2,',','.')?></td>
                <td class="td_calendario" align="right"><?=number_format($valor_liquido,2,',','.')?></td>
            </tr>
<?	
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
		</table>
      

<?
	}

	if($mostra_variaveis == true){
    
		echo $variaveis;
    
	}
?>


            <div class="caixa_bt_opcoes_quadro" style="padding: 20px 0;">
            	<input type="button" class="link_download" value="Baixar folha de pagamento" style="margin-right: 10px;" />
	            <input type="button" id="btContinuar" value="Continuar">
            </div>

</div>

<script>

var clickLink = false;
			
$(document).ready(function(e) {

	$(".link_download").click(function(){
		clickLink = true;
		location.href="sefip_folha_downloadRE.php";
	});
	
	$("#btContinuar").click(function(){
		if(clickLink == true){
			
			location.href="<?=$_SESSION['SEFIP_pgdestino']?>";
			
		}else{
			if(confirm('Você ainda não fez o download do arquivo Sefip. Deseja prosseguir mesmo assim?')){
				location.href="<?=$_SESSION['SEFIP_pgdestino']?>";
			}else{
//				alert('Para prosseguir, faça o download do arquivo da sefip gerado.');
				$(".link_download").focus();
//				location.href="sefip_folha_downloadRE.php";
			}
		}
	});

});

</script>


<?php  include 'rodape.php'; ?>