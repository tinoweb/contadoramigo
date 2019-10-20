<?php 

	//Função que croptografa e decriptografa os dados através do método AES, chave necessita ter 16, 24 ou 32 caracteres ou bytes
	function aes($imputText,$acao,$imputKey = "__MINHA__CHAVE__"){
		
		# criptografia
		require_once("class/aes.php");
		//A chave deve conter 16, 24 ou 32 caracteres
		$blockSize = 256;
		$aes = new AES($imputText, $imputKey, $blockSize);

		if($acao == 'encode'):
			$enc = $aes->encrypt();
			return  $enc;
		endif;

		if($acao == 'decode'):
			$aes->setData($imputText);
			$dec=$aes->decrypt();
			return $dec;
		endif;
	}
	//Função que faz a leitura do conteudo de um arquivo, requer o caminho do arquivo
	function lerArquivo($file){
		//Abre o arquivo em modo de leitura
		$myfile = fopen($file, "r");
		//Realiza a leitura do conteudo do arquivo
		$content = fread($myfile,filesize($file));
		//Fecha o arquivo
		fclose($myfile);
		//Retorna o conteudo lido
		return $content;

	}
	//Função que escreve um conteudo em um arquivo, requer um caminho e a string com o conteudo
	function escreverArquivo($file,$content){
		//Abre o arquivo em modo escrita
		$myfile = fopen($file, "w");
		//Escreve o conteudo
		fwrite($myfile, $content);
		//Fecha o arquivo
		fclose($myfile);

	}
	//Função que gera um nome de arquivo baseado no timestamp, requer um path do arquivo
	function gerarNomeArquivo($in){
		//Divide o arquivo pelas /
		$aux = explode('/', $in);
		//Pega até a penultima posição
		$limite = $aux[count($aux)-1];
		//Define um nome para o arquivo
		$nome = md5(date("Y-m-d H:m:s"));
		$path = '';
		//Define o local e nome do arquivo
		foreach ($aux as $value) {
			if( $value == $limite ){
				$path .= $nome;
				break;
			}
			else
				$path .= $value.'/';

		}
		//Retorna a futura localização do arquivo
		return $path;
	}
	//Função que criptografa um arquivo, requer a localização do arquivo e a chave para criptografar
	function criptoFile($file,$senha){
		//Le o arquivo
		$imagem = lerArquivo($file);
		//Transforma em base 64
		$img_base64 = base64_encode($imagem);
		//Criptografa o arquivo
		$imagem_cripto = aes($img_base64,'encode',$senha);
		//Gera um nome único para o arquivo
		$file = gerarNomeArquivo($file);
		//Escreve o arquivo no servidor
		escreverArquivo($file,$imagem_cripto);
		//Retorno nome do arquivo, jutamente com o caminho para acessa-lo
		return $file;
	}
	//Função responsável por decriptografar um conteudo, requer o a localização do arquivo e a chave para decriptografar
	function decriptoFile($file,$senha){
		//Le o arquivo
		$imagem = lerArquivo($file);
		//Decriptografa o arquivo com a senha enviada atraves do usuario
		$imagem = aes($imagem,'decode',$senha);
		//retorna o conteudo do arquivo
		return $imagem;

	}

	$in = "uploads/img.jpg";
	$out = "uploads/ada91ae950e3390ee25b4debb2541092";

	// $teste = lerArquivo($in);

	// $teste = criptoFile($in,$_GET['senha']);
	$teste = decriptoFile($out,$_GET['senha']);
	// $teste = lerArquivo($in);
	// $teste = base64_encode($teste);

?>

<img src="data:image/png;base64,<?php echo $teste; ?>" alt="">