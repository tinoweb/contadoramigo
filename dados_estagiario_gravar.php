<?
include 'session.php';
include 'conect.php' ;

/*
, data_nasc
, '".date('Y-m-d',mktime(0,0,0,substr($_REQUEST['data_nasc'],3,2),substr($_REQUEST['data_nasc'],0,2),substr($_REQUEST['data_nasc'],6,4)))."'
*/
$nome = $_REQUEST['nome'];
$cpf = $_REQUEST['cpf'];
$rg = $_REQUEST['rg'];
$endereco = $_REQUEST['endereco'];
$cep = $_REQUEST['cep'];
$cidade = $_REQUEST['cidade'];
$estado = $_REQUEST['estado'];
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
$SQL = "INSERT INTO estagiarios (
			id
			, id_login
			, nome
			, cpf
			, rg
			, endereco
			, cep
			, cidade
			, estado
		) VALUES (
			NULL
			, ".$id_login."
			, '".mysql_real_escape_string($nome)."'
			, '".$cpf."'
			, '".$rg."'
			, '".mysql_real_escape_string($endereco)."'
			, '".$cep."'
			, '".mysql_real_escape_string($cidade)."'
			, '".$estado."'
		)"
;


$teste = mysql_query($SQL) or die (mysql_error());

if($teste){
	$query = mysql_query('SELECT id, nome from estagiarios WHERE id_login = ' . $_REQUEST['id_login'] . ' order by nome');
	while($dados = mysql_fetch_array($query)){


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