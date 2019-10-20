<?php 
    
    session_start();
    include 'header.php';
    include 'gerenciar-boletos.class.php';
    include '../datas.class.php';

    if( isset($_GET['pagina']) ){
       $paginacao = 'LIMIT '. (50*( intval($_GET['pagina'] ) - 1)).',50';
    }
    else
        $paginacao = 'LIMIT 0,50';
    $filtro = "WHERE 1 = 1";
    $ordem = 'id DESC';
    if( isset( $_GET['nosso_numero'] ) ):    
        $filtro .= " AND nosso_numero = '".$_GET['nosso_numero']."'";
    endif;
    if( isset( $_GET['id_user'] ) ):    
        $filtro .= " AND id_user = '".$_GET['id_user']."'";
    endif;
    if( isset( $_GET['competencia'] ) ):    
        $ordem = " vencimento ASC  ";
        $parametros = '&competencia';
    endif;

    

    $boletos_consulta = mysql_query("SELECT * FROM boletos_registrados ".$filtro." ORDER BY ".$ordem." ".$paginacao." ");
    $datas = new Datas();

?>
<div class="principal minHeight">
    <div class="titulo" style="margin-bottom:10px; float:left">Boletos</div>
    <div style="float: left;width: 100%;margin-bottom: 10px;">
        <form id="form_id_user" method="get" accept-charset="utf-8" style="float: left;margin-right: 10px;">
            ID: <input type="text" name="id_user" id="id_user" value="<?php echo $_GET['id_user'] ?>" placeholder="">
        </form>
        <form id="form_nosso_numero" method="get" accept-charset="utf-8" style="float: left;margin-right: 10px;">
            ou Nosso Número: <input type="text" name="nosso_numero" id="nosso_numero" value="<?php echo $_GET['nosso_numero'] ?>" placeholder="">
        </form>
        <input name="Alterar" type="submit" value="Filtrar" id="filtrar" style="float: left;">
    </div>
    <script>
    
        
        $( document ).ready(function() {
            
            $("#filtrar").click(function() {
                if( $("#id_user").val() != '' )
                    $("#form_id_user").submit();
                else if( $("#nosso_numero").val() != '' )
                    $("#form_nosso_numero").submit();
            });
            
        });
    
    </script>
    <table border="0" cellspacing="2" cellpadding="4" style="font-size:11px;" width="100%">
        <tbody>
            <tr>
                <th width="25%" align="left">Usuario</th>
                <th width="9%" align="center">Vencimento</th>
                <th width="9%" align="center">Competência</th>
                <th width="10%" align="center">Nosso Número</th>
                <th width="8%" align="center">Status</th>
                <th width="9%" align="center">Tipo</th>
                <th width="10%" align="center">Valor</th>
                <th width="10%" align="center">Geração</th>
                <th width="10%" align="center">Remessa</th>
            </tr> 
            <?php 

                while( $objeto_consulta = mysql_fetch_array($boletos_consulta) ){

                    $dados_boleto = new Dados_boletos($objeto_consulta);
                    

                    ?>
                    <tr valign="top">
                		<td align="left">
                            <a href="cliente_administrar.php?id=<?php echo $dados_boleto->getid_user() ?>" title="<?php echo $dados_boleto->getUser(); ?>" target="_blank">
                                <?php echo substr( $dados_boleto->getUser() , 0 , 30 ); ?>
                            </a>
                        </td>
                        <td>
                            <?php echo $datas->desconverterData($dados_boleto->getvencimento()); ?>
                        </td>
                        <td>
                            <?php 
                                if( $dados_boleto->gettipo() == 'mensalidade' )
                                    echo $datas->desconverterData($dados_boleto->getCompetencia()); 
                                else
                                    echo '-';
                            ?>
                        </td>
                        <td>
                            <?php echo $dados_boleto->getnosso_numero(); ?>
                        </td>
                        <td>
                            <?php echo ucfirst($dados_boleto->getstatus()); ?>
                        </td>
                        <td>
                            <?php echo ucfirst($dados_boleto->gettipo()); ?>
                        </td>
                        <td>
                            <?php echo $dados_boleto->toMoney(($dados_boleto->gettipo())); ?>
                        </td>
                        <td>
                            <?php echo $datas->desconverterData($dados_boleto->getdata_geracao()); ?>
                        </td>
                        <td>
                            <?php echo $dados_boleto->getremessa_aceita(); ?>
                        </td>
                    </tr>
            <?php 
                    
                } 

            ?>
        </tbody>
    </table>
    <br />
    <?php 

                $boletos_consulta = mysql_query("SELECT * FROM boletos_registrados");
                $totalPesquisado = mysql_num_rows($boletos_consulta);
                $quantidadeResultados = 50;
                $paginaAtual = (isset($_GET['pagina'])) ? $_GET['pagina'] : '1';
                $qtdPaginasAntesEDepois = 5;

                if($totalPesquisado > $quantidadeResultados && $_GET['nosso_numero'] == "" && $_GET['id_user'] == '' ) {
                    echo "<div style=\"width: 49%; float: left; text-align: left;\">";
                    if($paginaAtual == 1) {
                        echo 'anterior | ';
                    } else {
                        echo '<a href="gerenciar-boletos.php?pagina=' . ($paginaAtual - 1) . $parametros.'">anterior</a> |';
                    }
                    
                    for($i = ($paginaAtual-$qtdPaginasAntesEDepois); $i <= $paginaAtual-1; $i++) { 
                        // Se o número da página for menor ou igual a zero, não faz nada 
                        // (afinal, não existe página 0, -1, -2..) 
                        if($i > 0) { 
                            echo '<a href="gerenciar-boletos.php?pagina=' . $i .$parametros. '">' . $i . '</a> |';
                        }
                    }

                    echo ' ' . $paginaAtual . ' |';

                    for($i = $paginaAtual+1; $i <= $paginaAtual+$qtdPaginasAntesEDepois; $i++) { 
                        // Verifica se a página atual é maior do que a última página. Se for, não faz nada. 
                        if($i <= ceil($totalPesquisado / $quantidadeResultados)) { 
                            echo '<a href="gerenciar-boletos.php?pagina=' . $i .$parametros. '">' . $i . '</a> |';
                        }
                    }

                /*  for($i = 1; $i <= ceil($totalPesquisado / $quantidadeResultados); $i++) { 
                        if($i == $paginaAtual) {
                            echo ' '.$i.' |';
                        } else {
                            echo ' <a href="gerenciar-boletos.php?pagina=' . $i . '">' . $i . '</a> |';
                        } 
                    }*/
                    
                    if($paginaAtual == ceil($totalPesquisado / $quantidadeResultados)) {
                        echo ' próxima';
                    } else {
                        echo ' <a href="gerenciar-boletos.php?pagina=' . ($paginaAtual + 1) .$parametros. '">próxima</a>';
                    }
                    echo "</div>";
                }


            ?>

</div>
<br />
<?php include '../rodape.php'; ?>