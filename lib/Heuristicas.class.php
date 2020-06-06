<?php
    include 'AutoLoad.php';
    class Heuristicas
    {
        static $dbManager = null;

        public function __construct()
        {
            self::$dbManager = new DBManager('mega_sena');
        }

        static function check_Aposta(array $aposta)
        {
            $ok = true;
            $check_array = self::heuristica_All($aposta);

            for($i = 0; $i <= 5; $i++){
                if(in_array(0, $check_array[$i], true)){
                    $ok = false;
                }
            }

            if($ok){
                return true;
            }else{
                return false;
            }
        }

        static function heuristica_All(array $aposta) : array
        {            
            $formated_array = [
                0 => self::heuristica_Som($aposta),
                1 => self::heuristica_Amp($aposta),
                2 => self::heuristica_Mul($aposta),
                3 => self::heuristica_Exi($aposta),
                4 => self::heuristica_Seq($aposta),
                5 => self::heuristica_Med($aposta),
            ];
            return $formated_array;
        }

        static function heuristica_Som(array $aposta) : array
        {
            $soma_media = self::$dbManager->DQL(
                ['AVG(d1+d2+d3+d4+d5+d6)'],
                ['jogos']
            );
            $soma_media = $soma_media[0][0];
            $soma_aposta = array_sum($aposta);
            $discrepancia = abs(intval((($soma_aposta*100)/$soma_media)-100));

            if($discrepancia <= 20 && $discrepancia >= -20){
                return [
                    'string' => "A discrepancia do somatorio das dezenas de sua aposta e da media do somatorio de todos os jogos vencedores é de: $discrepancia% ",
                    'bool' => 1
                ];
            }else{
                return [
                    'string' => "A discrepancia do somatorio das dezenas de sua aposta e da media do somatorio de todos os jogos vencedores é de: $discrepancia% ",
                    'bool' => 0
                ];
            }
        }

        static function heuristica_Amp(array $aposta) : array
        {
            $amplitude_media = self::$dbManager->DQL(
                ['avg(amplitude)'],
                ['( select id_concurso, max(d) - min(d) as amplitude from 
                ( select id_concurso, d1 as d from jogos union all select id_concurso, 
                d2 as d from jogos union all select id_concurso, 
                d3 as d from jogos union all select id_concurso, 
                d4 as d from jogos union all select id_concurso, 
                d5 as d from jogos union all select id_concurso, 
                d6 as d from jogos) t 
                group by id_concurso) t2']
            );
            $amplitude_aposta = $aposta[5] - $aposta[0];
            $discrepancia = abs(intval((($amplitude_aposta*100)/$amplitude_media[0][0])-100));

            if($discrepancia <= 20 && $discrepancia >= -20){
                return [
                    'string' => "A discrepancia da amplitude das dezenas de sua aposta e da media da amplitude de todos os jogos vencedores é de: $discrepancia% ",
                    'bool' => 1
                ];
            }else{
                return [
                    'string' => "A discrepancia da amplitude das dezenas de sua aposta e da media da amplitude de todos os jogos vencedores é de: $discrepancia% ",
                    'bool' => 0
                ];
            }
        }

        static function heuristica_Mul(array $aposta) : array
        {
            $pares = self::$dbManager->DQL(
                ['count(id_concurso)'],
                ['jogos'],
                "(d1%2+ d2%2+ d3%2+ d4%2+ d5%2+ d6%2) = 0",
                []
            );
            $impares = self::$dbManager->DQL(
                ['count(id_concurso)'],
                ['jogos'],
                "d1%2 != 0 AND d2%2 != 0 AND d3%2 != 0 AND d4%2 != 0 AND d5%2 != 0 AND d6%2 != 0",
                []
            );

            $e_par = 1;
            $e_impar = 1;
        
            foreach($aposta as $i)
            {
                if($i & 1){
                    $e_par = 0;
                }
            }
            foreach($aposta as $i){
                if(!($i & 1)){
                    $e_impar = 0;
                }
            }
            $new_aposta = implode('/', $aposta);
            $pares = (count($pares)/2178)*100;
            $impares = (count($impares)/2178)*100;
            $misto = (1-($pares+$impares))*100;

            if($e_par){
                if($pares >= 20){
                    return [
                        'string' => "A sua aposta é composta apenas de dezenas pares, jogos neste estilo compõe apenas $pares% do total de jogos ",
                        'bool' => 1
                    ];
                }else{
                    return [
                        'string' => "A sua aposta é composta apenas de dezenas pares, jogos neste estilo compõe apenas $pares% do total de jogos ",
                        'bool' => 0
                    ];
                }
            }elseif($e_impar){
                if($impares >= 20){
                    return [
                        'string' => "A sua aposta é composta apenas de dezenas impares, jogos neste estilo compõe apenas $impares% do total de jogos ",
                        'bool' => 1
                    ];
                }else{
                    return [
                        'string' => "A sua aposta é composta apenas de dezenas impares, jogos neste estilo compõe apenas $impares% do total de jogos ",
                        'bool' => 0
                    ];
                }
            }else{
                if($misto >= 20){
                    return [
                        'string' => "A sua aposta é composta de dezenas impares e pares, jogos neste estilo compõe $misto% do total de jogos ",
                        'bool' => 1
                    ];
                }else{
                    return [
                        'string' => "A sua aposta é composta de dezenas impares e pares, jogos neste estilo compõe $misto% do total de jogos ",
                        'bool' => 0
                    ];
                }
            }
        }

        static function heuristica_Exi(array $aposta) : array
        {
            $query = self::$dbManager->DQL(
                ['id_concurso'],
                ['jogos'],
                "d1 = :d1 AND d2 = :d2 AND d3 = :d3 AND d4 = :d4 AND d5 = :d5 AND d6 = :d6",
                ['d1' => $aposta[0], 'd2' => $aposta[1], 'd3' => $aposta[2], 'd4' => $aposta[3], 'd5' => $aposta[4], 'd6' => $aposta[5]]
            );

            if($query == null){
                return [
                    'string' => "A sua aposta não é igual a nenhuma outra aposta vencedora ",
                    'bool' => 1
                ];
            }else{
                return [
                    'string' => "A sua aposta é igual a outra aposta vencedora ",
                    'bool' => 0
                ];
            }

        }

        static function heuristica_Seq(array $aposta) : array
        {
            $e_sequencia = 0;
            for($i = 0; $i <= (count($aposta)-2); $i++)
            {
                if(($aposta[$i+1] - $aposta[$i]) == 1){
                    $e_sequencia = 1;
                    $sequencia[$i] = $aposta[$i];
                    $sequencia[$i+1] = $aposta[$i+1];
                }
            }
            
            if($e_sequencia){
                $sequencia = implode(' / ', $sequencia);
                return [
                    'string' => "A sua aposta possui dezenas em sequencia -> ($sequencia) ",
                    'bool' => 0
                ];
            }else{
                return [
                    'string' => "A sua aposta não possui dezenas em sequencia ",
                    'bool' => 1
                ];
            }

        }

        static function heuristica_Med(array $aposta) : array
        {
            $mediana_media = self::$dbManager->DQL(
                ['AVG((d3+d4)/2)'],
                ['jogos']
            );
            $mediana_aposta = ($aposta[2] + $aposta[3])/2;
            $discrepancia = abs(intval((($mediana_aposta*100)/$mediana_media[0][0])-100));

            if($discrepancia <= 20 && $discrepancia >= -20){
                return [
                    'string' => "A discrepancia da mediana das dezenas de sua aposta e da media da mediana de todos os jogos vencedores é de: $discrepancia% ",
                    'bool' => 1
                ];
            }else{
                return [
                    'string' => "A discrepancia da mediana das dezenas de sua aposta e da media da mediana de todos os jogos vencedores é de: $discrepancia% ",
                    'bool' => 0
                ];
            }
        }
    }