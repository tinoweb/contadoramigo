<?php $nome_meta = "duvidas_frequentes";?>
<?php include 'header_mobile.php' ?>
<?php include 'menu_mobile2.php' ?>


<script language="JavaScript">



function supports_video() {
  return !!document.createElement('video').canPlayType;
}

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
		var pergunta = $(this).find('h3').html();

		$('source[type="video/mp4"]').attr('src','videos/duvida_'+(indice_selecionada + 1)+'.mp4');
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

	$('#div_pergunta').html($('.perguntas > ul > li ').eq(0).html());
});

</script>
<div class="principal">

<h1 style="font-size:1em">Dúvidas Frequentes</h1>

<h2 style="margin-bottom:20px" id="div_pergunta">
Clique na pergunta e assista ao vídeo.
</h2>

    <center>
    <video id="video_html5" width="90%"  controls style="margin-bottom:5px">
    <source src="videos/duvida_1.mp4" type='video/mp4'> 
    <source src="videos/duvida_1.ogv" type='video/ogg'>
    </video>
    
    <div id="video_flash" style="width:100%"></div>
    


<div style="width:90%; overflow-y:scroll; height: 150px ; border-style:solid; border-width:1px; border-color:#CCCCCC; background-color:#FFFFFF; text-align:left; margin-bottom:20px">
<div style="padding:10px">

<ul class="perguntas" style="margin-left:0px; padding-left:10px; display:inline">
<li>Quais empresas podem usufruir dos serviços oferecidos pelo Contador Amigo?</li><br>
<li>Posso ter mais de uma empresa cadastrada em uma única assinatura?</li><br>
<li>Se eu parar de pagar as mensalidades, o que acontece?</li><br>
<li>Caso eu necessite de um comprovante de rendimentos, quem vai assinar esse comprovante?</li><br>
<li>Quando eu assinar o Contador Amigo e fizer minha própria contabilidade, como posso ter certeza de que estou fazendo tudo certo?</li><br>
<li>Se eu fizer minha assinatura no Contador Amigo, serei obrigado a permanecer com ela por quanto tempo?</li><br>
<li>Atende empresa de outros estados?</li><br>
<li>Quanto custa?</li><br>
<li>Posso dispensar o meu contador?</li><br>
<li>Atende empresas com funcionários?</li><br>
<li>Um leigo pode usar?</li><br>
<li>Existe previsão para cadastramento de empresas no ramo de comércio?</li><br>
<li>O contador não precisa assinar os livros ?</li><br>
<li>Se eu tiver alguma dúvida com relação aos meus impostos, como poderei saná-la?</li><br>
<li>O Contador Amigo está vinculado a algum escritório de contabilidade?</li><br>
<li>O Contador Amigo faz algum uso das minhas informações cadastrais?</li><br>
<li>Se eu cancelar minha assinatura, posso guardar as informações gravadas no Livro Caixa?</li><br>
<li>Sou Contador. Posso cadastrar as contas de meus clientes no Contador Amigo para poder gerenciá-las melhor?</li><br>
<li>O Contador Amigo  mostra como abrir uma empresa?</li><br>
<li>Aceitam parceiras?</li><br>
<li>Como fica o imposto de renda de pessoa jurídica?</li><br>
</ul>
</div>
</div>

</center>
</div>

<?php include 'rodape_mobile.php' ?>
