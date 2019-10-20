<?php 

include '../conect.php';
include '../session.php';
include 'check_login.php';


if ($_POST) {
	$busca = $_POST["txtBusca"];
	$coluna = $_POST["selColuna"];
	$status = $_POST["selStatus"];
	
	header('Location: clientes_lista.php?busca=' . $busca . '&coluna=' . $coluna . '&status=' . $status . '&ordem=' . $_GET["ordem"] . '&pagina=1' );
} 

function selected( $value, $prev ){
   return $value==$prev ? ' selected="selected"' : ''; 
};
?>
<?php include 'header.php' ?>

<script>
function formCSV() {
	window.open('clientes_lista_csv.php');
}
</script>

<div class="principal">

 <div style="float:right">
 

 
 <form method="post" action="">
<?php
//Valores pré-definidos para a busca.
if (!$_GET["coluna"]) {
	$_GET["coluna"] = "email";
}
if (!$_GET["ordem"]) {
	$_GET["ordem"] = "l.assinante";
//	$_GET["ordem"] = "l.email";
}
if ((!$_GET["pagina"]) or ((int)$_GET["pagina"] == 0)) {
	$_GET["pagina"] = "1";
}

/*$query = mysql_query("SELECT * FROM dados_da_empresa"); 
$number = mysql_num_rows($query); 

WHERE STATUS IN ('ativo', 'inativo', 'demo', 'demoInativo', 'cancelado')
AND NOT ISNULL(email) AND email <> '' AND email <> '1' AND email like '%@%'
AND id NOT IN (9,1581, 4093, 6857, 6905, 6958, 6835, 6858, 6764, 7072, 6870, 7077, 7075, 7004, 7045, 6963, 6869, 6963, 6787, 6614, 6576, 6742, 6613) AND id = idUsuarioPai")); 

*/

$sqlExcluiTestes = " AND l.id NOT IN (9, 1581, 4093, 6857, 6905, 6958, 6835, 6858, 6764, 7072, 6870, 7077, 7075, 7004, 7045, 6963, 6869, 6963, 6787, 6614, 6576, 6742, 6613)";
$sqlEmailsValidos = " AND NOT ISNULL(l.email) AND l.email <> '' AND l.email <> '1' AND l.email like '%@%'";

$loginAtiv = "SELECT COUNT(*) ativos FROM login l 
JOIN dados_cobranca c ON c.id = l.id 
WHERE l.id = l.idUsuarioPai
AND c.tipo_plano = 'P'
AND l.status = 'ativo'"
.$sqlExcluiTestes;

$premiums = mysql_fetch_array(mysql_query($loginAtiv));

$sqlStatus = "
SELECT
	SUM(CASE WHEN l.status = 'inativo' THEN 1 ELSE 0 END) inativos
	, SUM(CASE WHEN l.status = 'ativo' THEN 1 ELSE 0 END) ativos
	, SUM(CASE WHEN l.status = 'demo' THEN 1 ELSE 0 END) demos
	, SUM(CASE WHEN l.status = 'demoInativo' THEN 1 ELSE 0 END) demoInativos
	, SUM(CASE WHEN l.status = 'cancelado' THEN 1 ELSE 0 END) cancelados
FROM 
	login l
WHERE 
	l.status IN ('inativo','cancelado','demo','demoInativo','ativo')
	AND l.id = l.idUsuarioPai
" . $sqlExcluiTestes . "
";
$rsStatus = mysql_fetch_array(mysql_query($sqlStatus));

$qtdInativos = $rsStatus['inativos'];
$qtdAtivos = $rsStatus['ativos'];
$qtdDemos = $rsStatus['demos'];
$qtdDemoInativos = $rsStatus['demoInativos'];
$qtdCancelados = $rsStatus['cancelados'];
$qtdTodos = ($rsStatus['inativos'] + $rsStatus['ativos'] + $rsStatus['demos'] + $rsStatus['demoInativos'] + $rsStatus['cancelados']);


$qtd = $qtdAtivos;

		
switch($_REQUEST['status']){
	case 'todos':
//		$query = mysql_query("SELECT * FROM login l WHERE l.id = l.idUsuarioPai " . $sqlExcluiTestes); 
		$labelFiltro = 'Inscritos';
		$qtd = $qtdTodos;
	break;
	case 'inativo':
//		$query = mysql_query("SELECT * FROM login l where l.status = 'inativo' AND l.id = l.idUsuarioPai " . $sqlExcluiTestes); 
		$labelFiltro = 'Inativos';
		$qtd = $qtdInativos;
	break;
	case 'cancelado':
//		$query = mysql_query("SELECT * FROM login l where l.status = 'cancelado' AND l.id = l.idUsuarioPai " . $sqlExcluiTestes); 
		$labelFiltro = 'Cancelados';
		$qtd = $qtdCancelados;
	break;
	case 'demo':
//		$query = mysql_query("SELECT * FROM login l where l.status = 'demo' AND l.id = l.idUsuarioPai " . $sqlExcluiTestes); 
		$labelFiltro = 'Demos';
		$qtd = $qtdDemos;
	break;
	case 'demoInativo':
//		$query = mysql_query("SELECT * FROM login l where l.status = 'demoInativo' AND l.id = l.idUsuarioPai " . $sqlExcluiTestes); 
		$labelFiltro = 'Demos Inativos';
		$qtd = $qtdDemoInativos;
	break;
	default:
//		$query = mysql_query("SELECT * FROM login l where l.status = 'ativo' AND l.id = l.idUsuarioPai " . $sqlExcluiTestes); 
		$labelFiltro = 'Ativos';
		$qtd = $qtdAtivos;
	break;
}

//$filtro = mysql_num_rows($query); 


$tipoConsulta = "login";




//Filtro referente aos status.
switch ($_GET["status"]){
	case 'todos':
		$resStatus = "";
	break;
	case 'inativo':
		$resStatus = " AND l.status='inativo'";
	break;
	case 'cancelado':
		$resStatus = " AND l.status='cancelado'";
	break;
	case 'demo':
		$resStatus = " AND l.status='demo'";
	break;
	case 'demoInativo':
		$resStatus = " AND l.status='demoInativo'";
	break;
	case 'certificacao_digital':
		$resStatus = " AND emp.certificacao_digital='pendente'";
	break;
	default:
		$resStatus = " AND (l.status='ativo' OR  l.status='admin')";
	break;
}

//Paginação
$paginaAtual = (int)$_GET["pagina"];
$quantidadeResultados = 100;
$camposExibidos = ($paginaAtual*$quantidadeResultados) - $quantidadeResultados; 
$qtdPaginasAntesEDepois = 5;



//Componente de pesquisa.
/*if ($_GET["busca"] != ""){
	if ($_GET["coluna"] == "cnpj"){
		$resColuna = " AND REPLACE(REPLACE(REPLACE(dados_da_empresa." . $_GET["coluna"] . ",'.',''),'-',''),'/','') = '" . str_replace('/','',str_replace('-','',str_replace('.','',$_GET["busca"]))) . "'";		
	} else if($_GET["coluna"] == "razao_social"){
		$resColuna = " AND dados_da_empresa." . $_GET["coluna"] . " LIKE '%" . $_GET["busca"] . "%'";
	} else if ($_GET["coluna"] == "email") {
		$resColuna = " AND login." . $_GET["coluna"] . " LIKE '%" . $_GET["busca"] . "%'";
	} else if ($_GET["coluna"] == "id") {
		$resColuna = " AND login." . $_GET["coluna"] . " = " . $_GET["busca"] . "";
	} else if($_GET["coluna"] == "cidade"){
		$resColuna = " AND dados_da_empresa." . $_GET["coluna"] . " LIKE '%" . $_GET["busca"] . "%'";
	} else if ($_GET["coluna"] == "numero_cartao") {
		$resColuna = " AND dados_cobranca." . $_GET["coluna"] . " LIKE '%" . $_GET["busca"] . "'";
	} else {
		$resColuna = " AND dados_cobranca." . $_GET["coluna"] . " LIKE '%" . $_GET["busca"] . "%'";
	}
}*/
$sql = "
SELECT 
	'' cnpj
	, '' razao_social
	, l.assinante
	, l.email
	, l.id
	, cob.pref_telefone
	, cob.telefone
FROM 
	login l
	INNER JOIN dados_cobranca cob ON l.id = cob.id
WHERE 
	1 = 1 
	AND l.id = l.idUsuarioPai"
	. $resStatus 
	. $sqlExcluiTestes
. " 
	ORDER BY " . $_GET['ordem'] . ", l.assinante ASC ";
//SELECT COUNT(id) FROM login WHERE idUsuarioPai = l.idUsuarioPai) 
// . $_GET["ordem"] . "
   
if ($_GET["busca"] != ""){
	switch($_GET["coluna"]){
		case "razao_social":
		case "cidade":
		default:
			
			$tipoConsulta = "empresa";
			
			$resColuna = " AND emp." . $_GET["coluna"] . " LIKE '%" . $_GET["busca"] . "%'";
		
		break;
//		case "numero_cartao":
//		case "nome_titular":
//			$tipoConsulta = "cobranca";
//			$resColuna = " AND cob." . $_GET["coluna"] . " LIKE '%" . $_GET["busca"] . "%'";
//		break;
		case "cnpj":
			$tipoConsulta = "empresa";
			$resColuna = " AND REPLACE(REPLACE(REPLACE(emp." . $_GET["coluna"] . ",'.',''),'-',''),'/','') like '%" . str_replace('/','',str_replace('-','',str_replace('.','',$_GET["busca"]))) . "%'";
		break;
		case "assinante":
		case "email":
			$tipoConsulta = "login";
			$resColuna = " AND l." . $_GET["coluna"] . " LIKE '%" . $_GET["busca"] . "%'";
		break;
		case "id":
			$tipoConsulta = "login";
			$resColuna = " AND l." . $_GET["coluna"] . " = " . $_GET["busca"] . "";
		break;
	}
	
	switch($tipoConsulta){
		case "cobranca":
		break;
		case "empresa":
			$sql = "
			SELECT 
				emp.cnpj
				, emp.razao_social
				, l.assinante
				, l.email
				, l.idUsuarioPai id
			FROM 
				dados_da_empresa emp 
				INNER JOIN login l ON emp.id = l.id
			WHERE 1 = 1 "
				. $resColuna 
				. $resStatus 
				. $sqlExcluiTestes
			. " 
			ORDER BY " . $_GET['ordem'] . ", emp.razao_social ASC, l.assinante ASC ";

		break;
		case "login":
			$sql = "
			SELECT 
				'' cnpj
				, '' razao_social
				, l.assinante
				, l.email
				, l.idUsuarioPai id
				, cob.pref_telefone
				, cob.telefone
			FROM 
				login l
				INNER JOIN dados_cobranca cob ON l.id = cob.id
			WHERE 1 = 1 "
				. $resColuna 
				. $resStatus 
			. " 
				AND l.id = l.idUsuarioPai
			ORDER BY " . $_GET['ordem'] . ", l.assinante ASC ";

		break;
	}

}

$_SESSION['SQLLISTA'] = $sql;
$_SESSION['SQLLISTATIPOCONSULTA'] = $tipoConsulta;

?>

	<!--<span style="margin-right:10px"> <strong>Total de inscritos:</strong> <?=$number?></span>-->
	<span style="margin-right:10px">
		<strong>Total de <?=$labelFiltro?>:</strong> <?=$qtd?> - 
		<strong> Premiums :</strong> <?php echo $premiums['ativos'];?>
	</span>
	<input name="txtBusca" type="text" id="txtBusca" style="width:160px" value="<?=$_GET["busca"]?>" />
	<select name="selColuna" id="selColuna">
	<option value="id" <?php echo selected( 'id', $_GET["coluna"] ); ?> >ID</option>
	<option value="assinante" <?php echo selected( 'assinante', $_GET["coluna"] ); ?> >Assinante</option>
	<option value="email" <?php echo selected( 'email', $_GET["coluna"] ); ?> >Email</option>
	<option value="razao_social" <?php echo selected( 'razao_social', $_GET["coluna"] ); ?> >Razão Social</option>
	<option value="cnpj" <?php echo selected( 'cnpj', $_GET["coluna"] ); ?> >CNPJ</option>
	<option value="cidade" <?php echo selected( 'cidade', $_GET["coluna"] ); ?> >Cidade</option>
<!--
     <option value="numero_cartao" <?php echo selected( 'numero_cartao', $_GET["coluna"] ); ?> >Final do Cartão</option>
     <option value="nome_titular" <?php echo selected( 'nome_titular', $_GET["coluna"] ); ?> >Nome no Cartão</option>
-->
   </select>
   <select name="selStatus" id="selStatus">
     <option value="todos" <?php echo selected( 'todos', $_GET["status"] ); ?> >Todos</option>
     <option value="ativo" <?php echo selected( 'ativo', $_GET["status"] ); ?> <?php echo selected( '', $_GET["status"] ); ?> >Ativo</option>
     <option value="inativo" <?php echo selected( 'inativo', $_GET["status"] ); ?> >Inativo</option>
     <option value="demo" <?php echo selected( 'demo', $_GET["status"] ); ?> >Demo</option>
     <option value="cancelado" <?php echo selected( 'cancelado', $_GET["status"] ); ?> >Cancelado</option>
     <option value="demoInativo" <?php echo selected( 'demoInativo', $_GET["status"] ); ?> >Demo Inativo</option>
     <!--<option value="certificacao_digital" <?php echo selected( 'certificacao_digital', $_GET["status"] ); ?> >CD Pendente</option> -->
   </select>
   <input type="submit" value="Pesquisar" />
   
 </form>
 </div>

  <div class="titulo" style="margin-bottom:10px;">Dados dos Clientes</div>
  <div style="clear:both"> </div>
  <table border="0" cellspacing="2" cellpadding="4" style="font-size:12px" width="100%">
    <tr>
    <? if($tipoConsulta == "empresa"){ ?>
      <th align="center" width="15%">CNPJ</th>
      <th align="center" width="25%">Razão Social</th>
      <th align="center" width="25%">Assinante <small>(nº de empresas)</small></th>
      <th align="center" width="25%">Email</th>
      <th align="center" width="10%">Ação</th>
    <? } else { ?>
      <th align="center" width="40%">Assinante <small>(nº de empresas)</small></th>
      <th align="center" width="40%">Email</th>
      <th align="center" width="12%">Telefone</th>
      <th align="center" width="8%">Ação</th>
    <? } ?>

    </tr>
<?php
	
	
//$sql = "SELECT * FROM dados_da_empresa emp, dados_cobranca cob, login l WHERE dados_da_empresa.id = dados_cobranca.id AND dados_da_empresa.id = login.id AND login.id = login.idUsuarioPai" . $resColuna . $resStatus . " ORDER BY " . $_GET["ordem"] . ", dados_da_empresa.razao_social ASC LIMIT " . $camposExibidos . ", " . $quantidadeResultados;	

$sqlLimit = " LIMIT " . $camposExibidos . ", " . $quantidadeResultados;	

//echo $sql;

$resultado = mysql_query($sql . $sqlLimit)
or die (mysql_error());

$corLinha = "#FFF";

while ($linha=mysql_fetch_array($resultado)) {
	
$qtdEmpresas = mysql_num_rows(mysql_query("SELECT id FROM dados_da_empresa WHERE id IN (SELECT id FROM login WHERE idUsuarioPai = " . $linha["id"] . ")"));	
	
?>
<tr class="guiaTabela" style="background-color:<?=$corLinha?>">


  <? if($tipoConsulta == "empresa"){ ?>
		<td><?=($linha["cnpj"] != '' ? $linha["cnpj"] : '')?></td>
	  <td><?=($linha["razao_social"] != '' ? $linha["razao_social"] : '')?></td>
	  <td><?=$linha["assinante"] . " <small>(" . $qtdEmpresas . ")</small>"?></td>
	  <td><a href="mailto:<?=$linha["email"]?>"><?=strtolower($linha["email"])?></a></td>
	  <td align="center"><a href="cliente_administrar.php?id=<?=$linha["id"]?>"><i class="fa fa-pencil-square-o" style="font-size: 16px;"></i></a> | <a href="#" onClick="if(confirm('Você tem certeza que deseja excluir este cliente?')){if(confirm('Ao remover um cliente, todos os dados serão perdidos.\nDeseja realmente excluir?')){location.href='clientes_excluir.php?linha=<?=$linha["id"]?>'}};"><i class="fa fa-trash-o" style="font-size: 16px;"></i></a></td>
  <? } else { 
		$telefone = "";
		if($linha["telefone"] != ""){
			if(strlen($linha["telefone"]) == 8){
				$telefone = "(" . $linha["pref_telefone"] . ") " . substr($linha["telefone"],0,4) . "-" . substr($linha["telefone"],-4);
			}else{
				$telefone = "(" . $linha["pref_telefone"] . ") " . substr($linha["telefone"],0,5) . "-" . substr($linha["telefone"],-4);
			}
		}
	?>
	  <td><?=$linha["assinante"] . " <small>(" . $qtdEmpresas . ")</small>"?></td>
	  <td><a href="mailto:<?=$linha["email"]?>"><?=strtolower($linha["email"])?></a></td>
	  <td><?=$telefone?></td>
	  <td align="center">
	  	<a href="cliente_administrar.php?id=<?=$linha["id"]?>">
	  		<i class="fa fa-pencil-square-o" style="font-size: 16px;"></i>
	  	</a> 
	  	<!-- <a href="#" onClick="if(confirm('Você tem certeza que deseja excluir este cliente?')){if(confirm('Ao remover um cliente, todos os dados serão perdidos.\nDeseja realmente excluir?')){location.href='clientes_excluir.php?linha=<?=$linha["id"]?>'}};"><i class="fa fa-trash-o" style="font-size: 16px;"></i> -->
	  	<!-- </a> -->
	  </td>
  <? } ?>
  

</tr>
<?php
	if ($corLinha == "#FFF") {
		$corLinha = "#E5E5E5";
	} else {
		$corLinha = "#FFF";
	} 
}?>
</table>
<?php
/*//Consulta sem o limite para produzir o número de páginas
$sql = "
SELECT 
	emp.cnpj
	, emp.razao_social
	, l.assinante
	, l.email
	, l.id
FROM 
	dados_da_empresa emp 
	RIGHT JOIN login l ON emp.id = l.id
	INNER JOIN dados_cobranca cob ON l.idUsuarioPai = cob.id
WHERE 
	1 = 1 
	AND l.id = l.idUsuarioPai"
	. $resColuna 
	. $resStatus 
	. $sqlExcluiTestes;*/

$resultado = mysql_query($sql)
or die (mysql_error());

$totalPesquisado = mysql_num_rows($resultado);
?>

	<div style="clear: both; height: 10px;"></div>
  
  <?
if($totalPesquisado > $quantidadeResultados) {
	echo "<div style=\"width: 49%; float: left; text-align: left;\">";
	if($paginaAtual == 1) {
		echo 'anterior | ';
	} else {
		echo '<a href="clientes_lista.php?busca=' . $busca . '&coluna=' . $coluna . '&status=' . $status . '&ordem=' . $_GET["ordem"] . '&pagina=' . ($paginaAtual - 1) . '">anterior</a> |';
	}
	
	for($i = ($paginaAtual-$qtdPaginasAntesEDepois); $i <= $paginaAtual-1; $i++) { 
		// Se o número da página for menor ou igual a zero, não faz nada 
		// (afinal, não existe página 0, -1, -2..) 
		if($i > 0) { 
			echo '<a href="clientes_lista.php?busca=' . $busca . '&coluna=' . $coluna . '&status=' . $status . '&ordem=' . $_GET["ordem"] . '&pagina=' . $i . '">' . $i . '</a> |';
		}
	}

	echo ' ' . $paginaAtual . ' |';

	for($i = $paginaAtual+1; $i <= $paginaAtual+$qtdPaginasAntesEDepois; $i++) { 
		// Verifica se a página atual é maior do que a última página. Se for, não faz nada. 
		if($i <= ceil($totalPesquisado / $quantidadeResultados)) { 
			echo '<a href="clientes_lista.php?busca=' . $busca . '&coluna=' . $coluna . '&status=' . $status . '&ordem=' . $_GET["ordem"] . '&pagina=' . $i . '">' . $i . '</a> |';
		}
	}

/*	for($i = 1; $i <= ceil($totalPesquisado / $quantidadeResultados); $i++) { 
		if($i == $paginaAtual) {
			echo ' '.$i.' |';
		} else {
			echo ' <a href="clientes_lista.php?busca=' . $busca . '&coluna=' . $coluna . '&status=' . $status . '&ordem=' . $_GET["ordem"] . '&pagina=' . $i . '">' . $i . '</a> |';
		} 
	}*/
	
	if($paginaAtual == ceil($totalPesquisado / $quantidadeResultados)) {
		echo ' próxima';
	} else {
		echo ' <a href="clientes_lista.php?busca=' . $busca . '&coluna=' . $coluna . '&status=' . $status . '&ordem=' . $_GET["ordem"] . '&pagina=' . ($paginaAtual + 1) . '">próxima</a>';
	}
	echo "</div>";
}

	echo "<div style=\"width: 49%; float: right; text-align: right;\">";
	echo "<input type=\"button\" value=\"Exportar para Excel\" onClick=\"formCSV()\" />";
	echo "</div>";

	echo "<div style=\"clear: both;\"></div>";
?>

</div>

<?php include '../rodape.php' ?>
