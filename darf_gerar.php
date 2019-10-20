<?php include 'header_restrita.php' ?>

<div class="minHeight principal">

<h1>Impostos e Obrigações</h1>

<h2>DARF</h2>


<?

//var_dump($_SESSION['dados_DARF_userSessao']);
$temPagamentos = false;


foreach($_SESSION['dados_DARF_userSessao'] as $dados){
	if($dados['valor'] > 0){
		$temPagamentos = true;
	}
}

if(count($_SESSION['dados_DARF_userSessao']) == 0 || !$temPagamentos){
?>		
<div style="">

Não há DARF a ser recolhido para este período. Os valores pagos referente a pró-labore, salários e serviços estão dentro do limite de isenção.

<?	
} else {
?>
<div style="width:760px">

Você deverá recolher os seguintes DARF:

<div style="clear:both;margin-bottom:20px;"></div>

<?
	$indice = 0;
	$loop = 0;

	foreach($_SESSION['dados_DARF_userSessao'] as $dados){
		if($dados['valor'] > 0){
			$loop++;
			?>
            <div class="tituloAzulPequeno">
            	<?=ucwords($dados['tipo'])?>
            </div>
            <table width="100%" cellpadding="5" style="margin-bottom:25px;">
            	<tr>
                	<th colspan="2">DARF</th>
                </tr>
                <tr>
                	<td width="80%" class="td_calendario"><?="Código de receita: " . $dados['codigo_servico']  . " - " . $dados['descricao_servico']?></td>
                	<td width="20%" class="td_calendario"><?="Valor: R$ " . number_format($dados['valor'],2,",",".")?></td>
                </tr>
                <tr>
                    <td class="td_calendario" align="right" colspan="2">
                    <? if($dados['valor'] > 10){ ?>
                            <a href="tutorial_darf.php?id=<?=$indice?>">
                            Gerar DARF
                            </a>&nbsp;&nbsp;<img src="images/avancar_vermelho.png" width="7" height="8" border="0" />
					<? } else { ?>
                    		Não é necessário gerar o DARF, pois o importo não atinge o valor mínimo.
                    <? } ?>
                    </td>
                </tr>
            </table>
<?
		}
		$indice++;;
	}

}
	
	
?>

</div>

</div>

<?php include 'rodape.php' ?>

