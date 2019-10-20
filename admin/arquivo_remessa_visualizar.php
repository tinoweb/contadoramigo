<?php 
include '../conect.php';

include '../session.php';

include 'check_login.php';

?>
<link href="../estilo.css" rel="stylesheet" type="text/css">
<?

$titulo_vermelho = "Arquivos de Retorno";

if(isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'subir'){

		$checaArquivo = mysql_fetch_array(mysql_query("select count(*) total FROM arquivo_remessa WHERE nome = '" . $_FILES['arquivo']['name'] . "'"));

		if($checaArquivo['total'] <= 0){
	
			// ATRIBUINDO O NOME DO ARQUIVO PROCESSADO À VARIÁVEL
			$temp_arquivo = $_FILES['arquivo']['tmp_name'];
			$nome_arquivo = ('arquivos_retorno/'.$_FILES['arquivo']['name']);
			$nome_arquivo_proc = ('arquivos_retorno/proc_'.$_FILES['arquivo']['name']);
	
			// SUBINDO O ARQUIVO DE RETORNO
			if(!file_exists($nome_arquivo)) move_uploaded_file($temp_arquivo,$nome_arquivo);
			
			// GRAVANDO DADOS DO ARQUIVO NA TABELA
			mysql_query("INSERT INTO arquivo_remessa SET nome = '" . $_FILES['arquivo']['name'] . "', data_carga = '" . date('Y-m-d H:i:s') . "', status = 'carregado'");
	
			//header ('location: subir_arquivo_retorno.php');

		}else{

			echo "<script>alert('Já existe um arquivo com este nome, carregado.');window.location='subir_arquivo_retorno.php';</script>";
			
		}
		
}


if($_REQUEST['reset'] != ''){
	mysql_query("UPDATE arquivo_remessa SET data_processamento = null, status = 'carregado' WHERE id = " . $_GET['reset']);
		header ('location: subir_arquivo_retorno.php');
}


if($_REQUEST['excluir'] != ''){
	$linhaNome = mysql_fetch_array(mysql_query("SELECT nome FROM arquivo_remessa WHERE id = " . $_GET['excluir']));
	if(file_exists($linhaNome['nome'])) unlink($linhaNome['nome']);
	mysql_query("DELETE FROM arquivo_remessa WHERE id = " . $_GET['excluir']);
		header ('location: subir_arquivo_retorno.php');
}
?>


<script type="text/javascript">

$(document).ready(function(e) {

	esquerda = ($('#assinante').offset().left);
	topo = ($('#assinante').offset().top);
	altura = ($('#assinante').innerHeight());
			
	$('#assinante').keyup(function(){
		if($(this).val() != ''){
			$.ajax({
				url:'preenchecampobusca.php',
				type: 'POST',
				data: 'valor='+$('#assinante').val(),
				async: true,
				cache:false,
				success: function(result){
					if(result != ''){
						$('#preenchimentoBusca').css({
							'height':'auto'
							,'display': 'block'
							, 'top': topo + altura + 3
							, 'left': esquerda
						}).fadeIn('fast');
						$('#preenchimentoBusca').html(result);
					} else {
						$('#preenchimentoBusca').html('').css('display','none');
					}
				}						
			});
			
			$('.selResultBusca').live('click',function(){
				$('#assinante').val($(this).html());
				$('#hddIdUser').val($(this).attr('iduser'));
				$('#preenchimentoBusca').fadeOut('fast');
			});
		}else{
			$('#preenchimentoBusca').fadeOut('fast');
		}
	});
});

</script>
<?
$dataInicio = $_GET["dataInicio"];
if ($dataInicio == "") {
	$dataInicio = date('Y-m-d',mktime(0,0,0,date('m'),date('d')-7,date('Y')));
}

$dataFim = $_GET["dataFim"];
if ($dataFim == "") {
	$dataFim = date("Y-m-d");
}

$status = $_GET["status"];
if ($status == "") {
	$status = "todos";
}

function selected( $value, $prev ){
   return $value==$prev ? ' selected="selected"' : ''; 
};
?>

<script>

function alterarPeriodo() {
	dataInicio = document.getElementById('DataInicio').value;
	anoInicio = dataInicio.substr(6,4);
	mesInicio = dataInicio.substr(3,2);
	diaInicio = dataInicio.substr(0,2);
	dataFim = document.getElementById('DataFim').value;
	anoFim = dataFim.substr(6,4);
	mesFim = dataFim.substr(3,2);
	diaFim = dataFim.substr(0,2);
	status = document.getElementById('selStatus').value;

	window.location='subir_arquivo_retorno.php?dataInicio='+anoInicio+'-'+mesInicio+'-'+diaInicio+'&dataFim='+anoFim+'-'+mesFim+'-'+diaFim+'&status='+status;
}

function enviaArquivo(){
	if(document.form.arquivo.value==''){
		alert('Selecione um arquivo');
		document.form.arquivo.focus();
		return false;
	}
}

</script>
<?php 

	class Retorno
	{
		private $file;
		function getId(){
			$consulta = mysql_query("SELECT * FROM boletos_registrados WHERE nosso_numero = '".(int)(substr($this->file,37,17))."' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			if( $objeto_consulta['id_user'] != '' )
				return $objeto_consulta['id_user'];
			else
				return (int)(substr($this->file,44,6));
		}
		function getData(){
			return (string)(substr($this->file,77,2)).'/'.(string)(substr($this->file,79,2)).'/'.(string)(substr($this->file,81,4));
		}
		function getValor(){
			$valor = (float)(substr($this->file,85,15))/100;

			return 'R$ '.number_format($valor,2,',','.'  );
		}
		function getMes(){
			$consulta = mysql_query("SELECT * FROM boletos_registrados WHERE nosso_numero = '".(int)(substr($this->file,37,17))."' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			$consulta = mysql_query("SELECT * FROM historico_cobranca WHERE idHistorico = '".$objeto_consulta['id_historico']."' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			$aux = explode('-', $objeto_consulta['data_pagamento']);
			if( $objeto_consulta['data_pagamento'] != '' )
				return $aux[1].'/'.$aux[0];
			else{
				$aux = (string)(substr($this->file,50,4));	
				return (substr($aux,0,2)).'/'.(substr($aux,2,4));
			}
		}
		function getRejeicao(){
			return (string)(substr($this->file,213,10));	
		}
		function getCarteira(){
			$carteira = (string)(substr($this->file,37,7));	
			if( $carteira == '2263282' )
				return '18 - Boleto Sem Registro'; 
			if( $carteira == '2850943' )
				return '17 - Boleto Registrado'; 
		}
		function __construct($string)
		{
			$this->file = $string;
		}

	}
	
	include '../conect.php';

	$id = $_GET['id'];

	$consulta = mysql_query("SELECT * FROM arquivo_remessa WHERE id = '".$id."' ");
	$objeto=mysql_fetch_array($consulta);

	if( $objeto['nome'] == '' )
		exit();

	$filename = $objeto['nome'];

	//$myfile = fopen('arquivos_retorno/'.$filename, "r") or die("Unable to open file!");
	
	$arquivo = file('../arquivo_remessa/files/'.$filename);		
	$total_linhas_arquivo = (int)(substr($arquivo[count($arquivo)-1],23,6));
	$total_pagamentos = (($total_linhas_arquivo - 4)/2);

	$data = (string)(substr($arquivo[0],143,8));	

	echo '	<div class="principal" style="width:100%">
				<div class="titulo" style="margin-bottom:10px;">Arquivo Remessa do dia '.substr($data,0,2).'/'.substr($data,2,2).'/'.substr($data,4,4).'</div>
				<table border="0" cellspacing="2" cellpadding="4" style="font-size:12px; width: 100%;">
					<tr>
				        <th>Id</th>
				        <th>Data Vencimento</th>
				        <th>Histórico</th>
				        <th>Valor</th>
					</tr> ';

	$transicao = 3;
	for ($i=2; $i < $total_linhas_arquivo-$transicao; $i=$i+$transicao) { 

		$line = new Retorno($arquivo[$i]);
		echo '	<tr>
			        <td style="background-color: rgb(255, 255, 255);"><a href="cliente_administrar.php?id='.$line->getId().'" target="_blank" title="Acessar Cliente">'.$line->getId().'</a></td>
			        <td style="background-color: rgb(255, 255, 255);">'.$line->getData().'</td>
			        <td style="background-color: rgb(255, 255, 255);">'.$line->getMes().'</td>
			        <td style="background-color: rgb(255, 255, 255);">'.$line->getValor().'</td>
				</tr> ';
		// echo 'Carteira: '.$line->getCarteira().'<br>';
		// echo 'User: '.$line->getId().'<br>';
		// echo 'Data: '.$line->getData().'<br>';
		// echo 'Valor: '.$line->getValor().'<br>';
		// echo 'Status: '.$line->getStatus().'<br>';
		// echo '<br>';
		// echo 'Rejeicao: '.$line->getRejeicao().'<br>';

	}
	echo 'Total de registros: '.(($total_linhas_arquivo-4)/3);
?>
	
		
		    
		</table>
	</div>