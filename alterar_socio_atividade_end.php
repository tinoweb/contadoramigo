<?php 
	
	session_start();
	if (isset($_SESSION["id_userSecao"])) {include 'header_restrita.php';}
	else {
		$nome_meta = "alteracao";
		include 'header.php';
	} 
?>

	<div class="principal minHeight">
		
			<h1 class="titulo" style="margin-bottom:20px;">Alterações de empresa</h1>
				
			
			A alteração de empresa (sócios, atividade, nome, endereço e demais informações cadastrais) deve ser solicitada na junta comercial do seu estado, ou no cartório, dependendo de onde esta foi aberta. Já escritórios de advocacia devem solicitar sua alteração na própria OAB. <br>
			<br>
			O processo  passará pelas  três esferas de governo: federação, estado e município, pois cada uma possui seu próprio registro e todos precisam ser atualizados. O CNPJ é seu registro junto ao Governo Federal, a Inscrição Estadual, junto ao estado e a Inscrição Municipal, também conhecida como CCM, é seu número de registro junto ao município. <br>
		    <br>
		    Para que possamos orientá-lo melhor, selecione abaixo o tipo de sua empresa:<br>
		    <ul>
				<li><a href="https://www.contadoramigo.com.br/alteracao_contrato_sociedade_junta.php">Alterar sociedade Empresária Registrada na Junta</a></li>
				<li><a href="https://www.contadoramigo.com.br/alteracao_contrato_sociedade_cartorio.php">Alterar sociedade registrada em cartório</a></li>
				<li><a href="https://www.contadoramigo.com.br/alteracao_contrato_individual.php">Alterar empresa individual</a></li>
				<li><a href="https://www.contadoramigo.com.br/alteracao_contrato_individual.php">Alterar Eireli</a></li>
			</ul>

	</div>


<?php include 'rodape.php' ?>
