	<?php
include "../conect.php";
session_start();

$codigo = $_REQUEST["idPergunta"];
$idUsuario = $_REQUEST['idUsuario'];


$getCodigoAnterior = mysql_fetch_array(mysql_query("SELECT max(idPostagem) idPostagem FROM suporte WHERE id = " . $idUsuario . " AND isnull(idPergunta)  AND idPostagem < " . $codigo . ""));

if(!is_null($getCodigoAnterior['idPostagem'])){

	$sql = "SELECT * FROM suporte WHERE idPostagem='" . $getCodigoAnterior['idPostagem'] . "' OR idPergunta='" . $getCodigoAnterior['idPostagem'] . "' ORDER BY idPostagem ASC";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	
	$texto_tabela = "";
	
	while ($linha=mysql_fetch_array($resultado)) { 
	
	$data = date('d/m/Y', strtotime($linha["ultimaResposta"])) .  " às " . date('H:i', strtotime($linha["ultimaResposta"]));
	$titulo = $linha["titulo"];
	$nome = explode(" ",$linha["nome"]);
	$status = $linha["status"];
	$id = $linha["id"];
	
		$arrNomeArquivo = explode("/",$linha["anexo"]);
	
	  
	$texto_tabela .= "<tr><td valign=\"top\"><strong>";
		if ($_REQUEST['nomeUser'] == $nome[0]) { 
			$texto_tabela .= "<a href=\"cliente_administrar.php?id=" . $id . "\" target=\"_blank\">" . $linha["nome"] . "</a>";
		} else {
			$texto_tabela .= $linha["nome"];
		}
		$texto_tabela .= "</strong><br /><em>" . date('d/m/Y', strtotime($linha["data"])) . ", às " . date('H:i', strtotime($linha["data"])) . "</em><br /><br />";
		if ($_REQUEST['nomeUser'] != $nome[0]) { 
			$texto_tabela .= "<a href=\"#\" onClick=\"if (confirm('Você tem certeza que deseja excluir esta mensagem?'))location.href='suporte_excluir_postagem.php?codigo=".$codigo."&linha=".$linha["idPostagem"]."';\"><img src=\"../images/del.png\" width=\"24\" height=\"23\" border=\"0\" title=\"Excluir\" /></a><a href=\"suporte_visualizar.php?codigo=".$codigo."&editar=".$linha["idPostagem"]."\"><img src=\"../images/edit.png\" width=\"24\" height=\"23\" border=\"0\" title=\"Editar\" /></a>";
		} 
		$texto_tabela .= "</td><td valign=\"top\">".urldecode($linha["mensagem"]);
		if(count($arrNomeArquivo)>1){
			$texto_tabela .= "<div style='clear:both'><em><b>Anexo: </b><a href=\"../" . $linha["anexo"] . "\" target=\"_blank\">" . $arrNomeArquivo[count($arrNomeArquivo)-1] . "</a></em></div>";
		}
		$texto_tabela .= "</td></tr>";
	}
	
	echo json_encode(array('data'=>$texto_tabela,'codigo'=>$getCodigoAnterior['idPostagem']));
}else{
	echo json_encode(array('data'=>"",'codigo'=>0));
}
?>