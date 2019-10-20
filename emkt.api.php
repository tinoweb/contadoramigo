<?php include 'header_restrita.php' ?>
<?php include 'emkt.api.class.php' ?>

<div class="principal">

	<?php 

		$emkt = new APi_EMKT();


		// //Pega os demos inscritos  a mais de 7 dias para enviar um email
		// $consulta_demo_info = mysql_query("SELECT *,DATEDIFF(data_pagamento, DATE(now())) from historico_cobranca WHERE DATEDIFF(data_pagamento, DATE(now())) <= -15 group by id");
		// $contatos = array();
		// while ( $objeto_demo_info = mysql_fetch_array($consulta_demo_info) ) {
			
		// 	$consulta_email_login = mysql_query("SELECT * FROM login WHERE status = 'demoInativo' AND idUsuarioPai = id AND idUsuarioPai = '".$objeto_demo_info['id']."' LIMIT 1");
		// 	$objeto_email_login = mysql_fetch_array($consulta_email_login);

		// 	if( $objeto_email_login['email'] != "" ){

		// 		// $consulta_email_enviado = mysql_query("SELECT * FROM envio_emails_cobranca WHERE tipo_mensagem = 'demo_info_15' AND email = '".$objeto_email_login['email']."' ");
		// 		// $objeto_email_enviado = mysql_fetch_array($consulta_email_enviado);

		// 		// if( $objeto_email_enviado['email'] == "" )
		// 		// echo $objeto_email_login['id'].'<br>';
		// 			// mysql_query("INSERT INTO envio_emails_cobranca SET tipo_mensagem = 'demo_info_15', nome = '".$objeto_email_login['assinante']."', email = '" . $objeto_email_login['email'] . "', status = 0, data = '" . date("Y-m-d H:i:s") . "'");
		// 		if ( filter_var(strtolower($objeto_email_login['email']), FILTER_VALIDATE_EMAIL)) {
		// 			$contatos[] = strtolower($objeto_email_login['email']);
		// 			// echo '<br>';
		// 		}
				


		// 	}

			

		// }
		// $emkt->addContatoLista($contatos,418);

		// echo 'aki';
		// $emkt->getIdLista("Total Demos");

		// $array_teste = array("demo_info");

		// echo $emkt->inserirContatoEMKTsemCadastro("testenovo@email.com.br","teste novo",5);

		##########################################################################################################################################
		// // INSERE UM CONTADO	

		// $consulta = mysql_query("SELECT * FROM login WHERE id = idUsuarioPai ");
		// while( $objeto=mysql_fetch_array($consulta))
			// echo $emkt->inserirContatoEMKT($objeto['id'],5); // function inserirContatoEMKT($id_user,$id_lista)
		##########################################################################################################################################

		##########################################################################################################################################
		// RESETA AS LISTA CRIADAS NA COBRANÇA
		// $emkt->resetarLista('demo_inativo');
		// // RESETA AS LISTA CRIADAS PARA MANTER OS CLIENTES DE ACORDO COM OS STATUS
		// $emkt->resetarListasGeral();
		##########################################################################################################################################

		// ##########################################################################################################################################
		// $tipo_mensagem = "demo_inativo";			
		// // Criar uma lista no Emkt
		// $id_da_lista = $emkt->criarListaEMKT("Comércio e indústria",""); // function criarListaEMKT($nome,$descricao)
		// ##########################################################################################################################################

		// ##########################################################################################################################################
		//Insere um array de contatos em uma lista 		
		$contatos = array('acdfelice@uol.com.br','adrianomlima78@gmail.com','airpotencia@gmail.com','albrancalion@outlook.com','alexandre.veiga@gmail.com','alexandre@integraal.com.br','algidra2@gmail.com','aline.and3@gmail.com','alissonprojetoti@gmail.com','alvaro.ferreira65@bol.com.br','amandanavarro@uol.com.br','ana@aavatec.com.br','annevelize@gmail.com','arq.csn@gmail.com','asmpi@hotmail.com','at39@bol.com.br','atendimento@miner-mi.com','atrios.cobranca@gmail.com','brztransporte@gmail.com','b_macaes@jhu.edu','calisto@mcsinformatica.net.br','caracolfeliz@hotmail.com','carlos.valerio@movinfo.com.br','carolbohlhalter@hotmail.com','cdbartz@gmail.com','cenouracomchocolate@gmail.com','cezar@usina82.com.br','chicobela@gmail.com','christiane.frascino@aasp.org.br','claudio@bitclick.com.br','claumareletro@gmail.com.br','comercial@filmeparaembalagem.com.br','conexaomarques@hotmail.com','contadorteixeira@gmail.com','contato@brunocasado.me','contato@equipedoispraca.com.br','crismonico@gmail.com','cristina.savi@advnew.com.br','daniel.picc@uol.com.br','danielle@duopet.com.br','dbiacsi@uol.com.br','dhyanamai@gmail.com','ducore@terra.com.br','edalagnol@gmail.com','eduardo@bcsa.com.br','elena.murata@uol.com.br','elispar@gmail.com','ellen.simoes@sevenplus.com.br','erictti@me.com','estevam.leal@gmail.com','estudio@aidacass.com.br','estudio@rlmix.com.br','fabiana@agenciapage.com','fabioschall@gmail.com','fabiot.kamio@gmail.com','felipe.ead@gmail.com','ferna98@uol.com.br','ferrazpena@gmail.com','fioretto@uol.com.br','flaviinha@gmail.com','fornari2006@hotmail.com','gilberto@gilbertoandrade.adv.br','gpadlipskas@gmail.com','hernando.almeida@yahoo.com.br','isano@ig.com.br','jackieoliveira.jso@gmail.com','japamunck@gmail.com','jfernandoandrade@uol.com.br','jhenrique5x@gmail.com','jorge@poweruser.com.br','jpaulo@netway.com.br','kenickel2009@hotmail.com','koike.rafael@gmail.com','langedebarros@gmail.com','lcvspa@gmail.com','levberni@hotmail.com','lhmiyada@gmail.com','maguer@uol.com.br','marcello.yesca@gmail.com','marcos-gon@uol.com.br','marcos.eml@gmail.com','marcuscarloscunha@gmail.com','marlosb@hotmail.com','meire@wsptelecom.com.br','mfregon@gmail.com','nely.bacic@gmail.com','odnanref.usp@gmail.com','pabloblanc10@hotmail.com','patricia_komaba@hotmail.com','pedro@comvoce.net','pedrolalbertin@gmail.com','pleblanc421@gmail.com','rafaelacasagrande@gmail.com','rafaelbee@gmail.com','redantasdesign@gmail.com','regiane.samartin@gmail.com','renan.melhado@gmail.com','renatapetta@uol.com.br','rguerrero@uol.com.br','ricardo.magalhaes@ramtecno.com.br','ricardo@bmove.com.br','riccieri@me.com','rodrigo.ikegaya@live.com','rodrigosillos@gmail.com','rosabicker@gmail.com','rosana@superez.com.br','sdarmani@gmail.com','sergio.duque@audatec.com.br','silvia.silvalima@gmail.com','takahashi.av@gmail.com','thaisviv@gmail.com','turntablesproducao@gmail.com','vanessa.beck@gmail.com','vanysbs@gmail.com','veiga.richard@gmail.com','vitor@vad.com.br','waltecir.lopes@uol.com.br','wilderson.cotrim@hotmail.com','WILTON@WPGESTAO.COM.BR','zacabreda@hotmail.com'); // function addContatoLista($contatos,$id_lista)
		$emkt->addContatoLista($contatos,849);

		$contatos = array('adm@tecvisual.com.br','agnaldo.novais@ig.com.br','aletofalo@gmail.com','avsneto68@gmail.com','cas.eletrica@gmail.com','cauecopiadora@hotmail.com','cidranef@gmail.com','contab.kleya@gmail.com','craiaz@hotmail.com','cristiano@criptomail.com.br','dalvg@hotmail.com','danielrahal@yahoo.com.br','danilo@voltlogistica.com.br','eletricidade1000volts@gmail.com','elpercino@gmail.com','fernandowiek@gmail.com','financeiro@arvita.com.br','financeiro@bertonesauto.com.br','givaldosp2007@gmail.com','hana@hanaelsamira.com.br','helengarcia@gmail.com','jlpaisagismome@hotmail.com','karina.cordeiro@icloud.com','lionbrinquedos@hotmail.com','logistica@loartransportes.com.br','lucianofrancisco@adv.oabsp.org.br','mariana.teruya@yahoo.com.br','mergulhao.pamei@gmail.com','mrcavalcanti1@hotmail.com','mrinteriores@uol.com.br','pauloivosp@hotmail.com','rosenifarias@ig.com.br','rubens@mercuriotecnologia.com.br','sac@fvrsolutions.com.br','specialitysoldas@hotmail.com','stefanodurazzo@gmail.com','wly.adm@outlook.com'); // function addContatoLista($contatos,$id_lista)
		$emkt->addContatoLista($contatos,849);

		$contatos = array('adriano@itsvm.com.br','alesil@gmail.com','alexwebmaster@uol.com.br','alex_sousa2003@yahoo.com.br','aline.lvasconcelos@gmail.com','alineb3006@hotmail.com','allangimenes@hotmail.com','alsexpress@live.com','atendimento@bravofilmes.com','atendimento@cii-idiomas.com.br','caioorenga@msn.com','caixa.postla.md@gmail.com','cleliamarcia@pbfjardimhelena.com.br','construcoessomar@gmail.com','contabilidade@arencon.com.br','cristiane.valasque@gmail.com','davidasilvarodrigues385@gmail.com','ethelyne@jswfdb48z.com','ff.santosjr@outlook.com','fiscal@iope.com.br','fsandes@gmail.com','giuliana_simone@hotmail.com','hudson.borges@wecanada.com.br','jehbazz@hotmail.com','jmoreno@hazorh.com.br','josuecontabil@yahoo.com.br','karin_chu@hotmail.com','marcelo@tersso.com.br','medicsell.roberval@gmail.com','mimisoliveirasp@gmail.com','narducci1@gmail.com','nivea@hiseg.com.br','otavio.santanna@edu.fecap.br','patricia@universotur.com','paulo_tavares_refratarios@hotmail.com','re.albuquerque@bol.com.br','rege@terra.com.br','renanborges.consultoria@gmail.com','renatocmoraes@globo.com','roberta.sparvoli77@gmail.com','rogerio.simoes@beermotor.com.br','sisconeto@gmail.com','tatiana_contabilidade@yahoo.com.br','taznety@gmail.com','willromanini@gmail.com'); // function addContatoLista($contatos,$id_lista)
		$emkt->addContatoLista($contatos,849);

		$contatos = array('a4cg@terra.com.br','adm@zvseguros.com.br','alessandro_sales@bol.com.br','aletofalo@hotmail.com','alex@mochilasocial.com','alexandre.brenno@gmail.com','alexandrehf@hotmail.com','alfa.servicoadm@uol.com.br','amanda_mcaldeira@hotmail.com','andrellima1057@gmail.com','angelica.branco@hotmail.com','apaulinadasilva@yahoo.com','arnaldofilho1@hotmail.com','atosvisual@gmail.com','bfnegocios@gmail.com','bruno-dr@ig.com.br','brunomatos_s@msn.com','camila.donascimentosilva@gmail.com','carlos.santiago.neto@gmail.com','claudia@rabiscosdogama.com.br','claudiothomasoadq@gmail.com','clayton.stay@gmail.com','cledsontst@gmail.com','coifasalca@hotmail.com','confianca.climafrio@gmail.com','contabilidadealvarenga@yahoo.com.br','contabilidadejsv@gmail.com','contato@arbisp.com.br','contato@rrftranslog.com.br','cristiano@inova6telecom.com.br','danielfrancisco310@gmail.com','danymoriyama@gmail.com','dianagassis@gmail.com','djane.palmeira@gmail.com','dmdbcontabilidade@hotmail.com','eduardo.labonia@hotmail.com','emiliano.paulista@gmail.com','engenheirojulio@uol.com.br','equipe.brasil@yahoo.com.br','fabioostronoff@yahoo.com.br','fbooxyz@gmail.com','fcgpes@gmail.com','felipe.mopta@yahoo.com.br','felipe_paoli@yahoo.com','felippe.fiuza@gmail.com','fernandastaropoli@hotmail.com','fernandoleonardoassessoria@gmail.com','fernando_versignassi@hotmail.com','fgmportoes@gmail.com','fguimaraes84@gmail.com','fla.matos81@gmail.com','flexinsp@flexinsp.com.br','fonseca.contabil@terra.com.br','freddiresolv@uol.com.br','galileu.brito@gmail.com','gerencia@estiloimoveispaulista.com.br','gerencia@yescomercial.com.br','ghoche@uol.com.br','gilsonsilva011077@gmail.com','gilsonsilvasp@hotmail.com','giulianno@gmail.com','grazii241@gmail.com','honoratoruiz@hotmail.com','iquirino91@gmail.com','joao.salvador@outlook.com.br','jorge@sensorh.com.br','lucratt@yahoo.com.br','luis@sipcom.com.br','luiz.evalt@gmail.com','luiz@alkanceassessoria.com.br','luizotaviosms@gmail.com','mac.valentini@gmail.com','mamarini01@gmail.com','marcel@marcelleal.com.br','marcelo.contabil.2014@outlook.com','marcelo.couto@sossego.com.br','marcio.gouveia@tihelper.com.br','marcosvistos2012@hotmail.com','mari.cosme@gmail.com','maronchaveiro@gmail.com','martaferreira78@hotmail.com','mauricio_palugan@hotmail.com','maxprime@live.com','michele@aficon.com.br','michelle.leal@loyalcontabilidade.com.br','miguelfernando@globo.com','milenecintra@yahoo.com.br','mlton@hotmail.com','mscabellom@hotmail.com','novaera-usinagem@outlook.com','partnersecotravel@gmail.com','paulo.tobaruela@yahoo.com.br','paulo@scarvalho.com','paulogenovese@outlook.com','personaldetalhamentoautomotivo@gmail.com','phpadovani@outlook.com','pierox2000@gmail.com','priscoradames@hotmail.com','rafa.elfernandes@hotmail.com','rafaelnbonifacio@hotmail.com','ramon@larafranco.com','re-mathias@hotmail.com','reinaldo.fdantas@gmail.com','renan_ashikawa@hotmail.com','rgtomirotti@gmail.com','ricont@ricont.com','roarnaud63@gmail.com','rodrigo.brinquedos@hotmail.com','rodrigo@l2midia.com.br','rodrigo@m3criatividade.com.br','romuloffreire@gmail.com','rone.ferreira@hotmail.com','roseni.farias@iope.com.br','rraoli@gmail.com','rtc.gabriel@gmail.com','s.sandrasouza36araujo@gmail.com','silvia.mimorimoto@gmail.com','sisilili45@gmail.com','spatour@uol.com.br','spaziofamily@yahoo.com.br','tanianl@ig.com.br','thaisbastos78@gmail.com','univisaovistoria@gmail.com','valdirjuni@gmail.com','van.donda@terra.com.br','vaniammarino@gmail.com','victor@siberiantech.com.br','vsantista@helphome.srv.br','waldir.arruda@advisesp.com.br','wilmamatosdemelo@yahoo.com.br','zaleski.leonardo@gmail.com'); // function addcontatolista($contatos,$id_lista)
		$emkt->addContatoLista($contatos,849);
		// ##########################################################################################################################################

		// ##########################################################################################################################################		
		// // Agendar uma mensagem 
		// echo $emkt->agendarMensagem( $tipo_mensagem , $id_da_lista ); // function agendarMensagem($tipo_mensagem,$id_lista)
		// echo '<br>';
		// ##########################################################################################################################################

	

	?>

</div>




