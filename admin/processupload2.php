<?php

 ini_set('display_errors',1);
 ini_set('display_startup_erros',1);
 error_reporting(E_ALL);

include '../conect.php';

include '../session.php';

include 'check_login.php';

$total_linhas = 0;
$total_linhas_atualizadas = 0;

function formataDataDB($data){
	$dia = substr($data,0,2);
	$mes = substr($data,3,2);
	$ano = substr($data,-4);
	$retorno = $ano.'-'.$mes.'-'.$dia;
	return $retorno;
}

function formataValor($valor){
	$retorno = str_replace(",",".",str_replace(".","",$valor));
	return $retorno;
}
  

if(isset($_FILES["FileInput"]) && $_FILES["FileInput"]["error"]== UPLOAD_ERR_OK)
{
    ############ Edit settings ##############
    $UploadDirectory    = 'arquivos_CIELO/'; //specify upload directory ends with / (slash)
    ##########################################
 
 
	if (!is_dir($UploadDirectory)) {
		mkdir($UploadDirectory);
		chmod($UploadDirectory, 0777);
	}
	// excluindo todos os arquivos de nota fiscal existentes
	array_map('unlink', glob($UploadDirectory."*.csv"));

    
    /*
    Note : You will run into errors or blank page if "memory_limit" or "upload_max_filesize" is set to low in "php.ini". 
    Open "php.ini" file, and search for "memory_limit" or "upload_max_filesize" limit 
    and set them adequately, also check "post_max_size".
    */
    
//    //check if this is an ajax request
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
            case 'application/vnd.ms-excel':
            case 'text/csv':
                break;
            default:
                die('Tipo de arquivo inválido!'); //output error
    }
    
	
    $File_Name          = strtolower($_FILES['FileInput']['name']);
    $File_Ext           = substr($File_Name, strrpos($File_Name, '.')); //get file extention
    $Random_Number      = rand(0, 9999999999); //Random number to be added to name.
    $NewFileName        = $Random_Number.$File_Ext; //new file name
    
	if(!file_exists($UploadDirectory.$NewFileName)){
		if(move_uploaded_file($_FILES['FileInput']['tmp_name'], $UploadDirectory.$NewFileName ))
		   {

			if(file_exists($UploadDirectory.$NewFileName)){
				$cielo = file($UploadDirectory.$NewFileName); // lendo o arquivo
				
				$arrDadosArquivo = array();

				$cielo = str_replace("\"","",$cielo);
				
				// Percorre as linhas do arquivo da cielo para criar um array com os dados.
				foreach($cielo as $linha => $texto){
					
					$dados = explode(";",$texto);

					switch($linha){
						case 0: // CABEÇALHO
							array_push($arrDadosArquivo, array());
							break;
						case 1:
							array_push($arrDadosArquivo, array());
							break;
						case 2:
							array_push($arrDadosArquivo, array());
							break;
//						case 3: // Titulo do arquivo
//							array_push($arrDadosArquivo, array());
//							break;							
						default:
							if($linha != 2){
								$total_linhas += 1;
								
								// Este cara foi alterado no dia 25/10/2017 devido alteração da cielo.
								$arrDadosLinhasArquivo = array('dtPagamento' => formataDataDB($dados[0])
									,'dtVenda' => formataDataDB($dados[1])
									,'cartao' => $dados[4]
									,'tid' => str_replace("*","",$dados[5])
									,'codAutorizacao' => $dados[7]
							   		,'valorBruto'=>formataValor($dados[8])
								);
								array_push($arrDadosArquivo, $arrDadosLinhasArquivo);
							}
						break;
					}

				}
				
				// Percorre o array com os dados da cielo para realizar a atualização dos pagamento. 				
				foreach($arrDadosArquivo as $i => $array){
					
					if($i > 2){
						if(strlen($array['tid']) == 20){
							
							$update1 = "UPDATE relatorio_cobranca SET data_pagamento = '" . $array['dtPagamento'] . "' WHERE tid = '" . $array['tid'] ."' AND data_pagamento IS NULL LIMIT 1";
							
							// Esta Atualização e para confirmar a data do pagamento.
							mysql_query($update1);
							
							// Pega a quantidade de linhas que foram incluida a data de pagamento.
							$total_linhas_atualizadas += mysql_affected_rows();
							
							$update2 = " UPDATE cobranca_contador c " 
										." JOIN relatorio_cobranca r ON c.idRelatorio = r.idRelatorio "
										." SET c.data_pagamento = '".$array['dtPagamento']."' " 
										." WHERE r.tid LIKE '".$array['tid']."'; ";
							
							// Esta atualização e utilizada para atualizar a data do cobranca do contador.
							mysql_query($update2);
						}
					}
				}
				
			}


			die('Total de linhas: ' . $total_linhas . ' - atualizadas: ' . $total_linhas_atualizadas);//Arquivo carregado.<br><div style="clear:both;margin-top:10px;"><input type="button" id="btReload" value="Fechar"></div>');
			
		}else{
			die('Erro ao carregar o arquivo!');
		}
	}
}
else
{
    die('Something wrong with upload! Is "upload_max_filesize" set correctly?');
}
?>