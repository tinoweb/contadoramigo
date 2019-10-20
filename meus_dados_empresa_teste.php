<?php
session_start();
//$_SESSION["id_userSecao"]						= $_SESSION["id_userSecaoMultiplo"];
?>

<?php include 'header_restrita.php' ?>

<script type="text/javascript">
	$( document ).ready( function () {


		$.mostraResultado = function ( texto, qualDiv ) {
			qualDiv.html( texto );
		}

		$.consultaBancoAjax = function ( URL, qualDiv, oCampo ) {
			//		alert(URL);
			$.ajax( {
				url: URL,
				type: 'get',
				dataType: "json",
				cache: false,
				async: true,
				beforeSend: function () {},
				success: function ( retorno ) {
					if ( !retorno.status ) {
						alert( retorno.mensagem )
						$.mostraResultado( '', qualDiv );
						oCampo.val( '' ).focus();
					} else {
						$.mostraResultado( retorno.mensagem, qualDiv );
					}
				}
			} );
		};


		$.enviaAjax = function ( URL, DATA, METHOD, DATATYPE, SUCCESS ) {
			return $.ajax( {
				url: URL,
				data: DATA,
				type: METHOD,
				dataType: DATATYPE,
				cache: false,
				async: false,
				beforeSend: function () {},
				success: SUCCESS
			} );
		};


		function getQTDEmpresasCadastradas( URL, DATA, METHOD, DATATYPE, SUCCESS ) {
			return $.enviaAjax( URL, DATA, METHOD, DATATYPE, SUCCESS );
		}




		<?=number_format($mensalidade_unitaria,2,',','.')?> { ? >
			var checaAvisoMensalidade = false;

			// AVISO DE ACRESCIMO DA MENSALIDADE
			$.enviaAjax( 'check_qtd_empresas.php', '', 'post', 'html', function ( retorno ) {
				if ( retorno > 0 ) {
					$( '#linhaAlertaMensalidade' ).css( "display", "block" );
					checaAvisoMensalidade = true;
				}
			} );
			<?=number_format($mensalidade_unitaria,2,',','.')?>
			<?=number_format($mensalidade_unitaria,2,',','.')?>
			false;

			var botaoSubmit = $( '#btSalvar' );

			if ( validElement( 'txtRazaoSocial', msg1 ) == false ) {
				erro = true;
				return false
			}
			//if( validElement('txtNomeFantasia', msg1) == false){erro = true; return false}
			if ( validElement( 'txtCNPJ', msg1 ) == false ) {
				erro = true;
				return false
			}
			if ( $( '#txtCNPJ' ).val().length != 18 ) {
				alert( 'Digite o CNPJ corretamente.' );
				erro = true;
				return false;
			}
			if ( validElement( 'txtCNAE_Principal', msg1 ) == false ) {
				erro = true;
				return false
			}

			if ( $( '#atividadePrincipal' ).html() == '' && $( '#txtCNAE_Principal' ).val() != '' ) {
				alert( "Digite um CNAE válido na atividade principal." );
				erro = true;
				return false;
			}

			// conta numero de campos do hideen count da linha 373
			//
			var count = parseInt( $( '#count' ).val() ) - 1;
			if ( count >= 0 ) {
				for ( i = 1; i <= count; i++ ) {
					if ( $( '#atividade' + i ).html() == '' && $( '#txtCodigoCNAE' + i ).val() != '' ) {
						alert( "Digite um CNAE válido no " + i + "º campo das atividades secundárias." );
						erro = true;
						return false;
					}
				}
			}
			if ( validElement( 'txtEndereco', msg1 ) == false ) {
				erro = true;
				return false
			}
			if ( validElement( 'txtBairro', msg1 ) == false ) {
				erro = true;
				return false
			}
			if ( validElement( 'txtCEP', msg1 ) == false ) {
				erro = true;
				return false
			}

			if ( validElement( 'txtCidade', msg1 ) == false ) {
				erro = true;
				return false
			}
			var Cidade = $( '#txtCidade' );
			if ( Cidade.val().toLowerCase() == 'são paulo' || Cidade.val().toLowerCase() == 'sao paulo' ) {
				Cidade.val( 'São Paulo' );
			}
			var Estado = $( '#selEstado' );
			if ( Estado.selectedIndex == "" ) {
				window.alert( msg2 + 'o Estado.' );
				erro = true;
				return false;
			}
			//			if(Estado.value != 'SP') {
			//				window.alert(msg5);
			//				return false;
			//			}
			var RamoAtividade = $( '#selRamoAtividade' );
			if ( RamoAtividade.selectedIndex == "" ) {
				window.alert( msg2 + 'Ramo de Atividade de sua empresa.' );
				erro = true;
				return false;
			}
			if ( RamoAtividade.value == "Comércio" || RamoAtividade.val() == "Indústria" ) {
				window.alert( msg3 );
				erro = true;
				return false;
			}
			/*
					var countPrefeitura = parseInt(document.getElementById('countPrefeitura').value) - 1;
					if (countPrefeitura >= 2) {
						for(i=1;i<=countPrefeitura;i++) {
							if(document.getElementById('pesquisaCampoPrefeitura'+i).value == 'erro' && document.getElementById('txtCodigoAtividadePrefeitura'+i).value != '') {
								alert("Digite Código de Serviço  valido no "+i+"º campo.");
								return false;
							}
						}
					}
			*/
			var RegimeTributacao = $( '#selRegimeTributacao' );
			if ( RegimeTributacao.val() == "Lucro Presumido" ) {
				window.alert( msg4 );
				erro = true;
				return false;
			}
			if ( $( '#selInscritaComo' ).value != 'Sociedade Simples' ) {
				if ( $( '#txtRegistroNire' ).val() != "" ) {
					/*
									if(document.getElementById('txtRegistroNire').value.length < 11){
										alert('Digite o Nire corretamente.'); 
										return false;
									}

									if (ValidaNire(document.getElementById('txtRegistroNire').value) == false){
										alert('Digite o Nire somente com números.'); 
										return false;
									}
					*/
				}
			}

			// if($('#txtDataCriacao').val() != ""){
			if ( ValidaDataCriacao( $( '#txtDataCriacao' ).val() ) == false ) {
				alert( 'Digite a data de criação no formato DD/MM/AAAA' );
				erro = true;
				$( '#txtDataCriacao' ).focus();
				return false;
			}
			// }

			//		$.enviaAjax('check_qtd_empresas.php', '', 'post', 'html', function(retorno){
			//			if(retorno > 0){
			//				if(!confirm('A inclusão de uma nova empresa acarretará no aumento\nde R$ <?=number_format($mensalidade_unitaria,2,',','.')?> na sua mensalidade. Deseja prosseguir?')){
			//					erro = true;
			//				}
			//			}
			//		});

			if ( checaAvisoMensalidade ) {
				if ( !$( '#chkAvisoMensalidade' ).is( ':checked' ) ) {
					alert( 'Para prosseguir, é necessário marcar que está ciente do acréscimo da mensalidade' );
					$( '#chkAvisoMensalidade' ).focus();
					erro = true;
					return false;
				}
			}

			if ( erro == false ) {


				$( "#btSalvar" ).parent().find( '.divCarregando2' ).css( 'display', 'block' );

				var $data = $( '#form_empresa' ).serialize();

				$.ajax( {
					url: 'meus_dados_empresa_gravar.php',
					type: 'post',
					data: $data,
					cache: false,
					async: true,
					dataType: "json",
					beforeSend: function () {
						botaoSubmit.hide();
						botaoSubmit.attr( 'disabled', true );
					},
					complete: function () {
						botaoSubmit.show();
					},
					success: function ( retorno ) {
						if ( retorno.status ) {
							botaoSubmit.attr( 'disabled', false );
							$( '#btGerenciarEmpresaCadastrada' ).attr( 'idEmpresa', retorno.IDEmpresa );
							$( '#btGerenciarEmpresaCadastrada' ).attr( 'qtd', retorno.qtdEmpresas );
							window.scrollTo( 0, 0 );
							<?=$_SERVER['HTTP_REFERER']?>
							$_GET[ 'act' ] == 'new' ) { ? >
							$( '#aviso-cadastrar-nova' ).fadeIn( 100 );
							<?=$_SERVER['HTTP_REFERER']?>
							so - empresa - alterada ').fadeIn(100);
							<?=$_SERVER['HTTP_REFERER']?>
							parent().find( '.divCarregando2' ).css( 'display', 'none' );

						}
					}
				} );

			}


		}

		$( '#btFecharBallomEmpresaCadastrada' ).bind( 'click', function () {

			$( '#aviso-empresa-alterada' ).fadeOut( 100 );

		} );





		$( '#btCadastrarNovaEmpresa' ).bind( 'click', function () {

			location.href = "meus_dados_empresa.php?act=new";

		} );


		$( '#btGerenciarEmpresaCadastrada' ).bind( 'click', function () {

			//location.href="<?=$_SERVER['HTTP_REFERER']?>";
			if ( $( this ).attr( "qtd" ) > 1 ) {
				location.href = "gerenciar_empresa.php?id=" + $( this ).attr( "idEmpresa" );
			} else {
				location.href = "meus_dados_empresa_gerenciar.php?id=" + $( this ).attr( "idEmpresa" );
			}

		} );

		$( '#btCancelar' ).click( function () {
			location.href = 'meus_dados_empresa.php';
		} );

		$( '#btSalvar' ).click( function ( e ) {
			e.preventDefault();

			var
				botao = $( this ),
				erro = false,
				urlCNPJ = "",
				urlID = "";

			botao.attr( 'disabled', true );

			if ( $( '#hidID' ).val() != '' ) {
				urlID = 'id=' + $( '#hidID' ).val();
			}
			if ( $( '#txtCNPJ' ).val() != '' ) {
				if ( urlID != '' ) {
					urlCNPJ = "&";
				}
				urlCNPJ += 'cnpj=' + $( '#txtCNPJ' ).val();
			}
			if ( urlCNPJ != '' || urlID != '' ) {
				$.ajax( {
					url: 'meus_dados_checa_cnpj.php?' + urlID + urlCNPJ,
					type: 'get',
					cache: false,
					async: true,
					beforeSend: function () {

					},
					success: function ( retorno ) {
						if ( retorno == 1 ) {
							alert( 'Já existe uma empresa cadastrada com este CNPJ!' );
							$( '#txtCNPJ' ).focus();
							botao.attr( 'disabled', false );
							erro = true;
							return false;
						}
					}
				} );
			}

			if ( erro == false ) {
				botao.attr( 'disabled', false );
				formSubmit();
			}

		} );

		var limite_cnpj = 0

		$( '#txtCNPJ' ).blur( function () {
			var erro = false;
			if ( $( this ).val() != '' ) { // && $('#hidID').val() != ''){
				$.ajax( {
					url: 'meus_dados_checa_cnpj.php?id=' + $( '#hidID' ).val() + '&cnpj=' + $( this ).val(),
					type: 'get',
					cache: false,
					async: true,
					beforeSend: function () {},
					success: function ( retorno ) {
						if ( retorno == 1 ) {
							$( '#txtCNPJ' ).focus();
							if ( limite_cnpj === 0 ) {
								alert( 'Já existe uma empresa cadastrada com este CNPJ!' );
								limite_cnpj = 1;
							}

						}
					}
				} );
			}

		} );


		$( '#selEstado' ).bind( 'change', function () {
			var arrDadosEstado = $( '#selEstado' ).val().split( ';' );
			var idUF = arrDadosEstado[ 0 ];
			$.getJSON( 'consultas.php?opcao=cidades&valor=' + idUF, function ( dados ) {
				if ( dados.length > 0 ) {
					var option = '<option></option>';
					$.each( dados, function ( i, obj ) {
						option += '<option value="' + obj.cidade + '">' + obj.cidade + '</option>';
					} )
					$( '#txtCidade' ).html( option ).show();
				}
			} );
		} );


		$( '#txtCNAE_Principal, input[id^="txtCodigoCNAE"]' ).bind( 'change', function () {
			var
				$campo = $( this ),
				$div = $campo.attr( 'div' ),
				$campoCheck = $( '#' + $campo.attr( 'check' ) ),
				$idEmpresa = $( '#hidID' ).val();;
			if ( $campo.val() != '' ) {
				var URL = 'meus_dados_empresa_consulta_cnae.php?acao=blur&codigo=' + $campo.val() + '&campoCheck=' + $campoCheck.val() + '&idEmpresa=' + $idEmpresa
				$.consultaBancoAjax( URL, $( '#' + $div ), $campo );
			}

		} );


		//	console.log($('#count').val());



		$( '.btAdicionaCNAE' ).bind( 'click', function ( e ) {
			e.preventDefault();
			reposicionaBallons();

			var orig = $( '#content' );
			var count = parseInt( $( '#count' ).val() ) + 1;

			$( '#count' ).val( count );

			orig.append( '<div id="item' + count + '"><div style="float:left; margin-right:5px"><input name="txtCodigoCNAE' + count + '" id="txtCodigoCNAE' + count + '" type="text" style="width:84px; margin-top:3px;" class="campoCNAE" div="atividade' + count + '" check="hddCodigoCNAE' + count + '"><input type="hidden" name="hddCodigoCNAE' + count + '" id="hddCodigoCNAE' + count + '" value=""></div> <div id="atividade' + count + '" style="float:left; margin-top:5px"></div><div style="clear:both"></div></div>' );


			orig.find( 'input[id="txtCodigoCNAE' + count + '"]' ).mask( '9999-9/99' );


			$( 'input[id^="txtCodigoCNAE"]' ).bind( 'change', function () {
				//console.log($('#count').val());
				var
					$campo = $( this ),
					$div = $campo.attr( "div" ),
					$campoCheck = $( '#' + $campo.attr( 'check' ) ),
					$idEmp = $( '#hidID' ).val();;

				//			alert($div);
				//			alert($campo.attr('check'));
				//			alert($idEmp);

				if ( $campo.val() != '' ) {
					var URL = 'meus_dados_empresa_consulta_cnae.php?acao=blur&codigo=' + $campo.val() + '&campoCheck=' + $campoCheck.val() + '&idEmpresa=' + $idEmp
					$.consultaBancoAjax( URL, $( '#' + $div ), $campo );
				}

			} );


			//console.log($('#count').val());

		} );


		$( '.btRemoveCNAE' ).bind( 'click', function ( e ) {
			e.preventDefault();
			reposicionaBallons();
			var count = parseInt( $( '#count' ).val() );
			if ( count > 1 ) {
				//			var orig = $('#content');
				//			var removeDiv = document.getElementById('item'+(count-1));
				$( '#item' + ( count - 1 ) ).remove();
				$( '#count' ).val( count - 1 );
			}
		} );




	} );


	var msg1 = 'É necessário preencher o campo';
	var msg2 = 'É necessário selecionar ';
	var msg3 = 'No momento o Contador Amigo não oferece suporte para empresas do ramo de Comércio e Indústria.';
	var msg4 = 'No momento o Contador Amigo não oferece suporte para empresas com Lucro Presumido.';
	// var msg5 = 'Empresas de outros estados ou cidades podem usar o Contador Amigo, porém não terão suporte referente à emissão de notas fiscais e para pagamento da TFE/TFA, cujos valores são determinados pelas respectivas prefeituras. Geralmente estes impostos chegam pelo correio e são pagos apenas 1 vez por ano.';

	function consultaBanco22( qualPagina, qualDiv ) {
		var xmlHttp2 = getXMLHttp();

		xmlHttp2.onreadystatechange = function () {
			if ( xmlHttp2.readyState == 4 ) {
				HandleResponse( xmlHttp2.responseText, qualDiv );
			}
		}

		xmlHttp2.open( "GET", qualPagina, true );
		xmlHttp2.send( null );
	}

	function HandleResponse( response, qualDiv ) {
		document.getElementById( qualDiv ).value = response;
	}

	//function consultaCNPJ(){
	//	if(document.getElementById('txtCNPJ').value != '') {
	//		consultaBancoAjax('meus_dados_checa_cnpj.php?id=' + document.getElementById('hidID').value + '&cnpj=' + document.getElementById('txtCNPJ').value, 'divCNPJ');
	//
	//	}
	//}

	function valida_cnpj( cnpj ) {
		exp = /\d{14}/
		if ( !exp.test( cnpj ) )
			return false;
	}

	function ValidaCep( cep ) {
		exp = /\d{8}/
		if ( !exp.test( cep ) )
			return false;
	}

	function ValidaNire( nire ) {
		exp = /\d{11}/
		if ( !exp.test( nire ) )
			return false;
	}

	function ValidaDataCriacao( dataCriacao ) {
		exp = /\d{2}\/\d{2}\/\d{4}/
		if ( !exp.test( dataCriacao ) )
			return false;
	}


	function add() {
		var orig = document.getElementById( 'content' );
		var count = parseInt( document.getElementById( 'count' ).value );
		var newDiv = document.createElement( 'div' );
		newDiv.setAttribute( "id", "item" + count );
		var newContent = "<div style=\"float:left; margin-right:5px\"><input name=\"txtCodigoCNAE" + count + "\" id=\"txtCodigoCNAE" + count + "\" type=\"text\" style=\"width:84px; margin-top:3px;\" class=\"campoCNAE2\"></div> <div id=\"atividade" + count + "\" style=\"float:left; margin-top:5px\"><input type=\"hidden\" id=\"pesquisaCampo" + count + "\" name=\"pesquisaCampo" + count + "\" value=\"ok\" /></div> <div style=\"clear:both\"> </div>";
		newDiv.innerHTML = newContent;
		orig.appendChild( newDiv );
		document.getElementById( 'count' ).value = count + 1;
	}

	function remove() {
		var count = parseInt( document.getElementById( 'count' ).value );
		if ( count > 1 ) {
			var orig = document.getElementById( 'content' );
			var removeDiv = document.getElementById( 'item' + ( count - 1 ) );
			orig.removeChild( removeDiv );
			document.getElementById( 'count' ).value = count - 1;
		}
	}

	function addPrefeitura() {
		var orig = document.getElementById( 'contentPrefeitura' );
		var count = parseInt( document.getElementById( 'countPrefeitura' ).value );
		var newDiv = document.createElement( 'div' );
		newDiv.setAttribute( "id", "itemPrefeitura" + count );
		// insere input hidden pesquisa CampoPrefeitura, ca vez que a pessoa clica no botão de adicionar codigo da prefeitura
		var newContent = '<input name="txtCodigoAtividadePrefeitura' + count + '" id="txtCodigoAtividadePrefeitura' + count + '" type="text" style="width:40px; float:left; margin-right:3px; margin-top:3px" value="" maxlength="5" onblur="consultaBancoAjax(\'meus_dados_empresa_consulta_codigo_prefeitura.php?codigo=\'+document.getElementById(\'txtCodigoAtividadePrefeitura' + count + '\').value+\'&campo=' + count + '\', \'denomicacaoPrefeitura' + count + '\');" /><div id="denomicacaoPrefeitura' + count + '" style="float:left; margin-top:5px"><input type="hidden" id="pesquisaCampoPrefeitura' + count + '" name="pesquisaCampoPrefeitura' + count + '" value="ok" /></div><div style="clear:both"> </div>';
		newDiv.innerHTML = newContent;
		orig.appendChild( newDiv );
		document.getElementById( 'countPrefeitura' ).value = count + 1;
	}

	function removePrefeitura() {
		var count = parseInt( document.getElementById( 'countPrefeitura' ).value );
		if ( count > 2 ) {
			var orig = document.getElementById( 'contentPrefeitura' );
			var removeDiv = document.getElementById( 'itemPrefeitura' + ( count - 1 ) );
			orig.removeChild( removeDiv );
			document.getElementById( 'countPrefeitura' ).value = count - 1;
		}
	}

	function selecionaRegistro() {alert('oi')
		if ( document.getElementById( 'registrado_em' ).value == '1' ) {
			abreDiv( 'divRegistroCartorio' );
			fechaDiv( 'divRegistroNire' );
		} else {
			abreDiv( 'divRegistroNire' );
			fechaDiv( 'divRegistroCartorio' );
		}
	}

	function getPosicaoElemento( elemID ) {
		var offsetTrail = document.getElementById( elemID );
		var offsetLeft = 0;
		var offsetTop = 0;
		while ( offsetTrail ) {
			offsetLeft += offsetTrail.offsetLeft;
			offsetTop += offsetTrail.offsetTop;
			offsetTrail = offsetTrail.offsetParent;
		}
		if ( navigator.userAgent.indexOf( "Mac" ) != -1 &&
			typeof document.body.leftMargin != "undefined" ) {
			offsetLeft += document.body.leftMargin;
			offsetTop += document.body.topMargin;
		}
		return {
			left: offsetLeft,
			top: offsetTop
		};
	}

	function reposicionaBallons() {
		var elementTop = 163;
		document.getElementById( 'ccm' ).style.marginTop = parseInt( getPosicaoElemento( 'dicaCCM' ).top ) - elementTop + 'px';
		document.getElementById( 'cnae' ).style.marginTop = parseInt( getPosicaoElemento( 'dicaCNAE' ).top ) - elementTop + 'px';
		document.getElementById( 'nire' ).style.marginTop = parseInt( getPosicaoElemento( 'dicaNire' ).top ) - elementTop + 'px';
		document.getElementById( 'prefeitura' ).style.marginTop = parseInt( getPosicaoElemento( 'dicaPrefeitura' ).top ) - elementTop + 'px';
		document.getElementById( 'tipo_emp' ).style.marginTop = parseInt( getPosicaoElemento( 'dicaTipo_Emp' ).top ) - elementTop + 'px';
	}
</script>
<?php
//$sql = "SELECT * FROM dados_da_empresa WHERE id='" . $_SESSION["id_userSecao"] . "' LIMIT 0, 1";
//$resultado = mysql_query($sql)
//or die (mysql_error());

//$linha=mysql_fetch_array($resultado);

function selected( $value, $prev ) {
	return $value == $prev ? ' selected="selected"' : '';
};




//$arrEstados = array();
//$sql = "SELECT * FROM estados ORDER BY sigla";
//$result = mysql_query($sql) or die(mysql_error());
//while($estados = mysql_fetch_array($result)){
//	array_push($arrEstados,array('id'=>$estados['id'],'sigla'=>$estados['sigla']));
//}


?>
<div class="principal">
	<div class="minHeight">

		<!--BALLOM Cadastrar Nova -->
		<div class="bubble only-box" style="display: none; padding:0; position:absolute; top: 50%; margin-top: -50px;width: 300px; left:50%; margin-left: -150px;" id="aviso-cadastrar-nova">
			<div style="padding:20px; font-size:12px;">
				<strong>Empresa <?=(isset($_GET['act']) && $_GET['act'] != '' ? 'cadastrada' : 'alterada')?> com sucesso!</strong><br>Deseja cadastrar uma nova empresa?<br><br>
				<div style="clear: both; margin: 0 auto; display: inline;">
					<center>
						<button id="btCadastrarNovaEmpresa" type="button">Sim</button>
						<button id="btGerenciarEmpresaCadastrada" type="button">Não</button>
					</center>
				</div>
				<div style="clear: both;"></div>
			</div>
		</div>
		<!--FIM DO BALLOOM Cadastrar Nova -->

		<!--BALLOM Empresa Cadastrada -->
		<div class="bubble only-box" style="display: none; padding:0; position:absolute; top: 50%; margin-top: -50px;width: 300px; left:50%; margin-left: -150px;" id="aviso-empresa-alterada">
			<div style="padding:20px; font-size:12px;">
				<center>Empresa alterada com sucesso!</center><br>
				<div style="clear: both; margin: 0 auto; display: inline;">
					<center>
						<button id="btFecharBallomEmpresaCadastrada" type="button">Ok</button>
					</center>
				</div>
				<div style="clear: both;"></div>
			</div>
		</div>
		<!--FIM DO BALLOOM Cadastrar Nova -->


		<!--BALLOM CNAE -->
		<div style="width:310px; position:absolute; display:none;" id="cnae" class="bubble_left box_visualizacao x_visualizacao">

			<div style="padding:20px;">
				<strong>CNAE</strong> é o código da atividade de sua empresa. No seu <a href="http://www.receita.fazenda.gov.br/pessoajuridica/cnpj/cnpjreva/cnpjreva_solicitacao.asp" target="_blank">Certificado do CNPJ</a>, os campos "Código e Descrição da Atividade Econômica Principal" e "Código e Descrição das Atividades Econômicas Secundárias", trazem os seus números do CNAE. <br/><br/>

				<span style="color:#cc0000">Cadastre-os neste formulário com atenção. São eles que definem as alíquotas dos impostos a serem pagos!</span><br/>
				<br/>
				<span style="font-size:10px"><strong>Obs:</strong> É  possível que sua empresa não tenha atividades secundárias, neste caso o campo deverá ficar em branco.</span>
			</div>

		</div>
		<!--FIM DO BALLOOM CNAE -->

		<!--BALLOM CCM -->
		<div style="width:310px; position:absolute; display:none;" id="ccm" class="bubble_left box_visualizacao x_visualizacao">

			<div style="padding:20px;">
				É o seu número de registro junto à Prefeitura. Para conhecê-lo, acesse sua <a href="https://www3.prefeitura.sp.gov.br/fdc/fdc_imp02_cgc.asp" target="_blank">Ficha de Dados Cadastrais</a>.
			</div>

		</div>
		<!--FIM DO BALLOOM CCM -->

		<!--BALLOM NIRE -->
		<div style="width:310px; position:absolute; display:none;" id="nire" class="bubble_left box_visualizacao x_visualizacao">

			<div style="padding:20px;">
				<strong>NIRE</strong> é o Número de Inscrição no Registro de Empresas, atribuído no momento em que sua empresa foi registrada pela primeira vez na Junta Comercial. <br/>
				<br/> Você pode encontrá-lo em seu contrato social ou, se for firma individual, em sua certidão de registro na Junta.
			</div>

		</div>
		<!--FIM DO BALLOOM NIRE -->

		<!--BALLOM PREFEITURA -->
		<div style="width:310px; position:absolute; display:none;" id="prefeitura" class="bubble_left box_visualizacao x_visualizacao">

			<div style="padding:20px;">
				O <strong>Código de Serviço</strong> está discriminado na <a href="https://www3.prefeitura.sp.gov.br/fdc/fdc_imp02_cgc.asp" target="_blank">Ficha de Dados Cadastrais - CCM</a> de sua empresa. Ele pode ser mais de um, de acordo com o número de atividades exercidas pelo seu estabelecimento. O Código de Serviço é tomado como base para calcular a TFE e TFA.
			</div>

		</div>
		<!--FIM DO BALLOOM PREFEITURA -->

		<!--BALLOM TIPO DE EMPRESA -->
		<div style="width:310px; position:absolute; display:none;" id="tipo_emp" class="bubble_left box_visualizacao x_visualizacao">

			<div style="padding:20px;">
				<strong>Tipo de Empresa</strong> - se você está na dúvida, consulte a cópia de seu registro na Junta Comercial (ou cartório). No caso de sociedades, verifique o Contrato Social.<br/><br/>A antiga Sociedade Civil Limitada, agora é denominada de Sociedade Simples. Já a Sociedade por Quotas de Responsabilidade Limitada, tornou-se Sociedade Empresária.<br/><br/>A Firma Individual passou a se chamar Empresa Individual. Foi criada também uma nova modalidade: a Empresa Individual de Responsabilidade Limitada (EIRELI).
			</div>

		</div>
		<!--FIM DO BALLOOM TIP ODE EMPRESA -->

		<div class="titulo" style="margin-bottom:20px">Meus Dados</div>

		<?
$mostrar_cadastrar_novo = false;

$acao = 'inserir';

$textoAcao = "- Incluir";

$arrEstados = array();
$sql = "SELECT * FROM estados ORDER BY sigla";
$result = mysql_query($sql) or die(mysql_error());
while($estados = mysql_fetch_array($result)){
	array_push($arrEstados,array('id'=>$estados['id'],'sigla'=>$estados['sigla']));
}


if($_GET['act'] != 'new' && $_GET["editar"] != ''){// CHECANDO SE NÃO É INCLUSAO 



	// PEGANDO DADOS DA EMPRESA SELECIONADA
	if( isset($_GET['editar']) )
		$sql = "SELECT * FROM dados_da_empresa WHERE id='" . $_GET["editar"] . "' LIMIT 0, 1";
	else
		$sql = "SELECT * FROM dados_da_empresa WHERE id='" . $_SESSION["id_empresaSecao"] . "' LIMIT 0, 1";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	
	$dadosEmpresa = mysql_fetch_array($resultado);
	$idEmpresa = $dadosEmpresa['id'];
	$mostrar_cadastrar_novo = true;
	
	if($idEmpresa){
		
		$textoAcao = "- Editar";
		$acao = 'editar';
	
		$id 																			= $dadosEmpresa["id"];
		$razao_social															= $dadosEmpresa["razao_social"];
		$nome_fantasia 														= $dadosEmpresa["nome_fantasia"];
		$cnpj																			= $dadosEmpresa["cnpj"];
		$inscricao_no_ccm 												= $dadosEmpresa["inscricao_no_ccm"];
		$inscricao_estadual												= $dadosEmpresa["inscricao_estadual"];
		$tipo_endereco														= $dadosEmpresa["tipo_endereco"];
		$endereco																	= $dadosEmpresa["endereco"];
		$numero																		= $dadosEmpresa["numero"];
		$complemento															= $dadosEmpresa["complemento"];
		$bairro 																	= $dadosEmpresa["bairro"];
		$cep																			= $dadosEmpresa["cep"];
		$cidade																		= $dadosEmpresa["cidade"];
//		if(strlen($rg)>0){
//			$rg = preg_replace("/\W/","",$rg);
//		}
		$estado																		= $dadosEmpresa["estado"];
		$pref_telefone														= $dadosEmpresa["pref_telefone"];	
		$telefone 																= $dadosEmpresa["telefone"];
		$ramo_de_atividade												= $dadosEmpresa["ramo_de_atividade"];
		$codigo_de_atividade_prefeitura						= $dadosEmpresa["codigo_de_atividade_prefeitura"];
		$regime_de_tributacao											= $dadosEmpresa["regime_de_tributacao"];	
		$inscrita_como														= $dadosEmpresa["inscrita_como"];	
		$registro_nire														= $dadosEmpresa["registro_nire"];
		$numero_cartorio													= $dadosEmpresa["numero_cartorio"];
		$registro_cartorio												= $dadosEmpresa["registro_cartorio"];
		
		$recolhe_cprb													= $dadosEmpresa["recolhe_cprb"];
		$registrado_em														= $dadosEmpresa["registrado_em"];
		
		$data_de_criacao													= ($dadosEmpresa["data_de_criacao"] != "0000-00-00" && $dadosEmpresa["data_de_criacao"] != "" && !is_null($dadosEmpresa["data_de_criacao"]) ? date("d/m/Y",strtotime($dadosEmpresa["data_de_criacao"])) : "");


	}
}
?>

		<div class="tituloVermelho" style="margin-bottom:10px">Cadastro de Empresa

			<?

if($_GET['act'] == 'new' || $_GET["editar"] || mysql_num_rows($resultado) == 1){ 

?>
			<?=$textoAcao?>
		</div>

		<form name="form_empresa" id="form_empresa" method="post" action="meus_dados_empresa_gravar.php">

			<input type="hidden" name="acao" value="<?=$acao?>"/>

			<table border="0" cellpadding="0" cellspacing="3" style="background:none" class="formTabela">
				<tr>
					<td align="right" valign="middle" class="formTabela">* Razão Social:</td>
					<td class="formTabela"><input name="txtRazaoSocial" id="txtRazaoSocial" type="text" style="width:300px; margin-bottom:0px" value="<?=$razao_social?>" maxlength="125" alt="Razão Social"/>
					</td>
				</tr>
				<tr>
					<td align="right" valign="middle" class="formTabela">Nome Fantasia:</td>
					<td class="formTabela"><input name="txtNomeFantasia" id="txtNomeFantasia" type="text" style="width:300px" value="<?=$nome_fantasia?>" maxlength="200" alt="Nome Fantasia"/>
					</td>
				</tr>
				<tr>
					<td align="right" valign="middle" class="formTabela">* CNPJ:</td>
					<td class="formTabela">
						<? if ($acao=='inserir') { ?>
						<input name="txtCNPJ" id="txtCNPJ" type="text" size="20" maxlength="18" class="campoCNPJ" value="<?=$cnpj;//str_replace(array("/ ","- ",". ")," ",$cnpj)?>" alt="CNPJ"/>
						<div name="divCNPJ" id="divCNPJ" style="display:none"></div>
						<? } else { ?>
						<input name="txtCNPJ" id="txtCNPJ" type="hidden" size="20" maxlength="18" class="campoCNPJ" value="<?=$cnpj;?>" alt="CNPJ"/>
						<?=$cnpj?>
						<? } ?>
					</td>
				</tr>

				<?php

				if ( $_GET[ 'act' ] != 'new' ) {

					//$sql2 = "SELECT t1.cnae,t2.denominacao FROM dados_da_empresa_codigos t1 INNER JOIN cnae t2 ON t1.cnae = t2.cnae WHERE t1.id='" . $_SESSION["id_userSecao"] . "' AND  t1.tipo='1' LIMIT 0,1";
					$sql2 = "SELECT t1.cnae,t2.denominacao FROM dados_da_empresa_codigos t1 INNER JOIN cnae t2 ON t1.cnae = t2.cnae WHERE t1.id='" . $id . "' AND  t1.tipo='1' LIMIT 0,1";
					$resultado2 = mysql_query( $sql2 )
					or die( mysql_error() );

					$linha2 = mysql_fetch_array( $resultado2 );
					?>
				<tr>
					<td align="right" valign="top" class="formTabela">* Atividade Principal (CNAE):</td>
					<td class="formTabela"> <input name="txtCNAE_Principal" id="txtCNAE_Principal" type="text" style="width:84px; float:left; margin-right:3px" value="<?=$linha2[" cnae "];?>" class="campoCNAE" div="atividadePrincipal" check="hddCNAEPrincipal" alt="Atividade Principal (CNAE)">
						<input type="hidden" name="hddCNAEPrincipal" id="hddCNAEPrincipal" value="<?=$linha2[" cnae "];?>">
						<div style="float:left; margin-right:5px; margin-top:5px">
							<img class="imagemDica" src="images/dica.gif" width="13" height="14" border="0" align="texttop" div="cnae"/>
						</div>
						<div id="atividadePrincipal" style="float:left; margin-top:5px; margin-bottom:-5px">
							<?=$linha2['denominacao']?>
						</div>
					</td>
					<?php
					//$sql2 = "SELECT t1.cnae,t2.denominacao FROM dados_da_empresa_codigos t1 INNER JOIN cnae t2 ON t1.cnae = t2.cnae WHERE t1.id='" . $_SESSION["id_userSecao"] . "' AND t1.tipo='2' ORDER BY t1.idCodigo ASC";
					$sql2 = "SELECT t1.cnae,t2.denominacao FROM dados_da_empresa_codigos t1 INNER JOIN cnae t2 ON t1.cnae = t2.cnae WHERE t1.id='" . $id . "' AND t1.tipo='2' ORDER BY t1.idCodigo ASC";
					$resultado2 = mysql_query( $sql2 )
					or die( mysql_error() );

					$totalResultados = mysql_num_rows( $resultado2 );
					?>
				</tr>
				<tr style="margin-top:-3px">
					<td align="right" valign="top" class="formTabela" style="padding-top:3px">Atividades Secundárias:</td>
					<td class="formTabela" valign="top">
						<div id="content">
							<?php 
			if ($totalResultados!="0") {
				$campo = 1;	
				while ($linha2=mysql_fetch_array($resultado2)) {
  ?>
							<div id="item<?=$campo?>">
								<div style="float:left; margin-right:5px">
									<input name="txtCodigoCNAE<?=$campo?>" id="txtCodigoCNAE<?=$campo?>" type="text" style="width:84px; margin-top:3px;" value="<?=$linha2[" cnae "]?>" class="campoCNAE" div="atividade<?=$campo?>" check="hddCodigoCNAE<?=$campo?>">
									<input type="hidden" name="hddCodigoCNAE<?=$campo?>" id="hddCodigoCNAE<?=$campo?>" value="<?=$linha2[" cnae "];?>">
								</div>
								<div id="atividade<?=$campo?>" style="float:left; margin-top:5px">
									<?=$linha2['denominacao']?>
								</div>
								<div style="clear:both"> </div>
							</div>
							<?php 
					$campo = $campo + 1;
				} 
			}else{
				
			}
	}else{
	?>

							<tr>
								<td align="right" valign="top" class="formTabela">* Atividade Principal (CNAE):</td>
								<td class="formTabela"> <input name="txtCNAE_Principal" id="txtCNAE_Principal" type="text" style="width:84px; float:left; margin-right:3px" value="" class="campoCNAE" div="atividadePrincipal" check="hddCNAEPrincipal" alt="Atividade Principal (CNAE)">
									<input type="hidden" name="hddCNAEPrincipal" id="hddCNAEPrincipal" value="">
									<div style="float:left; margin-right:5px; margin-top:5px">
										<img class="imagemDica" src="images/dica.gif" width="13" height="14" border="0" align="texttop" div="cnae"/>
									</div>
									<div id="atividadePrincipal" style="float:left; margin-top:5px; margin-bottom:-5px"></div>
								</td>
							</tr>
							<tr style="margin-top:-3px">
								<td align="right" valign="top" class="formTabela" style="padding-top:3px">Atividades Secundárias:</td>
								<td class="formTabela" valign="top">
									<div id="content">

										<div id="item1">
											<div style="float:left; margin-right:5px">
												<input name="txtCodigoCNAE1" id="txtCodigoCNAE1" type="text" style="width:84px; margin-top:3px;" value="" class="campoCNAE" div="atividade1" check="hddCodigoCNAE1">
												<input type="hidden" name="hddCodigoCNAE1" id="hddCodigoCNAE1" value="1">
											</div>
											<div id="atividade1" style="float:left; margin-top:5px"></div>
											<div style="clear:both"> </div>
										</div>

										<?
		$totalResultados = 0;
	}
  ?>
									</div>
									<a href="#" class="btAdicionaCNAE">Adicionar</a> | <a href="#" class="btRemoveCNAE">Remover</a>

									<!--o valor do campo count depende da variavel totalresultados, que é o numero de linhas cnae no banco de dados. Se for zero, deve ser um, de forma a gerar 1 campo de input para ser preenchido pelo usuario-->
									<input type="hidden" id="count" name="skill_count" value="<?php echo ($totalResultados == " 0 " ? "1 " : $totalResultados); //if ($totalResultados=="0 ") {echo "0 ";} else {echo $totalResultados + 1;} ?>">
								</td>
							</tr>
							<tr>
								<td align="right" valign="middle" class="formTabela">Inscri&ccedil;&atilde;o municipal:</td>
								<td class="formTabela"><input name="txtInscricaoCCM" id="txtInscricaoCCM" type="text" style="width:95px; float:left; margin-right:3px" value="<?=$inscricao_no_ccm?>" maxlength="11" class="inteiro"/>
									<div style="float:left; margin-right:5px; margin-top:5px; display: none;"><a href="javascript:abreDiv('ccm');reposicionaBallons()"><img src="images/dica.gif" width="13" height="14" border="0" align="texttop" id="dicaCCM" /></a>
									</div>
								</td>
							</tr>
							<tr>
								<td align="right" valign="middle" class="formTabela">Inscri&ccedil;&atilde;o estadual:</td>
								<td class="formTabela"><input name="txtInscricaoEstadual" id="txtInscricaoEstadual" type="text" style="width:95px; float:left; margin-right:3px" value="<?=($inscricao_estadual != '' ? $inscricao_estadual : 'isento')?>" maxlength="20"/>
									<div style="float:left; margin-right:5px; margin-top:5px; display: none;"><a href="javascript:abreDiv('ccm');reposicionaBallons()"><img src="images/dica.gif" width="13" height="14" border="0" align="texttop" id="dicaCCM" /></a>
									</div>
								</td>
							</tr>
							<tr>
								<td align="right" valign="middle" class="formTabela">* Endere&ccedil;o:</td>
								<td class="formTabela"><input name="txtEndereco" id="txtEndereco" type="text" style="width:300px" value="<?=$endereco?>" maxlength="200" alt="Endereço"/>
								</td>
							</tr>
							<tr>
								<td align="right" valign="middle" class="formTabela">* Bairro:</td>
								<td class="formTabela"><input name="txtBairro" id="txtBairro" type="text" style="width:300px" value="<?=$bairro?>" maxlength="200" alt="Bairro"/>
								</td>
							</tr>
							<tr>
								<td align="right" valign="middle" class="formTabela">* CEP:</td>
								<td class="formTabela"><input name="txtCEP" id="txtCEP" type="text" style="width:80px" value="<?=$cep;//str_replace(array("/ ","- ",". ")," ",$cep)?>" maxlength="9" alt="CEP" class="campoCEP"/>
									<span style="font-size:10px; display: none">(somente números)</span>
								</td>
							</tr>
							<tr>
								<td align="right" valign="middle" class="formTabela">* Estado:</td>
								<td class="formTabela">
									<select name="selEstado" id="selEstado" alt="Estado">
										<option value="" <?php echo selected( '',$estado ); ?>></option>
										<?
        
              foreach($arrEstados as $dadosEstado){
         				echo "<option value=\"".$dadosEstado['id'].";".$dadosEstado['sigla']."\" " . selected( $dadosEstado['sigla'], $estado ) . " >".$dadosEstado['sigla']."</option>";
								if($dadosEstado['sigla'] == $estado){
									$idEstadoSelecionado = $dadosEstado['id'];
								}
              }
  
  ?>
									</select>
								</td>
							</tr>
							<tr>
								<td align="right" valign="middle" class="formTabela">* Cidade:</td>
								<td class="formTabela">
									<!--<input name="txtCidade" id="txtCidade" type="text" style="width:300px" value="<?=$cidade?>" maxlength="200" alt="Cidade" />-->
									<select name="txtCidade" id="txtCidade" style="width:300px" class="comboM" alt="Cidade">
										<option value="" <?php echo selected( '', $cidade ); ?>></option>
										<?
        if($idEstadoSelecionado != ''){
          $sql = "SELECT * FROM cidades WHERE id_uf = '" . $idEstadoSelecionado . "' ORDER BY cidade";
          $result = mysql_query($sql) or die(mysql_error());
          while($cidades = mysql_fetch_array($result)){
            echo "<option value=\"".$cidades['cidade']."\" " . selected( $cidades['cidade'], $cidade) . " >".$cidades['cidade']."</option>";
          }
        }
              ?>
									</select>
								</td>
							</tr>
							<tr>
								<td align="right" valign="middle" class="formTabela">Telefone:</td>
								<td valign="middle" class="formTabela">
									<div style="float:left; margin-right: 3px;"><input name="txtPrefixoTelefone" id="txtPrefixoTelefone" type="text" style="width:30px" value="<?=$pref_telefone?>" maxlength="2" class="inteiro"/>
									</div>
									<div style="float:left"><input name="txtTelefone" id="txtTelefone" type="text" style="width:75px" value="<?=$telefone?>" maxlength="9" class="inteiro"/>
									</div>
								</td>
							</tr>
							<tr>
								<td align="right" valign="middle" class="formTabela">Ramo de Atividade:</td>
								<td class="formTabela">
									<select name="selRamoAtividade" id="selRamoAtividade">
										<option value="Prestação de Serviços" <?php echo selected( 'Prestação de Serviços', $ramo_de_atividade ); ?>>Prestação de Serviços</option>
										<option value="Comércio" onClick="javascript:alert(msg3)" <?php echo selected( 'Comércio', $ramo_de_atividade ); ?> >Comércio</option>
										<option value="Indústria" onClick="javascript:alert(msg3)" <?php echo selected( 'Indústria', $ramo_de_atividade ); ?> >Indústria</option>
									</select>
								</td>
							</tr>

							<tr>
								<td align="right" valign="top" class="formTabela">Regime de Tributação:</td>
								<td class="formTabela">
									<select name="selRegimeTributacao" id="selRegimeTributacao">
										<option value="Simples" <?php echo selected( 'Simples', $regime_de_tributacao ); ?> >Simples</option>
										<option value="Lucro Presumido" onClick="javascript:alert(msg4)" <?php echo selected( 'Lucro Presumido', $regime_de_tributacao ); ?> >Lucro Presumido</option>
									</select>
								</td>
							</tr>
							<tr>
								<td align="right" valign="top" class="formTabela">Tipo de empresa:</td>
								<td class="formTabela">
									<select name="selInscritaComo" id="selInscritaComo" style="float:left">
									
										<option value="Empresa Individual" 
										<?php echo selected( 'Empresa Individual', $inscrita_como ); ?>  >Empresário Individual
										</option>
										
										<option value="Empresa Individual de Responsabilidade Limitada (EIRELI)" 
										<?php echo selected( 'Empresa Individual de Responsabilidade Limitada (EIRELI)', $inscrita_como ); ?>  >
										Empresa Individual de Responsabilidade Limitada (EIRELI)
										</option>
										
										<option value="Sociedade Empresária" 
										<?php echo selected( 'Sociedade Empresária', $inscrita_como ); ?> ) >
										Sociedade Empresária
										</option>
										
										<option value="Sociedade Simples" 
										<?php echo selected( 'Sociedade Simples', $inscrita_como ); ?> ) >
										Sociedade Simples
										</option>
									</select>
									
								</td>
							</tr>

							<tr>
								<td align="right" valign="middle" class="formTabela">* Data de Cria&ccedil;&atilde;o:</td>
								<td class="formTabela"><input name="txtDataCriacao" id="txtDataCriacao" type="text" style="width:75px" value="<?=$data_de_criacao?>" maxlength="10" class="campoData"/>
									<span style="font-size:10px; display: none">DD/MM/AAAA</span>
								</td>
							</tr>

							<tr>
							<td align="right" valign="middle" class="formTabela"> Local de registro:</td>
								<td class="formTabela">
									<select name="registrado_em">
										<option value="">Selecione</option>
							<option value="1" <?php echo selected( '1', $registrado_em ); ?>  onchange="selecionaRegistro()" >1</option>
							<option value="2" <?php echo selected( '2', $registrado_em ); ?>  onchange="selecionaRegistro()" >2</option>
									</select></td>

							</tr>
								<tr>
								<td colspan="2">
									<!--pedir dados do registro no cartório se cliente selecionaou cartório-->
									
									<table id="divRegistroCartorio" border="0" cellpadding="0" cellspacing="3" style="background:none; display:none" class="formTabela">
										<tr>
											<td align="right" valign="middle" class="formTabela" width="196px">Cartório nº:</td>
											<td class="formTabela"><input name="txtNumCartorio" id="txtNumCartorio" type="text" style="width:55px; float:left; margin-right:3px" value="<?=$numero_cartorio?>" maxlength="7" alt="Número do Cartório"/> </td>
										</tr>
										<tr>
											<td align="right" valign="middle" class="formTabela">Nº de registro:</td>
											<td class="formTabela"><input name="txtRegistroCartorio" id="txtRegistroCartorio" type="text" style="width:150px; float:left; margin-right:3px" value="<?=$registro_cartorio?>" maxlength="20" alt="Número de registro"/>
											</td>
										</tr>
									</table>
									
									<!--fim dos dados do cartório-->
									
									<!--inicio dados nire-->
								
								<table id="divRegistroNire" border="0" cellpadding="0" cellspacing="3" style="background:none; display:none" class="formTabela">
										<tr>
															
											<td align="right" valign="middle" class="formTabela" width="196px">Registro NIRE:</td>
											
											<td class="formTabela"><input name="txtRegistroNire" id="txtRegistroNire" type="text" style="width:100px; float:left; margin-right:3px" value="<?=$registro_nire;//str_replace(array("/ ","- ",". ")," ",$registro_nire)?>" maxlength="13" alt="NIRE"/>
											
												<div style="float:left; margin-right:5px; margin-top:5px">
													<img class="imagemDica" src="images/dica.gif" width="13" height="14" border="0" align="texttop" div="nire"/>
												</div>
											</td>
							
										</tr>
									</table>
								<!--fim dados nire-->
								
								</td>
							</tr>
							<tr>
								<td align="right" valign="middle" class="formTabela"> Recolhe CPRB:</td>
								<td class="formTabela">
									<input type="radio" name="txt_recolhe_cprb" value="1" <?=($recolhe_cprb==1 ? 'checked' : '')?> />Sim&nbsp;&nbsp;
									<input type="radio" name="txt_recolhe_cprb" value="0" <?=($recolhe_cprb==0 ? 'checked' : '')?> />Não
								</td>
							</tr>
							<tr>
								<td colspan="2" valign="middle" class="formTabela">
									&nbsp;<input type="hidden" name="hidID" id="hidID" value="<?=$id?>"/>
									<? if(isset($_GET['act']) && $_GET['act'] == 'new'){ ?>
									<div id="linhaAlertaMensalidade" style="display: none; clear: both; margin-bottom: 20px;">
										<input type="checkbox" name="chkAvisoMensalidade" id="chkAvisoMensalidade" value="1"> <label for="chkAvisoMensalidade">Estou ciente que o cadastramento de uma nova empresa corresponderá a um acréscimo de R$ <?=number_format($mensalidade_unitaria,2,',','.')?> na minha mensalidade.</label>
									</div>
									<? } ?>
								</td>
							</tr>


							<tr>
								<td align="right" valign="middle" class="formTabela">&nbsp;</td>
								<td class="formTabela">
									<input type="button" value="Salvar<?=($_GET['act'] != 'new' ? ' Alterações' : '')?>" id="btSalvar" style="margin-right:5px;float:left"/>
									<div class="divCarregando2" style="margin-top: 10px;text-align: center;display: none;float: left;margin-right: 20px;margin-top: 5px;"><img src="images/loading.gif" width="16" height="16">
									</div>
									<input type="button" value="<?=isset($_GET[" editar "]) ? "Voltar " : "Cancelar "?>" id="btCancelar"/>
								</td>
							</tr>
			</table>
		</form>
		<br/>

		<?
  if($mostrar_cadastrar_novo){
  ?>
		<div style="text-align: left; margin-bottom:10px; width:75%"><a href="meus_dados_empresa.php?act=new">Cadastrar nova Empresa</a>
		</div>
		<?	
  }
  




} else {
	// LISTAGEM
	
	$sql = "SELECT emp.id, razao_social, cnpj, nome_fantasia, ativa, data_desativacao, l.data_inclusao FROM dados_da_empresa emp INNER JOIN login l ON emp.id = l.id  WHERE l.idUsuarioPai = '" . $_SESSION["id_userSecaoMultiplo"] . "' ORDER BY ativa DESC, razao_social";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	
?>
		</div>

		<div style="text-align: right; margin-bottom:10px; width:99.8%"><a href="meus_dados_empresa.php?act=new">Cadastrar <?=$_SESSION['n_empresasVinculadas'] > 0 ? 'outra' : ''?> empresa</a>
		</div>
		<table width="100%" cellpadding="5">
			<tr>
				<th style="width: 7%;" align="center">Alterar</th>
				<th style="width: 33%;" align="left">Razão Social</th>
				<th style="width: 23%;" align="left">Nome Fantasia</th>
				<th style="width: 14%;">CNPJ</th>
				<th style="width: 9%;" align="center">Inclusão</th>
				<th style="width: 7%;" align="center">Status</th>
				<th style="width: 7%;" align="center">Entrar</th>
			</tr>
			<?

	if(mysql_num_rows($resultado) > 0){
		
		$esconde_botao_excluir = false;
		if(mysql_num_rows($resultado) == 1){ $esconde_botao_excluir = true;}
		
		// TRAZENDO OS DADOS PARA MONTAR TABELA DE AUTONOMOS
		while($linha=mysql_fetch_array($resultado)){
			$id 							= $linha["id"];
			$razao_social			= $linha["razao_social"];
			$nome_fantasia		= $linha["nome_fantasia"];
			$cnpj 						= $linha["cnpj"];
			$dtInclusao				= $linha["data_inclusao"];
			$ativa						= $linha["ativa"];
			$data_desativacao	= $linha["data_desativacao"];
			if($data_desativacao != '' && $data_desativacao != '0000-00-00'){
				$diffData = (strtotime(date('Y-m-d')) - strtotime(date('Y-m-d', strtotime($data_desativacao))));
				$dias_empresa_desativada = $diffData / 86400;
			}
//			echo '<tr><td colspan="5">'.$dias_empresa_desativada.'</td></tr>';
?>
			<tr>
				<td class="td_calendario" align="center">
					<? if($ativa == 1){ ?>
					<a href="meus_dados_empresa.php?editar=<?=$id?>">
                    	<i class="fa fa-pencil-square-o iconesAzuis iconesGrd"></i>
                  		<!--<img src="images/edit.png" width="24" height="23" border="0" title="Editar" />-->
                    </a>
				
					<? } else { ?>
					<i class="fa fa-pencil-square-o iconesCinzaEscuro iconesGrd"></i>
					<? } ?>
				</td>
				<td class="td_calendario">
					<?=$razao_social?>
				</td>
				<td class="td_calendario">
					<?=$nome_fantasia?>
				</td>
				<td class="td_calendario" align="center">
					<?=$cnpj?>
				</td>
				<td class="td_calendario" align="center">
					<?=(date('Y',strtotime($dtInclusao)) > 0 ? date('d/m/Y',strtotime($dtInclusao)) : 'NA')?>
				</td>
				<td class="td_calendario" align="center">
					<? if($ativa == 1){ ?>
					<a href="#" onClick="if (confirm('Tem certeza de que deseja desativar esta empresa? A reativação só poderá ser feita em 30 dias.'))location.href='meus_dados_empresa_excluir.php?empresa=<?=$id?>';" alt="DESATIVAR EMPRESA" title="DESATIVAR">
                      	<i class="fa fa-circle iconesVerdes iconesPeq"></i> 
                      </a>
				
					<? } else { ?>
					<? if(isset($dias_empresa_desativada) && $dias_empresa_desativada < 29){ ?>
					<a href="#" onClick="alert('Uma empresa só pode ser reativada após 30 dias da sua desativação. Em caso de urgência, contate nosso Help Desk.');" alt="ATIVAR EMPRESA" title="ATIVAR">
                        	<i class="fa fa-circle iconesVermelhos iconesPeq"></i> 
                        </a>
				
					<? } else { ?>
					<a href="#" onClick="if (confirm('Você tem certeza que deseja reativar esta Empresa?'))location.href='meus_dados_empresa_ativar.php?empresa=<?=$id?>';" alt="ATIVAR EMPRESA" title="ATIVAR">
                        	<i class="fa fa-circle iconesVermelhos iconesPeq"></i> 
                        </a>
				
					<? } ?>
					<? } ?>


					<? //($ativa == 1 ? 'ativa' : 'inativa')?>
				</td>
				<td class="td_calendario" align="center">
					<? if($ativa == 1){ ?>
					<a href="meus_dados_empresa_gerenciar.php?id=<?=$id?>"><i class="fa fa-angle-double-right iconesAzuis iconesMed"></i></a>
					<? } else {?>
					<i class="fa fa-angle-double-right iconesCinzaEscuro iconesMed"></i>
					<? } ?>
				</td>
			</tr>
			<?	
		}

	}else{
?>
			<tr>
				<td class="td_calendario">&nbsp;</td>
				<td class="td_calendario">&nbsp;</td>
				<td class="td_calendario">&nbsp;</td>
				<td class="td_calendario">&nbsp;</td>
				<td class="td_calendario">&nbsp;</td>
				<td class="td_calendario">&nbsp;</td>
				<td class="td_calendario">&nbsp;</td>
			</tr>
			<?		
	}
}
?>

		</table>

	</div>
</div>

<script>
	< ?
	if ( ( $_SESSION[ "aviso" ] != '' ) ) {
		echo "alert('".$_SESSION[ "aviso" ].
		"');";
		$_SESSION[ "aviso" ] = "";
	}


	?
	>
</script>
<?php include 'rodape.php' ?>