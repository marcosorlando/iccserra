<?php
    
    session_start();
    require '../../_app/Config.inc.php';
    $NivelAcess = LEVEL_WC_DOCUMENTS;
    
    if (!APP_DOCUMENTS || empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess):
        $jSON['trigger'] = AjaxErro(
            '<b class="icon-warning">OPPSSS:</b> Você não tem permissão para essa ação ou não está logado como administrador!',
            E_USER_ERROR
        );
        echo json_encode($jSON);
        die;
    endif;
    
    usleep(50000);
    
    //DEFINE O CALLBACK E RECUPERA O POST
    $jSON = null;
    $CallBack = 'Documents';
    $PostData = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    
    //VALIDA AÇÃO
    if ($PostData && $PostData['callback_action'] && $PostData['callback'] == $CallBack):
        //PREPARA OS DADOS
        $Case = $PostData['callback_action'];
        unset($PostData['callback'], $PostData['callback_action']);
        
        // AUTO INSTANCE OBJECT READ
        if (empty($Read)):
            $Read = new Read;
        endif;
        
        // AUTO INSTANCE OBJECT CREATE
        if (empty($Create)):
            $Create = new Create;
        endif;
        
        // AUTO INSTANCE OBJECT UPDATE
        if (empty($Update)):
            $Update = new Update;
        endif;
        
        // AUTO INSTANCE OBJECT DELETE
        if (empty($Delete)):
            $Delete = new Delete;
        endif;
        
        //SELECIONA AÇÃO
        switch ($Case):
            //DELETE
            case 'delete':
                $Read->FullRead(
                    "SELECT doc_cover, doc_pdf FROM " . DB_DOCUMENTS . " WHERE doc_id = :ps",
                    "ps={$PostData['del_id']}"
                );
                
                if ($Read->getResult()):
                    foreach ($Read->getResult() as $docImage):
                        $CoverRemove = "../../uploads/documents/{$docImage['doc_cover']}";
                        $MaterialRemove = "../../uploads/documents/{$docImage['doc_pdf']}";
                        
                        if (file_exists($CoverRemove) && !is_dir($CoverRemove)):
                            unlink($CoverRemove);
                        endif;
                        
                        if (file_exists($MaterialRemove) && !is_dir($MaterialRemove)):
                            unlink($MaterialRemove);
                        endif;
                    
                    endforeach;
                endif;
                
                $Delete->ExeDelete(DB_DOCUMENTS, "WHERE doc_id = :id", "id={$PostData['del_id']}");
                
                $jSON['success'] = true;
                break;
            //MANAGER
            case 'manage':
                $docId = $PostData['doc_id'];
                unset($PostData['doc_id']);
                
                //VALIDANDO CAMPOS VAZIOS
                if (empty($PostData['doc_title'])) {
                    $jSON['field'] = 'doc_title';
                    $jSON['trigger'] = AjaxErro(
                        "<b class='icon-image'>Ooops!</b> Por favor, informe o Título do Documento.",
                        E_USER_NOTICE
                    );
                    
                }elseif (empty($PostData['doc_btn_bg_color'])) {
                    $jSON['field'] = 'doc_btn_bg_color';
                    $jSON['trigger'] = AjaxErro(
                        "<b class='icon-image'>Ooops!</b> Por favor, informe a cor de fundo do botão de download.",
                        E_USER_NOTICE
                    );
                }elseif (empty($PostData['doc_btn_bg_color_hover'])) {
                    $jSON['field'] = 'doc_btn_bg_color_hover';
                    $jSON['trigger'] = AjaxErro(
                        "<b class='icon-image'>Ooops!</b> Por favor, informe a cor de fundo do botão de download ao passar mouse em cima.",
                        E_USER_NOTICE
                    );
                }elseif (empty($PostData['doc_btn_text_color'])) {
                    $jSON['field'] = 'doc_btn_text_color';
                    $jSON['trigger'] = AjaxErro(
                        "<b class='icon-image'>Ooops!</b> Por favor, informe a cor do texto do botão.",
                        E_USER_NOTICE
                    );
                }elseif (empty($PostData['doc_btn_text'])) {
                    $jSON['field'] = 'doc_btn_text';
                    $jSON['trigger'] = AjaxErro(
                        "<b class='icon-image'>Ooops!</b> Por favor, informe o texto do botão de download.",
                        E_USER_NOTICE
                    );
                }elseif (empty($PostData['doc_category'])) {
                    $jSON['field'] = 'doc_category';
                    $jSON['trigger'] = AjaxErro(
                        "<b class='icon-image'>Ooops!</b> Por favor, informe a categoria do documento.",
                        E_USER_NOTICE
                    );
                    //ATULIZANDO O DOCUMENTO
                } else {
                    $pdf = $Read->LinkResult(DB_DOCUMENTS, 'doc_id', $docId, 'doc_pdf');
                    
                    if (empty($_FILES['doc_pdf']) && (!$pdf['doc_pdf'] || file_exists(
                                BASE . "/uploads/documents/{$pdf['doc_pdf']}"
                            ))) {
                        $jSON['trigger'] = AjaxErro(
                            "<b class='icon-file-pdf'>Doc.: (PDF):</b> Olá {$_SESSION['userLogin']['user_name']}, selecione um arquivo PDF para enviar como documento para Download.",
                            E_USER_WARNING
                        );
                        $jSON['field'] = 'doc_pdf';
                    } else {
                        $PostData['doc_status'] = (!empty($PostData['doc_status']) ? '1' : '0');
                        $PostData['doc_name'] = (!empty($PostData['doc_name']) ? Check::Name(
                            $PostData['doc_name']
                        ) : Check::Name($PostData['doc_title']));
                        
                        $Read->ExeRead(DB_DOCUMENTS, 'WHERE doc_id= :id', "id={$docId}");
                        
                        if ($Read->getResult()) {
                            $Thisdoc = $Read->getResult()[0];
                        }
                        
                        //UPLOAD PDF
                        if (!empty($_FILES['doc_pdf'])):
                            $File = $_FILES['doc_pdf'];
                            
                            if ($Thisdoc['doc_pdf'] && file_exists(
                                    "../../uploads/documents/{$Thisdoc['doc_pdf']}"
                                ) && !is_dir(
                                    "../../uploads/documents/{$Thisdoc['doc_pdf']}"
                                )):
                                unlink("../../uploads/documents/{$Thisdoc['doc_pdf']}");
                            endif;
                            
                            $Upload = new Upload('../../uploads/documents/', '');
                            $Upload->File($File, $PostData['doc_name'] . '-' . time());
                            
                            if ($Upload->getResult()):
                                $PostData['doc_pdf'] = $Upload->getResult();
                            else:
                                $jSON['trigger'] = AjaxErro(
                                    "<b class='icon-file-pdf'>ERRO AO ARQUIVO PDF</b> Olá {$_SESSION['userLogin']['user_name']}, selecione um arquivo em PDF para inserir no produto!",
                                    E_USER_WARNING
                                );
                                echo json_encode($jSON);
                                return;
                            endif;
                        else:
                            unset($PostData['doc_pdf']);
                        endif;
                        
                        //UPLOAD BACKGROUND-IMAGE
                        if (!empty($_FILES['doc_cover'])):
                            $File = $_FILES['doc_cover'];
                            
                            if ($Thisdoc['doc_cover'] && file_exists(
                                    "../../uploads/documents/{$Thisdoc['doc_cover']}"
                                ) && !is_dir("../../uploads/documents/{$Thisdoc['doc_cover']}")):
                                unlink("../../uploads/documents/{$Thisdoc['doc_cover']}");
                            endif;
                            
                            $Upload = new Upload('../../uploads/documents/');
                            $Upload->Image($File, $PostData['doc_name'] . '-cover');
                            
                            if ($Upload->getResult()):
                                $PostData['doc_cover'] = $Upload->getResult();
                            else:
                                $jSON['trigger'] = AjaxErro(
                                    "<b class='icon-image'>ERRO AO ENVIAR CAPA:</b> Olá {$_SESSION['userLogin']['user_name']}, selecione uma imagem JPG ou PNG para enviar como capa!",
                                    E_USER_WARNING
                                );
                                echo json_encode($jSON);
                                return;
                            endif;
                        else:
                            unset($PostData['doc_cover']);
                        endif;
                        
                        $Read->FullRead(
                            'SELECT doc_name FROM ' . DB_DOCUMENTS . ' WHERE doc_name = :nm AND doc_id != :id',
                            "nm={$PostData['doc_name']}&id={$docId}"
                        );
                        
                        if ($Read->getResult()):
                            $PostData['doc_name'] = "{$PostData['doc_name']}-{$docId}";
                        endif;
                        
                        $jSON['name'] = $PostData['doc_name'];
                        $jSON['view'] = BASE . "/{$PostData['doc_name']}";
                        
                        $Update->ExeUpdate(DB_DOCUMENTS, $PostData, 'WHERE doc_id = :id', "id={$docId}");
                        $jSON['trigger'] = AjaxErro("<b class='icon-checkmark'>Documento atualizado com sucesso!</b>");
                    }
                }
                break;
            
            case 'docs_order':
                if (is_array($PostData['Data'])) {
                    foreach ($PostData['Data'] as $RE) {
                        $UpdateDocs = ['doc_order' => $RE[1]];
                        $Update->ExeUpdate(DB_DOCUMENTS, $UpdateDocs, 'WHERE doc_id = :doc', "doc={$RE[0]}");
                    }
                    $jSON['sucess'] = true;
                }
                break;
            
            case 'category_add':
                $PostData = array_map('strip_tags', $PostData);
                $CatId = $PostData['category_id'];
                unset($PostData['category_id']);
                
                $PostData['category_name'] = Check::Name($PostData['category_title']);
                
                $Read->FullRead(
                    'SELECT category_id FROM ' . DB_DOCS_CATEGORIES . ' WHERE category_name = :cn AND category_id != :ci',
                    "cn={$PostData['category_name']}&ci={$CatId}"
                );
                if ($Read->getResult()):
                    $PostData['category_name'] = $PostData['category_name'] . '-' . $CatId;
                endif;
                
                $Update->ExeUpdate(DB_DOCS_CATEGORIES, $PostData, 'WHERE category_id = :id', "id={$CatId}");
                $jSON['trigger'] = AjaxErro(
                    "<b class='icon-checkmark'>TUDO CERTO: </b> A categoria <b>{$PostData['category_title']}</b> foi atualizada com sucesso!"
                );
                
                break;
            
            case 'category_remove':
                $PostData['category_id'] = $PostData['del_id'];
                $Read->FullRead(
                    'SELECT doc_id FROM ' . DB_DOCUMENTS . ' WHERE doc_category = :cat ',
                    "cat={$PostData['category_id']}"
                );
                
                if ($Read->getResult()):
                    $jSON['trigger'] = AjaxErro(
                        "<b class='icon-warning'>{$Read->getRowCount()} DOC(S): </b> Olá {$_SESSION['userLogin']['user_name']}, não é possível remover categorias quando existem documentos cadastrados na mesma! Exclua o documento ou mude sua categoria e tente novamente.",
                        E_USER_WARNING
                    );
                else:
                    $Delete->ExeDelete(
                        DB_DOCS_CATEGORIES,
                        'WHERE category_id = :cat',
                        "cat={$PostData['category_id']}"
                    );
                    $jSON['success'] = true;
                endif;
                
                break;
        
        endswitch;
        
        //RETORNA O CALLBACK
        if ($jSON):
            echo json_encode($jSON);
        else:
            $jSON['trigger'] = AjaxErro(
                '<b class="icon-warning">OPSS:</b> Desculpe. Mas uma ação do sistema não respondeu corretamente. Ao persistir, contate o desenvolvedor!',
                E_USER_ERROR
            );
            echo json_encode($jSON);
        endif;
    else:
        //ACESSO DIRETO
        die('<br><br><br><center><h1>Acesso Restrito!</h1></center>');
    endif;
