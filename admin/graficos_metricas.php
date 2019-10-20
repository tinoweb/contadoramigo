<?php 

include 'header.php';

//include '../conect.php';

include '../session.php';

include 'check_login.php';

$sqlExcluiTestes = " AND l.id NOT IN (9,1581, 4093, 6857, 6905, 6958, 6835, 6858, 6764, 7072, 6870, 7077, 7075, 7004, 7045, 6963, 6869, 6963, 6787, 6614, 6576, 6742, 6613)";

// ATUALIZA OS TOTASIS DE STATUS DE LOGIN DO BANCO DE DADOS DESTE DIA
$result_cobranca_dia = mysql_query("select * from totalizador_status_login where DATE_FORMAT(data,'%Y-%m-%d') = '" . date('Y-m-d',mktime(0,0,0,date('m'),date('d'),date('Y'))) . "'");
if(mysql_num_rows($result_cobranca_dia) > 0){
	mysql_query("UPDATE totalizador_status_login SET
	total_ativos =(select count(*) from login l WHERE l.status = 'ativo' " . $sqlExcluiTestes . " AND l.id = l.idUsuarioPai)
	, total_inativos = (select count(*) from login l WHERE l.status = 'inativo' " . $sqlExcluiTestes . " AND l.id = l.idUsuarioPai)
	, total_demos = (select count(*) from login l WHERE l.status = 'demo' " . $sqlExcluiTestes . " AND l.id = l.idUsuarioPai)
	, total_demoInativos = (select count(*) from login l WHERE l.status = 'demoInativo' " . $sqlExcluiTestes . " AND l.id = l.idUsuarioPai)
	, total_cancelados = (select count(*) from login l WHERE l.status = 'cancelado' " . $sqlExcluiTestes . " AND l.id = l.idUsuarioPai))
	WHERE DATE_FORMAT(data,'%Y-%m-%d') = '" . date('Y-m-d',mktime(0,0,0,date('m'),date('d'),date('Y'))) . "'");
}else{
	mysql_query("insert into totalizador_status_login (total_ativos,total_inativos,total_demos,total_demoInativos,total_cancelados)
	select
	sum(case when l.status = 'ativo' then 1 else 0 end) total_ativos
	, sum(case when l.status = 'inativo' then 1 else 0 end) total_inativos
	, sum(case when l.status = 'demo' then 1 else 0 end) total_demos
	, sum(case when l.status = 'demoInativo' then 1 else 0 end) total_demoInstivos
	, sum(case when l.status = 'cancelado' then 1 else 0 end) total_cancelados
	from login l WHERE 1=1 " . $sqlExcluiTestes . " AND l.id = l.idUsuarioPai");	
}

//unset($_SESSION['dataInicioGraficosMetrica']);
//unset($_SESSION['dataFimGraficosMetrica']);

//echo date("Y-m-d H:i:s");

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
	anoInicio = dataInicio.substr(6,4);
	mesInicio = dataInicio.substr(3,2);
	diaInicio = dataInicio.substr(0,2);

	dataFim = document.getElementById('DataFim').value;
	anoFim = dataFim.substr(6,4);
	mesFim = dataFim.substr(3,2);
	diaFim = dataFim.substr(0,2);

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
	
	window.location='graficos_metricas.php?dataInicio='+anoInicio+'-'+mesInicio+'-'+diaInicio+'&dataFim='+anoFim+'-'+mesFim+'-'+diaFim;
}

</script>

<div class="principal">
<div>
	<div class="titulo" style="margin-bottom:10px;">Métricas</div>
	<div style="clear:both"> </div>
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
	
//	$mes = (int)substr($mesAno,0,2);
//	$ano = (int)substr($mesAno,-4);
	$mes = (int)substr($mesAno,-2);
	$ano = (int)substr($mesAno,0,4);
	
	return $nome_mes[$mes][$padrao];// . " - " . $ano;
	
}

//$_SESSION['dataInicioGraficosMetrica'] = "";
//$_SESSION['dataFimGraficosMetrica'] = "";

$dataInicio = date('Y-m-d',mktime(0,0,0,date('m'),date('d')-30,date('Y'))); // data inicial padrao é de 12 meses passados

//$dataFim = date('Y-m-d',mktime(0,0,0,date('m'),numdias(date('m'),date('Y')),date('Y'))); // montando a data atual final mas pegando o ultimo dia do mes corrente
$dataFim = date('Y-m-d',mktime(0,0,0,date('m'),date('d'),date('Y'))); // montando a data atual final 

if(!isset($_SESSION['dataInicioGraficosMetrica'])){
	if($_GET["dataInicio"]){
		$dataInicio = $_GET["dataInicio"];
	}
}else{
	if($_GET["dataInicio"] != $_SESSION["dataInicioGraficosMetrica"] && isset($_GET["dataInicio"])){
		$dataInicio = $_GET["dataInicio"];
	}else{
		$dataInicio = $_SESSION["dataInicioGraficosMetrica"];
	}
}

if(!isset($_SESSION['dataFimGraficosMetrica'])){
	if($_GET["dataFim"]){
		$dataFim = $_GET["dataFim"];
	}
}else{
	if($_GET["dataFim"] != $_SESSION["dataFimGraficosMetrica"] && isset($_GET["dataFim"])){
		$dataFim = $_GET["dataFim"];
	}else{
		$dataFim = $_SESSION["dataFimGraficosMetrica"];
	}
}

$_SESSION['dataInicioGraficosMetrica'] = $dataInicio;
$_SESSION['dataFimGraficosMetrica'] = $dataFim;

$diaInicio = date('d',strtotime($dataInicio));
$mesInicio = date('m',strtotime($dataInicio));
$anoInicio = date('Y',strtotime($dataInicio));

$diaFim = date('d',strtotime($dataFim));
$mesFim = date('m',strtotime($dataFim));
$anoFim = date('Y',strtotime($dataFim));

?>

<form method="post" style="display:inline" action="Javascript:alterarPeriodo()">
<div style="float:left">Período de:  <input name="DataInicio" id="DataInicio" type="text" value="<?=date('d/m/Y',strtotime($dataInicio))?>" maxlength="10"  style="width:80px" class="campoData" /> até: <input name="DataFim" id="DataFim" type="text" value="<?=date('d/m/Y',strtotime($dataFim))?>" maxlength="10"  style="width:80px" class="campoData" /> <input name="Alterar" type="submit" value="Alterar Período" /></div>
</form>
<div style="clear:both; height:30px"> </div>

<?php

$dadosGraficoLinha="";
$dadosGraficoLinhaTotais="";

$menor_data = strtotime($dataInicio);
$maior_data = strtotime($dataFim);

$meses = number_format(($maior_data - $menor_data) / (3600 * 24 * 30),0);

$dias = number_format(($maior_data - $menor_data) / (3600 * 24),0);

$total_abandonou = 0;
$total_nao_convertido = 0;
$total_cancelado = 0;
$total_recuperado = 0;
$total_assinatura = 0;
$total_ativado = 0;


$total_ativos = 0;
$total_inativos = 0;
$total_demos = 0;
$total_demoInativos = 0;
$total_cancelados = 0;


while($dias >= 0){ // rodando o loop a quantidade de meses do periodo

	if($a == ''){ // se a variavel de controle para o ano estiver vazia recebe o ano inicial
		$a = (int)$anoInicio;
	}
	if($m == ''){ // se a variavel de controle para o mes estiver vazia recebe o mes inicial
		$m = (int)$mesInicio;
	}
	if($d == ''){ // se a variavel de controle para o mes estiver vazia recebe o mes inicial
		$d = (int)$diaInicio;
	}
		
	// montando a query que fará a consulta somando as ocorrencias do periodo
	// CONCAT(YEAR(data) , LPAD(MONTH(data),2,'0') ) data 
	$sql = "SELECT data 
			, sum(case when status = 'abandonou' then 1 else 0 end) total_abandonou 
			, sum(case when status = 'não convertido' then 1 else 0 end) total_nao_convertido 
			, sum(case when status = 'cancelado' then 1 else 0 end) total_cancelado
			, sum(case when status = 'recuperado' then 1 else 0 end) total_recuperado
			, sum(case when status = 'assinatura' then 1 else 0 end) total_assinatura
			, sum(case when status = 'ativado' then 1 else 0 end) total_ativado
			FROM metricas_conversao 
			WHERE data = '" . $a . "-" . str_pad($m,2,'0',STR_PAD_LEFT) . "-" . str_pad($d,2,'0',STR_PAD_LEFT) . "'
			GROUP BY 1
			ORDER BY data";
			//WHERE data BETWEEN '" . $a . "-" . str_pad($m,2,'0',STR_PAD_LEFT) . "-" . "01" . "' AND '" . $a . "-" . str_pad($m,2,'0',STR_PAD_LEFT) . "-" . numdias($m,$a) . "'
//echo $sql . "<BR>";

//	$sql = "SELECT CONCAT(YEAR(data),LPAD(MONTH(data),2,'0')) mesAno, SUM(entrada) entrada, SUM(saida) saida FROM user_" . $_SESSION['id_userSecao'] . "_livro_caixa WHERE data BETWEEN '" . $a . "-" . str_pad($m,2,'0',STR_PAD_LEFT) . "-" . "01" . "' AND '" . $a . "-" . str_pad($m,2,'0',STR_PAD_LEFT) . "-" . numdias($m,$a) . "' GROUP BY 1 ORDER BY data, id ASC";
	$resultado_line = mysql_query($sql)
	or die (mysql_error());

	if(mysql_num_rows($resultado_line) > 0){
		// se houver resultado monta a string que será utilizada na montagem do gráfico
		$dados_line=mysql_fetch_array($resultado_line);

		$total_abandonou = $dados_line['total_abandonou'];
		$total_nao_convertido = $dados_line['total_nao_convertido'];
		$total_cancelado = $dados_line['total_cancelado'];
		$total_recuperado = $dados_line['total_recuperado'];

		$total_ativado = $total_recuperado + $dados_line['total_ativado'];

		$total_assinatura = $dados_line['total_assinatura'];


		$dadosGraficoLinha .= "[new Date(" . substr($dados_line['data'],0,4) . "," . ((int)substr($dados_line['data'],5,2) - 1) . "," . (int)substr($dados_line['data'],8,2) . ")"
				. "," . $total_abandonou// . ",'Abandonos: " . $dados_line['total_abandonou'] . "'"
				. "," . $total_nao_convertido// . ",'Não Convertidos: " . $dados_line['total_nao_convertido'] . "'"
//				. "," . $total_cancelado// . ",'Cancelados: " . $dados_line['total_cancelado'] . "'"
				. "," . $total_ativado// . ",'Ativados: " . $dados_line['total_ativado'] . "'"
//				. "," . $total_recuperado// . ",'Recuparados: " . $dados_line['total_recuperado'] . "'"
				. "," . $total_assinatura// . ",'Assinaturas: " . $dados_line['total_assinatura'] . "'"
				. "]," . "\r\n";

	// se não houver resultado monta a string zerada
	}else{

		$total_abandonou = 0;
		$total_nao_convertido = 0;
//		$total_cancelado = 0;
//		$total_recuperado = 0;
		$total_assinatura = 0;
		$total_ativado = 0;
		
		//$dadosGraficoLinha .= "['" . escreveMesAno($a.str_pad($m,2,'0',STR_PAD_LEFT),1) . "',0,'Abandonos: 0',0,'Recuparados: 0',0,'Assinaturas: 0',0,'Ativados: 0',0,'Não Convertidos: 0',0,'Cancelados: 0'],";
		$dadosGraficoLinha .= "[new Date(" . $a . "," . ((int)str_pad($m,2,'0',STR_PAD_LEFT) - 1) . "," . (int)str_pad($d,2,'0',STR_PAD_LEFT) . ")"
				. "," . $total_abandonou// . ",'Abandonos: " . $dados_line['total_abandonou'] . "'"
				. "," . $total_nao_convertido// . ",'Não Convertidos: " . $dados_line['total_nao_convertido'] . "'"
//				. "," . $total_cancelado// . ",'Cancelados: " . $dados_line['total_cancelado'] . "'"
				. "," . $total_ativado// . ",'Ativados: " . $dados_line['total_ativado'] . "'"
//				. "," . $total_recuperado// . ",'Recuparados: " . $dados_line['total_recuperado'] . "'"
				. "," . $total_assinatura// . ",'Assinaturas: " . $dados_line['total_assinatura'] . "'"
				. "]," . "\r\n";

	}
	
	
	
		// montando a query dos status do periodo
		//CONCAT(YEAR(data) , LPAD(MONTH(data),2,'0') ) data 
	$sqlTotais = "SELECT DATE_FORMAT(data,'%Y-%m-%d') data
			, total_ativos
			, total_inativos
			, total_demos
			, total_demoInativos
			, total_cancelados
			FROM totalizador_status_login  
			WHERE DATE_FORMAT(data,'%Y-%m-%d') = '" . $a . "-" . str_pad($m,2,'0',STR_PAD_LEFT) . "-" . str_pad($d,2,'0',STR_PAD_LEFT) . "'
			ORDER BY data";
	//echo $sqlTotais;
	$resultado_line_totais = mysql_query($sqlTotais)
	or die (mysql_error());
	if(mysql_num_rows($resultado_line_totais) > 0){
		
		$dados_line_totais=mysql_fetch_array($resultado_line_totais);

		$total_ativos = $dados_line_totais['total_ativos'];
		$total_inativos = $dados_line_totais['total_inativos'];
		$total_demos = $dados_line_totais['total_demos'];
		$total_demoInativos = $dados_line_totais['total_demoInativos'];
		$total_cancelados = $dados_line_totais['total_cancelados'];

		$dadosGraficoLinhaTotais .= "[new Date(" . substr($dados_line_totais['data'],0,4) . "," . ((int)substr($dados_line_totais['data'],5,2) - 1) . "," . (int)substr($dados_line_totais['data'],8,2) . ")"
				. "," . $total_ativos// . ",'Ativos: " . $dados_line_totais['total_ativos'] . "'"
				. "," . $total_demos// . ",'Demos: " . $dados_line_totais['total_demos'] . "'"
				. "]," . "\r\n";

	}else{
		//$dadosGraficoLinhaTotais .= "['" . escreveMesAno($a.str_pad($m,2,'0',STR_PAD_LEFT),1) . "',0,'Ativos: 0',0,'Demos: 0'],";
		$dadosGraficoLinhaTotais .= "[new Date(" . $a . "," . ((int)str_pad($m,2,'0',STR_PAD_LEFT) - 1) . "," . (int)str_pad($d,2,'0',STR_PAD_LEFT) . ")"
				. "," . $total_ativos// . ",'Ativos: " . $dados_line_totais['total_ativos'] . "'"
				. "," . $total_demos// . ",'Demos: " . $dados_line_totais['total_demos'] . "'"
				. "]," . "\r\n";

	}





//echo "MES: " . $m . " DIA: " . $d . " ANO: " . $a . "<BR>";
	if(
		(
			(
				($m==1) || ($m==3) || ($m==5) || ($m==7) || ($m==8) || ($m==10) || ($m==12)
			) 
			&& $d == 31
		)
		|| 
		(
			(
			($m==4) || ($m==6) || ($m==9) || ($m==11)
			)
			&& $d == 30
		)
		||
		(
			(
				($m==2) && ($a % 4 == 0)
			)
			&& $d == 29
		)
		||
		(
			(
				($m==2) && ($a % 4 != 0)
			)
			&& $d == 28
		)
	){
		$d = 0;
		if($m==12){
			$a++;
			$m = 0;
		}
		$m++;
	}
	$d++;
	
//	$meses--;
	$dias--;
	
}


?>
<script type="text/javascript">
	google.load("visualization", "1", {packages:["corechart"]});
	google.setOnLoadCallback(drawLineChart);
	google.setOnLoadCallback(drawLineChartTotais);
//	google.setOnLoadCallback(drawLineChartTotais);
//	google.load("visualization", "1", {packages:["annotatedtimeline"]});


	
	function drawLineChart() {
		//var data = google.visualization.arrayToDataTable([
		var data = new google.visualization.DataTable();
		data.addColumn({type:'date',label:'Data',role:'domain'}); // Implicit domain column.
		data.addColumn({type:'number',label:'Abandonos'}); // Implicit data column.
//		data.addColumn({type:'string', role:'tooltip'});
		data.addColumn({type:'number',label:'Não Convertidos'}); // Implicit data column.
//		data.addColumn({type:'string', role:'tooltip'});
//		data.addColumn({type:'number',label:'Cancelados'}); // Implicit data column.
//		data.addColumn({type:'string', role:'tooltip'});
		data.addColumn({type:'number',label:'Ativados'}); // Implicit data column.
//		data.addColumn({type:'string', role:'tooltip'});
//		data.addColumn({type:'number',label:'Recuperados'}); // Implicit data column.
//		data.addColumn({type:'string', role:'tooltip'});
		data.addColumn({type:'number',label:'Iniciou Demo'}); // Implicit data column.
//		data.addColumn({type:'string', role:'tooltip'});
		data.addRows([
		
			<?
echo substr($dadosGraficoLinha,0,strlen($dadosGraficoLinha)-1);
			?>
		]);
  
		var options = {
			title: '',
			titlePosition: 'none',
			hAxis: {title: '',  titleTextStyle: {color: '#333'}},
			legend: { position: "bottom" },
			vAxis: {minValue: 0},
			colors:['#ff3300','#cccccc','#006699','#FFAA00'], // cancelado: ,'#990000' recuperados ,'#009900'
		focusTarget:'category',
			fontSize:12,
			chartArea:{left:60,top:50,width:"880",height:"320"}
		};

		var chart = new google.visualization.LineChart(document.getElementById('line_chart_div'));
		chart.draw(data, options);
	}
	
	
	
	function drawLineChartTotais() {
		//var data = google.visualization.arrayToDataTable([
		var data = new google.visualization.DataTable();
		data.addColumn({type:'date',label:'Data',role:'domain'}); // Implicit domain column.
		data.addColumn({type:'number',label:'Ativos'}); // Implicit data column.
//		data.addColumn({type:'string', role:'tooltip'});
//		data.addColumn({type:'number',label:'Inativos'}); // Implicit data column.
//		data.addColumn({type:'string', role:'tooltip'});
		data.addColumn({type:'number',label:'Demos'}); // Implicit data column.
//		data.addColumn({type:'string', role:'tooltip'});
//		data.addColumn({type:'number',label:'Demo Inativos'}); // Implicit data column.
//		data.addColumn({type:'string', role:'tooltip'});
//		data.addColumn({type:'number',label:'Cancelados'}); // Implicit data column.
//		data.addColumn({type:'string', role:'tooltip'});
		data.addRows([
			 
//	['DATA', 'ENTRADAS', null, 'SAÍDAS', null],
			<?
				echo substr($dadosGraficoLinhaTotais,0,strlen($dadosGraficoLinhaTotais)-1);
			?>
		]);

		var options = {
			title: '',
			titlePosition: 'none',
			hAxis: {title: '',  titleTextStyle: {color: '#333'}},
			legend: { position: "bottom" },
			vAxis: {minValue: 0},
			//colors:['#006699','#009900','#ff3300','#FFFF00','#990099'],
			colors:['#006699','#FFAA00'],
		focusTarget:'category',
			fontSize:12,
			chartArea:{left:60,top:50,width:"880",height:"320"}
		};

		var chart = new google.visualization.LineChart(document.getElementById('line_chart_div_totais'));
		chart.draw(data, options);
	}
  
</script>

    <div style="font-size: 16px;color:#000;font-weight:bold;margin-bottom:10px;">Movimentação de usuários
    <!--Métricas do período de <?=date('d/m/Y',strtotime($dataInicio))?> a <?=date('d/m/Y',strtotime($dataFim))?>-->
    </div>
    <div id="line_chart_div" style="width:960px; height: 450px; border: 1px solid #ccc;"></div>
<!--
    <div style="width:780px;height:12px;clear:both;margin-bottom:20px;color:#000;font-size:10px;">
        <div style="float:right;margin-top:10px;">
            <div style="position:relative;float:left;margin-left:2px;width:10px;height:10px;background-color:#006699;"></div>
            <div style="position:relative;float:left;margin-left:2px;">Abandonos</div>
            <div style="position:relative;float:left;margin-left:10px;width:10px;height:10px;background-color:#990099;"></div>
            <div style="position:relative;float:left;margin-left:2px;">Não Convertidos</div>
            <div style="position:relative;float:left;margin-left:10px;width:10px;height:10px;background-color:#990000;"></div>
            <div style="position:relative;float:left;margin-left:2px;">Cancelados</div>
            <div style="position:relative;float:left;margin-left:10px;width:10px;height:10px;background-color:#009900;"></div>
            <div style="position:relative;float:left;margin-left:2px;">Recuperados</div>
            <div style="position:relative;float:left;margin-left:10px;width:10px;height:10px;background-color:#ff3300;"></div>
            <div style="position:relative;float:left;margin-left:2px;">Assinaturas</div>
            <div style="position:relative;float:left;margin-left:10px;width:10px;height:10px;background-color:#FFFF00;"></div>
            <div style="position:relative;float:left;margin-left:2px;">Ativados</div>
	    </div>
    </div>
-->
    <div style="clear:both;height:20px;"></div>

    <div style="font-size: 16px;color:#000;font-weight:bold;margin-bottom:10px;">Totais de usuários
    <!--Situação dos usuários no período de <?=date('d/m/Y',strtotime($dataInicio))?> a <?=date('d/m/Y',strtotime($dataFim))?>-->
    </div>
    <div id="line_chart_div_totais" style="width:960px; height: 450px; border: 1px solid #ccc;"></div>
<!--
    <div style="width:780px;height:12px;clear:both;margin-bottom:20px;color:#000;font-size:10px;">
        <div style="float:right;margin-top:10px;">
            <div style="position:relative;float:left;margin-left:2px;width:10px;height:10px;background-color:#006699;"></div>
            <div style="position:relative;float:left;margin-left:2px;">Ativos</div>
            <!--
            <div style="position:relative;float:left;margin-left:10px;width:10px;height:10px;background-color:#009900;"></div>
            <div style="position:relative;float:left;margin-left:2px;">Inativos</div>
            --
            <div style="position:relative;float:left;margin-left:10px;width:10px;height:10px;background-color:#ff3300;"></div>
            <div style="position:relative;float:left;margin-left:2px;">Demos</div>
            <!--
            <div style="position:relative;float:left;margin-left:10px;width:10px;height:10px;background-color:#FFFF00;"></div>
            <div style="position:relative;float:left;margin-left:2px;">Demo Inativos</div>
            <div style="position:relative;float:left;margin-left:10px;width:10px;height:10px;background-color:#990099;"></div>
            <div style="position:relative;float:left;margin-left:2px;">Cancelados</div>
            --
        </div>
	</div>
-->
    <div style="clear:both;margin-bottom:20px;"></div>



    <div style="font-size: 16px;color:#000;font-weight:bold;margin-bottom:10px;">Totais de usuários Ativos por Cidade
    </div>
    <div id="line_chart_div_totais_ativos_cidades" style="width:960px; min-height: 250px; border: 1px solid #ccc; overflow:auto; padding: 1px;">
    <?
	$qtdColunas = 4;
	echo '<table width="100%" cellpadding="4" cellspacing="2" style="float: left;">
		<thead>
			<tr>
				<th width="17%">Cidade</th>
				<th width="3.5%">UF</th>
				<th width="4%">Qtd</th>
				<th width=".5%" style="background-color: #FFF !important"></th>
				<th width="17%">Cidade</th>
				<th width="3.5%">UF</th>
				<th width="4%">Qtd</th>
				<th width=".5%" style="background-color: #FFF !important"></th>
				<th width="17%">Cidade</th>
				<th width="3.5%">UF</th>
				<th width="4%">Qtd</th>
				<th width=".5%" style="background-color: #FFF !important"></th>
				<th width="17%">Cidade</th>
				<th width="4%">UF</th>		
				<th width="4%">Qtd</th>
			<tr>
		</thead>
		<tbody>
';
    $sql = "
		SELECT 
			CASE WHEN d.cidade = '' THEN 'NAO INFORMADA' ELSE d.cidade END cidade
			, CASE WHEN d.estado = '' THEN '--' ELSE d.estado END estado
			, SUM(CASE WHEN l.id > 0 THEN 1 ELSE 0 END) total
		FROM
			login l
			INNER JOIN dados_da_empresa d ON l.id = d.id
			LEFT JOIN estados e1 ON LCASE(TRIM(d.estado)) = LCASE(TRIM(e1.sigla))
			LEFT JOIN cidades c ON LCASE(TRIM(d.cidade)) = LCASE(TRIM(c.cidade)) AND c.id_uf = e1.id
		WHERE 
			l.status = 'ativo'
			AND d.ativa = '1'
			AND l.id AND l.idUsuarioPai NOT IN (9, 1581, 4093, 6857, 6905, 6958, 6835, 6858, 6764, 7072, 6870, 7077, 7075, 7004, 7045, 6963, 6869, 6963, 6787, 6614, 6576, 6742, 6613)
		GROUP BY 1, 2
		ORDER BY 3 desc, 1, 2
	";
	$rs = mysql_query($sql) or die('erro');
	echo '<tr class="guiaTabelaAdmin">';
	while($rsAtivosPorCidade = mysql_fetch_array($rs)){
		echo '<td>' . $rsAtivosPorCidade['cidade'] . '</td><td>' . $rsAtivosPorCidade['estado'] . '</td><td align="right"><span style="color: #C00">' . $rsAtivosPorCidade['total'] . '</span></td>';
		$total += $rsAtivosPorCidade['total'];
		$loop += 1;
		$count += 1;
		if($loop === $qtdColunas){
			echo '</tr><tr class="guiaTabelaAdmin">';
			$loop = 0;
		}else{
			echo '<td style="background-color: #FFF !important"></td>';
		}
	}
	echo '</tr>
	</tbody>';
	?>
        <tfoot>
	        
            <tr>
                <th style="background-color: #999; font-weight: normal" colspan="14" align="right">Totais de empresas ativas por cidade:&nbsp;</th>
                <th style="background-color: #999; font-weight: normal" align="right"><?=$total?></th>
            </tr>
        </tfoot>
    </table>
    
    </div>
    <div style="clear:both;margin-bottom:20px;"></div>




</div>
</div>


<?php include '../rodape.php' ?>