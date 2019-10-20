<?php
	//ini_set('display_errors',1);
	//ini_set('display_startup_erros',1);
	//error_reporting(E_ALL);

include 'header_restrita.php'; 
require_once('DataBaseMySQL/checkup_prefeitura_op.php');
$checkupPrefeitura = new CheckupPrefeitura();
$empresaId = $_SESSION['id_empresaSecao'];
?>

<div class="principal">

<h1>Dívidas e Regularização</h1>
	
<h2 style="margin-left:30px; color:#024a68">Certidões Negativas</h2>

<h2 style="margin-left:60px; font-size: 16px; margin-bottom: 30px">Certidão Negativa de Débitos Municipais</h2>


A Certidão Negativa de Débitos Municipais indica que você está em dia com os tributos cobrados pelo município, tais como a taxa anual de fiscalização e funcionamento e o IPTU. 
	<?php //Faz a verificação da cidade para mostrar ou não o texto abaixo
	if($checkupPrefeitura->verificaCidade($empresaId) != 'São Paulo' || $checkupPrefeitura->verificaCidade($empresaId) == ''): ?>
	Nas grandes cidades este certificado pode ser obtido pela internet, a partir dos sites das respectivas prefeituras.
	<?php else:  ?>
	<a href="checkup_prefeitura_SP.php">Veja como obter a certidão no município de São Paulo.</a>
	<?php endif; ?> <br />
<br />
Caso a sua cidade não emita este tipo de certificado pela internet, você precisará se dirigir à prefeitura se quiser emitir o documento. Em caso de pendência, você será informado e poderá quitar todos os deus débitos.<br />
<br />
<strong>Divida comum x Dívida Ativa</strong><br />
Se você possuir débitos muito antigos com a prefeitura, é possível que eles tenham sido encaminhados para a <strong>Divida Ativa do Município</strong>. Isto significa que  está a um passo de vir a ser cobrada judicialmente, podendo inclusive implicar no leilão de bens em nome da empresa. Dessa forma, fique atento, você pode pensar que não débito nenhum, porque ele foi retirado da cobrança comum e  está na dívida ativa. De qualquer forma, a Certidão Negativa só será emitida se você não possuir débitos de nehuma espécie.<br />
<br />
<br />
<strong>Parcelamento e anistia</strong><br />
Se o seu débito for muito grande, sempre é possível solicitar o parcelamento. Fique atento também à anistia. Periodicamente as prefeituras decretam a  redução dos juros de algumas dívidas dos contribuintes para ajudá-losß na regularização de seu débitos. Isso ocorre com mais frequência no início do ano.<br />
<br />
</div>
</div>
<?php include 'rodape.php' ?>

