<?php include '../conect.php';
include '../session.php';
include 'check_login.php' ?>
<?php include 'header.php' ?>

<div class="principal">

  <div class="titulo" style="margin-bottom:10px;">Suporte</div>
 
 <?php include 'suporte_menu.php' ?>
 
<div style="float:right; width:820px; padding-left:20px; border-left:#113b63 solid 1px"><span class="tituloVermelho">Busca Chamados</span><br />
  <br />


<?
// FOI FEITA UMA BUSCA
if($_POST['acao'] == 'buscar'){ 
?>

	<div style="float: right; font-weight: bold;"><a href="busca.php">nova busca</a></div>
    <div style="clear: both; height: 5px"></div>
    <table border="0" cellpadding="4" cellspacing="2">
        <tr>
            <th width="140">Início do chamado</th>
            <th width="70">Nome</th>
            <th width="500">Assunto</th>
            <th width="60">Ação</th>
        </tr>
        <?php 

		if($_POST['dt_inicio'] != ""){
			$busca_data = " AND (data BETWEEN '" . substr($_POST['dt_inicio'],6,4) . "-" . substr($_POST['dt_inicio'],3,2) . "-" . substr($_POST['dt_inicio'],0,2) . " 00:00:00' 
									  AND '" . substr($_POST['dt_inicio'],6,4) . "-" . substr($_POST['dt_inicio'],3,2) . "-" . substr($_POST['dt_inicio'],0,2) . " 23:59:59')";
		}
		if($_POST['assunto'] != ""){
			$busca_assunto = " AND titulo = '" . mysql_real_escape_string($_POST['assunto']) . "'";
		}
		if($_POST['assinante'] != ""){
			$busca_assinante = " AND assinante like '%" . mysql_real_escape_string($_POST['assinante']) . "%'";
		}
		if($_POST['palavras'] != ""){
			$busca_palavras = " MATCH(t.titulo, t.mensagem) AGAINST ('{".$_POST['palavras']."}' IN BOOLEAN MODE)";
			$busca_palavras_select = "," . $busca_palavras . " AS relevancia";
			$busca_palavras_where = " AND " . $busca_palavras . "";
		}

/*
		echo "DATA: " . $_POST['dt_inicio'] . "<BR>";
		echo "ASSUNTO: " . $_POST['assunto'] . "<BR>";
		echo "ASSINANTE: " . $_POST['assinante'] . "<BR>";
		echo "PALAVRAS: " . $_POST['palavras'] . "<BR>";
*/

        $sql = "
				SELECT 
					t.idPostagem
					, t.nome
					, t.data
					, t.titulo
					, t.ultimaResposta
					, t.mensagem
				" . $busca_palavras_select . "
				FROM 
					suporte t 
					LEFT JOIN login u ON t.id = u.id
				WHERE 
					1 = 1
					AND u.id = u.idUsuarioPai
					" . $busca_data . "
					" . $busca_assunto . "
					" . $busca_assinante . "
					" . $busca_palavras_where . "
				ORDER BY data DESC
		";
		//					AND t.idPergunta IS NULL
//		echo($sql);
		
        $resultado = mysql_query($sql)
        or die (mysql_error());
        
        while ($linha=mysql_fetch_array($resultado)) { 
        ?>  
        <tr class="guiaTabela">
            <td><?=date('d/m/Y', strtotime($linha["data"]))?>, às <?=date('H:i', strtotime($linha["data"]))?></td>
            <td><?=$linha["nome"]?></td>
            <td><?=$linha["titulo"]?></td>
            <td><a href="suporte_visualizar.php?codigo=<?=$linha["idPostagem"]?>">Visualizar</a></td>
        </tr>
        <? 
		} 
		?>
    </table>

<?
// FORMULÁRIO
} else {
?>

	<script>
    
	$(document).ready(function(e) {
        $('#btnBuscar').click(function(){
			$('#form_busca').submit();
		});
		
		$('#assinante').keyup(function(){
			if($(this).val() != ''){
				$.ajax({
					url:'preenchecampobusca.php',
					type: 'POST',
					data: 'valor='+$('#assinante').val(),
					async: false,
					cache:false,
					success: function(result){
						if(result != ''){
							$('#preenchimentoBusca').css({
								'height':'auto'
								,'display': 'block'
							}).fadeIn('fast');
							$('#preenchimentoBusca').html(result);
						} else {
							$('#preenchimentoBusca').html('').css('display','none');
						}
					}						
				});
				
				$('.selResultBusca').live('click',function(){
					$('#assinante').val($(this).html());
					$('#hddIdUser').val($(this).attr('iduser'));
					$('#preenchimentoBusca').fadeOut('fast');
				});
			}else{
				$('#preenchimentoBusca').fadeOut('fast');
			}
		});
    });
    
    </script>

	<div>
    <form name="form_busca" id="form_busca" method="post" action="busca.php">
    <input type="hidden" name="acao" value="buscar" />
    	<div style="float: left; width: 170px; font-weight: bold;">Data de início do chamado: </div>
    	<div style="float: left; width: 500px;"><input name="dt_inicio" id="dt_inicio" type="text" size="12" maxlength="10" class="campoData" /></div>
    	<div style="clear: both; height: 5px;"></div>

    	<div style="float: left; width: 170px; font-weight: bold;">Assunto: </div>
    	<div style="float: left; width: 500px;"><input name="assunto" id="assunto" type="text" size="60" maxlength="100" /></div>
    	<div style="clear: both; height: 5px;"></div>

    	<div style="float: left; width: 170px; font-weight: bold;">Palavras-chave: </div>
    	<div style="float: left; width: 500px;"><input name="palavras" id="palavras" type="text" size="60" maxlength="100" /> <em>(separadas por ",")</em></div>
    	<div style="clear: both; height: 5px;"></div>

    	<div style="float: left; width: 170px; font-weight: bold;">Assinante: </div>
    	<div style="float: left; width: 500px;">
	        <input type="hidden" name="hddIdUser" id="hddIdUser" />
        	<input type="text" name="assinante" id="assinante" size="60" maxlength="255" />
            <div id="preenchimentoBusca"></div>
        </div>
    	<div style="clear: both; height: 5px;"></div>

    	<div style="float: left; width: 170px; font-weight: bold;"></div>
    	<div style="float: left; width: 500px;"><input type="button" value="Buscar" id="btnBuscar" /></div>
    	<div style="clear: both; height: 5px;"></div>
    </form>
    </div>	

<?
}
?>
</div>
<div style="clear:both"> </div>
</div>


<?php include '../rodape.php' ?>
