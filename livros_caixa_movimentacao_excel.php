<?php 
session_start();
include 'conect.php';
//include 'session.php';
//include 'check_login.php' ;

//echo "SELECT * FROM user_" . $_SESSION['id_empresaSecao'] . "_livro_caixa WHERE data BETWEEN '" . $_GET["dataInicio"] . "' AND '" . $_GET["dataFim"] . "' ORDER BY data, id ASC";
//exit;

$fileName = 'livroCaixa_' . date('Ymd', strtotime($_GET["dataInicio"])).'_a_'.date('Ymd', strtotime($_GET["dataFim"]));

//header("Content-Type: application/ms-excel; charset=utf-8");
//header("Content-type: application/octet-stream; charset=utf-8");
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
//header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename='".$fileName.".xls'"); 
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);


//header("Content-type: application/vnd.ms-excel");
//header("Content-type: application/force-download");
//header("Content-Disposition: attachment; filename=".$fileName.".xls");
header("Pragma: no-cache");

$html = '<body><table>';
$html .= '<tr>';
$html .= '<td colspan="3" align="left">';
$html .= '<strong>Empresa:</strong> '.utf8_decode($_SESSION["nome_userSecao"]);
$html .= '</td>';
$html .= '<td colspan="3" align="left">';
$html .= '<strong>'.utf8_decode("Período").':</strong> '.date('d/m/Y', strtotime($_GET["dataInicio"])).' a '.date('d/m/Y', strtotime($_GET["dataFim"]));
$html .= '</td>';
$html .= '</tr>';
$html .= '<thead>';
$html .= '<tr>';
$html .= '<th style="text-align:center;font-weight:bold;border:1px solid #000">Data</th>';
$html .= '<th style="text-align:center;font-weight:bold;border:1px solid #000">'.utf8_decode("Documento nº").'</th>';
$html .= '<th style="text-align:center;font-weight:bold;border:1px solid #000" width="400">'.utf8_decode("Descrição").'</th>';
$html .= '<th style="text-align:center;font-weight:bold;border:1px solid #000">Entrada</th>';
$html .= '<th style="text-align:center;font-weight:bold;border:1px solid #000">'.utf8_decode("Saída").'</th>';
$html .= '<th style="text-align:center;font-weight:bold;border:1px solid #000">Saldo</th>';
$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody>';

$sql = "SELECT * FROM user_" . $_SESSION['id_empresaSecao'] . "_livro_caixa WHERE data < '" . $_GET["dataInicio"] . "' ORDER BY data, id ASC";
$resultado = mysql_query($sql)
or die (mysql_error());

while ($linha=mysql_fetch_array($resultado)) {
	$entrada = $linha["entrada"];
	$saida = $linha["saida"];
	$saldo = $saldo + $entrada - $saida;
}

$sql = "SELECT * FROM user_" . $_SESSION['id_empresaSecao'] . "_livro_caixa WHERE data BETWEEN '" . $_GET["dataInicio"] . "' AND '" . $_GET["dataFim"] . "' ORDER BY data, id ASC";
$resultado = mysql_query($sql)
or die (mysql_error());
while ($linha=mysql_fetch_array($resultado)) {
	
$descricao = trataProlabore($linha["categoria"], $linha["descricao"]);
	
	$saldo = $saldo + $linha["entrada"] - $linha["saida"];
$html .= '<tr>';
$html .= '<td style="text-align:left;border:1px solid #000" valign="top">'.date('d/m/Y', strtotime($linha["data"])).'</td>';
$html .= '<td style="text-align:left;border:1px solid #000" valign="top">'.utf8_decode($linha["documento_numero"]).'</td>';
$html .= '<td style="text-align:left;border:1px solid #000" valign="top">'.utf8_decode($descricao).'</td>';
$html .= '<td style="text-align:right;border:1px solid #000" valign="top">'.number_format($linha["entrada"],2,",",".").'</td>';
$html .= '<td style="text-align:right;border:1px solid #000" valign="top">'.number_format($linha["saida"],2,",",".").'</td>';
$html .= '<td style="text-align:right;border:1px solid #000" valign="top">'.number_format($saldo,2,",",".").'</td>';
$html .= '</tr>';
}
$html .= '</tbody>';
$html .= '</table></body>';

echo $html;

// Método criado para realizar o tratamento da categoria caso seja um Pró-labore, pois e necessario verificar o id do Sócio. 01-03-2017.
function trataProlabore($categoria, $descricao){
	
	if( $categoria == 'Pró-Labore' && is_numeric($descricao)){
														
		$qry = "SELECT * FROM dados_do_responsavel WHERE idSocio = '".$descricao."' AND id = '".$_SESSION['id_empresaSecao']."' ";
		
		$consulta = mysql_query($qry);
		
		$objeto_consulta = mysql_fetch_array($consulta);
		return $objeto_consulta['nome'];
	} else {
		return $descricao;
	}
}