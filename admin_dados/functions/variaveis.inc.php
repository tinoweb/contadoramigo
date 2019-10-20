<?php
	# POSTS
	if(isset($_POST)){
		foreach($_POST as $campos => $valor){
			$comando = "\$".$campos."='".$valor."';";
			eval($comando);
			//echo $campos . ' -- '.$valor.'<br>';
		}
	}

	# GET
	if(isset($_GET)){
		foreach($_GET as $campos => $valor){
			$comando = "\$".$campos."='".$valor."';";
			eval($comando);
			//echo $campos . ' -- '.$valor.'<br>';
		}
	}

	# FILES
	if(isset($_FILES)){
		foreach($_FILES as $campos => $valor){
			$comando = "\$".$campos."='".$valor['name']."';";
			eval($comando);
			//echo $campos . ' -- '.$valor[name].'<br>';
		}
	}
?>