<?php
	
	include 'header_restrita.php';
	
	$link = explode('?', $_SERVER['REQUEST_URI']);
	
?>

	<iframe src="boleto.class.teste.php?<?php echo $link[1]; ?>" style="width: 666px;border: 0;height: 1000px;"></iframe>


<?php include 'rodape.php'; ?>
