<?php 

	include 'remessa/bean.php';
	include("../conect.php");
	//Cria um objeto para cada segmento de arquivo
	$header_arquivo 	=   new Registro_header_arquivo();
	$header_lote 		=  	new Registro_header_lote();//Retirando teste, enviamos para ambiente de produção
	$segmento_p 		=  	new Registro_segmento_p();
	$segmento_q 		=  	new Registro_segmento_q();
	$trailer_lote 		=  	new Registro_trailer_lote();
	$trailer_arquivo 	=   new Registro_trailer_arquivo();
	
	//Header do arquivo		
	$string = 	$header_arquivo->getArquivo();
	$string .= 	$header_lote->getArquivo();

	//Consulta para geração do arquivo remessa com todos os boletos no intervalo desejado
	$consulta = mysql_query("SELECT * FROM boleto WHERE remessa_gerada = '0' ");
	
	//Contador de segmentos, p começa em 1 e q em dois e incrementa de 2 em dois cada um deles
	$cont_segmento_p = 1;
	$cont_segmento_q = 2;
	//Gerar um segmento p e um segmento q para cada boleto a ser registrado
	$linhas = 0;
	while( $boleto = mysql_fetch_array($consulta) ){

		//Define os dados do segmento P para este boleto, caso comentado, puxa dados default
		$segmento_p->setDados($boleto,$cont_segmento_p);
		$string .= 	$segmento_p->getArquivo();

		//Define os dados do segmento Q para este boleto, caso comentado, puxa dados default
		$segmento_q->setDados($boleto,$cont_segmento_q);	
		$string .= 	$segmento_q->getArquivo();
		
		//Incrementa o contador de segmentos
		$cont_segmento_p += 2;
		$cont_segmento_q += 2;
		$linhas = $linhas + 1;

		mysql_query("UPDATE boleto set remessa_gerada = 1 , gerassao_remessa = '".date("Y-m-d H:m:s")."' WHERE id = '".$boleto['id']."'  ");
	}

	//Definir as quantidades para o trailer de arquivo
	$trailer_lote->quantidade_de_registros_do_lote = $trailer_lote->zeros(6-strlen(strval($linhas*2+2))).strval($linhas*2+2);//Total de linhas do lote (inclui Header de lote, Registros e Trailer de lote).

	//Definir as quantidades para o trailer de lote
	$trailer_arquivo->quantidade_de_lotes_do_arquivo = $trailer_arquivo->zeros(6-strlen(strval(count(1)))).strval(count(1));//Informar quantos lotes o arquivo possui.
	$trailer_arquivo->quantidade_de_registros_do_arquivo = $trailer_arquivo->zeros(6-strlen(strval($linhas*2+4))).strval($linhas*2+4);//Quantidade igual ao número total de registros (linhas) do arquivo.

	$string .= 	$trailer_lote->getArquivo();
	$string .= 	$trailer_arquivo->getArquivo();

	//Gera um arquivo com nome teste.rem, caso não informe ao construtor o nome do arquivo, e escreve o conteudo gerado pelos header, segmentos e trailers de arquivos
	$file = new File();

	$file_name = 'files/'.date("Y-m-d").'.rem';
	$file->write(strtoupper($string),$file_name);

	$consulta = mysql_query("INSERT INTO `arquivo_remessa`(`id`, `nome`, `status`, `gerado`, `baixado`) VALUES ('','".date("Y-m-d").".rem','gerado','".date("Y-m-d H:m:s")."','0')");

	$id = mysql_insert_id();

	// //Exibe o link para download do arquivo rmessa ou informa que nenhuma remessa foi gerada no momento
	// if( $linhas == 0 ):
	// 	echo 'Não existem remessas a serem enviadas no momento';
	// else:
	// 	echo '<a href="download_arquivo_remessa.php?file='.$id.'" title="">Download do arquivo remessa gerado</a>';
	// endif;


?>
