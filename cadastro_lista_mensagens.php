<?php include 'header_restrita.php' ?>

<div class="principal">
<div  class="minHeight">
  <h1>Cadastro</h1>
  <h2>Correio Fiscal</h2>
  <div>
    Comece a receber periodicamente lembretes do <strong>Contador Amigo</strong>  sobre suas obrigações fiscais e assuntos relacionados à sua assinatura. Preencha nos campos abaixo seu nome e o e-mail informado na assinatura. Em seguida, clique no botão <strong>Enviar</strong>.<br>
  <br><br>
  
<form method="post" action="https://contadoramigo1.comunicacaoporemail.net/recebeForm.php">
  
  <input type="hidden" name="uniqid" value="111515145452369630" />
	<input type="hidden" name="senha" value="b504bd09a5bc70a0eaf5d398ae2e741e" />
	<input type="hidden" name="id_sender_email" value="4827" />
	<input type="hidden" name="urlredir" value="https://www.contadoramigo.com.br/cadastro_lista_mensagens_sucesso.php" />
    
    <div style="float:left; width:60px">Nome:</div><div style="float:left"><input name="nome" type="text" maxlength="256" size="40" value="" /></div>
    <div style="clear:both; height:10px"></div>
    
        <div style="float:left; width:60px">E-mail:</div><div style="float:left"><input name="email" type="text" maxlength="256" size="40"   value="" /></div>
    <div style="clear:both; height:20px"></div>
    
    <div><input type="submit" value="Enviar" /></div>
  
</form>
  
</div>
  
</div>
</div>

<?php include 'rodape.php' ?>
