$(function(){
    $('td > :button').on('click', function(){
        ProcessarEscolha($(this));
    });

    $('#card > input').on('click', function(){
        $('#resultado').css("visibility", "hidden");
    });

    $('#limpar').on('click', function(){
        LimparJogo();
    });

    $('#jogar').on('click', function(){
        if($("#selecionadas > span").length == 6)
            Jogar();
    });

});

function ProcessarEscolha(button){

    if(button.hasClass('btn-success')){
        
        button.removeClass('btn-success');
        $(`#selecionadas > span:contains('${button.val()}')`).remove();

    }
    else{
        if($("#selecionadas > span").length < 6 && !button.hasClass("btn-success")){

            button.addClass("btn-success");
            $('#selecionadas').append(`<span class="m-1 btn border border-dark">${button.val()}</span>`);
            
        }
    }
}

function LimparJogo(){

    $(".btn-success").removeClass("btn-success");

    $("span").remove();

}

function Jogar(){

    let dezenas = []

    $('#selecionadas > span').each(function(){ dezenas.push($(this).html())});

    dezenas = dezenas.sort();

    $.ajax({
        type: "POST",
        url : "../../app/controllers/ApostaController.php",
        data: `dezenas=${JSON.stringify(dezenas)}`,
        timeout : "2000",  
        success: function(data){console.log(data); printarResultado(data)},
    });

}

function printarResultado(data){

    $('#resultado').html(data);
    $('#resultado').css("visibility", "visible");

}