<?php
/**
 * Autor: Átano de Farias
 * Data: 21/02/2017 
 */	
class Util {

	public function DeleteFile($filePath)
	{
		$out = false;
		// Verifica se o arquivo existe para Excluir.
		if(file_exists($filePath)){
			unlink($filePath);                
			if(!file_exists($filePath)){
				$out = true;
			}               
		} else {
			$out = true;
		}
		return $out;
	}        
	
	public function UploadFile($nameDirectory = 'files', $nameFile = 'file', $extensionFile = array())
	{
		$out = '';
		
		// Verifica se existe Arquivo para salvar as imagens
		if(!is_dir($nameDirectory))
		{
			mkdir($nameDirectory, 0744);
		}
		
		// Pasta onde o arquivo vai ser salvo
		$UpImage['directory'] = $nameDirectory;

		// Tamanho máximo do arquivo (em Bytes)
		$UpImage['size'] = 1024 * 1024 * 10; // 10Mb

		// Array com as extensões permitidas
		$UpImage['extension'] = $extensionFile; //Exemplo - array('jpg', 'png', 'gif')

		// Renomeia o arquivo? (Se true, o arquivo será salvo como .jpg e um nome único)
		$UpImage['rename'] = true;

		// Array com os tipos de erros de upload do PHP
		$UpImage['erros'][0] = 'Não houve erro';
		$UpImage['erros'][1] = 'O arquivo é maior do que o permitido pelo PHP';
		$UpImage['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
		$UpImage['erros'][3] = 'O upload foi feito parcialmente';
		$UpImage['erros'][4] = 'Não foi posivel fazer o upload do arquivo';            
		
		// Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
		if ($_FILES['file']['error'] != 0)
		{
		  throw new \Exception("Não foi possível fazer o upload, erro:" . $UpImage['erros'][$_FILES['file']['error']]);
		}            
		
		if(isset($extensionFile[0]) && !empty($extensionFile[0]))
		{
			foreach ($extensionFile as $value)
			{
				if(empty($listExtension)){
					$listExtension = $value;
				} else {
					$listExtension .= ' ,'.$value;
				}           
			}                
			
			// Faz a verificação da extensão do arquivo
			$extension = explode('.', $_FILES['file']['name']);
			$extension = strtolower(end($extension));
			if (array_search($extension, $UpImage['extension']) === false)
			{
			  throw new \Exception("Por favor, envie arquivos com as seguintes extensões: ". $listExtension);
			}
		}
			
		// Faz a verificação do tamanho do arquivo
		if ($UpImage['size'] < $_FILES['file']['size'])
		{
		  throw new \Exception("O arquivo enviado é muito grande, envie arquivos de até 10Mb.");
		}

		// Primeiro verifica se deve trocar o nome do arquivo
		if ($UpImage['rename'] == true)
		{
		  // Cria um nome baseado no UNIX TIMESTAMP atual e com extensão .jpg
		  $nome_final = $nameFile.'-'.md5(time()).'.'.$extension;
		} else {
		  // Mantém o nome original do arquivo
		  $nome_final = $_FILES['file']['name'];
		}
		
		if(file_exists($_FILES['file']['tmp_name']))
		{
			// Depois verifica se é possível mover o file para a pasta escolhida
			if (move_uploaded_file($_FILES['file']['tmp_name'], $UpImage['directory'] .'/'. $nome_final))
			{
				$out = $UpImage['directory'] .'/'. $nome_final;
			} else {
				throw new \Exception("O arquivo não pode se movido da pasta tempe para ".$nameDirectory);
			}                
			
		} else {
			throw new \Exception("O arquivo enviado Temp não Existe");
		}
		
		return $out;
	}
	
	public static function MassageTreatment() {
		
		$message = "";
			
		if(isset($_SESSION['message'])){
			$message = $_SESSION['message'];

			unset($_SESSION['message']);
		}
		
		return $message;
	}
	
	public static function ErrorTreatment() {
		
		$errorMsg = "";
			
		if(isset($_SESSION['errorMsg'])){
			$errorMsg = '<div id="boxError">'.$_SESSION['errorMsg'].'</div>';

			unset($_SESSION['errorMsg']);
		}
		
		return $errorMsg;
	}

	public static function GeraPagination($rows = 20, $position = 1, $amount, $link, $paransaAray = false){
		
		$out ="";
		$parans = '';
		$pageNumber = '';
		
		if($amount > $rows){
			$pageNumber = ceil($amount / $rows);
		}
		
		if($paransaAray){
			foreach($paransaAray as $key => $val){
				$parans .='&'.$key.'='.$val; 
			}
		}
					   
		if($position > 1){ 
			$out .='<a href="'.$link.'?page='.($position - 1).$parans.'">anterior</a> |'; 
		}

		for($i = 0; $i < $pageNumber; $i++) {

			$iNumber = $i + 1;
			
			if($i == $position){
				$out .='<a class="Active" href="'.$link.'?page='.$iNumber.$parans.'">'.$iNumber.'</a> | '; 
			} else {
				$out .='<a href="'.$link.'?page='.$iNumber.$parans.'">'.$iNumber.'</a> | ';
			}
		}

		if($position < $pageNumber){
			$out .='<a href="'.$link.'?page='.($position + 1).$parans.'">próxima</a>';
		}
		
		$out .= '</ul>';
		
		return $out;
	}
}