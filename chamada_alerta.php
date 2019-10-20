<?php include 'header_restrita.php' ?>

<div class="principal minHeight">

<!--compartilhar na rede social-->
<div id="chamada" class="layer_branco" style="position:absolute; width:230px; top:150px; left:50%; margin-left:-100px; z-index:2; display:block; text-align:center">
<div style=" padding:15px">

<div style="width:200px; text-align:right" ><a href="#" class="fechar_mensagem_compartilhamento"><img src="images/x.png" width="8" height="9" border="0" alt="Compartilhe no Facebook" title="Compartilhe no Facebook" /></a></div>

<h1>Ajude a divulgar o Contador Amigo!</h1>
<div  class="tituloVermelho" style="font-size:14px; margin-bottom:20px; color:#666666">Compartilhe  nosso site <br />
  com seus amigos do<br />
  Facebook ou  Google Plus.<br />
Obrigado!
</div>

<div id="fb-root"></div>
<script>(function(d, s, id) {
var js, fjs = d.getElementsByTagName(s)[0];
if (d.getElementById(id)){return;}
js = d.createElement(s); js.id = id;
js.src = "//connect.facebook.net/pt_BR/sdk.js#xfbml=1&appId=156991511034067&version=v2.0";
fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>
<div class="fb-share-button" style="float:left;" data-href="http://www.contadoramigo.com.br" data-type="button"></div>

<script src="https://apis.google.com/js/platform.js" async defer> {lang: 'pt-BR'}</script>
<div style="float:right"><div class="g-plus" data-action="share" data-annotation="none" data-width="_"></div></div>
<div style="clear:both; height:10px"></div>
<div>
<a href="#" class="fechar_mensagem_compartilhamento">Agora não.</a></div>
</div>
</div>
<!--compartilhar na rede social-->



</div>
<?php include 'rodape.php' ?>
