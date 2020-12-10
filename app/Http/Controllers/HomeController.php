<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\HomeClass;

class HomeController extends Controller
{

    private string $api;
    private string $token;

    function __construct()
    {
        $this->api = config("app.api_brasil");
        $this->token = config("app.token_api_brasil");
    }
    /**
     * Envia para Endpoint as 10 cidades com o maior percentual de casos no período 
     * 
     * @param string    $state
     * @param string    $dateStart
     * @param string    $dateEnd
     */
    public function index($state, $dateStart, $dateEnd)
    {
        $responseStart = Http::withHeaders([
            'Authorization' => "Token " . $this->token
        ])->get($this->api . "/dataset/covid19/caso/data/", [
            'state' => $state,
            'date' => $dateStart
        ]);

        $responseEnd = Http::withHeaders([
            'Authorization' => "Token " . $this->token
        ])->get($this->api . "/dataset/covid19/caso/data/", [
            'state' => $state,
            'date' => $dateEnd
        ]);

        // Se ocorrer tudo certo prosseguir para pegar as 10 cidades e enviar para o endpoint
        // Senão retorna erro
        if ($responseEnd->successful()) {

            // Pegando Json do Resultado da pesquisa na API
            $responseStart=$responseStart->json();
            $responseEnd=$responseEnd->json();

            // Pegando o top 10 cidades com maior percentual de casos
            $topList = new HomeClass();
            $percentageCases = $topList->percentageCases($responseStart['results'], $responseEnd['results']);

            // Enviando resultado para API
            foreach ($percentageCases as $key => $value) {
                Http::withHeaders([
                    'MeuNome' => "Ysmine"
                ])->post("https://us-central1-lms-nuvem-mestra.cloudfunctions.net/testApi", [
                    'id' => $key,
                    'nomeCidade' => $value['nameCity'],
                    'percentualDeCasos' => $value['percentage'],
                ]);
            }
        }
        else{
            return $responseEnd;
        }
    }
}
