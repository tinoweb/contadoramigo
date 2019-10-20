<?php
    ob_start();
    include "seguranca.php"; // Inclui o arquivo com o sistema de segurança
    protegePagina(); // Chama a função que protege a página

    require 'editar/configuracoes.inc.php';
    require 'editar/paginas.class.php';
    require 'functions/db_connect.inc.php';
    require 'functions/crud.inc.php';
	require 'functions/functions.inc.php';
    include 'functions/variaveis.inc.php';
    include("../assets/wideimage/WideImage.php");
	$base = "http://".$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],'/')+1);
?>
<!doctype html>
<html lang="pt">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="Agência Toocano - Painel Admin" />
<meta name="author" content="Toocano" />
<meta charset="UTF-8">
<base href="<?=$base?>" />
<title>Painel Gerenciador de Conteúdo</title>
<script src="js/jquery-1.11.0.min.js"></script>
<link rel="stylesheet" href="css/reset.css" />
<link rel="stylesheet" href="css/main.css" />
<!-- TEMA -->
<link rel="stylesheet" href="tema/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css"  id="style-resource-1">
<link rel="stylesheet" href="tema/css/font-icons/entypo/css/entypo.css"  id="style-resource-2">
<link rel="stylesheet" href="tema/css/font-icons/entypo/css/animation.css"  id="style-resource-3">
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic"  id="style-resource-4">
<link rel="stylesheet" href="tema/css/neon.css"  id="style-resource-5">
<!-- TEMA -->
</head>

<body class="page-body">
    <div class="page-container">
        <?php
            $functions = new Funcoes();
            $banco = new Banco();
            include 'functions/menu.inc.php';
        ?>
        <div class="main-content">
            <div class="row">
                <div class="col-md-12 clearfix hidden-xs">
                    <ul class="list-inline links-list pull-right">
                        <li>
                            <a href="logout.php">Sair <i class="entypo-logout right"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
            <hr />
            <?php
                if(isset($_GET['m']) && isset($_GET['t'])):
                    $modulo = $_GET['m'];
                    $tela = $_GET['t'];
            ?>
                <!-- MAPA -->
                <ol class="breadcrumb bc-3">
                    <li><a href="<?php echo $url_painel; ?>"><i class="entypo-home"></i>Home</a></li>
                    <li><a href="<?php echo $url_painel.'?m='.$modulo.'&t='.$tela; ?>"><?php echo ucwords(str_replace("_"," ",$modulo)); ?></a></li>
                    <li class="active"><strong><?php echo ucfirst($tela); ?></strong></li>
                </ol>
                <!-- MAPA -->
            <?php
                    # Chama a classe
                    $pagina = new Paginas($modulo);

                    echo '<h2>'.$pagina->titulo.'</h2><br>';

                    if(isset($_POST['acao_form'])):
                        # Salva dados no banco
                        if(isset($_GET['id']))
                            $db = $functions->popularCampos($modulo,$pagina->campoID,$_GET['id']);
                        else
                            $db = null;
                        switch ($acao_form):
                            case 'editar':
                                if($functions->salvarDados($pagina->campos,$_POST,$_FILES,$pagina->campoID,$db,$tela,$banco,$pagina->tabela) > 0):
                                    $functions->printMSG("Dados alterados com sucesso. <a href='?m=".$modulo."&t=listar'><strong>Exibir registros</strong>.</a>","sucesso");
                                    unset($_POST);
                                else:
                                    $functions->printMSG("Nenhum dado foi alterado. <a href='?m=".$modulo."&t=listar'><strong>Exibir registros</strong>.</a>","alerta");
                                endif;
                            break;
                            case 'inserir':
                                if($functions->salvarDados($pagina->campos,$_POST,$_FILES,$pagina->campoID,$db,$tela,$banco,$pagina->tabela) > 0):
                                    $functions->printMSG("Dados alterados com sucesso. <a href='?m=".$modulo."&t=listar'><strong>Exibir registros</strong>.</a>","sucesso");
                                    unset($_POST);
                                else:
                                    $functions->printMSG("Nenhum dado foi alterado. <a href='?m=".$modulo."&t=listar'><strong>Exibir registros</strong>.</a>","alerta");
                                endif;
                            break;
                            case 'deletar':
                                if($functions->deletarRegistro($_POST,$pagina->campoID,$banco) > 0):
                                    $functions->printMSG("Excluido com sucesso. <a href='?m=".$modulo."&t=listar'><strong>Exibir registros</strong>.</a>","sucesso");
                                    unset($_POST);
                                else:
                                    $functions->printMSG("Nenhum dado foi alterado. <a href='?m=".$modulo."&t=listar'><strong>Exibir registros</strong>.</a>","alerta");
                                endif;
                            break;
                        endswitch;
                    else:
                        switch($tela):
                            case 'listar':
                                echo $functions->gerarLista($pagina->listar,$pagina->campoID,$pagina->tabela,$modulo,$pagina->campos);
                            break;
                            case 'deletar':
                                $formulario_pronto = $functions->gerarFormDeletar($_GET['id'],$pagina->tabela,$pagina->campoID,$tela,$pagina->operacoes);
                            break;
                            case 'teste':
                                $formulario_pronto = $functions->gerarFormDeletar($_GET['id'],$pagina->tabela,$pagina->campoID,$tela,$pagina->operacoes);
                            break;
                            default:
                                # Exibe o formulário
                                if(isset($_GET['id'])):
                                    $db = $functions->popularCampos($modulo,$pagina->campoID,$_GET['id']);
                                    $formulario_pronto = $functions->gerarForm($pagina->campos,$db,$tela,$pagina->operacoes,$pagina->tabela,$pagina->campoID);
                                else:
                                    $formulario_pronto = $functions->gerarForm($pagina->campos,null,$tela,$pagina->operacoes,$pagina->tabela,$pagina->campoID);
                                endif;
                            break;
                        endswitch;
                    endif;
                else:
                   //--
                endif;

                if(isset($formulario_pronto)):
                    echo '
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-primary" data-collapsed="0">
                                <div class="panel-heading">
                                    <div class="panel-title">
                                        '.ucfirst($tela).' '.ucwords(str_replace("_"," ",$modulo)).'
                                    </div>
                                </div>
                                <div class="panel-body">';
                    echo            $formulario_pronto;
                    echo '      </div>
                            </div>
                        </div>
                    </div>';

                endif;
            ?>

            <!-- FOOTER -->
            <footer class="main">
                <div class="pull-right">
                    &copy; 2015 - Painel Gerenciador de Conteúdo 
                </div>
            </footer>
        </div>
    </div>

    <?php
        switch($tela):
            case 'listar': ?>
                <script type="text/javascript">
                    jQuery(document).ready(function($){
                        $("#lista-dados").dataTable({
                            "sPaginationType": "bootstrap",
                            "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Exibir todos"]],
                            "bStateSave": true
                        });
                    });
                </script>
    <?php   break;
        endswitch;
    ?>
    <script>

        // SLUG
        $(".slug-texto").change(function(){
            var texto = $(this).val();

            $.ajax({
                url: '../ajax/ajax.php',
                type: 'POST',
                data: {acao:'trazSlug',texto:texto},
            })
            .done(function(retorno){
                var texto = retorno.toString().replace('\t','')
                $(".slug").val(texto);
            })
        });

    </script>       
    <!-- TEMA -->
    <link rel="stylesheet" href="tema/js/wysihtml5/bootstrap-wysihtml5.css"  id="style-resource-1">
    <script src="tema/js/gsap/main-gsap.js" id="script-resource-1"></script>
    <script src="tema/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js" id="script-resource-2"></script>
    <script src="tema/js/bootstrap.min.js" id="script-resource-3"></script>
    <script src="tema/js/joinable.js" id="script-resource-4"></script>
    <script src="tema/js/resizeable.js" id="script-resource-5"></script>
    <script src="tema/js/neon-api.js" id="script-resource-6"></script>
    <script src="tema/js/fileinput.js" id="script-resource-7"></script>
    <script src="tema/js/wysihtml5/wysihtml5-0.4.0pre.min.js" id="script-resource-7"></script>
    <script src="tema/js/wysihtml5/bootstrap-wysihtml5.js" id="script-resource-8"></script>
    <script src="tema/js/jquery.sparkline.min.js" id="script-resource-9"></script>
    <script src="tema/js/bootstrap-datepicker.js" id="script-resource-11"></script>
    <script src="tema/js/raphael-min.js" id="script-resource-12"></script>
    <script src="tema/js/morris.min.js" id="script-resource-13"></script>
    <script src="tema/js/toastr.js" id="script-resource-14"></script>
    <script src="tema/js/neon-custom.js" id="script-resource-16"></script>
    <script src="tema/js/neon-demo.js" id="script-resource-17"></script>
    <script src="tema/js/jquery.dataTables.min.js" id="script-resource-7"></script>
    <script src="tema/js/dataTables.bootstrap.js" id="script-resource-8"></script>
    <!-- TEMA -->
    <script src="js/jquery.maskMoney.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
<?php 
    ob_end_flush();
?>