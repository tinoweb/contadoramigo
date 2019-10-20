<?php include 'header_restrita.php' ?>

<div class="principal">
<div class="minHeight" style="width:780px">
<div class="titulo" style="margin-bottom:20px;">Abrir ou Alterar Empresa</div>
  <span class="tituloVermelho">Alterar Sociedade Empresária Limitada</span><br />
  <br />
  No caso de sociedades com registro na Junta, o primeiro passo para alteração da empresa é redigir o novo contrato social consolidado, com a alteração. Para tal, use o <a href="alteracao_contrato.php">aplicativo de alteração contratual</a>. Em seguida, você deverá ir até a Junta  Comercial de sua cidade e solicitar o kit de alteração contratual, que inclui:<br />
  

<ul>
  <li> Formulário de requerimento</li>
  <li> Guias para recolhimento das taxas Federal (DARF) e Estadual (DARE)</li>
  <li> Lista dos documentos necessários (esta relação varia de acordo com o tipo de alteração solicitada)</li>
</ul>
Alguns estados  já possibilitam o preenchimento online do requerimento e a impressão das guias. Verifique se esta facilidade  já está disponível na sua cidade. Se você é de São Paulo, acesse o <a href="alteracao_contrato_junta_sp.php">tutorial do Via Rápida Empresa</a>.<br />
<br />
Você deverá  anexar a toda essa documentação também o <strong>DBE - Documento Básico de Entrada</strong>. Este documento pode ser gerado pela Internet. Acesse o <a href="orientacoes_dbe.php">tutorial para geração do DBE</a>. Você notará que ele contém informações semelhantes ao requerimento da Junta. O primeiro será usado para atualizar seus dados no sistema da Receita Federal e o outro para atualizá-los junto ao Estado.<br />
<br />
Leve tudo até a Junta Comercial. A documentação será analisada e, se estiver  em ordem, a alteração será homologada. Se houver alguma irregularidade, o processo retorna, para que você o complemente com a exigência solicitada. <br />
<br />
<a name="prefeitura"></a><h2 class="tituloVermelho">Atualização na Prefeitura</h2>
Depois de homologado pela Junta, seu registro já estará devidamente alterado perante o Estado e a Receita Federal. Ficará ainda faltando efetuar a alteração junto à Prefeitura. Para isso você deverá preencher <?php if( $user->getCidade() == 'São Paulo' ){ ?>o <a href="https://ccm.prefeitura.sp.gov.br/login/contribuinte?tipo=A" target="_blank">Requerimento de Atualização do CCM</a>, imprimi-lo e levá-lo na Praça de Atendimento da Secretaria de Finanças, localizada no Vale do Anhangabaú, 206/226, ao lado da Galeria Prestes Maia, de segunda a sexta-feira, das 8h às 18h. Mas atenção a entrega só pode ser feita mediante <a href="http://agendamentosf.prefeitura.sp.gov.br/forms/BemVindo.aspx" target="_blank">agendamento prévio</a>.<?php }else{ ?>um requerimento de atualização. Algumas cidades já possibilitam o preenchimento online. Verifique se esta facilidade  já está disponível em seu município. Mesmo com o preenchimento online, é bastante provável que você ainda precise ir até a Prefeitura para que seus dados sejam conferidos e sua identidade confirmada.<?php } ?>
  <br>
<br>

 <strong> Finalmente, depois de feita a alteração, não esqueça de atualizar os dados da empresa aqui no Contador Amigo para que nosso sistema possa orientá-lo corretamente no cumprimenro de suas obrigações.</strong>
  <br>
<br>

  <div class="quadro_branco"> <span class="destaque">ATENÇÃO:</span> Se estiver alterando a atividade da empresa, faça uma pesquisa no site do <a href="http://www.cnae.ibge.gov.br/" target="_blank">IBGE/Concla</a> para defini-los. Como as alíquotas variam de acordo com a atividade, procure encaixar códigos que pagam menos impostos. Além disso, é preciso verificar se a atividade  é permitida ao Simples. Acesse nossa <a href="https://www.contadoramigo.com.br/abertura_selecao_atividades.php">Página de Seleção de Atividades</a>, para fazer estas verificações.</div>
</div>
</div>
<?php include 'rodape.php' ?>
