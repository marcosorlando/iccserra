
$(function () {
    /*############### CAPITALIZE TEXT FORMS*/
    //Call function *ucfirts or ucwords* for capitalize input[type=text]
    $("form.form_capitalize textarea:not(.no-capitalise)").on('blur change', function () {
        // force: true to lower case all letter except first
        let cp_value = ucfirst($(this).val(), true);
        $(this).val(cp_value);
    });
    
    $("form.form_capitalize input[type='text']").blur(function () {
        // to capitalize all word
        let cp_value = capitalize($(this).val());
        $(this).val(cp_value);
    });
    
    //Call function *lowercase* for lowercase input[type=email]
    $("form.form_capitalize input[type='email']").keyup(function () {
        let email = lowercase($(this).val());
        $(this).val(email);
    });
    
})

/*TEXT FUNCTION*/
function ucfirst(str, force) {
    str = force ? str.toLowerCase() : str;
    return str.replace(/(\b)([a-zA-Z])/, function (firstLetter) {
        return firstLetter.toUpperCase();
    });
}

function abreviacao(s) {
    return /^([A-Z]\.)+$/.test(s);
}

function numeralRomano(s) {
    return /^M{0,4}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/.test(s);
}

function capitalize(texto) {
    let prepos = ["da", "do", "das", "dos", "a", "e", "de"];
    return texto.split(' ') // quebra o texto em palavras
    .map((palavra) => { // para cada palavra
        if (abreviacao(palavra) || numeralRomano(palavra)) {
            return palavra;
        }
        palavra = palavra.toLowerCase();
        if (prepos.includes(palavra)) {
            return palavra;
        }
        return palavra.charAt(0).toUpperCase() + palavra.slice(1);
    })
    .join(' '); // junta as palavras novamente
}

function lowercase(str) {
    return str.toLowerCase();
}
