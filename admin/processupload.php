<?php

include '../conect.php';

include '../session.php';

include 'check_login.php';

function formataCNPJ($string){
	return substr($string,0,2) . '.' . substr($string,2,3) . '.' . substr($string,5,3) . '/' . substr($string,8,4) . '-' . substr($string,12,2);
}

function formataCPF($string){
	return substr($string,0,3) . '.' . substr($string,3,3) . '.' . substr($string,6,3) . '-' . substr($string,9,2);
}

if(isset($_FILES["FileInput"]) && $_FILES["FileInput"]["error"]== UPLOAD_ERR_OK)
{
    ############ Edit settings ##############
    $UploadDirectory    = 'arquivos_NF/'; //specify upload directory ends with / (slash)
    ##########################################
 
 
	if (!is_dir($UploadDirectory)) {
		mkdir($UploadDirectory);
		chmod($UploadDirectory, 0755);
	}

	// excluindo todos os arquivos de nota fiscal existentes
	array_map('unlink', glob($UploadDirectory."*.txt"));

    
    /*
    Note : You will run into errors or blank page if "memory_limit" or "upload_max_filesize" is set to low in "php.ini". 
    Open "php.ini" file, and search for "memory_limit" or "upload_max_filesize" limit 
    and set them adequately, also check "post_max_size".
    */
    
    //check if this is an ajax request
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
        die();
    }    
    
    //Is file size is less than allowed size.
    if ($_FILES["FileInput"]["size"] > 5242880) {
        die("Arquivo é muito grande!");
    }

    
    //allowed file type Server side check
    switch(strtolower($_FILES['FileInput']['type']))
        {
            //allowed file types
//            case 'image/png': 
//            case 'image/gif': 
//            case 'image/jpeg': 
//            case 'image/pjpeg':
            case 'text/plain':
//            case 'text/html': //html file
//            case 'application/x-zip-compressed':
//            case 'application/pdf':
//            case 'application/msword':
//            case 'application/vnd.ms-excel':
//            case 'video/mp4':
                break;
            default:
                die('Tipo de arquivo inválido!'); //output error
    }
    
	// ATRIBUINDO O NOME DO ARQUIVO PROCESSADO À VARIÁVEL
//	$temp_arquivo = $_FILES['myfile']['tmp_name'];
//	$nome_arquivo = ($dir_arquivos . '/' . $_FILES['myfile']['name']);

	
    $File_Name          = strtolower($_FILES['FileInput']['name']);
    $File_Ext           = substr($File_Name, strrpos($File_Name, '.')); //get file extention
    $Random_Number      = rand(0, 9999999999); //Random number to be added to name.
    $NewFileName        = $Random_Number.$File_Ext; //new file name

    
	if(!file_exists($UploadDirectory.$NewFileName)){
		if(move_uploaded_file($_FILES['FileInput']['tmp_name'], $UploadDirectory.$NewFileName ))
		   {

			if(file_exists($UploadDirectory.$NewFileName)){

				$nf = file($UploadDirectory.$NewFileName); // lendo o arquivo
				$periodo = substr(trim($nf[0]),12); // pegando o periodo da primeira linha do arquivo
				$data_inicial = substr($periodo,0,8);
				$dInicial = substr($data_inicial,6,2);
				$mInicial = substr($data_inicial,4,2);
				$YInicial = substr($data_inicial,0,4);
					
				$data_inicial_4dias_antes = date('Y-m-d',strtotime(date('Y-m-d',strtotime($YInicial.'-'.$mInicial.'-'.$dInicial)) . "-4 days"));
				
				$data_final = substr($periodo,-8);
				$dFinal = substr($data_final,6,2);
				$mFinal = substr($data_final,4,2);
				$YFinal = substr($data_final,0,4);
				
				$data_final_formatada = date('Y-m-d',strtotime($YFinal.'-'.$mFinal.'-'.$dFinal));
				
				// AQUI É ESTIPULADA UMA DATA PARA FILTRAR OS PAGAMENTOS DOS ULTIMOS 30 DIAS
				//$vcto = new DateTime('-30 day');
				//$vcto = strtotime('-30 day');
	
				// Cria a consulta dos pagamento de acordo com a data da nota informada.
				$rsPagtos = mysql_query("
					SELECT 
						rel.idRelatorio idPagto
						, rel.data
						, rel.numero_NF
						, rel.valor_pago
						, rel.tipo
						, REPLACE(REPLACE(REPLACE(d.documento,'.',''),'-',''),'/','') documento
					FROM
						relatorio_cobranca rel
						INNER JOIN dados_cobranca d ON rel.id = d.id
					WHERE 
						rel.data BETWEEN '".($data_inicial_4dias_antes)."' AND '".($data_final_formatada)."'
						AND rel.resultado_acao IN ('1.2','2.1','8.2','8.3','8.4','8.5','8.6','8.7','8.8','8.9','9.0','9.3','9.4','9.5','9.6','9.7','9.8','10.1','11.0','11.1')
						AND (rel.numero_NF = '' OR rel.numero_NF = 0)
					ORDER BY data 
				");
				
				// rel.data >= '" . $vcto->format('Y-m-d') . "' // PARA DATAS DE 30 DIAS PARA TRAZ
				$arrPagtos = array();	
				while($pagtos = mysql_fetch_array($rsPagtos)){
					
					$valorPago = (float)$pagtos['valor_pago'];
					
					// Verifica se o valor e premium e divide para poder comparar com o numero da nota.
					if($pagtos['tipo'] == 'Premium'){
						$valorPago = (float)$valorPago/2;
					}
					
					array_push($arrPagtos, array('id'=>$pagtos['idPagto'], 'data'=>$pagtos['data'], 'nf'=>(int)$pagtos['numero_NF'], 'documento'=>$pagtos['documento'],'valor_pago'=>$valorPago ));
				}

				$arrNFs = array();				
				
				// percorrendo linhas do arquivo.
				foreach($nf as $linha => $texto){
					
					// percorrendo somente as linhas de conteudo do arquivo excluindo o cabeçalho e rodape
					if($linha > 0 && $linha < count($nf) -1){ 
						
						//Pega o numero da nota fiscal.
						$numero_nf = substr($texto,1,8);
						
						// Pega o valor da nota fiscal.
						$valor_nota = ((int)substr($texto,455,5)).'.'.substr($texto,460,2);
						
						// Converte para float.
						$valor_nota = (float)$valor_nota;
												
						// Pega o documento do cliente.
						$documento_cliente = substr($texto,518,14);

						// Realiza a formatação do documento para CPF e CNPJ.
						$CNPJ_formatado = formataCNPJ($documento_cliente);
						$CPF_formatado = formataCPF($documento_cliente);
						
						if((int)$numero_nf == 0) die('Documento '.$documento_cliente.' está com NF em branco'); // TRATA ERRO
						//array_push($arrNFs,array('nf'=>(int)$numero_nf,'documento'=>(string)$documento_formatado));
						array_push($arrNFs,array('nf'=>(int)$numero_nf,'cnpj'=>$documento_cliente,'valor'=>$valor_nota,'cpf'=>substr($documento_cliente,-11)));
					}
				}
				
//				var_dump($arrPagtos);
//				echo "<BR><BR><BR>";
//				var_dump($arrNFs);
//				exit;
				
				foreach($arrPagtos as $indicePgto => $dadosPagtos){
					
					foreach($arrNFs as $indiceNfs => $dadosNFs){
						
						//if($dadosPagtos['documento'] === $dadosNFs['documento']){
						if(($dadosPagtos['documento'] === $dadosNFs['cnpj']) || ($dadosPagtos['documento'] === $dadosNFs['cpf'])){
							if($dadosPagtos['nf'] === 0 && $dadosPagtos['valor_pago'] === $dadosNFs['valor']){
							
								//mysql_query("UPDATE relatorio_cobranca SET numero_NF = " . $dadosNFs['nf'] . " WHERE idRelatorio = " . $dadosPagtos['id'] . " AND (numero_NF = '' OR numero_NF = 0) LIMIT 1") or die('ERRO AO ATUALIZAR NUMERO DA NF PARA O DOCUMENTO '.$documento_formatado);
								mysql_query("UPDATE relatorio_cobranca SET numero_NF = " . $dadosNFs['nf'] . " WHERE idRelatorio = " . $dadosPagtos['id'] . " AND (numero_NF = '' OR numero_NF = 0) LIMIT 1") or die('ERRO AO ATUALIZAR NUMERO DA NF PARA O DOCUMENTO '.$documento_cliente);
								
								
								// Remove do arrays os dados do pagamento e os dados da nota.
								unset($arrNFs[$indiceNfs]);
								unset($arrPagtos[$indicePgto]);
								break;
							}
						}
					}
				}
				
			}

			die('');//Arquivo carregado.<br><div style="clear:both;margin-top:10px;"><input type="button" id="btReload" value="Fechar"></div>');
			
		}else{
			die('Erro ao carregar o arquivo!');
		}
	}
}
else
{
    die('Algo errado ocorreu no upload do arquivo.! A configuração de "upload_max_filesize" está correta?');
}
?>