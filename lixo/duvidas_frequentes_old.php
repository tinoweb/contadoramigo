<?php 
session_start();
$nome_meta = "duvidas_frequentes";
?>
<?php if(isset($_SESSION["id_empresaSecao"])){?>
<?php include 'header_restrita.php' ?>
<?php } else { ?>
<?php include 'header_old.php' ?>
<?php } ?>

<script>
	function validaForm(form) {	
		if (form.txtEmail.value==''){
			alert('Digite o Email.');
			form.txtEmail.focus();
		}else{		
			if (form.txtSenha.value==''){
				alert('Digite a Senha.');
				form.txtSenha.focus();
			}else{		
				form.submit();
			}
		}
	}

	// function supports_video() {
	//   return !!document.createElement('video').canPlayType;
	// }

	$(document).ready(function(e) {
	
		var indice_selecionada = 0;
		
		if(supports_video()){ // checando se suporta html5
			$("#video_html5").css('display','block');
			$("#video_flash").css('display','none');
		}else{
			$("#video_html5").css('display','none');
			$("#video_flash").css('display','block');
		}
		
	    $('.perguntas > li').each(function(index, element) {
	        $(this).css({
				'cursor':'pointer'
				,'color':'#666666'
				, 'text-decoration':'none'
			});
	    }).hover(function(){
			$(this).css({
				'color':'#336699'
				, 'text-decoration':'underline'
			});
		},function(){
			$(this).css({
				'color':'#666666'
				, 'text-decoration':'none'
			});
		}).bind('click',function(){
			indice_selecionada = $('.perguntas > li').index($(this));
			var pergunta = $(this).html();
			pergunta = pergunta.replace('<h3>','');
			pergunta = pergunta.replace('</h3>','');
			console.log(pergunta);

			$('source[type="video/mp4"]').attr('src','videos/duvida_'+(indice_selecionada + 1)+'.mp4a');
			$('source[type="video/ogg"]').attr('src','videos/duvida_'+(indice_selecionada + 1)+'.ogv');
			if(supports_video()){ // checando se suporta html5
				var video = $('#video_html5')[0];
		        video.load();
				video.play();
			} else {
				$('#video_flash').flash({  
					"src":"video_fac.swf",  
					"width":"640px",
					"height":"360px",  
					"vars":{"file":"videos/duvida_"+(indice_selecionada + 1)+".mp4"},  
					"quality":"high"
				}); 	
			}		
			$('#div_pergunta').html(pergunta);	
		});



		if(!supports_video()){ // checando se suporta html5

			$('#video_flash').flash({  
				"src":"video_fac.swf",  
				"width":"640px",
				"height":"360px",  
				"vars":{"file":"videos/duvida_"+(indice_selecionada + 1)+".mp4"},  
				"quality":"high"
			});
			
		}

		// $('#div_pergunta').html($('.perguntas > ul > li ').eq(0).html());
		
		// $('#div_pergunta').html("Entenda como funciona a contabilidade online do Contador Amigo e tire suas dúvidas.");

	});
	


</script>
<div class="principal">

<div class="titulo">Dúvidas Frequentes</div>
<h1 class="tituloVermelho" style="margin-bottom:20px" id="div_pergunta">
Entenda como funciona a contabilidade online do Contador Amigo e tire suas dúvidas.
</h1>
<div style="width:640px; border-style:solid; border-width:1px; border-color:#CCCCCC; float:left; margin-right:20px">
    
    <video id="video_html5" width="640" height="360" controls>
    <source src="videos/duvida_1.mp4" type='video/mp4'> 
    <source src="videos/duvida_1.ogv" type='video/ogg'>
    </video>
    
    <div id="video_flash" style="width:640px;height:360px"></div>
    
</div>

<div style="width:300px; float:left; overflow-y:scroll; height: 360px ; border-style:solid; border-width:1px; border-color:#CCCCCC; background-color:#FFFFFF">
<div style="padding:20px">
<h2 style="font-size:18px; margin-bottom:20px">Clique na pergunta abaixo e <br />
assista ao vídeo com a resposta.</h2>
<ul class="perguntas" style="padding-left:20px">
<li><h3>Quais empresas podem usufruir dos serviços oferecidos pelo Contador Amigo?</h3></li><br />
<li><h3>Posso ter mais de uma empresa cadastrada em uma única assinatura?</h3></li><br />
<li><h3>Se eu parar de pagar as mensalidades, o que acontece?</h3></li><br />
<li><h3>Caso eu necessite de um comprovante de rendimentos, quem vai assinar esse comprovante?</h3></li><br />
<li><h3>Quando eu assinar o Contador Amigo e fizer minha própria contabilidade, como posso ter certeza de que estou fazendo tudo certo?</h3></li><br />
<li><h3>Se eu fizer minha assinatura no Contador Amigo, serei obrigado a permanecer com ela por quanto tempo?</h3></li><br />
<li><h3>Atende empresa de outros estados?</h3></li><br />
<li><h3>Quanto custa?</h3></li><br />
<li><h3>Posso dispensar o meu contador?</h3></li><br />
<li><h3>Atende empresas com funcionários?</h3></li><br />
<li><h3>Um leigo pode usar?</h3></li><br />
<li><h3>Existe previsão para cadastramento de empresas no ramo de comércio?</h3></li><br />
<li><h3>O contador não precisa assinar os livros ?</h3></li><br />
<li><h3>Se eu tiver alguma dúvida com relação aos meus impostos, como poderei saná-la?</h3></li><br />
<li><h3>O Contador Amigo está vinculado a algum escritório de contabilidade?</h3></li><br />
<li><h3>O Contador Amigo faz algum uso das minhas informações cadastrais?</h3></li><br />
<li><h3>Se eu cancelar minha assinatura, posso guardar as informações gravadas no Livro Caixa?</h3></li><br />
<li><h3>Sou Contador. Posso cadastrar as contas de meus clientes no Contador Amigo para poder gerenciá-las melhor?</h3></li><br />
<li><h3>O Contador Amigo  mostra como abrir uma empresa?</h3></li><br />
<li><h3>Aceitam parceiras?</h3></li><br />
<li><h3>Como fica o imposto de renda de pessoa jurídica?</h3></li>
</ul>
</div>
</div>

<div style="clear:both"> </div>
</div>

</div>



<?php include 'rodape.php' ?>
