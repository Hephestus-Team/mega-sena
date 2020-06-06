$(function(){
    getHistorico();

    $(':button').on('click', function(){
        getConcurso();
    });

});

function getHistorico(numero_concurso = 0){

    let url = '';

    if(numero_concurso == 0)
        url = "http://apiloterias.com.br/app/resultado?loteria=megasena&token=8Pg3go3LxY9BB6k";
    else
        url = `http://apiloterias.com.br/app/resultado?loteria=megasena&token=8Pg3go3LxY9BB6k&concurso=${numero_concurso}`

    $.ajax({
        type: "POST",
        url : url,
        timeout : "2000",  

        success: function(data){
            $('#caixa').append(`
                <div class="row dezUltimos alert alert-light border border-dark">
                    <div class="col-4">
                        <h5>${data.numero_concurso}</h5>
                    </div>

                    <div class="col-4">
                        <h5>
                            <span class="btn m-1 border border-dark">${data.dezenas[0]}</span>
                            <span class="btn m-1 border border-dark">${data.dezenas[1]}</span>
                            <span class="btn m-1 border border-dark">${data.dezenas[2]}</span>
                            <span class="btn m-1 border border-dark">${data.dezenas[3]}</span>
                            <span class="btn m-1 border border-dark">${data.dezenas[4]}</span>
                            <span class="btn m-1 border border-dark">${data.dezenas[5]}</span>
                        </h5>
                    </div>

                    <div class="col-4">
                        <h5>${new Date(data.data_concurso).toLocaleDateString()}</h5>
                    </div>
                </div>
            `);

            if($('#caixa > .dezUltimos').length < 10)
                getHistorico(data.numero_concurso - 1);
        },

        error: function(jqXHR, textStatus, errorThrown ){
            console.log(jqXHR.status);
        }
    });
}

function getConcurso(){

    $('#searchBar').removeClass("is-invalid");

    $.ajax({
        type: "POST",
        url : `http://apiloterias.com.br/app/resultado?loteria=megasena&token=8Pg3go3LxY9BB6k&concurso=${$('#searchBar').val()}`,
        timeout : "2000",  

        success: function(data){

            $('#concursoPesquisado').html(`
                
                    <div class="col-4">
                        <h5>${data.numero_concurso}</h5>
                    </div>

                    <div class="col-4">
                        <p>
                            <span class="m-1 btn border border-dark">${data.dezenas[0]}</span>
                            <span class="m-1 btn border border-dark">${data.dezenas[1]}</span>
                            <span class="m-1 btn border border-dark">${data.dezenas[2]}</span>
                            <span class="m-1 btn border border-dark">${data.dezenas[3]}</span>
                            <span class="m-1 btn border border-dark">${data.dezenas[4]}</span>
                            <span class="m-1 btn border border-dark">${data.dezenas[5]}</span>
                        </p>
                    </div>

                    <div class="col-4 ">
                        <h5>${new Date(data.data_concurso).toLocaleDateString()}</h5>
                    </div>
                
            `).addClass('alert').addClass('alert-light');

        },

        error: function(jqXHR, textStatus, errorThrown ){
            $('#concursoPesquisado').html('').removeClass('alert').removeClass('alert-light');
            $('#searchBar').addClass("is-invalid");
        }
    });
}