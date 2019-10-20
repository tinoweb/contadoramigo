<?php 
	
	/**
	* 
	*/
	class File_input_injection{
		
		private function extensao($file){

			$ext = explode(".", $file);
			$tam = count($ext);
			$ext = strtolower($ext[$tam-1]); 
			if( $ext == "php" || $ext == 'js' || $ext == 'css' || $ext == 'html' || $ext == 'phtml' || $ext == 'htm' || $ext == 'sql' )
				return true;
			else
				return false;


		}
		function execute(){

			foreach ($_FILES as $__FILES) {
				
				for ($i=0; $i < count($__FILES["name"]); $i++) { 
					
					if( $this->extensao($__FILES["name"][$i]) ):

						header("Location: ".$_SERVER['REQUEST_URI']."?erro ");

					endif;
				}
			}
		}

	}
	if( isset( $_GET['erro'] ) ):
		
		echo 'aki nao';
	
	endif;
	if( isset($_FILES) ){
	
		$file = new File_input_injection();
		$file->execute();
	
	}

?>


	<form method="post" action="anti-file-injection.php" accept-charset="utf-8" enctype="multipart/form-data">
							
		<input type="file" name="anexos_doc[]" value="" multiple="" style="margin-left:10px;margin-right:10px;">
		<input name="incluir" type="submit" value="Incluir lançamento"></center>

	</form>


