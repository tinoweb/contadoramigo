<?
include 'session.php';
include 'conect.php' ;

/*
, data_nasc
, '".date('Y-m-d',mktime(0,0,0,substr($_REQUEST['data_nasc'],3,2),substr($_REQUEST['data_nasc'],0,2),substr($_REQUEST['data_nasc'],6,4)))."'
*/
$cpf = $_REQUEST['cpf'];
$rg = $_REQUEST['rg'];
$pis = $_REQUEST['pis'];
$cep = $_REQUEST['cep'];
/*
$cpf = str_replace('.','',$_REQUEST['cpf']);
$cpf = str_replace('-','',$cpf);

$rg = str_replace('.','',$_REQUEST['rg']);
$rg = str_replace('-','',$rg);

$pis = str_replace('.','',$_REQUEST['pis']);
$pis = str_replace('-','',$pis);

$cep = str_replace('.','',$_REQUEST['cep']);
$cep = str_replace('-','',$cep);
*/
$SQL = "INSERT INTO dados_autonomos (
			id
			, id_login
			, nome
			, cbo
			, tipo_servico
			, cpf
			, rg
			, orgao_emissor
			, pis
			, dependentes
			, endereco
			, cep
			, cidade
			, estado
			, pensao
			, perc_pensao
			, inscr_municipal
			, aliquota_ISS
		) VALUES (
			NULL
			, ".$_REQUEST['id_login']."
			, '".mysql_real_escape_string($_REQUEST['nome'])."'
			, '".$_REQUEST['CBO']."'
			, '".$_REQUEST['tipo_servico']."'
			, '".$cpf."'
			, '".$rg."'
			, '".$_REQUEST['orgao_emissor']."'
			, '".$pis."'
			, ".$_REQUEST['NumeroDep']."
			, '".mysql_real_escape_string($_REQUEST['endereco'])."'
			, '".$cep."'
			, '".mysql_real_escape_string($_REQUEST['cidade'])."'
			, '".$_REQUEST['estado']."'
			, ".($_REQUEST['pensao'] == '1' ? '1' : '0')."
			, ".($_REQUEST['PercentPensao'] != '' ? number_format($_REQUEST['PercentPensao'],2,'.','') : 0)."
			, '".$_REQUEST['inscricao_municipal']."'
			, ".str_replace("%","",$_REQUEST['AliquotaISS'])."
		)"
;

$teste = mysql_query($SQL) or die (mysql_error());

if($teste){
	$query = mysql_query('SELECT id, nome, dependentes, aliquota_ISS, perc_pensao from dados_autonomos WHERE id_login = ' . $_REQUEST['id_login'] . ' order by nome');
	while($dados = mysql_fetch_array($query)){
		//echo "<OPTION value=\"".$dados['id']."|".$dados['dependentes']."|".$dados['aliquota_ISS']."|".$dados['perc_pensao']."\"";
		echo "<OPTION value=\"".$dados['id']."\"";
		if(mysql_real_escape_string($_REQUEST['nome']) == mysql_real_escape_string($dados['nome'])){
			echo " selected";
		}
		echo ">".$dados['nome']."</OPTION>";
	}
}else{
	echo "";
}
exit;
?>