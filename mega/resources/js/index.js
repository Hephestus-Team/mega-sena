    $(function(){
        getPage();
        getOcorrenciaDados();
    });

    function getOcorrenciaDados(){

        $.ajax({
            type: "POST",
            url : "../../app/controllers/IndexController.php",
            timeout : "2000",  
            success: function(data){criarGraficos(data)},
        });

    }


    function criarGraficos(dados_json){

        const dados = JSON.parse(dados_json);

        console.table(dados);

        let ctx_mais = $('#mais_caem');
        let ctx_menos = $('#menos_caem');

        let mais_caem = new Chart(ctx_mais, {
            type: 'bar',
            data: {
                labels: [dados.mais_caem[3][0], dados.mais_caem[1][0], dados.mais_caem[0][0], dados.mais_caem[2][0], dados.mais_caem[4][0]],
                datasets: [{
                    label: 'Vezes sorteado',
                    data: [dados.mais_caem[3][1], dados.mais_caem[1][1], dados.mais_caem[0][1], dados.mais_caem[2][1], dados.mais_caem[4][1]],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Mais Sorteados'
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            suggestedMin: 230,
                            suggestedMax: 250
                        }
                    }]
                }
            }
        });

        let menos_caem = new Chart(ctx_menos, {
            type: 'bar',
            data: {
                labels: [dados.menos_caem[3][0], dados.menos_caem[1][0], dados.menos_caem[0][0], dados.menos_caem[2][0], dados.menos_caem[4][0]],
                datasets: [{
                    label: 'Vezes sorteado',
                    data: [dados.menos_caem[3][1], dados.menos_caem[1][1], dados.menos_caem[0][1], dados.menos_caem[2][1], dados.menos_caem[4][1]],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Menos Sorteados'
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            suggestedMin: 170,
                            suggestedMax: 200
                        }
                    }]
                }
            }
        });

    }

    function getPage(){

        $.ajax({
            type: "POST",
            url : "http://apiloterias.com.br/app/resultado?loteria=megasena&token=8Pg3go3LxY9BB6k",
            timeout : "2000",  
            success: function(data){setInfo(data)},
        });

    }

    function setInfo({data_proximo_concurso, valor_estimado_proximo_concurso, data_proximo_concurso_milliseconds, acumulou , valor_acumulado}){
 
        if(acumulou){

            $('#anuncio > h3').html("Mega-Sena valor acumulado");
            $('#anuncio > p').html(`R$ ${valor_acumulado.toFixed().toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}`);

        }
        else{

            $('#anuncio > h3').html("Data do próximo concurso");
            $('#anuncio > p').html(`${new Date(data_proximo_concurso).toLocaleDateString()}`);

        }

        cronometro(new Date(data_proximo_concurso).getTime());

        $("#valor > p").html("R$ " + valor_estimado_proximo_concurso.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));

    }

    function cronometro(countDownDate){

        let x = setInterval(function() {

            let now = new Date().getTime();
    
            let distance = countDownDate - now;
    
            let days = Math.floor(distance / (1000 * 60 * 60 * 24));
            let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            if(days != 0)
                $("#cronometro").html(`${days}d ${hours}h ${minutes}m ${seconds}s`);
            else
                $("#cronometro").html(`${hours}h ${minutes}m ${seconds}s`);
    
            if (distance < 0) {
                clearInterval(x);
                $("#cronometro").html("Expirou");
            }

        }, 1000);
    }