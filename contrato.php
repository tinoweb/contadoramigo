<?php include 'ballon_contrato.php'; ?>

<div id="novo_contrato" class="sombra_contrato" style="display:none;border-style:solid;border-width:1px;border-color:#CCCCCC;position:absolute;left:50%;margin-left:-340px;top:148px;background-color:#fff;width: 700px;z-index: 9;"><div style="position: absolute; top: 10px; right: 10px; display: block;"><!-- <img src="images/x.png" style="width: 8px; height: 9px; border: 0px; cursor: pointer;"> --></div>
    <div style="padding:20px;text-align:justify">
        <h1>Termos e condições do Serviço</h1>
        Prezado Cliente<br>
<br>

        Atendendo exigência do Banco do Brasil, responsável pelo nosso sistema de pagamento, a partir de 23/06, os boletos referentes às mensalidades do Contador Amigo passarão a ser registrados. Por conta disso, recomendamos sua atenção às datas de vencimento, uma vez que um eventual atraso acarretará cobrança automática de multa de 2% sobre o valor da parcela vencida.<br><br>
Para lembrá-lo dos vencimentos, mandamos mensalmente avisos por e-mail e alertas na página inicial do site. Certifique-se de que nossas mensagens estejam chegando normalmente em sua caixa postal. Se isso não acontecer, contate o <a href="https://www.contadoramigo.com.br/suporte.php">help desk</a>.<br><br>
Prefira sempre o pagamento por cartão de crédito. Nele a cobrança ocorre automaticamente, assim você não corre o risco de esquecer algum pagamento. Para optar pelo cartão, acesse <a href="https://www.contadoramigo.com.br/minha_conta.php">Dados da Conta</a> e preencha as informações de seu cartão.<br>
<br>

            
        <label>
            <input type="checkbox" name="cheTermos" id="termos">
        </label> 
        Li  e concordo com os <a id="abrirTermos" href="#">novos termos e condições</a> de serviço.<br><br>
        <center>
            <button type="button" id="aceitar_termos">Prosseguir</button>            
        </center>
    
    
    </div>
</div>
<div class="">
    
</div>
<style type="text/css" media="screen">
    .layer_branco{
        border-radius: 0 !important;
        text-align: left;
    }
    #video{
        display: none!important;
    }
    #contrato{
        width:550px !important;
        height:365px !important;
        left:55% !important;
        top:110px !important;
    }
    .contrato_interior{
        width: 515px!important;
    }
</style>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script> -->
<script>
    
    $( document ).ready(function() {
        $("#novo_contrato").fadeIn(200);
        $("#aceitar_termos").click(function() {
            // console.log($("#termos").attr("checked"));
            if( $("#termos").attr("checked") )
                location.href = "aceitar_contrato.php";
            else
                alert("É necessário concordar com termos e condições de serviço.");
        });

        $("#abrirTermos").click(function() {
            $("#contrato").fadeIn(0);
        });
    });
</script>

<style type="text/css" media="screen">
   .sombra_contrato{
        position: absolute;
        background-color: #fff;
        /*-o-border-radius: 10px;*/
        /*-moz-border-radius: 18px;*/
        /*-webkit-border-radius: 18px;*/
        /*border-radius: 18px;*/
        -moz-box-shadow: 3px 3px 8px #999;
        -webkit-box-shadow: 3px 3px 8px #999;
        box-shadow: 3px 3px 8px #999;
        -ms-filter: progid:DXImageTransform.Microsoft.Shadow(Strength=1, Direction=10, Color='#999');
        filter: progid:DXImageTransform.Microsoft.Shadow(Strength=1, Direction=10, Color='#999');
    }
</style>