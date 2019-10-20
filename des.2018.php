<?php include 'header_restrita.php' ?>


<?php 
    
    $user = new Show();//Classe que pega dados do usuário
    $des = new DES();//Cria o objeto que trata os dados da DES 
    $des->setCidade($user->getCidade());//Define a cidade do usuário
    $des->getDadosDes();//Pega os dados da DES para a cidade do usuário

?>

<?php if( $des->getCidade() ): ?>

<div class="principal">
<div style="width:740px" class="minHeight">

<div class="titulo">Impostos e Obrigações</div>

	<div class="tituloVermelho">
		<?php echo $des->getNomeTexto(); ?><?php echo $des->getNomeCompletoTexto(); ?> 
	</div>

	<div>          
			A 
			<?php echo $des->getNomeTexto(); ?>
			é uma obrigação acessória exigida pela prefeitura de <?php echo $user->getCidade(); ?><?php echo $des->getTextoValor(); ?>.<br>
			<br>
			Em sua cidade, <!--  [para empresas com faturamento mensal acima de [valor do banco](Frase so aparece se existir o valor) ] -->é preciso informar  os serviços 
			<strong><?php echo $des->getTiposServicos(); ?></strong>
			 no período. Para declarar, acesse 
			<?php echo $des->getLinkTexto(); ?>
			e siga as orientações. Em caso de dúvida, <?php if( $des->getTutorialTexto() != '' ){ ?>consulte o 
			<?php echo $des->getTutorialTexto(); ?>
			 ou <?php } ?>
			 entre em contato com nosso <a href="suporte.php">Help desk</a>. <br>
			<br>
			<?php if(  $des->getTipo() == 'web' ){ ?>
			O acesso à  
			<?php echo $des->getNomeTexto(); ?>
			 requer login e senha. Se você já tinha um contador, pergunte a ele  os dados de acesso. Se for a primeira vez que esta obrigação 
			 estiver sendo enviada, será preciso gerar a senha. Isso pode ser feito no próprio site da declaração.<br>
			<br>
			<?php } ?>
			A data limite para envio é até o
			<?php echo $des->getDiaTexto(); ?>
			 subsequente ao serviço. <br>
			<br>
			<?php if( $des->getPrestados() && $des->getTomados() ){ ?>
			Considere <strong>serviços prestados</strong> as notas fiscais emitidas no 
			período e <strong>serviços tomados</strong>, aqueles que você contratou 
			de terceiros e para os quais foram emitidas notas fiscais ou recibos. Atenção, 
			relacione apenas serviços. Na declaração não entram compras de produtos, 
			materiais ou equipamentos.
			<br>
			<?php }else if( $des->getPrestados() && !$des->getTomados() ){ ?>
			Considere <strong>serviços tomados</strong> aqueles que você contratou de 
			terceiros para sua empresa e para os quais foram emitidos nostas fiscais 
			ou recibos. 
			Atenção, relacione apenas serviços. Na declaração não entram compras de 
			produtos, materiais ou equipamentos.<br>
			<?php } ?>
			
			<?php echo '<br>'.$des->getObservacao();?>
			
			<div class="quadro_branco"> <span class="destaque">IMPORTANTE:</span> não se esqueça de cadastrar os <strong>serviços tomados</strong> 
				em <a href="pagamento_autonomos.php">Pagamentos / Autônomos</a> ou<a href="pagamento_pj.php"> Pagamentos / Pessoa Jurídica</a>, conforme o caso. 
				Assim o Contador Amigo o informará sobre eventuais descontos e retenções de impostos.
			</div>

	</div>
</div>
<?php else:?>
	<div class="principal">
		<div style="width:740px" class="minHeight">

		<div class="titulo">DES - Declaração de Imposto Sobre Serviço</div>

		<div> 
			A DES é uma obrigação acessória exigida por alguns municípios. Normalmente é preciso enviá-la somente para informar os serviços tomados, mas alguns municípios exigem o envio mensal de todos os serviços prestados.<br><br>

			Considere serviços prestados as notas fiscais emitidas no período e serviços tomados, aqueles que você contratou de terceiros e para os quais foram emitidas notas fiscais ou recibos.<br><br> 

			Atenção, na declaração não entram compras de produtos, materiais ou equipamentos.<br><br>

			Contate nosso <a href="/suporte.php">Help Desk</a> para saber se a DES é exigida em seu município e como fazer para enviá-la.<br><br>
		</div>
	</div>

<?php endif;?>

<?php include 'rodape.php' ?>

