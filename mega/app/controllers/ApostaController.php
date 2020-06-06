<?php
    include "../../../lib/AutoLoad.php";

    $aposta = json_decode($_POST['dezenas'], true);
    $heuManager = new Heuristicas();

    $heuristicas = $heuManager::heuristica_All($aposta);
    for($i = 0; $i < count($heuristicas); $i++)
    {
        if($heuristicas[$i]['bool'] == 1){
            print '<div class="alert alert-success m-5" role="alert">' .
            $heuristicas[$i]['string'] . implode(' / ', $aposta)
            . '</div>';
        }else{
            print '<div class="alert alert-danger m-5" role="alert">' .
            $heuristicas[$i]['string'] . implode(' / ', $aposta)
            . '</div>';
        }
    }