<?php
	
	include 'header_restrita.php';
	
	$link = explode('?', $_SERVER['REQUEST_URI']);
	
?>

	<iframe src="boleto.class.alteracao.php?<?php echo $link[1]; ?>" style="width: 1280px;border: 0;height: 2000px;"></iframe>


<?php include 'rodape.php'; ?>
