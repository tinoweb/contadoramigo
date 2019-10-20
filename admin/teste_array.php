<?
include '../conect.php';

include '../session.php';


function formataCNPJ($cnpj){
	return substr($cnpj,0,2) . '.' . substr($cnpj,2,3) . '.' . substr($cnpj,5,3) . '/' . substr($cnpj,8,4) . '-' . substr($cnpj,12,2);
}


			if(file_exists('arquivos_NF/1596584706.txt')){
				$nf = file('arquivos_NF/1596584706.txt'); // lendo o arquivo
				$periodo = substr(trim($nf[0]),12); // pegando o periodo da primeira linha do arquivo
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
				
				foreach($nf as $linha => $texto){
					if($linha > 0 && $linha < count($nf) -1){ // percorrendo somente as linhas de conteudo do arquivo excluindo o cabeçalho e rodape
						$numero_nf = substr($texto,1,8);
						$cnpj_cliente = substr($texto,518,14);
						if((int)$numero_nf == 0) die('CNPJ '.formataCNPJ($cnpj_cliente).' está com NF em branco'); // TRATA ERRO
						array_push($arrNFs,array('nf'=>(int)$numero_nf,'cnpj'=>$cnpj_cliente));
					}
				}
				
				
				
				var_dump($arrNFs);
				
				echo "<BR>";
				echo "<BR>";

				//var_dump($arrNFs);

				
				foreach($arrPagtos as $indicePgto => $dadosPagtos){
					foreach($arrNFs as $indiceNfs => $dadosNFs){
						if($dadosPagtos['cnpj'] === $dadosNFs['cnpj']){
							if($dadosPagtos['nf'] === 0){
								unset($arrNFs[$indiceNfs]);
								unset($arrPagtos[$indicePgto]);
								break;
							}
						}
					}
				}
				

				//echo "<BR>";
				//echo "<BR>";
				
				var_dump($arrNFs);
				
				echo "<BR>";
				echo "<BR>";

				//var_dump($arrNFs);



				
			}
			
?>