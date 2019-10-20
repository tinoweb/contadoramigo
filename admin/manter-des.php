<?php 

//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);

include 'header.php';
include '../des.class.php';
	
$des = new DES();
// $meses = $agenda->getMeses();
// $tipos = $agenda->getTipos();

// echo $agenda->getredirect();
	
?>
<script src="../jquery.maskMoney.js" type="text/javascript"></script>
<div class="principal">
	<div style="width:740px" class="minHeight">

		<div class="titulo">Des</div>
		
			<table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
				<tbody>
					<form action="manter-des.php" method="POST" accept-charset="utf-8">
						<tr>
							<td align="right" valign="middle" class="formTabela">Estado:</td>
							<td class="formTabela" width="100">
								<select name="uf" id="estado_des">
									<?php echo $des->getEstados(); ?>
								</select>
							</td>
						</tr>
						<tr>
							<td align="right" valign="middle" class="formTabela">Cidade:</td>
							<td class="formTabela" width="100">
								<select name="municipio" id="cidade_des" style="min-width:100px;">
									<?php echo $des->getCidades(); ?>
								</select>
							</td>
						</tr>
						<tr>
							<td align="right" valign="middle" class="formTabela">Nome da declaração:</td>
							<td class="formTabela" width="100">
								<input name="nome" id="nome_des" type="text" style="width:200px; margin-bottom:0px" alt="Dia" value="<?php echo $des->getNome(); ?>">
							</td>
						</tr>
						<tr>
							<td align="right" valign="middle" class="formTabela">Sigla:</td>
							<td class="formTabela" width="100">
								<input name="abreviacao" id="abreviacao_des" type="text" style="width:200px; margin-bottom:0px" alt="Dia" value="<?php echo $des->getAbreviacao(); ?>">
							</td>
						</tr>	
						<tr>
							<td>
								A DES deverá informar os serviços:
							</td>
						</tr>
						<tr>
							<td align="right" valign="middle" class="formTabela">Prestados</td>
							<td class="formTabela" width="100">
								<input type="checkbox" name="prestados" <?php if( $des->getPrestadosVal() == 'x' ) echo 'checked="checked"'; ?> value="x">
							</td>
						</tr>
						<tr>
							<td align="right" valign="middle" class="formTabela">Tomados</td>
							<td class="formTabela" width="100">
								<input type="checkbox" name="tomados" <?php if( $des->getTomadosVal() == 'x' ) echo 'checked="checked"'; ?> value="x">
							</td>
						</tr>
						<tr>
							<td align="right" valign="middle" class="formTabela">Tomados - Outro Município</td>
							<td class="formTabela" width="100">
								<input type="checkbox" name="outro_m" <?php if( $des->getTomados_outro_municipioVal() == 'x' ) echo 'checked="checked"'; ?> value="x">
							</td>
						</tr>
						<tr>
							<td align="right" valign="middle" class="formTabela">Vencimento:</td>
							<td class="formTabela" width="100">
								<?php if( $des->getCriterio() == 'no_ato' ) { ?>
									<input id="vencimento" name="vencimento" type="text" style="width:150px; margin-bottom:0px" alt="Dia" value="<?php echo $des->getDataVal(); ?>" disabled>
								<?php } else { ?>
									<input id="vencimento" name="vencimento" type="text" style="width:150px; margin-bottom:0px" alt="Dia" value="<?php echo $des->getDataVal(); ?>">
								<?php } ?>	
								Critério:
								<select id="criterio"  name="criterio">
									<option value="corridos" <?php if( $des->getCriterio() == 'corridos' ) echo 'selected'; ?>>Corridos</option>
									<option value="uteis" <?php if( $des->getCriterio() == 'uteis' ) echo 'selected'; ?>>Úteis</option>
									<option value="no_ato" <?php if( $des->getCriterio() == 'no_ato' ) echo 'selected'; ?>>No ato</option>
								</select>
							</td>
						</tr>
						<tr>
							<td align="right" valign="middle" class="formTabela">Forma de preenchimento:</td>
							<td class="formTabela" width="100">
								<select name="tipo">
									<option value="web" <?php if( $des->getTipo() == "web" ) echo 'selected="selected"'; ?>>Web</option>
									<option value="download" <?php if( $des->getTipo() == "download" ) echo 'selected="selected"'; ?> >Programa</option>
								</select>
							</td>
						</tr>
						<tr>
							<td align="right" valign="middle" class="formTabela">Nome site:</td>
							<td class="formTabela" width="100">
								<input name="nomeSite" type="text" style="width:500px; margin-bottom:0px" value="<?php echo $des->getNomeSite(); ?>">
							</td>
						</tr>						
						<tr>
							<td align="right" valign="middle" class="formTabela">Link para preenchimento:</td>
							<td class="formTabela" width="100">
								<input name="link" type="text" style="width:500px; margin-bottom:0px" alt="Dia" value="<?php echo $des->getLink(); ?>">
							</td>
						</tr>
						<tr>
							<td align="right" valign="middle" class="formTabela">Link para tutorial:</td>
							<td class="formTabela" width="100">
								<input name="tutorial" type="text" style="width:500px; margin-bottom:0px" alt="Dia" value="<?php echo $des->getTutorial(); ?>">
							</td>
						</tr>
					<!-- 	<tr>
							<td align="right" valign="middle" class="formTabela">:</td>
							<td class="formTabela" width="100">
								
							</td>
						</tr> -->
						<tr>
							<td align="right" valign="middle" class="formTabela">Link para Retenção do ISS:</td>
							<td class="formTabela" width="100">
								<input name="retencao_iss" type="text" style="width:500px; margin-bottom:0px" alt="Dia" value="<?php echo $des->getRetencao_iss(); ?>">
							</td>
						</tr>
						<tr>
							<td align="right" valign="middle" class="formTabela">Dispensada para receita até:</td>
							<td class="formTabela" width="100">
								<input name="valor" type="text" style="width:500px; margin-bottom:0px" alt="Dia" class="currency" value="<?php echo $des->getvalorRS(); ?>">
							</td>
						</tr>
						<tr>
							<td align="right" valign="top" class="formTabela">Observação:</td>
							<td class="formTabela" width="100">
								<textarea style="width:500px;height:200px;" id="txtObservacao" name="observacao"><?php echo $des->getObservacao(); ?></textarea>
							</td>
						</tr>
						<tr>
							<td align="right" valign="middle" class="formTabela">Fonte (lei):</td>
							<td class="formTabela" width="100">
								<input name="fonte" type="text" style="width:500px; margin-bottom:0px" alt="Dia" value="<?php echo $des->getFonte(); ?>">
								<input type="hidden" name="acao" value="<?php echo $des->getAcao(); ?>">
								<input type="hidden" name="id" value="<?php echo $des->getId(); ?>">
							</td>
						</tr>
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
<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>

<script>
			
	$(function(){

		$('#criterio').change(function(){

			if($(this).val() == 'no_ato'){
				$('#vencimento').prop('disabled', true);
				$('#vencimento').val(0);
			} else {
				$('#vencimento').prop('disabled', false);
			}
		});
		
	});
</script>

<script>
	
	$("#estado_des").change(function(event) {
		var uf = $(this).val();
		$.ajax({
			url: 'ajax.php',
			data: 'uf='+uf,
			dataType:"text",
			type:"POST",
			cache: false,
			success: function(response){
				$("#cidade_des").empty();
				$("#cidade_des").append(response);
			}
		});
	});

	$(function() {
        $('.currency').maskMoney();//
    })   

	$(".salvarDados").click(function() {
		
		if($("#nome_des").val() ==""){
			alert('Preencha o campo nome');      
			return false;
		} if($("#abreviacao_des").val() ==""){
			alert('Preencha o campo sigla');      
			return false;
		}
		
		$("form").submit();

	});
	
	CKEDITOR.replace( 'txtObservacao',
	{
		language: ['pt-br'],
				disableNativeSpellChecker:false,
				scayt_autoStartup:false,
				scayt_sLang: 'pt_BR',
		toolbar: 
		[
		  ['Source'],['Bold', 'Italic', '-', 'Link', 'Unlink','-','SpellChecker', 'Scayt'],['TextColor']
		],
		width: [ '504px' ],
		height: [ '255px' ],
		contentsCss : ['style.css'],
		forcePasteAsPlainText:[ false ]
	});

</script>

<?php include 'rodape.php' ?>
