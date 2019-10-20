<?

// faz a requisição do arquivo de conexão com o banco de dados. 
require_once('conect.php');

function insertXML($empresaId, $codigo_do_evento, $doc, $xml_envio) {
	
	$insert = "INSERT INTO esocial_envio_retorno_xml ( user_id, data_inclusao, codigo_do_evento, CNPJ, xml_envio ) VALUES ('".$empresaId."', NOW(), '".$codigo_do_evento."', '".$doc."', '".$xml_envio."');";
	
	$consulta = mysql_query($insert);
	
	// Retorna o id da inclusão.
	return mysql_insert_id();
}

# Instancia do objeto XMLWriter
$xml = new XMLWriter;

# Cria memoria para armazenar a saida
$xml->openMemory();

# Inicia o cabeçalho do documento XML
$xml->startDocument( '1.0' , 'iso-8859-1' );

# Adiciona/Inicia um Elemento / Nó Pai <item>
$xml->startElement("item");

#  Adiciona um Nó Filho <quantidade> e valor 8
$xml->writeElement("quantidade", 8);

#  Adiciona um Nó Filho <preco> e valor 110
$xml->writeElement("preco", 110);

#  Finaliza o Nó Pai / Elemento <Item>
$xml->endElement();

#  Configura a saida do conteúdo para o formato XML
header( 'Content-type: text/xml' );

# Imprime os dados armazenados
$xml = $xml->outputMemory(true);

// Chama o método para realizar a inclusão dos dados para envio do XML.
insertXML('9', '00000', '000.000.000000/00', $xml);

print $xml;

# Salvando o arquivo em disco

# retorna erro se o header foi definido

# retorna erro se outputMemory já foi chamado

//$file = fopen('foo.xml','w+');
//
//fwrite($file,$xml->outputMemory(true));
//
//fclose($file);

?>
