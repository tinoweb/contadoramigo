<?php include '../conect.php';

echo preg_replace('/[\n]+?/','|',"Assinatura mensal do portal Contador Amigo

Alíquotas de impostos: IRPJ: 0,48% - CSLL: 0,43% - COFINS: 1,43% - PIS: 0,35% - CPP: 4,07% - ISS: 2,00%");
exit;

//header("Content-type: document/text; charset=ISO-8859-1");
//header("Content-Disposition: attachment; filename=\"teste.txt\""); 
//header("Expires: 0");
//header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
//header("Cache-Control: private",false);

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
	"º" => ".",
	"ª" => "."
);

$tipos = array(
	"avenida" => "av", 
	"estrada" => "est",
	"praca" => "prc",
	"alameda" => "alm",
	"travessa" => "trv",
	"rodovia" => "rod"
);

/* campo final de linha */$fim_linha = chr(10);// . chr(10);//"\n";

/* HEADER */

/* campo 1 */$tipo_registro = "1";
/* campo 2 */$versao_arquivo = "1";
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
$sql = "SELECT 
 cob.valor_pago
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
	cob.data = '".date('Y-m-d')."'
	AND cob.resultado_acao in ('1.2','2.1')
";
//	emp.id = '9'
//	LIMIT 0,1

$resultado = mysql_query($sql) or die (mysql_error());
$conta_linhas = 0;


// CHECANDO A NUMERACAO DOS RPS
$rsNumeros = mysql_fetch_array(mysql_query("SELECT MAX(fim_numeracao_rps) inicioProximo FROM rps WHERE DATE(data_geracao) < '".date('Y-m-d')."'"));

$numero_inicial = $rsNumeros['inicioProximo'];

$numero_RPS = $numero_inicial;

while($dados_cobranca = mysql_fetch_array($resultado)){

/* campo 1 */$tipo_registro = "2";
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
/* campo 12 */$indicador_doc_tomador = "2";
/*
1 - CPF
2 - CNPJ
3 - CPF não informado
*/
/* campo 13 */$doc_tomador = preg_replace('/[^0-9]/','',$dados_cobranca['cnpj']);
/* campo 14 */$inscricao_municipal_tomador = "0";//preg_replace('/[^0-9]/','',$dados_cobranca['inscricao_no_ccm']);
/*
SOMENTE PARA TOMADORES ESTABELECIDOS NO MUNICIPIO DE SÃO PAULO
*/
/* campo 15 */$inscricao_estadual_tomador = "0";//preg_replace('/[^0-9]/','',$dados_cobranca['inscricao_estadual']);
/* campo 16 */$nome_razao_social_tomador = substr($dados_cobranca['razao_social'],0,75);
/*
OBRIGATÓRIO SOMENTE PARA TOMADORES PJ
*/
$tipo_endereco = substr($dados_cobranca['endereco'],0,strpos($dados_cobranca['endereco']," "));
//echo $tipo_endereco . "<BR>";
$tipo_endereco = strtr(strtolower(strtr($tipo_endereco,$trans)),$tipos);
//echo $tipo_endereco . "<BR>";
/* campo 17 */$tipo_endereco_tomador = substr($tipo_endereco,0,3);//substr($dados_cobranca['endereco'],0,3);
//echo $tipo_endereco_tomador . "<BR>";
/*
OBRIGATÓRIO SOMENTE PARA TOMADORES PJ
*/
/* campo 18 */$endereco_tomador = substr(preg_replace('/^(rua |r |r. |avenida |av |av. |estrada |est |estr |praça |praca |prc |pr |alam |alameda |rodovia |rod |rod. )/i','',$dados_cobranca['endereco']),0,50);
/*
OBRIGATÓRIO SOMENTE PARA TOMADORES PJ
*/
/* campo 19 */$numero_endereco_tomador = substr($dados_cobranca['numero'],0,10);
/* campo 20 */$complemento_endereco_tomador = substr($dados_cobranca['complemento'],0,30);
/* campo 21 */$bairro_endereco_tomador = substr($dados_cobranca['bairro'],0,30);
/*
OBRIGATÓRIO SOMENTE PARA TOMADORES PJ
*/
/* campo 22 */$cidade_endereco_tomador = substr($dados_cobranca['cidade'],0,50);
/*
OBRIGATÓRIO SOMENTE PARA TOMADORES PJ
*/
/* campo 23 */$estado_endereco_tomador = substr($dados_cobranca['estado'],0,2);
/*
OBRIGATÓRIO SOMENTE PARA TOMADORES PJ
*/
/* campo 24 */$cep_endereco_tomador = preg_replace('/[^0-9]/','',$dados_cobranca['cep']);
/*
OBRIGATÓRIO SOMENTE PARA TOMADORES PJ
*/
/* campo 25 */$email_tomador = $dados_cobranca['email'];

$forma_pagamento_cliente = $dados_cobranca['plano'];

if($forma_pagamento_cliente == 'mensalidade')
	$plano_cliente = 'mensal';
if($forma_pagamento_cliente == 'trimestral')
	$plano_cliente = 'trimestral';
if($forma_pagamento_cliente == 'semestral')
	$plano_cliente = 'semestral';
if($forma_pagamento_cliente == 'anual')
	$plano_cliente = 'anual';

/* campo 26 */$descricao_servicos = preg_replace('/[\n]+/','|',"Assinatura ".$plano_cliente." do portal Contador Amigo



Alíquotas de impostos: IRPJ: 0,48% - CSLL: 0,43% - COFINS: 1,43% - PIS: 0,35% - CPP: 4,07% - ISS: 3,50%");

echo $descricao_servicos;

exit();

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
$detalhes .= str_pad(strtoupper(strtr($nome_razao_social_tomador, $trans)),75," ",STR_PAD_RIGHT);
$detalhes .= str_pad(strtoupper(strtr($tipo_endereco_tomador, $trans)),3," ",STR_PAD_RIGHT);
$detalhes .= str_pad(strtoupper(strtr($endereco_tomador, $trans)),50," ",STR_PAD_RIGHT);
$detalhes .= str_pad(strtoupper(strtr($numero_endereco_tomador, $trans)),10," ",STR_PAD_RIGHT);
$detalhes .= str_pad(strtoupper(strtr($complemento_endereco_tomador, $trans)),30," ",STR_PAD_RIGHT);
$detalhes .= str_pad(strtoupper(strtr($bairro_endereco_tomador, $trans)),30," ",STR_PAD_RIGHT);
$detalhes .= str_pad(strtoupper(strtr($cidade_endereco_tomador, $trans)),50," ",STR_PAD_RIGHT);
$detalhes .= str_pad(strtoupper(strtr($estado_endereco_tomador, $trans)),2," ",STR_PAD_RIGHT);
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

echo $rps;

// MONTANDO O DIRETORIO PARA SALVAR O ARQUIVO sefip.re DO USUARIO CASO NÃO EXISTA
//$nome_pasta = '../RPS/COBRANCA';

// VERIFICANDO SE A PASTA JÁ EXISTE
//if(!is_dir($nome_pasta)){
//	mkdir($nome_pasta, 0777, true);
//}
/*
// VERIFICANDO SE O ARQUIVO JÁ EXISTE
if(file_exists($nome_pasta . '/sefip.re')){
	unlink($nome_pasta . '/sefip.re');
}
*/
//$nome_arquivo = 'RPS_'.date('Y').date('m').date('d').'.txt';

// SALVANDO O ARQUIVO
//file_put_contents($nome_pasta . "/" . $nome_arquivo, iconv('UTF-8', 'ISO-8859-1//TRANSLIT',$rps));

//$rsArquivos = mysql_query("SELECT id, nome, data_geracao, baixado, processado, inicio_numeracao_rps, fim_numeracao_rps FROM rps WHERE DATE(data_geracao) = '".date('Y-m-d')."'");

//if($linha = mysql_fetch_array($rsArquivos)){
//	mysql_query("UPDATE rps SET
//	nome = '".$nome_arquivo."'
//	, data_geracao = '" . date('Y-m-d H:i:s') . "'
//	, inicio_numeracao_rps = " . ($numero_inicial+1) . "
//	, fim_numeracao_rps = " . ($numero_RPS) . "
//	WHERE id = '".$linha['id']."'
//	");
//}else{
//	mysql_query("INSERT INTO rps SET
//	nome = '".$nome_arquivo."'
//	, inicio_numeracao_rps = " . ($numero_inicial+1) . "
//	, fim_numeracao_rps = " . ($numero_RPS) . "
//	");
//}

?>