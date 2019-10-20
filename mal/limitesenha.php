  <script src="https://code.jquery.com/jquery-1.10.2.js"></script>

<input type="text" name="" value="" placeholder="" maxlength="10" style="width:200px;">
<span>0</span>

<script>

	$("input").keypress(function(event) {
		console.log(event.KeyCode);
		$("span").empty();

		var tam = $("input").val().length + 1;
		var string = $("input").val();
		var nova_string = "";
		if(tam > 10){
			alert("Sua senha pode ter no máximo 10 caracteres");
		}
	});


</script>