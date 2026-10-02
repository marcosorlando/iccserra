//MOVIMENTO REDUZIDO: desliga animações do jQuery (slide, fade, scroll animado)
var ReducedMotion = !!(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);
if (ReducedMotion) {
    $.fx.off = true;
}

//ANIMAÇÕES DE ENTRADA (Design System): .reveal para blocos, .reveal-card escalonado para cards.
//As classes são aplicadas só em tempo de execução; sem JS ou com movimento reduzido nada fica oculto.
(function () {
    if (ReducedMotion || !('IntersectionObserver' in window)) {
        return;
    }

    var Blocks = '.site_header, #footeroptin .content, .wc_bio .bio_logo, .wc_bio .bio_content, '
        + '.htmlchars form, .post_single .left_content, .page_single .htmlchars, .icc_linhas_nota, '
        + '.icc_passos_actions, .icc_transparencia';
    var Cards = '.wc_videos .post_list, .wc_more .post_list, .main_blog .post_list, #me ul li, '
        + '.ouvidoria-options, .materiais, .partners .clients-area li, .main_footer > .content > section, '
        + '.sidebar_widget, .sidebar_most, .icc_prova li, .icc_linha, .icc_passos_lista li, .icc_docs li';
    var Step = 90;

    document.documentElement.classList.add('js-motion');

    //O gatilho é a borda superior do bloco cruzar 90% da janela (threshold 0), e não uma
    //fração da área. Com fração, um bloco mais alto que ~7x a janela (um artigo longo)
    //nunca alcança o limite e ficaria invisível para sempre.
    var Observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) {
                return;
            }
            entry.target.classList.add('in-view');
            Observer.unobserve(entry.target);
        });
    }, {threshold: 0, rootMargin: '0px 0px -10% 0px'});

    //Rede de segurança: se o observador não disparar (layout tardio, imagem que muda a
    //altura, aba em segundo plano), nada que já esteja na janela fica escondido.
    function revealVisible() {
        document.querySelectorAll('.reveal:not(.in-view), .reveal-card:not(.in-view)').forEach(function (el) {
            var Box = el.getBoundingClientRect();
            if (Box.top < window.innerHeight && Box.bottom > 0) {
                el.classList.add('in-view');
                Observer.unobserve(el);
            }
        });
    }

    window.addEventListener('load', function () {
        window.setTimeout(revealVisible, 1200);
    });

    function prepare(selector, className) {
        document.querySelectorAll(selector).forEach(function (el) {
            var Siblings = Array.prototype.filter.call(el.parentElement.children, function (child) {
                return child.matches(selector);
            });
            el.classList.add(className);
            el.style.setProperty('--reveal-delay', (Siblings.indexOf(el) % 8) * Step + 'ms');
            Observer.observe(el);
        });
    }

    $(function () {
        prepare(Blocks, 'reveal');
        prepare(Cards, 'reveal-card');
    });
})();

//MENU FIXO: a barra já é sticky no CSS; aqui só marcamos quando a página saiu do topo,
//para a descida suave com fade e a sombra. Sem JS o menu continua fixo, só sem o efeito.
(function () {
    var Header = document.querySelector('.main_header');
    if (!Header) {
        return;
    }

    var Limite = 80;
    var Marcado = false;
    var Pendente = false;

    function atualiza() {
        Pendente = false;
        var Passou = (window.pageYOffset || document.documentElement.scrollTop) > Limite;
        if (Passou !== Marcado) {
            Marcado = Passou;
            Header.classList.toggle('is-stuck', Passou);
        }
    }

    window.addEventListener('scroll', function () {
        if (!Pendente) {
            Pendente = true;
            window.requestAnimationFrame(atualiza);
        }
    }, {passive: true});

    atualiza();
})();

//TILT 3D (DS · seção 05 Motion): os cards de linha de crédito acompanham o ponteiro.
//Só com mouse, JS ativo e movimento normal. No toque e no teclado nada muda.
(function () {
    var Fino = window.matchMedia && window.matchMedia('(hover: hover) and (pointer: fine)').matches;
    if (ReducedMotion || !Fino) {
        return;
    }

    var Grau = 3;

    $(function () {
        var Cards = document.querySelectorAll('.icc_linha');
        if (!Cards.length) {
            return;
        }

        Cards.forEach(function (Card) {
            Card.classList.add('icc_tilt');

            Card.addEventListener('mousemove', function (e) {
                var Box = Card.getBoundingClientRect();
                var X = (e.clientX - Box.left) / Box.width - 0.5;
                var Y = (e.clientY - Box.top) / Box.height - 0.5;
                Card.classList.add('is-tilting');
                Card.style.setProperty('--tilt-y', (X * Grau * 2).toFixed(2) + 'deg');
                Card.style.setProperty('--tilt-x', (-Y * Grau * 2).toFixed(2) + 'deg');
                Card.style.setProperty('--tilt-lift', 'var(--lift)');
            });

            Card.addEventListener('mouseleave', function () {
                Card.classList.remove('is-tilting');
                Card.style.removeProperty('--tilt-y');
                Card.style.removeProperty('--tilt-x');
                Card.style.removeProperty('--tilt-lift');
            });
        });
    });
})();

//ESTADO DE ENVIO: bloqueia reenvio e expõe o carregamento para leitor de tela
function formBusy(form, busy) {
    form.attr('aria-busy', busy ? 'true' : 'false');
    form.find('button:not([type="button"]), input[type="submit"]').prop('disabled', busy).attr('aria-busy', busy ? 'true' : 'false');
}

//COMPARADOR NO SIMULADOR: o CTA da linha preenche o formulário e leva o foco até ele
//(sem JS o link só rola até o formulário)
$(function () {
    var Linha = document.getElementById('tipo_credito');
    if (!Linha) {
        return;
    }

    $(document).on('click', '[data-simular-linha]', function (e) {
        var Valor = this.getAttribute('data-simular-linha');
        if (!Linha.querySelector('option[value="' + Valor + '"]')) {
            return;
        }

        e.preventDefault();
        Linha.value = Valor;
        Linha.dispatchEvent(new Event('change', {bubbles: true}));

        (Linha.closest('label') || Linha).scrollIntoView({behavior: ReducedMotion ? 'auto' : 'smooth', block: 'center'});
        Linha.focus({preventScroll: true});
    });
});

//SOMA ANTISPAM: um par de números por formulário (a ouvidoria tem três na mesma página).
//Ficava em lp/js/lp.js, que não é carregado em todas as páginas e só atendia ao primeiro campo por id.
$(function () {
    $('[name="senderHuman"]').each(function () {
        var Campo = $(this);
        var Form = Campo.closest('form');
        var A = Math.ceil(Math.random() * 10) + 1;
        var B = Math.ceil(Math.random() * 10) + 1;

        Campo.attr('placeholder', A + ' + ' + B + ' = ?');
        Form.find('[name="checkHuman_a"]').val(A);
        Form.find('[name="checkHuman_b"]').val(B);
    });
});

//POSTOS DE ATENDIMENTO: busca por cidade e lista recolhida (sem JS a lista aparece inteira)
$(function () {
    var Busca = document.querySelector('[data-postos-busca]');
    var Lista = document.getElementById('icc-postos-lista');
    if (!Busca || !Lista) {
        return;
    }

    var Campo = Busca.querySelector('input');
    var Status = document.getElementById('icc-postos-status');
    var Toggle = document.querySelector('.icc_postos_toggle');
    var Itens = Array.prototype.slice.call(Lista.children);
    var Limite = 8;
    var Aberto = false;

    function normaliza(texto) {
        return (texto || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').trim();
    }

    Itens.forEach(function (item) {
        var Titulo = item.querySelector('h3');
        item.setAttribute('data-cidade', normaliza(Titulo ? Titulo.textContent : item.textContent));
    });

    function aplica() {
        var Termo = normaliza(Campo.value);
        var Visiveis = 0;

        Itens.forEach(function (item) {
            var Casa = !Termo || item.getAttribute('data-cidade').indexOf(Termo) !== -1;
            var Mostra = Casa && (Termo || Aberto || Visiveis < Limite);
            item.hidden = !Mostra;
            if (Casa) {
                Visiveis++;
            }
        });

        if (Termo) {
            Status.textContent = Visiveis === 0
                ? 'Nenhum posto encontrado. Fale com a matriz: (54) 3217-8787.'
                : Visiveis + (Visiveis === 1 ? ' posto encontrado.' : ' postos encontrados.');
        } else {
            Status.textContent = Itens.length + ' postos de atendimento no Rio Grande do Sul.';
        }

        Toggle.hidden = !!Termo || Itens.length <= Limite;
        Toggle.setAttribute('aria-expanded', Aberto ? 'true' : 'false');
        Toggle.textContent = Aberto ? 'Mostrar menos postos' : 'Ver todos os ' + Itens.length + ' postos';
    }

    Busca.hidden = false;
    Campo.addEventListener('input', aplica);
    Toggle.addEventListener('click', function () {
        Aberto = !Aberto;
        aplica();
    });
    aplica();
});

$(function () {
        //Oculta Trigger clicada
    $('html').on('click', '.trigger_ajax, .trigger_modal', function () {
        $(this).fadeOut('slow', function () {
            $(this).remove();
        });
    });
    
    //MENU MOBILE: botão acessível (aria-expanded) e Esc fecha devolvendo o foco
    var MenuButton = $('.mobile_menu');
    
    function toggleMenu(open) {
        MenuButton.attr({
            'aria-expanded': open ? 'true' : 'false',
            'aria-label': open ? 'Fechar menu' : 'Abrir menu'
        });
        $('.main_nav').stop(true, true)[open ? 'slideDown' : 'slideUp']();
    }
    
    MenuButton.on('click', function () {
        toggleMenu($(this).attr('aria-expanded') !== 'true');
    });
    
    $(document).on('keydown', function (e) {
        if (e.key === 'Escape' && MenuButton.is(':visible') && MenuButton.attr('aria-expanded') === 'true') {
            toggleMenu(false);
            MenuButton.trigger('focus');
        }
    });
    
    $("#search-open").on('click', function (e) {
        e.preventDefault();
        var SearchOpen = $(this).attr('aria-expanded') !== 'true';
        $(this).attr('aria-expanded', SearchOpen ? 'true' : 'false');
        $(".navbar-search").css('overflow', 'visible').stop(true, true).slideToggle();
        if (SearchOpen) {
            $('#search-input').trigger('focus');
        }
    });
    
    //INSTALA - HELLOBAR
    $.getScript(BASE + "/_cdn/widgets/hellobar/hellobar.wc.js", function () {
        $("head").append("<link rel='stylesheet' href='" + BASE + "/_cdn/widgets/hellobar/hellobar.wc.css' />");
    });
    
    //PLAY TAKE
    $('.testimony_start').click(function () {
        var Testimony = $(this).attr('id');
        var Headding = $(this).find('h1').html();
        $('.testimony_content h1').html(Headding);
        $('.testimony_content .embed-container').html('<iframe width="640" height="360" src="https://www.youtube.com/embed/' + Testimony + '?rel=0&amp;showinfo=0&autoplay=1&origin=https://zen.ppg.br" frameborder="0" allowfullscreen></iframe>');
        $('.testimony').fadeIn(200);
    });
    
    $('.testimony_close').click(function () {
        $('.testimony').fadeOut(200, function () {
            $('.testimony_content .embed-container').html('');
        });
    });
    //END PLAY TAKE
    
    //ENVIA FOMS VIA AJAX: SELETOR, EVENTO/EFEITO, CALLBACK, AÇÃO
    $('.j_formsubmit').submit(function () {
        var form = $(this);
        
        var data = $(this).serialize();
        var callback = form.find('input[name="callback"]').val();
        // var callback_action = form.find('input[name="callback_action"]').val();
        
        var url = BASE + "/themes/wc_icc/_ajax/" + callback + ".ajax.php";
        
        $.ajax({
            url: url,
            data: data,
            type: 'POST',
            dataType: 'json',
            beforeSend: function () {
                formBusy(form, true);
                form.find('.form_load').fadeIn(500);
            },
            complete: function () {
                formBusy(form, false);
            },
            success: function (data) {
                //REMOVE LOAD
                form.find('.form_load').fadeOut('slow', function () {
                    //EXIBE CALLBACKS
                    if (data.trigger) {
                        var CallBackPresent = $('.callback_return');
                        if (CallBackPresent.length) {
                            CallBackPresent.html(data.trigger);
                            $('.trigger_ajax').fadeIn('slow');
                        } else {
                            Trigger(data.trigger);
                        }
                    }
                    //Limpa erros anteriores (estado visual vem do CSS via aria-invalid)
                    form.find('[aria-invalid]').removeAttr('aria-invalid');
                    if (data.foco) {
                        if (data.foco == 'senderHuman'){
                            form.find("[name=" + data.foco + "]").val('');
                        }
                        //COLOCA O FOCO NO ITEM VAZIO
                        form.find("[name=" + data.foco + "]").attr('aria-invalid', 'true').focus();
                    }
                    
                    if (data.simulacao) {
                        $('.retorno_simulacao').html(data.simulacao);
                        $('html, body').animate({scrollTop: $('#print').offset().top}, 800);
                    }
                    
                    if (data.tracking){
                        window.dataLayer = window.dataLayer || [];
                        dataLayer.push(data.tracking);
                    }
                    
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                    
                });
            }
        });
        return false;
    });
    
    $(document).ready(function () {
        
        $('.curriculum').on('dragover', function (event) {
            event.preventDefault();
            event.stopPropagation();
            $(this).addClass('dragover');
        });
        
        $('.curriculum').on('dragleave', function (event) {
            event.preventDefault();
            event.stopPropagation();
            $(this).removeClass('dragover');
        });
        
        $('.curriculum').on('drop', function (event) {
            event.preventDefault();
            event.stopPropagation();
            $(this).addClass('dragover');
            $('.curriculum img').prop('src', BASE + '/themes/wc_icc/images/curriculo-sucess.svg');
            var cv = document.getElementById('cv_pdf');
            cv.files = event.originalEvent.dataTransfer.files;
        });
        
    });
    
    $('#curriculo_form').submit(function () {
        var form = $(this);
        var callback = $(this).find('.callback').attr('value');
        var callback_action = $(this).find('.callback-action').attr('value');
        var cv = $('#cv_pdf').prop('files')[0];
        var url = BASE + "/themes/wc_icc/_ajax/" + callback + ".ajax.php";
        
        var formData;
        
        formData = new FormData($(this)[0]);
        
        $.ajax({
            url: url,
            data: formData,
            type: 'POST',
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function () {
                formBusy(form, true);
                form.find('.form_load').fadeIn(500);
            },
            complete: function () {
                formBusy(form, false);
            },
            success: function (data) {
                //REMOVE LOAD
                form.find('.form_load').fadeOut('slow', function () {
                    //EXIBE CALLBACKS
                    if (data.trigger) {
                        var CallBackPresent = $('.callback_return');
                        if (CallBackPresent.length) {
                            CallBackPresent.html(data.trigger);
                            $('.trigger_ajax').fadeIn('slow');
                        } else {
                            Trigger(data.trigger);
                        }
                    }
                    //Limpa erros anteriores (estado visual vem do CSS via aria-invalid)
                    form.find('[aria-invalid]').removeAttr('aria-invalid');
                    if (data.foco) {
                        if (data.foco == 'senderHuman'){
                            form.find("[name=" + data.foco + "]").val('');
                        }
                        //COLOCA O FOCO NO ITEM VAZIO
                        form.find("[name=" + data.foco + "]").attr('aria-invalid', 'true').focus();
                    }
                });
            }
        });
        return false;
    });
    
    //SHOW DIV OUVIDORIA
    $('.ouvidoria a').click(function () {
        
        let destiny = $(this).attr('href');
        let Goto = $($(this).attr("href"));
        
        switch (destiny) {
            
            case '#denuncia':
                $('#icb-01').addClass('active');
                $('#icb-02').removeClass('active');
                $('#icb-03').removeClass('active');
                break;
            case '#reclamacao':
                $('#icb-02').addClass('active');
                $('#icb-01').removeClass('active');
                $('#icb-03').removeClass('active');
                break;
            case '#sugestao':
                $('#icb-03').addClass('active');
                $('#icb-01').removeClass('active');
                $('#icb-02').removeClass('active');
                break;
        }
        
        $('.destiny > form').hide();
        $('.trigger_ajax').hide();
        $(destiny).slideDown(400);
        $('[name="privacy"]').prop('checked', false);
        $('[name="first_name"], [name="last_name"], [name="email"]').val('');
        
        if (Goto.length) {
            $('html, body').animate({scrollTop: Goto.offset().top - 20}, 800);
            
        } else {
            $('html, body').animate({scrollTop: 0}, 800);
        }
        return false;
    });
    
});

function getBaseUrl() {
    // Nome do host
    var hostName = location.hostname;
    
    if (hostName === "localhost") {
        // Endereço após o domínio do site
        pathname = window.location.pathname;
        // Separa o pathname com uma barra transformando o resultado em um array
        splitPath = pathname.split('/');
        
        // Obtém o segundo valor do array, que é o nome da pasta do servidor local
        path = splitPath[1];
        
        baseUrl = "https://" + hostName + "/" + path;
    } else {
        baseUrl = "https://" + hostName;
    }
    
    return baseUrl;
}

$('.fadeOutLeft h2').addClass('animated bounceOutLeft');
