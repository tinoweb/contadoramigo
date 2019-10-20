<?php 

	if(isset($_POST['methodAjax']) && !empty($_POST['methodAjax'])) {

		session_start();
		
		require_once('conect.php');
		require_once('Controller/balanco_patrimonial-controller.php');
		
		// Instância a classe.
		$balancoPatrimonial = new Balanco_Patrimonial();
		
		// Chama o método de controle do ajax.
		echo $balancoPatrimonial->ControleAjax($_POST['methodAjax']);
		
		// Para a execução do código.
		die();
	}

?>

<form method="post" action="atano.php">

	<input type="hidden" name="methodAjax" value="RealizaCalculoBalanco">
	<span style="margin-right: 20px">Selecione o ano-calendário do balanço: </span>
	<label style="margin-right:20px;">
		<input class="atualizarAno" style="margin-right:5px;" type="radio" name="ano" value="2018" >2018
	</label>	<label style="margin-right:20px;">
		<input class="atualizarAno" style="margin-right:5px;" type="radio" name="ano" value="2017" >2017
	</label>	<label style="margin-right:20px;">
		<input class="atualizarAno" style="margin-right:5px;" type="radio" name="ano" value="2016" >2016
	</label>	<label style="margin-right:20px;">
		<input class="atualizarAno" style="margin-right:5px;" type="radio" name="ano" value="2015">2015
	</label>	<label style="margin-right:20px;">
		<input class="atualizarAno" style="margin-right:5px;" type="radio" name="ano" value="2014">2014
	</label>	

	
	<input type="submit" value="Go" />

</form>