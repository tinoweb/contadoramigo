<?php
	header('Content-Type: text/plain');
    require '../editar/configuracoes.inc.php';
	require '../functions/db_connect.inc.php';

    $paginas_menu = "";
    $sql_table = mysql_query("SHOW TABLES");
    while($row_table = mysql_fetch_array($sql_table)):
		$campos = "";
    	$tabela = $row_table[0];
        $paginas_menu = $paginas_menu."'".$tabela."',";
    	echo "case '".$tabela."':";

    	echo "\n\t".'$this->titulo = "__TITULO__";';
        echo "\n\t".'$this->tabela = "'.$tabela.'";';

    	$sql_coluna_p = mysql_query("SHOW KEYS FROM $tabela WHERE Key_name = 'PRIMARY'");
    	$row_coluna_p = mysql_fetch_array($sql_coluna_p);
    	$campo_id = $row_coluna_p['Column_name'];
        echo "\n\t".'$this->campoID = "'.$row_coluna_p['Column_name'].'";';
        echo "\n\t".'$this->operacoes = array( \'inserir\',\'listar\',\'editar\',\'deletar\');';
        echo "\n\t".'$this->listar = array(\'id\'';

        $sql_coluna = mysql_query("SHOW COLUMNS FROM $tabela WHERE Field <> '$campo_id'");
        while($row_coluna = mysql_fetch_array($sql_coluna)):
            echo ',\''.$row_coluna['Field'].'\''  ;
        endwhile;

        echo ',\'acao\'=>array(\'editar\',\'deletar\'));';

        echo "\n\t".'$this->campos = array(';
        $sql_coluna = mysql_query("SHOW COLUMNS FROM $tabela WHERE Field <> '$campo_id'");
        while($row_coluna = mysql_fetch_array($sql_coluna)):
            if( (strstr ( $row_coluna['Field'] , 'image')) ):
        	    $campos = $campos."\n\t\t\"".$row_coluna['Field']."\" => array('Imagem','upload-img','../assets/images/',200,200),";
            elseif( (strstr ( $row_coluna['Field'] , 'select')) ):
                $campos = $campos."\n\t\t\"".$row_coluna['Field']."\" => array('Nome','select','tabela','id_tabela','nome_campo'),";
            elseif( (strstr ( $row_coluna['Field'] , 'text')) ):
                $campos = $campos."\n\t\t\"".$row_coluna['Field']."\" => array('Titulo do Campo','textarea',1000,'placeholder'),";
            elseif( (strstr ( $row_coluna['Field'] , 'dat')) ):
                $campos = $campos."\n\t\t\"".$row_coluna['Field']."\" => array('Data','date','Data'),";
            else:
                $campos = $campos."\n\t\t\"".$row_coluna['Field']."\" => array('Titulo','input',255,'Titulo','Titulo'),";
            endif;

        endwhile;
        echo substr($campos,0,-1);
        echo "\n\t".');';
        echo "\n".'break;'."\n\n";
    endwhile;

    echo $paginas_menu;

    # case 'categoria_empreendimento':
	# 	$this->tabela = "categoria_empreendimento";
	# 	$this->titulo = "Categoria dos Empreendimentos";
	# 	$this->campoID = "id";
    #   $this->operacoes = array('inserir','listar','editar','deletar');
    #   $this->listar = array('id','acao'=>array('editar','deletar'));
	# 	$this->campos = array(
	# 		"titulo" => array('input',255,'placeholder','type')
	# 	);
	# break;
?>