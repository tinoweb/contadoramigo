<?php
include '../conect.php';

include '../session.php';

include 'check_login.php';

function formataCNPJ($cnpj){
	return substr($cnpj,0,2) . '.' . substr($cnpj,2,3) . '.' . substr($cnpj,5,3) . '/' . substr($cnpj,8,4) . '-' . substr($cnpj,12,2);
}

if(isset($_FILES["FileInput"]) && $_FILES["FileInput"]["error"]== UPLOAD_ERR_OK)
{
    ############ Edit settings ##############
    $UploadDirectory    = 'arquivos_NF/'; //specify upload directory ends with / (slash)
    ##########################################
 
 
	if (!is_dir($UploadDirectory)) {
		mkdir($UploadDirectory);
		chmod($UploadDirectory, 0777);
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
				$vcto = new DateTime('-30 day');
	
				$rsPagtos = mysql_query("
					SELECT 
						rel.idRelatorio idPagto
						, rel.data
						, rel.numero_NF
						, REPLACE(REPLACE(REPLACE(d.documento,'.',''),'-',''),'/','') documento
					FROM
						relatorio_cobranca rel
						INNER JOIN dados_cobranca d ON rel.id = d.id
					WHERE 
						
						rel.data BETWEEN '".($data_inicial_4dias_antes)."' AND '".($data_final_formatada)."'
						AND rel.resultado_acao IN ('2.1','1.2')
						AND (rel.numero_NF = '' OR rel.numero_NF = 0)
					ORDER BY data 
				");
				
/*
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
						
						rel.data BETWEEN '".($data_inicial_4dias_antes)."' AND '".($data_final_formatada)."'
						AND rel.resultado_acao IN ('2.1','1.2')
						AND (rel.numero_NF = '' OR rel.numero_NF = 0)
					ORDER BY data 
				");
*/	
/*
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
						AND (rel.numero_NF = '' OR rel.numero_NF = 0)
					ORDER BY data 
				");
*/
				// rel.data >= '" . $vcto->format('Y-m-d') . "' // PARA DATAS DE 30 DIAS PARA TRAZ
				$arrPagtos = array();	
				$arrNFs = array();	
				while($pagtos = mysql_fetch_array($rsPagtos)){
					array_push($arrPagtos,array('id'=>$pagtos['idPagto'],'data'=>$pagtos['data'],'nf'=>(int)$pagtos['numero_NF'],'cnpj'=>$pagtos['documento']));
				}
				
				foreach($nf as $linha => $texto){
					if($linha > 0 && $linha < count($nf) -1){ // percorrendo somente as linhas de conteudo do arquivo excluindo o cabeçalho e rodape
						$numero_nf = substr($texto,1,8);
						$cnpj_cliente = substr($texto,518,14);
						if((int)$numero_nf == 0) die('CNPJ '.formataCNPJ($cnpj_cliente).' está com NF em branco'); // TRATA ERRO
						array_push($arrNFs,array('nf'=>(int)$numero_nf,'cnpj'=>$cnpj_cliente));
					}
				}
				
				foreach($arrPagtos as $indicePgto => $dadosPagtos){
					foreach($arrNFs as $indiceNfs => $dadosNFs){
						if($dadosPagtos['cnpj'] === $dadosNFs['cnpj']){
							if($dadosPagtos['nf'] === 0){
								mysql_query("UPDATE relatorio_cobranca SET numero_NF = " . $dadosNFs['nf'] . " WHERE idRelatorio = " . $dadosPagtos['id'] . " AND (numero_NF = '' OR numero_NF = 0) LIMIT 1") or die('ERRO AO ATUALIZAR NUMERO DA NF PARA O CNPJ '.formataCNPJ($cnpj_cliente));
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
    die('Something wrong with upload! Is "upload_max_filesize" set correctly?');
}
?>