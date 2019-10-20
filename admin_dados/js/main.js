$(document).ready(function(){
	// Preço
	if($("body").find(".preco").length > 0)
		$(".preco").maskMoney({prefix:'R$ ', allowNegative:false, thousands:'', decimal:'.', affixesStay: false});

	// Dicas
	if($("body").find("*[data-dica]").length > 0){
		$("*[data-dica]").focusin(function(){
			$(this).parent().append("<img class='dica' src='images/"+$(this).attr("data-dica")+"'>");
		});
		$("*[data-dica]").focusout(function(){
			$(this).parent().find(".dica").remove();
		});
	}
	// Sufixo
	if($("body").find("*[data-sufixo]").length > 0){
		$("*[data-sufixo]").each(function(){
			$(this).on("click keyup", function(){
			    var value = $(this).val();
			    var output = value.substring(0, value.length - 1) + $(this).attr("data-sufixo");
			    var cursorPosition = output.length - 1;
			    $(this).val(output);
			    $(this)[0].selectionStart = $(this)[0].selectionEnd = cursorPosition;
			});
		});
	}
});