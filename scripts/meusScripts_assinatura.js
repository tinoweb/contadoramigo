jQuery(document).ready(function() {		

	$('#btProsseguir').click(function(){
		$.ajax({
		  url:'assinatura_checa_dados.php',
		  data: 'cnpj=' + $('#txtCNPJ').val() + '&email=' + $('#txtEmail').val(),
		  type: 'get',
		  async: false,
		  beforeSend: function(){
			$("#hidPassCNPJ").val('');
			$("#hidPass").val('');
		  },
		  success: function(retorno){
			$('#hidPassCNPJ').val(retorno.split("|")[0]);
			if(retorno.split("|")[0] > 0){
				$('#txtCNPJ').focus();
				alert('O CNPJ já está cadastrado em nosso sistema.');
				return false;
			}
			$('#hidPass').val(retorno.split("|")[1]);
			if(retorno.split("|")[1] > 0){
				$('#txtEmail').focus();
				alert('O E-mail já está cadastrado em nosso sistema.');
				return false;
			}
			
			
/*				alert($('#hidPassCNPJ').val());
				alert($('#hidPass').val());*/


			formSubmit();
		  }
		});
	});

/*
	$('#txtCNPJ').blur(function(){
		if($(this).val()!=''){
			$.ajax({
			  url:'assinatura_checa_cnpj.php',
			  data: 'cnpj='+$(this).val(), 
			  type: 'get',
			  async: false,
			  beforeSend: function(){
				$("#hidPassCNPJ").val('');
			  },
			  success: function(retorno){
				$("#hidPassCNPJ").val(retorno);
				if(retorno > 0){
					$('#txtCNPJ').focus();
					alert('O CNPJ já está cadastrado em nosso sistema.');
					return false;
				}
			  }
			});
		}
	});
	
	
	$('#txtEmail').blur(function(){
		if($(this).val()!=''){
			$.ajax({
			  url:'assinatura_checa_email.php',
			  data: 'email=' + $('#txtEmail').val(), 
			  type: 'get',
			  async: false,
			  beforeSend: function(){
				$("#hidPass").val('');
			  },
			  success: function(retorno){
				$("#hidPass").val(retorno);
				if(retorno>0){
					$('#txtEmail').focus();
					alert('O E-mail já está cadastrado em nosso sistema.');
					return false;
				}
			  }
			});
		}
	});
*/

});


 var msg1 = 'É necessário preencher o campo';
 var msg2 = 'É necessário selecionar ';
 var msg3 = 'No momento o Contador Amigo não oferece suporte para empresas do ramo de Comércio e Indústria.';
 var msg4 = 'No momento o Contador Amigo não oferece suporte para empresas com Lucro Presumido.';


function consultaEmailExistente(){
	if(document.getElementById('txtEmail').value != '' && document.getElementById('txtSenha').value != '') {
		consultaBanco('assinatura_checa_email.php?email=' + document.getElementById('txtEmail').value + '&senha=' + document.getElementById('txtSenha').value, 'divPass');
	}
}

function consultaCNPJExistente(){
	if(document.getElementById('txtCNPJ').value != '') {
		consultaBanco('assinatura_checa_cnpj.php?cnpj=' + document.getElementById('txtCNPJ').value, 'divPassCNPJ');
	}
}

function fnValidaEmail(email){

        var v_email = email.value;
        var jSintaxe;
        var jArroba;
        var jPontos;

	var ExpReg = new RegExp('[^a-zA-Z0-9\.@_-]', 'g');

        jSintaxe = !ExpReg.test(v_email);
	if(jSintaxe == false){
            window.alert('Favor digitar o e-mail corretamente!');
            return false;
	}
	jPontos = (v_email.indexOf('.') > 0) && !(v_email.indexOf('..') > 0);
	if (jPontos == false){
            window.alert('Favor digitar o e-mail corretamente!');
            return false;
	}
	jArroba = (v_email.indexOf('@') > 0) && (v_email.indexOf('@') == v_email.lastIndexOf('@'));
	if (jArroba == false){
            window.alert('Favor digitar o e-mail corretamente!');
            return false;
	}

        return true;

}

function valida_cnpj(cnpj) {
	exp = /\d{14}/
	if(!exp.test(cnpj))
	return false;
}  

function ValidaCep(cep){
	exp = /\d{8}/
	if(!exp.test(cep))
	return false; 
}

function ValidaDataValidade(DataValidade){
	exp = /\d{2}\/\d{4}/
	if(!exp.test(DataValidade))
	return false; 
}

 function validElement(idElement, msg){

                var Element= document.getElementById(idElement);

                if(Element.value == "" || Element.value == false ){
                    window.alert(msg+' '+Element.alt+'.');
                    return false;
                }
            }

 function formSubmit(){ 
			if( validElement('txtRazaoSocial', msg1) == false){return false}
            if( validElement('txtCNPJ', msg1) == false){return false}
/*
			if(document.getElementById('txtCNPJ').value.length != 18){
				alert('Digite o CNPJ corretamente.'); 
				return false;
			}
			if (valida_cnpj(document.getElementById('txtCNPJ').value) == false){
				alert('Digite o CNPJ somente com números.'); 
				return false;
			}
*/
			if(document.getElementById('hidPassCNPJ').value == "erro") {
				alert('O CNPJ já está cadastrado em nosso sistema.');
				return false;
			} 
			if( validElement('txtEndereco', msg1) == false){return false}
            if( validElement('txtCEP', msg1) == false){return false}
/*
			if(document.getElementById('txtCEP').value.length != 9){
				alert('Digite o CEP corretamente.'); 
				return false;
			}
			if (ValidaCep(document.getElementById('txtCEP').value) == false){
				alert('Digite o CEP somente com números.'); 
				return false;
			}
*/
			if( validElement('txtCidade', msg1) == false){return false}
			if( validElement('txtCidade', msg1) == false){return false}
			var Cidade = document.getElementById('txtCidade');
			if(Cidade.value.toLowerCase() == 'são paulo' || Cidade.value.toLowerCase() == 'sao paulo') {
				Cidade.value = 'São Paulo';
			}
			var Estado = document.getElementById('selEstado');
            if(Estado.selectedIndex == ""){
                window.alert(msg2+'o Estado em que se localiza sua empresa.');
                return false;
            }
			
			var RamoAtividade = document.getElementById('selRamoAtividade');
            /*if(RamoAtividade.selectedIndex == ""){
                window.alert(msg2+'Ramo de Atividade de sua empresa.');
                return false
            }*/
            if(RamoAtividade.value == "Comércio" || RamoAtividade.value == "Indústria"){
                window.alert(msg3);
                return false;
            }
			var RegimeTributacao = document.getElementById('selRegimeTributacao');
			/*if(RegimeTributacao.selectedIndex == ""){
                window.alert(msg2+'o tipo de Regime de tributação.');
                return false
            }*/
            if(RegimeTributacao.value == "Lucro Presumido"){
                window.alert(msg4);
                return false;
            }
			/*var InscritaComo = document.getElementById('selInscritaComo');
            if(InscritaComo.selectedIndex == ""){
                window.alert(msg2+'como sua empresa está Inscrita.');
                return false
            }*/
            if( validElement('txtAssinante', msg1) == false){return false}
            if( validElement('txtEmail', msg1)== false){
                return false;
            } else {
               var email = document.getElementById('txtEmail');
                if(fnValidaEmail(email) == false){
                    return false;
                }
            }

            if( validElement('txtSenha', msg1) == false){return false}
			if(document.getElementById('txtSenha').value.length < 8) {
				window.alert('A senha deve ter no mínimo 8 caracteres.');
                return false;
			}
            if( validElement('txtConfirmaSenha', msg1) == false){return false}
			var Senha = document.getElementById('txtSenha').value;
			var ConfirmaSenha = document.getElementById('txtConfirmaSenha').value;
			if(Senha != ConfirmaSenha) {
				window.alert('As senhas não comferem.');
                return false;
			}
//			consultaEmailExistente();
			if(document.getElementById('hidPass').value == "erro") {
//				alert('O e-mail e a senha já estão cadastrados em nosso sistema.');
				alert('O e-mail já está cadastrado em nosso sistema.');
				return false;
			} 
			if( validElement('txtPrefixoTelefoneCobranca', msg1) == false){return false}
            if( validElement('txtTelefoneCobranca', msg1) == false){return false}
			if(document.getElementById('cheTermos').checked == false) {
				window.alert('É necessário concordar com termos e condições de serviço.');
               	return false;
			}
		
			document.getElementById('frmAssinatura').submit()
 }