<?php 
include '../conect.php';
include '../session.php';
include 'check_login.php';

function selected( $value, $prev ){
   return $value==$prev ? ' selected="selected"' : ''; 
};

$pagina = ($_GET["pagina"] != '' ? (int)$_GET["pagina"] : 1);
?>
<?php include 'header.php' ?>

<div class="principal">

  <div class="titulo" style="margin-bottom:10px;">Perguntas e respostas FAQ</div>
  <div style="clear:both;height:10px;"> </div>

  <div style="float:left">
   <form method="GET" action="faq_lista.php">
  

     <input name="busca" type="text" id="busca" style="width:160px" value="<?=$_GET["busca"]?>" />
     <input type="submit" value="Pesquisar" />
     
   </form>
  </div>
  <div style="clear:both;height:10px;"> </div>


	<div style="width:598px;float:left;text-align:right;"><a href="faq_administrar.php">Cadastrar nova</a></div>
  <div style="clear:both;height:10px;"> </div>

  <table width="600" border="0" cellspacing="2" cellpadding="4" style="font-size:12px">
    <tr>
    <th width="488" align="center">Pergunta</th>
<!--    <th align="center" width="472">Resposta</th>-->
    <th width="90" align="center">Ação</th>
    </tr>
<?php
//Filtro referente aos status.

//Paginação
$paginaAtual = $pagina;
$quantidadeResultados = 500;
$camposExibidos = ($paginaAtual*$quantidadeResultados) - $quantidadeResultados; 

//Componente de pesquisa.
if ($_GET["busca"] != ""){
	$resColuna = " AND (pergunta LIKE '%" . $_GET["busca"] . "%')";
}
	
$sql = "SELECT * 
FROM faq
WHERE 1=1"
. $resColuna .
" ORDER BY pergunta, pergunta_ordem LIMIT " . $camposExibidos . ", " . $quantidadeResultados;	

$resultado = mysql_query($sql)
or die (mysql_error());

$corLinha = "#FFF";


//$entities = get_html_translation_table(HTML_ENTITIES);
//$arrFrom = array();
//$arrTo = array();
//foreach ($entities as $entity) {
//    //$new_entities[$entity] = htmlspecialchars($entity);
//		array_push($arrFrom,htmlspecialchars($entity));
//		array_push($arrTo,$entity);
//}

while ($linha=mysql_fetch_array($resultado)) {

//	$resposta = $linha["resposta"];
//	$resposta = str_replace('<p>','',$resposta);
//	$resposta = str_replace('</p>','',$resposta);
//	$resposta = str_replace('<br>','',$resposta);
//	$resposta = str_replace('<br />','',$resposta);
//	$resposta = str_replace('<br/>','',$resposta);
//	$resposta = mb_convert_encoding(html_entity_decode($resposta), 'UTF-8', 'WINDOWS-1252');

?>
<tr id="linha_<?=$linha["id_faq"]?>" class="guiaTabela" style="background-color:<?=$corLinha?>">
	<td><?=str_replace("<p>","",str_replace("</p>","",($linha["pergunta"])))?></td>
<!--	<td><?=(strlen($resposta) > 100 ? substr($resposta,0,100).'[...]' : $resposta)?></td>-->
  <td><a href="faq_administrar.php?id=<?=$linha["id_faq"]?>">Editar</a> | <a href="#" onClick="if(confirm('Você tem certeza que deseja excluir esta FAQ?')){location.href='faq_excluir.php?id=<?=$linha["id_faq"]?>'};">Excluir</a></td>
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
////Consulta sem o limite para produzir o número de páginas
//$sql = "SELECT * 
//FROM faq
//WHERE 1=1"
//. $resColuna;	
//
//$resultado = mysql_query($sql)
//or die (mysql_error());
//
//$totalPesquisado = mysql_num_rows($resultado);
//$busca = $_GET['busca'];
//if($totalPesquisado > $quantidadeResultados) {
//	echo "<br>";
//	
//	if($paginaAtual == 1) {
//		echo 'anterior | ';
//	} else {
//		echo '<a href="faq_lista.php?busca=' . $busca . '&pagina=' . ($paginaAtual - 1) . '">anterior</a> |';
//	}
//		
//	for($i = 1; $i <= ceil($totalPesquisado / $quantidadeResultados); $i++) { 
//		if($i == $paginaAtual) {
//			echo ' '.$i.' |';
//		} else {
//			echo ' <a href="faq_lista.php?busca=' . $busca . '&pagina=' . $i . '">' . $i . '</a> |';
//		} 
//	}
//	
//	if($paginaAtual == ceil($totalPesquisado / $quantidadeResultados)) {
//		echo ' próxima';
//	} else {
//		echo ' <a href="faq_lista.php?busca=' . $busca . '&pagina=' . ($paginaAtual + 1) . '">próxima</a>';
//	}
//}
?>
</div>
<?php
	
	// utilizado para Evento de âncora.  
	$id_linha = '0';
	
	// Pega o id da linha da tabela.
	if(isset($_SESSION['linha_id'])) {
		$id_linha = '#linha_'.$_SESSION['linha_id'];
		unset($_SESSION['linha_id']);
	}
?>

<script>

	//Evento de âncora - 05-05-2017 - átano.
	$(function(){
		
		var linha_id = '<?php echo $id_linha?>';
		
		if(linha_id){
			$('html, body').animate({scrollTop: $(linha_id).offset().top}, 400);
		}
		
	});
	
</script>

<?php include '../rodape.php' ?>
