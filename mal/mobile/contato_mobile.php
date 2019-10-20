<?php $nome_meta = "assinatura_orientacoes";?>
<?php include 'header_mobile.php' ?>
<?php include 'menu_mobile2.php' ?>


<div class="principal">

<h1 style="font-size:1em">Contato</h1>

<h3 style="margin-bottom:20px">Consulte-nos e conte sua história. Estamos à disposição!</h3>


<form action="contato_envio.php" method="post" name="form" id="form" style="display:inline">

<div style="float:left; width:15%" >Nome:</div><div style="float:left; width:85%"><input name="NomePessoal" type="text" id="NomePessoal" style="width:100%; margin-bottom:0px" maxlength="200"  alt="Nome"  /></div>
<div style="clear:both; height:10px"></div>

<div style="float:left; width:15%" >E-mail:</div><div style="float:left; width:85%"><input type="text" name="emailPessoal" id="emailPessoal" style="width:100%" maxlength="200" alt="E-mail" /></div>
<div style="clear:both; height:10px"></div>

<div style="float:left; width:15%" >Fone:</div><div style="float:left; width:20%"><input name="DDDPessoal" type="text" id="DDDPessoal" style="width:90%" maxlength="2" alt="Prefixo do Telefone para Cobrança" /></div><div style="float:left; width:65%"><input name="telPessoal" type="text" id="telPessoal" style="width:100%" maxlength="9" alt="Telefone" /></div>
<div style="clear:both; height:20px"></div>

<div style="width:100%; margin-bottom:20px" >Mensagem:<br>
<textarea name="Mensagem" rows="10" id="Mensagem" style="width:100%"></textarea></div>

<div style="width:100%; text-align:center; clear:both; margin-bottom:20px"><input class="botao_azul" type="button" value="Prosseguir" id="btProsseguir" /></div>

</form>

</div>


<?php include 'rodape_mobile.php' ?>
