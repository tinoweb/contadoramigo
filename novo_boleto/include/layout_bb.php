<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title><?php echo $dadosboleto["identificacao"]; ?></title>
        <link href="include/style.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        </head>
        <body>
            <div id="container">
                <div id="instr_header">
                    <img src="https://www.contadoramigo.com.br/images/logo.png" alt="Contador Amigo" width="198" height="34" border="0" style="float:left">
                    <a id="Img_btImprimir" title="Imprimir Boleto" href="javascript:window.print();"><img title="Imprimir Boleto" src="https://comercio.locaweb.com.br/LocaWebCE/boleto/images/bt_imprimir.gif" style="border-width:0px;float:right"></a>
                </div>
                <div id="">
                    <div id="instr_content">
                        <center>
                            <h2>Instru&ccedil;&otilde;es de impress&atilde;o</h2>
                            <ol>
                                Imprimir em impressora jato de tinta (ink jet) ou laser em qualidade normal. (N&atilde;o use modo econ&ocirc;mico). <br>
                                Utilize folha A4 (210 x 297 mm) ou Carta (216 x 279 mm) - Corte na linha indicada
                            </ol>
                        </center>
                    </div>
                </div>
                <div id="boleto">
                    <div class="cut">
                        <p>Corte na linha pontilhada</p>
                    </div>
                    <table cellspacing="0" cellpadding=0 width=666 border=0>
                        <TBODY>
                            <TR>
                                <TD class=ct width=666>
                                    <div align=right><b class=cp>Recibo
                                        do Sacado</b>
                                    </div>
                                </TD>
                            </tr>
                        </tbody>
                    </table>
                    <table class="header" border=0 cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr>
                                <td width=150><IMG SRC="imagens/logobb.jpg"></td>
                                <td width=50>
                                    <div class="field_cod_banco"><?php echo $dadosboleto["codigo_banco_com_dv"]?></div>
                                </td>
                                <td class="linha_digitavel"><?php echo $dadosboleto["linha_digitavel"]?></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="line" cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr class="titulos">
                                <td class="cedente">Cedente</TD>
                                <td class="ag_cod_cedente">Ag&ecirc;ncia / C&oacute;digo do Cedente</td>
                                <td class="especie">Esp&eacute;cie</TD>
                                <td class="qtd">Quantidade</TD>
                                <td class="nosso_numero">Nosso n&uacute;mero</td>
                            </tr>
                            <tr class="campos">
                                <td class="cedente"><?php echo $dadosboleto["cedente"]; ?>&nbsp;</td>
                                <td class="ag_cod_cedente"><?php echo $dadosboleto["agencia_codigo"]?> &nbsp;</td>
                                <td class="especie"><?php echo $dadosboleto["especie"]?>&nbsp;</td>
                                <TD class="qtd"><?php echo $dadosboleto["quantidade"]?>&nbsp;</td>
                                <TD class="nosso_numero"><?php echo $dadosboleto["nosso_numero"]?>&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="line" cellspacing="0" cellPadding="0">
                        <tbody>
                            <tr class="titulos">
                                <td class="num_doc">N&uacute;mero do documento</td>
                                <td class="cpf_cei_cnpj">CPF/CEI/CNPJ</TD>
                                <td class="vencmento">Vencimento</TD>
                                <td class="valor_doc">Valor documento</TD>
                            </tr>
                            <tr class="campos">
                                <td class="num_doc"><?php echo $dadosboleto["numero_documento"]?></td>
                                <td class="cpf_cei_cnpj"><?php echo $dadosboleto["cpf_cnpj"]?></td>
                                <td class="vencimento"><?php echo $dadosboleto["data_vencimento"]?></td>
                                <td class="valor_doc"><?php echo $dadosboleto["valor_boleto"]?></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="line" cellspacing="0" cellPadding="0">
                        <tbody>
                            <tr class="titulos">
                                <td class="desconto">(-) Desconto / Abatimento</td>
                                <td class="outras_deducoes">(-) Outras dedu��es</td>
                                <td class="mora_multa">(+) Mora / Multa</td>
                                <td class="outros_acrescimos">(+) Outros acr&eacute;scimos</td>
                                <td class="valor_cobrado">(=) Valor cobrado</td>
                            </tr>
                            <tr class="campos">
                                <td class="desconto">&nbsp;</td>
                                <td class="outras_deducoes">&nbsp;</td>
                                <td class="mora_multa">&nbsp;</td>
                                <td class="outros_acrescimos">&nbsp;</td>
                                <td class="valor_cobrado">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="line" cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr class="titulos">
                                <td class="sacado">Sacado</td>
                            </tr>
                            <tr class="campos">
                                <td class="sacado"><?php echo $dadosboleto["sacado"]?></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="footer">
                        <p>Autentica&ccedil;&atilde;o mec&acirc;nica</p>
                    </div>
                    <div class="cut">
                        <p>Corte na linha pontilhada</p>
                    </div>
                    <table class="header" border=0 cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr>
                                <td width=150><IMG SRC="imagens/logobb.jpg"></td>
                                <td width=50>
                                    <div class="field_cod_banco"><?php echo $dadosboleto["codigo_banco_com_dv"]?></div>
                                </td>
                                <td class="linha_digitavel"><?php echo $dadosboleto["linha_digitavel"]?></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="line" cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr class="titulos">
                                <td class="local_pagto">Local de pagamento</td>
                                <td class="vencimento2">Vencimento</td>
                            </tr>
                            <tr class="campos">
                                <td class="local_pagto">QUALQUER BANCO AT&Eacute; O VENCIMENTO</td>
                                <td class="vencimento2"><?php echo $dadosboleto["data_vencimento"]?></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="line" cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr class="titulos">
                                <td class="cedente2">Cedente</td>
                                <td class="ag_cod_cedente2">Ag&ecirc;ncia/C&oacute;digo cedente</td>
                            </tr>
                            <tr class="campos">
                                <td class="cedente2"><?php echo $dadosboleto["cedente"]?></td>
                                <td class="ag_cod_cedente2"><?php echo $dadosboleto["agencia_codigo"]?></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="line" cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr class="titulos">
                                <td class="data_doc">Data do documento</td>
                                <td class="num_doc2">No. documento</td>
                                <td class="especie_doc">Esp&eacute;cie doc.</td>
                                <td class="aceite">Aceite</td>
                                <td class="data_process">Data process.</td>
                                <td class="nosso_numero2">Nosso n&uacute;mero</td>
                            </tr>
                            <tr class="campos">
                                <td class="data_doc"><?php echo $dadosboleto["data_documento"]?></td>
                                <td class="num_doc2"><?php echo $dadosboleto["numero_documento"]?></td>
                                <td class="especie_doc"><?php echo $dadosboleto["especie_doc"]?></td>
                                <td class="aceite"><?php echo $dadosboleto["aceite"]?></td>
                                <td class="data_process"><?php echo $dadosboleto["data_processamento"]?></td>
                                <td class="nosso_numero2"><?php echo $dadosboleto["nosso_numero"]?></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="line" cellspacing="0" cellPadding="0">
                        <tbody>
                            <tr class="titulos">
                                <td class="reservado">Uso do  banco</td>
                                <td class="carteira">Carteira</td>
                                <td class="especie2">Esp�cie</td>
                                <td class="qtd2">Quantidade</td>
                                <td class="xvalor">Valor</td>
                                <td class="valor_doc2">(=) Valor documento</td>
                            </tr>
                            <tr class="campos">
                                <td class="reservado">&nbsp;</td>
                                <td class="carteira"><?php echo $dadosboleto["carteira"]?> <?php echo isset($dadosboleto["variacao_carteira"]) ? $dadosboleto["variacao_carteira"] : '&nbsp;' ?></td>
                                <td class="especie2"><?php echo $dadosboleto["especie"]?></td>
                                <td class="qtd2"><?php echo $dadosboleto["quantidade"]?></td>
                                <td class="xvalor"><?php echo $dadosboleto["valor_unitario"]?></td>
                                <td class="valor_doc2"><?php echo $dadosboleto["valor_boleto"]?></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="line" cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr>
                                <td class="last_line" rowspan="6">
                                    <table class="line" cellspacing="0" cellpadding="0">
                                        <tbody>
                                            <tr class="titulos">
                                                <td class="instrucoes">
                                                    Instru&ccedil;&otilde;es (Texto de responsabilidade do cedente)
                                                </td>
                                            </tr>
                                            <tr class="campos">
                                                <td class="instrucoes" rowspan="5">
                                                    <p><?php echo $dadosboleto["demonstrativo1"]; ?></p>
                                                    <p><?php echo $dadosboleto["demonstrativo2"]; ?></p>
                                                    <p><?php echo $dadosboleto["demonstrativo3"]; ?></p>
                                                    <p><?php echo $dadosboleto["instrucoes1"]; ?></p>
                                                    <p><?php echo $dadosboleto["instrucoes2"]; ?></p>
                                                    <p><?php echo $dadosboleto["instrucoes3"]; ?></p>
                                                    <p><?php echo $dadosboleto["instrucoes4"]; ?></p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table class="line" cellspacing="0" cellpadding="0">
                                        <tbody>
                                            <tr class="titulos">
                                                <td class="desconto2">(-) Desconto / Abatimento</td>
                                            </tr>
                                            <tr class="campos">
                                                <td class="desconto2">&nbsp;</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table class="line" cellspacing="0" cellpadding="0">
                                        <tbody>
                                            <tr class="titulos">
                                                <td class="outras_deducoes2">(-) Outras dedu&ccedil;&otilde;es</td>
                                            </tr>
                                            <tr class="campos">
                                                <td class="outras_deducoes2">&nbsp;</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table class="line" cellspacing="0" cellpadding="0">
                                        <tbody>
                                            <tr class="titulos">
                                                <td class="mora_multa2">(+) Mora / Multa</td>
                                            </tr>
                                            <tr class="campos">
                                                <td class="mora_multa2">&nbsp;</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table class="line" cellspacing="0" cellpadding="0">
                                        <tbody>
                                            <tr class="titulos">
                                                <td class="outros_acrescimos2">(+) Outros Acr&eacute;scimos</td>
                                            </tr>
                                            <tr class="campos">
                                                <td class="outros_acrescimos2">&nbsp;</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td class="last_line">
                                    <table class="line" cellspacing="0" cellpadding="0">
                                        <tbody>
                                            <tr class="titulos">
                                                <td class="valor_cobrado2">(=) Valor cobrado</td>
                                            </tr>
                                            <tr class="campos">
                                                <td class="valor_cobrado2">&nbsp;</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="line" cellspacing="0" cellPadding="0">
                        <tbody>
                            <tr class="titulos">
                                <td class="sacado2">Sacado</td>
                            </tr>
                            <tr class="campos">
                                <td class="sacado2">
                                    <p><?php echo $dadosboleto["sacado"]?></p>
                                    <p><?php echo $dadosboleto["endereco1"]?></p>
                                    <p><?php echo $dadosboleto["endereco2"]?></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="line" cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr class="titulos">
                                <td class="sacador_avalista" colspan="2">Sacador/Avalista</td>
                            </tr>
                            <tr class="campos">
                                <td class="sacador_avalista">&nbsp;</td>
                                <td class="cod_baixa">C&oacute;d. baixa</td>
                            </tr>
                        </tbody>
                    </table>
                    <table cellspacing=0 cellpadding=0 width=666 border=0>
                        <TBODY>
                            <TR>
                                <TD width=666 align=right ><font style="font-size: 10px;">Autentica&ccedil;&atilde;o mec&acirc;nica - Ficha de Compensa��o</font></TD>
                            </tr>
                        </tbody>
                    </table>
                    <div class="barcode">
                        <p><?php fbarcode($dadosboleto["codigo_barras"]); ?></p>
                    </div>
                    <div class="cut">
                        <p>Corte na linha pontilhada</p>
                    </div>
                </div>
            </div>
    </body>
</html>