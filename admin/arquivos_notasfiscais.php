<?php 
include '../conect.php';

//include '../session.php';

//include 'check_login.php';

if(isset($_FILES)){
	
	$dir_arquivos = "arquivos_NF";
	if (!is_dir($dir_arquivos)) {
		mkdir($dir_arquivos);
		chmod($dir_arquivos, 0777);
	}
	// excluindo todos os arquivos de nota fiscal existentes
	array_map('unlink', glob("arquivos_NF/*.txt"));
	
	// ATRIBUINDO O NOME DO ARQUIVO PROCESSADO À VARIÁVEL
	$temp_arquivo = $_FILES['myfile']['tmp_name'];
	$nome_arquivo = ($dir_arquivos . '/' . $_FILES['myfile']['name']);
	
	// SUBINDO O ARQUIVO DE NF
	if(!file_exists($nome_arquivo)) move_uploaded_file($temp_arquivo,$nome_arquivo);
	
	if(file_exists($nome_arquivo)){
		$nf = file($nome_arquivo); // lendo o arquivo
		$periodo = substr(trim($nf[0]),12); // pegando o periodo da primeira linha do arquivo
		$data_inicial = substr($periodo,0,8);
		$dInicial = substr($data_inicial,6,2);
		$mInicial = substr($data_inicial,4,2);
		$YInicial = substr($data_inicial,0,4);
		
		$data_final = substr($periodo,-8);
		$dFinal = substr($data_final,6,2);
		$mFinal = substr($data_final,4,2);
		$YFinal = substr($data_final,0,4);
		
		$rsPagtos = mysql_query("
			SELECT 
				rel.idRelatorio idPagto
				, rel.data
				, rel.numero_NF
				, REPLACE(REPLACE(REPLACE(d.cnpj,'.',''),'-',''),'/','') cnpj
			FROM
				relatorio_cobranca rel
				INNER JOIN dados_da_empresa d ON rel.id = d.id
			WHERE 
				rel.data BETWEEN '".($YInicial.'-'.$mInicial.'-'.$dInicial)."' AND '".($YFinal.'-'.$mFinal.'-'.$dFinal)."'
				AND rel.resultado_acao IN ('2.1','1.2')
			ORDER BY data DESC
		");	
		$arrPagtos = array();	
		$arrNFs = array();	
		while($pagtos = mysql_fetch_array($rsPagtos)){
			array_push($arrPagtos,array('id'=>$pagtos['idPagto'],'data'=>$pagtos['data'],'nf'=>(int)$pagtos['numero_NF'],'cnpj'=>$pagtos['cnpj']));
		}
	//				REPLACE(REPLACE(REPLACE(d.cnpj,'.',''),'-',''),'/','') = 
	
	
		
		foreach($nf as $linha => $texto){
			if($linha > 0 && $linha < count($nf) -1){ // percorrendo somente as linhas de conteudo do arquivo excluindo o cabeçalho e rodape
				$numero_nf = substr($texto,1,8);
				$cnpj_cliente = substr($texto,518,14);
				array_push($arrNFs,array('nf'=>(int)$numero_nf,'cnpj'=>$cnpj_cliente));
			}
		}
		
		foreach($arrPagtos as $dadosPagtos){
			foreach($arrNFs as $dadosNFs){
				if($dadosPagtos['cnpj'] === $dadosNFs['cnpj']){
					if($dadosPagtos['nf'] === 0){
						mysql_query("UPDATE relatorio_cobranca SET numero_NF = " . $dadosNFs['nf'] . " WHERE idRelatorio = " . $dadosPagtos['id']) or die(exit);
					}
	//					echo "pagtos: ";
	//					var_dump($dadosPagtos);
	//					echo "<BR>NFs: ";
	//					var_dump($dadosNFs);
	//					echo "<BR>";
				}
			}
		}
		//var_dump($arrNFs);
		
	}
	
	echo "1";
	exit;
}else{
	echo "0";
	exit;	
}

?>