<?
include 'session.php';
include 'conect.php' ;

$cnpj = $_REQUEST['cnpj'];
$cep = $_REQUEST['cep'];
/*
$cep = str_replace('.','',$_REQUEST['cep']);
$cep = str_replace('-','',$cep);
*/
$SQL = "INSERT INTO dados_pj (
			id
			, id_login
			, nome
			, cnpj
			, endereco
			, cep
			, cidade
			, estado
		) VALUES (
			NULL
			, ".$_REQUEST['id_login']."
			, '".mysql_real_escape_string($_REQUEST['nome'])."'
			, '".$cnpj."'
			, '".mysql_real_escape_string($_REQUEST['endereco'])."'
			, '".$cep."'
			, '".mysql_real_escape_string($_REQUEST['cidade'])."'
			, '".$_REQUEST['estado']."'
		)"
;

$teste = mysql_query($SQL) or die (mysql_error());

if($teste){
	$query = mysql_query('SELECT id, nome from dados_pj WHERE id_login = ' . $_REQUEST['id_login'] . ' order by nome');
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