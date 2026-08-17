

$(window).on('load', function () {
     /* --------------------------------------------
     SECURITY CHECK HUMAN
    -------------------------------------------- */
    if($("#senderHuman").length > 0 ) {
        var a = Math.ceil(Math.random() * 10) + 1;
        var b = Math.ceil(Math.random() * 10) + 1;
        document.getElementById("senderHuman").placeholder = a +" + "+ b +" = ?";
        document.getElementById("checkHuman_a").value = a;
        document.getElementById("checkHuman_b").value = b;
    }
});

$(function () {
    //SELETOR, EVENTO/EFEITO, CALLBACK, AÇÃO
    $('.j_landing_page').submit(function () {
        var form = $(this);
        var data = $(this).serialize();
        
        $.ajax({
            url: BASE + '/themes/wc_icc/lp/ajax/Lp.ajax.php',
            data: data,
            type: 'POST',
            dataType: 'json',
            beforeSend: function () {
                form.find('.form_load').fadeIn(500);
            },
            success: function (data) {
                //REMOVE LOAD
                form.find('.form_load').fadeOut('slow', function () {
                    //EXIBE CALLBACKS
                    if (data.trigger) {
                        var CallBackPresent = form.find('.callback_return');
                        if (CallBackPresent.length) {
                            CallBackPresent.html(data.trigger);
                            
                            $('.trigger_ajax').fadeIn('slow');
                            window.setTimeout(function () {
                                $(window.document.location).attr('href', data.redirect);
                            }, 3000);
                        } else {
                            Trigger(data.trigger);
                        }
                    }
    
                    if (data.foco){
                        if (data.foco == 'senderHuman'){
                            $("[name='" + data.foco + "']").focus().val('');
                        }else{
                            $("[name='" + data.foco + "']").focus();
                        }
                    }
                    
                });
            }
        });
        return false;
    });
});
