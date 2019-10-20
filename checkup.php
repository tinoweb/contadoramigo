<?php 
	
	session_start();
	if (isset($_SESSION["id_userSecao"])) {
		include 'header_restrita.php';
	}
	else{
		$nome_meta = "certidoes";
		include 'header.php';
	} 
?>

<div class="principal minHeight">
	

		<h1 class="titulo">Check Up Fiscal</h1>

		<h2>Certidões Negativas</h2>
		As certidões negativas servem também como prova de regularidade numa eventual fiscalização. A maioria delas podem ser obtidas online pela internet, mas não se entusiasme. Isso só acontece se você não tiver pendência nenhuma, o que é raro. Em caso de irregularidade, você terá que se dirigir ao órgão competente para saber a origem do problema. Pode ser um débito pendente, um preenchimento errado, ou alguma outra inconsistência. A seguir a lista de documentos que você deve obter:<br />
		<ol>
			<li>
				<a href="checkup_uniao.php">
					Certidão Conjunta de Débitos Relativos a Tributos Federais e à Dívida Ativa da União
				</a>
			</li>
			<li>
				<a href="checkup_fgts.php">
					Certidão de  Regularidade junto ao FGTS
				</a>
			</li>
			<li>
				<a href="checkup_rais.php">
					Recibos de entrega da RAIS
				</a>
			</li>
			<li>
				<a href="checkup_prefeitura.php">
					Certidão Negativa de Débitos Municipais
				</a>
			</li>
			<li>
				<a href="checkup_estado.php">
					Certidão Negativa de Débitos Estaduais
				</a>
			</li>
		</ol>
	</div>



<?php include 'rodape.php' ?>

