<?php include 'header_restrita.php' ?>
<?
//unset($_SESSION['dataInicioLivroCaixaGraficos']);
//unset($_SESSION['dataFimLivroCaixaGraficos']);
?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<script>

function numdias(mes,ano) { // RETORNA O ULTIMO DIA DO MES
    if((mes<8 && mes%2==1) || (mes>7 && mes%2==0)) return 31;
    if(mes!=2) return 30;
    if(ano%4==0) return 29;
    return 28;
}

function somadias(data, dias) {
   data=data.split('/');
   diafuturo=parseInt(data[0])+dias;
   mes=parseInt(data[1]);
   ano=parseInt(data[2]);
   while(diafuturo>numdias(mes,ano)) {
       diafuturo-=numdias(mes,ano);
       mes++;
       if(mes>12) {
           mes=1;
           ano++;
       }
   }

   if(diafuturo<10) diafuturo='0'+diafuturo;
   if(mes<10) mes='0'+mes;

   return diafuturo+"/"+mes+"/"+ano;
}

var dataInicio,anoInicio,mesInicio,diaInicio,dataFim,anoFim,mesFim,diaFim = 0;

function dateDiff(dFim,dInicio,tipo){ // CALCULA A DIFERENÇA EM DIAS ENTRE DUAS DATAS
//	tipo = 0 - diferenca em dias / 	tipo = 1 - diferenca em meses
	switch(tipo){
		case 1:
			TIPO = 3600000 * 24 * 30;
		break;
		default:
			TIPO = 3600000 * 24;
		break;
	}
	
	d1 = new Date(dInicio);
	d2 = new Date(dFim);
	
	passed = Math.round((d2.getTime() - d1.getTime()) / TIPO);
	return passed;
	
}

	
function alterarPeriodo() {
	dataInicio = document.getElementById('DataInicio').value;
	anoInicio = dataInicio.substr(3,4);
	mesInicio = dataInicio.substr(0,2);
	diaInicio = '01';

	dataFim = document.getElementById('DataFim').value;
	anoFim = dataFim.substr(3,4);
	mesFim = dataFim.substr(0,2);
	diaFim = numdias(mesFim,anoFim);

	if(mesInicio > 12 || mesFim > 12){
		alert('Mês inválido. Corrija e tente novamente!');
		return false;
	}

	if((anoInicio < 1990 || anoInicio > 2050) || (anoFim < 1990 || anoFim > 2050)){
		alert('Ano inválido. Corrija e tente novamente!');
		return false;
	}

	diferenca = dateDiff('"' + anoFim+'-'+mesFim+'-'+diaFim+'"','"'+anoInicio+'-'+mesInicio+'-'+diaInicio+'"',1);
//alert(diferenca);
	if(diferenca < 0){
		alert("A data inicial deve ser maior que a data final.");
		document.getElementById('DataInicio').focus();
		return false;
	}

	if(diferenca > 12){
		alert("O período não deve ser superior a 1 ano.");
		document.getElementById('DataInicio').focus();
		return false;
	}
	
	window.location='livros_caixa_graficos.php?dataInicio='+anoInicio+'-'+mesInicio+'-'+diaInicio+'&dataFim='+anoFim+'-'+mesFim+'-'+diaFim;
}

</script>

<div class="principal">
<div>
<h1>Livro Caixa</h1>
</div>




<?php

function numdias($mes,$ano) { // RETORNA O ULTIMO DIA DO MES
    if(((int)$mes<8 && (int)$mes%2==1) || ((int)$mes>7 && (int)$mes%2==0)) return 31;
    if((int)$mes!=2) return 30;
    if((int)$ano%4==0) return 29;
    return 28;
}

function escreveMesAno($mesAno,$padrao = 1){
	
//	$padrao = nome por extenso (0) ou abreviado(1);
	
	$nome_mes = array(
		1=>array('Janeiro','Jan')
		,2=>array('Fevereiro','Fev')
		,3=>array('Março','Mar')
		,4=>array('Abril','Abr')
		,5=>array('Maio','Mai')
		,6=>array('Junho','Jun')
		,7=>array('Julho','Jul')
		,8=>array('Agosto','Ago')
		,9=>array('Setembro','Set')
		,10=>array('Outubro','Out')
		,11=>array('Novembro','Nov')
		,12=>array('Dezembro','Dez')
	);
	
	$mes = (int)substr($mesAno,0,2);
	$ano = (int)substr($mesAno,-4);
	
	return $nome_mes[$mes][$padrao];// . " - " . $ano;
	
}

$dataInicio = date('Y-m-d',mktime(0,0,0,'01','01',date('Y'))); // data inicial padrao é de 12 meses passados

$dataFim = date('Y-m-d',mktime(0,0,0,date('m'),numdias(date('m'),date('Y')),date('Y'))); // montando a data atual final mas pegando o ultimo dia do mes corrente

if(!isset($_SESSION['dataInicioLivroCaixaGraficos'])){
	if($_GET["dataInicio"]){
		$dataInicio = $_GET["dataInicio"];
	}
}else{
	if($_GET["dataInicio"] != $_SESSION["dataInicioLivroCaixaGraficos"] && isset($_GET["dataInicio"])){
		$dataInicio = $_GET["dataInicio"];
	}else{
		$dataInicio = $_SESSION["dataInicioLivroCaixaGraficos"];
	}
}

if(!isset($_SESSION['dataFimLivroCaixaGraficos'])){
	if($_GET["dataFim"]){
		$dataFim = $_GET["dataFim"];
	}
}else{
	if($_GET["dataFim"] != $_SESSION["dataFimLivroCaixaGraficos"] && isset($_GET["dataFim"])){
		$dataFim = $_GET["dataFim"];
	}else{
		$dataFim = $_SESSION["dataFimLivroCaixaGraficos"];
	}
}

$_SESSION['dataInicioLivroCaixaGraficos'] = $dataInicio;
$_SESSION['dataFimLivroCaixaGraficos'] = $dataFim;

$mesInicio = date('m',strtotime($dataInicio));
$anoInicio = date('Y',strtotime($dataInicio));

$mesFim = date('m',strtotime($dataFim));
$anoFim = date('Y',strtotime($dataFim));

?>

<h2>Gráficos de Movimentação</h2>
<div style="margin-bottom:20px">Nesta página você poderá observar a evolução de sua empresa ao longo do tempo, bem como identificar os gastos e as fontes de receitas mais relevantes. Os gráficos refletem os  lançamentos efetuados no<strong> livro-caixa </strong>(se ainda não tiver nenhum lançamento, você não conseguirá ver nada). Dica: para dividir as receitas por cliente, cadastre-os em <a href="meus_dados_clientes.php">cadastro de clientes</a>. Desta forma, ao fazer o lançamento de uma entrada no livro-caixa, eles aparecerão na lista de categorias e serão automaticamente exibidos no gráfico de Entradas quando algum lançamento for feito com eles.</div>
<form method="post" style="display:inline" action="Javascript:alterarPeriodo()">
<div style="float:left">Período de:  <input name="DataInicio" id="DataInicio" type="text" value="<?=date('m/Y',strtotime($dataInicio))?>" maxlength="7"  style="width:70px" class="campoDataMesAno" /> até: <input name="DataFim" id="DataFim" type="text" value="<?=date('m/Y',strtotime($dataFim))?>" maxlength="7"  style="width:70px" class="campoDataMesAno" /> <input name="Alterar" type="submit" value="Alterar Período" /></div>
</form>
<div style="clear:both; height:30px"> </div>

<?php

$menor_data = strtotime($dataInicio);
$maior_data = strtotime($dataFim);

$meses = number_format(($maior_data - $menor_data) / (3600 * 24 * 30),0);

while($meses > 0){ // rodando o loop a quantidade de meses do periodo

	if($a == ''){ // se a variavel de controle para o ano estiver vazia recebe o ano inicial
		$a = $anoInicio;
	}
	if($m == ''){ // se a variavel de controle para o mes estiver vazia recebe o mes inicial
		$m = $mesInicio;
	}
	
	// montando a query que fará a consulta somando as entradas e saídas no périodo
	$sql = "SELECT CONCAT(YEAR(data),LPAD(MONTH(data),2,'0')) mesAno, SUM(entrada) entrada, SUM(saida) saida FROM user_" . $_SESSION['id_empresaSecao'] . "_livro_caixa WHERE data BETWEEN '" . $a . "-" . str_pad($m,2,'0',STR_PAD_LEFT) . "-" . "01" . "' AND '" . $a . "-" . str_pad($m,2,'0',STR_PAD_LEFT) . "-" . numdias($m,$a) . "' GROUP BY 1 ORDER BY data, id ASC";
	$resultado_line = mysql_query($sql)
	or die (mysql_error());

	// se houver resultado monta a string que será utilizada na montagem do gráfico
	if($dados_line=mysql_fetch_array($resultado_line)){
		
		$dadosGraficoLinha .= "['" . escreveMesAno(str_pad(substr($dados_line['mesAno'],4,2),2,'0',STR_PAD_LEFT).substr($dados_line['mesAno'],0,4),1) . "'," . $dados_line['entrada'] . ",'R$ " . number_format($dados_line['entrada'],2,",",".") . "'," . $dados_line['saida'] . ",'R$ " . number_format($dados_line['saida'],2,",",".") . "'],";

	// se não houver resultado monta a string zerada
	}else{

		$dadosGraficoLinha .= "['" . escreveMesAno(str_pad($m,2,'0',STR_PAD_LEFT).$a,1) . "',0.0,'R$ " . number_format('0.0',2,",",".") . "',0.0,'R$ " . number_format('0.0',2,",",".") . "'],";

	}

	if($m==12){
		$a++;
		$m = 0;
	}
	$m++;
	
	$meses--;
	
}


// DADOS PARA GRAFICOS DE BARRA
//$sql = "SELECT categoria, SUM(entrada) entrada, SUM(saida) saida FROM user_" . $_SESSION['id_empresaSecao'] . "_livro_caixa WHERE data BETWEEN '".$dataInicio."' AND '".$dataFim."' AND categoria != 'Outros' GROUP BY 1 ORDER BY categoria, id ASC";
$sql = "SELECT categoria, SUM(entrada) entrada, SUM(saida) saida FROM user_" . $_SESSION['id_empresaSecao'] . "_livro_caixa WHERE data BETWEEN '".$dataInicio."' AND '".$dataFim."' GROUP BY 1 ORDER BY categoria, id ASC";

$resultado = mysql_query($sql)
or die (mysql_error());

$total = mysql_num_rows($resultado);

$countEntradas = 0;
$countSaidas  = 0;

while ($dados_barra=mysql_fetch_array($resultado)) {
	if($dados_barra['entrada'] > 0){
		$countEntradas++;
		$arrEntradas[$countEntradas] = array("'" . $dados_barra['categoria'] . "'",$dados_barra['entrada'],'opacity:0.5');
	}

	if($dados_barra['saida'] > 0){
		$countSaidas++;
		$arrSaidas[$countSaidas] = array("'" . $dados_barra['categoria'] . "'",$dados_barra['saida'],'opacity:0.5');
	}
	
}

?>
<script type="text/javascript">
	google.load("visualization", "1", {packages:["corechart"]});
	google.setOnLoadCallback(drawLineChart);
	google.setOnLoadCallback(drawBarChartEntradas);
	google.setOnLoadCallback(drawBarChartSaidas);
	function drawLineChart() {
		//var data = google.visualization.arrayToDataTable([
		var data = new google.visualization.DataTable();
		data.addColumn({type:'string',label:'Data',role:'domain'}); // Implicit domain column.
		data.addColumn({type:'number',label:'Entradas'}); // Implicit data column.
		data.addColumn({type:'string', role:'tooltip'});
		data.addColumn({type:'number',label:'Saídas'}); // Implicit data column.
		data.addColumn({type:'string', role:'tooltip'});
		data.addRows([
			 
//	['DATA', 'ENTRADAS', null, 'SAÍDAS', null],
			<?
				echo substr($dadosGraficoLinha,0,strlen($dadosGraficoLinha)-1);
			?>
		]);

		var options = {
			title: '',
			titlePosition: 'none',
			hAxis: {title: '',  titleTextStyle: {color: '#333'}},
			legend: { position: "none" },
			vAxis: {minValue: 0},
			colors:['#006699','#990000'],
//		focusTarget:'category',
			fontSize:10,
			chartArea:{left:60,top:50,width:"850",height:"320"}
		};

		var chart = new google.visualization.AreaChart(document.getElementById('line_chart_div'));
		chart.draw(data, options);
	}
	
	function drawBarChartEntradas() {
//	var data = google.visualization.arrayToDataTable([
		var data = new google.visualization.DataTable();
		data.addColumn({type:'string',label:'Data',role:'domain'}); // Implicit domain column.
		data.addColumn({type:'number',label:'Entradas'}); // Implicit data column.
		data.addColumn({type:'string', role:'tooltip'});
		data.addColumn({type:'string', role:'style'});
		data.addRows([
		//			['Categoria', 'ENTRADAS', { role: 'annotation' }, { role: 'style' }],
			<?
			for($i=1;$i<=count($arrEntradas);$i++){
				echo "[" . $arrEntradas[$i][0] . "," . $arrEntradas[$i][1] . ",'R$ " . number_format((float)$arrEntradas[$i][1],2,",",".") . "','" . $arrEntradas[$i][2] . "']";
				if($i < count($arrEntradas)){
					echo ","."\n";
				}
			}
			?>
		]);

		var options = {
			title: '',
			titlePosition: 'none',
			hAxis: {title: '',  titleTextStyle: {color: '#333'}},
			legend: { position: "none" },
			vAxis: {minValue: 0},
			vAxis: {minValue: 0},
			colors:['#006699','#990000'],
//			focusTarget:'category',
			fontSize:10,
			chartArea:{left:80,top:50,width:"850",height:"320"}
		};

		var chart = new google.visualization.ColumnChart(document.getElementById('entradas_bar_chart_div'));
		chart.draw(data, options);
	}

	function drawBarChartSaidas() {
//		var data = google.visualization.arrayToDataTable([
		var data = new google.visualization.DataTable();
		data.addColumn({type:'string',label:'Data',role:'domain'}); // Implicit domain column.
		data.addColumn({type:'number',label:'Saídas'}); // Implicit data column.
		data.addColumn({type:'string', role:'tooltip'});
		data.addColumn({type:'string', role:'style'});
		data.addRows([
		//			['Categoria', 'SAIDAS', { role: 'annotation' }, { role: 'style' }],
			<?
			for($i=1;$i<=count($arrSaidas);$i++){
				echo "[" . $arrSaidas[$i][0] . "," . $arrSaidas[$i][1] . ",'R$ " . number_format((float)$arrSaidas[$i][1],2,",",".") . "','" . $arrSaidas[$i][2] . "']";
				if($i < count($arrSaidas)){
					echo ","."\n";
				}
			}
			?>
		]);

		var options = {
			title: '',
			titlePosition: 'none',
			hAxis: {title: '',  titleTextStyle: {color: '#333'}},
			legend: { position: "none" },
			vAxis: {minValue: 0},
			vAxis: {minValue: 0},
			colors:['#990000'],
//			focusTarget:'category',
			fontSize:10,
			chartArea:{left:100,top:50,width:"850",height:"320"}
		};

		var chart = new google.visualization.ColumnChart(document.getElementById('saidas_bar_chart_div'));
		chart.draw(data, options);
	}

</script>

		<div style="font-size: 16px;color:#000;font-weight:bold;margin-bottom:10px;">Entradas e Saídas por período de <?=date('m/Y',strtotime($dataInicio))?> a <?=date('m/Y',strtotime($dataFim))?></div>
    <div id="line_chart_div" style="width:963px; height: 400px; border: 1px solid #ccc;"></div>
		<div style="width:650px;height:12px;clear:both;margin-bottom:20px;color:#000;font-size:10px;">
    	<div style="float:right;margin-top:10px;">
        <div style="position:relative;float:left;margin-left:2px;width:10px;height:10px;background-color:#006699;"></div>
        <div style="position:relative;float:left;margin-left:2px;">Entrada</div>
        <div style="position:relative;float:left;margin-left:10px;width:10px;height:10px;background-color:#990000;"></div>
        <div style="position:relative;float:left;margin-left:2px;">Saída</div>
      </div>
    </div>
		<div style="clear:both;margin-bottom:20px;"></div>
    
		<div style="font-size: 16px;color:#000;font-weight:bold;margin-bottom:10px;">Entradas por categoria</div>
    <div id="entradas_bar_chart_div" style="width:963px; height: 450px; border: 1px solid #ccc;"></div>
		<div style="clear:both;margin-bottom:30px;"></div>

		<div style="font-size: 16px;color:#000;font-weight:bold;margin-bottom:10px;">Saídas por categoria</div>
    <div id="saidas_bar_chart_div" style="width:963px; height: 470px; border: 1px solid #ccc;"></div>


</div>
</div>


<?php include 'rodape.php' ?>