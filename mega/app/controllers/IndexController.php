<?php
    include '../../../lib/AutoLoad.php';
    $dbManager = new DBManager('mega_sena');

    $menos_caem = $dbManager->DQL(
        ['dezenas.d', 'COUNT(dezenas.d) as contador'],
        ['(SELECT d1 as d from jogos union all
        SELECT d2 as d from jogos union all
        SELECT d3 as d from jogos union all
        SELECT d4 as d from jogos union all
        SELECT d5 as d from jogos union all
        SELECT d6 as d from jogos) as dezenas
        GROUP BY dezenas.d
        ORDER BY contador
        LIMIT 5'],
    );

    $mais_caem = $dbManager->DQL(
        ['dezenas.d', 'COUNT(dezenas.d) as contador'],
        ['(SELECT d1 as d from jogos union all
        SELECT d2 as d from jogos union all
        SELECT d3 as d from jogos union all
        SELECT d4 as d from jogos union all
        SELECT d5 as d from jogos union all
        SELECT d6 as d from jogos) as dezenas
        GROUP BY dezenas.d
        ORDER BY contador DESC
        LIMIT 5'],
    );


    $dados = [
        'mais_caem' => $mais_caem,
        'menos_caem' => $menos_caem
    ];

    echo json_encode($dados);