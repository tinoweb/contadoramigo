<?php 
session_start();
$nome_meta = "index";
?>
<?php if(isset($_SESSION["id_userSecao"])){?>
<?php include 'header_restrita.php' ?>
<?php } else { ?>
<?php include 'header.php' ?>
<?php } ?>

<div class="principal minHeight">

<div class="titulo" style="margin-bottom:10px;">Desculpe-nos</div>
<div class="tituloVermelho" style="margin-bottom:40px">Página não encontrada</div>
  
<div style="float:left"><img src="images/boneca_preocupada.png" width="58" height="197" alt="Boneca preocupada" style="margin-top:20px"/></div>

<div class="bubble_left" style="width:280px; margin-left:20px; float:left;"> 
<div style="padding:20px; font-size:12px"> 
A página que você está tentando acessar foi removida ou não existe. <br>
<br>
Se  estiver procurando alguma informação, <a href="<?=isset($_SESSION["id_userSecao"]) ? "suporte.php" : "javascript:abreDiv('contato')"?>" title="Envie-nos uma mensagem">envie-nos uma mensagem</a>. Teremos prazer em atendê-lo!
</div> 
</div> 
  
</div>

<?php include 'rodape.php' ?>
