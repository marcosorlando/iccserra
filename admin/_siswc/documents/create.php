<?php
    
    $AdminLevel = LEVEL_WC_DOCUMENTS;
    if (!APP_DOCUMENTS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
        die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
    endif;
    
    // AUTO INSTANCE OBJECT READ
    if (empty($Read)):
        $Read = new Read;
    endif;
    
    // AUTO INSTANCE OBJECT CREATE
    if (empty($Create)):
        $Create = new Create;
    endif;
    
    $docId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($docId):
        $Read->ExeRead(DB_DOCUMENTS, "WHERE doc_id = :id", "id={$docId}");
        if ($Read->getResult()):
            $FormData = array_map('htmlspecialchars', $Read->getResult()[0]);
            extract($FormData);
        else:
            $_SESSION['trigger_controll'] = Erro(
                "<b>OPPSS {$Admin['user_name']}</b>, você tentou editar uma página que não existe ou que foi removida recentemente!",
                E_USER_NOTICE
            );
            header('Location: dashboard.php?wc=documents/home');
            exit;
        endif;
    else:
        $docCreate = ['doc_create' => date('Y-m-d H:i:s'), 'doc_status' => 0];
        $Create->ExeCreate(DB_DOCUMENTS, $docCreate);
        header('Location: dashboard.php?wc=documents/create&id=' . $Create->getResult());
        exit;
    endif;
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-file-pdf"><?= $doc_title ? : 'Novo Documento'; ?></h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=documents/home">Documentos</a>
            <span class="crumb">/</span>
            Gerenciar Documento
        </p>
    </div>

    <div class="dashboard_header_search">
        <a title="Replicar Documento"
           href="dashboard.php?wc=documents/reply&id=<?= $docId; ?>"
           class="btn btn_blue icon-copy">Replicar!</a>
        <a title="Novo Documento" href="dashboard.php?wc=documents/create" class="btn btn_green icon-plus">Adicionar</a>

        <a target="_blank" title="Ver no site" href="<?= BASE; ?>/<?= $doc_name; ?>"
           class="wc_view btn btn_blue icon-eye float-right">Ver
            no site!</a>
    </div>
</header>

<div class="workcontrol_imageupload none" id="post_control">
    <div class="workcontrol_imageupload_content">
        <form name="workcontrol_post_upload" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="callback" value="Documents"/>
            <input type="hidden" name="callback_action" value="sendimage"/>
            <input type="hidden" name="doc_id" value="<?= $docId; ?>"/>
            <div class="upload_progress none"
                 style="padding: 5px; background: #00B594; color: #fff; width: 0%; text-align: center; max-width: 100%;">
                0%
            </div>
            <div style="overflow: auto; max-height: 300px;">
                <img class="image image_default" alt="Nova Imagem" title="Nova Imagem"
                     src="../tim.php?src=admin/_img/no_image.jpg&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>"
                     default="../tim.php?src=admin/_img/no_image.jpg&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>"/>
            </div>
            <div class="workcontrol_imageupload_actions">
                <input class="wc_loadimage" type="file" name="image" required/>
                <span class="workcontrol_imageupload_close icon-cancel-circle btn btn_red" id="post_control"
                      style="margin-right: 8px;">Fechar</span>
                <button class="btn btn_green icon-image">Enviar e Inserir!</button>
                <img class="form_load none" style="margin-left: 10px;" alt="Enviando Requisição!"
                     title="Enviando Requisição!" src="_img/load.gif"/>
            </div>
            <div class="clear"></div>
        </form>
    </div>
</div>

<div class="dashboard_content">

    <form class="auto_save" name="doc_add" action="" method="post" enctype="multipart/form-data">
      
        <input type="hidden" name="callback" value="Documents"/>
        <input type="hidden" name="callback_action" value="manage"/>
        <input type="hidden" name="doc_id" value="<?= $docId; ?>"/>

        <div class="box box70">

            <div class="panel_header default">
                <h2 class="icon-file-pdf">Insira as informações do Documento</h2>
            </div>

            <div class="panel">
                <label class="label">
                    <span class="legend">Título do Documento:</span>
                    <input class="font_medium" type="text" name="doc_title" value="<?= $doc_title; ?>"
                           placeholder="Título do documento:" required/>
                </label>
           
                <div class="label_33">
                    <label class="label">
                        <span class="legend">Cor do Botão:</span>
                        <input type="text" name="doc_btn_bg_color" class="btnPicker" value="<?= $doc_btn_bg_color; ?>"
                               placeholder="Hexadecimal da Cor" required/>
                        <div id='btnPicker' class='none'></div>
                    </label>

                    <label class="label">
                        <span class="legend">Botão ao Passar Mouse:</span>
                        <input type="text" name="doc_btn_bg_color_hover" class="hoverPicker"
                               value="<?= $doc_btn_bg_color_hover; ?>"
                               placeholder="Hexadecimal da Cor" required/>
                        <div id='hoverPicker' class='none'></div>
                    </label>

                    <label class='label'>
                        <span class='legend'>Cor do texto no Botão:</span>
                        <input type='text' class='textPicker' name='doc_btn_text_color'
                               value="<?= $doc_btn_text_color; ?>"
                               placeholder='Hexadecimal da Cor' required/>
                        <div id='textPicker' class='none'></div>
                    </label>

                </div>


                <div class="label_50">
                    <label class="label">
                        <span class="legend">Texto do Botão:</span>
                        <input type="text" name="doc_btn_text" value="<?= $doc_btn_text; ?>"
                               placeholder="Texto do Botão" required/>
                    </label>

                    <label for="doc_category" class="label">
                        <span class='legend'>Selecione a Categoria:</span>
                        <select name="doc_category" id="doc_category" required>
                            
                            <?php
                                $Read->ExeRead(DB_DOCS_CATEGORIES);
                                if (!$Read->getResult()) {
                                    echo "<option value='' disabled selected>Cadastre algumas Categorias</option>";
                                } else {
                                    echo "<option value='' disabled selected>SELECIONE UMA CATEGORIA</option>";
                                    foreach ($Read->getResult() as $Cats) {
                                        $selected = $doc_category == $Cats['category_id'] ? "selected='1'" : '';
                                        
                                        echo "<option value='{$Cats['category_id']}' {$selected}>{$Cats['category_title']}</option>";
                                    }
                                }
                            ?>

                        </select>

                    </label>


                </div>
                
                <?php
                    if (APP_LINK_PAGES): ?>
                        <label class="label">
                            <span class="legend">Link Alternativo (Opcional):</span>
                            <input id="doc_add" type="text" name="doc_name" value="<?= $doc_name; ?>"
                                   placeholder="Link do Documento:"/>
                        </label>
                    <?php
                    endif; ?>

                <div class="clear"></div>
            </div>
        </div>

        <div class="box box30">

            <div class="panel_header default">
                <h2>Capa do Doc</h2>
            </div>

            <div class="panel">

                <label class="label">
                    <span class="legend">Capa - Opcional<b> (1200X628px):</b></span>
                    <input type="file" class="wc_loadimage" name="doc_cover"/>
                </label>
                
                <div class="post_create_cover m_botton">
                    <div class="upload_progress none">0%</div>
                    <?php
                        $docCover = (!empty($doc_cover) && file_exists(
                            "../uploads/documents/{$doc_cover}"
                        ) && !is_dir(
                            "../uploads/documents/{$doc_cover}"
                        ) ? "uploads/documents/{$doc_cover}" : 'admin/_img/no_image.jpg');
                    ?>
                    <img class="post_thumb doc_cover" id="doc_cover" callback="Documents" alt="Background"
                         title="Background"
                         src="../tim.php?src=<?= $docCover; ?>&w=<?= IMAGE_W / 2; ?>&h=<?= IMAGE_H / 2; ?>"
                         default="../tim.php?src=<?= $docCover; ?>&w=<?= IMAGE_W / 2; ?>&h=<?= IMAGE_H / 2; ?>"/>
                </div>

                <label class='label' for='doc_pdf'>
                    <span class='legend'>Arquivo para Download: <b>(PDF)</b></span>
                    <input type='file' id='doc_pdf' name='doc_pdf'/>
                </label>

                <div class="m_top">&nbsp;</div>
                <div class="wc_actions" style="text-align: center; margin-bottom: 10px;">
                    <label class="label_check label_publish <?= ($doc_status == 1 ? 'active' : ''); ?>"><input
                                style="margin-top: -1px;" type="checkbox" value="1"
                                name="doc_status" <?= ($doc_status == 1 ? 'checked' : ''); ?>>
                        Publicar Agora!</label>
                    <button name="public" value="1" class="btn btn_green icon-share">ATUALIZAR</button>
                    <img class="form_load none" style="margin-left: 10px;" alt="Enviando Requisição!"
                         title="Enviando Requisição!" src="_img/load.gif"/>
                </div>
            </div>
        </div>
    </form>
</div>
<script src='./_js/iro.min.js'></script>
<script>
    /*  IRO - Seletor de Cores HexaString*  *******/
    // Box & hue slider TEXT OF BUTTON
    var boxWidth = document.getElementById('textPicker').parentElement;

    var textPicker = new iro.ColorPicker('#textPicker', {
        width: boxWidth.clientWidth,
        color: 'rgb(255, 0, 0)',
        borderWidth: 2,
        borderColor: '#FFFFFF',
        layout: [
            {
                component: iro.ui.Box,
            },
            {
                component: iro.ui.Slider,
                options: {
                    sliderType: 'hue'
                }
            }
        ]
    });

    $('.textPicker').on('focus', function () {
        $('#textPicker').slideDown()
        textPicker.on('input:end', function (color) {
            $('.textPicker').val(color.hexString).css({
                backgroundColor: color.hexString,
                color: 'white'
            })
        });
    });
    $('.textPicker').on('blur', function () {
        $('#textPicker').slideUp();
    });

    //btnPicker: modifica cor de fundo do botão do formulário
    var btnPicker = new iro.ColorPicker('#btnPicker', {
        width: boxWidth.clientWidth,
        color: 'rgb(255, 0, 0)',
        borderWidth: 2,
        borderColor: '#EEEEEE',
        layout: [
            {
                component: iro.ui.Box,
            },
            {
                component: iro.ui.Slider,
                options: {
                    sliderType: 'hue'
                }
            }
        ]
    });
    $('.btnPicker').on('focus', function () {
        $('#btnPicker').slideDown()
        btnPicker.on('input:end', function (color) {
            $('.btnPicker').val(color.hexString).css({
                backgroundColor: color.hexString,
                color: 'white'
            })
        });
    });
    $('.btnPicker').on('blur', function () {
        $('#btnPicker').slideUp();
    });

    //hoverPicker: modifica cor de fundo do botão do formulário no :hover
    var hoverPicker = new iro.ColorPicker('#hoverPicker', {
        width: boxWidth.clientWidth,
        color: 'rgb(255, 0, 0)',
        borderWidth: 2,
        borderColor: '#EEEEEE',
        layout: [
            {
                component: iro.ui.Box,
            },
            {
                component: iro.ui.Slider,
                options: {
                    sliderType: 'hue'
                }
            }
        ]
    });
    $('.hoverPicker').on('focus', function () {
        $('#hoverPicker').slideDown()
        hoverPicker.on('input:end', function (color) {
            $('.hoverPicker').val(color.hexString).css({
                backgroundColor: color.hexString,
                color: 'white'
            })
        });
    });
    $('.hoverPicker').on('blur', function () {
        $('#hoverPicker').slideUp();
    });

    $(function () {

        $('.btnPicker').css({backgroundColor: $('.btnPicker').val(), color: '#cccccc', textTransform: 'uppercase'})
        $('.hoverPicker').css({backgroundColor: $('.hoverPicker').val(), color: '#cccccc', textTransform: 'uppercase'})
        $('.textPicker').css({backgroundColor: $('.textPicker').val(), color: '#cccccc', textTransform: 'uppercase'})

    });


    //END IRO
</script>
