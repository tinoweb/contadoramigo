<?php 

//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);

include 'header_restrita.php'; 

$user = new Show();//Classe que pega dados do usuário
$des = new DES();//Cria o objeto que trata os dados da DES 
$des->setCidade($user->getCidade());//Define a cidade do usuário
$des->getDadosDes();//Pega os dados da DES para a cidade do usuário

?>

<?php if( $des->getCidade() ): ?>

<div class="principal">
<div style="width:80%" class="minHeight">

<h1>Impostos e Obrigações</h1>

	<h2>
		<?php echo $des->getNomeCompletoTexto(); ?> 
	</h2>

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
			
			<?php if($des->getCriterio() == 'no_ato'){ ?>
				<br>O envio deve ser realizado logo após a prestação do serviço.<br>
			<?php } else {?>	
				<br>A data limite para envio é até o <?php echo $des->getDiaTexto(); ?> subsequente ao serviço. <br>
			<?php }?>
			
			<?php if( $des->getPrestados() && $des->getTomados() ){ ?>
			<br>Considere <strong>serviços prestados</strong> as notas fiscais emitidas no 
			período e <strong>serviços tomados</strong>, aqueles que você contratou 
			de terceiros e para os quais foram emitidas notas fiscais ou recibos. Atenção, 
			relacione apenas serviços. Na declaração não entram compras de produtos, 
			materiais ou equipamentos.<br>
			<?php }else if( $des->getPrestados() && !$des->getTomados() ){ ?>
			<br>Considere <strong>serviços tomados</strong> aqueles que você contratou de 
			terceiros para sua empresa e para os quais foram emitidos nostas fiscais 
			ou recibos. 
			Atenção, relacione apenas serviços. Na declaração não entram compras de 
			produtos, materiais ou equipamentos.<br>
			<?php } ?>
			
			<?php echo $des->getObservacao();?>
			
			<div class="quadro_branco"> <span class="destaque">IMPORTANTE:</span> não se esqueça de cadastrar os <strong>serviços tomados</strong> 
				em <a href="pagamento_autonomos.php">Pagamentos / Autônomos</a> ou<a href="pagamento_pj.php"> Pagamentos / Pessoa Jurídica</a>, conforme o caso. 
				Assim o Contador Amigo o informará sobre eventuais descontos e retenções de impostos.
			</div>

	</div>
</div>
<?php else:?>
	<div class="principal">
		<div style="width:80%" class="minHeight">

		<h1>DES - Declaração de Imposto Sobre Serviço</h1>

		<div> 
			A DES é uma obrigação acessória exigida por alguns municípios. Normalmente é preciso enviá-la somente para informar os serviços tomados, mas alguns municípios exigem o envio mensal de todos os serviços prestados.<br><br>

			Considere serviços prestados as notas fiscais emitidas no período e serviços tomados, aqueles que você contratou de terceiros e para os quais foram emitidas notas fiscais ou recibos.<br><br> 

			Atenção, na declaração não entram compras de produtos, materiais ou equipamentos.<br><br>

			Contate nosso <a href="/suporte.php">Help Desk</a> para saber se a DES é exigida em seu município e como fazer para enviá-la.<br><br>
		</div>
	</div>

<?php endif;?>

<?php include 'rodape.php' ?>

