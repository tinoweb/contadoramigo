<?php include 'header.php' ?>
<?php include 'agenda.class.php' ?>
<?php 
	
	$agenda = new Agenda();	
	$meses = $agenda->getMeses();
	$tipos = $agenda->getTipos();

	echo $agenda->getredirect();
	
?>
<div class="principal">
	<div style="width:740px" class="minHeight">

		<div class="titulo">Agenda</div>
		
			<table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
				<tbody>
					<form action="manter-item.php" method="POST" accept-charset="utf-8">
						<tr>
							<td width="123" align="right" valign="middle" class="formTabela">Dia:</td>
							<td class="formTabela" width="100">
								<input name="dia" type="text" style="width:30px; margin-bottom:0px" maxlength="2" alt="Dia" value="<?php echo $agenda->getdia(); ?>">
							</td>
						</tr>
						<tr>
							<td align="right" valign="middle" class="formTabela">Mês:</td>
							<td class="formTabela">
								<select name="mes" style="width:100px" class="comboM" alt="Mês">
									<option value="" <?php if( $agenda->getmes() != "" ) echo 'selected="selected"'; ?> >Selecione</option>
									<?php while ( $mes=mysql_fetch_array($meses) ){ ?>
									<option value="<?php echo $mes['id'] ?>" <?php if( $mes['id'] == $agenda->getmes() ) echo 'selected="selected"'; ?>><?php echo $mes['mes']; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td align="right" valign="middle" class="formTabela">Tipo:</td>
							<td class="formTabela">
								<select name="tipo" style="width:2	00px" class="comboM" alt="Mês">
									<option value="" <?php if( $agenda->gettipo() != "" ) echo 'selected="selected"'; ?>>Selecione</option>
									<?php while ( $tipo=mysql_fetch_array($tipos) ){ ?>
									<option value="<?php echo $tipo['id'] ?>" <?php if( $tipo['id'] == $agenda->gettipo() ) echo 'selected="selected"'; ?>><?php echo $tipo['frase']; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<input type="hidden" name="id" value="<?php echo $agenda->getid(); ?>">
						<input type="hidden" name="<?php echo $agenda->getacao(); ?>" value="">
					</form>
					<tr>
						<td colspan="2">
							<br>
							<center>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="Salvar" class="salvarDados" type="submit" value="Salvar">
							</center>
						</td>
					</tr>
				</tbody>

			</table>


	</div>
</div>
<script>
	
	$(".salvarDados").click(function() {
		
		$("form").submit();

	});

</script>

<?php include 'rodape.php' ?>
