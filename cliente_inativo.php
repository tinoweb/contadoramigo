<?php include 'header_inativo.php' ?>

<?php 
$sql = "SELECT * FROM dados_cobranca WHERE id='" . $_SESSION["id_userSecaoMultiplo"] . "' LIMIT 0, 1";
$resultado = mysql_query($sql)
or die (mysql_error());

$linha=mysql_fetch_array($resultado);
?>
		
<div class="principal">
<div class="minHeight">
  <div class="titulo" style="margin-bottom:10px;">Cliente Inativo</div>

  Prezado <?=$linha['assinante']?><br />
<br />
<?php if(($linha['forma_pagameto'] == 'Visa') or ($linha['forma_pagameto'] == 'Master Card')) { ?>
<!--cartao -->
Seu acesso ao portal <strong>Contador Amigo</strong> está desativado, pois não conseguimos efetuar a cobrança em seu cartão de crédito. Caso o cartão tenha sido cancelado, ou esteja com o prazo de validade vencido, por favor acesse a página <a href="minha_conta.php">Dados da Conta</a> e atualize as informações de cobrança. <br />
<br />
Se preferir, você pode alterar a forma de pagamento para boleto bancário, nesse caso, porém, será necessário aguardar o crédito do valor em nosso sistema por parte da instituição financeira, o que leva em torno de 2 dias úteis.<br />
<br />
Estamos à disposição para maiores esclarecimentos, através da nossa <a href="suporte.php">Central de Ajuda</a><br />
<!--fim cartão -->
<?php }
if($linha['forma_pagameto'] == 'Boleto') { ?>
<!--boleto -->
Seu acesso ao portal <strong>Contador Amigo</strong> está temporariamente desativado, pois foram encontradas as seguites pendências em sua conta:<br />
<ul>
<li>Boleto nº 000000, com vencimento em 01/05 <a href="#">(gerar 2ª via)</a></li>
</ul>
<br />
Caso o documento já tenha sido quitado, sua conta será reativada automaticamente em até 2 dias úteis após o pagamento. 
<br />

<br />
Estamos à disposição para maiores esclarecimentos, através da nossa <a href="suporte.php">Central de Ajuda</a>.<br />
<!--fim boleto -->
<?php } ?>
<br />
<br />
Cordialmente,<br />
<br />
<br />
Secretaria Geral<br />
<a href="mailto:secretaria@contadoramigo.com.br">secretaria@contadoramigo.com.br</a><br />
</div>
</div>
<?php include 'rodape.php' ?>
