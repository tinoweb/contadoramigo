<?php 
include "conect.php";


$data = $_GET["data"];
$dataInicio = $_GET["dataInicio"];
$dataFim = $_GET["dataFim"];
$tipo = $_GET["tipo"];
$ID = $_GET["id"];

if($data != '' && $dataInicio == ''){
// clique nos icones de boleto ou cartão
	$yData = substr($data, 6, 4);
	$mData = substr($data, 3, 2);
	$dData = substr($data, 0, 2);
	
	if($tipo == 'boleto'){
		$vDataIni = date("Y-m-d",mktime(0,0,0,$mData,$dData,$yData));
		$vDataFim = date("Y-m-d",mktime(0,0,0,$mData,$dData,$yData));
		$resultadoAcao = '1.2';
	}else{
		$vDataIni = date("Y-m-d",mktime(0,0,0,$mData,$dData-30-1,$yData));
		$vDataFim = date("Y-m-d",mktime(0,0,0,$mData,$dData-30+1,$yData));
		$resultadoAcao = '2.1';
	}
}else{
// clique no filtro da janela
	$yDataI = substr($dataInicio, 6, 4);
	$mDataI = substr($dataInicio, 3, 2);
	$dDataI = substr($dataInicio, 0, 2);

	$yDataF = substr($dataFim, 6, 4);
	$mDataF = substr($dataFim, 3, 2);
	$dDataF = substr($dataFim, 0, 2);

	$vDataIni = date("Y-m-d",mktime(0,0,0,$mDataI,$dDataI,$yDataI));
	$vDataFim = date("Y-m-d",mktime(0,0,0,$mDataF,$dDataF,$yDataF));

	if($tipo == 'boleto'){
		$resultadoAcao = '1.2';
	}else{
		$resultadoAcao = '2.1';
	}
	
}


$sql="SELECT * FROM relatorio_cobranca rel INNER JOIN dados_da_empresa emp ON rel.id = emp.id WHERE rel.data BETWEEN '" . $vDataIni . "' AND '" . $vDataFim . "' AND rel.emissao_NF = 1 AND (rel.numero_NF <> 0 AND rel.numero_NF <> '') AND rel.resultado_acao = '" . $resultadoAcao . "'";

$resultado = mysql_query($sql)
or die (mysql_error());

$result = '
	<table border="0" cellspacing="2" cellpadding="4" style="font-size: 11px; margin-bottom: 20px;">
    <thead>
        <tr>
            <th width="20" style="font-size: 12px;"></th>
            <th width="40" style="font-size: 12px;">Dia</th>
            <th width="40" style="font-size: 12px;">Nº NF</th>
            <th width="160" style="font-size: 12px;">CNPJ</th>
        </tr>
    </thead>
    <tbody>
';

while($linha = mysql_fetch_array($resultado)){
	$result .= '
        <tr>
            <td class="td_calendario"><input type="checkbox" name="chkNF" value="'.$linha['numero_NF'].'"></td>
            <td class="td_calendario">'.date('d',strtotime($linha['data'])).'</td>
            <td class="td_calendario">'.$linha['numero_NF'].'</td>
            <td class="td_calendario">'.$linha['cnpj'].'</td>
        </tr>
	';
}
$result .= '
    </tbody>
    </table>
';
echo $result;
?>