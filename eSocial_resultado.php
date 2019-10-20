<?php 

session_start();

if(isset($_SESSION['registro_eSocial_Id'])){

	$IdRegistroDoEnvioXML = $_SESSION['registro_eSocial_Id'];
	
	// Mata a sessão do id de inclusão do xml de envio para esocial.
	unset($_SESSION['registro_eSocial_Id']);
	
}

include 'header_restrita.php';

function PegaAlinhaRegistroXMLEnvio(){
	
	$out = false;
	
	$sql = "SELECT * FROM esocial_envio_retorno_xml WHERE user_id = '".$_SESSION[ "id_empresaSecao" ]."' ORDER BY data_inclusao DESC;";
	
	$result = mysql_query($sql) or die(mysql_error());
	
	if( mysql_num_rows($result) > 0 ){
		
		while($array = mysql_fetch_array($result)){
			
			$out[] = $array;
		}
	}
	
	return $out;
}

// Pega os dados do de inclusão do xml de envio.
$resultadoXML = PegaAlinhaRegistroXMLEnvio($IdRegistroDoEnvioXML);
	
$linhasTabela = '';
$statusInclusao = false; 

if($resultadoXML) {
	
	foreach($resultadoXML as $val){
	
		if($IdRegistroDoEnvioXML == $val['id']){
			$statusInclusao = true; 
		}
		
		$linhasTabela .= '<tr style="background-color: white"><td>'.$val['CNPJ'].'</td>'
		.'<td>'.$val['codigo_do_evento'].'</td>'
		.'<td>'.date('d/m/Y H:i:s', strtotime($val['data_inclusao'])).'</td>'
		.'<td>'.$val['status_envio'].'</td></tr>';
	}
}	

?>
<div class="principal minHeight">

	<H1>Impostos e Obrigações</H!>
	<H2>E-Social</H2>

<? if($resultadoXML):?> 

	<?if($statusInclusao):?>
	<span style="font-size: 14px">Arquivo gerado com sucesso!</span><br><br>
	<?endif?>
	
	<table border="0" cellspacing="2" cellpadding="4" style="font-size:12px; max-width: 500px; width: 100%; margin-bottom: 20px">
	<tr>
	<th>CNPJ</th>
	<th>Evento</th>
	<th>Data e Hora</th>
	<th>Status</th>
	</tr>
	<?=$linhasTabela?>
	</table>

	<span class="destaqueAzul">Agora só falta enviá-lo</span>. <br><br>

	Para isso, abra em seu computador o <strong>E-Zap</strong>, nosso aplicativo de transmissão gratuito, que você já deve ter instalado, e siga as instruções para envio do arquivo. Se ainda não baixou o e-Zap, faça-o <a href="downloads/e-zap.exe">aqui</a>.<br><br>

	O programa localizará seu arquivo diretamente em nossos servidores e fará a transmissão para a Receita Federal. A qualquer momento você poderá consultar a lista de arquivos enviados, aqui mesmo no Contador Amigo, na página inicial desta seção, em <a href="e-social.php">Obrigações/E-social</a>.<br><br>

<? elseif(!$statusInclusao): ?>
	
	<span style="font-size: 14px">Erro ao gerar o arquivo!</span><br><br>
	Por favor, entre em contato com o nosso <a href='/suporte.php'>Help Desk</a>.
	
<? else: ?>	
	
	Abra em seu computador o <strong>E-Zap</strong>, nosso aplicativo de transmissão gratuito, que você já deve ter instalado, e siga as instruções para envio do arquivo. Se ainda não baixou o e-Zap, faça-o <a href="downloads/e-zap.exe">aqui</a>.<br><br>

	O programa localizará seu arquivo diretamente em nossos servidores e fará a transmissão para a Receita Federal. A qualquer momento você poderá consultar a lista de arquivos enviados, aqui mesmo no Contador Amigo, na página inicial desta seção, em <a href="e-social.php">Obrigações/E-social</a>.<br><br>	
<? endif; ?>
</div>

<?php include 'rodape.php' ?>
