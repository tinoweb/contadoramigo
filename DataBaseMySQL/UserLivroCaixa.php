<?
/**
 * Objeto criado para manipulação dos dados de Livro caixa.
 * Autor: Átano de Farias
 * Data: 23/05/2017
 */
class UserLivroCaixa {
	
	// Método criado para realizar a criação da table user_0000_livro_caixa.
	public function CreateTableLivroCaixa($id) {
		
		// Verifica se o id e numerico.
		if(is_numeric($id)) {
		
			// Variável que recebe a instrunção para criação da tabela de livro caixa.  
			$sql = "CREATE TABLE IF NOT EXISTS user_".$id."_livro_caixa ( "
					."	id int(30) NOT NULL AUTO_INCREMENT UNIQUE, "
					."	data date NOT NULL, "
					."	entrada decimal(50,2) NOT NULL, "
					."	saida decimal(50,2) NOT NULL, "
					."	documento_numero varchar(20) NOT NULL, " 
					."	descricao varchar(200) NOT NULL,  "
					."	categoria varchar(125),  "
					."	PRIMARY KEY (id) "
					." )";
			$resultado = mysql_query($sql) 
			or die (mysql_error());
		
		}
	}
	
}