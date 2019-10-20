<html>
	<head>
		<style>
		body{ font:12px Arial }
		</style>
	</head>
	<body>
	<p>
	<?php
	if(isset($_GET['k']) && $_GET['k'] == '!wefit123-----'){
		$file = "PHP_errors.log";
 
		$content = file($file);
		$data = implode("</p><p>",$content);
		echo $data;	
	}else{
		echo 'forbidden';
	}
	?>
</body>
</html>