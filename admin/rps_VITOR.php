<?php 
include '../conect.php';

include '../session.php';

include 'check_login.php';

?>

<?
//printf("%%s = '%s'\n", "º");
// 1248, 3919, 4005
$titulo_vermelho = "Arquivos de RPS";

function checa_caracteres_especiais($string){
	$qtd = 0;
	if(preg_match_all('/[ªº]/i',$string,$arrResultado)){
		foreach($arrResultado[0] as $campo=>$valor){
			if(preg_match('/[ªº]/i',utf8_encode($valor))){
				$qtd++;
			}
		}	
	}
	return $qtd;
}


if($_GET['undo'] != ''){

	if($rsUltimoArquivo = mysql_fetch_array(mysql_query("SELECT * FROM rps WHERE id = " . $_GET['undo'] . " LIMIT 1"))){

		// MONTANDO O DIRETORIO PARA SALVAR O ARQUIVO sefip.re DO USUARIO CASO NÃO EXISTA
		$nome_pasta = '../RPS/COBRANCA';
	
		$nome_arquivo = $rsUltimoArquivo['nome'];
		
		unlink($nome_pasta . "/" . $nome_arquivo);
		
		$arrIdsRelatorios = json_decode($rsUltimoArquivo['ids_relatorios_cobranca']);
		
		foreach($arrIdsRelatorios as $valor){
			mysql_query("UPDATE relatorio_cobranca SET emissao_NF = 0 WHERE idRelatorio = " . $valor);
		}
		
		mysql_query("DELETE FROM rps WHERE id = " . $_GET['undo'] . " LIMIT 1");
				
	}
	
	header('location: rps.php');
		
}

if($_GET['toggleRPS'] != ''){
	$url = str_replace("?toggleRPS","#",str_replace("&toggleRPS","#",$_SERVER['REQUEST_URI']));
	
	$arrURI = explode('#',$url); // TIRANTO O ID DO PAGAMENTO DA URL
	
	mysql_query('UPDATE rps SET processado = case when processado = 0 then 1 else 0 end WHERE id = ' . $_GET['toggleRPS'] . ' LIMIT 1');

	header('location: rps.php');

}


	
			
if(isset($_GET['processar'])){


	// ############################# GERAÇÃO DO RPS #################################

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
	"Ç" => "C"
	,"º" => "."
	,"ª" => "."
	,"´" => " "
	,"'" => " "
	,"`" => " "
	,"-" => "-"
	,"-" => "-"
);

$tipos = array(
	"avenida" => "av", 
	"estrada" => "est",
	"praca" => "prc",
	"alameda" => "alm",
	"travessa" => "trv",
	"rodovia" => "rod"
);

if($_GET['ids'] != ''){
//	$arrIDS = explode(',',$_GET['ids']);
	$excluirIDs = $_GET['ids'];
//
//	//mysql_query("TRUNCATE TABLE rps_excluir_ids");
//	
//	foreach($arrIDS as $id){
//		mysql_query("INSERT INTO rps_excluir_ids SET id = " . trim($id));
//	}

}

/* campo final de linha */$fim_linha = chr(10);// . chr(10);//"\n";

/* HEADER */

/* campo 1 */$tipo_registro = "1";
/* campo 2 */$versao_arquivo = "2";
/* campo 3 */$inscricao_municipal_prestador = "22544356";
/* campo 4 */$data_inicio_periodo = date("Y") . date("m") . date("d");
/* campo 5 */$data_fim_periodo = date("Y") . date("m") . date("d");

$header = str_pad($tipo_registro,1," ",STR_PAD_RIGHT);
$header .= str_pad($versao_arquivo,3,"0",STR_PAD_LEFT);
$header .= str_pad($inscricao_municipal_prestador,8,"0",STR_PAD_LEFT);
$header .= str_pad($data_inicio_periodo,8,"0",STR_PAD_LEFT);
$header .= str_pad($data_inicio_periodo,8,"0",STR_PAD_LEFT);
$header .= $fim_linha;

/* /HEADER */


/* DETALHES */
$detalhes = "";
//PEGANDO DADOS DA EMPRESA
/*
$sql = "SELECT 
cob.idRelatorio
, cob.valor_pago
, emp.razao_social
, emp.cnpj
, emp.inscricao_no_ccm
, emp.inscricao_estadual
, emp.endereco
, emp.bairro
, emp.cidade
, emp.estado
, emp.cep
, usu.email
FROM 
	dados_da_empresa emp
INNER JOIN 
	relatorio_cobranca cob ON emp.id = cob.id
INNER JOIN 
	login usu ON emp.id = usu.id
WHERE
	cob.resultado_acao in ('1.2','2.1')
	AND cob.emissao_NF = 0
	" . ($excluirIDs != '' ? "AND emp.id NOT IN (".$excluirIDs.")" : "") . "
";
*/
$sql = "SELECT 
cob.idRelatorio
, cob.valor_pago
, dCob.sacado
, dCob.documento
, '' inscricao_no_ccm
, '' inscricao_estadual
, dCob.endereco
, dCob.bairro
, dCob.cidade
, dCob.uf
, dCob.cep
, dCob.tipo
, usu.email
FROM 
	dados_cobranca dCob
INNER JOIN 
	relatorio_cobranca cob ON dCob.id = cob.id
INNER JOIN 
	login usu ON dCob.id = usu.id
WHERE
	cob.resultado_acao in ('1.2','2.1')
	AND cob.emissao_NF = 0
	" . ($excluirIDs != '' ? "AND dCob.id NOT IN (".$excluirIDs.")" : "") . "
";

// 	AND emp.id NOT IN (SELECT id FROM rps_excluir_ids)
//	cob.data = '".date('Y-m-d')."'
//	AND cob.resultado_acao in ('1.2','2.1')


//	emp.id = '9'
//	LIMIT 0,1

$resultado = mysql_query($sql) or die (mysql_error());

if(mysql_num_rows($resultado) <= 0){
	
	$_SESSION['erro_RPS'] = "Não há dados para geração do RPS!";
	header('location:rps.php');
	exit;
	
}

$conta_linhas = 0;


// CHECANDO A NUMERACAO DOS RPS
$rsNumeros = mysql_fetch_array(mysql_query("SELECT MAX(fim_numeracao_rps) inicioProximo FROM rps "));//WHERE DATE(data_geracao) < '".date('Y-m-d')."'"));

$numero_inicial = $rsNumeros['inicioProximo'];

$numero_RPS = $numero_inicial;

$ids_relatorios_cobranca = array();

while($dados_cobranca = mysql_fetch_array($resultado)){

	array_push($ids_relatorios_cobranca,$dados_cobranca['idRelatorio']);

/* campo 1 */$tipo_registro = "6";
/* campo 2 */$tipo_RPS = "RPS";
/* campo 3 */$serie_RPS = "";
/* campo 4 */$numero_RPS++;
/* campo 5 */$data_emissao_RPS = date("Y") . date("m") . date("d");
/* campo 6 */$situacao_RPS = "T";//"I"; 
/* 
T - Operação normal
I - Operação isenta ou não tributável, executadas no Município de São Paulo
F - Operação isenta ou não tributável pelo Município de São Paulo, executada em outro município
C - Cancelado
E - Extraviado
J - ISS Suspenso por decisão judicial (neste caso, informar no campo Discriminação dos Serviços , o número do processo judicial na 1º instância
*/
/* campo 7 */$valor_servicos = number_format($dados_cobranca['valor_pago'],2,'','');
/* campo 8 */$valor_deducoes = "";
/* campo 9 */$codigo_servico_prestado = "2798";
/* campo 10 */$aliquota = ($situacao_RPS != "T" ? number_format("0.00",2,'','') : number_format("2.00",2,'',''));
/* campo 11 */$iss_retido = "2";
/*
1 - ISS retido pelo Tomador
2 - NF sem ISS retido
3 - ISS retido pelo intermediario
*/
/* campo 12 */$indicador_doc_tomador = ($dados_cobranca['tipo'] == 'PJ' ? "2" : "1");
/*
1 - CPF
2 - CNPJ
3 - CPF não informado
*/
/* campo 13 */$doc_tomador = preg_replace('/[^0-9]/','',$dados_cobranca['documento']);
/* campo 14 */$inscricao_municipal_tomador = "0";//preg_replace('/[^0-9]/','',$dados_cobranca['inscricao_no_ccm']);
/*
SOMENTE PARA TOMADORES ESTABELECIDOS NO MUNICIPIO DE SÃO PAULO
*/
/* campo 15 */$inscricao_estadual_tomador = "0";//preg_replace('/[^0-9]/','',$dados_cobranca['inscricao_estadual']);
/* campo 16 */$nome_razao_social_tomador = substr(strtr($dados_cobranca['sacado'], $trans),0,75);
//$nome_razao_social_tomador = utf8_decode(iconv('UTF-8', 'ASCII//TRANSLIT',$nome_razao_social_tomador));

/*
OBRIGATÓRIO SOMENTE PARA TOMADORES PJ
*/
$tipo_endereco = substr($dados_cobranca['endereco'],0,strpos($dados_cobranca['endereco']," "));
//echo $tipo_endereco . "<BR>";
$tipo_endereco = strtr(strtolower(strtr($tipo_endereco,$trans)),$tipos);
//echo $tipo_endereco . "<BR>";
/* campo 17 */$tipo_endereco_tomador = substr($tipo_endereco,0,3);//substr($dados_cobranca['endereco'],0,3);
//$tipo_endereco_tomador = utf8_decode(iconv('UTF-8', 'ASCII//TRANSLIT',$tipo_endereco_tomador));
/*
OBRIGATÓRIO SOMENTE PARA TOMADORES PJ
*/

/* campo 18 */$endereco_tomador = substr(preg_replace('/^(rua |r |r. |avenida |av |av. |estrada |est |estr |praça |praca |prc |pr |alam |alameda |rodovia |rod |rod. |-)/i','',strtr($dados_cobranca['endereco'],$trans)),0,50);
$endereco_tomador = utf8_decode(iconv('UTF-8', 'ASCII//TRANSLIT',$endereco_tomador));

/*
OBRIGATÓRIO SOMENTE PARA TOMADORES PJ
*/
/* campo 19 */$numero_endereco_tomador = substr($dados_cobranca['numero'],0,10);
//$numero_endereco_tomador = utf8_decode(iconv('UTF-8', 'ASCII//TRANSLIT',$numero_endereco_tomador));

/* campo 20 */$complemento_endereco_tomador = substr($dados_cobranca['complemento'],0,30);
//$complemento_endereco_tomador = utf8_decode(iconv('UTF-8', 'ASCII//TRANSLIT',$complemento_endereco_tomador));

/* campo 21 */$bairro_endereco_tomador = substr(strtr($dados_cobranca['bairro'],$trans),0,30);
//$bairro_endereco_tomador = utf8_decode(iconv('UTF-8', 'ASCII//TRANSLIT',$bairro_endereco_tomador));

/*
OBRIGATÓRIO SOMENTE PARA TOMADORES PJ
*/
/* campo 22 */$cidade_endereco_tomador = substr(strtr($dados_cobranca['cidade'],$trans),0,50);
//$cidade_endereco_tomador = utf8_encode(iconv('UTF-8', 'ASCII//IGNORE',$cidade_endereco_tomador));

/*
OBRIGATÓRIO SOMENTE PARA TOMADORES PJ
*/
/* campo 23 */$estado_endereco_tomador = substr($dados_cobranca['uf'],0,2);
/*
OBRIGATÓRIO SOMENTE PARA TOMADORES PJ
*/
/* campo 24 */$cep_endereco_tomador = preg_replace('/[^0-9]/','',$dados_cobranca['cep']);
/*
OBRIGATÓRIO SOMENTE PARA TOMADORES PJ
*/
/* campo 25 */$email_tomador = $dados_cobranca['email'];




/* campo 26 */$descricao_servicos = preg_replace('/[\n]+?/','|',"Assinatura mensal do portal Contador Amigo 

Alíquotas de impostos: IRPJ: 0,48% - CSLL: 0,43% - COFINS: 1,43% - PIS: 0,35% - CPP: 4,07% - ISS: 2,00%");


$total_servicos += $dados_cobranca['valor_pago'];
$total_deducoes += 0;

$detalhes .= str_pad($tipo_registro,1," ",STR_PAD_RIGHT);
$detalhes .= str_pad($tipo_RPS,5," ",STR_PAD_RIGHT);
$detalhes .= str_pad($serie_RPS,5," ",STR_PAD_RIGHT);
$detalhes .= str_pad($numero_RPS,12,"0",STR_PAD_LEFT);
$detalhes .= str_pad($data_emissao_RPS,8," ",STR_PAD_RIGHT);
$detalhes .= str_pad($situacao_RPS,1," ",STR_PAD_RIGHT);
$detalhes .= str_pad($valor_servicos,15,"0",STR_PAD_LEFT);
$detalhes .= str_pad($valor_deducoes,15,"0",STR_PAD_LEFT);
$detalhes .= str_pad($codigo_servico_prestado,5,"0",STR_PAD_LEFT);
$detalhes .= str_pad($aliquota,4,"0",STR_PAD_LEFT);
$detalhes .= str_pad($iss_retido,1,"0",STR_PAD_LEFT);
$detalhes .= str_pad($indicador_doc_tomador,1,"0",STR_PAD_LEFT);
$detalhes .= str_pad($doc_tomador,14,"0",STR_PAD_LEFT);
$detalhes .= str_pad($inscricao_municipal_tomador,8,"0",STR_PAD_LEFT);
$detalhes .= str_pad($inscricao_estadual_tomador,12,"0",STR_PAD_LEFT);
$detalhes .= str_pad(strtoupper($nome_razao_social_tomador),75," ",STR_PAD_RIGHT);
$detalhes .= str_pad(strtoupper($tipo_endereco_tomador),3," ",STR_PAD_RIGHT);
$detalhes .= (str_pad(strtoupper($endereco_tomador),50," ",STR_PAD_RIGHT));
$detalhes .= str_pad(strtoupper($numero_endereco_tomador),10," ",STR_PAD_RIGHT);
$detalhes .= str_pad(strtoupper($complemento_endereco_tomador),30," ",STR_PAD_RIGHT);
$detalhes .= str_pad(strtoupper($bairro_endereco_tomador),30," ",STR_PAD_RIGHT);
$detalhes .= str_pad(strtoupper($cidade_endereco_tomador),50," ",STR_PAD_RIGHT);
$detalhes .= str_pad(strtoupper($estado_endereco_tomador),2," ",STR_PAD_RIGHT);
$detalhes .= str_pad($cep_endereco_tomador,8,"0",STR_PAD_LEFT);
$detalhes .= str_pad(strtolower(strtr($email_tomador, $trans)),75," ",STR_PAD_RIGHT);
$detalhes .= strtoupper(strtr($descricao_servicos, $trans));
$detalhes .= $fim_linha;

}
/* /DETALHES */

/* FOOTER */
/* campo 1 */$tipo_registro = "9";
/* campo 2 */$numero_linhas_detalhes = $numero_RPS - $numero_inicial;//$conta_linhas;
/* campo 3 */$valor_total_servicos = number_format($total_servicos,2,'','');
/* campo 4 */$valor_total_deducoes = number_format($total_deducoes,2,'','');
$footer = str_pad($tipo_registro,1," ",STR_PAD_RIGHT);
$footer .= str_pad($numero_linhas_detalhes,7,"0",STR_PAD_LEFT);
$footer .= str_pad($valor_total_servicos,15,"0",STR_PAD_LEFT);
$footer .= str_pad($valor_total_deducoes,15,"0",STR_PAD_LEFT);
$footer .= $fim_linha;
/* /FOOTER */

$rps = $header . $detalhes . $footer;



mysql_query('UPDATE `relatorio_cobranca` SET emissao_NF = 1 WHERE emissao_NF = 0');


// MONTANDO O DIRETORIO PARA SALVAR O ARQUIVO sefip.re DO USUARIO CASO NÃO EXISTA
$nome_pasta = '../RPS/COBRANCA';

// VERIFICANDO SE A PASTA JÁ EXISTE
if(!is_dir($nome_pasta)){
	mkdir($nome_pasta, 0777, true);
}

$nome_arquivo = 'RPS_'.date('Y').date('m').date('d').'_'.time().'.txt';

// SALVANDO O ARQUIVO
file_put_contents($nome_pasta . "/" . $nome_arquivo, iconv('UTF-8', 'ISO-8859-1//TRANSLIT',$rps));

//$rsArquivos = mysql_query("SELECT id, nome, data_geracao, baixado, processado, inicio_numeracao_rps, fim_numeracao_rps FROM rps WHERE DATE(data_geracao) = '".date('Y-m-d')."'");

//if($linha = mysql_fetch_array($rsArquivos)){
//	mysql_query("UPDATE rps SET
//	nome = '".$nome_arquivo."'
//	, data_geracao = '" . date('Y-m-d H:i:s') . "'
//	, inicio_numeracao_rps = " . ($numero_inicial+1) . "
//	, fim_numeracao_rps = " . ($numero_RPS) . "
//	, ids_relatorio_cobranca = '" . json_encode($ids_relatorios_cobranca) . "'
//	WHERE id = '".$linha['id']."'
//	");
//}else{

	mysql_query("INSERT INTO rps SET
	nome = '".$nome_arquivo."'
	, inicio_numeracao_rps = " . ($numero_inicial+1) . "
	, fim_numeracao_rps = " . ($numero_RPS) . "
	, ids_relatorios_cobranca = '" . json_encode($ids_relatorios_cobranca) . "'
	");


//}
//	header('location: rps.php');


	// ######################### FIM DA GERAÇÃO DO RPS ##############################
}

include 'header.php';

$dataInicio = $_GET["dataInicio"];
if ($dataInicio == "") {
	$dataInicio = date('Y-m-d',mktime(0,0,0,date('m'),date('d')-7,date('Y')));
}

$dataFim = $_GET["dataFim"];
if ($dataFim == "") {
	$dataFim = date("Y-m-d");
}

$status = $_GET["status"];
if ($status == "") {
	$status = "todos";
}

function selected( $value, $prev ){
   return $value==$prev ? ' selected="selected"' : ''; 
};
?>

<script>
function alterarPeriodo() {
	dataInicio = document.getElementById('DataInicio').value;
	anoInicio = dataInicio.substr(6,4);
	mesInicio = dataInicio.substr(3,2);
	diaInicio = dataInicio.substr(0,2);
	dataFim = document.getElementById('DataFim').value;
	anoFim = dataFim.substr(6,4);
	mesFim = dataFim.substr(3,2);
	diaFim = dataFim.substr(0,2);
	status = document.getElementById('selStatus').value;

	window.location='rps.php?dataInicio='+anoInicio+'-'+mesInicio+'-'+diaInicio+'&dataFim='+anoFim+'-'+mesFim+'-'+diaFim+'&status='+status;
}

</script>

<div class="principal">

	<div class="titulo" style="margin-bottom:10px;">Arquivos RPS</div>
   
    
<?
// A CONSULTA
$sql_arquivos = 'SELECT id,nome,data_geracao,baixado,processado FROM rps WHERE 1 = 1';

// OS FILTROS
if($dataInicio != "" && $dataFim != "") {
	$sql_arquivos .= " AND data_geracao BETWEEN '$dataInicio 00:00:00' AND '$dataFim 23:59:59'";
	$titulo_vermelho = "Arquivos RPS  - " . ($dataInicio == $dataFim ? "dia " . date('d/m/Y',strtotime($dataInicio)) : "período entre " . date('d/m/Y',strtotime($dataInicio)) . " até " . date('d/m/Y',strtotime($dataFim)) );

}

if($status == "baixado") {
	$sql_arquivos .= " AND baixado = 1";
}

if($status == "processado") {
	$sql_arquivos .= " AND processado = 1";
}

$sql_arquivos .= ' ORDER BY data_geracao DESC';

// lista os arquivos se houverem
$rsArquivos = mysql_query($sql_arquivos);

//$IdExcluir = mysql_query("SELECT id FROM rps_excluir_ids");
//$arrIds = array();
//while($rsIdExcluir = mysql_fetch_array($IdExcluir)){
//	array_push($arrIds,$rsIdExcluir['id']);
//}
//$ids_excluir = implode(',',$arrIds);

?>

<div style=" clear:both; height: 15px;"> </div>

<div style="float:left">Excluir IDs: <input type="text" name="txt_id_excluir" id="txt_id_excluir" value="<?=$ids_excluir?>" />&nbsp;&nbsp;<input type="button" value="Gerar RPS" id="bt_gerar_arquivo" /></div>

<div style="float:left; width:30px;">&nbsp;</div>

<div style="float:left">
<form method="post" action="Javascript:alterarPeriodo()">
Exibir 
<select name="selStatus" id="selStatus" style="width:150px;font-size: 12px;"> 
<option value="" <?php echo selected( 'todos', $status); ?>>Todos</option>
<option value="baixado" <?php echo selected( 'baixado', $status); ?>>Baixados</option>
<option value="processado" <?php echo selected( 'processado', $status); ?>>Processados</option>
</select>
&nbsp;no período entre  
  <input name="DataInicio" id="DataInicio" type="text" value="<?=date('d/m/Y',strtotime($dataInicio))?>" maxlength="10"  style="width:85px;font-size: 12px;" class="campoData"/> 
  até 
  <input name="DataFim" id="DataFim" type="text" value="<?=date('d/m/Y',strtotime($dataFim))?>" maxlength="10"  style="width:85px;font-size: 12px;" class="campoData" /> <input name="Alterar" type="submit" value="Filtrar" />
</form>
</div>
<div style=" clear:both; height: 10px;"> </div>

<div class="tituloVermelho">
	<?=$titulo_vermelho?>
</div>

<table border="0" cellspacing="2" cellpadding="4" style="font-size:12px; width: 80%;">
    <tr>
        <th width="40%" align="center">Arquivo</th>
        <th width="25%" align="center">Data</th>
        <th width="20%" align="center">Ação</th>
        <th width="15%" align="center">NF</th>
	</tr> 
<?

if (mysql_num_rows($rsArquivos) > 0) {
	$nome_pasta = '../RPS/COBRANCA';
	$data_ultimo_arquivo = '';
	$contador = 1;
	while($arquivos=mysql_fetch_object($rsArquivos)){
		
		if($data_ultimo_arquivo == ''){$data_ultimo_arquivo = date('Ymd',strtotime($arquivos->data_geracao));}
	
	   	print " 	<tr class='guiaTabela' style='background-color:#" . ($arquivos->processado == 0 ? "fff6c3" : "FFF") . "' valign='top'>
	   				<td><a href='" . $nome_pasta . "/".$arquivos->nome."' target='_blank'>".$arquivos->nome."</a></td>
					<td>" . date('d/m/Y H:i:s',strtotime($arquivos->data_geracao)) . "</td>
					<td align=\"center\">
					<div style=\"float:left;width:50%;text-align:center\"><a href=\"".$arquivos->id."\" class=\"baixar\">baixar</a></div>
					<div style=\"float:left;width:50%;text-align:center\">" . ($contador == 1 && $arquivos->processado == 0 ? "<a href=\"".$arquivos->id."\" class=\"undo\">excluir</a>" : "") . "</div></td>
					<td align=\"center\">
					<div id=\"linkNF_". $arquivos->id . "\">
					<a class=\"marcaEmissaoNF\" href=\"#\">" . ($arquivos->processado == 0 ? "N" : "S") . "</a></div>
					</td>
				</tr>";
				//&nbsp;&nbsp;<a href=\"javascript:if(confirm('Deseja realmente excluir este arquivo?')){location.href='rps.php?excluir=".$arquivos->id."';}\">Excluir</a>
		$contador++;

	}
}else{
	print "<tr class='guiaTabela' style='background-color:#FFF' valign='top'><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
}
?>
</table>
</div>

<script type="text/javascript">

$(document).ready(function(e) {

var dataHoje = new Date();
var dataUltimoArquivo = "<?=$data_ultimo_arquivo?>";

	$('.baixar').bind('click',function(e){
		e.preventDefault();
		window.open("baixar_rps.php?download="+$(this).attr('href'));
		location.href='rps.php';
	});
	
	$('.undo').bind('click',function(e){
		e.preventDefault();
		if(confirm('Deseja realmente excluir este arquivo?\nCaso já tenha gerado as notas fiscais relativas a este RPS NÃO EXCLUA o arquivo.')){
			location.href="rps.php?undo="+$(this).attr('href');
		}
	});
	  
    $('.marcaEmissaoNF').click(function(e){
		e.preventDefault();
		var id_pagto = $(this).parent().attr('id').replace("linkNF_","");
		var url = location.href.replace("#","");
		var separador = (url.search(/\?/i) > 0 ? separador = "&" : separador = "?");
//		(url.search(/\?/i) > 0 ? separador = "&" : separador = "?")
		
		if(id_pagto != '' && id_pagto != 'undefined'){
//			alert(url + separador + "toggleRPS=" + id_pagto);
			location.href=(url + separador + "toggleRPS=" + id_pagto);
		}
	});

    $('#bt_gerar_arquivo').click(function(e){
		e.preventDefault();
		var url = "<?=$_SERVER['PHP_SELF']?>";
		var separador = (url.search(/\?/i) > 0 ? separador = "&" : separador = "?");
		var ids = ($('#txt_id_excluir').val() != '' ? "&ids="+$('#txt_id_excluir').val() : ""); // variavel que guarda os ids que devem ser excluidos da geração do RPS

		var dataComparaArquivo = dataHoje.getFullYear() + ('00' + (dataHoje.getMonth() + 1)).substr(-2) + dataHoje.getDate();
		
//		if(dataComparaArquivo == dataUltimoArquivo){
//			if(confirm('Já foi gerado um arquivo de RPS na data de hoje.\nDeseja emitir um novo arquivo na data de hoje?')){
//				alert(url + separador + "processar");
				location.href=(url + separador + "processar"+ids);
//			}
//		}
				
	});
	
	
});


<?
if($_SESSION['erro_RPS'] != ''){
	echo "alert('".$_SESSION['erro_RPS']."');";
	$_SESSION['erro_RPS'] = '';
}
?>

</script>