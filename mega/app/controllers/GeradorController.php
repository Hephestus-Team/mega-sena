<?php
    include '../../../lib/AutoLoad.php';

    $heuManager = new Heuristicas();
    $array = array();
    $check = true;

    for($x = 0; $x < $_POST['quantidade'];)
    {
        for($i = 0; $i <= 5; $i++)
        {
            $temp = rand(1,60);
            if($i == 0){
                $array[$x][$i] = $temp;
            }elseif(in_array($temp, $array[$x], true) == false){
                $array[$x][$i] = $temp;
            }else{
                $i--;
            }
        }

        sort($array[$x]);

        if($heuManager::check_Aposta($array[$x])){
            $good[$x] = $array[$x];
            $x++;
        }
    }

    print json_encode($good);
