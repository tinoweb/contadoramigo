<?php 
	
	function extensao($file){

		$ext = explode(".", $file);
		$tam = count($ext);
		$ext = strtolower($ext[$tam-1]); 

		if( $ext != 'pdf' && $ext != 'doc' && $ext != 'jpg' && $ext != 'gif' && $ext != 'png' )
			return false;

		return $ext;

	}
	function nomeArquivo($file,$ext){
		$nome = explode(".".$ext, $file);
		return $nome[0];
	}

	function ifArquivoValido($file){
		if( $file != 'pdf' && $file != 'doc' && $file != 'jpg' && $file != 'gif' && $file != 'png' )
			return false;

		return true;
	}

	if( isset( $_POST['teste'] ) ):
		


		move_uploaded_file($_FILES["anexos_doc"]["tmp_name"],"nome.png");

		// var_dump($_FILES);
		// foreach ($_FILES as $__FILES) {
			
		// 	for ($i=0; $i < count($__FILES["name"]); $i++) { 
				
		// 		$file = extensao($__FILES["name"][$i]);
		// 		if( ifArquivoValido($file) ){
		// 			$id = '10';

		// 			$name = $__FILES["name"][$i];
		// 			$extensao = extensao($file);
		// 			$file_name = nomeArquivo($name,$extensao);

		// 			$file_name = $file_name."_".md5($id)."_.".$extensao;

		// 			echo $file_name;

		// 			echo '<br>'; 
					
					
		// 			echo $__FILES["tmp_name"][$i];
					
		// 			$ret = move_uploaded_file($__FILES["tmp_name"][$i],$file_name);
		// 			if($ret)
		// 				echo 'OK';
		// 			else
		// 				echo 'ERRO';
		// 			// while ($ret == false) {
		// 			// 	$ret = move_uploaded_file($__FILES["tmp_name"][$i],"uploads/comprovantes/".$file_name);
		// 			// }	
		// 		 }

		// 	}
		


		
			
			

			
		// }
	
	

	endif;

	$myfile = fopen("testfile.txt", "w");
		$txt = "John Doe\n";
		fwrite($myfile, $txt);
		fclose($myfile);
	

?>


	<form action="" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
		<input type="hidden" name="teste" value="">
		<input type="file" name="anexos_doc" value=""><br>
		<input type="submit" name="" value="Enviar">
	</form>	