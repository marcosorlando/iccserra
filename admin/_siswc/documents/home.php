<?php
    
    $AdminLevel = LEVEL_WC_DOCUMENTS;
    if (!APP_DOCUMENTS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
        die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
    endif;
    
    // AUTO INSTANCE OBJECT READ
    if (empty($Read)):
        $Read = new Read;
    endif;
    
    //AUTO DELETE DOC TRASH
    if (DB_AUTO_TRASH):
        $Delete = new Delete;
        
        //AUTO TRASH IMAGES
        $Read->FullRead(
            "SELECT doc_cover FROM " . DB_DOCUMENTS . " WHERE doc_title IS NULL AND doc_status = :st",
            "st=0"
        );
        
        if ($Read->getResult()):
            foreach ($Read->getResult() as $docImage):
                $CoverRemove = "../../uploads/documents/{$docImage['doc_cover']}";
                
                if (file_exists($CoverRemove) && !is_dir($CoverRemove)):
                    unlink($CoverRemove);
                endif;
            endforeach;
        endif;
        $Delete->ExeDelete(DB_DOCUMENTS, "WHERE doc_title IS NULL AND doc_status = :st", "st=0");
    endif;
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-file-pdf">Documentos</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            Documentos
        </p>
    </div>

    <div class="dashboard_header_search">
        <a title="Novo Documento" href="dashboard.php?wc=documents/create" class="btn btn_green icon-plus">Novo documento</a>
        <span class='btn btn_green icon-spinner9 wc_drag_active' title='Ordenar Documentos'>Ordenar</span>

    </div>

</header>
<div class="dashboard_content">
    <?php
        $getPage = filter_input(INPUT_GET, 'pg', FILTER_VALIDATE_INT);
        $doc = ($getPage ? $getPage : 1);
        $Paginator = new Pager('dashboard.php?wc=documents/home&pg=', '<<', '>>', 10);
        $Paginator->ExePager($doc, 12);
        
        $Read->ExeRead(
            DB_DOCUMENTS,
            "ORDER BY doc_order, doc_title ASC LIMIT :limit OFFSET :offset",
            "limit={$Paginator->getLimit()}&offset={$Paginator->getOffset()}"
        );
        if (!$Read->getResult()):
            $Paginator->ReturnPage();
            echo Erro(
                "<span class='al_center icon-notification'>Ainda não existem documentos cadastrados {$Admin['user_name']}. Comece agora mesmo criando seu primeiro!</span>",
                E_USER_NOTICE
            );
        else:
            foreach ($Read->getResult() as $Doc):
                extract($Doc);
            
                $doc_status = ($doc_status == 1 ? '<span class="icon-checkmark font_green">Publicado</span>' : '<span class="icon-warning font_yellow">Rascunho</span>');
                $doc_cover = (!empty($doc_cover) ? BASE . "/tim.php?src=uploads/documents/{$doc_cover}&w=" . IMAGE_W / 2 . "&h=" . IMAGE_H / 2 . "" : "");
                
                echo "<article class='box box25 post_single wc_draganddrop' callback='Documents' callback_action='docs_order' id='{$doc_id}'>

                            <a title='Ver página no site' target='_blank' href='" . BASE . "/{$doc_name}'><img alt='{$doc_title}' src='{$doc_cover}'/></a>
                            
                            <div class='box_content wc_normalize_height'>
                                <h1 class='title'><a title='Ver no site' target='_blank' href='" . BASE . "/{$doc_name}'>{$doc_title}</a></h1>
                                <p>{$doc_status}</p>
                            </div>
                            
                            <div class='post_single_actions'>
                                <a title='Editar Documento' href='dashboard.php?wc=documents/create&id={$doc_id}' class='post_single_center icon-pencil btn btn_blue'>Editar</a>
                                <span rel='post_single' class='j_delete_action icon-cancel-circle btn btn_red' id='{$doc_id}'>Excluir</span>
                                <span rel='post_single' callback='Documents' callback_action='delete' class='j_delete_action_confirm icon-warning btn btn_yellow' style='display: none' id='{$doc_id}'>Confirmar?</span>
                            </div>
                </article>";
            endforeach;
            
            $Paginator->ExePaginator(DB_DOCUMENTS);
            echo $Paginator->getPaginator();
        endif;
    ?>
</div>
