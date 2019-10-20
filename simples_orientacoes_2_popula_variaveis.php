<?php

include "conect.php";

$comRetencao3 = 'nao';
$comRetencao4 = 'nao';
$comRetencao5 = 'nao';

$semRetencao3 = 'nao';
$semRetencao4 = 'nao';
$semRetencao5 = 'nao';

$mesmoMunicipio3 = 'nao';
$mesmoMunicipio4 = 'nao';
$mesmoMunicipio5 = 'nao';

$outroMunicipio3 = 'nao';
$outroMunicipio4 = 'nao';
$outroMunicipio5 = 'nao';


$id = $_REQUEST["id"];
$totalLinhas = $_REQUEST["totalLinhas"];

for($i=0; $i < count($_REQUEST["comRetencao3"]); $i ++){
	if($_REQUEST["comRetencao3"][$i]){
		$comRetencao3 = 'sim';	
	}
}
for($i=0; $i < count($_REQUEST["comRetencao4"]); $i ++){
	if($_REQUEST["comRetencao4"][$i]){
		$comRetencao4 = 'sim';	
	}
}
for($i=0; $i < count($_REQUEST["comRetencao5"]); $i ++){
	if($_REQUEST["comRetencao5"][$i]){
		$comRetencao5 = 'sim';	
	}
}

for($i=0; $i < count($_REQUEST["semRetencaoMesmoMunicipio3"]); $i ++){
	if($_REQUEST["semRetencaoMesmoMunicipio3"][$i]){
		$semRetencao3 = 'sim';
		$mesmoMunicipio3 = 'sim';
	}
}
for($i=0; $i < count($_REQUEST["semRetencaoMesmoMunicipio4"]); $i ++){
	if($_REQUEST["semRetencaoMesmoMunicipio4"][$i]){
		$semRetencao4 = 'sim';	
		$mesmoMunicipio4 = 'sim';
	}
}
for($i=0; $i < count($_REQUEST["semRetencaoMesmoMunicipio5"]); $i ++){
	if($_REQUEST["semRetencaoMesmoMunicipio5"][$i]){
		$semRetencao5 = 'sim';	
		$mesmoMunicipio5 = 'sim';
	}
}

for($i=0; $i < count($_REQUEST["semRetencaoOutroMunicipio3"]); $i ++){
	if($_REQUEST["semRetencaoOutroMunicipio3"][$i]){
		$semRetencao3 = 'sim';
		$outroMunicipio3 = 'sim';
	}
}
for($i=0; $i < count($_REQUEST["semRetencaoOutroMunicipio4"]); $i ++){
	if($_REQUEST["semRetencaoOutroMunicipio4"][$i]){
		$semRetencao4 = 'sim';	
		$outroMunicipio4 = 'sim';
	}
}
for($i=0; $i < count($_REQUEST["semRetencaoOutroMunicipio5"]); $i ++){
	if($_REQUEST["semRetencaoOutroMunicipio5"][$i]){
		$semRetencao5 = 'sim';	
		$outroMunicipio5 = 'sim';
	}
}


echo "ID: " . $id . "|totalLinhas: " . $totalLinhas . "|comRetencao3: " . $comRetencao3 . "|comRetencao4: " . $comRetencao4 . "|comRetencao5: " . $comRetencao5 . "|semRetencao3: " . $semRetencao3 . "|semRetencao4: " . $semRetencao4 . "|semRetencao5: " . $semRetencao5 . "|mesmoMunicipio3: " . $mesmoMunicipio3 . "|mesmoMunicipio4: " . $mesmoMunicipio4 . "|mesmoMunicipio5: " . $mesmoMunicipio5 . "|outroMunicipio3: " . $outroMunicipio3 . "|outroMunicipio4: " . $outroMunicipio4 . "|outroMunicipio5: " . $outroMunicipio5;

exit;

?>