<?php 
	
	session_start();
	if (isset($_SESSION["id_userSecao"])) 
	{
		include 'header_restrita.php';
		$cidade = !empty($user->getCidade()) ? $user->getCidade() : '';							  
	} else {
		$cidade = '';
		$nome_meta = "alteracao";
		include 'header.php';
	} 
?>

<div class="principal">
<h1 style="margin-bottom:20px">Alteração de Empresa</h1>

<h2>Veja como alterar sozinho os dados de sua EIRELI, EI ou Sociedade Limitada.</h2>


A alteração de empresa passa pelas três esferas de governo: federação, estado e município, pois cada uma possui seu próprio registro e todos precisam ser atualizados. Proceda da seguinte forma:<br>
<br><br>
<!--ballon-->
<div style="float: right; margin-left: 20px">
<div style="float:right"><img src="images/boneca-assinatura.png" alt="Nosso contador faz a abertura para você" width="110" title="Nosso contador faz a abertura para você."/></div>    
<div class="bubble_right_top" style="width:200px; float:right">
<div style="padding:15px">
<div class="tituloAzul">Está complicado?</div>
Se precisar nosso contador faz a abertura para você. Ele cobra  R$ 300.
<br>
<br>
Contrate-o <a href="servico-contador.php">aqui mesmo</a>.
</div>
</div>
<div style="clear: both"></div>
</div>
	
<!--fim do ballon-->

<h3>Alteração na Receita Federal</h3>
Para alterar os dados de sua empresa no Cadastro Nacional da Pessoa Jurídica - <strong>CNPJ</strong>-, acesse nosso <a href="orientacoes_dbe.php">tutorial para geração do DBE</a>.<br><br><br>


<h3>Alteração no Estado</h3>
Feito o DBE, você deve dar entrada com o processo de alteração na <strong>Junta Comercial</strong> de seu estado. Excepcionalmente, sociedades uniprofissionais, registradas em <strong>cartório</strong>, e escritórios de Advocacia, registrados na <strong>OAB</strong>, devem requerer a alteração diretamente nestes órgãos.<br>
<br>

Para a alteração na Junta, todos os estados já dispõem de mecanismos online. Os sites nem sempre são muito intuitivos, entretanto, o Governo Federal  vem realizando um grande esforço para padronizar o processo através do Portal <strong>Redesim</strong>. Ao entrar na Junta Comerical de seu estado, você normalmente precisará informar seu CPF e gerar uma senha de acesso. Em seguida deve procurar pelo link "Alteração de Empresa".<br>
<br>
O Contador Amigo fez um levantamento das páginas iniciais para alteração de uma empresa. Estes sites costumam mudar muito e caso você encontre algum link quebrado ou apontando para outro local, por favor nos comunique pelo <a href="suporte.php">Help Desk</a> para que possamos manter as informações atualizadas. Seguem os links, divididos por estado:<br>


<div style="float: left; margin-right: 30px">
<ul>
<li><a href="http://www.juceac.ac.gov.br/wps/portal/juceac/juceac/principal/!ut/p/c5/vZDPboJAEIefxRdgZ1lh4YjpKqss_6GwF4JNY8CCphLQffrS9ND0UHsxnd9lksnM92WQRHP6emwO9dCc-voNFUiaVejyKMlcA1tZTICHefC02xjEigh6RgUsq6S9nbk6qriFq_DbOBj20SQ8BoJNV6HinThaKmWDl7biNigH-9lFwcXG-TpijvfeS14uZlZpVvBLOfCHyRbJZt9p00ungUYp2HhJTCCmYWNKULGaVeXdE_hrfkegRJJ-7weCWcANwvL1KsJzh9IHPuMny_KoPhM4ELEFXd_Q_2NZy4eyfPfUvaJzN3rGmITd-JnD4gPdTtf0/dl3/d3/L2dBISEvZ0FBIS9nQSEh/">Acre</a></li>
<li><a href="http://www.facilita.al.gov.br/" target="_blank">Alagoas</a></li>
<li><a href="http://www.empresafacil.ap.gov.br/" target="_blank">Amapá</a></li>
<li><a href="http://www.empresasuperfacil.am.gov.br/" target="_blank">Amazonas</a></li>
<li><a href="http://www.juceb.ba.gov.br/" target="_blank">Bahia</a></li>
<li><a href="http://portalservicos.jucec.ce.gov.br/auth/realms/Portal_Servicos/protocol/openid-connect/auth?response_type=code&client_id=portalexterno&redirect_uri=http%3A%2F%2Fportalservicos.jucec.ce.gov.br%2FPortal%2Fpages%2Fprincipal.jsf&state=199492%2F20359cf1-77c2-4c3b-b178-906695e67053&login=true" target="_blank">Ceará</a></li>
<li><a href="https://rle.empresasimples.gov.br" target="_blank">Distrito Federal</a></li>
</ul>
</div>

<div style="float: left; margin-right: 30px">
<ul>	

<li><a href="https://www.jucees.es.gov.br/" target="_blank">Espírito Santo</a></li>
<li><a href="http://www.portaldoempreendedorgoiano.go.gov.br/" target="_blank">Goiás</a></li>
<li><a href="http://www.jucema.ma.gov.br/pagina?/124/26/REGISTRO_EMPRESARIAL" target="_blank">Maranhão</a></li>
<li><a href="http://redesim.jucemat.mt.gov.br/requerimentoV2/servicos.aspx" target="_blank">Mato Grosso</a></li>
<li><a href="http://portalservicos.jucems.ms.gov.br/auth/realms/rsintegrar/protocol/openid-connect/auth?response_type=code&client_id=Portal&redirect_uri=http%3A%2F%2Fportalservicos.jucems.ms.gov.br%2FPortal%2Fpages%2Fprincipal.jsf&state=4494%2Fdf6ccedc-e569-4cd8-be35-d93385f1bc4d&login=true" target="_blank">Mato Grosso do Sul</a></li>
<li><a href="http://portalservicos.jucemg.mg.gov.br/auth/realms/Portalservicos/protocol/openid-connect/auth?response_type=code&client_id=portalexterno&redirect_uri=http%3A%2F%2Fportalservicos.jucemg.mg.gov.br%2FPortal%2Fpages%2Fprincipal.jsf&state=9841%2F60b9a8e5-5473-4a36-be30-fb693408a552&login=true" target="_blank">Minas Gerais</a></li>	 
<li><a href="http://regin.jucepa.pa.gov.br/RequerimentoUniversal/" target="_blank">Pará</a></li>	 
</ul>
</div>

<div style="float: left; margin-right: 30px">
<ul>
<li><a href="http://www.redesim.pb.gov.br/" target="_blank">Paraíba</a></li>	 
<li><a href="http://portalservicos.jucepar.pr.gov.br/Portal/login.jsp?josso_back_to=http://portalservicos.jucepar.pr.gov.br/Portal/josso_security_check" target="_blank">Paraná</a></li>	 
<li><a href="http://www.jucepe.pe.gov.br/" target="_blank">Pernambuco</a></li>
<li><a href="http://www.piauidigital.pi.gov.br/" target="_blank">Piauí</a></li>
<li><a href="https://www.jucerja.rj.gov.br/JucerjaPortalWeb/Paginas/Servicos/UsuarioAutenticacao.aspx?redir=L0p1Y2VyamFQb3J0YWxXZWIvUGFnaW5hcy9SZXF1ZXJpbWVudG8vUmVxdWVyaW1lbnRvUFdKLmFzcHg=" target="_blank">Rio de Janeiro</a></li>
<li><a href="http://www.redesim.rn.gov.br/" target="_blank">Rio Grande do Norte</a></li> 
<li><a href="http://jucisrs.rs.gov.br/inicial" target="_blank">Rio Grande do Sul</a></li>
</ul>
</div>

<div style="float: left; margin-right: 30px">
<ul>
<li><a href="http://www.rondonia.ro.gov.br/jucer/" target="_blank">Rondônia</a></li>
<li><a href="http://projetointegrar.jucerr.rr.gov.br/auth/realms/rsintegrar/protocol/openid-connect/auth?response_type=code&client_id=Portal&redirect_uri=http%3A%2F%2Fprojetointegrar.jucerr.rr.gov.br%2FPortal%2Fpages%2Fprincipal.jsf&state=777%2Fab5fb955-0d05-4def-bf2a-4ca3112ca63e&login=true" target="_blank">Roraima</a></li>
<li><a href="http://www.jucesc.sc.gov.br/" target="_blank">Santa Catarina</a></li>
<li><a href="alteracao_contrato_junta_sp.php">São Paulo</a></li>
<li><a href="http://www.agiliza.se.gov.br/" target="_blank">Sergipe</a></li>
<li><a href="http://www.simplifica.to.gov.br/" target="_blank">Tocantins</a></li>	 
</ul>
</div>

<div style="clear: both; height: 20px"></div>


Se você estiver alterando uma sociedade, precisará redigir o novo contrato social consolidado com a alteração. Para tal, use o <a href="alteracao_contrato.php">aplicativo de alteração contratual</a>. Se estiver alterando uma <strong>Ereli</strong>, é preciso alterar o Ato Constitutivo. Baixe este <a href="downloads/modelo_eireli_alt.doc" target="_blank">modelo básico de alteração do ato constitutivo</a>.<br /><br />

Envie toda essa papelada até a Junta Comercial (alguns estados já possibilitam a transmissão online para quem tem certificado digital). A documentação será analisada e, se estiver  em ordem, a alteração será homologada. Se houver alguma irregularidade, o processo retorna para que você o complemente com a exigência solicitada. Depois de homologado, sua empresa já estará alterada perante o Estado e a Receita Federal. <br>
<br><br>

<a name="prefeitura"></a><h3>Inscrição na Prefeitura</h3>
Após a alteração na junta, as empresas <strong>prestadoras de serviços</strong> precisam atualizar também seu registro na prefeitura. Para isso você deverá preencher <?php if( $cidade == 'São Paulo' ){ ?>o <a href="https://ccm.prefeitura.sp.gov.br/login/contribuinte?tipo=A" target="_blank">Requerimento de Atualização do CCM</a>, imprimi-lo e levá-lo na Praça de Atendimento da Secretaria de Finanças (localizada no Vale do Anhangabaú, 206/226, ao lado da Galeria Prestes Maia) de segunda a sexta-feira, das 8h às 18h. Mas atenção: a entrega só pode ser feita mediante <a href="http://agendamentosf.prefeitura.sp.gov.br/forms/BemVindo.aspx" target="_blank">agendamento prévio</a>.<?php }else{ ?>um requerimento de atualização. Algumas cidades já possibilitam o preenchimento online. Verifique se esta facilidade  já está disponível em seu município. Mesmo com o preenchimento online, é bastante provável que você ainda precise ir até a Prefeitura para que seus dados sejam conferidos e sua identidade confirmada.<?php } ?>
<br /><br /><br>

<!--<h3 >Alteração na Prefeitura</h3>
Depois de homologado pela Junta, seu registro já estará devidamente alterado perante o Estado e a Receita Federal. Ficará ainda faltando efetuar a alteração junto à Prefeitura. Para isso você deverá preencher <?php if( $cidade == 'São Paulo' ){ ?>o <a href="https://ccm.prefeitura.sp.gov.br/login/contribuinte?tipo=A" target="_blank">Requerimento de Atualização do CCM</a>, imprimi-lo e levá-lo na Praça de Atendimento da Secretaria de Finanças, localizada no Vale do Anhangabaú, 206/226, ao lado da Galeria Prestes Maia, de segunda a sexta-feira, das 8h às 18h. Mas atenção a entrega só pode ser feita mediante <a href="http://agendamentosf.prefeitura.sp.gov.br/forms/BemVindo.aspx" target="_blank">agendamento prévio</a>.<?php }else{ ?>um requerimento de atualização. Algumas cidades já possibilitam o preenchimento online. Verifique se esta facilidade  já está disponível em seu município. Mesmo com o preenchimento online, é bastante provável que você ainda precise ir até a Prefeitura para que seus dados sejam conferidos e sua identidade confirmada.<?php } ?><br /><br /><br>-->



  <strong> Depois de feita a alteração, não esqueça de atualizar os dados da empresa aqui no Contador Amigo. Assim nosso sistema poderá orientá-lo corretamente no cumprimenro de suas obrigações.</strong>
 <br>
<br>
  <div class="quadro_branco"> <span class="destaque">ATENÇÃO:</span> Se estiver alterando a atividade da empresa, faça uma pesquisa no site do <a href="http://www.cnae.ibge.gov.br/" target="_blank">IBGE/Concla</a> para definir os códigos correspondentes. Como as alíquotas variam de acordo com a atividade, procure encaixar códigos que pagam menos impostos. Além disso, é preciso verificar se a atividade  é permitida ao Simples. Acesse nossa <a href="https://www.contadoramigo.com.br/abertura_selecao_atividades.php">Página de Seleção de Atividades</a>, para fazer estas verificações.</div>
  
  
</div>
<?php include 'rodape.php' ?>
