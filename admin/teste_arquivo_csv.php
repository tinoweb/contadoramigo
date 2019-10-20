<?php
include '../conect.php';

include '../session.php';

include 'check_login.php';


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
    ############ Edit settings ##############
    $UploadDirectory    = 'arquivos_CIELO/'; //specify upload directory ends with / (slash)
    ##########################################
 
 
	if (!is_dir($UploadDirectory)) {
		mkdir($UploadDirectory);
		chmod($UploadDirectory, 0777);
	}
	// excluindo todos os arquivos de nota fiscal existentes
	//array_map('unlink', glob($UploadDirectory."*.csv"));

    
    $File_Name          = "lancamentos.csv";//strtolower($_FILES['FileInput']['name']);
    $File_Ext           = substr($File_Name, strrpos($File_Name, '.')); //get file extention
//    $Random_Number      = rand(0, 9999999999); //Random number to be added to name.
    $NewFileName        = $File_Name; //new file name
    
//	if(!file_exists($UploadDirectory.$NewFileName)){
//		if(move_uploaded_file($_FILES['FileInput']['tmp_name'], $UploadDirectory.$NewFileName ))
//		   {

			if(file_exists($UploadDirectory.$NewFileName)){
				$cielo = file($UploadDirectory.$NewFileName); // lendo o arquivo
				
				$arrDadosArquivo = array();

				$cielo = str_replace("\"","",$cielo);
				
				foreach($cielo as $linha => $texto){
					$dados = explode(";",$texto);
					//echo "'" . $linha . " - " . $texto . "'<br>";
					switch($linha){
						case 0:
							$varLinha1 = $dados[0];
							$arrLinha1 = explode(": ",$varLinha1);
							$arrPeriodoLinha1 = explode(" a ",$arrLinha1[1]);
							$strDtInicial = $arrPeriodoLinha1[0];
							$strDtFinal = $arrPeriodoLinha1[1];
							
							$vDtInicial = formataDataDB($strDtInicial);
							//echo date('Y-m-d',strtotime("-1 month, -1 day",strtotime($vDtInicial)));
							$vDtFinal = formataDataDB($strDtFinal);
							
//							$dDtInicial = substr($strDtInicial,0,2);
//							$mDtInicial = substr($strDtInicial,3,2);
//							$yDtInicial = substr($strDtInicial,-4);

//							$dDtFinal = substr($strDtFinal,0,2);
//							$mDtFinal = substr($strDtFinal,3,2);
//							$yDtFinal = substr($strDtFinal,-4);
							
//							print_r('<br>' . $dDtInicial.$mDtInicial.$yDtInicial . '-' . $dDtFinal. $mDtFinal. $yDtFinal . '<br>');
							array_push($arrDadosArquivo, array('dtInicial'=>$vDtInicial));
							array_push($arrDadosArquivo, array('dtFinal'=>$vDtFinal));
						break;
						case 1:
							$total = $dados[7];
							
							array_push($arrDadosArquivo, array('total'=>formataValor($total)));
						break;
						default:
							if($linha != 2){
								$arrDadosLinhasArquivo = array('dtVenda'=>formataDataDB($dados[0]),'dtPagamento'=>formataDataDB($dados[1]),'descricao'=>$dados[2],'resumo'=>$dados[3],'tid'=>str_replace("*","",$dados[4]),'doc'=>$dados[5],'codAutorizacao'=>$dados[6],'valor'=>formataValor($dados[7]),'rejeitado'=>$dados[8],'valorSaque'=>formataValor($dados[9]));
								array_push($arrDadosArquivo, $arrDadosLinhasArquivo);
							}
						break;
					}
//					if($linha > 0 && $linha < count($cielo) -1){ // percorrendo somente as linhas de conteudo do arquivo excluindo o cabeçalho e rodape
//						$numero_nf = substr($texto,1,8);
//						$cnpj_cliente = substr($texto,518,14);
//						if((int)$numero_nf == 0) die('CNPJ '.formataCNPJ($cnpj_cliente).' está com NF em branco'); // TRATA ERRO
//						array_push($arrNFs,array('nf'=>(int)$numero_nf,'cnpj'=>$cnpj_cliente));
//					}
				}
				//var_dump($arrDadosArquivo);
								
				foreach($arrDadosArquivo as $i => $array){
					echo $array['total'];
					
					if($i > 2){
//						foreach($v as $indice => $valor){
							print_r("UPDATE relatorio_cobranca SET data_pagamento = '" . $array['dtPagamento'] . "' WHERE tid = '" . $array['tid'] ."' AND data_pagamento IS NULL LIMIT 1;<BR>");
							//mysql_affected_rows();
//						}
					}
				}
				
				exit;
				
				$periodo = substr(trim($cielo[0]),12); // pegando o periodo da primeira linha do arquivo
				$data_inicial = substr($periodo,0,8);
				$dInicial = substr($data_inicial,6,2);
				$mInicial = substr($data_inicial,4,2);
				$YInicial = substr($data_inicial,0,4);
				
				$data_final = substr($periodo,-8);
				$dFinal = substr($data_final,6,2);
				$mFinal = substr($data_final,4,2);
				$YFinal = substr($data_final,0,4);
				
				// AQUI É ESTIPULADA UMA DATA PARA FILTRAR OS PAGAMENTOS DOS ULTIMOS 30 DIAS
				$vcto = new DateTime('-30 day');
				
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
				// rel.data >= '" . $vcto->format('Y-m-d') . "' // PARA DATAS DE 30 DIAS PARA TRAZ
				$arrPagtos = array();	
				$arrNFs = array();	
				while($pagtos = mysql_fetch_array($rsPagtos)){
					array_push($arrPagtos,array('id'=>$pagtos['idPagto'],'data'=>$pagtos['data'],'nf'=>(int)$pagtos['numero_NF'],'cnpj'=>$pagtos['cnpj']));
				}
				
				foreach($cielo as $linha => $texto){
					if($linha > 0 && $linha < count($cielo) -1){ // percorrendo somente as linhas de conteudo do arquivo excluindo o cabeçalho e rodape
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
			
//		}else{
//			die('Erro ao carregar o arquivo!');
//		}
//	}
//}
//else
//{
//    die('Something wrong with upload! Is "upload_max_filesize" set correctly?');
//}
?>