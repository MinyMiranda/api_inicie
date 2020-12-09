<?php
namespace App;

class HomeClass
{

    /**
     * Método que cria o array com as 10 cidades que possuem maior percentual de casos.
     * @param array $responseEnd
     * @return Array
     */
    public function percentageCases(Array $responseEnd)
    {
        // Calculando o percentual de casos em cada cidade
        $result = [];
        foreach ($responseEnd['results'] as $value) {
            if ($value['confirmed'] == 0 || $value['estimated_population'] == 0) {
                $percentage = $value['confirmed'];
            } else {
                $percentage = ($value['confirmed'] / $value['estimated_population']) * 100;
            }
            $newData = [
                "percentage" => $percentage,
                "nameCity" => $value['city']
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
