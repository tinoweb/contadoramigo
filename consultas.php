<?php
include "conect.php";

$opcao = isset($_GET['opcao']) ? $_GET['opcao'] : '';
$valor = isset($_GET['valor']) ? $_GET['valor'] : '';
if (! empty($opcao)){
	switch ($opcao) {
		case 'paises': 
			echo getAllPais();
			break;
		case 'estados':

		case 'cidades':
            $sql = "SELECT * FROM cidades WHERE id_uf = '" . $valor . "' ORDER BY cidade";
            $result = mysql_query($sql) or die(mysql_error());
			$arrCidades = array();

            while($cidades = mysql_fetch_array($result)){
				array_push($arrCidades,array('cidade'=>$cidades['cidade']));
            }

//			;
//			var_dump($arrCidades);
			echo json_encode($arrCidades);
//			echo getFilterCidade($valor);
			break;
	}
}
?>