<?php
    include "../seguranca.php"; // Inclui o arquivo com o sistema de segurança
    protegePagina(); // Chama a função que protege a página

    if($_SESSION['gerar'] == 'Desenvolvedor'):

        header('Content-Type: text/plain');
        require '../editar/configuracoes.inc.php';
        require '../functions/db_connect.inc.php';

        $paginas_menu = "";
        $writePage = "";
        $sql_table = mysql_query("SHOW TABLES");

        while($row_table = mysql_fetch_array($sql_table)):
            $campos = "";
            $tabela = $row_table[0];
            $paginas_menu = $paginas_menu."'".$tabela."',";
        endwhile;
        //Carrega cada parte para montar a pagina
        $conteudoParte1 = file_get_contents("parte1.txt");
        $conteudoParte2 = file_get_contents("parte2.txt");
        $conteudoParte3 = file_get_contents("parte3.txt");

        $writePage .= $conteudoParte1;
        $writePage .= $paginas_menu;
        $writePage .= $conteudoParte2;
        $sql_table = mysql_query("SHOW TABLES");
        
        while($row_table = mysql_fetch_array($sql_table)):
            $campos = "";
            $tabela = $row_table[0];
            $paginas_menu = $paginas_menu."'".$tabela."',";
            if($tabela == 'adm_usuarios')
               $writePage.= "\t\t\tcase '".$tabela."':";
            else
                $writePage.= "\t\t\t\tcase '".$tabela."':";

            $writePage.= "\n\t\t\t\t\t".'$this->titulo = "Tabela: '.$tabela.'";';
            $writePage.= "\n\t\t\t\t\t".'$this->tabela = "'.$tabela.'";';

            $sql_coluna_p = mysql_query("SHOW KEYS FROM $tabela WHERE Key_name = 'PRIMARY'");
            $row_coluna_p = mysql_fetch_array($sql_coluna_p);
            $campo_id = $row_coluna_p['Column_name'];
            $writePage.= "\n\t\t\t\t\t".'$this->campoID = "'.$row_coluna_p['Column_name'].'";';
            $writePage.= "\n\t\t\t\t\t".'$this->operacoes = array( \'inserir\',\'listar\',\'editar\',\'deletar\');';
            $writePage.= "\n\t\t\t\t\t".'$this->listar = array(\'id\'';

            $sql_coluna = mysql_query("SHOW COLUMNS FROM $tabela WHERE Field <> '$campo_id'");
            //Escreve os campos da tabela
            while($row_coluna = mysql_fetch_array($sql_coluna)):
                $writePage.= ',\''.$row_coluna['Field'].'\''  ;
                if( (strstr ( $row_coluna['Field'] , 'select')) ):
                    $writePage.= "=>array('tabela','campo','id')";
                endif;

            endwhile;

            //Define as operações possíveis com a tupla
            $writePage.= ',\'acao\'=>array(\'editar\',\'deletar\'));';

            $writePage.= "\n\t\t\t\t\t".'$this->campos = array(';
            $sql_coluna = mysql_query("SHOW COLUMNS FROM $tabela WHERE Field <> '$campo_id'");
            while($row_coluna = mysql_fetch_array($sql_coluna)):
                if( (strstr ( $row_coluna['Field'] , 'image')) ):
                    //Se for imagem, traz dados do tipo imagem
                    $campos = $campos."\n\t\t\t\t\t\t\"".$row_coluna['Field']."\" => array('".ucfirst(strtolower($row_coluna['Field']))."','upload-img','../assets/images/',200,200),";
                elseif( (strstr ( $row_coluna['Field'] , 'select')) ):
                    //Se for select, traz dados do tipo select
                    $campos = $campos."\n\t\t\t\t\t\t\"".$row_coluna['Field']."\" => array('".ucfirst(strtolower($row_coluna['Field']))."','select','tabela','id','campo'),";
                elseif( (strstr ( $row_coluna['Field'] , 'text')) ):
                    //Se for textarea, traz dados do tipo textarea
                    $campos = $campos."\n\t\t\t\t\t\t\"".$row_coluna['Field']."\" => array('".ucfirst(strtolower($row_coluna['Field']))."','textarea',1000,'".ucfirst(strtolower($row_coluna['Field']))."'),";
                elseif( (strstr ( $row_coluna['Field'] , 'dat')) ):
                    //Se for data, traz dados do tipo data
                    $campos = $campos."\n\t\t\t\t\t\t\"".$row_coluna['Field']."\" => array('Data','date','Data'),";
                elseif( (strstr ( $row_coluna['Field'] , 'arquivo')) ):
                    //Se for upload de aruivo, traz dados do tipo uopload de arquivo
                    $campos = $campos."\n\t\t\t\t\t\t\"".$row_coluna['Field']."\" => array('Arquivo','upload-file','../assets/arquivo/'),";
                elseif( (strstr ( $row_coluna['Field'] , 'radio')) ):
                    //Se for radio, traz dados do tipo radio
                    $campos = $campos."\n\t\t\t\t\t\t\"".$row_coluna['Field']."\" => array('Selecione:','radio', array( '1' => 'Opção 1', '2' => 'Opção 2' )),";
                else:
                    //Traz tipo varchar
                    $campos = $campos."\n\t\t\t\t\t\t\"".$row_coluna['Field']."\" => array('".ucfirst(strtolower($row_coluna['Field']))."','input',255,'".ucfirst(strtolower($row_coluna['Field']))."','text'),";
                endif;

            endwhile;
            $writePage.= substr($campos,0,-1);
            $writePage.= "\n\t\t\t\t\t".');';
            $writePage.= "\n\t\t\t\t".'break;'."\n\n";
        endwhile;
        $writePage .= $conteudoParte3;
        error_reporting(0);
        //$paginas_class = fopen("../editar/paginas.class.php", "w");
        //fwrite($paginas_class, $writePage);
        echo $writePage;
        fclose($paginas_class);
        //echo "Foi gerado o arquivo paginas.class.php na pasta editar com todo o conteúdo";
    else:
        header("Location: ".$url_painel." ");
    endif
?>