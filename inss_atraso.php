<?php include 'header_restrita.php' ?>
<script src="jquery.maskMoney.js" type="text/javascript"></script>
<div class="principal minHeight">

    <h1>Impostos e Obrigações</h1>
    <h2>Recolhimento do INSS em atraso.</h2>

    <div style="width:100%;">
        <?php 

            $consulta_cnaes = mysql_query("SELECT * FROM dados_da_empresa_codigos WHERE id = '".$_SESSION['id_empresaSecao']."' ");
            
            $anexoIV = false;
            $anexoGeral = false;
            $string = '';
            while( $objeto_consulta_cnaes = mysql_fetch_array($consulta_cnaes) ){

                $consulta_cnae = mysql_query("SELECT * FROM cnae WHERE cnae = '".$objeto_consulta_cnaes['cnae']."' ");
                $objeto_consulta_cnae = mysql_fetch_array($consulta_cnae);

                if( $objeto_consulta_cnae['anexo'] == 'IV' )
                    $anexoIV = true;
                else
                    $anexoGeral = true;

            }
            $tipo_gps = 1;

            if( $anexoIV && !$anexoGeral)
                $tipo_gps = 2; //codigo 2100
            if( $anexoIV && $anexoGeral)
                $tipo_gps = 3; //Verificar codigo
            if( !$anexoIV && !$anexoGeral)
                $tipo_gps = 1; //codigo 2003 

            if( isset($_GET['tipo']) )
                $tipo_gps = $_GET['tipo'];
        ?>

        <?php if( $tipo_gps == 1 ): ?>

            <table width="900" cellpadding="3" style="margin-bottom:10px;">         
                <tbody style="float:left">
                    <tr>
                        <td>Período de apuração</td>
                        <td>
                            <select name="mes" id="mes">
                                <?php 
                                    $mesReferencia = ((int)date('m')) - 1;
                                    if(((int)date('m')) == 1){
                                        $mesReferencia = 12;
                                    }               
                                ?>
                                <option value="">Selecione</option>
                                <option value="1">Janeiro</option>
                                <option value="2">Fevereiro</option>
                                <option value="3">Março</option>
                                <option value="4">Abril</option>
                                <option value="5">Maio</option>
                                <option value="6">Junho</option>
                                <option value="7">Julho</option>
                                <option value="8">Agosto</option>
                                <option value="9">Setembro</option>
                                <option value="10">Outubro</option>
                                <option value="11">Novembro</option>
                                <option value="12">Dezembro</option>
                            </select> de 
                            <select name="ano" id="ano">
                            <?
                            $anoInicio = date('Y') - 5;
                            $anoAtual = date('Y');
                            $anoReferencia = $anoAtual;
                            if(date('m') == '01'){
                                $anoReferencia = $anoAtual - 1;
                            }

                            for($i = $anoInicio; $i <= $anoAtual; $i++) {?>
                                <option value="<?=$i?>"<?
                                echo ($anoReferencia == $i ? " selected" : "");
                                ?>><?=$i?></option>
                            <? } ?>
                            </select> 
                        </td>
                    </tr>
                    <tr>
                        <td align="right" valign="top" class="formTabela">Valor original:</td>
                        <td align="left" valign="top" class="formTabela">
                            <input name="valor_emprestimo" id="valor_emprestimo" class="currency" type="text" size="13">
                        </td>
                    </tr>
                    <tr>
                        <td align="right" valign="top" class="formTabela">Pretendo pagar em:</td>
                        <td align="left" valign="top" class="formTabela">
                            <input name="data_emprestimo" id="data_emprestimo" class="campoData" type="text" size="11"></td>
                    </tr>
                </tbody>
            </table>
            <input type="button" id="calcularDados" value="Calcular" style="margin-bottom:30px;">

            <div style="margin-bottom: 5px;width:100%;float:left">      
                <div style="float: left;width: 90px;">Multa e juros: </div>
                <input type="text" id="multa_juros" class="currency" value="" size="15" disabled="" style="margin-right:10px;float: left;">
            </div>
            <div style="margin-bottom: 10px;width:100%;float:left">      
                <div style="float: left;width: 90px;">Total: </div>
                <input type="text" id="valor_total" class="currency" value="" size="15" disabled="" style="margin-right:10px;float: left;">
            </div>
            <br>
            <input type="hidden" id="codigo_receita" value="2003">
            <input type="button" id="gerar_gps" value="Gerar GPS">

        <?php endif; ?>
        <?php if( $tipo_gps == 2 ): ?>

            <table width="900" cellpadding="3" style="margin-bottom:10px;">         
                <tbody style="float:left">
                    <tr>
                        <td>Período de apuração</td>
                        <td>
                            <select name="mes" id="mes">
                                <?php 
                                    $mesReferencia = ((int)date('m')) - 1;
                                    if(((int)date('m')) == 1){
                                        $mesReferencia = 12;
                                    }               
                                ?>
                                <option value="">Selecione</option>
                                <option value="1">Janeiro</option>
                                <option value="2">Fevereiro</option>
                                <option value="3">Março</option>
                                <option value="4">Abril</option>
                                <option value="5">Maio</option>
                                <option value="6">Junho</option>
                                <option value="7">Julho</option>
                                <option value="8">Agosto</option>
                                <option value="9">Setembro</option>
                                <option value="10">Outubro</option>
                                <option value="11">Novembro</option>
                                <option value="12">Dezembro</option>
                            </select> de 
                            <select name="ano" id="ano">
                            <?
                            $anoInicio = date('Y') - 5;
                            $anoAtual = date('Y');
                            $anoReferencia = $anoAtual;
                            if(date('m') == '01'){
                                $anoReferencia = $anoAtual - 1;
                            }

                            for($i = $anoInicio; $i <= $anoAtual; $i++) {?>
                                <option value="<?=$i?>"<?
                                echo ($anoReferencia == $i ? " selected" : "");
                                ?>><?=$i?></option>
                            <? } ?>
                            </select> 
                        </td>
                    </tr>
                    <tr>
                        <td align="right" valign="top" class="formTabela">Valor original:</td>
                        <td align="left" valign="top" class="formTabela">
                            <input name="valor_emprestimo" id="valor_emprestimo" class="currency" type="text" size="13">
                        </td>
                    </tr>
                    <tr>
                        <td align="right" valign="top" class="formTabela">Pretendo pagar em:</td>
                        <td align="left" valign="top" class="formTabela">
                            <input name="data_emprestimo" id="data_emprestimo" class="campoData" type="text" size="11"> 
                        </td>
                    </tr>
                </tbody>
            </table>
            <input type="button" id="calcularDados" value="Calcular" style="margin-bottom:30px;">

            <div style="margin-bottom: 5px;width:100%;float:left">      
                <div style="float: left;width: 90px;">Multa e juros: </div>
                <input type="text" id="multa_juros" class="currency" value="" size="15" disabled="" style="margin-right:10px;float: left;">
            </div>
            <div style="margin-bottom: 10px;width:100%;float:left">      
                <div style="float: left;width: 90px;">Total: </div>
                <input type="text" id="valor_total" class="currency" value="" size="15" disabled="" style="margin-right:10px;float: left;">
            </div>
            <br>
            <input type="hidden" id="codigo_receita" value="2100">
            <input type="button" id="gerar_gps" value="Gerar GPS">

        <?php endif; ?>
        <?php if( $tipo_gps == 3 ): ?>

            <table width="900" cellpadding="3">         
                <tbody style="float:left">
                    <tr>
                        <td>Período de apuração</td>
                        <td>
                            <select name="mes" id="mes">
                                <?php 
                                    $mesReferencia = ((int)date('m')) - 1;
                                    if(((int)date('m')) == 1){
                                        $mesReferencia = 12;
                                    }               
                                ?>
                                <option value="">Selecione</option>
                                <option value="1">Janeiro</option>
                                <option value="2">Fevereiro</option>
                                <option value="3">Março</option>
                                <option value="4">Abril</option>
                                <option value="5">Maio</option>
                                <option value="6">Junho</option>
                                <option value="7">Julho</option>
                                <option value="8">Agosto</option>
                                <option value="9">Setembro</option>
                                <option value="10">Outubro</option>
                                <option value="11">Novembro</option>
                                <option value="12">Dezembro</option>
                            </select> de 
                            <select name="ano" id="ano">
                            <?
                            $anoInicio = date('Y') - 5;
                            $anoAtual = date('Y');
                            $anoReferencia = $anoAtual;
                            if(date('m') == '01'){
                                $anoReferencia = $anoAtual - 1;
                            }

                            for($i = $anoInicio; $i <= $anoAtual; $i++) {?>
                                <option value="<?=$i?>"<?
                                echo ($anoReferencia == $i ? " selected" : "");
                                ?>><?=$i?></option>
                            <? } ?>
                            </select> 
                        </td>
                    </tr>
                    <tr>
                        <td align="right" valign="top" class="formTabela">Valor original:</td>
                        <td align="left" valign="top" class="formTabela">
                            <input name="valor_emprestimo" id="valor_emprestimo" class="currency" type="text" size="13">
                        </td>
                    </tr>
                    <tr>
                        <td align="right" valign="top" class="formTabela">Pretendo pagar em:</td>
                        <td align="left" valign="top" class="formTabela">
                            <input name="data_emprestimo" id="data_emprestimo" class="campoData" type="text" size="11"> 
                        </td>
                    </tr>
                    <tr>
                        <td align="right" valign="top" class="formTabela">Tem a GPS vencida?</td>
                        <td align="left" valign="top" class="formTabela">
                            <input name="tem_gps" style="margin-bottom:5px;margin-right:5px;" class="respondeu_sim" type="radio" value="sim">Sim 
                            <input name="tem_gps" style="margin-bottom:5px;margin-left:10px;" class="respondeu_nao" type="radio" value="nao"> Não
                        </td>
                    </tr>
                </tbody>
            </table>

            <div style="width:100%;position:relative;float:left;display:none;margin-bottom:10px;" class="resposta_nao">
                <p>Indique as atividades prestadas no período </p>
                <?php 
                    $consulta_cnaes = mysql_query("SELECT * FROM dados_da_empresa_codigos WHERE id = '".$_SESSION['id_empresaSecao']."' ");
                    while( $objeto_consulta_cnaes = mysql_fetch_array($consulta_cnaes) ){

                        $consulta_cnae = mysql_query("SELECT * FROM cnae WHERE cnae = '".$objeto_consulta_cnaes['cnae']."' ");
                        $objeto_consulta_cnae = mysql_fetch_array($consulta_cnae);

                        if( $objeto_consulta_cnae['anexo'] == 'IV' )
                            $anexoIV = true;
                        else
                            $anexoGeral = true;

                        if($objeto_consulta_cnae["tomador"] == "sim" ){
                            echo '  <div style="float: left;width: 100%;position: relative;">
                                        <input type="checkbox" style="margin-left:20px;margin-bottom:5px;" class="codigo_receita_outros anexo_s" name="codigo_receita_cnae" value=""> '.$objeto_consulta_cnae['denominacao'].'
                                        <div class="div_inputs_cnae" style="float: left;width: 100%;margin: 10px 0px 10px 40px;display:none">
                                            Esta atividade está vinculada a empreitada da construção civil?<br>
                                            <input class="abrir_cprb_input" name="cnae_'.$objeto_consulta_cnae['id'].'" type="radio" value="IV" /> Sim (selecione esta opção se estiver executando o serviço para uma obra registrada)
                                            <div style="clear: both; height: 5px" ></div>
                                            <input class="abrir_cprb_input" name="cnae_'.$objeto_consulta_cnae['id'].'" type="radio" value="III" /> Não
                                        </div>
                                    </div>';
                        }
                        else{
                            echo '  <div style="float: left;width: 100%;position: relative;">
                                        <input type="checkbox" style="margin-left:20px;margin-bottom:5px;" class="codigo_receita_outros" name="codigo_receita_cnae" value="'.$objeto_consulta_cnae['anexo'].'"> '.$objeto_consulta_cnae['denominacao'].'
                                    </div>';
                        }
                    }

                ?>
            </div>
            <script>
                $( document ).ready(function() {
                    $(".abrir_cprb_input").click(function(event) {
                        $(this).parent().parent().find(".codigo_receita_outros").val($(this).val());
                    });
                    $(".anexo_s").click(function() {
                        if( $(this).attr("checked") === false ){
                            $(this).val("");
                            $(this).parent().find(".abrir_cprb_input").removeAttr("checked");
                            $(this).parent().find(".div_inputs_cnae").css("display","none");
                        }
                        else{
                            $(this).parent().find(".div_inputs_cnae").css("display","block");
                        }
                    });
                });
            </script>
            <div style="width:100%;position:relative;float:left;;display:none" class="resposta_sim">
                Localize o código de pagamento na GPS, conforme indicado na imagem abaixo <br><br>
                <div style="float:left;width: 10%;">
                    <input name="codigo_gps" class="codigo_gps" type="radio" style="margin-bottom:5px;margin-right:5px;" value="2003"> 2003 <br>
                    <input name="codigo_gps" class="codigo_gps" type="radio" style="margin-bottom:5px;margin-right:5px;" value="2100"> 2100<br>    
                </div>
                <div style="float:left;width: 90%;">
                    <img src="images/codigo_gps_busca.png" width="500" height="300" style="border: 1px solid #cccccc;">
                </div>
            </div>
            <input style="margin-bottom:20px;" type="button" id="calcularDados" value="Calcular">
            <br><br>

            <div style="margin-bottom: 5px;width:100%;float:left">      
                <div style="float: left;width: 90px;">Multa e juros: </div>
                <input type="text" id="multa_juros" class="currency" value="" size="15" disabled="" style="margin-right:10px;float: left;">
            </div>
            <div style="margin-bottom: 10px;width:100%;float:left">      
                <div style="float: left;width: 90px;">Total: </div>
                <input type="text" id="valor_total" class="currency" value="" size="15" disabled="" style="margin-right:10px;float: left;">
            </div>
            <br>
            <input type="hidden" id="codigo_receita" value="">
            <input type="button" id="gerar_gps" value="Gerar GPS">

        <?php endif; ?>
		<div style="clear: both; height: 20px"></div>
		<div class="quadro_branco"><span class="destaque">Pagamento sem código de barras:</span> você pode quitar normalmente a GPS em atraso pela Internet. Para isso, acesse o site de seu banco, localize a opção Pagamento/GPS e preencha os dados de pagamento com as mesmas informações constantes na guia gerada aqui pelo Contador Amigo.</div>
   
    </div>
</div>
<script>

    
    $( document ).ready(function() {
        //Altera o codigo da receita quando escolhido pelo usuario
        $(".codigo_gps").click(function() {
            $("#codigo_receita").val($(this).val());
        });    
        //Função que define o codigo da receita de acordo com a seleção do usuário, sendo que: Apenas anexo IV->2100, Apenas Outros->2003, IV e Outros->2003
        function getCodigoReceita(){
            var anexo4 = false;
            var anexoGeral = false;
            var erro = false;
            $(".codigo_receita_outros").each(function() {
                if( $(this).attr("checked") === true ){
                    if( $(this).val() === 'IV' )
                        anexo4 = true;
                    else
                        anexoGeral = true;
                    if( $(this).val() === '' )
                        erro = true;
                }

            });
            if( erro === true )  
                return false;
            if( anexo4 === false && anexoGeral === false )  
                return "";
            if( anexo4 === true && anexoGeral === true )    
                return ('2003');
            if( anexo4 === false && anexoGeral === true )   
                return ('2003');
            if( anexo4 === true && anexoGeral === false )   
                return ('2100');
            
        }

        $("#calcularDados").click(function() {
            //Validação de preenchimento dos campos
            var data = '1/'+$("#mes").val()+'/'+$("#ano").val();
            if( $("#mes").val() === '' || $("#ano").val() === '' ){
                alert("Informe a data.");
               $("#mes").focus();
                return;
            }
            //Validação de preenchimento dos campos
            var valor = $("#valor_emprestimo").val();
            if( valor === '' ){
                alert("Informe o valor.");
                $("#valor_emprestimo").focus();
                return;
            }
            //Validação de preenchimento dos campos
            var data2 = $("#data_emprestimo").val();
             if( data2 === '' ){
                alert("Informe a data de pagamento.");
                $("#data_emprestimo").focus();
                return;
            }            
            //Trecho que verifica validade da data em relação à data atual
            <?php 
                echo "
                    data3 = data2.split('/'); 
                    if( parseInt(data3[1]) <= 0 || parseInt(data3[1]) > 12  ){
                        alert('Informe uma data válida.');
                        $('#data_emprestimo').focus();
                        return;
                    }
                    if( parseInt(data3[0]) <= 0 || parseInt(data3[0]) > 31  ){
                        alert('Informe uma data válida.');
                        $('#data_emprestimo').focus();
                        return;
                    }
                ";
                echo " 
                    if( parseInt(data3[2]) > ".date('Y')." ){
                        alert('A data não pode ser posterior à atual.');
                        $('#data_emprestimo').focus();
                        return;
                    } 
                    if( parseInt(data3[0]) < ".date('d')." || parseInt(data3[0]) > 31  ){
                        if( parseInt(data3[1]) <= ".date('m')." ){
                            alert('A data não pode ser anterior à atual.');
                            $('#data_emprestimo').focus();
                            return;
                        }
                    }
                    if( parseInt(data3[1]) < ".date('m')." || parseInt(data3[1]) > 12  ){
                        if( parseInt(data3[2]) <= ".date('Y')." ){
                            alert('A data não pode ser anterior ou à atual.');
                            $('#data_emprestimo').focus();
                            return;
                        } 
                    }
                    if( parseInt(data3[1]) != ".date('m')."){
                        alert('Mês de pagamento não pode ultrapassar o mês corrente.');
                        return;
                    }

                    if( parseInt(data3[2]) < ".date('Y')." ){
                        alert('A data não pode ser anterior à atual.');
                        $('#data_emprestimo').focus();
                        return;
                    } 
                    ";
            ?>
            //Chamada ajax para calcular os valores de multa e juros para a nova data de pagamento definida pelo usuario
            $.ajax({
                url:'ajax.php'
                , data: 'gpsCalcularJuros=gpsCalcularJuros&valor='+valor+'&data='+data+'&data2='+data2
                , type: 'post'
                , async: true
                , cache:false
                , success: function(retorno){
                    console.log(retorno);
                    obj = JSON.parse(retorno);
                    //Define cada resultado vindo via jSON para os locais correspondentes
                    //0 ->Multa+Juros
                    //1 ->Valor original da guia
                    //2 ->Valor total, Multa e juros + vlor Original
                    $("#valor_total").val(obj[2]);
                    $("#multa_juros").val(obj[0]);
                    $("#valor_original").val(obj[1]);

                }
            });
        });
        //Gera o guia GSP, pegando os dados via jquery de cada input e preparando a URL que sera aberta em uma nova janela
        $("#gerar_gps").click(function() {
            //Validação de preenchimento dos campos
            var data = '1/'+$("#mes").val()+'/'+$("#ano").val();
            if( $("#mes").val() === '' || $("#ano").val() === '' ){
                alert("Informe a data.");
               $("#mes").focus();
                return;
            }
            //Validação de preenchimento dos campos
            var valor = $("#valor_emprestimo").val();
            if( valor === '' ){
                alert("Informe o valor.");
                $("#valor_emprestimo").focus();
                return;
            }
            //Validação de preenchimento dos campos
            var data2 = $("#data_emprestimo").val();
            if( data2 === '' ){
                alert("Informe a data de pagamento.");
                $("#data_emprestimo").focus();
                return;
            }
            //Validação de preenchimento dos campos
            var codigo_receita = $("#codigo_receita").val();
            if( codigo_receita === '' ){
                //alert("Informe as atividades exercidas no mês.");
                codigo_receita = getCodigoReceita();
                if( codigo_receita === '' ){
                    alert("Informe as atividades exercidas no mês.");
                    return;
                }
                if( codigo_receita === false ){
                    alert("Informe se a atividade está vinculada a empreitada da construção civil.");
                    return;
                }
            }
            //Zera o código da receita para possíveis outras interações
            // $("#codigo_receita").val("");
            //Abre a janela com a guia GPS
            abreJanela('gerar-gps-geral.php?data='+data+'&data2='+data2+'&converter&valor='+valor+'&codigo_receita='+codigo_receita+'','_blank','width=740, height=420, top=150, left=150, scrollbars=yes, resizable=yes');   

        });
        //Abre a tela de opções para quando ousuario nao tem a guia de pagamento
        $(".respondeu_nao").click(function() {
            $(".resposta_nao").css("display","block");
            $(".resposta_sim").css("display","none");
            //Define o código da receita como vazio caso o usuario troque entre as opções de ter a guia ou nao
            $("#codigo_receita").val("");
        });
        //Abre a tela de opções para quando ousuario tem a guia de pagamento
        $(".respondeu_sim").click(function() {
            $(".resposta_sim").css("display","block");
            $(".resposta_nao").css("display","none");
            //Define o código da receita como vazio caso o usuario troque entre as opções de ter a guia ou nao
            $("#codigo_receita").val("");
            //Define como default o primeiro item do radio para quando o usuario tem a guia de pagamento
            $(".codigo_gps")[0].click();
        });

        $(function() {
            $('.currency').maskMoney();//
        })    
        
    });

</script>
<?php include 'rodape.php' ?>