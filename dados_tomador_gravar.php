<?
include 'session.php';
include 'conect.php' ;

/*
, data_nasc
, '".date('Y-m-d',mktime(0,0,0,substr($_REQUEST['data_nasc'],3,2),substr($_REQUEST['data_nasc'],0,2),substr($_REQUEST['data_nasc'],6,4)))."'
*/
$cei = $_REQUEST['CEI'];
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
$SQL = "INSERT INTO dados_tomadores (
			id
			, id_login
			, nome
			, cei
			, endereco
			, cep
			, cidade
			, estado
			, bairro
		) VALUES (
			NULL
			, ".$_REQUEST['id_login']."
			, '".mysql_real_escape_string($_REQUEST['nomeTomador'])."'
			, '".$_REQUEST['CEI']."'
			, '".mysql_real_escape_string($_REQUEST['enderecoTomador'])."'
			, '".$cep."'
			, '".mysql_real_escape_string($_REQUEST['cidadeTomador'])."'
			, '".$_REQUEST['estadoTomador']."'
			, '".$_REQUEST['bairroTomador']."'
		)"
;

$teste = mysql_query($SQL) or die (mysql_error());

if($teste){
	$query = mysql_query('SELECT id, nome, cei from dados_tomadores WHERE id_login = ' . $_REQUEST['id_login'] . ' order by nome');
	echo "<option value=\"\">Selecione o tomador</option>";
	while($dados = mysql_fetch_array($query)){
		//echo "<OPTION value=\"".$dados['id']."|".$dados['dependentes']."|".$dados['aliquota_ISS']."|".$dados['perc_pensao']."\"";
		echo "<OPTION value=\"".$dados['id']."\"";
		if(mysql_real_escape_string($_REQUEST['nome']) == mysql_real_escape_string($dados['nome'])){
			echo " selected";
		}
		echo ">".$dados['nome']." - ".$dados['cei']."</OPTION>";
	}
}else{
	echo "";
}
exit;
?>