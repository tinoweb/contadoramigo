<?php 
include '../conect.php';
include '../session.php';
include 'check_login.php';

function selected( $value, $prev ){
   return $value==$prev ? ' selected="selected"' : ''; 
};
?>
<?php include 'header.php' ?>

<div class="principal">

 <div style="float:right">

<?

$number = mysql_fetch_array(mysql_query("SELECT count(*) total FROM dados_indicacoes")); 

?>



 <form method="post" action="">


	<span style="margin-right:10px"> <strong>Total de envios:</strong> <?=$number['total']?></span>

<?php
if(!isset($_GET['pagina']) || $_GET['pagina']=='0'){
	$pagina = 1;
}else{
	$pagina = $_GET['pagina'];
}


//Valores pré-definidos para a busca.
if ($_POST) {
	$busca = $_POST["txtBusca"];
	$coluna = $_POST["selColuna"];
	
	header('Location: indicacoes.php?busca=' . $busca . '&coluna=' . $coluna . '&pagina=1' );
} 
?>
   <input name="txtBusca" type="text" id="txtBusca" style="width:160px" value="<?=$_GET["busca"]?>" />
   <select name="selColuna" id="selColuna">
   <option value="assinante" <?php echo selected( 'assinante', $_GET["coluna"] ); ?> >Assinante</option>
   <option value="data" <?php echo selected( 'data', $_GET["coluna"] ); ?> >Data</option>
   </select>
   <input type="submit" value="Pesquisar" />
   
 </form>



 </div>

  <div class="titulo" style="margin-bottom:10px;">Indicações feitas</div>
  <div style="clear:both"> </div>
  <table border="0" cellspacing="2" cellpadding="4" style="font-size:12px">
    <tr>
    <th align="center" width="236">Assinante</th>
    <th align="center" width="246">Nome indicado</th>
    <th align="center" width="280">Email indicado</th>
    <th align="center" width="80">Data</th>
    <th align="center" width="80">Ação</th>
    </tr>
<?php
//Paginação
$paginaAtual = (int)$pagina;
$quantidadeResultados = 100;
$camposExibidos = ($paginaAtual*$quantidadeResultados) - $quantidadeResultados; 

if ($_GET["busca"] != ""){
	if ($_GET["coluna"] == "assinante") {
		$resColuna = " AND l." . $_GET["coluna"] . " LIKE '%" . $_GET["busca"] . "%'";
	} else if ($_GET["coluna"] == "data") {
		$resColuna = " AND DATE(di." . $_GET["coluna"] . ") = '" . date('Y-m-d',mktime(0,0,0,substr($_GET["busca"],3,2),substr($_GET["busca"],0,2),substr($_GET["busca"],6,4))) . "'";
	} 
}

$sql = "SELECT l.id idUsuario, di.nome_amigo, l.assinante, di.id, di.email_amigo, di.data, di.premiada, case when isnull(l_indica.status) then '' else l_indica.status end status_indica, case when isnull(l_indica.id) then '' else l_indica.id end id_indica
FROM dados_da_empresa de INNER JOIN dados_indicacoes di ON de.id = di.idUser INNER JOIN login l ON di.idUser = l.id LEFT JOIN login l_indica ON di.email_amigo = l_indica.email
WHERE 1=1"
. $resColuna . 
" ORDER BY di.data DESC, l.assinante ASC LIMIT " . $camposExibidos . ", " . $quantidadeResultados;	

$resultado = mysql_query($sql)
or die (mysql_error());

$corLinha = "#FFF";

while ($linha=mysql_fetch_array($resultado)) {
?>
    <tr class="guiaTabela" style="background-color:<?=$corLinha?>">
        <td>
          <?
          echo '<div class="';
					switch($linha['premiada']){
						case "1":
						 echo 'bullets_indica_ativo';
						 $alt = 'Mensalidade premiada';
						break;
						default:
							echo 'bullets_indica_naoentrou';
						 $alt = 'Mensalidade não premiada';
						break;
					}
					echo '" title="'.$alt.'"></div>';
					?>
          <a href="cliente_administrar.php?id=<?=$linha["idUsuario"]?>" target="_blank"><?=$linha["assinante"]?></a></td>
        <td><?
        	if($linha['status_indica'] != ''){
						echo '<a href="cliente_administrar.php?id='.$linha['id_indica'].'">' . $linha["nome_amigo"] . '</a>';
					}else{
						echo $linha["nome_amigo"];
					}
				?>
        </td>
        <td><?
        	echo '<div class="';
					switch($linha['status_indica']){
						case "ativo":
						case "inativo":
						 echo 'bullets_indica_ativo';
						 $alt = 'Usuário Ativo / Inativo';
						break;
						case "demo":
						case "demoInativo":
						 echo 'bullets_indica_demo';
						 $alt = 'Usuário Demo / Demo Inativo';
						break;
						default:
							echo 'bullets_indica_naoentrou';
						 $alt = 'Usuário não cadastrado';
						break;
					}
					echo '" title="'.$alt.'"></div>' . $linha["email_amigo"]?></td>
        <td><?=date('d/m/Y',strtotime($linha["data"]))?></td>
        <td><a href="#" onClick="if(confirm('Você tem certeza que deseja excluir este registro?')){location.href='indicacoes_excluir.php?linha=<?=$linha["id"]?>'};">Excluir</a></td>
    </tr>
	<?php
	if ($corLinha == "#FFF") {
		$corLinha = "#E5E5E5";
	} else {
		$corLinha = "#FFF";
	} 
}
?>

</table>
<?php
//Consulta sem o limite para produzir o número de páginas
$sql = "SELECT di.nome_amigo, l.assinante, di.id, di.email_amigo, di.data
FROM dados_da_empresa de, dados_indicacoes di, login l
WHERE de.id = di.idUser AND di.idUser = l.id"
. $resColuna . $resAtivo .
" ORDER BY di.data DESC, l.assinante ASC";	

$resultado = mysql_query($sql)
or die (mysql_error());

$totalPesquisado = mysql_num_rows($resultado);

if($totalPesquisado > $quantidadeResultados) {
	echo "<br>";
	
	if($paginaAtual == 1) {
		echo 'anterior | ';
	} else {
		echo '<a href="indicacoes.php?busca=' . $busca . '&coluna=' . $coluna . '&status=' . $status . '&ordem=' . $_GET["ordem"] . '&pagina=' . ($paginaAtual - 1) . '">anterior</a> |';
	}
		
	for($i = 1; $i <= ceil($totalPesquisado / $quantidadeResultados); $i++) { 
		if($i == $paginaAtual) {
			echo ' '.$i.' |';
		} else {
			echo ' <a href="indicacoes.php?busca=' . $busca . '&coluna=' . $coluna . '&status=' . $status . '&ordem=' . $_GET["ordem"] . '&pagina=' . $i . '">' . $i . '</a> |';
		} 
	}
	
	if($paginaAtual == ceil($totalPesquisado / $quantidadeResultados)) {
		echo ' próxima';
	} else {
		echo ' <a href="indicacoes.php?busca=' . $busca . '&coluna=' . $coluna . '&status=' . $status . '&ordem=' . $_GET["ordem"] . '&pagina=' . ($paginaAtual + 1) . '">próxima</a>';
	}
}
?>
</div>

<?php include '../rodape.php' ?>
