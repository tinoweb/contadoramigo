<?php include 'header_restrita.php' ?>
<script>
function PrintElementID(id, pg) {
	var oPrint, oJan;
	oPrint = CKEDITOR.instances['txtMensagem'].getData();
	oJan = window.open(pg);
	oJan.document.write(oPrint);
	oJan.history.go();
	oJan.window.print();
}

function CopyToClipBoard() 

{ 

var div = document.getElementById('conteudoImpressao'); 
div.contentEditable = true; //very important. 
var range = document.body.createControlRange(); 

range.addElement(document.getElementById('conteudoImpressao')); 

range.select(); 

range.execCommand("Copy"); 

range.remove(0); 

range.select(); 

alert("Documento copiado! Abra o Word e cole o texto para editá-lo"); 

div.contentEditable = false; 

return false; 

} 

</script>
<script type="text/javascript" src="./ckeditor/ckeditor.js"></script>
<div class="principal">
<h1>Alteração de Contrato Social</h1>
  
  <h2>Aplicativo para Alteração Contratual</h2>

Seu documento de alteração contratual está pronto! Confira-o atentamente. Se precisar, você pode acrescentar outras cláusulas ou fazer alterações.  Imprima 3 vias do documento. As páginas deverão estar rubricadas e assinadas pelos sócios ou seus procuradores, com as firmas devidamente reconhecidas. Depois de pronto, volte à página de instruções para seguir as próximas etapas do processo.<br />
<br />
<br />
<center>
 <td class="formTabela"><textarea name="txtMensagem" id="txtMensagem" style="width:100%; height:500px"><div style="background:#FFF; color:#000"><div id="conteudoImpressao" style="padding:20px">

 		<?php include 'alteracao_contrato_impressao.php'; ?>



</div>

 	</div></div></textarea>
	 <script type="text/javascript">
    CKEDITOR.replace( 'txtMensagem',
    {
        language: ['pt-br'],
				disableNativeSpellChecker:true,
				scayt_autoStartup:true,
				scayt_sLang: 'pt_BR',
        toolbar: 
        [
          ['Styles'],['Format'],['Font'],['FontSize'],['TextColor'],['BGColor'],['Undo'],['Redo'],['Bold'],['Italic'],['Underline'],['NumberedList'],['BulletedList'],['Outdent'],['Indent'],['Blockquote'],['CreateDiv'],['JustifyLeft'],['JustifyCenter'],['JustifyRight'],['JustifyBlock'],['Link'],['Unlink'],['Image'],['Table'],['HorizontalRule'],['UIColor'],['Maximize']
        ],
        width: [ '960px' ],
        height: [ '500px' ],
        contentsCss : ['style.css'],
        forcePasteAsPlainText:[ false ]
    });
    </script>



<br />
<input type="button" style="float: left"value="Voltar" onClick="location.href='alteracao_contrato.php'" /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" style="float: left;margin-left:5px;"value="Imprimir" onClick="PrintElementID('conteudoImpressao', 'alteracao_contrato_impressao.php')" /> 
<br>
</div>
<?php include 'rodape.php' ?>
