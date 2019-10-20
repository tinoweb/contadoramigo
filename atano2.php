<?php 

	ini_set('display_errors',1);
	ini_set('display_startup_erros',1);
	error_reporting(E_ALL);

/** 
 * Classe criada para pegar os do xml
 */
 class ESocialXML {

	public function GeraXML($grupo_de_eventos){

		// Pega o xml do modelo.
		$modelo = $this->PegaXML($grupo_de_eventos);

		// Verifica se houve erro.
		if($modelo['status']){

			$xml = $modelo['xml'];
			
			$dadosParaXML = $this->$grupo_de_eventos($grupo_de_eventos);
			
			// Percorre o array com os dados a serem incluidos no xml.
			foreach( $dadosParaXML as $key=>$val ) {

				// Substitui as chaves pelos valores informados.
				$xml = str_replace('{/'.$key.'/}', $val, $xml);
			}
			
			#  Configura a saida do conteúdo para o formato XML
			header( 'Content-type: text/xml' );
						
			echo $xml;
			
		} else {
			
			echo "ERRO";
		}
	}
	 	 
	// Método criado para pegar o XML do modelo.
	private function PegaXML($file){
		
		$xml = '';
		
		// Verifica se o arquivo de modelo do xml existe.
		if( file_exists("eSocial_modelos_xml/".$file.".xml") ) {
			
			// Abre o arquivo. 
			$modelXML = fopen("eSocial_modelos_xml/".$file.".xml", "r") or die("Unable to open file!");

			// Pega as linhas do arquivo.
			while(!feof($modelXML)){
				$xml .= fgets($modelXML);
			}

			// Fecha o arquivo.
			fclose($modelXML);

			return array('xml'=>$xml,'status'=>true);
			
		} else {
				
			return array('xml'=>$xml,'status'=>false);
		}
	}
	
	// Método  
	private function S1000(){
				
		$dadosXml['tpAmb'] = '1';
		$dadosXml['procEmi'] = '1';
		$dadosXml['verProc'] = '1';
		$dadosXml['tpInsc'] = '1';
		$dadosXml['nrInsc'] = '1';
		$dadosXml['iniValid'] = '1';
		$dadosXml['nmRazao'] = '1';
		$dadosXml['classTrib'] = '1';
		$dadosXml['natJurid'] = '1';
		$dadosXml['indCoop'] = '1';
		$dadosXml['indConstr'] = '1';
		$dadosXml['indDesFolha'] = '1';
		$dadosXml['indOptRegEletron'] = '1';
		$dadosXml['indEntEd'] = '1';
		$dadosXml['indEtt'] = '1';
		$dadosXml['nmCtt'] = '1';
		$dadosXml['cpfCtt'] = '1';
		$dadosXml['foneFixo'] = '1';
		$dadosXml['contatoEmail'] = '1';
		$dadosXml['cnpjSoftHouse'] = '1';
		$dadosXml['nmRazao'] = '1';
		$dadosXml['nmCont'] = '1';
		$dadosXml['telefone'] = '1';
		$dadosXml['softwareHouseEmail'] = '1';
		$dadosXml['indSitPJ'] = '1';
		
		return $dadosXml;
	}  
 }

$instancia = new ESocialXML();

$grupo_de_eventos = 'S1000';

echo $instancia->GeraXML($grupo_de_eventos);


?>




