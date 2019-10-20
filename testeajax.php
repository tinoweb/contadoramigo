<?

$teste1 = "https://www.contadoramigo.com.br/descadastramento_mei.php?erro=erro1";

$pagina = strrev( strchr(strrev($teste1),"?") );

echo substr($pagina,0,strlen($pagina)-1);


$teste = array('indice1'=>'valor1', 'indice2'=>'valor2');
echo json_encode($teste);
?>