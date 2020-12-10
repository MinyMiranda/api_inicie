<?php

namespace App;

class HomeClass
{

    /**
     * Método que cria o array com as 10 cidades que possuem maior percentual de casos.
     * @param array $responseEnd
     * @return Array
     */
    public function percentageCases(array $responseStart, array $responseEnd)
    {

        // Calculando o percentual de casos em cada cidade
        $result = [];
        foreach ($responseEnd as $key => $end) {

            // Verificando se já chegou ao fim do primeiro array segundo a chave
            $endStart = count($responseStart) <= $key    ? true : false;

            // Caso já tenha chegado ao final start recebe o primeiro valor do array
            $start = !$endStart ? $responseStart[$key] : $responseStart[0];

            // Se os valores não sejam válidos não se realiza cálculo e assume o valor 0
            // Se a cidade existia anteriomente calcula a porcentagem de aumento
            // Senão calcula a porcentagem com base nos casos que surgiram
            if ($end['confirmed'] == 0 || $end['estimated_population'] == 0) {
                $percentage = 0;
            } 
            else if (!$endStart && $start['city'] == $end['city']) {
                $increase = $end['confirmed'] - $start['confirmed'];
                $percentage = ($increase / $end['estimated_population']) * 100;
            } else {
                $percentage = ($end['confirmed'] / $end['estimated_population']) * 100;
            }
            $newData = [
                "percentage" => $percentage,
                "nameCity" => $end['city']
            ];
            array_push($result, $newData);
        }
        // Ordenando o array
        arsort($result);

        // Colocando em um array as dez cidades com maiores percentuais
        $topList = array_slice($result, 0, 10);

        return $topList;
    }
}
