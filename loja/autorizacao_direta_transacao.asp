<!--
'-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#
' Kit de Integra��o Cielo
' Vers�o: 3.0
' Arquivo: autorizacao_direta_transacao.asp
' Fun��o: Autoriza��o direta de uma transa��o na Cielo Ecommerce
'-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#
-->
<%
' Dados obtidos da loja para a transa��o

' - dados do processo
identificacao = "4843543"
modulo = "CIELO"
operacao = "Autorizacao-Direta"
ambiente = "TESTE"

' - dados do cart�o
nome_portador_cartao = "Vitor Maradei"
numero_cartao = "4551870000000183"
validade_cartao = "201311"
indicador_cartao = "1"
codigo_seguranca_cartao = "343"

' - dados do pedido
idioma = "PT"
valor = "100"
pedido = "0001"
descricao = "Assinatura contador amigo"

' - dados do pagamento
bandeira = "visa"
forma_pagamento = "1"
parcelas = "1"
capturar = "true"

' - dados adicionais
campo_livre = ""


' Par�metros
parametros = parametros & "identificacao=" & identificacao
parametros = parametros & "&modulo=" & modulo
parametros = parametros & "&operacao=" & operacao
parametros = parametros & "&ambiente=" & ambiente

parametros = parametros & "&nome_portador_cartao=" & nome_portador_cartao
parametros = parametros & "&numero_cartao=" & numero_cartao
parametros = parametros & "&validade_cartao=" & validade_cartao
parametros = parametros & "&indicador_cartao=" & indicador_cartao
parametros = parametros & "&codigo_seguranca_cartao=" & codigo_seguranca_cartao

parametros = parametros & "&idioma=" & idioma
parametros = parametros & "&valor=" & valor
parametros = parametros & "&pedido=" & pedido
parametros = parametros & "&descricao=" & Server.URLEncode(descricao)

parametros = parametros & "&bandeira=" & bandeira
parametros = parametros & "&forma_pagamento=" & forma_pagamento
parametros = parametros & "&parcelas=" & parcelas
parametros = parametros & "&capturar=" & capturar

parametros = parametros & "&campo_livre=" & Server.URLEncode(campo_livre)

' URL de acesso ao Gateway Locaweb
urlLocaWebCE = "https://comercio.locaweb.com.br/comercio.comp"

' Instancia o objeto HttpRequest. 
Set objSrvHTTP = Server.CreateObject("MSXML2.XMLHTTP.3.0") 

' Informe o m�todo e a URL a ser capturada 
objSrvHTTP.open "POST", urlLocawebCE, false 

' Com o m�todo setRequestHeader informamos o cabe�alho HTTP 
objSrvHTTP.setRequestHeader "Content-Type", "application/x-www-form-urlencoded" 

' O m�todo Send envia a solicita��o HTTP e exibe o conte�do da p�gina 
objSrvHTTP.Send(parametros)

' Verificando se a busca foi bem sucedida 
If objSrvHTTP.statusText = "OK" Then

    xml = objSrvHTTP.responseText

    retorno_codigo_erro = pegaValorNode(xml,"erro//codigo")
    retorno_mensagem_erro = pegaValorNode(xml,"erro//mensagem")

    retorno_tid = pegaValorNode(xml,"transacao//tid")
    retorno_pan = pegaValorNode(xml,"transacao//pan")

    retorno_pedido = pegaValorNode(xml,"transacao//dados-pedido//numero")
    retorno_valor = pegaValorNode(xml,"transacao//dados-pedido//valor")
    retorno_moeda = pegaValorNode(xml,"transacao//dados-pedido//moeda")
    retorno_data_hora = pegaValorNode(xml,"transacao//dados-pedido//data-hora")
    retorno_descricao = pegaValorNode(xml,"transacao//dados-pedido//descricao")
    retorno_idioma = pegaValorNode(xml,"transacao//dados-pedido//idioma")

    retorno_bandeira = pegaValorNode(xml,"transacao//forma-pagamento//bandeira")
    retorno_produto = pegaValorNode(xml,"transacao//forma-pagamento//produto")
    retorno_parcelas = pegaValorNode(xml,"transacao//forma-pagamento//parcelas")

    retorno_status = pegaValorNode(xml,"transacao//status")

    retorno_codigo_autenticacao = pegaValorNode(xml,"transacao//autenticacao//codigo")
    retorno_mensagem_autenticacao = pegaValorNode(xml,"transacao//autenticacao//mensagem")
    retorno_data_hora_autenticacao = pegaValorNode(xml,"transacao//autenticacao//data-hora")
    retorno_valor_autenticacao = pegaValorNode(xml,"transacao//autenticacao//valor")
    retorno_eci_autenticacao = pegaValorNode(xml,"transacao//autenticacao//eci")

    retorno_codigo_autorizacao = pegaValorNode(xml,"transacao//autorizacao//codigo")
    retorno_mensagem_autorizacao = pegaValorNode(xml,"transacao//autorizacao//mensagem")
    retorno_data_hora_autorizacao = pegaValorNode(xml,"transacao//autorizacao//data-hora")
    retorno_valor_autorizacao = pegaValorNode(xml,"transacao//autorizacao//valor")
    retorno_lr_autorizacao = pegaValorNode(xml,"transacao//autorizacao//lr")
    retorno_arp_autorizacao = pegaValorNode(xml,"transacao//autorizacao//arp")

    retorno_url_autenticacao = pegaValorNode(xml,"transacao//url-autenticacao")

    ' Se n�o ocorreu erro exibe par�metros
    If retorno_codigo_erro = "" Then
        Response.write "<b> TRANSA��O </b><br />"
        Response.write "<b>C�digo de identifica��o do pedido (TID): </b>" & retorno_tid & "<br />" 
        Response.write "<b>PAN do pedido (pan): </b>" & retorno_pan & "<br />" 
        
        Response.write "<b>N�mero do pedido (numero): </b>" & retorno_pedido & "<br />"
        Response.write "<b>Valor do pedido (valor): </b>" & retorno_valor & "<br />"
        Response.write "<b>Moeda do pedido (moeda): </b>" & retorno_moeda & "<br />" 
        Response.write "<b>Data e hora do pedido (data-hora): </b>" & retorno_data_hora & "<br />"
        Response.write "<b>Descri��o do pedido (descricao): </b>" & retorno_descricao & "<br />"
        Response.write "<b>Idioma do pedido (idioma): </b>" & retorno_idioma & "<br />"

        Response.write "<b>Bandeira (bandeira): </b>" & retorno_bandeira & "<br />"
        Response.write "<b>Forma de pagamento (produto): </b>" & retorno_produto & "<br />"
        Response.write "<b>N�mero de parcelas (parcelas): </b>" & retorno_parcelas & "<br />"

        Response.write "<b>Status do pedido (status): </b>" & retorno_status & "<br />"

        Response.write "<b>URL para autentica��o (url-autenticacao): </b>" & retorno_url_autenticacao & "<br /><br />"

        Response.write "<b> AUTENTICA��O </b><br />"
        Response.write "<b>C�digo da autentica��o (codigo): </b>" & retorno_codigo_autenticacao & "<br />"
        Response.write "<b>Mensagem da autentica��o (mensagem): </b>" & retorno_mensagem_autenticacao & "<br />"
        Response.write "<b>Data e hora da autentica��o (data-hora): </b>" & retorno_data_hora_autenticacao & "<br />"
        Response.write "<b>Valor da autentica��o (valor): </b>" & retorno_valor_autenticacao & "<br />" 
        Response.write "<b>ECI da autentica��o (eci): </b>" & retorno_eci_autenticacao & "<br /><br />"

        Response.write "<b> AUTORIZA��O </b><br />"
        Response.write "<b>C�digo da autoriza��o (codigo): </b>" & retorno_codigo_autorizacao & "<br />"
        Response.write "<b>Mensagem da autoriza��o (mensagem): </b>" & retorno_mensagem_autorizacao & "<br />"
        Response.write "<b>Data e hora da autoriza��o (data-hora): </b>" & retorno_data_hora_autorizacao & "<br />"
        Response.write "<b>Valor da autoriza��o (valor): </b>" & retorno_valor_autorizacao & "<br />" 
        Response.write "<b>LR da autoriza��o (LR): </b>" & retorno_lr_autorizacao & "<br />"
        Response.write "<b>ARP da autoriza��o (ARP): </b>" & retorno_arp_autorizacao & "<br /><br />"
    Else
        Response.write "<b>Erro: </b>" & retorno_codigo_erro & "<br />" 
        Response.write "<b>Mensagem: </b>" & retorno_mensagem_erro & "<br />" 
    End If        

End If

Set objSrvHTTP = Nothing 

' ################################################################################################
' pegaValorNode
' Retorno o valor espec�fico de um Node de um XML
Function pegaValorNode(xml,node)

    Set objXml = Server.CreateObject("MSXML2.DOMDocument")

    objXml.loadXML(xml)

    If TypeName(objXml) = "DOMDocument" Then
        If objXml.GetElementsByTagName(node).length <> 0 Then
            pegaValorNode = objXml.selectSingleNode("//" & node).text
        Else
            pegaValorNode = ""
        End If
    Else
        pegaValorNode = ""
    End If

    Set objXml = Nothing

End Function
%>