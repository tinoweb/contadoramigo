<?php 
	
	include '../conect.php';
	include '../session.php';
	include 'check_login.php'; 

if(isset($_POST['txtTexto'])){

	mysql_query("UPDATE conteudo SET texto = '" . mysql_real_escape_string($_POST['txtTexto']) . "' WHERE area = 'home'");

	header('location: textos_home.php' . $link_param);

}

?>
<?php include 'header.php' ?>

<div class="principal minHeight">


<?

$sql = "SELECT * FROM  conteudo LIMIT 0 , 30";
$resultado = mysql_query($sql);
$dados = mysql_fetch_object($resultado);

?>

  

<script type="text/javascript" src="../ckeditor/ckeditor.js?v=4.3"></script>


  <div class="titulo" style="margin-bottom:10px;">Texto para a home</div>
  <div style="clear:both"> </div>

  <div style="clear:both"> </div>
  
  <div style="margin-top: 20px; display: table; text-align:left">
  
  <form name="frmTextoHome" id="frmTextoHome" action="textos_home.php" method="post">
    <textarea name="txtTexto" id="txtTexto"><?=$dados->texto?></textarea>
    <script type="text/javascript">

		CKEDITOR.replace('txtTexto',
    {
				customConfig : '../ckeditor/config_textos_home.js',
				allowedContent: true,
    });
    </script>

	  <div style="clear:both;height:10px;"> </div>
  	
    <input type="button" name="btEnvia" id="btEnvia" value="Envia" />
  </form>
  </div>

  <div style="clear:both"> </div>
  

<script>

$(document).ready(function(e) {

  $('#btEnvia').click(function(e){
		$('#frmTextoHome').submit();
	});

});
</script>

<?php include '../rodape.php' ?>
