-- phpMyAdmin SQL Dump
-- version 4.5.3.1
-- http://www.phpmyadmin.net
--
-- Host: 177.153.16.160
-- Generation Time: 05-Jun-2017 às 09:57
-- Versão do servidor: 5.6.35-80.0-log
-- PHP Version: 5.6.30-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `contadoramigo`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `mensagens_emkt`
--

CREATE TABLE `mensagens_emkt` (
  `id` int(11) NOT NULL,
  `tipo` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `html_body` text COLLATE latin1_general_ci NOT NULL,
  `text_body` text COLLATE latin1_general_ci NOT NULL,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `sender_name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `sender` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `subject` varchar(255) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `mensagens_emkt`
--

INSERT INTO `mensagens_emkt` (`id`, `tipo`, `html_body`, `text_body`, `name`, `sender_name`, `sender`, `subject`) VALUES
(2, 'assinatura_inativa', '<div style="border: #ccc 1px solid; background-color: #ffff99; width: 340px; color:#666666; font-family:arial, helvetica, sans-serif; font-size:12px"><div style="padding:15px"><img alt="Contador Amigo" height="40" src="http://www.contadoramigo.com.br/images/logo_email.png" title="Contador Amigo" width="235" /><div style="height: 1px;background: #143c62;margin: 1em 0;margin-top: 10px;width:100%;margin-bottom:20px;">&nbsp;</div>Ol&aacute;, [NOME]!<br /><br />Nosso sistema verificou uma mensalidade pendente h&aacute; mais de 5 dias e, por isso, sua assinatura do Contador Amigo est&aacute; temporariamente inativa.<br /><br />Para realizar o pagamento, acesse seus <a href="https://contadoramigo.com.br/minha_conta.php" style="color: rgb(51, 102, 153);">dados da conta</a>, localize a mensalidade pendente e clique em "recalcular boleto vencido".<br /><br />Em caso de d&uacute;vida, contate-nos pelo <a href="https://contadoramigo.com.br/suporte.php" style="color: rgb(51, 102, 153);" title="Help Desk">help desk</a>.<br /><br /><strong>Contador Amigo</strong><br /><a href="http://www.contadoramigo.com.br" style="color: rgb(51, 102, 153);">www.contadoramigo.com.br</a></div></div>', 'Prezado(a) Assinante,\\n\\nNosso sistema verificou uma mensalidade pendente há mais de 5 dias e, por isso, sua assinatura do Contador Amigo está temporariamente inativa.\\n\\nPara regularizá-la, vá em Dados da Conta, e siga as instruções. Você pode gerar um novo boleto ou atualizar os dados de seu cartão.\\n\\nEm caso de dúvida, contate-nos pelo help desk.\\n\\nContador Amigo\\nwww.contadoramigo.com.br', 'Assinatura Inativa', 'Contador Amigo', 'secretaria@contadoramigo.com.br', 'Reative sua assinatura!'),
(3, 'assinatura_reativada', '<div style="border: #ccc 1px solid; background-color: #ffff99; width: 340px; color:#666666; font-family:arial, helvetica, sans-serif; font-size:12px">   <div style="padding:15px">      <img alt="Contador Amigo" height="40" src="http://www.contadoramigo.com.br/images/logo_email.png" title="Contador Amigo" width="235" /><br>      <hr style="solid;width:100%;height: 1px;border: 0;border-top: 1px solid #ccc;margin: 1em 0;padding: 0;border-top:1px solid #143c62; margin-top:10px;" size="1px"><br />      Ol&aacute;, [NOME]! <br /><br />            Seu acesso ao Contador Amigo foi plenamente restabelecido. Você já pode utilizar todas as funcionalidades do portal.<br />      <br><br>      Em caso de d&uacute;vida, contate-nos pelo <a href="https://contadoramigo.com.br/suporte.php" style="color: rgb(51, 102, 153);" title="Help Desk">help desk</a>.      <br /><br /><strong>Contador Amigo<br /></strong><a href="http://www.contadoramigo.com.br" style="color: rgb(51, 102, 153);">www.contadoramigo.com.br</a>   </div></div>', 'Prezado(a) Assinante,\\n\\nSeu acesso ao Contador Amigo foi plenamente restabelecido.\\n\\nVocê já pode utilizar todas as funcionalidades do portal.\\n\\nEm caso de dúvida, contate-nos pelo help desk\\n\\nContador Amigo\\nwww.contadoramigo.com.br', 'Assinatura Reativada', 'Contador Amigo', 'secretaria@contadoramigo.com.br', 'Assinatura reativada'),
(4, 'boleto_a_vencer', '<div style="border: #ccc 1px solid; background-color: #ffff99; width: 340px; color:#666666; font-family:arial, helvetica, sans-serif; font-size:12px">   <div style="padding:15px">      <img alt="Contador Amigo" height="40" src="http://www.contadoramigo.com.br/images/logo_email.png" title="Contador Amigo" width="235" /><br><div style="height: 1px;background: #143c62;margin: 1em 0;margin-top: 10px;width:100%;margin-bottom:20px;"></div>Ol&aacute;, [NOME]! <br /><br />            Est&aacute; chegando a hora de quitar a mensalidade do Contador Amigo. Clique <a href="https://contadoramigo.com.br/minha_conta.php" style="color: rgb(51, 102, 153);">aqui</a> para imprimir seu boleto.<br />      <br />      Para sua comodidade, voc&ecirc; pode a qualquer momento optar pelo d&eacute;bito autom&aacute;tico no cart&atilde;o de cr&eacute;dito. A nova op&ccedil;&atilde;o ser&aacute; v&aacute;lida j&aacute; para sua pr&oacute;xima fatura.<br />      <br />            <br />      Em caso de d&uacute;vida, contate-nos pelo <a href="https://contadoramigo.com.br/suporte.php" style="color: rgb(51, 102, 153);" title="Help Desk">help desk</a>.      <br /><br /><strong>Contador Amigo<br /></strong><a href="http://www.contadoramigo.com.br" style="color: rgb(51, 102, 153);">www.contadoramigo.com.br</a>   </div></div>', 'Prezado(a) Assinante,\\n\\nEstá chegando a hora de quitar a mensalidade do Contador Amigo. Acesse a página Dados da Conta para imprimir seu boleto.\\n\\nPara sua comodidade, você pode a qualquer momento optar pelo débito automático no cartão de crédito. A nova opção será válida já para sua próxima fatura.\\n\\nEm caso de dúvida, contate-nos pelo help desk\\n\\nContador Amigo\\nwww.contadoramigo.com.br', 'Boleto a Vencer', 'Contador Amigo', 'secretaria@contadoramigo.com.br', 'Seu boleto chegou'),
(5, 'boleto_compensado', '<div style="border: #ccc 1px solid; background-color: #ffff99; width: 340px; color:#666666; font-family:arial, helvetica, sans-serif; font-size:12px">   <div style="padding:15px">      <img alt="Contador Amigo" height="40" src="http://www.contadoramigo.com.br/images/logo_email.png" title="Contador Amigo" width="235" /><br><div style="height: 1px;background: #143c62;margin: 1em 0;margin-top: 10px;width:100%;margin-bottom:20px;"></div>Ol&aacute;, [NOME]! <br /><br />            Recebemos o pagamento do boleto referente &agrave; sua assinatura mensal no Portal Contador Amigo. A nota fiscal ser&aacute; enviada por email dentro de alguns dias. Obrigado!<br />                  <br />      Em caso de d&uacute;vida, contate-nos pelo <a href="https://contadoramigo.com.br/suporte.php" style="color: rgb(51, 102, 153);" title="Help Desk">help desk</a>.      <br /><br /><strong>Contador Amigo<br /></strong><a href="http://www.contadoramigo.com.br" style="color: rgb(51, 102, 153);">www.contadoramigo.com.br</a>   </div></div>', 'Prezado(a) Assinante!\\n\\nRecebemos o pagamento do boleto referente à sua assinatura mensal no Portal Contador Amigo. A nota fiscal será enviada por email dentro de alguns dias. Obrigado!\\n\\nEm caso de dúvida, contate-nos pelo help desk.\\n\\nContador Amigo\\nwww.contadoramigo.com.br', 'Boleto Compensado', 'Contador Amigo', 'secretaria@contadoramigo.com.br', 'Boleto compensado'),
(6, 'cartao_autorizado', '<div style="border: #ccc 1px solid; background-color: #ffff99; width: 340px; color:#666666; font-family:arial, helvetica, sans-serif; font-size:12px">   <div style="padding:15px">      <img alt="Contador Amigo" height="40" src="http://www.contadoramigo.com.br/images/logo_email.png" title="Contador Amigo" width="235" /><br><div style="height: 1px;background: #143c62;margin: 1em 0;margin-top: 10px;width:100%;margin-bottom:20px;"></div>Ol&aacute;, [NOME]! <br /><br />            Efetuamos com sucesso o d&eacute;bito em seu cart&atilde;o referente &agrave; assinatura mensal do Contador Amigo. A nota fiscal ser&aacute; enviada por email dentro de alguns dias.<br />                  <br />      Em caso de d&uacute;vida, contate-nos pelo <a href="https://contadoramigo.com.br/suporte.php" style="color: rgb(51, 102, 153);" title="Help Desk">help desk</a>.      <br /><br /><strong>Contador Amigo<br /></strong><a href="http://www.contadoramigo.com.br" style="color: rgb(51, 102, 153);">www.contadoramigo.com.br</a>   </div></div>', 'Prezado(a) Assinante,\\n\\nEfetuamos com sucesso o débito em seu cartão referente à assinatura mensal do Contador Amigo. A nota fiscal será enviada por email dentro de alguns dias.\\n\\nEm caso de dúvida, contate-nos pelo help desk.\\n\\nContador Amigo\\nwww.contadoramigo.com.br', 'Cartão Autorizado', 'Contador Amigo', 'secretaria@contadoramigo.com.br', 'Cartão autorizado'),
(7, 'demo_info_15', '<div style="border: #ccc 1px solid; background-color: #ffff99; widh:100%;max-width:600px; color:#666666; font-family:arial, helvetica, sans-serif; font-size:12px">   <div style="padding:15px">      <img alt="Contador Amigo" height="40" src="http://www.contadoramigo.com.br/images/logo_email.png" title="Contador Amigo" width="235" /><br><div style="height: 1px;background: #143c62;margin: 1em 0;margin-top: 10px;width:100%;margin-bottom:20px;"></div>Ol&aacute;, [NOME]! <br /><br />            H&aacute; alguns dias voc&ecirc; iniciou o teste gr&aacute;tis do Contador Amigo. Esperamos que esteja gostando! Pode parecer um pouco complicado       no come&ccedil;o, mas n&atilde;o desista! A economia &eacute; substancial e logo voc&ecirc; estar&aacute; dominando todo o processo.       <br><br>      Ali&aacute;s, <strong>entender como funciona a contabilidade de sua empresa &eacute; fundamental para seu neg&oacute;cio</strong>, mesmo se no futuro voc&ecirc; optar por um contador.       <br><br> Nessa fase inicial, colocamos &agrave; sua disposi&ccedil;&atilde;o <strong>o nosso n&uacute;mero (11) 3434-6631</strong>. Ligue para a gente. Vamos bater um       papo e esclarecer suas d&uacute;vidas.      <br><br>      Se estiver com dificuldade para gerar as guias de seus impostos, lembre-se ainda que voc&ecirc; pode contar com o <strong>suporte remoto</strong>. Atrav&eacute;s dele, nossa       equipe acessar&aacute; seu computador e far&aacute; todas as configura&ccedil;&otilde;es necess&aacute;rias.      <br><br>      Para que voc&ecirc; possa assumir definitivamente o controle sobre suas obriga&ccedil;&otilde;es ficais, no entanto, &eacute; necess&aacute;rio       que j&aacute; tenha adquirido o <a href="https://www.contadoramigo.com.br/certificado_digital.php" style="color: rgb(51, 102, 153);">Certificado Digital</a>. <br />             <br />      Em caso de d&uacute;vida, contate-nos pelo <a href="https://contadoramigo.com.br/suporte.php" style="color: rgb(51, 102, 153);" title="Help Desk">help desk</a>.      <br /><br /><strong>Contador Amigo<br /></strong><a href="http://www.contadoramigo.com.br" style="color: rgb(51, 102, 153);">www.contadoramigo.com.br</a>   </div></div>', 'Prezado(a) Assinante,\\n\\nHá alguns dias você iniciou o teste grátis do Contador Amigo. Esperamos que esteja gostando! Pode parecer um pouco complicado no começo, mas não desista! A economia é substancial e logo você estará dominando todo o processo. \\n\\nAliás, entender como funciona a contabilidade de sua empresa é fundamental para seu negócio, mesmo se no futuro você optar por um contador. \\n\\nNessa fase inicial, colocamos à sua disposição o nosso número (11) 3434-6631. Ligue para a gente. Vamos bater um papo e esclarecer suas dúvidas. \\n\\nSe estiver com dificuldade para gerar as guias de seus impostos, lembre-se ainda que você pode contar com o suporte remoto. Através dele, nossa equipe acessará seu computador e fará todas as configurações necessárias. \\n\\nPara que você possa assumir definitivamente o controle sobre suas obrigações ficais, no entanto, é necessário que já tenha adquirido o Certificado Digital. \\n\\nQualquer dúvida, consulte-nos pelo help desk. \\n\\nContador Amigo\\nwww.contadoramigo.com.br', 'Informação para os Demos 15 dias', 'Contador Amigo', 'secretaria@contadoramigo.com.br', 'Promoção 15 dias'),
(8, 'demo_inativo_info', '<div style="border: #ccc 1px solid; background-color: #ffff99; widh:100%;max-width:600px; color:#666666; font-family:arial, helvetica, sans-serif; font-size:12px">   <div style="padding:15px">      <img alt="Contador Amigo" height="40" src="http://www.contadoramigo.com.br/images/logo_email.png" title="Contador Amigo" width="235" /><br><div style="height: 1px;background: #143c62;margin: 1em 0;margin-top: 10px;width:100%;margin-bottom:20px;"></div>Ol&aacute;, [NOME]! <br /><br />            H&aacute; alguns dias voc&ecirc; iniciou o teste gr&aacute;tis do Contador Amigo. Esperamos que esteja gostando! Pode parecer um pouco complicado       no come&ccedil;o, mas n&atilde;o desista! A economia &eacute; substancial e logo voc&ecirc; estar&aacute; dominando todo o processo.       <br><br>      Ali&aacute;s, <strong>entender como funciona a contabilidade de sua empresa &eacute; fundamental para seu neg&oacute;cio</strong>, mesmo se no futuro voc&ecirc; optar por um contador.       <br><br> Nessa fase inicial, colocamos &agrave; sua disposi&ccedil;&atilde;o <strong>o nosso n&uacute;mero (11) 3434-6631</strong>. Ligue para a gente. Vamos bater um       papo e esclarecer suas d&uacute;vidas.      <br><br>      Se estiver com dificuldade para gerar as guias de seus impostos, lembre-se ainda que voc&ecirc; pode contar com o <strong>suporte remoto</strong>. Atrav&eacute;s dele, nossa       equipe acessar&aacute; seu computador e far&aacute; todas as configura&ccedil;&otilde;es necess&aacute;rias.      <br><br>      Para que voc&ecirc; possa assumir definitivamente o controle sobre suas obriga&ccedil;&otilde;es ficais, no entanto, &eacute; necess&aacute;rio       que j&aacute; tenha adquirido o <a href="https://www.contadoramigo.com.br/certificado_digital.php" style="color: rgb(51, 102, 153);">Certificado Digital</a>, ou a <a href="https://www.contadoramigo.com.br/gfip_obtencao_chave_pri.php" style="color: rgb(51, 102, 153);">Chave de acesso PRI</a> gratuita. <br />             <br />      Em caso de d&uacute;vida, contate-nos pelo <a href="https://contadoramigo.com.br/suporte.php" style="color: rgb(51, 102, 153);" title="Help Desk">help desk</a>.      <br /><br /><strong>Contador Amigo<br /></strong><a href="http://www.contadoramigo.com.br" style="color: rgb(51, 102, 153);">www.contadoramigo.com.br</a>   </div></div>', 'Prezado(a) Assinante,\\n\\nHá alguns dias você iniciou o teste grátis do Contador Amigo. Esperamos que esteja gostando! Pode parecer um pouco complicado no começo, mas não desista! A economia é substancial e logo você estará dominando todo o processo. \\n\\nAliás, entender como funciona a contabilidade de sua empresa é fundamental para seu negócio, mesmo se no futuro você optar por um contador. \\n\\nNessa fase inicial, colocamos à sua disposição o nosso número (11) 3434-6631. Ligue para a gente. Vamos bater um papo e esclarecer suas dúvidas. \\n\\nSe estiver com dificuldade para gerar as guias de seus impostos, lembre-se ainda que você pode contar com o suporte remoto. Através dele, nossa equipe acessará seu computador e fará todas as configurações necessárias. \\n\\nPara que você possa assumir definitivamente o controle sobre suas obrigações ficais, no entanto, é necessário que já tenha adquirido o Certificado Digital, ou a Chave de acesso PRI gratuita. \\n\\nQualquer dúvida, consulte-nos pelo help desk. \\n\\nContador Amigo\\nwww.contadoramigo.com.br', 'Informação para os Demos Inativos', 'Contador Amigo', 'secretaria@contadoramigo.com.br', 'Informação para os Demos Inativos'),
(9, 'demo_inativo', '<div style="border: #ccc 1px solid; background-color: #ffff99; width: 340px; color:#666666; font-family:arial, helvetica, sans-serif; font-size:12px">   <div style="padding:15px">      <img alt="Contador Amigo" height="40" src="http://www.contadoramigo.com.br/images/logo_email.png" title="Contador Amigo" width="235" /><br>      <hr style="solid;width:100%;height: 1px;border: 0;border-top: 1px solid #ccc;margin: 1em 0;padding: 0;border-top:1px solid #143c62; margin-top:10px;" size="1px"><br />      Olá, [NOME]! <br /><br />      O período gratuito de sua assinatura do Contador Amigo expirou e seu acesso está temporariamente suspenso. Esperamos que o portal tenha atendido suas expectativas e possamos tê-lo como nosso assinante efetivo daqui para frente.<br />      <br />      Para confirmar sua adesão, vá em <a href="https://contadoramigo.com.br/minha_conta.php" style="color: rgb(51, 102, 153);" title="Dados da Conta">Dados da Conta</a> e siga as instruções. Você poderá optar pelo pagamento por cartão ou boleto, conforme sua conveniência.<br />      <br />      Em caso de d&uacute;vida, contate-nos pelo <a href="https://contadoramigo.com.br/suporte.php" style="color: rgb(51, 102, 153);" title="Help Desk">help desk</a>.      <br /><br /><strong>Contador Amigo<br /></strong><a href="http://www.contadoramigo.com.br" style="color: rgb(51, 102, 153);">www.contadoramigo.com.br</a>   </div></div>', 'Prezado(a) Assinante,\\n\\nHá alguns dias você iniciou o teste grátis do Contador Amigo. Esperamos que esteja gostando! Pode parecer um pouco complicado no começo, mas não desista! A economia é substancial e logo você estará dominando todo o processo. \\n\\nAliás, entender como funciona a contabilidade de sua empresa é fundamental para seu negócio, mesmo se no futuro você optar por um contador. \\n\\nNessa fase inicial, colocamos à sua disposição o nosso número (11) 3434-6631. Ligue para a gente. Vamos bater um papo e esclarecer suas dúvidas. \\n\\nSe estiver com dificuldade para gerar as guias de seus impostos, lembre-se ainda que você pode contar com o suporte remoto. Através dele, nossa equipe acessará seu computador e fará todas as configurações necessárias. \\n\\nPara que você possa assumir definitivamente o controle sobre suas obrigações ficais, no entanto, é necessário que já tenha adquirido o Certificado Digital, ou a Chave de acesso PRI gratuita. \\n\\nQualquer dúvida, consulte-nos pelo help desk. \\n\\nContador Amigo\\nwww.contadoramigo.com.br', 'Demos Inativos', 'Contador Amigo', 'secretaria@contadoramigo.com.br', 'Confirme sua assinatura'),
(10, 'demo_info', '<div style="border: #ccc 1px solid; background-color: #ffff99; width: 600px; color:#666666; font-family:arial, helvetica, sans-serif; font-size:12px"><div style="padding:15px"><img alt="Contador Amigo" height="40" src="http://www.contadoramigo.com.br/images/logo_email.png" title="Contador Amigo" width="235" /><hr size="1px" style="solid;width:100%;height: 1px;border: 0;border-top: 1px solid #ccc;margin: 1em 0;padding: 0;border-top:1px solid #143c62; margin-top:10px;" /><br />Ol&aacute;, [NOME]!<br /><br />H&aacute; alguns dias voc&ecirc; iniciou o teste gr&aacute;tis do <strong>Contador Amigo</strong>. Esperamos que esteja gostando!<br /><br />Voc&ecirc; j&aacute; deve ter percebido que, para cumprir sozinho com suas obriga&ccedil;&otilde;es fiscais, tudo o que precisa &eacute; de um <strong>Certificado Digital</strong>. Com ele, voc&ecirc; acessa os sistemas da Receita Federal e da Previd&ecirc;ncia e pode gerar as guias de seus impostos.<br /><br />Assinantes do Contador Amigo podem adquiri-lo pela <strong>Valid Certificadora</strong> com um super desconto: apenas R$ 189,75 em 3 x sem juros. <a href="http://www.validcertificadora.com.br/e-CNPJ-A1.htm/388C546B-98DA-4A0E-B8F3-89BBA8FB5FEE/RD007797" style="color: rgb(51, 102, 153);">Solicite agora mesmo o seu</a>.&nbsp;<br /><br />Em caso de d&uacute;vida, contate-nos pelo <a href="https://contadoramigo.com.br/suporte.php" style="color: rgb(51, 102, 153);" title="Help Desk">help desk</a>.<br /><br /><strong>Contador Amigo</strong><br /><a href="http://www.contadoramigo.com.br" style="color: rgb(51, 102, 153);">www.contadoramigo.com.br</a></div></div>', 'Prezado Assinante\\n\\n Há alguns dias você iniciou o teste grátis do Contador Amigo. Esperamos que esteja gostando! \\n\\n Você já deve ter percebido que, para cumprir sozinho com suas obrigações fiscais, tudo o que precisa é de um Certificado Digital. Com ele, você acessa os sistemas da Receita Federal e da Previdência e pode gerar as guias de seus impostos. \\n\\n Assinantes do Contador Amigo podem adquir o certificado E-CNPJ A1 com um super desconto: apenas R$ 189,75 em 3 vezes sem juros. Confirme agora mesmo sua assinatura e aproveite esta promoção! \\n\\n Em caso de dúvida, contate-nos pelo help desk. \\n\\n Contador Amigo\\n\\n www.contadoramigo.com.br', 'Informação para os Demos 7 dias', 'Contador Amigo', 'secretaria@contadoramigo.com.br', 'Certificado Digital com um super deconto'),
(11, 'demo_a_vencer', '<div style="border: #ccc 1px solid; background-color: #ffff99; width: 340px; color:#666666; font-family:arial, helvetica, sans-serif; font-size:12px">   <div style="padding:15px">      <img alt="Contador Amigo" height="40" src="http://www.contadoramigo.com.br/images/logo_email.png" title="Contador Amigo" width="235" /><br><div style="height: 1px;background: #143c62;margin: 1em 0;margin-top: 10px;width:100%;margin-bottom:20px;"></div>Ol&aacute;, [NOME]! <br /><br />            O per&iacute;odo gratuito de sua assinatura do Contador Amigo est&aacute; prestes a expirar. Esperamos que o portal tenha atendido suas expectativas e possamos t&ecirc;-lo como nosso assinante efetivo daqui para frente.<br />      <br />      Para confirmar sua ades&atilde;o, v&aacute; em <a href="https://contadoramigo.com.br/minha_conta.php" style="color: rgb(51, 102, 153);" title="Dados da Conta">Dados da Conta</a> e siga as instru&ccedil;&otilde;es. Voc&ecirc; poder&aacute; optar pelo pagamento por cart&atilde;o ou boleto, conforme sua conveni&ecirc;ncia.<br />                  <br />      Em caso de d&uacute;vida, contate-nos pelo <a href="https://contadoramigo.com.br/suporte.php" style="color: rgb(51, 102, 153);" title="Help Desk">help desk</a>.      <br /><br /><strong>Contador Amigo<br /></strong><a href="http://www.contadoramigo.com.br" style="color: rgb(51, 102, 153);">www.contadoramigo.com.br</a>   </div></div>', 'Prezado(a) Assinante,\\n\\nO período gratuito de sua assinatura do Contador Amigo está prestes a expirar. Esperamos que o portal tenha atendido suas expectativas e possamos tê-lo como nosso assinante efetivo daqui para frente.\\n\\nPara confirmar sua adesão, vá em Dados da Conta e siga as instruções. Você poderá optar pelo pagamento por cartão ou boleto, conforme sua conveniência.\\n\\nEm caso de dúvida, contate-nos pelo help desk.\\n\\nContador Amigo\\nwww.contadoramigo.com.br', 'Demos a Vencer', 'Contador Amigo', 'secretaria@contadoramigo.com.br', 'Período gratuito prestes a expirar'),
(12, 'cartao_nao_autorizado', '<div style="border: #ccc 1px solid; background-color: #ffff99; width: 340px; color:#666666; font-family:arial, helvetica, sans-serif; font-size:12px"><div style="padding:15px"><img alt="Contador Amigo" height="40" src="http://www.contadoramigo.com.br/images/logo_email.png" title="Contador Amigo" width="235" /><div style="height: 1px;background: #143c62;margin: 1em 0;margin-top: 10px;width:100%;margin-bottom:20px;">&nbsp;</div>Ol&aacute;, [NOME]!<br /><br />O d&eacute;bito em seu cart&atilde;o, referente &agrave; assinatura mensal do Contador Amigo, n&atilde;o foi autorizado.<br /><br />Para fazer nova tentativa, v&aacute; em <a href="https://contadoramigo.com.br/minha_conta.php" style="color: rgb(51, 102, 153);" title="Dados da Conta">dados da conta</a>, localize a mensalidade pendente e clique em &quot;Pagar&quot;. Se o erro persistir, confira se as informa&ccedil;&otilde;es do cart&atilde;o est&atilde;o corretas, ou entre em contato com sua operadora.<br /><br />Lembramos que voc&ecirc; pode, a qualquer momento, alterar a forma de pagamento para boleto.<br /><br />Em caso de d&uacute;vida, contate-nos pelo <a href="https://contadoramigo.com.br/suporte.php" style="color: rgb(51, 102, 153);" title="Help Desk">help desk</a>.<br /><br /><strong>Contador Amigo</strong><br /><a href="http://www.contadoramigo.com.br" style="color: rgb(51, 102, 153);">www.contadoramigo.com.br</a></div></div>', 'Prezado(a) Assinante,\\n\\nO débito em seu cartão, referente à assinatura mensal do Contador Amigo, não foi autorizado.\\n\\nPara fazer nova tentativa, vá em Dados da Conta, confira se a informações do cartão estão corretas e  clique no botão \\"Quitar Débito em Atraso\\". Se o erro persisitir, contate sua operadora ou altere a forma de pagamento para boleto.\\n\\nLembramos que após 5 dias de atraso, o acesso ao portal será bloqueado.\\n\\nEm caso de dúvida, contate-nos pelo help desk.\\n\\nContador Amigo\\nwww.contadoramigo.com.br', 'Cartão não Autorizado', 'Contador Amigo', 'secretaria@contadoramigo.com.br', 'Cartão não autorizado'),
(13, 'user_vencidos', '<div style="border: #ccc 1px solid; background-color: #ffff99; width: 340px; color:#666666; font-family:arial, helvetica, sans-serif; font-size:12px"><div style="padding:15px"><img alt="Contador Amigo" height="40" src="http://www.contadoramigo.com.br/images/logo_email.png" title="Contador Amigo" width="235" /><div style="height: 1px;background: #143c62;margin-top: 10px;width:100%;margin-bottom:20px;">&nbsp;</div>Ol&aacute;, [NOME]!<br /><br />Verificamos em nosso sistema que sua assinatura do Contador Amigo est&aacute; vencida. Procure regulariz&aacute;-la assim que poss&iacute;vel, evitando a desativa&ccedil;&atilde;o acidental de sua conta. Para realizar o pagamento, acesse seus <a href="https://contadoramigo.com.br/minha_conta.php" style="color: rgb(51, 102, 153);">dados da conta</a>, localize a mensalidade pendente e clique em &quot;recalcular boleto vencido&quot;.<br /><br />Se o pagamento j&aacute; foi efetuado, por favor desconsidere esta mensagem.<br /><br />Em caso de d&uacute;vida, contate-nos pelo <a href="https://contadoramigo.com.br/suporte.php" style="color: rgb(51, 102, 153);" title="Help Desk">help desk</a>.<br /><br /><strong>Contador Amigo</strong><br /><a href="http://www.contadoramigo.com.br" style="color: rgb(51, 102, 153);">www.contadoramigo.com.br</a></div></div>', 'Prezado(a) Assinante,\\n\\n Verificamos em nosso sistema que sua assinatura do Contador Amigo esta vencida.  Procure regulariz&aacute;-la, assim que poss&iacute;vel, evitando a desativa&ccedil;&atilde;o acidental de sua conta. Para realizar o pagamento, acesse seus da dados de conta, localize a mensalidade pendente e clique em recalcular boleto vencido.\\n\\nCaso j&aacute; tenha realizado o pagamento, desconsidere esta mensagem.\\n\\nEm caso de dúvida, contate-nos pelo help desk.\\n\\nContador Amigo\\nwww.contadoramigo.com.br', 'Assinatura Vencida', 'Contador Amigo', 'secretaria@contadoramigo.com.br', 'Regularize sua assinatura');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mensagens_emkt`
--
ALTER TABLE `mensagens_emkt`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mensagens_emkt`
--
ALTER TABLE `mensagens_emkt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;