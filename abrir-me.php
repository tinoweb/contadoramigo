contr<?php 
	
	session_start();
	if (isset($_SESSION["id_userSecao"])) 
	{
		include 'header_restrita.php';
		$cidade = !empty($user->getCidade()) ? $user->getCidade() : '';							  
	} else {
		$cidade = '';
		$nome_meta = "abertura_empresa";
		include 'header.php';
	} 
?>

<div class="principal">
<h1 style="margin-bottom:20px">Como abrir uma ME</h1>

<h2>Veja como abrir sozinho sua microempresa - EIRELI, EI ou Sociedade Limitada</h2>
<br>


<h3>Escolha o tipo de empresa que vai abrir</h3> 
Se você pretende ser o único dono da empresa, deve escolher o tipo <b>Empresário Individual</b>. Se terá sócios, deve abrir uma <b>Sociedade Empresária Limitada</b>. Alguém pode lhe sugerir abrir uma empresa tipo <b>EIRELI</b> - Empresa Individual de Responsabilidade Limitada. É semelhante ao Empresário Individual, a diferença é que na EIRELI os bens da pessoa física ficam separados dos bens da empresa. Se você falir, vai ser mais difícil irem atrás dos seus bens pessoais. Em compensação, para abrir uma EIRELI o capital social mínimo precisa ser de 100 salários mínimos. Nossa recomendação é que você deixe a opção de EIRELI de lado, pois no final os credores avançariam em seus bens pessoais de qualquer forma.<br><br><br>

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

<h3>Sociedades Uniprofissionais</h3>
Se você for um profissional liberal (médico, advogado, engenheiro, dentista, etc) e estiver montando uma empresa sozinho ou com outros colegas da mesma profissão, você pode se enquadrar como Sociedade Uniprofissional. A vantagem é que, na maioria dos municípios, o ISS é menor. Ao invés de  um percentual sobre o  faturamento (normalmente 2%), a empresa pagaria uma taxa fixa mensal, que é bem menor. Durante o processo de registro na prefeitura você poderá fazer esta opção. Vale lembrar, porém, que em São Paulo Capital, empresas optantes pelo Simples não têm direito ao ISS fixo, exceto escritórios contábeis.<br><br><br>

    
<h3>Pesquise um nome para sua ME</h3>
O nome da empresa, também conhecido como Razão Social, pode ser escolhido livremente em se tratando de sociedade Empresária, devendo terminar com a palavra Ltda. Já o empresário Individual precisa usar seu próprio nome na empresa, podendo  acrescentar palavras que informem o tipo de atividade desenvolvida. Por exemplo: <b>José da Silva  Representações</b>. No registro também é possível definir o <b>Nome Fantasia</b> para usar em seus impressos e cartazes. Este você pode escolher livremente.<br><br> 

No caso das Sociedades, para não correr o risco de registrar um nome de empresa já existente, você deve fazer uma <a href="https://gru.inpi.gov.br/pePI/jsp/marcas/Pesquisa_classe_basica.jsp" target="_blank">pesquisa no INPI</a> .<br><br><br>


<h3>Defina o Capital Social</h3>
Ao abrir a sua empresa, você deverá registrar um capital social. Defina um valor correspondente ao investimento inicial para a abertura da empresa. Some os valores dos móveis e equipamentos e o dinheiro que colocará na conta da empresa ao abri-la. O ideal é que você tenha as notas fiscais que comprovem o valor dos bens adquiridos. Lembre-se que no caso de EIRELI o capital social mínimo é de 100 salários mínimos<br><br><br>


<h3>Selecione as Atividades</h3>
Durante o processo de abertura da empresa, você precisará informar <b>o código (CNAE)</b> das atividades a serem desenvolvidas. Faça uma pesquisa no site do <a href="http://www.cnae.ibge.gov.br/" target="_blank">IBGE/Concla</a> para defini-las. Uma delas deverá ser escolhida como a principal e as demais serão as secundárias. Evite usar muitos códigos, para não arcar com obrigações desnecessárias - você poderá acrescentar novas atividades no futuro. <br><br>
Como as alíquotas variam de acordo com a atividade, procure encaixar códigos que pagam menos impostos. Além disso, é preciso verificar se a atividade  é permitida ao Simples. Acesse nossa <a href="abertura_selecao_atividades.php">Página de Seleção de Atividades</a> para fazer estas verificações.<br><br><br>


<h3>Escolha o regime de tributação</h3> 
No Brasil, é possível escolher entre três regimes de tributação: <b>Lucro Real</b>, <b>Lucro Presumido</b> ou <b>Simples Nacional</b>. O mais conveniente para pequenas empresas recém-abertas é o Simples Nacional. Nele, as alíquotas dos impostos são quase sempre mais baixas e há menos burocracia. Para optar pelo Simples Nacional, você precisará encaminhar o pedido de enquadramento pela Internet em até 30 dias a partir do registro da empresa.<br><br><br>


<div style="font-weight: bold">Muito bem, definidas estas questões, você está pronto para requerer a abertura de sua empresa! Para isso, siga nossas orientações abaixo.</div> <br>
<br>


<h3 class="tituloVermelho">1. DBE - Documento Básico de Entrada</h3>
O primeiro passo é solicitar seu registro no CNPJ. Para isso, acesse nosso <a href="abertura_dbe.php">tutorial para geração do DBE</a>.<br><br>
Observação: Na cidade de São Paulo, o DBE só poderá ser iniciado após <strong>análise prévia de viabilidade</strong>. Isto é, verificar se já existe outra empresa com o mesmo nome e se a atividade que irá desempenhar pode ser exercida no endereço onde pretende instalar a empresa. Para fazer a análise, acesse o site de <a href="https://rle.empresasimples.gov.br/rle/" target="_blank">Registro e Licenciamento de Empresas</a>.
<br><br><br>

<h3 class="tituloVermelho">2. Registro na Junta Comercial ou Cartório</h3>
Feito o DBE, você deve dar entrada com o processo de registro na <strong>Junta Comercial</strong> de seu estado. Excepcionalmente, sociedades Uniprofissionais devem registrar nova empresa em um <strong>Cartório</strong> e os escritórios de Advocacia, na <strong>OAB</strong>. Se este for o seu caso, obtenha as informações sobre o processo de abertura diretamente nestes órgãos.<br>
<br>
Para a abertura na Junta, todos os estados já dispõem de mecanismos online. Os sites nem sempre são muito intuitivos, mas o Governo Federal  vem realizando um grande esforço para padronizar o processo através do Portal Redesim. Ao entrar na Junta Comerical de seu estado, você normalmente precisará informar seu CPF e gerar uma senha de acesso. Em seguida deve procurar pelo link de "Abertura de Empresa". Se não encontrá-lo, entre em "Solicitar Análise de Viabilidade" que é o primeiro passo para a abertura da empresa. Na análise de viabilidade é verificado se já existe outra firma com o mesmo nome, e também se a atividade que irá desempenhar pode ser exercida no endereço onde pretende instalar a empresa. <br>
<br>
O Contador Amigo fez um levantamento das páginas iniciais para o registro de uma empresa em todo o Brasil. Estes sites costumam mudar muito e caso você encontre algum link quebrado ou apontando para outro local, por favor nos comunique pelo <a href="suporte.php">Help Desk</a> para que possamos manter as informações atualizadas. Seguem os links, divididos por estado:<br>


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
<li><a href="http://vre.portal.jucesp.sp.gov.br/VRE2_Portal/login/initLogin.html" target="_blank">São Paulo</a></li>
<li><a href="http://www.agiliza.se.gov.br/" target="_blank">Sergipe</a></li>
<li><a href="http://www.simplifica.to.gov.br/" target="_blank">Tocantins</a></li>	 
</ul>
</div>

<div style="clear: both; height: 20px"></div>

Ao finalizar os processos online na Junta comecial de seu Estado, imprima os documentos e assine. Entre os arquivos gerados estarão duas guias (A DARE e a DARF) para pagamento das taxas de abertura. Faça o recolhimento e depois leve toda a papelada até a Junta Comercial  (alguns estados já possibilitam a transmissão online para quem tem certificado digital). <br>
<br>
Se você estiver abrindo uma sociedade, precisará elaborar também o <b>Contrato Social</b> da empresa. Baixe este <a href="http://www.jucespciesp.com.br/index.php/modelo-de-documentos/arquivos-para-download?download=2:modelo-de-contrato-social" target="_blank">modelo básico de Contrato Social</a>. Basta alterá-lo com os dados dos sócios. Se quiser, pode acrescentar cláusulas específicas, que atendam suas necessidades. Se estiver abrindo uma <strong>Ereli</strong>, é preciso redigir o Ato Constitutivo. Baixe este <a href="downloads/modelo_de_ato_constitutivo_do_eireli.doc" target="_blank">modelo básico de ato constitutivo</a>.<br /><br />
 
A documentação será analisada e, se estiver  em ordem, a abertura será homologada. Se houver alguma irregularidade, o processo retorna para que você o complemente com a exigência solicitada. Depois de homologado, sua empresa já estará aberta perante o Estado e a Receita Federal. <br>
<br><br>

<a name="prefeitura"></a><h3 class="tituloVermelho">3. Inscrição na Prefeitura</h3>


Após o registro na Junta, todas as empresas prestadoras de serviços precisam fazer sua <strong>inscrição municipal</strong>. Para isso você deverá preencher <?php if( $cidade == 'São Paulo' ){ ?>o <a href="https://ccm.prefeitura.sp.gov.br/login/contribuinte?tipo=I" target="_blank">Requerimento de Inscrição no CCM</a>, imprimi-lo e levá-lo na Praça de Atendimento da Secretaria de Finanças (localizada no Vale do Anhangabaú, 206/226, ao lado da Galeria Prestes Maia) de segunda a sexta-feira, das 8h às 18h. Mas atenção: a entrega só pode ser feita mediante <a href="http://agendamentosf.prefeitura.sp.gov.br/forms/BemVindo.aspx" target="_blank">agendamento prévio</a>.<?php }else{ ?>o requerimento de abertura a partir do site da Prefeitura de sua cidade.<?php } ?> 

Em se tratando de comércio, ao invés da inscrição municipal, você deve obter a <strong>inscrição estadual</strong>. Isto porque as empresas do comércio recolhem ICMS, que é um imposto estadual, enquanto que as prestadoras de serviços recolhem o ISS, que é municipal. Para obter sua inscrição estadual, dirija-se ao Posto Fiscal da secretaria da fazenda de seu estado.
<br /><br /><br> 

<h3 class="tituloVermelho">4. Nota Fiscal Eletrônica</h3>
Muito bem. Agora que sua empresa está aberta é hora de cadastrar-se na Nota Fiscal Eletrônica. Normalmente o processo funciona assim: ao obter sua inscrição municipal, a prefeitura lhe fornece uma senha. Com ela, acesse o site da Nota Fiscal Eletrônica de seu município. Em São Paulo - SP, entre no <a href="http://nfpaulistana.prefeitura.sp.gov.br/" target="_blank">site da Nota Fiscal Paulistana</a>. Você também poderá usar o certificado digital de sua empresa, se já tiver um. No primeiro acesso, é preciso configurar o perfil da empresa. Aproveite e verifique se ela já aparece cadastrada como optante pelo Simples. Feito isto, você já estará pronto para imprimir suas notas fiscais!<br>
<br>
Para empresas do comércio, é um pouco diferente. Após a obtenção da inscrição estadual, não haverá um site do governo para emitir as notas. Você deverá contratar um serviço de emissão de notas particular. Existem várias opções no mercado, variando entre sistemas online e softwares para download. Nossa recomendação é o <a href="http://www.sebrae.com.br/sites/PortalSebrae/ufs/sp/institucional/emissor-da-nf-e,43ce5762777fa510VgnVCM1000004c00210aRCRD">sistema emissor de notas do Sebrae</a>.<br>
<br>
<br>


<h3 class="tituloVermelho">5. Adesão ao Simples</h3>
Feito o registro da empresa, solicite o quanto antes sua <a href="https://www.contadoramigo.com.br/enquadramento_simples.php">adesão ao Simples Nacional</a> para que você já comece a gozar dos benefícios fiscais. <b>Empresas recém-abertas têm até 30 dias para fazê-lo, </b>a contar do último deferimento de inscrição (municipal ou estadual). Se não o fizer, você será automaticamente enquadrado no Regime de Lucro Presumido e só poderá solicitar seu enquadramento no Simples em janeiro do ano seguinte. Uma vez deferido seu enquadramento no Simples, pode abrir a champagne. Sua nova empresa estará pronta para começar!<br><br><br>


</div>


</div>




<?php include 'rodape.php' ?>
