function showDiv() {
    //oculta paragrafos
    document.getElementById("p_msg").className = "invisivel";

    //oculta DIV n_parcelas
    document.getElementById("n_parcelas").className = "invisivel";

    //captura elemento parcelas para variavel
    parcelas = document.getElementById("parcelas");

    // obter o elemento select
    var elem = document.getElementById("parcelas");

    // excluir todas as opções
    elem.options.length = 0;
    // cria opção Selecione...
    parcelas.innerHTML += "<option selected disabled value=''>Selecione as parcelas...</option>";

//    //Capital de Giro Projeto Banrisul 2,5%
//    if ((document.getElementById('modalidade').value == "giro") && (document.getElementById('tipo_credito').value == "banrisul")) {
//        document.getElementById("p_msg").innerHTML = "Parcelado em até 12X - Juros  30% do valor a 0.79% ao mês 70% do valor a 3.20% ao mês até 10X</strong> + TAC de <strong>3%</strong>";
//        document.getElementById("p_msg").className = "visivel";
//
//        for (i = 1; i <= 12; i++) {
//            parcelas.innerHTML += "<option value=" + i + ">" + i + "</option>";
//        }
//    }//
//    //Capital de Fixo Projeto Banrisul 2,5%
//    if ((document.getElementById('modalidade').value == "fixo") && (document.getElementById('tipo_credito').value == "banrisul")) {
//        document.getElementById("p_msg").innerHTML = "Até 15X -  30% do valor a 0.79%  e 70% do valor a 3.20% ao mês<br>Mais de 15X -  30% do valor a 0.79% e 70% do valor a 3.90% ao mês<br> + TAC de <strong>3%</strong>";
//        document.getElementById("p_msg").className = "visivel";
//
//        for (i = 1; i <= 24; i++) {
//            parcelas.innerHTML += "<option value=" + i + ">" + i + "</option>";
//        }
//    }

    //Capital de Giro ICC PROPRIO e Credisol  3,79%
    if (document.getElementById('modalidade').value == "giro") {

        document.getElementById('reforma').className = "invisivel";
        document.getElementById('agricola').className = "invisivel";
//    document.getElementById('pessoal').className = "invisivel";


        if ((document.getElementById('tipo_credito').value == "microcredito")) {
            document.getElementById("p_msg").innerHTML = "<strong>Microcrédito Produtivo Giro</strong><br>Parcelado em até 12X  - Juros <strong>3.99%</strong> + TAC de <strong>3%</strong>";
            document.getElementById("p_msg").className = "visivel";

            for (i = 1; i <= 12; i++) {
                parcelas.innerHTML += "<option value=" + i + ">" + i + "</option>";
            }
        }

        if ((document.getElementById('tipo_credito').value == "mulher")) {
            document.getElementById("p_msg").innerHTML = "<strong>Microcrédito Mulher</strong><br>Parcelado em até" +
                " 12X  - Juros <strong>3.99%</strong> + TAC de <strong>3%</strong>";
            document.getElementById("p_msg").className = "visivel";

            for (i = 1; i <= 12; i++) {
                parcelas.innerHTML += "<option value=" + i + ">" + i + "</option>";
            }
        }
    }

    //Capital de Fixo ICC PROPRIO e Credisol  3,79%
    if (document.getElementById('modalidade').value == "fixo") {
        document.getElementById('reforma').className = "visivel";
        document.getElementById('agricola').className = "visivel";
//    document.getElementById('pessoal').className = "visivel";

        if (document.getElementById('tipo_credito').value == "microcredito") {
            document.getElementById("p_msg").innerHTML = "<strong>Microcrédito Produtivo Fixo ou Misto</strong><br>Parcelado em até 24X - Juros <strong>3.99%</strong> + TAC de <strong>3%</strong>";
            document.getElementById("p_msg").className = "visivel";

            for (i = 1; i <= 24; i++) {
                parcelas.innerHTML += "<option value=" + i + ">" + i + "</option>";
            }
        }
        //CAPITAL FIXO - CRÉDITO PARA REFORMA 3.99%
        if ((document.getElementById('tipo_credito').value == "reforma")) {
            document.getElementById("p_msg").innerHTML = "<strong>Linha de Crédito para Reforma/Ampliação</strong><br>Parcelado em até 24X - Juros <strong>3.99%</strong> + TAC de <strong>3%</strong>";
            document.getElementById("p_msg").className = "visivel";

            for (i = 1; i <= 24; i++) {
                parcelas.innerHTML += "<option value=" + i + ">" + i + "</option>";
            }
        }
        //CAPITAL FIXO - CRÉDITO AGRICOLA 3.99%
        if ((document.getElementById('tipo_credito').value == "agricola")) {
            document.getElementById("p_msg").innerHTML = "<strong>Linha de Crédito Agricola</strong><br>Parcelado em até 24X - Juros <strong>3.99%</strong> + TAC de <strong>3%</strong>";
            document.getElementById("p_msg").className = "visivel";

            for (i = 1; i <= 24; i++) {
                parcelas.innerHTML += "<option value=" + i + ">" + i + "</option>";
            }
        }

        //MICROCRÉDITO MULHER
        if ((document.getElementById('tipo_credito').value == "mulher")) {
            document.getElementById("p_msg").innerHTML = "<strong>Microcrédito Mulher</strong><br>Parcelado em até" +
                " 24X - Juros <strong>3.99%</strong> + TAC de <strong>3%</strong>";
            document.getElementById("p_msg").className = "visivel";

            for (i = 1; i <= 24; i++) {
                parcelas.innerHTML += "<option value=" + i + ">" + i + "</option>";
            }
        }

        //CAPITAL FIXO - CRÉDITO PESSOAL 3.99%
//    if ((document.getElementById('tipo_credito').value == "pessoal")) {
//      document.getElementById("p_msg").innerHTML = "<strong>Linha de Crédito Pessoal</strong><br>Parcelado em até 24X - Juros <strong>3.99%</strong> + TAC de <strong>3%</strong>";
//      document.getElementById("p_msg").className = "visivel";
//
//      for (i = 1; i <= 24; i++) {
//        parcelas.innerHTML += "<option value=" + i + ">" + i + "</option>";
//      }
//    }

    }

    document.getElementById("n_parcelas").className = "visivel";

}

/*fim da função*/

function validaValor(field) {
    var valor = document.getElementById("valor");
    //Valida valor Banrisul
    if ((document.getElementById('tipo_credito').value == "banrisul") && (field > 30000)) {
        valor.maxLength = 5;
        alert("Valor máximo para essa modalidade é R$ 30.000,00");
        valor.value = 30000;
        return false;
    }
    //Valida valor Iicc
    if ((document.getElementById('tipo_credito').value == "icc") && (field > 30000)) {
        valor.maxLength = 5;
        alert("Valor máximo para essa modalidade é R$ 30.000,00");
        valor.value = 30000;
        return false;
    }

}

function showAddress(address) {

    document.getElementById("bento").className = "invisivel";
    document.getElementById("caxias").className = "invisivel";
    document.getElementById("cambara").className = "invisivel";
    document.getElementById("cotipora").className = "invisivel";
    document.getElementById("dois-lajeados").className = "invisivel";
    document.getElementById("esmeralda").className = "invisivel";
    document.getElementById("farroupilha").className = "invisivel";
    document.getElementById("garibaldi").className = "invisivel";
    document.getElementById("nova-roma").className = "invisivel";
    document.getElementById("nova-prata").className = "invisivel";
    document.getElementById("pinto-bandeira").className = "invisivel";
    document.getElementById("vacaria").className = "invisivel";
    document.getElementById("veranopolis").className = "invisivel";
    document.getElementById("sao-valentin").className = "invisivel";
    document.getElementById("serafina").className = "invisivel";

    document.getElementById(address).className = "visivel";
}

function valida_Contato() {

    var msg = "* Os seguintes ERROS foram apresentados:\n\n";

    if (document.getElementById("nome").value.length <= 0) {

        msg += "Preencha o campo nome.\n";

    }
    if (document.getElementById("email").value.length <= 0) {

        msg += "Preencha o campo e-mail.\n";

    }
    if (document.getElementById("fone").value.length <= 0) {

        msg += "Preencha o campo telefone.\n";

    }
    if (document.getElementById("cidade").value.length <= 0) {

        msg += "Preencha o campo cidade.\n";

    }

    if (document.getElementById("cbUf").value == 0) {

        msg += "Selecione um estado.\n";

    }
    if (document.getElementById("assunto").value.length <= 0) {

        msg += "Preencha o campo assunto.\n";

    }
    if (document.getElementById("mensagem").value.length <= 0) {

        msg += "Digite o conteúdo da mensagem.\n ";

    }

    if (msg != "* Os seguintes ERROS foram apresentados:\n\n") {
        alert(msg);
        return false;
    }
}


function valida_Simulador() {

    var valor = document.getElementById("valor").value;
    var msg = "* Os seguintes ERROS foram apresentados:\n\n";

    if (document.getElementById("parcelas").value == '0') {

        msg += "Selecione o número de parcelas.\n";
        grifar = document.getElementById("parcelas");

    }

    if (document.getElementById("tipo_credito").value == '0') {

        msg += "Selecione a modalidade de crédito.\n";
        grifar = document.getElementById("tipo_credito");

    }
    if (document.getElementById("modalidade").value == '0') {

        msg += "Selecione um Tipo de capital. Giro ou Fixo.\n";
        grifar = document.getElementById("modalidade");
    }

    if (msg != "* Os seguintes ERROS foram apresentados:\n\n") {
        alert(msg);
        grifar.focus();
        return false;
    }

    validaValor(valor);

}


function mask_date(field) {

    if (document.getElementById(field).value.length == 2) {
        document.getElementById(field).value = document.getElementById(field).value + "/";

    }
    if (document.getElementById(field).value.length == 5) {
        document.getElementById(field).value = document.getElementById(field).value + "/";

    }

}

function formata_URL(field) {

    if (document.getElementById(field).value.length > 0) {

        var new_value = document.getElementById(field).value.replace("http://www.", "");

        document.getElementById(field).value = new_value;

        document.getElementById(field).value = "http://www." + document.getElementById(field).value;
    }
}

function mask_fone(field) {
    if (document.getElementById(field).value.length == 2) {
        document.getElementById(field).value = "(" + document.getElementById(field).value + ") ";
    }
    if (document.getElementById(field).value.length == 9) {
        document.getElementById(field).value = document.getElementById(field).value + "-";
    }
}

// function printBy(selector){
//     var print = $(selector)
//     .clone()
//     .addClass('print')
//     .prependTo('body');

// Stop JS execution
//   window.print();

// Remove div once printed
//$print.remove();
// }

function cont() {
    $(".no-print").css("display", "none");

    var conteudo = document.getElementById('print').innerHTML,
        tela_impressao = window.open('about:blank');
    tela_impressao.document.write(conteudo);

    document.getElementsByClassName('no-print').remove;

    tela_impressao.window.print();
    tela_impressao.window.close();

}
