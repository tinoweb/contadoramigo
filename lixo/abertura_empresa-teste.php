<?php  
session_start();
if (isset($_SESSION["id_userSecao"])) {include 'header_restrita.php';}
else {	
	$nome_meta = "abertura_empresa";
	include 'header-teste.php';} 
?>


<div class="principal">
<h1 class="titulo" style="margin-bottom:20px">Abertura de Empresa</h1>

<h2 class="tituloVermelho" style="margin-bottom:20px">Abra sua empresa de graça. Siga nosso tutorial e faça sozinho a abertura da sua empresa.</h2>

Antes de iniciar o processo de abertura propriamente dita, é preciso definir algumas questões:<br><br>
    
<h3 class="tituloAzul">Escolha o tipo de empresa que vai abrir</h3> 
Se você pretende ser o único dono da empresa, deve escolher o tipo <b>Empresário Individual</b>. Se terá sócios, deve abrir uma <b>Sociedade Empresária Limitada</b>. Alguém pode lhe sugerir abrir uma empresa tipo <b>EIRELI</b> - Empresa Individual de Responsabilidade Limitada. A deferença para o empresário individual é que os bens da pessoa física ficam separados dos bens da empresa. Se você falir, vai ser mais difícil irem atrás dos seus bens pessoais. Em compensação, para abrir uma EIRELI o capital social mínimo precisa ser de 100 salários mínimos. Nossa recomendação é que vc deixe a opção de EIRELI de lado, pois no final os credores avançariam em seus bens pessoais de qualquer forma.<br><br>

<h3 class="tituloAzul">Sociedades Uniprofissionais</h3>
Se você for um profissional liberal (médico, advogado, engenheiro, dentista, etc) e estiver montando uma empresa sozinho, ou com outros colegas da mesma profissão, você pode se enquadrar como Sociedade Uniprofissional - SUP. A vantagem é que, na grande maioria dos municípios, o ISS é menor. Ao invés de  um percentual sobre o  faturamento  (normalmente 2%), a empresa pagaria   uma taxa fixa mensal, que é bem menor. Durante o processo de registro na prefeitura você poderá fazer esta opção. Vale lembrar, porém, que em São Paulo Capital, empresas optantes pelo Simples não têm direito ao ISS fixo, exceto escritórios contábeis.<br><br>
    
<h3 class="tituloAzul">Pesquise um nome para sua ME</h3>
O nome da empresa, também conhecido como Razão Social, pode ser escolhido livremente em se tratando de sociedade Empresária, devendo terminar com a palavra Ltda. Já o empresário Individual precisa usar seu próprio nome na empresa, podendo,  acrescentar palavras que informem o tipo de atividade desenvolvida, por exemplo <b>José da Silva  Representações</b>. Mas no registro é possível definir também o <b>Nome Fantasia</b>, para usar em seus imprtessos e cartazes. Este você pode escolher livrmente.<br><br> No caso das Sociedades para não correr o risco de registrar um nome de empresa já existente, você deve fazer uma <a href="https://gru.inpi.gov.br/pePI/jsp/marcas/Pesquisa_classe_basica.jsp" target="_blank">pesquisa no INPI</a> .<br><br>

<h3 class="tituloAzul">Defina o Capital Social</h3>
O abrir a sua empresa, você deverá registrar um capital social. Defina um valor correspondente ao investimento inicial para a abertura da empresa (Some os valores dos móveis e equipamentos e o dinheiro que colocará na conta da empresa ao abri-la. Lembre que no caso de EIRELI o capital social mínimo é de 100 salários mínimos.<br><br>

<h3 class="tituloAzul">Escolha o regime de tributação</h3> 
No Brasil é possível escolher entre três regimes de tributação: <b>Lucro Real</b>, <b>Lucro Presumido</b> ou <b>Simples Nacional</b>. O mais conveniente para pequenas empresas recém-abertas é o Simples Nacional. Nele as alíquotas dos impostos são quase sempre mais baixas e há menos burocracia. Para optar pelo Simples Nacional, você precisará encaminhar o pedido de enquadramento pela Internet em até 30 dias a partir do registro da empresa no CNPJ.<br><br>

<h3 class="tituloAzul">Selecione as Atividades</h3>
Durante o processo de abertura da empresa, você precisará informar <b>o código (CNAE)</b> das atividades a serem desenvolvidas. Faça uma pesquisa no site do <a href="http://www.cnae.ibge.gov.br/" target="_blank">IBGE/Concla</a> para defini-los. Pode ser apenas uma ou mais atividades. Nesse caso uma delas deverá ser a principal. Evite usar muitos códigos, para não arcar com obrigações desnecessárias. Você poderá acrescentar novos  no futuro. <br><br>
Como as alíquotas variam de acordo com a atividade, procure encaixar códigos que pagam menos impostos. Além disso, é preciso verificar se a atividade  é permitida ao Simples. Acesse nossa <a href="https://www.contadoramigo.com.br/abertura_selecao_atividades.php">Página de Seleção de Atividades</a>, para fazer estas verificações.<br><br><br>
Definidas estas questões, você está pronto para iniciar a abertura de sua empresa.<br><br><br>

<h2 class="tituloVermelho">Registro na Junta Comercial ou Cartório</h2>
	O primeiro passo para o registro de sua empresa é dar entrada com o processo na Junta Comercial. Excepcionalmente, sociedades Uniprofissinais, ao invés da Junta, devem registrar nova empresa em um Cartório e sociedade de Advogados, na OAB. <br>
<br>
Se você vai registrar sua empresa na Junta, acesse o site da Junta Comercial de seu estado. Quase todos já dispõem de mecanismos online para geração dos formulários, documentos e guias de impostos necessários. Se você é do Estado de São Paulo, siga nosso <a href="abertura_junta_sp_geral.php">tutorial de registro na Junta</a>. <br><br>

Você deverá  anexar ainda a toda essa documentação o <strong>DBE - Documento Básico de Entrada</strong>, também gerado pela Internet. Acesse o <a href="abertura_dbe.php">tutorial para geração do DBE</a> (vale para empresas de todos os estados). Você notará que ele contém informações semelhantes ao requerimento da Junta. O primeiro será usado para cadastrar seus dados no sistema da Receita Federal e gerar seu CNPJ e o outro para cadastrá-lo junto ao Estado.<br><br>

Se você estiver abrindo uma sociedade, precisará elaborar também o <b>Contrato Social</b> da empresa. Baixe este <a href="http://www.jucespciesp.com.br/modelo/Modelo%20Contrato%20Social.doc" target="_blank">modelo básico de Contrato Social</a>. Basta alterá-lo com os dados dos sócios. Se quiser pode acrescentar cláusulas específicas, que atentam suas necessidades. Se estiver abrindo uma <strong>Ereli</strong>, é preciso redigir o Ato Constitutivo. Baixe este <a href="downloads/modelo_de_ato_constituvico_do_eireli.doc" target="_blank">modelo básico de ato constitutivo</a>.<br /><br />

Leve toda essa papelada até a Junta Comercial. A documentação será analisada e, se estiver  em ordem, a alteração será homologada. Se houver alguma irregularidade, o processo retorna, para que você o complemente com a exigência solicitada. Depois de homologado, sua empresa já estará aberta perante o Estado e a Receita Federal. <br>
<br><br>



	<a name="prefeitura"></a><h2 class="tituloVermelho">Inscrição na Prefeitura</h2>

Após o registro na Junta, todas as empresas <strong>prestadoras de serviços</strong> precisam fazer também o cadastro junto à Prefeitura. Para isso você deverá preencher um requerimento de atualização. Algumas cidades já possibilitam o preenchimento online. Verifique se esta facilidade  já está disponível em seu município. Mesmo com o preenchimento online, é bastante provável que você ainda precise ir até a Prefeitura para que seus dados sejam conferidos e sua identidade confirmada.<br /><br /><br>


<h2 class="tituloVermelho">Adesão ao Simples</h2>
Feio o registro na Prefeitura <a href="https://www.contadoramigo.com.br/enquadramento_simples.php">solicite, o quanto antes, sua adesão ao Simples Nacional</a>, para que você já comece a gozar dos benefícios fiscais. <b>Empresas recém-abertas têm até 30 dias para fazê-lo, </b>a contar do último deferimento de inscrição (municipal ou estadual). Se não o fizer, você será automaticamente enquadrado no Regime de Lucro Presumido e só poderá solicitar seu enquadramento no Simples em janeiro do ano seguinte.Uma vez deferido seu enquadramento no Simples, pode abrir a champanhe. Sua nova empresa estará pronta para começar!<br><br><br>

<h2 class="tituloVermelho">Nota Fiscal Eletrônica</h2>
Muito bem. Agora que sua empresa está aberta é hora de cadastrar-se na Nota Fiscal Eletrônica. Normalmente o processo funciona assim: ao obter sua inscrição municipal, a prefeitura lhe fornece uma senha. Com ela, acesse o site da Nota Fiscal Eletrônica de seu município. Em São Paulo - SP, entre no <a href="http://nfpaulistana.prefeitura.sp.gov.br/index.asp" target="_blank">site da Nota Fiscal Paulistana</a>. Você também poderá usar o certificado digital de sua empresa, se já tiver um. No primeiro acesso, é preciso configurar o perfil da empresa. Aproveite e verifique se ela já aparece cadastrada como optante pelo Simples. Feito isto, você já estará pronto para imprimir suas notas fiscais!
</div>


</div>




<?php include 'rodape.php' ?>
