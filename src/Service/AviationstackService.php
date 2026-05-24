<?php

namespace App\Service;


use Symfony\Contracts\HttpClient\HttpClientInterface;

class AviationstackService
{

    private const AIRPORTS = [ // je définis une constante qui associe les noms de villes à leurs codes IATA d'aéroport
        "Paris" => "CDG",
        "Londres" => "LHR",
        "Madrid" => "MAD",
        "Rome" => "FCO",
        "Tokyo" => "HND",
        "New York" => "JFK",
        "Istanbul" => "IST",
        "Marrakech" => "RAK",
    ];
    private HttpClientInterface $httpClient;
    private string $apiKey;

    public function __construct(HttpClientInterface $httpClient, string $apiKey)
    {
        $this->httpClient = $httpClient;
        $this->apiKey = $apiKey;
    }

    public function searchFlight(string $departureCity, string $arrivalCity, string $date): array
    {

        $departureCode = self::AIRPORTS[$departureCity] ?? null; // je récupère le code IATA de l'aéroport de départ à partir du nom de la ville
        $arrivalCode = self::AIRPORTS[$arrivalCity] ?? null; // je récupère le code IATA de l'aéroport d'arrivée à partir du nom de la ville

        if (!$departureCode || !$arrivalCode) { // si l'un des codes IATA n'est pas trouvé, je retourne un tableau vide
            return [];
        }


        $response = $this->httpClient->request('GET', 'http://api.aviationstack.com/v1/flights', [
            'query' => [
                'access_key' => $this->apiKey, 
                'dep_iata' => $departureCode, // c'est le code IATA de l'aéroport de départ
                'arr_iata' => $arrivalCode, // c'est le code IATA de l'aéroport d'arrivée
                'flight_date' => $date,
                'limit'=> 5,
            ],
        ]);

        if ($response->getStatusCode() >= 400) { // si la réponse de l'API indique une erreur, je retourne un tableau vide
            return [];
        }

        $data = $response->toArray(false); // je convertis la réponse JSON de l'API en tableau associatif PHP

        if (empty($data['data'])) { 
            return [];
        }

        $flights = []; 

        foreach ($data['data'] as $flight) { // je parcours les résultats de l'API et je construis un tableau de vols avec les informations que je souhaite afficher
            $flights[] = [
                'compagnie' => $flight['airline']['name'] ?? 'Non renseigné',
                'numeroVol' => $flight['flight']['iata'] ?? 'Non renseigné',
                'aeroportDepart' => $flight['departure']['airport'] ?? 'Non renseigné',
                'codeDepart' => $flight['departure']['iata'] ?? 'Non renseigné',
                'heureDepart' => $flight['departure']['scheduled'] ?? null,
                'aeroportArrivee' => $flight['arrival']['airport'] ?? 'Non renseigné',
                'codeArrivee' => $flight['arrival']['iata'] ?? 'Non renseigné',
                'heureArrivee' => $flight['arrival']['scheduled'] ?? null,
                'statutVol' => $flight['flight_status'] ?? 'Non renseigné',
            ];
        }

        return $flights; 


        
    }
}


