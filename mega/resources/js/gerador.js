$(function(){
    $('#gerar').on('click', function(){
        $('#jogosGerados').html('');
        enviarQuant();

    });
});

function enviarQuant(){

    $.ajax({
        type: "POST",
        url : "../../app/controllers/GeradorController.php",
        data: `quantidade=${$('input:first').val()}`,
        timeout : "2000",  
        success: function(data){printarJogos(data)},
    });

}

function printarJogos(jsonJogos) {

    const jogos = JSON.parse(jsonJogos);

    for(let i = 0; i < jogos.length; i++){

        $('#jogosGerados').append(`
            <div class="my-5 alert alert-light border border-dark">
                <h1> Jogo ${i + 1} </h1>
                <h5>
                    <span class="btn m-1 border border-dark">${jogos[i][0]}</span>
                    <span class="btn m-1 border border-dark">${jogos[i][1]}</span>
                    <span class="btn m-1 border border-dark">${jogos[i][2]}</span>
                    <span class="btn m-1 border border-dark">${jogos[i][3]}</span>
                    <span class="btn m-1 border border-dark">${jogos[i][4]}</span>
                    <span class="btn m-1 border border-dark">${jogos[i][5]}</span>
                </h5>
            </div>
        `);
        
    }
    
}